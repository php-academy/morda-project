<?php
/**
 * Created by PhpStorm.
 * User: LIS
 * Date: 02.10.2015
 * Time: 13:03
 */
$autos = require(__DIR__ . '/data/dbAuto.php');

$dist = (!isset($_POST['distance'])) ? $_POST['distance'] : NULL;
$price_ot = (isset($_POST['price_ot'])) ? $_POST['price_ot'] : NULL;
$price_do = (isset($_POST['price_do'])) ? $_POST['price_do'] : NULL;
$year_ot = (!isset($_POST['year_ot'])) ? $_POST['year_ot'] : NULL;
$year_do = (!isset($_POST['year_do'])) ? $_POST['year_do'] : NULL;
$isAutoTrans = (!isset($_POST['isAutoTrans'])) ? $_POST['isAutoTrans'] : NULL;
$is4wd = (!isset($_POST['is4wd'])) ? $_POST['is4wd'] : NULL;