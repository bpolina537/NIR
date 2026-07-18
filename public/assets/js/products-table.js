'use strict';

const filterForm = document.querySelector('#products-filter');
const priceFromInput = document.querySelector('#price-from');
const priceToInput = document.querySelector('#price-to');
const resetButton = document.querySelector('#filter-reset');
const notice = document.querySelector('#products-notice');
const errorBox = document.querySelector('#products-error');
const tableWrap = document.querySelector('#products-table-wrap');
const tableBody = document.querySelector('#products-body');
const emptyMessage = document.querySelector('#products-empty');
const summary = document.querySelector('#products-summary');
const productsCount = document.querySelector('#products-count');
const productsTotal = document.querySelector('#products-total');

let products = [];

const moneyFormatter = new Intl.NumberFormat('ru-RU', {
    style: 'currency',
    currency: 'RUB',
    maximumFractionDigits: 0,
});

function normalizeProducts(data) {
    if (!Array.isArray(data)) {
        throw new Error('Получен некорректный формат данных');
    }

    return data
        .filter((item) => item && typeof item.name === 'string')
        .map((item) => ({
            name: item.name,
            price: Number(item.price),
            quantity: Number(item.quantity),
        }))
        .filter((item) => Number.isFinite(item.price) && Number.isFinite(item.quantity));
}

function createCell(text) {
    const cell = document.createElement('td');
    cell.textContent = text;
    return cell;
}

function renderProducts(items) {
    tableBody.replaceChildren();
    errorBox.textContent = '';

    if (items.length === 0) {
        tableWrap.hidden = true;
        summary.hidden = true;
        emptyMessage.hidden = false;
        return;
    }

    let total = 0;
    const fragment = document.createDocumentFragment();

    items.forEach((product) => {
        const row = document.createElement('tr');
        const amount = product.price * product.quantity;
        total += amount;

        row.append(
            createCell(product.name),
            createCell(moneyFormatter.format(product.price)),
            createCell(String(product.quantity)),
            createCell(moneyFormatter.format(amount)),
        );
        fragment.append(row);
    });

    tableBody.append(fragment);
    productsCount.textContent = String(items.length);
    productsTotal.textContent = moneyFormatter.format(total);
    emptyMessage.hidden = true;
    tableWrap.hidden = false;
    summary.hidden = false;
}

function readPrice(input, label) {
    const value = input.value.trim();

    if (value === '') return 0;

    const number = Number(value);
    if (!Number.isFinite(number) || number < 0) {
        throw new Error(`${label} должна быть неотрицательным числом`);
    }
    return number;
}

function applyFilter() {
    try {
        const from = readPrice(priceFromInput, 'Цена «от»');
        const to = readPrice(priceToInput, 'Цена «до»');

        if (to !== 0 && from > to) {
            throw new Error('Цена «от» не может быть больше цены «до»');
        }

        const filtered = products.filter((product) => {
            const matchesFrom = from === 0 || product.price >= from;
            const matchesTo = to === 0 || product.price <= to;
            return matchesFrom && matchesTo;
        });

        renderProducts(filtered);
    } catch (error) {
        errorBox.textContent = error.message;
    }
}

async function loadProducts() {
    notice.textContent = 'Загрузка данных из внешнего сервиса…';

    try {
        const response = await fetch('api/products-proxy.php', { headers: { Accept: 'application/json' } });
        const data = await response.json();

        if (!response.ok) {
            throw new Error(data.error || 'Не удалось получить данные');
        }

        products = normalizeProducts(data);
        notice.textContent = 'Данные получены из внешнего сервиса';
    } catch (serviceError) {
        const fallbackResponse = await fetch('assets/data/products-fallback.json');
        products = normalizeProducts(await fallbackResponse.json());
        notice.textContent = `${serviceError.message}. Показаны демонстрационные данные из задания.`;
        notice.classList.add('warning');
    }

    renderProducts(products);
}

filterForm.addEventListener('submit', (event) => {
    event.preventDefault();
    applyFilter();
});

resetButton.addEventListener('click', () => {
    priceFromInput.value = '0';
    priceToInput.value = '0';
    renderProducts(products);
});

loadProducts().catch((error) => {
    notice.textContent = '';
    errorBox.textContent = `Ошибка загрузки данных: ${error.message}`;
});
