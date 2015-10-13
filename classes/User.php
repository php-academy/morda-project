<?php
/**
 * Created by PhpStorm.
 * User: engel
 * Date: 13.10.2015
 * Time: 23:54
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

    public static function logout() {
        setcookie('user', '', time() - 60*60*24, '/');
    }

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

    public static function auth(){
        if( $arCookie = User::parseUserCookie() ) {
            $userRepo = new UserRepo();
            if( $user = $userRepo->getUserByLogin($arCookie['login']) ) {
                if( $user->validateUserByCookieHash($arCookie['cookieHash']) ) {
                    return $user;
                }
            }
        }
        return false;
    }

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
}