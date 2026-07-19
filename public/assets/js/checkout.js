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
const mapElement = document.querySelector('#map');
const mapHint = document.querySelector('#map-hint');
const deliveryCitySelect = document.querySelector('#delivery-city');
let deliveryPrice = null;
let deliveryTerm = '—';
let deliveryMap = null;
let placemark = null;
let citySelectedFromMap = false;
let selectedMapCity = 'Москва';
const cityCoordinates = {
    'Волгоград': [48.7080, 44.5133], 'Воронеж': [51.6608, 39.2003],
    'Екатеринбург': [56.8389, 60.6057], 'Казань': [55.7961, 49.1064],
    'Калуга': [54.5138, 36.2612], 'Красноярск': [56.0153, 92.8932],
    'Москва': [55.7558, 37.6176], 'Нижний Новгород': [56.3269, 44.0059],
    'Новосибирск': [55.0084, 82.9357], 'Омск': [54.9885, 73.3242],
    'Орел': [52.9704, 36.0638], 'Пермь': [58.0105, 56.2502],
    'Ростов-на-Дону': [47.2357, 39.7015], 'Самара': [53.1959, 50.1002],
    'Санкт-Петербург': [59.9343, 30.3351], 'Тула': [54.1931, 37.6173],
    'Уфа': [54.7388, 55.9721], 'Челябинск': [55.1644, 61.4368],
};

function resetDeliveryCalculation() {
    deliveryPrice = null;
    deliveryTerm = '—';
    updateTotal();
}

function centerMapOnCity(city) {
    if (!deliveryMap || !city) return;
    const coordinates = cityCoordinates[city];
    if (!coordinates) return;
    deliveryMap.setCenter(coordinates, 11, { duration: 350 });
    if (placemark) deliveryMap.geoObjects.remove(placemark);
    placemark = null;
    document.querySelector('#map-lat').value = '';
    document.querySelector('#map-lng').value = '';
    mapHint.textContent = `Выберите адрес в городе ${city}`;
}

function nearestDeliveryCity(coords) {
    return Object.entries(cityCoordinates).reduce((nearest, entry) => {
        const distance = Math.hypot(coords[0] - entry[1][0], (coords[1] - entry[1][1]) * 0.65);
        return distance < nearest.distance ? { city: entry[0], distance } : nearest;
    }, { city: 'Москва', distance: Number.POSITIVE_INFINITY }).city;
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

if (typeof ymaps !== 'undefined') {
    ymaps.ready(() => {
        deliveryMap = new ymaps.Map('map', { center: [55.751574, 37.573856], zoom: 10, controls: ['zoomControl'] });
        centerMapOnCity(deliveryCitySelect.value || 'Москва');
        deliveryMap.events.add('click', (event) => {
            const coords = event.get('coords');
            const detectedCity = nearestDeliveryCity(coords);
            selectedMapCity = detectedCity;
            deliveryCitySelect.value = detectedCity;
            citySelectedFromMap = true;
            deliveryCitySelect.dispatchEvent(new Event('change'));
            if (placemark) deliveryMap.geoObjects.remove(placemark);
            const coordinateText = `${coords[0].toFixed(5)}, ${coords[1].toFixed(5)}`;
            placemark = new ymaps.Placemark(coords, { balloonContent: `${detectedCity}<br>${coordinateText}` }, { preset: 'islands#redDotIcon' });
            deliveryMap.geoObjects.add(placemark);
            placemark.balloon.open();
            document.querySelector('#map-lat').value = coords[0].toFixed(5);
            document.querySelector('#map-lng').value = coords[1].toFixed(5);
            mapHint.textContent = `${detectedCity}: ${coordinateText}`;
        });
    });
}

deliveryCitySelect.addEventListener('change', () => {
    if (deliveryCitySelect.value) selectedMapCity = deliveryCitySelect.value;
    resetDeliveryCalculation();
    if (citySelectedFromMap) {
        citySelectedFromMap = false;
        return;
    }
    centerMapOnCity(deliveryCitySelect.value);
});

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
        city: deliveryCitySelect.value || selectedMapCity,
        coordinates: `${document.querySelector('#map-lat').value}, ${document.querySelector('#map-lng').value}`,
        deliveryPrice: deliveryPrice || 0,
        deliveryTerm: pickup ? 'Сегодня' : deliveryTerm,
        total: productsTotal + (deliveryPrice || 0),
    });
    const orderedIds = new Set(orderedItems.map((item) => item.id));
    AtmosferaCart.saveCart(AtmosferaCart.getCart().filter((item) => !orderedIds.has(item.id)));
    submitButton.textContent = '✔ Заказ оформлен!';
    submitButton.style.background = '#52685d';
    submitButton.disabled = true;
});
