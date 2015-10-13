<?php
/**
 * Created by PhpStorm.
 * User: nasedkin
 * Date: 13.10.15
 * Time: 8:56
 */

function __autoload($class_name) {
    $class_file = __DIR__ . "/../classes/{$class_name}.php";
    if( file_exists($class_file) ) {
        require($class_file);
    }
}

require(__DIR__ . '/../data/project_functions.php');