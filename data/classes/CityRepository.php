<?php

class CityRepository {
    const TABLE_NAME = 'city';
    /**
     * @var PDO
     */
    protected $_conn;
    public function __construct() {
        $this->_conn = DB::getConnection();
    }
    /**
     * @return array
     */
    public function getCities() {
        $cities = array();
        $table = self::TABLE_NAME;
        $query = $this->_conn->query("SELECT * from {$table}");
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