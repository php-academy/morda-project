<?php

class DistanceCalculator{

    const EARTH_RADIUS=6372795;

    public static function calculateTheDistance (Coordinate $coord1, Coordinate $coord2) {

        // ��������� ���������� � �������
        $lat1 = ($coord1->latitude) * M_PI / 180;
        $lat2 = ($coord2->latitude) * M_PI / 180;
        $long1 = ($coord1->longitude) * M_PI / 180;
        $long2 = ($coord2->longitude) * M_PI / 180;

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
        $dist = $ad *(self:: EARTH_RADIUS);

        return $dist;
    }

}