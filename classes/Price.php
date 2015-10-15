<?php
/**
 * Created by PhpStorm.
 * User: Ivan
 * Date: 14.10.2015
 * Time: 0:46
 */

class Price{
    public $value;
    public $currency;
    function __construct($value,$currency){
        $this->value = $value;
        $this->currancy = $currency;
    }
    public function getPriceString(){
        switch ($this->currency) {
            case 'RUB':
                return "{$this->value} &#8381;";
            case 'EUR':
                return "{$this->value} &euro;";
            case 'USD':
                return "{$this->value} $";
            default: return "{$this->value} {$this->currency}";
        }
    }
}