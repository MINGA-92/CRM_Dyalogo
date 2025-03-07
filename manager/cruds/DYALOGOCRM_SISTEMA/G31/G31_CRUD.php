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
            $bd = $_POST['bd'];

            $str_Lsql = 'SELECT ESTPAS_ConsInte__b as id, ESTPAS_Comentari_b as nombre, ESTPAS_activo as activo FROM '.$BaseDatos_systema.'.ESTPAS WHERE ESTPAS_ConsInte__b = '.$_POST['pasoId'];
            $res = $mysqli->query($str_Lsql);
            $datos = $res->fetch_object();

            // Traigo la configuracion en CORREO_ENTRANTE
            $sqlCorreo = "SELECT CORREO_ENTRANTE_ConsInte__b AS id, CORREO_ENTRANTE_Cuenta_b AS cuenta, CORREO_ENTRANTE_AutorespuestaCuerpo_b AS cuerpoBienvenida, CORREO_ENTRANTE_CampoBusqueda_b AS campoBusqueda FROM {$BaseDatos_systema}.CORREO_ENTRANTE WHERE CORREO_ENTRANTE_ConsInte__ESTPAS_b = {$pasoId}";
            $resCorreo = $mysqli->query($sqlCorreo);
            $correoConf = $resCorreo->fetch_object();

            // Traigo las cuentas de correo disponibles
            $opcionesCorreo = [];
            $Lsql = 'SELECT * FROM '.$dyalogo_canales_electronicos.'.dy_ce_configuracion WHERE id_huesped = '.$_SESSION['HUESPED'];
            $combo = $mysqli->query($Lsql);

            while($row = $combo->fetch_object()){
                $opcionesCorreo[$row->id] = $row->direccion_correo_electronico;
            }  

            // Traigo los campos que se usaran como campo de busqueda
            $camposBd = [];
            $lsql = "SELECT PREGUN_ConsInte__b AS id, PREGUN_Texto_____b AS nombre FROM {$BaseDatos_systema}.PREGUN WHERE PREGUN_ConsInte__GUION__b = {$bd} AND (PREGUN_Tipo______b = 1 OR PREGUN_Tipo______b = 14) AND PREGUN_Texto_____b NOT LIKE '%_DY%' ORDER BY PREGUN_Texto_____b ASC";
                $resBd = $mysqli->query($lsql);
                while ($row = $resBd->fetch_object()) {
                    $camposBd[$row->id] = $row->nombre;
                }

            echo json_encode([
                "datosPaso" => $datos,
                "correoConf" => $correoConf,
                "opcionesCorreo" => $opcionesCorreo,
                "camposBd" => $camposBd
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
            
            // Reutilizare la tabla de CORREO_ENTRANTE
            $cuenta = $_POST['mailCuentaCorreo'];
            $checkMensajeBienvenida = $_POST['configMensajeBienvenida'] ?? 0;
            $mensajeBienvenida = '';

            if(isset($checkMensajeBienvenida) && $checkMensajeBienvenida == -1){
                $mensajeBienvenida = $_POST['correoMensajeBienvenida'];
            }

            $campoBusqueda = $_POST['mailCampoBusqueda'];

            if($_POST["oper"] == "add"){

                //Guardo en la tabla CORREO_ENTRANTE
                $sql = "INSERT INTO {$BaseDatos_systema}.CORREO_ENTRANTE (CORREO_ENTRANTE_Nombre_b, CORREO_ENTRANTE_ConsInte__ESTPAS_b, CORREO_ENTRANTE_Cuenta_b, CORREO_ENTRANTE_AutorespuestaCuerpo_b, CORREO_ENTRANTE_TipoAccion_b, CORREO_ENTRANTE_CampoBusqueda_b) 
                    VALUES ('{$nombre}', {$pasoId}, {$cuenta}, '{$mensajeBienvenida}', 2, '{$campoBusqueda}')";

                $mysqli->query($sql);                    

            }

            if($_POST["oper"] == "edit"){
                
                // Actualizacion
                $sql = "UPDATE {$BaseDatos_systema}.CORREO_ENTRANTE SET CORREO_ENTRANTE_Nombre_b = '{$nombre}',
                CORREO_ENTRANTE_Cuenta_b = {$cuenta}, CORREO_ENTRANTE_AutorespuestaCuerpo_b = '{$mensajeBienvenida}', CORREO_ENTRANTE_CampoBusqueda_b = '{$campoBusqueda}' WHERE CORREO_ENTRANTE_ConsInte__ESTPAS_b = {$pasoId}";

                $mysqli->query($sql);

                // Buscon el id correo entrante
                $sqlCorreo = "SELECT CORREO_ENTRANTE_ConsInte__b as id, CORREO_ENTRANTE_Cuenta_b AS cuenta  FROM {$BaseDatos_systema}.CORREO_ENTRANTE WHERE CORREO_ENTRANTE_ConsInte__ESTPAS_b = {$pasoId}";
                $resCorreo = $mysqli->query($sqlCorreo);
                $correoConf = $resCorreo->fetch_object();

                $correoId = $correoConf->id;

                $checkMensajeBienvenida = $_POST['configMensajeBienvenida'] ?? 0;
                
                // Valido de que almenos exista un filtro 
                $filtroSql = "SELECT id, id_ce_configuracion FROM {$dyalogo_canales_electronicos}.dy_ce_filtros WHERE id_estpas = {$pasoId} LIMIT 1";
                $resFiltro = $mysqli->query($filtroSql);

                if($resFiltro->num_rows > 0){

                    $filtro = $resFiltro->fetch_array();

                    // Ahora si cambio la cuenta de los filtros
                    $sqlFiltroUpdate = "UPDATE {$dyalogo_canales_electronicos}.dy_ce_filtros SET id_ce_configuracion = {$correoConf->cuenta} WHERE id_estpas = {$pasoId}";
                    $mysqli->query($sqlFiltroUpdate);
                
                    // Seccion del mensaje de bienvenida autorespuesta
                    if(isset($checkMensajeBienvenida) && $checkMensajeBienvenida == -1){
                          
                        // Insercion o actualizacion
                        $accionLsql = "SELECT * FROM ".$dyalogo_canales_electronicos.".dy_ce_acciones_filtro WHERE accion = 1 AND id_correo_entrante = ".$correoId;
                        $resAccion = $mysqli->query($accionLsql);
    
                        if($resAccion->num_rows == 0){
    
                            // Si no estan creadas debo ponerlas en las campanas a las cuales este conectada
                            $sqlCampanas = "SELECT CAMPAN_ConsInte__b, CAMPAN_Nombre____b, CAMPAN_IdCamCbx__b FROM DYALOGOCRM_SISTEMA.ESTCON 
                                JOIN DYALOGOCRM_SISTEMA.ESTPAS ON ESTPAS_ConsInte__b = ESTCON_ConsInte__ESTPAS_Has_b
                                JOIN DYALOGOCRM_SISTEMA.CAMPAN ON ESTPAS_ConsInte__CAMPAN_b = CAMPAN_ConsInte__b
                                WHERE ESTCON_ConsInte__ESTPAS_Des_b = {$pasoId}";
                            
                            $resCampanas = $mysqli->query($sqlCampanas);
    
                            if($resCampanas->num_rows > 0){
    
                                while ($row = $resCampanas->fetch_object()) {
                                    
                                    $id_campanas_cbx = $row->CAMPAN_IdCamCbx__b;
    
                                    $orden = ordenMax($correoConf->cuenta);
                                    $Isql = "INSERT INTO {$dyalogo_canales_electronicos}.dy_ce_acciones_filtro (id_filtro, orden, accion, id_cola_distribucion, cuerpo, id_correo_entrante) VALUES (".$filtro['id'].",".$orden.",1,".$id_campanas_cbx.", '".$mensajeBienvenida."', ".$correoId.")";
                                    $mysqli->query($Isql);
                                }
    
                            }
    
                        }else{
                            // Actualizo el campo
                            $mensajeBienvenida = $_POST['correoMensajeBienvenida'];
                            $update = "UPDATE {$dyalogo_canales_electronicos}.dy_ce_acciones_filtro SET cuerpo = '{$mensajeBienvenida}' WHERE accion = 1 AND id_correo_entrante = {$correoId}";
                            $mysqli->query($update);
                        }
    
    
                    }else{
                        // Lo elimino
                        $Dsql = "DELETE FROM {$dyalogo_canales_electronicos}.dy_ce_acciones_filtro WHERE accion = 1 AND id_correo_entrante = {$correoId}";
                        $mysqli->query($Dsql);
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

            // limpiarFlechas($pasoId);
            // generarFlecha($pasoId);

            echo json_encode(["estado" => "registrado"]);
        }

        if(isset($_GET['guardarFiltros'])){

            // Vemos cual es el estado de la flecha
            $activo = $_POST['estconActivo'] ?? 0;
            $pasoId = $_POST['from'];
            $pasoToId = $_POST['to'];

            // valido de que este creado una configuracion en la bola de email

            $sql = "SELECT CORREO_ENTRANTE_ConsInte__b AS id, CORREO_ENTRANTE_Cuenta_b AS cuenta, CORREO_ENTRANTE_AutorespuestaCuerpo_b AS cuerpoBienvenida FROM {$BaseDatos_systema}.CORREO_ENTRANTE WHERE CORREO_ENTRANTE_ConsInte__ESTPAS_b = {$pasoId}";
            $res = $mysqli->query($sql);

            // Valido que este configurado el paso, de lo contrario no guarde nada
            if($res->num_rows > 0){
                $correoConfiguracion = $res->fetch_object();

                // Buscamos la campana configurada
                $pasoCampana = $_POST['to'];

                $sqlCampana = "SELECT CAMPAN_ConsInte__b AS id, CAMPAN_IdCamCbx__b AS id_campana_cbx FROM {$BaseDatos_systema}.CAMPAN 
                    JOIN {$BaseDatos_systema}.ESTPAS ON CAMPAN_ConsInte__b = ESTPAS_ConsInte__CAMPAN_b
                    WHERE ESTPAS_ConsInte__b = {$pasoCampana}";
                
                $resCampana = $mysqli->query($sqlCampana);
                $campanaConfiguracion = $resCampana->fetch_object();

                $contador = $_POST["cantFiltrosEmail"];
    
                if($contador > 0){
                    // Recorro las filas de los filtros
                    for ($i=0; $i < $contador; $i++) { 
                        $oper = isset($_POST['campo_edit_'.$i]) ? 'edit' : 'add';
    
                        if(isset($_POST['campo_'.$oper.'_'.$i])){
                            $cuentaId = $correoConfiguracion->cuenta;
                            $operador = $_POST['operador_'.$oper.'_'.$i];
                            $tipoCondicion = $_POST['mailTipoCondicion_'.$oper.'_'.$i];

                            $ruta = "/mnt/disks/grabaciones/adjuntos/";
                            $id_campanas_crm = $campanaConfiguracion->id;
                            $id_campanas_cbx = $campanaConfiguracion->id_campana_cbx;
                            
                            // esto es para setear la condicion si es tipo 100
                            if($tipoCondicion == 100){
                                $condicion = '';
                            }else{
                                $condicion = $_POST['mailCondicion_'.$oper.'_'.$i];
                            }

                            if(strtolower($condicion) == 'null'){
                                $condicion = '';
                            }
    
                            // Valido el operador para actualizar/insertar
                            if($oper == 'add'){
                                // Insertamos los nuevos filtros de email
                                $Lsql = "INSERT INTO ".$dyalogo_canales_electronicos.".dy_ce_filtros (id_ce_configuracion, filtro, condicion, descargar_adjuntos, directorio_adjuntos, id_campana_crm, operador, id_estpas) VALUES ('".$cuentaId."','".$tipoCondicion."','".$condicion."',1,'".$ruta."', ".$id_campanas_crm.", '".$operador."', ".$pasoId.")";
                                $mysqli->query($Lsql);
                            }else{
                                // Actualizo
                                $filtroId = $_POST['campo_'.$oper.'_'.$i];
                                $Lsql = "UPDATE ".$dyalogo_canales_electronicos.".dy_ce_filtros SET id_ce_configuracion = ".$cuentaId.", filtro = ".$tipoCondicion.", condicion = '".$condicion."', descargar_adjuntos = 1, operador = '".$operador."' WHERE id = ".$filtroId;
                                $mysqli->query($Lsql);
                            }
                        }
                    }

                    // Valido de que almenos exista un filtro 
                    $filtroSql = "SELECT id, id_ce_configuracion FROM ".$dyalogo_canales_electronicos.".dy_ce_filtros WHERE id_campana_crm = ".$id_campanas_crm." LIMIT 1";
                    $resFiltro = $mysqli->query($filtroSql);

                    if($resFiltro->num_rows > 0){

                        $filtro = $resFiltro->fetch_array();

                        // Inserto la accion_filtro si aun no se ha creado
                        $Lsql = "SELECT * FROM ".$dyalogo_canales_electronicos.".dy_ce_acciones_filtro WHERE accion = 2 AND id_cola_distribucion = ".$id_campanas_cbx;
                        $respuesta = $mysqli->query($Lsql);
                        
                        if($respuesta->num_rows == 0){
                            $orden = ordenMax($filtro['id_ce_configuracion']);
                            $Lsql = "INSERT INTO ".$dyalogo_canales_electronicos.".dy_ce_acciones_filtro (id_filtro, orden, accion, id_cola_distribucion) VALUES (".$filtro['id'].",".$orden.",2,".$id_campanas_cbx.")";
                            $mysqli->query($Lsql);
                        }

                        // Inserto El mensaje de bienvenida si esta cheackeado
                        if(!is_null($correoConfiguracion->cuerpoBienvenida) && $correoConfiguracion->cuerpoBienvenida != ''){
                            
                            // Insercion o actualizacion
                            $accionLsql = "SELECT * FROM ".$dyalogo_canales_electronicos.".dy_ce_acciones_filtro WHERE accion = 1 AND id_cola_distribucion = ".$id_campanas_cbx;
                            $resAccion = $mysqli->query($accionLsql);

                            if($resAccion->num_rows == 0){
                                $orden = ordenMax($filtro['id_ce_configuracion']);
                                $Isql = "INSERT INTO ".$dyalogo_canales_electronicos.".dy_ce_acciones_filtro (id_filtro, orden, accion, id_cola_distribucion, cuerpo, id_correo_entrante) VALUES (".$filtro['id'].",".$orden.",1,".$id_campanas_cbx.", '".$correoConfiguracion->cuerpoBienvenida."', ".$correoConfiguracion->id.")";
                                $mysqli->query($Isql);
                            }else{
                                $Usql = "UPDATE ".$dyalogo_canales_electronicos.".dy_ce_acciones_filtro SET cuerpo = '".$correoConfiguracion->cuerpoBienvenida."' WHERE accion = 1 AND id_cola_distribucion = ".$id_campanas_cbx;
                                $mysqli->query($Usql);
                            }
                        }else{
                            // Eliminacion
                            $Dsql = "DELETE FROM ".$dyalogo_canales_electronicos.".dy_ce_acciones_filtro WHERE accion = 1 AND id_cola_distribucion = ".$id_campanas_cbx;
                            $mysqli->query($Dsql);
                        }

                    }
                }

                // Aqui ejecuto el generador de flechas automatico para que quede en estcon
                // Necesito traer la estrategia
                $pasoConf = "SELECT ESTPAS_ConsInte__ESTRAT_b AS estrategia FROM {$BaseDatos_systema}.ESTPAS WHERE ESTPAS_ConsInte__b = {$pasoId}";
                $resPaso = $mysqli->query($pasoConf);

                $paso = $resPaso->fetch_object();

                generarFlecha($pasoId, $pasoCampana, $paso->estrategia);

                // Actualizo el estado activo de la flecha en base al check
                $query = "UPDATE {$BaseDatos_systema}.ESTCON SET ESTCON_Activo_b = {$activo} WHERE ESTCON_ConsInte__ESTPAS_Des_b = {$pasoId} AND ESTCON_ConsInte__ESTPAS_Has_b = {$pasoToId}";
                $mysqli->query($query);
    
                echo json_encode([
                    "estado" => "ok",
                    "mensaje" => "Se ha actualizado los datos correctamente"
                ]);
            }else{
                echo json_encode([
                    "estado" => "fallo",
                    "mensaje" => "Antes de guardar el registro debes haber configurado el paso de correo entrante"
                ]);
            }

        }

        if(isset($_GET['getFiltros'])){
            $pasoEmail = $_POST['pasoFrom'];
            $pasoCampana = $_POST['pasoTo'];

            // Busco si hay configuracion en CORREO_ENTRANTE
            $sql = "SELECT CORREO_ENTRANTE_ConsInte__b AS id, CORREO_ENTRANTE_Nombre_b AS nombre, CORREO_ENTRANTE_Cuenta_b AS cuenta, CORREO_ENTRANTE_AutorespuestaCuerpo_b AS cuerpoBienvenida FROM {$BaseDatos_systema}.CORREO_ENTRANTE WHERE CORREO_ENTRANTE_ConsInte__ESTPAS_b = {$pasoEmail}";
            $res = $mysqli->query($sql);

            if($res->num_rows > 0){

                $correoConf = $res->fetch_object();

                // Traigo la configuracion del canal de correo seleccionado
                $sqlCanalCorreo = "SELECT id, nombre, direccion_correo_electronico FROM {$dyalogo_canales_electronicos}.dy_ce_configuracion WHERE id = $correoConf->cuenta";
                $resCanal = $mysqli->query($sqlCanalCorreo);
                $canalCorreo = $resCanal->fetch_object();

                // Necesito traer tambien la CAMPANA para poder buscar los filtros
                $sqlCampana = "SELECT CAMPAN_ConsInte__b AS id, CAMPAN_IdCamCbx__b AS id_campana_cbx FROM {$BaseDatos_systema}.CAMPAN 
                    JOIN {$BaseDatos_systema}.ESTPAS ON CAMPAN_ConsInte__b = ESTPAS_ConsInte__CAMPAN_b
                    WHERE ESTPAS_ConsInte__b = {$pasoCampana}";
                
                $resCampana = $mysqli->query($sqlCampana);
                $campanaConfiguracion = $resCampana->fetch_object();

                // Ahora si vienen los filtros
                $sqlFiltros = "SELECT * FROM {$dyalogo_canales_electronicos}.dy_ce_filtros WHERE id_ce_configuracion = {$canalCorreo->id} AND id_campana_crm = {$campanaConfiguracion->id}";
                $resFiltros = $mysqli->query($sqlFiltros);

                $filtros = [];

                $i = 0;
                while ($row = $resFiltros->fetch_object()) {
                    $filtros[$i] = $row;
                    $i++;
                }

                // Traigo la configuracion de la flecha
                $query = "SELECT ESTCON_ConsInte__b AS id, ESTCON_Activo_b AS activo FROM {$BaseDatos_systema}.ESTCON WHERE ESTCON_ConsInte__ESTPAS_Des_b = {$pasoEmail} AND ESTCON_ConsInte__ESTPAS_Has_b = {$pasoCampana}";
                $resEstcon = $mysqli->query($query);

                $estcon = null;
                if($resEstcon && $resEstcon->num_rows > 0){
                    $estcon = $resEstcon->fetch_object();
                }

                echo json_encode([
                    "estado" => "ok",
                    "configCorreo" => $correoConf,
                    "canalCorreo" => $canalCorreo,
                    "filtros" => $filtros,
                    "flecha" => $estcon
                ]);

            }else{
                echo json_encode([
                    "estado" => "fallo",
                    "mensaje" => "Debes realizar primero la configuracion en el paso de correo entrante"
                ]);
            }

        }

        if(isset($_GET['deleteFiltro'])){

            $filtroId = $_POST['filtroId'];

            $Lsql = "SELECT id, id_campana_crm FROM ".$dyalogo_canales_electronicos.".dy_ce_filtros WHERE id = ".$filtroId;
            $res = $mysqli->query($Lsql);
            if($res->num_rows > 0 ){
                $data = $res->fetch_array();

                // Verifico si hay alguna accion filtro con id_filtro que eliminare
                $accion = "SELECT id FROM ".$dyalogo_canales_electronicos.".dy_ce_acciones_filtro WHERE id_filtro = ".$filtroId;
                $resAccion = $mysqli->query($accion);

                if($resAccion->num_rows == 0){
                    $Lsql = "DELETE FROM ".$dyalogo_canales_electronicos.".dy_ce_filtros WHERE id = ".$filtroId;
                    $mysqli->query($Lsql);

                    echo json_encode(array('message' => 'Eliminado', 'eliminado' => true));
                }else{

                    // Si hay una accion con el id_filtro a eliminar cambio el id_filtro por otro de la misma campana
                    $filtrosLsql = "SELECT id FROM ".$dyalogo_canales_electronicos.".dy_ce_filtros WHERE id_campana_crm = ".$data['id_campana_crm']." AND id != ".$data['id'];
                    $filtrosCampana = $mysqli->query($filtrosLsql);
                    if($filtrosCampana->num_rows > 0){
                        $filtroCamp = $filtrosCampana->fetch_array();

                        $Lsql = "UPDATE ".$dyalogo_canales_electronicos.".dy_ce_acciones_filtro SET id_filtro = ".$filtroCamp['id']." WHERE id_filtro = ".$filtroId;
                        $mysqli->query($Lsql);

                        $Lsql = "DELETE FROM ".$dyalogo_canales_electronicos.".dy_ce_filtros WHERE id = ".$filtroId;
                        $mysqli->query($Lsql);

                        echo json_encode(array('message' => 'Eliminado', 'eliminado' => true));
                    }else{
                        echo json_encode(array('message' => 'Debe haber por lo menos un filtro', 'eliminado' => false));
                    }
                }
            }
        }

        if(isset($_GET['deleteTodosFiltros'])){
            $pasoId = $_POST['pasoId'];

            // Primero tengo que borrar las acciones filtros
            $deleteAccFiltro = "DELETE FROM {$dyalogo_canales_electronicos}.dy_ce_acciones_filtro WHERE id_filtro IN (
                SELECT id FROM {$dyalogo_canales_electronicos}.dy_ce_filtros WHERE id_estpas = {$pasoId}
            )";
            $mysqli->query($deleteAccFiltro);

            // Luego si borrar los filtros
            $deleteFiltros = "DELETE FROM {$dyalogo_canales_electronicos}.dy_ce_filtros WHERE id_estpas = {$pasoId}";
            $mysqli->query($deleteFiltros);

            echo json_encode(array('message' => 'Eliminado', 'eliminado' => true));
        }
    }

    // Esto para traer el orden actual de accion_filtro
    function ordenMax($idConfiguracion){
        global $mysqli;
        global $dyalogo_canales_electronicos;

        $maxSql = "SELECT max(a.orden) as orden FROM dyalogo_canales_electronicos.dy_ce_acciones_filtro a JOIN dyalogo_canales_electronicos.dy_ce_filtros f ON a.id_filtro = f.id WHERE f.id_ce_configuracion = ".$idConfiguracion;
        $res = $mysqli->query($maxSql);

        if($res->num_rows > 0){
            $respuesta = $res->fetch_array();

            return ($respuesta['orden'] + 1);
        }else{
            return 1;
        }
    }

    function generarFlecha($pasoFrom, $pasoTo, $estrategia){
        global $mysqli;

        // Busco si ya existe una flecha configurada con anterioridad
        $sqlFlechas = "SELECT * FROM DYALOGOCRM_SISTEMA.ESTCON WHERE ESTCON_ConsInte__ESTPAS_Des_b = {$pasoFrom} AND ESTCON_ConsInte__ESTPAS_Has_b = $pasoTo";
        $resFlechas = $mysqli->query($sqlFlechas);
        if($resFlechas && $resFlechas->num_rows > 0){
            return false;
        }else{
            $insertFlecha = "INSERT INTO DYALOGOCRM_SISTEMA.ESTCON (ESTCON_Nombre____b, ESTCON_ConsInte__ESTPAS_Des_b, ESTCON_ConsInte__ESTPAS_Has_b, ESTCON_FromPort_b, ESTCON_ToPort_b, ESTCON_ConsInte__ESTRAT_b, ESTCON_Tipo_Consulta_b, ESTCON_Tipo_Insercion_b,ESTCON_ConsInte_PREGUN_Fecha_b,ESTCON_ConsInte_PREGUN_Hora_b,ESTCON_Operacion_Fecha_b,ESTCON_Operacion_Hora_b,ESTCON_Cantidad_Fecha_b,ESTCON_Cantidad_Hora_b,ESTCON_Estado_cambio_b,ESTCON_Sacar_paso_anterior_b,ESTCON_resucitar_registro) VALUES ('conector', {$pasoFrom}, {$pasoTo}, 'R', 'L', {$estrategia} , 1,0,-1,-1,1,1,0,0,0,0,0)";
            $insert = $mysqli->query($insertFlecha);

            return true;
        }
        
    }

?>