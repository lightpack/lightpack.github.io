<!DOCTYPE html>
<html lang="en">

<head>
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