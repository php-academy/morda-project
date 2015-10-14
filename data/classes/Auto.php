<?php

class Auto{
    public $name;
    public $year;
    public $run;
    public $power;
    public $isAutoTrans;
    public $is4wd;
    public $id;

    function __construct($name,$year,$run,$power,$isAutoTrans,$is4wd,$id){
        $this->name=$name;
        $this->year=$year;
        $this->run=$run;
        $this->power=$power;
        $this->isAutoTrans=$isAutoTrans;
        $this->is4wd=$is4wd;
        $this->id=$id;
    }

    public static function filter($currCityCode,$search){

        $ar_auto=array();
        $auto=new AutoRepo();
        $dbAuto=$auto->getAutos();

        $ar_city=City::distance_cities($currCityCode,$search['distance']);

        if(!empty($ar_city)){
            foreach($dbAuto as $auto){
                if (in_array($auto->codeCity, $ar_city)) {
                    if ($auto->auto->is4wd == $search['wd'] && $auto->auto->isAutoTrans == $search['autotrans']) {
                        $ar_auto[] = $auto;
                    }
                }
            }
        }
        else{
            return false;
        }
        if(empty($ar_auto)){
            return false;
        }
        return $ar_auto;

    }
}