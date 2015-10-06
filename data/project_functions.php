<?php
/**
 * Created by PhpStorm.
 * User: nasedkin
 * Date: 27.09.15
 * Time: 14:48
 */
# Функция определения текущего города по куке:
# - проверяет переданный GET-параметр с кодом города;
# - если код города не передан, то проверят существование куки с кодом города;
# - если куки нет, то текущий код города (по умолчанию) - Новосибирск;
# - записывает в куку и возвращает значение кода города;
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
    setcookie('curr_city', $currentCity, time()+ 60*60*24*30, '/');
    return $currentCity;
}

# Функция авторизации пользователя по Куке:
# - подключает внутри себя базу пользователей;
# - проверяет наличие куки с данными авторизации пользователя;
# - если такой пользователь найден в базе пользователей и его данные авторизации верны,то - true;
# - иначе возвращает - значение false;
function getAuthFromCookie() {
    $users = require(__DIR__ . '/dbUsers.php');
    $isUserAuth = false;
    if ( isset($_COOKIE['user']) ) {
        $userCookie = $_COOKIE['user'];
        $arUserCookie = explode(':', $userCookie);
        $login = $arUserCookie[0];
        $hash = $arUserCookie[1];
        # Проверяем соответствие логина и пароля из куки с масивом пользователей:
        if (
            isset($users[$login]) &&
            ( $hash == $users[$login]['salt_password'] . md5($_SERVER["REMOTE_ADDR"]) . md5($_SERVER["HTTP_USER_AGENT"]) . md5(date('D,M,Y')) )
        ) {
            $isUserAuth = true;
        }
    }
    return $isUserAuth;
}

# Функция поиска пользователя по логину:
# false|array
# - подключает внутри себя базу пользователей;
# - проверяет наличие пользователя с указанным логином;
# - если пользователь найден возвращает массив его данных;
# - иначе возвращает признак отсутствия пользователя - значение false;
function getUserByLogin($login) {
    $users = require(__DIR__ . '/dbUsers.php');
    if ( isset($users[$login]) ) {
        $currUser = $users[$login];
    }
    else $currUser = false;
    return $currUser;
}