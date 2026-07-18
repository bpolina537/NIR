'use strict';

const STORAGE_KEY = 'atmosfera-spreadsheet-v1';
const table = document.querySelector('#spreadsheet');
const message = document.querySelector('#sheet-message');
const addRowButton = document.querySelector('#add-row');
const removeRowButton = document.querySelector('#remove-row');
const addColumnButton = document.querySelector('#add-column');
const removeColumnButton = document.querySelector('#remove-column');

function createInitialState() {
    return {
        rows: 4,
        columns: 4,
        cells: Array.from({ length: 4 }, () => Array(4).fill('')),
    };
}

function loadState() {
    try {
        const saved = JSON.parse(localStorage.getItem(STORAGE_KEY));
        if (!saved || !Number.isInteger(saved.rows) || !Number.isInteger(saved.columns)
            || saved.rows < 1 || saved.columns < 1 || !Array.isArray(saved.cells)) {
            return createInitialState();
        }

        const cells = Array.from({ length: saved.rows }, (_, row) =>
            Array.from({ length: saved.columns }, (_, column) =>
                String(saved.cells[row]?.[column] ?? '')));

        return { rows: saved.rows, columns: saved.columns, cells };
    } catch (error) {
        return createInitialState();
    }
}

let state = loadState();

function saveState() {
    localStorage.setItem(STORAGE_KEY, JSON.stringify(state));
}

function columnName(index) {
    let value = index + 1;
    let name = '';
    while (value > 0) {
        value -= 1;
        name = String.fromCharCode(65 + (value % 26)) + name;
        value = Math.floor(value / 26);
    }
    return name;
}

function showMessage(text, isError = false) {
    message.textContent = text;
    message.className = isError ? 'sheet-message error' : 'sheet-message';
}

function renderTable() {
    const head = document.createElement('thead');
    const headerRow = document.createElement('tr');
    const corner = document.createElement('th');
    corner.className = 'row-number';
    headerRow.append(corner);

    for (let column = 0; column < state.columns; column += 1) {
        const header = document.createElement('th');
        header.textContent = columnName(column);
        headerRow.append(header);
    }
    head.append(headerRow);

    const body = document.createElement('tbody');
    for (let row = 0; row < state.rows; row += 1) {
        const tableRow = document.createElement('tr');
        const rowNumber = document.createElement('th');
        rowNumber.className = 'row-number';
        rowNumber.textContent = String(row + 1);
        tableRow.append(rowNumber);

        for (let column = 0; column < state.columns; column += 1) {
            const cell = document.createElement('td');
            cell.dataset.row = String(row);
            cell.dataset.column = String(column);
            cell.textContent = state.cells[row][column];
            cell.title = 'Двойной клик для редактирования';
            tableRow.append(cell);
        }
        body.append(tableRow);
    }

    table.replaceChildren(head, body);
}

function editCell(cell) {
    if (cell.classList.contains('editing')) return;

    const row = Number(cell.dataset.row);
    const column = Number(cell.dataset.column);
    const oldValue = state.cells[row][column];
    const input = document.createElement('input');
    input.type = 'text';
    input.value = oldValue;
    input.setAttribute('aria-label', `Ячейка ${columnName(column)}${row + 1}`);
    cell.classList.add('editing');
    cell.replaceChildren(input);
    input.focus();
    input.select();

    let finished = false;
    const finish = (save) => {
        if (finished) return;
        finished = true;
        if (save) {
            state.cells[row][column] = input.value;
            saveState();
            showMessage(`Ячейка ${columnName(column)}${row + 1} сохранена`);
        }
        renderTable();
    };

    input.addEventListener('keydown', (event) => {
        if (event.key === 'Enter') finish(true);
        if (event.key === 'Escape') finish(false);
    });
    input.addEventListener('blur', () => finish(true));
}

table.addEventListener('dblclick', (event) => {
    const cell = event.target.closest('td');
    if (cell) editCell(cell);
});

table.addEventListener('pointerup', (event) => {
    if (event.pointerType !== 'touch' && event.pointerType !== 'pen') return;
    const cell = event.target.closest('td');
    if (cell) editCell(cell);
});

addRowButton.addEventListener('click', () => {
    state.cells.push(Array(state.columns).fill(''));
    state.rows += 1;
    saveState();
    renderTable();
    showMessage('Добавлена новая строка');
});

addColumnButton.addEventListener('click', () => {
    state.cells.forEach((row) => row.push(''));
    state.columns += 1;
    saveState();
    renderTable();
    showMessage('Добавлен новый столбец');
});

removeRowButton.addEventListener('click', () => {
    if (state.rows === 1) {
        showMessage('Нельзя удалить последнюю строку', true);
        return;
    }

    const lastRowHasData = state.cells[state.rows - 1].some((value) => value.trim() !== '');
    if (lastRowHasData && !window.confirm('В удаляемой строке есть данные. Удалить строку?')) return;

    state.cells.pop();
    state.rows -= 1;
    saveState();
    renderTable();
    showMessage('Последняя строка удалена');
});

removeColumnButton.addEventListener('click', () => {
    if (state.columns === 1) {
        showMessage('Нельзя удалить последний столбец', true);
        return;
    }

    const lastColumnHasData = state.cells.some((row) => row[state.columns - 1].trim() !== '');
    if (lastColumnHasData && !window.confirm('В удаляемом столбце есть данные. Удалить столбец?')) return;

    state.cells.forEach((row) => row.pop());
    state.columns -= 1;
    saveState();
    renderTable();
    showMessage('Последний столбец удалён');
});

renderTable();
showMessage('Таблица загружена. Данные сохраняются автоматически.');
