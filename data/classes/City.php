<?php

class City{
    const TABLE_NAME = 'city';
    public $code;
    public $name;
    public $coord;

    function __construct($code,$name,Coordinate $coord){
        $this->code=$code;
        $this->name=$name;
        $this->coord=$coord;
    }

    function set_curr_city( $curr_city ) {
        setcookie('curr_city', $curr_city, time()+ 60*60*24*30, '/');
    }

    /*public function getDistanceTo(City $c){
        $coord_other=$c->coord;
        return DistanceCalculator::calculateTheDistance ($this->coord,$coord_other);
    }*/
    public static function distance_cities($currCityCode,$needDistance){

        $ar_city=array();
        $city_rep=new CityRepository();
        $dbCity=$city_rep->getCities();

        foreach($dbCity as $codeCity=>$city){
            if($currCityCode==$codeCity){
                $city_coord=$city->coord;
                break;
            }
        }
        foreach($dbCity as $codeCity=>$city){
            $coord_other_city=$city->coord;

            $distance=DistanceCalculator::calculateTheDistance($city_coord,$coord_other_city)/1000;

            if($distance<=$needDistance){
                $ar_city[]=$codeCity;
                //$ar_city[$codeCity]=$distance;
            }
        }
        return $ar_city;
    }
}