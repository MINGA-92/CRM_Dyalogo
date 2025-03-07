<?php
ini_set('display_errors', 'On');
ini_set('display_errors', 1);

/**
 *JDBD - Esta funcion se encarga de quitar las tildes por vocal unica para evitar problemas con servicios externos.
 *@param string - Cadena.
 *@return string - Cadena. 
 */
function quitarTildes($strCadena_p)
{   
    $strCadena_t = trim($strCadena_p);

    $arrBuscar_t = ['á', 'à', 'ä', 'â', 'ª', 'Á', 'À', 'Â', 'Ä','é', 'è', 'ë', 'ê', 'É', 'È', 'Ê', 'Ë','í', 'ì', 'ï', 'î', 'Í', 'Ì', 'Ï', 'Î','ó', 'ò', 'ö', 'ô', 'Ó', 'Ò', 'Ö', 'Ô','ú', 'ù', 'ü', 'û', 'Ú', 'Ù', 'Û', 'Ü','ñ', 'Ñ', 'ç', 'Ç'];

    $arrCambiar_t = ['a', 'a', 'a', 'a', 'a', 'A', 'A', 'A', 'A','e', 'e', 'e', 'e', 'E', 'E', 'E', 'E','i', 'i', 'i', 'i', 'I', 'I', 'I', 'I','o', 'o', 'o', 'o', 'O', 'O', 'O', 'O','u', 'u', 'u', 'u', 'U', 'U', 'U', 'U','n', 'N', 'c', 'C'];

    $strCadena_t = str_replace($arrBuscar_t, $arrCambiar_t, $strCadena_t);

    return $strCadena_t; 
}

/**
 *JDBD - Esta funcion nos ayuda a verificar si el paso ya tiene las metas para no volver a insertar.
 *@param string - tipo de meta.
 *@param int - id del paso.
 *@return boolean - si existe o no la meta. 
 */
function metasQueYaExisten($strTipoMeta_p,$intIdPaso_p){
    
    global $mysqli;
    global $BaseDatos_general;
    global $DB_User_R;
    global $DB_Pass_R;
    global $ipReportes;
    global $BaseDatos;
    global $BaseDatos_systema;
    global $BaseDatos_telefonia;
    global $dyalogo_canales_electronicos;

    $arrMediciones = explode(",", $strTipoMeta_p);

    //JDBD - buscamos la meta.
    $strSQLMetas_t = "SELECT METDEF_Consinte__b 
                      FROM ".$BaseDatos_systema.".METDEF 
                      WHERE METDEF_Consinte__ESTPAS_b = ".$intIdPaso_p." AND 
                      METDEF_Nivel_____b = ".$arrMediciones[0]." AND 
                      METDEF_Tipo______b = ".$arrMediciones[1]." AND 
                      METDEF_SubTipo___b = ".$arrMediciones[2]." AND 
                      METDEF_Rango____b = ".$arrMediciones[3].";";

    $resSQLMeta_t = $mysqli->query($strSQLMetas_t);
    if ($resSQLMeta_t->num_rows > 0) {
        $boolMetaExis_t = true;
    }else{
        $boolMetaExis_t = false;
    }
    //JDBD - true = existe, false = no existe para insertar meta. 
    return $boolMetaExis_t;
}

/**
 *JDBD - Esta funcion retorna la configuracion de la meta segun la meta que se quiera obtener.
 *@param string - tipo de meta.
 *@return string - configuracion de la meta. 
 */
function configuracionDeMetas($strTipoMeta_p){
    $arrMetaDefinida_t = [];
    //JDBD - se valida el tipo de meta.
    switch ($strTipoMeta_p) {

            case '232':
                $arrMetaDefinida_t=['#Sin gestion','2,3,2,1'];
                break;
            case '264':
                $arrMetaDefinida_t=['%Sin gestion','2,6,4,1'];
                break;
            case '236':
                $arrMetaDefinida_t=['#Gestiones','2,3,6,1'];
                break;
            case '241':
                $arrMetaDefinida_t=['TMO','2,4,1,1'];
                break;
            case '234':
                $arrMetaDefinida_t=['#Contactados','2,3,4,1'];
                break;
            case '262':
                $arrMetaDefinida_t=['%Contactados','2,6,2,1'];
                break;
            case '238':
                $arrMetaDefinida_t=['#Contestadas','2,3,8,1'];
                break;
            case '261':
                $arrMetaDefinida_t=['%Contestadas','2,6,1,1'];
                break;
            case '233':
                $arrMetaDefinida_t=['#Efectivos','2,3,3,1'];
                break;
            case '263':
                $arrMetaDefinida_t=['%Efectivos','2,6,3,1'];
                break;
            case '221':
                $arrMetaDefinida_t=['#En cola','2,2,1,1'];
                break;
            case '211':
                $arrMetaDefinida_t=['%TSF','2,1,1,1'];
                break;
            case '237':
                $arrMetaDefinida_t=['#Recibidas','2,3,7,1'];
                break;
            case '311':
                $arrMetaDefinida_t=['TMO2','3,1,1,1'];
                break;
            case '312':
                $arrMetaDefinida_t=['Marcaciones','3,1,2,1'];
                break;
            case '313':
                $arrMetaDefinida_t=['Contestadas','3,1,3,1'];
                break;
            case '321':
                $arrMetaDefinida_t=['Gestiones','3,2,1,1'];
                break;
            case '322':
                $arrMetaDefinida_t=['Efectivos','3,2,2,1'];
                break;
            case '331':
                $arrMetaDefinida_t=['Infracciones','3,3,1,1'];
                break;

    }

    return $arrMetaDefinida_t;
}
/**
 *JDBD - Esta funcion inserta las metas pre definidas segun estrategia por cada paso, saliente o entrante.
 *@param int - Id de la estrategia.
 *@param int - Id del paso.
 *@param int - tipo de meta.
 *@return fetch - datos de consulta. 
 */
function insertarMetas($intIdEstrat_p,$intIdPaso_p,$intTipoPaso_p){

    global $mysqli;
    global $BaseDatos_general;
    global $DB_User_R;
    global $DB_Pass_R;
    global $ipReportes;
    global $BaseDatos;
    global $BaseDatos_systema;
    global $BaseDatos_telefonia;
    global $dyalogo_canales_electronicos;


    //JDBD - validamos si las metas son para paso tipo entrante o saliente.
    if ($intTipoPaso_p == 1) {
        //JDBD - Escogemos las metas pertenecientes en este caso a las entrantes.
        $arrMetas_t = ['221','237','238','233','263','261','241','211','311','312','313','321','322','331'];

        for ($i=0; $i < count($arrMetas_t); $i++) { 
            //JDBD - obtenemos el nombre y la configuracion de la meta. 
            $resConfMetas_t = configuracionDeMetas($arrMetas_t[$i]);
            $resMeta_t = metasQueYaExisten($resConfMetas_t[1],$intIdPaso_p);
            //JDBD - validamos si existe la meta por insertar.
            if ($resMeta_t == false) {
                //JDBD - insertamos la meta
                $strSQLMetInsert_t = "INSERT INTO ".$BaseDatos_systema.".METDEF (METDEF_Nombre___b,METDEF_Consinte__ESTRAT_b,METDEF_Consinte__ESTPAS_b,METDEF_Nivel_____b,METDEF_Tipo______b,METDEF_SubTipo___b,METDEF_Rango____b) VALUES ('".$resConfMetas_t[0]."',".$intIdEstrat_p.",".$intIdPaso_p.",".$resConfMetas_t[1].");";
                
                $resSQLInsert_t = $mysqli->query($strSQLMetInsert_t);
            }
        }

    }else{

        $arrMetas_t = ['236','241','232','234','262','233','263','264','311','312','313','321','322','331'];

        for ($i=0; $i < count($arrMetas_t); $i++) { 

            $resConfMetas_t = configuracionDeMetas($arrMetas_t[$i]);
            $resMeta_t = metasQueYaExisten($resConfMetas_t[1],$intIdPaso_p);

            if ($resMeta_t == false) {
                $strSQLMetInsert_t = "INSERT INTO ".$BaseDatos_systema.".METDEF (METDEF_Nombre___b,METDEF_Consinte__ESTRAT_b,METDEF_Consinte__ESTPAS_b,METDEF_Nivel_____b,METDEF_Tipo______b,METDEF_SubTipo___b,METDEF_Rango____b) VALUES ('".$resConfMetas_t[0]."',".$intIdEstrat_p.",".$intIdPaso_p.",".$resConfMetas_t[1].");";
                
                $resSQLInsert_t = $mysqli->query($strSQLMetInsert_t);
            }

        }

    }

}
/**
 *JDBD - Esta funcion obtiene todos los pasos de tipo ENTRANTE y SALIENTE de la estrategia que llega por parametro.
 *@param int - Id de la estrategia.
 *@return fetch - datos de consulta. 
 */
function obtenerPasos($intIdEstrat_p){

    global $mysqli;
    global $BaseDatos_general;
    global $DB_User_R;
    global $DB_Pass_R;
    global $ipReportes;
    global $BaseDatos;
    global $BaseDatos_systema;
    global $BaseDatos_telefonia;
    global $dyalogo_canales_electronicos;

    $strSQLPasos_t = "SELECT ESTPAS_ConsInte__b, ESTPAS_Tipo______b, ESTPAS_Comentari_b 
                  FROM ".$BaseDatos_systema.".ESTPAS 
                  WHERE ESTPAS_Tipo______b IN (1,6) AND 
                  ESTPAS_ConsInte__ESTRAT_b = ".$intIdEstrat_p.";";

    $resSQLPasos_t = $mysqli->query($strSQLPasos_t);
    $arrPasos_t = [];
    if ($resSQLPasos_t->num_rows > 0) {
        $i=0;
        //JDBD - obtenemos los datos de cada paso.
        while ($resSQLPaso_t = $resSQLPasos_t->fetch_object()) {
            $arrPasos_t[]=[
                    "ESTPAS_ConsInte__b" => $resSQLPaso_t->ESTPAS_ConsInte__b,
                    "ESTPAS_Tipo______b" => $resSQLPaso_t->ESTPAS_Tipo______b,
                    "ESTPAS_Comentari_b" => $resSQLPaso_t->ESTPAS_Comentari_b
            ]; 
            $i++;
        }
    }

    return $arrPasos_t;

}

