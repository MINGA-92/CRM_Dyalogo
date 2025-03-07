<?php
session_start();
ini_set('display_errors', 'On');
ini_set('display_errors', 1);
include_once(__DIR__."../../../../pages/conexion.php");
include_once(__DIR__."../../../../global/GeneradorDeFlechas.php");
require_once(__DIR__."../../../../xlsxwriter/xlsxwriter.class.php");
include_once(__DIR__."../../../../global/GenerarGuion.php");

// Exportamos el excel
if(isset($_POST['pn_exportar'])){

    $pasoId = $_POST['pn_export_excel_paso_id'];
    $seccionId = $_POST['pn_export_excel_paso_seccion'];
    $ejecutor = $_POST['pn_export_excel_paso_ejecutor'];

    $nombreHoja = "Preguntas que el bot no pudo entregar";
    $estilos = ['font'=>'Arial','font-size'=>10,'fill'=>'#20BEE8','color' => '#FFFF','halign'=>'center'];

    // Traemos los registros
    
    if($ejecutor == 'bot'){
        $sql = "SELECT a.pregunta, c.nombre, a.fecha_hora, a.contador + 1 AS contador FROM {$dyalogo_canales_electronicos}.preguntas_sin_respuesta_bot a
            INNER JOIN {$dyalogo_canales_electronicos}.dy_base_autorespuestas b ON a.id_base_autorespuesta = b.id
            INNER JOIN {$dyalogo_canales_electronicos}.secciones_bot c ON c.id = b.id_seccion
        WHERE b.id_estpas = {$pasoId} and a.fecha_hora > '2023-01-01 00:00:00' ORDER BY a.fecha_hora DESC LIMIT 4500";
        $columnas = [
            'Texto que escribió el cliente' => 'string',
            'Sección del Bot donde se escribió' => 'string',
            'Ultima fecha en que escribieron esto' => 'string',
            'Cantidad de veces que lo han escrito' => 'string'
        ];
    }else{
        $sql = "SELECT pregunta, fecha_hora, contador + 1 AS contador FROM {$dyalogo_canales_electronicos}.preguntas_sin_respuesta_bot WHERE id_base_autorespuesta = {$seccionId} and a.fecha_hora > '2023-01-01 00:00:00' ORDER BY fecha_hora DESC LIMIT 4500";
        $columnas = [
            'Texto que escribió el cliente' => 'string',
            'Ultima fecha en que escribieron esto' => 'string',
            'Cantidad de veces que lo han escrito' => 'string'
        ];
    }

    $res = $mysqli->query($sql);

    $writer = new XLSXWriter();
    $writer->writeSheetHeader($nombreHoja, $columnas, $estilos);

    if($res && $res->num_rows > 0){
        while($row = $res->fetch_object()){
            $writer->writeSheetRow($nombreHoja, (array)$row);
        }
    }

    $fechaActual = date("Y-m-d H:i:s");

    // Buscamos el nombre del bot/seccion para darle el nombre al archivo
    if($ejecutor == 'bot'){
        $sql = "SELECT id, nombre FROM {$dyalogo_canales_electronicos}.dy_bot WHERE id_estpas = {$pasoId} LIMIT 1";
        $nombreEjecutor = "el_BOT";
    }else{
        $sql = "SELECT a.id, b.nombre FROM {$dyalogo_canales_electronicos}.dy_base_autorespuestas a
            INNER JOIN {$dyalogo_canales_electronicos}.secciones_bot b ON b.id = a.id_seccion
        WHERE a.id_estpas = {$pasoId} LIMIT 1";
        $nombreEjecutor = "la_SECCION";
    }

    $resSeccion = $mysqli->query($sql);

    $nombreBotSeccion = '';

    if($resSeccion && $resSeccion->num_rows > 0){
        $dataBotSec = $resSeccion->fetch_object();
        $nombreBotSeccion = str_replace(' ', '_', $dataBotSec->nombre);
    }

    $fileName = 'Respuestas_que_' . $nombreEjecutor .'_' . $nombreBotSeccion . '_no_pudo_entregar' . $fechaActual;

    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    header('Content-Disposition: attachment;filename="'.$fileName.'.xlsx"');
    header('Cache-Control: max-age=0');

    $writer->writeToStdOut();
    exit(0);
    
}

