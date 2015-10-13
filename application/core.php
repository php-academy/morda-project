<?php
/**
 * Created by PhpStorm.
 * User: nasedkin
 * Date: 13.10.15
 * Time: 19:42
 */

session_start();
date_default_timezone_set('America/Los_Angeles');
require(__DIR__ . '/../data/project_functions.php');
require(__DIR__ . '/../data/project_classes.php');


function __autoload($className) {
    require(__DIR__."/../classes/{$className}.php");
}