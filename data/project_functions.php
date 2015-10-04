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

# Функция поиска пользователя по логину:
# false|true
/*function getAuthFromCookie() {
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
            return true;
        }
    }
}*/


# Функция поиска пользователя по логину:
# false|array
# Функция выполняет следующее:
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