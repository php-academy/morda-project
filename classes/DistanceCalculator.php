<?php

/**
 * Created by PhpStorm.
 * User: nasedkin
 * Date: 13.10.15
 * Time: 8:04
 */
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