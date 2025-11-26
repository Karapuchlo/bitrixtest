<?php
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

$arComponentParameters = [
    "PARAMETERS" => [
        "IBLOCK_ID" => [
            "PARENT" => "BASE",
            "NAME" => "ID инфоблока",
            "TYPE" => "STRING",
            "DEFAULT" => "4"
        ],
        "CACHE_TIME" => [
            "DEFAULT" => 3600
        ],
        "SHOW_EXPORT" => [
            "NAME" => "Показывать экспорт в Excel",
            "TYPE" => "CHECKBOX",
            "DEFAULT" => "Y"
        ]
    ]
];
?>