/**
*JDBD - Esta funcion actualiza los reportes automatizados existentes por estrategia.
*@param int - id de la estrategia
*@param string - correo electronico del destinatario del reporte.
*@param strin - correo electronico a quien va la copia
*@param int - este el el numero que indica el tipo de periodicidad de envio d,s,m
*@param string - la hora a la que se decide enviar el reporte.
*@param strin - nombre o asuton del reporte.
*@param int - id del reporte.
*@return string - retorna un 'si' si fue exitosa la actualizacion de lo contrario sera un vacio.
*/
function updateNuevoReporte($intIdEstrat_p,$strDestinatario_p,$strCopia_p,$intPeriodicidad_p,$strHoraEnvio_p,$strNombreReport_p,$intIdReporte_p){

    global $mysqli;
    global $BaseDatos_general;
    global $DB_User_R;
    global $DB_Pass_R;
    global $ipReportes;
    global $BaseDatos;
    global $BaseDatos_systema;
    global $BaseDatos_telefonia;
    global $dyalogo_canales_electronicos;

    $strUpdate_t = "UPDATE ".$BaseDatos_general.".reportes_automatizados SET 
                        destinatarios = '".$strDestinatario_p."',
                        destinatarios_cc = '".$strCopia_p."',
                        tipo_periodicidad = ".$intPeriodicidad_p.",
                        momento_envio = '".$strHoraEnvio_p."',
                        asunto = '".$strNombreReport_p."' ,
                        bd_ip = '".$ipReportes."' 
                        WHERE id = ".$intIdReporte_p." AND personalizado IS NULL;";
    $strUP_T = "";
    if ($mysqli->query($strUpdate_t)) {
        $strUP_T = "si";
        $arrConsultasHojas_t = generarReportesEstrategia($intIdEstrat_p, $intPeriodicidad_p, true);
        
    }
   return $strUP_T;
}

/**
*JDBD - Esta funcion inserta nuevos reportes automatizados segun la estrategia estrategia.
*@param strin - nombre o asuton del reporte.
*@param string - indica la ruta en donde quedara alojado el excell temporalmente en el servidor.
*@param string - correo electronico del destinatario del reporte.
*@param strin - correo electronico a quien va la copia
*@param int - este el el numero que indica el tipo de periodicidad de envio d,s,m
*@param int - id de la estrategia
*@param int - id del huesped donde pertenece la estrategia.
*@return array - posicion [0] = nombres de las hosjas en el excell, [1] = consultas para cada tipo de reporte.
*/
function insertarNuevoReporte($strAsunto_p,$strRuta_p,$strDestinatario_p,$strHoraEnvio_p,$strCopia_p,$intPeriodicidad_p,$intIdEstrat_p,$intIdHuesped_p,$booExiste_p){

    global $mysqli;
    global $BaseDatos_general;
    global $DB_User_R;
    global $DB_Pass_R;
    global $ipReportes;
    global $BaseDatos;
    global $BaseDatos_systema;
    global $BaseDatos_telefonia;
    global $dyalogo_canales_electronicos;
    
    $strInsercion_t = "INSERT INTO ".$BaseDatos_general.".reportes_automatizados 
                       (bd_usuario,
                       bd_contrasena,
                       bd_ip,
                        ruta_archivo,
                        destinatarios,
                        destinatarios_cc,
                        tipo_periodicidad,
                        momento_envio,
                        asunto,
                        id_huesped,
                        id_estrategia,nombres_hojas) VALUES ('".$DB_User_R."','".$DB_Pass_R."','".$ipReportes."','".$strRuta_p."','".$strDestinatario_p."','".$strCopia_p."',".$intPeriodicidad_p.",'".$strHoraEnvio_p."','".$strAsunto_p."',".$intIdHuesped_p.",".$intIdEstrat_p.",'vacio')";
    $arrConsultasHojas_t = [];

    if ($booExiste_p == false) {

        $arrConsultasHojas_t = generarReportesEstrategia($intIdEstrat_p, $intPeriodicidad_p, true);

    }else{

        if ($mysqli->query($strInsercion_t)) {

            $arrConsultasHojas_t = generarReportesEstrategia($intIdEstrat_p, $intPeriodicidad_p, true);
            
        }

    }

   return $arrConsultasHojas_t;

}

/**
*JDBD - Esta funcion obtiene la informacion de la estrategia,
*@param int - id de la estrategia.
*@return string - el nombre de la estrategia.
*/
function datosEstrat($intIdEstrat_p){

    global $mysqli;
    global $BaseDatos_general;
    global $DB_User_R;
    global $DB_Pass_R;
    global $ipReportes;
    global $BaseDatos;
    global $BaseDatos_systema;
    global $BaseDatos_telefonia;
    global $dyalogo_canales_electronicos;

    $strSQLEstrat_t = "SELECT  * FROM ".$BaseDatos_systema.".ESTRAT WHERE ESTRAT_ConsInte__b = ".$intIdEstrat_p;
    $resSQLDatosEstrat_p = $mysqli->query($strSQLEstrat_t);
    $resSQLDatosEstrat_p = $resSQLDatosEstrat_p->fetch_array();

    $strNombreEstrat_t = $resSQLDatosEstrat_p["ESTRAT_Nombre____b"];

    return $strNombreEstrat_t;
}


/**
 * JDB - Lo comente yo pero esta funcion la realizo Juan David
 * Quita lo que no sea numeros y letras
 * @return String retorna numeros del 0-1 y letras Aa - Zz
 */
function depCadenas($string)
{
    $string = strtolower($string);
    $conservar = '0-9a-z'; // juego de caracteres a conservar
    $regex = sprintf('~[^%s]++~i', $conservar); //se le ordena arrancar todo segun la regla $conservar
    $cadena = preg_replace($regex, '', $string); //se reemplaza a vacio por la $regex a la cadena;
    return $cadena;
}

/**
 * Reemplaza caracteres raros y especiales
 * @return String
 */
function sanear_strings($string)
{
    $string = str_replace(array('á', 'à', 'ä', 'â', 'ª', 'Á', 'À', 'Â', 'Ä'), array('a', 'a', 'a', 'a', 'a', 'A', 'A', 'A', 'A'), $string);
    $string = str_replace(array('é', 'è', 'ë', 'ê', 'É', 'È', 'Ê', 'Ë'), array('e', 'e', 'e', 'e', 'E', 'E', 'E', 'E'), $string);
    $string = str_replace(array('í', 'ì', 'ï', 'î', 'Í', 'Ì', 'Ï', 'Î'), array('i', 'i', 'i', 'i', 'I', 'I', 'I', 'I'), $string);
    $string = str_replace(array('ó', 'ò', 'ö', 'ô', 'Ó', 'Ò', 'Ö', 'Ô'), array('o', 'o', 'o', 'o', 'O', 'O', 'O', 'O'), $string);
    $string = str_replace(array('ú', 'ù', 'ü', 'û', 'Ú', 'Ù', 'Û', 'Ü'), array('u', 'u', 'u', 'u', 'U', 'U', 'U', 'U'), $string);
    $string = str_replace(array('ñ', 'Ñ', 'ç', 'Ç'), array('n', 'N', 'c', 'C'), $string);
    $string = str_replace(array("\\", "¨", "º", "-", "~", "#", "@", "|", "!", "\"", "·", "$", "%", "&", "/", "(", ")", "?", "'", "¡", "¿", "[", "^", "`", "]", "+", "}", "{", "¨", "´", ">“, “< ", ";", ",", ":", "."), '', $string);
    return $string;
}

/**
 * Esta funcion crea el reporte diario de gestiones en el sistema
 * @param boolean
 * @param int
 * @return String retorna la consulta SQL con las gestiones del dia
 */
