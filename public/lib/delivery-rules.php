<?php

declare(strict_types=1);

function deliveryTerm(string $city): array
{
    if ($city === 'Москва') return [1, 2];
    if ($city === 'Тула') return [2, 3];
    $from = 3 + (abs(crc32($city)) % 2);
    return [$from, $from + 2];
}

function fallbackDeliveryPrice(string $city, int $weight): int
{
    return 350 + (abs(crc32($city)) % 650) + $weight * 45;
}
