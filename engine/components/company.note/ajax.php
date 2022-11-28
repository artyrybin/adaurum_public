<?php
    include($_SERVER['DOCUMENT_ROOT'] . '/engine/prologue.php');

    global $USER;

    if(!$USER->isAuthorized()) die();

    if(isset($_POST['company_id']))
        $sendNote = Companies::getInstance()->SendNote($_POST);

    # Reinit component

    Core::GetComponent('company.note', 'main', [
        'COMPANY_ID' => $_POST['company_id'],
        'FIELD_TYPE' => $_POST['field_type'],
        'AJAX_RESULT' => $sendNote
    ]);