<?php
class Coordinates {
    public $long;
    public $lat;

    public function __construct ($lat, $long) {
        $this->lat = $lat;
        $this->long = $long;
    }
}

class City {
    public $coord;
    public $code;
    public $name;
    public function __construct ($coord, $name, $code) {
        $this->coord = $coord;
        $this->code = $code;
        $this->name = $name;
    }
    public function getDistanceTo($to) {
        return DistanceCalculator::calculateTheDistance($this->coord, $to->coord);
    }
}

class DistanceCalculator {
    const EARTH_RADIUS = 6371302;
    static function calculateTheDistance ($coord1, $coord2) {
        // перевести координаты в радианы
        $lat1 = $coord1->lat * M_PI / 180;
        $lat2 = $coord2->lat * M_PI / 180;
        $long1 = $coord1->long * M_PI / 180;
        $long2 = $coord2->long * M_PI / 180;
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
        $dist = $ad * DistanceCalculator::EARTH_RADIUS;

        return $dist;
    }
}

class Price {
    public $priceVal;
    public $currency;
    function __construct($priceVal, $currency) {
        $this->priceVal = $priceVal;
        $this->currency = $currency;
    }
    /*
     * - возвращает данные цены в виде строки, например, "1000$";
     * - метод рассчитан на работу с RUB, USD, EUR;
     * - вместо 3-символьного кода выдавать html код символа валюты;
     * - RUB - "&#8381;", EUR - "&euro;", USD - "$";
     * - если у цены валюта не из трех указанных выдет строку в исходном виде, например, "1000 рублей";
     */
    public function getPriceString() {
        switch ($this->currency) {
            case 'RUB':
                return $this->priceVal.'&#8381;';
                break;
            case 'USD':
                return $this->priceVal.'$';
                break;
            case 'EUR':
                return $this->priceVal.'&euro;';
                break;
            default:
                return $this->priceVal." $this->currency";
            break;
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
    public function __construct($name, $year, $run, $power, $isAutoTrans, $is4wd) {
        $this->name = $name;
        $this->year = $year;
        $this->run = $run;
        $this->power = $power;
        $this->isAutoTrans = $isAutoTrans;
        $this->is4wd = $is4wd;
    }
}
class AutoAd {
    public $auto;
    public $cityCode;
    public $price;
    public function __construct($auto, $cityCode, $price) {
        $this->auto = $auto;
        $this->cityCode = $cityCode;
        $this->price = $price;
    }
}
class User {
    public $dbLogin;
    private $dbSalt;
    private $dbSaltPassword;
    public function __construct($dbLogin, $dbSalt, $dbSaltPassword) {
        $this->dbLogin = $dbLogin;
        $this->dbSalt = $dbSalt;
        $this->dbSaltPassword = $dbSaltPassword;
    }
    /*
     - метод принимает на вход пароль пользователя;
     - склеиваем соль и переданный пароль и берем от этого md5;
    */
    public function init($password) {
        return md5($this->dbSalt.$password);
    }
    /*
     * - метод принимает на вход пароль переданный при авторизации и проверяет его валидность,
     * т.е. что md5 от $salt пользователя и переданного пароля совпадает с $saltPassword пользователя;
     */
    public function validateUserPassword($password) {
        if ( md5($this->dbSalt.$password) == $this->dbSaltPassword ) return true;
        else return false;
    }
    /*
     * - метод генерирующий 2-у часть пользовательской куки так называемый hash,
     * т.е. метод склеивает IP, User Agent, текущую дату, $saltPassword, $salt пользователя
     * и возвращает md5 от склеенной cтроки;
     */
    public function getUserCookieHash() {
        return md5( $_SERVER["REMOTE_ADDR"]
                    .$_SERVER["HTTP_USER_AGENT"]
                    .date('D,M,Y')
                    .$this->dbSaltPassword
                    .$this->dbSalt );
    }
    /*
     * - метод принимает на вход 2ую часть user cookie (после ':')
     * и проверяет ее корректность для пользователя,
     * т.е. что md5 от склейки IP, User Agent-а, даты, $saltPassword и $salt
     * совпадают с переданным $cookieHash;
     */
    public function validateUserByCookieHash($cookieHash) {
        if ( $cookieHash ==
             md5($_SERVER["REMOTE_ADDR"].$_SERVER["HTTP_USER_AGENT"]
                 .date('D,M,Y')
                 .$this->dbSaltPassword
                 .$this->dbSalt) ) {
            return true;
        } else return false;
    }
}




/* Проверка
*/
$vasya = new User('vasya', 'Hello, salt!', 'd34fd6a87bd69cff97617188ade1c17f');
echo $vasya->init('123456');

var_dump( $vasya->validateUserPassword('123456') );



$nskCoord = new Coordinates(50, 67);
$krskCoord = new Coordinates(45, 89);

$nsk = new City($nskCoord, 'Новосибирск', 'nsk');
$krsk = new City($krskCoord, 'Красноярск', 'krsk');

$d = $nsk->getDistanceTo($krsk);
echo $d;


$sale = new Price(1234, 'руб.');
echo $sale->getPriceString();











