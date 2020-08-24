<?php
class MyDB extends SQLite3
{
    public function __construct(){
    $this->open('bs.db');
    }

}
    $db = new MyDB();
     if (!$db) {
     $arr = array('code' => '1', 'msg' => $db->lastErrorMsg());
     echo json_encode($arr);
     die();
     }
