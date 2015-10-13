<?php

/**
 * Created by PhpStorm.
 * User: nasedkin
 * Date: 13.10.15
 * Time: 8:08
 */
class UserRepo {
    const TABLE_NAME = 'user';

    protected $_conn;

    public function __construct() {
        $this->_conn = DB::getConnection();
    }

    /**
     * @return array
     */
    public function getAllUsers() {
        $result = array();
        $q = $this->_conn->query("SELECT * FROM " . self::TABLE_NAME, PDO::FETCH_ASSOC);
        while( $r = $q->fetch() ) {
            $result[$r['login']] = new User($r['login'], $r['salt'], $r['saltPassword']);

        }
        return $result;
    }

    /**
     * @param $login
     * @return bool|User
     */
    public function getUserByLogin($login) {
        $table = self::TABLE_NAME;
        $sql = "Select * from {$table} where login = :login";
        $q = $this->_conn->prepare($sql);
        $q->execute(array(
            'login' => $login
        ));

        $r = $q->fetch();

        if( $r ) {
            return new User($r['login'], $r['salt'], $r['saltPassword']);
        } else {
            return false;
        }
    }
}