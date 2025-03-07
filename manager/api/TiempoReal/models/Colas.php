<?php

include_once(__DIR__ . "/../../helpers/WSCoreClient.php");

class Colas
{

    protected static $db;

    public function __construct()
    {
        self::$db = Helpers::connect();
    }


    // SE OBTIENE TODOS LOS PASOS AMARILLOS Y CAMPAÑAS DE CADA ESTRATEGIA

    public function getColaTel($idCola)
    {
        return json_decode(metricaColaPredictivo($idCola));
    }


    public function getColaChat($idCola, $tipoPaso)
    {

        switch ($tipoPaso) {
            case 14:
                $strChannel = "web";
                break;

            case 15:
                $strChannel = "whatsapp";
                break;

            case 16:
                $strChannel = "facebook";
                break;

            case 20:
                $strChannel = "instagram";
                break;
        }

        $strSQLcola = "SELECT COUNT(1) AS conteo FROM dyalogo_canales_electronicos.dy_chat_entrantes e JOIN dyalogo_canales_electronicos.dy_chat_configuracion c on e.id_configuracion = c.id WHERE e.estado = 1 and e.id_cola_distribucion_asignada = '{$idCola}' and date(e.fecha_hora) >= date(now()) and  c.integrado_con = '{$strChannel}'";

        echo ("Consulta SQL:  Cola Chat: {$idCola} Canal: {$tipoPaso} = {$strSQLcola} \n");

        $resSQLcola = self::$db->query($strSQLcola);
        if ($resSQLcola) {
            if ($resSQLcola->num_rows > 0) {
                return intval($resSQLcola->fetch_object()->conteo);
            }
        }

        return 0;
    }

    public function getColaMail($idCola, $tipoPaso)
    {
        switch ($tipoPaso) {
            case 17:
                $strChannel = "email";
                break;

            case 19:
                $strChannel = "webform";
                break;

        }

        $strSQLcola = "SELECT COUNT(1) as conteo FROM dyalogo_canales_electronicos.dy_ce_espera e join dyalogo_canales_electronicos.dy_ce_entrantes s on s.id = e.id_entrante where id_cola = '{$idCola}' and  origen = '{$strChannel}'";

        echo ("Consulta SQL:  Cola Mail: {$idCola} Canal: {$tipoPaso} = {$strSQLcola} \n");

        $resSQLcola = self::$db->query($strSQLcola);
        if ($resSQLcola) {
            if ($resSQLcola->num_rows > 0) {
                return intval($resSQLcola->fetch_object()->conteo);
            }
        }

        return 0;
    }


    public function getAllColas($infoCampan){
        $arrColas = [];

        $arrTypeChannels = [1, 14,15,16,17,19,20];


        foreach($arrTypeChannels as $typeChannel){
            switch($typeChannel){
                case 1:
                    $cola_req = $this->getColaTel($infoCampan->nombre_interno);
                    echo ("Respuesta WS Java: " . json_encode($cola_req)." \n");

                    if($cola_req->strEstado_t == "ok"){
                        $cola_value = $cola_req->objSerializar_t->{$infoCampan->nombre_interno};
                    }else{
                        $cola_value = 0;
                    }
                    break;


                    break;
                case 14: case 15: case 16: case 20:
                    $cola_value =$this->getColaChat($infoCampan->id_campana,$typeChannel );
                    break;
                case 17: case 19:
                    $cola_value = $this->getColaMail($infoCampan->id_campana,$typeChannel );
                    break;
            }

            array_push($arrColas, (object)["value" => $cola_value, "typeChannel" => $typeChannel]);
        }

        return $arrColas;
    }
}
