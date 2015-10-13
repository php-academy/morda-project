<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 01.10.2015
 * Time: 20:17
 */
//print_r($_POST);
session_start();
require(__DIR__ . '/data/projectclasses.php');
require(__DIR__ . '/data/project_functions.php');
if( isset($_GET['action']) && $_GET['action'] == 'login' ) {
    if( User::validatePostData() ) {
        $userRepo = new UserRepo();
        $user = $userRepo->getUserByLogin($_POST['login']);
        if ( $user ) {
            if ( $user->validateUserByPassword($_POST['password']) ) {
                $user->markUser();
            }
        }

    }
} elseif( isset($_GET['action']) && $_GET['action'] == 'logout' ) {
    $user = new User();
    $user->unMarkUser();
}
header('Location: /');