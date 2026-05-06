<!DOCTYPE html>
<html lang="en">

<head>
  <script>(function(){var t=localStorage.getItem('lp-theme');if(t)document.documentElement.setAttribute('data-theme',t);}());</script>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="icon" href="../assets/logo.ico" type="image/x-icon">
  <title><?= $title ?? 'Lightpack PHP Web Framework' ?></title>
  <?php if ($description): ?>
    <meta name="description" content="<?= htmlspecialchars($description) ?>">
    <meta property="og:description" content="<?= htmlspecialchars($description) ?>">
  <?php endif; ?>
  <meta property="og:title" content="<?= htmlspecialchars($title ?? 'Lightpack Documentation') ?>">
  <meta property="og:type" content="website">
  <link rel="stylesheet" href="<?= $assetPath ?? '../assets' ?>/styles.css">
  <link rel="stylesheet" href="<?= $assetPath ?? '../assets' ?>/prism.min.css">
  <style>
    @font-face {
      font-family: 'Montserrat';
      src: url('<?= $assetPath ?? '../assets' ?>/fonts/Montserrat-Regular.woff2') format('woff2');
      font-weight: 400;
      font-style: normal;
      font-display: swap;
    }

    @font-face {
      font-family: 'Montserrat';
      src: url('<?= $assetPath ?? '../assets' ?>/fonts/Montserrat-Medium.woff2') format('woff2');
      font-weight: 500;
      font-style: normal;
      font-display: swap;
    }

    /* swup animate */
    html.is-changing .transition-fade {
      transition: opacity 0.25s;
      opacity: 1;
    }

    html.is-animating .transition-fade {
      opacity: 0;
    }

    body {
      font-family: 'Montserrat', -apple-system, BlinkMacSystemFont, sans-serif;
    }

    hr {
      height: 1px;
      border: none;
      background: #eee;
    }
  </style>
</head>

<body>
<button class="hamburger" id="hamburger" aria-label="Toggle navigation" title="Open navigation">
  <span></span>
  <span></span>
  <span></span>
</button>
<div class="sidebar-overlay" id="sidebar-overlay"></div>
<button class="theme-toggle" id="theme-toggle" aria-label="Toggle dark/light mode" title="Toggle dark/light mode">
  <svg class="icon-sun" xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="5"/><line x1="12" y1="1" x2="12" y2="3"/><line x1="12" y1="21" x2="12" y2="23"/><line x1="4.22" y1="4.22" x2="5.64" y2="5.64"/><line x1="18.36" y1="18.36" x2="19.78" y2="19.78"/><line x1="1" y1="12" x2="3" y2="12"/><line x1="21" y1="12" x2="23" y2="12"/><line x1="4.22" y1="19.78" x2="5.64" y2="18.36"/><line x1="18.36" y1="5.64" x2="19.78" y2="4.22"/></svg>
  <svg class="icon-moon" xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 12.79A9 9 0 1 1 11.21 3 7 7 0 0 0 21 12.79z"/></svg>
</button>