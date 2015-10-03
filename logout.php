<?php
/**
 * Created by PhpStorm.
 * User: LIS
 * Date: 02.10.2015
 * Time: 10:35
 */
setcookie( "user", '',- 60*60*24*30, '/');
header('Location: /');