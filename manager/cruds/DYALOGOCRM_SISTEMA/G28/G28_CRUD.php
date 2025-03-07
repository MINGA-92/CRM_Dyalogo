<?php 
    session_start();
    ini_set('display_errors', 'On');
    ini_set('display_errors', 1);
    include_once(__DIR__."../../../../pages/conexion.php");
    include_once(__DIR__."../../../../global/GeneradorDeFlechas.php");
    include_once(__DIR__."/../reporteador.php");

    function guardar_auditoria($accion, $superAccion){
        global $mysqli;
        global $BaseDatos_systema;
        $str_Lsql = "INSERT INTO ".$BaseDatos_systema."AUACAD (AUACAD_Fecha_____b , AUACAD_Hora______b, AUACAD_Ejecutor__b, AUACAD_TipoAcci__b , AUACAD_SubTipAcc_b, AUACAD_Accion____b , AUACAD_Huesped___b ) VALUES ('".date('Y-m-d H:s:i')."', '".date('Y-m-d H:s:i')."', ".$_SESSION['IDENTIFICACION'].", 'G14', '".$accion."', '".$superAccion."', ".$_SESSION['HUESPED']." );";
        $mysqli->query($str_Lsql);
    } 

    if(!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest'){

        // Traer la informacion de la bola
        if(isset($_GET['getDatos'])){
            $pasoId = $_POST['pasoId'];

            $str_Lsql = 'SELECT ESTPAS_ConsInte__b as id, ESTPAS_Comentari_b as nombre, ESTPAS_activo as activo FROM '.$BaseDatos_systema.'.ESTPAS WHERE ESTPAS_ConsInte__b = '.$_POST['pasoId'];
            $res = $mysqli->query($str_Lsql);
            $datos = $res->fetch_object();

            $str_Lsql = 'SELECT id, nombre, ruta_logo, id_pregun_campo_busqueda, id_pregun_campo_busqueda, integrado_con, dato_integracion, frase_sin_agentes_disponibles, frase_agente_asignado, frase_bienvenida_autorespuesta, frase_fuera_horario, mensaje_bienvenida, tiempo_maximo_asignacion, tiempo_maximo_inactividad_cliente, frase_tiempo_asignacion_excedido, tiempo_maximo_inactividad_agente, frase_inactividad_agente, tiempo_maximo_inactividad_cliente, frase_inactividad_cliente, link_politica_privacidad,dentro_horario_accion,dentro_horario_detalle_accion,fuera_horario_accion,fuera_horario_detalle_accion, id_estpas_envio_historial FROM '.$dyalogo_canales_electronicos.'.dy_chat_configuracion WHERE id_estpas = '.$pasoId;
            $res = $mysqli->query($str_Lsql);
            $chat = $res->fetch_object();

            // Aqui trato de cargar los campos del formulario
            $disponible = "";
            $seleccionado = "";

            if(isset($_POST['bd']) && $_POST['bd'] != null){

                $bd = $_POST['bd'];

                // Busco la configuracion, si es que existe
                $str_Lsql = "SELECT id, id_formulario FROM ".$dyalogo_canales_electronicos.".dy_chat_configuracion WHERE integrado_con = 'web' AND id_estpas = ".$_POST['pasoId'];
                $res = $mysqli->query($str_Lsql);

                if($res->num_rows > 0){
                    $data = $res->fetch_array();

                    // Traigo las listas de campos disponibles y seleccionados
                    $sqlDisponibles = "SELECT PREGUN_ConsInte__b as id, PREGUN_Texto_____b as nombre, PREGUN_IndiRequ__b as requerido, PREGUN_Tipo______b as tipo FROM DYALOGOCRM_SISTEMA.PREGUN s LEFT JOIN (SELECT * FROM dyalogo_general.dy_preguntas WHERE id_formulario = ".$data['id_formulario'].") AS g ON s.PREGUN_ConsInte__b = g.id_pregun WHERE s.PREGUN_ConsInte__GUION__b = ".$bd." AND g.id IS NULL AND PREGUN_Texto_____b NOT LIKE '%_DY%' AND PREGUN_Tipo______b NOT IN (6, 11, 13)";
                    $respDisponible = $mysqli->query($sqlDisponibles);
                    if($respDisponible){
                        while ($item = $respDisponible->fetch_object()){
                            $requerido = 0;
                            if($item->requerido == -1){
                                $requerido = 1;
                            }
                            $disponible .= '<li data-id="'.$item->id.'" data-requerido="'.$item->requerido.'" data-nombre="'.$item->nombre.'" data-tipo="'.$item->tipo.'" data-formulario="'.$data['id_formulario'].'"><table class="table table-hover"><tr><td width="40px"><input type="checkbox" class="flat-red mi-check"></td><td class="nombre">'.$item->nombre.'</td></tr></table></li>';
                        }
                    }

                    $sqlSeleccionado = "SELECT PREGUN_ConsInte__b as id, PREGUN_Texto_____b as nombre, PREGUN_IndiRequ__b as requerido, PREGUN_Tipo______b as tipo FROM DYALOGOCRM_SISTEMA.PREGUN s JOIN dyalogo_general.dy_preguntas g ON s.PREGUN_ConsInte__b = g.id_pregun AND g.id_formulario = ".$data['id_formulario'];
                    $respSeleccionado = $mysqli->query($sqlSeleccionado);
                    if($respSeleccionado){
                        while ($item = $respSeleccionado->fetch_object()){
                            $requerido = 0;
                            if($item->requerido == -1){
                                $requerido = 1;
                            }
                            $seleccionado .= '<li data-id="'.$item->id.'" data-requerido="'.$item->requerido.'" data-nombre="'.$item->nombre.'" data-tipo="'.$item->tipo.'" data-formulario="'.$data['id_formulario'].'"><table class="table table-hover"><tr><td width="40px"><input type="checkbox" class="flat-red mi-check"></td><td class="nombre">'.$item->nombre.'</td></tr></table></li>';
                        }
                    }
                }else{
                    $sqlDisponibles = "SELECT PREGUN_ConsInte__b as id, PREGUN_Texto_____b as nombre, PREGUN_IndiRequ__b as requerido, PREGUN_Tipo______b as tipo FROM DYALOGOCRM_SISTEMA.PREGUN WHERE PREGUN_ConsInte__GUION__b = ".$bd." AND PREGUN_Texto_____b NOT LIKE '%_DY%' AND PREGUN_Tipo______b NOT IN (6, 11, 13)";
                    
                    $respDisponible = $mysqli->query($sqlDisponibles);
                    if($respDisponible){
                        while ($item = $respDisponible->fetch_object()){
                            $requerido = 0;
                            if($item->requerido == -1){
                                $requerido = 1;
                            }
                            $disponible .= '<li data-id="'.$item->id.'" data-requerido="'.$item->requerido.'" data-nombre="'.$item->nombre.'" data-tipo="'.$item->tipo.'" data-formulario="0"><table class="table table-hover"><tr><td width="40px"><input type="checkbox" class="flat-red mi-check"></td><td class="nombre">'.$item->nombre.'</td></tr></table></li>';
                        }
                    }
                }
                
                
            }

            // Horarios
            $horario = [];
            if($chat){
                $horariosSql = "SELECT * FROM ".$dyalogo_canales_electronicos.".dy_chat_horarios WHERE id_configuracion = ".$chat->id;
                $resHorario = $mysqli->query($horariosSql);
                
                while($row = $resHorario->fetch_object()){
                    $horario[$row->dia_inicial] = $row;
                }            
            }


            echo json_encode([
                "datosPaso" => $datos,
                "datosChat" => $chat,
                "horario" => $horario,
                "camposFormularioDisponible" => $disponible,
                "camposFormularioSeleccionados" => $seleccionado
            ]);
        }

        // Insertar datos
        if(isset($_GET['insertarDatos']) && isset($_POST["oper"])){

            // Independiente si es nuevo o no actualizo el paso
            $pasoId = $_POST['id_paso'];
            $poblacion = $_GET['poblacion'];

            $activo = isset($_POST['pasoActivo']) ? $_POST['pasoActivo'] : 0;

            $pasoSql = "UPDATE ".$BaseDatos_systema.".ESTPAS SET ESTPAS_Comentari_b = '".$_POST['nombre']."', ESTPAS_activo = '".$activo."' WHERE ESTPAS_ConsInte__b = ".$_POST['id_paso'];
            $mysqli->query($pasoSql);

            $nombre = $_POST['nombre'];
            $huespedId = $_POST['huesped'];
            
            if($_POST["oper"] == "add"){

                // Primero creamos el formulario
                $Lsql = "INSERT INTO ".$BaseDatos_general.".dy_formularios (nombre, id_huesped) VALUES ('".$nombre."','".$huespedId."')";
                $mysqli->query($Lsql);
                $formularioId = $mysqli->insert_id;
                
                // Llamo la funcion para insertar los campos del formulario
                insertarCampos($_POST['camposFormulario'], $formularioId);

                // Ahora si inserto los datos del chat
                
                //Mensajes generales automaticos
                $mensajeBienvenidaAutorespuesta =   $_POST['dentroHorarioMensaje'];
                $mensajeFueraHorario            =   $_POST['fueraHorarioMensaje'];
                $mensajeSinAgenteDisponible     =   'Por favor espera mientras tenemos agentes disponibles.';
                $mensajeAgenteAsignado          =   'Ha sido asignado el agente.';
                $tiempoMaximoAsignacion         =   10;
                $mensajeTiempoExcedido          =   'Nuestros agentes continúan ocupados, por favor intenta de nuevo mas tarde.';
                $tiempoMaximoInactividadAgente  =   8;
                $mensajeInactividadAgente       =   'Lo sentimos, la comunicación dejó de estar activa, intenta nuevamente.';
                $TiempoMaximoInactividadCliente =   8;
                $mensajeInactividadCliente      =   'Seguramente te ocupaste, porque dejaste de hablarnos. No importa, cuando lo desees puedes comunicarte con nosotros nuevamente.';

                $fraseSolicitudAccion = "Selecciona una opción";
                $fraseSolicitudIdUsuario = "Por favor digita tu dirección de correo electrónico";
                $validaSesionesPorLlave = 1;
                $activarTimeoutInactividadAgente = 1;
                $activarTimeoutInactividadCliente = 1;
                $fraseValidacionLlave = "Usted ya tiene otra conversación activa. Si ya no tiene acceso al dispositivo donde la tenía, puede esperar unos minutos e intentar nuevamente.";
                $placeholders = 1;
                $publicarApp = 1;

                $tipoIntegracion = 'web';

                $titulo = $_POST['tituloWeb'];
                $mensajeBienvenida = $_POST['mensajeBienvenida'];
                $campoBusqueda = (isset($_POST['campoBusquedaWeb'])) ? $_POST['campoBusquedaWeb'] : 0;
                $datoIntegracion = null;
                $urlPoliticasPrivacidad = $_POST['politicasPrivacidad'];

                // Aqui es donde inserto
                $Lsql = "INSERT INTO ".$dyalogo_canales_electronicos.".dy_chat_configuracion (
                    nombre, ruta_logo, id_formulario, mensaje_bienvenida, id_huesped, frase_solicitud_accion, frase_fuera_horario, 
                    frase_solicitud_id_usuario, tiempo_maximo_asignacion, frase_tiempo_asignacion_excedido, valida_sesiones_por_llave, 
                    activar_timeout_inactividad_agente, activar_timeout_inactividad_cliente, tiempo_maximo_inactividad_agente, 
                    tiempo_maximo_inactividad_cliente, frase_inactividad_agente, frase_inactividad_cliente, frase_validacion_llave, placeholders, 
                    publicar_app, integrado_con, dato_integracion, frase_sin_agentes_disponibles, frase_agente_asignado, 
                    frase_bienvenida_autorespuesta, id_pregun_campo_busqueda, link_politica_privacidad, id_estpas
                ) VALUES (
                    '".$titulo."','','".$formularioId."','".$mensajeBienvenida."','".$_SESSION['HUESPED']."','".$fraseSolicitudAccion."','".$mensajeFueraHorario."',
                    '".$fraseSolicitudIdUsuario."','".$tiempoMaximoAsignacion."','".$mensajeTiempoExcedido."','".$validaSesionesPorLlave."',
                    '".$activarTimeoutInactividadAgente."','".$activarTimeoutInactividadCliente."','".$tiempoMaximoInactividadAgente."',
                    '".$TiempoMaximoInactividadCliente."','".$mensajeInactividadAgente."','".$mensajeInactividadCliente."',
                    '".$fraseValidacionLlave."','".$placeholders."','".$publicarApp."','".$tipoIntegracion."', 
                    '".$datoIntegracion."', '".$mensajeSinAgenteDisponible."', '".$mensajeAgenteAsignado."', '".$mensajeBienvenidaAutorespuesta."',
                    '".$campoBusqueda."', '".$urlPoliticasPrivacidad."', ".$pasoId.")";
                
                $mysqli->query($Lsql);
                
                $id_chat = $mysqli->insert_id;
                
                // Inserto los horarios
                insertarHorarios($id_chat, 'insert');

                //Inserto la accion
                $accion = 0;
                $campanaIdentificador = null;

                if($_POST['dentroHorarioAccion'] == 1){
                    
                    $accionDetalle = $_POST['campan'];
                    $campanaIdentificador = $_POST['campan'];

                }else if($_POST['dentroHorarioAccion'] == 2) {
                    $accionDetalle = $_POST['bot'];
                    agregarConfiguracionBot($id_chat, $accionDetalle);

                }else if($_POST['dentroHorarioAccion'] == 3){
                    $accionDetalle = $_POST['webform'];
                }

                $upd = "UPDATE ".$dyalogo_canales_electronicos.".dy_chat_configuracion SET dentro_horario_accion = ".$_POST['dentroHorarioAccion'].", dentro_horario_detalle_accion = ".$accionDetalle." WHERE id = ".$id_chat;
                $mysqli->query($upd);

                // Si la accion fuera de horario es 3 genero un link para que se guarde
                if($_POST['dentroHorarioAccion'] == 3){
                    $link = generarLinkWebform($accionDetalle);
                    $sql = "UPDATE ".$dyalogo_canales_electronicos.".dy_chat_configuracion SET link_webform = '".$link."' WHERE id = ".$id_chat;
                    $mysqli->query($sql);
                }

                //Fuera de horario
                if($_POST['fueraHorarioAccion'] == 1){
                    
                    $accionDetallef = $_POST['fueraHorarioCampana'];
                    $campanaIdentificador = $_POST['fueraHorarioCampana'];

                }else if($_POST['fueraHorarioAccion'] == 2) {
                    $accionDetallef = $_POST['fueraHorarioBot'];
                    agregarConfiguracionBot($id_chat, $accionDetallef);
                }else if($_POST['fueraHorarioAccion'] == 3) {
                    $accionDetallef = $_POST['fueraHorarioWebform'];
                }

                $updf = "UPDATE ".$dyalogo_canales_electronicos.".dy_chat_configuracion SET fuera_horario_accion = ".$_POST['fueraHorarioAccion'].", fuera_horario_detalle_accion = ".$accionDetallef." WHERE id = ".$id_chat;
                $mysqli->query($updf);

                // Si la accion fuera de horario es 3 genero un link para que se guarde
                if($_POST['fueraHorarioAccion'] == 3){
                    $link = generarLinkWebform($accionDetallef);
                    $sql = "UPDATE ".$dyalogo_canales_electronicos.".dy_chat_configuracion SET link_webform = '".$link."' WHERE id = ".$id_chat;
                    $mysqli->query($sql);
                }

                // Hay que hacer una insercion que se inserta el chat_accion pero para eso se necesita la campana_cbx
                agregarConfiguracionCampana($id_chat, $titulo, $campanaIdentificador);

            }
            
            if($_POST["oper"] == "edit"){

                //Mensajes generales automaticos
                $mensajeBienvenidaAutorespuesta =   $_POST['dentroHorarioMensaje'];
                $mensajeFueraHorario            =   $_POST['fueraHorarioMensaje'];
                $mensajeSinAgenteDisponible     =   'Por favor espera mientras tenemos agentes disponibles.';
                $mensajeAgenteAsignado          =   'Ha sido asignado el agente.';
                $tiempoMaximoAsignacion         =   10;
                $mensajeTiempoExcedido          =   'Nuestros agentes continúan ocupados, por favor intenta de nuevo mas tarde.';
                $tiempoMaximoInactividadAgente  =   8;
                $mensajeInactividadAgente       =   'Lo sentimos, la comunicación dejó de estar activa, intenta nuevamente.';
                $TiempoMaximoInactividadCliente =   8;
                $mensajeInactividadCliente      =   'Seguramente te ocupaste, porque dejaste de hablarnos. No importa, cuando lo desees puedes comunicarte con nosotros nuevamente.';

                $tipoIntegracion = "web";

                $titulo = $_POST['tituloWeb'];
                $mensajeBienvenida = $_POST['mensajeBienvenida'];
                $campoBusqueda = (isset($_POST['campoBusquedaWeb'])) ? $_POST['campoBusquedaWeb'] : 0;
                $datoIntegracion = null;
                $urlPoliticasPrivacidad = $_POST['politicasPrivacidad'];

                $id_campanas_crm = null;

                $Lsql = "UPDATE ".$dyalogo_canales_electronicos.".dy_chat_configuracion SET 
                nombre = '".$titulo."', mensaje_bienvenida = '".$mensajeBienvenida."', tiempo_maximo_asignacion = ".$tiempoMaximoAsignacion.", 
                frase_tiempo_asignacion_excedido = '".$mensajeTiempoExcedido."', tiempo_maximo_inactividad_agente = ".$tiempoMaximoInactividadAgente.",         
                tiempo_maximo_inactividad_cliente = ".$TiempoMaximoInactividadCliente.", frase_inactividad_agente = '".$mensajeInactividadAgente."', 
                frase_inactividad_cliente  = '".$mensajeInactividadCliente."', integrado_con = '".$tipoIntegracion."', dato_integracion = '".$datoIntegracion."', 
                frase_sin_agentes_disponibles = '".$mensajeSinAgenteDisponible."', frase_agente_asignado = '".$mensajeAgenteAsignado."', 
                frase_bienvenida_autorespuesta = '".$mensajeBienvenidaAutorespuesta."', frase_fuera_horario = '".$mensajeFueraHorario."',
                id_pregun_campo_busqueda = '".$campoBusqueda."', link_politica_privacidad = '".$urlPoliticasPrivacidad."'        
                WHERE id = ".$_POST['configuracionId']." AND integrado_con = '".$tipoIntegracion."'";
                
                $mysqli->query($Lsql);

                // Ahora acutualizo los horarios
                insertarHorarios($_POST['configuracionId'], 'update');

                // Esto solo deberia acutalizarse si es campaña el horario
                // $Lsql = "UPDATE ".$dyalogo_canales_electronicos.".dy_chat_opciones_accion SET opcion = '".$_POST['G10_C71']."', accion = 1, id_campana = ".$id_campanas_cbx." WHERE id_chat_cfg = ".$datos_chat['id'];
                // $mysqli->query($Lsql);

                // Actualizo los campos del formulario
                $Lsql = "SELECT id, id_formulario FROM ".$dyalogo_canales_electronicos.".dy_chat_configuracion WHERE id = ".$_POST['configuracionId']." AND integrado_con = '".$tipoIntegracion."'";
                $res = $mysqli->query($Lsql);

                if($res->num_rows > 0){
                    $row = $res->fetch_object();
                    $Lsql = "UPDATE ".$BaseDatos_general.".dy_formularios SET nombre = '".$nombre."' WHERE id = ".$row->id_formulario; 
                    $mysqli->query($Lsql);
    
                    //Actualizo los campos
                    insertarCampos($_POST['camposFormulario'], $row->id_formulario);
                }

                // Acciones
                $id_chat = $_POST['configuracionId'];
                //Inserto la accion
                $accion = 0;
                $campanaIdentificador = null;

                if($_POST['dentroHorarioAccion'] == 1){
                    
                    $accionDetalle = $_POST['campan'];
                    $campanaIdentificador = $_POST['campan'];

                }else if($_POST['dentroHorarioAccion'] == 2) {
                    $accionDetalle = $_POST['bot'];
                    agregarConfiguracionBot($id_chat, $accionDetalle);
                }else if($_POST['dentroHorarioAccion'] == 3){
                    $accionDetalle = $_POST['webform'];
                }

                $upd = "UPDATE ".$dyalogo_canales_electronicos.".dy_chat_configuracion SET dentro_horario_accion = ".$_POST['dentroHorarioAccion'].", dentro_horario_detalle_accion = ".$accionDetalle." WHERE id = ".$id_chat;
                $mysqli->query($upd);

                if($_POST['dentroHorarioAccion'] == 3){
                    $link = generarLinkWebform($accionDetalle);
                    $sql = "UPDATE ".$dyalogo_canales_electronicos.".dy_chat_configuracion SET link_webform = '".$link."' WHERE id = ".$id_chat;
                    $mysqli->query($sql);
                }

                //Fuera de horario
                if($_POST['fueraHorarioAccion'] == 1){
                    
                    $accionDetallef = $_POST['fueraHorarioCampana'];
                    $campanaIdentificador = $_POST['fueraHorarioCampana'];

                }else if($_POST['fueraHorarioAccion'] == 2) {
                    $accionDetallef = $_POST['fueraHorarioBot'];
                    agregarConfiguracionBot($id_chat, $accionDetallef);
                }else if($_POST['fueraHorarioAccion'] == 3) {
                    $accionDetallef = $_POST['fueraHorarioWebform'];
                }

                $updf = "UPDATE ".$dyalogo_canales_electronicos.".dy_chat_configuracion SET fuera_horario_accion = ".$_POST['fueraHorarioAccion'].", fuera_horario_detalle_accion = ".$accionDetallef." WHERE id = ".$id_chat;
                $mysqli->query($updf);

                // Si la accion fuera de horario es 3 genero un link para que se guarde
                if($_POST['fueraHorarioAccion'] == 3){
                    $link = generarLinkWebform($accionDetallef);
                    $sql = "UPDATE ".$dyalogo_canales_electronicos.".dy_chat_configuracion SET link_webform = '".$link."' WHERE id = ".$id_chat;
                    $mysqli->query($sql);
                }

                // Hay que hacer una insercion que se inserta el chat_accion pero para eso se necesita la campana_cbx
                agregarConfiguracionCampana($id_chat, $titulo, $campanaIdentificador);

            }

            // Sea insertar o actualizar el proceso de insertar el logo es el mismo para ambos casos
            if(isset($_FILES['logo_web'])){
                storeLogoChat($_FILES['logo_web'], $_POST['id_paso']);
            }

            // Realizo el proceso para generar las flechas que salen de la bola
            limpiarFlechas($pasoId);
            generarFlecha($pasoId);

            // independiente si es nuevo o existe valido el check enviarHistorial
            $activarEnvioHistorial = isset($_POST['enviarHistorial']) ? $_POST['enviarHistorial'] : 0;

            if($activarEnvioHistorial == 1){
                // Valido si la bola de enviar historial esta creada o no, si es asi no hago nada

                $sqlPasoEnvioHistorial = "SELECT id, dentro_horario_accion, dentro_horario_detalle_accion, fuera_horario_accion, fuera_horario_detalle_accion, id_estpas_envio_historial FROM dyalogo_canales_electronicos.dy_chat_configuracion WHERE id_estpas = {$pasoId} LIMIT 1";
                $res = $mysqli->query($sqlPasoEnvioHistorial);
                
                if($res->num_rows > 0){
                    $data = $res->fetch_object();

                    if($data->id_estpas_envio_historial == 0){
                        
                        // Busco el paso del chat
                        $sql = "SELECT * FROM {$BaseDatos_systema}.ESTPAS WHERE ESTPAS_ConsInte__b = {$pasoId} LIMIT 1";
                        $resEstpas = $mysqli->query($sql);

                        $dataEstpas = $resEstpas->fetch_object();

                        $loc = explode(" ", $dataEstpas->ESTPAS_Loc______b);
                        $locX = $loc[0];
                        $locY = $loc[1] + 86;

                        // Creo el paso que sea de tipo email
                        $sqlPas = "INSERT INTO {$BaseDatos_systema}.ESTPAS (ESTPAS_ConsInte__ESTRAT_b, ESTPAS_Nombre__b, ESTPAS_Tipo______b, ESTPAS_Loc______b, ESTPAS_Comentari_b, ESTPAS_activo) 
                            VALUES (".$dataEstpas->ESTPAS_ConsInte__ESTRAT_b.", 'salMail', 7, '{$locX} {$locY}', 'Historial del chat {$pasoId}', '-1')";

                        if($mysqli->query($sqlPas) === true){
                            $pasoEmail = $mysqli->insert_id;

                            $updatePas = "UPDATE {$BaseDatos_systema}.ESTPAS SET ESTPAS_key_____b = '{$pasoEmail}' WHERE ESTPAS_ConsInte__b = {$pasoEmail}";
                            $mysqli->query($updatePas);

                            // luego debo crear la muestra para el paso
                            crearMuestra($poblacion, $pasoEmail);

                            // Creo la configuracion de la bola del email
                            insertoConfiguracionCorreoSaliente($pasoEmail, $pasoId, $huespedId, $poblacion);
                            
                            // Luego creo la flecha que va desde el paso a la campaña u bot 
                            generarFlechaHistorial($data->dentro_horario_accion, $data->dentro_horario_detalle_accion, $data->fuera_horario_accion, $data->fuera_horario_detalle_accion, $pasoEmail, $dataEstpas->ESTPAS_ConsInte__ESTRAT_b, $poblacion);
                            
                            // Asigno el paso a la configuracion del chat
                            $sqlActualizarPaso = "UPDATE {$dyalogo_canales_electronicos}.dy_chat_configuracion SET id_estpas_envio_historial = {$pasoEmail} WHERE id_estpas = {$pasoId}";
                            $mysqli->query($sqlActualizarPaso);
                        }

                    }
                }

            }

            // Borro el paso asociado y el id
            if($activarEnvioHistorial == 0){

                $sqlPasoEnvioHistorial = "SELECT id, nombre, id_estpas_envio_historial FROM dyalogo_canales_electronicos.dy_chat_configuracion WHERE id_estpas = {$pasoId} LIMIT 1";
                $res = $mysqli->query($sqlPasoEnvioHistorial);
                
                if($res->num_rows > 0){
                    $data = $res->fetch_object();

                    // Valido que no haya paso creado de lo contrario realizo el proceso de borrado
                    if($data->id_estpas_envio_historial != 0){

                        // Primero elimino la flecha
                        $sqlEstcon = "DELETE FROM DYALOGOCRM_SISTEMA.ESTCON WHERE ESTCON_ConsInte__ESTPAS_Des_b = {$data->id_estpas_envio_historial} OR ESTCON_ConsInte__ESTPAS_Has_b = {$data->id_estpas_envio_historial}";
                        $mysqli->query($sqlEstcon);

                        // Elimino la configuracion del email
                        $sqlEmail = "DELETE FROM DYALOGOCRM_SISTEMA.CORREO_SALIENTE WHERE CORREO_SALIENTE_ConsInte__ESTPAS_b = {$data->id_estpas_envio_historial}";
                        $mysqli->query($sqlEmail);

                        // Elimino la bola
                        $sqlPaso = "DELETE FROM DYALOGOCRM_SISTEMA.ESTPAS WHERE ESTPAS_ConsInte__b = {$data->id_estpas_envio_historial}";
                        $mysqli->query($sqlPaso);

                        // actualizo la configuracion del chat
                        $sqlActualizarPaso = "UPDATE {$dyalogo_canales_electronicos}.dy_chat_configuracion SET id_estpas_envio_historial = 0 WHERE id_estpas = {$pasoId}";
                        $mysqli->query($sqlActualizarPaso);
                    }
                }
            }


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

            echo json_encode(["estado" => "registrado"]);
        }
    }

    // Aqui se guardan los campos para un formulario web
    function insertarCampos($campos, $formulario){
        global $mysqli;
        global $BaseDatos_general;

        $campos = stripslashes($campos);
        $data = json_decode($campos);
        
        $arrSeleccionados = [];
        for ($i=0; $i < count($data); $i++) { 
            $arrSeleccionados[$i] = $data[$i][0];
        }

        $Lsql = "SELECT id, id_pregun FROM ".$BaseDatos_general.".dy_preguntas WHERE id_formulario = ".$formulario;
        $res = $mysqli->query($Lsql);
        
        // Eliminar los que ya no estan 
        $arrActual = [];
        while($key = $res->fetch_object()){
            if(!in_array($key->id_pregun, $arrSeleccionados)){
                $Dsql = "DELETE FROM ".$BaseDatos_general.".dy_preguntas WHERE id = ".$key->id;
                $mysqli->query($Dsql);
            }else{
                $arrActual[] = $key->id_pregun;
            }
        }
        // Agregar los nuevos
        for ($i=0; $i < count($data); $i++) {
            
            $tipo = $data[$i][4];

            // Valido el tipo que sea input text
            if($data[$i][4] == 1 || $data[$i][4] == 3 || $data[$i][4] == 14){
                $tipo = 1;
            }

            // Valido que el input sea check
            if($data[$i][4] == 8){
                $tipo = 3;
            }

            if(!in_array($data[$i][0], $arrActual)){
                $Isql = "INSERT INTO ".$BaseDatos_general.".dy_preguntas (id_formulario, pregunta, tipo, es_requerida, id_pregun) VALUES ('".$formulario."','".$data[$i][1]."','".$tipo."','".$data[$i][3]."','".$data[$i][0]."')";
                //echo $Isql;
                $mysqli->query($Isql);
            }else{
                // Si esta en el array significa que existe entonces lo que tengo que hacer es actualizarlo
                $update = "UPDATE {$BaseDatos_general}.dy_preguntas SET pregunta = '{$data[$i][1]}', tipo = '{$tipo}', es_requerida = '{$data[$i][3]}' WHERE id_formulario = {$formulario} AND id_pregun = {$data[$i][0]}";
                $mysqli->query($update);
            }
            
        }
    }

    // Aqui van los horarios
    function insertarHorarios($id_chat, $tipo){

        if(isset($_POST['G10_C108']) && $_POST['G10_C108'] == -1){
            $dia_inicial = 1;
            $momento_incial = $_POST['G10_C109'];
            $momento_final = $_POST['G10_C110'];

            saveHorario($tipo, $id_chat, $dia_inicial, $momento_incial, $momento_final);
        }else{
            deleteHorario($id_chat, 1);
        }

        if(isset($_POST['G10_C111']) && $_POST['G10_C111'] == -1){
            $dia_inicial = 2;
            $momento_incial = $_POST['G10_C112'];
            $momento_final = $_POST['G10_C113'];

            saveHorario($tipo, $id_chat, $dia_inicial, $momento_incial, $momento_final);
        }else{
            deleteHorario($id_chat, 2);
        }

        if(isset($_POST['G10_C114']) && $_POST['G10_C114'] == -1){
            $dia_inicial = 3;
            $momento_incial = $_POST['G10_C115'];
            $momento_final = $_POST['G10_C116'];
            
            saveHorario($tipo, $id_chat, $dia_inicial, $momento_incial, $momento_final);
        }else{
            deleteHorario($id_chat, 3);
        }

        if(isset($_POST['G10_C117']) && $_POST['G10_C117'] == -1){
            $dia_inicial = 4;
            $momento_incial = $_POST['G10_C118'];
            $momento_final = $_POST['G10_C119'];
            
            saveHorario($tipo, $id_chat, $dia_inicial, $momento_incial, $momento_final);
        }else{
            deleteHorario($id_chat, 4);
        }

        if(isset($_POST['G10_C120']) && $_POST['G10_C120'] == -1){
            $dia_inicial = 5;
            $momento_incial = $_POST['G10_C121'];
            $momento_final = $_POST['G10_C122'];
            
            saveHorario($tipo, $id_chat, $dia_inicial, $momento_incial, $momento_final);
        }else{
            deleteHorario($id_chat, 5);
        }

        if(isset($_POST['G10_C123']) && $_POST['G10_C123'] == -1){
            $dia_inicial = 6;
            $momento_incial = $_POST['G10_C124'];
            $momento_final = $_POST['G10_C125'];
            
            saveHorario($tipo, $id_chat, $dia_inicial, $momento_incial, $momento_final);
        }else{
            deleteHorario($id_chat, 6);
        }

        if(isset($_POST['G10_C126']) && $_POST['G10_C126'] == -1){
            $dia_inicial = 7;
            $momento_incial = $_POST['G10_C127'];
            $momento_final = $_POST['G10_C128'];
            
            saveHorario($tipo, $id_chat, $dia_inicial, $momento_incial, $momento_final);
        }else{
            deleteHorario($id_chat, 7);
        }

    }

    function saveHorario($tipo, $id_chat, $dia_inicial, $momento_incial, $momento_final){
        global $mysqli;
        global $dyalogo_canales_electronicos;

        $lsql = "SELECT * FROM ".$dyalogo_canales_electronicos.".dy_chat_horarios WHERE id_configuracion = ".$id_chat." AND dia_inicial = ".$dia_inicial;
        $res = $mysqli->query($lsql);

        if($res->num_rows > 0){
            $sqlHorario = "UPDATE ".$dyalogo_canales_electronicos.".dy_chat_horarios SET momento_inicial = '".$momento_incial."', momento_final = '".$momento_final."' WHERE id_configuracion = ".$id_chat." AND dia_inicial = ".$dia_inicial;
        }else{
            $sqlHorario = "INSERT INTO ".$dyalogo_canales_electronicos.".dy_chat_horarios (id_configuracion, momento_inicial, momento_final, dia_inicial, dia_final) VALUES ('".$id_chat."','".$momento_incial."','".$momento_final."','".$dia_inicial."','".$dia_inicial."')";
        }            

        //echo $sqlHorario;
        $mysqli->query($sqlHorario);        
    }

    function deleteHorario($id_chat, $dia_inicial){
        global $mysqli;
        global $dyalogo_canales_electronicos;

        $Lsql = "DELETE FROM ".$dyalogo_canales_electronicos.".dy_chat_horarios WHERE id_configuracion = ".$id_chat." AND dia_inicial = ".$dia_inicial;
        $mysqli->query($Lsql);
    }

    // Esta funcion se encarga de guardar el logo
    function storeLogoChat($file, $pasoId){
        global $mysqli;
        global $dyalogo_canales_electronicos;
        
        $Lsql = "SELECT id FROM ".$dyalogo_canales_electronicos.".dy_chat_configuracion WHERE id_estpas = ".$pasoId." AND integrado_con = 'web'";

        $res = $mysqli->query($Lsql);
        if($res->num_rows > 0){
            $datos = $res->fetch_array();
            
            if(isset($file['tmp_name']) && !empty($file['tmp_name'])){
                $tmp = explode('.', basename($file['name']));
                $extension = end($tmp);
                
                $name = $datos['id'].".".$extension;
                
                $path = "/Dyalogo/img_chat/".$name;
                $path = str_replace(' ', '', $path);
    
                if (file_exists($path)) {
                    unlink($path);
                }

                if (move_uploaded_file($file['tmp_name'], $path)){
                    //echo "El archivo ha sido cargado correctamente.";
                    $usql = "UPDATE ".$dyalogo_canales_electronicos.".dy_chat_configuracion SET ruta_logo = '".$path."' WHERE id = ".$datos['id'];
                    $mysqli->query($usql);
                }else{
                    //echo "Ocurrió algún error al subir el fichero. No pudo guardarse.";
                }
            }
        }

    }

    function generarFlecha($paso_id){
        global $mysqli;

        // Trae las opciones de chat configuracion que sean 1 2 o 3
        $sql = "SELECT dentro_horario_accion, dentro_horario_detalle_accion, fuera_horario_accion, fuera_horario_detalle_accion FROM dyalogo_canales_electronicos.dy_chat_configuracion WHERE id_estpas = {$paso_id}";
        $res = $mysqli->query($sql);

        if($res && $res->num_rows > 0){

            $row = $res->fetch_object();

            $generarFlecha = new GeneradorDeFlechas;

            // Con horario
            $idPasoEnd = 0;
            $idEstrategia = 0;

            // Busco el id del paso de la bola destino
            if($row->dentro_horario_accion == 1){
                $sqlPasoEnd = "SELECT ESTPAS_ConsInte__b AS id, ESTPAS_ConsInte__ESTRAT_b AS estrategia FROM DYALOGOCRM_SISTEMA.ESTPAS INNER JOIN DYALOGOCRM_SISTEMA.CAMPAN ON ESTPAS_ConsInte__CAMPAN_b = CAMPAN_ConsInte__b WHERE ESTPAS_Tipo______b = 1 AND CAMPAN_IdCamCbx__b = ".$row->dentro_horario_detalle_accion;
                $resPasoEnd = $mysqli->query($sqlPasoEnd);
                
                if($resPasoEnd && $resPasoEnd->num_rows > 0){
                    $pasoEnd = $resPasoEnd->fetch_object();
                    $idPasoEnd = $pasoEnd->id;
                    $idEstrategia = $pasoEnd->estrategia;
                }
            }else if($row->dentro_horario_accion == 2){
                $sqlPasoEnd = "SELECT ESTPAS_ConsInte__b AS id, ESTPAS_ConsInte__ESTRAT_b AS estrategia FROM DYALOGOCRM_SISTEMA.ESTPAS INNER JOIN dyalogo_canales_electronicos.dy_base_autorespuestas ON ESTPAS_ConsInte__b = id_estpas WHERE id = ".$row->dentro_horario_detalle_accion;
                $resPasoEnd = $mysqli->query($sqlPasoEnd);
                
                if($resPasoEnd && $resPasoEnd->num_rows > 0){
                    $pasoEnd = $resPasoEnd->fetch_object();
                    $idPasoEnd = $pasoEnd->id;
                    $idEstrategia = $pasoEnd->estrategia;
                }
            }else if($row->dentro_horario_accion == 3){
                $sqlPasoEnd = "SELECT ESTPAS_ConsInte__b AS id, ESTPAS_ConsInte__ESTRAT_b AS estrategia FROM DYALOGOCRM_SISTEMA.ESTPAS WHERE ESTPAS_ConsInte__b = {$row->dentro_horario_detalle_accion}";
                $resPasoEnd = $mysqli->query($sqlPasoEnd);
                if($resPasoEnd && $resPasoEnd->num_rows > 0){
                    $pasoEnd = $resPasoEnd->fetch_object();
                    $idPasoEnd = $pasoEnd->id;
                    $idEstrategia = $pasoEnd->estrategia;
                }
            }

            // Si hay un paso final validar si existe en estcon
            if($idPasoEnd != 0){
                $sqlFlechas = "SELECT * FROM DYALOGOCRM_SISTEMA.ESTCON WHERE ESTCON_ConsInte__ESTPAS_Des_b = {$paso_id} AND ESTCON_ConsInte__ESTPAS_Has_b = $idPasoEnd";
                $resFlechas = $mysqli->query($sqlFlechas);
                if($resFlechas && $resFlechas->num_rows > 0){
                    //
                }else{
                    $puertoFrom = $generarFlecha->generarPuerto($paso_id, $idPasoEnd, 'flujograma');
                    $puertoTo = $generarFlecha->generarPuerto($idPasoEnd, $paso_id, 'flujograma');
                    $insertFlecha = "INSERT INTO DYALOGOCRM_SISTEMA.ESTCON (ESTCON_Nombre____b, ESTCON_ConsInte__ESTPAS_Des_b, ESTCON_ConsInte__ESTPAS_Has_b, ESTCON_FromPort_b, ESTCON_ToPort_b, ESTCON_ConsInte__ESTRAT_b, ESTCON_Tipo_Consulta_b, ESTCON_Tipo_Insercion_b,ESTCON_ConsInte_PREGUN_Fecha_b,ESTCON_ConsInte_PREGUN_Hora_b,ESTCON_Operacion_Fecha_b,ESTCON_Operacion_Hora_b,ESTCON_Cantidad_Fecha_b,ESTCON_Cantidad_Hora_b,ESTCON_Estado_cambio_b,ESTCON_Sacar_paso_anterior_b,ESTCON_resucitar_registro, ESTCON_Deshabilitado_b)VALUES ('conector', {$paso_id}, {$idPasoEnd}, '{$puertoFrom}', '{$puertoTo}', {$idEstrategia} , 1,0,-1,-1,1,1,0,0,0,0,0,-1)";
                    $insert = $mysqli->query($insertFlecha);
                }
            }


            // Sin horario
            $idPasoEnd = 0;
            $idEstrategia = 0;
            
            // Busco el id del paso de la bola destino
            if($row->fuera_horario_accion == 1){
                $sqlPasoEnd = "SELECT ESTPAS_ConsInte__b AS id, ESTPAS_ConsInte__ESTRAT_b AS estrategia FROM DYALOGOCRM_SISTEMA.ESTPAS INNER JOIN DYALOGOCRM_SISTEMA.CAMPAN ON ESTPAS_ConsInte__CAMPAN_b = CAMPAN_ConsInte__b WHERE ESTPAS_Tipo______b = 1 AND CAMPAN_IdCamCbx__b = ".$row->fuera_horario_detalle_accion;
                $resPasoEnd = $mysqli->query($sqlPasoEnd);
                
                if($resPasoEnd && $resPasoEnd->num_rows > 0){
                    $pasoEnd = $resPasoEnd->fetch_object();
                    $idPasoEnd = $pasoEnd->id;
                    $idEstrategia = $pasoEnd->estrategia;
                }
            }else if($row->fuera_horario_accion == 2){
                $sqlPasoEnd = "SELECT ESTPAS_ConsInte__b AS id, ESTPAS_ConsInte__ESTRAT_b AS estrategia FROM DYALOGOCRM_SISTEMA.ESTPAS INNER JOIN dyalogo_canales_electronicos.dy_base_autorespuestas ON ESTPAS_ConsInte__b = id_estpas WHERE id = ".$row->fuera_horario_detalle_accion;
                $resPasoEnd = $mysqli->query($sqlPasoEnd);
                
                if($resPasoEnd && $resPasoEnd->num_rows > 0){
                    $pasoEnd = $resPasoEnd->fetch_object();
                    $idPasoEnd = $pasoEnd->id;
                    $idEstrategia = $pasoEnd->estrategia;
                }
            }else if($row->fuera_horario_accion == 3){
                $sqlPasoEnd = "SELECT ESTPAS_ConsInte__b AS id, ESTPAS_ConsInte__ESTRAT_b AS estrategia FROM DYALOGOCRM_SISTEMA.ESTPAS WHERE ESTPAS_ConsInte__b = {$row->fuera_horario_detalle_accion}";
                $resPasoEnd = $mysqli->query($sqlPasoEnd);
                if($resPasoEnd && $resPasoEnd->num_rows > 0){
                    $pasoEnd = $resPasoEnd->fetch_object();
                    $idPasoEnd = $pasoEnd->id;
                    $idEstrategia = $pasoEnd->estrategia;
                }
            }

            // Si hay un paso final validar si existe en estcon
            if($idPasoEnd != 0){
                $sqlFlechas = "SELECT * FROM DYALOGOCRM_SISTEMA.ESTCON WHERE ESTCON_ConsInte__ESTPAS_Des_b = {$paso_id} AND ESTCON_ConsInte__ESTPAS_Has_b = $idPasoEnd";
                $resFlechas = $mysqli->query($sqlFlechas);
                if($resFlechas && $resFlechas->num_rows > 0){
                    //
                }else{
                    $puertoFrom = $generarFlecha->generarPuerto($paso_id, $idPasoEnd, 'flujograma');
                    $puertoTo = $generarFlecha->generarPuerto($idPasoEnd, $paso_id, 'flujograma');
                    $insertFlecha = "INSERT INTO DYALOGOCRM_SISTEMA.ESTCON (ESTCON_Nombre____b, ESTCON_ConsInte__ESTPAS_Des_b, ESTCON_ConsInte__ESTPAS_Has_b, ESTCON_FromPort_b, ESTCON_ToPort_b, ESTCON_ConsInte__ESTRAT_b, ESTCON_Tipo_Consulta_b, ESTCON_Tipo_Insercion_b,ESTCON_ConsInte_PREGUN_Fecha_b,ESTCON_ConsInte_PREGUN_Hora_b,ESTCON_Operacion_Fecha_b,ESTCON_Operacion_Hora_b,ESTCON_Cantidad_Fecha_b,ESTCON_Cantidad_Hora_b,ESTCON_Estado_cambio_b,ESTCON_Sacar_paso_anterior_b,ESTCON_resucitar_registro, ESTCON_Deshabilitado_b) VALUES ('conector', {$paso_id}, {$idPasoEnd}, '{$puertoFrom}', '{$puertoTo}', {$idEstrategia} , 1,0,-1,-1,1,1,0,0,0,0,0,-1)";
                    $insert = $mysqli->query($insertFlecha);
                }
            }

    
        }
    }

    function limpiarFlechas($paso_id){
        global $mysqli;
        global $BaseDatos_systema;
        $sql = "DELETE FROM {$BaseDatos_systema}.ESTCON WHERE ESTCON_ConsInte__ESTPAS_Des_b = {$paso_id}";
        $mysqli->query($sql);
    }

    function generarLinkWebform($pasoId){
        global $mysqli;
        global $BaseDatos_systema;

        $sql = "SELECT ESTPAS_ConsInte__b AS paso_id, ESTPAS_Comentari_b AS nombre, ESTRAT_ConsInte_GUION_Pob AS poblacion, WEBFORM_ConsInte__b AS web_id FROM {$BaseDatos_systema}.ESTPAS INNER JOIN {$BaseDatos_systema}.ESTRAT ON ESTPAS_ConsInte__ESTRAT_b = ESTRAT_ConsInte__b INNER JOIN DYALOGOCRM_SISTEMA.WEBFORM ON ESTPAS_ConsInte__b = WEBFORM_ConsInte__ESTPAS_b WHERE ESTPAS_ConsInte__b = {$pasoId}";
        $res = $mysqli->query($sql);

        if($res->num_rows > 0){
            $row = $res->fetch_object();

            $origen = str_replace(' ', '_', $row->nombre);

            $cod_web = base64_encode($row->poblacion.'_'.$row->web_id);

            $url = "https://{$_SERVER['HTTP_HOST']}/crm_php/web_forms.php?web2={$cod_web}&paso={$row->paso_id}&origen=WF_{$origen}";

            return $url;

        }else{
            return ' ';
        }
    }

    function agregarConfiguracionCampana($configuracionId, $titulo, $accionDetalle){
        global $mysqli;
        global $dyalogo_canales_electronicos;
        global $BaseDatos_systema;

        $campanaId = 0;

        // Validamos si accionDetalle es null, si lo es traemos un id campana de cualquier lugar
        if(is_null($accionDetalle)){

            // Traigo una campaña de la misma estrategia
            $sql = "SELECT e.CAMPAN_ConsInte__b AS identificador, e.CAMPAN_IdCamCbx__b AS campanId FROM DYALOGOCRM_SISTEMA.ESTPAS c
                INNER JOIN (
                    SELECT b.ESTPAS_ConsInte__b AS id, b.ESTPAS_ConsInte__ESTRAT_b AS estrat from dyalogo_canales_electronicos.dy_chat_configuracion a
                    INNER JOIN DYALOGOCRM_SISTEMA.ESTPAS b ON a.id_estpas = b.ESTPAS_ConsInte__b
                    WHERE id = {$configuracionId} LIMIT 1
                    ) AS d ON d.estrat = c.ESTPAS_ConsInte__ESTRAT_b
                INNER JOIN DYALOGOCRM_SISTEMA.CAMPAN e ON e.CAMPAN_ConsInte__b = c.ESTPAS_ConsInte__CAMPAN_b
            WHERE c.ESTPAS_Tipo______b = 1 AND ESTPAS_ConsInte__CAMPAN_b IS NOT NULL LIMIT 1
            ";

            $res1 = $mysqli->query($sql);

            if($res1 && $res1->num_rows > 0){
                $dataCampan = $res1->fetch_object();
                $campanaId = $dataCampan->campanId;
            }else{
                // Entonces si no hay ninguna campaña la traigo de donde sea
                $sql = "SELECT CAMPAN_ConsInte__b AS id, CAMPAN_IdCamCbx__b AS campanaId FROM {$BaseDatos_systema}.CAMPAN WHERE CAMPAN_IdCamCbx__b IS NOT NULL LIMIT 1";
                $res = $mysqli->query($sql);
                if($res && $res->num_rows > 0){
                    $dataCampana = $res->fetch_object();
                    $campanaId = $dataCampana->campanaId;
                }
            }

        }else{

            $campan = "SELECT CAMPAN_ConsInte__b AS id FROM {$BaseDatos_systema}.CAMPAN WHERE CAMPAN_IdCamCbx__b = {$accionDetalle} LIMIT 1";
            $data = $mysqli->query($campan);
            
            if($data->num_rows > 0){
                $row = $data->fetch_object();
    
                $sql = "UPDATE {$dyalogo_canales_electronicos}.dy_chat_configuracion SET id_campana_crm = {$row->id} WHERE id = {$configuracionId}";
                $mysqli->query($sql);
            }

            // Reasigno el id de camapana
            $campanaId = $accionDetalle;
        }

        // Valido si existe opcion_accion
        $opcionAccion = "SELECT * FROM {$dyalogo_canales_electronicos}.dy_chat_opciones_accion WHERE id_chat_cfg = {$configuracionId} LIMIT 1";
        $data = $mysqli->query($opcionAccion);
        
        if($data->num_rows == 0){
            // Guardo la campana opcion_accion si no existe
            $accionCampana = "INSERT INTO dyalogo_canales_electronicos.dy_chat_opciones_accion (id_chat_cfg, opcion, accion, id_campana) VALUES 
                (".$configuracionId.", '".$titulo."', 1, ".$campanaId.")";
                
            $mysqli->query($accionCampana);

        }else if(!is_null($accionDetalle)){
            // Si existe una configuracion pero el accion detalle no es null actualizaria este dato
            $sql = "UPDATE {$dyalogo_canales_electronicos}.dy_chat_opciones_accion SET id_campana = {$campanaId} WHERE id_chat_cfg = {$configuracionId}";
            $mysqli->query($sql);
        }

    }

    function agregarConfiguracionBot($id_chat, $accionDetalle){
        global $mysqli;
        global $dyalogo_canales_electronicos;

        $accionBot = "UPDATE ".$dyalogo_canales_electronicos.".dy_chat_configuracion SET id_base_autorespuestas = ".$accionDetalle." WHERE id = ".$id_chat;
        $mysqli->query($accionBot);

        // Agregamos a la configuracion del bot el campo que sera el ani

        // Primero buscamos el id_bot a insertar
        $sql = "SELECT id, id_bot FROM {$dyalogo_canales_electronicos}.dy_base_autorespuestas WHERE id = {$accionDetalle}";
        $a_res = $mysqli->query($sql);

        // si encuentra el bot continuamos
        if($a_res && $a_res->num_rows > 0){

            $autoRes = $a_res->fetch_object();

            // Valido que exista la llave a insertar
            if(isset($_POST['campoBusquedaWeb']) && $_POST['campoBusquedaWeb'] != '' && $_POST['campoBusquedaWeb'] != 0){

                // Actualizamos la tabla bot con el ani
                $upd = "UPDATE {$dyalogo_canales_electronicos}.dy_bot SET id_pregun_dato_chat_web = " . $_POST['campoBusquedaWeb'] . " WHERE id = " . $autoRes->id_bot;
                $mysqli->query($upd);
            }

        }
    }

    function crearMuestra($id_Guion, $pasoId){
        
        global $mysqli;
        global $BaseDatos_systema;
        global $BaseDatos;

        $Lsql = "INSERT INTO ".$BaseDatos_systema.".MUESTR (MUESTR_Nombre____b, MUESTR_ConsInte__GUION__b) VALUES ('".$id_Guion."_MUESTRA_".rand()."', '".$id_Guion."')";

        if($mysqli->query($Lsql) === true){

            $id_Muestras = $mysqli->insert_id;

            $CreateMuestraLsql = "CREATE TABLE `".$BaseDatos."`.`G".$id_Guion."_M".$id_Muestras."` (
                `G".$id_Guion."_M".$id_Muestras."_CoInMiPo__b` int(10) NOT NULL,
                `G".$id_Guion."_M".$id_Muestras."_ConIntUsu_b` int(10) DEFAULT NULL,
                `G".$id_Guion."_M".$id_Muestras."_Activo____b` smallint(5) DEFAULT '-1',
                `G".$id_Guion."_M".$id_Muestras."_FechaCreacion_b` datetime DEFAULT NULL,
                `G".$id_Guion."_M".$id_Muestras."_FechaAsignacion_b` datetime DEFAULT NULL,
                `G".$id_Guion."_M".$id_Muestras."_FechaReactivacion_b` datetime DEFAULT NULL,
                `G".$id_Guion."_M".$id_Muestras."_FecHorMinProGes__b` datetime DEFAULT NULL,
                `G".$id_Guion."_M".$id_Muestras."_UltiGest__b` int(10) DEFAULT NULL,
                `G".$id_Guion."_M".$id_Muestras."_GesMasImp_b` int(10) DEFAULT NULL,
                `G".$id_Guion."_M".$id_Muestras."_FecUltGes_b` datetime DEFAULT NULL,
                `G".$id_Guion."_M".$id_Muestras."_FeGeMaIm__b` datetime DEFAULT NULL,
                `G".$id_Guion."_M".$id_Muestras."_Estado____b` int(10) DEFAULT '0',
                `G".$id_Guion."_M".$id_Muestras."_TipoReintentoGMI_b` smallint(5) DEFAULT '0',
                `G".$id_Guion."_M".$id_Muestras."_FecHorAge_b` datetime DEFAULT NULL,
                `G".$id_Guion."_M".$id_Muestras."_FecHorAgeGMI_b` datetime DEFAULT NULL,
                `G".$id_Guion."_M".$id_Muestras."_ConUltGes_b` int(10) DEFAULT NULL,
                `G".$id_Guion."_M".$id_Muestras."_CoGesMaIm_b` int(10) DEFAULT NULL,
                `G".$id_Guion."_M".$id_Muestras."_EstadoUG_b`  int(10) DEFAULT NULL,
                `G".$id_Guion."_M".$id_Muestras."_EstadoGMI_b` int(10) DEFAULT NULL,
                `G".$id_Guion."_M".$id_Muestras."_UsuarioUG_b` int(10) DEFAULT NULL,
                `G".$id_Guion."_M".$id_Muestras."_UsuarioGMI_b` int(10) DEFAULT NULL,
                `G".$id_Guion."_M".$id_Muestras."_CanalUG_b` varchar(20) DEFAULT NULL,
                `G".$id_Guion."_M".$id_Muestras."_CanalGMI_b` varchar(20) DEFAULT NULL,
                `G".$id_Guion."_M".$id_Muestras."_SentidoUG_b` varchar(10) DEFAULT NULL,
                `G".$id_Guion."_M".$id_Muestras."_SentidoGMI_b` varchar(10) DEFAULT NULL,
                `G".$id_Guion."_M".$id_Muestras."_NumeInte__b` int(10) DEFAULT '0',
                `G".$id_Guion."_M".$id_Muestras."_CantidadIntentosGMI_b` int(10) DEFAULT '0',
                `G".$id_Guion."_M".$id_Muestras."_Comentari_b` longtext DEFAULT NULL,
                `G".$id_Guion."_M".$id_Muestras."_ComentarioGMI_b` longtext DEFAULT NULL,
                `G".$id_Guion."_M".$id_Muestras."_LinkContenidoUG_b` varchar(500) DEFAULT NULL,
                `G".$id_Guion."_M".$id_Muestras."_LinkContenidoGMI_b` varchar(500) DEFAULT NULL,
                `G".$id_Guion."_M".$id_Muestras."_DetalleCanalUG_b` varchar(255) DEFAULT NULL,
                `G".$id_Guion."_M".$id_Muestras."_DetalleCanalGMI_b` varchar(255) DEFAULT NULL,
                `G".$id_Guion."_M".$id_Muestras."_DatoContactoUG_b` varchar(255) DEFAULT NULL,
                `G".$id_Guion."_M".$id_Muestras."_DatoContactoGMI_b` varchar(255) DEFAULT NULL,

                
                `G".$id_Guion."_M".$id_Muestras."_TienGest__b` varchar(253) DEFAULT NULL,
                `G".$id_Guion."_M".$id_Muestras."_MailEnvi__b` smallint(5) DEFAULT NULL, 
                `G".$id_Guion."_M".$id_Muestras."_GruRegRel_b` int(10) DEFAULT NULL, 
                `G".$id_Guion."_M".$id_Muestras."_EfeUltGes_b` smallint(5) DEFAULT NULL,
                `G".$id_Guion."_M".$id_Muestras."_EfGeMaIm__b` smallint(5) DEFAULT NULL,                                         
                

                PRIMARY KEY (`G".$id_Guion."_M".$id_Muestras."_CoInMiPo__b`),
                KEY `G".$id_Guion."_M".$id_Muestras."_Estado____b_Indice` (`G".$id_Guion."_M".$id_Muestras."_Estado____b`),
                KEY `G".$id_Guion."_M".$id_Muestras."_ConIntUsu_b_Indice` (`G".$id_Guion."_M".$id_Muestras."_ConIntUsu_b`)
            ) ENGINE=InnoDB DEFAULT CHARSET=latin1;";

            if($mysqli->query($CreateMuestraLsql) === true){
                $PasoLsql = "UPDATE ".$BaseDatos_systema.".ESTPAS SET ESTPAS_ConsInte__MUESTR_b = ".$id_Muestras." WHERE ESTPAS_ConsInte__b = ".$pasoId;
                $mysqli->query($PasoLsql);
            }else{
                echo $mysqli->error;
            }

        }else{
            echo "No guardo la muestra => ".$mysqli->error;
        }
    }

    function insertoConfiguracionCorreoSaliente($pasoId, $pasoChat, $huespedId, $poblacion){

        global $mysqli;
        global $BaseDatos_systema;
        global $dyalogo_canales_electronicos;

        // Busco una cuenta de email para usar por defecto
        $sqlEmail = "SELECT id, nombre FROM {$dyalogo_canales_electronicos}.dy_ce_configuracion WHERE id_huesped = {$huespedId} LIMIT 1";
        $resEmail = $mysqli->query($sqlEmail);
        if($resEmail->num_rows > 0){
            $dataEmail = $resEmail->fetch_object();
            $emailId = $dataEmail->id;
        }else{
            $emailId = 0;
        }

        // Busco un campo a cual enviar por defecto buscare que sea email o correo
        $sqlPregun = "SELECT PREGUN_ConsInte__b AS id, PREGUN_Texto_____b AS nombre FROM {$BaseDatos_systema}.PREGUN WHERE PREGUN_ConsInte__GUION__b = {$poblacion} AND (PREGUN_Texto_____b LIKE '%correo%' OR PREGUN_Texto_____b LIKE '%email%') LIMIT 1";
        $resPregun = $mysqli->query($sqlPregun);
        if($resPregun->num_rows > 0){
            $dataPregun = $resPregun->fetch_object();

            $para = '${G'.$poblacion.'_C'.$dataPregun->id.'}';
        }else{
            $para = '';
        }

        $asunto = "Link de conversación del historial de chat";
        $cuerpo = '<p>En el siguiente link podr&aacute; descargar el historial del chat ${LinkContenidoUG} ${LinkContenidoGMI} </p>';

        $sql = "INSERT INTO {$BaseDatos_systema}.CORREO_SALIENTE (CORREO_SALIENTE_ConsInte__ESTPAS_b, CORREO_SALIENTE_Nombre_b, CORREO_SALIENTE_CuentaFijaOAgente_b, 
            CORREO_SALIENTE_CuentaAUsar_b, CORREO_SALIENTE_Para_b, CORREO_SALIENTE_CC_b, CORREO_SALIENTE_CCO_b, CORREO_SALIENTE_Asunto_b, CORREO_SALIENTE_Cuerpo_b) 
        VALUES ({$pasoId}, 'Historial del chat {$pasoChat}', 0, {$emailId}, '{$para}', '', '', '{$asunto}', '{$cuerpo}')";

        if($mysqli->query($sql) === true){
            // 
        }else{
            echo $mysqli->error;
        }
        
    }

    function generarFlechaHistorial($dentroHorarioAccion, $dentroHorarioDetalle, $fueraDeHorarioAccion, $fueraDeHorarioDetalle, $pasoHas, $estrategia, $poblacion){

        global $mysqli;
        global $BaseDatos_systema;
        global $dyalogo_canales_electronicos;

        // Traigo el paso dentro de horario
        if($dentroHorarioAccion == 1){

            $sql = "SELECT ESTPAS_ConsInte__b AS id, ESTPAS_Comentari_b AS nombre, CAMPAN_ConsInte__MUESTR_b AS muestra FROM DYALOGOCRM_SISTEMA.ESTPAS 
            JOIN DYALOGOCRM_SISTEMA.CAMPAN ON ESTPAS_ConsInte__CAMPAN_b = CAMPAN_ConsInte__b WHERE ESTPAS_Tipo______b = 1 AND CAMPAN_IdCamCbx__b = {$dentroHorarioDetalle} LIMIT 1";

        }else{
            $sql = "SELECT a.ESTPAS_ConsInte__b AS id, a.ESTPAS_Comentari_b AS nombre FROM DYALOGOCRM_SISTEMA.ESTPAS a
            JOIN dyalogo_canales_electronicos.dy_base_autorespuestas b ON a.ESTPAS_ConsInte__b = b.id_estpas WHERE b.id = {$dentroHorarioDetalle} LIMIT 1";
        }

        $res = $mysqli->query($sql);
        $data = $res->fetch_object();
        $pasoDes = $data->id;

        $generarFlecha = new GeneradorDeFlechas;

        $puertoFrom = $generarFlecha->generarPuerto($pasoDes, $pasoHas, 'flujograma');
        $puertoTo = $generarFlecha->generarPuerto($pasoHas, $pasoDes, 'flujograma');

        if($dentroHorarioAccion == 1){
            $muestra = $data->muestra;
            $consulta = "SELECT G".$poblacion."_ConsInte__b as id FROM DYALOGOCRM_WEB.G".$poblacion." LEFT JOIN DYALOGOCRM_WEB.G".$poblacion."_M".$muestra." ON G".$poblacion."_ConsInte__b  = G".$poblacion."_M".$muestra."_CoInMiPo__b WHERE G".$poblacion."_M".$muestra."_CanalUG_b = \'Chat_web\'";
            // Sql de insercion en flecha
            $insertFlecha = "INSERT INTO {$BaseDatos_systema}.ESTCON (ESTCON_Nombre____b, ESTCON_Comentari_b, ESTCON_ConsInte__ESTPAS_Des_b, ESTCON_ConsInte__ESTPAS_Has_b, 
            ESTCON_FromPort_b, ESTCON_ToPort_b, ESTCON_ConsInte__ESTRAT_b, ESTCON_Consulta_sql_b, ESTCON_Tipo_Consulta_b, 
            ESTCON_Tipo_Insercion_b, ESTCON_ConsInte_PREGUN_Fecha_b, ESTCON_ConsInte_PREGUN_Hora_b, ESTCON_Operacion_Fecha_b, ESTCON_Operacion_Hora_b, ESTCON_Cantidad_Fecha_b, 
            ESTCON_Cantidad_Hora_b, ESTCON_Estado_cambio_b, ESTCON_Sacar_paso_anterior_b, ESTCON_resucitar_registro, ESTCON_Activo_b, ESTCON_Deshabilitado_b) 
            VALUES('Conector', 'Conector historial', {$pasoDes}, {$pasoHas}, '{$puertoFrom}', '{$puertoTo}', {$estrategia}, '{$consulta}', 3, 0, -1, -1, 1, 1, 0, 0, 0, 0, 1, -1, -1)";
        }else{
            $consulta = "SELECT G".$poblacion."_ConsInte__b as id FROM DYALOGOCRM_WEB.G".$poblacion;
            // Sql de insercion en flecha
            $insertFlecha = "INSERT INTO {$BaseDatos_systema}.ESTCON (ESTCON_Nombre____b, ESTCON_Comentari_b, ESTCON_ConsInte__ESTPAS_Des_b, ESTCON_ConsInte__ESTPAS_Has_b, 
            ESTCON_FromPort_b, ESTCON_ToPort_b, ESTCON_ConsInte__ESTRAT_b, ESTCON_Consulta_sql_b, ESTCON_Tipo_Consulta_b, 
            ESTCON_Tipo_Insercion_b, ESTCON_ConsInte_PREGUN_Fecha_b, ESTCON_ConsInte_PREGUN_Hora_b, ESTCON_Operacion_Fecha_b, ESTCON_Operacion_Hora_b, ESTCON_Cantidad_Fecha_b, 
            ESTCON_Cantidad_Hora_b, ESTCON_Estado_cambio_b, ESTCON_Sacar_paso_anterior_b, ESTCON_resucitar_registro, ESTCON_Activo_b, ESTCON_Deshabilitado_b) 
            VALUES('Conector', 'Conector historial', {$pasoDes}, {$pasoHas}, '{$puertoFrom}', '{$puertoTo}', {$estrategia}, '{$consulta}', 1, 0, -1, -1, 1, 1, 0, 0, 0, 0, 1, -1, -1)";
        }


        if($mysqli->query($insertFlecha) === true){
            // Inserto la condicion de la flecha
            $estconId = $mysqli->insert_id;
            $sqlCondicionFlecha = "INSERT INTO {$BaseDatos_systema}.ESTCON_CONDICIONES (id_estcon, campo, condicion, valor, separador) VALUES ({$estconId}, '_CanalUG_b', '=', 'Chat_web', ' WHERE ')";
            $mysqli->query($sqlCondicionFlecha);
        }else{
            echo $mysqli->error;
        }
    }
?>