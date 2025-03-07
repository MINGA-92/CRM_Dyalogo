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

        $P = "SELECT GUION__ConsInte__PREGUN_Pri_b AS P, GUION__ConsInte__PREGUN_Sec_b AS S FROM ".$BaseDatos_systema.". GUION_ 
              WHERE GUION__ConsInte__b = 2992;";
        $P = $mysqli->query($P);
        $P = $P->fetch_array();

        $upGCE = "UPDATE ".$BaseDatos.".G2992
                  SET G2992_C61150 = -201
                  WHERE G2992_ConsInte__b = ".$_POST["IdGestion"];           
        $upGCE = $mysqli->query($upGCE);

        $gestion = "SELECT * FROM ".$BaseDatos.".G2992 
                    WHERE G2992_ConsInte__b = ".$_POST["IdGestion"];
        $gestion = $mysqli->query($gestion);
        $gestion = $gestion->fetch_array();

        if (is_null($gestion["G2992_C61149"]) || $gestion["G2992_C61149"] == "") {
            $valCal = "NULL";
        }else{
            $valCal = $gestion["G2992_C61149"];
        }

        if (is_null($gestion["G2992_C61151"]) || $gestion["G2992_C61151"] == "") {
            $valCom = "NULL";
        }else{
            $valCom = $gestion["G2992_C61151"];
        }

        $histCalidad = "INSERT INTO ".$BaseDatos_systema.".CALHIS 
                        (CALHIS_ConsInte__GUION__b,CALHIS_IdGestion_b,CALHIS_FechaGestion_b,CALHIS_ConsInte__USUARI_Age_b,CALHIS_DatoPrincipalScript_b,CALHIS_DatoSecundarioScript_b,CALHIS_FechaEvaluacion_b,CALHIS_ConsInte__USUARI_Cal_b,CALHIS_Calificacion_b,CALHIS_ComentCalidad_b)
                        VALUES
                        (".$_POST["IdGuion"].",".$_POST["IdGestion"].",'".$gestion["G2992_FechaInsercion"]."',".$gestion["G2992_Usuario"].",'".$gestion["G2992_C".$P["P"]]."','".$gestion["G2992_C".$P["S"]]."','".date('Y-m-d H:i:s')."',".$_POST["IdCal"].",".$valCal.",'".$valCom."')";

        if ($mysqli->query($histCalidad)) {
            $H = $mysqli->insert_id;
            $URL = "onuris.dyalogo.cloud/QA/index.php?SC=".$SC."&G=".$G."&H=".$H;
        }else{
            $URL="";
        }

        //GuardarURL
        $GuardarURL= "UPDATE DYALOGOCRM_SISTEMA.CALHIS SET CALHIS_LinkCalificacion= ". $URL ." WHERE CALHIS_ConsInte__GUION__b= ".$SC." AND CALHIS_ConsInte__b= ".$H.";";
        if ($ResultadoSQL = $mysqli->query($GuardarURL)) {
            $php_response= array("msg: " => "URL Guardada");
        }else{
            $php_response= array("msg: " => "Error Al Guardar URL");
            print_r($php_response);
        }

        $HTML = "<!DOCTYPE html><html><head><title>HTML</title></head><body><div><h3>AÃ±adir un comentario : </h3><a href = '".$URL."'>".$URL."</a></div><div>";

        //JDBD - obtenemos las secciones del formulario.
        $Secciones = "SELECT SECCIO_ConsInte__b AS id, 
                             SECCIO_TipoSecc__b AS tipo, 
                             SECCIO_Nombre____b AS nom 
                      FROM ".$BaseDatos_systema.".SECCIO 
                      WHERE SECCIO_ConsInte__GUION__b = 2992 
                      AND SECCIO_TipoSecc__b <> 4 ORDER BY FIELD(SECCIO_TipoSecc__b,2) DESC, 
                               SECCIO_ConsInte__b DESC;";

        $email = "SELECT USUARI_Correo___b AS email
                  FROM ".$BaseDatos_systema.".USUARI 
                  WHERE USUARI_ConsInte__b = ".$gestion["G2992_Usuario"];
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

                $columnas = "SELECT PREGUN_ConsInte__GUION__b AS G, 
                                    PREGUN_ConsInte__b AS C,
                                    PREGUN_Texto_____b AS nom,
                                    PREGUN_Tipo______b AS tipo
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

                $columnas = "SELECT PREGUN_ConsInte__GUION__b AS G, 
                                    PREGUN_ConsInte__b AS C,
                                    PREGUN_Texto_____b AS nom,
                                    PREGUN_Tipo______b AS tipo
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
                    "strSubject_t"              =>  "Calificacion Llamada #". $gestion["G2992_ConsInte__b"],
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
            $Con = "SELECT G2992_LinkContenido as url FROM ".$BaseDatos.".G2992 WHERE G2992_ConsInte__b = ".$_POST["idReg"];
            $result = $mysqli->query($Con);

            $url = $result->fetch_array();

            echo $url["url"];
        }                   

    if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
      //Datos del formulario
      if(isset($_POST['CallDatos'])){
          
            $Lsql = 'SELECT G2992_ConsInte__b, G2992_FechaInsercion , G2992_Usuario ,  G2992_CodigoMiembro  , G2992_PoblacionOrigen , G2992_EstadoDiligenciamiento ,  G2992_IdLlamada , G2992_C59915 as principal ,G2992_C59910,G2992_C59911,G2992_C59913,G2992_C59914,G2992_C59912,G2992_C59915,G2992_C65749,G2992_C66500,G2992_C59899,G2992_C59900,G2992_C59901,G2992_C59902,G2992_C59903,G2992_C59904,G2992_C59905,G2992_C59906,G2992_C59907,G2992_C59916,G2992_C59917,G2992_C59918,G2992_C59919,G2992_C59920,G2992_C59921,G2992_C59922,G2992_C59923,G2992_C59924,G2992_C65254,G2992_C59951,G2992_C59952,G2992_C59953,G2992_C61722,G2992_C59955,G2992_C59988,G2992_C59990,G2992_C59992,G2992_C59994,G2992_C59996,G2992_C59998,G2992_C60000,G2992_C60002,G2992_C60004,G2992_C60006,G2992_C66544,G2992_C66545,G2992_C61150,G2992_C61149,G2992_C61151,G2992_C61152,G2992_C61153,G2992_C61154,G2992_C66507,G2992_C61320,G2992_C66401,G2992_C61319,G2992_C66484,G2992_C66402,G2992_C65875,G2992_C65876,G2992_C65877,G2992_C65878,G2992_C65879,G2992_C65880,G2992_C65881,G2992_C65882,G2992_C65883,G2992_C65884,G2992_C65885,G2992_C71470 FROM '.$BaseDatos.'.G2992 WHERE G2992_ConsInte__b ='.$_POST['id'];
            $result = $mysqli->query($Lsql);
            $datos = array();
            $i = 0;

            while($key = $result->fetch_object()){

                $datos[$i]['G2992_C59910'] = $key->G2992_C59910;

                $datos[$i]['G2992_C59911'] = $key->G2992_C59911;

                $datos[$i]['G2992_C59913'] = $key->G2992_C59913;

                $datos[$i]['G2992_C59914'] = $key->G2992_C59914;

                $datos[$i]['G2992_C59912'] = $key->G2992_C59912;

                $datos[$i]['G2992_C59915'] = $key->G2992_C59915;

                $datos[$i]['G2992_C65749'] = $key->G2992_C65749;

                $datos[$i]['G2992_C66500'] = explode(' ', $key->G2992_C66500)[0];

                $datos[$i]['G2992_C59899'] = $key->G2992_C59899;

                $datos[$i]['G2992_C59900'] = $key->G2992_C59900;

                $datos[$i]['G2992_C59901'] = explode(' ', $key->G2992_C59901)[0];
  
                $hora = '';
                if(!is_null($key->G2992_C59902)){
                    $hora = explode(' ', $key->G2992_C59902)[1];
                }

                $datos[$i]['G2992_C59902'] = $hora;

                $datos[$i]['G2992_C59903'] = $key->G2992_C59903;

                $datos[$i]['G2992_C59904'] = $key->G2992_C59904;

                $datos[$i]['G2992_C59905'] = $key->G2992_C59905;

                $datos[$i]['G2992_C59906'] = $key->G2992_C59906;

                $datos[$i]['G2992_C59907'] = $key->G2992_C59907;

                $datos[$i]['G2992_C59916'] = $key->G2992_C59916;

                $datos[$i]['G2992_C59917'] = $key->G2992_C59917;

                $datos[$i]['G2992_C59918'] = $key->G2992_C59918;

                $datos[$i]['G2992_C59919'] = $key->G2992_C59919;

                $datos[$i]['G2992_C59920'] = $key->G2992_C59920;

                $datos[$i]['G2992_C59921'] = $key->G2992_C59921;

                $datos[$i]['G2992_C59922'] = $key->G2992_C59922;

                $datos[$i]['G2992_C59923'] = $key->G2992_C59923;

                $datos[$i]['G2992_C59924'] = $key->G2992_C59924;

                $datos[$i]['G2992_C65254'] = $key->G2992_C65254;

                $datos[$i]['G2992_C59951'] = $key->G2992_C59951;

                $datos[$i]['G2992_C59952'] = $key->G2992_C59952;

                $datos[$i]['G2992_C59953'] = $key->G2992_C59953;

                $datos[$i]['G2992_C61722'] = $key->G2992_C61722;

                $datos[$i]['G2992_C59955'] = $key->G2992_C59955;

                $datos[$i]['G2992_C59988'] = $key->G2992_C59988;

                $datos[$i]['G2992_C59990'] = $key->G2992_C59990;

                $datos[$i]['G2992_C59992'] = $key->G2992_C59992;

                $datos[$i]['G2992_C59994'] = $key->G2992_C59994;

                $datos[$i]['G2992_C59996'] = $key->G2992_C59996;

                $datos[$i]['G2992_C59998'] = $key->G2992_C59998;

                $datos[$i]['G2992_C60000'] = $key->G2992_C60000;

                $datos[$i]['G2992_C60002'] = $key->G2992_C60002;

                $datos[$i]['G2992_C60004'] = $key->G2992_C60004;

                $datos[$i]['G2992_C60006'] = $key->G2992_C60006;

                $datos[$i]['G2992_C66544'] = $key->G2992_C66544;

                $datos[$i]['G2992_C66545'] = $key->G2992_C66545;

                $datos[$i]['G2992_C61150'] = $key->G2992_C61150;

                $datos[$i]['G2992_C61149'] = $key->G2992_C61149;

                $datos[$i]['G2992_C61151'] = $key->G2992_C61151;

                $datos[$i]['G2992_C61152'] = $key->G2992_C61152;

                $datos[$i]['G2992_C61153'] = explode(' ', $key->G2992_C61153)[0];

                $datos[$i]['G2992_C61154'] = $key->G2992_C61154;

                $datos[$i]['G2992_C66507'] = $key->G2992_C66507;

                $datos[$i]['G2992_C61320'] = $key->G2992_C61320;

                $datos[$i]['G2992_C66401'] = explode(' ', $key->G2992_C66401)[0];

                $datos[$i]['G2992_C61319'] = $key->G2992_C61319;

                $datos[$i]['G2992_C66484'] = $key->G2992_C66484;
  
                $hora = '';
                if(!is_null($key->G2992_C66402)){
                    $hora = explode(' ', $key->G2992_C66402)[1];
                }

                $datos[$i]['G2992_C66402'] = $hora;

                $datos[$i]['G2992_C65875'] = $key->G2992_C65875;

                $datos[$i]['G2992_C65876'] = $key->G2992_C65876;

                $datos[$i]['G2992_C65877'] = $key->G2992_C65877;

                $datos[$i]['G2992_C65878'] = $key->G2992_C65878;

                $datos[$i]['G2992_C65879'] = $key->G2992_C65879;

                $datos[$i]['G2992_C65880'] = $key->G2992_C65880;

                $datos[$i]['G2992_C65881'] = $key->G2992_C65881;

                $datos[$i]['G2992_C65882'] = $key->G2992_C65882;

                $datos[$i]['G2992_C65883'] = $key->G2992_C65883;

                $datos[$i]['G2992_C65884'] = $key->G2992_C65884;

                $datos[$i]['G2992_C65885'] = $key->G2992_C65885;

                $datos[$i]['G2992_C71470'] = $key->G2992_C71470;
      
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
            $strRegPro_t = "SELECT PEOBUS_VeRegPro__b AS reg FROM ".$BaseDatos_systema.".PEOBUS WHERE PEOBUS_ConsInte__USUARI_b = ".$_POST["idUsuario"]." AND PEOBUS_ConsInte__GUION__b = 2992";

            $resRegPro_t = $mysqli->query($strRegPro_t);

            if ($resRegPro_t->num_rows > 0) {

                $objRegPro_t = $resRegPro_t->fetch_object();
                
                if ($objRegPro_t->reg != 0) {
                    $strRegProp_t = " AND G2992_Usuario = ".$_POST["idUsuario"]." ";
                }else{
                    $strRegProp_t = "";
                }

            }else{
                $strRegProp_t = "";
            }

            //JDBD-2020-05-03 : Consulta estandar de los registros del guion.
            $Lsql = "SELECT G2992_ConsInte__b as id,  G2992_C59910 as camp2 , G2992_C59915 as camp1 
                     FROM ".$BaseDatos.".G2992  WHERE TRUE ".$strRegProp_t;

            // Si lo que estamos consultando es de tareas de backoffice cambia la consulta
            if(isset($_POST['tareaBackoffice']) && $_POST['tareaBackoffice'] == 1 && isset($_POST['muestra']) && $_POST['muestra'] != 0){

                $Lsql = "SELECT G2992_ConsInte__b as id,  G2992_C59910 as camp2 , G2992_C59915 as camp1 FROM ".$BaseDatos.".G2992  JOIN ".$BaseDatos.".G2992_M".$_POST['muestra']." ON G2992_ConsInte__b = G2992_M".$_POST['muestra']."_CoInMiPo__b 
                    WHERE ( (G2992_M".$_POST['muestra']."_Estado____b = 0 OR G2992_M".$_POST['muestra']."_Estado____b = 1 OR G2992_M".$_POST['muestra']."_Estado____b = 3) OR (G2992_M".$_POST['muestra']."_Estado____b = 2 AND G2992_M".$_POST['muestra']."_FecHorAge_b <= NOW() ) )";

                if($_POST['tareaTipoDist'] != 1){
                    $Lsql .= " AND G2992_M".$_POST['muestra']."_ConIntUsu_b = ".$_POST["idUsuario"]." ";
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
                            $Lsql .= operadorYFiltro("G2992_C".$_POST["selCampo_".$filtro],$_POST["selOperador_".$filtro],$_POST["tipo_".$filtro],$_POST["valor_".$filtro]);
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
                    $Lsql .= " AND (G2992_C59910 LIKE '%".$B."%' OR G2992_C59915 LIKE '%".$B."%') ";
                }

            }


            $Lsql .= " ORDER BY G2992_ConsInte__b DESC".$strLimit_t; 

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
            $result = $mysqli->query("SELECT COUNT(*) AS count FROM ".$BaseDatos.".G2992");
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

            $Lsql = 'SELECT G2992_ConsInte__b, G2992_FechaInsercion , G2992_Usuario ,  G2992_CodigoMiembro  , G2992_PoblacionOrigen , G2992_EstadoDiligenciamiento ,  G2992_IdLlamada , G2992_C59915 as principal ,G2992_C59910,G2992_C59911,G2992_C59913,G2992_C59914,G2992_C59912,G2992_C59915,G2992_C65749,G2992_C66500, a.LISOPC_Nombre____b as G2992_C59899, b.LISOPC_Nombre____b as G2992_C59900,G2992_C59901,G2992_C59902,G2992_C59903,G2992_C59904,G2992_C59905,G2992_C59906,G2992_C59907,G2992_C59916,G2992_C59917,G2992_C59918,G2992_C59919,G2992_C59920,G2992_C59921,G2992_C59922,G2992_C59923,G2992_C59924,G2992_C65254, c.LISOPC_Nombre____b as G2992_C59951, d.LISOPC_Nombre____b as G2992_C59952, e.LISOPC_Nombre____b as G2992_C59953,G2992_C61722, f.LISOPC_Nombre____b as G2992_C59955, g.LISOPC_Nombre____b as G2992_C59988, h.LISOPC_Nombre____b as G2992_C59990, i.LISOPC_Nombre____b as G2992_C59992, j.LISOPC_Nombre____b as G2992_C59994, k.LISOPC_Nombre____b as G2992_C59996, l.LISOPC_Nombre____b as G2992_C59998, m.LISOPC_Nombre____b as G2992_C60000, n.LISOPC_Nombre____b as G2992_C60002, o.LISOPC_Nombre____b as G2992_C60004, p.LISOPC_Nombre____b as G2992_C60006,G2992_C66544,G2992_C66545, q.LISOPC_Nombre____b as G2992_C61150,G2992_C61149,G2992_C61151,G2992_C61152,G2992_C61153,G2992_C61154, r.LISOPC_Nombre____b as G2992_C66507, s.LISOPC_Nombre____b as G2992_C61320,G2992_C66401,G2992_C61319,G2992_C66484,G2992_C66402,G2992_C65875,G2992_C65876,G2992_C65877,G2992_C65878,G2992_C65879,G2992_C65880,G2992_C65881,G2992_C65882,G2992_C65883,G2992_C65884,G2992_C65885,G2992_C71470 FROM '.$BaseDatos.'.G2992 LEFT JOIN '.$BaseDatos_systema.'.LISOPC as a ON a.LISOPC_ConsInte__b =  G2992_C59899 LEFT JOIN '.$BaseDatos_systema.'.LISOPC as b ON b.LISOPC_ConsInte__b =  G2992_C59900 LEFT JOIN '.$BaseDatos_systema.'.LISOPC as c ON c.LISOPC_ConsInte__b =  G2992_C59951 LEFT JOIN '.$BaseDatos_systema.'.LISOPC as d ON d.LISOPC_ConsInte__b =  G2992_C59952 LEFT JOIN '.$BaseDatos_systema.'.LISOPC as e ON e.LISOPC_ConsInte__b =  G2992_C59953 LEFT JOIN '.$BaseDatos_systema.'.LISOPC as f ON f.LISOPC_ConsInte__b =  G2992_C59955 LEFT JOIN '.$BaseDatos_systema.'.LISOPC as g ON g.LISOPC_ConsInte__b =  G2992_C59988 LEFT JOIN '.$BaseDatos_systema.'.LISOPC as h ON h.LISOPC_ConsInte__b =  G2992_C59990 LEFT JOIN '.$BaseDatos_systema.'.LISOPC as i ON i.LISOPC_ConsInte__b =  G2992_C59992 LEFT JOIN '.$BaseDatos_systema.'.LISOPC as j ON j.LISOPC_ConsInte__b =  G2992_C59994 LEFT JOIN '.$BaseDatos_systema.'.LISOPC as k ON k.LISOPC_ConsInte__b =  G2992_C59996 LEFT JOIN '.$BaseDatos_systema.'.LISOPC as l ON l.LISOPC_ConsInte__b =  G2992_C59998 LEFT JOIN '.$BaseDatos_systema.'.LISOPC as m ON m.LISOPC_ConsInte__b =  G2992_C60000 LEFT JOIN '.$BaseDatos_systema.'.LISOPC as n ON n.LISOPC_ConsInte__b =  G2992_C60002 LEFT JOIN '.$BaseDatos_systema.'.LISOPC as o ON o.LISOPC_ConsInte__b =  G2992_C60004 LEFT JOIN '.$BaseDatos_systema.'.LISOPC as p ON p.LISOPC_ConsInte__b =  G2992_C60006 LEFT JOIN '.$BaseDatos_systema.'.LISOPC as q ON q.LISOPC_ConsInte__b =  G2992_C61150 LEFT JOIN '.$BaseDatos_systema.'.LISOPC as r ON r.LISOPC_ConsInte__b =  G2992_C66507 LEFT JOIN '.$BaseDatos_systema.'.LISOPC as s ON s.LISOPC_ConsInte__b =  G2992_C61320';
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
                if(!is_null($fila->G2992_C59902)){
                    $hora_a = explode(' ', $fila->G2992_C59902)[1];
                }

                $hora_b = '';
                //esto es para todo los tipo fecha, para que no muestre la parte de la hora
                if(!is_null($fila->G2992_C66402)){
                    $hora_b = explode(' ', $fila->G2992_C66402)[1];
                }
                $respuesta['rows'][$i]['id']=$fila->G2992_ConsInte__b;
                $respuesta['rows'][$i]['cell']=array($fila->G2992_ConsInte__b , ($fila->G2992_C59910) , ($fila->G2992_C59911) , ($fila->G2992_C59913) , ($fila->G2992_C59914) , ($fila->G2992_C59912) , ($fila->G2992_C59915) , ($fila->G2992_C65749) , explode(' ', $fila->G2992_C66500)[0] , ($fila->G2992_C59899) , ($fila->G2992_C59900) , explode(' ', $fila->G2992_C59901)[0] , $hora_a , ($fila->G2992_C59903) , ($fila->G2992_C59904) , ($fila->G2992_C59905) , ($fila->G2992_C59906) , ($fila->G2992_C59907) , ($fila->G2992_C59916) , ($fila->G2992_C59917) , ($fila->G2992_C59918) , ($fila->G2992_C59919) , ($fila->G2992_C59920) , ($fila->G2992_C59921) , ($fila->G2992_C59922) , ($fila->G2992_C59923) , ($fila->G2992_C59924) , ($fila->G2992_C65254) , ($fila->G2992_C59951) , ($fila->G2992_C59952) , ($fila->G2992_C59953) , ($fila->G2992_C61722) , ($fila->G2992_C59955) , ($fila->G2992_C59988) , ($fila->G2992_C59990) , ($fila->G2992_C59992) , ($fila->G2992_C59994) , ($fila->G2992_C59996) , ($fila->G2992_C59998) , ($fila->G2992_C60000) , ($fila->G2992_C60002) , ($fila->G2992_C60004) , ($fila->G2992_C60006) , ($fila->G2992_C66544) , ($fila->G2992_C66545) , ($fila->G2992_C61150) , ($fila->G2992_C61149) , ($fila->G2992_C61151) , ($fila->G2992_C61152) , explode(' ', $fila->G2992_C61153)[0] , ($fila->G2992_C61154) , ($fila->G2992_C66507) , ($fila->G2992_C61320) , explode(' ', $fila->G2992_C66401)[0] , ($fila->G2992_C61319) , ($fila->G2992_C66484) , $hora_b , ($fila->G2992_C65875) , ($fila->G2992_C65876) , ($fila->G2992_C65877) , ($fila->G2992_C65878) , ($fila->G2992_C65879) , ($fila->G2992_C65880) , ($fila->G2992_C65881) , ($fila->G2992_C65882) , ($fila->G2992_C65883) , ($fila->G2992_C65884) , ($fila->G2992_C65885) , ($fila->G2992_C71470) );
                $i++;
            }
            // La respuesta se regresa como json
            echo json_encode($respuesta);
        }

        if(isset($_POST['CallEliminate'])){
            if($_POST['oper'] == 'del'){
                $Lsql = "DELETE FROM ".$BaseDatos.".G2992 WHERE G2992_ConsInte__b = ".$_POST['id'];
                if ($mysqli->query($Lsql) === TRUE) {
                    echo "1";
                } else {
                    echo "Error eliminado los registros : " . $mysqli->error;
                }
            }
        }

        if(isset($_POST['callDatosNuevamente'])){

            //JDBD-2020-05-03 : Averiguamos si el usuario en session solo puede ver sus propios registros.
            $strRegPro_t = "SELECT PEOBUS_VeRegPro__b AS reg FROM ".$BaseDatos_systema.".PEOBUS WHERE PEOBUS_ConsInte__USUARI_b = ".$_POST["idUsuario"]." AND PEOBUS_ConsInte__GUION__b = 2992";

            $resRegPro_t = $mysqli->query($strRegPro_t);

            if ($resRegPro_t->num_rows > 0) {
                
                $objRegPro_t = $resRegPro_t->fetch_object();

                if ($objRegPro_t->reg != 0) {
                    $strRegProp_t = ' AND G2992_Usuario = '.$_POST["idUsuario"].' ';
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
            $Zsql = 'SELECT  G2992_ConsInte__b as id,  G2992_C59910 as camp2 , G2992_C59915 as camp1  FROM '.$BaseDatos.'.G2992 WHERE TRUE'.$strRegProp_t;
            
            // Si lo que estamos consultando es de tareas de backoffice cambia la consulta
            if(isset($_POST['tareaBackoffice']) && $_POST['tareaBackoffice'] == 1 && isset($_POST['muestra']) && $_POST['muestra'] != 0){

                $Zsql = "SELECT G2992_ConsInte__b as id,  G2992_C59910 as camp2 , G2992_C59915 as camp1  FROM ".$BaseDatos.".G2992  JOIN ".$BaseDatos.".G2992_M".$_POST['muestra']." ON G2992_ConsInte__b = G2992_M".$_POST['muestra']."_CoInMiPo__b 
                    WHERE ( (G2992_M".$_POST['muestra']."_Estado____b = 0 OR G2992_M".$_POST['muestra']."_Estado____b = 1 OR G2992_M".$_POST['muestra']."_Estado____b = 3) OR (G2992_M".$_POST['muestra']."_Estado____b = 2 AND G2992_M".$_POST['muestra']."_FecHorAge_b <= NOW() ) )";

                if($_POST['tareaTipoDist'] != 1){
                    $Zsql .= " AND G2992_M".$_POST['muestra']."_ConIntUsu_b = ".$_POST["idUsuario"]." ";
                }
            }

            //JDBD-2020-05-03 : Este es el campo de busqueda sencilla que esta al lado de la lupa.
            if ($B != "") {
                $Zsql .= ' AND (G2992_C59910 LIKE "%'.$B.'%" OR G2992_C59915 LIKE "%'.$B.'%") ';
            }

            $Zsql .= ' ORDER BY G2992_ConsInte__b DESC LIMIT '.$inicio.' , '.$fin;
            
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
            $LsqlU = "UPDATE ".$BaseDatos.".G2992 SET "; 
            $LsqlI = "INSERT INTO ".$BaseDatos.".G2992(";
            $LsqlV = " VALUES ("; 
  

            if(isset($_POST["G2992_C59910"])){
                $separador = "";
                if($validar == 1){
                    $separador = ",";
                }

                $LsqlU .= $separador."G2992_C59910 = '".$_POST["G2992_C59910"]."'";
                $LsqlI .= $separador."G2992_C59910";
                $LsqlV .= $separador."'".$_POST["G2992_C59910"]."'";
                $validar = 1;
            }
                
  

            if(isset($_POST["G2992_C59911"])){
                $separador = "";
                if($validar == 1){
                    $separador = ",";
                }

                $LsqlU .= $separador."G2992_C59911 = '".$_POST["G2992_C59911"]."'";
                $LsqlI .= $separador."G2992_C59911";
                $LsqlV .= $separador."'".$_POST["G2992_C59911"]."'";
                $validar = 1;
            }
                
  

            if(isset($_POST["G2992_C59913"])){
                $separador = "";
                if($validar == 1){
                    $separador = ",";
                }

                $LsqlU .= $separador."G2992_C59913 = '".$_POST["G2992_C59913"]."'";
                $LsqlI .= $separador."G2992_C59913";
                $LsqlV .= $separador."'".$_POST["G2992_C59913"]."'";
                $validar = 1;
            }
                
  

            if(isset($_POST["G2992_C59914"])){
                $separador = "";
                if($validar == 1){
                    $separador = ",";
                }

                $LsqlU .= $separador."G2992_C59914 = '".$_POST["G2992_C59914"]."'";
                $LsqlI .= $separador."G2992_C59914";
                $LsqlV .= $separador."'".$_POST["G2992_C59914"]."'";
                $validar = 1;
            }
                
  

            if(isset($_POST["G2992_C59912"])){
                $separador = "";
                if($validar == 1){
                    $separador = ",";
                }

                $LsqlU .= $separador."G2992_C59912 = '".$_POST["G2992_C59912"]."'";
                $LsqlI .= $separador."G2992_C59912";
                $LsqlV .= $separador."'".$_POST["G2992_C59912"]."'";
                $validar = 1;
            }
                
  

            if(isset($_POST["G2992_C59915"])){
                $separador = "";
                if($validar == 1){
                    $separador = ",";
                }

                $LsqlU .= $separador."G2992_C59915 = '".$_POST["G2992_C59915"]."'";
                $LsqlI .= $separador."G2992_C59915";
                $LsqlV .= $separador."'".$_POST["G2992_C59915"]."'";
                $validar = 1;
            }
                
  

            if(isset($_POST["G2992_C65749"])){
                $separador = "";
                if($validar == 1){
                    $separador = ",";
                }

                $LsqlU .= $separador."G2992_C65749 = '".$_POST["G2992_C65749"]."'";
                $LsqlI .= $separador."G2992_C65749";
                $LsqlV .= $separador."'".$_POST["G2992_C65749"]."'";
                $validar = 1;
            }
                
 
            $G2992_C66500 = NULL;
            //este es de tipo date hay que preguntar si esta vacia o no
            if(isset($_POST["G2992_C66500"])){    
                if($_POST["G2992_C66500"] != ''){
                    if(validateDate(str_replace(' ', '',$_POST["G2992_C66500"])." 00:00:00")){
                        $separador = "";
                        if($validar == 1){
                            $separador = ",";
                        }

                        $tieneHora = explode(' ' , $_POST["G2992_C66500"]);
                        if(count($tieneHora) > 1){
                            $G2992_C66500 = "'".$_POST["G2992_C66500"]."'";
                        }else{
                            $G2992_C66500 = "'".str_replace(' ', '',$_POST["G2992_C66500"])." 00:00:00'";
                        }


                        $LsqlU .= $separador." G2992_C66500 = ".$G2992_C66500;
                        $LsqlI .= $separador." G2992_C66500";
                        $LsqlV .= $separador.$G2992_C66500;
                        $validar = 1;
                    }else{
                        if(!isset($_GET["LlamadoExterno"])){
                            $data=json_encode(array("estado"=>"Error","mensaje"=>"Validar el campo FECHA CREACION GESTION"));
                            echo $data;
                            exit();
                        }
                    }
                }
            }
 
            $G2992_C59899 = NULL;
            if(isset($_POST["tipificacion"])){    
                if($_POST["tipificacion"] != ''){
                    $separador = "";
                    if($validar == 1){
                        $separador = ",";
                    }
                    $G2992_C59899 = str_replace(' ', '',$_POST["tipificacion"]);
                    $LsqlU .= $separador." G2992_C59899 = ".$G2992_C59899;
                    $LsqlI .= $separador." G2992_C59899";
                    $LsqlV .= $separador.$G2992_C59899;
                    $validar = 1;

                    
                }
            }
 
            $G2992_C59900 = NULL;
            if(isset($_POST["reintento"])){    
                if($_POST["reintento"] != ''){
                    $separador = "";
                    if($validar == 1){
                        $separador = ",";
                    }
                    $G2992_C59900 = str_replace(' ', '',$_POST["reintento"]);
                    $LsqlU .= $separador." G2992_C59900 = ".$G2992_C59900;
                    $LsqlI .= $separador." G2992_C59900";
                    $LsqlV .= $separador.$G2992_C59900;
                    $validar = 1;
                }
            }
 
            $G2992_C59901 = NULL;
            //este es de tipo date hay que preguntar si esta vacia o no
            if(isset($_POST["TxtFechaReintento"])){    
                if($_POST["TxtFechaReintento"] != ''){
                    if(validateDate(str_replace(' ', '',$_POST["TxtFechaReintento"])." 00:00:00")){
                        $separador = "";
                        if($validar == 1){
                            $separador = ",";
                        }
                        $G2992_C59901 = "'".str_replace(' ', '',$_POST["TxtFechaReintento"])." 00:00:00'";
                        $LsqlU .= $separador." G2992_C59901 = ".$G2992_C59901;
                        $LsqlI .= $separador." G2992_C59901";
                        $LsqlV .= $separador.$G2992_C59901;
                        $validar = 1;
                    }
                }else{
                    if(!isset($_GET["LlamadoExterno"])){
                        echo "Validar el campo Fecha de agenda";
                        exit();
                    }
                }
            }
 
            $G2992_C59902 = NULL;
            //este es de tipo date hay que preguntar si esta vacia o no
            if(isset($_POST["TxtHoraReintento"])){    
                if($_POST["TxtHoraReintento"] != ''){
                    if(validateDate(str_replace(' ', '',$_POST["TxtFechaReintento"])." ".str_replace(' ', '',$_POST["TxtHoraReintento"]))){
                        $separador = "";
                        if($validar == 1){
                            $separador = ",";
                        }
                        $G2992_C59902 = "'".str_replace(' ', '',$_POST["TxtFechaReintento"])." ".str_replace(' ', '',$_POST["TxtHoraReintento"])."'";
                        $LsqlU .= $separador." G2992_C59902 = ".$G2992_C59902;
                        $LsqlI .= $separador." G2992_C59902";
                        $LsqlV .= $separador.$G2992_C59902;
                        $validar = 1;
                    }else{
                        if(!isset($_GET["LlamadoExterno"])){
                            echo "Validar el campo de la hora de agenda";
                            exit();
                        }
                    }
                }
            }
 
            $G2992_C59903 = NULL;
            if(isset($_POST["textAreaComentarios"])){    
                if($_POST["textAreaComentarios"] != ''){
                    $separador = "";
                    if($validar == 1){
                        $separador = ",";
                    }
                    $G2992_C59903 = "'".$_POST["textAreaComentarios"]."'";
                    $LsqlU .= $separador." G2992_C59903 = ".$G2992_C59903;
                    $LsqlI .= $separador." G2992_C59903";
                    $LsqlV .= $separador.$G2992_C59903;
                    $validar = 1;
                }
            }
  

            if(isset($_POST["G2992_C59904"])){
                $separador = "";
                if($validar == 1){
                    $separador = ",";
                }

                $LsqlU .= $separador."G2992_C59904 = '".$_POST["G2992_C59904"]."'";
                $LsqlI .= $separador."G2992_C59904";
                $LsqlV .= $separador."'".$_POST["G2992_C59904"]."'";
                $validar = 1;
            }
                
  

            if(isset($_POST["G2992_C59905"])){
                $separador = "";
                if($validar == 1){
                    $separador = ",";
                }

                $LsqlU .= $separador."G2992_C59905 = '".$_POST["G2992_C59905"]."'";
                $LsqlI .= $separador."G2992_C59905";
                $LsqlV .= $separador."'".$_POST["G2992_C59905"]."'";
                $validar = 1;
            }
                
  

            if(isset($_POST["G2992_C59906"])){
                $separador = "";
                if($validar == 1){
                    $separador = ",";
                }

                $LsqlU .= $separador."G2992_C59906 = '".$_POST["G2992_C59906"]."'";
                $LsqlI .= $separador."G2992_C59906";
                $LsqlV .= $separador."'".$_POST["G2992_C59906"]."'";
                $validar = 1;
            }
                
  

            if(isset($_POST["G2992_C59907"])){
                $separador = "";
                if($validar == 1){
                    $separador = ",";
                }

                $LsqlU .= $separador."G2992_C59907 = '".$_POST["G2992_C59907"]."'";
                $LsqlI .= $separador."G2992_C59907";
                $LsqlV .= $separador."'".$_POST["G2992_C59907"]."'";
                $validar = 1;
            }
                
  

            if(isset($_POST["G2992_C59916"])){
                $separador = "";
                if($validar == 1){
                    $separador = ",";
                }

                $LsqlU .= $separador."G2992_C59916 = '".$_POST["G2992_C59916"]."'";
                $LsqlI .= $separador."G2992_C59916";
                $LsqlV .= $separador."'".$_POST["G2992_C59916"]."'";
                $validar = 1;
            }
                
  

            if(isset($_POST["G2992_C59917"])){
                $separador = "";
                if($validar == 1){
                    $separador = ",";
                }

                $LsqlU .= $separador."G2992_C59917 = '".$_POST["G2992_C59917"]."'";
                $LsqlI .= $separador."G2992_C59917";
                $LsqlV .= $separador."'".$_POST["G2992_C59917"]."'";
                $validar = 1;
            }
                
  

            if(isset($_POST["G2992_C59918"])){
                $separador = "";
                if($validar == 1){
                    $separador = ",";
                }

                $LsqlU .= $separador."G2992_C59918 = '".$_POST["G2992_C59918"]."'";
                $LsqlI .= $separador."G2992_C59918";
                $LsqlV .= $separador."'".$_POST["G2992_C59918"]."'";
                $validar = 1;
            }
                
  

            if(isset($_POST["G2992_C59919"])){
                $separador = "";
                if($validar == 1){
                    $separador = ",";
                }

                $LsqlU .= $separador."G2992_C59919 = '".$_POST["G2992_C59919"]."'";
                $LsqlI .= $separador."G2992_C59919";
                $LsqlV .= $separador."'".$_POST["G2992_C59919"]."'";
                $validar = 1;
            }
                
  

            if(isset($_POST["G2992_C59920"])){
                $separador = "";
                if($validar == 1){
                    $separador = ",";
                }

                $LsqlU .= $separador."G2992_C59920 = '".$_POST["G2992_C59920"]."'";
                $LsqlI .= $separador."G2992_C59920";
                $LsqlV .= $separador."'".$_POST["G2992_C59920"]."'";
                $validar = 1;
            }
                
  

            if(isset($_POST["G2992_C59921"])){
                $separador = "";
                if($validar == 1){
                    $separador = ",";
                }

                $LsqlU .= $separador."G2992_C59921 = '".$_POST["G2992_C59921"]."'";
                $LsqlI .= $separador."G2992_C59921";
                $LsqlV .= $separador."'".$_POST["G2992_C59921"]."'";
                $validar = 1;
            }
                
  

            if(isset($_POST["G2992_C59922"])){
                $separador = "";
                if($validar == 1){
                    $separador = ",";
                }

                $LsqlU .= $separador."G2992_C59922 = '".$_POST["G2992_C59922"]."'";
                $LsqlI .= $separador."G2992_C59922";
                $LsqlV .= $separador."'".$_POST["G2992_C59922"]."'";
                $validar = 1;
            }
                
  

            if(isset($_POST["G2992_C59923"])){
                $separador = "";
                if($validar == 1){
                    $separador = ",";
                }

                $LsqlU .= $separador."G2992_C59923 = '".$_POST["G2992_C59923"]."'";
                $LsqlI .= $separador."G2992_C59923";
                $LsqlV .= $separador."'".$_POST["G2992_C59923"]."'";
                $validar = 1;
            }
                
  

            if(isset($_POST["G2992_C59924"])){
                $separador = "";
                if($validar == 1){
                    $separador = ",";
                }

                $LsqlU .= $separador."G2992_C59924 = '".$_POST["G2992_C59924"]."'";
                $LsqlI .= $separador."G2992_C59924";
                $LsqlV .= $separador."'".$_POST["G2992_C59924"]."'";
                $validar = 1;
            }
                
  

            if(isset($_POST["G2992_C65254"])){
                $separador = "";
                if($validar == 1){
                    $separador = ",";
                }

                $LsqlU .= $separador."G2992_C65254 = '".$_POST["G2992_C65254"]."'";
                $LsqlI .= $separador."G2992_C65254";
                $LsqlV .= $separador."'".$_POST["G2992_C65254"]."'";
                $validar = 1;
            }
                
  

            if(isset($_POST["G2992_C59951"])){
                $separador = "";
                if($validar == 1){
                    $separador = ",";
                }

                $LsqlU .= $separador."G2992_C59951 = '".$_POST["G2992_C59951"]."'";
                $LsqlI .= $separador."G2992_C59951";
                $LsqlV .= $separador."'".$_POST["G2992_C59951"]."'";
                $validar = 1;
            }
                
  

            if(isset($_POST["G2992_C59952"])){
                $separador = "";
                if($validar == 1){
                    $separador = ",";
                }

                $LsqlU .= $separador."G2992_C59952 = '".$_POST["G2992_C59952"]."'";
                $LsqlI .= $separador."G2992_C59952";
                $LsqlV .= $separador."'".$_POST["G2992_C59952"]."'";
                $validar = 1;
            }
                
  

            if(isset($_POST["G2992_C59953"])){
                $separador = "";
                if($validar == 1){
                    $separador = ",";
                }

                $LsqlU .= $separador."G2992_C59953 = '".$_POST["G2992_C59953"]."'";
                $LsqlI .= $separador."G2992_C59953";
                $LsqlV .= $separador."'".$_POST["G2992_C59953"]."'";
                $validar = 1;
            }
                
  
            //este es tipo check primeo si viene y de acuerdo a su valor se pone 1 o 0
            $_POST["G2992_C61722"] = isset($_POST["G2992_C61722"]) ? 1 : 0;
            if(isset($_POST["G2992_C61722"])){
                $G2992_C61722 = $_POST["G2992_C61722"];
                $separador = "";
                if($validar == 1){
                    $separador = ",";
                }

                $LsqlU .= $separador." G2992_C61722 = ".$G2992_C61722."";
                $LsqlI .= $separador." G2992_C61722";
                $LsqlV .= $separador.$G2992_C61722;

                $validar = 1;
            }
  

            if(isset($_POST["G2992_C59955"])){
                $separador = "";
                if($validar == 1){
                    $separador = ",";
                }

                $LsqlU .= $separador."G2992_C59955 = '".$_POST["G2992_C59955"]."'";
                $LsqlI .= $separador."G2992_C59955";
                $LsqlV .= $separador."'".$_POST["G2992_C59955"]."'";
                $validar = 1;
            }
                
  

            if(isset($_POST["G2992_C59988"])){
                $separador = "";
                if($validar == 1){
                    $separador = ",";
                }

                $LsqlU .= $separador."G2992_C59988 = '".$_POST["G2992_C59988"]."'";
                $LsqlI .= $separador."G2992_C59988";
                $LsqlV .= $separador."'".$_POST["G2992_C59988"]."'";
                $validar = 1;
            }
                
  

            if(isset($_POST["G2992_C59990"])){
                $separador = "";
                if($validar == 1){
                    $separador = ",";
                }

                $LsqlU .= $separador."G2992_C59990 = '".$_POST["G2992_C59990"]."'";
                $LsqlI .= $separador."G2992_C59990";
                $LsqlV .= $separador."'".$_POST["G2992_C59990"]."'";
                $validar = 1;
            }
                
  

            if(isset($_POST["G2992_C59992"])){
                $separador = "";
                if($validar == 1){
                    $separador = ",";
                }

                $LsqlU .= $separador."G2992_C59992 = '".$_POST["G2992_C59992"]."'";
                $LsqlI .= $separador."G2992_C59992";
                $LsqlV .= $separador."'".$_POST["G2992_C59992"]."'";
                $validar = 1;
            }
                
  

            if(isset($_POST["G2992_C59994"])){
                $separador = "";
                if($validar == 1){
                    $separador = ",";
                }

                $LsqlU .= $separador."G2992_C59994 = '".$_POST["G2992_C59994"]."'";
                $LsqlI .= $separador."G2992_C59994";
                $LsqlV .= $separador."'".$_POST["G2992_C59994"]."'";
                $validar = 1;
            }
                
  

            if(isset($_POST["G2992_C59996"])){
                $separador = "";
                if($validar == 1){
                    $separador = ",";
                }

                $LsqlU .= $separador."G2992_C59996 = '".$_POST["G2992_C59996"]."'";
                $LsqlI .= $separador."G2992_C59996";
                $LsqlV .= $separador."'".$_POST["G2992_C59996"]."'";
                $validar = 1;
            }
                
  

            if(isset($_POST["G2992_C59998"])){
                $separador = "";
                if($validar == 1){
                    $separador = ",";
                }

                $LsqlU .= $separador."G2992_C59998 = '".$_POST["G2992_C59998"]."'";
                $LsqlI .= $separador."G2992_C59998";
                $LsqlV .= $separador."'".$_POST["G2992_C59998"]."'";
                $validar = 1;
            }
                
  

            if(isset($_POST["G2992_C60000"])){
                $separador = "";
                if($validar == 1){
                    $separador = ",";
                }

                $LsqlU .= $separador."G2992_C60000 = '".$_POST["G2992_C60000"]."'";
                $LsqlI .= $separador."G2992_C60000";
                $LsqlV .= $separador."'".$_POST["G2992_C60000"]."'";
                $validar = 1;
            }
                
  

            if(isset($_POST["G2992_C60002"])){
                $separador = "";
                if($validar == 1){
                    $separador = ",";
                }

                $LsqlU .= $separador."G2992_C60002 = '".$_POST["G2992_C60002"]."'";
                $LsqlI .= $separador."G2992_C60002";
                $LsqlV .= $separador."'".$_POST["G2992_C60002"]."'";
                $validar = 1;
            }
                
  

            if(isset($_POST["G2992_C60004"])){
                $separador = "";
                if($validar == 1){
                    $separador = ",";
                }

                $LsqlU .= $separador."G2992_C60004 = '".$_POST["G2992_C60004"]."'";
                $LsqlI .= $separador."G2992_C60004";
                $LsqlV .= $separador."'".$_POST["G2992_C60004"]."'";
                $validar = 1;
            }
                
  

            if(isset($_POST["G2992_C60006"])){
                $separador = "";
                if($validar == 1){
                    $separador = ",";
                }

                $LsqlU .= $separador."G2992_C60006 = '".$_POST["G2992_C60006"]."'";
                $LsqlI .= $separador."G2992_C60006";
                $LsqlV .= $separador."'".$_POST["G2992_C60006"]."'";
                $validar = 1;
            }
                
  

            if(isset($_POST["G2992_C66544"])){
                $separador = "";
                if($validar == 1){
                    $separador = ",";
                }

                $LsqlU .= $separador."G2992_C66544 = '".$_POST["G2992_C66544"]."'";
                $LsqlI .= $separador."G2992_C66544";
                $LsqlV .= $separador."'".$_POST["G2992_C66544"]."'";
                $validar = 1;
            }
                
  

            if(isset($_POST["G2992_C66545"])){
                $separador = "";
                if($validar == 1){
                    $separador = ",";
                }

                $LsqlU .= $separador."G2992_C66545 = '".$_POST["G2992_C66545"]."'";
                $LsqlI .= $separador."G2992_C66545";
                $LsqlV .= $separador."'".$_POST["G2992_C66545"]."'";
                $validar = 1;
            }
                
  

            if(isset($_POST["G2992_C61150"])){
                $separador = "";
                if($validar == 1){
                    $separador = ",";
                }

                $LsqlU .= $separador."G2992_C61150 = '".$_POST["G2992_C61150"]."'";
                $LsqlI .= $separador."G2992_C61150";
                $LsqlV .= $separador."'".$_POST["G2992_C61150"]."'";
                $validar = 1;
            }
                
  
            $G2992_C61149 = NULL;
            //este es de tipo numero no se deja ir asi '', si est avacio lo mejor es no mandarlo
            if(isset($_POST["G2992_C61149"])){
                if($_POST["G2992_C61149"] != ''){
                    $_POST["G2992_C61149"]=str_replace($valor,"",$_POST["G2992_C61149"]);
                    $separador = "";
                    if($validar == 1){
                        $separador = ",";
                    }

                    $G2992_C61149 = $_POST["G2992_C61149"];
                    $LsqlU .= $separador." G2992_C61149 = ".$G2992_C61149."";
                    $LsqlI .= $separador." G2992_C61149";
                    $LsqlV .= $separador.$G2992_C61149;
                    $validar = 1;
                }
            }
  

            if(isset($_POST["G2992_C61151"])){
                $separador = "";
                if($validar == 1){
                    $separador = ",";
                }

                $LsqlU .= $separador."G2992_C61151 = '".$_POST["G2992_C61151"]."'";
                $LsqlI .= $separador."G2992_C61151";
                $LsqlV .= $separador."'".$_POST["G2992_C61151"]."'";
                $validar = 1;
            }
                
  

            if(isset($_POST["G2992_C61152"])){
                $separador = "";
                if($validar == 1){
                    $separador = ",";
                }

                $LsqlU .= $separador."G2992_C61152 = '".$_POST["G2992_C61152"]."'";
                $LsqlI .= $separador."G2992_C61152";
                $LsqlV .= $separador."'".$_POST["G2992_C61152"]."'";
                $validar = 1;
            }
                
 
            $G2992_C61153 = NULL;
            //este es de tipo date hay que preguntar si esta vacia o no
            if(isset($_POST["G2992_C61153"])){    
                if($_POST["G2992_C61153"] != ''){
                    if(validateDate(str_replace(' ', '',$_POST["G2992_C61153"])." 00:00:00")){
                        $separador = "";
                        if($validar == 1){
                            $separador = ",";
                        }

                        $tieneHora = explode(' ' , $_POST["G2992_C61153"]);
                        if(count($tieneHora) > 1){
                            $G2992_C61153 = "'".$_POST["G2992_C61153"]."'";
                        }else{
                            $G2992_C61153 = "'".str_replace(' ', '',$_POST["G2992_C61153"])." 00:00:00'";
                        }


                        $LsqlU .= $separador." G2992_C61153 = ".$G2992_C61153;
                        $LsqlI .= $separador." G2992_C61153";
                        $LsqlV .= $separador.$G2992_C61153;
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
  

            if(isset($_POST["G2992_C61154"])){
                $separador = "";
                if($validar == 1){
                    $separador = ",";
                }

                $LsqlU .= $separador."G2992_C61154 = '".$_POST["G2992_C61154"]."'";
                $LsqlI .= $separador."G2992_C61154";
                $LsqlV .= $separador."'".$_POST["G2992_C61154"]."'";
                $validar = 1;
            }
                
  

            if(isset($_POST["G2992_C66507"])){
                $separador = "";
                if($validar == 1){
                    $separador = ",";
                }

                $LsqlU .= $separador."G2992_C66507 = '".$_POST["G2992_C66507"]."'";
                $LsqlI .= $separador."G2992_C66507";
                $LsqlV .= $separador."'".$_POST["G2992_C66507"]."'";
                $validar = 1;
            }
                
  

            if(isset($_POST["G2992_C61320"])){
                $separador = "";
                if($validar == 1){
                    $separador = ",";
                }

                $LsqlU .= $separador."G2992_C61320 = '".$_POST["G2992_C61320"]."'";
                $LsqlI .= $separador."G2992_C61320";
                $LsqlV .= $separador."'".$_POST["G2992_C61320"]."'";
                $validar = 1;
            }
                
 
            $G2992_C66401 = NULL;
            //este es de tipo date hay que preguntar si esta vacia o no
            if(isset($_POST["G2992_C66401"])){    
                if($_POST["G2992_C66401"] != ''){
                    if(validateDate(str_replace(' ', '',$_POST["G2992_C66401"])." 00:00:00")){
                        $separador = "";
                        if($validar == 1){
                            $separador = ",";
                        }

                        $tieneHora = explode(' ' , $_POST["G2992_C66401"]);
                        if(count($tieneHora) > 1){
                            $G2992_C66401 = "'".$_POST["G2992_C66401"]."'";
                        }else{
                            $G2992_C66401 = "'".str_replace(' ', '',$_POST["G2992_C66401"])." 00:00:00'";
                        }


                        $LsqlU .= $separador." G2992_C66401 = ".$G2992_C66401;
                        $LsqlI .= $separador." G2992_C66401";
                        $LsqlV .= $separador.$G2992_C66401;
                        $validar = 1;
                    }else{
                        if(!isset($_GET["LlamadoExterno"])){
                            $data=json_encode(array("estado"=>"Error","mensaje"=>"Validar el campo Fecha llamada"));
                            echo $data;
                            exit();
                        }
                    }
                }
            }
  

            if(isset($_POST["G2992_C61319"])){
                $separador = "";
                if($validar == 1){
                    $separador = ",";
                }

                $LsqlU .= $separador."G2992_C61319 = '".$_POST["G2992_C61319"]."'";
                $LsqlI .= $separador."G2992_C61319";
                $LsqlV .= $separador."'".$_POST["G2992_C61319"]."'";
                $validar = 1;
            }
                
  

            if(isset($_POST["G2992_C66484"])){
                $separador = "";
                if($validar == 1){
                    $separador = ",";
                }

                $LsqlU .= $separador."G2992_C66484 = '".$_POST["G2992_C66484"]."'";
                $LsqlI .= $separador."G2992_C66484";
                $LsqlV .= $separador."'".$_POST["G2992_C66484"]."'";
                $validar = 1;
            }
                
  
            $G2992_C66402 = NULL;
            //este es de tipo date hay que preguntar si esta vacia o no o esta undefined
            if(isset($_POST["G2992_C66402"])){   
                if($_POST["G2992_C66402"] != '' && $_POST["G2992_C66402"] != 'undefined' && $_POST["G2992_C66402"] != 'null'){
                    $fecha = date('Y-m-d');
                    if(validateDate($fecha." ".str_replace(' ', '',$_POST["G2992_C66402"]))){
                        $separador = "";
                        if($validar == 1){
                            $separador = ",";
                        }

                        $G2992_C66402 = "'".$fecha." ".str_replace(' ', '',$_POST["G2992_C66402"])."'";
                        $LsqlU .= $separador." G2992_C66402 = ".$G2992_C66402."";
                        $LsqlI .= $separador." G2992_C66402";
                        $LsqlV .= $separador.$G2992_C66402;
                        $validar = 1;
                    }else{
                        if(!isset($_GET["LlamadoExterno"])){
                            $data=json_encode(array("estado"=>"Error","mensaje"=>"Validar el campo Hora llamada"));
                            echo $data;
                            exit();
                        }
                    }
                }
            }
  
            $G2992_C65875 = NULL;
            //este es de tipo numero no se deja ir asi '', si est avacio lo mejor es no mandarlo
            if(isset($_POST["G2992_C65875"])){
                if($_POST["G2992_C65875"] != ''){
                    $_POST["G2992_C65875"]=str_replace($valor,"",$_POST["G2992_C65875"]);
                    $separador = "";
                    if($validar == 1){
                        $separador = ",";
                    }

                    $G2992_C65875 = $_POST["G2992_C65875"];
                    $LsqlU .= $separador." G2992_C65875 = ".$G2992_C65875."";
                    $LsqlI .= $separador." G2992_C65875";
                    $LsqlV .= $separador.$G2992_C65875;
                    $validar = 1;
                }
            }
  
            $G2992_C65876 = NULL;
            //este es de tipo numero no se deja ir asi '', si est avacio lo mejor es no mandarlo
            if(isset($_POST["G2992_C65876"])){
                if($_POST["G2992_C65876"] != ''){
                    $_POST["G2992_C65876"]=str_replace($valor,"",$_POST["G2992_C65876"]);
                    $separador = "";
                    if($validar == 1){
                        $separador = ",";
                    }

                    $G2992_C65876 = $_POST["G2992_C65876"];
                    $LsqlU .= $separador." G2992_C65876 = ".$G2992_C65876."";
                    $LsqlI .= $separador." G2992_C65876";
                    $LsqlV .= $separador.$G2992_C65876;
                    $validar = 1;
                }
            }
  
            $G2992_C65877 = NULL;
            //este es de tipo numero no se deja ir asi '', si est avacio lo mejor es no mandarlo
            if(isset($_POST["G2992_C65877"])){
                if($_POST["G2992_C65877"] != ''){
                    $_POST["G2992_C65877"]=str_replace($valor,"",$_POST["G2992_C65877"]);
                    $separador = "";
                    if($validar == 1){
                        $separador = ",";
                    }

                    $G2992_C65877 = $_POST["G2992_C65877"];
                    $LsqlU .= $separador." G2992_C65877 = ".$G2992_C65877."";
                    $LsqlI .= $separador." G2992_C65877";
                    $LsqlV .= $separador.$G2992_C65877;
                    $validar = 1;
                }
            }
  
            $G2992_C65878 = NULL;
            //este es de tipo numero no se deja ir asi '', si est avacio lo mejor es no mandarlo
            if(isset($_POST["G2992_C65878"])){
                if($_POST["G2992_C65878"] != ''){
                    $_POST["G2992_C65878"]=str_replace($valor,"",$_POST["G2992_C65878"]);
                    $separador = "";
                    if($validar == 1){
                        $separador = ",";
                    }

                    $G2992_C65878 = $_POST["G2992_C65878"];
                    $LsqlU .= $separador." G2992_C65878 = ".$G2992_C65878."";
                    $LsqlI .= $separador." G2992_C65878";
                    $LsqlV .= $separador.$G2992_C65878;
                    $validar = 1;
                }
            }
  
            $G2992_C65879 = NULL;
            //este es de tipo numero no se deja ir asi '', si est avacio lo mejor es no mandarlo
            if(isset($_POST["G2992_C65879"])){
                if($_POST["G2992_C65879"] != ''){
                    $_POST["G2992_C65879"]=str_replace($valor,"",$_POST["G2992_C65879"]);
                    $separador = "";
                    if($validar == 1){
                        $separador = ",";
                    }

                    $G2992_C65879 = $_POST["G2992_C65879"];
                    $LsqlU .= $separador." G2992_C65879 = ".$G2992_C65879."";
                    $LsqlI .= $separador." G2992_C65879";
                    $LsqlV .= $separador.$G2992_C65879;
                    $validar = 1;
                }
            }
  
            $G2992_C65880 = NULL;
            //este es de tipo numero no se deja ir asi '', si est avacio lo mejor es no mandarlo
            if(isset($_POST["G2992_C65880"])){
                if($_POST["G2992_C65880"] != ''){
                    $_POST["G2992_C65880"]=str_replace($valor,"",$_POST["G2992_C65880"]);
                    $separador = "";
                    if($validar == 1){
                        $separador = ",";
                    }

                    $G2992_C65880 = $_POST["G2992_C65880"];
                    $LsqlU .= $separador." G2992_C65880 = ".$G2992_C65880."";
                    $LsqlI .= $separador." G2992_C65880";
                    $LsqlV .= $separador.$G2992_C65880;
                    $validar = 1;
                }
            }
  
            $G2992_C65881 = NULL;
            //este es de tipo numero no se deja ir asi '', si est avacio lo mejor es no mandarlo
            if(isset($_POST["G2992_C65881"])){
                if($_POST["G2992_C65881"] != ''){
                    $_POST["G2992_C65881"]=str_replace($valor,"",$_POST["G2992_C65881"]);
                    $separador = "";
                    if($validar == 1){
                        $separador = ",";
                    }

                    $G2992_C65881 = $_POST["G2992_C65881"];
                    $LsqlU .= $separador." G2992_C65881 = ".$G2992_C65881."";
                    $LsqlI .= $separador." G2992_C65881";
                    $LsqlV .= $separador.$G2992_C65881;
                    $validar = 1;
                }
            }
  
            $G2992_C65882 = NULL;
            //este es de tipo numero no se deja ir asi '', si est avacio lo mejor es no mandarlo
            if(isset($_POST["G2992_C65882"])){
                if($_POST["G2992_C65882"] != ''){
                    $_POST["G2992_C65882"]=str_replace($valor,"",$_POST["G2992_C65882"]);
                    $separador = "";
                    if($validar == 1){
                        $separador = ",";
                    }

                    $G2992_C65882 = $_POST["G2992_C65882"];
                    $LsqlU .= $separador." G2992_C65882 = ".$G2992_C65882."";
                    $LsqlI .= $separador." G2992_C65882";
                    $LsqlV .= $separador.$G2992_C65882;
                    $validar = 1;
                }
            }
  
            $G2992_C65883 = NULL;
            //este es de tipo numero no se deja ir asi '', si est avacio lo mejor es no mandarlo
            if(isset($_POST["G2992_C65883"])){
                if($_POST["G2992_C65883"] != ''){
                    $_POST["G2992_C65883"]=str_replace($valor,"",$_POST["G2992_C65883"]);
                    $separador = "";
                    if($validar == 1){
                        $separador = ",";
                    }

                    $G2992_C65883 = $_POST["G2992_C65883"];
                    $LsqlU .= $separador." G2992_C65883 = ".$G2992_C65883."";
                    $LsqlI .= $separador." G2992_C65883";
                    $LsqlV .= $separador.$G2992_C65883;
                    $validar = 1;
                }
            }
  
            $G2992_C65884 = NULL;
            //este es de tipo numero no se deja ir asi '', si est avacio lo mejor es no mandarlo
            if(isset($_POST["G2992_C65884"])){
                if($_POST["G2992_C65884"] != ''){
                    $_POST["G2992_C65884"]=str_replace($valor,"",$_POST["G2992_C65884"]);
                    $separador = "";
                    if($validar == 1){
                        $separador = ",";
                    }

                    $G2992_C65884 = $_POST["G2992_C65884"];
                    $LsqlU .= $separador." G2992_C65884 = ".$G2992_C65884."";
                    $LsqlI .= $separador." G2992_C65884";
                    $LsqlV .= $separador.$G2992_C65884;
                    $validar = 1;
                }
            }
  
            $G2992_C65885 = NULL;
            //este es de tipo numero no se deja ir asi '', si est avacio lo mejor es no mandarlo
            if(isset($_POST["G2992_C65885"])){
                if($_POST["G2992_C65885"] != ''){
                    $_POST["G2992_C65885"]=str_replace($valor,"",$_POST["G2992_C65885"]);
                    $separador = "";
                    if($validar == 1){
                        $separador = ",";
                    }

                    $G2992_C65885 = $_POST["G2992_C65885"];
                    $LsqlU .= $separador." G2992_C65885 = ".$G2992_C65885."";
                    $LsqlI .= $separador." G2992_C65885";
                    $LsqlV .= $separador.$G2992_C65885;
                    $validar = 1;
                }
            }
  

            if(isset($_POST["G2992_C71470"])){
                $separador = "";
                if($validar == 1){
                    $separador = ",";
                }

                $LsqlU .= $separador."G2992_C71470 = '".$_POST["G2992_C71470"]."'";
                $LsqlI .= $separador."G2992_C71470";
                $LsqlV .= $separador."'".$_POST["G2992_C71470"]."'";
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

                            $LsqlU .= $separador."G2992_Clasificacion = ".$conatcto;
                            $LsqlI .= $separador."G2992_Clasificacion";
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

            $LsqlI .= $separador."G2992_PoblacionOrigen";
            $LsqlV .= $separador."'".$pasoOrigenId."'";
            $validar = 1;
            
            if(isset($_GET['id_gestion_cbxx2'])){
                $separador = "";
                if($validar == 1){
                    $separador = ",";
                }

                $LsqlU .= $separador."G2992_IdLlamada = '".$_GET['id_gestion_cbxx2']."'";
                $LsqlI .= $separador."G2992_IdLlamada";
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
                    $valorG = "G2992_C";
                    $valorH = $valorG.$campo;
                    $LsqlU .= $separador." " .$valorH." = ".$_POST["padre"];
                    $LsqlI .= $separador." ".$valorH;
                    $LsqlV .= $separador.$_POST['padre'] ;
                    $validar = 1;
                }
            }
            if(isset($_POST['oper'])){
                if($_POST["oper"] == 'add' ){
                    $LsqlI .= ", G2992_Usuario , G2992_FechaInsercion, G2992_CodigoMiembro";
                    if(!isset($_GET['CodigoMiembro']) && !is_numeric($_GET['CodigoMiembro'])){
                        $_GET['CodigoMiembro']=-1;
                    }
                    $LsqlV .= ", ".$_GET['usuario']." , '".date('Y-m-d H:i:s')."', ".$_GET['CodigoMiembro'];
                    $Lsql = $LsqlI.")" . $LsqlV.")";
                }else if($_POST["oper"] == 'edit' ){
                    $Lsql = $LsqlU." WHERE G2992_ConsInte__b =".$_POST["id"]; 
                    //echo $Lsql;die();
                }else if($_POST["oper"] == 'del' ){
                    $Lsql = "DELETE FROM ".$BaseDatos.".G2992 WHERE G2992_ConsInte__b = ".$_POST['id'];
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
  

    if(isset($_GET["callDatosSubgrilla_0"])){

        $numero = $_GET['id'];
        if(isset($_GET['idBd'])){
            $sqlMiembro=$mysqli->query("SELECT G2992_CodigoMiembro AS miembro FROM DYALOGOCRM_WEB.G2992 WHERE G2992_ConsInte__b={$numero}");
            if($sqlMiembro && $sqlMiembro-> num_rows ==1){
                $sqlMiembro=$sqlMiembro->fetch_object();
                $numero=$sqlMiembro->miembro;            
            }
        }

        $SQL = "SELECT G3286_ConsInte__b, G3286_C67397, G3286_C67398, G3286_C67399, G3286_C70893, G3286_C71545, G3286_C70872, G3286_C70873, G3286_C70874, G3286_C70875, G3286_C70876, G3286_C70877, G3286_C70881, G3286_C70882, G3286_C70883, G3286_C70884, G3286_C70885, G3286_C70886, G3286_C71553 FROM ".$BaseDatos.".G3286  ";

        $SQL .= " WHERE G3286_C67399 = '".$numero."'"; 

        $SQL .= " ORDER BY G3286_C67397";

        // echo $SQL;
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
            echo "<row asin='".$fila->G3286_ConsInte__b."'>"; 
            echo "<cell>". ($fila->G3286_ConsInte__b)."</cell>"; 
            

                echo "<cell>". ($fila->G3286_C67397)."</cell>";

                echo "<cell>". ($fila->G3286_C67398)."</cell>";

                echo "<cell>". $fila->G3286_C67399."</cell>"; 

                echo "<cell>". ($fila->G3286_C70893)."</cell>";

                echo "<cell><![CDATA[". ($fila->G3286_C71545)."]]></cell>";

                echo "<cell>". ($fila->G3286_C70872)."</cell>";

                echo "<cell>". ($fila->G3286_C70873)."</cell>";

                echo "<cell>". ($fila->G3286_C70874)."</cell>";

                echo "<cell>". ($fila->G3286_C70875)."</cell>";

                echo "<cell>". ($fila->G3286_C70876)."</cell>";

                echo "<cell>". ($fila->G3286_C70877)."</cell>";

                echo "<cell>". ($fila->G3286_C70881)."</cell>";

                echo "<cell>". ($fila->G3286_C70882)."</cell>";

                echo "<cell>". ($fila->G3286_C70883)."</cell>";

                echo "<cell>". ($fila->G3286_C70884)."</cell>";

                echo "<cell>". ($fila->G3286_C70885)."</cell>";

                echo "<cell>". ($fila->G3286_C70886)."</cell>";

                echo "<cell>". ($fila->G3286_C71553)."</cell>";
            echo "</row>"; 
        } 
        echo "</rows>"; 
    }

  
    if(isset($_GET["insertarDatosSubgrilla_0"])){
        
        if(isset($_POST["oper"])){
            $Lsql  = '';

            $validar = 0;
            $LsqlU = "UPDATE ".$BaseDatos.".G3286 SET "; 
            $LsqlI = "INSERT INTO ".$BaseDatos.".G3286(";
            $LsqlV = " VALUES ("; 
 

                if(isset($_POST["G3286_C67397"])){
                    $separador = "";
                    if($validar == 1){
                        $separador = ",";
                    }

                    $LsqlU .= $separador."G3286_C67397 = '".$_POST["G3286_C67397"]."'";
                    $LsqlI .= $separador."G3286_C67397";
                    $LsqlV .= $separador."'".$_POST["G3286_C67397"]."'";
                    $validar = 1;
                }

                                                                               
 

                if(isset($_POST["G3286_C67398"])){
                    $separador = "";
                    if($validar == 1){
                        $separador = ",";
                    }

                    $LsqlU .= $separador."G3286_C67398 = '".$_POST["G3286_C67398"]."'";
                    $LsqlI .= $separador."G3286_C67398";
                    $LsqlV .= $separador."'".$_POST["G3286_C67398"]."'";
                    $validar = 1;
                }

                                                                               
 

                if(isset($_POST["G3286_C70893"])){
                    $separador = "";
                    if($validar == 1){
                        $separador = ",";
                    }

                    $LsqlU .= $separador."G3286_C70893 = '".$_POST["G3286_C70893"]."'";
                    $LsqlI .= $separador."G3286_C70893";
                    $LsqlV .= $separador."'".$_POST["G3286_C70893"]."'";
                    $validar = 1;
                }

                                                                               
  

                if(isset($_POST["G3286_C71545"])){
                    $separador = "";
                    if($validar == 1){
                        $separador = ",";
                    }

                    $LsqlU .= $separador."G3286_C71545 = '".$_POST["G3286_C71545"]."'";
                    $LsqlI .= $separador."G3286_C71545";
                    $LsqlV .= $separador."'".$_POST["G3286_C71545"]."'";
                    $validar = 1;
                }
                                                                               
 

                if (isset($_FILES["FG3286_C70872"]["tmp_name"])) {

                    $destinoFile = "/Dyalogo/tmp/G3286/";
                    $fechUp = date("Y-m-d_H:i:s");
                    if (!file_exists("/Dyalogo/tmp/G3286")){
                        mkdir("/Dyalogo/tmp/G3286", 0777);
                    }

                    if ($_FILES["FG3286_C70872"]["size"] != 0) {
                        $G3286_C70872 = $_FILES["FG3286_C70872"]["tmp_name"];
                        $nG3286_C70872 = $fechUp."_".$_FILES["FG3286_C70872"]["name"];
                        $rutaFinal = $destinoFile.$nG3286_C70872;
                        if (is_uploaded_file($G3286_C70872)) {
                            move_uploaded_file($G3286_C70872, $rutaFinal);
                        }

                        $separador = "";
                        if($validar == 1){
                            $separador = ",";
                        }

                        $LsqlU .= $separador."G3286_C70872 = '".$nG3286_C70872."'";
                        $LsqlI .= $separador."G3286_C70872";
                        $LsqlV .= $separador."'".$nG3286_C70872."'";
                        $validar = 1;
                    }
                }
 

                if (isset($_FILES["FG3286_C70873"]["tmp_name"])) {

                    $destinoFile = "/Dyalogo/tmp/G3286/";
                    $fechUp = date("Y-m-d_H:i:s");
                    if (!file_exists("/Dyalogo/tmp/G3286")){
                        mkdir("/Dyalogo/tmp/G3286", 0777);
                    }

                    if ($_FILES["FG3286_C70873"]["size"] != 0) {
                        $G3286_C70873 = $_FILES["FG3286_C70873"]["tmp_name"];
                        $nG3286_C70873 = $fechUp."_".$_FILES["FG3286_C70873"]["name"];
                        $rutaFinal = $destinoFile.$nG3286_C70873;
                        if (is_uploaded_file($G3286_C70873)) {
                            move_uploaded_file($G3286_C70873, $rutaFinal);
                        }

                        $separador = "";
                        if($validar == 1){
                            $separador = ",";
                        }

                        $LsqlU .= $separador."G3286_C70873 = '".$nG3286_C70873."'";
                        $LsqlI .= $separador."G3286_C70873";
                        $LsqlV .= $separador."'".$nG3286_C70873."'";
                        $validar = 1;
                    }
                }
 

                if (isset($_FILES["FG3286_C70874"]["tmp_name"])) {

                    $destinoFile = "/Dyalogo/tmp/G3286/";
                    $fechUp = date("Y-m-d_H:i:s");
                    if (!file_exists("/Dyalogo/tmp/G3286")){
                        mkdir("/Dyalogo/tmp/G3286", 0777);
                    }

                    if ($_FILES["FG3286_C70874"]["size"] != 0) {
                        $G3286_C70874 = $_FILES["FG3286_C70874"]["tmp_name"];
                        $nG3286_C70874 = $fechUp."_".$_FILES["FG3286_C70874"]["name"];
                        $rutaFinal = $destinoFile.$nG3286_C70874;
                        if (is_uploaded_file($G3286_C70874)) {
                            move_uploaded_file($G3286_C70874, $rutaFinal);
                        }

                        $separador = "";
                        if($validar == 1){
                            $separador = ",";
                        }

                        $LsqlU .= $separador."G3286_C70874 = '".$nG3286_C70874."'";
                        $LsqlI .= $separador."G3286_C70874";
                        $LsqlV .= $separador."'".$nG3286_C70874."'";
                        $validar = 1;
                    }
                }
 

                if (isset($_FILES["FG3286_C70875"]["tmp_name"])) {

                    $destinoFile = "/Dyalogo/tmp/G3286/";
                    $fechUp = date("Y-m-d_H:i:s");
                    if (!file_exists("/Dyalogo/tmp/G3286")){
                        mkdir("/Dyalogo/tmp/G3286", 0777);
                    }

                    if ($_FILES["FG3286_C70875"]["size"] != 0) {
                        $G3286_C70875 = $_FILES["FG3286_C70875"]["tmp_name"];
                        $nG3286_C70875 = $fechUp."_".$_FILES["FG3286_C70875"]["name"];
                        $rutaFinal = $destinoFile.$nG3286_C70875;
                        if (is_uploaded_file($G3286_C70875)) {
                            move_uploaded_file($G3286_C70875, $rutaFinal);
                        }

                        $separador = "";
                        if($validar == 1){
                            $separador = ",";
                        }

                        $LsqlU .= $separador."G3286_C70875 = '".$nG3286_C70875."'";
                        $LsqlI .= $separador."G3286_C70875";
                        $LsqlV .= $separador."'".$nG3286_C70875."'";
                        $validar = 1;
                    }
                }
 

                if (isset($_FILES["FG3286_C70876"]["tmp_name"])) {

                    $destinoFile = "/Dyalogo/tmp/G3286/";
                    $fechUp = date("Y-m-d_H:i:s");
                    if (!file_exists("/Dyalogo/tmp/G3286")){
                        mkdir("/Dyalogo/tmp/G3286", 0777);
                    }

                    if ($_FILES["FG3286_C70876"]["size"] != 0) {
                        $G3286_C70876 = $_FILES["FG3286_C70876"]["tmp_name"];
                        $nG3286_C70876 = $fechUp."_".$_FILES["FG3286_C70876"]["name"];
                        $rutaFinal = $destinoFile.$nG3286_C70876;
                        if (is_uploaded_file($G3286_C70876)) {
                            move_uploaded_file($G3286_C70876, $rutaFinal);
                        }

                        $separador = "";
                        if($validar == 1){
                            $separador = ",";
                        }

                        $LsqlU .= $separador."G3286_C70876 = '".$nG3286_C70876."'";
                        $LsqlI .= $separador."G3286_C70876";
                        $LsqlV .= $separador."'".$nG3286_C70876."'";
                        $validar = 1;
                    }
                }
 

                if (isset($_FILES["FG3286_C70877"]["tmp_name"])) {

                    $destinoFile = "/Dyalogo/tmp/G3286/";
                    $fechUp = date("Y-m-d_H:i:s");
                    if (!file_exists("/Dyalogo/tmp/G3286")){
                        mkdir("/Dyalogo/tmp/G3286", 0777);
                    }

                    if ($_FILES["FG3286_C70877"]["size"] != 0) {
                        $G3286_C70877 = $_FILES["FG3286_C70877"]["tmp_name"];
                        $nG3286_C70877 = $fechUp."_".$_FILES["FG3286_C70877"]["name"];
                        $rutaFinal = $destinoFile.$nG3286_C70877;
                        if (is_uploaded_file($G3286_C70877)) {
                            move_uploaded_file($G3286_C70877, $rutaFinal);
                        }

                        $separador = "";
                        if($validar == 1){
                            $separador = ",";
                        }

                        $LsqlU .= $separador."G3286_C70877 = '".$nG3286_C70877."'";
                        $LsqlI .= $separador."G3286_C70877";
                        $LsqlV .= $separador."'".$nG3286_C70877."'";
                        $validar = 1;
                    }
                }
 

                if (isset($_FILES["FG3286_C70881"]["tmp_name"])) {

                    $destinoFile = "/Dyalogo/tmp/G3286/";
                    $fechUp = date("Y-m-d_H:i:s");
                    if (!file_exists("/Dyalogo/tmp/G3286")){
                        mkdir("/Dyalogo/tmp/G3286", 0777);
                    }

                    if ($_FILES["FG3286_C70881"]["size"] != 0) {
                        $G3286_C70881 = $_FILES["FG3286_C70881"]["tmp_name"];
                        $nG3286_C70881 = $fechUp."_".$_FILES["FG3286_C70881"]["name"];
                        $rutaFinal = $destinoFile.$nG3286_C70881;
                        if (is_uploaded_file($G3286_C70881)) {
                            move_uploaded_file($G3286_C70881, $rutaFinal);
                        }

                        $separador = "";
                        if($validar == 1){
                            $separador = ",";
                        }

                        $LsqlU .= $separador."G3286_C70881 = '".$nG3286_C70881."'";
                        $LsqlI .= $separador."G3286_C70881";
                        $LsqlV .= $separador."'".$nG3286_C70881."'";
                        $validar = 1;
                    }
                }
 

                if (isset($_FILES["FG3286_C70882"]["tmp_name"])) {

                    $destinoFile = "/Dyalogo/tmp/G3286/";
                    $fechUp = date("Y-m-d_H:i:s");
                    if (!file_exists("/Dyalogo/tmp/G3286")){
                        mkdir("/Dyalogo/tmp/G3286", 0777);
                    }

                    if ($_FILES["FG3286_C70882"]["size"] != 0) {
                        $G3286_C70882 = $_FILES["FG3286_C70882"]["tmp_name"];
                        $nG3286_C70882 = $fechUp."_".$_FILES["FG3286_C70882"]["name"];
                        $rutaFinal = $destinoFile.$nG3286_C70882;
                        if (is_uploaded_file($G3286_C70882)) {
                            move_uploaded_file($G3286_C70882, $rutaFinal);
                        }

                        $separador = "";
                        if($validar == 1){
                            $separador = ",";
                        }

                        $LsqlU .= $separador."G3286_C70882 = '".$nG3286_C70882."'";
                        $LsqlI .= $separador."G3286_C70882";
                        $LsqlV .= $separador."'".$nG3286_C70882."'";
                        $validar = 1;
                    }
                }
 

                if (isset($_FILES["FG3286_C70883"]["tmp_name"])) {

                    $destinoFile = "/Dyalogo/tmp/G3286/";
                    $fechUp = date("Y-m-d_H:i:s");
                    if (!file_exists("/Dyalogo/tmp/G3286")){
                        mkdir("/Dyalogo/tmp/G3286", 0777);
                    }

                    if ($_FILES["FG3286_C70883"]["size"] != 0) {
                        $G3286_C70883 = $_FILES["FG3286_C70883"]["tmp_name"];
                        $nG3286_C70883 = $fechUp."_".$_FILES["FG3286_C70883"]["name"];
                        $rutaFinal = $destinoFile.$nG3286_C70883;
                        if (is_uploaded_file($G3286_C70883)) {
                            move_uploaded_file($G3286_C70883, $rutaFinal);
                        }

                        $separador = "";
                        if($validar == 1){
                            $separador = ",";
                        }

                        $LsqlU .= $separador."G3286_C70883 = '".$nG3286_C70883."'";
                        $LsqlI .= $separador."G3286_C70883";
                        $LsqlV .= $separador."'".$nG3286_C70883."'";
                        $validar = 1;
                    }
                }
 

                if (isset($_FILES["FG3286_C70884"]["tmp_name"])) {

                    $destinoFile = "/Dyalogo/tmp/G3286/";
                    $fechUp = date("Y-m-d_H:i:s");
                    if (!file_exists("/Dyalogo/tmp/G3286")){
                        mkdir("/Dyalogo/tmp/G3286", 0777);
                    }

                    if ($_FILES["FG3286_C70884"]["size"] != 0) {
                        $G3286_C70884 = $_FILES["FG3286_C70884"]["tmp_name"];
                        $nG3286_C70884 = $fechUp."_".$_FILES["FG3286_C70884"]["name"];
                        $rutaFinal = $destinoFile.$nG3286_C70884;
                        if (is_uploaded_file($G3286_C70884)) {
                            move_uploaded_file($G3286_C70884, $rutaFinal);
                        }

                        $separador = "";
                        if($validar == 1){
                            $separador = ",";
                        }

                        $LsqlU .= $separador."G3286_C70884 = '".$nG3286_C70884."'";
                        $LsqlI .= $separador."G3286_C70884";
                        $LsqlV .= $separador."'".$nG3286_C70884."'";
                        $validar = 1;
                    }
                }
 

                if (isset($_FILES["FG3286_C70885"]["tmp_name"])) {

                    $destinoFile = "/Dyalogo/tmp/G3286/";
                    $fechUp = date("Y-m-d_H:i:s");
                    if (!file_exists("/Dyalogo/tmp/G3286")){
                        mkdir("/Dyalogo/tmp/G3286", 0777);
                    }

                    if ($_FILES["FG3286_C70885"]["size"] != 0) {
                        $G3286_C70885 = $_FILES["FG3286_C70885"]["tmp_name"];
                        $nG3286_C70885 = $fechUp."_".$_FILES["FG3286_C70885"]["name"];
                        $rutaFinal = $destinoFile.$nG3286_C70885;
                        if (is_uploaded_file($G3286_C70885)) {
                            move_uploaded_file($G3286_C70885, $rutaFinal);
                        }

                        $separador = "";
                        if($validar == 1){
                            $separador = ",";
                        }

                        $LsqlU .= $separador."G3286_C70885 = '".$nG3286_C70885."'";
                        $LsqlI .= $separador."G3286_C70885";
                        $LsqlV .= $separador."'".$nG3286_C70885."'";
                        $validar = 1;
                    }
                }
 

                if (isset($_FILES["FG3286_C70886"]["tmp_name"])) {

                    $destinoFile = "/Dyalogo/tmp/G3286/";
                    $fechUp = date("Y-m-d_H:i:s");
                    if (!file_exists("/Dyalogo/tmp/G3286")){
                        mkdir("/Dyalogo/tmp/G3286", 0777);
                    }

                    if ($_FILES["FG3286_C70886"]["size"] != 0) {
                        $G3286_C70886 = $_FILES["FG3286_C70886"]["tmp_name"];
                        $nG3286_C70886 = $fechUp."_".$_FILES["FG3286_C70886"]["name"];
                        $rutaFinal = $destinoFile.$nG3286_C70886;
                        if (is_uploaded_file($G3286_C70886)) {
                            move_uploaded_file($G3286_C70886, $rutaFinal);
                        }

                        $separador = "";
                        if($validar == 1){
                            $separador = ",";
                        }

                        $LsqlU .= $separador."G3286_C70886 = '".$nG3286_C70886."'";
                        $LsqlI .= $separador."G3286_C70886";
                        $LsqlV .= $separador."'".$nG3286_C70886."'";
                        $validar = 1;
                    }
                }
 

                if (isset($_FILES["FG3286_C71553"]["tmp_name"])) {

                    $destinoFile = "/Dyalogo/tmp/G3286/";
                    $fechUp = date("Y-m-d_H:i:s");
                    if (!file_exists("/Dyalogo/tmp/G3286")){
                        mkdir("/Dyalogo/tmp/G3286", 0777);
                    }

                    if ($_FILES["FG3286_C71553"]["size"] != 0) {
                        $G3286_C71553 = $_FILES["FG3286_C71553"]["tmp_name"];
                        $nG3286_C71553 = $fechUp."_".$_FILES["FG3286_C71553"]["name"];
                        $rutaFinal = $destinoFile.$nG3286_C71553;
                        if (is_uploaded_file($G3286_C71553)) {
                            move_uploaded_file($G3286_C71553, $rutaFinal);
                        }

                        $separador = "";
                        if($validar == 1){
                            $separador = ",";
                        }

                        $LsqlU .= $separador."G3286_C71553 = '".$nG3286_C71553."'";
                        $LsqlI .= $separador."G3286_C71553";
                        $LsqlV .= $separador."'".$nG3286_C71553."'";
                        $validar = 1;
                    }
                }

            if(isset($_POST["Padre"])){
                if($_POST["Padre"] != ''){
                    //esto es porque el padre es el entero
                    $numero = $_POST["Padre"];

                    $G3286_C67399 = $numero;
                    $LsqlU .= ", G3286_C67399 = ".$G3286_C67399."";
                    $LsqlI .= ", G3286_C67399";
                    $LsqlV .= ",".$_POST["Padre"];
                }
            }  



            if(isset($_POST['oper'])){
                if($_POST["oper"] == 'add' ){
                    $LsqlI .= ",  G3286_Usuario ,  G3286_FechaInsercion";
                    $LsqlV .= ", ".$_GET['usuario']." , '".date('Y-m-d H:i:s')."'";
                    $Lsql = $LsqlI.")" . $LsqlV.")";
                }else if($_POST["oper"] == 'edit' ){
                    $Lsql = $LsqlU." WHERE G3286_ConsInte__b =".$_POST["providerUserId"]; 
                }else if($_POST['oper'] == 'del'){
                    $Lsql = "DELETE FROM  ".$BaseDatos.".G3286 WHERE  G3286_ConsInte__b = ".$_POST['id'];
                    $validar = 1;
                }
            }

            if($validar == 1){
                // echo $Lsql;
                if ($mysqli->query($Lsql) === TRUE) {
                    echo $mysqli->insert_id;
                } else {
                    echo '0';
                    $queryCondia="INSERT INTO ".$BaseDatos_systema.".LOGGEST (LOGGEST_SQL_b,LOGGEST_Error_b,LOGGEST_Comentario_b)
                    VALUES(\"".$Lsql."\",\"".$mysqli->error."\",'Insercion Script')";
                    $mysqli->query($queryCondia);                    
                    echo "Error Haciendo el proceso los registros : " . $mysqli->error;
                }  
            }  
        }
    }
?>
