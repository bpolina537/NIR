'use strict';

const list = document.querySelector('#cart-list');
const summary = document.querySelector('#cart-summary');
const empty = document.querySelector('#cart-empty');
const selectedCount = document.querySelector('#cart-selected-count');
const selectedTotal = document.querySelector('#cart-selected-total');
const selectedWeight = document.querySelector('#cart-selected-weight');
const checkoutButton = document.querySelector('#cart-checkout');
const productsById = new Map(window.STORE_PRODUCTS.map((product) => [product.id, product]));
const money = new Intl.NumberFormat('ru-RU', { style: 'currency', currency: 'RUB', maximumFractionDigits: 0 });

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
renderCart();
