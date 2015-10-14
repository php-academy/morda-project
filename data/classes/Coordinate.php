<?php

class Coordinate{
    public $longitude;
    public $latitude;
    function __construct($longitude,$latitude){
        $this->longitude=$longitude;
        $this->latitude=$latitude;
    }
}