<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 04.10.2015
 * Time: 11:25
 */
require(__DIR__ . '/data/project_functions.php');
$login = 'vasya';
var_dump(getuser($login));
$login = 'asgftsg';
var_dump(getuser($login));
