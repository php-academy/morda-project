<?php
/**
 * Created by PhpStorm.
 * User: nasedkin
 * Date: 13.10.15
 * Time: 8:04
 */
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