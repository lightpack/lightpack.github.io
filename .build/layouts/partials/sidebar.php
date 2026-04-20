<aside class="sidebar">
  <div class="sidebar-logo">
    <img src="<?= $assetPath ?? '../assets' ?>/logo.svg" alt="Lightpack PHP MVC Web Framework" />
  </div>
  <?php if(isset($sidebar)): ?>
    <?php foreach ($sidebar as $group): ?>
      <div class="sidebar-section">
        <div class="sidebar-section-title" style="cursor: pointer; user-select: none;">
          <span class="collapse-icon">▶</span> <?= htmlspecialchars($group['section']) ?>
        </div>
        <ul class="sidebar-section-content" style="display: none;">
          <?php foreach ($group['pages'] as $page): ?>
            <li>
              <a href="<?= $navPrefix ?? '../' ?><?= htmlspecialchars($page['href']) ?>"
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
