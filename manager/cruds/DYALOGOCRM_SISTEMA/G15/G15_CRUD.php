<?php
    session_start();
    ini_set('display_errors', 'On');
    ini_set('display_errors', 1);
    include_once(__DIR__."../../../../pages/conexion.php");
    include_once(__DIR__."../../../../../crm_php/funciones.php");

    function guardar_auditoria($accion, $superAccion){
        global $mysqli;
        global $BaseDatos_systema;
        $str_Lsql = "INSERT INTO ".$BaseDatos_systema."AUACAD (AUACAD_Fecha_____b , AUACAD_Hora______b, AUACAD_Ejecutor__b, AUACAD_TipoAcci__b , AUACAD_SubTipAcc_b, AUACAD_Accion____b , AUACAD_Huesped___b ) VALUES ('".date('Y-m-d H:s:i')."', '".date('Y-m-d H:s:i')."', ".$_SESSION['IDENTIFICACION'].", 'G15', '".$accion."', '".$superAccion."', ".$_SESSION['HUESPED']." );";
        $mysqli->query($str_Lsql);
    }   

    if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
        //Funciones de la carga maestro y eso
        

        //Datos del formulario
        if(isset($_POST['CallDatos'])){
          
            $str_Lsql = 'SELECT G15_ConsInte__b, G15_C146 as principal ,G15_C146,G15_C148,G15_C149,G15_C150 FROM '.$BaseDatos_systema.'.G15 WHERE G15_ConsInte__b ='.$_POST['id'];
            $result = $mysqli->query($str_Lsql);
            $datos = array();
            $i = 0;

            while($key = $result->fetch_object()){

                $datos[$i]['G15_C146'] = $key->G15_C146;

                $datos[$i]['G15_C148'] = $key->G15_C148;

                $datos[$i]['G15_C149'] = $key->G15_C149;

                $datos[$i]['G15_C150'] = $key->G15_C150;
      
                $datos[$i]['principal'] = $key->principal;
                $i++;
            }
            echo json_encode($datos);
        }


        if(isset($_POST['CallDatos_2'])){

            $bd = $_POST['poblacion'];

            $str_Lsql = 'SELECT G15_ConsInte__b, G15_C146 as principal ,G15_C146,G15_C148,G15_C149,G15_C150, G15_C152, G15_C153, G15_C154, B.ESTPAS_activo FROM '.$BaseDatos_systema.'.G15 LEFT JOIN '.$BaseDatos_systema.'.ESTPAS B ON G15_C152 = B.ESTPAS_ConsInte__b WHERE G15_C152 ='.$_POST['id'];
            $result = $mysqli->query($str_Lsql);
            $datos = array();
            $i = 0;
            
            if($result->num_rows > 0){
                while($key = $result->fetch_object()){
                    $datos[$i]['G15_C146'] = $key->G15_C146;

                    $datos[$i]['G15_C148'] = $key->G15_C148;

                    $datos[$i]['G15_C149'] = $key->G15_C149;

                    // Tenemos que darle una traduccion a las variables del mensaje
                    $mensaje = traducirDePregunATexto($key->G15_C150, $bd);

                    if($mensaje === null){
                        echo "Se presento un error al realizar la traduccion del mensaje";
                        exit();
                    }

                    $datos[$i]['G15_C150'] = $mensaje;

                    $datos[$i]['G15_C153'] = $key->G15_C153;

                    $datos[$i]['G15_C154'] = $key->G15_C154;
                    
                    $datos[$i]['pasoActivo'] = $key->ESTPAS_activo;

                    $datos[$i]['principal'] = $key->principal;

                    $datos[$i]['G15_ConsInte__b'] =  $key->G15_ConsInte__b;
                    $i++;
                }
                
            }
            echo json_encode($datos);
        }

        //Datos de la lista de la izquierda
        if(isset($_POST['CallDatosJson'])){
            $str_Lsql = 'SELECT G15_ConsInte__b as id,  G15_C146 as camp1, G16_C147 as camp1 , G16_C147 as camp2 ';
            $str_Lsql .= ' FROM '.$BaseDatos_systema.'.G15   LEFT JOIN '.$BaseDatos_systema.'.G16 ON G16_ConsInte__b = G15_C148 ';
            if($_POST['Busqueda'] != '' && !is_null($_POST['Busqueda'])){
                $str_Lsql .= ' WHERE  like "%'.$_POST['Busqueda'].'%" ';
                $str_Lsql .= ' OR  like "%'.$_POST['Busqueda'].'%" ';
            }

            $PEOBUS_VeRegPro__b = 0 ;
            $idUsuario = $_SESSION['IDENTIFICACION'];
            $peobus = "SELECT * FROM ".$BaseDatos_systema.".PEOBUS WHERE PEOBUS_ConsInte__USUARI_b = ".$idUsuario." AND PEOBUS_ConsInte__GUION__b = 15";
            $query = $mysqli->query($peobus);
            

            while ($key =  $query->fetch_object()) {
                $PEOBUS_VeRegPro__b = $key->PEOBUS_VeRegPro__b ;
            }

            if($PEOBUS_VeRegPro__b != 0){
                if($_POST['Busqueda'] != '' && !is_null($_POST['Busqueda'])){
                    $str_Lsql .= ' AND G15_Usuario = '.$idUsuario;
                }else{
                    $str_Lsql .= ' WHERE G15_Usuario = '.$idUsuario;
                }
        
            }

            $str_Lsql .= ' ORDER BY G15_ConsInte__b DESC LIMIT 0, 50';

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

        

        if(isset($_GET['CallDatosCombo_Guion_G15_C148'])){
            $Ysql = 'SELECT   G16_ConsInte__b as id , G16_C147 FROM ".$BaseDatos_systema.".G16';
            $guion = $mysqli->query($Ysql);
            echo '<select class="form-control input-sm"  name="G15_C148" id="G15_C148">';
            echo '<option >NOMBRE</option>';
            while($obj = $guion->fetch_object()){
               echo "<option value='".$obj->id."' dinammicos='0'>".utf8_encode($obj->G16_C147)."</option>";
            } 
            echo '</select>';
        }

        if(isset($_GET['CallDatosCombo_Guion_G15_C149'])){
            $Ysql = 'SELECT   G6_ConsInte__b as id , G6_C39 FROM ".$BaseDatos_systema.".G6';
            $guion = $mysqli->query($Ysql);
            echo '<select class="form-control input-sm"  name="G15_C149" id="G15_C149">';
            echo '<option >TEXTO</option>';
            while($obj = $guion->fetch_object()){
               echo "<option value='".$obj->id."' dinammicos='0'>".utf8_encode($obj->G6_C39)."</option>";
            } 
            echo '</select>';
        }





        if(isset($_POST['CallEliminate'])){
            if($_POST['oper'] == 'del'){
                $str_Lsql = "DELETE FROM ".$BaseDatos_systema.".G15 WHERE G15_ConsInte__b = ".$_POST['id'];
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
            $Zsql = 'SELECT  G15_ConsInte__b as id,  G15_C146 as camp1, G16_C147 as camp2  FROM '.$BaseDatos_systema.'.G15   LEFT JOIN '.$BaseDatos_systema.'.G16 ON G16_ConsInte__b = G15_C148 ORDER BY G15_ConsInte__b DESC LIMIT '.$inicio.' , '.$fin;
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
            $str_LsqlU = "UPDATE ".$BaseDatos_systema.".G15 SET "; 
            $str_LsqlI = "INSERT INTO ".$BaseDatos_systema.".G15(";
            $str_LsqlV = " VALUES ("; 
  
            if(isset($_POST["G15_C146"])){
                $separador = "";
                if($validar == 1){
                    $separador = ",";
                }

                $str_LsqlU .= $separador."G15_C146 = '".$_POST["G15_C146"]."'";
                $str_LsqlI .= $separador."G15_C146";
                $str_LsqlV .= $separador."'".$_POST["G15_C146"]."'";
                $validar = 1;
            }
             
  
            if(isset($_POST["G15_C148"])){
                $separador = "";
                if($validar == 1){
                    $separador = ",";
                }

                $str_LsqlU .= $separador."G15_C148 = '".$_POST["G15_C148"]."'";
                $str_LsqlI .= $separador."G15_C148";
                $str_LsqlV .= $separador."'".$_POST["G15_C148"]."'";
                $validar = 1;
            }
             
  
            if(isset($_POST["G15_C149"])){
                $separador = "";
                if($validar == 1){
                    $separador = ",";
                }

                $str_LsqlU .= $separador."G15_C149 = '".$_POST["G15_C149"]."'";
                $str_LsqlI .= $separador."G15_C149";
                $str_LsqlV .= $separador."'".$_POST["G15_C149"]."'";
                $validar = 1;
            }
             
  
            if(isset($_POST["G15_C150"])){
                $separador = "";
                if($validar == 1){
                    $separador = ",";
                }

                $bd = $_GET['poblacion'];

                // Tenemos que darle una traduccion a las variables del mensaje
                $mensaje = traducirDeTextoAPregun($_POST["G15_C150"], $bd);

                if($mensaje === null){
                    echo "Se presento un error al realizar la traduccion del mensaje";
                    exit();
                }

                $str_LsqlU .= $separador."G15_C150 = '".$mensaje."'";
                $str_LsqlI .= $separador."G15_C150";
                $str_LsqlV .= $separador."'".$mensaje."'";
                $validar = 1;
            }

            
            if(isset($_POST["G15_C152"])){
                $separador = "";
                if($validar == 1){
                    $separador = ",";
                }

                $str_LsqlU .= $separador."G15_C152 = '".$_POST["G15_C152"]."'";
                $str_LsqlI .= $separador."G15_C152";
                $str_LsqlV .= $separador."'".$_POST["G15_C152"]."'";
                $validar = 1;
            }

            // Campo esperar Respuesta
            $esperarRespuesta = isset($_POST["esperarRespuesta"]) ? $_POST["esperarRespuesta"] : 0;
            
            if($esperarRespuesta == -1 || $esperarRespuesta == 0){
                $separador = "";
                if($validar == 1){
                    $separador = ",";
                }

                $str_LsqlU .= $separador."G15_C154 = '".$esperarRespuesta."'";
                $str_LsqlI .= $separador."G15_C154";
                $str_LsqlV .= $separador."'".$esperarRespuesta."'";
                $validar = 1;
            }

            $campoActualizar = isset($_POST["campoActualizar"]) ? $_POST["campoActualizar"] : 0;

            // Campo pregun, el check debe estar activado para ejecutar esta accion
            if($esperarRespuesta != -1){
                $campoActualizar = 0;
            }

            // Esto es un enredo pero para no perder la estructura que tiene, valido que esos campos tengan algo
            if(is_numeric($campoActualizar)){
                $separador = "";
                if($validar == 1){
                    $separador = ",";
                }

                $str_LsqlU .= $separador."G15_C153 = '".$campoActualizar."'";
                $str_LsqlI .= $separador."G15_C153";
                $str_LsqlV .= $separador."'".$campoActualizar."'";
                $validar = 1;
            }
            
            $pasoactivo= isset($_POST['pasoActivo']) ? $_POST['pasoActivo'] :"0";
            $pasoactivo=$mysqli->query("UPDATE {$BaseDatos_systema}.ESTPAS SET ESTPAS_Activo={$pasoactivo} WHERE ESTPAS_ConsInte__b = ".$_POST['G15_C152']);          
 
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
                    $valorG = "G15_C";
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

                $str_LsqlU .= $separador."G15_IdLlamada = '".$_GET['id_gestion_cbx']."'";
                $str_LsqlI .= $separador."G15_IdLlamada";
                $str_LsqlV .= $separador."'".$_GET['id_gestion_cbx']."'";
                $validar = 1;
            }



            if(isset($_POST['oper'])){
                if($_POST["oper"] == 'add' ){
                    $str_Lsql = $str_LsqlI.")" . $str_LsqlV.")";
                }else if($_POST["oper"] == 'edit' ){
                    $str_Lsql = $str_LsqlU." WHERE G15_ConsInte__b =".$_POST["id"]; 
                }else if($_POST["oper"] == 'del' ){
                    $str_Lsql = "DELETE FROM ".$BaseDatos_systema.".G15 WHERE G15_ConsInte__b = ".$_POST['id'];
                    $validar = 1;
                }
            }

            //si trae algo que insertar inserta

//            echo $str_Lsql;
            if($validar == 1){
                if ($mysqli->query($str_Lsql) === TRUE) {
                    if($_POST["oper"] == 'add' ){

                        $registroId = $mysqli->insert_id;

                        $Lsql = "UPDATE ".$BaseDatos_systema.".ESTPAS SET  ESTPAS_Comentari_b  = '".$_POST['G15_C146']."' WHERE ESTPAS_ConsInte__b = ".$_POST['G15_C152'];
                        $mysqli->query($Lsql);

                        guardar_auditoria("INSERTAR", "INSERTAR REGISTRO EN G15 CON ID ".$registroId);
                    }else if($_POST["oper"] == 'edit' ){

                        $registroId = $_POST['id'];

                        $Lsql = "UPDATE ".$BaseDatos_systema.".ESTPAS SET  ESTPAS_Comentari_b  = '".$_POST['G15_C146']."' WHERE ESTPAS_ConsInte__b = ".$_POST['G15_C152'];
                        $mysqli->query($Lsql);

                        guardar_auditoria("ACTUALIZAR", "ACTUALIZO EL REGISTRO # ".$_POST["padre"]." EN G15 CON ID ".$registroId);
                    }else if($_POST["oper"] == 'del' ){
                       guardar_auditoria("ELIMINAR", "ELIMINO EL REGISTRO # ".$_POST['id']." EN G15");
                    }
                    
                    echo $registroId;
                } else {
                    echo "Error Hacieno el proceso los registros : " . $mysqli->error;
                }
            }
        }

        if(isset($_GET['realizarPruebaPaso'])){

            $telefono = $_POST['telefono'];
            $smsId = $_POST['smsId'];
            $bd = $_POST['pob'];

            // Traemos los datos del paso
            $sql = "SELECT * FROM {$BaseDatos_systema}.SMS_SALIENTE INNER JOIN {$BaseDatos_systema}.ESTPAS ON SMS_SALIENTE_ConsInte_ESTPAS_b = ESTPAS_ConsInte__b WHERE SMS_SALIENTE_ConsInte__b = {$smsId}";
            $res = $mysqli->query($sql);
            $smsSaliente = $res->fetch_object();

            $estadoPaso = $smsSaliente->ESTPAS_activo;

            // Obtenemos los campos
            // TODO: OBTENER EL LIMITE DE LOS CAMPOS  CUANDO SEA TEXTO Y TENGA UN LIMITE
            $sql = "SELECT PREGUN_ConsInte__b AS id, PREGUN_Texto_____b AS nombre, PREGUN_Tipo______b AS tipo, PREGUN_Longitud__b AS longitud FROM {$BaseDatos_systema}.PREGUN WHERE PREGUN_ConsInte__GUION__b = {$bd} AND (PREGUN_Texto_____b != 'ORIGEN_DY_WF' AND PREGUN_Texto_____b != 'OPTIN_DY_WF' AND PREGUN_Texto_____b != 'ESTADO_DY')";
            $resPregun = $mysqli->query($sql);

            $strCampos = "G{$bd}_ComentarioUG_b, G{$bd}_ComentarioGMI_b, G{$bd}_LinkContenidoUG_b, G{$bd}_LinkContenidoGMI_b";
            $strValores = "'ComentarioUG', 'ComentarioGMI', 'https://linkContenidoUG.test', 'https://linkContenidoGMI.test'";
            $strUpdate = "G{$bd}_ComentarioUG_b = 'ComentarioUG', G{$bd}_ComentarioGMI_b = 'ComentarioUG', G{$bd}_LinkContenidoUG_b = 'https://linkContenidoUG.test', G{$bd}_LinkContenidoGMI_b = 'https://linkContenidoGMI.test'";

            if($resPregun && $resPregun->num_rows > 0){

                while ($pregun = $resPregun->fetch_object()) {

                    // Texto
                    if($pregun->tipo == '1' || $pregun->tipo == '2' || $pregun->tipo == '14'){

                        $textoPrueba = substr($pregun->nombre, 0, 14) .' de prueba';
                        
                        if(!is_null($pregun->longitud) && is_numeric($pregun->longitud)){
                            $textoPrueba = substr($textoPrueba, 0, $pregun->longitud);
                        }

                        $textoPrueba = sanear_strings($textoPrueba);
                        $textoPrueba = utf8_decode($textoPrueba);
                        $textoPrueba = sanear_strings($textoPrueba);

                        $strCampos .= ", G{$bd}_C{$pregun->id}";
                        $strValores .= ",'". $textoPrueba ."'";
                        $strUpdate .= ", G{$bd}_C{$pregun->id} = '".$textoPrueba."'";
                    }

                    // Numero
                    if($pregun->tipo == '3' || $pregun->tipo == '4' || $pregun->tipo == '6' || $pregun->tipo == '11' || $pregun->tipo == '13'){
                        $strCampos .= ", G{$bd}_C{$pregun->id}";
                        $strValores .= ', 100';
                        $strUpdate .= ", G{$bd}_C{$pregun->id} = 100";
                    }

                    // Fecha
                    if($pregun->tipo == '5'){
                        $strCampos .= ", G{$bd}_C{$pregun->id}";
                        $strValores .= ", '2012-12-12'";
                        $strUpdate .= ", G{$bd}_C{$pregun->id} = '2012-12-12'";
                    }

                    // Hora
                    if($pregun->tipo == '10'){
                        $strCampos .= ", G{$bd}_C{$pregun->id}";
                        $strValores .= ", '2012-12-12 00:00:00'";
                        $strUpdate .= ", G{$bd}_C{$pregun->id} = '2012-12-12 00:00:00'";
                    }
                }

            }

            date_default_timezone_set('America/Bogota');
            $fechaEjecucion = date('Y-m-d H:i:s');

            // verificar si existe el registro -1
            $sql = "SELECT * from {$BaseDatos}.G{$bd} WHERE G{$bd}_ConsInte__b = -1";
            $resBd = $mysqli->query($sql);

            if($resBd && $resBd->num_rows > 0){
                // Actualizar
                $sqlBd = "UPDATE {$BaseDatos}.G{$bd} SET {$strUpdate} WHERE G{$bd}_ConsInte__b = -1";
            }else{
                // Insertar
                $sqlBd = "INSERT INTO {$BaseDatos}.G{$bd} (G{$bd}_ConsInte__b, G{$bd}_FechaInsercion,  {$strCampos}) VALUES (-1, '{$fechaEjecucion}', $strValores)";
            }

            if($mysqli->query($sqlBd) === false){
                echo json_encode(["estado" => "fallo", "respuesta" => "Hay un problema al crear el usuario de prueba ". $mysqli->error]);
                exit;
            }

            // Agregamos el campo telefono al registro
            $sqlU = "UPDATE {$BaseDatos}.G{$bd} SET G{$bd}_C{$smsSaliente->SMS_SALIENTE_ConsInte_PREGUN_b} = '{$telefono}' WHERE G{$bd}_ConsInte__b = -1";
            $mysqli->query($sqlU);

            // Validamos la muestra si existe o no y luego la insertamos
            $sqlMuestra = "SELECT * FROM {$BaseDatos}.G{$bd}_M{$smsSaliente->ESTPAS_ConsInte__MUESTR_b} WHERE G{$bd}_M{$smsSaliente->ESTPAS_ConsInte__MUESTR_b}_CoInMiPo__b = -1";
            $resMuestra = $mysqli->query($sqlMuestra);

            if($resMuestra && $resMuestra->num_rows > 0){
                // Actualizar muestra
                insertarEnMuestra($bd, $smsSaliente->ESTPAS_ConsInte__MUESTR_b, -1, 0, true);
            }else{
                // Insertar muestra
                insertarEnMuestra($bd, $smsSaliente->ESTPAS_ConsInte__MUESTR_b, -1, 0, false);
            }

            // Ejecutar el registro
            $respuestaPaso = procesarPaso($smsSaliente->SMS_SALIENTE_ConsInte_ESTPAS_b, $bd, $smsSaliente->ESTPAS_ConsInte__MUESTR_b, -1);

            sleep(10);

            // Revisamos si obtuvimos el log del mensaje enviado y la muestra
            $resMuestra2 = $mysqli->query($sqlMuestra);

            $muestra = null;

            if($resMuestra2 && $resMuestra2->num_rows > 0){
                $muestra2 = $resMuestra2->fetch_object();

                $varEstado = 'G'. $bd . '_M' . $smsSaliente->ESTPAS_ConsInte__MUESTR_b . '_Estado____b';
                $varFechaGestion = 'G'. $bd . '_M' . $smsSaliente->ESTPAS_ConsInte__MUESTR_b . '_FecUltGes_b';

                $muestra['estado'] = $muestra2->$varEstado;
                $muestra['fechaGestion'] = $muestra2->$varFechaGestion;
            }

            $sms_saliente = null;

            // Buscamos el mensaje en correos salientes
            $sqlsms_saliente = "SELECT * from dy_sms.salientes WHERE id_estpas = {$smsSaliente->ESTPAS_ConsInte__b} AND consinte_miembro = -1 AND fecha_hora >= '{$fechaEjecucion}' LIMIT 1";
            $resSms_saliente = $mysqli->query($sqlsms_saliente);

            if($resSms_saliente && $resSms_saliente->num_rows > 0){
                $sms_saliente = $resSms_saliente->fetch_object();
            }


            echo json_encode(["estado" => "ok", "respuesta" => $respuestaPaso, "muestra" => $muestra, "smsSaliente" => $sms_saliente, "estadoPaso" => $estadoPaso]);
        }

        
    }

    function sanear_strings($string) { 

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

    function obtenerVariables($bd){

        global $mysqli;
        global $BaseDatos_systema;

        $arrVariables = [];

        // Traigo la lista de campos de la base de datos
        $sql = "SELECT PREGUN_ConsInte__b AS id, PREGUN_Texto_____b AS nombre FROM ".$BaseDatos_systema.".PREGUN JOIN ".$BaseDatos_systema.".SECCIO ON SECCIO_ConsInte__b = PREGUN_ConsInte__SECCIO_b WHERE SECCIO_TipoSecc__b = 1 AND PREGUN_ConsInte__GUION__b = ".$bd;
        $res = $mysqli->query($sql);

        if($res && $res->num_rows > 0){

            while($row = $res->fetch_object()){

                $nombre = $row->nombre;

                $nombre = sanear_strings($nombre);
                $nombre = str_replace(' ', '_', $nombre);
                $nombre = substr($nombre, 0, 20);

                $arrVariables[$nombre] = 'G'.$bd.'_C'.$row->id;
            }
        }

        return $arrVariables;
    }

    function traducirVariablesSistema($mensaje, $sentido){

        $variables = [
            ["nombre" => "Comentario_Ultima_Gestion", "traduccion" => "ComentarioUG_b"],
            ["nombre" => "Comentario_Gestión_Mas_Importante", "traduccion" => "ComentarioGMI_b"]
        ];

        foreach ($variables as $variable) {
            if($sentido == 'entrante'){
                $mensaje = str_replace('${'.$variable['nombre'].'}', '${'.$variable['traduccion'].'}', $mensaje);
            }else{
                $mensaje = str_replace('${'.$variable['traduccion'].'}', '${'.$variable['nombre'].'}', $mensaje);
            }
        }

        return $mensaje;
    }

    function traducirDeTextoAPregun($mensaje, $bd){

        $mensaje = html_entity_decode($mensaje);

        $mensajeTraducido = null;
        $arrVariables = obtenerVariables($bd);

        if(count($arrVariables) > 0){

            $mensajeTraducido = $mensaje;

            foreach ($arrVariables as $key => $value) {
                $mensajeTraducido = str_replace('${'.$key.'}', '${'.$value.'}', $mensajeTraducido);
            }
        }

        $mensajeTraducido = traducirVariablesSistema($mensajeTraducido, 'entrante');

        return $mensajeTraducido;
    }

    function traducirDePregunATexto($mensaje, $bd){

        $mensajeTraducido = null;
        $arrVariables = obtenerVariables($bd);

        if(count($arrVariables) > 0){

            $mensajeTraducido = $mensaje;

            foreach ($arrVariables as $key => $value) {
                $mensajeTraducido = str_replace('${'.$value.'}', '${'.$key.'}', $mensajeTraducido);
            }
        }

        $mensajeTraducido = traducirVariablesSistema($mensajeTraducido, 'saliente');

        return $mensajeTraducido;
    }

?>
