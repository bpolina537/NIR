'use strict';

(function () {
    const citySelect = document.querySelector('#delivery-city');
    const mapHint = document.querySelector('#map-hint');
    const latitudeInput = document.querySelector('#map-lat');
    const longitudeInput = document.querySelector('#map-lng');
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
    let deliveryMap = null;
    let placemark = null;
    let citySelectedFromMap = false;

    function clearPlacemark() {
        if (placemark) deliveryMap.geoObjects.remove(placemark);
        placemark = null;
        latitudeInput.value = '';
        longitudeInput.value = '';
    }

    function centerOnCity(city) {
        if (!deliveryMap || !cityCoordinates[city]) return;
        deliveryMap.setCenter(cityCoordinates[city], 11, { duration: 350 });
        clearPlacemark();
        mapHint.textContent = `Выберите адрес в городе ${city}`;
    }

    function nearestCity(coords) {
        return Object.entries(cityCoordinates).reduce((nearest, [city, cityCoords]) => {
            const distance = Math.hypot(coords[0] - cityCoords[0], (coords[1] - cityCoords[1]) * 0.65);
            return distance < nearest.distance ? { city, distance } : nearest;
        }, { city: 'Москва', distance: Number.POSITIVE_INFINITY }).city;
    }

    citySelect.addEventListener('change', () => {
        window.dispatchEvent(new CustomEvent('delivery-location-change'));
        if (citySelectedFromMap) {
            citySelectedFromMap = false;
            return;
        }
        centerOnCity(citySelect.value);
    });

    if (typeof ymaps === 'undefined') {
        mapHint.textContent = 'Карта временно недоступна';
        return;
    }

    ymaps.ready(() => {
        deliveryMap = new ymaps.Map('map', {
            center: cityCoordinates.Москва,
            zoom: 10,
            controls: ['zoomControl'],
        });
        centerOnCity(citySelect.value || 'Москва');
        deliveryMap.events.add('click', (event) => {
            const coords = event.get('coords');
            const detectedCity = nearestCity(coords);
            const coordinateText = `${coords[0].toFixed(5)}, ${coords[1].toFixed(5)}`;
            citySelectedFromMap = true;
            citySelect.value = detectedCity;
            citySelect.dispatchEvent(new Event('change'));
            clearPlacemark();
            placemark = new ymaps.Placemark(
                coords,
                { balloonContent: `${detectedCity}<br>${coordinateText}` },
                { preset: 'islands#redDotIcon' },
            );
            deliveryMap.geoObjects.add(placemark);
            placemark.balloon.open();
            latitudeInput.value = coords[0].toFixed(5);
            longitudeInput.value = coords[1].toFixed(5);
            mapHint.textContent = `${detectedCity}: ${coordinateText}`;
        });
    });
})();
