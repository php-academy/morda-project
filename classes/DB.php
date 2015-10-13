<?php
/**
 * Created by PhpStorm.
 * User: Ivan
 * Date: 14.10.2015
 * Time: 0:47
 */

class DB {
    const DB_HOST = 'localhost';
    const DB_NAME = 'morda';
    const DB_USER = 'root';
    const DB_PASS = '';

    /**
     * @return PDO
     */
    public static function getConnection() {
        $host = self::DB_HOST;
        $name = self::DB_NAME;
        return new PDO("mysql:host={$host};dbname={$name}", self::DB_USER, self::DB_PASS);
    }
}