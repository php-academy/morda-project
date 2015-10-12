<?php

class City{
    const TABLE_NAME = 'city';
    public $code;
    public $name;
    public $coord;

    function __construct($code,$name,Coordinate $coord){
        $this->code=$code;
        $this->name=$name;
        $this->coord=$coord;
    }

    public static function get_curr_city() {
        if( isset($_GET['curr_city']) ) {
            $currentCity = $_GET['curr_city'];
        } else {
            if( isset($_COOKIE['curr_city']) ) {
                $currentCity = $_COOKIE['curr_city'];
            } else {
                $currentCity = 'nsk';
            }
        }
        return $currentCity;
    }

    function set_curr_city( $curr_city ) {
        setcookie('curr_city', $curr_city, time()+ 60*60*24*30, '/');
    }

    /*public function getDistanceTo(City $c){
        $coord_other=$c->coord;
        return DistanceCalculator::calculateTheDistance ($this->coord,$coord_other);
    }*/

    public static function distance_cities($dbCity,$currCityCode,$needDistance){

        $ar_city=array();

        foreach($dbCity as $codeCity=>$city){
            if($currCityCode==$codeCity){
                $city_coord=$city->coord;
                break;
            }
        }
        foreach($dbCity as $codeCity=>$city){
            $coord_other_city=$city->coord;

            $distance=DistanceCalculator::calculateTheDistance($city_coord,$coord_other_city)/1000;

            if($distance<=$needDistance){
                $ar_city[]=$codeCity;
                //$ar_city[$codeCity]=$distance;
            }
        }
        return $ar_city;
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
    public $id;
    public $name;
    public $year;
    public $run;
    public $power;
    public $isAutoTrans;
    public $is4wd;

    function __construct($name,$year,$run,$power,$isAutoTrans,$is4wd,$id){
        $this->id=$id;
        $this->name=$name;
        $this->year=$year;
        $this->run=$run;
        $this->power=$power;
        $this->isAutoTrans=$isAutoTrans;
        $this->is4wd=$is4wd;
        $this->id=$id;
    }
}


class AutoRepo {
    const TABLE_NAME = 'auto';
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
    public function getAutos() {
        $autos = array();
        $table = self::TABLE_NAME;
        $city=City::TABLE_NAME;
        $auto_city=AutoCity::TABLE_NAME;
        $sql="SELECT {$table}.id,{$table}.name,{$table}.year,{$table}.run,{$table}.power,{$table}.isAutoTrans,{$table}.is4wd,{$city}.code
              FROM {$table} JOIN {$auto_city} ON {$table}.id={$auto_city}.auto_id JOIN {$city} ON {$auto_city}.city_id={$city}.id";
        //echo $sql;
        //$query = $this->_conn->query("SELECT * from {$table} WHERE ");
        $query = $this->_conn->query($sql);
        $query->setFetchMode(PDO::FETCH_ASSOC);
        while( $result = $query->fetch() ) {
            $autos[][$result['code']] = new Auto($result['name'], $result['year'],$result['run'],$result['power'],$result['isAutoTrans'],$result['is4wd'],$result['id']);
        }
        return $autos;
    }

    public function filter($dbAuto,$dbCity,$currCityCode,$search){

        $ar_auto=array();

        $ar_city=City::distance_cities($dbCity,$currCityCode,$search['distance']);
        //print_r($ar_city);

        if(!empty($ar_city)){
            foreach($dbAuto as $auto){
                foreach($auto as $cityCode=>$auto) {
                    if (in_array($cityCode, $ar_city)) {
                        if ($auto->is4wd == $search['wd'] && $auto->isAutoTrans == $search['autotrans']) {
                            $ar_auto[] = $auto;
                        }
                    }
                }
            }
        }
        else{
            return false;
        }
        if(empty($ar_auto)){
            return false;
        }
        return $ar_auto;

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

    public static function validatePostData() {
        if( isset($_POST['login']) && isset($_POST['password']) ) {
            $login = $_POST['login'];
            $password = $_POST['password'];

            if (preg_match("/^[a-zA-Z0-9]{3,30}$/", $login) && preg_match("/^[a-zA-Z0-9]{6,30}$/", $password)) {
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
        if(!User::logout()){
            $isUserAuth = array();
            if ($arCookie = User::parseUserCookie()) {
                $userRepo = new UserRepo();
                if ($user = $userRepo->getUserByLogin($arCookie['login'])) {
                    if ($user->validateUserByCookieHash($arCookie['cookieHash'])) {
                        $isUserAuth['user'] = $user;
                    }
                }
            }
        }
        $isUserAuth['error']=User::isError();
        return $isUserAuth;
    }

    public static function errorData($error){
        switch($error){
            case 'invalidPass':
                $err='Неверный пароль!';
                break;
            case 'invalidLogin':
                $err='Пользователя с таким логином не существует!';
                break;
            case 'invalidData':
                $err='Введенные данные не корректны!';
                break;
        }
        $_SESSION['error']=$err;
        return $err;
    }

    public static function logout(){
        if( isset($_GET['logout']) ) {
            if($_GET['logout']==1){
                setcookie('user', '', time() - 60*60*24, '/');
                return true;
            }
        }else{
            return false;
        }
    }

    public static function isError(){
        if (isset($_SESSION['error'])) {
            $error = $_SESSION['error'];
            unset($_SESSION['error']);
        }else{
            return false;
        }
        return $error;
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
            $cities[$result['code']] = new City($result['code'], $result['name'], new Coordinate($result['lat'], $result['long']));
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
                return new City($result['code'], $result['name'], new Coordinate($result['lat'], $result['long']));
            }
        }
        return false;
    }
}

class AutoCity{
    const TABLE_NAME = 'auto_city';

}