<!doctype html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= htmlspecialchars($title ?? 'Технические задания') ?></title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;500;600;700&family=Prata&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="assets/css/admin.css">
    <?php if (($adminPage ?? '') === 'task-1'): ?>
        <link rel="stylesheet" href="assets/css/task.css">
    <?php endif; ?>
    <?php if (($adminPage ?? '') === 'products'): ?>
        <link rel="stylesheet" href="assets/css/products-table.css">
    <?php endif; ?>
    <?php if (($adminPage ?? '') === 'cpu'): ?>
        <link rel="stylesheet" href="assets/css/cpu-monitor.css">
    <?php endif; ?>
    <?php if (($adminPage ?? '') === 'spreadsheet'): ?>
        <link rel="stylesheet" href="assets/css/spreadsheet.css">
    <?php endif; ?>
</head>
<body class="admin-body">
<header class="admin-topbar">
    <a class="admin-brand" href="admin.php">АТМОСФЕРА <span>технические задания</span></a>
    <a class="store-link" href="index.php">Перейти в магазин →</a>
</header>
<div class="admin-shell">
    <aside class="admin-sidebar">
        <nav>
            <a class="<?= ($adminPage ?? '') === 'dashboard' ? 'active' : '' ?>" href="admin.php">Все задания</a>
            <a class="<?= ($adminPage ?? '') === 'task-1' ? 'active' : '' ?>" href="js-task-1.php">JS 1 · Элемент</a>
            <a href="checkout.php">JS 2 · Заказ</a>
            <a class="<?= ($adminPage ?? '') === 'products' ? 'active' : '' ?>" href="products-table.php">JS 3 · Товары</a>
            <a class="<?= ($adminPage ?? '') === 'cpu' ? 'active' : '' ?>" href="cpu-monitor.php">JS 4 · График</a>
            <a class="<?= ($adminPage ?? '') === 'spreadsheet' ? 'active' : '' ?>" href="spreadsheet.php">JS 5 · Таблица</a>
            <span>PHP/SQL · следующий этап</span>
        </nav>
        <small>Учебные модули производственной практики. Авторизация для их просмотра не требуется.</small>
    </aside>
    <main class="admin-content">
