<?php

class Repo{

    protected $_conn;
    public $table;

    public function __construct($table) {
        $this->_conn = DB::getConnection();
        $this->table=$table;
    }

    /*public function getTable(){
        $table=$this->table;
        $arr = array();
        $query = $this->_conn->query("SELECT * from {$this->table}");
        $query->setFetchMode(PDO::FETCH_ASSOC);
        while( $result = $query->fetch() ) {

        }

        return $arr;
    }

    public function getTableBySearch(){

    }*/

}