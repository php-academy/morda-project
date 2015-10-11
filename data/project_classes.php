<?php

class City{
    public $code;
    public $name;
    //������ ������ Coordinate
    public $coord;

    function __construct($code,$name,Coordinate $coord){
        $this->code=$code;
        $this->name=$name;
        $this->coord=$coord;
    }


    public function getDistanceTo(City $c){
        $coord_other=$c->coord;
        return DistanceCalculator::calculateTheDistance ($this->coord,$coord_other);
    }

}

class Coordinate{
    public $longitude;
    public $latitude;
    function __construct($longitude,$latitude){
        $this->longitude=$longitude;
        $this->latitude=$latitude;
    }
}


class DistanceCalculator{

    const EARTH_RADIUS=6372795;

    public static function calculateTheDistance (Coordinate $coord1, Coordinate $coord2) {

        // ��������� ���������� � �������
        $lat1 = ($coord1->latitude) * M_PI / 180;
        $lat2 = ($coord2->latitude) * M_PI / 180;
        $long1 = ($coord1->longitude) * M_PI / 180;
        $long2 = ($coord2->longitude) * M_PI / 180;

        // �������� � ������ ����� � ������� ������
        $cl1 = cos($lat1);
        $cl2 = cos($lat2);
        $sl1 = sin($lat1);
        $sl2 = sin($lat2);
        $delta = $long2 - $long1;
        $cdelta = cos($delta);
        $sdelta = sin($delta);

        // ���������� ����� �������� �����
        $y = sqrt(pow($cl2 * $sdelta, 2) + pow($cl1 * $sl2 - $sl1 * $cl2 * $cdelta, 2));
        $x = $sl1 * $sl2 + $cl1 * $cl2 * $cdelta;

        //
        $ad = atan2($y, $x);
        $dist = $ad *(self:: EARTH_RADIUS);

        return $dist;
    }

}

/*$coord1=new Coordinate(55.1,82.55);
$coord2=new Coordinate(56.01,93.04);

$city1=new City('nsk','Novosibirsk',$coord1);
$city2=new City('krsk','Krasnoyarsk',$coord2);

echo $city1->getDistanceTo($city2);*/

//var_dump($coord1);

//echo DistanceCalculator::calculateTheDistance($coord1,$coord2);

class Price{

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

//$price=new Price(29000,'USD');
//echo $price->getPriceString();

class Auto{
    public $name;
    public $year;
    public $run;
    public $power;
    public $isAutoTrans;
    public $is4wd;

    function __construct($name,$year,$run,$power,$isAutoTrans,$is4wd){
        $this->name=$name;
        $this->year=$year;
        $this->run=$run;
        $this->power=$power;
        $this->isAutoTrans=$isAutoTrans;
        $this->is4wd=$is4wd;
    }
}

class AutoAdd{
    public $auto;
    public $codeCity;
    public $price;

    function __construct(Auto $auto,$codeCity,Price $price){
        $this->auto=$auto;
        $this->codeCity=$codeCity;
        $this->price=$price;
    }
}

class User{

    //$_salt
    public $login;
    protected $salt;
    protected $saltPassword;

    function __construct($login,$salt,$saltPassword){
        $this->login=$login;
        $this->salt=$salt;
        $this->saltPassword=$saltPassword;
    }

    function init($password){
        $this->salt = $this->_generateSalt();
        $this->saltPassword = md5($this->salt . $password);
    }

    function validateUserByPassword($password){
        return $this->saltPassword == md5($this->salt . $password);
    }
    protected function _generateSalt() {
        $s = '1234567890ABCDEFGHIJKLMNOPQRSTUVWXYZabcefghijklmnopqrstuvwxyz';
        $s = str_shuffle($s);
        return substr($s, 0, 8);
    }
    function getUserCookieHash(){
        $user_agent=$_SERVER['HTTP_USER_AGENT'];
        $ip=$_SERVER['REMOTE_ADDR'];
        $date=date('d.m.Y');
        $hash=md5($user_agent.$ip.$date.$this->salt.$this->salt_password);
        return $hash;
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
    function validateUserByCookieHash($cookieHash){
        if($this->getUserCookieHash()==$cookieHash){
            return true;
        }
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
        if( $arCookie = User::parseUserCookie() ) {
            $userRepo = new UserRepo();
            if( $user = $userRepo->getUserByLogin($arCookie['login']) ) {
                if( $user->validateUserByCookieHash($arCookie['cookieHash']) ) {
                    return $user;
                }
                else{
                    return false;
                }
            }
        }
        return $user;
    }
}

class DB {
    const DB_HOST = 'localhost';
    const DB_NAME = 'morda_project';
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

class UserRepo{
    const TABLE_NAME='user';
    protected $_conn;
    public function __construct(){
        $this->_conn=DB::getConnection();
    }
    public function getUserByLogin($login){

        $table=self::TABLE_NAME;
        $sql="SELECT * FROM {$table} WHERE login=:login";
        $q=$this->_conn->prepare($sql);
        $q->execute(array(
            'login'=>$login
        ));
        $r=$q->fetch();
        if($r){
            return new User($r['login'],$r['salt'],$r['saltPassword']);
        }else{
            return false;
        }
    }
    public function getAllUsers(){

        $q=$this->_conn=query("SELECT * FROM ".self::TABLE_NAME,PDO::FETCH_ASSOC);
        while($r=$q->fetch()){
            $result[$r['login']]=new User($r['login'],$r['salt'].$r['saltPassword']);
        }
        return $result;
    }
}

class CityRepository {
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

