<?php
    session_start();
    ini_set('display_errors', 'On');
    ini_set('display_errors', 1);
    include(__DIR__."../../../../pages/conexion.php");
    include_once("../../../generador/generar_web_form.php");
    // include_once("../../../generador/generar_tablas_bd.php");
    include_once(__DIR__."/../reporteador.php");

    if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
        //Funciones de la carga maestro y eso

        if (isset($_GET["Redireccion"])) {

            $intIdPaso_t = $_POST["intIdPaso_t"];

            $strSQL_t = "SELECT ESTPAS_ConsInte__ESTRAT_b AS id FROM ".$BaseDatos_systema.".ESTPAS WHERE ESTPAS_ConsInte__b = ".$intIdPaso_t;

            if ($resSQL_t = $mysqli->query($strSQL_t)) {

                if ($resSQL_t->num_rows > 0) {

                    $objSQL_t = $resSQL_t->fetch_object();

                    $intIdEstrategia_t = $objSQL_t->id;

                    $strSQL1_t = "SELECT ESTPAS_ConsInte__b AS id, ESTPAS_Comentari_b AS nombre, ESTPAS_Tipo______b AS tipo, B.id AS id_chat, B.dato_integracion AS numero_autorizado FROM ".$BaseDatos_systema.".ESTPAS A LEFT JOIN ".$dyalogo_canales_electronicos.".dy_chat_configuracion B ON A.ESTPAS_ConsInte__b = B.id_estpas WHERE ESTPAS_Tipo______b IN (14,15) AND ESTPAS_ConsInte__ESTRAT_b = ".$intIdEstrategia_t." ORDER BY ESTPAS_Tipo______b, ESTPAS_Comentari_b";

                    if ($resSQL1_t = $mysqli->query($strSQL1_t)) {

                        if ($resSQL1_t->num_rows > 0) {

                            $arrData_t = [];

                            while($obj = $resSQL1_t->fetch_object()){

                                $arrData_t[] = $obj;

                            }

                            echo json_encode($arrData_t);

                        }

                    }

                }

            }

        }
        

        //Datos del formulario
        if(isset($_POST['obtenerDatos']) && $_POST['obtenerDatos'] == true){

            // Obtengo los valores post de la consulta y realizo una limpieza de caracteres
            $pasoId = $mysqli->real_escape_string($_POST['pasoId']);
            $poblacion = $mysqli->real_escape_string($_POST['poblacion']);

            // Genero la url para traer los datos del paso
            $selectData = "SELECT WEBFORM_Consinte__b AS id, WEBFORM_Guion____b AS guion, WEBFORM_Observacion_b AS observaciones, WEBFORM_Nombre_b AS nombre, WEBFORM_tipo_redireccion AS tipo_redireccion, WEBFORM_codigo_propiedad_b AS codigo_propiedad, WEBFORM_url_analytics_b AS url_analytics, 
            b.ESTPAS_activo AS activo, WEBFORM_Ruta_Logo_b as logo, WEBFORM_ConsInte_PREGUN_Campo_Llave_b as pregun_validacion, WEBFORM_Origen_b AS origen, WEBFORM_id_redireccion_b AS selRedireccion, WEBFORM_url_redireccion_b AS inpLinkRedireccion, WEBFORM_Ruta_Css_b AS css, WEBFORM_Url______b AS url, b.ESTPAS_Tipo______b as tipo, WEBFORM_IdMailSaliente__b as wfMailSaliente, WEBFORM_IdCampoMailCliente__b as wfMailClient FROM {$BaseDatos_systema}.WEBFORM a LEFT JOIN DYALOGOCRM_SISTEMA.ESTPAS b ON a.WEBFORM_ConsInte__ESTPAS_b = b.ESTPAS_ConsInte__b WHERE b.ESTPAS_ConsInte__b = {$pasoId} LIMIT 1";
            $res = $mysqli->query($selectData);

            $data = [];
            $existe = false;

            // Valido si existe, sino debo crear el registro en WEBFORM
            if($res->num_rows > 0){

                $data = $res->fetch_object();
                $existe = true;

                // Aqui formateo el resultado del logo, si es null lo trato de enviar como vacio
                // if(is_null($data->logo) || $$data->logo == ''){
                //     $data->logo = '';
                // }

                $data->css = str_replace('/var/www/html/', '', $data->css);
            }else{
                $insert = "INSERT INTO {$BaseDatos_systema}.WEBFORM (WEBFORM_Nombre_b, WEBFORM_Guion____b, WEBFORM_ConsInte__ESTPAS_b, WEBFORM_ConsInte_PREGUN_Campo_Llave_b, WEBFORM_Url______b) VALUES ('WebForm_{$pasoId}', {$poblacion}, {$pasoId}, 0, 'web2')";
                $mysqli->query($insert);

                $res = $mysqli->query($selectData);
                $data = $res->fetch_object();
                $existe = true;
            }

            //obtengo los campos de la bd que se pueden asociar al webform
            $listaCamposN='';
            $listaCamposA='';
            $listaCamposMail = "";

            // Obtenemos los disponibles
            $sql = "SELECT a.PREGUN_ConsInte__b AS id, a.PREGUN_Texto_____b AS nombre,  a.PREGUN_Tipo______b as tipo from {$BaseDatos_systema}.PREGUN a
                LEFT JOIN (SELECT * FROM {$BaseDatos_systema}.WEBFORM_CAMPOS WHERE WEBFORM_CAMPOS_ConsInte__WEBFORM_b = {$data->id}) AS b
                ON a.PREGUN_ConsInte__b = b.WEBFORM_CAMPOS_ConsInte__PREGUN_b
            WHERE a.PREGUN_ConsInte__GUION__b = {$poblacion} AND (a.PREGUN_Texto_____b != 'ORIGEN_DY_WF' AND a.PREGUN_Texto_____b != 'OPTIN_DY_WF' AND a.PREGUN_Texto_____b != 'ESTADO_DY') AND WEBFORM_CAMPOS_ConsInte__b IS NULL";

            $resDisponibles = $mysqli->query($sql);
            if($resDisponibles && $resDisponibles->num_rows > 0){
                if(isset($_POST['infoWS'])){
                    $listaCamposN=array();
                }
                while($row = $resDisponibles->fetch_object()){
                    if(isset($_POST['infoWS'])){
                        array_push($listaCamposN,array('id'=>$row->id,'tipo'=>$row->tipo,'nombre'=>$row->nombre));
                    }else{
                        $listaCamposN .= "<li data-id='{$row->id}' data-type='{$row->tipo}'><table class='table table-hover'><tr><td width='40px'><input type='checkbox' class='flat-red mi-check'></td><td class='nombre'>{$row->nombre}</td></tr></table></li>";
                    }
                }
            }

            // Obtenemos los seleccionados
            $sql = "SELECT PREGUN_ConsInte__b AS id, PREGUN_Texto_____b AS nombre,  PREGUN_Tipo______b as tipo from {$BaseDatos_systema}.PREGUN 
                INNER JOIN {$BaseDatos_systema}.WEBFORM_CAMPOS ON WEBFORM_CAMPOS_ConsInte__PREGUN_b = PREGUN_Consinte__b
                WHERE WEBFORM_CAMPOS_ConsInte__WEBFORM_b = {$data->id}
            ORDER BY WEBFORM_CAMPOS_Orden_b ASC";

            $resAsignados = $mysqli->query($sql);
            if($resAsignados && $resAsignados->num_rows > 0){
                if(isset($_POST['infoWS'])){
                    $listaCamposA=array();
                }
                while ($row = $resAsignados->fetch_object()) {
                    if(isset($_POST['infoWS'])){
                        array_push($listaCamposA,array('id'=>$row->id,'tipo'=>$row->tipo,'nombre'=>$row->nombre));
                    }else{
                        $listaCamposA .= "<li data-id='{$row->id}' data-type='{$row->tipo}'><table class='table table-hover'><tr><td width='40px'><input type='checkbox' class='flat-red mi-check'></td><td class='nombre'>{$row->nombre}</td></tr></table></li>";
                    }
                    if(isset($_POST["com_web_form"])){
                        if($row->tipo == 1 || $row->tipo == 2 || $row->tipo == 14){
                            $listaCamposMail .= "<option value='{$row->id}' data-type='{$row->tipo}'>{$row->nombre}</option>";
                        }
                        
                    }

                }
            }

            // obtengo los mails disponibles para responder

            $listMails = "";

            if(isset($_POST["com_web_form"])){
                $sqlMails = 'SELECT * FROM '.$dyalogo_canales_electronicos.'.dy_ce_configuracion WHERE id_huesped = '.$_SESSION['HUESPED'];

                $resMails = $mysqli->query($sqlMails);
                if($resMails && $resMails->num_rows > 0){
                    while ($rowMail = $resMails->fetch_object()) {
                        $listMails .= "<option value='{$rowMail->id}'>{$rowMail->direccion_correo_electronico}</option>";
                    }
                }
            }

            // Generamos el codigo de web2
            $cod = base64_encode($poblacion.'_'.$data->id);

            echo json_encode([
                "existe" => $existe,
                "datos" => $data,
                "listaCamposN" => $listaCamposN,
                "listaCamposA" => $listaCamposA,
                "web2" => $cod,
                "listMails" => $listMails,
                "listaCamposMail" => $listaCamposMail
            ]);
        }

        // Insercion y actualizacio
        if(isset($_GET['insertarDatos']) && $_GET['insertarDatos'] == true){

            // Inicializo las variables que usare

            $pasoId = $mysqli->real_escape_string($_POST['wfPasoId']);
            $webformId = isset($_POST['wfId']) ? $mysqli->real_escape_string($_POST['wfId']) : 0;
            $webformTipo = isset($_POST['wfTipo']) ? $mysqli->real_escape_string($_POST['wfTipo']) : '4';

            $oper = $mysqli->real_escape_string($_POST['wfOper']);

            $activo = isset($_POST['wfPasoActivo']) ? $_POST['wfPasoActivo'] :"0";
            $nombre = $mysqli->real_escape_string($_POST['wfNombre']);
            $poblacion = $mysqli->real_escape_string($_POST['wfPoblacion']);
            $pregunValidacion = $mysqli->real_escape_string($_POST['wfPregunValidacion']);
            $origen = $mysqli->real_escape_string($_POST['Web_Origen']);
            $observaciones = $mysqli->real_escape_string($_POST['wfObservaciones']);
            $selRedirecionFormWeb = $mysqli->real_escape_string($_POST['selRedirecionFormWeb']);
            $inpLinkPage = $mysqli->real_escape_string($_POST['inpLinkPage']);
            $inpCodigoGoogle = preg_replace("([^0-9A-Za-z-\s])", "", $_POST['inpCodigoGoogle']);
            $IdMailSaliente = isset($_POST['wfMailSaliente']) ? $_POST['wfMailSaliente'] :"-1";
            $idCampoMailClient = isset($_POST['wfMailClient']) ? $_POST['wfMailClient'] : 'null';

            $selRedireccion = -1;
            $inpLinkRedireccion = "";
            $respuestaCss = [];
            $respuestaLogo = [];

            if (isset($_POST['selRedireccion'])) {

                $selRedireccion = $_POST['selRedireccion'];

            }
            
            if (isset($_POST['inpLinkRedireccion'])) {
                
                $inpLinkRedireccion = $mysqli->real_escape_string($_POST['inpLinkRedireccion']);

            }


            $error = "";

            // Actualizo la informacion del paso
            $sqlPaso = "UPDATE {$BaseDatos_systema}.ESTPAS SET ESTPAS_Comentari_b = '{$nombre}', ESTPAS_Activo = {$activo} WHERE ESTPAS_ConsInte__b = ".$pasoId;
            $mysqli->query($sqlPaso); 

            // Si el operador es add, genero la consulta para generar un nuevo registro de WEBFORM
            if($oper == 'add'){
                $sql = "INSERT INTO {$BaseDatos_systema}.WEBFORM (WEBFORM_Nombre_b, WEBFORM_Guion____b, WEBFORM_Observacion_b, WEBFORM_ConsInte__ESTPAS_b, WEBFORM_ConsInte_PREGUN_Campo_Llave_b, WEBFORM_Origen_b, WEBFORM_tipo_redireccion, WEBFORM_url_analytics_b, WEBFORM_codigo_propiedad_b, WEBFORM_id_redireccion_b, WEBFORM_url_redireccion_b, WEBFORM_Url______b, WEBFORM_IdMailSaliente__b,WEBFORM_IdCampoMailCliente__b) 
                VALUES ('{$nombre}', {$poblacion}, '{$observaciones}', {$pasoId}, {$pregunValidacion}, '{$origen}', {$selRedirecionFormWeb}, '{$inpLinkPage}','{$inpCodigoGoogle}','{$selRedireccion}','{$inpLinkRedireccion}', 'web2', $IdMailSaliente, $idCampoMailClient)";
            }

            // Si el operador es edit, genero la consulta para guardar el registro en base al id del WEBFORM
            if($oper == 'edit'){
                $sql = "UPDATE {$BaseDatos_systema}.WEBFORM SET WEBFORM_Nombre_b = '{$nombre}', WEBFORM_Observacion_b = '{$observaciones}',WEBFORM_ConsInte_PREGUN_Campo_Llave_b = {$pregunValidacion}, 
                WEBFORM_Origen_b = '{$origen}', WEBFORM_tipo_redireccion = {$selRedirecionFormWeb}, WEBFORM_url_analytics_b = '{$inpLinkPage}', WEBFORM_codigo_propiedad_b = '{$inpCodigoGoogle}', WEBFORM_id_redireccion_b = '{$selRedireccion}', WEBFORM_url_redireccion_b = '{$inpLinkRedireccion}', WEBFORM_Url______b = 'web2', WEBFORM_IdMailSaliente__b = '{$IdMailSaliente}', WEBFORM_IdCampoMailCliente__b = {$idCampoMailClient} WHERE WEBFORM_Consinte__b = {$webformId}";
            }

            if($mysqli->query($sql) === TRUE){
                
                // Si se guardo correctamente hago lo siguiente
                if($oper == 'add'){
                    // Asigno el nuevo id 
                    $webformId = $mysqli->insert_id;
                    try {
                        @guardar_auditoria("INSERTAR", "INSERTAR REGISTRO {$webformId} EN G13");
                    } catch (\Throwable $th){}

                }else if ($oper == 'edit'){
                    // Solo guardo la auditoria
                    try {
                        @guardar_auditoria("ACTUALIZAR", "ACTUALIZO EL REGISTRO # ".$webformId." EN G13");
                    } catch (\Throwable $th){}
                }

                
                //validar si hay hoja de estilos para cargar
                if(isset($_FILES['css']) && $webformId != 0){
                    $respuestaCss = uploadCss($_FILES['css'],$webformId);
                }

                
                //Independiente si es insertar o actualizar valido si hay imagen para guardarla
                if(isset($_FILES['logo_form']) && $webformId != 0){
                    $respuestaLogo = storeLogo($_FILES['logo_form'], $webformId);
                }

                // Actualizamos el orden de campos
                $campos = isset($_POST['ordenCampos']) ? $_POST['ordenCampos'] : '';
                
                if($campos != ''){
                    $arrCampos = explode(',', $campos);

                    for ($i=0; $i < count($arrCampos); $i++) { 
                        
                        // Validemos que lo que tenemos es un numero
                        if(is_numeric($arrCampos[$i])){
                            // Actualizamos la posicion
                            $sql = "UPDATE {$BaseDatos_systema}.WEBFORM_CAMPOS SET WEBFORM_CAMPOS_Orden_b = {$i} WHERE WEBFORM_CAMPOS_ConsInte__PREGUN_b = {$arrCampos[$i]} AND WEBFORM_CAMPOS_ConsInte__WEBFORM_b = {$webformId}";
                            $mysqli->query($sql);
                        }

                    }
                }

                if($webformTipo == 19){

                    // SE GENERAN LAS METAS PARA ESTE PASO

                    $strEstratSql = "SELECT ESTPAS_ConsInte__ESTRAT_b as estrategia FROM {$BaseDatos_systema}.ESTPAS WHERE ESTPAS_ConsInte__b = {$pasoId};";
                    $resEstratSql = $mysqli->query($strEstratSql);
                    $idEstrategia = 0;
                    if($resEstratSql && $resEstratSql->num_rows > 0){
                        if($resEstratObj = $resEstratSql->fetch_object()){
                            $idEstrategia = $resEstratObj->estrategia;
                        }
                        
                    }

                    insertarMetas($idEstrategia,$pasoId,1);
                    
                }


                // Despues de haber insertado o actualizado regenero el formulario web
                generar_Web_Form($poblacion, $webformId, $webformTipo);
                // generar_tablas_bd($poblacion, 0, 0, 0, 1);

                $valido = true;

            }else{
                // De lo contrario devuelvo una respuesta con el mensaje de error
                $error = "Error Hacieno el proceso los registros : " . $mysqli->error;
                $valido = false;
            }

            echo json_encode([
                "id" => $webformId,
                "valido" => $valido,
                "mensajeError" => $error,
                "respuestaLogo" => $respuestaLogo,
                "respuestaCss" => $respuestaCss
            ]);
        }

        if(isset($_GET['agregarCamposWeb'])){

            $webformId = $_POST['webformId'];

            $arrCampos = $_POST['arrCampos'];
            for ($i=0; $i < count($arrCampos); $i++) {
                $LsqlInsert = "INSERT INTO {$BaseDatos_systema}.WEBFORM_CAMPOS (WEBFORM_CAMPOS_ConsInte__WEBFORM_b, WEBFORM_CAMPOS_ConsInte__PREGUN_b, WEBFORM_CAMPOS_Orden_b) VALUES ({$webformId}, {$arrCampos[$i]}, 0)";
                if($mysqli->query($LsqlInsert) === true){
                    $estado = 'ok';
                } else  {
                    $estado = 'error';
                    break;
                }
            }
            
            if(isset($_POST['generarByWS'])){
                generar_Web_Form($_POST['poblacion'], $webformId, 4);
            }
            echo json_encode(['estado' => $estado]);
        }        
        
        if(isset($_GET['quitarCamposWeb'])){

            $webformId = $_POST['webformId'];

            $arrCampos = $_POST['arrCampos'];
            for ($i=0; $i < count($arrCampos); $i++) {
                $sqlDelete = "DELETE FROM {$BaseDatos_systema}.WEBFORM_CAMPOS WHERE WEBFORM_CAMPOS_ConsInte__WEBFORM_b = {$webformId} AND WEBFORM_CAMPOS_ConsInte__PREGUN_b = {$arrCampos[$i]}";
                if($mysqli->query($sqlDelete) === true){
                    $estado = 'ok';
                } else  {
                    $estado = 'error';
                    break;
                }
            }
            
            echo json_encode(['estado' => $estado]);
        }
        
    }

    function guardar_auditoria($accion, $superAccion){

        global $mysqli;
        global $BaseDatos_systema;

        $str_Lsql = "INSERT INTO ".$BaseDatos_systema."AUACAD (AUACAD_Fecha_____b , AUACAD_Hora______b, AUACAD_Ejecutor__b, AUACAD_TipoAcci__b , AUACAD_SubTipAcc_b, AUACAD_Accion____b , AUACAD_Huesped___b ) VALUES ('".date('Y-m-d H:s:i')."', '".date('Y-m-d H:s:i')."', ".$_SESSION['IDENTIFICACION'].", 'G13', '".$accion."', '".$superAccion."', ".$_SESSION['HUESPED']." );";
        $mysqli->query($str_Lsql);
    }

    // Esta funcion se encargaria de guardar la imagen y actualizar el registro
    function storeLogo($file, $id){
        global $mysqli;
        global $BaseDatos_systema;
            
        if(isset($file['tmp_name']) && !empty($file['tmp_name'])){
            $tmp = explode('.', basename($file['name']));
            $extension = end($tmp);
            
            $name = $id.".".$extension;
            
            $path = "/var/www/html/img_usr/".$name;
            $pathname = "/img_usr/$name";
            $path = str_replace(' ', '', $path);

            if (file_exists($path)) {
                unlink($path);
            }

            if (move_uploaded_file($file['tmp_name'], $path)){
                // echo "El archivo ha sido cargado correctamente.";
                $usql = "UPDATE ".$BaseDatos_systema.".WEBFORM SET WEBFORM_Ruta_Logo_b = '".$pathname."' WHERE WEBFORM_Consinte__b = ".$id;
                $mysqli->query($usql);

                return array(true, "El archivo ha sido cargado correctamente.");
            }else{
                // echo "Ocurrió algún error al subir el fichero. No pudo guardarse.";
                return array(false, "Ocurrió algún error al subir el fichero. No pudo guardarse.");
            }
        }

        return [];
    }

    //funcion para cargar la hoja de estilos del webform al server y actualizar la ruta en la tabla
    function uploadCss($archivo, $id_web){
        global $mysqli;
        global $BaseDatos_systema;
        
        if(isset($archivo['tmp_name']) && !empty($archivo['tmp_name'])){

            $id_huesped=isset($_SESSION['HUESPED_CRM']) ? $_SESSION['HUESPED_CRM'] : false;
            if($id_huesped){
                $file=$archivo;
                $filename="/var/www/html/recursos_clientes/{$id_huesped}/css_web/".date('Y-m-d_H-i-s')."_{$id_web}_{$file['name']}";
                $filetype=$file['type'];
                if($filetype=='text/css'){
                    if(!is_dir("/var/www/html/recursos_clientes")){
                        mkdir("/var/www/html/recursos_clientes", 0777, true);
                    }
                    
                    if(!is_dir("/var/www/html/recursos_clientes/{$id_huesped}")){
                        mkdir("/var/www/html/recursos_clientes/{$id_huesped}", 0777, true);
                    }
                    
                    if(!is_dir("/var/www/html/recursos_clientes/{$id_huesped}/css_web")){
                        mkdir("/var/www/html/recursos_clientes/{$id_huesped}/css_web", 0777, true);
                    }
                    
                    if(move_uploaded_file($file['tmp_name'], $filename)){
                        $sql=$mysqli->query("UPDATE {$BaseDatos_systema}.WEBFORM SET WEBFORM_Ruta_Css_b = '{$filename}' WHERE WEBFORM_Consinte__b = {$id_web}");
                        // echo "Archivo cargado exitosamente";
                        return array(true, "Archivo cargado exitosamente");
                    }else{
                        // echo "No se pudo cargar la hoja de estilos";
                        return array(false, "No se pudo cargar la hoja de estilos");
                    }
                }else{
                    // echo "El archivo debe ser una hoja de estilos en cascadda (.css)";
                    return array(false, "El archivo debe ser una hoja de estilos en cascadda (.css)");
                }
            }else{
                // echo "Por favor inicie sesión nuevamente";
                return array(false, "Por favor inicie sesión nuevamente");
            }

        }

        return [];
    }

?>
