<?php
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

$arComponentParameters = array(
    "PARAMETERS" => array(
        "IBLOCK_ID" => array(
            "PARENT" => "BASE",
            "NAME" => "ID инфоблока",
            "TYPE" => "STRING",
            "DEFAULT" => "4"
        ),
        "SHOW_EXPORT" => array(
            "PARENT" => "BASE", 
            "NAME" => "Показывать экспорт",
            "TYPE" => "CHECKBOX",
            "DEFAULT" => "Y"
        ),
        "CACHE_TIME" => array("DEFAULT" => "3600"),
    )
);
?>