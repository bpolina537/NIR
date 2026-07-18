<?php

declare(strict_types=1);

require dirname(__DIR__) . '/lib/external-service.php';

header('Content-Type: application/json; charset=utf-8');
header('Cache-Control: no-store');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    header('Allow: POST');
    echo json_encode(['error' => 'Допустим только POST-запрос'], JSON_UNESCAPED_UNICODE);
    exit;
}

$input = json_decode((string) file_get_contents('php://input'), true);
$city = trim((string) ($input['city'] ?? ''));
$weightRaw = $input['weight'] ?? null;

if ($city === '') {
    http_response_code(422);
    echo json_encode(['error' => 'Выберите город доставки'], JSON_UNESCAPED_UNICODE);
    exit;
}

if (filter_var($weightRaw, FILTER_VALIDATE_INT) === false || (int) $weightRaw <= 0) {
    http_response_code(422);
    echo json_encode(['error' => 'Вес должен быть положительным целым числом'], JSON_UNESCAPED_UNICODE);
    exit;
}

$weight = (int) $weightRaw;
$url = 'http://exercise.develop.maximaster.ru/service/delivery/?' . http_build_query([
    'city' => $city,
    'weight' => $weight,
]);
$response = externalServiceRequest($url);

if ($response['body'] === false || $response['status'] >= 400) {
    http_response_code(502);
    echo json_encode(['error' => 'Сервис расчёта доставки временно недоступен'], JSON_UNESCAPED_UNICODE);
    exit;
}

$result = decodeExternalJson((string) $response['body']);
if (!is_array($result) || !isset($result['status'], $result['message'])) {
    http_response_code(502);
    echo json_encode(['error' => 'Сервис доставки вернул некорректные данные'], JSON_UNESCAPED_UNICODE);
    exit;
}

if ($city === 'Москва') {
    $daysFrom = 1; $daysTo = 2;
} elseif ($city === 'Тула') {
    $daysFrom = 2; $daysTo = 3;
} else {
    $daysFrom = 3 + (abs(crc32($city)) % 2);
    $daysTo = $daysFrom + 2;
}

echo json_encode([
    'status' => (string) $result['status'],
    'price' => isset($result['price']) ? (int) $result['price'] : 0,
    'message' => (string) $result['message'],
    'days_from' => $daysFrom,
    'days_to' => $daysTo,
], JSON_UNESCAPED_UNICODE);
