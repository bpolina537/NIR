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
        <span>JavaScript · Задание 01</span>
        <h2>Настройка элемента</h2>
        <p>Изменение ширины, высоты и цвета демонстрационного блока.</p>
        <b>Готово к проверке →</b>
    </a>
    <a class="admin-card ready" href="checkout.php?from=tasks">
        <span>JavaScript · Задание 02</span>
        <h2>Оформление заказа</h2>
        <p>Проверка формы, интерактивная карта, координаты доставки и сообщение об успешном оформлении.</p>
        <b>Перейти к оформлению →</b>
    </a>
    <a class="admin-card ready" href="products-table.php">
        <span>JavaScript · Задание 03</span>
        <h2>Таблица товаров</h2>
        <p>Загрузка JSON, расчёт суммы и фильтрация товаров по цене.</p>
        <b>Открыть таблицу →</b>
    </a>
    <a class="admin-card ready" href="cpu-monitor.php">
        <span>JavaScript · Задание 04</span>
        <h2>Загрузка процессора</h2>
        <p>Динамический график с обновлением данных каждые пять секунд.</p>
        <b>Открыть мониторинг →</b>
    </a>
    <a class="admin-card ready" href="spreadsheet.php">
        <span>JavaScript · Задание 05</span>
        <h2>Учётная таблица</h2>
        <p>Редактирование ячеек и сохранение данных в локальном хранилище.</p>
        <b>Открыть таблицу →</b>
    </a>
    <a class="admin-card ready" href="index.php?from=tasks#site-counter">
        <span>PHP / SQL · Задание 01</span>
        <h2>Счётчик посещений</h2>
        <p>Хранение количества загрузок в MariaDB и вывод текущего времени по Москве.</p>
        <b>Показать на сайте →</b>
    </a>
    <a class="admin-card ready" href="guestbook.php?from=tasks">
        <span>PHP / SQL · Задание 02</span>
        <h2>Гостевая книга</h2>
        <p>Клиентская страница отзывов с автоматической датой и хранением сообщений в MariaDB.</p>
        <b>Перейти к отзывам →</b>
    </a>
    <a class="admin-card ready" href="checkout.php?from=tasks#delivery-calculator">
        <span>PHP / SQL · Задание 03</span>
        <h2>Калькулятор доставки</h2>
        <p>AJAX-расчёт стоимости и срока, кэш городов, вес корзины и связь с картой.</p>
        <b>Открыть калькулятор →</b>
    </a>
    <a class="admin-card ready" href="rest-api.php">
        <span>PHP / SQL · Задание 04</span>
        <h2>REST API товаров</h2>
        <p>Получение, создание, изменение и удаление товаров в формате JSON.</p>
        <b>Открыть задание →</b>
    </a>
</section>
<?php require 'partials/footer.php'; ?>
