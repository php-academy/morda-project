<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 01.10.2015
 * Time: 20:17
 */
//print_r($_POST);
require(__DIR__ . '/data/project_functions.php');
session_start();
log_in();
header('Location: /');