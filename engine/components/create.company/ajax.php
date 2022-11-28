<?php
    include $_SERVER['DOCUMENT_ROOT'] . '/engine/prologue.php';

    global $USER;

    if(!$USER->isAuthorized()) die('Access denied');

    # Ajax actions

    if(isset($_POST['FORM_ID'])) {
        // Create company
        $requiredFields = [
            'name' => 'Company name',
            'xml_id' => 'XML ID',
        ];

        $create = Companies::getInstance()->Create($_POST, $requiredFields, $_FILES['picture']);
    }

    # Reinit component

    Core::GetComponent('create.company', 'main', [
        'AJAX_RESULT' => $create
    ]);