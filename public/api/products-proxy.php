<?php

declare(strict_types=1);

header('Content-Type: application/json; charset=utf-8');
header('Cache-Control: no-store');

$credentials = '';
$login = getenv('EXERCISE_LOGIN') ?: '';
$password = getenv('EXERCISE_PASSWORD') ?: '';

if ($login !== '' && $password !== '') {
    $credentials = 'Authorization: Basic ' . base64_encode($login . ':' . $password) . "\r\n";
}

$context = stream_context_create([
    'http' => [
        'method' => 'GET',
        'timeout' => 12,
        'ignore_errors' => true,
        'header' => "Accept: application/json\r\n" . $credentials,
    ],
    // У учебного сервиса некорректный сертификат. Проверка отключена только для этого запроса.
    'ssl' => [
        'verify_peer' => false,
        'verify_peer_name' => false,
    ],
]);

$urls = [
    'http://exercise.develop.maximaster.ru/service/products/',
    'https://exercise.develop.maximaster.ru/service/products/',
];

$payload = false;

foreach ($urls as $url) {
    $payload = @file_get_contents($url, false, $context);
    if ($payload !== false && trim($payload) !== '') {
        break;
    }
}

if ($payload === false || trim($payload) === '') {
    http_response_code(502);
    echo json_encode([
        'error' => 'Внешний сервис товаров временно недоступен',
    ], JSON_UNESCAPED_UNICODE);
    exit;
}

$products = json_decode($payload, true);

if (!is_array($products)) {
    http_response_code(502);
    echo json_encode([
        'error' => 'Внешний сервис вернул некорректный JSON',
    ], JSON_UNESCAPED_UNICODE);
    exit;
}

$result = [];

foreach ($products as $product) {
    if (!is_array($product) || !isset($product['name'], $product['price'], $product['quantity'])) {
        continue;
    }

    if (!is_numeric($product['price']) || !is_numeric($product['quantity'])) {
        continue;
    }

    $result[] = [
        'name' => (string) $product['name'],
        'price' => (float) $product['price'],
        'quantity' => (int) $product['quantity'],
    ];
}

if ($result === []) {
    http_response_code(502);
    echo json_encode([
        'error' => 'В ответе внешнего сервиса нет корректных товаров',
    ], JSON_UNESCAPED_UNICODE);
    exit;
}

echo json_encode($result, JSON_UNESCAPED_UNICODE | JSON_PRESERVE_ZERO_FRACTION);
