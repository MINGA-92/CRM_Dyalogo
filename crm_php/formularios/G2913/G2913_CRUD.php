<?php
    ini_set('display_errors', 'On');
    ini_set('display_errors', 1);
    include(__DIR__."/../../conexion.php");
    include(__DIR__."/../../funciones.php");
    date_default_timezone_set('America/Bogota');
    // JDBD - se envia calificacion de la gestion al agente que la creo.
    
    if (isset($_GET["EnviarCalificacion"])) {
        $IdGuion = $_POST["IdGuion"];
        $Servidor = $_POST["Servidor"];
        $IdGestion = $_POST["IdGestion"];
        $IdCampana = $_POST["IdCampana"];

        $P = "SELECT GUION__ConsInte__PREGUN_Pri_b AS P, GUION__ConsInte__PREGUN_Sec_b AS S FROM ".$BaseDatos_systema.". GUION_ WHERE GUION__ConsInte__b = 2913;"; 
        $P = $mysqli->query($P);
        $P = $P->fetch_array();
        /* print_r($P);
        print_r("\n");
        print_r($P["P"]);
        print_r("\n");
        print_r($P["S"]);  */


        $gestion = "SELECT * FROM ".$BaseDatos.".G2913 WHERE G2913_ConsInte__b = ".$_POST["IdGestion"];
        $gestion = $mysqli->query($gestion);
        $gestion = $gestion->fetch_array();


        if (is_null($gestion["G2913_C48182"]) || $gestion["G2913_C48182"] == "") {
            $valCal = "NULL";
        }else{
            $valCal = $gestion["G2913_C48182"];
        }
        print_r("\n");
        print_r($valCal);

        if (is_null($gestion["G2913_C48183"]) || $gestion["G2913_C48183"] == "") {
            $valCom = "";
        }else{
            $valCom = $gestion["G2913_C48183"];
        }
        print_r("\n");
        print_r($valCom);

        $histCalidad = "INSERT INTO ".$BaseDatos_systema.".CALHIS (CALHIS_ConsInte__GUION__b,CALHIS_IdGestion_b,CALHIS_FechaGestion_b,CALHIS_ConsInte__USUARI_Age_b,CALHIS_DatoPrincipalScript_b,CALHIS_DatoSecundarioScript_b,CALHIS_FechaEvaluacion_b,CALHIS_ConsInte__USUARI_Cal_b,CALHIS_Calificacion_b,CALHIS_ComentCalidad_b) 
        VALUES(".$_POST["IdGuion"].",".$_POST["IdGestion"].",'".$gestion["G2913_FechaInsercion"]."',".$gestion["G2913_Usuario"].",'".$gestion["G2913_C".$P["P"]]."','".$gestion["G2913_C".$P["S"]]."','".date('Y-m-d H:i:s')."',".$_POST["IdCal"].",".$valCal.",'".$valCom."')";
        if ($mysqli->query($histCalidad)) {
            $IdCalificacion = $mysqli->insert_id;
            $IdProyecto= $_POST["IdProyecto"];
            $URL = $Servidor."/QA/index.php?SC=".$IdGuion."&G=".$IdGestion."&H=".$IdCalificacion;
        }else{
            $URL="";
            $IdProyecto= $_POST["IdProyecto"];
        }
        
        //GuardarURL
        $URL= strval("$URL");
        $GuardarURL= "UPDATE DYALOGOCRM_SISTEMA.CALHIS SET CALHIS_LinkCalificacion= '". $URL ."', CALHIS_ConsInte__PROYEC_b= '".$IdProyecto."', CALHIS_IdCampana= '". $IdCampana ."' WHERE CALHIS_ConsInte__GUION__b= '".$IdGuion."' AND CALHIS_ConsInte__b= '".$IdCalificacion."';";
        if ($ResultadoSQL = $mysqli->query($GuardarURL)) {
            $php_response= array("msg" => "URL Guardada");
            //print_r($php_response);
        }
        
        /*echo json_encode($php_response);
        print_r($php_response);
        exit;*/
        
        $upGCE = "UPDATE ".$BaseDatos.".G2913 SET G2913_C47728 = -201 WHERE G2913_ConsInte__b = ".$_POST["IdGestion"];
        $upGCE = $mysqli->query($upGCE);


        //Validar Dato Pricipal y Secundario
        /*
        $ConsultaSQL = "SELECT * FROM DYALOGOCRM_SISTEMA.CALHIS WHERE CALHIS_ConsInte__GUION__b= '".$IdGuion."' AND CALHIS_ConsInte__b= '".$IdCalificacion."';";
        if ($ResultadoSQL = $mysqli->query($ConsultaSQL)) {
            $CantidadResultados = $ResultadoSQL->num_rows;
            if($CantidadResultados > 0) {
                while ($FilaResultado = $ResultadoSQL->fetch_assoc()) {
            
                }
            }
        }
        */


        $HTML = "<!DOCTYPE html><html><head><title>HTML</title></head><body><div><h3>Añadir un comentario : </h3><a href = '".$URL."'>".$URL."</a></div><div>";

        $email = "SELECT USUARI_Correo___b AS email FROM ".$BaseDatos_systema.".USUARI WHERE USUARI_ConsInte__b = ".$gestion["G2913_Usuario"];
        $email = $mysqli->query($email);
        $email = $email->fetch_array();

        //JDBD - obtenemos las secciones del formulario.
        $Secciones = "SELECT SECCIO_ConsInte__b AS id, SECCIO_TipoSecc__b AS tipo, SECCIO_Nombre____b AS nom 
        FROM ".$BaseDatos_systema.".SECCIO WHERE SECCIO_ConsInte__GUION__b = 2913 AND SECCIO_TipoSecc__b <> 4 ORDER BY FIELD(SECCIO_TipoSecc__b,2) DESC, SECCIO_ConsInte__b DESC;";
        $Secciones = $mysqli->query($Secciones);

        $itCal = 0;
        $itNor = 0;

        while ($s = $Secciones->fetch_object()) {
            if ($s->tipo == 2) {
                if ($itCal == 0) {
                    $HTML .= "<div><h1 style='color: #2D0080'>CALIFICACION DE LA LLAMADA</h1><div>";
                }

                $HTML .= "<em style='color: #11CFFF'><h3>".$s->nom."</h3></em>";

                $columnas = "SELECT PREGUN_ConsInte__GUION__b AS G, PREGUN_ConsInte__b AS C, PREGUN_Texto_____b AS nom, PREGUN_Tipo______b AS tipo
                FROM ".$BaseDatos_systema.".PREGUN WHERE PREGUN_ConsInte__SECCIO_b = ".$s->id." ORDER BY PREGUN_ConsInte__b;";
                $columnas = $mysqli->query($columnas);

                while ($c = $columnas->fetch_object()) {
                    if (isset($gestion["G".$c->G."_C".$c->C])) {
                        $HTML .= "<p><strong>".$c->nom." : </strong>".traductor($gestion["G".$c->G."_C".$c->C],$c->tipo)."</p>"; 
                    }
                }

                if ($itCal == 0) {
                    $HTML .= "</div></div>";
                }
                $itCal ++;
            }else{
                if ($itNor == 0) {
                    $HTML .= "<h1 style='color: #2D0080'>INFORMACION DE LA GESTION DE LLAMADA</h1>";
                }

                $HTML .= "<div><em><h3 style='color: #11CFFF'>".$s->nom."</h3></em>";

                $columnas = "SELECT PREGUN_ConsInte__GUION__b AS G, PREGUN_ConsInte__b AS C, PREGUN_Texto_____b AS nom, PREGUN_Tipo______b AS tipo
                FROM ".$BaseDatos_systema.".PREGUN WHERE PREGUN_ConsInte__SECCIO_b = ".$s->id." ORDER BY PREGUN_ConsInte__b;";

                $columnas = $mysqli->query($columnas);

                while ($c = $columnas->fetch_object()) {
                    if (isset($gestion["G".$c->G."_C".$c->C])) {
                         $HTML .= "<p><strong>".$c->nom." : </strong>".traductor($gestion["G".$c->G."_C".$c->C],$c->tipo)."</p>";  
                    }
                    
                }

                $HTML .= "</div>";

                $itNor ++;
            }
        }

        $HTML .= "</div></body></html>";
        
        $data = array(  
            "strUsuario_t"              =>  "crm",
            "strToken_t"                =>  "D43dasd321",
            "strIdCfg_t"                =>  "18",
            "strTo_t"                   =>  '"'.$email["email"].'"',
            "strCC_t"                   =>  $_POST["Correos"],
            "strCCO_t"                  =>  null,
            "strSubject_t"              =>  "Calificacion Llamada #". $gestion["G2913_ConsInte__b"],
            "strMessage_t"              =>  $HTML,
            "strListaAdjuntos_t"        =>  null
        ); 

        $data_string = json_encode($data); 

        $ch = curl_init("localhost:8080/dyalogocore/api/ce/correo/sendmailservice");
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");                                                                     
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string); 
        curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(    
                "Accept: application/json",                                                               
                "Content-Type: application/json",                                                  
                "Content-Length: ".strlen($data_string)
            )                                                                      
        ); 
        $respuesta = curl_exec ($ch);
        $error = curl_error($ch);
        if (isset($respuesta)) {
            echo json_encode($respuesta);
        }else{
            echo json_encode($error);
        }
        curl_close ($ch);

    }
    
    if(isset($_GET["llenarBtnLlamada"])){// JDBD - Devolver link de la llamada
        $Con = "SELECT G2913_LinkContenido as url FROM ".$BaseDatos.".G2913 WHERE G2913_ConsInte__b = ".$_POST["idReg"];
        $result = $mysqli->query($Con);

        $url = $result->fetch_array();

        echo $url["url"];
    }                   

    if(isset($_GET["llenarBtnLlamada"])){// JDBD - Devolver link de la llamada
        $Con = "SELECT G2913_LinkContenido as url FROM ".$BaseDatos.".G2913 WHERE G2913_ConsInte__b = ".$_POST["idReg"];
        $result = $mysqli->query($Con);

        $url = $result->fetch_array();

        echo $url["url"];
    }                   

    if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
      //Datos del formulario
      if(isset($_POST['CallDatos'])){
          
            $Lsql = 'SELECT G2913_ConsInte__b, G2913_FechaInsercion , G2913_Usuario ,  G2913_CodigoMiembro  , G2913_PoblacionOrigen , G2913_EstadoDiligenciamiento ,  G2913_IdLlamada , G2913_C47728 as principal ,G2913_C47728,G2913_C47708,G2913_C47709,G2913_C47710,G2913_C47711,G2913_C47712,G2913_C47713,G2913_C47714,G2913_C47715,G2913_C47716,G2913_C47717,G2913_C47718,G2913_C47719,G2913_C47720,G2913_C47721,G2913_C47722,G2913_C47723,G2913_C47724,G2913_C47725,G2913_C48100,G2913_C48101,G2913_C48187,G2913_C48182,G2913_C48183,G2913_C48184,G2913_C48185,G2913_C48186,G2913_C48102,G2913_C48103,G2913_C48201,G2913_C48202 FROM '.$BaseDatos.'.G2913 WHERE G2913_ConsInte__b ='.$_POST['id'];
            $result = $mysqli->query($Lsql);
            $datos = array();
            $i = 0;

            while($key = $result->fetch_object()){

                $datos[$i]['G2913_C47728'] = $key->G2913_C47728;

                $datos[$i]['G2913_C47708'] = $key->G2913_C47708;

                $datos[$i]['G2913_C47709'] = $key->G2913_C47709;

                $datos[$i]['G2913_C47710'] = $key->G2913_C47710;

                $datos[$i]['G2913_C47711'] = $key->G2913_C47711;

                $datos[$i]['G2913_C47712'] = $key->G2913_C47712;

                $datos[$i]['G2913_C47713'] = $key->G2913_C47713;

                $datos[$i]['G2913_C47714'] = $key->G2913_C47714;

                $datos[$i]['G2913_C47715'] = $key->G2913_C47715;

                $datos[$i]['G2913_C47716'] = $key->G2913_C47716;

                $datos[$i]['G2913_C47717'] = $key->G2913_C47717;

                $datos[$i]['G2913_C47718'] = $key->G2913_C47718;

                $datos[$i]['G2913_C47719'] = explode(' ', $key->G2913_C47719)[0];
  
                $hora = '';
                if(!is_null($key->G2913_C47720)){
                    $hora = explode(' ', $key->G2913_C47720)[1];
                }

                $datos[$i]['G2913_C47720'] = $hora;

                $datos[$i]['G2913_C47721'] = $key->G2913_C47721;

                $datos[$i]['G2913_C47722'] = $key->G2913_C47722;

                $datos[$i]['G2913_C47723'] = $key->G2913_C47723;

                $datos[$i]['G2913_C47724'] = $key->G2913_C47724;

                $datos[$i]['G2913_C47725'] = $key->G2913_C47725;

                $datos[$i]['G2913_C48100'] = $key->G2913_C48100;

                $datos[$i]['G2913_C48101'] = $key->G2913_C48101;

                $datos[$i]['G2913_C48187'] = $key->G2913_C48187;

                $datos[$i]['G2913_C48182'] = $key->G2913_C48182;

                $datos[$i]['G2913_C48183'] = $key->G2913_C48183;

                $datos[$i]['G2913_C48184'] = $key->G2913_C48184;

                $datos[$i]['G2913_C48185'] = explode(' ', $key->G2913_C48185)[0];

                $datos[$i]['G2913_C48186'] = $key->G2913_C48186;

                $datos[$i]['G2913_C48102'] = $key->G2913_C48102;

                $datos[$i]['G2913_C48103'] = $key->G2913_C48103;

                $datos[$i]['G2913_C48201'] = explode(' ', $key->G2913_C48201)[0];

                $datos[$i]['G2913_C48202'] = explode(' ', $key->G2913_C48202)[0];
      
                $datos[$i]['principal'] = $key->principal;
                $i++;
            }
            echo json_encode($datos);
        }


        //JDBD-2020-05-03 : Datos de la lista de la izquierda
        if(isset($_POST['CallDatosJson'])){

            $strLimit_t = " LIMIT 0, 50";

            //JDBD-2020-05-03 : Preguntamos si esta funcion es llamada por el boton de (Buscar o lupa) o por el scroll.
            if (isset($_POST["strScroll_t"])) {
                if ($_POST["strScroll_t"] == "si") {
                    $strLimit_t = " LIMIT ".$_POST["inicio_t"].", ".$_POST["fin_t"];
                }
            }

            //JDBD-2020-05-03 : Averiguamos si el usuario en session solo puede ver sus propios registros.
            $strRegPro_t = "SELECT PEOBUS_VeRegPro__b AS reg FROM ".$BaseDatos_systema.".PEOBUS WHERE PEOBUS_ConsInte__USUARI_b = ".$_POST["idUsuario"]." AND PEOBUS_ConsInte__GUION__b = 2913";

            $resRegPro_t = $mysqli->query($strRegPro_t);

            if ($resRegPro_t->num_rows > 0) {

                $objRegPro_t = $resRegPro_t->fetch_object();
                
                if ($objRegPro_t->reg != 0) {
                    $strRegProp_t = " AND G2913_Usuario = ".$_POST["idUsuario"]." ";
                }else{
                    $strRegProp_t = "";
                }

            }else{
                $strRegProp_t = "";
            }

            //JDBD-2020-05-03 : Consulta estandar de los registros del guion.
            $Lsql = "SELECT G2913_ConsInte__b as id,  a.LISOPC_Nombre____b as camp1 , G2913_C47708 as camp2 
                     FROM ".$BaseDatos.".G2913  LEFT JOIN ".$BaseDatos_systema.".LISOPC as a ON a.LISOPC_ConsInte__b = G2913_C47728 WHERE TRUE ".$strRegProp_t;

            // Si lo que estamos consultando es de tareas de backoffice cambia la consulta
            if(isset($_POST['tareaBackoffice']) && $_POST['tareaBackoffice'] == 1 && isset($_POST['muestra']) && $_POST['muestra'] != 0){

                $Lsql = "SELECT G2913_ConsInte__b as id,  a.LISOPC_Nombre____b as camp1 , G2913_C47708 as camp2 FROM ".$BaseDatos.".G2913  LEFT JOIN ".$BaseDatos_systema.".LISOPC as a ON a.LISOPC_ConsInte__b = G2913_C47728 JOIN ".$BaseDatos.".G2913_M".$_POST['muestra']." ON G2913_ConsInte__b = G2913_M".$_POST['muestra']."_CoInMiPo__b 
                    WHERE ( (G2913_M".$_POST['muestra']."_Estado____b = 0 OR G2913_M".$_POST['muestra']."_Estado____b = 1 OR G2913_M".$_POST['muestra']."_Estado____b = 3) OR (G2913_M".$_POST['muestra']."_Estado____b = 2 AND G2913_M".$_POST['muestra']."_FecHorAge_b <= NOW() ) )";

                if($_POST['tareaTipoDist'] != 1){
                    $Lsql .= " AND G2913_M".$_POST['muestra']."_ConIntUsu_b = ".$_POST["idUsuario"]." ";
                }
            }

            if (isset($_POST["arrNumerosFiltros_t"])) {

                //JDBD-2020-05-03 : Busqueda Avanzada.

                $arrNumerosFiltros_t = explode(",", $_POST["arrNumerosFiltros_t"]);

                $intNumerosFiltros_t = count($arrNumerosFiltros_t);

                if ($intNumerosFiltros_t > 0) {
                    $Lsql .= " AND (";
                    foreach ($arrNumerosFiltros_t as $key => $filtro) {
                        if (is_numeric($_POST["selCampo_".$filtro])) {
                            $Lsql .= operadorYFiltro("G2913_C".$_POST["selCampo_".$filtro],$_POST["selOperador_".$filtro],$_POST["tipo_".$filtro],$_POST["valor_".$filtro]);
                        }else{
                            $Lsql .= operadorYFiltro($_POST["selCampo_".$filtro],$_POST["selOperador_".$filtro],$_POST["tipo_".$filtro],$_POST["valor_".$filtro]);
                        }

                        if (array_key_exists(($key+1),$arrNumerosFiltros_t)) {
                            if (isset($_POST["selCondicion_".($arrNumerosFiltros_t[$key+1])])) {
                                $Lsql .= $_POST["selCondicion_".($arrNumerosFiltros_t[$key+1])]." ";
                            }
                        }
                    }
                    $Lsql .= ") ";
                }

            }else{

                //JDBD-2020-05-03 : Busqueda Sencilla por la Lupa.

                $B = $_POST["B"];

                if ($B != "" && $B != NULL) {
                    $Lsql .= " AND (G2913_C47728 LIKE '%".$B."%' OR G2913_C47708 LIKE '%".$B."%') ";
                }

            }


            $Lsql .= " ORDER BY G2913_ConsInte__b DESC".$strLimit_t; 

            $result = $mysqli->query($Lsql);
            $datos = array();
            $i = 0;
            while($key = $result->fetch_object()){
                $datos[$i]['camp1'] = strtoupper(($key->camp1));
                $datos[$i]['camp2'] = strtoupper(($key->camp2));
                $datos[$i]['id'] = $key->id;
                $i++;
            }
            echo json_encode($datos);
        }

        if(isset($_POST['getListaHija'])){
            $Lsql = "SELECT LISOPC_ConsInte__b , LISOPC_Nombre____b FROM ".$BaseDatos_systema.".LISOPC WHERE LISOPC_ConsInte__LISOPC_Depende_b = ".$_POST['idPadre']." AND LISOPC_ConsInte__OPCION_b = ".$_POST['opcionID'];
            $res = $mysqli->query($Lsql);
            echo "<option value='0'>Seleccione</option>";
            while($key = $res->fetch_object()){
                echo "<option value='".$key->LISOPC_ConsInte__b."'>".$key->LISOPC_Nombre____b."</option>";
            }
        }


        //Esto ya es para cargar los combos en la grilla

        if(isset($_GET['CallDatosLisop_'])){
            $lista = $_GET['idLista'];
            $comboe = $_GET['campo'];
            $Lsql = "SELECT LISOPC_ConsInte__b AS OPCION_ConsInte__b, LISOPC_Nombre____b AS OPCION_Nombre____b FROM ".$BaseDatos_systema.".LISOPC WHERE LISOPC_ConsInte__OPCION_b = ".$lista." ORDER BY LISOPC_Nombre____b";
            
            $combo = $mysqli->query($Lsql);
            echo '<select class="form-control input-sm"  name="'.$comboe.'" id="'.$comboe.'">';
            echo '<option value="0">Seleccione</option>';
            while($obj = $combo->fetch_object()){
                echo "<option value='".$obj->OPCION_ConsInte__b."'>".$obj->OPCION_Nombre____b."</option>";
            }   
            echo '</select>'; 
        } 

        


        // esto carga los datos de la grilla CallDatosJson
        if(isset($_GET['CallDatosJson'])){
            $page = $_POST['page'];  // Almacena el numero de pagina actual
            $limit = $_POST['rows']; // Almacena el numero de filas que se van a mostrar por pagina
            $sidx = $_POST['sidx'];  // Almacena el indice por el cual se harÃ¡ la ordenaciÃ³n de los datos
            $sord = $_POST['sord'];  // Almacena el modo de ordenaciÃ³n
            if(!$sidx) $sidx =1;
            //Se hace una consulta para saber cuantos registros se van a mostrar
            $result = $mysqli->query("SELECT COUNT(*) AS count FROM ".$BaseDatos.".G2913");
            // Se obtiene el resultado de la consulta
            $fila = $result->fetch_array();
            $count = $fila['count'];
            //En base al numero de registros se obtiene el numero de paginas
            if( $count >0 ) {
                $total_pages = ceil($count/$limit);
            } else {
                $total_pages = 0;
            }
            if ($page > $total_pages)
                $page=$total_pages;

            //Almacena numero de registro donde se va a empezar a recuperar los registros para la pagina
            $start = $limit*$page - $limit; 
            //Consulta que devuelve los registros de una sola pagina

            $Lsql = 'SELECT G2913_ConsInte__b, G2913_FechaInsercion , G2913_Usuario ,  G2913_CodigoMiembro  , G2913_PoblacionOrigen , G2913_EstadoDiligenciamiento ,  G2913_IdLlamada , G2913_C47728 as principal , a.LISOPC_Nombre____b as G2913_C47728,G2913_C47708,G2913_C47709,G2913_C47710,G2913_C47711,G2913_C47712,G2913_C47713,G2913_C47714,G2913_C47715,G2913_C47716, b.LISOPC_Nombre____b as G2913_C47717, c.LISOPC_Nombre____b as G2913_C47718,G2913_C47719,G2913_C47720,G2913_C47721,G2913_C47722,G2913_C47723,G2913_C47724,G2913_C47725,G2913_C48100,G2913_C48101, d.LISOPC_Nombre____b as G2913_C48187,G2913_C48182,G2913_C48183,G2913_C48184,G2913_C48185,G2913_C48186,G2913_C48102,G2913_C48103,G2913_C48201,G2913_C48202 FROM '.$BaseDatos.'.G2913 LEFT JOIN '.$BaseDatos_systema.'.LISOPC as a ON a.LISOPC_ConsInte__b =  G2913_C47728 LEFT JOIN '.$BaseDatos_systema.'.LISOPC as b ON b.LISOPC_ConsInte__b =  G2913_C47717 LEFT JOIN '.$BaseDatos_systema.'.LISOPC as c ON c.LISOPC_ConsInte__b =  G2913_C47718 LEFT JOIN '.$BaseDatos_systema.'.LISOPC as d ON d.LISOPC_ConsInte__b =  G2913_C48187';
            if ($_REQUEST["_search"] == "false") {
                $where = " where 1";
            } else {
                $operations = array(
                    'eq' => "= '%s'",            // Equal
                    'ne' => "<> '%s'",           // Not equal
                    'lt' => "< '%s'",            // Less than
                    'le' => "<= '%s'",           // Less than or equal
                    'gt' => "> '%s'",            // Greater than
                    'ge' => ">= '%s'",           // Greater or equal
                    'bw' => "like '%s%%'",       // Begins With
                    'bn' => "not like '%s%%'",   // Does not begin with
                    'in' => "in ('%s')",         // In
                    'ni' => "not in ('%s')",     // Not in
                    'ew' => "like '%%%s'",       // Ends with
                    'en' => "not like '%%%s'",   // Does not end with
                    'cn' => "like '%%%s%%'",     // Contains
                    'nc' => "not like '%%%s%%'", // Does not contain
                    'nu' => "is null",           // Is null
                    'nn' => "is not null"        // Is not null
                ); 
                $value = $mysqli->real_escape_string($_REQUEST["searchString"]);
                $where = sprintf(" where %s ".$operations[$_REQUEST["searchOper"]], $_REQUEST["searchField"], $value);
            }
            $Lsql .= $where.' ORDER BY '.$sidx.' '.$sord.' LIMIT '.$start.','.$limit;
            $result = $mysqli->query($Lsql);
            $respuesta = array();
            $respuesta['page'] = $page;
            $respuesta['total'] = $total_pages;
            $respuesta['records'] = $count;
            $i=0;
            while( $fila = $result->fetch_object() ) {  
                

                $hora_a = '';
                //esto es para todo los tipo fecha, para que no muestre la parte de la hora
                if(!is_null($fila->G2913_C47720)){
                    $hora_a = explode(' ', $fila->G2913_C47720)[1];
                }
                $respuesta['rows'][$i]['id']=$fila->G2913_ConsInte__b;
                $respuesta['rows'][$i]['cell']=array($fila->G2913_ConsInte__b , ($fila->G2913_C47728) , ($fila->G2913_C47708) , ($fila->G2913_C47709) , ($fila->G2913_C47710) , ($fila->G2913_C47711) , ($fila->G2913_C47712) , ($fila->G2913_C47713) , ($fila->G2913_C47714) , ($fila->G2913_C47715) , ($fila->G2913_C47716) , ($fila->G2913_C47717) , ($fila->G2913_C47718) , explode(' ', $fila->G2913_C47719)[0] , $hora_a , ($fila->G2913_C47721) , ($fila->G2913_C47722) , ($fila->G2913_C47723) , ($fila->G2913_C47724) , ($fila->G2913_C47725) , ($fila->G2913_C48100) , ($fila->G2913_C48101) , ($fila->G2913_C48187) , ($fila->G2913_C48182) , ($fila->G2913_C48183) , ($fila->G2913_C48184) , explode(' ', $fila->G2913_C48185)[0] , ($fila->G2913_C48186) , ($fila->G2913_C48102) , ($fila->G2913_C48103) , explode(' ', $fila->G2913_C48201)[0] , explode(' ', $fila->G2913_C48202)[0] );
                $i++;
            }
            // La respuesta se regresa como json
            echo json_encode($respuesta);
        }

        if(isset($_POST['CallEliminate'])){
            if($_POST['oper'] == 'del'){
                $Lsql = "DELETE FROM ".$BaseDatos.".G2913 WHERE G2913_ConsInte__b = ".$_POST['id'];
                if ($mysqli->query($Lsql) === TRUE) {
                    echo "1";
                } else {
                    echo "Error eliminado los registros : " . $mysqli->error;
                }
            }
        }

        if(isset($_POST['callDatosNuevamente'])){

            //JDBD-2020-05-03 : Averiguamos si el usuario en session solo puede ver sus propios registros.
            $strRegPro_t = "SELECT PEOBUS_VeRegPro__b AS reg FROM ".$BaseDatos_systema.".PEOBUS WHERE PEOBUS_ConsInte__USUARI_b = ".$_POST["idUsuario"]." AND PEOBUS_ConsInte__GUION__b = 2913";

            $resRegPro_t = $mysqli->query($strRegPro_t);

            if ($resRegPro_t->num_rows > 0) {
                
                $objRegPro_t = $resRegPro_t->fetch_object();

                if ($objRegPro_t->reg != 0) {
                    $strRegProp_t = ' AND G2913_Usuario = '.$_POST["idUsuario"].' ';
                }else{
                    $strRegProp_t = '';
                }
                
            }else{
                $strRegProp_t = '';
            }


            $inicio = $_POST['inicio'];
            $fin = $_POST['fin'];

            $B = "";

            if (isset($_POST["B"])) {
                $B = $_POST["B"];
            }

            //JDBD-2020-05-03 : Consulta estandar para los registros del guion.
            $Zsql = 'SELECT  G2913_ConsInte__b as id,  a.LISOPC_Nombre____b as camp1 , G2913_C47708 as camp2  FROM '.$BaseDatos.'.G2913 WHERE TRUE'.$strRegProp_t;
            
            // Si lo que estamos consultando es de tareas de backoffice cambia la consulta
            if(isset($_POST['tareaBackoffice']) && $_POST['tareaBackoffice'] == 1 && isset($_POST['muestra']) && $_POST['muestra'] != 0){

                $Zsql = "SELECT G2913_ConsInte__b as id,  a.LISOPC_Nombre____b as camp1 , G2913_C47708 as camp2  FROM ".$BaseDatos.".G2913  LEFT JOIN ".$BaseDatos_systema.".LISOPC as a ON a.LISOPC_ConsInte__b = G2913_C47728 JOIN ".$BaseDatos.".G2913_M".$_POST['muestra']." ON G2913_ConsInte__b = G2913_M".$_POST['muestra']."_CoInMiPo__b 
                    WHERE ( (G2913_M".$_POST['muestra']."_Estado____b = 0 OR G2913_M".$_POST['muestra']."_Estado____b = 1 OR G2913_M".$_POST['muestra']."_Estado____b = 3) OR (G2913_M".$_POST['muestra']."_Estado____b = 2 AND G2913_M".$_POST['muestra']."_FecHorAge_b <= NOW() ) )";

                if($_POST['tareaTipoDist'] != 1){
                    $Zsql .= " AND G2913_M".$_POST['muestra']."_ConIntUsu_b = ".$_POST["idUsuario"]." ";
                }
            }

            //JDBD-2020-05-03 : Este es el campo de busqueda sencilla que esta al lado de la lupa.
            if ($B != "") {
                $Zsql .= ' AND (G2913_C47728 LIKE "%'.$B.'%" OR G2913_C47708 LIKE "%'.$B.'%") ';
            }

            $Zsql .= ' ORDER BY G2913_ConsInte__b DESC LIMIT '.$inicio.' , '.$fin;
            
            $result = $mysqli->query($Zsql);
            while($obj = $result->fetch_object()){
                echo "<tr class='CargarDatos' id='".$obj->id."'>
                    <td>
                        <p style='font-size:14px;'><b>".strtoupper(($obj->camp1))."</b></p>
                        <p style='font-size:12px; margin-top:-10px;'>".strtoupper(($obj->camp2))."</p>
                    </td>
                </tr>";
            } 
        }
              
        //Inserciones o actualizaciones
        if(isset($_POST["oper"]) && isset($_GET['insertarDatosGrilla'])){
            $Lsql  = '';

            $validar = 0;
            $valor=array("$",","," ","%");
            $LsqlU = "UPDATE ".$BaseDatos.".G2913 SET "; 
            $LsqlI = "INSERT INTO ".$BaseDatos.".G2913(";
            $LsqlV = " VALUES ("; 
  

            if(isset($_POST["G2913_C47728"])){
                $separador = "";
                if($validar == 1){
                    $separador = ",";
                }

                $LsqlU .= $separador."G2913_C47728 = '".$_POST["G2913_C47728"]."'";
                $LsqlI .= $separador."G2913_C47728";
                $LsqlV .= $separador."'".$_POST["G2913_C47728"]."'";
                $validar = 1;
            }
                
  

            if(isset($_POST["G2913_C47708"])){
                $separador = "";
                if($validar == 1){
                    $separador = ",";
                }

                $LsqlU .= $separador."G2913_OrigenUltimoCargue = '".$_POST["G2913_C47708"]."', G2913_FechaUltimoCargue = NOW() ";
                $LsqlI .= $separador."G2913_C47708, G2913_OrigenUltimoCargue ";
                $LsqlV .= $separador."'".$_POST["G2913_C47708"]."', '".$_POST["G2913_C47708"]."'";
                $validar = 1;
            }
                
  

            if(isset($_POST["G2913_C47709"])){
                $separador = "";
                if($validar == 1){
                    $separador = ",";
                }

                $LsqlU .= $separador."G2913_C47709 = '".$_POST["G2913_C47709"]."'";
                $LsqlI .= $separador."G2913_C47709";
                $LsqlV .= $separador."'".$_POST["G2913_C47709"]."'";
                $validar = 1;
            }
                
  

            if(isset($_POST["G2913_C47710"])){
                $separador = "";
                if($validar == 1){
                    $separador = ",";
                }

                $LsqlU .= $separador."G2913_C47710 = '".$_POST["G2913_C47710"]."'";
                $LsqlI .= $separador."G2913_C47710";
                $LsqlV .= $separador."'".$_POST["G2913_C47710"]."'";
                $validar = 1;
            }
                
  

            if(isset($_POST["G2913_C47711"])){
                $separador = "";
                if($validar == 1){
                    $separador = ",";
                }

                $LsqlU .= $separador."G2913_C47711 = '".$_POST["G2913_C47711"]."'";
                $LsqlI .= $separador."G2913_C47711";
                $LsqlV .= $separador."'".$_POST["G2913_C47711"]."'";
                $validar = 1;
            }
                
  

            if(isset($_POST["G2913_C47712"])){
                $separador = "";
                if($validar == 1){
                    $separador = ",";
                }

                $LsqlU .= $separador."G2913_C47712 = '".$_POST["G2913_C47712"]."'";
                $LsqlI .= $separador."G2913_C47712";
                $LsqlV .= $separador."'".$_POST["G2913_C47712"]."'";
                $validar = 1;
            }
                
  

            if(isset($_POST["G2913_C47713"])){
                $separador = "";
                if($validar == 1){
                    $separador = ",";
                }

                $LsqlU .= $separador."G2913_C47713 = '".$_POST["G2913_C47713"]."'";
                $LsqlI .= $separador."G2913_C47713";
                $LsqlV .= $separador."'".$_POST["G2913_C47713"]."'";
                $validar = 1;
            }
                
  

            if(isset($_POST["G2913_C47714"])){
                $separador = "";
                if($validar == 1){
                    $separador = ",";
                }

                $LsqlU .= $separador."G2913_C47714 = '".$_POST["G2913_C47714"]."'";
                $LsqlI .= $separador."G2913_C47714";
                $LsqlV .= $separador."'".$_POST["G2913_C47714"]."'";
                $validar = 1;
            }
                
  

            if(isset($_POST["G2913_C47715"])){
                $separador = "";
                if($validar == 1){
                    $separador = ",";
                }

                $LsqlU .= $separador."G2913_C47715 = '".$_POST["G2913_C47715"]."'";
                $LsqlI .= $separador."G2913_C47715";
                $LsqlV .= $separador."'".$_POST["G2913_C47715"]."'";
                $validar = 1;
            }
                
  

            if(isset($_POST["G2913_C47716"])){
                $separador = "";
                if($validar == 1){
                    $separador = ",";
                }

                $LsqlU .= $separador."G2913_C47716 = '".$_POST["G2913_C47716"]."'";
                $LsqlI .= $separador."G2913_C47716";
                $LsqlV .= $separador."'".$_POST["G2913_C47716"]."'";
                $validar = 1;
            }
                
 
            $G2913_C47717 = NULL;
            if(isset($_POST["tipificacion"])){    
                if($_POST["tipificacion"] != ''){
                    $separador = "";
                    if($validar == 1){
                        $separador = ",";
                    }
                    $G2913_C47717 = str_replace(' ', '',$_POST["tipificacion"]);
                    $LsqlU .= $separador." G2913_C47717 = ".$G2913_C47717;
                    $LsqlI .= $separador." G2913_C47717";
                    $LsqlV .= $separador.$G2913_C47717;
                    $validar = 1;

                    
                }
            }
 
            $G2913_C47718 = NULL;
            if(isset($_POST["reintento"])){    
                if($_POST["reintento"] != ''){
                    $separador = "";
                    if($validar == 1){
                        $separador = ",";
                    }
                    $G2913_C47718 = str_replace(' ', '',$_POST["reintento"]);
                    $LsqlU .= $separador." G2913_C47718 = ".$G2913_C47718;
                    $LsqlI .= $separador." G2913_C47718";
                    $LsqlV .= $separador.$G2913_C47718;
                    $validar = 1;
                }
            }
 
            $G2913_C47719 = NULL;
            //este es de tipo date hay que preguntar si esta vacia o no
            if(isset($_POST["TxtFechaReintento"])){    
                if($_POST["TxtFechaReintento"] != ''){
                    if(validateDate(str_replace(' ', '',$_POST["TxtFechaReintento"])." 00:00:00")){
                        $separador = "";
                        if($validar == 1){
                            $separador = ",";
                        }
                        $G2913_C47719 = "'".str_replace(' ', '',$_POST["TxtFechaReintento"])." 00:00:00'";
                        $LsqlU .= $separador." G2913_C47719 = ".$G2913_C47719;
                        $LsqlI .= $separador." G2913_C47719";
                        $LsqlV .= $separador.$G2913_C47719;
                        $validar = 1;
                    }
                }else{
                    if(!isset($_GET["LlamadoExterno"])){
                        echo "Validar el campo Fecha de agenda";
                        exit();
                    }
                }
            }
 
            $G2913_C47720 = NULL;
            //este es de tipo date hay que preguntar si esta vacia o no
            if(isset($_POST["TxtHoraReintento"])){    
                if($_POST["TxtHoraReintento"] != ''){
                    if(validateDate(str_replace(' ', '',$_POST["TxtFechaReintento"])." ".str_replace(' ', '',$_POST["TxtHoraReintento"]))){
                        $separador = "";
                        if($validar == 1){
                            $separador = ",";
                        }
                        $G2913_C47720 = "'".str_replace(' ', '',$_POST["TxtFechaReintento"])." ".str_replace(' ', '',$_POST["TxtHoraReintento"])."'";
                        $LsqlU .= $separador." G2913_C47720 = ".$G2913_C47720;
                        $LsqlI .= $separador." G2913_C47720";
                        $LsqlV .= $separador.$G2913_C47720;
                        $validar = 1;
                    }else{
                        if(!isset($_GET["LlamadoExterno"])){
                            echo "Validar el campo de la hora de agenda";
                            exit();
                        }
                    }
                }
            }
 
            $G2913_C47721 = NULL;
            if(isset($_POST["textAreaComentarios"])){    
                if($_POST["textAreaComentarios"] != ''){
                    $separador = "";
                    if($validar == 1){
                        $separador = ",";
                    }
                    $G2913_C47721 = "'".$_POST["textAreaComentarios"]."'";
                    $LsqlU .= $separador." G2913_C47721 = ".$G2913_C47721;
                    $LsqlI .= $separador." G2913_C47721";
                    $LsqlV .= $separador.$G2913_C47721;
                    $validar = 1;
                }
            }
  

            if(isset($_POST["G2913_C47722"])){
                $separador = "";
                if($validar == 1){
                    $separador = ",";
                }

                $LsqlU .= $separador."G2913_C47722 = '".$_POST["G2913_C47722"]."'";
                $LsqlI .= $separador."G2913_C47722";
                $LsqlV .= $separador."'".$_POST["G2913_C47722"]."'";
                $validar = 1;
            }
                
  

            if(isset($_POST["G2913_C47723"])){
                $separador = "";
                if($validar == 1){
                    $separador = ",";
                }

                $LsqlU .= $separador."G2913_C47723 = '".$_POST["G2913_C47723"]."'";
                $LsqlI .= $separador."G2913_C47723";
                $LsqlV .= $separador."'".$_POST["G2913_C47723"]."'";
                $validar = 1;
            }
                
  

            if(isset($_POST["G2913_C47724"])){
                $separador = "";
                if($validar == 1){
                    $separador = ",";
                }

                $LsqlU .= $separador."G2913_C47724 = '".$_POST["G2913_C47724"]."'";
                $LsqlI .= $separador."G2913_C47724";
                $LsqlV .= $separador."'".$_POST["G2913_C47724"]."'";
                $validar = 1;
            }
                
  

            if(isset($_POST["G2913_C47725"])){
                $separador = "";
                if($validar == 1){
                    $separador = ",";
                }

                $LsqlU .= $separador."G2913_C47725 = '".$_POST["G2913_C47725"]."'";
                $LsqlI .= $separador."G2913_C47725";
                $LsqlV .= $separador."'".$_POST["G2913_C47725"]."'";
                $validar = 1;
            }
                
  

            if(isset($_POST["G2913_C47726"])){
                $separador = "";
                if($validar == 1){
                    $separador = ",";
                }

                $LsqlU .= $separador."G2913_C47726 = '".$_POST["G2913_C47726"]."'";
                $LsqlI .= $separador."G2913_C47726";
                $LsqlV .= $separador."'".$_POST["G2913_C47726"]."'";
                $validar = 1;
            }
                
  

            if(isset($_POST["G2913_C47727"])){
                $separador = "";
                if($validar == 1){
                    $separador = ",";
                }

                $LsqlU .= $separador."G2913_C47727 = '".$_POST["G2913_C47727"]."'";
                $LsqlI .= $separador."G2913_C47727";
                $LsqlV .= $separador."'".$_POST["G2913_C47727"]."'";
                $validar = 1;
            }
                
  

            if(isset($_POST["G2913_C48100"])){
                $separador = "";
                if($validar == 1){
                    $separador = ",";
                }

                $LsqlU .= $separador."G2913_C48100 = '".$_POST["G2913_C48100"]."'";
                $LsqlI .= $separador."G2913_C48100";
                $LsqlV .= $separador."'".$_POST["G2913_C48100"]."'";
                $validar = 1;
            }
                
  

            if(isset($_POST["G2913_C48101"])){
                $separador = "";
                if($validar == 1){
                    $separador = ",";
                }

                $LsqlU .= $separador."G2913_C48101 = '".$_POST["G2913_C48101"]."'";
                $LsqlI .= $separador."G2913_C48101";
                $LsqlV .= $separador."'".$_POST["G2913_C48101"]."'";
                $validar = 1;
            }
                
  

            if(isset($_POST["G2913_C48187"])){
                $separador = "";
                if($validar == 1){
                    $separador = ",";
                }

                $LsqlU .= $separador."G2913_C48187 = '".$_POST["G2913_C48187"]."'";
                $LsqlI .= $separador."G2913_C48187";
                $LsqlV .= $separador."'".$_POST["G2913_C48187"]."'";
                $validar = 1;
            }
                
  
            $G2913_C48182 = NULL;
            //este es de tipo numero no se deja ir asi '', si est avacio lo mejor es no mandarlo
            if(isset($_POST["G2913_C48182"])){
                if($_POST["G2913_C48182"] != ''){
                    $_POST["G2913_C48182"]=str_replace($valor,"",$_POST["G2913_C48182"]);
                    $separador = "";
                    if($validar == 1){
                        $separador = ",";
                    }

                    $G2913_C48182 = $_POST["G2913_C48182"];
                    $LsqlU .= $separador." G2913_C48182 = ".$G2913_C48182."";
                    $LsqlI .= $separador." G2913_C48182";
                    $LsqlV .= $separador.$G2913_C48182;
                    $validar = 1;
                }
            }
  

            if(isset($_POST["G2913_C48183"])){
                $separador = "";
                if($validar == 1){
                    $separador = ",";
                }

                $LsqlU .= $separador."G2913_C48183 = '".$_POST["G2913_C48183"]."'";
                $LsqlI .= $separador."G2913_C48183";
                $LsqlV .= $separador."'".$_POST["G2913_C48183"]."'";
                $validar = 1;
            }
                
  

            if(isset($_POST["G2913_C48184"])){
                $separador = "";
                if($validar == 1){
                    $separador = ",";
                }

                $LsqlU .= $separador."G2913_C48184 = '".$_POST["G2913_C48184"]."'";
                $LsqlI .= $separador."G2913_C48184";
                $LsqlV .= $separador."'".$_POST["G2913_C48184"]."'";
                $validar = 1;
            }
                
 
            $G2913_C48185 = NULL;
            //este es de tipo date hay que preguntar si esta vacia o no
            if(isset($_POST["G2913_C48185"])){    
                if($_POST["G2913_C48185"] != ''){
                    if(validateDate(str_replace(' ', '',$_POST["G2913_C48185"])." 00:00:00")){
                        $separador = "";
                        if($validar == 1){
                            $separador = ",";
                        }

                        $tieneHora = explode(' ' , $_POST["G2913_C48185"]);
                        if(count($tieneHora) > 1){
                            $G2913_C48185 = "'".$_POST["G2913_C48185"]."'";
                        }else{
                            $G2913_C48185 = "'".str_replace(' ', '',$_POST["G2913_C48185"])." 00:00:00'";
                        }


                        $LsqlU .= $separador." G2913_C48185 = ".$G2913_C48185;
                        $LsqlI .= $separador." G2913_C48185";
                        $LsqlV .= $separador.$G2913_C48185;
                        $validar = 1;
                    }else{
                        if(!isset($_GET["LlamadoExterno"])){
                            $data=json_encode(array("estado"=>"Error","mensaje"=>"Validar el campo FECHA_AUDITADO_Q_DY"));
                            echo $data;
                            exit();
                        }
                    }
                }
            }
  

            if(isset($_POST["G2913_C48186"])){
                $separador = "";
                if($validar == 1){
                    $separador = ",";
                }

                $LsqlU .= $separador."G2913_C48186 = '".$_POST["G2913_C48186"]."'";
                $LsqlI .= $separador."G2913_C48186";
                $LsqlV .= $separador."'".$_POST["G2913_C48186"]."'";
                $validar = 1;
            }
                
  

            if(isset($_POST["G2913_C48102"])){
                $separador = "";
                if($validar == 1){
                    $separador = ",";
                }

                $LsqlU .= $separador."G2913_C48102 = '".$_POST["G2913_C48102"]."'";
                $LsqlI .= $separador."G2913_C48102";
                $LsqlV .= $separador."'".$_POST["G2913_C48102"]."'";
                $validar = 1;
            }
                
  

            if(isset($_POST["G2913_C48103"])){
                $separador = "";
                if($validar == 1){
                    $separador = ",";
                }

                $LsqlU .= $separador."G2913_C48103 = '".$_POST["G2913_C48103"]."'";
                $LsqlI .= $separador."G2913_C48103";
                $LsqlV .= $separador."'".$_POST["G2913_C48103"]."'";
                $validar = 1;
            }
                
 
            $G2913_C48201 = NULL;
            //este es de tipo date hay que preguntar si esta vacia o no
            if(isset($_POST["G2913_C48201"])){    
                if($_POST["G2913_C48201"] != ''){
                    if(validateDate(str_replace(' ', '',$_POST["G2913_C48201"])." 00:00:00")){
                        $separador = "";
                        if($validar == 1){
                            $separador = ",";
                        }

                        $tieneHora = explode(' ' , $_POST["G2913_C48201"]);
                        if(count($tieneHora) > 1){
                            $G2913_C48201 = "'".$_POST["G2913_C48201"]."'";
                        }else{
                            $G2913_C48201 = "'".str_replace(' ', '',$_POST["G2913_C48201"])." 00:00:00'";
                        }


                        $LsqlU .= $separador." G2913_C48201 = ".$G2913_C48201;
                        $LsqlI .= $separador." G2913_C48201";
                        $LsqlV .= $separador.$G2913_C48201;
                        $validar = 1;
                    }else{
                        if(!isset($_GET["LlamadoExterno"])){
                            $data=json_encode(array("estado"=>"Error","mensaje"=>"Validar el campo Fecha Inicial"));
                            echo $data;
                            exit();
                        }
                    }
                }
            }
 
            $G2913_C48202 = NULL;
            //este es de tipo date hay que preguntar si esta vacia o no
            if(isset($_POST["G2913_C48202"])){    
                if($_POST["G2913_C48202"] != ''){
                    if(validateDate(str_replace(' ', '',$_POST["G2913_C48202"])." 00:00:00")){
                        $separador = "";
                        if($validar == 1){
                            $separador = ",";
                        }

                        $tieneHora = explode(' ' , $_POST["G2913_C48202"]);
                        if(count($tieneHora) > 1){
                            $G2913_C48202 = "'".$_POST["G2913_C48202"]."'";
                        }else{
                            $G2913_C48202 = "'".str_replace(' ', '',$_POST["G2913_C48202"])." 00:00:00'";
                        }


                        $LsqlU .= $separador." G2913_C48202 = ".$G2913_C48202;
                        $LsqlI .= $separador." G2913_C48202";
                        $LsqlV .= $separador.$G2913_C48202;
                        $validar = 1;
                    }else{
                        if(!isset($_GET["LlamadoExterno"])){
                            $data=json_encode(array("estado"=>"Error","mensaje"=>"Validar el campo Fecha Final"));
                            echo $data;
                            exit();
                        }
                    }
                }
            }

                //JDBD - Llenado de Reintento y Clasificacion.
                if(isset($_POST["MonoEf"])){
                    
                    $LmonoEfLSql = "SELECT MONOEF_Contacto__b FROM ".$BaseDatos_systema.".MONOEF WHERE MONOEF_ConsInte__b = ".$_POST['MonoEf'];
                    
                    if ($resMonoEf = $mysqli->query($LmonoEfLSql)) {
                        if ($resMonoEf->num_rows > 0) {

                            $dataMonoEf = $resMonoEf->fetch_object();

                            $conatcto = $dataMonoEf->MONOEF_Contacto__b;

                            $separador = "";
                            if($validar == 1){
                                $separador = ",";
                            }

                            $LsqlU .= $separador."G2913_Clasificacion = ".$conatcto;
                            $LsqlI .= $separador."G2913_Clasificacion";
                            $LsqlV .= $separador.$conatcto;
                            $validar = 1;

                        }
                    }
                }            
                
            // Agregamos el paso origen cuando creamos un registro
            $pasoOrigenId = 0;

            $separador = "";
            if($validar == 1){
                $separador = ",";
            }

            $LsqlI .= $separador."G2913_PoblacionOrigen";
            $LsqlV .= $separador."'".$pasoOrigenId."'";
            $validar = 1;
            
            if(isset($_GET['id_gestion_cbxx2'])){
                $separador = "";
                if($validar == 1){
                    $separador = ",";
                }

                $LsqlU .= $separador."G2913_IdLlamada = '".$_GET['id_gestion_cbxx2']."'";
                $LsqlI .= $separador."G2913_IdLlamada";
                $LsqlV .= $separador."'".$_GET['id_gestion_cbxx2']."'";
                $validar = 1;
            }


            $padre = NULL;
            if(isset($_POST["padre"])){    
                if($_POST["padre"] != '0' && $_POST['padre'] != ''){
                    $separador = "";
                    if($validar == 1){
                        $separador = ",";
                    }

                    //primero hay que ir y buscar los campos
                    $Lsql = "SELECT GUIDET_ConsInte__PREGUN_De1_b FROM ".$BaseDatos_systema.".GUIDET WHERE GUIDET_ConsInte__GUION__Mae_b = ".$_POST['formpadre']." AND GUIDET_ConsInte__GUION__Det_b = ".$_POST['formhijo'];

                    $GuidRes = $mysqli->query($Lsql);
                    $campo = null;
                    while($ky = $GuidRes->fetch_object()){
                        $campo = $ky->GUIDET_ConsInte__PREGUN_De1_b;
                    }
                    $valorG = "G2913_C";
                    $valorH = $valorG.$campo;
                    $LsqlU .= $separador." " .$valorH." = ".$_POST["padre"];
                    $LsqlI .= $separador." ".$valorH;
                    $LsqlV .= $separador.$_POST['padre'] ;
                    $validar = 1;
                }
            }
            if(isset($_POST['oper'])){
                if($_POST["oper"] == 'add' ){
                    $LsqlI .= ", G2913_Usuario , G2913_FechaInsercion, G2913_CodigoMiembro";
                    if(!isset($_GET['CodigoMiembro']) && !is_numeric($_GET['CodigoMiembro'])){
                        $_GET['CodigoMiembro']=-1;
                    }
                    $LsqlV .= ", ".$_GET['usuario']." , '".date('Y-m-d H:i:s')."', ".$_GET['CodigoMiembro'];
                    $Lsql = $LsqlI.")" . $LsqlV.")";
                }else if($_POST["oper"] == 'edit' ){
                    $Lsql = $LsqlU." WHERE G2913_ConsInte__b =".$_POST["id"]; 
                    //echo $Lsql;die();
                }else if($_POST["oper"] == 'del' ){
                    $Lsql = "DELETE FROM ".$BaseDatos.".G2913 WHERE G2913_ConsInte__b = ".$_POST['id'];
                    $validar = 1;
                }
            }
            //si trae algo que insertar inserta

            //echo $Lsql;
            if($validar == 1){
                if ($mysqli->query($Lsql) === TRUE) {
                    if($_POST["oper"] == 'add' ){
                        $UltimoID = $mysqli->insert_id;
                        echo json_encode(array("estado"=>"ok","mensaje"=>$mysqli->insert_id));
                        //echo $mysqli->insert_id;
                    }else{
                        if(isset($_POST["id"]) && $_POST["id"] != '0' ){
                            $UltimoID = $_POST["id"];
                            echo json_encode(array("estado"=>"ok","mensaje"=>$UltimoID));
                            //echo $UltimoID;
                        }
                    }

                    

                } else {
                    echo json_encode(array("estado"=>"Error","mensaje"=>"Se genero un error al guardar la información"));
                    $queryCondia="INSERT INTO ".$BaseDatos_systema.".LOGGEST (LOGGEST_SQL_b,LOGGEST_Error_b,LOGGEST_Comentario_b)
                    VALUES(\"".$Lsql."\",\"".$mysqli->error."\",'Insercion Script')";
                    $mysqli->query($queryCondia);                    
                   // echo "Error Haciendo el proceso los registros : " . $mysqli->error;
                }
            }        

        }
    }

    if(isset($_GET["ConsultarHuesped"])) {
        $IdGuion = $_POST["IdGuion"];
        $ConsultaID = "SELECT GUION__ConsInte__PROYEC_b FROM DYALOGOCRM_SISTEMA.GUION_ WHERE GUION__ConsInte__b= '". $IdGuion ."';";
        if($ResultadoID = $mysqli->query($ConsultaID)) {
            $CantidadResultados = $ResultadoID->num_rows;
            if($CantidadResultados > 0) {
                while ($FilaResultado = $ResultadoID->fetch_assoc()) {
                    $IdProyecto= $FilaResultado['GUION__ConsInte__PROYEC_b'];
                    $php_response= $IdProyecto;
                    echo($php_response);
                    mysqli_close($mysqli);
                    exit;
                } 
            }else {
                //Sin Resultados
                $php_response = array("msg" => "Nada");
                mysqli_close($mysqli);
                echo json_encode($php_response);
                exit;
            }
        }else {
            mysqli_close($mysqli);
            $Falla = mysqli_error($mysqli);
            $php_response = array("msg" => "Error", "Falla" => $Falla);
            echo json_encode($php_response);
            exit;
        }
                
    }


    //Reporte - C_200
    if(isset($_GET["ConsultarReporte200"])) {
        $HoraI= '00:00:00';
        $HoraF= '23:59:59';
        $FechaI= $_POST['FechaInicial'];
        $FechaF= $_POST['FechaFinal'];
        $FechaInicial= $FechaI. ' ' .$HoraI;
        $FechaFinal= $FechaF. ' ' .$HoraF;

        $ArrayReporte200= array();
        $ConsultaReporte200 = "SELECT * FROM DYALOGOCRM_WEB.G3622 WHERE G3622_FechaInsercion BETWEEN '". $FechaInicial ."' AND '". $FechaFinal ."'";
        if($ResultadoReporte200 = $mysqli->query($ConsultaReporte200)) {
            $CantidadResultados = $ResultadoReporte200->num_rows;
            if($CantidadResultados > 0) {
                while ($FilaResultado = $ResultadoReporte200->fetch_assoc()) {   
                    $IdGestion= $FilaResultado['G3622_ConsInte__b'];
                    $FechaInsercion= $FilaResultado['G3622_FechaInsercion'];
                    $NumeroCuenta= $FilaResultado['G3622_C72270'];
                    $FechaRegistrada= $FilaResultado['G3622_C71982'];
                    $Host= $FilaResultado['G3622_C71990'];
                    $CodigoArea= $FilaResultado['G3622_C71987'];
                    $NumeroTelefono= $FilaResultado['G3622_C71993'];
                    $CodigoAccion= $FilaResultado['G3622_C72418'];
                    $CodigoResultadoI= $FilaResultado['G3622_C72279'];
                    $Comentario= $FilaResultado['G3622_C72386'];

                    //Consultar Codigo Resultado
                    $ConsultaReporteCR = "SELECT * FROM DYALOGOCRM_WEB.G3622_CODIGOS_RESULTADO WHERE G3622_ID_CODIGOS_RESULTADO= '". $CodigoResultadoI ."';";
                    if($ResultadoReporteCR = $mysqli->query($ConsultaReporteCR)) {
                        $CantidadResultadosCR = $ResultadoReporteCR->num_rows;
                        if($CantidadResultadosCR > 0) {
                            while ($FilaResultadoCR = $ResultadoReporteCR->fetch_assoc())  { 
                                $NombreCodigoResultado= $FilaResultadoCR['G3622_CODIGOS_RESULTADO_NOMBRE'];
                                $CodigoResultado= $FilaResultadoCR['G3622_CODIGOS_RESULTADO_LETRAS'];
                            }

                        }else {
                            //Sin Resultados
                            $php_response = array("msg" => "!No Existe Codigo Resultado!  //  Revisar Columna: G3622_C72279 ");
                            mysqli_close($mysqli);
                            echo json_encode($php_response);
                            exit;
                        }
                    }else {
                        mysqli_close($mysqli);
                        $Falla = mysqli_error($mysqli);
                        $php_response = array("msg" => "Error", "Falla" => $Falla);
                        echo json_encode($php_response);
                        exit;
                    }
                    
                    array_push($ArrayReporte200, array("0" => $IdGestion, "1" => $FechaInsercion, "2" => $NumeroCuenta, "3" => $FechaRegistrada, "4" => $Host, "5" => $CodigoArea, "6" => $NumeroTelefono, "7" => $CodigoAccion, "8" => $CodigoResultado, "9" => $Comentario));
                }
                
                //Todo Ok
                $php_response= array("msg" => "Ok", "Resultado" => $ArrayReporte200);
                echo json_encode($php_response);
                mysqli_close($mysqli);
                exit;

            }else {
                //Sin Resultados
                $php_response = array("msg" => "Nada");
                mysqli_close($mysqli);
                echo json_encode($php_response);
                exit;
            }
        }else {
            mysqli_close($mysqli);
            $Falla = mysqli_error($mysqli);
            $php_response = array("msg" => "Error", "Falla" => $Falla);
            echo json_encode($php_response);
            exit;
        }
    }

    //Reporte - C_600
    if(isset($_GET["ConsultarReporte600"])) {
        $HoraI= '00:00:00';
        $HoraF= '23:59:59';
        $FechaI= $_POST['FechaInicial'];
        $FechaF= $_POST['FechaFinal'];
        $FechaInicial= $FechaI. ' ' .$HoraI;
        $FechaFinal= $FechaF. ' ' .$HoraF;

        $ArrayReporte600= array();
        $ConsultaReporte600 = "SELECT * FROM DYALOGOCRM_WEB.G3630 WHERE G3630_FechaInsercion BETWEEN '". $FechaInicial ."' AND '". $FechaFinal ."'";
        if($ResultadoReporte600 = $mysqli->query($ConsultaReporte600)) {
            $CantidadResultados = $ResultadoReporte600->num_rows;
            if($CantidadResultados > 0) {
                while ($FilaResultado = $ResultadoReporte600->fetch_assoc()) {   
                    $IdPromesa= $FilaResultado['G3630_ConsInte__b'];
                    $NumeroCuenta= $FilaResultado['G3630_C72262'];
                    //$IdGestor= $FilaResultado[''];
                    //$CodigoAccion= $FilaResultado[''];
                    //$CodigoResultado= $FilaResultado[''];
                    $FechaElaboracion= $FilaResultado['G3630_FechaInsercion'];
                    //$NumeroPromesas= $FilaResultado[''];
                    //$ConsecutivoPromesas= $FilaResultado[''];
                    $FechaVencimiento= $FilaResultado['G3630_C72266'];
                    $Monto= $FilaResultado['G3630_C72267'];
                    
                    array_push($ArrayReporte600, array("0" => $IdPromesa, "1" => $NumeroCuenta, "2" => $FechaElaboracion, "3" => $FechaVencimiento, "4" => $Monto));
                    
                    //array_push($ArrayReporte600, array("0" => $IdPromesa, "1" => $NumeroCuenta, "2" => $NumeroCuenta, "3" => $IdGestor, "4" => $Host, "5" => $CodigoArea, "6" => $NumeroTelefono, "7" => $CodigoAccion, "8" => $CodigoResultado, "9" => $Comentario));
                
                }
                
                //Todo Ok
                $php_response= array("msg" => "Ok", "Resultado" => $ArrayReporte600);
                echo json_encode($php_response);
                mysqli_close($mysqli);
                exit;

            }else {
                //Sin Resultados
                $php_response = array("msg" => "Nada");
                mysqli_close($mysqli);
                echo json_encode($php_response);
                exit;
            }
        }else {
            mysqli_close($mysqli);
            $Falla = mysqli_error($mysqli);
            $php_response = array("msg" => "Error", "Falla" => $Falla);
            echo json_encode($php_response);
            exit;
        }
    }

  
?>
