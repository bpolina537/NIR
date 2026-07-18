<?php
require 'lib/store-products.php';
$products = storeProducts();
$title = 'Каталог — Атмосфера';
$page = 'catalog';
require 'partials/header.php';
?>
<section class="page-hero"><span class="eyebrow">Коллекция для дома</span><h1>Каталог</h1><p>Ровно десять товаров учебного интернет-магазина.</p></section>
<section class="catalog-layout">
    <aside class="filters"><h3>Категории</h3><a class="active" href="#">Все товары <span>10</span></a><a href="#">Декор <span>3</span></a><a href="#">Текстиль <span>2</span></a><a href="#">Освещение <span>1</span></a><a href="#">Мебель <span>2</span></a></aside>
    <div>
        <div class="catalog-toolbar"><p>Найдено: 10 товаров</p><select id="catalog-sort"><option value="default">Сначала популярные</option><option value="cheap">Сначала дешевле</option><option value="expensive">Сначала дороже</option></select></div>
        <div class="product-grid catalog-grid" id="catalog-grid">
            <?php foreach ($products as $product): ?>
                <article class="product-card" data-price="<?= $product['price'] ?>">
                    <a href="product.php?id=<?= $product['id'] ?>"><div class="product-image"><img src="<?= htmlspecialchars($product['image']) ?>" alt="<?= htmlspecialchars($product['name']) ?>"><?php if ($product['id'] === 1): ?><em>Новинка</em><?php endif; ?></div><small><?= htmlspecialchars($product['category']) ?> · <?= $product['weight'] ?> кг</small><h3><?= htmlspecialchars($product['name']) ?></h3><p><?= number_format($product['price'], 0, ',', ' ') ?> ₽</p></a>
                    <button class="button add-to-cart" type="button" data-product-id="<?= $product['id'] ?>">В корзину</button>
                </article>
            <?php endforeach; ?>
        </div>
    </div>
</section>
<script src="assets/js/catalog.js" defer></script>
<?php require 'partials/footer.php'; ?>
