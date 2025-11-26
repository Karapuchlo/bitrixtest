<?php
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

?>

<?php
require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/header.php");

$APPLICATION->SetTitle("Сравнение цен с конкурентами");


if (!CModule::IncludeModule("iblock")) {
    ShowError("Модуль инфоблоков не установлен");
    require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/footer.php");
    exit();
}
?>

<!-- Подключаем Kendo UI -->
<link rel="stylesheet" href="https://kendo.cdn.telerik.com/2023.3.1114/styles/kendo.default-main.min.css" />
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://kendo.cdn.telerik.com/2023.3.1114/js/kendo.all.min.js"></script>

<?

$iblockId = 4;

$res = CIBlockElement::GetList(
    ['NAME' => 'ASC'],
    ['IBLOCK_ID' => $iblockId],
    false,
    false,
    ['ID', 'NAME', 'PROPERTY_PRICE', 'PROPERTY_COMPETITOR_PRICE']
);

$products = [];
$competitorDomains = [];

while ($element = $res->Fetch()) {
    $product = [
        'id' => $element['ID'],
        'name' => $element['NAME'],
        'our_price' => $element['PROPERTY_PRICE_VALUE'],
        'competitors' => []
    ];


    $dbProps = CIBlockElement::GetProperty(
        $iblockId,
        $element['ID'],
        [],
        ['CODE' => 'COMPETITOR_PRICE']
    );

    while ($arProp = $dbProps->Fetch()) {
        $domain = $arProp['DESCRIPTION'];
        $price = $arProp['VALUE'];

        if (!empty($domain) && !empty($price)) {
            $product['competitors'][$domain] = floatval($price);
            if (!in_array($domain, $competitorDomains)) {
                $competitorDomains[] = $domain;
            }
        }
    }

    $products[] = $product;
}


$columns = [
    ['field' => 'name', 'title' => 'Товар', 'width' => 200]
];

foreach ($competitorDomains as $domain) {
    $columns[] = [
        'field' => $domain,
        'title' => $domain,
        'width' => 150,
        'template' => '#= ' . $domain . ' ? kendo.toString(' . $domain . ', "n2") + " ₽" : "-" #'
    ];
}

$columns[] = [
    'field' => 'our_price', 
    'title' => 'Наша цена', 
    'width' => 150,
    'template' => '#= our_price ? kendo.toString(our_price, "n2") + " ₽" : "-" #'
];
?>

<h1>Сравнение цен с конкурентами</h1>

<div id="grid"></div>

<script>
    $(document).ready(function () {
        var productsData = <?= CUtil::PhpToJSObject($products) ?>;

        // Преобразуем данные для Kendo
        var gridData = productsData.map(function(product) {
            var dataItem = {
                id: product.id,
                name: product.name,
                our_price: product.our_price
            };
            
            // Добавляем цены конкурентов как отдельные поля
            for (var domain in product.competitors) {
                dataItem[domain] = product.competitors[domain];
            }
            
            return dataItem;
        });

        $("#grid").kendoGrid({
            dataSource: {
                data: gridData,
                pageSize: 10,
                schema: {
                    model: {
                        fields: {
                            id: { type: "number" },
                            name: { type: "string" },
                            our_price: { type: "number" }
                        }
                    }
                }
            },
            height: 900,
            sortable: true,
            pageable: {
                pageSizes: [20, 50, 100],
                buttonCount: 5
            },
            toolbar: ["excel"],
            excel: {
                fileName: "CompetitorPrices.xlsx",
                filterable: true,
                allPages: true
            },
            columns: <?= CUtil::PhpToJSObject($columns) ?>
        });
    });
</script>