if(!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest'){

    // Configuraciones Iniciales

    if(isset($_GET['getPasoEsNuevo'])){

        $pasoId = $mysqli->real_escape_string($_GET['id_paso']);

        //Verifico que el paso tenga secciones creadas, si no creo las secciones(configuracion) por defecto
        $sql = "SELECT * FROM {$dyalogo_canales_electronicos}.secciones_bot WHERE id_estpas = {$pasoId}";
        $res = $mysqli->query($sql);

        $esNuevo = true;

        // Valido si el paso es nuevo viendo si tiene secciones en el bot
        if($res->num_rows > 0){
            $esNuevo = false;
        }

        echo json_encode([
            "esNuevo" => $esNuevo,
            "paso" => $pasoId
        ]);
    }

    if(isset($_GET['guardarConfiguracionInicial'])){

        $pasoId = $mysqli->real_escape_string($_GET['id_paso']);
        $huesped = $mysqli->real_escape_string($_GET['huesped']);
        $nombre = $mysqli->real_escape_string($_POST['configuracionInicialBotNombre']);        
        // $tipoSeccionBienvenida = $mysqli->real_escape_string($_POST['configuracionInicialBotTipoSeccion']);

        $sql = "INSERT INTO {$dyalogo_canales_electronicos}.dy_bot (nombre, id_estpas) VALUES ('{$nombre}', {$pasoId})";
        
        if($mysqli->query($sql)){

            $botId = $mysqli->insert_id;

            insertarSeccionPorDefecto($pasoId, $huesped, 1, $nombre, $botId);

            generarTablaGestiones($botId, $pasoId);

            echo json_encode([
                "respuesta" => "Guardado con exito"
            ]);
        }else{
            echo json_encode([
                "respuesta" => "Se presento un error al tratar de crear el bot"
            ]);
        }
    }


    // Consultas del paso
    if(isset($_GET['getDatosPaso'])){

        $pasoId = $mysqli->real_escape_string($_GET['id_paso']);
        $huesped = $mysqli->real_escape_string($_GET['huesped']);

        // Traigo los datos de las bolas
        $sqlPaso = "SELECT ESTPAS_ConsInte__b AS id, ESTPAS_Comentari_b AS nombre, ESTPAS_activo AS activo, ESTPAS_ConsInte__ESTRAT_b AS estrategia FROM {$BaseDatos_systema}.ESTPAS WHERE ESTPAS_ConsInte__b = {$pasoId} LIMIT 1";
        $resPaso = $mysqli->query($sqlPaso);
        $paso = $resPaso->fetch_object();
    
        // Traigo tambien todas las secciones del bot, si tiene
        $secciones = [];
        $sqlSecciones = "SELECT s.*, a.id AS autorespuestaId, a.frase_no_encuentra_respuesta, a.timeout_cliente, a.frase_timeout, a.id_pregunta_busqueda_ani AS busquedaAniBot, a.tipo_base FROM {$dyalogo_canales_electronicos}.secciones_bot s 
            LEFT JOIN {$dyalogo_canales_electronicos}.dy_base_autorespuestas a ON a.id_seccion = s.id 
            WHERE s.id_estpas = {$pasoId} GROUP BY a.id_seccion having a.id_seccion is not null";
        $resSec = $mysqli->query($sqlSecciones);
        
        if($resSec->num_rows > 0){
            $i = 0;
            $tamanno = $resSec->num_rows;
            while ($row = $resSec->fetch_object()) {
                $secciones[$i] = $row;
                $i++;
            }
        }

        // Traigo las conexiones de las secciones del bot
        $conexiones = [];
        $sqlConexiones = "SELECT * FROM {$dyalogo_canales_electronicos}.secciones_conexiones_bot WHERE id_estpas = {$pasoId}";
        $resConexiones = $mysqli->query($sqlConexiones);

        if($resConexiones && $resConexiones->num_rows > 0){
            $i = 0;
            while ($row = $resConexiones->fetch_object()) {
                $conexiones[$i] = $row;
                $i++;
            }
        }

        // Pasos externos
        $pasosExternos = [];
        $sqlPasosExternos = "SELECT * FROM {$dyalogo_canales_electronicos}.secciones_bot WHERE id_estpas = {$pasoId} AND (tipo_seccion = 24 OR tipo_seccion = 25)";
        $resPasosExternos = $mysqli->query($sqlPasosExternos);
    
        if($resPasosExternos->num_rows > 0){
            $i = 0;
            $tamanno = $resPasosExternos->num_rows;
            while ($row = $resPasosExternos->fetch_object()) {

                if($row->tipo_seccion == 24 && !is_null($row->id_paso_externo)){
                    $sql = "SELECT CAMPAN_ConsInte__b AS id, CAMPAN_Nombre____b AS nombre FROM DYALOGOCRM_SISTEMA.CAMPAN WHERE CAMPAN_IdCamCbx__b = {$row->id_paso_externo} LIMIT 1";
                    $res = $mysqli->query($sql);

                    if($res && $res->num_rows > 0){
                        $campan = $res->fetch_object();
                        $row->nombre = $campan->nombre;
                    }
                }

                // Si es bot traemos el nombre actual
                if($row->tipo_seccion == 25 && !is_null($row->id_paso_externo)){
                    $sql = "SELECT ESTPAS_ConsInte__b AS id, ESTPAS_Comentari_b AS nombre FROM DYALOGOCRM_SISTEMA.ESTPAS a INNER JOIN dyalogo_canales_electronicos.dy_bot b ON a.ESTPAS_ConsInte__b = b.id_estpas WHERE b.id = {$row->id_paso_externo} LIMIT 1";
                    $res = $mysqli->query($sql);

                    if($res && $res->num_rows > 0){
                        $estpas = $res->fetch_object();
                        $row->nombre = $estpas->nombre;
                    }
                }

                $pasosExternos[$i] = $row;
                $i++;
            }
        }

        // Obtengo la lista de pasos a los cuales se pueden ejecutar o pasar registros
        $pasosEjecutables = [];

        $sql = "SELECT ESTPAS_ConsInte__b AS id, ESTPAS_Comentari_b AS nombre FROM {$BaseDatos_systema}.ESTPAS WHERE ESTPAS_Tipo______b IN (6, 7, 8, 9, 13) AND ESTPAS_ConsInte__ESTRAT_b = {$paso->estrategia}";
        $resPasos = $mysqli->query($sql);
        
        if($resPasos && $resPasos->num_rows > 0){
            while($row = $resPasos->fetch_object()){
                $pasosEjecutables[] = $row;
            }
        }

        // Traemos los pasos de timeout de bot
        $arrPasosTimeout = [];

        $sql = "SELECT a.id AS id, a.id_estpas AS pasoId FROM {$dyalogo_canales_electronicos}.dy_base_autorespuestas_contenidos a INNER JOIN {$dyalogo_canales_electronicos}.dy_bot b ON a.id_base_autorespuestas = b.id_seccion_timeout WHERE b.id_estpas = {$pasoId} AND b.id_seccion_timeout IS NOT NULL";

        $resPasosTimeout = $mysqli->query($sql);
        if($resPasosTimeout && $resPasosTimeout->num_rows > 0){
            while ($row = $resPasosTimeout->fetch_object()) {
                $arrPasosTimeout[] = $row->pasoId;
            }
        }

        // Traigo la configuracion de la accion NoRespuesta
        $configuracionAccionNoRespuesta = null;

        $sql = "SELECT a.id, a.accion, a.id_campana, a.id_base_transferencia, a.id_bot_transferido FROM {$dyalogo_canales_electronicos}.dy_base_autorespuestas_contenidos a 
            INNER JOIN {$dyalogo_canales_electronicos}.dy_bot b ON a.id_base_autorespuestas = b.id_base_ar_sin_respuesta WHERE b.id_estpas = {$pasoId} AND b.id_base_ar_sin_respuesta IS NOT NULL AND pregunta = 'DY_ACCION_DEFECTO' LIMIT 1";
        $resConfNoRespuesta = $mysqli->query($sql);
        if($resConfNoRespuesta && $resConfNoRespuesta->num_rows > 0){
            $configuracionAccionNoRespuesta = $resConfNoRespuesta->fetch_object();
        }

        // Se obtiene la lista de secciones del bot actual
        $listaSecciones = getAllSeccionesByPasoId($pasoId);
        $listaBots = [];
        $listaCampanas = [];

        // Traigo la lista de bots de la estrategia
        $sqlListaBots = "select a.id, a.nombre from {$dyalogo_canales_electronicos}.dy_bot as a
            JOIN {$BaseDatos_systema}.ESTPAS b ON b.ESTPAS_ConsInte__b = a.id_estpas
            WHERE b.ESTPAS_ConsInte__ESTRAT_b = {$paso->estrategia} AND b.ESTPAS_ConsInte__b != {$pasoId}";
        
        $resBots = $mysqli->query($sqlListaBots);
        if($resBots->num_rows > 0){
            $i = 0;
            while($row = $resBots->fetch_object()){
                $listaBots[$i] = $row;
                $i++;
            }
        }

        // Traigo la lista de campanas de la estrategia
        $sqlCampana = "SELECT ESTPAS_ConsInte__CAMPAN_b AS id_campan ,B.CAMPAN_IdCamCbx__b AS dy_campan, ESTPAS_Comentari_b AS nombre FROM DYALOGOCRM_SISTEMA.ESTPAS 
            LEFT JOIN DYALOGOCRM_SISTEMA.CAMPAN B ON ESTPAS_ConsInte__CAMPAN_b = CAMPAN_ConsInte__b 
            WHERE ESTPAS_ConsInte__ESTRAT_b = {$paso->estrategia} AND ESTPAS_Tipo______b =1 AND ESTPAS_ConsInte__CAMPAN_b IS NOT NULL";
        $resCampana = $mysqli->query($sqlCampana);
        if($resCampana->num_rows > 0){
            $i = 0;
            while($row = $resCampana->fetch_object()){
                $listaCampanas[$i] = $row;
                $i++;
            }
        }

        echo json_encode([
            "paso" => $paso,
            "secciones" => $secciones,
            "conexiones" => $conexiones,
            "pasosExternos" => $pasosExternos,
            "pasosEjecutables" => $pasosEjecutables,
            "pasosTimeout" => $arrPasosTimeout,
            "listaBots" => $listaBots,
            "listaCampanas" => $listaCampanas,
            "listaSecciones" => $listaSecciones,
            "configuracionAccionNoRespuesta" => $configuracionAccionNoRespuesta
        ]);
    }

    // Guarda el menu principal de secciones
    if(isset($_GET['guardar'])){
        
        $pasoId = $mysqli->real_escape_string($_GET['id_paso']);
        $nombreBot = $mysqli->real_escape_string($_POST['nombre_bot']);
        $textoNoRespuesta = $mysqli->real_escape_string($_POST['dyTr_textoNoRespuesta']);
        $iniciador = $mysqli->real_escape_string($_GET['iniciador']);

        $timeout = $mysqli->real_escape_string($_POST['timeoutBot']);
        $fraseTimeout = $mysqli->real_escape_string($_POST['dyTr_timeoutBotFrase']);

        $flujograma = traducirFljujograma($_POST['mySavedModelBot']);

        // Actualizo el paso
        $sqlPaso = "UPDATE {$BaseDatos_systema}.ESTPAS SET ESTPAS_Comentari_b='{$nombreBot}', ESTPAS_activo = -1 WHERE ESTPAS_ConsInte__b = {$pasoId}";
        $mysqli->query($sqlPaso);

        // Acualizo el bot 
        $sqlBot = "UPDATE {$dyalogo_canales_electronicos}.dy_bot SET nombre = '{$nombreBot}' WHERE id_estpas = {$pasoId}";
        
        // Actualizo primero la respuesta del bot
        $updateNoRespuesta = "UPDATE {$dyalogo_canales_electronicos}.dy_base_autorespuestas SET frase_no_encuentra_respuesta = '{$textoNoRespuesta}', timeout_cliente = {$timeout}, frase_timeout = '{$fraseTimeout}' WHERE id_estpas = {$pasoId}";
        $mysqli->query($updateNoRespuesta);

        // Actualizar los pasos que se ejecutaran despues del timeout
        if(isset($_POST['actionAfterTimeout'])){
            actualizarPasosTimeout($_POST['actionAfterTimeout'], $pasoId);
        }else{
            actualizarPasosTimeout(null, $pasoId);
        }

        // Actualizar la accion despues del noRespuesta
        $dataNoRespuesta = [
            "accion" => $_POST['accion_NoRes'] ?? 0,
            "seccionId" => $_POST['bot_seccion_NoRes'] ?? 0,
            "botId" => $_POST['bot_NoRes']?? 0,
            "botSeccionId" => $_POST['seccion_bot_NoRes']?? 0,
            "campanaId" => $_POST['campan_NoRes']?? 0
        ];

        actualizarAccionNoRespuesta($pasoId, $dataNoRespuesta);

        // Actualizo la ubicacion de las bolas del bot
        if(count($flujograma->nodeDataArray) > 0){

            foreach ($flujograma->nodeDataArray as $key => $value) {
                $sql = "UPDATE {$dyalogo_canales_electronicos}.secciones_bot SET loc = '{$value->loc}' WHERE id = {$value->key}";
                $mysqli->query($sql);
            }

        }

        // Proceso de guardado o actualizacion de flechas
        if(count($flujograma->linkDataArray) > 0){

            foreach ($flujograma->linkDataArray as $key => $value) {
                
                // Primero verifico que ya exista
                $sqlExiste = "SELECT * FROM {$dyalogo_canales_electronicos}.secciones_conexiones_bot WHERE desde = {$value->from} AND hasta = {$value->to}";
                $resExiste = $mysqli->query($sqlExiste);

                // Capturo el nombre del texto si es que tiene
                $nombre = '';
                if(isset($value->text)){
                    $nombre = $value->text;
                }

                $coordenadas = json_encode($value->points);

                // Si es mayor a cero significa que existe
                if($resExiste && $resExiste->num_rows > 0){
                    $sqlConexion = "UPDATE {$dyalogo_canales_electronicos}.secciones_conexiones_bot SET nombre = '{$nombre}', from_port = '{$value->fromPort}', to_port = '{$value->toPort}', coordenadas = '{$coordenadas}' WHERE desde = {$value->from} AND hasta = {$value->to}";
                }else{
                    $sqlConexion = "INSERT INTO {$dyalogo_canales_electronicos}.secciones_conexiones_bot (nombre, desde, hasta, coordenadas, from_port, to_port, id_estpas) 
                    VALUES ('{$nombre}', {$value->from}, {$value->to}, '{$coordenadas}', '{$value->fromPort}', '{$value->toPort}', {$pasoId})";
                }
                if($mysqli->query($sqlConexion) !== true){
                    echo "Error al insertar la flecha";
                }
            }


        }

        // Genero la tabla de gestiones, esto por si se ha creado un nuevo campo
        if($iniciador == 'botonSave'){
            generarTablaGestiones(null, $pasoId);
        }
        
        echo json_encode([
            "estado" => "ok",
            "flujo" => $flujograma
        ]);
    }

    // Crea una nueva seccion
    if(isset($_GET['crearNuevaSeccion'])){

        $pasoId = $_GET['id_paso'];
        $huespedId = $_GET['huesped'];
        $fraseNoRespuesta = $_POST['dyTr_textoNoRespuesta'];

        $timeout = $mysqli->real_escape_string($_POST['timeoutBot']);
        $fraseTimeout = $mysqli->real_escape_string($_POST['dyTr_timeoutBotFrase']);
        
        $flujograma = traducirFljujograma($_POST['mySavedModelBot']);

        // Recorro el flujograma hasta que encuentre un key negativo, esa seria la nueva bola
        if(count($flujograma->nodeDataArray) > 0){

            // Traigo el bot
            $sql = "SELECT * FROM {$dyalogo_canales_electronicos}.dy_bot WHERE id_estpas = {$pasoId} LIMIT 1";
            $resBot = $mysqli->query($sql);

            $datosBot = $resBot->fetch_object();

            foreach ($flujograma->nodeDataArray as $key => $value) {
                
                // Si la key es un valor negativo realizo la creacion
                if($value->key < 0){

                    //Insertar en seccion_bot
                    $sql = "INSERT INTO {$dyalogo_canales_electronicos}.secciones_bot (nombre, id_estpas, tipo_seccion, orden, loc, id_bot) 
                    VALUES ('Nueva seccion {$value->category} {$pasoId}', {$pasoId}, {$value->tipoPaso}, 0, '{$value->loc}', {$datosBot->id})";
                    $res1 = $mysqli->query($sql);

                    $seccionId = $mysqli->insert_id;
                    $baseAutorespuestaId = 0;
                    $res2 = true;

                    //Insertar en dy_autorespuestas si es conversacional
                    if($value->tipoPaso == '1'){
                        $sql2 = "INSERT INTO {$dyalogo_canales_electronicos}.dy_base_autorespuestas (nombre, id_huesped, id_estpas, tipo_base, id_seccion, frase_no_encuentra_respuesta, id_bot, timeout_cliente, frase_timeout) 
                            VALUES ('Nueva seccion {$value->category} {$pasoId}', {$huespedId}, {$pasoId}, 1, {$seccionId}, '{$fraseNoRespuesta}', {$datosBot->id}, {$timeout}, '{$fraseTimeout}')";
                        $res2 = $mysqli->query($sql2);

                        if(!$res2){
                            echo $mysqli->error;
                        }
                
                        $baseAutorespuestaId = $mysqli->insert_id;
                    }

                    // Si es transaccional
                    if($value->tipoPaso == '2'){

                        // Creo la autorrespuesta principal
                        $sql = "INSERT INTO {$dyalogo_canales_electronicos}.dy_base_autorespuestas (nombre, id_huesped, id_estpas, tipo_base, id_seccion, frase_no_encuentra_respuesta, id_bot, timeout_cliente, frase_timeout)
                            VALUES ('Nueva seccion {$value->category} {$pasoId}', {$huespedId}, {$pasoId}, 0, {$seccionId}, '{$fraseNoRespuesta}', {$datosBot->id}, {$timeout}, '{$fraseTimeout}')";
                        $mysqli->query($sql);

                        $autorespuestaIdPorDefecto = $mysqli->insert_id;
                        $baseAutorespuestaId = $autorespuestaIdPorDefecto;

                        // Se crea una seccion por defecto para las condiciones
                        $sql = "INSERT INTO {$dyalogo_canales_electronicos}.dy_base_autorespuestas (nombre, id_huesped, id_estpas, tipo_base, id_seccion, frase_no_encuentra_respuesta, id_bot, timeout_cliente, frase_timeout)
                        VALUES ('Nueva seccion {$value->category} {$pasoId}', {$huespedId}, {$pasoId}, 4, {$seccionId}, '{$fraseNoRespuesta}', {$datosBot->id}, {$timeout}, '{$fraseTimeout}')";
                        $mysqli->query($sql);

                        $autorespuestaIdCondiciones = $mysqli->insert_id;

                        // Creo la autores_contenido de la accion final
                        $sqlInsertAccionFinalDefecto = "INSERT INTO {$dyalogo_canales_electronicos}.dy_base_autorespuestas_contenidos (id_base_autorespuestas, pregunta, tags, respuesta, accion, orden, orden_finalizacion) 
                            VALUES ({$autorespuestaIdCondiciones}, 'DY_ACCION_DEFECTO', 'cancelar', '', 2, 1, 1)";
                        $mysqli->query($sqlInsertAccionFinalDefecto);
                    }

                }

            }

        }

        echo json_encode([
            "res1" => $res1,
            "res2" => $res2,
            "seccionId" => $seccionId,
            "baseAutorespuestaId" => $baseAutorespuestaId,
        ]);
    }

    // Vuelve a traer las secciones del paso
    if(isset($_GET['recargarFlujograma'])){

        $pasoId = $_GET['id_paso'];

        // Traigo tambien todas las secciones del bot, si tiene
        $secciones = [];
        $sqlSecciones = "SELECT s.*, a.id AS autorespuestaId, a.frase_no_encuentra_respuesta, a.id_pregunta_busqueda_ani AS busquedaAniBot, a.tipo_base FROM {$dyalogo_canales_electronicos}.secciones_bot s 
            LEFT JOIN {$dyalogo_canales_electronicos}.dy_base_autorespuestas a ON a.id_seccion = s.id 
            WHERE s.id_estpas = {$pasoId} GROUP BY a.id_seccion having a.id_seccion is not null";
        $resSec = $mysqli->query($sqlSecciones);
    
        if($resSec->num_rows > 0){
            $i = 0;
            while ($row = $resSec->fetch_object()) {
                $secciones[$i] = $row;
                $i++;
            }
        }

        // Traigo las conexiones de las secciones del bot
        $conexiones = [];
        $sqlConexiones = "SELECT * FROM {$dyalogo_canales_electronicos}.secciones_conexiones_bot WHERE id_estpas = {$pasoId}";
        $resConexiones = $mysqli->query($sqlConexiones);

        if($resConexiones && $resConexiones->num_rows > 0){
            $i = 0;
            while ($row = $resConexiones->fetch_object()) {
                $conexiones[$i] = $row;
                $i++;
            }
        }

        // Pasos externos
        $pasosExternos = [];
        $sqlPasosExternos = "SELECT * FROM {$dyalogo_canales_electronicos}.secciones_bot WHERE id_estpas = {$pasoId} AND (tipo_seccion = 24 OR tipo_seccion = 25)";
        $resPasosExternos = $mysqli->query($sqlPasosExternos);
    
        if($resPasosExternos->num_rows > 0){
            $i = 0;
            $tamanno = $resPasosExternos->num_rows;
            while ($row = $resPasosExternos->fetch_object()) {
                $pasosExternos[$i] = $row;
                $i++;
            }
        }

        echo json_encode([
            "secciones" => $secciones,
            "conexiones" => $conexiones,
            "pasosExternos" => $pasosExternos
        ]);
    }

    if(isset($_GET['borrar_seccion'])){

        $seccionId = $_POST["seccionId"];
        $autorespuestaId = $_POST['autorespuestaId'];
        $tipoSeccion = $_POST['tipoSeccion'];

        // si el tipo de seccion es 3 tambien debo borrar datos adicionales
        if($tipoSeccion == 3){

            // borro los datos de la tablas de ws_bot_parametros
            $sqlDeleteParametros = "DELETE FROM {$dyalogo_canales_electronicos}.dy_bot_ws_parametros WHERE id_bot_ws IN (SELECT id FROM {$dyalogo_canales_electronicos}.dy_bot_ws WHERE id_base_autorespuestas = {$autorespuestaId})";
            $mysqli->query($sqlDeleteParametros);

            // borro los datos de la tabla ws_bot
            $sqlDeleteWebservice = "DELETE FROM {$dyalogo_canales_electronicos}.dy_bot_ws WHERE id_base_autorespuestas = {$autorespuestaId}";
            $mysqli->query($sqlDeleteWebservice);
        }
        
        
        // Borro primero autorespuestas contenido
        $sqlContenido = "DELETE FROM {$dyalogo_canales_electronicos}.dy_base_autorespuestas_contenidos WHERE id_base_autorespuestas = {$autorespuestaId}";
        $mysqli->query($sqlContenido);

        // Borro autorespuestas
        $sqlAutorespuestas = "DELETE FROM {$dyalogo_canales_electronicos}.dy_base_autorespuestas WHERE id = {$autorespuestaId}";
        $mysqli->query($sqlAutorespuestas);

        // Borro seccion
        $sqlSeccion = "DELETE FROM {$dyalogo_canales_electronicos}.secciones_bot WHERE id = {$seccionId}";
        $mysqli->query($sqlSeccion);

        echo json_encode([
            "estado" => "ok"
        ]);
    }

    // Secciones

    if(isset($_GET['getdatosSeccion'])){
        
        $pasoId = $mysqli->real_escape_string($_GET['id_paso']);
        $seccionId = $mysqli->real_escape_string($_GET['seccionId']);
        $autorespuestaId = $mysqli->real_escape_string($_GET['autorespuestaId']);
        $huespedId = $mysqli->real_escape_string($_GET['huesped']);

        // Traigo la informacion de la seccion actual

        // seccion bot actual
        $seccion = getSeccion($seccionId);

        // Los campos con sus acciones 
        $campos = getAutorespuestasContenidos($autorespuestaId, $seccion->tipo_seccion);

        // Inicializo variables

        $wsBotSecciones = [];
        $wsParametrosSeccion = [];
        $wsAccion = [];
        $camposAccionesFinales = [];
        $listaBd = [];
        $camposBdActual = [];
        $listaG = [];

        switch ($seccion->tipo_seccion) {
            case '1':
                
                // Traigo datos de la seccion, solo necesito por el momento el id de la bd
                $baseAutorespuesta = getBaseAutorespuestas($autorespuestaId);
                
                break;

            case '2':
                $baseAutorespuesta = getBaseAutorespuestas($autorespuestaId);

                //Traigo los campos de las acciones finales
                $camposAccionesFinales = getAccionesFinales($seccionId);

                // Traigo la lista de campos del la seccionn transaccional
                $campos = getCamposSeccionTransaccional($seccionId);

                // Tambien traigo la lista de webservices configurados en el bot
                $sqlSeccionWebservice = "SELECT a.* FROM {$dyalogo_canales_electronicos}.dy_bot_ws a JOIN {$dyalogo_canales_electronicos}.dy_base_autorespuestas b ON a.id_base_autorespuestas = b.id WHERE b.id_seccion = {$seccionId}";
                $resSeccWebser = $mysqli->query($sqlSeccionWebservice);
                if($resSeccWebser->num_rows > 0){
                    $i = 0;
                    while ($row = $resSeccWebser->fetch_object()) {
                        $wsBotSecciones[$i] = $row;
                        
                        // Traigo los paramentros de cada webservice
                        $sqlparamsws = "SELECT b.id AS id, b.id_parametro, b.tipo_valor, b.valor, p.parametro FROM {$dyalogo_canales_electronicos}.dy_bot_ws_parametros b 
                            JOIN {$BaseDatos_general}.ws_parametros p ON b.id_parametro = p.id WHERE id_bot_ws = {$row->id} AND sentido = 'IN'";
                        $resParamsWs = $mysqli->query($sqlparamsws);

                        if($resParamsWs->num_rows > 0){
                            $j = 0;
                            while ($rowP = $resParamsWs->fetch_object()) {
                                $wsParametrosSeccion[$row->id][$j] = $rowP;
                                $j++;
                            }
                        }

                        $i++;
                    }
                }

                // TODO:CAMBIAR ESTO Traigo la accion que se ejecuta en esa seccion
                // $sqlConsultaAccion = "SELECT * FROM {$dyalogo_canales_electronicos}.dy_base_autorespuestas_contenidos WHERE id_base_autorespuestas = {$autorespuestaId} LIMIT 1";
                // $resConsultaAccion = $mysqli->query($sqlConsultaAccion);

                // if($resConsultaAccion->num_rows > 0){
                //     $wsAccion = $resConsultaAccion->fetch_object();
                // }
                break;

            case '3':
            case '4':

                // Traigo datos de la seccion, solo necesito por el momento el id de la bd
                $baseAutorespuesta = getBaseAutorespuestas($autorespuestaId);
                break;
            
            default:
                # code...
                break;
        }

        // Traigo la informacion de las bd disponibles
        $sqlListaBd = "SELECT GUION__Nombre____b AS nombre, GUION__ConsInte__b AS id FROM {$BaseDatos_systema}.GUION_ WHERE GUION__Tipo______b = 2 AND GUION__ConsInte__PROYEC_b = {$huespedId} ORDER BY GUION__Nombre____b ASC";
        $resListaBd = $mysqli->query($sqlListaBd);

        if($resListaBd->num_rows > 0){
            $i=0;
            while ($row = $resListaBd->fetch_object()) {
                $listaBd[$i] = $row;
                $i++;
            }
        }

        $sqlListaG = "SELECT GUION__ConsInte__b AS id,
        CASE 
            WHEN GUION__Tipo______b = 1 THEN CONCAT('Formulario - ', GUION__Nombre____b)
            WHEN GUION__Tipo______b = 2 THEN CONCAT('BD - ', GUION__Nombre____b)
            WHEN GUION__Tipo______b = 3 THEN CONCAT('Auxiliar - ', GUION__Nombre____b) 
            ELSE GUION__Nombre____b 
            END AS nombre
        FROM {$BaseDatos_systema}.GUION_ WHERE GUION__ConsInte__PROYEC_b = {$huespedId} ORDER BY GUION__Tipo______b ASC, GUION__Nombre____b ASC";
        $resListaG = $mysqli->query($sqlListaG);

        if($resListaG && $resListaG->num_rows > 0){
            $i=0;
            while ($row = $resListaG->fetch_object()) {
                $listaG[$i] = $row;
                $i++;
            }
        }

        // Traigo los campos de la base actual
        $camposBdActual = getCamposBd($baseAutorespuesta['id_base']);

        // Traigo la lista de campos existente en la tabla de gestion
        $camposGestion = [];
        // Me toco extraer primero el bot
        $sql = "SELECT id, id_guion_gestion FROM {$dyalogo_canales_electronicos}.dy_bot WHERE id_estpas = {$pasoId}";
        $resBot = $mysqli->query($sql);
        if($resBot && $resBot->num_rows > 0){

            $bot = $resBot->fetch_object();

            $sql ="SELECT PREGUN_ConsInte__b AS id, PREGUN_Texto_____b AS nombre from DYALOGOCRM_SISTEMA.SECCIO 
                    JOIN DYALOGOCRM_SISTEMA.PREGUN ON SECCIO_ConsInte__b = PREGUN_ConsInte__SECCIO_b
                WHERE SECCIO_Nombre____b = 'DATOS' AND SECCIO_ConsInte__GUION__b = {$bot->id_guion_gestion}";

            $resCamposG = $mysqli->query($sql);

            if($resCamposG && $resCamposG->num_rows > 0){
                
                while ($row = $resCamposG->fetch_object()) {
                    $camposGestion[] = $row;
                }
            }

        }

        // Obtengo la lista de pasos a los cuales se pueden ejecutar o pasar registros
        $pasos = [];

        // A traves del id del paso podemos traer la estrategia
        $sql = "SELECT ESTPAS_ConsInte__b AS id, ESTPAS_ConsInte__ESTRAT_b AS estrategia FROM {$BaseDatos_systema}.ESTPAS WHERE ESTPAS_ConsInte__b = {$pasoId}";
        $resPaso = $mysqli->query($sql);
        $pasoObj = $resPaso->fetch_object();

        $sql = "SELECT ESTPAS_ConsInte__b AS id, ESTPAS_Comentari_b AS nombre FROM {$BaseDatos_systema}.ESTPAS WHERE ESTPAS_Tipo______b IN (6, 7, 8, 9, 13) AND ESTPAS_ConsInte__ESTRAT_b = {$pasoObj->estrategia}";
        $resPasos = $mysqli->query($sql);
        
        if($resPasos && $resPasos->num_rows > 0){
            while($row = $resPasos->fetch_object()){
                $pasos[] = $row;
            }
        }

        echo json_encode([
            "seccion" => $seccion,
            "campos" => $campos,
            "camposAccionesFinales" => $camposAccionesFinales,
            "baseAutorespuesta" => $baseAutorespuesta,
            "wsBotSecciones" => $wsBotSecciones,
            "wsParametrosSeccion" => $wsParametrosSeccion,
            "wsAccion" => $wsAccion, 
            "listaBd" => $listaBd,
            "listaG" => $listaG,
            "camposBdActual" => $camposBdActual,
            "camposGestion" => $camposGestion,
            "pasoEjecutables" => $pasos
        ]);

    }

    if(isset($_GET['guardar_seccion'])){

        $pasoId = $_GET['id_paso'];
        $huespedId = $_GET['huesped'];

        $nombreSeccion = $_POST['seccionNombre'];
        $tipoSeccion = $_POST['tipoSeccion1'];
        $seccionBotId = $_POST['seccionBotId'];
        $autorespuestaId = $_POST['autorespuestaId'];
        $autorespuestaAccionFinalId = $_POST['autorespuestaAccionFinal'];
        $baseSeccion = $_POST['seccionBd'];
        $totalFilasSeccion = $_POST['totalFilasSeccion'];

        // Necesito traer el bot simplemente para obtener el id de la tabla de gestion
        $sql = "SELECT * FROM {$dyalogo_canales_electronicos}.dy_bot WHERE id_estpas = {$pasoId}";
        $resBot = $mysqli->query($sql);

        if($resBot && $resBot->num_rows > 0){
            $bot = $resBot->fetch_object();
        }else{
            $bot = NULL;
        }

        if(isset($_POST['llavePrincipal'])){
            $llavePrincipal = $_POST['llavePrincipal'];
        }else{
            $llavePrincipal = 'NULL';
        }

        // Orden de los campos
        $ordenRows = $_POST['ordenSecciones'];
        $ordenRows = str_replace('row_add_', '', $ordenRows);
        $ordenRows = str_replace('row_edit_', '', $ordenRows);
        $arrOrdenRows = explode(",", $ordenRows);

        // Primero actualizamos el nombre de la seccion
        $sqlSeccion = "UPDATE {$dyalogo_canales_electronicos}.secciones_bot SET nombre = '{$nombreSeccion}' WHERE id = {$seccionBotId}";
        $mysqli->query($sqlSeccion);

        $sqlAutorespuestas = "UPDATE {$dyalogo_canales_electronicos}.dy_base_autorespuestas SET nombre = '{$nombreSeccion}', id_base = {$baseSeccion}, id_base_llave_principal = {$llavePrincipal} WHERE id = {$autorespuestaId}";
        $mysqli->query($sqlAutorespuestas);

        // Convierto todas las flechas que salen del paso para que ese eliminen
        asignarAccionEliminacion($seccionBotId);

        // Si la seccion es conversacional actualizo lo que haya en la accion inicial
        if($tipoSeccion == 1){
            actualizarAccionInicial($autorespuestaId, $seccionBotId);
        }

        // Si es mayor que 0 realizo el proceso de insercion de datos
        if($totalFilasSeccion > 0){

            switch ($tipoSeccion) {

                // Conversacional
                case '1':
                    
                    $posicion = 0;

                    // Recorro las filas 
                    for($i = 0; $i < $totalFilasSeccion; $i++){
                        
                        $oper = isset($_POST['campo_edit_'.$i]) ? 'edit' : 'add';
                        $oper = isset($_POST['campo_del_'.$i]) ? 'del' : $oper;

                        // Traigo los tags
                        $strPgta_t = isset($_POST['tag_'.$oper.'_'.$i]) ? $_POST['tag_'.$oper.'_'.$i] : false;
                        
                        // Accion
                        $strAccion_t = isset($_POST['accion_'.$oper.'_'.$i]) ? $_POST['accion_'.$oper.'_'.$i] : false;
        
                        // Respuesta
                        $strRpta_t = isset($_POST['dyTr_rpta_'.$oper.'_'.$i]) ? $_POST['dyTr_rpta_'.$oper.'_'.$i] : ' ';

                        // Detalle de accion
                        $strCampan_t = (isset($_POST['campan_'.$oper.'_'.$i]) && $_POST['campan_'.$oper.'_'.$i] != '') ? $_POST['campan_'.$oper.'_'.$i] : 'null';
                        $pregun_t = (isset($_POST['pregun_'.$oper.'_'.$i]) && $_POST['pregun_'.$oper.'_'.$i] != '') ? $_POST['pregun_'.$oper.'_'.$i] : 'null';

                        // Esta variable es para saber si la accion es pasar a otra seccion
                        $accionPasarOtraSeccion = false;
                        $strSeccionBot_t = 0;
        
                        if($strAccion_t == '2_1'){
                            $strBot_t = (isset($_POST['bot_seccion_'.$oper.'_'.$i]) && $_POST['bot_seccion_'.$oper.'_'.$i] != '') ? $_POST['bot_seccion_'.$oper.'_'.$i] : 'null';
                            $strAccion_t = "2";
                            $accionPasarOtraSeccion = true;
                        }else if($strAccion_t == '2_2'){
                            $strBot_t = (isset($_POST['bot_'.$oper.'_'.$i]) && $_POST['bot_'.$oper.'_'.$i] != '') ? $_POST['bot_'.$oper.'_'.$i] : 'null';
                            $strSeccionBot_t = (isset($_POST['seccion_bot_'.$oper.'_'.$i]) && $_POST['seccion_bot_'.$oper.'_'.$i] != '') ? $_POST['seccion_bot_'.$oper.'_'.$i] : 'null';
                            $strAccion_t = "2";
                        }else{
                            $strBot_t = (isset($_POST['bot_'.$oper.'_'.$i]) && $_POST['bot_'.$oper.'_'.$i] != '') ? $_POST['bot_'.$oper.'_'.$i] : 'null';
                        }

                        $guardarRespuesta = isset($_POST['guardar_respuesta_'.$oper.'_'.$i]) ? $_POST['guardar_respuesta_'.$oper.'_'.$i] : 0;
        
                        // Sume una posicion si el campo se agrega o edita
                        if($oper == 'edit' || $oper == 'add'){
                            $posicion .= 1;
                        }
        
                        // Arreglo los valores a insertar/actualizar dependiendo del tipo de paso
                        $arrValores = mapeoDeValores($tipoSeccion, 0, $strPgta_t, $strRpta_t, $strAccion_t, $strCampan_t, $strBot_t, $pregun_t, $strSeccionBot_t);
                        
                        if($strPgta_t !== false){

                            // Obtenemos las variables adicionales que usaremos para guardar el dato del campo gestion
                            $usarCampoGestion = isset($_POST['usarCampoGestion'.$i]) ? $_POST['usarCampoGestion'.$i] : 0;
                            $variableCaptura = $_POST['nombre_variable_'.$oper.'_'.$i];
                            $pregunGestionExistente = isset($_POST['pregunGestionExistente_'.$oper.'_'.$i]) ? $_POST['pregunGestionExistente_'.$oper.'_'.$i] : 0;
                            
                            $pregunId = isset($_POST['pregunConver_'.$oper.'_'.$i]) ? $_POST['pregunConver_'.$oper.'_'.$i] : 0;

                            $pregunBdId = 'NULL';
                            $pregunGestionId = 'NULL';
                            $nombreVariable = '';
                            $campoGestionPropio = 0;
                            
                            // Solo guardar en la bd
                            if($guardarRespuesta == 1){
                                $pregunBdId = $pregunId;
                            }else if($guardarRespuesta == 2){
                                // Si es gestion
                                if($usarCampoGestion == 1){
                                    $nombreVariable = $variableCaptura;
                                    $pregunGestionId = verificarCampoPregun($nombreVariable, $bot->id_guion_gestion);
                                    $campoGestionPropio = 1;
                                }else{
                                    $pregunGestionId = $pregunGestionExistente;
                                    // Me toca traer el nombre del campo pregun EL NOMBRE DE LA VARIABLE NO LA NECESITO POR EL MOMENTO
                                    // $sql = "SELECT PREGUN_ConsInte__b AS id, PREGUN_Texto_____b AS nombre FROM {$BaseDatos_systema}.PREGUN WHERE PREGUN_ConsInte__b = {$pregunGestionId}";
                                    // $resPregun = $mysqli->query($sql);
                                    // if($resPregun && $resPregun->num_rows > 0){
                                    //     $pregun = $resPregun->fetch_object();
                                    //     $nombreVariable = $pregun->nombre;
                                    // }else{
                                    //     $nombreVariable = $pregunGestionId;
                                    // }
                                }
                            }else if($guardarRespuesta == 3){
                                $pregunBdId = $pregunId;
                                $nombreVariable = obtenerNombreVariable($pregunId);
                                $pregunGestionId = verificarCampoPregun($nombreVariable, $bot->id_guion_gestion);
                            }

                            $registroId = 0;
                            
                            if($oper == 'add'){
                                //insertar las nuevas preguntas
                                $sqlInsert = "INSERT INTO dyalogo_canales_electronicos.dy_base_autorespuestas_contenidos 
                                        (id_base_autorespuestas, pregunta, tags, respuesta, accion, id_campana, id_base_transferencia, id_pregun, nombre_variable, id_pregun_gestion, pregun_gestion_propio, orden, llave, id_bot_transferido) 
                                        VALUES 
                                        ({$autorespuestaId},'{$arrValores['pregunta']}','{$arrValores['tags']}','{$arrValores['respuesta']}','{$arrValores['accion']}',{$arrValores['id_campana']},{$arrValores['id_seccion']}, {$pregunBdId}, '{$nombreVariable}', {$pregunGestionId}, {$campoGestionPropio}, {$arrValores['orden']}, {$arrValores['llave']}, {$arrValores['id_bot']})";
                                //echo $sqlInsert;
                                $mysqli->query($sqlInsert);
                                $registroId = $mysqli->insert_id;
                            }else if($oper == 'edit'){
                                //actualizar las preguntas
                                $sqlUpdate = "UPDATE dyalogo_canales_electronicos.dy_base_autorespuestas_contenidos SET pregunta='{$arrValores['pregunta']}',tags='{$arrValores['tags']}',respuesta='{$arrValores['respuesta']}',accion='{$arrValores['accion']}',id_campana={$arrValores['id_campana']}, id_base_transferencia = {$arrValores['id_seccion']}, id_bot_transferido = {$arrValores['id_bot']}, id_pregun = {$pregunBdId}, nombre_variable = '{$nombreVariable}', id_pregun_gestion = {$pregunGestionId}, pregun_gestion_propio = {$campoGestionPropio}, orden = {$arrValores['orden']}, llave = {$arrValores['llave']} WHERE id={$_POST['campo_edit_'.$i]}";
                                //echo $sqlUpdate;
                                $mysqli->query($sqlUpdate);

                                $registroId = $_POST['campo_edit_'.$i];
                            }else{
                                //Eliminar preguntas
                                $insert=$mysqli->query("DELETE FROM dyalogo_canales_electronicos.dy_base_autorespuestas_contenidos WHERE id={$_POST['campo_del_'.$i]}");
                            }

                            // Validamos el tipo de respuesta y si llego algun adjunto
                            gestionarAdjuntos($i, $registroId);

                            // Revisamos si llegaron botones para configurar
                            botonesRespuestasRapidas($i, $registroId);

                            // Valido si la accion es campana o pasar a otro bot para crear el paso
                            if($strAccion_t == '1'){
                                crearCampanaExterna($strCampan_t, $pasoId);
                                actualizarFlechas($seccionBotId, $strCampan_t, $pasoId, true, 'campana_externa');
                            }else if($strAccion_t == '2' && !$accionPasarOtraSeccion){
                                crearBotExterno($strBot_t, $pasoId);
                                actualizarFlechas($seccionBotId, $strBot_t, $pasoId, true, 'bot_externo');
                            }
                        }

                        // Valido la accion para realizar la insercion de la flecha
                        if($accionPasarOtraSeccion){
                            actualizarFlechas($seccionBotId, $strBot_t, $pasoId, false);
                        }
                    }
                    
                    break;
                
                // Transaccional
                case '2':
                    
                    $posicionActual = 0; // Posicion que se agregara a cada elemento en la iteracion
                    $posicionGlobal = 1; // Este valor es para ordenar los campos (captura, webservice) que se van insertando
                    $ultimoTipoSeccion = null; // Tendre el tipo de la ultima iteracion
                    $ultimoTipoSeccionId = 0;
                    
                    if($ordenRows != ''){

                        for ($i=0; $i < count($arrOrdenRows); $i++) { 
    
                            // Primero empezamos viendo cual registro gestionar osea editar o agregar
                            $row = $arrOrdenRows[$i];
                            
                            // Consigo el operador
                            $oper = isset($_POST['campo_edit_'.$row]) ? 'edit' : 'add';
    
    
                            if(isset($_POST['tipo_plantilla_'.$oper.'_'.$row])){
                                // Agarro el tipo de seccion actual
                                $tipoSeccionActual = $_POST['tipo_plantilla_'.$oper.'_'.$row]; // Tipo -> captura - consulta
        
                                if($tipoSeccionActual == 'captura'){
        
                                    // Valido cual es el ultimo tipo de seccion, si es null decimos que esta de primeras
                                    if($ultimoTipoSeccion === null){
        
                                        $ultimoTipoSeccionId = $autorespuestaId;
                                        
                                        // Convertimos la seccion principal a captura de datos
                                        cambiarEstadoSeccionPrincipal($autorespuestaId, 'captura');
                                        $ultimoTipoSeccion = 'captura';
                                    }
        
                                    // Si son diferentes significa que hay que realizar un cambio de seccion
                                    if($ultimoTipoSeccion != $tipoSeccionActual){
        
                                        // Creamos esa nueva seccion en dy_base_autorespuestas
                                        $newAutorespuestaId = crearBaseAutorrespuesta('captura', $huespedId, $pasoId, $seccionBotId);
        
                                        // Necesitamos crear una accion final de la autorespuesta anterior para que sea la puerta de enlace entre la ultima seccion y la nueva
                                        crearAccionFinalUnionSecciones($ultimoTipoSeccionId, $newAutorespuestaId, $posicionActual, 'consulta', $posicionGlobal);
                                        $posicionGlobal++;
        
                                        $posicionActual = 0;
                                        $ultimoTipoSeccionId = $newAutorespuestaId;
                                    }
        
                                    $ultimoTipoSeccion = 'captura';
                                    $posicionActual++;
        
                                    $campoId = $_POST['campo_'.$oper.'_'.$row];
                                    $pregunta = $_POST['dyTr_pregunta_bot_'.$oper.'_'.$row];
                                    $respuesta = $_POST['dyTr_rpta_'.$oper.'_'.$row];
                                    $accion = $_POST['accion_'.$oper.'_'.$row];
                                    $pregunId = $_POST['pregun_'.$oper.'_'.$row];
                                    $variableCaptura = $_POST['nombre_variable_'.$oper.'_'.$row];
        
                                    $usarCampoGestion = isset($_POST['usarCampoGestion'.$row]) ? $_POST['usarCampoGestion'.$row] : 0;
                                    $pregunGestionExistente = isset($_POST['pregunGestionExistente_'.$oper.'_'.$row]) ? $_POST['pregunGestionExistente_'.$oper.'_'.$row] : 0;
        
                                    if($oper == 'add'){
        
                                        insertarCapturaDeDatos($ultimoTipoSeccionId, $pregunta, $respuesta, $accion, $pregunId, $posicionActual, $posicionGlobal, $variableCaptura, $bot, $usarCampoGestion, $pregunGestionExistente);
                                        $posicionGlobal++;
        
                                    }
        
                                    if($oper == 'edit'){
                                        actualizarCapturaDeDatos($campoId, $ultimoTipoSeccionId, $pregunta, $respuesta, $accion, $pregunId, $posicionActual, $posicionGlobal, $variableCaptura, $bot, $usarCampoGestion, $pregunGestionExistente);
                                        $posicionGlobal++;
                                    }
        
        
        
                                    // TODO: FALTA INSERCION CUANDO ES CONSULTA DE DATOS, EL UPDATE (LO VEO DIFICIL) Y EL DELETE
                                    // se recorre los que son update, si no estan en una autorespuesta al que pertenece se cambia,  si no se deja, luego 
                                    // puede que queden autorespuestas huerfanas, entonces tengo que validar si hay una autorrespuesta que no tenga contenido
                                    // validando por el paso y que excluya los dy_cancel, si no hay nada se borra, asi controlo eso y ya 
                                        
                                    
                                }
        
                                if($tipoSeccionActual == 'consulta'){
        
                                    // Valido cual es el ultimo tipo de seccion, si es null decimos que esta de primeras
                                    if($ultimoTipoSeccion === null){
                                        $ultimoTipoSeccionId = $autorespuestaId;
        
                                        // Convertimos la seccion principal a captura de datos
                                        cambiarEstadoSeccionPrincipal($autorespuestaId, 'consulta');
                                        $ultimoTipoSeccion = 'consulta';
                                    }
        
                                    if($ultimoTipoSeccion != $tipoSeccionActual){
        
                                        // Creamos esa nueva seccion en dy_base_autorespuestas
                                        $newAutorespuestaId = crearBaseAutorrespuesta('consulta', $huespedId, $pasoId, $seccionBotId);
        
                                        // Necesitamos crear una accion final de la autorespuesta anterior para que sea la puerta de enlace entre la ultima seccion y la nueva
                                        crearAccionFinalUnionSecciones($ultimoTipoSeccionId, $newAutorespuestaId, $posicionActual, 'captura', $posicionGlobal);
        
                                        $posicionActual = 0;
                                        $ultimoTipoSeccionId = $newAutorespuestaId;
                                    }
        
                                    $ultimoTipoSeccion = 'consulta';
                                    $posicionActual++;
        
                                    $campoId = $_POST['campo_'.$oper.'_'.$row];
                                    $webserviceId = $_POST['webservice_'.$oper.'_'.$row];
        
                                    if($oper == 'add'){
        
                                        // Creo el registro en dy_bot_ws pasa asociar un autorrespuesta con un consumo de ws
                                        $sqlInsert = "INSERT INTO {$dyalogo_canales_electronicos}.dy_bot_ws (id_base_autorespuestas, id_ws_general, orden) VALUES ({$ultimoTipoSeccionId}, {$webserviceId}, {$posicionActual})";
                                        $resInsert = $mysqli->query($sqlInsert);
                                        $wsBotId = $mysqli->insert_id;
        
                                        // Ahora viene lo chido, llenar los parametros, primero debo trae la lista de parametros de ese service
                                        $sqlParametros = "SELECT * FROM {$BaseDatos_general}.ws_parametros WHERE id_ws = {$webserviceId} AND sentido = 'IN'";
                                        $resParametros = $mysqli->query($sqlParametros);
                
                                        if($resParametros->num_rows > 0){
                
                                            // Ahora si
                                            while ($row2 = $resParametros->fetch_object()) {
                                                
                                                // Como es nuevo solo tengo que realizar la insercion
                                                $tipoValor = $_POST['tipoParametro_'.$row.'_'.$row2->id];
        
                                                if($tipoValor == 1){
                                                    $valor = $_POST['valorEstatico_'.$row.'_'.$row2->id];
                                                }else{
                                                    $valor = $_POST['valorDinamico_'.$row.'_'.$row2->id];
                                                }
                
                                                // Hago la insercion
                                                $sqlInsertParam = "INSERT INTO {$dyalogo_canales_electronicos}.dy_bot_ws_parametros (id_bot_ws, id_parametro, tipo_valor, valor) VALUES ({$wsBotId}, {$row2->id}, {$tipoValor}, '{$valor}')";
                                                $mysqli->query($sqlInsertParam);                                    
                                            }
                                        }
                                    }
        
                                    if($oper == 'edit'){
                                        // Puede pasar 2 cosas que solo se cambie los parametros o que se cambie el webservice y los parametros
                                        $wsBotId = $campoId;
        
                                        // Necesito revisar que el ws que llega sea el mismo en la bd
                                        $sqlwsBot = "SELECT * FROM {$dyalogo_canales_electronicos}.dy_bot_ws WHERE id = {$wsBotId} LIMIT 1";
                                        $reswsBot = $mysqli->query($sqlwsBot);
        
                                        // Esto me debe traer un registro, sino no haga nada
                                        if($reswsBot->num_rows > 0){
                                            $rowWsBot = $reswsBot->fetch_object();
        
                                            // Aqui, dependiendo si el campo ws cambia, realizo una cosa u otra
                                            if($rowWsBot->id_ws_general == $webserviceId){
                                                //Si son iguales solo tengo que cambiar los parametros
                                                
                                                // Primero actualizo el ws_bot
                                                $sqlUpdateWsBot = "UPDATE {$dyalogo_canales_electronicos}.dy_bot_ws SET orden = {$posicionActual} WHERE id = {$wsBotId}";
                                                $mysqli->query($sqlUpdateWsBot);
        
                                                $sqlParametros = "SELECT * FROM {$BaseDatos_general}.ws_parametros WHERE id_ws = {$webserviceId} AND sentido = 'IN'";
                                                $resParametros = $mysqli->query($sqlParametros);
                        
                                                if($resParametros->num_rows > 0){
                        
                                                    // Ahora shi
                                                    while ($row = $resParametros->fetch_object()) {
                                                        
                                                        // Variables
                                                        $paramId = $_POST['parametro_'.$i.'_'.$row->id];
        
                                                        $tipoValor = $_POST['tipoParametro_'.$i.'_'.$row->id];
        
                                                        if($tipoValor == 1){
                                                            $valor = $_POST['valorEstatico_'.$i.'_'.$row->id];
                                                        }else{
                                                            $valor = $_POST['valorDinamico_'.$i.'_'.$row->id];
                                                        }
                        
                                                        // Actualizo
                                                        $sqlUpdateParam = "UPDATE {$dyalogo_canales_electronicos}.dy_bot_ws_parametros SET tipo_valor = {$tipoValor}, valor = '{$valor}' WHERE id = {$paramId}";
                                                        $mysqli->query($sqlUpdateParam);                                    
                                                    }
                                                }
        
                                            }else{
                                                // Aca son mas cosas que realizar
        
                                                // Primero actualizo el ws_bot
                                                $sqlUpdateWsBot = "UPDATE {$dyalogo_canales_electronicos}.dy_bot_ws SET id_ws_general = {$webserviceId}, orden = {$posicionActual} WHERE id = {$wsBotId}";
                                                $mysqli->query($sqlUpdateWsBot);
        
                                                // Como los parametros cambiaron, no me sirven los actuales, los borro
                                                $deleteParametros = "DELETE FROM {$dyalogo_canales_electronicos}.dy_bot_ws_parametros WHERE id_bot_ws = {$wsBotId}";
                                                $mysqli->query($deleteParametros);
        
                                                // Ahora cargo los nuevos parametros
                                                $sqlParametros = "SELECT * FROM {$BaseDatos_general}.ws_parametros WHERE id_ws = {$webserviceId} AND sentido = 'IN'";
                                                $resParametros = $mysqli->query($sqlParametros);
                        
                                                if($resParametros->num_rows > 0){
                        
                                                    // Ahora shi
                                                    while ($row = $resParametros->fetch_object()) {
                                                        
                                                        // Como es nuevo solo tengo que realizar la insercion
                                                        $tipoValor = $_POST['tipoParametro_'.$i.'_'.$row->id];
        
                                                        if($tipoValor == 1){
                                                            $valor = $_POST['valorEstatico_'.$i.'_'.$row->id];
                                                        }else{
                                                            $valor = $_POST['valorDinamico_'.$i.'_'.$row->id];
                                                        }
                        
                                                        // Hago la insercion
                                                        $sqlInsertParam = "INSERT INTO {$dyalogo_canales_electronicos}.dy_bot_ws_parametros (id_bot_ws, id_parametro, tipo_valor, valor) VALUES ({$wsBotId}, {$row->id}, {$tipoValor}, '{$valor}')";
                                                        $mysqli->query($sqlInsertParam);                                    
                                                    }
                                                }   
                                            }
                                        }
                                    }
                                }
        
                                if($tipoSeccionActual == 'consultaDyalogo'){
        
                                    // Valido cual es el ultimo tipo de seccion, si es null decimos que esta de primeras
                                    if($ultimoTipoSeccion === null){
                                        $ultimoTipoSeccionId = $autorespuestaId;
        
                                        // Convertimos la seccion principal a consulta dyalogo
                                        cambiarEstadoSeccionPrincipal($autorespuestaId, 'consultaDyalogo');
                                        $ultimoTipoSeccion = 'consultaDyalogo';
                                    }
        
                                    if($ultimoTipoSeccion != $tipoSeccionActual){
        
                                        // Creamos esa nueva seccion en dy_base_autorespuestas
                                        $newAutorespuestaId = crearBaseAutorrespuesta('consultaDyalogo', $huespedId, $pasoId, $seccionBotId);
        
                                        // Necesitamos crear una accion final de la autorespuesta anterior para que sea la puerta de enlace entre la ultima seccion y la nueva
                                        crearAccionFinalUnionSecciones($ultimoTipoSeccionId, $newAutorespuestaId, $posicionActual, 'captura', $posicionGlobal);
        
                                        $posicionActual = 0;
                                        $ultimoTipoSeccionId = $newAutorespuestaId;
                                    }
        
                                    $ultimoTipoSeccion = 'consultaDyalogo';
                                    $posicionActual++;
        
                                    // Actualizamos los campos que envia para armar la consulta
                                    $bd = (isset($_POST['bdConsultaDy_add_'.$row])) ? $_POST['bdConsultaDy_add_'.$row] : 0;
        
                                    // Campos de consulta
                                    $arrCamposConsulta = (isset($_POST['camposConsultaDy_add_'.$row])) ? $_POST['camposConsultaDy_add_'.$row] : [];
                                    $camposConsulta = '';
                                    $variablesConsulta = '';
                                    
                                    if(count($arrCamposConsulta) > 0){
                                        for ($i=0; $i < count($arrCamposConsulta); $i++) { 
                                            if($camposConsulta != ''){
                                                $camposConsulta .= ',';
                                                $variablesConsulta .= ',';
                                            }
        
                                            if($arrCamposConsulta[$i] == '_ConsInte__b'){
                                                $camposConsulta .= 'G' . $bd . $arrCamposConsulta[$i];
                                                $nombre = 'BD_ID';
                                            }else{
                                                $camposConsulta .= 'G' . $bd . '_C' . $arrCamposConsulta[$i];
                                                $nombre = obtenerNombreVariable($arrCamposConsulta[$i]);
                                            }
                                            $variablesConsulta .= 'CRM' . $ultimoTipoSeccionId . '.' .$nombre;
                                        }
                                    }
        
                                    // Ahora la condicion
                                    $cantCondiciones = (isset($_POST['cantConsultaDyCondiciones_add_'.$row])) ? $_POST['cantConsultaDyCondiciones_add_'.$row] : 0;
        
                                    $strCondiciones = '';
        
                                    if($cantCondiciones > 0){
                                        for ($i=0; $i < $cantCondiciones; $i++) { 
                                            if(isset($_POST['condConsultaDy_identificador_'.$row.'_'.$i]) && isset($_POST['condConsultaDy_accion_'.$row.'_'.$i])){
                                                if($strCondiciones != ''){
                                                    $strCondiciones .= ' '.$_POST['condConsultaDy_operador_'.$row.'_'.$i].' ';
                                                }
                                                $campo1 = $_POST['condConsultaDy_campo_'.$row.'_'.$i];
                                                $condicion = $_POST['dyTr_condConsultaDy_condicion_'.$row.'_'.$i];
                                                $campo2 = $_POST['condConsultaDy_valor_'.$row.'_'.$i];
        
                                                if($condicion == 'dy_contiene'){
                                                    $strCondiciones .= "G".$bd."_C".$campo1 . " LIKE ''%" . $campo2 . "%''";
                                                }else if($condicion == 'dy_inicie_por'){
                                                    $strCondiciones .= "G".$bd."_C".$campo1 . " LIKE ''" . $campo2 . "%''";
                                                }else if($condicion == 'dy_termine_en'){
                                                    $strCondiciones .= "G".$bd."_C".$campo1 . " LIKE ''%" . $campo2 . "''";
                                                }else{
                                                    $strCondiciones .= "G".$bd."_C".$campo1 . " " . $condicion . " ''" . $campo2 . "''";
                                                }
                                            }
                                        }
                                    }
        
                                    $sql = "UPDATE {$dyalogo_canales_electronicos}.dy_base_autorespuestas SET consulta_dy_tabla = 'G{$bd}', consulta_dy_campos = '$camposConsulta', consulta_dy_variables = '{$variablesConsulta}', consulta_dy_condicion = '{$strCondiciones}' WHERE id = {$ultimoTipoSeccionId}";
                                    $mysqli->query($sql);
                                }
                            }
    
    
                        }
    
                        // Al terminar de recorrer el ciclo tengo que crear la accion que une la ultima seccion baseAutorrespuestas con las acciones finales
                        crearAccionFinalUnionSecciones($ultimoTipoSeccionId, $autorespuestaAccionFinalId, $posicionActual, $ultimoTipoSeccion, $posicionGlobal);

                        // Validamos si hay acciones conectadas a la accion final para pasarlo a la principal
                        $sql = "UPDATE {$dyalogo_canales_electronicos}.dy_base_autorespuestas_contenidos SET id_base_transferencia = {$autorespuestaId} WHERE accion = 2 AND id_base_transferencia = {$autorespuestaAccionFinalId} AND id_base_autorespuestas != {$autorespuestaId} AND pregunta != 'DY_CANCEL'";
                        $mysqli->query($sql);
                        
                    }else{
                        setearSeccionTransaccionalConfiuracionInicial($autorespuestaId, $autorespuestaAccionFinalId);
                    }

                    break;
            
                default:
                    # code...
                    break;
            }

        }else{
            if($tipoSeccion == 2){
                setearSeccionTransaccionalConfiuracionInicial($autorespuestaId, $autorespuestaAccionFinalId);
            }
        }

        if($tipoSeccion == 2){
            // Inicializo la variable de cantidad de acciones finales
            $cantAccionesFinales = $_POST['totalAccionesFinales'] ?? 0; 

            // Valido si existen acciones finales para la creacion o actualizacion de cada una
            if($cantAccionesFinales > 0){

                $orden = 1;

                for ($i=0; $i < $cantAccionesFinales; $i++) {
                    
                    if(isset($_POST['campo_accion_final_'.$i])){

                        $accionFinalId = $_POST['campo_accion_final_'.$i];

                        // Formateo el texto de la respuesta
                        $accionFinalRpta = $_POST['dyTr_rpta_accion_final_'.$i];
                        $accionFinalRpta = formatearStringDeVariables($accionFinalRpta);

                        $accionFinalAccion = $_POST['accion_accion_final_'.$i];

                        $accionFinalCampana = (isset($_POST['campan_accion_final_'.$i]) && $_POST['campan_accion_final_'.$i] != '') ? $_POST['campan_accion_final_'.$i] : 'null';

                        $accionPasarOtraSeccion = false;

                        $ejecutarPasoExterno = 'NULL';

                        $accionFinalIdAutorespuesta = 'NULL';

                        $accionFinalIdBot = 0;
                        
                        // Realizo una traduccion para obtener cual es el valor de la accion y el bot
                        if($accionFinalAccion == '2_1'){
                            $accionFinalAccion = "2";
                            $accionFinalIdAutorespuesta = (isset($_POST['bot_seccion_accion_final_'.$i]) && $_POST['bot_seccion_accion_final_'.$i] != '') ? $_POST['bot_seccion_accion_final_'.$i] : 'null';    
                            $tipoPasoTo = 'bot';
                        }else if($accionFinalAccion == '2_2'){
                            $accionFinalAccion = "2";
                            $accionFinalIdAutorespuesta = (isset($_POST['accion_final_seccion_bot_'.$i]) && $_POST['accion_final_seccion_bot_'.$i] != '') ?  $_POST['accion_final_seccion_bot_'.$i] : 'null';
                            $accionFinalIdBot = (isset($_POST['bot_accion_final_'.$i]) && $_POST['bot_accion_final_'.$i] != '') ?  $_POST['bot_accion_final_'.$i] : 'null';
                            $tipoPasoTo = 'bot_externo';
                            crearBotExterno($accionFinalIdAutorespuesta, $pasoId);
                        }else if($accionFinalAccion == "1"){
                            $accionFinalIdAutorespuesta = (isset($_POST['bot_accion_final_'.$i]) && $_POST['bot_accion_final_'.$i] != '') ?  $_POST['bot_accion_final_'.$i] : 'null';
                            $tipoPasoTo = 'campana_externa';
                            crearCampanaExterna($accionFinalCampana, $pasoId);
                        }
                        
                        $accionPasarOtraSeccion = true;

                        if($accionFinalAccion == '7'){
                            $accionPasarOtraSeccion = false;
                            
                            $ejecutarPasoExterno = (isset($_POST['pasos_externos_accion_final_'.$i]) && $_POST['pasos_externos_accion_final_'.$i] != '') ?  $_POST['pasos_externos_accion_final_'.$i] : 'null';
                        }

                        $sql = "UPDATE {$dyalogo_canales_electronicos}.dy_base_autorespuestas_contenidos SET respuesta = '{$accionFinalRpta}', accion = {$accionFinalAccion}, id_campana = {$accionFinalCampana}, id_base_transferencia = {$accionFinalIdAutorespuesta}, id_bot_transferido = {$accionFinalIdBot}, id_estpas = {$ejecutarPasoExterno} WHERE id = {$accionFinalId}";
                        $res = $mysqli->query($sql);

                        $orden++;

                        if(!$res){
                            echo "Error message, Se ha presentado un error al guardar las acciones finales";
                        }

                        // Valido la accion para realizar la insercion de la flecha
                        if($accionPasarOtraSeccion){
                            if($tipoPasoTo == 'campana_externa'){
                                actualizarFlechas($seccionBotId, $accionFinalCampana, $pasoId, true, $tipoPasoTo);
                            }else{
                                actualizarFlechas($seccionBotId, $accionFinalIdAutorespuesta, $pasoId, true, $tipoPasoTo);
                            }
                        }
                    }
                    
                }
            }
        }

        eliminarFlechas($seccionBotId);

        echo json_encode([
            "estado" => "ok"
        ]);

    }

    if(isset($_GET['getParametrosWebservice'])){

        $webserviceId = $_GET['webserviceId'];

        $sql = "SELECT * FROM {$BaseDatos_general}.ws_parametros WHERE id_ws = {$webserviceId} AND sentido = 'IN'";
        $res = $mysqli->query($sql);

        $parametros = [];
        if($res->num_rows > 0){

            $i = 0;
            while ($row = $res->fetch_object()) {
                $parametros[$i] = $row;
                $i++;
            }
        }

        $sqlparams2 = "SELECT * FROM {$BaseDatos_general}.ws_parametros WHERE id_ws = {$webserviceId} AND sentido = 'OUT'";
        $resParams2 = $mysqli->query($sqlparams2);

        $paramsRetorno = [];
        if($resParams2->num_rows > 0){
            $j = 0;
            while ($row2 = $resParams2->fetch_object()) {
                $paramsRetorno[$j] = $row2;
                $j++;
            }
        }

        echo json_encode([
            "parametros" => $parametros,
            "parametrosRetorno" => $paramsRetorno
        ]);
    }

    if(isset($_GET['obtenerVariables'])){

        $pasoId = $_GET['pasoId'];
        $huespedId = $_GET['huesped'];
        $esSeccion = $_GET['esSeccion'];

        // Valido si el paso que viene es una seccion o no, si lo es traigo el paso verdadero
        if($esSeccion){
            $sqlSeccion = "SELECT id, id_estpas FROM {$dyalogo_canales_electronicos}.secciones_bot WHERE id = {$pasoId} LIMIT 1";
            $resSeccion = $mysqli->query($sqlSeccion);

            if($resSeccion && $resSeccion->num_rows > 0){
                $dataSeccion = $resSeccion->fetch_object();
                $pasoId = $dataSeccion->id_estpas;
            }
        }

        // Traigo la estrategia en base al paso id
        $sqlEstrategia = "SELECT ESTPAS_ConsInte__b AS id, ESTPAS_ConsInte__ESTRAT_b AS estrategiaId FROM {$BaseDatos_systema}.ESTPAS WHERE ESTPAS_ConsInte__b = {$pasoId}";
        $resEstrategia = $mysqli->query($sqlEstrategia);
        $dataEstrategia = $resEstrategia->fetch_object();
        $estrategiaId = $dataEstrategia->estrategiaId;

        $baseId = obtenerIdBaseByPasoId($pasoId);
        $camposBd = getCamposBd($baseId);

        // Traigo las variables dinamincas
        $variablesBot = [];
        $variablesWs = [];
        $webservices = [];
        $listaSecciones = [];
        $listaBots = [];
        $listaCampanas = [];

        // Estas son las variables dinamicas que tiene un bot
        $sqlVariablesBot = "SELECT a.nombre AS seccion, c.nombre_variable AS variable, c.id_pregun AS id_pregun, a.id AS autoresId FROM dyalogo_canales_electronicos.dy_base_autorespuestas_contenidos c
                JOIN dyalogo_canales_electronicos.dy_base_autorespuestas a ON c.id_base_autorespuestas = a.id
                WHERE a.id_estpas = {$pasoId} AND  a.tipo_base = 2 AND c.pregunta != 'DY_CANCEL' AND (accion = 3 OR accion = 4 OR accion = 5 OR accion = 6)";
        $resVariablesBot = $mysqli->query($sqlVariablesBot);
        if($resVariablesBot->num_rows > 0){
            $i = 0;
            while ($row = $resVariablesBot->fetch_object()) {
                $variablesBot[$i]['id_pregun'] = $row->id_pregun;
                $variablesBot[$i]['seccion'] = reformatearVariable($row->seccion);
                $variablesBot[$i]['variable'] = reformatearVariable($row->variable);
                $variablesBot[$i]['autoresId'] = $row->autoresId;
                $i++;
            }
        }

        // Estas son las variables de retorno ws
        $sqlVariablesWs = "SELECT CONCAT('WS', b.id) AS seccion, p.parametro AS variable, id_ws_general AS id_ws, a.id AS autoresId, w.nombre AS nombreService FROM dyalogo_general.ws_parametros p
                JOIN dyalogo_canales_electronicos.dy_bot_ws b ON p.id_ws = b.id_ws_general
                JOIN dyalogo_general.ws_general w ON b.id_ws_general = w.id
                JOIN dyalogo_canales_electronicos.dy_base_autorespuestas a ON b.id_base_autorespuestas = a.id
            WHERE a.id_estpas = {$pasoId} AND sentido = 'OUT'";
        $resVariablesWs = $mysqli->query($sqlVariablesWs);
        if($resVariablesWs->num_rows > 0){
            $i = 0;
            while ($row = $resVariablesWs->fetch_object()) {
                $variablesWs[$i]['id_ws'] = $row->id_ws;
                $variablesWs[$i]['seccion'] = reformatearVariable($row->seccion);
                $variablesWs[$i]['variable'] = reformatearVariable($row->variable);
                $variablesWs[$i]['autoresId'] = $row->autoresId;
                $variablesWs[$i]['nombreService'] = reformatearVariable($row->nombreService);
                $i++;
            }
        }

        // Esto trae la lista de webservices del huesped
        $sqlWebservices = "SELECT * FROM {$BaseDatos_general}.ws_general WHERE id_huesped = {$huespedId} OR inter_huesped = 1";
        $resWebservices = $mysqli->query($sqlWebservices);
        if($resWebservices->num_rows > 0){
            $i = 0;
            while($row = $resWebservices->fetch_object()){
                $webservices[$i] = $row;
                $i++;
            }
        }

        $camposSeccionesBot = getAllSeccionesByPasoId($pasoId);

        // Traigo la lista de bots de la estrategia
        $sqlListaBots = "select a.id, a.nombre from {$dyalogo_canales_electronicos}.dy_bot as a
            JOIN {$BaseDatos_systema}.ESTPAS b ON b.ESTPAS_ConsInte__b = a.id_estpas
            WHERE b.ESTPAS_ConsInte__ESTRAT_b = {$estrategiaId} AND b.ESTPAS_ConsInte__b != {$pasoId}";
        
        $resBots = $mysqli->query($sqlListaBots);
        if($resBots->num_rows > 0){
            $i = 0;
            while($row = $resBots->fetch_object()){
                $listaBots[$i] = $row;
                $i++;
            }
        }

        // Traigo la lista de campanas de la estrategia
        $sqlCampana = "SELECT ESTPAS_ConsInte__CAMPAN_b AS id_campan ,B.CAMPAN_IdCamCbx__b AS dy_campan, ESTPAS_Comentari_b AS nombre FROM DYALOGOCRM_SISTEMA.ESTPAS LEFT JOIN DYALOGOCRM_SISTEMA.CAMPAN B ON ESTPAS_ConsInte__CAMPAN_b = CAMPAN_ConsInte__b WHERE ESTPAS_ConsInte__ESTRAT_b = {$estrategiaId} AND ESTPAS_Tipo______b =1 AND ESTPAS_ConsInte__CAMPAN_b IS NOT NULL";
        $resCampana = $mysqli->query($sqlCampana);
        if($resCampana->num_rows > 0){
            $i = 0;
            while($row = $resCampana->fetch_object()){
                $listaCampanas[$i] = $row;
                $i++;
            }
        }

        $listaCamposDyalogo = [];

        // Traemos todos los campos de las consultas a bases de dyalogo
        $sql = "SELECT id, consulta_dy_campos, consulta_dy_variables FROM {$dyalogo_canales_electronicos}.dy_base_autorespuestas WHERE tipo_base = 5 AND id_estpas = {$pasoId}";
        $resConsultas = $mysqli->query($sql);
        if($resConsultas && $resConsultas->num_rows > 0){
            while($row = $resConsultas->fetch_object()){
                $arrCampos = explode(',',$row->consulta_dy_campos);
                $arrNombres = explode(',',$row->consulta_dy_variables);

                for ($i=0; $i < count($arrCampos); $i++) { 

                    $identificador = "CRM" . $row->id . "." . $arrCampos[$i];

                    if(isset($arrNombres[$i])){
                        $nombre = $arrNombres[$i];
                    }else{
                        $nombre = "CRM" . $row->id . "." . $arrCampos[$i];
                    }
                    
                    $listaCamposDyalogo[] = ["id" => $identificador, "nombre" => $nombre, "autoresId" => $row->id];
                }
            }
        }

        echo json_encode([
            "camposBd" => $camposBd,
            "variablesBot" => $variablesBot,
            "variablesWs" => $variablesWs,
            "webservices" => $webservices,
            "listaSeccionesBot" => $camposSeccionesBot,
            "listaBots" => $listaBots,
            "listaCampanas" => $listaCampanas,
            "listaCamposDyalogo" => $listaCamposDyalogo
        ]);
    }

    if(isset($_GET['obtenerSeccionesDeBot'])){
        
        $botId = $_GET['botId'];

        $data = [];

        $sql = "SELECT b.id, a.nombre FROM {$dyalogo_canales_electronicos}.secciones_bot a
            LEFT JOIN {$dyalogo_canales_electronicos}.dy_base_autorespuestas b ON b.id_seccion = a.id 
        WHERE a.id_bot = {$botId} GROUP BY b.id_seccion having b.id_seccion is not null ";

        $res = $mysqli->query($sql);
        if($res && $res->num_rows > 0){

            while ($row = $res->fetch_object()) {
                $data[] = $row;
            }

        }

        echo json_encode(["secciones" => $data]);
    }

    if(isset($_GET['obtenerIdSeccion'])){
        $autorespuestaId = $mysqli->real_escape_string($_POST['seccionAutores']);

        $sql = "SELECT id_seccion FROM {$dyalogo_canales_electronicos}.dy_base_autorespuestas WHERE id = {$autorespuestaId} LIMIT 1";
        $res = $mysqli->query($sql);

        $seccionId = 0;
        $valido = false;

        if($res && $res->num_rows > 0){
            $data = $res->fetch_object();

            $seccionId = $data->id_seccion;
            $valido = true;
        }

        echo json_encode([
            "seccionId" => $seccionId,
            "valido" => $valido
        ]);
    }

    if(isset($_GET['borrar_campo_bot'])){
        $campoBotId = $_POST['campoBotId'];
        $seccionId = isset($_POST['seccionId']) ? $_POST['seccionId'] : NULL;

        // Me toca hacerle un analisis en donde tengo que encontrar el bot, la gestion
        if(!is_null($seccionId)){
            // Tengo que buscar el id de la gestion para eso debo buscar en la tabla dy_bot
            $sql = "SELECT * FROM {$dyalogo_canales_electronicos}.dy_bot a
                inner join {$dyalogo_canales_electronicos}.secciones_bot b ON a.id = b.id_bot
                where b.id = {$seccionId}
            ";
            $resBot = $mysqli->query($sql);
            
            if($resBot && $resBot->num_rows > 0){
                $bot = $resBot->fetch_object();

                // Ya teniendo el bot ahora nos toca buscar el id pregun que se va a eliminar
                $sql = "SELECT * FROM {$dyalogo_canales_electronicos}.dy_base_autorespuestas_contenidos WHERE id = {$campoBotId}";
                $resCampo = $mysqli->query($sql);

                if($resCampo && $resCampo->num_rows > 0){

                    $campo = $resCampo->fetch_object();

                    // Primero, buscamos si este id pregun ya se encuentra en otras secciones del bot
                    $sql = "SELECT * FROM {$dyalogo_canales_electronicos}.dy_base_autorespuestas_contenidos WHERE id_pregun_gestion = {$campo->id_pregun_gestion} AND id != {$campo->id}";
                    $resBusquedaCoincidencia = $mysqli->query($sql);
                    
                    if($resBusquedaCoincidencia && $resBusquedaCoincidencia->num_rows <= 0){
                        $updateEstadoPregun = "UPDATE {$BaseDatos_systema}.PREGUN SET PREGUN_FueGener_b = 3 WHERE PREGUN_ConsInte__b = {$campo->id_pregun_gestion}";
                        $mysqli->query($updateEstadoPregun);
                    }
                }
            }
        }

        $sqlContenido = "DELETE FROM {$dyalogo_canales_electronicos}.dy_base_autorespuestas_contenidos WHERE id = {$campoBotId}";
        $mysqli->query($sqlContenido);

        echo json_encode([
            "estado" => "ok"
        ]);
    }

    if(isset($_GET['borrar_campos_ws'])){
        $wsId = $_POST['rowId'];

        // borro los datos de la tablas de ws_bot_parametros
        $sqlDeleteParametros = "DELETE FROM {$dyalogo_canales_electronicos}.dy_bot_ws_parametros WHERE id_bot_ws = {$wsId}";
        $mysqli->query($sqlDeleteParametros);

        // borro los datos de la tabla ws_bot
        $sqlDeleteWebservice = "DELETE FROM {$dyalogo_canales_electronicos}.dy_bot_ws WHERE id = {$wsId}";
        $mysqli->query($sqlDeleteWebservice);

        echo json_encode([
            "estado" => "ok"
        ]);
    }

    if(isset($_GET['get_campos_bd_especifica'])){
        $baseId = $_GET['baseId'];

        $sql = "SELECT PREGUN_ConsInte__b AS id, PREGUN_Texto_____b AS nombre FROM DYALOGOCRM_SISTEMA.PREGUN WHERE PREGUN_ConsInte__GUION__b = {$baseId} AND PREGUN_Tipo______b NOT IN (6, 11, 13) AND PREGUN_Texto_____b NOT LIKE '%_DY%'";
        $res = $mysqli->query($sql);

        $data = [];

        if($res && $res->num_rows > 0){
            $i = 0;

            while ($row = $res->fetch_object()) {
                $data[$i] = $row;
                $i++;
            }
        }

        echo json_encode([
            "campos" => $data
        ]);
    }

    // Acciones finales
    if(isset($_GET['crearAccionFinal'])){
        $autorespuestaId = $_POST['autorespuesta'];
        
        if(isset($_POST['destino']) && $_POST['destino'] != ''){
            $destino = $_POST['destino'];
        }else{
            $destino = 'NULL';
        }

        // Obtengo el orden de finalizacion maximo
        $sqlOrdenFinalizacionMaximo = "SELECT id, max(orden_finalizacion) AS orden FROM {$dyalogo_canales_electronicos}.dy_base_autorespuestas_contenidos WHERE id_base_autorespuestas = {$autorespuestaId}";
        $resOrdenFinalizacionMaximo = $mysqli->query($sqlOrdenFinalizacionMaximo);
        $ordenFinalizacionMaximo = $resOrdenFinalizacionMaximo->fetch_object();

        $newOrdenFinalizacionMaximo = $ordenFinalizacionMaximo->orden + 1; //1 + 1

        // Obtengo el orden maximo
        $sqlOrdenMaximo = "SELECT id, max(orden) AS orden FROM {$dyalogo_canales_electronicos}.dy_base_autorespuestas_contenidos WHERE id_base_autorespuestas = {$autorespuestaId}";
        $resOrdenMaximo = $mysqli->query($sqlOrdenMaximo);
        $ordenMaximo = $resOrdenMaximo->fetch_object();
        $newOrden = $ordenMaximo->orden + 1; // 1 + 1

        // Actualizo el orden de finalizacion actual
        $update = "UPDATE {$dyalogo_canales_electronicos}.dy_base_autorespuestas_contenidos SET orden_finalizacion = {$newOrdenFinalizacionMaximo}, orden = {$newOrden} WHERE id_base_autorespuestas = {$autorespuestaId} AND orden_finalizacion = {$ordenFinalizacionMaximo->orden}";
        $mysqli->query($update);

        $sql = "INSERT INTO {$dyalogo_canales_electronicos}.dy_base_autorespuestas_contenidos (id_base_autorespuestas, pregunta, tags, respuesta, accion, orden, orden_finalizacion, id_base_transferencia) 
            VALUES ({$autorespuestaId}, 'DY_ACCION_CONDICON', 'cancelar', '', 2, {$ordenMaximo->orden}, {$ordenFinalizacionMaximo->orden}, {$destino})";
        $mysqli->query($sql);

        $id = $mysqli->insert_id;

        echo json_encode([
            "estado" => "ok",
            "id" => $id
        ]);
    }

    // Condiciones

    if(isset($_GET['obtenerCondiciones'])){

        $campoId = $_POST['campoId'];

        $condiciones = [];

        $sql = "SELECT * FROM {$dyalogo_canales_electronicos}.condiciones_bot WHERE id_base_autorespuestas_contenido = {$campoId}";
        $res = $mysqli->query($sql);

        if($res && $res->num_rows > 0){
            $i = 0;
            while ($row = $res->fetch_object()) {
                $condiciones[$i] = $row;
                $i++;
            }
        }

        echo json_encode([
            "estado" => "ok",
            "condiciones" => $condiciones
        ]);
    }

    if(isset($_GET['guardarCondiciones'])){

        // $tipoCondicion = $mysqli->real_escape_string($_POST['tipoCondicion']);
        $accionFinalId = $mysqli->real_escape_string($_POST['autorespuestaContenidoId']);
        $cantCondiciones = $mysqli->real_escape_string($_POST['contCondiciones']);

        for ($i=0; $i < $cantCondiciones; $i++) { 
            
            // Valido si el registro es para crear o para actualizar
            // Valido si el id del registro a actualizar existe
            if(isset($_POST['condiciones_accion_'.$i])){

                $accion = $_POST['condiciones_accion_'.$i];

                $identificador = $_POST['condiciones_identificador_'.$i];
                $operador = $_POST['condiciones_operador_'.$i];
                $campo = $_POST['condiciones_campo_'.$i];
                $condicion = $_POST['condiciones_condicion_'.$i];
                $tipoValorAComparar = $_POST['tipo_valor_'.$i];
                $valorAComparar = $_POST['condiciones_valor_'.$i];

                $orden = getUltimoOrdenCondicion($accionFinalId);
                $orden = $orden + 1;

                // Validar tipo de campo
                if(strncmp($campo, '${WS', 4) === 0){
                    $tipoCampo = 'webservice';
                }else if (strncmp($campo, '${', 2) === 0){
                    $tipoCampo = 'datocapturado';
                }else{
                    $tipoCampo = 'pregun';
                }

                // Validar el tipo de campo a comparar
                if($tipoValorAComparar == 'dinamico' && $valorAComparar != "DY_VACIO"){
                    if(strncmp($valorAComparar, '${WS', 4) === 0){
                        $tipoCampoAComparar = 'webservice';
                    }else if (strncmp($valorAComparar, '${', 2) === 0){
                        $tipoCampoAComparar = 'datocapturado';
                    }else{
                        $tipoCampoAComparar = 'pregun';
                    }
                }else{
                    $tipoCampoAComparar = 'estatico';
                }

                if($accion == 'add'){
                    $sql = "INSERT INTO {$dyalogo_canales_electronicos}.condiciones_bot (id_base_autorespuestas_contenido, tipo_campo, campo, condicion, tipo_valor_comparar, valor_a_comparar, operador, orden) 
                        VALUES ({$accionFinalId}, '{$tipoCampo}', '{$campo}', '{$condicion}', '{$tipoCampoAComparar}', '{$valorAComparar}', '{$operador}', {$orden})";
                }else{
                    $sql = "UPDATE {$dyalogo_canales_electronicos}.condiciones_bot SET tipo_campo = '{$tipoCampo}', campo = '{$campo}', condicion ='{$condicion}', 
                        tipo_valor_comparar = '{$tipoCampoAComparar}', valor_a_comparar = '{$valorAComparar}', operador = '{$operador}' WHERE id = {$identificador}";
                }
                $mysqli->query($sql);
            }
        }
        
        $nombreCondicion = getNombreDeCondicionesSeccion($accionFinalId);

        echo json_encode([
            "estado" => "ok",
            "nombreCondicion" => $nombreCondicion
        ]);
    }

    if(isset($_GET['borrar_condicion'])){
        $id = $mysqli->real_escape_string($_POST['condicionId']);

        $sql = "DELETE FROM {$dyalogo_canales_electronicos}.condiciones_bot WHERE id = {$id}";
        $mysqli->query($sql);

        echo json_encode([
            "estado" => "ok"
        ]);
    }

    // Validaciones TODO: AUN NO TERMINADO

    if(isset($_GET['traerValidaciones'])){

        $huespedId = $_GET['huesped'];
        $accionId = $_POST['accionId'];
        $tipoAccion = $_POST['tipoAccion'];
        $campoBd = $_POST['campoBdId'];

        // Primero devuelvo la lista de listas del huesped
        $listas = [];
        $sqlListas = "SELECT OPCION_ConsInte__b as id , OPCION_Nombre____b as nombre FROM DYALOGOCRM_SISTEMA.OPCION WHERE OPCION_ConsInte__PROYEC_b = {$huespedId} ORDER BY OPCION_Nombre____b ASC";
        $resListas = $mysqli->query($sqlListas);
        if($resListas->num_rows > 0){
            $i = 0;
            while($row = $resListas->fetch_object()){
                $listas[$i] = $row;
                $i++;
            }
        }

        // Ahora traigo la informacion del campo
        $autorespuesta = null;
        $pregun = null;

        // Me toca traer la informacion por defecto del campo que usa el bot
        if($tipoAccion == 3 || $tipoAccion == 5){
            
            $sql = "SELECT PREGUN_ConsInte__b AS id, PREGUN_Texto_____b AS nombre, 1 AS validar_campo, PREGUN_Tipo______b AS tipo_campo, 
            PREGUN_NumeMini__b AS rango_numero_desde, PREGUN_NumeMaxi__b AS rango_numero_hasta, PREGUN_FechMini__b AS rango_fecha_desde, PREGUN_FechMaxi__b AS rango_fecha_hasta, PREGUN_ConsInte__OPCION_B AS lista_opcion_id, PREGUN_Longitud__b AS validacion_cantidad_caracteres_textos
            from DYALOGOCRM_SISTEMA.PREGUN where PREGUN_ConsInte__b = {$campoBd}";

            $resPregun = $mysqli->query($sql);
            if($resPregun && $resPregun->num_rows > 0){
                $pregun = $resPregun->fetch_object();
            }
        }

        // Ahora tremos si o si la data de autorespuesta
        $sql = "SELECT * FROM {$dyalogo_canales_electronicos}.dy_base_autorespuestas_contenidos WHERE id = {$accionId}";
        
        $resAutorespuesta = $mysqli->query($sql);
        if($resAutorespuesta && $resAutorespuesta->num_rows > 0){
            $autorespuesta = $resAutorespuesta->fetch_object();
        }

        echo json_encode([
            "estado" => "ok",
            "listas" => $listas,
            "autorespuesta" => $autorespuesta,
            "pregun" => $pregun
        ]);
    }


    if(isset($_GET['guardarValidaciones'])){

        $accionId = $_POST['validacionAccionId'];
        $tipoAccion = $_POST['validacionAccionTipo'];
        $campo = $_POST['validacionCampo'];

        $validacion = $_POST['validacionRadio'];

        $tipoCampo = 1;

        $maxCaracteres = "NULL";

        $valorMinimo = "NULL";
        $valorMaximo = "NULL";

        $fechaMinima = "NULL";
        $fechaMaxima = "NULL";
        $strFecha = "rango_fecha_desde = {$fechaMinima}, rango_fecha_hasta = {$fechaMaxima}";

        $listaId = "NULL";

        if($validacion == 1){

            $tipoCampo = $_POST['tipoCampo'];

            if($tipoCampo == 1){
                $maxCaracteres = $_POST['campoCantMaximaTexto'];
            }

            if($tipoCampo == 3){
                if($_POST['campoValorMinimo'] != ''){
                    $valorMinimo = $_POST['campoValorMinimo'];
                }
                if($_POST['campoValorMaximo'] != ''){
                    $valorMaximo = $_POST['campoValorMaximo'];
                }
            }

            if($tipoCampo == 5){

                if($_POST['campoFechaMinima'] != ''){
                    $fechaMinima = $_POST['campoFechaMinima'];
                }
                if($_POST['campoFechaMaxima'] != ''){
                    $fechaMaxima = $_POST['campoFechaMaxima'];
                    $strFecha = "rango_fecha_desde = '{$fechaMinima}', rango_fecha_hasta = '{$fechaMaxima}'";
                }

            }

            if($tipoCampo == 6){
                $listaId = $_POST['campoLista'];
            }
        }

        // Sql de actualizacion de campos
        $sql = "UPDATE {$dyalogo_canales_electronicos}.dy_base_autorespuestas_contenidos SET 
        validar_campo = {$validacion}, tipo_campo = {$tipoCampo}, rango_numero_desde = {$valorMinimo}, rango_numero_hasta = {$valorMaximo}, {$strFecha}, lista_opcion_id = {$listaId}, validacion_cantidad_caracteres_textos = {$maxCaracteres} WHERE id = {$accionId}";

        if($mysqli->query($sql)){
            $exito = true;
        }else{
            $exito = false;
            echo $mysqli->error;
        }

        echo json_encode([
            "estado" => "ok",
            "exito" => $exito
        ]);
    }

    // Tags

    if(isset($_GET['tagsComunes'])){
        $huespedId = $_POST['huesped'];

        // Traigo la lista de disparadores de tags disponibles
        $sqlTag = "SELECT * FROM {$dyalogo_canales_electronicos}.tags_comunes WHERE id_huesped = {$huespedId}";
        $resTags = $mysqli->query($sqlTag);

        $tagsComunes = [];
        if($resTags->num_rows > 0){
            $i = 0;
            while ($row = $resTags->fetch_object()) {
                $tagsComunes[$i] = $row->tag_disparador;
                $i++;
            }
        }

        echo json_encode([
            "tagsComunes" => $tagsComunes
        ]);
    }

    if(isset($_GET['guardar_tags_comunes'])){
        $huesped = $_GET['huesped'];

        if($_POST['totalCamposComunes'] == 0){
            $valido = true;
        }else{
            $posicion = 0;

            for ($i=0; $i < $_POST['totalCamposComunes']; $i++) { 
                
                $oper = isset($_POST['cTag_edit_'.$i]) ? 'edit' : 'add';

                $tag = $_POST['mTag_'.$oper.'_'.$i];
                $expresiones = $_POST['tTag_'.$oper.'_'.$i];

                // Sume una posicion si el campo se agrega o edita
                if($oper == 'edit' || $oper == 'add'){
                    $posicion .= 1;
                }

                if($tag && $expresiones){
                    
                    if($oper == 'add'){
                        //insertar las nuevas preguntas
                        $sqlInsert = "INSERT INTO {$dyalogo_canales_electronicos}.tags_comunes 
                                (tag_disparador, expresiones, id_huesped) 
                                VALUES 
                                ('{$tag}','{$expresiones}','{$huesped}')";
                        $mysqli->query($sqlInsert);
                    }else if($oper == 'edit'){
                        //actualizar las preguntas
                        $sqlUpdate = "UPDATE {$dyalogo_canales_electronicos}.tags_comunes SET tag_disparador = '{$tag}', expresiones = '{$expresiones}' WHERE id = {$_POST['cTag_edit_'.$i]}";
                        $mysqli->query($sqlUpdate);
                    }
                }
            }
        }

        echo json_encode([
            "estado" => "ok"
        ]);
    }

    if(isset($_GET['getTagsComunes'])){
        $huesped = $_GET['huesped'];

        $campos = [];

        $sql = "SELECT * FROM {$dyalogo_canales_electronicos}.tags_comunes WHERE id_huesped = {$huesped}";
        $res = $mysqli->query($sql);

        if($res->num_rows > 0){
            $i = 0;
            while ($row = $res->fetch_object()) {
                $campos[$i] = $row;
                $i++;
            }
        }

        echo json_encode([
            "campos" => $campos
        ]);
    }

    if(isset($_GET['borrar_campo_tag_comun'])){
        $campoId = $_POST['rowId'];

        $sqlContenido = "DELETE FROM {$dyalogo_canales_electronicos}.tags_comunes WHERE id = {$campoId}";
        $mysqli->query($sqlContenido);

        echo json_encode([
            "estado" => "ok"
        ]);
    }

    // Tags globales

    // Obtengo los tags globales por seccion
    if(isset($_GET['obtenerTagsGlobales'])){

        $pasoId = $_POST['pasoId'];

        $secciones = [];

        $sql = "SELECT a.id, a.nombre, a.id_estpas AS estpas_id, c.id AS tag_id, c.pregunta AS tag, c.global, s.tipo_seccion FROM {$dyalogo_canales_electronicos}.dy_base_autorespuestas a
                    LEFT JOIN (SELECT * FROM {$dyalogo_canales_electronicos}.dy_base_autorespuestas_contenidos WHERE global = 1) AS c ON a.id = c.id_base_autorespuestas
                    INNER JOIN dyalogo_canales_electronicos.secciones_bot s ON a.id_seccion = s.id
                WHERE a.id_estpas = {$pasoId} ORDER BY s.orden";
        $res = $mysqli->query($sql);

        while ($row = $res->fetch_object()) {
            $secciones[] = $row;
        }

        echo json_encode([
            "secciones" => $secciones
        ]);
    }

    if(isset($_GET['obtenerIdSeccion'])){
        $autorespuestaId = $mysqli->real_escape_string($_POST['seccionAutores']);

        $sql = "SELECT id_seccion FROM {$dyalogo_canales_electronicos}.dy_base_autorespuestas WHERE id = {$autorespuestaId} LIMIT 1";
        $res = $mysqli->query($sql);

        $seccionId = 0;
        $valido = false;

        if($res && $res->num_rows > 0){
            $data = $res->fetch_object();

            $seccionId = $data->id_seccion;
            $valido = true;
        }

        echo json_encode([
            "seccionId" => $seccionId,
            "valido" => $valido
        ]);
    }

    // Guardar tags globales
    if(isset($_GET['guardar_tags_globales'])){

        $pasoId = $mysqli->real_escape_string($_GET['id_paso']);
        $huespedId = $mysqli->real_escape_string($_GET['huesped']);

        // Recorro las secciones con sus tags globales
        $sql = "SELECT a.id, c.id AS tag_id, c.pregunta AS tag FROM {$dyalogo_canales_electronicos}.dy_base_autorespuestas a
            LEFT JOIN (SELECT * FROM {$dyalogo_canales_electronicos}.dy_base_autorespuestas_contenidos WHERE global = 1) AS c ON a.id = c.id_base_autorespuestas
        WHERE a.id_estpas = {$pasoId}";

        $res = $mysqli->query($sql);

        if($res && $res->num_rows > 0){

            while ($row = $res->fetch_object()) {

                // Si es null creo el tag
                if($row->tag_id == null && $row->tag == null){

                    if(isset($_POST['tag_global_'.$row->id]) && $_POST['tag_global_'.$row->id] != ''){
                        $tags = $mysqli->real_escape_string($_POST['tag_global_'.$row->id]);
                        $tagsFormateado = obtenerTags($tags);

                        $insert = "INSERT INTO {$dyalogo_canales_electronicos}.dy_base_autorespuestas_contenidos 
                        (id_base_autorespuestas, pregunta, tags, accion, id_base_transferencia, global) VALUES ({$row->id}, '{$tags}', '{$tagsFormateado}', 2, {$row->id}, 1)";
                        $mysqli->query($insert);
                    }

                }else{
                    // Actualizo el tag si existe y no esta vacio
                    if(isset($_POST['tag_global_'.$row->id]) && $_POST['tag_global_'.$row->id] != ''){
                        $tags = $mysqli->real_escape_string($_POST['tag_global_'.$row->id]);
                        $tagsFormateado = obtenerTags($tags);

                        $update = "UPDATE {$dyalogo_canales_electronicos}.dy_base_autorespuestas_contenidos SET pregunta = '{$tags}', tags = '{$tagsFormateado}' WHERE id = {$row->tag_id}";
                        $mysqli->query($update);
                    }else{
                        // Si no existe o esta vacio lo elimino
                        $delete = "DELETE FROM {$dyalogo_canales_electronicos}.dy_base_autorespuestas_contenidos WHERE id = {$row->tag_id}";
                        $mysqli->query($delete);
                    }

                }
            }
        }

        echo json_encode([
            "estado" => "ok"
        ]);
    }

    // Preguntas que el bot no responde
    
    if(isset($_GET['traerPreguntasBotNoResponde'])){

        $pasoId = $_GET['id_paso'];
        $seccionId = $_POST['seccion'];
        $ejecutor = $_POST['ejecutor'];

        if($ejecutor == 'bot'){
            $sql = "SELECT a.id, a.id_bot, a.pregunta, a.fecha_hora, a.contador + 1 AS contador, c.nombre AS nombreSeccion, a.id_base_autorespuesta FROM {$dyalogo_canales_electronicos}.preguntas_sin_respuesta_bot a
                INNER JOIN {$dyalogo_canales_electronicos}.dy_base_autorespuestas b ON a.id_base_autorespuesta = b.id
                INNER JOIN {$dyalogo_canales_electronicos}.secciones_bot c ON c.id = b.id_seccion
            WHERE b.id_estpas = {$pasoId} and a.fecha_hora > '2023-01-01 00:00:00' ORDER BY a.fecha_hora DESC";
        }else{
            $sql = "SELECT id, id_bot, pregunta, fecha_hora, contador + 1 AS contador FROM {$dyalogo_canales_electronicos}.preguntas_sin_respuesta_bot WHERE dy_base_autorespuestas = {$seccionId} and a.fecha_hora > '2023-01-01 00:00:00' ORDER BY fecha_hora DESC";
        }
        $res = $mysqli->query($sql);

        $campos = [];

        if($res->num_rows > 0){
            $i = 0;
            while ($row = $res->fetch_object()) {
                $campos[$i] = $row;
                $i++;
            }
        }

        $listaSecciones = [];

        // necesitaria la lista de secciones dependiendo del ejecutor
        if($ejecutor == 'bot'){
            $sql = "SELECT a.* FROM {$dyalogo_canales_electronicos}.preguntas_sin_respuesta_bot a
                INNER JOIN {$dyalogo_canales_electronicos}.dy_base_autorespuestas b ON a.id_base_autorespuesta = b.id
                INNER JOIN {$dyalogo_canales_electronicos}.secciones_bot c ON c.id = b.id_seccion
            WHERE b.id_estpas = {$pasoId} GROUP BY a.id_base_autorespuesta";

            $res = $mysqli->query($sql);

            if($res && $res->num_rows > 0){
                while($row = $res->fetch_object()){
                    $listaSecciones[] = $row->id_base_autorespuesta;
                }
            }
        }else{
            $listaSecciones[] = $seccionId;
        }

        $ItemsPorSecciones = [];

        // Recorro el array de secciones para traer las opciones
        if(count($listaSecciones) > 0){

            for ($i=0; $i < count($listaSecciones); $i++) { 
                
                $sqlItems = "SELECT id, pregunta FROM {$dyalogo_canales_electronicos}.dy_base_autorespuestas_contenidos WHERE id_base_autorespuestas = {$listaSecciones[$i]}";
                $resItems = $mysqli->query($sqlItems);

                $items = [];

                if($resItems && $resItems->num_rows > 0){
                    while ($row = $resItems->fetch_object()) {
                        $items[] = $row;
                    }
                }

                $ItemsPorSecciones[] = ["id" => $listaSecciones[$i], "items" => $items];
            }
        }

        echo json_encode([
            "campos" => $campos,
            "itemsPorSecciones" => $ItemsPorSecciones
        ]);
    }

    if(isset($_GET['eliminarTagNoDeseado'])){
        $tagId = $_POST['tagId'];

        $sql = "DELETE FROM {$dyalogo_canales_electronicos}.preguntas_sin_respuesta_bot WHERE id = {$tagId}";
        $mysqli->query($sql);

        echo json_encode([
            "estado" => "ok"
        ]);
    }

    if(isset($_GET['agregarTagAlItem'])){
        $tagId = $_POST['tagId']; // Id del tag a ingresar
        $campoItemId = $_POST['campoItem']; // El elemento en donde se quiere insertar el tag
        $nuevoTag = $_POST['nuevoTag']; // El tag a insertar
        $seccionId = $_POST['seccion']; // La seccion donde esta el tag a insertar

        $valido = true;
        $mensaje = 'Se ha agegado el tag correctamente';

        // Comparo si ese tag esta agregado en la seccion
        $sqlListaTags = "SELECT tags FROM {$dyalogo_canales_electronicos}.dy_base_autorespuestas_contenidos WHERE id_base_autorespuestas = {$seccionId}";
        $resListaTags = $mysqli->query($sqlListaTags);

        if($resListaTags){
            if($resListaTags->num_rows > 0){
                while ($row = $resListaTags->fetch_object()) {
                    $tags = explode(',', $row->tags);

                    if(in_array($nuevoTag, $tags)){
                        $valido = false;
                        $mensaje = "Este tag ya se encuentra agregado en la seccion";
                    }
                }
            }
        }

        if($valido){
            // Empiezo agrgeando el tag al la opcion de la seccopm especifica
            $sqlUpdate = "UPDATE {$dyalogo_canales_electronicos}.dy_base_autorespuestas_contenidos SET pregunta = CONCAT(pregunta, ', {$nuevoTag}'), tags = CONCAT(tags, ', {$nuevoTag}') WHERE id = {$campoItemId}";
            $mysqli->query($sqlUpdate);

            // Elimino el tag de la lista de tags sin respuesta
            $sqlDelete = "DELETE FROM {$dyalogo_canales_electronicos}.preguntas_sin_respuesta_bot WHERE id = {$tagId}";
            $mysqli->query($sqlDelete);
        }

        echo json_encode([
            "valido" => $valido,
            "mensaje" => $mensaje
        ]);
    }

    // Flechas

    if(isset($_GET['traerDisparadoresDeFlecha'])){
        $from = $_POST['from'];
        $to = $_POST['to'];
        $tipoEjecucion = $_POST['tipoEjecucion'];
        $aux = $_POST['aux'];

        // Valido el tipo de bola de donde sale
        $sqlFromPaso = "SELECT id, tipo_seccion FROM {$dyalogo_canales_electronicos}.secciones_bot WHERE id = {$from}";
        $resFromPaso = $mysqli->query($sqlFromPaso);
        $dataFromPaso = $resFromPaso->fetch_object();

        $tipoSeccionFrom = 'conversacional';

        // Traemos el id_base_autorespuesta de from y to
        if($dataFromPaso->tipo_seccion == '2'){
            $fromAutorespuestaId = getIdBaseAutorespuestaByIdSeccionAccionFinal($from);
            $tipoSeccionFrom = 'transaccional';
        }else{
            $fromAutorespuestaId = getIdBaseAutorespuestaByIdSeccion($from);
        }

        // Validamos tambien el tipo de dato de to
        $sqlToPaso = "SELECT id, tipo_seccion, id_paso_externo FROM {$dyalogo_canales_electronicos}.secciones_bot WHERE id = {$to}";
        $resToPaso = $mysqli->query($sqlToPaso);
        $dataToPaso = $resToPaso->fetch_object();

        if($dataToPaso->tipo_seccion == 24){
            $toAutorespuestaId = $dataToPaso->id_paso_externo;
            $tipoSeccionTo = 'campana';
            $sql = "SELECT * FROM {$dyalogo_canales_electronicos}.dy_base_autorespuestas_contenidos WHERE id_base_autorespuestas = {$fromAutorespuestaId} AND accion = 1 AND id_campana = {$dataToPaso->id_paso_externo}";
        }else if($dataToPaso->tipo_seccion == 25){
            $toAutorespuestaId = $dataToPaso->id_paso_externo;
            $tipoSeccionTo = 'bot';
            $sql = "SELECT * FROM {$dyalogo_canales_electronicos}.dy_base_autorespuestas_contenidos WHERE id_base_autorespuestas = {$fromAutorespuestaId} AND accion = 2 AND id_base_transferencia = {$dataToPaso->id_paso_externo}";
        }else{
            $toAutorespuestaId =  getIdBaseAutorespuestaByIdSeccion($to);
            $tipoSeccionTo = 'seccion_normal';
            $sql = "SELECT * FROM {$dyalogo_canales_electronicos}.dy_base_autorespuestas_contenidos WHERE id_base_autorespuestas = {$fromAutorespuestaId} AND accion = 2 AND id_base_transferencia = {$toAutorespuestaId}";
        }


        // ahora si, traemos solo los disparadores que se ejecutan entre esa seccion
        $res = $mysqli->query($sql);

        $disparadores = [];

        if($res && $res->num_rows > 0){
            $i = 0;
            while ($row = $res->fetch_object()) {
                $disparadores[$i] = $row;
                $i++;
            }
        }

        $valido = true;

        // TODO: TENGO QUE VALIDAR QUE ESTE OK
        // if($tipoEjecucion == 'edit'){
            
        //     $sqlOldPaso = "SELECT id, tipo_seccion FROM {$dyalogo_canales_electronicos}.secciones_bot WHERE id = {$aux}";
        //     $resOldPaso = $mysqli->query($sqlOldPaso);
        //     $dataOldPaso = $resOldPaso->fetch_object();
            
        //     if($dataOldPaso->tipo_seccion != $dataFromPaso->tipo_seccion){
        //         $valido = false; // Si son diferentes no puedo permitir realizar el cambio
        //     }
        // }

        // Traigo todos las autorespuestas para tener todos los tags
        $sqlOtrosDisaparadores = "SELECT * FROM {$dyalogo_canales_electronicos}.dy_base_autorespuestas_contenidos WHERE id_base_autorespuestas = {$fromAutorespuestaId} AND (id_base_transferencia != {$toAutorespuestaId} OR id_base_transferencia IS NULL) AND global = 0";
        $resOtrosDisparadores = $mysqli->query($sqlOtrosDisaparadores);

        $otrosDisparadores = [];
        if($resOtrosDisparadores && $resOtrosDisparadores->num_rows > 0){
            $i = 0;
            while($row = $resOtrosDisparadores->fetch_object()){
                $otrosDisparadores[$i] = $row;
                $i++;
            }
        }

        echo json_encode([
            "valido" => $valido,
            "from" => $fromAutorespuestaId,
            "to" => $toAutorespuestaId,
            "disparadores" => $disparadores,
            "tipoSeccionFrom" => $tipoSeccionFrom,
            "tipoSeccionTo" => $tipoSeccionTo,
            "otrosDisparadores" => $otrosDisparadores
        ]);
    }

    if(isset($_GET['guardarDisparadoresFlecha'])){

        $totalCampos = $_POST['totalCamposDisparadores'];
        $from = $_POST['flechaFromId'];
        $to = $_POST['flechaToId'];

        $tipoSeccionFrom = $_POST['tipoSeccionFrom'];
        $tipoSeccionTo = $_POST['tipoSeccionTo'];

        if($tipoSeccionTo == 'campana'){
            $accion = 1;
            $campanaId = $to;
            $botIdAutores = 'null';
        }else{
            $accion = 2;
            $campanaId = 'null';
            $botIdAutores = $to;
        }

        if($totalCampos > 0){

            if($tipoSeccionFrom == 'conversacional'){
                for ($i=0; $i < $totalCampos; $i++) { 
                    
                    if(isset($_POST['oper_disparador_'.$i])){
    
                        $oper = $_POST['oper_disparador_'.$i];
                        $disparadorId = $_POST['campo_disparador_'.$i];
                        $tag = $_POST['tag_disparador_'.$i];
                        $rpta = $_POST['dyTr_rpta_disparador_'.$i];
    
                        $arrValores = mapeoDeValores(1, 0, $tag, $rpta, $accion, $campanaId, $botIdAutores, 'null');
    
                        if($oper == 'add'){
                            //insertar las nuevas preguntas
                            $sqlInsert = "INSERT INTO dyalogo_canales_electronicos.dy_base_autorespuestas_contenidos 
                                    (id_base_autorespuestas, pregunta, tags, respuesta, accion, id_campana, id_base_transferencia, id_pregun, nombre_variable, orden, llave) 
                                    VALUES 
                                    ({$from},'{$arrValores['pregunta']}','{$arrValores['tags']}','{$arrValores['respuesta']}','{$arrValores['accion']}',{$arrValores['id_campana']},{$arrValores['id_bot']}, {$arrValores['id_pregun']}, '{$arrValores['nombre_variable']}', {$arrValores['orden']}, {$arrValores['llave']})";
                            //echo $sqlInsert;                                
                            $mysqli->query($sqlInsert);
                        }else if($oper == 'edit'){
                            //actualizar las preguntas
                            $sqlUpdate = "UPDATE dyalogo_canales_electronicos.dy_base_autorespuestas_contenidos SET pregunta='{$arrValores['pregunta']}',tags='{$arrValores['tags']}',respuesta='{$arrValores['respuesta']}',accion='{$arrValores['accion']}',id_campana={$arrValores['id_campana']}, id_base_transferencia = {$arrValores['id_bot']}, id_pregun = {$arrValores['id_pregun']}, nombre_variable = '{$arrValores['nombre_variable']}', orden = {$arrValores['orden']}, llave = {$arrValores['llave']} WHERE id={$disparadorId}";
                            //echo $sqlUpdate;
                            $mysqli->query($sqlUpdate);
                        }
    
                    }
    
                }
            }

            if($tipoSeccionFrom == 'transaccional'){
                for ($i=0; $i < $totalCampos; $i++) { 
                    
                    if(isset($_POST['oper_disparador_'.$i])){
    
                        $oper = $_POST['oper_disparador_'.$i];
                        $disparadorId = $_POST['campo_disparador_'.$i];
                        $rpta = $_POST['dyTr_rpta_disparador_'.$i];
    
                        $arrValores = mapeoDeValores(1, 0, '', $rpta, $accion, $campanaId, $botIdAutores, 'null');
    
                        if($oper == 'edit'){
                            //actualizar las preguntas
                            $sqlUpdate = "UPDATE dyalogo_canales_electronicos.dy_base_autorespuestas_contenidos SET respuesta='{$arrValores['respuesta']}', accion='{$arrValores['accion']}', id_base_transferencia = {$arrValores['id_bot']}, llave = 0 WHERE id={$disparadorId}";
                            $mysqli->query($sqlUpdate);
                        }
    
                    }
    
                }
            }


        }

        echo json_encode([
            "ok" => "ok"
        ]);
    }

    if(isset($_GET['borrarDisparadorFlecha'])){
        $id = $_POST['id'];
        $tipoSeccion = $_POST['tipoSeccion'];
        $from = $_POST['from'];

        if($tipoSeccion == 'transaccional'){
            $sqlDeleteCondiciones = "DELETE FROM {$dyalogo_canales_electronicos}.condiciones_bot WHERE id_base_autorespuestas_contenido = {$id}";
            $mysqli->query($sqlDeleteCondiciones);
        }

        $sql = "DELETE FROM {$dyalogo_canales_electronicos}.dy_base_autorespuestas_contenidos WHERE id = {$id}";
        $res = $mysqli->query($sql);

        if($res){

            if($tipoSeccion == 'transaccional'){
                ordenarAcciones($from);
            }

            echo json_encode([
                "mensaje" => "se ha eliminado el registro",
                "estado" => "ok"
            ]);
        }else{
            echo json_encode([
                "mensaje" => "Hay un error al borrar el registro",
                "estado" => "fallo"
            ]);
        }
    }

    if(isset($_GET['cambiarDestinoFlecha'])){

        $pasoId = $_GET['id_paso'];
        $totalCampos = $_POST['totalCamposDisparadores'];
        $from = $_POST['flechaFromId'];
        $to = $_POST['flechaToId'];
        $tipoSeccionFrom = $_POST['tipoSeccionFrom'];
        $tipoSeccionTo = $_POST['tipoSeccionTo'];

        $newPortSeccion = $_POST['aux']; // El valor de la nueva bola a la que apunta
        $newPort = $_POST['aux2']; // Este valor hace referencia a si se esta cambiando la direccion del from o el to de la flecha
        $puertos = $_POST['puertosFlecha']; // Traigo cuales son los puertos 

        // Busco el tipo de seccion que tipo es a donde va
        $sqlTipoSeccion = "SELECT * FROM {$dyalogo_canales_electronicos}.secciones_bot WHERE id = {$newPortSeccion}";
        $resTipoSeccion = $mysqli->query($sqlTipoSeccion);
        $dataTS = $resTipoSeccion->fetch_object();



        if($dataTS->tipo_seccion == '24'){ //CAMPANA
            $accion = 1;
            $campanaId = getIdPasoExternoByIdSeccion($newPortSeccion);
            $newAutorespuestaId = 'null';
        }else if($dataTS->tipo_seccion == '25'){ //BOT
            $accion = 2;
            $campanaId = 'null';
            $newAutorespuestaId = getIdPasoExternoByIdSeccion($newPortSeccion);
        }else{
            $accion = 2;
            $campanaId = 'null';
            $newAutorespuestaId = getIdBaseAutorespuestaByIdSeccion($newPortSeccion);
        }

        $error = '';
        
        if($newPort == 'to'){

            if($totalCampos > 0){

                for ($i=0; $i < $totalCampos; $i++) { 
                    
                    if(isset($_POST['mover_disparador_'.$i])){
    
                        $disparadorId = $_POST['campo_disparador_'.$i];
    
                        $sqlUpdate = "UPDATE {$dyalogo_canales_electronicos}.dy_base_autorespuestas_contenidos SET accion = {$accion}, id_campana = {$campanaId}, id_base_transferencia = {$newAutorespuestaId} WHERE id = {$disparadorId}";
                        
                        if($mysqli->query($sqlUpdate)){
                            // 
                        }else{
                            $error .= ' '.$sqlUpdate;
                        }
                    }
                }
            }

        }

        if($newPort == 'from'){
            if($totalCampos > 0){

                for ($i=0; $i < $totalCampos; $i++) { 
                    
                    if(isset($_POST['mover_disparador_'.$i])){
    
                        $disparadorId = $_POST['campo_disparador_'.$i];

                        if($tipoSeccionFrom == 'transaccional'){
                            $newAutorespuestaId = getIdBaseAutorespuestaFinalByIdSeccion($newPortSeccion);
                        }
    
                        $sqlUpdate = "UPDATE {$dyalogo_canales_electronicos}.dy_base_autorespuestas_contenidos SET id_base_autorespuestas = {$newAutorespuestaId} WHERE id = {$disparadorId}";
                        
                        if($mysqli->query($sqlUpdate)){
                            // 
                        }else{
                            $error .= ' '.$sqlUpdate;
                        }
                    }
                }

                if($tipoSeccionFrom == 'transaccional'){
                    ordenarAcciones($newAutorespuestaId);
                    ordenarAcciones($from);
                }
            }
        }

        // Valido si hay disparadores en la entrada
        if($tipoSeccionTo == 'campana'){
            $sqlVerificar = "SELECT id FROM {$dyalogo_canales_electronicos}.dy_base_autorespuestas_contenidos where id_base_autorespuestas = {$from} AND accion = 1 AND id_campana = {$to}";
        }else{
            $sqlVerificar = "SELECT id FROM {$dyalogo_canales_electronicos}.dy_base_autorespuestas_contenidos where id_base_autorespuestas = {$from} AND accion = 2 AND id_base_transferencia = {$to}";
        }
        $resVerificar = $mysqli->query($sqlVerificar);

        $fromSeccion = getSeccionIdByIdBaseAutorespuesta($from);

        if($tipoSeccionTo == 'campana' || $tipoSeccionTo == 'bot'){
            $toSeccion = getSeccionByIdPasoExterno($to);
        }else{
            $toSeccion = getSeccionIdByIdBaseAutorespuesta($to);
        }

        if($resVerificar && $resVerificar->num_rows <= 0){
            // Si no hay coincidencias elimino la flecha
            $sqlDeleteFlecha = "DELETE FROM {$dyalogo_canales_electronicos}.secciones_conexiones_bot WHERE desde = {$fromSeccion} AND hasta = {$toSeccion}";
            $mysqli->query($sqlDeleteFlecha);
        }

        // ahora valido si a movimos la flecha tiene ya una flecha creara de lo contrario la creo
        if($newPort == 'to'){
            $sqlExisteFlecha = "SELECT id FROM {$dyalogo_canales_electronicos}.secciones_conexiones_bot WHERE desde = {$fromSeccion} AND hasta = {$newPortSeccion}";
        }else{
            $sqlExisteFlecha = "SELECT id FROM {$dyalogo_canales_electronicos}.secciones_conexiones_bot WHERE desde = {$newPortSeccion} AND hasta = {$toSeccion}";
        }
        
        $resExisteFlecha = $mysqli->query($sqlExisteFlecha);
        if($resExisteFlecha && $resExisteFlecha->num_rows <= 0){

            $ports = explode('_', $puertos);

            if($newPort == 'to'){
                $sqlCrearFlecha = "INSERT INTO {$dyalogo_canales_electronicos}.secciones_conexiones_bot (nombre, desde, hasta, from_port, to_port, id_estpas) 
                VALUES ('', {$fromSeccion}, {$newPortSeccion}, '{$ports[0]}', '{$ports[1]}', {$pasoId})";
            }else{
                $sqlCrearFlecha = "INSERT INTO {$dyalogo_canales_electronicos}.secciones_conexiones_bot (nombre, desde, hasta, from_port, to_port, id_estpas) 
                VALUES ('', {$newPortSeccion}, {$toSeccion}, '{$ports[0]}', '{$ports[1]}', {$pasoId})";
            }
            
            $resInsertFlecha = $mysqli->query($sqlCrearFlecha);
        }


        echo json_encode([
            "mensaje" => "Configuracion actualizada",
            "error" => $error
        ]);
    }

    if(isset($_GET['borrarFlecha'])){

        // $totalCampos = $_POST['totalCamposDisparadores'];
        $from = $_POST['flechaFromId'];
        $to = $_POST['flechaToId'];
        // $tipoSeccionFrom = $_POST['tipoSeccionFrom'];
        // $tipoSeccionTo = $_POST['tipoSeccionTo'];

        // if($totalCampos > 0){

        //     for ($i=0; $i < $totalCampos; $i++) { 
                
        //         if(isset($_POST['delete_disparador_'.$i])){

        //             $disparadorId = $_POST['campo_disparador_'.$i];

        //             $sqlDelete = "DELETE FROM {$dyalogo_canales_electronicos}.dy_base_autorespuestas_contenidos WHERE id = {$disparadorId}";
        //             $mysqli->query($sqlDelete);
        //         }
        //     }

        // }

        // Forma numero dos

        // Valido el tipo de bola de donde sale
        $sqlFromPaso = "SELECT id, tipo_seccion FROM {$dyalogo_canales_electronicos}.secciones_bot WHERE id = {$from}";
        $resFromPaso = $mysqli->query($sqlFromPaso);
        $dataFromPaso = $resFromPaso->fetch_object();

        // Traemos el id_base_autorespuesta de from y to
        if($dataFromPaso->tipo_seccion == '2'){
            $fromAutorespuestaId = getIdBaseAutorespuestaByIdSeccionAccionFinal($from);
            $tipoSeccionFrom = 'transaccional';
        }else{
            $fromAutorespuestaId = getIdBaseAutorespuestaByIdSeccion($from);
            $tipoSeccionFrom = 'conversacional';
        }

        // Validamos tambien el tipo de dato de to
        $sqlToPaso = "SELECT id, tipo_seccion, id_paso_externo FROM {$dyalogo_canales_electronicos}.secciones_bot WHERE id = {$to}";
        $resToPaso = $mysqli->query($sqlToPaso);
        $dataToPaso = $resToPaso->fetch_object();

        if($dataToPaso->tipo_seccion == 24){
            $toAutorespuestaId = $dataToPaso->id_paso_externo;
            $tipoSeccionTo = 'campana';
            $sql = "DELETE FROM {$dyalogo_canales_electronicos}.dy_base_autorespuestas_contenidos WHERE id_base_autorespuestas = {$fromAutorespuestaId} AND accion = 1 AND id_campana = {$dataToPaso->id_paso_externo}";
        }else if($dataToPaso->tipo_seccion == 25){
            $toAutorespuestaId = $dataToPaso->id_paso_externo;
            $tipoSeccionTo = 'bot';
            $sql = "DELETE FROM {$dyalogo_canales_electronicos}.dy_base_autorespuestas_contenidos WHERE id_base_autorespuestas = {$fromAutorespuestaId} AND accion = 2 AND id_base_transferencia = {$dataToPaso->id_paso_externo}";
        }else{
            $toAutorespuestaId =  getIdBaseAutorespuestaByIdSeccion($to);
            $tipoSeccionTo = 'seccion_normal';
            $sql = "DELETE FROM {$dyalogo_canales_electronicos}.dy_base_autorespuestas_contenidos WHERE id_base_autorespuestas = {$fromAutorespuestaId} AND accion = 2 AND id_base_transferencia = {$toAutorespuestaId}";
        }

        $estado = "ok";
        $mensajeError = "";
        if(!$mysqli->query($sql)){
            $mensajeError = "Se presento un error al borrar la configuracion";
            $estado = "fallo";
        }

        // end forma numero dos
        
        if($tipoSeccionFrom == 'transaccional'){
            ordenarAcciones($fromAutorespuestaId);
        }
        // Verificamos si quedaron mas pasos que apuntan a la flecha si no ya se elimina la flecha como tal

        // if($tipoSeccionTo == 'campana'){
        //     $sqlVerificar = "SELECT id FROM {$dyalogo_canales_electronicos}.dy_base_autorespuestas_contenidos where id_base_autorespuestas = {$from} AND accion = 1 AND id_campana = {$to}";
        // }else{
        //     $sqlVerificar = "SELECT id FROM {$dyalogo_canales_electronicos}.dy_base_autorespuestas_contenidos where id_base_autorespuestas = {$from} AND accion = 2 AND id_base_transferencia = {$to}";
        // }
        
        // $resVerificar = $mysqli->query($sqlVerificar);

        // if($resVerificar && $resVerificar->num_rows <= 0){
        //     // Si no hay coincidencias elimino la flecha
        //     $fromSeccion = getSeccionIdByIdBaseAutorespuesta($from);
            
        //     if($tipoSeccionTo == 'campana' || $tipoSeccionTo == 'bot'){
        //         $toSeccion = getSeccionByIdPasoExterno($to);
        //     }else{
        //         $toSeccion = getSeccionIdByIdBaseAutorespuesta($to);
        //     }

        // }
        $sqlDeleteFlecha = "DELETE FROM {$dyalogo_canales_electronicos}.secciones_conexiones_bot WHERE desde = {$from} AND hasta = {$to}";
        $mysqli->query($sqlDeleteFlecha);

        echo json_encode([
            "mensaje" => "Configuracion actualizada",
            "estado" => $estado,
            "mensajeError" => $mensajeError
        ]);
    }

    if(isset($_GET['obtenerIdPaso'])){
        $tipoPaso = $_POST['tipoPasoBot'];
        $key = $_POST['key'];

        $paso = 0;
        $sql = '';

        // Valido el tipoPaso
        if($tipoPaso == 'bot'){
            $sql = "SELECT a.id_estpas AS paso_id FROM dyalogo_canales_electronicos.dy_bot a
            INNER JOIN dyalogo_canales_electronicos.secciones_bot b ON a.id = b.id_paso_externo
            WHERE b.id = {$key} LIMIT 1";
        }

        if($tipoPaso == 'campana'){
            $sql = "SELECT ESTPAS_ConsInte__b AS paso_id FROM DYALOGOCRM_SISTEMA.ESTPAS
            INNER JOIN DYALOGOCRM_SISTEMA.CAMPAN ON ESTPAS_ConsInte__CAMPAN_b = CAMPAN_ConsInte__b
            INNER JOIN dyalogo_canales_electronicos.secciones_bot ON CAMPAN_IdCamCbx__b = id_paso_externo
            WHERE id = {$key} LIMIT 1";
        }
        
        if($sql != ''){
            $res = $mysqli->query($sql);
    
            if($res && $res->num_rows > 0){
                
                $data = $res->fetch_object();
                $paso = $data->paso_id;
    
            }else{
                // Si no encuentra el paso por bot le realizo una segunda vuelta
                if($tipoPaso == 'bot'){
                    $sql = "SELECT a.id_estpas AS paso_id FROM dyalogo_canales_electronicos.dy_base_autorespuestas a
                    INNER JOIN dyalogo_canales_electronicos.secciones_bot b ON a.id = b.id_paso_externo
                    WHERE b.id = {$key} LIMIT 1;";

                    $res2 = $mysqli->query($sql);

                    if($res2 && $res2->num_rows > 0){
                        $data = $res2->fetch_object();
                        $paso = $data->paso_id;
                    }
                }

            }
        }

        echo json_encode([
            "estado" => "ok",
            "paso" => $paso
        ]);
    }

    if(isset($_GET['validarConexionesFlecha'])){

        $from = $mysqli->real_escape_string($_POST['from']);
        $to = $mysqli->real_escape_string($_POST['to']);

        $aux = false;
        $ambosSentidos = false;

        $dataFrom = [];
        $dataTo = [];
        $dataSentido1 = [];
        $dataSentido2 = [];

        $sqlSentido1 = "SELECT * FROM {$dyalogo_canales_electronicos}.secciones_conexiones_bot WHERE (desde = {$from} AND hasta = ${to})";
        $resSentido1 = $mysqli->query($sqlSentido1);

        if($resSentido1 && $resSentido1->num_rows > 0){
            $aux = true;

            $dataSentido1 = $resSentido1->fetch_object();
        }

        // Valido si continuo con el proceso
        if($aux){
            $aux = false;

            $sqlSentido2 = "SELECT * FROM {$dyalogo_canales_electronicos}.secciones_conexiones_bot WHERE (desde = {$to} AND hasta = ${from})";
            $resSentido2 = $mysqli->query($sqlSentido2);

            if($resSentido2 && $resSentido2->num_rows > 0){
                $aux = true;
                $dataSentido2 = $resSentido2->fetch_object();
            }
        }

        // Traigo las dos secciones
        $sqlSeccionFrom = "SELECT * FROM {$dyalogo_canales_electronicos}.secciones_bot WHERE id = {$from}";
        $resFrom = $mysqli->query($sqlSeccionFrom);
        if($resFrom && $resFrom->num_rows > 0){
            $dataFrom = $resFrom->fetch_object();
        }

        // Si llegamos hasta aqui significa que hay flechas en ambos sentidos
        if($aux){
            if($dataSentido1->from_port == $dataSentido2->to_port && $dataSentido2->from_port == $dataSentido1->to_port){
                $ambosSentidos = true;
            }

            $sqlSeccionTo = "SELECT * FROM {$dyalogo_canales_electronicos}.secciones_bot WHERE id = {$to}";
            $resTo = $mysqli->query($sqlSeccionTo);

            if($resTo && $resTo->num_rows > 0){
                $dataTo = $resTo->fetch_object();
            }
        }

        echo json_encode([
            "valido" => $aux,
            "ambosSentidos" => $ambosSentidos,
            "sentido1" => $dataSentido1,
            "sentido2" => $dataSentido2,
            "dataFrom" => $dataFrom,
            "dataTo" => $dataTo
        ]);
    }

    if(isset($_GET['buscarCamposBd'])){

        $id = $_POST['id'];

        $campos = getCamposBd($id);

        echo json_encode([
            "campos" => $campos,
            "estado" => "ok"
        ]);
    }

    if(isset($_GET['eliminarSeccion'])){
        
        $id = $_POST['id'];

        $estado = 'ok';
        $mensajeError = 'Seccion eliminada correctamente';

        // Traigo la informacion de la seccion a eliminar
        $sql = "SELECT * FROM {$dyalogo_canales_electronicos}.secciones_bot WHERE id = {$id} LIMIT 1";
        $res = $mysqli->query($sql);
        $seccion = $res->fetch_object();

        // Si la seccion es de bienvenida o despedida no puedo borrarla
        if($seccion->tipo_seccion == '3' || $seccion->tipo_seccion == '4'){
            $mensajeError = "No es posible borrar una seccion predeterminada";
            echo json_encode([
                "estado" => $estado,
                "mensajeError" => $mensajeError
            ]);
            exit();
        }

        // Realizo el proceso de eliminacion a las secciones conversacionales y transaccionales
        if($seccion->tipo_seccion == '1' || $seccion->tipo_seccion == '2'){

            // Primero necesito obtener las secciones 
            $sql = "SELECT * FROM {$dyalogo_canales_electronicos}.dy_base_autorespuestas WHERE id_seccion = {$id}";
            $resSec = $mysqli->query($sql);

            // Validamos si existe por lo menos un registro
            if($resSec && $resSec->num_rows > 0){

                // Guardamos la info de autorespuestas en un array
                $autorespuestas = [];

                while ($row = $resSec->fetch_object()) {
                    $autorespuestas[] = $row;
                }

                for ($i=0; $i < count($autorespuestas); $i++) { 

                    // Empezamos con el borrado de los webservices
                    $sql = "SELECT * FROM {$dyalogo_canales_electronicos}.dy_bot_ws WHERE id_base_autorespuestas = {$autorespuestas[$i]->id}";
                    $resBot_ws = $mysqli->query($sql);

                    if($resBot_ws && $resBot_ws->num_rows > 0){
                        // al existir deben haber regristros en dy_bot_ws_parametros
                        $bot_ws = $resBot_ws->fetch_object();

                        $sql = "SELECT * FROM {$dyalogo_canales_electronicos}.dy_bot_ws_parametros WHERE id_bot_ws = {$bot_ws->id}";
                        $resBot_ws_parametros = $mysqli->query($sql);
                        if($resBot_ws_parametros && $resBot_ws_parametros->num_rows > 0){

                            // Si hay campos lo guardamos en una variable
                            $bot_ws_parametros = [];
                            while ($row_bwp = $resBot_ws_parametros->fetch_object()) {
                                $bot_ws_parametros[] = $row_bwp;
                            }

                            // Empezamos el borrado
                            $delete_bot_ws_parametros = "DELETE FROM {$dyalogo_canales_electronicos}.dy_bot_ws_parametros WHERE id_bot_ws = {$bot_ws->id}";
                            if($mysqli->query($delete_bot_ws_parametros)){
                                guardar_auditoria('BOT-DELETE_id'. $seccion->id_bot, 'DEL dy_bot_ws_parametros -> '.json_encode($bot_ws_parametros));
                            }else{
                                $estado = "error";
                                $mensajeError = "Error " . $mysqli->error;
                            }

                        }

                        // Aqui ya borramos los ws
                        $delete_ws = "DELETE FROM {$dyalogo_canales_electronicos}.dy_bot_ws WHERE id_base_autorespuestas = {$autorespuestas[$i]->id}";
                        if($mysqli->query($delete_ws)){
                            guardar_auditoria('BOT-DELETE_id'. $seccion->id_bot, 'DEL dy_bot_ws -> '.json_encode($bot_ws));
                        }else{
                            $estado = "error";
                            $mensajeError = "Error " . $mysqli->error;
                        }
                    }

                    // Despues procedemos a borrar dy_base_autorespuestas_contenidos
                    $sql = "SELECT * FROM {$dyalogo_canales_electronicos}.dy_base_autorespuestas_contenidos WHERE id_base_autorespuestas = {$autorespuestas[$i]->id}";
                    $resAutorespuestaContenidos = $mysqli->query($sql);

                    if($resAutorespuestaContenidos && $resAutorespuestaContenidos->num_rows > 0){

                        // Si hay registros lo recorremos
                        $autoresContenidos = [];

                        while ($row_ac = $resAutorespuestaContenidos->fetch_object()) {
                            $autoresContenidos[] = $row_ac;

                            // Validamos si es una accion final con condiciones para borrar las condiciones que tenga asociada
                            if($row_ac->pregunta === 'DY_ACCION_CONDICON'){
                                $sql = "SELECT * FROM {$dyalogo_canales_electronicos}.condiciones_bot WHERE id_base_autorespuestas_contenido = {$row_ac->id}";
                                $resCondiciones = $mysqli->query($sql);

                                if($resCondiciones && $resCondiciones->num_rows > 0){
                                    // Si hay campos lo guardamos en una variable
                                    $condiciones_bot = [];
                                    while ($cond = $resCondiciones->fetch_object()) {
                                        $condiciones_bot[] = $cond;
                                    }

                                    // Realizamos el borrado de las condiciones
                                    $delete_condiciones = "DELETE FROM {$dyalogo_canales_electronicos}.condiciones_bot WHERE id_base_autorespuestas_contenido = {$row_ac->id}";
                                    if($mysqli->query($delete_condiciones)){
                                        guardar_auditoria('BOT-DELETE_id'. $seccion->id_bot, 'DEL dy_condiciones_bot -> '.json_encode($condiciones_bot));
                                    }else{
                                        $estado = "error";
                                        $mensajeError = "Error " . $mysqli->error;
                                    }
                                }
                            }
                        }

                        // Iniciamos el proceso de borrado de autorespuestas contenido
                        $deleteAC = "DELETE FROM {$dyalogo_canales_electronicos}.dy_base_autorespuestas_contenidos WHERE id_base_autorespuestas = {$autorespuestas[$i]->id}";
                        if($mysqli->query($deleteAC)){
                            guardar_auditoria('BOT-DELETE_id'. $seccion->id_bot, 'DEL dy_autorespuestas_contenido -> '.json_encode($autoresContenidos));
                        }else{
                            $estado = "error";
                            $mensajeError = "Error " . $mysqli->error;
                        }
                    }

                }

                // Procedemos a borrar las autorespuestas
                $deleteAutorespuestas = "DELETE FROM {$dyalogo_canales_electronicos}.dy_base_autorespuestas WHERE id_seccion = {$id}";
                if($mysqli->query($deleteAutorespuestas)){
                    guardar_auditoria('BOT-DELETE_id'. $seccion->id_bot, 'DEL dy_autorespuestas -> '.json_encode($autorespuestas));
                }else{
                    $estado = "error";
                    $mensajeError = "Error " . $mysqli->error;
                }
            }

        }

        // Borro las conexiones
        $sqlDeleteConexiones = "DELETE FROM {$dyalogo_canales_electronicos}.secciones_conexiones_bot WHERE desde = {$id} OR hasta = {$id}";
        if($mysqli->query($sqlDeleteConexiones)){
            guardar_auditoria('BOT-DELETE', 'DEL CONEXIONES SECCION -> '.$id);
        }else{
            $estado = "error";
            $mensajeError = "Error " . $mysqli->error;
        }

        // Borro la informacion de las secciones
        $sqlDeleteSeccion = "DELETE FROM {$dyalogo_canales_electronicos}.secciones_bot WHERE id = {$id}";
        if($mysqli->query($sqlDeleteSeccion)){
            guardar_auditoria('BOT-DELETE', 'DEL SECCION -> '.json_encode($seccion));
        }else{
            $estado = "error";
            $mensajeError = "Error " . $mysqli->error;
        }
        

        echo json_encode([
            "estado" => $estado,
            "mensajeError" => $mensajeError
        ]);
    }

}

