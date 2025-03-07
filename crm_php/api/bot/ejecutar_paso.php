<?php

include_once('../../conexion.php');
include_once('../../funciones.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST'){

    $data = json_decode(file_get_contents('php://input'), true);

    // Debe llegar el paso que se va a insertar y el registro que se insertaria
    if(!isset($data['registroId']) || !isset($data['pasoDestino'])){
        header("HTTP/1.1 400 Bad Request");
        exit;
    }

    // Ya teniendo el paso buscamos su muestra
    $sql = "SELECT ESTPAS_ConsInte__b AS id, ESTPAS_Comentari_b AS nombre, ESTPAS_Tipo______b AS tipo, ESTPAS_ConsInte__CAMPAN_b AS campanId, ESTPAS_ConsInte__MUESTR_b AS muestra, ESTPAS_activo AS activo, ESTRAT_ConsInte__PROYEC_b AS huesped, ESTRAT_ConsInte_GUION_Pob AS bd 
        FROM {$BaseDatos_systema}.ESTPAS INNER JOIN {$BaseDatos_systema}.ESTRAT ON ESTRAT_ConsInte__b = ESTPAS_ConsInte__ESTRAT_b
    WHERE ESTPAS_ConsInte__b = {$data['pasoDestino']}";

    $res = $mysqli->query($sql);

    $muestra = 0;

    // Paso intermedio, si el paso destino tiene x id debo hacer una actualizacion en la bd expecifica solo porque no esta la funcionalidad
    if($data['pasoDestino'] == 3937 || $data['pasoDestino'] == 3939 || $data['pasoDestino'] == 3940 || $data['pasoDestino'] == 3941 || $data['pasoDestino'] == 3942){
        
        // Dyana
        if($data['pasoDestino'] == 3937){
            $base = 'G2490';
            $gestionBot = 'G2740';
        }

        // Tecnilalo Dentro horario
        if($data['pasoDestino'] == 3939){
            $base = 'G2859';
            $gestionBot = 'G2902';
        }

        // Tecnilalo fuera horario
        if($data['pasoDestino'] == 3940){
            $base = 'G2859';
            $gestionBot = 'G2915';
        }

        // Contigo
        if($data['pasoDestino'] == 3941){
            $base = 'G1486';
            $gestionBot = 'G2761';
        }

        // Cateterismo
        if($data['pasoDestino'] == 3942){
            $base = 'G2391';
            $gestionBot = 'G2929';
        }

        // Traigo el ultimo registro del bot para obtener el id de la comunicacion
        $sql = "SELECT {$gestionBot}_ConsInte__b AS id, {$gestionBot}_LinkContenido AS link FROM {$BaseDatos}.{$gestionBot} WHERE {$gestionBot}_CodigoMiembro = {$data['registroId']} ORDER BY {$gestionBot}_ConsInte__b DESC LIMIT 1";
        $res1 = $mysqli->query($sql);

        if($res1 && $res1->num_rows > 0){

            $ges = $res1->fetch_object();

            // Actualizo el campo de link de contenido para que envie la conversacion
            $sql = "UPDATE {$BaseDatos}.{$base} SET {$base}_LinkContenidoUG_b = '{$ges->link}', {$base}_LinkContenidoGMI_b = '{$ges->link}' WHERE {$base}_ConsInte__b = {$data['registroId']}";
            $mysqli->query($sql);
        }

    }

    if($res && $res->num_rows > 0){
        $paso = $res->fetch_object();
        
        // Si es campaña me toca buscar la muestra por otro lado
        if($paso->tipo == 6){
            $sql = "SELECT CAMPAN_ConsInte__b AS id, CAMPAN_Nombre____b AS nombre, CAMPAN_ConsInte__MUESTR_b AS muestra FROM {$BaseDatos_systema}.CAMPAN WHERE CAMPAN_ConsInte__b = {$paso->campanId}";
            $resCamp = $mysqli->query($sql);

            if($resCamp && $resCamp->num_rows > 0){
                $campana = $resCamp->fetch_object();
                $muestra = $campana->muestra;
            }
        }else{
            $muestra = $paso->muestra;
        }
        
        // Validamos si el registro ya esta en la muestra
        $sql = "SELECT * FROM {$BaseDatos}.G{$paso->bd}_M{$muestra} WHERE G{$paso->bd}_M{$muestra}_CoInMiPo__b = {$data['registroId']}";
        $resMuestra = $mysqli->query($sql);

        if($resMuestra && $resMuestra->num_rows > 0){

            // Toca obtener datos del la base de datos
            $sql = "SELECT G{$paso->bd}_ConsInte__b AS id, IF(G{$paso->bd}_ClasificacionUG_b=2, 2, 3) AS clasificacion WHERE G{$paso->bd}_ConsInte__b = {$data['registroId']}";
            $res = $mysqli->query($sql);

            $clasificacion = 3;

            if($res && $res->num_rows > 0){
                $dataDos = $res->fetch_object();
                $clasificacion = $dataDos->clasificacion;
            }

            // Actualizar muestra
            $sqlMuestra = "UPDATE {$BaseDatos}.G{$paso->bd}_M{$muestra} SET G{$paso->bd}_M{$muestra}_Activo____b = -1, G{$paso->bd}_M{$muestra}_Estado____b = 0, G{$paso->bd}_M{$muestra}_NumeInte__b = 0, G{$paso->bd}_M{$muestra}_ConUltGes_b = {$clasificacion} WHERE G{$paso->bd}_M{$muestra}_CoInMiPo__b = ".$data['registroId'];
        }else{
            // Insertamos como nuevo
            $sqlMuestra = "INSERT INTO {$BaseDatos}.G{$paso->bd}_M{$muestra} (G{$paso->bd}_M{$muestra}_CoInMiPo__b, G{$paso->bd}_M{$muestra}_Activo____b, G{$paso->bd}_M{$muestra}_Estado____b, G{$paso->bd}_M{$muestra}_TipoReintentoGMI_b, G{$paso->bd}_M{$muestra}_NumeInte__b, G{$paso->bd}_M{$muestra}_CantidadIntentosGMI_b, G{$paso->bd}_M{$muestra}_ConUltGes_b) SELECT G{$paso->bd}_ConsInte__b,-1 AS Activo____b, 0 AS Estado____b, 0 AS TipoReintentoGMI_b, 0 AS NumeInte__b, 0 AS CantidadIntentosGMI_b, IF(G{$paso->bd}_ClasificacionUG_b=2, 2, 3) AS ConUltGes_b FROM {$BaseDatos}.G{$paso->bd} WHERE G{$paso->bd}_ConsInte__b = " . $data['registroId'];
        }
        
        $mysqli->query($sqlMuestra);

        if($paso->tipo == 7 || $paso->tipo == 8){
            procesarPaso($data['pasoDestino'], $paso->bd, $muestra, $data['registroId']);
        }

        if($paso->tipo == 13){
            procesarPasoWhatsapp($data['pasoDestino'], $paso->bd, $muestra, $data['registroId'], $paso->huesped);
        }
    }

    echo json_encode([
        "estado" => "ok"
    ]);
}