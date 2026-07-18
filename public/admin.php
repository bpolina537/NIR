<?php

$title = 'Технические задания — Атмосфера';
$page = 'technical';
require 'partials/header.php';
?>
<section class="page-hero compact">
    <span class="eyebrow">Производственная практика</span>
    <h1>Технические задания</h1>
    <p>Демонстрационные страницы выполненных заданий JavaScript, PHP и SQL</p>
</section>

<section class="admin-cards technical-cards">
    <a class="admin-card ready" href="js-task-1.php">
        <span>JavaScript 01</span>
        <h2>Настройка элемента</h2>
        <p>Изменение ширины, высоты и цвета демонстрационного блока.</p>
        <b>Готово к проверке →</b>
    </a>
    <a class="admin-card ready" href="products-table.php">
        <span>JavaScript 03</span>
        <h2>Таблица товаров</h2>
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
    <a class="admin-card ready" href="rest-api.php">
        <span>PHP / SQL 04</span>
        <h2>REST API товаров</h2>
        <p>Получение, создание, изменение и удаление товаров в формате JSON.</p>
        <b>Открыть задание →</b>
    </a>
</section>
<?php require 'partials/footer.php'; ?>
