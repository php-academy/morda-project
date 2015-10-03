<?php
date_default_timezone_set('America/Los_Angeles');
/**
 * Created by PhpStorm.
 * User: nasedkin
 * Date: 01.10.15
 * Time: 20:17
 */
$users = require(__DIR__ . '/data/dbUsers.php');

if( isset($_GET['action']) && $_GET['action'] == 'login' ) {
    if( isset($_POST['login']) && isset($_POST['password']) ) {
        $login = $_POST['login'];
        $password = $_POST['password'];

        if (
            preg_match("/^[a-zA-Z0-9]{3,30}$/", $login) &&
            preg_match("/^[a-zA-Z0-9]{6,30}$/", $password)
        ) {
            if (
                isset($users[$login])
            ) {
                if( $users[$login]['salt_password'] == md5($password . $users[$login]['salt']) ) {
                    $hash = md5(
                        $_SERVER['REMOTE_ADDR'] .
                        $_SERVER['HTTP_USER_AGENT'] .
                        date('Y-m-d') .
                        $users[$login]['salt_password'] .
                        $users[$login]['salt']
                    );

                    setcookie("user", $login . ':' . $hash, time() + 60 * 60 * 24 * 30, '/');
                }
            }
        }
    }
} elseif( isset($_GET['action']) && $_GET['action'] == 'logout' ) {
    setcookie('user', '', time() - 60*60*24, '/');
}
header('Location: /');