function SQLReporteCampana($intIdCampan_p, $intTipoReporte_p, $intPeriodicidad_t, $intMuestra_t = null, $intIdDB_t = null, $idPaso = null  )
{   

    global $mysqli;
    global $BaseDatos_general;
    global $DB_User_R;
    global $DB_Pass_R;
    global $ipReportes;
    global $BaseDatos;
    global $BaseDatos_systema;
    global $BaseDatos_telefonia;
    global $dyalogo_canales_electronicos;

    $INT_CANTIDAD_MAXIMA_REGISTROS_T = 5000;
    $strFiltro_t = "";

    // Si es de tipo 3 son gestiones por lo cual el filtro va por fecha de gestion
    // si es 4 son las muestras estas deberia filtra por

    switch ($intTipoReporte_p){
        case 3:
            $strColFecha = "FECHA_GESTION";
            break;
        case 6:
            $strColFecha = "G".$intIdDB_t."_FechaInsercion";
            break;
        default:
            $strColFecha = "FECHA_CREACION";
            break;
    }

    switch ($intPeriodicidad_t) {
        case 1:
            $strFiltro_t = " DATE_FORMAT({$strColFecha},'%Y-%m-%d') = DATE(now()) ";
            break;

        case 2:
            $strFiltro_t = " WEEK({$strColFecha}) = WEEK(now())-1 AND YEAR({$strColFecha}) = YEAR(CURDATE()) ";
            break;

        case 3:
            $strFiltro_t = " DATE_FORMAT({$strColFecha},'%Y-%m') = DATE_FORMAT(DATE_SUB(curdate(),INTERVAL 1 MONTH),'%Y-%m') ";
            break;
    }

    $strSQLDatosCampana_t = "SELECT CAMPAN_ConsInte__GUION__Pob_b,CAMPAN_ConsInte__GUION__Gui_b, CAMPAN_Nombre____b, CAMPAN_TipoCamp__b FROM " . $BaseDatos_systema . ".CAMPAN WHERE CAMPAN_ConsInte__b = " . $intIdCampan_p;
    $resSQLDatosCampana_t = $mysqli->query($strSQLDatosCampana_t);
    $objDatosAnalisis_t = $resSQLDatosCampana_t->fetch_array();


    // Tipo 4 hace referencia a los reportes que no son campañas pero si poseen una muestra
    if($intTipoReporte_p == 4){
        $strConsultaReferenciaVistas_t = "SELECT nombre FROM " . $BaseDatos_general . ".vistas_generadas where id_estrat = ".$intIdCampan_p." and nombre like '%".$intMuestra_t."%' ORDER BY ID DESC LIMIT 1";
    }

    if ($intTipoReporte_p == 3) {
        $strConsultaReferenciaVistas_t = "SELECT nombre FROM " . $BaseDatos_general . ".vistas_generadas WHERE id_guion=" . $objDatosAnalisis_t['CAMPAN_ConsInte__GUION__Gui_b']. " ORDER BY ID DESC LIMIT 1";

    }elseif($intTipoReporte_p == 2){

            $strIdEstrat_t = "SELECT ESTPAS_ConsInte__ESTRAT_b FROM " . $BaseDatos_systema . ".ESTPAS WHERE ESTPAS_ConsInte__CAMPAN_b = ".$intIdCampan_p;

            $resIdEstrat_t = $mysqli->query($strIdEstrat_t);

            if($resIdEstrat_t->num_rows > 0){
                $objIdEstrat_t = $resIdEstrat_t->fetch_object();
                $intIdEstrat_t = $objIdEstrat_t->ESTPAS_ConsInte__ESTRAT_b;
                $strConsultaReferenciaVistas_t = "SELECT nombre FROM " . $BaseDatos_general . ".vistas_generadas WHERE id_guion = -1000 AND id_estrat = ".$intIdEstrat_t;
            }else{
                $strConsultaReferenciaVistas_t = "";
            }

            
    }elseif($intTipoReporte_p == 1){

        if($resSQLDatosCampana_t->num_rows > 0){
            $strConsultaReferenciaVistas_t = "SELECT nombre FROM " . $BaseDatos_general . ".vistas_generadas WHERE id_guion=" . $objDatosAnalisis_t['CAMPAN_ConsInte__GUION__Pob_b'];
        }else{
            $strConsultaReferenciaVistas_t = "SELECT nombre FROM " . $BaseDatos_general . ".vistas_generadas WHERE id_guion=" . $intIdCampan_p;
        }
    }

    // Primero se valida si el reporte no corresponde a un backoffice y si la consulta de la vista no esta vacia
    if($intTipoReporte_p < 5  && $strConsultaReferenciaVistas_t != ""){
        $resSQLReferencia_t = $mysqli->query($strConsultaReferenciaVistas_t);
        if ($resSQLReferencia_t->num_rows > 0) {
            $objNombreVista_t = $resSQLReferencia_t->fetch_array();
    
            if ($intTipoReporte_p == 3) {
                $resSentidoCol_t = $mysqli->query("SHOW COLUMNS FROM ".$BaseDatos.".".$objNombreVista_t['nombre']." LIKE 'SENTIDO';");
    
                if ($resSentidoCol_t->num_rows > 0) {
                    //JDBD - se valida el tipo de sentido por si la campaña compleja usa una sola tabla de gestiones.
                    if ($objDatosAnalisis_t['CAMPAN_TipoCamp__b'] < 4) {
                        $strFiltroTipo_t = " AND (SENTIDO LIKE 'Entrante' OR SENTIDO = '' OR SENTIDO IS NULL) ";
                    }else{
                        $strFiltroTipo_t = " AND (SENTIDO LIKE 'Saliente' OR SENTIDO = '' OR SENTIDO IS NULL) ";
                    }
                }else{
                    $strFiltroTipo_t = "";
                }
            }
    
            if ($intTipoReporte_p == 3) {
                $strConsulta_t = '"SELECT * FROM ' . $BaseDatos . '.' . $objNombreVista_t['nombre'] . ' WHERE ' . $strFiltro_t .$strFiltroTipo_t. ' AND  Paso = \''.$idPaso.'\' "';
            } else { // si es una base de datos o base+paso
                $strConsulta_t = '"SELECT * FROM ' . $BaseDatos . '.' . $objNombreVista_t['nombre'] . ' LIMIT ' . $INT_CANTIDAD_MAXIMA_REGISTROS_T . '"';
            }
        }else{
            $strConsulta_t = null;
        }
    }else{
        $strConsulta_t = null;
    }

    // Si es un reporte para backoffice se crea una consulta diferente
    if($intTipoReporte_p == 5){
        $arrDataColumnasDinamicas_t = columnasReportBkPaso($intIdDB_t);;
        $strConsulta_t = '"SELECT '.$arrDataColumnasDinamicas_t[0].' FROM '.$BaseDatos.'.G'.$intIdDB_t.' INNER JOIN '.$BaseDatos.'.G'.$intIdDB_t.'_M'.$intMuestra_t.' ON G'.$intIdDB_t.'_ConsInte__b = G'.$intIdDB_t.'_M'.$intMuestra_t.'_CoInMiPo__b '.$arrDataColumnasDinamicas_t[1] .' LIMIT ' . $INT_CANTIDAD_MAXIMA_REGISTROS_T . '"';
    } 


    // Si es un reporte de bots se crea una consulta muy diferente
    if($intTipoReporte_p == 6){
        $arrDataColumnasDinamicas_t = columnasReportBot($intIdDB_t);
        $strConsulta_t = '"SELECT '.$arrDataColumnasDinamicas_t[0].' FROM '.$BaseDatos.'.G'.$intIdDB_t." ".$arrDataColumnasDinamicas_t[1] . ' WHERE (G'.$intIdDB_t.'_Paso = '.$idPaso.' OR G'.$intIdDB_t.'_Paso = "" OR G'.$intIdDB_t.'_Paso IS NULL) AND ('.$strFiltro_t.') ORDER BY G'.$intIdDB_t.'_ConsInte__b DESC LIMIT ' . $INT_CANTIDAD_MAXIMA_REGISTROS_T . '"';
    } 

    return $strConsulta_t;
}

/**
 * DLAB - 2019-08-15
 * Funcion para generar las consultas de ACD
 * @param Integer intIdCampan_p  campana
 * @param String strTipoFuncionFecha_p el tipo de funcion d=dia, m = mes s= semana
 * @param Integer intTipoReporte_p el tipo de reporte a generar
 * @return String strSSQL
 */
function SQLReporteACD($intIdCampan_p, $intPeriodicidad_t, $intTipoReporte_p)
{

    global $mysqli;
    global $BaseDatos_general;
    global $DB_User_R;
    global $DB_Pass_R;
    global $ipReportes;
    global $BaseDatos;
    global $BaseDatos_systema;
    global $BaseDatos_telefonia;
    global $dyalogo_canales_electronicos;

    switch ($intPeriodicidad_t) {
        case 1:
            $strFiltro_t = " DATE_FORMAT(Fecha,'%Y-%m-%d') = DATE(now()) ";
            break;

        case 2:
            $strFiltro_t = " WEEK(Fecha) = WEEK(now())-1 AND YEAR(Fecha) = YEAR(CURDATE()) ";
            break;

        case 3:
            $strFiltro_t = " DATE_FORMAT(Fecha,'%Y-%m') = DATE_FORMAT(DATE_SUB(curdate(),INTERVAL 1 MONTH),'%Y-%m') ";
            break;
    }

    switch ($intTipoReporte_p) {
        case 1:

            $strTipoACD_t = "_ACD_DIA_";

            break;
        case 2:

            $strTipoACD_t = "_ACD_HORA_";

            break;
    }

    $strSQLVistaACD_t = "SELECT nombre FROM ".$BaseDatos_general.".vistas_generadas WHERE id_campan = ".$intIdCampan_p." AND nombre LIKE '%".$strTipoACD_t."%'";

    $resSQLVistaACD_t = $mysqli->query($strSQLVistaACD_t);

    if ($resSQLVistaACD_t->num_rows > 0) {

        $objSQLVistaACD_t = $resSQLVistaACD_t->fetch_object();      
        
        return ' , "SELECT * FROM '.$BaseDatos.'.'.$objSQLVistaACD_t->nombre.' WHERE '.$strFiltro_t.' ORDER BY Fecha DESC;"';  

    }else{

        return false;

    }

}

/**
 * DLAB - 20190816
 * Funcion para crear los reportes dependiendo de la periodicidad
 * @param Int el Id de la estrategia
 * @param String el tipo de periodicidad 1 - 2 - 3
 * @param Bool pregunta si se serializa la tabla o no
 * @return array Posicion 0 el nombre de las fichas, posicion 1 las consultas
 */
