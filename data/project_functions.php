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

define('EARTH_RADIUS', 6372795);
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


function filter($dbAuto,$cities,$currentCity,$needDistance,$is4wd,$isAutoTrans,$price_ot,$price_do,$year_ot,$year_do){
$ar_auto = array();
    $ar_city = array();
    $ar_city=distance_cities($cities,$currentCity,$needDistance);
    foreach($dbAuto as $dataauto){

            if ((in_array($dataauto['cityCode'],$ar_city)
                && (!isset($is4wd) || ($dataauto['model']['is4wd'] == $is4wd))
                && (!isset($isAutoTrans) || ($dataauto['model']['isAutoTrans'] == $isAutoTrans))
                && (!isset($price_ot) || ($dataauto['price']['value'] > ($price_ot * 1000)))
                && (!isset($price_do) || ($dataauto['price']['value'] < ($price_do * 1000)))
                && (!isset($year_ot) || ($dataauto['model']['year'] >= $year_ot))
                && (!isset($year_do) || ($dataauto['model']['year'] <= $year_do))
                && (!isset($needDistance) || ($needDistance <= $year_do))
            )) {
                $ar_auto[] = $dataauto;
            }

    }
    return $ar_auto;
}
   /* $ar_city=array();
    $ar_auto=array();

    $ar_city=distance_cities($cities,$currentCity,$needDistance);
    if(!empty($ar_city)){
        foreach($ar_city as $cityCode){
            foreach($dbAuto as $auto){
                if(in_array($auto['cityCode'],$ar_city)){
                    if((!isset($is4wd) || ($auto['model']['is4wd']==$is4wd))
                    && (!isset($isAutoTrans) || ($auto['model']['isAutoTrans']==$isAutoTrans))
                    && (!isset($price_ot) || ($auto['price']['value']>=$price_ot*1000))
                    && (!isset($price_do) || ($auto['price']['value']<=$price_do*1000))
                    && (!isset($year_ot) || ($auto['model']['year']>=$year_ot))
                    && (!isset($year_do) || ($auto['model']['year']<=$year_do))){
                        $ar_auto[]=$auto;
                    }
                }
            }
        }


    }
    else{
        $ar_auto['error']='Машины не найдены';
    }
    if(empty($ar_auto)){
        $ar_auto['error']='Машины не найдены';
    }
    return $ar_auto;*/


function distance_cities($cities,$currentCity,$needDistance){
    $ar_city = array();
    foreach ($cities as $name => $val) {
 //       $citycode=$name;
        if (calculateTheDistance($cities, $currentCity, $name) <= $needDistance) {
            $ar_city[] = $name;
        }
    }
    return $ar_city;
}
/*
function getuser($dbusers,$login){
    $userdata = array();
    if (in_array($dbusers, $login)){
        foreach($dbusers[$login] as $data){
            $userdata[] = $data;
        }
    }
    else
return $userdata;
}
/*
function filter($dbAuto, $currCity) {
        $result = array();
        foreach($dbAuto as $autoData) {
                if( $autoData['cityCode'] == $currCity ) {
                        $result[] = $autoData;
                    }
    }
    return $result;
}
*/