<?php

/**
 * Created by PhpStorm.
 * User: nasedkin
 * Date: 13.10.15
 * Time: 10:13
 */
interface IMigration
{
    public function up();
    public function down();
}