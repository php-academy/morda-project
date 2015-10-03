<?php
/**
 * Created by PhpStorm.
 * User: nasedkin
 * Date: 01.10.15
 * Time: 20:17
 */

$users = require(__DIR__ . '/data/dbUsers.php');

if( isset($_POST['login']) && isset($_POST['password']) ) {
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
            setcookie( "user", $login . ':' . md5($password) , time() + 60*60*24*30, '/');
        }
    }
}
header('Location: /');