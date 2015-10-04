<?php
error_reporting(E_ALL); # Замечать все ошибки.
ini_set("display_errors", 1);

$users = require(__DIR__ . '/data/dbUsers.php');

# LogIn:
if ( isset($_POST['login']) && isset($_POST['password']) ) {
    $login = $_POST['login'];
    $password = $_POST['password'];
    if (
        preg_match("/^[a-zA-Z0-9]{3,30}$/", $_POST["login"]) &&
        preg_match("/^[a-zA-Z0-9]{6,30}$/", $_POST["password"])
    ) {
        $salt_password = md5( $users[$login]['salt'].$_POST["password"] );
        if ( isset($users[$login]) && ($users[$login]['salt_password'] == $salt_password) ) {
            setcookie( "user", $login . ':' . $salt_password, time() + 60*60*24*30, '/');
        }
    }
}

# LogOut:
if ( isset($_GET['action']) ) {
    $action = $_GET['action'];
    if ($action == 'logout') {
        setcookie( "user", '', time() - 10, '/');
    }
}

# Refresh IndexPage:
header('location: /');