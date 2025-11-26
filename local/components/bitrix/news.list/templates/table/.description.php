<?php
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

$arComponentDescription = [
    "NAME" => "Таблица цен конкурентов",
    "DESCRIPTION" => "Вывод таблицы с ценами конкурентов с использованием Kendo UI",
    "ICON" => "/images/icon.gif",
    "PATH" => [
        "ID" => "custom",
        "NAME" => "Кастомные компоненты",
        "CHILD" => [
            "ID" => "prices",
            "NAME" => "Цены"
        ]
    ],
    "CACHE_PATH" => "Y",
    "COMPLEX" => "N"
];
?>