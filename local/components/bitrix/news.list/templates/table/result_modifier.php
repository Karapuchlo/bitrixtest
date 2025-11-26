<?php
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

// Обрабатываем и дополняем данные в arResult
if (!isset($arResult['PRODUCTS']) || empty($arResult['PRODUCTS'])) {
    // Если нет данных - добавляем тестовые
    $arResult['PRODUCTS'] = [
        [
            'ID' => 1,
            'NAME' => 'Samsung Galaxy S23 Ultra 256GB',
            'OUR_PRICE' => 89990,
            'COMPETITOR_PRICES' => [
                'dns-shop.ru' => 87990,
                'citilink.ru' => 90990,
                'mvideo.ru' => 91990
            ]
        ],
        [
            'ID' => 2,
            'NAME' => 'iPhone 15 Pro 128GB',
            'OUR_PRICE' => 99990,
            'COMPETITOR_PRICES' => [
                'dns-shop.ru' => 79799,
                'citilink.ru' => 109990,
                'mvideo.ru' => 83499
            ]
        ],
        [
            'ID' => 3,
            'NAME' => 'Xiaomi 13 Pro 256GB',
            'OUR_PRICE' => 30000,
            'COMPETITOR_PRICES' => [
                'dns-shop.ru' => 33999,
                'citilink.ru' => 22990,
                'mvideo.ru' => 26999
            ]
        ]
    ];
}

// Добавляем статистику
$arResult['STATS'] = [
    'TOTAL_PRODUCTS' => count($arResult['PRODUCTS']),
    'TOTAL_COMPETITORS' => 0,
    'CHEAPER_COUNT' => 0,
    'EXPENSIVE_COUNT' => 0
];

// Обрабатываем каждый продукт
foreach ($arResult['PRODUCTS'] as &$product) {
    $product['PRICE_DIFF'] = [];
    
}
unset($product);
?>