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

function filter($dbAuto, $currCity) {
    $result = array();
    foreach( $dbAuto as $autoData ) {
        if( $autoData['cityCode'] == $currCity ) {
            $result[] = $autoData;
        }
    }
    return $result;

function calculateTheDistance ($cities,$currentCity,$cityauto) {
    if (isset($currentCity) == 0 or isset($cityauto) == 0){
        $dist = null;
    }
    else{
        $coord1 = $cities[$currentCity]['coord'];
        $lat1 = $coord1['latitude'];
        $long1 = $coord1['longitude'];
        $coord2 = $cities[$cityauto]['coord'];
        $lat2 = $coord2['latitude'];
        $long2 = $coord2['longitude'];

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
        $dist = (int)(($ad * EARTH_RADIUS)/1000);
    }
    return $dist;
}


function filter($dbAuto,$cities,$currentCity,$needDistance=500,$is4wd=true,$isAutoTrans=true){

    $ar_city=array();
    $ar_auto=array();

    $ar_city=distance_cities($cities,$currentCity,$needDistance);
    # насколько я увидел $currCityCode - не попадает в список городов, по которым происходит поиск
    # нужно искать в текущем и всех остальных, которые удовлетворяют расстоянию
    if(!empty($ar_city)){
        foreach($ar_city as $cityCode){
            foreach($dbAuto as $auto){
                if($cityCode==$auto['cityCode']){
                    if($auto['model']['is4wd']==$is4wd && $auto['model']['isAutoTrans']==$isAutoTrans){
                        $ar_auto[]=$auto['model']['name'];
                    }
                }
            }
        }
        #код поиска выше можно сократить, почитай о функции in_array
        #очень часто используемая функция
        #foreach($dbAuto as $auto){
        #        if(in_array($auto['cityCode'], $ar_city){
        #            if($auto['model']['is4wd']==$is4wd && $auto['model']['isAutoTrans']==$isAutoTrans){
        #                $ar_auto[]=$auto['model']['name'];
        #            }
        #        }
        #    }

    }
    else{
        $ar_auto['error']='Машины не найдены';
    }
    if(empty($ar_auto)){
        $ar_auto['error']='Машины не найдены';
    }
    return $ar_auto;
}

function distance_cities($cities,$currentCity,$needDistance)
{
    $ar_city = array();
    foreach ($cities as $name) {
        if (calculateTheDistance($cities, $currentCity, $name) <= $needDistance) {
            $ar_city = $name;
        }
    }
    return $ar_city;

}