<?php
ini_set('display_errors', 'On');
ini_set('display_errors', 1);
include_once(__DIR__."/pages/conexion.php");

$sql = "SELECT * FROM dyalogo_canales_electronicos.dy_chat_configuracion WHERE dentro_horario_detalle_accion IS NOT NULL AND fuera_horario_detalle_accion IS NOT NULL";
$res = $mysqli->query($sql);

if($res && $res->num_rows > 0){

    while($row = $res->fetch_object()){

        // Valido que dentro de horario sea 1
        if($row->dentro_horario_accion == 1){

            // Ahora miro si esa campana ya esta configurada
            $campana = obtenerCampana($row->dentro_horario_detalle_accion);
            
            if(!is_null($campana) && is_null($campana->CACOCH_ConsInte__b)){

                // Significa que no esta creado, creemoslo
                crear(
                    $campana->CAMPAN_ConsInte__b,
                    $row->frase_agente_asignado,
                    $row->frase_sin_agentes_disponibles,
                    $row->tiempo_maximo_asignacion,
                    $row->frase_tiempo_asignacion_excedido,
                    $row->tiempo_maximo_inactividad_cliente,
                    $row->frase_inactividad_cliente,
                    $row->tiempo_maximo_inactividad_agente,
                    $row->frase_inactividad_agente
                );
            }

        }
        
        // Si son diferentes debo actualizar el fuera de horario tambien
        if($row->dentro_horario_detalle_accion != $row->fuera_horario_detalle_accion){

            // Valido que sea solo tipo accion 1 el fuera de horario
            if($row->fuera_horario_accion == 1){

                // Ahora miro si esa campana ya esta configurada
                $campana = obtenerCampana($row->fuera_horario_detalle_accion);
                
                if(!is_null($campana) && is_null($campana->CACOCH_ConsInte__b)){

                    // Significa que no esta creado, creemoslo
                    crear(
                        $campana->CAMPAN_ConsInte__b,
                        $row->frase_agente_asignado,
                        $row->frase_sin_agentes_disponibles,
                        $row->tiempo_maximo_asignacion,
                        $row->frase_tiempo_asignacion_excedido,
                        $row->tiempo_maximo_inactividad_cliente,
                        $row->frase_inactividad_cliente,
                        $row->tiempo_maximo_inactividad_agente,
                        $row->frase_inactividad_agente
                    );
                }

            }

        }

    }

}

function obtenerCampana($id){
    
    global $mysqli;

    $campana = null;

    $sqlCamapa = "SELECT * FROM DYALOGOCRM_SISTEMA.CAMPAN
                    LEFT JOIN DYALOGOCRM_SISTEMA.CAMPAN_CONFIGURACION_CHAT ON CAMPAN_ConsInte__b = CACOCH_ConsInte__CAMPAN_b
                WHERE CAMPAN_IdCamCbx__b = {$id} LIMIT 1";

    $res = $mysqli->query($sqlCamapa);

    if($res && $res->num_rows > 0){
        $campana = $res->fetch_object();
    }
    
    return $campana;
}

function crear($campanaId, $agente_asignado_frase, $en_espera_frase, $tiempo_asignacion_excedido, $asignacion_excedido_frase, $tiempo_maximo_inactividad_cliente, $inactividad_cliente_frase, $tiempo_maximo_inactividad_agente, $inactividad_agente_frase){

    global $mysqli;

    $cierre_chat_frase = "Gracias por comunicarte con nosotros, Hasta luego";

    // Inserto
    $sql = "INSERT INTO DYALOGOCRM_SISTEMA.CAMPAN_CONFIGURACION_CHAT (CACOCH_ConsInte__CAMPAN_b, CACOCH_CierreChatFrase_b, CACOCH_CierreChatEnviarBot_b, CACOCH_CierreChatIdEstpasBot_b, CACOCH_CierreChatIdAutorespuesta_b, 
    CACOCH_EnEsperaIntervaloMensaje_b, CACOCH_EnEsperaFrase_b, CACOCH_EnEsperaEnviarBot_b, CACOCH_EnEsperaIdEstpasBot_b, CACOCH_EnEsperaIdAutorespuesta_b, 
    CACOCH_AsignacionExcedidaTiempo_b, CACOCH_AsignacionExcedidaFrase_b, CACOCH_AsignacionExcedidaEnviarBot_b, CACOCH_AsignacionExcedidaIdEstpasBot_b, CACOCH_AsignacionExcedidaIdAutorespuesta_b, 
    CACOCH_InactividadClienteTiempo_b, CACOCH_InactividadClienteFrase_b, CACOCH_InactividadClienteEnviarBot_b, CACOCH_InactividadClienteIdEstpasBot_b, CACOCH_InactividadClienteIdAutorespuesta_b, 
    CACOCH_InactividadAgenteTiempo_b, CACOCH_InactividadAgenteFrase_b, CACOCH_InactividadAgenteEnviarBot_b, CACOCH_InactividadAgenteIdEstpasBot_b, CACOCH_InactividadAgenteIdAutorespuesta_b, 
    CACOCH_AgenteAsignadoFrase_b, CACOCH_InactividadAgenteActivarTimeout_b, CACOCH_InactividadClienteActivarTimeout_b) VALUES (
        {$campanaId},'{$cierre_chat_frase}', 0, 0, 0,
        1,'{$en_espera_frase}', 0, 0, 0,
        {$tiempo_asignacion_excedido},'{$asignacion_excedido_frase}', 0, 0, 0,
        {$tiempo_maximo_inactividad_cliente},'{$inactividad_cliente_frase}', 0, 0, 0,
        {$tiempo_maximo_inactividad_agente},'{$inactividad_agente_frase}', 0, 0, 0,
        '{$agente_asignado_frase}', 1, 1
    )";

    if($mysqli->query($sql)){
        echo "ejecucion existosa para la campana {$campanaId}";
    }else{
        echo "error al ejecutar {$sql}";
    }
}