<?php
/**
 * Created by PhpStorm.
 * User: nasedkin
 * Date: 10.10.15
 * Time: 10:01
 */

class Coordinates {
    public $lat;
    public $long;

    /**
     * @param float $lat
     * @param float $long
     */
    public function __construct($lat, $long) {
        $this->lat = $lat;
        $this->long = $long;
    }
}


class DistanceCalculator {

    const EARTH_RADIUS = 6372795;

    /**
     * @param Coordinates $x
     * @param Coordinates $y
     * @return float
     */
    public static function getDistance(Coordinates $x, Coordinates $y) {

        // перевести координаты в радианы
        $lat1 = $x->lat;
        $lat2 = $y->lat;
        $long1 = $x->long;
        $long2 = $y->long;

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
        $dist = $ad * self::EARTH_RADIUS;

        return $dist;
    }
}

class City {
    public $code;
    public $name;
    public $coord;

    /**
     * @param string $code
     * @param string $name
     * @param Coordinates $coord
     */
    public function __construct($code, $name, Coordinates $coord) {
        $this->code = $code;
        $this->name = $name;
        $this->coord = $coord;
    }

    /**
     * @param City $c
     * @return float
     */
    public function getDistanceTo(City $c) {
        return DistanceCalculator::getDistance($this->coord, $c->coord);
    }
}