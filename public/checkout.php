<?php require 'lib/store-products.php'; $deliveryCities=['Волгоград','Воронеж','Екатеринбург','Казань','Калуга','Красноярск','Москва','Нижний Новгород','Новосибирск','Омск','Орел','Пермь','Ростов-на-Дону','Самара','Санкт-Петербург','Тула','Уфа','Челябинск']; $title='Оформление заказа'; $page='checkout'; require 'partials/header.php'; ?>
<section class="page-hero compact"><span class="eyebrow">Последний шаг</span><h1>Оформление заказа</h1></section>
<section class="checkout">
<form class="checkout-form" id="checkout-form" novalidate>
    <div class="form-section"><span>01</span><div>
        <h2>Контактные данные</h2>
        <div class="form-grid">
            <label>ФИО<input type="text" id="fio" placeholder="Иванова Анна Сергеевна"></label>
            <label>Телефон<input type="tel" id="phone" inputmode="numeric" placeholder="79990000000"></label>
            <label class="wide">Email<input type="email" id="email" placeholder="example@mail.ru"></label>
        </div>
    </div></div>
    <div class="form-section"><span>02</span><div>
        <h2>Доставка</h2>
        <div class="delivery-choice">
            <label><input type="radio" name="delivery" value="courier" checked><b>Курьером</b><small>от 350 ₽ · 2-4 дня</small></label>
            <label><input type="radio" name="delivery" value="pickup"><b>Самовывоз</b><small>Бесплатно · сегодня</small></label>
        </div>
        <div class="delivery-calculator" id="delivery-calculator">
            <h3>Рассчитать стоимость доставки</h3>
            <div class="delivery-fields">
                <label>Город
                    <select id="delivery-city" required>
                        <?php foreach ($deliveryCities as $city): ?><option value="<?= htmlspecialchars($city) ?>" <?= $city === 'Москва' ? 'selected' : '' ?>><?= htmlspecialchars($city) ?></option><?php endforeach; ?>
                    </select>
                </label>
                <label>Вес заказа, кг (подставлен из корзины)
                    <input id="delivery-weight" type="number" min="1" step="1" value="1" required>
                </label>
            </div>
            <button class="button" id="calculate-delivery" type="button">Рассчитать</button>
            <div class="cache-info" id="cities-cache-info"></div>
            <div class="form-result" id="delivery-result" role="status" aria-live="polite"></div>
        </div>
        <div id="map" style="width:100%;height:300px;margin-top:18px;background:#f4f0e8"></div>
        <input type="hidden" id="map-lat" name="lat">
        <input type="hidden" id="map-lng" name="lng">
        <p class="task-hint" id="map-hint" style="margin-top:10px;font-size:12px">Нажмите на карте для отметки адреса</p>
    </div></div>
    <div class="form-section"><span>03</span><div>
        <h2>Комментарий</h2>
        <textarea id="comment" maxlength="500" placeholder="Комментарий к заказу"></textarea>
        <p class="task-hint" style="margin-top:6px;font-size:11px"><span id="comment-count">0</span> / 500 символов</p>
    </div></div>
    <div id="form-errors" style="color:#bd4040;font-size:13px;margin-bottom:20px"></div>
</form>
<aside class="order">
    <h2>Ваш заказ</h2>
    <div id="checkout-items"></div>
    <div class="cart-empty" id="checkout-empty" hidden>Нет выбранных товаров<br><a href="cart.php">Вернуться в корзину</a></div>
    <dl>
        <div><dt>Товары</dt><dd id="checkout-products-total">0 ₽</dd></div>
        <div><dt>Доставка</dt><dd id="checkout-delivery-price">Не рассчитана</dd></div>
        <div><dt>Срок доставки</dt><dd id="checkout-delivery-term">—</dd></div>
        <div class="total"><dt>Итого</dt><dd id="checkout-total">0 ₽</dd></div>
    </dl>
    <button class="button full" type="button" id="submit-order">Оформить заказ</button>
    <div id="form-success" class="order-success" role="status" aria-live="polite" hidden></div>
    <small>Нажимая кнопку вы соглашаетесь.</small>
</aside>
</section>
<script src="https://api-maps.yandex.ru/2.1/?apikey=ec6d1b31-d0d2-43af-9686-e448f71e1ad9&lang=ru_RU"></script>
<script>window.STORE_PRODUCTS = <?= storeProductsForJs() ?>;</script>
<script src="assets/js/checkout.js?v=<?= filemtime(__DIR__ . '/assets/js/checkout.js') ?>"></script>
<script src="assets/js/checkout-map.js?v=<?= filemtime(__DIR__ . '/assets/js/checkout-map.js') ?>"></script>
<script src="assets/js/delivery.js?v=<?= filemtime(__DIR__ . '/assets/js/delivery.js') ?>"></script>
<?php require 'partials/footer.php'; ?>
