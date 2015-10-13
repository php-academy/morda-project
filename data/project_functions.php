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
    $i=0;
//    $c=new City();
    foreach($dbAuto as $dataauto){

            if ((!isset($is4wd) || ($dataauto['model']['is4wd'] == $is4wd))
                && (!isset($isAutoTrans) || ($dataauto['model']['isAutoTrans'] == $isAutoTrans))
                && (!isset($price_ot) || ($dataauto['price']['value'] > ($price_ot * 1000)))
                && (!isset($price_do) || ($dataauto['price']['value'] < ($price_do * 1000)))
                && (!isset($year_ot) || ($dataauto['model']['year'] >= $year_ot))
                && (!isset($year_do) || ($dataauto['model']['year'] <= $year_do))
                && ($needDistance >= City::getDistanceTo($dataauto->citycode))
            ) {
          //      calculateTheDistance($cities, $currentCity, $dataauto['citycode'])
                $ar_auto[$i] = $dataauto;
            }
        $i++;
    }
    return $ar_auto;
}

function getuser($login){
    $dbusers = require(__DIR__ . '/dbUsers.php');
    if (isset($dbusers[$login])){
        $userdata = $dbusers[$login];
    }
    else return false;
return $userdata;
}
function log_in(){
    if(isset($_POST['login']) && isset($_POST['password'])){
        $login = $_POST['login'];
        $password = $_POST['password'];
        if(
            preg_match("/^[a-zA-Z0-9]{3,30}$/", $login) &&
            preg_match("/^[a-zA-Z0-9]{6,30}$/", $password)
        ) {
            $userdata = getuser($login);
            if($userdata){
                $saltpassword = md5($userdata['salt'].$_POST['password']);
                if($userdata['saltpassword'] == $saltpassword)
                {
                    setcookie( "user", $login. ':'.md5($_SERVER['HTTP_USER_AGENT'].$_SERVER['REMOTE_ADDR'].date("d.m.Y").$userdata['salt'].$saltpassword), time() + 60*60*24*30, '/');
                }
                else return $_SESSION['login_error'] = 'Неверные логин и/или пароль';
            }
        }
        else return $_SESSION['login_error'] = 'Неверные логин и/или пароль';
    }

}
function authCheck(){
    if(isset($_COOKIE['user'])){
        $userCookie = $_COOKIE['user'];
        $arUserCookie = explode(':',$userCookie);
        $saltPasswordCookie = $arUserCookie[1];
        if (getuser($arUserCookie[0])){
            $userData = getuser($arUserCookie[0]);
            $saltpassword = md5($_SERVER['HTTP_USER_AGENT'].$_SERVER['REMOTE_ADDR'].date("d.m.Y").$userData['salt'].$userData['saltpassword']);
            if($saltPasswordCookie == $saltpassword){
               return $isUserAuth = true;
            }
        }
    }
}
