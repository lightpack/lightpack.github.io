<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'Lightpack PHP Web Framework' ?></title>
    <?php if (isset($description) && $description): ?>
    <meta name="description" content="<?= htmlspecialchars($description) ?>">
    <meta property="og:description" content="<?= htmlspecialchars($description) ?>">
    <?php endif; ?>
    <meta property="og:title" content="<?= htmlspecialchars($title ?? 'Lightpack Documentation') ?>">
    <meta property="og:type" content="website">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="<?= $assetPath ?? '../assets' ?>/styles.css">
    <style>
      body {
        font-family: 'Montserrat', Verdana, system-ui, sans-serif;
      }
    </style>
</head>
<body>
