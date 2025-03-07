<?php
session_start();
ini_set('display_errors', 'On');
ini_set('display_errors', 1);
require_once("../conexion.php");

if(isset($_GET['getDataWS'])){
    $ws=$_POST["ws"];
    $llave=$_POST["llave"];

    //CONSULTA QUE TRAE LA INFORMACION DEL WS
    $sql=$mysqli->query("SELECT * FROM dyalogo_general.ws_general WHERE id={$ws}");

    if($sql){
        $sql=$sql->fetch_object();
        
        //TRAER LOS HEADERS DEL WS
        $sqlHeaders=$mysqli->query("SELECT * FROM dyalogo_general.ws_headers WHERE id_ws={$ws}");
                
        //CONSULTA QUE TRAE LOS CAMPOS RELACIONADOS ENTRE EL WS Y EL FORMULARIO
        $sqlCampos=$mysqli->query("SELECT A.CAMCONWS_ConsInte__GUION__b AS guion,A.CAMCONWS_TipValor_b AS tipo,A.CAMCONWS_ConsInte__ws_parametros__b AS id_ws,A.CAMCONWS_ConsInte__PREGUN__b AS id_pregun,A.CAMCONWS_Valor_b AS valor,B.parametro,B.sentido,C.lista FROM DYALOGOCRM_SISTEMA.CAMCONWS A LEFT JOIN dyalogo_general.ws_parametros B ON A.CAMCONWS_ConsInte__ws_parametros__b=B.id LEFT JOIN (SELECT LISOPCWS_ConsInte__PREGUN__b AS lista,LISOPCWS_ConsInte__PREGUN__b AS lisopWS_pregun FROM DYALOGOCRM_SISTEMA.LISOPCWS WHERE LISOPCWS_ConsInte__PREGUN__llave_b={$llave} GROUP BY LISOPCWS_ConsInte__PREGUN__b) C ON A.CAMCONWS_ConsInte__PREGUN__b=C.lisopWS_pregun WHERE CAMCONWS_ConsInte__PREGUN__llave_b={$llave}");


        // VALIDAMOS LOS HEADERS QUE TOQUE ENVIAR AL HACER EL CONSUMO DEL WS
        $headers=array();
        if($sqlHeaders && $sqlHeaders->num_rows>0){
            while($header = $sqlHeaders->fetch_object()){
                // SI ESTE HEADER CONTIENE LA PALABARA $TOKEN ENTONCES HAY QUE LLAMAR A LA FUNCION PROGRAMADA DENTRO DEL ARCHIVO EXTENEDER FUNCIONALIDAD DE CADA G 
                if(count(explode('$token', $header->valor)) > 1){
                    $funcion=$sql->funcion_requerida;
                    if($funcion != null){
                        //LLAMAR A LA FUNCION PROGRAMADA DEL G 
                        $rg=$_POST['formRequired'];
                        require_once("G{$rg}/G{$rg}_extender_funcionalidad.php");
                        $token=$funcion($ws);
                        $tokenfinal=str_replace('$token',$token,$header->valor);
                        $headers[]="{$header->nombre}: ".trim($tokenfinal);
                    }else{
                        //NO HAY UNA FUNCIÓN PROGRAMADA PARA ESTE WS
                    }
                }else{
                    $headers[]="{$header->nombre}: {$header->valor}";
                }
            }
        }


        $url=$sql->url;
        $metodo=$sql->metodo;

        //RECORREMOS LOS ENLACES ENTRE LOS CAMPOS PARA ASIGNAR EL VALOR
        $camposRelacion=array();
        $arrayDatos_p=array();
        while($relacion = $sqlCampos->fetch_object()){
            //CAMPOS A ENVIAR PARA REALIZAR EL CONSUMO DEL WS
            if($relacion->sentido == 'IN'){
                if($metodo == 'GET'){
                    //BUSCAR LOS VARIABLES EN LA URL y REEMPLAZARLAS POR LOS VALORES CORRESPONDIENTES
                    if($relacion->tipo == '1'){
                        //VALOR DINAMICO, SE ASIGNA EL VALOR CON LO QUE LLEGUE POR EL POST
                        $valCampo=$_POST["G{$relacion->guion}_C{$relacion->id_pregun}"];
                        $url=str_replace($relacion->parametro,$valCampo,$url);
                    }else{
                        //VALOR FIJO, SE ASIGNA EL VALOR GUARDADO EN LA BASE DE DATOS
                        $url=str_replace($relacion->parametro,$relacion->valor,$url);                        
                    }
                }

                if($metodo == 'PATCH' || $metodo == 'POST' || $metodo == 'PUT'){
                    //ARMAR EL ARRAY CON LOS VALORES A ENVIAR
                    $enURL=false;
                    if($relacion->tipo == '1'){
                        //VALOR DINAMICO, SE ASIGNA EL VALOR CON LO QUE LLEGUE POR EL POST
                        $valCampo=$_POST["G{$relacion->guion}_C{$relacion->id_pregun}"];
                        if (strpos($url, $relacion->parametro) !== false) {
                            $url=str_replace($relacion->parametro,$valCampo,$url);
                            $enURL=true;
                        }
                    }else{
                        //VALOR FIJO, SE ASIGNA EL VALOR GUARDADO EN LA BASE DE DATOS
                        if (strpos($url, $relacion->parametro) !== false) {
                            $url=str_replace($relacion->parametro,$relacion->valor,$url);
                            $enURL=true;
                        }
                    }
                    
                    if(is_null($relacion->lista)){
                        if(!$enURL){
                            // Si es tipo 1 se almacena el valor que viene por POST
                            if($relacion->tipo == '1'){
                                $arrayDatos_p[$relacion->parametro]=$_POST["G{$relacion->guion}_C{$relacion->id_pregun}"];
                            }else{
                                $arrayDatos_p[$relacion->parametro]=$relacion->valor;
                            }
                        }
                    }else{
                        if(!$enURL){
                            $option=traduceOpcion($llave,$relacion->lista,$_POST["G{$relacion->guion}_C{$relacion->id_pregun}"],'dyalogo');
                            if($option['mensaje']){
                                $arrayDatos_p[$relacion->parametro]=$option["opcion"];
                            }else{
                                $arrayDatos_p[$relacion->parametro]=0;
                            }
                        }
                    }

                }
            }
            //ARMAMOS EL JSON DE LOS CAMPOS QUE SE DEBEN LLENAR CON LOS VALORES DEL WS EN EL FORMULARIO
            if($relacion->sentido == 'OUT'){
                $camposRelacion[]=array(
                    'parametro' => $relacion->parametro,
                    'campoG' => "G{$relacion->guion}_C{$relacion->id_pregun}",
                    'lista' => $relacion->lista,
                );
            }
        }

        if($metodo == 'PATCH' || $metodo == 'POST' || $metodo == 'PUT'){
            $arrayDatos_p=json_encode($arrayDatos_p);
            $headers[]="Accept: application/json";
            $headers[]="Content-Length: ".strlen($arrayDatos_p);
        }
        echo json_encode(array('dataWS' => consumirWS($url,$metodo,$headers,$arrayDatos_p), 'dataRelaciones' => $camposRelacion));
    }
}