function setearSeccionTransaccionalConfiuracionInicial($autorespuestaId, $autorespuestaAccionFinalId){

    global $mysqli;
    global $dyalogo_canales_electronicos;    
    
    $update = "UPDATE {$dyalogo_canales_electronicos}.dy_base_autorespuestas SET tipo_base = 0 WHERE id = {$autorespuestaId}";
    if($mysqli->query($update) == TRUE){
        // Elimino las autorespuestas contenidas
        $delete = "DELETE FROM {$dyalogo_canales_electronicos}.dy_base_autorespuestas_contenidos WHERE id_base_autorespuestas = {$autorespuestaId}";
        $mysqli->query($delete);

        // Validamos si hay acciones conectadas a la accion final para pasarlo a la principal
        $sql = "UPDATE {$dyalogo_canales_electronicos}.dy_base_autorespuestas_contenidos SET id_base_transferencia = {$autorespuestaAccionFinalId} WHERE accion = 2 AND id_base_transferencia = {$autorespuestaId} AND pregunta != 'DY_CANCEL'";
        $mysqli->query($sql);
    }
}


function guardar_auditoria($accion, $superAccion){
    global $mysqli;
    global $BaseDatos_systema;

    $str_Lsql = "INSERT INTO " . $BaseDatos_systema . ".AUACAD (AUACAD_Fecha_____b , AUACAD_Hora______b, AUACAD_Ejecutor__b, AUACAD_TipoAcci__b , AUACAD_SubTipAcc_b, AUACAD_Accion____b , AUACAD_Huesped___b ) VALUES ('" . date('Y-m-d H:s:i') . "', '" . date('Y-m-d H:s:i') . "', " . $_SESSION['IDENTIFICACION'] . ", 'G26', '" . $accion . "', '" .$mysqli->real_escape_string($superAccion). "', " . $_SESSION['HUESPED'] . " );";
    $mysqli->query($str_Lsql);
}

