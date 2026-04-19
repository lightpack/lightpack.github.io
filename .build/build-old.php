<?php

require __DIR__ . '/vendor/autoload.php';

$parsedown = new Parsedown();

$contentDir = __DIR__ . '/content';
$layoutFile = __DIR__ . '/layouts/default.php';
$distDir = __DIR__ . '/../docs';

if (!is_dir($contentDir)) {
    die("Error: content/ directory not found. Run: git clone https://github.com/lightpack/docs content\n");
}

if (!is_dir($distDir)) {
    mkdir($distDir, 0777, true);
}

$mainPages = [];
$blogPosts = [];
$sections = [];

foreach (glob("$contentDir/*.md") as $mdFile) {
    $mdContent = file_get_contents($mdFile);
    $frontmatter = [];
    $contentStart = 0;
    
    if (preg_match('/^```json\s*\n(.*?)\n```\s*\n---\s*\n/s', $mdContent, $matches, PREG_OFFSET_CAPTURE)) {
        $frontmatter = json_decode($matches[1][0], true) ?: [];
        $contentStart = $matches[0][1] + strlen($matches[0][0]);
    }
    
    $basename = basename($mdFile, '.md');
    $title = $frontmatter['title'] ?? ucfirst(str_replace('-', ' ', $basename));
    $href = $basename . '/';
    $section = $frontmatter['section'] ?? 'General';
    
    $page = [
        'title' => $title,
        'href' => $href,
        'order' => $frontmatter['order'] ?? 1000
    ];
    
    if (($frontmatter['layout'] ?? '') === 'blog') {
        $blogPosts[] = [
            'title' => $title,
            'href' => $href,
            'date' => $frontmatter['date'] ?? '',
            'order' => $frontmatter['order'] ?? 1000
        ];
    } else {
        $sections[$section][] = $page;
        $mainPages[] = $page;
    }
}

ksort($sections);
foreach ($sections as &$pages) {
    usort($pages, function($a, $b) {
        return $a['order'] <=> $b['order'] ?: strcmp($a['title'], $b['title']);
    });
}
unset($pages);

usort($blogPosts, function($a, $b) {
    return strcmp($b['date'], $a['date']);
});

$navigation = $mainPages;
$sidebar = [];
foreach ($sections as $sectionName => $pages) {
    $sidebar[] = [
        'section' => $sectionName,
        'pages' => $pages
    ];
}

if (!empty($blogPosts)) {
    $blogIndexHtml = "<h1>Blog</h1>\n<ul style='list-style:none;padding:0;'>\n";
    foreach ($blogPosts as $post) {
        $date = $post['date'] ? "<span style='color:#888;font-size:0.9em;'>" . date('F j, Y', strtotime($post['date'])) . "</span>" : '';
        $blogIndexHtml .= "<li style='margin-bottom:1.5em;'><a href='{$post['href']}' style='font-size:1.2em;color:#4f8cff;text-decoration:none;font-weight:500'>{$post['title']}</a> $date</li>\n";
    }
    $blogIndexHtml .= "</ul>\n";
    
    $blogDir = "$distDir/blog";
    if (!is_dir($blogDir)) {
        mkdir($blogDir, 0777, true);
    }
    
    ob_start();
    $title = 'Blog';
    $content = $blogIndexHtml;
    $currentPage = 'blog/';
    include __DIR__ . '/layouts/default.php';
    $fullBlogIndex = ob_get_clean();
    file_put_contents("$blogDir/index.html", $fullBlogIndex);
}

foreach (glob("$contentDir/*.md") as $mdFile) {
    $mdContent = file_get_contents($mdFile);
    
    $frontmatter = [];
    $contentStart = 0;
    if (preg_match('/^```json\s*\n(.*?)\n```\s*\n---\s*\n/s', $mdContent, $matches, PREG_OFFSET_CAPTURE)) {
        $frontmatter = json_decode($matches[1][0], true) ?: [];
        $contentStart = $matches[0][1] + strlen($matches[0][0]);
    }
    
    $mdBody = $contentStart ? substr($mdContent, $contentStart) : $mdContent;
    $htmlContent = $parsedown->text($mdBody);
    
    $basename = basename($mdFile, '.md');
    $title = $frontmatter['title'] ?? ucfirst(str_replace('-', ' ', $basename));
    $layout = $frontmatter['layout'] ?? 'default';
    $layoutFile = __DIR__ . "/layouts/{$layout}.php";
    
    if (!file_exists($layoutFile)) {
        $layoutFile = __DIR__ . '/layouts/default.php';
    }
    
    $pageDir = "$distDir/$basename";
    if (!is_dir($pageDir)) {
        mkdir($pageDir, 0777, true);
    }
    
    ob_start();
    $content = $htmlContent;
    extract($frontmatter, EXTR_SKIP);
    $currentPage = "$basename/";
    include $layoutFile;
    $fullHtml = ob_get_clean();
    
    file_put_contents("$pageDir/index.html", $fullHtml);
}

$assetsSrc = __DIR__ . '/assets';
$assetsDest = $distDir . '/assets';
if (is_dir($assetsSrc)) {
    if (!is_dir($assetsDest)) {
        mkdir($assetsDest, 0777, true);
    }
    
    $iterator = new RecursiveIteratorIterator(
        new RecursiveDirectoryIterator($assetsSrc, RecursiveDirectoryIterator::SKIP_DOTS),
        RecursiveIteratorIterator::SELF_FIRST
    );
    
    foreach ($iterator as $item) {
        $destPath = $assetsDest . DIRECTORY_SEPARATOR . $iterator->getSubPathName();
        if ($item->isDir()) {
            if (!is_dir($destPath)) {
                mkdir($destPath, 0777, true);
            }
        } else {
            copy($item, $destPath);
        }
    }
}

echo "✅ Build complete! Generated docs/ folder with friendly URLs.\n";
echo "   Example: docs/ai-service/index.html → /docs/ai-service/\n";
