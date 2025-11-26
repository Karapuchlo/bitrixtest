<?php
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();
?>

<div class="price-compare-container">
    <div class="header">
        <h1>💰 Мониторинг цен конкурентов</h1>
        <div class="stats">
            <span class="stat">Товаров: <strong><?=count($arResult['PRODUCTS'])?></strong></span>
            <span class="stat">Конкурентов: <strong><?=count($arResult['COMPETITORS'])-1?></strong></span>
            <span class="stat">Обновлено: <strong><?=$arResult['LAST_UPDATE']?></strong></span>
        </div>
    </div>

    <div class="prices-table-container">
        <table class="prices-table">
            <thead>
                <tr>
                    <th class="product-col">Товар</th>
                    <th class="price-col our-price">Наша цена</th>
                    <?php foreach ($arResult['COMPETITORS'] as $competitor => $code): ?>
                        <?php if ($competitor !== 'Наш магазин'): ?>
                            <th class="price-col competitor-price"><?=htmlspecialchars($competitor)?></th>
                        <?php endif; ?>
                    <?php endforeach; ?>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($arResult['PRODUCTS'] as $product): ?>
                    <tr>
                        <td class="product-name">
                            <strong><?=htmlspecialchars($product['NAME'])?></strong>
                        </td>
                        <td class="our-price-cell">
                            <span class="price-value"><?=number_format($product['OUR_PRICE'], 0, '', ' ')?> ₽</span>
                        </td>
                        <?php foreach ($arResult['COMPETITORS'] as $competitor => $code): ?>
                            <?php if ($competitor !== 'Наш магазин'): ?>
                                <td class="competitor-price-cell">
                                    <?php if (isset($product['COMPETITOR_PRICES'][$competitor])): ?>
                                        <?php
                                        $price = $product['COMPETITOR_PRICES'][$competitor];
                                        $our_price = $product['OUR_PRICE'];
                                        $diff = $price - $our_price;
                                        $class = '';
                                        $icon = '';
                                        if ($diff < 0) {
                                            $class = 'cheaper';
                                            $icon = '↓';
                                        } elseif ($diff > 0) {
                                            $class = 'expensive';
                                            $icon = '↑';
                                        } else {
                                            $class = 'same';
                                            $icon = '=';
                                        }
                                        ?>
                                        <div class="price-comparison <?=$class?>">
                                            <span class="price"><?=number_format($price, 0, '', ' ')?> ₽</span>
                                            <?php if ($diff != 0): ?>
                                                <div class="difference">
                                                    <span class="icon"><?=$icon?></span>
                                                    <span class="amount"><?=number_format(abs($diff), 0, '', ' ')?> ₽</span>
                                                </div>
                                            <?php endif; ?>
                                        </div>
                                    <?php else: ?>
                                        <span class="no-data">—</span>
                                    <?php endif; ?>
                                </td>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <div class="legend">
        <div class="legend-item">
            <span class="color cheaper"></span>
            <span>Дешевле чем у нас</span>
        </div>
        <div class="legend-item">
            <span class="color expensive"></span>
            <span>Дороже чем у нас</span>
        </div>
        <div class="legend-item">
            <span class="color same"></span>
            <span>Такая же цена</span>
        </div>
    </div>
</div>

<style>
.price-compare-container {
    font-family: 'Segoe UI', Arial, sans-serif;
    margin: 0;
    background: white;
    border-radius: 8px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    overflow: hidden;
}

.header {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    padding: 30px;
}

.header h1 {
    margin: 0 0 15px 0;
    font-size: 28px;
    font-weight: 300;
}

.stats {
    display: flex;
    gap: 30px;
    flex-wrap: wrap;
}

.stat {
    font-size: 14px;
    opacity: 0.9;
}

.stat strong {
    font-weight: 600;
}

.prices-table-container {
    overflow-x: auto;
}

.prices-table {
    width: 100%;
    border-collapse: collapse;
    min-width: 800px;
}

.prices-table th {
    background: #f8f9fa;
    padding: 15px 12px;
    text-align: center;
    border-bottom: 2px solid #dee2e6;
    font-weight: 600;
    color: #495057;
}

.prices-table td {
    padding: 12px;
    border-bottom: 1px solid #e9ecef;
    vertical-align: middle;
}

.product-col {
    width: 350px;
    text-align: left;
}

.price-col {
    width: 160px;
}

.our-price {
    background: #e7f3ff !important;
    color: #0066cc;
}

.product-name {
    font-weight: 500;
    color: #2c3e50;
}

.our-price-cell {
    text-align: center;
    background: #f0f8ff;
    font-weight: 600;
    color: #0066cc;
}

.price-value {
    font-size: 16px;
}

.competitor-price-cell {
    text-align: center;
    padding: 8px 12px !important;
}

.price-comparison {
    padding: 8px 0;
}

.price {
    font-size: 15px;
    font-weight: 500;
    display: block;
}

.difference {
    margin-top: 4px;
    font-size: 12px;
}

.cheaper .price {
    color: #28a745;
    font-weight: 600;
}

.cheaper .difference {
    color: #28a745;
}

.expensive .price {
    color: #dc3545;
    font-weight: 600;
}

.expensive .difference {
    color: #dc3545;
}

.same .price {
    color: #6c757d;
}

.icon {
    font-weight: bold;
    margin-right: 2px;
}

.no-data {
    color: #6c757d;
    font-style: italic;
}

.legend {
    display: flex;
    justify-content: center;
    gap: 30px;
    padding: 20px;
    background: #f8f9fa;
    border-top: 1px solid #dee2e6;
}

.legend-item {
    display: flex;
    align-items: center;
    gap: 8px;
    font-size: 14px;
    color: #6c757d;
}

.color {
    width: 16px;
    height: 16px;
    border-radius: 3px;
    display: inline-block;
}

.color.cheaper { background: #28a745; }
.color.expensive { background: #dc3545; }
.color.same { background: #6c757d; }

/* Адаптивность */
@media (max-width: 768px) {
    .header {
        padding: 20px;
    }
    
    .header h1 {
        font-size: 24px;
    }
    
    .stats {
        gap: 15px;
    }
    
    .legend {
        flex-direction: column;
        gap: 10px;
        align-items: center;
    }
}
</style>