// Funciones al crear una bola tipo bot
function insertarSeccionPorDefecto($pasoId, $huesped, $tipoSeccionBienvenida, $nombrePaso, $botId){

    global $mysqli;
    global $dyalogo_canales_electronicos;
    global $BaseDatos_systema;

    $fraseSinRespuesta = 'No encuentro una respuesta a lo que escribiste';

    if(is_null($nombrePaso) || $nombrePaso == ''){
        $nombrePaso = "bot_{$pasoId}";
    }

    // Actualizo el nombre del paso
    $updatePaso = "UPDATE {$BaseDatos_systema}.ESTPAS SET ESTPAS_Comentari_b = '{$nombrePaso}' WHERE ESTPAS_ConsInte__b = {$pasoId}";
    $mysqli->query($updatePaso);

    // creo las secciones por defecto
    $sqlInsert = "INSERT INTO {$dyalogo_canales_electronicos}.secciones_bot (nombre, id_estpas, tipo_seccion, orden, id_bot) VALUES ('Bienvenida', {$pasoId}, 3, 1, {$botId})";
    $mysqli->query($sqlInsert);

    $seccionId = $mysqli->insert_id;

    if($tipoSeccionBienvenida == 1){
        $sqlInsert2 = "INSERT INTO {$dyalogo_canales_electronicos}.dy_base_autorespuestas (nombre, id_huesped, id_estpas, tipo_base, buscar_ani_dato, id_seccion, frase_no_encuentra_respuesta, mostrar_primera_pregunta_automatica, id_bot, timeout_cliente, frase_timeout) 
            VALUES ('Bienvenida', {$huesped}, {$pasoId}, 1, 1, {$seccionId}, '{$fraseSinRespuesta}', 1, {$botId}, 60, 'Que pena te ocupaste y no respondiste mas, que estes muy bien.')";
        $mysqli->query($sqlInsert2);
    
        $autorespuestaId = $mysqli->insert_id;
    
        $sqlInsertCampo = "INSERT INTO {$dyalogo_canales_electronicos}.dy_base_autorespuestas_contenidos (id_base_autorespuestas, pregunta, tags, respuesta, accion, orden, llave, global) 
            VALUES ({$autorespuestaId}, 'DY_SALUDO', 'DY_SALUDO', 'Pon acá tu menú inicial', 0, 1, 0, 0)";
        $mysqli->query($sqlInsertCampo);
    }
    

    // Seccion de despedida
    $sqlInsert3 = "INSERT INTO {$dyalogo_canales_electronicos}.secciones_bot (nombre, id_estpas, tipo_seccion, orden, id_bot) VALUES ('Despedida', {$pasoId}, 4, 2, {$botId})";
    $mysqli->query($sqlInsert3);

    $seccionId2 = $mysqli->insert_id;

    $sqlInsert4 = "INSERT INTO {$dyalogo_canales_electronicos}.dy_base_autorespuestas (nombre, id_huesped, id_estpas, tipo_base, buscar_ani_dato, id_seccion, frase_no_encuentra_respuesta, mostrar_primera_pregunta_automatica, id_bot, timeout_cliente, frase_timeout) 
    VALUES ('Despedida', {$huesped}, {$pasoId}, 1, 1, {$seccionId2}, '{$fraseSinRespuesta}', 1, {$botId}, 60, 'Que pena te ocupaste y no respondiste mas, que estes muy bien.')";
    $mysqli->query($sqlInsert4);

    $autorespuestaId = $mysqli->insert_id;

    $sqlInsertCampo2 = "INSERT INTO dyalogo_canales_electronicos.dy_base_autorespuestas_contenidos (id_base_autorespuestas, pregunta, tags, respuesta, accion, orden, llave, global) 
    VALUES ({$autorespuestaId}, 'DY_EXIT', 'DY_EXIT', 'fue un gusto atenderte.  Por favor no escribas nada mas a menos que necesites algo adicional', 0, 1, 0, 0)";
    $mysqli->query($sqlInsertCampo2);

}

