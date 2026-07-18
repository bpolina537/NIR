<?php

$title = 'Панель владельца — Атмосфера';
$adminPage = 'dashboard';
require 'partials/admin-header.php';
?>
<section class="admin-welcome">
    <div>
        <span class="admin-kicker">Панель управления</span>
        <h1>Добро пожаловать</h1>
        <p>Здесь будут собраны инструменты владельца магазина: товары, заказы, учёт и статистика.</p>
    </div>
    <a class="admin-primary" href="js-task-1.php">Открыть задание № 1 →</a>
</section>

<section class="admin-cards">
    <a class="admin-card ready" href="js-task-1.php">
        <span>JavaScript 01</span>
        <h2>Настройка элемента</h2>
        <p>Изменение ширины, высоты и цвета демонстрационного блока.</p>
        <b>Готово к проверке →</b>
    </a>
    <article class="admin-card">
        <span>Магазин</span>
        <h2>Товары и остатки</h2>
        <p>Раздел появится после выполнения заданий с JSON и REST API.</p>
        <b>Запланировано</b>
    </article>
    <article class="admin-card">
        <span>Аналитика</span>
        <h2>Статистика</h2>
        <p>Посещения и мониторинг сервера будут добавлены на следующих этапах.</p>
        <b>Запланировано</b>
    </article>
</section>
<?php require 'partials/admin-footer.php'; ?>