function generarReportesEstrategia($intIdEstrategia_p, $intPeriodicidad_t, $booSerializarTabla_p)
{

    global $mysqli;
    global $BaseDatos_general;
    global $DB_User_R;
    global $DB_Pass_R;
    global $ipReportes;
    global $BaseDatos;
    global $BaseDatos_systema;
    global $BaseDatos_telefonia;
    global $dyalogo_canales_electronicos;

    $arrReportesFichas_t = [];

    //Comenzamos por los reportes diarios
    $strSQLEstrat_t = "SELECT ESTRAT_Nombre____b,ESTRAT_ConsInte_GUION_Pob FROM " . $BaseDatos_systema . ".ESTRAT WHERE ESTRAT_Consinte__b = " . $intIdEstrategia_p;   
    $resEstrat_t = $mysqli->query($strSQLEstrat_t);
    $objEstrategia_t = $resEstrat_t->fetch_Array();
    $intIDBaseDatos_t = $objEstrategia_t['ESTRAT_ConsInte_GUION_Pob'];
    $strNombreEstrat_t = $objEstrategia_t['ESTRAT_Nombre____b'];

    //Consulta para extraer todas los pasos
    $strSQLPasos_t = "SELECT ESTPAS_ConsInte__b,ESTPAS_Tipo______b, ESTPAS_Comentari_b, ESTPAS_ConsInte__MUESTR_b,  ESTPAS_ConsInte__CAMPAN_b, a.CAMPAN_Nombre____b, a.CAMPAN_IdCamCbx__b, CAMPAN_TipoCamp__b  FROM " . $BaseDatos_systema . ".ESTPAS LEFT JOIN " . $BaseDatos_systema . ".CAMPAN as a ON a.CAMPAN_ConsInte__b = ESTPAS_ConsInte__CAMPAN_b WHERE ESTPAS_ConsInte__ESTRAT_b = " . $intIdEstrategia_p." AND  ESTPAS_Tipo______b != 4 AND ESTPAS_Tipo______b != 5 AND ESTPAS_activo = -1";
    $resPasos_t = $mysqli->query($strSQLPasos_t);
    $idCampan_t = 0;

    $strNombresFichas_t = "";
    $strConsultasReportes_t = "";
    $intContadorReportes_t = 0;

    //Recorremos todos los pasos de la estrategia que esten asociados 
    while ($itemPaso_t = $resPasos_t->fetch_object()) {

        // Validamos si tiene campaña asociadas
        if($itemPaso_t->ESTPAS_ConsInte__CAMPAN_b != null ){

            $idCampan_t = $itemPaso_t->ESTPAS_ConsInte__CAMPAN_b;
            $strNombreCampana_t = $itemPaso_t->CAMPAN_Nombre____b;
            $intIdCampanCBX_t = $itemPaso_t->CAMPAN_IdCamCbx__b;
    
            //Asignamos el nombre de la ficha
            $strNombreFicha_t = $strNombreCampana_t;
            $strNombreFicha_t = sanear_strings($strNombreFicha_t);
            $strNombreFicha_t = str_replace(' ', '_', $strNombreFicha_t);
            $strNombreFicha_t = substr($strNombreFicha_t, 0, 15); //NUMERO DEL CONTADOR
            $strNombreFicha_t = $strNombreFicha_t . "_" . $intContadorReportes_t;

        }else{

            $idCampan_t = $intIDBaseDatos_t;
            $strNombrePaso_t = $itemPaso_t->ESTPAS_Comentari_b;


            //Asignamos el nombre de la ficha
            $strNombreFicha_t = $strNombrePaso_t;
            $strNombreFicha_t = sanear_strings($strNombreFicha_t);
            $strNombreFicha_t = str_replace(' ', '_', $strNombreFicha_t);
            $strNombreFicha_t = substr($strNombreFicha_t, 0, 15); //NUMERO DEL CONTADOR
            $strNombreFicha_t = $strNombreFicha_t . "_" . $intContadorReportes_t;

        }

        //Analizamos esta variable para solo crear una vez la consulta a la base de datos
        if ($intContadorReportes_t == 0) {
            //JDBD - se recorta nombre de hoja base.
            $strNombreHojaBase_t = "BASE_".$idCampan_t."_".$strNombreEstrat_t;
            $strNombreHojaBase_t = substr($strNombreHojaBase_t, 0, 25);

            $strNombresFichas_t .= '"'.$strNombreHojaBase_t.'"';
            $strConsultasReportes_t .= SQLReporteCampana($idCampan_t, 1, $intPeriodicidad_t);



            // SE DESACTIVA REPORTE DE ESTRATEGIA DEBIDO A QUE GENERA UNA CONSULTA DEMASIADO PESADA
            
            // JDBD - se recorta nombre de hoja base - El reporte tipo 2 hace referencia al resumen general de la estrategia
            // if (!is_null(SQLReporteCampana($idCampan_t, 2, $intPeriodicidad_t))) {
            //     $strNombreHojaBase_t = "ESTRATEGIA_".$idCampan_t."_".$strNombreEstrat_t;
            //     $strNombreHojaBase_t = substr($strNombreHojaBase_t, 0, 25);

            //     $strNombresFichas_t .= ',"'.$strNombreHojaBase_t.'"';
            //     $strConsultasReportes_t .= ','.SQLReporteCampana($idCampan_t, 2, $intPeriodicidad_t);
            // }
        }

        if ($itemPaso_t->ESTPAS_ConsInte__CAMPAN_b != null && !empty($itemPaso_t->ESTPAS_ConsInte__CAMPAN_b)) {
            //JDBD - se recorta nombre de hoja gestiones
            $strNombreHojaGestiones_t = "GS_".$itemPaso_t->ESTPAS_ConsInte__CAMPAN_b."_".$strNombreFicha_t;
            $strNombreHojaGestiones_t = substr($strNombreHojaGestiones_t, 0, 25);
            //Gestiones
            $strNombresFichas_t .= ',"'.$strNombreHojaGestiones_t.'"';
            $strConsultasReportes_t .= ','.SQLReporteCampana($itemPaso_t->ESTPAS_ConsInte__CAMPAN_b, 3, $intPeriodicidad_t, null, null, $itemPaso_t->ESTPAS_ConsInte__b);

            //ACD - Generamos los reportes de ACD
            if ($itemPaso_t->CAMPAN_TipoCamp__b < 4) {
                //JDBD - definimos los reportes ACD dependiendo la periodicidad
                switch ($intPeriodicidad_t) {
                    case 1:
                        
                        $resSQLReporteACD = SQLReporteACD($idCampan_t, $intPeriodicidad_t, 1);

                        if ($resSQLReporteACD) {

                            $strNombresFichas_t .= ',"ACD ' . $strNombreFicha_t . '_DIA"';
                            $strConsultasReportes_t .= SQLReporteACD($idCampan_t, $intPeriodicidad_t, 1);

                        }

                        $resSQLReporteACD = SQLReporteACD($idCampan_t, $intPeriodicidad_t, 2);

                        if ($resSQLReporteACD) {

                            $strNombresFichas_t .= ' , "ACD ' . $strNombreFicha_t . '_HORA"';
                            $strConsultasReportes_t .= SQLReporteACD($idCampan_t, $intPeriodicidad_t, 2);

                        }

                        break;
                    case 2:

                        $resSQLReporteACD = SQLReporteACD($idCampan_t, $intPeriodicidad_t, 1);

                        if ($resSQLReporteACD) {

                            $strNombresFichas_t .= ',"ACD ' . $strNombreFicha_t . '_DIA"';
                            $strConsultasReportes_t .= SQLReporteACD($idCampan_t, $intPeriodicidad_t, 1);

                        }

                        $resSQLReporteACD = SQLReporteACD($idCampan_t, $intPeriodicidad_t, 2);

                        if ($resSQLReporteACD) {

                            $strNombresFichas_t .= ',"ACD ' . $strNombreFicha_t . '_HORA"';
                            $strConsultasReportes_t .= SQLReporteACD($idCampan_t, $intPeriodicidad_t, 2);

                        }

                        break;
                    case 3:

                        $resSQLReporteACD = SQLReporteACD($idCampan_t, $intPeriodicidad_t, 1);

                        if ($resSQLReporteACD) {

                            $strNombresFichas_t .= ',"ACD ' . $strNombreFicha_t . '_DIA"';
                            $strConsultasReportes_t .= SQLReporteACD($idCampan_t, $intPeriodicidad_t, 1);

                        }

                        break;
                }
            }

        }


        // Se ejecuta si el paso no tiene ninguna campaña asociada pero si una muestra, por lo general backoffice o canales salientes
        if($itemPaso_t->ESTPAS_ConsInte__CAMPAN_b == null && $itemPaso_t->ESTPAS_ConsInte__MUESTR_b != null){

            //JDBD - se recorta nombre de hoja gestiones
            $strNombreHojaGestiones_t = "BD+PASO_".$itemPaso_t->ESTPAS_ConsInte__b."_".$strNombreFicha_t;
            $strNombreHojaGestiones_t = substr($strNombreHojaGestiones_t, 0, 25);

            //Muestras
            $strNombresFichas_t .= ',"'.$strNombreHojaGestiones_t.'"';

            if($itemPaso_t->ESTPAS_Tipo______b == 9){
                $strConsultasReportes_t .= ','.SQLReporteCampana($intIdEstrategia_p, 5, $intPeriodicidad_t, $itemPaso_t->ESTPAS_ConsInte__MUESTR_b, $intIDBaseDatos_t);
            }else{
                $strConsultasReportes_t .= ','.SQLReporteCampana($intIdEstrategia_p, 4, $intPeriodicidad_t, $itemPaso_t->ESTPAS_ConsInte__MUESTR_b, null, $itemPaso_t->ESTPAS_ConsInte__b);
            }
            


        }

        // Para los pasos que son bots toca adicionar un reporte especifico
        if($itemPaso_t->ESTPAS_Tipo______b == 12){
                //JDBD - se recorta nombre de hoja gestiones
                $strNombreHojaGestiones_t = "GS_BOT_".$itemPaso_t->ESTPAS_ConsInte__b."_".$strNombreFicha_t;
                $strNombreHojaGestiones_t = substr($strNombreHojaGestiones_t, 0, 25);
    
                // Antes de adicionarlo primero validamos si encontramos el G de las gestiones
                $sqlGBot = "SELECT id_guion_gestion FROM  {$dyalogo_canales_electronicos}.dy_bot WHERE id_estpas = {$itemPaso_t->ESTPAS_ConsInte__b};";
                $resGBot = $mysqli->query($sqlGBot);
                if($resGBot && $resGBot->num_rows > 0){

                    $idGbot = $resGBot->fetch_object()->id_guion_gestion;

                    $strNombresFichas_t .= ',"'.$strNombreHojaGestiones_t.'"';
                    $strConsultasReportes_t .= ','.SQLReporteCampana($intIdEstrategia_p, 6, $intPeriodicidad_t, NULL, $idGbot, $itemPaso_t->ESTPAS_ConsInte__b);

                }

                        
        }

        $intContadorReportes_t++;

    } //Fin del recorrido de los pasos

    //Limipamos las consultas y los nombres de cualquier caracter especial con real_escape_string
    $strNombresFichas_t = $mysqli->real_escape_string($strNombresFichas_t);
    $strConsultasReportes_t = $mysqli->real_escape_string($strConsultasReportes_t);

    $arrReportesFichas_t[0] = $strNombresFichas_t;
    $arrReportesFichas_t[1] = $strConsultasReportes_t;

    $strUpdate= "";
    if ($booSerializarTabla_p) {
       $strUpdate = actualizaConsultasReportes($intIdEstrategia_p, $intPeriodicidad_t, $arrReportesFichas_t);
    }
    return $strUpdate;
}

