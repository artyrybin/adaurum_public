<?php
    include $_SERVER['DOCUMENT_ROOT'] . '/engine/prologue.php';

    global $USER;

    if(!$USER->isAuthorized()) die('Access denied');

    # Ajax actions

    if(isset($_POST['FORM_ID'])) {
        // Update company info
        $update = Companies::getInstance()->Update($_POST);
    }

    # Reinit component

    Core::GetComponent('edit.form', 'main', [
        'COMPANY_ID' => $_POST['COMPANY_ID'],
        'AJAX_RESULT' => $update
    ]);