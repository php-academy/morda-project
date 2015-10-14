<?php

require(__DIR__ . '/application/core.php');

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