// Esta funcion traduce lo que llega como strin a un json
function traducirFljujograma($stringFlujograma){

    $stringFlujograma = str_replace("\\n", "", $stringFlujograma);
    $stringFlujograma = str_replace("\\r", "", $stringFlujograma);
    $stringFlujograma = str_replace('\\', "", $stringFlujograma);
    $jsonFlujo = json_decode($stringFlujograma);

    return $jsonFlujo;
}

function sanear_strings($string) { 
    
    // $string = utf8_decode($string);
    $string = str_replace( array('á', 'à', 'ä', 'â', 'ª', 'Á', 'À', 'Â', 'Ä'), array('a', 'a', 'a', 'a', 'a', 'A', 'A', 'A', 'A'), $string );
    $string = str_replace( array('é', 'è', 'ë', 'ê', 'É', 'È', 'Ê', 'Ë'), array('e', 'e', 'e', 'e', 'E', 'E', 'E', 'E'), $string ); 
    $string = str_replace( array('í', 'ì', 'ï', 'î', 'Í', 'Ì', 'Ï', 'Î'), array('i', 'i', 'i', 'i', 'I', 'I', 'I', 'I'), $string ); 
    $string = str_replace( array('ó', 'ò', 'ö', 'ô', 'Ó', 'Ò', 'Ö', 'Ô'), array('o', 'o', 'o', 'o', 'O', 'O', 'O', 'O'), $string ); 
    $string = str_replace( array('ú', 'ù', 'ü', 'û', 'Ú', 'Ù', 'Û', 'Ü'), array('u', 'u', 'u', 'u', 'U', 'U', 'U', 'U'), $string ); 
    $string = str_replace( array('ñ', 'Ñ', 'ç', 'Ç'), array('n', 'N', 'c', 'C',), $string ); 
    //Esta parte se encarga de eliminar cualquier caracter extraño 
    // Le quitare que remplace los puntos
    $string = str_replace( array("\\", "¨", "º", "-", "~", "#", "@", "|", "!", "\"", "·", "$", "%", "&", "/", "(", ")", "?", "'", "¡", "¿", "[", "^", "`", "]", "+", "}", "{", "¨", "´", ">“, “< ", ";", ",", ":"), '', $string ); 
    return $string; 
}

