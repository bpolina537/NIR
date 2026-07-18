<?php

declare(strict_types=1);

require dirname(__DIR__) . '/lib/database.php';

header('Content-Type: application/json; charset=utf-8');
header('Cache-Control: no-store');

function respond(mixed $data = null, int $status = 200): never
{
    http_response_code($status);
    if ($status !== 204) {
        echo json_encode($data, JSON_UNESCAPED_UNICODE | JSON_PRESERVE_ZERO_FRACTION);
    }
    exit;
}

function requestJson(): array
{
    $raw = (string) file_get_contents('php://input');
    $data = json_decode($raw, true);
    if (!is_array($data)) respond(['error' => 'Тело запроса должно содержать корректный JSON'], 400);
    return $data;
}

function validateProduct(array $data): array
{
    $errors = [];
    $name = trim((string) ($data['name'] ?? ''));
    $price = $data['price'] ?? null;
    $quantity = $data['quantity'] ?? null;

    if ($name === '') $errors['name'] = 'Укажите название товара';
    if (strlen($name) > 180) $errors['name'] = 'Название слишком длинное';
    if (!is_numeric($price) || (float) $price < 0) $errors['price'] = 'Цена должна быть неотрицательным числом';
    if (filter_var($quantity, FILTER_VALIDATE_INT) === false || (int) $quantity < 0) {
        $errors['quantity'] = 'Количество должно быть неотрицательным целым числом';
    }

    if ($errors !== []) respond(['error' => 'Ошибка проверки данных', 'fields' => $errors], 422);

    return ['name' => $name, 'price' => (float) $price, 'quantity' => (int) $quantity];
}

function formatProduct(array $product): array
{
    return [
        'id' => (int) $product['id'],
        'name' => (string) $product['name'],
        'price' => (float) $product['price'],
        'quantity' => (int) $product['quantity'],
    ];
}

try {
    $connection = database();
    $method = $_SERVER['REQUEST_METHOD'];
    $id = isset($_GET['id']) && $_GET['id'] !== '' ? filter_var($_GET['id'], FILTER_VALIDATE_INT) : null;

    if (isset($_GET['id']) && $id === false) respond(['error' => 'Некорректный идентификатор товара'], 400);

    if ($method === 'OPTIONS') {
        header('Allow: GET, POST, PUT, DELETE, OPTIONS');
        respond(null, 204);
    }

    if ($method === 'GET' && $id === null) {
        $rows = $connection->query('SELECT id, name, price, quantity FROM products ORDER BY id')->fetchAll();
        respond(array_map('formatProduct', $rows));
    }

    if ($method === 'GET') {
        $statement = $connection->prepare('SELECT id, name, price, quantity FROM products WHERE id = ?');
        $statement->execute([$id]);
        $product = $statement->fetch();
        if (!$product) respond(['error' => 'Товар не найден'], 404);
        respond(formatProduct($product));
    }

    if ($method === 'POST' && $id === null) {
        $product = validateProduct(requestJson());
        $statement = $connection->prepare('INSERT INTO products (name, price, quantity) VALUES (?, ?, ?)');
        $statement->execute([$product['name'], $product['price'], $product['quantity']]);
        $newId = (int) $connection->lastInsertId();
        header('Location: /api/products/' . $newId . '/');
        respond(['id' => $newId] + $product, 201);
    }

    if ($method === 'PUT') {
        if ($id === null) respond(['error' => 'Для обновления укажите идентификатор товара'], 400);
        $product = validateProduct(requestJson());
        $statement = $connection->prepare('UPDATE products SET name = ?, price = ?, quantity = ? WHERE id = ?');
        $statement->execute([$product['name'], $product['price'], $product['quantity'], $id]);
        if ($statement->rowCount() === 0) {
            $exists = $connection->prepare('SELECT 1 FROM products WHERE id = ?');
            $exists->execute([$id]);
            if (!$exists->fetchColumn()) respond(['error' => 'Товар не найден'], 404);
        }
        respond(['id' => (int) $id] + $product);
    }

    if ($method === 'DELETE') {
        if ($id === null) respond(['error' => 'Для удаления укажите идентификатор товара'], 400);
        $statement = $connection->prepare('DELETE FROM products WHERE id = ?');
        $statement->execute([$id]);
        if ($statement->rowCount() === 0) respond(['error' => 'Товар не найден'], 404);
        respond(null, 204);
    }

    header('Allow: GET, POST, PUT, DELETE, OPTIONS');
    respond(['error' => 'HTTP-метод не поддерживается'], 405);
} catch (PDOException $error) {
    respond(['error' => 'Ошибка базы данных'], 500);
}
