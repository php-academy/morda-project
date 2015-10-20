<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 13.10.2015
 * Time: 20:01
 */
require(__DIR__ . '/../app/core.php');
$migration_name = $_SERVER['argv'][1];
$migration_action = $_SERVER['argv'][2];

$s = file_get_contents(__DIR__. "/{$migration_name}/{$migration_action}.sql");
DB::getConnection()->exec($s);
/*
$migration_name = $_SERVER['agrv'][1];
$migration_action = $_SERVER['agrv'][2];
$m = new {$migration_name} . 'Migration';
//$a ={$migration_action};
$s=$m->$migration_action();
DB::getConnection()->exec($s);
*/