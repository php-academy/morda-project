<?php
/**
 * Created by PhpStorm.
 * User: nasedkin
 * Date: 10.10.15
 * Time: 10:01
 */

class Coordinates {
    public $lat;
    public $long;

    /**
     * @param float $lat
     * @param float $long
     */
    public function __construct($lat, $long) {
        $this->lat = $lat;
        $this->long = $long;
    }
}


class DistanceCalculator {

    const EARTH_RADIUS = 6372795;

    /**
     * @param Coordinates $x
     * @param Coordinates $y
     * @return float
     */
    public static function getDistance(Coordinates $x, Coordinates $y) {

        // перевести координаты в радианы
        $lat1 = $x->lat;
        $lat2 = $y->lat;
        $long1 = $x->long;
        $long2 = $y->long;

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
        $dist = $ad * self::EARTH_RADIUS;

        return $dist;
    }
}

class City {
    public $code;
    public $name;
    public $coord;

    /**
     * @param string $code
     * @param string $name
     * @param Coordinates $coord
     */
    public function __construct($code, $name, Coordinates $coord) {
        $this->code = $code;
        $this->name = $name;
        $this->coord = $coord;
    }

    /**
     * @param City $c
     * @return float
     */
    public function getDistanceTo(City $c) {
        return DistanceCalculator::getDistance($this->coord, $c->coord);
    }
}

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
    public function __construct($auto, $cityCode, $price)
    {
        $this->auto = $auto;
        $this->cityCode = $cityCode;
        $this->price = $price;
    }
}

class User {
    public $login;
    protected $_salt;
    protected $_saltPassword;

    /**
     * User constructor.
     * @param string $login;
     * @param string $salt
     * @param string $saltPassword
     */
    public function __construct($login, $salt, $saltPassword) {
        $this->login = $login;
        $this->_salt = $salt;
        $this->_saltPassword = $saltPassword;
    }

    public function init($password) {
        $this->_salt = $this->_generateSalt();
        $this->_saltPassword = md5($this->_salt . $password);
    }

    public function validateUserByCookieHash($cookieHash) {
        return $this->getUserCookieHash() == $cookieHash;
    }

    public function validateUserByPassword($password) {
        return $this->_saltPassword == md5($this->_salt . $password);
    }

    protected function _generateSalt() {
        $s = '1234567890ABCDEFGHIJKLMNOPQRSTUVWXYZabcefghijklmnopqrstuvwxyz';
        $s = str_shuffle($s);
        return substr($s, 0, 8);
    }

    public function getUserCookieHash() {
        return md5(
            $_SERVER['REMOTE_ADDR'] .
            $_SERVER['HTTP_USER_AGENT'] .
            date('Y-m-d') .
            $this->_saltPassword .
            $this->_salt
        );
    }


}