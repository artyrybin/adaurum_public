<?php
    session_start();

    $GLOBALS['E_SITE_TITLE'] = "-";

    require $_SERVER['DOCUMENT_ROOT'] . '/engine/.settings.php';

    const INDEX = true;

    if(DEBUG == "Y") {
        error_reporting( E_ALL );
        ini_set("display_errors", 1);
    }

    spl_autoload_register(function($class) {
        $classesDir = $_SERVER['DOCUMENT_ROOT'].'/engine/classes/';
        $fn = $classesDir . $class . '.php';
        if (file_exists($fn)) require $fn;
    });

    try {
        $sql = new SafeMySQL([
            "user" => DB_USER_NAME,
            "db" => DB_DATA_BASE,
            "pass" => DB_PASSWORD,
            "charset" => DB_CHARSET,
        ]);
    } catch (Exception $e) {
        var_dump($e);
        die();
    }

    @require($_SERVER['DOCUMENT_ROOT'] . '/.access.php');
    Core::CheckAccess($access);
    Core::StartSession();

    global $USER;

    $USER = User::getInstance();

    $buffer = new Buffer();
    $buffer->startBuffering();