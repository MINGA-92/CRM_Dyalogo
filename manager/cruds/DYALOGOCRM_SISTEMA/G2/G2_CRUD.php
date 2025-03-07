<?php
    session_start();
    ini_set('display_errors', 'On');
    ini_set('display_errors', 1);
    include(__DIR__."../../../../pages/conexion.php");
    include(__DIR__."../../../../global/WSCoreClient.php");
    require_once('../../../../helpers/parameters.php');

    /**
     *JDBD - En esta funcion armamos las condiciones de los filtros que recibimos.
     *@param .....
     *@return string. 
     */

    function armarCondicion($strCampo_p,$strOperador_p,$strValor_p,$strTipo_p,$strCondicion_p,$strMetodo_p=null){

        $strCampo_p = str_replace("\\", "", $strCampo_p);

        if ($strMetodo_p == "in" || $strMetodo_p == "1") {

            $strCampo_p = "Fecha";

        }


        switch ($strTipo_p) {
            case '5':

                return $strCondicion_p." DATE(".$strCampo_p.") ".$strOperador_p." '".$strValor_p."' ";

                break;
                        
            default:

                $strOperadorFinal_t  = "LIKE";

                switch ($strOperador_p) {

                    case 'LIKE_1':

                        $strValorFinal_t = "'".$strValor_p."%'";

                        break;
                    case 'LIKE_2':

                        $strValorFinal_t = "'%".$strValor_p."%'";

                        break;

                    case 'LIKE_3':

                        $strValorFinal_t = "'%".$strValor_p."'";

                        break;
                    default:

                        $strValorFinal_t = "'".$strValor_p."'";
                        $strOperadorFinal_t = $strOperador_p;

                        break;            

                    }

                return $strCondicion_p." ".$strCampo_p." ".$strOperadorFinal_t." ".$strValorFinal_t." ";

                break;
        }

    }



    function pregun($intSeccio_p,$intBd_p){

        global $mysqli;
        global $BaseDatos_systema;

        $strCampo_t = "Fecha";

        $strIdPregun_t = "";

        if ($intSeccio_p == "3") {

            $strCampo_t = "Tipificación";

        }

        $strSQL_t = "SELECT PREGUN_ConsInte__b AS id FROM ".$BaseDatos_systema.".PREGUN JOIN ".$BaseDatos_systema.".SECCIO ON PREGUN_ConsInte__SECCIO_b = SECCIO_ConsInte__b WHERE PREGUN_ConsInte__GUION__b = ".$intBd_p." AND SECCIO_TipoSecc__b = ".$intSeccio_p." AND PREGUN_Texto_____b = '".$strCampo_t."'";

        if ($resSQL_t = $mysqli->query($strSQL_t)) {

            if ($resSQL_t->num_rows > 0) {

                $strIdPregun_t = "G".$intBd_p."_C".$resSQL_t->fetch_object()->id;

            }

        }

        return $strIdPregun_t;

    }

    function guardar_auditoria($accion, $superAccion) {
        
        global $mysqli;
        global $BaseDatos_systema;

        $str_Lsql = "INSERT INTO " . $BaseDatos_systema . ".AUACAD (AUACAD_Fecha_____b , AUACAD_Hora______b, AUACAD_Ejecutor__b, AUACAD_TipoAcci__b , AUACAD_SubTipAcc_b, AUACAD_Accion____b , AUACAD_Huesped___b ) VALUES ('" . date('Y-m-d H:s:i') . "', '" . date('Y-m-d H:s:i') . "', " . $_SESSION['IDENTIFICACION'] . ", 'G10', '" . $accion . "', '" .$mysqli->real_escape_string($superAccion). "', " . $_SESSION['HUESPED'] . " );";
        $mysqli->query($str_Lsql);

    }

    include(__DIR__."../../../../global/funcionesGenerales.php");
    include(__DIR__."/../reporteador.php");


    /**
     *JDBD - Em esta funcion se le aplicara un formato para el nombre de la columna.
     *@param .....
     *@return string. 
     */

    function aliasColumna($strNombre_p){

      $strNombreCorregido_p = preg_replace("/\s+/", "_", $strNombre_p);

      $strNombreCorregido_p = preg_replace('([^0-9a-zA-Z_-])', "", $strNombreCorregido_p);

      $strNombreCorregido_p = strtoupper($strNombreCorregido_p);

      $strNombreCorregido_p = rtrim($strNombreCorregido_p,"_");

      $strNombreCorregido_p = substr($strNombreCorregido_p,0,40);

      return $strNombreCorregido_p;

    }


    /**
     *JDBD - En esta funcion se retornara toda la informacion en columnas dinamicas del reporte.
     *@param .....
     *@return obj. 
     */

    function columnasDinamicas($intIdBd_p){

        global $mysqli;
        global $BaseDatos_systema;
        global $BaseDatos_general;

        $strSQLCamposDinamicos_t = "SELECT PREGUN_ConsInte__b AS ID, CONCAT('G',PREGUN_ConsInte__GUION__b,'_C',PREGUN_ConsInte__b) AS campoId , PREGUN_Texto_____b AS nombre, PREGUN_Tipo______b AS tipo, SECCIO_TipoSecc__b AS seccion FROM ".$BaseDatos_systema.".PREGUN LEFT JOIN ".$BaseDatos_systema.".SECCIO ON PREGUN_ConsInte__SECCIO_b = SECCIO_ConsInte__b WHERE PREGUN_ConsInte__GUION__b = ".$intIdBd_p." AND PREGUN_Tipo______b NOT IN (11,9,12,16,17) ORDER BY PREGUN_ConsInte__b ASC";
       
        $arrCampos_t = [];

        $objSQLCamposDinamicos_t = $mysqli->query($strSQLCamposDinamicos_t);

        while ($obj = $objSQLCamposDinamicos_t->fetch_object()) {
        
            $arrCampos_t[] = ["ID"=>$obj->ID,"campoId"=>$obj->campoId,"nombre"=>aliasColumna($obj->nombre),"nombre_real"=>$obj->nombre,"tipo"=>$obj->tipo,"seccion"=>$obj->seccion];

        }

        return $arrCampos_t;
        

    }

    // Obtengo las columnas dinamicas en el bot
    function columnasDinamicasBot($botId, $pasoId){
        global $mysqli;
        global $BaseDatos_systema;
        global $dyalogo_canales_electronicos;

        $strSQLCamposDinamicos_t = "SELECT c.PREGUN_ConsInte__b AS ID, CONCAT('B{$botId}','_C',c.PREGUN_ConsInte__b) AS campoId, c.PREGUN_Texto_____b AS nombre, c.PREGUN_Tipo______b AS tipo, d.SECCIO_TipoSecc__b AS seccion, a.accion, a.id_pregun, a.nombre_variable
        FROM {$dyalogo_canales_electronicos}.dy_base_autorespuestas_contenidos a
            INNER JOIN {$dyalogo_canales_electronicos}.dy_base_autorespuestas b ON a.id_base_autorespuestas = b.id
            INNER JOIN ${BaseDatos_systema}.PREGUN c ON c.PREGUN_ConsInte__b = a.id_pregun
            LEFT JOIN {$BaseDatos_systema}.SECCIO d ON c.PREGUN_ConsInte__SECCIO_b = d.SECCIO_ConsInte__b 
        WHERE b.id_estpas = {$pasoId} AND a.id_pregun IS NOT NULL AND c.PREGUN_Tipo______b NOT IN (11,9,12,16) ORDER BY c.PREGUN_ConsInte__b ASC";

        $arrCampos_t = [];

        $objSQLCamposDinamicos_t = $mysqli->query($strSQLCamposDinamicos_t);

        while ($obj = $objSQLCamposDinamicos_t->fetch_object()) {

            $arrCampos_t[] = ["ID"=>$obj->ID,"campoId"=>$obj->campoId,"nombre"=>aliasColumna($obj->nombre),"tipo"=>$obj->tipo,"seccion"=>$obj->seccion];

        }

        return $arrCampos_t;

    }

    // Esta funcion retorna el id de la tabla gestiones del bot
    function getIdTablaGestionBot($pasoId){

        global $mysqli;
        global $dyalogo_canales_electronicos;

        $sql = "SELECT * FROM {$dyalogo_canales_electronicos}.dy_bot WHERE id_estpas = {$pasoId}";
        $res = $mysqli->query($sql);

        if($res && $res->num_rows > 0){
            $data = $res->fetch_object();

            return $data->id_guion_gestion;
        }

        return 0;
    }

    if (isset($_GET["filtroAvanzadoJSON"])) {
        echo json_encode($_POST);

    }

    if (isset($_GET["traerOpcionesLista"])) {

        $intIdCampo_t = $_POST["intIdCampo_t"];
        $strEspecial_t = $_POST["strEspecial_t"];
        $strEstrategia_t = $_POST["strEstrategia_t"];
        $intPasoCampan = (isset($_POST["intPasoCampan"])) ? $_POST["intPasoCampan"] : 0;
        $strValue = $_POST['strValue'];

        if ($strEspecial_t) {

            if ($strEspecial_t == "estado") {

                $strSQLLista_t = "SELECT LISOPC_ConsInte__b AS id, LISOPC_Nombre____b AS nombre FROM ".$BaseDatos_systema.".LISOPC  JOIN ".$BaseDatos_systema.".OPCION ON LISOPC_ConsInte__OPCION_b = OPCION_ConsInte__b WHERE OPCION_Nombre____b = 'ESTADO_DY_".$intIdCampo_t."' ORDER BY LISOPC_ConsInte__b ASC";
                $defaultList = "SELECT LISOPC_ConsInte__b AS id, LISOPC_Nombre____b AS nombre FROM ".$BaseDatos_systema.".LISOPC  WHERE  LISOPC_ConsInte__b IN  (-10,-11,-12,-13,-14,-15,-16,-17,-18,-19,-20,-21)  ORDER BY LISOPC_ConsInte__b ASC";
                $resDefault = $mysqli->query($defaultList);
                $resSQLLista_t = $mysqli->query($strSQLLista_t);

                if ($resSQLLista_t->num_rows > 0) {

                    while ($obj = $resSQLLista_t->fetch_object()) {

                        echo '<option value="'.$obj->id.'">'.aliasColumna($obj->nombre).'</option>';

                    }

                } 

                if ($resDefault->num_rows > 0) {
                    while ($obj = $resDefault->fetch_object()) {

                        echo '<option value="'.$obj->id.'">'.aliasColumna($obj->nombre).'</option>';

                    }
                }

            } else if ($strEspecial_t == "monoef") {

                $strSQLLista_t = "SELECT LISOPC_Clasifica_b AS id, LISOPC_Nombre____b AS nombre, OP.CAMPAN_Nombre____b AS campana FROM ".$BaseDatos_systema.".LISOPC JOIN (SELECT OPCION_ConsInte__b, CAMPAN_Nombre____b FROM ".$BaseDatos_systema.".CAMPAN JOIN ".$BaseDatos_systema.".OPCION ON CAMPAN_ConsInte__GUION__Gui_b = OPCION_ConsInte__GUION__b  WHERE CAMPAN_ConsInte__GUION__Pob_b = ".$intIdCampo_t." AND OPCION_Nombre____b LIKE 'Tipificaciones - %' GROUP BY CAMPAN_ConsInte__GUION__Gui_b) AS OP ON LISOPC_ConsInte__OPCION_b = OP.OPCION_ConsInte__b";

                // Si viene con la variable de la campaña entonces traemos las tipificaciones especificas de la campaña
                if($intPasoCampan){
                    $strSQLLista_t = "SELECT LISOPC_Clasifica_b AS id, LISOPC_Nombre____b AS nombre FROM ".$BaseDatos_systema.".LISOPC JOIN ".$BaseDatos_systema.".PREGUN ON PREGUN_ConsInte__OPCION_B = LISOPC_ConsInte__OPCION_b JOIN ".$BaseDatos_systema.".MONOEF ON MONOEF_ConsInte__b = LISOPC_Clasifica_b WHERE PREGUN_ConsInte__b = (select GUION__ConsInte__PREGUN_Tip_b from ".$BaseDatos_systema.".ESTPAS JOIN ".$BaseDatos_systema.".CAMPAN ON ESTPAS_ConsInte__CAMPAN_b = CAMPAN_ConsInte__b JOIN ".$BaseDatos_systema.".GUION_ G ON GUION__ConsInte__b = CAMPAN_ConsInte__GUION__Gui_b  WHERE ESTPAS_ConsInte__b = '{$intPasoCampan}') AND LISOPC_Borrado_b=0 ORDER BY LISOPC_Nombre____b ASC";
                }



                $resSQLLista_t = $mysqli->query($strSQLLista_t);

                if ($resSQLLista_t->num_rows > 0) {

                    while ($obj = $resSQLLista_t->fetch_object()) {
                        $strCampan = (!$intPasoCampan) ? " - {$obj->campana}" : "";
                        echo '<option value="'.$obj->id.'">'.aliasColumna($obj->nombre.$strCampan).'</option>';
                        

                    }

                }

            }else if ($strEspecial_t == "usu") {

                $strSQLLista_t = "SELECT ASITAR_ConsInte__USUARI_b AS id, USUARI_Nombre____b AS nombre FROM ".$BaseDatos_systema.".ASITAR JOIN (SELECT CAMPAN_ConsInte__b FROM ".$BaseDatos_systema.".CAMPAN WHERE CAMPAN_ConsInte__GUION__Pob_b = ".$intIdCampo_t.") AS CAM ON ASITAR_ConsInte__CAMPAN_b = CAM.CAMPAN_ConsInte__b JOIN ".$BaseDatos_systema.".USUARI ON ASITAR_ConsInte__USUARI_b = USUARI_ConsInte__b GROUP BY ASITAR_ConsInte__USUARI_b";

                $resSQLLista_t = $mysqli->query($strSQLLista_t);

                // Agrego una opcion por defecto que sea vacio
                echo '<option value="">VACIO</option>';

                if ($resSQLLista_t->num_rows > 0) {

                    while ($obj = $resSQLLista_t->fetch_object()) {

                        echo '<option value="'.$obj->id.'">'.aliasColumna($obj->nombre).'</option>';

                    }

                }

            }else if ($strEspecial_t == "usu_campan") {

                $strSQLLista_t = "SELECT ASITAR_ConsInte__USUARI_b AS id, USUARI_Nombre____b AS nombre FROM ".$BaseDatos_systema.".ASITAR JOIN ".$BaseDatos_systema.".USUARI ON ASITAR_ConsInte__USUARI_b = USUARI_ConsInte__b  where ASITAR_ConsInte__CAMPAN_b = ".$intIdCampo_t." GROUP BY ASITAR_ConsInte__USUARI_b";
                $resSQLLista_t = $mysqli->query($strSQLLista_t);

                if ($resSQLLista_t->num_rows > 0) {

                    while ($obj = $resSQLLista_t->fetch_object()) {

                        echo '<option value="'.$obj->id.'">'.aliasColumna($obj->nombre).'</option>';

                    }

                }

            }else if ($strEspecial_t == "usu_tel") {
                $strSQLLista_t = "SELECT TEL.id AS id, USUARI_Nombre____b AS nombre FROM ".$BaseDatos_systema.".ASITAR JOIN (SELECT CAMPAN_ConsInte__b FROM ".$BaseDatos_systema.".CAMPAN WHERE CAMPAN_ConsInte__GUION__Pob_b = ".$intIdCampo_t.") AS CAM ON ASITAR_ConsInte__CAMPAN_b = CAM.CAMPAN_ConsInte__b JOIN ".$BaseDatos_systema.".USUARI ON ASITAR_ConsInte__USUARI_b = USUARI_ConsInte__b  JOIN ".$BaseDatos_telefonia.".dy_agentes TEL ON ASITAR_UsuarioCBX_b =  TEL.id_usuario_asociado   GROUP BY ASITAR_ConsInte__USUARI_b";

                $resSQLLista_t = $mysqli->query($strSQLLista_t);

                if ($resSQLLista_t->num_rows > 0) {

                    while ($obj = $resSQLLista_t->fetch_object()) {

                        echo '<option value="'.$obj->id.'">'.aliasColumna($obj->nombre).'</option>';

                    }

                }

            }else if($strEspecial_t == "estrat_paso"){
                
                $sql = "SELECT ESTPAS_ConsInte__b AS id, ESTPAS_Comentari_b AS nombre, ESTPAS_Tipo______b AS tipo FROM DYALOGOCRM_SISTEMA.ESTPAS JOIN DYALOGOCRM_SISTEMA.ESTRAT ON ESTPAS_ConsInte__ESTRAT_b = ESTRAT_ConsInte__b
                WHERE md5(concat('".clave_get."', ESTRAT_ConsInte__b)) = '".$strEstrategia_t."'
                AND ESTPAS_Tipo______b IN (1, 4, 5, 6, 9, 10, 11, 19)" ;

                $res = $mysqli->query($sql);

                if($res && $res->num_rows > 0){

                    while($obj = $res->fetch_object()){

                        if($obj->tipo == '5'){
                            $nombrePaso = "Cargador_Datos_".$obj->id;
                        }else{
                            $nombrePaso = $obj->nombre;
                        }

                        echo '<option value="'.$obj->id.'">'.aliasColumna($nombrePaso).'</option>';
                    }
                    echo '<option value="0">INSERTADO FUERA DEL PROCESO</option>';

                }

            }else if($strEspecial_t == "ivr_raiz"){
                
                $sql = "SELECT nombre_raiz FROM {$BaseDatos_telefonia}.dy_ivrs JOIN {$BaseDatos_systema}.ESTRAT ON id_proyecto = ESTRAT_ConsInte__PROYEC_b where '{$strEstrategia_t}' = md5(CONCAT('".clave_get."', ESTRAT_ConsInte__b)) GROUP BY nombre_raiz" ;

                $res = $mysqli->query($sql);

                if($res && $res->num_rows > 0){

                    while($obj = $res->fetch_object()){

                        echo '<option value="'.$obj->nombre_raiz.'">'.$obj->nombre_raiz.'</option>';
                    }

                }

            }else if($strEspecial_t == "ivr_opcion"){
                
                $sql = "SELECT nombre_usuario_ivr FROM {$BaseDatos_telefonia}.dy_ivrs JOIN {$BaseDatos_systema}.ESTRAT ON id_proyecto = ESTRAT_ConsInte__PROYEC_b where '{$strEstrategia_t}' = md5(CONCAT('".clave_get."', ESTRAT_ConsInte__b)) GROUP BY nombre_usuario_ivr" ;

                $res = $mysqli->query($sql);

                if($res && $res->num_rows > 0){

                    while($obj = $res->fetch_object()){

                        echo '<option value="'.$obj->nombre_usuario_ivr.'">'.$obj->nombre_usuario_ivr.'</option>';
                    }

                }

            }else if($strEspecial_t == "campanProyecto"){
                
                $sql = "SELECT c.id, c.nombre_usuario FROM {$BaseDatos_telefonia}.dy_proyectos p  JOIN {$BaseDatos_systema}.ESTRAT e ON  e.ESTRAT_ConsInte__PROYEC_b = p.id_huesped JOIN {$BaseDatos_telefonia}.dy_campanas c ON p.id = c.id_proyecto WHERE MD5(CONCAT('".clave_get."', e.ESTRAT_ConsInte__b)) = '{$strEstrategia_t}' AND c.tipo_campana = '1'" ;

                $res = $mysqli->query($sql);

                if($res && $res->num_rows > 0){

                    while($obj = $res->fetch_object()){

                        echo '<option value="'.$obj->id.'">'.$obj->nombre_usuario.'</option>';
                    }

                }

            } elseif ($strEspecial_t == "origen_dy_wf") {
                $sql = "SELECT b.".$strValue." AS origen_dy_wf FROM " . $BaseDatos . ".G".$intIdCampo_t." AS b WHERE ".$strValue." IS NOT NULL GROUP BY ".$strValue."";
                $resp = $mysqli->query($sql);
                if ($resp->num_rows > 0) {

                    while($obj = $resp->fetch_object()){
                        $dy_value = "(NULL)";
                        $dy_valueText = "Registros nulos";
                        if (!empty($obj->origen_dy_wf)) {
                            $dy_value = $obj->origen_dy_wf;
                            $dy_valueText = $obj->origen_dy_wf;
                        }

                        echo '<option value="'.$dy_value.'">'.$dy_valueText.'</option>';
                    }
                } else {
                    echo '<option value="">sin datos</option>';
                }
            }


        }else{

            $strSQLLista_t = "SELECT LISOPC_ConsInte__b AS id, LISOPC_Nombre____b AS nombre from ".$BaseDatos_systema.".LISOPC JOIN ".$BaseDatos_systema.".PREGUN ON LISOPC_ConsInte__OPCION_b = PREGUN_ConsInte__OPCION_B WHERE PREGUN_ConsInte__b = ".$intIdCampo_t."";

            $resSQLLista_t = $mysqli->query($strSQLLista_t);

            if ($resSQLLista_t->num_rows > 0) {
               
                while ($obj = $resSQLLista_t->fetch_object()) {

                    echo '<option value="'.$obj->id.'">'.aliasColumna($obj->nombre).'</option>';

                }

            }

        }

    }

    if(isset($_GET["obtenerTipoDistribucionCampana"])){

        $campanaId = $_POST["campanId"];
        
        $sql = "SELECT CAMPAN_ConfDinam_b AS distribucion from DYALOGOCRM_SISTEMA.CAMPAN where CAMPAN_ConsInte__b = {$campanaId}";
        $res = $mysqli->query($sql);
        if($res && $res->num_rows > 0){
            $data = $res->fetch_object();
            echo json_encode(["status" => "OK", "data" => $data->distribucion]);
            exit;
        }
        echo json_encode(["status" => "OK", "data" => -1]);
        exit;
    }

