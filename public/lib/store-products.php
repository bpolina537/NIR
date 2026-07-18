<?php

declare(strict_types=1);

function storeProducts(): array
{
    return [
        1 => ['id'=>1,'name'=>'Ваза «Линия»','category'=>'Декор','price'=>2490,'stock'=>12,'weight'=>2,'image'=>'https://images.unsplash.com/photo-1618220179428-22790b461013?auto=format&fit=crop&w=900&q=85','description'=>'Керамическая ваза ручной работы с мягким матовым покрытием.'],
        2 => ['id'=>2,'name'=>'Плед «Тёплый песок»','category'=>'Текстиль','price'=>3690,'stock'=>8,'weight'=>1,'image'=>'https://images.unsplash.com/photo-1583845112203-454c2254edcf?auto=format&fit=crop&w=900&q=85','description'=>'Мягкий плед спокойного песочного оттенка для спальни и гостиной.'],
        3 => ['id'=>3,'name'=>'Лампа «Рассвет»','category'=>'Освещение','price'=>5990,'stock'=>6,'weight'=>3,'image'=>'https://images.unsplash.com/photo-1507473885765-e6ed057f782c?auto=format&fit=crop&w=900&q=85','description'=>'Настольная лампа с тёплым рассеянным светом и лаконичным основанием.'],
        4 => ['id'=>4,'name'=>'Поднос «Баланс»','category'=>'Декор','price'=>1890,'stock'=>15,'weight'=>1,'image'=>'https://images.unsplash.com/photo-1610701596007-11502861dcfa?auto=format&fit=crop&w=900&q=85','description'=>'Минималистичный поднос для сервировки и хранения небольших предметов.'],
        5 => ['id'=>5,'name'=>'Кресло «Облако»','category'=>'Мебель','price'=>19900,'stock'=>3,'weight'=>18,'image'=>'https://images.unsplash.com/photo-1567538096630-e0c55bd6374c?auto=format&fit=crop&w=900&q=85','description'=>'Компактное мягкое кресло с округлой спинкой и глубокой посадкой.'],
        6 => ['id'=>6,'name'=>'Свеча «Тишина»','category'=>'Ароматы','price'=>990,'stock'=>20,'weight'=>1,'image'=>'https://images.unsplash.com/photo-1603006905003-be475563bc59?auto=format&fit=crop&w=900&q=85','description'=>'Ароматическая свеча с древесными нотами в матовом стекле.'],
        7 => ['id'=>7,'name'=>'Зеркало «Круг»','category'=>'Декор','price'=>7490,'stock'=>5,'weight'=>8,'image'=>'https://images.unsplash.com/photo-1618220179428-22790b461013?auto=format&fit=crop&w=900&q=85','description'=>'Круглое настенное зеркало в тонкой металлической раме.'],
        8 => ['id'=>8,'name'=>'Корзина «Плетение»','category'=>'Хранение','price'=>2790,'stock'=>11,'weight'=>2,'image'=>'https://images.unsplash.com/photo-1616486338812-3dadae4b4ace?auto=format&fit=crop&w=900&q=85','description'=>'Вместительная плетёная корзина для текстиля и домашних мелочей.'],
        9 => ['id'=>9,'name'=>'Подушка «Шалфей»','category'=>'Текстиль','price'=>1590,'stock'=>14,'weight'=>1,'image'=>'https://images.unsplash.com/photo-1604014237800-1c9102c219da?auto=format&fit=crop&w=900&q=85','description'=>'Декоративная подушка приглушённого зелёного оттенка.'],
        10 => ['id'=>10,'name'=>'Столик «Контур»','category'=>'Мебель','price'=>12900,'stock'=>4,'weight'=>12,'image'=>'https://images.unsplash.com/photo-1578500494198-246f612d3b3d?auto=format&fit=crop&w=900&q=85','description'=>'Небольшой журнальный столик из массива дерева.'],
    ];
}

function storeProductsForJs(): string
{
    return json_encode(array_values(storeProducts()), JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
}
