<?php
    include($_SERVER['DOCUMENT_ROOT'] . '/engine/prologue.php');

    global $USER;

    if(!$USER->isAuthorized()) die();

    if(isset($_POST['company_id']))
        $sendComment = Companies::getInstance()->SendComment($_POST);

    # Reinit component

    Core::GetComponent('company.comments', 'main', [
        'COMPANY_ID' => $_POST['company_id'],
        'FIELD_TYPE' => $_POST['field_type'],
        'AJAX_RESULT' => $sendComment
    ]);