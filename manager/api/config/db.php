<?php

class Database{

    public static function connect(){
        $db = new mysqli('172.25.0.3','dyremadm','dy4dmiN4pp*bd');
        $db->query("set names 'utf8'");
        return $db;
    }

    public static function connectBPO(){
        $dbBPO = new mysqli('172.25.0.5','eyrs','eyrs-ykfviNOJvY');
        $dbBPO->query("set names 'utf8'");
        return $dbBPO;
    }
}