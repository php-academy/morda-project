<?php
/**
 * Created by PhpStorm.
 * User: nasedkin
 * Date: 10.10.15
 * Time: 10:01
 */



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
    public function __construct(Auto $auto, $cityCode, Price $price)
    {
        $this->auto = $auto;
        $this->cityCode = $cityCode;
        $this->price = $price;
    }
}

/**
 * Class User
 *
 * Создайте класс User.
Поля класса:
- логин;
- salt;
- saltPassword;
- поля salt и saltPassword закрытые;
Методы класса:
- конструктор;
- init($password) - метод принимает на вход пароль пользователя и инициализирует поля класса $salt - случайныой строкой и $saltPassword - склеиваем соль и переданный пароль и берем от этого md5;
- validateUserPassword($password) - метод принимает на вход пароль переданный при авторизации и проверяет его валидность, т.е. что md5 от $salt пользователя и переданного пароля совпадает с $saltPassword пользователя;
- getUserCookieHash() - метод генерирующий 2-у часть пользовательской куки так называемый hash, т.е. метод склеивает IP, User Agent, текущую дату, $saltPassword, $salt пользователя и возвращает md5 от склеенной cтроки;
- validateUserByCookieHash($cookieHash) - метод принимает на вход 2ую часть user cookie (после ':') и проверяет ее корректность для пользователя, т.е. что md5 от склейки IP, User Agent-а, даты, $saltPassword и $salt совпадают с переданным $cookieHash;

 */
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
    public function __construct($login = '', $salt = '', $saltPassword= '') {
        $this->login = $login;
        $this->_salt = $salt;
        $this->_saltPassword = $saltPassword;
    }

    /**
     * @param string $password
     */
    public function init($password) {
        $this->_salt = $this->_generateSalt();
        $this->_saltPassword = md5($this->_salt . $password);
    }

    /**
     * @param string $cookieHash
     * @return bool
     */
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

    public function markUser() {
        setcookie("user", $this->login . ':' . $this->getUserCookieHash(), time() + 60 * 60 * 24 * 30, '/');
    }

    public static function logout() {
        setcookie('user', '', time() - 60*60*24, '/');
    }

    /**
     * @return bool
     */
    protected static function _validatePostData() {
        if( isset($_POST['login']) && isset($_POST['password']) ) {
            $login = $_POST['login'];
            $password = $_POST['password'];

            if (
                preg_match("/^[a-zA-Z0-9]{3,30}$/", $login) &&
                preg_match("/^[a-zA-Z0-9]{6,30}$/", $password)
            ) {
                return true;
            }
        }
        return false;
    }

    /**
     * @return bool
     */
    public static function login() {
        if( self::_validatePostData() ) {
            $userRepo = new UserRepo();
            $user = $userRepo->getUserByLogin($_POST['login']);
            if ( $user ) {
                if ( $user->validateUserByPassword($_POST['password']) ) {
                    $user->markUser();
                    return true;
                }
            }
        }
        return false;
    }

    /**
     * @return array|bool
     */
    protected static function _parseUserCookie() {
        if( isset($_COOKIE['user']) ) {
            $userCookie = $_COOKIE['user'];
            $arUserCookie = explode(':', $userCookie);
            $login = $arUserCookie[0];
            $cookieHash = $arUserCookie[1];
            return array(
                'login' => $login,
                'cookieHash' => $cookieHash,
            );
        } else {
            return false;
        }
    }

    /**
     * @return bool|User
     */
    public static function auth() {
        if( $arCookie = self::_parseUserCookie() ) {
            $userRepo = new UserRepo();
            if( $user = $userRepo->getUserByLogin($arCookie['login']) ) {
                if( $user->validateUserByCookieHash($arCookie['cookieHash']) ) {
                    return $user;
                }
            }
        }
        return false;
    }
}

class DB {
    const DB_HOST = 'localhost';
    const DB_NAME = 'morda';
    const DB_USER = 'root';
    const DB_PASS = '';

    /**
     * @return false|PDO
     */
    public static function getConnection() {
        $host = self::DB_HOST;
        $name = self::DB_NAME;


        try {
            $conn = new PDO("mysql:host={$host};dbname={$name}", self::DB_USER, self::DB_PASS);
        } catch( Exception $e ) {
            $conn = false;
        }
        return $conn;
    }

}

class UserRepo {
    const TABLE_NAME = 'user';

    protected $_conn;

    public function __construct() {
        $this->_conn = DB::getConnection();
    }

    /**
     * @return array
     */
    public function getAllUsers() {
        $result = array();
        $q = $this->_conn->query("SELECT * FROM " . self::TABLE_NAME, PDO::FETCH_ASSOC);
        while( $r = $q->fetch() ) {
            $result[$r['login']] = new User($r['login'], $r['salt'], $r['saltPassword']);

        }
        return $result;
    }

    /**
     * @param $login
     * @return bool|User
     */
    public function getUserByLogin($login) {
        $table = self::TABLE_NAME;
        $sql = "Select * from {$table} where login = :login";
        $q = $this->_conn->prepare($sql);
        $q->execute(array(
            'login' => $login
        ));

        $r = $q->fetch();

        if( $r ) {
            return new User($r['login'], $r['salt'], $r['saltPassword']);
        } else {
            return false;
        }
    }
}

class CityRepo {

    const TABLE_NAME = 'city';

    /**
     * @var PDO
     */
    protected $_conn;

    public function __construct() {
        $this->_conn = DB::getConnection();
    }

    /**
     * @return array
     */
    public function getCities() {
        $cities = array();

        $table = self::TABLE_NAME;
        $query = $this->_conn->query("SELECT * from {$table}");
        $query->setFetchMode(PDO::FETCH_ASSOC);

        while( $result = $query->fetch() ) {
            $cities[$result['code']] = new City($result['code'], $result['name'], new Coordinates($result['lat'], $result['long']));
        }

        return $cities;
    }

    /**
     * @param string $code
     * @return bool|City
     */
    public function getCityByCode($code) {
        $table = self::TABLE_NAME;
        $query = $this->_conn->prepare("SELECT * from {$table} where code=:code");
        if( $query->execute(array('code' => $code)) ) {
            $query->setFetchMode(PDO::FETCH_ASSOC);
            if(  $result = $query->fetch() ) {
                return new City($result['code'], $result['name'], new Coordinates($result['lat'], $result['long']));
            }
        }
        return false;
    }
}