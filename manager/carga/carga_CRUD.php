<?php
    session_start();
    require "Excel.php";
    ini_set('display_errors', 'On');
    ini_set('display_errors', 1);
    include(__DIR__."/../pages/conexion.php"); 
    include(__DIR__."/../global/funcionesGenerales.php");
 
    function tipoCampo($intIdCampo_p){

        global $mysqli;
        global $BaseDatos_systema;

        if ($intIdCampo_p != 'ConsInte__b' && $intIdCampo_p != 'NONE') {
            
            $strSQLTipoCampo_t = "SELECT (CASE WHEN PREGUN_Tipo______b = 2 THEN 'longtext' ELSE 'varchar' END) AS tipo FROM ".$BaseDatos_systema.".PREGUN WHERE PREGUN_ConsInte__b = '".$intIdCampo_p."'";

            $resSQLTipoCampo_t = $mysqli->query($strSQLTipoCampo_t);

            $objSQLTipoCampo_t = $resSQLTipoCampo_t->fetch_object();

            return $objSQLTipoCampo_t->tipo;

        }else{

            return "varchar";

        }

    }

    function addIncrementable($intBase_p,$intCampoG_p,$strNombreBd_p,$sqlUpTabla_p){
        global $mysqli;
        global $BaseDatos_systema;

        $valido=true;
        //ACTUALIZAR EL CAMPO AUTO INCREMENTABLE DE CADA REGISTRO CREADO EN LA TABLA TEMPORAL
        while($row = $sqlUpTabla_p->fetch_object()){
            //ACTUALIZAR LA TABLA DE CONTADORES
            $update=$mysqli->query("UPDATE {$BaseDatos_systema}.CONTADORES SET CONTADORES_Valor_b = (CONTADORES_Valor_b+1) WHERE CONTADORES_ConsInte__PREGUN_b = {$intCampoG_p}");
            if($update && $mysqli->affected_rows == 1){
                // ACTUALIZAR LA COLUMNA AUTO INCREMENTAL DEL REGISTRO EN LA TABLA TEMPORAL
                $sqlUpTabla=$mysqli->query("UPDATE dy_cargue_datos.{$strNombreBd_p} SET G{$intBase_p}_C{$intCampoG_p}=(SELECT CONTADORES_Valor_b FROM {$BaseDatos_systema}.CONTADORES WHERE CONTADORES_ConsInte__PREGUN_b = {$intCampoG_p}) WHERE id={$row->id}");
                if(!$sqlUpTabla){
                    $valido=false;
                }else{
                    if($mysqli -> affected_rows == 0){
                        $valido=false;
                    }
                }
            }
        }

        return $valido;
    }

    function getCampoIncremental($intBase_p,$strNombreBd_p,$arrOrdenCampos_t,$arrCamposEnlazados_t){
        global $mysqli;
        global $BaseDatos_systema;
        // CONSULTA PARA OBTENER LOS ID INSERTADOS EN LA TABLA TEMPORAL, SE HACE EN ESTE PASO PARA SOLO EJECUTARLA UNA VEZ Y YA DESPUÉS SE ENVIA COMO PARAMETRO POR CADA CAMPO INCREMENTABLE QUE TENGA LA BD
        $sqlUpTabla=$mysqli->query("SELECT id FROM dy_cargue_datos.{$strNombreBd_p} ORDER BY id");
        if($sqlUpTabla && $sqlUpTabla ->num_rows > 0){
            // CONSULTAR SI LA BD TIENE CAMPOS INCREMENTABLES
            $sql=$mysqli->query("SELECT PREGUN_ConsInte__b FROM {$BaseDatos_systema}.PREGUN WHERE PREGUN_ConsInte__GUION__b={$intBase_p} AND PREGUN_Default___b=3002");
            if($sql && $sql->num_rows > 0){
                while($row = $sql->fetch_object()){
                    // CREAR LA COLUMNA DEL CAMPO INCREMENTAL EN LA BD
                    $sqlCreaColumna=$mysqli->query("ALTER TABLE dy_cargue_datos.{$strNombreBd_p} ADD COLUMN G{$intBase_p}_C{$row->PREGUN_ConsInte__b} INT");
                    if($sqlCreaColumna){
                        if(addIncrementable($intBase_p,$row->PREGUN_ConsInte__b,$strNombreBd_p,$sqlUpTabla)){
                            $esteCampo=[
                                "coll" => count($arrOrdenCampos_t),
                                "name" => "G{$intBase_p}_C{$row->PREGUN_ConsInte__b}",
                                "tipo" => "int"
                            ];
                            array_push($arrOrdenCampos_t,$esteCampo);

                            $esteCampo=[
                                "id_campo"=>$row->PREGUN_ConsInte__b,
                                "validador" => 1
                            ];
                            array_push($arrCamposEnlazados_t,$esteCampo);
                            $rpta=true;
                        }else{
                            $rpta=false;
                        }
                    }else{
                        $rpta=false;
                    }
                }
            }else{
                $rpta=true;
            }
        }else{
             $rpta=true;
        }

        return ["rpta" => $rpta, "arrOrdenCampos_t" =>$arrOrdenCampos_t, "arrCamposEnlazados_t" => $arrCamposEnlazados_t];
    }

    function removeCampoIncremental($intBase_p,$strCamposUpdate_t){
        // CONSULTAR SI LA BD TIENE CAMPOS INCREMENTABLES
        global $mysqli;
        global $BaseDatos_systema;
        $sql=$mysqli->query("SELECT PREGUN_ConsInte__b FROM {$BaseDatos_systema}.PREGUN WHERE PREGUN_ConsInte__GUION__b={$intBase_p} AND PREGUN_Default___b=3002");
        if($sql && $sql->num_rows > 0){
            while($row = $sql->fetch_object()){
                $campo="G{$intBase_p}_C{$row->PREGUN_ConsInte__b}";
                $strCamposUpdate_t=str_replace(",A.{$campo} = TRIM(B.{$campo})","",$strCamposUpdate_t);
            }
        }

        return $strCamposUpdate_t;
    }

    function nombreCampoP($intIdCampoP_p){

        global $mysqli;
        global $BaseDatos_systema;

        if (is_numeric($intIdCampoP_p)) {

            $strSQLNombre_t = "SELECT PREGUN_Texto_____b AS nombre FROM ".$BaseDatos_systema.".PREGUN WHERE PREGUN_ConsInte__b = ".$intIdCampoP_p;
            $resSQLNombre_t = $mysqli->query($strSQLNombre_t);
            if ($resSQLNombre_t && $resSQLNombre_t->num_rows>0) {
                return $resSQLNombre_t->fetch_object()->nombre;
            }else{
                return "Llave interna";
            }

        }else{

            return "Llave interna";

        }


    }

    function insertarLogconsulta($boo_p,$intInsertId_p=null,$intIdBase_p=null,$intIdMuestra_p=null,$intIdPaso_p=null,$strConsulta_p=null,$strAccion_p=null,$strErrores_p=null,$strUnionCampo_p=null){

        global $mysqli;

        if ($boo_p) {

            $strConsulta_t = $mysqli->real_escape_string($strConsulta_p);

            $strSQLInsertLog_t = "INSERT INTO dy_cargue_datos.log_cargador (bd_id,muestra_id,paso_id,consulta,accion,errores,usuari_id,campos_apareamiento) VALUES (".$intIdBase_p.",".$intIdMuestra_p.",".$intIdPaso_p.",'".$strConsulta_t."','".$strAccion_p."','FALLO',".$_SESSION['IDENTIFICACION'].",'".$strUnionCampo_p."')";

            $mysqli->query($strSQLInsertLog_t);

            return $mysqli->insert_id;
            
        }else{
            

            $strErrores_t = "EXITO";

            if ($strErrores_p != "" && !is_null($strErrores_p)) {

                $strErrores_t = $mysqli->real_escape_string($strErrores_p);

            }

            $strUPDATE_t = "UPDATE dy_cargue_datos.log_cargador SET errores = '".$strErrores_t."' WHERE id = ".$intInsertId_p;

            $mysqli->query($strUPDATE_t);
        }

    }

    function siNoEsNumero($strId_p){
        if (!is_numeric($strId_p)) {
            return "";
        }else{
            return "C";
        }
    }

    function valuesDefaultMt($intIdBase_p,$booTelErrado_p){

        $intActivo_t = -1;

        if ($booTelErrado_p) {

            $intActivo_t = 0;

        }

        $intEstado_t = 0;

        if ($booTelErrado_p) {

            $intEstado_t = 3;

        }

        $intClasificacion_t = 3;

        if ($booTelErrado_p) {
            $intClasificacion_t = 2;
        }

        $intGestion_t = -14;

        if ($booTelErrado_p) {
            $intGestion_t = -21;
        }

        return "A.G".$intIdBase_p."_ConsInte__b,".$intActivo_t." AS Activo____b,".$intEstado_t." AS Estado____b,".$intEstado_t." AS TipoReintentoGMI_b,0 AS NumeInte__b,0 AS CantidadIntentosGMI_b, ".$intClasificacion_t." AS ConUltGes_b, ".$intClasificacion_t." AS CoGesMaIm_b, ".$intGestion_t." AS UltiGest__b, ".$intGestion_t." AS GesMasImp_b";

    }

    function collsDefaultMt($intIdBase_p,$intIdMuestra_p){

        return "G".$intIdBase_p."_M".$intIdMuestra_p."_CoInMiPo__b,G".$intIdBase_p."_M".$intIdMuestra_p."_Activo____b,G".$intIdBase_p."_M".$intIdMuestra_p."_Estado____b,G".$intIdBase_p."_M".$intIdMuestra_p."_TipoReintentoGMI_b,G".$intIdBase_p."_M".$intIdMuestra_p."_NumeInte__b,G".$intIdBase_p."_M".$intIdMuestra_p."_CantidadIntentosGMI_b,G".$intIdBase_p."_M".$intIdMuestra_p."_ConUltGes_b,G".$intIdBase_p."_M".$intIdMuestra_p."_CoGesMaIm_b,G".$intIdBase_p."_M".$intIdMuestra_p."_UltiGest__b,G".$intIdBase_p."_M".$intIdMuestra_p."_GesMasImp_b";

    }

    function valuesDefaultBd($strNombreExcell_p,$intTiempo_p,$strFechaActual_p,$intIdOrigen_DY_p,$booTelErrado_p, $intIdPaso_p){

        $intGestion_t = -14;

        if ($booTelErrado_p) {
            $intGestion_t = -21;
        }

        $intTipoReintento_t = 0;

        if ($booTelErrado_p) {
            $intTipoReintento_t = 3;
        }

        $intClasificacion_t = 3;

        if ($booTelErrado_p) {
            $intClasificacion_t = 2;
        }

        $strOrigenDy_t = "";

        if ($intIdOrigen_DY_p != 0) {

            $strOrigenDy_t .= ",'".$strNombreExcell_p."' AS ORIGEN_DY";
            $strOrigenDy_t .= ",'".$strNombreExcell_p."' AS OrigenUltimoCargue";

        }

        return "'".$strFechaActual_p."' AS FechaInsercion,".$intGestion_t." AS UltiGest__b,".$intGestion_t." AS GesMasImp_b,".$intTipoReintento_t." AS TipoReintentoUG_b,".$intTipoReintento_t." AS TipoReintentoGMI_b,".$intClasificacion_t." AS ClasificacionUG_b,".$intClasificacion_t." AS ClasificacionGMI_b,".$intGestion_t." AS EstadoUG_b,".$intGestion_t." AS EstadoGMI_b,0 AS CantidadIntentos,0 AS CantidadIntentosGMI_b,".$intTiempo_p." AS EstadoDiligenciamiento, ".$intIdPaso_p." AS PoblacionOrigen ".$strOrigenDy_t;
    
    }

    function collsDefaultBd($intIdBase_p,$intIdOrigen_DY_p){

        $strOrigenDy_t = "";

        if ($intIdOrigen_DY_p != 0) {

            $strOrigenDy_t .= ",G".$intIdBase_p."_C".$intIdOrigen_DY_p;
            $strOrigenDy_t .= ",G".$intIdBase_p."_OrigenUltimoCargue";

        }

        return "G".$intIdBase_p."_FechaInsercion,G".$intIdBase_p."_UltiGest__b,G".$intIdBase_p."_GesMasImp_b,G".$intIdBase_p."_TipoReintentoUG_b,G".$intIdBase_p."_TipoReintentoGMI_b,G".$intIdBase_p."_ClasificacionUG_b,G".$intIdBase_p."_ClasificacionGMI_b,G".$intIdBase_p."_EstadoUG_b,G".$intIdBase_p."_EstadoGMI_b,G".$intIdBase_p."_CantidadIntentos,G".$intIdBase_p."_CantidadIntentosGMI_b,G".$intIdBase_p."_EstadoDiligenciamiento, G".$intIdBase_p."_PoblacionOrigen".$strOrigenDy_t;

    }

    function depurador($strNombreCampo_p,$intTipo_p){

        switch ($intTipo_p) {

            case 3:

                return "TRIM(".$strNombreCampo_p.")";

                break;

            case 4:

                return "TRIM(".$strNombreCampo_p.")";

                break;

            case 5:

                return "STR_TO_DATE(TRIM(".$strNombreCampo_p."),'%Y-%m-%d %H:%i:%s')";

                break;

            case 10:

                return "STR_TO_DATE(TRIM(CONCAT('".date("Y-m-d ")."',".trim($strNombreCampo_p).")),'%Y-%m-%d %H:%i:%s')";

                break;

            case 14:

                return "TRIM(".$strNombreCampo_p.")";

                break;
            
            default:
                
                return $strNombreCampo_p;

                break;
        }

    }

    function fn_validador_horas($strHora_p){
        
        $strFecha_t=date("Y-m-d ");

        $strHora_t = trim($strHora_p);

        if(is_numeric($strHora_t)){
            $strHora_t=$strHora_t * 86400;
            $strHora_t = gmdate("H:i:s",$strHora_t);
        }

        $strHora_t = date("H:i:s",strtotime($strHora_t));

        $strHora_t = $strFecha_t.$strHora_t;

        return $strHora_t;

    } 

    function fn_validador_fechas($strFecha_p){
        
        $strFecha_t = trim($strFecha_p);

        if(is_numeric($strFecha_t)){
            $intFechaNumerica_t = ($strFecha_t - 25569) * 86400;
            $strFecha_t = gmdate("Y-m-d H:i:s", $intFechaNumerica_t);
        }else{
            $strFecha_t = str_replace("/","-",$strFecha_t);
        }

        $strFecha_t = date("Y-m-d H:i:s",strtotime($strFecha_t));

        return $strFecha_t;

    } 

    function validarLongitudCampo($strNombreTablaTemporal, $strNombreCampo, $intLongitudCampo)
    {
        global $mysqli;
        # code...
        // echo "[strNombreCampo intLongitudCampo] => $strNombreCampo, $intLongitudCampo <br><br>";
        $consulta = "SELECT {$strNombreCampo} AS valor, id FROM dy_cargue_datos.{$strNombreTablaTemporal}";
        // echo "consulta[validarLongitudCampo] => $consulta <br><br>";
        $sql = mysqli_query($mysqli,$consulta);
        if (mysqli_num_rows($sql) > 0) {
            # code...
            $result = mysqli_fetch_all($sql, MYSQLI_ASSOC);
            // echo " result[validarLongitudCampo] =>", json_encode($result), "<br><br>";
            characterCut($strNombreTablaTemporal, $strNombreCampo, $result, $intLongitudCampo);
            
        }
    }
    
    function characterCut($strNombreTablaTemporal, $strNombreCampo, $arrayCadena, $intLongitudCampo)
	{
        global $mysqli;
		# se parsea la [intLongitudCampo] a int
		# se cuenta la Longitud de la variable[arrayCadena] 
        foreach ($arrayCadena as $key => $value) {
            $keyid = array_keys($value)[1];

            $intLongitudCampo = intval($intLongitudCampo);
            $intCadenaLength = mb_strlen($value['valor']) - $intLongitudCampo;
    
            if (!$intCadenaLength == 0 && mb_strlen($value['valor']) > $intLongitudCampo) {
                # Solo se corta la cade que llega del cargue, cuando sea mayor a la longitud, configurada en pregun
                $strCadena = mb_substr($value['valor'], -0, -$intCadenaLength);
                # Se actualiza la tabla del cargue, con la cadena truncada, según la longitud configurada en pregun
                $consulta = "UPDATE dy_cargue_datos.{$strNombreTablaTemporal} SET {$strNombreCampo} = '{$strCadena}' WHERE {$keyid} = {$value['id']}";

                mysqli_query($mysqli, $consulta);
                
            }
        }

	}


    // Esta funcion valida si los registros del cargue concuerdan con los patrones de marcacion de las troncales
    function validarNumeroTelefonico($intSentido_p , $intIdHuesped_p, $intIdPaso_p, $strNombreCampo_p, $strNombreBaseTemporal_p ){

        global $mysqli;
        global $BaseDatos_telefonia;

        if ($intSentido_p == 1) {

            $strSQLPatrones_t = "SELECT TRIM(patron_validacion) AS patron FROM ".$BaseDatos_telefonia.".tipos_destino WHERE patron_validacion IS NOT NULL AND patron_validacion != '' AND id_huesped = ".$intIdHuesped_p;

        }else{

            if ($intIdPaso_p == 0) {

                $strSQLPatrones_t = "SELECT TRIM(patron_validacion) AS patron FROM ".$BaseDatos_telefonia.".tipos_destino WHERE patron_validacion IS NOT NULL AND patron_validacion != '' AND id_huesped = ".$intIdHuesped_p;

            }else if ($intIdPaso_p > 0){

                $strSQLPatrones_t = "SELECT TRIM(patron_validacion) AS patron FROM ".$BaseDatos_telefonia.".pasos_troncales JOIN ".$BaseDatos_telefonia.".tipos_destino ON id_tipos_destino = tipos_destino.id WHERE id_estpas = ".$intIdPaso_p;


            }
            
        }

        $resSQLPatrones_t = $mysqli->query($strSQLPatrones_t);

        $strFalseSeparador = " ";
        $strFalseRegexp_t = "AND ( ".$strNombreCampo_p." IS NULL OR (  ";
        $strTrueRegexp_t = " AND (";
        
        while ($row = $resSQLPatrones_t->fetch_object()) {
            
            $strFalseRegexp_t .=  $strFalseSeparador.$strNombreCampo_p." NOT REGEXP ".$row->patron;
            $strTrueRegexp_t .= $strNombreCampo_p." REGEXP ".$row->patron." OR ";

            $strFalseSeparador= " AND ";

        }

        $strTrueRegexp_t = substr($strTrueRegexp_t, 0, -4);
        $strTrueRegexp_t .= ")";

        $strSQLUpdateTrue_t = "UPDATE dy_cargue_datos.".$strNombreBaseTemporal_p." SET ESTADO = 0 WHERE ESTADO != 2".$strTrueRegexp_t;
        $resSQLUpdateTrue_t = $mysqli->query($strSQLUpdateTrue_t);

        $strSQLUpdateFalse_t = "UPDATE dy_cargue_datos.".$strNombreBaseTemporal_p." SET ESTADO = 3, RESULTADO = CONCAT(COALESCE(RESULTADO,''),'El numero telefonico no es valido,') WHERE (ESTADO != 0 AND ESTADO != 2)".$strFalseRegexp_t." )) ";
        $resSQLUpdateFalse_t = $mysqli->query($strSQLUpdateFalse_t);
    }


    function fn_validar_y_traducir($strNombreBaseTemporal_p,$strNombreCampo_p,$intTipoCampo_p,$intIdCampo_p,$intValidador_p,$intProgreso_p,$intIdPaso_p,$intIdHuesped_p,$intSentido_p, $intLongitudCampo){

        global $mysqli;
        global $BaseDatos;
        global $BaseDatos_systema;
        global $BaseDatos_telefonia;
        global $BaseDatos_general;

        switch ($intTipoCampo_p) {
            case 1:    
                validarLongitudCampo($strNombreBaseTemporal_p, $strNombreCampo_p, $intLongitudCampo);
                if($intValidador_p==2){
                    validarNumeroTelefonico($intSentido_p, $intIdHuesped_p, $intIdPaso_p, $strNombreCampo_p, $strNombreBaseTemporal_p);
                }                
                return $intProgreso_p;
                break;
            case 3:

                $strSQLUpdateNull_t = "UPDATE dy_cargue_datos.".$strNombreBaseTemporal_p." SET ".$strNombreCampo_p." = NULL WHERE ".$strNombreCampo_p." = ''";
                $resSQLUpdateNull_t = $mysqli->query($strSQLUpdateNull_t);

                $strSQLUpdate_t = "UPDATE dy_cargue_datos.".$strNombreBaseTemporal_p."
                                   SET ESTADO = 2, RESULTADO = CONCAT(COALESCE(RESULTADO,''),'Debe ser un numero entero,')
                                   WHERE TRIM(".$strNombreCampo_p.") NOT REGEXP '^[+\-]?[0-9]+\\\.?[0-9]*$'";
                $resSQLUpdate_t = $mysqli->query($strSQLUpdate_t);


                if($intValidador_p==2){
                    validarNumeroTelefonico($intSentido_p, $intIdHuesped_p, $intIdPaso_p, $strNombreCampo_p, $strNombreBaseTemporal_p);
                }      
                

                return $intProgreso_p;
                break;
            case 4:

                $strSQLUpdateNull_t = "UPDATE dy_cargue_datos.".$strNombreBaseTemporal_p." SET ".$strNombreCampo_p." = NULL WHERE ".$strNombreCampo_p." = ''";

                $resSQLUpdateNull_t = $mysqli->query($strSQLUpdateNull_t);

                $strSQLUpdate_t = "UPDATE dy_cargue_datos.".$strNombreBaseTemporal_p."
                                   SET ESTADO = 2, RESULTADO = CONCAT(COALESCE(RESULTADO,''),'Debe ser un numero entero o con decimales,')
                                   WHERE TRIM(".$strNombreCampo_p.") NOT REGEXP '(^[0-9]+$)|(^[0-9]+\.[0-9]{1,4}$)' ";

                $resSQLUpdate_t = $mysqli->query($strSQLUpdate_t);

                return $intProgreso_p;
                break;
            case 14:
                validarLongitudCampo($strNombreBaseTemporal_p, $strNombreCampo_p, $intLongitudCampo);
                $strSQLUpdate_t = "UPDATE dy_cargue_datos.".$strNombreBaseTemporal_p."
                                   SET ESTADO = 2, RESULTADO = CONCAT(COALESCE(RESULTADO,''),'No corresponde a un correo electronico,')
                                   WHERE TRIM(".$strNombreCampo_p.") NOT REGEXP '^[A-Z0-9._%+-]+@[A-Z0-9.-]+(.)+[A-Z]{2,6}$' OR LENGTH(TRIM(".$strNombreCampo_p.")) < 10";

                $resSQLUpdate_t = $mysqli->query($strSQLUpdate_t);

                return $intProgreso_p;
                break;
            case -14:

                $strSQLUpdateAgenteFallo_t = "UPDATE dy_cargue_datos.".$strNombreBaseTemporal_p." A LEFT JOIN ".$BaseDatos_systema.".USUARI B ON A.".$strNombreCampo_p." = B.USUARI_Codigo____b SET A.ESTADO = 2, RESULTADO = CONCAT(COALESCE(RESULTADO,''),'El correo no corresponde a ningun agente,') WHERE B.USUARI_Codigo____b IS NULL;";

                $resSQLUpdateAgenteFallo_t = $mysqli->query($strSQLUpdateAgenteFallo_t);


                $strSQLUpdateAgente_t = "UPDATE dy_cargue_datos.".$strNombreBaseTemporal_p." A JOIN ".$BaseDatos_systema.".USUARI B ON A.".$strNombreCampo_p." = B.USUARI_Codigo____b SET A.".$strNombreCampo_p." = B.USUARI_ConsInte__b WHERE A.ESTADO != 2";
                $resSQLUpdateAgente_t = $mysqli->query($strSQLUpdateAgente_t);


                return $intProgreso_p;
                break;
            case 5:

               $strSQLUpdateNull_t = "UPDATE dy_cargue_datos.".$strNombreBaseTemporal_p." SET ".$strNombreCampo_p." = NULL WHERE ".$strNombreCampo_p." = ''"; 

               $onjSQLUpdateNull_t = $mysqli->query($strSQLUpdateNull_t);

               $strSQLUpdate_t = "UPDATE dy_cargue_datos.".$strNombreBaseTemporal_p." SET ESTADO = 2, RESULTADO = CONCAT(COALESCE(RESULTADO,''),'La fecha debe ser de formato \'YYYY-mm-dd\' o \'YYYY-mm-dd HH:mm:ss\',') WHERE (TRIM(".$strNombreCampo_p.") NOT REGEXP '^[0-9]{4}-[0-1][0-9]-[0-3][0-9]$|^[0-9]{4}-[0-1][0-9]-[0-3][0-9] [0-9]{2}:[0-9]{2}:[0-9]{2}$'OR (SUBSTRING(TRIM(".$strNombreCampo_p."),1,4) < 1000 OR SUBSTRING(TRIM(".$strNombreCampo_p."),6,2) NOT BETWEEN 1 AND 12 OR SUBSTRING(TRIM(".$strNombreCampo_p."),9,2) NOT BETWEEN 1 AND 31) OR (SUBSTRING(TRIM(".$strNombreCampo_p."),12,2) > 23 OR SUBSTRING(TRIM(".$strNombreCampo_p."),15,2) > 59 OR SUBSTRING(TRIM(".$strNombreCampo_p."),18,2) > 59))";

                $objSQLUpdate_t = $mysqli->query($strSQLUpdate_t);

                return $intProgreso_p;
                break;
            case 10:

                $strSQLUpdateNull_t = "UPDATE dy_cargue_datos.".$strNombreBaseTemporal_p." SET ".$strNombreCampo_p." = NULL WHERE ".$strNombreCampo_p." = ''";

                $resSQLUpdateNull_t = $mysqli->query($strSQLUpdateNull_t);

                $strSQLUpdate_t = "UPDATE dy_cargue_datos.".$strNombreBaseTemporal_p." SET ESTADO = 2, RESULTADO = CONCAT(COALESCE(RESULTADO,''),'La hora debe ser de formato \'HH:mm:ss\',') WHERE (TRIM(".$strNombreCampo_p.") NOT REGEXP '^[0-9]{2}:[0-9]{2}:[0-9]{2}$' OR (SUBSTRING(TRIM(".$strNombreCampo_p."),1,2) > 23 OR SUBSTRING(TRIM(".$strNombreCampo_p."),4,2) > 59 OR SUBSTRING(TRIM(".$strNombreCampo_p."),7,2) > 59))";

                $objSQLUpdate_t = $mysqli->query($strSQLUpdate_t);

                return $intProgreso_p;
                break;
            case 6:

                $strUPDATENull_t = "UPDATE dy_cargue_datos.".$strNombreBaseTemporal_p." SET ".$strNombreCampo_p." = NULL WHERE ".$strNombreCampo_p." = '' OR ".$strNombreCampo_p." = ' '";

                $mysqli->query($strUPDATENull_t);

                $strSQLTraducirListas_t = "CALL ".$BaseDatos_general.".sp_traduccion_listas_simples(".$intIdCampo_p.",'".$strNombreBaseTemporal_p."','".$strNombreCampo_p."')";

                $resSQLTraducirListas_t = $mysqli->query($strSQLTraducirListas_t);

                return $intProgreso_p;
                break;
            case 13:

                $strSQLTraducirListas_t = "CALL ".$BaseDatos_general.".sp_traduccion_listas_simples(".$intIdCampo_p.",'".$strNombreBaseTemporal_p."','".$strNombreCampo_p."')";

                $resSQLTraducirListas_t = $mysqli->query($strSQLTraducirListas_t);

                return $intProgreso_p;
                break;
            case 11:

                $strUPDATENull_t = "UPDATE dy_cargue_datos.".$strNombreBaseTemporal_p." SET ".$strNombreCampo_p." = NULL WHERE ".$strNombreCampo_p." = '' OR ".$strNombreCampo_p." = ' '";

                $mysqli->query($strUPDATENull_t);

                $strSQLPregui_t = "SELECT MIN(PREGUI_ConsInte__b), SUBSTRING(CAMPO__Nombre____b,1,(POSITION('_' IN CAMPO__Nombre____b)-1)) AS BD, CAMPO__Nombre____b AS CP FROM ".$BaseDatos_systema.".CAMPO_ JOIN ".$BaseDatos_systema.".PREGUI ON CAMPO__ConsInte__b = PREGUI_ConsInte__CAMPO__b WHERE PREGUI_ConsInte__PREGUN_b = ".$intIdCampo_p;

                if ($resSQLPregui_t = $mysqli->query($strSQLPregui_t)) {

                    if ($resSQLPregui_t->num_rows > 0) {

                        $objSQLPregui_t = $resSQLPregui_t->fetch_object();

                        $BD = $objSQLPregui_t->BD; 
                        $CP = $objSQLPregui_t->CP; 

                        $strSQLUpdate2_t = "UPDATE dy_cargue_datos.".$strNombreBaseTemporal_p." A LEFT JOIN (SELECT ".$BD."_ConsInte__b AS ID, ".$CP." AS NOMBRE FROM ".$BaseDatos.".".$BD.") B ON A.".$strNombreCampo_p." = B.NOMBRE SET A.ESTADO = 2, A.RESULTADO = CONCAT(COALESCE(A.RESULTADO,''),'La opcion no corresponde a una lista compleja,') WHERE B.ID IS NULL AND A.".$strNombreCampo_p." IS NOT NULL";

                        $mysqli->query($strSQLUpdate2_t);

                        $strSQLUpdate1_t = "UPDATE dy_cargue_datos.".$strNombreBaseTemporal_p." A LEFT JOIN (SELECT ".$BD."_ConsInte__b AS ID, ".$CP." AS NOMBRE FROM ".$BaseDatos.".".$BD.") B ON A.".$strNombreCampo_p." = B.NOMBRE SET A.".$strNombreCampo_p." = B.ID WHERE B.ID IS NOT NULL";

                        $mysqli->query($strSQLUpdate1_t);
                    }

                }

                return $intProgreso_p;
                break;
            case 8:

                $strSQLChecksFallos_t = "UPDATE dy_cargue_datos.".$strNombreBaseTemporal_p." SET ESTADO = 2, RESULTADO = CONCAT(COALESCE(RESULTADO,''),'No corresponde a un valor de check,') WHERE UPPER(".$strNombreCampo_p.") != 'si' AND UPPER(".$strNombreCampo_p.") != 'yes' AND UPPER(".$strNombreCampo_p.") != 'true' AND UPPER(".$strNombreCampo_p.") != 'verdadero' AND UPPER(".$strNombreCampo_p.") != 'no' AND UPPER(".$strNombreCampo_p.") != 'false' AND UPPER(".$strNombreCampo_p.") != 'falso'";

                $resSQLChecksFallos_t = $mysqli->query($strSQLChecksFallos_t); 

                $strSQLChecksTrue_t = "UPDATE dy_cargue_datos.".$strNombreBaseTemporal_p." SET ".$strNombreCampo_p." = 1 WHERE UPPER(".$strNombreCampo_p.") = 'si' OR UPPER(".$strNombreCampo_p.") = 'yes' OR UPPER(".$strNombreCampo_p.") = 'true' OR UPPER(".$strNombreCampo_p.") = 'verdadero'";

                $resSQLChecksTrue_t = $mysqli->query($strSQLChecksTrue_t);

                $strSQLChecksFalse_t = "UPDATE dy_cargue_datos.".$strNombreBaseTemporal_p." SET ".$strNombreCampo_p." = 0 WHERE UPPER(".$strNombreCampo_p.") = 'no' OR UPPER(".$strNombreCampo_p.") = 'false' OR UPPER(".$strNombreCampo_p.") = 'falso'";

                $resSQLChecksFalse_t = $mysqli->query($strSQLChecksFalse_t);

                return $intProgreso_p;
                break;
            default:
                # code...
                return $intProgreso_p;
                break;
        }
    }  

    if(isset($_POST['llenarDatosGs'])){

        $baseDeDatos = $_POST['cmbControl'];
        $LsqlDetalle = "SELECT PREGUN_Texto_____b as titulo_pregunta , PREGUN_Tipo______b as tipo_Pregunta, PREGUN_ConsInte__b as id , PREGUN_ConsInte__GUION__PRE_B as guion, PREGUN_ConsInte__OPCION_B as lista, PREGUN_OrdePreg__b , PREGUN_NumeMini__b as minimoNumero, PREGUN_NumeMaxi__b as maximoNumero, PREGUN_FechMini__b as fechaMinimo, PREGUN_FechMaxi__b as fechaMaximo , PREGUN_HoraMini__b as horaMini , PREGUN_HoraMaxi__b as horaMaximo, PREGUN_TexErrVal_b as error FROM ".$BaseDatos_systema.".PREGUN WHERE PREGUN_ConsInte__GUION__b = ".$baseDeDatos." ORDER BY PREGUN_OrdePreg__b";

        $sqlCampoLlave=$mysqli->query("SELECT GUION__ConsInte__PREGUN_Llave_b AS llave FROM {$BaseDatos_systema}.GUION_ WHERE GUION__ConsInte__b={$baseDeDatos} AND GUION__ConsInte__PREGUN_Llave_b IS NOT NULL");
        $campoLlave=0;
        if($sqlCampoLlave->num_rows > 0){
            $sqlCampoLlave=$sqlCampoLlave->fetch_object();
            $campoLlave=$sqlCampoLlave->llave;
        }
        //echo $LsqlDetalle;
        $campos = $mysqli->query($LsqlDetalle);
        echo "<option value=\"NONE\">SELECCIONE</option>";
        echo "<option value='ConsInte__b'>Llave Interna</option>";
        while ($key3 = $campos->fetch_object()){
            if($key3->tipo_Pregunta != '9'){
                $strLlave='';
                if($key3->id == $campoLlave){
                    $strLlave="selected";
                }
                echo "<option value='".$key3->id."' {$strLlave}>".$key3->titulo_pregunta."</option>";
            }
        }

    }

    if(isset($_POST['validar_campos_primarios'])){
        $Lslq_Siexll = "SELECT SIEXLL_LlavDest__b, SIEXLL_LlavOrig__b FROM ".$BaseDatos_systema.".SIEXLL JOIN   ".$BaseDatos_systema.".SIDAEX ON SIEXLL_ConsInte__SIDAEX_b = SIDAEX_ConsInte__b WHERE SIDAEX_Destino___b = ".$_POST['cmbControl'];
        $result_Siexll = $mysqli->query($Lslq_Siexll);
        $cur_Siexll = $result_Siexll->fetch_array();
         echo json_encode(array('Principal_origen' => $cur_Siexll['SIEXLL_LlavOrig__b'] , 'Principal_Destino' => $cur_Siexll['SIEXLL_LlavDest__b']));
    }

    if(isset($_POST['validar_configuraciones'])){
        $baseDeDatos = $_POST['cmbControl'];
        $Lsq_Siexin = "SELECT  SIEXIN_CampOrig__b, SIEXIN_CampDest__b, SIEXIN_Validacion_b FROM ".$BaseDatos_systema.".SIEXIN JOIN   ".$BaseDatos_systema.".SIDAEX ON SIEXIN_ConsInte__SIDAEX_b = SIDAEX_ConsInte__b WHERE SIDAEX_Destino___b = ".$baseDeDatos;
        //echo $Lsq_Siexin;
        $res_Siexin = $mysqli->query($Lsq_Siexin);
        $i = 0;

        while ($key = $res_Siexin->fetch_object()) {
            echo '<option value="'.$i.'">'.$key->SIEXIN_CampOrig__b.'</option>';
            $i++;
        } 
    }

    if(isset($_POST['obtener_configuraciones'])){
        include '../idioma.php';

        $baseDeDatos = $_POST['cmbControl'];
        $destinos = array();
        $validaciones_array = array();
        $Lsq_Siexin = "SELECT  SIEXIN_CampOrig__b, SIEXIN_CampDest__b, SIEXIN_Validacion_b FROM ".$BaseDatos_systema.".SIEXIN JOIN   ".$BaseDatos_systema.".SIDAEX ON SIEXIN_ConsInte__SIDAEX_b = SIDAEX_ConsInte__b WHERE SIDAEX_Destino___b = ".$baseDeDatos;
        //echo $Lsq_Siexin;
        $res_Siexin = $mysqli->query($Lsq_Siexin);
        $i = 0;
        while ($key = $res_Siexin->fetch_object()) {
            $destinos[$i] = $key->SIEXIN_CampDest__b;
            $validaciones_array[$i] = $key->SIEXIN_Validacion_b;
            $i++;
        } 

        if($res_Siexin->num_rows > 0){
            $LsqlDetalle = "SELECT PREGUN_Texto_____b as titulo_pregunta , PREGUN_Tipo______b as tipo_Pregunta, PREGUN_ConsInte__b as id , PREGUN_ConsInte__GUION__PRE_B as guion, PREGUN_ConsInte__OPCION_B as lista, PREGUN_OrdePreg__b , PREGUN_NumeMini__b as minimoNumero, PREGUN_NumeMaxi__b as maximoNumero, PREGUN_FechMini__b as fechaMinimo, PREGUN_FechMaxi__b as fechaMaximo , PREGUN_HoraMini__b as horaMini , PREGUN_HoraMaxi__b as horaMaximo, PREGUN_TexErrVal_b as error FROM ".$BaseDatos_systema.".PREGUN WHERE PREGUN_ConsInte__GUION__b = ".$baseDeDatos." ORDER BY PREGUN_OrdePreg__b";

            $campos_contador = $mysqli->query($LsqlDetalle);
            for($i = 0; $i < $campos_contador->num_rows; $i++){
?>
    <tr>
        <td width="33%">
            <select class="form-control select_excel" numero="<?php echo $i;?>" id="selExcel<?php echo $i;?>" name="selExcel<?php echo $i;?>">
                <option value="NONE">SELECCIONE</option>
                <?php
                    $Lsq_Siexin = "SELECT  SIEXIN_CampOrig__b, SIEXIN_CampDest__b FROM ".$BaseDatos_systema.".SIEXIN JOIN   ".$BaseDatos_systema.".SIDAEX ON SIEXIN_ConsInte__SIDAEX_b = SIDAEX_ConsInte__b WHERE SIDAEX_Destino___b = ".$baseDeDatos;
                        
                        $res_Siexin2 = $mysqli->query($Lsq_Siexin);
                        $x = 0;
                        $nombreExell = "NONE";

                        while ($key2 = $res_Siexin2->fetch_object()) {
                            if($x == $i){
                                $nombreExell = ($key2->SIEXIN_CampOrig__b);
                                echo '<option value="'.$x.'" selected>'.($key2->SIEXIN_CampOrig__b).'</option>';
                                
                            }else{
                                echo '<option value="'.$x.'">'.($key2->SIEXIN_CampOrig__b).'</option>';
                            }
                            
                            $x++;
                        } 
                ?>
            </select>
            <input type="hidden" name="nombreExell<?php echo $i;?>" id="nombreExell<?php echo $i;?>" value="<?php echo $nombreExell;?>">
        </td>
        <td width="34%">
            <select class="form-control" id="selDB<?php echo $i;?>" name="selDB<?php echo $i;?>">
                <?php
                    $LsqlDetalle = "SELECT PREGUN_Texto_____b as titulo_pregunta , PREGUN_Tipo______b as tipo_Pregunta, PREGUN_ConsInte__b as id , PREGUN_ConsInte__GUION__PRE_B as guion, PREGUN_ConsInte__OPCION_B as lista, PREGUN_OrdePreg__b , PREGUN_NumeMini__b as minimoNumero, PREGUN_NumeMaxi__b as maximoNumero, PREGUN_FechMini__b as fechaMinimo, PREGUN_FechMaxi__b as fechaMaximo , PREGUN_HoraMini__b as horaMini , PREGUN_HoraMaxi__b as horaMaximo, PREGUN_TexErrVal_b as error FROM ".$BaseDatos_systema.".PREGUN WHERE PREGUN_ConsInte__GUION__b = ".$baseDeDatos." ORDER BY PREGUN_OrdePreg__b";

                    $campos = $mysqli->query($LsqlDetalle);
                    echo "<option value=\"NONE\">SELECCIONE</option>";
                    while ($key3 = $campos->fetch_object()){
                        if($key3->tipo_Pregunta != '9'){
                            if($key3->id == $destinos[$i]){
                                echo "<option value='".$key3->id."' selected>".$key3->titulo_pregunta."</option>";    
                            }else{
                                echo "<option value='".$key3->id."'>".$key3->titulo_pregunta."</option>";
                            }
                            
                        }
                    }
                ?>
            </select>
        </td>
        <td width="33%">
            <select class="form-control" id="valDB<?php echo $i;?>" name="valDB<?php echo $i;?>">
                <option value='1'  <?php if(count($validaciones_array) > $i) { if($validaciones_array[$i] == 1){ echo "selected"; } } ?>><?php echo $str_validacion_1;?></option>
                <option value='2'  <?php if(count($validaciones_array) > $i) { if($validaciones_array[$i] == 2){ echo "selected"; } }?>><?php echo $str_validacion_2;?></option>
                 <option value='3'  <?php if(count($validaciones_array) > $i) { if($validaciones_array[$i] == 3){ echo "selected"; } }?>><?php echo $str_validacion_3;?></option>
                <option value='4'  <?php if(count($validaciones_array) > $i) { if($validaciones_array[$i] == 4){ echo "selected"; } }?>><?php echo $str_validacion_4;?></option>
                <option value='5'  <?php if(count($validaciones_array) > $i) { if($validaciones_array[$i] == 5){ echo "selected"; } }?>><?php echo $str_validacion_5;?></option>
                <option value='6'  <?php if(count($validaciones_array) > $i) { if($validaciones_array[$i] == 6){ echo "selected"; } }?>><?php echo $str_validacion_6;?></option>
                <option value='7'  <?php if(count($validaciones_array) > $i) { if($validaciones_array[$i] == 7){ echo "selected"; } }?>><?php echo $str_validacion_7;?></option> 
            </select>
        </td>
    </tr>
<?php
            }
        }

        $SIDAEXLSQL = "SELECT SIDAEX_RutaArchi_b FROM ".$BaseDatos_systema.".SIDAEX WHERE SIDAEX_Destino___b = ".$baseDeDatos;
        $resSLql  = $mysqli->query($SIDAEXLSQL);
        $datos = $resSLql->fetch_array();
        echo "<script type='text/javascript'>$('#NombrearcExcell__BD').val('".$datos['SIDAEX_RutaArchi_b']."');</script>";
    }


    /** YCR - 2019-08-30 
    * Obtenemos columnbas del excel
    */
    if(isset($_GET['getcolumns'])){

        $name   = $_FILES['arcExcell']['name'];
        $tname  = $_FILES['arcExcell']['tmp_name'];

        if($_FILES['arcExcell']["type"] == 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'){
            $objReader = new PHPExcel_Reader_Excel2007();
            $objReader->setReadDataOnly(true);
            $obj_excel = $objReader->load($tname);
        }else if($_FILES['arcExcell']["type"] == 'application/vnd.ms-excel'){
            $obj_excel = PHPExcel_IOFactory::load($tname);
        }
        $sheetData = $obj_excel->getActiveSheet()->toArray(null,true,true,true);
        $arr_datos = array();
        $highestColumm = $obj_excel->setActiveSheetIndex(0)->getHighestColumn(); // e.g. "EL"
        $highestRow = $obj_excel->setActiveSheetIndex(0)->getHighestRow();
        $highestColumm++;
        $datasets = array();
        for ($row = 1; $row < $highestRow + 1; $row++) {
            $dataset = array();
            for ($column = 'A'; $column != $highestColumm; $column++) {
                $dataset[] = $obj_excel->setActiveSheetIndex(0)->getCell($column . $row)->getValue();
                $datasets[$row-1] = $dataset;
            }
        }
        $datos  = array();
        for($i = 0; $i < count($datasets[0]); $i++){
            $datos[$i]['valor'] = $i;
            $datos[$i]['Nombres'] = $datasets[0][$i];
        }

        echo json_encode(array('total' => count($datasets[0]) , 'opciones' => $datos));
    }
    /** YCR - 2019-08-28
    * Aqui hacemos el llenado de datos 
    */

    if(isset($_GET['lleno'])){
        $data= $_POST['count']+1;
        echo $data;
    }

    if(isset($_GET['conteo'])){
        $lsql='SELECT progreso,cantidad_total_reg as total, campos_muy_largos FROM dy_cargue_datos.'.$_POST['base'].'_info';
        $result=$mysqli->query($lsql);
        if($result){    
            if($result->num_rows > 0){
                $conteo=$result->fetch_object();
                $total=$conteo->total;
                $progreso=$conteo->progreso;
                $info=($progreso/$total)*100;
                $excede=$conteo->campos_muy_largos;
                echo json_encode(["porcentaje"=>(explode('.',$info,2)[0]),"total"=>$total,"excede"=>$excede]);
            }
        }   
    }

    if(isset($_GET['Exentos'])){

        $strCadenaFinal_t = "";

        $strCampos_t = $_POST['strCampos_t'];

        $strWhere_t = "WHERE PREGUN_ConsInte__b IN (";

        $strCampos_t = trim($strCampos_t);    
        $strCampos_t = trim($strCampos_t,",");
        $arrComa_t = explode(",",$strCampos_t);

        foreach ($arrComa_t as $strCampo_t) {

            $arrC_t = explode("_C", explode("|",$strCampo_t)[0])[1];

            $strWhere_t .= $arrC_t.",";

        }

        $strWhere_t = trim($strWhere_t,",").")";

        $strSQL_t = "SELECT PREGUN_Texto_____b AS nombre FROM ".$BaseDatos_systema.".PREGUN ".$strWhere_t;


        $resSQL_t = $mysqli->query($strSQL_t);

        if ($resSQL_t) {

            if ($resSQL_t->num_rows > 0) {

                while ($objSQL_t = $resSQL_t->fetch_object()) {

                    $strCadenaFinal_t .= "\"".preg_replace("/[\"'><\s]/"," ", $objSQL_t->nombre)."\", ";

                }

            }

        }

        $strCadenaFinal_t = trim($strCadenaFinal_t);
        $strCadenaFinal_t = trim($strCadenaFinal_t,",");  

        echo $strCadenaFinal_t;
    }

    if(isset($_GET['llenarDatos'])){

        $intIdPasp_t = $_POST["id_paso"];

        $base='';

        $intAsignacion_t = -1;
        $intPosicionAgente_t = -1;

        $arrIdAgentes_t = [];

        $camposIncrementables=true;

        if (isset($_POST["tipoasignacion"])) {
            if ($_POST["tipoasignacion"] == 2) {
                $intAsignacion_t = 2;
                if (isset($_POST["cmbAgent"])) {
                    $intPosicionAgente_t = $_POST["cmbAgent"];
                }
            }elseif ($_POST["tipoasignacion"] == 1) {
                
                
                if ($intIdPasp_t != 0) {
                    
                    $strSQLAgentes_t = "SELECT ASITAR_ConsInte__USUARI_b AS id FROM ".$BaseDatos_systema.".ASITAR A JOIN ".$BaseDatos_systema.".ESTPAS B ON A.ASITAR_ConsInte__CAMPAN_b = B.ESTPAS_ConsInte__CAMPAN_b WHERE B.ESTPAS_ConsInte__b = ".$intIdPasp_t;

                    $resSQLAgentes_t = $mysqli->query($strSQLAgentes_t);

                    while ($row = $resSQLAgentes_t->fetch_object()) {
                        $arrIdAgentes_t[] = (INT)$row->id;
                    }
                }
            }
        }

        $intCheckVolverMar_t=0;
        $intCheckInactivar_t=0;
        $strTipificacionNoUpdate_t='';

        if (isset($_POST["cheRegistrosCargarNuevo"])) {
            $intCheckVolverMar_t = 1;
            if(isset($_POST['noCambiaEstado'])){
                $i=0;
                while($i < count($_POST['noCambiaEstado'])){
                    if($i==0){
                        $strTipificacionNoUpdate_t=$_POST['noCambiaEstado'][$i];
                    }else{
                        $strTipificacionNoUpdate_t.=",".$_POST['noCambiaEstado'][$i];
                    }
                    $i++;
                }
            }
        }

        
        if (isset($_POST["cheRegistrosInactivar"])) {
            $intCheckInactivar_t = 1;
        }

        $tname  = $_FILES['arcExcell']['tmp_name'];
        $date=date('Y-m-d h:i:s');
        $name= $_FILES['arcExcell']['name'];
        $strNombreExcell_t = $date."_".$name;

        if(isset($_POST['cmbControl'])){
            $name= 'G'.$_POST['cmbControl'].'_'.$name;
        }

        $permitido=array('-',':',' ');
        $date=str_replace($permitido,'', $date);
        $name   = $date.'.'.pathinfo($name, PATHINFO_EXTENSION);
        move_uploaded_file($tname, '/Dyalogo/tmp/archivos_cargue/'.$name);

        $arrLetras_t = [];
        $strLetra_t = "A";
        for ($i=0; $i < 50; $i++) { 
            $arrLetras_t[]=$strLetra_t++;
        }
        
        $TotalColumnas = $_POST['totales'];
        $strColmCampoP_t = $arrLetras_t[$_POST["cmbColumnaP"]];
        $strNombreCampoP_t = nombreCampoP($_POST['cmbColumnaD']); 
        $c=siNoEsNumero($_POST['cmbColumnaD']);
        $keyCargue = 'G'.$_POST['cmbControl'].'_'.$c.''.$_POST['cmbColumnaD'];
        $relaciones="";
        $nombretabla= 'G'.$_POST['cmbControl'].''.$date;
        $arrCamposEnlazados_t = [];
        $arrColumnasExcell_t = [];
        $arrOrdenCampos_t = [];
        $arrCampoNoExiste_t = [];
        $strCamposIdPregun_t = " AND (";
        $intIdOrigen_DY_t = 0;

        for ($i=0; $i < $TotalColumnas; $i++) {
            $arrColumnasExcell_t[] = $_POST["selExcel".$i];
            $strCamposIdPregun_t .= "PREGUN_ConsInte__b = '".$_POST['selDB'.$i]."' OR ";
        }

        $strCamposIdPregun_t = substr($strCamposIdPregun_t, 0, -4).")";

        //JDBD - Averiguamos si emparejaron el campo de ORIGEN_DY para no ponerle origen por defecto a los registros.
        $strSQLOrigen_DY_t = "SELECT PREGUN_ConsInte__b AS id FROM ".$BaseDatos_systema.".PREGUN WHERE PREGUN_Texto_____b = 'ORIGEN_DY_WF'".$strCamposIdPregun_t;
        if ($resSQLOrigen_DY_t = $mysqli->query($strSQLOrigen_DY_t)) {

            if ($resSQLOrigen_DY_t->num_rows == 0) {

                //JDBD - Como no emparejaron el campo ORIGEN_DY buscamos el id de ese campo para luego poner un valor de origen por defecto.
                $strSQLIdOrigen_DY_t = "SELECT PREGUN_ConsInte__b AS id FROM ".$BaseDatos_systema.".PREGUN WHERE PREGUN_ConsInte__GUION__b = '".$_POST['cmbControl']."' AND PREGUN_Texto_____b = 'ORIGEN_DY_WF'";

                if ($resSQLIdOrigen_DY_t = $mysqli->query($strSQLIdOrigen_DY_t)) {

                    if ($resSQLIdOrigen_DY_t->num_rows > 0) {
                        $objSQLIdOrigen_DY_t = $resSQLIdOrigen_DY_t->fetch_object();
                        $intIdOrigen_DY_t = $objSQLIdOrigen_DY_t->id;
                    }

                }

            }

        }

        foreach ($arrColumnasExcell_t as $row => $posicionCol) {

            if (is_numeric($_POST['selDB'.$row])) {

                $strSQLShow_t = "SHOW COLUMNS FROM ".$BaseDatos.".G".$_POST['cmbControl']." WHERE Field = 'G".$_POST['cmbControl']."_C".$_POST['selDB'.$row]."'";
                $resSQLShow_t = $mysqli->query($strSQLShow_t);

                if ($resSQLShow_t) {
                     
                    if ($resSQLShow_t->num_rows != 1) {

                        $arrCampoNoExiste_t[]=$_POST['selDB'.$row];

                    }

                 } 

            }

            $c=siNoEsNumero($_POST['selDB'.$row]);
            $arrOrdenCampos_t[]=[
                                    "coll" => $posicionCol,
                                    "name" => "G".$_POST['cmbControl']. "_".$c.''.$_POST['selDB'.$row],
                                    "tipo" => tipoCampo($_POST['selDB'.$row])
                                ];
        }

        $strSQLCampos_t = "SELECT PREGUN_Texto_____b AS nombre FROM ".$BaseDatos_systema.".PREGUN WHERE PREGUN_ConsInte__b IN (";
        
        foreach ($arrCampoNoExiste_t as $kCampo => $vCampo) {

            $strSQLCampos_t .= $vCampo.",";                

        }
        
        $strSQLCampos_t = substr($strSQLCampos_t, 0, -1).")";

        $resSQLCampos_t = $mysqli->query($strSQLCampos_t);

        $strErrores_t = "!!Los siguientes campos no han sido generados : (( ";

        if ($resSQLCampos_t) {

            if ($resSQLCampos_t->num_rows > 0) {
                
                while ($obj = $resSQLCampos_t->fetch_object()) {

                    $obj->nombre = substr(preg_replace('/[^A-Za-z0-9_.\-\s]/u', "", $obj->nombre),0,15);
                    
                    $strErrores_t .= $obj->nombre.", ";

                }

                $strErrores_t = substr($strErrores_t, 0, -2)." ))!! Debe guardar y generar la base de datos.";

            }

        } 

        if (count($arrCampoNoExiste_t) > 0) {

            $data= [
                'strTablaTemp_t'=> null,
                'intIdBase_t' => null, 
                'strCampoUnion_t' => null,
                "strAgente_t" => null,
                'arrCamposEnlazados_t' => null,
                'strJar_t' => null,
                'strNombreExcell_t'=> null,
                'intReLlamar_t' => null,
                'strTipificacionNoUpdate_t' => null,
                'intInactivar_t' => null,
                'arrIdAgentes_t' => null,
                'strUnionDeCampos_t' => null,
                'strCampoNoExiste_t' => $strErrores_t,
                'intCantidadCargaPaginada_t' => $intCantidadCargaPaginada_t,
                'intLapsoPorPagina_t' => $intLapsoPorPagina_t,
                'intIdOrigen_DY_t' => null,
                'arrValidaTelefonos_t' => null,  
                'orden' => null  
            ];
            
            echo json_encode($data);            

        }else{

            $booValidaTelefonos_t = false;

            $arrOrdenCampos2_t = [];

            foreach ($arrOrdenCampos_t as $valores) {
                $arrOrdenCampos2_t[] = $valores['coll'];
            }

            array_multisort($arrOrdenCampos2_t, SORT_ASC, $arrOrdenCampos_t);

            foreach ($arrOrdenCampos_t as $row => $posicionCol) {
                if($_POST['selDB'.$row] != 'NONE'){
                    $arrCamposEnlazados_t[] = ['id_campo'=>$_POST['selDB'.$row], 'validador'=>$_POST['valDB'.$row]];
                    if ($_POST['valDB'.$row] == 2) {
                        $booValidaTelefonos_t = true;
                    }
                }
            }

            $intSentidoPaso_t = null;
            $arrValidaTelefonos_t = [0,0,false];

            if ($booValidaTelefonos_t) {

                $strSQLSentidoPaso_t = "SELECT ESTPAS_Tipo______b AS tipo FROM ".$BaseDatos_systema.".ESTPAS WHERE ESTPAS_ConsInte__b = ".$intIdPasp_t;
                $resSQLSentidoPaso_t = $mysqli->query($strSQLSentidoPaso_t);

                if ($resSQLSentidoPaso_t && $resSQLSentidoPaso_t->num_rows > 0) {

                    $intSentidoPaso_t = $resSQLSentidoPaso_t->fetch_object()->tipo;

                    $arrValidaTelefonos_t[0] = (int)$intSentidoPaso_t;

                    if ($intSentidoPaso_t == 6) {

                        $strSQLTroncales_t = "SELECT COUNT(1) AS cantidad FROM dyalogo_telefonia.pasos_troncales WHERE id_estpas = ".$intIdPasp_t;
                        $resSQLTroncales_t = $mysqli->query($strSQLTroncales_t);

                        if ($resSQLTroncales_t && $resSQLTroncales_t->num_rows > 0) {

                            if ($resSQLTroncales_t->fetch_object()->cantidad > 0) {
                                $arrValidaTelefonos_t[1] = 1;
                            }else{
                                $arrValidaTelefonos_t[1] = 0;
                            }

                        }

                    }else{
                        $arrValidaTelefonos_t[1] = 1;
                    }

                }
                
            }

            $arrValidaTelefonos_t[2] = $booValidaTelefonos_t;

            $strAgente_t="";

            if ($intPosicionAgente_t > -1) {
                
                $strAgente_t = $arrLetras_t[$intPosicionAgente_t];
                array_push($arrCamposEnlazados_t,['id_campo'=>'AGENTE','validador'=>1]);

                $intCantApa_t = count($arrOrdenCampos_t);
                $arrOrdenCampos_t[$intCantApa_t]=[
                    "coll" => $intPosicionAgente_t,
                    "name" => "AGENTE",
                    "tipo" => "varchar"
                ];

            }

            $arrPosiciones_t = [];
            foreach ($arrOrdenCampos_t as $key => $value) {
                $arrPosiciones_t[$key] = (string)$value["coll"];
            }

            array_multisort($arrPosiciones_t, SORT_ASC, $arrOrdenCampos_t);

            foreach ($arrOrdenCampos_t as $ROW => $VAL) {
                if ($VAL["coll"] != "NONE") {
                    $relaciones .= $arrLetras_t[$VAL["coll"]]."=".$VAL["name"]."|".$VAL["tipo"].",";
                }
            }

            $relaciones= trim($relaciones, ',');


            $base="G".$_POST['cmbControl']."".$date;
            
            $cmd='java -jar /etc/dyalogo/apps/DyImportaExcel.jar /Dyalogo/tmp/archivos_cargue/'.$name.' "G'.$_POST['cmbControl'].''.$date.'" "'.$relaciones.'" "'.$keyCargue.'"';
            if ($booValidaTelefonos_t) {
                if ($arrValidaTelefonos_t[0] == 6) {

                    if ($arrValidaTelefonos_t[1] == 1) {

                        $pid = shell_exec($cmd);
                        if($pid){
                            //VALIDAR SI LA BD TIENE CAMPOS AUTOINCREMENTABLES
                            $camposIncrementables=getCampoIncremental($_POST['cmbControl'],$base,$arrOrdenCampos_t,$arrCamposEnlazados_t);
                            $arrOrdenCampos_t=$camposIncrementables['arrOrdenCampos_t'];
                            $arrCamposEnlazados_t=$camposIncrementables['arrCamposEnlazados_t'];
                            $camposIncrementables=$camposIncrementables['rpta'];
                        }

                    }

                }else{

                    $pid = shell_exec($cmd);
                    if($pid){
                        //VALIDAR SI LA BD TIENE CAMPOS AUTOINCREMENTABLES
                        $camposIncrementables=getCampoIncremental($_POST['cmbControl'],$base,$arrOrdenCampos_t,$arrCamposEnlazados_t);
                        $arrOrdenCampos_t=$camposIncrementables['arrOrdenCampos_t'];
                        $arrCamposEnlazados_t=$camposIncrementables['arrCamposEnlazados_t'];
                        $camposIncrementables=$camposIncrementables['rpta'];
                    }
                    
                }
            }else{

                $pid = shell_exec($cmd);
                if($pid){
                    //VALIDAR SI LA BD TIENE CAMPOS AUTOINCREMENTABLES
                    $camposIncrementables=getCampoIncremental($_POST['cmbControl'],$base,$arrOrdenCampos_t,$arrCamposEnlazados_t);
                    $arrOrdenCampos_t=$camposIncrementables['arrOrdenCampos_t'];
                    $arrCamposEnlazados_t=$camposIncrementables['arrCamposEnlazados_t'];
                    $camposIncrementables=$camposIncrementables['rpta'];
                }
                
            }


            $strUnionDeCampos_t = "Cam.Dyalogo : ".$strNombreCampoP_t." => Cam.Excell : ".$strColmCampoP_t;

            $data= [
                'strTablaTemp_t'=> $base,
                'intIdBase_t' => $_POST['cmbControl'], 
                'strCampoUnion_t' => $keyCargue,
                "strAgente_t" => $strAgente_t,
                'arrCamposEnlazados_t' => json_encode($arrCamposEnlazados_t),
                'strJar_t' => $cmd,
                'strNombreExcell_t'=> $strNombreExcell_t,
                'intReLlamar_t' => $intCheckVolverMar_t,
                'strTipificacionNoUpdate_t' => $strTipificacionNoUpdate_t,
                'intInactivar_t' => $intCheckInactivar_t,
                'arrIdAgentes_t' => json_encode($arrIdAgentes_t),
                'strUnionDeCampos_t' => $strUnionDeCampos_t,
                'strCampoNoExiste_t' => "",
                'intCantidadCargaPaginada_t' => $intCantidadCargaPaginada_t,
                'intLapsoPorPagina_t' => $intLapsoPorPagina_t,
                'intIdOrigen_DY_t' => $intIdOrigen_DY_t,
                'arrValidaTelefonos_t' => json_encode($arrValidaTelefonos_t),
                'orden' => $arrOrdenCampos_t,
                'incrementables' => $camposIncrementables 
            ];
            
            echo json_encode($data);
            
        }

        
    }

    if (isset($_GET["getInformation"])) {

        $_POST["arrCamposEnlazados_t"] = str_replace("\\n","",$_POST['arrCamposEnlazados_t']);
        $_POST["arrCamposEnlazados_t"] = str_replace('\\',"",$_POST['arrCamposEnlazados_t']);

        $strTablaTemp_t = $_POST["strTablaTemp_t"];
        $intIdBase_t = $_POST["intIdBase_t"];
        $arrCamposEnlazados_t = json_decode($_POST["arrCamposEnlazados_t"]);

        $arrCampoYTipo_t=[];
        foreach ($arrCamposEnlazados_t as $row => $campo) {
            if (is_numeric($campo->id_campo)) {
                
                $strSQLTipo_t = "SELECT PREGUN_Tipo______b AS tipo_Pregunta, PREGUN_Texto_____b AS nombre, PREGUN_Longitud__b AS longitud_pregun FROM ".$BaseDatos_systema.".PREGUN WHERE PREGUN_ConsInte__b = ".$campo->id_campo;

                $resSQLTipo_t = $mysqli->query($strSQLTipo_t);

                $objSQLTipo_t = $resSQLTipo_t->fetch_object();

                $arrCampoYTipo_t[]=["strNombreCampo_t"=>"G".$intIdBase_t."_C".$campo->id_campo,
                                    "strNombrePregun_t" => $objSQLTipo_t->nombre,
                                    "intTipo_t"=>$objSQLTipo_t->tipo_Pregunta,
                                    "intIdCampo_t"=>$campo->id_campo,
                                    "intValidador_t"=>$campo->validador,
                                    "longitud_pregun" => $objSQLTipo_t->longitud_pregun
                                ]; 

            }else{
                if ($campo->id_campo == "AGENTE") {
                    $arrCampoYTipo_t[]=["strNombreCampo_t"=>"AGENTE",
                                        "strNombrePregun_t" => "AGENTE",
                                        "intTipo_t"=>-14,
                                        "intIdCampo_t"=>"AGENTE",
                                        "intValidador_t"=>1,
                                        "longitud_pregun" => $objSQLTipo_t->longitud_pregun
                                    ];
                }else{
                    $arrCampoYTipo_t[]=["strNombreCampo_t"=>"G".$intIdBase_t."_ConsInte__b",
                                        "strNombrePregun_t" => "Llave interna",
                                        "intTipo_t"=>3,
                                        "intIdCampo_t"=>"ConsInte__b",
                                        "intValidador_t"=>1,
                                        "longitud_pregun" => $objSQLTipo_t->longitud_pregun
                                    ];
                }
            }
        }
        
        echo json_encode($arrCampoYTipo_t);
    }

    if(isset($_GET['validaCampos'])) {

        $strTablaTemp_t=$_POST['strTablaTemp_t'];
        $strNombreCampo_t=$_POST['strNombreCampo_t'];
        $intTipo_t=$_POST['intTipo_t'];
        $intIdCampo_t=$_POST['intIdCampo_t'];
        $intProgreso_t=$_POST['intProgreso_t'];
        $intTotal_t=$_POST['intTotal_t'];
        $intValidador_t=$_POST['intValidador_t'];
        $intIdPaso_t = $_POST["intIdPaso_t"];
        $intIdHuesped_t = $_POST["intIdHuesped_t"];
        $intSentido_t = $_POST["intSentido_t"];
        $intLongitudCampo = $_POST["longitud_campo"];

        $intAvance_t=fn_validar_y_traducir($strTablaTemp_t,$strNombreCampo_t,(INT)$intTipo_t,$intIdCampo_t,$intValidador_t,$intProgreso_t,$intIdPaso_t,$intIdHuesped_t,$intSentido_t, $intLongitudCampo);
        $intAvance_t=$intAvance_t+1;
        $intAvance_t=($intAvance_t/$intTotal_t)*100;
        
        echo explode('.',$intAvance_t,2)[0];
    }


    if (isset($_GET["EliminarTablaTemporal"])) {
       
       //JDBD - Vamos a eliminar la tabla temporal.
       $strNombreTablaTemporal_t = $_POST["strNombreTablaTemporal_t"];

       $strSQLDropTablaTemporal = "DROP TABLE dy_cargue_datos.".$strNombreTablaTemporal_t.", dy_cargue_datos.".$strNombreTablaTemporal_t."_info";

    //    $mysqli->query($strSQLDropTablaTemporal);       

    }

    if (isset($_GET["depurarBase"])) {

        $strTablaTemp_t = $_POST["strTablaTemp_t"]; 
        $intIdBase_t = $_POST["intIdBase_t"]; 
        $strCampoUnion_t = $_POST["strCampoUnion_t"]; 
        $intIdPaso_t = $_POST["intIdPaso_t"]; 
        $intIdMuestra_t = $_POST["intIdMuestra_t"]; 
        $strUnionDeCampos_t = $_POST["strUnionDeCampos_t"];
        $strFechaInsercion_t = date('Y-m-d H:i:s');
        $intTiempo_t = time(); 

        //JDBD - Creamos el indice a la tabla de la base de la campaña por el campo principal.
            $strSQLADDIndiceBD_t = "ALTER TABLE ".$BaseDatos.".G".$intIdBase_t." ADD INDEX ix_".$strCampoUnion_t." (".$strCampoUnion_t." ASC)";
            $mysqli->query($strSQLADDIndiceBD_t);   

        //JDBD - Creamos el indice a la tabla temporal por el campo principal.
            $strSQLADDIndice_t = "ALTER TABLE dy_cargue_datos.".$strTablaTemp_t." ADD INDEX ix_".$strCampoUnion_t." (".$strCampoUnion_t." ASC)";
            $mysqli->query($strSQLADDIndice_t);    

        //JDBD - Añadimos una columna a la tabla temporal para identificar los registros ya existentes en la base.
            $strSQLADDColumn_t = "ALTER TABLE dy_cargue_datos.".$strTablaTemp_t." ADD COLUMN EXISTENTE VARCHAR(5) DEFAULT NULL";
            $mysqli->query($strSQLADDColumn_t);

        //JDBD - total registros
            $intTotalReg_t = 0;
            $strSQLCantidadTotal_t = "SELECT COUNT(1) as cantidad FROM dy_cargue_datos.".$strTablaTemp_t;
            $resSQLCantidadTotal_t = $mysqli->query($strSQLCantidadTotal_t);

            if ($resSQLCantidadTotal_t && $resSQLCantidadTotal_t->num_rows > 0) {
                $objSQLCantidadTotal_t = $resSQLCantidadTotal_t->fetch_object();
                $intTotalReg_t = $objSQLCantidadTotal_t->cantidad;
            }

        //JDBD - registros repetidos.
            $intTotalRep_t = 0;
            $strSQLCantidadRepetidos_t = "SELECT COUNT(cantidad) AS cantidad FROM (SELECT ".$strCampoUnion_t.", COUNT(1) AS cantidad from dy_cargue_datos.".$strTablaTemp_t." GROUP BY ".$strCampoUnion_t.") Repetidos where cantidad > 1";
            $resSQLCantidadRepetidos_t = $mysqli->query($strSQLCantidadRepetidos_t);

            if ($resSQLCantidadRepetidos_t && $resSQLCantidadRepetidos_t->num_rows > 0) {
                $objSQLCantidadRepetidos_t = $resSQLCantidadRepetidos_t->fetch_object();
                $intTotalRep_t = $objSQLCantidadRepetidos_t->cantidad;
            }

        //JDBD - eliminamos los registros repetidos.
            $strSQLDelete_t = "DELETE dy_cargue_datos.".$strTablaTemp_t." FROM dy_cargue_datos.".$strTablaTemp_t." LEFT JOIN (SELECT min(id) AS id2 FROM dy_cargue_datos.".$strTablaTemp_t." GROUP BY ".$strCampoUnion_t.") b ON id = id2 WHERE id2 IS NULL";

            $intErrorId_t = insertarLogconsulta(true,null,$intIdBase_t,$intIdMuestra_t,$intIdPaso_t,$strSQLDelete_t,'eliminar-repetidos','',$strUnionDeCampos_t);

            $mysqli->query($strSQLDelete_t);

            insertarLogconsulta(false,$intErrorId_t,null,null,null,null,null,$mysqli->error,null);

        //JDBD - Total Registro en tabla temporal.
            $intTotalTemp_t = 0;
            $strSQLCantidadTemp_t = "SELECT COUNT(1) as cantidad FROM dy_cargue_datos.".$strTablaTemp_t;
            $resSQLCantidadTemp_t = $mysqli->query($strSQLCantidadTemp_t);

            if ($resSQLCantidadTemp_t && $resSQLCantidadTemp_t->num_rows > 0) {
                $objSQLCantidadTemp_t = $resSQLCantidadTemp_t->fetch_object();
                $intTotalTemp_t = $objSQLCantidadTemp_t->cantidad;
            }

        //JDBD - Identificamos los registros en la tabla temporal que ya existen en la base.
            $strSQLUpdateExistente_t = "UPDATE dy_cargue_datos.".$strTablaTemp_t." A JOIN ".$BaseDatos.".G".$intIdBase_t." B ON A.".$strCampoUnion_t." = B.".$strCampoUnion_t." SET A.EXISTENTE = '1|0'";
            $resSQLUpdateExistente_t = $mysqli->query($strSQLUpdateExistente_t);

        if ($intIdMuestra_t != -1) {

            //JDBD - Identificamos los registros que ya existen en la muestra.
                $strSQLUpdateExistenteM_t = "UPDATE dy_cargue_datos.".$strTablaTemp_t." A JOIN ".$BaseDatos.".G".$intIdBase_t." B ON A.".$strCampoUnion_t." = B.".$strCampoUnion_t." JOIN ".$BaseDatos.".G".$intIdBase_t."_M".$intIdMuestra_t." C ON B.G".$intIdBase_t."_ConsInte__b = C.G".$intIdBase_t."_M".$intIdMuestra_t."_CoInMiPo__b SET A.EXISTENTE = CONCAT(SUBSTR(A.EXISTENTE,1,1),'|1')";
                $resSQLUpdateExistenteM_t = $mysqli->query($strSQLUpdateExistenteM_t);
            
        }


        //JDBD - nuevos registros
            $intTotalNew_t = 0;

            $strSQLCantidadInsertados_t = "SELECT COUNT(1) AS cantidad FROM dy_cargue_datos.".$strTablaTemp_t." A LEFT JOIN ".$BaseDatos.".G".$intIdBase_t." B ON A.".$strCampoUnion_t." = B.".$strCampoUnion_t." WHERE B.".$strCampoUnion_t." IS NULL AND A.ESTADO != 2";

            $intErrorId_t = insertarLogconsulta(true,null,$intIdBase_t,$intIdMuestra_t,$intIdPaso_t,$strSQLCantidadInsertados_t,'registros-nuevos','',$strUnionDeCampos_t);

            $resSQLCantidadInsertados_t = $mysqli->query($strSQLCantidadInsertados_t);

            insertarLogconsulta(false,$intErrorId_t,null,null,null,null,null,$mysqli->error,null);

            if ($resSQLCantidadInsertados_t && $resSQLCantidadInsertados_t->num_rows > 0) {

                $intTotalNew_t = $resSQLCantidadInsertados_t->fetch_object()->cantidad;

            }

        //JDBD - actualizados
            $intTotalAct_t = 0;
            $strSQLCantidadActualizados_t = "SELECT COUNT(1) AS cantidad FROM dy_cargue_datos.".$strTablaTemp_t." WHERE ESTADO != 2 AND SUBSTR(EXISTENTE,1,1) = 1";

            $intErrorId_t = insertarLogconsulta(true,null,$intIdBase_t,$intIdMuestra_t,$intIdPaso_t,$strSQLCantidadActualizados_t,'registros-existentes','',$strUnionDeCampos_t);

            $resSQLCantidadActualizados_t = $mysqli->query($strSQLCantidadActualizados_t);

            insertarLogconsulta(false,$intErrorId_t,null,null,null,null,null,$mysqli->error,null);

            if ($resSQLCantidadActualizados_t && $resSQLCantidadActualizados_t->num_rows > 0) {
                $objSQLCantidadActualizados_t = $resSQLCantidadActualizados_t->fetch_object();
                $intTotalAct_t = $objSQLCantidadActualizados_t->cantidad;
            }


        //JDBD - fallos
            $intTotalFail_t = 0;
            $strSQLCantidadErrores_t = "SELECT COUNT(1) AS cantidad FROM dy_cargue_datos.".$strTablaTemp_t." WHERE (ESTADO = 2 OR ESTADO = 3)";
            $resSQLCantidadErrores_t = $mysqli->query($strSQLCantidadErrores_t);
            if ($resSQLCantidadErrores_t && $resSQLCantidadErrores_t->num_rows > 0) {
                $objSQLCantidadErrores_t = $resSQLCantidadErrores_t->fetch_object();
                $intTotalFail_t = $objSQLCantidadErrores_t->cantidad;
            }

        //JDBD - Retornamos el JSON con el conteo de los registros.
            echo json_encode(["intTotalReg_t"=>$intTotalReg_t,"intTotalRep_t"=>$intTotalRep_t,"intTotalNew_t"=>$intTotalNew_t,"intTotalAct_t"=>$intTotalAct_t,"intTotalFail_t"=>$intTotalFail_t,"intTotalTemp_t"=>$intTotalTemp_t,"strFechaInsercion_t"=>$strFechaInsercion_t,"intTiempo_t"=>$intTiempo_t]);

    }

    if (isset($_GET["Paginar"])) {

        $strTablaTemp_t = $_POST["strTablaTemp_t"];
        $intPaginacion_t = $_POST["intPaginacion_t"];


        $strSQLPaginar_t = "UPDATE dy_cargue_datos.".$strTablaTemp_t." SET TRAMITADO = 1 WHERE TRAMITADO = 0 AND ESTADO != 2 LIMIT ".$intPaginacion_t;

        if ($mysqli->query($strSQLPaginar_t)) {
            echo "ok";
        }else{
            echo "fail";
        }

    }

    if (isset($_GET["transaccionesBd"])) {

        $arrInfoCampos_t = $_POST["arrInfoCampos_t"];
        $arrIdAgentes_t = json_decode($_POST["arrIdAgentes_t"]);
        $strTablaTemp_t = $_POST["strTablaTemp_t"];
        $strCampoUnion_t = $_POST["strCampoUnion_t"];
        $intIdBase_t = $_POST["intIdBase_t"];
        $intIdPaso_t = $_POST["intIdPaso_t"];
        $intIdMuestra_t = $_POST["intIdMuestra_t"];
        $strUnionDeCampos_t = $_POST["strUnionDeCampos_t"];
        $intTiempo_t = $_POST["intTiempo_t"];
        $intReLlamar_t = $_POST["intReLlamar_t"];
        $strTipificacionNoUpdate_t = $_POST["strTipificacionNoUpdate_t"];
        $strFechaInsercion_t = $_POST["strFechaInsercion_t"];
        $strNombreExcell_t = $_POST["strNombreExcell_t"];
        $intInactivar_t = $_POST["intInactivar_t"];
        $strAgente_t = $_POST["strAgente_t"];
        $intIdOrigen_DY_t = $_POST["intIdOrigen_DY_t"];
        $intCampoValorContactdo = null;

        //JDBD - Armamos los valores para los campos a tramitar.
            $strCamposInsert_t = "";
            $strCamposValues_t = "";
            $strCamposUpdate_t = "";
            foreach ($arrInfoCampos_t as $objeto) {
                if (is_numeric($objeto["intIdCampo_t"])) {
                    
                    $strCamposInsert_t .= $objeto["strNombreCampo_t"].",";
                    $strCamposValues_t .= depurador("A.".$objeto["strNombreCampo_t"],$objeto["intTipo_t"]).",";
                    $strCamposUpdate_t .= "A.".$objeto["strNombreCampo_t"]." = ".depurador("B.".$objeto["strNombreCampo_t"],$objeto["intTipo_t"]).",";

                }

                if($objeto["intValidador_t"] == 2 && $intCampoValorContactdo == null){
                    $intCampoValorContactdo = $objeto["intIdCampo_t"];
                }
            }

            $strSQLCampoContactado = ($intCampoValorContactdo != null) ? ", G.G{$intIdBase_t}_C{$intCampoValorContactdo} AS datoContacto " : "";

            $strCamposUpdate_t .= "A.G".$intIdBase_t."_EstadoDiligenciamiento = ".$intTiempo_t;
            $strCamposUpdate_t .= ",A.G".$intIdBase_t."_FechaUltimoCargue = NOW()";
            if($strNombreExcell_t != ""){
                $strCamposUpdate_t .=",A.G".$intIdBase_t."_OrigenUltimoCargue = '".$strNombreExcell_t."'";
            }



        //JDBD - Re marcar - JDBD /////////////
            if ($intIdMuestra_t != -1) {
                if ($intReLlamar_t==1) {
                    
                    if($strTipificacionNoUpdate_t !='' && $strTipificacionNoUpdate_t != null){
                        $strSQLUpdateReinicio_t = "UPDATE ".$BaseDatos.".G".$intIdBase_t."_M".$intIdMuestra_t." A
                        JOIN ".$BaseDatos.".G".$intIdBase_t." B ON A.G".$intIdBase_t."_M".$intIdMuestra_t."_CoInMiPo__b = B.G".$intIdBase_t."_ConsInte__b JOIN dy_cargue_datos.".$strTablaTemp_t." C ON B.".$strCampoUnion_t." = C.".$strCampoUnion_t." SET A.G".$intIdBase_t."_M".$intIdMuestra_t."_Activo____b = -1, G".$intIdBase_t."_M".$intIdMuestra_t."_Estado____b = 0, G".$intIdBase_t."_M".$intIdMuestra_t."_NumeInte__b = 0,G".$intIdBase_t."_M".$intIdMuestra_t."_FecHorAge_b = NULL,G".$intIdBase_t."_M".$intIdMuestra_t."_UltiGest__b = NULL,G".$intIdBase_t."_M".$intIdMuestra_t."_FecHorMinProGes__b = NULL, G".$intIdBase_t."_M".$intIdMuestra_t."_ConUltGes_b = IF(G".$intIdBase_t."_M".$intIdMuestra_t."_ConUltGes_b = 2, 3, G".$intIdBase_t."_M".$intIdMuestra_t."_ConUltGes_b ), G".$intIdBase_t."_M".$intIdMuestra_t."_FechaReactivacion_b = NOW() WHERE C.TRAMITADO = 1 AND G".$intIdBase_t."_M".$intIdMuestra_t."_UltiGest__b NOT IN ({$strTipificacionNoUpdate_t})";
                    }else{
                        //JDBD - Se reinician los registros en el Excell existentes en Dyalogo.
                        $strSQLUpdateReinicio_t = "UPDATE ".$BaseDatos.".G".$intIdBase_t."_M".$intIdMuestra_t." A
                                                   JOIN ".$BaseDatos.".G".$intIdBase_t." B ON A.G".$intIdBase_t."_M".$intIdMuestra_t."_CoInMiPo__b = B.G".$intIdBase_t."_ConsInte__b JOIN dy_cargue_datos.".$strTablaTemp_t." C ON B.".$strCampoUnion_t." = C.".$strCampoUnion_t." SET A.G".$intIdBase_t."_M".$intIdMuestra_t."_Activo____b = -1, G".$intIdBase_t."_M".$intIdMuestra_t."_Estado____b = 0, G".$intIdBase_t."_M".$intIdMuestra_t."_NumeInte__b = 0,G".$intIdBase_t."_M".$intIdMuestra_t."_FecHorAge_b = NULL,G".$intIdBase_t."_M".$intIdMuestra_t."_UltiGest__b = NULL,G".$intIdBase_t."_M".$intIdMuestra_t."_FecHorMinProGes__b = NULL, G".$intIdBase_t."_M".$intIdMuestra_t."_ConUltGes_b = IF(G".$intIdBase_t."_M".$intIdMuestra_t."_ConUltGes_b = 2, 3, G".$intIdBase_t."_M".$intIdMuestra_t."_ConUltGes_b ), G".$intIdBase_t."_M".$intIdMuestra_t."_FechaReactivacion_b = NOW() WHERE C.TRAMITADO = 1";
                    }

                    $intErrorId_t = insertarLogconsulta(true,null,$intIdBase_t,$intIdMuestra_t,$intIdPaso_t,$strSQLUpdateReinicio_t,'re-marcar','',$strUnionDeCampos_t);

                    $mysqli->query($strSQLUpdateReinicio_t);

                    insertarLogconsulta(false,$intErrorId_t,null,null,null,null,null,$mysqli->error,null);


                }
            }

            $arrDataJouney = [];

        //JDBD - primero actualizamos lo que ya existe entre las bases.
            $strCamposUpdate_t=removeCampoIncremental($intIdBase_t,$strCamposUpdate_t);
            $strSQLUpdateBase_t = "UPDATE ".$BaseDatos.".G".$intIdBase_t." A JOIN dy_cargue_datos.".$strTablaTemp_t." B ON A.".$strCampoUnion_t." = B.".$strCampoUnion_t." SET ".$strCamposUpdate_t." WHERE B.TRAMITADO = 1 AND B.ESTADO != 3 AND SUBSTR(B.EXISTENTE,1,1) = 1";

            $intErrorId_t = insertarLogconsulta(true,null,$intIdBase_t,$intIdMuestra_t,$intIdPaso_t,$strSQLUpdateBase_t,'actualizacion-bd','',$strUnionDeCampos_t);

            $mysqli->query($strSQLUpdateBase_t);

            // BGCR - NECESITAMOS SABER LOS IDS DE LOS REGISTROS ACTUALIZADOS PARA CREAR UN REGISTRO DE ACTUALIZACION EN EL JOURNEY

            $strSQLLastIdsUpdate = "SELECT G.G{$intIdBase_t}_ConsInte__b AS id {$strSQLCampoContactado} FROM {$BaseDatos}.G{$intIdBase_t} G JOIN dy_cargue_datos.{$strTablaTemp_t} A ON G.{$strCampoUnion_t} =  A.{$strCampoUnion_t} WHERE A.TRAMITADO = 1 AND A.ESTADO != 3 AND SUBSTR(A.EXISTENTE,1,1) = 1";

            $resLastIdsUpdate = $mysqli->query($strSQLLastIdsUpdate);

            $dataJourney = (Object) ["infoInsert" => (Object) ["sentido" => "Entrante", "tipificacion" => -303, "clasificacion" => 3, "tipoReintento" => 0, "canal" => "cargador"], "ids" => []];

            if($resLastIdsUpdate &&  $resLastIdsUpdate->num_rows > 0){
                while($idObj = $resLastIdsUpdate->fetch_object()){

                    $datoContactoJ = (isset($idObj->datoContacto) && $idObj->datoContacto != null) ? $idObj->datoContacto : null;
                    $fechaHoraJ = (isset($idObj->fecha) && $idObj->fecha != null) ? $idObj->fecha : null;
                    array_push($dataJourney->ids, (Object) ["idPoblacion" => $idObj->id, "datoContacto" => $datoContactoJ]);
                }

                array_push($arrDataJouney, $dataJourney);

            }


            insertarLogconsulta(false,$intErrorId_t,null,null,null,null,null,$mysqli->error,null);

        //JDBD - Insertamos lo nuevo en la base
            $strSQLInsertBase_t = "INSERT INTO ".$BaseDatos.".G".$intIdBase_t." (".$strCamposInsert_t.collsDefaultBd($intIdBase_t,$intIdOrigen_DY_t).")
                                   SELECT ".$strCamposValues_t.valuesDefaultBd($strNombreExcell_t,$intTiempo_t,$strFechaInsercion_t,$intIdOrigen_DY_t,false, $intIdPaso_t)." FROM dy_cargue_datos.".$strTablaTemp_t." A WHERE A.EXISTENTE IS NULL AND A.TRAMITADO = 1 AND A.ESTADO != 3";

            $intErrorId_t = insertarLogconsulta(true,null,$intIdBase_t,$intIdMuestra_t,$intIdPaso_t,$strSQLInsertBase_t,'insercion-bd','',$strUnionDeCampos_t);
                
            $mysqli->query($strSQLInsertBase_t);

            // BGCR - PARA EL JOURNEY NECESITAMOS SABER CUALES SON LOS IDS NUEVOS
            $strSQLLastIds = "SELECT G.G{$intIdBase_t}_ConsInte__b AS id, G.G{$intIdBase_t}_FechaInsercion as fecha {$strSQLCampoContactado} FROM {$BaseDatos}.G{$intIdBase_t} G JOIN dy_cargue_datos.{$strTablaTemp_t} A ON G.{$strCampoUnion_t} =  A.{$strCampoUnion_t} WHERE A.EXISTENTE IS NULL AND A.TRAMITADO = 1 AND A.ESTADO != 3";

            $resLastIds = $mysqli->query($strSQLLastIds);

            $dataJourneyNew = (Object) ["infoInsert" => (Object) ["sentido" => "Entrante", "tipificacion" => -103, "clasificacion" => 3, "tipoReintento" => 0, "canal" => "cargador"], "ids" => []];

            if($resLastIds &&  $resLastIds->num_rows > 0){
                while($idObj = $resLastIds->fetch_object()){
                    $datoContactoJ = (isset($idObj->datoContacto) && $idObj->datoContacto != null) ? $idObj->datoContacto : null;
                    $fechaHoraJ = (isset($idObj->fecha) && $idObj->fecha != null) ? $idObj->fecha : null;
                    array_push($dataJourneyNew->ids, (Object) ["idPoblacion" => $idObj->id, "datoContacto" => $datoContactoJ, "fechaHora" => $fechaHoraJ]);
                }

            }

            insertarLogconsulta(false,$intErrorId_t,null,null,null,null,null,$mysqli->error,null);

        //JDBD - Insertamos lo nuevo telefonos errados en la base
            $strSQLInsertBase_t = "INSERT INTO ".$BaseDatos.".G".$intIdBase_t." (".$strCamposInsert_t.collsDefaultBd($intIdBase_t,$intIdOrigen_DY_t).")
                                   SELECT ".$strCamposValues_t.valuesDefaultBd($strNombreExcell_t,$intTiempo_t,$strFechaInsercion_t,$intIdOrigen_DY_t,true, $intIdPaso_t)." FROM dy_cargue_datos.".$strTablaTemp_t." A WHERE A.EXISTENTE IS NULL AND A.TRAMITADO = 1 AND A.ESTADO = 3";

            $intErrorId_t = insertarLogconsulta(true,null,$intIdBase_t,$intIdMuestra_t,$intIdPaso_t,$strSQLInsertBase_t,'insercion-bd','',$strUnionDeCampos_t);
                
            $mysqli->query($strSQLInsertBase_t);


            // BGCR - PARA EL JOURNEY NECESITAMOS SABER CUALES SON LOS IDS NUEVOS
            $strSQLLastIdsErr = "SELECT G.G{$intIdBase_t}_ConsInte__b AS id, G.G{$intIdBase_t}_FechaInsercion as fecha {$strSQLCampoContactado}  FROM {$BaseDatos}.G{$intIdBase_t} G JOIN dy_cargue_datos.{$strTablaTemp_t} A ON G.{$strCampoUnion_t} =  A.{$strCampoUnion_t} WHERE A.EXISTENTE IS NULL AND A.TRAMITADO = 1 AND A.ESTADO = 3";

            $resLastIdsErr = $mysqli->query($strSQLLastIdsErr);

            if($resLastIdsErr &&  $resLastIdsErr->num_rows > 0){
                while($idObj = $resLastIds->fetch_object()){
                    $datoContactoJ = (isset($idObj->datoContacto) && $idObj->datoContacto != null) ? $idObj->datoContacto : null;
                    $fechaHoraJ = (isset($idObj->fecha) && $idObj->fecha != null) ? $idObj->fecha : null;
                    array_push($dataJourneyNew->ids, (Object) ["idPoblacion" => $idObj->id, "datoContacto" => $datoContactoJ, "fechaHora" => $fechaHoraJ]);
                }

            }

            array_push($arrDataJouney, $dataJourneyNew);

            insertarJourney(null, $intIdBase_t, $intIdPaso_t, $arrDataJouney, true);
                
            insertarLogconsulta(false,$intErrorId_t,null,null,null,null,null,$mysqli->error,null);

        $strSQLUpdateUser_t = "";

        if ($intIdMuestra_t != -1) {

            //JDBD - Insertamos lo nuevo en la muestra
            $strSQLInsertMuestra_t = "INSERT INTO ".$BaseDatos.".G".$intIdBase_t."_M".$intIdMuestra_t." (".collsDefaultMt($intIdBase_t,$intIdMuestra_t).") SELECT ".valuesDefaultMt($intIdBase_t,false)." FROM ".$BaseDatos.".G".$intIdBase_t." A JOIN dy_cargue_datos.".$strTablaTemp_t." B ON A.".$strCampoUnion_t." = B.".$strCampoUnion_t." WHERE B.TRAMITADO = 1 AND B.ESTADO != 3 AND (B.EXISTENTE IS NULL OR SUBSTR(B.EXISTENTE,3,1) = 0)";

            $intErrorId_t = insertarLogconsulta(true,null,$intIdBase_t,$intIdMuestra_t,$intIdPaso_t,$strSQLInsertMuestra_t,'insercion-muestra','',$strUnionDeCampos_t);

            $mysqli->query($strSQLInsertMuestra_t);
                
            insertarLogconsulta(false,$intErrorId_t,null,null,null,null,null,$mysqli->error,null);

            //JDBD - Insertamos lo nuevo de telefonos errados en la muestra
            $strSQLInsertMuestra_t = "INSERT INTO ".$BaseDatos.".G".$intIdBase_t."_M".$intIdMuestra_t." (".collsDefaultMt($intIdBase_t,$intIdMuestra_t).") SELECT ".valuesDefaultMt($intIdBase_t,true)." FROM ".$BaseDatos.".G".$intIdBase_t." A JOIN dy_cargue_datos.".$strTablaTemp_t." B ON A.".$strCampoUnion_t." = B.".$strCampoUnion_t." WHERE B.TRAMITADO = 1 AND B.ESTADO = 3 AND (B.EXISTENTE IS NULL OR SUBSTR(B.EXISTENTE,3,1) = 0)";

            $intErrorId_t = insertarLogconsulta(true,null,$intIdBase_t,$intIdMuestra_t,$intIdPaso_t,$strSQLInsertMuestra_t,'insercion-muestra','',$strUnionDeCampos_t);

            $mysqli->query($strSQLInsertMuestra_t);
                
            insertarLogconsulta(false,$intErrorId_t,null,null,null,null,null,$mysqli->error,null);

            //JDBD - Si la campaña es de asignacion predefinida y seleccionaron asignacion automatica, se reparte equitativamente los registros del Excell por la cantidad de agentes.
            if (count($arrIdAgentes_t) > 0) {
                
                $intCantAgentes_t = count($arrIdAgentes_t);

                $strSQLRegistros_t = "SELECT count(1) as cantidad FROM ".$BaseDatos.".G".$intIdBase_t."_M".$intIdMuestra_t." A JOIN ".$BaseDatos.".G".$intIdBase_t." B ON A.G".$intIdBase_t."_M".$intIdMuestra_t."_CoInMiPo__b = B.G".$intIdBase_t."_ConsInte__b JOIN dy_cargue_datos.".$strTablaTemp_t." C ON B.".$strCampoUnion_t." = C.".$strCampoUnion_t." WHERE C.TRAMITADO = 1 ORDER BY A.G".$intIdBase_t."_M".$intIdMuestra_t."_CoInMiPo__b ASC";

                $resSQLRegistros_t = $mysqli->query($strSQLRegistros_t);

                $objSQLRegistros_t = $resSQLRegistros_t->fetch_object();

                $intCantReg = $objSQLRegistros_t->cantidad;

                $intCantPorAgente_t = ceil($intCantReg/$intCantAgentes_t);

                $intOffset_t = 0;

                $intCantAsignar_t = $intCantAgentes_t*$intCantPorAgente_t;

                foreach ($arrIdAgentes_t as $fila => $agente) {

                    if ($intCantAgentes_t == $fila+1) {
                        $strLimit_t = "LIMIT ".$intOffset_t.",".($intCantPorAgente_t+$intCantPorAgente_t);
                    }else{
                        $strLimit_t = "LIMIT ".$intOffset_t.",".$intCantPorAgente_t;
                    }

                    $strSQLUpdateUser_t = "UPDATE ".$BaseDatos.".G".$intIdBase_t."_M".$intIdMuestra_t." A JOIN (SELECT DISTINCT a.".$strCampoUnion_t.", b.G".$intIdBase_t."_ConsInte__b AS id, a.TRAMITADO FROM dy_cargue_datos.".$strTablaTemp_t." a JOIN ".$BaseDatos.".G".$intIdBase_t." b ON a.".$strCampoUnion_t." = b.".$strCampoUnion_t." WHERE a.TRAMITADO = 1 ORDER BY b.G".$intIdBase_t."_ConsInte__b ASC ".$strLimit_t.") B ON A.G".$intIdBase_t."_M".$intIdMuestra_t."_CoInMiPo__b = B.id SET A.G".$intIdBase_t."_M".$intIdMuestra_t."_ConIntUsu_b = ".$agente." WHERE B.TRAMITADO = 1";

                        $intErrorId_t = insertarLogconsulta(true,null,$intIdBase_t,$intIdMuestra_t,$intIdPaso_t,$strSQLUpdateUser_t,'asignacion-predefinida-automatica','',$strUnionDeCampos_t);

                        $mysqli->query($strSQLUpdateUser_t);

                        insertarLogconsulta(false,$intErrorId_t,null,null,null,null,null,$mysqli->error,null);

                    $intOffset_t = $intOffset_t+$intCantPorAgente_t;
                    
                }

            }

            //JDBD - Se asignan los registros por agente.
            if ($strAgente_t != "") {

                $strSQLUpdateAgente_t = "UPDATE ".$BaseDatos.".G".$intIdBase_t."_M".$intIdMuestra_t." A JOIN ".$BaseDatos.".G".$intIdBase_t." B ON A.G".$intIdBase_t."_M".$intIdMuestra_t."_CoInMiPo__b = B.G".$intIdBase_t."_ConsInte__b JOIN dy_cargue_datos.".$strTablaTemp_t." C ON B.".$strCampoUnion_t." = C.".$strCampoUnion_t." SET A.G".$intIdBase_t."_M".$intIdMuestra_t."_ConIntUsu_b = C.AGENTE WHERE C.ESTADO != 2 AND C.TRAMITADO = 1";

                    $intErrorId_t = insertarLogconsulta(true,null,$intIdBase_t,$intIdMuestra_t,$intIdPaso_t,$strSQLUpdateAgente_t,'asignacion-predefinida-manual','',$strUnionDeCampos_t); 

                    $mysqli->query($strSQLUpdateAgente_t);

                    insertarLogconsulta(false,$intErrorId_t,null,null,null,null,null,$mysqli->error,null);

            }
        }

        //JDBD - Marcamos con 2 alos registros tramitados.
            $strSQLTramitado_t = "UPDATE dy_cargue_datos.".$strTablaTemp_t." SET TRAMITADO = 2 WHERE TRAMITADO = 1";
            $mysqli->query($strSQLTramitado_t);

    }

    if (isset($_GET["InactivarRegistros"])) {

        $intIdBase_t = $_POST["intIdBase_t"];
        $intIdMuestra_t = $_POST["intIdMuestra_t"];
        $strTablaTemp_t = $_POST["strTablaTemp_t"];
        $strCampoUnion_t = $_POST["strCampoUnion_t"];
        
        //JDBD - Se inactivan todos los registros que no vengan en el excell.
        $strSQLUpdateInactivar_t = "UPDATE ".$BaseDatos.".G".$intIdBase_t."_M".$intIdMuestra_t." A JOIN ".$BaseDatos.".G".$intIdBase_t." B ON A.G".$intIdBase_t."_M".$intIdMuestra_t."_CoInMiPo__b = B.G".$intIdBase_t."_ConsInte__b LEFT JOIN dy_cargue_datos.".$strTablaTemp_t." C ON B.".$strCampoUnion_t." = C.".$strCampoUnion_t." SET G".$intIdBase_t."_M".$intIdMuestra_t."_Activo____b = 0 WHERE C.id IS NULL";

        $intErrorId_t = insertarLogconsulta(true,null,$intIdBase_t,$intIdMuestra_t,$intIdPaso_t,$strSQLUpdateInactivar_t,'inactivar-registros','',$strUnionDeCampos_t);

            $mysqli->query($strSQLUpdateInactivar_t);

            insertarLogconsulta(false,$intErrorId_t,null,null,null,null,null,$mysqli->error,null);

    }

    if(isset($_GET['procesoFlecha'])){

        $intIdPaso_t = $_POST["intIdPaso_t"];
        $intIdBase_t = $_POST["intIdBase_t"];
        $strCampoUnion_t = $_POST["strCampoUnion_t"];
        $strTablaTemp_t = $_POST["strTablaTemp_t"];
        $intIdMuestra_t = $_POST["intIdMuestra_t"];

        // Despues de haber realizado la insercion ejecuto el proceso de ejecucion de flechas para los registros insertados desde el paso de cargue
        if($intIdMuestra_t == -1){
	     exec("node /etc/dyalogo/apps/procesadorFlechas/procesarFlechasCargue.js -e cargador -p {$intIdPaso_t} -b {$intIdBase_t} -c {$strCampoUnion_t} -t {$strTablaTemp_t} >> /dev/null &");
        }

        echo json_encode(['procesado' => 'ok']);
    }
?>