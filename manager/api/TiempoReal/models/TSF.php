<?php

class TSF
{
    protected static $db;

    private $saltoLinea;

    public function __construct()
    {
        self::$db = Helpers::connect();
        $this->saltoLinea = (php_sapi_name() == "cli") ? "{\n}" : "</br>";
    }


    // SE OBTIENE TODOS LOS PASOS AMARILLOS Y CAMPAÑAS DE CADA ESTRATEGIA

    public function getTsfTel($idCbxCampan)
    {
        $strSQLTsf = "SELECT nivel_servicio  FROM dyalogo_telefonia.dy_informacion_actual_campanas where fecha = date(now()) and id_campana =  '{$idCbxCampan}' limit 1;";

        echo ("Consulta SQL:  TSF Campan {$idCbxCampan}: {$strSQLTsf} {$this->saltoLinea}");

        $resSQLTsf = self::$db->query($strSQLTsf);
        if ($resSQLTsf) {
            if ($resSQLTsf->num_rows > 0) {
                return intval($resSQLTsf->fetch_object()->nivel_servicio);
            }
        }

        return 0;
    }


    public function getTsfChat($idCbxCampan, $tipoPaso, $secMeta)
    {

        $secMeta = ($secMeta != null | $secMeta != "") ? $secMeta : 20;

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


        $strSQLConteoQueCumple = "SELECT count(*) as conteo FROM dyalogo_canales_electronicos.dy_chat_entrantes e JOIN dyalogo_canales_electronicos.dy_chat_configuracion c on e.id_configuracion = c.id WHERE date(e.fecha_hora) = date(now()) and e.tiempo_espera <= '{$secMeta}' and e.estado > 2 and e.id_cola_distribucion_asignada = '{$idCbxCampan}' and not fecha_hora_asignacion is null  and c.integrado_con = '{$strChannel}'";


        echo ("Consulta SQL:  Conteo que cumple la meta campan {$idCbxCampan}: {$strSQLConteoQueCumple} {$this->saltoLinea}");


        $resSQLConteoQueCumple = self::$db->query($strSQLConteoQueCumple);
        if ($resSQLConteoQueCumple) {
            if ($resSQLConteoQueCumple->num_rows > 0) {
                $numChatMeta = $resSQLConteoQueCumple->fetch_object()->conteo;
            }
        }


        $strSQLConteoTotal = "SELECT count(*) as conteo FROM dyalogo_canales_electronicos.dy_chat_entrantes e JOIN dyalogo_canales_electronicos.dy_chat_configuracion c on e.id_configuracion = c.id WHERE date(e.fecha_hora) >= date(now()) and e.id_cola_distribucion_asignada = '{$idCbxCampan}'  and c.integrado_con = '{$strChannel}'";


        echo ("Consulta SQL:  Conteo total campan {$idCbxCampan}: {$strSQLConteoTotal} {$this->saltoLinea}");


        $resSQLConteoTotal = self::$db->query($strSQLConteoTotal);
        if ($resSQLConteoTotal) {
            if ($resSQLConteoTotal->num_rows > 0) {
                $numChatTotal = $resSQLConteoTotal->fetch_object()->conteo;
            }
        }


        if (isset($numChatMeta) && isset($numChatTotal)) {
            if($numChatTotal > 0){
                return ($numChatMeta / $numChatTotal) * 100;
            }
        }

        return 0;
    }

    public function getTsfMail($idCbxCampan, $tipoPaso, $secMeta)
    {


        $secMeta = ($secMeta != null || $secMeta != "") ? $secMeta : 20;
        
        switch ($tipoPaso) {
            case 17:
                $strChannel = "email";
                break;

            case 19:
                $strChannel = "webform";
                break;
        }


        $strSQLConteoQueCumple = "SELECT COUNT(1) as conteo from dyalogo_canales_electronicos.dy_ce_entrantes where estado != 1 and origen = '{$strChannel}' and date(fecha_hora) = date(now()) and id_cola_distribucion = '{$idCbxCampan}' AND TIMESTAMPDIFF(SECOND, fecha_hora_recibido_servidor, fecha_hora_asignacion) <= '{$secMeta}'";


        echo ("Consulta SQL: Conteo que cumple la meta  campan {$idCbxCampan}: {$strSQLConteoQueCumple} {$this->saltoLinea}");


        $resSQLConteoQueCumple = self::$db->query($strSQLConteoQueCumple);
        if ($resSQLConteoQueCumple) {
            if ($resSQLConteoQueCumple->num_rows > 0) {
                $numEmailMeta = $resSQLConteoQueCumple->fetch_object()->conteo;
            }
        }


        $strSQLConteoTotal = "SELECT COUNT(1) as conteo from dyalogo_canales_electronicos.dy_ce_entrantes where estado != 1 and origen = '{$strChannel}' and date(fecha_hora) = date(now()) and id_cola_distribucion = '{$idCbxCampan}'";


        echo ("Consulta SQL:  Conteo total campan {$idCbxCampan}: {$strSQLConteoTotal} {$this->saltoLinea}");


        $resSQLConteoTotal = self::$db->query($strSQLConteoTotal);
        if ($resSQLConteoTotal) {
            if ($resSQLConteoTotal->num_rows > 0) {
                $numEmailTotal = $resSQLConteoTotal->fetch_object()->conteo;
            }
        }


        if (isset($numEmailTotal) && isset($numEmailMeta)) {
            if($numEmailTotal > 0){
                return ($numEmailMeta / $numEmailTotal) * 100;
            }
        }

        return 0;
    }
}
