<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title><?= $title ?? 'LightPress' ?></title>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="assets/styles.css">
    <style>
      body {
        font-family: 'Montserrat', Verdana, system-ui, sans-serif;
      }
    </style>
</head>
<body>
    <div class="container">
      <aside class="sidebar">
      <div class="sidebar-logo">
        <img src="assets/logo.svg" />
      </div>
        <?php if(isset($sidebar)): ?>
          <?php foreach ($sidebar as $group): ?>
            <div class="sidebar-section">
              <div class="sidebar-section-title"> <?= htmlspecialchars($group['section']) ?> </div>
              <ul>
                <?php foreach ($group['pages'] as $page): ?>
                  <li>
                    <a href="<?= htmlspecialchars(basename($page['href'], '/')) ?>/"
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
      <main>
        <?= $content ?>
    </main>
    <nav class="toc"></nav>
</div>
<script src="assets/toc.js"></script>
</body>
</html>
