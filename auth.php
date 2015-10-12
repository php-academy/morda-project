<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 01.10.2015
 * Time: 20:17
 */
require(__DIR__ . '/data/project_functions.php');
require(__DIR__ . '/data/project_classes.php');
header('Content-Type: text/html; charset=utf-8');
session_start();

    if( User::validatePostData() ) {
        $userRepo = new UserRepo();
        $user = $userRepo->getUserByLogin($_POST['login']);
        if ( $user ) {
            if ( $user->validateUserByPassword($_POST['password']) ) {
                $user->markUser();
            }else{
                User::errorData('invalidPass');
            }
        }else {
            User::errorData('invalidLogin');
        }
    }else {
        User::errorData('invalidData');
    }

header('Location: /');