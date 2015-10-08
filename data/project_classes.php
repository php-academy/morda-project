<?php

class Auto{

}

class City{
    public $code;
    public $name;
    //объект класса Coordinate
    public $coord;

    function __construct($code,$name,$coord){
        $this->code=$code;
        $this->name=$name;
        $this->coord=$coord;
    }


    public function getDistanceTo(City $c){
        $coord_other=$c->coord;
        return DistanceCalculator::calculateTheDistance ($this->coord,$coord_other);
    }

}

class Coordinate{
    public $longitude;
    public $latitude;
    function __construct($longitude,$latitude){
        $this->longitude=$longitude;
        $this->latitude=$latitude;
    }
}


class DistanceCalculator{

    const EARTH_RADIUS=6372795;

    public static function calculateTheDistance (Coordinate $coord1, Coordinate $coord2) {

        // перевести координаты в радианы
        $lat1 = ($coord1->latitude) * M_PI / 180;
        $lat2 = ($coord2->latitude) * M_PI / 180;
        $long1 = ($coord1->longitude) * M_PI / 180;
        $long2 = ($coord2->longitude) * M_PI / 180;

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
        $dist = $ad *(self:: EARTH_RADIUS);

        return $dist;
    }

}

$coord1=new Coordinate(55.1,82.55);
$coord2=new Coordinate(56.01,93.04);

$city1=new City('nsk','Novosibirsk',$coord1);
$city2=new City('krsk','Krasnoyarsk',$coord2);

echo $city1->getDistanceTo(City $city2);

//var_dump($coord1);

//echo DistanceCalculator::calculateTheDistance($coord1,$coord2);