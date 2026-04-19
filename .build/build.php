<?php

declare(strict_types=1);

require __DIR__ . '/vendor/autoload.php';

const CONTENT_DIR = __DIR__ . '/content';
const DIST_DIR = __DIR__ . '/../docs';
const DEFAULT_SECTION = 'General';
const DEFAULT_ORDER = 1000;

function parseFrontmatter(string $content): array
{
    if (preg_match('/^```json\s*\n(.*?)\n```\s*\n---\s*\n/s', $content, $matches, PREG_OFFSET_CAPTURE)) {
        $frontmatter = json_decode($matches[1][0], true);
        if (!is_array($frontmatter)) {
            return ['frontmatter' => [], 'contentStart' => 0];
        }
        return [
            'frontmatter' => $frontmatter,
            'contentStart' => $matches[0][1] + strlen($matches[0][0])
        ];
    }
    return ['frontmatter' => [], 'contentStart' => 0];
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

ensureDir(DIST_DIR);

$parsedown = new Parsedown();
$sections = [];

foreach (glob(CONTENT_DIR . '/*.md') as $mdFile) {
    $mdContent = file_get_contents($mdFile);
    if ($mdContent === false) {
        error_log("Failed to read: $mdFile");
        continue;
    }
    
    $parsed = parseFrontmatter($mdContent);
    $frontmatter = $parsed['frontmatter'];
    $mdBody = $parsed['contentStart'] ? substr($mdContent, $parsed['contentStart']) : $mdContent;
    
    $basename = basename($mdFile, '.md');
    $title = $frontmatter['title'] ?? ucfirst(str_replace('-', ' ', $basename));
    $section = $frontmatter['section'] ?? DEFAULT_SECTION;
    $order = $frontmatter['order'] ?? DEFAULT_ORDER;
    
    $sections[$section][] = [
        'title' => $title,
        'href' => $basename . '/',
        'order' => $order,
        'basename' => $basename,
        'content' => $parsedown->text($mdBody),
        'frontmatter' => $frontmatter
    ];
}

ksort($sections);
foreach ($sections as &$pages) {
    usort($pages, fn($a, $b) => $a['order'] <=> $b['order'] ?: strcmp($a['title'], $b['title']));
}
unset($pages);

$sidebar = array_map(
    fn($section, $pages) => ['section' => $section, 'pages' => $pages],
    array_keys($sections),
    $sections
);

foreach ($sections as $sectionPages) {
    foreach ($sectionPages as $page) {
        $pageDir = DIST_DIR . '/' . $page['basename'];
        ensureDir($pageDir);
        
        $layoutFile = __DIR__ . '/layouts/' . ($page['frontmatter']['layout'] ?? 'default') . '.php';
        if (!file_exists($layoutFile)) {
            $layoutFile = __DIR__ . '/layouts/default.php';
        }
        
        ob_start();
        extract([
            'title' => $page['title'],
            'content' => $page['content'],
            'currentPage' => $page['basename'] . '/',
            'sidebar' => $sidebar
        ]);
        include $layoutFile;
        $html = ob_get_clean();
        
        file_put_contents("$pageDir/index.html", $html);
    }
}

if (is_dir(__DIR__ . '/assets')) {
    copyDirectory(__DIR__ . '/assets', DIST_DIR . '/assets');
}

echo "✅ Build complete! Generated docs/ folder with friendly URLs.\n";
