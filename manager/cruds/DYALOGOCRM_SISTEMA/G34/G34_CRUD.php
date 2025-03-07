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

        if(isset($_GET['getDatos']) && $_GET['getDatos'] == true){

            $pasoId = $_POST['pasoId'];
            $huespedId = $_POST['huesped'];
            $bdId = $_POST['bd'];

            // Obtenemos el paso del registro
            $sql = "SELECT ESTPAS_ConsInte__b as id, ESTPAS_Comentari_b as nombre, ESTPAS_activo as activo, ESTPAS_ConsInte__ESTRAT_b AS est FROM ".$BaseDatos_systema.".ESTPAS WHERE ESTPAS_ConsInte__b = ".$pasoId;
            $res = $mysqli->query($sql);
            $paso = $res->fetch_object();

            // Obtenemos la configuracion del chat
            $sql = "SELECT id, nombre, ruta_logo, id_pregun_campo_busqueda, id_pregun_campo_busqueda, integrado_con, dato_integracion, frase_sin_agentes_disponibles, frase_agente_asignado, frase_bienvenida_autorespuesta, frase_fuera_horario, mensaje_bienvenida, tiempo_maximo_asignacion, tiempo_maximo_inactividad_cliente, frase_tiempo_asignacion_excedido, tiempo_maximo_inactividad_agente, frase_inactividad_agente, tiempo_maximo_inactividad_cliente, frase_inactividad_cliente, link_politica_privacidad,dentro_horario_accion,dentro_horario_detalle_accion,fuera_horario_accion,fuera_horario_detalle_accion FROM ".$dyalogo_canales_electronicos.".dy_chat_configuracion WHERE id_estpas = ".$pasoId;
            $resChat = $mysqli->query($sql);

            $chat = null;

            if($resChat && $resChat->num_rows > 0){
                $chat = $resChat->fetch_object();
            }

            // Horarios
            $horario = [];

            if(!is_null($chat)){
                $horariosSql = "SELECT * FROM ".$dyalogo_canales_electronicos.".dy_chat_horarios WHERE id_configuracion = ".$chat->id;
                $resHorario = $mysqli->query($horariosSql);
    
                while($row = $resHorario->fetch_object()){
                    $horario[$row->dia_inicial] = $row;
                }
                
            }

            // Traigo la lista de canales disponibles
            $sql = "SELECT * FROM ". $dyalogo_canales_electronicos . ".dy_canales_dymdw WHERE tipo_canal = 'instagram' AND id_huesped = " . $huespedId;
            $resCanales = $mysqli->query($sql);

            $canales = [];

            if($resCanales && $resCanales->num_rows > 0){

                $i = 0;

                while($row = $resCanales->fetch_object()){

                    if($row->id_cfg_chat == 0){
                        
                        $canales[$i]['nombre'] = $row->nombre;
                        $canales[$i]['cuenta'] = $row->identificador;
                        $canales[$i]['disponible'] = true;

                    }else if(!is_null($chat) && $row->id_cfg_chat == $chat->id){

                        $canales[$i]['nombre'] = $row->nombre. '- Usado en este paso';
                        $canales[$i]['cuenta'] = $row->identificador;
                        $canales[$i]['disponible'] = true;

                    }else{

                        // Me toca traer el nombre de esoas y estrategia
                        $sql = "SELECT ESTRAT_Nombre____b as estrategia, ESTPAS_Comentari_b as paso FROM DYALOGOCRM_SISTEMA.ESTPAS 
                            INNER JOIN DYALOGOCRM_SISTEMA.ESTRAT ON ESTPAS.ESTPAS_ConsInte__ESTRAT_b = ESTRAT.ESTRAT_ConsInte__b
                            INNER JOIN dyalogo_canales_electronicos.dy_chat_configuracion ON ESTPAS.ESTPAS_ConsInte__b = dy_chat_configuracion.id_estpas
                        WHERE dy_chat_configuracion.id = ".$row->id_cfg_chat;
                        
                        $resDisponibles = $mysqli->query($sql);
                        $rowDisponible = $resDisponibles->fetch_object();

                        $canales[$i]['nombre'] = $row->nombre.' (Está siendo usado en:'.$rowDisponible->estrategia.', Paso:'.$rowDisponible->paso.')';
                        $canales[$i]['cuenta'] = $row->identificador;
                        $canales[$i]['disponible'] = false;

                    }

                    $i++;
                }
            }

            // Obtenemos las campañas
            $sql = "SELECT ESTPAS_ConsInte__CAMPAN_b AS id, B.CAMPAN_IdCamCbx__b AS campanCbx, ESTPAS_Comentari_b AS nombre FROM DYALOGOCRM_SISTEMA.ESTPAS 
                INNER JOIN DYALOGOCRM_SISTEMA.CAMPAN B ON ESTPAS_ConsInte__CAMPAN_b = CAMPAN_ConsInte__b 
            WHERE ESTPAS_ConsInte__ESTRAT_b = {$paso->est} AND ESTPAS_Tipo______b = 1 AND CAMPAN_IdCamCbx__b IS NOT NULL";
            $resCampana = $mysqli->query($sql);

            $campanas = [];

            if($resCampana && $resCampana->num_rows > 0){
                while($row = $resCampana->fetch_object()){
                    $campanas[] = $row;
                }
            }

            // Obtenemos los bots
            $sql = "SELECT d.ESTPAS_Comentari_b AS nombre, c.id AS id FROM {$dyalogo_canales_electronicos}.secciones_bot b 
                INNER JOIN {$dyalogo_canales_electronicos}.dy_base_autorespuestas c ON c.id_seccion = b.id
                INNER JOIN {$BaseDatos_systema}.ESTPAS d ON d.ESTPAS_ConsInte__b = b.id_estpas
            WHERE b.tipo_seccion = 3 AND d.ESTPAS_ConsInte__ESTRAT_b = ". $paso->est;
            $resBots = $mysqli->query($sql);

            $bots = [];

            if($resBots && $resBots->num_rows > 0){
                while($row = $resBots->fetch_object()){
                    $bots[] = $row;
                }
            }

            echo json_encode([
                "estado" => "ok",
                "paso" => $paso,
                "chat" => $chat,
                "horarios" => $horario,
                "canales" => $canales,
                "campanas" => $campanas,
                "bots" => $bots
            ]);
            exit;
        }

        if(isset($_GET['insertarDatos']) && isset($_POST["oper"])){

            $pasoId = $_POST['id_paso'];
            $huespedId = $_POST['huesped'];

            $nombre = $_POST['nombre'];
            $activo = isset($_POST['pasoActivo']) ? $_POST['pasoActivo'] : 0;

            // Actualizamos el paso
            $sql = "UPDATE ".$BaseDatos_systema.".ESTPAS SET ESTPAS_Comentari_b = '".$nombre."', ESTPAS_activo = '".$activo."' WHERE ESTPAS_ConsInte__b = ".$pasoId;
            $mysqli->query($sql);

            //Mensajes generales automaticos
            $mensajeBienvenidaAutorespuesta =   isset($_POST['dentroHorarioMensaje']) ? $_POST['dentroHorarioMensaje'] : '';
            $mensajeFueraHorario            =   isset($_POST['fueraHorarioMensaje']) ? $_POST['fueraHorarioMensaje'] : '';
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
            
            $tipoIntegracion = 'instagram';

            $titulo = $nombre;
            $mensajeBienvenida = '';
            $campoBusqueda = (isset($_POST['campoBusquedaIg'])) ? $_POST['campoBusquedaIg'] : 0;
            $datoIntegracion = $_POST['datoIntegracion'];
            $urlPoliticasPrivacidad = '';

            if($_POST["oper"] == "add"){

                // Primero creamos el formulario
                $Lsql = "INSERT INTO ".$BaseDatos_general.".dy_formularios (nombre, id_huesped) VALUES ('".$nombre."','".$huespedId."')";
                $mysqli->query($Lsql);
                $formularioId = $mysqli->insert_id;

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
                    '".$fraseValidacionLlave."','".$placeholders."','".$publicarApp."', '".$tipoIntegracion."', 
                    '".$datoIntegracion."', '".$mensajeSinAgenteDisponible."', '".$mensajeAgenteAsignado."', '".$mensajeBienvenidaAutorespuesta."',
                    '".$campoBusqueda."', '".$urlPoliticasPrivacidad."', ".$pasoId.")";
                
                $mysqli->query($Lsql);

                $id_chat = $mysqli->insert_id;

                // Inserto los horarios
                insertarHorarios($id_chat, 'insert');
            }

            if($_POST["oper"] == "edit"){

                $id_chat = $_POST['configuracionId'];                
                
                $Lsql = "UPDATE ".$dyalogo_canales_electronicos.".dy_chat_configuracion SET 
                    nombre = '".$titulo."', mensaje_bienvenida = '".$mensajeBienvenida."', tiempo_maximo_asignacion = ".$tiempoMaximoAsignacion.", 
                    frase_tiempo_asignacion_excedido = '".$mensajeTiempoExcedido."', tiempo_maximo_inactividad_agente = ".$tiempoMaximoInactividadAgente.",         
                    tiempo_maximo_inactividad_cliente = ".$TiempoMaximoInactividadCliente.", frase_inactividad_agente = '".$mensajeInactividadAgente."', 
                    frase_inactividad_cliente  = '".$mensajeInactividadCliente."', integrado_con = '".$tipoIntegracion."', dato_integracion = '".$datoIntegracion."', 
                    frase_sin_agentes_disponibles = '".$mensajeSinAgenteDisponible."', frase_agente_asignado = '".$mensajeAgenteAsignado."', 
                    frase_bienvenida_autorespuesta = '".$mensajeBienvenidaAutorespuesta."', frase_fuera_horario = '".$mensajeFueraHorario."',
                    id_pregun_campo_busqueda = '".$campoBusqueda."', link_politica_privacidad = '".$urlPoliticasPrivacidad."'        
                WHERE id = ".$id_chat." AND integrado_con = '".$tipoIntegracion."'";
                
                $mysqli->query($Lsql);
                
                // Ahora acutualizo los horarios
                insertarHorarios($_POST['configuracionId'], 'update');
            }

            $campanaIdentificador = null;

            // Dentro de horario accion
            if($_POST['dentroHorarioAccion'] == 1){
                $accionDetalle = $_POST['dentroHorarioCampan'];
                $campanaIdentificador = $_POST['dentroHorarioCampan'];
            }else if($_POST['dentroHorarioAccion'] == 2) {
                $accionDetalle = $_POST['dentroHorarioBot'];
                agregarConfiguracionBot($id_chat, $accionDetalle);
            }

            //Fuera de horario
            if($_POST['fueraHorarioAccion'] == 1){
                $accionDetallef = $_POST['fueraHorarioCampana'];
                $campanaIdentificador = $_POST['fueraHorarioCampana'];
            }else if($_POST['fueraHorarioAccion'] == 2) {
                $accionDetallef = $_POST['fueraHorarioBot'];
                agregarConfiguracionBot($id_chat, $accionDetallef);
            }

            $updf = "UPDATE ".$dyalogo_canales_electronicos.".dy_chat_configuracion SET dentro_horario_accion = ".$_POST['dentroHorarioAccion'].", dentro_horario_detalle_accion = ".$accionDetalle.", fuera_horario_accion = ".$_POST['fueraHorarioAccion'].", fuera_horario_detalle_accion = ".$accionDetallef." WHERE id = ".$id_chat;
            $mysqli->query($updf);

            //Guardamos tambien en dy_instagram(El consumo para hacer la actualizacion en middleware)
            $ws = "UPDATE {$dyalogo_canales_electronicos}.dy_canales_dymdw SET id_cfg_chat = {$id_chat} WHERE identificador = '{$datoIntegracion}' AND tipo_canal = 'instagram'";
            $mysqli->query($ws);

            // Hay que hacer una insercion que se inserta el chat_accion pero para eso se necesita la campana_cbx
            agregarConfiguracionCampana($id_chat, $titulo, $campanaIdentificador);


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

            // Sincronizamos el chat con dymdw
            $syncCanales = json_decode(syncDymdw($datoIntegracion, $id_chat));

            limpiarFlechas($pasoId);
            generarFlecha($pasoId);

            echo json_encode(["estado" => "registrado", "sync" => $syncCanales]);
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
            if(isset($_POST['campoBusquedaIg']) && $_POST['campoBusquedaIg'] != '' && $_POST['campoBusquedaIg'] != 0){

                // Actualizamos la tabla bot con el ani
                $upd = "UPDATE {$dyalogo_canales_electronicos}.dy_bot SET id_pregun_dato_instagram = " . $_POST['campoBusquedaIg'] . " WHERE id = " . $autoRes->id_bot;
                $mysqli->query($upd);
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

            // Con horario
            $idPasoEnd = 0;
            $idEstrategia = 0;

            $generarFlecha = new GeneradorDeFlechas;

            // Busco el id del paso de la bola destino
            if($row->dentro_horario_accion == 1){
                $sqlPasoEnd = "SELECT ESTPAS_ConsInte__b AS id, ESTPAS_ConsInte__ESTRAT_b AS estrategia FROM DYALOGOCRM_SISTEMA.ESTPAS INNER JOIN DYALOGOCRM_SISTEMA.CAMPAN ON ESTPAS_ConsInte__CAMPAN_b = CAMPAN_ConsInte__b WHERE CAMPAN_IdCamCbx__b = ".$row->dentro_horario_detalle_accion;
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
                $sqlPasoEnd = "SELECT ESTPAS_ConsInte__b AS id, ESTPAS_ConsInte__ESTRAT_b AS estrategia FROM DYALOGOCRM_SISTEMA.ESTPAS INNER JOIN DYALOGOCRM_SISTEMA.CAMPAN ON ESTPAS_ConsInte__CAMPAN_b = CAMPAN_ConsInte__b WHERE CAMPAN_IdCamCbx__b = ".$row->fuera_horario_detalle_accion;
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

    function syncDymdw($canal, $chatId){
        
        global $URL_SERVER;
        global $DY_MIDDLEWARE_HOST;

        $data = array(
            "intIdHuespedGeneralt" => $_SESSION['HUESPED'],
            "strServer" => $URL_SERVER,
            "strCanal" => $canal,
            "intChatId" => $chatId,
            "strUsuario_t" => 'crm',
            "strToken_t" => 'D43dasd321',
        );
        return conexionApi($DY_MIDDLEWARE_HOST.'/dymdw/api/config/i/sync', $data);
    }

    function conexionApi($strUrl_p, $arrayDatos_p){

        // Se codifica el arreglo en formato JSON
        $dataString = json_encode($arrayDatos_p);
        
        // Se inicia la conexion CURL al web service para ser consumido
        $ch = curl_init($strUrl_p);

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