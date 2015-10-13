<?php

/**
 * Created by PhpStorm.
 * User: nasedkin
 * Date: 13.10.15
 * Time: 8:05
 */
class Price {
    public $value;
    public $currency;

    /**
     * Price constructor.
     * @param float $value
     * @param string $currency
     */
    public function __construct($value, $currency)
    {
        $this->value = $value;
        $this->currency = $currency;
    }

    /**
     * @return string
     */
    public function getPriceString() {
        switch($this->currency) {
            case 'RUB':
                return "{$this->value} &#8381;";
            case 'USD':
                return "{$this->value} $";
            case "EUR":
                return "{$this->value} &euro;";
            default:
                return "{$this->value} {$this->currency}";
        }
    }
}