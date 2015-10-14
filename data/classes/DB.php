<?php

class DB {
    const DB_HOST = 'localhost';
    const DB_NAME = 'morda_project';
    const DB_USER = 'root';
    const DB_PASS = '';

    /**
     * @return false|PDO
     */
    public static function getConnection() {
        $host = self::DB_HOST;
        $name = self::DB_NAME;


        try {
            $conn = new PDO("mysql:host={$host};dbname={$name}", self::DB_USER, self::DB_PASS);
        } catch( Exception $e ) {
            $conn = false;
        }
        return $conn;
    }
}