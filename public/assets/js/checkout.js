'use strict';

if (typeof ymaps !== 'undefined') {
    ymaps.ready(function () {
        var map = new ymaps.Map('map', {
            center: [55.751574, 37.573856],
            zoom: 10,
            controls: ['zoomControl']
        });
        var placemark = null;
        map.events.add('click', function (e) {
            var coords = e.get('coords');
            if (placemark) { map.geoObjects.remove(placemark); }
            placemark = new ymaps.Placemark(coords, {
                balloonContent: coords[0].toFixed(5) + ', ' + coords[1].toFixed(5)
            }, { preset: 'islands#redDotIcon' });
            map.geoObjects.add(placemark);
            document.getElementById('map-lat').value = coords[0].toFixed(5);
            document.getElementById('map-lng').value = coords[1].toFixed(5);
            document.getElementById('map-hint').textContent = coords[0].toFixed(5) + ', ' + coords[1].toFixed(5);
        });
    });
}

var commentEl = document.getElementById('comment');
var countEl = document.getElementById('comment-count');
if (commentEl && countEl) {
    commentEl.addEventListener('input', function () {
        countEl.textContent = this.value.length;
    });
}

var submitBtn = document.getElementById('submit-order');
var errorsEl = document.getElementById('form-errors');

if (submitBtn) {
    submitBtn.addEventListener('click', function () {
        var errors = [];
        var fio = document.getElementById('fio').value.trim();
        var phone = document.getElementById('phone').value.trim();
        var email = document.getElementById('email').value.trim();
        var lat = document.getElementById('map-lat').value;

        if (!fio) { errors.push('Не заполнено поле ФИО'); }
        if (!phone) { errors.push('Не заполнено поле «Телефон»'); }
        else { var digits = phone.replace(/[^0-9]/g, ''); if (!digits || digits.length < 7) { errors.push('Телефон должен содержать только числа'); } }
        if (email && email.indexOf('@') === -1) { errors.push('Неверный Email (отсутствует собака @)'); }
        if (!lat) { errors.push('Не отмечен адрес доставки'); }

        errorsEl.innerHTML = '';
        submitBtn.style.background = '';
        submitBtn.disabled = false;

        if (errors.length > 0) {
            errorsEl.innerHTML = errors.map(function (e) { return '<p style="margin:4px 0;color:#bd4040">' + e + '</p>'; }).join('');
            submitBtn.textContent = 'Оформить заказ';
        } else {
            submitBtn.textContent = '✔ Заказ оформлен!';
            submitBtn.style.background = '#52685d';
            submitBtn.disabled = true;
        }
    });
}