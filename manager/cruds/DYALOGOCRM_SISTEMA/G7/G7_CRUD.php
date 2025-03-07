<?php
    session_start();
    ini_set('display_errors', 'On');
    ini_set('display_errors', 1);
    include(__DIR__."../../../../pages/conexion.php");
    date_default_timezone_set('America/Bogota');
    function guardar_auditoria($accion, $superAccion){
        global $mysqli;
        global $BaseDatos_systema;
        $str_Lsql = "INSERT INTO ".$BaseDatos_systema."AUACAD (AUACAD_Fecha_____b , AUACAD_Hora______b, AUACAD_Ejecutor__b, AUACAD_TipoAcci__b , AUACAD_SubTipAcc_b, AUACAD_Accion____b , AUACAD_Huesped___b ) VALUES ('".date('Y-m-d H:s:i')."', '".date('Y-m-d H:s:i')."', ".$_SESSION['IDENTIFICACION'].", 'G7', '".$accion."', '".$superAccion."', ".$_SESSION['HUESPED']." );";
        $mysqli->query($str_Lsql);
    }   

    if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
        //Funciones de la carga maestro y eso
        

        //Datos del formulario
        if(isset($_POST['CallDatos'])){
          
            $str_Lsql = 'SELECT G7_ConsInte__b, G7_C33 as principal ,G7_C33,G7_C34,G7_C35,G7_C36,G7_C37,G7_C38,G7_C60 FROM '.$BaseDatos_systema.'.G7 WHERE G7_ConsInte__b ='.$_POST['id'];
            $result = $mysqli->query($str_Lsql);
            $datos = array();
            $i = 0;

            while($key = $result->fetch_object()){

                $datos[$i]['G7_C33'] = $key->G7_C33;

                $datos[$i]['G7_C34'] = $key->G7_C34;

                $datos[$i]['G7_C35'] = $key->G7_C35;

                $datos[$i]['G7_C36'] = $key->G7_C36;

                $datos[$i]['G7_C37'] = $key->G7_C37;

                $datos[$i]['G7_C38'] = $key->G7_C38;

                $datos[$i]['G7_C60'] = $key->G7_C60;
      
                $datos[$i]['principal'] = $key->principal;
                $i++;
            }
            echo json_encode($datos);
        }

        //Datos de la lista de la izquierda
        if(isset($_POST['CallDatosJson'])){
            $str_Lsql = "SELECT G7_ConsInte__b as id,  G7_C33 as camp1 , G7_C35 as camp2 ";
            $str_Lsql .= " FROM ".$BaseDatos_systema.".G7   ";
            if($_POST['Busqueda'] != '' && !is_null($_POST['Busqueda'])){
                $str_Lsql .= " WHERE  like '%".$_POST['Busqueda']."%' ";
                $str_Lsql .= " OR  like '%".$_POST['Busqueda']."%' ";
            }

            $PEOBUS_VeRegPro__b = 0 ;
            $idUsuario = $_SESSION['IDENTIFICACION'];
            $peobus = "SELECT * FROM ".$BaseDatos_systema.".PEOBUS WHERE PEOBUS_ConsInte__USUARI_b = ".$idUsuario." AND PEOBUS_ConsInte__GUION__b = 7";
            $query = $mysqli->query($peobus);
            

            while ($key =  $query->fetch_object()) {
                $PEOBUS_VeRegPro__b = $key->PEOBUS_VeRegPro__b ;
            }

            if($PEOBUS_VeRegPro__b != 0){
                if($_POST['Busqueda'] != '' && !is_null($_POST['Busqueda'])){
                    $str_Lsql .= " AND G7_Usuario = ".$idUsuario;
                }else{
                    $str_Lsql .= " WHERE G7_Usuario = ".$idUsuario;
                }
        
            }

            $str_Lsql .= ' ORDER BY G7_ConsInte__b DESC LIMIT 0, 50'; 
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

        if(isset($_GET['CallDatosLisop_Tipos'])){
            $lista = $_GET['idLista'];
            $comboe = $_GET['campo'];
            echo '<select class="form-control input-sm"  name="'.$comboe.'" id="'.$comboe.'">';
                echo '<option value="0">Seleccione</option>';
                echo '<option value="1">Texto</option>';
                echo '<option value="2">Memo</option>';
                echo '<option value="3">Numerico</option>';
                echo '<option value="4">Decimal</option>';
                echo '<option value="5">Fecha</option>';
                echo '<option value="10">Hora</option>';
                echo '<option value="6">Lista</option>';
                echo '<option value="11">Guión</option>';
                echo '<option value="8">Casilla de verificación</option>';
                echo '<option value="9">Libreto / Label</option>';
          echo '<option value="0">Seleccione</option>';            
            echo '</select>'; 
        } 

        

        if(isset($_GET['CallDatosCombo_Guion_G7_C60'])){
            $Ysql = 'SELECT   G5_ConsInte__b as id , G5_C28 FROM ".$BaseDatos_systema.".G5';
            $guion = $mysqli->query($Ysql);
            echo '<select class="form-control input-sm"  name="G7_C60" id="G7_C60">';
            echo '<option value="0">NOMBRE</option>';
            while($obj = $guion->fetch_object()){
               echo "<option value='".$obj->id."' dinammicos='0'>".($obj->G5_C28)."</option>";
            } 
            echo '</select>';
        }


        if(isset($_GET['CallDatosCombo_Guion_G6_C32'])){
            $Ysql = 'SELECT   G7_ConsInte__b as id , G7_C33 FROM '.$BaseDatos_systema.'G7';
            $guion = $mysqli->query($Ysql);
            echo '<select class="form-control input-sm"  name="G6_C32" id="G6_C32">';
            echo '<option value="0">NOMBRE</option>';
            while($obj = $guion->fetch_object()){
               echo "<option value='".$obj->id."' dinammicos='0'>".($obj->G7_C33)."</option>";
            } 
            echo '</select>';
        }


        if(isset($_GET['CallDatosCombo_Guion_G6_C48'])){
            $Ysql = 'SELECT   G6_ConsInte__b as id , G6_C39 FROM '.$BaseDatos_systema.'G6';
            $guion = $mysqli->query($Ysql);
            echo '<select class="form-control input-sm"  name="G6_C48" id="G6_C48">';
            echo '<option value="0">TEXTO</option>';
            while($obj = $guion->fetch_object()){
               echo "<option value='".$obj->id."' dinammicos='0'>".($obj->G6_C39)."</option>";
            } 
            echo '</select>';
        }


        if(isset($_GET['CallDatosCombo_Guion_G6_C49'])){
            $Ysql = 'SELECT   G6_ConsInte__b as id , G6_C39 FROM '.$BaseDatos_systema.'G6';
            $guion = $mysqli->query($Ysql);
            echo '<select class="form-control input-sm"  name="G6_C49" id="G6_C49">';
            echo '<option value="0">TEXTO</option>';
            while($obj = $guion->fetch_object()){
               echo "<option value='".$obj->id."' dinammicos='0'>".($obj->G6_C39)."</option>";
            } 
            echo '</select>';
        }


        if(isset($_GET['CallDatosCombo_Guion_G6_C43'])){
            $Ysql = 'SELECT   G5_ConsInte__b as id , G5_C28 FROM '.$BaseDatos_systema.'G5';
            $guion = $mysqli->query($Ysql);
            echo '<select class="form-control input-sm"  name="G6_C43" id="G6_C43">';
            echo '<option value="0">NOMBRE</option>';
            while($obj = $guion->fetch_object()){
               echo "<option value='".$obj->id."' dinammicos='0'>".($obj->G5_C28)."</option>";
            } 
            echo '</select>';
        }


        if(isset($_GET['CallDatosCombo_Guion_G6_C44'])){
            $Ysql = 'SELECT   G8_ConsInte__b as id , G8_C45 FROM '.$BaseDatos_systema.'G8';
            $guion = $mysqli->query($Ysql);
            echo '<select class="form-control input-sm"  name="G6_C44" id="G6_C44">';
            echo '<option value="0">NOMBRE</option>';
            while($obj = $guion->fetch_object()){
               echo "<option value='".$obj->id."' dinammicos='0'>".($obj->G8_C45)."</option>";
            } 
            echo '</select>';
        }


        if(isset($_GET['CallDatosCombo_Guion_G6_C207'])){
            $Ysql = 'SELECT   G5_ConsInte__b as id , G5_C28 FROM '.$BaseDatos_systema.'G5';
            $guion = $mysqli->query($Ysql);
            echo '<select class="form-control input-sm"  name="G6_C207" id="G6_C207">';
            echo '<option value="0">NOMBRE</option>';
            while($obj = $guion->fetch_object()){
               echo "<option value='".$obj->id."' dinammicos='0'>".($obj->G5_C28)."</option>";
            } 
            echo '</select>';
        }


        if(isset($_POST['CallEliminate'])){
            if($_POST['oper'] == 'del'){
                $str_Lsql = "DELETE FROM ".$BaseDatos_systema.".G7 WHERE G7_ConsInte__b = ".$_POST['id'];
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
            $Zsql = "SELECT  G7_ConsInte__b as id,  G7_C33 as camp1 , G7_C35 as camp2  FROM ".$BaseDatos_systema.".G7   ORDER BY G7_ConsInte__b DESC LIMIT ".$inicio." , ".$fin;
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
             
  
            $G7_C34 = NULL;
            //este es de tipo numero no se deja ir asi '', si est avacio lo mejor es no mandarlo
            if(isset($_POST["G7_C34"])){
                if($_POST["G7_C34"] != ''){
                    $separador = "";
                    if($validar == 1){
                        $separador = ",";
                    }

                    //$G7_C34 = $_POST["G7_C34"];
                    $G7_C34 = str_replace(".", "", $_POST["G7_C34"]);
                    $G7_C34 =  str_replace(",", ".", $G7_C34);
                    $str_LsqlU .= $separador." G7_C34 = '".$G7_C34."'";
                    $str_LsqlI .= $separador." G7_C34";
                    $str_LsqlV .= $separador."'".$G7_C34."'";
                    $validar = 1;
                }
            }
  
            $G7_C35 = NULL;
            //este es de tipo numero no se deja ir asi '', si est avacio lo mejor es no mandarlo
            if(isset($_POST["G7_C35"])){
                if($_POST["G7_C35"] != ''){
                    $separador = "";
                    if($validar == 1){
                        $separador = ",";
                    }

                    //$G7_C35 = $_POST["G7_C35"];
                    $G7_C35 = str_replace(".", "", $_POST["G7_C35"]);
                    $G7_C35 =  str_replace(",", ".", $G7_C35);
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
                if($_POST["G7_C37"] == 'Yes'){
                    $G7_C37 = 1;
                }else if($_POST["G7_C37"] == 'off'){
                    $G7_C37 = 0;
                }else if($_POST["G7_C37"] == 'on'){
                    $G7_C37 = 1;
                }else if($_POST["G7_C37"] == 'No'){
                    $G7_C37 = 0;
                }else{
                    $G7_C37 = $_POST["G7_C37"] ;
                }   

                $separador = "";
                if($validar == 1){
                    $separador = ",";
                }

                $str_LsqlU .= $separador." G7_C37 = ".$G7_C37."";
                $str_LsqlI .= $separador." G7_C37";
                $str_LsqlV .= $separador.$G7_C37;

                $validar = 1;
            }else{
                $separador = "";
                if($validar == 1){
                    $separador = ",";
                }

                $str_LsqlU .= $separador." G7_C37 = ".$G7_C37."";
                $str_LsqlI .= $separador." G7_C37";
                $str_LsqlV .= $separador.$G7_C37;

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
             
  
            if(isset($_POST["G7_C60"])){
                $separador = "";
                if($validar == 1){
                    $separador = ",";
                }

                $str_LsqlU .= $separador."G7_C60 = '".$_POST["G7_C60"]."'";
                $str_LsqlI .= $separador."G7_C60";
                $str_LsqlV .= $separador."'".$_POST["G7_C60"]."'";
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


                    $str_LsqlU .= $separador." G7_C60 = ".$_POST["padre"];
                    $str_LsqlI .= $separador." G7_C60";
                    $str_LsqlV .= $separador.$_POST['padre'] ;
                    $validar = 1;
                }
            }

            if(isset($_GET['id_gestion_cbx'])){
                $separador = "";
                if($validar == 1){
                    $separador = ",";
                }

                $str_LsqlU .= $separador."G7_IdLlamada = '".$_GET['id_gestion_cbx']."'";
                $str_LsqlI .= $separador."G7_IdLlamada";
                $str_LsqlV .= $separador."'".$_GET['id_gestion_cbx']."'";
                $validar = 1;
            }



            if(isset($_POST['oper'])){
                if($_POST["oper"] == 'add' ){
                    
                    $str_Lsql = $str_LsqlI.", G6_C209)" . $str_LsqlV.", 1)";
                }else if($_POST["oper"] == 'edit' ){
                    $str_Lsql = $str_LsqlU." , G6_C209 = 2 WHERE G7_ConsInte__b =".$_POST["id"]; 
                }else if($_POST["oper"] == 'del' ){

                    $delCam = "DELETE FROM ".$BaseDatos_systema.".CAMPO_ WHERE CAMPO__ConsInte__PREGUN_b IN (SELECT PREGUN_ConsInte__b FROM ".$BaseDatos_systema.".PREGUN WHERE PREGUN_ConsInte__SECCIO_b = ".$_POST["id"].");";
                    $delCam = $mysqli->query($delCam);
                    
                    $delCam = "DELETE FROM ".$BaseDatos_systema.".CAMCON WHERE CAMCON_Nombre____b IN (SELECT CONCAT('G',PREGUN_ConsInte__GUION__b,'_C',PREGUN_ConsInte__b) FROM DYALOGOCRM_SISTEMA.PREGUN WHERE PREGUN_ConsInte__SECCIO_b = ".$_POST["id"].")";
                    $delCam = $mysqli->query($delCam);

                    $delCam = "DELETE FROM ".$BaseDatos_systema.".CAMORD WHERE CAMORD_POBLCAMP__B IN (SELECT CONCAT('G',PREGUN_ConsInte__GUION__b,'_C',PREGUN_ConsInte__b) FROM DYALOGOCRM_SISTEMA.PREGUN WHERE PREGUN_ConsInte__SECCIO_b = ".$_POST["id"].")";
                    $delCam = $mysqli->query($delCam);

                    $delCamp = "DELETE FROM ".$BaseDatos_systema.".CAMINC WHERE CAMINC_NomCamPob_b IN (SELECT CONCAT('G',PREGUN_ConsInte__GUION__b,'_C',PREGUN_ConsInte__b) FROM DYALOGOCRM_SISTEMA.PREGUN WHERE PREGUN_ConsInte__SECCIO_b = ".$_POST["id"].") OR CAMINC_NomCamGui_b IN (SELECT CONCAT('G',PREGUN_ConsInte__GUION__b,'_C',PREGUN_ConsInte__b) FROM DYALOGOCRM_SISTEMA.PREGUN WHERE PREGUN_ConsInte__SECCIO_b = ".$_POST["id"].");";
                    $delCamp = $mysqli->query($delCamp);
                    
                    $strDelSubform_t=$mysqli->query("DELETE FROM {$BaseDatos_systema}.GUIDET WHERE GUIDET_ConsInte__PREGUN_Cre_b IN (SELECT PREGUN_ConsInte__b FROM {$BaseDatos_systema}.PREGUN WHERE PREGUN_ConsInte__SECCIO_b = {$_POST['id']})");
                    
                    $delCam = "DELETE FROM ".$BaseDatos_systema.".PREGUN WHERE PREGUN_ConsInte__SECCIO_b = ".$_POST["id"];
                    $delCam = $mysqli->query($delCam);

                    $str_Lsql = "DELETE FROM ".$BaseDatos_systema.".SECCIO WHERE SECCIO_ConsInte__b = ".$_POST["id"];
                    $validar = 1;
                }
            }

            //si trae algo que insertar inserta

            //echo $str_Lsql;
            if($validar == 1){
                if ($mysqli->query($str_Lsql) === TRUE) {
                    $usuarionuevo = $mysqli->insert_id;
                    if($_POST["oper"] == 'add' ){
                        /* necesito ponerle el id a los campos */
                        $str_updateSql = "UPDATE ".$BaseDatos_systema.".G6 SET G6_C32 = ".$usuarionuevo.", G6_C208 = NULL WHERE G6_C208 = '".$_POST['padre_mientras']."';";
                        if($mysqli->query($str_updateSql) === true){

                        }else{
                            echo $mysqli->error;
                        }
                       guardar_auditoria("INSERTAR", "INSERTAR REGISTRO EN G7");
                    }else if($_POST["oper"] == 'edit' ){
                       guardar_auditoria("ACTUALIZAR", "ACTUALIZO EL REGISTRO # ".$_POST["padre"]." EN G7");
                    }else if($_POST["oper"] == 'del' ){
                       guardar_auditoria("ELIMINAR", "ELIMINO EL REGISTRO # ".$_POST['id']." EN G7");
                    }
                    
                    echo $usuarionuevo;
                } else {
                    echo "Error Hacieno el proceso los registros : " . $mysqli->error;
                }
            }
        }
    }

    
    if(isset($_GET["insertarDatosSubgrilla_0"])){
        
        if(isset($_POST["oper"])){
            $str_Lsql  = '';

            $validar = 0;
            $str_LsqlU = "UPDATE ".$BaseDatos_systema.".G6 SET "; 
            $str_LsqlI = "INSERT INTO ".$BaseDatos_systema.".G6(";
            $str_LsqlV = " VALUES ("; 
  
            if(isset($_POST["G6_C48"])){
                $separador = "";
                if($validar == 1){
                    $separador = ",";
                }

                $str_LsqlU .= $separador."G6_C48 = '".$_POST["G6_C48"]."'";
                $str_LsqlI .= $separador."G6_C48";
                $str_LsqlV .= $separador."'".$_POST["G6_C48"]."'";
                $validar = 1;
            }
            
 
            $G6_C51 = 0;
            //este es tipo check primeo si viene y de acuerdo a su valor se pone 1 o 0
            if(isset($_POST["G6_C51"])){
                if($_POST["G6_C51"] == 'Yes'){
                    $G6_C51 = 1;
                }else if($_POST["G6_C51"] == 'off'){
                    $G6_C51 = 0;
                }else if($_POST["G6_C51"] == 'on'){
                    $G6_C51 = 1;
                }else if($_POST["G6_C51"] == 'No'){
                    $G6_C51 = 1;
                }else{
                    $G6_C51 = $_POST["G6_C51"] ;
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
 
                                                                         
            if(isset($_POST["G6_C39"])){
                $separador = "";
                if($validar == 1){
                    $separador = ",";
                }

                $str_LsqlU .= $separador."G6_C39 = '".$_POST["G6_C39"]."'";
                $str_LsqlI .= $separador."G6_C39";
                $str_LsqlV .= $separador."'".$_POST["G6_C39"]."'";
                $validar = 1;
            }
                                                                          
                                                                           
  
            if(isset($_POST["G6_C49"])){
                $separador = "";
                if($validar == 1){
                    $separador = ",";
                }

                $str_LsqlU .= $separador."G6_C49 = '".$_POST["G6_C49"]."'";
                $str_LsqlI .= $separador."G6_C49";
                $str_LsqlV .= $separador."'".$_POST["G6_C49"]."'";
                $validar = 1;
            }
            
 
            $G6_C52 = 0;
            //este es tipo check primeo si viene y de acuerdo a su valor se pone 1 o 0
            if(isset($_POST["G6_C52"])){
                if($_POST["G6_C52"] == 'Yes'){
                    $G6_C52 = 1;
                }else if($_POST["G6_C52"] == 'off'){
                    $G6_C52 = 0;
                }else if($_POST["G6_C52"] == 'on'){
                    $G6_C52 = 1;
                }else if($_POST["G6_C52"] == 'No'){
                    $G6_C52 = 1;
                }else{
                    $G6_C52 = $_POST["G6_C52"] ;
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
 
            if(isset($_POST["G6_C40"])){
                $separador = "";
                if($validar == 1){
                    $separador = ",";
                }

                $str_LsqlU .= $separador."G6_C40 = '".$_POST["G6_C40"]."'";
                $str_LsqlI .= $separador."G6_C40";
                $str_LsqlV .= $separador."'".$_POST["G6_C40"]."'";
                $validar = 1;
            }
 
            $G6_C50 = 0;
            //este es tipo check primeo si viene y de acuerdo a su valor se pone 1 o 0
            if(isset($_POST["G6_C50"])){
                if($_POST["G6_C50"] == 'Yes'){
                    $G6_C50 = 1;
                }else if($_POST["G6_C50"] == 'off'){
                    $G6_C50 = 0;
                }else if($_POST["G6_C50"] == 'on'){
                    $G6_C50 = 1;
                }else if($_POST["G6_C50"] == 'No'){
                    $G6_C50 = 1;
                }else{
                    $G6_C50 = $_POST["G6_C50"] ;
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
            //este es de tipo numero no se deja ir asi '', si est avacio lo mejor es no mandarlo
            if(isset($_POST["G6_C53"])){    
                if($_POST["G6_C53"] != ''){
                    $separador = "";
                    if($validar == 1){
                        $separador = ",";
                    }

                    $G6_C53 = $_POST["G6_C53"];
                    $str_LsqlU .= $separador." G6_C53 = '".$G6_C53."'";
                    $str_LsqlI .= $separador." G6_C53";
                    $str_LsqlV .= $separador."'".$G6_C53."'";
                    $validar = 1;
                }
            }
 
            $G6_C41 = 0;
            //este es tipo check primeo si viene y de acuerdo a su valor se pone 1 o 0
            if(isset($_POST["G6_C41"])){
                if($_POST["G6_C41"] == 'Yes'){
                    $G6_C41 = 1;
                }else if($_POST["G6_C41"] == 'off'){
                    $G6_C41 = 0;
                }else if($_POST["G6_C41"] == 'on'){
                    $G6_C41 = 1;
                }else if($_POST["G6_C41"] == 'No'){
                    $G6_C41 = 1;
                }else{
                    $G6_C41 = $_POST["G6_C41"] ;
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
 
            $G6_C54= NULL;
            //este es de tipo numero no se deja ir asi '', si est avacio lo mejor es no mandarlo
            if(isset($_POST["G6_C54"])){    
                if($_POST["G6_C54"] != ''){
                    $separador = "";
                    if($validar == 1){
                        $separador = ",";
                    }

                    $G6_C54 = $_POST["G6_C54"];
                    $str_LsqlU .= $separador." G6_C54 = '".$G6_C54."'";
                    $str_LsqlI .= $separador." G6_C54";
                    $str_LsqlV .= $separador."'".$G6_C54."'";
                    $validar = 1;
                }
            }
 
            $G6_C42 = 0;
            //este es tipo check primeo si viene y de acuerdo a su valor se pone 1 o 0
            if(isset($_POST["G6_C42"])){
                if($_POST["G6_C42"] == 'Yes'){
                    $G6_C42 = 1;
                }else if($_POST["G6_C42"] == 'off'){
                    $G6_C42 = 0;
                }else if($_POST["G6_C42"] == 'on'){
                    $G6_C42 = 1;
                }else if($_POST["G6_C42"] == 'No'){
                    $G6_C42 = 1;
                }else{
                    $G6_C42 = $_POST["G6_C42"] ;
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
            //este es de tipo date hay que preguntar si esta vacia o no
            if(isset($_POST["G6_C55"])){    
                if($_POST["G6_C55"] != ''){
                    $separador = "";
                    if($validar == 1){
                        $separador = ",";
                    }

                    $G6_C55 = "'".str_replace(' ', '',$_POST["G6_C55"])." 00:00:00'";
                    $str_LsqlU .= $separador." G6_C55 = ".$G6_C55;
                    $str_LsqlI .= $separador." G6_C55";
                    $str_LsqlV .= $separador.$G6_C55;
                    $validar = 1;
                }
            }
  
            if(isset($_POST["G6_C43"])){
                $separador = "";
                if($validar == 1){
                    $separador = ",";
                }

                $str_LsqlU .= $separador."G6_C43 = '".$_POST["G6_C43"]."'";
                $str_LsqlI .= $separador."G6_C43";
                $str_LsqlV .= $separador."'".$_POST["G6_C43"]."'";
                $validar = 1;
            }
            

            $G6_C56 = NULL;
            //este es de tipo date hay que preguntar si esta vacia o no
            if(isset($_POST["G6_C56"])){    
                if($_POST["G6_C56"] != ''){
                    $separador = "";
                    if($validar == 1){
                        $separador = ",";
                    }

                    $G6_C56 = "'".str_replace(' ', '',$_POST["G6_C56"])." 00:00:00'";
                    $str_LsqlU .= $separador." G6_C56 = ".$G6_C56;
                    $str_LsqlI .= $separador." G6_C56";
                    $str_LsqlV .= $separador.$G6_C56;
                    $validar = 1;
                }
            }
  
            if(isset($_POST["G6_C44"])){
                $separador = "";
                if($validar == 1){
                    $separador = ",";
                }

                $str_LsqlU .= $separador."G6_C44 = '".$_POST["G6_C44"]."'";
                $str_LsqlI .= $separador."G6_C44";
                $str_LsqlV .= $separador."'".$_POST["G6_C44"]."'";
                $validar = 1;
            }
            
 
            $G6_C57 = NULL;
            //este es de tipo date hay que preguntar si esta vacia o no o esta undefined
            if(isset($_POST["G6_C57"])){    
                if($_POST["G6_C57"] != '' && $_POST["G6_C57"] != 'undefined' && $_POST["G6_C57"] != 'null'){
                    $separador = "";
                    $fecha = date('Y-m-d');
                    if($validar == 1){
                        $separador = ",";
                    }

                    $G6_C57 = "'".$fecha." ".str_replace(' ', '',$_POST["G6_C57"])."'";
                    $str_LsqlU .= $separador."  G6_C57 = ".$G6_C57."";
                    $str_LsqlI .= $separador."  G6_C57";
                    $str_LsqlV .= $separador.$G6_C57;
                    $validar = 1;
                }
            }
 
            $G6_C46 = 0;
            //este es tipo check primeo si viene y de acuerdo a su valor se pone 1 o 0
            if(isset($_POST["G6_C46"])){
                if($_POST["G6_C46"] == 'Yes'){
                    $G6_C46 = 1;
                }else if($_POST["G6_C46"] == 'off'){
                    $G6_C46 = 0;
                }else if($_POST["G6_C46"] == 'on'){
                    $G6_C46 = 1;
                }else if($_POST["G6_C46"] == 'No'){
                    $G6_C46 = 1;
                }else{
                    $G6_C46 = $_POST["G6_C46"] ;
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
            //este es de tipo date hay que preguntar si esta vacia o no o esta undefined
            if(isset($_POST["G6_C58"])){    
                if($_POST["G6_C58"] != '' && $_POST["G6_C58"] != 'undefined' && $_POST["G6_C58"] != 'null'){
                    $separador = "";
                    $fecha = date('Y-m-d');
                    if($validar == 1){
                        $separador = ",";
                    }

                    $G6_C58 = "'".$fecha." ".str_replace(' ', '',$_POST["G6_C58"])."'";
                    $str_LsqlU .= $separador."  G6_C58 = ".$G6_C58."";
                    $str_LsqlI .= $separador."  G6_C58";
                    $str_LsqlV .= $separador.$G6_C58;
                    $validar = 1;
                }
            }
            

            /* Esto es para el orden */
            if(isset($_POST["id_seccion"])) {
               if($validar == 1){
                    /* Primero es meter el orden en el que estan siendo generados */
                    $Lsqls = "SELECT COUNT(*) as TOTAL FROM ".$BaseDatos_systema.".G6 WHERE G6_C32 = ".$_POST["id_seccion"] ;
                    $resLsqls = $mysqli->query($Lsqls);
                    $data = $resLsqls->fetch_array();

                    $str_LsqlI .= ", G6_C317";
                    $str_LsqlV .= ",".($data['TOTAL'] + 1);
                }     
            }
            
            

        
            if(isset($_POST["id_guion"])){
                if($_POST["id_guion"] != ''){
                    //esto es porque el padre es el entero
                    
                    $numero = $_POST["id_guion"];
                    
                   
                    $G6_C207 = $numero;
                    $str_LsqlU .= ", G6_C207 = ".$G6_C207."";
                    $str_LsqlI .= ", G6_C207";
                    $str_LsqlV .= ",".$_POST["id_guion"];
                    
                }
            } 

            if(isset($_POST["id_seccion"])){
                if($_POST["id_seccion"] != ''){
                    //esto es porque el padre es el entero
                    
                    $numero = $_POST["id_seccion"];
                    
                   
                    $G6_C32 = $numero;
                    $str_LsqlU .= ", G6_C32 = ".$G6_C32."";
                    $str_LsqlI .= ", G6_C32";
                    $str_LsqlV .= ",".$_POST["id_seccion"];
                    

                    
                }
            }  



            /* Comment */
            

            if(isset($_POST['oper'])){
                if($_POST["oper"] == 'add' ){
                    $str_Lsql = $str_LsqlI.", G6_C209)" . $str_LsqlV.", 1)";
                }else if($_POST["oper"] == 'edit' ){
                    $str_Lsql = $str_LsqlU." , G6_C209 = 2  WHERE G6_ConsInte__b =".$_POST["id_pregun"]; 
                }else if($_POST['oper'] == 'del'){
                    $str_Lsql = "UPDATE ".$BaseDatos_systema.".G6 SET G6_C209 = 3 WHERE G6_ConsInte__b = ".$_POST['id'];
                    $validar = 1;
                }
            }

            if($validar == 1){
                //cho $str_Lsql;
                if ($mysqli->query($str_Lsql) === TRUE) {
                    $ultimo = $mysqli->insert_id;
                    if($_POST["oper"] == 'add' ){
                        /* Esto es para meterlo en el CAMPO_ */
                        if($_POST['G6_C40'] != '12'){
                            $CampoLsql = "INSERT INTO ".$BaseDatos_systema.".CAMPO_ (CAMPO__Nombre____b, CAMPO__Tipo______b, CAMPO__ConsInte__PREGUN_b) VALUES ('G".$_POST['id_guion']."_C".$ultimo."' , ".$_POST['G6_C40'].", ".$ultimo.")";

                            if ($mysqli->query($CampoLsql) === TRUE) {
                            
                            }else{
                                echo "Error metiendo el valor en campo ".$mysqli->error;
                            }
                        }
                        

                        /* aqui empezamos a guardar el GUIDET */
                        if($_POST['G6_C40'] == '12'){
                            if(isset($_POST["GuidetM"])){
                            $modoGrilla = 0;
                            if(isset($_POST['modoGuidet'])){
                                $modoGrilla = $_POST['modoGuidet'];
                            }
                            $LsqlGuidet = "";
                            if($_POST["GuidetM"] != ''){
                                if($_POST["GuidetM"] != '_ConsInte__b'){

                                    $LsqlGuidet = 'INSERT INTO '.$BaseDatos_systema.".GUIDET (GUIDET_ConsInte__GUION__Mae_b, GUIDET_ConsInte__GUION__Det_b, GUIDET_Nombre____b, GUIDET_ConsInte__PREGUN_Ma1_b, GUIDET_ConsInte__PREGUN_De1_b, GUIDET_Modo______b, GUIDET_ConsInte__PREGUN_Cre_b) VALUES (".$_POST["id_guion"].", ".$_POST['G6_C43']." , '".$_POST['G6_C39'] ."' , ".$_POST["GuidetM"]." , ".$_POST["Guidet"].", ".$modoGrilla.", ".$ultimo.")";
                                }else{
                                    $LsqlGuidet = 'INSERT INTO '.$BaseDatos_systema.".GUIDET (GUIDET_ConsInte__GUION__Mae_b, GUIDET_ConsInte__GUION__Det_b, GUIDET_Nombre____b, GUIDET_ConsInte__PREGUN_De1_b, GUIDET_Modo______b, GUIDET_ConsInte__PREGUN_Cre_b) VALUES (".$_POST["id_guion"].", ".$_POST['G6_C43']." , '".$_POST['G6_C39']."' ,  ".$_POST["Guidet"].", ".$modoGrilla.", ".$ultimo.")";
                                }  

                                if($mysqli->query($LsqlGuidet) === TRUE){

                                }else{
                                    echo "Error Guidet".$mysqli->error;
                                }
                                
                            }
                            }
                        }

                        /* definimos los campos que se van a mostrar en el combo */

                        if(isset($_POST['camposGuion'])){
                            foreach ($_POST['camposGuion'] as $key) {
                                /* Lo pirmero es obtener el id del campo */
                                $campoLsql = "SELECT CAMPO__ConsInte__b FROM ".$BaseDatos_systema.".CAMPO_ WHERE CAMPO__ConsInte__PREGUN_b = ".$key;
                                $rsCampo = $mysqli->query($campoLsql);
                                $campo = $rsCampo->fetch_array();

                                /* ya tengo el id del campo lo voy a meter sencillo sin salto ni mucho menos */
                                $preguiLsql = "INSERT INTO ".$BaseDatos_systema.".PREGUI (PREGUI_ConsInte__PREGUN_b, PREGUI_ConsInte__CAMPO__b, PREGUI_Consinte__GUION__b, PREGUI_Consinte__CAMPO__GUI_B) VALUES (".$ultimo.",".$campo['CAMPO__ConsInte__b']." , ".$_POST['id_guion'].", 0)";
                                if ($mysqli->query($preguiLsql) === TRUE) {
                            
                                }else{
                                    echo "Error metiendo el valor en pregui ".$mysqli->error;
                                }
                            }                           
                        }

                        guardar_auditoria("INSERTAR", "INSERTAR REGISTRO EN G6");
                        echo $ultimo;
                    }else if($_POST["oper"] == 'edit' ){
                       guardar_auditoria("ACTUALIZAR", "ACTUALIZO EL REGISTRO # ".$_POST["id_pregun"]." EN G6");
                       echo $ultimo;
                    }else if($_POST["oper"] == 'del' ){

                        $LsqlGuidet = 'DELETE FROM '.$BaseDatos_systema.".GUIDET WHERE GUIDET_ConsInte__PREGUN_Cre_b = ".$_POST['id'];
                        if($mysqli->query($LsqlGuidet) === TRUE){

                        }else{
                            echo "Error Guidet".$mysqli->error();
                        }

                        $preguiLsql = "DELETE FROM ".$BaseDatos_systema.".PREGUI WHERE PREGUI_ConsInte__PREGUN_b = ".$_POST['id'];
                        if ($mysqli->query($preguiLsql) === TRUE) {
                    
                        }else{
                            echo "Error eliminado el valor en pregui ".$mysqli->error;
                        }

                        guardar_auditoria("ELIMINAR", "ELIMINO EL REGISTRO # ".$_POST['id']." EN G6");
                        echo '1';
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
                

        $SQL = "SELECT G6_ConsInte__b, G7_C33 as G6_C32, G6_C39 as G6_C48, G6_C51, G6_C39, G6_C39 as G6_C49, G6_C52, g.LISOPC_Nombre____b as  G6_C40, G6_C50, G6_C53, G6_C41, G6_C54, G6_C42, G6_C55, G5_C28 as G6_C43, G6_C56, G8_C45 as G6_C44, G6_C57, G6_C46, G6_C58, G5_C28 as G6_C207 FROM ".$BaseDatos_systema.".G6  LEFT JOIN '.$BaseDatos_systema.'.G7 ON G7_ConsInte__b  =  G6_C32 LEFT JOIN '.$BaseDatos_systema.'.G6 ON G6_ConsInte__b  =  G6_C48 LEFT JOIN '.$BaseDatos_systema.'.G6 ON G6_ConsInte__b  =  G6_C49 LEFT JOIN ".$BaseDatos_systema.".LISOPC as g ON g.LISOPC_ConsInte__b =  G6_C40 LEFT JOIN '.$BaseDatos_systema.'.G5 ON G5_ConsInte__b  =  G6_C43 LEFT JOIN '.$BaseDatos_systema.'.G8 ON G8_ConsInte__b  =  G6_C44 LEFT JOIN '.$BaseDatos_systema.'.G5 ON G5_ConsInte__b  =  G6_C207 ";

        $SQL .= " WHERE G6_C32 = ".$numero." AND G6_C209 != 3"; 

        $PEOBUS_VeRegPro__b = 0 ;
        $idUsuario = $_SESSION['IDENTIFICACION'];
        $peobus = "SELECT * FROM ".$BaseDatos_systema.".PEOBUS WHERE PEOBUS_ConsInte__USUARI_b = ".$idUsuario." AND PEOBUS_ConsInte__GUION__b = 6";
        $query = $mysqli->query($peobus);
        

        while ($key =  $query->fetch_object()) {
            $PEOBUS_VeRegPro__b = $key->PEOBUS_VeRegPro__b ;
        }

        if($PEOBUS_VeRegPro__b != 0){
            $SQL .= " AND G6_Usuario = ".$idUsuario;
        }
    
        $SQL .= " ORDER BY G6_C32";

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
            echo "<row asin='".$fila->G6_ConsInte__b."'>"; 
            echo "<cell>". ($fila->G6_ConsInte__b)."</cell>"; 
            

            echo "<cell>". ($fila->G6_C32)."</cell>";

            echo "<cell>". ($fila->G6_C48)."</cell>";

            echo "<cell>". $fila->G6_C51."</cell>"; 

            echo "<cell>". ($fila->G6_C39)."</cell>";

            echo "<cell>". ($fila->G6_C49)."</cell>";

            echo "<cell>". $fila->G6_C52."</cell>"; 

            echo "<cell>". ($fila->G6_C40)."</cell>";

            echo "<cell>". $fila->G6_C50."</cell>"; 

            echo "<cell>". $fila->G6_C53."</cell>"; 

            echo "<cell>". $fila->G6_C41."</cell>"; 

            echo "<cell>". $fila->G6_C54."</cell>"; 

            echo "<cell>". $fila->G6_C42."</cell>"; 

            if($fila->G6_C55 != ''){
                echo "<cell>". explode(' ', $fila->G6_C55)[0]."</cell>";
            }else{
                echo "<cell></cell>";
            }

            echo "<cell>". ($fila->G6_C43)."</cell>";

            if($fila->G6_C56 != ''){
                echo "<cell>". explode(' ', $fila->G6_C56)[0]."</cell>";
            }else{
                echo "<cell></cell>";
            }

            echo "<cell>". ($fila->G6_C44)."</cell>";

            if($fila->G6_C57 != ''){
                echo "<cell>". explode(' ', $fila->G6_C57)[1]."</cell>";
            }else{
                echo "<cell></cell>";
            }

            echo "<cell>". $fila->G6_C46."</cell>"; 

            if($fila->G6_C58 != ''){
                echo "<cell>". explode(' ', $fila->G6_C58)[1]."</cell>";
            }else{
                echo "<cell></cell>";
            }

            echo "<cell>". ($fila->G6_C207)."</cell>";
            echo "</row>"; 
        } 
        echo "</rows>"; 
    }

?>
