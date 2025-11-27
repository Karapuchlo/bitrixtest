<?php
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

/**
 * @global CMain $APPLICATION
 */

global $APPLICATION;

// Формируем JSON-LD структуру Schema.org
$jsonLd = [
    '@context' => 'https://schema.org',
    '@type' => 'BreadcrumbList',
    'itemListElement' => []
];

foreach ($arResult as $index => $item) {
    $jsonLd['itemListElement'][] = [
        '@type' => 'ListItem',
        'position' => $index + 1,
        'name' => $item['TITLE'],
        'item' => $item['LINK'] ? 'https://' . $_SERVER['HTTP_HOST'] . $item['LINK'] : 'https://' . $_SERVER['HTTP_HOST'] . '/'
    ];
}

// Добавляем JSON-LD в head страницы
$jsonLdString = '<script type="application/ld+json">' . 
    json_encode($jsonLd, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT) . 
    '</script>';

$GLOBALS['APPLICATION']->AddHeadString($jsonLdString);


//delayed function must return a string
if(empty($arResult))
	return "";

$strReturn = '';

//we can't use $APPLICATION->SetAdditionalCSS() here because we are inside the buffered function GetNavChain()
$css = $APPLICATION->GetCSSArray();
if(!is_array($css) || !in_array("/bitrix/css/main/font-awesome.css", $css))
{
	$strReturn .= '<link href="'.CUtil::GetAdditionalFileURL("/bitrix/css/main/font-awesome.css").'" type="text/css" rel="stylesheet" />'."\n";
}

$strReturn .= '<div class="bx-breadcrumb" itemprop="http://schema.org/breadcrumb" itemscope itemtype="http://schema.org/BreadcrumbList">';

$itemSize = count($arResult);
for($index = 0; $index < $itemSize; $index++)
{
	$title = htmlspecialcharsex($arResult[$index]["TITLE"]);
	$arrow = ($index > 0? '<i class="bx-breadcrumb-item-angle fa fa-angle-right"></i>' : '');

	if($arResult[$index]["LINK"] <> "" && $index != $itemSize-1)
	{
		$strReturn .=  $arrow.'
			<div class="bx-breadcrumb-item" id="bx_breadcrumb_'.$index.'" itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem">
				<a class="bx-breadcrumb-item-link" href="'.$arResult[$index]["LINK"].'" title="'.$title.'" itemprop="item">
					<span class="bx-breadcrumb-item-text" itemprop="name">'.$title.'</span>
				</a>
				<meta itemprop="position" content="'.($index + 1).'" />
			</div>';
	}
	else
	{
		$strReturn .= $arrow.'
			<div class="bx-breadcrumb-item">
				<span class="bx-breadcrumb-item-text">'.$title.'</span>
			</div>';
	}
}

$strReturn .= '</div>';

return $strReturn;
