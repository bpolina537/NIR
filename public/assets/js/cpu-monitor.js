'use strict';

const currentValue = document.querySelector('#cpu-current');
const connectionStatus = document.querySelector('#cpu-connection');
const requestsValue = document.querySelector('#cpu-requests');
const errorsValue = document.querySelector('#cpu-errors');
const errorRateValue = document.querySelector('#cpu-error-rate');
const chartCanvas = document.querySelector('#cpu-chart');

let requests = 0;
let errors = 0;
let previousLoad = null;
let requestInProgress = false;

const chart = new Chart(chartCanvas, {
    type: 'line',
    data: {
        labels: [],
        datasets: [{
            label: 'Загрузка процессора, %',
            data: [],
            borderColor: '#b56f4a',
            backgroundColor: 'rgba(181, 111, 74, 0.14)',
            borderWidth: 2,
            pointRadius: 4,
            pointBackgroundColor: '#234238',
            tension: 0.3,
            fill: true,
        }],
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        animation: { duration: 350 },
        scales: {
            y: {
                beginAtZero: true,
                max: 100,
                ticks: { callback: (value) => `${value}%` },
            },
        },
        plugins: {
            legend: { display: true },
        },
    },
});

function updateStatistics() {
    requestsValue.textContent = String(requests);
    errorsValue.textContent = String(errors);
    const rate = requests === 0 ? 0 : (errors / requests) * 100;
    errorRateValue.textContent = `${rate.toFixed(1)}%`;
}

function addChartPoint(load) {
    const time = new Date().toLocaleTimeString('ru-RU');
    chart.data.labels.push(time);
    chart.data.datasets[0].data.push(load);

    if (chart.data.labels.length > 20) {
        chart.data.labels.shift();
        chart.data.datasets[0].data.shift();
    }

    chart.update();
}

async function requestCpuLoad() {
    if (requestInProgress) return;
    requestInProgress = true;
    requests += 1;

    try {
        const response = await fetch('api/cpu-proxy.php', {
            cache: 'no-store',
            headers: { Accept: 'application/json' },
        });
        const data = await response.json();

        if (!response.ok) {
            throw new Error(data.error || 'Не удалось получить загрузку процессора');
        }

        const load = Number(data.load);
        if (!Number.isFinite(load) || load < 0 || load > 100) {
            throw new Error('Сервис вернул некорректное значение');
        }

        if (load === 0) {
            errors += 1;
            connectionStatus.className = 'cpu-connection warning';
            connectionStatus.textContent = previousLoad === null
                ? 'Получена ошибка (0). Ожидается первое корректное значение.'
                : `Получена ошибка (0). Повторено предыдущее значение: ${previousLoad}%`;

            if (previousLoad !== null) addChartPoint(previousLoad);
        } else {
            previousLoad = load;
            currentValue.textContent = `${load}%`;
            connectionStatus.className = 'cpu-connection';
            connectionStatus.textContent = `Данные обновлены в ${new Date().toLocaleTimeString('ru-RU')}`;
            addChartPoint(load);
        }
    } catch (error) {
        errors += 1;
        connectionStatus.className = 'cpu-connection error';
        connectionStatus.textContent = error.message;
        if (previousLoad !== null) addChartPoint(previousLoad);
    } finally {
        updateStatistics();
        requestInProgress = false;
    }
}

requestCpuLoad();
setInterval(requestCpuLoad, 5000);
