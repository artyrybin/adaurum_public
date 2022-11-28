<?php
    include($_SERVER['DOCUMENT_ROOT'] . '/engine/prologue.php');

    global $USER;

    if($USER->isAuthorized()) die('Access denied');

    # Ajax actions

    $ajaxResult = [];
    if(isset($_POST['FORM_ID'])) {
        $ajaxResult = $USER->createUser(
            $_POST,
            [
                // Field id => field name
                'username'        => 'Username',
                'password'        => 'Password',
                'password_repeat' => 'Password repeat'
            ]
        );
    }

    # Reinit component

    Core::GetComponent('user.reg', 'main', [
        'COMPANY_ID' => $_POST['COMPANY_ID'],
        'AJAX_RESULT' => $ajaxResult
    ]);