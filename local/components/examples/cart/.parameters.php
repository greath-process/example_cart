<?php
if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

CModule::IncludeModule("iblock");

$dbIBlockType = CIBlockType::GetList(
   array("sort" => "asc"),
   array("ACTIVE" => "Y")
);
while ($arIBlockType = $dbIBlockType->Fetch())
{
   if ($arIBlockTypeLang = CIBlockType::GetByIDLang($arIBlockType["ID"], LANGUAGE_ID))
      $arIblockType[$arIBlockType["ID"]] = "[".$arIBlockType["ID"]."] ".$arIBlockTypeLang["NAME"];
}


// Формирование массива параметров https://dev.1c-bitrix.ru/learning/course/index.php?COURSE_ID=43&LESSON_ID=2132
$arComponentParameters = [
    'GROUPS' => [],

    'PARAMETERS' => [
        "ORDERS_IBLOCK_CODE" => array(
            "PARENT" => "BASE",
            "NAME" => GetMessage("ORDERS_IBLOCK_CODE"),
            "TYPE" => "LIST",
            "ADDITIONAL_VALUES" => "Y",
            "VALUES" => $arIblockType,
            "REFRESH" => "Y"
        ),
        'DOCS_TABLE_NAME' => [
            'PARENT' => 'BASE', // Базовые параметры для работы компонента
            'NAME' => GetMessage('DOCS_TABLE_NAME'),
            'TYPE' => 'STRING',
            'DEFAULT' => ''
        ],
        'COMPANIES_TABLE_NAME' => [
            'PARENT' => 'BASE',
            'NAME' => GetMessage('COMPANIES_TABLE_NAME'),
            'TYPE' => 'STRING',
            'DEFAULT' => ''
        ],
        'PAGE_SIZE' => [
            'PARENT' => 'BASE',
            'NAME' => GetMessage('PAGE_SIZE'),
            'TYPE' => 'STRING',
            'DEFAULT' => ''
        ],
        "CACHE_TIME" => array(),
        /*
        "код параметра" => array(
            "PARENT" => "код группы",  // если нет - ставится ADDITIONAL_SETTINGS
            "NAME" => "название параметра на текущем языке",
            "TYPE" => "тип элемента управления, в котором будет устанавливаться параметр",
            "REFRESH" => "перегружать настройки или нет после выбора (N/Y)",
            "MULTIPLE" => "одиночное/множественное значение (N/Y)",
            "VALUES" => "массив значений для списка (TYPE = LIST)",
            "ADDITIONAL_VALUES" => "показывать поле для значений, вводимых вручную (Y/N)",
            "SIZE" => "число строк для списка (если нужен не выпадающий список)",
            "DEFAULT" => "значение по умолчанию",
            "COLS" => "ширина поля в символах",
        ),


        "TYPE" => 'LIST' //STRING - текстовое поле ввода. / CHECKBOX - да/нет. / CUSTOM - позволяет создавать кастомные элементы управления. FILE - выбор файла.
        VALUES => array(
        "ID или код, сохраняемый в настройках компонента" => "языкозависимое описание",
        ),

        // файл 
        $ext = 'wmv,wma,flv,vp6,mp3,mp4,aac,jpg,jpeg,gif,png';
        Array(
        "PARENT" => "BASE_SETTINGS",
        "NAME" => 'Выберите файл:',
        "TYPE" => "FILE",
        "FD_TARGET" => "F",
        "FD_EXT" => $ext,
        "FD_UPLOAD" => true,
        "FD_USE_MEDIALIB" => true,
        "FD_MEDIALIB_TYPES" => Array('video', 'sound')
        );

        // COLORPICKER - указание цвета:
        $arComponentParameters["PARAMETERS"]["COLOR"]  = Array(
            "PARENT" => "BASE",
            "NAME" => 'Выбор цвета',
            "TYPE" => "COLORPICKER",
            "DEFAULT" => 'FFFF00'
        );

        */
    ],
];