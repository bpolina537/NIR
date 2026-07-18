<?php

$title = 'Загрузка процессора — Атмосфера';
$adminPage = 'cpu';
require 'partials/admin-header.php';
?>
<section class="page-hero compact admin-page-heading">
    <span class="eyebrow">Практикум JavaScript</span>
    <h1>Загрузка процессора</h1>
    <p>Динамический график обновляется раз в 5 секунд</p>
</section>

<section class="cpu-tool">
    <div class="cpu-status-row">
        <div>
            <span>Текущая загрузка</span>
            <strong id="cpu-current">Ожидание…</strong>
        </div>
        <div class="cpu-connection" id="cpu-connection" role="status" aria-live="polite">
            Подключение к сервису…
        </div>
    </div>

    <div class="cpu-chart-wrap">
        <canvas id="cpu-chart" aria-label="График загруженности процессора"></canvas>
    </div>

    <div class="cpu-statistics">
        <div><span>Всего запросов</span><strong id="cpu-requests">0</strong></div>
        <div><span>Запросов с ошибкой</span><strong id="cpu-errors">0</strong></div>
        <div><span>Процент ошибок</span><strong id="cpu-error-rate">0%</strong></div>
    </div>

    <p class="cpu-note">Если сервис возвращает 0, запрос считается ошибочным, а на графике повторяется значение предыдущего успешного запроса.</p>
</section>

<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.7/dist/chart.umd.min.js" defer></script>
<script src="assets/js/cpu-monitor.js" defer></script>
<?php require 'partials/admin-footer.php'; ?>
