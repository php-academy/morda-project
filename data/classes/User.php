<?php

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