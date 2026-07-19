<?php

declare(strict_types=1);

require dirname(__DIR__) . '/lib/external-service.php';

header('Content-Type: application/json; charset=utf-8');
header('Cache-Control: no-store');

$cacheDirectory = '/var/www/storage/cache';
$cacheFile = $cacheDirectory . '/cities.json';
$today = date('Y-m-d');
$source = 'cache';
$cities = null;
$fallbackCities = ['Волгоград','Воронеж','Екатеринбург','Казань','Калуга','Красноярск','Москва','Нижний Новгород','Новосибирск','Омск','Орел','Пермь','Ростов-на-Дону','Самара','Санкт-Петербург','Тула','Уфа','Челябинск'];

if (is_file($cacheFile) && date('Y-m-d', (int) filemtime($cacheFile)) === $today) {
    $cached = file_get_contents($cacheFile);
    $cities = $cached === false ? null : json_decode($cached, true);
}

if (!is_array($cities) || $cities === []) {
    $source = 'service';
    $response = externalServiceRequest('http://exercise.develop.maximaster.ru/service/city/');
    if ($response['body'] === false || $response['status'] >= 400) {
        $cities = $fallbackCities;
        $source = 'fallback';
    } else {
        $cities = decodeExternalJson((string) $response['body']);
    }

    if (!is_array($cities) || $cities === []) {
        $cities = $fallbackCities;
        $source = 'fallback';
    }

    $cities = array_values(array_filter(array_map('strval', $cities), fn(string $city) => trim($city) !== ''));
    if (!is_dir($cacheDirectory)) mkdir($cacheDirectory, 0775, true);
    file_put_contents($cacheFile, json_encode($cities, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT), LOCK_EX);
}

echo json_encode(['cities' => $cities, 'source' => $source], JSON_UNESCAPED_UNICODE);
