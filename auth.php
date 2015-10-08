<?php
session_start();
date_default_timezone_set('America/Los_Angeles');
/**
 * Created by PhpStorm.
 * User: nasedkin
 * Date: 01.10.15
 * Time: 20:17
 */
require(__DIR__ . '/data/project_functions.php');
if( isset($_GET['action']) && $_GET['action'] == 'login' ) {
    login();
} elseif( isset($_GET['action']) && $_GET['action'] == 'logout' ) {
    logout();
}
header('Location: /');