if (isset($_GET["traerCamposDelReporte"])) {

    error_reporting(0);

    $tipoReport_t = $_POST["tipoReport_t"];
    $intIdHuesped_t = $_POST["intIdHuesped_t"];
    $intIdEstrat_t = $_POST["intIdEstrat_t"];
    $intIdBd_t = $_POST["intIdBd_t"];
    $intIdPaso_t = $_POST["intIdPaso_t"];
    $intIdTipo_t = $_POST["intIdTipo_t"];
    $intIdGuion_t = $_POST["intIdGuion_t"];
    $intIdCBX_t = $_POST["intIdCBX_t"];
    $intIdPeriodo_t = $_POST["intIdPeriodo_t"];
    $intIdMuestra_t = $_POST["intIdMuestra_t"];

    $arrCampos_t = [];
    $arrCamposTemp_t = [];

    switch ($tipoReport_t) {

        case 'pausas':

            $arrCampos_t[0] = ["campoId" => "fecha_hora_inicio", "nombre" => "Inicio", "tipo" => "5"];

            echo json_encode($arrCampos_t);

            break;

        case '2':

            $arrCampos_t[0] = ["campoId" => "agente_nombre", "nombre" => "Agente", "tipo" => "1"];
            $arrCampos_t[1] = ["campoId" => "fecha_hora_inicio", "nombre" => "Inicio", "tipo" => "5"];
            $arrCampos_t[2] = ["campoId" => "fecha_hora_fin", "nombre" => "Fin", "tipo" => "5"];
            $arrCampos_t[3] = ["campoId" => "tipo_descanso_nombre", "nombre" => "TipoPausa", "tipo" => "1"];
            $arrCampos_t[4] = ["campoId" => "comentario", "nombre" => "Comentario", "tipo" => "1"];

            echo json_encode($arrCampos_t);

            break;

        case '1':

            $arrCampos_t[0] = ["campoId" => "agente_nombre", "nombre" => "Agente", "tipo" => "1"];
            $arrCampos_t[1] = ["campoId" => "fecha_hora_inicio", "nombre" => "Inicio", "tipo" => "5"];
            $arrCampos_t[2] = ["campoId" => "fecha_hora_fin", "nombre" => "Fin", "tipo" => "5"];

            echo json_encode($arrCampos_t);

            break;

        case 'condia';

            $arrCampos_t = array(
                ["campoId" => "ID_BD", "nombre" => "ID_BD", "tipo" => "3"],
                ["campoId" => "FECHA_GESTION", "nombre" => "  FECHA_GESTION	", "tipo" => "5"],
                ["campoId" => "ANHO_GESTION", "nombre" => "ANHO_GESTION", "tipo" => "3"],
                ["campoId" => "MES_GESTION", "nombre" => "MES_GESTION", "tipo" => "3"],
                ["campoId" => "DIA_GESTION", "nombre" => "DIA_GESTION", "tipo" => "3"],
                ["campoId" => "HORA_GESTION", "nombre" => "HORA_GESTION", "tipo" => "3"],
                ["campoId" => "MINUTO_GESTION", "nombre" => "MINUTO_GESTION", "tipo" => "3"],
                ["campoId" => "SEGUNDO_GESTION", "nombre" => "SEGUNDO_GESTION", "tipo" => "3"],
                ["campoId" => "DURACION_GESTION", "nombre" => "DURACION_GESTION", "tipo" => "3"],
                ["campoId" => "DURACION_GESTION_SEG", "nombre" => "DURACION_GESTION_SEG", "tipo" => "3"],

                // ["campoId" => "AGENTE_ID", "nombre" => "AGENTE_ID", "tipo" => "3"],
                // ["campoId" => "AGENTE", "nombre" => "AGENTE", "tipo" => "5"],
                ["campoId" => "SENTIDO", "nombre" => "SENTIDO", "tipo" => "sentido"],
                ["campoId" => "CANAL", "nombre" => "CANAL", "tipo" => "canal"],
                ["campoId" => "ULTIMA_GESTION", "nombre" => "ULTIMA_GESTION", "tipo" => "ultimaGs"],
                ["campoId" => "CLASIFICACION", "nombre" => "CLASIFICACION", "tipo" => "clasi"],
                ["campoId" => "REINTENTO", "nombre" => "REINTENTO", "tipo" => "reintento"],
                ["campoId" => "FECHA_AGENDA", "nombre" => "FECHA_AGENDA", "tipo" => "5"],
                ["campoId" => "ANHO_AGENDA", "nombre" => "ANHO_AGENDA", "tipo" => "3"],
                
                ["campoId" => "MES_AGENDA", "nombre" => "MES_AGENDA", "tipo" => "3"],
                ["campoId" => "DIA_AGENDA", "nombre" => "DIA_AGENDA", "tipo" => "3"],
                ["campoId" => "HORA_AGENDA", "nombre" => "HORA_AGENDA", "tipo" => "3"],
                ["campoId" => "MINUTO_AGENDA", "nombre" => "MINUTO_AGENDA", "tipo" => "3"],
                ["campoId" => "SEGUNDO_AGENDA", "nombre" => "SEGUNDO_AGENDA", "tipo" => "3"],
                ["campoId" => "OBSERVACION", "nombre" => "OBSERVACION", "tipo" => "1"],
                ["campoId" => "CAMPANA", "nombre" => "CAMPANA", "tipo" => "campan"],
                ["campoId" => "ESTRATEGIA", "nombre" => "ESTRATEGIA", "tipo" => "estra"],
                ["campoId" => "FORMULARIO_GESTION", "nombre" => "FORMULARIO_GESTION", "tipo" => "formulario"],
                ["campoId" => "BASE_DATOS", "nombre" => "BASE_DATOS", "tipo" => "basedatos"],
                
            );

            echo json_encode($arrCampos_t);
            break;
        case 'calidad':
            $arrCampos_t = array(
               
                ["campoId" => "ID_GESTION", "nombre" => "ID_GESTION", "tipo" => "3"],
                ["campoId" => "FECHA_GESTION", "nombre" => "FECHA_GESTION", "tipo" => "5"],
                ["campoId" => "ANHO_GESTION", "nombre" => "ANHO_GESTION ", "tipo" => "3"],
                ["campoId" => "MES_GESTION", "nombre" => "MES_GESTION", "tipo" => "3"],
                ["campoId" => "DIA_GESTION", "nombre" => "DIA_GESTION", "tipo" => "3"],
                ["campoId" => "HORA_GESTION", "nombre" => "HORA_GESTION", "tipo" => "3"],
                ["campoId" => "MINUTO_GESTION", "nombre" => "MINUTO_GESTION", "tipo" => "3"],
                ["campoId" => "SEGUNDO_GESTION", "nombre" => "SEGUNDO_GESTION", "tipo" => "3"],
                ["campoId" => "FORMULARIO_GESTION", "nombre" => "FORMULARIO_GESTION", "tipo" => "formulario"],
                ["campoId" => "DATO_PRINCIPAL_SCRIPT", "nombre" => "DATO_PRINCIPAL_SCRIPT", "tipo" => "datoprincipal"],
                ["campoId" => "DATO_SECUNDARIO_SCRIPT", "nombre" => "DATO_SECUNDARIO_SCRIPT", "tipo" => "datosecundario"],
                ["campoId" => "FECHA_EVALUACION", "nombre" => "FECHA_EVALUACION", "tipo" => "5"],
                ["campoId" => "ANHO_EVALUACION", "nombre" => "ANHO_EVALUACION", "tipo" => "3"],
                ["campoId" => "MES_EVALUACION", "nombre" => "MES_EVALUACION", "tipo" => "3"],
                ["campoId" => "DIA_EVALUACION", "nombre" => "DIA_EVALUACION", "tipo" => "3"],
                ["campoId" => "HORA_EVALUACION", "nombre" => "HORA_EVALUACION", "tipo" => "3"],
                ["campoId" => "MINUTO_EVALUACION", "nombre" => "MINUTO_EVALUACION", "tipo" => "3"],
                ["campoId" => "SEGUNDO_EVALUACION", "nombre" => "SEGUNDO_EVALUACION", "tipo" => "3"],
                ["campoId" => "USUARIO_CALIFICA", "nombre" => "USUARIO_CALIFICA", "tipo" => "usu"],
                ["campoId" => "CALIFICACION", "nombre" => "CALIFICACION", "tipo" => "3"],
                ["campoId" => "COMENTARIO_CALIDAD", "nombre" => "COMENTARIO_CALIDAD", "tipo" => "1"],
                ["campoId" => "COMENTARIO_AGENTE", "nombre" => "COMENTARIO_AGENTE", "tipo" => "1"],

            );

            echo json_encode($arrCampos_t);
            break;
        case 'acd':
        case 'acdChat':
        case 'acdEmail':

            if ($intIdPeriodo_t == "1") {

                $arrCampos_t[0] = ["campoId" => "Fecha", "nombre" => "Fecha", "tipo" => "5"];
                $arrCampos_t[1] = ["campoId" => "Estrategia", "nombre" => "Estrategia", "tipo" => "1"];
                $arrCampos_t[2] = ["campoId" => "TSF_Tiempo", "nombre" => "TSF_Tiempo", "tipo" => "3"];
                $arrCampos_t[3] = ["campoId" => "TSF_porcentaje", "nombre" => "TSF_porcentaje", "tipo" => "3"];
                $arrCampos_t[4] = ["campoId" => "Ofrecidas", "nombre" => "Ofrecidas", "tipo" => "3"];
                $arrCampos_t[5] = ["campoId" => "Contestadas", "nombre" => "Contestadas", "tipo" => "3"];
                $arrCampos_t[6] = ["campoId" => "Cont_antes_tsf", "nombre" => "Cont_antes_tsf", "tipo" => "3"];
                $arrCampos_t[7] = ["campoId" => "Cont_despues_tsf", "nombre" => "Cont_despues_tsf", "tipo" => "3"];
                $arrCampos_t[8] = ["campoId" => "Aban_despues_tsf", "nombre" => "Aban_despues_tsf", "tipo" => "3"];
                $arrCampos_t[9] = ["campoId" => "Cont_porcentaje", "nombre" => "Cont_porcentaje", "tipo" => "1"];
                $arrCampos_t[10] = ["campoId" => "TSF", "nombre" => "TSF", "tipo" => "4"];
                $arrCampos_t[11] = ["campoId" => "TSF_Cont_antes_tsf", "nombre" => "TSF_Cont_antes_tsf", "tipo" => "4"];
                $arrCampos_t[12] = ["campoId" => "TSF_Cont_despues_tsf", "nombre" => "TSF_Cont_despues_tsf", "tipo" => "4"];
                $arrCampos_t[13] = ["campoId" => "ASA", "nombre" => "ASA", "tipo" => "10"];
                $arrCampos_t[14] = ["campoId" => "ASAMin", "nombre" => "ASAMin", "tipo" => "10"];
                $arrCampos_t[15] = ["campoId" => "ASAMax", "nombre" => "ASAMax", "tipo" => "10"];
                $arrCampos_t[16] = ["campoId" => "TSA", "nombre" => "TSA", "tipo" => "10"];
                $arrCampos_t[17] = ["campoId" => "AHT", "nombre" => "AHT", "tipo" => "10"];
                $arrCampos_t[18] = ["campoId" => "THT", "nombre" => "THT", "tipo" => "10"];
                $arrCampos_t[19] = ["campoId" => "Aban", "nombre" => "Aban", "tipo" => "3"];
                $arrCampos_t[20] = ["campoId" => "Aban_antes_tsf", "nombre" => "Aban_antes_tsf", "tipo" => "3"];
                $arrCampos_t[21] = ["campoId" => "Aban_porcentaje", "nombre" => "Aban_porcentaje", "tipo" => "4"];
                $arrCampos_t[22] = ["campoId" => "Aban_umbral_tsf", "nombre" => "Aban_umbral_tsf", "tipo" => "4"];
                $arrCampos_t[23] = ["campoId" => "Aban_espera", "nombre" => "Aban_espera", "tipo" => "10"];
                $arrCampos_t[24] = ["campoId" => "Aban_espera_total", "nombre" => "Aban_espera_total", "tipo" => "10"];
                $arrCampos_t[25] = ["campoId" => "Aban_espera_min", "nombre" => "Aban_espera_min", "tipo" => "10"];
                $arrCampos_t[26] = ["campoId" => "Aban_espera_max", "nombre" => "Aban_espera_max", "tipo" => "10"];
                $arrCampos_t[27] = ["campoId" => "transfer", "nombre" => "transfer", "tipo" => "3"];
            } else if ($intIdPeriodo_t == "2") {

                $arrCampos_t[0] = ["campoId" => "Fecha", "nombre" => "Fecha", "tipo" => "5"];
                $arrCampos_t[1] = ["campoId" => "Intervalo", "nombre" => "Intervalo", "tipo" => "1"];
                $arrCampos_t[2] = ["campoId" => "Estrategia", "nombre" => "Estrategia", "tipo" => "1"];
                $arrCampos_t[3] = ["campoId" => "TSF_Tiempo", "nombre" => "TSF_Tiempo", "tipo" => "3"];
                $arrCampos_t[4] = ["campoId" => "TSF_porcentaje", "nombre" => "TSF_porcentaje", "tipo" => "3"];
                $arrCampos_t[5] = ["campoId" => "Ofrecidas", "nombre" => "Ofrecidas", "tipo" => "3"];
                $arrCampos_t[6] = ["campoId" => "Contestadas", "nombre" => "Contestadas", "tipo" => "3"];
                $arrCampos_t[7] = ["campoId" => "Cont_antes_tsf", "nombre" => "Cont_antes_tsf", "tipo" => "3"];
                $arrCampos_t[8] = ["campoId" => "Cont_despues_tsf", "nombre" => "Cont_despues_tsf", "tipo" => "3"];
                $arrCampos_t[9] = ["campoId" => "Aban_despues_tsf", "nombre" => "Aban_despues_tsf", "tipo" => "3"];
                $arrCampos_t[10] = ["campoId" => "Cont_porcentaje", "nombre" => "Cont_porcentaje", "tipo" => "1"];
                $arrCampos_t[11] = ["campoId" => "TSF", "nombre" => "TSF", "tipo" => "4"];
                $arrCampos_t[12] = ["campoId" => "TSF_Cont_antes_tsf", "nombre" => "TSF_Cont_antes_tsf", "tipo" => "4"];
                $arrCampos_t[13] = ["campoId" => "TSF_Cont_despues_tsf", "nombre" => "TSF_Cont_despues_tsf", "tipo" => "4"];
                $arrCampos_t[14] = ["campoId" => "ASA", "nombre" => "ASA", "tipo" => "10"];
                $arrCampos_t[15] = ["campoId" => "ASAMin", "nombre" => "ASAMin", "tipo" => "10"];
                $arrCampos_t[16] = ["campoId" => "ASAMax", "nombre" => "ASAMax", "tipo" => "10"];
                $arrCampos_t[17] = ["campoId" => "TSA", "nombre" => "TSA", "tipo" => "10"];
                $arrCampos_t[18] = ["campoId" => "AHT", "nombre" => "AHT", "tipo" => "10"];
                $arrCampos_t[19] = ["campoId" => "THT", "nombre" => "THT", "tipo" => "10"];
                $arrCampos_t[20] = ["campoId" => "Aban", "nombre" => "Aban", "tipo" => "3"];
                $arrCampos_t[21] = ["campoId" => "Aban_antes_tsf", "nombre" => "Aban_antes_tsf", "tipo" => "3"];
                $arrCampos_t[22] = ["campoId" => "Aban_porcentaje", "nombre" => "Aban_porcentaje", "tipo" => "4"];
                $arrCampos_t[23] = ["campoId" => "Aban_umbral_tsf", "nombre" => "Aban_umbral_tsf", "tipo" => "4"];
                $arrCampos_t[24] = ["campoId" => "Aban_espera", "nombre" => "Aban_espera", "tipo" => "10"];
                $arrCampos_t[25] = ["campoId" => "Aban_espera_total", "nombre" => "Aban_espera_total", "tipo" => "10"];
                $arrCampos_t[26] = ["campoId" => "Aban_espera_min", "nombre" => "Aban_espera_min", "tipo" => "10"];
                $arrCampos_t[27] = ["campoId" => "Aban_espera_max", "nombre" => "Aban_espera_max", "tipo" => "10"];
            }


            if($tipoReport_t == "acdChat"){
                
                // Remplazamons los nombres en donde las columnas son diferente
                $arrCampos_t[array_search('Ofrecidas', array_column($arrCampos_t, "campoId"))] = ["campoId" => "Ofrecidos", "nombre" => "Ofrecidos", "tipo" => "3"];
                $arrCampos_t[array_search('Contestadas', array_column($arrCampos_t, "campoId"))] = ["campoId" => "Contestados", "nombre" => "Contestados", "tipo" => "3"];

                // Eliminamos las columnas que no aplican 
                array_splice($arrCampos_t, array_search('Estrategia', array_column($arrCampos_t, "campoId")),1);

                if(array_search('transfer', array_column($arrCampos_t, "campoId"))){
                    array_splice($arrCampos_t, array_search('transfer', array_column($arrCampos_t, "campoId")),1);
                }

            }else if($tipoReport_t == "acdEmail"){
                // Remplazamons los nombres en donde las columnas son diferente
                $arrCampos_t[array_search('Ofrecidas', array_column($arrCampos_t, "campoId"))] = ["campoId" => "Ofrecidos", "nombre" => "Ofrecidos", "tipo" => "3"];
                $arrCampos_t[array_search('Contestadas', array_column($arrCampos_t, "campoId"))] = ["campoId" => "Contestados", "nombre" => "Contestados", "tipo" => "3"];

                // Eliminamos las columnas que no aplican 
                array_splice($arrCampos_t, array_search('Estrategia', array_column($arrCampos_t, "campoId")),1);

                if(array_search('transfer', array_column($arrCampos_t, "campoId"))){
                    array_splice($arrCampos_t, array_search('transfer', array_column($arrCampos_t, "campoId")),1);
                }
                
                array_splice($arrCampos_t, array_search('Aban_despues_tsf', array_column($arrCampos_t, "campoId")),1);


                array_splice($arrCampos_t, array_search('Aban', array_column($arrCampos_t, "campoId")));

            }

            echo json_encode($arrCampos_t);


            break;
        case 'bd':

            $arrCamposDinamicos_t = columnasDinamicas($intIdBd_t);

            $arrCampos_t[0] = ["campoId" => "G" . $intIdBd_t . "_ConsInte__b", "nombre" => "ID_DY", "tipo" => "3", "idbg" => $intIdBd_t];
            $arrCampos_t[1] = ["campoId" => "G" . $intIdBd_t . "_FechaInsercion", "nombre" => "FECHA_CREACION_DY", "tipo" => "5", "idbg" => $intIdBd_t];
            $arrCampos_t[2] = ["campoId" => "DATE_FORMAT(G" . $intIdBd_t . "_FechaInsercion,'%Y')", "nombre" => "ANHO_CREACION_DY", "tipo" => "3", "idbg" => $intIdBd_t];
            $arrCampos_t[3] = ["campoId" => "DATE_FORMAT(G" . $intIdBd_t . "_FechaInsercion,'%m')", "nombre" => "MES_CREACION_DY", "tipo" => "3", "idbg" => $intIdBd_t];
            $arrCampos_t[4] = ["campoId" => "DATE_FORMAT(G" . $intIdBd_t . "_FechaInsercion,'%d')", "nombre" => "DIA_CREACION_DY", "tipo" => "3", "idbg" => $intIdBd_t];
            $arrCampos_t[5] = ["campoId" => "DATE_FORMAT(G" . $intIdBd_t . "_FechaInsercion,'%H')", "nombre" => "HORA_CREACION_DY", "tipo" => "3", "idbg" => $intIdBd_t];

            $arrCampos_t[6] = ["campoId" => "G" . $intIdBd_t . "_FechaUltimoCargue", "nombre" => "FECHA_ULTIMO_CARGUE_DY", "tipo" => "5", "idbg" => $intIdBd_t];
            $arrCampos_t[7] = ["campoId" => "G" . $intIdBd_t . "_OrigenUltimoCargue", "nombre" => "ORIGEN_ULT_CARGUE", "tipo" => "1", "idbg" => $intIdBd_t, "campoLista" => "G" . $intIdBd_t . "_OrigenUltimoCargue"];


            foreach ($arrCamposDinamicos_t as $value) {

                if ($value["tipo"] == "1" && $value["nombre"] == "ORIGEN_DY_WF") {

                    $arrCampos_t[] = ["campoId" => $value["campoId"], "nombre" => "ORIGEN_DY_WF", "tipo" => "1", "idbg" => $intIdBd_t, "idpregun" => $value["ID"], "campoLista" => $value["campoId"]];
                    $arrCampos_t[count($arrCampos_t)] = ["campoId" => "G" . $intIdBd_t . "_PoblacionOrigen", "nombre" => "PASO", "tipo" => "estrat_paso", "idbg" => $intIdBd_t];
                    $arrCampos_t[count($arrCampos_t)] = ["campoId" => "G" . $intIdBd_t . "_PasoGMI_b", "nombre" => "PASO_GMI_DY", "tipo" => "3", "idbg" => $intIdBd_t];
                    $arrCampos_t[count($arrCampos_t)] = ["campoId" => "G" . $intIdBd_t . "_EstadoGMI_b", "nombre" => "ESTADO_GMI_DY", "tipo" => "estado", "idbg" => $intIdBd_t, "campoLista" => "G" . $intIdBd_t . "_EstadoGMI_b"];
                    $arrCampos_t[count($arrCampos_t)] = ["campoId" => "G" . $intIdBd_t . "_GesMasImp_b", "nombre" => "GESTION_MAS_IMPORTANTE_DY", "tipo" => "monoef", "idbg" => $intIdBd_t];
                    $arrCampos_t[count($arrCampos_t)] = ["campoId" => "G" . $intIdBd_t . "_ClasificacionGMI_b", "nombre" => "CLASIFICACION_GMI_DY", "tipo" => "clasi", "idbg" => $intIdBd_t];
                    $arrCampos_t[count($arrCampos_t)] = ["campoId" => "G" . $intIdBd_t . "_TipoReintentoGMI_b", "nombre" => "REINTENTO_GMI_DY", "tipo" => "reintento", "idbg" => $intIdBd_t];
                    $arrCampos_t[count($arrCampos_t)] = ["campoId" => "G" . $intIdBd_t . "_FecHorAgeGMI_b", "nombre" => "FECHA_AGENDA_GMI_DY", "tipo" => "5", "idbg" => $intIdBd_t];
                    $arrCampos_t[count($arrCampos_t)] = ["campoId" => "DATE_FORMAT(G" . $intIdBd_t . "_FecHorAgeGMI_b,'%Y')", "nombre" => "ANHO_AGENDA_GMI_DY", "tipo" => "3", "idbg" => $intIdBd_t];
                    $arrCampos_t[count($arrCampos_t)] = ["campoId" => "DATE_FORMAT(G" . $intIdBd_t . "_FecHorAgeGMI_b,'%m')", "nombre" => "MES_AGENDA_GMI_DY", "tipo" => "3", "idbg" => $intIdBd_t];
                    $arrCampos_t[count($arrCampos_t)] = ["campoId" => "DATE_FORMAT(G" . $intIdBd_t . "_FecHorAgeGMI_b,'%d')", "nombre" => "DIA_AGENDA_GMI_DY", "tipo" => "3", "idbg" => $intIdBd_t];
                    $arrCampos_t[count($arrCampos_t)] = ["campoId" => "DATE_FORMAT(G" . $intIdBd_t . "_FecHorAgeGMI_b,'%H')", "nombre" => "HORA_AGENDA_GMI_DY", "tipo" => "3", "idbg" => $intIdBd_t];
                    $arrCampos_t[count($arrCampos_t)] = ["campoId" => "G" . $intIdBd_t . "_ComentarioGMI_b", "nombre" => "COMENTARIO_GMI_DY", "tipo" => "1", "idbg" => $intIdBd_t];
                    $arrCampos_t[count($arrCampos_t)] = ["campoId" => "G" . $intIdBd_t . "_UsuarioGMI_b", "nombre" => "AGENTE_GMI_DY", "tipo" => "usu", "idbg" => $intIdBd_t];
                    $arrCampos_t[count($arrCampos_t)] = ["campoId" => "G" . $intIdBd_t . "_CanalGMI_b", "nombre" => "CANAL_GMI_DY", "tipo" => "canal", "idbg" => $intIdBd_t, "campoLista" => "G" . $intIdBd_t . "_CanalGMI_b"];
                    $arrCampos_t[count($arrCampos_t)] = ["campoId" => "G" . $intIdBd_t . "_SentidoGMI_b", "nombre" => "SENTIDO_GMI_DY", "tipo" => "sentido", "idbg" => $intIdBd_t];
                    $arrCampos_t[count($arrCampos_t)] = ["campoId" => "G" . $intIdBd_t . "_FeGeMaIm__b", "nombre" => "FECHA_GMI_DY", "tipo" => "5", "idbg" => $intIdBd_t];
                    $arrCampos_t[count($arrCampos_t)] = ["campoId" => "DATE_FORMAT(G" . $intIdBd_t . "_FeGeMaIm__b,'%Y')", "nombre" => "ANHO_GMI_DY", "tipo" => "3", "idbg" => $intIdBd_t];
                    $arrCampos_t[count($arrCampos_t)] = ["campoId" => "DATE_FORMAT(G" . $intIdBd_t . "_FeGeMaIm__b,'%m')", "nombre" => "MES_GMI_DY", "tipo" => "3", "idbg" => $intIdBd_t];
                    $arrCampos_t[count($arrCampos_t)] = ["campoId" => "DATE_FORMAT(G" . $intIdBd_t . "_FeGeMaIm__b,'%d')", "nombre" => "DIA_GMI_DY", "tipo" => "3", "idbg" => $intIdBd_t];
                    $arrCampos_t[count($arrCampos_t)] = ["campoId" => "DATE_FORMAT(G" . $intIdBd_t . "_FeGeMaIm__b,'%H')", "nombre" => "HORA_GMI_DY", "tipo" => "3", "idbg" => $intIdBd_t];
                    $arrCampos_t[count($arrCampos_t)] = ["campoId" => "(G" . $intIdBd_t . "_FeGeMaIm__b-G" . $intIdBd_t . "_FechaInsercion)", "nombre" => "DIAS_MADURACION_GMI_DY", "tipo" => "3", "idbg" => $intIdBd_t];
                    $arrCampos_t[count($arrCampos_t)] = ["campoId" => "G" . $intIdBd_t . "_PasoUG_b", "nombre" => "PASO_UG_DY", "tipo" => "3", "idbg" => $intIdBd_t];
                    $arrCampos_t[count($arrCampos_t)] = ["campoId" => "G" . $intIdBd_t . "_EstadoUG_b", "nombre" => "ESTADO_UG_DY", "tipo" => "estado", "idbg" => $intIdBd_t, "campoLista" => "G" . $intIdBd_t . "_EstadoUG_b"];
                    $arrCampos_t[count($arrCampos_t)] = ["campoId" => "G" . $intIdBd_t . "_UltiGest__b", "nombre" => "ULTIMA_GESTION_DY", "tipo" => "monoef", "idbg" => $intIdBd_t];
                    $arrCampos_t[count($arrCampos_t)] = ["campoId" => "G" . $intIdBd_t . "_ClasificacionUG_b", "nombre" => "CLASIFICACION_UG_DY", "tipo" => "clasi", "idbg" => $intIdBd_t];
                    $arrCampos_t[count($arrCampos_t)] = ["campoId" => "G" . $intIdBd_t . "_TipoReintentoUG_b", "nombre" => "REINTENTO_UG_DY", "tipo" => "reintento", "idbg" => $intIdBd_t];
                    $arrCampos_t[count($arrCampos_t)] = ["campoId" => "G" . $intIdBd_t . "_FecHorAgeUG_b", "nombre" => "FECHA_AGENDA_UG_DY", "tipo" => "5", "idbg" => $intIdBd_t];
                    $arrCampos_t[count($arrCampos_t)] = ["campoId" => "DATE_FORMAT(G" . $intIdBd_t . "_FecHorAgeUG_b,'%Y')", "nombre" => "ANHO_AGENDA_UG_DY", "tipo" => "3", "idbg" => $intIdBd_t];
                    $arrCampos_t[count($arrCampos_t)] = ["campoId" => "DATE_FORMAT(G" . $intIdBd_t . "_FecHorAgeUG_b,'%m')", "nombre" => "MES_AGENDA_UG_DY", "tipo" => "3", "idbg" => $intIdBd_t];
                    $arrCampos_t[count($arrCampos_t)] = ["campoId" => "DATE_FORMAT(G" . $intIdBd_t . "_FecHorAgeUG_b,'%d')", "nombre" => "DIA_AGENDA_UG_DY", "tipo" => "3", "idbg" => $intIdBd_t];
                    $arrCampos_t[count($arrCampos_t)] = ["campoId" => "DATE_FORMAT(G" . $intIdBd_t . "_FecHorAgeUG_b,'%H')", "nombre" => "HORA_AGENDA_UG_DY", "tipo" => "3", "idbg" => $intIdBd_t];
                    $arrCampos_t[count($arrCampos_t)] = ["campoId" => "G" . $intIdBd_t . "_ComentarioUG_b", "nombre" => "COMENTARIO_UG_DY", "tipo" => "1", "idbg" => $intIdBd_t];
                    $arrCampos_t[count($arrCampos_t)] = ["campoId" => "G" . $intIdBd_t . "_UsuarioUG_b", "nombre" => "AGENTE_UG_DY", "tipo" => "usu", "idbg" => $intIdBd_t];
                    $arrCampos_t[count($arrCampos_t)] = ["campoId" => "G" . $intIdBd_t . "_Canal_____b", "nombre" => "CANAL_UG_DY", "tipo" => "canal", "idbg" => $intIdBd_t, "campoLista" => "G" . $intIdBd_t . "_Canal_____b"];
                    $arrCampos_t[count($arrCampos_t)] = ["campoId" => "G" . $intIdBd_t . "_Sentido___b", "nombre" => "SENTIDO_UG_DY", "tipo" => "sentido", "idbg" => $intIdBd_t];
                    $arrCampos_t[count($arrCampos_t)] = ["campoId" => "G" . $intIdBd_t . "_FecUltGes_b", "nombre" => "FECHA_UG_DY", "tipo" => "5", "idbg" => $intIdBd_t];
                    $arrCampos_t[count($arrCampos_t)] = ["campoId" => "DATE_FORMAT(G" . $intIdBd_t . "_FecUltGes_b,'%Y')", "nombre" => "ANHO_UG_DY", "tipo" => "3", "idbg" => $intIdBd_t];
                    $arrCampos_t[count($arrCampos_t)] = ["campoId" => "DATE_FORMAT(G" . $intIdBd_t . "_FecUltGes_b,'%m')", "nombre" => "MES_UG_DY", "tipo" => "3", "idbg" => $intIdBd_t];
                    $arrCampos_t[count($arrCampos_t)] = ["campoId" => "DATE_FORMAT(G" . $intIdBd_t . "_FecUltGes_b,'%d')", "nombre" => "DIA_UG_DY", "tipo" => "3", "idbg" => $intIdBd_t];
                    $arrCampos_t[count($arrCampos_t)] = ["campoId" => "DATE_FORMAT(G" . $intIdBd_t . "_FecUltGes_b,'%H')", "nombre" => "HORA_UG_DY", "tipo" => "3", "idbg" => $intIdBd_t];
                    $arrCampos_t[count($arrCampos_t)] = ["campoId" => "G" . $intIdBd_t . "_LinkContenidoUG_b", "nombre" => "CONTENIDO_UG_DY", "tipo" => "1", "idbg" => $intIdBd_t];
                    $arrCampos_t[count($arrCampos_t)] = ["campoId" => "G" . $intIdBd_t . "_EstadoDiligenciamiento", "nombre" => "LLAVE_CARGUE_DY", "tipo" => "3", "idbg" => $intIdBd_t];
                    $arrCampos_t[count($arrCampos_t)] = ["campoId" => "G" . $intIdBd_t . "_CantidadIntentos", "nombre" => "CANTIDAD_INTENTOS_DY", "tipo" => "3", "idbg" => $intIdBd_t];
                    $arrCampos_t[count($arrCampos_t)] = ["campoId" => "G" . $intIdBd_t . "_LinkContenidoGMI_b", "nombre" => "CONTENIDO_GMI_DY", "tipo" => "1", "idbg" => $intIdBd_t];
                } else {

                    $arrCamposTemp_t[] = ["campoId" => $value["campoId"], "nombre" => $value["nombre"], "tipo" => $value["tipo"], "idbg" => $intIdBd_t, "idpregun" => $value["ID"]];
                }
            }

            foreach ($arrCamposTemp_t as $value) {

                array_push($arrCampos_t, $value);
            }


            echo json_encode($arrCampos_t);

            break;

        case 'bdpaso':

            $arrCamposDinamicos_t = columnasDinamicas($intIdBd_t);

            $arrCampos_t[0] = ["campoId" => "ID", "nombre" => "ID_DY", "tipo" => "3", "idbg" => $intIdBd_t];
            $arrCampos_t[1] = ["campoId" => "FECHA_CREACION", "nombre" => "FECHA_CREACION_DY", "tipo" => "5", "idbg" => $intIdBd_t];
            $arrCampos_t[2] = ["campoId" => "ANHO_CREACION", "nombre" => "ANHO_CREACION_DY", "tipo" => "3", "idbg" => $intIdBd_t];
            $arrCampos_t[3] = ["campoId" => "MES_CREACION", "nombre" => "MES_CREACION_DY", "tipo" => "3", "idbg" => $intIdBd_t];
            $arrCampos_t[4] = ["campoId" => "DIA_CREACION", "nombre" => "DIA_CREACION_DY", "tipo" => "3", "idbg" => $intIdBd_t];
            $arrCampos_t[5] = ["campoId" => "HORA_CREACION", "nombre" => "HORA_CREACION_DY", "tipo" => "3", "idbg" => $intIdBd_t];
            $arrCampos_t[6] = ["campoId" => "FECHA_ULT_CARGUE", "nombre" => "FECHA_ULTIMO_CARGUE_DY", "tipo" => "5", "idbg" => $intIdBd_t];
            $arrCampos_t[7] = ["campoId" => "ORIGEN_ULT_CARGUE", "nombre" => "ORIGEN_ULT_CARGUE", "tipo" => "1", "idbg" => $intIdBd_t];

            foreach ($arrCamposDinamicos_t as $value) {

                if ($value["tipo"] == "1" && $value["nombre"] == "ORIGEN_DY_WF") {

                    $arrCampos_t[] = ["campoId" => $value["nombre"], "nombre" => "ORIGEN_DY_WF", "tipo" => "1", "idbg" => $intIdBd_t, "idpregun" => $value["ID"]];
                    $arrCampos_t[count($arrCampos_t)] = ["campoId" => "Paso", "nombre" => "PASO", "tipo" => "estrat_paso", "idbg" => $intIdBd_t];
                    if ($intIdTipo_t == 7 || $intIdTipo_t == 8 || $intIdTipo_t == 10) {

                        $arrCampos_t[count($arrCampos_t)] = ["campoId" => "ACTIVO", "nombre" => "ACTIVO_DY", "tipo" => "activo", "idbg" => $intIdBd_t];
                        // $arrCampos_t[count($arrCampos_t)] = ["campoId" => "ESTADO_DY", "nombre" => "ESTADO_DY", "tipo" => "estado", "idbg" => $intIdBd_t];

                        $arrCampos_t[count($arrCampos_t)] = ["campoId" => "FECHA_CREACION_MUESTRA_DY", "nombre" => "FECHA_CREACION_MUESTRA_DY", "tipo" => "5", "idbg" => $intIdBd_t];

                        $arrCampos_t[count($arrCampos_t)] = ["campoId" => "FECHA_REACTIVACION_MUESTRA_DY", "nombre" => "FECHA_REACTIVACION_MUESTRA_DY", "tipo" => "5", "idbg" => $intIdBd_t];

                        // $arrCampos_t[count($arrCampos_t)] = ["campoId"=>"G".$intIdBd_t."_M".$intIdMuestra_t."_NumeInte__b","nombre"=>"NUMERO_INTENTOS_DY","tipo"=>"3","idbg"=>$intIdBd_t];
                        $arrCampos_t[count($arrCampos_t)] = ["campoId" => "ULTIMA_GESTION", "nombre" => "ULTIMA_GESTION_DY", "tipo" => "monoef", "idbg" => $intIdBd_t];
                        $arrCampos_t[count($arrCampos_t)] = ["campoId" => "FECHA_UG", "nombre" => "FECHA_UG_DY", "tipo" => "5", "idbg" => $intIdBd_t];
                        $arrCampos_t[count($arrCampos_t)] = ["campoId" => "ANHO_UG", "nombre" => "ANHO_UG_DY", "tipo" => "3", "idbg" => $intIdBd_t];
                        $arrCampos_t[count($arrCampos_t)] = ["campoId" => "MES_UG", "nombre" => "MES_UG_DY", "tipo" => "3", "idbg" => $intIdBd_t];
                        $arrCampos_t[count($arrCampos_t)] = ["campoId" => "DIA_UG", "nombre" => "DIA_UG_DY", "tipo" => "3", "idbg" => $intIdBd_t];
                        $arrCampos_t[count($arrCampos_t)] = ["campoId" => "HORA_UG", "nombre" => "HORA_UG_DY", "tipo" => "3", "idbg" => $intIdBd_t];
                        $arrCampos_t[count($arrCampos_t)] = ["campoId" => "CLASIFICACION_UG", "nombre" => "CLASIFICACION_UG_DY", "tipo" => "clasi", "idbg" => $intIdBd_t];
                        $arrCampos_t[count($arrCampos_t)] = ["campoId" => "AGENTE_UG", "nombre" => "USUARIO_UG_DY", "tipo" => "usu", "idbg" => $intIdBd_t];
                        $arrCampos_t[count($arrCampos_t)] = ["campoId" => "CANAL_UG", "nombre" => "CANAL_UG_DY", "tipo" => "canal", "idbg" => $intIdBd_t];
                        $arrCampos_t[count($arrCampos_t)] = ["campoId" => "SENTIDO_UG", "nombre" => "SENTIDO_UG_DY", "tipo" => "sentido", "idbg" => $intIdBd_t];
                        $arrCampos_t[count($arrCampos_t)] = ["campoId" => "REINTENTO_UG", "nombre" => "REINTENTO_UG_DY", "tipo" => "reintento", "idbg" => $intIdBd_t];
                        $arrCampos_t[count($arrCampos_t)] = ["campoId" => "CONTENIDO_UG", "nombre" => "LINK_UG_DY", "tipo" => "1", "idbg" => $intIdBd_t];
                        $arrCampos_t[count($arrCampos_t)] = ["campoId" => "COMENTARIO_UG", "nombre" => "COMENTARIO_UG_DY", "tipo" => "1", "idbg" => $intIdBd_t];
                    } else {

                        $arrCampos_t[count($arrCampos_t)] = ["campoId" => "ACTIVO", "nombre" => "ACTIVO_DY", "tipo" => "activo", "idbg" => $intIdBd_t];
                        $arrCampos_t[count($arrCampos_t)] = ["campoId" => "AGENTE_ASIGNADO", "nombre" => "AGENTE_DY", "tipo" => "usu", "idbg" => $intIdBd_t];
                        // $arrCampos_t[count($arrCampos_t)] = ["campoId"=>"G".$intIdBd_t."_M".$intIdMuestra_t."_Estado____b","nombre"=>"ESTADO_DY","tipo"=>"estado","idbg"=>$intIdBd_t];
                        // $arrCampos_t[count($arrCampos_t)] = ["campoId"=>"G".$intIdBd_t."_M".$intIdMuestra_t."_NumeInte__b","nombre"=>"NUMERO_INTENTOS_DY","tipo"=>"3","idbg"=>$intIdBd_t];
                        $arrCampos_t[count($arrCampos_t)] = ["campoId" => "FECHA_CREACION_MUESTRA_DY", "nombre" => "FECHA_CREACION_MUESTRA_DY", "tipo" => "5", "idbg" => $intIdBd_t];

                        $arrCampos_t[count($arrCampos_t)] = ["campoId" => "FECHA_REACTIVACION_MUESTRA_DY", "nombre" => "FECHA_REACTIVACION_MUESTRA_DY", "tipo" => "5", "idbg" => $intIdBd_t];

                        $arrCampos_t[count($arrCampos_t)] = ["campoId" => "GESTION_MAS_IMPORTANTE", "nombre" => "GESTION_MAS_IMPORTANTE_DY", "tipo" => "monoef", "idbg" => $intIdBd_t];
                        $arrCampos_t[count($arrCampos_t)] = ["campoId" => "FECHA_GMI", "nombre" => "FECHA_GMI_DY", "tipo" => "5", "idbg" => $intIdBd_t];
                        $arrCampos_t[count($arrCampos_t)] = ["campoId" => "ANHO_GMI", "nombre" => "ANHO_GMI_DY", "tipo" => "3", "idbg" => $intIdBd_t];
                        $arrCampos_t[count($arrCampos_t)] = ["campoId" => "MES_GMI", "nombre" => "MES_GMI_DY", "tipo" => "3", "idbg" => $intIdBd_t];
                        $arrCampos_t[count($arrCampos_t)] = ["campoId" => "DIA_GMI", "nombre" => "DIA_GMI_DY", "tipo" => "3", "idbg" => $intIdBd_t];
                        $arrCampos_t[count($arrCampos_t)] = ["campoId" => "HORA_GMI", "nombre" => "HORA_GMI_DY", "tipo" => "3", "idbg" => $intIdBd_t];
                        $arrCampos_t[count($arrCampos_t)] = ["campoId" => "CLASIFICACION_GMI", "nombre" => "CLASIFICACION_GMI_DY", "tipo" => "clasi", "idbg" => $intIdBd_t];
                        $arrCampos_t[count($arrCampos_t)] = ["campoId" => "AGENTE_GMI", "nombre" => "USUARIO_GMI_DY", "tipo" => "usu", "idbg" => $intIdBd_t];
                        $arrCampos_t[count($arrCampos_t)] = ["campoId" => "CANAL_GMI", "nombre" => "CANAL_GMI_DY", "tipo" => "canal", "idbg" => $intIdBd_t];
                        $arrCampos_t[count($arrCampos_t)] = ["campoId" => "REINTENTO_GMI", "nombre" => "REINTENTO_GMI_DY", "tipo" => "reintento", "idbg" => $intIdBd_t];
                        $arrCampos_t[count($arrCampos_t)] = ["campoId" => "SENTIDO_GMI", "nombre" => "SENTIDO_GMI_DY", "tipo" => "sentido", "idbg" => $intIdBd_t];
                        $arrCampos_t[count($arrCampos_t)] = ["campoId" => "CONTENIDO_GMI", "nombre" => "LINK_GMI_DY", "tipo" => "1", "idbg" => $intIdBd_t];
                        $arrCampos_t[count($arrCampos_t)] = ["campoId" => "REINTENTO_GMI", "nombre" => "TIPO_REINTENTO_GMI_DY", "tipo" => "reintento", "idbg" => $intIdBd_t];
                        // $arrCampos_t[count($arrCampos_t)] = ["campoId"=>"G".$intIdBd_t."_M".$intIdMuestra_t."_CantidadIntentosGMI_b","nombre"=>"NUMERO_INTENTOS_GMI_DY","tipo"=>"3","idbg"=>$intIdBd_t];
                        $arrCampos_t[count($arrCampos_t)] = ["campoId" => "COMENTARIO_GMI", "nombre" => "COMENTARIO_GMI_DY", "tipo" => "1", "idbg" => $intIdBd_t];
                        $arrCampos_t[count($arrCampos_t)] = ["campoId" => "ULTIMA_GESTION", "nombre" => "ULTIMA_GESTION_DY", "tipo" => "monoef", "idbg" => $intIdBd_t];
                        $arrCampos_t[count($arrCampos_t)] = ["campoId" => "FECHA_UG", "nombre" => "FECHA_UG_DY", "tipo" => "5", "idbg" => $intIdBd_t];
                        $arrCampos_t[count($arrCampos_t)] = ["campoId" => "ANHO_UG", "nombre" => "ANHO_UG_DY", "tipo" => "3", "idbg" => $intIdBd_t];
                        $arrCampos_t[count($arrCampos_t)] = ["campoId" => "MES_UG", "nombre" => "MES_UG_DY", "tipo" => "3", "idbg" => $intIdBd_t];
                        $arrCampos_t[count($arrCampos_t)] = ["campoId" => "DIA_UG", "nombre" => "DIA_UG_DY", "tipo" => "3", "idbg" => $intIdBd_t];
                        $arrCampos_t[count($arrCampos_t)] = ["campoId" => "HORA_UG", "nombre" => "HORA_UG_DY", "tipo" => "3", "idbg" => $intIdBd_t];
                        $arrCampos_t[count($arrCampos_t)] = ["campoId" => "CLASIFICACION_UG", "nombre" => "CLASIFICACION_UG_DY", "tipo" => "clasi", "idbg" => $intIdBd_t];
                        $arrCampos_t[count($arrCampos_t)] = ["campoId" => "AGENTE_UG", "nombre" => "USUARIO_UG_DY", "tipo" => "usu", "idbg" => $intIdBd_t];
                        $arrCampos_t[count($arrCampos_t)] = ["campoId" => "CANAL_UG", "nombre" => "CANAL_UG_DY", "tipo" => "canal", "idbg" => $intIdBd_t];
                        $arrCampos_t[count($arrCampos_t)] = ["campoId" => "SENTIDO_UG", "nombre" => "SENTIDO_UG_DY", "tipo" => "sentido", "idbg" => $intIdBd_t];
                        $arrCampos_t[count($arrCampos_t)] = ["campoId" => "REINTENTO_UG", "nombre" => "REINTENTO_UG_DY", "tipo" => "reintento", "idbg" => $intIdBd_t];
                        $arrCampos_t[count($arrCampos_t)] = ["campoId" => "CONTENIDO_UG", "nombre" => "LINK_UG_DY", "tipo" => "1", "idbg" => $intIdBd_t];
                        // $arrCampos_t[count($arrCampos_t)] = ["campoId"=>"G".$intIdBd_t."_M".$intIdMuestra_t."_FecHorMinProGes__b","nombre"=>"FECHA_MINIMA_PROXIMO_INTENTO_DY","tipo"=>"5","idbg"=>$intIdBd_t];
                        // $arrCampos_t[count($arrCampos_t)] = ["campoId"=>"G".$intIdBd_t."_M".$intIdMuestra_t."_Comentari_b","nombre"=>"COMENTARIO_DY","tipo"=>"1","idbg"=>$intIdBd_t];
                        $arrCampos_t[count($arrCampos_t)] = ["campoId" => "COMENTARIO_UG", "nombre" => "COMENTARIO_UG_DY", "tipo" => "1", "idbg" => $intIdBd_t];
                        $arrCampos_t[count($arrCampos_t)] = ["campoId" => "FECHA_AGENDA_UG", "nombre" => "FECHA_AGENDA_DY", "tipo" => "5", "idbg" => $intIdBd_t];
                        $arrCampos_t[count($arrCampos_t)] = ["campoId" => "ANHO_AGENDA_UG", "nombre" => "ANHO_AGENDA_DY", "tipo" => "3", "idbg" => $intIdBd_t];
                        $arrCampos_t[count($arrCampos_t)] = ["campoId" => "MES_AGENDA_UG", "nombre" => "MES_AGENDA_DY", "tipo" => "3", "idbg" => $intIdBd_t];
                        $arrCampos_t[count($arrCampos_t)] = ["campoId" => "DIA_AGENDA_UG", "nombre" => "DIA_AGENDA_DY", "tipo" => "3", "idbg" => $intIdBd_t];
                        $arrCampos_t[count($arrCampos_t)] = ["campoId" => "HORA_AGENDA_UG", "nombre" => "HORA_AGENDA_DY", "tipo" => "3", "idbg" => $intIdBd_t];
                    }
                } else {

                    $arrCamposTemp_t[] = ["campoId" => $value["campoId"], "nombre" => $value["nombre"], "tipo" => $value["tipo"], "idbg" => $intIdBd_t, "idpregun" => $value["ID"]];
                }
            }

            foreach ($arrCamposTemp_t as $value) {

                array_push($arrCampos_t, $value);
            }


            echo json_encode($arrCampos_t);

            break;

        case 'bkpaso':

            $arrCamposDinamicos_t = columnasDinamicas($intIdBd_t);

            $arrCampos_t[0] = ["campoId" => "G" . $intIdBd_t . "_ConsInte__b", "nombre" => "ID_DY", "tipo" => "3", "idbg" => $intIdBd_t];
            $arrCampos_t[1] = ["campoId" => "G" . $intIdBd_t . "_FechaInsercion", "nombre" => "FECHA_CREACION_DY", "tipo" => "5", "idbg" => $intIdBd_t];
            $arrCampos_t[2] = ["campoId" => "DATE_FORMAT(G" . $intIdBd_t . "_FechaInsercion,'%Y')", "nombre" => "ANHO_CREACION_DY", "tipo" => "3", "idbg" => $intIdBd_t];
            $arrCampos_t[3] = ["campoId" => "DATE_FORMAT(G" . $intIdBd_t . "_FechaInsercion,'%m')", "nombre" => "MES_CREACION_DY", "tipo" => "3", "idbg" => $intIdBd_t];
            $arrCampos_t[4] = ["campoId" => "DATE_FORMAT(G" . $intIdBd_t . "_FechaInsercion,'%d')", "nombre" => "DIA_CREACION_DY", "tipo" => "3", "idbg" => $intIdBd_t];
            $arrCampos_t[5] = ["campoId" => "DATE_FORMAT(G" . $intIdBd_t . "_FechaInsercion,'%H')", "nombre" => "HORA_CREACION_DY", "tipo" => "3", "idbg" => $intIdBd_t];

            foreach ($arrCamposDinamicos_t as $value) {

                $arrCamposTemp_t[] = ["campoId" => $value["campoId"], "nombre" => $value["nombre"], "tipo" => $value["tipo"], "idbg" => $intIdBd_t, "idpregun" => $value["ID"]];
            }

            foreach ($arrCamposTemp_t as $value) {

                array_push($arrCampos_t, $value);
            }


            echo json_encode($arrCampos_t);

            break;

        case 'gspaso':

            $arrCamposDinamicos_t = columnasDinamicas($intIdGuion_t);

            $arrGestionControl_t = ["tipificacion" => ""];


            foreach ($arrCamposDinamicos_t as $value) {
                if ($value["seccion"] == "4" || $value["seccion"] == "3") {
                    switch ($value["nombre"]) {

                        case 'TIPIFICACIN':

                            $arrGestionControl_t["tipificacion"] = $value["ID"];

                            break;
                    }
                } else {
                    $arrCamposTemp_t[] = ["campoId" => $value["campoId"], "nombre" => $value["nombre"], "tipo" => $value["tipo"], "idbg" => $intIdBd_t, "idpregun" => $value["ID"]];
                }
            }
            // Se asignan los filtros fijos

            $arrCampos_t[0] = ["campoId" => "ID", "nombre" => "ID_DY", "tipo" => "3", "idbg" => $intIdGuion_t];
            $arrCampos_t[1] = ["campoId" => "ID_BD", "nombre" => "ID_BD_DY", "tipo" => "3", "idbg" => $intIdBd_t];
            $arrCampos_t[2] = ["campoId" => "FECHA_CREACION", "nombre" => "FECHA_CREACION_DY", "tipo" => "5", "idbg" => $intIdBd_t];

            $arrCampos_t[count($arrCampos_t)] = ["campoId" => "AGENTE", "nombre" => "AGENTE_DY", "tipo" => "usu", "idbg" => $intIdBd_t];
            $arrCampos_t[count($arrCampos_t)] = ["campoId" => "FECHA_GESTION", "nombre" => "FECHA_GESTION_DY", "tipo" => "5", "idbg" => $intIdGuion_t];
            $arrCampos_t[count($arrCampos_t)] = ["campoId" => "ANHO_GESTION", "nombre" => "ANHO_GESTION_DY", "tipo" => "3", "idbg" => $intIdGuion_t];
            $arrCampos_t[count($arrCampos_t)] = ["campoId" => "MES_GESTION", "nombre" => "MES_GESTION_DY", "tipo" => "3", "idbg" => $intIdGuion_t];
            $arrCampos_t[count($arrCampos_t)] = ["campoId" => "DIA_GESTION", "nombre" => "DIA_GESTION_DY", "tipo" => "3", "idbg" => $intIdGuion_t];
            $arrCampos_t[count($arrCampos_t)] = ["campoId" => "HORA_GESTION", "nombre" => "HORA_GESTION_DY", "tipo" => "3", "idbg" => $intIdGuion_t];
            $arrCampos_t[count($arrCampos_t)] = ["campoId" => "SENTIDO", "nombre" => "SENTIDO", "tipo" => "sentido", "idbg" => $intIdGuion_t];
            $arrCampos_t[count($arrCampos_t)] = ["campoId" => "CANAL", "nombre" => "CANAL", "tipo" => "canal", "idbg" => $intIdGuion_t, "campoLista" => "G".$intIdGuion_t."_Canal_____b"];
            $arrCampos_t[count($arrCampos_t)] = ["campoId" => "TIPIFICACIN", "nombre" => "TIPIFICACIÓN_DY", "tipo" => "6", "idbg" => $intIdGuion_t, "idpregun" => $arrGestionControl_t["tipificacion"]];
            $arrCampos_t[count($arrCampos_t)] = ["campoId" => "CLASIFICACION", "nombre" => "CLASIFICACION_DY", "tipo" => "clasi", "idbg" => $intIdGuion_t];
            $arrCampos_t[count($arrCampos_t)] = ["campoId" => "REINTENTO", "nombre" => "REINTENTO_DY", "tipo" => "reintento", "idbg" => $intIdGuion_t];
            $arrCampos_t[count($arrCampos_t)] = ["campoId" => "FECHA_AGENDA", "nombre" => "FECHA_AGENDA_DY", "tipo" => "5", "idbg" => $intIdGuion_t];
            $arrCampos_t[count($arrCampos_t)] = ["campoId" => "ANHO_AGENDA", "nombre" => "ANHO_AGENDA_DY", "tipo" => "3", "idbg" => $intIdGuion_t];
            $arrCampos_t[count($arrCampos_t)] = ["campoId" => "MES_AGENDA", "nombre" => "MES_AGENDA_DY", "tipo" => "3", "idbg" => $intIdGuion_t];
            $arrCampos_t[count($arrCampos_t)] = ["campoId" => "DIA_AGENDA", "nombre" => "DIA_AGENDA_DY", "tipo" => "3", "idbg" => $intIdGuion_t];
            $arrCampos_t[count($arrCampos_t)] = ["campoId" => "HORA_AGENDA", "nombre" => "HORA_AGENDA_DY", "tipo" => "3", "idbg" => $intIdGuion_t];
            $arrCampos_t[count($arrCampos_t)] = ["campoId" => "OBSERVACION", "nombre" => "OBSERVACION_DY", "tipo" => "1", "idbg" => $intIdGuion_t];
            $arrCampos_t[count($arrCampos_t)] = ["campoId" => "DESCARGAGRABACION", "nombre" => "DESCARGAGRABACION_DY", "tipo" => "1", "idbg" => $intIdGuion_t];
            $arrCampos_t[count($arrCampos_t)] = ["campoId" => "Paso", "nombre" => "PASO_DY", "tipo" => "3", "idbg" => $intIdGuion_t];
            $arrCampos_t[count($arrCampos_t)] = ["campoId" => "OrigenGestion", "nombre" => "ORIGEN_DY", "tipo" => "1", "idbg" => $intIdGuion_t, "campoLista" => "G".$intIdGuion_t."_Origen_b"];


            foreach ($arrCamposTemp_t as $value) {

                array_push($arrCampos_t, $value);
            }


            echo json_encode($arrCampos_t);

            break;

        case 'gsbot':

            $botId = getIdTablaGestionBot($intIdPaso_t);
            $arrCamposDinamicos_t = columnasDinamicas($botId);

            $arrCampos_t = [];

            if ($botId > 0) {

                $arrCampos_t[] = ["campoId" => "G{$botId}_ConsInte__b", "nombre" => "ID_DY", "tipo" => "3", "idbg" => $botId];
                $arrCampos_t[] = ["campoId" => "G{$botId}_FechaInsercion", "nombre" => "FECHA_CREACION_DY", "tipo" => "5", "idbg" => $botId];
                $arrCampos_t[] = ["campoId" => "G{$botId}_CodigoMiembro", "nombre" => "ID_BD_DY", "tipo" => "3", "idbg" => $botId];

                $arrCampos_t[] = ["campoId" => "G" . $botId . "_Origen_b", "nombre" => "ORIGEN_DY", "tipo" => "1", "idbg" => $botId];
                $arrCampos_t[] = ["campoId" => "G" . $botId . "_IdLlamada", "nombre" => "CHAT_ENTRANTE", "tipo" => "1", "idbg" => $botId];
                $arrCampos_t[] = ["campoId" => "G" . $botId . "_Sentido___b", "nombre" => "SENTIDO", "tipo" => "sentido", "idbg" => $botId];
                $arrCampos_t[] = ["campoId" => "G" . $botId . "_Canal_____b", "nombre" => "CANAL", "tipo" => "canal", "idbg" => $botId];
                $arrCampos_t[] = ["campoId" => "G" . $botId . "_LinkContenido", "nombre" => "DESCARGAGRABACION_DY", "tipo" => "1", "idbg" => $botId];

                $arrCampos_t[] = ["campoId" => "G" . $botId . "_DatoContacto", "nombre" => "DatoContacto", "tipo" => "3", "idbg" => $botId];
                $arrCampos_t[] = ["campoId" => "G" . $botId . "_Paso", "nombre" => "Paso", "tipo" => "3", "idbg" => $botId];
                $arrCampos_t[] = ["campoId" => "G" . $botId . "_Duracion___b", "nombre" => "Duracion", "tipo" => "3", "idbg" => $botId];

                $arrCampos_t[] = ["campoId" => "G" . $botId . "_Clasificacion", "nombre" => "CLASIFICACION", "tipo" => "clasi", "idbg" => $botId];
            }

            foreach ($arrCamposDinamicos_t as $value) {

                if ($value["seccion"] == "4" || $value["seccion"] == "3") {

                    switch ($value["nombre"]) {

                        case 'TIPIFICACION':
                            $arrGestionControl_t["tipificacion"] = $value["ID"];
                            break;
                        case 'REINTENTO':
                            $arrGestionControl_t["reintento"] = $value["ID"];
                            break;
                        case 'OBSERVACION':
                            $arrGestionControl_t["observacion"] = $value["ID"];
                            break;
                    }
                } else {
                    $arrCamposTemp_t[] = ["campoId" => $value["campoId"], "nombre" => $value["nombre"], "tipo" => $value["tipo"], "idbg" => $intIdGuion_t, "idpregun" => $value["ID"]];
                }
            }

            $arrCampos_t[] = ["campoId" => "G" . $botId . "_C" . $arrGestionControl_t["tipificacion"], "idpregun" => $arrGestionControl_t["tipificacion"], "nombre" => "TIPIFICACIÓN_DY", "tipo" => "6", "idbg" => $intIdGuion_t];
            $arrCampos_t[] = ["campoId" => "G" . $botId . "_C" . $arrGestionControl_t["reintento"], "idpregun" => $arrGestionControl_t["reintento"], "nombre" => "REINTENTO_DY", "tipo" => "6", "idbg" => $intIdGuion_t];
            $arrCampos_t[] = ["campoId" => "G" . $botId . "_C" . $arrGestionControl_t["observacion"], "idpregun" => $arrGestionControl_t["observacion"], "nombre" => "OBSERVACION_DY", "tipo" => "1", "idbg" => $intIdGuion_t];

            foreach ($arrCamposTemp_t as $value) {
                array_push($arrCampos_t, $value);
            }

            echo json_encode($arrCampos_t);
            break;

        case 'opcionesUsadasBot':
            $arrCampos_t[0] = ["campoId" => "fecha_hora", "nombre" => "Fecha", "tipo" => "5"];
            echo json_encode($arrCampos_t);

            break;

        case 'comWebForm':
        case 'comMail':
            $arrCampos_t[] = ["campoId" => "ID_COMUNICACION", "nombre" => "ID_COMUNICACION", "tipo" => "3"];
            $arrCampos_t[] = ["campoId" => "FECHA_INGRESO", "nombre" => "FECHA_INGRESO", "tipo" => "5"];
            $arrCampos_t[] = ["campoId" => "AÑO_INGRESO", "nombre" => "AÑO_INGRESO", "tipo" => "3"];
            $arrCampos_t[] = ["campoId" => "MES_INGRESO", "nombre" => "MES_INGRESO", "tipo" => "3"];
            $arrCampos_t[] = ["campoId" => "DIA_INGRESO", "nombre" => "DIA_INGRESO", "tipo" => "3"];
            $arrCampos_t[] = ["campoId" => "HORA_INGRESO", "nombre" => "HORA_INGRESO", "tipo" => "3"];

            if($tipoReport_t == 'comMail'){
                $arrCampos_t[] = ["campoId" => "DE", "nombre" => "CORREO_ORIGEN", "tipo" => "1"];
            }else{
                $arrCampos_t[] = ["campoId" => "CONTACTO_CRM", "nombre" => "CONTACTO_ORIGEN", "tipo" => "1"];
            }

            $arrCampos_t[] = ["campoId" => "PASO_ASIGNADO", "nombre" => "PASO_ASIGNADO", "tipo" => "campan"];
            $arrCampos_t[] = ["campoId" => "AGENTE_ASIGNADO", "nombre" => "AGENTE_DY", "tipo" => "usu_tel" , "idbg" => $intIdBd_t];

            if($tipoReport_t == 'comMail'){
                $arrCampos_t[] = ["campoId" => "PARA", "nombre" => "PARA", "tipo" => "1"];
                $arrCampos_t[] = ["campoId" => "CC", "nombre" => "CC", "tipo" => "1"];
                $arrCampos_t[] = ["campoId" => "CCO", "nombre" => "CCO", "tipo" => "1"];
                $arrCampos_t[] = ["campoId" => "ASUNTO", "nombre" => "ASUNTO", "tipo" => "1"];
            }
            else{
                $arrCampos_t[] = ["campoId" => "CC", "nombre" => "CORREO_PARA_RESPUESTA", "tipo" => "1"];
            }
            
           
            $arrCampos_t[] = ["campoId" => "ESTADO", "nombre" => "ESTADO", "tipo" => "estado_mail"];
            $arrCampos_t[] = ["campoId" => "LEIDO", "nombre" => "LEIDO", "tipo" => "sino"];
            $arrCampos_t[] = ["campoId" => "ESTADO_GESTION", "nombre" => "ESTADO GESTION", "tipo" => "estado_ges"];
            if($tipoReport_t == 'comMail'){
                $arrCampos_t[] = ["campoId" => "FECHA_INGRESO_MAIL", "nombre" => "FECHA_INGRESO_MAIL", "tipo" => "5"];
            }
            $arrCampos_t[] = ["campoId" => "FECHA_ASIGNACION", "nombre" => "FECHA_ASIGNACION", "tipo" => "5"];
            $arrCampos_t[] = ["campoId" => "FECHA_ACCION_PROCESADA", "nombre" => "FECHA_ACCION_PROCESADA", "tipo" => "5"];
            $arrCampos_t[] = ["campoId" => "FECHA_PASO_COLA", "nombre" => "FECHA_PASO_COLA", "tipo" => "5"];
            $arrCampos_t[] = ["campoId" => "FECHA_RESPUESTA", "nombre" => "FECHA_RESPUESTA", "tipo" => "5"];
            $arrCampos_t[] = ["campoId" => "CC_RESPUESTA", "nombre" => "CC_RESPUESTA", "tipo" => "1"];

            echo json_encode($arrCampos_t);

            break;


            case 'comSms':
                $arrCampos_t[] = ["campoId" => "ID_COMUNICACION", "nombre" => "ID_DY", "tipo" => "3"];
                $arrCampos_t[] = ["campoId" => "ID_BD", "nombre" => "ID_BD_DY", "tipo" => "3"];
                $arrCampos_t[] = ["campoId" => "FECHA_INGRESO", "nombre" => "FECHA_INGRESO", "tipo" => "5"];
                $arrCampos_t[] = ["campoId" => "AÑO_INGRESO", "nombre" => "AÑO_INGRESO", "tipo" => "3"];
                $arrCampos_t[] = ["campoId" => "MES_INGRESO", "nombre" => "MES_INGRESO", "tipo" => "3"];
                $arrCampos_t[] = ["campoId" => "DIA_INGRESO", "nombre" => "DIA_INGRESO", "tipo" => "3"];
                $arrCampos_t[] = ["campoId" => "HORA_INGRESO", "nombre" => "HORA_INGRESO", "tipo" => "3"];
                $arrCampos_t[] = ["campoId" => "CONTACTO_ORIGEN", "nombre" => "CONTACTO_ORIGEN", "tipo" => "3"];
                $arrCampos_t[] = ["campoId" => "TEXTO_1", "nombre" => "TEXTO_1", "tipo" => "1"];
                $arrCampos_t[] = ["campoId" => "TEXTO_2", "nombre" => "TEXTO_2", "tipo" => "1"];
                $arrCampos_t[] = ["campoId" => "PROVEDOR", "nombre" => "PROVEDOR", "tipo" => "1"];
                $arrCampos_t[] = ["campoId" => "FECHA_INGRESO_PROVEDOR", "nombre" => "FECHA_INGRESO_PROVEDOR", "tipo" => "5"];

    
                echo json_encode($arrCampos_t);
    
                break;

            case 'comChat':
                $arrCampos_t[] = ["campoId" => "ID_COMUNICACION", "nombre" => "ID_DY", "tipo" => "3"];
                $arrCampos_t[] = ["campoId" => "FECHA_INGRESO", "nombre" => "FECHA_INGRESO", "tipo" => "5"];
                $arrCampos_t[] = ["campoId" => "AÑO_INGRESO", "nombre" => "AÑO_INGRESO", "tipo" => "3"];
                $arrCampos_t[] = ["campoId" => "MES_INGRESO", "nombre" => "MES_INGRESO", "tipo" => "3"];
                $arrCampos_t[] = ["campoId" => "DIA_INGRESO", "nombre" => "DIA_INGRESO", "tipo" => "3"];
                $arrCampos_t[] = ["campoId" => "HORA_INGRESO", "nombre" => "HORA_INGRESO", "tipo" => "3"];
                $arrCampos_t[] = ["campoId" => "DATO_CONTACTO", "nombre" => "CONTACTO_ORIGEN", "tipo" => "1"];
                $arrCampos_t[] = ["campoId" => "PASO_ASIGNADO", "nombre" => "PASO_ASIGNADO", "tipo" => "1"];
                $arrCampos_t[] = ["campoId" => "AGENTE", "nombre" => "AGENTE_DY", "tipo" => "usu_tel" , "idbg" => $intIdBd_t];


                $arrCampos_t[] = ["campoId" => "FECHA_ASIGNACION", "nombre" => "FECHA_ASIGNACION", "tipo" => "5"];
                $arrCampos_t[] = ["campoId" => "FECHA_PASO_COLA", "nombre" => "FECHA_PASO_COLA", "tipo" => "5"];
                $arrCampos_t[] = ["campoId" => "FECHA_FINALIZACION", "nombre" => "FECHA_FINALIZACION", "tipo" => "5"];
                $arrCampos_t[] = ["campoId" => "ESTADO", "nombre" => "ESTADO", "tipo" => "estado_chat" , "idbg" => $intIdBd_t];
                $arrCampos_t[] = ["campoId" => "ESTADO_GESTION", "nombre" => "ESTADO GESTION", "tipo" => "estado_ges"];
                $arrCampos_t[] = ["campoId" => "DURACION_GESTION", "nombre" => "DURACION_GESTION", "tipo" => "10"];
                $arrCampos_t[] = ["campoId" => "DATOS_INICIALES", "nombre" => "DATOS_INICIALES", "tipo" => "1"];
                $arrCampos_t[] = ["campoId" => "EN_ESPERA", "nombre" => "EN_ESPERA", "tipo" => "sino"];
                $arrCampos_t[] = ["campoId" => "TIEMPO_ESPERA", "nombre" => "TIEMPO_ESPERA", "tipo" => "10"];
    
                echo json_encode($arrCampos_t);
    
                break;
    

            case 'gsComMail':
                $arrCampos_t[] = ["campoId" => "ID", "nombre" => "ID_DY", "tipo" => "3"];
                $arrCampos_t[] = ["campoId" => "ID_BD", "nombre" => "ID_BD_DY", "tipo" => "3"];
                $arrCampos_t[] = ["campoId" => "FECHA_GESTION_DY", "nombre" => "FECHA_GESTION_DY", "tipo" => "5"];
                $arrCampos_t[] = ["campoId" => "AÑO_GESTION_DY", "nombre" => "AÑO_GESTION_DY", "tipo" => "3"];
                $arrCampos_t[] = ["campoId" => "MES_GESTION_DY", "nombre" => "MES_GESTION_DY", "tipo" => "3"];
                $arrCampos_t[] = ["campoId" => "DIA_GESTION_DY", "nombre" => "DIA_GESTION_DY", "tipo" => "3"];
                $arrCampos_t[] = ["campoId" => "HORA_GESTION_DY", "nombre" => "HORA_GESTION_DY", "tipo" => "3"];
                $arrCampos_t[] = ["campoId" => "LINK_CONTENIDO", "nombre" => "LINK_CONTENIDO", "tipo" => "1"];
                $arrCampos_t[] = ["campoId" => "PARA", "nombre" => "PARA", "tipo" => "1"];
                $arrCampos_t[] = ["campoId" => "CC", "nombre" => "CC", "tipo" => "1"];
                $arrCampos_t[] = ["campoId" => "CCO", "nombre" => "CCO", "tipo" => "1"];

    
                echo json_encode($arrCampos_t);
    
                break;

            case 'gsComSMS':
                $arrCampos_t[] = ["campoId" => "ID", "nombre" => "ID_DY", "tipo" => "3"];
                $arrCampos_t[] = ["campoId" => "ID_BD", "nombre" => "ID_BD_DY", "tipo" => "3"];
                $arrCampos_t[] = ["campoId" => "FECHA_GESTION_DY", "nombre" => "FECHA_GESTION_DY", "tipo" => "5"];
                $arrCampos_t[] = ["campoId" => "AÑO_GESTION_DY", "nombre" => "AÑO_GESTION_DY", "tipo" => "3"];
                $arrCampos_t[] = ["campoId" => "MES_GESTION_DY", "nombre" => "MES_GESTION_DY", "tipo" => "3"];
                $arrCampos_t[] = ["campoId" => "DIA_GESTION_DY", "nombre" => "DIA_GESTION_DY", "tipo" => "3"];
                $arrCampos_t[] = ["campoId" => "HORA_GESTION_DY", "nombre" => "HORA_GESTION_DY", "tipo" => "3"];
                $arrCampos_t[] = ["campoId" => "NUMERO_DESTINO", "nombre" => "NUMERO_DESTINO", "tipo" => "3"];
                $arrCampos_t[] = ["campoId" => "CONTENIDO", "nombre" => "PARA", "tipo" => "3"];
    
                echo json_encode($arrCampos_t);
    
                break;
    
            case 'gsComWhatsapp':
                $arrCampos_t[] = ["campoId" => "ID", "nombre" => "ID", "tipo" => "3"];
                $arrCampos_t[] = ["campoId" => "ID_BD", "nombre" => "ID_BD", "tipo" => "3"];
                $arrCampos_t[] = ["campoId" => "FECHA_GESTION_DY", "nombre" => "FECHA_GESTION_DY", "tipo" => "5"];
                $arrCampos_t[] = ["campoId" => "AÑO_GESTION_DY", "nombre" => "AÑO_GESTION_DY", "tipo" => "3"];
                $arrCampos_t[] = ["campoId" => "MES_GESTION_DY", "nombre" => "MES_GESTION_DY", "tipo" => "3"];
                $arrCampos_t[] = ["campoId" => "DIA_GESTION_DY", "nombre" => "DIA_GESTION_DY", "tipo" => "3"];
                $arrCampos_t[] = ["campoId" => "HORA_GESTION_DY", "nombre" => "HORA_GESTION_DY", "tipo" => "3"];
                $arrCampos_t[] = ["campoId" => "NUMERO_ORIGEN", "nombre" => "NUMERO_ORIGEN", "tipo" => "1"];
                $arrCampos_t[] = ["campoId" => "NUMERO_DESTINO", "nombre" => "NUMERO_DESTINO", "tipo" => "1"];      
                $arrCampos_t[] = ["campoId" => "PROVEDOR", "nombre" => "PROVEDOR", "tipo" => "1"];
    
                echo json_encode($arrCampos_t);
    
                break;


            case 'detalladoLlamadas':
                $arrCampos_t[] = ["campoId" => "fecha_hora", "nombre" => "FECHA", "tipo" => "5"];
                $arrCampos_t[] = ["campoId" => "llamada_id_asterisk", "nombre" => "UID", "tipo" => "3"];
                $arrCampos_t[] = ["campoId" => "fecha_hora", "nombre" => "INICIO", "tipo" => "14"];
                $arrCampos_t[] = ["campoId" => "fecha_hora_final", "nombre" => "FIN", "tipo" => "14"];
                $arrCampos_t[] = ["campoId" => "sentido", "nombre" => "SENTIDO", "tipo" => "dlSentido"];
                $arrCampos_t[] = ["campoId" => "tipo_llamada", "nombre" => "TIPO", "tipo" => "1"];
                $arrCampos_t[] = ["campoId" => "troncal", "nombre" => "TRONCAL", "tipo" => "1"];
                $arrCampos_t[] = ["campoId" => "dnis", "nombre" => "DID", "tipo" => "1"];
                $arrCampos_t[] = ["campoId" => "numero_telefonico", "nombre" => "NUMERO", "tipo" => "1"];      
                $arrCampos_t[] = ["campoId" => "disa", "nombre" => "DISA", "tipo" => "sinoNumber"];
                $arrCampos_t[] = ["campoId" => "origen_disa", "nombre" => "ORIGEN_DISA", "tipo" => "1"];
                $arrCampos_t[] = ["campoId" => "resultado", "nombre" => "RESULTADO", "tipo" => "1"];
                $arrCampos_t[] = ["campoId" => "extension", "nombre" => "EXTENSION", "tipo" => "3"];
                $arrCampos_t[] = ["campoId" => "transferida", "nombre" => "TRANSFERIDA", "tipo" => "sinoNumber"];
                $arrCampos_t[] = ["campoId" => "duracion_total", "nombre" => "DURACION", "tipo" => "14"];
                $arrCampos_t[] = ["campoId" => "tiempo_espera", "nombre" => "ESPERA", "tipo" => "14"];
                $arrCampos_t[] = ["campoId" => "duracion_al_aire", "nombre" => "AL_AIRE", "tipo" => "14"];
                $arrCampos_t[] = ["campoId" => "redondeo_minuto", "nombre" => "REDONDEO", "tipo" => "3"];
                $arrCampos_t[] = ["campoId" => "costo_llamada", "nombre" => "COSTO", "tipo" => "3"];
                $arrCampos_t[] = ["campoId" => "tiempo_timbrando", "nombre" => "TIMBRADO", "tipo" => "14"];
                $arrCampos_t[] = ["campoId" => "tiempo_espera_llamada_activa", "nombre" => "ESPERA_LLAMADA_ACTIVA", "tipo" => "10"];
                $arrCampos_t[] = ["campoId" => "agente_nombre", "nombre" => "AGENTE", "tipo" => "1"];
                $arrCampos_t[] = ["campoId" => "identificacion_agente", "nombre" => "ID_AGENTE", "tipo" => "3"];
                $arrCampos_t[] = ["campoId" => "campana", "nombre" => "CAMPANA", "tipo" => "1"];
                $arrCampos_t[] = ["campoId" => "quien_completo", "nombre" => "COLGO_PRIMERO", "tipo" => "1"];
                $arrCampos_t[] = ["campoId" => "nombre_tipificacion", "nombre" => "TIPIFICACION", "tipo" => "1"];
                $arrCampos_t[] = ["campoId" => "respuesta_encuesta", "nombre" => "ENCUESTA", "tipo" => "1"];
                $arrCampos_t[] = ["campoId" => "etiqueta", "nombre" => "ETIQUETA", "tipo" => "1"];

            case 'historicoIVRDetallado':
                
                $arrCampos_t[] = ["campoId" => "telefono", "nombre" => "TELÉFONO", "tipo" => "1"];
                $arrCampos_t[] = ["campoId" => "unique_id", "nombre" => "UID", "tipo" => "1"];
                $arrCampos_t[] = ["campoId" => "nombre_usuario_ivr", "nombre" => "NOMBRE OPCIÓN", "tipo" => "ivr_opcion"];
                $arrCampos_t[] = ["campoId" => "nombre_usuario_opcion", "nombre" => "OPCIÓN MARCADA", "tipo" => "1"];

            case 'historicoIVRResum':
                $arrCampos_t[] = ["campoId" => "fecha_hora", "nombre" => "FECHA", "tipo" => "5"];
                $arrCampos_t[] = ["campoId" => "nombre_raiz", "nombre" => "NOMBRE IVR", "tipo" => "ivr_raiz"];

                echo json_encode($arrCampos_t);
    
                break;

            case 'encuestasIVRResumenAgente':
                $arrCampos_t[] = ["campoId" => "r.fecha_hora", "nombre" => "FECHA", "tipo" => "5"];
                $arrCampos_t[] = ["campoId" => "e.nombre", "nombre" => "ENCUESTA", "tipo" => "1"];
                $arrCampos_t[] = ["campoId" => "r.nombre_agente", "nombre" => "AGENTE", "tipo" => "1"];
                $arrCampos_t[] = ["campoId" => "p.nombre", "nombre" => "PREGUNTA", "tipo" => "1"];

                echo json_encode($arrCampos_t);
    
                break;


            case 'encuestasIVRResumenPregun':
                $arrCampos_t[] = ["campoId" => "r.fecha_hora", "nombre" => "FECHA", "tipo" => "5"];
                $arrCampos_t[] = ["campoId" => "e.nombre", "nombre" => "ENCUESTA", "tipo" => "1"];
                $arrCampos_t[] = ["campoId" => "p.nombre", "nombre" => "PREGUNTA", "tipo" => "1"];

                echo json_encode($arrCampos_t);
    
                break;

            case 'encuestasIVRdetallado':
                $arrCampos_t[] = ["campoId" => "r.fecha_hora", "nombre" => "FECHA", "tipo" => "5"];
                $arrCampos_t[] = ["campoId" => "r.unique_id", "nombre" => "UID", "tipo" => "1"];
                $arrCampos_t[] = ["campoId" => "r.caller_id", "nombre" => "CONTACTO", "tipo" => "1"];
                $arrCampos_t[] = ["campoId" => "e.nombre", "nombre" => "ENCUESTA", "tipo" => "1"];
                $arrCampos_t[] = ["campoId" => "p.nombre", "nombre" => "PREGUNTA", "tipo" => "1"];
                $arrCampos_t[] = ["campoId" => "c.nombre_usuario", "nombre" => "CAMPAÑA", "tipo" => "1"];
                $arrCampos_t[] = ["campoId" => "r.nombre_agente", "nombre" => "AGENTE", "tipo" => "1"];
                $arrCampos_t[] = ["campoId" => "r.respuesta", "nombre" => "RESPUESTA", "tipo" => "1"];

                echo json_encode($arrCampos_t);
    
                break;


            case 'historicoUltOpcIVRDetallado':
                $arrCampos_t[] = ["campoId" => "fecha_hora", "nombre" => "FECHA", "tipo" => "5"];
                $arrCampos_t[] = ["campoId" => "unique_id", "nombre" => "UID", "tipo" => "1"];
                $arrCampos_t[] = ["campoId" => "numero_telefonico", "nombre" => "TELEFÓNO", "tipo" => "1"];
                $arrCampos_t[] = ["campoId" => "ivr_ultimo_raiz", "nombre" => "NOMBRE_IVR", "tipo" => "ivr_raiz"];
                $arrCampos_t[] = ["campoId" => "ivr_ultimo_usu", "nombre" => "NOMBRE_OPCION", "tipo" => "ivr_opcion"];
                $arrCampos_t[] = ["campoId" => "ivr_ultima_opcion", "nombre" => "OPCION_MARCADA", "tipo" => "1"];

                echo json_encode($arrCampos_t);
    
                break;

            case 'erlang':
                
                $arrCampos_t[] = ["campoId" => "fecha", "nombre" => "FECHA", "tipo" => "5"];
                $arrCampos_t[] = ["campoId" => "id_campana", "nombre" => "CAMPANA", "tipo" => "campanProyecto"];
                $arrCampos_t[] = ["campoId" => "intervalo_traducido", "nombre" => "HORA", "tipo" => "14"];
                $arrCampos_t[] = ["campoId" => "num_semanas", "nombre" => "NUMERO DE SEMANAS A TOMAR", "tipo" => "num_semanas"];
                $arrCampos_t[] = ["campoId" => "sino", "nombre" => "CALCULAR FESTIVOS POR SEPARADO", "tipo" => "sino"];

                echo json_encode($arrCampos_t);
    
                break;
                
            case 'ordenamiento':
                $arrCampos_t[1] = ["campoId" => "AGENTE", "nombre" => "AGENTE_DY", "tipo" => "usu", "idbg" => $intIdBd_t];
                echo json_encode($arrCampos_t);
                break;

        default:

            echo json_encode($arrCampos_t);

            break;
    }
}

    function opcionesLista($strNombre_p)
    {   
        $strNombre_t = trim($strNombre_p);

        $arrBuscar_t = ['á', 'à', 'ä', 'â', 'ª', 'Á', 'À', 'Â', 'Ä','é', 'è', 'ë', 'ê', 'É', 'È', 'Ê', 'Ë','í', 'ì', 'ï', 'î', 'Í', 'Ì', 'Ï', 'Î','ó', 'ò', 'ö', 'ô', 'Ó', 'Ò', 'Ö', 'Ô','ú', 'ù', 'ü', 'û', 'Ú', 'Ù', 'Û', 'Ü','ñ', 'Ñ', 'ç', 'Ç'];

        $arrCambiar_t = ['a', 'a', 'a', 'a', 'a', 'A', 'A', 'A', 'A','e', 'e', 'e', 'e', 'E', 'E', 'E', 'E','i', 'i', 'i', 'i', 'I', 'I', 'I', 'I','o', 'o', 'o', 'o', 'O', 'O', 'O', 'O','u', 'u', 'u', 'u', 'U', 'U', 'U', 'U','n', 'N', 'c', 'C'];

        $strNombre_t = str_replace($arrBuscar_t, $arrCambiar_t, $strNombre_t);  

        $strNombre_t = preg_replace("/\s+/", "_", $strNombre_t);

        $strNombre_t = preg_replace('([^0-9a-zA-Z_])', "", $strNombre_t);

        $strNombre_t = strtoupper($strNombre_t);

        $strNombre_t = rtrim($strNombre_t,"_");

        return $strNombre_t; 
    }

    if (isset($_GET['ConsumirWS'])) {
        //JDBD - se actualizan las vistas del huesped.
        // echo generarVistasPorHuesped(null,$_GET['huespedId']);

        echo generarVistasUnicas(3, $_GET['huespedId']);
        
    }

    if(isset($_POST['opcion']) && $_POST['opcion'] == "borrarLogCargue"){
         $Lsql = "DELETE FROM ".$BaseDatos_systema.".LOG_CARGUE WHERE LOG_CARGUE_Token_b =".$_SESSION['TOKEN'];
         $res = $mysqli->query($Lsql);
    }
    if (isset($_POST['ACD'])) {
        $EstpasLslq = "SELECT ESTPAS_ConsInte__CAMPAN_b FROM ".$BaseDatos_systema.".ESTPAS WHERE ESTPAS_Tipo______b = 1 AND ESTPAS_ConsInte__ESTRAT_b = ".$_POST['ACD'];
        $resEspaslq = $mysqli->query($EstpasLslq);
        $nombresRe = [];
        if ($resEspaslq->num_rows > 0) {
            while ($key = $resEspaslq->fetch_object()) {
                if($key->ESTPAS_ConsInte__CAMPAN_b != null && !empty($key->ESTPAS_ConsInte__CAMPAN_b)){
                    $CampanLsql = "SELECT CAMPAN_Nombre____b, CAMPAN_IdCamCbx__b FROM ".$BaseDatos_systema.".CAMPAN WHERE CAMPAN_ConsInte__b = ".$key->ESTPAS_ConsInte__CAMPAN_b;   
                    $resPanLsql = $mysqli->query($CampanLsql);
                    $dataAnLsql = $resPanLsql->fetch_array();
                    $NombreCamp = $dataAnLsql['CAMPAN_Nombre____b']; 
                    $CAMPAN_IdCamCbx__b = $dataAnLsql['CAMPAN_IdCamCbx__b'];

                    $titulodeLapregunta = $NombreCamp;
                    $titulodeLapregunta = sanear_strings($titulodeLapregunta);
                    $titulodeLapregunta = str_replace(' ', '_', $titulodeLapregunta);
                    $titulodeLapregunta = substr($titulodeLapregunta, 0 , 20);


                    $nombresRe[] = ['nombre' => 'ACD '.$titulodeLapregunta,
                                    'IdCamCbx__b' => (int)$CAMPAN_IdCamCbx__b];
                }
            }
            echo json_encode($nombresRe);
        }else{
            $nombresRe[] = ['vacio' => 0];
            echo json_encode($nombresRe);
        }


        // echo $EstpasLslq;
    }
    if (isset($_GET['limpiar_nombre'])) {
        $sql=$mysqli->query("SELECT ESTRAT_ConsInte__b FROM ".$BaseDatos_systema.".ESTRAT WHERE md5(concat('".clave_get."', ESTRAT_ConsInte__b)) = '".$_GET['estrategiaId']."'");
        if($sql && $sql->num_rows==1){
            $sql=$sql->fetch_object();
            $_GET['estrategiaId']=$sql->ESTRAT_ConsInte__b;
        }
        $res_nombre = "UPDATE ".$BaseDatos_general.".reportes_automatizados 
                       SET ruta_archivo = SUBSTRING(ruta_archivo,1,length(ruta_archivo)-19)
                       WHERE id_huesped = ".$_GET['huespedId']." AND id_estrategia = ".$_GET['estrategiaId'].";";
        // if ($mysqli->query($res_nombre) === TRUE) {
        //     echo "SE ARRANCA LA FECHA";
        // }
    }


    if (isset($_GET['correosFinal'])) {
        $sql=$mysqli->query("SELECT ESTRAT_ConsInte__b FROM ".$BaseDatos_systema.".ESTRAT WHERE md5(concat('".clave_get."', ESTRAT_ConsInte__b)) = '".$_GET['estrategiaId']."'");
        if($sql && $sql->num_rows==1){
            $sql=$sql->fetch_object();
            $_GET['estrategiaId']=$sql->ESTRAT_ConsInte__b;
        }
        $data = array(  
                    "strUsuario_t"              =>  'crm',
                    "strToken_t"                =>  'D43dasd321',
                    "intIdHuesped_p"      =>  $_GET['huespedId'],
                    "intIdEstrategia_p" => $_GET['estrategiaId'],
                    "strEmailSolicitante_p" => $_GET['correosFinal']
                );                
        $data_string = json_encode($data); 
        $ch = curl_init('http://127.0.0.1:8080/dyalogocore/api/reportes/solicitarReportesAutomatizados');
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");                                                                     
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string); 
        curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(                                                                          
            'Accept: application/json', 
            'Content-Type: application/json',                                                                               
            'Content-Length: ' . strlen($data_string))                                                                      
        ); 
        $respuesta = curl_exec ($ch);
        $error = curl_error($ch);
        curl_close ($ch);

        echo json_encode($respuesta);
    }

    //JDBD - Esta funcion retorna el nombre de la vista para reportes.
        function nombreVistaBd($intEstratEstpasId_p,$intTipo_t){

            global $mysqli;
            global $BaseDatos_systema;
            global $BaseDatos_general;

            if ($intTipo_t == 2) {

                $strSQLNombreVista_t = "SELECT MAX(id) AS id, nombre FROM ".$BaseDatos_general.".vistas_generadas JOIN ".$BaseDatos_systema.".ESTRAT ON id_guion = ESTRAT_ConsInte_GUION_Pob WHERE ESTRAT_ConsInte__b = ".$intEstratEstpasId_p." GROUP BY id_guion";

            }

            if ($intTipo_t == 3) {

                $strSQLNombreVista_t = "SELECT nombre FROM ".$BaseDatos_general.".vistas_generadas JOIN ".$BaseDatos_systema.".CAMPAN ON id_campan = CAMPAN_ConsInte__b JOIN ".$BaseDatos_systema.".ESTPAS ON CAMPAN_ConsInte__b = ESTPAS_ConsInte__CAMPAN_b WHERE ESTPAS_ConsInte__b = ".$intEstratEstpasId_p." AND nombre LIKE '%ACD_DIA%'";

            }

            if ($intTipo_t == 4) {

                $strSQLNombreVista_t = "SELECT nombre FROM ".$BaseDatos_general.".vistas_generadas JOIN ".$BaseDatos_systema.".CAMPAN ON id_campan = CAMPAN_ConsInte__b JOIN ".$BaseDatos_systema.".ESTPAS ON CAMPAN_ConsInte__b = ESTPAS_ConsInte__CAMPAN_b WHERE ESTPAS_ConsInte__b = ".$intEstratEstpasId_p." AND nombre LIKE '%ACD_HORA%'";

            }


            $resSQLNombreVista_t = $mysqli->query($strSQLNombreVista_t);

            if ($resSQLNombreVista_t && $resSQLNombreVista_t->num_rows > 0) {

                return $resSQLNombreVista_t->fetch_object()->nombre; 

            }else{

                return "sin vista";

            }

        }    

    if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {

        $arrDataFiltros_t = [];

        if (isset($_POST["totalFiltros"]) && $_POST["totalFiltros"] > 0) {

            $arrTotalFiltros_t = $_POST["totalFiltros"];

            $arrDataFiltros_t["totalFiltros"]=$_POST["totalFiltros"];
            $arrDataFiltros_t["dataFiltros"]=[];

            foreach ($arrTotalFiltros_t as $value) {

                array_push($arrDataFiltros_t["dataFiltros"], ["selCampo_".$value=>$_POST["selCampo_".$value],"selOperador_".$value=>$_POST["selOperador_".$value],"valor_".$value=>$_POST["valor_".$value],"tipo_".$value=>$_POST["tipo_".$value],"selCondicion_".$value=>(isset($_POST["selCondicion_".$value]) ? $_POST["selCondicion_".$value] : null)]);    

            }


        }


        if (isset($_GET["llenarIndicadores"])) {

            $idTipo_t = $_POST["idTipo_t"];
            $idBd_t = $_POST["idBd_t"];
            $idEstpas_t = $_POST["idEstpas_t"];

            $strCondicion_t = "";
            
            if ($arrDataFiltros_t["totalFiltros"]>0) {


                foreach ($arrDataFiltros_t["totalFiltros"] as $ite => $value) {


                    $strCondicion_t .=armarCondicion($arrDataFiltros_t["dataFiltros"][$ite]["selCampo_".$value],$arrDataFiltros_t["dataFiltros"][$ite]["selOperador_".$value],$arrDataFiltros_t["dataFiltros"][$ite]["valor_".$value],$arrDataFiltros_t["dataFiltros"][$ite]["tipo_".$value],$arrDataFiltros_t["dataFiltros"][$ite]["selCondicion_".$value],$idTipo_t);

                }

            }

            $arrDatos_t = [0,0,0,0,0,0,0,0,0];

            if ($idTipo_t != "1") {

                //JDBD Indicadores.
                $strSQL_t = "SELECT COUNT(1) AS gestiones, SUM((CASE WHEN ".$BaseDatos_general.".fn_clasificacion_traduccion(G".$idBd_t."_Clasificacion) = 'No contactable' THEN 1 ELSE 0 END)) AS no_contactable, SUM((CASE WHEN ".$BaseDatos_general.".fn_clasificacion_traduccion(G".$idBd_t."_Clasificacion) = 'Devoluciones' THEN 1 ELSE 0 END)) AS devoluciones, SUM((CASE WHEN ".$BaseDatos_general.".fn_clasificacion_traduccion(G".$idBd_t."_Clasificacion) = 'Sin gestion' THEN 1 ELSE 0 END)) AS sin_gestion, SUM((CASE WHEN ".$BaseDatos_general.".fn_clasificacion_traduccion(G".$idBd_t."_Clasificacion) = 'No contactado' THEN 1 ELSE 0 END)) AS no_contactado, SUM((CASE WHEN ".$BaseDatos_general.".fn_clasificacion_traduccion(G".$idBd_t."_Clasificacion) = 'Contactado' THEN 1 ELSE 0 END)) AS contactado, SUM((CASE WHEN ".$BaseDatos_general.".fn_clasificacion_traduccion(G".$idBd_t."_Clasificacion) = 'No efectivo' THEN 1 ELSE 0 END)) AS no_efectivo, SUM((CASE WHEN ".$BaseDatos_general.".fn_clasificacion_traduccion(G".$idBd_t."_Clasificacion) = 'Efectivo' THEN 1 ELSE 0 END)) AS efectivo, CAST(SEC_TO_TIME(AVG(TIME_TO_SEC(G".$idBd_t."_Duracion___b))) AS TIME(0)) AS duracion FROM ".$BaseDatos.".G".$idBd_t." WHERE (G".$idBd_t."_Paso = ".$idEstpas_t." OR G".$idBd_t."_Paso IS NULL OR G".$idBd_t."_Paso = '') AND (".$strCondicion_t.")";

                if ($resSQL_t = $mysqli->query($strSQL_t)) {

                    if ($resSQL_t->num_rows > 0) {

                        $objSQL_t = $resSQL_t->fetch_object();

                        $arrDatos_t[0] = $objSQL_t->gestiones;
                        $arrDatos_t[1] = $objSQL_t->no_contactable;
                        $arrDatos_t[2] = $objSQL_t->devoluciones;
                        $arrDatos_t[3] = $objSQL_t->sin_gestion;
                        $arrDatos_t[4] = $objSQL_t->no_contactado;
                        $arrDatos_t[5] = $objSQL_t->contactado;
                        $arrDatos_t[6] = $objSQL_t->no_efectivo;
                        $arrDatos_t[7] = $objSQL_t->efectivo;
                        $arrDatos_t[8] = $objSQL_t->duracion;

                    }

                }

            }else{

                $strSQLVista_t = "SELECT nombre AS vista FROM ".$BaseDatos_systema.".ESTPAS A JOIN ".$BaseDatos_general.".vistas_generadas B ON A.ESTPAS_ConsInte__CAMPAN_b = B.id_campan WHERE A.ESTPAS_ConsInte__b = ".$idEstpas_t." AND B.nombre LIKE '%_ACD_DIA_%'";

                if ($resSQLVista_t = $mysqli->query($strSQLVista_t)) {

                    if ($resSQLVista_t->num_rows > 0) {

                        $strVista_t = $resSQLVista_t->fetch_object()->vista;

                        $strSQL_t = "SELECT AVG(Ofrecidas) AS ofrecidas, CAST(SEC_TO_TIME(AVG(TIME_TO_SEC(ASA))) AS TIME(0)) AS asa, SUM(Aban) AS aban, SUM(Aban_despues_tsf) AS aban_despues_tsf, ROUND(AVG(Aban_porcentaje),2) AS aban_porcentaje, SUM(Contestadas) AS contestadas, AVG(Cont_despues_tsf) AS cont_despues_tsf, ROUND(AVG(Cont_porcentaje),2) AS cont_porcentaje, ROUND(AVG(TSF),2) AS tsf, CAST(SEC_TO_TIME(AVG(TIME_TO_SEC(CAST(AHT AS TIME(0))))) AS TIME(0)) AS aht FROM ".$BaseDatos.".".$strVista_t." WHERE (".$strCondicion_t.")";

                        if ($resSQL_t = $mysqli->query($strSQL_t)) {

                            if ($resSQL_t->num_rows > 0) {

                                $objSQL_t = $resSQL_t->fetch_object();  

                                $arrDatos_t[0] = $objSQL_t->ofrecidas;                              
                                $arrDatos_t[1] = $objSQL_t->asa;                              
                                $arrDatos_t[2] = $objSQL_t->aban;                              
                                $arrDatos_t[3] = $objSQL_t->aban_despues_tsf;                              
                                $arrDatos_t[4] = $objSQL_t->aban_porcentaje;                              
                                $arrDatos_t[5] = $objSQL_t->contestadas;                              
                                $arrDatos_t[6] = $objSQL_t->cont_despues_tsf;                              
                                $arrDatos_t[7] = $objSQL_t->cont_porcentaje;                              
                                $arrDatos_t[8] = $objSQL_t->tsf;                              
                                $arrDatos_t[9] = $objSQL_t->aht;                              

                            }

                        }

                    }

                }

            }

            echo json_encode($arrDatos_t);

        }

        if (isset($_GET["DataGraficaBd_4"])) {

            $idBd_t = $_POST["idBd_t"];

            $strCondicion_t = "";
            
            if ($arrDataFiltros_t["totalFiltros"]>0) {


                foreach ($arrDataFiltros_t["totalFiltros"] as $ite => $value) {


                    $strCondicion_t .=armarCondicion($arrDataFiltros_t["dataFiltros"][$ite]["selCampo_".$value],$arrDataFiltros_t["dataFiltros"][$ite]["selOperador_".$value],$arrDataFiltros_t["dataFiltros"][$ite]["valor_".$value],$arrDataFiltros_t["dataFiltros"][$ite]["tipo_".$value],$arrDataFiltros_t["dataFiltros"][$ite]["selCondicion_".$value]);

                }

            }

             $strSQLData_t = "SELECT COUNT(1) AS cantidad, ".$BaseDatos_general.".fn_item_monoef(G".$idBd_t."_GesMasImp_b) AS GESTION_MAS_IMPORTANTE FROM ".$BaseDatos.".G".$idBd_t." WHERE (".$strCondicion_t.") GROUP BY ".$BaseDatos_general.".fn_item_monoef(G".$idBd_t."_GesMasImp_b) ORDER BY ".$BaseDatos_general.".fn_item_monoef(G".$idBd_t."_GesMasImp_b) ASC";

             $resSQLData_t = $mysqli->query($strSQLData_t);

             $arrDatos_t = [];

            if ($resSQLData_t && $resSQLData_t->num_rows > 0) {

                while ($obj = $resSQLData_t->fetch_object()) {
                    $arrDatos_t[] = $obj;
                }

            }

            echo json_encode($arrDatos_t);

        }

        if (isset($_GET["DataGraficaBd_3"])) {

            $idBd_t = $_POST["idBd_t"];

            $strCondicion_t = "";
            
            if ($arrDataFiltros_t["totalFiltros"]>0) {


                foreach ($arrDataFiltros_t["totalFiltros"] as $ite => $value) {


                    $strCondicion_t .=armarCondicion($arrDataFiltros_t["dataFiltros"][$ite]["selCampo_".$value],$arrDataFiltros_t["dataFiltros"][$ite]["selOperador_".$value],$arrDataFiltros_t["dataFiltros"][$ite]["valor_".$value],$arrDataFiltros_t["dataFiltros"][$ite]["tipo_".$value],$arrDataFiltros_t["dataFiltros"][$ite]["selCondicion_".$value]);

                }

            }


            $strSQLData_t = "SELECT COUNT(1) AS cantidad, ".$BaseDatos_general.".fn_tipo_reintento_traduccion(G".$idBd_t."_TipoReintentoGMI_b) AS REINTENTO_GMI FROM ".$BaseDatos.".G".$idBd_t." WHERE (".$strCondicion_t.") GROUP BY ".$BaseDatos_general.".fn_tipo_reintento_traduccion(G".$idBd_t."_TipoReintentoGMI_b) ORDER BY ".$BaseDatos_general.".fn_tipo_reintento_traduccion(G".$idBd_t."_TipoReintentoGMI_b) DESC";

             $resSQLData_t = $mysqli->query($strSQLData_t);

             $arrDatos_t = [];

            if ($resSQLData_t && $resSQLData_t->num_rows > 0) {

                while ($obj = $resSQLData_t->fetch_object()) {
                    $arrDatos_t[] = $obj;
                }

            }

            echo json_encode($arrDatos_t);

        }

        if (isset($_GET["DataGraficaBd_2"])) {

            $idBd_t = $_POST["idBd_t"];

            $strCondicion_t = "";
            
            if ($arrDataFiltros_t["totalFiltros"]>0) {


                foreach ($arrDataFiltros_t["totalFiltros"] as $ite => $value) {


                    $strCondicion_t .=armarCondicion($arrDataFiltros_t["dataFiltros"][$ite]["selCampo_".$value],$arrDataFiltros_t["dataFiltros"][$ite]["selOperador_".$value],$arrDataFiltros_t["dataFiltros"][$ite]["valor_".$value],$arrDataFiltros_t["dataFiltros"][$ite]["tipo_".$value],$arrDataFiltros_t["dataFiltros"][$ite]["selCondicion_".$value]);

                }

            }            

            $strSQLData_t = "SELECT COUNT(1) AS cantidad, G".$idBd_t."_CantidadIntentos AS CANTIDAD_INTENTOS FROM ".$BaseDatos.".G".$idBd_t." WHERE (".$strCondicion_t.") GROUP BY G".$idBd_t."_CantidadIntentos ORDER BY G".$idBd_t."_CantidadIntentos ASC";

             $resSQLData_t = $mysqli->query($strSQLData_t);

             $arrDatos_t = [];

            if ($resSQLData_t && $resSQLData_t->num_rows > 0) {

                while ($obj = $resSQLData_t->fetch_object()) {
                    $arrDatos_t[] = $obj;
                }

            }

            echo json_encode($arrDatos_t);

        }

        if (isset($_GET["DataGraficaSC_4"])) {

            $strCondicion_t = "";
            
            if ($arrDataFiltros_t["totalFiltros"]>0) {


                foreach ($arrDataFiltros_t["totalFiltros"] as $ite => $value) {


                    $strCondicion_t .=armarCondicion($arrDataFiltros_t["dataFiltros"][$ite]["selCampo_".$value],$arrDataFiltros_t["dataFiltros"][$ite]["selOperador_".$value],$arrDataFiltros_t["dataFiltros"][$ite]["valor_".$value],$arrDataFiltros_t["dataFiltros"][$ite]["tipo_".$value],$arrDataFiltros_t["dataFiltros"][$ite]["selCondicion_".$value]);

                }

            }

            //JDBD - Seteamos las varibles para traer la informacion del reporte grafico.
            $idTipo_t = $_POST["idTipo_t"];
            $idBd_t = $_POST["idBd_t"];
            $idEstpas_t = $_POST["idEstpas_t"];

            $strHoras_t = "";
            $arrDatos_t = ["horas"=>[],"data"=>[]];

            $pregun1 = pregun(3,$idBd_t);

            $strSQLHoras_t = "SELECT DATE_FORMAT(G".$idBd_t."_FechaInsercion,'%H') AS hora FROM ".$BaseDatos.".G".$idBd_t." WHERE (G".$idBd_t."_Paso = ".$idEstpas_t." OR G".$idBd_t."_Paso IS NULL OR G".$idBd_t."_Paso = '') AND (".$strCondicion_t.") GROUP BY DATE_FORMAT(G".$idBd_t."_FechaInsercion,'%H')";



            if ($resSQLHoras_t = $mysqli->query($strSQLHoras_t)) {

                if ($resSQLHoras_t->num_rows > 0) {

                    while ($obj = $resSQLHoras_t->fetch_object()) {

                        $hora = $obj->hora;

                        $strHoras_t .= ", SUM((CASE WHEN DATE_FORMAT(G".$idBd_t."_FechaInsercion,'%H') = '".$hora."' THEN 1 ELSE 0 END)) AS '".$hora."'";

                        array_push($arrDatos_t["horas"], $hora);

                    }

                    $strSQLData_t = "SELECT SUBSTR(".$BaseDatos_general.".fn_item_lisopc(".$pregun1."),1,20) AS gestion ".$strHoras_t." FROM ".$BaseDatos.".G".$idBd_t." WHERE (G".$idBd_t."_Paso = ".$idEstpas_t." OR G".$idBd_t."_Paso IS NULL OR G".$idBd_t."_Paso = '') AND (".$strCondicion_t.") GROUP BY ".$pregun1." ORDER BY ".$pregun1." DESC";

                    if ($resSQLData_t = $mysqli->query($strSQLData_t)) {

                        if ($resSQLData_t->num_rows > 0) {

                            while ($obj = $resSQLData_t->fetch_object()) {

                                array_push($arrDatos_t["data"], $obj);
                            }

                        }

                    }
                }

            }

            echo json_encode($arrDatos_t);

        }

        if (isset($_GET["DataGraficaSC_3"])) {

            $strCondicion_t = "";
            
            if ($arrDataFiltros_t["totalFiltros"]>0) {

                foreach ($arrDataFiltros_t["totalFiltros"] as $ite => $value) {


                    $strCondicion_t .=armarCondicion($arrDataFiltros_t["dataFiltros"][$ite]["selCampo_".$value],$arrDataFiltros_t["dataFiltros"][$ite]["selOperador_".$value],$arrDataFiltros_t["dataFiltros"][$ite]["valor_".$value],$arrDataFiltros_t["dataFiltros"][$ite]["tipo_".$value],$arrDataFiltros_t["dataFiltros"][$ite]["selCondicion_".$value],$_GET["DataGraficaSC_3"]);

                }

            }

            //JDBD - Seteamos las varibles para traer la informacion del reporte grafico.
            $idTipo_t = $_POST["idTipo_t"];
            $idBd_t = $_POST["idBd_t"];
            $idEstpas_t = $_POST["idEstpas_t"];

            $strHoras_t = "";
            $arrDatos_t = ["horas"=>[],"data"=>[]];

            if ($_GET["DataGraficaSC_3"] == "in") {

                $arrDatos_t = [];

                $strSQLVista_t = "SELECT nombre AS vista FROM ".$BaseDatos_systema.".ESTPAS A JOIN ".$BaseDatos_general.".vistas_generadas B ON A.ESTPAS_ConsInte__CAMPAN_b = B.id_campan WHERE A.ESTPAS_ConsInte__b = ".$idEstpas_t." AND B.nombre LIKE '%_ACD_HORA_%'";

                if ($resSQLVista_t = $mysqli->query($strSQLVista_t)) {

                    if ($resSQLVista_t->num_rows == 1) {

                        $strVista_t = $resSQLVista_t->fetch_object()->vista;

                        $strSQLData_t = "SELECT Intervalo, SUM(Cont_antes_tsf) AS Cont_antes_tsf, SUM(Cont_despues_tsf) AS Cont_despues_tsf, SUM(Aban_antes_tsf) AS Aban_antes_tsf, SUM(Aban_despues_tsf) AS Aban_despues_tsf FROM ".$BaseDatos.".".$strVista_t." WHERE (".$strCondicion_t.") GROUP BY Intervalo ORDER BY Intervalo DESC";

                        if ($resSQLData_t = $mysqli->query($strSQLData_t)) {

                            if ($resSQLData_t->num_rows > 0) {

                                while ($obj = $resSQLData_t->fetch_object()) {

                                    $arrDatos_t[] = $obj;

                                }

                            }

                        }

                    }

                }

            }else{

                $strSQLHoras_t = "SELECT DATE_FORMAT(G".$idBd_t."_FechaInsercion,'%H') AS hora FROM ".$BaseDatos.".G".$idBd_t." WHERE (G".$idBd_t."_Paso = ".$idEstpas_t." OR G".$idBd_t."_Paso IS NULL OR G".$idBd_t."_Paso = '') AND (".$strCondicion_t.") GROUP BY DATE_FORMAT(G".$idBd_t."_FechaInsercion,'%H')";


                if ($resSQLHoras_t = $mysqli->query($strSQLHoras_t)) {

                    if ($resSQLHoras_t->num_rows > 0) {

                        while ($obj = $resSQLHoras_t->fetch_object()) {

                            $hora = $obj->hora;

                            $strHoras_t .= ", SUM((CASE WHEN DATE_FORMAT(G".$idBd_t."_FechaInsercion,'%H') = '".$hora."' THEN 1 ELSE 0 END)) AS '".$hora."'";

                            array_push($arrDatos_t["horas"], $hora);

                        }

                    }

                }

                $strSQLData_t = "SELECT ".$BaseDatos_general.".fn_clasificacion_traduccion(G".$idBd_t."_Clasificacion) AS clasificacion ".$strHoras_t." FROM ".$BaseDatos.".G".$idBd_t." WHERE (G".$idBd_t."_Paso = ".$idEstpas_t." OR G".$idBd_t."_Paso IS NULL OR G".$idBd_t."_Paso = '') AND (".$strCondicion_t.") GROUP BY G".$idBd_t."_Clasificacion ORDER BY ".$BaseDatos_general.".fn_clasificacion_traduccion(G".$idBd_t."_Clasificacion) ASC";

                if ($resSQLData_t = $mysqli->query($strSQLData_t)) {

                    if ($resSQLData_t->num_rows > 0) {

                        while ($obj = $resSQLData_t->fetch_object()) {

                            array_push($arrDatos_t["data"], $obj);
                        }

                    }

                }

            }


            echo json_encode($arrDatos_t);

        }

        if (isset($_GET["DataGraficaSC_2"])) {

            $strCondicion_t = "";
            
            if ($arrDataFiltros_t["totalFiltros"]>0) {


                foreach ($arrDataFiltros_t["totalFiltros"] as $ite => $value) {


                    $strCondicion_t .=armarCondicion($arrDataFiltros_t["dataFiltros"][$ite]["selCampo_".$value],$arrDataFiltros_t["dataFiltros"][$ite]["selOperador_".$value],$arrDataFiltros_t["dataFiltros"][$ite]["valor_".$value],$arrDataFiltros_t["dataFiltros"][$ite]["tipo_".$value],$arrDataFiltros_t["dataFiltros"][$ite]["selCondicion_".$value]);

                }

            }

            //JDBD - Seteamos las varibles para traer la informacion del reporte grafico.
            $idTipo_t = $_POST["idTipo_t"];
            $idBd_t = $_POST["idBd_t"];
            $idEstpas_t = $_POST["idEstpas_t"];

            $arrDatos_t = [];

            $pregun = pregun(3,$idBd_t);

            $strSQLData_t = "SELECT COUNT(1) AS cantidad, SUBSTR(".$BaseDatos_general.".fn_item_lisopc(".$pregun."),1,20) AS gestion FROM ".$BaseDatos.".G".$idBd_t." WHERE (".$strCondicion_t.") AND (G".$idBd_t."_Paso = ".$idEstpas_t." OR G".$idBd_t."_Paso IS NULL OR G".$idBd_t."_Paso = '') GROUP BY ".$pregun." ORDER BY ".$pregun." DESC";

            if ($resSQLData_t = $mysqli->query($strSQLData_t)) {

                if ($resSQLData_t->num_rows > 0) {

                    while ($obj = $resSQLData_t->fetch_object()) {
                        $arrDatos_t[]=$obj;
                    }

                }

            }
            
            echo json_encode($arrDatos_t);

        }

        if (isset($_GET["DataGraficaSC_1"])) {

            $strCondicion_t = "";
            
            if ($arrDataFiltros_t["totalFiltros"]>0) {


                foreach ($arrDataFiltros_t["totalFiltros"] as $ite => $value) {


                    $strCondicion_t .=armarCondicion($arrDataFiltros_t["dataFiltros"][$ite]["selCampo_".$value],$arrDataFiltros_t["dataFiltros"][$ite]["selOperador_".$value],$arrDataFiltros_t["dataFiltros"][$ite]["valor_".$value],$arrDataFiltros_t["dataFiltros"][$ite]["tipo_".$value],$arrDataFiltros_t["dataFiltros"][$ite]["selCondicion_".$value]);

                }

            }

            //JDBD - Seteamos las varibles para traer la informacion del reporte grafico.
            $idTipo_t = $_POST["idTipo_t"];
            $idBd_t = $_POST["idBd_t"];
            $idEstpas_t = $_POST["idEstpas_t"];

            $strHoras_t = "";
            $arrDatos_t = ["horas"=>[],"data"=>[]];

            $strSQLHoras_t = "SELECT DATE_FORMAT(G".$idBd_t."_FechaInsercion,'%H') AS hora FROM ".$BaseDatos.".G".$idBd_t." WHERE (G".$idBd_t."_Paso = ".$idEstpas_t." OR G".$idBd_t."_Paso IS NULL OR G".$idBd_t."_Paso = '') AND (".$strCondicion_t.") GROUP BY DATE_FORMAT(G".$idBd_t."_FechaInsercion,'%H')";

            if ($resSQLHoras_t = $mysqli->query($strSQLHoras_t)) {

                if ($resSQLHoras_t->num_rows > 0) {

                    while ($obj = $resSQLHoras_t->fetch_object()) {

                        $hora = $obj->hora;

                        $strHoras_t .= ", SUM((CASE WHEN DATE_FORMAT(G".$idBd_t."_FechaInsercion,'%H') = '".$hora."' THEN 1 ELSE 0 END)) AS '".$hora."'";

                        array_push($arrDatos_t["horas"], $hora);

                    }

                }

            }

            $strSQLData_t = "SELECT ".$BaseDatos_general.".fn_nombre_USUARI(G".$idBd_t."_Usuario) AS agente ".$strHoras_t." FROM ".$BaseDatos.".G".$idBd_t." WHERE (G".$idBd_t."_Paso = ".$idEstpas_t." OR G".$idBd_t."_Paso IS NULL OR G".$idBd_t."_Paso = '') AND (".$strCondicion_t.") GROUP BY G".$idBd_t."_Usuario ORDER BY ".$BaseDatos_general.".fn_nombre_USUARI(G".$idBd_t."_Usuario) ASC";

            if ($resSQLData_t = $mysqli->query($strSQLData_t)) {

                if ($resSQLData_t->num_rows > 0) {

                    while ($obj = $resSQLData_t->fetch_object()) {

                        array_push($arrDatos_t["data"], $obj);
                    }

                }

            }

            echo json_encode($arrDatos_t);

        }

        if (isset($_GET["DataGraficaBd_1"])) {

            $idBd_t = $_POST["idBd_t"];

            $strCondicion_t = "";
            
            if ($arrDataFiltros_t["totalFiltros"]>0) {


                foreach ($arrDataFiltros_t["totalFiltros"] as $ite => $value) {


                    $strCondicion_t .=armarCondicion($arrDataFiltros_t["dataFiltros"][$ite]["selCampo_".$value],$arrDataFiltros_t["dataFiltros"][$ite]["selOperador_".$value],$arrDataFiltros_t["dataFiltros"][$ite]["valor_".$value],$arrDataFiltros_t["dataFiltros"][$ite]["tipo_".$value],$arrDataFiltros_t["dataFiltros"][$ite]["selCondicion_".$value]);

                }

            }

            $strSQLData_t = "SELECT COUNT(1) AS cantidad, ".$BaseDatos_general.".fn_clasificacion_traduccion(G".$idBd_t."_ClasificacionGMI_b) AS CLASIFICACION_GMI FROM ".$BaseDatos.".G".$idBd_t." WHERE ".$BaseDatos_general.".fn_clasificacion_traduccion(G".$idBd_t."_ClasificacionGMI_b) IN ('Sin gestion','No contactable','No contactado','Contactado','No efectivo','Efectivo') AND (".$strCondicion_t.") GROUP BY ".$BaseDatos_general.".fn_clasificacion_traduccion(G".$idBd_t."_ClasificacionGMI_b) ORDER BY FIELD(".$BaseDatos_general.".fn_clasificacion_traduccion(G".$idBd_t."_ClasificacionGMI_b),'Sin gestion','No contactable','No contactado','Contactado','No efectivo','Efectivo')";

             $resSQLData_t = $mysqli->query($strSQLData_t);

             $arrDatos_t = [];

            if ($resSQLData_t && $resSQLData_t->num_rows > 0) {

                while ($obj = $resSQLData_t->fetch_object()) {
                    $arrDatos_t[] = $obj;
                }

            }

            echo json_encode($arrDatos_t);

        }

        if (isset($_POST["TraerGraficas"])) {

            $intIdEstrat_t = $_POST['TraerGraficas'];
            $intIdHuesped_t = $_POST['intIdHuesped'];

            $strSQLBaseEstrat_t = "SELECT ESTRAT_ConsInte_GUION_Pob AS bd, ESTRAT_Nombre____b AS nombre FROM ".$BaseDatos_systema.".ESTRAT WHERE md5(concat('".clave_get."', ESTRAT_ConsInte__b)) = '".$intIdEstrat_t."'";

            $strSQLBdPasos_t = "SELECT (CASE WHEN ESTPAS_Tipo______b IN (1,6,9) THEN CAMPAN_Nombre____b ELSE ESTPAS_Comentari_b END) AS nombre, ESTPAS_ConsInte__b AS paso, CAMPAN_IdCamCbx__b AS idCBX, ESTPAS_Tipo______b AS tipo , CAMPAN_ConsInte__GUION__Gui_b AS guion, (CASE WHEN ESTPAS_Tipo______b > 6 THEN ESTPAS_ConsInte__MUESTR_b ELSE CAMPAN_ConsInte__MUESTR_b END) AS muestra FROM ".$BaseDatos_systema.".ESTPAS LEFT JOIN ".$BaseDatos_systema.".CAMPAN ON ESTPAS_ConsInte__CAMPAN_b = CAMPAN_ConsInte__b WHERE md5(concat('".clave_get."', ESTPAS_ConsInte__ESTRAT_b)) = '".$intIdEstrat_t."' AND ESTPAS_Tipo______b IN (1,6) ORDER BY ESTPAS_Tipo______b ASC";
            $resSQLBdPasos_t = $mysqli->query($strSQLBdPasos_t);

            $resSQLBaseEstrat_t = $mysqli->query($strSQLBaseEstrat_t);
            $objSQLBaseEstrat_t = $resSQLBaseEstrat_t->fetch_object();

            $intIdBase_t = $objSQLBaseEstrat_t->bd;
            $strNombreEstrat_t = opcionesLista($objSQLBaseEstrat_t->nombre);
            $strHTMLReports_t = '<option value="bd" idBd="'.$intIdBase_t.'">GENERAL - '.$strNombreEstrat_t.'</option>';

            while ($objSQLBdPasos_t = $resSQLBdPasos_t->fetch_object()) {

                $strHTMLReports_t .= '<option value="sc" idTipo="'.$objSQLBdPasos_t->tipo.'" idEstpas="'.$objSQLBdPasos_t->paso.'" idBd="'.$objSQLBdPasos_t->guion.'">'.opcionesLista($objSQLBdPasos_t->nombre).'</option>';

            }

            echo $strHTMLReports_t;

        }
        
        if(isset($_POST['TraerReportes'])){

            $thisMuestra=false;

            $stepUnique = (isset($_POST['stepUnique'])) ? $_POST['stepUnique'] : false;

            if(isset($_POST['paso']) && $_POST['paso'] !=0){
                $sqlPaso=$mysqli->query("SELECT CAMPAN_ConsInte__MUESTR_b FROM {$BaseDatos_systema}.CAMPAN WHERE CAMPAN_ConsInte__b=(SELECT ESTPAS_ConsInte__CAMPAN_b FROM {$BaseDatos_systema}.ESTPAS WHERE ESTPAS_ConsInte__b={$_POST['paso']})");
                if($sqlPaso && $sqlPaso->num_rows == 1){
                    $sqlPaso=$sqlPaso->fetch_object();
                    $thisMuestra=$sqlPaso->CAMPAN_ConsInte__MUESTR_b;
                }
            }

            $intIdEstrat_t = $_POST['TraerReportes'];
            $intIdHuesped_t = $_POST['intIdHuesped'];


            $strSQLBaseEstrat_t = "SELECT ESTRAT_ConsInte_GUION_Pob AS bd, ESTRAT_Nombre____b AS nombre FROM ".$BaseDatos_systema.".ESTRAT WHERE md5(concat('".clave_get."', ESTRAT_ConsInte__b)) = '".$intIdEstrat_t."'";


            // Se valida si el reporte solo es un paso en especifico

            $strSQLFiltroPaso = (isset($_POST['stepUnique'])) ? " AND ESTPAS_ConsInte__b = {$_POST['paso']} " : " AND ESTPAS_Tipo______b IN (1,6,7,8,9,12,13,14,15,16,17,18,19,22) ";

            
            $strSQLBdPasos_t = "SELECT (CASE WHEN ESTPAS_Tipo______b IN (1,6,22) THEN CAMPAN_Nombre____b ELSE ESTPAS_Comentari_b END) AS nombre, ESTPAS_ConsInte__b AS paso, CAMPAN_IdCamCbx__b AS idCBX, ESTPAS_Tipo______b AS tipo , CAMPAN_ConsInte__GUION__Gui_b AS guion, (CASE WHEN ESTPAS_Tipo______b > 6  AND ESTPAS_Tipo______b != 22 THEN ESTPAS_ConsInte__MUESTR_b ELSE CAMPAN_ConsInte__MUESTR_b END) AS muestra, ESTPAS_ConsInte__CAMPAN_b AS idCampan FROM ".$BaseDatos_systema.".ESTPAS LEFT JOIN ".$BaseDatos_systema.".CAMPAN ON ESTPAS_ConsInte__CAMPAN_b = CAMPAN_ConsInte__b WHERE md5(concat('".clave_get."', ESTPAS_ConsInte__ESTRAT_b)) = '".$intIdEstrat_t."' ".$strSQLFiltroPaso." ORDER BY ESTPAS_Tipo______b ASC";

            $resSQLBdPasos_t = $mysqli->query($strSQLBdPasos_t);

            $resSQLBaseEstrat_t = $mysqli->query($strSQLBaseEstrat_t);
            $objSQLBaseEstrat_t = $resSQLBaseEstrat_t->fetch_object();

            $intIdBase_t = $objSQLBaseEstrat_t->bd;
            $strNombreEstrat_t = opcionesLista($objSQLBaseEstrat_t->nombre);
            $strConexionMiddleware = '';

            $strHTMLReports_t = '';
            $strHTMLReportsBack_t = '';
            $strHTMLReportsGestiones_t = '';
            $strHTMLReportsACD_t = '';
            $strHTMLReportsBOT_t = '';
            $strHTMLReportsBOT_OpcionesUsadas_t = '';
            $strHTMLbdPasoReports_t = '';
            $strHTMLReportsComun_t = '';
            $strHTMLReportsGsComun_t = '';
            $strHTMLReportsOrdenamiento_t = '';

            // Si el paso es diferente a una campaña o aun bot no se muestra el resumen de las bases de datos
                if( $stepUnique != 'campan' && $stepUnique != 'bot' && $stepUnique != 'red' && $stepUnique != 'campanout' && $stepUnique != 'bkpaso'  ){
                    $strHTMLbdPasoReports_t .= '<option value="bd" idTipo="" idEstrat="'.$intIdEstrat_t.'" idBd="'.$intIdBase_t.'" idGuion="" idCampanCBX="" periodo="" idPaso="" idMuestra="">GENERAL - '.$strNombreEstrat_t.'</option>';
                }

                while ($objSQLBdPasos_t = $resSQLBdPasos_t->fetch_object()) {

                    if ($objSQLBdPasos_t->tipo == 9) {
                        $strHTMLReportsBack_t .= '<option value="bkpaso" idTipo="" idEstrat="'.$intIdEstrat_t.'" idBd="'.$intIdBase_t.'" idGuion="'.$objSQLBdPasos_t->guion.'" idCampanCBX="" periodo="" idPaso="'.$objSQLBdPasos_t->paso.'" idMuestra="'.$objSQLBdPasos_t->muestra.'">BACK - '.opcionesLista($objSQLBdPasos_t->nombre).'</option>';
                    }else{

                        $strTipo_t = "PASO - ";

                        if ($objSQLBdPasos_t->tipo == 1) {
                            
                            $strTipo_t = "CAMPAÑA - ENTRANTE - ";

                        }elseif ($objSQLBdPasos_t->tipo == 6 || $objSQLBdPasos_t->tipo == 22) {
                            
                            $strTipo_t = "CAMPAÑA - SALIENTE - ";
                            
                        }else if($objSQLBdPasos_t->tipo == 7 || $objSQLBdPasos_t->tipo == 10){

                            $strTipo_t = "CORREO - ";

                        }else if($objSQLBdPasos_t->tipo == 8){

                            $strTipo_t = "SMS - ";

                        }else if($objSQLBdPasos_t->tipo == 13){
                            $strTipo_t = "PLANTILLA WHA - ";
                        }

                        if($objSQLBdPasos_t->tipo != 12 && $objSQLBdPasos_t->tipo != 14 && $objSQLBdPasos_t->tipo != 15 && $objSQLBdPasos_t->tipo != 16 && $objSQLBdPasos_t->tipo != 17 && $objSQLBdPasos_t->tipo != 18 && $stepUnique != 'green'){
                            if($thisMuestra){
                                if($thisMuestra == $objSQLBdPasos_t->muestra){
                                    $strHTMLbdPasoReports_t .= '<option value="bdpaso" idTipo="'.$objSQLBdPasos_t->tipo.'" idEstrat="'.$intIdEstrat_t.'" idBd="'.$intIdBase_t.'" idGuion="" idCampanCBX="" periodo="" idPaso="" idMuestra="'.$objSQLBdPasos_t->muestra.'">'.$strTipo_t.opcionesLista($objSQLBdPasos_t->nombre).'</option>'; 
                                }
                            }else{
                                $strHTMLbdPasoReports_t .= '<option value="bdpaso" idTipo="'.$objSQLBdPasos_t->tipo.'" idEstrat="'.$intIdEstrat_t.'" idBd="'.$intIdBase_t.'" idGuion="" idCampanCBX="" periodo="" idPaso="" idMuestra="'.$objSQLBdPasos_t->muestra.'">'.$strTipo_t.opcionesLista($objSQLBdPasos_t->nombre).'</option>'; 
                            }
                        }
                    }   

                    if ($objSQLBdPasos_t->tipo < 7 || $objSQLBdPasos_t->tipo == 22) {
                        if($thisMuestra){
                            if($thisMuestra == $objSQLBdPasos_t->muestra){
                                $strHTMLReportsGestiones_t .= '<option value="gspaso" idTipo="" idEstrat="'.$intIdEstrat_t.'" idBd="'.$intIdBase_t.'" idGuion="'.$objSQLBdPasos_t->guion.'" idCampanCBX="" periodo="" idPaso="'.$objSQLBdPasos_t->paso.'" idMuestra="'.$objSQLBdPasos_t->muestra.'">GS - '.opcionesLista($objSQLBdPasos_t->nombre).'</option>';
                            }
                        }else{
                            $strHTMLReportsGestiones_t .= '<option value="gspaso" idTipo="" idEstrat="'.$intIdEstrat_t.'" idBd="'.$intIdBase_t.'" idGuion="'.$objSQLBdPasos_t->guion.'" idCampanCBX="" periodo="" idPaso="'.$objSQLBdPasos_t->paso.'" idMuestra="'.$objSQLBdPasos_t->muestra.'">GS - '.opcionesLista($objSQLBdPasos_t->nombre).'</option>';
                        }

                        if ($objSQLBdPasos_t->tipo == 1) {
                            if($thisMuestra){
                                if($thisMuestra == $objSQLBdPasos_t->muestra){
                                    $strHTMLReportsACD_t .= '<option value="acd" idTipo="" idEstrat="'.$intIdEstrat_t.'" idBd="'.$intIdBase_t.'" idGuion="'.$objSQLBdPasos_t->guion.'" idCampanCBX="'.$objSQLBdPasos_t->idCBX.'" periodo="1" idPaso="" idMuestra="'.$objSQLBdPasos_t->muestra.'">ACD - DIA - CANAL TELEFONIA - '.opcionesLista($objSQLBdPasos_t->nombre).'</option>';
                                    $strHTMLReportsACD_t .= '<option value="acd" idEstrat="'.$intIdEstrat_t.'" idBd="'.$intIdBase_t.'" idGuion="'.$objSQLBdPasos_t->guion.'" idCampanCBX="'.$objSQLBdPasos_t->idCBX.'" periodo="2" idPaso="" idMuestra="'.$objSQLBdPasos_t->muestra.'">ACD - HORA - CANAL TELEFONIA - '.opcionesLista($objSQLBdPasos_t->nombre).'</option>';

                                    $strHTMLReportsACD_t .= '<option value="acdChat" idTipo="" idEstrat="'.$intIdEstrat_t.'" idBd="'.$intIdBase_t.'" idGuion="'.$objSQLBdPasos_t->guion.'" idCampanCBX="'.$objSQLBdPasos_t->idCBX.'" periodo="1" idPaso="" idMuestra="'.$objSQLBdPasos_t->muestra.'">ACD - DIA - CANAL CHAT - '.opcionesLista($objSQLBdPasos_t->nombre).'</option>';
                                    $strHTMLReportsACD_t .= '<option value="acdChat" idEstrat="'.$intIdEstrat_t.'" idBd="'.$intIdBase_t.'" idGuion="'.$objSQLBdPasos_t->guion.'" idCampanCBX="'.$objSQLBdPasos_t->idCBX.'" periodo="2" idPaso="" idMuestra="'.$objSQLBdPasos_t->muestra.'">ACD - HORA - CANAL CHAT - '.opcionesLista($objSQLBdPasos_t->nombre).'</option>';

                                    $strHTMLReportsACD_t .= '<option value="acdEmail" idTipo="" idEstrat="'.$intIdEstrat_t.'" idBd="'.$intIdBase_t.'" idGuion="'.$objSQLBdPasos_t->guion.'" idCampanCBX="'.$objSQLBdPasos_t->idCBX.'" periodo="1" idPaso="" idMuestra="'.$objSQLBdPasos_t->muestra.'">ACD - DIA - CANAL CORREO - '.opcionesLista($objSQLBdPasos_t->nombre).'</option>';
                                    $strHTMLReportsACD_t .= '<option value="acdEmail" idEstrat="'.$intIdEstrat_t.'" idBd="'.$intIdBase_t.'" idGuion="'.$objSQLBdPasos_t->guion.'" idCampanCBX="'.$objSQLBdPasos_t->idCBX.'" periodo="2" idPaso="" idMuestra="'.$objSQLBdPasos_t->muestra.'">ACD - HORA - CANAL CORREO - '.opcionesLista($objSQLBdPasos_t->nombre).'</option>';
                                }
                            }else{
                                $strHTMLReportsACD_t .= '<option value="acd" idTipo="" idEstrat="'.$intIdEstrat_t.'" idBd="'.$intIdBase_t.'" idGuion="'.$objSQLBdPasos_t->guion.'" idCampanCBX="'.$objSQLBdPasos_t->idCBX.'" periodo="1" idPaso="" idMuestra="'.$objSQLBdPasos_t->muestra.'">ACD - DIA - CANAL TELEFONIA - '.opcionesLista($objSQLBdPasos_t->nombre).'</option>';
                                $strHTMLReportsACD_t .= '<option value="acd" idEstrat="'.$intIdEstrat_t.'" idBd="'.$intIdBase_t.'" idGuion="'.$objSQLBdPasos_t->guion.'" idCampanCBX="'.$objSQLBdPasos_t->idCBX.'" periodo="2" idPaso="" idMuestra="'.$objSQLBdPasos_t->muestra.'">ACD - HORA - CANAL TELEFONIA - '.opcionesLista($objSQLBdPasos_t->nombre).'</option>';

                                $strHTMLReportsACD_t .= '<option value="acdChat" idTipo="" idEstrat="'.$intIdEstrat_t.'" idBd="'.$intIdBase_t.'" idGuion="'.$objSQLBdPasos_t->guion.'" idCampanCBX="'.$objSQLBdPasos_t->idCBX.'" periodo="1" idPaso="" idMuestra="'.$objSQLBdPasos_t->muestra.'">ACD - DIA - CANAL CHAT - '.opcionesLista($objSQLBdPasos_t->nombre).'</option>';
                                $strHTMLReportsACD_t .= '<option value="acdChat" idEstrat="'.$intIdEstrat_t.'" idBd="'.$intIdBase_t.'" idGuion="'.$objSQLBdPasos_t->guion.'" idCampanCBX="'.$objSQLBdPasos_t->idCBX.'" periodo="2" idPaso="" idMuestra="'.$objSQLBdPasos_t->muestra.'">ACD - HORA - CANAL CHAT - '.opcionesLista($objSQLBdPasos_t->nombre).'</option>';

                                $strHTMLReportsACD_t .= '<option value="acdEmail" idTipo="" idEstrat="'.$intIdEstrat_t.'" idBd="'.$intIdBase_t.'" idGuion="'.$objSQLBdPasos_t->guion.'" idCampanCBX="'.$objSQLBdPasos_t->idCBX.'" periodo="1" idPaso="" idMuestra="'.$objSQLBdPasos_t->muestra.'">ACD - DIA - CANAL CORREO - '.opcionesLista($objSQLBdPasos_t->nombre).'</option>';
                                $strHTMLReportsACD_t .= '<option value="acdEmail" idEstrat="'.$intIdEstrat_t.'" idBd="'.$intIdBase_t.'" idGuion="'.$objSQLBdPasos_t->guion.'" idCampanCBX="'.$objSQLBdPasos_t->idCBX.'" periodo="2" idPaso="" idMuestra="'.$objSQLBdPasos_t->muestra.'">ACD - HORA - CANAL CORREO - '.opcionesLista($objSQLBdPasos_t->nombre).'</option>';
                            }

                        }else if ($objSQLBdPasos_t->tipo == 6){
                            $strHTMLReportsOrdenamiento_t .= '<option value="ordenamiento" idTipo="" idEstrat="'.$intIdEstrat_t.'" idBd="'.$intIdBase_t.'" idGuion="" idCampanCBX="'.$objSQLBdPasos_t->idCampan.'" periodo="" idPaso="'.$objSQLBdPasos_t->paso.'" idMuestra="'.$objSQLBdPasos_t->muestra.'">ORDENAMIENTO - '.opcionesLista($objSQLBdPasos_t->nombre).'</option>';
                        }

                    }   

                    if($objSQLBdPasos_t->tipo == 12){
                        $strHTMLReportsBOT_OpcionesUsadas_t .= '<option value="opcionesUsadasBot" idTipo="'.$objSQLBdPasos_t->tipo.'" idEstrat="'.$intIdEstrat_t.'" idBd="'.$intIdBase_t.'" idGuion="'.$objSQLBdPasos_t->guion.'" idCampanCBX="" periodo="" idPaso="'.$objSQLBdPasos_t->paso.'" idMuestra="'.$objSQLBdPasos_t->muestra.'">OPCIONES USADAS BOT - '.opcionesLista($objSQLBdPasos_t->nombre).'</option>';
                        $strHTMLReportsBOT_t .= '<option value="gsbot" idTipo="'.$objSQLBdPasos_t->tipo.'" idEstrat="'.$intIdEstrat_t.'" idBd="'.$intIdBase_t.'" idGuion="'.$objSQLBdPasos_t->guion.'" idCampanCBX="" periodo="" idPaso="'.$objSQLBdPasos_t->paso.'" idMuestra="'.$objSQLBdPasos_t->muestra.'">GESTIONES BOT - '.opcionesLista($objSQLBdPasos_t->nombre).'</option>';
                    }

                    // Se valida el tipo de paso para los reportes de canales de comunicacion


                    if($objSQLBdPasos_t->tipo == 14 || $objSQLBdPasos_t->tipo == 15 || $objSQLBdPasos_t->tipo == 16 || $objSQLBdPasos_t->tipo == 17 || $objSQLBdPasos_t->tipo == 18 || $objSQLBdPasos_t->tipo == 19 || $objSQLBdPasos_t->tipo == 26){

                        switch ($objSQLBdPasos_t->tipo ){
                            case 14: // WebChat
                            case 15: // Chat Whatsapp
                            case 16: // Chat Facebook
                            case 26: // Chat Sms
                                $strTipoValueReport = 'comChat';
                                break;

                            case 17:
                                $strTipoValueReport = 'comMail';
                                break;
                            case 19:
                                $strTipoValueReport = 'comWebForm';
                                break;

                            case 18:
                                $strTipoValueReport = 'comSms';
                                break;
                        }

                        
                        $strHTMLReportsComun_t .= '<option value="'.$strTipoValueReport.'" idTipo="'.$objSQLBdPasos_t->tipo.'" idEstrat="'.$intIdEstrat_t.'" idBd="'.$intIdBase_t.'" idGuion="'.$objSQLBdPasos_t->guion.'" idCampanCBX="" periodo="" idPaso="'.$objSQLBdPasos_t->paso.'" idMuestra="'.$objSQLBdPasos_t->muestra.'">CANAL DE COMUNICACIÓN - '.opcionesLista($objSQLBdPasos_t->nombre).'</option>';

                    };

                    // Se valida el tipo de paso para los reportes de gestiones canales de comunicacion
                    
                    if($objSQLBdPasos_t->tipo == 7 || $objSQLBdPasos_t->tipo == 8 || $objSQLBdPasos_t->tipo == 13){

                        switch ($objSQLBdPasos_t->tipo ){

                            case 7:
                                $strTipoValueReport = 'gsComMail';
                                break;
                            case 8:
                                $strTipoValueReport = 'gsComSMS';
                                break;
                            case 13:
                                $strTipoValueReport = 'gsComWhatsapp';
                                $strConexionMiddleware = 'middleWare="si"';
                                break;
                        }

                        
                        $strHTMLReportsGsComun_t .= '<option value="'.$strTipoValueReport.'" idTipo="'.$objSQLBdPasos_t->tipo.'" idEstrat="'.$intIdEstrat_t.'" idBd="'.$intIdBase_t.'" idGuion="'.$objSQLBdPasos_t->guion.'" idCampanCBX="" periodo="" idPaso="'.$objSQLBdPasos_t->paso.'" idMuestra="'.$objSQLBdPasos_t->muestra.'" '.$strConexionMiddleware.'>GS CANAL DE COMUNICACIÓN - '.opcionesLista($objSQLBdPasos_t->nombre).'</option>';
                    
                    };




                }

            if(!isset($_POST['stepUnique']) ||  $stepUnique != 'bot' && $stepUnique != 'yellow'){
                $strHTMLReports_t .= '<optgroup label="RESUMEN DE BASE">';
            }

            if(!isset($_POST['stepUnique']) ||  $stepUnique != 'bot' && $stepUnique != 'yellow' ){
                $strHTMLReports_t .= $strHTMLbdPasoReports_t;
            }



            $strHTMLReports_t .= $strHTMLReportsBack_t;

            // Si el reporte es para un paso de campaña se envian las gestiones humanas

            if(!isset($_POST['stepUnique']) || $stepUnique == 'campan' || $stepUnique == 'campanout'){
                $strHTMLReports_t .= '</optgroup>';
                $strHTMLReports_t .= '<optgroup label="GESTIONES HUMANAS">';
                    $strHTMLReports_t .= $strHTMLReportsGestiones_t;
                    $strHTMLReports_t .= '</optgroup>';
            
            }


            // Si el reporte es para un paso de campaña se muestran los ordenamientos de la campañas

            if(!isset($_POST['stepUnique']) || $stepUnique == 'campanout'){
                $strHTMLReports_t .= '</optgroup>';
                $strHTMLReports_t .= '<optgroup label="ORDENAMIENTO CAMPAÑAS">';
                    $strHTMLReports_t .= $strHTMLReportsOrdenamiento_t;
                    $strHTMLReports_t .= '</optgroup>';
            
            }


            // Reportes de gs de canales de comunicacion
            if(!isset($_POST['stepUnique']) || $stepUnique == 'yellow'){
                $strHTMLReports_t .= '<optgroup label="CANALES DE COMUNICACIÓN">';
                $strHTMLReports_t .= $strHTMLReportsComun_t;
                $strHTMLReports_t .= '</optgroup>';
            }

            // Reportes de gs de canales de comunicacion

            if(!isset($_POST['stepUnique']) || $stepUnique == 'red'){
                $strHTMLReports_t .= '<optgroup label="GS CANALES DE COMUNICACIÓN">';
                $strHTMLReports_t .= $strHTMLReportsGsComun_t;
                $strHTMLReports_t .= '</optgroup>';
            }

            // Si el reporte es un bot se envia la seccion de bots
            if(!isset($_POST['stepUnique']) || $stepUnique == 'bot'){
                $strHTMLReports_t .= '<optgroup label="BOTS">';
                $strHTMLReports_t .= $strHTMLReportsBOT_OpcionesUsadas_t;
                $strHTMLReports_t .= $strHTMLReportsBOT_t;
                $strHTMLReports_t .= '</optgroup>';
            }

            // Si el reporte es de una paso de campaña se envia la seccion ACD
            if(!isset($_POST['stepUnique']) || $stepUnique == 'campan' ){
            $strHTMLReports_t .= '<optgroup label="ACD">';
                $strHTMLReports_t .= $strHTMLReportsACD_t;
            $strHTMLReports_t .= '</optgroup>';
            }


            // La seccion de adherencias solo se envia cuando se solicita los reportes de toda la estrategia
            if(!$thisMuestra && !$stepUnique){  
                $arrAdherencias_t = ['SESIONES','PAUSAS','NO SE REGISTRARON','LLEGARON TARDE','LLEGARON A TIEMPO','SE FUERON ANTES','SE FUERON A TIEMPO','SESIONES CORTAS','SESIONES DURACION OK','PAUSAS CON HORARIO MUY LARGAS','PAUSAS CON HORARIO DURACION OK','PAUSAS CON HORARIO INCUMPLIDAS','PAUSAS CON HORARIOS CUMPLIDAS','PAUSAS SIN HORARIO MUY LARGA','PAUSAS SIN HORARIO MUCHAS VECES','PAUSAS SIN HORARIO OK','AGENTES SIN MALLA DEFINIDA'];
    
                $strHTMLReports_t .= '<optgroup label="ADHERENCIAS POR HUESPED">'; 
    
                    foreach ($arrAdherencias_t as $key => $reporte) {
                        $strHTMLReports_t .= '<option value="'.($key+1).'" idTipo="" idEstrat="'.$intIdEstrat_t.'" idBd="" idGuion="" idCampanCBX="" periodo="" idPaso="" idMuestra="" >'.$reporte.'</option>';    
    
                        if ($key == 1) {
    
                            $strHTMLReports_t .= '<option value="pausas" idTipo="" idEstrat="'.$intIdEstrat_t.'" idBd="" idGuion="" idCampanCBX="" periodo="" idPaso="" idMuestra="" >PAUSAS - DURACION POR AGENTE</option>';  
    
                        }
                    }
    
                $strHTMLReports_t .= '</optgroup>';


                // SE LLAMA TAMBIEN AL REPORTE DE DETALLADO DE LLAMADAS

                $strHTMLReports_t .= '
                <optgroup label="DETALLADO DE LLAMADAS DEL HUESPED">
                    <option value="detalladoLlamadas" idTipo="" idEstrat="" idBd="" idGuion="" idCampanCBX="" periodo="" idPaso="" idMuestra="" >DETALLADO DE LLAMADAS</option>
                </optgroup>';

                $strHTMLReports_t .= '
                <optgroup label="IVR">
                    <option value="historicoIVRResum" idTipo="" idEstrat="" idBd="" idGuion="" idCampanCBX="" periodo="" idPaso="" idMuestra="" >
                    HISTORICO COMPLETO - RESUMEN</option>
                    <option value="historicoIVRDetallado" idTipo="" idEstrat="" idBd="" idGuion="" idCampanCBX="" periodo="" idPaso="" idMuestra="" >HISTORICO COMPLETO - DETALLADO</option>
                    <option value="historicoUltOpcIVRDetallado" idTipo="" idEstrat="" idBd="" idGuion="" idCampanCBX="" periodo="" idPaso="" idMuestra="" >HISTORICO ULTIMA OPCION DE CADA LLAMADA - DETALLADO</option>
                </optgroup>
                <optgroup label="ENCUESTAS">
                    <option value="encuestasIVRResumenAgente" idTipo="" idEstrat="" idBd="" idGuion="" idCampanCBX="" periodo="" idPaso="" idMuestra="" >RESUMEN POR AGENTE</option>
                    <option value="encuestasIVRResumenPregun" idTipo="" idEstrat="" idBd="" idGuion="" idCampanCBX="" periodo="" idPaso="" idMuestra="" >RESUMEN POR PREGUNTA</option>
                    <option value="encuestasIVRdetallado" idTipo="" idEstrat="" idBd="" idGuion="" idCampanCBX="" periodo="" idPaso="" idMuestra="" >DETALLADO</option>
                </optgroup>
                <optgroup label="ERLANG">
                    <option value="erlang" idTipo="" idEstrat="" idBd="" idGuion="" idCampanCBX="" periodo="" idPaso="" idMuestra="" >PROYECCION ERLANG</option>
                </optgroup>
                ';
            }

            echo $strHTMLReports_t;

        }


        //Datos del formulario
        if(isset($_POST['CallDatos'])){
          
            $Lsql = "SELECT G2_ConsInte__b, G2_C7 as principal ,G2_C5,G2_C6,G2_C7,G2_C8,G2_C9,G2_C10,G2_C11,G2_C12,G2_C13,G2_C14, G2_C69 FROM {$BaseDatos_systema}.G2 WHERE md5(concat('".clave_get."', G2_ConsInte__b)) = '".$_POST['id']."'";
            $result = $mysqli->query($Lsql);
            $datos = array();
            $i = 0;
            

            while($key = $result->fetch_object()){

                $datos[$i]['G2_C5'] = utf8_encode($key->G2_C5);

                $datos[$i]['G2_C6'] = utf8_encode($key->G2_C6);

                $datos[$i]['G2_C7'] = $key->G2_C7;

                $datos[$i]['G2_C8'] = utf8_encode($key->G2_C8);

                $datos[$i]['G2_C10'] = utf8_encode($key->G2_C10);

                $datos[$i]['G2_C11'] = utf8_encode($key->G2_C11);

                $datos[$i]['G2_C12'] = utf8_encode($key->G2_C12);

                $datos[$i]['G2_C13'] = utf8_encode($key->G2_C13);

                $datos[$i]['G2_C14'] = utf8_encode($key->G2_C14);

                $datos[$i]['G2_C69'] = utf8_encode($key->G2_C69);
      
                $datos[$i]['principal'] = utf8_encode($key->principal);

                $imagenUser = "assets/img/user2-160x160.jpg?foto=".rand(10, 9999);
                if(file_exists("../../../pages/CampanhasImagenes/".$key->G2_ConsInte__b.".jpg")){
                    $imagenUser = "pages/CampanhasImagenes/".$key->G2_ConsInte__b.".jpg?foto=".rand(10, 9999);
                }

                $datos[$i]['imagenes'] = $imagenUser;   

                $i++;
            }
            echo json_encode($datos);
        }

        if(isset($_POST['traer_Flujograma'])){
            echo "{
    \"class\": \"go.GraphLinksModel\",
    \"linkFromPortIdProperty\": \"fromPort\",
    \"linkToPortIdProperty\": \"toPort\",
    \"nodeDataArray\": [";
            $Lsql = "SELECT * FROM {$BaseDatos_systema}.ESTPAS WHERE md5(concat('".clave_get."', ESTPAS_ConsInte__ESTRAT_b)) = '".$_POST['id']."'";
            $res_Pasos = $mysqli->query($Lsql);
            $x = 0;
            $separador = '';
            while ($keu = $res_Pasos->fetch_object()) {
                if($x != 0){
                    $separador = ',';
                }
                echo $separador."
        {\"category\":\"".$keu->ESTPAS_Nombre__b."\",  \"tipoPaso\": ".$keu->ESTPAS_Tipo______b.", \"figure\":\"Circle\", \"key\": ".$keu->ESTPAS_ConsInte__b.", \"loc\":\"".$keu->ESTPAS_Loc______b."\"}"."\n";
                $x++;
            }

    echo "],
    \"linkDataArray\": [ ";
           $Lsql = "SELECT * FROM {$BaseDatos_systema}.ESTCON WHERE md5(concat('".clave_get."', ESTCON_ConsInte__ESTRAT_b)) = '".$_POST['id']."'";
            $res_Pasos = $mysqli->query($Lsql);
            $x = 0;
            $separador = '';
            while ($keu = $res_Pasos->fetch_object()) {
                if($x != 0){
                    $separador = ',';
                }
               echo $separador."
        {\"from\":".$keu->ESTCON_ConsInte__ESTPAS_Des_b.", \"to\":".$keu->ESTCON_ConsInte__ESTPAS_Has_b.", \"fromPort\":\"".$keu->ESTCON_FromPort_b."\", \"toPort\":\"".$keu->ESTCON_ToPort_b."\", \"visible\":true, \"points\":".$keu->ESTCON_Coordenadas_b.", \"text\":\"".$keu->ESTCON_Comentari_b."\", \"disabled\":".$keu->ESTCON_Deshabilitado_b."}"."\n";
                $x++;
            }
    echo "
    ]
}";
        }

        if(isset($_POST['traeMiPaso'])){
            $LSql = "SELECT ESTPAS_ConsInte__b FROM ".$BaseDatos_systema.".ESTPAS WHERE md5(concat('".clave_get."', ESTPAS_ConsInte__ESTRAT_b)) = '".$_POST['idEstrat']."'";
            $res = $mysqli->query($LSql);
            $dato = $res->fetch_array();
            echo md5( clave_get . $dato['ESTPAS_ConsInte__b']);
        }
        
        if(isset($_POST['traePaso'])){
            echo md5( clave_get . $_POST['idEstrat']);
        }

        //Datos de la lista de la izquierda
        if(isset($_POST['CallDatosJson'])){
            $Lsql = 'SELECT G2_ConsInte__b as id,  G2_C7 as camp1 , TIPO_ESTRAT_Nombre____b as camp2 ';
            $Lsql .= ' FROM '.$BaseDatos_systema.'.G2 JOIN  '.$BaseDatos_systema.'.TIPO_ESTRAT ON TIPO_ESTRAT_ConsInte__b = G2_C6 WHERE G2_C5 = '.$_SESSION['HUESPED'];
            if($_POST['Busqueda'] != '' && !is_null($_POST['Busqueda'])){
                $Lsql .= ' AND (G2_C7 like "%'.$_POST['Busqueda'].'%" ';
                $Lsql .= ' OR TIPO_ESTRAT_Nombre____b like "%'.$_POST['Busqueda'].'%") ';
            }

           
            $Lsql .= ' ORDER BY G2_C7 ASC LIMIT 0, 50'; 
            $result = $mysqli->query($Lsql);
            $datos = array();
            $i = 0;
            while($key = $result->fetch_object()){
                $datos[$i]['camp1'] = $key->camp1;
                $datos[$i]['camp2'] = $key->camp2;
                $datos[$i]['id'] = url::urlSegura($key->id);
                $i++;
            }
            echo json_encode($datos);
        }


        //Esto ya es para cargar los combos en la grilla

        if(isset($_GET['CallDatosLisop_'])){
            $lista = $_GET['idLista'];
            $comboe = $_GET['campo'];
            $Lsql = "SELECT LISOPC_ConsInte__b AS OPCION_ConsInte__b, LISOPC_Nombre____b AS OPCION_Nombre____b FROM ".$BaseDatos_systema.".LISOPC WHERE LISOPC_ConsInte__OPCION_b = ".$lista." ORDER BY LISOPC_Nombre____b";
            
            $combo = $mysqli->query($Lsql);
            echo '<select class="form-control input-sm"  name="'.$comboe.'" id="'.$comboe.'">';
            echo '<option value="0">Seleccione</option>';
            while($obj = $combo->fetch_object()){
                echo "<option value='".$obj->OPCION_ConsInte__b."'>".$obj->OPCION_Nombre____b."</option>";
            }   
            echo '</select>'; 
        } 

        if(isset($_GET['CallDatosCombo_Guion_G2_C10'])){
            $Ysql = 'SELECT   G4_ConsInte__b as id  FROM ".$BaseDatos_systema.".G4';
            $guion = $mysqli->query($Ysql);
            echo '<select class="form-control input-sm"  name="G2_C10" id="G2_C10">';
            echo '<option ></option>';
            while($obj = $guion->fetch_object()){
               echo "<option value='".$obj->id."' dinammicos='0'></option>";
            } 
            echo '</select>';
        }

        if(isset($_GET['CallDatosCombo_Guion_G2_C11'])){
            $Ysql = 'SELECT   G4_ConsInte__b as id  FROM ".$BaseDatos_systema.".G4';
            $guion = $mysqli->query($Ysql);
            echo '<select class="form-control input-sm"  name="G2_C11" id="G2_C11">';
            echo '<option ></option>';
            while($obj = $guion->fetch_object()){
               echo "<option value='".$obj->id."' dinammicos='0'></option>";
            } 
            echo '</select>';
        }

        if(isset($_GET['CallDatosCombo_Guion_G2_C12'])){
            $Ysql = 'SELECT   G4_ConsInte__b as id  FROM ".$BaseDatos_systema.".G4';
            $guion = $mysqli->query($Ysql);
            echo '<select class="form-control input-sm"  name="G2_C12" id="G2_C12">';
            echo '<option ></option>';
            while($obj = $guion->fetch_object()){
               echo "<option value='".$obj->id."' dinammicos='0'></option>";
            } 
            echo '</select>';
        }

        if(isset($_GET['CallDatosCombo_Guion_G2_C13'])){
            $Ysql = 'SELECT   G4_ConsInte__b as id  FROM ".$BaseDatos_systema.".G4';
            $guion = $mysqli->query($Ysql);
            echo '<select class="form-control input-sm"  name="G2_C13" id="G2_C13">';
            echo '<option ></option>';
            while($obj = $guion->fetch_object()){
               echo "<option value='".$obj->id."' dinammicos='0'></option>";
            } 
            echo '</select>';
        }


        if(isset($_POST['CallEliminate'])){
            if($_POST['oper'] == 'del'){
                $Lsql = "DELETE FROM ".$BaseDatos_systema.".G2 WHERE G2_ConsInte__b = ".$_POST['id'];
                if ($mysqli->query($Lsql) === TRUE) {
                    echo "1";
                } else {
                    echo "Error eliminado los registros : " . $mysqli->error;
                }
            }
        }

        if(isset($_POST['callDatosNuevamente'])){
            $inicio = $_POST['inicio'];
            $fin = $_POST['fin'];
            $Zsql = 'SELECT  G2_ConsInte__b as id,  G2_C7 as camp1 , G2_C8 as camp2  FROM '.$BaseDatos_systema.'.$BaseDatos_systema.".G2   WHERE G2_C5 = '.$_SESSION['HUESPED'].'  ORDER BY G2_ConsInte__b DESC LIMIT '.$inicio.' , '.$fin;
            $result = $mysqli->query($Zsql);
            while($obj = $result->fetch_object()){
                echo "<tr class='CargarDatos' id='".$obj->id."'>
                    <td>
                        <p style='font-size:14px;'><b>".($obj->camp1)."</b></p>
                        <p style='font-size:12px; margin-top:-10px;'>".($obj->camp2)."</p>
                    </td>
                </tr>";
            } 
        }
          
        //Insertar Extras en caso de haber
        


        //Inserciones o actualizaciones

        if(isset($_POST["oper"]) && isset($_GET['insertarDatosGrilla'])){
            $Lsql  = '';

            $validar = 0;
            $LsqlU = "UPDATE ".$BaseDatos_systema.".G2 SET "; 
            $LsqlI = "INSERT INTO ".$BaseDatos_systema.".G2(";
            $LsqlV = " VALUES ("; 
  
            $G2_C5 = NULL;
        //este es de tipo numero no se deja ir asi '', si est avacio lo mejor es no mandarlo
        if (isset($_POST["G2_C5"])) {
            if ($_POST["G2_C5"] != '') {
                $separador = "";
                if ($validar == 1) {
                    $separador = ",";
                }

                $G2_C5 = str_replace(".", "", $_POST["G2_C5"]);
                $G2_C5 =  str_replace(",", ".", $G2_C5);
                $LsqlU .= $separador . " G2_C5 = '" . $G2_C5 . "'";
                $LsqlI .= $separador . " G2_C5";
                $LsqlV .= $separador . "'" . $G2_C5 . "'";
                
                $G2_C6 = 3;
                $separador = ",";
                $LsqlU .= "{$separador} G2_C6 = '{$G2_C6}'";
                $LsqlI .= "{$separador} G2_C6";
                $LsqlV .= "{$separador} '{$G2_C6}'";
                $validar = 1;
            }
        }


        if (isset($_POST["G2_C7"])) {
            $separador = "";
            if ($validar == 1) {
                $separador = ",";
            }

            $LsqlU .= $separador . "G2_C7 = '" . $_POST["G2_C7"] . "'";
            $LsqlI .= $separador . "G2_C7";
            $LsqlV .= $separador . "'" . $_POST["G2_C7"] . "'";
            $validar = 1;
        }
             
  
            if(isset($_POST["G2_C8"])){
                $separador = "";
                if($validar == 1){
                    $separador = ",";
                }

                $LsqlU .= $separador."G2_C8 = '".$_POST["G2_C8"]."'";
                $LsqlI .= $separador."G2_C8";
                $LsqlV .= $separador."'".$_POST["G2_C8"]."'";
                $validar = 1;
            }
             
  
            if(isset($_POST["G2_C9"])){
                $separador = "";
                if($validar == 1){
                    $separador = ",";
                }

                $LsqlU .= $separador."G2_C9 = '".$_POST["G2_C9"]."'";
                $LsqlI .= $separador."G2_C9";
                $LsqlV .= $separador."'".$_POST["G2_C9"]."'";
                $validar = 1;
            }
             
  
            if(isset($_POST["G2_C10"])){
                $separador = "";
                if($validar == 1){
                    $separador = ",";
                }

                $LsqlU .= $separador."G2_C10 = '".$_POST["G2_C10"]."'";
                $LsqlI .= $separador."G2_C10";
                $LsqlV .= $separador."'".$_POST["G2_C10"]."'";
                $validar = 1;
            }
             
  
            if(isset($_POST["G2_C11"])){
                $separador = "";
                if($validar == 1){
                    $separador = ",";
                }

                $LsqlU .= $separador."G2_C11 = '".$_POST["G2_C11"]."'";
                $LsqlI .= $separador."G2_C11";
                $LsqlV .= $separador."'".$_POST["G2_C11"]."'";
                $validar = 1;
            }
             
  
            if(isset($_POST["G2_C12"])){
                $separador = "";
                if($validar == 1){
                    $separador = ",";
                }

                $LsqlU .= $separador."G2_C12 = '".$_POST["G2_C12"]."'";
                $LsqlI .= $separador."G2_C12";
                $LsqlV .= $separador."'".$_POST["G2_C12"]."'";
                $validar = 1;
            }
             
  
            if(isset($_POST["G2_C13"])){
                $separador = "";
                if($validar == 1){
                    $separador = ",";
                }

                $LsqlU .= $separador."G2_C13 = '".$_POST["G2_C13"]."'";
                $LsqlI .= $separador."G2_C13";
                $LsqlV .= $separador."'".$_POST["G2_C13"]."'";
                $validar = 1;
            }


            if(isset($_POST["G2_C69"])){
                if($_POST["G2_C69"] != ''){
                    $separador = "";
                    if($validar == 1){
                        $separador = ",";
                    }

                    $LsqlU .= $separador."G2_C69 = '".$_POST["G2_C69"]."'";
                    $LsqlI .= $separador."G2_C69";
                    $LsqlV .= $separador."'".$_POST["G2_C69"]."'";
                    $validar = 1;
                }
                
            }

            if(isset($_POST['G10_C74'])){
                if($_POST["G10_C74"] != '' && $_POST["G10_C74"] != '0'){
                    $separador = "";
                    if($validar == 1){
                        $separador = ",";
                    }

                    $LsqlU .= $separador."G2_C69 = '".$_POST["G10_C74"]."'";
                    $LsqlI .= $separador."G2_C69";
                    $LsqlV .= $separador."'".$_POST["G10_C74"]."'";
                    $validar = 1;
                }
                
            }
             
  
            if(isset($_POST["G2_C14"])){
                $separador = "";
                if($validar == 1){
                    $separador = ",";
                }

                if(empty($_POST["G2_C14"]) || is_null($_POST["G2_C14"]) ){       
                    $LsqlU .= $separador."G2_C14 = '".$_POST["txtOcultoColor"]."'";
                    $LsqlI .= $separador."G2_C14";
                    $LsqlV .= $separador."'".$_POST["txtOcultoColor"]."'";
                }else{
                    $LsqlU .= $separador."G2_C14 = '".$_POST["G2_C14"]."'";
                    $LsqlI .= $separador."G2_C14";
                    $LsqlV .= $separador."'".$_POST["G2_C14"]."'";
                }
                
                $validar = 1;
            }else{
                $separador = "";
                if($validar == 1){
                    $separador = ",";
                }
                $LsqlU .= $separador."G2_C14 = '".color_rand()."'";
                $LsqlI .= $separador."G2_C14";
                $LsqlV .= $separador."'".color_rand()."'";
                $validar = 1;
            }
             
 
            $padre = NULL;
            //este es de tipo date hay que preguntar si esta vacia o no
            if(isset($_POST["padre"])){    
                if($_POST["padre"] != '0' && $_POST['padre'] != ''){
                    $separador = "";
                    if($validar == 1){
                        $separador = ",";
                    }

                    //primero hay que ir y buscar los campos
                    $Lsql = "SELECT GUIDET_ConsInte__PREGUN_De1_b FROM ".$BaseDatos_systema.".GUIDET WHERE GUIDET_ConsInte__GUION__Mae_b = ".$_POST['formpadre']." AND GUIDET_ConsInte__GUION__Det_b = ".$_POST['formhijo'];

                    $GuidRes = $mysqli->query($Lsql);
                    $campo = null;
                    while($ky = $GuidRes->fetch_object()){
                        $campo = $ky->GUIDET_ConsInte__PREGUN_De1_b;
                    }
                    $valorG = "G2_C";
                    $valorH = $valorG.$campo;
                    $LsqlU .= $separador." " .$valorH." = ".$_POST["padre"];
                    $LsqlI .= $separador." ".$valorH;
                    $LsqlV .= $separador.$_POST['padre'] ;
                    $validar = 1;
                }
            }

            if(isset($_GET['id_gestion_cbx'])){
                $separador = "";
                if($validar == 1){
                    $separador = ",";
                }

                $LsqlU .= $separador."G2_IdLlamada = '".$_GET['id_gestion_cbx']."'";
                $LsqlI .= $separador."G2_IdLlamada";
                $LsqlV .= $separador."'".$_GET['id_gestion_cbx']."'";
                $validar = 1;
            }

            
            if(isset($_POST['oper'])){
                if($_POST["oper"] == 'add' ){
                    $Lsql = $LsqlI.")" . $LsqlV.")";
                }else if($_POST["oper"] == 'edit' ){

                    $Lsql = $LsqlU." WHERE md5(concat('".clave_get."', G2_ConsInte__b)) = '".$_POST["id"]."'"; 
                }else if($_POST["oper"] == 'del' ){
                    
                    
                }
            }

            //si trae algo que insertar inserta

            //echo $Lsql;
            if($validar == 1){
                if ($mysqli->query($Lsql) === TRUE) {
                     
                    if($_POST["oper"] == 'add' ){
                        
                        $id_Usuario_Nuevo = $mysqli->insert_id;
                        if(isset($_FILES['txtFileCampana']['tmp_name']) && !empty($_FILES['txtFileCampana']['tmp_name'])){
                            $ruta = "../../../pages/CampanhasImagenes";
                            if(!file_exists($ruta))
                            {
                                mkdir($ruta);
                            }

                            $extencion = explode('.', basename($_FILES['txtFileCampana']['name']));   
                            $target_path = $ruta.'/'.$id_Usuario_Nuevo.'.jpg'; 
                            $target_path = str_replace(' ', '', $target_path);
                            
                            copy($_FILES['txtFileCampana']['tmp_name'], $target_path);
                            $imagenNombre = $id_Usuario_Nuevo.'.jpg'; 
                            $imagenNombre = str_replace(' ', '', $imagenNombre);
                        }

                        /*ctrCrearReportesDiarios($id_Usuario_Nuevo);
                        ctrCrearReportesSemanales($id_Usuario_Nuevo);
                        ctrCrearReportesMensuales($id_Usuario_Nuevo);
                        */
                        guardar_auditoria("INSERTAR", "INSERTAR REGISTRO EN G2");
                        echo json_encode(["idEstrategia" => $id_Usuario_Nuevo]);

                    }else if($_POST["oper"] == 'edit' ){

                        if(isset($_FILES['txtFileCampana']['tmp_name']) && !empty($_FILES['txtFileCampana']['tmp_name'])){
                            
                            $ruta = "../../../pages/CampanhasImagenes";
                            if(!file_exists($ruta))
                            {
                                mkdir($ruta);
                            }

                            
  
                            $extencion = explode('.', basename($_FILES['txtFileCampana']['name']));   
                            $target_path = $ruta.'/'.$_POST['id'].'.jpg'; 
                            $target_path = str_replace(' ', '', $target_path);
                            
                            if(file_exists($target_path)){
                                unlink($target_path);
                            }

                            $ver = (float)phpversion();
                            if ($ver > 7.0) {
                                $nuevoAncho = 500;
                                $nuevoAlto  = 500;
                                list($ancho, $alto) = getimagesize($_FILES['txtFileCampana']['tmp_name']);
                                $origen  = imagecreatefromjpeg($_FILES['txtFileCampana']['tmp_name']);
                                //le decimos que vamos acrear una imagen en el destino con esos ancho y alto
                                $destino = imagecreatetruecolor($nuevoAncho, $nuevoAlto);
                                //cortamos esta imagen

                                
                                imagecopyresized($destino, $origen, 0, 0, 0, 0, $nuevoAncho, $nuevoAlto, $ancho, $alto);
                                imagejpeg($destino, $target_path);
                            }else{
                                copy($_FILES['txtFileCampana']['tmp_name'], $target_path);
                            }
                            

                            $imagenNombre = $_POST["id"].'.jpg'; 
                            $imagenNombre = str_replace(' ', '', $imagenNombre);
                        }
                        //JDBD actualizamos todos los reportes y los cambios nuevos.
                        if(isset($_POST['totalGuardadosAsuntos'])){
                            //JDBD recorremos la cantidad de reportes guardados de la estrategias.
                            for ($i = 0; $i < $_POST['totalGuardadosAsuntos']; $i++) { 
                                if(isset($_POST['txtAsuntosGuardados_'.$i]) && $_POST['txtAquienVa_'.$i] != '' && $_POST['txtNombreReporte_'.$i] != '' && $_POST['txtHoraEnvio_'.$i] != '' ){
                                        $sqlIdEstrategia=$mysqli->query("select ESTRAT_ConsInte__b as id from {$BaseDatos_systema}.ESTRAT where md5(concat('".clave_get."', ESTRAT_ConsInte__b)) = '".$_POST["id"]."'");
                                        if($sqlIdEstrategia && $sqlIdEstrategia->num_rows==1){
                                            $sqlIdEstrategia=$sqlIdEstrategia->fetch_object();
                                            $intIdEstrat_t = $sqlIdEstrategia->id;
                                        }
                                        $strDestinatario_t = $_POST['txtAquienVa_'.$i];
                                        $strCopia_t = $_POST['txtCopiaA_'.$i];
                                        $strHoraEnvio_t = $_POST['txtHoraEnvio_'.$i];
                                        $intPeriodicidad_t = $_POST['cmbPeriodicidad_'.$i];
                                        $strNombreReport_t = $_POST['txtNombreReporte_'.$i];
                                        $intIdHuesped_t = $_SESSION['HUESPED'];
                                        $intIdReporte_t = $_POST['txtAsuntosGuardados_'.$i];

                                    //JDBD actualizamos los reportes que no sean tipo adherencia.
                                    if ($intPeriodicidad_t != '4') {

                                        $strUp_t = updateNuevoReporte($intIdEstrat_t,$strDestinatario_t,$strCopia_t,$intPeriodicidad_t,$strHoraEnvio_t,$strNombreReport_t,$intIdReporte_t);

                                    }else{
                                        //JDBD actualizamos los reportes de tipo adherencia.
                                        $updateSQL = "UPDATE ".$BaseDatos_general.".reportes_automatizados SET destinatarios = '".$strDestinatario_t."' , destinatarios_cc = '".$strCopia_t."', momento_envio = '".$strHoraEnvio_t."', asunto = '".$strNombreReport_t."' WHERE id = ".$intIdReporte_t;
                                        $res = $mysqli->query($updateSQL); 
                                        ctrCrearReportesDirarioAdherencia($intIdEstrat_t,$intIdHuesped_t,null,null,null,"::UPDATE::",null,true);
                                    }

                                }
                            }
                        }

                        //JDBD vamos a insertar los nuevos reportes.
                        if(isset($_POST['contarAsuntos']) && $_POST['contarAsuntos'] != 1){
                            //JDBD recorremos la cantidad de reportes nuevos.
                            for ($i=0; $i < $_POST['contarAsuntos']; $i++) { 
                                if(isset($_POST['GtxtNombreReporte_'.$i]) && $_POST['GtxtNombreReporte_'.$i] != ''  && $_POST['GtxtAquienVa_'.$i] != ''  && $_POST['GtxtHoraEnvio_'.$i] != '' ){
                                    $sqlIdEstrategia=$mysqli->query("select ESTRAT_ConsInte__b as id from {$BaseDatos_systema}.ESTRAT where md5(concat('".clave_get."', ESTRAT_ConsInte__b)) = '".$_POST["id"]."'");
                                    if($sqlIdEstrategia && $sqlIdEstrategia->num_rows==1){
                                        $sqlIdEstrategia=$sqlIdEstrategia->fetch_object();
                                        $intIdEstrat_t = $sqlIdEstrategia->id;
                                    }
                                    $strNombreReport_t = $_POST['GtxtNombreReporte_'.$i];
                                    $strDestinatario_t = $_POST['GtxtAquienVa_'.$i];
                                    $strCopia_t = $_POST['GtxtCopiaA_'.$i];
                                    $strHoraEnvio_t = $_POST['GtxtHoraEnvio_'.$i];
                                    $intPeriodicidad_t = $_POST['GcmbPeriodicidad_'.$i];
                                    $intIdHuesped_t = $_SESSION['HUESPED'];

                                    $strProyecto_t = str_replace(' ', '', $_SESSION['PROYECTO']);
                                    $strProyecto_t = substr($strProyecto_t, 0, 8);
                                    $strProyecto_t = sanear_strings($strProyecto_t);
                                    $strNomEstrat_t = datosEstrat($intIdEstrat_t);
                                    $strRutaArchivo_t = "/tmp/".$strProyecto_t."_".$strNomEstrat_t;
                                    $strRutaArchivo_t = str_replace(' ', '_', $strRutaArchivo_t);
                                    $strRutaArchivo_t = quitarTildes($strRutaArchivo_t);

                                    //JDBD validamos el tipo de reporte para el nombre del excell e insertamos.
                                    switch ($intPeriodicidad_t) {
                                        case '1':
                                            $strRutaArchivo_t .= "_DIARIO_";
                                            insertarNuevoReporte($strNombreReport_t,$strRutaArchivo_t,$strDestinatario_t,$strHoraEnvio_t,$strCopia_t,$intPeriodicidad_t,$intIdEstrat_t,$intIdHuesped_t,true);
                                            break;
                                        case '2':
                                            $strRutaArchivo_t .= "_SEMANAL_";
                                            insertarNuevoReporte($strNombreReport_t,$strRutaArchivo_t,$strDestinatario_t,$strHoraEnvio_t,$strCopia_t,$intPeriodicidad_t,$intIdEstrat_t,$intIdHuesped_t,true);
                                            break;
                                        case '3':
                                            $strRutaArchivo_t .= "_MENSUAL_";
                                            insertarNuevoReporte($strNombreReport_t,$strRutaArchivo_t,$strDestinatario_t,$strHoraEnvio_t,$strCopia_t,$intPeriodicidad_t,$intIdEstrat_t,$intIdHuesped_t,true);
                                            break;
                                        case '4':
                                            ctrCrearReportesDirarioAdherencia($intIdEstrat_t,$intIdHuesped_t, $strDestinatario_t,$strCopia_t,null,$strNombreReport_t,$strHoraEnvio_t,true);
                                            break;            
                                    }
                                }
                            }
                            
                        }

                        if(isset($_POST['contadorMetasViejas'])){
                            $sqlIdEstrategia=$mysqli->query("select ESTRAT_ConsInte__b as id from {$BaseDatos_systema}.ESTRAT where md5(concat('".clave_get."', ESTRAT_ConsInte__b)) = '".$_POST["id"]."'");
                            if($sqlIdEstrategia && $sqlIdEstrategia->num_rows==1){
                                $sqlIdEstrategia=$sqlIdEstrategia->fetch_object();
                                $intIdEstrat_t = $sqlIdEstrategia->id;
                            }
                            $arrPasos_t = obtenerPasos($intIdEstrat_t);

                            if (count($arrPasos_t) > 0) {
                                foreach ($arrPasos_t as $key => $bola) {

                                    switch ($bola["ESTPAS_Tipo______b"]) {
                                        case 1:
                                            //JDBD - iniciamos la insercion de las metas entrantes
                                            insertarMetas($intIdEstrat_t,$bola["ESTPAS_ConsInte__b"],1);
                                            break;
                                        case 6:
                                            //JDBD - iniciamos la insercion de las metas salientes
                                            insertarMetas($intIdEstrat_t,$bola["ESTPAS_ConsInte__b"],6);
                                            break;
                                    }

                                } 
                            }
                                
                        }
 
                        guardar_auditoria("ACTUALIZAR", "ACTUALIZO EL REGISTRO # ".$intIdEstrat_t." EN G2");
                        echo '0';
                    }else if($_POST["oper"] == 'del' ){
                        $sqlIdEstrategia=$mysqli->query("select ESTRAT_ConsInte__b as id from {$BaseDatos_systema}.ESTRAT where md5(concat('".clave_get."', ESTRAT_ConsInte__b)) = '".$_POST["id"]."'");
                        if($sqlIdEstrategia && $sqlIdEstrategia->num_rows==1){
                            $sqlIdEstrategia=$sqlIdEstrategia->fetch_object();
                            $intIdEstrat_t = $sqlIdEstrategia->id;
                        }
                        $Lsql_InsercionRepor = "DELETE FROM ".$BaseDatos_general.".reportes_automatizados WHERE id_estrategia =".$intIdEstrat_t;
                        if($mysqli->query($Lsql_InsercionRepor) === true ){
                            guardar_auditoria("ELIMINAR", "ELIMINO EL REGISTRO # ".$intIdEstrat_t." EN G2");
                            echo '1';
                        }else{
                            echo $mysqli->error;
                        }
                    }
                } else {
                    echo json_encode(array('code' => '-2' , 'messaje' => $mysqli->errno));
                }
            }
        }

        if(isset($_POST['crearRegistroNuevo'])){
            $LsqlI = "INSERT INTO ".$BaseDatos_systema.".G2(G2_C5) VALUES (".$_SESSION['HUESPED'].")";
            if ($mysqli->query($LsqlI) === TRUE) {
                echo $mysqli->insert_id;
            }
        }

        
        if(isset($_GET["insertarDatosSubgrilla_0"])){
            
            if(isset($_POST["oper"])){
                $Lsql  = '';

                $validar = 0;
                $LsqlU = "UPDATE ".$BaseDatos_systema.".G4 SET "; 
                $LsqlI = "INSERT INTO ".$BaseDatos_systema.".G4(";
                $LsqlV = " VALUES ("; 
     
                                                                             
                if(isset($_POST["G4_C21"])){
                    $separador = "";
                    if($validar == 1){
                        $separador = ",";
                    }

                    $LsqlU .= $separador."G4_C21 = '".$_POST["G4_C21"]."'";
                    $LsqlI .= $separador."G4_C21";
                    $LsqlV .= $separador."'".$_POST["G4_C21"]."'";
                    $validar = 1;
                }
                                                                              
                                                                               
     
                $G4_C22= NULL;
                //este es de tipo numero no se deja ir asi '', si est avacio lo mejor es no mandarlo
                if(isset($_POST["G4_C22"])){    
                    if($_POST["G4_C22"] != ''){
                        $separador = "";
                        if($validar == 1){
                            $separador = ",";
                        }

                        $G4_C22 = $_POST["G4_C22"];
                        $LsqlU .= $separador." G4_C22 = '".$G4_C22."'";
                        $LsqlI .= $separador." G4_C22";
                        $LsqlV .= $separador."'".$G4_C22."'";
                        $validar = 1;
                    }
                }
     
                $G4_C24= NULL;
                //este es de tipo numero no se deja ir asi '', si est avacio lo mejor es no mandarlo
                if(isset($_POST["G4_C24"])){    
                    if($_POST["G4_C24"] != ''){
                        $separador = "";
                        if($validar == 1){
                            $separador = ",";
                        }

                        $G4_C24 = $_POST["G4_C24"];
                        $LsqlU .= $separador." G4_C24 = '".$G4_C24."'";
                        $LsqlI .= $separador." G4_C24";
                        $LsqlV .= $separador."'".$G4_C24."'";
                        $validar = 1;
                    }
                }
     
                $G4_C25= NULL;
                //este es de tipo numero no se deja ir asi '', si est avacio lo mejor es no mandarlo
                if(isset($_POST["G4_C25"])){    
                    if($_POST["G4_C25"] != ''){
                        $separador = "";
                        if($validar == 1){
                            $separador = ",";
                        }

                        $G4_C25 = $_POST["G4_C25"];
                        $LsqlU .= $separador." G4_C25 = '".$G4_C25."'";
                        $LsqlI .= $separador." G4_C25";
                        $LsqlV .= $separador."'".$G4_C25."'";
                        $validar = 1;
                    }
                }
     
                $G4_C26= NULL;
                //este es de tipo numero no se deja ir asi '', si est avacio lo mejor es no mandarlo
                if(isset($_POST["G4_C26"])){    
                    if($_POST["G4_C26"] != ''){
                        $separador = "";
                        if($validar == 1){
                            $separador = ",";
                        }

                        $G4_C26 = $_POST["G4_C26"];
                        $LsqlU .= $separador." G4_C26 = '".$G4_C26."'";
                        $LsqlI .= $separador." G4_C26";
                        $LsqlV .= $separador."'".$G4_C26."'";
                        $validar = 1;
                    }
                }
     
                $G4_C27= NULL;
                //este es de tipo numero no se deja ir asi '', si est avacio lo mejor es no mandarlo
                if(isset($_POST["G4_C27"])){    
                    if($_POST["G4_C27"] != ''){
                        $separador = "";
                        if($validar == 1){
                            $separador = ",";
                        }

                        $G4_C27 = $_POST["G4_C27"];
                        $LsqlU .= $separador." G4_C27 = '".$G4_C27."'";
                        $LsqlI .= $separador." G4_C27";
                        $LsqlV .= $separador."'".$G4_C27."'";
                        $validar = 1;
                    }
                }

                if(isset($_POST["Padre"])){
                    if($_POST["Padre"] != ''){
                        //esto es porque el padre es el entero
                        
                        $numero = $_POST["Padre"];
             

                        $G4_C23 = $numero;
                        $LsqlU .= ", G4_C23 = ".$G4_C23."";
                        $LsqlI .= ", G4_C23";
                        $LsqlV .= ",".$_POST["Padre"];
                    }
                }  



                if(isset($_POST['oper'])){
                    if($_POST["oper"] == 'add' ){
                        $Lsql = $LsqlI.")" . $LsqlV.")";
                    }else if($_POST["oper"] == 'edit' ){
                        $Lsql = $LsqlU." WHERE G4_ConsInte__b =".$_POST["providerUserId"]; 
                    }else if($_POST['oper'] == 'del'){
                        $Lsql = "DELETE FROM  ".$BaseDatos_systema.".G4 WHERE G4_ConsInte__b = ".$_POST['id'];
                        $validar = 1;
                    }
                }

                if($validar == 1){
                    // echo $Lsql;
                        $id_Usuario_Nuevo = 1;
                    if ($mysqli->query($Lsql) === TRUE) {
                        $id_Usuario_Nuevo = $mysqli->insert_id;

                        if($_POST["oper"] == 'add' ){
                            guardar_auditoria("INSERTAR", "INSERTAR REGISTRO EN G4");
                        }else if($_POST["oper"] == 'edit' ){
                            guardar_auditoria("ACTUALIZAR", "ACTUALIZO EL REGISTRO # ".$_POST["padre"]." EN G4");
                        }else if($_POST["oper"] == 'del' ){
                           guardar_auditoria("ELIMINAR", "ELIMINO EL REGISTRO # ".$_POST['id']." EN G4");
                        }

                        echo $id_Usuario_Nuevo ;
                    } else {
                        echo "Error Hacieno el proceso los registros : " . $mysqli->error;
                    }  
                }  
            }
        }
                                        
            

        

        if(isset($_GET["callDatosSubgrilla_0"])){

            $id = $_GET['id'];  

            
            $numero = $id;
                    

            $SQL = "SELECT G4_ConsInte__b, G4_C21, G4_C22, G4_C23, G4_C24, G4_C25, G4_C26, G4_C27 FROM ".$BaseDatos_systema.".G4  ";

            $SQL .= " WHERE G4_C23 = ".$numero; 

            $PEOBUS_VeRegPro__b = 0 ;
            $idUsuario = $_SESSION['IDENTIFICACION'];
            $peobus = "SELECT * FROM ".$BaseDatos_systema.".PEOBUS WHERE PEOBUS_ConsInte__USUARI_b = ".$idUsuario." AND PEOBUS_ConsInte__GUION__b = 4";
            $query = $mysqli->query($peobus);
            

            while ($key =  $query->fetch_object()) {
                $PEOBUS_VeRegPro__b = $key->PEOBUS_VeRegPro__b ;
            }

            if($PEOBUS_VeRegPro__b != 0){
                $SQL .= " AND G4_Usuario = ".$idUsuario;
            }
        
            $SQL .= " ORDER BY G4_C21";

          //  echo $SQL;
            if ( stristr($_SERVER["HTTP_ACCEPT"],"application/xhtml+xml") ) { 
                header("Content-type: application/xhtml+xml;charset=utf-8"); 
            } else { 
                header("Content-type: text/xml;charset=utf-8"); 
            } 

            $et = ">"; 
            echo "<?xml version='1.0' encoding='utf-8'?$et\n"; 
            echo "<rows>"; // be sure to put text data in CDATA

            $result = $mysqli->query($SQL);
            while( $fila = $result->fetch_object() ) {
                echo "<row asin='".$fila->G4_ConsInte__b."'>"; 
                echo "<cell>". ($fila->G4_ConsInte__b)."</cell>"; 
                

                echo "<cell>". ($fila->G4_C21)."</cell>";

                echo "<cell>". $fila->G4_C22."</cell>"; 



                echo "<cell>". $fila->G4_C25."</cell>"; 

                echo "<cell>". $fila->G4_C26."</cell>"; 

                echo "<cell>". $fila->G4_C27."</cell>"; 
                echo "</row>"; 
            } 
            echo "</rows>"; 
        }


        if(isset($_POST['guardar_flugrama'])){
            if(isset($_POST['mySavedModel'])){
                if(!empty($_POST['mySavedModel'])){
                    //$jsonArecorrer = json_decode($_POST['mySavedModel']);
                    $strin1 = str_replace("\\n","",$_POST['mySavedModel']);
                    $strin1 = str_replace('\\',"",$strin1);
                    $jsonArecorrer = json_decode($strin1);                    
                    $nodeArray = $jsonArecorrer->nodeDataArray;
                    $id_Usuario_Nuevo = $_POST['id_estrategia'];
                    $flujogramaAnterior = null;

                    $Xsql_json = "SELECT G2_ConsInte__b,G2_C9  FROM ".$BaseDatos_systema.".G2 WHERE md5(concat('".clave_get."', G2_ConsInte__b)) = '{$id_Usuario_Nuevo}'";
                    $cur_XsqlJson = $mysqli->query($Xsql_json);
                    $res_Cur_XsqlJson = $cur_XsqlJson->fetch_array();
                    $G2_id=$res_Cur_XsqlJson['G2_ConsInte__b'];
                    if(!empty($res_Cur_XsqlJson['G2_C9'])){
                        $flujogramaAnterior = json_decode($res_Cur_XsqlJson['G2_C9']);
                    }
                    


                    $Lsql = "UPDATE ".$BaseDatos_systema.".G2 SET G2_C9 = '".$strin1."' WHERE md5(concat('".clave_get."', G2_ConsInte__b)) = '{$id_Usuario_Nuevo}'";
                    if($mysqli->query($Lsql)===TRUE){
                        guardar_auditoria("ACTUALIZO", "ACTUALIZO EL FLUJOGRAMA DE LA ESTRATEGIA ".$G2_id);
                    }

                    $valido = 0;
                    $estPasId = 0;
                    $i = 0;
                    foreach ($nodeArray as $key) {

                        //primero validamos que ese paso exista
                        $Lsql = "SELECT * FROM ".$BaseDatos_systema.".ESTPAS WHERE ESTPAS_ConsInte__b = ".$key->key;
                        $queri_Lsql = $mysqli->query($Lsql);
                        if($queri_Lsql->num_rows > 0){ // Este paso existe
                            $resUlt = $queri_Lsql->fetch_array();
                            $LsqlPas = "UPDATE ".$BaseDatos_systema.".ESTPAS SET ESTPAS_ConsInte__ESTRAT_b = ".$G2_id.", ESTPAS_Nombre__b = '".$key->category."', ESTPAS_Tipo______b = ".$key->tipoPaso.", ESTPAS_Loc______b = '".$key->loc."', ESTPAS_key_____b = '".$key->key."' WHERE ESTPAS_ConsInte__b = ".$key->key;

                            if($mysqli->query($LsqlPas) === true){

                                $valido = 1;
                                if($key->tipoPaso == '7' || $key->tipoPaso == '8' || $key->tipoPaso == '9' || $key->tipoPaso == '13'){
                                    $id_Muestras = $resUlt['ESTPAS_ConsInte__MUESTR_b'];
                                    $id_Guion = $_POST['poblacion'];
                                    $Lsql = "SELECT * FROM ".$BaseDatos.".G".$id_Guion."_M".$id_Muestras;
                                    $resMuestra = $mysqli->query($Lsql);
                                    if($resMuestra){
                                        //Perfecto la muestra Existe y no nospreocupamos 
                                    }else{
                                        //Nooooooooooooo, It is a tramp, not found the fuck muestra creation, Why..Wilson Whyyyy
                                        $CreateMuestraLsql = "CREATE TABLE `".$BaseDatos."`.`G".$id_Guion."_M".$id_Muestras."` (
                                                  `G".$id_Guion."_M".$id_Muestras."_CoInMiPo__b` int(10) NOT NULL,
                                                  `G".$id_Guion."_M".$id_Muestras."_ConIntUsu_b` int(10) DEFAULT NULL,
                                                  `G".$id_Guion."_M".$id_Muestras."_Activo____b` smallint(5) DEFAULT '-1',
                                                  `G".$id_Guion."_M".$id_Muestras."_FechaCreacion_b` datetime DEFAULT NOW(),
                                                  `G".$id_Guion."_M".$id_Muestras."_FechaAsignacion_b` datetime DEFAULT NULL,
                                                  `G".$id_Guion."_M".$id_Muestras."_FechaReactivacion_b` datetime DEFAULT NOW(),
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
                                                ) ENGINE=InnoDB DEFAULT CHARSET=utf8;";
                                        if($mysqli->query($CreateMuestraLsql) === true){
                                            //echo "Si creo la tabla";
                                               
                                        }else{
                                            echo "No pude crear la muetsra por => ".$mysqli->error;
                                        }
                                    }
                                }
                                guardar_auditoria("ACTUALIZO", "ACTUALIZO EL PASO CON EL ID ".$key->key);
                            }else{
                                $mysqli->error;
                            }

                        }else{ // lo estan creando nuevo
                            $LsqlPas = "INSERT INTO ".$BaseDatos_systema.".ESTPAS (ESTPAS_ConsInte__ESTRAT_b, ESTPAS_Nombre__b, ESTPAS_Tipo______b, ESTPAS_Loc______b, ESTPAS_key_____b) VALUES (".$G2_id.", '".$key->category."', ".$key->tipoPaso.", '".$key->loc."', '".$key->key."')";
                            if($mysqli->query($LsqlPas) === true){
                                $valido = 1;
                                $estPasId = $mysqli->insert_id;
                                if($key->tipoPaso == '7' || $key->tipoPaso == '8' || $key->tipoPaso == '9' || $key->tipoPaso == '13'){
                                    /* toca crear la muestra y asignarla al pinche paso */
                                    $id_Muestras = 0;
                                    $id_Guion = $_POST['poblacion'];
                                    $Lsql = "INSERT INTO ".$BaseDatos_systema.".MUESTR (MUESTR_Nombre____b, MUESTR_ConsInte__GUION__b) VALUES ('".$id_Guion."_MUESTRA_".rand()."', '".$id_Guion."')";
                                    if($mysqli->query($Lsql) === true){
                                        $id_Muestras = $mysqli->insert_id;
                                        /* toca asociarla al Paso */
                                        //echo "Entra aqui tambien y este es el id de la muestra".$id_Muestras;

                                        $CreateMuestraLsql = "CREATE TABLE `".$BaseDatos."`.`G".$id_Guion."_M".$id_Muestras."` (
                                                  `G".$id_Guion."_M".$id_Muestras."_CoInMiPo__b` int(10) NOT NULL,
                                                  `G".$id_Guion."_M".$id_Muestras."_ConIntUsu_b` int(10) DEFAULT NULL,
                                                  `G".$id_Guion."_M".$id_Muestras."_Activo____b` smallint(5) DEFAULT '-1',
                                                  `G".$id_Guion."_M".$id_Muestras."_FechaCreacion_b` datetime DEFAULT NOW(),
                                                  `G".$id_Guion."_M".$id_Muestras."_FechaAsignacion_b` datetime DEFAULT NULL,
                                                  `G".$id_Guion."_M".$id_Muestras."_FechaReactivacion_b` datetime DEFAULT NOW(),
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
                                                ) ENGINE=InnoDB DEFAULT CHARSET=utf8;";
                                        if($mysqli->query($CreateMuestraLsql) === true){
                                            //echo "Si creo la tabla";
                                            $PasoLsql = "UPDATE ".$BaseDatos_systema.".ESTPAS SET ESTPAS_ConsInte__MUESTR_b = ".$id_Muestras." WHERE ESTPAS_ConsInte__b = ".$estPasId;
                                            $mysqli->query($PasoLsql);

                                        }else{
                                            echo $mysqli->error;
                                        }
                                    }else{
                                        echo "No guardo la muestra => ".$mysqli->error;
                                    }
                                }
                                guardar_auditoria("INSERTO", "INSERTO EL PASO CON EL ID ".$key->key);
                            }else{
                                $mysqli->error;
                            }
                        }

                        //echo "  - ".$LsqlPas;
                
                        
                    }
                    


                    $datosConecciones = $jsonArecorrer->linkDataArray;

                    foreach ($datosConecciones as $conescciones) {

                        $LsqlPass = "SELECT ESTPAS_ConsInte__b FROM ".$BaseDatos_systema.".ESTPAS WHERE md5(concat('".clave_get."', ESTPAS_ConsInte__ESTRAT_b)) = '{$id_Usuario_Nuevo}' AND ESTPAS_key_____b = '".$conescciones->from."' ";
                        $f = $mysqli->query($LsqlPass);
                        $from = $f->fetch_array();

                        $LsqlPassTo = "SELECT ESTPAS_ConsInte__b FROM ".$BaseDatos_systema.".ESTPAS WHERE md5(concat('".clave_get."', ESTPAS_ConsInte__ESTRAT_b)) = '{$id_Usuario_Nuevo}' AND ESTPAS_key_____b = '".$conescciones->to."' ";
                        $t = $mysqli->query($LsqlPassTo);
                        $to = $t->fetch_array();
                        $texto = null;

                        if(isset($conescciones->text) && $conescciones->text != '' && !is_null($conescciones->text) && !empty($conescciones->text)){
                            $texto = $conescciones->text;
                        }

                        $Lsql = "SELECT * FROM {$BaseDatos_systema}.ESTCON WHERE ESTCON_ConsInte__ESTPAS_Des_b = {$from['ESTPAS_ConsInte__b']} AND ESTCON_ConsInte__ESTPAS_Has_b = {$to['ESTPAS_ConsInte__b']}";

                        $res = $mysqli->query($Lsql);
                        if(isset($conescciones->points)){
                            $conesccionesPoints = json_encode($conescciones->points);
                        }
                        if($res && $res->num_rows > 0){
                            /* existe */
                            $InsertCon = "UPDATE {$BaseDatos_systema}.ESTCON SET ESTCON_Nombre____b = 'Conector', ESTCON_FromPort_b =  '{$conescciones->fromPort}', ESTCON_ToPort_b = '{$conescciones->toPort}', ESTCON_Coordenadas_b = '{$conesccionesPoints}', ESTCON_Comentari_b = '{$texto}' WHERE ESTCON_ConsInte__ESTPAS_Des_b = {$from['ESTPAS_ConsInte__b']} AND ESTCON_ConsInte__ESTPAS_Has_b = {$to['ESTPAS_ConsInte__b']}";
                        }else{
                            /* No existe */
                            $InsertCon = "INSERT INTO {$BaseDatos_systema}.ESTCON(ESTCON_Nombre____b, ESTCON_ConsInte__ESTPAS_Des_b, ESTCON_ConsInte__ESTPAS_Has_b, ESTCON_FromPort_b, ESTCON_ToPort_b, ESTCON_Coordenadas_b, ESTCON_Comentari_b, ESTCON_ConsInte__ESTRAT_b, ESTCON_Tipo_Consulta_b, ESTCON_Tipo_Insercion_b, ESTCON_ConsInte_PREGUN_Fecha_b, ESTCON_Operacion_Fecha_b, ESTCON_ConsInte_PREGUN_Hora_b, ESTCON_Operacion_Hora_b ) VALUES ('Conector', {$from['ESTPAS_ConsInte__b']}, {$to['ESTPAS_ConsInte__b']}, '{$conescciones->fromPort}', '{$conescciones->toPort}', '{$conesccionesPoints}', '{$texto}', {$G2_id}, 0, 0, -1, 1, -1, 1)";
                        }
                        
                        // echo "InsertCon => $InsertCon";
    
                        if($mysqli->query($InsertCon) === true){
                            $valido = 1;
                        }
                    }

                    if($valido == 1){
                        if(isset($_POST['byCallback'])){
                            $idEstcon=$mysqli->insert_id;
                            $queryEs=$mysqli->query("UPDATE ".$BaseDatos_systema.".ESTCON SET ESTCON_Deshabilitado_b=-1 WHERE ESTCON_ConsInte__b={$idEstcon}");
                            echo $estPasId;
                        }else{
                            echo "1";
                        }
                    }else{
                        echo "Ocurrio un error";
                    }
                }
            }
        }

        if(isset($_POST['guardar_flugrama_simple'])){
            if(isset($_POST['mySavedModel'])){
                if(!empty($_POST['mySavedModel'])){
                    //$jsonArecorrer = json_decode($_POST['mySavedModel']);
                    $strin1 = str_replace("\\n","",$_POST['mySavedModel']);
                    $strin1 = str_replace('\\',"",$strin1);
                    $jsonArecorrer = json_decode($strin1);                    
                    $nodeArray = $jsonArecorrer->nodeDataArray;
                    $id_Usuario_Nuevo = url::urlSegura($_POST['id_estrategia']);
                    $flujogramaAnterior = null;

                    $Xsql_json = "SELECT G2_ConsInte__b,G2_C9 FROM ".$BaseDatos_systema.".G2 WHERE md5(concat('".clave_get."', G2_ConsInte__b)) = '{$id_Usuario_Nuevo}'";
                    $cur_XsqlJson = $mysqli->query($Xsql_json);
                    $res_Cur_XsqlJson = $cur_XsqlJson->fetch_array();
                    $G2_id=$res_Cur_XsqlJson['G2_ConsInte__b'];
                    if(!empty($res_Cur_XsqlJson['G2_C9'])){
                        $flujogramaAnterior = json_decode($res_Cur_XsqlJson['G2_C9']);
                    }
                    


                    $Lsql = "UPDATE ".$BaseDatos_systema.".G2 SET G2_C9 = '".$strin1."' WHERE md5(concat('".clave_get."', G2_ConsInte__b)) = '{$id_Usuario_Nuevo}'";
                    if($mysqli->query($Lsql)===TRUE){
                        guardar_auditoria("ACTUALIZO", "ACTUALIZO EL FLUJOGRAMA DE LA ESTRATEGIA ".$G2_id);
                    }

                    $Lsql = "DELETE FROM ".$BaseDatos_systema.".ESTCON WHERE md5(concat('".clave_get."', ESTCON_ConsInte__ESTRAT_b)) = '{$id_Usuario_Nuevo}'";
                    if($mysqli->query($Lsql)===TRUE){
                        guardar_auditoria("ELIMINO", "ELIMINO TODOS LOS CONECTORES DE LA ESTRATEGIA ".$G2_id);
                    }

                    $valido = 0;
                    $estPasId = 0;
      
                    foreach ($nodeArray as $key) {

                        //primero validamos que ese paso exista
                        $Lsql = "SELECT * FROM ".$BaseDatos_systema.".ESTPAS WHERE ESTPAS_ConsInte__b = ".$key->key;
                        $queri_Lsql = $mysqli->query($Lsql);
                        if($queri_Lsql->num_rows > 0){ // Este paso existe
                            $LsqlPas = "UPDATE ".$BaseDatos_systema.".ESTPAS SET ESTPAS_ConsInte__ESTRAT_b = ".$G2_id.", ESTPAS_Nombre__b = '".$key->category."', ESTPAS_Tipo______b = ".$key->tipoPaso.", ESTPAS_Loc______b = '".$key->loc."', ESTPAS_key_____b = '".$key->key."' WHERE ESTPAS_ConsInte__b = ".$key->key;

                            if($mysqli->query($LsqlPas) === true){
                                $valido = 1;
                                guardar_auditoria("ACTUALIZO", "ACTUALIZO EL PASO CON EL ID ".$key->key);
                            }else{
                                $mysqli->error;
                            }

                        }else{ // lo estan creando nuevo
                            $LsqlPas = "INSERT INTO ".$BaseDatos_systema.".ESTPAS (ESTPAS_ConsInte__ESTRAT_b, ESTPAS_Nombre__b, ESTPAS_Tipo______b, ESTPAS_Loc______b, ESTPAS_key_____b) VALUES (".$G2_id.", '".$key->category."', ".$key->tipoPaso.", '".$key->loc."', '".$key->key."')";
                            if($mysqli->query($LsqlPas) === true){
                                $valido = 1;
                                $estPasId = $mysqli->insert_id;
                                guardar_auditoria("INSERTO", "INSERTO EL PASO CON EL ID ".$key->key);
                            }else{
                                $mysqli->error;
                            }
                        }

                        //echo "  - ".$LsqlPas;
                    
                    }

                    
                    if($valido == 1){
                        echo $estPasId;
                    }else{
                        echo "Ocurrio un error";
                    }
                }
            }
        }

        if(isset($_POST['borrarFlujograma'])){

            $intIdPaso_t = $_POST['id_paso'];

            EliminarCampañaEnCascada($intIdPaso_t);
            
        }

        if(isset($_POST['borrarFlujogramaLink'])){
            $from   = $_POST['from'];
            $to     = $_POST['to'];
            $Lsql = "DELETE FROM ".$BaseDatos_systema.".ESTCON WHERE ESTCON_ConsInte__ESTPAS_Des_b = ".$from." AND ESTCON_ConsInte__ESTPAS_Has_b = ".$to;

            if($mysqli->query($Lsql)===TRUE){
                guardar_auditoria("ELIMINO", "ELIMINO LA CONECCION ".$from." A ".$to);
            }else{
                echo "error => ".$mysqli->error;
            }
        }

        if(isset($_POST['verificarPasoABorrar'])){
            
            $paso = $_POST['paso'];
            $validoParaBorrar = true;
            $estado = "ok";

            $sql = "SELECT ESTCON_Deshabilitado_b FROM {$BaseDatos_systema}.ESTCON WHERE ESTCON_ConsInte__ESTPAS_Des_b = {$paso} OR ESTCON_ConsInte__ESTPAS_Has_b = {$paso}";
            $res = $mysqli->query($sql);

            if($res && $res->num_rows > 0){
                while($row = $res->fetch_object()){
                    if($row->ESTCON_Deshabilitado_b == -1){
                        $validoParaBorrar = false;
                    }
                }
            }

            echo json_encode(["validoAEliminar" => $validoParaBorrar, "estado" => $estado]);
        }

        if(isset($_POST['obtenerNombrePasoOrigen'])){

            $pasoId = $_POST['pasoId'];
            $tipoPaso = $_POST['tipoPaso'];

            $nombrePaso = '';

            // Si el paso el cual vamos a buscar de donde se origino es correo saliente
            if($tipoPaso == 7){
                $sql = "SELECT ESTPAS_ConsInte__b AS id, ESTPAS_Comentari_b AS nombre FROM {$BaseDatos_systema}.ESTPAS a INNER JOIN {$dyalogo_canales_electronicos}.dy_chat_configuracion b ON a.ESTPAS_ConsInte__b = b.id_estpas
                    WHERE id_estpas_envio_historial = {$pasoId} LIMIT 1";
                $res = $mysqli->query($sql);

                if($res && $res->num_rows > 0){
                    $dataPaso = $res->fetch_object();
                    $nombrePaso = $dataPaso->nombre;
                }

            }

            echo json_encode(["estado" => "ok", "nombrePaso" => $nombrePaso]);
        }
    }

?>
