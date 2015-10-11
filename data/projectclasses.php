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
    public $auto;
    public $cityCode;
    public $price;
    function __construct(Auto $auto, $cityCode,Price $price){
        $this->name = $auto;
        $this->year = $cityCode;
        $this->run = $price;
    }
}
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
    public function markUser() {
        setcookie("user", $this->login . ':' . $this->getUserCookieHash(), time() + 60 * 60 * 24 * 30, '/');
    }

    public function unMarkUser() {
        setcookie('user', '', time() - 60*60*24, '/');
    }

    public static function validatePostData() {
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

    public static function parseUserCookie() {
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
    public static function isAuth(){
//        $isAuth = false;
        if( $arCookie = User::parseUserCookie() ) {
            $userRepo = new UserRepo();
            if( $user = $userRepo->getUserByLogin($arCookie['login']) ) {
                if( $user->validateUserByCookieHash($arCookie['cookieHash']) ) {
                    return $user->login;
                }
            }
        }
        else return false;
    }
}

class DB {
    const DB_HOST = 'localhost';
    const DB_NAME = 'morda_project';
    const DB_USER = 'root';
    const DB_PASS = '';

    /**
     * @return PDO
     */
    public static function getConnection() {
        $host = self::DB_HOST;
        $name = self::DB_NAME;
        return new PDO("mysql:host={$host};dbname={$name}", self::DB_USER, self::DB_PASS);
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
            return new User($r['login'], $r['salt'], $r['saltpassword']);
        } else {
            return false;
        }
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
//    public function
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
        $dist = (int)(($ad * self::EARTH_RADIUS)/1000);
return $dist;
    }
}
