<?php
/**
 * Created by PhpStorm.
 * User: Ivan
 * Date: 14.10.2015
 * Time: 0:46
 */

class Price{
    public $value;
    public $currancy;
    function __construct($value,$currancy){
        $this->price = $value;
        $this->currancy = $currancy;
    }
    function getPriceString(){
        switch ($this->currancy) {
            case 'RUB':
                return "{$this->value} &#8381;";
            case 'EUR':
                return "{$this->value} &euro;";
            case 'USD':
                return "{$this->value} $";
            default: return "{$this->value} {$this->currancy}";
        }
    }
}