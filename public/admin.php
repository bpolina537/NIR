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
    <a class="admin-card ready" href="products-table.php">
        <span>Магазин</span>
        <h2>Товары и остатки</h2>
        <p>Загрузка JSON, расчёт суммы и фильтрация товаров по цене.</p>
        <b>Открыть таблицу →</b>
    </a>
    <a class="admin-card ready" href="cpu-monitor.php">
        <span>Аналитика</span>
        <h2>Загрузка процессора</h2>
        <p>Динамический график с обновлением данных каждые пять секунд.</p>
        <b>Открыть мониторинг →</b>
    </a>
    <a class="admin-card ready" href="spreadsheet.php">
        <span>JavaScript 05</span>
        <h2>Учётная таблица</h2>
        <p>Редактирование ячеек и сохранение данных в локальном хранилище.</p>
        <b>Открыть таблицу →</b>
    </a>
</section>
<?php require 'partials/admin-footer.php'; ?>
