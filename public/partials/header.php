<?php

$page = $page ?? '';
?>
<!doctype html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= htmlspecialchars($title ?? 'Атмосфера') ?></title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;500;600;700&family=Prata&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="assets/css/fallbacks.css">
    <link rel="stylesheet" href="assets/css/client-php.css">
    <link rel="stylesheet" href="assets/css/cart.css">
    <link rel="stylesheet" href="assets/css/orders.css">
    <link rel="stylesheet" href="assets/css/mobile-nav.css">
    <link rel="stylesheet" href="assets/css/back-to-tasks.css">
    <script src="assets/js/mobile-nav.js" defer></script>
    <script src="assets/js/cart-common.js?v=<?= filemtime(__DIR__ . '/../assets/js/cart-common.js') ?>"></script>
    <?php if (($page ?? '') === 'task-1'): ?>
        <link rel="stylesheet" href="assets/css/task.css">
    <?php endif; ?>
    <?php if (($page ?? '') === 'technical'): ?>
        <link rel="stylesheet" href="assets/css/admin.css">
        <link rel="stylesheet" href="assets/css/technical.css">
    <?php endif; ?>
    <?php if (in_array(($page ?? ''), ['checkout', 'reviews'], true)): ?>
        <link rel="stylesheet" href="assets/css/php-tasks.css">
        <link rel="stylesheet" href="assets/css/php-buttons.css">
    <?php endif; ?>
</head>
<body>
<div class="topline">Бесплатная доставка заказов от 5 000 ₽</div>
<header class="header">
    <a class="logo" href="index.php">АТМОСФЕРА<span>дом в деталях</span></a>
    <button class="nav-toggle" id="nav-toggle" type="button" aria-expanded="false" aria-controls="main-nav" aria-label="Открыть меню"><span></span><span></span><span></span></button>
    <nav class="nav" id="main-nav">
        <a class="<?= $page === 'home' ? 'active' : '' ?>" href="index.php">Главная</a>
        <a class="<?= $page === 'catalog' ? 'active' : '' ?>" href="catalog.php">Каталог</a>
        <a class="<?= $page === 'reviews' ? 'active' : '' ?>" href="guestbook.php">Отзывы</a>
        <a href="index.php#about">О магазине</a>
        <a class="<?= $page === 'technical' ? 'active' : '' ?>" href="admin.php">Задания</a>
    </nav>
    <div class="header-actions">
        <a href="catalog.php" aria-label="Поиск">⌕</a>
        <a href="cart.php" aria-label="Корзина">Корзина <b id="cart-count">0</b></a>
    </div>
</header>
<?php if (($_GET['from'] ?? '') === 'tasks'): ?>
    <a class="back-to-tasks" href="admin.php">← Вернуться к заданиям</a>
<?php endif; ?>
<main>
