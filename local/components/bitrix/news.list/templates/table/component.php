<?php
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

class PriceTableComponent extends CBitrixComponent
{
    public function executeComponent()
    {
        // Устанавливаем данные в arResult
        $this->arResult = [
            'PRODUCTS' => [
                [
                    'NAME' => 'Samsung Galaxy S23 Ultra 256GB',
                    'OUR_PRICE' => 89990,
                    'DNS_SHOP' => 87990,
                    'CITILINK' => 90990,
                    'MVIDEO' => 91990
                ],
                [
                    'NAME' => 'iPhone 15 Pro 128GB',
                    'OUR_PRICE' => 99990,
                    'DNS_SHOP' => 98990,
                    'CITILINK' => 100990,
                    'MVIDEO' => 101990
                ],
                [
                    'NAME' => 'Xiaomi 13 Pro 256GB',
                    'OUR_PRICE' => 69990,
                    'DNS_SHOP' => 68990,
                    'CITILINK' => 70990,
                    'MVIDEO' => 71990
                ]
            ],
            'LAST_UPDATE' => date('Y-m-d H:i:s')
        ];

        echo "<!-- DEBUG: arResult set with " . count($this->arResult['PRODUCTS']) . " products -->\n";
        
        // Включаем шаблон
        $this->includeComponentTemplate();
        
        return $this->arResult;
    }
}
?>