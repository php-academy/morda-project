<?php

/**
 * Created by PhpStorm.
 * User: nasedkin
 * Date: 13.10.15
 * Time: 8:05
 */
class Auto {

    public $name;
    public $year;
    public $run;
    public $power;
    public $isAutoTrans;
    public $is4wd;

    /**
     * AutoAd constructor.
     * @param string $name
     * @param integer $year
     * @param float $run
     * @param integer $power
     * @param bool $isAutoTrans
     * @param bool $is4wd
     */
    public function __construct($name, $year, $run, $power, $isAutoTrans, $is4wd)
    {
        $this->name = $name;
        $this->year = $year;
        $this->run = $run;
        $this->power = $power;
        $this->isAutoTrans = $isAutoTrans;
        $this->is4wd = $is4wd;
    }
}