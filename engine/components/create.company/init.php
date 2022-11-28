<?php
    if(INDEX !== true) die();

    $fields = [];
    $fieldTypes  = [];
    $fieldValues = [];

    # Get all fields

    $fTypes   = Companies::getInstance()->getFieldTypes();
    $cFields  = Companies::getInstance()->getCompanyFields($data['COMPANY_ID']);
    $aCompany = Companies::getInstance()->getByID($data['COMPANY_ID']);

    foreach($cFields as $field) {
        $fieldValues[$field['type']] = $field;
    }

    foreach($fTypes as $i => $type) {
        $fields[$type['xml_id']] = $type;
        $fields[$type['xml_id']]['value'] = $fieldValues[$type['xml_id']]['value'];
    }

    $data['FIELDS'] = $fields;
    $data['COMPANY'] = $aCompany;

?>