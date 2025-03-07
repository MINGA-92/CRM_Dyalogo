<?php
ini_set('display_errors', 'On');
ini_set('display_errors', 1);
require_once("../conexion.php");

if(isset($_GET['bd']) && isset($_GET['paso'])){
    $bd=$_GET["bd"];
    $llave=$_GET["paso"];
    $arrData_t = json_decode(file_get_contents('php://input'), true);
    if($arrData_t){
        $idLog=insertarLog($bd,"Procesando",'Se recibio un json por post',file_get_contents('php://input'));
        $relaciones= getRelacionesWeb($bd, $llave);
        if($relaciones){
            $lead=$arrData_t['leads'][0];
            $arrDataCampos=array();
            while($relacion=$relaciones->fetch_object()){
                foreach ($lead as $valor=>$data) {
                    if($valor == $relacion->parametro){
                        if(!is_null($relacion->lista)){
                            $opcion=traduceOpcion($llave,$relacion->lista,$data);
                            $arrDataCampos[$relacion->id_pregun]=$opcion['opcion'];
                        }else{
                            $arrDataCampos[$relacion->id_pregun]=$data;
                        }
                    }
                }
            }
        }else{
            updateLog($idLog,"Error","No se han configurado las relaciones de campos");
        }
    }else{
        insertarLog($bd, "Error", "No se recibio información por post");
    }
}else{
    insertarLog(NULL,"Error","No se identifico la base en la url");
}


function insertarLog($intBD_p=NULL,$strEstado_p,$strMensaje_p,$strData_p=''){
    global $mysqli;
    $idLog_t=false;
    $strSQL_t = $mysqli->query("INSERT INTO DYALOGOCRM_SISTEMA.LOGWEB  VALUES (NULL,NOW(),{$intBD_p},'{$strEstado_p}','{$strMensaje_p}','{$strData_p}',NULL)");
    if ($strSQL_t){
        $idLog_t=$mysqli->insert_id;
    }
    return $idLog_t;
}

function updateLog($intId_p,$strEstado_p,$strMensaje_p,$strData_p='',$strQuery_p=''){
    global $mysqli;
    $data= is_null($strData_p) ? NULL : "'{$strData_p}'";
    $query=is_null($strQuery_p) ? NULL : "'{$strQuery_p}'";

    $mysqli->query("UPDATE DYALOGOCRM_SISTEMA.LOGWEB SET LOGWEB_Estado_b = {$strEstado_p}, LOGWEB_EstadoMensaje_b='{$strMensaje_p}', LOGWEB_Data_b={$data}, LOGWEB_Query_b={$query} WHERE LOGWEB_ConsInte__b ={$intId_p}");

}

function getRelacionesWeb($bd, $llave){
    global $mysqli;
    //CONSULTA QUE TRAE LOS CAMPOS RELACIONADOS ENTRE EL WS Y EL FORMULARIO
    $sqlCampos=$mysqli->query("SELECT A.CAMCONWS_ConsInte__GUION__b AS guion,A.CAMCONWS_TipValor_b AS tipo,A.CAMCONWS_ConsInte__ws_parametros__b AS id_ws,A.CAMCONWS_ConsInte__PREGUN__b AS id_pregun,A.CAMCONWS_Valor_b AS valor,B.parametro,B.sentido,C.lista FROM DYALOGOCRM_SISTEMA.CAMCONWS A LEFT JOIN dyalogo_general.ws_parametros B ON A.CAMCONWS_ConsInte__ws_parametros__b=B.id LEFT JOIN (SELECT LISOPCWS_ConsInte__PREGUN__b AS lista,LISOPCWS_ConsInte__PREGUN__b AS lisopWS_pregun FROM DYALOGOCRM_SISTEMA.LISOPCWS WHERE LISOPCWS_ConsInte__PREGUN__llave_b={$llave} GROUP BY LISOPCWS_ConsInte__PREGUN__b) C ON A.CAMCONWS_ConsInte__PREGUN__b=C.lisopWS_pregun WHERE CAMCONWS_ConsInte__PREGUN__llave_b={$llave} AND CAMCONWS_ConsInte_ESTPAS__b={$llave} AND CAMCONWS_ConsInte__GUION__b={$bd}");
    
    if($sqlCampos && $sqlCampos->num_rows > 0){
        return $sqlCampos;
    }else{
        return false;
    }
}

