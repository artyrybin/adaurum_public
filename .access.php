<?php
    /**
     * empty = all users
     * NU = not authorized users
     * U = authorized users
     * A = authorized users with admin access
     */

    $access = [
        '/' => '',
        '/auth' => 'NU',
        '/reg'  => 'NU',
    ];