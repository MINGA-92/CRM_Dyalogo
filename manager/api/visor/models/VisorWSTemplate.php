<?php

class VisorWSTemplate{
    private static $dbMiddeware;
    private static $db;
    private $id;
    private $pasoId;


    public function __construct()
    {
        self::$dbMiddeware = Helpers::connectMiddleware();
        self::$db = Helpers::connect();
    }


    public function setId(string $id):void
    {
        $this->id = $id;
    }

    public function getId():string 
    {
        return $this->id;
    }


    public function setpasoId(int $pasoId):void
    {
        $this->pasoId = $pasoId;
    }

    public function getpasoId():int 
    {
        return $this->pasoId;
    }

    // SE OBTIENEN TODOS LA INFORMACION ENVIADA EN LA PLANTILLA
    public function getIncomingParameters(){
        $response=[];
        $sql = self::$dbMiddeware->query("SELECT S.fecha_hora as fecha, S.parametros_entrantes AS parametros_entrantes, S.ws_destino AS destino, C.cuenta as origen, C.proveedor as proveedor, S.id_estpas as id_estpas FROM dy_whatsapp.plantillas_salientes S JOIN dy_whatsapp.canales C ON C.id = S.id_canal WHERE  MD5(CONCAT('p.fs@3!@M',S.id)) = '{$this->id}'");
        if($sql){
            if($sql->num_rows == 1){
                $response=$sql->fetch_array();
            }else{
                showResponse();
            }
        }else{
            $this->error();
        }

        return $response;

    }


    // SE OBTIENE EL TEXTO DEL TEMPLATE
    public function getTemplate(int $id_estpas){
        $response=[];
        $sql = self::$db->query("SELECT texto FROM dyalogo_canales_electronicos.dy_wa_plantillas WHERE id = (SELECT id_wa_plantilla FROM dyalogo_canales_electronicos.dy_wa_plantillas_salientes WHERE id_estpas = {$id_estpas})");
        if($sql){
            if($sql->num_rows == 1){
                $response=$sql->fetch_array();
            }else{
                showResponse();
            }
        }else{
            $this->error();
        }

        return $response;
    }
    


    // SE CONSTRUYE EL TEXTO ENVIADO DEPENDIENDO LOS PARAMENTROS Y LA PLANTILLA
    public function buildMessage(object $incomingParameters, string $textTemplate):string
    {
        $message = $textTemplate;

        foreach ($incomingParameters as $key => $value) {
            $message = str_replace($key, $value, $message);
        }
        return $message;
    }
    
    // MOSTRAR ERROR CUANDO EL SISTEMA NO PUEDA COMPLETAR UNA SOLICITUD
    private function error():void
    {
        showResponse("No se pudo completar la solicitud",false,500,"500 Internal Server Error");
    }

    
    public function buildPdf(string $message, $messageInfo):void
    {
        include_once './visor/models/bulidPdf.php';
    }

}

?>