<?php if (isset($arResult['PRODUCTS']) && is_array($arResult['PRODUCTS']) && !empty($arResult['PRODUCTS'])): ?>
<table border="1" cellpadding="10" cellspacing="0" style="border-collapse: collapse; width: 100%;">
    <thead style="background: #f5f5f5;">
        <tr>
            <th>Товар</th>
            <th>Наша цена</th>
            <th>DNS-Shop</th>
            <th>Citilink</th>
            <th>MVideo</th>
        </tr>
    </thead>
    <tbody>
        <?/*<pre>
            <?var_dump($arResult['PRODUCTS'])?>
        </pre>*/?>
        <?php foreach ($arResult['PRODUCTS'] as $product): ?>
        <tr>
            <td><strong><?=htmlspecialchars($product['NAME'])?></strong></td>
            <td style="text-align: center; background: #e3f2fd; font-weight: bold;">
                <?=number_format($product['OUR_PRICE'], 0, '', ' ')?> ₽
            </td>
            <td style="text-align: center; color: <?=$product['COMPETITOR_PRICES']['dns-shop.ru'] < $product['OUR_PRICE'] ? 'green' : 'red'?>;">
                <?=number_format($product['COMPETITOR_PRICES']['dns-shop.ru'], 0, '', ' ')?> ₽
                <br>
                <small>
                    <?php if ($product['COMPETITOR_PRICES']['dns-shop.ru'] < $product['OUR_PRICE']): ?>
                        ↓ <?=number_format($product['OUR_PRICE'] - $product['COMPETITOR_PRICES']['dns-shop.ru'], 0, '', ' ')?> ₽
                    <?php else: ?>
                        ↑ <?=number_format($product['COMPETITOR_PRICES']['dns-shop.ru'] - $product['OUR_PRICE'], 0, '', ' ')?> ₽
                    <?php endif; ?>
                </small>
            </td>
            <td style="text-align: center; color: <?=$product["COMPETITOR_PRICES"]['citilink.ru'] < $product['OUR_PRICE'] ? 'green' : 'red'?>;">
                <?=number_format($product["COMPETITOR_PRICES"]['citilink.ru'], 0, '', ' ')?> ₽
                <br>
                <small>
                    <?php if ($product["COMPETITOR_PRICES"]['citilink.ru'] < $product['OUR_PRICE']): ?>
                        ↓ <?=number_format($product['OUR_PRICE'] - $product["COMPETITOR_PRICES"]['citilink.ru'], 0, '', ' ')?> ₽
                    <?php else: ?>
                        ↑ <?=number_format($product["COMPETITOR_PRICES"]['citilink.ru'] - $product['OUR_PRICE'], 0, '', ' ')?> ₽
                    <?php endif; ?>
                </small>
            </td>
            <td style="text-align: center; color: <?=$product["COMPETITOR_PRICES"]['mvideo.ru'] < $product['OUR_PRICE'] ? 'green' : 'red'?>;">
                <?=number_format($product["COMPETITOR_PRICES"]['mvideo.ru'], 0, '', ' ')?> ₽
                <br>
                <small>
                    <?php if ($product["COMPETITOR_PRICES"]['mvideo.ru'] < $product['OUR_PRICE']): ?>
                        ↓ <?=number_format($product['OUR_PRICE'] - $product["COMPETITOR_PRICES"]['mvideo.ru'], 0, '', ' ')?> ₽
                    <?php else: ?>
                        ↑ <?=number_format($product["COMPETITOR_PRICES"]['mvideo.ru'] - $product['OUR_PRICE'], 0, '', ' ')?> ₽
                    <?php endif; ?>
                </small>
            </td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>
<?php else: ?>
    <div style="padding: 20px; background: #fff3cd; border: 2px solid #ffc107;">
        <h3>❌ Данные не передались в шаблон</h3>
        <p>Переменная $arResult в шаблоне: <?=isset($arResult) ? 'установлена' : 'не установлена'?></p>
        <p>PRODUCTS: <?=isset($arResult['PRODUCTS']) ? 'установлен' : 'не установлен'?></p>
    </div>
<?php endif; ?>
<pre>
<?php
//var_dump($arResult);
?>
</pre>