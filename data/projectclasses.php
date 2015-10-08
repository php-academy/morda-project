<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 08.10.2015
 * Time: 20:38
 */
/*
class Auto{
    public $name;
    public $year;
    public $run;
    public $isAutoTrans;
    public $is4wd;
    public $price;
    public $cityCode;
    function __construct(
        $name,
        $year,
        $run,
        $isAutoTrans,
        $is4wd,
        $price,
        $cityCode){
    }
}
*/
class City{
    public $code;
    public $name;
    public $coordinate;
//    public $coordinate;
    function __construct($code,$name,Coordinate $coordinate){
        $this->code = $code;
        $this->name = $name;
        $this->coordinate = $coordinate;
    }
    public  function getDistanceTo(City $c){
        $x = $this->coordinate;
        $y = $c->coordinate;
        return DistanceCalculator::getDistance($x,$y);
    }
}

class Coordinate{
    public $long;
    public  $lat;
    function __construct(
        $long,
        $lat){
        $this->long = $long;
        $this->lat = $lat;
    }
}
class DistanceCalculator{
    const  EARTH_RADIUS = 6372795;

    static function getDistance(Coordinate $x,$y){
        $lat1 = $x->lat;
        $long1 = $x->long;
        $lat2 = $y->lat;
        $long2 = $y->long;
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
return $dist;
    }
}