/**
 * DLAB - 20190816
 * Actualiza las consultas en los reportes de la estrategia
 * @param int ID Estrategia
 * @param int Tipo de periodicidad 1= diario , 2 = semanal, 3 = mensual
 */
function actualizaConsultasReportes($intIdEstrategia_p, $intTipoPeriodicidad_p, $arrConsutlasHojas_p)
{

    global $mysqli;
    global $BaseDatos_general;
    global $DB_User_R;
    global $DB_Pass_R;
    global $ipReportes;
    global $BaseDatos;
    global $BaseDatos_systema;
    global $BaseDatos_telefonia;
    global $dyalogo_canales_electronicos;

    // ESTA CONSULTA NO HACE NADA
    // $strSQLEstrat_t = "SELECT ESTRAT_ConsInte__b, ESTRAT_Nombre____b,ESTRAT_ConsInte__PROYEC_b FROM " . $BaseDatos_systema . ".ESTRAT WHERE ESTRAT_ConsInte__b = " . $intIdEstrategia_p;
    // $resEstrat_t = $mysqli->query($strSQLEstrat_t);
    // $objEstrat_t = $resEstrat_t->fetch_array();

    $strConsultaReportesAutomatizados_t = "SELECT id FROM " . $BaseDatos_general . ".reportes_automatizados WHERE id_estrategia = " . $intIdEstrategia_p . " AND tipo_periodicidad = " . $intTipoPeriodicidad_p . " AND nombres_hojas NOT LIKE '%PausasConHorarioMuyLargas%'";
    $resReportesAutomatizados_t = $mysqli->query($strConsultaReportesAutomatizados_t);

    //Recorremos los reportes dependiendo de la periodicidad
    while ($itemFilaReporte_t = $resReportesAutomatizados_t->fetch_object()) {
        $strActualizacion_t = "UPDATE " . $BaseDatos_general . ".reportes_automatizados SET ";
        $strActualizacion_t .= "consultas = '" . $arrConsutlasHojas_p[1] . "',";
        $strActualizacion_t .= "nombres_hojas = '" . $arrConsutlasHojas_p[0] . "'";
        $strActualizacion_t .= " WHERE id = " . $itemFilaReporte_t->id;
        if ($mysqli->query($strActualizacion_t) === true) {
            
        } else {
            echo "ERROR ACT REP AUTO " . $mysqli->error;
        }
    }

}

