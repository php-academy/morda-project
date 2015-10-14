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

