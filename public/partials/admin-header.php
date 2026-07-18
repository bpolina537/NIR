<!doctype html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= htmlspecialchars($title ?? 'Панель владельца') ?></title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;500;600;700&family=Prata&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="assets/css/admin.css">
    <?php if (($adminPage ?? '') === 'task-1'): ?>
        <link rel="stylesheet" href="assets/css/task.css">
    <?php endif; ?>
</head>
<body class="admin-body">
<header class="admin-topbar">
    <a class="admin-brand" href="admin.php">АТМОСФЕРА <span>управление магазином</span></a>
    <a class="store-link" href="index.php">Перейти в магазин →</a>
</header>
<div class="admin-shell">
    <aside class="admin-sidebar">
        <nav>
            <a class="<?= ($adminPage ?? '') === 'dashboard' ? 'active' : '' ?>" href="admin.php">Обзор</a>
            <a class="<?= ($adminPage ?? '') === 'task-1' ? 'active' : '' ?>" href="js-task-1.php">Настройка элемента</a>
            <span>Товары</span>
            <span>Заказы</span>
            <span>Учётная таблица</span>
            <span>Статистика</span>
            <span>Мониторинг</span>
        </nav>
        <small>Вход владельца будет защищён после подключения PHP и базы данных.</small>
    </aside>
    <main class="admin-content">
