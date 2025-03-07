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

            // Teniendo ya la informacion del paso actualizamos la muestra
            $sql = "UPDATE {$BaseDatos}.G{$paso->bd}_M{$paso->muestra} SET G{$paso->bd}_M{$paso->muestra}_TienGest__b = 'Respondido' WHERE G{$paso->bd}_M{$paso->muestra}_CoInMiPo__b = {$registroId}";
            
            if($mysqli->query($sql)){
                $valido = "true";
            }else{
                $valido = "false";
            }


            header("HTTP/1.1 200 OK");
            echo json_encode(["estado" => "ok","mensaje" => "proceso realizado","registroId" => $registroId, "registroAcualizado" => $valido]);
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