function traduceOpcion($llave,$lista,$opcion,$origen=null){
    global $mysqli;
    $campoCondicion='LISOPCWS_OpcWS_b';
    if(!is_null($origen)){
        $campoCondicion='LISOPCWS_ConsInte__LISOPC__b';
    }
    $sql=$mysqli->query("SELECT LISOPCWS_ConsInte__LISOPC__b,LISOPCWS_OpcWS_b FROM DYALOGOCRM_SISTEMA.LISOPCWS WHERE LISOPCWS_ConsInte__PREGUN__llave_b={$llave} AND LISOPCWS_ConsInte__PREGUN__b={$lista} AND {$campoCondicion}={$opcion}");
    if($sql && $sql->num_rows == 1){
        $sql=$sql->fetch_object();
        if(!is_null($origen)){
            return array('mensaje'=>true,'opcion'=>$sql->LISOPCWS_OpcWS_b);
        }else{
            return json_encode(array('mensaje'=>'ok', 'tipo'=>'lista', 'opcion'=>$sql->LISOPCWS_ConsInte__LISOPC__b));
        }

    }else{
        //VALIDAMOS SI ES DE TIPO LISTA AUXILIAR
        $sql=$mysqli->query("SELECT LISOPCWS_ConsInte__TablaG__b FROM DYALOGOCRM_SISTEMA.LISOPCWS WHERE LISOPCWS_ConsInte__PREGUN__llave_b={$llave} AND LISOPCWS_ConsInte__PREGUN__b={$lista} AND LISOPCWS_ConsInte__TablaG__b IS NOT NULL");
        if($sql && $sql->num_rows == 1){
            $sql=$sql->fetch_object();
            //OBTENER LA BASE DE DATOS CON LA QUE ESTA RELACIONADA EL CAMPO
            $sqlBD=$mysqli->query("SELECT PREGUN_ConsInte__GUION__b AS id FROM DYALOGOCRM_SISTEMA.PREGUN WHERE PREGUN_ConsInte__b={$sql->LISOPCWS_ConsInte__TablaG__b}");
            if($sqlBD){
                $sqlBD=$sqlBD->fetch_object();
                $campoCondicion="G{$sqlBD->id}_C{$sql->LISOPCWS_ConsInte__TablaG__b}";
                if(!is_null($origen)){
                    $campoCondicion="G{$sqlBD->id}_ConsInte__b";
                }
                //AHORA OBTENEMOS EL ID DEL REGISTRO EN LA BASE DE DATOS CORRESPONDIENTE
                $sqlIdBd=$mysqli->query("SELECT G{$sqlBD->id}_ConsInte__b AS id,G{$sqlBD->id}_C{$sql->LISOPCWS_ConsInte__TablaG__b} AS opcWS FROM DYALOGOCRM_WEB.G{$sqlBD->id} WHERE {$campoCondicion}='{$opcion}' LIMIT 1");
                if($sqlIdBd && $sqlIdBd->num_rows==1){
                    $sqlIdBd=$sqlIdBd->fetch_object();
                    if(!is_null($origen)){
                        return array('mensaje'=>true, 'opcion'=>$sqlIdBd->opcWS);
                    }else{
                        return json_encode(array('mensaje'=>'ok', 'tipo'=>'auxiliar', 'opcion'=>$sqlIdBd->id));
                    }
                }else{
                    if(!is_null($origen)){
                        return array('mensaje'=>false);
                    }else{
                        return json_encode(array('mensaje'=>'Hubo un error al traducir la opcion: La opción del WS no corresponde a una opción configurada'));
                    }
                }
            }else{
                if(!is_null($origen)){
                    return array('mensaje'=>false);
                }else{
                    return json_encode(array('mensaje'=>'Hubo un error al traducir la opcion: Fallo al leer la base de lista auxiliar'));
                }
            }
        }else{
            if(!is_null($origen)){
                return array('mensaje'=>false);
            }else{
                return json_encode(array('mensaje'=>'Hubo un error al traducir la opcion: Validar el enlace de las listas del Web Service'));
            }
        }
    }
}

echo 'exito';
?>