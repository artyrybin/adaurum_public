<?php
    include($_SERVER['DOCUMENT_ROOT'] . '/engine/prologue.php');

    global $USER;

    if(!$USER->isAuthorized()) die();

    # Ajax actions

    if($_POST['ACTION'] == 'DELETE_COMPANY') {
        Companies::getInstance()->Delete($_POST['COMPANY_ID']);
    }