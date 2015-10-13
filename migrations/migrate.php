<?php

require(__DIR__ . '../application/core.php');

$migration_name=$_SERVER['argv'][1];
$migration_action=$_SERVER['argv'][2];

//$s=file_get_contents(__DIR__ . '/{migration_name}/{migration_action}.sql');

class Migration{

    public $name;
    public $action;

    public function __construct($name,$action){
        $this->name=$name;
        $this->action=$action;
    }

    public function migration(){
        $s=file_get_contents(__DIR__ . '/{$this->name}/{$this->action}.sql');
        DB::getConnection()->exec($s);
    }
}

class CreateTableCityMigration extendts Migration{
    public function up(){
        parent::migration();
    }
    public function down(){
        parent::migration();
    }
}

$migration_class=$migration_name.'Migration';
$migration=new $migration_class($migration_name,$migration_action);
$migration->$migration_action();

DB::getConnection()->exec($s);