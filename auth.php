<?php
session_start();
date_default_timezone_set('America/Los_Angeles');
/**
 * Created by PhpStorm.
 * User: nasedkin
 * Date: 01.10.15
 * Time: 20:17
 */
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