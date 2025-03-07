
<?php

    ini_set('display_errors', 'On');
    ini_set('display_errors', 1);
    include_once(__DIR__."../../../../pages/conexion.php");
    include_once __DIR__ . '/../../../global/CalcularEjecucionProximaTarea.php';

    if(isset($_POST['accion']) && $_POST['accion'] == 'Listar'){

        $idPaso=isset($_POST['paso']) ? $_POST['paso'] : 0;
        $estado = false;
        $mensaje="No Hay Mensaje";
        $lista=array();

        //Se consulta la tabla de TARHOR
        if($idPaso > 0){
            $sqlTarHor = "SELECT TARHOR_ConsInte__b,TARHOR_Nombre____b FROM {$BaseDatos_systema}.TARHOR WHERE TARHOR_ConsInte_ESTPAS____b = {$idPaso}";
            
            $respTarHor = $mysqli->query($sqlTarHor);

            if($respTarHor){
                if($respTarHor ->num_rows > 0){
                    $estado=true;
                    $mensaje="Se Obtuvo Una Lista De Tareas";
                    while ($item = $respTarHor->fetch_object()){
                        array_push($lista,array(
                            "TARHOR_ConsInte__b"=>$item->TARHOR_ConsInte__b, "TARHOR_Nombre____b"=>$item->TARHOR_Nombre____b,));
                    }
                }else{
                    $mensaje="No Se Encontraron Tareas Programadas";
                }
            }
        }
        $respuesta =json_encode(array("estado"=>$estado,"mensaje"=>$mensaje,"lista"=>$lista));
        echo $respuesta;

    }

    if(isset($_POST['accion']) && $_POST['accion'] == 'AgregarProgramacion'){

        $idPaso=isset($_POST['paso']) ? $_POST['paso'] : 0;
        $estado = false;
        $mensaje="No Hay Mensaje";
        $idEstrat = 0;
        $ultimoResgistroInsertado = 0;

        //Se inserta un nuevo registro en TARHOR
        if($idPaso > 0){

            //Se obtiene el id de la estrategia
            $sqlEstpas = "SELECT ESTPAS_ConsInte__ESTRAT_b FROM {$BaseDatos_systema}.ESTPAS WHERE ESTPAS_ConsInte__b = {$idPaso}";
            $respEstpas = $mysqli->query($sqlEstpas);

            if($respEstpas){
                if($respEstpas ->num_rows > 0){
                    $respEstpas = $respEstpas->fetch_object();
                    $idEstrat = $respEstpas->ESTPAS_ConsInte__ESTRAT_b;
                    
                    //se realiza la insersion en TARHOR 
                    $sqlTarHor = "INSERT INTO {$BaseDatos_systema}.TARHOR (TARHOR_FechaCreacion_____b, TARHOR_ConsInte_ESTRAT____b, TARHOR_ConsInte_ESTPAS____b, TARHOR_Nombre____b) VALUES(now(), {$idEstrat},{$idPaso}, 'defaul')";
                    //print_r($sqlTarHor);

                    if ($mysqli->query($sqlTarHor) === true) {
                        //Ahora enseguida a la muestra
                        $ultimoResgistroInsertado = $mysqli->insert_id;
                        $estado=true;
                        $mensaje="Se Inserto La Tarea Correctamente";
                    }
                    
                }else{
                    $mensaje="No tiene estrategia";
                }
            }else{
                $mensaje="Error -->  respEstpas";
                echo $mensaje;
            }
            
        }
        $respuesta =json_encode(array("estado"=>$estado,"mensaje"=>$mensaje,"idNuevaTarea"=>$ultimoResgistroInsertado));
        echo $respuesta;

    }

    if(isset($_POST['accion']) && $_POST['accion'] == 'GuardarProgramacion'){
        
        $idPaso=isset($_POST['paso']) ? $_POST['paso'] : 0;
        $arr = $_POST['arrayDatosNuevaTarea'];
        $arregloTareaProgramada = $arr[0];
        $estado = false;
        $mensaje="No Hay Mensaje";

        if(isset($arregloTareaProgramada['radioFecha']) && $arregloTareaProgramada['radioFecha'] != 0){
            /*$timestamp = strtotime(str_replace('/', '-', $arregloTareaProgramada['radioFecha']));
            $partes_fecha = explode('/', $timestamp);
            $dia = $partes_fecha[0];
            $mes = $partes_fecha[1];
            $anio = $partes_fecha[2];
            $timestamp = mktime(0, 0, 0, $mes, $dia, $anio);
            $fecha_convertida = date('Y-m-d', $timestamp);*/
            $fecha_convertida = ",TARHOR_Fecha_Finalizacion____b = '".$arregloTareaProgramada['radioFecha']." 00:00:00"."'";
            
        }else{
            $fecha_convertida = "";
        }

        
        if($idPaso > 0){
            $FechaEjecucion1= $arregloTareaProgramada['FechaEjecucion'];
            $FechaEjecucionStr= strval($FechaEjecucion1);
            $FechaEjecucion= '"'.$FechaEjecucionStr.'"';
            $sqlUpdateTarea = "UPDATE DYALOGOCRM_SISTEMA.TARHOR SET TARHOR_Nombre____b='{$arregloTareaProgramada['nombreTarea']}', TARHOR_Activo____b={$arregloTareaProgramada['activo']}, TARHOR_Cantidad____b={$arregloTareaProgramada['tarRepetir']}, TARHOR_Unidad____b={$arregloTareaProgramada['tarCadaCuanto']}, TARHOR_Hora_Ejecucion____b=concat(curdate(),' ','{$arregloTareaProgramada['tarHor']}'), TARHOR_Lunes____b={$arregloTareaProgramada['lunesActivo']}, TARHOR_Martes____b={$arregloTareaProgramada['martesActivo']}, TARHOR_Miercoles____b={$arregloTareaProgramada['miercolesActivo']}, TARHOR_Jueves____b={$arregloTareaProgramada['juevesActivo']}, TARHOR_Viernes____b={$arregloTareaProgramada['viernesActivo']}, TARHOR_Sabado____b={$arregloTareaProgramada['sabadoActivo']}, TARHOR_Domingo____b={$arregloTareaProgramada['domingoActivo']}, TARHOR_Finaliza____b={$arregloTareaProgramada['tarFinaliza']} {$fecha_convertida}, TARHOR_FecProxEje_b=".$FechaEjecucion." WHERE TARHOR_ConsInte__b = {$_POST['idNuevoTarHor']}";
            //print_r($sqlUpdateTarea);
            //exit;
            if ($mysqli->query($sqlUpdateTarea) === true) {
                $estado=true;
                $mensaje="Se Actualizó La Tarea Correctamente";
            } else {
                echo "ERROR " . $mysqli->error;
            }
        }

        $calcularTarea = new CalcularEjecucionProximaTarea;
        $calcularTarea->__invoke($_POST['idNuevoTarHor']);

        $respuesta =json_encode(array("estado"=>$estado,"mensaje"=>$mensaje));
        echo $respuesta;

    }


    if(isset($_POST['accion']) && $_POST['accion'] == 'DatosTareaPrograma'){

        $idTarea=isset($_POST['idTarea']) ? $_POST['idTarea'] : 0;
        $estado = false;
        $mensaje="No Hay Mensaje";
        $lista=array();

        if($idTarea > 0){
            $sqlTarHor = "SELECT TARHOR_ConsInte__b, TARHOR_FechaCreacion_____b, TARHOR_ConsInte_ESTRAT____b, TARHOR_ConsInte_ESTPAS____b, TARHOR_Nombre____b, TARHOR_Activo____b, TARHOR_Cantidad____b, TARHOR_Unidad____b, DATE_FORMAT(TARHOR_Hora_Ejecucion____b,'%H:%s:%i') as TARHOR_Hora_Ejecucion____b, TARHOR_Lunes____b, TARHOR_Martes____b, TARHOR_Miercoles____b, TARHOR_Jueves____b, TARHOR_Viernes____b, TARHOR_Sabado____b, TARHOR_Domingo____b, TARHOR_Finaliza____b, TARHOR_Fecha_Finalizacion____b, TARHOR_FecProxEje_b, TARHOR_FecUltEje_b FROM {$BaseDatos_systema}.TARHOR WHERE TARHOR_ConsInte__b = {$idTarea}";
            $respTarHor = $mysqli->query($sqlTarHor);
            if($respTarHor){
                if($respTarHor ->num_rows > 0){
                    $estado=true;
                    $mensaje="Se Obtuvo Una Lista De Tareas";
                    while ($item = $respTarHor->fetch_object()){
                        array_push($lista,array(
                            "TARHOR_ConsInte__b"=>$item->TARHOR_ConsInte__b, "TARHOR_Nombre____b"=>$item->TARHOR_Nombre____b, "TARHOR_Activo____b" =>$item->TARHOR_Activo____b, "TARHOR_Cantidad____b" =>$item->TARHOR_Cantidad____b, "TARHOR_Unidad____b" =>$item->TARHOR_Unidad____b, "TARHOR_Hora_Ejecucion____b" =>$item->TARHOR_Hora_Ejecucion____b, "TARHOR_Lunes____b" =>$item->TARHOR_Lunes____b, "TARHOR_Martes____b" =>$item->TARHOR_Martes____b, "TARHOR_Miercoles____b" =>$item->TARHOR_Miercoles____b, "TARHOR_Jueves____b" =>$item->TARHOR_Jueves____b, "TARHOR_Viernes____b" =>$item->TARHOR_Viernes____b, "TARHOR_Sabado____b" =>$item->TARHOR_Sabado____b, "TARHOR_Domingo____b" =>$item->TARHOR_Domingo____b, "TARHOR_Finaliza____b" =>$item->TARHOR_Finaliza____b, "TARHOR_Fecha_Finalizacion____b" =>$item->TARHOR_Fecha_Finalizacion____b, "TARHOR_FecProxEje_b" =>$item-> TARHOR_FecProxEje_b, "TARHOR_FecUltEje_b" =>$item-> TARHOR_FecUltEje_b));
                    }
                }else{
                    $mensaje="No Existen Tareas Programadas";
                }
            }
        }
        $respuesta =json_encode(array("estado"=>$estado,"mensaje"=>$mensaje,"lista"=>$lista));
        echo $respuesta;

    }

    if(isset($_POST['accion']) && $_POST['accion'] == 'CrearAccionTareaPrograma'){
        $idTarea=isset($_POST['idTarea']) ? $_POST['idTarea'] : 0;
        $estado = false;
        $mensaje="No Hay Mensaje";
        $ultimoResgistroInsertado = 0;

        //Se inserta un nuevo registro en TARPRO
        if($idTarea > 0){
            $sqlTarPro = "INSERT INTO {$BaseDatos_systema}.TARPRO (TARPRO_FechaCreacion_____b, TARPRO_ConsInte_TARHOR____b) VALUES(now(), {$idTarea})";
            if ($mysqli->query($sqlTarPro) === true) {
                
                $ultimoResgistroInsertado = $mysqli->insert_id;
                $estado=true;
                $mensaje="Se inserto la accion correctamente";
            }else{
                $mensaje = "No se inserto la accion";
            }
            
        }else{
            $mensaje = "No tiene una tarea en comun";
        }
        $respuesta =json_encode(array("estado"=>$estado,"mensaje"=>$mensaje,"idNuevaAccion"=>$ultimoResgistroInsertado));
        echo $respuesta;

    }

    if(isset($_POST['accion']) && $_POST['accion'] == 'ListarAccionTareaPrograma'){

        $idTarea=isset($_POST['idTarea']) ? $_POST['idTarea'] : 0;
        $estado = false;
        $mensaje="No Hay Mensaje";
        $lista=array();

        //Se consulta las acciones
        if($idTarea > 0){

            $sqlTarPro = "SELECT TARPRO_ConsInte__b, TARPRO_Tipo_De_Tarea____b, TARPRO_Para_Que_Registros____b, TARPRO_ConsInte__USUARI____b, TARPRO_Aplicar_Limite_Cantidad____b, TARPRO_Cantidad_Aplicar_Limite____b
            FROM {$BaseDatos_systema}.TARPRO WHERE TARPRO_ConsInte_TARHOR____b = {$idTarea}";
            $respTarHor = $mysqli->query($sqlTarPro);
            if($respTarHor){
                if($respTarHor ->num_rows > 0){
                    $estado=true;
                    $mensaje="Se Obtuvo Una Lista De Acciones";
                    while ($item = $respTarHor->fetch_object()){
                        $IdAgente= $item->TARPRO_ConsInte__USUARI____b;

                        $ConsultaAgente = "SELECT * FROM DYALOGOCRM_SISTEMA.USUARI WHERE USUARI_ConsInte__b= '". $IdAgente ."';";
                        if ($ResultadoAgente = $mysqli->query($ConsultaAgente)) {
                            $CantidadResultados = $ResultadoAgente->num_rows;
                            if($CantidadResultados > 0) {
                                while ($FilaResultado = $ResultadoAgente->fetch_assoc()) {
                                    $NombreAgente = $FilaResultado['USUARI_Nombre____b'];
                                }
                            }else {
                                //Sin Resultados
                                $NombreAgente = "No Aplica";
                            }
                        }else{
                            $Falla = mysqli_error($mysqli);
                            $respuesta = array("msg" => "Error", "Falla" => $Falla);
                            echo json_encode($respuesta);
                            exit;
                        }
                        array_push($lista,array("TARPRO_ConsInte__b"=>$item->TARPRO_ConsInte__b, "TARPRO_Tipo_De_Tarea____b"=>$item->TARPRO_Tipo_De_Tarea____b,"TARPRO_Para_Que_Registros____b"=>$item->TARPRO_Para_Que_Registros____b, "TARPRO_ConsInte__USUARI____b"=>$item->TARPRO_ConsInte__USUARI____b, "TARPRO_Aplicar_Limite_Cantidad____b"=>$item->TARPRO_Aplicar_Limite_Cantidad____b,  "TARPRO_Cantidad_Aplicar_Limite____b"=>$item->TARPRO_Cantidad_Aplicar_Limite____b, "NombreAgente"=>$NombreAgente));
                    
                    }
                }else{
                    $mensaje="No Existen Acciones Programadas";
                }
            }
        }else{
            $mensaje = "No tiene una tarea en comun";
        }
        $respuesta =json_encode(array("estado"=>$estado,"mensaje"=>$mensaje,"lista"=>$lista));
        echo $respuesta;

    }


    if(isset($_POST['accion']) && $_POST['accion'] == 'DatosActualizarAccionTareaPrograma'){

        $idAccion=isset($_POST['idAccion']) ? $_POST['idAccion'] : 0;
        $arr = $_POST['camposAccion'];
        $arregloAccion = $arr[0];
        $estado = false;
        $mensaje="No Hay Mensaje";
        $asignarUnAgente = "";
        $aplicaLimite = 0;

        if($arregloAccion['tipoTarea'] == 3){
            $asignarUnAgente = " ,TARPRO_ConsInte__USUARI____b= {$arregloAccion['alAgente']} ";

        }else{
            $asignarUnAgente = "";
        }

        if($arregloAccion['aplicaLimite'] == -1){
            $aplicaLimite = $arregloAccion['cantidadLimite'];

        }else{
            $aplicaLimite = 0;
        }
        

        //Se inserta un nuevo registro en TARHOR
        if($idAccion > 0){
            //Se obtiene el id de la bd y la muestra
            $sqlUpdateAccion = "UPDATE {$BaseDatos_systema}.TARPRO
            SET TARPRO_Tipo_De_Tarea____b={$arregloAccion['tipoTarea']}, TARPRO_Para_Que_Registros____b={$arregloAccion['paraQueRegistros']}, TARPRO_Aplicar_Limite_Cantidad____b={$arregloAccion['aplicaLimite']}, TARPRO_Cantidad_Aplicar_Limite____b={$aplicaLimite}, TARPRO_Consulta_sql_b='' {$asignarUnAgente}
            WHERE TARPRO_ConsInte__b = {$idAccion}";
            

            if ($mysqli->query($sqlUpdateAccion) === true) {
                $estado=true;
                $mensaje="Se Actualizó La Acción Correctamente";
            } else {
                echo "ERROR " . $mysqli->error;
            }
            
        }
        $respuesta =json_encode(array("estado"=>$estado,"mensaje"=>$mensaje));
        echo $respuesta;

    }

    if(isset($_GET['agregar_condiciones_accion'])){

        $intEstpas = $_POST["idEstpas_2"];
        $intQueResgistros = $_POST["paraqueregistros_2"];
        $Lsql_sacar_Muestra = "";
        $str_Pobla_Campan = "";
        $strConSinCondiciones = "";
        $strUpdateMuestras = "UPDATE DYALOGOCRM_WEB.G";

        $strDatosCampana_t = "SELECT cam.CAMPAN_ConsInte__GUION__Pob_b as idBase, cam.CAMPAN_ConsInte__MUESTR_b as idMuestra FROM {$BaseDatos_systema}.ESTPAS est
        inner join $BaseDatos_systema.CAMPAN cam
        on est.ESTPAS_ConsInte__CAMPAN_b = cam.CAMPAN_ConsInte__b 
        where ESTPAS_ConsInte__b =".$intEstpas;
        $resSQLCampana_t = $mysqli->query($strDatosCampana_t);

        if ($resSQLCampana_t->num_rows>0) {

            $objCampana_t = $resSQLCampana_t->fetch_object();
            $str_Pobla_Campan = $objCampana_t->idBase;
            $int_Muest_Campan = $objCampana_t->idMuestra;

        }

        $join = "INNER JOIN DYALOGOCRM_WEB.G{$str_Pobla_Campan} ON G{$str_Pobla_Campan}_M{$int_Muest_Campan}_CoInMiPo__b = G{$str_Pobla_Campan}_ConsInte__b";

        //Armamos el update
        if(isset($_POST['tipoTarea']) && $_POST['tipoTarea'] == 1){//Activar

            $strUpdateMuestras .= "{$str_Pobla_Campan}_M{$int_Muest_Campan} {$join} SET G{$str_Pobla_Campan}_M{$int_Muest_Campan}_Activo____b = -1, G{$str_Pobla_Campan}_M{$int_Muest_Campan}_Estado____b = 0, G{$str_Pobla_Campan}_M{$int_Muest_Campan}_ConUltGes_b = 3 ";

        }elseif(isset($_POST['tipoTarea']) && $_POST['tipoTarea'] == 2){//Inactivar
            
            $strUpdateMuestras .= "{$str_Pobla_Campan}_M{$int_Muest_Campan} {$join} SET G{$str_Pobla_Campan}_M{$int_Muest_Campan}_Activo____b = 0, G{$str_Pobla_Campan}_M{$int_Muest_Campan}_FecHorMinProGes__b = NULL, G{$str_Pobla_Campan}_M{$int_Muest_Campan}_NumeInte__b = 0, G{$str_Pobla_Campan}_M{$int_Muest_Campan}_FecUltGes_b = NULL, G{$str_Pobla_Campan}_M{$int_Muest_Campan}_FecHorAge_b = NULL ";

        }elseif(isset($_POST['tipoTarea']) && $_POST['tipoTarea'] == 3){//Asignar un agente

            $strUpdateMuestras .= "{$str_Pobla_Campan}_M{$int_Muest_Campan} {$join} SET G{$str_Pobla_Campan}_M{$int_Muest_Campan}_ConIntUsu_b = {$_POST['asignarAgente']} ";

        }elseif (isset($_POST['tipoTarea']) && $_POST['tipoTarea'] == 4) {//desasignar

            $strUpdateMuestras .= "{$str_Pobla_Campan}_M{$int_Muest_Campan} {$join} SET G{$str_Pobla_Campan}_M{$int_Muest_Campan}_ConIntUsu_b = null ";
            
        }

        //Armamos el sql para actualizar la accion
        if($intQueResgistros == 2){

            /* agregar el filtro de la base de datos */
            $valido = 0;
            for ($i=1; $i <= $_POST['contador']; $i++) { 

                if($valido == 0){
                    $separador = " WHERE ";
                    // Valido si hay un separador inicial
                    if(isset($_POST['andOr_'.$i]) && $_POST['andOr_'.$i] == '('){
                        $separador .= "( ";
                    }
                }else{
                    if(isset($_POST['andOr_'.$i])){
                        $separador = $_POST['andOr_'.$i];
                    }else{
                        $separador = " AND "; 
                    }
                }
                
                // Valido si hay un dato en la pregunta
                if(isset($_POST['pregun_'.$i]) && $_POST['pregun_'.$i] != '0'){
                    $datosAbuscar = "G".$str_Pobla_Campan."_C";
                    if(isset($_POST['esMuestra_'.$i])){
                        /* es un campo de la muestra */
                        $datosAbuscar = "G".$str_Pobla_Campan."_M".$int_Muest_Campan;
                    }else if(!is_numeric($_POST['pregun_'.$i])){
                        $datosAbuscar = "G".$str_Pobla_Campan."_";
                    }

                    // Buscamos el separadorFinal
                    $separadorFinal = '';
                    if(isset($_POST['cierre'.$i]) && $_POST['cierre'.$i] != ''){
                        $separadorFinal = $_POST['cierre'.$i];
                    }

                    if($_POST['condicion_'.$i] == '='){
                        $Lsql_sacar_Muestra .= $separador." ".$datosAbuscar.$_POST['pregun_'.$i]." = '".$_POST['valor_'.$i]."' ".$separadorFinal; 
                        $valido = 1; 
                    }else if($_POST['condicion_'.$i] == '!='){
                        $Lsql_sacar_Muestra .= $separador." ".$datosAbuscar.$_POST['pregun_'.$i]." != '".$_POST['valor_'.$i]."' ".$separadorFinal; 
                        $valido = 1;
                    }else if ($_POST['condicion_'.$i] == 'LIKE_1') {
                        $Lsql_sacar_Muestra .= $separador." ".$datosAbuscar.$_POST['pregun_'.$i]." LIKE '".$_POST['valor_'.$i]."%' ".$separadorFinal; 
                        $valido = 1;  
                    }else if ($_POST['condicion_'.$i] == 'LIKE_2') {
                        $Lsql_sacar_Muestra .= $separador." ".$datosAbuscar.$_POST['pregun_'.$i]." LIKE '%".$_POST['valor_'.$i]."%' ".$separadorFinal; 
                        $valido = 1; 
                    }else if ($_POST['condicion_'.$i] == 'LIKE_3') {
                        $Lsql_sacar_Muestra .= $separador." ".$datosAbuscar.$_POST['pregun_'.$i]." LIKE '%".$_POST['valor_'.$i]."' ".$separadorFinal; 
                        $valido = 1; 
                    }else if($_POST['condicion_'.$i] == '>'){
                        $Lsql_sacar_Muestra .= $separador." ".$datosAbuscar.$_POST['pregun_'.$i]." > '".$_POST['valor_'.$i]."' ".$separadorFinal; 
                        $valido = 1;
                    }else if($_POST['condicion_'.$i] == '<'){
                        $Lsql_sacar_Muestra .= $separador." ".$datosAbuscar.$_POST['pregun_'.$i]." < '".$_POST['valor_'.$i]."' ".$separadorFinal; 
                        $valido = 1;
                    }

                    $separdorCondicional = 'NULL';
                    if($separador != 'WHERE'){
                        $separdorCondicional = $separador;
                    }
                    
                    $LsqlInsert = "INSERT INTO ".$BaseDatos_systema.".TARCON (TARCON_ConsInte_TARPRO____b , TARCON_Campo____b, TARCON_Condicion____b, TARCON_Valor____b, TARCON_Separador____b, TARCON_Separador_Final____b) VALUES (".$_POST["idAccion"]." , '".$_POST['pregun_'.$i]."' , '".$_POST['condicion_'.$i]."' , '".$_POST['valor_'.$i]."' , '".$separdorCondicional."', '".$separadorFinal."')";  
                    if($mysqli->query($LsqlInsert) === true){
                        echo $LsqlInsert;
                    }else{
                        //Error en la Insercion
                        echo "Error TARCON => ".$mysqli->error;
                        mysqli_close($mysqli);
                        exit;
                    }
                }
                
            }
            $strUpdateMuestras .= " $Lsql_sacar_Muestra";
            
        }

        // Aplicamos el limite de ejecucion
        if(isset($_POST['aplicaLimite']) && $_POST['aplicaLimite'] == '-1'){
            if(isset($_POST['numbCantidadLimite']) && $_POST['numbCantidadLimite'] > 0){
                $strUpdateMuestras .= " LIMIT ".$_POST['numbCantidadLimite'];
            }
        }

        $strUpdateMuestras = str_replace("'", "\'", $strUpdateMuestras);
        //Actualizamos la accion
        $sqlUpdateAccion = "UPDATE {$BaseDatos_systema}.TARPRO SET TARPRO_Consulta_sql_b='{$strUpdateMuestras}' WHERE TARPRO_ConsInte__b = {$_POST['idAccion']}";
        echo $sqlUpdateAccion;

        if ($mysqli->query($sqlUpdateAccion) === true) {
            $estado=true;
            $mensaje="Se Actualizó La Acción Correctamente";
        } else {
            echo "ERROR " . $mysqli->error;
        }

        $respuesta =json_encode(array("estado"=>$estado,"mensaje"=>$mensaje));
        echo $respuesta;

    }
    
    //Funcion Para Eliminar Tareas
    if(isset($_POST['accion']) && $_POST['accion'] == 'EliminarTarea'){
        $IdTarea= $_POST['IdTarea'];
        $EliminarSQL= "DELETE FROM DYALOGOCRM_SISTEMA.TARPRO WHERE TARPRO_ConsInte_TARHOR____b= '". $IdTarea ."';";
        if ($ResultadoSQL= $mysqli->query($EliminarSQL)) {
            //Eliminación correcta acciones
            $EliminarSQL_2= "DELETE FROM DYALOGOCRM_SISTEMA.TARHOR WHERE TARHOR_ConsInte__b= '". $IdTarea ."';";
            if ($ResultadoSQL_= $mysqli->query($EliminarSQL_2)) {
                //Eliminación correcta tarea
                $php_response= array("msg" => "Ok");
                echo json_encode($php_response);
                mysqli_close($mysqli);
                exit;
            } else {
                //Error en la Eliminación
                $php_response= array("msg" => "Error");
                $ErrorConsulta= mysqli_error($mysqli);
                mysqli_close($mysqli);
                echo $ErrorConsulta;
                exit;
            }
        } else {
            //Error en la Eliminación
            $php_response= array("msg" => "Error");
            $ErrorConsulta= mysqli_error($mysqli);
            mysqli_close($mysqli);
            echo $ErrorConsulta;
            exit;
        }
    }

    //Funcion Para Eliminar Accion
    if(isset($_POST['accion']) && $_POST['accion'] == 'EliminarAccion'){
        $IdAccion= $_POST['IdAccion'];
        $EliminarSQL= "DELETE FROM DYALOGOCRM_SISTEMA.TARPRO WHERE TARPRO_ConsInte__b= '". $IdAccion ."';";
        if($ResultadoSQL= $mysqli->query($EliminarSQL)){
            //Eliminación correcta
            $php_response= array("msg" => "Ok");
            echo json_encode($php_response);
            mysqli_close($mysqli);
            exit;
        }else{
            //Error en la Eliminación
            $php_response= array("msg" => "Error");
            $ErrorConsulta= mysqli_error($mysqli);
            mysqli_close($mysqli);
            echo $ErrorConsulta;
            exit;
        }
    }

    //Funcion Para Eliminar Condiciones
    if(isset($_POST['accion']) && $_POST['accion'] == 'EliminarCondiciones'){
        $IdAccion= $_POST['IdAccion'];
        $EliminarSQL= "DELETE FROM DYALOGOCRM_SISTEMA.TARCON WHERE TARCON_ConsInte_TARPRO____b= '". $IdAccion ."';";
        if($ResultadoSQL= $mysqli->query($EliminarSQL)){
            //Eliminación correcta
            $php_response= array("msg" => "Ok");
            echo json_encode($php_response);
            mysqli_close($mysqli);
            exit;
        }else{
            //Error en la Eliminación
            $php_response= array("msg" => "Error");
            $ErrorConsulta= mysqli_error($mysqli);
            mysqli_close($mysqli);
            echo $ErrorConsulta;
            exit;
        }
    }

    //Funcion Para Consultar Agentes
    if(isset($_POST['accion']) && $_POST['accion'] == 'ConsultarAgentes'){
        $IdEstrat= $_POST['IdEstpas'];
        //Consulta campaña
        $ConsultaSQL= "SELECT ESTPAS_ConsInte__CAMPAN_b AS IdCampana from DYALOGOCRM_SISTEMA.ESTPAS WHERE ESTPAS_ConsInte__b = '". $IdEstrat ."';";
        if ($ResultadoSQL = $mysqli->query($ConsultaSQL)) {
            $CantidadResultados = $ResultadoSQL->num_rows;
            if($CantidadResultados > 0) {
                while ($FilaResultado = $ResultadoSQL->fetch_assoc()) {
                    $IdCampana= $FilaResultado['IdCampana'];
                }
                //Consulta Usuario
                $ListaAgentes = "";
                $ConsultaSQL_2= "SELECT USUARI_ConsInte__b AS IdUsuario, USUARI_Nombre____b AS NombreUsuario FROM DYALOGOCRM_SISTEMA.ASITAR INNER JOIN DYALOGOCRM_SISTEMA.USUARI ON ASITAR_ConsInte__USUARI_b = USUARI_ConsInte__b WHERE ASITAR_ConsInte__CAMPAN_b = '". $IdCampana ."'";
                if ($ResultadoSQL_2 = $mysqli->query($ConsultaSQL_2)) {
                    $CantidadResultados_2 = $ResultadoSQL_2->num_rows;
                    if($CantidadResultados_2 > 0) {
                        while ($FilaResultado = $ResultadoSQL_2->fetch_assoc()) {
                            $IdUsuario= $FilaResultado['IdUsuario'];
                            $NombreUsuario= $FilaResultado['NombreUsuario'];
                            $ListaAgentes = $ListaAgentes . '<option value="' . $FilaResultado['IdUsuario'] . '">' . $FilaResultado['NombreUsuario'] . '</option>';
                        }
                        
                        //Si realizo Consulta
                        $php_response = array("msg" => "Ok", "Respuesta" => $ListaAgentes);
                        echo json_encode($php_response);
                        mysqli_close($mysqli);
                        exit;

                    }else{
                        $ErrorConsulta = mysqli_error($mysqli);
                        mysqli_close($mysqli);
                        echo '<script>alert("Error Falla -> ' . $ErrorConsulta . '");</script>';
                        exit;
                    }
                }else{
                    //Sin Resultados
                    $php_response = array("msg" => "Nada");
                    mysqli_close($mysqli);
                    echo json_encode($php_response);
                    exit;
                }
            }else {
                //Sin Resultados
                $php_response = array("msg" => "Nada");
                mysqli_close($mysqli);
                echo json_encode($php_response);
                exit;
            }
        } else {
            mysqli_close($mysqli);
            $Falla = mysqli_error($mysqli);
            $php_response = array("msg" => "Error", "Falla" => $Falla);
            echo json_encode($php_response);
            exit;
        }
    }

    
?>
