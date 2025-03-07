<?php
    ini_set('display_errors', 'On');
    ini_set('display_errors', 1);
    include(__DIR__."/conexion.php");
    include(__DIR__."/funciones.php");
    require_once(__DIR__.'/../helpers/parameters.php');
    date_default_timezone_set('America/Bogota');
    
    if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {

        if(isset($_POST['obtenerTareasBackoffice'])){

            $est = $mysqli->real_escape_string($_POST['est']);
            $pasos = [];

            $query = "SELECT ESTPAS_ConsInte__b AS id, ESTPAS_Comentari_b AS nombre FROM {$BaseDatos_systema}.ESTPAS WHERE md5(concat('".clave_get."', ESTPAS_ConsInte__ESTRAT_b)) = '{$est}' AND ESTPAS_Tipo______b = 9";
            $res = $mysqli->query($query);

            if($res && $res->num_rows > 0){
                while($row = $res->fetch_object()){
                    $pasos[] = $row;
                }
            }

            echo json_encode([
                "pasos" => $pasos
            ]);

        }

        if(isset($_POST['validarRegistrosBackoffice'])){

            $pasoBackofficeId = $mysqli->real_escape_string($_POST['paso']);
            $filtrosPersonalizados = $_POST['filtrosPersonalizados'] ?? null;
            $tipoBusqueda = $_POST['tipoBusqueda'];

            $valido = true;

            $cantidadRegistrosValidos = 0;
            $registrosValidos = [];

            $pasoBackoffice = obtenerPaso($pasoBackofficeId);
            $muestraBackoffice = "G" . $pasoBackoffice->baseId . "_M" . $pasoBackoffice->estpasMuestraId;

            $filtro = '';

            // Primero buscamos cuantas flechas estan conectadas

            $query = "SELECT * FROM {$BaseDatos_systema}.ESTCON WHERE ESTCON_ConsInte__ESTPAS_Has_b = {$pasoBackofficeId} AND ESTCON_Consulta_sql_b IS NOT NULL AND ESTCON_Consulta_sql_b != '' AND ESTCON_Activo_b = -1";
            $resFlechas = $mysqli->query($query);

            if($resFlechas && $resFlechas->num_rows > 0){

                while($flecha = $resFlechas->fetch_object()){

                    // Validamos si el paso donde llega la flecha es campaña o backoffice
                    $pasoDesde = obtenerPaso($flecha->ESTCON_ConsInte__ESTPAS_Des_b);
                    $condicion = '';
                    
                    $consulta = explode('WHERE', $flecha->ESTCON_Consulta_sql_b);
                    $cond = '';
                    
                    // Armamos la consulta cuando sale de campaña o backoffice
                    // if($pasoDesde->tipo == 1 || $pasoDesde->tipo == 6 || $pasoDesde->tipo == 9){
                    //     if(isset($consulta[1])){
                    //         $cond = " WHERE {$consulta[1]} ";
                    //     }

                    //     // se arma la condicion
                    //     if($pasoDesde->tipo == 1 || $pasoDesde->tipo == 6){
                    //         $muestraDesde = 'G' . $pasoDesde->baseId . '_M'. $pasoDesde->campanMuestraId;
                    //     }else{
                    //         $muestraDesde = 'G' . $pasoDesde->baseId . '_M'. $pasoDesde->estpasMuestraId;
                    //     }

                    //     $condicion = "G{$pasoBackoffice->baseId}_ConsInte__b IN (SELECT {$muestraDesde}_CoInMiPo__b FROM DYALOGOCRM_WEB.{$muestraDesde} {$cond})";

                    // }

                    // Se arma la consulta cuando viene de otro paso que no sea campaña o backoffice
                    if($pasoDesde->tipo == 4 || $pasoDesde->tipo == 5 || $pasoDesde->tipo == 10 || $pasoDesde->tipo == 11 || $pasoDesde->tipo == 9){
                        // Si existe alguna condicion lo asignamos, de lo contrario no hay que asignar nada
                        if(isset($consulta[1])){
                            $condicion = '(' . $consulta[1] .')';
                        }
                    }

                    if($filtro != ''){
                        if($condicion != ''){
                            $filtro .= ' OR ' . $condicion;
                        }
                    }else{
                        $filtro = $condicion;
                    }

                }

                if($filtro != ''){
                    $filtro = ' AND (' . $filtro . ')';
                }

            }
            // else{
            //     $valido = false;
            // }

            if(!is_null($filtrosPersonalizados)){

                if(isset($_POST['campo'])){

                    $queryCondicion = "";

                    $campoFiltro = $_POST['campo'];
                    $operador = $_POST['dyTr_condicion'];
                    $valor = $_POST['valor'];
                    $tipoCampo = $_POST['tipoCampo'];
                    $logic = $_POST['operador'];
                    
                    for ($i=0; $i < count($campoFiltro); $i++) { 
                           
                        if(is_numeric($campoFiltro[$i])){
                            
                            // Valido si existe este campo en la bd
                            $queryComprobar = "SHOW COLUMNS FROM {$BaseDatos}.G{$pasoBackoffice->baseId} WHERE Field = 'G{$pasoBackoffice->baseId}_C{$campoFiltro[$i]}'";
                            $resultComprobar = $mysqli->query($queryComprobar);

                            if ($resultComprobar && $resultComprobar->num_rows > 0) {
                                $queryCondicion .= generarCondicion("G{$pasoBackoffice->baseId}_C{$campoFiltro[$i]}", $operador[$i], $tipoCampo[$i], $valor[$i], $logic[$i]); 
                            }

                        }else{
                            if ($campoFiltro[$i] != "_FechaInsercion") {
                                $queryCondicion .= generarCondicion("{$muestraBackoffice}{$campoFiltro[$i]}", $operador[$i], $tipoCampo[$i], $valor[$i], $logic[$i]);
                            }else{
                                $queryCondicion .= generarCondicion("G{$pasoBackoffice->baseId}_FechaInsercion", $operador[$i], $tipoCampo[$i], $valor[$i], $logic[$i]);
                            }
                        }
                    }

                    $queryCondicion .= " )";

                    $queryCondicion = "AND TRUE" . $queryCondicion;
                    $queryCondicion = str_replace("AND TRUE AND", " AND (", $queryCondicion);
                    $queryCondicion = str_replace("AND TRUE OR", " OR (", $queryCondicion);

                    $filtro = $filtro . $queryCondicion;

                }

            }

            // Traigo los campos principales y secundarios
            $campos = obtenerCampoPrincipalSecundario($pasoBackoffice->baseId);

            // Ahora si creamos la consulta que nos va a traer todos los registros
            // Primero validamos tipo de busqueda vamos a realizar
            switch ($tipoBusqueda) {
                case 'noAsignados':
                    $consulta_registros = "SELECT {$campos} FROM DYALOGOCRM_WEB.G{$pasoBackoffice->baseId} 
                        LEFT JOIN DYALOGOCRM_WEB.{$muestraBackoffice} ON G{$pasoBackoffice->baseId}_ConsInte__b = {$muestraBackoffice}_CoInMiPo__b
                        WHERE {$muestraBackoffice}_ConIntUsu_b IS NULL {$filtro} ORDER BY {$muestraBackoffice}_CoInMiPo__b DESC LIMIT 250";
                    
                    break;
                case 'asignados':
                    $consulta_registros = "SELECT {$campos}, USUARI_Nombre____b AS agenteAsignado, {$muestraBackoffice}_CoInMiPo__b AS muestraId FROM DYALOGOCRM_WEB.G{$pasoBackoffice->baseId} 
                        LEFT JOIN DYALOGOCRM_WEB.{$muestraBackoffice} ON G{$pasoBackoffice->baseId}_ConsInte__b = {$muestraBackoffice}_CoInMiPo__b
                        LEFT JOIN DYALOGOCRM_SISTEMA.USUARI ON {$muestraBackoffice}_ConIntUsu_b = USUARI_ConsInte__b
                        WHERE {$muestraBackoffice}_ConIntUsu_b IS NOT NULL {$filtro} ORDER BY {$muestraBackoffice}_CoInMiPo__b DESC LIMIT 250";
                    
                    break;
                case 'todos':
                    $consulta_registros = "SELECT {$campos}, USUARI_Nombre____b AS agenteAsignado, {$muestraBackoffice}_CoInMiPo__b AS muestraId FROM DYALOGOCRM_WEB.G{$pasoBackoffice->baseId} 
                        LEFT JOIN DYALOGOCRM_WEB.{$muestraBackoffice} ON G{$pasoBackoffice->baseId}_ConsInte__b = {$muestraBackoffice}_CoInMiPo__b
                        LEFT JOIN DYALOGOCRM_SISTEMA.USUARI ON {$muestraBackoffice}_ConIntUsu_b = USUARI_ConsInte__b
                        WHERE TRUE {$filtro} ORDER BY {$muestraBackoffice}_CoInMiPo__b DESC LIMIT 250";

                    break;
                default:
                    # code...
                    break;
            }

            // Ejecutamos la consulta para la cantidad de registros
            // $resConsulta_registros_cantidad = $mysqli->query($consulta_registros_cantidad);

            // if($resConsulta_registros_cantidad && $resConsulta_registros_cantidad->num_rows > 0){
            //     $dataConsulta_registros_cantidad = $resConsulta_registros_cantidad->fetch_object();
            //     $cantidadRegistrosValidos = $dataConsulta_registros_cantidad->cantidad;
            // }

            // y traemos los registros validos
            $resConsultaRegistros = $mysqli->query($consulta_registros);
            
            if($resConsultaRegistros && $resConsultaRegistros->num_rows > 0){

                $cantidadRegistrosValidos = $resConsultaRegistros->num_rows;

                while($row = $resConsultaRegistros->fetch_object()){
                    $registrosValidos[] = $row;
                }

            }

            // Tengo que traer el paso de backoffice primero
            $sqlTareaBackoffice = "SELECT TAREAS_BACKOFFICE_ConsInte__b AS id, TAREAS_BACKOFFICE_Nombre_b AS nombre FROM {$BaseDatos_systema}.TAREAS_BACKOFFICE WHERE TAREAS_BACKOFFICE_ConsInte__ESTPAS_b = " . $pasoBackofficeId;
            $resBackoffice = $mysqli->query($sqlTareaBackoffice);

            $tareaBackoffice = $resBackoffice->fetch_object();

            // Traigo el dia actual
            $hoy = date('Y-m-d');

            // Busco los agentes que hay
            $sqlAgentes = "SELECT USUARI_ConsInte__b as id, 
                    USUARI_Nombre____b as camp1 , 
                    USUARI_Correo___b as camp2, 
                    USUARI_UsuaCBX___b as camp3, 
                    (SELECT COUNT(1) FROM DYALOGOCRM_WEB.{$muestraBackoffice} WHERE {$muestraBackoffice}_ConIntUsu_b = USUARI_ConsInte__b) AS cant_total,
                    (SELECT COUNT(1) FROM DYALOGOCRM_WEB.{$muestraBackoffice} WHERE {$muestraBackoffice}_ConIntUsu_b = USUARI_ConsInte__b AND {$muestraBackoffice}_FechaAsignacion_b >= '{$hoy}') AS cant_hoy
                FROM ".$BaseDatos_systema.".USUARI 
                INNER JOIN ".$BaseDatos_systema.".ASITAR_BACKOFFICE ON USUARI_ConsInte__b = ASITAR_BACKOFFICE_ConsInte__USUARI_b
                WHERE ASITAR_BACKOFFICE_ConsInte__TAREAS_BACKOFFICE_b = ".$tareaBackoffice->id." AND USUARI_ConsInte__PERUSU_b IS NULL ORDER BY USUARI_Nombre____b ASC ";

            $resAgentes = $mysqli->query($sqlAgentes);

            $agentes = [];
        
            if($resAgentes && $resAgentes->num_rows > 0){
                while($row = $resAgentes->fetch_object()){
                    $agentes[] = $row;
                }
            }


            $camposBd = array();
            $contieneCampana = false;

            // Traigo los campos de la bd
            $Lsql = "SELECT * FROM ".$BaseDatos_systema.".PREGUN WHERE PREGUN_ConsInte__GUION__b = ".$pasoBackoffice->baseId." AND PREGUN_Tipo______b NOT IN (12,9) AND (PREGUN_Texto_____b != 'OPTIN_DY_WF' AND PREGUN_Texto_____b != 'ESTADO_DY');";
            $res_Resultado = $mysqli->query($Lsql);
            
            while ($key = $res_Resultado->fetch_object()) {
                $camposBd[] = $key;
            }

            // Valido si hay campañas entrantes o salientes conectadas al backoffice
            $query = "SELECT * FROM {$BaseDatos_systema}.ESTCON JOIN {$BaseDatos_systema}.ESTPAS ON ESTCON_ConsInte__ESTPAS_Des_b = ESTPAS_ConsInte__b WHERE ESTCON_ConsInte__ESTPAS_Has_b = {$pasoBackofficeId} AND ESTCON_Consulta_sql_b IS NOT NULL AND ESTCON_Consulta_sql_b != '' AND (ESTPAS_Tipo______b = 1 OR ESTPAS_Tipo______b = 6)";
            $res = $mysqli->query($query);

            if($res && $res->num_rows > 0){
                $contieneCampana = true;
            }

            echo json_encode([
                "valido" => $valido,
                "pasoBackoffice" => $pasoBackoffice,
                "cantidadRegistrosValidos" => $cantidadRegistrosValidos,
                "registrosValidos" => $registrosValidos,
                "agentes" => $agentes,
                "camposBd" => $camposBd,
                "contieneCampana" => $contieneCampana
            ]);
        }

        if(isset($_GET['asignarRegistros'])){

            $pasoBackofficeId = $_POST['pasoBackoffice'];
            $arrCamposInsertar = $_POST['camposInsertar'];
            $agente = $_POST['agente'];

            // Traigo inicialmente la informacion del paso
            $pasoBackoffice = obtenerPaso($pasoBackofficeId);

            $base = 'G' . $pasoBackoffice->baseId;
            $muestra = 'G' . $pasoBackoffice->baseId . '_M' . $pasoBackoffice->estpasMuestraId;

            $valido = true;
            $error = '';

            // Recorro los registros a insertar
            for ($i=0; $i < count($arrCamposInsertar); $i++) { 

                // Validamos si el registro ya esta en la muestra
                $campoInsertar = explode('_M', $arrCamposInsertar[$i]);

                // Fecha hora actual
                $fechaActual = date('Y-m-d H:i:s');

                // Si es 0 significa que es nuevo
                if($campoInsertar[1] == '0'){

                    // Valido si existe o no en la muestra
                    $consulta = "SELECT {$muestra}_CoInMiPo__b FROM {$BaseDatos}.{$muestra} WHERE {$muestra}_CoInMiPo__b = {$campoInsertar[0]} LIMIT 1";
                    $resConsulta = $mysqli->query($consulta);

                    // Si ya existe solo la actualizo
                    if($resConsulta->num_rows > 0){
                        $query =  "UPDATE {$BaseDatos}.{$muestra} SET {$muestra}_ConIntUsu_b = {$agente}, {$muestra}_FechaAsignacion_b = '{$fechaActual}' WHERE {$muestra}_CoInMiPo__b = {$campoInsertar[0]}";
                    }else{
                        // Insertar en muestra
                        $query = "INSERT INTO {$BaseDatos}.{$muestra} ({$muestra}_CoInMiPo__b, {$muestra}_Activo____b, {$muestra}_Estado____b, {$muestra}_TipoReintentoGMI_b, {$muestra}_NumeInte__b, {$muestra}_CantidadIntentosGMI_b, {$muestra}_ConIntUsu_b, {$muestra}_FechaAsignacion_b) SELECT {$base}_ConsInte__b, -1 AS Activo____b, 0 AS Estado____b, 0 AS TipoReintentoGMI_b, 0 AS NumeInte__b, 0 AS CantidadIntentosGMI_b, {$agente} AS agente, '{$fechaActual}' AS fechaActual FROM {$BaseDatos}.{$base} WHERE {$base}_ConsInte__b = {$campoInsertar[0]}";
                    }

                }else{
                    // Actualizamos la muestra
                    $query =  "UPDATE {$BaseDatos}.{$muestra} SET {$muestra}_ConIntUsu_b = {$agente}, {$muestra}_FechaAsignacion_b = '{$fechaActual}' WHERE {$muestra}_CoInMiPo__b = {$campoInsertar[0]}";
                }
                
                if($mysqli->query($query) === false){
                    $valido = false;
                    $error = "Error insertando el registro " . $mysqli->error;
                }

            }

            echo json_encode([
                "valido" => $valido,
                "error" => $error
            ]);
        }

        if(isset($_GET['getUsuariosBackoffice'])){

            $pasoId = $_POST['backoffice'];
            $datos = array();

            // necesito traer el id del paso de backoffice
            $sql = "SELECT TAREAS_BACKOFFICE_ConsInte__b AS id FROM {$BaseDatos_systema}.TAREAS_BACKOFFICE WHERE TAREAS_BACKOFFICE_ConsInte__ESTPAS_b = {$pasoId}";
            $resBackoffice = $mysqli->query($sql);
            $backoffice = $resBackoffice->fetch_object();
    
            // Obtengo una lista de usuarios pertenecientes al huesped que ya han sido asignados a asitar_backoffice 
            $Asql = "SELECT USUARI_ConsInte__b as id, USUARI_Nombre____b as nombre
            FROM ".$BaseDatos_systema.".USUARI 
            JOIN ".$BaseDatos_systema.".ASITAR_BACKOFFICE ON USUARI_ConsInte__b = ASITAR_BACKOFFICE_ConsInte__USUARI_b
            WHERE ASITAR_BACKOFFICE_ConsInte__TAREAS_BACKOFFICE_b = ".$backoffice->id." AND USUARI_ConsInte__PERUSU_b IS NULL ORDER BY USUARI_Nombre____b ASC ";
    
            $res = $mysqli->query($Asql);
            
            while ($row = $res->fetch_object()) {
                $datos[] = $row;
            }
            
            echo json_encode(['usuarios' => $datos]);
    
        }

    }

    function obtenerPaso($id){

        global $mysqli;
        global $BaseDatos_systema;

        $data = [];

        $sql = "SELECT ESTPAS_ConsInte__b AS id, ESTPAS_Comentari_b AS nombre, ESTPAS_Tipo______b AS tipo, ESTPAS_ConsInte__CAMPAN_b AS campanId, ESTPAS_ConsInte__MUESTR_b AS estpasMuestraId, CAMPAN_ConsInte__MUESTR_b AS campanMuestraId, ESTRAT_ConsInte_GUION_Pob AS baseId FROM {$BaseDatos_systema}.ESTPAS 
            INNER JOIN {$BaseDatos_systema}.ESTRAT ON ESTPAS_ConsInte__ESTRAT_b = ESTRAT_ConsInte__b  
            LEFT JOIN {$BaseDatos_systema}.CAMPAN ON ESTPAS_ConsInte__CAMPAN_b = CAMPAN_ConsInte__b
        WHERE ESTPAS_ConsInte__b = {$id}";

        $res = $mysqli->query($sql);

        if($res && $res->num_rows > 0){
            $data = $res->fetch_object();
        }

        return $data;
    }

    function obtenerCampoPrincipalSecundario($id){
        global $mysqli;
        global $BaseDatos_systema;

        $data = [];

        $sql = "SELECT GUION__ConsInte__PREGUN_Pri_b AS principal, GUION__ConsInte__PREGUN_Sec_b AS secundario FROM DYALOGOCRM_SISTEMA.GUION_ WHERE GUION__ConsInte__b = {$id}";

        $res = $mysqli->query($sql);

        if($res && $res->num_rows > 0){
            $data = $res->fetch_object();
            return 'G'. $id . '_ConsInte__b AS id, G' . $id .'_C'. $data->principal . ' AS principal, G' . $id .'_C' . $data->secundario . ' AS secundario ';
        }

        return 'G'. $id . '_ConsInte__b AS id, G'. $id . '_ConsInte__b AS principal, G' . $id . '_ConsInte__b AS secundario ';
    }

    function generarCondicion($campo, $operador, $tipo, $valor, $andOr){

        $condicion = "";
        $operadorLogico = "AND";

        if($andOr == "0"){
            $operadorLogico = "OR";
        }

        // Validamos si es una fecha o una hora o es otro dato
        if ($tipo == 5) { // Fecha
            $condicion = " {$operadorLogico} DATE_FORMAT({$campo},'%Y-%m-%d') ";
        }elseif($tipo == 10){ // Hora
            if (strlen($valor) == 5) {
                $valor .= ":00";
            }
            $condicion = " {$operadorLogico} DATE_FORMAT({$campo},'%H:%i:%s') ";
        }else{
            $condicion = " {$operadorLogico} {$campo} ";
        }

        // Ahora validamos si es un campo creado dimanicamente o estandar
        if (is_numeric($tipo)) {
            if ($tipo < 3 || $tipo == 5 || $tipo == 10 || $tipo == 14) {
                switch ($operador) {
                    case '=':
                        $condicion .= "= '{$valor}' ";
                        break;
                    case '!=':
                        $condicion .= "!= '{$valor}' ";
                        break;
                    case 'LIKE_1':
                        $condicion .= "LIKE '{$valor}%' ";
                        break;
                    case 'LIKE_2':
                        $condicion .= "LIKE '%{$valor}%' ";
                        break;
                    case 'LIKE_3':
                        $condicion .= "LIKE '%{$valor}' ";
                        break;
                    case '>':
                        $condicion .= "> '{$valor}' ";
                        break;
                    case 'menorQue':
                        $condicion .= "< '{$valor}' ";
                        break;
                    default:
                        break;
                }                    
            }else{
                switch ($operador) {
                    case '=':
                        $condicion .= "= {$valor} ";
                        break;
                    case '!=':
                        $condicion .= "!= {$valor} ";
                        break;
                    case '>':
                        $condicion .= "> {$valor} ";
                        break;
                    case 'menorQue':
                        $condicion .= "< {$valor} ";
                        break;
                    default:
                        break;
                }   
            }
        }else{
            if ($tipo == "_FecUltGes_b" || $tipo == "_FeGeMaIm__b") {

                switch ($operador) {
                    case '=':
                        $condicion .= "= '{$valor}' ";
                        break;
                    case '!=':
                        $condicion .= "!= '{$valor}' ";
                        break;
                    case '>':
                        $condicion .= "> '{$valor}' ";
                        break;
                    case 'menorQue':
                        $condicion .= "< '{$valor}' ";
                        break;
                    default:
                        break;
                }
            }else if($tipo == '_ConIntUsu_b' && $valor == "-1"){
                if($operador == '='){
                    $condicion .= "IS NULL ";
                }else if($operador == '!='){
                    $condicion .= "IS NOT NULL ";
                }
            }else{
                switch ($operador) {
                    case '=':
                        $condicion .= "= {$valor} ";
                        break;
                    case '!=':
                        $condicion .= "!= {$valor} ";
                        break;
                    case '>':
                        $condicion .= "> {$valor} ";
                        break;
                    case 'menorQue':
                        $condicion .= "< {$valor} ";
                        break;
                    default:
                        break;
                }
            }
        }

        return $condicion;
    }

?>