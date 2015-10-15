<?php

class CityRepository extends Repo {
    const TABLE_NAME = 'city';

    public function __construct() {
        parent::__construct(self::TABLE_NAME);
    }
    /**
     * @return array
     */
    public function getCities() {
        $cities = array();
        $query = $this->_conn->query("SELECT * from {$this->table}");
        $query->setFetchMode(PDO::FETCH_ASSOC);
        while( $result = $query->fetch() ) {
            $cities[$result['code']] = new City($result['code'], $result['name'], new Coordinate($result['lat'], $result['long']));
        }
        return $cities;
    }
    /**
     * @param string $code
     * @return bool|City
     */
    public function getCityByCode($code) {
        $table = self::TABLE_NAME;
        $query = $this->_conn->prepare("SELECT * from {$table} where code=:code");
        if( $query->execute(array('code' => $code)) ) {
            $query->setFetchMode(PDO::FETCH_ASSOC);
            if(  $result = $query->fetch() ) {
                return new City($result['code'], $result['name'], new Coordinate($result['lat'], $result['long']));
            }
        }
        return false;
    }
}