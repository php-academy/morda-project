<?php
/**
 * Created by PhpStorm.
 * User: nasedkin
 * Date: 13.10.15
 * Time: 19:23
 */


require(__DIR__.'/application/core.php');


try {
    $a = new City('', '', new Coordinates(0,0));
    print_r($a);
}
catch (Exception $e) {
    print_r($e->getMessage());
}