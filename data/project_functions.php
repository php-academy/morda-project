<?php
/**
 * Created by PhpStorm.
 * User: nasedkin
 * Date: 27.09.15
 * Time: 14:48
 */

function get_curr_city() {
    if( isset($_GET['curr_city']) ) {
        $currentCity = $_GET['curr_city'];
    } else {
        if( isset($_COOKIE['curr_city']) ) {
            $currentCity = $_COOKIE['curr_city'];
        } else {
            $currentCity = 'nsk';
        }
    }
    return $currentCity;
}

function set_curr_city( $curr_city ) {
    setcookie('curr_city', $curr_city, time()+ 60*60*24*30, '/');
}

function get_city_data_by_code($cityCode) {

    $dbCity = require(__DIR__ . '/dbCity.php');

    foreach( $dbCity as $cityData ) {
        if( $cityData['code'] == $cityCode ) {
            return $cityData;
        }
    }
    return false;
}

function get_city_name_by_code( $cityCode) {
    $cityData = get_city_data_by_code($cityCode);
    if( $cityData ) {
        return $cityData['name'];
    } else {
        return false;
    }
}

function get_autos($currCity) {

    $dbAuto = require(__DIR__ . '/dbAuto.php');

    $result = array();
    foreach( $dbAuto as $autoData ) {
        if( $autoData['cityCode'] == $currCity ) {
            $result[] = $autoData;
        }
    }
    return $result;
}

function get_user_by_login($login) {
    $users = require(__DIR__.'/dbUsers.php');
    if( isset($users[$login]) ) {
        return $users[$login];
    } else {
        return false;
    }
}

function login() {

    $_SESSION['login']['error'] = 'Неверные логин/пароль';

    if( isset($_POST['login']) && isset($_POST['password']) ) {
        $login = $_POST['login'];
        $password = $_POST['password'];

        if (
            preg_match("/^[a-zA-Z0-9]{3,30}$/", $login) &&
            preg_match("/^[a-zA-Z0-9]{6,30}$/", $password)
        ) {
            if (
                $user = get_user_by_login($login)
            ) {
                if( $user['salt_password'] == md5($password . $user['salt']) ) {
                    $hash = cookie_hash(
                        $user['salt_password'] ,
                        $user['salt']
                    );
                    unset($_SESSION['login']['error']);
                    setcookie("user", $login . ':' . $hash, time() + 60 * 60 * 24 * 30, '/');
                }
            }
        }
    }
}

function authorize() {

    if( isset($_COOKIE['user']) ) {
        $userCookie = $_COOKIE['user'];
        $arUserCookie = explode(':', $userCookie);
        $login = $arUserCookie[0];
        $cookieHash = $arUserCookie[1];

        if( $user = get_user_by_login($login) ) {
            $userHash =  cookie_hash(
                $user['salt_password'] ,
                $user['salt']
            );

            if( $userHash == $cookieHash ) {
                return $login;
            }
        }
    }
    return false;
}

function logout() {
    setcookie('user', '', time() - 60*60*24, '/');
}

function cookie_hash($saltPassword, $salt) {
    return md5(
        $_SERVER['REMOTE_ADDR'] .
        $_SERVER['HTTP_USER_AGENT'] .
        date('Y-m-d') .
        $saltPassword .
        $salt
    );
}