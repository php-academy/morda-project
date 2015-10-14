<?php

class AutoAdd{
    public $auto;
    public $codeCity;
    public $price;

    function __construct(Auto $auto,$codeCity,Price $price){
        $this->auto=$auto;
        $this->codeCity=$codeCity;
        $this->price=$price;
    }
}