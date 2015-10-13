<?php

/**
 * Created by PhpStorm.
 * User: nasedkin
 * Date: 13.10.15
 * Time: 10:35
 */
class CreateAndFillCityMigration implements IMigration
{
    public function up() {
        DB::getConnection()->exec("
        CREATE TABLE IF NOT EXISTS `city` (
          `code` varchar(10) NOT NULL,
          `name` varchar(256) NOT NULL,
          `lat` float NOT NULL,
          `long` float NOT NULL
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8;
        ");

        DB::getConnection()->exec("
        INSERT INTO `city` (`code`, `name`, `lat`, `long`) VALUES
        ('nsk', 'Новосибирск', 55.1, 82.55),
        ('krsk', 'Красноярск', 56.01, 93.04),
        ('brnl', 'Барнаул', 53.2, 83.46),
        ('tsk', 'Томск', 56.29, 84.57),
        ('nzsk', 'Новокузнетск', 53.44, 87.05);
        ");

        return true;
    }

    public function down() {
        DB::getConnection()->exec("
        DROP TABLE IF EXISTS `city`
        ");

        return true;
    }
}