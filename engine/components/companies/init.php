<?php
    if(INDEX !== true) die();

    $allCompanies = Companies::getInstance()->getList();

    // Создание массива полей

    $thisFields  = [];
    $fieldTypes  = [];
    $fieldsArray = [];

    $getTypes  = Companies::getFieldTypes(true);
    $getFields = Companies::getFields();

    $thisFields['TYPES']  = $getTypes;
    $thisFields['VALUES'] = $getFields;

    $data["ITEMS"] = $allCompanies;
    $data["FIELDS"] = $thisFields;