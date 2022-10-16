<?php
// Display all errors
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once ("../fct.inc.php");

session_start();

if (!isset($_GET['page']) && http_response_code() == 200) {
    controller("index");
} else {
    switch (getString('page')) {
        case 'auth':
            controller('auth');
            break;
        case 'post':
            controller('post');
            break;
        case 'user':
            controller('user');
            break;
        default:
            controller('error');
            break;
    }
}

