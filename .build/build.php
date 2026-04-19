<?php

declare(strict_types=1);

require __DIR__ . '/vendor/autoload.php';

const CONTENT_DIR = __DIR__ . '/content';
const DIST_DIR = __DIR__ . '/../docs';
const NAV_FILE = __DIR__ . '/navigation.json';

function getPageMetadata(array $sidebar, string $filename): array
{
    foreach ($sidebar as $section) {
        foreach ($section['pages'] as $page) {
            if ($page['file'] === $filename) {
                return [
                    'title' => $page['title'],
                    'description' => $page['description'] ?? null
                ];
            }
        }
    }
    return [
        'title' => ucfirst(str_replace('-', ' ', $filename)),
        'description' => null
    ];
}

function ensureDir(string $path): void
{
    if (!is_dir($path)) {
        mkdir($path, 0777, true);
    }
}

function copyDirectory(string $src, string $dest): void
{
    ensureDir($dest);
    
    $iterator = new RecursiveIteratorIterator(
        new RecursiveDirectoryIterator($src, RecursiveDirectoryIterator::SKIP_DOTS),
        RecursiveIteratorIterator::SELF_FIRST
    );
    
    foreach ($iterator as $item) {
        $destPath = $dest . DIRECTORY_SEPARATOR . $iterator->getSubPathName();
        if ($item->isDir()) {
            ensureDir($destPath);
        } else {
            copy($item->getPathname(), $destPath);
        }
    }
}

if (!is_dir(CONTENT_DIR)) {
    die("Error: content/ directory not found.\nRun: git clone https://github.com/lightpack/docs content\n");
}

if (!file_exists(NAV_FILE)) {
    die("Error: navigation.json not found.\n");
}

ensureDir(DIST_DIR);

$parsedown = new Parsedown();
$navConfig = json_decode(file_get_contents(NAV_FILE), true);

if (!$navConfig || !isset($navConfig['sections'])) {
    die("Error: Invalid navigation.json format.\n");
}

$sidebar = [];
foreach ($navConfig['sections'] as $section) {
    $pages = [];
    foreach ($section['pages'] as $pageConfig) {
        $pages[] = [
            'title' => $pageConfig['title'],
            'href' => $pageConfig['file'] . '/',
            'file' => $pageConfig['file']
        ];
    }
    $sidebar[] = [
        'section' => $section['section'],
        'pages' => $pages
    ];
}

$readmePath = CONTENT_DIR . '/README.md';
if (file_exists($readmePath)) {
    $readmeContent = file_get_contents($readmePath);
    
    ob_start();
    extract([
        'title' => 'Lightpack Documentation',
        'description' => 'A modern PHP web framework with extreme performance and small footprint',
        'content' => $parsedown->text($readmeContent),
        'currentPage' => '',
        'sidebar' => $sidebar,
        'assetPath' => '/docs/assets',
        'navPrefix' => '/docs/'
    ]);
    include __DIR__ . '/layouts/index.php';
    file_put_contents(DIST_DIR . '/index.html', ob_get_clean());
}

foreach (glob(CONTENT_DIR . '/*.md') as $mdFile) {
    $basename = basename($mdFile, '.md');
    
    if ($basename === 'README' || $basename === '_sidebar' || $basename === '_navbar' || $basename === '_coverpage') {
        continue;
    }
    
    $mdContent = file_get_contents($mdFile);
    if ($mdContent === false) {
        error_log("Failed to read: $mdFile");
        continue;
    }
    
    $metadata = getPageMetadata($sidebar, $basename);
    
    $pageDir = DIST_DIR . '/' . $basename;
    ensureDir($pageDir);
    
    $layoutFile = __DIR__ . '/layouts/default.php';
    if (!file_exists($layoutFile)) {
        $layoutFile = __DIR__ . '/layouts/default.php';
    }
    
    ob_start();
    extract([
        'title' => $metadata['title'],
        'description' => $metadata['description'],
        'content' => $parsedown->text($mdContent),
        'currentPage' => $basename . '/',
        'sidebar' => $sidebar,
        'assetPath' => '../assets',
        'navPrefix' => '../'
    ]);
    include $layoutFile;
    file_put_contents("$pageDir/index.html", ob_get_clean());
}

if (is_dir(__DIR__ . '/assets')) {
    copyDirectory(__DIR__ . '/assets', DIST_DIR . '/assets');
}

echo "✅ Build complete! Generated docs/ folder with friendly URLs.\n";
echo "   Navigation structure defined in navigation.json\n";
