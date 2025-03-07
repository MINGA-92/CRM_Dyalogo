<?php
    session_start();
    ini_set('display_errors', 'On');
    ini_set('display_errors', 1);
    include(__DIR__."../../../../pages/conexion.php");
    function guardar_auditoria($accion, $superAccion){
        include(__DIR__."../../../../pages/conexion.php");
        $str_Lsql = "INSERT INTO ".$BaseDatos_systema."AUACAD (AUACAD_Fecha_____b , AUACAD_Hora______b, AUACAD_Ejecutor__b, AUACAD_TipoAcci__b , AUACAD_SubTipAcc_b, AUACAD_Accion____b , AUACAD_Huesped___b ) VALUES ('".date('Y-m-d H:s:i')."', '".date('Y-m-d H:s:i')."', ".$_SESSION['IDENTIFICACION'].", 'G24', '".$accion."', '".$superAccion."', ".$_SESSION['HUESPED']." );";
        $mysqli->query($str_Lsql);
    }   

    if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
        //Funciones de la carga maestro y eso
        

        //Datos del formulario
        if(isset($_POST['CallDatos'])){
          
            $str_Lsql = 'SELECT G24_ConsInte__b, G24_C243 as principal ,G24_C219,G24_C220,G24_C221,G24_C222,G24_C223,G24_C224,G24_C225,G24_C226,G24_C227,G24_C228,G24_C229,G24_C230,G24_C231,G24_C232,G24_C233,G24_C234,G24_C235,G24_C236,G24_C237,G24_C238,G24_C239,G24_C240,G24_C241,G24_C242,G24_C243,G24_C244,G24_C247 FROM '.$BaseDatos.'.G24 WHERE G24_ConsInte__b ='.$_POST['id'];
            $result = $mysqli->query($str_Lsql);
            $datos = array();
            $i = 0;

            while($key = $result->fetch_object()){

                $datos[$i]['G24_C219'] = $key->G24_C219;
  
                if(!is_null($key->G24_C220)){
                    $datos[$i]['G24_C220'] = explode(' ', $key->G24_C220)[1];
                }

                $datos[$i]['G24_C221'] = $key->G24_C221;

                $datos[$i]['G24_C222'] = $key->G24_C222;
  
                if(!is_null($key->G24_C223)){
                    $datos[$i]['G24_C223'] = explode(' ', $key->G24_C223)[1];
                }
  
                if(!is_null($key->G24_C224)){
                    $datos[$i]['G24_C224'] = explode(' ', $key->G24_C224)[1];
                }

                $datos[$i]['G24_C225'] = $key->G24_C225;
  
                if(!is_null($key->G24_C226)){
                    $datos[$i]['G24_C226'] = explode(' ', $key->G24_C226)[1];
                }
  
                if(!is_null($key->G24_C227)){
                    $datos[$i]['G24_C227'] = explode(' ', $key->G24_C227)[1];
                }

                $datos[$i]['G24_C228'] = $key->G24_C228;
  
                if(!is_null($key->G24_C229)){
                    $datos[$i]['G24_C229'] = explode(' ', $key->G24_C229)[1];
                }
  
                if(!is_null($key->G24_C230)){
                    $datos[$i]['G24_C230'] = explode(' ', $key->G24_C230)[1];
                }

                $datos[$i]['G24_C231'] = $key->G24_C231;
  
                if(!is_null($key->G24_C232)){
                    $datos[$i]['G24_C232'] = explode(' ', $key->G24_C232)[1];
                }
  
                if(!is_null($key->G24_C233)){
                    $datos[$i]['G24_C233'] = explode(' ', $key->G24_C233)[1];
                }

                $datos[$i]['G24_C234'] = $key->G24_C234;
  
                if(!is_null($key->G24_C235)){
                    $datos[$i]['G24_C235'] = explode(' ', $key->G24_C235)[1];
                }
  
                if(!is_null($key->G24_C236)){
                    $datos[$i]['G24_C236'] = explode(' ', $key->G24_C236)[1];
                }

                $datos[$i]['G24_C237'] = $key->G24_C237;

                $datos[$i]['G24_C238'] = $key->G24_C238;
  
                if(!is_null($key->G24_C239)){
                    $datos[$i]['G24_C239'] = explode(' ', $key->G24_C239)[1];
                }

                $datos[$i]['G24_C240'] = $key->G24_C240;
  
                if(!is_null($key->G24_C241)){
                    $datos[$i]['G24_C241'] = explode(' ', $key->G24_C241)[1];
                }
  
                if(!is_null($key->G24_C242)){
                    $datos[$i]['G24_C242'] = explode(' ', $key->G24_C242)[1];
                }

                $datos[$i]['G24_C243'] = $key->G24_C243;

                $datos[$i]['G24_C244'] = $key->G24_C244;

                $datos[$i]['G24_C247'] = $key->G24_C247;
      
                $datos[$i]['principal'] = $key->principal;
                $i++;
            }
            echo json_encode($datos);
        }

        //Datos del formulario
        if(isset($_POST['CallDatos_2'])){
          
            $str_Lsql = 'SELECT G24_ConsInte__b, G24_C243 as principal ,G24_C219,G24_C220,G24_C221,G24_C222,G24_C223,G24_C224,G24_C225,G24_C226,G24_C227,G24_C228,G24_C229,G24_C230,G24_C231,G24_C232,G24_C233,G24_C234,G24_C235,G24_C236,G24_C237,G24_C238,G24_C239,G24_C240,G24_C241,G24_C242,G24_C243,G24_C244,G24_C247 FROM '.$BaseDatos_systema.'.G24 WHERE G24_C248 ='.$_POST['id'];
            $result = $mysqli->query($str_Lsql);
            $datos = array();
            $i = 0;

            while($key = $result->fetch_object()){

                $datos[$i]['G24_C219'] = $key->G24_C219;
  
                if(!is_null($key->G24_C220)){
                    $datos[$i]['G24_C220'] = explode(' ', $key->G24_C220)[1];
                }

                if(!is_null($key->G24_C221)){
                    $datos[$i]['G24_C221'] = explode(' ', $key->G24_C221)[1];
                }

                $datos[$i]['G24_C222'] = $key->G24_C222;
  
                if(!is_null($key->G24_C223)){
                    $datos[$i]['G24_C223'] = explode(' ', $key->G24_C223)[1];
                }
  
                if(!is_null($key->G24_C224)){
                    $datos[$i]['G24_C224'] = explode(' ', $key->G24_C224)[1];
                }

                $datos[$i]['G24_C225'] = $key->G24_C225;
  
                if(!is_null($key->G24_C226)){
                    $datos[$i]['G24_C226'] = explode(' ', $key->G24_C226)[1];
                }
  
                if(!is_null($key->G24_C227)){
                    $datos[$i]['G24_C227'] = explode(' ', $key->G24_C227)[1];
                }

                $datos[$i]['G24_C228'] = $key->G24_C228;
  
                if(!is_null($key->G24_C229)){
                    $datos[$i]['G24_C229'] = explode(' ', $key->G24_C229)[1];
                }
  
                if(!is_null($key->G24_C230)){
                    $datos[$i]['G24_C230'] = explode(' ', $key->G24_C230)[1];
                }

                $datos[$i]['G24_C231'] = $key->G24_C231;
  
                if(!is_null($key->G24_C232)){
                    $datos[$i]['G24_C232'] = explode(' ', $key->G24_C232)[1];
                }
  
                if(!is_null($key->G24_C233)){
                    $datos[$i]['G24_C233'] = explode(' ', $key->G24_C233)[1];
                }

                $datos[$i]['G24_C234'] = $key->G24_C234;
  
                if(!is_null($key->G24_C235)){
                    $datos[$i]['G24_C235'] = explode(' ', $key->G24_C235)[1];
                }
  
                if(!is_null($key->G24_C236)){
                    $datos[$i]['G24_C236'] = explode(' ', $key->G24_C236)[1];
                }

                $datos[$i]['G24_C237'] = $key->G24_C237;

                $datos[$i]['G24_C238'] = $key->G24_C238;
  
                if(!is_null($key->G24_C239)){
                    $datos[$i]['G24_C239'] = explode(' ', $key->G24_C239)[1];
                }

                $datos[$i]['G24_C240'] = $key->G24_C240;
  
                if(!is_null($key->G24_C241)){
                    $datos[$i]['G24_C241'] = explode(' ', $key->G24_C241)[1];
                }
  
                if(!is_null($key->G24_C242)){
                    $datos[$i]['G24_C242'] = explode(' ', $key->G24_C242)[1];
                }

                $datos[$i]['G24_C243'] = $key->G24_C243;

                $datos[$i]['G24_C244'] = $key->G24_C244;

                $datos[$i]['G24_C247'] = $key->G24_C247;
      
                $datos[$i]['principal'] = $key->principal;

                $datos[$i]['G24_ConsInte__b'] = $key->G24_ConsInte__b;
                $i++;
            }
            echo json_encode($datos);
        }

        //Datos de la lista de la izquierda
        if(isset($_POST['CallDatosJson'])){
            $str_Lsql = "SELECT G24_ConsInte__b as id,  G24_C243 as camp1 , G24_C244 as camp2 ";
            $str_Lsql .= " FROM ".$BaseDatos.".G24   ";
            if($_POST['Busqueda'] != '' && !is_null($_POST['Busqueda'])){
                $str_Lsql .= " WHERE  like '%".$_POST['Busqueda']."%' ";
                $str_Lsql .= " OR  like '%".$_POST['Busqueda']."%' ";
            }

            $PEOBUS_VeRegPro__b = 0 ;
            $idUsuario = $_SESSION['IDENTIFICACION'];
            $peobus = "SELECT * FROM ".$BaseDatos_systema.".PEOBUS WHERE PEOBUS_ConsInte__USUARI_b = ".$idUsuario." AND PEOBUS_ConsInte__GUION__b = 24";
            $query = $mysqli->query($peobus);
            

            while ($key =  $query->fetch_object()) {
                $PEOBUS_VeRegPro__b = $key->PEOBUS_VeRegPro__b ;
            }

            if($PEOBUS_VeRegPro__b != 0){
                if($_POST['Busqueda'] != '' && !is_null($_POST['Busqueda'])){
                    $str_Lsql .= " AND G24_Usuario = ".$idUsuario;
                }else{
                    $str_Lsql .= " WHERE G24_Usuario = ".$idUsuario;
                }
        
            }

            $str_Lsql .= ' ORDER BY G24_ConsInte__b DESC LIMIT 0, 50'; 
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

        





        if(isset($_POST['CallEliminate'])){
            if($_POST['oper'] == 'del'){
                $str_Lsql = "DELETE FROM ".$BaseDatos.".G24 WHERE G24_ConsInte__b = ".$_POST['id'];
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
            $Zsql = "SELECT  G24_ConsInte__b as id,  G24_C243 as camp1 , G24_C244 as camp2  FROM ".$BaseDatos.".G24   ORDER BY G24_ConsInte__b DESC LIMIT ".$inicio." , ".$fin;
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
            $str_LsqlU = "UPDATE ".$BaseDatos_systema.".G24 SET "; 
            $str_LsqlI = "INSERT INTO ".$BaseDatos_systema.".G24(";
            $str_LsqlV = " VALUES ("; 
  
            $G24_C219 = 0;
            //este es tipo check primeo si viene y de acuerdo a su valor se pone 1 o 0
            if(isset($_POST["G24_C219"])){
                if($_POST["G24_C219"] == 'Yes'){
                    $G24_C219 = 1;
                }else if($_POST["G24_C219"] == 'off'){
                    $G24_C219 = 0;
                }else if($_POST["G24_C219"] == 'on'){
                    $G24_C219 = 1;
                }else if($_POST["G24_C219"] == 'No'){
                    $G24_C219 = 0;
                }else{
                    $G24_C219 = $_POST["G24_C219"] ;
                }   

                $separador = "";
                if($validar == 1){
                    $separador = ",";
                }

                $str_LsqlU .= $separador." G24_C219 = ".$G24_C219."";
                $str_LsqlI .= $separador." G24_C219";
                $str_LsqlV .= $separador.$G24_C219;

                $validar = 1;
            }else{
                $separador = "";
                if($validar == 1){
                    $separador = ",";
                }

                $str_LsqlU .= $separador." G24_C219 = ".$G24_C219."";
                $str_LsqlI .= $separador." G24_C219";
                $str_LsqlV .= $separador.$G24_C219;

                $validar = 1;
            }
  
            $G24_C220 = NULL;
            //este es de tipo date hay que preguntar si esta vacia o no o esta undefined
            if(isset($_POST["G24_C220"])){   
                if($_POST["G24_C220"] != '' && $_POST["G24_C220"] != 'undefined' && $_POST["G24_C220"] != 'null'){
                    $separador = "";
                    $fecha = date('Y-m-d');
                    if($validar == 1){
                        $separador = ",";
                    }

                    $G24_C220 = "'".$fecha." ".str_replace(' ', '',$_POST["G24_C220"])."'";
                    $str_LsqlU .= $separador." G24_C220 = ".$G24_C220."";
                    $str_LsqlI .= $separador." G24_C220";
                    $str_LsqlV .= $separador.$G24_C220;
                    $validar = 1;
                }
            }
  
            if(isset($_POST["G24_C221"])){
                $separador = "";
                $fecha = date('Y-m-d');
                if($validar == 1){
                    $separador = ",";
                }

                $str_LsqlU .= $separador." G24_C221 = '".$fecha." ".str_replace(' ', '',$_POST["G24_C221"])."'";
                $str_LsqlI .= $separador."G24_C221";
                $str_LsqlV .= $separador."'".$fecha." ".str_replace(' ', '',$_POST["G24_C221"])."'";
                $validar = 1;
            }
             
  
            $G24_C222 = 0;
            //este es tipo check primeo si viene y de acuerdo a su valor se pone 1 o 0
            if(isset($_POST["G24_C222"])){
                if($_POST["G24_C222"] == 'Yes'){
                    $G24_C222 = 1;
                }else if($_POST["G24_C222"] == 'off'){
                    $G24_C222 = 0;
                }else if($_POST["G24_C222"] == 'on'){
                    $G24_C222 = 1;
                }else if($_POST["G24_C222"] == 'No'){
                    $G24_C222 = 0;
                }else{
                    $G24_C222 = $_POST["G24_C222"] ;
                }   

                $separador = "";
                if($validar == 1){
                    $separador = ",";
                }

                $str_LsqlU .= $separador." G24_C222 = ".$G24_C222."";
                $str_LsqlI .= $separador." G24_C222";
                $str_LsqlV .= $separador.$G24_C222;

                $validar = 1;
            }else{
                $separador = "";
                if($validar == 1){
                    $separador = ",";
                }

                $str_LsqlU .= $separador." G24_C222 = ".$G24_C222."";
                $str_LsqlI .= $separador." G24_C222";
                $str_LsqlV .= $separador.$G24_C222;

                $validar = 1;
            }
  
            $G24_C223 = NULL;
            //este es de tipo date hay que preguntar si esta vacia o no o esta undefined
            if(isset($_POST["G24_C223"])){   
                if($_POST["G24_C223"] != '' && $_POST["G24_C223"] != 'undefined' && $_POST["G24_C223"] != 'null'){
                    $separador = "";
                    $fecha = date('Y-m-d');
                    if($validar == 1){
                        $separador = ",";
                    }

                    $G24_C223 = "'".$fecha." ".str_replace(' ', '',$_POST["G24_C223"])."'";
                    $str_LsqlU .= $separador." G24_C223 = ".$G24_C223."";
                    $str_LsqlI .= $separador." G24_C223";
                    $str_LsqlV .= $separador.$G24_C223;
                    $validar = 1;
                }
            }
  
            $G24_C224 = NULL;
            //este es de tipo date hay que preguntar si esta vacia o no o esta undefined
            if(isset($_POST["G24_C224"])){   
                if($_POST["G24_C224"] != '' && $_POST["G24_C224"] != 'undefined' && $_POST["G24_C224"] != 'null'){
                    $separador = "";
                    $fecha = date('Y-m-d');
                    if($validar == 1){
                        $separador = ",";
                    }

                    $G24_C224 = "'".$fecha." ".str_replace(' ', '',$_POST["G24_C224"])."'";
                    $str_LsqlU .= $separador." G24_C224 = ".$G24_C224."";
                    $str_LsqlI .= $separador." G24_C224";
                    $str_LsqlV .= $separador.$G24_C224;
                    $validar = 1;
                }
            }
  
            $G24_C225 = 0;
            //este es tipo check primeo si viene y de acuerdo a su valor se pone 1 o 0
            if(isset($_POST["G24_C225"])){
                if($_POST["G24_C225"] == 'Yes'){
                    $G24_C225 = 1;
                }else if($_POST["G24_C225"] == 'off'){
                    $G24_C225 = 0;
                }else if($_POST["G24_C225"] == 'on'){
                    $G24_C225 = 1;
                }else if($_POST["G24_C225"] == 'No'){
                    $G24_C225 = 0;
                }else{
                    $G24_C225 = $_POST["G24_C225"] ;
                }   

                $separador = "";
                if($validar == 1){
                    $separador = ",";
                }

                $str_LsqlU .= $separador." G24_C225 = ".$G24_C225."";
                $str_LsqlI .= $separador." G24_C225";
                $str_LsqlV .= $separador.$G24_C225;

                $validar = 1;
            }else{
                $separador = "";
                if($validar == 1){
                    $separador = ",";
                }

                $str_LsqlU .= $separador." G24_C225 = ".$G24_C225."";
                $str_LsqlI .= $separador." G24_C225";
                $str_LsqlV .= $separador.$G24_C225;

                $validar = 1;
            }
  
            $G24_C226 = NULL;
            //este es de tipo date hay que preguntar si esta vacia o no o esta undefined
            if(isset($_POST["G24_C226"])){   
                if($_POST["G24_C226"] != '' && $_POST["G24_C226"] != 'undefined' && $_POST["G24_C226"] != 'null'){
                    $separador = "";
                    $fecha = date('Y-m-d');
                    if($validar == 1){
                        $separador = ",";
                    }

                    $G24_C226 = "'".$fecha." ".str_replace(' ', '',$_POST["G24_C226"])."'";
                    $str_LsqlU .= $separador." G24_C226 = ".$G24_C226."";
                    $str_LsqlI .= $separador." G24_C226";
                    $str_LsqlV .= $separador.$G24_C226;
                    $validar = 1;
                }
            }
  
            $G24_C227 = NULL;
            //este es de tipo date hay que preguntar si esta vacia o no o esta undefined
            if(isset($_POST["G24_C227"])){   
                if($_POST["G24_C227"] != '' && $_POST["G24_C227"] != 'undefined' && $_POST["G24_C227"] != 'null'){
                    $separador = "";
                    $fecha = date('Y-m-d');
                    if($validar == 1){
                        $separador = ",";
                    }

                    $G24_C227 = "'".$fecha." ".str_replace(' ', '',$_POST["G24_C227"])."'";
                    $str_LsqlU .= $separador." G24_C227 = ".$G24_C227."";
                    $str_LsqlI .= $separador." G24_C227";
                    $str_LsqlV .= $separador.$G24_C227;
                    $validar = 1;
                }
            }
  
            $G24_C228 = 0;
            //este es tipo check primeo si viene y de acuerdo a su valor se pone 1 o 0
            if(isset($_POST["G24_C228"])){
                if($_POST["G24_C228"] == 'Yes'){
                    $G24_C228 = 1;
                }else if($_POST["G24_C228"] == 'off'){
                    $G24_C228 = 0;
                }else if($_POST["G24_C228"] == 'on'){
                    $G24_C228 = 1;
                }else if($_POST["G24_C228"] == 'No'){
                    $G24_C228 = 0;
                }else{
                    $G24_C228 = $_POST["G24_C228"] ;
                }   

                $separador = "";
                if($validar == 1){
                    $separador = ",";
                }

                $str_LsqlU .= $separador." G24_C228 = ".$G24_C228."";
                $str_LsqlI .= $separador." G24_C228";
                $str_LsqlV .= $separador.$G24_C228;

                $validar = 1;
            }else{
                $separador = "";
                if($validar == 1){
                    $separador = ",";
                }

                $str_LsqlU .= $separador." G24_C228 = ".$G24_C228."";
                $str_LsqlI .= $separador." G24_C228";
                $str_LsqlV .= $separador.$G24_C228;

                $validar = 1;
            }
  
            $G24_C229 = NULL;
            //este es de tipo date hay que preguntar si esta vacia o no o esta undefined
            if(isset($_POST["G24_C229"])){   
                if($_POST["G24_C229"] != '' && $_POST["G24_C229"] != 'undefined' && $_POST["G24_C229"] != 'null'){
                    $separador = "";
                    $fecha = date('Y-m-d');
                    if($validar == 1){
                        $separador = ",";
                    }

                    $G24_C229 = "'".$fecha." ".str_replace(' ', '',$_POST["G24_C229"])."'";
                    $str_LsqlU .= $separador." G24_C229 = ".$G24_C229."";
                    $str_LsqlI .= $separador." G24_C229";
                    $str_LsqlV .= $separador.$G24_C229;
                    $validar = 1;
                }
            }
  
            $G24_C230 = NULL;
            //este es de tipo date hay que preguntar si esta vacia o no o esta undefined
            if(isset($_POST["G24_C230"])){   
                if($_POST["G24_C230"] != '' && $_POST["G24_C230"] != 'undefined' && $_POST["G24_C230"] != 'null'){
                    $separador = "";
                    $fecha = date('Y-m-d');
                    if($validar == 1){
                        $separador = ",";
                    }

                    $G24_C230 = "'".$fecha." ".str_replace(' ', '',$_POST["G24_C230"])."'";
                    $str_LsqlU .= $separador." G24_C230 = ".$G24_C230."";
                    $str_LsqlI .= $separador." G24_C230";
                    $str_LsqlV .= $separador.$G24_C230;
                    $validar = 1;
                }
            }
  
            $G24_C231 = 0;
            //este es tipo check primeo si viene y de acuerdo a su valor se pone 1 o 0
            if(isset($_POST["G24_C231"])){
                if($_POST["G24_C231"] == 'Yes'){
                    $G24_C231 = 1;
                }else if($_POST["G24_C231"] == 'off'){
                    $G24_C231 = 0;
                }else if($_POST["G24_C231"] == 'on'){
                    $G24_C231 = 1;
                }else if($_POST["G24_C231"] == 'No'){
                    $G24_C231 = 0;
                }else{
                    $G24_C231 = $_POST["G24_C231"] ;
                }   

                $separador = "";
                if($validar == 1){
                    $separador = ",";
                }

                $str_LsqlU .= $separador." G24_C231 = ".$G24_C231."";
                $str_LsqlI .= $separador." G24_C231";
                $str_LsqlV .= $separador.$G24_C231;

                $validar = 1;
            }else{
                $separador = "";
                if($validar == 1){
                    $separador = ",";
                }

                $str_LsqlU .= $separador." G24_C231 = ".$G24_C231."";
                $str_LsqlI .= $separador." G24_C231";
                $str_LsqlV .= $separador.$G24_C231;

                $validar = 1;
            }
  
            $G24_C232 = NULL;
            //este es de tipo date hay que preguntar si esta vacia o no o esta undefined
            if(isset($_POST["G24_C232"])){   
                if($_POST["G24_C232"] != '' && $_POST["G24_C232"] != 'undefined' && $_POST["G24_C232"] != 'null'){
                    $separador = "";
                    $fecha = date('Y-m-d');
                    if($validar == 1){
                        $separador = ",";
                    }

                    $G24_C232 = "'".$fecha." ".str_replace(' ', '',$_POST["G24_C232"])."'";
                    $str_LsqlU .= $separador." G24_C232 = ".$G24_C232."";
                    $str_LsqlI .= $separador." G24_C232";
                    $str_LsqlV .= $separador.$G24_C232;
                    $validar = 1;
                }
            }
  
            $G24_C233 = NULL;
            //este es de tipo date hay que preguntar si esta vacia o no o esta undefined
            if(isset($_POST["G24_C233"])){   
                if($_POST["G24_C233"] != '' && $_POST["G24_C233"] != 'undefined' && $_POST["G24_C233"] != 'null'){
                    $separador = "";
                    $fecha = date('Y-m-d');
                    if($validar == 1){
                        $separador = ",";
                    }

                    $G24_C233 = "'".$fecha." ".str_replace(' ', '',$_POST["G24_C233"])."'";
                    $str_LsqlU .= $separador." G24_C233 = ".$G24_C233."";
                    $str_LsqlI .= $separador." G24_C233";
                    $str_LsqlV .= $separador.$G24_C233;
                    $validar = 1;
                }
            }
  
            $G24_C234 = 0;
            //este es tipo check primeo si viene y de acuerdo a su valor se pone 1 o 0
            if(isset($_POST["G24_C234"])){
                if($_POST["G24_C234"] == 'Yes'){
                    $G24_C234 = 1;
                }else if($_POST["G24_C234"] == 'off'){
                    $G24_C234 = 0;
                }else if($_POST["G24_C234"] == 'on'){
                    $G24_C234 = 1;
                }else if($_POST["G24_C234"] == 'No'){
                    $G24_C234 = 0;
                }else{
                    $G24_C234 = $_POST["G24_C234"] ;
                }   

                $separador = "";
                if($validar == 1){
                    $separador = ",";
                }

                $str_LsqlU .= $separador." G24_C234 = ".$G24_C234."";
                $str_LsqlI .= $separador." G24_C234";
                $str_LsqlV .= $separador.$G24_C234;

                $validar = 1;
            }else{
                $separador = "";
                if($validar == 1){
                    $separador = ",";
                }

                $str_LsqlU .= $separador." G24_C234 = ".$G24_C234."";
                $str_LsqlI .= $separador." G24_C234";
                $str_LsqlV .= $separador.$G24_C234;

                $validar = 1;
            }
  
            $G24_C235 = NULL;
            //este es de tipo date hay que preguntar si esta vacia o no o esta undefined
            if(isset($_POST["G24_C235"])){   
                if($_POST["G24_C235"] != '' && $_POST["G24_C235"] != 'undefined' && $_POST["G24_C235"] != 'null'){
                    $separador = "";
                    $fecha = date('Y-m-d');
                    if($validar == 1){
                        $separador = ",";
                    }

                    $G24_C235 = "'".$fecha." ".str_replace(' ', '',$_POST["G24_C235"])."'";
                    $str_LsqlU .= $separador." G24_C235 = ".$G24_C235."";
                    $str_LsqlI .= $separador." G24_C235";
                    $str_LsqlV .= $separador.$G24_C235;
                    $validar = 1;
                }
            }
  
            $G24_C236 = NULL;
            //este es de tipo date hay que preguntar si esta vacia o no o esta undefined
            if(isset($_POST["G24_C236"])){   
                if($_POST["G24_C236"] != '' && $_POST["G24_C236"] != 'undefined' && $_POST["G24_C236"] != 'null'){
                    $separador = "";
                    $fecha = date('Y-m-d');
                    if($validar == 1){
                        $separador = ",";
                    }

                    $G24_C236 = "'".$fecha." ".str_replace(' ', '',$_POST["G24_C236"])."'";
                    $str_LsqlU .= $separador." G24_C236 = ".$G24_C236."";
                    $str_LsqlI .= $separador." G24_C236";
                    $str_LsqlV .= $separador.$G24_C236;
                    $validar = 1;
                }
            }
  
            $G24_C237 = 0;
            //este es tipo check primeo si viene y de acuerdo a su valor se pone 1 o 0
            if(isset($_POST["G24_C237"])){
                if($_POST["G24_C237"] == 'Yes'){
                    $G24_C237 = 1;
                }else if($_POST["G24_C237"] == 'off'){
                    $G24_C237 = 0;
                }else if($_POST["G24_C237"] == 'on'){
                    $G24_C237 = 1;
                }else if($_POST["G24_C237"] == 'No'){
                    $G24_C237 = 0;
                }else{
                    $G24_C237 = $_POST["G24_C237"] ;
                }   

                $separador = "";
                if($validar == 1){
                    $separador = ",";
                }

                $str_LsqlU .= $separador." G24_C237 = ".$G24_C237."";
                $str_LsqlI .= $separador." G24_C237";
                $str_LsqlV .= $separador.$G24_C237;

                $validar = 1;
            }else{
                $separador = "";
                if($validar == 1){
                    $separador = ",";
                }

                $str_LsqlU .= $separador." G24_C237 = ".$G24_C237."";
                $str_LsqlI .= $separador." G24_C237";
                $str_LsqlV .= $separador.$G24_C237;

                $validar = 1;
            }
  
            if(isset($_POST["G24_C238"])){
                if($_POST["G24_C238"] != '' && $_POST["G24_C238"] != 'undefined' && $_POST["G24_C238"] != 'null'){
                    $separador = "";
                    $fecha = date('Y-m-d');
                    if($validar == 1){
                        $separador = ",";
                    }

                    $G24_C238 = "'".$fecha." ".str_replace(' ', '',$_POST["G24_C238"])."'";
                    $str_LsqlU .= $separador." G24_C238 = ".$G24_C238."";
                    $str_LsqlI .= $separador." G24_C238";
                    $str_LsqlV .= $separador.$G24_C238;
                    $validar = 1;
                }
            }
             
  
            $G24_C239 = NULL;
            //este es de tipo date hay que preguntar si esta vacia o no o esta undefined
            if(isset($_POST["G24_C239"])){   
                if($_POST["G24_C239"] != '' && $_POST["G24_C239"] != 'undefined' && $_POST["G24_C239"] != 'null'){
                    $separador = "";
                    $fecha = date('Y-m-d');
                    if($validar == 1){
                        $separador = ",";
                    }

                    $G24_C239 = "'".$fecha." ".str_replace(' ', '',$_POST["G24_C239"])."'";
                    $str_LsqlU .= $separador." G24_C239 = ".$G24_C239."";
                    $str_LsqlI .= $separador." G24_C239";
                    $str_LsqlV .= $separador.$G24_C239;
                    $validar = 1;
                }
            }
  
            $G24_C240 = 0;
            //este es tipo check primeo si viene y de acuerdo a su valor se pone 1 o 0
            if(isset($_POST["G24_C240"])){
                if($_POST["G24_C240"] == 'Yes'){
                    $G24_C240 = 1;
                }else if($_POST["G24_C240"] == 'off'){
                    $G24_C240 = 0;
                }else if($_POST["G24_C240"] == 'on'){
                    $G24_C240 = 1;
                }else if($_POST["G24_C240"] == 'No'){
                    $G24_C240 = 0;
                }else{
                    $G24_C240 = $_POST["G24_C240"] ;
                }   

                $separador = "";
                if($validar == 1){
                    $separador = ",";
                }

                $str_LsqlU .= $separador." G24_C240 = ".$G24_C240."";
                $str_LsqlI .= $separador." G24_C240";
                $str_LsqlV .= $separador.$G24_C240;

                $validar = 1;
            }else{
                $separador = "";
                if($validar == 1){
                    $separador = ",";
                }

                $str_LsqlU .= $separador." G24_C240 = ".$G24_C240."";
                $str_LsqlI .= $separador." G24_C240";
                $str_LsqlV .= $separador.$G24_C240;

                $validar = 1;
            }
  
            $G24_C241 = NULL;
            //este es de tipo date hay que preguntar si esta vacia o no o esta undefined
            if(isset($_POST["G24_C241"])){   
                if($_POST["G24_C241"] != '' && $_POST["G24_C241"] != 'undefined' && $_POST["G24_C241"] != 'null'){
                    $separador = "";
                    $fecha = date('Y-m-d');
                    if($validar == 1){
                        $separador = ",";
                    }

                    $G24_C241 = "'".$fecha." ".str_replace(' ', '',$_POST["G24_C241"])."'";
                    $str_LsqlU .= $separador." G24_C241 = ".$G24_C241."";
                    $str_LsqlI .= $separador." G24_C241";
                    $str_LsqlV .= $separador.$G24_C241;
                    $validar = 1;
                }
            }
  
            $G24_C242 = NULL;
            //este es de tipo date hay que preguntar si esta vacia o no o esta undefined
            if(isset($_POST["G24_C242"])){   
                if($_POST["G24_C242"] != '' && $_POST["G24_C242"] != 'undefined' && $_POST["G24_C242"] != 'null'){
                    $separador = "";
                    $fecha = date('Y-m-d');
                    if($validar == 1){
                        $separador = ",";
                    }

                    $G24_C242 = "'".$fecha." ".str_replace(' ', '',$_POST["G24_C242"])."'";
                    $str_LsqlU .= $separador." G24_C242 = ".$G24_C242."";
                    $str_LsqlI .= $separador." G24_C242";
                    $str_LsqlV .= $separador.$G24_C242;
                    $validar = 1;
                }
            }
  
            if(isset($_POST["G24_C243"])){
                $separador = "";
                if($validar == 1){
                    $separador = ",";
                }

                $str_LsqlU .= $separador."G24_C243 = '".$_POST["G24_C243"]."'";
                $str_LsqlI .= $separador."G24_C243";
                $str_LsqlV .= $separador."'".$_POST["G24_C243"]."'";
                $validar = 1;
            }
             
  
            $G24_C244 = 0;
            //este es tipo check primeo si viene y de acuerdo a su valor se pone 1 o 0
            if(isset($_POST["G24_C244"])){
                if($_POST["G24_C244"] == 'Yes'){
                    $G24_C244 = 1;
                }else if($_POST["G24_C244"] == 'off'){
                    $G24_C244 = 0;
                }else if($_POST["G24_C244"] == 'on'){
                    $G24_C244 = 1;
                }else if($_POST["G24_C244"] == 'No'){
                    $G24_C244 = 0;
                }else{
                    $G24_C244 = $_POST["G24_C244"] ;
                }   

                $separador = "";
                if($validar == 1){
                    $separador = ",";
                }

                $str_LsqlU .= $separador." G24_C244 = ".$G24_C244."";
                $str_LsqlI .= $separador." G24_C244";
                $str_LsqlV .= $separador.$G24_C244;

                $validar = 1;
            }else{
                $separador = "";
                if($validar == 1){
                    $separador = ",";
                }

                $str_LsqlU .= $separador." G24_C244 = ".$G24_C244."";
                $str_LsqlI .= $separador." G24_C244";
                $str_LsqlV .= $separador.$G24_C244;

                $validar = 1;
            }
  
            $G24_C247 = NULL;
            //este es de tipo numero no se deja ir asi '', si est avacio lo mejor es no mandarlo
            if(isset($_POST["G24_C247"])){
                if($_POST["G24_C247"] != ''){
                    $separador = "";
                    if($validar == 1){
                        $separador = ",";
                    }

                    //$G24_C247 = $_POST["G24_C247"];
                    $G24_C247 = str_replace(".", "", $_POST["G24_C247"]);
                    $G24_C247 =  str_replace(",", ".", $G24_C247);
                    $str_LsqlU .= $separador." G24_C247 = '".$G24_C247."'";
                    $str_LsqlI .= $separador." G24_C247";
                    $str_LsqlV .= $separador."'".$G24_C247."'";
                    $validar = 1;
                }
            }

            /*$G24_C248 = NULL;
            //este es de tipo numero no se deja ir asi '', si est avacio lo mejor es no mandarlo
            if(isset($_POST["G24_C248"])){
                if($_POST["G24_C248"] != ''){
                    $separador = "";
                    if($validar == 1){
                        $separador = ",";
                    }

                    //$G24_C247 = $_POST["G24_C247"];
                    $G24_C247 = str_replace(".", "", $_POST["G24_C248"]);
                    $G24_C247 =  str_replace(",", ".", $G24_C248);
                    $str_LsqlU .= $separador." G24_C248 = '".$G24_C248."'";
                    $str_LsqlI .= $separador." G24_C248";
                    $str_LsqlV .= $separador."'".$G24_C248."'";
                    $validar = 1;
                }
            }*/
 
            $padre = NULL;
            //este es de tipo date hay que preguntar si esta vacia o no
            if(isset($_POST["padre"])){    
                if($_POST["padre"] != '0' && $_POST['padre'] != ''){
                    $separador = "";
                    if($validar == 1){
                        $separador = ",";
                    }


                    $str_LsqlU .= $separador." G24_C248= ".$_POST["padre"];
                    $str_LsqlI .= $separador." G24_C248";
                    $str_LsqlV .= $separador.$_POST['padre'] ;
                    $validar = 1;
                }
            }

            if(isset($_GET['id_gestion_cbx'])){
                $separador = "";
                if($validar == 1){
                    $separador = ",";
                }

                $str_LsqlU .= $separador."G24_IdLlamada = '".$_GET['id_gestion_cbx']."'";
                $str_LsqlI .= $separador."G24_IdLlamada";
                $str_LsqlV .= $separador."'".$_GET['id_gestion_cbx']."'";
                $validar = 1;
            }



            if(isset($_POST['oper'])){
                if($_POST["oper"] == 'add' ){
                    
                    $str_Lsql = $str_LsqlI.")" . $str_LsqlV.")";
                }else if($_POST["oper"] == 'edit' ){
                    $str_Lsql = $str_LsqlU." WHERE G24_ConsInte__b =".$_POST["id"]; 
                }else if($_POST["oper"] == 'del' ){
                    $str_Lsql = "DELETE FROM ".$BaseDatos.".G24 WHERE G24_ConsInte__b = ".$_POST['id'];
                    $validar = 1;
                }
            }

            //si trae algo que insertar inserta

            echo $str_Lsql;
            if($validar == 1){
                if ($mysqli->query($str_Lsql) === TRUE) {
                    if($_POST["oper"] == 'add' ){
                       guardar_auditoria("INSERTAR", "INSERTAR REGISTRO EN G24");
                    }else if($_POST["oper"] == 'edit' ){
                       guardar_auditoria("ACTUALIZAR", "ACTUALIZO EL REGISTRO # ".$_POST["padre"]." EN G24");
                    }else if($_POST["oper"] == 'del' ){
                       guardar_auditoria("ELIMINAR", "ELIMINO EL REGISTRO # ".$_POST['id']." EN G24");
                    }
                    
                    echo $mysqli->insert_id;
                } else {
                    echo "Error Hacieno el proceso los registros : " . $mysqli->error;
                }
            }
        }
    }

    

    

?>
