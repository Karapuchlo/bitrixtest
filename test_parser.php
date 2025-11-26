<?php
// test_parser.php - тестовые данные
require_once $_SERVER['DOCUMENT_ROOT'] . '/bitrix/modules/main/include/prolog_before.php';

class TestSmartphoneParser {
    private $iblockId = 4;
    
    public function run() {
        $testProducts = [
            // Первые 10 смартфонов
            ['NAME' => 'Смартфон Samsung Galaxy S23 Ultra 12/256GB Black', 'PRICE' => 89990],
            ['NAME' => 'Смартфон Xiaomi 13 Pro 12/256GB White', 'PRICE' => 69990],
            ['NAME' => 'Смартфон Apple iPhone 15 Pro 128GB Natural Titanium', 'PRICE' => 99990],
            ['NAME' => 'Смартфон Samsung Galaxy A54 5G 8/128GB Black', 'PRICE' => 29990],
            ['NAME' => 'Смартфон Xiaomi Redmi Note 12 Pro 8/256GB Gray', 'PRICE' => 25990],
            ['NAME' => 'Смартфон Apple iPhone 14 128GB Midnight', 'PRICE' => 79990],
            ['NAME' => 'Смартфон Samsung Galaxy Z Flip5 8/256GB Graphite', 'PRICE' => 89990],
            ['NAME' => 'Смартфон Xiaomi Poco X5 Pro 8/256GB Black', 'PRICE' => 22990],
            ['NAME' => 'Смартфон Apple iPhone 13 128GB Pink', 'PRICE' => 59990],
            ['NAME' => 'Смартфон Samsung Galaxy S22 8/128GB Phantom Black', 'PRICE' => 49990],
            
            // Дополнительные 10 смартфонов
            ['NAME' => 'Смартфон Google Pixel 8 Pro 12/256GB Obsidian', 'PRICE' => 84990],
            ['NAME' => 'Смартфон OnePlus 11 16/256GB Eternal Green', 'PRICE' => 64990],
            ['NAME' => 'Смартфон Realme GT Neo 5 16/1TB Purple', 'PRICE' => 45990],
            ['NAME' => 'Смартфон Samsung Galaxy A34 6/128GB Graphite', 'PRICE' => 24990],
            ['NAME' => 'Смартфон Xiaomi Redmi Note 13 Pro 8/256GB Blue', 'PRICE' => 28990],
            ['NAME' => 'Смартфон Apple iPhone 15 128GB Blue', 'PRICE' => 84990],
            ['NAME' => 'Смартфон Nothing Phone (2) 12/256GB White', 'PRICE' => 54990],
            ['NAME' => 'Смартфон Samsung Galaxy S24+ 12/512GB Violet', 'PRICE' => 109990],
            ['NAME' => 'Смартфон Xiaomi 14 12/512GB Black', 'PRICE' => 79990],
            ['NAME' => 'Смартфон Apple iPhone SE 2022 64GB Midnight', 'PRICE' => 39990],
            
            // Еще 5 премиальных моделей
            ['NAME' => 'Смартфон Samsung Galaxy Z Fold5 12/512GB Phantom Black', 'PRICE' => 149990],
            ['NAME' => 'Смартфон Apple iPhone 15 Pro Max 1TB Natural Titanium', 'PRICE' => 189990],
            ['NAME' => 'Смартфон Google Pixel 8 8/256GB Hazel', 'PRICE' => 69990],
            ['NAME' => 'Смартфон Xiaomi 13T Pro 12/512GB Alpine Blue', 'PRICE' => 59990],
            ['NAME' => 'Смартфон Samsung Galaxy S23+ 8/512GB Cream', 'PRICE' => 79990]
        ];
        
        return $this->saveToBitrix($testProducts);
    }
    
    private function saveToBitrix($products) {
        CModule::IncludeModule('iblock');
        $savedCount = 0;
        
        foreach ($products as $product) {
            // Проверяем, существует ли уже товар с таким названием
            $existingElement = $this->findExistingElement($product['NAME']);
            
            if (!$existingElement) {
                $el = new CIBlockElement;
                
                $fields = [
                    'IBLOCK_ID' => $this->iblockId,
                    'NAME' => $product['NAME'],
                    'ACTIVE' => 'Y',
                    'PROPERTY_VALUES' => [
                        'PRICE' => $product['PRICE']
                    ]
                ];
                
                if ($el->Add($fields)) {
                    echo "Создан: {$product['NAME']} - {$product['PRICE']} руб.\n";
                    $savedCount++;
                } else {
                    echo "Ошибка создания: {$product['NAME']} - {$el->LAST_ERROR}\n";
                }
            } else {
                echo "Товар уже существует: {$product['NAME']}\n";
            }
        }
        
        return $savedCount;
    }
    
    private function findExistingElement($name) {
        CModule::IncludeModule('iblock');
        
        $res = CIBlockElement::GetList(
            [],
            [
                'IBLOCK_ID' => $this->iblockId,
                'NAME' => $name
            ],
            false,
            ['nTopCount' => 1],
            ['ID']
        );
        
        if ($element = $res->Fetch()) {
            return $element['ID'];
        }
        
        return false;
    }
}

// Запуск тестового парсера
echo "Добавление тестовых смартфонов в инфоблок 4...\n";
$parser = new TestSmartphoneParser();
$count = $parser->run();
echo "\nГотово! Добавлено товаров: {$count}\n";
?>