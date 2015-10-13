<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 13.10.2015
 * Time: 19:40
 */

class CityRepo{
    const TABLE_NAME = 'cities';
//    protected $_conn;
    public function __construct() {
        $this->_conn = DB::getConnection();
    }

    public function getCities() {
        $result = array();
        $table = self::TABLE_NAME;
        $q = $this->_conn->query("SELECT * FROM {$table}", PDO::FETCH_ASSOC);
        while( $r = $q->fetch() ) {
            $result[$r['code']] = new City($r['code'], $r['name'],new Coordinate($r['lat'], $r['long']));

        }
        return $result;
    }
    /*
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
    */
    public function getCityByCode($code)
    {
        $table = self::TABLE_NAME;
        $query = $this->_conn->prepare("Select * from {$table} where code = {$code}");
        $query->execute(array(
            'code' => $code));
        if ($r = $query->fetch()) {
            return new City($query['code'], $query['name'], new Coordinate($query['lat'], $query['long']));
        }
        // if($query->fetch()){
        //      $query->setFetchMode(PDO::FETCH_ASSOC);
        //       return new City($query['code'], $query['name'], new Coordinate($query['lat'], $query['long']));
        /*   $sql = "Select * from {$table} where login = :login";
           $q = $this->_conn->prepare($sql);
           $q->execute(array(
               'login' => $login
           ));

           $r = $q->fetch();*/
        // }
        else return false;
    }
}