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
</head>
<body>
<div class="topline">Бесплатная доставка заказов от 5 000 ₽</div>
<header class="header">
    <a class="logo" href="index.php">АТМОСФЕРА<span>дом в деталях</span></a>
    <nav class="nav">
        <a class="<?= $page === 'home' ? 'active' : '' ?>" href="index.php">Главная</a>
        <a class="<?= $page === 'catalog' ? 'active' : '' ?>" href="catalog.php">Каталог</a>
        <a href="index.php#about">О магазине</a>
        <a href="#contacts">Контакты</a>
    </nav>
    <div class="header-actions">
        <a href="catalog.php" aria-label="Поиск">⌕</a>
        <a href="checkout.php" aria-label="Корзина">Корзина <b>2</b></a>
    </div>
</header>
<main>
