<?php
/**
 * Created by PhpStorm.
 * User: nasedkin
 * Date: 13.10.15
 * Time: 20:01
 */

require(__DIR__ . '/../application/core.php');

$migration_name = $_SERVER['argv'][1];
$migration_action = $_SERVER['argv'][2];


$s = file_get_contents(__DIR__ . "/{$migration_name}/{$migration_action}.sql");

DB::getConnection()->exec($s);