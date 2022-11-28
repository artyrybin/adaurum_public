<?php
    if (ini_get('short_open_tag') == 0 && mb_strtoupper(ini_get('short_open_tag')) != 'ON')
        die('short_open_tag is off');

    @include($_SERVER['DOCUMENT_ROOT'] . '/engine/prologue.php');
    @include($_SERVER['DOCUMENT_ROOT'] . '/template/header.php');