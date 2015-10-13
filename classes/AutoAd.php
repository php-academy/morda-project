<?php

/**
 * Created by PhpStorm.
 * User: nasedkin
 * Date: 13.10.15
 * Time: 8:07
 */
class AutoAdd {

    public $auto;
    public $cityCode;
    public $price;

    /**
     * AutoAdd constructor.
     * @param Auto $auto
     * @param string $cityCode
     * @param Price $price
     */
    public function __construct(Auto $auto, $cityCode, Price $price)
    {
        $this->auto = $auto;
        $this->cityCode = $cityCode;
        $this->price = $price;
    }
}