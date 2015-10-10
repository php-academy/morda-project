<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 08.10.2015
 * Time: 20:38
 */

class Auto{
    public $name;
    public $year;
    public $run;
    public $power;
    public $isAutoTrans;
    public $is4wd;
    function __construct($name, $year, $run, $power, $isAutoTrans, $is4wd){
        $this->name = $name;
        $this->year = $year;
        $this->run = $run;
        $this->power = $power;
        $this->isAutoTrans = $isAutoTrans;
        $this->is4wd = $is4wd;
    }
}
class AutoAd{
    public $name;
    public $cityCode;
    public $price;
    function __construct($name, $cityCode,Price $price){
        $this->name = $name;
        $this->year = $cityCode;
        $this->run = $price;
    }
}
class Price{
    public $price;
    public $currancy;
    function __construct($price,$currancy){
        $this->price = $price;
        $this->currancy = $currancy;
    }
    function getPriceString(){
        switch ($this->currancy) {
            case 'RUB':
                return "{$this->price} &#8381;";
                break;
            case 'EUR':
                return "{$this->price} &euro;";
                break;
            case 'USD':
                return "{$this->price} $";
                break;
            default: return "{$this->price} {$this->currancy}";
        }
    }
}
class User{
    public $login;
    protected $salt;
    protected $saltPassword;
    function __construct($login, $salt, $saltPassword){
        $this->login = $login;
        $this->salt = $salt;
        $this->saltPassword = $saltPassword;
    }
    function init($password){
        $salt = $this->salt;


    }
}
class City{
    public $code;
    public $name;
    public $coordinate;
//    public $coordinate;
    function __construct($code,$name,Coordinate $coordinate){
        $this->code = $code;
        $this->name = $name;
        $this->coordinate = $coordinate;
    }
    public  function getDistanceTo(City $c){
        return DistanceCalculator::getDistance($this->coordinate,$c->coordinate);
    }
}

class Coordinate{
    public $long;
    public  $lat;
    function __construct(
        $long,
        $lat){
        $this->long = $long;
        $this->lat = $lat;
    }
}
class DistanceCalculator{
    const  EARTH_RADIUS = 6372795;

    static function getDistance(Coordinate $x,$y){
        $lat1 = $x->lat;
        $long1 = $x->long;
        $lat2 = $y->lat;
        $long2 = $y->long;
        // перевести координаты в радианы
        $lat1 = $lat1 * M_PI / 180;
        $lat2 = $lat2 * M_PI / 180;
        $long1 = $long1 * M_PI / 180;
        $long2 = $long2 * M_PI / 180;

        // косинусы и синусы широт и разницы долгот
        $cl1 = cos($lat1);
        $cl2 = cos($lat2);
        $sl1 = sin($lat1);
        $sl2 = sin($lat2);
        $delta = $long2 - $long1;
        $cdelta = cos($delta);
        $sdelta = sin($delta);

        // вычисления длины большого круга
        $y = sqrt(pow($cl2 * $sdelta, 2) + pow($cl1 * $sl2 - $sl1 * $cl2 * $cdelta, 2));
        $x = $sl1 * $sl2 + $cl1 * $cl2 * $cdelta;

        //
        $ad = atan2($y, $x);
        $dist = (int)(($ad * EARTH_RADIUS)/1000);
return $dist;
    }
}
