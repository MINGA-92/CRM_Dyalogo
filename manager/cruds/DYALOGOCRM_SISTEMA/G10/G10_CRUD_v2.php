<?php

    session_start();
    ini_set('display_errors', 'On');
    ini_set('display_errors', 1);
    require "../../../carga/lib_excel/Classes/PHPExcel/Reader/Excel2007.php";
    include(__DIR__."../../../../pages/conexion.php");
    include(__DIR__."../../../../global/WSCoreClient.php");
    include(__DIR__."../../../../generador/generar_tablas_bd.php");
    include(__DIR__."/../reporteador.php");
    require_once('../../../../helpers/parameters.php');
    require_once(__DIR__ . "../../../../generador/busqueda_manual.php");
    require_once(__DIR__ . "../../../../generador/busqueda_ani.php");
    require_once(__DIR__ . "../../../../generador/busqueda_dato_adicional.php");

    function verificarReporteExistente($strReporte_p,$intPeriodicidad_p,$intIdHuesped_p,$intIdEstrat_p){

        global $mysqli;
        global $BaseDatos_general;

        if ($strReporte_p == "NORMAL") {

            $strSQLReporte_t = "SELECT COUNT(1) AS cantidad FROM ".$BaseDatos_general.".reportes_automatizados WHERE id_huesped = ".$intIdHuesped_p." AND id_estrategia = ".$intIdEstrat_p." AND tipo_periodicidad = ".$intPeriodicidad_p;
            
        }elseif($strReporte_p == "ADHERENCIA"){

            $strSQLReporte_t = "SELECT COUNT(1) AS cantidad FROM ".$BaseDatos_general.".reportes_automatizados WHERE id_huesped = ".$intIdHuesped_p." AND id_estrategia = ".$intIdEstrat_p." AND tipo_periodicidad = ".$intPeriodicidad_p." AND asunto LIKE '%_ADHERENCIA'";

        }

        $booExiste_t = false;

        if ($resSQLReporte_t = $mysqli->query($strSQLReporte_t)) {

            $objSQLReporte_t = $resSQLReporte_t->fetch_object();

            if ($objSQLReporte_t->cantidad > 0) {

                $booExiste_t = true;

            }

        }

        return $booExiste_t;

    }
    
    function guardar_auditoria($accion, $superAccion){
        global $mysqli;
        global $BaseDatos_systema;
        $str_Lsql = "INSERT INTO ".$BaseDatos_systema.".AUACAD (AUACAD_Fecha_____b , AUACAD_Hora______b, AUACAD_Ejecutor__b, AUACAD_TipoAcci__b , AUACAD_SubTipAcc_b, AUACAD_Accion____b , AUACAD_Huesped___b ) VALUES ('".date('Y-m-d H:s:i')."', '".date('Y-m-d H:s:i')."', ".$_SESSION['IDENTIFICACION'].", 'G10', '".$accion."', '".$superAccion."', ".$_SESSION['HUESPED']." );";
        $mysqli->query($str_Lsql);
    }  

    function invocarCrm_CrearScripts($idCampan){
        global $mysqli;
        global $BaseDatos_systema;

        $ch = curl_init($urlCrearScripts.'crm_php/generarBusqueda.php');
        //especificamos el POST (tambien podemos hacer peticiones enviando datos por GET
        curl_setopt ($ch, CURLOPT_POST, 1);
         
        //le decimos qué paramáetros enviamos (pares nombre/valor, también acepta un array)
        curl_setopt ($ch, CURLOPT_POSTFIELDS, "generar=".$idCampan);
         
        //le decimos que queremos recoger una respuesta (si no esperas respuesta, ponlo a false)
        curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);

        $respuesta = curl_exec ($ch);
        $error = curl_error($ch);
        curl_close ($ch);
        echo " Respuesta => ".$respuesta;
        echo " Error => ".$error;
    }

    function getTextoPregun($id){

        if (!is_numeric($id)) {

            if (strpos($id, "_ConsInte__b")) {

                return "ID";

            }else if (strpos($id, "_FechaInsercion")) {
                
                return "FECHA INSERCION";

            }

        }else{

            global $mysqli;
            global $BaseDatos_systema;
            $Lsql = "SELECT PREGUN_Texto_____b FROM " . $BaseDatos_systema . ".PREGUN WHERE PREGUN_ConsInte__b = " . $id;
            $res = $mysqli->query($Lsql);
            $datos = $res->fetch_array();
            return $datos['PREGUN_Texto_____b'];

            
        }

    } 

    function getIvrEncuesta($id){
        
        global $mysqli;
        global $BaseDatos_general;
        
        $sql=$mysqli->query("SELECT IF(context LIKE '%IVRCB_%',0,context) as context FROM dyalogo_telefonia.dy_campanas WHERE id_campana_crm={$id}");
        $dato=null;
        if($sql && $sql->num_rows==1){
            $sql=$sql->fetch_object();
            $dato=$sql->context;
        }
        
        return $dato;
    }

    function validarEstpasCallback($estado,$campan,$id_estrategia){
        global $mysqli;
        global $BaseDatos_general;
        
        // 1. Validar si ya hay una campaña de callback creada
        $sqlCampanCallback=$mysqli->query("SELECT CAMPAN_CallBack_ConsInte__b FROM DYALOGOCRM_SISTEMA.CAMPAN WHERE CAMPAN_ConsInte__b={$campan}");
        
        if($sqlCampanCallback && $sqlCampanCallback->num_rows == 1){
            $sqlCampanCallback=$sqlCampanCallback->fetch_object();
            $sqlCallbackEstpas=$mysqli->query("SELECT ESTPAS_ConsInte__b,ESTPAS_ConsInte__ESTRAT_b FROM DYALOGOCRM_SISTEMA.ESTPAS WHERE ESTPAS_ConsInte__CAMPAN_b={$sqlCampanCallback->CAMPAN_CallBack_ConsInte__b}");
            
            if($sqlCallbackEstpas && $sqlCallbackEstpas->num_rows == 1){
                $sqlCallbackEstpas=$sqlCallbackEstpas->fetch_object();
                
                if($estado === 0 && $sqlCallbackEstpas->ESTPAS_ConsInte__ESTRAT_b != null){
                    //DESACTIVARON EL CALLBACK
                    
                    //ACTUALIZAR ESTPAS A NULL EN EL CAMPO DE LA ESTRATEGIA
                    $sqlUpEstpas=$mysqli->query("UPDATE DYALOGOCRM_SISTEMA.ESTPAS SET ESTPAS_ConsInte__ESTRAT_b=NULL WHERE ESTPAS_ConsInte__b={$sqlCallbackEstpas->ESTPAS_ConsInte__b}");
                    
                    //ACTUALIZAR ESTCON A NEGATIVO
                    $sqlUpEstcon=$mysqli->query("UPDATE DYALOGOCRM_SISTEMA.ESTCON SET ESTCON_ConsInte__ESTRAT_b=NULL WHERE ESTCON_ConsInte__ESTPAS_Has_b={$sqlCallbackEstpas->ESTPAS_ConsInte__b}");

                    //DESACTIVAR LA CAMPAÑA DE CALLBACK
                    $sqlUpCampan=$mysqli->query("UPDATE DYALOGOCRM_SISTEMA.CAMPAN SET CAMPAN_IndiAbie__b=0 WHERE CAMPAN_ConsInte__b={$sqlCampanCallback->CAMPAN_CallBack_ConsInte__b}");
                }
                
                if($estado != 0 && $sqlCallbackEstpas->ESTPAS_ConsInte__ESTRAT_b == null){
                    //ACTIVARON EL CALLBACK
                    
                    //ACTUALIZAR ESTPAS CON EL ID DE LA ESTRATEGIA
                    $sqlUpEstpas=$mysqli->query("UPDATE DYALOGOCRM_SISTEMA.ESTPAS SET ESTPAS_ConsInte__ESTRAT_b={$id_estrategia} WHERE ESTPAS_ConsInte__b={$sqlCallbackEstpas->ESTPAS_ConsInte__b}");
                    
                    //ACTUALIZAR ESTCON A POSITIVO
                    $sqlUpEstcon=$mysqli->query("UPDATE DYALOGOCRM_SISTEMA.ESTCON SET ESTCON_ConsInte__ESTRAT_b={$id_estrategia} WHERE ESTCON_ConsInte__ESTPAS_Has_b={$sqlCallbackEstpas->ESTPAS_ConsInte__b}");

                    //ACTIVAR LA CAMPAÑA DE CALLBACK
                    $sqlUpCampan=$mysqli->query("UPDATE DYALOGOCRM_SISTEMA.CAMPAN SET CAMPAN_IndiAbie__b=-1 WHERE CAMPAN_ConsInte__b={$sqlCampanCallback->CAMPAN_CallBack_ConsInte__b}");
                }
            }
        }
        
        return true;
        
    }

    function updateCamposCBX(array $arrDatos):bool
    {
        global $mysqli;

        (int) $accionDesborde=      isset($arrDatos['accionDesborde']) && $arrDatos['accionDesborde'] !=''      ? $arrDatos['accionDesborde']           : 'NULL';
        (int) $valorDesborde=       isset($arrDatos['valorDesborde'])  && $arrDatos['valorDesborde'] !='0'      ? $arrDatos['valorDesborde']            : 'NULL';
        (int) $prioridad=           isset($arrDatos['pesoCampan'])          ? $arrDatos['pesoCampan']               : 1;
        (int) $metaTSF=             isset($arrDatos['tsf'])                 ? $arrDatos['tsf']                      : 80;
        (int) $tiempoTSF=           isset($arrDatos['tsfs'])                ? $arrDatos['tsfs']                     : 20;
        (int) $tiempoConverMin=     isset($arrDatos['tcMin'])               ? $arrDatos['tcMin']                    : 30;
        (int) $tiempoConverMax=     isset($arrDatos['tcMax'])               ? $arrDatos['tcMax']                    : 180;
        (int) $tiempoAbandono=      isset($arrDatos['tiempoAbandono'])      ? $arrDatos['tiempoAbandono']           : 0;
        (string) $audioEspera=      isset($arrDatos['audioEspera']) && $arrDatos['audioEspera'] != "DY_AUDIO_OFRECE_DEVOLUCION_CB" ? "'{$arrDatos['audioEspera']}'" : 'NULL';
        (int) $tiempoEspera=        isset($arrDatos['frecuenciaEspera'])    ? $arrDatos['frecuenciaEspera']         : 15;
        (int) $tiempoDesborde=      isset($arrDatos['tiempoDesborde'])      ? $arrDatos['tiempoDesborde']           : 0;
        (string) $estratDistribuir= isset($arrDatos['estratDistribuir'])    ? "'{$arrDatos['estratDistribuir']}'"   : 'NULL';
        (int) $llamadasCola=        isset($arrDatos['llamadasCola'])        ? $arrDatos['llamadasCola']             : 0;
        (string) $unirLlamadas=     isset($arrDatos['unirLlamadas'])        ? "'{$arrDatos['unirLlamadas']}'"       : 'NULL';
        (string) $dejarVacia=       isset($arrDatos['dejarVacia'])          ? "'{$arrDatos['dejarVacia']}'"         : 'NULL';
        (int) $CAMPAN_RepAnuncio_b= isset($arrDatos['audioEspera']) && $arrDatos['audioEspera'] != "DY_AUDIO_OFRECE_DEVOLUCION_CB" ? -1 : 0;
        (int) $idCampanCBX=         isset($arrDatos['G10_C106'])            ? $_POST['G10_C106']                    :false;
        (int) $idCampanCRM=         isset($arrDatos['id'])                  ? $_POST['id']                          :false;

        //VALIDAR LA ACCION DE DESBORDE 
        (string) $strValorDesborde="";
        switch($accionDesborde){
            case '2':
                $strValorDesborde="CAMPAN_DesbordeACD_b={$valorDesborde},CAMPAN_DesbordeAudio_b=NULL,CAMPAN_DesbordeIVR_b=NULL";
                break;
            case '3':
                $strValorDesborde="CAMPAN_DesbordeAudio_b={$valorDesborde},CAMPAN_DesbordeACD_b=NULL,CAMPAN_DesbordeIVR_b=NULL";
                break;
            case '4':
                $strValorDesborde="CAMPAN_DesbordeIVR_b={$valorDesborde},CAMPAN_DesbordeACD_b=NULL,CAMPAN_DesbordeAudio_b=NULL";
                break;
            default:
                $strValorDesborde="CAMPAN_DesbordeIVR_b=NULL,CAMPAN_DesbordeACD_b=NULL,CAMPAN_DesbordeAudio_b=NULL";
                break;
        }

        (bool) $response = true;
        if($idCampanCBX && $idCampanCRM){
            $query="UPDATE dyalogo_telefonia.dy_campanas SET strategy={$estratDistribuir}, maxlen={$llamadasCola}, joinempty={$unirLlamadas}, leavewhenempty={$dejarVacia} WHERE id={$idCampanCBX}";
            $upSql=$mysqli->query($query);
            $response = $upSql && $mysqli->affected_rows == 0 ?? false;
            
            // LA ACTUALIZACIÓN DEBIA SER EN CAMPAN
            $queryCampan="UPDATE DYALOGOCRM_SISTEMA.CAMPAN SET CAMPAN_Peso_____b={$prioridad}, CAMPAN_NivelServicio_b={$metaTSF}, CAMPAN_TiempoNivelServ_b={$tiempoTSF}, CAMPAN_TMOmin___b={$tiempoConverMin}, CAMPAN_TMOmax___b={$tiempoConverMax}, CAMPAN_TiempoAbandono_b={$tiempoAbandono}, CAMPAN_RepAnuncio_b={$CAMPAN_RepAnuncio_b}, CAMPAN_Anuncio__b={$audioEspera}, CAMPAN_TiempoAnuncio_b={$tiempoEspera}, CAMPAN_AccDesborde_b={$accionDesborde}, {$strValorDesborde}, CAMPAN_TiempoDesb_b={$tiempoDesborde} WHERE CAMPAN_ConsInte__b={$idCampanCRM}";
            $upSqlCampan=$mysqli->query($queryCampan);
            $response = $upSqlCampan && $mysqli->affected_rows == 0 ?? false;

        }
        return $response;
    }

    function getCamposCBX(int $idCampanCBX):array
    {
        global $mysqli;
        $sql=$mysqli->query("SELECT CAMPAN_Peso_____b AS pesoCampan, CAMPAN_NivelServicio_b AS tsf, CAMPAN_TiempoNivelServ_b AS tsfs , CAMPAN_TMOmin___b AS tcMin, CAMPAN_TMOmax___b AS tcMax, CAMPAN_TiempoAbandono_b AS tiempoAbandono, CAMPAN_Anuncio__b AS audioEspera, CAMPAN_TiempoAnuncio_b AS frecuenciaEspera, CAMPAN_AccDesborde_b AS accionDesborde, CAMPAN_DesbordeACD_b AS campanDesborde, CAMPAN_DesbordeAudio_b AS audioDesborde, CAMPAN_DesbordeIVR_b AS ivrDesborde, CAMPAN_TiempoDesb_b AS tiempoDesborde, strategy AS estratDistribuir, maxlen AS llamadasCola, joinempty AS unirLlamadas, leavewhenempty AS dejarVacia FROM dyalogo_telefonia.dy_campanas LEFT JOIN DYALOGOCRM_SISTEMA.CAMPAN ON id=CAMPAN_IdCamCbx__b WHERE id={$idCampanCBX}");
        if($sql && $sql->num_rows ==1){
            return $sql->fetch_array();
        }

        return array();
    }

    if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
        //Funciones de la carga maestro y eso
        
        //Obtener la lista de plantillas de respuestas rapida asociadas al huesped
        if(isset($_POST['getPlantillas'])){
            $session=isset($_SESSION['HUESPED']) ? $_SESSION['HUESPED'] : 0;
            $estado=false;
            $mensaje="No se pudo obtener las plantillas de respuestas";
            if($session > 0){
                $sqlPlantillas=$mysqli->query("SELECT PLANTILLAS_RESPUESTAS_ConsInte__b AS id, PLANTILLAS_RESPUESTAS_Nombre____b AS nombre FROM {$BaseDatos_systema}.PLANTILLAS_RESPUESTAS WHERE PLANTILLAS_RESPUESTAS_ConsInte__HUESPED__b = {$session}");
                if($sqlPlantillas){
                    $estado=true;
                    $mensaje=array();
                    if($sqlPlantillas->num_rows > 0){
                        while($row = $sqlPlantillas->fetch_object()){
                            array_push($mensaje,array("id"=>$row->id,"nombre"=>$row->nombre));
                        }
                    }
                }
            }

            echo json_encode(array("estado"=>$estado,"mensaje"=>$mensaje));
        }

        //Guardar las respuestas de una plantilla de respuestas rapidas
        if(isset($_POST['saveRespuestasPlantilla'])){
            $i = 0;
            $id = isset($_POST['idPlantilla']) ? $_POST['idPlantilla'] : -1;
            $estado=false;
            $mensaje="No se detecto la operación";
            $nombrePlantilla= isset($_POST['nombrePlantilla']) ? $_POST['nombrePlantilla'] : "NULL";

            if($id >= 0){
                if($id > 0){
                    $sqlUpPlantilla=$mysqli->query("UPDATE {$BaseDatos_systema}.PLANTILLAS_RESPUESTAS SET PLANTILLAS_RESPUESTAS_Nombre____b='{$nombrePlantilla}' WHERE PLANTILLAS_RESPUESTAS_ConsInte__b={$id}");
                }else{
                    $sqlInsertPlantilla=$mysqli->query("INSERT INTO {$BaseDatos_systema}.PLANTILLAS_RESPUESTAS (PLANTILLAS_RESPUESTAS_ConsInte__HUESPED__b,PLANTILLAS_RESPUESTAS_Nombre____b) VALUES ({$_SESSION['HUESPED']},'{$nombrePlantilla}')");
                    if($sqlInsertPlantilla){
                        $id=$mysqli->insert_id;
                    }else{
                        $mensaje="No se guardo la plantilla";
                    }
                }

                if($id > 0){
                    while($i<=$_POST['totalPlantillas']){
                        if(isset($_POST["plantilla_{$i}"])){
                            $accion=$_POST["accion_{$i}"];
                            $titulo=$_POST["titulo_{$i}"];
                            $texto=$_POST["plantilla_{$i}"];
                            $valor=isset($_POST["valor_{$i}"]) ? $_POST["valor_{$i}"] : false;
    
                            if($accion=='add'){
                                $sqlPlantilla=$mysqli->query("INSERT INTO {$BaseDatos_systema}.RESPUESTAS_PLANTILLAS (RESPUESTAS_PLANTILLAS_Titulo____b,RESPUESTAS_PLANTILLAS_Contenido____b,PLANTILLAS_RESPUESTAS_ConsInte__PLANTILLAS_RESPUESTAS__b) VALUES ('{$titulo}','{$texto}',{$id})");
                            }
    
                            if($accion=='update'){
                                $sqlPlantilla=$mysqli->query("UPDATE {$BaseDatos_systema}.RESPUESTAS_PLANTILLAS SET RESPUESTAS_PLANTILLAS_Titulo____b='{$titulo}', RESPUESTAS_PLANTILLAS_Contenido____b='{$texto}' WHERE RESPUESTAS_PLANTILLAS_ConsInte__b={$valor}");
                            }
    
                            if($accion=='delete'){
                                $sqlPlantilla=$mysqli->query("DELETE FROM {$BaseDatos_systema}.RESPUESTAS_PLANTILLAS WHERE RESPUESTAS_PLANTILLAS_ConsInte__b={$valor}");
                            }
                        }
                        $i++;
                    }
                    $estado=true;
                    $mensaje=$id;
                }
            }

            echo json_encode(array("estado"=>$estado,"mensaje"=>$mensaje));
        }

        //Obtener la lista de respuestas de una ´plantilla de respuestas rapidas
        if(isset($_POST["getRespuestasPlantilla"])){
            $id=isset($_POST['id']) ? $_POST['id'] : 0;
            $estado=false;
            $mensaje="No se pudo obtener la lista de respuestas";
            
            if($id > 0){
                $sqlPlantillas=$mysqli->query("SELECT RESPUESTAS_PLANTILLAS_ConsInte__b AS id, RESPUESTAS_PLANTILLAS_Titulo____b AS pregunta,RESPUESTAS_PLANTILLAS_Contenido____b AS respuesta FROM {$BaseDatos_systema}.RESPUESTAS_PLANTILLAS WHERE PLANTILLAS_RESPUESTAS_ConsInte__PLANTILLAS_RESPUESTAS__b={$id}");
                $mensaje=array();
                $i=0;
                if($sqlPlantillas){
                    $estado=true;
                    if($sqlPlantillas->num_rows>0){
                        while($plantilla = $sqlPlantillas->fetch_array()){
                            array_push($mensaje,$plantilla);
                        }
                    }
                }
            }

            echo json_encode(array("estado"=>$estado,"mensaje"=>$mensaje));
        }

        //Datos del formulario
        if (isset($_GET["callDatosSubgrilla_callback"])) {

            $id = $_GET['id'];
            $numero = $id;

            $SQL = " SELECT pasos_troncales.id as id ,tipos_destino.nombre as tipos_destino, a.nombre_interno as troncal, b.nombre_interno as troncal_desborde ";
            $SQL .= " FROM " . $BaseDatos_telefonia . ".pasos_troncales ";
            $SQL .= " LEFT JOIN " . $BaseDatos_telefonia . ".tipos_destino ON tipos_destino.id = pasos_troncales.id_tipos_destino ";
            $SQL .= " LEFT JOIN " . $BaseDatos_telefonia . ".dy_troncales a ON a.id = pasos_troncales.id_troncal ";
            $SQL .= " LEFT JOIN " . $BaseDatos_telefonia . ".dy_troncales b ON b.id = pasos_troncales.id_troncal_desborde ";
            $SQL .= " WHERE pasos_troncales.id_campana = " . $numero;
            $SQL .= " ORDER BY tipos_destino.nombre";

            //  echo $SQL;
            if (stristr($_SERVER["HTTP_ACCEPT"], "application/xhtml+xml")) {
                header("Content-type: application/xhtml+xml;charset=utf-8");
            } else {
                header("Content-type: text/xml;charset=utf-8");
            }

            $et = ">";
            echo "<?xml version='1.0' encoding='utf-8'?$et\n";
            echo "<rows>"; // be sure to put text data in CDATA

            $result = $mysqli->query($SQL);
            while ($fila = $result->fetch_object()) {
                echo "<row asin='" . $fila->id . "'>";
                echo "<cell>" . ($fila->id) . "</cell>";
                echo "<cell>" . ($fila->tipos_destino) . "</cell>";
                echo "<cell>" . ($fila->troncal) . "</cell>";
                echo "<cell>" . ($fila->troncal_desborde) . "</cell>";
                echo "</row>";
            }
            echo "</rows>";
        }        
        
        if(isset($_POST['CallDatos'])){
          
            $str_Lsql = 'SELECT G10_ConsInte__b, G10_C71 as principal ,G10_C70,G10_C71,G10_C72,G10_C73,G10_C74,G10_C75,G10_C76,G10_C77,G10_C105,G10_C106,G10_C107,G10_C78,G10_C330,G10_C79,G10_C80,G10_C81,G10_C82,G10_C83,G10_C84,G10_C85,G10_C90,G10_C91,G10_C92,G10_C93,G10_C94,G10_C95,G10_C98,G10_C99,G10_C100,G10_C101,G10_C102,G10_C103,G10_C104,G10_C108,G10_C109,G10_C110,G10_C111,G10_C112,G10_C113,G10_C114,G10_C115,G10_C116,G10_C117,G10_C118,G10_C119,G10_C120,G10_C121,G10_C122,G10_C123,G10_C124,G10_C125,G10_C126,G10_C127,G10_C128,G10_C129,G10_C130,G10_C131,G10_C333, ESTPAS_ConsInte__b FROM '.$BaseDatos_systema.'.G10 LEFT JOIN '.$BaseDatos_systema.'.ESTPAS ON ESTPAS_ConsInte__CAMPAN_b = G10_ConsInte__b WHERE G10_ConsInte__b ='.$_POST['id'];
            $result = $mysqli->query($str_Lsql);
            $datos = array();
            $i = 0;

            while($key = $result->fetch_object()){

                $datos[$i]['G10_C70'] = $key->G10_C70;

                $datos[$i]['G10_C71'] = $key->G10_C71;

                $datos[$i]['G10_C72'] = $key->G10_C72;

                $datos[$i]['G10_C73'] = $key->G10_C73;

                $datos[$i]['G10_C74'] = $key->G10_C74;

                $datos[$i]['G10_C75'] = $key->G10_C75;

                $datos[$i]['G10_C76'] = $key->G10_C76;

                $datos[$i]['G10_C77'] = $key->G10_C77;

                $datos[$i]['G10_C105'] = $key->G10_C105;

                $datos[$i]['G10_C106'] = $key->G10_C106;

                $datos[$i]['G10_C107'] = $key->G10_C107;

                $datos[$i]['G10_C78'] = $key->G10_C78;

                $datos[$i]['G10_C330'] = $key->G10_C330;

                $datos[$i]['G10_C79'] = $key->G10_C79;

                $datos[$i]['G10_C80'] = $key->G10_C80;

                $datos[$i]['G10_C81'] = $key->G10_C81;

                $datos[$i]['G10_C82'] = $key->G10_C82;

                $datos[$i]['G10_C83'] = $key->G10_C83;

                $datos[$i]['G10_C84'] = $key->G10_C84;

                $datos[$i]['G10_C85'] = $key->G10_C85;

                $datos[$i]['G10_C90'] = $key->G10_C90;

                $datos[$i]['G10_C91'] = $key->G10_C91;

                $datos[$i]['G10_C92'] = $key->G10_C92;

                $datos[$i]['G10_C93'] = $key->G10_C93;

                $datos[$i]['G10_C94'] = $key->G10_C94;

                $datos[$i]['G10_C95'] = $key->G10_C95;

                $datos[$i]['G10_C98'] = $key->G10_C98;

                $datos[$i]['G10_C99'] = $key->G10_C99;

                $datos[$i]['G10_C100'] = $key->G10_C100;

                $datos[$i]['G10_C101'] = $key->G10_C101;

                $datos[$i]['G10_C102'] = $key->G10_C102;

                $datos[$i]['G10_C333'] = $key->G10_C333;

                $datos[$i]['G10_C103'] = $key->G10_C103;

                $datos[$i]['G10_C104'] = $key->G10_C104;

                $datos[$i]['G10_C108'] = $key->G10_C108;
  
                if(!is_null($key->G10_C109)){
                    $datos[$i]['G10_C109'] = explode(' ', $key->G10_C109)[1];
                }
  
                if(!is_null($key->G10_C110)){
                    $datos[$i]['G10_C110'] = explode(' ', $key->G10_C110)[1];
                }

                $datos[$i]['G10_C111'] = $key->G10_C111;
  
                if(!is_null($key->G10_C112)){
                    $datos[$i]['G10_C112'] = explode(' ', $key->G10_C112)[1];
                }
  
                if(!is_null($key->G10_C113)){
                    $datos[$i]['G10_C113'] = explode(' ', $key->G10_C113)[1];
                }

                $datos[$i]['G10_C114'] = $key->G10_C114;
  
                if(!is_null($key->G10_C115)){
                    $datos[$i]['G10_C115'] = explode(' ', $key->G10_C115)[1];
                }
  
                if(!is_null($key->G10_C116)){
                    $datos[$i]['G10_C116'] = explode(' ', $key->G10_C116)[1];
                }

                $datos[$i]['G10_C117'] = $key->G10_C117;
  
                if(!is_null($key->G10_C118)){
                    $datos[$i]['G10_C118'] = explode(' ', $key->G10_C118)[1];
                }

                $datos[$i]['G10_C119'] = $key->G10_C119;

                if(!is_null($key->G10_C119)){
                    $datos[$i]['G10_C119'] = explode(' ', $key->G10_C119)[1];
                }

                $datos[$i]['G10_C120'] = $key->G10_C120;
  
                if(!is_null($key->G10_C121)){
                    $datos[$i]['G10_C121'] = explode(' ', $key->G10_C121)[1];
                }
  
                if(!is_null($key->G10_C122)){
                    $datos[$i]['G10_C122'] = explode(' ', $key->G10_C122)[1];
                }

                $datos[$i]['G10_C123'] = $key->G10_C123;
  
                if(!is_null($key->G10_C124)){
                    $datos[$i]['G10_C124'] = explode(' ', $key->G10_C124)[1];
                }
  
                if(!is_null($key->G10_C125)){
                    $datos[$i]['G10_C125'] = explode(' ', $key->G10_C125)[1];
                }

                $datos[$i]['G10_C126'] = $key->G10_C126;
  
                if(!is_null($key->G10_C127)){
                    $datos[$i]['G10_C127'] = explode(' ', $key->G10_C127)[1];
                }
  
                if(!is_null($key->G10_C128)){
                    $datos[$i]['G10_C128'] = explode(' ', $key->G10_C128)[1];
                }

                $datos[$i]['G10_C129'] = $key->G10_C129;
  
                if(!is_null($key->G10_C130)){
                    $datos[$i]['G10_C130'] = explode(' ', $key->G10_C130)[1];
                }
  
                if(!is_null($key->G10_C131)){
                    $datos[$i]['G10_C131'] = explode(' ', $key->G10_C131)[1];
                }
      
                $datos[$i]['principal'] = $key->principal;

                $datos[$i]['id_estpas'] = $key->ESTPAS_ConsInte__b;

                $datos[$i]['G10_ConsInte__b'] = $key->G10_ConsInte__b;

                $datos[$i]['camposCbx']=getCamposCBX($key->G10_C106);

                $i++;
            }
            echo json_encode($datos);
        }

        if(isset($_POST['InfoChat'])){
            $str_Lsql = 'SELECT ESTPAS_ConsInte__CAMPAN_b, ESTPAS_ConsInte__ESTRAT_b FROM '.$BaseDatos_systema.'.ESTPAS WHERE ESTPAS_ConsInte__b = '.$_POST['id'];
            $res = $mysqli->query($str_Lsql);
            $datos = $res->fetch_array();

            $estrategiaId = $datos['ESTPAS_ConsInte__ESTRAT_b'];

            $Lsql = "SELECT CAMPAN_id_pregun_campo_busqueda_whatsapp as busquedaW, CAMPAN_id_pregun_campo_busqueda_web as busquedaWeb, CAMPAN_IdCamCbx__b FROM ".$BaseDatos_systema.".CAMPAN WHERE CAMPAN_ConsInte__b = ".$datos['ESTPAS_ConsInte__CAMPAN_b'];
            $res = $mysqli->query($Lsql);
            $camp = $res->fetch_array();

            $idCampana = $datos['ESTPAS_ConsInte__CAMPAN_b'];
            $campanaCbx = $camp['CAMPAN_IdCamCbx__b'];
            $campoBusquedaWhatsapp = $camp['busquedaW'];

            // Cargo la lista de bolas de bot disponible en la estrategia
            $sql = "SELECT ESTPAS_ConsInte__b AS id, ESTPAS_Comentari_b AS nombre FROM {$BaseDatos_systema}.ESTPAS WHERE ESTPAS_Tipo______b = 12 AND ESTPAS_ConsInte__ESTRAT_b = ".$estrategiaId;
            $resBots = $mysqli->query($sql);

            $bots = [];
            $i = 0;
            if($resBots->num_rows > 0){
                while ($row = $resBots->fetch_object()) {
                    $bots[$i] = $row;
                    $i++;
                }
            }

            // Traigo la configuracion del bot
            $sql = "SELECT 
                CACOCH_ConsInte__b AS id,
                CACOCH_ConsInte__CAMPAN_b AS id_campana,

                CACOCH_CierreChatFrase_b AS cierre_chat_frase,
                CACOCH_CierreChatEnviarBot_b AS cierre_chat_enviar_bot,
                CACOCH_CierreChatIdEstpasBot_b AS cierre_chat_id_estpas_bot,
                CACOCH_CierreChatIdAutorespuesta_b AS cierre_chat_id_autorespuesta,

                CACOCH_EnEsperaIntervaloMensaje_b AS en_espera_intervalo_mensaje,
                CACOCH_CantMaxMesajeEspera_b AS cant_max_mensajes,
                CACOCH_EnEsperaFrase_b AS en_espera_frase,
                CACOCH_EnEsperaEnviarBot_b AS en_espera_enviar_bot,
                CACOCH_EnEsperaIdEstpasBot_b AS en_espera_id_estpas_bot,
                CACOCH_EnEsperaIdAutorespuesta_b AS en_espera_id_autorespuesta,
                CACOCH_EnEsperaEnviarNotificarPosicion_b AS en_espera_posicion,

                CACOCH_AsignacionExcedidaTiempo_b AS tiempo_asignacion_excedido,
                CACOCH_AsignacionExcedidaFrase_b AS asignacion_excedido_frase,
                CACOCH_AsignacionExcedidaEnviarBot_b AS asignacion_excedido_enviar_bot,
                CACOCH_AsignacionExcedidaIdEstpasBot_b AS asignacion_excedido_id_estpas_bot,
                CACOCH_AsignacionExcedidaIdAutorespuesta_b AS asignacion_excedido_id_autorespuesta,

                CACOCH_InactividadClienteTiempo_b AS tiempo_maximo_inactividad_cliente,
                CACOCH_InactividadClienteFrase_b AS inactividad_cliente_frase,
                CACOCH_InactividadClienteEnviarBot_b AS inactividad_cliente_enviar_bot,
                CACOCH_InactividadClienteIdEstpasBot_b AS inactividad_cliente_id_estpas_bot,
                CACOCH_InactividadClienteIdAutorespuesta_b AS inactividad_cliente_id_autorespuesta,

                CACOCH_InactividadAgenteTiempo_b AS tiempo_maximo_inactividad_agente,
                CACOCH_InactividadAgenteFrase_b AS inactividad_agente_frase,
                CACOCH_InactividadAgenteEnviarBot_b AS inactividad_agente_enviar_bot,
                CACOCH_InactividadAgenteIdEstpasBot_b AS inactividad_agente_id_estpas_bot,
                CACOCH_InactividadAgenteIdAutorespuesta_b AS inactividad_agente_id_autorespuesta,

                CACOCH_AgenteAsignadoFrase_b AS agente_asignado_frase,
                CACOCH_InactividadAgenteActivarTimeout_b AS activar_timeout_agente,
                CACOCH_InactividadClienteActivarTimeout_b AS activar_timeout_cliente
            FROM {$BaseDatos_systema}.CAMPAN_CONFIGURACION_CHAT WHERE CACOCH_ConsInte__CAMPAN_b = ".$idCampana." LIMIT 1";
            $resConfigChat = $mysqli->query($sql);

            $configChatCampana = [];
            $existeConfigChatCampana = false;
            if($resConfigChat->num_rows > 0){
                $configChatCampana = $resConfigChat->fetch_object();
                $existeConfigChatCampana = true;
            }
            
            echo json_encode([
                'bots'=> $bots,
                'existeConfigChatCampana' => $existeConfigChatCampana,
                'configChatCampana' => $configChatCampana
            ]);
        }

        if(isset($_POST['InfoMail'])){
            $str_Lsql = 'SELECT ESTPAS_ConsInte__CAMPAN_b FROM '.$BaseDatos_systema.'.ESTPAS WHERE ESTPAS_ConsInte__b = '.$_POST['id'];
            $res = $mysqli->query($str_Lsql);
            $estpasData = $res->fetch_array();

            // $str_Lsql = 'SELECT id, id_ce_configuracion as cuenta, filtro, condicion, operador FROM '.$dyalogo_canales_electronicos.'.dy_ce_filtros WHERE id_campana_crm = '.$estpasData['ESTPAS_ConsInte__CAMPAN_b'];
            // $res = $mysqli->query($str_Lsql);
            
            // $datos = array();
            // $i = 0;

            // while($key = $res->fetch_object()){
            //     $datos[$i]['id'] = $key->id;
            //     $datos[$i]['cuenta'] = $key->cuenta;
            //     $datos[$i]['filtro'] = $key->filtro;
            //     $datos[$i]['condicion'] = $key->condicion;
            //     $datos[$i]['operador'] = $key->operador;
            //     $i++;
            // }

            // mensaje de bienvenida
            $Lsql = "SELECT CAMPAN_IdCamCbx__b, CAMPAN_id_pregun_campo_busqueda_email as busqueda FROM ".$BaseDatos_systema.".CAMPAN WHERE CAMPAN_ConsInte__b = ".$estpasData['ESTPAS_ConsInte__CAMPAN_b'];
            $res = $mysqli->query($Lsql);
            $camp = $res->fetch_array();

            // $Lsql = "SELECT cuerpo FROM ".$dyalogo_canales_electronicos.".dy_ce_acciones_filtro WHERE accion = 1 AND id_cola_distribucion = ".$camp['CAMPAN_IdCamCbx__b'];
            // $resAccion = $mysqli->query($Lsql);
            // if($resAccion && $resAccion->num_rows > 0){
            //     $dataAccion = $resAccion->fetch_array();
            //     $checkCorreoMensaje = true;
            //     $correoMensaje = $dataAccion['cuerpo'];
            // }else{
            //     $checkCorreoMensaje = false;
            //     $correoMensaje = null;
            // }

            // Firma de correo
            $Lsql = "SELECT firma FROM ".$dyalogo_canales_electronicos.".dy_ce_firmas WHERE id_cola_distribucion = ".$camp['CAMPAN_IdCamCbx__b'];
            $resFirma = $mysqli->query($Lsql);
            if($resFirma && $resFirma->num_rows > 0){
                $dataFirma = $resFirma->fetch_array();
                $checkFirma = true;
                $firmaCorreo = $dataFirma['firma'];
            }else{
                $checkFirma = false;
                $firmaCorreo = null;
            }

            echo json_encode([
                'checkFirma' => $checkFirma,
                'dyTr_firmaCorreo' => $firmaCorreo
            ]);
        }

        if(isset($_POST['infoAvanzado'])){

            $pasoId = $_POST['pasoId'];

            $sql_paso = "SELECT ESTPAS_ConsInte__CAMPAN_b as campana FROM DYALOGOCRM_SISTEMA.ESTPAS WHERE ESTPAS_ConsInte__b = ".$pasoId;
            $res = $mysqli->query($sql_paso);
            $paso = $res->fetch_array();

            $sqlAvanzadosChat = "SELECT max_chats, max_correos_electronicos FROM ".$BaseDatos_telefonia.".dy_campanas WHERE id_campana_crm = ".$paso['campana'];
            $res = $mysqli->query($sqlAvanzadosChat);
            $avanzadoChat = $res->fetch_array();

            $sqlCampan = "SELECT CAMPAN_ConsInte__b AS id, CAMPAN_CorreoTiempoMaxGes_b AS correoTiempoMaxGestion FROM ".$BaseDatos_systema.".CAMPAN WHERE CAMPAN_ConsInte__b = ".$paso['campana'];
            $resCampan = $mysqli->query($sqlCampan);
            $campan = $resCampan->fetch_object();

            echo json_encode([
                'avanzadoChat' => $avanzadoChat,
                "campan" => $campan
            ]);
        }

        if(isset($_POST['infoClickToCall'])){
            $pasoId = $_POST['pasoId'];

            $sql_paso = "SELECT ESTPAS_ConsInte__CAMPAN_b as campana FROM DYALOGOCRM_SISTEMA.ESTPAS WHERE ESTPAS_ConsInte__b = {$pasoId}";
            // echo "<br> sql_paso => $sql_paso <br>";
            $res = $mysqli->query($sql_paso);
            $paso = $res->fetch_array();
            $paso = obtenerIdcbxCampan($paso['campana']);
            $sqlClickToCall = "SELECT * FROM {$BaseDatos_telefonia}.ctc WHERE id_cola_acd = '{$paso}'";
            // echo "<br> sqlClickToCall => $sqlClickToCall <br>";
            $res = $mysqli->query($sqlClickToCall);
            $data = $res->fetch_array();

            echo json_encode([
                'clickToCall' => $data
            ]);
        }

        if(isset($_POST['infoWhatsapp'])){
            
        }

        //Datos del formulario
        if(isset($_POST['CallDatos_2'])){
          
            $str_Lsql = 'SELECT G10_ConsInte__b, G10_C71 as principal ,G10_C70,G10_C71,G10_C72,G10_C73,G10_C74,G10_C75,G10_C76,G10_C77,G10_C105,G10_C106,G10_C107,G10_C78,G10_C330,G10_C79,G10_C80,G10_C81,G10_C82,G10_C83,G10_C84,G10_C85,G10_C90,G10_C91,G10_C92,G10_C93,G10_C94,G10_C95,G10_C98,G10_C99,G10_C100,G10_C101,G10_C102,G10_C103,G10_C104,G10_C108,G10_C109,G10_C110,G10_C111,G10_C112,G10_C113,G10_C114,G10_C115,G10_C116,G10_C117,G10_C118,G10_C119,G10_C120,G10_C121,G10_C122,G10_C123,G10_C124,G10_C125,G10_C126,G10_C127,G10_C128,G10_C129,G10_C130,G10_C131, G10_C318, G10_C319, G10_C320, G10_C321, G10_C322, G10_C325, G10_C323, G10_C324, G10_C328, G10_C329, G10_C330,G10_C333,G10_C335,G10_C336,ESTPAS_ConsInte__ESTRAT_b FROM '.$BaseDatos_systema.'.G10 JOIN '.$BaseDatos_systema.'.ESTPAS ON ESTPAS_ConsInte__CAMPAN_b = G10_ConsInte__b WHERE ESTPAS_ConsInte__b ='.$_POST['id'];
            $result = $mysqli->query($str_Lsql);
            $datos = array();
            $i = 0;

            while($key = $result->fetch_object()){

                $datos[$i]['G10_ConsInte__b'] = $key->G10_ConsInte__b;

                $datos[$i]['G10_C70'] = $key->G10_C70;

                $datos[$i]['G10_C71'] = $key->G10_C71;

                $datos[$i]['G10_C72'] = $key->G10_C72;

                $datos[$i]['G10_C73'] = $key->G10_C73;

                $datos[$i]['G10_C74'] = $key->G10_C74;

                $datos[$i]['G10_C75'] = $key->G10_C75;

                $datos[$i]['G10_C76'] = $key->G10_C76;

                $datos[$i]['G10_C77'] = $key->G10_C77;

                $datos[$i]['G10_C105'] = $key->G10_C105;

                $datos[$i]['G10_C106'] = $key->G10_C106;

                $datos[$i]['G10_C107'] = $key->G10_C107;

                $datos[$i]['G10_C78'] = $key->G10_C78;

                $datos[$i]['G10_C330'] = $key->G10_C330;

                $datos[$i]['G10_C79'] = $key->G10_C79;

                $datos[$i]['G10_C80'] = $key->G10_C80;

                $datos[$i]['G10_C81'] = $key->G10_C81;

                $datos[$i]['G10_C82'] = $key->G10_C82;

                $datos[$i]['G10_C83'] = $key->G10_C83;

                $datos[$i]['G10_C84'] = $key->G10_C84;

                $datos[$i]['G10_C85'] = $key->G10_C85;

                $datos[$i]['G10_C90'] = $key->G10_C90;

                $datos[$i]['G10_C91'] = $key->G10_C91;

                $datos[$i]['G10_C92'] = $key->G10_C92;

                $datos[$i]['G10_C93'] = $key->G10_C93;

                $datos[$i]['G10_C94'] = $key->G10_C94;

                $datos[$i]['G10_C95'] = $key->G10_C95;

                $datos[$i]['G10_C98'] = $key->G10_C98;

                $datos[$i]['G10_C99'] = $key->G10_C99;

                $datos[$i]['G10_C100'] = $key->G10_C100;

                $datos[$i]['G10_C101'] = $key->G10_C101;

                $datos[$i]['G10_C102'] = $key->G10_C102;

                $datos[$i]['G10_C333'] = $key->G10_C333;

                $datos[$i]['G10_C103'] = $key->G10_C103;

                $datos[$i]['G10_C104'] = $key->G10_C104;

                $datos[$i]['G10_C108'] = $key->G10_C108;
  
                if(!is_null($key->G10_C109)){
                    $datos[$i]['G10_C109'] = explode(' ', $key->G10_C109)[1];
                }
  
                if(!is_null($key->G10_C110)){
                    $datos[$i]['G10_C110'] = explode(' ', $key->G10_C110)[1];
                }

                $datos[$i]['G10_C111'] = $key->G10_C111;
  
                if(!is_null($key->G10_C112)){
                    $datos[$i]['G10_C112'] = explode(' ', $key->G10_C112)[1];
                }
  
                if(!is_null($key->G10_C113)){
                    $datos[$i]['G10_C113'] = explode(' ', $key->G10_C113)[1];
                }

                $datos[$i]['G10_C114'] = $key->G10_C114;
  
                if(!is_null($key->G10_C115)){
                    $datos[$i]['G10_C115'] = explode(' ', $key->G10_C115)[1];
                }
  
                if(!is_null($key->G10_C116)){
                    $datos[$i]['G10_C116'] = explode(' ', $key->G10_C116)[1];
                }

                $datos[$i]['G10_C117'] = $key->G10_C117;
  
                if(!is_null($key->G10_C118)){
                    $datos[$i]['G10_C118'] = explode(' ', $key->G10_C118)[1];
                }

                $datos[$i]['G10_C119'] = $key->G10_C119;

                if(!is_null($key->G10_C119)){
                    $datos[$i]['G10_C119'] = explode(' ', $key->G10_C119)[1];
                }

                $datos[$i]['G10_C120'] = $key->G10_C120;
  
                if(!is_null($key->G10_C121)){
                    $datos[$i]['G10_C121'] = explode(' ', $key->G10_C121)[1];
                }
  
                if(!is_null($key->G10_C122)){
                    $datos[$i]['G10_C122'] = explode(' ', $key->G10_C122)[1];
                }

                $datos[$i]['G10_C123'] = $key->G10_C123;
  
                if(!is_null($key->G10_C124)){
                    $datos[$i]['G10_C124'] = explode(' ', $key->G10_C124)[1];
                }
  
                if(!is_null($key->G10_C125)){
                    $datos[$i]['G10_C125'] = explode(' ', $key->G10_C125)[1];
                }

                $datos[$i]['G10_C126'] = $key->G10_C126;
  
                if(!is_null($key->G10_C127)){
                    $datos[$i]['G10_C127'] = explode(' ', $key->G10_C127)[1];
                }
  
                if(!is_null($key->G10_C128)){
                    $datos[$i]['G10_C128'] = explode(' ', $key->G10_C128)[1];
                }

                $datos[$i]['G10_C129'] = $key->G10_C129;
  
                if(!is_null($key->G10_C130)){
                    $datos[$i]['G10_C130'] = explode(' ', $key->G10_C130)[1];
                }
  
                if(!is_null($key->G10_C131)){
                    $datos[$i]['G10_C131'] = explode(' ', $key->G10_C131)[1];
                }

                $datos[$i]['G10_C318'] = $key->G10_C318;

                $datos[$i]['G10_C319'] = $key->G10_C319;

                $datos[$i]['G10_C320'] = $key->G10_C320;

                $datos[$i]['G10_C321'] = $key->G10_C321;

                $datos[$i]['G10_C322'] = $key->G10_C322;

                $datos[$i]['G10_C323'] = $key->G10_C323;

                $datos[$i]['G10_C324'] = $key->G10_C324;

                $datos[$i]['G10_C325'] = $key->G10_C325;

                $datos[$i]['G10_C328'] = $key->G10_C328;


                $datos[$i]['G10_C329'] = $key->G10_C329;

                $datos[$i]['G10_C330'] = $key->G10_C330;

                if($key->G10_C330=='-1'){
                    $datos[$i]['ivr_encuesta']=getIvrEncuesta($key->G10_ConsInte__b);
                }

                $datos[$i]['G10_C335'] = $key->G10_C335;

                $datos[$i]['G10_C336'] = $key->G10_C336;
      
                $datos[$i]['principal'] = $key->principal;

                $datos[$i]['camposCbx']=getCamposCBX($key->G10_C106);
                $datos[$i]['idEstrat']=url::urlSegura($key->ESTPAS_ConsInte__ESTRAT_b);
                //$datos[$i]['G10_ConsInte__b'] = $key->G19_ConsInte__b;
                $i++;
            }
            echo json_encode($datos);
        }

        //Datos de la lista de la izquierda
        if(isset($_POST['CallDatosJson'])){
            $str_Lsql = 'SELECT G10_ConsInte__b as id,  G10_C71 as camp1 , b.LISOPC_Nombre____b as camp2 ';
            $str_Lsql .= ' FROM '.$BaseDatos_systema.'.G10   LEFT JOIN '.$BaseDatos_systema.'.LISOPC as b ON b.LISOPC_ConsInte__b = G10_C76 ';
            if($_POST['Busqueda'] != '' && !is_null($_POST['Busqueda'])){
                $str_Lsql .= ' WHERE  G10_C71 like "%'.$_POST['Busqueda'].'%" ';
                $str_Lsql .= ' OR b.LISOPC_Nombre____b like "%'.$_POST['Busqueda'].'%" ';
            }

            $str_Lsql .= ' ORDER BY G10_ConsInte__b DESC LIMIT 0, 50'; 
            $result = $mysqli->query($str_Lsql);
            $datos = array();
            $i = 0;
            while($key = $result->fetch_object()){
                $datos[$i]['camp1'] = $key->camp1;
                $datos[$i]['camp2'] = $key->camp2;
                $datos[$i]['id'] = $key->id;
                $i++;
            }
            echo json_encode($datos);
        }

        //Esto ya es para cargar los combos en la grilla
        if(isset($_GET['CallDatosLisop_'])){
            $lista = $_GET['idLista'];
            $comboe = $_GET['campo'];
            $str_Lsql = "SELECT LISOPC_ConsInte__b AS OPCION_ConsInte__b, LISOPC_Nombre____b AS OPCION_Nombre____b FROM ".$BaseDatos_systema.".LISOPC WHERE LISOPC_ConsInte__OPCION_b = ".$lista." ORDER BY LISOPC_Nombre____b";
            
            $combo = $mysqli->query($str_Lsql);
            echo '<select class="form-control input-sm"  name="'.$comboe.'" id="'.$comboe.'">';
            echo '<option value="0">Seleccione</option>';
            while($obj = $combo->fetch_object()){
                echo "<option value='".$obj->OPCION_ConsInte__b."'>".$obj->OPCION_Nombre____b."</option>";
            }   
            echo '</select>'; 
        } 

        
        if(isset($_GET['CallDatosCombo_Guion_G10_C73'])){
            $Ysql = 'SELECT   G5_ConsInte__b as id , G5_C28 FROM ".$BaseDatos_systema.".G5';
            $guion = $mysqli->query($Ysql);
            echo '<select class="form-control input-sm"  name="G10_C73" id="G10_C73">';
            echo '<option >NOMBRE</option>';
            while($obj = $guion->fetch_object()){
               echo "<option value='".$obj->id."' dinammicos='0'>".utf8_encode($obj->G5_C28)."</option>";
            } 
            echo '</select>';
        }


        if(isset($_GET['CallDatosCombo_Guion_G10_C74'])){
            $Ysql = 'SELECT   G5_ConsInte__b as id , G5_C28 FROM ".$BaseDatos_systema.".G5';
            $guion = $mysqli->query($Ysql);
            echo '<select class="form-control input-sm"  name="G10_C74" id="G10_C74">';
            echo '<option >NOMBRE</option>';
            while($obj = $guion->fetch_object()){
               echo "<option value='".$obj->id."' dinammicos='0'>".utf8_encode($obj->G5_C28)."</option>";
            } 
            echo '</select>';
        }


        if(isset($_GET['CallDatosCombo_Guion_G10_C90'])){
            $Ysql = 'SELECT   G11_ConsInte__b as id , G11_C87 FROM ".$BaseDatos_systema.".G11';
            $guion = $mysqli->query($Ysql);
            echo '<select class="form-control input-sm"  name="G10_C90" id="G10_C90">';
            echo '<option >NOMBRE USUARIO</option>';
            while($obj = $guion->fetch_object()){
               echo "<option value='".$obj->id."' dinammicos='0'>".utf8_encode($obj->G11_C87)."</option>";
            } 
            echo '</select>';
        }


        if(isset($_GET['CallDatosCombo_Guion_G10_C91'])){
            $Ysql = 'SELECT   G11_ConsInte__b as id , G11_C87 FROM ".$BaseDatos_systema.".G11';
            $guion = $mysqli->query($Ysql);
            echo '<select class="form-control input-sm"  name="G10_C91" id="G10_C91">';
            echo '<option >NOMBRE USUARIO</option>';
            while($obj = $guion->fetch_object()){
               echo "<option value='".$obj->id."' dinammicos='0'>".utf8_encode($obj->G11_C87)."</option>";
            } 
            echo '</select>';
        }


        if(isset($_GET['CallDatosCombo_Guion_G10_C98'])){
            $Ysql = 'SELECT   G12_ConsInte__b as id , G12_C96 FROM ".$BaseDatos_systema.".G12';
            $guion = $mysqli->query($Ysql);
            echo '<select class="form-control input-sm"  name="G10_C98" id="G10_C98">';
            echo '<option >NOMBRE</option>';
            while($obj = $guion->fetch_object()){
               echo "<option value='".$obj->id."' dinammicos='0'>".utf8_encode($obj->G12_C96)."</option>";
            } 
            echo '</select>';
        }


        if(isset($_GET['CallDatosCombo_Guion_G10_C101'])){
            $Ysql = 'SELECT   G12_ConsInte__b as id , G12_C96 FROM ".$BaseDatos_systema.".G12';
            $guion = $mysqli->query($Ysql);
            echo '<select class="form-control input-sm"  name="G10_C101" id="G10_C101">';
            echo '<option >NOMBRE</option>';
            while($obj = $guion->fetch_object()){
               echo "<option value='".$obj->id."' dinammicos='0'>".utf8_encode($obj->G12_C96)."</option>";
            } 
            echo '</select>';
        }


        if(isset($_GET['CallDatosCombo_Guion_G10_C103'])){
            $Ysql = 'SELECT   G6_ConsInte__b as id , G6_C39 FROM ".$BaseDatos_systema.".G6';
            $guion = $mysqli->query($Ysql);
            echo '<select class="form-control input-sm"  name="G10_C103" id="G10_C103">';
            echo '<option >TEXTO</option>';
            while($obj = $guion->fetch_object()){
               echo "<option value='".$obj->id."' dinammicos='0'>".utf8_encode($obj->G6_C39)."</option>";
            } 
            echo '</select>';
        }


        if(isset($_GET['CallDatosCombo_Guion_G10_C104'])){
            $Ysql = 'SELECT   G12_ConsInte__b as id , G12_C96 FROM ".$BaseDatos_systema.".G12';
            $guion = $mysqli->query($Ysql);
            echo '<select class="form-control input-sm"  name="G10_C104" id="G10_C104">';
            echo '<option >NOMBRE</option>';
            while($obj = $guion->fetch_object()){
               echo "<option value='".$obj->id."' dinammicos='0'>".utf8_encode($obj->G12_C96)."</option>";
            } 
            echo '</select>';
        }


        if(isset($_POST['CallEliminate'])){
            if($_POST['oper'] == 'del'){
                $str_Lsql = "DELETE FROM ".$BaseDatos_systema.".G10 WHERE G10_ConsInte__b = ".$_POST['id'];
                if ($mysqli->query($str_Lsql) === TRUE) {
                    echo "1";
                } else {
                    echo "Error eliminado los registros : " . $mysqli->error;
                }
            }
        }


        if(isset($_POST['callDatosNuevamente'])){
            $inicio = $_POST['inicio'];
            $fin = $_POST['fin'];
            $Zsql = 'SELECT  G10_ConsInte__b as id,  G10_C71 as camp1 , b.LISOPC_Nombre____b as camp2  FROM '.$BaseDatos_systema.'.G10   LEFT JOIN '.$BaseDatos_systema.'.LISOPC as b ON b.LISOPC_ConsInte__b = G10_C76 ORDER BY G10_ConsInte__b DESC LIMIT '.$inicio.' , '.$fin;
            $result = $mysqli->query($Zsql);
            while($obj = $result->fetch_object()){
                echo "<tr class='CargarDatos' id='".$obj->id."'>
                    <td>
                        <p style='font-size:14px;'><b>".($obj->camp1)."</b></p>
                        <p style='font-size:12px; margin-top:-10px;'>".($obj->camp2)."</p>
                    </td>
                </tr>";
            } 
        }
          
        //Insertar Extras en caso de haber
        if (isset($_GET["insertarDatosSubgrilla_callback"])) {

            if (isset($_POST["oper"])) {
                $Lsql = '';

                $validar = 0;
                $LsqlU = "UPDATE " . $BaseDatos_telefonia . ".pasos_troncales SET ";
                $LsqlI = "INSERT INTO " . $BaseDatos_telefonia . ".pasos_troncales(";
                $LsqlV = " VALUES (";


                if (isset($_POST["tipos_destino"])) {
                    if ($_POST["tipos_destino"] != '0') {
                        $separador = "";
                        if ($validar == 1) {
                            $separador = ",";
                        }

                        $LsqlU .= $separador . "id_tipos_destino = '" . $_POST["tipos_destino"] . "'";
                        $LsqlI .= $separador . "id_tipos_destino";
                        $LsqlV .= $separador . "'" . $_POST["tipos_destino"] . "'";
                        $validar = 1;
                    }
                }



                $troncal = NULL;
                //este es de tipo numero no se deja ir asi '', si est avacio lo mejor es no mandarlo
                if (isset($_POST["troncal"])) {
                    if ($_POST["troncal"] != '0') {
                        $separador = "";
                        if ($validar == 1) {
                            $separador = ",";
                        }

                        $troncal = $_POST["troncal"];
                        $LsqlU .= $separador . "id_troncal = '" . $troncal . "'";
                        $LsqlI .= $separador . "id_troncal";
                        $LsqlV .= $separador . "'" . $troncal . "'";
                        $validar = 1;
                    }
                }

                $troncal_desborde = NULL;
                //este es de tipo numero no se deja ir asi '', si est avacio lo mejor es no mandarlo
                if (isset($_POST["troncal_desborde"])) {
                    if ($_POST["troncal_desborde"] != '0') {
                        $separador = "";
                        if ($validar == 1) {
                            $separador = ",";
                        }

                        $troncal_desborde = $_POST["troncal_desborde"];
                        $LsqlU .= $separador . " id_troncal_desborde = '" . $troncal_desborde . "'";
                        $LsqlI .= $separador . " id_troncal_desborde";
                        $LsqlV .= $separador . "'" . $troncal_desborde . "'";
                        $validar = 1;
                    }
                }



                if (isset($_POST["Padre"])) {
                    if ($_POST["Padre"] != '') {
                        //esto es porque el padre es el entero

                        $numero = $_POST["Padre"];


                        $G4_C23 = $numero;
                        $LsqlU .= ", id_campana = " . $G4_C23 . "";
                        $LsqlI .= ", id_campana";
                        $LsqlV .= "," . $_POST["Padre"];
                    }
                }

                if (isset($_GET["Id_paso"])) {
                    if ($_GET["Id_paso"] != '') {
                        //esto es porque el padre es el entero

                        $numero = $_GET["Id_paso"];


                        $G4_C23 = $numero;
                        $LsqlU .= ", id_estpas = " . $G4_C23 . "";
                        $LsqlI .= ", id_estpas";
                        $LsqlV .= "," . $_GET["Id_paso"];
                    }
                }



                if (isset($_POST['oper'])) {
                    if ($_POST["oper"] == 'add') {
                        $Lsql = $LsqlI . ")" . $LsqlV . ")";
                    } else if ($_POST["oper"] == 'edit') {
                        $Lsql = $LsqlU . " WHERE id =" . $_POST["id"];
                    } else if ($_POST['oper'] == 'del') {
                        $Lsql = "DELETE FROM  " . $BaseDatos_telefonia . ".pasos_troncales WHERE id = " . $_POST['id'];
                        $validar = 1;
                    }
                }

                if ($validar == 1) {
                    // echo $Lsql;
                    $id_Usuario_Nuevo = 1;
                    if ($mysqli->query($Lsql) === TRUE) {
                        $id_Usuario_Nuevo = $mysqli->insert_id;

                        if ($_POST["oper"] == 'add') {
                            guardar_auditoria("INSERTAR", "INSERTAR REGISTRO EN pasos_troncales");
                        } else if ($_POST["oper"] == 'edit') {
                            guardar_auditoria("ACTUALIZAR", "ACTUALIZO EL REGISTRO # " . $_POST["padre"] . " EN pasos_troncales");
                        } else if ($_POST["oper"] == 'del') {
                            guardar_auditoria("ELIMINAR", "ELIMINO EL REGISTRO # " . $_POST['id'] . " EN pasos_troncales");
                        }

                        echo $id_Usuario_Nuevo;
                    } else {
                        echo "Error Hacieno el proceso los registros : " . $mysqli->error;
                    }
                }
            }
        }        

        //Inserciones o actualizaciones
        if(isset($_POST["oper"]) && isset($_GET['insertarDatosGrilla'])){
            $id_Guion  = 0;
            $id_Muestras = 0;
            $id_scrip  = 0;

            if(isset($_POST['G10_C74'])){
                $id_Guion  = $_POST['G10_C74'];
            }

            if(isset($_POST['G10_C75'])){
                $id_Muestras  = $_POST['G10_C75'];
            }

            if(isset($_POST['G10_C73'])){
                $id_scrip  = $_POST['G10_C73'];
            }         

            //Base de datos G10_C74;

            if($_POST["oper"] == 'add' ){

                /* preguntamos si viene a ser creado desde el Excel Y creamos todo desde Excel */
                if(isset($_POST['GenerarFromExel'])){

                    $datosArray = array('A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z', 'AA', 'AB', 'AC', 'AD', 'AE', 'AF', 'AG', 'AH', 'AI', 'AJ', 'AK', 'AL', 'AM', 'AN', 'AP', 'AQ', 'AR', 'AS', 'AT', 'AU', 'AV', 'AW', 'AX', 'AY', 'AZ');


                    $nombreScript = $_POST['G10_C71']." - BD";
                    $ScriptLsql = "INSERT INTO ".$BaseDatos_systema.".G5(G5_C28, G5_C29, G5_C30, G5_C316) VALUES ('".$nombreScript."' , 2 , 'Generado automaticamente atravez de un excel' , ".$_SESSION['HUESPED'].")";
                    $Bdtraducir = 0;
                    //echo $ScriptLsql;
                    if($mysqli->query($ScriptLsql) === true){
                        
                        $ultimoGuion = $mysqli->insert_id;

                        $Bdtraducir = $ultimoGuion;
                        
                        $id_Guion = $ultimoGuion;

                        /* creamos la general */
                        $Lsql_General = "INSERT INTO ".$BaseDatos_systema.".G7(G7_C60, G7_C38, G7_C36 , G7_C34, G7_C33, G7_C35) VALUES(".$ultimoGuion.", 1, 1, 1, 'GENERAL', 2)";

                        if($mysqli->query($Lsql_General) === true){
                            $general = $mysqli->insert_id;
                            /* mandaron a generar desde el Excel */
                            /* lo pirmero es obtener los nombres del Excel y meterlos en general */
                            require "../../../carga/Excel.php";
                            if(isset($_FILES['newGuionFile']['tmp_name']) && !empty($_FILES['newGuionFile']['tmp_name'])){

                                $name   = $_FILES['newGuionFile']['name'];
                                $tname  = $_FILES['newGuionFile']['tmp_name'];
                                ini_set('memory_limit','128M');

                                if($_FILES['newGuionFile']["type"] == 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'){
                                    $objReader = new PHPExcel_Reader_Excel2007();
                                    $objReader->setReadDataOnly(true);
                                    $obj_excel = $objReader->load($tname);
                                }else if($_FILES['newGuionFile']["type"] == 'application/vnd.ms-excel'){
                                    $obj_excel = PHPExcel_IOFactory::load($tname);
                                }
                               
                                
                                $sheetData = $obj_excel->getActiveSheet()->toArray(null,true,true,true);
                                $arr_datos = array();
                                $highestColumm = $obj_excel->setActiveSheetIndex(0)->getHighestColumn(); // e.g. "EL"
                                $highestRow = $obj_excel->setActiveSheetIndex(0)->getHighestRow();
                                $highestColumm++;
                                $datasets = array();
                                for ($row = 1; $row < $highestRow + 1; $row++) {
                                    $dataset = array();
                                    for ($column = 'A'; $column != $highestColumm; $column++) {
                                        $dataset[] = $obj_excel->setActiveSheetIndex(0)->getCell($column . $row)->getValue();
                                    }
                                    $datasets[] = $dataset;
                                }

                                $datosPoblacion = array();
                                $dtaosTelefonicos = array();
                                $datosColumnasInsertar = array();

                                $j = 0;
                                for($i = 0; $i < count($datasets[0]); $i++){
                                    /* aqui si empezamosa meter datos */
                                    $Lsql_campa_campo = "INSERT INTO ".$BaseDatos_systema.".PREGUN(PREGUN_Texto_____b, PREGUN_Tipo______b, PREGUN_IndiRequ__b, PREGUN_ConsInte__SECCIO_b, PREGUN_ConsInte__GUION__b,   PREGUN_FueGener_b, PREGUN_OrdePreg__b) VALUES ('".preg_replace('/(\r\n|\r|\n)+/', " ", $datasets[0][$i])."', 1, 0, ".$general.", ".$ultimoGuion.", 1, ".($i+1).");";

                                    if($mysqli->query($Lsql_campa_campo) === true){
                                        $lasrt = $mysqli->insert_id;

                                        $Lsql_Campo = "INSERT INTO ".$BaseDatos_systema.".CAMPO_ (CAMPO__Nombre____b, CAMPO__Tipo______b, CAMPO__ConsInte__PREGUN_b) VALUES ('G".$ultimoGuion."_C".$lasrt."' , 1 , ".$lasrt.")";

                                        $mysqli->query($Lsql_Campo);

                                        $datosColumnasInsertar[$i]['campo'] = 'G'.$ultimoGuion."_C".$lasrt;
                                        $datosColumnasInsertar[$i]['column'] = $datosArray[$i];

                                        
                                        $datosPoblacion[$i]['CAMINC_ConsInte__CAMPO_Pob_b'] = $lasrt;
                                        $datosPoblacion[$i]['CAMINC_NomCamPob_b'] = 'G'.$ultimoGuion."_C".$lasrt;
                                        $datosPoblacion[$i]['CAMINC_TexPrePob_b'] = $datasets[0][$i];
                                        $datosPoblacion[$i]['CAMINC_ConsInte__GUION__Pob_b'] = $ultimoGuion;

                                        /* cmapos primario y secundario */
                                        
                                        if($i == 0){
                                            $primariLsql = "UPDATE ".$BaseDatos_systema.".G5 SET G5_C31 = ".$lasrt." WHERE G5_ConsInte__b = ".$ultimoGuion;
                                            if($mysqli->query($primariLsql) === true){

                                            }else{
                                                echo "Error Insertanndo el principal => ".$mysqli->error;
                                            }
                                        }

                                        if($i == 1){
                                            $secundariLsql = "UPDATE ".$BaseDatos_systema.".G5 SET G5_C59 = ".$lasrt." WHERE G5_ConsInte__b = ".$ultimoGuion;
                                            if($mysqli->query($secundariLsql) === true){

                                            }else{
                                                echo "Error Insertanndo el principal => ".$mysqli->error;
                                            }
                                        }
                                    }
                                }
                            }
                        }

                        $datosBusquedaLsql = "SELECT PREGUN_ConsInte__b ,PREGUN_Texto_____b  FROM ".$BaseDatos_systema.".PREGUN WHERE  PREGUN_ConsInte__GUION__b = ".$Bdtraducir." AND (PREGUN_Texto_____b like '%celular%' or PREGUN_Texto_____b like '%telefono%' or PREGUN_Texto_____b like '%teléfono%' or PREGUN_Texto_____b = 'cel' or PREGUN_Texto_____b = 'tel' or PREGUN_Texto_____b = 'nombre' or PREGUN_Texto_____b = 'mail' or PREGUN_Texto_____b = 'correo' or PREGUN_Texto_____b like '%nombre%' or PREGUN_Texto_____b like '%mail%' or PREGUN_Texto_____b like '%correo%'  or PREGUN_Texto_____b like '%name%'  or PREGUN_Texto_____b like '%cédula%'  or PREGUN_Texto_____b like '%cedula%' or PREGUN_Texto_____b like '%id%'  or PREGUN_Texto_____b = 'name'  or PREGUN_Texto_____b = 'id' ) AND (PREGUN_Tipo______b = 1 OR PREGUN_Tipo______b = 2 OR PREGUN_Tipo______b = 3 OR PREGUN_Tipo______b = 4 );";

                        $res_Bus = $mysqli->query($datosBusquedaLsql);

                        while($key = $res_Bus->fetch_object()){

                            $LsqlUpdateBusquedas = "UPDATE ".$BaseDatos_systema.".PREGUN SET PREGUN_IndiBusc__b = -1 WHERE PREGUN_ConsInte__b = ".$key->PREGUN_ConsInte__b;

                            if($mysqli->query($LsqlUpdateBusquedas) === true){

                            }else{
                                echo "ERROR PREGUN BUSQUEDA => ".$mysqli->error;
                            }
                        }
                        
                        crearSeccionesBD($ultimoGuion,2);


                    }else{
                        echo " creando el Guion ". $mysqli->error;
                    }


                    /* Ahora generamos el Script */
                    $nombreScript = $_POST['G10_C71']." - SCRIPT";
                    $ScriptLsql = "INSERT INTO ".$BaseDatos_systema.".G5(G5_C28, G5_C29, G5_C30, G5_C316) VALUES ('".$nombreScript."' , 1 , 'Generado automaticamente atravez de un excel' , ".$_SESSION['HUESPED'].")";
                    //echo $ScriptLsql;
                    if($mysqli->query($ScriptLsql) === true){
                        $ultimoGuion = $mysqli->insert_id;
                        $id_scrip = $ultimoGuion;
                        $Script = $ultimoGuion;
                        /* creamos la general */
                        $Lsql_General = "INSERT INTO ".$BaseDatos_systema.".G7(G7_C60, G7_C38, G7_C36 , G7_C34, G7_C33, G7_C35) VALUES(".$ultimoGuion.", 1, 1, 1, 'DATOS DE LA BASE DE DATOS', 2)";
                        if($mysqli->query($Lsql_General) === true){
                            $general = $mysqli->insert_id;
                            /* mandaron a generar desde el Excel */
                            /* lo pirmero es obtener los nombres del Excel y meterlos en general */
                            if(isset($_FILES['newGuionFile']['tmp_name']) && !empty($_FILES['newGuionFile']['tmp_name'])){

                                $name   = $_FILES['newGuionFile']['name'];
                                $tname  = $_FILES['newGuionFile']['tmp_name'];
                                ini_set('memory_limit','128M');

                                if($_FILES['newGuionFile']["type"] == 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'){
                                    $objReader = new PHPExcel_Reader_Excel2007();
                                    $objReader->setReadDataOnly(true);
                                    $obj_excel = $objReader->load($tname);
                                }else if($_FILES['newGuionFile']["type"] == 'application/vnd.ms-excel'){
                                    $obj_excel = PHPExcel_IOFactory::load($tname);
                                }
                               
                                
                                $sheetData = $obj_excel->getActiveSheet()->toArray(null,true,true,true);
                                $arr_datos = array();
                                $highestColumm = $obj_excel->setActiveSheetIndex(0)->getHighestColumn(); // e.g. "EL"
                                $highestRow = $obj_excel->setActiveSheetIndex(0)->getHighestRow();
                                $highestColumm++;
                                $datasets = array();
                                for ($row = 1; $row < $highestRow + 1; $row++) {
                                    $dataset = array();
                                    for ($column = 'A'; $column != $highestColumm; $column++) {
                                        $dataset[] = $obj_excel->setActiveSheetIndex(0)->getCell($column . $row)->getValue();
                                    }
                                    $datasets[] = $dataset;
                                }

                                $datosScript = array();

                                for($i = 0; $i < count($datasets[0]); $i++){
                                    /* aqui si empezamosa meter datos */
                                    $Lsql_campa_campo = "INSERT INTO ".$BaseDatos_systema.".PREGUN(PREGUN_Texto_____b, PREGUN_Tipo______b, PREGUN_IndiRequ__b, PREGUN_ConsInte__SECCIO_b, PREGUN_ConsInte__GUION__b,   PREGUN_FueGener_b, PREGUN_OrdePreg__b) VALUES ('".preg_replace('/(\r\n|\r|\n)+/', " ", $datasets[0][$i])."', 1, 0, ".$general.", ".$ultimoGuion.", 1, ".($i+1).");";

                                

                                    if($mysqli->query($Lsql_campa_campo) === true){

                                        $lasrt = $mysqli->insert_id;

                                        $Lsql_Campo = "INSERT INTO ".$BaseDatos_systema.".CAMPO_ (CAMPO__Nombre____b, CAMPO__Tipo______b, CAMPO__ConsInte__PREGUN_b) VALUES ('G".$ultimoGuion."_C".$lasrt."' , 1 , ".$lasrt.")";

                                        $mysqli->query($Lsql_Campo);

                                        $datosScript[$i]['CAMINC_ConsInte__CAMPO_Gui_b'] = $lasrt;
                                        $datosScript[$i]['CAMINC_NomCamGui_b'] = 'G'.$ultimoGuion."_C".$lasrt;
                                        $datosScript[$i]['CAMINC_TexPreGui_b'] = $datasets[0][$i];
                                        $datosScript[$i]['CAMINC_ConsInte__GUION__Gui_b'] = $ultimoGuion;

                                        if($i == 0){
                                            $primariLsql = "UPDATE ".$BaseDatos_systema.".G5 SET G5_C31 = ".$lasrt." WHERE G5_ConsInte__b = ".$ultimoGuion;
                                            if($mysqli->query($primariLsql) === true){

                                            }else{
                                                echo "Error Insertanndo el principal => ".$mysqli->error;
                                            }
                                        }

                                        if($i == 1){
                                            $secundariLsql = "UPDATE ".$BaseDatos_systema.".G5 SET G5_C59 = ".$lasrt." WHERE G5_ConsInte__b = ".$ultimoGuion;
                                            if($mysqli->query($secundariLsql) === true){

                                            }else{
                                                echo "Error Insertanndo el principal => ".$mysqli->error;
                                            }
                                        }

                                    }
                                }
                            }
                        }
                        crearSecciones($ultimoGuion, $nombreScript, $id_Guion,1);

                    }else{
                        echo " creando el Script ". $mysqli->error;
                    }

                    /* falta invocar el generador */
                    
                    
                }


                if(isset($_POST['generarFromDB'])){
                    /* toca generar desde la base de datos */
                    /* Ahora generamos el Script */

                    $datosArray = array('A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z', 'AA', 'AB', 'AC', 'AD', 'AE', 'AF', 'AG', 'AH', 'AI', 'AJ', 'AK', 'AL', 'AM', 'AN', 'AP', 'AQ', 'AR', 'AS', 'AT', 'AU', 'AV', 'AW', 'AX', 'AY', 'AZ');


                    $nombreScript = $_POST['G10_C71']." - SCRIPT";
                    $ScriptLsql = "INSERT INTO ".$BaseDatos_systema.".G5(G5_C28, G5_C29, G5_C30, G5_C316) VALUES ('".$nombreScript."' , 1 , 'Generado automaticamente tomando la estructura de la base de datos G".$id_Guion."' , ".$_SESSION['HUESPED'].")";
                    //echo $ScriptLsql;
                    if($mysqli->query($ScriptLsql) === true){
                        $ultimoGuion = $mysqli->insert_id;
                        $id_scrip = $ultimoGuion;

                        /* creamos la general */
                        $Lsql_General = "INSERT INTO ".$BaseDatos_systema.".G7(G7_C60, G7_C38, G7_C36 , G7_C34, G7_C33, G7_C35) VALUES(".$ultimoGuion.", 1, 1, 1, 'DATOS DE LA BASE DE DATOS', 2)";
                        if($mysqli->query($Lsql_General) === true){
                            $general = $mysqli->insert_id;
                            /* mandaron a generar desde el Excel */
                            /* lo pirmero es obtener los nombres del Excel y meterlos en general */
                            $DatosLsql = "SELECT * FROM ".$BaseDatos_systema.".PREGUN WHERE PREGUN_ConsInte__GUION__b = ".$id_Guion;
                            $res_DatosLsql = $mysqli->query($DatosLsql);

                            $datosScript = array();
                            $datosPoblacion = array();
                            $dtaosTelefonicos = array();
                            $datosColumnasInsertar = array();

                            $i = 0;
                            while($key = $res_DatosLsql->fetch_object()){

                                /* aqui si empezamosa meter datos */
                                $Lsql_campa_campo = "INSERT INTO ".$BaseDatos_systema.".PREGUN(PREGUN_Texto_____b, PREGUN_Tipo______b, PREGUN_IndiRequ__b, PREGUN_ConsInte__SECCIO_b, PREGUN_ConsInte__GUION__b,   PREGUN_FueGener_b, PREGUN_OrdePreg__b) VALUES ('".$key->PREGUN_Texto_____b."', 1, 0, ".$general.", ".$ultimoGuion.", 1, ".$key->PREGUN_OrdePreg__b.");";

                                $datosColumnasInsertar[$i]['campo'] = 'G'.$id_Guion."_C".$key->PREGUN_ConsInte__b;
                                $datosColumnasInsertar[$i]['column'] = $datosArray[$i];

                                        
                                $datosPoblacion[$i]['CAMINC_ConsInte__CAMPO_Pob_b'] = $key->PREGUN_ConsInte__b;
                                $datosPoblacion[$i]['CAMINC_NomCamPob_b'] = 'G'.$id_Guion."_C".$key->PREGUN_ConsInte__b;
                                $datosPoblacion[$i]['CAMINC_TexPrePob_b'] = $key->PREGUN_Texto_____b;
                                $datosPoblacion[$i]['CAMINC_ConsInte__GUION__Pob_b'] = $id_Guion;
                        
                                if($mysqli->query($Lsql_campa_campo) === true){

                                    $lasrt = $mysqli->insert_id;

                                    $Lsql_Campo = "INSERT INTO ".$BaseDatos_systema.".CAMPO_ (CAMPO__Nombre____b, CAMPO__Tipo______b, CAMPO__ConsInte__PREGUN_b) VALUES ('G".$ultimoGuion."_C".$lasrt."' , 1 , ".$lasrt.")";

                                    $mysqli->query($Lsql_Campo);

                                    $datosScript[$i]['CAMINC_ConsInte__CAMPO_Gui_b'] = $lasrt;
                                    $datosScript[$i]['CAMINC_NomCamGui_b'] = 'G'.$ultimoGuion."_C".$lasrt;
                                    $datosScript[$i]['CAMINC_TexPreGui_b'] = $key->PREGUN_Texto_____b;
                                    $datosScript[$i]['CAMINC_ConsInte__GUION__Gui_b'] = $ultimoGuion;

                                    if($i == 0){
                                        $primariLsql = "UPDATE ".$BaseDatos_systema.".G5 SET G5_C31 = ".$lasrt." WHERE G5_ConsInte__b = ".$ultimoGuion;
                                        if($mysqli->query($primariLsql) === true){

                                        }else{
                                            echo "Error Insertanndo el principal => ".$mysqli->error;
                                        }
                                    }

                                    if($i == 1){
                                        $secundariLsql = "UPDATE ".$BaseDatos_systema.".G5 SET G5_C59 = ".$lasrt." WHERE G5_ConsInte__b = ".$ultimoGuion;
                                        if($mysqli->query($secundariLsql) === true){

                                        }else{
                                            echo "Error Insertanndo el principal => ".$mysqli->error;
                                        }
                                    }

                                }

                                $i++;
                            }
                            
                        }
                        crearSecciones($ultimoGuion, $nombreScript, $id_Guion,1);

                    }else{
                        echo " creando el Script ". $mysqli->error;
                    }
                }
                

                
                $Lsql_ValidaCampan = "SELECT G10_ConsInte__b FROM ".$BaseDatos_systema.".G10 JOIN ".$BaseDatos_systema.".ESTPAS ON ESTPAS_ConsInte__CAMPAN_b = G10_ConsInte__b WHERE ESTPAS_ConsInte__b =".$_POST['id_paso'];
                
                $res_ValidaCampan = $mysqli->query($Lsql_ValidaCampan);
                
                if($res_ValidaCampan->num_rows < 1){
                    //No existe Y pues toca crearla de una para que se pueda editar todo
                    //echo "Si llega aqui y esta es la poblacion => ".$_GET['poblacion'];
                    $id_Muestras = 0;
                    //Base de datos G10_C74;
                    $Lsql = "INSERT INTO ".$BaseDatos_systema.".MUESTR (MUESTR_Nombre____b, MUESTR_ConsInte__GUION__b) VALUES ('".$id_Guion."_MUESTRA_".rand()."', '".$id_Guion."')";
                    if($mysqli->query($Lsql) === true){
                        $id_Muestras = $mysqli->insert_id;
                        //echo "Entra aqui tambien y este es el id de la muestra".$id_Muestras;

                        $CreateMuestraLsql = "CREATE TABLE `".$BaseDatos."`.`G".$id_Guion."_M".$id_Muestras."` (
                                                  `G".$id_Guion."_M".$id_Muestras."_CoInMiPo__b` int(10) NOT NULL,
                                                  `G".$id_Guion."_M".$id_Muestras."_ConIntUsu_b` int(10) DEFAULT NULL,
                                                  `G".$id_Guion."_M".$id_Muestras."_Activo____b` smallint(5) DEFAULT '-1',
                                                  `G".$id_Guion."_M".$id_Muestras."_FecHorMinProGes__b` datetime DEFAULT NULL,
                                                  `G".$id_Guion."_M".$id_Muestras."_FechaCreacion_b` datetime DEFAULT NOW(),
                                                  `G".$id_Guion."_M".$id_Muestras."_FechaAsignacion_b` datetime DEFAULT NULL,
                                                  `G".$id_Guion."_M".$id_Muestras."_FechaReactivacion_b` datetime DEFAULT NOW(),
                                                  

                                                  `G".$id_Guion."_M".$id_Muestras."_UltiGest__b` int(10) DEFAULT NULL,
                                                  `G".$id_Guion."_M".$id_Muestras."_GesMasImp_b` int(10) DEFAULT NULL,
                                                  `G".$id_Guion."_M".$id_Muestras."_FecUltGes_b` datetime DEFAULT NULL,
                                                  `G".$id_Guion."_M".$id_Muestras."_FeGeMaIm__b` datetime DEFAULT NULL,
                                                  `G".$id_Guion."_M".$id_Muestras."_Estado____b` int(10) DEFAULT '0',
                                                  `G".$id_Guion."_M".$id_Muestras."_TipoReintentoGMI_b` smallint(5) DEFAULT '0',
                                                  `G".$id_Guion."_M".$id_Muestras."_FecHorAge_b` datetime DEFAULT NULL,
                                                  `G".$id_Guion."_M".$id_Muestras."_FecHorAgeGMI_b` datetime DEFAULT NULL,
                                                  `G".$id_Guion."_M".$id_Muestras."_ConUltGes_b` int(10) DEFAULT NULL,
                                                  `G".$id_Guion."_M".$id_Muestras."_CoGesMaIm_b` int(10) DEFAULT NULL,
                                                  `G".$id_Guion."_M".$id_Muestras."_EstadoUG_b`  int(10) DEFAULT NULL,
                                                  `G".$id_Guion."_M".$id_Muestras."_EstadoGMI_b` int(10) DEFAULT NULL,
                                                  `G".$id_Guion."_M".$id_Muestras."_UsuarioUG_b` int(10) DEFAULT NULL,
                                                  `G".$id_Guion."_M".$id_Muestras."_UsuarioGMI_b` int(10) DEFAULT NULL,
                                                  `G".$id_Guion."_M".$id_Muestras."_CanalUG_b` varchar(20) DEFAULT NULL,
                                                  `G".$id_Guion."_M".$id_Muestras."_CanalGMI_b` varchar(20) DEFAULT NULL,
                                                  `G".$id_Guion."_M".$id_Muestras."_SentidoUG_b` varchar(10) DEFAULT NULL,
                                                  `G".$id_Guion."_M".$id_Muestras."_SentidoGMI_b` varchar(10) DEFAULT NULL,
                                                  `G".$id_Guion."_M".$id_Muestras."_NumeInte__b` int(10) DEFAULT '0',
                                                  `G".$id_Guion."_M".$id_Muestras."_CantidadIntentosGMI_b` int(10) DEFAULT '0',
                                                  `G".$id_Guion."_M".$id_Muestras."_Comentari_b` longtext DEFAULT NULL,
                                                  `G".$id_Guion."_M".$id_Muestras."_ComentarioGMI_b` longtext DEFAULT NULL,
                                                  `G".$id_Guion."_M".$id_Muestras."_LinkContenidoUG_b` varchar(500) DEFAULT NULL,
                                                  `G".$id_Guion."_M".$id_Muestras."_LinkContenidoGMI_b` varchar(500) DEFAULT NULL,
                                                  `G".$id_Guion."_M".$id_Muestras."_DetalleCanalUG_b` varchar(255) DEFAULT NULL,
                                                  `G".$id_Guion."_M".$id_Muestras."_DetalleCanalGMI_b` varchar(255) DEFAULT NULL,
                                                  `G".$id_Guion."_M".$id_Muestras."_DatoContactoUG_b` varchar(255) DEFAULT NULL,
                                                  `G".$id_Guion."_M".$id_Muestras."_DatoContactoGMI_b` varchar(255) DEFAULT NULL,

                                                  
                                                  `G".$id_Guion."_M".$id_Muestras."_TienGest__b` varchar(253) DEFAULT NULL,
                                                  `G".$id_Guion."_M".$id_Muestras."_MailEnvi__b` smallint(5) DEFAULT NULL, 
                                                  `G".$id_Guion."_M".$id_Muestras."_GruRegRel_b` int(10) DEFAULT NULL, 
                                                  `G".$id_Guion."_M".$id_Muestras."_EfeUltGes_b` smallint(5) DEFAULT NULL,
                                                  `G".$id_Guion."_M".$id_Muestras."_EfGeMaIm__b` smallint(5) DEFAULT NULL,                                         
                                                  

                                                  PRIMARY KEY (`G".$id_Guion."_M".$id_Muestras."_CoInMiPo__b`),
                                                  KEY `G".$id_Guion."_M".$id_Muestras."_Estado____b_Indice` (`G".$id_Guion."_M".$id_Muestras."_Estado____b`),
                                                  KEY `G".$id_Guion."_M".$id_Muestras."_ConIntUsu_b_Indice` (`G".$id_Guion."_M".$id_Muestras."_ConIntUsu_b`)
                                                ) ENGINE=InnoDB DEFAULT CHARSET=latin1;";
                        if($mysqli->query($CreateMuestraLsql) === true){
                            //echo "Si creo la tabla";
                        }else{
                            echo $mysqli->error;
                        }

                        $str_Lsql  = '';
                        $validar = 0;

                        $str_LsqlI = "INSERT INTO ".$BaseDatos_systema.".G10(G10_C73, G10_C74, G10_C72 , G10_C75, G10_C78, G10_C330, G10_C80, G10_C92 , G10_C93, G10_C94, G10_C99, G10_C109, G10_C110, G10_C112, G10_C113, G10_C115, G10_C116, G10_C118, G10_C119, G10_C121, G10_C122, G10_C76, G10_C77, G10_C108, G10_C111, G10_C114, G10_C117, G10_C120, G10_C71, G10_C126, G10_C127, G10_C128, G10_C129, G10_C130, G10_C131 , G10_C123, G10_C124, G10_C125, G10_C326, G10_C327)";
                        $str_LsqlV = " VALUES ('".$id_scrip."', '".$id_Guion."', -1, ".$id_Muestras.", -1 , -1, 1, 0, 10, 30, 0, '".date('Y-m-d')." 08:00:00', '".date('Y-m-d')." 18:00:00', '".date('Y-m-d')." 08:00:00', '".date('Y-m-d')." 18:00:00', '".date('Y-m-d')." 08:00:00', '".date('Y-m-d')." 18:00:00', '".date('Y-m-d')." 08:00:00', '".date('Y-m-d')." 18:00:00', '".date('Y-m-d')." 08:00:00', '".date('Y-m-d')." 18:00:00', 1, -1 ,  -1, -1, -1, -1, -1, '".$_POST['G10_C71']."', 0, '".date('Y-m-d')." 08:00:00', '".date('Y-m-d')." 18:00:00', 0, '".date('Y-m-d')." 08:00:00', '".date('Y-m-d')." 18:00:00', 0, '".date('Y-m-d')." 08:00:00', '".date('Y-m-d')." 18:00:00', '25', 0)"; 
                        $Lsql_Insertar = $str_LsqlI.$str_LsqlV;
                        //echo $Lsql_Insertar;
                        if ($mysqli->query($Lsql_Insertar) === TRUE) {
                            //echo "Creo la campaña";

                            $id_usuario = $mysqli->insert_id;

                            $Bdtraducir = $id_Guion;

                            $Script = $id_scrip;

                            //Ordenamiento
                            $Lsql_CAMCOR = "INSERT INTO ".$BaseDatos_systema.".CAMORD (CAMORD_MUESPOBL__B, CAMORD_POBLCAMP__B, CAMORD_MUESCAMP__B, CAMORD_PRIORIDAD_B, CAMORD_ORDEN_____B, CAMORD_CONSINTE__CAMPAN_B) VALUES ('M', NULL, '_Estado____b', '1', 'ASC', ".$id_usuario."), ('M', NULL, '_FecUltGes_b ', '2', 'ASC', ".$id_usuario.");";
                            if($mysqli->query($Lsql_CAMCOR) === true){
                                //echo "Creo el Id_paso con la campaña";
                            }else{
                                echo "ERROR CAMORD". $mysqli->error;
                            }

                            //Este es la union de Paso y campaña
                            $Lsql = "UPDATE ".$BaseDatos_systema.".ESTPAS SET ESTPAS_ConsInte__CAMPAN_b = ".$id_usuario." WHERE ESTPAS_ConsInte__b = ".$_POST['id_paso'];

                            if($mysqli->query($Lsql) === true){
                                //echo "Creo el Id_paso con la campaña";
                            }else{
                                echo "ESTPAS ERROR ".$mysqli->error;
                            }

                            $data = array(  
                                            "strUsuario_t"          =>  'local',
                                            "strToken_t"            =>  'local',
                                            "intIdESTPAS_t"         =>  $_POST['id_paso']
                                        );                                                             
                            $data_string = json_encode($data);   
                            //echo $data_string; 
                            $ch = curl_init($Api_Gestion.'dyalogocore/api/campanas/voip/persistir');
                            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");                                                                     
                            curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string); 
                            curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);
                            curl_setopt($ch, CURLOPT_HTTPHEADER, array(                                                                          
                                'Content-Type: application/json',                                                                                
                                'Content-Length: ' . strlen($data_string))                                                                      
                            ); 
                            $respuesta = curl_exec ($ch);
                            $error = curl_error($ch);
                            curl_close ($ch);
                            //echo " Respuesta => ".$respuesta;
                            //echo " Error => ".$error;
                            $dyCampanId = "NULL";
                            if(!empty($respuesta) && !is_null($respuesta)){
                                $json = json_decode($respuesta);

                                $dyCampanId = $json->objSerializar_t;

                                if($json->strEstado_t == "ok"){
                                    //en caso de que sea extoso 
                                    $actualizarMaxChat = "UPDATE ".$BaseDatos_telefonia.".dy_campanas SET max_chats = 3 WHERE id = ".$dyCampanId;
                                    if($mysqli->query($actualizarMaxChat) === true){
                                        // Actualizo los chats a 3
                                    }
                                    
                                    $UpdateSqlCampanCBX = "UPDATE ".$BaseDatos_systema.".CAMPAN SET CAMPAN_IdCamCbx__b = ".$dyCampanId." WHERE CAMPAN_ConsInte__b = ".$id_usuario;
                                    if($mysqli->query($UpdateSqlCampanCBX) === true){
                                        //si actualizo esta jugada bienvenido sea
                                    }

                                    $ESTPAS_CampanACD_b = "UPDATE ".$BaseDatos_systema.".ESTPAS SET ESTPAS_CampanACD_b = ".$dyCampanId." WHERE ESTPAS_ConsInte__b = ".$_POST['id_paso'];

                                    if ($mysqli->query($ESTPAS_CampanACD_b) === true) {
                                    
                                    } 
                                }
                                  
                            }


                            /* Manipular los datos de caminc */

                            if(isset($datosPoblacion)){
                                for ($i=0; $i < count($datosPoblacion); $i++) { 
                                    $strTextoPo_t=substr($datosPoblacion[$i]['CAMINC_TexPrePob_b'], 0,250);
                                    $strTextoGion_t=substr($datosScript[$i]['CAMINC_TexPreGui_b'], 0,250);
                                    $CamincLsql = "INSERT INTO ".$BaseDatos_systema.".CAMINC(CAMINC_ConsInte__CAMPO_Pob_b,CAMINC_NomCamPob_b , CAMINC_TexPrePob_b,CAMINC_ConsInte__CAMPO_Gui_b,CAMINC_NomCamGui_b , CAMINC_TexPreGui_b, CAMINC_ConsInte__CAMPAN_b, CAMINC_ConsInte__GUION__Pob_b, CAMINC_ConsInte__GUION__Gui_b, CAMINC_ConsInte__MUESTR_b ) VALUES ( '".$datosPoblacion[$i]['CAMINC_ConsInte__CAMPO_Pob_b']."' , '".$datosPoblacion[$i]['CAMINC_NomCamPob_b']."' , '".$strTextoPo_t."' , '".$datosScript[$i]['CAMINC_ConsInte__CAMPO_Gui_b']."' , '".$datosScript[$i]['CAMINC_NomCamGui_b']."' , '".$strTextoGion_t."' , ".$id_usuario." , '".$datosPoblacion[$i]['CAMINC_ConsInte__GUION__Pob_b']."' , '".$datosScript[$i]['CAMINC_ConsInte__GUION__Gui_b']."' , ".$id_Muestras.")";

                                    if($mysqli->query($CamincLsql) === true){

                                    }else{
                                        echo "ERROR CAMINC".$mysqli->error;
                                    }
                                }    
                            }
                            

                            /* Manipular los datos de CAMCOM */
                            //var_dump($dtaosTelefonicos);
                            $datosTelefonicosLsql = "SELECT PREGUN_ConsInte__b ,PREGUN_Texto_____b, CAMPO__ConsInte__b  FROM ".$BaseDatos_systema.".PREGUN JOIN ".$BaseDatos_systema.".CAMPO_ ON CAMPO__ConsInte__PREGUN_b = PREGUN_ConsInte__b  WHERE  PREGUN_ConsInte__GUION__b = ".$Bdtraducir." AND (PREGUN_Texto_____b like '%celular%' or PREGUN_Texto_____b like '%telefono%' or PREGUN_Texto_____b like '%teléfono%' or PREGUN_Texto_____b = 'cel' or PREGUN_Texto_____b = 'tel') AND (PREGUN_Tipo______b = 1 OR PREGUN_Tipo______b = 2 OR PREGUN_Tipo______b = 3 OR PREGUN_Tipo______b = 4 );";

                            $res_Tel = $mysqli->query($datosTelefonicosLsql);
                            $i = 0;
                            while($key = $res_Tel->fetch_object()){

                                //ar_dump($dtaosTelefonicos[$i]);
                                $LsqCamcom = "INSERT INTO ".$BaseDatos_systema.".CAMCON (CAMCON_ConsInte__CAMPAN_b , CAMCON_ConsInte__GUION__Gui_b, CAMCON_ConsInte__GUION__Pob_b, CAMCON_ConsInte__MUESTR_b, CAMCON_Nombre____b, CAMCON_Orden_____b, CAMCON_ConsInte__PREGUN_b, CAMCON_TextPreg__b, CAMCON_ConsInte__CAMPO__Pob_b) VALUES (".$id_usuario." , ".$Script." , ".$Bdtraducir." , ".$id_Muestras." , 'G".$Bdtraducir."_C".$key->PREGUN_ConsInte__b."' , ".($i+1).", ".$key->PREGUN_ConsInte__b." , '".$key->PREGUN_Texto_____b."' ,".$key->CAMPO__ConsInte__b.");";

                                if($mysqli->query($LsqCamcom) === true){

                                }else{
                                    echo "ERROR CAMCOM".$mysqli->error;
                                }

                                $i++;
                            }
                            

                            /* aqui toca cargar la base de datos */
                            if(isset($_POST['insertarDataBase']) && !empty($_FILES['newGuionFile']['tmp_name'])){
                                /* insertar la base de datos */
                                $name   = $_FILES['newGuionFile']['name'];
                                $tname  = $_FILES['newGuionFile']['tmp_name'];
                                ini_set('memory_limit','128M');


                                

                                if($_FILES['newGuionFile']["type"] == 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'){
                                    $objReader = new PHPExcel_Reader_Excel2007();
                                    $objReader->setReadDataOnly(true);
                                    $obj_excel = $objReader->load($tname);
                                }else if($_FILES['newGuionFile']["type"] == 'application/vnd.ms-excel'){
                                    $obj_excel = PHPExcel_IOFactory::load($tname);
                                }
                            
                                $sheetData = $obj_excel->getActiveSheet()->toArray(null,true,true,true);
                                $arr_datos = array();
                                $highestColumm = $obj_excel->setActiveSheetIndex(0)->getHighestColumn(); 
                                $highestRow = $obj_excel->setActiveSheetIndex(0)->getHighestRow();

                                

                                foreach ($sheetData as $index => $value) {
                                    if ( $index > 1 ){
                                        if((!is_null($value['A']) OR !empty($value['A'])) && 
                                            (!is_null($value['B']) OR !empty($value['B']))
                                        ){

                                            $Lsql_InsertarBase = "INSERT INTO ".$BaseDatos.".G".$Bdtraducir."(G".$Bdtraducir."_FechaInsercion";
                                            $Lsql_ValuesssBase = " VALUES ('".date('Y-m-d H:s:i')."'";

                                            for($i=0; $i < count($datosColumnasInsertar); $i++){
                                                $Lsql_InsertarBase .= " , ".$datosColumnasInsertar[$i]['campo'];
                                                $Lsql_ValuesssBase .= " , '".$value[$datosColumnasInsertar[$i]['column']]."'";
                                            }

                                            $Lsql_Insercion = $Lsql_InsertarBase.")".$Lsql_ValuesssBase.")";
                                            if($mysqli->query($Lsql_Insercion) === true){
                                                /* ahora enseguida a la muestra */
                                                $ultimoResgistroInsertado = $mysqli->insert_id;
                                                $muestraCompleta = "G".$Bdtraducir."_M".$id_Muestras;
                                                $insertarMuestraLsql = "INSERT INTO  ".$BaseDatos.".".$muestraCompleta." (".$muestraCompleta."_CoInMiPo__b ,  ".$muestraCompleta."_NumeInte__b, ".$muestraCompleta."_Estado____b) VALUES (".$ultimoResgistroInsertado.", 0 , 0);";
                                                $mysqli->query($insertarMuestraLsql);                                                  
                                            }else{
                                                echo "Error Insertando Los Datos en la base ".$mysqli->error;
                                            }           
                                        }
                                    }
                                }
                            }else{
                                
                                // DESABILITO ESTO COMO EN ENTRANTES NO SE DEBE LLENAR LA MUESTRA
                                // // Valido si la estrategia es tipo 3 para no ejecutar esto, pero tengo que traer la estrategia
                                // $sqlEstrategia = "SELECT ESTRAT_ConsInte__b AS id, ESTRAT_ConsInte__TIPO_ESTRAT_b AS tipo, ESTRAT_Nombre____b AS nombre FROM ".$BaseDatos_systema.".ESTRAT JOIN ".$BaseDatos_systema.".ESTPAS ON ESTRAT_ConsInte__b = ESTPAS_ConsInte__ESTRAT_b WHERE ESTPAS_ConsInte__b = ".$_POST['id_paso']." LIMIT 1";
                                // $resEstrategia = $mysqli->query($sqlEstrategia);
                                // $estrategia = $resEstrategia->fetch_object();

                                // if($estrategia->tipo == 1){

                                //     // Si la estrategia es tipo 2 campaña entrante simple o tipo 1 campaña saliente simple ejecute esto

                                //     $SelectLsql = "SELECT G" . $id_Guion . "_ConsInte__b as id FROM " . $BaseDatos . ".G" . $id_Guion . ";";
                                //     //echo $SelectLsql;
                                //     $ros = $mysqli->query($SelectLsql);
                                //     if ($ros) {
                                //         //echo "Aqui";
                                //         while ($key = $ros->fetch_object()) {
                                //             $ultimoResgistroInsertado = $key->id;
                                //             $muestraCompleta = "G" . $id_Guion . "_M" . $id_Muestras;
                                //             $insertarMuestraLsql = "INSERT INTO  " . $BaseDatos . "." . $muestraCompleta . " (" . $muestraCompleta . "_CoInMiPo__b ,  " . $muestraCompleta . "_NumeInte__b, " . $muestraCompleta . "_Estado____b) VALUES (" . $ultimoResgistroInsertado . ", 0 , 0);";
                                //             $mysqli->query($insertarMuestraLsql);
                                //         }
                                //     }

                                // }
                            }


                            /* le asignamos la base de datos a la estartegia */
                            $PasoLsql = "SELECT ESTPAS_ConsInte__ESTRAT_b FROM ".$BaseDatos_systema.".ESTPAS WHERE ESTPAS_ConsInte__b = ".$_POST['id_paso'];
                            $resPAso = $mysqli->query($PasoLsql);
                            $Estrat = $resPAso->fetch_array();

                            $EstratLsql = "UPDATE ".$BaseDatos_systema.".ESTRAT SET ESTRAT_ConsInte_GUION_Pob = ".$Bdtraducir." WHERE ESTRAT_ConsInte__b = ".$Estrat['ESTPAS_ConsInte__ESTRAT_b'];
                            if($mysqli->query($EstratLsql) === true){

                            }else{
                                echo "error Estrat => ".$mysqli->error;
                            }

                            $UpdatePass= "UPDATE ".$BaseDatos_systema.".ESTPAS SET ESTPAS_Comentari_b = '".$_POST['G10_C71']."', ESTPAS_activo = -1 WHERE ESTPAS_ConsInte__b = ".$_POST['id_paso'];
                            if($mysqli->query($UpdatePass) === true){

                            }else{
                                echo "error Estpas => ".$mysqli->error;
                            }

                            //JDBD - se crean las vistas de las estrategias del huesped en MYSQL 
                            generarVistasPorHuesped($_SESSION['HUESPED']);
                            $intIdEstrat_t = $Estrat['ESTPAS_ConsInte__ESTRAT_b'];
                            $strNomEstrat_t = datosEstrat($intIdEstrat_t);
                            $strNombreReport_t = $_SESSION['PROYECTO'];
                            $strRutaArchivo_t = "/tmp/".$strNombreReport_t."_".$strNomEstrat_t;
                            $strRutaArchivo_t = str_replace(' ', '_', $strRutaArchivo_t);
                            $strRutaArchivo_t = quitarTildes($strRutaArchivo_t);
                            
                            //JDBD - se crea reporte diario
                            if (verificarReporteExistente("NORMAL",1,$_SESSION['HUESPED'],$intIdEstrat_t)) {

                                insertarNuevoReporte($strNomEstrat_t."_DIARIO",$strRutaArchivo_t."_DIARIO",$_SESSION['CORREO'],'20:00','','1',$intIdEstrat_t,$_SESSION['HUESPED'],false);
                                
                            }else{

                                insertarNuevoReporte($strNomEstrat_t."_DIARIO",$strRutaArchivo_t."_DIARIO",$_SESSION['CORREO'],'20:00','','1',$intIdEstrat_t,$_SESSION['HUESPED'],true);

                            }

                            //JDBD - se crea reporte semanal
                            if (verificarReporteExistente("NORMAL",2,$_SESSION['HUESPED'],$intIdEstrat_t)) {

                                insertarNuevoReporte($strNomEstrat_t."_SEMANAL",$strRutaArchivo_t."_SEMANAL",$_SESSION['CORREO'],'07:00','','2',$intIdEstrat_t,$_SESSION['HUESPED'],false);

                            }else{

                                insertarNuevoReporte($strNomEstrat_t."_SEMANAL",$strRutaArchivo_t."_SEMANAL",$_SESSION['CORREO'],'07:00','','2',$intIdEstrat_t,$_SESSION['HUESPED'],true);

                            }

                            //JDBD - se crea reporte mensual
                            if (verificarReporteExistente("NORMAL",3,$_SESSION['HUESPED'],$intIdEstrat_t)) {

                                insertarNuevoReporte($strNomEstrat_t."_MENSUAL",$strRutaArchivo_t."_MENSUAL",$_SESSION['CORREO'],'07:00','','3',$intIdEstrat_t,$_SESSION['HUESPED'],false);

                            }else{

                                insertarNuevoReporte($strNomEstrat_t."_MENSUAL",$strRutaArchivo_t."_MENSUAL",$_SESSION['CORREO'],'07:00','','3',$intIdEstrat_t,$_SESSION['HUESPED'],true);

                            }
                            
                            //JDBD - se crea reporte adherencia
                            if (verificarReporteExistente("ADHERENCIA",1,$_SESSION['HUESPED'],$intIdEstrat_t)) {

                                ctrCrearReportesDirarioAdherencia($intIdEstrat_t,$_SESSION['HUESPED'], $_SESSION['CORREO'],null,null,$strNombreReport_t."_ADHERENCIA",'20:00',false);

                            }else{

                                ctrCrearReportesDirarioAdherencia($intIdEstrat_t,$_SESSION['HUESPED'], $_SESSION['CORREO'],null,null,$strNombreReport_t."_ADHERENCIA",'20:00',true);

                            }

                            //JDBD - definicion de metas.
                        
                            $arrPasos_t = obtenerPasos($intIdEstrat_t);

                            if (count($arrPasos_t) > 0) {
                                foreach ($arrPasos_t as $key => $bola) {

                                    switch ($bola["ESTPAS_Tipo______b"]) {
                                        case 1:
                                            //JDBD - iniciamos la insercion de las metas entrantes
                                            insertarMetas($intIdEstrat_t,$bola["ESTPAS_ConsInte__b"],1);
                                            break;
                                        case 6:
                                            //JDBD - iniciamos la insercion de las metas salientes
                                            insertarMetas($intIdEstrat_t,$bola["ESTPAS_ConsInte__b"],6);
                                            break;
                                    }

                                } 
                            }

                            echo true;
                        }else{
                            echo "ERROR CAMPANHA => ".$mysqli->error;
                        }
                    }else{
                        echo "CREANDO LA MUESTRA => ".$mysqli->error;
                    }
                
                }else{
                    //echo "aja";
                }
            }
            


            $str_Lsql  = '';

            $validar = 0;
            $str_LsqlU = "UPDATE ".$BaseDatos_systema.".G10 SET "; 
            $str_LsqlI = "INSERT INTO ".$BaseDatos_systema.".G10(";
            $str_LsqlV = " VALUES ("; 
  
            $G10_C70 = NULL;
            //este es de tipo numero no se deja ir asi '', si est avacio lo mejor es no mandarlo
            if(isset($_POST["G10_C70"])){
                if($_POST["G10_C70"] != ''){
                    $separador = "";
                    if($validar == 1){
                        $separador = ",";
                    }

                    //$G10_C70 = $_POST["G10_C70"];
                    $G10_C70 = str_replace(".", "", $_POST["G10_C70"]);
                    $G10_C70 =  str_replace(",", ".", $G10_C70);
                    $str_LsqlU .= $separador." G10_C70 = '".$G10_C70."'";
                    $str_LsqlI .= $separador." G10_C70";
                    $str_LsqlV .= $separador."'".$G10_C70."'";
                    $validar = 1;
                }
            }
  
            if(isset($_POST["G10_C71"])){
                $separador = "";
                if($validar == 1){
                    $separador = ",";
                }

                $str_LsqlU .= $separador."G10_C71 = '".$_POST["G10_C71"]."'";
                $str_LsqlI .= $separador."G10_C71";
                $str_LsqlV .= $separador."'".$_POST["G10_C71"]."'";
                $validar = 1;
            }
             
  
            $G10_C72 = 0;
            //este es tipo check primeo si viene y de acuerdo a su valor se pone 1 o 0
            if(isset($_POST["G10_C72"])){
                if($_POST["G10_C72"] == 'Yes'){
                    $G10_C72 = 1;
                }else if($_POST["G10_C72"] == 'off'){
                    $G10_C72 = 0;
                }else if($_POST["G10_C72"] == 'on'){
                    $G10_C72 = 1;
                }else if($_POST["G10_C72"] == 'No'){
                    $G10_C72 = 0;
                }else{
                    $G10_C72 = $_POST["G10_C72"] ;
                }   

                $separador = "";
                if($validar == 1){
                    $separador = ",";
                }

                $str_LsqlU .= $separador." G10_C72 = ".$G10_C72."";
                $str_LsqlI .= $separador." G10_C72";
                $str_LsqlV .= $separador.$G10_C72;

                $validar = 1;
            }else{
                $separador = "";
                if($validar == 1){
                    $separador = ",";
                }

                $str_LsqlU .= $separador." G10_C72 = ".$G10_C72."";
                $str_LsqlI .= $separador." G10_C72";
                $str_LsqlV .= $separador.$G10_C72;

                $validar = 1;
            }
  
            if(isset($_POST["G10_C73"])){
                $separador = "";
                if($validar == 1){
                    $separador = ",";
                }

                $str_LsqlU .= $separador."G10_C73 = '".$_POST["G10_C73"]."'";
                $str_LsqlI .= $separador."G10_C73";
                $str_LsqlV .= $separador."'".$_POST["G10_C73"]."'";
                $validar = 1;
            }
             
  
            if(isset($_POST["G10_C74"])){
                $separador = "";
                if($validar == 1){
                    $separador = ",";
                }

                $str_LsqlU .= $separador."G10_C74 = '".$_POST["G10_C74"]."'";
                $str_LsqlI .= $separador."G10_C74";
                $str_LsqlV .= $separador."'".$_POST["G10_C74"]."'";
                $validar = 1;
            }
             
  
            $G10_C75 = NULL;
            //este es de tipo numero no se deja ir asi '', si est avacio lo mejor es no mandarlo
            if(isset($_POST["G10_C75"])){
                if($_POST["G10_C75"] != ''){
                    $separador = "";
                    if($validar == 1){
                        $separador = ",";
                    }

                    //$G10_C75 = $_POST["G10_C75"];
                    $G10_C75 = str_replace(".", "", $_POST["G10_C75"]);
                    $G10_C75 =  str_replace(",", ".", $G10_C75);
                    $str_LsqlU .= $separador." G10_C75 = '".$G10_C75."'";
                    $str_LsqlI .= $separador." G10_C75";
                    $str_LsqlV .= $separador."'".$G10_C75."'";
                    $validar = 1;
                }
            }
  
            if(isset($_POST["G10_C76"])){
                $separador = "";
                if($validar == 1){
                    $separador = ",";
                }

                $str_LsqlU .= $separador."G10_C76 = '".$_POST["G10_C76"]."'";
                $str_LsqlI .= $separador."G10_C76";
                $str_LsqlV .= $separador."'".$_POST["G10_C76"]."'";
                $validar = 1;
            }
             
  
            if(isset($_POST["G10_C77"])){
                $separador = "";
                if($validar == 1){
                    $separador = ",";
                }

                $str_LsqlU .= $separador."G10_C77 = '".$_POST["G10_C77"]."'";
                $str_LsqlI .= $separador."G10_C77";
                $str_LsqlV .= $separador."'".$_POST["G10_C77"]."'";
                $validar = 1;
            }
             
  
            $G10_C105 = NULL;
            //este es de tipo numero no se deja ir asi '', si est avacio lo mejor es no mandarlo
            if(isset($_POST["G10_C105"])){
                if($_POST["G10_C105"] != ''){
                    $separador = "";
                    if($validar == 1){
                        $separador = ",";
                    }

                    //$G10_C105 = $_POST["G10_C105"];
                    $G10_C105 = str_replace(".", "", $_POST["G10_C105"]);
                    $G10_C105 =  str_replace(",", ".", $G10_C105);
                    $str_LsqlU .= $separador." G10_C105 = '".$G10_C105."'";
                    $str_LsqlI .= $separador." G10_C105";
                    $str_LsqlV .= $separador."'".$G10_C105."'";
                    $validar = 1;
                }
            }
  
            $G10_C106 = NULL;
            //este es de tipo numero no se deja ir asi '', si est avacio lo mejor es no mandarlo
            if(isset($_POST["G10_C106"])){
                if($_POST["G10_C106"] != ''){
                    $separador = "";
                    if($validar == 1){
                        $separador = ",";
                    }

                    //$G10_C106 = $_POST["G10_C106"];
                    $G10_C106 = str_replace(".", "", $_POST["G10_C106"]);
                    $G10_C106 =  str_replace(",", ".", $G10_C106);
                    $str_LsqlU .= $separador." G10_C106 = '".$G10_C106."'";
                    $str_LsqlI .= $separador." G10_C106";
                    $str_LsqlV .= $separador."'".$G10_C106."'";
                    $validar = 1;
                }
            }
  
            $G10_C107 = NULL;
            //este es de tipo numero no se deja ir asi '', si est avacio lo mejor es no mandarlo
            if(isset($_POST["G10_C107"])){
                if($_POST["G10_C107"] != ''){
                    $separador = "";
                    if($validar == 1){
                        $separador = ",";
                    }

                    //$G10_C107 = $_POST["G10_C107"];
                    $G10_C107 = str_replace(".", "", $_POST["G10_C107"]);
                    $G10_C107 =  str_replace(",", ".", $G10_C107);
                    $str_LsqlU .= $separador." G10_C107 = '".$G10_C107."'";
                    $str_LsqlI .= $separador." G10_C107";
                    $str_LsqlV .= $separador."'".$G10_C107."'";
                    $validar = 1;
                }
            }

            $G10_C330 = 0;
            //este es tipo check primeo si viene y de acuerdo a su valor se pone 1 o 0
            if(isset($_POST["G10_C330"])){
                if($_POST["G10_C330"] == 'Yes'){
                    $G10_C330 = 1;
                }else if($_POST["G10_C330"] == 'off'){
                    $G10_C330 = 0;
                }else if($_POST["G10_C330"] == 'on'){
                    $G10_C330 = 1;
                }else if($_POST["G10_C330"] == 'No'){
                    $G10_C330 = 0;
                }else{
                    $G10_C330 = $_POST["G10_C330"] ;
                }   

                $separador = "";
                if($validar == 1){
                    $separador = ",";
                }

                $str_LsqlU .= $separador." G10_C330 = ".$G10_C330."";
                $str_LsqlI .= $separador." G10_C330";
                $str_LsqlV .= $separador.$G10_C330;

                $validar = 1;

                if(isset($_POST['ivr_encuesta']) && $_POST['ivr_encuesta']!=0){
                    $context="context='{$_POST['ivr_encuesta']}'";
                }else{
                    $context="context=NULL";
                }
                
                $sql_ivr_espera=$mysqli->query("update dyalogo_telefonia.dy_campanas set {$context} where id_campana_crm={$_POST["id"]}");
                $booEstpas=validarEstpasCallback($G10_C330,$_POST["id"],$_POST['id_estrategia']);
            }else{
                $separador = "";
                if($validar == 1){
                    $separador = ",";
                }

                $str_LsqlU .= $separador." G10_C330 = ".$G10_C330."";
                $str_LsqlI .= $separador." G10_C330";
                $str_LsqlV .= $separador.$G10_C330;

                $validar = 1;

                if(isset($_POST['ivr_encuesta']) && $_POST['ivr_encuesta']!=0){
                    $context="context='{$_POST['ivr_encuesta']}'";
                }else{
                    $context="context=NULL";
                }
                
                $sql_ivr_espera=$mysqli->query("update dyalogo_telefonia.dy_campanas set {$context} where id_campana_crm={$_POST["id"]}");
                $booEstpas=validarEstpasCallback($G10_C330,$_POST["id"],$_POST['id_estrategia']);
            }
  
            $G10_C79 = 0;
            //este es tipo check primeo si viene y de acuerdo a su valor se pone 1 o 0
            if(isset($_POST["G10_C79"])){
                if($_POST["G10_C79"] == 'Yes'){
                    $G10_C79 = 1;
                }else if($_POST["G10_C79"] == 'off'){
                    $G10_C79 = 0;
                }else if($_POST["G10_C79"] == 'on'){
                    $G10_C79 = 1;
                }else if($_POST["G10_C79"] == 'No'){
                    $G10_C79 = 0;
                }else{
                    $G10_C79 = $_POST["G10_C79"] ;
                }   

                $separador = "";
                if($validar == 1){
                    $separador = ",";
                }

                $str_LsqlU .= $separador." G10_C79 = ".$G10_C79."";
                $str_LsqlI .= $separador." G10_C79";
                $str_LsqlV .= $separador.$G10_C79;

                $validar = 1;
            }else{
                $separador = "";
                if($validar == 1){
                    $separador = ",";
                }

                $str_LsqlU .= $separador." G10_C79 = ".$G10_C79."";
                $str_LsqlI .= $separador." G10_C79";
                $str_LsqlV .= $separador.$G10_C79;

                $validar = 1;
            }
  
            $G10_C80 = NULL;
            //este es de tipo numero no se deja ir asi '', si est avacio lo mejor es no mandarlo
            if(isset($_POST["G10_C80"])){
                if($_POST["G10_C80"] != ''){
                    $separador = "";
                    if($validar == 1){
                        $separador = ",";
                    }

                    //$G10_C80 = $_POST["G10_C80"];
                    $G10_C80 = str_replace(".", "", $_POST["G10_C80"]);
                    $G10_C80 =  str_replace(",", ".", $G10_C80);
                    $str_LsqlU .= $separador." G10_C80 = '".$G10_C80."'";
                    $str_LsqlI .= $separador." G10_C80";
                    $str_LsqlV .= $separador."'".$G10_C80."'";
                    $validar = 1;
                }
            }
  
            $G10_C81 = 0;
            //este es tipo check primeo si viene y de acuerdo a su valor se pone 1 o 0
            if(isset($_POST["G10_C81"])){
                if($_POST["G10_C81"] == 'Yes'){
                    $G10_C81 = 1;
                }else if($_POST["G10_C81"] == 'off'){
                    $G10_C81 = 0;
                }else if($_POST["G10_C81"] == 'on'){
                    $G10_C81 = 1;
                }else if($_POST["G10_C81"] == 'No'){
                    $G10_C81 = 0;
                }else{
                    $G10_C81 = $_POST["G10_C81"] ;
                }   

                $separador = "";
                if($validar == 1){
                    $separador = ",";
                }

                $str_LsqlU .= $separador." G10_C81 = ".$G10_C81."";
                $str_LsqlI .= $separador." G10_C81";
                $str_LsqlV .= $separador.$G10_C81;

                $validar = 1;
            }else{
                $separador = "";
                if($validar == 1){
                    $separador = ",";
                }

                $str_LsqlU .= $separador." G10_C81 = ".$G10_C81."";
                $str_LsqlI .= $separador." G10_C81";
                $str_LsqlV .= $separador.$G10_C81;

                $validar = 1;
            }
  
            $G10_C82 = NULL;
            //este es de tipo numero no se deja ir asi '', si est avacio lo mejor es no mandarlo
            if(isset($_POST["G10_C82"])){
                if($_POST["G10_C82"] != ''){
                    $separador = "";
                    if($validar == 1){
                        $separador = ",";
                    }

                    //$G10_C82 = $_POST["G10_C82"];
                    $G10_C82 = str_replace(".", "", $_POST["G10_C82"]);
                    $G10_C82 =  str_replace(",", ".", $G10_C82);
                    $str_LsqlU .= $separador." G10_C82 = '".$G10_C82."'";
                    $str_LsqlI .= $separador." G10_C82";
                    $str_LsqlV .= $separador."'".$G10_C82."'";
                    $validar = 1;
                }
            }
  
            $G10_C83 = NULL;
            //este es de tipo numero no se deja ir asi '', si est avacio lo mejor es no mandarlo
            if(isset($_POST["G10_C83"])){
                if($_POST["G10_C83"] != ''){
                    $separador = "";
                    if($validar == 1){
                        $separador = ",";
                    }

                    //$G10_C83 = $_POST["G10_C83"];
                    $G10_C83 = str_replace(".", "", $_POST["G10_C83"]);
                    $G10_C83 =  str_replace(",", ".", $G10_C83);
                    $str_LsqlU .= $separador." G10_C83 = '".$G10_C83."'";
                    $str_LsqlI .= $separador." G10_C83";
                    $str_LsqlV .= $separador."'".$G10_C83."'";
                    $validar = 1;
                }
            }
  
            $G10_C84 = NULL;
            //este es de tipo numero no se deja ir asi '', si est avacio lo mejor es no mandarlo
            if(isset($_POST["G10_C84"])){
                if($_POST["G10_C84"] != ''){
                    $separador = "";
                    if($validar == 1){
                        $separador = ",";
                    }

                    //$G10_C84 = $_POST["G10_C84"];
                    $G10_C84 = str_replace(".", "", $_POST["G10_C84"]);
                    $G10_C84 =  str_replace(",", ".", $G10_C84);
                    $str_LsqlU .= $separador." G10_C84 = '".$G10_C84."'";
                    $str_LsqlI .= $separador." G10_C84";
                    $str_LsqlV .= $separador."'".$G10_C84."'";
                    $validar = 1;
                }
            }
  
            $G10_C85 = 0;
            //este es tipo check primeo si viene y de acuerdo a su valor se pone 1 o 0
            if(isset($_POST["G10_C85"])){
                if($_POST["G10_C85"] == 'Yes'){
                    $G10_C85 = 1;
                }else if($_POST["G10_C85"] == 'off'){
                    $G10_C85 = 0;
                }else if($_POST["G10_C85"] == 'on'){
                    $G10_C85 = 1;
                }else if($_POST["G10_C85"] == 'No'){
                    $G10_C85 = 0;
                }else{
                    $G10_C85 = $_POST["G10_C85"] ;
                }   

                $separador = "";
                if($validar == 1){
                    $separador = ",";
                }

                $str_LsqlU .= $separador." G10_C85 = ".$G10_C85."";
                $str_LsqlI .= $separador." G10_C85";
                $str_LsqlV .= $separador.$G10_C85;

                $validar = 1;
            }else{
                $separador = "";
                if($validar == 1){
                    $separador = ",";
                }

                $str_LsqlU .= $separador." G10_C85 = ".$G10_C85."";
                $str_LsqlI .= $separador." G10_C85";
                $str_LsqlV .= $separador.$G10_C85;

                $validar = 1;
            }
  
            if(isset($_POST["G10_C90"])){
                $separador = "";
                if($validar == 1){
                    $separador = ",";
                }

                $str_LsqlU .= $separador."G10_C90 = '".$_POST["G10_C90"]."'";
                $str_LsqlI .= $separador."G10_C90";
                $str_LsqlV .= $separador."'".$_POST["G10_C90"]."'";
                $validar = 1;
            }
             
  
            if(isset($_POST["G10_C91"])){
                $separador = "";
                if($validar == 1){
                    $separador = ",";
                }

                $str_LsqlU .= $separador."G10_C91 = '".$_POST["G10_C91"]."'";
                $str_LsqlI .= $separador."G10_C91";
                $str_LsqlV .= $separador."'".$_POST["G10_C91"]."'";
                $validar = 1;
            }
             
  
            $G10_C92 = NULL;
            //este es de tipo numero no se deja ir asi '', si est avacio lo mejor es no mandarlo
            if(isset($_POST["G10_C92"])){
                if($_POST["G10_C92"] != ''){
                    $separador = "";
                    if($validar == 1){
                        $separador = ",";
                    }

                    //$G10_C92 = $_POST["G10_C92"];
                    $G10_C92 = str_replace(".", "", $_POST["G10_C92"]);
                    $G10_C92 =  str_replace(",", ".", $G10_C92);
                    $str_LsqlU .= $separador." G10_C92 = '".$G10_C92."'";
                    $str_LsqlI .= $separador." G10_C92";
                    $str_LsqlV .= $separador."'".$G10_C92."'";
                    $validar = 1;
                }
            }
  
            $G10_C93 = NULL;
            //este es de tipo numero no se deja ir asi '', si est avacio lo mejor es no mandarlo
            if(isset($_POST["G10_C93"])){
                if($_POST["G10_C93"] != ''){
                    $separador = "";
                    if($validar == 1){
                        $separador = ",";
                    }

                    //$G10_C93 = $_POST["G10_C93"];
                    $G10_C93 = str_replace(".", "", $_POST["G10_C93"]);
                    $G10_C93 =  str_replace(",", ".", $G10_C93);
                    $str_LsqlU .= $separador." G10_C93 = '".$G10_C93."'";
                    $str_LsqlI .= $separador." G10_C93";
                    $str_LsqlV .= $separador."'".$G10_C93."'";
                    $validar = 1;
                }
            }
  
            $G10_C94 = NULL;
            //este es de tipo numero no se deja ir asi '', si est avacio lo mejor es no mandarlo
            if(isset($_POST["G10_C94"])){
                if($_POST["G10_C94"] != ''){
                    $separador = "";
                    if($validar == 1){
                        $separador = ",";
                    }

                    //$G10_C94 = $_POST["G10_C94"];
                    $G10_C94 = str_replace(".", "", $_POST["G10_C94"]);
                    $G10_C94 =  str_replace(",", ".", $G10_C94);
                    $str_LsqlU .= $separador." G10_C94 = '".$G10_C94."'";
                    $str_LsqlI .= $separador." G10_C94";
                    $str_LsqlV .= $separador."'".$G10_C94."'";
                    $validar = 1;
                }
            }
  
            $G10_C95 = 0;
            //este es tipo check primeo si viene y de acuerdo a su valor se pone 1 o 0
            if(isset($_POST["G10_C95"])){
                if($_POST["G10_C95"] == 'Yes'){
                    $G10_C95 = 1;
                }else if($_POST["G10_C95"] == 'off'){
                    $G10_C95 = 0;
                }else if($_POST["G10_C95"] == 'on'){
                    $G10_C95 = 1;
                }else if($_POST["G10_C95"] == 'No'){
                    $G10_C95 = 0;
                }else{
                    $G10_C95 = $_POST["G10_C95"] ;
                }   

                $separador = "";
                if($validar == 1){
                    $separador = ",";
                }

                $str_LsqlU .= $separador." G10_C95 = ".$G10_C95."";
                $str_LsqlI .= $separador." G10_C95";
                $str_LsqlV .= $separador.$G10_C95;

                $validar = 1;
            }else{
                $separador = "";
                if($validar == 1){
                    $separador = ",";
                }

                $str_LsqlU .= $separador." G10_C95 = ".$G10_C95."";
                $str_LsqlI .= $separador." G10_C95";
                $str_LsqlV .= $separador.$G10_C95;

                $validar = 1;
            }
  
            if(isset($_POST["G10_C98"])){
                $separador = "";
                if($validar == 1){
                    $separador = ",";
                }

                $str_LsqlU .= $separador."G10_C98 = '".$_POST["G10_C98"]."'";
                $str_LsqlI .= $separador."G10_C98";
                $str_LsqlV .= $separador."'".$_POST["G10_C98"]."'";
                $validar = 1;
            }
             
  
            $G10_C99 = NULL;
            //este es de tipo numero no se deja ir asi '', si est avacio lo mejor es no mandarlo
            if(isset($_POST["G10_C99"])){
                if($_POST["G10_C99"] != ''){
                    $separador = "";
                    if($validar == 1){
                        $separador = ",";
                    }

                    //$G10_C99 = $_POST["G10_C99"];
                    $G10_C99 = str_replace(".", "", $_POST["G10_C99"]);
                    $G10_C99 =  str_replace(",", ".", $G10_C99);
                    $str_LsqlU .= $separador." G10_C99 = '".$G10_C99."'";
                    $str_LsqlI .= $separador." G10_C99";
                    $str_LsqlV .= $separador."'".$G10_C99."'";
                    $validar = 1;
                }
            }
  
            $G10_C100 = 0;
            //este es tipo check primeo si viene y de acuerdo a su valor se pone 1 o 0
            if(isset($_POST["G10_C100"])){
                if($_POST["G10_C100"] == 'Yes'){
                    $G10_C100 = 1;
                }else if($_POST["G10_C100"] == 'off'){
                    $G10_C100 = 0;
                }else if($_POST["G10_C100"] == 'on'){
                    $G10_C100 = 1;
                }else if($_POST["G10_C100"] == 'No'){
                    $G10_C100 = 0;
                }else{
                    $G10_C100 = $_POST["G10_C100"] ;
                }   

                $separador = "";
                if($validar == 1){
                    $separador = ",";
                }

                $str_LsqlU .= $separador." G10_C100 = ".$G10_C100."";
                $str_LsqlI .= $separador." G10_C100";
                $str_LsqlV .= $separador.$G10_C100;

                $validar = 1;
            }else{
                $separador = "";
                if($validar == 1){
                    $separador = ",";
                }

                $str_LsqlU .= $separador." G10_C100 = ".$G10_C100."";
                $str_LsqlI .= $separador." G10_C100";
                $str_LsqlV .= $separador.$G10_C100;

                $validar = 1;
            }
  
            if(isset($_POST["G10_C101"])){
                $separador = "";
                if($validar == 1){
                    $separador = ",";
                }

                $str_LsqlU .= $separador."G10_C101 = '".$_POST["G10_C101"]."'";
                $str_LsqlI .= $separador."G10_C101";
                $str_LsqlV .= $separador."'".$_POST["G10_C101"]."'";
                $validar = 1;
            }
             
  
            $G10_C102 = NULL;
            //este es de tipo numero no se deja ir asi '', si est avacio lo mejor es no mandarlo
            if(isset($_POST["G10_C102"])){
                if($_POST["G10_C102"] != ''){
                    $separador = "";
                    if($validar == 1){
                        $separador = ",";
                    }

                    //$G10_C102 = $_POST["G10_C102"];
                    $G10_C102 = str_replace(".", "", $_POST["G10_C102"]);
                    $G10_C102 =  str_replace(",", ".", $G10_C102);
                    $str_LsqlU .= $separador." G10_C102 = '".$G10_C102."'";
                    $str_LsqlI .= $separador." G10_C102";
                    $str_LsqlV .= $separador."'".$G10_C102."'";
                    $validar = 1;
                }
            }
             
  
            $G10_C333 = NULL;
            //este es de tipo numero no se deja ir asi '', si est avacio lo mejor es no mandarlo
            if(isset($_POST["G10_C333"])){
                if($_POST["G10_C333"] != ''){
                    $separador = "";
                    if($validar == 1){
                        $separador = ",";
                    }

                    //$G10_C333 = $_POST["G10_C333"];
                    $G10_C333 = str_replace(".", "", $_POST["G10_C333"]);
                    $G10_C333 =  str_replace(",", ".", $G10_C333);
                    $str_LsqlU .= $separador." G10_C333 = '".$G10_C333."'";
                    $str_LsqlI .= $separador." G10_C333";
                    $str_LsqlV .= $separador."'".$G10_C333."'";
                    $validar = 1;
                }
            }

            if(isset($_POST['G10_C335']) && $_POST['G10_C335']!='0'){
                $separador = "";
                
                if($validar == 1){
                    $separador = ",";
                }
                
                
                if(isset($_POST['G10_C335'])){
                    $str_LsqlU .= $separador."G10_C335 = '".$_POST['G10_C335']."'";
                    $str_LsqlI .= $separador."G10_C335";
                    $str_LsqlV .= $separador."'".$_POST['G10_C335']."'";
                }

                
            }else{

                $separador = "";
                if($validar == 1){
                    $separador = ",";
                }  

                $str_LsqlU .= $separador."G10_C335 = NULL";
                $str_LsqlI .= $separador."G10_C335";
                $str_LsqlV .= $separador."NULL";

            }

            if(isset($_POST['G10_C336']) && $_POST['G10_C336']!='0'){
                $separador = "";
                
                if($validar == 1){
                    $separador = ",";
                }
                
                
                if(isset($_POST['G10_C336'])){
                    $str_LsqlU .= $separador."G10_C336 = '".$_POST['G10_C336']."'";
                    $str_LsqlI .= $separador."G10_C336";
                    $str_LsqlV .= $separador."'".$_POST['G10_C336']."'";
                }

                
            }else{

                $separador = "";
                if($validar == 1){
                    $separador = ",";
                }  

                $str_LsqlU .= $separador."G10_C336 = 0";
                $str_LsqlI .= $separador."G10_C336";
                $str_LsqlV .= $separador."0";

            }
  
            if(isset($_POST["G10_C103"])){
                $separador = "";
                if($validar == 1){
                    $separador = ",";
                }

                $str_LsqlU .= $separador."G10_C103 = '".$_POST["G10_C103"]."'";
                $str_LsqlI .= $separador."G10_C103";
                $str_LsqlV .= $separador."'".$_POST["G10_C103"]."'";
                $validar = 1;
            }
             
  
            if(isset($_POST["G10_C104"])){
                $separador = "";
                if($validar == 1){
                    $separador = ",";
                }

                $str_LsqlU .= $separador."G10_C104 = '".$_POST["G10_C104"]."'";
                $str_LsqlI .= $separador."G10_C104";
                $str_LsqlV .= $separador."'".$_POST["G10_C104"]."'";
                $validar = 1;
            }
             
  
            $G10_C108 = 0;
            //este es tipo check primeo si viene y de acuerdo a su valor se pone 1 o 0
            if(isset($_POST["G10_C108"])){
                if($_POST["G10_C108"] == 'Yes'){
                    $G10_C108 = 1;
                }else if($_POST["G10_C108"] == 'off'){
                    $G10_C108 = 0;
                }else if($_POST["G10_C108"] == 'on'){
                    $G10_C108 = 1;
                }else if($_POST["G10_C108"] == 'No'){
                    $G10_C108 = 0;
                }else{
                    $G10_C108 = $_POST["G10_C108"] ;
                }   

                $separador = "";
                if($validar == 1){
                    $separador = ",";
                }

                $str_LsqlU .= $separador." G10_C108 = ".$G10_C108."";
                $str_LsqlI .= $separador." G10_C108";
                $str_LsqlV .= $separador.$G10_C108;

                $validar = 1;
            }else{
                $separador = "";
                if($validar == 1){
                    $separador = ",";
                }

                $str_LsqlU .= $separador." G10_C108 = ".$G10_C108."";
                $str_LsqlI .= $separador." G10_C108";
                $str_LsqlV .= $separador.$G10_C108;

                $validar = 1;
            }
  
            $G10_C109 = NULL;
            //este es de tipo date hay que preguntar si esta vacia o no o esta undefined
            if(isset($_POST["G10_C109"])){   
                if($_POST["G10_C109"] != '' && $_POST["G10_C109"] != 'undefined' && $_POST["G10_C109"] != 'null'){
                    $separador = "";
                    $fecha = date('Y-m-d');
                    if($validar == 1){
                        $separador = ",";
                    }

                    $G10_C109 = "'".$fecha." ".str_replace(' ', '',$_POST["G10_C109"])."'";
                    $str_LsqlU .= $separador." G10_C109 = ".$G10_C109."";
                    $str_LsqlI .= $separador." G10_C109";
                    $str_LsqlV .= $separador.$G10_C109;
                    $validar = 1;
                }
            }
  
            $G10_C110 = NULL;
            //este es de tipo date hay que preguntar si esta vacia o no o esta undefined
            if(isset($_POST["G10_C110"])){   
                if($_POST["G10_C110"] != '' && $_POST["G10_C110"] != 'undefined' && $_POST["G10_C110"] != 'null'){
                    $separador = "";
                    $fecha = date('Y-m-d');
                    if($validar == 1){
                        $separador = ",";
                    }

                    $G10_C110 = "'".$fecha." ".str_replace(' ', '',$_POST["G10_C110"])."'";
                    $str_LsqlU .= $separador." G10_C110 = ".$G10_C110."";
                    $str_LsqlI .= $separador." G10_C110";
                    $str_LsqlV .= $separador.$G10_C110;
                    $validar = 1;
                }
            }
  
            $G10_C111 = 0;
            //este es tipo check primeo si viene y de acuerdo a su valor se pone 1 o 0
            if(isset($_POST["G10_C111"])){
                if($_POST["G10_C111"] == 'Yes'){
                    $G10_C111 = 1;
                }else if($_POST["G10_C111"] == 'off'){
                    $G10_C111 = 0;
                }else if($_POST["G10_C111"] == 'on'){
                    $G10_C111 = 1;
                }else if($_POST["G10_C111"] == 'No'){
                    $G10_C111 = 0;
                }else{
                    $G10_C111 = $_POST["G10_C111"] ;
                }   

                $separador = "";
                if($validar == 1){
                    $separador = ",";
                }

                $str_LsqlU .= $separador." G10_C111 = ".$G10_C111."";
                $str_LsqlI .= $separador." G10_C111";
                $str_LsqlV .= $separador.$G10_C111;

                $validar = 1;
            }else{
                $separador = "";
                if($validar == 1){
                    $separador = ",";
                }

                $str_LsqlU .= $separador." G10_C111 = ".$G10_C111."";
                $str_LsqlI .= $separador." G10_C111";
                $str_LsqlV .= $separador.$G10_C111;

                $validar = 1;
            }
  
            $G10_C112 = NULL;
            //este es de tipo date hay que preguntar si esta vacia o no o esta undefined
            if(isset($_POST["G10_C112"])){   
                if($_POST["G10_C112"] != '' && $_POST["G10_C112"] != 'undefined' && $_POST["G10_C112"] != 'null'){
                    $separador = "";
                    $fecha = date('Y-m-d');
                    if($validar == 1){
                        $separador = ",";
                    }

                    $G10_C112 = "'".$fecha." ".str_replace(' ', '',$_POST["G10_C112"])."'";
                    $str_LsqlU .= $separador." G10_C112 = ".$G10_C112."";
                    $str_LsqlI .= $separador." G10_C112";
                    $str_LsqlV .= $separador.$G10_C112;
                    $validar = 1;
                }
            }
  
            $G10_C113 = NULL;
            //este es de tipo date hay que preguntar si esta vacia o no o esta undefined
            if(isset($_POST["G10_C113"])){   
                if($_POST["G10_C113"] != '' && $_POST["G10_C113"] != 'undefined' && $_POST["G10_C113"] != 'null'){
                    $separador = "";
                    $fecha = date('Y-m-d');
                    if($validar == 1){
                        $separador = ",";
                    }

                    $G10_C113 = "'".$fecha." ".str_replace(' ', '',$_POST["G10_C113"])."'";
                    $str_LsqlU .= $separador." G10_C113 = ".$G10_C113."";
                    $str_LsqlI .= $separador." G10_C113";
                    $str_LsqlV .= $separador.$G10_C113;
                    $validar = 1;
                }
            }
  
            $G10_C114 = 0;
            //este es tipo check primeo si viene y de acuerdo a su valor se pone 1 o 0
            if(isset($_POST["G10_C114"])){
                if($_POST["G10_C114"] == 'Yes'){
                    $G10_C114 = 1;
                }else if($_POST["G10_C114"] == 'off'){
                    $G10_C114 = 0;
                }else if($_POST["G10_C114"] == 'on'){
                    $G10_C114 = 1;
                }else if($_POST["G10_C114"] == 'No'){
                    $G10_C114 = 0;
                }else{
                    $G10_C114 = $_POST["G10_C114"] ;
                }   

                $separador = "";
                if($validar == 1){
                    $separador = ",";
                }

                $str_LsqlU .= $separador." G10_C114 = ".$G10_C114."";
                $str_LsqlI .= $separador." G10_C114";
                $str_LsqlV .= $separador.$G10_C114;

                $validar = 1;
            }else{
                $separador = "";
                if($validar == 1){
                    $separador = ",";
                }

                $str_LsqlU .= $separador." G10_C114 = ".$G10_C114."";
                $str_LsqlI .= $separador." G10_C114";
                $str_LsqlV .= $separador.$G10_C114;

                $validar = 1;
            }
  
            $G10_C115 = NULL;
            //este es de tipo date hay que preguntar si esta vacia o no o esta undefined
            if(isset($_POST["G10_C115"])){   
                if($_POST["G10_C115"] != '' && $_POST["G10_C115"] != 'undefined' && $_POST["G10_C115"] != 'null'){
                    $separador = "";
                    $fecha = date('Y-m-d');
                    if($validar == 1){
                        $separador = ",";
                    }

                    $G10_C115 = "'".$fecha." ".str_replace(' ', '',$_POST["G10_C115"])."'";
                    $str_LsqlU .= $separador." G10_C115 = ".$G10_C115."";
                    $str_LsqlI .= $separador." G10_C115";
                    $str_LsqlV .= $separador.$G10_C115;
                    $validar = 1;
                }
            }
  
            $G10_C116 = NULL;
            //este es de tipo date hay que preguntar si esta vacia o no o esta undefined
            if(isset($_POST["G10_C116"])){   
                if($_POST["G10_C116"] != '' && $_POST["G10_C116"] != 'undefined' && $_POST["G10_C116"] != 'null'){
                    $separador = "";
                    $fecha = date('Y-m-d');
                    if($validar == 1){
                        $separador = ",";
                    }

                    $G10_C116 = "'".$fecha." ".str_replace(' ', '',$_POST["G10_C116"])."'";
                    $str_LsqlU .= $separador." G10_C116 = ".$G10_C116."";
                    $str_LsqlI .= $separador." G10_C116";
                    $str_LsqlV .= $separador.$G10_C116;
                    $validar = 1;
                }
            }
  
            $G10_C117 = 0;
            //este es tipo check primeo si viene y de acuerdo a su valor se pone 1 o 0
            if(isset($_POST["G10_C117"])){
                if($_POST["G10_C117"] == 'Yes'){
                    $G10_C117 = 1;
                }else if($_POST["G10_C117"] == 'off'){
                    $G10_C117 = 0;
                }else if($_POST["G10_C117"] == 'on'){
                    $G10_C117 = 1;
                }else if($_POST["G10_C117"] == 'No'){
                    $G10_C117 = 0;
                }else{
                    $G10_C117 = $_POST["G10_C117"] ;
                }   

                $separador = "";
                if($validar == 1){
                    $separador = ",";
                }

                $str_LsqlU .= $separador." G10_C117 = ".$G10_C117."";
                $str_LsqlI .= $separador." G10_C117";
                $str_LsqlV .= $separador.$G10_C117;

                $validar = 1;
            }else{
                $separador = "";
                if($validar == 1){
                    $separador = ",";
                }

                $str_LsqlU .= $separador." G10_C117 = ".$G10_C117."";
                $str_LsqlI .= $separador." G10_C117";
                $str_LsqlV .= $separador.$G10_C117;

                $validar = 1;
            }
  
            $G10_C118 = NULL;
            //este es de tipo date hay que preguntar si esta vacia o no o esta undefined
            if(isset($_POST["G10_C118"])){   
                if($_POST["G10_C118"] != '' && $_POST["G10_C118"] != 'undefined' && $_POST["G10_C118"] != 'null'){
                    $separador = "";
                    $fecha = date('Y-m-d');
                    if($validar == 1){
                        $separador = ",";
                    }

                    $G10_C118 = "'".$fecha." ".str_replace(' ', '',$_POST["G10_C118"])."'";
                    $str_LsqlU .= $separador." G10_C118 = ".$G10_C118."";
                    $str_LsqlI .= $separador." G10_C118";
                    $str_LsqlV .= $separador.$G10_C118;
                    $validar = 1;
                }
            }
  
            if(isset($_POST["G10_C119"])){
                $separador = "";
                $fecha = date('Y-m-d');
                if($validar == 1){
                    $separador = ",";
                }

                $G10_C119 = "'".$fecha." ".str_replace(' ', '',$_POST["G10_C119"])."'";
                $str_LsqlU .= $separador." G10_C119 = ".$G10_C119."";
                $str_LsqlI .= $separador." G10_C119";
                $str_LsqlV .= $separador.$G10_C119;
                $validar = 1;
            }
             
  
            $G10_C120 = 0;
            //este es tipo check primeo si viene y de acuerdo a su valor se pone 1 o 0
            if(isset($_POST["G10_C120"])){
                if($_POST["G10_C120"] == 'Yes'){
                    $G10_C120 = 1;
                }else if($_POST["G10_C120"] == 'off'){
                    $G10_C120 = 0;
                }else if($_POST["G10_C120"] == 'on'){
                    $G10_C120 = 1;
                }else if($_POST["G10_C120"] == 'No'){
                    $G10_C120 = 0;
                }else{
                    $G10_C120 = $_POST["G10_C120"] ;
                }   

                $separador = "";
                if($validar == 1){
                    $separador = ",";
                }

                $str_LsqlU .= $separador." G10_C120 = ".$G10_C120."";
                $str_LsqlI .= $separador." G10_C120";
                $str_LsqlV .= $separador.$G10_C120;

                $validar = 1;
            }else{
                $separador = "";
                if($validar == 1){
                    $separador = ",";
                }

                $str_LsqlU .= $separador." G10_C120 = ".$G10_C120."";
                $str_LsqlI .= $separador." G10_C120";
                $str_LsqlV .= $separador.$G10_C120;

                $validar = 1;
            }
  
            $G10_C121 = NULL;
            //este es de tipo date hay que preguntar si esta vacia o no o esta undefined
            if(isset($_POST["G10_C121"])){   
                if($_POST["G10_C121"] != '' && $_POST["G10_C121"] != 'undefined' && $_POST["G10_C121"] != 'null'){
                    $separador = "";
                    $fecha = date('Y-m-d');
                    if($validar == 1){
                        $separador = ",";
                    }

                    $G10_C121 = "'".$fecha." ".str_replace(' ', '',$_POST["G10_C121"])."'";
                    $str_LsqlU .= $separador." G10_C121 = ".$G10_C121."";
                    $str_LsqlI .= $separador." G10_C121";
                    $str_LsqlV .= $separador.$G10_C121;
                    $validar = 1;
                }
            }
  
            $G10_C122 = NULL;
            //este es de tipo date hay que preguntar si esta vacia o no o esta undefined
            if(isset($_POST["G10_C122"])){   
                if($_POST["G10_C122"] != '' && $_POST["G10_C122"] != 'undefined' && $_POST["G10_C122"] != 'null'){
                    $separador = "";
                    $fecha = date('Y-m-d');
                    if($validar == 1){
                        $separador = ",";
                    }

                    $G10_C122 = "'".$fecha." ".str_replace(' ', '',$_POST["G10_C122"])."'";
                    $str_LsqlU .= $separador." G10_C122 = ".$G10_C122."";
                    $str_LsqlI .= $separador." G10_C122";
                    $str_LsqlV .= $separador.$G10_C122;
                    $validar = 1;
                }
            }
  
            $G10_C123 = 0;
            //este es tipo check primeo si viene y de acuerdo a su valor se pone 1 o 0
            if(isset($_POST["G10_C123"])){
                if($_POST["G10_C123"] == 'Yes'){
                    $G10_C123 = 1;
                }else if($_POST["G10_C123"] == 'off'){
                    $G10_C123 = 0;
                }else if($_POST["G10_C123"] == 'on'){
                    $G10_C123 = 1;
                }else if($_POST["G10_C123"] == 'No'){
                    $G10_C123 = 0;
                }else{
                    $G10_C123 = $_POST["G10_C123"] ;
                }   

                $separador = "";
                if($validar == 1){
                    $separador = ",";
                }

                $str_LsqlU .= $separador." G10_C123 = ".$G10_C123."";
                $str_LsqlI .= $separador." G10_C123";
                $str_LsqlV .= $separador.$G10_C123;

                $validar = 1;
            }else{
                $separador = "";
                if($validar == 1){
                    $separador = ",";
                }

                $str_LsqlU .= $separador." G10_C123 = ".$G10_C123."";
                $str_LsqlI .= $separador." G10_C123";
                $str_LsqlV .= $separador.$G10_C123;

                $validar = 1;
            }
  
            $G10_C124 = NULL;
            //este es de tipo date hay que preguntar si esta vacia o no o esta undefined
            if(isset($_POST["G10_C124"])){   
                if($_POST["G10_C124"] != '' && $_POST["G10_C124"] != 'undefined' && $_POST["G10_C124"] != 'null'){
                    $separador = "";
                    $fecha = date('Y-m-d');
                    if($validar == 1){
                        $separador = ",";
                    }

                    $G10_C124 = "'".$fecha." ".str_replace(' ', '',$_POST["G10_C124"])."'";
                    $str_LsqlU .= $separador." G10_C124 = ".$G10_C124."";
                    $str_LsqlI .= $separador." G10_C124";
                    $str_LsqlV .= $separador.$G10_C124;
                    $validar = 1;
                }
            }
  
            $G10_C125 = NULL;
            //este es de tipo date hay que preguntar si esta vacia o no o esta undefined
            if(isset($_POST["G10_C125"])){   
                if($_POST["G10_C125"] != '' && $_POST["G10_C125"] != 'undefined' && $_POST["G10_C125"] != 'null'){
                    $separador = "";
                    $fecha = date('Y-m-d');
                    if($validar == 1){
                        $separador = ",";
                    }

                    $G10_C125 = "'".$fecha." ".str_replace(' ', '',$_POST["G10_C125"])."'";
                    $str_LsqlU .= $separador." G10_C125 = ".$G10_C125."";
                    $str_LsqlI .= $separador." G10_C125";
                    $str_LsqlV .= $separador.$G10_C125;
                    $validar = 1;
                }
            }
  
            $G10_C126 = 0;
            //este es tipo check primeo si viene y de acuerdo a su valor se pone 1 o 0
            if(isset($_POST["G10_C126"])){
                if($_POST["G10_C126"] == 'Yes'){
                    $G10_C126 = 1;
                }else if($_POST["G10_C126"] == 'off'){
                    $G10_C126 = 0;
                }else if($_POST["G10_C126"] == 'on'){
                    $G10_C126 = 1;
                }else if($_POST["G10_C126"] == 'No'){
                    $G10_C126 = 0;
                }else{
                    $G10_C126 = $_POST["G10_C126"] ;
                }   

                $separador = "";
                if($validar == 1){
                    $separador = ",";
                }

                $str_LsqlU .= $separador." G10_C126 = ".$G10_C126."";
                $str_LsqlI .= $separador." G10_C126";
                $str_LsqlV .= $separador.$G10_C126;

                $validar = 1;
            }else{
                $separador = "";
                if($validar == 1){
                    $separador = ",";
                }

                $str_LsqlU .= $separador." G10_C126 = ".$G10_C126."";
                $str_LsqlI .= $separador." G10_C126";
                $str_LsqlV .= $separador.$G10_C126;

                $validar = 1;
            }
  
            $G10_C127 = NULL;
            //este es de tipo date hay que preguntar si esta vacia o no o esta undefined
            if(isset($_POST["G10_C127"])){   
                if($_POST["G10_C127"] != '' && $_POST["G10_C127"] != 'undefined' && $_POST["G10_C127"] != 'null'){
                    $separador = "";
                    $fecha = date('Y-m-d');
                    if($validar == 1){
                        $separador = ",";
                    }

                    $G10_C127 = "'".$fecha." ".str_replace(' ', '',$_POST["G10_C127"])."'";
                    $str_LsqlU .= $separador." G10_C127 = ".$G10_C127."";
                    $str_LsqlI .= $separador." G10_C127";
                    $str_LsqlV .= $separador.$G10_C127;
                    $validar = 1;
                }
            }
  
            $G10_C128 = NULL;
            //este es de tipo date hay que preguntar si esta vacia o no o esta undefined
            if(isset($_POST["G10_C128"])){   
                if($_POST["G10_C128"] != '' && $_POST["G10_C128"] != 'undefined' && $_POST["G10_C128"] != 'null'){
                    $separador = "";
                    $fecha = date('Y-m-d');
                    if($validar == 1){
                        $separador = ",";
                    }

                    $G10_C128 = "'".$fecha." ".str_replace(' ', '',$_POST["G10_C128"])."'";
                    $str_LsqlU .= $separador." G10_C128 = ".$G10_C128."";
                    $str_LsqlI .= $separador." G10_C128";
                    $str_LsqlV .= $separador.$G10_C128;
                    $validar = 1;
                }
            }
  
            $G10_C129 = 0;
            //este es tipo check primeo si viene y de acuerdo a su valor se pone 1 o 0
            if(isset($_POST["G10_C129"])){
                if($_POST["G10_C129"] == 'Yes'){
                    $G10_C129 = 1;
                }else if($_POST["G10_C129"] == 'off'){
                    $G10_C129 = 0;
                }else if($_POST["G10_C129"] == 'on'){
                    $G10_C129 = 1;
                }else if($_POST["G10_C129"] == 'No'){
                    $G10_C129 = 0;
                }else{
                    $G10_C129 = $_POST["G10_C129"] ;
                }   

                $separador = "";
                if($validar == 1){
                    $separador = ",";
                }

                $str_LsqlU .= $separador." G10_C129 = ".$G10_C129."";
                $str_LsqlI .= $separador." G10_C129";
                $str_LsqlV .= $separador.$G10_C129;

                $validar = 1;
            }else{
                $separador = "";
                if($validar == 1){
                    $separador = ",";
                }

                $str_LsqlU .= $separador." G10_C129 = ".$G10_C129."";
                $str_LsqlI .= $separador." G10_C129";
                $str_LsqlV .= $separador.$G10_C129;

                $validar = 1;
            }
  
            $G10_C323 = 0;
            //este es tipo check primeo si viene y de acuerdo a su valor se pone 1 o 0
            if(isset($_POST["ActivaChatCampana"])){
                if($_POST["ActivaChatCampana"] == 'Yes'){
                    $G10_C323 = 1;
                }else if($_POST["ActivaChatCampana"] == 'off'){
                    $G10_C323 = 0;
                }else if($_POST["ActivaChatCampana"] == 'on'){
                    $G10_C323 = 1;
                }else if($_POST["ActivaChatCampana"] == 'No'){
                    $G10_C323 = 0;
                }else{
                    $G10_C323 = $_POST["ActivaChatCampana"] ;
                }   

                $separador = "";
                if($validar == 1){
                    $separador = ",";
                }

                $str_LsqlU .= $separador." G10_C323 = ".$G10_C323."";
                $str_LsqlI .= $separador." G10_C323";
                $str_LsqlV .= $separador.$G10_C323;

                $validar = 1;
            }else{
                $separador = "";
                if($validar == 1){
                    $separador = ",";
                }

                $str_LsqlU .= $separador." G10_C323 = ".$G10_C323."";
                $str_LsqlI .= $separador." G10_C323";
                $str_LsqlV .= $separador.$G10_C323;

                $validar = 1;
            }

            $G10_C130 = NULL;
            //este es de tipo date hay que preguntar si esta vacia o no o esta undefined
            if(isset($_POST["G10_C130"])){   
                if($_POST["G10_C130"] != '' && $_POST["G10_C130"] != 'undefined' && $_POST["G10_C130"] != 'null'){
                    $separador = "";
                    $fecha = date('Y-m-d');
                    if($validar == 1){
                        $separador = ",";
                    }

                    $G10_C130 = "'".$fecha." ".str_replace(' ', '',$_POST["G10_C130"])."'";
                    $str_LsqlU .= $separador." G10_C130 = ".$G10_C130."";
                    $str_LsqlI .= $separador." G10_C130";
                    $str_LsqlV .= $separador.$G10_C130;
                    $validar = 1;
                }
            }
  
            $G10_C131 = NULL;
            //este es de tipo date hay que preguntar si esta vacia o no o esta undefined
            if(isset($_POST["G10_C131"])){   
                if($_POST["G10_C131"] != '' && $_POST["G10_C131"] != 'undefined' && $_POST["G10_C131"] != 'null'){
                    $separador = "";
                    $fecha = date('Y-m-d');
                    if($validar == 1){
                        $separador = ",";
                    }

                    $G10_C131 = "'".$fecha." ".str_replace(' ', '',$_POST["G10_C131"])."'";
                    $str_LsqlU .= $separador." G10_C131 = ".$G10_C131."";
                    $str_LsqlI .= $separador." G10_C131";
                    $str_LsqlV .= $separador.$G10_C131;
                    $validar = 1;
                }
            }
    
            /* Datos adiconales */
            if(isset($_POST["G10_C318"])){
                $dato=$_POST["G10_C318"];
                if($_POST["G10_C318"]=='0'){
                    $dato="NULL";
                }
                $separador = "";
                if($validar == 1){
                    $separador = ",";
                }

                $str_LsqlU .= $separador."G10_C318 = ".$dato;
                $str_LsqlI .= $separador."G10_C318";
                $str_LsqlV .= $separador.$dato;
                $validar = 1;
            }

            if(isset($_POST["G10_C319"])){
                $dato=$_POST["G10_C319"];
                if($_POST["G10_C319"]=='0'){
                    $dato="NULL";
                }
                $separador = "";
                if($validar == 1){
                    $separador = ",";
                }

                $str_LsqlU .= $separador."G10_C319 = ".$dato;
                $str_LsqlI .= $separador."G10_C319";
                $str_LsqlV .= $separador.$dato;
                $validar = 1;
            }
            if(isset($_POST["G10_C320"])){
                $dato=$_POST["G10_C320"];
                if($_POST["G10_C320"]=='0'){
                    $dato="NULL";
                }
                $separador = "";
                if($validar == 1){
                    $separador = ",";
                }

                $str_LsqlU .= $separador."G10_C320 = ".$dato;
                $str_LsqlI .= $separador."G10_C320";
                $str_LsqlV .= $separador.$dato;
                $validar = 1;
            }

            if(isset($_POST["G10_C321"])){
                $dato=$_POST["G10_C321"];
                if($_POST["G10_C321"]=='0'){
                    $dato="NULL";
                }
                $separador = "";
                if($validar == 1){
                    $separador = ",";
                }

                $str_LsqlU .= $separador."G10_C321 = ".$dato;
                $str_LsqlI .= $separador."G10_C321";
                $str_LsqlV .= $separador.$dato;
                $validar = 1;
            }            
            
            if(isset($_POST["G10_C322"])){
                $dato=$_POST["G10_C322"];
                if($_POST["G10_C322"]=='0'){
                    $dato="NULL";
                }
                $separador = "";
                if($validar == 1){
                    $separador = ",";
                }

                $str_LsqlU .= $separador."G10_C322 = ".$dato;
                $str_LsqlI .= $separador."G10_C322";
                $str_LsqlV .= $separador.$dato;
                $validar = 1;
            }

            if(isset($_POST["MailCuenta"])){
                $separador = "";
                if($validar == 1){
                    $separador = ",";
                }

                $str_LsqlU .= $separador."G10_C325 = '".$_POST["MailCuenta"]."'";
                $str_LsqlI .= $separador."G10_C325";
                $str_LsqlV .= $separador."'".$_POST["MailCuenta"]."'";
                $validar = 1;
            }

            if(isset($_POST['G10_C328_check'])){
                $separador = "";
                
                if($validar == 1){
                    $separador = ",";
                }

                $str_LsqlU .= $separador."G10_C328 = '".$_POST['G10_C328']."'";
                $str_LsqlI .= $separador."G10_C328";
                $str_LsqlV .= $separador."'".$_POST['G10_C328']."'";
                $validar = 1;
                
            }else{

                $separador = "";
                if($validar == 1){
                    $separador = ",";
                }

                $str_LsqlU .= $separador."G10_C328 = NULL";
                $str_LsqlI .= $separador."G10_C328";
                $str_LsqlV .= $separador."NULL";
                $validar = 1;

            }

            if(isset($_POST['G10_C329'])){
                $separador = "";
                if($validar == 1){
                    $separador = ",";
                }

                $str_LsqlU .= $separador."G10_C329 = '".$_POST['G10_C329']."'";
                $str_LsqlI .= $separador."G10_C329";
                $str_LsqlV .= $separador."'".$_POST['G10_C329']."'";
                $validar = 1;
            }

            
            $G10_C330 = 0;
            //este es tipo check primeo si viene y de acuerdo a su valor se pone 1 o 0
            if(isset($_POST["G10_C330"])){
                if($_POST["G10_C330"] == 'Yes'){
                    $G10_C330 = 1;
                }else if($_POST["G10_C330"] == 'off'){
                    $G10_C330 = 0;
                }else if($_POST["G10_C330"] == 'on'){
                    $G10_C330 = 1;
                }else if($_POST["G10_C330"] == 'No'){
                    $G10_C330 = 0;
                }else{
                    $G10_C330 = $_POST["G10_C330"] ;
                }   

                $separador = "";
                if($validar == 1){
                    $separador = ",";
                }

                $str_LsqlU .= $separador." G10_C330 = ".$G10_C330."";
                $str_LsqlI .= $separador." G10_C330";
                $str_LsqlV .= $separador.$G10_C330;

                $validar = 1;

                if(isset($_POST['ivr_encuesta']) && $_POST['ivr_encuesta']!='0'){
                    $context="context='{$_POST['ivr_encuesta']}'";
                }else{
                    $context="context=NULL";
                }
                
                $sql_ivr_espera=$mysqli->query("update dyalogo_telefonia.dy_campanas set {$context} where id_campana_crm={$_POST["id"]}");
                $booEstpas=validarEstpasCallback($G10_C330,$_POST["id"],$_POST['id_estrategia']);
            }else{
                $separador = "";
                if($validar == 1){
                    $separador = ",";
                }

                $str_LsqlU .= $separador." G10_C330 = ".$G10_C330."";
                $str_LsqlI .= $separador." G10_C330";
                $str_LsqlV .= $separador.$G10_C330;

                $validar = 1;

                if(isset($_POST['ivr_encuesta']) && $_POST['ivr_encuesta']!='0'){
                    $context="context='{$_POST['ivr_encuesta']}'";
                }else{
                    $context="context=NULL";
                }
                
                $sql_ivr_espera=$mysqli->query("update dyalogo_telefonia.dy_campanas set {$context} where id_campana_crm={$_POST["id"]}");
                $booEstpas=validarEstpasCallback($G10_C330,$_POST["id"],$_POST['id_estrategia']);
            }


            $G10_C324 = 1;
            //este es tipo check primeo si viene y de acuerdo a su valor se pone 1 o 0
            if(isset($_POST["ActivaMailCampana"])){
                if($_POST["ActivaMailCampana"] == 'Yes'){
                    $G10_C324 = 1;
                }else if($_POST["ActivaMailCampana"] == 'off'){
                    $G10_C324 = 0;
                }else if($_POST["ActivaMailCampana"] == 'on'){
                    $G10_C324 = 1;
                }else if($_POST["ActivaMailCampana"] == 'No'){
                    $G10_C324 = 0;
                }else{
                    $G10_C324 = $_POST["ActivaMailCampana"] ;
                }   

                $separador = "";
                if($validar == 1){
                    $separador = ",";
                }

                $str_LsqlU .= $separador." G10_C324 = ".$G10_C324."";
                $str_LsqlI .= $separador." G10_C324";
                $str_LsqlV .= $separador.$G10_C324;

                $validar = 1;
            }else{
                $separador = "";
                if($validar == 1){
                    $separador = ",";
                }

                $str_LsqlU .= $separador." G10_C324 = ".$G10_C324."";
                $str_LsqlI .= $separador." G10_C324";
                $str_LsqlV .= $separador.$G10_C324;

                $validar = 1;
            }


            $padre = NULL;
            //este es de tipo date hay que preguntar si esta vacia o no
            if(isset($_POST["padre"])){    
                if($_POST["padre"] != '0' && $_POST['padre'] != ''){
                    $separador = "";
                    if($validar == 1){
                        $separador = ",";
                    }

                    //primero hay que ir y buscar los campos
                    $str_Lsql = "SELECT GUIDET_ConsInte__PREGUN_De1_b FROM ".$BaseDatos_systema.".GUIDET WHERE GUIDET_ConsInte__GUION__Mae_b = ".$_POST['formpadre']." AND GUIDET_ConsInte__GUION__Det_b = ".$_POST['formhijo'];

                    $GuidRes = $mysqli->query($str_Lsql);
                    $campo = null;
                    while($ky = $GuidRes->fetch_object()){
                        $campo = $ky->GUIDET_ConsInte__PREGUN_De1_b;
                    }
                    $valorG = "G10_C";
                    $valorH = $valorG.$campo;
                    $str_LsqlU .= $separador." " .$valorH." = ".$_POST["padre"];
                    $str_LsqlI .= $separador." ".$valorH;
                    $str_LsqlV .= $separador.$_POST['padre'] ;
                    $validar = 1;
                }
            }

            if(isset($_GET['id_gestion_cbx'])){
                $separador = "";
                if($validar == 1){
                    $separador = ",";
                }

                $str_LsqlU .= $separador."G10_IdLlamada = '".$_GET['id_gestion_cbx']."'";
                $str_LsqlI .= $separador."G10_IdLlamada";
                $str_LsqlV .= $separador."'".$_GET['id_gestion_cbx']."'";
                $validar = 1;
            }

            

            if(isset($_POST['oper'])){
                if($_POST["oper"] == 'add' ){
                    //$str_Lsql = $str_LsqlI." , G10_C75)" . $str_LsqlV.", ".$id_Muestras.")";
                }else if($_POST["oper"] == 'edit' ){
                    $str_Lsql = $str_LsqlU." WHERE G10_ConsInte__b =".$_POST["id"]; 
                }else if($_POST["oper"] == 'del' ){
                    $str_Lsql = "DELETE FROM ".$BaseDatos_systema.".G10 WHERE G10_ConsInte__b = ".$_POST['id'];
                    $validar = 1;
                }
            }

            //si trae algo que insertar inserta

            // echo 'ese'.$str_Lsql;
            // die();
            if($validar == 1){
                if ($mysqli->query($str_Lsql) === TRUE) {
                    $id_usuario = $mysqli->insert_id;
                    if($id_usuario != 0){
                        $id_campanas_crm = $id_usuario;
                    }else{
                        $id_campanas_crm = $_POST['id'];
                    }

                    if($_POST["oper"] == 'add' ){
                        
                    }else if($_POST["oper"] == 'edit' ){

                        //GUARDAR LOS PERMISOS DE BUSQUEDA
                        if(isset($_POST['insertRegistro'])){
                            $insertAuto=isset($_POST['insertAuto']) ? $_POST['insertAuto'] : 0;
                            $strSQLTipoBusMa="UPDATE {$BaseDatos_systema}.GUION_ SET GUION_PERMITEINSERTAR_b={$_POST['insertRegistro']}, GUION_INSERTAUTO_b={$insertAuto} WHERE GUION__ConsInte__b={$_POST['poblacion']}";
                            $strSQLTipoBusMa=$mysqli->query($strSQLTipoBusMa);
                        }

                        //GENERAR EL FORMULARIO DE BÚSQUEDA DE LA BD DE LA CAMPAÑA
                        if(isset($_POST['checkBusqueda'])){
                            $strSQLTipoBusMa="UPDATE {$BaseDatos_systema}.GUION_ SET GUION__TipoBusqu__Manual_b={$_POST['TipoBusqManual']} WHERE GUION__ConsInte__b={$_POST['poblacion']}";
                            $strSQLTipoBusMa=$mysqli->query($strSQLTipoBusMa);
                            generar_Busqueda_Manual($_POST['poblacion']);
                            generar_Busqueda_Dato_Adicional($_POST['poblacion']);
                            generar_Busqueda_Ani($_POST['poblacion']);
                        }
                        
                        // Valido que almenos traiga el check
                        $checkActivoCampana = $_POST["G10_C72"] ?? 0;

                        $strSQLUpdateEstpas_t = "UPDATE ".$BaseDatos_systema.".ESTPAS SET ESTPAS_Comentari_b = '".$_POST["G10_C71"]."', ESTPAS_activo = ".$checkActivoCampana." WHERE ESTPAS_ConsInte__CAMPAN_b = ".$_POST["id"];

                        $mysqli->query($strSQLUpdateEstpas_t);
                        /* como cuando llega aca siempre sera para ctualizar */
                        //$carpeta = "/var/www/html/crm_php/formularios/G".$_POST['G10_C74'];
                        //if (!file_exists($carpeta)) {
                          //  echo "No existe";
                            //invocarCrm_CrearScripts($_POST["id"]);
                        //}//

                        //GUARDAR LOS CAMPOS DEL CBX
                        updateCamposCBX($_POST);

                        $data = array(  
                                        "strUsuario_t"          =>  'local',
                                        "strToken_t"            =>  'local',
                                        "intIdESTPAS_t"         =>  $_POST['id_estpas']
                                    );                                                             
                        $data_string = json_encode($data);   
                        echo $data_string; 

                        $ch = curl_init($Api_Gestion.'dyalogocore/api/campanas/voip/persistir');
                        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");                                                                     
                        curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string); 
                        curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);
                        curl_setopt($ch, CURLOPT_HTTPHEADER, array(                                                                          
                            'Content-Type: application/json',                                                                                
                            'Content-Length: ' . strlen($data_string))                                                                      
                        ); 
                        $respuesta = curl_exec ($ch);
                        $error = curl_error($ch);
                        curl_close ($ch);
                        echo " Respuesta => ".$respuesta;
                        echo " Error => ".$error;
                        if(!empty($respuesta) && !is_null($respuesta)){
                            $json = json_decode($respuesta);

                            if($json->strEstado_t == "ok"){
                                //en caso de que sea extoso 

                                $UpdateSqlCampanCBX = "UPDATE ".$BaseDatos_systema.".CAMPAN SET CAMPAN_IdCamCbx__b = ".$json->objSerializar_t ." WHERE CAMPAN_ConsInte__b = ".$id_usuario;

                                if($mysqli->query($UpdateSqlCampanCBX) === true){
                                    //si actualizo esta jugada bienvenido sea
                                } 
                            }
                              
                        }

                        /* ahora toca crear y editar lso lisopc , primero es validar que venga algo que editar*/
                        $ultimoLista = 0;
                        if(isset($_POST['idLisop'])){

                            //var_dump($_POST['idLisop']);
                            /* vienen a edicion */
                            foreach ($_POST['idLisop'] as $key) {
                                $estados = 0;
                                if(isset($_POST['estado_'.$key])){
                                    $estados = $_POST['estado_'.$key];
                                }
                                $insertLisopc = "UPDATE ".$BaseDatos_systema.".LISOPC  SET LISOPC_Nombre____b = '".$_POST['opciones_'.$key]."' , LISOPC_Valor____b = '".$estados."' WHERE LISOPC_ConsInte__b = ".$key;
                                if($mysqli->query($insertLisopc) === true){
                             
                                }else{
                                    echo 'Error Actualizando las tipificaciones '.$mysqli->error;
                                }

                                $Lip = "SELECT LISOPC_Clasifica_b , LISOPC_ConsInte__OPCION_b FROM ".$BaseDatos_systema.".LISOPC WHERE LISOPC_ConsInte__b = ".$key;
                               // echo $Lip;
                                $res = $mysqli->query($Lip);
                                $data = $res->fetch_array();

                                $ultimoLista = $data['LISOPC_ConsInte__OPCION_b'];
                                //, MONOEF_Importanc_b = ".$_POST['inportancia_'.$key]."
                                $MONOEFLsql = "UPDATE ".$BaseDatos_systema.".MONOEF SET MONOEF_Texto_____b = '".$_POST['opciones_'.$key]."', MONOEF_Contacto__b=".$_POST['contacto_'.$key]." , MONOEF_TipiCBX___b = ".$estados." , MONOEF_CanHorProxGes__b=".$_POST['txtHorasMas_'.$key]." WHERE MONOEF_ConsInte__b = ".$data ['LISOPC_Clasifica_b'];

                                if($mysqli->query($MONOEFLsql) === true){
                                    //$monoefNew = $mysqli->insert_id;
                                    /* ahora si lo insertamos en el LISOPC */
                                    
                                }else{
                                    echo 'Error actualizando el monoef '.$mysqli->error;
                                }
                            }
                            
                        }

                        //metemos los nuevos
                        if(isset($_POST['contador'])){
                            $cuantoshay = 0;
                            for ($i=0; $i < $_POST['contador']; $i++) { 
                        
                                if(isset($_POST['opciones_'.$i])){
                                    $cuantoshay++;
                                    $importancia = 0;
                                    if(isset($_POST['efectividad_'.$i])){
                                        $importancia = -1;
                                    }
                                    //, '".$_POST['inportancia_'.$i]."' 
                                    //MONOEF_Importanc_b,
                                    $estados = 0;
                                    if(isset($_POST['estado_'.$i])){
                                        $estados = $_POST['estado_'.$i];
                                    }

                                    $MONOEFLsql = "INSERT INTO ".$BaseDatos_systema.".MONOEF (MONOEF_Texto_____b, MONOEF_EFECTIVA__B, MONOEF_TipNo_Efe_b,  MONOEF_FechCrea__b, MONOEF_UsuaCrea__b, MONOEF_Contacto__b, MONOEF_TipiCBX___b, MONOEF_CanHorProxGes__b) VALUES ('".$_POST['opciones_'.$i]."','".$importancia."' , 1 , '".date('Y-m-d H:s:i')."' , ".$_SESSION['IDENTIFICACION']." , '".$_POST['contacto_'.$i]."' , '".$estados."' , ".$_POST['txtHorasMas_'.$i].")";

                                    if($mysqli->query($MONOEFLsql) === true){
                                        $monoefNew = $mysqli->insert_id;
                                        /* ahora si lo insertamos en el LISOPC */

                                        if(isset($_POST['IdListaMia'])){
                                            $insertLisopc = "INSERT INTO ".$BaseDatos_systema.".LISOPC (LISOPC_Nombre____b, LISOPC_ConsInte__OPCION_b, LISOPC_Posicion__b, LISOPC_Clasifica_b, LISOPC_Valor____b ) VALUES ('".$_POST['opciones_'.$i]."', ".$_POST['IdListaMia'].", 0, ".$monoefNew." , '".$estados."' );";

                                            if($mysqli->query($insertLisopc) === true){
                                                //$correcto++;  
                                            }else{
                                                echo "Cagada en LISOPC => ". $mysqli->error;
                                            }

                                        }else{
                                            /* No tenemos la puta lista creada */
                                            
                                        }
                                    }else{
                                        echo $mysqli->error;
                                    }
                                }else{
                                    echo "No existe";
                                }
                            }
                        }


                        if(isset($_POST['totalGuardadosAsuntos'])){
                            /* Toca hacer update a los reportes */
                            for ($i = 0; $i < $_POST['totalGuardadosAsuntos']; $i++) { 
                                if(isset($_POST['txtAsuntosGuardados_'.$i]) && $_POST['txtAquienVa_'.$i] != '' && $_POST['txtNombreReporte_'.$i] != '' && $_POST['txtHoraEnvio_'.$i] != '' ){

                                    $updateSQL = "UPDATE ".$BaseDatos_general.".reportes_automatizados SET destinatarios = '".$_POST['txtAquienVa_'.$i]."' , destinatarios_cc = '".$_POST['txtCopiaA_'.$i]."', momento_envio = '".$_POST['txtHoraEnvio_'.$i]."', asunto = '".$_POST['txtNombreReporte_'.$i]."' WHERE id  = ".$_POST['txtAsuntosGuardados_'.$i];

                                    $res = $mysqli->query($updateSQL);                     
                                    if($res === true){

                                    }else{
                                        echo "ERROR HACIENDO UPDATE EN LOS REPORTES ".$mysqli->error;
                                    }
                                }
                            }
                        }

                        if(isset($_POST['contarAsuntos']) && $_POST['contarAsuntos'] != 0){
                            
                            for ($i=0; $i < $_POST['contarAsuntos']; $i++) { 
                                if(isset($_POST['GtxtNombreReporte_'.$i])  && $_POST['GtxtAquienVa_'.$i] != '' && $_POST['GtxtNombreReporte_'.$i] != '' && $_POST['GtxtHoraEnvio_'.$i] != '' ){
                                    echo "Llego aqui";
                                    if($_POST['GcmbPeriodicidad_'.$i] == '1'){
                                        echo "Llego aqui";
                                        generarReportesExcell_diarios($_POST['id'], $_POST['GtxtAquienVa_'.$i], $_POST['GtxtCopiaA_'.$i], $_POST['GtxtNombreReporte_'.$i],  $_POST['GtxtHoraEnvio_'.$i]);
                                    
                                    }else if($_POST['GcmbPeriodicidad_'.$i] == '2'){
                                        
                                        generarReportesExcell_semanales($_POST['id'], $_POST['GtxtAquienVa_'.$i], $_POST['GtxtCopiaA_'.$i], $_POST['GtxtNombreReporte_'.$i],  $_POST['GtxtHoraEnvio_'.$i]);
                                    
                                    }else if($_POST['GcmbPeriodicidad_'.$i] == '3'){
                                        
                                        generarReportesExcell_mensuales($_POST['id'], $_POST['GtxtAquienVa_'.$i], $_POST['GtxtCopiaA_'.$i], $_POST['GtxtNombreReporte_'.$i],  $_POST['GtxtHoraEnvio_'.$i]);
                                    
                                    }
                                }
                            }
                            
                        }

                        if(isset($_POST['contadorASociaciones']) && $_POST['contadorASociaciones'] != 0){
                            //Guardamos las asociaciones de campos
                            $csql = "SELECT CAMPAN_ConsInte__GUION__Pob_b, CAMPAN_ConsInte__GUION__Gui_b, CAMPAN_ConsInte__MUESTR_b FROM ".$BaseDatos_systema.".CAMPAN WHERE CAMPAN_ConsInte__b = ".$_POST['id'];
                            $res = $mysqli->query($csql);
                            $dataCam = $res->fetch_array();

                            for ($i=0; $i < $_POST['contadorASociaciones']; $i++) { 

                                if (isset($_POST['asocioa_' . $i]) && $_POST['asocioa_' . $i] != '0') {

                                    if (isset($_POST['asocioaG_' . $i]) && $_POST['asocioaG_' . $i] != '0') {

                                        if (is_numeric($_POST['asocioa_'.$i])) {

                                            $CAMINC_NomCamPob_b = "G".$dataCam['CAMPAN_ConsInte__GUION__Pob_b']."_C".$_POST['asocioa_'.$i];
                                            $CAMINC_ConsInte__CAMPO_Pob_b = $_POST['asocioa_'.$i];

                                        }else{

                                            if (strpos($_POST['asocioa_'.$i], "_ConsInte__b")) {

                                                $CAMINC_ConsInte__CAMPO_Pob_b = "-1";
                                                $CAMINC_NomCamPob_b = "G".$dataCam['CAMPAN_ConsInte__GUION__Pob_b']."_ConsInte__b";

                                            }else if (strpos($_POST['asocioa_'.$i], "_FechaInsercion")) {

                                                $CAMINC_ConsInte__CAMPO_Pob_b = "-2";
                                                $CAMINC_NomCamPob_b = "G".$dataCam['CAMPAN_ConsInte__GUION__Pob_b']."_FechaInsercion";

                                            }


                                        }

                                    }

                                }

                                if(isset($_POST['asocioa_'.$i]) && $_POST['asocioa_'.$i] != '0'){
                                    if(isset($_POST['asocioaG_'.$i]) && $_POST['asocioaG_'.$i] != '0'){

                                        $strTextoPo_t=substr(getTextoPregun($_POST['asocioa_' . $i]), 0,250);
                                        $strTextoGion_t=substr(getTextoPregun($_POST['asocioaG_' . $i]), 0,250);

                                        $CamincLsql = "INSERT INTO ".$BaseDatos_systema.".CAMINC(CAMINC_ConsInte__CAMPO_Pob_b, CAMINC_NomCamPob_b , CAMINC_TexPrePob_b , CAMINC_ConsInte__CAMPO_Gui_b,CAMINC_NomCamGui_b , CAMINC_TexPreGui_b, CAMINC_ConsInte__CAMPAN_b, CAMINC_ConsInte__GUION__Pob_b, CAMINC_ConsInte__GUION__Gui_b, CAMINC_ConsInte__MUESTR_b ) VALUES ( ".$CAMINC_ConsInte__CAMPO_Pob_b.", '".$CAMINC_NomCamPob_b."' ,  '".$strTextoPo_t."' , ".$_POST['asocioaG_'.$i]." , 'G".$dataCam['CAMPAN_ConsInte__GUION__Gui_b']."_C".$_POST['asocioaG_'.$i]."' , '".$strTextoGion_t."' , ".$_POST['id']." , '".$dataCam['CAMPAN_ConsInte__GUION__Pob_b']."' , '".$dataCam['CAMPAN_ConsInte__GUION__Gui_b']."' , ".$dataCam['CAMPAN_ConsInte__MUESTR_b'].")";

                                        if($mysqli->query($CamincLsql) === true){

                                        }else{
                                            echo "ERROR CAMINC".$mysqli->error;
                                        } 
                                    }  
                                }

                                // Validamos si ya existen los registros
                                if(isset($_POST['idCamincYa_'.$i]) && $_POST['idCamincYa_'.$i] != '0'){

                                    $idCaminc = $_POST['idCamincYa_'.$i];

                                    // Validamos que existan los datos que se van a sincronizar
                                    if(isset($_POST['datosPob_'.$idCaminc]) && $_POST['datosPob_'.$idCaminc] != '0'){
                                        if(isset($_POST['datosGui_'.$idCaminc]) && $_POST['datosGui_'.$idCaminc] != '0'){

                                            // Miramos el tipo de datos de datosPob
                                            if (is_numeric($_POST['datosPob_'.$idCaminc])) {
                                                $CAMINC_NomCamPob_b = "G".$dataCam['CAMPAN_ConsInte__GUION__Pob_b']."_C".$_POST['datosPob_'.$idCaminc];
                                                $CAMINC_ConsInte__CAMPO_Pob_b = $_POST['datosPob_'.$idCaminc];
                                            }else{
                                                // Si lo que llega es un texto
                                                if (strpos($_POST['datosPob_'.$idCaminc], "_ConsInte__b")) {
                                                    // Validamos si es el id de la bd
                                                    $CAMINC_ConsInte__CAMPO_Pob_b = "-1";
                                                    $CAMINC_NomCamPob_b = "G".$dataCam['CAMPAN_ConsInte__GUION__Pob_b']."_ConsInte__b";
                                                }else if (strpos($_POST['datosPob_'.$idCaminc], "_FechaInsercion")) {
                                                    // Se valida si es la fecha de insercion del registrop
                                                    $CAMINC_ConsInte__CAMPO_Pob_b = "-2";
                                                    $CAMINC_NomCamPob_b = "G".$dataCam['CAMPAN_ConsInte__GUION__Pob_b']."_FechaInsercion";
                                                }
                                            }
                                            
                                            $CamincLsql = "UPDATE ".$BaseDatos_systema.".CAMINC SET CAMINC_ConsInte__CAMPO_Pob_b = ".$CAMINC_ConsInte__CAMPO_Pob_b." , CAMINC_NomCamPob_b = '".$CAMINC_NomCamPob_b."' , CAMINC_TexPrePob_b = '".getTextoPregun($_POST['datosPob_'.$idCaminc])."' , CAMINC_ConsInte__CAMPO_Gui_b =  ".$_POST['datosGui_'.$idCaminc]." , CAMINC_NomCamGui_b = 'G".$dataCam['CAMPAN_ConsInte__GUION__Gui_b']."_C".$_POST['datosGui_'.$idCaminc]."' , CAMINC_TexPreGui_b = '".getTextoPregun($_POST['datosGui_'.$idCaminc])."' , CAMINC_ConsInte__CAMPAN_b = ".$_POST['id']." , CAMINC_ConsInte__GUION__Pob_b = '".$dataCam['CAMPAN_ConsInte__GUION__Pob_b']."' , CAMINC_ConsInte__GUION__Gui_b = '".$dataCam['CAMPAN_ConsInte__GUION__Gui_b']."' ,  CAMINC_ConsInte__MUESTR_b = ".$dataCam['CAMPAN_ConsInte__MUESTR_b']." WHERE CAMINC_ConsInte__b = ".$_POST['idCamincYa_'.$i];
                                            
                                            if($mysqli->query($CamincLsql) === true){

                                            }else{
                                                echo "ERROR CAMINC".$mysqli->error;
                                            } 
                                        }
                                    }  
                                }
                            } 
                        }


                        if(isset($_POST['G10_C76']) && $_POST['G10_C76'] == '6'){
                            $data = array(  
                                "strUsuario_t"              =>  'crm',
                                "strToken_t"                =>  'D43dasd321',
                                "intIdESTPAS_t"         =>  $_POST['id_estpas']
                            );                                                             
                            $data_string = json_encode($data);   
                            echo $data_string; 

                            $ch = curl_init($Api_Gestion.'dyalogocore/api/campanas/voip/sincronizar');
                            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");                                                                     
                            curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string); 
                            curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);
                            curl_setopt($ch, CURLOPT_HTTPHEADER, array(                                                                          
                                'Content-Type: application/json',                                                                                
                                'Content-Length: ' . strlen($data_string))                                                                      
                            ); 
                            $respuesta = curl_exec ($ch);
                            $error = curl_error($ch);
                            curl_close ($ch);
                            echo " Respuesta => ".$respuesta;
                            echo " Error => ".$error;
                            if(!empty($respuesta) && !is_null($respuesta)){
                                $json = json_decode($respuesta);
                                  
                            } 
                        }

                        $PasoLsql = "SELECT ESTPAS_ConsInte__ESTRAT_b FROM ".$BaseDatos_systema.".ESTPAS WHERE ESTPAS_ConsInte__b = ".$_POST['id_paso'];
                        $resPAso = $mysqli->query($PasoLsql);
                        $Estrat = $resPAso->fetch_array();

                        $UpdatePass= "UPDATE ".$BaseDatos_systema.".ESTPAS SET ESTPAS_Comentari_b = '".$_POST['G10_C71']."' WHERE ESTPAS_ConsInte__CAMPAN_b = ".$_POST["id"];
                        if($mysqli->query($UpdatePass) === true){

                        }else{
                            echo "error ESTPAS => ".$mysqli->error;
                        }            
                                            
                        
                        guardar_auditoria("ACTUALIZAR", "ACTUALIZO EL REGISTRO # ".$_POST["id"]." EN G10");
                    }else if($_POST["oper"] == 'del' ){
                       guardar_auditoria("ELIMINAR", "ELIMINO EL REGISTRO # ".$_POST['id']." EN G10");
                    }
                    
                    //insertamos la configuracion del chat si existe DESABILITADO
                    /*
                    if(isset($_POST['Chat']) && 
                        (
                            (isset($_POST['Chat'][1]['activar_chat']) && $_POST['Chat'][1]['activar_chat'] == 'on') ||
                            (isset($_POST['Chat'][2]['activar_chat']) && $_POST['Chat'][2]['activar_chat'] == 'on') ||
                            (isset($_POST['Chat'][3]['activar_chat']) && $_POST['Chat'][3]['activar_chat'] == 'on') 
                        )
                    ){

                        //seteamos las variables de horario
                        $horario_1 = 'Nuestro horario de atención es de ';

                        if(isset($_POST['G10_C126']) && $_POST['G10_C126'] == -1){
                            $dia_final = 7;
                            $horario_3 = 'Domingo ';
                        }else if(isset($_POST['G10_C123']) && $_POST['G10_C123'] == -1){
                            $dia_final = 6;
                            $horario_3 = 'Sabado ';
                        }else if(isset($_POST['G10_C120']) && $_POST['G10_C120'] == -1){
                            $dia_final = 5;
                            $horario_3 = 'Viernes ';
                        }else if(isset($_POST['G10_C120']) && $_POST['G10_C120'] == -1){
                            $dia_final = 4;
                            $horario_3 = 'Jueves ';
                        }else if(isset($_POST['G10_C117']) && $_POST['G10_C117'] == -1){
                            $dia_final = 3;
                            $horario_3 = 'Miercoles ';
                        }else if(isset($_POST['G10_C114']) && $_POST['G10_C114'] == -1){
                            $dia_final = 2;
                            $horario_3 = 'Martes ';
                        }else if(isset($_POST['G10_C108']) && $_POST['G10_C108'] == -1){
                            $dia_final = 1;
                            $horario_3 = 'Lunes ';
                        }

                        if(isset($_POST['G10_C108']) && $_POST['G10_C108'] == -1){
                            $dia_inicial = 1;
                            $momento_incial = $_POST['G10_C109'];
                            $momento_final = $_POST['G10_C110'];
                            $horario_2 = 'Lunes a ';
                        }else if(isset($_POST['G10_C111']) && $_POST['G10_C111'] == -1){
                            $dia_inicial = 2;
                            $momento_incial = $_POST['G10_C112'];
                            $momento_final = $_POST['G10_C113'];
                            $horario_2 = 'Martes a ';
                        }else if(isset($_POST['G10_C114']) && $_POST['G10_C114'] == -1){
                            $dia_inicial = 3;
                            $momento_incial = $_POST['G10_C115'];
                            $momento_final = $_POST['G10_C116'];
                            $horario_2 = 'Miercoles a ';
                        }else if(isset($_POST['G10_C117']) && $_POST['G10_C117'] == -1){
                            $dia_inicial = 4;
                            $momento_incial = $_POST['G10_C118'];
                            $momento_final = $_POST['G10_C119'];
                            $horario_2 = 'Jueves a ';
                        }else if(isset($_POST['G10_C120']) && $_POST['G10_C120'] == -1){
                            $dia_inicial = 5;
                            $momento_incial = $_POST['G10_C121'];
                            $momento_final = $_POST['G10_C122'];
                            $horario_2 = 'Viernes a ';
                        }else if(isset($_POST['G10_C123']) && $_POST['G10_C123'] == -1){
                            $dia_inicial = 6;
                            $momento_incial = $_POST['G10_C124'];
                            $momento_final = $_POST['G10_C1125'];
                            $horario_2 = 'Sabado a ';
                        }else if(isset($_POST['G10_C126']) && $_POST['G10_C126'] == -1){
                            $dia_inicial = 7;
                            $momento_incial = $_POST['G10_C127'];
                            $momento_final = $_POST['G10_C128'];
                            $horario_2 = 'Domingo a ';
                        }

                        $horario_4 = 'de '.$momento_incial.' a '.$momento_final;

                        $frase_fuera_horario = $horario_1.$horario_2.$horario_3.$horario_4;

                        // Recorreoms los chats y validamos si existen
                        $arr_chat = $_POST['Chat'];

                        foreach ($arr_chat as $key => $value) {
                            
                            if(isset($value['tipo_integracion'])){
                                switch ($value['tipo_integracion']) {
                                    case 'web':
                                        
                                        if(isset($value['activar_chat']) && $value['activar_chat'] == 'on'){
                                            // Valido si exite el chat
                                            $Lsql = "SELECT id, id_formulario FROM ".$dyalogo_canales_electronicos.".dy_chat_configuracion WHERE id_campana_crm = ".$_POST['id']." AND integrado_con = 'web'";
                                            $res = $mysqli->query($Lsql);

                                            if($res->num_rows > 0){
                                                $datos = $res->fetch_array();
                                            }

                                            // Si no existe lo creamos
                                            if(!isset($datos) && !isset($datos['id'])){
                                                $formulario = crearFormulario($_POST['G10_C71'], $_POST['G10_C70'], $id_campanas_crm, 'web');
                                                // insertarChat($value, $formulario, $frase_fuera_horario, $id_campanas_crm, $momento_incial, $momento_final, $dia_inicial, $dia_final);
                                            }else{
                                                // Si existe lo actualizamos
                                                // actualizarChat($value, $id_campanas_crm, $momento_incial, $momento_final, $dia_inicial, $dia_final);
                                            }

                                            if(isset($_FILES['logo_web'])){
                                                storeLogoChat($_FILES['logo_web'], $_POST['id']);
                                            }
                                        }else{
                                            // Si no esta checkeado lo elimino
                                            echo "Eliminar WEB";
                                            eliminarChat($id_campanas_crm, 'web');
                                        }
                                        break;
                                    
                                    case 'whatsapp':
                                        
                                        if(isset($value['activar_chat']) && $value['activar_chat'] == 'on'){
                                            // Valido que el chat existe
                                            $Lsql = "SELECT id, id_formulario FROM ".$dyalogo_canales_electronicos.".dy_chat_configuracion WHERE id_campana_crm = ".$_POST['id']." AND integrado_con = 'whatsapp'";
                                            
                                            $res = $mysqli->query($Lsql);
                                            if($res->num_rows > 0){
                                                $datosW = $res->fetch_array();
                                            }

                                            // Si no existe lo creamos
                                            if(!isset($datosW) && !isset($datosW['id'])){
                                                $formulario = crearFormulario($_POST['G10_C71'], $_POST['G10_C70'], $id_campanas_crm, 'whatsapp');
                                                // insertarChat($value, $formulario, $frase_fuera_horario, $id_campanas_crm, $momento_incial, $momento_final, $dia_inicial, $dia_final);
                                            }else{
                                                // Si existe lo actualizamos
                                                // actualizarChat($value, $id_campanas_crm, $momento_incial, $momento_final, $dia_inicial, $dia_final);
                                            }
                                        }else{
                                            // Si no esta checkeado lo elimino
                                            echo "Eliminar WWbr>";
                                            eliminarChat($id_campanas_crm, 'whatsapp');
                                        }
                                        break;
                                    
                                    case 'facebook':
                                        
                                        if(isset($value['activar_chat']) && $value['activar_chat'] == 'on'){
                                            // Valido que el chat existe
                                            $Lsql = "SELECT id, id_formulario FROM ".$dyalogo_canales_electronicos.".dy_chat_configuracion WHERE id_campana_crm = ".$_POST['id']." AND integrado_con = 'facebook'";
                                            
                                            $res = $mysqli->query($Lsql);
                                            if($res->num_rows > 0){
                                                $datosF = $res->fetch_array();
                                            }

                                            // Si no existe lo creamos
                                            if(!isset($datosF) && !isset($datosF['id'])){
                                                $formulario = crearFormulario($_POST['G10_C71'], $_POST['G10_C70'], $id_campanas_crm, 'facebook');
                                                // insertarChat($value, $formulario, $frase_fuera_horario, $id_campanas_crm, $momento_incial, $momento_final, $dia_inicial, $dia_final);
                                            }else{;
                                                // Si existe lo actualizamos
                                                // actualizarChat($value, $id_campanas_crm, $momento_incial, $momento_final, $dia_inicial, $dia_final);
                                            }
                                        }else{
                                            // Si no esta checkeado lo elimino
                                            echo "Eliminar FB>";
                                            eliminarChat($id_campanas_crm, 'facebook');
                                        }
                                        break;

                                    default:
                                        break;
                                }
                            }
                        }                        
                    }else{
                        eliminarChat($id_campanas_crm, 'web');
                        eliminarChat($id_campanas_crm, 'whatsapp');
                        eliminarChat($id_campanas_crm, 'facebook');
                    }*/

                    gestionarChatsCampana($id_campanas_crm);

                    if(isset($_POST['chat_cant_max'])){
                        $maxChat = $_POST['chat_cant_max'];
                        if($maxChat != ''){
                            $usql = "UPDATE ".$BaseDatos_telefonia.".dy_campanas SET max_chats = ".$maxChat." WHERE id_campana_crm = ".$id_campanas_crm;
                            $mysqli->query($usql);
                        }
                    }

                    if(isset($_POST['mails_cant_max'])){
                        $maxMails = $_POST['mails_cant_max'];
                        if($maxMails != ''){
                            $usql = "UPDATE ".$BaseDatos_telefonia.".dy_campanas SET max_correos_electronicos = ".$maxMails." WHERE id_campana_crm = ".$id_campanas_crm;
                            $mysqli->query($usql);
                        }
                    }

                    if(isset($_POST['tiempoMaximoEmailSinGestion'])){
                        $tiempoMaxEmail = $_POST['tiempoMaximoEmailSinGestion'];
                        if($tiempoMaxEmail != ''){
                            $uCamsql = "UPDATE ".$BaseDatos_systema.".CAMPAN SET CAMPAN_CorreoTiempoMaxGes_b = ".$tiempoMaxEmail." WHERE CAMPAN_ConsInte__b = ".$id_campanas_crm;
                            $mysqli->query($uCamsql);
                        }
                    }

                    //Aqui es donde registro el click to call
                    if(isset($_POST['activarClickToCall']) && $_POST['activarClickToCall'] == 'on'){
                        $idcampancbx = obtenerIdcbxCampan($id_campanas_crm);
                        $Lsqlctc = "SELECT * FROM ".$BaseDatos_telefonia.".ctc WHERE id_cola_acd = {$idcampancbx}";
                        // echo "<br><br> sql-> {$Lsqlctc} <br>";
                        $res = $mysqli->query($Lsqlctc);
                        //Verifico si ya existe
                        if($res->num_rows == 0){
                            $isql = "INSERT INTO ".$BaseDatos_telefonia.".ctc (nombre, codigo_html, accion, id_cola_acd, activa, id_huesped) VALUES (
                                '".$_POST['G10_C71']."', '".$_POST['dyTr_ctcHtml']."', '1', '{$idcampancbx}', '1', '".$_SESSION['HUESPED']."'
                            )";

                            $mysqli->query($isql);
                            $id_ctc = $mysqli->insert_id;

                            // echo "<br><br> sql-> {$isql} <br>";
                            // echo "<br><br> idcbx if", $idcampancbx, "<br><br>";

                            persistirCtc($id_ctc);
                        }else{
                            $datos = $res->fetch_array();
                            $usql = "UPDATE {$BaseDatos_telefonia}.ctc SET codigo_html = '{$_POST['dyTr_ctcHtml']}', id_cola_acd = '{$idcampancbx}' WHERE id = '{$datos['id']}'";
                            
                            // echo "<br><br> sql-> {$usql} <br>";
                            // echo "<br><br> idcbx else ", $idcampancbx, "<br><br>";
                            
                            $mysqli->query($usql);
                            persistirCtc($datos['id']);
                        }
                    }else{
                        //Elimiar el click to call
                        echo 'DELETED CTC';
                    }

                    //aqui se inserta, actualiza o elimina segun el check de mail
                    // if(isset($_POST['ActivaMailCampana']) && $_POST['ActivaMailCampana'] == 'on'){

                        // if(isset($id_campanas_crm)){
                        //     $Lsql = "SELECT CAMPAN_IdCamCbx__b FROM ".$BaseDatos_systema.".CAMPAN WHERE CAMPAN_ConsInte__b = ".$id_campanas_crm;
                        //     $res = $mysqli->query($Lsql);
                        //     $datos = $res->fetch_array();
                        //     $id_campanas_cbx = $datos['CAMPAN_IdCamCbx__b'];
                        // }

                        // // Guardo el campo de busqueda 
                        // $usql = "UPDATE ".$BaseDatos_systema.".CAMPAN SET CAMPAN_id_pregun_campo_busqueda_email = ".$_POST['emailCampoBusqueda']." WHERE CAMPAN_ConsInte__b = ".$id_campanas_crm;
                        // $mysqli->query($usql);

                        // $emailCuenta = $_POST['mailCuentaCorreo'];

                        // // Verifico si hay nuevas condiciones a insertar
                        // if(isset($_POST['contNuevaCondicionCorreo']) && $_POST['contNuevaCondicionCorreo'] > 0){
                        //     for ($i=0; $i < $_POST['contNuevaCondicionCorreo']; $i++) {                                 

                        //         if($_POST['MailTipoCondicion_'.$i] != 100){
                        //             $condicion = $_POST['MailCondicion_'.$i];
                        //         }else{
                        //             $condicion = '';
                        //         }

                        //         //Verifico si trae el operador
                        //         $operador = $_POST['operador_'.$i] ?? NULL;

                        //         // Insertamos los nuevos filtros de email
                        //         $Lsql = "INSERT INTO ".$dyalogo_canales_electronicos.".dy_ce_filtros (id_ce_configuracion, filtro, condicion, descargar_adjuntos, directorio_adjuntos, id_campana_crm, operador) VALUES ('".$emailCuenta."','".$_POST['MailTipoCondicion_'.$i]."','".$condicion."',1,'/mnt/disks/grabaciones/adjuntos/', ".$id_campanas_crm.", '".$operador."')";
                        //         $mysqli->query($Lsql);

                        //         $id_filtro = $mysqli->insert_id;
                                
                        //     }
                        // }

                        // // Actualizo los que ya estan creados con anterioridad
                        // if(isset($_POST['condLis'])){
                        //     foreach($_POST['condLis'] as $key){

                        //         if($_POST['MailTipoCondicion_'.$key] != 100){
                        //             $condicion = $_POST['MailCondicion_'.$key];
                        //         }else{
                        //             $condicion = '';
                        //         }

                        //         $operador = $_POST['operador_'.$key] ?? NULL;

                        //         $Lsql = "UPDATE ".$dyalogo_canales_electronicos.".dy_ce_filtros SET id_ce_configuracion = ".$emailCuenta.", filtro = ".$_POST['MailTipoCondicion_'.$key].", condicion = '".$condicion."', descargar_adjuntos = 1, operador = '".$operador."' WHERE id = ".$key;
                        //         $mysqli->query($Lsql);
                        //     }
                            
                        // }

                        // // Valido de que almenos exista un filtro 
                        // $filtroSql = "SELECT id, id_ce_configuracion FROM ".$dyalogo_canales_electronicos.".dy_ce_filtros WHERE id_campana_crm = ".$id_campanas_crm." LIMIT 1";
                        // $resFiltro = $mysqli->query($filtroSql);

                        // $firmaIdCeCongfig = 0;

                        // if($resFiltro->num_rows > 0){

                        //     $filtro = $resFiltro->fetch_array();
                        //     $firmaIdCeCongfig = $filtro['id_ce_configuracion'];

                        //     // Inserto la accion_filtro si aun no se ha creado
                        //     $Lsql = "SELECT * FROM ".$dyalogo_canales_electronicos.".dy_ce_acciones_filtro WHERE accion = 2 AND id_cola_distribucion = ".$id_campanas_cbx;
                        //     $respuesta = $mysqli->query($Lsql);
                            
                        //     if($respuesta->num_rows == 0){
                        //         $orden = ordenMax($filtro['id_ce_configuracion']);
                        //         $Lsql = "INSERT INTO ".$dyalogo_canales_electronicos.".dy_ce_acciones_filtro (id_filtro, orden, accion, id_cola_distribucion) VALUES (".$filtro['id'].",".$orden.",2,".$id_campanas_cbx.")";
                        //         $mysqli->query($Lsql);
                        //     }

                        //     // Inserto El mensaje de bienvenida si esta cheackeado
                        //     if(isset($_POST['configMensajeBienvenida']) && $_POST['configMensajeBienvenida'] == 'on'){
                                
                        //         // Insercion o actualizacion
                        //         $accionLsql = "SELECT * FROM ".$dyalogo_canales_electronicos.".dy_ce_acciones_filtro WHERE accion = 1 AND id_cola_distribucion = ".$id_campanas_cbx;
                        //         $resAccion = $mysqli->query($accionLsql);

                        //         if($resAccion->num_rows == 0){
                        //             $orden = ordenMax($filtro['id_ce_configuracion']);
                        //             $Isql = "INSERT INTO ".$dyalogo_canales_electronicos.".dy_ce_acciones_filtro (id_filtro, orden, accion, id_cola_distribucion, cuerpo) VALUES (".$filtro['id'].",".$orden.",1,".$id_campanas_cbx.", '".$_POST['dyTr_correoMensajeBienvenida']."')";
                        //             $mysqli->query($Isql);
                        //         }else{
                        //             $Usql = "UPDATE ".$dyalogo_canales_electronicos.".dy_ce_acciones_filtro SET cuerpo = '".$_POST['dyTr_correoMensajeBienvenida']."' WHERE accion = 1 AND id_cola_distribucion = ".$id_campanas_cbx;
                        //             $mysqli->query($Usql);
                        //         }
                        //     }else{
                        //         // Eliminacion
                        //         $Dsql = "DELETE FROM ".$dyalogo_canales_electronicos.".dy_ce_acciones_filtro WHERE accion = 1 AND id_cola_distribucion = ".$id_campanas_cbx;
                        //         $mysqli->query($Dsql);
                        //     }

                        // }

                        if(isset($id_campanas_crm)){
                            $Lsql = "SELECT CAMPAN_IdCamCbx__b FROM ".$BaseDatos_systema.".CAMPAN WHERE CAMPAN_ConsInte__b = ".$id_campanas_crm;
                            $res = $mysqli->query($Lsql);
                            $datos = $res->fetch_array();
                            $id_campanas_cbx = $datos['CAMPAN_IdCamCbx__b'];
                        }

                        // Inserto la firma si esta checkeado
                        if(isset($_POST['configFirma']) && $_POST['configFirma'] == 'on'){

                            // Insercion o actualizacion
                            $firmaLsql = "SELECT * FROM ".$dyalogo_canales_electronicos.".dy_ce_firmas WHERE id_cola_distribucion = ".$id_campanas_cbx;
                            $resFirma = $mysqli->query($firmaLsql);

                            // Valido de que almenos exista un filtro 
                            $filtroSql = "SELECT id, id_ce_configuracion FROM ".$dyalogo_canales_electronicos.".dy_ce_filtros WHERE id_campana_crm = ".$id_campanas_crm." LIMIT 1";
                            $resFiltro = $mysqli->query($filtroSql);
                            
                            if($resFiltro && $resFiltro->num_rows > 0){
                                $filtro = $resFiltro->fetch_array();
                                $firmaIdCeCongfig = $filtro['id_ce_configuracion'];
                                
                                if($resFirma->num_rows == 0){
                                    $Isql = "INSERT INTO ".$dyalogo_canales_electronicos.".dy_ce_firmas (id_cola_distribucion, firma, id_ce_configuracion) VALUES (".$id_campanas_cbx.", '".$_POST['dyTr_firmaCorreo']."', ".$firmaIdCeCongfig.")";
                                    $mysqli->query($Isql);
                                }else{
                                    $Usql = "UPDATE ".$dyalogo_canales_electronicos.".dy_ce_firmas SET firma = '".$_POST['dyTr_firmaCorreo']."', id_ce_configuracion = ".$firmaIdCeCongfig." WHERE id_cola_distribucion = ".$id_campanas_cbx;
                                    $mysqli->query($Usql);
                                }
                            }
                            
                        }else{
                            $Dsql = "DELETE FROM ".$dyalogo_canales_electronicos.".dy_ce_firmas WHERE id_cola_distribucion = ".$id_campanas_cbx;
                            $mysqli->query($Dsql);
                        }

                    // }else{

                    //     $Lsql = "SELECT id FROM ".$dyalogo_canales_electronicos.".dy_ce_filtros WHERE id_campana_crm = ".$id_campanas_crm;
                    //     $res = $mysqli->query($Lsql);
                    //     if($res->num_rows > 0 ){
                    //         while($key = $res->fetch_object()) {

                    //             $Lsql = "DELETE FROM ".$dyalogo_canales_electronicos.".dy_ce_acciones_filtro WHERE id_filtro = ".$key->id;
                    //             $mysqli->query($Lsql);

                    //             $Lsql = "DELETE FROM ".$dyalogo_canales_electronicos.".dy_ce_filtros WHERE id = ".$key->id;
                    //             $mysqli->query($Lsql);

                    //         }
                    //     }
                                          
                    // }

                    echo $id_usuario;
                } else {
                    echo "Error Haciendo el proceso los registros sQL DE INSERTAR: " . $mysqli->error;
                }
            }
        }

        //Mostrar dtaos de por donde salen las llamadas
        if(isset($_GET["callDatosSubgrilla_0"])){

            $id = $_GET['id'];  
            $numero = $id;
                
            $SQL = "SELECT pasos_troncales.id as id ,tipos_destino.nombre as tipos_destino, a.nombre_interno as troncal, b.nombre_interno as troncal_desborde ";
            $SQL .= " FROM ".$BaseDatos_telefonia.".pasos_troncales ";
            $SQL .= " LEFT JOIN ".$BaseDatos_telefonia.".tipos_destino ON tipos_destino.id = pasos_troncales.id_tipos_destino ";
            $SQL .= " LEFT JOIN ".$BaseDatos_telefonia.".dy_troncales a ON a.id = pasos_troncales.id_troncal ";
            $SQL .= " LEFT JOIN ".$BaseDatos_telefonia.".dy_troncales b ON b.id = pasos_troncales.id_troncal_desborde ";
            $SQL .= " WHERE pasos_troncales.id_campana = ".$numero;
            $SQL .= " ORDER BY tipos_destino.nombre";

          //  echo $SQL;
            if ( stristr($_SERVER["HTTP_ACCEPT"],"application/xhtml+xml") ) { 
                header("Content-type: application/xhtml+xml;charset=utf-8"); 
            } else { 
                header("Content-type: text/xml;charset=utf-8"); 
            } 

            $et = ">"; 
            echo "<?xml version='1.0' encoding='utf-8'?$et\n"; 
            echo "<rows>"; // be sure to put text data in CDATA

            $result = $mysqli->query($SQL);
            while( $fila = $result->fetch_object() ) {
                echo "<row asin='".$fila->id."'>"; 
                echo "<cell>". ($fila->id)."</cell>"; 
                echo "<cell>". utf8_encode($fila->tipos_destino)."</cell>"; 
                echo "<cell>". utf8_encode($fila->troncal)."</cell>";
                echo "<cell>". utf8_encode($fila->troncal_desborde)."</cell>"; 
                echo "</row>"; 
            } 
            echo "</rows>"; 
        }

        //Llenar datos de por donde salen las llamadas
        if(isset($_GET["insertarDatosSubgrilla_0"])){
        
            if(isset($_POST["oper"])){
                $Lsql  = '';

                $validar = 0;
                $LsqlU = "UPDATE ".$BaseDatos_telefonia.".pasos_troncales SET "; 
                $LsqlI = "INSERT INTO ".$BaseDatos_telefonia.".pasos_troncales(";
                $LsqlV = " VALUES ("; 
     
                                                                             
                if(isset($_POST["tipos_destino"])){
                    if($_POST["tipos_destino"] != '0'){
                        $separador = "";
                        if($validar == 1){
                            $separador = ",";
                        }

                        $LsqlU .= $separador."id_tipos_destino = '".$_POST["tipos_destino"]."'";
                        $LsqlI .= $separador."id_tipos_destino";
                        $LsqlV .= $separador."'".$_POST["tipos_destino"]."'";
                        $validar = 1;
                    }   
                }
                                                                              
                                                                               
     
                $troncal= NULL;
                //este es de tipo numero no se deja ir asi '', si est avacio lo mejor es no mandarlo
                if(isset($_POST["troncal"])){    
                    if($_POST["troncal"] != '0'){
                        $separador = "";
                        if($validar == 1){
                            $separador = ",";
                        }

                        $troncal = $_POST["troncal"];
                        $LsqlU .= $separador."id_troncal = '".$troncal."'";
                        $LsqlI .= $separador."id_troncal";
                        $LsqlV .= $separador."'".$troncal."'";
                        $validar = 1;
                    }
                }
     
                $troncal_desborde= NULL;
                //este es de tipo numero no se deja ir asi '', si est avacio lo mejor es no mandarlo
                if(isset($_POST["troncal_desborde"])){    
                    if($_POST["troncal_desborde"] != '0'){
                        $separador = "";
                        if($validar == 1){
                            $separador = ",";
                        }

                        $troncal_desborde = $_POST["troncal_desborde"];
                        $LsqlU .= $separador." id_troncal_desborde = '".$troncal_desborde."'";
                        $LsqlI .= $separador." id_troncal_desborde";
                        $LsqlV .= $separador."'".$troncal_desborde."'";
                        $validar = 1;
                    }
                }
     
               

                if(isset($_POST["Padre"])){
                    if($_POST["Padre"] != ''){
                        //esto es porque el padre es el entero
                        
                        $numero = $_POST["Padre"];
             

                        $G4_C23 = $numero;
                        $LsqlU .= ", id_campana = ".$G4_C23."";
                        $LsqlI .= ", id_campana";
                        $LsqlV .= ",".$_POST["Padre"];
                    }
                } 

                if(isset($_GET["Id_paso"])){
                    if($_GET["Id_paso"] != ''){
                        //esto es porque el padre es el entero
                        
                        $numero = $_GET["Id_paso"];
             

                        $G4_C23 = $numero;
                        $LsqlU .= ", id_estpas = ".$G4_C23."";
                        $LsqlI .= ", id_estpas";
                        $LsqlV .= ",".$_GET["Id_paso"];
                    }
                }  



                if(isset($_POST['oper'])){
                    if($_POST["oper"] == 'add' ){
                        $Lsql = $LsqlI.")" . $LsqlV.")";
                    }else if($_POST["oper"] == 'edit' ){
                        $Lsql = $LsqlU." WHERE id =".$_POST["id"]; 
                    }else if($_POST['oper'] == 'del'){
                        $Lsql = "DELETE FROM  ".$BaseDatos_telefonia.".pasos_troncales WHERE id = ".$_POST['id'];
                        $validar = 1;
                    }
                }

                if($validar == 1){
                    // echo $Lsql;
                        $id_Usuario_Nuevo = 1;
                    if ($mysqli->query($Lsql) === TRUE) {
                        $id_Usuario_Nuevo = $mysqli->insert_id;

                        if($_POST["oper"] == 'add' ){
                            guardar_auditoria("INSERTAR", "INSERTAR REGISTRO EN pasos_troncales");
                        }else if($_POST["oper"] == 'edit' ){
                            guardar_auditoria("ACTUALIZAR", "ACTUALIZO EL REGISTRO # ".$_POST["padre"]." EN pasos_troncales");
                        }else if($_POST["oper"] == 'del' ){
                           guardar_auditoria("ELIMINAR", "ELIMINO EL REGISTRO # ".$_POST['id']." EN pasos_troncales");
                        }

                        echo $id_Usuario_Nuevo ;
                    } else {
                        echo "Error Hacieno el proceso los registros : " . $mysqli->error;
                    }  
                }  
            }
        }

        //Llenar datos de CAMCON
        if(isset($_GET["callDatosSubgrilla_camcom"])){

            $id = $_GET['id'];  
            $numero = $id;
                
            $SQL = "SELECT G19_ConsInte__b, PREGUN_Texto_____b as G19_C202, G19_C203 FROM ".$BaseDatos_systema.".G19 JOIN ".$BaseDatos_systema.".PREGUN ON PREGUN_ConsInte__b = G19_C202 WHERE G19_C200 = ".$id;
            //$SQL .= " WHERE pasos_troncales.id_campana = ".$numero;
            $SQL .= " ORDER BY G19_C203;";

            //echo $SQL;
            if ( stristr($_SERVER["HTTP_ACCEPT"],"application/xhtml+xml") ) { 
                header("Content-type: application/xhtml+xml;charset=utf-8"); 
            } else { 
                header("Content-type: text/xml;charset=utf-8"); 
            } 

            $et = ">"; 
            echo "<?xml version='1.0' encoding='utf-8'?$et\n"; 
            echo "<rows>"; // be sure to put text data in CDATA

            $result = $mysqli->query($SQL);
            while( $fila = $result->fetch_object() ) {
                echo "<row asin='".$fila->G19_ConsInte__b."'>"; 
                echo "<cell>". ($fila->G19_ConsInte__b)."</cell>"; 
                echo "<cell>". ($fila->G19_C202)."</cell>"; 
                echo "<cell>". ($fila->G19_C203)."</cell>";
                echo "</row>"; 
            } 
            echo "</rows>"; 
        }

        //insertar en CAMCON
        if(isset($_GET["insertarDatosCamcom"])){
        
            if(isset($_POST["oper"])){
                $Lsql  = '';

                $validar = 0;
                $LsqlU = "UPDATE ".$BaseDatos_systema.".CAMCON SET "; 
                $LsqlI = "INSERT INTO ".$BaseDatos_systema.".CAMCON(";
                $LsqlV = " VALUES ("; 
     
                                                                             
                if(isset($_POST["G19_C202"])){
                    $separador = "";
                    if($validar == 1){
                        $separador = ",";
                    }

                    $LsqlU .= $separador."CAMCON_ConsInte__PREGUN_b = '".$_POST["G19_C202"]."'";
                    $LsqlI .= $separador."CAMCON_ConsInte__PREGUN_b";
                    $LsqlV .= $separador."'".$_POST["G19_C202"]."'";
                    $validar = 1;

    

                    $LsqlU .= ", CAMCON_ConsInte__CAMPO__Pob_b = '".$_POST["G19_C202"]."'";
                    $LsqlI .= ", CAMCON_ConsInte__CAMPO__Pob_b ";
                    $LsqlV .= " , '".$_POST["G19_C202"]."'";


                    $Lsql = "SELECT PREGUN_Texto_____b, PREGUN_ConsInte__GUION__b FROM ".$BaseDatos_systema.".PREGUN WHERE PREGUN_ConsInte__b = ".$_POST["G19_C202"];
                    $res = $mysqli->query($Lsql);
                    $dataPre = $res->fetch_array();

                    

                }
                                                                              
                                                                               
     
                $G19_C203= NULL;


        
               

                if(isset($_POST["Padre"])){
                    if($_POST["Padre"] != ''){
                        //esto es porque el padre es el entero
                        $separador = "";
                        if($validar == 1){
                            $separador = ",";
                        }

                        $numero = $_POST["Padre"];
             

                        $Padre = $numero;
                        $LsqlU .= ", CAMCON_ConsInte__CAMPAN_b = ".$Padre."";
                        $LsqlI .= ", CAMCON_ConsInte__CAMPAN_b";
                        $LsqlV .= ",".$_POST["Padre"];


                        $Lsql_Campan = "SELECT CAMPAN_ConsInte__GUION__Pob_b, CAMPAN_ConsInte__MUESTR_b, CAMPAN_ConsInte__GUION__Gui_b  FROM ".$BaseDatos_systema.".CAMPAN WHERE CAMPAN_ConsInte__b = ".$_POST["Padre"];
                        $res_Lsql_Campan = $mysqli->query($Lsql_Campan);
                        $datoCampan = $res_Lsql_Campan->fetch_array();
                        $str_Pobla_Campan = "G".$datoCampan['CAMPAN_ConsInte__GUION__Pob_b'];
                        $int_Pobla_Camp_2 = $datoCampan['CAMPAN_ConsInte__GUION__Pob_b'];
                        $int_Muest_Campan = $datoCampan['CAMPAN_ConsInte__MUESTR_b'];
                        $int_Guion_Campan = $datoCampan['CAMPAN_ConsInte__GUION__Gui_b'];


                       
                        $LsqlU .= ", CAMCON_ConsInte__GUION__Pob_b = ".$int_Pobla_Camp_2.", CAMCON_ConsInte__GUION__Gui_b = ".$int_Guion_Campan." , CAMCON_ConsInte__MUESTR_b = ".$int_Muest_Campan;
                        $LsqlI .= ", CAMCON_ConsInte__GUION__Pob_b,  CAMCON_ConsInte__GUION__Gui_b , CAMCON_ConsInte__MUESTR_b";
                        $LsqlV .= ",".$int_Pobla_Camp_2." , ".$int_Guion_Campan." , ".$int_Muest_Campan;
                        
                        $validar = 1;
                        if($validar == 1){
                            $separador = ",";
                        } 

                        if(isset($_POST['G19_C203'])){
                            
                            $G19_C203 = $_POST['G19_C203'];
                            $LsqlU .= $separador."CAMCON_Orden_____b = '".$G19_C203."'";
                            $LsqlI .= $separador."CAMCON_Orden_____b";
                            $LsqlV .= $separador."'".$G19_C203."'";

                        }else{
                            $str_OrderSql = "SELECT MAX(CAMCON_Orden_____b) as max FROM ".$BaseDatos_systema.".CAMCON WHERE CAMCON_ConsInte__CAMPAN_b = ".$_POST["Padre"];
                            
                            $res_OrderSql = $mysqli->query($str_OrderSql);
                            $datos = $res_OrderSql->fetch_array();
                            $G19_C203 = ($datos["max"] + 1);
                            $LsqlU .= $separador."CAMCON_Orden_____b = '".$G19_C203."'";
                            $LsqlI .= $separador."CAMCON_Orden_____b";
                            $LsqlV .= $separador."'".$G19_C203."'";

                        }


                        
                        

                    }
                }  




                if(isset($_POST['oper'])){
                    if($_POST["oper"] == 'add' ){
                        $Lsql = $LsqlI.")" . $LsqlV.")";
                    }else if($_POST["oper"] == 'edit' ){
                        $Lsql = $LsqlU." WHERE CAMCON_ConsInte__b =".$_POST["id"]; 
                    }else if($_POST['oper'] == 'del'){
                        $Lsql = "DELETE FROM  ".$BaseDatos_systema.".CAMCON WHERE CAMCON_ConsInte__b = ".$_POST['id'];
                        $validar = 1;
                    }
                }

                if($validar == 1){
                    echo $Lsql;
                    $id_Usuario_Nuevo = 1;
                    if ($mysqli->query($Lsql) === TRUE) {
                        $id_Usuario_Nuevo = $mysqli->insert_id;

                        if($_POST["oper"] == 'add' ){
                            guardar_auditoria("INSERTAR", "INSERTAR REGISTRO EN G19");
                        }else if($_POST["oper"] == 'edit' ){
                            guardar_auditoria("ACTUALIZAR", "ACTUALIZO EL REGISTRO # ".$_POST["id"]." EN G19");
                        }else if($_POST["oper"] == 'del' ){
                           guardar_auditoria("ELIMINAR", "ELIMINO EL REGISTRO # ".$_POST['id']." EN G19");
                        }

                        echo $id_Usuario_Nuevo ;
                    } else {
                        echo "Error Hacieno el proceso los registros : " . $mysqli->error;
                    }  
                }  
            }
        }

        //Llenar la tabla de CAMINC
        if(isset($_GET["callDatosSubgrilla_caminc"])){

            $id = $_GET['id'];  
            $numero = $id;
                
            $SQL = "SELECT * FROM ".$BaseDatos_systema.".CAMINC WHERE CAMINC_ConsInte__CAMPAN_b = ".$numero;
            //$SQL .= " WHERE pasos_troncales.id_campana = ".$numero;
            $SQL .= " ORDER BY CAMINC_TexPrePob_b;";

            //echo $SQL;
            if ( stristr($_SERVER["HTTP_ACCEPT"],"application/xhtml+xml") ) { 
                header("Content-type: application/xhtml+xml;charset=utf-8"); 
            } else { 
                header("Content-type: text/xml;charset=utf-8"); 
            } 

            $et = ">"; 
            echo "<?xml version='1.0' encoding='utf-8'?$et\n"; 
            echo "<rows>"; // be sure to put text data in CDATA

            $result = $mysqli->query($SQL);
            while( $fila = $result->fetch_object() ) {
                echo "<row asin='".$fila->CAMINC_ConsInte__b."'>"; 
                echo "<cell>". ($fila->CAMINC_ConsInte__b)."</cell>"; 
                echo "<cell>". ($fila->CAMINC_TexPrePob_b)."</cell>"; 
                echo "<cell>". ($fila->CAMINC_TexPreGui_b)."</cell>";
                echo "</row>"; 
            } 
            echo "</rows>"; 
        }

        //Insertar Dtaos Caminc
        if(isset($_GET['insertarDatosCaminc'])){
            if(isset($_POST["oper"])){
                $Lsql  = '';

                $validar = 0;
                $LsqlU = "UPDATE ".$BaseDatos_systema.".CAMINC SET "; 
                $LsqlI = "INSERT INTO ".$BaseDatos_systema.".CAMINC(";
                $LsqlV = " VALUES ("; 
     
                                                                             
                if(isset($_POST["CAMINC_ConsInte__CAMPO_Pob_b"])){
                    $separador = "";
                    if($validar == 1){
                        $separador = ",";
                    }

                    $LsqlU .= $separador."CAMINC_ConsInte__CAMPO_Pob_b = '".$_POST["CAMINC_ConsInte__CAMPO_Pob_b"];
                    $LsqlI .= $separador."CAMINC_ConsInte__CAMPO_Pob_b";
                    $LsqlV .= $separador."'".$_POST["CAMINC_ConsInte__CAMPO_Pob_b"]."'";

                    $validar = 1;

                    $OpcionSQl = "SELECT * FROM ".$BaseDatos_systema.".PREGUN WHERE PREGUN_ConsInte__b = ".$_POST["CAMINC_ConsInte__CAMPO_Pob_b"];

                    $res_OpcionSql = $mysqli->query($OpcionSQl);
                    while ($key = $res_OpcionSql->fetch_object()) {
                        //OPCION_ConsInte__GUION__b
                        //OPCION_Nombre____b

                        if($validar == 1){
                            $separador = ",";
                        }

                        $LsqlU .= $separador."CAMINC_NomCamPob_b = 'G".$key->PREGUN_ConsInte__GUION__b."_C".$key->PREGUN_ConsInte__b."' , CAMINC_TexPrePob_b = '".$key->PREGUN_Texto_____b."'";
                        $LsqlI .= $separador."CAMINC_NomCamPob_b , CAMINC_TexPrePob_b";
                        $LsqlV .= $separador."'G".$key->PREGUN_ConsInte__GUION__b."_C".$key->PREGUN_ConsInte__b."' , '".$key->PREGUN_Texto_____b."'";
                    }

                    
                }
                                                                              
                                                                               
     
                $G19_C203= NULL;
                //este es de tipo numero no se deja ir asi '', si est avacio lo mejor es no mandarlo
                if(isset($_POST["CAMINC_ConsInte__CAMPO_Gui_b"])){    
                    if($_POST["CAMINC_ConsInte__CAMPO_Gui_b"] != ''){
                        $separador = "";
                        if($validar == 1){
                            $separador = ",";
                        }

                        $G19_C203 = $_POST["CAMINC_ConsInte__CAMPO_Gui_b"];
                        $LsqlU .= $separador."CAMINC_ConsInte__CAMPO_Gui_b = '".$G19_C203."'";
                        $LsqlI .= $separador."CAMINC_ConsInte__CAMPO_Gui_b";
                        $LsqlV .= $separador."'".$G19_C203."'";
                        $validar = 1;

                        $OpcionSQl = "SELECT * FROM ".$BaseDatos_systema.".PREGUN WHERE PREGUN_ConsInte__b = ".$_POST["CAMINC_ConsInte__CAMPO_Gui_b"];
                        $res_OpcionSql = $mysqli->query($OpcionSQl);
                        while ($key = $res_OpcionSql->fetch_object()) {
                            //OPCION_ConsInte__GUION__b
                            //OPCION_Nombre____b
                            if($validar == 1){
                                $separador = ",";
                            }

                            $LsqlU .= $separador."CAMINC_NomCamGui_b = 'G".$key->PREGUN_ConsInte__GUION__b."_C".$key->PREGUN_ConsInte__b."' , CAMINC_TexPreGui_b = '".$key->PREGUN_Texto_____b."'";
                            $LsqlI .= $separador."CAMINC_NomCamGui_b , CAMINC_TexPreGui_b";
                            $LsqlV .= $separador."'G".$key->PREGUN_ConsInte__GUION__b."_C".$key->PREGUN_ConsInte__b."' , '".$key->PREGUN_Texto_____b."'";
                        }

                        
                    }
                }
        
               

                if(isset($_POST["Padre"])){
                    if($_POST["Padre"] != ''){
                        //esto es porque el padre es el entero
                        
                        $numero = $_POST["Padre"];
             

                        $Padre = $numero;
                        $LsqlU .= ", CAMINC_ConsInte__CAMPAN_b = ".$Padre."";
                        $LsqlI .= ", CAMINC_ConsInte__CAMPAN_b";
                        $LsqlV .= ",".$_POST["Padre"];


                        $XSql = "SELECT G10_C73 as CAMPAN_ConsInte__GUION__Gui_b , G10_C74 as CAMPAN_ConsInte__GUION__Pob_b , G10_C75 as CAMPAN_ConsInte__MUESTR_b FROM ".$BaseDatos_systema.".G10 WHERE G10_ConsInte__b = ".$Padre;
                        $res_XSql = $mysqli->query($XSql);
                        while($key = $res_XSql->fetch_object()){
                            $LsqlU .= ", CAMINC_ConsInte__GUION__Pob_b = ".$key->CAMPAN_ConsInte__GUION__Pob_b.", CAMINC_ConsInte__GUION__Gui_b = ".$key->CAMPAN_ConsInte__GUION__Gui_b." , CAMINC_ConsInte__MUESTR_b = ".$key->CAMPAN_ConsInte__MUESTR_b;
                            $LsqlI .= ", CAMINC_ConsInte__GUION__Pob_b, CAMINC_ConsInte__GUION__Gui_b, CAMINC_ConsInte__MUESTR_b ";
                            $LsqlV .= ",".$key->CAMPAN_ConsInte__GUION__Pob_b." , ".$_GET['script']." , ".$key->CAMPAN_ConsInte__MUESTR_b;
                        }

                    }
                }  


                if(isset($_POST['oper'])){
                    if($_POST["oper"] == 'add' ){
                        $Lsql = $LsqlI.")" . $LsqlV.")";
                    }else if($_POST["oper"] == 'edit' ){
                        $Lsql = $LsqlU." WHERE CAMINC_ConsInte__b =".$_POST["id"]; 
                    }else if($_POST['oper'] == 'del'){
                        $Lsql = "DELETE FROM  ".$BaseDatos_systema.".CAMINC WHERE CAMINC_ConsInte__b = ".$_POST['id'];
                        $validar = 1;
                    }
                }

                if($validar == 1){
                    echo $Lsql;
                    $id_Usuario_Nuevo = 1;
                    if ($mysqli->query($Lsql) === TRUE) {
                        $id_Usuario_Nuevo = $mysqli->insert_id;

                        if($_POST["oper"] == 'add' ){
                            guardar_auditoria("INSERTAR", "INSERTAR REGISTRO EN CAMINC");
                        }else if($_POST["oper"] == 'edit' ){
                            guardar_auditoria("ACTUALIZAR", "ACTUALIZO EL REGISTRO # ".$_POST["id"]." EN CAMINC");
                        }else if($_POST["oper"] == 'del' ){
                           guardar_auditoria("ELIMINAR", "ELIMINO EL REGISTRO # ".$_POST['id']." EN CAMINC");
                        }

                        echo $id_Usuario_Nuevo ;
                    } else {
                        echo "Error Hacieno el proceso los registros : " . $mysqli->error;
                    }  
                }  
            }
        }


        if(isset($_GET['CallDatosCombo_tipos_destino'])){
            $Ysql = 'SELECT   id , nombre FROM '.$BaseDatos_telefonia.'.tipos_destino WHERE id_huesped = '.$_SESSION['HUESPED'];
            $guion = $mysqli->query($Ysql);
            echo '<select class="form-control input-sm"  name="tipos_destino" id="tipos_destino">';
            echo '<option value="0">Seleccione</option>';
            while($obj = $guion->fetch_object()){
               echo "<option value='".$obj->id."' dinammicos='0'>".utf8_encode($obj->nombre)."</option>";
            } 
            echo '</select>';
        }


        if(isset($_GET['CallDatosCombo_troncales'])){
            $HSlq = "SELECT id FROM ".$BaseDatos_telefonia.".dy_proyectos WHERE id_huesped = ".$_SESSION['HUESPED'];
            $res= $mysqli->query($HSlq);
            $data = $res->fetch_array();
            $Ysql = 'SELECT  id , nombre_interno FROM '.$BaseDatos_telefonia.'.dy_troncales WHERE id_proyecto ='.$data['id'].' ORDER BY nombre_interno ASC'; // WHERE id_proyecto = '.$_SESSION['HUESPED'];
            $guion = $mysqli->query($Ysql);
            echo '<select class="form-control input-sm"  name="troncal" id="troncal">';
            echo '<option value="0">Seleccione</option>';
            while($obj = $guion->fetch_object()){
               echo "<option value='".$obj->id."' dinammicos='0'>".utf8_encode($obj->nombre_interno)."</option>";
            } 
            echo '</select>';
        }


        if(isset($_GET['CallDatosCombo_troncales2'])){
            $HSlq = "SELECT id FROM ".$BaseDatos_telefonia.".dy_proyectos WHERE id_huesped = ".$_SESSION['HUESPED'];
            $res= $mysqli->query($HSlq);
            $data = $res->fetch_array();
            $Ysql = 'SELECT  id , nombre_interno FROM '.$BaseDatos_telefonia.'.dy_troncales WHERE id_proyecto ='.$data['id'].' ORDER BY nombre_interno ASC'; // WHERE id_proyecto = '.$_SESSION['HUESPED'];
            $guion = $mysqli->query($Ysql);
            echo '<select class="form-control input-sm"  name="troncal_desborde" id="troncal_desborde">';
            echo '<option value="0">Seleccione</option>';
            while($obj = $guion->fetch_object()){
               echo "<option value='".$obj->id."' dinammicos='0'>".utf8_encode($obj->nombre_interno)."</option>";
            } 
            echo '</select>';
        }

        //Llenar la tabla de CAMINC
        if(isset($_GET["callDatosSubgrilla_camord"])){

            $id = $_GET['id'];  
            $numero = $id;
                
            $SQL = "SELECT CAMORD_CONSINTE__B, CASE CAMORD_MUESPOBL__B WHEN 'M' THEN 'De la campaña' WHEN 'P' THEN 'De la base de datos' END AS  CAMORD_MUESPOBL__B, CAMORD_POBLCAMP__B, CASE CAMORD_MUESCAMP__B WHEN '_Estado____b' THEN 'Estado' WHEN '_FecUltGes_b' THEN 'Fecha ultima gestión' WHEN '_NumeInte__b' THEN 'Cantidad de intentos' WHEN '_UltiGest__b' THEN 'Ultima gestión' END AS CAMORD_MUESCAMP__B, CAMORD_PRIORIDAD_B, CASE CAMORD_ORDEN_____B WHEN 'ASC' THEN 'ASCENDENTE' WHEN 'DESC' THEN 'DESCENDENTE' END AS CAMORD_ORDEN_____B FROM ".$BaseDatos_systema.".CAMORD WHERE CAMORD_CONSINTE__CAMPAN_B = ".$numero;
            //$SQL .= " WHERE pasos_troncales.id_campana = ".$numero;
            $SQL .= " ORDER BY CAMORD_PRIORIDAD_B;";

            //echo $SQL;
            if ( stristr($_SERVER["HTTP_ACCEPT"],"application/xhtml+xml") ) { 
                header("Content-type: application/xhtml+xml;charset=utf-8"); 
            } else { 
                header("Content-type: text/xml;charset=utf-8"); 
            } 

            $et = ">"; 
            echo "<?xml version='1.0' encoding='utf-8'?$et\n"; 
            echo "<rows>"; // be sure to put text data in CDATA

            $result = $mysqli->query($SQL);
            while( $fila = $result->fetch_object() ) {
                echo "<row asin='".$fila->CAMORD_CONSINTE__B."'>"; 
                echo "<cell>". ($fila->CAMORD_CONSINTE__B)."</cell>";
                echo "<cell>". ($fila->CAMORD_MUESPOBL__B)."</cell>"; 
                echo "<cell>". ($fila->CAMORD_POBLCAMP__B)."</cell>";
                echo "<cell>". ($fila->CAMORD_MUESCAMP__B)."</cell>";
                echo "<cell>". ($fila->CAMORD_PRIORIDAD_B)."</cell>";
                echo "<cell>". ($fila->CAMORD_ORDEN_____B)."</cell>";
                
                echo "</row>"; 
            } 
            echo "</rows>"; 
        }

        //Insertar Dtaos Caminc
        if(isset($_GET['insertarDatosCamord'])){
            if(isset($_POST["oper"])){
                $Lsql  = '';

                $validar = 0;
                $LsqlU = "UPDATE ".$BaseDatos_systema.".CAMORD SET "; 
                $LsqlI = "INSERT INTO ".$BaseDatos_systema.".CAMORD(";
                $LsqlV = " VALUES ("; 
     
                if(isset($_POST["CAMORD_MUESCAMP__B"])){
                    if($_POST["CAMORD_MUESCAMP__B"] != '0'){
                        $separador = "";
                        if($validar == 1){
                            $separador = ",";
                        }

                        $LsqlU .= $separador."CAMORD_MUESCAMP__B = '".$_POST["CAMORD_MUESCAMP__B"]."'";
                        $LsqlI .= $separador."CAMORD_MUESCAMP__B";
                        $LsqlV .= $separador."'".$_POST["CAMORD_MUESCAMP__B"]."'";
                        $validar = 1;
                    }
                }


                if(isset($_POST["CAMORD_MUESPOBL__B"])){
                    if($_POST["CAMORD_MUESPOBL__B"] != '0'){
                        $separador = "";
                        if($validar == 1){
                            $separador = ",";
                        }

                        $LsqlU .= $separador."CAMORD_MUESPOBL__B = '".$_POST["CAMORD_MUESPOBL__B"]."'";
                        $LsqlI .= $separador."CAMORD_MUESPOBL__B";
                        $LsqlV .= $separador."'".$_POST["CAMORD_MUESPOBL__B"]."'";
                        $validar = 1;
                    }
                    
                }                                                           
                 
                if(isset($_POST["CAMORD_POBLCAMP__B"])){
                    if($_POST["CAMORD_POBLCAMP__B"] != '0'){
                        $separador = "";
                        if($validar == 1){
                            $separador = ",";
                        }

                        $LsqlU .= $separador."CAMORD_POBLCAMP__B = '".$_POST["CAMORD_POBLCAMP__B"]."'";
                        $LsqlI .= $separador."CAMORD_POBLCAMP__B";
                        $LsqlV .= $separador."'".$_POST["CAMORD_POBLCAMP__B"]."'";
                        $validar = 1;
                    }
                }                                                             
                     

                if(isset($_POST["CAMORD_PRIORIDAD_B"])){
                    $separador = "";
                    if($validar == 1){
                        $separador = ",";
                    }

                    $LsqlU .= $separador."CAMORD_PRIORIDAD_B = '".$_POST["CAMORD_PRIORIDAD_B"]."'";
                    $LsqlI .= $separador."CAMORD_PRIORIDAD_B";
                    $LsqlV .= $separador."'".$_POST["CAMORD_PRIORIDAD_B"]."'";
                    $validar = 1;
                }                                                             
                     

                if(isset($_POST["CAMORD_ORDEN_____B"])){
                    $separador = "";
                    if($validar == 1){
                        $separador = ",";
                    }

                    $LsqlU .= $separador."CAMORD_ORDEN_____B = '".$_POST["CAMORD_ORDEN_____B"]."'";
                    $LsqlI .= $separador."CAMORD_ORDEN_____B";
                    $LsqlV .= $separador."'".$_POST["CAMORD_ORDEN_____B"]."'";
                    $validar = 1;
                }                                                             
                     

                if(isset($_POST["Padre"])){
                    if($_POST["Padre"] != ''){
                        //esto es porque el padre es el entero
                        
                        $numero = $_POST["Padre"];
                        $Padre = $numero;
                        $LsqlU .= ", CAMORD_CONSINTE__CAMPAN_B = ".$Padre."";
                        $LsqlI .= ", CAMORD_CONSINTE__CAMPAN_B";
                        $LsqlV .= ",".$_POST["Padre"];
                    }
                }                                                       
                                                                     


                if(isset($_POST['oper'])){
                    if($_POST["oper"] == 'add' ){
                        $Lsql = $LsqlI.")" . $LsqlV.")";
                    }else if($_POST["oper"] == 'edit' ){
                        $Lsql = $LsqlU." WHERE CAMORD_ConsInte__b =".$_POST["id"]; 
                    }else if($_POST['oper'] == 'del'){
                        $Lsql = "DELETE FROM  ".$BaseDatos_systema.".CAMORD WHERE CAMORD_CONSINTE__B = ".$_POST['id'];
                        $validar = 1;
                    }
                }

                if($validar == 1){
                    //echo $Lsql;
                    $id_Usuario_Nuevo = 1;
                    if ($mysqli->query($Lsql) === TRUE) {
                        $id_Usuario_Nuevo = $mysqli->insert_id;

                        if($_POST["oper"] == 'add' ){
                            guardar_auditoria("INSERTAR", "INSERTAR REGISTRO EN CAMORD");
                        }else if($_POST["oper"] == 'edit' ){
                            guardar_auditoria("ACTUALIZAR", "ACTUALIZO EL REGISTRO # ".$_POST["id"]." EN CAMORD");
                        }else if($_POST["oper"] == 'del' ){
                           guardar_auditoria("ELIMINAR", "ELIMINO EL REGISTRO # ".$_POST['id']." EN CAMORD");
                        }

                        echo $id_Usuario_Nuevo ;
                    } else {
                        echo "Error Hacieno el proceso los registros : " . $mysqli->error;
                    } 
                }  
            }
        }


        if(isset($_GET["callDatosSubgrilla_UsuariosCampan"])){

            $id = $_GET['id'];  
            $numero = $id;
                
            $SQL = "SELECT ASITAR_ConsInte__b, USUARI_Nombre____b FROM ".$BaseDatos_systema.".ASITAR JOIN ".$BaseDatos_systema.".USUARI ON USUARI_ConsInte__b = ASITAR_ConsInte__USUARI_b WHERE ASITAR_ConsInte__CAMPAN_b = ".$id;
            //$SQL .= " WHERE pasos_troncales.id_campana = ".$numero;
            $SQL .= " ORDER BY USUARI_Nombre____b;";

            //echo $SQL;
            if ( stristr($_SERVER["HTTP_ACCEPT"],"application/xhtml+xml") ) { 
                header("Content-type: application/xhtml+xml;charset=utf-8"); 
            } else { 
                header("Content-type: text/xml;charset=utf-8"); 
            } 

            $et = ">"; 
            echo "<?xml version='1.0' encoding='utf-8'?$et\n"; 
            echo "<rows>"; // be sure to put text data in CDATA

            $result = $mysqli->query($SQL);
            while( $fila = $result->fetch_object() ) {
                echo "<row asin='".$fila->ASITAR_ConsInte__b."'>"; 
                echo "<cell>". ($fila->ASITAR_ConsInte__b)."</cell>"; 
                echo "<cell>". ($fila->USUARI_Nombre____b)."</cell>"; 
                echo "</row>"; 
            } 
            echo "</rows>"; 
        }


        if(isset($_GET['insertarDatosUsuarioCampan'])){
            if(isset($_POST["oper"])){
                $Lsql  = '';
                $Lsql_dy  = '';

                $validar = 0;
                $LsqlU = "UPDATE ".$BaseDatos_systema.".ASITAR SET "; 
                $LsqlI = "INSERT INTO ".$BaseDatos_systema.".ASITAR(";
                $LsqlV = " VALUES ("; 


                $LsqlU_dy = "UPDATE ".$BaseDatos_telefonia.".dy_campanas_agentes SET "; 
                $LsqlI_dy = "INSERT INTO ".$BaseDatos_telefonia.".dy_campanas_agentes(";
                $LsqlV_dy = " VALUES ("; 
     
                if(isset($_POST["USUARI_Nombre____b"])){
                    if($_POST["USUARI_Nombre____b"] != '0'){
                        $separador = "";
                        if($validar == 1){
                            $separador = ",";
                        }

                        $str_UsuarioCbx = "SELECT USUARI_UsuaCBX___b FROM ".$BaseDatos_systema.".USUARI WHERE USUARI_ConsInte__b = ".$_POST["USUARI_Nombre____b"];
                        $res_strUsuariCbx = $mysqli->query($str_UsuarioCbx);
                        $datoSql = $res_strUsuariCbx->fetch_array();

                        $LsqlU .= $separador."ASITAR_ConsInte__USUARI_b = '".$_POST["USUARI_Nombre____b"]."', ASITAR_UsuarioCBX_b = ".$datoSql['USUARI_UsuaCBX___b'];
                        $LsqlI .= $separador."ASITAR_ConsInte__USUARI_b , ASITAR_UsuarioCBX_b";
                        $LsqlV .= $separador."'".$_POST["USUARI_Nombre____b"]."' , ".$datoSql['USUARI_UsuaCBX___b'];

                        $validar = 1;    

                        /* Insertar en dy_agentes_campañas */
                        $str_dyAgentesLsql = "SELECT id FROM ".$BaseDatos_telefonia.".dy_agentes WHERE id_usuario_asociado = ".$datoSql['USUARI_UsuaCBX___b'];
                        $res_str_dyAgentesLsql = $mysqli->query($str_dyAgentesLsql);
                        $datos_DyAgentes = $res_str_dyAgentesLsql->fetch_array();
                        $LsqlI_dy .= $separador."id_agente";
                        $LsqlV_dy .= $separador."".$datos_DyAgentes['id'];
                        $LsqlU_dy .= $separador."id_agente = ".$datos_DyAgentes['id'];                    

                    }
                }

                if(isset($_POST["Padre"])){
                    if($_POST["Padre"] != ''){
                        //esto es porque el padre es el entero
                        
                        $numero = $_POST["Padre"];
                        $Padre = $numero;
                        $LsqlU .= ", ASITAR_ConsInte__CAMPAN_b = ".$Padre."";
                        $LsqlI .= ", ASITAR_ConsInte__CAMPAN_b";
                        $LsqlV .= ",".$_POST["Padre"];


                        /* obtenerlas muestar y todo eso */

                        $Lsql_Campan = "SELECT CAMPAN_ConsInte__GUION__Pob_b, CAMPAN_ConsInte__MUESTR_b, CAMPAN_ConsInte__GUION__Gui_b , CAMPAN_IdCamCbx__b FROM ".$BaseDatos_systema.".CAMPAN WHERE CAMPAN_ConsInte__b = ".$_POST["Padre"];
                        $res_Lsql_Campan = $mysqli->query($Lsql_Campan);
                        $datoCampan = $res_Lsql_Campan->fetch_array();
                        $int_Pobla_Camp_2 = $datoCampan['CAMPAN_ConsInte__GUION__Pob_b'];
                        $int_Muest_Campan = $datoCampan['CAMPAN_ConsInte__MUESTR_b'];
                        $int_Guion_Campan = $datoCampan['CAMPAN_ConsInte__GUION__Gui_b'];
                        $int_Cbx___Campan = $datoCampan['CAMPAN_IdCamCbx__b'];


                        $LsqlU .= ", ASITAR_IndiConc__b = 0, ASITAR_Prioridad_b = 0, ASITAR_ConsInte__GUION__Gui_b = ".$int_Guion_Campan.", ASITAR_ConsInte__GUION__Pob_b = ".$int_Pobla_Camp_2.", ASITAR_ConsInte__MUESTR_b = ".$int_Muest_Campan;
                        $LsqlI .= ", ASITAR_IndiConc__b, ASITAR_Prioridad_b, ASITAR_ConsInte__GUION__Gui_b, ASITAR_ConsInte__GUION__Pob_b, ASITAR_ConsInte__MUESTR_b";
                        $LsqlV .= ",0,0,".$int_Guion_Campan.",".$int_Pobla_Camp_2.",".$int_Muest_Campan;

                        /* insertar en dy_agentes_campañas */
                        
                        $LsqlI_dy .= ",id_campana";
                        $LsqlV_dy .= ",".$int_Cbx___Campan;
                        $LsqlU_dy .= ",id_campana = ".$int_Cbx___Campan;


                        $LsqlI_dy .= ",prioridad, fijo, responde_correos_electronicos, responde_chat, responde_llamadas";
                        $LsqlV_dy .= ",1, 0, 0, 0, 1";
                        $LsqlU_dy .= ",prioridad = 1, fijo = 0, responde_correos_electronicos = 0, responde_chat = 0, responde_llamadas = 1";

                        
                    }
                }                                                       
                       
                /* */
                                                                                                            


                if(isset($_POST['oper'])){
                    if($_POST["oper"] == 'add' ){
                        $Lsql = $LsqlI.")" . $LsqlV.")";
                        $Lsql_dy = $LsqlI_dy.")" . $LsqlV_dy.")";
                    }else if($_POST["oper"] == 'edit' ){
                        $Lsql = $LsqlU." WHERE ASITAR_ConsInte__b =".$_POST["id"]; 
                    }else if($_POST['oper'] == 'del'){
                        $Lsql = "DELETE FROM  ".$BaseDatos_systema.".ASITAR WHERE ASITAR_ConsInte__b = ".$_POST['id'];
                        $validar = 1;


                        $getDatosLsql = "SELECT ASITAR_UsuarioCBX_b, ASITAR_ConsInte__CAMPAN_b FROM ".$BaseDatos_systema.".ASITAR WHERE ASITAR_ConsInte__b = ".$_POST['id'];
                        $res_datosLSql = $mysqli->query($getDatosLsql);
                        $datosASItar = $res_datosLSql->fetch_array(); 
                        $Lsql_dy = "DELETE FROM  ".$BaseDatos_telefonia.".dy_campanas_agentes WHERE id_agente = ".$datosASItar['ASITAR_UsuarioCBX_b']." AND id_campana = ".$datosASItar['ASITAR_ConsInte__CAMPAN_b'];
                     
                    }
                }

                if($validar == 1){
                    echo $Lsql_dy;
                    $id_Usuario_Nuevo = 1;
                    if ($mysqli->query($Lsql) === TRUE) {
                        $id_Usuario_Nuevo = $mysqli->insert_id;

                        if($_POST["oper"] == 'add' ){
                            guardar_auditoria("INSERTAR", "INSERTAR REGISTRO EN ASITAR");
                        }else if($_POST["oper"] == 'edit' ){
                            guardar_auditoria("ACTUALIZAR", "ACTUALIZO EL REGISTRO # ".$_POST["id"]." EN ASITAR");
                        }else if($_POST["oper"] == 'del' ){
                           guardar_auditoria("ELIMINAR", "ELIMINO EL REGISTRO # ".$_POST['id']." EN ASITAR");
                        }
                        echo $id_Usuario_Nuevo ;
                    } else {
                        echo "Error Hacieno el proceso los registros : " . $mysqli->error;
                    }


                    if( $mysqli->query($Lsql_dy) === true){
                        
                    }else{
                        echo "Error => ".$mysqli->error;
                    }
                }  
            }
        }


        if(isset($_GET['crudLisopcDelete'])){
            $id = $_POST['idLisopc'];
            $Lsql = "SELECT LISOPC_Clasifica_b FROM ".$BaseDatos_systema.".LISOPC WHERE LISOPC_ConsInte__b = ".$id;
            $res = $mysqli->query($Lsql);
            $datos = $res->fetch_array();

            if($datos['LISOPC_Clasifica_b'] > 5){
                $DeleteMoNoEf = "DELETE FROM ".$BaseDatos_systema.".MONOEF WHERE MONOEF_ConsInte__b = ".$datos['LISOPC_Clasifica_b'];

                if($mysqli->query($DeleteMoNoEf) === true){
                    $DeleteLiSoPc = "DELETE  FROM ".$BaseDatos_systema.".LISOPC WHERE LISOPC_ConsInte__b = ".$id;
                    $mysqli->query($DeleteLiSoPc);
                }
            }else{
                $DeleteLiSoPc = "DELETE  FROM ".$BaseDatos_systema.".LISOPC WHERE LISOPC_ConsInte__b = ".$id;
                $mysqli->query($DeleteLiSoPc);
            }
           
        }

        function condiciones_mysql($name_p,$operador_p,$tipo_p,$valor_p){

            if ($tipo_p == 5) {
                $CON = " AND DATE_FORMAT(".$name_p.",'%Y-%m-%d') ";
            }else{
                $CON = " AND ".$name_p." ";
            }

            if (is_numeric($tipo_p)) {
                if ($tipo_p<3 || $tipo_p == 5 || $tipo_p == 14) {
                    if ($operador_p == "=") {
                        $CON .= "= '".$valor_p."' ";
                    }elseif ($operador_p == "!=") {
                        $CON .= "!= '".$valor_p."' ";
                    }elseif ($operador_p == "LIKE_1") {
                        $CON .= "LIKE '".$valor_p."%' ";
                    }elseif ($operador_p == "LIKE_2") {
                        $CON .= "LIKE '%".$valor_p."%' ";
                    }elseif ($operador_p == "LIKE_3") {
                        $CON .= "LIKE '%".$valor_p."' ";
                    }elseif ($operador_p == ">") {
                        $CON .= "> '".$valor_p."' ";
                    }elseif ($operador_p == "<") {
                        $CON .= "< '".$valor_p."' ";
                    }
                        
                }else{
                    if ($operador_p == "=") {
                        $CON .= "= ".$valor_p." ";
                    }elseif ($operador_p == "!=") {
                        $CON .= "!= ".$valor_p." ";
                    }elseif ($operador_p == ">") {
                        $CON .= "> ".$valor_p." ";
                    }elseif ($operador_p == "<") {
                        $CON .= "< ".$valor_p." ";
                    }
                }
            }else{
                if ($tipo_p == "_FecUltGes_b" || $tipo_p == "_FeGeMaIm__b") {
                    if ($operador_p == "=") {
                        $CON .= "= '".$valor_p."' ";
                    }elseif ($operador_p == "!=") {
                        $CON .= "!= '".$valor_p."' ";
                    }elseif ($operador_p == ">") {
                        $CON .= "> '".$valor_p."' ";
                    }elseif ($operador_p == "<") {
                        $CON .= "< '".$valor_p."' ";
                    }
                }else{
                    if ($operador_p == "=") {
                        $CON .= "= ".$valor_p." ";
                    }elseif ($operador_p == "!=") {
                        $CON .= "!= ".$valor_p." ";
                    }elseif ($operador_p == ">") {
                        $CON .= "> ".$valor_p." ";
                    }elseif ($operador_p == "<") {
                        $CON .= "< ".$valor_p." ";
                    }
                }
            }

            return $CON;

        }   

        if (isset($_GET['delete_base'])) {

            $intRadioCondiciones_t = $_POST["radio_condiciones_delete"];
            $intCampanaId_t = $_POST["id_campana_delete"];

            $strDatosCampana_t = "SELECT CAMPAN_ConsInte__GUION__Pob_b, CAMPAN_ConsInte__MUESTR_b 
                                FROM ".$BaseDatos_systema.".CAMPAN 
                                WHERE CAMPAN_ConsInte__b = ".$intCampanaId_t;
            $resSQLCampana_t = $mysqli->query($strDatosCampana_t);

            if ($resSQLCampana_t->num_rows>0) {

                $objCampana_t = $resSQLCampana_t->fetch_object();
                $intBase_t = $objCampana_t->CAMPAN_ConsInte__GUION__Pob_b;
                $intMuestra_t = $objCampana_t->CAMPAN_ConsInte__MUESTR_b;

                if ($intRadioCondiciones_t == 1) {

                    $strDeleteBD_t = "DELETE FROM ".$BaseDatos.".G".$intBase_t;

                    $strDeleteM_t = "DELETE FROM ".$BaseDatos.".G".$intBase_t."_M".$intMuestra_t;

                    if ($mysqli->query($strDeleteBD_t)) {
                        guardar_auditoria("DeleteRegistros","POB: G".$intBase_t." TipL: ELIMINAR TODO");
                        echo "exito";
                    }

                    if ($mysqli->query($strDeleteM_t)) {
                        guardar_auditoria("DeleteRegistros","POB: G".$intBase_t."_M".$intMuestra_t." TipL: ELIMINAR TODO");
                        echo "exito";
                    }

                }elseif ($intRadioCondiciones_t == 2) {

                    $arrFiltros_t = explode(",", $_POST["contador"]);
                    $strCONDICION_t = "";

                    foreach ($arrFiltros_t as $intCondicion_t) {
                        if ($intCondicion_t != "") {
                            $campFil_t = $_POST["pregun_".$intCondicion_t];
                            $strOperador_t = $_POST["condicion_".$intCondicion_t];
                            $valor_t = $_POST["valor_".$intCondicion_t];
                            $tipo_t = $_POST["tipo_campo_".$intCondicion_t];
                            if (is_numeric($campFil_t)) {
                                if ($campFil_t>0) {

                                    $strComprobar_t = "SHOW COLUMNS FROM " . $BaseDatos . ".G".$intBase_t." WHERE Field = 'G".$intBase_t."_C".$campFil_t."'";
                                    $resSQLUltComprobar_t = $mysqli->query($strComprobar_t);

                                    if ($resSQLUltComprobar_t->num_rows > 0) {
                                        $strCONDICION_t .= condiciones_mysql("G".$intBase_t."_C".$campFil_t,$strOperador_t,$tipo_t,$valor_t); 
                                    }else{
                                        echo "error";
                                    }

                                }
                            }else{
                                if ($campFil_t != "") {
                                    $strCONDICION_t .= condiciones_mysql("G".$intBase_t."_M".$intMuestra_t.$campFil_t,$strOperador_t,$tipo_t,$valor_t);
                                }else{
                                    echo "error";
                                }
                            }
                        }
                    }

                    if ($strCONDICION_t != "") {

                        $strDeleteId_t = "SELECT G".$intBase_t."_ConsInte__b AS ID 
                                            FROM ".$BaseDatos.".G".$intBase_t." 
                                            JOIN ".$BaseDatos.".G".$intBase_t."_M".$intMuestra_t." ON 
                                            G".$intBase_t.".G".$intBase_t."_ConsInte__b = G".$intBase_t."_M".$intMuestra_t.".G".$intBase_t."_M".$intMuestra_t."_CoInMiPo__b 
                                            WHERE TRUE ".$strCONDICION_t;

                        $resDeleteId_t = $mysqli->query($strDeleteId_t);

                        $strIdDelete_t = "";
                        if ($resDeleteId_t->num_rows > 0) {
                            while ($objDeleteId_t = $resDeleteId_t->fetch_object()) {
                                $strIdDelete_t .= $objDeleteId_t->ID.","; 
                            }
                            $strIdDelete_t = substr($strIdDelete_t, 0, -1);
                        }

                        if ($strIdDelete_t != "") {
                            
                            $strDeleteBD_t = "DELETE FROM ".$BaseDatos.".G".$intBase_t." 
                                            WHERE G".$intBase_t."_ConsInte__b IN (".$strIdDelete_t.")";

                            $strDeleteM_t = "DELETE FROM ".$BaseDatos.".G".$intBase_t."_M".$intMuestra_t." 
                                            WHERE G".$intBase_t."_M".$intMuestra_t."_CoInMiPo__b IN (".$strIdDelete_t.")";

                            if ($mysqli->query($strDeleteBD_t)) {
                                guardar_auditoria("DeleteRegistros","POB: G".$intBase_t." TipL: ELIMINAR CON CONDICION");
                                echo "exito";
                            }

                            if ($mysqli->query($strDeleteM_t)) {
                                guardar_auditoria("DeleteRegistros","POB: G".$intBase_t."_M".$intMuestra_t." TipL: ELIMINAR CON CONDICION");
                                echo "exito";
                            }

                        }else{
                            echo "error_3";
                        }

                    }else{
                        echo "error_2";
                    }
                }elseif ($intRadioCondiciones_t == 3) {
                    $intCampoAFiltrar_t = $_POST["campo_a_filtrar_delete"];
                    $strFileName_t = $_FILES['listaExcell_delete']['name'];
                    $filFile_t  = $_FILES['listaExcell_delete']['tmp_name'];
                    $strFileType_t  = $_FILES['listaExcell_delete']['type'];

                    if ($filFile_t && $intCampoAFiltrar_t != "0" && $intCampoAFiltrar_t != "-1") {

                        if($strFileType_t == 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'){
                            $objReader = new PHPExcel_Reader_Excel2007();
                            $objReader->setReadDataOnly(true);
                            $obj_excel = $objReader->load($filFile_t);
                        }else if($strFileType_t == 'application/vnd.ms-excel'){
                            $obj_excel = PHPExcel_IOFactory::load($filFile_t);
                        }

                        $sheetData = $obj_excel->getActiveSheet()->toArray(null,true,true,true);
                        $highestColumm = $obj_excel->setActiveSheetIndex(0)->getHighestColumn(); // e.g. "EL"
                        $highestRow = $obj_excel->setActiveSheetIndex(0)->getHighestRow();

                        $intTipoCampo_t = 0;

                        if ($intCampoAFiltrar_t != "_ConsInte__b" && $intCampoAFiltrar_t != 0 && $intCampoAFiltrar_t != -1) {
                            $strTipoCampo_t = "SELECT PREGUN_Tipo______b FROM ".$BaseDatos_systema.".PREGUN WHERE PREGUN_ConsInte__b = ".$intCampoAFiltrar_t;

                            $resTipoCampo_t = $mysqli->query($strTipoCampo_t);

                            if ($resTipoCampo_t->num_rows > 0) {
                                $objTipoCampo_t = $resTipoCampo_t->fetch_object();    
                                $intTipoCampo_t = $objTipoCampo_t->PREGUN_Tipo______b; 
                            }else{
                                echo "error_1";
                            }

                            $intCampoAFiltrar_t = "G".$intBase_t."_C".$intCampoAFiltrar_t;

                        }elseif($intCampoAFiltrar_t == "_ConsInte__b"){
                            $intTipoCampo_t = 3;
                            $intCampoAFiltrar_t = "G".$intBase_t."_ConsInte__b";
                        }

                        $strIN_t = "";

                        if (count($sheetData)>0) {
                            
                            $strIN_t .= " AND ".$intCampoAFiltrar_t." IN (";
                            foreach ($sheetData as $index => $value) {
                                if ($intTipoCampo_t < 3 || $intTipoCampo_t == 14 || $intTipoCampo_t == 5) {
                                    $strIN_t .= "'".trim($value["A"])."',";
                                }else{
                                    $strIN_t .= trim($value["A"]).",";
                                }   
                            }
                            $strIN_t = substr($strIN_t, 0, -1);
                            $strIN_t .= ") ";
                        }

                        if ($strIN_t != "") {

                            $strDeleteId_t = "SELECT G".$intBase_t."_ConsInte__b AS ID 
                                            FROM ".$BaseDatos.".G".$intBase_t." 
                                            JOIN ".$BaseDatos.".G".$intBase_t."_M".$intMuestra_t." ON 
                                            G".$intBase_t.".G".$intBase_t."_ConsInte__b = G".$intBase_t."_M".$intMuestra_t.".G".$intBase_t."_M".$intMuestra_t."_CoInMiPo__b 
                                            WHERE TRUE ".$strIN_t;

                            $resDeleteId_t = $mysqli->query($strDeleteId_t);

                            $strIdDelete_t = "";
                            if ($resDeleteId_t->num_rows > 0) {
                                while ($objDeleteId_t = $resDeleteId_t->fetch_object()) {
                                    $strIdDelete_t .= $objDeleteId_t->ID.","; 
                                }
                                $strIdDelete_t = substr($strIdDelete_t, 0, -1);
                            }

                            if ($strIdDelete_t != "") {
                                
                                $strDeleteBD_t = "DELETE FROM ".$BaseDatos.".G".$intBase_t."
                                                WHERE G".$intBase_t."_ConsInte__b IN (".$strIdDelete_t.")";

                                $strDeleteM_t = "DELETE FROM ".$BaseDatos.".G".$intBase_t."_M".$intMuestra_t."
                                                WHERE G".$intBase_t."_M".$intMuestra_t."_CoInMiPo__b IN (".$strIdDelete_t.")";
                                
                                if ($mysqli->query($strDeleteBD_t)) {
                                    guardar_auditoria("DeleteRegistros","POB: G".$intBase_t." TipL: ELIMINAR CON CONDICION");
                                    echo "exito";
                                }

                                if ($mysqli->query($strDeleteM_t)) {
                                    guardar_auditoria("DeleteRegistros","POB: G".$intBase_t."_M".$intMuestra_t." TipL: ELIMINAR CON CONDICION");
                                    echo "exito";
                                }

                            }

                            
                        }else{
                            echo "error_5";
                        }

                    }else{
                        echo "error_4";
                    }
                }

            }else{
                echo "error_1";
            }
        }

        if (isset($_GET['administrar_base'])) {
 
            $intRadioCondiciones_t = $_POST["radio_condiciones"];
            $intCampanaId_t = $_POST["id_campana"];

            $intEstado_t = $_POST["sel_estados_acciones"];
            $intTipificacion_t = $_POST["sel_tipificaciones_acciones"];
            $intReintento_t = $_POST["sel_tipo_reintento_acciones"];
            $intActivo_t = $_POST["sel_activo_acciones"];
            $intExcluirOincluir_t = $_POST["sel_meter_sacar"];
            $intAgente_t = $_POST["sel_usuarios_asignacion"];
            $strFechaReintento_t = $_POST["txt_fecha_reintento_acciones"];
            $strHoraReintento_t = $_POST["txt_hora_reintento_acciones"];

            $strDatosCampana_t = "SELECT CAMPAN_ConsInte__GUION__Pob_b, CAMPAN_ConsInte__MUESTR_b FROM ".$BaseDatos_systema.".CAMPAN WHERE CAMPAN_ConsInte__b = ".$intCampanaId_t;
            $resSQLCampana_t = $mysqli->query($strDatosCampana_t);

            if ($resSQLCampana_t->num_rows>0) {

                $objCampana_t = $resSQLCampana_t->fetch_object();
                $intBase_t = $objCampana_t->CAMPAN_ConsInte__GUION__Pob_b;
                $intMuestra_t = $objCampana_t->CAMPAN_ConsInte__MUESTR_b;

                $strUPDATE_B_t = "";
                $strUPDATE_M_t = "";

                if ($intEstado_t > 0 || $intTipificacion_t > 0) {

                    $strUPDATE_B_t .= "UPDATE ".$BaseDatos.".G".$intBase_t." 
                                    JOIN ".$BaseDatos.".G".$intBase_t."_M".$intMuestra_t." 
                                    ON G".$intBase_t.".G".$intBase_t."_ConsInte__b = G".$intBase_t."_M".$intMuestra_t.".G".$intBase_t."_M".$intMuestra_t."_CoInMiPo__b SET ";


                    if ($intEstado_t > 0) {

                        $strCampoEstado_t = "SELECT PREGUN_ConsInte__b 
                                            FROM ".$BaseDatos_systema.".PREGUN 
                                            WHERE PREGUN_ConsInte__GUION__b = ".$intBase_t." 
                                            AND PREGUN_Texto_____b = 'ESTADO_DY';";

                        $resCampo_t = $mysqli->query($strCampoEstado_t);

                        if ($resCampo_t->num_rows > 0) {

                            $objCampo_t = $resCampo_t->fetch_object();
                            $strCampoEstado_id_t = $objCampo_t->PREGUN_ConsInte__b; 

                            $strComprobar_t = "SHOW COLUMNS FROM " . $BaseDatos . ".G".$intBase_t." WHERE Field = 'G".$intBase_t."_C".$strCampoEstado_id_t."'";

                            $resSQLUltComprobar_t = $mysqli->query($strComprobar_t);

                            if ($resSQLUltComprobar_t->num_rows > 0) {

                                $strUPDATE_B_t .= " G".$intBase_t."_C".$strCampoEstado_id_t." = ".$intEstado_t.",";
                            
                            }else{
                                echo "error_1";
                            }
                            

                        }


                    }

                    if ($intTipificacion_t > 0) { 

                            $strTipificacion_t = "SELECT MONOEF_ConsInte__b FROM " . $BaseDatos_systema . ".MONOEF
                                JOIN ".$BaseDatos_systema.".LISOPC ON MONOEF.MONOEF_ConsInte__b = LISOPC.LISOPC_Clasifica_b
                                WHERE LISOPC.LISOPC_ConsInte__b = ".$intTipificacion_t;

                            $resSQLTipificacionId_t = $mysqli->query($strTipificacion_t);
                            
                            if ($resSQLTipificacionId_t->num_rows > 0) {
                                
                                $objTipificacionId_t = $resSQLTipificacionId_t->fetch_object();
                                $intTipificacionId_t = $objTipificacionId_t->MONOEF_ConsInte__b;

                                $strUPDATE_B_t .= " G".$intBase_t."_UltiGest__b = ".$intTipificacionId_t.",";
                            }else{
                                echo "error_1";
                            }    
                            
                        
                    }

                    $strUPDATE_B_t = substr($strUPDATE_B_t, 0, -1);

                    $strUPDATE_B_t .= " WHERE TRUE ";


                }

                if ($intReintento_t > 0 || $intActivo_t > -2 || $intAgente_t > -2) {
                    
                    $strUPDATE_M_t = "UPDATE ".$BaseDatos.".G".$intBase_t."_M".$intMuestra_t." JOIN ".$BaseDatos.".G".$intBase_t." ON G".$intBase_t."_M".$intMuestra_t.".G".$intBase_t."_M".$intMuestra_t."_CoInMiPo__b = G".$intBase_t.".G".$intBase_t."_ConsInte__b SET ";

                    if ($intReintento_t > 0) {
                        $strUPDATE_M_t .= " G".$intBase_t."_M".$intMuestra_t."_Estado____b = ".$intReintento_t.",";
                    }
                    if ($strFechaReintento_t != "" && $strHoraReintento_t != "") {
                        $strUPDATE_M_t .= " G".$intBase_t."_M".$intMuestra_t."_FecHorAge_b = '".$strFechaReintento_t." ".$strHoraReintento_t."',";
                    }
                    if ($intActivo_t > -2) {
                        $strUPDATE_M_t .= " G".$intBase_t."_M".$intMuestra_t."_Activo____b = ".$intActivo_t.",";
                    }
                    if ($intAgente_t > -2) {
                        $strUPDATE_M_t .= " G".$intBase_t."_M".$intMuestra_t."_ConIntUsu_b = ".$intAgente_t.",";
                    }

                    $strUPDATE_M_t = substr($strUPDATE_M_t, 0, -1);

                    $strUPDATE_M_t .= " WHERE TRUE ";                
                }

                if ($intRadioCondiciones_t == 1) {

                    if ($strUPDATE_B_t != "") {
                        if ($mysqli->query($strUPDATE_B_t)) {
                            guardar_auditoria("AdmRegistrosGestionarBD","POB: G".$intBase_t." TipL: ACTUALIZACION APLICADA A TODOS LOS REGISTROS");
                            echo "exito";
                        }
                    }
                    if ($strUPDATE_M_t != "") {
                        if ($mysqli->query($strUPDATE_M_t)) {
                            guardar_auditoria("AdmRegistrosGestionarBD","POB: G".$intBase_t."_M".$intMuestra_t." TipL: ACTUALIZACION APLICADA A TODOS LOS REGISTROS");
                            echo "exito";
                        }
                    }

                }

                if ($intRadioCondiciones_t == 2) {

                    $arrFiltros_t = explode(",", $_POST["contador"]);
                    $strCONDICION_t = "";

                    foreach ($arrFiltros_t as $intCondicion_t) {
                        if ($intCondicion_t != "") {
                            $campFil_t = $_POST["pregun_".$intCondicion_t];
                            $strOperador_t = $_POST["condicion_".$intCondicion_t];
                            $valor_t = $_POST["valor_".$intCondicion_t];
                            $tipo_t = $_POST["tipo_campo_".$intCondicion_t];
                            if (is_numeric($campFil_t)) {
                                if ($campFil_t>0) {

                                    $strComprobar_t = "SHOW COLUMNS FROM " . $BaseDatos . ".G".$intBase_t." WHERE Field = 'G".$intBase_t."_C".$campFil_t."'";
                                    $resSQLUltComprobar_t = $mysqli->query($strComprobar_t);

                                    if ($resSQLUltComprobar_t->num_rows > 0) {
                                        $strCONDICION_t .= condiciones_mysql("G".$intBase_t."_C".$campFil_t,$strOperador_t,$tipo_t,$valor_t); 
                                    }

                                }
                            }else{
                                if ($campFil_t != "") {
                                    $strCONDICION_t .= condiciones_mysql("G".$intBase_t."_M".$intMuestra_t.$campFil_t,$strOperador_t,$tipo_t,$valor_t);
                                }
                            }
                        }
                    }

                    if ($strCONDICION_t != "") {

                        if ($strUPDATE_B_t != "") {

                            $strUPDATE_B_t = $strUPDATE_B_t.$strCONDICION_t;

                            if ($mysqli->query($strUPDATE_B_t)) {
                                guardar_auditoria("AdmRegistrosGestionarBD","POB: G".$intBase_t." TipL: ACTUALIZACION APLICADA CON CONDICION");
                                echo "exito";
                            }else{
                                echo "fallo";
                            }

                        }

                        if ($strUPDATE_M_t != "") {

                            $strUPDATE_M_t = $strUPDATE_M_t.$strCONDICION_t;

                            if ($mysqli->query($strUPDATE_M_t)) {
                                guardar_auditoria("AdmRegistrosGestionarBD","POB: G".$intBase_t."_M".$intMuestra_t." TipL: ACTUALIZACION APLICADA CON CONDICION");
                                echo "exito";
                            }else{
                                echo "fallo";
                            }
                        }
                    }else{
                        echo "error_2";
                    }
                }

                if ($intRadioCondiciones_t == 3) {

                    $intCampoAFiltrar_t = $_POST["campo_a_filtrar"];
                    $strFileName_t = $_FILES['listaExcell']['name'];
                    $filFile_t  = $_FILES['listaExcell']['tmp_name'];
                    $strFileType_t  = $_FILES['listaExcell']['type'];

                    if ($filFile_t && $intCampoAFiltrar_t != "0" && $intCampoAFiltrar_t != "-1") {

                        if($strFileType_t == 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'){
                            $objReader = new PHPExcel_Reader_Excel2007();
                            $objReader->setReadDataOnly(true);
                            $obj_excel = $objReader->load($filFile_t);
                        }else if($strFileType_t == 'application/vnd.ms-excel'){
                            $obj_excel = PHPExcel_IOFactory::load($filFile_t);
                        }

                        $sheetData = $obj_excel->getActiveSheet()->toArray(null,true,true,true);
                        $highestColumm = $obj_excel->setActiveSheetIndex(0)->getHighestColumn(); // e.g. "EL"
                        $highestRow = $obj_excel->setActiveSheetIndex(0)->getHighestRow();

                        $intTipoCampo_t = 0;

                        if ($intCampoAFiltrar_t != "_ConsInte__b" && $intCampoAFiltrar_t != 0 && $intCampoAFiltrar_t != -1) {
                            $strTipoCampo_t = "SELECT PREGUN_Tipo______b FROM ".$BaseDatos_systema.".PREGUN WHERE PREGUN_ConsInte__b = ".$intCampoAFiltrar_t;

                            $resTipoCampo_t = $mysqli->query($strTipoCampo_t);

                            if ($resTipoCampo_t->num_rows > 0) {
                                $objTipoCampo_t = $resTipoCampo_t->fetch_object();    
                                $intTipoCampo_t = $objTipoCampo_t->PREGUN_Tipo______b; 
                            }

                            $intCampoAFiltrar_t = "G".$intBase_t."_C".$intCampoAFiltrar_t;

                        }elseif($intCampoAFiltrar_t == "_ConsInte__b"){
                            $intTipoCampo_t = 3;
                            $intCampoAFiltrar_t = "G".$intBase_t."_ConsInte__b";
                        }else{
                            echo "error_4";
                        }

                        $strIN_t = "";

                        if (count($sheetData)>0) {
                            
                            $strIN_t .= " AND ".$intCampoAFiltrar_t." IN (";
                            foreach ($sheetData as $index => $value) {
                                if ($intTipoCampo_t < 3 || $intTipoCampo_t == 14 || $intTipoCampo_t == 5) {
                                    $strIN_t .= "'".trim($value["A"])."',";
                                }else{
                                    $strIN_t .= trim($value["A"]).",";
                                }   
                            }
                            $strIN_t = substr($strIN_t, 0, -1);
                            $strIN_t .= ") ";
                        }

                        if ($strIN_t != "") {

                            if ($strUPDATE_B_t != "") {

                                $strUPDATE_B_t = $strUPDATE_B_t.$strIN_t;

                                if ($mysqli->query($strUPDATE_B_t)) {
                                    guardar_auditoria("AdmRegistrosGestionarBD","POB: G".$intBase_t." TipL: ACTUALIZACION APLICADA CON LISTA DE REGISTROS");
                                    echo "exito";
                                }

                            }

                            if ($strUPDATE_M_t != "") {

                                $strUPDATE_M_t = $strUPDATE_M_t.$strIN_t;

                                if ($mysqli->query($strUPDATE_M_t)) {
                                    guardar_auditoria("AdmRegistrosGestionarBD","POB: G".$intBase_t."_M".$intMuestra_t." TipL: ACTUALIZACION APLICADA CON LISTA DE REGISTOS");
                                    echo "exito";
                                }
                            }
                            
                        }else{
                            echo "error_5";
                        }

                    }else{
                        echo "error_4";
                    }
                }

            }else{
                echo "error_1";
            }
        }

        if(isset($_POST['deleteOption'])){
            $Lsql = "DELETE FROM ".$BaseDatos_systema.".LISOPC WHERE LISOPC_ConsInte__b = ".$_POST['id'];
            if($mysqli->query($Lsql) === true){
                echo "1";
            }else{
                echo "No se pudo Eliminar la opcion";
            }

        }

        if(isset($_POST['getListaRecibeMails'])){
            $opcionesCorreo = '';
    
            $Lsql = 'SELECT * FROM '.$dyalogo_canales_electronicos.'.dy_ce_configuracion WHERE id_huesped = '.$_SESSION['HUESPED'];
            $combo = $mysqli->query($Lsql);
            while($obj = $combo->fetch_object()){
                $opcionesCorreo .= "<option value='".$obj->id."' dinammicos='0'>".($obj->direccion_correo_electronico)."</option>";
    
            }  
            echo json_encode(['opciones' => $opcionesCorreo]);
        }
    
        // Elimina una opcion de correo
        if(isset($_POST['deleteCondicionCorreo'])){
            
            $Lsql = "SELECT id, id_campana_crm FROM ".$dyalogo_canales_electronicos.".dy_ce_filtros WHERE id = ".$_POST['idCondicion'];
            $res = $mysqli->query($Lsql);
            if($res->num_rows > 0 ){
                $data = $res->fetch_array();

                // Verifico si hay alguna accion filtro con id_filtro que eliminare
                $accion = "SELECT id FROM ".$dyalogo_canales_electronicos.".dy_ce_acciones_filtro WHERE id_filtro = ".$_POST['idCondicion'];
                $resAccion = $mysqli->query($accion);
                if($resAccion->num_rows == 0){
                    $Lsql = "DELETE FROM ".$dyalogo_canales_electronicos.".dy_ce_filtros WHERE id = ".$_POST['idCondicion'];
                    $mysqli->query($Lsql);

                    echo json_encode(array('message' => 'Eliminado1', 'eliminado' => true));
                }else{

                    // Si hay una accion con el id_filtro a eliminar cambio el id_filtro por otro de la misma campana
                    $filtrosLsql = "SELECT id FROM ".$dyalogo_canales_electronicos.".dy_ce_filtros WHERE id_campana_crm = ".$data['id_campana_crm']." AND id != ".$data['id'];
                    $filtrosCampana = $mysqli->query($filtrosLsql);
                    if($filtrosCampana->num_rows > 0){
                        $filtroCamp = $filtrosCampana->fetch_array();

                        $Lsql = "UPDATE ".$dyalogo_canales_electronicos.".dy_ce_acciones_filtro SET id_filtro = ".$filtroCamp['id']." WHERE id_filtro = ".$_POST['idCondicion'];
                        $mysqli->query($Lsql);

                        $Lsql = "DELETE FROM ".$dyalogo_canales_electronicos.".dy_ce_filtros WHERE id = ".$_POST['idCondicion'];
                        $mysqli->query($Lsql);

                        echo json_encode(array('message' => 'Eliminado', 'eliminado' => true));
                    }else{
                        echo json_encode(array('message' => 'Debe haber por lo menos un filtro', 'eliminado' => false));
                    }
                }
            }
            
        }
        
        if(isset($_POST['callPersistir'])){
            $data = array(  
                "strUsuario_t"          =>  'local',
                "strToken_t"            =>  'local',
                "intIdESTPAS_t"         =>  $_POST['id_estpas']
            );                                                             
            $data_string = json_encode($data);    

            $ch = curl_init($Api_Gestion.'dyalogocore/api/campanas/voip/persistir');
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");                                                                     
            curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string); 
            curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, array(                                                                          
                'Content-Type: application/json',                                                                                
                'Content-Length: ' . strlen($data_string))                                                                      
            ); 
            $respuesta = curl_exec ($ch);
            $error = curl_error($ch);
            curl_close ($ch);
            echo " Respuesta => ".$respuesta."<br>";
            echo " Error => ".$error;
        }
    
    }

    function sanear_string($string) { 

       // $string = utf8_decode($string);

        $string = str_replace( array('á', 'à', 'ä', 'â', 'ª', 'Á', 'À', 'Â', 'Ä'), array('a', 'a', 'a', 'a', 'a', 'A', 'A', 'A', 'A'), $string );
        $string = str_replace( array('é', 'è', 'ë', 'ê', 'É', 'È', 'Ê', 'Ë'), array('e', 'e', 'e', 'e', 'E', 'E', 'E', 'E'), $string ); 
        $string = str_replace( array('í', 'ì', 'ï', 'î', 'Í', 'Ì', 'Ï', 'Î'), array('i', 'i', 'i', 'i', 'I', 'I', 'I', 'I'), $string ); 
        $string = str_replace( array('ó', 'ò', 'ö', 'ô', 'Ó', 'Ò', 'Ö', 'Ô'), array('o', 'o', 'o', 'o', 'O', 'O', 'O', 'O'), $string ); 
        $string = str_replace( array('ú', 'ù', 'ü', 'û', 'Ú', 'Ù', 'Û', 'Ü'), array('u', 'u', 'u', 'u', 'U', 'U', 'U', 'U'), $string ); 
        $string = str_replace( array('ñ', 'Ñ', 'ç', 'Ç'), array('n', 'N', 'c', 'C',), $string ); 
        //Esta parte se encarga de eliminar cualquier caracter extraño 
        $string = str_replace( array("\\", "¨", "º", "-", "~", "#", "@", "|", "!", "\"", "·", "$", "%", "&", "/", "(", ")", "?", "'", "¡", "¿", "[", "^", "`", "]", "+", "}", "{", "¨", "´", ">“, “< ", ";", ",", ":", "."), '', $string ); 
        return $string; 
    }


    function crearSeccionesBD($ultimoGuion,$tipoGuion=0){
        global $mysqli;
        global $BaseDatos_systema;

        //Seccio control
        $Lsql_Control = "INSERT INTO ".$BaseDatos_systema.".G7(G7_C60, G7_C38, G7_C36 , G7_C34, G7_C33, G7_C35) VALUES(".$ultimoGuion.", 4, 1, 2, 'CONTROL', 1)";

        if($mysqli->query($Lsql_Control) === true){
            $control = $mysqli->insert_id;
            $Lsql_Agente_origen = "INSERT INTO ".$BaseDatos_systema.".PREGUN(PREGUN_Texto_____b, PREGUN_Tipo______b, PREGUN_IndiRequ__b, PREGUN_ConsInte__SECCIO_b, PREGUN_ConsInte__GUION__b, PREGUN_ContAcce__b, PREGUN_FueGener_b ) VALUES ('ORIGEN_DY_WF', 1, 0, ".$control.", ".$ultimoGuion.", 2, 1);";
            if($mysqli->query($Lsql_Agente_origen) === true){
                
            }

            $Lsql_Agente_OPtin = "INSERT INTO ".$BaseDatos_systema.".PREGUN(PREGUN_Texto_____b, PREGUN_Tipo______b, PREGUN_IndiRequ__b, PREGUN_ConsInte__SECCIO_b, PREGUN_ConsInte__GUION__b, PREGUN_ContAcce__b, PREGUN_FueGener_b ) VALUES ('OPTIN_DY_WF', 1, 0, ".$control.", ".$ultimoGuion.", 2, 1);";
            if($mysqli->query($Lsql_Agente_OPtin) === true){
                
            }

            $insertLsql = "INSERT INTO ".$BaseDatos_systema.".OPCION (OPCION_ConsInte__GUION__b, OPCION_Nombre____b, OPCION_ConsInte__PROYEC_b, OPCION_FechCrea__b, OPCION_UsuaCrea__b) VALUES (".$ultimoGuion.", 'ESTADO_DY_".$ultimoGuion."', ".$_SESSION['HUESPED'].", '".date('Y-m-d H:s:i')."', ".$_SESSION['IDENTIFICACION'].");";
            $ultimoLista = 0;
            if($mysqli->query($insertLsql) === true){
                $ultimoLista = $mysqli->insert_id;
                $array = array('1. No aplica', '2. Sin definir ', '3. No interesado', '4. Interesado', '5. Oportunidad', '6. No exitoso', '7. Exitoso');

                for ($i=0; $i < 7; $i++) {
                    $insertLisopc = "INSERT INTO ".$BaseDatos_systema.".LISOPC (LISOPC_Nombre____b, LISOPC_ConsInte__OPCION_b, LISOPC_Posicion__b) VALUES ('".$array[$i]."', ".$ultimoLista.", ".$i.");";
                    if($mysqli->query($insertLisopc) === true){
                     
                    }else{
                        echo $mysqli->error;
                    }
                }

                /* ahora insert la pregunta ESTADO_DY */
                $Lsql_Estado_campo = "INSERT INTO ".$BaseDatos_systema.".G6(G6_C39, G6_C40, G6_C51, G6_C32, G6_C207, G6_C209, G6_C44) VALUES ('ESTADO_DY', 6, 1, ".$control.", ".$ultimoGuion.", 1, ".$ultimoLista.");";
                if($mysqli->query($Lsql_Estado_campo) === true){
                    
                }else{
                    echo "Error generando Estado ".$mysqli->error;
                }
            }
        }

        generar_tablas_bd($ultimoGuion, 1 , 1 , 1 , 1,$tipoGuion);
    }

    function crearSecciones($ultimoGuion, $nombre, $guionBd,$tipoGuion=0){

        global $mysqli;
        global $BaseDatos_systema;
        //* una vez creada la tabla procedemos a generar lo que toca generar */
       // include(__DIR__."../../../../generador/generar_tablas_bd.php");
        /* 
            El tipo de Guión debe ser script.
            Primero debemos crear la seccion Tipificación 
        */
        
        $Lsql_Tipificacion = "INSERT INTO ".$BaseDatos_systema.".G7(G7_C60, G7_C38, G7_C36 , G7_C34, G7_C33) VALUES(".$ultimoGuion.", 3, 1, 4, 'TIPIFICACION')";
        
        if($mysqli->query($Lsql_Tipificacion) === true){
            
            $tipificacion = $mysqli->insert_id;
            
            $LsqlEstados_Search = "SELECT PREGUN_ConsInte__OPCION_B FROM ".$BaseDatos_systema.".PREGUN WHERE PREGUN_ConsInte__GUION__b = ".$guionBd." AND PREGUN_Texto_____b = 'ESTADO_DY'; ";
            $EstadoOpcion = $mysqli->query($LsqlEstados_Search);
            $tipificacionEstad1 = 0;
            $tipificacionEstad2 = 0;
            $tipificacionEstad3 = 0;
            $tipificacionEstad4 = 0;
            $tipificacionEstad5 = 0;
            $tipificacionEstad6 = 0;
            $tipificacionEstad7 = 0;


            if($EstadoOpcion->num_rows > 0){
                $datoOPcionEstado = $EstadoOpcion->fetch_array();
                $LsqlEstados = "SELECT LISOPC_ConsInte__b as id, LISOPC_Nombre____b as texto FROM ".$BaseDatos_systema.".LISOPC WHERE LISOPC_ConsInte__OPCION_b = ".$datoOPcionEstado['PREGUN_ConsInte__OPCION_B']." ORDER BY LISOPC_Nombre____b ASC";
                $resOpcionesEstado = $mysqli->query($LsqlEstados);
                while($resKey = $resOpcionesEstado->fetch_object()){ 

                    if($resKey->texto == 'No contactable'){
                        $tipificacionEstad2 = $resKey->id;
                    }

                    if($resKey->texto == 'No interesado'){
                        $tipificacionEstad3= $resKey->id;
                    }

                    if($resKey->texto == 'Interesado'){
                        $tipificacionEstad4= $resKey->id;
                    }

                    if($resKey->texto == 'No exitoso'){
                        $tipificacionEstad5= $resKey->id;
                    }

                    if($resKey->texto == 'Exitoso'){
                        $tipificacionEstad6= $resKey->id;
                    }
                }
            }


            /* priemro creamos la lista de las tipifiaciones */
            $insertLsql = "INSERT INTO ".$BaseDatos_systema.".OPCION (OPCION_ConsInte__GUION__b, OPCION_Nombre____b, OPCION_ConsInte__PROYEC_b, OPCION_FechCrea__b, OPCION_UsuaCrea__b) VALUES (".$ultimoGuion.", 'Tipificaciones - ".$nombre."', ".$_SESSION['HUESPED'].", '".date('Y-m-d H:s:i')."', ".$_SESSION['IDENTIFICACION'].");";
            //MONOEF_Texto_____b , MONOEF_Contacto__b,  MONOEF_TipNo_Efe_b, LISOPC_Clasifica_b
            $array = array(
                    array('Contacto Exitoso', 7, 3, 0, 0, $tipificacionEstad6),
                    array('Contacto No Exitoso',  6, 3, 0, 0, $tipificacionEstad5),
                    array('Contacto no pertinente ',  5, 3 , 0, 0,  $tipificacionEstad2)
                );
            $ultimoLista = 0;
            if($mysqli->query($insertLsql) === true){
                /* Se inserto la lista perfectamente */
                $ultimoLista = $mysqli->insert_id;
                /* toca meterlo en MONOEF */
                /* Primero lo pirmero crear el MonoEf */
                for ($i=0; $i < 3; $i++) { 

                    $MONOEFLsql = "INSERT INTO ".$BaseDatos_systema.".MONOEF (MONOEF_Texto_____b, MONOEF_EFECTIVA__B, MONOEF_TipNo_Efe_b, MONOEF_Importanc_b, MONOEF_FechCrea__b, MONOEF_UsuaCrea__b, MONOEF_Contacto__b, MONOEF_CanHorProxGes__b, MONOEF_TipiCBX___b) VALUES ('".$array[$i][0]."','0', 1 , '".($i+1)."' , '".date('Y-m-d H:s:i')."', ".$_SESSION['IDENTIFICACION'].", '".$array[$i][1]."' , '".$array[$i][4]."' , '".$array[$i][5]."')";

                    if($mysqli->query($MONOEFLsql) === true){
                        $monoefNew = $mysqli->insert_id;
                        /* ahora si lo insertamos en el LISOPC */
                        $insertLisopc = "INSERT INTO ".$BaseDatos_systema.".LISOPC (LISOPC_Nombre____b, LISOPC_ConsInte__OPCION_b, LISOPC_Posicion__b, LISOPC_Clasifica_b, LISOPC_Valor____b) VALUES ('".$array[$i][0]."', ".$ultimoLista.", 0, ".$monoefNew.", '".$array[$i][5]."');";
                        if($mysqli->query($insertLisopc) === true){
                     
                        }else{
                            echo $mysqli->error;
                        }

                    }else{
                        echo $mysqli->error;
                    }                  
                }
            }else{

            }

            /* Ahora toca crear los campos de la tipificacion */
            $int_Tipificacion_campo = 0;
            $int_Reintento_campo = 0;
            $int_Fecha_Agenda_campo = 0;
            $int_Hora_Agenda_campo = 0;
            $int_Observacion_campo = 0;
            $int_Link_Contenido_Comunicacion_campo = 0;

            $Lsql_Tipificacion_campo = "INSERT INTO ".$BaseDatos_systema.".G6(G6_C39, G6_C40, G6_C51, G6_C32, G6_C207, G6_C209, G6_C44) VALUES ('Tipificación', 6, 1, ".$tipificacion.", ".$ultimoGuion.", 1, ".$ultimoLista.");";
            if($mysqli->query($Lsql_Tipificacion_campo) === true){
                $int_Tipificacion_campo = $mysqli->insert_id;



            }
            
            $Lsql_Reintento_campo = "INSERT INTO ".$BaseDatos_systema.".G6(G6_C39, G6_C40, G6_C51, G6_C32, G6_C207, G6_C209) VALUES ('Reintento', 6, 1, ".$tipificacion.", ".$ultimoGuion.", 1);";
            if($mysqli->query($Lsql_Reintento_campo) === true){
                $int_Reintento_campo = $mysqli->insert_id;
            }

            $Lsql_Fecha_Agenda_campo = "INSERT INTO ".$BaseDatos_systema.".G6(G6_C39, G6_C40, G6_C51, G6_C32, G6_C207, G6_C209) VALUES ('Fecha Agenda', 5, 1, ".$tipificacion.", ".$ultimoGuion.", 1);";
            if($mysqli->query($Lsql_Fecha_Agenda_campo) === true){
                $int_Fecha_Agenda_campo = $mysqli->insert_id;
            }

            $Lsql_Hora_Agenda_campo = "INSERT INTO ".$BaseDatos_systema.".G6(G6_C39, G6_C40, G6_C51, G6_C32, G6_C207, G6_C209) VALUES ('Hora Agenda', 10, 1, ".$tipificacion.", ".$ultimoGuion.", 1);";
            if($mysqli->query($Lsql_Hora_Agenda_campo) === true){
                $int_Hora_Agenda_campo = $mysqli->insert_id;
            }

            $Lsql_Observacion_campo = "INSERT INTO ".$BaseDatos_systema.".G6(G6_C39, G6_C40, G6_C51, G6_C32, G6_C207, G6_C209) VALUES ('Observacion', 2, 1, ".$tipificacion.", ".$ultimoGuion." , 1);";
            if($mysqli->query($Lsql_Observacion_campo) === true){
                $int_Observacion_campo = $mysqli->insert_id;
            }

          



            $Lsql_Editar_Guion = "UPDATE ".$BaseDatos_systema.".G5 SET G5_C313 = ".$int_Fecha_Agenda_campo.", G5_C314 = ".$int_Hora_Agenda_campo." , G5_C315 = ".$int_Observacion_campo." , G5_C311 = ".$int_Tipificacion_campo." , G5_C312 = ".$int_Reintento_campo." WHERE G5_ConsInte__b = ".$ultimoGuion;

            if($mysqli->query($Lsql_Editar_Guion) !== true){
                echo "error => ".$mysqli->error;
            }
            
        }else{
            echo "TipificacioM  ".$mysqli->error;
        }

        //Seccio control
        $Lsql_Control = "INSERT INTO ".$BaseDatos_systema.".G7(G7_C60, G7_C38, G7_C36 , G7_C34, G7_C33, G7_C35) VALUES(".$ultimoGuion.", 4, 1, 2, 'CONTROL', 1)";

        if($mysqli->query($Lsql_Control) === true){
            $control = $mysqli->insert_id;

            /* insertar todos los campos de control */
            //Agente:texto con valor por defecto nombre del agente activo, fecha:fecha con valor por defecto fecha actual, hora:hora con valor por defecto hora actual, campaña:texto con valor por defecto nombre de la campaña activa

            //PREGUN_Default___b
            //PREGUN_ContAcce__b
            $int_Agente_campo=null;
            $int_Fecha_campo=null;
            $int_Hora_campo=null;
            $Lsql_Agente_campo = "INSERT INTO ".$BaseDatos_systema.".PREGUN(PREGUN_Texto_____b, PREGUN_Tipo______b, PREGUN_IndiRequ__b, PREGUN_ConsInte__SECCIO_b, PREGUN_ConsInte__GUION__b, PREGUN_Default___b , PREGUN_ContAcce__b, PREGUN_FueGener_b ) VALUES ('Agente', 1, 0, ".$control.", ".$ultimoGuion.", 102, 2, 1);";
            if($mysqli->query($Lsql_Agente_campo) === true){
                $int_Agente_campo = $mysqli->insert_id;
            }

            $Lsql_fecha_campo = "INSERT INTO ".$BaseDatos_systema.".PREGUN(PREGUN_Texto_____b, PREGUN_Tipo______b, PREGUN_IndiRequ__b, PREGUN_ConsInte__SECCIO_b, PREGUN_ConsInte__GUION__b, PREGUN_Default___b , PREGUN_ContAcce__b , PREGUN_FueGener_b) VALUES ('Fecha', 1, 0, ".$control.", ".$ultimoGuion.", 501, 2, 1);";
            if($mysqli->query($Lsql_fecha_campo) === true){
                $int_Fecha_campo = $mysqli->insert_id;
            }


            $Lsql_hora_campo = "INSERT INTO ".$BaseDatos_systema.".PREGUN(PREGUN_Texto_____b, PREGUN_Tipo______b, PREGUN_IndiRequ__b, PREGUN_ConsInte__SECCIO_b, PREGUN_ConsInte__GUION__b, PREGUN_Default___b , PREGUN_ContAcce__b , PREGUN_FueGener_b) VALUES ('Hora', 1, 0, ".$control.", ".$ultimoGuion.", 1001, 2, 1);";
            if($mysqli->query($Lsql_hora_campo) === true){
                $int_Hora_campo = $mysqli->insert_id;
            }

            $Lsql_campa_campo = "INSERT INTO ".$BaseDatos_systema.".PREGUN(PREGUN_Texto_____b, PREGUN_Tipo______b, PREGUN_IndiRequ__b, PREGUN_ConsInte__SECCIO_b, PREGUN_ConsInte__GUION__b, PREGUN_Default___b , PREGUN_ContAcce__b , PREGUN_FueGener_b) VALUES ('Campaña', 1, 0, ".$control.", ".$ultimoGuion.", 105, 2, 1);";
            if($mysqli->query($Lsql_campa_campo) === true){
            
            }

            $Lsql="UPDATE  ".$BaseDatos_systema.".GUION_ 
            SET GUION__ConsInte__PREGUN_Age_b = ".$int_Agente_campo.",GUION__ConsInte__PREGUN_Fec_b = ". $int_Fecha_campo.",GUION__ConsInte__PREGUN_Hor_b = ".$int_Hora_campo."
            WHERE GUION__ConsInte__b =".$ultimoGuion;

            if($mysqli->query($Lsql) === true){
            
            }
            
        }else{
            echo "Control  ".$mysqli->error;
        }


        /* seccion para calidad */
        
        $Lsql_Calidad = "INSERT INTO ".$BaseDatos_systema.".G7(G7_C60, G7_C38, G7_C36 , G7_C34, G7_C33, G7_C35) VALUES(".$ultimoGuion.", 2, 1, 3, 'CALIDAD', 2)";
        if($mysqli->query($Lsql_Calidad) === true){
            $calidad = $mysqli->insert_id;

            /* insetar todos los campos de calidad */
            
        }else{
            echo "Calidad  ".$mysqli->error;
        }


        //Seccio coonversacion
        $Lsql_Converacion = "INSERT INTO ".$BaseDatos_systema.".G7(G7_C60, G7_C38, G7_C36 , G7_C34, G7_C33, G7_C35) VALUES(".$ultimoGuion.", 1, 1, 5, 'CONVERSACION', 1)";

        if($mysqli->query($Lsql_Converacion) === true){
            $control = $mysqli->insert_id;

            /* insertar todos los campos de control */
            //Agente:texto con valor por defecto nombre del agente activo, fecha:fecha con valor por defecto fecha actual, hora:hora con valor por defecto hora actual, campaña:texto con valor por defecto nombre de la campaña activa

            //PREGUN_Default___b
            //PREGUN_ContAcce__b
            $Lsql_Agente_campo = "INSERT INTO ".$BaseDatos_systema.".PREGUN(PREGUN_Texto_____b, PREGUN_Tipo______b, PREGUN_IndiRequ__b, PREGUN_ConsInte__SECCIO_b, PREGUN_ConsInte__GUION__b,  PREGUN_ContAcce__b, PREGUN_FueGener_b ) VALUES ('Buenos días|tardes|noches, podría comunicarme con el señor(a) |NombreCliente|', 9, 0, ".$control.", ".$ultimoGuion.", 2, 1);";
            if($mysqli->query($Lsql_Agente_campo) === true){
            
            }else{
                echo "Error conversacion => ".$mysqli->error;
            }

            $Lsql_Agente_campo = "INSERT INTO ".$BaseDatos_systema.".PREGUN(PREGUN_Texto_____b, PREGUN_Tipo______b, PREGUN_IndiRequ__b, PREGUN_ConsInte__SECCIO_b, PREGUN_ConsInte__GUION__b,  PREGUN_ContAcce__b, PREGUN_FueGener_b ) VALUES ('Mi nombre es |Agente|, le estoy llamando de |Empresa| con el fin de ...', 9, 0, ".$control.", ".$ultimoGuion.", 2, 1);";
            if($mysqli->query($Lsql_Agente_campo) === true){
            
            }else{
                echo "Error conversacion => ".$mysqli->error;
            }
            
        }else{
            echo "Control  ".$mysqli->error;
        }

        generar_tablas_bd($ultimoGuion, 1 , 1 , 0 , 0,$tipoGuion);
    }

    // Esta funcion es utilizada para crear formularios para el chat de tipo web
    function crearFormulario($nombre, $id_huesped, $id_campanas_crm, $integracion){

        global $mysqli;
        global $BaseDatos_systema;
        global $BaseDatos_general;
        
        //creamos el formulario
        $Lsql = "INSERT INTO ".$BaseDatos_general.".dy_formularios (nombre, id_huesped) VALUES ('".$nombre."','".$id_huesped."')";
        $mysqli->query($Lsql);
        $id_formulario = $mysqli->insert_id;

        if(isset($_POST["id"])){
            $Lsql = "SELECT CAMPAN_ConsInte__GUION__Pob_b FROM ".$BaseDatos_systema.".CAMPAN WHERE CAMPAN_ConsInte__b = ".$_POST["id"];
            $res = $mysqli->query($Lsql);
            $datos = $res->fetch_array();
            $Bdtraducir = $datos['CAMPAN_ConsInte__GUION__Pob_b'];
        }else{
            $Lsql = "SELECT CAMPAN_ConsInte__GUION__Pob_b FROM ".$BaseDatos_systema.".CAMPAN WHERE CAMPAN_ConsInte__b = ".$id_campanas_crm;
            $res = $mysqli->query($Lsql);
            $datos = $res->fetch_array();
            $Bdtraducir = $datos['CAMPAN_ConsInte__GUION__Pob_b'];
        }

        if($integracion == 'web'){
            insertarCampos($_POST['camposFormulario'], $id_formulario);
        }
        
        return $id_formulario;
    }

    // Esta funcion inserta un chat cuando es nuevo
    function insertarChat($value, $id_formulario, $frase_fuera_horario, $id_campanas_crm, $momento_incial, $momento_final, $dia_inicial, $dia_final){

        global $mysqli;
        global $BaseDatos_systema;
        global $BaseDatos_general;
        global $dyalogo_canales_electronicos;

        //Mensajes generales automaticos
        $mensajeBienvenidaAutorespuesta =   $_POST['FraseBienvenidaAutorespuesta'];
        $mensajeFueraHorario            =   $_POST['FraseFueraDeHorario'];
        $mensajeSinAgenteDisponible     =   $_POST['enEsperaMensaje'];
        $mensajeAgenteAsignado          =   $_POST['FraseAgenteAsignado'];
        $tiempoMaximoAsignacion         =   10; //$_POST['TiempoMaximoAsignacion'];
        $mensajeTiempoExcedido          =   $_POST['maximoAsignacionMensaje'];
        $tiempoMaximoInactividadAgente  =   10; //$_POST['TiemMaxInactividadAgente'];
        $mensajeInactividadAgente       =   $_POST['inactividadAgenteMensaje'];
        $TiempoMaximoInactividadCliente =   10; //$_POST['TiemMaxInactividadCliente'];
        $mensajeInactividadCliente      =   $_POST['inactividadClienteMensaje'];

        $fraseSolicitudAccion = "Selecciona una opción";
        $fraseSolicitudIdUsuario = "Por favor digita tu dirección de correo electrónico";
        $validaSesionesPorLlave = 1;
        $activarTimeoutInactividadAgente = 1;
        $activarTimeoutInactividadCliente = 1;
        $fraseValidacionLlave = "Usted ya tiene otra conversación activa. Si ya no tiene acceso al dispositivo donde la tenía, puede esperar unos minutos e intentar nuevamente.";
        $placeholders = 1;
        $publicarApp = 1;

        $tipoIntegracion = $value['tipo_integracion'];

        //Campos de cada canal
        if($tipoIntegracion == 'web'){
            $nombre = $value['titulo'];
            $mensajeBienvenida = $value['mensaje_bienvenida'];
            $campoBusqueda = (isset($value['campo_busqueda'])) ? $value['campo_busqueda'] : 0;
            $datoIntegracion = null;
            $urlPoliticasPrivacidad = $value['politicas_privacidad'];
        }else{
            $nombre = $_POST['G10_C71'];
            $mensajeBienvenida = '';
            $campoBusqueda = 0;
            $datoIntegracion = $value['dato_integracion'];
            $urlPoliticasPrivacidad = null;
        }

        if($tipoIntegracion == 'web'){
            $usqlw = "UPDATE ".$BaseDatos_systema.".CAMPAN SET CAMPAN_id_pregun_campo_busqueda_web = ".$campoBusqueda." WHERE CAMPAN_ConsInte__b = ".$id_campanas_crm;
            $mysqli->query($usqlw);
        }

        if($tipoIntegracion == 'whatsapp'){
            $usql = "UPDATE ".$BaseDatos_systema.".CAMPAN SET CAMPAN_id_pregun_campo_busqueda_whatsapp = ".$_POST['whatsappCampoBusqueda']." WHERE CAMPAN_ConsInte__b = ".$id_campanas_crm;
            $mysqli->query($usql);
        }
        
        $Lsql = "INSERT INTO ".$dyalogo_canales_electronicos.".dy_chat_configuracion (
            nombre, ruta_logo, id_formulario, mensaje_bienvenida, id_huesped, frase_solicitud_accion, frase_fuera_horario, 
            frase_solicitud_id_usuario, tiempo_maximo_asignacion, frase_tiempo_asignacion_excedido, valida_sesiones_por_llave, 
            activar_timeout_inactividad_agente, activar_timeout_inactividad_cliente, tiempo_maximo_inactividad_agente, 
            tiempo_maximo_inactividad_cliente, frase_inactividad_agente, frase_inactividad_cliente, frase_validacion_llave, placeholders, 
            publicar_app, id_campana_crm, integrado_con, dato_integracion, frase_sin_agentes_disponibles, frase_agente_asignado, 
            frase_bienvenida_autorespuesta, id_pregun_campo_busqueda, link_politica_privacidad
        ) VALUES (
            '".$nombre."','','".$id_formulario."','".$mensajeBienvenida."','".$_SESSION['HUESPED']."','".$fraseSolicitudAccion."','".$mensajeFueraHorario."',
            '".$fraseSolicitudIdUsuario."','".$tiempoMaximoAsignacion."','".$mensajeTiempoExcedido."','".$validaSesionesPorLlave."',
            '".$activarTimeoutInactividadAgente."','".$activarTimeoutInactividadCliente."','".$tiempoMaximoInactividadAgente."',
            '".$TiempoMaximoInactividadCliente."','".$mensajeInactividadAgente."','".$mensajeInactividadCliente."',
            '".$fraseValidacionLlave."','".$placeholders."','".$publicarApp."','".$id_campanas_crm."', '".$tipoIntegracion."', 
            '".$datoIntegracion."', '".$mensajeSinAgenteDisponible."', '".$mensajeAgenteAsignado."', '".$mensajeBienvenidaAutorespuesta."',
            '".$campoBusqueda."', '".$urlPoliticasPrivacidad."')";
        
        $mysqli->query($Lsql);
        
        $id_chat = $mysqli->insert_id;

        //creamos el horario del chat
        insertarHorarios($id_chat, 'insert');
        // $Lsql = "INSERT INTO ".$dyalogo_canales_electronicos.".dy_chat_horarios (id_configuracion, momento_inicial, momento_final, dia_inicial, dia_final) VALUES ('".$id_chat."','".$momento_incial."','".$momento_final."','".$dia_inicial."','".$dia_final."')";
        // $mysqli->query($Lsql);

        if(isset($id_campanas_crm)){
            $Lsql = "SELECT CAMPAN_IdCamCbx__b FROM ".$BaseDatos_systema.".CAMPAN WHERE CAMPAN_ConsInte__b = ".$id_campanas_crm;
            $res = $mysqli->query($Lsql);
            $datos = $res->fetch_array();
            $id_campanas_cbx = $datos['CAMPAN_IdCamCbx__b'];
        }

        $Lsql = "INSERT INTO ".$dyalogo_canales_electronicos.".dy_chat_opciones_accion (id_chat_cfg, opcion, accion, id_campana) VALUES ('".$id_chat."','".$_POST['G10_C71']."',1,'".$id_campanas_cbx."')";

        $mysqli->query($Lsql);

        $sqlUpdateDentroFueraAccion = "UPDATE {$dyalogo_canales_electronicos}.dy_chat_configuracion SET dentro_horario_accion = 1, dentro_horario_detalle_accion = {$id_campanas_cbx}, fuera_horario_accion = 1, fuera_horario_detalle_accion = {$id_campanas_cbx} WHERE id = {$id_chat}";
        $mysqli->query($sqlUpdateDentroFueraAccion);
    }

    // Esta funcion actualiza un chat existente
    function actualizarChat($value, $id_campanas_crm, $momento_incial, $momento_final, $dia_inicial, $dia_final){

        global $mysqli;
        global $BaseDatos_systema;
        global $BaseDatos_general;
        global $dyalogo_canales_electronicos;

        if(isset($id_campanas_crm)){
            $Lsql = "SELECT CAMPAN_IdCamCbx__b FROM ".$BaseDatos_systema.".CAMPAN WHERE CAMPAN_ConsInte__b = ".$id_campanas_crm;
            $res = $mysqli->query($Lsql);
            $datos = $res->fetch_array();
            $id_campanas_cbx = $datos['CAMPAN_IdCamCbx__b'];
        }

        //Mensajes generales automaticos
        $mensajeBienvenidaAutorespuesta =   $_POST['FraseBienvenidaAutorespuesta'];
        $mensajeFueraHorario            =   $_POST['FraseFueraDeHorario'];
        $mensajeSinAgenteDisponible     =   $_POST['enEsperaMensaje'];
        $mensajeAgenteAsignado          =   $_POST['FraseAgenteAsignado'];
        $tiempoMaximoAsignacion         =   $_POST['maximoAsignacionTiempo'];
        $mensajeTiempoExcedido          =   $_POST['maximoAsignacionMensaje'];
        $tiempoMaximoInactividadAgente  =   $_POST['inactividadAgenteTiempo'];
        $mensajeInactividadAgente       =   $_POST['inactividadAgenteMensaje'];
        $TiempoMaximoInactividadCliente =   $_POST['inactividadClienteTiempo'];
        $mensajeInactividadCliente      =   $_POST['inactividadClienteMensaje'];

        $tipoIntegracion = $value['tipo_integracion'];

        //Campos de cada canal
        if($tipoIntegracion == 'web'){
            $nombre = $value['titulo'];
            $mensajeBienvenida = $value['mensaje_bienvenida'];
            $campoBusqueda = (isset($value['campo_busqueda'])) ? $value['campo_busqueda'] : 0;
            $datoIntegracion = null;
            $urlPoliticasPrivacidad = $value['politicas_privacidad'];
        }else{
            $nombre = $_POST['G10_C71'];
            $mensajeBienvenida = '';
            $campoBusqueda = 0;
            $datoIntegracion = $value['dato_integracion'];
            $urlPoliticasPrivacidad = null;
        }

        if($tipoIntegracion == 'web'){
            $usqlw = "UPDATE ".$BaseDatos_systema.".CAMPAN SET CAMPAN_id_pregun_campo_busqueda_web = ".$campoBusqueda." WHERE CAMPAN_ConsInte__b = ".$id_campanas_crm;
            $mysqli->query($usqlw);
        }

        if($tipoIntegracion == 'whatsapp'){
            $usql = "UPDATE ".$BaseDatos_systema.".CAMPAN SET CAMPAN_id_pregun_campo_busqueda_whatsapp = ".$_POST['whatsappCampoBusqueda']." WHERE CAMPAN_ConsInte__b = ".$id_campanas_crm;
            $mysqli->query($usql);
        }

        $Lsql = "UPDATE ".$dyalogo_canales_electronicos.".dy_chat_configuracion SET 
        nombre = '".$nombre."', mensaje_bienvenida = '".$mensajeBienvenida."', tiempo_maximo_asignacion = ".$tiempoMaximoAsignacion.", 
        frase_tiempo_asignacion_excedido = '".$mensajeTiempoExcedido."', tiempo_maximo_inactividad_agente = ".$tiempoMaximoInactividadAgente.",         
        tiempo_maximo_inactividad_cliente = ".$TiempoMaximoInactividadCliente.", frase_inactividad_agente = '".$mensajeInactividadAgente."', 
        frase_inactividad_cliente  = '".$mensajeInactividadCliente."', integrado_con = '".$tipoIntegracion."', dato_integracion = '".$datoIntegracion."', 
        frase_sin_agentes_disponibles = '".$mensajeSinAgenteDisponible."', frase_agente_asignado = '".$mensajeAgenteAsignado."', 
        frase_bienvenida_autorespuesta = '".$mensajeBienvenidaAutorespuesta."', frase_fuera_horario = '".$mensajeFueraHorario."',
        id_pregun_campo_busqueda = '".$campoBusqueda."', link_politica_privacidad = '".$urlPoliticasPrivacidad."'        
        WHERE id_campana_crm = ".$id_campanas_crm." AND integrado_con = '".$tipoIntegracion."'";

        $mysqli->query($Lsql);
        
        $Lsql = "SELECT id, id_formulario FROM ".$dyalogo_canales_electronicos.".dy_chat_configuracion WHERE id_campana_crm = ".$id_campanas_crm." AND integrado_con = '".$tipoIntegracion."'";
        $res = $mysqli->query($Lsql);

        if($res->num_rows > 0){
            $datos_chat = $res->fetch_array();
            insertarHorarios($datos_chat['id'], 'update');
            // $Lsql = "UPDATE ".$dyalogo_canales_electronicos.".dy_chat_horarios SET momento_inicial = '".$momento_incial."', momento_final = '".$momento_final."', dia_inicial = '".$dia_inicial."', dia_final = '".$dia_final."' WHERE id_configuracion = ".$datos_chat['id'];
            // $mysqli->query($Lsql);
            $Lsql = "UPDATE ".$dyalogo_canales_electronicos.".dy_chat_opciones_accion SET opcion = '".$_POST['G10_C71']."', accion = 1, id_campana = ".$id_campanas_cbx." WHERE id_chat_cfg = ".$datos_chat['id'];

            $mysqli->query($Lsql);
        }

        if($res->num_rows > 0){
            $id_formulario = $res->fetch_array();
            $Lsql = "UPDATE ".$BaseDatos_general.".dy_formularios SET nombre = '".$_POST['G10_C71']."' WHERE id = ".$datos_chat['id_formulario']; 
            $mysqli->query($Lsql);

            if($value['tipo_integracion'] == 'web'){
                //Actualizo los campos
                insertarCampos($_POST['camposFormulario'], $datos_chat['id_formulario']);
            }
        }
        
    }

    function insertarHorarios($id_chat, $tipo){

        if(isset($_POST['G10_C108']) && $_POST['G10_C108'] == -1){
            $dia_inicial = 1;
            $momento_incial = $_POST['G10_C109'];
            $momento_final = $_POST['G10_C110'];

            saveHorario($tipo, $id_chat, $dia_inicial, $momento_incial, $momento_final);
        }else{
            deleteHorario($id_chat, 1);
        }

        if(isset($_POST['G10_C111']) && $_POST['G10_C111'] == -1){
            $dia_inicial = 2;
            $momento_incial = $_POST['G10_C112'];
            $momento_final = $_POST['G10_C113'];

            saveHorario($tipo, $id_chat, $dia_inicial, $momento_incial, $momento_final);
        }else{
            deleteHorario($id_chat, 2);
        }

        if(isset($_POST['G10_C114']) && $_POST['G10_C114'] == -1){
            $dia_inicial = 3;
            $momento_incial = $_POST['G10_C115'];
            $momento_final = $_POST['G10_C116'];
            
            saveHorario($tipo, $id_chat, $dia_inicial, $momento_incial, $momento_final);
        }else{
            deleteHorario($id_chat, 3);
        }

        if(isset($_POST['G10_C117']) && $_POST['G10_C117'] == -1){
            $dia_inicial = 4;
            $momento_incial = $_POST['G10_C118'];
            $momento_final = $_POST['G10_C119'];
            
            saveHorario($tipo, $id_chat, $dia_inicial, $momento_incial, $momento_final);
        }else{
            deleteHorario($id_chat, 4);
        }

        if(isset($_POST['G10_C120']) && $_POST['G10_C120'] == -1){
            $dia_inicial = 5;
            $momento_incial = $_POST['G10_C121'];
            $momento_final = $_POST['G10_C122'];
            
            saveHorario($tipo, $id_chat, $dia_inicial, $momento_incial, $momento_final);
        }else{
            deleteHorario($id_chat, 5);
        }

        if(isset($_POST['G10_C123']) && $_POST['G10_C123'] == -1){
            $dia_inicial = 6;
            $momento_incial = $_POST['G10_C124'];
            $momento_final = $_POST['G10_C125'];
            
            saveHorario($tipo, $id_chat, $dia_inicial, $momento_incial, $momento_final);
        }else{
            deleteHorario($id_chat, 6);
        }

        if(isset($_POST['G10_C126']) && $_POST['G10_C126'] == -1){
            $dia_inicial = 7;
            $momento_incial = $_POST['G10_C127'];
            $momento_final = $_POST['G10_C128'];
            
            saveHorario($tipo, $id_chat, $dia_inicial, $momento_incial, $momento_final);
        }else{
            deleteHorario($id_chat, 7);
        }

    }

    function saveHorario($tipo, $id_chat, $dia_inicial, $momento_incial, $momento_final){
        global $mysqli;
        global $dyalogo_canales_electronicos;

        $lsql = "SELECT * FROM ".$dyalogo_canales_electronicos.".dy_chat_horarios WHERE id_configuracion = ".$id_chat." AND dia_inicial = ".$dia_inicial;
        $res = $mysqli->query($lsql);

        if($res->num_rows > 0){
            $sqlHorario = "UPDATE ".$dyalogo_canales_electronicos.".dy_chat_horarios SET momento_inicial = '".$momento_incial."', momento_final = '".$momento_final."' WHERE id_configuracion = ".$id_chat." AND dia_inicial = ".$dia_inicial;
        }else{
            $sqlHorario = "INSERT INTO ".$dyalogo_canales_electronicos.".dy_chat_horarios (id_configuracion, momento_inicial, momento_final, dia_inicial, dia_final) VALUES ('".$id_chat."','".$momento_incial."','".$momento_final."','".$dia_inicial."','".$dia_inicial."')";
        }            

        echo $sqlHorario;
        $mysqli->query($sqlHorario);        
    }

    function deleteHorario($id_chat, $dia_inicial){
        global $mysqli;
        global $dyalogo_canales_electronicos;

        $Lsql = "DELETE FROM ".$dyalogo_canales_electronicos.".dy_chat_horarios WHERE id_configuracion = ".$id_chat." AND dia_inicial = ".$dia_inicial;
        $mysqli->query($Lsql);
    }

    function eliminarChat($id_campanas_crm, $integradoCon){

        global $mysqli;
        global $BaseDatos_general;
        global $dyalogo_canales_electronicos;

        $Lsql = "SELECT id, id_formulario FROM ".$dyalogo_canales_electronicos.".dy_chat_configuracion WHERE id_campana_crm = ".$id_campanas_crm." AND integrado_con = '".$integradoCon."'";
        // $res = $mysqli->query($Lsql);
        // if($res->num_rows > 0){
        //     $eliminar = $res->fetch_array();

        //     $Lsql = "DELETE FROM ".$dyalogo_canales_electronicos.".dy_chat_horarios WHERE id_configuracion = ".$eliminar["id"];
        //     $mysqli->query($Lsql);

        //     $Lsql = "DELETE FROM ".$dyalogo_canales_electronicos.".dy_chat_opciones_accion WHERE id_chat_cfg = ".$eliminar["id"];
        //     $mysqli->query($Lsql);

        //     $Lsql = "DELETE FROM ".$dyalogo_canales_electronicos.".dy_chat_configuracion WHERE id = ".$eliminar["id"];
        //     $mysqli->query($Lsql);
            
        //     $Lsql = "DELETE FROM ".$BaseDatos_general.".dy_preguntas WHERE id_formulario = ".$eliminar["id_formulario"];
        //     $mysqli->query($Lsql);

        //     $Lsql = "DELETE FROM ".$BaseDatos_general.".dy_formularios WHERE id = ".$eliminar["id_formulario"];
        //     $mysqli->query($Lsql);

        // }
    }

    // Esta funcion se encarga de guardar el logo
    function storeLogoChat($file, $campana){
        global $mysqli;
        global $dyalogo_canales_electronicos;
        
        $Lsql = "SELECT id FROM ".$dyalogo_canales_electronicos.".dy_chat_configuracion WHERE id_campana_crm = ".$campana." AND integrado_con = 'web'";

        $res = $mysqli->query($Lsql);
        if($res->num_rows > 0){
            $datos = $res->fetch_array();
            
            if(isset($file['tmp_name']) && !empty($file['tmp_name'])){
                $tmp = explode('.', basename($file['name']));
                $extension = end($tmp);
                
                $name = $datos['id'].".".$extension;
                
                $path = "/Dyalogo/img_chat/".$name;
                $path = str_replace(' ', '', $path);
    
                if (file_exists($path)) {
                    unlink($path);
                }

                if (move_uploaded_file($file['tmp_name'], $path)){
                    echo "El archivo ha sido cargado correctamente.";
                    $usql = "UPDATE ".$dyalogo_canales_electronicos.".dy_chat_configuracion SET ruta_logo = '".$path."' WHERE id = ".$datos['id'];
                    $mysqli->query($usql);
                }else{
                    echo "Ocurrió algún error al subir el fichero. No pudo guardarse.";
                }
            }
        }

    }

    // Aqui se guardan los campos para un formulario web
    function insertarCampos($campos, $formulario){
        global $mysqli;
        global $BaseDatos_general;

        $campos = stripslashes($campos);
        $data = json_decode($campos);
        
        $arrSeleccionados = [];
        for ($i=0; $i < count($data); $i++) { 
            $arrSeleccionados[$i] = $data[$i][0];
        }

        $Lsql = "SELECT id, id_pregun FROM ".$BaseDatos_general.".dy_preguntas WHERE id_formulario = ".$formulario;
        $res = $mysqli->query($Lsql);
        
        // Eliminar los que ya no estan 
        $arrActual = [];
        while($key = $res->fetch_object()){
            if(!in_array($key->id_pregun, $arrSeleccionados)){
                $Dsql = "DELETE FROM ".$BaseDatos_general.".dy_preguntas WHERE id = ".$key->id;
                $mysqli->query($Dsql);
            }else{
                $arrActual[] = $key->id_pregun;
            }
        }
        // Agregar los nuevos
        for ($i=0; $i < count($data); $i++) { 
            if(!in_array($data[$i][0], $arrActual)){

                $tipo = $data[$i][4];

                // Valido el tipo que sea input text
                if($data[$i][4] == 1 || $data[$i][4] == 3 || $data[$i][4] == 14){
                    $tipo = 1;
                }

                // Valido que el input sea check
                if($data[$i][4] == 8){
                    $tipo = 3;
                }

                $Isql = "INSERT INTO ".$BaseDatos_general.".dy_preguntas (id_formulario, pregunta, tipo, es_requerida, id_pregun) VALUES ('".$formulario."','".$data[$i][1]."','".$tipo."','".$data[$i][3]."','".$data[$i][0]."')";
                // echo $Isql;
                $mysqli->query($Isql);
            }
            
        }
    }

    // Esto para traer el orden actual de accion_filtro
    function ordenMax($idConfiguracion){
        global $mysqli;
        global $dyalogo_canales_electronicos;

        $maxSql = "SELECT max(a.orden) as orden FROM dyalogo_canales_electronicos.dy_ce_acciones_filtro a JOIN dyalogo_canales_electronicos.dy_ce_filtros f ON a.id_filtro = f.id WHERE f.id_ce_configuracion = ".$idConfiguracion;
        $res = $mysqli->query($maxSql);

        if($res->num_rows > 0){
            $respuesta = $res->fetch_array();

            return ($respuesta['orden'] + 1);
        }else{
            return 1;
        }
        

    }

    function obtenerIdcbxCampan($idcampan){
        global $mysqli;
        global $BaseDatos_systema;

        $consulta = "SELECT G10_ConsInte__b, G10_C71, G10_C106 AS idcampamcbx  FROM {$BaseDatos_systema}.G10 WHERE G10_ConsInte__b={$idcampan}";
        $sql = mysqli_query($mysqli, $consulta);

        if($sql == true && mysqli_num_rows($sql) > 0){
            $result = mysqli_fetch_object($sql);
            $idcampancbx = $result->idcampamcbx;
        } else {
            $idcampancbx = 0;
        }

        return $idcampancbx;
    }

    function persistirCtc($idCtc){
        global $Api_Gestion;

        $data = array(  
            "strUsuario_t"              => 'crm',
            "strToken_t"                => 'D43dasd321',
            "intIDConfiguracionCTC_t"   => $idCtc
        );                                                             
        $data_string = json_encode($data);   
        echo $data_string; 

        $ch = curl_init($Api_Gestion.'dyalogocore/api/voip/ctc/configurar');
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");                                                                     
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string); 
        curl_setopt($ch, CURLOPT_RETURNTRANSFER,true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(                                                                          
            'Content-Type: application/json',                                                                                
            'Content-Length: ' . strlen($data_string))                                                                      
        ); 

        $respuesta = curl_exec($ch);
        $error = curl_error($ch);

        curl_close ($ch);

        echo " Respuesta => ".$respuesta;
        echo " Error => ".$error;
        if(!empty($respuesta) && !is_null($respuesta)){
            $json = json_decode($respuesta);
        } 
    }

    function gestionarChatsCampana($campanaId){
        
        global $mysqli;
        global $dyalogo_canales_electronicos;
        global $BaseDatos_systema;

        $cierre_chat_frase = $_POST['cierreChatMensaje'];
        $cierre_chat_enviar_bot = isset($_POST['cierreChatEnviarBot']) ? $_POST['cierreChatEnviarBot'] : 0;
        $cierre_chat_id_estpas_bot = isset($_POST['cierreChatBot']) ? $_POST['cierreChatBot'] : 0;
        $cierre_chat_id_autorespuesta = isset($_POST['cierreChatSeccion']) ? $_POST['cierreChatSeccion'] : 0;

        $en_espera_mostrar_posicion = isset($_POST['enEsperaPosicion']) ? $_POST['enEsperaPosicion'] : 0;
        $en_espera_intervalo_mensaje = $_POST['enEsperaTiempo'];
        $cant_maxima_ejecucion_mensaje = $_POST['cantMaxMensajeEspera'];
        $en_espera_frase = $_POST['enEsperaMensaje'];
        $en_espera_enviar_bot = isset($_POST['enEsperaEnviarBot']) ? $_POST['enEsperaEnviarBot'] : 0;
        $en_espera_id_estpas_bot = isset($_POST['enEsperaBot']) ? $_POST['enEsperaBot'] : 0;
        $en_espera_id_autorespuesta = isset($_POST['enEsperaSeccion']) ? $_POST['enEsperaSeccion'] : 0;

        $agente_asignado_frase = $_POST['FraseAgenteAsignado'];

        $tiempo_asignacion_excedido = $_POST['maximoAsignacionTiempo'];
        $asignacion_excedido_frase = $_POST['maximoAsignacionMensaje'];
        $asignacion_excedido_enviar_bot = isset($_POST['maximoAsignacionEnviarBot']) ? $_POST['maximoAsignacionEnviarBot'] : 0;
        $asignacion_excedido_id_estpas_bot = isset($_POST['maximoAsignacionBot']) ? $_POST['maximoAsignacionBot'] : 0;
        $asignacion_excedido_id_autorespuesta = isset($_POST['maximoAsignacionSeccion']) ? $_POST['maximoAsignacionSeccion'] : 0;

        $activarCierreCliente = isset($_POST['inactividadClienteActivar']) ? $_POST['inactividadClienteActivar'] : 0;
        $tiempo_maximo_inactividad_cliente = isset($_POST['inactividadClienteTiempo']) ? $_POST['inactividadClienteTiempo'] : 60;
        $inactividad_cliente_frase = isset($_POST['inactividadClienteMensaje']) ? $_POST['inactividadClienteMensaje'] : 'Seguramente te ocupaste, porque dejaste de hablarnos. No importa, cuando lo desees puedes comunicarte con nosotros nuevamente.';
        $inactividad_cliente_enviar_bot = isset($_POST['inactividadClienteEnviarBot']) ? $_POST['inactividadClienteEnviarBot'] : 0;
        $inactividad_cliente_id_estpas_bot = isset($_POST['inactividadClienteBot']) ? $_POST['inactividadClienteBot'] : 0;
        $inactividad_cliente_id_autorespuesta = isset($_POST['inactividadClienteSeccion']) ? $_POST['inactividadClienteSeccion'] : 0;

        $activarCierreAgente = isset($_POST['inactividadAgenteActivar']) ? $_POST['inactividadAgenteActivar'] : 0;
        $tiempo_maximo_inactividad_agente = isset($_POST['inactividadAgenteTiempo']) ? $_POST['inactividadAgenteTiempo'] : 60;
        $inactividad_agente_frase = isset($_POST['inactividadAgenteMensaje']) ? $_POST['inactividadAgenteMensaje'] : 'Lo sentimos, la comunicación dejó de estar activa, intenta nuevamente.';
        $inactividad_agente_enviar_bot = isset($_POST['inactividadAgenteEnviarBot']) ? $_POST['inactividadAgenteEnviarBot'] : 0;
        $inactividad_agente_id_estpas_bot = isset($_POST['inactividadAgenteBot']) ? $_POST['inactividadAgenteBot'] : 0;
        $inactividad_agente_id_autorespuesta = isset($_POST['inactividadAgenteSeccion']) ? $_POST['inactividadAgenteSeccion'] : 0;

        // Valido si recibe la posicion y tiene la variable de posicion
        if($en_espera_mostrar_posicion == 1){
            if(strpos($en_espera_frase, '${DY_POSICION}') == false){
                $en_espera_frase .= ' Eres el ${DY_POSICION} en la cola';
            }
        }

        // Primero valido si hay una configuracion creada en la campaña
        $sql = "SELECT * FROM {$BaseDatos_systema}.CAMPAN_CONFIGURACION_CHAT WHERE CACOCH_ConsInte__CAMPAN_b = {$campanaId} LIMIT 1";
        $resConf = $mysqli->query($sql);

        // Si existe realizo update, de lo contrario realizo insert
        if($resConf->num_rows > 0){
            
            // Actualizo
            $sql = "UPDATE {$BaseDatos_systema}.CAMPAN_CONFIGURACION_CHAT SET CACOCH_CierreChatFrase_b = '{$cierre_chat_frase}', CACOCH_CierreChatEnviarBot_b = {$cierre_chat_enviar_bot}, CACOCH_CierreChatIdEstpasBot_b = {$cierre_chat_id_estpas_bot}, CACOCH_CierreChatIdAutorespuesta_b = {$cierre_chat_id_autorespuesta},
            CACOCH_EnEsperaIntervaloMensaje_b = {$en_espera_intervalo_mensaje}, CACOCH_EnEsperaFrase_b = '{$en_espera_frase}', CACOCH_EnEsperaEnviarBot_b = {$en_espera_enviar_bot}, CACOCH_EnEsperaIdEstpasBot_b = {$en_espera_id_estpas_bot}, CACOCH_EnEsperaIdAutorespuesta_b = {$en_espera_id_autorespuesta}, 
            CACOCH_AsignacionExcedidaTiempo_b = {$tiempo_asignacion_excedido}, CACOCH_AsignacionExcedidaFrase_b = '{$asignacion_excedido_frase}', CACOCH_AsignacionExcedidaEnviarBot_b = {$asignacion_excedido_enviar_bot}, CACOCH_AsignacionExcedidaIdEstpasBot_b = {$asignacion_excedido_id_estpas_bot}, CACOCH_AsignacionExcedidaIdAutorespuesta_b = {$asignacion_excedido_id_autorespuesta}, 
            CACOCH_InactividadClienteTiempo_b = {$tiempo_maximo_inactividad_cliente}, CACOCH_InactividadClienteFrase_b = '{$inactividad_cliente_frase}', CACOCH_InactividadClienteEnviarBot_b = {$inactividad_cliente_enviar_bot}, CACOCH_InactividadClienteIdEstpasBot_b = {$inactividad_cliente_id_estpas_bot}, CACOCH_InactividadClienteIdAutorespuesta_b = {$inactividad_cliente_id_autorespuesta}, 
            CACOCH_InactividadAgenteTiempo_b = {$tiempo_maximo_inactividad_agente}, CACOCH_InactividadAgenteFrase_b = '{$inactividad_agente_frase}', CACOCH_InactividadAgenteEnviarBot_b = {$inactividad_agente_enviar_bot}, CACOCH_InactividadAgenteIdEstpasBot_b = {$inactividad_agente_id_estpas_bot}, CACOCH_InactividadAgenteIdAutorespuesta_b = {$inactividad_agente_id_autorespuesta}, 
            CACOCH_AgenteAsignadoFrase_b = '{$agente_asignado_frase}', CACOCH_InactividadAgenteActivarTimeout_b = {$activarCierreAgente}, CACOCH_InactividadClienteActivarTimeout_b = {$activarCierreCliente}, CACOCH_CantMaxMesajeEspera_b = {$cant_maxima_ejecucion_mensaje}, CACOCH_EnEsperaEnviarNotificarPosicion_b = {$en_espera_mostrar_posicion} WHERE CACOCH_ConsInte__CAMPAN_b = {$campanaId}";

        }else{

            // Inserto
            $sql = "INSERT INTO {$BaseDatos_systema}.CAMPAN_CONFIGURACION_CHAT (CACOCH_ConsInte__CAMPAN_b, CACOCH_CierreChatFrase_b, CACOCH_CierreChatEnviarBot_b, CACOCH_CierreChatIdEstpasBot_b, CACOCH_CierreChatIdAutorespuesta_b, 
            CACOCH_EnEsperaIntervaloMensaje_b, CACOCH_EnEsperaFrase_b, CACOCH_EnEsperaEnviarBot_b, CACOCH_EnEsperaIdEstpasBot_b, CACOCH_EnEsperaIdAutorespuesta_b, 
            CACOCH_AsignacionExcedidaTiempo_b, CACOCH_AsignacionExcedidaFrase_b, CACOCH_AsignacionExcedidaEnviarBot_b, CACOCH_AsignacionExcedidaIdEstpasBot_b, CACOCH_AsignacionExcedidaIdAutorespuesta_b, 
            CACOCH_InactividadClienteTiempo_b, CACOCH_InactividadClienteFrase_b, CACOCH_InactividadClienteEnviarBot_b, CACOCH_InactividadClienteIdEstpasBot_b, CACOCH_InactividadClienteIdAutorespuesta_b, 
            CACOCH_InactividadAgenteTiempo_b, CACOCH_InactividadAgenteFrase_b, CACOCH_InactividadAgenteEnviarBot_b, CACOCH_InactividadAgenteIdEstpasBot_b, CACOCH_InactividadAgenteIdAutorespuesta_b, 
            CACOCH_AgenteAsignadoFrase_b, CACOCH_InactividadAgenteActivarTimeout_b, CACOCH_InactividadClienteActivarTimeout_b, CACOCH_CantMaxMesajeEspera_b, CACOCH_EnEsperaEnviarNotificarPosicion_b) VALUES (
                {$campanaId},'{$cierre_chat_frase}',{$cierre_chat_enviar_bot},{$cierre_chat_id_estpas_bot},{$cierre_chat_id_autorespuesta},
                {$en_espera_intervalo_mensaje},'{$en_espera_frase}',{$en_espera_enviar_bot},{$en_espera_id_estpas_bot},{$en_espera_id_autorespuesta},
                {$tiempo_asignacion_excedido},'{$asignacion_excedido_frase}',{$asignacion_excedido_enviar_bot},{$asignacion_excedido_id_estpas_bot},{$asignacion_excedido_id_autorespuesta},
                {$tiempo_maximo_inactividad_cliente},'{$inactividad_cliente_frase}',{$inactividad_cliente_enviar_bot},{$inactividad_cliente_id_estpas_bot},{$inactividad_cliente_id_autorespuesta},
                {$tiempo_maximo_inactividad_agente},'{$inactividad_agente_frase}',{$inactividad_agente_enviar_bot},{$inactividad_agente_id_estpas_bot},{$inactividad_agente_id_autorespuesta},
                '{$agente_asignado_frase}', {$activarCierreAgente}, {$activarCierreCliente}, {$cant_maxima_ejecucion_mensaje}, {$en_espera_mostrar_posicion}
            )";
    
        }

        if($mysqli->query($sql) === TRUE){
            echo "ejecutado";
        }else{
            echo $mysqli->error;
        }

    }
?>
