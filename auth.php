<?php
require(__DIR__.'/application/core.php');
/**
 * Created by PhpStorm.
 * User: nasedkin
 * Date: 01.10.15
 * Time: 20:17
 */
if( isset($_GET['action']) && $_GET['action'] == 'login' ) {
    User::login();
} elseif( isset($_GET['action']) && $_GET['action'] == 'logout' ) {
    User::logout();
}
header('Location: /');