function ctrCrearReportesDirarioAdherencia($id_estrategia, $id_huesped, $destinatarios = null, $copia = null, $copiaOculta = null, $asunto = null, $hora = null,$booExiste_p)
{

    global $mysqli;
    global $BaseDatos_general;
    global $DB_User_R;
    global $DB_Pass_R;
    global $ipReportes;
    global $BaseDatos;
    global $BaseDatos_systema;
    global $BaseDatos_telefonia;
    global $dyalogo_canales_electronicos;


    $NOMBRE_HOJAS = "";
    $CONSULTAS = "";

    $EstpasLslq = "SELECT ESTPAS_ConsInte__CAMPAN_b FROM ".$BaseDatos_systema.".ESTPAS WHERE ESTPAS_ConsInte__ESTRAT_b = " . $id_estrategia;

    $resEspaslq = $mysqli->query($EstpasLslq);

    if ($resEspaslq->num_rows > 0) {
        $numerosCampanha = '';
        $i = 0;
        while ($key = $resEspaslq->fetch_object()) {
            if ($key->ESTPAS_ConsInte__CAMPAN_b != null && !empty($key->ESTPAS_ConsInte__CAMPAN_b)) {
                if ($i == 0) {
                    $numerosCampanha .= $key->ESTPAS_ConsInte__CAMPAN_b;
                } else {
                    $numerosCampanha .= ' , ' . $key->ESTPAS_ConsInte__CAMPAN_b;
                }
                $i++;
            }
        }

        $NOMBRE_HOJAS .= '"SESIONES" , ';

        $CONSULTAS .= '"SELECT agente_nombre AS AGENTE, fecha_hora_inicio AS INICIO, fecha_hora_fin AS FIN, SUBSTRING_INDEX(SEC_TO_TIME(duracion), \'.\', 1) as DURACION_HORAS FROM dyalogo_telefonia.dy_v_historico_sesiones_por_campana WHERE campana_id IN (SELECT CAMPAN_IdCamCbx__b FROM DYALOGOCRM_SISTEMA.ESTPAS JOIN DYALOGOCRM_SISTEMA.CAMPAN ON ESTPAS_ConsInte__CAMPAN_b = CAMPAN_ConsInte__b WHERE ESTPAS_ConsInte__ESTRAT_b IN (SELECT ESTRAT_ConsInte__b FROM DYALOGOCRM_SISTEMA.ESTRAT WHERE ESTRAT_ConsInte__PROYEC_b = ' . $id_huesped . ')) AND (DATE_FORMAT(fecha_hora_inicio,\'%Y-%m-%d\') = DATE_FORMAT(CURDATE(),\'%Y-%m-%d\') ) GROUP BY sesion_id ;" , ';

        $NOMBRE_HOJAS .= '"PAUSAS" , ';

        $CONSULTAS .= '"SELECT agente_nombre AS AGENTE, DATE(fecha_hora_inicio) AS INICIO, DATE_FORMAT(fecha_hora_inicio,\'%H\') AS INICIO_HORA, DATE_FORMAT(fecha_hora_inicio,\'%i\') AS INICIO_MINUTO , DATE(fecha_hora_fin) AS FIN, DATE_FORMAT(fecha_hora_fin,\'%H\') AS FIN_HORA, DATE_FORMAT(fecha_hora_fin,\'%i\') AS FIN_MINUTO, DATE_FORMAT(SEC_TO_TIME(duracion),\'%H:%i:%s\') as DURACION_PAUSA, tipo_descanso_nombre AS TIPOPAUSA,  comentario AS COMENTARIO FROM dyalogo_telefonia.dy_v_historico_descansos_por_campana WHERE campana_id in (SELECT CAMPAN_IdCamCbx__b FROM DYALOGOCRM_SISTEMA.ESTPAS JOIN DYALOGOCRM_SISTEMA.CAMPAN ON ESTPAS_ConsInte__CAMPAN_b = CAMPAN_ConsInte__b WHERE ESTPAS_ConsInte__ESTRAT_b IN (SELECT ESTRAT_ConsInte__b FROM DYALOGOCRM_SISTEMA.ESTRAT WHERE ESTRAT_ConsInte__PROYEC_b = ' . $id_huesped . ')) AND (DATE_FORMAT(fecha_hora_inicio,\'%Y-%m-%d\') = DATE_FORMAT(CURDATE(),\'%Y-%m-%d\') ) GROUP BY descanso_id ORDER BY fecha_hora_inicio DESC;" , ';
    }

    $NOMBRE_HOJAS .= '"NO SE REGISTRARON" , ';

    $CONSULTAS .= '"select USUARI_ConsInte__b as Id, USUARI_Nombre____b as Nombre, USUARI_Correo___b as Correo, HoraInicialDefinida FROM DYALOGOCRM_SISTEMA.qryUSUARI_usuarios_agentes LEFT JOIN DYALOGOCRM_SISTEMA.qrySesionesDelDia ON IdAgente = qrySesionesDelDia.agente_id WHERE (NOT (HoraInicialDefinida is null)) AND HoraInicialDefinida < date_format(current_time, \'%H:%i:%S\') and USUARI_ConsInte__PROYEC_b = ' . $id_huesped . ' AND qrySesionesDelDia.agente_id IS NULL Order By USUARI_Nombre____b;" , ';

    $NOMBRE_HOJAS .= '"LLEGARON TARDE" , ';

    $CONSULTAS .= '"select USUARI_ConsInte__b as Id, USUARI_Nombre____b as Nombre, USUARI_Correo___b as Correo, date_format(timediff(HoraInicio, HoraInicialDefinida), \'%H:%i:%S\') as Retraso, HoraInicialDefinida, HoraInicio as HoraInicialReal FROM DYALOGOCRM_SISTEMA.qryUSUARI_usuarios_agentes JOIN DYALOGOCRM_SISTEMA.qrySesionesDelDia ON IdAgente = qrySesionesDelDia.agente_id WHERE  HoraInicio > HoraInicialDefinida AND USUARI_ConsInte__PROYEC_b = ' . $id_huesped . ' Order By USUARI_Nombre____b;" , ';

    $NOMBRE_HOJAS .= '"LLEGARON A TIEMPO" , ';

    $CONSULTAS .= '"select USUARI_ConsInte__b as Id, USUARI_Nombre____b as Nombre, USUARI_Correo___b as Correo, HoraInicialDefinida, HoraInicio as HoraInicialReal FROM DYALOGOCRM_SISTEMA.qryUSUARI_usuarios_agentes JOIN DYALOGOCRM_SISTEMA.qrySesionesDelDia ON IdAgente = qrySesionesDelDia.agente_id WHERE  HoraInicio <= HoraInicialDefinida and USUARI_ConsInte__PROYEC_b = ' . $id_huesped . ' order by USUARI_Nombre____b;" , ';

    $NOMBRE_HOJAS .= '"SE FUERON ANTES" , ';

    $CONSULTAS .= '"select USUARI_ConsInte__b as Id, USUARI_Nombre____b as Nombre, USUARI_Correo___b as Correo, date_format(timediff(HoraFinalDefinida, HoraFin), \'%H:%i:%S\') as TiempoFaltante, HoraFinalDefinida, HoraFin as HoraFinalReal FROM DYALOGOCRM_SISTEMA.qryUSUARI_usuarios_agentes JOIN DYALOGOCRM_SISTEMA.qrySesionesDelDia ON IdAgente = qrySesionesDelDia.agente_id WHERE  HoraFin < HoraFinalDefinida and USUARI_ConsInte__PROYEC_b = ' . $id_huesped . ' Order By USUARI_Nombre____b;" , ';

    $NOMBRE_HOJAS .= '"SE FUERON A TIEMPO" , ';

    $CONSULTAS .= '"select USUARI_ConsInte__b as Id, USUARI_Nombre____b as Nombre, USUARI_Correo___b as Correo, HoraFinalDefinida, HoraFin as HoraFinalReal FROM DYALOGOCRM_SISTEMA.qryUSUARI_usuarios_agentes JOIN DYALOGOCRM_SISTEMA.qrySesionesDelDia ON IdAgente = qrySesionesDelDia.agente_id WHERE  HoraFin >= HoraFinalDefinida and USUARI_ConsInte__PROYEC_b = ' . $id_huesped . ' Order By USUARI_Nombre____b;" , ';

    $NOMBRE_HOJAS .= '"SESIONES CORTAS" , ';

    $CONSULTAS .= '"select USUARI_ConsInte__b as Id, USUARI_Nombre____b as Nombre, USUARI_Correo___b as Correo, date_format(timediff(timediff(HoraFinalDefinida, HoraInicialDefinida), timediff(HoraFin, HoraInicio)), \'%H:%i:%S\') as TiempoFaltante, date_format(timediff(HoraFinalDefinida, HoraInicialDefinida), \'%H:%i:%S\') as DuracionDefinida, date_format(timediff(HoraFin, HoraInicio), \'%H:%i:%S\') as DuracionReal, HoraInicialDefinida, HoraInicio as HoraInicialReal, HoraFinalDefinida, HoraFin as HoraFinalReal FROM DYALOGOCRM_SISTEMA.qryUSUARI_usuarios_agentes JOIN DYALOGOCRM_SISTEMA.qrySesionesDelDia ON IdAgente = qrySesionesDelDia.agente_id WHERE  timediff(HoraFinalDefinida, HoraInicialDefinida) > date_format(timediff(HoraFin, HoraInicio), \'%H:%i:%S\') and USUARI_ConsInte__PROYEC_b = ' . $id_huesped . ' Order By USUARI_Nombre____b;" , ';

    $NOMBRE_HOJAS .= '"SESIONES DURACION OK" , ';

    $CONSULTAS .= '"select USUARI_ConsInte__b as Id, USUARI_Nombre____b as Nombre, USUARI_Correo___b as Correo, date_format(timediff(HoraFinalDefinida, HoraInicialDefinida), \'%H:%i:%S\') as DuracionDefinida, date_format(timediff(HoraFin, HoraInicio), \'%H:%i:%S\') as DuracionReal, HoraInicialDefinida, HoraInicio as HoraInicialReal, HoraFinalDefinida, HoraFin as HoraFinalReal FROM DYALOGOCRM_SISTEMA.qryUSUARI_usuarios_agentes JOIN DYALOGOCRM_SISTEMA.qrySesionesDelDia ON IdAgente = qrySesionesDelDia.agente_id WHERE  timediff(HoraFinalDefinida, HoraInicialDefinida) <= date_format(timediff(HoraFin, HoraInicio), \'%H:%i:%S\') and USUARI_ConsInte__PROYEC_b = ' . $id_huesped . ' Order By USUARI_Nombre____b;" , ';

    $NOMBRE_HOJAS .= '"PausasConHorarioMuyLargas" , ';

    $CONSULTAS .= '"select USUARI_ConsInte__b as Id, USUARI_Nombre____b as Nombre, USUARI_Correo___b as Correo, tipo_descanso_nombre as Pausa, timediff(timediff(fecha_hora_fin,fecha_hora_inicio), timediff(HoraFinalProgramada, HoraInicialProgramada)) as Exceso, timediff(HoraFinalProgramada, HoraInicialProgramada) DuracionProgramada, timediff(fecha_hora_fin,fecha_hora_inicio) as DuracionReal, HoraInicialProgramada, date_format(fecha_hora_inicio, \'%H:%i:%S\') as HoraInicialReal, HoraFinalProgramada, date_format(fecha_hora_fin , \'%H:%i:%S\') as HoraFinalReal FROM DYALOGOCRM_SISTEMA.qryPausasProgramadasDelDia left join dyalogo_telefonia.dy_v_historico_descansos ON IdAgente = agente_id and USUPAU_PausasId_b = tipo_descanso_id WHERE fecha_hora_inicio >= current_date() and USUPAU_Tipo_b = 1 and timediff(HoraFinalProgramada, HoraInicialProgramada) < timediff(fecha_hora_fin,fecha_hora_inicio) and USUARI_ConsInte__PROYEC_b = ' . $id_huesped . ' Order by USUARI_Nombre____b;" , ';

    $NOMBRE_HOJAS .= '"PausasConHorarioDuracionOk" , ';

    $CONSULTAS .= '"select USUARI_ConsInte__b as Id, USUARI_Nombre____b as Nombre, USUARI_Correo___b as Correo, tipo_descanso_nombre as Pausa, timediff(timediff(HoraFinalProgramada, HoraInicialProgramada), timediff(fecha_hora_fin,fecha_hora_inicio)) as TiempoAFavor, timediff(HoraFinalProgramada, HoraInicialProgramada) DuracionProgramada, timediff(fecha_hora_fin,fecha_hora_inicio) as DuracionReal, HoraInicialProgramada, date_format(fecha_hora_inicio, \'%H:%i:%S\') as HoraInicialReal, HoraFinalProgramada, date_format(fecha_hora_fin , \'%H:%i:%S\') as HoraFinalReal FROM DYALOGOCRM_SISTEMA.qryPausasProgramadasDelDia left join dyalogo_telefonia.dy_v_historico_descansos ON IdAgente = agente_id and USUPAU_PausasId_b = tipo_descanso_id WHERE fecha_hora_inicio >= current_date() and USUPAU_Tipo_b = 1 and timediff(HoraFinalProgramada, HoraInicialProgramada) >= timediff(fecha_hora_fin,fecha_hora_inicio) and USUARI_ConsInte__PROYEC_b = ' . $id_huesped . ' Order by USUARI_Nombre____b;" , ';

    $NOMBRE_HOJAS .= '"PausasConHorarioIncumplidas" , ';

    $CONSULTAS .= '"select USUARI_ConsInte__b as Id, USUARI_Nombre____b as Nombre, USUARI_Correo___b as Correo, tipo_descanso_nombre as Pausa, IF(HoraInicialProgramada > date_format(fecha_hora_inicio, \'%H:%i:%S\'), timediff(HoraInicialProgramada, date_format(fecha_hora_inicio, \'%H:%i:%S\')),null) as SalioAntesPor, IF(date_format(fecha_hora_fin , \'%H:%i:%S\') > HoraFinalProgramada, timediff(date_format(fecha_hora_fin , \'%H:%i:%S\'), HoraFinalProgramada),null) as LlegoTardePor, HoraInicialProgramada, date_format(fecha_hora_inicio, \'%H:%i:%S\') as HoraInicialReal, HoraFinalProgramada, date_format(fecha_hora_fin , \'%H:%i:%S\') as HoraFinalReal, timediff(timediff(fecha_hora_fin,fecha_hora_inicio), timediff(HoraFinalProgramada, HoraInicialProgramada)) as TiempoDiferencia, timediff(HoraFinalProgramada, HoraInicialProgramada) DuracionProgramada, timediff(fecha_hora_fin,fecha_hora_inicio) as DuracionReal FROM DYALOGOCRM_SISTEMA.qryPausasProgramadasDelDia left join dyalogo_telefonia.dy_v_historico_descansos ON IdAgente = agente_id and USUPAU_PausasId_b = tipo_descanso_id WHERE fecha_hora_inicio >= current_date() and USUPAU_Tipo_b = 1 and (HoraInicialProgramada > date_format(fecha_hora_inicio, \'%H:%i:%S\') or date_format(fecha_hora_fin , \'%H:%i:%S\') > HoraFinalProgramada) and USUARI_ConsInte__PROYEC_b = ' . $id_huesped . ' Order by USUARI_Nombre____b;" , ';

    $NOMBRE_HOJAS .= '"PausasConHorarioCumplidas" , ';

    $CONSULTAS .= '"select USUARI_ConsInte__b as Id, USUARI_Nombre____b as Nombre, USUARI_Correo___b as Correo, tipo_descanso_nombre as Pausa, IF(HoraInicialProgramada > date_format(fecha_hora_inicio, \'%H:%i:%S\'),timediff(HoraInicialProgramada, date_format(fecha_hora_inicio, \'%H:%i:%S\')),null) as SalioAntesPor, IF(date_format(fecha_hora_fin , \'%H:%i:%S\') > HoraFinalProgramada, timediff(date_format(fecha_hora_fin, \'%H:%i:%S\'), HoraFinalProgramada),null) as LlegoTardePor, HoraInicialProgramada, date_format(fecha_hora_inicio, \'%H:%i:%S\') as HoraInicialReal, HoraFinalProgramada, date_format(fecha_hora_fin , \'%H:%i:%S\') as HoraFinalReal, timediff(timediff(fecha_hora_fin,fecha_hora_inicio), timediff(HoraFinalProgramada, HoraInicialProgramada)) as TiempoDiferencia, timediff(HoraFinalProgramada, HoraInicialProgramada) DuracionProgramada, timediff(fecha_hora_fin,fecha_hora_inicio) as DuracionReal FROM DYALOGOCRM_SISTEMA.qryPausasProgramadasDelDia left join dyalogo_telefonia.dy_v_historico_descansos ON IdAgente = agente_id and USUPAU_PausasId_b = tipo_descanso_id WHERE fecha_hora_inicio >= current_date() and USUPAU_Tipo_b = 1 and (HoraInicialProgramada <= date_format(fecha_hora_inicio, \'%H:%i:%S\') and date_format(fecha_hora_fin , \'%H:%i:%S\') <= HoraFinalProgramada) and USUARI_ConsInte__PROYEC_b = ' . $id_huesped . ' Order by USUARI_Nombre____b;" , ';

    $NOMBRE_HOJAS .= '"PausasSinHorarioMuyLargas" , ';

    $CONSULTAS .= '"select USUARI_ConsInte__b as Id, USUARI_Nombre____b as Nombre, USUARI_Correo___b as Correo, tipo_descanso_nombre as Pausa, timediff(sec_to_time(sum(time_to_sec(timediff(fecha_hora_fin,fecha_hora_inicio)))), DuracionMaxima) as Exceso, DuracionMaxima, sec_to_time(sum(time_to_sec(timediff(fecha_hora_fin,fecha_hora_inicio)))) as DuracionReal, count(USUARI_ConsInte__b) as CantidadReal FROM DYALOGOCRM_SISTEMA.qryPausasProgramadasDelDia left join dyalogo_telefonia.dy_v_historico_descansos ON IdAgente = agente_id and USUPAU_PausasId_b = tipo_descanso_id WHERE fecha_hora_inicio >= current_date() and USUARI_ConsInte__PROYEC_b = ' . $id_huesped . ' and USUPAU_Tipo_b = 0 group by USUARI_ConsInte__b having time_to_sec(DuracionMaxima) < sum(time_to_sec(timediff(fecha_hora_fin,fecha_hora_inicio)))Order by USUARI_Nombre____b;" , ';

    $NOMBRE_HOJAS .= '"PausasSinHorarioMuchasVeces" , ';

    $CONSULTAS .= '"select USUARI_ConsInte__b as Id, USUARI_Nombre____b as Nombre, USUARI_Correo___b as Correo, tipo_descanso_nombre as Pausa, count(USUARI_ConsInte__b) - CantidadMaxima as VecesDeMas, count(USUARI_ConsInte__b) as CantidadReal, CantidadMaxima, sec_to_time(sum(time_to_sec(timediff(fecha_hora_fin,fecha_hora_inicio)))) as DuracionReal, DuracionMaxima FROM DYALOGOCRM_SISTEMA.qryPausasProgramadasDelDia left join dyalogo_telefonia.dy_v_historico_descansos ON IdAgente = agente_id and USUPAU_PausasId_b = tipo_descanso_id WHERE fecha_hora_inicio >= current_date() and USUPAU_Tipo_b = 0 and USUARI_ConsInte__PROYEC_b = ' . $id_huesped . ' group by USUARI_ConsInte__b having CantidadMaxima < count(USUARI_ConsInte__b) Order by USUARI_Nombre____b;" , ';

    $NOMBRE_HOJAS .= '"PausasSinHorarioOK" , ';

    $CONSULTAS .= '"select USUARI_ConsInte__b as Id, USUARI_Nombre____b as Nombre, USUARI_Correo___b as Correo, tipo_descanso_nombre as Pausa, sec_to_time(sum(time_to_sec(timediff(fecha_hora_fin,fecha_hora_inicio)))) as DuracionReal, DuracionMaxima, count(USUARI_ConsInte__b) as CantidadReal, CantidadMaxima FROM DYALOGOCRM_SISTEMA.qryPausasProgramadasDelDia left join dyalogo_telefonia.dy_v_historico_descansos ON IdAgente = agente_id and USUPAU_PausasId_b = tipo_descanso_id WHERE fecha_hora_inicio >= current_date() and USUARI_ConsInte__PROYEC_b = ' . $id_huesped . ' and USUPAU_Tipo_b = 0 group by USUARI_ConsInte__b having time_to_sec(DuracionMaxima) >= sum(time_to_sec(timediff(fecha_hora_fin,fecha_hora_inicio))) and CantidadMaxima >= count(USUARI_ConsInte__b) Order by USUARI_Nombre____b;" , ';

    $NOMBRE_HOJAS .= '"AGENTES SIN MALLA DEFINIDA"';

    $CONSULTAS .= '"select USUARI_ConsInte__b as Id, USUARI_Nombre____b as Nombre, USUARI_Correo___b as Correo FROM DYALOGOCRM_SISTEMA.qryUSUARI_usuarios_agentes WHERE USUARI_ConsInte__PROYEC_b = ' . $id_huesped . ' and HoraInicialDefinida IS NULL Order By USUARI_Nombre____b;"';

    $CONSULTASXX = $mysqli->real_escape_string($CONSULTAS);
    $NOMBRE_HOJASXXX = $mysqli->real_escape_string($NOMBRE_HOJAS);

    $Lsql_InsercionRepor = "SELECT * FROM " . $BaseDatos_general . ".reportes_automatizados WHERE id_huesped =" . $id_huesped . " AND nombres_hojas LIKE '%PausasConHorarioMuyLargas%';";
    $res = $mysqli->query($Lsql_InsercionRepor);

    //capturar id de reportes semanales
    $idReportesAdherencia = [];
    while ($fila = $res->fetch_assoc()) {
        $idReportesAdherencia[]['id'] = $fila["id"];
    }

    $LsqlEstra = "SELECT id, nombre FROM ".$BaseDatos_general.".huespedes WHERE id = " . $id_huesped . ";";

    $res_Estra = $mysqli->query($LsqlEstra);

    $datos = $res_Estra->fetch_array();

    $proyecto = str_replace(' ', '', $_SESSION['PROYECTO']);
    // $proyecto = substr($proyecto, 0, 8);
    $proyecto = sanear_strings($proyecto);

    $huesped = str_replace(' ', '', $datos['nombre']);
    // $estrategia = substr($estrategia, 0, 8);
    $huesped = sanear_strings($huesped);

    $ruta_archivo = "/tmp/" . $huesped . "_ADHERENCIA_DIARIOS";

    $ruta_archivo = quitarTildes($ruta_archivo);

    if ($res->num_rows > 0) {

        $datosDeEstoCampana = $res->fetch_array();

        if ($destinatarios != null && $asunto != null && $hora != null) {
            $Lsql = "INSERT INTO " . $BaseDatos_general . ".reportes_automatizados(bd_usuario, bd_contrasena, bd_ip, consultas, nombres_hojas, id_huesped, ruta_archivo, id_estrategia ,destinatarios , tipo_periodicidad, momento_envio, asunto, destinatarios_cc) VALUES('" . $DB_User_R . "' , '" . $DB_Pass_R . "' , '" . $ipReportes . "', '" . $CONSULTASXX . "', '" . $NOMBRE_HOJASXXX . "' , " . $_SESSION['HUESPED'] . ", '" . $ruta_archivo . "' , " . $id_estrategia . " , '" . $destinatarios . "' , 1 , '" . $hora . "' , '" . $asunto . "' , '" . $copia . "');";
        } else {

            //actualiza todos los reportes semanales
            foreach ($idReportesAdherencia as $data) {

                $Lsql = "UPDATE " . $BaseDatos_general . ".reportes_automatizados SET bd_usuario = '" . $DB_User_R . "', bd_contrasena = '" . $DB_Pass_R . "', bd_ip = '" . $ipReportes . "', consultas = '" . $CONSULTASXX . "', nombres_hojas = '" . $NOMBRE_HOJASXXX . "', id_huesped = " . $_SESSION['HUESPED'] . ", ruta_archivo = '" . $ruta_archivo . "' WHERE id = " . $data["id"];

                if ($mysqli->query($Lsql) === true) {

                    echo "adherencia diario";

                }

            }
        }

    } else {
        if ($destinatarios != null && $asunto != null && $hora != null) {
            $Lsql = "INSERT INTO " . $BaseDatos_general . ".reportes_automatizados(bd_usuario, bd_contrasena, bd_ip, consultas, nombres_hojas, id_huesped, ruta_archivo, id_estrategia ,destinatarios , tipo_periodicidad, momento_envio, asunto, destinatarios_cc) VALUES('" . $DB_User_R . "' , '" . $DB_Pass_R . "' , '" . $ipReportes . "', '" . $CONSULTASXX . "', '" . $NOMBRE_HOJASXXX . "' , " . $_SESSION['HUESPED'] . ", '" . $ruta_archivo . "' , " . $id_estrategia . " , '" . $destinatarios . "' , 1 , '" . $hora . "' , '" . $asunto . "' , '" . $copia . "');";

        } else {
            if ($asunto != "::UPDATE::") {
                $Lsql = "INSERT INTO " . $BaseDatos_general . ".reportes_automatizados(bd_usuario, bd_contrasena, bd_ip, consultas, nombres_hojas, id_huesped, ruta_archivo, id_estrategia ,destinatarios , tipo_periodicidad, momento_envio, asunto) VALUES('" . $DB_User_R . "' , '" . $DB_Pass_R . "' , '" . $ipReportes . "', '" . $CONSULTASXX . "', '" . $NOMBRE_HOJASXXX . "' , " . $_SESSION['HUESPED'] . ", '" . $ruta_archivo . "' , " . $id_estrategia . " , '" . $_SESSION['CORREO'] . "' , 1 , '20:00' , '" . $_SESSION['PROYECTO'] . "_" . sanear_strings(str_replace(' ', '_', $datos['nombre'])) . "_ADHERENCIA_DIARIOS_" . $datos['id'] . "' );";
            }
        }
    }

    if ($booExiste_p) {

        if ($mysqli->query($Lsql) === true) {

        } else {
            echo "INSERTANDO EN LOS REPORTES 2 " . $mysqli->error;
        }
        
    }


}

