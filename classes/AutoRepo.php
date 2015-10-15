<?php
/**
 * Created by PhpStorm.
 * User: Ivan
 * Date: 14.10.2015
 * Time: 0:46
 */

class AutoRepo{
    const TABLE_NAME = 'auto';
//    protected $_conn;
    public function __construct(){
        $this->_conn = DB::getConnection();
    }
    public function Autos() {
        $autos = array();
        $table = self::TABLE_NAME;
        $q = $this->_conn->query("SELECT * FROM {$table}", PDO::FETCH_ASSOC);
        while( $r = $q->fetch() ) {
            $autos[$r['id']] = new Auto($r['id'],$r['model'], $r['year'],$r['run'], $r['power'], $r['isAutoTrans'], $r['is4wd'],
                $r['citycode'], new Price($r['value'], $r['currency']));
        }
        return $autos;
    }
}