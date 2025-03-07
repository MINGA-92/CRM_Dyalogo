<?php

include_once(__DIR__."/../../configuracion/configuracion.php");
include_once(__DIR__.'/../../conexion.php');
include_once(__DIR__.'/../../funciones.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST'){
    
    header('Content-Type: application/json');
    $data = json_decode(file_get_contents('php://input'), true);

    $credencialesValidas = false;

    // Valido que las credenciales de auth esten bien
    if(isset($data['strUsuario_t']) && $data['strUsuario_t'] == 'crm'){
        if(isset($data['strToken_t']) && $data['strToken_t'] == 'D43dasd321'){
            $credencialesValidas = true;
        }
    }

    if($credencialesValidas){

        // Validamos que el paso venga en el consumo
        if(!isset($data['pasoId_t'])){
            header("HTTP/1.1 200 OK");
            echo json_encode(["status" => "fallo", "mensaje" => "Se debe agregar todos los parametros en el envio de la peticion"]);
            exit;
        }

        // Apagamos el paso 
        // $sql = "UPDATE {$BaseDatos_systema}.ESTPAS SET ESTPAS_activo = 0 WHERE ESTPAS_ConsInte__b = {$data['pasoId_t']}";
        // $mysqli->query($sql);

        // Agrego el mensaje de respuesta en el log
        date_default_timezone_set('America/Bogota');
        $fechaActual = date('Y-m-d h:i:s');

        $mensajeError = "Error {$fechaActual} - RegId: ".$data['registroId_t']." Mensaje: {$data['mensajeError']}";
        $mensajeError = str_replace("'", "",  $mensajeError);

        $sql = "UPDATE dyalogo_canales_electronicos.dy_wa_plantillas_salientes SET mensajes_estado = '{$mensajeError}' WHERE id_estpas = {$data['pasoId_t']}";
        $mysqli->query($sql);

        // Buscamos la informacion del paso
        $sql = "SELECT ESTPAS_ConsInte__b AS id, ESTPAS_Comentari_b AS nombre, ESTRAT_Nombre____b AS nombre_estrategia, ESTRAT_ConsInte__PROYEC_b AS huesped_id, ESTRAT_ConsInte_GUION_Pob AS bdId, ESTPAS_ConsInte__MUESTR_b AS muestraId from {$BaseDatos_systema}.ESTPAS INNER JOIN {$BaseDatos_systema}.ESTRAT ON ESTRAT_ConsInte__b = ESTPAS_ConsInte__ESTRAT_b WHERE ESTPAS_ConsInte__b = ".$data['pasoId_t']." LIMIT 1";
        $resPaso = $mysqli->query($sql);

        $paso = $resPaso->fetch_object();

        // Generamos el correo para notificar el error
        $sql = "SELECT * FROM dyalogo_canales_electronicos.dy_wa_plantillas_salientes WHERE id_estpas = {$data['pasoId_t']}";
        $res = $mysqli->query($sql);

        $mensajeNotificacion = 'No se ha enviado ninguna notificacion';

        if($res && $res->num_rows > 0){
            $plantilla = $res->fetch_object();

            // Validamos si no se ha enviado
            if($plantilla->mensaje_enviado == 0 && (!is_null($plantilla->correos_envio_mensaje) && $plantilla->correos_envio_mensaje != '')){

                $to = str_replace(" ","", $plantilla->correos_envio_mensaje);

                $mensaje = "<p>El envío de plantillas de WhatsApp del paso ".$paso->nombre." de la estrategia ".$paso->nombre_estrategia." se detuvo porque el proceso de envío genero un error, entrar en la configuración del paso, revisar que este bien y encender el paso.</p><p>(Este problema es debido a que la plataforma de META retorno un error durante el envío del mensaje de uno de los registros)</p>";
                // $mensaje = "<p>El envio de plantillas de WhatsApp del paso Prueba Plantilla 1 de la estrategia BS TEST v1 se detuvo porque el proceso de envio genero un error, entrar en la configuracion del paso, revisar que este bien y encender el paso.</p><p>(Este problema es debido a que la plataforma de META retorno un error durante el envio del mensaje de uno de los registros)</p>";

                // Ejecuto el servicio de enviar el mensaje
                $dataCorreo =[
                    "strUsuario_t" => "crm",
                    "strToken_t" => "D43dasd321", 
                    "strPara_t" => $to, 
                    "strConCopia_t" => "", 
                    "strConCopiaOculta_t" => "", 
                    "strAsunto_t" => "Paso ".$paso->nombre." desactivado", 
                    "strCuerpo_t" => $mensaje,
                    "intIdHuesped_t" => $paso->huesped_id
                ];
                $mensajeNotificacion = consumirWSJSON("http://localhost:8080/dyalogocore/api/notificaciones/notificar", $dataCorreo);
                
                $update = "UPDATE dyalogo_canales_electronicos.dy_wa_plantillas_salientes SET mensaje_enviado = 1 WHERE id_estpas = {$data['pasoId_t']}";
                $mysqli->query($update);
            }

        }

        // Si se daña guardamos en la muestra con el error que genera
        $updateError = "UPDATE {$BaseDatos}.G{$paso->bdId}_M{$paso->muestraId} SET G{$paso->bdId}_M{$paso->muestraId}_Comentari_b = 'fallo - {$mensajeError}' WHERE G{$paso->bdId}_M{$paso->muestraId}_CoInMiPo__b = {$data['registroId_t']}";
        $mysqli->query($updateError);

        // Al generarse un error significaria que la plantilla no se envio por lo tanto toca setear la bd a null
        $updateBd = "UPDATE {$BaseDatos}.G{$paso->bdId} SET G{$paso->bdId}_Template_b = NULL, G{$paso->bdId}_TemplateFechaEnvio_b = NULL WHERE G{$paso->bdId}_ConsInte__b = {$data['registroId_t']}";
        $mysqli->query($updateBd);

        header("HTTP/1.1 200 OK");
        echo json_encode(["status" => "ok", "mensaje" => "Paso desactivado", "mensaje_notificacion" => $mensajeNotificacion]);
        exit;
    }

}

header("HTTP/1.1 400 Bad Request");
?>