<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 13.10.2015
 * Time: 21:38
 */

class CreateTableCityMigration extends migration{
    public function up(){
        DB::getConnection()->exec("CREATE TABLE IF NOT EXISTS `city` (
  `code` varchar(10) NOT NULL,
  `name` varchar(256) NOT NULL,
  `lat` float NOT NULL,
  `long` float NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;");

    }
    public function down(){
        DB::getConnection()->exec("DROP TABLE IF EXISTS 'city'");
    }
}
