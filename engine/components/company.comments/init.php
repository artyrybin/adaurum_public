<?php
    if(INDEX !== true) die();
    global $USER;

    $userIds = [];

    $thisComments = Companies::getInstance()->getCompanyComments($data['COMPANY_ID']);

    foreach($thisComments as $comment)
        $userIds[] = $comment['user_id'];

    $userIds = array_unique($userIds);
    $thisUsers = User::getInstance()->getList($userIds);

    $data['COMMENTS'] = $thisComments;
    $data['USERS'] = $thisUsers;
    $data['COMPANY'] = Companies::getInstance()->getByID($data['COMPANY_ID']);