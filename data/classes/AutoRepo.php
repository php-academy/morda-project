<?php

class AutoRepo {
    const TABLE_NAME = 'auto';
    /**
     * @var PDO
     */
    protected $_conn;
    public function __construct() {
        $this->_conn = DB::getConnection();
    }

    public function getAutos() {
        $autos = array();
        $table = self::TABLE_NAME;
        $city=City::TABLE_NAME;
        $auto_city=AutoCity::TABLE_NAME;
        $auto_price=AutoPrice::TABLE_NAME;
        $price=Price::TABLE_NAME;

        $sql="SELECT {$table}.id,{$table}.name,{$table}.year,{$table}.run,{$table}.power,{$table}.isAutoTrans,{$table}.is4wd,{$city}.code,{$price}.value,{$price}.currency
              FROM {$table}
              JOIN {$auto_city} ON {$table}.id={$auto_city}.auto_id
              JOIN {$city} ON {$auto_city}.city_id={$city}.id
              JOIN {$auto_price} ON {$table}.id={$auto_price}.auto_id
              JOIN {$price} ON {$auto_price}.price_id={$price}.id";

        $query = $this->_conn->query($sql);
        $query->setFetchMode(PDO::FETCH_ASSOC);
        while( $result = $query->fetch() ) {
            $auto=new Auto($result['name'], $result['year'],$result['run'],$result['power'],$result['isAutoTrans'],$result['is4wd'],$result['id']);
            $price=new Price($result['value'],$result['currency']);
            $autos[] = new AutoAdd($auto,$result['code'],$price);
        }
        //print_r($autos);
        return $autos;
    }

    public function getAutoById($id) {
        $autos = array();
        $table = self::TABLE_NAME;
        $city=City::TABLE_NAME;
        $auto_city=AutoCity::TABLE_NAME;
        $auto_price=AutoPrice::TABLE_NAME;
        $price=Price::TABLE_NAME;

        $sql="SELECT {$table}.id,{$table}.name,{$table}.year,{$table}.run,{$table}.power,{$table}.isAutoTrans,{$table}.is4wd,{$city}.code,{$price}.value,{$price}.currency
              FROM {$table}
              JOIN {$auto_city} ON {$table}.id={$auto_city}.auto_id
              JOIN {$city} ON {$auto_city}.city_id={$city}.id
              JOIN {$auto_price} ON {$table}.id={$auto_price}.auto_id
              JOIN {$price} ON {$auto_price}.price_id={$price}.id
              WHERE {$table}.id=:id";

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