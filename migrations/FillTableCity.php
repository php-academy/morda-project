<?php
/**
 * Created by PhpStorm.
 * User: Ivan
 * Date: 14.10.2015
 * Time: 1:06
 */

class FillTableCityMigration extends migration{
    public function up(){
        DB::getConnection()->exec("INSERT INTO `city` (`code`, `name`, `lat`, `long`) VALUES
                                  ('nsk', '', 55.1, 82.55),
                                  ('krsk', '', 56.01, 93.04),
                                  ('brnl', '', 53.2, 83.46),
                                  ('tsk', '', 56.29, 84.57),
                                  ('nzsk', '', 53.44, 87.05);");
    }
    public function down(){
        DB::getConnection()->exec("TRUNCATE TABLE 'cities';");
    }
}