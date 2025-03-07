<?php

use Illuminate\Support\Arr;

    session_start();
    ini_set('display_errors', 'On');
    ini_set('display_errors', 1);
    include(__DIR__."/../../conexion.php");
    require_once(__DIR__.'/../../../../helpers/parameters.php');

    /**
     *BGCR - En esta funcion consultamos la informacion general de la llamada
     *@param idTelephony = id de telefonia
     *@return object. 
     */

    function getDetailCall($idTelephony){
        global $mysqli;
        global $BaseDatos_telefonia;

        // SE CONSULTA LA INFORMACION GENERAL DE LA LLAMADA
        $SqlGeneralData = $mysqli->query("SELECT  fecha_hora, llamada_id_asterisk, fecha_hora_final, sentido , tipo_llamada , troncal,dnis, numero_telefonico , IF(disa, 'SI', 'NO') disa, origen_disa, resultado, extension, IF(transferida, 'SI', 'NO') AS trans, SUBSTRING(SEC_TO_TIME(duracion_total),1,8) AS duracion, SUBSTRING(SEC_TO_TIME(tiempo_espera),1,8) AS espera, SUBSTRING(SEC_TO_TIME(duracion_al_aire),1,8) AS al_aire, redondeo_minuto, costo_llamada, SUBSTRING(SEC_TO_TIME(tiempo_timbrando),1,8) AS timbrado, SUBSTRING(SEC_TO_TIME(tiempo_espera_llamada_activa),1,8) AS llamada_activa, agente_nombre, identificacion_agente, campana, quien_completo, nombre_tipificacion , respuesta_encuesta, etiqueta  FROM {$BaseDatos_telefonia}.dy_v_historico_llamadas WHERE llamada_id_asterisk = '{$idTelephony}'");
        $resGeneralData = null;

        if($SqlGeneralData && $SqlGeneralData->num_rows > 0){
            $resGeneralData = $SqlGeneralData->fetch_object();
        }

        return $resGeneralData;
    }


    /**
     *BGCR - En esta funcion consultamos la informacion de cdr
     *@param idTelephony = id de telefonia
     *@return object. 
     */


    function getDetailPlant($idTelephony){
        global $mysqli;

        // SE CONSULTA LA INFORMACION GENERAL DE LA LLAMADA
        $SqlCdrData = $mysqli->query("SELECT userfield, calldate, clid, src, dst, dcontext, channel, dstchannel, lastapp, lastdata, duration, billsec, disposition FROM asterisk.cdr WHERE uniqueid = '{$idTelephony}'");
        $data = array();

        if($SqlCdrData && $SqlCdrData->num_rows > 0){
            while ($obj = $SqlCdrData->fetch_object()) {
                array_push($data, $obj);
            }
        }

        return $data;
    }

     /**
     *BGCR - En esta funcion consultamos la informacion de detalle de ACD
     *@param idTelephony = id de telefonia
     *@return object. 
     */


    function getDetailACD($idTelephony){
        global $mysqli;

        // SE CONSULTA LA INFORMACION GENERAL DE LA LLAMADA
        $SqlGeneralACD = $mysqli->query("select fecha_hora, cola, agente, evento, datos from asterisk.v_queue_log where unique_id= '{$idTelephony}'");
        $data = array();
        if($SqlGeneralACD && $SqlGeneralACD->num_rows > 0){
            while ($obj = $SqlGeneralACD->fetch_object()) {
                array_push($data, $obj);
            }
        }

        return $data;
    }

    // SE DEVUELVE TODA LA INFO

    if (isset($_GET["getData"])) {

        $idTelephony = (isset($_POST["idTelephony"])) ? $_POST["idTelephony"] : null;
        $arrData = array(
            "call" => getDetailCall($idTelephony),
            "plant" => getDetailPlant($idTelephony),
            "acd" => getDetailACD($idTelephony)
        );

        echo json_encode($arrData);
        
    }
    
?>