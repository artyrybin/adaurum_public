<?php
    include($_SERVER['DOCUMENT_ROOT'] . '/engine/header.php');
    global $USER;

    $USER->LogOut();

    header("Location: /");
