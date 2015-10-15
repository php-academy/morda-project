<?php
/**
 * Created by PhpStorm.
 * User: nasedkin
 * Date: 15.10.15
 * Time: 21:27
 */

$format = isset($_POST['date_format']) && !empty($_POST['date_format']) ? $_POST['date_format'] : 'Y-m-d';
echo date($format);