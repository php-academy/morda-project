<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 01.10.2015
 * Time: 20:17
 */
//print_r($_POST);
require(__DIR__ . '/app/core.php');
//session_start();
//require(__DIR__ . '/data/projectclasses.php');
//require(__DIR__ . '/data/project_functions.php');
if( isset($_GET['action']) && $_GET['action'] == 'login' ) {
    User::login();
    if(!User::login()){
        $_SESSION['login_error'] = 'Неверные логин и/или пароль';
    }
} elseif( isset($_GET['action']) && $_GET['action'] == 'logout' ) {
    User::logout();
}
header('Location: /');