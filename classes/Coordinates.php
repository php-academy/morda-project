<?php
/**
 * Created by PhpStorm.
 * User: nasedkin
 * Date: 13.10.15
 * Time: 19:32
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