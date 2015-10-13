<?php
/**
 * Created by PhpStorm.
 * User: Ivan
 * Date: 14.10.2015
 * Time: 0:47
 */

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