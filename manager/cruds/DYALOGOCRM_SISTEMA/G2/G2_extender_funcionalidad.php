<?php
    session_start();
    ini_set('display_errors', 'On');
    ini_set('display_errors', 1);
    include(__DIR__."../../../../pages/conexion.php");
    require_once('../../../../helpers/parameters.php');
    require_once('../../../global/WSCoreClient.php');
    date_default_timezone_set('America/Bogota');
    function guardar_auditoria($accion, $superAccion) {
        
        global $mysqli;
        global $BaseDatos_systema;

        $str_Lsql = "INSERT INTO " . $BaseDatos_systema . ".AUACAD (AUACAD_Fecha_____b , AUACAD_Hora______b, AUACAD_Ejecutor__b, AUACAD_TipoAcci__b , AUACAD_SubTipAcc_b, AUACAD_Accion____b , AUACAD_Huesped___b ) VALUES ('" . date('Y-m-d H:s:i') . "', '" . date('Y-m-d H:s:i') . "', " . $_SESSION['IDENTIFICACION'] . ", 'G2', '" . $accion . "', '" .$mysqli->real_escape_string($superAccion). "', " . $_SESSION['HUESPED'] . " );";
        $mysqli->query($str_Lsql);

    }

    include(__DIR__."../../../../global/funcionesGenerales.php");

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

    //Este archivo es para agregar funcionalidades al G, y que al momento de generar de nuevo no se pierdan

    //Cosas como nuevas consultas, nuevos Inserts, Envio de correos, etc, en fin extender mas los formularios en PHP
    if(isset($_GET['Litacompuesta'])){
        $str_Lsql = "SELECT PREGUN_ConsInte__GUION__PRE_B FROM ".$BaseDatos_systema.".PREGUN WHERE PREGUN_ConsInte__b = ".$_POST['idPregun'];
        $obj = $mysqli->query($str_Lsql);
        $guion = $obj->fetch_array();

        /*Ahora obtenemos los cmaos principales y segundarios */
        $guionP = "SELECT GUION__ConsInte__PREGUN_Pri_b FROM ".$BaseDatos_systema.".GUION_ WHERE GUION__ConsInte__b = ".$guion['PREGUN_ConsInte__GUION__PRE_B'];
        $objN = $mysqli->query($guionP);
        $prin = $objN->fetch_array();

        /*Obtenemos la consulta */
        $Lsql = "SELECT G".$guion['PREGUN_ConsInte__GUION__PRE_B']."_ConsInte__b as id , G".$guion['PREGUN_ConsInte__GUION__PRE_B']."_C".$prin['GUION__ConsInte__PREGUN_Pri_b']." as texto  FROM ".$BaseDatos.".G".$guion['PREGUN_ConsInte__GUION__PRE_B'];
        $obj = $mysqli->query($Lsql);
        while($obje = $obj->fetch_object()){
            echo "<option value='".$obje->id."'>".($obje->texto)."</option>";
        } 
    }

    if(isset($_GET['Lisopc'])){
        $str_Lsql = "SELECT LISOPC_ConsInte__b AS OPCION_ConsInte__b, LISOPC_Nombre____b AS OPCION_Nombre____b FROM ".$BaseDatos_systema.".LISOPC WHERE LISOPC_ConsInte__OPCION_b = ".$_POST['lisopc']." ORDER BY LISOPC_Nombre____b ASC ";
        $obj = $mysqli->query($str_Lsql);
        while($obje = $obj->fetch_object()){
            echo "<option value='".$obje->OPCION_ConsInte__b."'>".($obje->OPCION_Nombre____b)."</option>";
        } 
    }

    if (isset($_GET['tieneCampana'])) {
        $Lsql = "SELECT ESTPAS_ConsInte__CAMPAN_b FROM ".$BaseDatos_systema.".ESTPAS WHERE ESTPAS_ConsInte__b = ".$_POST['pasoId'];
        $res_Lsql = $mysqli->query($Lsql);
        $data = $res_Lsql->fetch_array();
        if($data['ESTPAS_ConsInte__CAMPAN_b'] != '' && !empty($data['ESTPAS_ConsInte__CAMPAN_b']) && !is_null($data['ESTPAS_ConsInte__CAMPAN_b'])){
            echo md5 ( clave_get . $data['ESTPAS_ConsInte__CAMPAN_b']);
        }else{
            echo "0";
        }
    }    

    if (isset($_GET['hayCampana'])) {
        echo md5 ( clave_get . $_POST['pasoId']);
    }

    if(isset($_GET['validaPasosPrincipales'])){
        /* validar que los pasos sean campañas entrantes */
        $Lsql = "SELECT ESTPAS_Tipo______b FROM ".$BaseDatos_systema.".ESTPAS WHERE ESTPAS_ConsInte__b = ".$_POST['paso'];
        $res_Lsql = $mysqli->query($Lsql);
        $data = $res_Lsql->fetch_array();
        if($data['ESTPAS_Tipo______b'] == 1 || $data['ESTPAS_Tipo______b'] == 6  || $data['ESTPAS_Tipo______b'] == 4  || $data['ESTPAS_Tipo______b'] == 5 || $data['ESTPAS_Tipo______b'] == 9 || $data['ESTPAS_Tipo______b'] == 10 || $data['ESTPAS_Tipo______b'] == 11 || $data['ESTPAS_Tipo______b'] == 8 || $data['ESTPAS_Tipo______b'] == 12 || $data['ESTPAS_Tipo______b'] == 14 || $data['ESTPAS_Tipo______b'] == 15 || $data['ESTPAS_Tipo______b'] == 16 || $data['ESTPAS_Tipo______b'] == 17 || $data['ESTPAS_Tipo______b'] == 18 || $data['ESTPAS_Tipo______b'] == 19 || $data['ESTPAS_Tipo______b'] == 21){
            /* pasa la pruebas son campañas */
            // echo $data['ESTPAS_Tipo______b'];
            echo json_encode(['tipo' => $data['ESTPAS_Tipo______b'], 'paso' => $_POST['paso']]);
        }else{
            // echo "0";
            echo json_encode(['tipo' => 0, 'paso' => $_POST['paso']]);
        }
    }

    if(isset($_GET['validaPasosSecundarios'])){
        /* validar que los pasos sean campañas entrantes */
        $Lsql = "SELECT ESTPAS_Tipo______b FROM ".$BaseDatos_systema.".ESTPAS WHERE ESTPAS_ConsInte__b = ".$_POST['paso'];
        $res_Lsql = $mysqli->query($Lsql);
        $data = $res_Lsql->fetch_array();
        if($data['ESTPAS_Tipo______b'] == 4 || $data['ESTPAS_Tipo______b'] == 6 || $data['ESTPAS_Tipo______b'] == 7 || $data['ESTPAS_Tipo______b'] == 8 || $data['ESTPAS_Tipo______b'] == 9 || $data['ESTPAS_Tipo______b'] == 10 || $data['ESTPAS_Tipo______b'] == 13 || $data['ESTPAS_Tipo______b'] == 12 || $data['ESTPAS_Tipo______b'] == 1 || $data['ESTPAS_Tipo______b'] == 18 || $data['ESTPAS_Tipo______b'] == 22){
            /* pasa la pruebas son campañas o mails salientes o sms salientes o campañas de BackOffice */
            // echo "1";
            echo json_encode(['tipo' => $data['ESTPAS_Tipo______b'], 'paso' => $_POST['paso']]);
        }else{
            // echo "0";
            echo json_encode(['tipo' => 0, 'paso' => $_POST['paso']]);
        }
    }

    if(isset($_GET['gestionar_base_datos'])){
        ini_set('display_errors', 'On');
        ini_set('display_errors', 1);
        /* primero obtenemos la campaña asociada al paso principal */

        $Lsql = "SELECT ESTPAS_Tipo______b FROM ".$BaseDatos_systema.".ESTPAS WHERE ESTPAS_ConsInte__b = ".$_POST['from'];
        $res_Lsql = $mysqli->query($Lsql);
        $datos = $res_Lsql->fetch_array();

        $tipoPaso = $datos['ESTPAS_Tipo______b'];

        if($datos['ESTPAS_Tipo______b'] == 6 || $datos['ESTPAS_Tipo______b'] == 1 ){
            $Lsql = "SELECT ESTPAS_ConsInte__CAMPAN_b as id_campana FROM ".$BaseDatos_systema.".ESTPAS WHERE ESTPAS_ConsInte__b = ".$_POST['from'];
        }else{
            $Lsql = "SELECT ESTPAS_ConsInte__CAMPAN_b as id_campana , ESTPAS_ConsInte__ESTRAT_b FROM ".$BaseDatos_systema.".ESTPAS WHERE ESTPAS_ConsInte__b = ".$_POST['to'];
        }

        $res_Lsql = $mysqli->query($Lsql);
        $datos = $res_Lsql->fetch_array();

        // Termina la ejecucion si la campaña no esta previamente configurada
        if(($tipoPaso == 6 || $tipoPaso == 1) && is_null($datos['id_campana'])){
            echo "Para poder guardar la configuración debes tener inicialmente configurado los pasos que conectan la flecha";
            exit();
        }

        $Lsql_Campan = "SELECT CAMPAN_ConsInte__GUION__Pob_b, CAMPAN_ConsInte__MUESTR_b  FROM ".$BaseDatos_systema.".CAMPAN WHERE CAMPAN_ConsInte__b = ".$datos["id_campana"];

        $res_Lsql_Campan = $mysqli->query($Lsql_Campan);
        if($res_Lsql_Campan){
            $datoCampan = $res_Lsql_Campan->fetch_array();

            $str_Pobla_Campan = "G".$datoCampan['CAMPAN_ConsInte__GUION__Pob_b'];
            $int_Pobla_Camp_2 = $datoCampan['CAMPAN_ConsInte__GUION__Pob_b'];
            $int_Muest_Campan = $datoCampan['CAMPAN_ConsInte__MUESTR_b'];
        }else{
            $LsqlEstrat = "SELECT ESTRAT_ConsInte_GUION_Pob FROM ".$BaseDatos_systema.".ESTRAT WHERE ESTRAT_ConsInte__b = ".$datos['ESTPAS_ConsInte__ESTRAT_b'];
            $res_Estrat = $mysqli->query($LsqlEstrat);
            $res_Estrat = $res_Estrat->fetch_array();
            $str_Pobla_Campan = "G".$res_Estrat['ESTRAT_ConsInte_GUION_Pob'];
            $int_Pobla_Camp_2 = $res_Estrat['ESTRAT_ConsInte_GUION_Pob'];
            $int_Muest_Campan = 0;
        }
        
       
        $Lsql_sacar_Muestra = '';
        $Lsql_Insercion = '';


        $IdConLsql = "SELECT ESTCON_ConsInte__b FROM ".$BaseDatos_systema.".ESTCON WHERE ESTCON_ConsInte__ESTPAS_Des_b = ".$_POST['from']." AND ESTCON_ConsInte__ESTPAS_Has_b = ".$_POST['to'];
        $res= $mysqli->query($IdConLsql);
        $datoIs = $res->fetch_array();

        $DelSql = "DELETE FROM ".$BaseDatos_systema.".ESTCON_CONDICIONES WHERE id_estcon = ".$datoIs['ESTCON_ConsInte__b'];
        $mysqli->query($DelSql);

        if($_POST['radiocondiciones'] == 1){
            /* todos */
            if($int_Muest_Campan  == 0){
                $Lsql_sacar_Muestra = "SELECT ".$str_Pobla_Campan."_ConsInte__b as id FROM ".$BaseDatos.".".$str_Pobla_Campan;
            }else{
                $Lsql_sacar_Muestra = "SELECT ".$str_Pobla_Campan."_ConsInte__b as id FROM ".$BaseDatos.".".$str_Pobla_Campan." LEFT JOIN  ".$BaseDatos.".".$str_Pobla_Campan."_M".$int_Muest_Campan." ON ".$str_Pobla_Campan."_ConsInte__b  = ".$str_Pobla_Campan."_M".$int_Muest_Campan."_CoInMiPo__b";
            }
            $Lsql_Insercion = $Lsql_sacar_Muestra;

        }else if($_POST['radiocondiciones'] == 2){
            /* cantidad fija y aleatioria */
            if($int_Muest_Campan  == 0){
                $Lsql_sacar_Muestra = "SELECT ".$str_Pobla_Campan."_ConsInte__b as id FROM ".$BaseDatos.".".$str_Pobla_Campan." order by RAND()  LIMIT ".$_POST['txtCantidadRegistrps'].";";
            }else{
                $Lsql_sacar_Muestra = "SELECT ".$str_Pobla_Campan."_ConsInte__b as id FROM ".$BaseDatos.".".$str_Pobla_Campan." LEFT JOIN  ".$BaseDatos.".".$str_Pobla_Campan."_M".$int_Muest_Campan." ON ".$str_Pobla_Campan."_ConsInte__b  = ".$str_Pobla_Campan."_M".$int_Muest_Campan."_CoInMiPo__b order by RAND()  LIMIT ".$_POST['txtCantidadRegistrps'].";";
    
            }
         
            $Lsql_Insercion = $Lsql_sacar_Muestra;
            $LsqlInsert = "INSERT INTO ".$BaseDatos_systema.".ESTCON_CONDICIONES (id_estcon , campo, condicion, valor) VALUES (".$datoIs['ESTCON_ConsInte__b']." , 'RAND' , 'LIMIT' , '".$_POST['txtCantidadRegistrps']."')";  
            if($mysqli->query($LsqlInsert) === true){

            }else{
                echo "Error ESTCON_CONDICIONES RAND => ".$mysqli->error;
            }

        }else if($_POST['radiocondiciones'] == 3){
            /* filtros */
            if($int_Muest_Campan  == 0){
                $Lsql_sacar_Muestra = "SELECT ".$str_Pobla_Campan."_ConsInte__b as id FROM ".$BaseDatos.".".$str_Pobla_Campan;
            }else{
                $Lsql_sacar_Muestra = "SELECT ".$str_Pobla_Campan."_ConsInte__b as id FROM ".$BaseDatos.".".$str_Pobla_Campan." LEFT JOIN  ".$BaseDatos.".".$str_Pobla_Campan."_M".$int_Muest_Campan." ON ".$str_Pobla_Campan."_ConsInte__b  = ".$str_Pobla_Campan."_M".$int_Muest_Campan."_CoInMiPo__b ";
    
            }

            /* agregar el filtro de la base de datos */
            $valido = 0;
            for ($i=1; $i < $_POST['contador']; $i++) { 

                if($valido == 0){
                    $separador = " WHERE ";

                    // Valido si hay un separador inicial
                    if(isset($_POST['condiciones_'.$i]) && $_POST['condiciones_'.$i] == '('){
                        $separador .= "( ";
                    }
                }else{
                    if(isset($_POST['condiciones_'.$i])){
                        $separador = $_POST['condiciones_'.$i];    
                    }else{
                        $separador = " AND "; 
                    }
                }
                
                // Valido si hay un dato en la pregunta
                if(isset($_POST['pregun_'.$i]) && $_POST['pregun_'.$i] != '0'){
                    $datosAbuscar = $str_Pobla_Campan."_C";
                    if(isset($_POST['esMuestra_'.$i])){
                        /* es un campo de la muestra */
                        $datosAbuscar = $str_Pobla_Campan."_M".$int_Muest_Campan;
                    }else if(!is_numeric($_POST['pregun_'.$i])){
                        $datosAbuscar = $str_Pobla_Campan."_";
                    }

                    // Buscamos el separadorFinal
                    $separadorFinal = '';
                    if(isset($_POST['cierre'.$i]) && $_POST['cierre'.$i] != ''){
                        $separadorFinal = $_POST['cierre'.$i];
                    }

                    if($_POST['dyTr_condicion_'.$i] == '='){
                        $Lsql_sacar_Muestra .= $separador.$datosAbuscar.$_POST['pregun_'.$i]." = '".$_POST['valor_'.$i]."'".$separadorFinal; 
                        $valido = 1; 
                    }else if($_POST['dyTr_condicion_'.$i] == '!='){
                        $Lsql_sacar_Muestra .= $separador.$datosAbuscar.$_POST['pregun_'.$i]." != '".$_POST['valor_'.$i]."'".$separadorFinal; 
                        $valido = 1;
                    }else if ($_POST['dyTr_condicion_'.$i] == 'LIKE_1') {
                        $Lsql_sacar_Muestra .= $separador.$datosAbuscar.$_POST['pregun_'.$i]." LIKE '".$_POST['valor_'.$i]."%'".$separadorFinal; 
                        $valido = 1;  
                    }else if ($_POST['dyTr_condicion_'.$i] == 'LIKE_2') {
                        $Lsql_sacar_Muestra .= $separador.$datosAbuscar.$_POST['pregun_'.$i]." LIKE '%".$_POST['valor_'.$i]."%'".$separadorFinal; 
                        $valido = 1; 
                    }else if ($_POST['dyTr_condicion_'.$i] == 'LIKE_3') {
                        $Lsql_sacar_Muestra .= $separador.$datosAbuscar.$_POST['pregun_'.$i]." LIKE '%".$_POST['valor_'.$i]."'".$separadorFinal; 
                        $valido = 1; 
                    }else if($_POST['dyTr_condicion_'.$i] == '>'){
                        $Lsql_sacar_Muestra .= $separador.$datosAbuscar.$_POST['pregun_'.$i]." > '".$_POST['valor_'.$i]."'".$separadorFinal; 
                        $valido = 1;
                    }else if($_POST['dyTr_condicion_'.$i] == '<'){
                        $Lsql_sacar_Muestra .= $separador.$datosAbuscar.$_POST['pregun_'.$i]." < '".$_POST['valor_'.$i]."'".$separadorFinal; 
                        $valido = 1;
                    }else if ($_POST['dyTr_condicion_'.$i] == 'MayorMes'){
                        $Lsql_sacar_Muestra .= $separador." ".$datosAbuscar.$_POST['pregun_'.$i]." > DATE_SUB(NOW(), INTERVAL '".$_POST['valor_'.$i]."' MONTH) ".$separadorFinal; 
                        $valido = 1;
                    }else if ($_POST['dyTr_condicion_'.$i] == 'MenorMes'){
                        $Lsql_sacar_Muestra .= $separador." ".$datosAbuscar.$_POST['pregun_'.$i]." < DATE_SUB(NOW(), INTERVAL '".$_POST['valor_'.$i]."' MONTH) ".$separadorFinal; 
                        $valido = 1;
                    }else if ($_POST['dyTr_condicion_'.$i] == 'MayorDia'){
                        $Lsql_sacar_Muestra .= $separador." ".$datosAbuscar.$_POST['pregun_'.$i]." > DATE_SUB(NOW(), INTERVAL '".$_POST['valor_'.$i]."' DAY) ".$separadorFinal; 
                        $valido = 1;
                    }else if ($_POST['dyTr_condicion_'.$i] == 'MenorDia'){
                        $Lsql_sacar_Muestra .= $separador." ".$datosAbuscar.$_POST['pregun_'.$i]." < DATE_SUB(NOW(), INTERVAL '".$_POST['valor_'.$i]."' DAY) ".$separadorFinal; 
                        $valido = 1;
                    }

                    $separdorCondicional = 'NULL';
                    if($separador != 'WHERE'){
                        $separdorCondicional = $separador;
                    }
                    $LsqlInsert = "INSERT INTO ".$BaseDatos_systema.".ESTCON_CONDICIONES (id_estcon , campo, condicion, valor, separador, separador_final) VALUES (".$datoIs['ESTCON_ConsInte__b']." , '".$_POST['pregun_'.$i]."' , '".$_POST['dyTr_condicion_'.$i]."' , '".$_POST['valor_'.$i]."' , '".$separdorCondicional."', '".$separadorFinal."')";  
                    if($mysqli->query($LsqlInsert) === true){

                    }else{
                        echo "Error ESTCON_CONDICIONES => ".$mysqli->error;
                    }
                }
                
            }

            $Lsql_Insercion = $Lsql_sacar_Muestra;


        }else if($_POST['radiocondiciones'] == 4){
            /* filtros y aleatorios */
            if($int_Muest_Campan  == 0){
                $Lsql_sacar_Muestra = "SELECT ".$str_Pobla_Campan."_ConsInte__b as id FROM ".$BaseDatos.".".$str_Pobla_Campan;
            }else{
                $Lsql_sacar_Muestra = "SELECT ".$str_Pobla_Campan."_ConsInte__b as id FROM ".$BaseDatos.".".$str_Pobla_Campan." LEFT JOIN  ".$BaseDatos.".".$str_Pobla_Campan."_M".$int_Muest_Campan." ON ".$str_Pobla_Campan."_ConsInte__b  = ".$str_Pobla_Campan."_M".$int_Muest_Campan."_CoInMiPo__b ";    
            }
            

            /* agregar el filtro de la base de datos */
            $valido = 0;
            for ($i=1; $i < $_POST['contador']; $i++) { 

                if($valido == 0){
                    $separador = " WHERE ";
                }else{
                    if(isset($_POST['condiciones_'.$i])){
                        $separador = $_POST['condiciones_'.$i];    
                    }else{
                        $separador = " AND "; 
                    }  
                }
                
                
                
                            

                if(isset($_POST['pregun_'.$i]) && $_POST['pregun_'.$i] != '0'){
                    $datosAbuscar = $str_Pobla_Campan."_C";
                    if(isset($_POST['esMuestra_'.$i])){
                        /* es un campo de la muestra */
                        $datosAbuscar = $str_Pobla_Campan."_M".$int_Muest_Campan;
                    }

                    if($_POST['condicion_'.$i] == '='){
                        $Lsql_sacar_Muestra .= $separador.$datosAbuscar.$_POST['pregun_'.$i]." = '".$_POST['valor_'.$i]."'"; 
                        $valido = 1; 
                    }else if($_POST['condicion_'.$i] == '!='){
                        $Lsql_sacar_Muestra .= $separador.$datosAbuscar.$_POST['pregun_'.$i]." != '".$_POST['valor_'.$i]."'";  
                        $valido = 1;
                    }else if ($_POST['condicion_'.$i] == 'LIKE_1') {
                        $Lsql_sacar_Muestra .= $separador.$datosAbuscar.$_POST['pregun_'.$i]." LIKE '".$_POST['valor_'.$i]."%'";
                        $valido = 1;  
                    }else if ($_POST['condicion_'.$i] == 'LIKE_2') {
                        $Lsql_sacar_Muestra .= $separador.$datosAbuscar.$_POST['pregun_'.$i]." LIKE '%".$_POST['valor_'.$i]."%'"; 
                        $valido = 1; 
                    }else if ($_POST['condicion_'.$i] == 'LIKE_3') {
                        $Lsql_sacar_Muestra .= $separador.$datosAbuscar.$_POST['pregun_'.$i]." LIKE '%".$_POST['valor_'.$i]."'"; 
                        $valido = 1; 
                    }else if($_POST['condicion_'.$i] == '>'){
                        $Lsql_sacar_Muestra .= $separador.$datosAbuscar.$_POST['pregun_'.$i]." > '".$_POST['valor_'.$i]."'";  
                        $valido = 1;
                    }else if($_POST['condicion_'.$i] == '<'){
                        $Lsql_sacar_Muestra .= $separador.$datosAbuscar.$_POST['pregun_'.$i]." < '".$_POST['valor_'.$i]."'";  
                        $valido = 1;
                    }    

                    $separdorCondicional = 'NULL';
                    if($separador != 'WHERE'){
                        $separdorCondicional = $separador;
                    }
                    $LsqlInsert = "INSERT INTO ".$BaseDatos_systema.".ESTCON_CONDICIONES (id_estcon , campo, condicion, valor, separador) VALUES (".$datoIs['ESTCON_ConsInte__b']." , '".$_POST['pregun_'.$i]."' , '".$_POST['condicion_'.$i]."' , '".$_POST['valor_'.$i]."' , '".$separdorCondicional."')";  
                    if($mysqli->query($LsqlInsert) === true){

                    }else{
                        echo "Error ESTCON_CONDICIONES => ".$mysqli->error;
                    }
                }
                
            }

            $Lsql_Insercion = $Lsql_sacar_Muestra." order by RAND()  LIMIT ".$_POST['txtCantidadRegistrps'].";";

            $LsqlInsert = "INSERT INTO ".$BaseDatos_systema.".ESTCON_CONDICIONES (id_estcon , campo, condicion, valor) VALUES (".$datoIs['ESTCON_ConsInte__b']." , 'RAND' , 'LIMIT' , '".$_POST['txtCantidadRegistrps']."')";  
            if($mysqli->query($LsqlInsert) === true){

            }else{
                echo "Error ESTCON_CONDICIONES RAND => ".$mysqli->error;
            }
            
        }else{
            // Si no se cumple niguna de las anteriores dejo esto en null
            $Lsql_Insercion = '';
        }

        

        $Lsql_Insercion = $mysqli->real_escape_string($Lsql_Insercion);

        // Obtenemos el tipo de insercion
        $cmbTipoInsercion = 0;
        if(isset($_POST['cmbTipoInsercion']) &&  $_POST['cmbTipoInsercion'] != ''){
            $cmbTipoInsercion = $_POST['cmbTipoInsercion'];
        }

        // Se obtiene el numero de dias
        $FechaNum = 0;
        if(isset($_POST['txtRestaSumaFecha']) && $_POST['txtRestaSumaFecha'] != ''){
            $FechaNum = $cmbTipoInsercion == 0 ? 0 : $_POST['txtRestaSumaFecha'];
        }

        // Se obtiene el numero de horas
        $HoraNum = 0;
        if(isset($_POST['txtRestaSumaHora']) && $_POST['txtRestaSumaHora'] != ''){
            $HoraNum = $cmbTipoInsercion == 0 ? 0 : $_POST['txtRestaSumaHora'];
        }

        // Campo fecha
        $cmbCampoFecha = 0;
        if(isset($_POST['cmbCampoFecha']) &&  $_POST['cmbCampoFecha'] != ''){
            $cmbCampoFecha = $cmbTipoInsercion == 0 ? -1 : $_POST['cmbCampoFecha'];
        }

        // Campo Hora
        $cmbCampoHora = 0;
        if(isset($_POST['cmbCampoHora']) && $_POST['cmbCampoHora'] != ''){
            $cmbCampoHora = $cmbTipoInsercion == 0 ? -1 : $_POST['cmbCampoHora'];
        }
        $masMenosFecha = 0;
        if(isset($_POST['masMenosFecha']) &&  $_POST['masMenosFecha'] != ''){
            $masMenosFecha = $_POST['masMenosFecha'];
        }

        $masMenosHora = 0;
        if(isset($_POST['masMenosHora']) &&  $_POST['masMenosHora'] != ''){
            $masMenosHora = $_POST['masMenosHora'];
        }

        $cmbCambioEstado = 0;
        if(isset($_POST['cmbCambioEstado']) &&  $_POST['cmbCambioEstado'] != ''){
            $cmbCambioEstado = $_POST['cmbCambioEstado'];
        }

        $sacarPasoAnterior = 0;
        if(isset($_POST['sacarPasoAnterior']) &&  $_POST['sacarPasoAnterior'] != ''){
            $sacarPasoAnterior = -1;
        }

        $resucitarRegistros = 0;
        if(isset($_POST['resucitarRegistros']) &&  $_POST['resucitarRegistros'] != ''){
            $resucitarRegistros = 1;
        }

        $inactivarRegistros = 0;
        if(isset($_POST['inactivarRegistros']) &&  $_POST['inactivarRegistros'] != ''){
            $inactivarRegistros = -1;
        }

        // Aqui guardo el check de activo
        $estconActivo = 0;
        if(isset($_POST['estconActivo']) &&  $_POST['estconActivo'] != ''){
            $estconActivo = -1;
        }

        $tipoAsignacion = 1;
        $campoAgenteAsignacion = 0;

        if(isset($_POST['tipoAsignacion'])){
            
            $tipoAsignacion = $_POST['tipoAsignacion'];

            // Valido que la asignacion sea predefinida
            if($tipoAsignacion == 2){
                if(isset($_POST['campoAgente']) && $_POST['campoAgente'] != 0){
                    $campoAgenteAsignacion = $_POST['campoAgente'];
                }
            }
            "ESTCON_Tipo_Asignacion_b = ".$tipoAsignacion.", ESTCON_Campo_Agente_Asignacion_b = ".$campoAgenteAsignacion;
        }

        // Guardamos la informacion de Sacar registros de otros pasos
        $estconSacarOtrosPasos = 0;
        if(isset($_POST['sacarOtrosPasos']) &&  $_POST['sacarOtrosPasos'] != ''){
            $estconSacarOtrosPasos = -1;
        }

        //VALIDAR SI HAY QUE HEREDAR LA TIPIFICACIÓN, AGENDA Y COMENTARIOS
        $estconHeredarAgenda=isset($_POST['heredarAgendas']) ? -1 : 0;
        

        $stringPasos = '';
        if(isset($_POST['pasosSacar'])){
            $pasos = $_POST['pasosSacar'];
            
            for ($i=0; $i < count($pasos); $i++) { 
                $con = ',';
                if($i == 0){
                    $con = '';
                }
                $stringPasos .= $con.$pasos[$i];
            }
        }

        $Lsql = "UPDATE ".$BaseDatos_systema.".ESTCON SET ESTCON_Consulta_sql_b = '".$Lsql_Insercion."' , ESTCON_Tipo_Consulta_b = ".$_POST['radiocondiciones']." , ESTCON_Tipo_Insercion_b = ".$cmbTipoInsercion." , ESTCON_ConsInte_PREGUN_Fecha_b = ".$cmbCampoFecha." , ESTCON_ConsInte_PREGUN_Hora_b = ".$cmbCampoHora." , ESTCON_Operacion_Fecha_b = '".$masMenosFecha."' , ESTCON_Operacion_Hora_b = '".$masMenosHora."' , ESTCON_Cantidad_Fecha_b = '".$FechaNum."' , ESTCON_Cantidad_Hora_b = '".$HoraNum."', ESTCON_Estado_cambio_b = '".$cmbCambioEstado."', ESTCON_Sacar_paso_anterior_b = '".$sacarPasoAnterior."', ESTCON_resucitar_registro = '".$resucitarRegistros."', ESTCON_Activo_b = '".$estconActivo."', ESTCON_Tipo_Asignacion_b = ".$tipoAsignacion.", ESTCON_Campo_Agente_Asignacion_b = ".$campoAgenteAsignacion.", ESTCON_Inactivar_Registros_b = ".$inactivarRegistros.", ESTCON_Sacar_Otros_Pasos_b = ".$estconSacarOtrosPasos.", ESTCON_Sacar_Otros_Pasos_Ids_b = '".$stringPasos."', ESTCON_Hereda_MONOEF_b={$estconHeredarAgenda} WHERE ESTCON_ConsInte__ESTPAS_Des_b = ".$_POST['from']." AND ESTCON_ConsInte__ESTPAS_Has_b = ".$_POST['to'];

       // echo $Lsql;
        if($mysqli->query($Lsql) === true){
            
            // Si $estconActivo es igual a 0 seteo ESTCON_Consulta_sql_b a null
            if($estconActivo === 0){
                $sql = "UPDATE {$BaseDatos_systema}.ESTCON SET ESTCON_Consulta_sql_b = NULL WHERE ESTCON_ConsInte__ESTPAS_Des_b = ".$_POST['from']." AND ESTCON_ConsInte__ESTPAS_Has_b = ".$_POST['to'];
                $mysqli->query($sql);
            }

            echo "1";
        }else{
            echo "ERROR INSERTANDO LA CONSULTA => ".$mysqli->error;
        }
    }

    if(isset($_GET['validarPasosCondiciones'])){
        $IdConLsql = "SELECT ESTCON_ConsInte__b, ESTCON_Tipo_Consulta_b FROM ".$BaseDatos_systema.".ESTCON WHERE ESTCON_ConsInte__ESTPAS_Des_b = ".$_POST['pasoFrom']." AND ESTCON_ConsInte__ESTPAS_Has_b = ".$_POST['pasoTo'];
        $res= $mysqli->query($IdConLsql);
        $datoIs = $res->fetch_array();

        $Lsql = "SELECT * FROM ".$BaseDatos_systema.".ESTCON_CONDICIONES JOIN  ".$BaseDatos_systema.".ESTCON ON id_estcon = ESTCON_ConsInte__b WHERE id_estcon = ".$datoIs['ESTCON_ConsInte__b'];
        $res = $mysqli->query($Lsql);
        $datos  = array();
        if($res){
            while ($key = $res->fetch_object()) {
                $datos[] = $key;
            }

            echo json_encode($datos);
        }else{
            echo "0";
        }
    }

    if(isset($_GET['getInserciones'])){
        $IdConLsql = "SELECT ESTCON_Tipo_Insercion_b,  ESTCON_ConsInte_PREGUN_Fecha_b, ESTCON_ConsInte_PREGUN_Hora_b, ESTCON_Operacion_Fecha_b, ESTCON_Operacion_Hora_b, ESTCON_Cantidad_Fecha_b, ESTCON_Cantidad_Hora_b, ESTCON_Estado_cambio_b, ESTCON_Sacar_paso_anterior_b, ESTCON_resucitar_registro, ESTCON_Tipo_Consulta_b, ESTCON_Activo_b, ESTCON_Tipo_Asignacion_b, ESTCON_Campo_Agente_Asignacion_b, ESTCON_Inactivar_Registros_b, ESTCON_Sacar_Otros_Pasos_b, ESTCON_Sacar_Otros_Pasos_Ids_b, ESTCON_Hereda_MONOEF_b FROM ".$BaseDatos_systema.".ESTCON WHERE ESTCON_ConsInte__ESTPAS_Des_b = ".$_POST['pasoFrom']." AND ESTCON_ConsInte__ESTPAS_Has_b = ".$_POST['pasoTo'];
        $res= $mysqli->query($IdConLsql);
        $datos  = array();
        if($res){
            while ($key = $res->fetch_object()) {
                $datos[] = $key;
            }

            echo json_encode($datos);
        }else{
            echo "0";
        }
    }

    // Obtengo los pasos que se pueden excluir en una flecha
    if(isset($_GET['getPasosExcluir'])){
        $from = $_POST['pasoFrom'];
        $to = $_POST['pasoTo'];

        // Primero me toca traer la estrategia basado en el paso from
        $sql = "SELECT ESTPAS_ConsInte__b AS id, ESTPAS_ConsInte__ESTRAT_b AS estrat_id FROM {$BaseDatos_systema}.ESTPAS WHERE ESTPAS_ConsInte__b = {$from} LIMIT 1";
        $res = $mysqli->query($sql);
        $pasoFrom = $res->fetch_object();

        // Ya teniendo el id de la estrategia traigo todos los pasos que pertenecen a esa estrategia
        $sql = "SELECT ESTPAS_ConsInte__b AS id, ESTPAS_Comentari_b AS nombre FROM {$BaseDatos_systema}.ESTPAS 
            WHERE ESTPAS_ConsInte__ESTRAT_b = {$pasoFrom->estrat_id} AND (ESTPAS_ConsInte__b != {$from} AND ESTPAS_ConsInte__b != {$to}) AND ESTPAS_Tipo______b IN (1, 6, 7, 8, 9, 13)";

        $resPasos = $mysqli->query($sql);

        $pasos = [];

        if($resPasos && $resPasos->num_rows > 0){
            while($row = $resPasos->fetch_object()){
                $pasos[] = $row;
            }
        }

        echo json_encode(['pasos' => $pasos]);
    }

    if(isset($_POST['getMetas'])){
        if(isset($_POST['idioma'])){
            switch ($_POST['idioma']) {
                case 'en':
                     include(__DIR__."../../../../idiomas/text_en.php");
                    break;

                case 'es':
                    include(__DIR__."../../../../idiomas/text_es.php");
                    break;

                default:
                    include(__DIR__."../../../../idiomas/text_en.php");
                    break;
            }
        }else{
            include(__DIR__."../../../../idiomas/text_en.php");
        }
        
        $Lsql = "SELECT METDEF_Consinte__b, METDEF_Nombre___b FROM ".$BaseDatos_systema.".METDEF WHERE md5(concat('".clave_get."', METDEF_Consinte__ESTRAT_b)) = '".$_POST['id']."'";
        $opciones1 = "<option value='0'></option>";
        $opciones2 = "<option value='0'></option>";
        $opciones3 = "<option value='0'></option>";
        $opciones4 = "<option value='0'></option>";
        $LsqlX = "SELECT ESTRAT_Meta1____b, ESTRAT_Meta2____b, ESTRAT_Meta3____b, ESTRAT_Meta4____b FROM ".$BaseDatos_systema.".ESTRAT WHERE md5(concat('".clave_get."', ESTRAT_ConsInte__b)) = '".$_POST['id']."'";
        $resX = $mysqli->query($LsqlX);
        $datosX = $resX->fetch_array();

        $res = $mysqli->query($Lsql);
        while ($key = $res->fetch_object()) {
            if($key->METDEF_Consinte__b == $datosX['ESTRAT_Meta1____b']){
                $opciones1 .= "<option value='".$key->METDEF_Consinte__b."' selected>".$key->METDEF_Nombre___b."</option>";
            }else{
                $opciones1 .= "<option value='".$key->METDEF_Consinte__b."'>".$key->METDEF_Nombre___b."</option>";
            }

            if($key->METDEF_Consinte__b == $datosX['ESTRAT_Meta2____b']){
                $opciones2 .= "<option value='".$key->METDEF_Consinte__b."' selected>".$key->METDEF_Nombre___b."</option>";
            }else{
                $opciones2 .= "<option value='".$key->METDEF_Consinte__b."'>".$key->METDEF_Nombre___b."</option>";
            }

            if($key->METDEF_Consinte__b == $datosX['ESTRAT_Meta3____b']){
                $opciones3 .= "<option value='".$key->METDEF_Consinte__b."' selected>".$key->METDEF_Nombre___b."</option>";
            }else{
                $opciones3 .= "<option value='".$key->METDEF_Consinte__b."'>".$key->METDEF_Nombre___b."</option>";
            }


            if($key->METDEF_Consinte__b == $datosX['ESTRAT_Meta4____b']){
                $opciones4 .= "<option value='".$key->METDEF_Consinte__b."' selected>".$key->METDEF_Nombre___b."</option>";
            }else{
                $opciones4 .= "<option value='".$key->METDEF_Consinte__b."'>".$key->METDEF_Nombre___b."</option>";
            }
        }
        echo '<div class="row" style="text-align:center;">
            <div class="col-md-3 col-xs-3">
                <div class="form-group">
                    <label for="G2_C10" id="LblG2_C10">'.$str_Meta_nombre.'</label>
                </div>
            </div>
            <div class="col-md-2 col-xs-2">
                <div class="form-group">
                    <label for="G2_C10" id="LblG2_C10">'.$str_Meta_paso.'</label>
                </div>
            </div>
            <div class="col-md-2 col-xs-2">
                <div class="form-group">
                    <label for="G2_C10" id="LblG2_C10">'.$str_Meta_nivel.'</label>
                </div>
            </div>
            <div class="col-md-2 col-xs-2">
                <div class="form-group">
                    <label for="G2_C10" id="LblG2_C10">'.$str_Meta_tipo.'</label>
                </div>
            </div>
            <div class="col-md-2 col-xs-2">
                <div class="form-group">
                    <label for="G2_C10" id="LblG2_C10">'.$str_Meta_subTipo.'</label>
                </div>
            </div>
            
        </div>';

        $XLsql = "SELECT ESTPAS_ConsInte__b, ESTPAS_Nombre__b, ESTPAS_Comentari_b FROM ".$BaseDatos_systema.".ESTPAS WHERE (ESTPAS_Tipo______b = 1 OR ESTPAS_Tipo______b = 6) AND md5(concat('".clave_get."', ESTPAS_ConsInte__ESTRAT_b)) = '".$_POST['id']."'";
        $resX = $mysqli->query($XLsql);
        
        $arayPasos = array();
        $i = 0;
        while($jey = $resX->fetch_object()){
            $arrayPasos[$i]['ESTPAS_ConsInte__b']   = $jey->ESTPAS_ConsInte__b;
            if($jey->ESTPAS_Comentari_b != NULL && $jey->ESTPAS_Comentari_b != ''){
                $arrayPasos[$i]['ESTPAS_Nombre__b']     = $jey->ESTPAS_Comentari_b;
            }else{
                $arrayPasos[$i]['ESTPAS_Nombre__b']     = $jey->ESTPAS_Nombre__b;
            }
           
            $i++;
        }

        $Lsql = "SELECT * FROM ".$BaseDatos_systema.".METDEF WHERE md5(concat('".clave_get."', METDEF_Consinte__ESTRAT_b)) = '".$_POST['id']."'";
        $res = $mysqli->query($Lsql);
        $i = 0;
        echo "<div id='EstrategiaMetas'>";
        while ($jey = $res->fetch_object()) {
            $opcionPaso = '<option value="0">'.$str_seleccione.'</option>';
            for($j= 0; $j < count($arrayPasos); $j++) {
                if($arrayPasos[$j]['ESTPAS_ConsInte__b'] == $jey->METDEF_Consinte__ESTPAS_b){
                    $opcionPaso .= '<option selected value="'.$arrayPasos[$j]['ESTPAS_ConsInte__b'].'">'.$arrayPasos[$j]['ESTPAS_Nombre__b'].'</option>';
                }else{
                    $opcionPaso .= '<option value="'.$arrayPasos[$j]['ESTPAS_ConsInte__b'].'">'.$arrayPasos[$j]['ESTPAS_Nombre__b'].'</option>';
                }
            }

            $opcionNivel = '';
            if($jey->METDEF_Nivel_____b == 1){
               $opcionNivel = '<option value="1" selected>'.$str_Meta_nivel2.'</option>
                            <option value="2">'.$str_Meta_nivel3.'</option>'; 
            }else if($jey->METDEF_Nivel_____b == 2){
                $opcionNivel = '<option value="1">'.$str_Meta_nivel2.'</option>
                            <option value="2" selected>'.$str_Meta_nivel3.'</option>';
            }else{
                $opcionNivel = '<option value="1">'.$str_Meta_nivel2.'</option>
                            <option value="2">'.$str_Meta_nivel3.'</option>';
            }

            $opcionTipo = '<option value="0">'.$str_seleccione.'</option>';
            if($jey->METDEF_Tipo______b == 1){
                $opcionTipo .= '<option value="1" selected>'.$str_Meta_tipo1.'</option>
                            <option value="2">'.$str_Meta_tipo2.'</option>
                            <option value="3">'.$str_Meta_tipo3.'</option>
                            <option value="4">'.$str_Meta_tipo4.'</option>
                            <option value="5">'.$str_Meta_tipo5.'</option>';
            }else if($jey->METDEF_Tipo______b == 2){
                $opcionTipo .= '<option value="1">'.$str_Meta_tipo1.'</option>
                            <option value="2" selected>'.$str_Meta_tipo2.'</option>
                            <option value="3">'.$str_Meta_tipo3.'</option>
                            <option value="4">'.$str_Meta_tipo4.'</option>
                            <option value="5">'.$str_Meta_tipo5.'</option>';
            }else if($jey->METDEF_Tipo______b == 3){
                $opcionTipo .= '<option value="1">'.$str_Meta_tipo1.'</option>
                            <option value="2">'.$str_Meta_tipo2.'</option>
                            <option value="3" selected>'.$str_Meta_tipo3.'</option>
                            <option value="4">'.$str_Meta_tipo4.'</option>
                            <option value="5">'.$str_Meta_tipo5.'</option>';
            }else if($jey->METDEF_Tipo______b == 4){
                $opcionTipo .= '<option value="1">'.$str_Meta_tipo1.'</option>
                            <option value="2">'.$str_Meta_tipo2.'</option>
                            <option value="3">'.$str_Meta_tipo3.'</option>
                            <option value="4" selected>'.$str_Meta_tipo4.'</option>
                            <option value="5">'.$str_Meta_tipo5.'</option>';
            }else if($jey->METDEF_Tipo______b == 5){
                $opcionTipo .= '<option value="1">'.$str_Meta_tipo1.'</option>
                            <option value="2">'.$str_Meta_tipo2.'</option>
                            <option value="3">'.$str_Meta_tipo3.'</option>
                            <option value="4">'.$str_Meta_tipo4.'</option>
                            <option value="5" selected>'.$str_Meta_tipo5.'</option>';
            }

            $opcionSubtipo = '<option value="0">'.$str_seleccione.'</option>';
            if($jey->METDEF_SubTipo___b == 1){
                $opcionSubtipo .= ' <option value="1" selected>'.$str_Meta_subTipo1.'</option>
                            <option value="2">'.$str_Meta_subTipo2.'</option>
                            <option value="3">'.$str_Meta_subTipo3.'</option>';
            }else if($jey->METDEF_SubTipo___b == 2){
                $opcionSubtipo .= ' <option value="1">'.$str_Meta_subTipo1.'</option>
                            <option value="2" selected>'.$str_Meta_subTipo2.'</option>
                            <option value="3">'.$str_Meta_subTipo3.'</option>';
            }else if($jey->METDEF_SubTipo___b == 3){
                $opcionSubtipo .= ' <option value="1">'.$str_Meta_subTipo1.'</option>
                            <option value="2">'.$str_Meta_subTipo2.'</option>
                            <option value="3" selected>'.$str_Meta_subTipo3.'</option>';
            }

            echo '<div class="row" id="EstaMetas_'.$i.'">
                <input type="hidden" name="metdef_id_'.$i.' id="metdef_id_'.$i.'" value="'.$jey->METDEF_Consinte__b.'">
                <div class="col-md-3 col-xs-3">
                    <div class="form-group">
                        <input type="text" name="txtNombreMeta_'.$i.'" id="txtNombreMeta_'.$i.'" class="form-control" disabled placeholder="'.$str_Meta_nombre.'" value="'.$jey->METDEF_Nombre___b.'">
                    </div>
                </div>
                <div class="col-md-2 col-xs-2">
                    <div class="form-group">
                        <select class="form-control" id="cmbPasos_'.$i.'" name="cmbPasos_'.$i.'" disabled>
                            '.$opcionPaso.'
                        </select>
                    </div>
                </div>
                <div class="col-md-2 col-xs-2">
                    <div class="form-group">
                        <select class="form-control" id="cmbNivel_'.$i.'" name="cmbNivel_'.$i.'" disabled>
                            '.$opcionNivel.'
                        </select>
                    </div>
                </div>
                <div class="col-md-2 col-xs-2">
                    <div class="form-group">
                        <select class="form-control" id="cmbTipo_'.$i.'" name="cmbTipo_'.$i.'" disabled>
                            '.$opcionTipo.'
                        </select>
                    </div>
                </div>
                <div class="col-md-2 col-xs-2">
                    <div class="form-group">
                        <select class="form-control" id="cmbSubTipo_'.$i.'" name="cmbSubTipo_'.$i.'" disabled>
                            '.$opcionSubtipo.'
                        </select>
                    </div>
                </div>
                <div class="col-md-1 col-xs-1">
                    <div class="form-group">
                        <button type="button" class="btn btn-sm btn-danger borrarEsto" id="quitarEsto_'.$i.'" valueI="'.$i.'" metaId="'.$jey->METDEF_Consinte__b.'"  title="Quitar Meta" onclick="javascript: borrarMeta(this);"><i class="fa fa-trash-o"></i></button>
                        </div>
                    </div>
                </div>
            </div>';
            $i++;
        }
        echo "<input type='hidden' name='contadorMetasViejas' id='contadorMetasViejas' value='".$i."'>";
        echo "<input type='hidden' name='contadorMetasNuevas' id='contadorMetasNuevas' value='0'>";
        echo "</div>";
       
        echo "<button class='btn btn-primary pull-right' id='AgergarMetasEstrat' onclick='javascript: agregarMetasEstrat();' type='button'>Agregar Meta</button><br/>";

        echo '<hr/><div class="row" >
        <div class="col-md-3 col-xs-3">
            <!-- CAMPO DE TIPO GUION -->
            <div class="form-group">
                <label for="G2_C10" id="LblG2_C10">'.$str_strategia_meta3.'</label>
                <select class="form-control input-sm select2" style="width: 100%;" disabled name="G2_C10" id="G2_C10">
                    '.$opciones1.'
                </select>
            </div>
            <!-- FIN DEL CAMPO TIPO LISTA -->
        </div>
        <div class="col-md-3 col-xs-3">
            <!-- CAMPO DE TIPO GUION -->
            <div class="form-group">
                <label for="G2_C11" id="LblG2_C11">'.$str_strategia_meta4.'</label>
                <select class="form-control input-sm select2" style="width: 100%;" disabled name="G2_C11" id="G2_C11">
                    '.$opciones2.'
                </select>
            </div>
        </div>
        <!-- FIN DEL CAMPO TIPO LISTA -->
        <div class="col-md-3 col-xs-3">
            <!-- CAMPO DE TIPO GUION -->
            <div class="form-group">
                <label for="G2_C12" id="LblG2_C12">'.$str_strategia_meta4.'</label>
                <select class="form-control input-sm select2" style="width: 100%;" disabled name="G2_C12" id="G2_C12">
                    '.$opciones3.'
                </select>
            </div>
            <!-- FIN DEL CAMPO TIPO LISTA -->
        </div>
        <div class="col-md-3 col-xs-3">
            <!-- CAMPO DE TIPO GUION -->
            <div class="form-group">
                <label for="G2_C13" id="LblG2_C13">'.$str_strategia_meta5.'</label>
                <select class="form-control input-sm select2" style="width: 100%;" disabled  name="G2_C13" id="G2_C13">
                    '.$opciones4.'
                </select>
            </div>
            <!-- FIN DEL CAMPO TIPO LISTA -->
        </div>
        </div>';
        
    }

    if(isset($_POST['deleteAllEstrat'])){

        $intIdEstrat_t = $_POST['id'];

        $strSQLEstpas_t = "SELECT ESTPAS_ConsInte__b AS id FROM ".$BaseDatos_systema.".ESTPAS WHERE md5(concat('".clave_get."', ESTPAS_ConsInte__ESTRAT_b)) = '".$intIdEstrat_t."'";

        $resSQLEstpas_t = $mysqli->query($strSQLEstpas_t);

        while ($obj = $resSQLEstpas_t->fetch_object()) {

            EliminarCampañaEnCascada($obj->id);

        }

        $strSQLDelEstrat_t = "DELETE FROM ".$BaseDatos_systema.".ESTRAT WHERE md5(concat('".clave_get."', ESTRAT_ConsInte__b)) = '".$intIdEstrat_t."'";
        $mysqli->query($strSQLDelEstrat_t);

        echo "1";
    }

    if(isset($_POST['getPasos'])){
        if(isset($_POST['idioma'])){
            switch ($_POST['idioma']) {
                case 'en':
                    include(__DIR__."../../../../idiomas/text_en.php");
                    break;

                case 'es':
                    include(__DIR__."../../../../idiomas/text_es.php");
                    break;

                default:
                    include(__DIR__."../../../../idiomas/text_en.php");
                    break;
            }
        }
        $XLsql = "SELECT ESTPAS_ConsInte__b, ESTPAS_Nombre__b FROM ".$BaseDatos_systema.".ESTPAS WHERE (ESTPAS_Tipo______b = 1 OR ESTPAS_Tipo______b = 6) AND md5(concat('".clave_get."', ESTPAS_ConsInte__ESTRAT_b)) = '".$_POST['id']."'";
        $resX = $mysqli->query($XLsql);
        
        $arayPasos = array();
        $i = 0;
        echo '<option value="0">'.$str_seleccione.'</option>';
        while($jey = $resX->fetch_object()){
            echo '<option selected value="'.$jey->ESTPAS_ConsInte__b.'">'.$jey->ESTPAS_Nombre__b.'</option>';
        }
        
    }

    if(isset($_POST['getReportes'])){

        if(isset($_POST['idioma'])){
            switch ($_POST['idioma']) {
                case 'en':
                    include(__DIR__."../../../../idiomas/text_en.php");
                    break;

                case 'es':
                    include(__DIR__."../../../../idiomas/text_es.php");
                    break;

                default:
                    include(__DIR__."../../../../idiomas/text_en.php");
                    break;
            }
        }
        

        $reportesLsql = "SELECT id, destinatarios, destinatarios_cc, destinatarios_cco, momento_envio, asunto, tipo_periodicidad, nombres_hojas FROM ".$BaseDatos_general.".reportes_automatizados WHERE md5(concat('".clave_get."', id_estrategia)) = '".$_POST['id_estrategia']."'";

        /* Toca hacer update a los reportes */
        $datoRepo = 0;
        $res3 = $mysqli->query($reportesLsql);

        if($res3){
            $datoRepo = $res3->num_rows;
            if(is_null($datoRepo)){
                $datoRepo = 0;
            }
        }
                                                            
        if( $datoRepo > 0){  
            $i = 0; 
            echo '<input type="hidden" value="Gen" name="txtAsuntosGuardados" id="txtAsuntosGuardados">';
            while ($resPond = $res3->fetch_object()) { ?>
                                                       
                <div class="row" id="<?php echo $resPond->id; ?>">
                    <input type="hidden" value="<?php echo $resPond->id; ?>" disabled  name="txtAsuntosGuardados_<?php echo $i;?>" id="txtAsuntosGuardados_<?php echo $i;?>">
                    <div class="col-md-3">
                        <div class="form-group">
                            <label><?php echo $repAsunto______;?></label>
                            <input type="text" class="form-control" name="txtNombreReporte_<?php echo $i;?>" id="txtNombreReporte_<?php echo $i;?>" placeholder="<?php echo $repAsunto______;?>"  disabled  value='<?php if($datoRepo != 0){ echo $resPond->asunto; } ?>'>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label><?php echo $repdirijidoa___;?></label>
                            <input type="email" class="form-control" name="txtAquienVa_<?php echo $i;?>" id="txtAquienVa_<?php echo $i;?>" placeholder="<?php echo $repdirijidoa___;?>" disabled  value='<?php if($datoRepo != 0){ echo $resPond->destinatarios; } ?>'>
                            <span class="help-block"></span>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label><?php echo $repcopia_______; ?></label>
                            <input type="email" class="form-control" name="txtCopiaA_<?php echo $i;?>" id="txtCopiaA_<?php echo $i;?>" placeholder="<?php echo $repcopia_______;?>" disabled  value='<?php if($datoRepo != 0){ echo $resPond->destinatarios_cc; } ?>'>
                            <span class="help-block"></span>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label><?php echo $rephorasdeenvio; ?></label>
                            <input type="text" class="form-control horaEnvioTxt ui-timepicker-input" name="txtHoraEnvio_<?php echo $i;?>" id="txtHoraEnvio_<?php echo $i;?>" placeholder="<?php echo $rephorasdeenvio; ?>"  disabled  value='<?php if($datoRepo != 0){ echo $resPond->momento_envio; } ?>'>
                        </div>
                    </div>
                    <div class="col-md-2 col-xs-10">
                        <div class="form-group">
                            <label><?php echo $campan_periodo_; ?></label>
                            <select class="form-control" onchange="HoraPre(this.value,'',<?php echo $i; ?>)"  disabled name="cmbPeriodicidad_<?php echo $i;?>">
                                <?php  if (strpos($resPond->nombres_hojas, 'PausasConHorarioMuyLargas') == true) { ?>
                                     <option value="4" <?php if($resPond->tipo_periodicidad == 1){ echo "selected"; } ?>>DIARIO ADHERENCIAS</option>
                                <?php }else{ ?>
                                    <option value="1" <?php if($resPond->tipo_periodicidad == 1){ echo "selected"; } ?>><?php echo $campan_diario__;?></option>
                                    <option value="2" <?php if($resPond->tipo_periodicidad == 2){ echo "selected"; } ?>><?php echo $campan_semanal_;?></option>
                                    <option value="3" <?php if($resPond->tipo_periodicidad == 3){ echo "selected"; } ?>><?php echo $campan_mensual_;?></option>
                                   <?php } ?>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-1 col-xs-1">
                        <br>
                        <button disabled type="button" class="btn btn-sm btn-danger deleteCorreoF" aborrar="<?php echo $resPond->id;?>">
                            <i class="fa fa-trash"></i>
                        </button>
                    </div>
                </div>
        <?php 
                $i++;   
            } 
            echo "<input type='hidden' name='totalGuardadosAsuntos' value='".$i."'>"
        ?>
        <?php }else{  } ?>

            
            <div id="horaEnvio">
                
            </div>
    <?php
    }


    if(isset($_POST['deleteEnvioCorreo'])){
        if($_POST['idEnvioCorreo'] != '' && $_POST['idEnvioCorreo'] != 0){
            $Lsql_InsercionRepor = "DELETE FROM ".$BaseDatos_general.".reportes_automatizados WHERE id = ".$_POST['idEnvioCorreo'];
            if($mysqli->query($Lsql_InsercionRepor) === true ){
                echo '1';
            }else{
                echo $mysqli->error;
            }
        }
    }

    if(isset($_POST['deleteMetDef'])){
        $Lsql = "DELETE FROM ".$BaseDatos_systema.".METDEF WHERE METDEF_Consinte__b = ".$_POST['deleteMetDef'];
        if($mysqli->query($Lsql) === true){
            echo 'ok';
        } else  {
            echo '0';
        }
    }

    /** Aqui traigo todo de LISOPC pertenecientes a PREGUN para los tareas_backoffice */
    if(isset($_GET['getLisopc'])){
        $id = isset($_POST['id'])? $_POST['id'] : null;

        $Lsql = "SELECT * FROM ".$BaseDatos_systema.".LISOPC WHERE LISOPC_ConsInte__OPCION_b = (SELECT PREGUN_ConsInte__OPCION_B FROM ".$BaseDatos_systema.".PREGUN WHERE PREGUN_ConsInte__b = ".$id.")";
        $respLISOPC = $mysqli->query($Lsql);

        $optionHtml = '<option value="">Seleccionar</option>';
        if($respLISOPC){
            while ($item = $respLISOPC->fetch_object()){
                $optionHtml .= '<option value="'.$item->LISOPC_ConsInte__b.'">'.$item->LISOPC_Nombre____b.'</option>';
            }
        }

        echo json_encode(['lisopcOption'=> $optionHtml]);
    }

    /**Verifico si la tarea de backoffice es nueva o ya ha sido creada */
    if(isset($_GET['verificarTareasBackoffice'])){
        
        $guion = isset($_POST['guion'])? $_POST['guion'] : null;
        $key = isset($_POST['key'])? $_POST['key'] : null;

        $sql = "SELECT A.*, B.ESTPAS_activo FROM ".$BaseDatos_systema.".TAREAS_BACKOFFICE A LEFT JOIN ".$BaseDatos_systema.".ESTPAS B ON A.TAREAS_BACKOFFICE_ConsInte__ESTPAS_b = B.ESTPAS_ConsInte__b WHERE TAREAS_BACKOFFICE_ConsInte__ESTPAS_b = ".$key;
        $respuesta = $mysqli->query($sql);

        if(mysqli_num_rows($respuesta) == 0){
            // Si no se obtiene ningun registro solo retorno la accion
            echo json_encode(['accion' => 'nuevo', 'nombrepaso' => "TAREA_BACKOFFICE_{$key}"]);

        }else {
            
            // Guardo el registro de TAREAS_BACKOFFICE en un array
            $arreglo = array();
            while($fila = $respuesta->fetch_object()) {
                $arreglo[] = $fila;
            }

            // Obtengo todos los campos de PREGUN de la base de datos enviada
            $sqlPregun = "SELECT * FROM ".$BaseDatos_systema.".PREGUN WHERE PREGUN_ConsInte__GUION__b = ".$guion." AND PREGUN_Tipo______b = 6";
            $respPregun = $mysqli->query($sqlPregun);

            $optionHtml = '<option value="">Seleccionar</option>';
            while ($item = $respPregun->fetch_object()){
                $optionHtml .= '<option value="'.$item->PREGUN_ConsInte__b.'">'.$item->PREGUN_Texto_____b.'</option>';
            }

            // Obtengo las opciones de lisopc
            $optionHtmlLisopc = '<option value="">Seleccionar</option>';
            
            if($arreglo[0]->TAREAS_BACKOFFICE_ConsInte__PREGUN_estado_b){
                $Lsql = "SELECT * FROM ".$BaseDatos_systema.".LISOPC WHERE LISOPC_ConsInte__OPCION_b = (SELECT PREGUN_ConsInte__OPCION_B FROM ".$BaseDatos_systema.".PREGUN WHERE PREGUN_ConsInte__b = ".$arreglo[0]->TAREAS_BACKOFFICE_ConsInte__PREGUN_estado_b.")";
                $respLISOPC = $mysqli->query($Lsql);

                if($respLISOPC){
                    while ($item = $respLISOPC->fetch_object()){
                        $optionHtmlLisopc .= '<option value="'.$item->LISOPC_ConsInte__b.'">'.$item->LISOPC_Nombre____b.'</option>';
                    }
                }
            }
            
            /**Obtengo una lista de usuarios pertenecientes al huesped que no han sido asignados a asitar_backoffice */
            $Usql = "SELECT USUARI_ConsInte__b as id, USUARI_Nombre____b as camp1 , USUARI_Correo___b as camp2, USUARI_UsuaCBX___b as camp3  
                    FROM ".$BaseDatos_systema.".USUARI 
                    JOIN ".$BaseDatos_general.".huespedes_usuarios ON id_usuario = USUARI_UsuaCBX___b  
                    LEFT JOIN 
                    (SELECT ASITAR_BACKOFFICE_ConsInte__b, ASITAR_BACKOFFICE_ConsInte__USUARI_b, ASITAR_BACKOFFICE_UsuarioCBX_b FROM ".$BaseDatos_systema.".ASITAR_BACKOFFICE WHERE ASITAR_BACKOFFICE_ConsInte__TAREAS_BACKOFFICE_b = ".$arreglo[0]->TAREAS_BACKOFFICE_ConsInte__b.") AS C
                    ON USUARI_ConsInte__b = C.ASITAR_BACKOFFICE_ConsInte__USUARI_b
                    WHERE id_huesped = ".$_SESSION['HUESPED']." AND USUARI_ConsInte__PERUSU_b IS NULL AND ASITAR_BACKOFFICE_ConsInte__b IS NULL ORDER BY USUARI_Nombre____b ASC ";

            $respUsu = $mysqli->query($Usql);

            $listaUsu = "";
            if($respUsu){
                while ($item = $respUsu->fetch_object()){
                    $listaUsu .= '<li data-id="'.$item->id.'" data-camp3="'.$item->camp3.'"><table class="table table-hover"><tr><td width="40px"><input type="checkbox" class="flat-red mi-check"></td><td class="nombre">'.$item->camp1.' - '.$item->camp2.'</td></tr></table></li>';
                    
                }
            }

            // Obtengo una lista de usuarios pertenecientes al huesped que ya han sido asignados a asitar_backoffice 
            $Asql = "SELECT USUARI_ConsInte__b as id, USUARI_Nombre____b as camp1 , USUARI_Correo___b as camp2, USUARI_UsuaCBX___b as camp3 
                    FROM ".$BaseDatos_systema.".USUARI 
                    JOIN ".$BaseDatos_general.".huespedes_usuarios ON id_usuario = USUARI_UsuaCBX___b  
                    JOIN ".$BaseDatos_systema.".ASITAR_BACKOFFICE ON USUARI_ConsInte__b = ASITAR_BACKOFFICE_ConsInte__USUARI_b
                    WHERE id_huesped = ".$_SESSION['HUESPED']." AND ASITAR_BACKOFFICE_ConsInte__TAREAS_BACKOFFICE_b = ".$arreglo[0]->TAREAS_BACKOFFICE_ConsInte__b." AND USUARI_ConsInte__PERUSU_b IS NULL ORDER BY USUARI_Nombre____b ASC ";

            $respUsuA = $mysqli->query($Asql);

            $listaUsuA = "";
            if($respUsuA){
                while ($item = $respUsuA->fetch_object()){
                    $listaUsuA .= '<li data-id="'.$item->id.'" data-camp3="'.$item->camp3.'"><table class="table table-hover"><tr><td width="40px"><input type="checkbox" class="flat-red mi-check"></td><td class="nombre">'.$item->camp1.' - '.$item->camp2.'</td></tr></table></li>';
                }
            }
            
            echo json_encode([
                'accion'=> 'editar', 
                'respuesta'=> $arreglo, 
                'pregunOption'=> $optionHtml, 
                'lisopcOption'=> $optionHtmlLisopc,
                'listaUsu' => $listaUsu,
                'listaUsuA' => $listaUsuA
            ]);

        }
    }

    // Aqui creo un registro cuando es un nuevo caso de backoffice
    if(isset($_GET['guardarCasoBackoffice']) && isset($_POST['nuevoCasoBackoffice'])){

        $nombre = mysqli_real_escape_string($mysqli, $_POST['nombreCaso']);
        $guion = $_POST['idGuion'];
        $estpas = $_POST['idEstpas'];
        
        $LsqlUpdate = "UPDATE ".$BaseDatos_systema.".ESTPAS SET ESTPAS_Comentari_b = '".$nombre."' WHERE ESTPAS_ConsInte__b= ".$estpas;

        $LsqlInsert = "INSERT INTO ".$BaseDatos_systema.".TAREAS_BACKOFFICE (TAREAS_BACKOFFICE_ConsInte__ESTPAS_b,TAREAS_BACKOFFICE_Nombre_b,TAREAS_BACKOFFICE_ConsInte__GUION_b) VALUES (".$estpas.",'".$nombre."', ".$guion.")";

        if($mysqli->query($LsqlUpdate) === true){
            $con1 = true;
        } else  {
            $con1 = false;
        }

        if($mysqli->query($LsqlInsert) === true){
            $con2 = true;
        } else  {
            $con2 = false;
        }

        echo json_encode(['con1'=> $con1, 'con2'=> $con2]);
    }

    // Aqui edito los cambios de los datos de Tareas_backoffice 
    if(isset($_GET['guardarCasoBackoffice']) && isset($_POST['editarCasoBackoffice'])){

        $nombre = mysqli_real_escape_string($mysqli, $_POST['nombreCaso']);
        $tipoDistribucion = mysqli_real_escape_string($mysqli, $_POST['tipoDistribucionTrabajo']);

        if($tipoDistribucion == 2){
            $pregun = mysqli_real_escape_string($mysqli, $_POST['pregun']);
            $lisopc = mysqli_real_escape_string($mysqli, $_POST['lisopc']);
        }else{
            $pregun = 'NULL';
            $lisopc = 'NULL';
        }
        
        $guion = $_POST['idGuion'];
        $estpas = $_POST['idEstpas'];
        $activo= isset($_POST['pasoActivo']) ? $_POST['pasoActivo']:'0';

        $LsqlUpdate = "UPDATE ".$BaseDatos_systema.".ESTPAS SET ESTPAS_Comentari_b = '".$nombre."', ESTPAS_Activo={$activo} WHERE ESTPAS_ConsInte__b= ".$estpas;
        $LsqlInsert = sprintf("UPDATE ".$BaseDatos_systema.".TAREAS_BACKOFFICE 
                        SET TAREAS_BACKOFFICE_Nombre_b = '".$nombre."', TAREAS_BACKOFFICE_TipoDistribucionTrabajo_b = ".$tipoDistribucion.", TAREAS_BACKOFFICE_ConsInte__PREGUN_estado_b = %s,
                        TAREAS_BACKOFFICE_ConsInte__LISOPC_estado_b = %s WHERE TAREAS_BACKOFFICE_ConsInte__ESTPAS_b = ".$estpas, $pregun, $lisopc);

        if($mysqli->query($LsqlUpdate) === true){
            $con1 = true;
        } else  {
            $con1 = false;
        }

        if($mysqli->query($LsqlInsert) === true){
            $con2 = true;
        } else  {
            $con2 = false;
        }

        echo json_encode(['con1'=> $con1, 'con2'=> $con2, 'LsqlInsert'=> $LsqlInsert]);
    }

    // Aqui agrego los usuarios que se seleccionen para la tarea de backoffice
    if(isset($_GET['agregarUsuarioTareaBackoffice'])){

        $tareaBack = $_POST['tareaBack'];
        $arrUsuarios = $_POST['arrUsuarios'];
        $arrUsuarios2 = $_POST['arrUsuarios2'];

        $Lsql = "SELECT ESTPAS_ConsInte__MUESTR_b FROM DYALOGOCRM_SISTEMA.ESTPAS JOIN DYALOGOCRM_SISTEMA.TAREAS_BACKOFFICE ON TAREAS_BACKOFFICE_ConsInte__ESTPAS_b = ESTPAS_ConsInte__b
                    WHERE TAREAS_BACKOFFICE_ConsInte__b = ".$tareaBack;
        $datoM = $mysqli->query($Lsql);
        $datoMuestra = $datoM->fetch_array();

        $estado = 'ok';

        for ($i=0; $i < count($arrUsuarios); $i++) { 
            $LsqlInsert = "INSERT INTO ".$BaseDatos_systema.".ASITAR_BACKOFFICE (ASITAR_BACKOFFICE_ConsInte__TAREAS_BACKOFFICE_b, ASITAR_BACKOFFICE_ConsInte__USUARI_b, ASITAR_BACKOFFICEConsInte__MUESTR_b, ASITAR_BACKOFFICE_Prioridad_b, ASITAR_BACKOFFICE_UsuarioCBX_b)
                    VALUES(".$tareaBack.", ".$arrUsuarios[$i].", ".$datoMuestra['ESTPAS_ConsInte__MUESTR_b'].", 0, ".$arrUsuarios2[$i].")";

            if($mysqli->query($LsqlInsert) === true){
                $estado = 'ok';
                guardar_auditoria('INSERTAR', "ASIGNAR USUARIO ".$arrUsuarios[$i]." AL PASO BACKOFFICE " . $tareaBack);
            } else  {
                $estado = 'error';
                break;
            }
             
        }

        echo json_encode(['estado' => $estado]);
    }

    // Aqui elimino los usuarios que se quitaran de la tarea de backoffice
    if(isset($_GET['quitarUsuarioTareaBackoffice'])){

        $tareaBack = $_POST['tareaBack'];
        $arrUsuarios = $_POST['arrUsuarios'];

        $estado = 'ok';

        // Buscamos la informacion del paso de backoffice
        $sql = "SELECT TAREAS_BACKOFFICE_ConsInte__b AS id, TAREAS_BACKOFFICE_ConsInte__GUION_b AS bd, ESTPAS_ConsInte__MUESTR_b AS muestra from DYALOGOCRM_SISTEMA.TAREAS_BACKOFFICE INNER JOIN DYALOGOCRM_SISTEMA.ESTPAS ON TAREAS_BACKOFFICE_ConsInte__ESTPAS_b = ESTPAS_ConsInte__b WHERE TAREAS_BACKOFFICE_ConsInte__b = ".$tareaBack;
        $res = $mysqli->query($sql);
        $backoffice = $res->fetch_object();

        for ($i=0; $i < count($arrUsuarios); $i++) { 

            guardar_auditoria('ELIMINAR', "DESASIGNAR USUARIO ".$arrUsuarios[$i]." DEL PASO BACKOFFICE " . $tareaBack);

            // Desasignamos los usuarios
            $sqlDesasignar = "UPDATE {$BaseDatos}.G{$backoffice->bd}_M{$backoffice->muestra} SET G{$backoffice->bd}_M{$backoffice->muestra}_ConIntUsu_b = NULL WHERE G{$backoffice->bd}_M{$backoffice->muestra}_ConIntUsu_b = {$arrUsuarios[$i]}";
            $mysqli->query($sqlDesasignar);

            $LsqlDelete = "DELETE FROM ".$BaseDatos_systema.".ASITAR_BACKOFFICE WHERE ASITAR_BACKOFFICE_ConsInte__USUARI_b = ".$arrUsuarios[$i]." AND ASITAR_BACKOFFICE_ConsInte__TAREAS_BACKOFFICE_b = ".$tareaBack;

            if($mysqli->query($LsqlDelete) === true){
                $estado = 'ok';
            } else  {
                $estado = 'error';
                break;
            }
        }

        echo json_encode(['estado' => $estado]);
    }

    if(isset($_GET['llenarDatosPregun'])){
        $guion = $_POST['guion'];
        $Lsql = "SELECT * FROM ".$BaseDatos_systema.".PREGUN WHERE PREGUN_ConsInte__GUION__b = ".$guion." AND PREGUN_Tipo______b NOT IN (12,9) AND (PREGUN_Texto_____b != 'OPTIN_DY_WF' AND PREGUN_Texto_____b != 'ESTADO_DY');";
        $res_Resultado = $mysqli->query($Lsql);
        $datos = array();
        while ($key = $res_Resultado->fetch_object()) {
            $datos[] = $key;
        }
        
        echo json_encode($datos);
    }

    if(isset($_GET['getUsuariosBackoffice'])){

        $backofficeId = $_POST['backoffice'];
        $datos = array();

        // Obtengo una lista de usuarios pertenecientes al huesped que ya han sido asignados a asitar_backoffice 
        $Asql = "SELECT USUARI_ConsInte__b as id, USUARI_Nombre____b as nombre
        FROM ".$BaseDatos_systema.".USUARI 
        JOIN ".$BaseDatos_systema.".ASITAR_BACKOFFICE ON USUARI_ConsInte__b = ASITAR_BACKOFFICE_ConsInte__USUARI_b
        WHERE ASITAR_BACKOFFICE_ConsInte__TAREAS_BACKOFFICE_b = ".$backofficeId." AND USUARI_ConsInte__PERUSU_b IS NULL ORDER BY USUARI_Nombre____b ASC ";

        $res = $mysqli->query($Asql);
        
        while ($row = $res->fetch_object()) {
            $datos[] = $row;
        }
        
        echo json_encode(['usuarios' => $datos]);

    }

    if(isset($_GET['gestionarBackoffice'])){

        $backofficeId = $_POST['backofficeId'];
        $tipoCondicion = $_POST['radioCodicionesBackoffice'];
        $cantCondiciones = $_POST['filtrosAdminBackoffice'];

        $asignarAgente = $_POST['sel_usuarios_asignacion'];

        $ejecucionExitosa = false;

        // Buscamos la configuracion del backoffice
        $query = "SELECT TAREAS_BACKOFFICE_ConsInte__GUION_b AS bd, ESTPAS_ConsInte__MUESTR_b AS muestraId FROM {$BaseDatos_systema}.ESTPAS JOIN {$BaseDatos_systema}.TAREAS_BACKOFFICE ON TAREAS_BACKOFFICE_ConsInte__ESTPAS_b = ESTPAS_ConsInte__b WHERE TAREAS_BACKOFFICE_ConsInte__b = {$backofficeId}";
        $res = $mysqli->query($query);
        $pasoBackoffice = $res->fetch_object();

        $base = 'G'.$pasoBackoffice->bd;
        $muestra = $base . '_M' . $pasoBackoffice->muestraId;

        // Validamos si llego algo en el campo de asignacion de agente
        if(!is_null($asignarAgente) && $asignarAgente != 0){

            // Creamos la consulta
            $queryUpdate = "UPDATE {$BaseDatos}.{$muestra} JOIN {$BaseDatos}.$base ON {$muestra}.{$muestra}_CoInMiPo__b = {$base}.{$base}_ConsInte__b SET ";

            if($asignarAgente == -1){
                $asignarAgente = "NULL";
            }

            $queryUpdate .= "{$muestra}_ConIntUsu_b = {$asignarAgente} ";

            // Miramos si tiene condiciones o aplica para todos
            if($tipoCondicion == 1){
                if ($queryUpdate != "") {
                    if ($mysqli->query($queryUpdate)) {
                        guardar_auditoria("AdmRegistrosGestionarBK","POB: {$muestra} TipL: ACTUALIZACION APLICADA A TODOS LOS REGISTROS | ".$queryUpdate);
                        $ejecucionExitosa = true;
                    }
                }
            }

            if($tipoCondicion == 2){

                // primero validamos si hay condiones
                if($cantCondiciones > 0){

                    $queryUpdate .= " WHERE TRUE ";
                    $queryCondicion = "";

                    for ($i=0; $i < $cantCondiciones; $i++) { 
                        
                        // Validamos si existe el dato
                        if(isset($_POST['pregunBack'.$i])){
                            $campoFiltro = $_POST['pregunBack'.$i];
                            $operador = $_POST['condicion_'.$i];
                            $valor = $_POST['valor'.$i];
                            $tipoCampo = $_POST['tipo_campo_'.$i];
                            $logic = (isset($_POST['operador'.$i])) ? $_POST['operador'.$i] : '1';

                            if(is_numeric($campoFiltro)){

                                // Valido si existe este campo en la bd
                                $queryComprobar = "SHOW COLUMNS FROM {$BaseDatos}.{$base} WHERE Field = '{$base}_C{$campoFiltro}'";
                                $resultComprobar = $mysqli->query($queryComprobar);

                                if ($resultComprobar && $resultComprobar->num_rows > 0) {
                                    $queryCondicion .= generarCondicion("{$base}_C{$campoFiltro}", $operador, $tipoCampo, $valor, $logic); 
                                }

                            }else{
                                if ($campoFiltro != "_FechaInsercion") {
                                    $queryCondicion .= generarCondicion("{$muestra}{$campoFiltro}", $operador, $tipoCampo, $valor, $logic);
                                }else{
                                    $queryCondicion .= generarCondicion("{$base}_FechaInsercion", $operador, $tipoCampo, $valor, $logic);
                                }
                            }
                        }

                    }

                    // Seteamos las condiciones para que quede algo ordenado
                    $queryCondicion = "AND TRUE" . $queryCondicion;
                    $queryCondicion = str_replace("AND TRUE AND", " AND (", $queryCondicion);
                    $queryCondicion = str_replace("AND TRUE OR", " OR (", $queryCondicion);
                    $queryCondicion = $queryCondicion . " )";

                    if($queryUpdate != ''){

                        $queryUpdate = $queryUpdate.$queryCondicion;
    
                        if ($mysqli->query($queryUpdate)) {
                            guardar_auditoria("AdmRegistrosGestionarBK","POB: {$muestra} TipL: ACTUALIZACION APLICADA CON CONDICION | ".$queryUpdate);
                            $ejecucionExitosa = true;
                        }
                    }

                }

            }

        }

        echo json_encode([
            "exito" => $ejecucionExitosa
        ]);
    }

    // Seccion lead

    if(isset($_GET['getLead'])){
        $estpas = $_POST['pasoId'];
        
        $Lsql = "SELECT CORREO_ENTRANTE_ConsInte__b as id, CORREO_ENTRANTE_Nombre_b as nombre, CORREO_ENTRANTE_NoTomarTagFinal_b AS noTomarTagFinal, CORREO_ENTRANTE_CampoLlave_b as campoLlave, B.ESTPAS_activo FROM ".$BaseDatos_systema.".CORREO_ENTRANTE LEFT JOIN ".$BaseDatos_systema.".ESTPAS B ON CORREO_ENTRANTE_ConsInte__ESTPAS_b=B.ESTPAS_ConsInte__b WHERE CORREO_ENTRANTE_ConsInte__ESTPAS_b = ".$estpas;
        $res = $mysqli->query($Lsql);

        if($res->num_rows == 0){
            $dataLead['nombre'] = null;
            $dataLead['quienRecibe'] = null;
            $dataLead['tipoCondicion'] = null;
            $dataLead['condicion'] = null;
            $camposEmparejarLead = null;
        }else{
            $data = $res->fetch_array();
            $dataLead['nombre'] = $data['nombre'];
            $dataLead['leadActivo'] = $data['ESTPAS_activo'];
            $dataLead['noTomarTagFinal'] = $data['noTomarTagFinal'];
            $dataLead['campoLlave'] = $data['campoLlave'];

            //Campos de filtro
            $Lsql = "SELECT id_filtro FROM ".$dyalogo_canales_electronicos.".dy_ce_acciones_filtro WHERE id_correo_entrante = ".$data['id'];
            $res_filtro = $mysqli->query($Lsql);

            if($res_filtro->num_rows > 0){
                $filtro = $res_filtro->fetch_array();
                $id_filtro = $filtro['id_filtro'];

                // filtro
                $filt_Lsql = "SELECT id, id_ce_configuracion as cuenta, filtro, condicion FROM ".$dyalogo_canales_electronicos.".dy_ce_filtros WHERE id = ".$id_filtro;
                $res = $mysqli->query($filt_Lsql);

                while($key = $res->fetch_object()){
                    $dataLead['quienRecibe'] = $key->cuenta;
                    $dataLead['tipoCondicion'] = $key->filtro;
                    $dataLead['condicion'] = $key->condicion;
                }
            }

            // Campos
            $Lsql = "SELECT * FROM DYALOGOCRM_SISTEMA.CORREO_ENTRANTE_CAMPOS WHERE CORREO_ENTRANTE_CAMPOS_CorreoEntrante_b = ".$data['id'];                
            $res = $mysqli->query($Lsql);

            $camposEmparejarLead = array();
            $i = 0;

            while($key = $res->fetch_object()){
                $camposEmparejarLead[$i]['id'] = $key->CORREO_ENTRANTE_CAMPOS_ConsInte__b;
                $camposEmparejarLead[$i]['tagInicial'] = $key->CORREO_ENTRANTE_CAMPOS_Prefijo_b;
                $camposEmparejarLead[$i]['tagFinal'] = $key->CORREO_ENTRANTE_CAMPOS_Posfijo_b;
                $camposEmparejarLead[$i]['campoBD'] = $key->CORREO_ENTRANTE_CAMPOS_ConsInte__PREGUN_b;
                $i++;
            }
        }

        echo json_encode(['dataLead'=> $dataLead, 'camposEmparejarLead' => $camposEmparejarLead]);
    }

    if(isset($_GET['guardarLead'])){
        // Aqui guardo los datos de LEAD

        $estpas = $_POST['id_estpas'];
        $activo = isset($_POST['leadActivo']) ? $_POST['leadActivo'] : '0';
        // Actualizo estpas
        $Lsql = "SELECT ESTPAS_ConsInte__CAMPAN_b, ESTPAS_ConsInte__ESTRAT_b FROM ".$BaseDatos_systema.".ESTPAS WHERE ESTPAS_ConsInte__b = ".$estpas;
        $resEstpas = $mysqli->query($Lsql);
        $estdata = $resEstpas->fetch_array();
        if($estdata['ESTPAS_ConsInte__CAMPAN_b'] == null){
            $estrategiaLsql = "SELECT ESTPAS_ConsInte__CAMPAN_b FROM ".$BaseDatos_systema.".ESTPAS WHERE ESTPAS_ConsInte__ESTRAT_b = ".$estdata['ESTPAS_ConsInte__ESTRAT_b']." AND ESTPAS_ConsInte__CAMPAN_b IS NOT NULL LIMIT 1";
            $res = $mysqli->query($estrategiaLsql);
            $data = $res->fetch_array();

            $EstpasUsql = "UPDATE ".$BaseDatos_systema.".ESTPAS SET ESTPAS_ConsInte__CAMPAN_b = '".$data['ESTPAS_ConsInte__CAMPAN_b']."', ESTPAS_activo={$activo} WHERE ESTPAS_ConsInte__b = ".$estpas;
            $mysqli->query($EstpasUsql);
            
        }

        $EstpasUsql = "UPDATE ".$BaseDatos_systema.".ESTPAS SET ESTPAS_Comentari_b = '".$_POST['nombreLead']."', ESTPAS_activo={$activo} WHERE ESTPAS_ConsInte__b = ".$estpas;
        $mysqli->query($EstpasUsql);

        // Verifico si ya esta creado el correo entrante
        $Lsql = "SELECT CORREO_ENTRANTE_ConsInte__b as id FROM ".$BaseDatos_systema.".CORREO_ENTRANTE WHERE CORREO_ENTRANTE_ConsInte__ESTPAS_b = ".$estpas;
        $res = $mysqli->query($Lsql);

        $desactivarTagFinal = 0;
        // Si trae el el check de tag final le decimos que es -1
        if(isset($_POST['desactivarTagFinal'])){
            $desactivarTagFinal = -1;
        }

        // Inserto si es nuevo
        if($res->num_rows == 0){
            // inserto correo_entrante
            $Lsql = "INSERT INTO ".$BaseDatos_systema.".CORREO_ENTRANTE (CORREO_ENTRANTE_Nombre_b, CORREO_ENTRANTE_ConsInte__ESTPAS_b, CORREO_ENTRANTE_NoTomarTagFinal_b, CORREO_ENTRANTE_CampoLlave_b) VALUES ('".$_POST['nombreLead']."', '".$estpas."', '".$desactivarTagFinal."', '".$_POST['LeadCampoLlave']."')";
            $mysqli->query($Lsql);

            $id_correo_entrante = $mysqli->insert_id;

        }else{
            // Actualizo si ya estan creado los campos
            $correo_entrante = $res->fetch_array();
            $id_correo_entrante = $correo_entrante['id'];

            $Lsql = "UPDATE ".$BaseDatos_systema.".CORREO_ENTRANTE SET CORREO_ENTRANTE_Nombre_b = '".$_POST['nombreLead']."', CORREO_ENTRANTE_NoTomarTagFinal_b = '".$desactivarTagFinal."', CORREO_ENTRANTE_CampoLlave_b = '".$_POST['LeadCampoLlave']."' WHERE CORREO_ENTRANTE_ConsInte__b = ".$id_correo_entrante;
            $mysqli->query($Lsql);
        }

        // Armo la condicion del filtro
        if($_POST['leadTipoCondicion'] != 100){
            $condicion = $_POST['leadCondicion'];
        }else{
            $condicion = '';
        }

        // Verifico si ya hay un filtro vinculado al correo entrante
        $Lsql = "SELECT id_filtro FROM ".$dyalogo_canales_electronicos.".dy_ce_acciones_filtro WHERE id_correo_entrante = ".$id_correo_entrante;
        $res_filtro = $mysqli->query($Lsql);

        if($res_filtro && $res_filtro->num_rows > 0){
            // Existe un filtro vinculado
            $filtro = $res_filtro->fetch_array();
            $id_filtro = $filtro['id_filtro'];

            // Actualizamos el filtro LEAD
            $Lsql = "UPDATE ".$dyalogo_canales_electronicos.".dy_ce_filtros SET id_ce_configuracion = '".$_POST['leadQuienRecibe']."', filtro = '".$_POST['leadTipoCondicion']."', condicion = '".$condicion."' WHERE id = ".$id_filtro;
            $mysqli->query($Lsql);

        }else {
            // No existe filtro
            // inserto dy_ce_filtros
            $Lsql = "INSERT INTO ".$dyalogo_canales_electronicos.".dy_ce_filtros (id_ce_configuracion, filtro, condicion, descargar_adjuntos, directorio_adjuntos, id_estpas) VALUES ('".$_POST['leadQuienRecibe']."','".$_POST['leadTipoCondicion']."','".$condicion."',0,'/mnt/disks/grabaciones/adjuntos/', ".$estpas.")";
            $mysqli->query($Lsql);
            
            $id_filtro = $mysqli->insert_id;

            // inserto dy_ce_accion_filtros    
            $orden = ordenMax($_POST['leadQuienRecibe']);            
            $Lsql = "INSERT INTO ".$dyalogo_canales_electronicos.".dy_ce_acciones_filtro (id_filtro, orden, accion, id_correo_entrante) VALUES (".$id_filtro.",".$orden.",7,".$id_correo_entrante.")";
            $mysqli->query($Lsql);
        }
        
        // Actualizo y/o inserto los campos
        if(isset($id_correo_entrante)){

            // Inserto los nuevos campos
            if(isset($_POST['contCamposEmparejar']) && $_POST['contCamposEmparejar'] > 0){
                for ($i=0; $i < $_POST['contCamposEmparejar']; $i++) { 
                    
                    if(isset($_POST['tagInicial_'.$i]) && isset($_POST['tagFinal_'.$i]) && isset($_POST['campoBD_'.$i]) && $_POST['campoBD_'.$i] != 0){
                        // Inserto los campos
                        $Lsql = "INSERT INTO ".$BaseDatos_systema.".CORREO_ENTRANTE_CAMPOS (CORREO_ENTRANTE_CAMPOS_CorreoEntrante_b, CORREO_ENTRANTE_CAMPOS_ConsInte__PREGUN_b, CORREO_ENTRANTE_CAMPOS_Prefijo_b, CORREO_ENTRANTE_CAMPOS_Posfijo_b) VALUES ('".$id_correo_entrante."', '".$_POST['campoBD_'.$i]."', '".$_POST['tagInicial_'.$i]."', '".$_POST['tagFinal_'.$i]."')";                                        
                        $mysqli->query($Lsql);    
                    }
                }
            }

            // Actualizo los campos existentes
            if(isset($_POST['listCamposLead'])){

                foreach($_POST['listCamposLead'] as $key){
                    
                    if(isset($_POST['tagInicial_'.$key]) && isset($_POST['tagFinal_'.$key]) && isset($_POST['campoBD_'.$key]) && $_POST['campoBD_'.$key] != 0){
                        
                        $Lsql = "UPDATE ".$BaseDatos_systema.".CORREO_ENTRANTE_CAMPOS SET CORREO_ENTRANTE_CAMPOS_ConsInte__PREGUN_b = '".$_POST['campoBD_'.$key]."', CORREO_ENTRANTE_CAMPOS_Prefijo_b = '".$_POST['tagInicial_'.$key]."', CORREO_ENTRANTE_CAMPOS_Posfijo_b = '".$_POST['tagFinal_'.$key]."' WHERE CORREO_ENTRANTE_CAMPOS_ConsInte__b = ".$key;
                        $mysqli->query($Lsql);
                    }

                }
                
            }
        }
        
        echo json_encode(['estado' => '1']);
        
    } /*** hasta aqui **/

    // Obtener los campos de la base de datos
    if(isset($_POST['getCamposPregun'])){
        $opcionesCampos = '';
        $opcionesCamposOnlyClient = '';

        $sql = "SELECT PREGUN_ConsInte__b as id, PREGUN_Texto_____b as nombre FROM DYALOGOCRM_SISTEMA.PREGUN WHERE PREGUN_ConsInte__GUION__b = ".$_POST['idGuion']." ORDER BY PREGUN_Texto_____b";
        $res = $mysqli->query($sql);
        while($obj = $res->fetch_object()){
            $opcionesCampos .= "<option value='".$obj->id."' dinammicos='0'>".$obj->nombre."</option>";

            if($obj->nombre != "ORIGEN_DY_WF" && $obj->nombre != "OPTIN_DY_WF" && $obj->nombre != "ESTADO_DY" ){
                $opcionesCamposOnlyClient .= "<option value='".$obj->id."' dinammicos='0'>".$obj->nombre."</option>";
            }
        }  
        echo json_encode(['opciones' => $opcionesCampos, 'opcionesSoloCliente' => $opcionesCamposOnlyClient]);
    }

    // Elimina una fila de campo LEAD
    if(isset($_POST['borrarCampoEmparejarLead'])){

        $Lsql = "SELECT * FROM ".$BaseDatos_systema.".CORREO_ENTRANTE_CAMPOS WHERE CORREO_ENTRANTE_CAMPOS_ConsInte__b = ".$_POST['idCampo'];
        $res = $mysqli->query($Lsql);
        if($res->num_rows > 0 ){

            $Lsql = "DELETE FROM ".$BaseDatos_systema.".CORREO_ENTRANTE_CAMPOS WHERE CORREO_ENTRANTE_CAMPOS_ConsInte__b = ".$_POST['idCampo'];
            $mysqli->query($Lsql);

        }
        echo json_encode(array('message' => 'Eliminado'));
    }

    // Obtengo la informacion de webservice
    if(isset($_GET['getwebservice']) && $_GET['getwebservice'] == true){
        $estpas = $_POST['pasoId'];
        $bd = $_POST['bd'];

        $querySelect = "SELECT ESTPAS_ConsInte__b AS id, ESTPAS_Comentari_b AS nombre, ESTPAS_ConsInte_WS_b AS id_Ws, ESTPAS_Ws_Json_b AS wsjson FROM ".$BaseDatos_systema.".ESTPAS WHERE ESTPAS_ConsInte__b = ".$estpas." LIMIT 1";
    
        // Traigo los campos que tiene la bd
        $Lsql = "SELECT PREGUN_ConsInte__b AS id, PREGUN_Texto_____b AS texto, PREGUN_Tipo______b AS tipo, PREGUN_ConsInte__OPCION_B AS opcion, IF(GUION__ConsInte__PREGUN_Llave_b IS NOT NULL,TRUE,FALSE) AS llave FROM ".$BaseDatos_systema.".PREGUN LEFT JOIN ".$BaseDatos_systema.".GUION_ ON PREGUN_ConsInte__b=GUION__ConsInte__PREGUN_Llave_b WHERE PREGUN_ConsInte__GUION__b = ".$bd." AND PREGUN_Texto_____b NOT LIKE '%_DY%';";
        $resCampos = $mysqli->query($Lsql);
        while ($item = $resCampos->fetch_object()) {
            $campos[] = $item;
        }

        // Traigo solo el campo origen
        $origenSql = "SELECT PREGUN_ConsInte__b AS id FROM ".$BaseDatos_systema.".PREGUN WHERE PREGUN_ConsInte__GUION__b = ".$bd." AND PREGUN_Texto_____b = 'ORIGEN_DY_WF'";
        $resOrigen = $mysqli->query($origenSql);
        $dataOrigen = $resOrigen->fetch_array();
        $origen = $dataOrigen['id'];
        
        $sql = mysqli_query($mysqli, $querySelect);
        if ($sql) {
            $data = mysqli_fetch_object($sql);
            echo json_encode(['paso' => $data, 'campos' => $campos, 'origen' => $origen]);
        } 
    }

if (isset($_GET['guardarwebservice']) && $_GET['guardarwebservice'] == true) {

    $json = array(
        'estpas' => $_POST['pasowsId'],
        'nombre' => $_POST['wsNombre'],
        'ws' => isset($_POST['selectWS']) == null ? 0 : $_POST['selectWS'],
        'campollave' => $_POST['cllave'],
        'validaConRestriccion' => isset($_POST['validaConRestriccion']) == true ? $_POST['validaConRestriccion'] : null
    );

    $consulta = "UPDATE " . $BaseDatos_systema . ".ESTPAS SET ESTPAS_Comentari_b = '{$json['nombre']}', ESTPAS_ConsInte_WS_b={$json['ws']}, ESTPAS_Ws_Json_b = '" . json_encode($json) . "' WHERE ESTPAS_ConsInte__b = {$json['estpas']}";
    // var_dump("consulta => $consulta");
    $sql = mysqli_query($mysqli, $consulta);
    if ($sql) {
        echo json_encode(['estado' => '1']);
    } else {
        echo json_encode(['estado' => '0']);
    }
}

    if(isset($_GET['traeOpcionesLista'])){
        $lista=isset($_POST['lista']) ? $_POST['lista'] : false;
        if($lista){
            $sqLista=$mysqli->query("SELECT LISOPC_ConsInte__b AS id, LISOPC_Nombre____b AS texto FROM {$BaseDatos_systema}.LISOPC WHERE LISOPC_ConsInte__OPCION_b={$lista}");
        }
        while ($opcion = $sqLista->fetch_object()) {
            $campos[] = $opcion;
        }
        echo json_encode($campos);
    }

    if(isset($_GET["getCredencialesApi"])){
        //VALIDAMOS SI YA SE GENERARON CREDENCIALES PARA ESE HUESPED
        $sql=$mysqli->query("SELECT usuario,token FROM dyalogo_api.dy_api_tokens WHERE usuario='".md5($_SESSION['PROYECTO'])."'");
        if($sql){
            if($sql->num_rows == 1){
                $sql=$sql->fetch_object();
                $user=$sql->usuario;
                $token=$sql->token;
                echo json_encode(array("mensaje"=>"ok", "user"=>$user, "token"=>$token));
            }else{
                $credenciales=crearToken(md5($_SESSION['PROYECTO']), sumarSeisMeses());
                $credenciales=json_decode($credenciales);
                if($credenciales){
                    $user=$credenciales->objSerializar_t->usuario;
                    $token=$credenciales->objSerializar_t->token;
                    echo json_encode(array("mensaje"=>"ok", "user"=>$user, "token"=>$token));
                }
                
            }
        }else{
            echo json_encode(array('mensaje'=>'No se pudo obtener las credenciales'));
        }
    }

function sumarSeisMeses()
{
    $currentDate = date('Y-m-d h:i:s');
    $fecha = date_create($currentDate);
    date_add($fecha, date_interval_create_from_date_string("6 months"));
    return date_format($fecha, 'Y-m-d h:i:s');
}

    if(isset($_GET["validarPasoChat"])){

        $pasoFrom = $_POST['pasoFrom'];
        $pasoTo = $_POST['pasoTo'];
        $esPasoChat = false;
        $tipoPaso = 0;

        $sql = "SELECT ESTPAS_ConsInte__b AS id, ESTPAS_Tipo______b AS tipo, ESTPAS_Comentari_b AS nombre FROM {$BaseDatos_systema}.ESTPAS WHERE ESTPAS_ConsInte__b = {$pasoFrom}";
        $res = $mysqli->query($sql);
        $row = $res->fetch_object();

        if($row){
            if($row->tipo == 14 || $row->tipo == 15 || $row->tipo == 16){
                $esPasoChat = true;
            }

            $tipoPaso = $row->tipo;
        }

        $nombrePasoFrom = $row->nombre;

        $horario = [];
        $esDentroHorario = false;
        $esFueraHorario = false;

        if($esPasoChat){
            $tipoAccion = 0;
            $detalleAccion = 0;

            // Voy a ver que es el paso dos para validar si es fuera de horario o no
            $sqlPaso2 = "SELECT ESTPAS_ConsInte__b AS id, ESTPAS_Tipo______b AS tipo, ESTPAS_ConsInte__CAMPAN_b AS campana FROM {$BaseDatos_systema}.ESTPAS WHERE ESTPAS_ConsInte__b = {$pasoTo}";
            $res2 = $mysqli->query($sqlPaso2);
            $row2 = $res2->fetch_object();

            if($row2->tipo == 1){
                // Es entrante
                $tipoAccion = 1;

                // Necesito la id campana
                $sqlCampana = "SELECT CAMPAN_IdCamCbx__b AS campana_cbx FROM {$BaseDatos_systema}.CAMPAN WHERE CAMPAN_ConsInte__b = {$row2->campana}";
                $resCamp = $mysqli->query($sqlCampana);
                $rowCamp = $resCamp->fetch_object();

                $detalleAccion = $rowCamp->campana_cbx;
            }

            if($row2->tipo == 12){
                // Es bot
                $tipoAccion = 2;

                // Necesito id bot
                $sqlBot = "SELECT id FROM dyalogo_canales_electronicos.dy_base_autorespuestas WHERE id_estpas = {$row2->id}";
                $resBot = $mysqli->query($sqlBot);
                $rowBot = $resBot->fetch_object();

                $detalleAccion = $rowBot->id;
            }

            if($row2->tipo == 4){
                // Es formulario
                $tipoAccion = 3;
                // Ya tengo el paso_id asi que nel
                $detalleAccion = $row2->id;
            }

            if($tipoAccion > 0 && $detalleAccion > 0){
                // Si ambos son diferentes a cero vamos por buen camino y necesitariamos id_configuracion y es con horario o no? o ambas!!!!

                // Validamos si me trae algo con horario
                $sql = "SELECT id FROM dyalogo_canales_electronicos.dy_chat_configuracion 
                    WHERE id_estpas = {$pasoFrom} AND dentro_horario_accion = {$tipoAccion} AND dentro_horario_detalle_accion = {$detalleAccion}";
                $resHorario = $mysqli->query($sql);

                if($resHorario->num_rows > 0){
                    $chatConfig = $resHorario->fetch_object();
                    $chatId = $chatConfig->id;
                    $esDentroHorario = true;
                }

                // Validamos si me trae algo sin horario
                $sql = "SELECT id FROM dyalogo_canales_electronicos.dy_chat_configuracion 
                    WHERE id_estpas = {$pasoFrom} AND fuera_horario_accion = {$tipoAccion} AND fuera_horario_detalle_accion = {$detalleAccion}";
                $resFueraHorario = $mysqli->query($sql);

                if($resFueraHorario->num_rows > 0){
                    $chatConfig = $resFueraHorario->fetch_object();
                    $chatId = $chatConfig->id;
                    $esFueraHorario = true;
                }

                if($esDentroHorario || $esFueraHorario){
                    // Por fin, ahora si traigo el horario
                    $sql = "SELECT * FROM dyalogo_canales_electronicos.dy_chat_horarios WHERE id_configuracion = {$chatId}";
                    $resHorario = $mysqli->query($sql);

                    while($row = $resHorario->fetch_object()){
                        $horario[] = $row;
                    }
                }
            }
            
        }

        echo json_encode([
            "esPasoChat" => $esPasoChat,
            "tipoPasoFrom" => $tipoPaso,
            "nombrePaso" => $nombrePasoFrom,
            "esDentroHorario" => $esDentroHorario,
            "esFueraHorario" => $esFueraHorario,
            "horario" => $horario
        ]);
    }

    if(isset($_GET['obtenerDatosCampana'])){

        $pasoToId = $_POST['pasoTo'];
        $bd = $_POST['bd'];

        $sql = "SELECT CAMPAN_ConsInte__b AS id, CAMPAN_Nombre____b AS nombre, CAMPAN_ConfDinam_b AS tipo_distribucion FROM {$BaseDatos_systema}.CAMPAN a 
            INNER JOIN {$BaseDatos_systema}.ESTPAS b ON a.CAMPAN_ConsInte__b = b.ESTPAS_ConsInte__CAMPAN_b WHERE b.ESTPAS_ConsInte__b = {$pasoToId}";

        $res = $mysqli->query($sql);

        if($res && $res->num_rows > 0){
            
            $data = $res->fetch_object();
            
            echo json_encode([
                "estado" => "ok",
                "campana" => $data
            ]);

        }else{
            echo json_encode([
                "estado" => "fallo"
            ]);
        }
    }

    if(isset($_GET['obtenerCondiciones'])){

        $pasoFromId = $_POST['pasoFrom'];
        $pasoToId = $_POST['pasoTo'];

        $estructuraCondiciones = '<p style="font-size: 1.2em">No se pudo obtener la lista de condiciones<p>';

        // Traigo la informacion de estcon
        $sql = "SELECT ESTCON_ConsInte__b AS id, ESTCON_Tipo_Consulta_b AS tipo_consulta, ESTCON_Consulta_sql_b AS consulta FROM {$BaseDatos_systema}.ESTCON WHERE ESTCON_ConsInte__ESTPAS_Des_b = {$pasoFromId} AND ESTCON_ConsInte__ESTPAS_Has_b = {$pasoToId}";
        $res = $mysqli->query($sql);
        
        if($res && $res->num_rows > 0){

            $estcon = $res->fetch_object();

            // Validamos el tipo de dato; si es todos = 1, condiciones = 3
            if($estcon->tipo_consulta == 1){
                $estructuraCondiciones = '<p style="font-size: 1.2em" class="textoSubTituloCondiciones"> Y se aplicaran a todos los registros<p>';
            }

            if($estcon->tipo_consulta == 3){

                // Traigo las condiciones
                $sql = "SELECT * FROM {$BaseDatos_systema}.ESTCON_CONDICIONES WHERE id_estcon = {$estcon->id}";
                $res = $mysqli->query($sql);

                if($res && $res->num_rows > 0){

                    $estructuraCondiciones = '<p style="font-size: 1.3em" class="textoSubTituloCondiciones">Y sera aplicable a los registros cuyas condiciones son: <p>';
                    
                    while($row = $res->fetch_object()){
                        
                        $campo = traerNombreCampo($row->campo);
                        $condicion = traerNombreCondicion($row->condicion);
                        $estructuraCondiciones .= '<p style="font-size: 1.2em"><strong>' . $campo .' '. $condicion .' '. $row->valor . '</strong><p>';
                    }

                }
            }

            $estructuraLabelFecha = "Fecha de CREACION desde la que se quieren tomar registros";

            // Necesito el tipo de paso de from
            $pasoFrom = obtenerEstpas($pasoFromId);

            if($pasoFrom->tipo == 1 || $pasoFrom->tipo == 6 || $pasoFrom->tipo == 9){
                // Validamos y miramos si en las condiciones tiene cantidad de intentos o tipo de reintento 
                $filtroTieneCantidadIntentos = strpos($estcon->consulta, "_NumeInte__b = '0'");
                $filtroTieneTipoReintento = strpos($estcon->consulta, "_Estado____b = '0'");
    
                if($filtroTieneCantidadIntentos === false && $filtroTieneTipoReintento === false){
                    $estructuraLabelFecha = "Fecha de GESTION desde la que se quieren tomar registros";
                }

                if($pasoFrom->tipo == 9){
                    $estructuraLabelFecha = "Fecha de CREACION desde la que se quieren tomar registros";
                }
            }


        }

        echo json_encode([
            "estconId" => $estcon->id,
            "estructuraCondiciones" => $estructuraCondiciones,
            "estructuraLabelFecha" => $estructuraLabelFecha
        ]);
    }

    if(isset($_GET['validarRegistrosAInsertar'])){

        $pasoFromId = $_POST['pasoFrom'];
        $pasoToId = $_POST['pasoTo'];
        $fecha = $_POST['fecha'];
        $base = $_POST['poblacion'];

        // obtenemos las informacion de los pasos from, to y la flecha asociada a ambos
        $pasoFrom = obtenerEstpas($pasoFromId);
        $pasoTo = obtenerEstpas($pasoToId);
        $estcon = obtenerEstconByFromTo($pasoFromId, $pasoToId);

        if($estcon->activo == 0){

            echo json_encode([
                "estado" => "fallo",
                "mensaje" => "La flecha esta apagada, para continuar con el proceso debe encenderla y guardar la configuracion"
            ]);

            exit();
        }

        if(is_null($estcon->consulta) || $estcon->consulta == ''){
            
            echo json_encode([
                "estado" => "fallo",
                "mensaje" => "Se genero un problema al obtener las condiciones de la flecha, por favor guarde la configuracion actual de la flecha"
            ]);

            exit();
        }

        // Entonces realizaremos el analisis preeliminar para ver cuantos registros se van a ejecutar

        // Buscamos cual es la condicion de la flecha
        $consulta = explode('WHERE', $estcon->consulta);
        $filtro = '';
        if(isset($consulta[1])){
            $filtro = "( {$consulta[1]} )";
        }

        $muestraFromId = 0;
        $muestraToId = 0;
        $registrosAProcesar = 0;

        // Obtenemos la muestra del paso to
        if($pasoTo->tipo == 1 || $pasoTo->tipo == 6){
            $muestraToId = $pasoTo->campanMuestraId;
        }else{
            $muestraToId = $pasoTo->estpasMuestraId;
        }
        
        // validamos el tipo del paso from 
        if($pasoFrom->tipo == 1 || $pasoFrom->tipo == 6 || $pasoFrom->tipo == 9){

            // Obtenemos la muestra del paso from
            if($pasoFrom->tipo == 1 || $pasoFrom->tipo == 6){
                $muestraFromId = $pasoFrom->campanMuestraId;
            }
            if($pasoFrom->tipo == 9){
                $muestraFromId = $pasoFrom->estpasMuestraId;
            }

            // Validamos y miramos si en las condiciones tiene cantidad de intentos o tipo de reintento, aplica solo para campaña
            $filtroTieneCantidadIntentos = strpos($estcon->consulta, "_NumeInte__b = '0'");
            $filtroTieneTipoReintento = strpos($estcon->consulta, "_Estado____b = '0'");

            $filtrarFechaPorLaMuestra = false;

            if($filtroTieneCantidadIntentos === false && $filtroTieneTipoReintento === false){
                $filtrarFechaPorLaMuestra = true;
            }

            // Si es tarea de backoffice el filtro no se hace por la muestra
            if($pasoFrom->tipo == 9){
                $filtrarFechaPorLaMuestra = false;
            }

            // Validamos si trae filtros o no
            if($filtro != ''){
                if($filtrarFechaPorLaMuestra){
                    $filtro = " WHERE {$filtro} AND G{$base}_M{$muestraFromId}_FecUltGes_b >= '{$fecha}'";
                }else{
                    $filtro = " WHERE {$filtro} AND G{$base}_FechaInsercion >= '{$fecha}'";
                }
            }else{
                if($filtrarFechaPorLaMuestra){
                    $filtro = " WHERE G{$base}_M{$muestraFromId}_FecUltGes_b >= '{$fecha}'";
                }else{
                    $filtro = " WHERE G{$base}_FechaInsercion >= '{$fecha}'";
                }
            }

            $sql = "SELECT count(1) AS contador
                FROM DYALOGOCRM_WEB.G{$base} 
                INNER JOIN DYALOGOCRM_WEB.G{$base}_M{$muestraFromId} ON G{$base}_ConsInte__b = G{$base}_M{$muestraFromId}_CoInMiPo__b
                LEFT JOIN DYALOGOCRM_WEB.G{$base}_M{$muestraToId} ON G{$base}_ConsInte__b = G{$base}_M{$muestraToId}_CoInMiPo__b {$filtro} AND G{$base}_M{$muestraToId}_CoInMiPo__b IS NULL
            ";
            
            $res = $mysqli->query($sql);

            if($res && $res->num_rows > 0){
                $data = $res->fetch_object();
                $registrosAProcesar = $data->contador;
            }

        }

        if($pasoFrom->tipo == 4 || $pasoFrom->tipo == 5 || $pasoFrom->tipo == 10 || $pasoFrom->tipo == 11){

            // Validamos si trae filtros o no
            if($filtro != ''){
                $filtro = " WHERE {$filtro} AND G{$base}_FechaInsercion >= '{$fecha}'";
            }else{
                $filtro = " WHERE G{$base}_FechaInsercion >= '{$fecha}'";
            }

            $sql = "SELECT count(1) AS contador
                FROM DYALOGOCRM_WEB.G{$base} 
                LEFT JOIN DYALOGOCRM_WEB.G{$base}_M{$muestraToId} ON G{$base}_ConsInte__b = G{$base}_M{$muestraToId}_CoInMiPo__b {$filtro} AND G{$base}_M{$muestraToId}_CoInMiPo__b IS NULL
            ";
            
            $res = $mysqli->query($sql);

            if($res && $res->num_rows > 0){
                $data = $res->fetch_object();
                $registrosAProcesar = $data->contador;
            }

        }

        echo json_encode([
            "estado" => "ok",
            "cantidadRegistros" => $registrosAProcesar,
            "estcon" => $estcon->id,
            "pasoTo" => $pasoTo,
            "condicionFecha" => $fecha,
            "base" => $base
        ]);

    }

    if(isset($_GET['ejecutarProcesoFlecha'])){

        $base = $_POST['base'];
        $estconId = $_POST['estconId'];
        $filtro = $_POST['filtro'];

        // Obtenemos la informacion de la flecha para traer el to y el from
        $estcon = obtenerEstconById($estconId);
        $pasoDesde = obtenerEstpas($estcon->pasoDesde);
        // $pasoHasta = obtenerEstpas($estcon->pasoHasta);

        exec("node /etc/dyalogo/apps/procesadorFlechas/procesarFlechasCargue.js -e flecha -p {$pasoDesde->id} -b {$base} -i {$estconId} -f {$pasoDesde->tipo} -d {$filtro} >> /dev/null &");

        echo json_encode([
            "estado" => "ok"
        ]);
    }


    if(isset($_GET['generarVistaMuestra'])){
        $pasoId = (isset($_POST["pasoId"])) ? $_POST["pasoId"] : false;

        if($pasoId){
            echo generarVistasUnicas(4, $_SESSION["HUESPED"] , $pasoId, null);
        }
        
    }


    if(isset($_GET['generarVistaACD'])){
        $pasoId = (isset($_POST["pasoId"])) ? $_POST["pasoId"] : false;

        if($pasoId){
            echo generarVistasUnicas(1, $_SESSION["HUESPED"] , $pasoId, null);
        }
        
    }

    function obtenerEstconByFromTo($fromId, $toId){
        
        global $mysqli;
        global $BaseDatos_systema;

        $data = [];

        $sql = "SELECT ESTCON_ConsInte__b AS id, ESTCON_Consulta_sql_b AS consulta, ESTCON_Activo_b AS activo FROM {$BaseDatos_systema}.ESTCON WHERE ESTCON_ConsInte__ESTPAS_Des_b = {$fromId} AND ESTCON_ConsInte__ESTPAS_Has_b = {$toId}";

        $res = $mysqli->query($sql);

        if($res && $res->num_rows > 0){
            $data = $res->fetch_object();
        }

        return $data;
    }

    function obtenerEstconById($id){
        
        global $mysqli;
        global $BaseDatos_systema;

        $data = [];

        $sql = "SELECT ESTCON_ConsInte__b AS id, ESTCON_Activo_b AS activo, ESTCON_ConsInte__ESTPAS_Des_b AS pasoDesde, ESTCON_ConsInte__ESTPAS_Has_b AS pasoHasta FROM {$BaseDatos_systema}.ESTCON WHERE ESTCON_ConsInte__b = {$id}";
        $res = $mysqli->query($sql);

        if($res && $res->num_rows > 0){
            $data = $res->fetch_object();
        }

        return $data;
    }

    function obtenerEstpas($id){

        global $mysqli;
        global $BaseDatos_systema;

        $data = [];

        $sql = "SELECT ESTPAS_ConsInte__b AS id, ESTPAS_Comentari_b AS nombre, ESTPAS_Tipo______b AS tipo, ESTPAS_ConsInte__CAMPAN_b AS campanId, ESTPAS_ConsInte__MUESTR_b AS estpasMuestraId, CAMPAN_ConsInte__MUESTR_b AS campanMuestraId FROM {$BaseDatos_systema}.ESTPAS 
            LEFT JOIN {$BaseDatos_systema}.CAMPAN ON ESTPAS_ConsInte__CAMPAN_b = CAMPAN_ConsInte__b
        WHERE ESTPAS_ConsInte__b = {$id}";

        $res = $mysqli->query($sql);

        if($res && $res->num_rows > 0){
            $data = $res->fetch_object();
        }

        return $data;

    }

    function traerNombreCampo($campoId){

        global $mysqli;
        global $BaseDatos_systema;

        if(is_numeric($campoId)){
            $sql = "SELECT PREGUN_ConsInte__b AS id, PREGUN_Texto_____b AS nombre FROM {$BaseDatos_systema}.PREGUN WHERE PREGUN_ConsInte__b = {$campoId}";
            $res = $mysqli->query($sql);
    
            if($res && $res->num_rows > 0){
                $campo = $res->fetch_object();
    
                return $campo->nombre;
            }else{
                return $campoId;
            }
        }else{
            // Si es texto lo traigo de una lista
            switch ($campoId) {
                case '_Estado____b':
                    $campoNombre = 'Tipo de reintento';
                    break;
                case '_ConIntUsu_b':
                    $campoNombre = 'Usuario';
                    break;
                case '_NumeInte__b':
                    $campoNombre = 'Numero de intentos';
                    break;
                case '_UltiGest__b':
                    $campoNombre = 'Ultima gesti&oacute;n';
                    break;
                case '_GesMasImp_b':
                    $campoNombre = 'Gesti&oacute;n mas importante';
                    break;
                case '_FecUltGes_b':
                    $campoNombre = 'Fecha ultima gesti&oacute;n';
                    break;
                case '_FeGeMaIm__b':
                    $campoNombre = 'Fecha gesti&oacute;n mas importante';
                    break;
                case '_ConUltGes_b':
                    $campoNombre = 'Clasificaci&oacute;n ultima gesti&oacute;n';
                    break;
                case '_CoGesMaIm_b':
                    $campoNombre = 'Clasificaci&oacute;n mas importante';
                    break;
                case '_Activo____b':
                    $campoNombre = 'Registro activo';
                    break;
                case '_CanalUG_b':
                    $campoNombre = 'Canal';
                    break;
                
                default:
                    $campoNombre = $campoId;
                    break;
            }
            return $campoNombre;
        }
    }

    function traerNombreCondicion($condicion){

        switch ($condicion) {
            case '=':
                $nombre = 'IGUAL A';
                break;
            case '!=':
                $nombre = 'DIFERENTE DE';
                break;
            case 'LIKE_1':
                $nombre = 'INICIE POR';
                break;
            case 'LIKE_2':
                $nombre = 'CONTIENE';
                break;
            case 'LIKE_3':
                $nombre = 'TERMINE EN';
                break;
            case '>':
                $nombre = 'MAYOR QUE';
                break;
            case '<':
                $nombre = 'MENOR QUE';
                break;
            default:
                $nombre = $condicion;
                break;
        }

        return $nombre;
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
                    case '<':
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
                    case '<':
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
                    case '<':
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
                    case '<':
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