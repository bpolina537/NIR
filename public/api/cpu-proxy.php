<?php

declare(strict_types=1);

header('Content-Type: application/json; charset=utf-8');
header('Cache-Control: no-store, no-cache, must-revalidate');

$login = getenv('EXERCISE_LOGIN') ?: '';
$password = getenv('EXERCISE_PASSWORD') ?: '';

if ($login === '' || $password === '') {
    http_response_code(500);
    echo json_encode(['error' => 'Не настроена авторизация внешнего сервиса'], JSON_UNESCAPED_UNICODE);
    exit;
}

$context = stream_context_create([
    'http' => [
        'method' => 'GET',
        'timeout' => 12,
        'ignore_errors' => true,
        'follow_location' => 1,
        'max_redirects' => 5,
        'header' => "Accept: text/plain\r\n"
            . "Accept-Encoding: identity\r\n"
            . "User-Agent: NIR-Atmosfera/1.0\r\n"
            . 'Authorization: Basic ' . base64_encode($login . ':' . $password) . "\r\n",
    ],
    'ssl' => [
        'verify_peer' => false,
        'verify_peer_name' => false,
    ],
]);

$payload = @file_get_contents('http://exercise.develop.maximaster.ru/service/cpu/', false, $context);
$status = 0;

foreach ($http_response_header ?? [] as $header) {
    if (preg_match('/^HTTP\/\S+\s+(\d{3})/', $header, $matches)) {
        $status = (int) $matches[1];
    }
}

if ($status === 401) {
    http_response_code(502);
    echo json_encode(['error' => 'Внешний сервис отклонил логин или пароль'], JSON_UNESCAPED_UNICODE);
    exit;
}

if ($payload === false || $status >= 400) {
    http_response_code(502);
    echo json_encode(['error' => 'Сервис загрузки процессора временно недоступен'], JSON_UNESCAPED_UNICODE);
    exit;
}

$load = filter_var(trim($payload), FILTER_VALIDATE_INT);

if ($load === false || $load < 0 || $load > 100) {
    http_response_code(502);
    echo json_encode(['error' => 'Сервис вернул некорректные данные'], JSON_UNESCAPED_UNICODE);
    exit;
}

echo json_encode(['load' => $load], JSON_UNESCAPED_UNICODE);
