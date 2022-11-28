<?php
    if(INDEX !== true) die();

    # Cache

    global $USER;

    if($_GET['clear_cache'] == 'Y') {
        Cache::ClearCache();
    }

    Cache::CacheJS();
    Cache::CacheCSS();

    ob_end_flush();