function reformatearVariable($nombre){

    $nombre = sanear_strings($nombre);
    $nombre = str_replace(' ', '_', $nombre);
    // $nombre = strtolower($nombre);
    $nombre = substr($nombre, 0, 50);

    return $nombre;
}

// Funciones de la seccion

// Esta funcion trae la informacion de la bd
function obtenerIdBaseByPasoId($id){

    global $mysqli;
    global $BaseDatos_systema;
    
    $sql = "SELECT ESTRAT_ConsInte__b AS id, ESTRAT_ConsInte_GUION_Pob AS id_base FROM {$BaseDatos_systema}.ESTRAT JOIN {$BaseDatos_systema}.ESTPAS ON ESTRAT_ConsInte__b = ESTPAS_ConsInte__ESTRAT_b WHERE ESTPAS_ConsInte__b = {$id} LIMIT 1";
    $res = $mysqli->query($sql);

    $data = $res->fetch_object();

    return $data->id_base;
}

// Trae la informacion de la seccion
function getSeccion($id) {

    global $mysqli;
    global $dyalogo_canales_electronicos;

    $sqlSeccioBot = "SELECT id, nombre, tipo_seccion FROM {$dyalogo_canales_electronicos}.secciones_bot WHERE id = {$id} LIMIT 1";

    $resSeccioBot = $mysqli->query($sqlSeccioBot);

    $seccion = $resSeccioBot->fetch_object();
    
    return $seccion;
}

function getAllSeccionesByPasoId($id){

    global $mysqli;
    global $dyalogo_canales_electronicos;

    $camposSeccionesBot = [];

    $sqlListaSecciones = "SELECT a.id, a.nombre, b.id_base, b.tipo_base, b.id AS base_autorespuesta_id FROM {$dyalogo_canales_electronicos}.secciones_bot a
        JOIN {$dyalogo_canales_electronicos}.dy_base_autorespuestas b
            ON a.id = b.id_seccion
        WHERE a.id_estpas = {$id}
        GROUP BY a.id";
    $resSeccBots = $mysqli->query($sqlListaSecciones);

    if($resSeccBots->num_rows > 0){
        while ($row = $resSeccBots->fetch_object()) {

            // Verifico que si hay secciones de tipo 0 cambio el id_base_autorespuesta para que apunte a las acciones finales
            if($row->tipo_base == 0){
                $sql = "SELECT id, tipo_base FROM {$dyalogo_canales_electronicos}.dy_base_autorespuestas WHERE id_seccion = {$row->id} AND tipo_base = 4";
                $resAut = $mysqli->query($sql);

                if($resAut && $resAut->num_rows > 0){
                    $aut = $resAut->fetch_object();
                    $row->base_autorespuesta_id = $aut->id;
                }
            }

            $camposSeccionesBot[] = $row;
        }
    }

    return $camposSeccionesBot;
}

function getAutorespuestasContenidos($id, $tipoSeccion){

    global $mysqli;
    global $dyalogo_canales_electronicos;

    $campos = [];

    if($tipoSeccion == 2 || $tipoSeccion == 2){
        $sqlCampos = "SELECT * FROM {$dyalogo_canales_electronicos}.dy_base_autorespuestas_contenidos WHERE id_base_autorespuestas = {$id} AND global = 0 AND pregunta != 'DY_CANCEL' ORDER BY orden,id ASC";
    }else{
        $sqlCampos = "SELECT * FROM {$dyalogo_canales_electronicos}.dy_base_autorespuestas_contenidos WHERE id_base_autorespuestas = {$id} AND global = 0 ORDER BY orden,id ASC";
    }
    $resCampos = $mysqli->query($sqlCampos);

    if($resCampos->num_rows > 0){
        $i = 0;
        while($row = $resCampos->fetch_object()){
            $row->respuesta = strip_html_tags(['a'], $row->respuesta);
            $campos[$i] = $row;
            $i++;
        }
    }

    return $campos;
}

function getBaseAutorespuestas($id){

    global $mysqli;
    global $dyalogo_canales_electronicos;

    $baseActual = 0;
    $esBaseOrigen = false;

    $sqlBaseAutorespuesta = "SELECT id, nombre, id_base, id_estpas, id_base_llave_principal FROM {$dyalogo_canales_electronicos}.dy_base_autorespuestas WHERE id = {$id} LIMIT 1";

    $resBaseAutorespuesta = $mysqli->query($sqlBaseAutorespuesta);

    $baseAutorespuesta = $resBaseAutorespuesta->fetch_object();

    $baseId = obtenerIdBaseByPasoId($baseAutorespuesta->id_estpas);
    // Si la base es null debo encontrar cual es la base actual
    if(is_null($baseAutorespuesta->id_base)){
        $esBaseOrigen = true;
        $baseActual = $baseId;
    }else{
        // Valido que tanto el id base de la seccion sea igual al del paso
        if($baseId == $baseAutorespuesta->id_base){
            $esBaseOrigen = true;
        }

        $baseActual = $baseAutorespuesta->id_base;
    }

    return [
        "id" => $baseAutorespuesta->id,
        "nombre" => $baseAutorespuesta->nombre,
        "id_base" => $baseActual,
        "id_base_llave_principal" => $baseAutorespuesta->id_base_llave_principal,
        "baseOrigen" => $baseId
    ];
}

// Obtengo los campos de la base de datos de la base especifica
function getCamposBd($baseId){

    global $mysqli;
    global $BaseDatos_systema;

    $camposBd = [];

    $sqlCamposBd = "SELECT PREGUN_ConsInte__b AS id, PREGUN_Texto_____b AS nombre FROM {$BaseDatos_systema}.PREGUN WHERE PREGUN_ConsInte__GUION__b = {$baseId} AND PREGUN_Tipo______b NOT IN (11, 13) AND PREGUN_Texto_____b NOT LIKE '%_DY%'";
    $resCamposBd = $mysqli->query($sqlCamposBd);

    if($resCamposBd && $resCamposBd->num_rows > 0){
        $camposBd[] = ["id" => "_ConsInte__b", "nombre" => "BD_ID"];
        while ($row2 = $resCamposBd->fetch_object()) {
            $camposBd[] = $row2;
        }
    }

    return $camposBd;
}

function getAccionesFinales($seccionId){

    global $mysqli;
    global $dyalogo_canales_electronicos;

    // Primero busco el autorespuesta asociado 
    $sqlAut = "SELECT id FROM {$dyalogo_canales_electronicos}.dy_base_autorespuestas WHERE id_seccion = {$seccionId} AND tipo_base = 4 LIMIT 1";
    $resAut = $mysqli->query($sqlAut);
    $autoresp = $resAut->fetch_object();

    $campos = [];

    $sql = "SELECT * FROM dyalogo_canales_electronicos.dy_base_autorespuestas_contenidos WHERE id_base_autorespuestas = {$autoresp->id} ORDER BY orden_finalizacion, id ASC";
    $res = $mysqli->query($sql);

    if($res && $res->num_rows > 0){
        $i = 0;
        while ($row = $res->fetch_object()) {
            $campos[$i] = $row;
            $campos[$i]->condicion = getNombreDeCondicionesSeccion($row->id);
            $i++;
        }
    }

    return $campos;
}

function getNombrePregun($id){

    global $mysqli;
    global $BaseDatos_systema;

    $sql = "SELECT PREGUN_ConsInte__b AS id, PREGUN_Texto_____b AS nombre FROM {$BaseDatos_systema}.PREGUN WHERE PREGUN_ConsInte__b = {$id}";
    $res = $mysqli->query($sql);

    $data = $res->fetch_object();

    return $data->nombre;
}

function getCamposSeccionTransaccional($seccionId){

    global $mysqli;
    global $dyalogo_canales_electronicos;

    $campos = [];

    $sql = "SELECT a.*, b.consulta_dy_tabla, b.consulta_dy_campos, b.consulta_dy_variables, b.consulta_dy_condicion FROM {$dyalogo_canales_electronicos}.dy_base_autorespuestas_contenidos a JOIN {$dyalogo_canales_electronicos}.dy_base_autorespuestas b ON b.id = a.id_base_autorespuestas WHERE b.id_seccion = {$seccionId} AND orden_global > 0 ORDER BY orden_global ASC";
    $res = $mysqli->query($sql);

    if($res->num_rows > 0){
        $i = 0;
        while($row = $res->fetch_object()){
            $campos[$i] = $row;
            $i++;
        }
    }

    return $campos;
}

function mapeoDeValores($tipoBase, $orden, $strPgta_t, $strRpta_t, $strAccion_t, $strCampan_t, $strBot_t, $pregun_t, $strSeccionBot_t = 0){

    $valores = [];
    $orden = $orden + 1;

    if($strRpta_t != ''){
        $rpta = formatearStringDeVariables($strRpta_t);
        $rpta = formatearUrlAUrlLinkeable($rpta);
    }else{
        $rpta = ' ';
    }

    if($strSeccionBot_t == 0){
        $seccionId = $strBot_t;
        $botId = 0;
    }else{
        $seccionId = $strSeccionBot_t;
        $botId = $strBot_t;
    }

    switch ($tipoBase) {
        case '1':
            // Traigo los tags correspondientes
            $tagsFormateado = obtenerTags($strPgta_t);
            $valores = [
                'pregunta' => $strPgta_t,
                'tags' => $tagsFormateado,
                'respuesta' => $rpta,
                'accion' => $strAccion_t,
                'id_campana' => $strCampan_t,
                'id_bot' => $botId,
                'id_seccion' => $seccionId,
                'id_pregun' => $pregun_t,
                'nombre_variable' => 'null' ,
                'orden' => 'null',
                'llave' => 0
            ];
            break;
        
        case '2':
            $nombreVariable = obtenerNombreVariable($pregun_t);

            $llave = 0;

            // if($orden == 1){ $llave = 1; }
            $pregunta = formatearStringDeVariables($strPgta_t);

            $valores = [
                'pregunta' => $pregunta,
                'tags' => 'NONE',
                'respuesta' => $rpta,
                'accion' => $strAccion_t,
                'id_campana' => $strCampan_t,
                'id_bot' => $botId,
                'id_seccion' => $seccionId,
                'id_pregun' => $pregun_t,
                'nombre_variable' => $nombreVariable,
                'orden' => $orden,
                'llave' => 0
            ];

            break;
    }

    return $valores;
}

function obtenerNombreVariable($pregunId){
    global $mysqli;
    global $BaseDatos_systema;

    $nombre = '';

    $sql = "SELECT PREGUN_Texto_____b FROM {$BaseDatos_systema}.PREGUN WHERE PREGUN_ConsInte__b = {$pregunId}";
    $res = $mysqli->query($sql);
    
    if($res && $res->num_rows > 0){
        $key = $res->fetch_object();
    
        $nombre = $key->PREGUN_Texto_____b;
    
        $nombre = reformatearVariable($nombre);
    }

    return $nombre;
}

function obtenerTags($tags){
    global $mysqli;
    global $dyalogo_canales_electronicos;

    $nuevosTags = "";

    // divido el string en un arreglo
    $arrTags = explode(',', $tags);
    
    foreach ($arrTags as $key => $value) {
        if(substr($value, 0, 1) === '#'){
            // Busco esa coincidencia en la bd
            $sql = "SELECT * FROM {$dyalogo_canales_electronicos}.tags_comunes WHERE tag_disparador = '{$value}' LIMIT 1";
            $res = $mysqli->query($sql);
            if($res && $res->num_rows > 0){
                $data = $res->fetch_object();
                
                if($nuevosTags == ''){
                    $nuevosTags .= $data->expresiones;
                }else{
                    $nuevosTags .= ','.$data->expresiones;
                }    
            }
        }else{
            if($nuevosTags == ''){
                $nuevosTags .= $value;
            }else{
                $nuevosTags .= ','.$value;
            }
        }
    }

    return $nuevosTags;
}

function formatearStringDeVariables($myStr){
    
    $str_1 = html_entity_decode($myStr);
    
    $str_1 = str_replace('&nbsp;', ' ', $str_1);
    
    $str_1 = str_replace('>${', '> ${', $str_1);
    $str_1 = str_replace('}<', '} <', $str_1);
    $str_1 = str_replace(' </strong>', '</strong> ', $str_1);
    $str_1 = str_replace('<p class="MsoNormal">', '<p>', $str_1);
    $str_1 = preg_replace("/([^\s])(<\/p>)/", '$1 </p>', $str_1);

    // $str_1 = str_replace( array('&Agrave;','&Aacute;','&Acirc;','&Atilde;','&Auml;','&Aring;','&AElig;','&Ccedil;'), array('À','Á','Â','Ã','Ä','Å','Æ','Ç'), $str_1 );
    // $str_1 = str_replace( array('&Egrave;','&Eacute;','&Ecirc;','&Euml;',), array('È','É','Ê','Ë'), $str_1 );
    // $str_1 = str_replace( array('&Igrave;','&Iacute;','&Icirc;','&Iuml;'), array('Ì','Í','Î','Ï'), $str_1 );
    // $str_1 = str_replace( array('&Ograve;','&Oacute;','&Ocirc;','&Otilde;','&Ouml;'), array('Ò','Ó','Ô','Õ','Ö'), $str_1 );
    // $str_1 = str_replace( array('&Ugrave;','&Uacute;','&Ucirc;','&Uuml;'), array('Ù','Ú','Û','Ü'), $str_1 );
    // $str_1 = str_replace( array('&agrave;','&aacute;','&acirc;','&atilde;','&auml;','&aring;'), array('à','á','â','ã','ä','å'), $str_1 );
    // $str_1 = str_replace( array('&egrave;','&eacute;','&ecirc;','&euml;'), array('è','é','ê','ë'), $str_1 );
    // $str_1 = str_replace( array('&igrave;','&iacute;','&icirc;','&iuml;'), array('ì','í','î','ï'), $str_1 );
    // $str_1 = str_replace( array('&ograve;','&oacute;','&ocirc;','&otilde;','&ouml;'), array('ò','ó','ô','õ','ö'), $str_1 );
    // $str_1 = str_replace( array('&ugrave;','&uacute;','&ucirc;','&uuml;'), array('ù','ú','û','ü'), $str_1 );
    
    // $str_1 = str_replace( array('&Ntilde;','&ntilde;'), array('Ñ','ñ'), $str_1 );

    // $str_1 = str_replace( array('&iquest;','&iexcl;'), array('¿','¡'), $str_1 );

    return $str_1;
}

function formatearUrlAUrlLinkeable($texto){

    $cadena_resultante = "";

    $texto = preg_replace("/<\//", " </", $texto);

    //filtro los enlaces normales
    $cadena_resultante = preg_replace("/((http|https|www)[^\s]+)/", '<a href="$1" target="_blank">$0</a>', $texto);

    //miro si hay enlaces con solamente www, si es así le añado el https://
    $cadena_resultante = preg_replace("/href=\"www/", 'href="https://www', $cadena_resultante);

    return $cadena_resultante;
}

function strip_html_tags(array $tags, string $str){ 
    
    $html = array();

    foreach ($tags as $tag) {
        $html[] = "/(<(?:\/".$tag."|".$tag.")[^>]*>)/i";
    }

    $data = preg_replace($html, '', $str);

    return $data;
}

function insertarCapturaDeDatos($autorespuestaId, $pregunta, $respuesta, $accion, $pregunId, $orden, $ordenGlobal, $variableCaptura, $bot, $usarCampoGestion, $pregunGestionExistente){

    global $mysqli;
    global $dyalogo_canales_electronicos;
    global $BaseDatos_systema;

    $pregunGestionId = 'NULL';
    $campoGestionPropio = 0;

    // Todos
    if($accion == 3){
        $nombreVariable = obtenerNombreVariable($pregunId);
        $pregunGestionId = verificarCampoPregun($nombreVariable, $bot->id_guion_gestion);
    }
    // En memoria
    if($accion == 4){
        $nombreVariable = $variableCaptura;
        $pregunId = 'NULL';
    }
    // Solo bd
    if($accion == 5){
        $nombreVariable = obtenerNombreVariable($pregunId);
    }
    // Guardar en la gestion
    if($accion == 6){
        if($usarCampoGestion == 1){
            $nombreVariable = $variableCaptura;
            $pregunGestionId = verificarCampoPregun($nombreVariable, $bot->id_guion_gestion);
            $campoGestionPropio = 1;
        }else{
            $pregunGestionId = $pregunGestionExistente;
            // Me toca traer el nombre del campo pregun
            $sql = "SELECT PREGUN_ConsInte__b AS id, PREGUN_Texto_____b AS nombre FROM {$BaseDatos_systema}.PREGUN WHERE PREGUN_ConsInte__b = {$pregunGestionId}";
            $resPregun = $mysqli->query($sql);
            if($resPregun && $resPregun->num_rows > 0){
                $pregun = $resPregun->fetch_object();
                $nombreVariable = $pregun->nombre;
            }else{
                $nombreVariable = $pregunGestionId;
            }
        }
        $pregunId = 'NULL';
    }

    $sql = "INSERT INTO {$dyalogo_canales_electronicos}.dy_base_autorespuestas_contenidos (
        id_base_autorespuestas, pregunta, tags, respuesta, accion, id_pregun, nombre_variable, orden, llave, global, orden_global, id_pregun_gestion, pregun_gestion_propio
    ) VALUES (
        {$autorespuestaId}, '{$pregunta}', 'NONE', '{$respuesta}', {$accion}, {$pregunId}, '{$nombreVariable}', $orden, 0, 0, {$ordenGlobal}, {$pregunGestionId}, {$campoGestionPropio}
    )";

    if($mysqli->query($sql) !== true){
        echo "Error al insertar el campo para capturar el dato ".$sql;
    }

}

function actualizarCapturaDeDatos($campoId, $autorespuestaId, $pregunta, $respuesta, $accion, $pregunId, $orden, $ordenGlobal, $variableCaptura, $bot, $usarCampoGestion, $pregunGestionExistente){

    global $mysqli;
    global $dyalogo_canales_electronicos;
    global $BaseDatos_systema;

    $sqlPregunGestion = '';
    $campoGestionPropio = 0;

    // Todos
    if($accion == 3){
        $nombreVariable = obtenerNombreVariable($pregunId);
        $pregunGestionId = verificarCampoPregun($nombreVariable, $bot->id_guion_gestion, $campoId);
        $sqlPregunGestion = 'id_pregun_gestion = ' . $pregunGestionId . ',';
    }
    // En memoria
    if($accion == 4){
        $nombreVariable = $variableCaptura;
        $pregunId = 'NULL';
    }
    // Solo bd
    if($accion == 5){
        $nombreVariable = obtenerNombreVariable($pregunId);
    }
    // Guardar en la gestion
    if($accion == 6){
        if($usarCampoGestion == 1){
            $nombreVariable = $variableCaptura;
            $pregunGestionId = verificarCampoPregun($nombreVariable, $bot->id_guion_gestion, $campoId);
            $campoGestionPropio = 1;
        }else{
            $pregunGestionId = $pregunGestionExistente;
            // Me toca traer el nombre del campo pregun
            $sql = "SELECT PREGUN_ConsInte__b AS id, PREGUN_Texto_____b AS nombre FROM {$BaseDatos_systema}.PREGUN WHERE PREGUN_ConsInte__b = {$pregunGestionId}";
            $resPregun = $mysqli->query($sql);
            if($resPregun && $resPregun->num_rows > 0){
                $pregun = $resPregun->fetch_object();
                $nombreVariable = $pregun->nombre;
            }else{
                $nombreVariable = $pregunGestionId;
            }
        }
        $pregunId = 'NULL';
        
        $sqlPregunGestion = 'id_pregun_gestion = ' . $pregunGestionId . ',';
    }

    $sql = "UPDATE {$dyalogo_canales_electronicos}.dy_base_autorespuestas_contenidos 
        SET id_base_autorespuestas = {$autorespuestaId}, pregunta = '{$pregunta}', respuesta = '{$respuesta}', 
        accion = {$accion}, id_pregun = {$pregunId}, nombre_variable = '{$nombreVariable}',
        {$sqlPregunGestion}
        orden = $orden, orden_global = {$ordenGlobal}, pregun_gestion_propio = {$campoGestionPropio} WHERE id = {$campoId}";

    if($mysqli->query($sql) !== true){
        echo "Error al insertar el campo para capturar el dato ".$sql;
    }

}

function cambiarEstadoSeccionPrincipal($autorespuestaId, $tipoSeccion){

    global $mysqli;
    global $dyalogo_canales_electronicos;

    $tipo = traducirTipoBaseAutorrespuesta($tipoSeccion);

    $sql = "UPDATE {$dyalogo_canales_electronicos}.dy_base_autorespuestas SET tipo_base = {$tipo} WHERE id = {$autorespuestaId}";
    $mysqli->query($sql);
}

function crearBaseAutorrespuesta($tipoSeccion, $huespedId, $pasoId, $seccionBotId){

    global $mysqli;
    global $dyalogo_canales_electronicos;

    $autorrespuestaId = 0;

    $tipo = traducirTipoBaseAutorrespuesta($tipoSeccion);

    $nombre = "SUBSECCION_{$tipoSeccion}_{$seccionBotId}";

    // Traigo el id_bot
    $sqlBot = "SELECT * FROM {$dyalogo_canales_electronicos}.dy_bot WHERE id_estpas = {$pasoId} LIMIT 1";
    $resBot = $mysqli->query($sqlBot);
    $dataBot = $resBot->fetch_object();

    $sql = "INSERT INTO {$dyalogo_canales_electronicos}.dy_base_autorespuestas (nombre, id_huesped, id_estpas, tipo_base, buscar_ani_dato, id_seccion, mostrar_primera_pregunta_automatica, id_bot) 
        VALUES ('{$nombre}', {$huespedId}, {$pasoId}, {$tipo}, 1, {$seccionBotId}, 0, {$dataBot->id})";

    if($mysqli->query($sql) === true){
        $autorrespuestaId = $mysqli->insert_id;
    }

    return $autorrespuestaId;
}

function crearAccionFinalUnionSecciones($ultimaSeccionId, $newAutorespuestaId, $posicionAnterior, $tipoSeccion, $ordenGlobal){

    global $mysqli;
    global $dyalogo_canales_electronicos;

    $posicion = $posicionAnterior + 1;

    $tipo = traducirTipoBaseAutorrespuesta($tipoSeccion);

    // Validamos si ya existe
    $sqlExiste = "SELECT * FROM {$dyalogo_canales_electronicos}.dy_base_autorespuestas_contenidos WHERE (pregunta = 'DY_CANCEL' OR pregunta = 'DY_WS_CONSUMO' OR pregunta = 'DY_CONSULTA_BD') AND id_base_autorespuestas = {$ultimaSeccionId}";
    $resExiste = $mysqli->query($sqlExiste);

    if($resExiste && $resExiste->num_rows == 0){

        if($tipo == 2){
            $sql = "INSERT INTO {$dyalogo_canales_electronicos}.dy_base_autorespuestas_contenidos (id_base_autorespuestas, pregunta, tags, respuesta, accion, id_base_transferencia, orden, llave, global, orden_global) 
            VALUES({$ultimaSeccionId}, 'DY_CANCEL', 'Cancelar', ' ', 2, '$newAutorespuestaId', {$posicion}, 0, 0, 0)";
        }
    
        if($tipo == 3){
            $sql = "INSERT INTO {$dyalogo_canales_electronicos}.dy_base_autorespuestas_contenidos (id_base_autorespuestas, pregunta, tags, respuesta, accion, id_base_transferencia, orden, llave, global, orden_global) 
                VALUES({$ultimaSeccionId}, 'DY_WS_CONSUMO', 'si', ' ', 2, '$newAutorespuestaId', 1, 0, 0, {$ordenGlobal})";
        }

        if($tipo == 5){
            $sql = "INSERT INTO {$dyalogo_canales_electronicos}.dy_base_autorespuestas_contenidos (id_base_autorespuestas, pregunta, tags, respuesta, accion, id_base_transferencia, orden, llave, global, orden_global) 
                VALUES({$ultimaSeccionId}, 'DY_CONSULTA_BD', 'DY_CONSULTA_BD', ' ', 2, '$newAutorespuestaId', 1, 0, 0, {$ordenGlobal})";
        }

    }else{
        // Este solo aplicara a la captura
        $sql = "UPDATE {$dyalogo_canales_electronicos}.dy_base_autorespuestas_contenidos SET orden = {$posicion} WHERE id_base_autorespuestas = {$ultimaSeccionId} AND pregunta = 'DY_CANCEL'";
    }
    $mysqli->query($sql);
}

function traducirTipoBaseAutorrespuesta($tipoSeccion){

    $tipo = 0;

    switch ($tipoSeccion) {
        case 'conversacional':
            $tipo = 1;
            break;

        case 'captura':
            $tipo = 2;
            break;

        case 'consulta':
            $tipo = 3;
            break;
        case 'consultaDyalogo':
            $tipo = 5;
            break;
        default:
            # code...
            break;
    }

    return $tipo;
}

function asignarAccionEliminacion($seccionId){

    global $mysqli;
    global $dyalogo_canales_electronicos;

    $sql = "UPDATE {$dyalogo_canales_electronicos}.secciones_conexiones_bot SET accion_flecha = 'DELETE' WHERE desde = {$seccionId}";

    if($mysqli->query($sql) !== true){
        echo "hubo un error al realizar el proceso de borrado de flechas";
    }

}

