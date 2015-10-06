<?php
session_start();
error_reporting(E_ALL); # Замечать все ошибки.
ini_set("display_errors", 1);

$users = require(__DIR__ . '/data/dbUsers.php');


### LogIn: ###
if ( isset($_POST['login']) && isset($_POST['password']) ) {
    $login = $_POST['login'];  # Логин.
    $password = $_POST['password']; # Пароль.
    $userIP = $_SERVER["REMOTE_ADDR"];  # IP пользователя.
    $currDate = date('D,M,Y');   # Текущаа дата.
    $userAgent = $_SERVER["HTTP_USER_AGENT"]; # Браузер.
    # Если логин и пароль имеют допустимы формат ввода:
    if (
         preg_match("/^[a-zA-Z0-9]{3,30}$/", $_POST["login"]) &&
         preg_match("/^[a-zA-Z0-9]{6,30}$/", $_POST["password"])
       )
    {
        $_SESSION['login'] = $login;
        # Если логин существует в базе, проверяем пароль:
        if ( isset($users[$login]) )
        {
            $salt_password = md5( $users[$login]['salt'].$_POST["password"] );
            if ( $users[$login]['salt_password'] == $salt_password )
            {
                setcookie( "user", $login . ':' . $salt_password . md5($userIP) . md5($userAgent) . md5($currDate), time() + 60*60*24*30, '/');
            }
            else
            {
                $_SESSION['login_error']  = 'Неверный пароль';
            }
        }
        # В противном случае выдаем ошибку:
        else $_SESSION['login_error'] = 'Нет аккаунта с таким логином';
    }
    elseif ( ( empty($login) && empty($password) ) || empty($login) )
    {
        $_SESSION['login_error'] = 'Введите логин и пароль';
        $_SESSION['login'] = $login;
    }
    elseif ( empty($password) ) {
        $_SESSION['login_error'] = 'Введите пароль';
        $_SESSION['login'] = $login;
    }
    else {
        $_SESSION['login_error'] = 'Недопустимый ввод';
        $_SESSION['login'] = $login;
    }
}

### LogOut: ###
if ( isset($_GET['action']) ) {
    $action = $_GET['action'];
    if ($action == 'logout') {
        setcookie( "user", '', time() - 10, '/');
    }
}

# Refresh IndexPage:
header('location: /');