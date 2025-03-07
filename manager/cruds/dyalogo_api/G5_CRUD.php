<?php
    session_start();
    ini_set('display_errors', 'On');
    ini_set('display_errors', 1);
    include(__DIR__."../../../../pages/conexion.php");

    /* una vez creada la tabla procedemos a generar lo que toca generar */
    include(__DIR__."../../../../generador/generar_tablas_bd.php");

    function guardar_auditoria($accion, $superAccion){
        include(__DIR__."../../../../pages/conexion.php");
        $str_Lsql = "INSERT INTO ".$BaseDatos_systema."AUACAD (AUACAD_Fecha_____b , AUACAD_Hora______b, AUACAD_Ejecutor__b, AUACAD_TipoAcci__b , AUACAD_SubTipAcc_b, AUACAD_Accion____b , AUACAD_Huesped___b ) VALUES ('".date('Y-m-d H:s:i')."', '".date('Y-m-d H:s:i')."', ".$_SESSION['IDENTIFICACION'].", 'G5', '".$accion."', '".$superAccion."', ".$_SESSION['HUESPED']." );";
        $mysqli->query($str_Lsql);
    }  

    if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
        //Funciones de la carga maestro y eso
        
        //Datos del formulario
        if(isset($_POST['CallDatos'])){
          
            $str_Lsql = 'SELECT G5_ConsInte__b, G5_C28 as principal , G5_C28,G5_C29,G5_C30,G5_C31,G5_C59 FROM '.$BaseDatos_systema.'.G5 WHERE G5_ConsInte__b ='.$_POST['id'];
            $result = $mysqli->query($str_Lsql);
            $datos = array();
            $i = 0;

            while($key = $result->fetch_object()){

                $datos[$i]['G5_C28'] = $key->G5_C28;

                $datos[$i]['G5_C29'] = $key->G5_C29;

                $datos[$i]['G5_C30'] = $key->G5_C30;

                $datos[$i]['G5_C31'] = $key->G5_C31;

                $datos[$i]['G5_C59'] = $key->G5_C59;
      
                $datos[$i]['principal'] = $key->principal;

                $datos[$i]['G5_ConsInte__b'] = $key->G5_ConsInte__b;

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
            $inicio = $_POST['inicio'];
            $fin = $_POST['fin'];
            if(isset($_POST['tipo'])){
                $Zsql = "SELECT G5_ConsInte__b as id, G5_C28 as camp1 , G5_C30 as camp2 FROM ".$BaseDatos_systema.".G5 WHERE G5_C29 = ".$_GET['tipo']."  ORDER BY G5_ConsInte__b DESC LIMIT 0, 50";
            }else{
                $Zsql = "SELECT G5_ConsInte__b as id, G5_C28 as camp1 , G5_C30 as camp2 FROM ".$BaseDatos_systema.".G5  ORDER BY G5_ConsInte__b DESC LIMIT 0, 50";    
            }
            
            //$Zsql = "SELECT  G5_ConsInte__b as id,  G5_C28 as camp1 , G5_C30 as camp2  FROM ".$BaseDatos_systema.".G5   ORDER BY G5_ConsInte__b DESC LIMIT ".$inicio." , ".$fin;
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
        
        //Inserciones o actualizaciones
        if(isset($_POST["oper"]) && isset($_GET['insertarDatosGrilla'])){
            $posible = true;
            if($_POST['oper'] == 'add'){
                $validarLsql = "SELECT GUION__ConsInte__b FROM ".$BaseDatos_systema.".GUION_ WHERE GUION__Nombre____b = '".$_POST['G5_C28']."' LIMIT 1";
                $res = $mysqli->query($validarLsql);

                if($res->num_rows > 0){
                    echo json_encode(array('code' => '-2'));
                    $posible = false;
                }
            }    

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
                            if($_POST['G5_C29'] == '1'){
                                
                                $Lsql_General = "INSERT INTO ".$BaseDatos_systema.".G7(G7_C60, G7_C38, G7_C36 , G7_C34, G7_C33, G7_C35) VALUES(".$ultimoGuion.", 1, 1, 1, 'GENERAL', 2)";
                                if($mysqli->query($Lsql_General) === true){
                                    
                                    $general = $mysqli->insert_id;
                                    
                                    if(isset($_POST['GenerarFromExel'])){
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


                                crearSecciones($ultimoGuion, $_POST['G5_C28'], 1);

                            }else if($_POST['G5_C29'] == '2'){
                                /* seccion generar */
                                

                                $Lsql_General = "INSERT INTO ".$BaseDatos_systema.".G7(G7_C60, G7_C38, G7_C36 , G7_C34, G7_C33, G7_C35) VALUES(".$ultimoGuion.", 1, 1, 1, 'GENERAL', 2)";
                                if($mysqli->query($Lsql_General) === true){
                                    $general = $mysqli->insert_id;

                                    if(isset($_POST['GenerarFromExel'])){
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
                                }

                                /* Esto es para tener elcontrol de el optin y el origen */               
                                crearSeccionesBD($ultimoGuion, true);
                                /* aqui toca cargar la base de datos */
                                $Bdtraducir = $ultimoGuion;
                                if(isset($_POST['CrearScript'])){
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
                                                }else{
                                                    echo "Error Insertando Los Datos en la base ".$mysqli->error;
                                                }           
                                            }
                                        }
                                    }
                                }


                            }else if($_POST['G5_C29'] == '3'){
                                $Lsql_General = "INSERT INTO ".$BaseDatos_systema.".G7(G7_C60, G7_C38, G7_C36 , G7_C34, G7_C33, G7_C35) VALUES(".$ultimoGuion.", 1, 1, 1, 'GENERAL', 2)";
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
                                }

                                /* Esto es para tener elcontrol de el optin y el origen */               
                                generar_tablas_bd($ultimoGuion, 1, 1,  0, 0);
                                /* aqui toca cargar la base de datos */
                                $Bdtraducir = $ultimoGuion;
                                if(isset($_POST['CrearScript'])){
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
                                                }else{
                                                    echo "Error Insertando Los Datos en la base ".$mysqli->error;
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
                            if(isset($_POST['contador']) && $_POST['contador'] != 0){

                                for ($i=0; $i < $_POST['contador']; $i++) { 
                                    if(isset($_POST['generado_'.$i])){
                                        $ruta = NULL;
                                        if(isset($_FILES['txtfile_'.$i]['tmp_name']) && !empty($_FILES['txtfile_'.$i]['tmp_name']) ){
                                            $aleatorio = mt_rand(100, 999);

                                            if($_FILES['txtfile_'.$i]["type"] == "image/jpeg"){
                                                $aleatorio = $aleatorio.".jpg";
                                            }elseif($_FILES['txtfile_'.$i]["type"] == "image/png"){
                                                $aleatorio = $aleatorio.".png";
                                            }
                                            //creamos la ruta donde se va a guardar la imagen
                                            $ruta =  "/var/www/html/crm_php/assets/img/plantilla/".$_POST['id']."/".$aleatorio;
                                            /* Creamos el directorio */
                                            $directorio = "/var/www/html/crm_php/assets/img/plantilla/".$_POST['id']."/";
                                            if (!file_exists($directorio)) {
                                                mkdir($directorio, 0755);
                                            }

                                            copy($_FILES['txtfile_'.$i]['tmp_name'], $ruta);
                                            $ruta = $aleatorio;
                                        }

                                        $codigo = $mysqli->real_escape_string($_POST['txtCodigo_'.$i]);
                                        $Lsql_insert = "INSERT INTO ".$BaseDatos_systema.".GUION_WEBFORM (id_guion, titulo, color_fondo, color_letra, url_gracias, codigo_a_insertar, nombre_imagen, tipo_optin, tipo_gracias, id_dy_ce_configuracion, Asunto_mail, Cuerpo_mail, id_pregun) VALUES (".$_POST['id']." , '".$_POST['txtTitulo_'.$i]."' , '".$_POST['txtColor_'.$i]."' , '".$_POST['txtColorLetra_'.$i]."' , '".$_POST['txtUrl_'.$i]."', '".$codigo."', '".$ruta."' , '".$_POST['optin_'.$i]."', '".$_POST['extanas_'.$i]."', '".$_POST['cuenta_'.$i]."', '".$_POST['txtAsunto_'.$i]."', '".$_POST['txtCuerpoMensaje_'.$i]."' , '".$_POST['cmbCampoCorreo_'.$i]."')";
                                   
                                        if($mysqli->query($Lsql_insert) === true){

                                        }else{
                                            echo "Error creando conf web form => ".$mysqli->error;
                                        }
                                    }

                                   
                                }
                            }

                            if(isset($_POST['actuales'])){
                                for ($i=0; $i < $_POST['actuales']; $i++) { 
                                    if(isset($_POST['idFila_'.$i])){

                                        $ruta = $_POST['rutaactual_'.$i];
                                        if(isset($_FILES['txtfile_'.$i]['tmp_name']) && !empty($_FILES['txtfile_'.$i]['tmp_name']) ){
                                            $aleatorio = mt_rand(100, 999);

                                            if($_FILES['txtfile_'.$i]["type"] == "image/jpeg"){
                                                $aleatorio = $aleatorio.".jpg";
                                            }elseif($_FILES['txtfile_'.$i]["type"] == "image/png"){
                                                $aleatorio = $aleatorio.".png";
                                            }
                                            //creamos la ruta donde se va a guardar la imagen
                                            $ruta =  "/var/www/html/crm_php/assets/img/plantilla/".$_POST['id']."/".$aleatorio;
                                            /* Creamos el directorio */
                                            $directorio = "/var/www/html/crm_php/assets/img/plantilla/".$_POST['id']."/";
                                            if (!file_exists($directorio)) {
                                                mkdir($directorio, 0755);
                                            }

                                            copy($_FILES['txtfile_'.$i]['tmp_name'], $ruta);
                                            $ruta = $aleatorio;
                                        }

                                        $codigo = $mysqli->real_escape_string($_POST['txtCodigo_'.$i]);
                                        $Lsql_insert = "UPDATE ".$BaseDatos_systema.".GUION_WEBFORM  SET titulo =  '".$_POST['txtTitulo_'.$i]."' , color_fondo = '".$_POST['txtColor_'.$i]."', color_letra = '".$_POST['txtColorLetra_'.$i]."', url_gracias = '".$_POST['txtUrl_'.$i]."', codigo_a_insertar = '".$codigo."' , nombre_imagen = '".$ruta."' , tipo_optin = '".$_POST['optin_'.$i]."', tipo_gracias = '".$_POST['extanas_'.$i]."', id_dy_ce_configuracion = '".$_POST['cuenta_'.$i]."', Asunto_mail = '".$_POST['txtAsunto_'.$i]."', Cuerpo_mail = '".$_POST['txtCuerpoMensaje_'.$i]."' , id_pregun = ".$_POST['cmbCampoCorreo_'.$i]." WHERE id = ".$_POST['idFila_'.$i];
                                      
                                        if($mysqli->query($Lsql_insert) === true){

                                        }else{
                                            echo "Error creando conf web form => ".$mysqli->error;
                                        }
                                    }
                                }
                            }

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
                        }
                        echo json_encode(array('code' => $ultimoGuion));
                    } else {
                       // echo json_encode(array('code' => '-4' , 'messaje' => "Error Hacieno el proceso los registros Guion : " . $mysqli->error));
                        echo "-1";
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

            $Lsql = "SELECT id, titulo, color_fondo, color_letra, url_gracias, codigo_a_insertar, nombre_imagen, tipo_optin, tipo_gracias, id_dy_ce_configuracion, Asunto_mail, Cuerpo_mail FROM ".$BaseDatos_systema.".GUION_WEBFORM WHERE id_guion = ".$_POST['guion'];
            $res = $mysqli->query($Lsql);
            if($res->num_rows > 0){
                $cuantosVan = 0;
                while ($key = $res->fetch_object()) {
                    $cuerpo = "<div class='row' id='idGen_". $cuantosVan ."'>";

                    $cuerpo .= "<div class='col-md-3'>";
                    $cuerpo .= "<div class='form-group'>";
                    $cuerpo .= "<input type='text' class='form-control' placeholder='".$str_title_config__."' name='txtTitulo_". $cuantosVan ."' value='".$key->titulo."'>";
                    $cuerpo .= "</div>";
                    $cuerpo .= "</div>";
                    
                    $cuerpo .= "<div class='col-md-3'>";
                    $cuerpo .= "<div class='form-group'>";
                    $cuerpo .= "<div class='input-group my-colorpicker2'>";
                    $cuerpo .= "<input type='text' class='form-control' placeholder='".$str_colorF_config_."' name='txtColor_". $cuantosVan ."' id='txtColor_". $cuantosVan ."' value='".$key->color_fondo."'>";
                    $cuerpo .= "<div class='input-group-addon'>";
                    $cuerpo .= "<i style='background-color: ".$key->color_fondo.";'></i>";
                    $cuerpo .= "</div>";
                    $cuerpo .= "</div>";
                    $cuerpo .= "</div>";
                    $cuerpo .= "</div>";
                    
                    $cuerpo .= "<div class='col-md-3'>";
                    $cuerpo .= "<div class='form-group'>";
                    $cuerpo .= "<div class='input-group my-colorpicker2'>";
                    $cuerpo .= "<input type='text' class='form-control' placeholder='".$str_colorL_config_."' name='txtColorLetra_". $cuantosVan ."' id='txtColorLetra_". $cuantosVan ."' value='".$key->color_letra."'>";
                    $cuerpo .= "<div class='input-group-addon'>";
                    $cuerpo .= "<i style='background-color: ".$key->color_letra.";'></i>";
                    $cuerpo .= "</div>";
                    $cuerpo .= "</div>";
                    $cuerpo .= "</div>";
                    $cuerpo .= "</div>";

                    $cuerpo .= "<div class='col-md-3'>";
                    $cuerpo .= "<div class='form-group'>";
                    $cuerpo .= "<input type='file' class='form-control' name='txtfile_".$cuantosVan."'>";
                    $cuerpo .= "</div>";
                    $cuerpo .= "</div>";

                    $cuerpo .= "<div class='col-md-3'>";
                    $cuerpo .= "<div class='form-group'>";
                    $cuerpo .= "<select class='form-control optin2' name='optin_".$cuantosVan."' Xaja='".$cuantosVan."'>";
                    $cuerpo .= "<option value='0'>".$str_optin_config__."</option>";
                    $disabled = '';
                    if($key->tipo_optin == 1){
                        $cuerpo .= "<option value='1' selected>".$str_simple_row____."</option>";
                    }else{
                        $cuerpo .= "<option value='1'>".$str_simple_row____."</option>";
                    }
                    if($key->tipo_optin == 2){
                        $disabled = 'disabled';
                        $cuerpo .= "<option value='2' selected>".$str_doble_________."</option>";
                    }else{
                        $cuerpo .= "<option value='2'>".$str_doble_________."</option>";
                    }
                    $cuerpo .= "</select>";
                    $cuerpo .= "</div>";
                    $cuerpo .= "</div>";


                    $cuerpo .= "<div class='col-md-3'>";
                    $cuerpo .= "<div class='form-group'>";
                    $cuerpo .= "<select disabled  class='form-control' ".$disabled." name='cuenta_".$cuantosVan."' id='cuenta_".$cuantosVan."'>";


                    $Lsql_2 = "SELECT * FROM ".$dyalogo_canales_electronicos.".dy_ce_configuracion;";
                    $cur_result = $mysqli->query($Lsql_2);
                    while ($key2 = $cur_result->fetch_object()) {
                        if($key->id_dy_ce_configuracion == $key2->id){
                            $cuerpo .= "<option value='".$key2->id."' selected >".$key2->direccion_correo_electronico."</option>";
                        }else{
                            $cuerpo .= "<option value='".$key2->id."'>".$key2->direccion_correo_electronico."</option>";
                        }
                    }
                    $cuerpo .= "</select>";
                    $cuerpo .= "</div>";
                    $cuerpo .= "</div>";


                    $cuerpo .= "<div class='col-md-3'>";
                    $cuerpo .= "<div class='form-group'>";
                    $cuerpo .= "<input disabled type='text' class='form-control' ".$disabled." name='txtAsunto_".$cuantosVan."'  id='txtAsunto_".$cuantosVan."' placeholder='".$str_asunto________."' value='".$key->Asunto_mail."'>";
                    $cuerpo .= "</div>";
                    $cuerpo .= "</div>";


                    $cuerpo .= "<div class='col-md-3'>";
                    $cuerpo .= "<div class='form-group'>";
                    $cuerpo .= "<select disabled type='text' class='form-control' ".$disabled." name='cmbCampoCorreo_".$cuantosVan."'  id='cmbCampoCorreo_".$cuantosVan."' placeholder='".$str_asunto________."'>";
                    $Lsql = "SELECT  G6_ConsInte__b as id , G6_C39 FROM ".$BaseDatos_systema.".G6 JOIN ".$BaseDatos_systema.".G7 ON G6_C32 = G7_ConsInte__b WHERE (G6_C40 != 12 AND G6_C40 != 9) AND G6_C207 = ".$_POST['guion']." AND G7_C38 = 1 AND G6_C209 != 3;";
        
                    $res_Lsql = $mysqli->query($Lsql);
                    while ($key3 = $res_Lsql->fetch_object()) {
                        $cuerpo .=  "<option value='".$key3->id."'>".utf8_encode($key3->G6_C39)."</option>";
                    }
                    $cuerpo .= "</select>";
                    $cuerpo .= "</div>";
                    $cuerpo .= "</div>";


                    $cuerpo .= "<div class='col-md-12'>";
                    $cuerpo .= "<div class='form-group'>";
                    $cuerpo .= "<textarea disabled  style='overflow:auto;resize:none' class='form-control' ".$disabled." name='txtCuerpoMensaje_".$cuantosVan."'  id='txtCuerpoMensaje_".$cuantosVan."' placeholder='".$str_cuerpo________."'>".$key->Cuerpo_mail."</textarea>";
                    $cuerpo .= "</div>";
                    $cuerpo .= "</div>";

                    $cuerpo .= "<div class='col-md-4'>";
                    $cuerpo .= "<div class='form-group'>";
                    $cuerpo .= "<select class='form-control externa2' name='extanas_".$cuantosVan."' Xaja='".$cuantosVan."'>";
                    $cuerpo .= "<option value='0'>".$str_url_salida____."</option>";
                    $disabled = '';
                    if($key->tipo_gracias == 1){
                        $disabled = 'disabled';
                        $cuerpo .= "<option value='1' selected>".$str_externa_______."</option>";
                    }else{
                        $cuerpo .= "<option value='1'>".$str_externa_______."</option>";
                    }
                    if($key->tipo_gracias == 1){
                        $cuerpo .= "<option value='2' selected>".$str_interna_row___."</option>";
                    }else{
                        $cuerpo .= "<option value='2'>".$str_interna_row___."</option>";
                    }
                    $cuerpo .= "</select>";
                    $cuerpo .= "</div>";
                    $cuerpo .= "</div>";


                    $cuerpo .= "<div class='col-md-3'>";
                    $cuerpo .= "<div class='form-group'>";
                    $cuerpo .= "<input type='text' class='form-control' ".$disabled." placeholder='".$str_Url_config____."' name='txtUrl_". $cuantosVan ."' value='".$key->url_gracias."'>";
                    $cuerpo .= "</div>";
                    $cuerpo .= "</div>";

                    

                    $cuerpo .= "<div class='col-md-3'>";
                    $cuerpo .= "<div class='form-group'>";
                    $cuerpo .= "<input type='text' class='form-control' placeholder='".$str_texto_config__."' name='txtCodigo_". $cuantosVan ."' value='".str_replace("'", '"' ,$key->codigo_a_insertar)."'>";
                    $cuerpo .= "</div>";
                    $cuerpo .= "</div>";

                   

                    $cuerpo .= "<div class='col-md-2'>";
                    $cuerpo .= "<button class='btn btn-sm btn-danger dleteTiposWebF' fila='".$cuantosVan."' type='button' id='". $key->id ."' title='".$str_delete_row____."'><i class='fa fa-trash'></i></button>&nbsp;<a target='_blank' class='btn btn-primary btn-sm' href='http://".$_SERVER["HTTP_HOST"]."/crm_php/web_forms.php?web=".base64_encode($_POST['guion'])."&v=".$key->id."' title='".$str_abrir_url_____."''><i class='fa fa-external-link'></i></a>";
                    $cuerpo .= "</div>";


                    $cuerpo .= "</div>";

                    $cuerpo .= "<input type='hidden' name='idFila_".$cuantosVan."' value='".$key->id."'>";
                    $cuerpo .= "<input type='hidden' name='rutaactual_".$cuantosVan."' value='".$key->nombre_imagen."'>";
                    $cuerpo .= "<hr/>";
                    $cuantosVan++;
                    echo $cuerpo;
                }
                echo "<input type='hidden' name='actuales' value='".$cuantosVan."'>";
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

        /* generar la tabla en la base de datos */
        if(isset($_GET['generarGuion']) && isset($_POST['generar'])){
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
            }

            if(isset($_POST['checkWebForm'])){
                $generar_web_form = 1;
            }

            generar_tablas_bd($_POST['id'], $generar_tabla ,$generar_formulario, $generar_busqueda, $generar_web_form );
            echo '1';
            
        }

        /* insertar las listas en la base de datos */
        if(isset($_GET["insertarDatosLista"])){
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
                    if(isset($_POST['opciones'])){
                        foreach ($_POST['opciones'] as $key) {
                            $cuantoshay++;
                            if(!$tipificacion){
                                /* solo lo metemos a LISOP */
                                $insertLisopc = "INSERT INTO ".$BaseDatos_systema.".LISOPC (LISOPC_Nombre____b, LISOPC_ConsInte__OPCION_b, LISOPC_Posicion__b) VALUES ('".$key."', ".$ultimoLista.", 0);";
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
            }else{
                $insertLsql = "UPDATE ".$BaseDatos_systema.".OPCION SET OPCION_ConsInte__GUION__b = ".$_POST['idGuion'].", OPCION_Nombre____b = '".$_POST['txtNombreLista']."' WHERE OPCION_ConsInte__b = ".$_POST['idListaE'];
                if($mysqli->query($insertLsql) === true){
                    /* Se inserto la lista perfectamente */
                    $ultimoLista = $_POST['idListaE'];
                    /* procedemos a insertar las opciones de la lista */
                    
                    if(isset($_POST['contadorEditables'])){
                        for($i = 0; $i < $_POST['contadorEditables']; $i++){ 
                            //($_POST['opcionesEditar'] as $key) {
                            if(isset($_POST['opcionesEditar_'.$i])){
                               /* solo lo metemos a LISOP */
                                $insertLisopc = "UPDATE ".$BaseDatos_systema.".LISOPC SET  LISOPC_Nombre____b = '".$_POST['opcionesEditar_'.$i]."'WHERE LISOPC_ConsInte__b = ".$_POST['hidIdOpcion_'.$i];
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
                    if(isset($_POST['opciones'])){
                        foreach ($_POST['opciones'] as $key) {
                            $cuantoshay++;
                         
                            /* solo lo metemos a LISOP */
                            $insertLisopc = "INSERT INTO ".$BaseDatos_systema.".LISOPC (LISOPC_Nombre____b, LISOPC_ConsInte__OPCION_b, LISOPC_Posicion__b) VALUES ('".$key."', ".$ultimoLista.", 0);";
                            if($mysqli->query($insertLisopc) === true){
                                $correcto++;  
                            }else{
                                echo $mysqli->error;
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

        if(isset($_POST['getListasDelSistema'])){
            $str_Lsql = "SELECT  OPCION_ConsInte__b as id , OPCION_Nombre____b as G8_C45 FROM ".$BaseDatos_systema.".OPCION WHERE OPCION_ConsInte__PROYEC_b = ".$_SESSION['HUESPED']." ORDER BY OPCION_Nombre____b ASC;";
            echo '<option value="0">Seleccione</option>';
            $combo = $mysqli->query($str_Lsql);
            while($obj = $combo->fetch_object()){
                echo "<option value='".$obj->id."' dinammicos='0'>".($obj->G8_C45)."</option>";
            } 
        }


        if(isset($_POST['getGuionesDelSistema'])){
            $str_Lsql = "SELECT  G5_ConsInte__b as id , G5_C28 FROM ".$BaseDatos_systema.".G5 WHERE G5_C316 = ".$_SESSION['HUESPED']." AND G5_C29 != 1  ORDER BY G5_C28 ASC;";
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
            $numero = $id;
            $SQL = "SELECT G7_ConsInte__b, G7_C33, G7_C34, G7_C35, G7_C36, G7_C37, G7_C38 FROM ".$BaseDatos_systema.".G7 ";
            $SQL .= " WHERE G7_C60 = ".$numero." AND G7_C38 = 1 "; 
            $SQL .= " ORDER BY G7_C34 ASC";
            $result = $mysqli->query($SQL);
            $i = 0;
            while( $fila = $result->fetch_object() ) {
                echo '<div class="panel box box-primary" id="seccion_'.$fila->G7_ConsInte__b.'">
                        <div class="box-header with-border">
                            <h4 class="box-title">
                                <i class="fa fa-arrows-v"></i>&nbsp;'.$fila->G7_C33.'
                            </h4>
                            <div class="box-tools pull-right">
                                <button type="button" class="btn btn-primary btn-sm btnEditarSeccion" valorSeccion="'.$fila->G7_ConsInte__b.'" >
                                    <i class="fa fa-edit"></i>
                                </button>
                                <button type="button" class="btn btn-danger btn-sm btnEliminarSeccion" valorSeccion="'.$fila->G7_ConsInte__b.'" >
                                    <i class="fa fa-trash-o"></i>
                                </button>
                            </div>
                        </div>
                    </div>';
            }

        }

        if(isset($_POST['getDatosSeccionesEdicion'])){
            $id = $_POST['id'];
            $SQL = "SELECT SECCIO_Nombre____b, SECCIO_VistPest__b, SECCIO_NumColumnas_b, SECCIO_PestMini__b FROM ".$BaseDatos_systema.".SECCIO WHERE SECCIO_ConsInte__b = ".$id; 
            $res = $mysqli->query($SQL);
            if($res->num_rows > 0){
                $datoSeccion = $res->fetch_array();

                $Lsql = "SELECT PREGUN_Texto_____b, PREGUN_ConsInte__b, PREGUN_IndiBusc__b, PREGUN_ConsInte__OPCION_B, PREGUN_Tipo______b FROM ".$BaseDatos_systema.".PREGUN WHERE PREGUN_ConsInte__SECCIO_b = ".$_POST['id']." AND PREGUN_FueGener_b != 3 ORDER BY PREGUN_Texto_____b ASC";

                $res_L = $mysqli->query($Lsql);
                $datosArray = array();
                $i = 0;
                while ($key = $res_L->fetch_object()) {
                    $datosArray[$i]['PREGUN_Texto_____b'] = $key->PREGUN_Texto_____b;
                    $datosArray[$i]['PREGUN_ConsInte__b'] = $key->PREGUN_ConsInte__b;
                    $datosArray[$i]['PREGUN_IndiBusc__b'] = $key->PREGUN_IndiBusc__b;
                    $datosArray[$i]['PREGUN_ConsInte__OPCION_B'] = $key->PREGUN_ConsInte__OPCION_B;
                    $datosArray[$i]['PREGUN_Tipo______b'] = $key->PREGUN_Tipo______b;
                    $i++;
                }

                echo json_encode(array('code' => 1, 'sec_nom' => $datoSeccion['SECCIO_Nombre____b'] , "sec_Tip" => $datoSeccion['SECCIO_VistPest__b'] , 'sec_num_col' => $datoSeccion['SECCIO_NumColumnas_b']  , 'sec_mini' => $datoSeccion['SECCIO_PestMini__b'] ,'datosPregun' => $datosArray));

            }else{
                echo json_encode(array('code' => 0));
            }   
        }

        if(isset($_POST['insertarSecciones'])){

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

                $str_LsqlI .= $separador." G7_C37";
                $str_LsqlV .= $separador."0";

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
                $str_LsqlV .= $separador."'1'";
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
                 //echo $str_Lsql;
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
                                if(isset($_POST["G6_C51_".$i])){
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
                                if(isset($_POST["G6_C42_".$i]) && $_POST["G6_C42_".$i] != '0'){
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
                          
                                if(isset($_POST["G6_C44_".$i]) && $_POST["G6_C44_".$i] != '0'){
                                    $separador = "";
                                    if($validar == 1){
                                        $separador = ",";
                                    }

                                    $str_LsqlU .= $separador."G6_C44 = '".$_POST["G6_C44_".$i]."'";
                                    $str_LsqlI .= $separador."G6_C44";
                                    $str_LsqlV .= $separador."'".$_POST["G6_C44_".$i]."'";
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
                                if(isset($_POST["G6_C46_".$i])){
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
                                    echo $str_Lsql;
                                    if ($mysqli->query($str_Lsql) === TRUE) {
                                        $ultimo = $mysqli->insert_id;
                                            
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

                                                        $LsqlGuidet = 'INSERT INTO '.$BaseDatos_systema.".GUIDET (GUIDET_ConsInte__GUION__Mae_b, GUIDET_ConsInte__GUION__Det_b, GUIDET_Nombre____b, GUIDET_ConsInte__PREGUN_Ma1_b, GUIDET_ConsInte__PREGUN_De1_b, GUIDET_Modo______b, GUIDET_ConsInte__PREGUN_Cre_b) VALUES (".$_POST["padre"].", ".$_POST['G6_C43_'.$i]." , '".$_POST['G6_C39_'.$i] ."' , ".$_POST["GuidetM_".$i]." , ".$_POST["Guidet_".$i].", ".$modoGrilla.", ".$ultimo.")";
                                                    }else{
                                                        $LsqlGuidet = 'INSERT INTO '.$BaseDatos_systema.".GUIDET (GUIDET_ConsInte__GUION__Mae_b, GUIDET_ConsInte__GUION__Det_b, GUIDET_Nombre____b, GUIDET_ConsInte__PREGUN_De1_b, GUIDET_Modo______b, GUIDET_ConsInte__PREGUN_Cre_b) VALUES (".$_POST["padre"].", ".$_POST['G6_C43_'.$i]." , '".$_POST['G6_C39_'.$i]."' ,  ".$_POST["Guidet_".$i].", ".$modoGrilla.", ".$ultimo.")";
                                                    }  

                                                    if($mysqli->query($LsqlGuidet) === TRUE){

                                                    }else{
                                                        echo "Error Guidet".$mysqli->error;
                                                    }
                                                    
                                                }
                                            }
                                        }

                                        /* definimos los campos que se van a mostrar en el combo */

                                        if(isset($_POST['camposGuion_'.$i])){
                                            foreach ($_POST['camposGuion_'.$i] as $key) {
                                                /* Lo pirmero es obtener el id del campo */
                                                $campoLsql = "SELECT CAMPO__ConsInte__b FROM ".$BaseDatos_systema.".CAMPO_ WHERE CAMPO__ConsInte__PREGUN_b = ".$key;
                                                $rsCampo = $mysqli->query($campoLsql);
                                                $campo = $rsCampo->fetch_array();

                                                /* ya tengo el id del campo lo voy a meter sencillo sin salto ni mucho menos */
                                                $preguiLsql = "INSERT INTO ".$BaseDatos_systema.".PREGUI (PREGUI_ConsInte__PREGUN_b, PREGUI_ConsInte__CAMPO__b, PREGUI_Consinte__GUION__b, PREGUI_Consinte__CAMPO__GUI_B) VALUES (".$ultimo.",".$campo['CAMPO__ConsInte__b']." , ".$_POST['padre'].", 0)";
                                                if ($mysqli->query($preguiLsql) === TRUE) {
                                            
                                                }else{
                                                    echo "Error metiendo el valor en pregui ".$mysqli->error;
                                                }
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
                $str_LsqlI .= $separador."G7_C38 = '1'";
                $validar = 1;
            }
    
            $str_Lsql = $str_LsqlI." WHERE G7_ConsInte__b = ".$_POST['IdseccionEdicion'];
                   
            if($validar == 1){
                //echo $str_Lsql;
                if ($mysqli->query($str_Lsql) === TRUE) {
                    $UltimaSeccion = $mysqli->insert_id;
                    //si logramos editar la seccion procedemos a guardar los campos , nuevamente;
                     if(isset($_POST['contador']) && $_POST['contador'] != 0){
                        $varContador = $_POST['contador'] + 1;
                        for($i = 0; $i < $varContador; $i++){
                            if(isset($_POST["ConsInte_".$i]) || isset($_POST['nuevo_campo_'.$i])){
                               
                                $str_Lsql  = '';
                                $validar = 0;
                                $str_LsqlU = "UPDATE ".$BaseDatos_systema.".G6 SET ";
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
                                if(isset($_POST["G6_C51_".$i])){
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
                                if(isset($_POST["G6_C42_".$i]) && $_POST["G6_C42_".$i] != '0'){
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
                          
                                if(isset($_POST["G6_C44_".$i]) && $_POST["G6_C44_".$i] != '0'){
                                    $separador = "";
                                    if($validar == 1){
                                        $separador = ",";
                                    }

                                    $str_LsqlU .= $separador."G6_C44 = '".$_POST["G6_C44_".$i]."'";
                                    $str_LsqlI .= $separador."G6_C44";
                                    $str_LsqlV .= $separador."'".$_POST["G6_C44_".$i]."'";
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
                                if(isset($_POST["G6_C46_".$i])){
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
                                    
 
         
                                if(!isset($_POST['nuevo_campo_'.$i])){
                                    $str_Lsql = $str_LsqlU." WHERE G6_ConsInte__b = " .$_POST['ConsInte_'.$i]; 
                                }else{
                                     if($validar == 1){
                                        /* Primero es meter el orden en el que estan siendo generados */
                                        $Lsqls = "SELECT COUNT(*) as TOTAL FROM ".$BaseDatos_systema.".G6 WHERE G6_C32 = ".$_POST['IdseccionEdicion'];
                                        $resLsqls = $mysqli->query($Lsqls);
                                        $data = $resLsqls->fetch_array();

                                        $str_LsqlI .= ", G6_C317";
                                        $str_LsqlV .= ",".($data['TOTAL'] + 1);


                                        $numero = $_POST['IdseccionEdicion'];
                                                
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
                                    

                                
                                    $str_Lsql = $str_LsqlI." , G6_C209 ) ".$str_LsqlV." , 1)";
                                }        

                                if($validar == 1){
                                    echo $str_Lsql;
                                    if ($mysqli->query($str_Lsql) === TRUE) {
                                        $ultimo = $mysqli->insert_id;
                                        
                                        if(isset($_POST['nuevo_campo_'.$i])){
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

                                                            $LsqlGuidet = 'INSERT INTO '.$BaseDatos_systema.".GUIDET (GUIDET_ConsInte__GUION__Mae_b, GUIDET_ConsInte__GUION__Det_b, GUIDET_Nombre____b, GUIDET_ConsInte__PREGUN_Ma1_b, GUIDET_ConsInte__PREGUN_De1_b, GUIDET_Modo______b, GUIDET_ConsInte__PREGUN_Cre_b) VALUES (".$_POST["padre"].", ".$_POST['G6_C43_'.$i]." , '".$_POST['G6_C39_'.$i] ."' , ".$_POST["GuidetM_".$i]." , ".$_POST["Guidet_".$i].", ".$modoGrilla.", ".$ultimo.")";
                                                        }else{
                                                            $LsqlGuidet = 'INSERT INTO '.$BaseDatos_systema.".GUIDET (GUIDET_ConsInte__GUION__Mae_b, GUIDET_ConsInte__GUION__Det_b, GUIDET_Nombre____b, GUIDET_ConsInte__PREGUN_De1_b, GUIDET_Modo______b, GUIDET_ConsInte__PREGUN_Cre_b) VALUES (".$_POST["padre"].", ".$_POST['G6_C43_'.$i]." , '".$_POST['G6_C39_'.$i]."' ,  ".$_POST["Guidet_".$i].", ".$modoGrilla.", ".$ultimo.")";
                                                        }  

                                                        if($mysqli->query($LsqlGuidet) === TRUE){

                                                        }else{
                                                            echo "Error Guidet".$mysqli->error;
                                                        }
                                                        
                                                    }
                                                }
                                            }

                                            /* definimos los campos que se van a mostrar en el combo */
                                            if(isset($_POST['camposGuion_'.$i])){
                                                foreach ($_POST['camposGuion_'.$i] as $key) {
                                                    /* Lo pirmero es obtener el id del campo */
                                                    $campoLsql = "SELECT CAMPO__ConsInte__b FROM ".$BaseDatos_systema.".CAMPO_ WHERE CAMPO__ConsInte__PREGUN_b = ".$key;
                                                    $rsCampo = $mysqli->query($campoLsql);
                                                    $campo = $rsCampo->fetch_array();

                                                    /* ya tengo el id del campo lo voy a meter sencillo sin salto ni mucho menos */
                                                    $preguiLsql = "INSERT INTO ".$BaseDatos_systema.".PREGUI (PREGUI_ConsInte__PREGUN_b, PREGUI_ConsInte__CAMPO__b, PREGUI_Consinte__GUION__b, PREGUI_Consinte__CAMPO__GUI_B) VALUES (".$ultimo.",".$campo['CAMPO__ConsInte__b']." , ".$_POST['padre'].", 0)";
                                                    if ($mysqli->query($preguiLsql) === TRUE) {
                                                
                                                    }else{
                                                        echo "Error metiendo el valor en pregui ".$mysqli->error;
                                                    }
                                                }                           
                                            }
                                        }
                                        

                                        guardar_auditoria("INSERTAR", "INSERTAR REGISTRO EN G6");
                                        echo $ultimo;
                                            
                                    } else {
                                        echo "Error Hacieno el proceso los registros Edicion : " . $mysqli->error;
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
            $str_Lsql = "UPDATE ".$BaseDatos_systema.".PREGUN SET PREGUN_FueGener_b = 3 WHERE PREGUN_ConsInte__b = ".$_POST['id'];
            if($mysqli->query($str_Lsql) === true){
                echo "1";
            }else{
                echo "No se pudo Eliminar la pregunta";
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


    }

    function crearTabla($idGuion){
        include(__DIR__."../../../../pages/conexion.php");

        /* una vez creada la tabla procedemos a generar lo que toca generar */
        include(__DIR__."../../../../generador/generar_tablas_bd.php");   

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

            generar_tablas_bd($_POST['id']);
            echo '1';
        }else{
            echo $mysqli->error;
        }
    }


    function crearSeccionesBD($ultimoGuion, $generarOno = null){
        include(__DIR__."../../../../pages/conexion.php");

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
        }

        $Lsql_estado = "INSERT INTO ".$BaseDatos_systema.".G7(G7_C60, G7_C38, G7_C36 , G7_C34, G7_C33, G7_C35) VALUES(".$ultimoGuion.", 1, 1, 3, 'ESTADO', 1)";

        if($mysqli->query($Lsql_estado) === true){
            $estadi = $mysqli->insert_id;

            $Lsql_Agente_origen = "INSERT INTO ".$BaseDatos_systema.".PREGUN(PREGUN_Texto_____b, PREGUN_Tipo______b, PREGUN_IndiRequ__b, PREGUN_ConsInte__SECCIO_b, PREGUN_ConsInte__GUION__b, PREGUN_ContAcce__b, PREGUN_FueGener_b ) VALUES ('ORIGEN_DY_WF', 1, 0, ".$control.", ".$ultimoGuion.", 2, 1);";
            if($mysqli->query($Lsql_Agente_origen) === true){
                /* priemro creamos la lista de las tipifiaciones */
                $insertLsql = "INSERT INTO ".$BaseDatos_systema.".OPCION (OPCION_ConsInte__GUION__b, OPCION_Nombre____b, OPCION_ConsInte__PROYEC_b, OPCION_FechCrea__b, OPCION_UsuaCrea__b) VALUES (".$ultimoGuion.", 'ESTADO_DY_".$ultimoGuion."', ".$_SESSION['HUESPED'].", '".date('Y-m-d H:s:i')."', ".$_SESSION['IDENTIFICACION'].");";
                $ultimoLista = 0;
                if($mysqli->query($insertLsql) === true){
                    $ultimoLista = $mysqli->insert_id;
                    $array = array('Dato',  'No autoriza OPT-IN' , 'Autoriza OPT-IN', 'No contactable', 'No interesado', 'Interesado', 'No exitoso', 'Exitoso');

                    for ($i=0; $i < 8; $i++) {
                        $insertLisopc = "INSERT INTO ".$BaseDatos_systema.".LISOPC (LISOPC_Nombre____b, LISOPC_ConsInte__OPCION_b, LISOPC_Posicion__b) VALUES ('".$array[$i]."', ".$ultimoLista.", ".$i.");";
                        if($mysqli->query($insertLisopc) === true){
                         
                        }else{
                            echo $mysqli->error;
                        }
                    }

                    /* ahora insert la pregunta ESTADO_DY */
                    $Lsql_Estado_campo = "INSERT INTO ".$BaseDatos_systema.".G6(G6_C39, G6_C40, G6_C51, G6_C32, G6_C207, G6_C209, G6_C44) VALUES ('ESTADO_DY', 6, 1, ".$estadi.", ".$ultimoGuion.", 1, ".$ultimoLista.");";
                    if($mysqli->query($Lsql_Estado_campo) === true){
                        
                    }else{
                        echo "Error generando Estado ".$mysqli->error;
                    }
                }
            }
        }

        if($generarOno != null){
            generar_tablas_bd($ultimoGuion, 1 , 1 , 1 , 1 );
        }
       
    }

    function crearSecciones($ultimoGuion, $nombre, $generarOno = null){

        include(__DIR__."../../../../pages/conexion.php");
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
        
        $Lsql_Tipificacion = "INSERT INTO ".$BaseDatos_systema.".G7(G7_C60, G7_C38, G7_C36 , G7_C34, G7_C33) VALUES(".$ultimoGuion.", 3, 1, 4, 'TIPIFICACION')";
        
        if($mysqli->query($Lsql_Tipificacion) === true){
            
            $tipificacion = $mysqli->insert_id;
            
            /* priemro creamos la lista de las tipifiaciones */
            $insertLsql = "INSERT INTO ".$BaseDatos_systema.".OPCION (OPCION_ConsInte__GUION__b, OPCION_Nombre____b, OPCION_ConsInte__PROYEC_b, OPCION_FechCrea__b, OPCION_UsuaCrea__b) VALUES (".$ultimoGuion.", 'Tipificaciones - ".$nombre."', ".$_SESSION['HUESPED'].", '".date('Y-m-d H:s:i')."', ".$_SESSION['IDENTIFICACION'].");";
            //MONOEF_Texto_____b , MONOEF_Contacto__b,  MONOEF_TipNo_Efe_b, LISOPC_Clasifica_b
            $array = array(
                    array('No contesta', 3, 1, 3),
                    array('Ocupado', 3, 1 , 4),
                    array('Fallida', 3, 1 , 5),
                    array('No lo conocen', 4, 1 , 0),
                    array('Llamar luego ', 5, 2, 0),
                    array('No exitoso ', 5, 1, 0),
                    array('Exitoso', 6, 3, 0)
                );
            $ultimoLista = 0;
            if($mysqli->query($insertLsql) === true){
                /* Se inserto la lista perfectamente */
                $ultimoLista = $mysqli->insert_id;
                /* toca meterlo en MONOEF */
                /* Primero lo pirmero crear el MonoEf */
                for ($i=0; $i < 7; $i++) {
                    $MONOEFLsql = "INSERT INTO ".$BaseDatos_systema.".MONOEF (MONOEF_Texto_____b, MONOEF_EFECTIVA__B, MONOEF_TipNo_Efe_b, MONOEF_Importanc_b, MONOEF_FechCrea__b, MONOEF_UsuaCrea__b, MONOEF_Contacto__b) VALUES ('".$array[$i][0]."','0', '".$array[$i][2]."', '".($i+1)."' , '".date('Y-m-d H:s:i')."', ".$_SESSION['IDENTIFICACION'].", '".$array[$i][1]."')";

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

                $Lsql_Tipificacion_campo = "INSERT INTO ".$BaseDatos_systema.".G6(G6_C39, G6_C40, G6_C51, G6_C32, G6_C207, G6_C209, G6_C44) VALUES ('Tipificación', 6, 1, ".$tipificacion.", ".$ultimoGuion.", 1, ".$ultimoLista.");";
                if($mysqli->query($Lsql_Tipificacion_campo) === true){
                    $int_Tipificacion_campo = $mysqli->insert_id;



                }
            }else{

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
            $Lsql_Agente_campo = "INSERT INTO ".$BaseDatos_systema.".PREGUN(PREGUN_Texto_____b, PREGUN_Tipo______b, PREGUN_IndiRequ__b, PREGUN_ConsInte__SECCIO_b, PREGUN_ConsInte__GUION__b, PREGUN_Default___b , PREGUN_ContAcce__b, PREGUN_FueGener_b ) VALUES ('Agente', 1, 0, ".$control.", ".$ultimoGuion.", 102, 2, 1);";
            if($mysqli->query($Lsql_Agente_campo) === true){
            
            }

            $Lsql_fecha_campo = "INSERT INTO ".$BaseDatos_systema.".PREGUN(PREGUN_Texto_____b, PREGUN_Tipo______b, PREGUN_IndiRequ__b, PREGUN_ConsInte__SECCIO_b, PREGUN_ConsInte__GUION__b, PREGUN_Default___b , PREGUN_ContAcce__b , PREGUN_FueGener_b) VALUES ('Fecha', 1, 0, ".$control.", ".$ultimoGuion.", 501, 2, 1);";
            if($mysqli->query($Lsql_fecha_campo) === true){
            
            }


            $Lsql_hora_campo = "INSERT INTO ".$BaseDatos_systema.".PREGUN(PREGUN_Texto_____b, PREGUN_Tipo______b, PREGUN_IndiRequ__b, PREGUN_ConsInte__SECCIO_b, PREGUN_ConsInte__GUION__b, PREGUN_Default___b , PREGUN_ContAcce__b , PREGUN_FueGener_b) VALUES ('Hora', 1, 0, ".$control.", ".$ultimoGuion.", 1001, 2, 1);";
            if($mysqli->query($Lsql_hora_campo) === true){
            
            }

            $Lsql_campa_campo = "INSERT INTO ".$BaseDatos_systema.".PREGUN(PREGUN_Texto_____b, PREGUN_Tipo______b, PREGUN_IndiRequ__b, PREGUN_ConsInte__SECCIO_b, PREGUN_ConsInte__GUION__b, PREGUN_Default___b , PREGUN_ContAcce__b , PREGUN_FueGener_b) VALUES ('Campaña', 1, 0, ".$control.", ".$ultimoGuion.", 105, 2, 1);";
            if($mysqli->query($Lsql_campa_campo) === true){
            
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

        if($generarOno != null){
            generar_tablas_bd($ultimoGuion, 1 , 1, 0, 0);
        }
        
    }

?>
