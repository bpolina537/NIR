'use strict';

const catalogSort = document.querySelector('#catalog-sort');
const catalogGrid = document.querySelector('#catalog-grid');
catalogSort?.addEventListener('change', () => {
    const cards = [...catalogGrid.children];
    if (catalogSort.value !== 'default') {
        cards.sort((a, b) => Number(a.dataset.price) - Number(b.dataset.price));
        if (catalogSort.value === 'expensive') cards.reverse();
    } else {
        cards.sort((a, b) => Number(a.querySelector('.add-to-cart').dataset.productId) - Number(b.querySelector('.add-to-cart').dataset.productId));
    }
    catalogGrid.append(...cards);
});
