<?php

$title = 'Таблица товаров — Атмосфера';
$adminPage = 'products';
require 'partials/admin-header.php';
?>
<section class="page-hero compact admin-page-heading">
    <span class="eyebrow">Практикум JavaScript</span>
    <h1>Таблица товаров</h1>
    <p>Получение JSON из внешнего сервиса, вычисление суммы и фильтрация по цене</p>
</section>

<section class="products-tool">
    <form class="products-filter" id="products-filter" novalidate>
        <div>
            <label for="price-from">Цена от</label>
            <input id="price-from" type="number" min="0" step="1" value="0" inputmode="numeric">
        </div>
        <div>
            <label for="price-to">Цена до</label>
            <input id="price-to" type="number" min="0" step="1" value="0" inputmode="numeric">
        </div>
        <button class="admin-primary" type="submit">Применить фильтр</button>
        <button class="filter-reset" id="filter-reset" type="button">Сбросить</button>
    </form>

    <div class="products-notice" id="products-notice" role="status" aria-live="polite">Загрузка данных…</div>
    <div class="products-error" id="products-error" role="alert" aria-live="assertive"></div>

    <div class="products-summary" id="products-summary" hidden>
        <div><span>Позиций</span><strong id="products-count">0</strong></div>
        <div><span>Общая сумма</span><strong id="products-total">0 ₽</strong></div>
    </div>

    <div class="products-table-wrap" id="products-table-wrap" hidden>
        <table class="products-table">
            <thead>
            <tr>
                <th>Наименование</th>
                <th>Цена за единицу</th>
                <th>Количество</th>
                <th>Сумма</th>
            </tr>
            </thead>
            <tbody id="products-body"></tbody>
        </table>
    </div>

    <div class="products-empty" id="products-empty" hidden>Нет данных, попадающих под условие фильтра</div>
</section>

<script src="assets/js/products-table.js" defer></script>
<?php require 'partials/admin-footer.php'; ?>
