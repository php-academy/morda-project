<?php

class AutoRepo extends Repo{

    const TABLE_NAME = 'auto';
    public $city;
    public $auto;
    public $auto_price;
    public $price;

    public function __construct() {
        parent::__construct(self::TABLE_NAME);
        $this->city=City::TABLE_NAME;
        $this->auto_city=AutoCity::TABLE_NAME;
        $this->auto_price=AutoPrice::TABLE_NAME;
        $this->price=Price::TABLE_NAME;
    }

    public function getAutos() {
        $autos = array();
        $sql="SELECT {$this->table}.id,{$this->table}.name,{$this->table}.year,{$this->table}.run,{$this->table}.power,{$this->table}.isAutoTrans,{$this->table}.is4wd,{$this->city}.code,{$this->price}.value,{$this->price}.currency
              FROM {$this->table}
              JOIN {$this->auto_city} ON {$this->table}.id={$this->auto_city}.auto_id
              JOIN {$this->city} ON {$this->auto_city}.city_id={$this->city}.id
              JOIN {$this->auto_price} ON {$this->table}.id={$this->auto_price}.auto_id
              JOIN {$this->price} ON {$this->auto_price}.price_id={$this->price}.id";

        $query = $this->_conn->query($sql);
        $query->setFetchMode(PDO::FETCH_ASSOC);
        while( $result = $query->fetch() ) {
            $auto=new Auto($result['id'],$result['name'], $result['year'],$result['run'],$result['power'],$result['isAutoTrans'],$result['is4wd']);
            $price=new Price($result['value'],$result['currency']);
            $autos[] = new AutoAdd($auto,$result['code'],$price);
        }
        //print_r($autos);
        return $autos;
    }

    public function getAutoById($id) {

        $sql="SELECT {$this->table}.id,{$this->table}.name,{$this->table}.year,{$this->table}.run,{$this->table}.power,{$this->table}.isAutoTrans,{$this->table}.is4wd,{$this->city}.code,{$this->price}.value,{$this->price}.currency
              FROM {$this->table}
              JOIN {$this->auto_city} ON {$this->table}.id={$this->auto_city}.auto_id
              JOIN {$this->city} ON {$this->auto_city}.city_id={$this->city}.id
              JOIN {$this->auto_price} ON {$this->table}.id={$this->auto_price}.auto_id
              JOIN {$this->price} ON {$this->auto_price}.price_id={$this->price}.id
              WHERE {$this->price}.id=:id";

        $query = $this->_conn->prepare($sql);
        if( $query->execute(array('id' => $id)) ) {
            $query->setFetchMode(PDO::FETCH_ASSOC);
            if(  $result = $query->fetch() ) {
                $auto = new Auto($result['name'], $result['year'], $result['run'], $result['power'], $result['isAutoTrans'], $result['is4wd'], $result['id']);
                $price = new Price($result['value'], $result['currency']);
                return new AutoAdd($auto, $result['code'], $price);
            }
        }
    }

}