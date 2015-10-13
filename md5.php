<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 04.10.2015
 * Time: 11:25
 */
//require(__DIR__ . '/data/projectclasses.php');
require(__DIR__ . '/app/core.php');
//require(__DIR__ . '/data/project_functions.php');
$autos=new AutoRepo();
$city=new CityRepo();
//$cityRepo = new CityRepo();
//print_r($cityRepo->getCities());
echo '<pre>';
print_r($autos->Autos());
echo '</pre>';

echo '<pre>';
print_r($city->getCities());
echo '</pre>';
/*
$login = 'vasya';
var_dump(getuser($login));
$login = 'asgftsg';
var_dump(getuser($login));
*/
