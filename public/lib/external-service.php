<?php

declare(strict_types=1);

function externalServiceRequest(string $url): array
{
    $login = getenv('EXERCISE_LOGIN') ?: '';
    $password = getenv('EXERCISE_PASSWORD') ?: '';

    if ($login === '' || $password === '') {
        return ['status' => 0, 'body' => false, 'error' => 'Не настроена авторизация учебного сервиса'];
    }

    $context = stream_context_create([
        'http' => [
            'method' => 'GET',
            'timeout' => 15,
            'ignore_errors' => true,
            'follow_location' => 1,
            'max_redirects' => 5,
            'header' => "Accept: application/json, text/plain\r\n"
                . "Accept-Encoding: identity\r\n"
                . "User-Agent: NIR-Atmosfera/1.0\r\n"
                . 'Authorization: Basic ' . base64_encode($login . ':' . $password) . "\r\n",
        ],
        'ssl' => [
            'verify_peer' => false,
            'verify_peer_name' => false,
        ],
    ]);

    $body = @file_get_contents($url, false, $context);
    $status = 0;
    foreach ($http_response_header ?? [] as $header) {
        if (preg_match('/^HTTP\/\S+\s+(\d{3})/', $header, $matches)) {
            $status = (int) $matches[1];
        }
    }

    return ['status' => $status, 'body' => $body, 'error' => ''];
}

function decodeExternalJson(string $payload): mixed
{
    $payload = preg_replace('/^\xEF\xBB\xBF/', '', $payload) ?? $payload;
    $data = json_decode($payload, true);

    if ($data === null && json_last_error() !== JSON_ERROR_NONE && function_exists('iconv')) {
        $converted = @iconv('Windows-1251', 'UTF-8//IGNORE', $payload);
        if ($converted !== false) $data = json_decode($converted, true);
    }

    return $data;
}
