<?php

$title = 'Учётная таблица — Атмосфера';
$adminPage = 'spreadsheet';
require 'partials/admin-header.php';
?>
<section class="page-hero compact admin-page-heading">
    <span class="eyebrow">Практикум JavaScript</span>
    <h1>Учётная таблица</h1>
    <p>Дважды щёлкните по ячейке для редактирования. На мобильном устройстве достаточно одного тапа.</p>
</section>

<section class="sheet-tool">
    <div class="sheet-message" id="sheet-message" role="status" aria-live="polite"></div>

    <div class="sheet-stage">
        <div class="sheet-wrap">
            <table class="sheet" id="spreadsheet" aria-label="Редактируемая учётная таблица"></table>
        </div>
        <div class="sheet-side-actions" aria-label="Управление столбцами">
            <span>Столбцы</span>
            <button id="add-column" type="button" aria-label="Добавить столбец">+</button>
            <button id="remove-column" type="button" aria-label="Удалить столбец">−</button>
        </div>
        <div class="sheet-bottom-actions" aria-label="Управление строками">
            <span>Строки</span>
            <button id="add-row" type="button" aria-label="Добавить строку">+</button>
            <button id="remove-row" type="button" aria-label="Удалить строку">−</button>
        </div>
    </div>

    <p class="sheet-note">Изменения сохраняются автоматически в LocalStorage этого браузера. Enter сохраняет ввод, Escape отменяет его.</p>
</section>

<script src="assets/js/spreadsheet.js" defer></script>
<?php require 'partials/admin-footer.php'; ?>
