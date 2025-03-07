<?php  

class DatabaseMiddleware {
    public static function connectMiddleware(){
        $dbMiddleware = new mysqli('10.142.0.13','dyhttpd','svr4app12*');
        $dbMiddleware->query("set names 'utf8'");
        return $dbMiddleware;
    }
}
?>