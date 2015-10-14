<?php
    header('Content-Type: text/html; charset=utf-8');
    session_start();
    require(__DIR__ . "/../data/project_functions.php");

    function __autoload($className){
        require(__DIR__ . "/../data/classes/{$className}.php");
    }