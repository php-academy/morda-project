<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 08.10.2015
 * Time: 20:38
 */


class AutoAd{
    public $model;
    public $cityCode;
    public $price;
    function __construct(Auto $model, $cityCode,Price $price){
        $this->model = $model;
        $this->cityCode = $cityCode;
        $this->price = $price;
    }
}














