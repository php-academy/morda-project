<?php
/**
 * Created by PhpStorm.
 * User: engel
 * Date: 13.10.2015
 * Time: 23:53
 */
class City{
    public $code;
    public $name;
    public $coordinate;
    function __construct($code,$name,Coordinate $coordinate){
        $this->code = $code;
        $this->name = $name;
        $this->coordinate = $coordinate;
    }
    public  function getDistanceTo(City $c){
        return DistanceCalculator::getDistance($this->coordinate,$c->coordinate);
    }

    public static function getCurrentCity(){
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
    public  static function setCurrentCity($currentCity){
        setcookie('curr_city', $currentCity, time()+ 60*60*24*30, '/');
    }
}