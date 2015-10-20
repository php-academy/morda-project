<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 13.10.2015
 * Time: 19:44
 */
require(__DIR__ . '/../data/project_functions.php');
//require(__DIR__ . '/../data/projectclasses.php');
session_start();
function __autoload($class_name) {
    $arPath = array(
        __DIR__ . "/../classes",
        __DIR__ . "/../migrations",
    );
    foreach( $arPath as $path ) {
        $class_file = "{$path}/{$class_name}.php";
        if( file_exists($class_file) ) {
            require($class_file);
            break;
        }
    }
}