// BGCR - CODIGO PARA REPORTE BACKOFFICE
// Estas funciones se adicionan unicamentre para el reporte de backoffice, cuando se creee la vista para backoffice se puede quitar

function columnasReportBkPaso($intIdBd_p){

    global $mysqli;
    global $BaseDatos_systema;
    global $BaseDatos_general;

    $strJOIN_t = "";

    
    $arrColumnasDinamicas_t = columnasDinamicas($intIdBd_p);


        $strColumnasFinal_t = "G".$intIdBd_p."_ConsInte__b AS ID_BD, G".$intIdBd_p."_FechaInsercion AS FECHA_CREACION_DY, DATE_FORMAT(G".$intIdBd_p."_FechaInsercion,'%Y') AS ANHO_CREACION_DY, DATE_FORMAT(G".$intIdBd_p."_FechaInsercion,'%m') AS MES_CREACION_DY, DATE_FORMAT(G".$intIdBd_p."_FechaInsercion,'%d') AS DIA_CREACION_DY, DATE_FORMAT(G".$intIdBd_p."_FechaInsercion,'%H') AS HORA_CREACION_DY, ";

        $strColumnasDinamicas_t = "";

        foreach ($arrColumnasDinamicas_t as $value) {

            
            if ($value["tipo"] != "11") {

                $strColumnasDinamicas_t .= campoEnReporte($value["tipo"],$value["campoId"],aliasColumna($value["nombre"]));

            }else{

                $arrData_t = campoListaAuxiliar($value["campoId"],aliasColumna($value["nombre"]));

                if (count($arrData_t) > 0) {
                    
                    $strColumnasDinamicas_t .= $arrData_t[0];
                    $strJOIN_t .= $arrData_t[1];

                }


            }

        }

        $strColumnasDinamicas_t = substr($strColumnasDinamicas_t, 0, -2);

        $strColumnasFinal_t .= $strColumnasDinamicas_t;

        return [$strColumnasFinal_t,$strJOIN_t];

}

