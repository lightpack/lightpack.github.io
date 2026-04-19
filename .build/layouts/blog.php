<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title><?= $title ?? 'Blog Post' ?></title>
    <link rel="stylesheet" href="assets/styles.css">
    <style>
        .blog-post { max-width: 700px; margin: 3rem auto; background: #fff; padding: 2rem; border-radius: 8px; box-shadow: 0 2px 8px rgba(0,0,0,0.06); }
        .blog-meta { color: #888; font-size: 0.9em; margin-bottom: 1em; }
    </style>
</head>
<body>
    <div class="container">
      <aside class="sidebar">
      <div class="sidebar-logo">
        <svg width="28" height="28" viewBox="0 0 32 32" fill="none" xmlns="http://www.w3.org/2000/svg" style="vertical-align:middle;"><rect width="32" height="32" rx="7" fill="#f44336"/><path d="M10 22L16 10L22 22H10Z" fill="white"/></svg>
        LightPress
      </div>
        <?php if(isset($sidebar)): ?>
          <?php foreach ($sidebar as $group): ?>
            <div class="sidebar-section" style="margin-bottom: 2em;">
              <div class="sidebar-section-title" style="font-weight: bold; margin-bottom: 0.5em; color: #333; font-size: 1.1em;"> <?= htmlspecialchars($group['section']) ?> </div>
              <ul style="list-style: none; padding-left: 0;">
                <?php foreach ($group['pages'] as $page): ?>
                  <li style="margin-bottom: 0.5em;">
                    <a href="<?= htmlspecialchars($page['href']) ?>"
   <?php if (isset($currentPage) && $page['href'] === $currentPage): ?>class="active" aria-current="page"<?php endif; ?>>
  <?= htmlspecialchars($page['title']) ?>
</a>
                  </li>
                <?php endforeach; ?>
              </ul>
            </div>
          <?php endforeach; ?>
        <?php endif; ?>
      </aside>
      <main class="blog-post" style="flex: 1; min-width: 0; padding: 2rem;">

        <div class="blog-meta">
            <?php if(isset($date)) echo date('F j, Y', strtotime($date)); ?>
        </div>
        <?= $content ?>
    </main>
    <nav class="toc"></nav>
</div>
<script src="assets/toc.js"></script>
</body>
</html>
