<?php
    if(INDEX !== true) die();

    $companyInfo = Companies::getInstance()->getByXML($_GET['xml_id']);
    Core::SetPageTitle("Компания «{$companyInfo['name']}»");

    // Создание массива полей

    $thisFields  = [];
    $fieldTypes  = [];
    $fieldsArray = [];

    $getTypes     = Companies::getInstance()->getFieldTypes(true);
    $getFields    = Companies::getInstance()->getCompanyFields($companyInfo['id']);

    $thisFields['TYPES']  = $getTypes;
    $thisFields['VALUES'] = $getFields;

    $data['ITEM'] = $companyInfo;
    $data['FIELDS'] = $thisFields;
    $data['NOTES'] = $thisNotes;