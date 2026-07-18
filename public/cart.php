<?php
require 'lib/store-products.php';
$title = 'Корзина — Атмосфера';
$page = 'cart';
require 'partials/header.php';
?>
<section class="page-hero compact"><span class="eyebrow">Ваш выбор</span><h1>Корзина</h1><p>Отметьте товары, которые хотите оформить сейчас</p></section>
<section class="cart-page">
    <div class="cart-list" id="cart-list"></div>
    <div class="cart-summary" id="cart-summary" hidden><div><span>Выбрано товаров</span><strong id="cart-selected-count">0</strong></div><div><span>Общий вес</span><strong id="cart-selected-weight">0 кг</strong></div><div><span>Сумма</span><strong id="cart-selected-total">0 ₽</strong></div><button class="button" id="cart-checkout" type="button">Перейти к оформлению →</button></div>
    <div class="cart-empty" id="cart-empty" hidden>Корзина пока пуста<br><a class="button" href="catalog.php">Перейти в каталог</a></div>
</section>
<script>window.STORE_PRODUCTS = <?= storeProductsForJs() ?>;</script>
<script src="assets/js/cart.js" defer></script>
<?php require 'partials/footer.php'; ?>
