<?php

use Psy\Util\Json;

    session_start();
    ini_set('display_errors', 'On');
    ini_set('display_errors', 1);
    include(__DIR__."../../../../pages/conexion.php");

    /* una vez creada la tabla procedemos a generar lo que toca generar */
    include(__DIR__."../../../../generador/generar_tablas_bd.php");
    include(__DIR__."../../../../global/WSCoreClient.php");
    require_once('../../../../helpers/parameters.php');

    function guardar_auditoria($accion, $superAccion){
        global $mysqli;
        global $BaseDatos;
        global $BaseDatos_systema;
        global $BaseDatos_telefonia;
        global $dyalogo_canales_electronicos;
        global $BaseDatos_general;
        $str_Lsql = "INSERT INTO ".$BaseDatos_systema.".AUACAD (AUACAD_Fecha_____b , AUACAD_Hora______b, AUACAD_Ejecutor__b, AUACAD_TipoAcci__b , AUACAD_SubTipAcc_b, AUACAD_Accion____b , AUACAD_Huesped___b ) VALUES ('".date('Y-m-d H:i:s')."', '".date('Y-m-d H:i:s')."', ".$_SESSION['IDENTIFICACION'].", 'G5', '".$accion."', '".$superAccion."', ".$_SESSION['HUESPED']." );";
        $mysqli->query($str_Lsql);
    }

    function cargueExcel($archivo,$idOpcion){
        global $mysqli;
        global $BaseDatos;
        global $BaseDatos_systema;
        require "../../../carga/Excel.php";
        $name = $archivo['name'];
        $tname = $archivo['tmp_name'];
        ini_set('memory_limit', '1024M');
        ini_set('max_execution_time', 300);

        if ($archivo["type"] == 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet') {
            $objReader = new PHPExcel_Reader_Excel2007();
            $objReader->setReadDataOnly(true);
            $obj_excel = $objReader->load($tname);
        } else if ($archivo["type"] == 'application/vnd.ms-excel') {
            $obj_excel = PHPExcel_IOFactory::load($tname);
        }

        $sheetData = $obj_excel->getActiveSheet()->toArray(null, true, true, true);
        $arr_datos = array();
        $highestColumm = $obj_excel->setActiveSheetIndex(0)->getHighestColumn(); // e.g. "EL"
        $highestRow = $obj_excel->setActiveSheetIndex(0)->getHighestRow();
        $insertado = 0;
        $fallidos_ = 0;
        $arrayFallas = array();
        $x = 0;

        foreach ($sheetData as $index => $value) {
            if ($index > 1) {
                if (!is_null($value['A']) OR ! empty($value['A'])){
                    $opcion=trim($value['A']);
                    $sql=$mysqli->query("INSERT INTO {$BaseDatos_systema}.LISOPC (LISOPC_Nombre____b,LISOPC_ConsInte__OPCION_b) VALUES ('{$opcion}',$idOpcion)");
                    $arrayFallas[$index]="INSERT INTO {$BaseDatos_systema}.LISOPC (LISOPC_Nombre____b,LISOPC_ConsInte__OPCION_b) VALUES ('{$opcion}',$idOpcion)"."<br>";
//                    if(!$sql){
//                        $arrayFallas[$index]=$opcion;    
//                    }
                }
            }
        }
        return $arrayFallas;
    }

    if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
        //Funciones de la carga maestro y eso
        
        //Datos del formulario
        if(isset($_POST['CallDatos'])){
          
            $str_Lsql = "SELECT G5_ConsInte__b, G5_C28 as principal , G5_C28,G5_C29,G5_C30,G5_C31,G5_C59,G5_C318,G5_C319, captcha FROM {$BaseDatos_systema}.G5 WHERE md5(concat('".clave_get."', G5_ConsInte__b)) = '".$_POST['id']."'";
            $result = $mysqli->query($str_Lsql);
            $datos = array();
            $i = 0;

            while($key = $result->fetch_object()){

                $datos[$i]['G5_C28'] = $key->G5_C28;

                $datos[$i]['G5_C29'] = $key->G5_C29;

                $datos[$i]['G5_C30'] = $key->G5_C30;

                $datos[$i]['G5_C31'] = $key->G5_C31;

                $datos[$i]['G5_C59'] = $key->G5_C59;

                $datos[$i]['G5_C319'] = $key->G5_C319;
                
                $datos[$i]['captcha'] = $key->captcha;
      
                $datos[$i]['principal'] = $key->principal;

                $datos[$i]['G5_ConsInte__b'] =md5(clave_get . $key->G5_ConsInte__b);
                
                $datos[$i]['byWs'] = $key->G5_ConsInte__b;

                $datos[$i]['G5_C318'] = $key->G5_C318;

                if(file_exists('/var/wwww/html/crm_php/formularios/G'.$key->G5_ConsInte__b.'/index.php')){
                    $datos[$i]['encode'] = base64_encode($key->G5_ConsInte__b);
                }else{
                    $datos[$i]['encode'] = 'false';
                }

                $datos[$i]['encode_preview'] = base64_encode($key->G5_ConsInte__b);

                $i++;
            }
            echo json_encode($datos);

        }

        //Datos de la lista de la izquierda
        if(isset($_POST['CallDatosJson'])){
            $str_Lsql = "SELECT G5_ConsInte__b as id,  G5_C28 as camp1 , G5_C29 as camp2 ";
            $str_Lsql .= " FROM ".$BaseDatos_systema.".G5 WHERE G5_C316 = ".$_SESSION['HUESPED'];
            if($_POST['Busqueda'] != '' && !is_null($_POST['Busqueda'])){
                $str_Lsql .= ' AND (G5_C28  like "%'.$_POST['Busqueda'].'%" ';
                $str_Lsql .= ' OR  G5_C29 like "%'.$_POST['Busqueda'].'%" )';
            }

            if(isset($_GET['tipo'])){
                $str_Lsql .= " AND G5_C29 = ".$_GET['tipo'];
            }


            $str_Lsql .= ' ORDER BY G5_C28 ASC LIMIT 0, 50'; 
            $result = $mysqli->query($str_Lsql);
            $datos = array();
            $i = 0;
            while($key = $result->fetch_object()){
            
                $datos[$i]['camp1'] = $key->camp1;
                $datos[$i]['camp2'] = $key->camp2;
                $datos[$i]['id'] = md5(clave_get . $key->id);
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

        if(isset($_GET['CallDatosCombo_Guion_G5_C31'])){
            $Ysql = 'SELECT   G6_ConsInte__b as id , G6_C39 FROM ".$BaseDatos_systema.".G6';
            $guion = $mysqli->query($Ysql);
            echo '<select class="form-control input-sm"  name="G5_C31" id="G5_C31">';
            echo '<option >TEXTO</option>';
            while($obj = $guion->fetch_object()){
               echo "<option value='".$obj->id."' dinammicos='0'>".($obj->G6_C39)."</option>";
            } 
            echo '</select>';

        }

        if(isset($_GET['CallDatosCombo_Guion_G5_C59'])){
            $Ysql = 'SELECT   G6_ConsInte__b as id , G6_C39 FROM ".$BaseDatos_systema.".G6';
            $guion = $mysqli->query($Ysql);
            echo '<select class="form-control input-sm"  name="G5_C59" id="G5_C59">';
            echo '<option >TEXTO</option>';
            while($obj = $guion->fetch_object()){
               echo "<option value='".$obj->id."' dinammicos='0'>".($obj->G6_C39)."</option>";
            } 
            echo '</select>';

        }

        if(isset($_GET['CallDatosCombo_Guion_G7_C60'])){
            $Ysql = 'SELECT   G5_ConsInte__b as id , G5_C28 FROM '.$BaseDatos_systema.'G5';
            $guion = $mysqli->query($Ysql);
            echo '<select class="form-control input-sm"  name="G7_C60" id="G7_C60">';
            echo '<option >NOMBRE</option>';
            while($obj = $guion->fetch_object()){
               echo "<option value='".$obj->id."' dinammicos='0'>".($obj->G5_C28)."</option>";
            } 
            echo '</select>';

        }

        if(isset($_POST['CallEliminate'])){
            if($_POST['oper'] == 'del'){
                $str_Lsql = "DELETE FROM ".$BaseDatos_systema.".G5 WHERE G5_ConsInte__b = ".$_POST['id'];
                if ($mysqli->query($str_Lsql) === TRUE) {
                    echo "1";
                } else {
                    echo "Error eliminado los registros : " . $mysqli->error;
                }
            }

        }

        if(isset($_POST['callDatosNuevamente'])){

            if(isset($_POST['idioma'])){
                switch ($_POST['idioma']) {
                    case 'en':
                        include(__DIR__."../../../../idiomas/text_en.php");
                        break;

                    case 'es':
                        include(__DIR__."../../../../idiomas/text_es.php");
                        break;

                    default:
                        include(__DIR__."../../../../idiomas/text_en.php");
                        break;
                }
            }

            $inicio = $_POST['inicio'];
            $fin = $_POST['fin'];
            if(isset($_POST['tipo'])){
                $Zsql = "SELECT G5_ConsInte__b as id, G5_C28 as camp1 , G5_C29 as camp2 FROM ".$BaseDatos_systema.".G5 WHERE G5_C29 = ".$_POST['tipo']."  AND G5_C316 = ".$_SESSION['HUESPED']."  ORDER BY G5_C28 ASC LIMIT ".$inicio.", ".$fin;
            }else{
                $Zsql = "SELECT G5_ConsInte__b as id, G5_C28 as camp1 , G5_C29 as camp2 FROM ".$BaseDatos_systema.".G5 WHERE  G5_C316 = ".$_SESSION['HUESPED']." ORDER BY G5_C28 ASC LIMIT ".$inicio.", ".$fin;
            }
            
            //$Zsql = "SELECT  G5_ConsInte__b as id,  G5_C28 as camp1 , G5_C30 as camp2  FROM ".$BaseDatos_systema.".G5   ORDER BY G5_ConsInte__b DESC LIMIT ".$inicio." , ".$fin;
            $result = $mysqli->query($Zsql);
            while($obj = $result->fetch_object()){
                $tipo = $str_Script______g_;
                if($obj->camp2 == 2){
                    $tipo = $str_Guion_______g_;
                }elseif($obj->camp2 == 3){
                    $tipo = $str_Otros_______g_;
                }

                echo "<tr class='CargarDatos' id='".md5(clave_get . $obj->id)."'>
                    <td>
                        <p style='font-size:14px;'><b>".mb_strtoupper($obj->camp1)."</b></p>
                        <p style='font-size:12px; margin-top:-10px;'>".mb_strtoupper($tipo)."</p>
                    </td>
                </tr>";

            } 

        }
        
        //Inserciones o actualizaciones
        if(isset($_POST["oper"]) && isset($_GET['insertarDatosGrilla'])){
            if(isset($_POST['id'])){
                $sqlIdGuion=$mysqli->query("select GUION__ConsInte__b as id from {$BaseDatos_systema}.GUION_ where md5(concat('".clave_get."', GUION__ConsInte__b)) = '".$_POST['id']."'");
                if($sqlIdGuion && $sqlIdGuion->num_rows==1){
                    $sqlIdGuion=$sqlIdGuion->fetch_object();
                    $_POST['id']=$sqlIdGuion->id;
                }
            }
            if (isset($_POST["strFilasTip_t"])) {
                
                    $strSQLDelete_t = "DELETE FROM ".$BaseDatos_systema.".ACCIONTIPI WHERE ACCIONTIPI_ConsInte_GUION__b = ".$_POST["id"];
                    $mysqli->query($strSQLDelete_t);

                    if ($_POST["strFilasTip_t"] != "") {
                        $arrFilasTip_t = explode(",", $_POST["strFilasTip_t"]);


                        $strSQLInsert_t = "INSERT INTO ".$BaseDatos_systema.".ACCIONTIPI (ACCIONTIPI_ConsInte_PREGUN_Tipi_b,ACCIONTIPI_ConsInte_LISOPC_Tipi_b,ACCIONTIPI_ConsInte_PREGUN_Campo_b,ACCIONTIPI_ConsInte_GUION__b,ACCIONTIPI_Requerido_b) VALUES ";

                        $intVerificaRequeridos = 0;

                        foreach ($arrFilasTip_t as $row => $i) {

                            if (is_numeric($_POST["idTip"]) && is_numeric($_POST["tip_".$i]) && is_numeric($_POST["campo_".$i]) && is_numeric($_POST["id"])) {
                                
                                if ($_POST["idTip"] > 0 && $_POST["tip_".$i] > 0 && $_POST["campo_".$i] > 0 && $_POST["id"] > 0) {

                                    $intVerificaRequeridos ++;
                                    $strSQLInsert_t .= "(".$_POST["idTip"].",".$_POST["tip_".$i].",".$_POST["campo_".$i].",".$_POST["id"].",1),"; 

                                }    

                            }   


                        }

                        if ($intVerificaRequeridos > 0) {

                            $strSQLInsert_t = substr($strSQLInsert_t, 0, -1);
                            $mysqli->query($strSQLInsert_t);

                        }
                    }
            
            }

            $posible = true;
            //            if($_POST['oper'] == 'add'){
            //                $validarLsql = "SELECT GUION__ConsInte__b FROM ".$BaseDatos_systema.".GUION_ WHERE GUION__Nombre____b = '".$_POST['G5_C28']."' LIMIT 1";
            //                $res = $mysqli->query($validarLsql);
            //
            //                if($res->num_rows > 0){
            //                    echo json_encode(array('code' => '-2'));
            //                    $posible = false;
            //                }
            //            }    

            if($posible){
                $datosArray = array(
                    'A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z', 
                    'AA', 'AB', 'AC', 'AD', 'AE', 'AF', 'AG', 'AH', 'AI', 'AJ', 'AK', 'AL', 'AM', 'AN', 'AO', 'AP', 'AQ', 'AR', 'AS', 'AT', 'AU', 'AV', 'AW', 'AX', 'AY', 'AZ', 
                    'BA', 'BB', 'BC', 'BD', 'BE', 'BF', 'BG', 'BH', 'BI', 'BJ', 'BK', 'BL', 'BM', 'BN', 'BO', 'BP', 'BQ', 'BR', 'BS', 'BT', 'BU', 'BV', 'BW', 'BX', 'BY', 'BZ', 
                    'CA', 'CB', 'CC', 'CD', 'CE', 'CF', 'CG', 'CH', 'CI', 'CJ', 'CK', 'CL', 'CM', 'CN', 'CO', 'CP', 'CQ', 'CR', 'CS', 'CT', 'CU', 'CV', 'CW', 'CX', 'CY', 'CZ', 
                    'DA', 'DB', 'DC', 'DD', 'DE', 'DF', 'DG', 'DH', 'DI', 'DJ', 'DK', 'DL', 'DM', 'DN', 'DO', 'DP', 'DQ', 'DR', 'DS', 'DT', 'DU', 'DV', 'DW', 'DX', 'DY', 'DZ', 
                    'EA', 'EB', 'EC', 'ED', 'EE', 'EF', 'EG', 'EH', 'EI', 'EJ', 'EK', 'EL', 'EM', 'EN', 'EO', 'EP', 'EQ', 'ER', 'ES', 'ET', 'EU', 'EV', 'EW', 'EX', 'EY', 'EZ');


                $str_Lsql  = '';

                $validar = 0;
                $str_LsqlU = "UPDATE ".$BaseDatos_systema.".G5 SET "; 
                $str_LsqlI = "INSERT INTO ".$BaseDatos_systema.".G5(";
                $str_LsqlV = " VALUES ("; 
                
      
                if(isset($_POST["G5_C28"])){
                    $separador = "";
                    if($validar == 1){
                        $separador = ",";
                    }

                    $str_LsqlU .= $separador."G5_C28 = '".$_POST["G5_C28"]."'";
                    $str_LsqlI .= $separador."G5_C28";
                    $str_LsqlV .= $separador."'".$_POST["G5_C28"]."'";
                    $validar = 1;
                }
                 
      
                if(isset($_POST["G5_C29"])){
                    $separador = "";
                    if($validar == 1){
                        $separador = ",";
                    }

                    $str_LsqlU .= $separador."G5_C29 = '".$_POST["G5_C29"]."'";
                    $str_LsqlI .= $separador."G5_C29";
                    $str_LsqlV .= $separador."'".$_POST["G5_C29"]."'";
                    $validar = 1;
                }
                 
      
                if(isset($_POST["G5_C30"])){
                    $separador = "";
                    if($validar == 1){
                        $separador = ",";
                    }

                    $str_LsqlU .= $separador."G5_C30 = '".$_POST["G5_C30"]."'";
                    $str_LsqlI .= $separador."G5_C30";
                    $str_LsqlV .= $separador."'".$_POST["G5_C30"]."'";
                    $validar = 1;
                }
                 
      
                if(isset($_POST["G5_C31"])){
                    $separador = "";
                    if($validar == 1){
                        $separador = ",";
                    }

                    $str_LsqlU .= $separador."G5_C31 = '".$_POST["G5_C31"]."'";
                    $str_LsqlI .= $separador."G5_C31";
                    $str_LsqlV .= $separador."'".$_POST["G5_C31"]."'";
                    $validar = 1;
                }
                 
      
                if(isset($_POST["G5_C59"])){
                    $separador = "";
                    if($validar == 1){
                        $separador = ",";
                    }

                    $str_LsqlU .= $separador."G5_C59 = '".$_POST["G5_C59"]."'";
                    $str_LsqlI .= $separador."G5_C59";
                    $str_LsqlV .= $separador."'".$_POST["G5_C59"]."'";
                    $validar = 1;
                }

                if(isset($_POST["G5_C319"])){
                    $separador = "";
                    if($validar == 1){
                        $separador = ",";
                    }

                    $str_LsqlU .= $separador."G5_C319 = '".$_POST["G5_C319"]."'";
                    $str_LsqlI .= $separador."G5_C319";
                    $str_LsqlV .= $separador."'".$_POST["G5_C319"]."'";
                    $validar = 1;
                }
                
                if(isset($_POST["captcha"]) && $_POST['captcha'] !='' && $_POST['captcha'] !='undefined'){
                    $separador = "";
                    if($validar == 1){
                        $separador = ",";
                    }

                    $str_LsqlU .= $separador."captcha = '".$_POST["captcha"]."'";
                    $str_LsqlI .= $separador."captcha";
                    $str_LsqlV .= $separador."'".$_POST["captcha"]."'";
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
                        $valorG = "G5_C";
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

                    $str_LsqlU .= $separador."G5_IdLlamada = '".$_GET['id_gestion_cbx']."'";
                    $str_LsqlI .= $separador."G5_IdLlamada";
                    $str_LsqlV .= $separador."'".$_GET['id_gestion_cbx']."'";
                    $validar = 1;
                }



                if(isset($_POST['oper'])){
                    if($_POST["oper"] == 'add' ){
                        $str_Lsql = $str_LsqlI.", G5_C316)" . $str_LsqlV.", ".$_POST['G5_C316'].")";
                    }else if($_POST["oper"] == 'edit' ){
                        $str_Lsql = $str_LsqlU.", G5_C316 =  ".$_POST['G5_C316']." WHERE G5_ConsInte__b =".$_POST["id"]; 
                    }else if($_POST["oper"] == 'del' ){

                        $str_LsqlSecciones = "DELETE FROM ".$BaseDatos_systema.".SECCIO WHERE SECCIO_ConsInte__GUION__b = ".$_POST['id'];
                        $mysqli->query($str_LsqlSecciones);


                        $str_LsqlPregun = "DELETE FROM ".$BaseDatos_systema.".PREGUN WHERE PREGUN_ConsInte__GUION__b ".$_POST['id'];
                        $mysqli->query($str_LsqlPregun);


                        //obtenemos las campañas
                        if(isset($_POST['tipo']) && $_POST['tipo'] == '1'){

                            $str_LsqlPregun = "DELETE FROM ".$BaseDatos_systema.".CAMINC WHERE CAMINC_ConsInte__GUION__Gui_b = ".$_POST['id'];
                            $mysqli->query($str_LsqlPregun);

                            /* toca por el CAMPAN_ConsInte__GUION__Gui_b */
                            $Lsql = "SELECT CAMPAN_ConsInte__b FROM ".$BaseDatos_systema.".CAMPAN WHERE CAMPAN_ConsInte__GUION__Gui_b = ".$_POST['id'];
                            $res = $mysqli->query($Lsql);
                            while ($key = $res->fetch_object()){
                                
                            }

                           
                        }

                        if (isset($_POST['tipo']) && $_POST['tipo'] == '2') {
                            /* Toca por el CAMPAN_ConsInte__GUION__Pob_b */
                            $Lsql = "SELECT CAMPAN_ConsInte__b FROM ".$BaseDatos_systema.".CAMPAN WHERE CAMPAN_ConsInte__GUION__Pob_b = ".$_POST['id'];
                            $res = $mysqli->query($Lsql);
                            while ($key = $res->fetch_object()){
                                
                            }

                            $str_LsqlPregun = "DELETE FROM ".$BaseDatos_systema.".CAMINC WHERE CAMINC_ConsInte__GUION__Pob_b = ".$_POST['id'];
                            $mysqli->query($str_LsqlPregun);
                        }

                                            
                        $str_Lsql = "DELETE FROM ".$BaseDatos_systema.".G5 WHERE G5_ConsInte__b = ".$_POST['id'];
                        $validar = 1;       

                    }
                }

                //si trae algo que insertar inserta

                //echo $str_Lsql;
                if($validar == 1){
                    if ($mysqli->query($str_Lsql) === TRUE) {
                        $ultimoGuion = $mysqli->insert_id;
                        if($_POST["oper"] == 'add' ){
                            
                            /* Toca crearle las secciones Ojo si el tipo es tipificación*/
                            if($_POST['G5_C29'] == '1' || $_POST['G5_C29'] == '4'){
                                
                                $Lsql_General = "INSERT INTO ".$BaseDatos_systema.".G7(G7_C60, G7_C38, G7_C36 , G7_C34, G7_C33, G7_C35) VALUES(".$ultimoGuion.", 1, 3, 1, 'GENERAL', 2)";
                                if($mysqli->query($Lsql_General) === true){
                                    
                                    $general = $mysqli->insert_id;
                                    
                                    if(isset($_POST['GenerarFromExel'])){
                                        /* mandaron a generar desde el Excel */
                                        /* lo pirmero es obtener los nombres del Excel y meterlos en general */
                                        require "../../../carga/Excel.php";
                                        if(isset($_FILES['newGuionFile']['tmp_name']) && !empty($_FILES['newGuionFile']['tmp_name'])){

                                            $name   = $_FILES['newGuionFile']['name'];
                                            $tname  = $_FILES['newGuionFile']['tmp_name'];
                                            ini_set('memory_limit','1024M');

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
                                            for($i = 0; $i < count($datasets[0]); $i++){
                                                /* aqui si empezamosa meter datos */
                                                $Lsql_campa_campo = "INSERT INTO ".$BaseDatos_systema.".PREGUN(PREGUN_Texto_____b, PREGUN_Tipo______b, PREGUN_IndiRequ__b, PREGUN_ConsInte__SECCIO_b, PREGUN_ConsInte__GUION__b,   PREGUN_FueGener_b, PREGUN_OrdePreg__b) VALUES ('".$datasets[0][$i]."', 1, 0, ".$general.", ".$ultimoGuion.", 1, ".($i+1).");";
                                                if($mysqli->query($Lsql_campa_campo) === true){
                                                    $lasrt = $mysqli->insert_id;
                                                    $Lsql_Campo = "INSERT INTO ".$BaseDatos_systema.".CAMPO_ (CAMPO__Nombre____b, CAMPO__Tipo______b, CAMPO__ConsInte__PREGUN_b) VALUES ('G".$ultimoGuion."_C".$lasrt."' , 1 , ".$lasrt.")";

                                                        $mysqli->query($Lsql_Campo);

                                                    if($i == 0){
                                                        $primariLsql = "UPDATE ".$BaseDatos_systema.".G5 SET G5_C31 = ". $lasrt." WHERE G5_ConsInte__b = ".$ultimoGuion;
                                                        if($mysqli->query($primariLsql) === true){

                                                        }else{
                                                            echo "Error Insertanndo el principal => ".$mysqli->error;
                                                        }
                                                    }


                                                    if($i == 1){
                                                        $secundariLsql = "UPDATE ".$BaseDatos_systema.".G5 SET G5_C31 = ". $lasrt." WHERE G5_ConsInte__b = ".$ultimoGuion;
                                                        if($mysqli->query($secundariLsql) === true){

                                                        }else{
                                                            echo "Error Insertanndo el principal => ".$mysqli->error;
                                                        }
                                                    }
                                                }
                                            }
                                        }
                                    }




                                }else{
                                    echo "General  ".$mysqli->error;
                                }


                                crearSecciones($ultimoGuion, $_POST['G5_C28'], 1,1);

                            }else if($_POST['G5_C29'] == '2' ){
                                /* seccion generar */
                                

                                $Lsql_General = "INSERT INTO ".$BaseDatos_systema.".G7(G7_C60, G7_C38, G7_C36 , G7_C34, G7_C33, G7_C35) VALUES(".$ultimoGuion.", 1, 3, 1, 'GENERAL', 2)";
                                if($mysqli->query($Lsql_General) === true){
                                    $general = $mysqli->insert_id;

                                    if(isset($_POST['GenerarFromExel'])){
                                        /* mandaron a generar desde el Excel */
                                        /* lo pirmero es obtener los nombres del Excel y meterlos en general */
                                        require "../../../carga/Excel.php";
                                        if(isset($_FILES['newGuionFile']['tmp_name']) && !empty($_FILES['newGuionFile']['tmp_name'])){

                                            $name   = $_FILES['newGuionFile']['name'];
                                            $tname  = $_FILES['newGuionFile']['tmp_name'];
                                            ini_set('memory_limit','1024M');

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
                                }

                                /* Esto es para tener elcontrol de el optin y el origen */               
                                crearSeccionesBD($ultimoGuion, true,2);
                                /* aqui toca cargar la base de datos */
                                $Bdtraducir = $ultimoGuion;
                                if(isset($_POST['CrearScript'])){
                                    /* insertar la base de datos */
                                    $name   = $_FILES['newGuionFile']['name'];
                                    $tname  = $_FILES['newGuionFile']['tmp_name'];
                                    ini_set('memory_limit','1024M');


                                    

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
                                                }else{
                                                    //echo "Error Insertando Los Datos en la base ".$mysqli->error;
                                                }           
                                            }
                                        }
                                    }
                                }


                            }else if($_POST['G5_C29'] == '3'){
                                $Lsql_General = "INSERT INTO ".$BaseDatos_systema.".G7(G7_C60, G7_C38, G7_C36 , G7_C34, G7_C33, G7_C35) VALUES(".$ultimoGuion.", 1, 3, 1, 'GENERAL', 2)";
                                if($mysqli->query($Lsql_General) === true){
                                    $general = $mysqli->insert_id;

                                    $Bdtraducir = $ultimoGuion;
                                    
                                    if(isset($_POST['GenerarFromExel'])){
                                        /* mandaron a generar desde el Excel */
                                        /* lo pirmero es obtener los nombres del Excel y meterlos en general */
                                        require "../../../carga/Excel.php";
                                        if(isset($_FILES['newGuionFile']['tmp_name']) && !empty($_FILES['newGuionFile']['tmp_name'])){

                                            $name   = $_FILES['newGuionFile']['name'];
                                            $tname  = $_FILES['newGuionFile']['tmp_name'];
                                            ini_set('memory_limit','1024M');

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
                                }

                                /* Esto es para tener elcontrol de el optin y el origen */               
                                generar_tablas_bd($ultimoGuion, 1, 1,  0, 0);
                                /* aqui toca cargar la base de datos */
                                $Bdtraducir = $ultimoGuion;
                                if(isset($_POST['CrearScript'])){
                                    /* insertar la base de datos */
                                    $name   = $_FILES['newGuionFile']['name'];
                                    $tname  = $_FILES['newGuionFile']['tmp_name'];
                                    ini_set('memory_limit','1024M');


                                    

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
                                                }else{
                                                    //echo "Error Insertando Los Datos en la base ".$mysqli->error;
                                                }           
                                            }
                                        }
                                    }
                                }
                            }

                            /* auditorias */                                   
                            guardar_auditoria("INSERTAR", "INSERTAR REGISTRO EN G5");
                        }else if($_POST["oper"] == 'edit' ){

                            /*Siempre se va a invocar desde aqui*/

                            if(isset($_POST['txtOrdenData']) && $_POST['txtOrdenData']){
                                //toca empezar a actualizar las secciones
                                $i = 1;
                                $secciones = explode('&', $_POST['txtOrdenData']);
                                foreach ($secciones as $value) {
                                   //echo "JOse => ".$value;
                                    $idSeccion = explode('=' , $value);

                                    $Xsql = "SELECT SECCIO_ConsInte__b FROM ".$BaseDatos_systema.".SECCIO WHERE SECCIO_ConsInte__b = ".$idSeccion[1];
                                    $res = $mysqli->query($Xsql);

                                    if($res->num_rows > 0){
                                        $datoLslq = $res->fetch_array();

                             

                                        //ahora si editamos el id que tenemos capturado
                                        //var_dump($idSeccion);
                                        $UpdateLSql ="UPDATE ".$BaseDatos_systema.".SECCIO SET SECCIO_Orden_____b = ".$i." WHERE SECCIO_ConsInte__b = ".$idSeccion[1];
                                        if($mysqli->query($UpdateLSql) === true){

                                        }else{
                                            echo "No pude editarla Despues =>".$mysqli->error;
                                        }
                                        // "SELE"
                                        // UPDATE [Table] SET [Position] = $i WHERE [EntityId] = $value
                                        $i++;    
                                    }
                                    
                                }
                            }
                            

                            guardar_auditoria("ACTUALIZAR", "ACTUALIZO EL REGISTRO # ".$_POST["id"]." EN G5");
                        }else if($_POST["oper"] == 'del' ){

                            $Lsql_borrar_Tablas = "DROP TABLE G".$_POST['id'];
                            $mysqli->query($Lsql_borrar_Tablas);

                            guardar_auditoria("ELIMINAR", "ELIMINO EL REGISTRO # ".$_POST['id']." EN G5");
                            echo json_encode(array('code' => '0'));
                            exit();
                        }
                        echo json_encode(array('code' => md5(clave_get . $ultimoGuion)));
                    } else {
                        echo json_encode(array('code' => '-2' , 'messaje' => $mysqli->errno));
                        //echo "-1";
                    }
                }
            }  

        }

        /* traer las configuraciones */
        if (isset($_GET['dameConfiguraciones'])) {

            if(isset($_POST['idioma'])){
                switch ($_POST['idioma']) {
                    case 'en':
                        include ('../../../idiomas/text_en.php');
                        break;

                    case 'es':
                        include ('../../../idiomas/text_es.php');
                        break;

                    default:
                        include ('../../../idiomas/text_en.php');
                        break;
                }
            }

            $sqlIdGuion=$mysqli->query("select GUION__ConsInte__b as id from {$BaseDatos_systema}.GUION_ where md5(concat('".clave_get."', GUION__ConsInte__b)) = '".$_POST['guion']."'");
            if($sqlIdGuion && $sqlIdGuion->num_rows==1){
                $sqlIdGuion=$sqlIdGuion->fetch_object();
                $guion_t=$sqlIdGuion->id;
            }
            echo '<div class="panel box box-primary"">
                        <div class="box-header with-border">
                            <h4 class="box-title">
                                '.$Str_ver_form1_guin.' 1
                            </h4>
                            <div class="box-tools pull-right">
                                <a href="http://'.$_SERVER["HTTP_HOST"].'/crm_php/web_forms.php?web='.base64_encode($_POST['guion']).'" target="_blank" type="button" class="btn btn-warning btn-sm" title="'.$Str_ver_forma_guin.'">
                                    <i class="fa fa-link"></i>
                                </a>
                            </div>
                        </div>
                    </div>';

            $Lsql = "SELECT id, titulo, color_fondo, color_letra, url_gracias, codigo_a_insertar, nombre_imagen, tipo_optin, tipo_gracias, id_dy_ce_configuracion, Asunto_mail, Cuerpo_mail FROM ".$BaseDatos_systema.".GUION_WEBFORM WHERE id_guion = ".$guion_t;
            $res = $mysqli->query($Lsql);
            if($res->num_rows > 0){
                $i = 2;

                while ($key = $res->fetch_object()) {
                    echo '<div class="panel box box-primary" id="forma_'.$key->id.'">
                        <div class="box-header with-border">
                            <h4 class="box-title">
                                '.$Str_ver_form1_guin.' '.$i.'
                            </h4>
                            <div class="box-tools pull-right">
                                <a href="http://'.$_SERVER["HTTP_HOST"].'/crm_php/web_forms.php?web='.base64_encode($guion_t).'&v='.$key->id.'" target="_blank" type="button" class="btn btn-warning btn-sm" title="'.$Str_ver_forma_guin.'" >
                                    <i class="fa fa-link"></i>
                                </a>
                                <button type="button" class="btn btn-primary btn-sm btnEditarForma" guion="'.$_POST['guion'].'" formaID="'.$key->id.'" >
                                    <i class="fa fa-edit"></i>
                                </button>
                                <button type="button" class="btn btn-danger btn-sm btnEliminarForma" guion="'.$_POST['guion'].'" formaID="'.$key->id.'" >
                                    <i class="fa fa-trash-o"></i>
                                </button>
                            </div>
                        </div>
                    </div>'; 

                    $i++;
                }
            }

        }

        if(isset($_GET['deleteOptionWeb'])){
            $Lsql = "DELETE FROM ".$BaseDatos_systema.".GUION_WEBFORM WHERE id = ".$_POST['id'];
            $mysqli->query($Lsql);
            echo "1";

        }

        if(isset($_GET["insertarDatosSubgrilla_0"])){
        
            if(isset($_POST["oper"])){
                $str_Lsql  = '';

                $validar = 0;
                $str_LsqlU = "UPDATE ".$BaseDatos_systema.".G7 SET "; 
                $str_LsqlI = "INSERT INTO ".$BaseDatos_systema.".G7(";
                $str_LsqlV = " VALUES ("; 
     
                                                                             
                if(isset($_POST["G7_C33"])){
                    $separador = "";
                    if($validar == 1){
                        $separador = ",";
                    }

                    $str_LsqlU .= $separador."G7_C33 = '".$_POST["G7_C33"]."'";
                    $str_LsqlI .= $separador."G7_C33";
                    $str_LsqlV .= $separador."'".$_POST["G7_C33"]."'";
                    $validar = 1;
                }
                                                                              
                                                                               
     
                $G7_C34= NULL;
                //este es de tipo numero no se deja ir asi '', si est avacio lo mejor es no mandarlo
                if(isset($_POST["G7_C34"])){    
                    if($_POST["G7_C34"] != ''){
                        $separador = "";
                        if($validar == 1){
                            $separador = ",";
                        }

                        $G7_C34 = $_POST["G7_C34"];
                        $str_LsqlU .= $separador." G7_C34 = '".$G7_C34."'";
                        $str_LsqlI .= $separador." G7_C34";
                        $str_LsqlV .= $separador."'".$G7_C34."'";
                        $validar = 1;
                    }
                }
     
                $G7_C35= NULL;
                //este es de tipo numero no se deja ir asi '', si est avacio lo mejor es no mandarlo
                if(isset($_POST["G7_C35"])){    
                    if($_POST["G7_C35"] != ''){
                        $separador = "";
                        if($validar == 1){
                            $separador = ",";
                        }

                        $G7_C35 = $_POST["G7_C35"];
                        $str_LsqlU .= $separador." G7_C35 = '".$G7_C35."'";
                        $str_LsqlI .= $separador." G7_C35";
                        $str_LsqlV .= $separador."'".$G7_C35."'";
                        $validar = 1;
                    }
                }
     
                if(isset($_POST["G7_C36"])){
                    $separador = "";
                    if($validar == 1){
                        $separador = ",";
                    }

                    $str_LsqlU .= $separador."G7_C36 = '".$_POST["G7_C36"]."'";
                    $str_LsqlI .= $separador."G7_C36";
                    $str_LsqlV .= $separador."'".$_POST["G7_C36"]."'";
                    $validar = 1;
                }

                if(isset($_POST["G7_C38"])){
                    $separador = "";
                    if($validar == 1){
                        $separador = ",";
                    }

                    $str_LsqlU .= $separador."G7_C38 = '".$_POST["G7_C38"]."'";
                    $str_LsqlI .= $separador."G7_C38";
                    $str_LsqlV .= $separador."'".$_POST["G7_C38"]."'";
                    $validar = 1;
                }
     
                $G7_C37 = 0;
                //este es tipo check primeo si viene y de acuerdo a su valor se pone 1 o 0
                if(isset($_POST["G7_C37"])){
                    $separador = "";
                    if($validar == 1){
                        $separador = ",";
                    }

                    $str_LsqlU .= $separador." G7_C37 = 0";
                    $str_LsqlI .= $separador." G7_C37";
                    $str_LsqlV .= $separador."0";

                    $validar = 1;
                }else{
                    $separador = "";
                    if($validar == 1){
                        $separador = ",";
                    }
                    $str_LsqlU .= $separador." G7_C37 = 1";
                    $str_LsqlI .= $separador." G7_C37";
                    $str_LsqlV .= $separador."1";
                    $validar = 1;
                }
     
                if(isset($_POST["G7_C38"])){
                    $separador = "";
                    if($validar == 1){
                        $separador = ",";
                    }

                    $str_LsqlU .= $separador."G7_C38 = '1'";
                    $str_LsqlI .= $separador."G7_C38";
                    $str_LsqlV .= $separador."'1'";
                    $validar = 1;
                }

                if(isset($_POST["padre"])){
                    if($_POST["padre"] != ''){
                        //esto es porque el padre es el entero
                        
                        $numero = $_POST["padre"];
             

                        $G7_C60 = $numero;
                        $str_LsqlU .= ", G7_C60 = ".$G7_C60."";
                        $str_LsqlI .= ", G7_C60";
                        $str_LsqlV .= ",".$_POST["padre"];
                    }
                }  



                if(isset($_POST['oper'])){
                    if($_POST["oper"] == 'add' ){
                        $str_Lsql = $str_LsqlI.")" . $str_LsqlV.")";
                    }else if($_POST["oper"] == 'edit' ){
                        $str_Lsql = $str_LsqlU." WHERE G7_ConsInte__b =".$_POST["id"]; 
                    }else if($_POST['oper'] == 'del'){
                        $str_Lsql = "DELETE FROM  ".$BaseDatos_systema.".G7 WHERE  G7_ConsInte__b = ".$_POST['id'];
                        $validar = 1;
                    }
                }

                if($validar == 1){
                    // echo $str_Lsql;
                    if ($mysqli->query($str_Lsql) === TRUE) {
                        echo $mysqli->insert_id;
                        if($_POST["oper"] == 'add' ){
                           guardar_auditoria("INSERTAR", "INSERTAR REGISTRO EN G7");
                        }else if($_POST["oper"] == 'edit' ){
                           guardar_auditoria("ACTUALIZAR", "ACTUALIZO EL REGISTRO # ".$_POST["padre"]." EN G7");
                        }else if($_POST["oper"] == 'del' ){
                           guardar_auditoria("ELIMINAR", "ELIMINO EL REGISTRO # ".$_POST['id']." EN G7");
                        }
                    } else {
                        echo "Error Hacieno el proceso los registros : " . $mysqli->error;
                    }  
                }  
            }

        }
                                        
        if(isset($_GET["callDatosSubgrilla_0"])){

            $id = $_GET['id'];  
            $numero = $id;
            $SQL = "SELECT G7_ConsInte__b, G7_C33, G7_C34, G7_C35, G7_C36, G7_C37, CASE G7_C38 WHEN 1 THEN 'NORMAL' WHEN 2 THEN 'SOLO TITULO' WHEN 3 THEN 'TITULO Y BORDE' WHEN 4 THEN 'ACORDEON' WHEN 5 THEN 'PESTAÑA' END AS G7_C38 FROM ".$BaseDatos_systema.".G7 ";
            $SQL .= " WHERE G7_C60 = ".$numero." AND G7_C38 = 1 "; 
            $SQL .= " ORDER BY G7_C34 ASC";

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
                echo "<row asin='".$fila->G7_ConsInte__b."'>"; 
                echo "<cell>". ($fila->G7_ConsInte__b)."</cell>"; 
                

                echo "<cell>". ($fila->G7_C33)."</cell>";

                echo "<cell>". $fila->G7_C34."</cell>"; 

                echo "<cell>". $fila->G7_C35."</cell>";  

                echo "<cell>". ($fila->G7_C38)."</cell>";
                echo "</row>"; 
            } 
            echo "</rows>"; 

        }

        if(isset($_GET["callDatosSubgrilla_Preguns"])){

            $id = $_GET['seccion_id'];  
            $numero = $id;
                
            $SQL = "SELECT G6_ConsInte__b, G6_C39, G6_C51, G6_C40, G6_C41 FROM ".$BaseDatos_systema.".G6 ";

            $SQL .= " WHERE G6_C32 = ".$numero;     
            $SQL .= " AND G6_C209 != 3 ORDER BY G6_C317 ASC";

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
                echo "<row asin='".$fila->G6_ConsInte__b."'>"; 
                echo "<cell>". ($fila->G6_ConsInte__b)."</cell>"; 
                echo "<cell>". ($fila->G6_C39)."</cell>";
                echo "</row>"; 
            } 
            echo "</rows>"; 

        }
        
        //Guardamos el tipo de busqueda manual
        if(isset($_POST['guardartipobusqueda'])){
            $sqlIdGuion=$mysqli->query("select GUION__ConsInte__b as id from {$BaseDatos_systema}.GUION_ where md5(concat('".clave_get."', GUION__ConsInte__b)) = '".$_POST['id']."'");
            if($sqlIdGuion && $sqlIdGuion->num_rows==1){
                $sqlIdGuion=$sqlIdGuion->fetch_object();
                $_POST['id']=$sqlIdGuion->id;
            }
            if($_POST['guardartipobusqueda'] == 'NO'){
                $strSQLTipoBusMa="SELECT GUION__TipoBusqu__Manual_b FROM {$BaseDatos_systema}.GUION_ WHERE GUION__ConsInte__b={$_POST['id']}";
                $strSQLTipoBusMa=$mysqli->query($strSQLTipoBusMa);
                if($strSQLTipoBusMa->num_rows>0){
                    $strSQLTipoBusMa=$strSQLTipoBusMa->fetch_object();
                    echo $strSQLTipoBusMa->GUION__TipoBusqu__Manual_b;
                }
            }else{
                $strSQLTipoBusMa="UPDATE {$BaseDatos_systema}.GUION_ SET GUION__TipoBusqu__Manual_b={$_POST['tipobusqueda']} WHERE GUION__ConsInte__b={$_POST['id']}";
                $strSQLTipoBusMa=$mysqli->query($strSQLTipoBusMa);
            }
        }


        if(isset($_GET['generarVista'])){
            $sqlIdGuion=$mysqli->query("select GUION__ConsInte__b as id from {$BaseDatos_systema}.GUION_ where md5(concat('".clave_get."', GUION__ConsInte__b)) = '".$_POST['id']."'");
            if($sqlIdGuion && $sqlIdGuion->num_rows==1){
                $sqlIdGuion=$sqlIdGuion->fetch_object();
                $_POST['id']=$sqlIdGuion->id;
            }
    
            echo generarVistasUnicas(2, $_SESSION["HUESPED"] ,null, $_POST['id']);
            
        }

        /* generar la tabla en la base de datos */
        if(isset($_GET['generarGuion']) && isset($_POST['generar'])){
            $sqlIdGuion=$mysqli->query("select GUION__ConsInte__b as id from {$BaseDatos_systema}.GUION_ where md5(concat('".clave_get."', GUION__ConsInte__b)) = '".$_POST['id']."'");
            if($sqlIdGuion && $sqlIdGuion->num_rows==1){
                $sqlIdGuion=$sqlIdGuion->fetch_object();
                $_POST['id']=$sqlIdGuion->id;
            }
            /* validamos que existe la tabla */
            $generar_tabla = 0;
            $generar_formulario = 0;
            $generar_busqueda = 0;
            $generar_web_form = 0;

            if(isset($_POST['checkGenerar'])){
                $generar_tabla = 1;
            }

            if(isset($_POST['checkFormBackoffice'])){
                $generar_formulario = 1;
            }

            if(isset($_POST['checkBusqueda'])){
                $generar_busqueda = 1;
                if(isset($_POST['TipoBusqManual'])){
                    $intTipoBusqMano=$_POST['TipoBusqManual'];
                }
            }

            if(isset($_POST['checkWebForm'])){
                $generar_web_form = 1;
            }

            $response=generar_tablas_bd($_POST['id'], $generar_tabla ,$generar_formulario, $generar_busqueda, $generar_web_form, 0, true);
            generarVistasPorHuesped($_POST['id']);

            $estado='ok';
            $alertas='';
            if(count($response) > 0){
                $estado="alerta";
                $alertas=$response;
            }
            echo json_encode(array("estado"=>$estado,"alertas"=>$response));
        }

        /* insertar las listas en la base de datos */
        if(isset($_GET["insertarDatosLista"])){
            $sqlIdGuion=$mysqli->query("select GUION__ConsInte__b as id from {$BaseDatos_systema}.GUION_ where md5(concat('".clave_get."', GUION__ConsInte__b)) = '".$_POST['idGuion']."'");
            if($sqlIdGuion && $sqlIdGuion->num_rows==1){
                $sqlIdGuion=$sqlIdGuion->fetch_object();
                $_POST['idGuion']=$sqlIdGuion->id;
            }
            if($_POST['operLista'] == 'add'){
                
                $insertLsql = "INSERT INTO ".$BaseDatos_systema.".OPCION (OPCION_ConsInte__GUION__b, OPCION_Nombre____b, OPCION_ConsInte__PROYEC_b, OPCION_FechCrea__b, OPCION_UsuaCrea__b) VALUES (".$_POST['idGuion'].", '".$_POST['txtNombreLista']."', ".$_SESSION['HUESPED'].", '".date('Y-m-d H:s:i')."', ".$_SESSION['IDENTIFICACION'].");";
                
                if($mysqli->query($insertLsql) === true){
                    /* Se inserto la lista perfectamente */
                    $ultimoLista = $mysqli->insert_id;
                    /* procedemos a insertar las opciones de la lista */
                    $tipificacion = false;
                    if(isset($_POST['checkTipificacion'])){
                        $tipificacion = true;
                    }
                    $correcto = 0;
                    $cuantoshay = 0;
                    
                    if(isset($_POST['arExcel']) && !is_null($_POST['arExcel'])){
                        $archivo=$_POST['arExcel'];
                        $res=cargueExcel($archivo,$ultimoLista);
                        echo json_encode($res);
                    }
                    
                    if(isset($_POST['contador'])){
                        $i = 0;
                        for ($i = 0; $i < $_POST['contador']; $i++) {
                            
                            if(!$tipificacion){
                                /* solo lo metemos a LISOP */
                                if(isset($_POST['opciones_'.$i])){
                                    $cuantoshay++;
                                    if($_POST['TipoListas'] == '13'){
                                        $insertLisopc = "INSERT INTO ".$BaseDatos_systema.".LISOPC (LISOPC_Nombre____b, LISOPC_ConsInte__OPCION_b, LISOPC_Posicion__b, LISOPC_Respuesta_b ) VALUES ('".$_POST['opciones_'.$i]."', ".$ultimoLista.", 0, '".$_POST['Respuesta_'.$i]."');";
                                    
                                    }else{
                                        if(isset($_POST['OpcionPadre_'.$i]) && $_POST['OpcionPadre_'.$i] != 0 ){

                                            $insertLisopc = "INSERT INTO ".$BaseDatos_systema.".LISOPC (LISOPC_Nombre____b, LISOPC_ConsInte__OPCION_b, LISOPC_Posicion__b, LISOPC_ConsInte__LISOPC_Depende_b ) VALUES ('".$_POST['opciones_'.$i]."', ".$ultimoLista.", 0, '".$_POST['OpcionPadre_'.$i]."');";

                                        }else{

                                            $insertLisopc = "INSERT INTO ".$BaseDatos_systema.".LISOPC (LISOPC_Nombre____b, LISOPC_ConsInte__OPCION_b, LISOPC_Posicion__b) VALUES ('".$_POST['opciones_'.$i]."', ".$ultimoLista.", 0);";

                                        }
                                    }

                                    
                                    if($mysqli->query($insertLisopc) === true){
                                        $correcto++;  
                                    }else{
                                        echo $mysqli->error;
                                    }
                                }
                            }
                        }

                        if($correcto == $cuantoshay){
                           echo $ultimoLista;
                        }else{
                            echo "0";
                        }
                    }
                }else{
                    echo $mysqli->error;
                    echo 0;
                }
                
            }else{

                $insertLsql = "UPDATE ".$BaseDatos_systema.".OPCION SET OPCION_ConsInte__GUION__b = ".$_POST['idGuion'].", OPCION_Nombre____b = '".$_POST['txtNombreLista']."' WHERE OPCION_ConsInte__b = ".$_POST['idListaE'];
                if($mysqli->query($insertLsql) === true){
                    /* Se inserto la lista perfectamente */
                    $ultimoLista = $_POST['idListaE'];
                    /* procedemos a insertar las opciones de la lista */
                    if(isset($_FILES['arExcel']) && isset($_POST['listExcel'])){
                        $archivo=$_FILES['arExcel'];
                        $res=cargueExcel($archivo,$ultimoLista);
                        echo json_encode($res);
                    }
                    if(isset($_POST['contadorEditables'])){
                        for($i = 0; $i < $_POST['contadorEditables']; $i++){ 
                            //($_POST['opcionesEditar'] as $key) {
                            if(isset($_POST['opcionesEditar_'.$i])){
                               /* solo lo metemos a LISOP */
                                if($_POST['TipoListas'] == '13'){
                                    $insertLisopc = "UPDATE ".$BaseDatos_systema.".LISOPC SET LISOPC_Nombre____b = '".$_POST['opcionesEditar_'.$i]."' , LISOPC_Respuesta_b = '".$_POST['Respuesta_'.$i]."' WHERE LISOPC_ConsInte__b = ".$_POST['hidIdOpcion_'.$i];
                                }else{
                                    if(isset($_POST['OpcionPadreX_'.$i]) && $_POST['OpcionPadreX_'.$i] != 0 ){

                                        $insertLisopc = "UPDATE ".$BaseDatos_systema.".LISOPC SET  LISOPC_Nombre____b = '".$_POST['opcionesEditar_'.$i]."' , LISOPC_ConsInte__LISOPC_Depende_b = ".$_POST['OpcionPadreX_'.$i]." WHERE LISOPC_ConsInte__b = ".$_POST['hidIdOpcion_'.$i];

                                    }else{

                                        $insertLisopc = "UPDATE ".$BaseDatos_systema.".LISOPC SET  LISOPC_Nombre____b = '".$_POST['opcionesEditar_'.$i]."' WHERE LISOPC_ConsInte__b = ".$_POST['hidIdOpcion_'.$i];
                                    
                                    }
                                    
                                }
                                
                                if($mysqli->query($insertLisopc) === true){
                                    //$correcto++;  
                                }else{
                                    echo $mysqli->error;
                                } 
                            }                            
                        }
                    }

                    $correcto = 0;
                    $cuantoshay = 0;
                    if(isset($_POST['contador'])){
                        $i = 0;
                        for ($i = 0; $i < $_POST['contador']; $i++) {
                            
                           
                            /* solo lo metemos a LISOP */
                            if(isset($_POST['opciones_'.$i])){
                                $cuantoshay++;
                                
                                if($_POST['TipoListas'] == '13'){
                                
                                    $insertLisopc = "INSERT INTO ".$BaseDatos_systema.".LISOPC (LISOPC_Nombre____b, LISOPC_ConsInte__OPCION_b, LISOPC_Posicion__b, LISOPC_Respuesta_b ) VALUES ('".$_POST['opciones_'.$i]."', ".$ultimoLista.", 0, '".$_POST['Respuesta_'.$i]."');";
                                
                                }else{
                                    if(isset($_POST['OpcionPadre_'.$i]) && $_POST['OpcionPadre_'.$i] != 0 ){

                                        $insertLisopc = "INSERT INTO ".$BaseDatos_systema.".LISOPC (LISOPC_Nombre____b, LISOPC_ConsInte__OPCION_b, LISOPC_Posicion__b, LISOPC_ConsInte__LISOPC_Depende_b ) VALUES ('".$_POST['opciones_'.$i]."', ".$ultimoLista.", 0, '".$_POST['OpcionPadre_'.$i]."');";

                                    }else{

                                        $insertLisopc = "INSERT INTO ".$BaseDatos_systema.".LISOPC (LISOPC_Nombre____b, LISOPC_ConsInte__OPCION_b, LISOPC_Posicion__b) VALUES ('".$_POST['opciones_'.$i]."', ".$ultimoLista.", 0);";

                                    }
                                }

                                if($mysqli->query($insertLisopc) === true){
                                    $correcto++;  
                                }else{
                                    echo $mysqli->error;
                                }
                            }
                            
                        }

                        if($correcto == $cuantoshay){
                           echo $ultimoLista;
                        }else{
                            echo "0";
                        }
                    }
                }else{
                    echo 0;
                }
            }
            
        }

        if(isset($_POST['editarListasPregunta'])){
            /* procedemos a insertar las opciones de la lista */
            if(isset($_POST['contador'])){
                $contador = $_POST['contador'] + 1;
                for($i = 0; $i < $contador; $i++){ 
                    //($_POST['opcionesEditar'] as $key) {
                    if(isset($_POST['hidIdOpcion_'.$i])){
                       /* solo lo metemos a LISOP */
                        $insertLisopc = "UPDATE ".$BaseDatos_systema.".LISOPC SET  LISOPC_Respuesta_b = '".$_POST['Respuesta_'.$i]."' WHERE LISOPC_ConsInte__b = ".$_POST['hidIdOpcion_'.$i];
                        if($mysqli->query($insertLisopc) === true){
                            //$correcto++;  
                        }else{
                            echo $mysqli->error;
                        } 
                    }                            
                }
            }

        }

        if(isset($_POST['getListasDelSistema'])){
            $str_Lsql = "SELECT  OPCION_ConsInte__b as id , OPCION_Nombre____b as G8_C45 FROM ".$BaseDatos_systema.".OPCION WHERE OPCION_ConsInte__PROYEC_b = ".$_SESSION['HUESPED']." ORDER BY OPCION_Nombre____b ASC;";
            echo '<option value="0">Seleccione</option>';
            $combo = $mysqli->query($str_Lsql);
            while($obj = $combo->fetch_object()){
                echo "<option value='".$obj->id."' dinammicos='0'>".($obj->G8_C45)."</option>";
            } 

        }


        if(isset($_POST['getGuionesDelSistema'])){
            $str_Lsql = "SELECT  G5_ConsInte__b as id , G5_C28 FROM ".$BaseDatos_systema.".G5 WHERE G5_C316 = ".$_SESSION['HUESPED']." ORDER BY G5_C28 ASC;";
            echo '<option value="0">Seleccione</option>';
            $combo = $mysqli->query($str_Lsql);
            while($obj = $combo->fetch_object()){
                echo "<option value='".$obj->id."' dinammicos='0'>".($obj->G5_C28)."</option>";
            } 

        }

        if(isset($_POST['getFormsDelSistema'])){
            $str_Lsql = "SELECT G5_ConsInte__b as id,  G5_C28 FROM ".$BaseDatos_systema.".G5 WHERE G5_C29 = ".$_GET['tipo']." AND G5_C316 = ".$_SESSION['HUESPED']." ORDER BY G5_C28 ASC";

            echo '<option value="0">Seleccione</option>';
            $combo = $mysqli->query($str_Lsql);
            while($obj = $combo->fetch_object()){
                echo "<option value='".$obj->id."' dinammicos='0'>".($obj->G5_C28)."</option>";
            } 

        }

        if(isset($_GET["callDatosSecciones"])){

            $id = $_POST['id'];
            $byModulo=isset($_POST['byModulo']) ? $_POST['byModulo'] : 0;
            $numero = $id;
            $SQL = "SELECT G7_ConsInte__b, G7_C33, G7_C34, G7_C35, G7_C36, G7_C37, G7_C38 FROM ".$BaseDatos_systema.".G7 ";
            $SQL .= " WHERE md5(concat('".clave_get."', G7_C60)) = '".$numero."' AND (G7_C38 =1 || G7_C38 =2 || G7_C38 =5) "; 
            $SQL .= " ORDER BY G7_C34 ASC";
            $result = $mysqli->query($SQL);
            $i = 0;
            $html='';
            while( $fila = $result->fetch_object() ) {
                $html.= '<div class="panel box box-primary" id="seccion_'.$fila->G7_ConsInte__b.'">
                        <div class="box-header with-border">
                            <h4 class="box-title">
                                <i class="fa fa-arrows-v"></i>&nbsp;'.$fila->G7_C33.'
                            </h4>
                            <div class="box-tools pull-right">
                                <button type="button" class="btn btn-primary btn-sm btnEditarSeccion" valorSeccion="'.$fila->G7_ConsInte__b.'" >
                                    <i class="fa fa-edit"></i>
                                </button>';
                if($byModulo == 0){
                    $html.='    <button type="button" class="btn btn-danger btn-sm btnEliminarSeccion" valorSeccion="'.$fila->G7_ConsInte__b.'" >
                                    <i class="fa fa-trash-o"></i>
                                </button>';
                }
                $html.='    </div>
                </div>
                </div>';
            }
            
            $html.="<input type='hidden' id='byModulo' value='{$byModulo}'>";
            echo $html;

        }

        if(isset($_POST['getDatosSeccionesEdicion'])){
            $id = $_POST['id'];
            $SQL = "SELECT SECCIO_Nombre____b, SECCIO_VistPest__b, SECCIO_NumColumnas_b, SECCIO_PestMini__b, SECCIO_TipoSecc__b FROM ".$BaseDatos_systema.".SECCIO WHERE SECCIO_ConsInte__b = ".$id; 
            $res = $mysqli->query($SQL);
            if($res->num_rows > 0){
                $datoSeccion = $res->fetch_array();

                $Lsql = "SELECT PREGUN_Texto_____b, PREGUN_ConsInte__b, PREGUN_IndiBusc__b, PREGUN_ConsInte__OPCION_B, PREGUN_Tipo______b , PREGUN_Agrupar_b as G6_C50, PREGUN_DepColGui_b AS G6_C49, PREGUN_DepPadre_b AS G6_C48, DATE(PREGUN_FechMaxi__b) AS G6_C56, DATE(PREGUN_FechMini__b) AS G6_C55, TIME(PREGUN_HoraMaxi__b) AS G6_C58, TIME(PREGUN_HoraMini__b) AS G6_C57, PREGUN_IndiEncr__b AS G6_C42, PREGUN_IndiRequ__b AS G6_C51, PREGUN_IndiUnic__b AS G6_C52, PREGUN_NumeMaxi__b AS G6_C54, PREGUN_NumeMini__b AS G6_C53, PREGUN_PermiteAdicion_b AS G6_C46, PREGUN_Codigo____b AS G6_C208, PREGUN_FueGener_b AS G6_C209, PREGUN_OrdePreg__b AS G6_C317 , PREGUN_ConsInte__GUION__PRE_B as G6_C43, PREGUN_ConsInte_PREGUN_Depende_b, PREGUN_Default___b as G6_C318, PREGUN_DefaNume__b as G6_C319, PREGUN_ContAcce__b as G6_C320 , PREGUN_OperEntreCamp_____b as G6_C321, PREGUN_WebForm_b as G6_C322, PREGUN_DefaText__b as G6_C323, PREGUN_DefCanFec_b as G6_C324, PREGUN_DefUniFec_b as G6_C325, PREGUN_TipoTel_b as G6_C326, PREGUN_SendMail_b as G6_C327, PREGUN_SendSMS_b as G6_C328, PREGUN_idCuentaMail_b as G6_C329, PREGUN_IdProveedorSms_b as G6_C330, PREGUN_textSMS_b as G6_C331, PREGUN_PrefijoSms_b as G6_C334, PREGUN_SearchMail_b as G6_C332, PREGUN_consInte__ws_B as G6_C335, PREGUN_FormaIntegrarWS_b as G6_C336, PREGUN_Formato_b AS G6_C337, PREGUN_PosDecimales_b AS G6_C338, PREGUN_Longitud__b AS G6_C339 FROM ".$BaseDatos_systema.".PREGUN WHERE PREGUN_ConsInte__SECCIO_b = ".$_POST['id']." AND PREGUN_FueGener_b <> 3 ORDER BY PREGUN_OrdePreg__b ASC";

                $res_L = $mysqli->query($Lsql);
                $datosArray = array();
                $i = 0;
                while ($key = $res_L->fetch_object()) {

                    $datosArray[$i]['PREGUN_Texto_____b'] = $key->PREGUN_Texto_____b;
                    $datosArray[$i]['PREGUN_ConsInte__b'] = $key->PREGUN_ConsInte__b;
                    $datosArray[$i]['PREGUN_IndiBusc__b'] = $key->PREGUN_IndiBusc__b;
                    $datosArray[$i]['PREGUN_ConsInte__OPCION_B'] = $key->PREGUN_ConsInte__OPCION_B;
                    $datosArray[$i]['PREGUN_Tipo______b'] = $key->PREGUN_Tipo______b;
                    $datosArray[$i]['G6_C50'] = $key->G6_C50;
                    $datosArray[$i]['G6_C49'] = $key->G6_C49;
                    $datosArray[$i]['G6_C48'] = $key->G6_C48;
                    $datosArray[$i]['G6_C56'] = $key->G6_C56;
                    $datosArray[$i]['G6_C55'] = $key->G6_C55;
                    $datosArray[$i]['G6_C58'] = $key->G6_C58;
                    $datosArray[$i]['G6_C57'] = $key->G6_C57;
                    $datosArray[$i]['G6_C51'] = $key->G6_C51;
                    $datosArray[$i]['G6_C52'] = $key->G6_C52;
                    $datosArray[$i]['G6_C54'] = $key->G6_C54;
                    $datosArray[$i]['G6_C53'] = $key->G6_C53;
                    $datosArray[$i]['G6_C46'] = $key->G6_C46;
                    $datosArray[$i]['G6_C208'] = $key->G6_C208;
                    $datosArray[$i]['G6_C209'] = $key->G6_C209;
                    $datosArray[$i]['G6_C317'] = $key->G6_C317;
                    $datosArray[$i]['G6_C43'] = $key->G6_C43;
                    $datosArray[$i]['G6_C42'] = $key->G6_C42;
                    $datosArray[$i]['G6_C318'] = $key->G6_C318;
                    $datosArray[$i]['G6_C319'] = $key->G6_C319;
                    $datosArray[$i]['G6_C320'] = $key->G6_C320;
                    $datosArray[$i]['G6_C321'] = $key->G6_C321;
                    $datosArray[$i]['G6_C322'] = $key->G6_C322;
                    $datosArray[$i]['G6_C323'] = $key->G6_C323;
                    $datosArray[$i]['G6_C324'] = $key->G6_C324;
                    $datosArray[$i]['G6_C325'] = $key->G6_C325;
                    $datosArray[$i]['G6_C326'] = $key->G6_C326;
                    $datosArray[$i]['G6_C327'] = $key->G6_C327;
                    $datosArray[$i]['G6_C328'] = $key->G6_C328;
                    $datosArray[$i]['G6_C329'] = $key->G6_C329;
                    $datosArray[$i]['G6_C330'] = $key->G6_C330;
                    $datosArray[$i]['G6_C331'] = $key->G6_C331;
                    $datosArray[$i]['G6_C332'] = $key->G6_C332;
                    $datosArray[$i]['G6_C334'] = $key->G6_C334;
                    $datosArray[$i]['G6_C335'] = $key->G6_C335;
                    $datosArray[$i]['G6_C336'] = $key->G6_C336;
                    $datosArray[$i]['G6_C337'] = $key->G6_C337;
                    $datosArray[$i]['G6_C338'] = $key->G6_C338;
                    $datosArray[$i]['G6_C339'] = $key->G6_C339;
                    if($key->PREGUN_Tipo______b=='16'){
                        $datosArray[$i]['G6_C333'] = $key->G6_C43;
                        $datosArray[$i]['G6_C43'] = 0;
                    }
                    $datosArray[$i]['PREGUN_ConsInte_PREGUN_Depende_b'] =  $key->PREGUN_ConsInte_PREGUN_Depende_b;

                    $i++;
                }

                echo json_encode(array('code' => 1, 'sec_nom' => $datoSeccion['SECCIO_Nombre____b'] , "sec_Tip" => $datoSeccion['SECCIO_VistPest__b'], 'sec_tip_sec' => $datoSeccion['SECCIO_TipoSecc__b'] , 'sec_num_col' => $datoSeccion['SECCIO_NumColumnas_b']  , 'sec_mini' => $datoSeccion['SECCIO_PestMini__b'] ,'datosPregun' => $datosArray));

            }else{
                echo json_encode(array('code' => 0));
            }   

        }

        if(isset($_POST['insertarSecciones'])){
            $sqlIdGuion=$mysqli->query("select GUION__ConsInte__b as id from {$BaseDatos_systema}.GUION_ where md5(concat('".clave_get."', GUION__ConsInte__b)) = '".$_POST['padre']."'");
            if($sqlIdGuion && $sqlIdGuion->num_rows==1){
                $sqlIdGuion=$sqlIdGuion->fetch_object();
                $_POST['padre']=$sqlIdGuion->id;
            }
            $validar = 0;
            $str_LsqlI = "INSERT INTO ".$BaseDatos_systema.".G7(";
            $str_LsqlV = " VALUES ("; 
 
                                                                             
            if(isset($_POST["G7_C33"])){
                $separador = "";
                if($validar == 1){
                    $separador = ",";
                }
                $str_LsqlI .= $separador."G7_C33";
                $str_LsqlV .= $separador."'".$_POST["G7_C33"]."'";
                $validar = 1;
            }
                                                                              
                                                                               
     
            $G7_C34= NULL;
            //este es de tipo numero no se deja ir asi '', si est avacio lo mejor es no mandarlo
            if(isset($_POST["G7_C34"])){    
                if($_POST["G7_C34"] != ''){
                    $separador = "";
                    if($validar == 1){
                        $separador = ",";
                    }

                    $G7_C34 = $_POST["G7_C34"];
                    $str_LsqlI .= $separador." G7_C34";
                    $str_LsqlV .= $separador."'".$G7_C34."'";
                    $validar = 1;
                }
            }else{
                $separador = "";
                if($validar == 1){
                    $separador = ",";
                }
                $Lsql = "SELECT MAX(G7_C34) As orden FROM ".$BaseDatos_systema.".G7 WHERE G7_C60 = ".$_POST['padre'];
                $rs = $mysqli->query($Lsql);
                $datoOrde = $rs->fetch_array();
                $orden = $datoOrde['orden'];
                $G7_C34 = $orden+1;
                $str_LsqlI .= $separador." G7_C34";
                $str_LsqlV .= $separador."'".$G7_C34."'";
                $validar = 1;
            }
     
            $G7_C35= NULL;
            //este es de tipo numero no se deja ir asi '', si est avacio lo mejor es no mandarlo
            if(isset($_POST["G7_C35"])){    
                if($_POST["G7_C35"] != ''){
                    $separador = "";
                    if($validar == 1){
                        $separador = ",";
                    }

                    $G7_C35 = $_POST["G7_C35"];
                    $str_LsqlI .= $separador." G7_C35";
                    $str_LsqlV .= $separador."'".$G7_C35."'";
                    $validar = 1;
                }
            }
     
            if(isset($_POST["G7_C36"])){
                $separador = "";
                if($validar == 1){
                    $separador = ",";
                }

                $str_LsqlI .= $separador."G7_C36";
                $str_LsqlV .= $separador."'".$_POST["G7_C36"]."'";
                $validar = 1;
            }
     
            $G7_C37 = 0;
            //este es tipo check primeo si viene y de acuerdo a su valor se pone 1 o 0
            if(isset($_POST["G7_C37"])){
                $separador = "";
                if($validar == 1){
                    $separador = ",";
                }

                $str_LsqlI .= "{$separador} G7_C37";
                $str_LsqlV .= "{$separador} {$_POST['G7_C37']}";

                $validar = 1;
            }else{
                $separador = "";
                if($validar == 1){
                    $separador = ",";
                }
                $str_LsqlI .= $separador." G7_C37";
                $str_LsqlV .= $separador."1";
                $validar = 1;
            }
     
            if(isset($_POST["G7_C38"])){
                $separador = "";
                if($validar == 1){
                    $separador = ",";
                }
                $str_LsqlI .= $separador."G7_C38";
                $str_LsqlV .= $separador."'".$_POST["G7_C38"]."'";
                $validar = 1;
            }

            if(isset($_POST["padre"])){
                if($_POST["padre"] != ''){
                    //esto es porque el padre es el entero
                    
                    $numero = $_POST["padre"];
         

                    $G7_C60 = $numero;
                    $str_LsqlI .= ", G7_C60";
                    $str_LsqlV .= ",".$_POST["padre"];
                }
            }  

    
            $str_Lsql = $str_LsqlI.")" . $str_LsqlV.")";
            
            if($validar == 1){
                //  echo "str_Lsql => $str_Lsql";
                if ($mysqli->query($str_Lsql) === TRUE) {
                    $UltimaSeccion = $mysqli->insert_id;

                    //echo $_POST['contador'];
                    if(isset($_POST['contador']) && $_POST['contador'] != 1){
                        $contador = $_POST['contador'] + 1;
                        for($i = 1; $i < $contador; $i++){
                            if(isset($_POST["G6_C39_".$i])){
                               
                                $str_Lsql  = '';
                                $validar = 0;
                                $str_LsqlU = '';
                                $str_LsqlI = "INSERT INTO ".$BaseDatos_systema.".G6(";
                                $str_LsqlV = " VALUES ("; 
                          
                                
                         
                                if(isset($_POST["G6_C39_".$i]) && $_POST["G6_C39_".$i] != ''){
                                    $separador = "";
                                    if($validar == 1){
                                        $separador = ",";
                                    }

                                    $str_LsqlU .= $separador."G6_C39 = '".$_POST["G6_C39_".$i]."'";
                                    $str_LsqlI .= $separador."G6_C39";
                                    $str_LsqlV .= $separador."'".$_POST["G6_C39_".$i]."'";
                                    $validar = 1;

                                }
                                                                                                  
                                if(isset($_POST["G6_C49_".$i]) && $_POST["G6_C49_".$i] != '0'){
                                    $separador = "";
                                    if($validar == 1){
                                        $separador = ",";
                                    }

                                    $str_LsqlU .= $separador."G6_C49 = '".$_POST["G6_C49_".$i]."'";
                                    $str_LsqlI .= $separador."G6_C49";
                                    $str_LsqlV .= $separador."'".$_POST["G6_C49_".$i]."'";
                                    $validar = 1;

                                }   
                         
                                $G6_C52 = 0;
                                if(isset($_POST["G6_C52_".$i]) && $_POST["G6_C52_".$i] != ''){
                                    if($_POST["G6_C52_".$i] == 'Yes'){
                                        $G6_C52 = 1;
                                    }else if($_POST["G6_C52_".$i] == 'off'){
                                        $G6_C52 = 0;
                                    }else if($_POST["G6_C52_".$i] == 'on'){
                                        $G6_C52 = 1;
                                    }else if($_POST["G6_C52_".$i] == 'No'){
                                        $G6_C52 = 1;
                                    }else{
                                        $G6_C52 = $_POST["G6_C52_".$i] ;
                                    }   

                                    $separador = "";
                                    if($validar == 1){
                                        $separador = ",";
                                    }

                                    $str_LsqlU .= $separador." G6_C52 = ".$G6_C52."";
                                    $str_LsqlI .= $separador." G6_C52";
                                    $str_LsqlV .= $separador.$G6_C52;

                                    $validar = 1;

                                }else{
                                    $separador = "";
                                    if($validar == 1){
                                        $separador = ",";
                                    }

                                    $str_LsqlU .= $separador." G6_C52 = ".$G6_C52."";
                                    $str_LsqlI .= $separador." G6_C52";
                                    $str_LsqlV .= $separador.$G6_C52;

                                    $validar = 1;

                                }
                         
                                if(isset($_POST["G6_C40_".$i]) && $_POST["G6_C40_".$i] != '0'){
                                    $separador = "";
                                    if($validar == 1){
                                        $separador = ",";
                                    }

                                    $str_LsqlU .= $separador."G6_C40 = '".$_POST["G6_C40_".$i]."'";
                                    $str_LsqlI .= $separador."G6_C40";
                                    $str_LsqlV .= $separador."'".$_POST["G6_C40_".$i]."'";
                                    $validar = 1;

                                }
                         
                                $G6_C50 = 0;
                                if(isset($_POST["G6_C50_".$i]) && $_POST["G6_C50_".$i] != ''){
                                    if($_POST["G6_C50_".$i] == 'Yes'){
                                        $G6_C50 = 1;
                                    }else if($_POST["G6_C50_".$i] == 'off'){
                                        $G6_C50 = 0;
                                    }else if($_POST["G6_C50_".$i] == 'on'){
                                        $G6_C50 = 1;
                                    }else if($_POST["G6_C50_".$i] == 'No'){
                                        $G6_C50 = 1;
                                    }else{
                                        $G6_C50 = $_POST["G6_C50_".$i] ;
                                    }   

                                    $separador = "";
                                    if($validar == 1){
                                        $separador = ",";
                                    }

                                    $str_LsqlU .= $separador." G6_C50 = ".$G6_C50."";
                                    $str_LsqlI .= $separador." G6_C50";
                                    $str_LsqlV .= $separador.$G6_C50;

                                    $validar = 1;

                                }else{
                                    $separador = "";
                                    if($validar == 1){
                                        $separador = ",";
                                    }

                                    $str_LsqlU .= $separador." G6_C50 = ".$G6_C50."";
                                    $str_LsqlI .= $separador." G6_C50";
                                    $str_LsqlV .= $separador.$G6_C50;

                                    $validar = 1;

                                }
                         
                                $G6_C53= NULL;
                                if(isset($_POST["G6_C53_".$i]) && $_POST["G6_C53_".$i] != '0'){    
                                    if($_POST["G6_C53_".$i] != ''){
                                        $separador = "";
                                        if($validar == 1){
                                            $separador = ",";
                                        }

                                        $G6_C53 = $_POST["G6_C53_".$i];
                                        $str_LsqlU .= $separador." G6_C53 = '".$G6_C53."'";
                                        $str_LsqlI .= $separador." G6_C53";
                                        $str_LsqlV .= $separador."'".$G6_C53."'";
                                        $validar = 1;
                                    }

                                }
                         
                                $G6_C41 = 0;
                                if(isset($_POST["G6_C41_".$i]) && $_POST["G6_C41_".$i] != ''){
                                    if($_POST["G6_C41_".$i] == 'Yes'){
                                        $G6_C41 = 1;
                                    }else if($_POST["G6_C41_".$i] == 'off'){
                                        $G6_C41 = 0;
                                    }else if($_POST["G6_C41_".$i] == 'on'){
                                        $G6_C41 = 1;
                                    }else if($_POST["G6_C41_".$i] == 'No'){
                                        $G6_C41 = 1;
                                    }else{
                                        $G6_C41 = $_POST["G6_C41_".$i] ;
                                    }   

                                    $separador = "";
                                    if($validar == 1){
                                        $separador = ",";
                                    }

                                    $str_LsqlU .= $separador." G6_C41 = ".$G6_C41."";
                                    $str_LsqlI .= $separador." G6_C41";
                                    $str_LsqlV .= $separador.$G6_C41;

                                    $validar = 1;

                                }else{
                                    $separador = "";
                                    if($validar == 1){
                                        $separador = ",";
                                    }

                                    $str_LsqlU .= $separador." G6_C41 = ".$G6_C41."";
                                    $str_LsqlI .= $separador." G6_C41";
                                    $str_LsqlV .= $separador.$G6_C41;

                                    $validar = 1;

                                }

                                if(isset($_POST["G6_C48_".$i]) && $_POST["G6_C48_".$i] != '0' ){
                                    $separador = "";
                                    if($validar == 1){
                                        $separador = ",";
                                    }

                                    $str_LsqlI .= $separador."G6_C48_".$i;
                                    $str_LsqlV .= $separador."'".$_POST["G6_C48_".$i]."'";
                                    $validar = 1;

                                }
                                    
                                $G6_C51 = 0;
                                //este es tipo check primeo si viene y de acuerdo a su valor se pone 1 o 0
                                if(isset($_POST["G6_C51_".$i]) && $_POST["G6_C51_".$i] != ''){
                                    if($_POST["G6_C51_".$i] == 'Yes'){
                                        $G6_C51 = 1;
                                    }else if($_POST["G6_C51_".$i] == 'off'){
                                        $G6_C51 = 0;
                                    }else if($_POST["G6_C51_".$i] == 'on'){
                                        $G6_C51 = 1;
                                    }else if($_POST["G6_C51_".$i] == 'No'){
                                        $G6_C51 = 1;
                                    }else{
                                        $G6_C51 = $_POST["G6_C51_".$i] ;
                                    }   

                                    $separador = "";
                                    if($validar == 1){
                                        $separador = ",";
                                    }

                                    $str_LsqlU .= $separador." G6_C51 = ".$G6_C51."";
                                    $str_LsqlI .= $separador." G6_C51";
                                    $str_LsqlV .= $separador.$G6_C51;

                                    $validar = 1;

                                }else{
                                    $separador = "";
                                    if($validar == 1){
                                        $separador = ",";
                                    }

                                    $str_LsqlU .= $separador." G6_C51 = ".$G6_C51."";
                                    $str_LsqlI .= $separador." G6_C51";
                                    $str_LsqlV .= $separador.$G6_C51;

                                    $validar = 1;

                                }


                                $G6_C54= NULL;
                                if(isset($_POST["G6_C54_".$i]) && $_POST["G6_C54_".$i] != '0'){    
                                    if($_POST["G6_C54_".$i] != ''){
                                        $separador = "";
                                        if($validar == 1){
                                            $separador = ",";
                                        }

                                        $G6_C54 = $_POST["G6_C54_".$i];
                                        $str_LsqlU .= $separador." G6_C54 = '".$G6_C54."'";
                                        $str_LsqlI .= $separador." G6_C54";
                                        $str_LsqlV .= $separador."'".$G6_C54."'";
                                        $validar = 1;
                                    }

                                }
                         
                                $G6_C42 = 0;
                                if(isset($_POST["G6_C42_".$i]) && $_POST["G6_C42_".$i] != ''){
                                    if($_POST["G6_C42_".$i] == 'Yes'){
                                        $G6_C42 = 1;
                                    }else if($_POST["G6_C42_".$i] == 'off'){
                                        $G6_C42 = 0;
                                    }else if($_POST["G6_C42_".$i] == 'on'){
                                        $G6_C42 = 1;
                                    }else if($_POST["G6_C42_".$i] == 'No'){
                                        $G6_C42 = 1;
                                    }else{
                                        $G6_C42 = $_POST["G6_C42_".$i] ;
                                    }   

                                    $separador = "";
                                    if($validar == 1){
                                        $separador = ",";
                                    }

                                    $str_LsqlU .= $separador." G6_C42 = ".$G6_C42."";
                                    $str_LsqlI .= $separador." G6_C42";
                                    $str_LsqlV .= $separador.$G6_C42;

                                    $validar = 1;

                                }else{
                                    $separador = "";
                                    if($validar == 1){
                                        $separador = ",";
                                    }

                                    $str_LsqlU .= $separador." G6_C42 = ".$G6_C42."";
                                    $str_LsqlI .= $separador." G6_C42";
                                    $str_LsqlV .= $separador.$G6_C42;

                                    $validar = 1;

                                }

                                $G6_C55 = NULL;
                                if(isset($_POST["G6_C55_".$i]) && $_POST["G6_C55_".$i] != '0'){    
                                    if($_POST["G6_C55_".$i] != ''){
                                        $separador = "";
                                        if($validar == 1){
                                            $separador = ",";
                                        }

                                        $G6_C55 = "'".str_replace(' ', '',$_POST["G6_C55_".$i])." 00:00:00'";
                                        $str_LsqlU .= $separador." G6_C55 = ".$G6_C55;
                                        $str_LsqlI .= $separador." G6_C55";
                                        $str_LsqlV .= $separador.$G6_C55;
                                        $validar = 1;
                                    }else{
                                        $separador = "";
                                        if($validar == 1){
                                            $separador = ",";
                                        }
                                        $str_LsqlU .= $separador." G6_C55 = NULL";
                                        $str_LsqlI .= $separador." G6_C55";
                                        $str_LsqlV .= $separador."NULL";
                                        $validar = 1;
                                    }

                                }else{
                                    if(!isset($_POST["G6_C55_".$i])){
                                        $separador = "";
                                        if($validar == 1){
                                            $separador = ",";
                                        }
    
                                        $G6_C55 = "'0001-01-01 00:00:00'";
                                        $str_LsqlU .= $separador." G6_C55 = ".$G6_C55;
                                        $str_LsqlI .= $separador." G6_C55";
                                        $str_LsqlV .= $separador.$G6_C55;
                                        $validar = 1;
                                    }
                                }
                          
                                if(isset($_POST["G6_C43_".$i]) && $_POST["G6_C43_".$i] != '0'){
                                    $separador = "";
                                    if($validar == 1){
                                        $separador = ",";
                                    }

                                    $str_LsqlU .= $separador."G6_C43 = '".$_POST["G6_C43_".$i]."'";
                                    $str_LsqlI .= $separador."G6_C43";
                                    $str_LsqlV .= $separador."'".$_POST["G6_C43_".$i]."'";
                                    $validar = 1;

                                }
                                    

                                $G6_C56 = NULL;
                                if(isset($_POST["G6_C56_".$i]) && $_POST["G6_C56_".$i] != '0'){    
                                    if($_POST["G6_C56_".$i] != ''){
                                        $separador = "";
                                        if($validar == 1){
                                            $separador = ",";
                                        }

                                        $G6_C56 = "'".str_replace(' ', '',$_POST["G6_C56_".$i])." 00:00:00'";
                                        $str_LsqlU .= $separador." G6_C56 = ".$G6_C56;
                                        $str_LsqlI .= $separador." G6_C56";
                                        $str_LsqlV .= $separador.$G6_C56;
                                        $validar = 1;
                                    }

                                }
                                
                                $G6_C44='0';
                                if($G6_C44=='0'){
                                    if(isset($_POST["G6_C44_".$i])){
                                        $G6_C44=$_POST["G6_C44_".$i];
                                    }
                                        $separador = "";
                                        if($validar == 1){
                                            $separador = ",";
                                        }

                                        $str_LsqlU .= $separador."G6_C44 = '".$G6_C44."'";
                                        $str_LsqlI .= $separador."G6_C44";
                                        $str_LsqlV .= $separador."'".$G6_C44."'";
                                        $validar = 1;
                                }
                                
                                $G6_C57 = NULL;
                                if(isset($_POST["G6_C57_".$i]) && $_POST["G6_C57_".$i] != '0'){    
                                    if($_POST["G6_C57_".$i] != '' && $_POST["G6_C57_".$i] != 'undefined' && $_POST["G6_C57_".$i] != 'null'){
                                        $separador = "";
                                        $fecha = date('Y-m-d');
                                        if($validar == 1){
                                            $separador = ",";
                                        }

                                        $G6_C57 = "'".$fecha." ".str_replace(' ', '',$_POST["G6_C57_".$i])."'";
                                        $str_LsqlU .= $separador."  G6_C57 = ".$G6_C57."";
                                        $str_LsqlI .= $separador."  G6_C57";
                                        $str_LsqlV .= $separador.$G6_C57;
                                        $validar = 1;
                                    }

                                }
                         
                                $G6_C46 = 0;
                                if(isset($_POST["G6_C46_".$i]) && $_POST["G6_C46_".$i] != ''){
                                    if($_POST["G6_C46_".$i] == 'Yes'){
                                        $G6_C46 = 1;
                                    }else if($_POST["G6_C46_".$i] == 'off'){
                                        $G6_C46 = 0;
                                    }else if($_POST["G6_C46_".$i] == 'on'){
                                        $G6_C46 = 1;
                                    }else if($_POST["G6_C46_".$i] == 'No'){
                                        $G6_C46 = 1;
                                    }else{
                                        $G6_C46 = $_POST["G6_C46_".$i] ;
                                    }   

                                    $separador = "";
                                    if($validar == 1){
                                        $separador = ",";
                                    }

                                    $str_LsqlU .= $separador." G6_C46 = ".$G6_C46."";
                                    $str_LsqlI .= $separador." G6_C46";
                                    $str_LsqlV .= $separador.$G6_C46;

                                    $validar = 1;

                                }else{
                                    $separador = "";
                                    if($validar == 1){
                                        $separador = ",";
                                    }

                                    $str_LsqlU .= $separador." G6_C46 = ".$G6_C46."";
                                    $str_LsqlI .= $separador." G6_C46";
                                    $str_LsqlV .= $separador.$G6_C46;

                                    $validar = 1;

                                }

                                $G6_C322 = 0;
                                if(isset($_POST["G6_C322_".$i]) && $_POST["G6_C322_".$i] != ''){
                                    if($_POST["G6_C322_".$i] == 'Yes'){
                                        $G6_C322 = 1;
                                    }else if($_POST["G6_C322_".$i] == 'off'){
                                        $G6_C322 = 0;
                                    }else if($_POST["G6_C322_".$i] == 'on'){
                                        $G6_C322 = 1;
                                    }else if($_POST["G6_C322_".$i] == 'No'){
                                        $G6_C322 = 1;
                                    }else{
                                        $G6_C322 = $_POST["G6_C322_".$i] ;
                                    }   

                                    $separador = "";
                                    if($validar == 1){
                                        $separador = ",";
                                    }

                                    $str_LsqlU .= $separador." G6_C322 = ".$G6_C322."";
                                    $str_LsqlI .= $separador." G6_C322";
                                    $str_LsqlV .= $separador.$G6_C322;

                                    $validar = 1;

                                }else{
                                    $separador = "";
                                    if($validar == 1){
                                        $separador = ",";
                                    }

                                    $str_LsqlU .= $separador." G6_C322 = ".$G6_C322."";
                                    $str_LsqlI .= $separador." G6_C322";
                                    $str_LsqlV .= $separador.$G6_C322;

                                    $validar = 1;

                                }
                                
                                $G6_C326 = 0;
                                if(isset($_POST["G6_C326_".$i]) && $_POST["G6_C326_".$i] != ''){
                                    if($_POST["G6_C326_".$i] == 'Yes'){
                                        $G6_C326 = 1;
                                    }else if($_POST["G6_C326_".$i] == 'off'){
                                        $G6_C326 = 0;
                                    }else if($_POST["G6_C326_".$i] == 'on'){
                                        $G6_C326 = 1;
                                    }else if($_POST["G6_C326_".$i] == 'No'){
                                        $G6_C326 = 1;
                                    }else{
                                        $G6_C326 = $_POST["G6_C326_".$i] ;
                                    }   

                                    $separador = "";
                                    if($validar == 1){
                                        $separador = ",";
                                    }

                                    $str_LsqlU .= $separador." G6_C326 = ".$G6_C326."";
                                    $str_LsqlI .= $separador." G6_C326";
                                    $str_LsqlV .= $separador.$G6_C326;

                                    $validar = 1;

                                }else{
                                    $separador = "";
                                    if($validar == 1){
                                        $separador = ",";
                                    }

                                    $str_LsqlU .= $separador." G6_C326= ".$G6_C326."";
                                    $str_LsqlI .= $separador." G6_C326";
                                    $str_LsqlV .= $separador.$G6_C326;

                                    $validar = 1;

                                }
                                
                                
                                $G6_C327 = 0;
                                if(isset($_POST["G6_C327_".$i]) && $_POST["G6_C327_".$i] != ''){
                                    if($_POST["G6_C327_".$i] == 'Yes'){
                                        $G6_C327 = 1;
                                    }else if($_POST["G6_C327_".$i] == 'off'){
                                        $G6_C327 = 0;
                                    }else if($_POST["G6_C327_".$i] == 'on'){
                                        $G6_C327 = 1;
                                    }else if($_POST["G6_C327_".$i] == 'No'){
                                        $G6_C327 = 1;
                                    }else{
                                        $G6_C327 = $_POST["G6_C327_".$i] ;
                                    }   

                                    $separador = "";
                                    if($validar == 1){
                                        $separador = ",";
                                    }

                                    $str_LsqlU .= $separador." G6_C327 = ".$G6_C327."";
                                    $str_LsqlI .= $separador." G6_C327";
                                    $str_LsqlV .= $separador.$G6_C327;

                                    $validar = 1;

                                }else{
                                    $separador = "";
                                    if($validar == 1){
                                        $separador = ",";
                                    }

                                    $str_LsqlU .= $separador." G6_C327= ".$G6_C327."";
                                    $str_LsqlI .= $separador." G6_C327";
                                    $str_LsqlV .= $separador.$G6_C327;

                                    $validar = 1;

                                }
                                
                                $G6_C328 = 0;
                                if(isset($_POST["G6_C328_".$i]) && $_POST["G6_C328_".$i] != ''){
                                    if($_POST["G6_C328_".$i] == 'Yes'){
                                        $G6_C328 = 1;
                                    }else if($_POST["G6_C328_".$i] == 'off'){
                                        $G6_C328 = 0;
                                    }else if($_POST["G6_C328_".$i] == 'on'){
                                        $G6_C328 = 1;
                                    }else if($_POST["G6_C328_".$i] == 'No'){
                                        $G6_C328 = 1;
                                    }else{
                                        $G6_C328 = $_POST["G6_C328_".$i] ;
                                    }   

                                    $separador = "";
                                    if($validar == 1){
                                        $separador = ",";
                                    }

                                    $str_LsqlU .= $separador." G6_C328 = ".$G6_C328."";
                                    $str_LsqlI .= $separador." G6_C328";
                                    $str_LsqlV .= $separador.$G6_C328;

                                    $validar = 1;

                                }else{
                                    $separador = "";
                                    if($validar == 1){
                                        $separador = ",";
                                    }

                                    $str_LsqlU .= $separador." G6_C328= ".$G6_C328."";
                                    $str_LsqlI .= $separador." G6_C328";
                                    $str_LsqlV .= $separador.$G6_C328;

                                    $validar = 1;

                                }
                                
                                $G6_C329='0';
                                if($G6_C329=='0'){
                                    if(isset($_POST["G6_C329_".$i])){
                                        $G6_C329=$_POST["G6_C329_".$i];
                                    }
                                        $separador = "";
                                        if($validar == 1){
                                            $separador = ",";
                                        }

                                        $str_LsqlU .= $separador."G6_C329 = '".$G6_C329."'";
                                        $str_LsqlI .= $separador."G6_C329";
                                        $str_LsqlV .= $separador."'".$G6_C329."'";
                                        $validar = 1;
                                }
                                
                                $G6_C330='0';
                                if($G6_C330=='0'){
                                    if(isset($_POST["G6_C330_".$i])){
                                        $G6_C330=$_POST["G6_C330_".$i];
                                    }
                                        $separador = "";
                                        if($validar == 1){
                                            $separador = ",";
                                        }

                                        $str_LsqlU .= $separador."G6_C330 = '".$G6_C330."'";
                                        $str_LsqlI .= $separador."G6_C330";
                                        $str_LsqlV .= $separador."'".$G6_C330."'";
                                        $validar = 1;
                                }                                
                                
                                $G6_C331='0';
                                if($G6_C331=='0'){
                                    if(isset($_POST["G6_C331_".$i])){
                                        $G6_C331=$_POST["G6_C331_".$i];
                                    }
                                        $separador = "";
                                        if($validar == 1){
                                            $separador = ",";
                                        }

                                        $str_LsqlU .= $separador."G6_C331 = '".$G6_C331."'";
                                        $str_LsqlI .= $separador."G6_C331";
                                        $str_LsqlV .= $separador."'".$G6_C331."'";
                                        $validar = 1;
                                }                                
                                
                                $G6_C334='';
                                if($G6_C334==''){
                                    if(isset($_POST["G6_C334_".$i])){
                                        $G6_C334=$_POST["G6_C334_".$i];
                                    }
                                        $separador = "";
                                        if($validar == 1){
                                            $separador = ",";
                                        }

                                        $str_LsqlU .= $separador."G6_C334 = '".$G6_C334."'";
                                        $str_LsqlI .= $separador."G6_C334";
                                        $str_LsqlV .= $separador."'".$G6_C334."'";
                                        $validar = 1;
                                }

                                $G6_C335='0';
                                if($G6_C335=='0'){
                                    if(isset($_POST["G6_C335_".$i])){
                                        $G6_C335=$_POST["G6_C335_".$i];
                                    }
                                        $separador = "";
                                        if($validar == 1){
                                            $separador = ",";
                                        }

                                        $str_LsqlU .= $separador."G6_C335 = '".$G6_C335."'";
                                        $str_LsqlI .= $separador."G6_C335";
                                        $str_LsqlV .= $separador."'".$G6_C335."'";
                                        $validar = 1;
                                }

                                $G6_C336='0';
                                if($G6_C336=='0'){
                                    if(isset($_POST["G6_C336_".$i])){
                                        $G6_C336=$_POST["G6_C336_".$i];
                                    }
                                        $separador = "";
                                        if($validar == 1){
                                            $separador = ",";
                                        }

                                        $str_LsqlU .= $separador."G6_C336 = '".$G6_C336."'";
                                        $str_LsqlI .= $separador."G6_C336";
                                        $str_LsqlV .= $separador."'".$G6_C336."'";
                                        $validar = 1;
                                }                                
                                
                                $G6_C332='0';
                                if($G6_C332=='0'){
                                    if(isset($_POST["G6_C332_".$i])){
                                        $G6_C332=$_POST["G6_C332_".$i];
                                    }
                                        $separador = "";
                                        if($validar == 1){
                                            $separador = ",";
                                        }

                                        $str_LsqlU .= $separador."G6_C332 = '".$G6_C332."'";
                                        $str_LsqlI .= $separador."G6_C332";
                                        $str_LsqlV .= $separador."'".$G6_C332."'";
                                        $validar = 1;
                                }
                                
                                $G6_C333='0';
                                if($G6_C333=='0' && !isset($_POST["G6_C43_".$i])){
                                    if(isset($_POST["G6_C333_".$i])){
                                        $G6_C333=$_POST["G6_C333_".$i];
                                    }
                                        $separador = "";
                                        if($validar == 1){
                                            $separador = ",";
                                        }

                                        $str_LsqlU .= $separador."G6_C43 = '".$G6_C333."'";
                                        $str_LsqlI .= $separador."G6_C43";
                                        $str_LsqlV .= $separador."'".$G6_C333."'";
                                        $validar = 1;
                                }


                                $G6_C320 = 0;
                                if(isset($_POST["G6_C320_".$i]) && $_POST["G6_C320_".$i] != ''){
                                    if($_POST["G6_C320_".$i] == 'Yes'){
                                        $G6_C320 = 1;
                                    }else if($_POST["G6_C320_".$i] == 'off'){
                                        $G6_C320 = 0;
                                    }else if($_POST["G6_C320_".$i] == 'on'){
                                        $G6_C320 = 1;
                                    }else if($_POST["G6_C320_".$i] == 'No'){
                                        $G6_C320 = 1;
                                    }else{
                                        $G6_C320 = $_POST["G6_C320_".$i] ;
                                    }   

                                    $separador = "";
                                    if($validar == 1){
                                        $separador = ",";
                                    }

                                    $str_LsqlU .= $separador." G6_C320 = ".$G6_C320."";
                                    $str_LsqlI .= $separador." G6_C320";
                                    $str_LsqlV .= $separador.$G6_C320;

                                    $validar = 1;

                                }else{
                                    $separador = "";
                                    if($validar == 1){
                                        $separador = ",";
                                    }

                                    $str_LsqlU .= $separador." G6_C320 = ".$G6_C320."";
                                    $str_LsqlI .= $separador." G6_C320";
                                    $str_LsqlV .= $separador.$G6_C320;

                                    $validar = 1;

                                }
                            

                                if(isset($_POST["G6_C321_".$i]) && $_POST["G6_C321_".$i] != '0'  && $_POST["G6_C321_".$i] != ''){
                                    $separador = "";
                                    if($validar == 1){
                                        $separador = ",";
                                    }

                                    $str_LsqlU .= $separador."G6_C321 = '".$_POST["G6_C321_".$i]."'";
                                    $str_LsqlI .= $separador."G6_C321";
                                    $str_LsqlV .= $separador."'".$_POST["G6_C321_".$i]."'";
                                    $validar = 1;

                                }

                                if(isset($_POST["G6_C337_".$i]) && $_POST["G6_C337_".$i] != ''){
                                    $separador = "";
                                    if($validar == 1){
                                        $separador = ",";
                                    }

                                    $str_LsqlU .= $separador."G6_C337 = '".$_POST["G6_C337_".$i]."'";
                                    $str_LsqlI .= $separador."G6_C337";
                                    $str_LsqlV .= $separador."'".$_POST["G6_C337_".$i]."'";
                                    $validar = 1;

                                }

                                if(isset($_POST["G6_C338_".$i]) && $_POST["G6_C338_".$i] != ''){
                                    $separador = "";
                                    if($validar == 1){
                                        $separador = ",";
                                    }

                                    $str_LsqlU .= $separador."G6_C338 = '".$_POST["G6_C338_".$i]."'";
                                    $str_LsqlI .= $separador."G6_C338";
                                    $str_LsqlV .= $separador."'".$_POST["G6_C338_".$i]."'";
                                    $validar = 1;

                                }

                                if(isset($_POST["G6_C339_".$i]) && $_POST["G6_C339_".$i] != ''){
                                    $separador = "";
                                    if($validar == 1){
                                        $separador = ",";
                                    }

                                    $str_LsqlU .= $separador."G6_C339 = '".$_POST["G6_C339_".$i]."'";
                                    $str_LsqlI .= $separador."G6_C339";
                                    $str_LsqlV .= $separador."'".$_POST["G6_C339_".$i]."'";
                                    $validar = 1;

                                }

                                $G6_C58 = NULL;
                                if(isset($_POST["G6_C58_".$i]) && $_POST["G6_C58_".$i] != '0'){    
                                    if($_POST["G6_C58_".$i] != '' && $_POST["G6_C58_".$i] != 'undefined' && $_POST["G6_C58_".$i] != 'null'){
                                        $separador = "";
                                        $fecha = date('Y-m-d');
                                        if($validar == 1){
                                            $separador = ",";
                                        }

                                        $G6_C58 = "'".$fecha." ".str_replace(' ', '',$_POST["G6_C58_".$i])."'";
                                        $str_LsqlU .= $separador."  G6_C58 = ".$G6_C58."";
                                        $str_LsqlI .= $separador."  G6_C58";
                                        $str_LsqlV .= $separador.$G6_C58;
                                        $validar = 1;
                                    }

                                }
                                    
                                if($validar == 1){
                                    /* Primero es meter el orden en el que estan siendo generados */
                                    $Lsqls = "SELECT COUNT(*) as TOTAL FROM ".$BaseDatos_systema.".G6 WHERE G6_C32 = ".$UltimaSeccion;
                                    $resLsqls = $mysqli->query($Lsqls);
                                    $data = $resLsqls->fetch_array();

                                    $str_LsqlI .= ", G6_C317";
                                    $str_LsqlV .= ",".($data['TOTAL'] + 1);


                                    $numero = $UltimaSeccion;
                                            
                                    $G6_C32 = $numero;
                                    $str_LsqlU .= ", G6_C32 = ".$G6_C32."";
                                    $str_LsqlI .= ", G6_C32";
                                    $str_LsqlV .= ",".$G6_C32;
                                }     
                                
                                if(isset($_POST["padre"])){
                                    if($_POST["padre"] != ''){
                                        $numero = $_POST["padre"];
                                        $G6_C207 = $numero;
                                        $str_LsqlU .= ", G6_C207 = ".$G6_C207."";
                                        $str_LsqlI .= ", G6_C207";
                                        $str_LsqlV .= ",".$_POST["padre"];
                                        
                                    }
                                } 

                                $str_Lsql = $str_LsqlI.", G6_C209)" . $str_LsqlV.", 1)";

                                if($validar == 1){
                                    //editaCamposecho $str_Lsql;
                                    if ($mysqli->query($str_Lsql) === TRUE) {
                                        $ultimo = $mysqli->insert_id;

                                        if(isset($_POST["ConsInte_".$i]) && $_POST["ConsInte_".$i] != 0){
                                            $idC = $_POST["ConsInte_".$i];
                                        }else{
                                            $idC = $ultimo;
                                        }
                                            
                                        /* Esto es para meterlo en el CAMPO_ */
                                        if($_POST['G6_C40_'.$i] != '12'){
                                            $CampoLsql = "INSERT INTO ".$BaseDatos_systema.".CAMPO_ (CAMPO__Nombre____b, CAMPO__Tipo______b, CAMPO__ConsInte__PREGUN_b) VALUES ('G".$_POST['padre']."_C".$ultimo."' , ".$_POST['G6_C40_'.$i].", ".$ultimo.")";

                                            if ($mysqli->query($CampoLsql) === TRUE) {
                                            
                                            }else{
                                                echo "Error metiendo el valor en campo ".$mysqli->error;
                                            }
                                        }
                                        

                                        /* aqui empezamos a guardar el GUIDET */
                                        if($_POST['G6_C40_'.$i] == '12'){

                                            if(isset($_POST["GuidetM_".$i])){
                                                $modoGrilla = 0;
                                                if(isset($_POST['modoGuidet_'.$i])){
                                                    $modoGrilla = $_POST['modoGuidet_'.$i];
                                                }
                                                $LsqlGuidet = "";

                                                if($_POST["GuidetM_".$i] != ''){
                                                    if($_POST["GuidetM_".$i] != '_ConsInte__b'){

                                                        $LsqlGuidet = 'INSERT INTO '.$BaseDatos_systema.".GUIDET (GUIDET_ConsInte__GUION__Mae_b, GUIDET_ConsInte__GUION__Det_b, GUIDET_Nombre____b, GUIDET_ConsInte__PREGUN_Ma1_b, GUIDET_ConsInte__PREGUN_De1_b, GUIDET_Modo______b, GUIDET_ConsInte__PREGUN_Cre_b,GUIDET_ConsInte__PREGUN_Totalizador_b,GUIDET_ConsInte__PREGUN_Totalizador_H_b,GUIDET_Oper_Totalizador_b) VALUES (".$_POST["padre"].", ".$_POST['G6_C43_'.$i]." , '".$_POST['G6_C39_'.$i] ."' , ".$_POST["GuidetM_".$i]." , ".$_POST["Guidet_".$i].", 0, ".$ultimo.",{$_POST['GuidetPadre_'.$i]},{$_POST['GuidetHijo_'.$i]},{$_POST['GuidetOper_'.$i]})";
                                                    }else{
                                                        $LsqlGuidet = 'INSERT INTO '.$BaseDatos_systema.".GUIDET (GUIDET_ConsInte__GUION__Mae_b, GUIDET_ConsInte__GUION__Det_b, GUIDET_Nombre____b, GUIDET_ConsInte__PREGUN_De1_b, GUIDET_Modo______b, GUIDET_ConsInte__PREGUN_Cre_b,GUIDET_ConsInte__PREGUN_Totalizador_b,GUIDET_ConsInte__PREGUN_Totalizador_H_b,GUIDET_Oper_Totalizador_b) VALUES (".$_POST["padre"].", ".$_POST['G6_C43_'.$i]." , '".$_POST['G6_C39_'.$i]."' ,  ".$_POST["Guidet_".$i].", 0, ".$ultimo.",{$_POST['GuidetPadre_'.$i]},{$_POST['GuidetHijo_'.$i]},{$_POST['GuidetOper_'.$i]})";
                                                    }  

                                                    if($mysqli->query($LsqlGuidet) === TRUE){

                                                    }else{
                                                        echo "Error Guidet".$mysqli->error;
                                                    }
                                                    
                                                }
                                            }
                                        }

                                        /* definimos los campos que se van a mostrar en el combo */
                                        if($_POST['G6_C40_'.$i] == '11'){
                                            if(isset($_POST['contadorEto_'.$i])){
                                                $varEto = $_POST['contadorEto_'.$i];
                                                for($j = 0; $j < $varEto; $j++){
                                                    /* Lo pirmero es obtener el id del campo */

                                                    $campoLsql = "SELECT CAMPO__ConsInte__b FROM ".$BaseDatos_systema.".CAMPO_ WHERE CAMPO__ConsInte__PREGUN_b = ".$_POST['camposCOnfiguradosGuionAux_'.$j];
                                                    $rsCampo = $mysqli->query($campoLsql);
                                                    $campo = $rsCampo->fetch_array();

                                                    $valorPregunMio = 0;
                                                    if($_POST['camposCOnfiguradosGuionTo_'.$j] != 0){
                                                        $campoLsqlx = "SELECT CAMPO__ConsInte__b FROM ".$BaseDatos_systema.".CAMPO_ WHERE CAMPO__ConsInte__PREGUN_b = ".$_POST['camposCOnfiguradosGuionTo_'.$j];
                                                        $rsCampox = $mysqli->query($campoLsqlx);
                                                        $campox = $rsCampox->fetch_array();   
                                                        $valorPregunMio = $campox['CAMPO__ConsInte__b'];
                                                    }
                                                    
                                                    /* ya tengo el id del campo lo voy a meter sencillo sin salto ni mucho menos */
                                                    $preguiLsql = "INSERT INTO ".$BaseDatos_systema.".PREGUI (PREGUI_ConsInte__PREGUN_b, PREGUI_ConsInte__CAMPO__b, PREGUI_Consinte__GUION__b, PREGUI_Consinte__CAMPO__GUI_B) VALUES (".$idC.",".$campo['CAMPO__ConsInte__b']." , ".$_POST['padre'].", ".$valorPregunMio.")";
                                                    //echo $preguiLsql;
                                                    if ($mysqli->query($preguiLsql) === TRUE) {
                                                
                                                    }else{
                                                        echo "Error metiendo el valor en pregui ".$mysqli->error." ".$preguiLsql;
                                                    }
                                                    
                                                   
                                                } 
                                            }                      
                                        }
                                        if($_POST['G6_C40_'.$i] == '1'){
                                            if($_POST['G6_C318_'.$i] != ''){

                                                $G6_C323_ = "";
                                                if($_POST['G6_C323_'.$i] != ''){
                                                    $G6_C323_ = $_POST['G6_C323_'.$i];
                                                }

                                                $LsqlDefectoText = "UPDATE ".$BaseDatos_systema.".PREGUN SET PREGUN_Default___b = ".$_POST['G6_C318_'.$i]." , PREGUN_DefaText__b = '".$G6_C323_."' WHERE PREGUN_ConsInte__b = ".$idC;

                                                if ($mysqli->query($LsqlDefectoText) === TRUE) {}
                                            }else{
                                                $LsqlDefectoText = "UPDATE ".$BaseDatos_systema.".PREGUN SET PREGUN_Default___b = NULL , PREGUN_DefaText__b = NULL WHERE PREGUN_ConsInte__b = ".$idC;

                                                if ($mysqli->query($LsqlDefectoText) === TRUE) {}
                                            }
                                        }else{
                                            $LsqlDefectoText = "UPDATE ".$BaseDatos_systema.".PREGUN SET PREGUN_DefaText__b = NULL WHERE PREGUN_ConsInte__b = ".$idC;
                                             $LsqlDefectoText = $mysqli->query($LsqlDefectoText);                                               
                                        }

                                        if($_POST['G6_C40_'.$i] == '6'){
                                            if($_POST['depende_'.$i] != 0 && $_POST['depende_'.$i] != ''){

                                                $LsqlDepen = "UPDATE ".$BaseDatos_systema.".PREGUN SET PREGUN_ConsInte_PREGUN_Depende_b = ".$_POST['depende_'.$i]." WHERE PREGUN_ConsInte__b = ".$idC;
                                                if ($mysqli->query($LsqlDepen) === TRUE) {

                                                }else{
                                                    echo "Error metiendo el Actualizando el valor de las dependientes ".$mysqli->error;
                                                }
                                                    
                                            }
                                            if($_POST['G6_C318_'.$i] != ''){
                                                $LsqlDefectoText =$mysqli->query("UPDATE ".$BaseDatos_systema.".PREGUN SET PREGUN_Default___b = ".$_POST['G6_C318_'.$i]." WHERE PREGUN_ConsInte__b = ".$idC);
                                            }else{
                                                $LsqlDefectoText =$mysqli->query("UPDATE ".$BaseDatos_systema.".PREGUN SET PREGUN_Default___b = 0 WHERE PREGUN_ConsInte__b = ".$idC);
                                            }
                                        }else{
                                            $LsqlDepen = "UPDATE ".$BaseDatos_systema.".PREGUN SET PREGUN_ConsInte_PREGUN_Depende_b = NULL WHERE PREGUN_ConsInte__b = ".$idC;
                                            $LsqlDepen = $mysqli->query($LsqlDepen);
                                        }
                                        
                                        if($_POST['G6_C40_'.$i] == '5' || $_POST['G6_C40_'.$i] == '10'){
                                            echo " <br> <br>[G6_C40_] =>" . $_POST['G6_C40_'.$i];
                                            if($_POST['G6_C318_'.$i] != ''){
                                                echo "<br><br> [G6_C318_] =>" . $_POST["G6_C318_{$i}"] ;
                                                $LsqlDefectoText = "UPDATE ".$BaseDatos_systema.".PREGUN SET PREGUN_Default___b = ".$_POST['G6_C318_'.$i]." WHERE PREGUN_ConsInte__b = ".$idC;
                                                
                                                if ($mysqli->query($LsqlDefectoText) === TRUE) {}
                                            }else{
                                                $LsqlDefectoText = "UPDATE ".$BaseDatos_systema.".PREGUN SET PREGUN_Default___b = NULL WHERE PREGUN_ConsInte__b = ".$idC;
                                                if ($mysqli->query($LsqlDefectoText) === TRUE) {}
                                            }

                                            if (isset($_POST['G6_C324_'.$i])) {
                                                if ($_POST['G6_C324_'.$i] != "") {
                                                    $sqlTimeCant = "UPDATE ".$BaseDatos_systema.".PREGUN SET PREGUN_DefCanFec_b = ".$_POST['G6_C324_'.$i]." WHERE PREGUN_ConsInte__b = ".$idC;
                                                    if ($mysqli->query($sqlTimeCant) === TRUE) {}
                                                }
                                            }

                                            if (isset($_POST['G6_C325_'.$i])) {
                                                if ($_POST['G6_C325_'.$i] != "0") {
                                                    $sqlTimePer = "UPDATE ".$BaseDatos_systema.".PREGUN SET PREGUN_DefUniFec_b = '".$_POST['G6_C325_'.$i]."' WHERE PREGUN_ConsInte__b = ".$idC;
                                                    if ($mysqli->query($sqlTimePer) === TRUE) {}
                                                }
                                            }
                                        }else{
                                            $LsqlDefectoText = "UPDATE ".$BaseDatos_systema.".PREGUN SET PREGUN_DefUniFec_b = NULL, PREGUN_DefCanFec_b = NULL WHERE PREGUN_ConsInte__b = ".$idC;
                                            $LsqlDefectoText = $mysqli->query($LsqlDefectoText);
                                        }


                                    if (isset($_POST["G6_C40_{$i}"]) == '3' || isset($_POST["G6_C40_{$i}"]) == '4') {
                                        
                                        if ($_POST['G6_C318_' . $i] != '') {
                                            //Es un campo numerico toca preguntar si el valor viene por defecto y eso

                                            $G6_C319_ = 0;
                                            if ($_POST['G6_C319_' . $i] != '') {
                                                $G6_C319_ = $_POST['G6_C319_' . $i];
                                            }
                                            $LsqlDefectoNumDec = "UPDATE " . $BaseDatos_systema . ".PREGUN SET PREGUN_Default___b = " . $_POST['G6_C318_' . $i] . " , PREGUN_DefaNume__b = " . $G6_C319_ . " WHERE PREGUN_ConsInte__b = " . $idC;

                                            //echo $LsqlDefectoNumDec;

                                            if ($mysqli->query($LsqlDefectoNumDec) === TRUE) {
                                                if ($_POST['G6_C318_' . $i] == '3002') {
                                                    $ValiarLsql = "SELECT * FROM " . $BaseDatos_systema . ".CONTADORES WHERE CONTADORES_ConsInte__PREGUN_b = " . $idC;
                                                    $resValiarLsql = $mysqli->query($ValiarLsql);
                                                    if ($resValiarLsql->num_rows === 0) {
                                                        $InsertLsql = "INSERT INTO " . $BaseDatos_systema . ".CONTADORES (CONTADORES_ConsInte__PREGUN_b, CONTADORES_Valor_b) VALUES (" . $idC . ", 0)";
                                                        if ($mysqli->query($InsertLsql) === TRUE) {
                                                        } else {
                                                            echo "Error metiendo el valor de los defectos autoincrmets " . $mysqli->error;
                                                        }
                                                    }
                                                }
                                            } else {
                                                echo "Error metiendo el valor de los defectos " . $mysqli->error;
                                            }
                                        } else {
                                            $LsqlDefectoNumDec = "UPDATE " . $BaseDatos_systema . ".PREGUN SET PREGUN_Default___b = NULL , PREGUN_DefaNume__b = NULL WHERE PREGUN_ConsInte__b = " . $idC;
                                            if ($mysqli->query($LsqlDefectoNumDec) === TRUE) {
                                            }
                                        }
                                    } else {
                                        $LsqlDefectoNumDec = "UPDATE " . $BaseDatos_systema . ".PREGUN SET PREGUN_DefaNume__b = NULL WHERE PREGUN_ConsInte__b = " . $idC;
                                        $LsqlDefectoNumDec = $mysqli->query($LsqlDefectoNumDec);
                                    }

                                        if(isset($_POST['orderCamposCrearSecciones']) && $_POST['orderCamposCrearSecciones']){

                                            /* Se modifico el orden de los campos */
                                            $campox = explode('&', $_POST['orderCamposCrearSecciones']);

                                            $j = 0;
                                            foreach ($campox as $value) {
                                               //echo "JOse => ".$value;
                                                $idCampoX = explode('=' , $value);

                                                if($idCampoX[1] == $i){

                                                    $UpdateLSql ="UPDATE ".$BaseDatos_systema.".PREGUN SET PREGUN_OrdePreg__b = ".$j." WHERE PREGUN_ConsInte__b = ".$idC;
                                                    if($mysqli->query($UpdateLSql) === true){

                                                    }else{
                                                        echo "No pude editarla Despues el orden de las preguntas  =>".$mysqli->error;
                                                    }

                                                }

                                                $j++;
                                            }
                                        }
                                        guardar_auditoria("INSERTAR", "INSERTAR REGISTRO EN G6");
                                        echo $ultimo;
                                            
                                    } else {
                                        echo "Error Hacieno el proceso los registros : " . $mysqli->error;
                                    }  
                                }
                            }
                        }
                    }


                    guardar_auditoria("INSERTAR", "INSERTAR REGISTRO EN SECCIONES");
                } else {
                    echo "0";
                    //echo "Error Hacieno el proceso los registros : " . $mysqli->error;
                }  
            }     

        }

        if(isset($_POST['editarDatosSecciones'])){
            $sqlIdGuion=$mysqli->query("select GUION__ConsInte__b as id from {$BaseDatos_systema}.GUION_ where md5(concat('".clave_get."', GUION__ConsInte__b)) = '".$_POST['padre']."'");
            if($sqlIdGuion && $sqlIdGuion->num_rows==1){
                $sqlIdGuion=$sqlIdGuion->fetch_object();
                $_POST['padre']=$sqlIdGuion->id;
            }
            $validar = 0;
            $str_LsqlI = "UPDATE ".$BaseDatos_systema.".G7 SET ";
 
                                                                             
            if(isset($_POST["EditarG7_C33"])){
                $separador = "";
                if($validar == 1){
                    $separador = ",";
                }
                $str_LsqlI .= $separador."G7_C33 = '".$_POST["EditarG7_C33"]."'";
                $validar = 1;
            }
                                                                            
            $G7_C35= NULL;
            //este es de tipo numero no se deja ir asi '', si est avacio lo mejor es no mandarlo
            if(isset($_POST["EditarG7_C35"])){    
                if($_POST["EditarG7_C35"] != ''){
                    $separador = "";
                    if($validar == 1){
                        $separador = ",";
                    }

                    $G7_C35 = $_POST["EditarG7_C35"];
                    $str_LsqlI .= $separador." G7_C35 = '".$G7_C35."'";
                    $validar = 1;
                }
            }
     
            if(isset($_POST["EditarG7_C36"])){
                $separador = "";
                if($validar == 1){
                    $separador = ",";
                }

                $str_LsqlI .= $separador."G7_C36 = '".$_POST["EditarG7_C36"]."'";
                $validar = 1;
            }

            if(isset($_POST["EditarG7_C38"])){
                $separador = "";
                if($validar == 1){
                    $separador = ",";
                }

                $str_LsqlI .= $separador."G7_C38 = '".$_POST["EditarG7_C38"]."'";
                $validar = 1;
            }
     
            $G7_C37 = 0;
            //este es tipo check primeo si viene y de acuerdo a su valor se pone 1 o 0
            if(isset($_POST["EditarG7_C37"])){
                $separador = "";
                if($validar == 1){
                    $separador = ",";
                }

                $str_LsqlI .= $separador." G7_C37 = 0";
                $validar = 1;
            }else{
                $separador = "";
                if($validar == 1){
                    $separador = ",";
                }
                $str_LsqlI .= $separador." G7_C37 = 1";
                $validar = 1;
            }
     
            if(isset($_POST["EditarG7_C38"])){
                $separador = "";
                if($validar == 1){
                    $separador = ",";
                }
                $str_LsqlI .= $separador."G7_C38 = '".$_POST["EditarG7_C38"]."'";
                $validar = 1;

                if ($_POST["EditarG7_C38"] == 2) {
                    $quality = true;
                }else{
                    $quality = false;
                }
            }
    
            $str_Lsql = $str_LsqlI." WHERE G7_ConsInte__b = ".$_POST['IdseccionEdicion'];
            if($validar == 1){
                //echo $str_Lsql;
                if ($mysqli->query($str_Lsql) === TRUE) {
                    $UltimaSeccion = $mysqli->insert_id;
                    if ($quality) {
                        $insertEstadoCalidad = "INSERT INTO ".$BaseDatos_systema.".G6 
                                                (G6_C207,G6_C32,G6_C39,G6_C40,G6_C317,G6_C44,G6_C209,G6_C320,G6_C318)
                                                VALUES 
                                                (".$_POST["padre"].",".$_POST['IdseccionEdicion'].",
                                                 'ESTADO_CALIDAD_Q_DY',6,1000,-3,1,3,-203);";

                        $insertCalificacionCalidad = "INSERT INTO ".$BaseDatos_systema.".G6 
                                                (G6_C207,G6_C32,G6_C39,G6_C40,G6_C317,G6_C209,G6_C318)
                                                VALUES 
                                                 (".$_POST["padre"].",".$_POST['IdseccionEdicion'].",
                                                 'CALIFICACION_Q_DY',4,1001,1,3003)";


                        $insertComentCalidad = "INSERT INTO ".$BaseDatos_systema.".G6 
                                                (G6_C207,G6_C32,G6_C39,G6_C40,G6_C317,G6_C209)
                                                VALUES 
                                                (".$_POST["padre"].",".$_POST['IdseccionEdicion'].",
                                                 'COMENTARIO_CALIDAD_Q_DY',2,1002,1);";

                        $insertComentAgente = "INSERT INTO ".$BaseDatos_systema.".G6 
                                                (G6_C207,G6_C32,G6_C39,G6_C40,G6_C317,G6_C209,G6_C320)
                                                VALUES 
                                                (".$_POST["padre"].",".$_POST['IdseccionEdicion'].",
                                                 'COMENTARIO_AGENTE_Q_DY',2,1003,1,2);";
                        $insertFechaAudicion = "INSERT INTO ".$BaseDatos_systema.".G6 
                                                (G6_C207,G6_C32,G6_C39,G6_C40,G6_C317,G6_C209,G6_C320)
                                                VALUES 
                                                (".$_POST["padre"].",".$_POST['IdseccionEdicion'].",
                                                 'FECHA_AUDITADO_Q_DY',5,1004,1,2);";
                        
                        $insertNombreAuditado = "INSERT INTO ".$BaseDatos_systema.".G6 
                                                (G6_C207,G6_C32,G6_C39,G6_C40,G6_C317,G6_C209,G6_C320)
                                                VALUES 
                                                (".$_POST["padre"].",".$_POST['IdseccionEdicion'].",
                                                 'NOMBRE_AUDITOR_Q_DY',1,1005,1,2);";

                        $exisCalificacionCalidad = "SELECT PREGUN_ConsInte__b 
                                                  FROM ".$BaseDatos_systema.".PREGUN 
                                                  WHERE PREGUN_ConsInte__GUION__b = ".$_POST["padre"]."
                                                  AND PREGUN_Texto_____b = 'CALIFICACION_Q_DY';";

                        $exisEstadoCalidad = "SELECT PREGUN_ConsInte__b 
                                                  FROM ".$BaseDatos_systema.".PREGUN 
                                                  WHERE PREGUN_ConsInte__GUION__b = ".$_POST["padre"]."
                                                  AND PREGUN_Texto_____b = 'ESTADO_CALIDAD_Q_DY';";

                        $exisComentCalidad = "SELECT PREGUN_ConsInte__b 
                                                  FROM ".$BaseDatos_systema.".PREGUN 
                                                  WHERE PREGUN_ConsInte__GUION__b = ".$_POST["padre"]."
                                                  AND PREGUN_Texto_____b = 'COMENTARIO_CALIDAD_Q_DY';";

                        $exisComentAgente = "SELECT PREGUN_ConsInte__b 
                                                  FROM ".$BaseDatos_systema.".PREGUN 
                                                  WHERE PREGUN_ConsInte__GUION__b = ".$_POST["padre"]."
                                                  AND PREGUN_Texto_____b = 'COMENTARIO_AGENTE_Q_DY';";
                        
                        $exisFechaAudicion = "SELECT PREGUN_ConsInte__b 
                                                  FROM ".$BaseDatos_systema.".PREGUN 
                                                  WHERE PREGUN_ConsInte__GUION__b = ".$_POST["padre"]."
                                                  AND PREGUN_Texto_____b = 'FECHA_AUDITADO_Q_DY';";
                        
                        $exisNombreAuditado = "SELECT PREGUN_ConsInte__b 
                                                  FROM ".$BaseDatos_systema.".PREGUN 
                                                  WHERE PREGUN_ConsInte__GUION__b = ".$_POST["padre"]."
                                                  AND PREGUN_Texto_____b = 'NOMBRE_AUDITOR_Q_DY';";

                        $exisCalificacionCalidad = $mysqli->query($exisCalificacionCalidad);
                        if ($exisCalificacionCalidad->num_rows == 0) {
                            $insertCalificacionCalidad = $mysqli->query($insertCalificacionCalidad);
                        }

                        $exisEstadoCalidad = $mysqli->query($exisEstadoCalidad);
                        if ($exisEstadoCalidad->num_rows == 0) {
                            $insertEstadoCalidad = $mysqli->query($insertEstadoCalidad);
                        }

                        $exisComentCalidad = $mysqli->query($exisComentCalidad);
                        if ($exisComentCalidad->num_rows == 0) {
                            $insertComentCalidad = $mysqli->query($insertComentCalidad);
                        }

                        $exisComentAgente = $mysqli->query($exisComentAgente);
                        if ($exisComentAgente->num_rows == 0) {
                            $insertComentAgente = $mysqli->query($insertComentAgente);
                        }
                        
                        $exisFechaAudicion = $mysqli->query($exisFechaAudicion);
                        if ($exisFechaAudicion->num_rows == 0) {
                            $insertFechaAudicion = $mysqli->query($insertFechaAudicion);
                        }
                        
                        $exisNombreAuditado = $mysqli->query($exisNombreAuditado);
                        if ($exisNombreAuditado->num_rows == 0) {
                            $insertNombreAuditado = $mysqli->query($insertNombreAuditado);
                        }
                    }
                    //si logramos editar la seccion procedemos a guardar los campos , nuevamente;
                     if(isset($_POST['arrCampos_t'])){

                        if ($_POST['arrCampos_t'] != "") {
                            $arrCampos_t = explode(",", $_POST['arrCampos_t']);
                            foreach ($arrCampos_t as $row => $i) {
                                if(isset($_POST["ConsInte_".$i]) || isset($_POST['nuevo_campo_'.$i])){

                                    $str_Lsql  = '';
                                    $validar = 0;
                                    $str_LsqlU = "UPDATE ".$BaseDatos_systema.".PREGUN SET ";
                                    $str_LsqlI = "INSERT INTO ".$BaseDatos_systema.".PREGUN (";
                                    $str_LsqlV = " VALUES ("; 
                                    
                                    //JDBD - Este es el nomb re del campo.
                                    if(isset($_POST["G6_C39_".$i]) && $_POST["G6_C39_".$i] != ''){
                                        $separador = "";
                                        if($validar == 1){
                                            $separador = ",";
                                        }

                                        $str_LsqlU .= $separador."PREGUN_Texto_____b = '".$_POST["G6_C39_".$i]."'";
                                        $str_LsqlI .= $separador."PREGUN_Texto_____b";
                                        $str_LsqlV .= $separador."'".$_POST["G6_C39_".$i]."'";
                                        $validar = 1;

                                    }
                             
                                    $G6_C52 = 0;
                                    if(isset($_POST["G6_C52_".$i]) && $_POST["G6_C52_".$i] != '0'){
                                        if($_POST["G6_C52_".$i] == 'Yes'){
                                            $G6_C52 = 1;
                                        }else if($_POST["G6_C52_".$i] == 'off'){
                                            $G6_C52 = 0;
                                        }else if($_POST["G6_C52_".$i] == 'on'){
                                            $G6_C52 = 1;
                                        }else if($_POST["G6_C52_".$i] == 'No'){
                                            $G6_C52 = 1;
                                        }else{
                                            $G6_C52 = $_POST["G6_C52_".$i] ;
                                        }   

                                        $separador = "";
                                        if($validar == 1){
                                            $separador = ",";
                                        }

                                        $str_LsqlU .= $separador."PREGUN_IndiUnic__b = ".$G6_C52."";
                                        $str_LsqlI .= $separador."PREGUN_IndiUnic__b";
                                        $str_LsqlV .= $separador.$G6_C52;

                                        $validar = 1;

                                    }else{
                                        $separador = "";
                                        if($validar == 1){
                                            $separador = ",";
                                        }

                                        $str_LsqlU .= $separador."PREGUN_IndiUnic__b = ".$G6_C52."";
                                        $str_LsqlI .= $separador."PREGUN_IndiUnic__b";
                                        $str_LsqlV .= $separador.$G6_C52;

                                        $validar = 1;

                                    }
                                    //JDBD - este es el tipo de campo.
                                    if(isset($_POST["G6_C40_".$i]) && $_POST["G6_C40_".$i] != '0'){
                                        $separador = "";
                                        if($validar == 1){
                                            $separador = ",";
                                        }

                                        $str_LsqlU .= $separador."PREGUN_Tipo______b = '".$_POST["G6_C40_".$i]."'";
                                        $str_LsqlI .= $separador."PREGUN_Tipo______b";
                                        $str_LsqlV .= $separador."'".$_POST["G6_C40_".$i]."'";
                                        $validar = 1;

                                    }
                             
                                    $G6_C50 = 0;
                                    if(isset($_POST["G6_C50_".$i]) && $_POST["G6_C50_".$i] != '0'){
                                        if($_POST["G6_C50_".$i] == 'Yes'){
                                            $G6_C50 = 1;
                                        }else if($_POST["G6_C50_".$i] == 'off'){
                                            $G6_C50 = 0;
                                        }else if($_POST["G6_C50_".$i] == 'on'){
                                            $G6_C50 = 1;
                                        }else if($_POST["G6_C50_".$i] == 'No'){
                                            $G6_C50 = 1;
                                        }else{
                                            $G6_C50 = $_POST["G6_C50_".$i] ;
                                        }   

                                        $separador = "";
                                        if($validar == 1){
                                            $separador = ",";
                                        }

                                        $str_LsqlU .= $separador."PREGUN_Agrupar_b = ".$G6_C50."";
                                        $str_LsqlI .= $separador."PREGUN_Agrupar_b";
                                        $str_LsqlV .= $separador.$G6_C50;

                                        $validar = 1;

                                    }else{
                                        $separador = "";
                                        if($validar == 1){
                                            $separador = ",";
                                        }

                                        $str_LsqlU .= $separador."PREGUN_Agrupar_b = ".$G6_C50."";
                                        $str_LsqlI .= $separador."PREGUN_Agrupar_b";
                                        $str_LsqlV .= $separador.$G6_C50;

                                        $validar = 1;

                                    }
                                    //JDBD - este es el minumo numero a guardar en el campo tipo numerico.
                                    $G6_C53= NULL;
                                    if(isset($_POST["G6_C53_".$i]) && $_POST["G6_C53_".$i] != '0'){    
                                        if($_POST["G6_C53_".$i] != ''){
                                            $separador = "";
                                            if($validar == 1){
                                                $separador = ",";
                                            }

                                            $G6_C53 = $_POST["G6_C53_".$i];
                                            $str_LsqlU .= $separador."PREGUN_NumeMini__b = '".$G6_C53."'";
                                            $str_LsqlI .= $separador."PREGUN_NumeMini__b";
                                            $str_LsqlV .= $separador."'".$G6_C53."'";
                                            $validar = 1;
                                        }

                                    }
                             
                                    $G6_C41 = 0;
                                    if(isset($_POST["G6_C41_".$i]) && $_POST["G6_C41_".$i] != '0'){
                                        if($_POST["G6_C41_".$i] == 'Yes'){
                                            $G6_C41 = 1;
                                        }else if($_POST["G6_C41_".$i] == 'off'){
                                            $G6_C41 = 0;
                                        }else if($_POST["G6_C41_".$i] == 'on'){
                                            $G6_C41 = 1;
                                        }else if($_POST["G6_C41_".$i] == 'No'){
                                            $G6_C41 = 1;
                                        }else{
                                            $G6_C41 = $_POST["G6_C41_".$i] ;
                                        }   

                                        $separador = "";
                                        if($validar == 1){
                                            $separador = ",";
                                        }

                                        $str_LsqlU .= $separador."PREGUN_IndiBusc__b = ".$G6_C41."";
                                        $str_LsqlI .= $separador."PREGUN_IndiBusc__b";
                                        $str_LsqlV .= $separador.$G6_C41;

                                        


                                        $validar = 1;
                                   
                                    }else{
                                        $separador = "";
                                        if($validar == 1){
                                            $separador = ",";
                                        }

                                        $str_LsqlU .= $separador."PREGUN_IndiBusc__b = ".$G6_C41."";
                                        $str_LsqlI .= $separador."PREGUN_IndiBusc__b";
                                        $str_LsqlV .= $separador.$G6_C41;

                                        $validar = 1;

                                    }
                                        
                                    $G6_C51 = 0;
                                    //JDBD - en este se indica si el valor del campo es obligatorio. 
                                    if(isset($_POST["G6_C51_".$i]) && $_POST["G6_C51_".$i] != 0 && $_POST["G6_C51_".$i] != ''){
                                        if($_POST["G6_C51_".$i] == 'Yes'){
                                            $G6_C51 = 1;
                                        }else if($_POST["G6_C51_".$i] == 'off'){
                                            $G6_C51 = 0;
                                        }else if($_POST["G6_C51_".$i] == 'on'){
                                            $G6_C51 = 1;
                                        }else if($_POST["G6_C51_".$i] == 'No'){
                                            $G6_C51 = 1;
                                        }else{
                                            $G6_C51 = $_POST["G6_C51_".$i] ;
                                        }   

                                        $separador = "";
                                        if($validar == 1){
                                            $separador = ",";
                                        }

                                        $str_LsqlU .= $separador."PREGUN_IndiRequ__b = ".$G6_C51."";
                                        $str_LsqlI .= $separador."PREGUN_IndiRequ__b";
                                        $str_LsqlV .= $separador.$G6_C51;

                                        $validar = 1;

                                    }else{
                                        $separador = "";
                                        if($validar == 1){
                                            $separador = ",";
                                        }

                                        $str_LsqlU .= $separador."PREGUN_IndiRequ__b = ".$G6_C51."";
                                        $str_LsqlI .= $separador."PREGUN_IndiRequ__b";
                                        $str_LsqlV .= $separador.$G6_C51;

                                        $validar = 1;

                                    }

                                    //JDBD - este es el numero maximo permitido a guardar en el campo tipo numerico
                                    $G6_C54= NULL;
                                    if(isset($_POST["G6_C54_".$i]) && $_POST["G6_C54_".$i] != '0'){    
                                        if($_POST["G6_C54_".$i] != ''){
                                            $separador = "";
                                            if($validar == 1){
                                                $separador = ",";
                                            }

                                            $G6_C54 = $_POST["G6_C54_".$i];
                                            $str_LsqlU .= $separador."PREGUN_NumeMaxi__b = '".$G6_C54."'";
                                            $str_LsqlI .= $separador."PREGUN_NumeMaxi__b";
                                            $str_LsqlV .= $separador."'".$G6_C54."'";
                                            $validar = 1;
                                        }

                                    }
                                    //JDBD - esta es la fecha minima a ingresar en el campo tipo fecha.
                                    $G6_C55 = NULL;
                                    if(isset($_POST["G6_C55_".$i]) && $_POST["G6_C55_".$i] != '0'){    
                                        if($_POST["G6_C55_".$i] != ''){
                                            $separador = "";
                                            if($validar == 1){
                                                $separador = ",";
                                            }

                                            $G6_C55 = "'".str_replace(' ', '',$_POST["G6_C55_".$i])." 00:00:00'";
                                            $str_LsqlU .= $separador."PREGUN_FechMini__b = ".$G6_C55;
                                            $str_LsqlI .= $separador."PREGUN_FechMini__b";
                                            $str_LsqlV .= $separador.$G6_C55;
                                            $validar = 1;
                                        }else{
                                            $separador = "";
                                            if($validar == 1){
                                                $separador = ",";
                                            }
                                            $str_LsqlU .= $separador." PREGUN_FechMini__b = NULL";
                                            $str_LsqlI .= $separador." PREGUN_FechMini__b";
                                            $str_LsqlV .= $separador."NULL";
                                            $validar = 1;

                                        }

                                    }else{
                                        if(!isset($_POST["G6_C55_".$i])){
                                            $separador = "";
                                            if($validar == 1){
                                                $separador = ",";
                                            }
        
                                            $G6_C55 = "'0001-01-01 00:00:00'";
                                            $str_LsqlU .= $separador." PREGUN_FechMini__b = ".$G6_C55;
                                            $str_LsqlI .= $separador." PREGUN_FechMini__b";
                                            $str_LsqlV .= $separador.$G6_C55;
                                            $validar = 1;   
                                        }
                                    }
                                    //JDBD - esta es la fecha maxima a ingresar en el campo tipo fecha.
                                    $G6_C56 = NULL;
                                    if(isset($_POST["G6_C56_".$i]) && $_POST["G6_C56_".$i] != '0'){    
                                        if($_POST["G6_C56_".$i] != ''){
                                            $separador = "";
                                            if($validar == 1){
                                                $separador = ",";
                                            }

                                            $G6_C56 = "'".str_replace(' ', '',$_POST["G6_C56_".$i])." 00:00:00'";
                                            $str_LsqlU .= $separador."PREGUN_FechMaxi__b = ".$G6_C56;
                                            $str_LsqlI .= $separador."PREGUN_FechMaxi__b";
                                            $str_LsqlV .= $separador.$G6_C56;
                                            $validar = 1;
                                        }

                                    }
                              
                                    if(isset($_POST["G6_C43_".$i]) && $_POST["G6_C43_".$i] != '0'){
                                        $separador = "";
                                        if($validar == 1){
                                            $separador = ",";
                                        }

                                        $str_LsqlU .= $separador."PREGUN_ConsInte__GUION__PRE_B = '".$_POST["G6_C43_".$i]."'";
                                        $str_LsqlI .= $separador."PREGUN_ConsInte__GUION__PRE_B";
                                        $str_LsqlV .= $separador."'".$_POST["G6_C43_".$i]."'";
                                        $validar = 1;

                                    }else{
                                        $G6_C333='0';
                                        if($G6_C333=='0'){
                                            if(isset($_POST["G6_C333_".$i]) && $_POST["G6_C333_".$i] !=''){
                                                $G6_C333=$_POST["G6_C333_".$i];
                                            }
                                                $separador = "";
                                                if($validar == 1){
                                                    $separador = ",";
                                                }

                                                $str_LsqlU .= $separador."PREGUN_ConsInte__GUION__PRE_B = '".$G6_C333."'";
                                                $str_LsqlI .= $separador."PREGUN_ConsInte__GUION__PRE_B";
                                                $str_LsqlV .= $separador."'".$G6_C333."'";
                                                $validar = 1;
                                        }
                                    }
                                    
                                    //JDBD - aqui se guarda la lista asociada para el campo de lista.
                                    $G6_C44='0';
                                    if($G6_C44=='0'){
                                        if(isset($_POST["G6_C44_".$i])){
                                            $G6_C44=$_POST["G6_C44_".$i];
                                        }
                                            $separador = "";
                                            if($validar == 1){
                                                $separador = ",";
                                            }

                                        $str_LsqlU .= $separador."PREGUN_ConsInte__OPCION_B = '".$G6_C44."'";
                                        $str_LsqlI .= $separador."PREGUN_ConsInte__OPCION_B";
                                        $str_LsqlV .= $separador."'".$G6_C44."'";
                                        $validar = 1;
                                    }                                
                                    
                                    //JDBD - esta es la hora minima a guardar en el campo tipo hora.
                                    $G6_C57 = NULL;
                                    if(isset($_POST["G6_C57_".$i]) && $_POST["G6_C57_".$i] != '0'){    
                                        if($_POST["G6_C57_".$i] != '' && $_POST["G6_C57_".$i] != 'undefined' && $_POST["G6_C57_".$i] != 'null'){
                                            $separador = "";
                                            $fecha = date('Y-m-d');
                                            if($validar == 1){
                                                $separador = ",";
                                            }

                                            $G6_C57 = "'".$fecha." ".str_replace(' ', '',$_POST["G6_C57_".$i])."'";
                                            $str_LsqlU .= $separador."PREGUN_HoraMini__b = ".$G6_C57."";
                                            $str_LsqlI .= $separador."PREGUN_HoraMini__b";
                                            $str_LsqlV .= $separador.$G6_C57;
                                            $validar = 1;
                                        }else{
                                            $str_LsqlU .= $separador."PREGUN_HoraMini__b = NULL";
                                            $str_LsqlI .= $separador."PREGUN_HoraMini__b";
                                            $str_LsqlV .= $separador."NULL";
                                        }

                                    }

                                    //JDBD este es la hora maxima a guardar en el campo tipo hora.
                                    $G6_C58 = NULL;
                                    if(isset($_POST["G6_C58_".$i]) && $_POST["G6_C58_".$i] != '0'){    
                                        if($_POST["G6_C58_".$i] != '' && $_POST["G6_C58_".$i] != 'undefined' && $_POST["G6_C58_".$i] != 'null'){
                                            $separador = "";
                                            $fecha = date('Y-m-d');
                                            if($validar == 1){
                                                $separador = ",";
                                            }

                                            $G6_C58 = "'".$fecha." ".str_replace(' ', '',$_POST["G6_C58_".$i])."'";
                                            $str_LsqlU .= $separador."PREGUN_HoraMaxi__b = ".$G6_C58."";
                                            $str_LsqlI .= $separador."PREGUN_HoraMaxi__b";
                                            $str_LsqlV .= $separador.$G6_C58;
                                            $validar = 1;
                                        }else{
                                            $str_LsqlU .= $separador."PREGUN_HoraMaxi__b = NULL";
                                            $str_LsqlI .= $separador."PREGUN_HoraMaxi__b";
                                            $str_LsqlV .= $separador."NULL";
                                        }

                                    }
                                    
                                    //JDBD - este define si el campos sera deshabilitado.
                                    $G6_C320 = 0;
                                    if(isset($_POST["G6_C320_".$i]) && $_POST["G6_C320_".$i] != '' && $_POST["G6_C320_".$i] != 0){
                                        if($_POST["G6_C320_".$i] == 'Yes'){
                                            $G6_C320 = 1;
                                        }else if($_POST["G6_C320_".$i] == 'off'){
                                            $G6_C320 = 0;
                                        }else if($_POST["G6_C320_".$i] == 'on'){
                                            $G6_C320 = 1;
                                        }else if($_POST["G6_C320_".$i] == 'No'){
                                            $G6_C320 = 1;
                                        }else{
                                            $G6_C320 = $_POST["G6_C320_".$i] ;
                                        }   

                                        $separador = "";
                                        if($validar == 1){
                                            $separador = ",";
                                        }

                                        $str_LsqlU .= $separador."PREGUN_ContAcce__b = ".$G6_C320."";
                                        $str_LsqlI .= $separador."PREGUN_ContAcce__b";
                                        $str_LsqlV .= $separador.$G6_C320;

                                        $validar = 1;

                                    }else{

                                        $separador = "";
                                        if($validar == 1){
                                            $separador = ",";
                                        }

                                        $str_LsqlU .= $separador."PREGUN_ContAcce__b = ".$G6_C320."";
                                        $str_LsqlI .= $separador."PREGUN_ContAcce__b";
                                        $str_LsqlV .= $separador.$G6_C320;

                                        $validar = 1;

                                    }

                                    $G6_C322 = 0;
                                    if(isset($_POST["G6_C322_".$i]) && $_POST["G6_C322_".$i] != ''){
                                        if($_POST["G6_C322_".$i] == 'Yes'){
                                            $G6_C322 = 1;
                                        }else if($_POST["G6_C322_".$i] == 'off'){
                                            $G6_C322 = 0;
                                        }else if($_POST["G6_C322_".$i] == 'on'){
                                            $G6_C322 = 1;
                                        }else if($_POST["G6_C322_".$i] == 'No'){
                                            $G6_C322 = 1;
                                        }else{
                                            $G6_C322 = $_POST["G6_C322_".$i] ;
                                        }   

                                        $separador = "";
                                        if($validar == 1){
                                            $separador = ",";
                                        }

                                        $str_LsqlU .= $separador."PREGUN_WebForm_b = ".$G6_C322."";
                                        $str_LsqlI .= $separador."PREGUN_WebForm_b";
                                        $str_LsqlV .= $separador.$G6_C322;

                                        $validar = 1;

                                    }else{
                                        $separador = "";
                                        if($validar == 1){
                                            $separador = ",";
                                        }

                                        $str_LsqlU .= $separador."PREGUN_WebForm_b = ".$G6_C322."";
                                        $str_LsqlI .= $separador."PREGUN_WebForm_b";
                                        $str_LsqlV .= $separador.$G6_C322;

                                        $validar = 1;

                                    }
                                    
                                $G6_C326 = 0;
                                if(isset($_POST["G6_C326_".$i]) && $_POST["G6_C326_".$i] != ''){
                                    if($_POST["G6_C326_".$i] == 'Yes'){
                                        $G6_C326 = 1;
                                    }else if($_POST["G6_C326_".$i] == 'off'){
                                        $G6_C326 = 0;
                                    }else if($_POST["G6_C326_".$i] == 'on'){
                                        $G6_C326 = 1;
                                    }else if($_POST["G6_C326_".$i] == 'No'){
                                        $G6_C326 = 1;
                                    }else{
                                        $G6_C326 = $_POST["G6_C326_".$i] ;
                                    }   

                                    $separador = "";
                                    if($validar == 1){
                                        $separador = ",";
                                    }

                                    $str_LsqlU .= $separador." PREGUN_TipoTel_b = ".$G6_C326."";
                                    $str_LsqlI .= $separador." PREGUN_TipoTel_b";
                                    $str_LsqlV .= $separador.$G6_C326;

                                    $validar = 1;

                                }else{
                                    $separador = "";
                                    if($validar == 1){
                                        $separador = ",";
                                    }

                                    $str_LsqlU .= $separador." PREGUN_TipoTel_b= ".$G6_C326."";
                                    $str_LsqlI .= $separador." PREGUN_TipoTel_b";
                                    $str_LsqlV .= $separador.$G6_C326;

                                    $validar = 1;

                                }
                                    
                                $G6_C327 = 0;
                                if(isset($_POST["G6_C327_".$i]) && $_POST["G6_C327_".$i] != ''){
                                    if($_POST["G6_C327_".$i] == 'Yes'){
                                        $G6_C327 = 1;
                                    }else if($_POST["G6_C327_".$i] == 'off'){
                                        $G6_C327 = 0;
                                    }else if($_POST["G6_C327_".$i] == 'on'){
                                        $G6_C327 = 1;
                                    }else if($_POST["G6_C327_".$i] == 'No'){
                                        $G6_C327 = 1;
                                    }else{
                                        $G6_C327 = $_POST["G6_C327_".$i] ;
                                    }   

                                    $separador = "";
                                    if($validar == 1){
                                        $separador = ",";
                                    }

                                    $str_LsqlU .= $separador." PREGUN_SendMail_b = ".$G6_C327."";
                                    $str_LsqlI .= $separador." PREGUN_SendMail_b";
                                    $str_LsqlV .= $separador.$G6_C327;

                                    $validar = 1;

                                }else{
                                    $separador = "";
                                    if($validar == 1){
                                        $separador = ",";
                                    }

                                    $str_LsqlU .= $separador." PREGUN_SendMail_b= ".$G6_C327."";
                                    $str_LsqlI .= $separador." PREGUN_SendMail_b";
                                    $str_LsqlV .= $separador.$G6_C327;

                                    $validar = 1;

                                }
                                
                                $G6_C328 = 0;
                                if(isset($_POST["G6_C328_".$i]) && $_POST["G6_C328_".$i] != ''){
                                    if($_POST["G6_C328_".$i] == 'Yes'){
                                        $G6_C328 = 1;
                                    }else if($_POST["G6_C328_".$i] == 'off'){
                                        $G6_C328 = 0;
                                    }else if($_POST["G6_C328_".$i] == 'on'){
                                        $G6_C328 = 1;
                                    }else if($_POST["G6_C328_".$i] == 'No'){
                                        $G6_C328 = 1;
                                    }else{
                                        $G6_C328 = $_POST["G6_C328_".$i] ;
                                    }   

                                    $separador = "";
                                    if($validar == 1){
                                        $separador = ",";
                                    }

                                    $str_LsqlU .= $separador." PREGUN_SendSMS_b = ".$G6_C328."";
                                    $str_LsqlI .= $separador." PREGUN_SendSMS_b";
                                    $str_LsqlV .= $separador.$G6_C328;

                                    $validar = 1;

                                }else{
                                    $separador = "";
                                    if($validar == 1){
                                        $separador = ",";
                                    }

                                    $str_LsqlU .= $separador." PREGUN_SendSMS_b= ".$G6_C328."";
                                    $str_LsqlI .= $separador." PREGUN_SendSMS_b";
                                    $str_LsqlV .= $separador.$G6_C328;

                                    $validar = 1;

                                }
                                    
                                $G6_C329='0';
                                if($G6_C329=='0'){
                                    if(isset($_POST["G6_C329_".$i])){
                                        $G6_C329=$_POST["G6_C329_".$i];
                                    }
                                        $separador = "";
                                        if($validar == 1){
                                            $separador = ",";
                                        }

                                        $str_LsqlU .= $separador."PREGUN_idCuentaMail_b = '".$G6_C329."'";
                                        $str_LsqlI .= $separador."PREGUN_idCuentaMail_b";
                                        $str_LsqlV .= $separador."'".$G6_C329."'";
                                        $validar = 1;
                                }
                                
                                $G6_C330='0';
                                if($G6_C330=='0'){
                                    if(isset($_POST["G6_C330_".$i])){
                                        $G6_C330=$_POST["G6_C330_".$i];
                                    }
                                        $separador = "";
                                        if($validar == 1){
                                            $separador = ",";
                                        }

                                        $str_LsqlU .= $separador."PREGUN_IdProveedorSms_b = '".$G6_C330."'";
                                        $str_LsqlI .= $separador."PREGUN_IdProveedorSms_b";
                                        $str_LsqlV .= $separador."'".$G6_C330."'";
                                        $validar = 1;
                                }                                
                                    
                                
                                $G6_C331='0';
                                if($G6_C331=='0'){
                                    if(isset($_POST["G6_C331_".$i])){
                                        $G6_C331=$_POST["G6_C331_".$i];
                                    }
                                        $separador = "";
                                        if($validar == 1){
                                            $separador = ",";
                                        }

                                        $str_LsqlU .= $separador."PREGUN_textSMS_b = '".$G6_C331."'";
                                        $str_LsqlI .= $separador."PREGUN_textSMS_b";
                                        $str_LsqlV .= $separador."'".$G6_C331."'";
                                        $validar = 1;
                                }                                
                                    
                                $G6_C334='0';
                                if($G6_C334=='0'){
                                    if(isset($_POST["G6_C334_".$i])){
                                        $G6_C334=$_POST["G6_C334_".$i];
                                    }
                                        $separador = "";
                                        if($validar == 1){
                                            $separador = ",";
                                        }

                                        $str_LsqlU .= $separador."PREGUN_PrefijoSms_b = '".$G6_C334."'";
                                        $str_LsqlI .= $separador."PREGUN_PrefijoSms_b";
                                        $str_LsqlV .= $separador."'".$G6_C334."'";
                                        $validar = 1;
                                }

                                $G6_C335='0';
                                if($G6_C335=='0'){
                                    if(isset($_POST["G6_C335_".$i])){
                                        $G6_C335=$_POST["G6_C335_".$i];
                                    }
                                        $separador = "";
                                        if($validar == 1){
                                            $separador = ",";
                                        }

                                        $str_LsqlU .= $separador."PREGUN_consInte__ws_B = '".$G6_C335."'";
                                        $str_LsqlI .= $separador."PREGUN_consInte__ws_B";
                                        $str_LsqlV .= $separador."'".$G6_C335."'";
                                        $validar = 1;
                                }

                                $G6_C336='0';
                                if($G6_C336=='0'){
                                    if(isset($_POST["G6_C336_".$i])){
                                        $G6_C336=$_POST["G6_C336_".$i];
                                    }
                                        $separador = "";
                                        if($validar == 1){
                                            $separador = ",";
                                        }

                                        $str_LsqlU .= $separador."PREGUN_FormaIntegrarWS_b = '".$G6_C336."'";
                                        $str_LsqlI .= $separador."PREGUN_FormaIntegrarWS_b";
                                        $str_LsqlV .= $separador."'".$G6_C336."'";
                                        $validar = 1;
                                }
                                    
                                $G6_C332='0';
                                if($G6_C332=='0'){
                                    if(isset($_POST["G6_C332_".$i])){
                                        $G6_C332=$_POST["G6_C332_".$i];
                                    }
                                        $separador = "";
                                        if($validar == 1){
                                            $separador = ",";
                                        }

                                        $str_LsqlU .= $separador."PREGUN_SearchMail_b = '".$G6_C332."'";
                                        $str_LsqlI .= $separador."PREGUN_SearchMail_b";
                                        $str_LsqlV .= $separador."'".$G6_C332."'";
                                        $validar = 1;
                                }    
                                    

                                    $G6_C46 = 0;
                                    if(isset($_POST["G6_C46_".$i]) && $_POST["G6_C46_".$i] != '' && $_POST["G6_C46_".$i] != 0){
                                        if($_POST["G6_C46_".$i] == 'Yes'){
                                            $G6_C46 = 1;
                                        }else if($_POST["G6_C46_".$i] == 'off'){
                                            $G6_C46 = 0;
                                        }else if($_POST["G6_C46_".$i] == 'on'){
                                            $G6_C46 = 1;
                                        }else if($_POST["G6_C46_".$i] == 'No'){
                                            $G6_C46 = 1;
                                        }else{
                                            $G6_C46 = $_POST["G6_C46_".$i] ;
                                        }   

                                        $separador = "";
                                        if($validar == 1){
                                            $separador = ",";
                                        }

                                        $str_LsqlU .= $separador."PREGUN_PermiteAdicion_b = ".$G6_C46."";
                                        $str_LsqlI .= $separador."PREGUN_PermiteAdicion_b";
                                        $str_LsqlV .= $separador.$G6_C46;

                                        $validar = 1;

                                    }else{
                                        $separador = "";
                                        if($validar == 1){
                                            $separador = ",";
                                        }

                                        $str_LsqlU .= $separador."PREGUN_PermiteAdicion_b = ".$G6_C46."";
                                        $str_LsqlI .= $separador."PREGUN_PermiteAdicion_b";
                                        $str_LsqlV .= $separador.$G6_C46;

                                        $validar = 1;

                                    }

                                    //JDBD - en este campo se define la operacion que se ara entre campos tipo numericos.
                                    if(isset($_POST["G6_C321_".$i]) && $_POST["G6_C321_".$i] != '0' && $_POST["G6_C321_".$i] != ''){
                                        $separador = "";
                                        if($validar == 1){
                                            $separador = ",";
                                        }

                                        $str_LsqlU .= $separador."PREGUN_OperEntreCamp_____b = '".$_POST["G6_C321_".$i]."'";
                                        $str_LsqlI .= $separador."PREGUN_OperEntreCamp_____b";
                                        $str_LsqlV .= $separador."'".$_POST["G6_C321_".$i]."'";
                                        $validar = 1;

                                    }

                                    if(isset($_POST["G6_C337_".$i]) && $_POST["G6_C337_".$i] != ''){
                                        $separador = "";
                                        if($validar == 1){
                                            $separador = ",";
                                        }
    
                                        $str_LsqlU .= $separador."PREGUN_Formato_b = '".$_POST["G6_C337_".$i]."'";
                                        $str_LsqlI .= $separador."PREGUN_Formato_b";
                                        $str_LsqlV .= $separador."'".$_POST["G6_C337_".$i]."'";
                                        $validar = 1;
    
                                    }
    
                                    if(isset($_POST["G6_C338_".$i]) && $_POST["G6_C338_".$i] != ''){
                                        $separador = "";
                                        if($validar == 1){
                                            $separador = ",";
                                        }
    
                                        $str_LsqlU .= $separador."PREGUN_PosDecimales_b = '".$_POST["G6_C338_".$i]."'";
                                        $str_LsqlI .= $separador."PREGUN_PosDecimales_b";
                                        $str_LsqlV .= $separador."'".$_POST["G6_C338_".$i]."'";
                                        $validar = 1;
    
                                    }

                                    if(isset($_POST["G6_C339_".$i]) && $_POST["G6_C339_".$i] != ''){
                                        $separador = "";
                                        if($validar == 1){
                                            $separador = ",";
                                        }
    
                                        $str_LsqlU .= $separador."PREGUN_Longitud__b = '".$_POST["G6_C339_".$i]."'";
                                        $str_LsqlI .= $separador."PREGUN_Longitud__b";
                                        $str_LsqlV .= $separador."'".$_POST["G6_C339_".$i]."'";
                                        $validar = 1;
    
                                    }
                                        
                                    if(!isset($_POST['nuevo_campo_'.$i])){

                                        $str_Lsql = $str_LsqlU." , PREGUN_FueGener_b = 2 WHERE PREGUN_ConsInte__b = " .$_POST['ConsInte_'.$i];
                                    }else{

                                        if($validar == 1){
                                            /* Primero es meter el orden en el que estan siendo generados */
                                            $Lsqls = "SELECT COUNT(*) as TOTAL FROM ".$BaseDatos_systema.".G6 WHERE G6_C32 = ".$_POST['IdseccionEdicion'];
                                            $resLsqls = $mysqli->query($Lsqls);
                                            $data = $resLsqls->fetch_array();

                                            $str_LsqlI .= ",PREGUN_OrdePreg__b";
                                            $str_LsqlV .= ",".($data['TOTAL'] + 1);

                                            $numero = $_POST['IdseccionEdicion'];
                                                    
                                            $G6_C32 = $numero;
                                            $str_LsqlU .= ",PREGUN_ConsInte__SECCIO_b = ".$G6_C32."";
                                            $str_LsqlI .= ",PREGUN_ConsInte__SECCIO_b";
                                            $str_LsqlV .= ",".$G6_C32;

                                        }   


                                        if(isset($_POST["padre"])){

                                            if($_POST["padre"] != ''){

                                                $numero = $_POST["padre"];
                                                $G6_C207 = $numero;
                                                $str_LsqlU .= ",PREGUN_ConsInte__GUION__b = ".$G6_C207."";
                                                $str_LsqlI .= ",PREGUN_ConsInte__GUION__b";
                                                $str_LsqlV .= ",".$_POST["padre"];
                                                
                                            }
                                        }   
                                        

                                    
                                        $str_Lsql = $str_LsqlI." , PREGUN_FueGener_b ) ".$str_LsqlV." , 1)";
                                    
                                    }        

                                    if($validar == 1){
                                        
                                        //echo $str_Lsql;die();

                                        if ($mysqli->query($str_Lsql) === TRUE) {
                                            $ultimo = $mysqli->insert_id;
                                            
                                            if(isset($_POST["ConsInte_".$i]) || isset($_POST['nuevo_campo_'.$i])){

                                                if(isset($_POST["ConsInte_".$i]) && $_POST["ConsInte_".$i] != 0){
                                                    $idC = $_POST["ConsInte_".$i];
                                                }else{
                                                    $idC = $ultimo;
                                                }

                                                /* Esto es para meterlo en el CAMPO_ */
                                                if($_POST['G6_C40_'.$i] != '12' && $_POST['G6_C40_'.$i] != '15' && isset($_POST['nuevo_campo_'.$i])){


                                                    $CampoLsql = "INSERT INTO ".$BaseDatos_systema.".CAMPO_ (CAMPO__Nombre____b, CAMPO__Tipo______b, CAMPO__ConsInte__PREGUN_b) VALUES ('G".$_POST['padre']."_C".$ultimo."' , ".$_POST['G6_C40_'.$i].", ".$ultimo.")";

                                                    if ($mysqli->query($CampoLsql) === TRUE) {
                                                    
                                                    }else{
                                                        echo "Error metiendo el valor en campo ".$mysqli->error;
                                                    }

                                                }
                                                
                                                /* aqui empezamos a guardar el GUIDET */
                                                if($_POST['G6_C40_'.$i] == '12'){

                                                    if($_POST["GuidetM_".$i] != '' && $_POST["GuidetM_".$i] != '0' ){

                                                        $guidetExis = "SELECT GUIDET_ConsInte__b AS id FROM ".$BaseDatos_systema.".GUIDET
                                                                       WHERE GUIDET_ConsInte__PREGUN_Cre_b = ".$idC;

                                                        $guidetExis = $mysqli->query($guidetExis);

                                                        $modoGrilla = 0;
                                                        if(isset($_POST['modoGuidet_'.$i])){
                                                            $modoGrilla = $_POST['modoGuidet_'.$i];
                                                        }

                                                        $LsqlGuidet = "";
                                                        if ($guidetExis->num_rows > 0) {
                                                            $guidetExis = $guidetExis->fetch_object();
                                                            $guidetExis = $guidetExis->id;

                                                            if($_POST["GuidetM_".$i] != '_ConsInte__b'){
                                                                $LsqlGuidet = "UPDATE ".$BaseDatos_systema.".GUIDET
                                                                           SET GUIDET_ConsInte__GUION__Mae_b = ".$_POST["padre"].",
                                                                               GUIDET_ConsInte__GUION__Det_b = ".$_POST['G6_C43_'.$i].",
                                                                               GUIDET_Nombre____b = '".$_POST['G6_C39_'.$i] ."', 
                                                                               GUIDET_ConsInte__PREGUN_Ma1_b = ".$_POST["GuidetM_".$i].",
                                                                               GUIDET_ConsInte__PREGUN_De1_b = ".$_POST["Guidet_".$i].",
                                                                               GUIDET_ConsInte__PREGUN_Totalizador_b={$_POST['GuidetPadre_'.$i]},
                                                                               GUIDET_ConsInte__PREGUN_Totalizador_H_b={$_POST['GuidetHijo_'.$i]},
                                                                               GUIDET_Oper_Totalizador_b={$_POST['GuidetOper_'.$i]},
                                                                               GUIDET_Modo______b = 0
                                                                           WHERE GUIDET_ConsInte__b = ".$guidetExis;       
                                                            }else{
                                                                $LsqlGuidet = "UPDATE ".$BaseDatos_systema.".GUIDET
                                                                           SET GUIDET_ConsInte__GUION__Mae_b = ".$_POST["padre"].",
                                                                               GUIDET_ConsInte__GUION__Det_b = ".$_POST['G6_C43_'.$i].",
                                                                               GUIDET_Nombre____b = '".$_POST['G6_C39_'.$i]."',
                                                                               GUIDET_ConsInte__PREGUN_De1_b = ".$_POST["Guidet_".$i].",
                                                                               GUIDET_ConsInte__PREGUN_Totalizador_b={$_POST['GuidetPadre_'.$i]},
                                                                               GUIDET_ConsInte__PREGUN_Totalizador_H_b={$_POST['GuidetHijo_'.$i]},
                                                                               GUIDET_Oper_Totalizador_b={$_POST['GuidetOper_'.$i]},
                                                                               GUIDET_Modo______b = 0
                                                                           WHERE GUIDET_ConsInte__b = ".$guidetExis;        
                                                            }
                                                        }else{                                        
                                                            if($_POST["GuidetM_".$i] != '_ConsInte__b'){
                                                                $LsqlGuidet = 'INSERT INTO '.$BaseDatos_systema.".GUIDET (GUIDET_ConsInte__GUION__Mae_b, GUIDET_ConsInte__GUION__Det_b, GUIDET_Nombre____b, GUIDET_ConsInte__PREGUN_Ma1_b, GUIDET_ConsInte__PREGUN_De1_b, GUIDET_Modo______b, GUIDET_ConsInte__PREGUN_Cre_b,GUIDET_ConsInte__PREGUN_Totalizador_b,GUIDET_ConsInte__PREGUN_Totalizador_H_b,GUIDET_Oper_Totalizador_b) VALUES (".$_POST["padre"].", ".$_POST['G6_C43_'.$i]." , '".$_POST['G6_C39_'.$i] ."' , ".$_POST["GuidetM_".$i]." , ".$_POST["Guidet_".$i].", 0, ".$ultimo.",{$_POST['GuidetPadre_'.$i]},{$_POST['GuidetHijo_'.$i]},{$_POST['GuidetOper_'.$i]})";
                                                            }else{
                                                                $LsqlGuidet = 'INSERT INTO '.$BaseDatos_systema.".GUIDET (GUIDET_ConsInte__GUION__Mae_b, GUIDET_ConsInte__GUION__Det_b, GUIDET_Nombre____b, GUIDET_ConsInte__PREGUN_De1_b, GUIDET_Modo______b, GUIDET_ConsInte__PREGUN_Cre_b,GUIDET_ConsInte__PREGUN_Totalizador_b,GUIDET_ConsInte__PREGUN_Totalizador_H_b,GUIDET_Oper_Totalizador_b) VALUES (".$_POST["padre"].", ".$_POST['G6_C43_'.$i]." , '".$_POST['G6_C39_'.$i]."' ,  ".$_POST["Guidet_".$i].", 0, ".$ultimo.",{$_POST['GuidetPadre_'.$i]},{$_POST['GuidetHijo_'.$i]},{$_POST['GuidetOper_'.$i]})";
                                                            }
                                                        }

                                                        if($mysqli->query($LsqlGuidet) === TRUE){

                                                        }else{
                                                            echo "Error Guidet".$mysqli->error;
                                                        }
                                                        
                                                    }
                                                   
                                                }else{

                                                    $LsqlDelete = "DELETE FROM ".$BaseDatos_systema.".GUIDET WHERE GUIDET_ConsInte__PREGUN_Cre_b = ".$idC;
                                                    $LsqlDelete = $mysqli->query($LsqlDelete);
                                                }   

                                                /* definimos los campos que se van a mostrar en el combo */
                                                if($_POST['G6_C40_'.$i] == '11'){

                                                    $arrRowListaCompleja_t = explode(",", $_POST["rowListaCompleja_".$i]);

                                                    if (count($arrRowListaCompleja_t) >= 1 && !is_null($arrRowListaCompleja_t[0]) && $arrRowListaCompleja_t[0] != "" && $arrRowListaCompleja_t[0] != -1) {

                                                        $LsqlDelete = "DELETE FROM ".$BaseDatos_systema.".PREGUI WHERE PREGUI_ConsInte__PREGUN_b = ".$_POST["ConsInte_".$i];

                                                        if($mysqli->query($LsqlDelete) === true){

                                                        }else{
                                                            echo "Error borrando pregui ".$mysqli->error;
                                                        }
                                                        foreach ($arrRowListaCompleja_t as $row => $j) {

                                                            $campoLsql = "SELECT CAMPO__ConsInte__b FROM ".$BaseDatos_systema.".CAMPO_ WHERE CAMPO__ConsInte__PREGUN_b = ".$_POST['camposCOnfiguradosGuionAux_'.$i.$j];

                                                            $rsCampo = $mysqli->query($campoLsql);
                                                            $campo = $rsCampo->fetch_array();

                                                            $valorPregunMio = 0;

                                                            if($_POST['camposCOnfiguradosGuionTo_'.$i.$j] != 0){

                                                                $campoLsqlx = "SELECT CAMPO__ConsInte__b FROM ".$BaseDatos_systema.".CAMPO_ WHERE CAMPO__ConsInte__PREGUN_b = ".$_POST['camposCOnfiguradosGuionTo_'.$i.$j];

                                                                $rsCampox = $mysqli->query($campoLsqlx);
                                                                $campox = $rsCampox->fetch_array();   
                                                                $valorPregunMio = $campox['CAMPO__ConsInte__b'];


                                                            }

                                                            $preguiLsql = "INSERT INTO ".$BaseDatos_systema.".PREGUI (PREGUI_ConsInte__PREGUN_b, PREGUI_ConsInte__CAMPO__b, PREGUI_Consinte__GUION__b, PREGUI_Consinte__CAMPO__GUI_B) VALUES (".$idC.",".$campo['CAMPO__ConsInte__b']." , ".$_POST['padre'].", ".$valorPregunMio.")";

                                                            if ($mysqli->query($preguiLsql) === TRUE) {
                                                            }else{
                                                                echo "Error metiendo el valor en pregui ".$mysqli->error;
                                                            }

                                                        }
                                                    }

                                                                             
                                                }


                                                if($_POST['G6_C40_'.$i] == '1'){
                                                    if($_POST['G6_C318_'.$i] != ''){
                                                        $G6_C323_ = "";
                                                        if($_POST['G6_C323_'.$i] != ''){
                                                            $G6_C323_ = $_POST['G6_C323_'.$i];
                                                        }

                                                        $LsqlDefectoText = "UPDATE ".$BaseDatos_systema.".PREGUN SET PREGUN_Default___b = ".$_POST['G6_C318_'.$i]." , PREGUN_DefaText__b = '".$G6_C323_."' WHERE PREGUN_ConsInte__b = ".$idC;


                                                        if ($mysqli->query($LsqlDefectoText) === TRUE) {}
                                                    }else{
                                                        $LsqlDefectoText = "UPDATE ".$BaseDatos_systema.".PREGUN SET PREGUN_Default___b = NULL, PREGUN_DefaText__b = NULL WHERE PREGUN_ConsInte__b = ".$idC;
                                                        if ($mysqli->query($LsqlDefectoText) === TRUE) {}
                                                    }
                                                }else{
                                                    $LsqlDefectoText = "UPDATE ".$BaseDatos_systema.".PREGUN SET PREGUN_Default___b = NULL, PREGUN_DefaText__b = NULL WHERE PREGUN_ConsInte__b = ".$idC;
                                                    $LsqlDefectoText = $mysqli->query($LsqlDefectoText);
                                                }
                                                
                                                if($_POST['G6_C40_'.$i] == '6'){
                                                    if($_POST['depende_'.$i] != ''){
                                                        $LsqlDepen = "UPDATE ".$BaseDatos_systema.".PREGUN SET PREGUN_ConsInte_PREGUN_Depende_b = ".$_POST['depende_'.$i]." WHERE PREGUN_ConsInte__b = ".$idC;
                                                        if ($mysqli->query($LsqlDepen) === TRUE) {

                                                        }else{
                                                            echo "Error metiendo el valor en dependientes ".$mysqli->error;
                                                        }
                                                            
                                                    }
                                                    if($_POST['G6_C318_'.$i] != ''){
                                                        $LsqlDefectoText =$mysqli->query("UPDATE ".$BaseDatos_systema.".PREGUN SET PREGUN_Default___b = ".$_POST['G6_C318_'.$i]." WHERE PREGUN_ConsInte__b = ".$idC);
                                                    }else{
                                                        $LsqlDefectoText =$mysqli->query("UPDATE ".$BaseDatos_systema.".PREGUN SET PREGUN_Default___b = 0 WHERE PREGUN_ConsInte__b = ".$idC);
                                                    }                                                    
                                                }


                                                if($_POST['G6_C40_'.$i] == '5' || $_POST['G6_C40_'.$i] == '10'){

                                                    if($_POST['G6_C318_'.$i] != ''){

                                                        $LsqlDefectoText = "UPDATE ".$BaseDatos_systema.".PREGUN SET PREGUN_Default___b = ".$_POST['G6_C318_'.$i]." WHERE PREGUN_ConsInte__b = ".$idC;

                                                        if ($mysqli->query($LsqlDefectoText) === TRUE) {}
                                                    }else{
                                                        $LsqlDefectoText = "UPDATE ".$BaseDatos_systema.".PREGUN SET PREGUN_Default___b = NULL WHERE PREGUN_ConsInte__b = ".$idC;
                                                        if ($mysqli->query($LsqlDefectoText) === TRUE) {}
                                                    }

                                                    if (isset($_POST['G6_C324_'.$i])) {
                                                        if ($_POST['G6_C324_'.$i] != "") {
                                                            $sqlTimeCant = "UPDATE ".$BaseDatos_systema.".PREGUN SET PREGUN_DefCanFec_b = ".$_POST['G6_C324_'.$i]." WHERE PREGUN_ConsInte__b = ".$idC;
                                                            if ($mysqli->query($sqlTimeCant) === TRUE) {}
                                                        }
                                                    }

                                                    if (isset($_POST['G6_C325_'.$i])) {
                                                        if ($_POST['G6_C325_'.$i] != "0") {
                                                            $sqlTimePer = "UPDATE ".$BaseDatos_systema.".PREGUN SET PREGUN_DefUniFec_b = '".$_POST['G6_C325_'.$i]."' WHERE PREGUN_ConsInte__b = ".$idC;
                                                            if ($mysqli->query($sqlTimePer) === TRUE) {}
                                                        }
                                                    }
                                                }else{
                                                    $LsqlDefectoText = "UPDATE ".$BaseDatos_systema.".PREGUN SET PREGUN_DefUniFec_b = NULL, PREGUN_DefCanFec_b = NULL WHERE PREGUN_ConsInte__b = ".$idC;
                                                    $LsqlDefectoText = $mysqli->query($LsqlDefectoText);
                                                }

                                                if(isset($_POST["G6_C40_{$i}"]) == '3' || isset($_POST["G6_C40_{$i}"]) == '4'){
                                                    if($_POST['G6_C318_'.$i] != ''){
                                                        //Es un campo numerico toca preguntar si el valor viene por defecto y eso

                                                        $G6_C319_ = 0;
                                                        if($_POST['G6_C319_'.$i] != ''){
                                                            $G6_C319_ = $_POST['G6_C319_'.$i];
                                                        }
                                                        $LsqlDefectoNumDec = "UPDATE ".$BaseDatos_systema.".PREGUN SET PREGUN_Default___b = ".$_POST['G6_C318_'.$i]." , PREGUN_DefaNume__b = ".$G6_C319_." WHERE PREGUN_ConsInte__b = ".$idC;

                                                        //echo $LsqlDefectoNumDec;
                                                        
                                                        if ($mysqli->query($LsqlDefectoNumDec) === TRUE) {
                                                            if($_POST['G6_C318_'.$i] == '3002'){
                                                                $ValiarLsql = "SELECT * FROM ".$BaseDatos_systema.".CONTADORES WHERE CONTADORES_ConsInte__PREGUN_b = ".$idC;
                                                                $resValiarLsql = $mysqli->query($ValiarLsql);
                                                                if($resValiarLsql->num_rows === 0){
                                                                    $InsertLsql = "INSERT INTO ".$BaseDatos_systema.".CONTADORES (CONTADORES_ConsInte__PREGUN_b, CONTADORES_Valor_b) VALUES (".$idC.", 0)";
                                                                    if ($mysqli->query($InsertLsql) === TRUE) {
                                                                        
                                                                    }else{
                                                                        echo "Error metiendo el valor de los defectos autoincrmets ".$mysqli->error;
                                                                    }
                                                                }
                                                            }
                                                        }else{
                                                            echo "Error metiendo el valor de los defectos ".$mysqli->error;
                                                        }
                                                    }else{
                                                        $LsqlDefectoNumDec = "UPDATE ".$BaseDatos_systema.".PREGUN SET PREGUN_Default___b = NULL , PREGUN_DefaNume__b = NULL WHERE PREGUN_ConsInte__b = ".$idC;
                                                        if ($mysqli->query($LsqlDefectoNumDec) === TRUE) {}
                                                    }
                                                    
                                                }else{
                                                    $LsqlDefectoNumDec = "UPDATE ".$BaseDatos_systema.".PREGUN SET PREGUN_DefaNume__b = NULL WHERE PREGUN_ConsInte__b = ".$idC;
                                                    $LsqlDefectoNumDec = $mysqli->query($LsqlDefectoNumDec);
                                                }

                                          
                                                if(isset($_POST['orderCamposCrearSeccionesEdicion']) && $_POST['orderCamposCrearSeccionesEdicion'] ){

                                                    /* Se modifico el orden de los campos */
                                                    $campox = explode('&', $_POST['orderCamposCrearSeccionesEdicion']);

                                                    $j = 0;
                                                    //var_dump($campox);
                                                    foreach ($campox as $value) {
                                                       //echo "JOse => ".$value;
                                                        $idCampoX = explode('=' , $value);
                                                        //var_dump($idCampoX);
                                                        if($idCampoX[1] == $i){

                                                            $UpdateLSql ="UPDATE ".$BaseDatos_systema.".PREGUN SET PREGUN_OrdePreg__b = ".$j." WHERE PREGUN_ConsInte__b = ".$idC;
                                                            //echo $UpdateLSql;
                                                            if($mysqli->query($UpdateLSql) === true){
                                                            }else{
                                                                echo "No pude editarla Despues el orden de las preguntas  =>".$mysqli->error;
                                                            }

                                                        }

                                                        $j++;
                                                    }
                                                }
                                            }
                                            

                                            guardar_auditoria("INSERTAR", "INSERTAR REGISTRO EN G6");
                                            echo $ultimo;
                                                
                                        } else {
                                            echo $str_Lsql;
                                            echo "Error Hacieno el proceso los registros Edicion : " . $mysqli->error;
                                        }  
                                    }
                                }
                            }
                        }
                    }

                    echo $_POST['IdseccionEdicion'];
                }else{
                    echo '0' . $mysqli->error;
                }
            }

        }

        if(isset($_POST['borraPreguntas'])){

            $campo = "SELECT PREGUN_ConsInte__b AS id, PREGUN_ConsInte__GUION__b AS idG, PREGUN_Tipo______b AS tip 
                      FROM ".$BaseDatos_systema.".PREGUN 
                      WHERE PREGUN_ConsInte__b = ".$_POST['id'];
            $campo = $mysqli->query($campo);

            if ($campo->num_rows > 0) {
                $campo = $campo->fetch_object();

                $strSQLEliminarSaltos_t = "SELECT PRSADE_ConsInte__b AS id FROM ".$BaseDatos_systema.".PRSADE WHERE PRSADE_ConsInte__PREGUN_b = ".$_POST['id'];
                $resSQLEliminarSaltos_t = $mysqli->query($strSQLEliminarSaltos_t);
                if ($resSQLEliminarSaltos_t->num_rows > 0) {
                    while ($row = $resSQLEliminarSaltos_t->fetch_object()) {
                        $mysqli->query("DELETE FROM ".$BaseDatos_systema.".PRSASA WHERE PRSASA_ConsInte__PRSADE_b = ".$row->id);
                    }
                }
                //JDBD - si es un subformulario lo eliminamos.
                $delCamp = "DELETE FROM ".$BaseDatos_systema.".PRSADE WHERE PRSADE_ConsInte__PREGUN_b = ".$_POST['id'];
                $delCamp = $mysqli->query($delCamp); 
                $delCamp = "DELETE FROM ".$BaseDatos_systema.".GUIDET WHERE GUIDET_ConsInte__PREGUN_Cre_b = ".$_POST['id'];
                $delCamp = $mysqli->query($delCamp);   
                //JDBD - ahora eliminamos en cascada todo registro perteneciente a este campo.
                $delCamp = "DELETE FROM ".$BaseDatos_systema.".PREGUI WHERE PREGUI_ConsInte__PREGUN_b = ".$_POST['id'];
                $delCamp = $mysqli->query($delCamp); 
                $delCamp = "DELETE FROM ".$BaseDatos_systema.".PREGUN WHERE PREGUN_ConsInte__b = ".$_POST['id'];
                $delCamp = $mysqli->query($delCamp); 
                $delCamp = "DELETE FROM ".$BaseDatos_systema.".CAMPO_ WHERE CAMPO__ConsInte__PREGUN_b = ".$_POST['id'];
                $delCamp = $mysqli->query($delCamp);
                $delCamp = "DELETE FROM ".$BaseDatos_systema.".CAMINC WHERE CAMINC_NomCamPob_b = 'G".$campo->idG."_C".$_POST['id']."' OR CAMINC_NomCamGui_b = 'G".$campo->idG."_C".$_POST['id']."';";
                $delCamp = $mysqli->query($delCamp);
                $delCamp = "DELETE FROM ".$BaseDatos_systema.".CAMCON WHERE CAMCON_ConsInte__PREGUN_b = ".$_POST['id'];
                $delCamp = $mysqli->query($delCamp);     
                $delCamp = "DELETE FROM ".$BaseDatos_systema.".CAMORD WHERE CAMORD_POBLCAMP__B = 'G".$campo->idG."_C".$_POST['id']."'";
                $delCamp = $mysqli->query($delCamp);

                //ELIMINAR RELACION CON WEBSERVICE
                $delCamp = $mysqli->query("DELETE FROM {$BaseDatos_systema}.CAMCONWS WHERE (CAMCONWS_ConsInte__PREGUN__b={$_POST['id']} OR CAMCONWS_ConsInte__PREGUN__llave_b={$_POST['id']})");
                //ELIMINAR RELACION CON LISTAS DEL WEBSERVICE
                $delCamp = $mysqli->query("DELETE FROM {$BaseDatos_systema}.LISOPCWS WHERE LISOPCWS_ConsInte__PREGUN__llave_b={$_POST['id']}");

                //JDBD - ahora eliminamos la columna en la tabla propia.
                // $delCamp = "ALTER TABLE ".$BaseDatos.".G".$campo->idG." DROP COLUMN G".$campo->idG."_C".$campo->id;
                // if ($mysqli->query($delCamp)) {
                //     echo "Campo borrado completamente";
                // }
                echo "1";
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
 /* ejemplo que retorna json */
        if(isset($_POST['traerDatosWs']) && $_POST['traerDatosWs'] == true){

            $query = "SELECT * from DYALOGOCRM_SISTEMA.PREGUN WHERE md5(concat('".clave_get."',  PREGUN_ConsInte__GUION__b)) = '" . $_POST['id'] . "'";  
            $res = $mysqli->query($query);

            $camposWS = [];
            $baseId=0;

            if($res && $res->num_rows > 0){
                while($row = $res->fetch_object()){
                    $camposWS[] = $row;
                    $baseId = $row->PREGUN_ConsInte__GUION__b;
                }
            }

            echo json_encode(["estado" => "ok", "camposWs" => $camposWS, "baseId" => $baseId]);
        }

        if(isset($_GET['RecortarCampos'])){
            $data=json_decode(stripslashes($_POST['data']));

            $response='Error';
            $mensaje="Parametros invalidos";
            if($data){
                $response=recortarCampos($data);
                if(count($response) == 0){
                    $response='ok';
                    $mensaje="Campos actualizados";
                }else{
                    $mensaje=$response;
                }
            }
            echo json_encode(array('Estado'=>$response, 'Mensaje'=>$mensaje));
        }



    }

    function crearTabla($idGuion){
        global $mysqli;
        global $BaseDatos;
        global $BaseDatos_systema;
        global $BaseDatos_telefonia;
        global $dyalogo_canales_electronicos;
        global $BaseDatos_general;

        /* una vez creada la tabla procedemos a generar lo que toca generar */
        // Este include esta raro no se necesita pero generar error supuestamente por variable no definida en el itellisence
        // include(__DIR__."../../../../generador/generar_tablas_bd.php");   

        $create_Lsql = "CREATE TABLE ".$BaseDatos.".".$generarA." (
                        ".$generarA."_ConsInte__b int(10) NOT NULL AUTO_INCREMENT,
                        ".$generarA."_FechaInsercion datetime DEFAULT NULL,
                        ".$generarA."_Usuario int(10) DEFAULT NULL,
                        ".$generarA."_CodigoMiembro int(10) DEFAULT NULL,
                        ".$generarA."_PoblacionOrigen int(10) DEFAULT NULL,
                        ".$generarA."_EstadoDiligenciamiento int(10) DEFAULT NULL,
                        ".$generarA."_IdLlamada varchar(253) DEFAULT NULL";

        $pregun_Lsql = "SELECT PREGUN_ConsInte__b, PREGUN_Tipo______b FROM ".$BaseDatos_systema.".PREGUN WHERE PREGUN_ConsInte__GUION__b = ".$_POST['id']."";
        $res_PregunLsql = $mysqli->query($pregun_Lsql);
        while ($res = $res_PregunLsql->fetch_object()) {
            /* recorremos todos los campos del guion */
            if($res->PREGUN_Tipo______b == '5' || $res->PREGUN_Tipo______b == '10'){
                /* es de tipo Fecha u Hora y toca ponerle dateTime */
                $create_Lsql .= ",".$generarA."_C".$res->PREGUN_ConsInte__b." datetime DEFAULT NULL";
            }

            if($res->PREGUN_Tipo______b == '3' || $res->PREGUN_Tipo______b == '6' || $res->PREGUN_Tipo______b == '11'){
                /* la pregunta es Entero, Lista o Guion que guardan los Ids */
                $create_Lsql .= ",".$generarA."_C".$res->PREGUN_ConsInte__b." int(10) DEFAULT NULL";
            } 

            if($res->PREGUN_Tipo______b == '4'){
                /* la pregunta es Decimal */
                $create_Lsql .= ",".$generarA."_C".$res->PREGUN_ConsInte__b."  double DEFAULT NULL";
            } 

            if($res->PREGUN_Tipo______b == '1'){
                /* es de tipo Varchar */
                $create_Lsql .= ",".$generarA."_C".$res->PREGUN_ConsInte__b." varchar(253) CHARSET utf8 COLLATE utf8_unicode_ci DEFAULT NULL";
            }

            if($res->PREGUN_Tipo______b == '2'){
                /* es de tipo Memo */
                $create_Lsql .= ",".$generarA."_C".$res->PREGUN_ConsInte__b." longtext CHARSET utf8 COLLATE utf8_unicode_ci DEFAULT NULL";
            }


            if($res->PREGUN_Tipo______b == '8'){
                /* es de tipo CheckBox */
                $create_Lsql .= ",".$generarA."_C".$res->PREGUN_ConsInte__b." smallint(5) DEFAULT NULL";
            }
        }

        $create_Lsql .= ",PRIMARY KEY (".$generarA."_ConsInte__b)) ENGINE=InnoDB AUTO_INCREMENT=0 ;";

        if($mysqli->query($create_Lsql) === true){
            $Lsql_Editar_Guion = "UPDATE ".$BaseDatos_systema.".PREGUN SET PREGUN_FueGener_b = 0 WHERE PREGUN_ConsInte__GUION__b = ".$_POST['id'];
            $mysqli->query($Lsql_Editar_Guion);

            //generar_tablas_bd($_POST['id']);
            echo '1';
        }else{
            echo $mysqli->error;
        }

    }


    function crearSeccionesBD($ultimoGuion, $generarOno = null,$tipoGuion=0){
        global $mysqli;
        global $BaseDatos;
        global $BaseDatos_systema;
        global $BaseDatos_telefonia;
        global $dyalogo_canales_electronicos;
        global $BaseDatos_general;

        //Seccio control
        $Lsql_Control = "INSERT INTO ".$BaseDatos_systema.".G7(G7_C60, G7_C38, G7_C36 , G7_C34, G7_C33, G7_C35) VALUES(".$ultimoGuion.", 4, 3, 2, 'CONTROL', 1)";



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


        if($generarOno != null){
            generar_tablas_bd($ultimoGuion, 1 , 1 , 1 , 1,$tipoGuion);
        }
       
    }

    function crearSecciones($ultimoGuion, $nombre, $generarOno = null,$tipoGuion=0){

        global $mysqli;
        global $BaseDatos;
        global $BaseDatos_systema;
        global $BaseDatos_telefonia;
        global $dyalogo_canales_electronicos;
        global $BaseDatos_general;
        //* una vez creada la tabla procedemos a generar lo que toca generar */
       // include(__DIR__."../../../../generador/generar_tablas_bd.php");
        /* 
            El tipo de Guión debe ser script.
            Primero debemos crear la seccion Tipificación 
        */
        
        /* Ahora toca crear los campos de la tipificacion */
        $int_Tipificacion_campo = 0;
        $int_Reintento_campo = 0;
        $int_Fecha_Agenda_campo = 0;
        $int_Hora_Agenda_campo = 0;
        $int_Observacion_campo = 0;   
        $isBackofice = 0;

        
        $Lsql_Tipificacion = "INSERT INTO ".$BaseDatos_systema.".G7(G7_C60, G7_C38, G7_C36 , G7_C34, G7_C33) VALUES(".$ultimoGuion.", 3, 1, 4, 'TIPIFICACION')";
        
        if($mysqli->query($Lsql_Tipificacion) === true){
            
            $tipificacion = $mysqli->insert_id;
            
            /* priemro creamos la lista de las tipifiaciones */
            $insertLsql = "INSERT INTO ".$BaseDatos_systema.".OPCION (OPCION_ConsInte__GUION__b, OPCION_Nombre____b, OPCION_ConsInte__PROYEC_b, OPCION_FechCrea__b, OPCION_UsuaCrea__b) VALUES (".$ultimoGuion.", 'Tipificaciones - ".$nombre."', ".$_SESSION['HUESPED'].", '".date('Y-m-d H:s:i')."', ".$_SESSION['IDENTIFICACION'].");";
            //MONOEF_Texto_____b , MONOEF_Contacto__b,  MONOEF_TipNo_Efe_b, LISOPC_Clasifica_b
            $tamanho = 7;
            $array = array(
                    array('No contesta', 4, 1, 3, 6),
                    array('Ocupado', 4, 1 , 4, 2),
                    array('Fallida', 2, 3 , 5, 0),
                    array('No lo conocen', 5, 3 , 0, 0),
                    array('Llamar luego', 6, 2, 0, 6),
                    array('No exitoso ', 6, 3, 0, 0),
                    array('Exitoso', 7, 3, 0, 0)
                );
            $Lsql = "SELECT G5_C29 FROM ".$BaseDatos_systema.".G5 WHERE G5_ConsInte__b = ".$ultimoGuion;
            $resX = $mysqli->query($Lsql);
            if($resX){
                $dat = $resX->fetch_array();
                if($dat['G5_C29'] == '4'){
                    $isBackofice = 1; 
                    $array = array(
                        array('Devolver tarea', 6, 2, 0, 6),
                        array('Cerrar tarea', 6, 3, 0, 0),
                        array('Gestión realizada', 7, 3, 0, 0)
                    );
                    $tamanho = 3;
                }
            }
           
            $ultimoLista = 0;
            if($mysqli->query($insertLsql) === true){
                /* Se inserto la lista perfectamente */
                $ultimoLista = $mysqli->insert_id;
                /* toca meterlo en MONOEF */
                /* Primero lo pirmero crear el MonoEf */

                for ($i=0; $i <  $tamanho ; $i++) { 

                    $MONOEFLsql = "INSERT INTO ".$BaseDatos_systema.".MONOEF (MONOEF_Texto_____b, MONOEF_EFECTIVA__B, MONOEF_TipNo_Efe_b, MONOEF_Importanc_b, MONOEF_FechCrea__b, MONOEF_UsuaCrea__b, MONOEF_Contacto__b, MONOEF_CanHorProxGes__b) VALUES ('".$array[$i][0]."','0', '".$array[$i][2]."', '".($i+1)."' , '".date('Y-m-d H:s:i')."', ".$_SESSION['IDENTIFICACION'].", '".$array[$i][1]."' , '".$array[$i][4]."')";

                    if($mysqli->query($MONOEFLsql) === true){
                        $monoefNew = $mysqli->insert_id;
                        /* ahora si lo insertamos en el LISOPC */
                        $insertLisopc = "INSERT INTO ".$BaseDatos_systema.".LISOPC (LISOPC_Nombre____b, LISOPC_ConsInte__OPCION_b, LISOPC_Posicion__b, LISOPC_Clasifica_b) VALUES ('".$array[$i][0]."', ".$ultimoLista.", 0, ".$monoefNew.");";
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


            
            $Lsql_Tipificacion_campo = "INSERT INTO ".$BaseDatos_systema.".G6(G6_C39, G6_C40, G6_C51, G6_C32, G6_C207, G6_C209, G6_C44) VALUES ('Tipificación', 6, 1, ".$tipificacion.", ".$ultimoGuion.", 1, ".$ultimoLista.");";
            if($mysqli->query($Lsql_Tipificacion_campo) === true){
                $int_Tipificacion_campo = $mysqli->insert_id;
            }
            
            if($isBackofice  == 0){
                $Lsql_Reintento_campo = "INSERT INTO ".$BaseDatos_systema.".G6(G6_C39, G6_C40, G6_C51, G6_C32, G6_C207, G6_C209) VALUES ('Reintento', 6, 1, ".$tipificacion.", ".$ultimoGuion.", 1);";
                if($mysqli->query($Lsql_Reintento_campo) === true){
                    $int_Reintento_campo = $mysqli->insert_id;
                }
            }else{
                /*Toca crear el estado de la tarea*/
                $array = array(
                    array('Sin gestión'),
                    array('En gestión'),
                    array('En gestión por devolución'),
                    array('Cerrada'),
                    array('Devuelta')
                );
                $tamanho = 5;
                /*Insertamos el OPCION */
                $insertLsql = "INSERT INTO ".$BaseDatos_systema.".OPCION (OPCION_ConsInte__GUION__b, OPCION_Nombre____b, OPCION_ConsInte__PROYEC_b, OPCION_FechCrea__b, OPCION_UsuaCrea__b) VALUES (".$ultimoGuion.", 'ESTADO_TAREA - ".$nombre."', ".$_SESSION['HUESPED'].", '".date('Y-m-d H:s:i')."', ".$_SESSION['IDENTIFICACION'].");";
                if($mysqli->query($insertLsql) === true){
                    /* Se inserto la lista perfectamente */
                    $ultimoLista = $mysqli->insert_id;
                    for ($i=0; $i < $tamanho ; $i++) { 
                        $insertLisopc = "INSERT INTO ".$BaseDatos_systema.".LISOPC (LISOPC_Nombre____b, LISOPC_ConsInte__OPCION_b, LISOPC_Posicion__b) VALUES ('".$array[$i][0]."', ".$ultimoLista.", ".$i.");";
                        if($mysqli->query($insertLisopc) === true){
                     
                        }else{
                            echo $mysqli->error;
                        }
                    }

                    $Lsql_Reintento_campo = "INSERT INTO ".$BaseDatos_systema.".G6(G6_C39, G6_C40, G6_C51, G6_C32, G6_C207, G6_C209, G6_C44) VALUES ('ESTADO_TAREA', 6, 1, ".$tipificacion.", ".$ultimoGuion.", 1, ".$ultimoLista.");";
                    if($mysqli->query($Lsql_Reintento_campo) === true){
                        $int_Reintento_campo = $mysqli->insert_id;
                    }

                    $Lsql_Paso_campo = "INSERT INTO ".$BaseDatos_systema.".G6(G6_C39, G6_C40, G6_C51, G6_C32, G6_C207, G6_C209) VALUES ('PASO_ID', 3, 1, ".$tipificacion.", ".$ultimoGuion.", 1);";
                    if($mysqli->query($Lsql_Paso_campo) === true){
                        
                    }


                    $Lsql_id_registro_campo = "INSERT INTO ".$BaseDatos_systema.".G6(G6_C39, G6_C40, G6_C51, G6_C32, G6_C207, G6_C209) VALUES ('REGISTRO_ID', 3, 1, ".$tipificacion.", ".$ultimoGuion.", 1);";
                    if($mysqli->query($Lsql_id_registro_campo) === true){
                        
                    }

                }else{
                    echo $mysqli->error;
                }
                
                
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
        $Lsql_Control = "INSERT INTO ".$BaseDatos_systema.".G7(G7_C60, G7_C38, G7_C36 , G7_C34, G7_C33, G7_C35) VALUES(".$ultimoGuion.", 4, 3, 2, 'CONTROL', 2)";

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

        //Seccio coonversacion
        if($isBackofice  == 0){
            $Lsql_Converacion = "INSERT INTO ".$BaseDatos_systema.".G7(G7_C60, G7_C38, G7_C36 , G7_C34, G7_C33, G7_C35) VALUES(".$ultimoGuion.", 1, 3, 5, 'CONVERSACION', 1)";

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
            }
        
            
        }

        if($generarOno != null){
            generar_tablas_bd($ultimoGuion, 1 , 1 , 0 , 0,$tipoGuion);
        }
        
    }

    function recortarCampos(array $data):array
    {
        global $mysqli;
        $fallas=array();
        foreach($data as $key){
            $sql="UPDATE DYALOGOCRM_WEB.{$key->tabla} SET {$key->tabla}_C{$key->campo}=LEFT({$key->tabla}_C{$key->campo},{$key->longitud}) WHERE LENGTH({$key->tabla}_C{$key->campo}) > {$key->longitud}";
            if(!$mysqli->query($sql)){
                array_push($fallas,$key->campo);
            }
        }

        return $fallas;
    }

?>
