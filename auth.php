<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 01.10.2015
 * Time: 20:17
 */
require(__DIR__ . '/data/project_functions.php');
header('Content-Type: text/html; charset=utf-8');
session_start();
$users = require( __DIR__ . '/data/dbUsers.php');

if(isset($_POST['login']) && isset($_POST['password'])){
    $login = $_POST['login'];
    $password = $_POST['password'];

    if(preg_match("/^[a-zA-Z0-9]{3,30}$/", $login) && preg_match("/^[a-zA-Z0-9]{6,30}$/", $password)) {
        if(isset($users[$login]) && $users[$login]['password'] == $password) {
            setcookie( "user", $login. ':'. md5($password), time() + 60*60*24*30, '/');
        }else{
            $_SESSION['error']='Неверный логин или пароль!';
        }
    }else{
        $_SESSION['error']='Введенные данные не корректны!';
    }
}

header('Location: /');