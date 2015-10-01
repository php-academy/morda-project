<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 01.10.2015
 * Time: 20:17
 */
//print_r($_POST);
$users = require( __DIR__ . '/data/dbUsers.php');
if(isset($_POST['login']) && isset($_POST['password'])){
    $login = $_POST['login'];
    $password = $_POST['password'];

    if(
    preg_match("/^[a-zA-Z0-9]{3,30}$/", $login) &&
    preg_match("/^[a-zA-Z0-9]{6,30}$/", $password)
    ) {
        if(
            isset($users[$login]) &&
            ($users[$login]['password'] == $password)
        ) {
            setcookie( "user", $login. ':'. md5($password), time() + 60*60*24*30, '/');
        }
//        $login;
//        $password;
    }
}

header('Location: /');