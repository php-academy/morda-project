<?php

class UserRepo{
    const TABLE_NAME='user';
    protected $_conn;
    public function __construct(){
        $this->_conn=DB::getConnection();
    }
    public function getUserByLogin($login){

        $table=self::TABLE_NAME;
        $sql="SELECT * FROM {$table} WHERE login=:login";
        $q=$this->_conn->prepare($sql);
        $q->execute(array(
            'login'=>$login
        ));
        $r=$q->fetch();
        if($r){
            return new User($r['login'],$r['salt'],$r['saltPassword']);
        }else{
            return false;
        }
    }
    public function getAllUsers(){

        $q=$this->_conn=query("SELECT * FROM ".self::TABLE_NAME,PDO::FETCH_ASSOC);
        while($r=$q->fetch()){
            $result[$r['login']]=new User($r['login'],$r['salt'].$r['saltPassword']);
        }
        return $result;
    }
}