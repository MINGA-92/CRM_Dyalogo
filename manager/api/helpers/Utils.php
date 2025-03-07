<?php
class Helpers{

    //CONEXIÓN A LA BASE DE DATOS
    public static function connect(){
        return Database::connect();
    }

    //CONEXIÓN A LA BASE DE DATOS DE MIDDLEWARE
    public static function connectMiddleware(){
        return DatabaseMiddleware::connectMiddleware();
    }

    //CONEXIÓN A LA BASE DE DATOS DE BPO
    public static function connectBPO(){
        return Database::connectBPO();
    }

    // VALIDAR CREDENCIALES DE AUTENTICACIÓN
    public static function auth(array $data):void
    {
        $usuario=isset($data['strUsuario_t']) && $data['strUsuario_t'] == 'crm' ? true : false;
        $token=isset($data['strToken_t']) && $data['strToken_t'] == 'D43dasd321' ? true : false;

        if(!$usuario || !$token){
            showResponse("Usuario no se autentico",false,401,"401 Unauthorizedd");
        }
    }

    //VALIDAR QUE EL METODO DEL REQUEST SEA EL ACEPTADO PARA EL METODO
    public static function method(string $request, string $method):void
    {
        if($request != $method){
            showResponse("El metodo de peticion enviado no es soportado para esta solicitud");
        }
    }

    public static function encrypt(string $string):String
    {
        return md5("p.fs@3!@M".$string);
    }

    public static function decodeId($id,$campo,$table):int
    {
        $db = Database::connect();
        $return=false;
        $id=$db->query("SELECT {$campo} FROM {$table} WHERE md5(concat('p.fs@3!@M',{$campo})) ='{$id}'");
        if($id && $id->num_rows==1){
            $id=$id->fetch_object();
            $return=$id->$campo;
        }else{
            showResponse("El id no existe");
        }
        return $return;
    }

    public static function decodeIdBPO($id,$campo,$table):int
    {
        $db = Database::connectBPO();
        $return=false;
        $id=$db->query("SELECT {$campo} FROM {$table} WHERE md5(concat('p.fs@3!@M',{$campo})) ='{$id}'");
        if($id && $id->num_rows==1){
            $id=$id->fetch_object();
            $return=$id->$campo;
        }else{
            showResponse("El id en BPO no existe");
        }
        return $return;
    }
}