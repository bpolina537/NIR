'use strict';

const list = document.querySelector('#cart-list');
const summary = document.querySelector('#cart-summary');
const empty = document.querySelector('#cart-empty');
const selectedCount = document.querySelector('#cart-selected-count');
const selectedTotal = document.querySelector('#cart-selected-total');
const selectedWeight = document.querySelector('#cart-selected-weight');
const checkoutButton = document.querySelector('#cart-checkout');
const ordersHistory = document.querySelector('#orders-history');
const ordersList = document.querySelector('#orders-list');
const productsById = new Map(window.STORE_PRODUCTS.map((product) => [product.id, product]));
const money = new Intl.NumberFormat('ru-RU', { style: 'currency', currency: 'RUB', maximumFractionDigits: 0 });
const orderCityCoordinates = {'Волгоград':[48.7080,44.5133],'Воронеж':[51.6608,39.2003],'Екатеринбург':[56.8389,60.6057],'Казань':[55.7961,49.1064],'Калуга':[54.5138,36.2612],'Красноярск':[56.0153,92.8932],'Москва':[55.7558,37.6176],'Нижний Новгород':[56.3269,44.0059],'Новосибирск':[55.0084,82.9357],'Омск':[54.9885,73.3242],'Орел':[52.9704,36.0638],'Пермь':[58.0105,56.2502],'Ростов-на-Дону':[47.2357,39.7015],'Самара':[53.1959,50.1002],'Санкт-Петербург':[59.9343,30.3351],'Тула':[54.1931,37.6173],'Уфа':[54.7388,55.9721],'Челябинск':[55.1644,61.4368]};

function orderCity(order) {
    if (order.city) return order.city;
    const coords = String(order.coordinates || '').split(',').map(Number);
    if (coords.length !== 2 || coords.some((value) => !Number.isFinite(value))) return 'Не указан';
    return Object.entries(orderCityCoordinates).reduce((nearest, entry) => {
        const distance = Math.hypot(coords[0] - entry[1][0], (coords[1] - entry[1][1]) * 0.65);
        return distance < nearest.distance ? { city: entry[0], distance } : nearest;
    }, { city: 'Москва', distance: Infinity }).city;
}

function renderCart() {
    const cart = AtmosferaCart.getCart().filter((item) => productsById.has(item.id));
    list.replaceChildren();
    empty.hidden = cart.length !== 0;
    summary.hidden = cart.length === 0;

    cart.forEach((item) => {
        const product = productsById.get(item.id);
        const row = document.createElement('article');
        row.className = 'cart-item';
        row.innerHTML = `<input class="cart-select" type="checkbox" ${item.selected !== false ? 'checked' : ''} aria-label="Выбрать ${product.name}"><img src="${product.image}" alt=""><div><h3>${product.name}</h3><small>${product.category} · ${product.weight} кг/шт.</small></div><strong class="cart-price">${money.format(product.price * item.quantity)}</strong><label class="cart-quantity">Количество<input type="number" min="1" max="${product.stock}" value="${item.quantity}"></label><button class="cart-remove" type="button" aria-label="Удалить">×</button>`;
        row.querySelector('.cart-select').addEventListener('change', (event) => updateItem(item.id, { selected: event.target.checked }));
        row.querySelector('input[type=number]').addEventListener('change', (event) => updateItem(item.id, { quantity: Math.max(1, Math.min(product.stock, Number(event.target.value) || 1)) }));
        row.querySelector('.cart-remove').addEventListener('click', () => removeItem(item.id));
        list.append(row);
    });
    updateSummary(cart);
}

function updateItem(id, changes) {
    const cart = AtmosferaCart.getCart();
    Object.assign(cart.find((item) => item.id === id), changes);
    AtmosferaCart.saveCart(cart);
    renderCart();
}

function removeItem(id) {
    AtmosferaCart.saveCart(AtmosferaCart.getCart().filter((item) => item.id !== id));
    renderCart();
}

function updateSummary(cart) {
    const selected = cart.filter((item) => item.selected !== false);
    selectedCount.textContent = String(selected.reduce((sum, item) => sum + item.quantity, 0));
    selectedWeight.textContent = `${selected.reduce((sum, item) => sum + productsById.get(item.id).weight * item.quantity, 0)} кг`;
    selectedTotal.textContent = money.format(selected.reduce((sum, item) => sum + productsById.get(item.id).price * item.quantity, 0));
    checkoutButton.disabled = selected.length === 0;
}

checkoutButton.addEventListener('click', () => { window.location.href = 'checkout.php'; });

function renderOrders() {
    const orders = AtmosferaCart.getOrders();
    ordersHistory.hidden = orders.length === 0;
    ordersList.replaceChildren();
    orders.forEach((order) => {
        const card = document.createElement('article');
        card.className = 'completed-order';
        const items = order.items.map((item) =>
            `<div class="completed-order-item"><span>${item.name} · ${item.quantity} шт.</span><strong>${money.format(item.price * item.quantity)}</strong></div>`).join('');
        const date = new Date(order.createdAt).toLocaleString('ru-RU', { dateStyle: 'medium', timeStyle: 'short' });
        card.innerHTML = `<header><div><h3>Заказ ${order.id}</h3><small>${date}</small></div><span class="order-status">${order.status}</span></header><div class="completed-order-grid"><div class="completed-order-items">${items}</div><dl class="completed-order-details"><div><dt>Получение</dt><dd>${order.deliveryMethod}</dd></div><div><dt>Город</dt><dd>${orderCity(order)}</dd></div><div><dt>Координаты</dt><dd>${order.coordinates}</dd></div><div><dt>Товары</dt><dd>${money.format(order.productsTotal)}</dd></div><div><dt>Доставка</dt><dd>${money.format(order.deliveryPrice)}</dd></div><div><dt>Срок</dt><dd>${order.deliveryTerm || (order.deliveryMethod === 'Самовывоз' ? 'Сегодня' : '—')}</dd></div><div class="order-grand-total"><dt>Итого</dt><dd>${money.format(order.total)}</dd></div></dl></div>`;
        ordersList.append(card);
    });
}

renderCart();
renderOrders();