if(isset($_GET["getOpcionWS"])){
    $llave=isset($_POST["llave"]) ? $_POST["llave"] : false;
    $lista=isset($_POST["lista"]) ? $_POST["lista"] : false;
    $opcion=isset($_POST["opcion"]) ? $_POST["opcion"] : false;
    if($llave && $lista && $opcion){
        echo traduceOpcion($llave,$lista,$opcion);
    }else{
        echo json_encode(array('mensaje'=>'Hubo un error al traducir la opcion: Información incompleta'));
    }
}

function consumirWS($strURL_p, $metodo, $headers=null, $arrayDatos_p= ""){
    //Inicializamos la conexion CURL al web service local para ser consumido

    $objCURL_t = curl_init($strURL_p);
    curl_setopt($objCURL_t,CURLOPT_RETURNTRANSFER,true);
    curl_setopt($objCURL_t,CURLOPT_FOLLOWLOCATION,true);
    curl_setopt($objCURL_t, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
    curl_setopt($objCURL_t, CURLOPT_CUSTOMREQUEST, $metodo);
    curl_setopt($objCURL_t, CURLOPT_POSTFIELDS, $arrayDatos_p);
    if($headers != null){
        curl_setopt($objCURL_t, CURLOPT_HTTPHEADER, $headers);
    }
    
    //Obtenemos la respuesta
    $objRespuestaCURL_t = curl_exec($objCURL_t);

    //VALIDAR SI RETORNA UN PDF
    $info = curl_getinfo($objCURL_t);
    $fileName='';
    try {
        $response=json_decode($objRespuestaCURL_t);
    } catch (\Throwable $th) {}
    //Obtenemos el error 
    $objRespuestaError_t = curl_error($objCURL_t);
    //Cerramos la conexion
    curl_close ($objCURL_t);
    if($info['content_type'] == "application/pdf"){
        $fileName=md5(rand().date("Y-m-d h:i:s")).'.pdf';
        if(!is_dir("/tmp/adjuntos_ws")){
            mkdir("/tmp/adjuntos_ws",0777);
        }
        $destination ='/tmp/adjuntos_ws/'.$fileName;
        $file = fopen($destination, "w+");
        fputs($file,$objRespuestaCURL_t);
        fclose($file);
        $file=$destination;
    }
    //RETORNAR LA RESPUESTA DEL WS
    return(array('info'=>$info, "file"=>$fileName, 'solicitud' => $response, 'Error' => json_decode($objRespuestaError_t)));


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