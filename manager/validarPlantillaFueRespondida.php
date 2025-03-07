<?php

include_once('pages/conexion.php');

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

        // Validamos si existe el id del paso o el nombre del paso al cual vamos a buscar
        $pasoId = isset($data['idPaso']) ? $data['idPaso'] : null;
        $nombrePaso = isset($data['nombrePaso']) ? $data['nombrePaso'] : null;
        $registroId = isset($data['registroId']) ? $data['registroId'] : null;

        if(is_null($pasoId) && is_null($nombrePaso)){
            echo json_encode(["status" => "fallo", "mensaje" => "debe enviar el id del paso o el nombre"]);
            exit;
        }

        if(is_null($registroId)){
            echo json_encode(["status" => "fallo", "mensaje" => "Debe enviarse el id del registro a buscar"]);
            exit;
        }

        if(!is_null($pasoId)){
            $sql = "SELECT ESTPAS_ConsInte__b AS id, ESTPAS_ConsInte__MUESTR_b AS muestra, ESTRAT_ConsInte_GUION_Pob AS bd FROM {$BaseDatos_systema}.ESTPAS JOIN {$BaseDatos_systema}.ESTRAT ON ESTPAS_ConsInte__ESTRAT_b = ESTRAT_ConsInte__b WHERE ESTPAS_ConsInte__b = {$pasoId} AND ESTPAS_Tipo______b = 13 LIMIT 1";
        }else if(!is_null($nombrePaso)){
            $sql = "SELECT ESTPAS_ConsInte__b AS id, ESTPAS_ConsInte__MUESTR_b AS muestra, ESTRAT_ConsInte_GUION_Pob AS bd FROM {$BaseDatos_systema}.ESTPAS JOIN {$BaseDatos_systema}.ESTRAT ON ESTPAS_ConsInte__ESTRAT_b = ESTRAT_ConsInte__b WHERE ESTPAS_Comentari_b = '{$nombrePaso}' AND ESTPAS_Tipo______b = 13 LIMIT 1";
        }

        $res = $mysqli->query($sql);

        if($res && $res->num_rows > 0){

            $paso = $res->fetch_object();

            // Teniendo ya la informacion del paso vamos a traer la muestra
            $sql = "SELECT G{$paso->bd}_M{$paso->muestra}_CoInMiPo__b AS id, G{$paso->bd}_M{$paso->muestra}_FecUltGes_b AS fecha, G{$paso->bd}_M{$paso->muestra}_TienGest__b AS respondido FROM {$BaseDatos}.G{$paso->bd}_M{$paso->muestra} WHERE G{$paso->bd}_M{$paso->muestra}_CoInMiPo__b = {$registroId} LIMIT 1";

            $resM = $mysqli->query($sql);

            $regId = $registroId;
            $fechaEnvio = '';
            $valido = "false";

            if($resM && $resM->num_rows > 0){

                $muestra = $resM->fetch_object();

                $regId = $muestra->id;
                $fechaEnvio = $muestra->fecha;

                // Calculamos las fechas
                date_default_timezone_set('America/Bogota');
                $fechaE = new DateTime($fechaEnvio);
                $now = new DateTime("now");
                $diferencia = date_diff($fechaE, $now);

                if($diferencia->days <= 0){
                    // Si aun nos encontramos en el rango de fechas vamos a validar si fue respondido
                    if(is_null($muestra->respondido) || $muestra->respondido == ''){
			$valido = "true";                        
// Vamos a realizar la actualizacion de una vez aca
                        //$sqlu = "UPDATE {$BaseDatos}.G{$paso->bd}_M{$paso->muestra} SET G{$paso->bd}_M{$paso->muestra}_TienGest__b = 'Respondido' WHERE G{$paso->bd}_M{$paso->muestra}_CoInMiPo__b = {$registroId}";
                        //$mysqli->query($sqlu);
                    }
                }

            }

            header("HTTP/1.1 200 OK");
            echo json_encode(["estado" => "ok","mensaje" => "proceso realizado","registroId" => $regId,"fechaEnvio" => $fechaEnvio,"valido" => $valido]);
            exit;
        }else{
            header("HTTP/1.1 200 OK");
            echo json_encode(["status" => "fallo", "mensaje" => "no se encontro el paso con la infomacion dada"]);
            exit;
        }
    }

}

//En caso de que ninguna de las opciones anteriores se haya ejecutado
header("HTTP/1.1 400 Bad Request");
?>