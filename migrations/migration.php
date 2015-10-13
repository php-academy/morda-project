<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 13.10.2015
 * Time: 20:30
 */
require(__DIR__ . '/../app/core.php');
class migration{
//    public $migration_name;
//    public $migration_action;
 //   function __construct($migration_name,$migration_action){
//        $this->$migration_name = $_SERVER['agrv'][1];
//        $this->$migration_action = $_SERVER['agrv'][2];
 //   }
    public function up(){}
    public function down(){}
}
class CreateTableCityMigration extends migration{
    public function up(){
        DB::getConnection()->exec("CREATE TABLE city");

    }
    public function down(){
        DB::getConnection()->exec("DROP TABLE 'city'");
    }
}
$migration_name = $_SERVER['agrv'][1];
$migration_action = $_SERVER['agrv'][2];
$m = new {$migration_name} . 'Migration';
//$a ={$migration_action};
$s=$m->$migration_action();
DB::getConnection()->exec($s);
