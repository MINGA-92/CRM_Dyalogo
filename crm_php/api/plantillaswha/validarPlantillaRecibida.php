<?php

include_once('../../conexion.php');
date_default_timezone_set('America/Bogota');

if ($_SERVER['REQUEST_METHOD'] == 'POST'){
    header('Content-Type: application/json');
    $data = json_decode(file_get_contents('php://input'), true);

    $credencialesValidas = true;

    // // Valido que las credenciales de auth esten bien
    // if(isset($data['strUsuario_t']) && $data['strUsuario_t'] == 'crm'){
    //     if(isset($data['strToken_t']) && $data['strToken_t'] == 'D43dasd321'){
    //         $credencialesValidas = true;
    //     }
    // }

    if($credencialesValidas){

        // Validamos si existe el id del paso o el nombre del paso al cual vamos a buscar
        $bdId = isset($data['bdId']) ? $data['bdId'] : null;
        $campoId = isset($data['campoId']) ? $data['campoId'] : null;
        $ani = isset($data['ani']) ? $data['ani'] : null;

        // Valido que si me este llegando datoInicial sino devuelvo null
        $obtenerDatoInicial = '';
        $boolObtenerDatoInicial = false;

        if(isset($data['pregunDatoInicial']) && $data['pregunDatoInicial'] != null && $data['pregunDatoInicial'] != ''){
            $obtenerDatoInicial = ", G{$bdId}_C{$data['pregunDatoInicial']} AS datoInicial";
            $boolObtenerDatoInicial = true;
        }

        if(is_null($bdId) && is_null($bdId)){
            echo json_encode(["status" => "fallo", "mensaje" => "Los campos estan incompletos"]);
            exit;
        }

        if(is_null($campoId)){
            echo json_encode(["status" => "fallo", "mensaje" => "Los campos estan incompletos"]);
            exit;
        }

        if(is_null($ani)){
            echo json_encode(["status" => "fallo", "mensaje" => "Los campos estan incompletos"]);
            exit;
        }

        // Separo el campo $campoId en un array para que haga una validacion por el ani+
        $arrCampoId = explode(',', $campoId);

        $strCondicion = "";

        if(count($arrCampoId) > 0){
            $strCondicion = "(";
            for ($i=0; $i < count($arrCampoId); $i++) { 
                if($i > 0){
                    $strCondicion .= "OR";
                }
                $strCondicion .= " G{$bdId}_C{$arrCampoId[$i]} = '{$ani}' ";
            }
            $strCondicion .= ')';
        }

        // Hacemos la consulta
        $sql = "SELECT G{$bdId}_ConsInte__b AS id, G{$bdId}_Template_b AS template, G{$bdId}_TemplateFechaEnvio_b AS fecha_envio {$obtenerDatoInicial}
            FROM {$BaseDatos}.G{$bdId} 
            WHERE {$strCondicion} AND G{$bdId}_Template_b IS NOT NULL ORDER BY G{$bdId}_TemplateFechaEnvio_b DESC LIMIT 1";
        $res = $mysqli->query($sql);

        if($res && $res->num_rows > 0){

            $reg = $res->fetch_object();

            $templateId = null;

            // Si el registro no esta null pues lo limpiamos
            if(!is_null($reg->template) && !is_null($reg->fecha_envio)){

                // Calculamos la diferencia entre fechas
                $date1 = new DateTime($reg->fecha_envio);
                $date2 = new DateTime("now");
                $diff = $date1->diff($date2);

                // obtiene la diferencia en horas
                $diferencia_horas = $diff->h + ($diff->days * 24);

                // Si la diferencia es mayor a 24 horas no le envio nada ya que expira el tiempo de comunicacion abierta
                if ($diferencia_horas >= 24) {
                    $templateId = null;
                }else{
                    $templateId = $reg->template;
                }
                
                // Ya realizada la consulta procedo a limpiarlo
                $update = "UPDATE {$BaseDatos}.G{$bdId} SET G{$bdId}_Template_b = NULL, G{$bdId}_TemplateFechaEnvio_b = NULL WHERE G{$bdId}_ConsInte__b = {$reg->id}";
                $mysqli->query($update);
            }

            // Si tenemos que obtener el dato inicial 
            if($obtenerDatoInicial){

                // Super amarre solo cuando la bd sea la de convatec cateterismo
                if($bdId == 2391 && $data['pregunDatoInicial'] == 57907){

                    // Me toca hacer de nuevo la consulta
                    $sql = "SELECT G{$bdId}_ConsInte__b AS id {$obtenerDatoInicial} FROM {$BaseDatos}.G{$bdId} WHERE G{$bdId}_C45456 = '{$ani}'  LIMIT 1";
                    $resAd = $mysqli->query($sql);
                    if($resAd && $resAd->num_rows > 0){
                        $data2 = $resAd->fetch_object();
                        $reg->datoInicial = $data2->datoInicial;
                    }
                }

                // Limpiamos los caracteres al mensaje inicial
                $datoInicial = str_replace("DY_SALUDO", "", $reg->datoInicial);
                $datoInicial = trim($datoInicial);
            }else{
                $datoInicial = null;
            }

            header("HTTP/1.1 200 OK");
            echo json_encode(["status" => "ok","mensaje" => "proceso realizado","registroId" => $reg->id, "template" => $templateId, "respuestaUsuario" => $datoInicial]);
            exit;
        }else{
            header("HTTP/1.1 200 OK");
            echo json_encode(["status" => "fallo", "mensaje" => "No se encontro el registro", "template" => null, "respuestaUsuario" => null]);
            exit;
        }
    }

}

//En caso de que ninguna de las opciones anteriores se haya ejecutado
header("HTTP/1.1 400 Bad Request");
// ALTER TABLE `DYALOGOCRM_WEB`.`G2351` ADD COLUMN `G2351_Template_b` INT(11) NULL;
// ALTER TABLE `DYALOGOCRM_WEB`.`G2351` ADD COLUMN `G2351_TemplateFechaEnvio_b` DATETIME NULL;

?>
