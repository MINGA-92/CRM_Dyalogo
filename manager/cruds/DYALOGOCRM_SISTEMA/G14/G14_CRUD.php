<?php
    session_start();
    ini_set('display_errors', 'On');
    ini_set('display_errors', 1);
    include_once(__DIR__."../../../../pages/conexion.php");
    include_once(__DIR__."../../../../../crm_php/funciones.php");

    function guardar_auditoria($accion, $superAccion){
        global $mysqli;
        global $BaseDatos_systema;
        $str_Lsql = "INSERT INTO ".$BaseDatos_systema."AUACAD (AUACAD_Fecha_____b , AUACAD_Hora______b, AUACAD_Ejecutor__b, AUACAD_TipoAcci__b , AUACAD_SubTipAcc_b, AUACAD_Accion____b , AUACAD_Huesped___b ) VALUES ('".date('Y-m-d H:s:i')."', '".date('Y-m-d H:s:i')."', ".$_SESSION['IDENTIFICACION'].", 'G14', '".$accion."', '".$superAccion."', ".$_SESSION['HUESPED']." );";
        $mysqli->query($str_Lsql);
    }   

    if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
        //Funciones de la carga maestro y eso
        

        //Datos del formulario
        if(isset($_POST['CallDatos'])){
          
            $str_Lsql = 'SELECT G14_ConsInte__b, G14_C137 as principal ,G14_C136,G14_C137,G14_C138,G14_C139,G14_C140,G14_C141,G14_C142,G14_C143,G14_C144, G14_C145 FROM '.$BaseDatos_systema.'.G14 WHERE G14_ConsInte__b ='.$_POST['id'];
            $result = $mysqli->query($str_Lsql);
            $datos = array();
            $i = 0;

            while($key = $result->fetch_object()){

                $datos[$i]['G14_C136'] = $key->G14_C136;

                $datos[$i]['G14_C137'] = $key->G14_C137;

                $datos[$i]['G14_C138'] = $key->G14_C138;

                $datos[$i]['G14_C139'] = $key->G14_C139;

                $datos[$i]['G14_C140'] = $key->G14_C140;

                $datos[$i]['G14_C141'] = $key->G14_C141;

                $datos[$i]['G14_C142'] = $key->G14_C142;

                $datos[$i]['dyTr_G14_C143'] = $key->G14_C143;

                $datos[$i]['G14_C144'] = $key->G14_C144;
        
                $datos[$i]['G14_C145'] = $key->G14_C145;

                $datos[$i]['principal'] = $key->principal;
                $i++;
            }
            echo json_encode($datos);
        }


        if(isset($_POST['CallDatos_paso'])){

            $bd = $_POST['poblacion'];
            
            $str_Lsql = 'SELECT G14_ConsInte__b, G14_C137 as principal ,G14_C136,G14_C137,G14_C138,G14_C139,G14_C140,G14_C141,G14_C142,G14_C143,G14_C144, G14_C145, B.ESTPAS_activo FROM '.$BaseDatos_systema.'.G14 LEFT JOIN '.$BaseDatos_systema.'.ESTPAS B ON G14_C136 = B.ESTPAS_ConsInte__b WHERE G14_C136 ='.$_POST['id_paso'];
            $result = $mysqli->query($str_Lsql);
            $datos = array();
            $i = 0;

            while($key = $result->fetch_object()){

                $datos[$i]['G14_ConsInte__b'] = $key->G14_ConsInte__b;

                $datos[$i]['G14_C137'] = $key->G14_C137;

                $datos[$i]['G14_C145'] = $key->G14_C145;

                $datos[$i]['G14_C138'] = $key->G14_C138;

                $para = traducirDePregunATexto($key->G14_C139, $bd);
                if($para === null){
                    echo "Se presento un error al realizar la traduccion del campo para";
                    exit();
                }
                $datos[$i]['G14_C139'] = $para;

                $cc = traducirDePregunATexto($key->G14_C140, $bd);
                if($cc === null){
                    echo "Se presento un error al realizar la traduccion del campo cc";
                    exit();
                }
                $datos[$i]['G14_C140'] = $cc;

                $cco = traducirDePregunATexto($key->G14_C141, $bd);
                if($cco === null){
                    echo "Se presento un error al realizar la traduccion del campo cco";
                    exit();
                }
                $datos[$i]['G14_C141'] = $cco;

                $asunto = traducirDePregunATexto($key->G14_C142, $bd);
                if($asunto === null){
                    echo "Se presento un error al realizar la traduccion del campo asunto";
                    exit();
                }
                $datos[$i]['G14_C142'] = $asunto;

                $cuerpo = traducirDePregunATexto($key->G14_C143, $bd);
                if($cuerpo === null){
                    echo "Se presento un error al realizar la traduccion del campo cuerpo";
                    exit();
                }
                $datos[$i]['dyTr_G14_C143'] = $cuerpo;

                $datos[$i]['G14_C144'] = $key->G14_C144;
                
                $datos[$i]['pasoActivo'] = $key->ESTPAS_activo;
      
                $datos[$i]['principal'] = $key->principal;
                $i++;
            }
            echo json_encode($datos);
        }

        //Datos de la lista de la izquierda
        if(isset($_POST['CallDatosJson'])){
            $str_Lsql = 'SELECT G14_ConsInte__b as id,  G14_C137 as camp1 , G14_C138 as camp2 ';
            $str_Lsql .= ' FROM '.$BaseDatos_systema.'.G14   ';
            if($_POST['Busqueda'] != '' && !is_null($_POST['Busqueda'])){
                $str_Lsql .= ' WHERE  like "%'.$_POST['Busqueda'].'%" ';
                $str_Lsql .= ' OR  like "%'.$_POST['Busqueda'].'%" ';
            }

            $PEOBUS_VeRegPro__b = 0 ;
            $idUsuario = $_SESSION['IDENTIFICACION'];
            $peobus = "SELECT * FROM ".$BaseDatos_systema.".PEOBUS WHERE PEOBUS_ConsInte__USUARI_b = ".$idUsuario." AND PEOBUS_ConsInte__GUION__b = 14";
            $query = $mysqli->query($peobus);
            

            while ($key =  $query->fetch_object()) {
                $PEOBUS_VeRegPro__b = $key->PEOBUS_VeRegPro__b ;
            }

            if($PEOBUS_VeRegPro__b != 0){
                if($_POST['Busqueda'] != '' && !is_null($_POST['Busqueda'])){
                    $str_Lsql .= ' AND G14_Usuario = '.$idUsuario;
                }else{
                    $str_Lsql .= ' WHERE G14_Usuario = '.$idUsuario;
                }
        
            }

            $str_Lsql .= ' ORDER BY G14_ConsInte__b DESC LIMIT 0, 50'; 
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

        if(isset($_GET['CallDatosCombo_Guion_G14_C136'])){
            $Ysql = 'SELECT   G3_ConsInte__b as id , G3_C15 FROM ".$BaseDatos_systema.".G3';
            $guion = $mysqli->query($Ysql);
            echo '<select class="form-control input-sm"  name="G14_C136" id="G14_C136">';
            echo '<option >NOMBRE</option>';
            while($obj = $guion->fetch_object()){
               echo "<option value='".$obj->id."' dinammicos='0'>".utf8_encode($obj->G3_C15)."</option>";
            } 
            echo '</select>';
        }

        if(isset($_POST['CallEliminate'])){
            if($_POST['oper'] == 'del'){
                $str_Lsql = "DELETE FROM ".$BaseDatos_systema.".G14 WHERE G14_ConsInte__b = ".$_POST['id'];
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
            $Zsql = 'SELECT  G14_ConsInte__b as id,  G14_C137 as camp1 , G14_C138 as camp2  FROM '.$BaseDatos_systema.'.$BaseDatos_systema.".G14   ORDER BY G14_ConsInte__b DESC LIMIT '.$inicio.' , '.$fin;
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

            /*$str_Lsql = "DELETE FROM ".$BaseDatos_systema.".G14 WHERE G14_C136 = ".$_POST['G14_C136'];
            $mysqli->query($str_Lsql);*/


            $str_Lsql  = '';

            $validar = 0;
            $str_LsqlU = "UPDATE ".$BaseDatos_systema.".G14 SET "; 
            $str_LsqlI = "INSERT INTO ".$BaseDatos_systema.".G14(";
            $str_LsqlV = " VALUES ("; 
  
            if(isset($_POST["G14_C136"])){
                $separador = "";
                if($validar == 1){
                    $separador = ",";
                }

                $str_LsqlU .= $separador."G14_C136 = '".$_POST["G14_C136"]."'";
                $str_LsqlI .= $separador."G14_C136";
                $str_LsqlV .= $separador."'".$_POST["G14_C136"]."'";
                $validar = 1;
            }
             
  
            if(isset($_POST["G14_C137"])){
                $separador = "";
                if($validar == 1){
                    $separador = ",";
                }

                $str_LsqlU .= $separador."G14_C137 = '".$_POST["G14_C137"]."'";
                $str_LsqlI .= $separador."G14_C137";
                $str_LsqlV .= $separador."'".$_POST["G14_C137"]."'";
                $validar = 1;
            }
             


  
            if(isset($_POST["G14_C138"])){
                $separador = "";
                if($validar == 1){
                    $separador = ",";
                }

                $str_LsqlU .= $separador."G14_C138 = '".$_POST["G14_C138"]."'";
                $str_LsqlI .= $separador."G14_C138";
                $str_LsqlV .= $separador."'".$_POST["G14_C138"]."'";
                $validar = 1;
            }
             
  
            if(isset($_POST["G14_C139"])){
                $separador = "";
                if($validar == 1){
                    $separador = ",";
                }

                $bd = $_GET['poblacion'];
                // Tenemos que darle una traduccion a las variables del mensaje
                $para = traducirDeTextoAPregun($_POST["G14_C139"], $bd);
                if($para === null){
                    echo "Se presento un error al realizar la traduccion de para";
                    exit();
                }

                $str_LsqlU .= $separador."G14_C139 = '".$para."'";
                $str_LsqlI .= $separador."G14_C139";
                $str_LsqlV .= $separador."'".$para."'";
                $validar = 1;
            }
             
  
            if(isset($_POST["G14_C140"])){
                $separador = "";
                if($validar == 1){
                    $separador = ",";
                }

                $bd = $_GET['poblacion'];
                // Tenemos que darle una traduccion a las variables del mensaje
                $cc = traducirDeTextoAPregun($_POST["G14_C140"], $bd);
                if($cc === null){
                    echo "Se presento un error al realizar la traduccion de cc";
                    exit();
                }

                $str_LsqlU .= $separador."G14_C140 = '".$cc."'";
                $str_LsqlI .= $separador."G14_C140";
                $str_LsqlV .= $separador."'".$cc."'";
                $validar = 1;
            }


            if(isset($_POST["G14_C145"])){
                $separador = "";
                if($validar == 1){
                    $separador = ",";
                }

                $str_LsqlU .= $separador."G14_C145 = '".$_POST["G14_C145"]."'";
                $str_LsqlI .= $separador."G14_C145";
                $str_LsqlV .= $separador."'".$_POST["G14_C145"]."'";
                $validar = 1;
            }
             
             
  
            if(isset($_POST["G14_C141"])){
                $separador = "";
                if($validar == 1){
                    $separador = ",";
                }

                $bd = $_GET['poblacion'];
                // Tenemos que darle una traduccion a las variables del mensaje
                $cco = traducirDeTextoAPregun($_POST["G14_C141"], $bd);
                if($cco === null){
                    echo "Se presento un error al realizar la traduccion del asunto";
                    exit();
                }

                $str_LsqlU .= $separador."G14_C141 = '".$cco."'";
                $str_LsqlI .= $separador."G14_C141";
                $str_LsqlV .= $separador."'".$cco."'";
                $validar = 1;
            }
             
  
            if(isset($_POST["G14_C142"])){
                $separador = "";
                if($validar == 1){
                    $separador = ",";
                }

                $bd = $_GET['poblacion'];
                // Tenemos que darle una traduccion a las variables del mensaje
                $asunto = traducirDeTextoAPregun($_POST["G14_C142"], $bd);
                if($asunto === null){
                    echo "Se presento un error al realizar la traduccion del asunto";
                    exit();
                }

                $str_LsqlU .= $separador."G14_C142 = '".$asunto."'";
                $str_LsqlI .= $separador."G14_C142";
                $str_LsqlV .= $separador."'".$asunto."'";
                $validar = 1;
            }
             
  
            if(isset($_POST["dyTr_G14_C143"])){
                $separador = "";
                if($validar == 1){
                    $separador = ",";
                }

                $bd = $_GET['poblacion'];

                // Tenemos que darle una traduccion a las variables del mensaje
                
                $mensaje = traducirDeTextoAPregun(htmlentities($_POST["dyTr_G14_C143"]), $bd);
                // echo "<br> mensaje => $mensaje <br>";
                if($mensaje === null){
                    echo "Se presento un error al realizar la traduccion del mensaje";
                    exit();
                }

                $str_LsqlU .= $separador."G14_C143 = '".$mensaje."'";
                $str_LsqlI .= $separador."G14_C143";
                $str_LsqlV .= $separador."'".$mensaje."'";
                $validar = 1;
            }
             
            

            if(isset($_POST['ruta_Adjuntos']) && $_POST['ruta_Adjuntos'] != 0){
                $separador = "";
                if($validar == 1){
                    $separador = ",";
                }

                $str_LsqlU .= $separador."G14_C144 = '".$_POST['ruta_Adjuntos']."'";
                $str_LsqlI .= $separador."G14_C144";
                $str_LsqlV .= $separador."'".$_POST['ruta_Adjuntos']."'";
                $validar = 1;
            }
            
            $pasoactivo= isset($_POST['pasoActivo']) ? $_POST['pasoActivo'] :"0";
            $pasoactivo=$mysqli->query("UPDATE {$BaseDatos_systema}.ESTPAS SET ESTPAS_Activo={$pasoactivo} WHERE ESTPAS_ConsInte__b = ".$_POST['G14_C136']);            
 
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
                    $valorG = "G14_C";
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

                $str_LsqlU .= $separador."G14_IdLlamada = '".$_GET['id_gestion_cbx']."'";
                $str_LsqlI .= $separador."G14_IdLlamada";
                $str_LsqlV .= $separador."'".$_GET['id_gestion_cbx']."'";
                $validar = 1;
            }



            if(isset($_POST['oper'])){
                if($_POST["oper"] == 'add' ){
                    $str_Lsql = $str_LsqlI.")" . $str_LsqlV.")";
                }else if($_POST["oper"] == 'edit' ){
                    $str_Lsql = $str_LsqlU." WHERE G14_ConsInte__b =".$_POST["id"]; 
                }else if($_POST["oper"] == 'del' ){
                    $str_Lsql = "DELETE FROM ".$BaseDatos_systema.".G14 WHERE G14_ConsInte__b = ".$_POST['id'];
                    $validar = 1;
                }
            }

            //si trae algo que insertar inserta

            //echo $str_Lsql;
            if($validar == 1){
                if ($mysqli->query($str_Lsql) === TRUE) {
                    if($_POST["oper"] == 'add' ){

                        $registroId = $mysqli->insert_id;

                        $Lsql = "UPDATE ".$BaseDatos_systema.".ESTPAS SET  ESTPAS_Comentari_b  = '".$_POST['G14_C137']."' WHERE ESTPAS_ConsInte__b = ".$_POST['G14_C136'];
                        $mysqli->query($Lsql);

                        guardar_auditoria("INSERTAR", "INSERTAR REGISTRO EN G14 CON ID " . $registroId);

                    }else if($_POST["oper"] == 'edit' ){

                        $registroId = $_POST['id'];

                        $Lsql = "UPDATE ".$BaseDatos_systema.".ESTPAS SET  ESTPAS_Comentari_b  = '".$_POST['G14_C137']."' WHERE ESTPAS_ConsInte__b = ".$_POST['G14_C136'];
                        $mysqli->query($Lsql);

                        guardar_auditoria("ACTUALIZAR", "ACTUALIZO EL REGISTRO # ".$_POST["padre"]." EN G14 CON ID " . $registroId);

                    }else if($_POST["oper"] == 'del' ){
                        guardar_auditoria("ELIMINAR", "ELIMINO EL REGISTRO # ".$_POST['id']." EN G14");
                    }
                    
                    echo $registroId;
                } else {
                    echo "Error Hacieno el proceso los registros : " . $mysqli->error;
                }
            }
        }


        if(isset($_GET['actualizarDatos'])){

        }
        
        // Aqui es donde se guardara los adjuntos, esto guarda un archivo cuando se ejecuta
        if(isset($_GET['fileUpload'])){

            $id_paso = $_POST['G14_C136'];
            $id = $_POST['id'];

            if($id == 0){
                echo json_encode(["estado" => "fallo", "mensaje" => "Antes de subir el archivo por favor guarde por primera vez la configuración"]);
                exit();
            }

            if(isset($_FILES['adjunto'])){
                $file = $_FILES['adjunto'];

                if(isset($file['tmp_name']) && !empty($file['tmp_name'])){
            
                    $name = str_replace(' ', '_', basename($file['name']));
                    $name = "adj_".$id.'_'.$name;
                    
                    $path = "/home/dyalogo/adjuntos/".$name;
                    $path = str_replace(' ', '', $path);

                    if (file_exists($path)) {
                        unlink($path);
                    }

                    if (move_uploaded_file($file['tmp_name'], $path)){

                        // Si se sube el archivo se guarda la ruta referente al id del paso
                        $sSql = "SELECT * FROM $BaseDatos_systema.CORREO_SALIENTE WHERE CORREO_SALIENTE_ConsInte__ESTPAS_b = $id_paso LIMIT 1";
                        $result = $mysqli->query($sSql);
                        $res = $result->fetch_array();
                        
                        //Valido si hay registros en ese campo
                        $listaAdjuntos = $res['CORREO_SALIENTE_Adjuntos_b'];

                        if(is_null($listaAdjuntos) || $listaAdjuntos == ''){
                            $listaAdjuntos = $path;
                        }else{
                            $listaAdjuntos .= ','.$path;
                        }

                        $uSql = "UPDATE $BaseDatos_systema.CORREO_SALIENTE SET CORREO_SALIENTE_Adjuntos_b = '$listaAdjuntos' WHERE CORREO_SALIENTE_ConsInte__ESTPAS_b = $id_paso";
                        $mysqli->query($uSql);
                        echo json_encode(["estado" => "ok", "mensaje" => "El archivo ha sido cargado correctamente.", "adjuntos" => $listaAdjuntos]);
                    }else{
                        echo json_encode(["estado" => "fallo", "mensaje" => "Ocurrió algún error al subir el fichero. No pudo guardarse."]);
                    }
                    
                }else{
                    echo json_encode(["estado" => "fallo", "mensaje" => "No hay un archivo para subir"]);
                }
            }
            
        }

        if(isset($_GET['deleteFile'])){

            $pathAdjuntos = "/home/dyalogo/adjuntos/";

            $id = $_POST['id'];
            $nombre = $pathAdjuntos.$_POST['nombre'];

            $sSql = "SELECT CORREO_SALIENTE_Adjuntos_b AS adjuntos FROM $BaseDatos_systema.CORREO_SALIENTE WHERE CORREO_SALIENTE_ConsInte__b = $id LIMIT 1";
            $result = $mysqli->query($sSql);
            $res = $result->fetch_array();

            $arrAdjuntos = explode(",", $res['adjuntos']);
            $newAdjuntos = "";
            
            foreach($arrAdjuntos as $valor){
                if($valor != $nombre){
                    if($newAdjuntos == ''){
                        $newAdjuntos = $valor;
                    }else{
                        $newAdjuntos .= ",".$valor;
                    }
                }else{
                    // Si el valor coincide con el ingresado lo elimino
                    if (file_exists($nombre)) {
                        unlink($nombre);
                    }
                }
            }

            $newAdjuntosVal = empty($newAdjuntos) == false ? "'{$newAdjuntos}'" : "null";

            $uSql = "UPDATE $BaseDatos_systema.CORREO_SALIENTE SET CORREO_SALIENTE_Adjuntos_b = {$newAdjuntosVal} WHERE CORREO_SALIENTE_ConsInte__b = {$id}";
            $mysqli->query($uSql);
            echo json_encode(["estado" => "ok", "mensaje" => "El archivo ha sido eliminado.", "adjuntos" => $newAdjuntos]);
        }
        
        if(isset($_GET['vincularImagen'])){

            $imagen = $_POST['imagen'];
            $huesped = $_POST['huesped'];

            $estado = false;

            if($imagen != ''){
                $ruta = '/Dyalogo/img_texteditor/' . $imagen;
                $sqlInsert = "INSERT INTO {$BaseDatos_general}.huespedes_imagenes (id_huesped, tipo, nombre, ruta) VALUES({$huesped}, 'img_texteditor', '{$imagen}', '{$ruta}')";

                $res = $mysqli->query($sqlInsert);

                if($res){
                    $estado = true;
                }

            }

            // Traigo todos las imagenes del huesped
            $sql = "SELECT id, id_huesped, nombre, tipo FROM {$BaseDatos_general}.huespedes_imagenes WHERE id_huesped = {$huesped}";
            $res = $mysqli->query($sql);

            $data = [];

            if($res && $res->num_rows > 0){
                
                while($row = $res->fetch_object()){
                    $data[] = $row;
                }
            }

            echo json_encode([
                'estado' => $estado,
                'imagenesHuesped' => $data
            ]);
        }

        if(isset($_GET['traerImagenes'])){

            $huesped = $_POST['huesped'];

            // Traigo todos las imagenes del huesped
            $sql = "SELECT id, id_huesped, nombre, tipo FROM {$BaseDatos_general}.huespedes_imagenes WHERE id_huesped = {$huesped}";
            $res = $mysqli->query($sql);

            $data = [];

            if($res && $res->num_rows > 0){
                
                while($row = $res->fetch_object()){
                    $data[] = $row;
                }
            }

            echo json_encode([
                'estado' => true,
                'imagenesHuesped' => $data
            ]);
        }

        if(isset($_GET['eliminarImagen'])){

            $imagenId = $_POST['imagen'];
            $huesped = $_POST['huesped'];

            // Necesito la ruta de la imagen
            $sql = "SELECT * FROM {$BaseDatos_general}.huespedes_imagenes WHERE id = {$imagenId} LIMIT 1";
            $res = $mysqli->query($sql);

            if($res && $res->num_rows > 0){

                $reg = $res->fetch_object();

                if (file_exists($reg->ruta)) {
                    
                    // Elimino la imagen
                    unlink($reg->ruta);
                    
                    $sqlDelete = "DELETE FROM {$BaseDatos_general}.huespedes_imagenes WHERE id = {$imagenId}";
                    $mysqli->query($sqlDelete);
                }

            }

            // Traigo todos las imagenes del huesped
            $sql = "SELECT id, id_huesped, nombre, tipo FROM {$BaseDatos_general}.huespedes_imagenes WHERE id_huesped = {$huesped}";
            $res = $mysqli->query($sql);

            $data = [];

            if($res && $res->num_rows > 0){
                
                while($row = $res->fetch_object()){
                    $data[] = $row;
                }
            }

            echo json_encode([
                'estado' => true,
                'imagenesHuesped' => $data
            ]);
        }

        if(isset($_GET['realizarPruebaPaso'])){

            $correo = $_POST['correo'];
            $correoId = $_POST['correoId'];
            $bd = $_POST['pob'];

            // Traemos los datos del paso
            $sql = "SELECT * FROM {$BaseDatos_systema}.CORREO_SALIENTE 
                INNER JOIN {$BaseDatos_systema}.ESTPAS ON CORREO_SALIENTE_ConsInte__ESTPAS_b = ESTPAS_ConsInte__b
            WHERE CORREO_SALIENTE_ConsInte__b = {$correoId}";
            $res = $mysqli->query($sql);
            $correoSaliente = $res->fetch_object();

            $estadoPaso = $correoSaliente->ESTPAS_activo;

            // Obtenemos los campos
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

            // Validamos la muestra si existe o no y luego la insertamos
            $sqlMuestra = "SELECT * FROM {$BaseDatos}.G{$bd}_M{$correoSaliente->ESTPAS_ConsInte__MUESTR_b} WHERE G{$bd}_M{$correoSaliente->ESTPAS_ConsInte__MUESTR_b}_CoInMiPo__b = -1";
            $resMuestra = $mysqli->query($sqlMuestra);

            if($resMuestra && $resMuestra->num_rows > 0){
                // Actualizar muestra
                insertarEnMuestra($bd, $correoSaliente->ESTPAS_ConsInte__MUESTR_b, -1, 0, true);
            }else{
                // Insertar muestra
                insertarEnMuestra($bd, $correoSaliente->ESTPAS_ConsInte__MUESTR_b, -1, 0, false);
            }

            // Guardamos el campo original en una variable temporal
            $valorCampoParaOriginal = $correoSaliente->CORREO_SALIENTE_Para_b;
            $valorCampoCcOriginal = $correoSaliente->CORREO_SALIENTE_CC_b;
            $valorCampoCcoOriginal = $correoSaliente->CORREO_SALIENTE_CCO_b;

            // Cambiar el campo para en el webform por el de prueba
            $sqlUpdate = "UPDATE {$BaseDatos_systema}.CORREO_SALIENTE SET CORREO_SALIENTE_Para_b = '{$correo}', CORREO_SALIENTE_CC_b = '', CORREO_SALIENTE_CCO_b = '' WHERE CORREO_SALIENTE_ConsInte__b = {$correoId}";
            $mysqli->query($sqlUpdate);

            // Ejecutar el registro
            $respuestaPaso = procesarPaso($correoSaliente->CORREO_SALIENTE_ConsInte__ESTPAS_b, $bd, $correoSaliente->ESTPAS_ConsInte__MUESTR_b, -1);

            sleep(10);

            // Delolver el cambio del para, si se ejecutara un correo de verdad seria mala suerte
            $sqlUpdateD = "UPDATE {$BaseDatos_systema}.CORREO_SALIENTE SET CORREO_SALIENTE_Para_b = '{$valorCampoParaOriginal}', CORREO_SALIENTE_CC_b = '{$valorCampoCcOriginal}', CORREO_SALIENTE_CCO_b = '{$valorCampoCcoOriginal}' WHERE CORREO_SALIENTE_ConsInte__b = {$correoId}";
            $mysqli->query($sqlUpdateD);

            // Revisamos si obtuvimos el log del mensaje enviado y la muestra
            $resMuestra2 = $mysqli->query($sqlMuestra);

            $muestra = null;

            if($resMuestra2 && $resMuestra2->num_rows > 0){
                $muestra2 = $resMuestra2->fetch_object();

                $varEstado = 'G'. $bd . '_M' . $correoSaliente->ESTPAS_ConsInte__MUESTR_b . '_Estado____b';
                $varFechaGestion = 'G'. $bd . '_M' . $correoSaliente->ESTPAS_ConsInte__MUESTR_b . '_FecUltGes_b';

                $muestra['estado'] = $muestra2->$varEstado;
                $muestra['fechaGestion'] = $muestra2->$varFechaGestion;
            }

            $ce_saliente = null;

            // Buscamos el mensaje en correos salientes
            $sqlCorreoSaliente = "SELECT * from {$dyalogo_canales_electronicos}.dy_ce_salientes WHERE id_estpas = {$correoSaliente->ESTPAS_ConsInte__b} AND consinte_miembro = -1 AND fecha_hora >= '{$fechaEjecucion}' LIMIT 1";
            $resCorreoSaliente = $mysqli->query($sqlCorreoSaliente);

            if($resCorreoSaliente && $resCorreoSaliente->num_rows > 0){
                $ce_saliente = $resCorreoSaliente->fetch_object();
            }


            echo json_encode(["estado" => "ok", "respuesta" => $respuestaPaso, "muestra" => $muestra, "correoSaliente" => $ce_saliente, "estadoPaso" => $estadoPaso]);
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
