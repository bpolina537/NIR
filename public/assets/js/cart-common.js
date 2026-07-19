'use strict';

(function () {
    const key = 'atmosfera-cart-v1';
    const ordersKey = 'atmosfera-orders-v1';

    function getCart() {
        try {
            const data = JSON.parse(localStorage.getItem(key));
            return Array.isArray(data) ? data.filter((item) => Number.isInteger(item.id) && item.quantity > 0) : [];
        } catch (error) {
            return [];
        }
    }

    function saveCart(cart) {
        localStorage.setItem(key, JSON.stringify(cart));
        updateCount();
        window.dispatchEvent(new CustomEvent('atmosfera-cart-change'));
    }

    function updateCount() {
        const count = getCart().reduce((sum, item) => sum + item.quantity, 0);
        const element = document.querySelector('#cart-count');
        if (element) element.textContent = String(count);
    }

    function add(id, quantity = 1) {
        const cart = getCart();
        const item = cart.find((entry) => entry.id === id);
        if (item) item.quantity += quantity;
        else cart.push({ id, quantity, selected: true });
        saveCart(cart);
    }

    function getOrders() {
        try {
            const data = JSON.parse(localStorage.getItem(ordersKey));
            return Array.isArray(data) ? data : [];
        } catch (error) {
            return [];
        }
    }

    function saveOrder(order) {
        const orders = getOrders();
        orders.unshift(order);
        localStorage.setItem(ordersKey, JSON.stringify(orders.slice(0, 20)));
    }

    window.AtmosferaCart = { getCart, saveCart, updateCount, add, getOrders, saveOrder };

    document.addEventListener('click', (event) => {
        const button = event.target.closest('.add-to-cart');
        if (!button) return;
        const id = Number(button.dataset.productId);
        const input = button.dataset.quantityInput ? document.querySelector(`#${button.dataset.quantityInput}`) : null;
        const quantity = input ? Math.max(1, Number.parseInt(input.value, 10) || 1) : 1;
        add(id, quantity);
        const oldText = button.textContent;
        button.textContent = 'Добавлено ✓';
        setTimeout(() => { button.textContent = oldText; }, 1200);
    });

    updateCount();
    document.addEventListener('DOMContentLoaded', updateCount);
})();
