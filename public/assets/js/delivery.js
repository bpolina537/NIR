'use strict';

(function () {

const citySelect = document.querySelector('#delivery-city');
const weightInput = document.querySelector('#delivery-weight');
const submitButton = document.querySelector('#calculate-delivery');
const result = document.querySelector('#delivery-result');
const cacheInfo = document.querySelector('#cities-cache-info');

function showResult(text, error = false) {
    result.textContent = text;
    result.className = error ? 'delivery-result error' : 'delivery-result';
}

async function loadCities() {
    try {
        const response = await fetch('api/cities.php', { cache: 'no-store' });
        const data = await response.json();
        if (!response.ok) throw new Error(data.error || 'Не удалось загрузить города');

        citySelect.replaceChildren();
        data.cities.forEach((city) => {
            const option = document.createElement('option');
            option.value = city;
            option.textContent = city;
            option.selected = city === 'Москва';
            citySelect.append(option);
        });

        citySelect.disabled = false;
        submitButton.disabled = false;
        cacheInfo.textContent = data.source === 'cache'
            ? 'Список городов загружен из кэша за сегодня'
            : 'Список городов получен из сервиса и сохранён в кэш';
        citySelect.dispatchEvent(new Event('change'));
    } catch (error) {
        citySelect.innerHTML = '<option value="">Города недоступны</option>';
        showResult(error.message, true);
    }
}

submitButton.addEventListener('click', async () => {
    result.className = 'form-result';

    const weight = Number(weightInput.value);
    if (!Number.isInteger(weight) || weight <= 0) {
        showResult('Вес должен быть положительным целым числом', true);
        return;
    }

    submitButton.disabled = true;
    showResult('Выполняется расчёт…');

    try {
        const response = await fetch('api/delivery.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', Accept: 'application/json' },
            body: JSON.stringify({ city: citySelect.value, weight }),
        });
        const data = await response.json();
        if (!response.ok) throw new Error(data.error || 'Ошибка расчёта');
        const isError = String(data.status).toLowerCase() !== 'ok';
        const term = `${data.days_from}–${data.days_to} дн.`;
        showResult(isError ? data.message : `${data.message} Срок доставки: ${term}`, isError);
        if (!isError) {
            window.dispatchEvent(new CustomEvent('delivery-calculated', {
                detail: { price: Number(data.price), term, city: citySelect.value },
            }));
        }
    } catch (error) {
        showResult(error.message, true);
    } finally {
        submitButton.disabled = false;
    }
});

loadCities();

})();
