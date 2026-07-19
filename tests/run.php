<?php

declare(strict_types=1);

require '/var/www/html/lib/store-products.php';
require '/var/www/html/lib/delivery-rules.php';

$tests = [];
function test(string $name, callable $callback): void
{
    global $tests;
    $tests[] = [$name, $callback];
}

function expect(bool $condition, string $message = 'Условие не выполнено'): void
{
    if (!$condition) throw new RuntimeException($message);
}

$products = storeProducts();

test('Каталог содержит ровно 10 товаров', fn() => expect(count($products) === 10));
test('Идентификаторы товаров уникальны', fn() => expect(count(array_unique(array_column($products, 'id'))) === 10));
test('У каждого товара есть обязательные поля', function () use ($products) {
    foreach ($products as $product) expect(array_diff(['id','name','category','price','stock','weight','image','description'], array_keys($product)) === []);
});
test('Все цены являются положительными числами', function () use ($products) {
    foreach ($products as $product) expect(is_int($product['price']) && $product['price'] > 0);
});
test('Все остатки являются неотрицательными целыми числами', function () use ($products) {
    foreach ($products as $product) expect(is_int($product['stock']) && $product['stock'] >= 0);
});
test('Вес каждого товара является положительным целым числом', function () use ($products) {
    foreach ($products as $product) expect(is_int($product['weight']) && $product['weight'] > 0);
});
test('JSON каталога корректен и содержит 10 записей', function () {
    $decoded = json_decode(storeProductsForJs(), true);
    expect(json_last_error() === JSON_ERROR_NONE && count($decoded) === 10);
});
test('Срок доставки по Москве равен 1–2 дня', fn() => expect(deliveryTerm('Москва') === [1, 2]));
test('Срок доставки по Туле равен 2–3 дня', fn() => expect(deliveryTerm('Тула') === [2, 3]));
test('Резервная стоимость растёт вместе с весом', fn() => expect(fallbackDeliveryPrice('Омск', 10) > fallbackDeliveryPrice('Омск', 1)));

$passed = 0;
foreach ($tests as $index => [$name, $callback]) {
    try {
        $callback();
        $passed++;
        echo sprintf("[%02d] PASS  %s\n", $index + 1, $name);
    } catch (Throwable $error) {
        echo sprintf("[%02d] FAIL  %s: %s\n", $index + 1, $name, $error->getMessage());
    }
}

echo "\nРезультат: {$passed}/" . count($tests) . " тестов пройдено.\n";
exit($passed === count($tests) ? 0 : 1);
