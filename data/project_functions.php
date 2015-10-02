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

function get_city_data_by_code($dbCity, $cityCode) {
    foreach( $dbCity as $cityData ) {
        if( $cityData['code'] == $cityCode ) {
            return $cityData;
        }
    }
    return false;
}

function get_city_name_by_code($dbCity, $cityCode) {
    $cityData = get_city_data_by_code($dbCity,$cityCode);
    if( $cityData ) {
        return $cityData['name'];
    } else {
        return false;
    }
}

function filter($dbAuto, $currCity) {
    $result = array();
    foreach( $dbAuto as $autoData ) {
        if( $autoData['cityCode'] == $currCity ) {
            $result[] = $autoData;
        }
    }
    return $result;
}