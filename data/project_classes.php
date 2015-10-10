<?php

class City{
    public $code;
    public $name;
    //������ ������ Coordinate
    public $coord;

    function __construct($code,$name,$coord){
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

class AutoId{
    public $auto;
    public $codeCity;
    public $price;

    function __construct(){
        $this->auto=$auto;
        $this->codeCity=$codeCity;
        $this->price=$price;
    }
}

class User{

    public $login;
    protected $salt;
    protected $saltPassword;

    function __construct($login,$salt,$saltPassword){
        $this->login=$login;
        $this->salt=$salt;
        $this->saltPassword=$saltPassword;
    }

    function init($password){
        return md5($this->salt.$password);
    }

    function validateUserPassword($password){
        if(init($password)==$this->saltPassword){
            return true;
        }
    }

    function getUserCookieHash(){
        $user_agent=$_SERVER['HTTP_USER_AGENT'];
        $ip=$_SERVER['REMOTE_ADDR'];
        $date=date('d.m.Y');
        $hash=md5($user_agent.$ip.$date.$this->salt.$this->salt_password);
        return $hash;
    }

    function validateUserByCookieHash($cookieHash){
        if(getUserCookieHash()==$cookieHash){
            return true;
        }
    }
}

