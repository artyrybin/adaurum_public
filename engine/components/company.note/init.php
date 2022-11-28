<?php
    if(INDEX !== true) die();
    global $USER;

    $userIds = [];

    $thisNotes = Companies::getInstance()->getNotes($data['COMPANY_ID']);

    foreach($thisNotes as $fn) {
        foreach($fn as $note) {
            $userIds[] = $note['user_id'];
        }
    }

    $userIds = array_unique($userIds);
    $thisUsers = User::getInstance()->getList($userIds);

    $data['NOTES'] = $thisNotes;
    $data['USERS'] = $thisUsers;
    $data['COMPANY'] = Companies::getInstance()->getByID($data['COMPANY_ID']);