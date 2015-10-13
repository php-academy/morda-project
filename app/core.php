<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 13.10.2015
 * Time: 19:44
 */
require(__DIR__ . '/../data/project_functions.php');
require(__DIR__ . '/../data/projectclasses.php');
session_start();
function __autoload($className){
    require(__DIR__ . "/../classes/{$className}.php");
}
// else require(__DIR__ . "/../migrations/{$className}.php");