<?php 
    session_start();
    ini_set('display_errors', 'On');
    ini_set('display_errors', 1);
    include_once(__DIR__."../../../../pages/conexion.php");
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
            $baseId = $_POST['bd'];

            $str_Lsql = 'SELECT ESTPAS_ConsInte__b as id, ESTPAS_Comentari_b as nombre, ESTPAS_activo as activo, ESTPAS_ConsInte__ESTRAT_b AS estrategia FROM '.$BaseDatos_systema.'.ESTPAS WHERE ESTPAS_ConsInte__b = '.$_POST['pasoId'];
            $res = $mysqli->query($str_Lsql);
            $datos = $res->fetch_object();

            // Obtengo los campos de la base de datos
            $sql_bd = "SELECT PREGUN_ConsInte__b AS id, PREGUN_Texto_____b AS nombre FROM {$BaseDatos_systema}.PREGUN WHERE PREGUN_ConsInte__GUION__b = {$baseId} AND PREGUN_Tipo______b = 1 AND (PREGUN_Texto_____b != 'ORIGEN_DY_WF' AND PREGUN_Texto_____b != 'OPTIN_DY_WF' AND PREGUN_Texto_____b != 'ESTADO_DY')";
            $res_bd = $mysqli->query($sql_bd);

            $camposBd = [];
            while ($row = $res_bd->fetch_object()) {
                $camposBd[$row->id] = $row->nombre;
            }

            // Obtengo los pasos tipo sms entrante de la estrategia
            $sql_pasos = "SELECT ESTPAS_ConsInte__b AS id, ESTPAS_Comentari_b AS nombre FROM {$BaseDatos_systema}.ESTPAS WHERE ESTPAS_Tipo______b = 8 AND ESTPAS_ConsInte__ESTRAT_b = {$datos->estrategia}";
            $res_pas = $mysqli->query($sql_pasos);

            $pasosSms = [];
            while ($row = $res_pas->fetch_object()) {
                
                if(!is_null($row->nombre)){
                    $pasosSms[$row->id] = $row->nombre;
                }else{
                    $pasosSms[$row->id] = "Paso {$row->id} no configurado";
                }

            }

            // Trigo una flecha, si existe para traer la configuracion relacionada a esta
            $sqlFlechas = "SELECT ESTCON_ConsInte__b AS id, ESTCON_ConsInte__ESTPAS_Des_b AS pasoSaliente FROM {$BaseDatos_systema}.ESTCON WHERE ESTCON_ConsInte__ESTPAS_Has_b = {$pasoId} LIMIT 1";
            $resFlechas = $mysqli->query($sqlFlechas);

            $pasoSalienteSms = 0;
            $pregun = 0;
            if($resFlechas->num_rows > 0){
                $flechas = $resFlechas->fetch_object();

                $pasoSalienteSms = $flechas->pasoSaliente;

                // Ahora traigo la informacion de ese paso
                $pasoSms = "SELECT SMS_SALIENTE_ConsInte_PREGUN_Actualizar_b AS pregun, SMS_SALIENTE_EsperarRespuesta_b AS esperarRespuesta FROM {$BaseDatos_systema}.SMS_SALIENTE WHERE SMS_SALIENTE_ConsInte_ESTPAS_b = {$pasoSalienteSms}";
                $resPasoSaliente = $mysqli->query($pasoSms);
                $estePaso = $resPasoSaliente->fetch_object();

                if($estePaso->esperarRespuesta == -1){
                    $pregun = $estePaso->pregun;
                }

            }

            echo json_encode([
                "datosPaso" => $datos,
                "camposBd" => $camposBd,
                "pasosSms" => $pasosSms,
                "pasoSalienteSms" => $pasoSalienteSms,
                "pregun" => $pregun
            ]);
        }

        // Insertar datos
        if(isset($_GET['insertarDatos']) && isset($_POST["oper"])){

            // Independiente si es nuevo o no actualizo el paso
            $pasoId = $_POST['id_paso'];

            $activo = isset($_POST['pasoActivo']) ? $_POST['pasoActivo'] : 0;

            $pasoSql = "UPDATE ".$BaseDatos_systema.".ESTPAS SET ESTPAS_Comentari_b = '".$_POST['nombre']."', ESTPAS_activo = '".$activo."' WHERE ESTPAS_ConsInte__b = ".$_POST['id_paso'];
            $mysqli->query($pasoSql);

            $nombre = $_POST['nombre'];
            $huespedId = $_POST['huesped'];
            
            $pregun = $_POST['pregun'];
            $pasoSmsSaliente = $_POST['pasoConectado'];

            // Ahora actualizo el paso sms saliente con la siguiente informacion
            $update = "UPDATE {$BaseDatos_systema}.SMS_SALIENTE SET SMS_SALIENTE_ConsInte_PREGUN_Actualizar_b = {$pregun}, SMS_SALIENTE_EsperarRespuesta_b = -1 WHERE SMS_SALIENTE_ConsInte_ESTPAS_b = {$pasoSmsSaliente}";
            $mysqli->query($update);

            // Necesito la estrategia
            $pasoConf = "SELECT ESTPAS_ConsInte__ESTRAT_b AS estrategia FROM {$BaseDatos_systema}.ESTPAS WHERE ESTPAS_ConsInte__b = {$pasoId}";
            $resPaso = $mysqli->query($pasoConf);

            $paso = $resPaso->fetch_object();

            // Ahora hago el dibujado de las flechas
            generarFlecha($pasoSmsSaliente, $pasoId, $paso->estrategia);
            
            
            // SE GENERAN LAS METAS PARA ESTE PASO

            insertarMetas($paso->estrategia,$pasoId,1);


            echo json_encode(["estado" => "registrado"]);
        }

        if(isset($_GET['quitarConexiones'])){
            $pasoId = $_POST['pasoId'];

            // hago la actualizacion para quitar el paso
            $update = "UPDATE {$BaseDatos_systema}.SMS_SALIENTE SET SMS_SALIENTE_ConsInte_PREGUN_Actualizar_b = 0, SMS_SALIENTE_EsperarRespuesta_b = 0";
            $mysqli->query($update);

            echo json_encode([
                "estado" => "ok",
                "mensaje" => "paso actualizado"
            ]);
        }

        if(isset($_GET['crearFlecha'])){
            $pasoFrom = $_POST['pasoFrom'];
            $pasoTo = $_POST['pasoTo'];

            // Primero valido si esa bola tiene configuracion 
            $sql = "SELECT SMS_SALIENTE_ConsInte__b AS id FROM {$BaseDatos_systema}.SMS_SALIENTE WHERE SMS_SALIENTE_ConsInte_ESTPAS_b = {$pasoFrom}";
            $res = $mysqli->query($sql);

            if($res->num_rows > 0){

                // Necesito la estrategia
                $pasoConf = "SELECT ESTPAS_ConsInte__ESTRAT_b AS estrategia FROM {$BaseDatos_systema}.ESTPAS WHERE ESTPAS_ConsInte__b = {$pasoFrom}";
                $resPaso = $mysqli->query($pasoConf);
    
                $paso = $resPaso->fetch_object();
    
                // Ahora hago el dibujado de las flechas
                generarFlecha($pasoFrom, $pasoTo, $paso->estrategia);
    
                echo json_encode([
                    "estado" => "ok",
                    "mensaje" => "flecha creada"
                ]);
            }else{
                echo json_encode([
                    "estado" => "fallo",
                    "mensaje" => "Se debe configurar primero el paso de sms saliente para luego crear la flecha"
                ]);
            }

        }

        if(isset($_GET['traerConfiguracion'])){
            $pregun = 0;
            $pasoFrom = $_POST['pasoFrom'];

            $sql = "SELECT SMS_SALIENTE_ConsInte__b AS id, SMS_SALIENTE_ConsInte_PREGUN_Actualizar_b AS pregun, SMS_SALIENTE_EsperarRespuesta_b AS esperarRespuesta FROM DYALOGOCRM_SISTEMA.SMS_SALIENTE 
                        JOIN DYALOGOCRM_SISTEMA.ESTCON ON SMS_SALIENTE_ConsInte_ESTPAS_b = ESTCON_ConsInte__ESTPAS_Des_b
                    WHERE ESTCON_ConsInte__ESTPAS_Has_b = {$pasoFrom} LIMIT 1";
            $res = $mysqli->query($sql);

            $smsSaliente = [];
            if($res->num_rows > 0){
                $smsSaliente = $res->fetch_object();
            }

            echo json_encode([
                "estado" => "ok",
                "smsSaliente" => $smsSaliente
            ]);
        }

    }

    function generarFlecha($pasoFrom, $pasoTo, $estrategia){
        global $mysqli;

        // Busco si ya existe una flecha configurada con anterioridad
        $sqlFlechas = "SELECT * FROM DYALOGOCRM_SISTEMA.ESTCON WHERE ESTCON_ConsInte__ESTPAS_Des_b = {$pasoFrom} AND ESTCON_ConsInte__ESTPAS_Has_b = $pasoTo";
        $resFlechas = $mysqli->query($sqlFlechas);
        if($resFlechas && $resFlechas->num_rows > 0){
            //
        }else{
            $insertFlecha = "INSERT INTO DYALOGOCRM_SISTEMA.ESTCON (ESTCON_Nombre____b, ESTCON_ConsInte__ESTPAS_Des_b, ESTCON_ConsInte__ESTPAS_Has_b, ESTCON_FromPort_b, ESTCON_ToPort_b, ESTCON_ConsInte__ESTRAT_b, ESTCON_Tipo_Consulta_b, ESTCON_Tipo_Insercion_b,ESTCON_ConsInte_PREGUN_Fecha_b,ESTCON_ConsInte_PREGUN_Hora_b,ESTCON_Operacion_Fecha_b,ESTCON_Operacion_Hora_b,ESTCON_Cantidad_Fecha_b,ESTCON_Cantidad_Hora_b,ESTCON_Estado_cambio_b,ESTCON_Sacar_paso_anterior_b,ESTCON_resucitar_registro, ESTCON_Deshabilitado_b) VALUES ('conector', {$pasoFrom}, {$pasoTo}, 'R', 'L', {$estrategia} , 1,0,-1,-1,1,1,0,0,0,0,0, -1)";
            $insert = $mysqli->query($insertFlecha);
        }
        
    }

?>