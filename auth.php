<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 01.10.2015
 * Time: 10:18
 */
$users = include(__DIR__ . '/data/dbusers.php');

if (isset($_POST['login']) && isset($_POST['password'])){
    $login = $_POST['login'];
    $password = $_POST['password'];
    if (
        preg_match("/^[a-zA-Z0-9]{3,30}$/", $_POST["login"]) &&
        preg_match("/^[a-zA-Z0-9]{6,30}$/", $_POST["password"])

    ) { if (isset($users[$login])&&($users[$login]['password'] == $password))
        {
            setcookie( "user", $login . ':' . md5($password), time() + 60*60*24*30, '/');
        }

    }


}
header('location: /');