<?php
    ini_set('display_errors', 'On');
    ini_set('display_errors', 1);
    include(__DIR__."/../../conexion.php");
    include(__DIR__."/../../funciones.php");
    date_default_timezone_set('America/Bogota');
    // JDBD - se envia calificacion de la gestion al agente que la creo.
    
    if (isset($_GET["EnviarCalificacion"])) {
        $SC = $_POST["IdGuion"];
        $G = $_POST["IdGestion"];

        $P = "SELECT GUION__ConsInte__PREGUN_Pri_b AS P, GUION__ConsInte__PREGUN_Sec_b AS S FROM ".$BaseDatos_systema.". GUION_ WHERE GUION__ConsInte__b = 3458;";
        $P = $mysqli->query($P);
        $P = $P->fetch_array();

        $upGCE = "UPDATE ".$BaseDatos.".G3458 SET G3458_C113799 = -201 WHERE G3458_ConsInte__b = ".$_POST["IdGestion"];
        $upGCE = $mysqli->query($upGCE);

        $gestion = "SELECT * FROM ".$BaseDatos.".G3458 WHERE G3458_ConsInte__b = ".$_POST["IdGestion"];
        $gestion = $mysqli->query($gestion);
        $gestion = $gestion->fetch_array();


        if (is_null($gestion["G3458_C113798"]) || $gestion["G3458_C113798"] == "") {
            $valCal = "NULL";
        }else{
            $valCal = $gestion["G3458_C113798"];
        }

        if (is_null($gestion["G3458_C113800"]) || $gestion["G3458_C113800"] == "") {
            $valCom = "NULL";
        }else{
            $valCom = $gestion["G3458_C113800"];
        }

        $histCalidad = "INSERT INTO ".$BaseDatos_systema.".CALHIS (CALHIS_ConsInte__GUION__b,CALHIS_IdGestion_b,CALHIS_FechaGestion_b,CALHIS_ConsInte__USUARI_Age_b,CALHIS_DatoPrincipalScript_b,CALHIS_DatoSecundarioScript_b,CALHIS_FechaEvaluacion_b,CALHIS_ConsInte__USUARI_Cal_b,CALHIS_Calificacion_b,CALHIS_ComentCalidad_b) VALUES(".$_POST["IdGuion"].",".$_POST["IdGestion"].",'".$gestion["G3458_FechaInsercion"]."',".$gestion["G3458_Usuario"].",'".$gestion["G3458_C".$P["P"]]."','".$gestion["G3458_C".$P["S"]]."','".date('Y-m-d H:i:s')."',".$_POST["IdCal"].",".$valCal.",'".$valCom."')";
        if ($mysqli->query($histCalidad)) {
            $H = $mysqli->insert_id;
            $URL = "interno.dyalogo.cloud/QA/index.php?SC=".$SC."&G=".$G."&H=".$H;
            $IdProyecto= $_POST["IdProyecto"];
        }else{
            $URL="";
            $IdProyecto= $_POST["IdProyecto"];
        }
        
        //GuardarURL
        $URL= strval("$URL");
        $GuardarURL= "UPDATE DYALOGOCRM_SISTEMA.CALHIS SET CALHIS_LinkCalificacion= '". $URL ."', CALHIS_ConsInte__PROYEC_b= '".$IdProyecto."' WHERE CALHIS_ConsInte__GUION__b= '".$SC."' AND CALHIS_ConsInte__b= '".$H."';";
        if ($ResultadoSQL = $mysqli->query($GuardarURL)) {
            $php_response= array("msg" => "URL Guardada");
            //print_r($php_response);
        }

        $HTML = "<!DOCTYPE html><html><head><title>HTML</title></head><body><div><h3>Añadir un comentario : </h3><a href = '".$URL."'>".$URL."</a></div><div>";

        //JDBD - obtenemos las secciones del formulario.
        $Secciones = "SELECT SECCIO_ConsInte__b AS id, SECCIO_TipoSecc__b AS tipo, SECCIO_Nombre____b AS nom 
                      FROM ".$BaseDatos_systema.".SECCIO WHERE SECCIO_ConsInte__GUION__b = 3458 
                      AND SECCIO_TipoSecc__b <> 4 ORDER BY FIELD(SECCIO_TipoSecc__b,2) DESC, SECCIO_ConsInte__b DESC;";

        $email = "SELECT USUARI_Correo___b AS email FROM ".$BaseDatos_systema.".USUARI WHERE USUARI_ConsInte__b = ".$gestion["G3458_Usuario"];
        $email = $mysqli->query($email);
        $email = $email->fetch_array();

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
                    "strSubject_t"              =>  "Calificacion Llamada #". $gestion["G3458_ConsInte__b"],
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
            $Con = "SELECT G3458_LinkContenido as url FROM ".$BaseDatos.".G3458 WHERE G3458_ConsInte__b = ".$_POST["idReg"];
            $result = $mysqli->query($Con);

            $url = $result->fetch_array();

            echo $url["url"];
        }                   

    if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
      //Datos del formulario
      if(isset($_POST['CallDatos'])){
          
            $Lsql = 'SELECT G3458_ConsInte__b, G3458_FechaInsercion , G3458_Usuario ,  G3458_CodigoMiembro  , G3458_PoblacionOrigen , G3458_EstadoDiligenciamiento ,  G3458_IdLlamada , G3458_C70272 as principal ,G3458_C70272,G3458_C70274,G3458_C70275,G3458_C70276,G3458_C70277,G3458_C70278,G3458_C70279,G3458_C70327,G3458_C70454,G3458_C70455,G3458_C112341,G3458_C112356,G3458_C112375,G3458_C70261,G3458_C70262,G3458_C70263,G3458_C70264,G3458_C70265,G3458_C70266,G3458_C70267,G3458_C70268,G3458_C70269,G3458_C70270,G3458_C70405,G3458_C113024,G3458_C113764,G3458_C113793,G3458_C113794,G3458_C113795,G3458_C113796,G3458_C113797,G3458_C113804,G3458_C113799,G3458_C113798,G3458_C113800,G3458_C113801,G3458_C113802,G3458_C113803 FROM '.$BaseDatos.'.G3458 WHERE G3458_ConsInte__b ='.$_POST['id'];
            $result = $mysqli->query($Lsql);
            $datos = array();
            $i = 0;

            while($key = $result->fetch_object()){

                $datos[$i]['G3458_C70272'] = $key->G3458_C70272;

                $datos[$i]['G3458_C70274'] = $key->G3458_C70274;

                $datos[$i]['G3458_C70275'] = $key->G3458_C70275;

                $datos[$i]['G3458_C70276'] = $key->G3458_C70276;

                $datos[$i]['G3458_C70277'] = $key->G3458_C70277;

                $datos[$i]['G3458_C70278'] = $key->G3458_C70278;

                $datos[$i]['G3458_C70279'] = $key->G3458_C70279;

                $datos[$i]['G3458_C70327'] = $key->G3458_C70327;

                $datos[$i]['G3458_C70454'] = $key->G3458_C70454;

                $datos[$i]['G3458_C70455'] = $key->G3458_C70455;

                $datos[$i]['G3458_C112341'] = $key->G3458_C112341;

                $datos[$i]['G3458_C112356'] = $key->G3458_C112356;

                $datos[$i]['G3458_C112375'] = $key->G3458_C112375;

                $datos[$i]['G3458_C70261'] = $key->G3458_C70261;

                $datos[$i]['G3458_C70262'] = $key->G3458_C70262;

                $datos[$i]['G3458_C70263'] = explode(' ', $key->G3458_C70263)[0];
  
                $hora = '';
                if(!is_null($key->G3458_C70264)){
                    $hora = explode(' ', $key->G3458_C70264)[1];
                }

                $datos[$i]['G3458_C70264'] = $hora;

                $datos[$i]['G3458_C70265'] = $key->G3458_C70265;

                $datos[$i]['G3458_C70266'] = $key->G3458_C70266;

                $datos[$i]['G3458_C70267'] = $key->G3458_C70267;

                $datos[$i]['G3458_C70268'] = $key->G3458_C70268;

                $datos[$i]['G3458_C70269'] = $key->G3458_C70269;

                $datos[$i]['G3458_C70270'] = $key->G3458_C70270;

                $datos[$i]['G3458_C70405'] = $key->G3458_C70405;

                $datos[$i]['G3458_C113024'] = $key->G3458_C113024;

                $datos[$i]['G3458_C113764'] = $key->G3458_C113764;

                $datos[$i]['G3458_C113793'] = $key->G3458_C113793;

                $datos[$i]['G3458_C113794'] = $key->G3458_C113794;

                $datos[$i]['G3458_C113795'] = $key->G3458_C113795;

                $datos[$i]['G3458_C113796'] = $key->G3458_C113796;

                $datos[$i]['G3458_C113797'] = $key->G3458_C113797;

                $datos[$i]['G3458_C113804'] = $key->G3458_C113804;

                $datos[$i]['G3458_C113799'] = $key->G3458_C113799;

                $datos[$i]['G3458_C113798'] = $key->G3458_C113798;

                $datos[$i]['G3458_C113800'] = $key->G3458_C113800;

                $datos[$i]['G3458_C113801'] = $key->G3458_C113801;

                $datos[$i]['G3458_C113802'] = explode(' ', $key->G3458_C113802)[0];

                $datos[$i]['G3458_C113803'] = $key->G3458_C113803;
      
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
            $strRegPro_t = "SELECT PEOBUS_VeRegPro__b AS reg FROM ".$BaseDatos_systema.".PEOBUS WHERE PEOBUS_ConsInte__USUARI_b = ".$_POST["idUsuario"]." AND PEOBUS_ConsInte__GUION__b = 3458";

            $resRegPro_t = $mysqli->query($strRegPro_t);

            if ($resRegPro_t->num_rows > 0) {

                $objRegPro_t = $resRegPro_t->fetch_object();
                
                if ($objRegPro_t->reg != 0) {
                    $strRegProp_t = " AND G3458_Usuario = ".$_POST["idUsuario"]." ";
                }else{
                    $strRegProp_t = "";
                }

            }else{
                $strRegProp_t = "";
            }

            //JDBD-2020-05-03 : Consulta estandar de los registros del guion.
            $Lsql = "SELECT G3458_ConsInte__b as id,  G3458_C70272 as camp1 , G3458_C70274 as camp2 
                     FROM ".$BaseDatos.".G3458  WHERE TRUE ".$strRegProp_t;

            // Si lo que estamos consultando es de tareas de backoffice cambia la consulta
            if(isset($_POST['tareaBackoffice']) && $_POST['tareaBackoffice'] == 1 && isset($_POST['muestra']) && $_POST['muestra'] != 0){

                $Lsql = "SELECT G3458_ConsInte__b as id,  G3458_C70272 as camp1 , G3458_C70274 as camp2 FROM ".$BaseDatos.".G3458  JOIN ".$BaseDatos.".G3458_M".$_POST['muestra']." ON G3458_ConsInte__b = G3458_M".$_POST['muestra']."_CoInMiPo__b 
                    WHERE ( (G3458_M".$_POST['muestra']."_Estado____b = 0 OR G3458_M".$_POST['muestra']."_Estado____b = 1 OR G3458_M".$_POST['muestra']."_Estado____b = 3) OR (G3458_M".$_POST['muestra']."_Estado____b = 2 AND G3458_M".$_POST['muestra']."_FecHorAge_b <= NOW() ) )";

                if($_POST['tareaTipoDist'] != 1){
                    $Lsql .= " AND G3458_M".$_POST['muestra']."_ConIntUsu_b = ".$_POST["idUsuario"]." ";
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
                            $Lsql .= operadorYFiltro("G3458_C".$_POST["selCampo_".$filtro],$_POST["selOperador_".$filtro],$_POST["tipo_".$filtro],$_POST["valor_".$filtro]);
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
                    $Lsql .= " AND (G3458_C70272 LIKE '%".$B."%' OR G3458_C70274 LIKE '%".$B."%') ";
                }

            }


            $Lsql .= " ORDER BY G3458_ConsInte__b DESC".$strLimit_t; 

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
            $result = $mysqli->query("SELECT COUNT(*) AS count FROM ".$BaseDatos.".G3458");
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

            $Lsql = 'SELECT G3458_ConsInte__b, G3458_FechaInsercion , G3458_Usuario ,  G3458_CodigoMiembro  , G3458_PoblacionOrigen , G3458_EstadoDiligenciamiento ,  G3458_IdLlamada , G3458_C70272 as principal ,G3458_C70272,G3458_C70274,G3458_C70275,G3458_C70276,G3458_C70277,G3458_C70278, a.LISOPC_Nombre____b as G3458_C70279,G3458_C70327,G3458_C70454,G3458_C70455,G3458_C112341, b.LISOPC_Nombre____b as G3458_C112356, c.LISOPC_Nombre____b as G3458_C112375, d.LISOPC_Nombre____b as G3458_C70261, e.LISOPC_Nombre____b as G3458_C70262,G3458_C70263,G3458_C70264,G3458_C70265,G3458_C70266,G3458_C70267,G3458_C70268,G3458_C70269, f.LISOPC_Nombre____b as G3458_C70270, g.LISOPC_Nombre____b as G3458_C70405,G3458_C113024, h.LISOPC_Nombre____b as G3458_C113764, i.LISOPC_Nombre____b as G3458_C113793, j.LISOPC_Nombre____b as G3458_C113794, k.LISOPC_Nombre____b as G3458_C113795, l.LISOPC_Nombre____b as G3458_C113796,G3458_C113797, m.LISOPC_Nombre____b as G3458_C113804, n.LISOPC_Nombre____b as G3458_C113799,G3458_C113798,G3458_C113800,G3458_C113801,G3458_C113802,G3458_C113803 FROM '.$BaseDatos.'.G3458 LEFT JOIN '.$BaseDatos_systema.'.LISOPC as a ON a.LISOPC_ConsInte__b =  G3458_C70279 LEFT JOIN '.$BaseDatos_systema.'.LISOPC as b ON b.LISOPC_ConsInte__b =  G3458_C112356 LEFT JOIN '.$BaseDatos_systema.'.LISOPC as c ON c.LISOPC_ConsInte__b =  G3458_C112375 LEFT JOIN '.$BaseDatos_systema.'.LISOPC as d ON d.LISOPC_ConsInte__b =  G3458_C70261 LEFT JOIN '.$BaseDatos_systema.'.LISOPC as e ON e.LISOPC_ConsInte__b =  G3458_C70262 LEFT JOIN '.$BaseDatos_systema.'.LISOPC as f ON f.LISOPC_ConsInte__b =  G3458_C70270 LEFT JOIN '.$BaseDatos_systema.'.LISOPC as g ON g.LISOPC_ConsInte__b =  G3458_C70405 LEFT JOIN '.$BaseDatos_systema.'.LISOPC as h ON h.LISOPC_ConsInte__b =  G3458_C113764 LEFT JOIN '.$BaseDatos_systema.'.LISOPC as i ON i.LISOPC_ConsInte__b =  G3458_C113793 LEFT JOIN '.$BaseDatos_systema.'.LISOPC as j ON j.LISOPC_ConsInte__b =  G3458_C113794 LEFT JOIN '.$BaseDatos_systema.'.LISOPC as k ON k.LISOPC_ConsInte__b =  G3458_C113795 LEFT JOIN '.$BaseDatos_systema.'.LISOPC as l ON l.LISOPC_ConsInte__b =  G3458_C113796 LEFT JOIN '.$BaseDatos_systema.'.LISOPC as m ON m.LISOPC_ConsInte__b =  G3458_C113804 LEFT JOIN '.$BaseDatos_systema.'.LISOPC as n ON n.LISOPC_ConsInte__b =  G3458_C113799';
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
                if(!is_null($fila->G3458_C70264)){
                    $hora_a = explode(' ', $fila->G3458_C70264)[1];
                }
                $respuesta['rows'][$i]['id']=$fila->G3458_ConsInte__b;
                $respuesta['rows'][$i]['cell']=array($fila->G3458_ConsInte__b , ($fila->G3458_C70272) , ($fila->G3458_C70274) , ($fila->G3458_C70275) , ($fila->G3458_C70276) , ($fila->G3458_C70277) , ($fila->G3458_C70278) , ($fila->G3458_C70279) , ($fila->G3458_C70327) , ($fila->G3458_C70454) , ($fila->G3458_C70455) , ($fila->G3458_C112341) , ($fila->G3458_C112356) , ($fila->G3458_C112375) , ($fila->G3458_C70261) , ($fila->G3458_C70262) , explode(' ', $fila->G3458_C70263)[0] , $hora_a , ($fila->G3458_C70265) , ($fila->G3458_C70266) , ($fila->G3458_C70267) , ($fila->G3458_C70268) , ($fila->G3458_C70269) , ($fila->G3458_C70270) , ($fila->G3458_C70405) , ($fila->G3458_C113024) , ($fila->G3458_C113764) , ($fila->G3458_C113793) , ($fila->G3458_C113794) , ($fila->G3458_C113795) , ($fila->G3458_C113796) , ($fila->G3458_C113797) , ($fila->G3458_C113804) , ($fila->G3458_C113799) , ($fila->G3458_C113798) , ($fila->G3458_C113800) , ($fila->G3458_C113801) , explode(' ', $fila->G3458_C113802)[0] , ($fila->G3458_C113803) );
                $i++;
            }
            // La respuesta se regresa como json
            echo json_encode($respuesta);
        }

        if(isset($_POST['CallEliminate'])){
            if($_POST['oper'] == 'del'){
                $Lsql = "DELETE FROM ".$BaseDatos.".G3458 WHERE G3458_ConsInte__b = ".$_POST['id'];
                if ($mysqli->query($Lsql) === TRUE) {
                    echo "1";
                } else {
                    echo "Error eliminado los registros : " . $mysqli->error;
                }
            }
        }

        if(isset($_POST['callDatosNuevamente'])){

            //JDBD-2020-05-03 : Averiguamos si el usuario en session solo puede ver sus propios registros.
            $strRegPro_t = "SELECT PEOBUS_VeRegPro__b AS reg FROM ".$BaseDatos_systema.".PEOBUS WHERE PEOBUS_ConsInte__USUARI_b = ".$_POST["idUsuario"]." AND PEOBUS_ConsInte__GUION__b = 3458";

            $resRegPro_t = $mysqli->query($strRegPro_t);

            if ($resRegPro_t->num_rows > 0) {
                
                $objRegPro_t = $resRegPro_t->fetch_object();

                if ($objRegPro_t->reg != 0) {
                    $strRegProp_t = ' AND G3458_Usuario = '.$_POST["idUsuario"].' ';
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
            $Zsql = 'SELECT  G3458_ConsInte__b as id,  G3458_C70272 as camp1 , G3458_C70274 as camp2  FROM '.$BaseDatos.'.G3458 WHERE TRUE'.$strRegProp_t;
            
            // Si lo que estamos consultando es de tareas de backoffice cambia la consulta
            if(isset($_POST['tareaBackoffice']) && $_POST['tareaBackoffice'] == 1 && isset($_POST['muestra']) && $_POST['muestra'] != 0){

                $Zsql = "SELECT G3458_ConsInte__b as id,  G3458_C70272 as camp1 , G3458_C70274 as camp2  FROM ".$BaseDatos.".G3458  JOIN ".$BaseDatos.".G3458_M".$_POST['muestra']." ON G3458_ConsInte__b = G3458_M".$_POST['muestra']."_CoInMiPo__b 
                    WHERE ( (G3458_M".$_POST['muestra']."_Estado____b = 0 OR G3458_M".$_POST['muestra']."_Estado____b = 1 OR G3458_M".$_POST['muestra']."_Estado____b = 3) OR (G3458_M".$_POST['muestra']."_Estado____b = 2 AND G3458_M".$_POST['muestra']."_FecHorAge_b <= NOW() ) )";

                if($_POST['tareaTipoDist'] != 1){
                    $Zsql .= " AND G3458_M".$_POST['muestra']."_ConIntUsu_b = ".$_POST["idUsuario"]." ";
                }
            }

            //JDBD-2020-05-03 : Este es el campo de busqueda sencilla que esta al lado de la lupa.
            if ($B != "") {
                $Zsql .= ' AND (G3458_C70272 LIKE "%'.$B.'%" OR G3458_C70274 LIKE "%'.$B.'%") ';
            }

            $Zsql .= ' ORDER BY G3458_ConsInte__b DESC LIMIT '.$inicio.' , '.$fin;
            
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
            $LsqlU = "UPDATE ".$BaseDatos.".G3458 SET "; 
            $LsqlI = "INSERT INTO ".$BaseDatos.".G3458(";
            $LsqlV = " VALUES ("; 
  

            if(isset($_POST["G3458_C70272"])){
                $separador = "";
                if($validar == 1){
                    $separador = ",";
                }

                $LsqlU .= $separador."G3458_C70272 = '".$_POST["G3458_C70272"]."'";
                $LsqlI .= $separador."G3458_C70272";
                $LsqlV .= $separador."'".$_POST["G3458_C70272"]."'";
                $validar = 1;
            }
                
  

            if(isset($_POST["G3458_C70274"])){
                $separador = "";
                if($validar == 1){
                    $separador = ",";
                }

                $LsqlU .= $separador."G3458_C70274 = '".$_POST["G3458_C70274"]."'";
                $LsqlI .= $separador."G3458_C70274";
                $LsqlV .= $separador."'".$_POST["G3458_C70274"]."'";
                $validar = 1;
            }
                
  

            if(isset($_POST["G3458_C70275"])){
                $separador = "";
                if($validar == 1){
                    $separador = ",";
                }

                $LsqlU .= $separador."G3458_C70275 = '".$_POST["G3458_C70275"]."'";
                $LsqlI .= $separador."G3458_C70275";
                $LsqlV .= $separador."'".$_POST["G3458_C70275"]."'";
                $validar = 1;
            }
                
  

            if(isset($_POST["G3458_C70276"])){
                $separador = "";
                if($validar == 1){
                    $separador = ",";
                }

                $LsqlU .= $separador."G3458_C70276 = '".$_POST["G3458_C70276"]."'";
                $LsqlI .= $separador."G3458_C70276";
                $LsqlV .= $separador."'".$_POST["G3458_C70276"]."'";
                $validar = 1;
            }
                
  

            if(isset($_POST["G3458_C70277"])){
                $separador = "";
                if($validar == 1){
                    $separador = ",";
                }

                $LsqlU .= $separador."G3458_C70277 = '".$_POST["G3458_C70277"]."'";
                $LsqlI .= $separador."G3458_C70277";
                $LsqlV .= $separador."'".$_POST["G3458_C70277"]."'";
                $validar = 1;
            }
                
  

            if(isset($_POST["G3458_C70278"])){
                $separador = "";
                if($validar == 1){
                    $separador = ",";
                }

                $LsqlU .= $separador."G3458_C70278 = '".$_POST["G3458_C70278"]."'";
                $LsqlI .= $separador."G3458_C70278";
                $LsqlV .= $separador."'".$_POST["G3458_C70278"]."'";
                $validar = 1;
            }
                
  

            if(isset($_POST["G3458_C70279"])){
                $separador = "";
                if($validar == 1){
                    $separador = ",";
                }

                $LsqlU .= $separador."G3458_C70279 = '".$_POST["G3458_C70279"]."'";
                $LsqlI .= $separador."G3458_C70279";
                $LsqlV .= $separador."'".$_POST["G3458_C70279"]."'";
                $validar = 1;
            }
                
  

            if(isset($_POST["G3458_C70327"])){
                $separador = "";
                if($validar == 1){
                    $separador = ",";
                }

                $LsqlU .= $separador."G3458_C70327 = '".$_POST["G3458_C70327"]."'";
                $LsqlI .= $separador."G3458_C70327";
                $LsqlV .= $separador."'".$_POST["G3458_C70327"]."'";
                $validar = 1;
            }
                
  

            if(isset($_POST["G3458_C70454"])){
                $separador = "";
                if($validar == 1){
                    $separador = ",";
                }

                $LsqlU .= $separador."G3458_C70454 = '".$_POST["G3458_C70454"]."'";
                $LsqlI .= $separador."G3458_C70454";
                $LsqlV .= $separador."'".$_POST["G3458_C70454"]."'";
                $validar = 1;
            }
                
  

            if(isset($_POST["G3458_C70455"])){
                $separador = "";
                if($validar == 1){
                    $separador = ",";
                }

                $LsqlU .= $separador."G3458_C70455 = '".$_POST["G3458_C70455"]."'";
                $LsqlI .= $separador."G3458_C70455";
                $LsqlV .= $separador."'".$_POST["G3458_C70455"]."'";
                $validar = 1;
            }
                
  

            if(isset($_POST["G3458_C112341"])){
                $separador = "";
                if($validar == 1){
                    $separador = ",";
                }

                $LsqlU .= $separador."G3458_C112341 = '".$_POST["G3458_C112341"]."'";
                $LsqlI .= $separador."G3458_C112341";
                $LsqlV .= $separador."'".$_POST["G3458_C112341"]."'";
                $validar = 1;
            }
                
  

            if(isset($_POST["G3458_C112356"])){
                $separador = "";
                if($validar == 1){
                    $separador = ",";
                }

                $LsqlU .= $separador."G3458_C112356 = '".$_POST["G3458_C112356"]."'";
                $LsqlI .= $separador."G3458_C112356";
                $LsqlV .= $separador."'".$_POST["G3458_C112356"]."'";
                $validar = 1;
            }
                
  

            if(isset($_POST["G3458_C112375"])){
                $separador = "";
                if($validar == 1){
                    $separador = ",";
                }

                $LsqlU .= $separador."G3458_C112375 = '".$_POST["G3458_C112375"]."'";
                $LsqlI .= $separador."G3458_C112375";
                $LsqlV .= $separador."'".$_POST["G3458_C112375"]."'";
                $validar = 1;
            }
                
 
            $G3458_C70261 = NULL;
            if(isset($_POST["tipificacion"])){    
                if($_POST["tipificacion"] != ''){
                    $separador = "";
                    if($validar == 1){
                        $separador = ",";
                    }
                    $G3458_C70261 = str_replace(' ', '',$_POST["tipificacion"]);
                    $LsqlU .= $separador." G3458_C70261 = ".$G3458_C70261;
                    $LsqlI .= $separador." G3458_C70261";
                    $LsqlV .= $separador.$G3458_C70261;
                    $validar = 1;

                    
                }
            }
 
            $G3458_C70262 = NULL;
            if(isset($_POST["reintento"])){    
                if($_POST["reintento"] != ''){
                    $separador = "";
                    if($validar == 1){
                        $separador = ",";
                    }
                    $G3458_C70262 = str_replace(' ', '',$_POST["reintento"]);
                    $LsqlU .= $separador." G3458_C70262 = ".$G3458_C70262;
                    $LsqlI .= $separador." G3458_C70262";
                    $LsqlV .= $separador.$G3458_C70262;
                    $validar = 1;
                }
            }
 
            $G3458_C70263 = NULL;
            //este es de tipo date hay que preguntar si esta vacia o no
            if(isset($_POST["TxtFechaReintento"])){    
                if($_POST["TxtFechaReintento"] != ''){
                    if(validateDate(str_replace(' ', '',$_POST["TxtFechaReintento"])." 00:00:00")){
                        $separador = "";
                        if($validar == 1){
                            $separador = ",";
                        }
                        $G3458_C70263 = "'".str_replace(' ', '',$_POST["TxtFechaReintento"])." 00:00:00'";
                        $LsqlU .= $separador." G3458_C70263 = ".$G3458_C70263;
                        $LsqlI .= $separador." G3458_C70263";
                        $LsqlV .= $separador.$G3458_C70263;
                        $validar = 1;
                    }
                }else{
                    if(!isset($_GET["LlamadoExterno"])){
                        echo "Validar el campo Fecha de agenda";
                        exit();
                    }
                }
            }
 
            $G3458_C70264 = NULL;
            //este es de tipo date hay que preguntar si esta vacia o no
            if(isset($_POST["TxtHoraReintento"])){    
                if($_POST["TxtHoraReintento"] != ''){
                    if(validateDate(str_replace(' ', '',$_POST["TxtFechaReintento"])." ".str_replace(' ', '',$_POST["TxtHoraReintento"]))){
                        $separador = "";
                        if($validar == 1){
                            $separador = ",";
                        }
                        $G3458_C70264 = "'".str_replace(' ', '',$_POST["TxtFechaReintento"])." ".str_replace(' ', '',$_POST["TxtHoraReintento"])."'";
                        $LsqlU .= $separador." G3458_C70264 = ".$G3458_C70264;
                        $LsqlI .= $separador." G3458_C70264";
                        $LsqlV .= $separador.$G3458_C70264;
                        $validar = 1;
                    }else{
                        if(!isset($_GET["LlamadoExterno"])){
                            echo "Validar el campo de la hora de agenda";
                            exit();
                        }
                    }
                }
            }
 
            $G3458_C70265 = NULL;
            if(isset($_POST["textAreaComentarios"])){    
                if($_POST["textAreaComentarios"] != ''){
                    $separador = "";
                    if($validar == 1){
                        $separador = ",";
                    }
                    $G3458_C70265 = "'".$_POST["textAreaComentarios"]."'";
                    $LsqlU .= $separador." G3458_C70265 = ".$G3458_C70265;
                    $LsqlI .= $separador." G3458_C70265";
                    $LsqlV .= $separador.$G3458_C70265;
                    $validar = 1;
                }
            }
  

            if(isset($_POST["G3458_C70266"])){
                $separador = "";
                if($validar == 1){
                    $separador = ",";
                }

                $LsqlU .= $separador."G3458_C70266 = '".$_POST["G3458_C70266"]."'";
                $LsqlI .= $separador."G3458_C70266";
                $LsqlV .= $separador."'".$_POST["G3458_C70266"]."'";
                $validar = 1;
            }
                
  

            if(isset($_POST["G3458_C70267"])){
                $separador = "";
                if($validar == 1){
                    $separador = ",";
                }

                $LsqlU .= $separador."G3458_C70267 = '".$_POST["G3458_C70267"]."'";
                $LsqlI .= $separador."G3458_C70267";
                $LsqlV .= $separador."'".$_POST["G3458_C70267"]."'";
                $validar = 1;
            }
                
  

            if(isset($_POST["G3458_C70268"])){
                $separador = "";
                if($validar == 1){
                    $separador = ",";
                }

                $LsqlU .= $separador."G3458_C70268 = '".$_POST["G3458_C70268"]."'";
                $LsqlI .= $separador."G3458_C70268";
                $LsqlV .= $separador."'".$_POST["G3458_C70268"]."'";
                $validar = 1;
            }
                
  

            if(isset($_POST["G3458_C70269"])){
                $separador = "";
                if($validar == 1){
                    $separador = ",";
                }

                $LsqlU .= $separador."G3458_C70269 = '".$_POST["G3458_C70269"]."'";
                $LsqlI .= $separador."G3458_C70269";
                $LsqlV .= $separador."'".$_POST["G3458_C70269"]."'";
                $validar = 1;
            }
                
  

            if(isset($_POST["G3458_C70270"])){
                $separador = "";
                if($validar == 1){
                    $separador = ",";
                }

                $LsqlU .= $separador."G3458_C70270 = '".$_POST["G3458_C70270"]."'";
                $LsqlI .= $separador."G3458_C70270";
                $LsqlV .= $separador."'".$_POST["G3458_C70270"]."'";
                $validar = 1;
            }
                
  

            if(isset($_POST["G3458_C70405"])){
                $separador = "";
                if($validar == 1){
                    $separador = ",";
                }

                $LsqlU .= $separador."G3458_C70405 = '".$_POST["G3458_C70405"]."'";
                $LsqlI .= $separador."G3458_C70405";
                $LsqlV .= $separador."'".$_POST["G3458_C70405"]."'";
                $validar = 1;
            }
                
  

            if(isset($_POST["G3458_C113024"])){
                $separador = "";
                if($validar == 1){
                    $separador = ",";
                }

                $LsqlU .= $separador."G3458_C113024 = '".$_POST["G3458_C113024"]."'";
                $LsqlI .= $separador."G3458_C113024";
                $LsqlV .= $separador."'".$_POST["G3458_C113024"]."'";
                $validar = 1;
            }
                
  

            if(isset($_POST["G3458_C70451"])){
                $separador = "";
                if($validar == 1){
                    $separador = ",";
                }

                $LsqlU .= $separador."G3458_C70451 = '".$_POST["G3458_C70451"]."'";
                $LsqlI .= $separador."G3458_C70451";
                $LsqlV .= $separador."'".$_POST["G3458_C70451"]."'";
                $validar = 1;
            }
                
  

            if(isset($_POST["G3458_C113764"])){
                $separador = "";
                if($validar == 1){
                    $separador = ",";
                }

                $LsqlU .= $separador."G3458_C113764 = '".$_POST["G3458_C113764"]."'";
                $LsqlI .= $separador."G3458_C113764";
                $LsqlV .= $separador."'".$_POST["G3458_C113764"]."'";
                $validar = 1;
            }
                
  

            if(isset($_POST["G3458_C113793"])){
                $separador = "";
                if($validar == 1){
                    $separador = ",";
                }

                $LsqlU .= $separador."G3458_C113793 = '".$_POST["G3458_C113793"]."'";
                $LsqlI .= $separador."G3458_C113793";
                $LsqlV .= $separador."'".$_POST["G3458_C113793"]."'";
                $validar = 1;
            }
                
  

            if(isset($_POST["G3458_C113794"])){
                $separador = "";
                if($validar == 1){
                    $separador = ",";
                }

                $LsqlU .= $separador."G3458_C113794 = '".$_POST["G3458_C113794"]."'";
                $LsqlI .= $separador."G3458_C113794";
                $LsqlV .= $separador."'".$_POST["G3458_C113794"]."'";
                $validar = 1;
            }
                
  

            if(isset($_POST["G3458_C113795"])){
                $separador = "";
                if($validar == 1){
                    $separador = ",";
                }

                $LsqlU .= $separador."G3458_C113795 = '".$_POST["G3458_C113795"]."'";
                $LsqlI .= $separador."G3458_C113795";
                $LsqlV .= $separador."'".$_POST["G3458_C113795"]."'";
                $validar = 1;
            }
                
  

            if(isset($_POST["G3458_C113796"])){
                $separador = "";
                if($validar == 1){
                    $separador = ",";
                }

                $LsqlU .= $separador."G3458_C113796 = '".$_POST["G3458_C113796"]."'";
                $LsqlI .= $separador."G3458_C113796";
                $LsqlV .= $separador."'".$_POST["G3458_C113796"]."'";
                $validar = 1;
            }
                
  

            if(isset($_POST["G3458_C113797"])){
                $separador = "";
                if($validar == 1){
                    $separador = ",";
                }

                $LsqlU .= $separador."G3458_C113797 = '".$_POST["G3458_C113797"]."'";
                $LsqlI .= $separador."G3458_C113797";
                $LsqlV .= $separador."'".$_POST["G3458_C113797"]."'";
                $validar = 1;
            }
                
  

            if(isset($_POST["G3458_C113804"])){
                $separador = "";
                if($validar == 1){
                    $separador = ",";
                }

                $LsqlU .= $separador."G3458_C113804 = '".$_POST["G3458_C113804"]."'";
                $LsqlI .= $separador."G3458_C113804";
                $LsqlV .= $separador."'".$_POST["G3458_C113804"]."'";
                $validar = 1;
            }
                
  

            if(isset($_POST["G3458_C113799"])){
                $separador = "";
                if($validar == 1){
                    $separador = ",";
                }

                $LsqlU .= $separador."G3458_C113799 = '".$_POST["G3458_C113799"]."'";
                $LsqlI .= $separador."G3458_C113799";
                $LsqlV .= $separador."'".$_POST["G3458_C113799"]."'";
                $validar = 1;
            }
                
  
            $G3458_C113798 = NULL;
            //este es de tipo numero no se deja ir asi '', si est avacio lo mejor es no mandarlo
            if(isset($_POST["G3458_C113798"])){
                if($_POST["G3458_C113798"] != ''){
                    $_POST["G3458_C113798"]=str_replace($valor,"",$_POST["G3458_C113798"]);
                    $separador = "";
                    if($validar == 1){
                        $separador = ",";
                    }

                    $G3458_C113798 = $_POST["G3458_C113798"];
                    $LsqlU .= $separador." G3458_C113798 = ".$G3458_C113798."";
                    $LsqlI .= $separador." G3458_C113798";
                    $LsqlV .= $separador.$G3458_C113798;
                    $validar = 1;
                }
            }
  

            if(isset($_POST["G3458_C113800"])){
                $separador = "";
                if($validar == 1){
                    $separador = ",";
                }

                $LsqlU .= $separador."G3458_C113800 = '".$_POST["G3458_C113800"]."'";
                $LsqlI .= $separador."G3458_C113800";
                $LsqlV .= $separador."'".$_POST["G3458_C113800"]."'";
                $validar = 1;
            }
                
  

            if(isset($_POST["G3458_C113801"])){
                $separador = "";
                if($validar == 1){
                    $separador = ",";
                }

                $LsqlU .= $separador."G3458_C113801 = '".$_POST["G3458_C113801"]."'";
                $LsqlI .= $separador."G3458_C113801";
                $LsqlV .= $separador."'".$_POST["G3458_C113801"]."'";
                $validar = 1;
            }
                
 
            $G3458_C113802 = NULL;
            //este es de tipo date hay que preguntar si esta vacia o no
            if(isset($_POST["G3458_C113802"])){    
                if($_POST["G3458_C113802"] != ''){
                    if(validateDate(str_replace(' ', '',$_POST["G3458_C113802"])." 00:00:00")){
                        $separador = "";
                        if($validar == 1){
                            $separador = ",";
                        }

                        $tieneHora = explode(' ' , $_POST["G3458_C113802"]);
                        if(count($tieneHora) > 1){
                            $G3458_C113802 = "'".$_POST["G3458_C113802"]."'";
                        }else{
                            $G3458_C113802 = "'".str_replace(' ', '',$_POST["G3458_C113802"])." 00:00:00'";
                        }


                        $LsqlU .= $separador." G3458_C113802 = ".$G3458_C113802;
                        $LsqlI .= $separador." G3458_C113802";
                        $LsqlV .= $separador.$G3458_C113802;
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
  

            if(isset($_POST["G3458_C113803"])){
                $separador = "";
                if($validar == 1){
                    $separador = ",";
                }

                $LsqlU .= $separador."G3458_C113803 = '".$_POST["G3458_C113803"]."'";
                $LsqlI .= $separador."G3458_C113803";
                $LsqlV .= $separador."'".$_POST["G3458_C113803"]."'";
                $validar = 1;
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

                            $LsqlU .= $separador."G3458_Clasificacion = ".$conatcto;
                            $LsqlI .= $separador."G3458_Clasificacion";
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

            $LsqlI .= $separador."G3458_PoblacionOrigen";
            $LsqlV .= $separador."'".$pasoOrigenId."'";
            $validar = 1;
            
            if(isset($_GET['id_gestion_cbxx2'])){
                $separador = "";
                if($validar == 1){
                    $separador = ",";
                }

                $LsqlU .= $separador."G3458_IdLlamada = '".$_GET['id_gestion_cbxx2']."'";
                $LsqlI .= $separador."G3458_IdLlamada";
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
                    $valorG = "G3458_C";
                    $valorH = $valorG.$campo;
                    $LsqlU .= $separador." " .$valorH." = ".$_POST["padre"];
                    $LsqlI .= $separador." ".$valorH;
                    $LsqlV .= $separador.$_POST['padre'] ;
                    $validar = 1;
                }
            }
            if(isset($_POST['oper'])){
                if($_POST["oper"] == 'add' ){
                    $LsqlI .= ", G3458_Usuario , G3458_FechaInsercion, G3458_CodigoMiembro";
                    if(!isset($_GET['CodigoMiembro']) && !is_numeric($_GET['CodigoMiembro'])){
                        $_GET['CodigoMiembro']=-1;
                    }
                    $LsqlV .= ", ".$_GET['usuario']." , '".date('Y-m-d H:i:s')."', ".$_GET['CodigoMiembro'];
                    $Lsql = $LsqlI.")" . $LsqlV.")";
                }else if($_POST["oper"] == 'edit' ){
                    $Lsql = $LsqlU." WHERE G3458_ConsInte__b =".$_POST["id"]; 
                    //echo $Lsql;die();
                }else if($_POST["oper"] == 'del' ){
                    $Lsql = "DELETE FROM ".$BaseDatos.".G3458 WHERE G3458_ConsInte__b = ".$_POST['id'];
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

    if (isset($_GET["ConsultarHuesped"])) {
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

  

  
?>
