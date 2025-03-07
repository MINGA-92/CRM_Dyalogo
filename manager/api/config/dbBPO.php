<?php
class DatabaseBPO{
    public static function connectBPO(){
        $dbBPO = new mysqli('172.25.0.3','dyremadm','dy4dmiN4pp*bd');
        $dbBPO->query("set names 'utf8'");
        return $dbBPO;
    }
}