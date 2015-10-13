<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 13.10.2015
 * Time: 20:01
 */
require(__DIR__ . '/../app/core.php');
$migration_name = $_SERVER['agrv'][1];
$migration_action = $_SERVER['agrv'][2];

$s = file_get_contents('/{$migration_name}/{$migration_action}.php');
DB::getConnection()->exec($s);