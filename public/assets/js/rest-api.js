'use strict';

const form = document.querySelector('#api-product-form');
const formTitle = document.querySelector('#api-form-title');
const idInput = document.querySelector('#api-product-id');
const productSelect = document.querySelector('#api-product-select');
const nameField = document.querySelector('#api-name-field');
const nameInput = document.querySelector('#api-name');
const priceInput = document.querySelector('#api-price');
const quantityInput = document.querySelector('#api-quantity');
const priceField = document.querySelector('#api-price-field');
const quantityField = document.querySelector('#api-quantity-field');
const methodSelect = document.querySelector('#api-method');
const submitAction = document.querySelector('#api-submit');
const resetButton = document.querySelector('#api-reset');
const productsBox = document.querySelector('#api-products');
const output = document.querySelector('#api-output');
const message = document.querySelector('#api-message');

let products = [];

function showResponse(method, status, data) {
    const content = status === 204
        ? 'Товар успешно удалён. Статус HTTP 204 по стандарту не содержит тела ответа.'
        : JSON.stringify(data, null, 2);
    output.textContent = `${method} · HTTP ${status}\n\n${content}`;
}

function resetForm() {
    form.reset();
    idInput.value = '';
    productSelect.value = '';
    methodSelect.value = 'POST';
    nameField.hidden = false;
    formTitle.textContent = 'Новый товар';
    message.textContent = '';
    nameInput.focus();
    updateMethodUi();
}

function updateMethodUi() {
    const method = methodSelect.value;
    const needsBody = method === 'POST' || method === 'PUT';
    const selectedProduct = products.find((item) => String(item.id) === productSelect.value);
    nameInput.disabled = method !== 'POST';
    priceInput.disabled = !needsBody;
    quantityInput.disabled = !needsBody;
    nameInput.required = method === 'POST';
    priceInput.required = needsBody;
    quantityInput.required = needsBody;
    nameField.hidden = method !== 'POST';
    priceField.hidden = !needsBody;
    quantityField.hidden = !needsBody;
    submitAction.textContent = `Выполнить ${method}`;
    if (selectedProduct) {
        const titles = { GET: 'Выбран товар', PUT: 'Изменение товара', DELETE: 'Удаление товара', POST: 'Новый товар' };
        formTitle.textContent = `${titles[method]} «${selectedProduct.name}»`;
    } else {
        formTitle.textContent = method === 'POST' ? 'Новый товар' : 'Выберите товар';
    }
}

function renderProducts() {
    productsBox.replaceChildren();
    const selectedId = productSelect.value;
    productSelect.replaceChildren(new Option('+ Новый товар', ''));
    products.forEach((product) => {
        productSelect.append(new Option(`${product.name} · ${product.price} ₽`, String(product.id)));
        const item = document.createElement('div');
        item.className = 'api-product';
        const text = document.createElement('span');
        text.textContent = `#${product.id} · ${product.name} · ${product.price} ₽ · ${product.quantity} шт.`;
        item.append(text);
        productsBox.append(item);
    });
    if (products.some((product) => String(product.id) === selectedId)) productSelect.value = selectedId;
}

productSelect.addEventListener('change', () => {
    const product = products.find((item) => String(item.id) === productSelect.value);
    if (!product) {
        resetForm();
        return;
    }
    idInput.value = product.id;
    nameInput.value = product.name;
    priceInput.value = product.price;
    quantityInput.value = product.quantity;
    nameField.hidden = true;
    if (methodSelect.value === 'POST') methodSelect.value = 'GET';
    updateMethodUi();
    formTitle.textContent = `Изменение товара «${product.name}»`;
});

async function loadProducts(showGetResponse = true) {
    const response = await fetch('api/products/');
    const data = await response.json();
    if (showGetResponse) showResponse('GET /api/products/', response.status, data);
    if (!response.ok) throw new Error(data.error || 'Ошибка получения товаров');
    products = data;
    renderProducts();
}

methodSelect.addEventListener('change', () => {
    if (methodSelect.value === 'POST' && productSelect.value !== '') {
        resetForm();
        return;
    }
    updateMethodUi();
});

form.addEventListener('submit', async (event) => {
    event.preventDefault();
    const id = idInput.value;
    const method = methodSelect.value;
    if ((method === 'PUT' || method === 'DELETE') && id === '') {
        message.textContent = `Для метода ${method} выберите товар`;
        message.className = 'form-result error';
        return;
    }
    const path = id === '' ? 'api/products/' : `api/products/${id}/`;
    const body = {
        name: nameInput.value.trim(),
        price: Number(priceInput.value),
        quantity: Number(quantityInput.value),
    };

    try {
        if (method === 'DELETE' && !window.confirm('Удалить выбранный товар?')) return;
        const options = { method, headers: { Accept: 'application/json' } };
        if (method === 'POST' || method === 'PUT') {
            options.headers['Content-Type'] = 'application/json';
            options.body = JSON.stringify(body);
        }
        const response = await fetch(path, options);
        const data = response.status === 204 ? null : await response.json();
        const operationLabel = `${method} /${path}`;
        if (!response.ok) {
            showResponse(operationLabel, response.status, data);
            throw new Error(data.error || 'Ошибка сохранения');
        }
        if (method !== 'GET') {
            resetForm();
            await loadProducts(false);
        }
        showResponse(operationLabel, response.status, data);
    } catch (error) {
        message.textContent = error.message;
        message.className = 'form-result error';
    }
});

resetButton.addEventListener('click', resetForm);
updateMethodUi();
loadProducts().catch((error) => {
    message.textContent = error.message;
    message.className = 'form-result error';
});
