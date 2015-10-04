<?php
/**
 * Created by PhpStorm.
 * User: nasedkin
 * Date: 27.09.15
 * Time: 14:48
 */

define('EARTH_RADIUS', 6372795);

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

function calculateTheDistance ($lat1, $long1, $lat2, $long2) {

    // перевести координаты в радианы
    $lat1 = $lat1 * M_PI / 180;
    $lat2 = $lat2 * M_PI / 180;
    $long1 = $long1 * M_PI / 180;
    $long2 = $long2 * M_PI / 180;

    // косинусы и синусы широт и разницы долгот
    $cl1 = cos($lat1);
    $cl2 = cos($lat2);
    $sl1 = sin($lat1);
    $sl2 = sin($lat2);
    $delta = $long2 - $long1;
    $cdelta = cos($delta);
    $sdelta = sin($delta);

    // вычисления длины большого круга
    $y = sqrt(pow($cl2 * $sdelta, 2) + pow($cl1 * $sl2 - $sl1 * $cl2 * $cdelta, 2));
    $x = $sl1 * $sl2 + $cl1 * $cl2 * $cdelta;

    //
    $ad = atan2($y, $x);
    $dist = $ad * EARTH_RADIUS;

    return $dist;
}

function filter(array $dbAuto, array $dbCity,$currCityCode='nsk',$needDistance,$is4wd,$isAutoTrans){

    $ar_city=array();
    $ar_auto=array();

    $ar_city=distance_cities($dbCity,$currCityCode,$needDistance);
    # насколько я увидел $currCityCode - не попадает в список городов, по которым происходит поиск
    # нужно искать в текущем и всех остальных, которые удовлетворяют расстоянию
    if(!empty($ar_city)){
        foreach($dbAuto as $auto){
            if(in_array($auto['cityCode'], $ar_city)){
                if($auto['model']['is4wd']==$is4wd && $auto['model']['isAutoTrans']==$isAutoTrans){
                    $ar_auto[]=$auto;
                }
            }
        }
    }
    else{
        return false;
    }
    if(empty($ar_auto)){
        return false;
    }
    return $ar_auto;
}


function distance_cities($dbCity,$currCityCode,$needDistance){

    $lat_curr=0;
    $long_curr=0;
    $lat_other_city=0;
    $long_other_city=0;
    $distance_to_city=array();
    $distance=0;
    $ar_city=array();

    //координаты текущего города
    foreach($dbCity as $city){
        if($currCityCode==$city['code']){
            $lat_curr=$city['coord']['latitude'];
            $long_curr=$city['coord']['longitude'];
            break;
            # как только нашли можно остановить обход цикла - break;
        }
    }
    //координаты других городов
    foreach($dbCity as $codeCity=>$city){
        $lat_other_city=$city['coord']['latitude'];
        $long_other_city=$city['coord']['longitude'];

        $distance=calculateTheDistance($lat_curr, $long_curr, $lat_other_city, $long_other_city)/1000;

        if($distance<$needDistance){
            $ar_city[]=$codeCity;
            //$ar_city[$codeCity]=$distance;
        }
    }
    return $ar_city;
}