function eliminarFlechas($desde){

    global $mysqli;
    global $dyalogo_canales_electronicos;
    global $BaseDatos_systema;

    // De las conexiones a borrar las recorro y veo si se pueden borrar conexiones externas
    $sql = "SELECT a.id AS conexion_id, a.hasta, b.tipo_seccion, b.id_paso_externo, b.id_estpas FROM {$dyalogo_canales_electronicos}.secciones_conexiones_bot a
        INNER JOIN {$dyalogo_canales_electronicos}.secciones_bot b ON a.hasta = b.id 
    WHERE desde = {$desde} AND accion_flecha = 'DELETE' AND (b.tipo_seccion = 24 OR b.tipo_seccion = 25)";
    
    $res = $mysqli->query($sql);
    if($res && $res->num_rows > 0){

        while($row = $res->fetch_object()){

            // Primero valido si es que hay otros pasos que estan conectado a este paso
            $sql = "SELECT * FROM {$dyalogo_canales_electronicos}.secciones_conexiones_bot WHERE hasta = {$row->hasta} AND desde != {$desde}";
            $res2 = $mysqli->query($sql);
            
            // Si ninguna otra conexion esta usando la conexion procedemos a borrarlo
            if($res2 && $res2->num_rows == 0){

                // Tenemos que obtener el id del paso externo
                if($row->tipo_seccion == 25){
                    $sql = "SELECT id, id_estpas, ESTPAS_ConsInte__ESTRAT_b AS estrategia_id FROM {$dyalogo_canales_electronicos}.dy_bot 
                        INNER JOIN {$BaseDatos_systema}.ESTPAS ON ESTPAS_ConsInte__b = id_estpas 
                    WHERE id = {$row->id_paso_externo} LIMIT 1";
                }

                if($row->tipo_seccion == 24){
                    $sql = "SELECT CAMPAN_ConsInte__b AS id, CAMPAN_Nombre____b AS nombre, ESTPAS_ConsInte__b AS id_estpas, ESTPAS_ConsInte__ESTRAT_b AS estrategia_id FROM {$BaseDatos_systema}.CAMPAN 
                        INNER JOIN {$BaseDatos_systema}.ESTPAS ON ESTPAS_ConsInte__CAMPAN_b = CAMPAN_ConsInte__b 
                    WHERE CAMPAN_IdCamCbx__b = {$row->id_paso_externo}";
                }

                $res3 = $mysqli->query($sql);

                if($res3 && $res3->num_rows > 0){
                    $dataBot = $res3->fetch_object();
                    $pasoExternoId = $dataBot->id_estpas;
                }

                if(is_null($pasoExternoId)){
                    echo "No se ha podido borrar las flechas externas";
                    return;
                }

                // Ahora si la elimino
                $deleteSql = "DELETE FROM {$BaseDatos_systema}.ESTCON WHERE ESTCON_ConsInte__ESTPAS_Des_b = {$row->id_estpas} AND ESTCON_ConsInte__ESTPAS_Has_b = {$pasoExternoId}";
                $mysqli->query($deleteSql);
            }
        }
    }

    // Y finalmente elimino los pasos que se debe eliminar
    $sqlDelete = "DELETE FROM {$dyalogo_canales_electronicos}.secciones_conexiones_bot WHERE desde = {$desde} AND accion_flecha = 'DELETE'";
    $mysqli->query($sqlDelete);
}

function actualizarFlechas($seccionId_desde, $idHasta, $pasoId, $generadoPorSistema, $tipoPasoTo = 'bot'){

    global $mysqli;
    global $dyalogo_canales_electronicos;
    global $BaseDatos_systema;

    $desde = $seccionId_desde;
    $hasta = 0;

    // Como solo me trae la autorespuestaId necesito es el id de la seccion
    if($tipoPasoTo == 'bot'){
        $sqlSeccion = "SELECT id, id_seccion FROM {$dyalogo_canales_electronicos}.dy_base_autorespuestas WHERE id = {$idHasta} LIMIT 1";
    }else if($tipoPasoTo == 'campana_externa'){
        $sqlSeccion = "SELECT id as id_seccion, nombre FROM {$dyalogo_canales_electronicos}.secciones_bot WHERE id_estpas = {$pasoId} AND tipo_seccion = 24 AND id_paso_externo = {$idHasta} LIMIT 1";
    }else if($tipoPasoTo == 'bot_externo'){
        $sqlSeccion = "SELECT id as id_seccion, nombre FROM {$dyalogo_canales_electronicos}.secciones_bot WHERE id_estpas = {$pasoId} AND tipo_seccion = 25 AND id_paso_externo = {$idHasta} LIMIT 1";
    }
    $resSeccion = $mysqli->query($sqlSeccion);
    
    if($resSeccion && $resSeccion->num_rows > 0){
        $dataSeccion = $resSeccion->fetch_object();
        $hasta = $dataSeccion->id_seccion;
    }

    // valido si existe la flecha para ver si actualizo o inserto
    $sqlFlecha = "SELECT * FROM {$dyalogo_canales_electronicos}.secciones_conexiones_bot WHERE desde = {$desde} AND hasta = {$hasta}";
    $resFlecha = $mysqli->query($sqlFlecha);

    if($generadoPorSistema == true){
        $valorGeneradoPorSistema = 1;
    }else{
        $valorGeneradoPorSistema = 0;
    }

    // Si existe acutalizo si no inserto
    if($resFlecha && $resFlecha->num_rows > 0){

        $sql = "UPDATE {$dyalogo_canales_electronicos}.secciones_conexiones_bot SET accion_flecha = NULL WHERE desde = {$desde} AND hasta = {$hasta}";

    }else{

        $puertoDesde = generarPuerto($desde, $hasta);
        $puertoHasta = generarPuerto($hasta, $desde);

        $sql = "INSERT INTO {$dyalogo_canales_electronicos}.secciones_conexiones_bot (nombre, desde, hasta, from_port, to_port, id_estpas, generado_por_sistema) VALUES ('', {$desde}, {$hasta}, '{$puertoDesde}', '{$puertoHasta}', {$pasoId}, {$valorGeneradoPorSistema})";
    }

    if($mysqli->query($sql) !== true){
        echo "Error al generar las flechas";
    }

    // Validamos si existe en estpas la conexion entre Bot y el paso
    if($tipoPasoTo == 'campana_externa' || $tipoPasoTo == 'bot_externo'){

        $pasoExternoId = null;
        $estrategiaId = null;

        // Tenemos que obtener el id del paso externo
        if($tipoPasoTo == 'bot_externo'){
            $sql = "SELECT id, id_estpas, ESTPAS_ConsInte__ESTRAT_b AS estrategia_id FROM {$dyalogo_canales_electronicos}.dy_bot 
                INNER JOIN {$BaseDatos_systema}.ESTPAS ON ESTPAS_ConsInte__b = id_estpas 
            WHERE id = {$idHasta} LIMIT 1";
        }

        if($tipoPasoTo == 'campana_externa'){
            $sql = "SELECT CAMPAN_ConsInte__b AS id, CAMPAN_Nombre____b AS nombre, ESTPAS_ConsInte__b AS id_estpas, ESTPAS_ConsInte__ESTRAT_b AS estrategia_id FROM {$BaseDatos_systema}.CAMPAN 
                INNER JOIN {$BaseDatos_systema}.ESTPAS ON ESTPAS_ConsInte__CAMPAN_b = CAMPAN_ConsInte__b 
            WHERE CAMPAN_IdCamCbx__b = {$idHasta}";
        }

        $res = $mysqli->query($sql);

        if($res && $res->num_rows > 0){
            $dataBot = $res->fetch_object();
            $pasoExternoId = $dataBot->id_estpas;
            $estrategiaId = $dataBot->estrategia_id;
        }

        if(is_null($pasoExternoId)){
            echo "No se ha podido generar las flechas externas";
            return;
        }

        $sql = "SELECT * FROM {$BaseDatos_systema}.ESTCON WHERE ESTCON_ConsInte__ESTPAS_Des_b = {$pasoId} AND ESTCON_ConsInte__ESTPAS_Has_b = {$pasoExternoId}";
        $resConsultaEstcon = $mysqli->query($sql);

        if($resConsultaEstcon && $resConsultaEstcon->num_rows == 0){
            // Creo la flecha
            $generarFlecha = new GeneradorDeFlechas;

            $puertoFrom = $generarFlecha->generarPuerto($pasoId, $pasoExternoId, 'flujograma');
            $puertoTo = $generarFlecha->generarPuerto($pasoExternoId, $pasoId, 'flujograma');

            $insert = "INSERT INTO {$BaseDatos_systema}.ESTCON (ESTCON_Nombre____b, ESTCON_Comentari_b, ESTCON_ConsInte__ESTPAS_Des_b, ESTCON_ConsInte__ESTPAS_Has_b, ESTCON_FromPort_b, ESTCON_ToPort_b, ESTCON_ConsInte__ESTRAT_b, ESTCON_Activo_b, ESTCON_Deshabilitado_b) 
            VALUES ('Conector', 'BOT', {$pasoId}, {$pasoExternoId}, '{$puertoFrom}', '{$puertoTo}', {$estrategiaId}, 0, -1)";
            
            if($mysqli->query($insert) !== true){
                echo "Error al generar las flechas externas";
            }
        }
    }

}

function generarPuerto($pasoDesde, $pasoHasta){

    global $mysqli;
    global $dyalogo_canales_electronicos;

    // Trago las coordenadas del primer paso
    $sql1 = "SELECT * FROM {$dyalogo_canales_electronicos}.secciones_bot WHERE id = {$pasoDesde}";
    $res1 = $mysqli->query($sql1);
    $data1 = $res1->fetch_object();

    // Traigo la coordenadas del paso 2
    $sql2 = "SELECT * FROM {$dyalogo_canales_electronicos}.secciones_bot WHERE id = {$pasoHasta}";
    $res2 = $mysqli->query($sql2);
    $data2 = $res2->fetch_object();

    if(!is_null($data1->loc) && !is_null($data2->loc)){
        $coordenadas1 = explode(" ", $data1->loc);
        $x1 = $coordenadas1[0];
        $y1 = $coordenadas1[1];
    
        $coordenadas2 = explode(" ", $data2->loc);
        $x2 = $coordenadas2[0];
        $y2 = $coordenadas2[1];
    
        $puertoY = 'B';
        $puertoX = 'R';
    
        // Calculamos
        if($y1 < $y2){
            $puertoY = 'B';
        }else{
            $puertoY = 'T';
        }
    
        if($x1 > $x2){
            $puertoX = 'L';
        }else{
            $puertoX = 'R';
        }
    
        $rand = rand(1,2);
    
        if($rand == 1){
            return $puertoX;
        }else{
            return $puertoY;
        }
    }else{
        $rand = rand(1,4);

        $puerto = 'T';

        switch ($rand) {
            case '1':
                $puerto = 'T';
                break;
            case '2':
                $puerto = 'B';
                break;
            case '3':
                $puerto = 'L';
                break;
            case '4':
                $puerto = 'R';
                break;
            default:
                $puerto = 'B';
                break;
        }

        return $puerto;
    }

}

function actualizarAccionInicial($autorespuestaId, $seccionId){

    global $dyalogo_canales_electronicos;
    global $mysqli;

    // Luego guardo la accion inicial
    $tagLocal = $mysqli->real_escape_string($_POST['tag_local']);
    $rptaInicial = $_POST['dyTr_rpta_accion_inicial'];

    $accion = $_POST['accion_inicial_accion'];
    $seccionBot = 0;
    $seccionCampana = 0;
    if($accion == 1 ){
        $seccionCampana = (isset($_POST['accion_inicial_seccion'])) ? $_POST['accion_inicial_seccion'] : 0;
    }else {
        $seccionBot = (isset($_POST['accion_inicial_seccion'])) ? $_POST['accion_inicial_seccion'] : 0;
    }

    if($tagLocal == '' && $rptaInicial == '' && $accion == 0){
        // Si tanto tag como la respuesta esta vacia borrela
        $delete = "DELETE FROM {$dyalogo_canales_electronicos}.dy_base_autorespuestas_contenidos WHERE id_base_autorespuestas = {$autorespuestaId} AND orden = 1";
        $mysqli->query($delete);
    }else{

        // Necesito saber que tipo de seccion es
        $sqlSeccion = "SELECT * FROM {$dyalogo_canales_electronicos}.secciones_bot WHERE id = {$seccionId} LIMIT 1";
        $resSeccion = $mysqli->query($sqlSeccion);

        if($resSeccion && $resSeccion->num_rows > 0){

            $dataSeccion = $resSeccion->fetch_object();

            $tagsDefecto = '';

            if($dataSeccion->tipo_seccion == 3){
                $tagsDefecto = 'DY_SALUDO';
            }
            if($dataSeccion->tipo_seccion == 4){
                $tagsDefecto = 'DY_EXIT';
            }

            if($tagLocal == ''){
                $tagLocal = $tagsDefecto;
            }else{
                if($tagsDefecto == ''){
                    $tagLocal = $tagLocal;
                }else{
                    $tagLocal = $tagsDefecto . ', ' . $tagLocal;
                }
            }

            // Valido que exista una accion en orden 1
            $select = "SELECT * FROM {$dyalogo_canales_electronicos}.dy_base_autorespuestas_contenidos WHERE id_base_autorespuestas = {$autorespuestaId} AND orden = 1 LIMIT 1";
            $resExisteInicial = $mysqli->query($select);
    
            $tagsFormateado = obtenerTags($tagLocal);

            // Tengo que formatear la respuesta
            $rptaInicial = formatearStringDeVariables($rptaInicial);
            $rptaInicial = formatearUrlAUrlLinkeable($rptaInicial);
    
            if($resExisteInicial->num_rows > 0){
                //Existe
                $existeInicial = $resExisteInicial->fetch_object();
    
                $update = "UPDATE {$dyalogo_canales_electronicos}.dy_base_autorespuestas_contenidos SET accion = '{$accion}', id_campana = '{$seccionCampana}', id_base_transferencia = '{$seccionBot}', pregunta = '{$tagLocal}', tags = '{$tagsFormateado}', respuesta = '{$rptaInicial}' WHERE id = {$existeInicial->id}";
                $mysqli->query($update);

                $registroId = $existeInicial->id;
            }else{
                //No existe
                $insert = "INSERT INTO {$dyalogo_canales_electronicos}.dy_base_autorespuestas_contenidos (id_base_autorespuestas, pregunta, tags, respuesta, accion, id_campana, id_base_transferencia, orden) 
                    VALUES ({$autorespuestaId}, '{$tagLocal}', '{$tagsFormateado}', '{$rptaInicial}', '{$accion}', '{$seccionCampana}', '{$seccionBot}', 1)";
                $mysqli->query($insert);

                $registroId = $mysqli->insert_id;
            }

            gestionarAdjuntos('accion_inicial', $registroId);

            botonesRespuestasRapidas('a_inicial', $registroId);

            // Agregamos o actualizamos flecha si, la seccion es bienvenida
            if($dataSeccion->tipo_seccion == 3){
                // if($accion == 1){
                //     actualizarFlechas($seccionId, $seccionCampana, $_GET['id_paso'], true, 'campana_externa');
                // } 
                if($accion == 2){
                    actualizarFlechas($seccionId, $seccionBot, $_GET['id_paso'], true);
                }else{
                    asignarAccionEliminacion($seccionId);
                }
            }
        }

    }

    

    if($tagLocal == ''){
        // Hacemos un update para dejar el campo de tag en null
        $update = "UPDATE {$dyalogo_canales_electronicos}.dy_base_autorespuestas_contenidos SET pregunta = NULL, tags = NULL WHERE id_base_autorespuestas = {$autorespuestaId} AND orden = 1";
        $mysqli->query($update);
    }

    $activarRespuestaInicial = $rptaInicial == '' ? 0 : 1;
    $sqlActivar = "UPDATE {$dyalogo_canales_electronicos}.dy_base_autorespuestas SET mostrar_primera_pregunta_automatica = {$activarRespuestaInicial} WHERE id = {$autorespuestaId}";
    $mysqli->query($sqlActivar);
}

// Funciones Condiciones

function getNombreDeCondicionesSeccion($id){

    global $mysqli;
    global $dyalogo_canales_electronicos;

    $texto = "sin condiciones";

    $sql = "SELECT * FROM {$dyalogo_canales_electronicos}.condiciones_bot WHERE id_base_autorespuestas_contenido = {$id}";
    $res = $mysqli->query($sql);

    if($res->num_rows > 0){
        $i = 0;
        $texto = '';
        while ($row = $res->fetch_object()) {

            $nombreCampo = $row->campo;

            if($row->tipo_campo == 'pregun' && is_numeric($row->campo)){
                $nombreCampo = getNombrePregun($row->campo);
            }

            $nombreCampoAComparar = $row->valor_a_comparar;

            if($row->tipo_valor_comparar == 'pregun' && is_numeric($row->valor_a_comparar)){
                $nombreCampoAComparar = getNombrePregun($row->valor_a_comparar);
            }
            
            if($i == 0){
                $texto = "cuando $nombreCampo $row->condicion $nombreCampoAComparar";
            }else{
                $texto.= " $row->operador $nombreCampo $row->condicion $nombreCampoAComparar";
            }

            $i++;
        }
    }

    return $texto;
}

function getUltimoOrdenCondicion($id){

    global $mysqli;
    global $dyalogo_canales_electronicos;

    $sql = "SELECT max(orden) AS orden FROM {$dyalogo_canales_electronicos}.condiciones_bot WHERE id_base_autorespuestas_contenido = {$id}";
    $res = $mysqli->query($sql);

    $orden = 0;

    if($res->num_rows > 0){
        $respuesta = $res->fetch_object();
        $orden = $respuesta->orden;
    }

    return $orden;
}

// Funciones para las flechas

function getIdBaseAutorespuestaByIdSeccionAccionFinal($seccionId){

    global $mysqli;
    global $dyalogo_canales_electronicos;

    $id = 0;

    $sql = "SELECT id, nombre FROM {$dyalogo_canales_electronicos}.dy_base_autorespuestas WHERE id_seccion = {$seccionId} AND tipo_base = 4";
    $res = $mysqli->query($sql);

    if($res && $res->num_rows > 0){
        $data = $res->fetch_object();
        $id = $data->id;
    }

    return $id;
}

function getIdBaseAutorespuestaByIdSeccion($seccionId){

    global $mysqli;
    global $dyalogo_canales_electronicos;

    $id = 0;

    $sql = "SELECT id, nombre FROM {$dyalogo_canales_electronicos}.dy_base_autorespuestas WHERE id_seccion = {$seccionId}";
    $res = $mysqli->query($sql);

    if($res && $res->num_rows > 0){
        $data = $res->fetch_object();
        $id = $data->id;
    }

    return $id;
}

function getIdBaseAutorespuestaFinalByIdSeccion($seccionId){

    global $mysqli;
    global $dyalogo_canales_electronicos;

    $id = 0;

    $sql = "SELECT id, nombre FROM {$dyalogo_canales_electronicos}.dy_base_autorespuestas WHERE id_seccion = {$seccionId} AND tipo_base = 4";
    $res = $mysqli->query($sql);

    if($res && $res->num_rows > 0){
        $data = $res->fetch_object();
        $id = $data->id;
    }

    return $id;
}

function getSeccionIdByIdBaseAutorespuesta($id){

    global $mysqli;
    global $dyalogo_canales_electronicos;

    $seccionId = 0;

    $sql = "SELECT id, id_seccion FROM {$dyalogo_canales_electronicos}.dy_base_autorespuestas WHERE id = {$id}";
    $res = $mysqli->query($sql);

    if($res && $res->num_rows > 0){
        $data = $res->fetch_object();
        $seccionId = $data->id_seccion;
    }

    return $seccionId;
}

function getSeccionByIdPasoExterno($id){
    global $mysqli;
    global $dyalogo_canales_electronicos;

    $seccionId = 0;

    $sql = "SELECT id, nombre FROM {$dyalogo_canales_electronicos}.secciones_bot WHERE id_paso_externo = {$id}";
    $res = $mysqli->query($sql);

    if($res && $res->num_rows > 0){
        $data = $res->fetch_object();
        $seccionId = $data->id;
    }

    return $seccionId;
}

function getIdPasoExternoByIdSeccion($id){
    global $mysqli;
    global $dyalogo_canales_electronicos;

    $idExterno = 0;

    $sql = "SELECT id, id_paso_externo FROM {$dyalogo_canales_electronicos}.secciones_bot WHERE id = {$id}";
    $res = $mysqli->query($sql);

    if($res && $res->num_rows > 0){
        $data = $res->fetch_object();
        $idExterno = $data->id_paso_externo;
    }

    return $idExterno;
}

function ordenarAcciones($autoRes){
    
    global $mysqli;
    global $dyalogo_canales_electronicos;

    $sql = "SELECT id, orden, orden_finalizacion FROM {$dyalogo_canales_electronicos}.dy_base_autorespuestas_contenidos WHERE id_base_autorespuestas = {$autoRes} AND pregunta = 'DY_ACCION_CONDICON' ORDER BY orden ASC";
    $res = $mysqli->query($sql);

    $i = 1;
    if($res && $res->num_rows > 0){
        while ($row = $res->fetch_object()) {
            $sqlUpdate = "UPDATE {$dyalogo_canales_electronicos}.dy_base_autorespuestas_contenidos SET orden = {$i}, orden_finalizacion = {$i} WHERE id = {$row->id}";
            $mysqli->query($sqlUpdate);
            $i++;
        }
    }
    $sqlUpdate = "UPDATE {$dyalogo_canales_electronicos}.dy_base_autorespuestas_contenidos SET orden = {$i}, orden_finalizacion = {$i} WHERE id_base_autorespuestas = {$autoRes} AND pregunta = 'DY_ACCION_DEFECTO'";
    $mysqli->query($sqlUpdate);
}

// Crear un bot externo
function crearBotExterno($botId, $pasoId){

    global $mysqli;
    global $dyalogo_canales_electronicos;

    // Valido si ya esta creado
    $sqlCreado = "SELECT * FROM {$dyalogo_canales_electronicos}.secciones_bot WHERE id_estpas = {$pasoId} AND tipo_seccion = 25 AND id_paso_externo = {$botId}";
    $resCreado = $mysqli->query($sqlCreado);
    
    if($resCreado && $resCreado->num_rows <= 0){

        // Obtenemos el nombre del bot
        $nombre = 'bot '.$botId;

        $sqlBot = "SELECT a.ESTPAS_ConsInte__b AS id, a.ESTPAS_Comentari_b AS nombre FROM DYALOGOCRM_SISTEMA.ESTPAS a
        JOIN dyalogo_canales_electronicos.dy_base_autorespuestas b ON a.ESTPAS_ConsInte__b = b.id_estpas
        WHERE b.id = {$botId} AND ESTPAS_Tipo______b = 12";
        $resBot = $mysqli->query($sqlBot);
        if($resBot && $resBot->num_rows > 0){
            $dataBot = $resBot->fetch_object();
            $nombre = $dataBot->nombre;
        }

        // Traigo el id del bot
        $sql = "SELECT * FROM {$dyalogo_canales_electronicos}.dy_bot WHERE id_estpas = {$pasoId}";
        $resBot = $mysqli->query($sql);
        $dataBot = $resBot->fetch_object();

        $sqlInsert = "INSERT INTO {$dyalogo_canales_electronicos}.secciones_bot (nombre, id_estpas, tipo_seccion, orden, id_paso_externo, id_bot) VALUES ('{$nombre}', {$pasoId}, 25, 0, {$botId}, {$dataBot->id})";
        $mysqli->query($sqlInsert);
    }
}

// Crear una campana externa
function crearCampanaExterna($campanaId, $pasoId){

    global $mysqli;
    global $dyalogo_canales_electronicos;


    // Valido si ya esta creado
    $sqlCreado = "SELECT * FROM {$dyalogo_canales_electronicos}.secciones_bot WHERE id_estpas = {$pasoId} AND tipo_seccion = 24 AND id_paso_externo = {$campanaId}";
    $resCreado = $mysqli->query($sqlCreado);

    if($resCreado && $resCreado->num_rows <= 0){

        // Obtenemos el nombre de la campana
        $nombre = 'campana '.$campanaId;

        $sqlCampana = "SELECT CAMPAN_ConsInte__b AS id, CAMPAN_Nombre____b AS nombre FROM DYALOGOCRM_SISTEMA.CAMPAN WHERE CAMPAN_IdCamCbx__b = {$campanaId}";
        $resCampana = $mysqli->query($sqlCampana);
        if($resCampana && $resCampana->num_rows > 0){
            $dataCampana = $resCampana->fetch_object();
            $nombre = $dataCampana->nombre;
        }

        // Traigo el id del bot
        $sql = "SELECT * FROM {$dyalogo_canales_electronicos}.dy_bot WHERE id_estpas = {$pasoId}";
        $resBot = $mysqli->query($sql);
        $dataBot = $resBot->fetch_object();

        $sqlInsert = "INSERT INTO {$dyalogo_canales_electronicos}.secciones_bot (nombre, id_estpas, tipo_seccion, orden, id_paso_externo, id_bot) VALUES ('{$nombre}', {$pasoId}, 24, 0, {$campanaId}, {$dataBot->id})";
        $mysqli->query($sqlInsert);
    }
}

// TODO:,FIXME: MODIFICADO Esta tabla se encarga de generar la tabla de gestiones para el bot
function generarTablaGestiones($botId, $pasoId){

    global $mysqli;
    global $dyalogo_canales_electronicos;
    global $BaseDatos;
    global $BaseDatos_systema;

    // Traigo la informacion principal de bot
    if(!is_null($botId)){
        $sql = "SELECT * FROM {$dyalogo_canales_electronicos}.dy_bot WHERE id = {$botId} LIMIT 1";
    }else{
        $sql = "SELECT * FROM {$dyalogo_canales_electronicos}.dy_bot WHERE id_estpas = {$pasoId} LIMIT 1";
    }

    $res = $mysqli->query($sql);

    if($res && $res->num_rows > 0){

        $bot = $res->fetch_object();

        // Me toca obtener el huesped
        $sqlHuesped = "SELECT ESTRAT_ConsInte__b AS id, ESTRAT_ConsInte__PROYEC_b AS huespedId from {$BaseDatos_systema}.ESTRAT
            INNER JOIN {$BaseDatos_systema}.ESTPAS ON ESTPAS_ConsInte__ESTRAT_b = ESTRAT_ConsInte__b
        WHERE ESTPAS_ConsInte__b = {$bot->id_estpas}";

        $resHuesped = $mysqli->query($sqlHuesped);
        if($resHuesped && $resHuesped->num_rows > 0){
            $hu = $resHuesped->fetch_object();
            $huespedId = $hu->huespedId;
        }else{
            $huespedId = null;
        }

        $guion = new GenerarGuion;

        // Declaro una variable que almacenara el id de gestion del guion
        $guionGestionId = $bot->id_guion_gestion;

        // Validamos si el bot ya tiene un id de formulario de lo contrario debo generarlo como nuevo

        if(is_null($bot->id_guion_gestion)){

            $respBd = $guion->crearBd('Gestiones bot ' . $bot->nombre, 'Creado desde el bot ' . $bot->nombre, 1, 'bot', $huespedId);
            
            // Si falla la creacion de la bd detenemos el proceso
            if($respBd['estado'] === false){
                return;
            }

            $guionGestionId = $respBd['idBd'];

            // Creamos la seccion por defecto
            $arrSecciones = [
                ['nombre' => 'Principal bot', 'tipo' => 1]
            ];
            $respSeccion = $guion->crearSeccion($respBd['idBd'], $arrSecciones);

            // Si falla la creacion de la seccion por defecto detenemos el proceso
            if(!isset($respSeccion['exito']['Principal bot'])){
                return;
            }

            // Ya teniendo la bd y la seccion creamos un campo que almacenara el campo
            $campos = $guion->crearPregun($respBd['idBd'], [
                ['nombre' => 'Dato principal', 'seccion' => $respSeccion['exito']['Principal bot'], 'tipo' => 1],
                ['nombre' => 'Tipificacion_Bot', 'seccion' => $respSeccion['exito']['Principal bot'], 'tipo' => 2],
                ['nombre' => 'UltimaSeccion', 'seccion' => $respSeccion['exito']['Principal bot'], 'tipo' => 1],
                ['nombre' => 'UltimoMensaje', 'seccion' => $respSeccion['exito']['Principal bot'], 'tipo' => 2]
            ]);

            // Actualizamos los campos principales y secundarios del formulario
            $guion->acutualizarCampoPrincipalSecundario($respBd['idBd'], $campos['exito'][0], $campos['exito'][0]);

            // Actualizamos el bot con los datos del formulario
            // $sql = "UPDATE {$dyalogo_canales_electronicos}.dy_bot SET id_guion_gestion = {$respBd['idBd']}, id_pregun_campo_principal_gestion = {$campos['exito'][0]} WHERE id = {$bot->id}";
            $sql = "UPDATE {$dyalogo_canales_electronicos}.dy_bot SET id_guion_gestion = {$respBd['idBd']} WHERE id = {$bot->id}";
            $resU = $mysqli->query($sql);
            
        }

        $guion->generarTabla($guionGestionId, 1);

        // // Ahora si empiezo la generacion
        // if(is_null($dataBot->id_guion_gestion)){

        //     // Como no hay un id de guion debo asignar uno
        //     $sql = "UPDATE {$dyalogo_canales_electronicos}.dy_bot SET id_guion_gestion = {$dataBot->id} WHERE id = {$dataBot->id}";
        //     $mysqli->query($sql);

        //     // Tengo que crear desde cero la tabla
        //     crearTablaGestion($dataBot->id);

        //     return;
        // }

        // // Valido que si este creada la tabla
        // $sql = "SELECT * FROM {$BaseDatos}.B{$dataBot->id_guion_gestion} LIMIT 1";
        // $resBaseDatos = $mysqli->query($sql);
        // // Al no existir debe crear la tabla
        // if(!$resBaseDatos){
            
        //     crearTablaGestion($dataBot->id_guion_gestion);

        //     return;
        // }
        
        // // Si ya esta creada la trato de actualizar
        // $arrCamposBot = [];
        // $arrCamposTabla = [];

        // // Debo listar todas las varibales que se guardan en la base del bot
        // $sqlListaSecciones = "SELECT a.accion, a.id_pregun, a.nombre_variable FROM {$dyalogo_canales_electronicos}.dy_base_autorespuestas_contenidos a
        //     INNER JOIN {$dyalogo_canales_electronicos}.dy_base_autorespuestas b ON a.id_base_autorespuestas = b.id
        //     WHERE b.id_estpas = {$pasoId} AND a.id_pregun IS NOT NULL";

        // $resListaSecciones = $mysqli->query($sqlListaSecciones);

        // if($resListaSecciones && $resListaSecciones->num_rows > 0){
        //     while($row = $resListaSecciones->fetch_object()){
        //         $arrCamposBot[] = $row;
        //     }
        // }
        
        // // Listo todos los campos de la bd que esten creados
        // $sqlList = "SHOW COLUMNS FROM {$BaseDatos}.B{$dataBot->id_guion_gestion}";
        // $res = $mysqli->query($sqlList);

        // if($res && $res->num_rows > 0){
        //     while($row = $res->fetch_object()){
        //         $arrCamposTabla[] = $row->Field;
        //     }
        // }

        // if(count($arrCamposBot) > 0){

        //     // Valido que este ya creado
        //     foreach ($arrCamposBot as $key => $value) {

        //         $campoBuscar = "B{$dataBot->id_guion_gestion}_C{$value->id_pregun}";
                
        //         if(!in_array($campoBuscar, $arrCamposTabla)){
                    
        //             gestionarCampo($value->id_pregun, $dataBot->id_guion_gestion, 'crear');
                    
        //         }else{
        //             gestionarCampo($value->id_pregun, $dataBot->id_guion_gestion, 'editar');
        //         }

        //     }
        // }

    }

}

