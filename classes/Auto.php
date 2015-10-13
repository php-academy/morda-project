<?php
/**
 * Created by PhpStorm.
 * User: Ivan
 * Date: 14.10.2015
 * Time: 0:46
 */

class Auto{
    public $id;
    public $model;
    public $year;
    public $run;
    public $power;
    public $isAutoTrans;
    public $is4wd;
    public $cityCode;
    public $price;
    function __construct($id, $model, $year, $run, $power, $isAutoTrans, $is4wd, $cityCode, Price $price){
        $this->id = $id;
        $this->model = $model;
        $this->year = $year;
        $this->run = $run;
        $this->power = $power;
        $this->isAutoTrans = $isAutoTrans;
        $this->is4wd = $is4wd;
        $this->cityCode = $cityCode;
        $this->price = $price;
    }
}