// BGCR - CODIGO PARA REPORTE BOT
// Estas funciones se adicionan unicamentre para el reporte de BOT

function columnasReportBot($intIdBd_p){

    global $mysqli;
    global $BaseDatos_systema;
    global $BaseDatos_general;

    $strJOIN_t = "";

    
    $arrColumnasDinamicas_t = columnasDinamicas($intIdBd_p);

        $strColumnasFinal_t = "G{$intIdBd_p}_ConsInte__b AS ID, G{$intIdBd_p}_CodigoMiembro AS ID_BD, G{$intIdBd_p}_FechaInsercionBD_b AS FECHA_CREACION_DY, ";

        $strColumnasDinamicas_t = "";

        foreach ($arrColumnasDinamicas_t as $value) {
            if ($value["seccion"] == "4" || $value["seccion"] == "3") {
                switch ($value["nombre_real"]) {
                    case 'Tipificacion':
                    case 'Tipificación':
                        $arrGestionControl_t["tipificacion"]=$BaseDatos_general.".fn_item_lisopc(".$value["campoId"].") AS TIPIFICACIÓN_DY, ";
                        break;
                    case 'Reintento':
                        $arrGestionControl_t["reintento"]=$BaseDatos_general.".fn_tipo_reintento_traduccion(".$value["campoId"].") AS REINTENTO_DY, ";
                        break;
                    case 'Observacion':    
                        $arrGestionControl_t["observacion"]=$value["campoId"]." AS OBSERVACION_DY, ";
                        break;    
                }
            }else{
                if ($value["tipo"] != "11") {

                    $strColumnasDinamicas_t .= campoEnReporte($value["tipo"],$value["campoId"],aliasColumna($value["nombre_real"]));
    
                }else{
    
                    $arrData_t = campoListaAuxiliar($value["campoId"],aliasColumna($value["nombre_real"]));
    
                    if (count($arrData_t) > 0) {
                        
                        $strColumnasDinamicas_t .= $arrData_t[0];
                        $strJOIN_t .= $arrData_t[1];
    
                    }
    
    
                }
            }
        }
        

        $strColumnasFinal_t .= "G{$intIdBd_p}_FechaInsercion AS FECHA_GESTION_DY, DATE_FORMAT(G{$intIdBd_p}_FechaInsercion,'%Y') AS ANHO_GESTION_DY, DATE_FORMAT(G{$intIdBd_p}_FechaInsercion,'%m') AS MES_GESTION_DY, DATE_FORMAT(G{$intIdBd_p}_FechaInsercion,'%d') AS DIA_GESTION_DY, DATE_FORMAT(G{$intIdBd_p}_FechaInsercion,'%H') AS HORA_GESTION_DY, SEC_TO_TIME(G{$intIdBd_p}_Duracion___b) AS DURACION_GESTION, G{$intIdBd_p}_Duracion___b AS DURACION_GESTION_SEG, G{$intIdBd_p}_IdLlamada AS CHAT_ENTRANTE, G{$intIdBd_p}_Sentido___b AS SENTIDO, G{$intIdBd_p}_Canal_____b AS CANAL, {$arrGestionControl_t["tipificacion"]} {$BaseDatos_general}.fn_clasificacion_traduccion(G{$intIdBd_p}_Clasificacion) AS CLASIFICACION_DY, {$arrGestionControl_t["reintento"]} {$arrGestionControl_t["observacion"]} G{$intIdBd_p}_LinkContenido AS DESCARGAGRABACION_DY, G{$intIdBd_p}_DatoContacto AS DATO_CONTACTO, G{$intIdBd_p}_Paso AS PASO_DY, G{$intIdBd_p}_Origen_b AS ORIGEN_DY, ";

        $strColumnasDinamicas_t = substr($strColumnasDinamicas_t, 0, -2);

        $strColumnasFinal_t .= $strColumnasDinamicas_t;

        return [$strColumnasFinal_t,$strJOIN_t];

}

function campoEnReporte($intTipoCampo_p,$intIdCampo_p,$strNombreCampo_p){
    
    global $BaseDatos_general;

    switch ($intTipoCampo_p) {

      case '15':

        return "SUBSTRING_INDEX(".$intIdCampo_p.",'/',-1) AS ".aliasColumna($strNombreCampo_p).", ";

        break;

      case '13':

        return $BaseDatos_general.".fn_item_lisopc(".$intIdCampo_p.") AS ".aliasColumna($strNombreCampo_p).", ";

        break;

      case '10':

        return "DATE_FORMAT(".$intIdCampo_p.",'%H:%i:%s') AS ".aliasColumna($strNombreCampo_p).", ";

        break;

      case '6':

        return $BaseDatos_general.".fn_item_lisopc(".$intIdCampo_p.") AS ".aliasColumna($strNombreCampo_p).", ";

        break;
      
      default:

        return $intIdCampo_p." AS ".aliasColumna($strNombreCampo_p).", ";

        break;

    }


}


  function campoListaAuxiliar($intIdCampo_p,$strNombre_p){


    global $mysqli;
    global $BaseDatos_systema;
    global $BaseDatos;
    global $BaseDatos_general;

    $arrData_t = [];

    $intIdCampo_t = explode("_C", $intIdCampo_p)[1];

    $strSQL_t = "SELECT SUBSTRING_INDEX(SUBSTRING_INDEX(CAMPO__Nombre____b,'G',-1),'_',1)  AS id, CAMPO__Nombre____b AS nombre FROM ".$BaseDatos_systema.".PREGUI JOIN ".$BaseDatos_systema.".CAMPO_ ON PREGUI_ConsInte__CAMPO__b = CAMPO__ConsInte__b WHERE PREGUI_ConsInte__PREGUN_b = ".$intIdCampo_t." ORDER BY PREGUI_ConsInte__b ASC LIMIT 1";   

    if ($resSQL_t = $mysqli->query($strSQL_t)) {

        if ($resSQL_t->num_rows > 0) {

            $objSQL_t = $resSQL_t->fetch_object(); 

            $arrData_t[0] =  "L_".$intIdCampo_t.".".$objSQL_t->nombre." AS ".$strNombre_p.", ";
            $arrData_t[1] =  "LEFT JOIN ".$BaseDatos.".G".$objSQL_t->id." L_".$intIdCampo_t." ON ".$intIdCampo_p." = L_".$intIdCampo_t.".G".$objSQL_t->id."_ConsInte__b ";

        }


    }

    return $arrData_t;

}


// FIN CODIGO PARA REPORTE BACKOFFICE