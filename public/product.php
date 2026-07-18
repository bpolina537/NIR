<?php
require 'lib/store-products.php';
$products = storeProducts();
$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT) ?: 1;
$product = $products[$id] ?? null;
if ($product === null) { http_response_code(404); $product = $products[1]; }
$title = $product['name'] . ' — Атмосфера';
$page = 'product';
require 'partials/header.php';
?>
<div class="breadcrumbs"><a href="index.php">Главная</a> / <a href="catalog.php">Каталог</a> / <?= htmlspecialchars($product['name']) ?></div>
<section class="product-detail">
    <div class="gallery"><img class="gallery-main" src="<?= htmlspecialchars($product['image']) ?>" alt="<?= htmlspecialchars($product['name']) ?>"><div><img src="<?= htmlspecialchars($product['image']) ?>" alt="Товар крупным планом"><img src="<?= htmlspecialchars($product['image']) ?>" alt="Товар в интерьере"></div></div>
    <div class="product-info">
        <span class="eyebrow"><?= htmlspecialchars($product['category']) ?></span>
        <h1><?= htmlspecialchars($product['name']) ?></h1>
        <p class="sku">Артикул AT-<?= 1000 + $product['id'] ?> · В наличии: <?= $product['stock'] ?> шт. · Вес: <?= $product['weight'] ?> кг</p>
        <div class="price"><?= number_format($product['price'], 0, ',', ' ') ?> ₽</div>
        <p class="lead"><?= htmlspecialchars($product['description']) ?></p>
        <div class="options"><b>Цвет</b><div><button class="swatch sand" aria-label="Песочный"></button><button class="swatch milk" aria-label="Молочный"></button><button class="swatch green" aria-label="Зелёный"></button></div></div>
        <div class="buy-row"><input id="product-quantity" type="number" min="1" max="<?= $product['stock'] ?>" value="1"><button class="button add-to-cart" type="button" data-product-id="<?= $product['id'] ?>" data-quantity-input="product-quantity">Добавить в корзину</button></div>
        <details open><summary>Описание</summary><p><?= htmlspecialchars($product['description']) ?></p></details>
        <details><summary>Доставка и возврат</summary><p>Доставка по России. Возврат товара возможен в течение 14 дней.</p></details>
    </div>
</section>
<?php require 'partials/footer.php'; ?>
