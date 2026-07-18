<?php $title='Оформление заказа'; $page='checkout'; require 'partials/header.php'; ?>
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
    <div id="form-success" style="display:none"></div>
</form>
<aside class="order">
    <h2>Ваш заказ</h2>
    <div class="order-item"><img src="https://images.unsplash.com/photo-1618220179428-22790b461013?auto=format&fit=crop&w=200&q=80" alt="Vase"><div><b>Ваза «Линия»</b><small>Песочный · 1 шт.</small><p>2 490 ₽</p></div></div>
    <div class="order-item"><img src="https://images.unsplash.com/photo-1583845112203-454c2254edcf?auto=format&fit=crop&w=200&q=80" alt="Pled"><div><b>Плед «Тёплый песок»</b><small>Бежевый · 1 шт.</small><p>3 690 ₽</p></div></div>
    <dl>
        <div><dt>Товары</dt><dd>6 180 ₽</dd></div>
        <div><dt>Доставка</dt><dd>Бесплатно</dd></div>
        <div class="total"><dt>Итого</dt><dd>6 180 ₽</dd></div>
    </dl>
    <button class="button full" type="button" id="submit-order">Оформить заказ</button>
    <small>Нажимая кнопку вы соглашаетесь.</small>
</aside>
</section>
<script src="https://api-maps.yandex.ru/2.1/?apikey=ec6d1b31-d0d2-43af-9686-e448f71e1ad9&lang=ru_RU"></script>
<script src="assets/js/checkout.js"></script>
<?php require 'partials/footer.php'; ?>
