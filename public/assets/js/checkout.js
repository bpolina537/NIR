'use strict';

const productsById = new Map(window.STORE_PRODUCTS.map((product) => [product.id, product]));
const orderedItems = AtmosferaCart.getCart().filter((item) => item.selected !== false && productsById.has(item.id));
const money = new Intl.NumberFormat('ru-RU', { style: 'currency', currency: 'RUB', maximumFractionDigits: 0 });
const itemsBox = document.querySelector('#checkout-items');
const emptyBox = document.querySelector('#checkout-empty');
const productsTotalBox = document.querySelector('#checkout-products-total');
const deliveryPriceBox = document.querySelector('#checkout-delivery-price');
const deliveryTermBox = document.querySelector('#checkout-delivery-term');
const totalBox = document.querySelector('#checkout-total');
const calculator = document.querySelector('#delivery-calculator');
const deliveryCitySelect = document.querySelector('#delivery-city');
let deliveryPrice = null;
let deliveryTerm = '—';

function resetDeliveryCalculation() {
    deliveryPrice = null;
    deliveryTerm = '—';
    updateTotal();
}

const productsTotal = orderedItems.reduce((sum, item) => sum + productsById.get(item.id).price * item.quantity, 0);
const orderWeight = orderedItems.reduce((sum, item) => sum + productsById.get(item.id).weight * item.quantity, 0);
document.querySelector('#delivery-weight').value = String(Math.max(1, orderWeight));
orderedItems.forEach((item) => {
    const product = productsById.get(item.id);
    const row = document.createElement('div');
    row.className = 'order-item';
    row.innerHTML = `<img src="${product.image}" alt=""><div><b>${product.name}</b><small>${item.quantity} шт.</small><p>${money.format(product.price * item.quantity)}</p></div>`;
    itemsBox.append(row);
});
emptyBox.hidden = orderedItems.length !== 0;
productsTotalBox.textContent = money.format(productsTotal);

function updateTotal() {
    deliveryPriceBox.textContent = deliveryPrice === null ? 'Не рассчитана' : money.format(deliveryPrice);
    deliveryTermBox.textContent = deliveryTerm;
    totalBox.textContent = money.format(productsTotal + (deliveryPrice || 0));
}
updateTotal();

window.addEventListener('delivery-location-change', resetDeliveryCalculation);

window.addEventListener('delivery-calculated', (event) => {
    deliveryPrice = event.detail.price;
    deliveryTerm = event.detail.term;
    updateTotal();
});

document.querySelectorAll('input[name="delivery"]').forEach((radio) => {
    radio.addEventListener('change', () => {
        const pickup = document.querySelector('input[name="delivery"]:checked').value === 'pickup';
        calculator.hidden = pickup;
        deliveryPrice = pickup ? 0 : null;
        deliveryTerm = pickup ? 'Сегодня' : '—';
        updateTotal();
    });
});

const commentElement = document.querySelector('#comment');
commentElement.addEventListener('input', () => { document.querySelector('#comment-count').textContent = String(commentElement.value.length); });

const submitButton = document.querySelector('#submit-order');
const errorsBox = document.querySelector('#form-errors');
const successBox = document.querySelector('#form-success');
submitButton.disabled = orderedItems.length === 0;
submitButton.addEventListener('click', () => {
    const errors = [];
    const pickup = document.querySelector('input[name="delivery"]:checked').value === 'pickup';
    const fio = document.querySelector('#fio').value.trim();
    const phone = document.querySelector('#phone').value.trim();
    const email = document.querySelector('#email').value.trim();
    if (!fio) errors.push('Не заполнено поле ФИО');
    if (!phone) errors.push('Не заполнено поле «Телефон»');
    else if (!/^\d{7,15}$/.test(phone)) errors.push('Телефон должен содержать только 7–15 цифр');
    if (email && !email.includes('@')) errors.push('Неверный Email (отсутствует символ @)');
    if (!pickup && deliveryPrice === null) errors.push('Сначала рассчитайте стоимость доставки');
    if (!document.querySelector('#map-lat').value) errors.push(pickup ? 'Не отмечена точка самовывоза' : 'Не отмечен адрес доставки в выбранном городе');
    errorsBox.replaceChildren(...errors.map((text) => { const paragraph = document.createElement('p'); paragraph.textContent = text; return paragraph; }));
    if (errors.length) return;
    const now = new Date();
    AtmosferaCart.saveOrder({
        id: `ATM-${String(now.getTime()).slice(-8)}`,
        createdAt: now.toISOString(),
        status: 'Оформлен',
        items: orderedItems.map((item) => {
            const product = productsById.get(item.id);
            return { id: item.id, name: product.name, quantity: item.quantity, price: product.price };
        }),
        productsTotal,
        deliveryMethod: pickup ? 'Самовывоз' : 'Курьерская доставка',
        city: deliveryCitySelect.value,
        coordinates: `${document.querySelector('#map-lat').value}, ${document.querySelector('#map-lng').value}`,
        deliveryPrice: deliveryPrice || 0,
        deliveryTerm: pickup ? 'Сегодня' : deliveryTerm,
        total: productsTotal + (deliveryPrice || 0),
    });
    const orderedIds = new Set(orderedItems.map((item) => item.id));
    AtmosferaCart.saveCart(AtmosferaCart.getCart().filter((item) => !orderedIds.has(item.id)));
    submitButton.disabled = true;
    successBox.textContent = 'Заказ оформлен!';
    successBox.hidden = false;
});
