<?php

class Price{

    const TABLE_NAME = 'price';
    public $value;
    public $currency;

    function __construct($value,$currency){
        $this->value=$value;
        $this->currency=$currency;
    }

    function getPriceString(){
        switch($this->currency){
            case 'RUB':
                $html_currency='&#8381';
                break;
            case 'EUR':
                $html_currency='&euro';
                break;
            case 'USD':
                $html_currency='$';
                break;
            default:
                $html_currency=$this->currency;
        }
        return $this->value.' '.$html_currency;
    }
}