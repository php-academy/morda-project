<?php

/**
 * Created by PhpStorm.
 * User: nasedkin
 * Date: 13.10.15
 * Time: 10:32
 */
class Migrator
{
    public static function run($name, $action) {
        $m = new $name();
        if( $m->$action() ) {
            echo "{$name} {$action} success\n";
        } else {
            echo "{$name} {$action} fail\n";
        }
    }
}