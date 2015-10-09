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

    // ��������� ���������� � �������
    $lat1 = $lat1 * M_PI / 180;
    $lat2 = $lat2 * M_PI / 180;
    $long1 = $long1 * M_PI / 180;
    $long2 = $long2 * M_PI / 180;

    // �������� � ������ ����� � ������� ������
    $cl1 = cos($lat1);
    $cl2 = cos($lat2);
    $sl1 = sin($lat1);
    $sl2 = sin($lat2);
    $delta = $long2 - $long1;
    $cdelta = cos($delta);
    $sdelta = sin($delta);

    // ���������� ����� �������� �����
    $y = sqrt(pow($cl2 * $sdelta, 2) + pow($cl1 * $sl2 - $sl1 * $cl2 * $cdelta, 2));
    $x = $sl1 * $sl2 + $cl1 * $cl2 * $cdelta;

    //
    $ad = atan2($y, $x);
    $dist = $ad * EARTH_RADIUS;

    return $dist;
}


function filter(array $dbAuto, array $dbCity,$currCityCode,$search){

    $ar_auto=array();

    $ar_city=distance_cities($dbCity,$currCityCode,$search['distance']);
    # ��������� � ������ $currCityCode - �� �������� � ������ �������, �� ������� ���������� �����
    # ����� ������ � ������� � ���� ���������, ������� ������������� ����������
    if(!empty($ar_city)){
        foreach($dbAuto as $auto){
            $i++;
            if(in_array($auto['cityCode'], $ar_city)){
                if($auto['model']['is4wd']==$search['wd'] && $auto['model']['isAutoTrans']==$search['autotrans']){
                    $ar_auto[$i]=$auto;
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

    //���������� �������� ������
    foreach($dbCity as $city){
        if($currCityCode==$city['code']){
            $lat_curr=$city['coord']['latitude'];
            $long_curr=$city['coord']['longitude'];
            break;
            # ��� ������ ����� ����� ���������� ����� ����� - break;
        }
    }
    //���������� ������ �������
    foreach($dbCity as $codeCity=>$city){
        $lat_other_city=$city['coord']['latitude'];
        $long_other_city=$city['coord']['longitude'];

        $distance=calculateTheDistance($lat_curr, $long_curr, $lat_other_city, $long_other_city)/1000;

        if($distance<=$needDistance){
            $ar_city[]=$codeCity;
            //$ar_city[$codeCity]=$distance;
        }
    }
    return $ar_city;
}


function get_user_by_login($login){
    $users = require( __DIR__ . '/dbusers.php');
    if(isset($users[$login])){
        return $users[$login];
    }else{
        return false;
    }
}

/*function getPostParam($param) {
    if( isset($_POST[$param]) ) {
        return $_POST[$param];
    } else {
        return false;
    }
}*/

function getPostParam() {
    $ar_post=array();
    if(isset($_POST)) {
        foreach ($_POST as $param => $value) {
            if (isset($value)) {
                if($param=='autotrans'){
                    $ar_post['autotrans']=true;
                }elseif ($param=='wd'){
                    $ar_post['wd']=true;
                }else {
                    $ar_post[$param] = (int)$value;
                }
            }
        }
        if(is_null($ar_post['autotrans'])){
            $ar_post['autotrans']=false;
        }
        if(is_null($ar_post['wd'])){
            $ar_post['wd']=false;
        }
        return $ar_post;
    }else{
        return NULL;
    }
}

function isChecked($search){

    if ($search['autotrans']) {
        $checked['autotrans'] = 'checked';
    } else {
        $checked['autotrans'] = '';
    }

    if ($search['wd']) {
        $checked['wd'] = 'checked';
    } else {
        $checked['wd'] = '';
    }
    return $checked;
}

function isAuth(){
    //$isUserAuth=false;
    $isUserAuth=array('is_auth'=>false);
    if (isset($_COOKIE['user'])) {
        $arUserCookie = explode(':', $_COOKIE['user']);
        $login = $arUserCookie[0];
        $md5hash = $arUserCookie[1];
        $user=get_user_by_login($login);
        if($user){
            $salt_password=$user['salt_password'];
            $salt=$user['salt'];
            $hash=hash_password($salt,$salt_password);
            if ($hash == $md5hash) {
                $isUserAuth['is_auth'] = true;
            }
        }
    }
    if( isset($_GET['logout']) ) {
        if($_GET['logout']==1){
            setcookie( "user", $login. ':'. md5($password), time(), '/');
            $isUserAuth['is_auth']=false;
        }
    }
    if (isset($_SESSION['error'])) {
        $isUserAuth['error'] = $_SESSION['error'];
        unset($_SESSION['error']);
    }
    return $isUserAuth;
}

function hash_password($salt,$salt_password){
    $user_agent=$_SERVER['HTTP_USER_AGENT'];
    $ip=$_SERVER['REMOTE_ADDR'];
    $date=date('d.m.Y');
    $hash=md5($user_agent.$ip.$date.$salt.$salt_password);
    return $hash;
}

function getAutoById($id){
    $autos  = require(__DIR__ . '/dbAuto.php');
    return $autos[$id-1];
}

function auto_handler(&$auto){
   if($auto['model']['isAutoTrans']==true){
       $auto['model']['isAutoTrans']='�������';
   }
    if($auto['model']['is4wd']==true){
        $auto['model']['is4wd']='4WD';
    }
    $auto['model']['run']=floor($auto['model']['run']/1000);
}