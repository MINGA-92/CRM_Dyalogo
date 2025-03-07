<?php
    session_start();
    ini_set('display_errors', 'On');
    ini_set('display_errors', 1);
    include(__DIR__."../../../../pages/conexion.php");
    date_default_timezone_set('America/Bogota');
    function guardar_auditoria($accion, $superAccion){
        include(__DIR__."../../../../pages/conexion.php");
        $str_Lsql = "INSERT INTO ".$BaseDatos_systema."AUACAD (AUACAD_Fecha_____b , AUACAD_Hora______b, AUACAD_Ejecutor__b, AUACAD_TipoAcci__b , AUACAD_SubTipAcc_b, AUACAD_Accion____b , AUACAD_Huesped___b ) VALUES ('".date('Y-m-d H:s:i')."', '".date('Y-m-d H:s:i')."', ".$_SESSION['IDENTIFICACION'].", 'G6', '".$accion."', '".$superAccion."', ".$_SESSION['HUESPED']." );";
        $mysqli->query($str_Lsql);
    }   

    if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
        //Funciones de la carga maestro y eso
        

        //Datos del formulario
        if(isset($_POST['CallDatos'])){
          
            $str_Lsql = 'SELECT G6_ConsInte__b, G6_C39 as principal ,G6_C32,G6_C39,G6_C40,G6_C41,G6_C42,G6_C43,G6_C44,G6_C46,G6_C207,G6_C48,G6_C49,G6_C50,G6_C51,G6_C52,G6_C53,G6_C54,G6_C55,G6_C56,G6_C57,G6_C58 FROM '.$BaseDatos_systema.'.G6 WHERE G6_ConsInte__b ='.$_POST['id'];
            $result = $mysqli->query($str_Lsql);
            $datos = array();
            $i = 0;

            while($key = $result->fetch_object()){

                $datos[$i]['G6_C32'] = $key->G6_C32;

                $datos[$i]['G6_C39'] = $key->G6_C39;

                $datos[$i]['G6_C40'] = $key->G6_C40;

                $datos[$i]['G6_C41'] = $key->G6_C41;

                $datos[$i]['G6_C42'] = $key->G6_C42;

                $datos[$i]['G6_C43'] = $key->G6_C43;

                $datos[$i]['G6_C44'] = $key->G6_C44;

                $datos[$i]['G6_C46'] = $key->G6_C46;

                $datos[$i]['G6_C207'] = $key->G6_C207;

                $datos[$i]['G6_C48'] = $key->G6_C48;

                $datos[$i]['G6_C49'] = $key->G6_C49;

                $datos[$i]['G6_C50'] = $key->G6_C50;

                $datos[$i]['G6_C51'] = $key->G6_C51;

                $datos[$i]['G6_C52'] = $key->G6_C52;

                $datos[$i]['G6_C53'] = $key->G6_C53;

                $datos[$i]['G6_C54'] = $key->G6_C54;

                $datos[$i]['G6_C55'] = explode(' ', $key->G6_C55)[0];

                $datos[$i]['G6_C56'] = explode(' ', $key->G6_C56)[0];
  
                if(!is_null($key->G6_C57)){
                    $datos[$i]['G6_C57'] = explode(' ', $key->G6_C57)[1];
                }
  
                if(!is_null($key->G6_C58)){
                    $datos[$i]['G6_C58'] = explode(' ', $key->G6_C58)[1];
                }
      
                $datos[$i]['principal'] = $key->principal;
                $i++;
            }
            echo json_encode($datos);
        }

        //Datos de la lista de la izquierda
        if(isset($_POST['CallDatosJson'])){
            $str_Lsql = "SELECT G6_ConsInte__b as id,  G6_C39 as camp1 , b.LISOPC_Nombre____b as camp2 ";
            $str_Lsql .= " FROM ".$BaseDatos_systema.".G6   LEFT JOIN ".$BaseDatos_systema.".LISOPC as b ON b.LISOPC_ConsInte__b = G6_C40 ";
            if($_POST['Busqueda'] != '' && !is_null($_POST['Busqueda'])){
                $str_Lsql .= " WHERE  like '%".$_POST['Busqueda']."%' ";
                $str_Lsql .= " OR  like '%".$_POST['Busqueda']."%' ";
            }

            $PEOBUS_VeRegPro__b = 0 ;
            $idUsuario = $_SESSION['IDENTIFICACION'];
            $peobus = "SELECT * FROM ".$BaseDatos_systema.".PEOBUS WHERE PEOBUS_ConsInte__USUARI_b = ".$idUsuario." AND PEOBUS_ConsInte__GUION__b = 6";
            $query = $mysqli->query($peobus);
            

            while ($key =  $query->fetch_object()) {
                $PEOBUS_VeRegPro__b = $key->PEOBUS_VeRegPro__b ;
            }

            if($PEOBUS_VeRegPro__b != 0){
                if($_POST['Busqueda'] != '' && !is_null($_POST['Busqueda'])){
                    $str_Lsql .= " AND G6_Usuario = ".$idUsuario;
                }else{
                    $str_Lsql .= " WHERE G6_Usuario = ".$idUsuario;
                }
        
            }

            $str_Lsql .= ' ORDER BY G6_ConsInte__b DESC LIMIT 0, 50'; 
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

        

        if(isset($_GET['CallDatosCombo_Guion_G6_C32'])){
            $Ysql = 'SELECT   G7_ConsInte__b as id , G7_C33 FROM ".$BaseDatos_systema.".G7';
            $guion = $mysqli->query($Ysql);
            echo '<select class="form-control input-sm"  name="G6_C32" id="G6_C32">';
            echo '<option >NOMBRE</option>';
            while($obj = $guion->fetch_object()){
               echo "<option value='".$obj->id."' dinammicos='0'>".($obj->G7_C33)."</option>";
            } 
            echo '</select>';
        }

        if(isset($_GET['CallDatosCombo_Guion_G6_C43'])){
            $Ysql = 'SELECT   G5_ConsInte__b as id , G5_C28 FROM ".$BaseDatos_systema.".G5';
            $guion = $mysqli->query($Ysql);
            echo '<select class="form-control input-sm"  name="G6_C43" id="G6_C43">';
            echo '<option >NOMBRE</option>';
            while($obj = $guion->fetch_object()){
               echo "<option value='".$obj->id."' dinammicos='0'>".($obj->G5_C28)."</option>";
            } 
            echo '</select>';
        }

        if(isset($_GET['CallDatosCombo_Guion_G6_C44'])){
            $Ysql = 'SELECT   G8_ConsInte__b as id , G8_C45 FROM ".$BaseDatos_systema.".G8';
            $guion = $mysqli->query($Ysql);
            echo '<select class="form-control input-sm"  name="G6_C44" id="G6_C44">';
            echo '<option >NOMBRE</option>';
            while($obj = $guion->fetch_object()){
               echo "<option value='".$obj->id."' dinammicos='0'>".($obj->G8_C45)."</option>";
            } 
            echo '</select>';
        }

        if(isset($_GET['CallDatosCombo_Guion_G6_C207'])){
            $Ysql = 'SELECT   G5_ConsInte__b as id , G5_C28 FROM ".$BaseDatos_systema.".G5';
            $guion = $mysqli->query($Ysql);
            echo '<select class="form-control input-sm"  name="G6_C207" id="G6_C207">';
            echo '<option >NOMBRE</option>';
            while($obj = $guion->fetch_object()){
               echo "<option value='".$obj->id."' dinammicos='0'>".($obj->G5_C28)."</option>";
            } 
            echo '</select>';
        }

        if(isset($_GET['CallDatosCombo_Guion_G6_C48'])){
            $Ysql = 'SELECT   G6_ConsInte__b as id , G6_C39 FROM ".$BaseDatos_systema.".G6';
            $guion = $mysqli->query($Ysql);
            echo '<select class="form-control input-sm"  name="G6_C48" id="G6_C48">';
            echo '<option >TEXTO</option>';
            while($obj = $guion->fetch_object()){
               echo "<option value='".$obj->id."' dinammicos='0'>".($obj->G6_C39)."</option>";
            } 
            echo '</select>';
        }

        if(isset($_GET['CallDatosCombo_Guion_G6_C49'])){
            $Ysql = 'SELECT   G6_ConsInte__b as id , G6_C39 FROM ".$BaseDatos_systema.".G6';
            $guion = $mysqli->query($Ysql);
            echo '<select class="form-control input-sm"  name="G6_C49" id="G6_C49">';
            echo '<option >TEXTO</option>';
            while($obj = $guion->fetch_object()){
               echo "<option value='".$obj->id."' dinammicos='0'>".($obj->G6_C39)."</option>";
            } 
            echo '</select>';
        }





        if(isset($_POST['CallEliminate'])){
            if($_POST['oper'] == 'del'){
                $str_Lsql = "DELETE FROM ".$BaseDatos_systema.".G6 WHERE G6_ConsInte__b = ".$_POST['id'];
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
            $Zsql = "SELECT  G6_ConsInte__b as id,  G6_C39 as camp1 , b.LISOPC_Nombre____b as camp2  FROM ".$BaseDatos_systema.".G6   LEFT JOIN ".$BaseDatos_systema.".LISOPC as b ON b.LISOPC_ConsInte__b = G6_C40 ORDER BY G6_ConsInte__b DESC LIMIT ".$inicio." , ".$fin;
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
            $str_LsqlU = "UPDATE ".$BaseDatos_systema.".G6 SET "; 
            $str_LsqlI = "INSERT INTO ".$BaseDatos_systema.".G6(";
            $str_LsqlV = " VALUES ("; 
  
            if(isset($_POST["G6_C32"])){
                $separador = "";
                if($validar == 1){
                    $separador = ",";
                }

                $str_LsqlU .= $separador."G6_C32 = '".$_POST["G6_C32"]."'";
                $str_LsqlI .= $separador."G6_C32";
                $str_LsqlV .= $separador."'".$_POST["G6_C32"]."'";
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
                    $G6_C41 = 0;
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
                    $G6_C42 = 0;
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
                    $G6_C46 = 0;
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
  
            if(isset($_POST["G6_C207"])){
                $separador = "";
                if($validar == 1){
                    $separador = ",";
                }

                $str_LsqlU .= $separador."G6_C207 = '".$_POST["G6_C207"]."'";
                $str_LsqlI .= $separador."G6_C207";
                $str_LsqlV .= $separador."'".$_POST["G6_C207"]."'";
                $validar = 1;
            }
             
  
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
                    $G6_C50 = 0;
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
                    $G6_C51 = 0;
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
                    $G6_C52 = 0;
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
  
            $G6_C53 = NULL;
            //este es de tipo numero no se deja ir asi '', si est avacio lo mejor es no mandarlo
            if(isset($_POST["G6_C53"])){
                if($_POST["G6_C53"] != ''){
                    $separador = "";
                    if($validar == 1){
                        $separador = ",";
                    }

                    //$G6_C53 = $_POST["G6_C53"];
                    $G6_C53 = str_replace(".", "", $_POST["G6_C53"]);
                    $G6_C53 =  str_replace(",", ".", $G6_C53);
                    $str_LsqlU .= $separador." G6_C53 = '".$G6_C53."'";
                    $str_LsqlI .= $separador." G6_C53";
                    $str_LsqlV .= $separador."'".$G6_C53."'";
                    $validar = 1;
                }
            }
  
            $G6_C54 = NULL;
            //este es de tipo numero no se deja ir asi '', si est avacio lo mejor es no mandarlo
            if(isset($_POST["G6_C54"])){
                if($_POST["G6_C54"] != ''){
                    $separador = "";
                    if($validar == 1){
                        $separador = ",";
                    }

                    //$G6_C54 = $_POST["G6_C54"];
                    $G6_C54 = str_replace(".", "", $_POST["G6_C54"]);
                    $G6_C54 =  str_replace(",", ".", $G6_C54);
                    $str_LsqlU .= $separador." G6_C54 = '".$G6_C54."'";
                    $str_LsqlI .= $separador." G6_C54";
                    $str_LsqlV .= $separador."'".$G6_C54."'";
                    $validar = 1;
                }
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
                    $str_LsqlU .= $separador." G6_C57 = ".$G6_C57."";
                    $str_LsqlI .= $separador." G6_C57";
                    $str_LsqlV .= $separador.$G6_C57;
                    $validar = 1;
                }
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
                    $str_LsqlU .= $separador." G6_C58 = ".$G6_C58."";
                    $str_LsqlI .= $separador." G6_C58";
                    $str_LsqlV .= $separador.$G6_C58;
                    $validar = 1;
                }
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
                    $str_Lsql = "SELECT GUIDET_ConsInte__PREGUN_De1_b FROM ".$BaseDatos_systema.".GUIDET WHERE GUIDET_ConsInte__GUION__Mae_b = ".$_POST['formpadre']." AND 
GUIDET_ConsInte__GUION__Det_b = ".$_POST['formhijo'];

                    $GuidRes = $mysqli->query($str_Lsql);
                    $campo = null;
                    while($ky = $GuidRes->fetch_object()){
                        $campo = $ky->GUIDET_ConsInte__PREGUN_De1_b;
                    }
                    $valorG = "G6_C";
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

                $str_LsqlU .= $separador."G6_IdLlamada = '".$_GET['id_gestion_cbx']."'";
                $str_LsqlI .= $separador."G6_IdLlamada";
                $str_LsqlV .= $separador."'".$_GET['id_gestion_cbx']."'";
                $validar = 1;
            }



            if(isset($_POST['oper'])){
                if($_POST["oper"] == 'add' ){
                    
                    $str_Lsql = $str_LsqlI.")" . $str_LsqlV.")";
                }else if($_POST["oper"] == 'edit' ){
                    $str_Lsql = $str_LsqlU." WHERE G6_ConsInte__b =".$_POST["id"]; 
                }else if($_POST["oper"] == 'del' ){
                    $str_Lsql = "DELETE FROM ".$BaseDatos_systema.".G6 WHERE G6_ConsInte__b = ".$_POST['id'];
                    $validar = 1;
                }
            }

            //si trae algo que insertar inserta

            //echo $str_Lsql;
            if($validar == 1){
                if ($mysqli->query($str_Lsql) === TRUE) {
                    if($_POST["oper"] == 'add' ){
                       guardar_auditoria("INSERTAR", "INSERTAR REGISTRO EN G6");
                    }else if($_POST["oper"] == 'edit' ){
                       guardar_auditoria("ACTUALIZAR", "ACTUALIZO EL REGISTRO # ".$_POST["padre"]." EN G6");
                    }else if($_POST["oper"] == 'del' ){
                       guardar_auditoria("ELIMINAR", "ELIMINO EL REGISTRO # ".$_POST['id']." EN G6");
                    }
                    
                    echo $mysqli->insert_id;
                } else {
                    echo "Error Hacieno el proceso los registros : " . $mysqli->error;
                }
            }
        }
    }

    

    

?>
