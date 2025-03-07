<?php
    session_start();
    include(__DIR__."/../../conexion.php");
    include(__DIR__."/../../funciones.php");

    function guardar_auditoria($accion, $superAccion){
        include(__DIR__."/../../conexion.php");
        $Lsql = "INSERT INTO AUACAD (AUACAD_Fecha_____b , AUACAD_Hora______b, AUACAD_Ejecutor__b, AUACAD_TipoAcci__b , AUACAD_SubTipAcc_b, AUACAD_Accion____b , AUACAD_Huesped___b ) VALUES ('".date('Y-m-d')."', '".date('H:s:i')."', ".$_SESSION['IDENTIFICACION'].", 'G4', '".$accion."', '".$superAccion."', ".$_SESSION['HUESPED']." );";
        $mysqli->query($Lsql);
    }   

    if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
        //Funciones de la carga maestro y eso
        

        //Datos del formulario
        if(isset($_POST['CallDatos'])){
          
            $Lsql = 'SELECT G4_ConsInte__b, G4_FechaInsercion , G4_Usuario ,  G4_CodigoMiembro  , G4_PoblacionOrigen , G4_EstadoDiligenciamiento ,  G4_IdLlamada , G4_C21 as principal ,G4_C21,G4_C22,G4_C23,G4_C24,G4_C25,G4_C26,G4_C27 FROM '.$BaseDatos_systema.'.G4 WHERE G4_ConsInte__b ='.$_POST['id'];
            $result = $mysqli->query($Lsql);
            $datos = array();
            $i = 0;

            while($key = $result->fetch_object()){

                $datos[$i]['G4_C21'] = $key->G4_C21;

                $datos[$i]['G4_C22'] = $key->G4_C22;

                $datos[$i]['G4_C23'] = $key->G4_C23;

                $datos[$i]['G4_C24'] = $key->G4_C24;

                $datos[$i]['G4_C25'] = $key->G4_C25;

                $datos[$i]['G4_C26'] = $key->G4_C26;

                $datos[$i]['G4_C27'] = $key->G4_C27;
      
                $datos[$i]['principal'] = $key->principal;
                $i++;
            }
            echo json_encode($datos);
        }

        //Datos de la lista de la izquierda
        if(isset($_POST['CallDatosJson'])){
            $Lsql = 'SELECT G4_ConsInte__b as id,  G4_C21 as camp1 , G4_C22 as camp2 ';
            $Lsql .= ' FROM '.$BaseDatos_systema.'.G4   ';
            if($_POST['Busqueda'] != '' && !is_null($_POST['Busqueda'])){
                $Lsql .= ' WHERE  like "%'.$_POST['Busqueda'].'%" ';
                $Lsql .= ' OR  like "%'.$_POST['Busqueda'].'%" ';
            }

            $PEOBUS_VeRegPro__b = 0 ;
            $idUsuario = $_SESSION['IDENTIFICACION'];
            $peobus = "SELECT * FROM ".$BaseDatos_systema.".PEOBUS WHERE PEOBUS_ConsInte__USUARI_b = ".$idUsuario." AND PEOBUS_ConsInte__GUION__b = 4";
            $query = $mysqli->query($peobus);
            

            while ($key =  $query->fetch_object()) {
                $PEOBUS_VeRegPro__b = $key->PEOBUS_VeRegPro__b ;
            }

            if($PEOBUS_VeRegPro__b != 0){
                if($_POST['Busqueda'] != '' && !is_null($_POST['Busqueda'])){
                    $Lsql .= ' AND G4_Usuario = '.$idUsuario;
                }else{
                    $Lsql .= ' WHERE G4_Usuario = '.$idUsuario;
                }
        
            }

            $Lsql .= ' ORDER BY G4_ConsInte__b DESC LIMIT 0, 50'; 
            $result = $mysqli->query($Lsql);
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
            $Lsql = "SELECT LISOPC_ConsInte__b AS OPCION_ConsInte__b, LISOPC_Nombre____b AS OPCION_Nombre____b FROM ".$BaseDatos_systema.".LISOPC WHERE LISOPC_ConsInte__OPCION_b = ".$lista." ORDER BY LISOPC_Nombre____b";
            
            $combo = $mysqli->query($Lsql);
            echo '<select class="form-control input-sm"  name="'.$comboe.'" id="'.$comboe.'">';
            echo '<option value="0">Seleccione</option>';
            while($obj = $combo->fetch_object()){
                echo "<option value='".$obj->OPCION_ConsInte__b."'>".$obj->OPCION_Nombre____b."</option>";
            }   
            echo '</select>'; 
        } 

        





        if(isset($_POST['CallEliminate'])){
            if($_POST['oper'] == 'del'){
                $Lsql = "DELETE FROM ".$BaseDatos_systema.".G4 WHERE G4_ConsInte__b = ".$_POST['id'];
                if ($mysqli->query($Lsql) === TRUE) {
                    echo "1";
                } else {
                    echo "Error eliminado los registros : " . $mysqli->error;
                }
            }
        }

        if(isset($_POST['callDatosNuevamente'])){
            $inicio = $_POST['inicio'];
            $fin = $_POST['fin'];
            $Zsql = 'SELECT  G4_ConsInte__b as id,  G4_C21 as camp1 , G4_C22 as camp2  FROM '.$BaseDatos_systema.'.$BaseDatos_systema.".G4   ORDER BY G4_ConsInte__b DESC LIMIT '.$inicio.' , '.$fin;
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
            $Lsql  = '';

            $validar = 0;
            $LsqlU = "UPDATE ".$BaseDatos_systema.".G4 SET "; 
            $LsqlI = "INSERT INTO ".$BaseDatos_systema.".G4(";
            $LsqlV = " VALUES ("; 
  
            if(isset($_POST["G4_C21"])){
                $separador = "";
                if($validar == 1){
                    $separador = ",";
                }

                $LsqlU .= $separador."G4_C21 = '".$_POST["G4_C21"]."'";
                $LsqlI .= $separador."G4_C21";
                $LsqlV .= $separador."'".$_POST["G4_C21"]."'";
                $validar = 1;
            }
             
  
            $G4_C22 = NULL;
            //este es de tipo numero no se deja ir asi '', si est avacio lo mejor es no mandarlo
            if(isset($_POST["G4_C22"])){
                if($_POST["G4_C22"] != ''){
                    $separador = "";
                    if($validar == 1){
                        $separador = ",";
                    }

                    //$G4_C22 = $_POST["G4_C22"];
                    $G4_C22 = str_replace(".", "", $_POST["G4_C22"]);
                    $G4_C22 =  str_replace(",", ".", $G4_C22);
                    $LsqlU .= $separador." G4_C22 = '".$G4_C22."'";
                    $LsqlI .= $separador." G4_C22";
                    $LsqlV .= $separador."'".$G4_C22."'";
                    $validar = 1;
                }
            }
  
            $G4_C23 = NULL;
            //este es de tipo numero no se deja ir asi '', si est avacio lo mejor es no mandarlo
            if(isset($_POST["G4_C23"])){
                if($_POST["G4_C23"] != ''){
                    $separador = "";
                    if($validar == 1){
                        $separador = ",";
                    }

                    //$G4_C23 = $_POST["G4_C23"];
                    $G4_C23 = str_replace(".", "", $_POST["G4_C23"]);
                    $G4_C23 =  str_replace(",", ".", $G4_C23);
                    $LsqlU .= $separador." G4_C23 = '".$G4_C23."'";
                    $LsqlI .= $separador." G4_C23";
                    $LsqlV .= $separador."'".$G4_C23."'";
                    $validar = 1;
                }
            }
  
            $G4_C24 = NULL;
            //este es de tipo numero no se deja ir asi '', si est avacio lo mejor es no mandarlo
            if(isset($_POST["G4_C24"])){
                if($_POST["G4_C24"] != ''){
                    $separador = "";
                    if($validar == 1){
                        $separador = ",";
                    }

                    //$G4_C24 = $_POST["G4_C24"];
                    $G4_C24 = str_replace(".", "", $_POST["G4_C24"]);
                    $G4_C24 =  str_replace(",", ".", $G4_C24);
                    $LsqlU .= $separador." G4_C24 = '".$G4_C24."'";
                    $LsqlI .= $separador." G4_C24";
                    $LsqlV .= $separador."'".$G4_C24."'";
                    $validar = 1;
                }
            }
  
            $G4_C25 = NULL;
            //este es de tipo numero no se deja ir asi '', si est avacio lo mejor es no mandarlo
            if(isset($_POST["G4_C25"])){
                if($_POST["G4_C25"] != ''){
                    $separador = "";
                    if($validar == 1){
                        $separador = ",";
                    }

                    //$G4_C25 = $_POST["G4_C25"];
                    $G4_C25 = str_replace(".", "", $_POST["G4_C25"]);
                    $G4_C25 =  str_replace(",", ".", $G4_C25);
                    $LsqlU .= $separador." G4_C25 = '".$G4_C25."'";
                    $LsqlI .= $separador." G4_C25";
                    $LsqlV .= $separador."'".$G4_C25."'";
                    $validar = 1;
                }
            }
  
            $G4_C26 = NULL;
            //este es de tipo numero no se deja ir asi '', si est avacio lo mejor es no mandarlo
            if(isset($_POST["G4_C26"])){
                if($_POST["G4_C26"] != ''){
                    $separador = "";
                    if($validar == 1){
                        $separador = ",";
                    }

                    //$G4_C26 = $_POST["G4_C26"];
                    $G4_C26 = str_replace(".", "", $_POST["G4_C26"]);
                    $G4_C26 =  str_replace(",", ".", $G4_C26);
                    $LsqlU .= $separador." G4_C26 = '".$G4_C26."'";
                    $LsqlI .= $separador." G4_C26";
                    $LsqlV .= $separador."'".$G4_C26."'";
                    $validar = 1;
                }
            }
  
            $G4_C27 = NULL;
            //este es de tipo numero no se deja ir asi '', si est avacio lo mejor es no mandarlo
            if(isset($_POST["G4_C27"])){
                if($_POST["G4_C27"] != ''){
                    $separador = "";
                    if($validar == 1){
                        $separador = ",";
                    }

                    //$G4_C27 = $_POST["G4_C27"];
                    $G4_C27 = str_replace(".", "", $_POST["G4_C27"]);
                    $G4_C27 =  str_replace(",", ".", $G4_C27);
                    $LsqlU .= $separador." G4_C27 = '".$G4_C27."'";
                    $LsqlI .= $separador." G4_C27";
                    $LsqlV .= $separador."'".$G4_C27."'";
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
                    $Lsql = "SELECT GUIDET_ConsInte__PREGUN_De1_b FROM ".$BaseDatos_systema.".GUIDET WHERE GUIDET_ConsInte__GUION__Mae_b = ".$_POST['formpadre']." AND 
GUIDET_ConsInte__GUION__Det_b = ".$_POST['formhijo'];

                    $GuidRes = $mysqli->query($Lsql);
                    $campo = null;
                    while($ky = $GuidRes->fetch_object()){
                        $campo = $ky->GUIDET_ConsInte__PREGUN_De1_b;
                    }
                    $valorG = "G4_C";
                    $valorH = $valorG.$campo;
                    $LsqlU .= $separador." " .$valorH." = ".$_POST["padre"];
                    $LsqlI .= $separador." ".$valorH;
                    $LsqlV .= $separador.$_POST['padre'] ;
                    $validar = 1;
                }
            }

            if(isset($_GET['id_gestion_cbx'])){
                $separador = "";
                if($validar == 1){
                    $separador = ",";
                }

                $LsqlU .= $separador."G4_IdLlamada = '".$_GET['id_gestion_cbx']."'";
                $LsqlI .= $separador."G4_IdLlamada";
                $LsqlV .= $separador."'".$_GET['id_gestion_cbx']."'";
                $validar = 1;
            }



            if(isset($_POST['oper'])){
                if($_POST["oper"] == 'add' ){
                    $LsqlI .= ", G4_Usuario , G4_FechaInsercion, G4_CodigoMiembro";
                    $LsqlV .= ", ".$_GET['usuario']." , '".date('Y-m-d H:i:s')."', ".$_GET['CodigoMiembro'];
                    $Lsql = $LsqlI.")" . $LsqlV.")";
                }else if($_POST["oper"] == 'edit' ){
                    $Lsql = $LsqlU." WHERE G4_ConsInte__b =".$_POST["id"]; 
                }else if($_POST["oper"] == 'del' ){
                    $Lsql = "DELETE FROM G4 WHERE G4_ConsInte__b = ".$_POST['id'];
                    $validar = 1;
                }
            }

            //si trae algo que insertar inserta

            //echo $Lsql;
            if($validar == 1){
                if ($mysqli->query($Lsql) === TRUE) {
                    if($_POST["oper"] == 'add' ){
                       guardar_auditoria("INSERTAR", "INSERTAR REGISTRO EN G4");
                    }else if($_POST["oper"] == 'edit' ){
                       guardar_auditoria("ACTUALIZAR", "ACTUALIZO EL REGISTRO # ".$_POST["padre"]." EN G4");
                    }else if($_POST["oper"] == 'del' ){
                       guardar_auditoria("ELIMINAR", "ELIMINO EL REGISTRO # ".$_POST['id']." EN G4");
                    }
                    
                    echo $mysqli->insert_id;
                } else {
                    echo "Error Hacieno el proceso los registros : " . $mysqli->error;
                }
            }
        }
    }

?>
