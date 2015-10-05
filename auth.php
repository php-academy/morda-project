<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 01.10.2015
 * Time: 20:17
 */
//print_r($_POST);
require(__DIR__ . '/data/project_functions.php');
if(isset($_POST['login']) && isset($_POST['password'])){

    $login = $_POST['login'];
    $password = $_POST['password'];
    if(
    preg_match("/^[a-zA-Z0-9]{3,30}$/", $login) &&
    preg_match("/^[a-zA-Z0-9]{6,30}$/", $password)
    ) {
        $userdata = getuser($login);
        if($userdata){
            $saltpassword = md5($userdata['salt'].$_POST['password']);
            if($userdata['saltpassword'] == $saltpassword)
             {
                setcookie( "user", $login. ':'.md5($_SERVER['HTTP_USER_AGENT'].$_SERVER['REMOTE_ADDR'].date("d.m.Y").$userdata['salt'].$saltpassword), time() + 60*60*24*30, '/');
            }
        }
    }
}

header('Location: /');