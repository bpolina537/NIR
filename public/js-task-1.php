<?php

$title = 'Задание JavaScript № 1 — Атмосфера';
$adminPage = 'task-1';
require 'partials/admin-header.php';
?>
<section class="page-hero compact">
    <span class="eyebrow">Практикум JavaScript</span>
    <h1>Размер и цвет</h1>
    <p>Интерактивное изменение параметров элемента</p>
</section>

<section class="task-layout">
    <div class="task-description">
        <span class="task-number">Задание 01</span>
        <h2>Настройте квадрат</h2>
        <p>Введите ширину и высоту. Размер фигуры изменится сразу после ввода. Кнопка назначает случайный цвет.</p>
        <div class="task-controls">
            <label for="square-width">Ширина, px<input id="square-width" type="number" min="20" max="500" value="220" inputmode="numeric"></label>
            <label for="square-height">Высота, px<input id="square-height" type="number" min="20" max="500" value="220" inputmode="numeric"></label>
        </div>
        <button class="button random-color" id="random-color" type="button">Случайный цвет</button>
        <p class="task-hint" id="size-hint" aria-live="polite">Текущий размер: 220 × 220 px</p>
    </div>
    <div class="square-stage" aria-label="Область предпросмотра">
        <div class="dynamic-square" id="dynamic-square" role="img" aria-label="Изменяемый цветной квадрат"></div>
    </div>
</section>

<script src="assets/js/task-1.js" defer></script>
<?php require 'partials/admin-footer.php'; ?>
