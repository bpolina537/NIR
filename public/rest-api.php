<?php

$title = 'REST API товаров — Атмосфера';
$adminPage = 'php-rest';
require 'partials/admin-header.php';
?>
<section class="page-hero compact admin-page-heading">
    <span class="eyebrow">PHP / SQL · Задание 4</span>
    <h1>REST API товаров</h1>
    <p>GET, POST, PUT и DELETE для ресурса /api/products/</p>
</section>
<section class="php-tool api-layout">
    <div>
        <form class="php-form" id="api-product-form">
            <h2 id="api-form-title">Новый товар</h2>
            <input id="api-product-id" type="hidden">
            <label>Товар
                <select id="api-product-select">
                    <option value="">+ Новый товар</option>
                </select>
            </label>
            <label>Что сделать с товаром
                <select id="api-method">
                    <option value="GET">GET — получить данные</option>
                    <option value="POST" selected>POST — добавить новый</option>
                    <option value="PUT">PUT — изменить выбранный</option>
                    <option value="DELETE">DELETE — удалить выбранный</option>
                </select>
            </label>
            <label id="api-name-field">Название нового товара<input id="api-name" type="text" maxlength="180" required></label>
            <label id="api-price-field">Цена<input id="api-price" type="number" min="0" step="0.01" required></label>
            <label id="api-quantity-field">Количество<input id="api-quantity" type="number" min="0" step="1" required></label>
            <button class="admin-primary" id="api-submit" type="submit">Выполнить POST</button>
            <button class="api-reset" id="api-reset" type="button">Новый товар</button>
            <div class="form-result" id="api-message"></div>
        </form>
    </div>
    <div>
        <div class="api-products" id="api-products"></div>
        <pre class="api-output" id="api-output">Загрузка данных…</pre>
    </div>
</section>
<script src="assets/js/rest-api.js?v=<?= filemtime(__DIR__ . '/assets/js/rest-api.js') ?>" defer></script>
<?php require 'partials/admin-footer.php'; ?>