function verificarCampoPregun($nombreCampo, $tablaGestionId, $idAutoresContenido = null):int {

    global $mysqli;
    global $BaseDatos_systema;
    global $dyalogo_canales_electronicos;

    $pregunId = NULL;

    if(is_null($idAutoresContenido)){

        $pregunId = crearCampoPregun($nombreCampo, $tablaGestionId);

    }else{
        // Validamos si ya esta creada con anterioridad en autorespuestas_contenido
        $sql = "SELECT * from {$dyalogo_canales_electronicos}.dy_base_autorespuestas_contenidos WHERE id = {$idAutoresContenido}";
        $res = $mysqli->query($sql);
        $autorespContenido = $res->fetch_object();

        // Si no es nulo significa que ya existe una variable asociada entonces me toca extraerla y actualizar nombre pregun
        if(!is_null($autorespContenido->id_pregun_gestion)){
            $pregunId = $autorespContenido->id_pregun_gestion;
            
            $update = "UPDATE {$BaseDatos_systema}.PREGUN SET PREGUN_Texto_____b = '{$nombreCampo}' WHERE PREGUN_ConsInte__b = {$pregunId}";
            $mysqli->query($update);
        }else{
            $pregunId = crearCampoPregun($nombreCampo, $tablaGestionId);
        }
    }

    return $pregunId;
}

function crearCampoPregun($nombreCampo, $tablaGestionId){

    global $mysqli;
    global $BaseDatos_systema;

    // Creamos desde cero el campo pregun pero tendriamos que analizar primero si existe el nombre
    $sqlExiste = "SELECT * FROM {$BaseDatos_systema}.PREGUN WHERE PREGUN_Texto_____b = '{$nombreCampo}' AND PREGUN_ConsInte__GUION__b = {$tablaGestionId} LIMIT 1";
    $res = $mysqli->query($sqlExiste);
    
    // Si existe agarramos ese id y lo insertamos de nuestro lado
    if($res && $res->num_rows > 0){

        $pregun = $res->fetch_object();
        $pregunId = $pregun->PREGUN_ConsInte__b;
        
    }else{
        // De lo contrario lo creamos como nuevo
        $guion = new GenerarGuion;

        // Traemos una seccion llamada DATOS, de lo contrario la creamos desde cero
        $sql = "SELECT * FROM {$BaseDatos_systema}.SECCIO WHERE SECCIO_ConsInte__GUION__b = {$tablaGestionId} AND SECCIO_Nombre____b = 'DATOS' LIMIT 1";
        $resSeccion = $mysqli->query($sql);

        if($resSeccion && $resSeccion->num_rows > 0){
            $seccion = $resSeccion->fetch_object();
            $seccionId = $seccion->SECCIO_ConsInte__b;
        }else{
            // Creamos la seccion
            $respSeccion = $guion->crearSeccion($tablaGestionId, [['nombre' => 'DATOS', 'tipo' => 1]]);
            // Si falla la creacion de la seccion por defecto detenemos el proceso
            if(!isset($respSeccion['exito']['DATOS'])){
                echo "Se genero un error al crear la seccion DATOS";
            }

            $seccionId = $respSeccion['exito']['DATOS'];
        }

        $campos = $guion->crearPregun($tablaGestionId, [
            ['nombre' => $nombreCampo, 'seccion' => $seccionId, 'tipo' => 1],
        ]);

        $pregunId = $campos['exito'][0];

        $sql = "INSERT INTO {$BaseDatos_systema}.CAMPO_ (CAMPO__Nombre____b, CAMPO__Tipo______b, CAMPO__ConsInte__PREGUN_b) VALUES ('G{$tablaGestionId}_C{$pregunId}', 1, {$pregunId})";
        $mysqli->query($sql);
    }

    return $pregunId;
}

function crearTablaGestion($id){

    global $mysqli;
    global $BaseDatos;
    
    $crearTabla = "CREATE TABLE {$BaseDatos}.B{$id} (
        B{$id}_ConsInte__b INT(11) AUTO_INCREMENT PRIMARY KEY,
        B{$id}_FechaInsercion datetime,
        B{$id}_CodigoMiembro bigint(20),
        B{$id}_PoblacionOrigen bigint(20),
        
        B{$id}_Origen_b varchar(255),
        B{$id}_IdChatEntrante bigint(20),
        B{$id}_Sentido___b varchar(255),
        B{$id}_Canal_____b varchar(255),
        B{$id}_LinkContenido varchar(500),
        
        B{$id}_DatoContacto varchar(255),
        B{$id}_Paso bigint(20),
        B{$id}_Duracion___b int(11),
        
        B{$id}_Tipificacion varchar(500),
        B{$id}_Clasificacion smallint(5),
        B{$id}_Observacion varchar(500),
        
        B{$id}_UltimaSeccion int(11),
        B{$id}_UltimoMensaje varchar(500)
        )
        ENGINE=InnoDB DEFAULT CHARSET=utf8;";
    
    if($mysqli->query($crearTabla) === true){
        //echo "Si creo la tabla";
    }else{
        echo "Se genero un error al crear la tabla de gestiones => ".$mysqli->error;
    }

}

function gestionarCampo($pregunId, $tablaId, $accion){

    global $mysqli;
    global $BaseDatos;
    global $BaseDatos_systema;

    // Se busca la configuracion de ese campo
    $sql = "SELECT PREGUN_ConsInte__b AS id, PREGUN_Tipo______b AS tipo FROM {$BaseDatos_systema}.PREGUN WHERE PREGUN_ConsInte__b = {$pregunId}";
    $resPregun = $mysqli->query($sql);

    // Se valida si no existe el campo
    if($resPregun && $resPregun->num_rows > 0){   
        
        $pregun = $resPregun->fetch_object();

        if($accion == 'crear'){
            $alterTable = "ALTER TABLE {$BaseDatos}.B{$tablaId} ADD B{$tablaId}_C{$pregun->id}";
        }

        if($accion == 'editar'){
            $alterTable = "ALTER TABLE {$BaseDatos}.B{$tablaId} CHANGE B{$tablaId}_C{$pregun->id} B{$tablaId}_C{$pregun->id}";
        }
    
        // Miramos cual es el tipo de dato
    
        if($pregun->tipo == '5'){
            /* es de tipo Fecha u Hora y toca ponerle dateTime */
            $alterTable .= " datetime DEFAULT NULL";
        }
    
        if($pregun->tipo == '10'){
            /* es de tipo Fecha u Hora y toca ponerle dateTime */
            $alterTable .= " datetime DEFAULT NULL";
        }
    
        if($pregun->tipo == '3'){
            /* la pregunta es Entero, Lista o Guion que guardan los Ids */
            $alterTable .= " bigint(20) DEFAULT NULL";
        }
    
        if($pregun->tipo == '6'){
            /* la pregunta es Entero, Lista o Guion que guardan los Ids */
            $alterTable .= " bigint(20) DEFAULT NULL";
        } 
    
        if($pregun->tipo == '13'){
            /* la pregunta es Entero, Lista o Guion que guardan los Ids */
            $alterTable .= " bigint(20) DEFAULT NULL";
        } 
    
        if($pregun->tipo == '14'){
            /* la pregunta es Entero, Lista o Guion que guardan los Ids */
            $alterTable .= " varchar(253) CHARSET utf8 COLLATE utf8_general_ci DEFAULT NULL";
        } 
    
        if($pregun->tipo == '11'){
            /* la pregunta es Entero, Lista o Guion que guardan los Ids */
            $alterTable .= " bigint(20) DEFAULT NULL";
        }
    
        if($pregun->tipo == '4'){
            /* la pregunta es Decimal */
            $alterTable .= "  double DEFAULT NULL";
        } 
    
        if($pregun->tipo == '1' || $pregun->tipo == '15'){
            /* es de tipo Varchar */
            $alterTable .= " varchar(253) CHARSET utf8 COLLATE utf8_general_ci DEFAULT NULL";
        }
    
        if($pregun->tipo == '2'){
            /* es de tipo Memo */
            $alterTable .= " longtext CHARSET utf8 COLLATE utf8_general_ci DEFAULT NULL";
        }
    
        if($pregun->tipo == '8'){
            /* es de tipo CheckBox */
            $alterTable .= " smallint(5) DEFAULT NULL";
        }
        
        if($mysqli->query($alterTable) === true){
            // 
        }else{
            echo "Error Agregando Columna => ".$mysqli->error;
        }
    }

}

function gestionarAdjuntos($i, $registroId){

    global $mysqli;
    global $dyalogo_canales_electronicos;
    global $URL_SERVER;
    global $URL_ADJUNTOS;

    if(isset($_POST['tipo_file_'.$i]) && ($_POST['tipo_file_'.$i] == 'IMAGE' || $_POST['tipo_file_'.$i] == 'VIDEO' || $_POST['tipo_file_'.$i] == 'AUDIO' || $_POST['tipo_file_'.$i] == 'DOCUMENT') ){

        // Si pasa validamos si nos esta llegando un adjunto
        if(isset($_FILES['archivoMedia'.$i]) && $_FILES['archivoMedia'.$i]['error'] === UPLOAD_ERR_OK){

            $file = $_FILES['archivoMedia'.$i];

            if(isset($file['tmp_name']) && !empty($file['tmp_name'])){
                
                $fileTmpPath = $file['tmp_name'];
                $fileName = $file['name'];
                $fileNameCmps = explode(".", $fileName);
                $fileExtension = strtolower(end($fileNameCmps));

                $newFileName = md5(time() . $fileName) . '.' . $fileExtension;

                switch ($_POST['tipo_file_'.$i]) {
                    case 'IMAGE':
                        $allowedfileExtensions = array('jpg', 'jpeg', 'png');
                        $tamanoPermitido = 5000000;
                        break;
                    case 'VIDEO':
                        $allowedfileExtensions = array('mp4', '3gpp');
                        $tamanoPermitido = 16000000;
                        break;
                    case 'AUDIO':
                        $allowedfileExtensions = array('aac', 'mp4', 'amr', 'mpeg', 'ogg');
                        $tamanoPermitido = 16000000;
                        break;
                    case 'DOCUMENT':
                        $allowedfileExtensions = array('doc', 'docx', 'odt', 'pdf', 'xlsx', 'xls', 'txt', 'csv');
                        $tamanoPermitido = 20000000;
                        break;
                    default:
                        $allowedfileExtensions = array();
                        $tamanoPermitido = 0;
                        break;
                }

                if (in_array($fileExtension, $allowedfileExtensions)) {
                    
                    $uploadFileDir = $URL_ADJUNTOS.$_GET['huesped'].'/bot';

                    if(!is_dir($uploadFileDir)){
                        mkdir($uploadFileDir, 0755, true);
                    }

                    $dest_path = $uploadFileDir .'/' .$newFileName;
                    
                    if(move_uploaded_file($fileTmpPath, $dest_path)){
                        $message ='File is successfully uploaded.';

                        // Limpiamos la media anterior si es que tiene
                        limpiarArchivoMediaActual($registroId, false);

                        // inplementamos gsutil para subir el archivo al repo de google
                        exec("gsutil cp $dest_path gs://dy-archivos-pulicos-clientes/");
                        // $output = shell_exec("node /etc/dyalogo/apps/carge_gs/index.js");
                        // echo "<pre>otra4$output</pre>";
                        unlink($dest_path);

                        // echo '<br> ' . "gsutil cp $dest_path gs://dy-archivos-pulicos-clientes/";

                        $rutaPublica = 'https://storage.googleapis.com/dy-archivos-pulicos-clientes/'.$newFileName;

                        // Actualizamos el registro
                        $sql = "UPDATE {$dyalogo_canales_electronicos}.dy_base_autorespuestas_contenidos SET tipo_media = '{$_POST['tipo_file_'.$i]}', media = '{$rutaPublica}', nombre_media = '{$newFileName}' WHERE id = {$registroId}";
                        $mysqli->query($sql);
                    }
                    
                }
            }
        }

    }else{
        // Si no es un adjunto es un texto entonces miramos si anteriormente existe un adjunto para limpiarlo
        limpiarArchivoMediaActual($registroId, true);
    }
}

function limpiarArchivoMediaActual($registroId, $tipoTexto = true){

    global $mysqli;
    global $dyalogo_canales_electronicos;
    global $URL_ADJUNTOS;

    $sql = "SELECT id, media, nombre_media FROM {$dyalogo_canales_electronicos}.dy_base_autorespuestas_contenidos WHERE id = {$registroId}";
    $resSelect = $mysqli->query($sql);

    if($resSelect && $resSelect->num_rows > 0){
        $dataTemp = $resSelect->fetch_object();

        // Validamos si hay algo para limpiarlo del servidor
        if(!is_null($dataTemp->media) && $dataTemp->media != '' && !is_null($dataTemp->nombre_media) && $dataTemp->nombre_media != ''){
            $fileDir = $URL_ADJUNTOS.$_GET['huesped'].'/bot/'.$dataTemp->nombre_media;

            if (file_exists($fileDir)) {
                unlink($fileDir);
            }

            // Limpiamos en la bd
            if($tipoTexto){
                $sql = "UPDATE {$dyalogo_canales_electronicos}.dy_base_autorespuestas_contenidos SET tipo_media = 'TEXT', media = NULL, nombre_media = NULL WHERE id = {$registroId}";
                $mysqli->query($sql);
            }
        }
    }
}

function botonesRespuestasRapidas($id, $registroId){

    global $mysqli;
    global $dyalogo_canales_electronicos;

    if(isset($_POST['boton_titulo'.$id]) && isset($_POST['boton_respuesta'.$id])){
        $arrTitulo = $_POST['boton_titulo'.$id];
        $arrRespuesta = $_POST['boton_respuesta'.$id];

        $botones = [];

        for ($i=0; $i < count($arrTitulo); $i++) { 
            
            // Validamos que tanto titulo como respuesta no esten en blanco
            if($arrTitulo[$i] == '' && $arrRespuesta[$i] == ''){
                continue;
            }

            // Agregamos los valores
            $titulo = $arrTitulo[$i];
            $respuesta = $arrRespuesta[$i];

            // Si el titulo esta vacio lo lleno con la respuesta
            if($titulo == ''){
                $titulo = $respuesta;
            }

            // si la respuesta esta vacia le pongo el titulo
            if($respuesta == ''){
                $respuesta = $titulo;
            }

            // Los recorto por si vienen con texto de mas
            $titulo = substr($titulo, 0, 20);
            $respuesta = substr($respuesta, 0, 255);

            array_push($botones, ['titulo' => $titulo, 'postback' => $respuesta]);
        }

        if(count($botones) == 0){
            $sql = "UPDATE {$dyalogo_canales_electronicos}.dy_base_autorespuestas_contenidos SET body_mensaje_interactivo = NULL WHERE id = {$registroId}";
        }else{
            $json = json_encode($botones);
            // Esto es una pendejada pero no se que mas hacer 
            $json = str_replace('\\u', '\\\\u', $json);
            $sql = "UPDATE {$dyalogo_canales_electronicos}.dy_base_autorespuestas_contenidos SET body_mensaje_interactivo = '{$json}' WHERE id = {$registroId}";
        }
        
        $mysqli->query($sql);
    }else{
        // Valido si esta vacio para limpiarlo
        $select = "SELECT id, body_mensaje_interactivo FROM {$dyalogo_canales_electronicos}.dy_base_autorespuestas_contenidos WHERE id = {$registroId}";

        $res = $mysqli->query($select);

        if($res && $res->num_rows > 0){

            $data = $res->fetch_object();

            // Si no es null limpio el campo para que no envie nada
            if(!is_null($data->body_mensaje_interactivo)){
                $sql = "UPDATE {$dyalogo_canales_electronicos}.dy_base_autorespuestas_contenidos SET body_mensaje_interactivo = NULL WHERE id = {$registroId}";
                $mysqli->query($sql);
            }

        }

    }
    
}

function actualizarPasosTimeout($arrPasos, $botPasoId){

    global $mysqli;
    global $dyalogo_canales_electronicos;

    // Traemos la informacion del bot para saber si tiene con anterioridad pasos timeout
    $sql = "SELECT id, id_seccion_timeout FROM {$dyalogo_canales_electronicos}.dy_bot WHERE id_estpas = {$botPasoId}";
    $resBot = $mysqli->query($sql);

    $bot = $resBot->fetch_object();

    // Si no tiene datos solamente borramos todo y limpiamos
    if(is_null($arrPasos)){

        // Validamos si se ha configurado el timeout con anterioridad
        if(!is_null($bot->id_seccion_timeout)){

            // Elimino todas la configuracion de la seccion a la cual pertenece el bot
            $sql = "DELETE FROM {$dyalogo_canales_electronicos}.dy_base_autorespuestas_contenidos WHERE id_base_autorespuestas = {$bot->id_seccion_timeout}";
            $mysqli->query($sql);

            // Desvinculo el id de la seccion de despedida del bot
            $sql = "UPDATE {$dyalogo_canales_electronicos}.dy_bot SET id_seccion_timeout = NULL WHERE id = {$bot->id}";
            $mysqli->query($sql);
        }
        return;
    }

    // Si tiene datos ejecuta un proceso de actualizar o crear la seccion timeout
    if(is_array($arrPasos)){

        if(is_null($bot->id_seccion_timeout)){

            // Miramos si ya hay una seccion creada que almacenara los valores de timeout
            $sql = "SELECT id FROM {$dyalogo_canales_electronicos}.dy_base_autorespuestas WHERE id_estpas = {$botPasoId} AND nombre = 'DY_TIMEOUT' AND tipo_base = 4 LIMIT 1";
            $resExisteTimeout = $mysqli->query($sql);
    
            // Si existe solamente seleccionamos el id
            if($resExisteTimeout && $resExisteTimeout->num_rows > 0){
    
                $secTimeout = $resExisteTimeout->fetch_object();
                $seccionTimeoutId = $secTimeout->id;
    
            }else{
    
                // Traigo una autorrespuesta como base
                $sql = "SELECT id, id_huesped, timeout_cliente, frase_timeout, frase_no_encuentra_respuesta, id_bot FROM {$dyalogo_canales_electronicos}.dy_base_autorespuestas WHERE id_estpas = {$botPasoId} LIMIT 1";
                $res = $mysqli->query($sql);
                $autoRes = $res->fetch_object();
    
                // Al no existir creamos la seccion y seleccionamos el id
                $sql = "INSERT INTO {$dyalogo_canales_electronicos}.dy_base_autorespuestas (nombre, id_huesped, id_estpas, tipo_base, buscar_ani_dato, timeout_cliente, frase_timeout, id_seccion, frase_no_encuentra_respuesta, mostrar_primera_pregunta_automatica, id_bot) VALUES ('DY_TIMEOUT', {$autoRes->id_huesped}, {$botPasoId}, 4, 1, {$autoRes->timeout_cliente}, '{$autoRes->frase_timeout}', 0, '{$autoRes->frase_no_encuentra_respuesta}', 0, {$autoRes->id_bot})";
                $resInsert = $mysqli->query($sql);
    
                $seccionTimeoutId = $mysqli->insert_id;
            }

            // Agrego el id de la seccion del timeout
            $sql = "UPDATE {$dyalogo_canales_electronicos}.dy_bot SET id_seccion_timeout = {$seccionTimeoutId} WHERE id = {$bot->id}";
            $mysqli->query($sql);

        }else{
            $seccionTimeoutId = $bot->id_seccion_timeout;
        }

        // Ahora realizo el proceso de agregado de los pasos

        // Busco si existen pasos ya creados
        $sql = "SELECT id, id_estpas FROM {$dyalogo_canales_electronicos}.dy_base_autorespuestas_contenidos WHERE id_base_autorespuestas = {$seccionTimeoutId}";
        $resAutoResPasos = $mysqli->query($sql);

        $arrPasosTimeout = [];

        if($resAutoResPasos && $resAutoResPasos->num_rows > 0){
            while($row = $resAutoResPasos->fetch_object()){
                $arrPasosTimeout[] = $row;
            }
        }

        $ordenPasos = 1;

        // recorremos los pasos creados y reordenamos
        if(count($arrPasosTimeout) > 0){

            foreach ($arrPasosTimeout as $value) {
                // Si esta dentro del array sigunifica que ya existe y tendria que sacarlo de arrPasos a insertar
                if (($clave = array_search($value->id_estpas, $arrPasos)) !== false) {
                    
                    // Quito el valor del array
                    unset($arrPasos[$clave]);
                    
                    // Actualizo el orden de autorespuesta
                    $sql = "UPDATE {$dyalogo_canales_electronicos}.dy_base_autorespuestas_contenidos SET orden = {$ordenPasos}, orden_finalizacion = {$ordenPasos} WHERE id = {$value->id}";
                    $mysqli->query($sql);
                    $ordenPasos++;
                }else{
                    // Si el paso no se encuentra en la lista de pasos nuevos que se insertaran lo borro
                    $sql = "DELETE FROM {$dyalogo_canales_electronicos}.dy_base_autorespuestas_contenidos WHERE id = {$value->id}";
                    $mysqli->query($sql);
                }
            }
        }

        // Ahora con los pasos que quedan me toca insertarlo
        if(count($arrPasos) > 0){

            foreach ($arrPasos as $key => $valor) {
                $sql = "INSERT INTO {$dyalogo_canales_electronicos}.dy_base_autorespuestas_contenidos (id_base_autorespuestas, pregunta, tags, accion, orden, orden_finalizacion, id_estpas) VALUES ({$seccionTimeoutId}, 'DY_ACCION_CONDICON', 'cancelar', 7, {$ordenPasos}, {$ordenPasos}, {$valor})";
                $mysqli->query($sql);
                $ordenPasos++;
            }

        }
    }
}

function actualizarAccionNoRespuesta($pasoId, $data){

    global $mysqli;
    global $dyalogo_canales_electronicos;

    // Miramos si ya hay una configuracion 
    $sql = "SELECT id, id_base_ar_sin_respuesta FROM {$dyalogo_canales_electronicos}.dy_bot where id_estpas = {$pasoId}";
    $res = $mysqli->query($sql);
    $bot = $res->fetch_object();

    // Si la accion es solo dar respuesta actualizamos todo a null
    if($data['accion'] == 0){

        // Validamos que no se haya configurado nada con anterioridad
        if(!is_null($bot->id_base_ar_sin_respuesta)){

            // Eliminamos la configuracion de la accion
            $sql = "DELETE FROM {$dyalogo_canales_electronicos}.dy_base_autorespuestas_contenidos WHERE id_base_autorespuestas = {$bot->id_base_ar_sin_respuesta}";
            $mysqli->query($sql);

            // Desvinculo el id de la seccion 
            $sql = "UPDATE {$dyalogo_canales_electronicos}.dy_bot SET id_base_ar_sin_respuesta = NULL WHERE id = {$bot->id}";
            $mysqli->query($sql);
        }
        return;
    }

    // De lo contrario empezamos el proceso de actualizacion o insercion

    // Si es null verificamos si ya existe una seccion creada con anterioridad
    if(is_null($bot->id_base_ar_sin_respuesta)){

        $sql = "SELECT id FROM {$dyalogo_canales_electronicos}.dy_base_autorespuestas WHERE id_estpas = {$pasoId} AND nombre = 'DY_NO_RESPUESTA' AND tipo_base = 4 LIMIT 1";
        $resExiste = $mysqli->query($sql);

        // Si existe solamente seleccionamos el id
        if($resExiste && $resExiste->num_rows > 0){

            $seccion = $resExiste->fetch_object();
            $seccionId = $seccion->id;

        }else{

            // Al no existir creo una seccion

            // Traigo una autorrespuesta como base
            $sql = "SELECT id, id_huesped, timeout_cliente, frase_timeout, frase_no_encuentra_respuesta, id_bot FROM {$dyalogo_canales_electronicos}.dy_base_autorespuestas WHERE id_estpas = {$pasoId} LIMIT 1";
            $res = $mysqli->query($sql);
            $autoRes = $res->fetch_object();

            // Al no existir creamos la seccion y seleccionamos el id
            $sql = "INSERT INTO {$dyalogo_canales_electronicos}.dy_base_autorespuestas (nombre, id_huesped, id_estpas, tipo_base, buscar_ani_dato, timeout_cliente, frase_timeout, id_seccion, frase_no_encuentra_respuesta, mostrar_primera_pregunta_automatica, id_bot) VALUES ('DY_NO_RESPUESTA', {$autoRes->id_huesped}, {$pasoId}, 4, 1, {$autoRes->timeout_cliente}, '{$autoRes->frase_timeout}', 0, '{$autoRes->frase_no_encuentra_respuesta}', 0, {$autoRes->id_bot})";
            $resInsert = $mysqli->query($sql);

            $seccionId = $mysqli->insert_id;

        }

        // Actualizamos el id de la seccion en el bot
        $sql = "UPDATE {$dyalogo_canales_electronicos}.dy_bot SET id_base_ar_sin_respuesta = {$seccionId} WHERE id = {$bot->id}";
        $mysqli->query($sql);

    }else{
        // Si existe solamente seleccionamos el id
        $seccionId = $bot->id_base_ar_sin_respuesta;
    }

    // Ahora solo asigno el paso o la seccion

    // Busco si existen pasos ya creados
    $sql = "SELECT id FROM {$dyalogo_canales_electronicos}.dy_base_autorespuestas_contenidos WHERE id_base_autorespuestas = {$seccionId} AND pregunta = 'DY_ACCION_DEFECTO' LIMIT 1";
    $resAutoResPasos = $mysqli->query($sql);

    $nuevaSeccionId = 'NULL';

    // Reseteo la accion
    if($data['accion'] == '2_1' || $data['accion'] == '2_2'){
        $nuevaSeccionId = ($data['accion'] == '2_1')? $data['seccionId'] : $data['botSeccionId'];
        $data['accion'] = '2';

        $data['campanaId'] = 'NULL';
        $data[''] = 'NULL';
    }

    if($data['accion'] == '1'){
        $data['botId'] = 'NULL';
    }

    if($resAutoResPasos && $resAutoResPasos->num_rows > 0){
        // Actualizo
        $autoResContenido = $resAutoResPasos->fetch_object();

        $sql = "UPDATE {$dyalogo_canales_electronicos}.dy_base_autorespuestas_contenidos SET accion = {$data['accion']}, id_campana = {$data['campanaId']}, id_base_transferencia = {$nuevaSeccionId}, id_bot_transferido = {$data['botId']} WHERE id = {$autoResContenido->id}";
    }else{
        // Creamos como nuevo
        $sql = "INSERT INTO {$dyalogo_canales_electronicos}.dy_base_autorespuestas_contenidos (id_base_autorespuestas, id_campana, id_base_transferencia, id_bot_transferido, accion, pregunta, tags, orden, llave, global, orden_finalizacion) VALUES ({$seccionId}, {$data['campanaId']}, {$nuevaSeccionId}, {$data['botId']}, {$data['accion']}, 'DY_ACCION_DEFECTO', 'cancelar', 1, 0, 0, 1)";
    }

    $mysqli->query($sql);

}
