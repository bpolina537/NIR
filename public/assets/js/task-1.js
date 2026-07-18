'use strict';

const widthInput = document.querySelector('#square-width');
const heightInput = document.querySelector('#square-height');
const square = document.querySelector('#dynamic-square');
const colorButton = document.querySelector('#random-color');
const sizeHint = document.querySelector('#size-hint');
const MIN_SIZE = 20;
const MAX_SIZE = 500;

function normalizeSize(value, fallback) {
    const number = Number.parseInt(value, 10);
    if (Number.isNaN(number)) return fallback;
    return Math.min(MAX_SIZE, Math.max(MIN_SIZE, number));
}

function updateSquareSize() {
    const width = normalizeSize(widthInput.value, MIN_SIZE);
    const height = normalizeSize(heightInput.value, MIN_SIZE);
    square.style.width = `${width}px`;
    square.style.height = `${height}px`;
    sizeHint.textContent = `Текущий размер: ${width} × ${height} px`;
}

function createRandomColor() {
    const channel = () => Math.floor(Math.random() * 206) + 30;
    return `rgb(${channel()}, ${channel()}, ${channel()})`;
}

widthInput.addEventListener('input', updateSquareSize);
heightInput.addEventListener('input', updateSquareSize);
colorButton.addEventListener('click', () => {
    square.style.backgroundColor = createRandomColor();
});

updateSquareSize();
