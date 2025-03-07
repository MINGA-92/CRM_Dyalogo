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
    
    if(!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest'){

        // Trae toda la info del paso
        if(isset($_GET['CallDatos'])){

            $esql = "SELECT ESTPAS_ConsInte__b AS id, ESTPAS_Nombre__b AS nombre, ESTPAS_Tipo______b AS tipo, ESTPAS_activo AS activo FROM ".$BaseDatos_systema.".ESTPAS WHERE ESTPAS_ConsInte__b = ".$_POST['id_paso']." LIMIT 1";
            $rese = $mysqli->query($esql);
            $paso = $rese->fetch_array();

            $resCanales  = obtenerCanalesWhatsapp($_POST['huesped']);
            $dataCanales = json_decode($resCanales);
            $canalesWa = $dataCanales->channels;

            $existe = false;
            $plantillaSaliente = array();
            $campos = array();
            $plantillas = array();
            $i = 0;

            // Valido si el paso ya esta configurado
            $sql = "SELECT * FROM ".$dyalogo_canales_electronicos.".dy_wa_plantillas_salientes WHERE id_estpas = ".$_POST['id_paso']." LIMIT 1";
            
            $res = $mysqli->query($sql);
            if($res->num_rows > 0){
                $plantillaSaliente = $res->fetch_array();
                $existe = true;

                // Traigo los campos de la plantilla
                $csql = "SELECT b.id, b.id_wa_plantilla_saliente, b.accion, b.id_pregun, b.valor_estatico, a.nombre, a.id as id_wa_plantilla_variable
                 FROM {$dyalogo_canales_electronicos}.dy_wa_plantillas_variables a
                LEFT JOIN (SELECT * FROM {$dyalogo_canales_electronicos}.dy_wa_plantillas_salientes_variables WHERE id_wa_plantilla_saliente = {$plantillaSaliente['id']} ) b ON a.id = b.id_wa_plantilla_variable
                WHERE id_wa_plantilla = {$plantillaSaliente['id_wa_plantilla']}";

                $resC = $mysqli->query($csql);
                if($resC){
                    while ($item = $resC->fetch_object()) {
                        $campos[$i]['id'] = isset($item->id) ? $item->id : 0;
                        $campos[$i]['nombre_variable'] = $item->nombre;
                        $campos[$i]['id_plantilla_saliente'] = $item->id_wa_plantilla_saliente;
                        $campos[$i]['id_plantilla_saliente_variable'] = isset($item->id_wa_plantilla_variable) ? $item->id_wa_plantilla_variable : 0;
                        $campos[$i]['id_pregun'] = isset($item->id_pregun) ? $item->id_pregun : '';
                        $campos[$i]['accion'] = isset($item->accion) ? $item->accion : '';
                        $campos[$i]['valor_estatico'] = isset($item->valor_estatico) ? $item->valor_estatico : '';
                        $i++;
                    }
                }

                // Traigo las plantillas del huesped
                $plantillas = getPlantillas($plantillaSaliente['id_cuenta_whatsapp']);
            }

            echo json_encode([
                "paso" => $paso,
                "canales" => $canalesWa, 
                "existe" => $existe,
                "plantillaSaliente" => $plantillaSaliente,
                "campos" => $campos, 
                "plantillas" => $plantillas
            ]);
        }

        // Traigo las plantilla de una cuenta de whatsapp especifica
        if(isset($_GET['getPlantillas'])){
            if(isset($_POST['cuentawa']) && $_POST['cuentawa'] != ''){

                $plantillas = getPlantillas($_POST['cuentawa']);

                echo json_encode(["plantillas" => $plantillas]);
            }
        }

        // Traigo los datos de una plantilla
        if(isset($_GET['getCamposPlantilla'])){
            $idPlantilla = $_POST['idPlantilla'];
            
            if($idPlantilla != ''){
                $sqlCP = "SELECT * FROM ".$dyalogo_canales_electronicos.".dy_wa_plantillas_variables WHERE id_wa_plantilla = ".$idPlantilla;
                $resCP = $mysqli->query($sqlCP);

                $campos = array();
                $i = 0;

                if($sqlCP){
                    while ($item = $resCP->fetch_object()) {
                        $campos[$i]['id'] = $item->id;
                        $campos[$i]['nombre'] = $item->nombre;
                        $campos[$i]['id_plantilla'] = $item->id_wa_plantilla;
                        $i++;
                    }
                }

                echo json_encode(["campos" => $campos]);
            }else{
                echo json_encode([]);
            }
            
        }

        if(isset($_GET['insertarDatos']) && isset($_POST["oper"])){

            // Traemos el check activo del paso
            $activo = isset($_POST['pasoActivo']) ? $_POST['pasoActivo'] : 0;
            
            // Actualizo el paso
            $pasoSql = "UPDATE ".$BaseDatos_systema.".ESTPAS SET ESTPAS_Comentari_b = '".$_POST['nombre']."', ESTPAS_activo = '".$activo."' WHERE ESTPAS_ConsInte__b = ".$_POST['id_paso'];
            $mysqli->query($pasoSql);

            if($_POST["oper"] == "add"){
                
                // Insertamos el registro de la plantilla
                $Isql = "INSERT INTO ".$dyalogo_canales_electronicos.".dy_wa_plantillas_salientes (id_estpas, nombre, id_cuenta_whatsapp, id_wa_plantilla, id_pregun_telefono, correos_envio_mensaje) VALUES (".$_POST['id_paso'].", '".$_POST['nombre']."', ".$_POST['cuentawa'].", ".$_POST['plantilla'].", ".$_POST['to'].", '".$_POST['correosNotificacion']."')";
                $mysqli->query($Isql);
                $id_plantilla_saliente = $mysqli->insert_id;

                // Inserto en variables plantillas
                if(isset($_POST['campov'])){
                    $arr_campos = $_POST['campov'];
                    foreach ($arr_campos as $key => $value) {
    
                        $pregun_id = 0;
                        $valor_estatico = '';
    
                        if($value['accion'] == 1 && isset($value['pregun']) ){
                            $pregun_id = $value['pregun'];
                        }
                        
                        if($value['accion'] == 2 && isset($value['estatico']) ){
                            $valor_estatico = $value['estatico'];
                        }
    
                        $Isql = "INSERT INTO ".$dyalogo_canales_electronicos.".dy_wa_plantillas_salientes_variables (id_wa_plantilla_saliente, id_wa_plantilla_variable, accion, id_pregun, valor_estatico) VALUES ('".$id_plantilla_saliente."', '".$value['id']."', ".$value['accion'].", ".$pregun_id.", '".$valor_estatico."')";
    
                        $mysqli->query($Isql);
                    }
                }

                echo json_encode(["id" => $id_plantilla_saliente]);
            }

            if($_POST["oper"] == "edit"){

                $Usql = "UPDATE ".$dyalogo_canales_electronicos.".dy_wa_plantillas_salientes SET nombre = '".$_POST['nombre']."', id_cuenta_whatsapp = '".$_POST['cuentawa']."', id_wa_plantilla = '".$_POST['plantilla']."', id_pregun_telefono = '".$_POST['to']."', correos_envio_mensaje = '".$_POST['correosNotificacion']."' WHERE id_estpas = ".$_POST['id_paso']; 
                $mysqli->query($Usql);
                
                // actualizo variables plantillas
                $id_plantilla_saliente = $_POST['plantillaSaliente'];

                // Elimino los antiguas variables de plantilla
                // $dsql = "DELETE FROM {$dyalogo_canales_electronicos}.dy_wa_plantillas_salientes_variables WHERE id_wa_plantilla_saliente = {$id_plantilla_saliente}";
                // $mysqli->query($dsql);

                if(isset($_POST['campov'])){
                    $arr_campos = $_POST['campov'];
                    
                    foreach ($arr_campos as $key => $value) {
    
                        $pregun_id = 0;
                        $valor_estatico = '';
    
                        if($value['accion'] == 1 && isset($value['pregun']) ){
                            $pregun_id = $value['pregun'];
                        }
                        
                        if($value['accion'] == 2 && isset($value['estatico']) ){
                            $valor_estatico = $value['estatico'];
                        }

                        if(isset($value['hidId']) && $value['hidId'] != '0'){
                            // Si el campo ya esta creado solo lo actualizo
                            $Usql = "UPDATE ".$dyalogo_canales_electronicos.".dy_wa_plantillas_salientes_variables SET id_pregun = ".$pregun_id.", accion= '".$value['accion']."', valor_estatico = '".$valor_estatico."' WHERE id = '".$value['hidId']."'";
                            $mysqli->query($Usql);
                        }else{
                            // Luego inserto los nuevos valores
                            $Isql = "INSERT INTO ".$dyalogo_canales_electronicos.".dy_wa_plantillas_salientes_variables (id_wa_plantilla_saliente, id_wa_plantilla_variable, accion, id_pregun, valor_estatico) VALUES ('".$id_plantilla_saliente."', '".$value['id']."', ".$value['accion'].", '".$pregun_id."', '".$valor_estatico."')";
                            $mysqli->query($Isql);
                        }
    
    
                    }
                }

                // Si pasamos el paso activo seteamos a null el mensaje de error
                if($activo == -1){
                    $update = "UPDATE {$dyalogo_canales_electronicos}.dy_wa_plantillas_salientes SET mensajes_estado = NULL, mensaje_enviado = 0 WHERE id_estpas = ".$_POST['id_paso'];
                    $mysqli->query($update);
                }

                echo json_encode(["id" => $id_plantilla_saliente]);
            }
        }

        if(isset($_GET['realizarPruebaPaso'])){

            $telefono = $_POST['telefono'];
            $plantillaId = $_POST['plantillaId'];
            $bd = $_POST['pob'];

            // Traemos los datos del paso
            $sql = "SELECT * FROM {$dyalogo_canales_electronicos}.dy_wa_plantillas_salientes INNER JOIN {$BaseDatos_systema}.ESTPAS ON id_estpas = ESTPAS_ConsInte__b INNER JOIN DYALOGOCRM_SISTEMA.ESTRAT ON ESTPAS_ConsInte__ESTRAT_b = ESTRAT_ConsInte__b WHERE id = {$plantillaId}";
            $res = $mysqli->query($sql);
            $whaSaliente = $res->fetch_object();

            $estadoPaso = $whaSaliente->ESTPAS_activo;

            // Obtenemos los campos
            $sql = "SELECT PREGUN_ConsInte__b AS id, PREGUN_Texto_____b AS nombre, PREGUN_Tipo______b AS tipo, PREGUN_Longitud__b AS longitud FROM {$BaseDatos_systema}.PREGUN WHERE PREGUN_ConsInte__GUION__b = {$bd} AND (PREGUN_Texto_____b != 'ORIGEN_DY_WF' AND PREGUN_Texto_____b != 'ESTADO_DY')";
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

                    // Limpiamos el campo optIn
                    if($pregun->nombre == 'OPTIN_DY_WF'){
                        $strCampos .= ", G{$bd}_C{$pregun->id}";
                        $strValores .= ",''";
                        $strUpdate .= ", G{$bd}_C{$pregun->id} = ''";
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
            $sqlU = "UPDATE {$BaseDatos}.G{$bd} SET G{$bd}_C{$whaSaliente->id_pregun_telefono} = '{$telefono}' WHERE G{$bd}_ConsInte__b = -1";
            $mysqli->query($sqlU);

            // Validamos la muestra si existe o no y luego la insertamos
            $sqlMuestra = "SELECT * FROM {$BaseDatos}.G{$bd}_M{$whaSaliente->ESTPAS_ConsInte__MUESTR_b} WHERE G{$bd}_M{$whaSaliente->ESTPAS_ConsInte__MUESTR_b}_CoInMiPo__b = -1";
            $resMuestra = $mysqli->query($sqlMuestra);

            if($resMuestra && $resMuestra->num_rows > 0){
                // Actualizar muestra
                insertarEnMuestra($bd, $whaSaliente->ESTPAS_ConsInte__MUESTR_b, -1, 0, true);
            }else{
                // Insertar muestra
                insertarEnMuestra($bd, $whaSaliente->ESTPAS_ConsInte__MUESTR_b, -1, 0, false);
            }

            // Ejecutar el registro
            $respuestaPaso = procesarPasoWhatsapp($whaSaliente->id_estpas, $bd, $whaSaliente->ESTPAS_ConsInte__MUESTR_b, -1, $whaSaliente->ESTRAT_ConsInte_GUION_Pob);

            sleep(10);

            // Revisamos si obtuvimos el log del mensaje enviado y la muestra
            $resMuestra2 = $mysqli->query($sqlMuestra);

            $muestra = null;

            if($resMuestra2 && $resMuestra2->num_rows > 0){
                $muestra2 = $resMuestra2->fetch_object();

                $varEstado = 'G'. $bd . '_M' . $whaSaliente->ESTPAS_ConsInte__MUESTR_b . '_Estado____b';
                $varFechaGestion = 'G'. $bd . '_M' . $whaSaliente->ESTPAS_ConsInte__MUESTR_b . '_FecUltGes_b';
                $varComentario = 'G'. $bd . '_M' . $whaSaliente->ESTPAS_ConsInte__MUESTR_b . '_Comentari_b';
                $varDatoContacto = 'G'. $bd . '_M' . $whaSaliente->ESTPAS_ConsInte__MUESTR_b . '_DatoContactoUG_b';

                $muestra['estado'] = $muestra2->$varEstado;
                $muestra['fechaGestion'] = $muestra2->$varFechaGestion;
                $muestra['comentario'] = $muestra2->$varComentario;
                $muestra['datoContacto'] = $muestra2->$varDatoContacto;
            }

            echo json_encode(["estado" => "ok", "respuesta" => $respuestaPaso, "muestra" => $muestra, "estadoPaso" => $estadoPaso]);
        }
    }

    function getPlantillas($cuentaWa){
        global $dyalogo_canales_electronicos;
        global $mysqli;

        $sqlP = "SELECT * FROM ".$dyalogo_canales_electronicos.".dy_wa_plantillas WHERE id_canal_whatsapp = ".$cuentaWa;
        $resP = $mysqli->query($sqlP);

        $plantillas = array();
        $i = 0;

        if($resP){
            while ($item = $resP->fetch_object()) {
                $plantillas[$i]['id'] = $item->id;
                $plantillas[$i]['nombre'] = $item->nombre;
                $plantillas[$i]['contenido'] = $item->texto;
                $i++;
            }
        }

        return $plantillas;
    }

    function obtenerCanalesWhatsapp($huesped){

        global $URL_SERVER;
        global $DY_MIDDLEWARE_HOST;

        $data = array(
            "intIdHuespedGeneralt" => $huesped,
            "strUsuario_t" => 'crm',
            "strToken_t" => 'D43dasd321',
            "strServer" => $URL_SERVER
        );

        // Se codifica el arreglo en formato JSON
        $dataString = json_encode($data);
        $url = $DY_MIDDLEWARE_HOST.'/dymdw/api/config/w/getChannels';

        // Se inicia la conexion CURL al web service para ser consumido
        $ch = curl_init($url);

        // Se asignan todos los parametros del consumo
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_POSTFIELDS, $dataString);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'Content-Type: application/json',
            'Content-Length: ' . strlen($dataString))
        );

        // Se obtiene la respuesta
        $respuesta = curl_exec($ch);

        // Se obtiene el error
        $error = curl_error($ch);

        // Cerramos la conexion
        curl_close($ch);

        // Si se obtine una respuesta esta se retorna de lo contrario se retorna el error
        if($respuesta){
            return $respuesta;
        }else if($error){
            return $error;
        }
    }

?>