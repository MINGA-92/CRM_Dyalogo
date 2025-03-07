<?php

require_once ("../conexion.php");
require_once ("../../carga/Excel.php");
require_once ("../../xlsxwriter/xlsxwriter.class.php");
include "languages/spanish.php";
require_once('../../../helpers/parameters.php');
require_once(__DIR__."/../../global/WSCoreClient.php");

$middleware = (isset($_POST['middleware']) && !empty($_POST['middleware'])) ? $_POST['middleware'] : false;
if($middleware){
    require_once ("../conexionMiddleware.php");
}

$arrDataFiltros_t = [];


if (isset($_POST["jsonTotalFil"]) && $_POST["jsonTotalFil"] != "") {

    $jsonTotalFil = str_replace("\\", "", $_POST["jsonTotalFil"]);

    // echo "jsonTotalFil => $jsonTotalFil" ;
    
    $arrTotalFil = (array)json_decode($jsonTotalFil);

    $jsonCondiciones = str_replace("\\", "", $_POST["jsonCondiciones"]);
    $arrCondiciones = (array)json_decode($jsonCondiciones);

    $arrDataFiltros_t["totalFiltros"]=$arrTotalFil;
    $arrDataFiltros_t["dataFiltros"]=[];

    foreach ($arrTotalFil as $key => $value) {

        array_push($arrDataFiltros_t["dataFiltros"], ["selCampo_".$value=>$arrCondiciones["selCampo_".$value],"selOperador_".$value=>$arrCondiciones["selOperador_".$value],"valor_".$value=>$arrCondiciones["valor_".$value],"tipo_".$value=>$arrCondiciones["tipo_".$value],"selCondicion_".$value=>(isset($arrCondiciones["selCondicion_".$value]) ? $arrCondiciones["selCondicion_".$value] : null), "cierre".$value=>(isset($arrCondiciones["cierre".$value]) ? $arrCondiciones["cierre".$value] : null)]);  
    }

}else{

    if (isset($_POST["totalFiltros"]) && $_POST["totalFiltros"] > 0) {

        $arrTotalFiltros_t = $_POST["totalFiltros"];

        $arrDataFiltros_t["totalFiltros"]=$_POST["totalFiltros"];
        $arrDataFiltros_t["dataFiltros"]=[];

        foreach ($arrTotalFiltros_t as $value) {

                array_push($arrDataFiltros_t["dataFiltros"], ["selCampo_".$value=>$_POST["selCampo_".$value],"selOperador_".$value=>$_POST["selOperador_".$value],"valor_".$value=>$_POST["valor_".$value],"tipo_".$value=>$_POST["tipo_".$value],"selCondicion_".$value=>(isset($_POST["selCondicion_".$value]) ? $_POST["selCondicion_".$value] : null), "cierre".$value=>(isset($_POST["cierre".$value]) ? $_POST["cierre".$value] : null)]);    

        }


    }
    
}


$tipoReport_t = (isset($_POST["tipoReport_t"]) ? $_POST["tipoReport_t"] : 0);
$strFechaIn_t = (isset($_POST["strFechaIn_t"]) ? $_POST["strFechaIn_t"] : date('Y-m-d'));
$strFechaFn_t = (isset($_POST["strFechaFn_t"]) ? $_POST["strFechaFn_t"] : date('Y-m-d'));
$intIdPeriodo_t = (isset($_POST["intIdPeriodo_t"]) ? $_POST["intIdPeriodo_t"] : 0);
$strLimit_t = (isset($_POST["strLimit_t"]) ? $_POST["strLimit_t"] : "no");
$intIdHuesped_t = (isset($_POST["intIdHuesped_t"]) ? $_POST["intIdHuesped_t"] : 0);
$intIdEstrat_t = isset($_POST["intIdEstrat_t"]) ? $_POST["intIdEstrat_t"] : 0;
if(!is_numeric($intIdEstrat_t)){
    $sqlIdEstrategia=$mysqli->query("select ESTRAT_ConsInte__b as id from {$BaseDatos_systema}.ESTRAT where md5(concat('".clave_get."', ESTRAT_ConsInte__b)) = '".$_POST["intIdEstrat_t"]."'");
    if($sqlIdEstrategia && $sqlIdEstrategia->num_rows==1){
        $sqlIdEstrategia=$sqlIdEstrategia->fetch_object();
        $intIdEstrat_t = $sqlIdEstrategia->id;
    }
}
$intIdCBX_t = (isset($_POST["intIdCBX_t"]) ? $_POST["intIdCBX_t"] : 0);
$id_agente = isset($_POST["id_agente_telefonia"]) ? $_POST["id_agente_telefonia"] : NULL;
$id_usuari = isset($_POST["id_usuari"]) ? $_POST["id_usuari"] : NULL;
$intIdBd_t = (isset($_POST["intIdBd_t"]) ? $_POST["intIdBd_t"] : 0);
$intIdPaso_t = (isset($_POST["intIdPaso_t"]) ? $_POST["intIdPaso_t"] : 0);
$intIdTipo_t = (isset($_POST["intIdTipo_t"]) ? $_POST["intIdTipo_t"] : 0);
$intIdMuestra_t = (isset($_POST["intIdMuestra_t"]) ? $_POST["intIdMuestra_t"] : -1);
$intIdGuion_t = (isset($_POST["intIdGuion_t"]) ? $_POST["intIdGuion_t"] : 0);
$intFilas_t = (isset($_POST["intFilas_t"]) ? $_POST["intFilas_t"] : 0);
$intLimite_t = (isset($_POST["intLimite_t"]) ? $_POST["intLimite_t"] : 10);
$intPaginaActual_t = (isset($_POST["intPaginaActual_t"]) ? $_POST["intPaginaActual_t"] : 1);
$strTablaTemp_t = (isset($_POST["strTablaTemp_t"]) ? $_POST["strTablaTemp_t"] : null);
$strCampoUnion_t = (isset($_POST["strCampoUnion_t"]) ? $_POST["strCampoUnion_t"] : null);
$strNombreExcell = (isset($_POST["NombreExcell"]) ? $_POST["NombreExcell"] : "");

if (isset($_POST["Exportar"])) {

    $arrDimensiones_t = dimensiones($tipoReport_t,$strFechaIn_t,$strFechaFn_t,$intIdPeriodo_t,0,$intIdHuesped_t,$intIdEstrat_t,$intIdCBX_t,$intIdBd_t,$intIdPaso_t,$intIdTipo_t,$intIdMuestra_t,$intIdGuion_t,0,0,"exportar",$arrDataFiltros_t);


    // Se valida si el informe se requiere desde el servidor de middleware
    if($middleware){
        $resSQLReporte = $mysqliMiddleware->query($arrDimensiones_t[3]." LIMIT 45000");
    }else{
        $resSQLReporte = $mysqli->query($arrDimensiones_t[3]." LIMIT 45000");
    }

    
    $strNombreHoja_t = $arrDimensiones_t[4];

    $arrColumnas_t = $resSQLReporte->fetch_fields();

    $arrStyles = ['font'=>'Arial','font-size'=>10,'fill'=>'#20BEE8','color' => '#FFFF','halign'=>'center'];

    foreach ($arrColumnas_t as $name) {
        $arrHeader_t[$name->name] = "string"; 
    }

    $writer = new XLSXWriter();
    $writer->writeSheetHeader($strNombreHoja_t, $arrHeader_t, $arrStyles);

    while ($dato = $resSQLReporte->fetch_object()) {
        $writer->writeSheetRow($strNombreHoja_t,(array)$dato);
    }

    $strSQLNombreEstrat_t = "SELECT ESTRAT_Nombre____b AS nombre FROM ".$BaseDatos_systema.".ESTRAT WHERE ESTRAT_ConsInte__b = ".$intIdEstrat_t;
    $resSQLNombreEstrat_t = $mysqli->query($strSQLNombreEstrat_t);
    $strFechaActual_t = date("Y-m-d H:i:s");

    if ($resSQLNombreEstrat_t->num_rows > 0) {
        $objSQLNombreEstrat_t = $resSQLNombreEstrat_t->fetch_object();
        $strFileName_t = sanear_strings($strNombreHoja_t."_".$objSQLNombreEstrat_t->nombre).$strFechaActual_t;
    }else{
        $strFileName_t = "RESUMEN_CARGUE_DE_BASE_".$strFechaActual_t;
    }

    //JDBD generamos el escell
    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    header('Content-Disposition: attachment;filename="'.$strFileName_t.'.xlsx"');
    header('Cache-Control: max-age=0');

    $writer->writeToStdOut();
    exit(0);


}else if(isset($_GET["ValidarExportar"])){
    // Validamos si la consulta de exportar esta correctamente realizada
    $estado = "ok";

    try {

        $arrDimensiones_t = dimensiones($tipoReport_t,$strFechaIn_t,$strFechaFn_t,$intIdPeriodo_t,0,$intIdHuesped_t,$intIdEstrat_t,$intIdCBX_t,$intIdBd_t,$intIdPaso_t,$intIdTipo_t,$intIdMuestra_t,$intIdGuion_t,0,0,"exportar",$arrDataFiltros_t);

        if(isset($arrDimensiones_t[5]) && $arrDimensiones_t[5] == "fallo"){
            if($arrDimensiones_t[6]){
                $mensaje = $arrDimensiones_t[6];
            }else{
                $mensaje = "Error al generar el reporte";
            }

            $estado = "fallo";
        }
    } catch (\Throwable $th) {
        $mensaje = "error del sistema al tratar de generar el reporte";
        $estado = "fallo";
    }

    echo json_encode(["estado" => $estado, "mensaje" => $mensaje]);

}else if (isset($_GET["Pivot"])) {

  $arrDimensiones_t = dimensiones($tipoReport_t,$strFechaIn_t,$strFechaFn_t,$intIdPeriodo_t,0,$intIdHuesped_t,$intIdEstrat_t,$intIdCBX_t,$intIdBd_t,$intIdPaso_t,$intIdTipo_t,$intIdMuestra_t,$intIdGuion_t,0,0,"pivot",$arrDataFiltros_t);

  $arrDatos_t = [];

  $resSQLReporte = $mysqli->query($arrDimensiones_t[3]." LIMIT 45000");

  if ($resSQLReporte) {
      if ($resSQLReporte->num_rows > 0) {
          while ($row = $resSQLReporte->fetch_assoc()) {
              $arrDatos_t[] = $row; 
          }
      }
  }

  echo json_encode($arrDatos_t);

}else if (isset($_GET["Paginado"])) {

    $intFilas_t = $_POST["intFilas_t"];
    $intLimit_t = 15;
    $intRegistrosTotal_t = $_POST["intRegistrosTotal_t"];
    $intCantidadPaginas_t = $_POST["intCantidadPaginas_t"];
    $intLimite_t = $_POST["intLimite_t"];

    $arrDimensiones_t = [$intRegistrosTotal_t,$intCantidadPaginas_t,$_POST["consulta"]." LIMIT ".$_POST["intFilas_t"].",".$intLimite_t,$_POST["consulta"]];

    $arrDimensiones_t[2] = str_replace("\\", "", $arrDimensiones_t[2]);
    $arrDimensiones_t[3] = str_replace("\\", "", $arrDimensiones_t[3]);

    $resSQLReporte_t = $mysqli->query($arrDimensiones_t[2]);

    $arrDatos_t = [];

    if ($resSQLReporte_t) {
        if ($resSQLReporte_t->num_rows > 0) {
            while ($row = $resSQLReporte_t->fetch_assoc()) {
                $arrDatos_t[] = $row; 
            }
        }
    }

    $strJsonDatos_t = json_encode($arrDatos_t);

    if (count($arrDatos_t) > 0) {

        $arrDatosHead_t = array_keys($arrDatos_t[0]);

        // SE ESCOJE LA VISTA PARA LOS REPORTES ESPECIALES

        switch ($tipoReport_t) {
            case 'detalladoLlamadas':
                include "reportsViews/gridDetalladoLlamadas.php";
                break;

            case 'historicoIVRResum':
                include "reportsViews/gridHistoricoIVRResum.php";
                break;

            case 'encuestasIVRResumenPregun':
            case 'encuestasIVRResumenAgente':
                include "reportsViews/gridEncuestasIVRResumenAgente.php";
                break;

            case 'erlang':
                include "reportsViews/gridErlang.php";
                break;

            case 'ordenamiento':
                include "reportsViews/gridOrdenamiento.php";
                break;

            default:
                include "tables/grid.php";
                break;
        }

    }else{
        echo 'No hay datos para mostrar!';
    }

}else if (isset($_GET["Reporte"])){
    
    $arrDimensiones_t = dimensiones($tipoReport_t,$strFechaIn_t,$strFechaFn_t,$intIdPeriodo_t,$strLimit_t,$intIdHuesped_t,$intIdEstrat_t,$intIdCBX_t,$intIdBd_t,$intIdPaso_t,$intIdTipo_t,$intIdMuestra_t,$intIdGuion_t,$intFilas_t,$intLimite_t,null,$arrDataFiltros_t);
    $arrDatos_t = [];
    
    
    // Se valida si el informe se requiere desde el servidor de middleware
    if($middleware){
        $resSQLReporte = $mysqliMiddleware->query($arrDimensiones_t[3]);
    }else{
        $resSQLReporte = $mysqli->query($arrDimensiones_t[2]);
    }

    if ($resSQLReporte) {
        if ($resSQLReporte->num_rows > 0) {
            while ($row = $resSQLReporte->fetch_assoc()) {
                $arrDatos_t[] = $row; 
            }
        }
    }else{
        // $mysqli->error;
        echo "Error: no se pudo obtener el resultado, por favor verifica que las condiciones esten definidas correctamente y antes de generar de nuevo el reporte guarda la configuracion <br />";
    }

    $strJsonDatos_t = json_encode($arrDatos_t);

    if (count($arrDatos_t) > 0) {

        $arrDatosHead_t = array_keys($arrDatos_t[0]);
        $arrayDatosTitle = arrayTitle();
        // SE ESCOJE LA VISTA PARA LOS REPORTES ESPECIALES
        
        switch ($tipoReport_t) {
            case 'detalladoLlamadas':
                include "reportsViews/gridDetalladoLlamadas.php";
                break;

            case 'historicoIVRResum':
                include "reportsViews/gridHistoricoIVRResum.php";
                break;

            case 'encuestasIVRResumenPregun':
            case 'encuestasIVRResumenAgente':
                include "reportsViews/gridEncuestasIVRResumenAgente.php";
                break;

            case 'erlang':
                include "reportsViews/gridErlang.php";
                break;

            case 'ordenamiento':
                include "reportsViews/gridOrdenamiento.php";
                break;
    
            default:
                include "tables/grid.php";
                break;
        }


    }else{
        echo 'No hay datos para mostrar!';
    }

    echo ('<input type="hidden" name="consultaReporte" value="'.$arrDimensiones_t[2].'">');

}else if (isset($_POST["ExportarCargue"])) {

    $arrDimensiones_t = dimensiones($tipoReport_t,$strFechaIn_t,$strFechaFn_t,$intIdPeriodo_t,0,$intIdHuesped_t,$intIdEstrat_t,$intIdCBX_t,$intIdBd_t,$intIdPaso_t,$intIdTipo_t,$intIdMuestra_t,$intIdGuion_t,0,0,"cargue",$arrDataFiltros_t);

    $strSQLReporte_t = $arrDimensiones_t[0];

    $strNombreHoja_t = $arrDimensiones_t[1];
    $arrDatos_t = [];
    $resSQLReporte = $mysqli->query($strSQLReporte_t);

    $arrColumnas_t = $resSQLReporte->fetch_fields();

    $arrStyles = ['font'=>'Arial','font-size'=>10,'fill'=>'#20BEE8','color' => '#FFFF','halign'=>'center'];

    foreach ($arrColumnas_t as $name) {
        $arrHeader_t[$name->name] = "string"; 
    }

    $writer = new XLSXWriter();
    $writer->writeSheetHeader($strNombreHoja_t, $arrHeader_t, $arrStyles);

    while ($dato = $resSQLReporte->fetch_object()) {
        $writer->writeSheetRow($strNombreHoja_t,(array)$dato);
    }

    $strSQLNombreEstrat_t = "SELECT ESTRAT_Nombre____b AS nombre FROM ".$BaseDatos_systema.".ESTRAT WHERE ESTRAT_ConsInte__b = ".$intIdEstrat_t;
    $resSQLNombreEstrat_t = $mysqli->query($strSQLNombreEstrat_t);
    $strFechaActual_t = date("Y-m-d H:i:s");

    if ($resSQLNombreEstrat_t->num_rows > 0) {
        $objSQLNombreEstrat_t = $resSQLNombreEstrat_t->fetch_object();
        $strFileName_t = sanear_strings($objSQLNombreEstrat_t->nombre).$strFechaActual_t;
    }else{
        $strFileName_t = "RESUMEN_CARGUE_DE_BASE_".$strFechaActual_t;
    }

    //JDBD generamos el escell
    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    header('Content-Disposition: attachment;filename="'.$strNombreExcell.'_'.$strFileName_t.'.xlsx"');
    header('Cache-Control: max-age=0');

    $writer->writeToStdOut();
    exit(0);

}else if (isset($_GET['reporteAgente'])){

    $arrDimensiones_t = obtenerReporteAgente($tipoReport_t, $strFechaIn_t, $strFechaFn_t, $intIdPeriodo_t, $strLimit_t, $intIdHuesped_t, $intIdEstrat_t, $intIdCBX_t, $id_agente, $id_usuari, $intIdBd_t, $intIdPaso_t, $intIdTipo_t, $intIdMuestra_t, $intIdGuion_t, $intFilas_t, $intLimite_t, null, $arrDataFiltros_t);
    $arrDatos_t = [];

    // echo "<br> consulta =>", json_encode($arrDimensiones_t), "<br>";
    // exit();

    $resSQLReporte = $mysqli->query($arrDimensiones_t[2]);

    if ($resSQLReporte) {
        if ($resSQLReporte->num_rows > 0) {
            while ($row = $resSQLReporte->fetch_assoc()) {
                $arrDatos_t[] = $row;
            }
        }
    }

    $strJsonDatos_t = json_encode($arrDatos_t);

    if (count($arrDatos_t) > 0) {
        $arrDatosHead_t = array_keys($arrDatos_t[0]);
        include "tables/grid.php";
    } else {
        echo 'No hay datos para mostrar!';
    }

}else if(isset($_POST['exportAgente'])) {
    
    $arrDimensiones_t = obtenerReporteAgente($tipoReport_t, $strFechaIn_t, $strFechaFn_t, $intIdPeriodo_t, 0, $intIdHuesped_t, $intIdEstrat_t, $intIdCBX_t, $id_agente, $id_usuari, $intIdBd_t, $intIdPaso_t, $intIdTipo_t, $intIdMuestra_t, $intIdGuion_t, 0, 0, "exportar", $arrDataFiltros_t);

    // echo " <br><br> arrDimensiones_t =>", json_encode($arrDimensiones_t[3]), "<br><br>";

    $resSQLReporte = $mysqli->query($arrDimensiones_t[3] . " LIMIT 45000");

    $strNombreHoja_t = $arrDimensiones_t[4];

    $arrColumnas_t = $resSQLReporte->fetch_fields();

    $arrStyles = ['font' => 'Arial', 'font-size' => 10, 'fill' => '#20BEE8', 'color' => '#FFFF', 'halign' => 'center'];

    foreach ($arrColumnas_t as $name) {
        $arrHeader_t[$name->name] = "string";
    }

    $writer = new XLSXWriter();
    $writer->writeSheetHeader($strNombreHoja_t, $arrHeader_t, $arrStyles);

    while ($dato = $resSQLReporte->fetch_object()) {
        $writer->writeSheetRow($strNombreHoja_t, (array)$dato);
    }

    // $strSQLNombreEstrat_t = "SELECT ESTRAT_Nombre____b AS nombre FROM " . $BaseDatos_systema . ".ESTRAT WHERE ESTRAT_ConsInte__b = " . $intIdEstrat_t;
    // $resSQLNombreEstrat_t = $mysqli->query($strSQLNombreEstrat_t);
    $strFechaActual_t = date("Y-m-d H:i:s");

    // if ($resSQLNombreEstrat_t->num_rows > 0) {
        // $objSQLNombreEstrat_t = $resSQLNombreEstrat_t->fetch_object();
        $strFileName_t = sanear_strings("{$strNombreHoja_t}_REPORTE") . $strFechaActual_t;
    // } else {
    //     $strFileName_t = "RESUMEN_CARGUE_DE_BASE_" . $strFechaActual_t;
    // }

    //JDBD generamos el escell
    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    header('Content-Disposition: attachment;filename="' . $strFileName_t . '.xlsx"');
    header('Cache-Control: max-age=0');

    $writer->writeToStdOut();
    exit(0);
    
} else {

    // Esta parte de codigo se utiliza para crear el resumen de los registros cargados a la BD por medio del cargador por excel

    $arrDimensiones_t = dimensiones($tipoReport_t,$strFechaIn_t,$strFechaFn_t,$intIdPeriodo_t,0,$intIdHuesped_t,$intIdEstrat_t,$intIdCBX_t, $intIdBd_t,$intIdPaso_t,$intIdTipo_t,$intIdMuestra_t,$intIdGuion_t,0,0,"cargue",$arrDataFiltros_t);

    $strSQLReporte_t = $arrDimensiones_t[0];

    $strNombreHoja_t = $arrDimensiones_t[1];

    $arrDatos_t = [];
    $resSQLReporte = $mysqli->query($strSQLReporte_t);

    $arrDatos_t = [];

    if ($resSQLReporte) {
        if ($resSQLReporte->num_rows > 0) {
            while ($row = $resSQLReporte->fetch_assoc()) {
                $arrDatos_t[] = $row; 
            }
        }
    }

    $strJsonDatos_t = json_encode($arrDatos_t);

    if (count($arrDatos_t) > 0) {

        $arrDatosHead_t = array_keys($arrDatos_t[0]);

        if ($tipoReport_t == "cargue") {

            $resSQLErroresCargue_t = $mysqli->query("SELECT RESULTADO AS Errores, ".$strCampoUnion_t." AS CampoUnion FROM dy_cargue_datos.".$strTablaTemp_t." WHERE (ESTADO = 2 OR ESTADO = 3)");

            $arrDatosErrores_t = [];
            $arrHeadErrores_t = [];

            if ($resSQLErroresCargue_t && $resSQLErroresCargue_t->num_rows > 0) {

                while ($row2 = $resSQLErroresCargue_t->fetch_assoc()) {
                    $arrDatosErrores_t[] = $row2;
                }

                $arrHeadErrores_t = array_keys($arrDatosErrores_t[0]);
                

            }

            include "../../carga/tablaCargue.php";

        }else{

            include "tables/grid.php";
            
        }


    }else{
        echo 'No hay datos para mostrar!';
    }
}


/**
 *JDBD - En esta funcion retornaremos el nombre quitando todos los caracteres especiales y los acentos.
 *@param string.
 *@return string. 
 */

function sanear_strings($string)
{

    // $string = utf8_decode($string);

    $string = str_replace(array('á', 'à', 'ä', 'â', 'ª', 'Á', 'À', 'Â', 'Ä'), array('a', 'a', 'a', 'a', 'a', 'A', 'A', 'A', 'A'), $string);
    $string = str_replace(array('é', 'è', 'ë', 'ê', 'É', 'È', 'Ê', 'Ë'), array('e', 'e', 'e', 'e', 'E', 'E', 'E', 'E'), $string);
    $string = str_replace(array('í', 'ì', 'ï', 'î', 'Í', 'Ì', 'Ï', 'Î'), array('i', 'i', 'i', 'i', 'I', 'I', 'I', 'I'), $string);
    $string = str_replace(array('ó', 'ò', 'ö', 'ô', 'Ó', 'Ò', 'Ö', 'Ô'), array('o', 'o', 'o', 'o', 'O', 'O', 'O', 'O'), $string);
    $string = str_replace(array('ú', 'ù', 'ü', 'û', 'Ú', 'Ù', 'Û', 'Ü'), array('u', 'u', 'u', 'u', 'U', 'U', 'U', 'U'), $string);
    $string = str_replace(array('ñ', 'Ñ', 'ç', 'Ç'), array('n', 'N', 'c', 'C'), $string);
    //Esta parte se encarga de eliminar cualquier caracter extraño
    $string = str_replace(array("\\", "¨", "º", "-", "~", "#", "@", "|", "!", "\"", "·", "$", "%", "&", "/", "(", ")", "?", "'", "¡", "¿", "[", "^", "`", "]", "+", "}", "{", "¨", "´", ">“, “< ", ";", ",", ":", "."), '', $string);
    return $string;

}



/**
 *JDBD - En esta funcion retornaremos el campo con el formato segun el tipode numero.
 *@param string.
 *@return string. 
 */

function depurarACD($strNombre_p){

    $strNombre_t = $strNombre_p;

    if ($strNombre_p == "Cont_porcentaje" || $strNombre_p == "TSF" || $strNombre_p == "TSF_Cont_antes_tsf" || $strNombre_p == "TSF_Cont_despues_tsf" || $strNombre_p == "Aban_porcentaje" || $strNombre_p == "Aban_umbral_tsf") {

        $strNombre_t = "ROUND(".$strNombre_p.",2) AS ".$strNombre_p;

    }else if ($strNombre_p == "AHT" || $strNombre_p == "THT" || $strNombre_p == "Aban_espera" || $strNombre_p == "Aban_espera_total" || $strNombre_p == "Aban_espera_min" || $strNombre_p == "Aban_espera_max") {

        $strNombre_t = "SUBSTR(".$strNombre_p.",1,8) AS ".$strNombre_p;

    }

    return $strNombre_t;

}


/**
 *BGCR - Esta funcion devuelve el id de proyecto de la tabla de telefonia (A veces llega a ser diferente)
 *@param int. id Huesped
 *@return int. id Proyecto
 */

 function getProyectoId(int $intIdHuesped_p): int{
    global $mysqli;
    global $BaseDatos_telefonia;

    $idProyecto = $intIdHuesped_p;

    $sqlIdProyecto = $mysqli->query("SELECT id FROM {$BaseDatos_telefonia}.dy_proyectos WHERE id_huesped = {$intIdHuesped_p}");
    if($sqlIdProyecto && $sqlIdProyecto->num_rows > 0){
        $idProyecto = $sqlIdProyecto->fetch_object()->id;
    }

    return $idProyecto;
 }
/**
 *JDBD - En esta funcion retornaremos el nombre de la vista segun su ID de base.
 *BGCR - Si pasamos el id de la muestra retornara el nombre de la vista de la muestra
 *@param int.
 *@return string. 
 */

function nombreVistas($intIdBd_p, $intIdMuestra_p = null){

    global $mysqli;
    global $BaseDatos;
    global $BaseDatos_systema;
    global $BaseDatos_telefonia;
    global $dyalogo_canales_electronicos;
    global $BaseDatos_general;


    if($intIdMuestra_p !== null){
    //BGCR - Buscamos el estrat del paso

        $strSQLEstrat_t = "SELECT ESTPAS_ConsInte__ESTRAT_b FROM {$BaseDatos_systema}.CAMPAN JOIN {$BaseDatos_systema}.ESTPAS ON CAMPAN_ConsInte__b = ESTPAS_ConsInte__CAMPAN_b WHERE CAMPAN_ConsInte__GUION__Pob_b = {$intIdBd_p} AND CAMPAN_ConsInte__MUESTR_b = {$intIdMuestra_p} LIMIT 1"; 
        
        $resSQLEstrat_t = $mysqli->query($strSQLEstrat_t);


        // Validamos si obtuvimos algo con la campaña en caso de no obtener nada validamos el paso por la muestra
        if($resSQLEstrat_t->num_rows > 0){

            $valSQLEstrat_t = $resSQLEstrat_t->fetch_object()->ESTPAS_ConsInte__ESTRAT_b;
            
        }else{

            $strSQLEstrat_t = "SELECT ESTPAS_ConsInte__ESTRAT_b FROM {$BaseDatos_systema}.ESTPAS WHERE ESTPAS_ConsInte__MUESTR_b = {$intIdMuestra_p} LIMIT 1"; 

            $resSQLEstrat_t = $mysqli->query($strSQLEstrat_t);
            $valSQLEstrat_t = $resSQLEstrat_t->fetch_object()->ESTPAS_ConsInte__ESTRAT_b;


        }

        $strSQLVista_t = "SELECT nombre FROM {$BaseDatos_general}.vistas_generadas where id_estrat = {$valSQLEstrat_t} and nombre like '%{$intIdMuestra_p}%' LIMIT 1"; 

        $resSQLVista_t = $mysqli->query($strSQLVista_t);
    
        return $resSQLVista_t->fetch_object()->nombre;

    }else{
        //JDBD - Buscamos el nombre de la vista MYSQL.
        $strSQLVista_t = "SELECT nombre FROM ".$BaseDatos_general.".vistas_generadas WHERE id_guion = ".$intIdBd_p." ORDER BY id DESC LIMIT 1"; 

        $resSQLVista_t = $mysqli->query($strSQLVista_t);

        return $resSQLVista_t->fetch_object()->nombre;
    }

}


/**
 *JDBD - Esta funcion calcula la cantidad de registros y paginas del reporte solicitado.
 *@param .....
 *@return array. 
 */

function filasPaginas($intIdBd_p,$strCondicion_p,$intLimite_p,$tipoReport_p="default",$intIdMuestra_p=null,$intIdGuion_p=null){

    global $BaseDatos;
    global $BaseDatos_telefonia;
    global $dyalogo_canales_electronicos;
    global $mysqli;

    //JDBD - Consultamos las cantidades de registros y las paginas que resultarian para mostrar como reporte.

    switch ($tipoReport_p) {

      case '2':

        $strSQL_t = $intIdBd_p;

        break;

      case '1':

        $strSQL_t = "SELECT COUNT(1) AS cantidad FROM {$BaseDatos_telefonia}.dy_v_historico_sesiones WHERE {$strCondicion_p} ";

        break;

    case 'customQuery':

        $strSQL_t = $intIdBd_p;

        break;

      case 'bdpaso':

        $strSQL_t = "SELECT COUNT(1) AS cantidad FROM ".$BaseDatos.".".$intIdGuion_p." WHERE ".$strCondicion_p;

        break;

    case 'bkpaso':

        $strSQL_t = "SELECT COUNT(1) AS cantidad FROM ".$BaseDatos.".G".$intIdBd_p." INNER JOIN ".$BaseDatos.".G".$intIdBd_p."_M".$intIdMuestra_p." ON  G".$intIdBd_p."_ConsInte__b = G".$intIdBd_p."_M".$intIdMuestra_p."_CoInMiPo__b  WHERE ".$strCondicion_p;

        break;

      case 'gspaso':

        $strSQL_t = "SELECT COUNT(1) AS cantidad FROM ".$BaseDatos.".".$intIdGuion_p." WHERE ".$strCondicion_p;

        break;

      case 'acd':

        $strSQL_t = "SELECT COUNT(1) AS cantidad FROM ".$BaseDatos.".".$intIdBd_p." WHERE ".$strCondicion_p;

        break;

    case 'acdChat':

        $strSQL_t = "SELECT COUNT(1) AS cantidad FROM ".$dyalogo_canales_electronicos.".".$intIdBd_p." WHERE ".$strCondicion_p;

        break;
 
      case 'gsbot':

        $strSQL_t = "SELECT COUNT(1) AS cantidad FROM ".$BaseDatos.".G".$intIdBd_p ." WHERE {$strCondicion_p}";

        break;

      case 'opcionesUsadasBot':
        // Me toca hacer un count de otro select porque me toca traer la cantidad de registros en totoal 
        $strSQL_t = "SELECT count(1) AS cantidad FROM (SELECT * FROM dyalogo_canales_electronicos.dy_chat_opciones_usadas WHERE id_bot = {$intIdBd_p} AND {$strCondicion_p} GROUP BY id_seccion, id_respuesta_bot) AS b";
        break;

    case 'marcador':

        $strSQL_t = "SELECT COUNT(1) AS cantidad FROM ".$BaseDatos_telefonia.".dy_marcador_log WHERE {$strCondicion_p}";
       
        break;

    case 'comMail':

        $strSQL_t = "SELECT COUNT(1) AS cantidad FROM ".$dyalogo_canales_electronicos.".DY_V_REPORTE_CE_ENTRANTES E WHERE {$strCondicion_p}";
        break;

    case 'comSms':

        $strSQL_t = "SELECT COUNT(1) AS cantidad FROM dy_sms.DY_REPORT_SMS_ENTRANTES WHERE {$strCondicion_p}";
        break;
    
    case 'comChat':

        $strSQL_t = "SELECT COUNT(1) AS cantidad FROM ".$dyalogo_canales_electronicos.".DY_V_REPORTE_CHAT_ENTRANTES WHERE {$strCondicion_p}";
        break;
        
    case 'gsComMail':

        $strSQL_t = "SELECT COUNT(1) AS cantidad FROM ".$dyalogo_canales_electronicos.".DY_V_REPORTE_CE_SALIENTES WHERE {$strCondicion_p}";
        break;

    case 'gsComSMS':

        $strSQL_t = "SELECT COUNT(1) AS cantidad FROM dy_sms.DY_REPORT_SMS_SALIENTES WHERE {$strCondicion_p}";
        break;

    case 'gsComWhatsapp':

        $strSQL_t = "SELECT COUNT(1) AS cantidad FROM dy_whatsapp.DY_V_REPORTE_PLANTILLAS_SALIENTES WHERE {$strCondicion_p}";
        break;

    case 'detalladoLlamadas':

        $strSQL_t = "SELECT COUNT(1) AS cantidad FROM ".$BaseDatos_telefonia.".dy_v_historico_llamadas WHERE {$strCondicion_p}";
        break;

    case 'historicoIVRResum':

        $strSQL_t = "SELECT COUNT(1) AS cantidad FROM (SELECT id_ivr FROM ".$BaseDatos_telefonia.".v_log_ivrs_opciones WHERE {$strCondicion_p} GROUP BY id_ivr) conteoIVR";
        break;
    
    case 'historicoIVRDetallado':

        $strSQL_t = "SELECT COUNT(1) AS cantidad FROM ".$BaseDatos_telefonia.".v_log_ivrs_opciones WHERE {$strCondicion_p}";
        break;

    case 'encuestasIVRResumenAgente':

        $strSQL_t = "SELECT COUNT(1) AS cantidad FROM (SELECT r.id FROM ".$BaseDatos_telefonia.".dy_encuestas_resultados r JOIN ".$BaseDatos_telefonia.".dy_encuestas e ON e.id = r.id_encuesta JOIN ".$BaseDatos_telefonia.".dy_encuestas_preguntas p ON p.id = r.id_pregunta  WHERE {$strCondicion_p} GROUP BY r.nombre_agente) porAgente";
        break;


    case 'encuestasIVRResumenPregun':

        $strSQL_t = "SELECT COUNT(1) AS cantidad FROM (SELECT r.id FROM ".$BaseDatos_telefonia.".dy_encuestas_resultados r JOIN ".$BaseDatos_telefonia.".dy_encuestas e ON e.id = r.id_encuesta JOIN ".$BaseDatos_telefonia.".dy_encuestas_preguntas p ON p.id = r.id_pregunta  WHERE {$strCondicion_p} GROUP BY p.nombre) porPregunta";
        break;
    
    case 'encuestasIVRdetallado':

        $strSQL_t = "SELECT COUNT(1) AS cantidad FROM ".$BaseDatos_telefonia.".dy_encuestas_resultados r JOIN {$BaseDatos_telefonia}.dy_encuestas e ON e.id = r.id_encuesta JOIN {$BaseDatos_telefonia}.dy_encuestas_preguntas p ON p.id = r.id_pregunta LEFT JOIN {$BaseDatos_telefonia}.dy_campanas c ON r.campana = c.nombre_interno WHERE {$strCondicion_p} ";
        break;

    case 'historicoUltOpcIVRDetallado':

        $strSQL_t = "SELECT COUNT(1) AS cantidad FROM ".$BaseDatos_telefonia.".dy_llamadas_espejo WHERE {$strCondicion_p} ";
        break;

    case 'ordenamiento':

        // Para el ordenamiento me toca pasarle la consulta completa ya que es un union
        $strSQL_t = "SELECT COUNT(1) AS cantidad FROM ({$strCondicion_p} LIMIT 500) conteo";
        break;

    case 'tareasProgramadas':

        $strSQL_t = str_replace("SELECT * FROM ", "SELECT count(1) AS cantidad FROM ", $strCondicion_p);
        break;

      default:
      
        $strSQL_t = "SELECT COUNT(1) AS cantidad FROM ".$BaseDatos.".G".$intIdBd_p." WHERE ".$strCondicion_p;
        break;
    }
    
    // Validamos si la conexion es por middleware o por principal
    if($intIdBd_p == "Middleware"){
        global $mysqliMiddleware;
        $resCant = $mysqliMiddleware->query($strSQL_t);
    }else{
        $resCant = $mysqli->query($strSQL_t);
    }

    $intCantidadFilas_t =0;

    // se verifica que la consulta sea correcta
    if($resCant){
        $intCantidadFilas_t = $resCant->fetch_object()->cantidad;
    }

    $intCantidadPaginas = 1;
    if(isset($intLimite_p) && $intLimite_p != 0){
        $intCantidadPaginas = ceil($intCantidadFilas_t/$intLimite_p);
    }
    return [$intCantidadFilas_t,$intCantidadPaginas];

}

/**
 *JDBD - En esta funcion armamos las condiciones de los filtros que recibimos.
 *@param .....
 *@return string. 
 */

function aliasColumna($strNombre_p){

  $strNombreCorregido_p = preg_replace("/\s+/", "_", $strNombre_p);

  $strNombreCorregido_p = preg_replace('([^0-9a-zA-Z_])', "", $strNombreCorregido_p);

  $strNombreCorregido_p = strtoupper($strNombreCorregido_p);

  $strNombreCorregido_p = rtrim($strNombreCorregido_p,"_");

  $strNombreCorregido_p = substr($strNombreCorregido_p,0,40);

  if (is_numeric($strNombreCorregido_p)) {

    $strNombreCorregido_p = "CAMPO_".$strNombreCorregido_p;

  }

  return $strNombreCorregido_p;

}

/**
 *JDBD - En esta funcion armamos las condiciones de los filtros que recibimos
 *@param .....
 *@return array. 
 */

function columnasDinamicas($intIdBd_p,$strCampoEspecifico_p=null){

    global $mysqli;
    global $BaseDatos_systema;
    global $BaseDatos_general;

    // Orden por defecto
    $orden = "ORDER BY PREGUN_ConsInte__b ASC";

    // Valido si el orden es por el campo PREGUN_OrdePreg__b
    if($strCampoEspecifico_p == "PREGUN_OrdePreg__b"){
        $orden = "ORDER BY PREGUN_ConsInte__SECCIO_b, PREGUN_OrdePreg__b ASC";
    }

    $strSQLCamposDinamicos_t = "SELECT PREGUN_ConsInte__b AS ID, CONCAT('G',PREGUN_ConsInte__GUION__b,'_C',PREGUN_ConsInte__b) AS campoId, IF(PREGUN_Texto_____b = '', 'COLUMNA_SIN_NOMBRE', PREGUN_Texto_____b) AS nombre, PREGUN_Tipo______b AS tipo, SECCIO_TipoSecc__b AS seccion FROM ".$BaseDatos_systema.".PREGUN LEFT JOIN ".$BaseDatos_systema.".SECCIO ON PREGUN_ConsInte__SECCIO_b = SECCIO_ConsInte__b WHERE PREGUN_ConsInte__GUION__b = ".$intIdBd_p." AND PREGUN_Tipo______b NOT IN (9,12,16,17) AND PREGUN_Texto_____b != 'ESTADO_DY' {$orden}";

    $strCamposFinal_t = "";

    $resSQLCamposDinamicos_t = $mysqli->query($strSQLCamposDinamicos_t);

    $objCamposDinamicos_t = [];

    while ($obj = $resSQLCamposDinamicos_t->fetch_object()) {

      $objCamposDinamicos_t[] = $obj;

    }

    return $objCamposDinamicos_t;

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

    $resSQLCamposDinamicos_t = $mysqli->query($strSQLCamposDinamicos_t);

    $objCamposDinamicos_t = [];

    while ($obj = $resSQLCamposDinamicos_t->fetch_object()) {

    $objCamposDinamicos_t[] = $obj;

    }

    return $objCamposDinamicos_t;

}

/**
 *JDBD - En esta funcion armamos el campo que quedara como reporte.
 *@param int,int,string
 *@return string. 
 */

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

/**
 *JDBD - En esta funcion armamos el campo de tipo lista auxiliar.
 *@param int
 *@return array. 
 */

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

/**
 * Esta funcion se encarga de obtener los campos principales y secundarios para mostrarlo en el reporte
 */
function obtenerCamposPrincipalSecundario($bdId){

    global $mysqli;
    global $BaseDatos_systema;

    $arrCampos = [];

    $sql = "SELECT GUION__ConsInte__PREGUN_Pri_b AS principal, GUION__ConsInte__PREGUN_Sec_b AS secundario FROM {$BaseDatos_systema}.GUION_ WHERE GUION__ConsInte__b = {$bdId}";
    $res = $mysqli->query($sql);

    if($res && $res->num_rows > 0){
        $data = $res->fetch_object();

        $arrCampos[] = "G" . $bdId . "_C" .  $data->principal;
        if($data->principal !== $data->secundario){
            $arrCampos[] = "G" . $bdId . "_C" .  $data->secundario;
        }
    }

    return $arrCampos;
}

/**
 *JDBD - En esta funcion armamos las condiciones de los filtros que recibimosm .....
 *@return array. 
 */

function columnasReporte($tipoReport_p,$intIdBd_p,$intIdMuestra_p,$intIdTipo_p=null,$intIdGuion_p=null,$intIdPaso_p=null, $strView_p=null, $arrCamposEspecificos_p = null){

    global $mysqli;
    global $BaseDatos_systema;
    global $BaseDatos_general;

    $strJOIN_t = "";

    switch ($tipoReport_p) {

      case 'bd':

        $arrColumnasDinamicas_t = columnasDinamicas($intIdBd_p);

        $strColumnasFinal_t = "G".$intIdBd_p."_ConsInte__b AS ID_BD, G".$intIdBd_p."_FechaInsercion AS FECHA_CREACION_DY, G".$intIdBd_p."_FechaUltimoCargue as FECHA_ULT_CARGUE_BD_DY, DATE_FORMAT(G".$intIdBd_p."_FechaInsercion,'%Y') AS ANHO_CREACION_DY, DATE_FORMAT(G".$intIdBd_p."_FechaInsercion,'%m') AS MES_CREACION_DY, DATE_FORMAT(G".$intIdBd_p."_FechaInsercion,'%d') AS DIA_CREACION_DY, DATE_FORMAT(G".$intIdBd_p."_FechaInsercion,'%H') AS HORA_CREACION_DY, ";

        $strColumnasDinamicas_t = "";

        foreach ($arrColumnasDinamicas_t as $value) {

          if ($value->tipo == "1" && $value->nombre == "ORIGEN_DY_WF") {

            $strColumnasFinal_t .= $value->campoId." AS ORIGEN_DY_WF, G".$intIdBd_p."_OrigenUltimoCargue as ORIGEN_ULT_CARGUE,  ";

            $strColumnasFinal_t .= "dyalogo_general.fn_nombre_paso_origen(G".$intIdBd_p."_PoblacionOrigen) AS PASO_ORIGEN, G".$intIdBd_p."_PasoGMI_b AS PASO_GMI_DY, ".$BaseDatos_general.".fn_item_lisopc(G".$intIdBd_p."_EstadoGMI_b) AS ESTADO_GMI_DY, ".$BaseDatos_general.".fn_item_monoef(G".$intIdBd_p."_GesMasImp_b) AS GESTION_MAS_IMPORTANTE_DY, ".$BaseDatos_general.".fn_clasificacion_traduccion(G".$intIdBd_p."_ClasificacionGMI_b) AS CLASIFICACION_GMI_DY, ".$BaseDatos_general.".fn_tipo_reintento_traduccion(G".$intIdBd_p."_TipoReintentoGMI_b) AS REINTENTO_GMI_DY, G".$intIdBd_p."_FecHorAgeGMI_b AS FECHA_AGENDA_GMI_DY, DATE_FORMAT(G".$intIdBd_p."_FecHorAgeGMI_b,'%Y') AS ANHO_AGENDA_GMI_DY, DATE_FORMAT(G".$intIdBd_p."_FecHorAgeGMI_b,'%m') AS MES_AGENDA_GMI_DY, DATE_FORMAT(G".$intIdBd_p."_FecHorAgeGMI_b,'%d') AS DIA_AGENDA_GMI_DY, DATE_FORMAT(G".$intIdBd_p."_FecHorAgeGMI_b,'%H') AS HORA_AGENDA_GMI_DY, G".$intIdBd_p."_ComentarioGMI_b AS COMENTARIO_GMI_DY, ".$BaseDatos_general.".fn_nombre_USUARI(G".$intIdBd_p."_UsuarioGMI_b) AS AGENTE_GMI_DY, G".$intIdBd_p."_CanalGMI_b AS CANAL_GMI_DY, G".$intIdBd_p."_SentidoGMI_b AS SENTIDO_GMI_DY, G".$intIdBd_p."_FeGeMaIm__b AS FECHA_GMI_DY, DATE_FORMAT(G".$intIdBd_p."_FeGeMaIm__b,'%Y') AS ANHO_GMI_DY, DATE_FORMAT(G".$intIdBd_p."_FeGeMaIm__b,'%m') AS MES_GMI_DY, DATE_FORMAT(G".$intIdBd_p."_FeGeMaIm__b,'%d') AS DIA_GMI_DY, DATE_FORMAT(G".$intIdBd_p."_FeGeMaIm__b,'%H') AS HORA_GMI_DY, (G".$intIdBd_p."_FeGeMaIm__b-G".$intIdBd_p."_FechaInsercion) AS DIAS_MADURACION_GMI_DY, ".$BaseDatos_general.".fn_nombre_paso(G".$intIdBd_p."_PasoUG_b) AS PASO_UG_DY, ".$BaseDatos_general.".fn_item_lisopc(G".$intIdBd_p."_EstadoUG_b) AS ESTADO_UG_DY, ".$BaseDatos_general.".fn_item_monoef(G".$intIdBd_p."_UltiGest__b) AS ULTIMA_GESTION_DY, ".$BaseDatos_general.".fn_clasificacion_traduccion(G".$intIdBd_p."_ClasificacionUG_b) AS CLASIFICACION_UG_DY, ".$BaseDatos_general.".fn_tipo_reintento_traduccion(G".$intIdBd_p."_TipoReintentoUG_b) AS REINTENTO_UG_DY, G".$intIdBd_p."_FecHorAgeUG_b AS FECHA_AGENDA_UG_DY, DATE_FORMAT(G".$intIdBd_p."_FecHorAgeUG_b,'%Y') AS ANHO_AGENDA_UG_DY, DATE_FORMAT(G".$intIdBd_p."_FecHorAgeUG_b,'%m') AS MES_AGENDA_UG_DY, DATE_FORMAT(G".$intIdBd_p."_FecHorAgeUG_b,'%d') AS DIA_AGENDA_UG_DY, DATE_FORMAT(G".$intIdBd_p."_FecHorAgeUG_b,'%H') AS HORA_AGENDA_UG_DY, G".$intIdBd_p."_ComentarioUG_b AS COMENTARIO_UG_DY, ".$BaseDatos_general.".fn_nombre_USUARI(G".$intIdBd_p."_UsuarioUG_b) AS AGENTE_UG_DY, G".$intIdBd_p."_Canal_____b AS CANAL_UG_DY, G".$intIdBd_p."_Sentido___b AS SENTIDO_UG_DY, G".$intIdBd_p."_FecUltGes_b AS FECHA_UG_DY, DATE_FORMAT(G".$intIdBd_p."_FecUltGes_b,'%Y') AS ANHO_UG_DY, DATE_FORMAT(G".$intIdBd_p."_FecUltGes_b,'%m') AS MES_UG_DY, DATE_FORMAT(G".$intIdBd_p."_FecUltGes_b,'%d') AS DIA_UG_DY, DATE_FORMAT(G".$intIdBd_p."_FecUltGes_b,'%H') AS HORA_UG_DY, G".$intIdBd_p."_LinkContenidoUG_b AS CONTENIDO_UG_DY, G".$intIdBd_p."_EstadoDiligenciamiento AS LLAVE_CARGUE_DY, (DAYOFMONTH((G".$intIdBd_p."_FechaInsercion - G".$intIdBd_p."_FecUltGes_b)) - DAYOFMONTH((G".$intIdBd_p."_FechaInsercion - G".$intIdBd_p."_FeGeMaIm__b))) AS DIAS_SIN_CONTACTO_DY, G".$intIdBd_p."_CantidadIntentos AS CANTIDAD_INTENTOS_DY, G".$intIdBd_p."_LinkContenidoGMI_b AS CONTENIDO_GMI_DY, ";

          }else{

            if ($value->tipo != "11") {

                $strColumnasDinamicas_t .= campoEnReporte($value->tipo,$value->campoId,aliasColumna($value->nombre));

            }else{

                $arrData_t = campoListaAuxiliar($value->campoId,aliasColumna($value->nombre));

                if (count($arrData_t) > 0) {

                    $strColumnasDinamicas_t .= $arrData_t[0];
                    $strJOIN_t .= $arrData_t[1];

                }


            }


          }

        }

        $strColumnasDinamicas_t = substr($strColumnasDinamicas_t, 0, -2);

        $strColumnasFinal_t .= $strColumnasDinamicas_t;

        return [$strColumnasFinal_t,$strJOIN_t];

        break;

      case 'bdpaso':

        // BGCR - Se modifica todos los titulos  para mover el origen de la consulta hacia la vista de las muestras // Se intenta mantener la misma estructura

        $arrColumnasDinamicas_t = columnasDinamicas($intIdBd_p);

        // if(validColumnExist("ESTADO_DY", $intIdGuion_p)){
        //     $strEstado_t = ", ESTADO_DY";
        // }else{
        //     $strEstado_t = "";

        // }

        $strColumnasFinal_t = "ID AS ID_DB, FECHA_CREACION AS FECHA_CREACION_BD_DY, FECHA_ULT_CARGUE AS  FECHA_ULT_CARGUE_BD_DY ,	ANHO_CREACION AS ANHO_CREACION_DY,MES_CREACION AS	MES_CREACION_DY, DIA_CREACION AS DIA_CREACION_DY, HORA_CREACION AS HORA_CREACION_DY,	Paso AS PASO_ORIGEN, ACTIVO AS ACTIVO_DY  , CANTIDAD_INTENTOS AS NUMERO_INTENTOS_DY, FECHA_CREACION_MUESTRA_DY, AGENTE_ASIGNADO AS AGENTE_DY, GESTION_MAS_IMPORTANTE, FECHA_GMI, ANHO_GMI, MES_GMI, DIA_GMI, HORA_GMI, CLASIFICACION_GMI, AGENTE_GMI, CANAL_GMI, SENTIDO_GMI, CONTENIDO_GMI AS LINK_GMI_DY, REINTENTO_GMI AS REINTENTO_GMI_DY, COMENTARIO_GMI, ULTIMA_GESTION, FECHA_REACTIVACION_MUESTRA_DY,	FECHA_UG, ANHO_UG, MES_UG, DIA_UG, HORA_UG, CLASIFICACION_UG, AGENTE_UG, CANAL_UG, SENTIDO_UG, CONTENIDO_UG AS LINK_UG_DY,REINTENTO_UG AS REINTENTO_UG_DY, COMENTARIO_UG, FECHA_AGENDA_UG AS FECHA_AGENDA_DY, ANHO_AGENDA_UG AS ANHO_AGENDA_DY, MES_AGENDA_UG AS MES_AGENDA_DY, DIA_AGENDA_UG AS DIA_AGENDA_DY, HORA_AGENDA_UG AS HORA_AGENDA_DY,";

        $strColumnasDinamicas_t = "";
        
        $strColumnasAdicionales_t = "";

        foreach ($arrColumnasDinamicas_t as $value) {

          if ($value->tipo == "1" && $value->nombre == "ORIGEN_DY_WF") {

                $strColumnasFinal_t .= $value->nombre.", ORIGEN_ULT_CARGUE,  ";

          }else{

                if ($value->tipo != "11") {

                    $strColumnasDinamicas_t .= getNameColumnView($intIdGuion_p, $value->campoId).", ";

                }else{

                    $strColumnasDinamicas_t .= getNameColumnView($intIdGuion_p, $value->campoId, 11).", ";

                }

          }

        }


        $strColumnasDinamicas_t = substr($strColumnasDinamicas_t, 0, -2);

        $strColumnasFinal_t .= $strColumnasAdicionales_t.$strColumnasDinamicas_t;

        return [$strColumnasFinal_t,$strJOIN_t];

        break;

      case 'bkpaso':

        $arrColumnasDinamicas_t = columnasDinamicas($intIdBd_p);

        $strColumnasFinal_t = "G".$intIdBd_p."_ConsInte__b AS ID_BD, G".$intIdBd_p."_FechaInsercion AS FECHA_CREACION_DY, DATE_FORMAT(G".$intIdBd_p."_FechaInsercion,'%Y') AS ANHO_CREACION_DY, DATE_FORMAT(G".$intIdBd_p."_FechaInsercion,'%m') AS MES_CREACION_DY, DATE_FORMAT(G".$intIdBd_p."_FechaInsercion,'%d') AS DIA_CREACION_DY, DATE_FORMAT(G".$intIdBd_p."_FechaInsercion,'%H') AS HORA_CREACION_DY, ";

        $strColumnasDinamicas_t = "";

        foreach ($arrColumnasDinamicas_t as $value) {

            if ($value->tipo != "11") {

                $strColumnasDinamicas_t .= campoEnReporte($value->tipo,$value->campoId,aliasColumna($value->nombre));

            }else{

                $arrData_t = campoListaAuxiliar($value->campoId,aliasColumna($value->nombre));

                if (count($arrData_t) > 0) {
                    
                    $strColumnasDinamicas_t .= $arrData_t[0];
                    $strJOIN_t .= $arrData_t[1];

                }


            }

        }

        $strColumnasDinamicas_t = substr($strColumnasDinamicas_t, 0, -2);

        $strColumnasFinal_t .= $strColumnasDinamicas_t;

        return [$strColumnasFinal_t,$strJOIN_t];

        break;

      case 'gspaso':

        // BGCR - Se modifica todos los titulos  para mover el origen de la consulta hacia la vista del script

        $arrColumnasDinamicas_t = columnasDinamicas($intIdGuion_p);

        $strColumnasFinal_t = "ID, ID_BD, FECHA_CREACION AS FECHA_CREACION_DY, ";

        $strColumnasDinamicas_t = "";

        foreach ($arrColumnasDinamicas_t as $value) {

            if ($value->seccion == "4" || $value->seccion == "3") {

                switch ($value->nombre) {
  
                  case 'Tipificación':
  
                    $arrGestionControl_t["tipificacion"]="ULTIMA_GESTION AS TIPIFICACIÓN_DY, ";
  
                    break;
  
                  case 'Reintento':
  
                    $arrGestionControl_t["reintento"]="REINTENTO, ";
  
                    break;
  
                  case 'Hora Agenda':
  
                    $arrGestionControl_t["feAgenda"]="FECHA_AGENDA, ";
                    $arrGestionControl_t["anAgenda"]="ANHO_AGENDA, ";
                    $arrGestionControl_t["meAgenda"]="MES_AGENDA, ";
                    $arrGestionControl_t["diAgenda"]="DIA_AGENDA, ";
                    $arrGestionControl_t["hoAgenda"]="HORA_AGENDA, ";
  
                    break;
  
                  case 'Observacion':
  
                    $arrGestionControl_t["observacion"]="OBSERVACION, ";
  
                    break;
  
                  case 'Fecha':
                    $arrGestionControl_t["fecha"]="FECHA_GESTION, ";
                    $arrGestionControl_t["anho"]="ANHO_GESTION, ";
                    $arrGestionControl_t["mes"]="MES_GESTION, ";
                    $arrGestionControl_t["dia"]="DIA_GESTION, ";
                    $arrGestionControl_t["hora"]="HORA_GESTION, ";
  
                    break;
  
                }
  
              }else{

                if ($value->tipo != "11") {

                    $strColumnasDinamicas_t .= getNameColumnView($strView_p, $value->campoId).", ";

                }
                
                else{

                    $strColumnasDinamicas_t .= getNameColumnView($strView_p, $value->campoId, 11).", ";

                }

            }



        }

        $strColumnasFinal_t .= $arrGestionControl_t["fecha"].$arrGestionControl_t["anho"].$arrGestionControl_t["mes"].$arrGestionControl_t["dia"].$arrGestionControl_t["hora"]." DURACION_GESTION, DURACION_GESTION_SEG, ";
        
        // Valido si el campo DatoContacto existe para agregarlo en el script
        if(validarCampoExiste($strView_p, 'DatoContacto')){
            $strColumnasFinal_t .= "DatoContacto AS DATO_CONTACTO, ";
        }

        $strColumnasFinal_t .= "AGENTE, SENTIDO, CANAL , ".$arrGestionControl_t["tipificacion"]." CLASIFICACION, ".$arrGestionControl_t["reintento"].$arrGestionControl_t["feAgenda"].$arrGestionControl_t["anAgenda"].$arrGestionControl_t["meAgenda"].$arrGestionControl_t["diAgenda"].$arrGestionControl_t["hoAgenda"].$arrGestionControl_t["observacion"]." RESULTADO_ENCUESTAS AS ENCUESTA_CALIDAD ,DESCARGAGRABACION, Paso AS PASO_DY, OrigenGestion AS ORIGEN_DY, ";

        $strColumnasDinamicas_t = substr($strColumnasDinamicas_t, 0, -2);

        $strColumnasFinal_t .= $strColumnasDinamicas_t;

        return [$strColumnasFinal_t,$strJOIN_t];

        break;
      case 'gsbot':

        $arrColumnasDinamicas_t = columnasDinamicas($intIdGuion_p, 'PREGUN_OrdePreg__b');

        $strColumnasFinal_t = "G{$intIdGuion_p}_ConsInte__b AS ID, G{$intIdBd_p}_CodigoMiembro AS ID_BD, G{$intIdBd_p}_FechaInsercionBD_b AS FECHA_CREACION_DY, ";

        $strColumnasDinamicas_t = "";        

        foreach ($arrColumnasDinamicas_t as $value) {

            if ($value->seccion == "4" || $value->seccion == "3") {
                
                switch ($value->nombre) {
                    case 'Tipificacion':
                        $arrGestionControl_t["tipificacion"]=$BaseDatos_general.".fn_item_lisopc(".$value->campoId.") AS TIPIFICACIÓN_DY, ";
                        break;
                    case 'Reintento':
                        $arrGestionControl_t["reintento"]=$BaseDatos_general.".fn_tipo_reintento_traduccion(".$value->campoId.") AS REINTENTO_DY, ";
                        break;
                    case 'Observacion':    
                        $arrGestionControl_t["observacion"]=$value->campoId." AS OBSERVACION_DY, ";
                        break;    
                }
            }else if($value->nombre == 'Tipificacion_Bot'){
                $arrGestionControl_t["Tipificacion_Bot"] = $value->campoId." AS DETALLE_TIPIFICACIÓN_BOT, ";
            }else{
                if ($value->tipo != "11") {
                    $strColumnasDinamicas_t .= campoEnReporte($value->tipo,$value->campoId,aliasColumna($value->nombre));
                }else{
                    $arrData_t = campoListaAuxiliar($value->campoId,aliasColumna($value->nombre));
                    if (count($arrData_t) > 0) {
                        $strColumnasDinamicas_t .= $arrData_t[0];
                        $strJOIN_t .= $arrData_t[1];
                    }
                }
            }
        }

        $strColumnasFinal_t .= "G{$intIdGuion_p}_FechaInsercion AS FECHA_GESTION_DY, DATE_FORMAT(G{$intIdGuion_p}_FechaInsercion,'%Y') AS ANHO_GESTION_DY, DATE_FORMAT(G{$intIdGuion_p}_FechaInsercion,'%m') AS MES_GESTION_DY, DATE_FORMAT(G{$intIdGuion_p}_FechaInsercion,'%d') AS DIA_GESTION_DY, DATE_FORMAT(G{$intIdGuion_p}_FechaInsercion,'%H') AS HORA_GESTION_DY, SEC_TO_TIME(G{$intIdGuion_p}_Duracion___b) AS DURACION_GESTION, G{$intIdGuion_p}_Duracion___b AS DURACION_GESTION_SEG, G{$intIdGuion_p}_IdLlamada AS CHAT_ENTRANTE, G{$intIdGuion_p}_Sentido___b AS SENTIDO, G{$intIdGuion_p}_Canal_____b AS CANAL, {$arrGestionControl_t["tipificacion"]} {$arrGestionControl_t["Tipificacion_Bot"]} {$BaseDatos_general}.fn_clasificacion_traduccion(G{$intIdGuion_p}_Clasificacion) AS CLASIFICACION_DY, {$arrGestionControl_t["reintento"]} {$arrGestionControl_t["observacion"]} G{$intIdGuion_p}_LinkContenido AS DESCARGAGRABACION_DY, G{$intIdGuion_p}_DatoContacto AS DATO_CONTACTO, G{$intIdGuion_p}_Paso AS PASO_DY, G{$intIdGuion_p}_Origen_b AS ORIGEN_DY, ";

        $strColumnasDinamicas_t = substr($strColumnasDinamicas_t, 0, -2);

        $strColumnasFinal_t .= $strColumnasDinamicas_t;

        return [$strColumnasFinal_t,$strJOIN_t];

        break;

      case 'opcionesUsadasBot':

        $strColumnasFinal_t = "a.seccion_nombre AS SECCION, b.pregunta AS OPCION, count(1) AS CANTIDAD";

        return [$strColumnasFinal_t, $strJOIN_t];

        break;
      case 'ordenamiento':

        $arrCamposBd = columnasDinamicas($intIdBd_p);

        $strColumnasFinal_t = "";

        // Recorro la lista de tipos de campos
        foreach ($arrCamposEspecificos_p as $tipoCampo => $listaCampos) {
            
            $etiqueta = "";

            if($tipoCampo == "porDefecto"){

            }else if($tipoCampo == "principalSecundario") {
                $etiqueta = "A_";
            }else if($tipoCampo == "select") {
                $etiqueta = "B_";
            }else if($tipoCampo == "orden") {
                $etiqueta = "ORDEN_";
            }

            // Recorro los campos de cada tipo
            foreach ($listaCampos as $campoId => $campo) {

                // Quito todos los espacios de cada elemento
                $campo = trim($campo);
                
                switch ($campo) {
                    case "G{$intIdBd_p}_ConsInte__b":
                        $strColumnasFinal_t .= "G".$intIdBd_p."_ConsInte__b AS {$etiqueta}ID_BD, ";
                        break;
                    case "G{$intIdBd_p}_FechaInsercion":
                        $strColumnasFinal_t .= "G{$intIdBd_p}_FechaInsercion AS {$etiqueta}FECHA_CREACION, ";
                        break;
                    // case "G{$intIdBd_p}_M{$intIdMuestra_p}_CoInMiPo__b":
                    //     $strColumnasFinal_t .= "G{$intIdBd_p}_M{$intIdMuestra_p}_CoInMiPo__b AS {$etiqueta}ID_BD, ";
                    //     break;
                    case "G{$intIdBd_p}_M{$intIdMuestra_p}_ConIntUsu_b":
                        $strColumnasFinal_t .= $BaseDatos_general.".fn_nombre_USUARI(G{$intIdBd_p}_M{$intIdMuestra_p}_ConIntUsu_b) AS {$etiqueta}AGENTE_ASIGNADO, ";
                        break;
                    case "G{$intIdBd_p}_M{$intIdMuestra_p}_Estado____b":
                        $strColumnasFinal_t .= "{$BaseDatos_general}.fn_tipo_reintento_traduccion(G{$intIdBd_p}_M{$intIdMuestra_p}_Estado____b) AS {$etiqueta}ESTADO_REGISTRO, ";
                        break;
                    case "G{$intIdBd_p}_M{$intIdMuestra_p}_FecHorAge_b":
                        $strColumnasFinal_t .= "G{$intIdBd_p}_M{$intIdMuestra_p}_FecHorAge_b AS {$etiqueta}FECHA_AGENDA, ";
                        break;
                    case "G{$intIdBd_p}_M{$intIdMuestra_p}_FecHorMinProGes__b":
                        $strColumnasFinal_t .= "G{$intIdBd_p}_M{$intIdMuestra_p}_FecHorMinProGes__b AS {$etiqueta}FECHA_MINIMA_PROXIMO_INTENTO_DY, ";
                        break;
                    case "G{$intIdBd_p}_M{$intIdMuestra_p}_FecUltGes_b":
                        $strColumnasFinal_t .= "G{$intIdBd_p}_M{$intIdMuestra_p}_FecUltGes_b AS {$etiqueta}FECHA_ULTIMA_GESTION, ";
                        break;
                    case "G{$intIdBd_p}_M{$intIdMuestra_p}_NumeInte__b":
                        $strColumnasFinal_t .= "G{$intIdBd_p}_M{$intIdMuestra_p}_NumeInte__b AS {$etiqueta}NUMERO_INTENTOS, ";
                        break;
                    case "G{$intIdBd_p}_M{$intIdMuestra_p}_UltiGest__b":
                        $strColumnasFinal_t .= "dyalogo_general.fn_item_monoef(G{$intIdBd_p}_M{$intIdMuestra_p}_UltiGest__b) AS {$etiqueta}ULTIMA_GESTON, ";
                        break;
                    default:
                        // Valido que el campo sea de bd
                        if(strpos($campo, "G".$intIdBd_p."_C") !== false){
                            // Busco el campo en la lista de campos de la bd
                            foreach ($arrCamposBd as $campoBd) {
                                if($campoBd->campoId == $campo){
                                    $strColumnasFinal_t .= campoEnReporte($campoBd->tipo,$campoBd->campoId,$etiqueta.aliasColumna($campoBd->nombre));
                                    break;
                                }
                            }
                        }
                        break;
                }

            }
        }
        
        $strColumnasFinal_t = substr($strColumnasFinal_t, 0, -2);

        return [$strColumnasFinal_t, $strJOIN_t];
        break;

      case 'tareasProgramadas':

        // Obtengo la lista de campos de la bd
        $arrColumnasDinamicas_t = columnasDinamicas($intIdBd_p);

        $strColumnasFinal_t = "G".$intIdBd_p."_ConsInte__b AS ID_BD, G".$intIdBd_p."_FechaInsercion AS FECHA_CREACION_DY,";

        $strColumnasDinamicas_t = "";

        foreach ($arrColumnasDinamicas_t as $value) {

            if ($value->tipo != "11") {
                $strColumnasDinamicas_t .= campoEnReporte($value->tipo,$value->campoId,aliasColumna($value->nombre));
            }else{
                $arrData_t = campoListaAuxiliar($value->campoId,aliasColumna($value->nombre));
                if (count($arrData_t) > 0) {
                    $strColumnasDinamicas_t .= $arrData_t[0];
                    $strJOIN_t .= $arrData_t[1];
                }
            }
        }

        $strColumnasDinamicas_t = substr($strColumnasDinamicas_t, 0, -2);

        $strColumnasFinal_t .= $strColumnasDinamicas_t;

        return [$strColumnasFinal_t,$strJOIN_t];

        break;
    }


}

/**
 * BSV - Esta funcion valida si un campo existe en una tabla
 * @param $tabla, @param $campo
 * @return boolean
 */
function validarCampoExiste($tabla, $campo){
    
    global $mysqli;
    global $BaseDatos;

    $sql = "SHOW COLUMNS FROM {$BaseDatos}.{$tabla} WHERE Field = '{$campo}'";
    $res = $mysqli->query($sql);

    if($res && $res->num_rows > 0){
        return true;
    }

    return false;
}

/**
 *JDBD - En esta funcion se armara las condiciones dinamicamente.
 *@param .....
 *@return string. 
 */

function condicionesDinamicas($strJsonCondicion_p){

  $arrJsonCondicion_t = json_decode($strJsonCondicion_p);

  $strCondicionFinal_t = "";

  foreach ($arrJsonCondicion_t as $value) {

    $strCondicionFinal_t .= "(".$value.") AND ";

  }

  $strCondicionFinal_t = substr($strCondicionFinal_t, 0, -4);

  return $strCondicionFinal_t;

}

// funcion que obtiene los campos que se usaran para la consulta de ordenamiento
function obtenerCamposDeConsultaOrdenamiento($consulta, $intIdBd_p, $intIdMuestra_p){
    // Obtenemos los campos del select 
    $campos = explode("FROM",$consulta)[0];
    $campos = str_replace("SELECT ", "", $campos);
    $arrCampos = explode(",", $campos);
    $arrCampos = array_map('trim', $arrCampos);

    // y del ordenamiento de la consulta de contactos
    $campos = explode("ORDER BY", $consulta)[1];
    $campos = str_replace("DESC", "", $campos);
    $campos = str_replace("ASC", "", $campos);
    $arrCamposOrden = explode(",", $campos);
    $arrCamposOrden = array_map('trim', $arrCamposOrden);

    $arrPorDefecto = [
        "G".$intIdBd_p."_M".$intIdMuestra_p."_Estado____b",
        "G{$intIdBd_p}_M{$intIdMuestra_p}_FecHorAge_b"
    ];

    return [
        "porDefecto" => $arrPorDefecto,
        "orden" => $arrCamposOrden,
        "principalSecundario" => obtenerCamposPrincipalSecundario($intIdBd_p),
        "select" => $arrCampos,
        "porDefecto2" => ["G".$intIdBd_p."_ConsInte__b","G".$intIdBd_p."_M".$intIdMuestra_p."_ConIntUsu_b",]
    ];
}

function dividirConsultaSql($query):array{
    
    // Divide la consulta en dos partes principales: SELECT-FROM y FROM-WHERE-ORDER BY
    list($selectFromPart, $fromWhereOrderPart) = explode(" FROM ", $query, 2);

    // Divide la segunda parte en FROM, WHERE y ORDER BY
    list($fromPart, $whereOrderPart) = preg_split("/ WHERE /i", $fromWhereOrderPart, 2);

    // Divido la ultima parte
    list($wherePart, $orderPart) = explode(" ORDER BY ", $whereOrderPart);

    // Asigna las partes a las variables correspondientes
    $select = $selectFromPart;
    $from = $fromPart;
    $where = $wherePart;
    $order = $orderPart;

    return [
        "select" => $select, "from" => $from, "where" => $where, "order" => $order
    ];
}

/**
 *JDBD - En esta funcion conseguiremos toda la informacion que se necesita para el reporte y su paginacion como lo es, la cantidad de registros, campos de la consulta ect.
 *@param .....
 *@return array. 
 */



function dimensiones($tipoReport_p,$strFechaIn_p,$strFechaFn_p,$intIdPeriodo_p,$strLimit_p,$intIdHuesped_p,$intIdEstrat_p,$intIdCBX_p,$intIdBd_p,$intIdPaso_p,$intIdTipo_p,$intIdMuestra_p,$intIdGuion_p,$intFilas_p,$intLimite_p,$strSolicitud_p=null,$arrDataFiltros_p){

    global $mysqli;
    global $BaseDatos;
    global $BaseDatos_systema;
    global $BaseDatos_telefonia;
    global $dyalogo_canales_electronicos;
    global $BaseDatos_general;
    global $arrCamposMuestra_t;
    global $arrCamposMuestra2_t;
    global $IP_SERVICIO_DISTRIBUCION;
    global $URL_SERVER_ADDONS;

    switch ($tipoReport_p) {

        case 'cargue':

            $strNombreHoja_t = "RESUMEN CARGUE";

            $strSQLVista_t = "SELECT nombre FROM ".$BaseDatos_general.".vistas_generadas WHERE id_guion = ".$intIdGuion_p." ORDER BY id DESC LIMIT 1"; 


            $resSQLVista_t = $mysqli->query($strSQLVista_t);
            $objSQLVista_t = $resSQLVista_t->fetch_object();

            if ($intIdMuestra_p == -1) {

                $strSQLReporte_t = "SELECT * FROM ".$BaseDatos.".".$objSQLVista_t->nombre." WHERE LLAVE_CARGUE = ".$strFechaIn_p." ORDER BY ID DESC LIMIT 500";
                $strSQLReporteNoLimit_t = "SELECT * FROM ".$BaseDatos.".".$objSQLVista_t->nombre." WHERE LLAVE_CARGUE = ".$strFechaIn_p." ORDER BY ID DESC";

            }else{
                
                $strSQLReporte_t = "SELECT ".$BaseDatos_general.".fn_tipo_reintento_traduccion(B.G".$intIdGuion_p."_M".$intIdMuestra_p."_Estado____b) AS REINTENTO_UG_DY, ".$BaseDatos_general.".fn_nombre_USUARI(B.G".$intIdGuion_p."_M".$intIdMuestra_p."_ConIntUsu_b) AS AGENTE_ASIGNADO, A.* FROM ".$BaseDatos.".".$objSQLVista_t->nombre." A JOIN ".$BaseDatos.".G".$intIdGuion_p."_M".$intIdMuestra_p." B ON A.ID = B.G".$intIdGuion_p."_M".$intIdMuestra_p."_CoInMiPo__b WHERE A.LLAVE_CARGUE = ".$strFechaIn_p." ORDER BY A.ID DESC LIMIT 500";
                $strSQLReporteNoLimit_t = "SELECT ".$BaseDatos_general.".fn_tipo_reintento_traduccion(B.G".$intIdGuion_p."_M".$intIdMuestra_p."_Estado____b) AS REINTENTO_UG_DY, ".$BaseDatos_general.".fn_nombre_USUARI(B.G".$intIdGuion_p."_M".$intIdMuestra_p."_ConIntUsu_b) AS AGENTE_ASIGNADO, A.* FROM ".$BaseDatos.".".$objSQLVista_t->nombre." A JOIN ".$BaseDatos.".G".$intIdGuion_p."_M".$intIdMuestra_p." B ON A.ID = B.G".$intIdGuion_p."_M".$intIdMuestra_p."_CoInMiPo__b WHERE A.LLAVE_CARGUE = ".$strFechaIn_p." ORDER BY A.ID DESC";


            }

            $arrReporte_t[0] = $strSQLReporte_t;
            $arrReporte_t[1] = $strNombreHoja_t;
            $arrReporte_t[3] = $strSQLReporteNoLimit_t;
            $arrReporte_t[4] = $strNombreHoja_t;


            return $arrReporte_t;

            break;

        case 'acd':

            //JDBD - traemos las vitas ACD de la campaña.

            $strCondicion_t = "DATE(Fecha) BETWEEN '".$strFechaIn_p."' AND '".$strFechaFn_p."'";

            if ($arrDataFiltros_p["totalFiltros"]>0) {

                $strCondicion_t = "";

                foreach ($arrDataFiltros_p["totalFiltros"] as $ite => $value) {


                    $strCondicion_t .=armarCondicion($arrDataFiltros_p["dataFiltros"][$ite]["selCampo_".$value],$arrDataFiltros_p["dataFiltros"][$ite]["selOperador_".$value],$arrDataFiltros_p["dataFiltros"][$ite]["valor_".$value],$arrDataFiltros_p["dataFiltros"][$ite]["tipo_".$value],$arrDataFiltros_p["dataFiltros"][$ite]["selCondicion_".$value], $arrDataFiltros_p["dataFiltros"][$ite]["cierre".$value]);

                }

            }

            
            $strIntervalo_t = "%ACD_DIA%";
            $strCampoIntervalo_t = "";
            $strCampoTransfer_t = ",transfer";
            $ordenRegistros = "ORDER BY Fecha DESC";

            if ($intIdPeriodo_p == "2") {

                    $strCampoIntervalo_t = ", Intervalo";
                    $strIntervalo_t = "%ACD_HORA%";
                    $strCampoTransfer_t = "";
                    $ordenRegistros = "ORDER BY Fecha DESC, Intervalo DESC";

            }

            $strSQLACD_t = "SELECT nombre FROM ".$BaseDatos_general.".vistas_generadas JOIN ".$BaseDatos_systema.".CAMPAN ON id_campan = CAMPAN_ConsInte__b WHERE CAMPAN_IdCamCbx__b = ".$intIdCBX_p." AND nombre LIKE '".$strIntervalo_t."'";

            $resSQLACD_t = $mysqli->query($strSQLACD_t);


            $strSQLReporte_t = "SELECT 'NO HAY REPORTE ACD DISPONIBLE' AS ERROR";
            $strSQLReporteNoLimit_t = "SELECT 'NO HAY REPORTE ACD DISPONIBLE' AS ERROR";

            if ($resSQLACD_t->num_rows > 0) {

                $strNombreACD_t = $resSQLACD_t->fetch_object()->nombre;

                $strSQLCampos_t = "DESC ".$BaseDatos.".".$strNombreACD_t;

                $resSQLCampos_t = $mysqli->query($strSQLCampos_t);

                $strColumnas_t = "";

                while ($obj = $resSQLCampos_t->fetch_object()) {

                    $strColumnas_t .= depurarACD($obj->Field).", ";

                }

                $strColumnas_t = substr($strColumnas_t, 0, -2);

                $arrFilasPaginas_t = filasPaginas($strNombreACD_t,$strCondicion_t,$intLimite_p,'acd');

                $strSQLReporte_t = "SELECT ".$strColumnas_t." FROM ".$BaseDatos.".".$strNombreACD_t." WHERE (".$strCondicion_t.") ".$ordenRegistros." LIMIT 0,".$intLimite_p;

                $strSQLReporteNoLimit_t = "SELECT ".$strColumnas_t." FROM ".$BaseDatos.".".$strNombreACD_t." WHERE (".$strCondicion_t.") ".$ordenRegistros;

            }

            return [$arrFilasPaginas_t[0],$arrFilasPaginas_t[1],$strSQLReporte_t,$strSQLReporteNoLimit_t,"ACD"];

            break;


        case 'acdChat':

            $strCondicion_t = "DATE(Fecha) BETWEEN '".$strFechaIn_p."' AND '".$strFechaFn_p."'";

            if ($arrDataFiltros_p["totalFiltros"]>0) {

                $strCondicion_t = "";

                foreach ($arrDataFiltros_p["totalFiltros"] as $ite => $value) {


                    $strCondicion_t .=armarCondicion($arrDataFiltros_p["dataFiltros"][$ite]["selCampo_".$value],$arrDataFiltros_p["dataFiltros"][$ite]["selOperador_".$value],$arrDataFiltros_p["dataFiltros"][$ite]["valor_".$value],$arrDataFiltros_p["dataFiltros"][$ite]["tipo_".$value],$arrDataFiltros_p["dataFiltros"][$ite]["selCondicion_".$value],$arrDataFiltros_p["dataFiltros"][$ite]["cierre".$value]);

                }

            }


            $tablaACD = "DY_V_ACD_DIA_CHATS";
            $Intervalo = "";

            if ($intIdPeriodo_p == "2") {
                $tablaACD = "DY_V_ACD_HORA_CHATS";
                $Intervalo = " Intervalo, ";
            }



            $strColumnas_t = " Fecha, {$Intervalo} TSF_Tiempo,TSF_porcentaje,Ofrecidos, Contestados, Cont_antes_tsf, Cont_despues_tsf, Aban_despues_tsf, REPLACE(ROUND(Cont_porcentaje, 2), '.00', '') AS Cont_porcentaje, TSF, ROUND(TSF_Cont_antes_tsf, 2) AS TSF_Cont_antes_tsf, ROUND(TSF_Cont_despues_tsf, 2) AS TSF_Cont_despues_tsf, ASA, ASAMin, ASAMax, TSA, SUBSTR(AHT, 1, 8) AS AHT, SUBSTR(THT, 1, 8) AS THT, Aban,Aban_antes_tsf, ROUND(Aban_porcentaje, 2) AS Aban_porcentaje, ROUND(Aban_umbral_tsf, 2) AS Aban_umbral_tsf, SUBSTR(Aban_espera, 1, 8) AS Aban_espera, SUBSTR(Aban_espera_total, 1, 8) AS Aban_espera_total, SUBSTR(Aban_espera_min, 1, 8) AS Aban_espera_min, SUBSTR(Aban_espera_max, 1, 8) AS Aban_espera_max ";

            $arrFilasPaginas_t = filasPaginas($tablaACD ," id_campana = {$intIdCBX_p} AND ({$strCondicion_t}) ",$intLimite_p,'acdChat');

            $strSQLReporte_t = "SELECT {$strColumnas_t} FROM {$dyalogo_canales_electronicos}.{$tablaACD} WHERE id_campana = {$intIdCBX_p} AND ({$strCondicion_t}) ORDER BY fecha DESC LIMIT 0,".$intLimite_p;

            $strSQLReporteNoLimit_t = "SELECT {$strColumnas_t} FROM {$dyalogo_canales_electronicos}.{$tablaACD} WHERE id_campana = {$intIdCBX_p} AND ({$strCondicion_t}) ORDER BY fecha DESC";

            return [$arrFilasPaginas_t[0],$arrFilasPaginas_t[1],$strSQLReporte_t,$strSQLReporteNoLimit_t,"ACD CHATS"];

            break;


        case 'acdEmail':

            $strCondicion_t = "DATE(Fecha) BETWEEN '".$strFechaIn_p."' AND '".$strFechaFn_p."'";

            if ($arrDataFiltros_p["totalFiltros"]>0) {

                $strCondicion_t = "";

                foreach ($arrDataFiltros_p["totalFiltros"] as $ite => $value) {


                    $strCondicion_t .=armarCondicion($arrDataFiltros_p["dataFiltros"][$ite]["selCampo_".$value],$arrDataFiltros_p["dataFiltros"][$ite]["selOperador_".$value],$arrDataFiltros_p["dataFiltros"][$ite]["valor_".$value],$arrDataFiltros_p["dataFiltros"][$ite]["tipo_".$value],$arrDataFiltros_p["dataFiltros"][$ite]["selCondicion_".$value],$arrDataFiltros_p["dataFiltros"][$ite]["cierre".$value]);

                }

            }


            $tablaACD = "DY_V_ACD_DIA_CORREOS";
            $Intervalo = "";

            if ($intIdPeriodo_p == "2") {
                $tablaACD = "DY_V_ACD_HORA_CORREOS";
                $Intervalo = " Intervalo, ";
            }



            $strColumnas_t = " Fecha, {$Intervalo} TSF_Tiempo,TSF_porcentaje,Ofrecidos, Contestados, Cont_antes_tsf, Cont_despues_tsf, REPLACE(ROUND(Cont_porcentaje, 2), '.00', '') AS Cont_porcentaje, TSF, ROUND(TSF_Cont_antes_tsf, 2) AS TSF_Cont_antes_tsf, ROUND(TSF_Cont_despues_tsf, 2) AS TSF_Cont_despues_tsf, ASA, ASAMin, ASAMax, TSA, SUBSTR(AHT, 1, 8) AS AHT, SUBSTR(THT, 1, 8) AS THT ";

            $arrFilasPaginas_t = filasPaginas($tablaACD ," id_campana = {$intIdCBX_p} AND ({$strCondicion_t}) ",$intLimite_p,'acdChat');

            $strSQLReporte_t = "SELECT {$strColumnas_t} FROM {$dyalogo_canales_electronicos}.{$tablaACD} WHERE id_campana = {$intIdCBX_p} AND ({$strCondicion_t}) ORDER BY fecha DESC LIMIT 0,".$intLimite_p;

            $strSQLReporteNoLimit_t = "SELECT {$strColumnas_t} FROM {$dyalogo_canales_electronicos}.{$tablaACD} WHERE id_campana = {$intIdCBX_p} AND ({$strCondicion_t}) ORDER BY fecha DESC";

            return [$arrFilasPaginas_t[0],$arrFilasPaginas_t[1],$strSQLReporte_t,$strSQLReporteNoLimit_t,"ACD CORREO"];

            break;

        case 'bkpaso':

            $strCondicion_t = "DATE(G".$intIdBd_p."_FechaInsercion) BETWEEN '".$strFechaIn_p."' AND '".$strFechaFn_p."'";

            if ($arrDataFiltros_p["totalFiltros"]>0) {

                $strCondicion_t = "";

                foreach ($arrDataFiltros_p["totalFiltros"] as $ite => $value) {


                    $strCondicion_t .=armarCondicion($arrDataFiltros_p["dataFiltros"][$ite]["selCampo_".$value],$arrDataFiltros_p["dataFiltros"][$ite]["selOperador_".$value],$arrDataFiltros_p["dataFiltros"][$ite]["valor_".$value],$arrDataFiltros_p["dataFiltros"][$ite]["tipo_".$value],$arrDataFiltros_p["dataFiltros"][$ite]["selCondicion_".$value], $arrDataFiltros_p["dataFiltros"][$ite]["cierre".$value]);

                }

            }

            $arrFilasPaginas_t = filasPaginas($intIdBd_p,$strCondicion_t,$intLimite_p,'bkpaso',$intIdMuestra_p);

            $arrData_t = columnasReporte("bkpaso",$intIdBd_p,$intIdMuestra_p);

            $strColumnasDinamicas_t = $arrData_t[0];

            $strSQLReporte_t = "SELECT ".$strColumnasDinamicas_t." FROM ".$BaseDatos.".G".$intIdBd_p." INNER JOIN ".$BaseDatos.".G".$intIdBd_p."_M".$intIdMuestra_p." ON G".$intIdBd_p."_ConsInte__b = G".$intIdBd_p."_M".$intIdMuestra_p."_CoInMiPo__b ".$arrData_t[1]." WHERE (".$strCondicion_t.") ORDER BY G".$intIdBd_p."_ConsInte__b DESC LIMIT 0,".$intLimite_p;

            $strSQLReporteNoLimit_t = "SELECT ".$strColumnasDinamicas_t." FROM ".$BaseDatos.".G".$intIdBd_p." INNER JOIN ".$BaseDatos.".G".$intIdBd_p."_M".$intIdMuestra_p." ON G".$intIdBd_p."_ConsInte__b = G".$intIdBd_p."_M".$intIdMuestra_p."_CoInMiPo__b ".$arrData_t[1]."  WHERE (".$strCondicion_t.") ORDER BY G".$intIdBd_p."_ConsInte__b DESC ";

            return [$arrFilasPaginas_t[0],$arrFilasPaginas_t[1],$strSQLReporte_t,$strSQLReporteNoLimit_t,"BACKOFFICE"];

            break;

        case 'bdpaso':

                $strNameView_t = nombreVistas($intIdBd_p,$intIdMuestra_p);

                if(is_null($strNameView_t)){
                    return ["","","","","RESUMEN BASE", "fallo", "No se pudo extraer la vista de la campaña o no existe"];
                }

                $strCondicion_t = "DATE(G".$intIdBd_p."_FechaInsercion) BETWEEN '".$strFechaIn_p."' AND '".$strFechaFn_p."'"; 

                if ($arrDataFiltros_p["totalFiltros"]>0) {

                    $strCondicion_t = "";

                    foreach ($arrDataFiltros_p["totalFiltros"] as $ite => $value) {
                    
                        if(preg_match("/^[G][0-9]{3,4}[_][C][0-9]{4,5}$/", $arrDataFiltros_p["dataFiltros"][$ite]["selCampo_".$value])){

                            // Se valida si es un campo de tipo lista, y se obtiene el valor en texto de la opcion
                            if($arrDataFiltros_p["dataFiltros"][$ite]["tipo_".$value] == 6){ 

                                $arrDataFiltros_p["dataFiltros"][$ite]["valor_".$value] = getOptionName($arrDataFiltros_p["dataFiltros"][$ite]["valor_".$value]);

                            }

                            $strCondicion_t .=armarCondicion(getNameColumnView($strNameView_t, $arrDataFiltros_p["dataFiltros"][$ite]["selCampo_".$value]),$arrDataFiltros_p["dataFiltros"][$ite]["selOperador_".$value],$arrDataFiltros_p["dataFiltros"][$ite]["valor_".$value],$arrDataFiltros_p["dataFiltros"][$ite]["tipo_".$value],$arrDataFiltros_p["dataFiltros"][$ite]["selCondicion_".$value], $arrDataFiltros_p["dataFiltros"][$ite]["cierre".$value]);

                        
                        }else{

                            // Se valida el tipo de filtro y si conincide se traduce el valor numerio a texto debido a que en la vista se guarda como texto
                            if($arrDataFiltros_p["dataFiltros"][$ite]["tipo_".$value] == "estrat_paso" || $arrDataFiltros_p["dataFiltros"][$ite]["tipo_".$value] == "monoef" || $arrDataFiltros_p["dataFiltros"][$ite]["tipo_".$value] ==  "clasi" || $arrDataFiltros_p["dataFiltros"][$ite]["tipo_".$value] ==  "usu" || $arrDataFiltros_p["dataFiltros"][$ite]["tipo_".$value] ==  "estado" && $arrDataFiltros_p["dataFiltros"][$ite]["valor_".$value] != 0 ){
                                $arrDataFiltros_p["dataFiltros"][$ite]["valor_".$value] = getOptionName($arrDataFiltros_p["dataFiltros"][$ite]["valor_".$value], $arrDataFiltros_p["dataFiltros"][$ite]["tipo_".$value]);
                            }

                            $strCondicion_t .=armarCondicion( $arrDataFiltros_p["dataFiltros"][$ite]["selCampo_".$value],$arrDataFiltros_p["dataFiltros"][$ite]["selOperador_".$value],$arrDataFiltros_p["dataFiltros"][$ite]["valor_".$value],$arrDataFiltros_p["dataFiltros"][$ite]["tipo_".$value],$arrDataFiltros_p["dataFiltros"][$ite]["selCondicion_".$value],$arrDataFiltros_p["dataFiltros"][$ite]["cierre".$value]);

                        }
                    }

                }

                $arrFilasPaginas_t = filasPaginas($intIdBd_p,$strCondicion_t,$intLimite_p,'bdpaso',$intIdMuestra_p, $strNameView_t);

                $arrData_t = columnasReporte("bdpaso",$intIdBd_p,$intIdMuestra_p,$intIdTipo_p,$strNameView_t);

                $strColumnasDinamicas_t = $arrData_t[0];

                $strSQLReporte_t = "SELECT ".$strColumnasDinamicas_t." FROM {$BaseDatos}.{$strNameView_t} WHERE (".$strCondicion_t.") ORDER BY ID DESC LIMIT 0,".$intLimite_p;

                $strSQLReporteNoLimit_t = "SELECT ".$strColumnasDinamicas_t." FROM {$BaseDatos}.{$strNameView_t} WHERE (".$strCondicion_t.") ORDER BY ID DESC";

            return [$arrFilasPaginas_t[0],$arrFilasPaginas_t[1],$strSQLReporte_t,$strSQLReporteNoLimit_t,"RESUMEN BASE"];

            break;


        case 'ordenamiento':

            // Obtenemos las condiciones
            $strCondicion_t = "true";

            if ($arrDataFiltros_p["totalFiltros"]>0) {
                $strCondicion_t = "";
                
                foreach ($arrDataFiltros_p["totalFiltros"] as $ite => $value) {
                    $strCondicion_t .=armarCondicion($arrDataFiltros_p["dataFiltros"][$ite]["selCampo_".$value],$arrDataFiltros_p["dataFiltros"][$ite]["selOperador_".$value],$arrDataFiltros_p["dataFiltros"][$ite]["valor_".$value],$arrDataFiltros_p["dataFiltros"][$ite]["tipo_".$value],$arrDataFiltros_p["dataFiltros"][$ite]["selCondicion_".$value], $arrDataFiltros_p["dataFiltros"][$ite]["cierre".$value]);
                }

                // Debido a que me trae AGENTE COMO VARIABLE ME TOCA REDEFINIRLA
                $strCondicion_t = str_replace("AGENTE", "G".$intIdBd_p."_M".$intIdMuestra_p."_ConIntUsu_b", $strCondicion_t);
            }


            $ordenamiento = json_decode(consultasDistribuidor($intIdCBX_p));

            // Valido que haya traido las consultas de ordenamiento
            if(!isset($ordenamiento->objSerializar_t)){
                return [0,0,"","","ORDENAMIENTO"];
            }

            $strSqlDistribuidor = "";

            // Obtengo los datos del select que se van a usar para el reporte
            $arrCampos = obtenerCamposDeConsultaOrdenamiento($ordenamiento->objSerializar_t->strConsultaContactos_t, $intIdBd_p, $intIdMuestra_p);

            $arrAux = [];
            $camposDefectos = "";

            // Creo un string para el select de cada subconsulta
            foreach ($arrCampos as $tipo => $tipoValor) {
                // Recorro cada array
                foreach ($tipoValor as $key => $valor3) {
                    // Valido si la variable ya esta en el array
                    if(!in_array($valor3, $arrAux)){
                        $arrAux[] = $valor3;
                        $camposDefectos .= $valor3 . ",";
                    }
                }
            }
            $camposDefectos = substr($camposDefectos, 0, -1);

            // Traigo las columans del reporte
            $arrData_t = columnasReporte("ordenamiento",$intIdBd_p,$intIdMuestra_p,$intIdTipo_p,null, null, null, $arrCampos);
            $strColumnasDinamicas_t = $arrData_t[0];

            // Si viene la consulta del blend la tengo que rehacer
            if(isset($ordenamiento->objSerializar_t->strConsultaBLEND_t)){

                // Divido todo en partes
                $arrSql = dividirConsultaSql($ordenamiento->objSerializar_t->strConsultaBLEND_t);

                $select = $strColumnasDinamicas_t . ", (@row_number := @row_number + 1) AS row_num, '1' AS TIPO";
                $from = "(SELECT " . $camposDefectos . " FROM " . $arrSql['from'] . " WHERE ".$arrSql['where']." ORDER BY ".$arrSql['order']." ) AS a, (SELECT @row_number := 0) AS b";

                $strSqlDistribuidor .= "(SELECT ".$select." FROM " . $from . ") UNION ";
            }

            // Valido que venga la consulta de la agenda
            if(isset($ordenamiento->objSerializar_t->strConsultaAgendas_t)){

                // Divido todo en partes
                $arrSql = dividirConsultaSql($ordenamiento->objSerializar_t->strConsultaAgendas_t);

                $select = $strColumnasDinamicas_t . ", (@row_number := @row_number + 1) AS row_num, '2' AS TIPO";
                $from = "(SELECT " . $camposDefectos . " FROM " . $arrSql['from'] . " WHERE ".$arrSql['where']." ORDER BY ".$arrSql['order']." ) AS a, (SELECT @row_number := 0) AS b";

                $strSqlDistribuidor .= "(SELECT ".$select." FROM " . $from . ") UNION ";
            }

            // Y agrego la consulta de contactos
            // Divido todo en partes
            $arrSql = dividirConsultaSql($ordenamiento->objSerializar_t->strConsultaContactos_t);

            $select = $strColumnasDinamicas_t . ", (@row_number := @row_number + 1) AS row_num, '3' AS TIPO";
            $from = "(SELECT " . $camposDefectos . " FROM " . $arrSql['from'] . " WHERE ".$arrSql['where']." ORDER BY ".$arrSql['order']." ) AS a, (SELECT @row_number := 0) AS b";

            $strSqlDistribuidor .= "(SELECT ".$select." FROM " . $from . ") ORDER BY TIPO, row_num";

            // Valido si existe G2351_M2490_ConIntUsu_b = -5000
            $strSqlDistribuidor = str_replace("G".$intIdBd_p."_M".$intIdMuestra_p."_ConIntUsu_b = -5000", "({$strCondicion_t})", $strSqlDistribuidor);

            // Reemplazo las variables constantes
            $strSqlDistribuidor = str_replace("'FECHA_DINAMICA_MINPG'", " NOW() ", $strSqlDistribuidor);
            $strSqlDistribuidor = str_replace("'FECHA_DINAMICA_AGENDA'", " NOW() ", $strSqlDistribuidor);

            $arrFilasPaginas_t = filasPaginas($intIdBd_p, $strSqlDistribuidor,$intLimite_p,'ordenamiento',$intIdMuestra_p);

            $strSQLReporte_t = $strSqlDistribuidor . " LIMIT 0,".$intLimite_p;
            $strSQLReporteNoLimit_t = $strSqlDistribuidor;

            return [$arrFilasPaginas_t[0],$arrFilasPaginas_t[1],$strSQLReporte_t,$strSQLReporteNoLimit_t,"ORDENAMIENTO"];

        break;

        case 'gspaso':

            $strNameView_t = nombreVistas($intIdGuion_p);

            $strCondicion_t = "FECHA_CREACION BETWEEN '".$strFechaIn_p."' AND '".$strFechaFn_p."'";

            if ($arrDataFiltros_p["totalFiltros"]>0) {

                $strCondicion_t = "";

                foreach ($arrDataFiltros_p["totalFiltros"] as $ite => $value) {

                    if(preg_match("/^[G][0-9]{3,4}[_][C][0-9]{4,5}$/", $arrDataFiltros_p["dataFiltros"][$ite]["selCampo_".$value])){

                        // Se valida si es un campo de tipo lista, y se obtiene el valor en texto de la opcion
                        if($arrDataFiltros_p["dataFiltros"][$ite]["tipo_".$value] == 6){ 

                            $arrDataFiltros_p["dataFiltros"][$ite]["valor_".$value] = getOptionName($arrDataFiltros_p["dataFiltros"][$ite]["valor_".$value]);

                        }

                        $strCondicion_t .=armarCondicion(getNameColumnView($strNameView_t, $arrDataFiltros_p["dataFiltros"][$ite]["selCampo_".$value]),$arrDataFiltros_p["dataFiltros"][$ite]["selOperador_".$value],$arrDataFiltros_p["dataFiltros"][$ite]["valor_".$value],$arrDataFiltros_p["dataFiltros"][$ite]["tipo_".$value],$arrDataFiltros_p["dataFiltros"][$ite]["selCondicion_".$value],$arrDataFiltros_p["dataFiltros"][$ite]["cierre".$value]);
                    
                    }else{

                        // Se valida el tipo de filtro y si conincide se traduce el valor numerio a texto debido a que en la vista se guarda como texto
                        if($arrDataFiltros_p["dataFiltros"][$ite]["tipo_".$value] == "estrat_paso" || $arrDataFiltros_p["dataFiltros"][$ite]["tipo_".$value] == "monoef" || $arrDataFiltros_p["dataFiltros"][$ite]["tipo_".$value] ==  "clasi" || $arrDataFiltros_p["dataFiltros"][$ite]["tipo_".$value] ==  "usu" || $arrDataFiltros_p["dataFiltros"][$ite]["tipo_".$value] ==  "estado" || $arrDataFiltros_p["dataFiltros"][$ite]["tipo_".$value] ==  "usu" || $arrDataFiltros_p["dataFiltros"][$ite]["selCampo_".$value] ==  "TIPIFICACIN" && $arrDataFiltros_p["dataFiltros"][$ite]["valor_".$value] != 0 ){
                            $arrDataFiltros_p["dataFiltros"][$ite]["valor_".$value] = getOptionName($arrDataFiltros_p["dataFiltros"][$ite]["valor_".$value], $arrDataFiltros_p["dataFiltros"][$ite]["tipo_".$value]);
                        }

                        $strCondicion_t .=armarCondicion( $arrDataFiltros_p["dataFiltros"][$ite]["selCampo_".$value],$arrDataFiltros_p["dataFiltros"][$ite]["selOperador_".$value],$arrDataFiltros_p["dataFiltros"][$ite]["valor_".$value],$arrDataFiltros_p["dataFiltros"][$ite]["tipo_".$value],$arrDataFiltros_p["dataFiltros"][$ite]["selCondicion_".$value],$arrDataFiltros_p["dataFiltros"][$ite]["cierre".$value]);

                    }

                }

            }

            $arrFilasPaginas_t = filasPaginas($intIdBd_p,"Paso = ".$intIdPaso_p." AND ".$strCondicion_t ,$intLimite_p,'gspaso',null,$strNameView_t);

            $arrData_t = columnasReporte("gspaso",$intIdBd_p,$intIdMuestra_p,null,$intIdGuion_p,null,$strNameView_t);

            $strColumnasDinamicas_t = $arrData_t[0];

            
            $strSQLReporte_t = "SELECT ".$strColumnasDinamicas_t." FROM {$BaseDatos}.{$strNameView_t} WHERE (Paso = ".$intIdPaso_p." AND ".$strCondicion_t.") ORDER BY ID DESC LIMIT 0,".$intLimite_p;

            $strSQLReporteNoLimit_t = "SELECT ".$strColumnasDinamicas_t." FROM {$BaseDatos}.{$strNameView_t} WHERE (Paso = ".$intIdPaso_p." AND ".$strCondicion_t.") ORDER BY ID DESC";

            return [$arrFilasPaginas_t[0],$arrFilasPaginas_t[1],$strSQLReporte_t,$strSQLReporteNoLimit_t,"GESTIONES"];

            break;

        case '1':

            $strCondicion_t = "DATE(fecha_hora_inicio) BETWEEN '".$strFechaIn_p."' AND '".$strFechaFn_p."'";

            if ($arrDataFiltros_p["totalFiltros"]>0) {

                $strCondicion_t = "";

                foreach ($arrDataFiltros_p["totalFiltros"] as $ite => $value) {


                    $strCondicion_t .=armarCondicion($arrDataFiltros_p["dataFiltros"][$ite]["selCampo_".$value],$arrDataFiltros_p["dataFiltros"][$ite]["selOperador_".$value],$arrDataFiltros_p["dataFiltros"][$ite]["valor_".$value],$arrDataFiltros_p["dataFiltros"][$ite]["tipo_".$value],$arrDataFiltros_p["dataFiltros"][$ite]["selCondicion_".$value],$arrDataFiltros_p["dataFiltros"][$ite]["cierre".$value]);

                }

            }

            $strSQLReporte_t = "SELECT agente_nombre AS Agente, DATE(fecha_hora_inicio) AS Inicio, DATE_FORMAT(fecha_hora_inicio, '%H') AS Inicio_hora, DATE_FORMAT(fecha_hora_inicio, '%i') AS Inicio_minuto, DATE(fecha_hora_fin) AS Fin, DATE_FORMAT(fecha_hora_fin, '%H') AS Fin_hora, DATE_FORMAT(fecha_hora_fin, '%i') AS Fin_minuto, SUBSTRING_INDEX(SEC_TO_TIME(duracion), '.', 1) AS Duracion_Horas FROM {$BaseDatos_telefonia}.dy_v_historico_sesiones WHERE id_proyecto = (SELECT id FROM {$BaseDatos_telefonia}.dy_proyectos where id_huesped = '{$intIdHuesped_p}') AND ({$strCondicion_t}) GROUP BY sesion_id ORDER BY fecha_hora_inicio DESC LIMIT 0 , {$intLimite_p}";

            $strSQLReporteNoLimit_t = "SELECT agente_nombre AS Agente, DATE(fecha_hora_inicio) AS Inicio, DATE_FORMAT(fecha_hora_inicio, '%H') AS Inicio_hora, DATE_FORMAT(fecha_hora_inicio, '%i') AS Inicio_minuto, DATE(fecha_hora_fin) AS Fin, DATE_FORMAT(fecha_hora_fin, '%H') AS Fin_hora, DATE_FORMAT(fecha_hora_fin, '%i') AS Fin_minuto, SUBSTRING_INDEX(SEC_TO_TIME(duracion), '.', 1) AS Duracion_Horas FROM {$BaseDatos_telefonia}.dy_v_historico_sesiones WHERE id_proyecto = (SELECT id FROM {$BaseDatos_telefonia}.dy_proyectos where id_huesped = '{$intIdHuesped_p}') AND ({$strCondicion_t}) GROUP BY sesion_id ORDER BY fecha_hora_inicio DESC ";

            $arrFilasPaginas_t = filasPaginas(null," id_proyecto = (SELECT id FROM {$BaseDatos_telefonia}.dy_proyectos where id_huesped = '{$intIdHuesped_p}') AND ({$strCondicion_t}) ",$intLimite_p,'1');
            
            return [$arrFilasPaginas_t[0],$arrFilasPaginas_t[1],$strSQLReporte_t,$strSQLReporteNoLimit_t,"SESIONES"];

            break;

        case 'pausas':

            $strCondicion_t = "DATE(fecha_hora_inicio) BETWEEN '".$strFechaIn_p."' AND '".$strFechaFn_p."'";

            if ($arrDataFiltros_p["totalFiltros"]>0) {

                $strCondicion_t = "";

                foreach ($arrDataFiltros_p["totalFiltros"] as $ite => $value) {


                    $strCondicion_t .=armarCondicion($arrDataFiltros_p["dataFiltros"][$ite]["selCampo_".$value],$arrDataFiltros_p["dataFiltros"][$ite]["selOperador_".$value],$arrDataFiltros_p["dataFiltros"][$ite]["valor_".$value],$arrDataFiltros_p["dataFiltros"][$ite]["tipo_".$value],$arrDataFiltros_p["dataFiltros"][$ite]["selCondicion_".$value],$arrDataFiltros_p["dataFiltros"][$ite]["cierre".$value]);

                }

            }

            $strSQLFechas_t = "SELECT DATE(fecha_hora_inicio) AS fecha FROM ".$BaseDatos_telefonia.".dy_v_historico_descansos_por_campana JOIN ".$BaseDatos_systema.".CAMPAN ON CAMPAN_IdCamCbx__b = campana_id JOIN ".$BaseDatos_systema.".ESTPAS ON CAMPAN_ConsInte__b = ESTPAS_ConsInte__CAMPAN_b WHERE ESTPAS_ConsInte__ESTRAT_b = ".$intIdEstrat_p." AND (".$strCondicion_t.") GROUP BY DATE(fecha_hora_inicio) ORDER BY DATE(fecha_hora_inicio) DESC";


            $resSQLFechas_t = $mysqli->query($strSQLFechas_t);


            $strSQLReporte_t = "SELECT 'NO HAY REPORTE DISPONIBLE' AS ERROR";

            $strSQLReporte_t = "SELECT agente_nombre AS AGENTE, tipo_descanso_nombre AS PAUSA";

            while ($obj = $resSQLFechas_t->fetch_object()) {

                $strSQLReporte_t .= ", CAST(SEC_TO_TIME((CASE WHEN DATE(fecha_hora_inicio) = '".$obj->fecha."' THEN duracion ELSE 0 END)) AS TIME(0)) AS '".$obj->fecha."'";

            }

            $strSQLReporte_t .= ", CAST(SEC_TO_TIME(duracion) AS TIME(0)) AS TOTAL FROM ".$BaseDatos_telefonia.".dy_v_historico_descansos_por_campana JOIN ".$BaseDatos_systema.".CAMPAN ON CAMPAN_IdCamCbx__b = campana_id JOIN ".$BaseDatos_systema.".ESTPAS ON CAMPAN_ConsInte__b = ESTPAS_ConsInte__CAMPAN_b WHERE ESTPAS_ConsInte__ESTRAT_b = ".$intIdEstrat_p." AND (".$strCondicion_t.") GROUP BY agente_nombre, tipo_descanso_nombre";


            return [0,0,$strSQLReporte_t,$strSQLReporte_t,"PAUSAS - DURACION POR AGENTE"];

            break;

        case '2':

            $strCondicion_t = "DATE(fecha_hora_inicio) BETWEEN '".$strFechaIn_p."' AND '".$strFechaFn_p."'";

            if ($arrDataFiltros_p["totalFiltros"]>0) {

                $strCondicion_t = "";

                foreach ($arrDataFiltros_p["totalFiltros"] as $ite => $value) {


                    $strCondicion_t .=armarCondicion($arrDataFiltros_p["dataFiltros"][$ite]["selCampo_".$value],$arrDataFiltros_p["dataFiltros"][$ite]["selOperador_".$value],$arrDataFiltros_p["dataFiltros"][$ite]["valor_".$value],$arrDataFiltros_p["dataFiltros"][$ite]["tipo_".$value],$arrDataFiltros_p["dataFiltros"][$ite]["selCondicion_".$value],$arrDataFiltros_p["dataFiltros"][$ite]["cierre".$value]);

                }

            } 

            $strSQLReporte_t = "SELECT agente_nombre AS Agente, DATE(fecha_hora_inicio) AS Inicio, DATE_FORMAT(fecha_hora_inicio,'%H') AS Inicio_hora, DATE_FORMAT(fecha_hora_inicio,'%i') AS Inicio_minuto, DATE(fecha_hora_fin) AS Fin, DATE_FORMAT(fecha_hora_fin,'%H') AS Fin_hora, DATE_FORMAT(fecha_hora_fin,'%i') AS Fin_minuto, DATE_FORMAT(SEC_TO_TIME(duracion),'%H:%i:%s') as Duracion_Pausa, tipo_descanso_nombre AS TipoPausa, comentario FROM ".$BaseDatos_telefonia.".dy_v_historico_descansos_por_campana WHERE campana_id in (SELECT CAMPAN_IdCamCbx__b FROM ".$BaseDatos_systema.".ESTPAS JOIN ".$BaseDatos_systema.".CAMPAN ON ESTPAS_ConsInte__CAMPAN_b = CAMPAN_ConsInte__b WHERE ESTPAS_ConsInte__ESTRAT_b IN (SELECT ESTRAT_ConsInte__b FROM DYALOGOCRM_SISTEMA.ESTRAT WHERE ESTRAT_ConsInte__PROYEC_b = '".$intIdHuesped_p."')) AND (".$strCondicion_t.") GROUP BY descanso_id ORDER BY fecha_hora_inicio DESC LIMIT 0,".$intLimite_p;

            $strSQLReporteNoLimit_t = "SELECT agente_nombre AS Agente, DATE(fecha_hora_inicio) AS Inicio, DATE_FORMAT(fecha_hora_inicio,'%H') AS Inicio_hora, DATE_FORMAT(fecha_hora_inicio,'%i') AS Inicio_minuto, DATE(fecha_hora_fin) AS Fin, DATE_FORMAT(fecha_hora_fin,'%H') AS Fin_hora, DATE_FORMAT(fecha_hora_fin,'%i') AS Fin_minuto, DATE_FORMAT(SEC_TO_TIME(duracion),'%H:%i:%s') as Duracion_Pausa, tipo_descanso_nombre AS TipoPausa, comentario FROM ".$BaseDatos_telefonia.".dy_v_historico_descansos_por_campana WHERE campana_id in (SELECT CAMPAN_IdCamCbx__b FROM ".$BaseDatos_systema.".ESTPAS JOIN ".$BaseDatos_systema.".CAMPAN ON ESTPAS_ConsInte__CAMPAN_b = CAMPAN_ConsInte__b WHERE ESTPAS_ConsInte__ESTRAT_b IN (SELECT ESTRAT_ConsInte__b FROM DYALOGOCRM_SISTEMA.ESTRAT WHERE ESTRAT_ConsInte__PROYEC_b = '".$intIdHuesped_p."')) AND (".$strCondicion_t.") GROUP BY descanso_id ORDER BY fecha_hora_inicio DESC ";

            $strSQLReportePaginas_t = "SELECT COUNT(1) AS cantidad FROM (SELECT COUNT(1) AS cantidad FROM ".$BaseDatos_telefonia.".dy_v_historico_descansos_por_campana WHERE campana_id in (SELECT CAMPAN_IdCamCbx__b FROM ".$BaseDatos_systema.".ESTPAS JOIN ".$BaseDatos_systema.".CAMPAN ON ESTPAS_ConsInte__CAMPAN_b = CAMPAN_ConsInte__b WHERE ESTPAS_ConsInte__ESTRAT_b IN (SELECT ESTRAT_ConsInte__b FROM DYALOGOCRM_SISTEMA.ESTRAT WHERE ESTRAT_ConsInte__PROYEC_b = '".$intIdHuesped_p."')) AND (".$strCondicion_t.") GROUP BY descanso_id ORDER BY fecha_hora_inicio DESC) AS a";

            $arrFilasPaginas_t = filasPaginas($strSQLReportePaginas_t,$strCondicion_t,$intLimite_p,'2');

            return [$arrFilasPaginas_t[0],$arrFilasPaginas_t[1],$strSQLReporte_t,$strSQLReporteNoLimit_t,"PAUSAS"];

            break;

        case '3':

            //JDBD - Consultamos la cantidad de registros a retornar.
            $strSQLCantidadFilas_t = "SELECT COUNT(1) AS cantidad FROM ".$BaseDatos_systema.".qryUSUARI_usuarios_agentes LEFT JOIN ".$BaseDatos_systema.".qrySesionesDelDia ON IdAgente = qrySesionesDelDia.agente_id WHERE (NOT (HoraInicialDefinida is null)) AND HoraInicialDefinida < date_format(current_time, '%H:%i:%S') and USUARI_ConsInte__PROYEC_b = ".$intIdHuesped_p." AND qrySesionesDelDia.agente_id IS NULL Order By USUARI_Nombre____b";

            $intCantidadFilas_t = $mysqli->query($strSQLCantidadFilas_t)->fetch_object()->cantidad;

            $intCantidadPaginas = ceil($intCantidadFilas_t/$intLimite_p);

            $strSQLReporte_t = "select USUARI_ConsInte__b as Id, USUARI_Nombre____b as Nombre, USUARI_Correo___b as Correo, HoraInicialDefinida FROM ".$BaseDatos_systema.".qryUSUARI_usuarios_agentes LEFT JOIN ".$BaseDatos_systema.".qrySesionesDelDia ON IdAgente = qrySesionesDelDia.agente_id WHERE (NOT (HoraInicialDefinida is null)) AND HoraInicialDefinida < date_format(current_time, '%H:%i:%S') and USUARI_ConsInte__PROYEC_b = ".$intIdHuesped_p." AND qrySesionesDelDia.agente_id IS NULL Order By USUARI_Nombre____b DESC LIMIT 0,".$intLimite_p;

            $strSQLReporteNoLimit_t = "select USUARI_ConsInte__b as Id, USUARI_Nombre____b as Nombre, USUARI_Correo___b as Correo, HoraInicialDefinida FROM ".$BaseDatos_systema.".qryUSUARI_usuarios_agentes LEFT JOIN ".$BaseDatos_systema.".qrySesionesDelDia ON IdAgente = qrySesionesDelDia.agente_id WHERE (NOT (HoraInicialDefinida is null)) AND HoraInicialDefinida < date_format(current_time, '%H:%i:%S') and USUARI_ConsInte__PROYEC_b = ".$intIdHuesped_p." AND qrySesionesDelDia.agente_id IS NULL Order By USUARI_Nombre____b DESC ";

            return [$intCantidadFilas_t,$intCantidadPaginas,$strSQLReporte_t,$strSQLReporteNoLimit_t,"NO REGISTRARON"];

            break;
        case '4':

            $strSQLCantidadFilas_t = "SELECT COUNT(1) AS cantidad FROM ".$BaseDatos_systema.".qryUSUARI_usuarios_agentes JOIN ".$BaseDatos_systema.".qrySesionesDelDia ON IdAgente = qrySesionesDelDia.agente_id WHERE  HoraInicio > HoraInicialDefinida AND USUARI_ConsInte__PROYEC_b = ".$intIdHuesped_p." Order By USUARI_Nombre____b";

            $intCantidadFilas_t = $mysqli->query($strSQLCantidadFilas_t)->fetch_object()->cantidad;

            $intCantidadPaginas = ceil($intCantidadFilas_t/$intLimite_p);

            $strSQLReporte_t = "select USUARI_ConsInte__b as Id, USUARI_Nombre____b as Nombre, USUARI_Correo___b as Correo, date_format(timediff(HoraInicio, HoraInicialDefinida), '%H:%i:%S') as Retraso, HoraInicialDefinida, HoraInicio as HoraInicialReal FROM ".$BaseDatos_systema.".qryUSUARI_usuarios_agentes JOIN ".$BaseDatos_systema.".qrySesionesDelDia ON IdAgente = qrySesionesDelDia.agente_id WHERE  HoraInicio > HoraInicialDefinida AND USUARI_ConsInte__PROYEC_b = ".$intIdHuesped_p." Order By USUARI_Nombre____b DESC LIMIT 0,".$intLimite_p;

            $strSQLReporteNoLimit_t = "select USUARI_ConsInte__b as Id, USUARI_Nombre____b as Nombre, USUARI_Correo___b as Correo, date_format(timediff(HoraInicio, HoraInicialDefinida), '%H:%i:%S') as Retraso, HoraInicialDefinida, HoraInicio as HoraInicialReal FROM ".$BaseDatos_systema.".qryUSUARI_usuarios_agentes JOIN ".$BaseDatos_systema.".qrySesionesDelDia ON IdAgente = qrySesionesDelDia.agente_id WHERE  HoraInicio > HoraInicialDefinida AND USUARI_ConsInte__PROYEC_b = ".$intIdHuesped_p." Order By USUARI_Nombre____b DESC ";

            return [$intCantidadFilas_t,$intCantidadPaginas,$strSQLReporte_t,$strSQLReporteNoLimit_t,"LLEGARON TARDE"];

            break;
        case '5':

            $strSQLCantidadFilas_t = "SELECT COUNT(1) AS cantidad FROM ".$BaseDatos_systema.".qryUSUARI_usuarios_agentes JOIN ".$BaseDatos_systema.".qrySesionesDelDia ON IdAgente = qrySesionesDelDia.agente_id WHERE  HoraInicio <= HoraInicialDefinida and USUARI_ConsInte__PROYEC_b = ".$intIdHuesped_p." order by USUARI_Nombre____b";

            $intCantidadFilas_t = $mysqli->query($strSQLCantidadFilas_t)->fetch_object()->cantidad;

            $intCantidadPaginas = ceil($intCantidadFilas_t/$intLimite_p);

            $strSQLReporte_t = "select USUARI_ConsInte__b as Id, USUARI_Nombre____b as Nombre, USUARI_Correo___b as Correo, HoraInicialDefinida, HoraInicio as HoraInicialReal FROM ".$BaseDatos_systema.".qryUSUARI_usuarios_agentes JOIN ".$BaseDatos_systema.".qrySesionesDelDia ON IdAgente = qrySesionesDelDia.agente_id WHERE  HoraInicio <= HoraInicialDefinida and USUARI_ConsInte__PROYEC_b = ".$intIdHuesped_p." order by USUARI_Nombre____b DESC LIMIT 0,".$intLimite_p;

            $strSQLReporteNoLimit_t = "select USUARI_ConsInte__b as Id, USUARI_Nombre____b as Nombre, USUARI_Correo___b as Correo, HoraInicialDefinida, HoraInicio as HoraInicialReal FROM ".$BaseDatos_systema.".qryUSUARI_usuarios_agentes JOIN ".$BaseDatos_systema.".qrySesionesDelDia ON IdAgente = qrySesionesDelDia.agente_id WHERE  HoraInicio <= HoraInicialDefinida and USUARI_ConsInte__PROYEC_b = ".$intIdHuesped_p." order by USUARI_Nombre____b DESC ";

            return [$intCantidadFilas_t,$intCantidadPaginas,$strSQLReporte_t,$strSQLReporteNoLimit_t,"LLEGARON A TIEMPO"];

            break;
        case '6':

            $strSQLCantidadFilas_t = "SELECT COUNT(1) AS cantidad FROM ".$BaseDatos_systema.".qryUSUARI_usuarios_agentes JOIN ".$BaseDatos_systema.".qrySesionesDelDia ON IdAgente = qrySesionesDelDia.agente_id WHERE  HoraFin < HoraFinalDefinida and USUARI_ConsInte__PROYEC_b = ".$intIdHuesped_p." Order By USUARI_Nombre____b";

            $intCantidadFilas_t = $mysqli->query($strSQLCantidadFilas_t)->fetch_object()->cantidad;

            $intCantidadPaginas = ceil($intCantidadFilas_t/$intLimite_p);

            $strSQLReporte_t = "select USUARI_ConsInte__b as Id, USUARI_Nombre____b as Nombre, USUARI_Correo___b as Correo, date_format(timediff(HoraFinalDefinida, HoraFin), '%H:%i:%S') as TiempoFaltante, HoraFinalDefinida, HoraFin as HoraFinalReal FROM ".$BaseDatos_systema.".qryUSUARI_usuarios_agentes JOIN ".$BaseDatos_systema.".qrySesionesDelDia ON IdAgente = qrySesionesDelDia.agente_id WHERE  HoraFin < HoraFinalDefinida and USUARI_ConsInte__PROYEC_b = ".$intIdHuesped_p." Order By USUARI_Nombre____b DESC LIMIT 0,".$intLimite_p;

            $strSQLReporteNoLimit_t = "select USUARI_ConsInte__b as Id, USUARI_Nombre____b as Nombre, USUARI_Correo___b as Correo, date_format(timediff(HoraFinalDefinida, HoraFin), '%H:%i:%S') as TiempoFaltante, HoraFinalDefinida, HoraFin as HoraFinalReal FROM ".$BaseDatos_systema.".qryUSUARI_usuarios_agentes JOIN ".$BaseDatos_systema.".qrySesionesDelDia ON IdAgente = qrySesionesDelDia.agente_id WHERE  HoraFin < HoraFinalDefinida and USUARI_ConsInte__PROYEC_b = ".$intIdHuesped_p." Order By USUARI_Nombre____b DESC ";

            return [$intCantidadFilas_t,$intCantidadPaginas,$strSQLReporte_t,$strSQLReporteNoLimit_t,"SE FUERON ANTES"];

            break;
        case '7':

            $strSQLCantidadFilas_t = "SELECT COUNT(1) AS cantidad FROM ".$BaseDatos_systema.".qryUSUARI_usuarios_agentes JOIN ".$BaseDatos_systema.".qrySesionesDelDia ON IdAgente = qrySesionesDelDia.agente_id WHERE  HoraFin >= HoraFinalDefinida and USUARI_ConsInte__PROYEC_b = ".$intIdHuesped_p." Order By USUARI_Nombre____b";

            $intCantidadFilas_t = $mysqli->query($strSQLCantidadFilas_t)->fetch_object()->cantidad;

            $intCantidadPaginas = ceil($intCantidadFilas_t/$intLimite_p);

            $strSQLReporte_t = "select USUARI_ConsInte__b as Id, USUARI_Nombre____b as Nombre, USUARI_Correo___b as Correo, HoraFinalDefinida, HoraFin as HoraFinalReal FROM ".$BaseDatos_systema.".qryUSUARI_usuarios_agentes JOIN ".$BaseDatos_systema.".qrySesionesDelDia ON IdAgente = qrySesionesDelDia.agente_id WHERE  HoraFin >= HoraFinalDefinida and USUARI_ConsInte__PROYEC_b = ".$intIdHuesped_p." Order By USUARI_Nombre____b DESC LIMIT 0,".$intLimite_p;

            $strSQLReporteNoLimit_t = "select USUARI_ConsInte__b as Id, USUARI_Nombre____b as Nombre, USUARI_Correo___b as Correo, HoraFinalDefinida, HoraFin as HoraFinalReal FROM ".$BaseDatos_systema.".qryUSUARI_usuarios_agentes JOIN ".$BaseDatos_systema.".qrySesionesDelDia ON IdAgente = qrySesionesDelDia.agente_id WHERE  HoraFin >= HoraFinalDefinida and USUARI_ConsInte__PROYEC_b = ".$intIdHuesped_p." Order By USUARI_Nombre____b DESC ";

            return [$intCantidadFilas_t,$intCantidadPaginas,$strSQLReporte_t,$strSQLReporteNoLimit_t,"SE FUERON A TIEMPO"];

            break;
        case '8':

            $strSQLCantidadFilas_t = "SELECT COUNT(1) AS cantidad FROM ".$BaseDatos_systema.".qryUSUARI_usuarios_agentes JOIN ".$BaseDatos_systema.".qrySesionesDelDia ON IdAgente = qrySesionesDelDia.agente_id WHERE  timediff(HoraFinalDefinida, HoraInicialDefinida) > date_format(timediff(HoraFin, HoraInicio), '%H:%i:%S') and USUARI_ConsInte__PROYEC_b = ".$intIdHuesped_p."  Order By USUARI_Nombre____b";

            $intCantidadFilas_t = $mysqli->query($strSQLCantidadFilas_t)->fetch_object()->cantidad;

            $intCantidadPaginas = ceil($intCantidadFilas_t/$intLimite_p);

            $strSQLReporte_t = "select USUARI_ConsInte__b as Id, USUARI_Nombre____b as Nombre, USUARI_Correo___b as Correo, date_format(timediff(timediff(HoraFinalDefinida, HoraInicialDefinida), timediff(HoraFin, HoraInicio)), '%H:%i:%S') as TiempoFaltante, date_format(timediff(HoraFinalDefinida, HoraInicialDefinida), '%H:%i:%S') as DuracionDefinida, date_format(timediff(HoraFin, HoraInicio), '%H:%i:%S') as DuracionReal, HoraInicialDefinida, HoraInicio as HoraInicialReal, HoraFinalDefinida, HoraFin as HoraFinalReal FROM ".$BaseDatos_systema.".qryUSUARI_usuarios_agentes JOIN ".$BaseDatos_systema.".qrySesionesDelDia ON IdAgente = qrySesionesDelDia.agente_id WHERE  timediff(HoraFinalDefinida, HoraInicialDefinida) > date_format(timediff(HoraFin, HoraInicio), '%H:%i:%S') and USUARI_ConsInte__PROYEC_b = ".$intIdHuesped_p."  Order By USUARI_Nombre____b DESC LIMIT 0,".$intLimite_p;

            $strSQLReporteNoLimit_t = "select USUARI_ConsInte__b as Id, USUARI_Nombre____b as Nombre, USUARI_Correo___b as Correo, date_format(timediff(timediff(HoraFinalDefinida, HoraInicialDefinida), timediff(HoraFin, HoraInicio)), '%H:%i:%S') as TiempoFaltante, date_format(timediff(HoraFinalDefinida, HoraInicialDefinida), '%H:%i:%S') as DuracionDefinida, date_format(timediff(HoraFin, HoraInicio), '%H:%i:%S') as DuracionReal, HoraInicialDefinida, HoraInicio as HoraInicialReal, HoraFinalDefinida, HoraFin as HoraFinalReal FROM ".$BaseDatos_systema.".qryUSUARI_usuarios_agentes JOIN ".$BaseDatos_systema.".qrySesionesDelDia ON IdAgente = qrySesionesDelDia.agente_id WHERE  timediff(HoraFinalDefinida, HoraInicialDefinida) > date_format(timediff(HoraFin, HoraInicio), '%H:%i:%S') and USUARI_ConsInte__PROYEC_b = ".$intIdHuesped_p."  Order By USUARI_Nombre____b DESC ";

            return [$intCantidadFilas_t,$intCantidadPaginas,$strSQLReporte_t,$strSQLReporteNoLimit_t,"SESIONES CORTAS"];

            break;
        case '9':

            $strSQLCantidadFilas_t = "SELECT COUNT(1) AS cantidad FROM ".$BaseDatos_systema.".qryUSUARI_usuarios_agentes JOIN ".$BaseDatos_systema.".qrySesionesDelDia ON IdAgente = qrySesionesDelDia.agente_id WHERE  timediff(HoraFinalDefinida, HoraInicialDefinida) <= date_format(timediff(HoraFin, HoraInicio), '%H:%i:%S') and USUARI_ConsInte__PROYEC_b = ".$intIdHuesped_p." Order By USUARI_Nombre____b";

            $intCantidadFilas_t = $mysqli->query($strSQLCantidadFilas_t)->fetch_object()->cantidad;

            $intCantidadPaginas = ceil($intCantidadFilas_t/$intLimite_p);

            $strSQLReporte_t = "select USUARI_ConsInte__b as Id, USUARI_Nombre____b as Nombre, USUARI_Correo___b as Correo, date_format(timediff(HoraFinalDefinida, HoraInicialDefinida), '%H:%i:%S') as DuracionDefinida, date_format(timediff(HoraFin, HoraInicio), '%H:%i:%S') as DuracionReal, HoraInicialDefinida, HoraInicio as HoraInicialReal, HoraFinalDefinida, HoraFin as HoraFinalReal FROM ".$BaseDatos_systema.".qryUSUARI_usuarios_agentes JOIN ".$BaseDatos_systema.".qrySesionesDelDia ON IdAgente = qrySesionesDelDia.agente_id WHERE  timediff(HoraFinalDefinida, HoraInicialDefinida) <= date_format(timediff(HoraFin, HoraInicio), '%H:%i:%S') and USUARI_ConsInte__PROYEC_b = ".$intIdHuesped_p." Order By USUARI_Nombre____b DESC LIMIT 0,".$intLimite_p;

            $strSQLReporteNoLimit_t = "select USUARI_ConsInte__b as Id, USUARI_Nombre____b as Nombre, USUARI_Correo___b as Correo, date_format(timediff(HoraFinalDefinida, HoraInicialDefinida), '%H:%i:%S') as DuracionDefinida, date_format(timediff(HoraFin, HoraInicio), '%H:%i:%S') as DuracionReal, HoraInicialDefinida, HoraInicio as HoraInicialReal, HoraFinalDefinida, HoraFin as HoraFinalReal FROM ".$BaseDatos_systema.".qryUSUARI_usuarios_agentes JOIN ".$BaseDatos_systema.".qrySesionesDelDia ON IdAgente = qrySesionesDelDia.agente_id WHERE  timediff(HoraFinalDefinida, HoraInicialDefinida) <= date_format(timediff(HoraFin, HoraInicio), '%H:%i:%S') and USUARI_ConsInte__PROYEC_b = ".$intIdHuesped_p." Order By USUARI_Nombre____b DESC ";

            return [$intCantidadFilas_t,$intCantidadPaginas,$strSQLReporte_t,$strSQLReporteNoLimit_t,"SESIONES DURACION OK"];

            break;
        case '10':

            $strSQLCantidadFilas_t = "SELECT COUNT(1) AS cantidad FROM ".$BaseDatos_systema.".qryPausasProgramadasDelDia left join dyalogo_telefonia.dy_v_historico_descansos ON IdAgente = agente_id and USUPAU_PausasId_b = tipo_descanso_id WHERE fecha_hora_inicio >= current_date() and USUPAU_Tipo_b = 1 and timediff(HoraFinalProgramada, HoraInicialProgramada) < timediff(fecha_hora_fin,fecha_hora_inicio) and USUARI_ConsInte__PROYEC_b = ".$intIdHuesped_p." Order by USUARI_Nombre____b";

            $intCantidadFilas_t = $mysqli->query($strSQLCantidadFilas_t)->fetch_object()->cantidad;

            $intCantidadPaginas = ceil($intCantidadFilas_t/$intLimite_p);

            $strSQLReporte_t = "select USUARI_ConsInte__b as Id, USUARI_Nombre____b as Nombre, USUARI_Correo___b as Correo, tipo_descanso_nombre as Pausa, timediff(timediff(fecha_hora_fin,fecha_hora_inicio), timediff(HoraFinalProgramada, HoraInicialProgramada)) as Exceso, timediff(HoraFinalProgramada, HoraInicialProgramada) DuracionProgramada, timediff(fecha_hora_fin,fecha_hora_inicio) as DuracionReal, HoraInicialProgramada, date_format(fecha_hora_inicio, '%H:%i:%S') as HoraInicialReal, HoraFinalProgramada, date_format(fecha_hora_fin , '%H:%i:%S') as HoraFinalReal FROM ".$BaseDatos_systema.".qryPausasProgramadasDelDia left join dyalogo_telefonia.dy_v_historico_descansos ON IdAgente = agente_id and USUPAU_PausasId_b = tipo_descanso_id WHERE fecha_hora_inicio >= current_date() and USUPAU_Tipo_b = 1 and timediff(HoraFinalProgramada, HoraInicialProgramada) < timediff(fecha_hora_fin,fecha_hora_inicio) and USUARI_ConsInte__PROYEC_b = ".$intIdHuesped_p." Order by USUARI_Nombre____b DESC LIMIT 0,".$intLimite_p;

            $strSQLReporteNoLimit_t = "select USUARI_ConsInte__b as Id, USUARI_Nombre____b as Nombre, USUARI_Correo___b as Correo, tipo_descanso_nombre as Pausa, timediff(timediff(fecha_hora_fin,fecha_hora_inicio), timediff(HoraFinalProgramada, HoraInicialProgramada)) as Exceso, timediff(HoraFinalProgramada, HoraInicialProgramada) DuracionProgramada, timediff(fecha_hora_fin,fecha_hora_inicio) as DuracionReal, HoraInicialProgramada, date_format(fecha_hora_inicio, '%H:%i:%S') as HoraInicialReal, HoraFinalProgramada, date_format(fecha_hora_fin , '%H:%i:%S') as HoraFinalReal FROM ".$BaseDatos_systema.".qryPausasProgramadasDelDia left join dyalogo_telefonia.dy_v_historico_descansos ON IdAgente = agente_id and USUPAU_PausasId_b = tipo_descanso_id WHERE fecha_hora_inicio >= current_date() and USUPAU_Tipo_b = 1 and timediff(HoraFinalProgramada, HoraInicialProgramada) < timediff(fecha_hora_fin,fecha_hora_inicio) and USUARI_ConsInte__PROYEC_b = ".$intIdHuesped_p." Order by USUARI_Nombre____b DESC ";

            return [$intCantidadFilas_t,$intCantidadPaginas,$strSQLReporte_t,$strSQLReporteNoLimit_t,"PausasConHorarioMuyLargas"];

            break;
        case '11':

            $strSQLCantidadFilas_t = "SELECT COUNT(1) AS cantidad FROM ".$BaseDatos_systema.".qryPausasProgramadasDelDia left join dyalogo_telefonia.dy_v_historico_descansos ON IdAgente = agente_id and USUPAU_PausasId_b = tipo_descanso_id WHERE fecha_hora_inicio >= current_date() and USUPAU_Tipo_b = 1 and timediff(HoraFinalProgramada, HoraInicialProgramada) >= timediff(fecha_hora_fin,fecha_hora_inicio) and USUARI_ConsInte__PROYEC_b = ".$intIdHuesped_p." Order by USUARI_Nombre____b";

            $intCantidadFilas_t = $mysqli->query($strSQLCantidadFilas_t)->fetch_object()->cantidad;

            $intCantidadPaginas = ceil($intCantidadFilas_t/$intLimite_p);

            $strSQLReporte_t = "select USUARI_ConsInte__b as Id, USUARI_Nombre____b as Nombre, USUARI_Correo___b as Correo, tipo_descanso_nombre as Pausa, timediff(timediff(HoraFinalProgramada, HoraInicialProgramada), timediff(fecha_hora_fin,fecha_hora_inicio)) as TiempoAFavor, timediff(HoraFinalProgramada, HoraInicialProgramada) DuracionProgramada, timediff(fecha_hora_fin,fecha_hora_inicio) as DuracionReal, HoraInicialProgramada, date_format(fecha_hora_inicio, '%H:%i:%S') as HoraInicialReal, HoraFinalProgramada, date_format(fecha_hora_fin , '%H:%i:%S') as HoraFinalReal FROM ".$BaseDatos_systema.".qryPausasProgramadasDelDia left join dyalogo_telefonia.dy_v_historico_descansos ON IdAgente = agente_id and USUPAU_PausasId_b = tipo_descanso_id WHERE fecha_hora_inicio >= current_date() and USUPAU_Tipo_b = 1 and timediff(HoraFinalProgramada, HoraInicialProgramada) >= timediff(fecha_hora_fin,fecha_hora_inicio) and USUARI_ConsInte__PROYEC_b = ".$intIdHuesped_p." Order by USUARI_Nombre____b DESC LIMIT 0,".$intLimite_p;

            $strSQLReporteNoLimit_t = "select USUARI_ConsInte__b as Id, USUARI_Nombre____b as Nombre, USUARI_Correo___b as Correo, tipo_descanso_nombre as Pausa, timediff(timediff(HoraFinalProgramada, HoraInicialProgramada), timediff(fecha_hora_fin,fecha_hora_inicio)) as TiempoAFavor, timediff(HoraFinalProgramada, HoraInicialProgramada) DuracionProgramada, timediff(fecha_hora_fin,fecha_hora_inicio) as DuracionReal, HoraInicialProgramada, date_format(fecha_hora_inicio, '%H:%i:%S') as HoraInicialReal, HoraFinalProgramada, date_format(fecha_hora_fin , '%H:%i:%S') as HoraFinalReal FROM ".$BaseDatos_systema.".qryPausasProgramadasDelDia left join dyalogo_telefonia.dy_v_historico_descansos ON IdAgente = agente_id and USUPAU_PausasId_b = tipo_descanso_id WHERE fecha_hora_inicio >= current_date() and USUPAU_Tipo_b = 1 and timediff(HoraFinalProgramada, HoraInicialProgramada) >= timediff(fecha_hora_fin,fecha_hora_inicio) and USUARI_ConsInte__PROYEC_b = ".$intIdHuesped_p." Order by USUARI_Nombre____b DESC ";

            return [$intCantidadFilas_t,$intCantidadPaginas,$strSQLReporte_t,$strSQLReporteNoLimit_t,"PausasConHorarioDuracionOk"];

            break;
        case '12':

            $strSQLCantidadFilas_t = "SELECT COUNT(1) AS cantidad FROM ".$BaseDatos_systema.".qryPausasProgramadasDelDia left join dyalogo_telefonia.dy_v_historico_descansos ON IdAgente = agente_id and USUPAU_PausasId_b = tipo_descanso_id WHERE fecha_hora_inicio >= current_date() and USUPAU_Tipo_b = 1 and (HoraInicialProgramada > date_format(fecha_hora_inicio, '%H:%i:%S') or date_format(fecha_hora_fin , '%H:%i:%S') > HoraFinalProgramada) and USUARI_ConsInte__PROYEC_b = ".$intIdHuesped_p." Order by USUARI_Nombre____b";

            $intCantidadFilas_t = $mysqli->query($strSQLCantidadFilas_t)->fetch_object()->cantidad;

            $intCantidadPaginas = ceil($intCantidadFilas_t/$intLimite_p);

            $strSQLReporte_t = "select USUARI_ConsInte__b as Id, USUARI_Nombre____b as Nombre, USUARI_Correo___b as Correo, tipo_descanso_nombre as Pausa, IF(HoraInicialProgramada > date_format(fecha_hora_inicio, '%H:%i:%S'), timediff(HoraInicialProgramada, date_format(fecha_hora_inicio, '%H:%i:%S')),null) as SalioAntesPor, IF(date_format(fecha_hora_fin , '%H:%i:%S') > HoraFinalProgramada, timediff(date_format(fecha_hora_fin , '%H:%i:%S'), HoraFinalProgramada),null) as LlegoTardePor, HoraInicialProgramada, date_format(fecha_hora_inicio, '%H:%i:%S') as HoraInicialReal, HoraFinalProgramada, date_format(fecha_hora_fin , '%H:%i:%S') as HoraFinalReal, timediff(timediff(fecha_hora_fin,fecha_hora_inicio), timediff(HoraFinalProgramada, HoraInicialProgramada)) as TiempoDiferencia, timediff(HoraFinalProgramada, HoraInicialProgramada) DuracionProgramada, timediff(fecha_hora_fin,fecha_hora_inicio) as DuracionReal FROM ".$BaseDatos_systema.".qryPausasProgramadasDelDia left join dyalogo_telefonia.dy_v_historico_descansos ON IdAgente = agente_id and USUPAU_PausasId_b = tipo_descanso_id WHERE fecha_hora_inicio >= current_date() and USUPAU_Tipo_b = 1 and (HoraInicialProgramada > date_format(fecha_hora_inicio, '%H:%i:%S') or date_format(fecha_hora_fin , '%H:%i:%S') > HoraFinalProgramada) and USUARI_ConsInte__PROYEC_b = ".$intIdHuesped_p." Order by USUARI_Nombre____b DESC LIMIT 0,".$intLimite_p;

            $strSQLReporteNoLimit_t = "select USUARI_ConsInte__b as Id, USUARI_Nombre____b as Nombre, USUARI_Correo___b as Correo, tipo_descanso_nombre as Pausa, IF(HoraInicialProgramada > date_format(fecha_hora_inicio, '%H:%i:%S'), timediff(HoraInicialProgramada, date_format(fecha_hora_inicio, '%H:%i:%S')),null) as SalioAntesPor, IF(date_format(fecha_hora_fin , '%H:%i:%S') > HoraFinalProgramada, timediff(date_format(fecha_hora_fin , '%H:%i:%S'), HoraFinalProgramada),null) as LlegoTardePor, HoraInicialProgramada, date_format(fecha_hora_inicio, '%H:%i:%S') as HoraInicialReal, HoraFinalProgramada, date_format(fecha_hora_fin , '%H:%i:%S') as HoraFinalReal, timediff(timediff(fecha_hora_fin,fecha_hora_inicio), timediff(HoraFinalProgramada, HoraInicialProgramada)) as TiempoDiferencia, timediff(HoraFinalProgramada, HoraInicialProgramada) DuracionProgramada, timediff(fecha_hora_fin,fecha_hora_inicio) as DuracionReal FROM ".$BaseDatos_systema.".qryPausasProgramadasDelDia left join dyalogo_telefonia.dy_v_historico_descansos ON IdAgente = agente_id and USUPAU_PausasId_b = tipo_descanso_id WHERE fecha_hora_inicio >= current_date() and USUPAU_Tipo_b = 1 and (HoraInicialProgramada > date_format(fecha_hora_inicio, '%H:%i:%S') or date_format(fecha_hora_fin , '%H:%i:%S') > HoraFinalProgramada) and USUARI_ConsInte__PROYEC_b = ".$intIdHuesped_p." Order by USUARI_Nombre____b DESC ";

            return [$intCantidadFilas_t,$intCantidadPaginas,$strSQLReporte_t,$strSQLReporteNoLimit_t,"PausasConHorarioIncumplidas"];

            break;
        case '13':

            $strSQLCantidadFilas_t = "SELECT COUNT(1) AS cantidad FROM ".$BaseDatos_systema.".qryPausasProgramadasDelDia left join dyalogo_telefonia.dy_v_historico_descansos ON IdAgente = agente_id and USUPAU_PausasId_b = tipo_descanso_id WHERE fecha_hora_inicio >= current_date() and USUPAU_Tipo_b = 1 and (HoraInicialProgramada <= date_format(fecha_hora_inicio, '%H:%i:%S') and date_format(fecha_hora_fin , '%H:%i:%S') <= HoraFinalProgramada) and USUARI_ConsInte__PROYEC_b = ".$intIdHuesped_p." Order by USUARI_Nombre____b";

            $intCantidadFilas_t = $mysqli->query($strSQLCantidadFilas_t)->fetch_object()->cantidad;

            $intCantidadPaginas = ceil($intCantidadFilas_t/$intLimite_p);

            $strSQLReporte_t = "select USUARI_ConsInte__b as Id, USUARI_Nombre____b as Nombre, USUARI_Correo___b as Correo, tipo_descanso_nombre as Pausa, IF(HoraInicialProgramada > date_format(fecha_hora_inicio, '%H:%i:%S'),timediff(HoraInicialProgramada, date_format(fecha_hora_inicio, '%H:%i:%S')),null) as SalioAntesPor, IF(date_format(fecha_hora_fin , '%H:%i:%S') > HoraFinalProgramada, timediff(date_format(fecha_hora_fin, '%H:%i:%S'), HoraFinalProgramada),null) as LlegoTardePor, HoraInicialProgramada, date_format(fecha_hora_inicio, '%H:%i:%S') as HoraInicialReal, HoraFinalProgramada, date_format(fecha_hora_fin , '%H:%i:%S') as HoraFinalReal, timediff(timediff(fecha_hora_fin,fecha_hora_inicio), timediff(HoraFinalProgramada, HoraInicialProgramada)) as TiempoDiferencia, timediff(HoraFinalProgramada, HoraInicialProgramada) DuracionProgramada, timediff(fecha_hora_fin,fecha_hora_inicio) as DuracionReal FROM ".$BaseDatos_systema.".qryPausasProgramadasDelDia left join dyalogo_telefonia.dy_v_historico_descansos ON IdAgente = agente_id and USUPAU_PausasId_b = tipo_descanso_id WHERE fecha_hora_inicio >= current_date() and USUPAU_Tipo_b = 1 and (HoraInicialProgramada <= date_format(fecha_hora_inicio, '%H:%i:%S') and date_format(fecha_hora_fin , '%H:%i:%S') <= HoraFinalProgramada) and USUARI_ConsInte__PROYEC_b = ".$intIdHuesped_p." Order by USUARI_Nombre____b DESC LIMIT 0,".$intLimite_p;

            $strSQLReporteNoLimit_t = "select USUARI_ConsInte__b as Id, USUARI_Nombre____b as Nombre, USUARI_Correo___b as Correo, tipo_descanso_nombre as Pausa, IF(HoraInicialProgramada > date_format(fecha_hora_inicio, '%H:%i:%S'),timediff(HoraInicialProgramada, date_format(fecha_hora_inicio, '%H:%i:%S')),null) as SalioAntesPor, IF(date_format(fecha_hora_fin , '%H:%i:%S') > HoraFinalProgramada, timediff(date_format(fecha_hora_fin, '%H:%i:%S'), HoraFinalProgramada),null) as LlegoTardePor, HoraInicialProgramada, date_format(fecha_hora_inicio, '%H:%i:%S') as HoraInicialReal, HoraFinalProgramada, date_format(fecha_hora_fin , '%H:%i:%S') as HoraFinalReal, timediff(timediff(fecha_hora_fin,fecha_hora_inicio), timediff(HoraFinalProgramada, HoraInicialProgramada)) as TiempoDiferencia, timediff(HoraFinalProgramada, HoraInicialProgramada) DuracionProgramada, timediff(fecha_hora_fin,fecha_hora_inicio) as DuracionReal FROM ".$BaseDatos_systema.".qryPausasProgramadasDelDia left join dyalogo_telefonia.dy_v_historico_descansos ON IdAgente = agente_id and USUPAU_PausasId_b = tipo_descanso_id WHERE fecha_hora_inicio >= current_date() and USUPAU_Tipo_b = 1 and (HoraInicialProgramada <= date_format(fecha_hora_inicio, '%H:%i:%S') and date_format(fecha_hora_fin , '%H:%i:%S') <= HoraFinalProgramada) and USUARI_ConsInte__PROYEC_b = ".$intIdHuesped_p." Order by USUARI_Nombre____b DESC ";

            return [$intCantidadFilas_t,$intCantidadPaginas,$strSQLReporte_t,$strSQLReporteNoLimit_t,"PausasConHorarioCumplidas"];

            break;
        case '14':

            $strSQLCantidadFilas_t = "SELECT COUNT(1) AS cantidad, DuracionMaxima FROM ".$BaseDatos_systema.".qryPausasProgramadasDelDia left join dyalogo_telefonia.dy_v_historico_descansos ON IdAgente = agente_id and USUPAU_PausasId_b = tipo_descanso_id WHERE fecha_hora_inicio >= current_date() and USUARI_ConsInte__PROYEC_b = ".$intIdHuesped_p." and USUPAU_Tipo_b = 0 group by USUARI_ConsInte__b having time_to_sec(DuracionMaxima) < sum(time_to_sec(timediff(fecha_hora_fin,fecha_hora_inicio)))Order by USUARI_Nombre____b";

            $intCantidadFilas_t = mysqli_query($mysqli, $strSQLCantidadFilas_t);
            $result = mysqli_fetch_object($intCantidadFilas_t);
            if (mysqli_num_rows($intCantidadFilas_t) > 0) {
                $intCantidadFilas_t = intval(json_encode($result->cantidad));
            }else {
                $result = new \stdClass;
                $intCantidadFilas_t = json_encode($result->cantidad = 0);
            }
            
            $intCantidadPaginas = ceil($intCantidadFilas_t/$intLimite_p);

            $strSQLReporte_t = "select USUARI_ConsInte__b as Id, USUARI_Nombre____b as Nombre, USUARI_Correo___b as Correo, tipo_descanso_nombre as Pausa, timediff(sec_to_time(sum(time_to_sec(timediff(fecha_hora_fin,fecha_hora_inicio)))), DuracionMaxima) as Exceso, DuracionMaxima, sec_to_time(sum(time_to_sec(timediff(fecha_hora_fin,fecha_hora_inicio)))) as DuracionReal, count(USUARI_ConsInte__b) as CantidadReal FROM ".$BaseDatos_systema.".qryPausasProgramadasDelDia left join dyalogo_telefonia.dy_v_historico_descansos ON IdAgente = agente_id and USUPAU_PausasId_b = tipo_descanso_id WHERE fecha_hora_inicio >= current_date() and USUARI_ConsInte__PROYEC_b = ".$intIdHuesped_p." and USUPAU_Tipo_b = 0 group by USUARI_ConsInte__b having time_to_sec(DuracionMaxima) < sum(time_to_sec(timediff(fecha_hora_fin,fecha_hora_inicio)))Order by USUARI_Nombre____b DESC LIMIT 0,".$intLimite_p;

            $strSQLReporteNoLimit_t = "select USUARI_ConsInte__b as Id, USUARI_Nombre____b as Nombre, USUARI_Correo___b as Correo, tipo_descanso_nombre as Pausa, timediff(sec_to_time(sum(time_to_sec(timediff(fecha_hora_fin,fecha_hora_inicio)))), DuracionMaxima) as Exceso, DuracionMaxima, sec_to_time(sum(time_to_sec(timediff(fecha_hora_fin,fecha_hora_inicio)))) as DuracionReal, count(USUARI_ConsInte__b) as CantidadReal FROM ".$BaseDatos_systema.".qryPausasProgramadasDelDia left join dyalogo_telefonia.dy_v_historico_descansos ON IdAgente = agente_id and USUPAU_PausasId_b = tipo_descanso_id WHERE fecha_hora_inicio >= current_date() and USUARI_ConsInte__PROYEC_b = ".$intIdHuesped_p." and USUPAU_Tipo_b = 0 group by USUARI_ConsInte__b having time_to_sec(DuracionMaxima) < sum(time_to_sec(timediff(fecha_hora_fin,fecha_hora_inicio)))Order by USUARI_Nombre____b DESC ";

            return [$intCantidadFilas_t,$intCantidadPaginas,$strSQLReporte_t,$strSQLReporteNoLimit_t,"PausasSinHorarioMuyLargas"];

            break;
        case '15':

            $strSQLCantidadFilas_t = "SELECT COUNT(1) AS cantidad, CantidadMaxima FROM ".$BaseDatos_systema.".qryPausasProgramadasDelDia left join dyalogo_telefonia.dy_v_historico_descansos ON IdAgente = agente_id and USUPAU_PausasId_b = tipo_descanso_id WHERE fecha_hora_inicio >= current_date() and USUPAU_Tipo_b = 0 and USUARI_ConsInte__PROYEC_b = ".$intIdHuesped_p." group by USUARI_ConsInte__b having CantidadMaxima < count(USUARI_ConsInte__b) Order by USUARI_Nombre____b";

            $intCantidadFilas_t = mysqli_query($mysqli, $strSQLCantidadFilas_t);
            $result = mysqli_fetch_object($intCantidadFilas_t);
            if (mysqli_num_rows($intCantidadFilas_t) > 0) {
                $intCantidadFilas_t = intval(json_encode($result->cantidad));
            }else {
                $result = new \stdClass;
                $intCantidadFilas_t = json_encode($result->cantidad = 0);
            }

            $intCantidadPaginas = ceil($intCantidadFilas_t/$intLimite_p);

            $strSQLReporte_t = "select USUARI_ConsInte__b as Id, USUARI_Nombre____b as Nombre, USUARI_Correo___b as Correo, tipo_descanso_nombre as Pausa, count(USUARI_ConsInte__b) - CantidadMaxima as VecesDeMas, count(USUARI_ConsInte__b) as CantidadReal, CantidadMaxima, sec_to_time(sum(time_to_sec(timediff(fecha_hora_fin,fecha_hora_inicio)))) as DuracionReal, DuracionMaxima FROM ".$BaseDatos_systema.".qryPausasProgramadasDelDia left join dyalogo_telefonia.dy_v_historico_descansos ON IdAgente = agente_id and USUPAU_PausasId_b = tipo_descanso_id WHERE fecha_hora_inicio >= current_date() and USUPAU_Tipo_b = 0 and USUARI_ConsInte__PROYEC_b = ".$intIdHuesped_p." group by USUARI_ConsInte__b having CantidadMaxima < count(USUARI_ConsInte__b) Order by USUARI_Nombre____b DESC LIMIT 0,".$intLimite_p;

            $strSQLReporteNoLimit_t = "select USUARI_ConsInte__b as Id, USUARI_Nombre____b as Nombre, USUARI_Correo___b as Correo, tipo_descanso_nombre as Pausa, count(USUARI_ConsInte__b) - CantidadMaxima as VecesDeMas, count(USUARI_ConsInte__b) as CantidadReal, CantidadMaxima, sec_to_time(sum(time_to_sec(timediff(fecha_hora_fin,fecha_hora_inicio)))) as DuracionReal, DuracionMaxima FROM ".$BaseDatos_systema.".qryPausasProgramadasDelDia left join dyalogo_telefonia.dy_v_historico_descansos ON IdAgente = agente_id and USUPAU_PausasId_b = tipo_descanso_id WHERE fecha_hora_inicio >= current_date() and USUPAU_Tipo_b = 0 and USUARI_ConsInte__PROYEC_b = ".$intIdHuesped_p." group by USUARI_ConsInte__b having CantidadMaxima < count(USUARI_ConsInte__b) Order by USUARI_Nombre____b DESC ";

            return [$intCantidadFilas_t,$intCantidadPaginas,$strSQLReporte_t,$strSQLReporteNoLimit_t,"PausasSinHorarioMuchasVeces"];

            break;
        case '16':

            $strSQLCantidadFilas_t = "SELECT  COUNT(1) AS cantidad, DuracionMaxima, CantidadMaxima FROM ".$BaseDatos_systema.".qryPausasProgramadasDelDia left join dyalogo_telefonia.dy_v_historico_descansos ON IdAgente = agente_id and USUPAU_PausasId_b = tipo_descanso_id WHERE fecha_hora_inicio >= current_date() and USUARI_ConsInte__PROYEC_b = ".$intIdHuesped_p." and USUPAU_Tipo_b = 0 group by USUARI_ConsInte__b having time_to_sec(DuracionMaxima) >= sum(time_to_sec(timediff(fecha_hora_fin,fecha_hora_inicio))) and CantidadMaxima >= count(USUARI_ConsInte__b) Order by USUARI_Nombre____b";

             $intCantidadFilas_t = mysqli_query($mysqli, $strSQLCantidadFilas_t);
             $result = mysqli_fetch_object($intCantidadFilas_t);
             if (mysqli_num_rows($intCantidadFilas_t) > 0) {
                 $intCantidadFilas_t = intval(json_encode($result->cantidad));
             }else {
                 $result = new \stdClass;
                 $intCantidadFilas_t = json_encode($result->cantidad = 0);
             }

            $intCantidadPaginas = ceil($intCantidadFilas_t/$intLimite_p);

            $strSQLReporte_t = "select USUARI_ConsInte__b as Id, USUARI_Nombre____b as Nombre, USUARI_Correo___b as Correo, tipo_descanso_nombre as Pausa, sec_to_time(sum(time_to_sec(timediff(fecha_hora_fin,fecha_hora_inicio)))) as DuracionReal, DuracionMaxima, count(USUARI_ConsInte__b) as CantidadReal, CantidadMaxima FROM ".$BaseDatos_systema.".qryPausasProgramadasDelDia left join dyalogo_telefonia.dy_v_historico_descansos ON IdAgente = agente_id and USUPAU_PausasId_b = tipo_descanso_id WHERE fecha_hora_inicio >= current_date() and USUARI_ConsInte__PROYEC_b = ".$intIdHuesped_p." and USUPAU_Tipo_b = 0 group by USUARI_ConsInte__b having time_to_sec(DuracionMaxima) >= sum(time_to_sec(timediff(fecha_hora_fin,fecha_hora_inicio))) and CantidadMaxima >= count(USUARI_ConsInte__b) Order by USUARI_Nombre____b DESC LIMIT 0,".$intLimite_p;

            $strSQLReporteNoLimit_t = "select USUARI_ConsInte__b as Id, USUARI_Nombre____b as Nombre, USUARI_Correo___b as Correo, tipo_descanso_nombre as Pausa, sec_to_time(sum(time_to_sec(timediff(fecha_hora_fin,fecha_hora_inicio)))) as DuracionReal, DuracionMaxima, count(USUARI_ConsInte__b) as CantidadReal, CantidadMaxima FROM ".$BaseDatos_systema.".qryPausasProgramadasDelDia left join dyalogo_telefonia.dy_v_historico_descansos ON IdAgente = agente_id and USUPAU_PausasId_b = tipo_descanso_id WHERE fecha_hora_inicio >= current_date() and USUARI_ConsInte__PROYEC_b = ".$intIdHuesped_p." and USUPAU_Tipo_b = 0 group by USUARI_ConsInte__b having time_to_sec(DuracionMaxima) >= sum(time_to_sec(timediff(fecha_hora_fin,fecha_hora_inicio))) and CantidadMaxima >= count(USUARI_ConsInte__b) Order by USUARI_Nombre____b DESC ";

            return [$intCantidadFilas_t,$intCantidadPaginas,$strSQLReporte_t,$strSQLReporteNoLimit_t,"PausasSinHorarioOK"];

            break;
        case '17':

            $strSQLCantidadFilas_t = "SELECT COUNT(1) AS cantidad FROM ".$BaseDatos_systema.".qryUSUARI_usuarios_agentes WHERE USUARI_ConsInte__PROYEC_b = ".$intIdHuesped_p." and HoraInicialDefinida IS NULL Order By USUARI_Nombre____b";

            $intCantidadFilas_t = $mysqli->query($strSQLCantidadFilas_t)->fetch_object()->cantidad;

            $intCantidadPaginas = ceil($intCantidadFilas_t/$intLimite_p);

            $strSQLReporte_t = "select USUARI_ConsInte__b as Id, USUARI_Nombre____b as Nombre, USUARI_Correo___b as Correo FROM ".$BaseDatos_systema.".qryUSUARI_usuarios_agentes WHERE USUARI_ConsInte__PROYEC_b = ".$intIdHuesped_p." and HoraInicialDefinida IS NULL Order By USUARI_Nombre____b DESC LIMIT 0,".$intLimite_p;

            $strSQLReporteNoLimit_t = "select USUARI_ConsInte__b as Id, USUARI_Nombre____b as Nombre, USUARI_Correo___b as Correo FROM ".$BaseDatos_systema.".qryUSUARI_usuarios_agentes WHERE USUARI_ConsInte__PROYEC_b = ".$intIdHuesped_p." and HoraInicialDefinida IS NULL Order By USUARI_Nombre____b DESC ";

            return [$intCantidadFilas_t,$intCantidadPaginas,$strSQLReporte_t,$strSQLReporteNoLimit_t,"AGENTES SIN MALLA DEFINIDA"];

            break;
        case 'gsbot':

            // Traigo el id de la tabla del bot de gestiones

            $tablaGestionBotId = 0;
            
            $sql = "SELECT * FROM {$dyalogo_canales_electronicos}.dy_bot WHERE id_estpas = {$intIdPaso_p}";
            $res = $mysqli->query($sql);
            if($res && $res->num_rows > 0){
                $data = $res->fetch_object();
                $tablaGestionBotId = $data->id_guion_gestion;
            }

            $strCondicion_t = "DATE(G".$tablaGestionBotId."_FechaInsercion) BETWEEN '".$strFechaIn_p."' AND '".$strFechaFn_p."'";

            if ($arrDataFiltros_p["totalFiltros"]>0) {

                $strCondicion_t = "";
                foreach ($arrDataFiltros_p["totalFiltros"] as $ite => $value) {
                    $strCondicion_t .=armarCondicion($arrDataFiltros_p["dataFiltros"][$ite]["selCampo_".$value],$arrDataFiltros_p["dataFiltros"][$ite]["selOperador_".$value],$arrDataFiltros_p["dataFiltros"][$ite]["valor_".$value],$arrDataFiltros_p["dataFiltros"][$ite]["tipo_".$value],$arrDataFiltros_p["dataFiltros"][$ite]["selCondicion_".$value],$arrDataFiltros_p["dataFiltros"][$ite]["cierre".$value]);
                }
            }

            $arrFilasPaginas_t = filasPaginas($tablaGestionBotId, "(G".$tablaGestionBotId."_Paso = ".$intIdPaso_p." OR G".$tablaGestionBotId."_Paso = '' OR G".$tablaGestionBotId."_Paso IS NULL) AND ".$strCondicion_t,$intLimite_p,'gsbot', null, $tablaGestionBotId);

            $arrData_t = columnasReporte("gsbot", $tablaGestionBotId, $intIdMuestra_p, null, $tablaGestionBotId, $intIdPaso_p);

            $strColumnasDinamicas_t = $arrData_t[0];

            $strSQLReporte_t = "SELECT ".$strColumnasDinamicas_t." FROM ".$BaseDatos.".G".$tablaGestionBotId." WHERE (G".$tablaGestionBotId."_Paso = ".$intIdPaso_p." OR G".$tablaGestionBotId."_Paso = '' OR G".$tablaGestionBotId."_Paso IS NULL) AND (".$strCondicion_t.") ORDER BY G".$tablaGestionBotId."_ConsInte__b DESC LIMIT 0,".$intLimite_p;

            $strSQLReporteNoLimit_t = "SELECT ".$strColumnasDinamicas_t." FROM ".$BaseDatos.".G".$tablaGestionBotId." WHERE (G".$tablaGestionBotId."_Paso = ".$intIdPaso_p." OR G".$tablaGestionBotId."_Paso = '' OR G".$tablaGestionBotId."_Paso IS NULL) AND (".$strCondicion_t.") ORDER BY G".$tablaGestionBotId."_ConsInte__b DESC ";

            return [$arrFilasPaginas_t[0],$arrFilasPaginas_t[1],$strSQLReporte_t,$strSQLReporteNoLimit_t,"GESTIONES_BOT"];

            break;
        
        case 'opcionesUsadasBot':

            // Tenemos el paso entonces podemos traer en base a este el id del bot
            $bot = null;
            $sql = "SELECT * FROM {$dyalogo_canales_electronicos}.dy_bot WHERE id_estpas = {$intIdPaso_p}";
            $res = $mysqli->query($sql);
            if($res && $res->num_rows > 0){
                $bot = $res->fetch_object();
            }

            // Se arman las condiciones teniendo por defecto una condicion de fecha
            $strCondicion_t = "DATE(fecha_hora) BETWEEN '".$strFechaIn_p."' AND '".$strFechaFn_p."'";

            if ($arrDataFiltros_p["totalFiltros"]>0) {
                $strCondicion_t = "";
                foreach ($arrDataFiltros_p["totalFiltros"] as $ite => $value) {
                    $strCondicion_t .= armarCondicion($arrDataFiltros_p["dataFiltros"][$ite]["selCampo_".$value],$arrDataFiltros_p["dataFiltros"][$ite]["selOperador_".$value],$arrDataFiltros_p["dataFiltros"][$ite]["valor_".$value],$arrDataFiltros_p["dataFiltros"][$ite]["tipo_".$value],$arrDataFiltros_p["dataFiltros"][$ite]["selCondicion_".$value],$arrDataFiltros_p["dataFiltros"][$ite]["cierre".$value]);
                }
            }

            // Esto para traer la cantidad de registros y las filas para el paginado
            $arrFilasPaginas_t = filasPaginas($bot->id, $strCondicion_t, $intLimite_p, 'opcionesUsadasBot', null, null);

            // Trae las columnas que se usaran en el reporte
            $arrData_t = columnasReporte("opcionesUsadasBot", $bot->id, null, null, null, null);

            $strColumnasDinamicas_t = $arrData_t[0];

            $strSQLReporte_t = "SELECT ".$strColumnasDinamicas_t." FROM ".$dyalogo_canales_electronicos.".dy_chat_opciones_usadas a LEFT JOIN {$dyalogo_canales_electronicos}.dy_base_autorespuestas_contenidos b ON a.id_respuesta_bot = b.id WHERE id_bot = {$bot->id} AND ({$strCondicion_t}) GROUP BY id_seccion, id_respuesta_bot ORDER BY CANTIDAD DESC LIMIT 0,".$intLimite_p;

            $strSQLReporteNoLimit_t = "SELECT ".$strColumnasDinamicas_t." FROM ".$dyalogo_canales_electronicos.".dy_chat_opciones_usadas a LEFT JOIN {$dyalogo_canales_electronicos}.dy_base_autorespuestas_contenidos b ON a.id_respuesta_bot = b.id WHERE id_bot = {$bot->id} AND ({$strCondicion_t}) GROUP BY id_seccion, id_respuesta_bot ORDER BY CANTIDAD DESC ";

            return [$arrFilasPaginas_t[0], $arrFilasPaginas_t[1], $strSQLReporte_t, $strSQLReporteNoLimit_t, "OPCIONES USADAS BOT"];

            break;

        case "marcador":

            // Este caso trae el reporte del marcador de cada campaña

            // Se crea la consulta respectiva

            $strCondicion_t = "contexto = 'PDSD_{$intIdBd_p}' AND id_marcador_muestra = {$intIdPaso_p} ";

            $arrFilasPaginas_t = filasPaginas(null, $strCondicion_t ,$intLimite_p,'marcador');



            $strSQLReporte_t = "SELECT ID, DATE(fecha_hora) AS FECHA, TIME(fecha_hora) AS HORA, RESPUESTA, CANAL, ID_CONTACTO, TELEFONO_MARCADO, traza_completa AS TRAZA,  IDENTIFICADOR_CONTACTO FROM {$BaseDatos_telefonia}.dy_marcador_log WHERE {$strCondicion_t} ORDER BY ID DESC LIMIT 0,".$intLimite_p;

            $strSQLReporteNoLimit_t = "SELECT ID, DATE(fecha_hora) AS FECHA, TIME(fecha_hora) AS HORA, RESPUESTA, CANAL, ID_CONTACTO, TELEFONO_MARCADO, traza_completa AS TRAZA,  IDENTIFICADOR_CONTACTO FROM {$BaseDatos_telefonia}.dy_marcador_log WHERE {$strCondicion_t} ORDER BY ID DESC";

            return [$arrFilasPaginas_t[0],$arrFilasPaginas_t[1],$strSQLReporte_t,$strSQLReporteNoLimit_t,"MARCADOR"];

            break;



            case 'comMail':

                // Traigo el id de cuenta del correo electronico
                
                $sql = "SELECT CORREO_ENTRANTE_Cuenta_b AS cuenta FROM {$BaseDatos_systema}.CORREO_ENTRANTE WHERE CORREO_ENTRANTE_ConsInte__ESTPAS_b = {$intIdPaso_p}";
                $res = $mysqli->query($sql);
                if($res && $res->num_rows > 0){
                    $data = $res->fetch_object();
                    $idCuentaCorreo = $data->cuenta;
                }

                // Se obtiene los filtros conectados al paso 
                $strCondicionFiltros = 'AND ( ';
                
                $sqlFiltros = "SELECT id FROM {$dyalogo_canales_electronicos}.dy_ce_filtros where id_estpas = {$intIdPaso_p}";
                $resFiltros = $mysqli->query($sqlFiltros);
                if($resFiltros && $resFiltros->num_rows > 0){
                    $strSeparador = " ";
                    while ($idFiltro = $resFiltros->fetch_object()->id) {
                        $strCondicionFiltros .= $strSeparador."ID_FILTRO_MAIL = '{$idFiltro}'";
                        $strSeparador = " OR ";
                    }
                    $strCondicionFiltros .= ' )';
                }else {
                    $strCondicionFiltros = '';
                }

                $strCondicion_t = "DATE(FECHA_CREACION_DY) BETWEEN '".$strFechaIn_p."' AND '".$strFechaFn_p."'";
    
                if ($arrDataFiltros_p["totalFiltros"]>0) {

    
                    $strCondicion_t = "";
                    foreach ($arrDataFiltros_p["totalFiltros"] as $ite => $value) {

                        // Se valida el tipo de filtro y si conincide se traduce el valor numerio a texto debido a que en la vista se guarda como texto
                        if($arrDataFiltros_p["dataFiltros"][$ite]["tipo_".$value] == "usu_tel"){
                            $arrDataFiltros_p["dataFiltros"][$ite]["valor_".$value] = getOptionName($arrDataFiltros_p["dataFiltros"][$ite]["valor_".$value], $arrDataFiltros_p["dataFiltros"][$ite]["tipo_".$value]);
                        }


                        $strCondicion_t .=armarCondicion($arrDataFiltros_p["dataFiltros"][$ite]["selCampo_".$value],$arrDataFiltros_p["dataFiltros"][$ite]["selOperador_".$value],$arrDataFiltros_p["dataFiltros"][$ite]["valor_".$value],$arrDataFiltros_p["dataFiltros"][$ite]["tipo_".$value],$arrDataFiltros_p["dataFiltros"][$ite]["selCondicion_".$value],$arrDataFiltros_p["dataFiltros"][$ite]["cierre".$value]);
                    }
                }

                // Se realiza el conteo total de registros

                $arrFilasPaginas_t = filasPaginas(null, "ID_CUENTA_MAIL = {$idCuentaCorreo} ".$strCondicionFiltros."  AND ".$strCondicion_t ,$intLimite_p,'comMail', null, null);
                
                $strColumnasReport = " ID_COMUNICACION, FECHA_INGRESO, ANHO_INGRESO, MES_INGRESO, DIA_INGRESO, HORA_INGRESO, DE AS CORREO_ORIGEN, PASO_ASIGNADO, AGENTE_ASIGNADO, LINK_CONTENIDO , PARA, CC, CCO, ASUNTO, ESTADO, LEIDO, ESTADO_GESTION, FECHA_INGRESO_MAIL, FECHA_ASIGNACION, FECHA_ACCION_PROCESADA, FECHA_PASO_COLA, FECHA_RESPUESTA, CC_RESPUESTA, DURACION_GESTION ";
    
                $strSQLReporte_t = "SELECT {$strColumnasReport} FROM {$dyalogo_canales_electronicos}.DY_V_REPORTE_CE_ENTRANTES WHERE ID_CUENTA_MAIL = {$idCuentaCorreo} {$strCondicionFiltros} AND {$strCondicion_t} ORDER BY ID_COMUNICACION DESC LIMIT 0, ".$intLimite_p;

                $strSQLReporteNoLimit_t = "SELECT {$strColumnasReport} FROM {$dyalogo_canales_electronicos}.DY_V_REPORTE_CE_ENTRANTES WHERE ID_CUENTA_MAIL = {$idCuentaCorreo} {$strCondicionFiltros} AND {$strCondicion_t} ORDER BY ID_COMUNICACION DESC  ";

                return [$arrFilasPaginas_t[0],$arrFilasPaginas_t[1],$strSQLReporte_t,$strSQLReporteNoLimit_t,"CANAL COMUNICACION MAIL"];
    
                break;

            case 'comWebForm':

                // Se trae el id del webform dependiendo del paso

                $sql = "SELECT WEBFORM_Consinte__b AS id FROM {$BaseDatos_systema}.WEBFORM WHERE WEBFORM_ConsInte__ESTPAS_b = {$intIdPaso_p}";
                $res = $mysqli->query($sql);
                if($res && $res->num_rows > 0){
                    $data = $res->fetch_object();
                    $idWebForm = $data->id;
                }


                $strCondicion_t = "DATE(FECHA_CREACION_DY) BETWEEN '".$strFechaIn_p."' AND '".$strFechaFn_p."'";
    
                if ($arrDataFiltros_p["totalFiltros"]>0) {

    
                    $strCondicion_t = "";
                    foreach ($arrDataFiltros_p["totalFiltros"] as $ite => $value) {

                        // Se valida el tipo de filtro y si conincide se traduce el valor numerio a texto debido a que en la vista se guarda como texto
                        if($arrDataFiltros_p["dataFiltros"][$ite]["tipo_".$value] == "usu_tel"){
                            $arrDataFiltros_p["dataFiltros"][$ite]["valor_".$value] = getOptionName($arrDataFiltros_p["dataFiltros"][$ite]["valor_".$value], $arrDataFiltros_p["dataFiltros"][$ite]["tipo_".$value]);
                        }


                        $strCondicion_t .=armarCondicion($arrDataFiltros_p["dataFiltros"][$ite]["selCampo_".$value],$arrDataFiltros_p["dataFiltros"][$ite]["selOperador_".$value],$arrDataFiltros_p["dataFiltros"][$ite]["valor_".$value],$arrDataFiltros_p["dataFiltros"][$ite]["tipo_".$value],$arrDataFiltros_p["dataFiltros"][$ite]["selCondicion_".$value],$arrDataFiltros_p["dataFiltros"][$ite]["cierre".$value]);
                    }
                }
            
                $arrFilasPaginas_t = filasPaginas(null, "PARA = {$idWebForm}  AND ".$strCondicion_t ,$intLimite_p,'comMail', null, null);
                
                $strColumnasReport = " ID_COMUNICACION, DE AS ID_BD ,FECHA_INGRESO, ANHO_INGRESO, MES_INGRESO, DIA_INGRESO, HORA_INGRESO, CONTACTO_CRM AS DATO_CONTACTO, PASO_ASIGNADO, AGENTE_ASIGNADO, LINK_CONTENIDO , CC AS CORREO_PARA_RESPUESTA, ESTADO, LEIDO, ESTADO_GESTION, FECHA_ASIGNACION, FECHA_ACCION_PROCESADA, FECHA_PASO_COLA, FECHA_RESPUESTA, CC_RESPUESTA, DURACION_GESTION ";
    
                $strSQLReporte_t = "SELECT {$strColumnasReport} FROM {$dyalogo_canales_electronicos}.DY_V_REPORTE_CE_ENTRANTES WHERE PARA = {$idWebForm} AND {$strCondicion_t} ORDER BY ID_COMUNICACION DESC LIMIT 0, ".$intLimite_p;

                $strSQLReporteNoLimit_t = "SELECT {$strColumnasReport} FROM {$dyalogo_canales_electronicos}.DY_V_REPORTE_CE_ENTRANTES WHERE PARA = {$idWebForm} AND {$strCondicion_t} ORDER BY ID_COMUNICACION DESC  ";

    
                return [$arrFilasPaginas_t[0],$arrFilasPaginas_t[1],$strSQLReporte_t,$strSQLReporteNoLimit_t,"CANAL COMUNICACION WEBFORM"];
    
                break;


            case 'comSms':

                $intIdPasoSaliente_t = 0;

                $sql = "SELECT ESTCON_ConsInte__ESTPAS_Des_b AS pasoSaliente FROM {$BaseDatos_systema}.ESTCON WHERE ESTCON_ConsInte__ESTPAS_Has_b = {$intIdPaso_p}";
                $res = $mysqli->query($sql);
                if($res && $res->num_rows > 0){
                    $data = $res->fetch_object();
                    $intIdPasoSaliente_t = $data->pasoSaliente;
                }

                $strCondicion_t = "DATE(FECHA_CREACION_DY) BETWEEN '".$strFechaIn_p."' AND '".$strFechaFn_p."'";
    
                if ($arrDataFiltros_p["totalFiltros"]>0) {

    
                    $strCondicion_t = "";
                    foreach ($arrDataFiltros_p["totalFiltros"] as $ite => $value) {

                        $strCondicion_t .=armarCondicion($arrDataFiltros_p["dataFiltros"][$ite]["selCampo_".$value],$arrDataFiltros_p["dataFiltros"][$ite]["selOperador_".$value],$arrDataFiltros_p["dataFiltros"][$ite]["valor_".$value],$arrDataFiltros_p["dataFiltros"][$ite]["tipo_".$value],$arrDataFiltros_p["dataFiltros"][$ite]["selCondicion_".$value],$arrDataFiltros_p["dataFiltros"][$ite]["cierre".$value]);
                    }
                }

                // Se realiza el conteo total de registros
                
                $arrFilasPaginas_t = filasPaginas(null, "PASO = {$intIdPasoSaliente_t}  AND ".$strCondicion_t ,$intLimite_p,'comSms', null, null);
                
                $strColumnasReport = " ID_COMUNICACION, ID_BD, FECHA_INGRESO, ANHO_INGRESO, MES_INGRESO,DIA_INGRESO, HORA_INGRESO, DATO_CONTACTO, TEXTO_1, TEXTO_2, PROVEEDOR,  FECHA_INGRESO_PROVEEDOR ";
                
                $strSQLReporte_t = "SELECT {$strColumnasReport} FROM dy_sms.DY_REPORT_SMS_ENTRANTES WHERE PASO = {$intIdPasoSaliente_t} AND {$strCondicion_t} ORDER BY ID_COMUNICACION DESC LIMIT 0, ".$intLimite_p;

                $strSQLReporteNoLimit_t = "SELECT {$strColumnasReport} FROM dy_sms.DY_REPORT_SMS_ENTRANTES WHERE PASO = {$intIdPasoSaliente_t} AND {$strCondicion_t} ORDER BY ID_COMUNICACION DESC    ";
    
                return [$arrFilasPaginas_t[0],$arrFilasPaginas_t[1],$strSQLReporte_t,$strSQLReporteNoLimit_t,"CANAL COMUNICACION SMS"];
    
                break;


            case 'comChat':


                // Se obtiene el id de configuracion asociado al paso

                $strCondicionConfiguracion  = " ( ";

                $sqlIdConfiguracion = "SELECT id FROM {$dyalogo_canales_electronicos}.dy_chat_configuracion WHERE id_estpas = {$intIdPaso_p}";
                $resIdConfiguracion = $mysqli->query($sqlIdConfiguracion);
                if($resIdConfiguracion && $resIdConfiguracion->num_rows > 0){
                    $strSeparador = " ";
                    while ($idConfiguracion = $resIdConfiguracion->fetch_object()->id) {
                        $strCondicionConfiguracion .= $strSeparador." ID_CONFIGURACION = '{$idConfiguracion}'";
                        $strSeparador = " OR ";
                    }
                    $strCondicionConfiguracion .= ' )';
                }else {
                    $strCondicionConfiguracion = 'ID_CONFIGURACION = -1';
                }


                $strCondicion_t = "DATE(FECHA_CREACION_DY) BETWEEN '".$strFechaIn_p."' AND '".$strFechaFn_p."'";
    
                if ($arrDataFiltros_p["totalFiltros"]>0) {

    
                    $strCondicion_t = "";
                    foreach ($arrDataFiltros_p["totalFiltros"] as $ite => $value) {

                        $strCondicion_t .=armarCondicion($arrDataFiltros_p["dataFiltros"][$ite]["selCampo_".$value],$arrDataFiltros_p["dataFiltros"][$ite]["selOperador_".$value],$arrDataFiltros_p["dataFiltros"][$ite]["valor_".$value],$arrDataFiltros_p["dataFiltros"][$ite]["tipo_".$value],$arrDataFiltros_p["dataFiltros"][$ite]["selCondicion_".$value],$arrDataFiltros_p["dataFiltros"][$ite]["cierre".$value]);
                    }
                }


                // Se realiza el conteo total de registros
                
                $arrFilasPaginas_t = filasPaginas(null, $strCondicionConfiguracion." AND ".$strCondicion_t ,$intLimite_p,'comChat', null, null);
                
                $strColumnasReport = " ID_COMUNICACION, FECHA_INGRESO, ANHO_INGRESO, MES_INGRESO, DIA_INGRESO, HORA_INGRESO, DATO_CONTACTO, PASO_ASIGNADO, AGENTE, LINK_CONTENIDO, FECHA_ASIGNACION, FECHA_PASO_COLA, FECHA_FINALIZACION, ESTADO, ESTADO_GESTION, DURACION_GESTION , DATOS_INICIALES, EN_ESPERA, TIEMPO_ESPERA ";

                $strSQLReporte_t = "SELECT {$strColumnasReport} FROM {$dyalogo_canales_electronicos}.DY_V_REPORTE_CHAT_ENTRANTES WHERE {$strCondicionConfiguracion} AND {$strCondicion_t} ORDER BY ID_COMUNICACION DESC LIMIT 0, ".$intLimite_p;

                $strSQLReporteNoLimit_t = "SELECT {$strColumnasReport} FROM {$dyalogo_canales_electronicos}.DY_V_REPORTE_CHAT_ENTRANTES WHERE {$strCondicionConfiguracion} AND {$strCondicion_t} ORDER BY ID_COMUNICACION DESC   ";
    
                return [$arrFilasPaginas_t[0],$arrFilasPaginas_t[1],$strSQLReporte_t,$strSQLReporteNoLimit_t,"CANAL COMUNICACION CHAT"];
    
                break;


            case 'gsComMail':

                $strCondicion_t = "DATE(FECHA_GESTION_DY) BETWEEN '".$strFechaIn_p."' AND '".$strFechaFn_p."'";
    
                if ($arrDataFiltros_p["totalFiltros"]>0) {

    
                    $strCondicion_t = "";
                    foreach ($arrDataFiltros_p["totalFiltros"] as $ite => $value) {

                        $strCondicion_t .=armarCondicion($arrDataFiltros_p["dataFiltros"][$ite]["selCampo_".$value],$arrDataFiltros_p["dataFiltros"][$ite]["selOperador_".$value],$arrDataFiltros_p["dataFiltros"][$ite]["valor_".$value],$arrDataFiltros_p["dataFiltros"][$ite]["tipo_".$value],$arrDataFiltros_p["dataFiltros"][$ite]["selCondicion_".$value],$arrDataFiltros_p["dataFiltros"][$ite]["cierre".$value]);
                    }
                }


                // Se realiza el conteo total de registros
                
                $arrFilasPaginas_t = filasPaginas(null,  "PASO = {$intIdPaso_p} AND ".$strCondicion_t ,$intLimite_p,'gsComMail', null, null);
                
                $strColumnasReport = " ID, ID_BD, FECHA_GESTION_DY, ANHO_GESTION_DY, MES_GESTION_DY, DIA_GESTION_DY, HORA_GESTION_DY, LINK_CONTENIDO, PARA, CC, CCO  ";
    
                
                $strSQLReporte_t = "SELECT {$strColumnasReport} FROM {$dyalogo_canales_electronicos}.DY_V_REPORTE_CE_SALIENTES WHERE PASO = {$intIdPaso_p} AND {$strCondicion_t} ORDER BY id DESC LIMIT 0, ".$intLimite_p;

                $strSQLReporteNoLimit_t = "SELECT {$strColumnasReport} FROM {$dyalogo_canales_electronicos}.DY_V_REPORTE_CE_SALIENTES WHERE PASO = {$intIdPaso_p} AND {$strCondicion_t} ORDER BY id DESC   ";
    
                return [$arrFilasPaginas_t[0],$arrFilasPaginas_t[1],$strSQLReporte_t,$strSQLReporteNoLimit_t,"CANAL COMUNICACION SALIENTE MAIL"];
    
                break;

            case 'gsComSMS':

                $strCondicion_t = "DATE(FECHA_GESTION_DY) BETWEEN '".$strFechaIn_p."' AND '".$strFechaFn_p."'";
    
                if ($arrDataFiltros_p["totalFiltros"]>0) {

    
                    $strCondicion_t = "";
                    foreach ($arrDataFiltros_p["totalFiltros"] as $ite => $value) {

                        $strCondicion_t .=armarCondicion($arrDataFiltros_p["dataFiltros"][$ite]["selCampo_".$value],$arrDataFiltros_p["dataFiltros"][$ite]["selOperador_".$value],$arrDataFiltros_p["dataFiltros"][$ite]["valor_".$value],$arrDataFiltros_p["dataFiltros"][$ite]["tipo_".$value],$arrDataFiltros_p["dataFiltros"][$ite]["selCondicion_".$value],$arrDataFiltros_p["dataFiltros"][$ite]["cierre".$value]);
                    }
                }


                // Se realiza el conteo total de registros
                
                $arrFilasPaginas_t = filasPaginas(null,  "PASO = {$intIdPaso_p} AND ".$strCondicion_t ,$intLimite_p,'gsComSMS', null, null);

                $strColumnasReport = " ID, ID_BD, FECHA_GESTION_DY, ANHO_GESTION_DY, MES_GESTION_DY, DIA_GESTION_DY, HORA_GESTION_DY, NUMERO_DESTINO, CONTENIDO ";
    
                
                $strSQLReporte_t = "SELECT {$strColumnasReport} FROM dy_sms.DY_REPORT_SMS_SALIENTES WHERE PASO = {$intIdPaso_p} AND {$strCondicion_t} ORDER BY id DESC LIMIT 0, ".$intLimite_p;

                $strSQLReporteNoLimit_t = "SELECT {$strColumnasReport} FROM dy_sms.DY_REPORT_SMS_SALIENTES WHERE PASO = {$intIdPaso_p} AND {$strCondicion_t} ORDER BY id DESC   ";
    
                return [$arrFilasPaginas_t[0],$arrFilasPaginas_t[1],$strSQLReporte_t,$strSQLReporteNoLimit_t,"CANAL COMUNICACION SALIENTE SMS"];
    
                break;



            case 'gsComWhatsapp':

                $strCondicion_t = "DATE(FECHA_GESTION_DY) BETWEEN '".$strFechaIn_p."' AND '".$strFechaFn_p."'";
    
                if ($arrDataFiltros_p["totalFiltros"]>0) {

    
                    $strCondicion_t = "";
                    foreach ($arrDataFiltros_p["totalFiltros"] as $ite => $value) {

                        $strCondicion_t .=armarCondicion($arrDataFiltros_p["dataFiltros"][$ite]["selCampo_".$value],$arrDataFiltros_p["dataFiltros"][$ite]["selOperador_".$value],$arrDataFiltros_p["dataFiltros"][$ite]["valor_".$value],$arrDataFiltros_p["dataFiltros"][$ite]["tipo_".$value],$arrDataFiltros_p["dataFiltros"][$ite]["selCondicion_".$value],$arrDataFiltros_p["dataFiltros"][$ite]["cierre".$value]);
                    }
                }


                // Se realiza el conteo total de registros
                
                $arrFilasPaginas_t = filasPaginas("Middleware",  "IP_SERVIDOR = '{$_SERVER["SERVER_ADDR"]}' AND PASO = {$intIdPaso_p} AND ".$strCondicion_t ,$intLimite_p,'gsComWhatsapp', null, null);

                // $strColumnasReport = " ID, ID_BD, FECHA_GESTION_DY, ANHO_GESTION_DY, MES_GESTION_DY, DIA_GESTION_DY, HORA_GESTION_DY, NUMERO_ORIGEN, NUMERO_DESTINO, CONCAT('https://','".$URL_SERVER_ADDONS."', '/api/visor/whatsappSaliente/',md5(concat('".clave_get."',ID))) AS LINK_CONTENIDO, ESTADO ";
                $strColumnasReport = " ID, ID_BD, FECHA_GESTION_DY, ANHO_GESTION_DY, MES_GESTION_DY, DIA_GESTION_DY, HORA_GESTION_DY, NUMERO_ORIGEN, NUMERO_DESTINO, ESTADO, COMENTARIO ";
    
                
                $strSQLReporte_t = "SELECT {$strColumnasReport} FROM dy_whatsapp.DY_V_REPORTE_PLANTILLAS_SALIENTES WHERE IP_SERVIDOR = '{$_SERVER["SERVER_ADDR"]}' AND PASO = {$intIdPaso_p} AND  {$strCondicion_t} ORDER BY id DESC LIMIT 0, ".$intLimite_p;

                $strSQLReporteNoLimit_t = "SELECT {$strColumnasReport} FROM dy_whatsapp.DY_V_REPORTE_PLANTILLAS_SALIENTES WHERE IP_SERVIDOR = '{$_SERVER["SERVER_ADDR"]}' AND PASO = {$intIdPaso_p} AND  {$strCondicion_t} ORDER BY id DESC   ";
    
                return [$arrFilasPaginas_t[0],$arrFilasPaginas_t[1],$strSQLReporte_t,$strSQLReporteNoLimit_t,"CANAL COMUNICACION WHATSAPP"];
    
                break;

        case 'detalladoLlamadas':


            // Primero se consulta el proyecto asociado al huesped
            $idProyecto = getProyectoId($intIdHuesped_p);

            $strCondicion_t = "DATE(fecha_hora) BETWEEN '".$strFechaIn_p."' AND '".$strFechaFn_p."'";

            if ($arrDataFiltros_p["totalFiltros"]>0) {

                $strCondicion_t = "";
                foreach ($arrDataFiltros_p["totalFiltros"] as $ite => $value) {

                    $strCondicion_t .=armarCondicion($arrDataFiltros_p["dataFiltros"][$ite]["selCampo_".$value],$arrDataFiltros_p["dataFiltros"][$ite]["selOperador_".$value],$arrDataFiltros_p["dataFiltros"][$ite]["valor_".$value],$arrDataFiltros_p["dataFiltros"][$ite]["tipo_".$value],$arrDataFiltros_p["dataFiltros"][$ite]["selCondicion_".$value],$arrDataFiltros_p["dataFiltros"][$ite]["cierre".$value]);
                }
            }

            // Se realiza el conteo total de registros

            $arrFilasPaginas_t = filasPaginas(null,  "id_proyecto = {$idProyecto} AND ".$strCondicion_t ,$intLimite_p,'detalladoLlamadas', null, null);


            $strColumnasReport = " date(fecha_hora) AS FECHA, llamada_id_asterisk AS UID, TIME(fecha_hora) AS INICIO, TIME(fecha_hora_final) AS FINAL, sentido AS SENTIDO, tipo_llamada AS TIPO, troncal AS TRONCAL,dnis  AS DID, numero_telefonico AS NUMERO, IF(disa, 'SI', 'NO') DISA, origen_disa AS ORIGEN_DISA, resultado AS RESULTADO, extension AS EXTENSION, IF(transferida, 'SI', 'NO') AS TRANSFERIDA, SUBSTRING(SEC_TO_TIME(duracion_total),1,8) AS DURACION, SUBSTRING(SEC_TO_TIME(tiempo_espera),1,8) AS ESPERA, SUBSTRING(SEC_TO_TIME(duracion_al_aire),1,8) AS AL_AIRE, redondeo_minuto AS REDONDEO, costo_llamada AS COSTO, SUBSTRING(SEC_TO_TIME(tiempo_timbrando),1,8) AS TIMBRADO, SUBSTRING(SEC_TO_TIME(tiempo_espera_llamada_activa),1,8) AS ESPERA_LLAMADA_ACTIVA, agente_nombre AS AGENTE, identificacion_agente AS ID_AGENTE, campana AS CAMPANA, quien_completo AS COLGO_PRIMERO, nombre_tipificacion AS TIPIFICACION, respuesta_encuesta AS ENCUESTA, etiqueta AS ETIQUETA ";


            if(isset($_GET["Reporte"])){
                $strColumnasReport .= ", grabacion ";
            }

            $strSQLReporte_t = "SELECT {$strColumnasReport} FROM {$BaseDatos_telefonia}.dy_v_historico_llamadas WHERE id_proyecto = {$idProyecto} AND  {$strCondicion_t} ORDER BY fecha_hora DESC LIMIT 0, ".$intLimite_p;

            $strSQLReporteNoLimit_t = "SELECT {$strColumnasReport} FROM {$BaseDatos_telefonia}.dy_v_historico_llamadas WHERE id_proyecto = {$idProyecto} AND  {$strCondicion_t} ORDER BY fecha_hora DESC";

            return [$arrFilasPaginas_t[0],$arrFilasPaginas_t[1],$strSQLReporte_t,$strSQLReporteNoLimit_t,"DETALLADO DE LLAMADAS"];

            break;


        case 'historicoIVRResum':

            // Primero se consulta el proyecto asociado al huesped
            $idProyecto = getProyectoId($intIdHuesped_p);

            $strCondicion_t = "DATE(fecha_hora) BETWEEN '".$strFechaIn_p."' AND '".$strFechaFn_p."'";

            if ($arrDataFiltros_p["totalFiltros"]>0) {

                $strCondicion_t = "";
                foreach ($arrDataFiltros_p["totalFiltros"] as $ite => $value) {

                    $strCondicion_t .=armarCondicion($arrDataFiltros_p["dataFiltros"][$ite]["selCampo_".$value],$arrDataFiltros_p["dataFiltros"][$ite]["selOperador_".$value],$arrDataFiltros_p["dataFiltros"][$ite]["valor_".$value],$arrDataFiltros_p["dataFiltros"][$ite]["tipo_".$value],$arrDataFiltros_p["dataFiltros"][$ite]["selCondicion_".$value],$arrDataFiltros_p["dataFiltros"][$ite]["cierre".$value]);
                }
            }

            global $strCondicionGlobal_t;
            $strCondicionGlobal_t = $strCondicion_t;

            // Se realiza el conteo total de registros

            $arrFilasPaginas_t = filasPaginas(null,  "id_proyecto = {$idProyecto} AND ".$strCondicion_t ,$intLimite_p,'historicoIVRResum', null, null);

            $strColumnasReport = " id_ivr, nombre_usuario_ivr, nombre_raiz, id_proyecto, COUNT(1) AS conteo ";

            $strSQLReporteNoLimit_t = "SELECT {$strColumnasReport} FROM {$BaseDatos_telefonia}.v_log_ivrs_opciones WHERE id_proyecto = {$idProyecto} AND  {$strCondicion_t} GROUP BY id_ivr DESC";

            $strSQLReporte_t = $strSQLReporteNoLimit_t. " LIMIT 0, ".$intLimite_p;

            return [$arrFilasPaginas_t[0],$arrFilasPaginas_t[1],$strSQLReporte_t,$strSQLReporteNoLimit_t,"HISTORICO IVR"];

            break;

        case 'historicoIVRDetallado':

            // Primero se consulta el proyecto asociado al huesped
            $idProyecto = getProyectoId($intIdHuesped_p);

            $strCondicion_t = "DATE(fecha_hora) BETWEEN '".$strFechaIn_p."' AND '".$strFechaFn_p."'";

            if ($arrDataFiltros_p["totalFiltros"]>0) {

                $strCondicion_t = "";
                foreach ($arrDataFiltros_p["totalFiltros"] as $ite => $value) {

                    $strCondicion_t .=armarCondicion($arrDataFiltros_p["dataFiltros"][$ite]["selCampo_".$value],$arrDataFiltros_p["dataFiltros"][$ite]["selOperador_".$value],$arrDataFiltros_p["dataFiltros"][$ite]["valor_".$value],$arrDataFiltros_p["dataFiltros"][$ite]["tipo_".$value],$arrDataFiltros_p["dataFiltros"][$ite]["selCondicion_".$value],$arrDataFiltros_p["dataFiltros"][$ite]["cierre".$value]);
                }
            }

            // Se realiza el conteo total de registros

            $arrFilasPaginas_t = filasPaginas(null,  "id_proyecto = {$idProyecto} AND ".$strCondicion_t ,$intLimite_p,'historicoIVRDetallado', null, null);

            $strColumnasReport = " fecha_hora AS FECHA_HORA, unique_id AS UID,telefono AS TELEFÓNO, nombre_raiz AS NOMBRE_IVR, nombre_usuario_ivr AS NOMBRE_OPCION, nombre_usuario_opcion AS OPCION_MARCADA  ";

            $strSQLReporteNoLimit_t = "SELECT {$strColumnasReport} FROM {$BaseDatos_telefonia}.v_log_ivrs_opciones WHERE id_proyecto = {$idProyecto} AND  {$strCondicion_t} ORDER BY fecha_hora DESC ";

            $strSQLReporte_t = $strSQLReporteNoLimit_t. " LIMIT 0, ".$intLimite_p;

            return [$arrFilasPaginas_t[0],$arrFilasPaginas_t[1],$strSQLReporte_t,$strSQLReporteNoLimit_t,"HISTORICO IVR"];

            break;

        case 'encuestasIVRResumenAgente':

            // Primero se consulta el proyecto asociado al huesped
            global $idProyecto;
            $idProyecto = getProyectoId($intIdHuesped_p);

            $strCondicion_t = "DATE(fecha_hora) BETWEEN '".$strFechaIn_p."' AND '".$strFechaFn_p."'";

            if ($arrDataFiltros_p["totalFiltros"]>0) {

                $strCondicion_t = "";
                foreach ($arrDataFiltros_p["totalFiltros"] as $ite => $value) {

                    $strCondicion_t .=armarCondicion($arrDataFiltros_p["dataFiltros"][$ite]["selCampo_".$value],$arrDataFiltros_p["dataFiltros"][$ite]["selOperador_".$value],$arrDataFiltros_p["dataFiltros"][$ite]["valor_".$value],$arrDataFiltros_p["dataFiltros"][$ite]["tipo_".$value],$arrDataFiltros_p["dataFiltros"][$ite]["selCondicion_".$value],$arrDataFiltros_p["dataFiltros"][$ite]["cierre".$value]);
                }
            }

            global $strCondicionGlobal_t;
            $strCondicionGlobal_t = $strCondicion_t;

            $arrFilasPaginas_t = filasPaginas(null,  "r.id_proyecto = {$idProyecto} AND ".$strCondicion_t ,$intLimite_p,'encuestasIVRResumenAgente', null, null);

            $strColumnasReport = " e.nombre AS ENCUESTA, r.nombre_agente AS AGENTE, p.nombre as PREGUNTA, count(1) as CANTIDAD ";

            $strSQLReporteNoLimit_t = "SELECT {$strColumnasReport} FROM {$BaseDatos_telefonia}.dy_encuestas_resultados r JOIN {$BaseDatos_telefonia}.dy_encuestas e ON e.id = r.id_encuesta JOIN {$BaseDatos_telefonia}.dy_encuestas_preguntas p ON p.id = r.id_pregunta WHERE r.id_proyecto = {$idProyecto} AND  {$strCondicion_t} GROUP BY r.nombre_agente DESC ";

            $strSQLReporte_t = $strSQLReporteNoLimit_t. " LIMIT 0, ".$intLimite_p;

            return [$arrFilasPaginas_t[0],$arrFilasPaginas_t[1],$strSQLReporte_t,$strSQLReporteNoLimit_t,"ENCUESTAS POR AGENTE"];

            break;


        case 'encuestasIVRResumenPregun':

            // Primero se consulta el proyecto asociado al huesped
            global $idProyecto;
            $idProyecto = getProyectoId($intIdHuesped_p);

            $strCondicion_t = "DATE(fecha_hora) BETWEEN '".$strFechaIn_p."' AND '".$strFechaFn_p."'";

            if ($arrDataFiltros_p["totalFiltros"]>0) {

                $strCondicion_t = "";
                foreach ($arrDataFiltros_p["totalFiltros"] as $ite => $value) {

                    $strCondicion_t .=armarCondicion($arrDataFiltros_p["dataFiltros"][$ite]["selCampo_".$value],$arrDataFiltros_p["dataFiltros"][$ite]["selOperador_".$value],$arrDataFiltros_p["dataFiltros"][$ite]["valor_".$value],$arrDataFiltros_p["dataFiltros"][$ite]["tipo_".$value],$arrDataFiltros_p["dataFiltros"][$ite]["selCondicion_".$value],$arrDataFiltros_p["dataFiltros"][$ite]["cierre".$value]);
                }
            }

            global $strCondicionGlobal_t;
            $strCondicionGlobal_t = $strCondicion_t;
            
            $arrFilasPaginas_t = filasPaginas(null,  "r.id_proyecto = {$idProyecto} AND ".$strCondicion_t ,$intLimite_p,'encuestasIVRResumenPregun', null, null);

            $strColumnasReport = " e.nombre AS ENCUESTA, p.nombre as PREGUNTA, count(1) as CANTIDAD ";

            $strSQLReporteNoLimit_t = "SELECT {$strColumnasReport} FROM {$BaseDatos_telefonia}.dy_encuestas_resultados r JOIN {$BaseDatos_telefonia}.dy_encuestas e ON e.id = r.id_encuesta JOIN {$BaseDatos_telefonia}.dy_encuestas_preguntas p ON p.id = r.id_pregunta WHERE r.id_proyecto = {$idProyecto} AND  {$strCondicion_t} GROUP BY p.nombre DESC";

            $strSQLReporte_t = $strSQLReporteNoLimit_t. " LIMIT 0, ".$intLimite_p;

            return [$arrFilasPaginas_t[0],$arrFilasPaginas_t[1],$strSQLReporte_t,$strSQLReporteNoLimit_t,"ENCUESTAS POR PREGUNTA"];

            break;


        case 'encuestasIVRdetallado':

            // Primero se consulta el proyecto asociado al huesped
            $idProyecto = getProyectoId($intIdHuesped_p);

            $strCondicion_t = "DATE(fecha_hora) BETWEEN '".$strFechaIn_p."' AND '".$strFechaFn_p."'";

            if ($arrDataFiltros_p["totalFiltros"]>0) {

                $strCondicion_t = "";
                foreach ($arrDataFiltros_p["totalFiltros"] as $ite => $value) {

                    $strCondicion_t .=armarCondicion($arrDataFiltros_p["dataFiltros"][$ite]["selCampo_".$value],$arrDataFiltros_p["dataFiltros"][$ite]["selOperador_".$value],$arrDataFiltros_p["dataFiltros"][$ite]["valor_".$value],$arrDataFiltros_p["dataFiltros"][$ite]["tipo_".$value],$arrDataFiltros_p["dataFiltros"][$ite]["selCondicion_".$value],$arrDataFiltros_p["dataFiltros"][$ite]["cierre".$value]);
                }
            }

            $arrFilasPaginas_t = filasPaginas(null,  "r.id_proyecto = {$idProyecto} AND ".$strCondicion_t ,$intLimite_p,'encuestasIVRdetallado', null, null);

            $strColumnasReport = " r.fecha_hora as FECHA_HORA, r.unique_id as UID,  r.caller_id as CONTACTO, e.nombre as ENCUESTA, p.nombre AS PREGUNTA, IF(c.nombre_usuario IS NULL, r.campana, c.nombre_usuario) as CAMPANA, r.nombre_agente as AGENTE, r.respuesta as RESPUESTA ";

            $strSQLReporteNoLimit_t = "SELECT {$strColumnasReport} FROM {$BaseDatos_telefonia}.dy_encuestas_resultados r JOIN {$BaseDatos_telefonia}.dy_encuestas e ON e.id = r.id_encuesta JOIN {$BaseDatos_telefonia}.dy_encuestas_preguntas p ON p.id = r.id_pregunta LEFT JOIN {$BaseDatos_telefonia}.dy_campanas c ON r.campana = c.nombre_interno WHERE r.id_proyecto = {$idProyecto} AND  {$strCondicion_t} ORDER BY r.id DESC ";

            $strSQLReporte_t = $strSQLReporteNoLimit_t. " LIMIT 0, ".$intLimite_p;

            return [$arrFilasPaginas_t[0],$arrFilasPaginas_t[1],$strSQLReporte_t,$strSQLReporteNoLimit_t,"ENCUESTAS DETALLADO"];

            break;



        case 'historicoUltOpcIVRDetallado':

            // Primero se consulta el proyecto asociado al huesped
            $idProyecto = getProyectoId($intIdHuesped_p);

            $strCondicion_t = "DATE(fecha_hora) BETWEEN '".$strFechaIn_p."' AND '".$strFechaFn_p."'";

            if ($arrDataFiltros_p["totalFiltros"]>0) {

                $strCondicion_t = "";
                foreach ($arrDataFiltros_p["totalFiltros"] as $ite => $value) {

                    $strCondicion_t .=armarCondicion($arrDataFiltros_p["dataFiltros"][$ite]["selCampo_".$value],$arrDataFiltros_p["dataFiltros"][$ite]["selOperador_".$value],$arrDataFiltros_p["dataFiltros"][$ite]["valor_".$value],$arrDataFiltros_p["dataFiltros"][$ite]["tipo_".$value],$arrDataFiltros_p["dataFiltros"][$ite]["selCondicion_".$value],$arrDataFiltros_p["dataFiltros"][$ite]["cierre".$value]);
                }
            }

            $arrFilasPaginas_t = filasPaginas(null,  "id_proyecto = {$idProyecto} AND sentido = 'Entrante' AND ivr_ultimo_usu IS NOT NULL AND ".$strCondicion_t ,$intLimite_p,'historicoUltOpcIVRDetallado', null, null);

            $strColumnasReport = " fecha_hora as FECHA, unique_id AS UID, numero_telefonico AS TELEFÓNO, ivr_ultimo_raiz AS NOMBRE_IVR, ivr_ultimo_usu AS NOMBRE_OPCION	, ivr_ultima_opcion AS OPCION_MARCADA ";

            $strSQLReporteNoLimit_t = "SELECT {$strColumnasReport} FROM {$BaseDatos_telefonia}.dy_llamadas_espejo WHERE id_proyecto = {$idProyecto} AND sentido = 'Entrante' AND ivr_ultimo_usu IS NOT NULL AND  {$strCondicion_t} ORDER BY fecha_hora DESC ";

            $strSQLReporte_t = $strSQLReporteNoLimit_t. " LIMIT 0, ".$intLimite_p;

            return [$arrFilasPaginas_t[0],$arrFilasPaginas_t[1],$strSQLReporte_t,$strSQLReporteNoLimit_t,"HISTORICO ULTIMA OPCION IVR"];

            break;


        case 'erlang':

            global $meta_tsf_erlang;
            global $tiempo_tsf_erlang;
            global $configValid_erlang;
            global $hora_inicial_erlang;
            global $hora_final_erlang;
            global $num_semanas;
            global $arr_festivos;

            $hora_inicial_erlang = intval(explode(":", $arrDataFiltros_p["dataFiltros"][2]["valor_3"])[0]) ;
            $hora_final_erlang = intval(explode(":",$arrDataFiltros_p["dataFiltros"][3]["valor_4"])[0]);
            $num_semanas = intval($arrDataFiltros_p["dataFiltros"][1]["valor_2"]);
            $arr_festivos = [];

            if ($arrDataFiltros_p["totalFiltros"]>0) {

                // VALIDAMOS QUE LA CONFIGURACION DE LAS CAMPAÑAS SELECCIONADAS SEAN IGUALES,SI NO NO SE PUEDE CALCULAR ERLANG

                $strWhereConfigCampan = "";
                $strSeparadorConfigCampan = " ";
                for ($i=0; $i < count($arrDataFiltros_p["totalFiltros"]) ; $i++) { 
                    if($arrDataFiltros_p["dataFiltros"][$i]["selCampo_".($i+1)] == "id_campana"){
                        foreach ($arrDataFiltros_p['dataFiltros'][$i]['valor_'.($i+1)] as $key => $value) {
                            $strWhereConfigCampan .= $strSeparadorConfigCampan." id = '".$value."' ";
                            $strSeparadorConfigCampan = " OR ";
                        }
                    }

                }


                $sqlConfigCampan = $mysqli->query("SELECT meta_tsf, tiempo_tsf FROM {$BaseDatos_telefonia}.dy_campanas WHERE {$strWhereConfigCampan}");
                if($sqlConfigCampan && $sqlConfigCampan->num_rows > 0){
                    $validSecondConfigCampan = false;
                    $configValid_erlang = true;
                    $meta_tsf_erlang = 0;
                    $tiempo_tsf_erlang = 0;

                    while($resConfigCampan = $sqlConfigCampan->fetch_object()){
                        if($validSecondConfigCampan){
                            // SI SE FILTRA UNA SEGUNDA CAMPAÑA O MAS TOCA VALIDAR QUE LA CONFIGURACION SEA LA MISMA
                            if($meta_tsf_erlang != $resConfigCampan->meta_tsf || $tiempo_tsf_erlang != $resConfigCampan->tiempo_tsf){
                                $configValid_erlang = false;
                            }
                        }else{
                            $meta_tsf_erlang = $resConfigCampan->meta_tsf;
                            $tiempo_tsf_erlang  = $resConfigCampan->tiempo_tsf;
                            $validSecondConfigCampan = true;
                        }
                    }

                }


                // AQUI CALCULO LA FECHA INICIAL ANTES DE ARMAR EL FILTRO DE SQL
                $fecha_final = $arrDataFiltros_p["dataFiltros"][0]["valor_1"];
                $num_semana_days = $num_semanas*7;
                $fecha_inicial = strtotime("-{$num_semana_days} days", strtotime($fecha_final)); // Se resta el numero de semanas

                // SE VALIDA SI EL DIA INICIAL ES SABADO, SI NO ENONCES ESPECIFICAMOS EL ULTIMO SABADO DE LA ANTERIOR SEMANA
                // if(date("l", $fecha_inicial) != "Saturday"){
                //     $fecha_inicial = strtotime("last Saturday", $fecha_inicial);
                // }

                $fecha_inicial = date("Y-m-d", $fecha_inicial); 

                array_push($arrDataFiltros_p["dataFiltros"], ["selCampo_7" => "fecha", "selOperador_7" => ">=", "valor_7" => $fecha_inicial, "tipo_7" => "5", "selCondicion_7" => "AND", "cierre7" => ""]);
                array_push($arrDataFiltros_p["totalFiltros"], "7");

                // VERIFICO SI REQUIERO HACER LOS CALCULOS TENIENDO EN CUENTA LOS FESTIVOS

                if($arrDataFiltros_p["dataFiltros"][5]["valor_6"] == "SI"){
                    $strSqlFestivos = "SELECT fecha AS festivo FROM {$BaseDatos_telefonia}.dy_festivos f JOIN {$BaseDatos_telefonia}.dy_listas_festivos l ON f.id_lista = l.id WHERE l.id_proyecto = '{$intIdHuesped_p}' and  fecha >= '{$fecha_inicial}' and fecha <= '{$fecha_final}';";

                    $resSqlFestivos = $mysqli->query($strSqlFestivos);

                    if($resSqlFestivos && $resSqlFestivos->num_rows > 0){
                        while($objFestivos = $resSqlFestivos->fetch_object()){
                            // ESTA INFO SE GUARDA EN UNA VARIABLE GLOBAL, YA QUE NECESITO USARLA EN gridErlang
                            array_push($arr_festivos, $objFestivos->festivo);
                        }
                    }
                }


                $strCondicion_t = "";
                foreach ($arrDataFiltros_p["totalFiltros"] as $ite => $value) {
                    // EN LA CONDICION SOLO NECESITAMOS LOS FILTROS DE FECHAS Y CAMPAÑAS
                    if($ite == "0" || $ite == "4" || $ite == "6"){
                        $strCondicion_t .=armarCondicion($arrDataFiltros_p["dataFiltros"][$ite]["selCampo_".$value],$arrDataFiltros_p["dataFiltros"][$ite]["selOperador_".$value],$arrDataFiltros_p["dataFiltros"][$ite]["valor_".$value],$arrDataFiltros_p["dataFiltros"][$ite]["tipo_".$value],$arrDataFiltros_p["dataFiltros"][$ite]["selCondicion_".$value],$arrDataFiltros_p["dataFiltros"][$ite]["cierre".$value]);
                    }
                }

                
            }

            $strColumnasReport = " fecha,intervalo_traducido AS hora, sum(recibidas) as recibidas, sum(contestadas) as contestadas, sum(tiempo_conversacion_total) as tiempo_conversacion_total ";

            $strSQLReporteNoLimit_t = "SELECT {$strColumnasReport} FROM {$BaseDatos_telefonia}.dy_informacion_intervalos_h where {$strCondicion_t} AND intervalo_traducido >= {$hora_inicial_erlang} AND intervalo_traducido <= {$hora_final_erlang} group by fecha, hora";

            $strSQLReporte_t = $strSQLReporteNoLimit_t;

            return [1,1,$strSQLReporte_t,$strSQLReporteNoLimit_t,"ERLANG"];

            break;

        case 'tareasProgramadas':

            // Obtengo la informacion de la accion
            $accion = getTarpro($_POST["accion"]);
            $tarea = getTarhor($accion->TARPRO_ConsInte_TARHOR____b);
            $bd = getBdByEstrat($tarea->TARHOR_ConsInte_ESTRAT____b);
            $muestra = getMuestraIdByEstpas($tarea->TARHOR_ConsInte_ESTPAS____b);

            // Traduzco la consulta de la accion para que sea un select
            $consulta = traducirConsulta($accion->TARPRO_Consulta_sql_b);

            // Esto para traer la cantidad de registros y las filas para el paginado
            $arrFilasPaginas_t = filasPaginas($bd, $consulta, $intLimite_p, 'tareasProgramadas', null, null);
            
            // Trae las columnas que se usaran en el reporte
            $arrData_t = columnasReporte("tareasProgramadas", $bd, $muestra, null, null, null);
            $strColumnasDinamicas_t = $arrData_t[0];

            // Partire la consulta por el FROM
            $arrConsulta = explode("FROM", $consulta);

            $strSQLReporte_t = "SELECT " . $strColumnasDinamicas_t . " FROM " . $arrConsulta[1] . "ORDER BY G". $bd."_ConsInte__b DESC LIMIT 0,".$intLimite_p;
            $strSQLReporteNoLimit_t = "SELECT " . $strColumnasDinamicas_t . " FROM " . $arrConsulta[1] . "ORDER BY G". $bd."_ConsInte__b DESC";

            return [$arrFilasPaginas_t[0],$arrFilasPaginas_t[1],$strSQLReporte_t,$strSQLReporteNoLimit_t,"tareasProgramadas"];

            break;

        default:  

            $strCondicion_t = "DATE(G".$intIdBd_p."_FechaInsercion) BETWEEN '".$strFechaIn_p."' AND '".$strFechaFn_p."'";

            if ($arrDataFiltros_p["totalFiltros"]>0) {

                $strCondicion_t = "";

                foreach ($arrDataFiltros_p["totalFiltros"] as $ite => $value) {


                    $strCondicion_t .=armarCondicion($arrDataFiltros_p["dataFiltros"][$ite]["selCampo_".$value],$arrDataFiltros_p["dataFiltros"][$ite]["selOperador_".$value],$arrDataFiltros_p["dataFiltros"][$ite]["valor_".$value],$arrDataFiltros_p["dataFiltros"][$ite]["tipo_".$value],$arrDataFiltros_p["dataFiltros"][$ite]["selCondicion_".$value],$arrDataFiltros_p["dataFiltros"][$ite]["cierre".$value]);

                }

            }
            $arrFilasPaginas_t = filasPaginas($intIdBd_p,$strCondicion_t,$intLimite_p);

            $arrData_t = columnasReporte("bd",$intIdBd_p,$intIdMuestra_p);

            $strColumnasDinamicas_t = $arrData_t[0];

            $strSQLReporte_t = "SELECT ".$strColumnasDinamicas_t." FROM ".$BaseDatos.".G".$intIdBd_p." ".$arrData_t[1]." WHERE (".$strCondicion_t.") ORDER BY G".$intIdBd_p."_ConsInte__b DESC LIMIT 0,".$intLimite_p;

            $strSQLReporteNoLimit_t = "SELECT ".$strColumnasDinamicas_t." FROM ".$BaseDatos.".G".$intIdBd_p." ".$arrData_t[1]." WHERE (".$strCondicion_t.") ORDER BY G".$intIdBd_p."_ConsInte__b DESC ";

            return [$arrFilasPaginas_t[0],$arrFilasPaginas_t[1],$strSQLReporte_t,$strSQLReporteNoLimit_t,"RESUMEN BASE GENERAL"];

            break;


    }

}

function getTarpro($id){

    global $mysqli;

    $query = "SELECT TARPRO_ConsInte__b, TARPRO_Consulta_sql_b, TARPRO_ConsInte_TARHOR____b FROM DYALOGOCRM_SISTEMA.TARPRO WHERE TARPRO_ConsInte__b= {$id} limit 1";
    $res = $mysqli->query($query);

    if($res && $res->num_rows > 0){
        $data = $res->fetch_object();
        return $data;
    }

    return null;
}

function getTarhor($id){

    global $mysqli;

    $query = "SELECT TARHOR_ConsInte__b, TARHOR_ConsInte_ESTRAT____b, TARHOR_ConsInte_ESTPAS____b FROM DYALOGOCRM_SISTEMA.TARHOR WHERE TARHOR_ConsInte__b= {$id} LIMIT 1";

    $res = $mysqli->query($query);

    if($res && $res->num_rows > 0){
        $data = $res->fetch_object();
        return $data;
    }

    return null;

}

function getBdByEstrat($estratId){

    global $mysqli;

    $query = "SELECT ESTRAT_ConsInte__b, ESTRAT_ConsInte_GUION_Pob from DYALOGOCRM_SISTEMA.ESTRAT WHERE ESTRAT_ConsInte__b = {$estratId} LIMIT 1";

    $res = $mysqli->query($query);

    if($res && $res->num_rows > 0){
        $data = $res->fetch_object();
        return $data->ESTRAT_ConsInte_GUION_Pob;
    }

    return null;
}

function getMuestraIdByEstpas($estpasId){

    global $mysqli;

    $query = "SELECT ESTPAS_ConsInte__b, CAMPAN_ConsInte__MUESTR_b from DYALOGOCRM_SISTEMA.ESTPAS INNER JOIN DYALOGOCRM_SISTEMA.CAMPAN ON ESTPAS_ConsInte__CAMPAN_b = CAMPAN_ConsInte__b WHERE ESTPAS_ConsInte__b = {$estpasId}";

    $res = $mysqli->query($query);

    if($res && $res->num_rows > 0){
        $data = $res->fetch_object();
        return $data->CAMPAN_ConsInte__MUESTR_b;
    }

    return null;

}

function traducirConsulta($query){
    
    // Limpio los caracteres multiples
    $query = preg_replace('/\s+/', ' ', $query);
    
    // Corto la consulta en dos partes por el WHERE
    $arrQuery = explode("WHERE", $query);
    $arrQuery2 = explode("SET", $arrQuery[0]);

    $bd = str_replace("UPDATE", "SELECT * FROM ", $arrQuery2[0]);
    $where = $arrQuery[1];

    return $bd . " WHERE " . $where;
}

/**
 * Esta funcion obtenemos los reportes por agente, adhrencias, calidad y condia
 */

function obtenerReporteAgente($tipoReport_p,$strFechaIn_p,$strFechaFn_p,$intIdPeriodo_p,$strLimit_p,$intIdHuesped_p,$intIdEstrat_p,$intIdCBX_p, $intIdAgente, $id_usuari, $intIdBd_p,$intIdPaso_p,$intIdTipo_p,$intIdMuestra_p,$intIdGuion_p,$intFilas_p,$intLimite_p,$strSolicitud_p=null,$arrDataFiltros_p)
{
    global $mysqli;
    global $BaseDatos;
    global $BaseDatos_systema;
    global $BaseDatos_telefonia;
    global $dyalogo_canales_electronicos;
    global $BaseDatos_general;
    // global $arrCamposMuestra_t;
    // global $arrCamposMuestra2_t;

    $arrData = array();
    # code...

    switch ($tipoReport_p) {
        case 'condia':

            // $strNameView_t = nombreVistas($intIdGuion_p);

            $strCondicion_t = "FECHA_GESTION BETWEEN '{$strFechaIn_p}' AND '{$strFechaFn_p}'";

            if ($arrDataFiltros_p["totalFiltros"] > 0) {

                $strCondicion_t = "";

                foreach ($arrDataFiltros_p["totalFiltros"] as $ite => $value) {

                    $strCondicion_t .= armarCondicion($arrDataFiltros_p["dataFiltros"][$ite]["selCampo_" . $value], $arrDataFiltros_p["dataFiltros"][$ite]["selOperador_" . $value], $arrDataFiltros_p["dataFiltros"][$ite]["valor_" . $value], $arrDataFiltros_p["dataFiltros"][$ite]["tipo_" . $value], $arrDataFiltros_p["dataFiltros"][$ite]["selCondicion_" . $value],$arrDataFiltros_p["dataFiltros"][$ite]["cierre".$value]);
                }
            }


            $strSQLReporte_t = "SELECT ID_BD, FECHA_GESTION, ANHO_GESTION, MES_GESTION, DIA_GESTION, HORA_GESTION, MINUTO_GESTION, SEGUNDO_GESTION, DURACION_GESTION, DURACION_GESTION_SEG, AGENTE_ID, AGENTE, SENTIDO, CANAL, ULTIMA_GESTION, CLASIFICACION, REINTENTO, FECHA_AGENDA, ANHO_AGENDA, MES_AGENDA, DIA_AGENDA, HORA_AGENDA, MINUTO_AGENDA, SEGUNDO_AGENDA, OBSERVACION, CAMPANA, ESTRATEGIA, FORMULARIO_GESTION, BASE_DATOS FROM {$BaseDatos}.DY_V_CONDIA WHERE AGENTE_ID = {$id_usuari} AND AGENTE_ID <> '' AND ({$strCondicion_t}) ORDER BY FECHA_GESTION DESC LIMIT 0,{$intLimite_p}";

            $strSQLReporteNoLimit_t = "SELECT ID_BD, FECHA_GESTION, ANHO_GESTION, MES_GESTION, DIA_GESTION, HORA_GESTION, MINUTO_GESTION, SEGUNDO_GESTION, DURACION_GESTION, DURACION_GESTION_SEG, AGENTE_ID, AGENTE, SENTIDO, CANAL, ULTIMA_GESTION, CLASIFICACION, REINTENTO, FECHA_AGENDA, ANHO_AGENDA, MES_AGENDA, DIA_AGENDA, HORA_AGENDA, MINUTO_AGENDA, SEGUNDO_AGENDA, OBSERVACION, CAMPANA, ESTRATEGIA, FORMULARIO_GESTION, BASE_DATOS FROM {$BaseDatos}.DY_V_CONDIA WHERE AGENTE_ID = {$id_usuari} AND AGENTE_ID <> '' AND ({$strCondicion_t}) ORDER BY FECHA_GESTION DESC";

            $strSQLReportePaginas_t = "SELECT COUNT(1) AS cantidad FROM {$BaseDatos}.DY_V_CONDIA WHERE AGENTE_ID = {$id_usuari} AND AGENTE_ID <> '' AND ({$strCondicion_t}) ORDER BY FECHA_GESTION DESC";

            $arrFilasPaginas_t = filasPaginas($strSQLReportePaginas_t, $strCondicion_t, $intLimite_p, 'customQuery');

            return [$arrFilasPaginas_t[0], $arrFilasPaginas_t[1], $strSQLReporte_t, $strSQLReporteNoLimit_t, "GESTIONES"];
            break;
        case 'calidad':
            # code...

            $strCondicion_t = "FECHA_GESTION BETWEEN '{$strFechaIn_p}' AND '{$strFechaFn_p}'";

            if ($arrDataFiltros_p["totalFiltros"] > 0) {

                $strCondicion_t = "";

                foreach ($arrDataFiltros_p["totalFiltros"] as $ite => $value) {

                    $strCondicion_t .= armarCondicion($arrDataFiltros_p["dataFiltros"][$ite]["selCampo_" . $value], $arrDataFiltros_p["dataFiltros"][$ite]["selOperador_" . $value], $arrDataFiltros_p["dataFiltros"][$ite]["valor_" . $value], $arrDataFiltros_p["dataFiltros"][$ite]["tipo_" . $value], $arrDataFiltros_p["dataFiltros"][$ite]["selCondicion_" . $value],$arrDataFiltros_p["dataFiltros"][$ite]["cierre".$value]);
                    
                }
            }



            $strSQLReporte_t = "SELECT ID_GESTION, FECHA_GESTION, ANHO_GESTION, MES_GESTION, DIA_GESTION, HORA_GESTION, MINUTO_GESTION, SEGUNDO_GESTION, AGENTE_ID, AGENTE, FORMULARIO_GESTION, DATO_PRINCIPAL_SCRIPT, DATO_SECUNDARIO_SCRIPT, FECHA_EVALUACION, ANHO_EVALUACION, MES_EVALUACION, DIA_EVALUACION, HORA_EVALUACION, MINUTO_EVALUACION, SEGUNDO_EVALUACION, USUARIO_CALIFICA_ID, USUARIO_CALIFICA, CALIFICACION, COMENTARIO_CALIDAD, COMENTARIO_AGENTE FROM {$BaseDatos}.DY_V_CALHIS WHERE AGENTE_ID = {$id_usuari} AND AGENTE_ID <> '' AND ({$strCondicion_t}) ORDER BY FECHA_GESTION DESC LIMIT 0, {$intLimite_p}";


            $strSQLReporteNoLimit_t = "SELECT ID_GESTION, FECHA_GESTION, ANHO_GESTION, MES_GESTION, DIA_GESTION, HORA_GESTION, MINUTO_GESTION, SEGUNDO_GESTION, AGENTE_ID, AGENTE, FORMULARIO_GESTION, DATO_PRINCIPAL_SCRIPT, DATO_SECUNDARIO_SCRIPT, FECHA_EVALUACION, ANHO_EVALUACION, MES_EVALUACION, DIA_EVALUACION, HORA_EVALUACION, MINUTO_EVALUACION, SEGUNDO_EVALUACION, USUARIO_CALIFICA_ID, USUARIO_CALIFICA, CALIFICACION, COMENTARIO_CALIDAD, COMENTARIO_AGENTE FROM {$BaseDatos}.DY_V_CALHIS WHERE AGENTE_ID = {$id_usuari} AND AGENTE_ID <> '' AND ({$strCondicion_t}) ORDER BY FECHA_GESTION DESC";

            $strSQLReportePaginas_t = "SELECT COUNT(1) AS cantidad FROM {$BaseDatos}.DY_V_CALHIS WHERE AGENTE_ID = {$id_usuari} AND AGENTE_ID <> '' AND  ({$strCondicion_t}) ORDER BY FECHA_GESTION DESC";

            $arrFilasPaginas_t = filasPaginas($strSQLReportePaginas_t, $strCondicion_t, $intLimite_p, 'customQuery');

            return [$arrFilasPaginas_t[0], $arrFilasPaginas_t[1], $strSQLReporte_t, $strSQLReporteNoLimit_t, "CALIDAD"];
            break;

        case '1':

            $strCondicion_t = "DATE(fecha_hora_inicio) BETWEEN '{$strFechaIn_p}' AND '{$strFechaFn_p}'";

            if ($arrDataFiltros_p["totalFiltros"] > 0) {

                $strCondicion_t = "";

                foreach ($arrDataFiltros_p["totalFiltros"] as $ite => $value) {

                    $strCondicion_t .= armarCondicion($arrDataFiltros_p["dataFiltros"][$ite]["selCampo_" . $value], $arrDataFiltros_p["dataFiltros"][$ite]["selOperador_" . $value], $arrDataFiltros_p["dataFiltros"][$ite]["valor_" . $value], $arrDataFiltros_p["dataFiltros"][$ite]["tipo_" . $value], $arrDataFiltros_p["dataFiltros"][$ite]["selCondicion_" . $value],$arrDataFiltros_p["dataFiltros"][$ite]["cierre".$value]);
                }
            }

            $strSQLReporte_t = "SELECT agente_nombre AS Agente, DATE(fecha_hora_inicio) AS Inicio, DATE_FORMAT(fecha_hora_inicio, '%H') AS Inicio_hora, DATE_FORMAT(fecha_hora_inicio, '%i') AS Inicio_minuto, DATE(fecha_hora_fin) AS Fin, DATE_FORMAT(fecha_hora_fin, '%H') AS Fin_hora, DATE_FORMAT(fecha_hora_fin, '%i') AS Fin_minuto, SUBSTRING_INDEX(SEC_TO_TIME(duracion), '.', 1) AS Duracion_Horas FROM {$BaseDatos_telefonia}.dy_v_historico_sesiones WHERE agente_id = {$intIdAgente} AND ({$strCondicion_t}) GROUP BY sesion_id ORDER BY fecha_hora_inicio DESC LIMIT 0 , {$intLimite_p}";


            $strSQLReporteNoLimit_t = "SELECT agente_nombre AS Agente, DATE(fecha_hora_inicio) AS Inicio, DATE_FORMAT(fecha_hora_inicio, '%H') AS Inicio_hora, DATE_FORMAT(fecha_hora_inicio, '%i') AS Inicio_minuto, DATE(fecha_hora_fin) AS Fin, DATE_FORMAT(fecha_hora_fin, '%H') AS Fin_hora, DATE_FORMAT(fecha_hora_fin, '%i') AS Fin_minuto, SUBSTRING_INDEX(SEC_TO_TIME(duracion), '.', 1) AS Duracion_Horas FROM {$BaseDatos_telefonia}.dy_v_historico_sesiones WHERE agente_id = {$intIdAgente} AND ({$strCondicion_t}) GROUP BY sesion_id ORDER BY fecha_hora_inicio DESC";


            $arrFilasPaginas_t = filasPaginas(null, "  agente_id = {$intIdAgente} AND ({$strCondicion_t}) ", $intLimite_p, '1');

            return [$arrFilasPaginas_t[0], $arrFilasPaginas_t[1], $strSQLReporte_t, $strSQLReporteNoLimit_t, "SESIONES"];

            break;

        case 'pausas':

            $strCondicion_t = "DATE(fecha_hora_inicio) BETWEEN '" . $strFechaIn_p . "' AND '" . $strFechaFn_p . "'";

            if ($arrDataFiltros_p["totalFiltros"] > 0) {

                $strCondicion_t = "";

                foreach ($arrDataFiltros_p["totalFiltros"] as $ite => $value) {


                    $strCondicion_t .= armarCondicion($arrDataFiltros_p["dataFiltros"][$ite]["selCampo_" . $value], $arrDataFiltros_p["dataFiltros"][$ite]["selOperador_" . $value], $arrDataFiltros_p["dataFiltros"][$ite]["valor_" . $value], $arrDataFiltros_p["dataFiltros"][$ite]["tipo_" . $value], $arrDataFiltros_p["dataFiltros"][$ite]["selCondicion_" . $value],$arrDataFiltros_p["dataFiltros"][$ite]["cierre".$value]);
                }
            }

            $strSQLFechas_t = "SELECT DATE(fecha_hora_inicio) AS fecha FROM {$BaseDatos_telefonia}.dy_v_historico_descansos WHERE agente_id = {$intIdAgente} AND ({$strCondicion_t}) GROUP BY DATE(fecha_hora_inicio) ORDER BY DATE(fecha_hora_inicio) DESC";

            $resSQLFechas_t = $mysqli->query($strSQLFechas_t);


            $strSQLReporte_t = "SELECT 'NO HAY REPORTE DISPONIBLE' AS ERROR";

            $strSQLReporte_t = "SELECT agente_nombre AS AGENTE, tipo_descanso_nombre AS PAUSA";

            while ($obj = $resSQLFechas_t->fetch_object()) {

                $strSQLReporte_t .= ", CAST(SEC_TO_TIME((CASE WHEN DATE(fecha_hora_inicio) = '{$obj->fecha}' THEN duracion ELSE 0 END)) AS TIME(0)) AS '{$obj->fecha}'";
            }

            $strSQLReporte_t .= ", CAST(SEC_TO_TIME(duracion) AS TIME(0)) AS TOTAL FROM " . $BaseDatos_telefonia . ".dy_v_historico_descansos WHERE agente_id = {$intIdAgente} AND ({$strCondicion_t}) GROUP BY agente_nombre, tipo_descanso_nombre";


            return [0, 0, $strSQLReporte_t, $strSQLReporte_t, "PAUSAS - DURACION POR AGENTE"];

            break;

        case '2':

            $strCondicion_t = "DATE(fecha_hora_inicio) BETWEEN '" . $strFechaIn_p . "' AND '" . $strFechaFn_p . "'";

            if ($arrDataFiltros_p["totalFiltros"] > 0) {

                $strCondicion_t = "";

                foreach ($arrDataFiltros_p["totalFiltros"] as $ite => $value) {


                    $strCondicion_t .= armarCondicion($arrDataFiltros_p["dataFiltros"][$ite]["selCampo_" . $value], $arrDataFiltros_p["dataFiltros"][$ite]["selOperador_" . $value], $arrDataFiltros_p["dataFiltros"][$ite]["valor_" . $value], $arrDataFiltros_p["dataFiltros"][$ite]["tipo_" . $value], $arrDataFiltros_p["dataFiltros"][$ite]["selCondicion_" . $value],$arrDataFiltros_p["dataFiltros"][$ite]["cierre".$value]);
                }
            }

            $strSQLReporte_t = "SELECT agente_nombre AS Agente, DATE(fecha_hora_inicio) AS Inicio, DATE_FORMAT(fecha_hora_inicio, '%H') AS Inicio_hora, DATE_FORMAT(fecha_hora_inicio, '%i') AS Inicio_minuto, DATE(fecha_hora_fin) AS Fin, DATE_FORMAT(fecha_hora_fin, '%H') AS Fin_hora, DATE_FORMAT(fecha_hora_fin, '%i') AS Fin_minuto, DATE_FORMAT(SEC_TO_TIME(duracion), '%H:%i:%s') AS Duracion_Pausa, tipo_descanso_nombre AS TipoPausa, comentario FROM dyalogo_telefonia.dy_v_historico_descansos WHERE agente_id = {$intIdAgente} AND ({$strCondicion_t}) GROUP BY descanso_id ORDER BY fecha_hora_inicio DESC LIMIT 0,{$intLimite_p}"; 

            $strSQLReporteNoLimit_t = "SELECT agente_nombre AS Agente, DATE(fecha_hora_inicio) AS Inicio, DATE_FORMAT(fecha_hora_inicio, '%H') AS Inicio_hora, DATE_FORMAT(fecha_hora_inicio, '%i') AS Inicio_minuto, DATE(fecha_hora_fin) AS Fin, DATE_FORMAT(fecha_hora_fin, '%H') AS Fin_hora, DATE_FORMAT(fecha_hora_fin, '%i') AS Fin_minuto, DATE_FORMAT(SEC_TO_TIME(duracion), '%H:%i:%s') AS Duracion_Pausa, tipo_descanso_nombre AS TipoPausa, comentario FROM dyalogo_telefonia.dy_v_historico_descansos WHERE agente_id = {$intIdAgente} AND ({$strCondicion_t}) GROUP BY descanso_id ORDER BY fecha_hora_inicio DESC";


            $strSQLReportePaginas_t = "SELECT COUNT(1) AS cantidad FROM (SELECT COUNT(1) AS cantidad FROM {$BaseDatos_telefonia}.dy_v_historico_descansos WHERE agente_id = {$intIdAgente} AND ({$strCondicion_t}) GROUP BY descanso_id ORDER BY fecha_hora_inicio DESC) AS a;";

            // echo "<br> strSQLReportePaginas_t => $strSQLReportePaginas_t <br><br>";

            $arrFilasPaginas_t = filasPaginas($strSQLReportePaginas_t, $strCondicion_t, $intLimite_p, '2');

            return [$arrFilasPaginas_t[0], $arrFilasPaginas_t[1], $strSQLReporte_t, $strSQLReporteNoLimit_t, "PAUSAS"];

            break;

        case '3':

            //JDBD - Consultamos la cantidad de registros a retornar.
            // $strSQLCantidadFilas_t = "SELECT COUNT(1) AS cantidad FROM " . $BaseDatos_systema . ".qryUSUARI_usuarios_agentes LEFT JOIN " . $BaseDatos_systema . ".qrySesionesDelDia ON IdAgente = qrySesionesDelDia.agente_id WHERE (NOT (HoraInicialDefinida is null)) AND HoraInicialDefinida < date_format(current_time, '%H:%i:%S') and USUARI_ConsInte__PROYEC_b = " . $intIdHuesped_p . " AND qrySesionesDelDia.agente_id IS NULL Order By USUARI_Nombre____b";

            $strSQLCantidadFilas_t =  "SELECT COUNT(1) AS cantidad FROM {$BaseDatos_systema}.qryUSUARI_usuarios_agentes LEFT JOIN {$BaseDatos_systema}.qrySesionesDelDia ON IdAgente = qrySesionesDelDia.agente_id WHERE USUARI_ConsInte__b = '{$id_usuari}' AND (NOT (HoraInicialDefinida IS NULL)) AND HoraInicialDefinida < DATE_FORMAT(CURRENT_TIME, '%H:%i:%S') AND USUARI_ConsInte__PROYEC_b = {$intIdHuesped_p} AND qrySesionesDelDia.agente_id IS NULL ORDER BY USUARI_Nombre____b";

            $intCantidadFilas_t = $mysqli->query($strSQLCantidadFilas_t)->fetch_object()->cantidad;

            $intCantidadPaginas = ceil($intCantidadFilas_t / $intLimite_p);

            // $strSQLReporte_t = "select USUARI_ConsInte__b as Id, USUARI_Nombre____b as Nombre, USUARI_Correo___b as Correo, HoraInicialDefinida FROM " . $BaseDatos_systema . ".qryUSUARI_usuarios_agentes LEFT JOIN " . $BaseDatos_systema . ".qrySesionesDelDia ON IdAgente = qrySesionesDelDia.agente_id WHERE (NOT (HoraInicialDefinida is null)) AND HoraInicialDefinida < date_format(current_time, '%H:%i:%S') and USUARI_ConsInte__PROYEC_b = " . $intIdHuesped_p . " AND qrySesionesDelDia.agente_id IS NULL Order By USUARI_Nombre____b DESC LIMIT 0," . $intLimite_p;

            $strSQLReporte_t = "SELECT USUARI_ConsInte__b AS Id, USUARI_Nombre____b AS Nombre, USUARI_Correo___b AS Correo, HoraInicialDefinida FROM {$BaseDatos_systema}.qryUSUARI_usuarios_agentes LEFT JOIN {$BaseDatos_systema}.qrySesionesDelDia ON IdAgente = qrySesionesDelDia.agente_id WHERE USUARI_ConsInte__b = '{$id_usuari}' AND (NOT (HoraInicialDefinida IS NULL)) AND HoraInicialDefinida < DATE_FORMAT(CURRENT_TIME, '%H:%i:%S') AND USUARI_ConsInte__PROYEC_b = '{$intIdHuesped_p}' AND qrySesionesDelDia.agente_id IS NULL ORDER BY USUARI_Nombre____b DESC LIMIT 0 , {$intLimite_p}";

            $strSQLReporteNoLimit_t = "SELECT USUARI_ConsInte__b AS Id, USUARI_Nombre____b AS Nombre, USUARI_Correo___b AS Correo, HoraInicialDefinida FROM {$BaseDatos_systema}.qryUSUARI_usuarios_agentes LEFT JOIN {$BaseDatos_systema}.qrySesionesDelDia ON IdAgente = qrySesionesDelDia.agente_id WHERE USUARI_ConsInte__b = '{$id_usuari}' AND (NOT (HoraInicialDefinida IS NULL)) AND HoraInicialDefinida < DATE_FORMAT(CURRENT_TIME, '%H:%i:%S') AND USUARI_ConsInte__PROYEC_b = '{$intIdHuesped_p}' AND qrySesionesDelDia.agente_id IS NULL ORDER BY USUARI_Nombre____b DESC";

            return [$intCantidadFilas_t, $intCantidadPaginas, $strSQLReporte_t, $strSQLReporteNoLimit_t, "NO REGISTRARON"];

            break;
        case '4':

            // $strSQLCantidadFilas_t = "SELECT COUNT(1) AS cantidad FROM " . $BaseDatos_systema . ".qryUSUARI_usuarios_agentes JOIN " . $BaseDatos_systema . ".qrySesionesDelDia ON IdAgente = qrySesionesDelDia.agente_id WHERE  HoraInicio > HoraInicialDefinida AND USUARI_ConsInte__PROYEC_b = " . $intIdHuesped_p . " Order By USUARI_Nombre____b";

            $strSQLCantidadFilas_t = "SELECT COUNT(1) AS cantidad FROM {$BaseDatos_systema}.qryUSUARI_usuarios_agentes JOIN {$BaseDatos_systema}.qrySesionesDelDia ON IdAgente = qrySesionesDelDia.agente_id WHERE USUARI_ConsInte__b = '{$id_usuari}' AND HoraInicio > HoraInicialDefinida AND USUARI_ConsInte__PROYEC_b = '{$intIdHuesped_p}' ORDER BY USUARI_Nombre____b";

            $intCantidadFilas_t = $mysqli->query($strSQLCantidadFilas_t)->fetch_object()->cantidad;

            $intCantidadPaginas = ceil($intCantidadFilas_t / $intLimite_p);

            // $strSQLReporte_t = "select USUARI_ConsInte__b as Id, USUARI_Nombre____b as Nombre, USUARI_Correo___b as Correo, date_format(timediff(HoraInicio, HoraInicialDefinida), '%H:%i:%S') as Retraso, HoraInicialDefinida, HoraInicio as HoraInicialReal FROM " . $BaseDatos_systema . ".qryUSUARI_usuarios_agentes JOIN " . $BaseDatos_systema . ".qrySesionesDelDia ON IdAgente = qrySesionesDelDia.agente_id WHERE  HoraInicio > HoraInicialDefinida AND USUARI_ConsInte__PROYEC_b = " . $intIdHuesped_p . " Order By USUARI_Nombre____b DESC LIMIT 0," . $intLimite_p;
            
           $strSQLReporte_t = "SELECT USUARI_ConsInte__b AS Id, USUARI_Nombre____b AS Nombre, USUARI_Correo___b AS Correo, DATE_FORMAT(TIMEDIFF(HoraInicio, HoraInicialDefinida), '%H:%i:%S') AS Retraso, HoraInicialDefinida, HoraInicio AS HoraInicialReal FROM {$BaseDatos_systema}.qryUSUARI_usuarios_agentes JOIN {$BaseDatos_systema}.qrySesionesDelDia ON IdAgente = qrySesionesDelDia.agente_id WHERE USUARI_ConsInte__b = '{$id_usuari}' AND HoraInicio > HoraInicialDefinida AND USUARI_ConsInte__PROYEC_b = '{$intIdHuesped_p}' ORDER BY USUARI_Nombre____b DESC LIMIT 0 , {$intLimite_p}";

            $strSQLReporteNoLimit_t = "SELECT USUARI_ConsInte__b AS Id, USUARI_Nombre____b AS Nombre, USUARI_Correo___b AS Correo, DATE_FORMAT(TIMEDIFF(HoraInicio, HoraInicialDefinida), '%H:%i:%S') AS Retraso, HoraInicialDefinida, HoraInicio AS HoraInicialReal FROM {$BaseDatos_systema}.qryUSUARI_usuarios_agentes JOIN {$BaseDatos_systema}.qrySesionesDelDia ON IdAgente = qrySesionesDelDia.agente_id WHERE USUARI_ConsInte__b = '{$id_usuari}' AND HoraInicio > HoraInicialDefinida AND USUARI_ConsInte__PROYEC_b = '{$intIdHuesped_p}' ORDER BY USUARI_Nombre____b DESC";

            return [$intCantidadFilas_t, $intCantidadPaginas, $strSQLReporte_t, $strSQLReporteNoLimit_t, "LLEGARON TARDE"];

            break;
        case '5':

            // $strSQLCantidadFilas_t = "SELECT COUNT(1) AS cantidad FROM " . $BaseDatos_systema . ".qryUSUARI_usuarios_agentes JOIN " . $BaseDatos_systema . ".qrySesionesDelDia ON IdAgente = qrySesionesDelDia.agente_id WHERE  HoraInicio <= HoraInicialDefinida and USUARI_ConsInte__PROYEC_b = " . $intIdHuesped_p . " order by USUARI_Nombre____b";
            
            $strSQLCantidadFilas_t = "SELECT COUNT(1) AS cantidad FROM {$BaseDatos_systema}.qryUSUARI_usuarios_agentes JOIN {$BaseDatos_systema}.qrySesionesDelDia ON IdAgente = qrySesionesDelDia.agente_id WHERE USUARI_ConsInte__b = '{$id_usuari}' AND HoraInicio <= HoraInicialDefinida AND USUARI_ConsInte__PROYEC_b = '{$intIdHuesped_p}' ORDER BY USUARI_Nombre____b";

            $intCantidadFilas_t = $mysqli->query($strSQLCantidadFilas_t)->fetch_object()->cantidad;

            $intCantidadPaginas = ceil($intCantidadFilas_t / $intLimite_p);

            // $strSQLReporte_t = "select USUARI_ConsInte__b as Id, USUARI_Nombre____b as Nombre, USUARI_Correo___b as Correo, HoraInicialDefinida, HoraInicio as HoraInicialReal FROM " . $BaseDatos_systema . ".qryUSUARI_usuarios_agentes JOIN " . $BaseDatos_systema . ".qrySesionesDelDia ON IdAgente = qrySesionesDelDia.agente_id WHERE  HoraInicio <= HoraInicialDefinida and USUARI_ConsInte__PROYEC_b = " . $intIdHuesped_p . " order by USUARI_Nombre____b DESC LIMIT 0," . $intLimite_p;

            $strSQLReporte_t = "SELECT USUARI_ConsInte__b AS Id, USUARI_Nombre____b AS Nombre, USUARI_Correo___b AS Correo, HoraInicialDefinida, HoraInicio AS HoraInicialReal FROM {$BaseDatos_systema}.qryUSUARI_usuarios_agentes JOIN {$BaseDatos_systema}.qrySesionesDelDia ON IdAgente = qrySesionesDelDia.agente_id WHERE USUARI_ConsInte__b = '{$id_usuari}' AND HoraInicio <= HoraInicialDefinida AND USUARI_ConsInte__PROYEC_b = '{$intIdHuesped_p}' ORDER BY USUARI_Nombre____b DESC LIMIT 0 , {$intLimite_p}";

            $strSQLReporteNoLimit_t = "SELECT USUARI_ConsInte__b AS Id, USUARI_Nombre____b AS Nombre, USUARI_Correo___b AS Correo, HoraInicialDefinida, HoraInicio AS HoraInicialReal FROM {$BaseDatos_systema}.qryUSUARI_usuarios_agentes JOIN {$BaseDatos_systema}.qrySesionesDelDia ON IdAgente = qrySesionesDelDia.agente_id WHERE USUARI_ConsInte__b = '{$id_usuari}' AND HoraInicio <= HoraInicialDefinida AND USUARI_ConsInte__PROYEC_b = '{$intIdHuesped_p}' ORDER BY USUARI_Nombre____b DESC";

            return [$intCantidadFilas_t, $intCantidadPaginas, $strSQLReporte_t, $strSQLReporteNoLimit_t, "LLEGARON A TIEMPO"];

            break;
        case '6':

            // $strSQLCantidadFilas_t = "SELECT COUNT(1) AS cantidad FROM " . $BaseDatos_systema . ".qryUSUARI_usuarios_agentes JOIN " . $BaseDatos_systema . ".qrySesionesDelDia ON IdAgente = qrySesionesDelDia.agente_id WHERE  HoraFin < HoraFinalDefinida and USUARI_ConsInte__PROYEC_b = " . $intIdHuesped_p . " Order By USUARI_Nombre____b";

            $strSQLCantidadFilas_t = "SELECT COUNT(1) AS cantidad FROM {$BaseDatos_systema}.qryUSUARI_usuarios_agentes JOIN {$BaseDatos_systema}.qrySesionesDelDia ON IdAgente = qrySesionesDelDia.agente_id WHERE USUARI_ConsInte__b = '{$id_usuari}' AND HoraFin < HoraFinalDefinida AND USUARI_ConsInte__PROYEC_b = '{$intIdHuesped_p}' ORDER BY USUARI_Nombre____b";


            $intCantidadFilas_t = $mysqli->query($strSQLCantidadFilas_t)->fetch_object()->cantidad;

            $intCantidadPaginas = ceil($intCantidadFilas_t / $intLimite_p);

            // $strSQLReporte_t = "select USUARI_ConsInte__b as Id, USUARI_Nombre____b as Nombre, USUARI_Correo___b as Correo, date_format(timediff(HoraFinalDefinida, HoraFin), '%H:%i:%S') as TiempoFaltante, HoraFinalDefinida, HoraFin as HoraFinalReal FROM " . $BaseDatos_systema . ".qryUSUARI_usuarios_agentes JOIN " . $BaseDatos_systema . ".qrySesionesDelDia ON IdAgente = qrySesionesDelDia.agente_id WHERE  HoraFin < HoraFinalDefinida and USUARI_ConsInte__PROYEC_b = " . $intIdHuesped_p . " Order By USUARI_Nombre____b DESC LIMIT 0," . $intLimite_p;


            $strSQLReporte_t = "SELECT USUARI_ConsInte__b AS Id, USUARI_Nombre____b AS Nombre, USUARI_Correo___b AS Correo, DATE_FORMAT(TIMEDIFF(HoraFinalDefinida, HoraFin), '%H:%i:%S') AS TiempoFaltante, HoraFinalDefinida, HoraFin AS HoraFinalReal FROM {$BaseDatos_systema}.qryUSUARI_usuarios_agentes JOIN {$BaseDatos_systema}.qrySesionesDelDia ON IdAgente = qrySesionesDelDia.agente_id WHERE USUARI_ConsInte__b = '{$id_usuari}' and HoraFin < HoraFinalDefinida AND USUARI_ConsInte__PROYEC_b = '{$intIdHuesped_p}' ORDER BY USUARI_Nombre____b DESC LIMIT 0 , {$intLimite_p}";

            $strSQLReporteNoLimit_t = "SELECT USUARI_ConsInte__b AS Id, USUARI_Nombre____b AS Nombre, USUARI_Correo___b AS Correo, DATE_FORMAT(TIMEDIFF(HoraFinalDefinida, HoraFin), '%H:%i:%S') AS TiempoFaltante, HoraFinalDefinida, HoraFin AS HoraFinalReal FROM {$BaseDatos_systema}.qryUSUARI_usuarios_agentes JOIN {$BaseDatos_systema}.qrySesionesDelDia ON IdAgente = qrySesionesDelDia.agente_id WHERE USUARI_ConsInte__b = '{$id_usuari}' and HoraFin < HoraFinalDefinida AND USUARI_ConsInte__PROYEC_b = '{$intIdHuesped_p}' ORDER BY USUARI_Nombre____b DESC";

            return [$intCantidadFilas_t, $intCantidadPaginas, $strSQLReporte_t, $strSQLReporteNoLimit_t, "SE FUERON ANTES"];

            break;
        case '7':

            // $strSQLCantidadFilas_t = "SELECT COUNT(1) AS cantidad FROM " . $BaseDatos_systema . ".qryUSUARI_usuarios_agentes JOIN " . $BaseDatos_systema . ".qrySesionesDelDia ON IdAgente = qrySesionesDelDia.agente_id WHERE  HoraFin >= HoraFinalDefinida and USUARI_ConsInte__PROYEC_b = " . $intIdHuesped_p . " Order By USUARI_Nombre____b";

            $strSQLCantidadFilas_t = "SELECT COUNT(1) AS cantidad FROM {$BaseDatos_systema}.qryUSUARI_usuarios_agentes JOIN {$BaseDatos_systema}.qrySesionesDelDia ON IdAgente = qrySesionesDelDia.agente_id WHERE USUARI_ConsInte__b = '{$id_usuari}' AND HoraFin >= HoraFinalDefinida AND USUARI_ConsInte__PROYEC_b = '{$intIdHuesped_p}' ORDER BY USUARI_Nombre____b";

            $intCantidadFilas_t = $mysqli->query($strSQLCantidadFilas_t)->fetch_object()->cantidad;

            $intCantidadPaginas = ceil($intCantidadFilas_t / $intLimite_p);

            // $strSQLReporte_t = "select USUARI_ConsInte__b as Id, USUARI_Nombre____b as Nombre, USUARI_Correo___b as Correo, HoraFinalDefinida, HoraFin as HoraFinalReal FROM " . $BaseDatos_systema . ".qryUSUARI_usuarios_agentes JOIN " . $BaseDatos_systema . ".qrySesionesDelDia ON IdAgente = qrySesionesDelDia.agente_id WHERE  HoraFin >= HoraFinalDefinida and USUARI_ConsInte__PROYEC_b = " . $intIdHuesped_p . " Order By USUARI_Nombre____b DESC LIMIT 0," . $intLimite_p;

            $strSQLReporte_t =  "SELECT USUARI_ConsInte__b AS Id, USUARI_Nombre____b AS Nombre, USUARI_Correo___b AS Correo, HoraFinalDefinida, HoraFin AS HoraFinalReal FROM {$BaseDatos_systema}.qryUSUARI_usuarios_agentes JOIN {$BaseDatos_systema}.qrySesionesDelDia ON IdAgente = qrySesionesDelDia.agente_id WHERE USUARI_ConsInte__b = '{$id_usuari}' AND HoraFin >= HoraFinalDefinida AND USUARI_ConsInte__PROYEC_b = '{$intIdHuesped_p}' ORDER BY USUARI_Nombre____b DESC LIMIT 0 , {$intLimite_p}";

            $strSQLReporteNoLimit_t = "SELECT USUARI_ConsInte__b AS Id, USUARI_Nombre____b AS Nombre, USUARI_Correo___b AS Correo, HoraFinalDefinida, HoraFin AS HoraFinalReal FROM {$BaseDatos_systema}.qryUSUARI_usuarios_agentes JOIN {$BaseDatos_systema}.qrySesionesDelDia ON IdAgente = qrySesionesDelDia.agente_id WHERE USUARI_ConsInte__b = '{$id_usuari}' AND HoraFin >= HoraFinalDefinida AND USUARI_ConsInte__PROYEC_b = '{$intIdHuesped_p}' ORDER BY USUARI_Nombre____b DESC";

            return [$intCantidadFilas_t, $intCantidadPaginas, $strSQLReporte_t, $strSQLReporteNoLimit_t, "SE FUERON A TIEMPO"];

            break;
        case '8':

            // $strSQLCantidadFilas_t = "SELECT COUNT(1) AS cantidad FROM {$BaseDatos_systema}.qryUSUARI_usuarios_agentes JOIN {$BaseDatos_systema}.qrySesionesDelDia ON IdAgente = qrySesionesDelDia.agente_id WHERE  timediff(HoraFinalDefinida, HoraInicialDefinida) > date_format(timediff(HoraFin, HoraInicio), '%H:%i:%S') and USUARI_ConsInte__PROYEC_b = " . $intIdHuesped_p . "  Order By USUARI_Nombre____b";

            $strSQLCantidadFilas_t = "SELECT COUNT(1) AS cantidad FROM {$BaseDatos_systema}.qryUSUARI_usuarios_agentes JOIN {$BaseDatos_systema}.qrySesionesDelDia ON IdAgente = qrySesionesDelDia.agente_id WHERE USUARI_ConsInte__b = '{$id_usuari}' AND TIMEDIFF(HoraFinalDefinida, HoraInicialDefinida) > DATE_FORMAT(TIMEDIFF(HoraFin, HoraInicio), '%H:%i:%S') AND USUARI_ConsInte__PROYEC_b = '{$intIdHuesped_p}' ORDER BY USUARI_Nombre____b";

            $intCantidadFilas_t = $mysqli->query($strSQLCantidadFilas_t)->fetch_object()->cantidad;

            $intCantidadPaginas = ceil($intCantidadFilas_t / $intLimite_p);

            // $strSQLReporte_t = "select USUARI_ConsInte__b as Id, USUARI_Nombre____b as Nombre, USUARI_Correo___b as Correo, date_format(timediff(timediff(HoraFinalDefinida, HoraInicialDefinida), timediff(HoraFin, HoraInicio)), '%H:%i:%S') as TiempoFaltante, date_format(timediff(HoraFinalDefinida, HoraInicialDefinida), '%H:%i:%S') as DuracionDefinida, date_format(timediff(HoraFin, HoraInicio), '%H:%i:%S') as DuracionReal, HoraInicialDefinida, HoraInicio as HoraInicialReal, HoraFinalDefinida, HoraFin as HoraFinalReal FROM {$BaseDatos_systema}.qryUSUARI_usuarios_agentes JOIN {$BaseDatos_systema}.qrySesionesDelDia ON IdAgente = qrySesionesDelDia.agente_id WHERE  timediff(HoraFinalDefinida, HoraInicialDefinida) > date_format(timediff(HoraFin, HoraInicio), '%H:%i:%S') and USUARI_ConsInte__PROYEC_b = " . $intIdHuesped_p . "  Order By USUARI_Nombre____b DESC LIMIT 0," . $intLimite_p;

            $strSQLReporte_t =  "SELECT USUARI_ConsInte__b AS Id, USUARI_Nombre____b AS Nombre, USUARI_Correo___b AS Correo, DATE_FORMAT(TIMEDIFF(TIMEDIFF(HoraFinalDefinida, HoraInicialDefinida), TIMEDIFF(HoraFin, HoraInicio)), '%H:%i:%S') AS TiempoFaltante, DATE_FORMAT(TIMEDIFF(HoraFinalDefinida, HoraInicialDefinida), '%H:%i:%S') AS DuracionDefinida, DATE_FORMAT(TIMEDIFF(HoraFin, HoraInicio), '%H:%i:%S') AS DuracionReal, HoraInicialDefinida, HoraInicio AS HoraInicialReal, HoraFinalDefinida, HoraFin AS HoraFinalReal FROM {$BaseDatos_systema}.qryUSUARI_usuarios_agentes JOIN {$BaseDatos_systema}.qrySesionesDelDia ON IdAgente = qrySesionesDelDia.agente_id WHERE TIMEDIFF(HoraFinalDefinida, HoraInicialDefinida) > DATE_FORMAT(TIMEDIFF(HoraFin, HoraInicio), '%H:%i:%S') AND USUARI_ConsInte__PROYEC_b = '{$intIdHuesped_p}' AND USUARI_ConsInte__b = '{$id_usuari}' ORDER BY USUARI_Nombre____b DESC LIMIT 0 , {$intLimite_p}";

            $strSQLReporteNoLimit_t = "SELECT USUARI_ConsInte__b AS Id, USUARI_Nombre____b AS Nombre, USUARI_Correo___b AS Correo, DATE_FORMAT(TIMEDIFF(TIMEDIFF(HoraFinalDefinida, HoraInicialDefinida), TIMEDIFF(HoraFin, HoraInicio)), '%H:%i:%S') AS TiempoFaltante, DATE_FORMAT(TIMEDIFF(HoraFinalDefinida, HoraInicialDefinida), '%H:%i:%S') AS DuracionDefinida, DATE_FORMAT(TIMEDIFF(HoraFin, HoraInicio), '%H:%i:%S') AS DuracionReal, HoraInicialDefinida, HoraInicio AS HoraInicialReal, HoraFinalDefinida, HoraFin AS HoraFinalReal FROM {$BaseDatos_systema}.qryUSUARI_usuarios_agentes JOIN {$BaseDatos_systema}.qrySesionesDelDia ON IdAgente = qrySesionesDelDia.agente_id WHERE TIMEDIFF(HoraFinalDefinida, HoraInicialDefinida) > DATE_FORMAT(TIMEDIFF(HoraFin, HoraInicio), '%H:%i:%S') AND USUARI_ConsInte__PROYEC_b = '{$intIdHuesped_p}' AND USUARI_ConsInte__b = '{$id_usuari}' ORDER BY USUARI_Nombre____b DESC";

            return [$intCantidadFilas_t, $intCantidadPaginas, $strSQLReporte_t, $strSQLReporteNoLimit_t, "SESIONES CORTAS"];

            break;
        case '9':

            // $strSQLCantidadFilas_t = "SELECT COUNT(1) AS cantidad FROM " . $BaseDatos_systema . ".qryUSUARI_usuarios_agentes JOIN " . $BaseDatos_systema . ".qrySesionesDelDia ON IdAgente = qrySesionesDelDia.agente_id WHERE  timediff(HoraFinalDefinida, HoraInicialDefinida) <= date_format(timediff(HoraFin, HoraInicio), '%H:%i:%S') and USUARI_ConsInte__PROYEC_b = " . $intIdHuesped_p . " Order By USUARI_Nombre____b";

            $strSQLCantidadFilas_t = "SELECT COUNT(1) AS cantidad FROM {$BaseDatos_systema}.qryUSUARI_usuarios_agentes JOIN {$BaseDatos_systema}.qrySesionesDelDia ON IdAgente = qrySesionesDelDia.agente_id WHERE TIMEDIFF(HoraFinalDefinida, HoraInicialDefinida) <= DATE_FORMAT(TIMEDIFF(HoraFin, HoraInicio), '%H:%i:%S') AND USUARI_ConsInte__b = '{$id_usuari}' AND USUARI_ConsInte__PROYEC_b = '{$intIdHuesped_p}' ORDER BY USUARI_Nombre____b";

            $intCantidadFilas_t = $mysqli->query($strSQLCantidadFilas_t)->fetch_object()->cantidad;

            $intCantidadPaginas = ceil($intCantidadFilas_t / $intLimite_p);

            // $strSQLReporte_t = "select USUARI_ConsInte__b as Id, USUARI_Nombre____b as Nombre, USUARI_Correo___b as Correo, date_format(timediff(HoraFinalDefinida, HoraInicialDefinida), '%H:%i:%S') as DuracionDefinida, date_format(timediff(HoraFin, HoraInicio), '%H:%i:%S') as DuracionReal, HoraInicialDefinida, HoraInicio as HoraInicialReal, HoraFinalDefinida, HoraFin as HoraFinalReal FROM " . $BaseDatos_systema . ".qryUSUARI_usuarios_agentes JOIN " . $BaseDatos_systema . ".qrySesionesDelDia ON IdAgente = qrySesionesDelDia.agente_id WHERE  timediff(HoraFinalDefinida, HoraInicialDefinida) <= date_format(timediff(HoraFin, HoraInicio), '%H:%i:%S') and USUARI_ConsInte__PROYEC_b = '{$intIdHuesped_p}'  Order By USUARI_Nombre____b DESC LIMIT 0," . $intLimite_p;

            $strSQLReporte_t = "SELECT  USUARI_ConsInte__b AS Id, USUARI_Nombre____b AS Nombre, USUARI_Correo___b AS Correo, DATE_FORMAT(TIMEDIFF(HoraFinalDefinida, HoraInicialDefinida), '%H:%i:%S') AS DuracionDefinida, DATE_FORMAT(TIMEDIFF(HoraFin, HoraInicio), '%H:%i:%S') AS DuracionReal, HoraInicialDefinida, HoraInicio AS HoraInicialReal, HoraFinalDefinida, HoraFin AS HoraFinalReal FROM {$BaseDatos_systema}.qryUSUARI_usuarios_agentes JOIN {$BaseDatos_systema}.qrySesionesDelDia ON IdAgente = qrySesionesDelDia.agente_id WHERE TIMEDIFF(HoraFinalDefinida, HoraInicialDefinida) <= DATE_FORMAT(TIMEDIFF(HoraFin, HoraInicio), '%H:%i:%S') AND USUARI_ConsInte__b = '{$id_usuari}' AND USUARI_ConsInte__PROYEC_b = '{$intIdHuesped_p}' ORDER BY USUARI_Nombre____b DESC LIMIT 0 , {$intLimite_p}";

            $strSQLReporteNoLimit_t = "SELECT  USUARI_ConsInte__b AS Id, USUARI_Nombre____b AS Nombre, USUARI_Correo___b AS Correo, DATE_FORMAT(TIMEDIFF(HoraFinalDefinida, HoraInicialDefinida), '%H:%i:%S') AS DuracionDefinida, DATE_FORMAT(TIMEDIFF(HoraFin, HoraInicio), '%H:%i:%S') AS DuracionReal, HoraInicialDefinida, HoraInicio AS HoraInicialReal, HoraFinalDefinida, HoraFin AS HoraFinalReal FROM {$BaseDatos_systema}.qryUSUARI_usuarios_agentes JOIN {$BaseDatos_systema}.qrySesionesDelDia ON IdAgente = qrySesionesDelDia.agente_id WHERE TIMEDIFF(HoraFinalDefinida, HoraInicialDefinida) <= DATE_FORMAT(TIMEDIFF(HoraFin, HoraInicio), '%H:%i:%S') AND USUARI_ConsInte__b = '{$id_usuari}' AND USUARI_ConsInte__PROYEC_b = '{$intIdHuesped_p}' ORDER BY USUARI_Nombre____b DESC";

            return [$intCantidadFilas_t, $intCantidadPaginas, $strSQLReporte_t, $strSQLReporteNoLimit_t, "SESIONES DURACION OK"];

            break;
        case '10':

            // $strSQLCantidadFilas_t = "SELECT COUNT(1) AS cantidad FROM " . $BaseDatos_systema . ".qryPausasProgramadasDelDia left join dyalogo_telefonia.dy_v_historico_descansos ON IdAgente = agente_id and USUPAU_PausasId_b = tipo_descanso_id WHERE fecha_hora_inicio >= current_date() and USUPAU_Tipo_b = 1 and timediff(HoraFinalProgramada, HoraInicialProgramada) < timediff(fecha_hora_fin,fecha_hora_inicio) and USUARI_ConsInte__PROYEC_b = " . $intIdHuesped_p . " Order by USUARI_Nombre____b";

            $strSQLCantidadFilas_t = "SELECT COUNT(1) AS cantidad FROM {$BaseDatos_systema}.qryPausasProgramadasDelDia LEFT JOIN {$BaseDatos_telefonia}.dy_v_historico_descansos ON IdAgente = agente_id AND USUPAU_PausasId_b = tipo_descanso_id WHERE fecha_hora_inicio >= CURRENT_DATE() AND USUPAU_Tipo_b = 1 AND USUARI_ConsInte__b = '{$id_usuari}' AND TIMEDIFF(HoraFinalProgramada, HoraInicialProgramada) < TIMEDIFF(fecha_hora_fin, fecha_hora_inicio) AND USUARI_ConsInte__PROYEC_b = '{$intIdHuesped_p}' ORDER BY USUARI_Nombre____b";

            $intCantidadFilas_t = $mysqli->query($strSQLCantidadFilas_t)->fetch_object()->cantidad;

            $intCantidadPaginas = ceil($intCantidadFilas_t / $intLimite_p);

            // $strSQLReporte_t = "select USUARI_ConsInte__b as Id, USUARI_Nombre____b as Nombre, USUARI_Correo___b as Correo, tipo_descanso_nombre as Pausa, timediff(timediff(fecha_hora_fin,fecha_hora_inicio), timediff(HoraFinalProgramada, HoraInicialProgramada)) as Exceso, timediff(HoraFinalProgramada, HoraInicialProgramada) DuracionProgramada, timediff(fecha_hora_fin,fecha_hora_inicio) as DuracionReal, HoraInicialProgramada, date_format(fecha_hora_inicio, '%H:%i:%S') as HoraInicialReal, HoraFinalProgramada, date_format(fecha_hora_fin , '%H:%i:%S') as HoraFinalReal FROM " . $BaseDatos_systema . ".qryPausasProgramadasDelDia left join dyalogo_telefonia.dy_v_historico_descansos ON IdAgente = agente_id and USUPAU_PausasId_b = tipo_descanso_id WHERE fecha_hora_inicio >= current_date() and USUPAU_Tipo_b = 1 and timediff(HoraFinalProgramada, HoraInicialProgramada) < timediff(fecha_hora_fin,fecha_hora_inicio) and USUARI_ConsInte__PROYEC_b = " . $intIdHuesped_p . " Order by USUARI_Nombre____b DESC LIMIT 0," . $intLimite_p;

            $strSQLReporte_t = "SELECT USUARI_ConsInte__b AS Id, USUARI_Nombre____b AS Nombre, USUARI_Correo___b AS Correo, tipo_descanso_nombre AS Pausa, TIMEDIFF(TIMEDIFF(fecha_hora_fin, fecha_hora_inicio), TIMEDIFF(HoraFinalProgramada, HoraInicialProgramada)) AS Exceso, TIMEDIFF(HoraFinalProgramada, HoraInicialProgramada) DuracionProgramada, TIMEDIFF(fecha_hora_fin, fecha_hora_inicio) AS DuracionReal, HoraInicialProgramada, DATE_FORMAT(fecha_hora_inicio, '%H:%i:%S') AS HoraInicialReal, HoraFinalProgramada, DATE_FORMAT(fecha_hora_fin, '%H:%i:%S') AS HoraFinalReal FROM {$BaseDatos_systema}.qryPausasProgramadasDelDia LEFT JOIN {$BaseDatos_telefonia}.dy_v_historico_descansos ON IdAgente = agente_id AND USUPAU_PausasId_b = tipo_descanso_id WHERE fecha_hora_inicio >= CURRENT_DATE() AND USUPAU_Tipo_b = 1 AND TIMEDIFF(HoraFinalProgramada, HoraInicialProgramada) < TIMEDIFF(fecha_hora_fin, fecha_hora_inicio) AND USUARI_ConsInte__PROYEC_b = '{$intIdHuesped_p}' AND USUARI_ConsInte__b = '{$id_usuari}' ORDER BY USUARI_Nombre____b DESC LIMIT 0 , {$intLimite_p}";

            $strSQLReporteNoLimit_t = "SELECT USUARI_ConsInte__b AS Id, USUARI_Nombre____b AS Nombre, USUARI_Correo___b AS Correo, tipo_descanso_nombre AS Pausa, TIMEDIFF(TIMEDIFF(fecha_hora_fin, fecha_hora_inicio), TIMEDIFF(HoraFinalProgramada, HoraInicialProgramada)) AS Exceso, TIMEDIFF(HoraFinalProgramada, HoraInicialProgramada) DuracionProgramada, TIMEDIFF(fecha_hora_fin, fecha_hora_inicio) AS DuracionReal, HoraInicialProgramada, DATE_FORMAT(fecha_hora_inicio, '%H:%i:%S') AS HoraInicialReal, HoraFinalProgramada, DATE_FORMAT(fecha_hora_fin, '%H:%i:%S') AS HoraFinalReal FROM {$BaseDatos_systema}.qryPausasProgramadasDelDia LEFT JOIN {$BaseDatos_telefonia}.dy_v_historico_descansos ON IdAgente = agente_id AND USUPAU_PausasId_b = tipo_descanso_id WHERE fecha_hora_inicio >= CURRENT_DATE() AND USUPAU_Tipo_b = 1 AND TIMEDIFF(HoraFinalProgramada, HoraInicialProgramada) < TIMEDIFF(fecha_hora_fin, fecha_hora_inicio) AND USUARI_ConsInte__PROYEC_b = '{$intIdHuesped_p}' AND USUARI_ConsInte__b = '{$id_usuari}' ORDER BY USUARI_Nombre____b DESC";

            return [$intCantidadFilas_t, $intCantidadPaginas, $strSQLReporte_t, $strSQLReporteNoLimit_t, "PausasConHorarioMuyLargas"];

            break;
        case '11':

            // $strSQLCantidadFilas_t = "SELECT COUNT(1) AS cantidad FROM " . $BaseDatos_systema . ".qryPausasProgramadasDelDia left join dyalogo_telefonia.dy_v_historico_descansos ON IdAgente = agente_id and USUPAU_PausasId_b = tipo_descanso_id WHERE fecha_hora_inicio >= current_date() and USUPAU_Tipo_b = 1 and timediff(HoraFinalProgramada, HoraInicialProgramada) >= timediff(fecha_hora_fin,fecha_hora_inicio) and USUARI_ConsInte__PROYEC_b = " . $intIdHuesped_p . " Order by USUARI_Nombre____b";

            $strSQLCantidadFilas_t = "SELECT COUNT(1) AS cantidad FROM {$BaseDatos_systema}.qryPausasProgramadasDelDia LEFT JOIN {$BaseDatos_telefonia}.dy_v_historico_descansos ON IdAgente = agente_id AND USUPAU_PausasId_b = tipo_descanso_id WHERE fecha_hora_inicio >= CURRENT_DATE() AND USUPAU_Tipo_b = 1 AND TIMEDIFF(HoraFinalProgramada, HoraInicialProgramada) >= TIMEDIFF(fecha_hora_fin, fecha_hora_inicio) AND USUARI_ConsInte__b = '{$id_usuari}' AND USUARI_ConsInte__PROYEC_b = '{$intIdHuesped_p}' ORDER BY USUARI_Nombre____b";

            $intCantidadFilas_t = $mysqli->query($strSQLCantidadFilas_t)->fetch_object()->cantidad;

            $intCantidadPaginas = ceil($intCantidadFilas_t / $intLimite_p);

            // $strSQLReporte_t = "select USUARI_ConsInte__b as Id, USUARI_Nombre____b as Nombre, USUARI_Correo___b as Correo, tipo_descanso_nombre as Pausa, timediff(timediff(HoraFinalProgramada, HoraInicialProgramada), timediff(fecha_hora_fin,fecha_hora_inicio)) as TiempoAFavor, timediff(HoraFinalProgramada, HoraInicialProgramada) DuracionProgramada, timediff(fecha_hora_fin,fecha_hora_inicio) as DuracionReal, HoraInicialProgramada, date_format(fecha_hora_inicio, '%H:%i:%S') as HoraInicialReal, HoraFinalProgramada, date_format(fecha_hora_fin , '%H:%i:%S') as HoraFinalReal FROM " . $BaseDatos_systema . ".qryPausasProgramadasDelDia left join dyalogo_telefonia.dy_v_historico_descansos ON IdAgente = agente_id and USUPAU_PausasId_b = tipo_descanso_id WHERE fecha_hora_inicio >= current_date() and USUPAU_Tipo_b = 1 and timediff(HoraFinalProgramada, HoraInicialProgramada) >= timediff(fecha_hora_fin,fecha_hora_inicio) and USUARI_ConsInte__PROYEC_b = " . $intIdHuesped_p . " Order by USUARI_Nombre____b DESC LIMIT 0," . $intLimite_p;

            $strSQLReporte_t = "SELECT USUARI_ConsInte__b AS Id, USUARI_Nombre____b AS Nombre, USUARI_Correo___b AS Correo, tipo_descanso_nombre AS Pausa, TIMEDIFF(TIMEDIFF(HoraFinalProgramada, HoraInicialProgramada), TIMEDIFF(fecha_hora_fin, fecha_hora_inicio)) AS TiempoAFavor, TIMEDIFF(HoraFinalProgramada, HoraInicialProgramada) DuracionProgramada, TIMEDIFF(fecha_hora_fin, fecha_hora_inicio) AS DuracionReal, HoraInicialProgramada, DATE_FORMAT(fecha_hora_inicio, '%H:%i:%S') AS HoraInicialReal, HoraFinalProgramada, DATE_FORMAT(fecha_hora_fin, '%H:%i:%S') AS HoraFinalReal FROM {$BaseDatos_systema}.qryPausasProgramadasDelDia LEFT JOIN {$BaseDatos_telefonia}.dy_v_historico_descansos ON IdAgente = agente_id AND USUPAU_PausasId_b = tipo_descanso_id WHERE fecha_hora_inicio >= CURRENT_DATE() AND USUPAU_Tipo_b = 1 AND TIMEDIFF(HoraFinalProgramada, HoraInicialProgramada) >= TIMEDIFF(fecha_hora_fin, fecha_hora_inicio) AND USUARI_ConsInte__PROYEC_b = '{$intIdHuesped_p}' AND USUARI_ConsInte__b = '{$id_usuari}' ORDER BY USUARI_Nombre____b DESC LIMIT 0 , {$intLimite_p}";

            $strSQLReporteNoLimit_t = "SELECT USUARI_ConsInte__b AS Id, USUARI_Nombre____b AS Nombre, USUARI_Correo___b AS Correo, tipo_descanso_nombre AS Pausa, TIMEDIFF(TIMEDIFF(HoraFinalProgramada, HoraInicialProgramada), TIMEDIFF(fecha_hora_fin, fecha_hora_inicio)) AS TiempoAFavor, TIMEDIFF(HoraFinalProgramada, HoraInicialProgramada) DuracionProgramada, TIMEDIFF(fecha_hora_fin, fecha_hora_inicio) AS DuracionReal, HoraInicialProgramada, DATE_FORMAT(fecha_hora_inicio, '%H:%i:%S') AS HoraInicialReal, HoraFinalProgramada, DATE_FORMAT(fecha_hora_fin, '%H:%i:%S') AS HoraFinalReal FROM {$BaseDatos_systema}.qryPausasProgramadasDelDia LEFT JOIN {$BaseDatos_telefonia}.dy_v_historico_descansos ON IdAgente = agente_id AND USUPAU_PausasId_b = tipo_descanso_id WHERE fecha_hora_inicio >= CURRENT_DATE() AND USUPAU_Tipo_b = 1 AND TIMEDIFF(HoraFinalProgramada, HoraInicialProgramada) >= TIMEDIFF(fecha_hora_fin, fecha_hora_inicio) AND USUARI_ConsInte__PROYEC_b = '{$intIdHuesped_p}' AND USUARI_ConsInte__b = '{$id_usuari}' ORDER BY USUARI_Nombre____b DESC";

            return [$intCantidadFilas_t, $intCantidadPaginas, $strSQLReporte_t, $strSQLReporteNoLimit_t, "PausasConHorarioDuracionOk"];

            break;
        case '12':

            // $strSQLCantidadFilas_t = "SELECT COUNT(1) AS cantidad FROM " . $BaseDatos_systema . ".qryPausasProgramadasDelDia left join dyalogo_telefonia.dy_v_historico_descansos ON IdAgente = agente_id and USUPAU_PausasId_b = tipo_descanso_id WHERE fecha_hora_inicio >= current_date() and USUPAU_Tipo_b = 1 and (HoraInicialProgramada > date_format(fecha_hora_inicio, '%H:%i:%S') or date_format(fecha_hora_fin , '%H:%i:%S') > HoraFinalProgramada) and USUARI_ConsInte__PROYEC_b = " . $intIdHuesped_p . " Order by USUARI_Nombre____b";

            $strSQLCantidadFilas_t = "SELECT COUNT(1) AS cantidad FROM {$BaseDatos_systema}.qryPausasProgramadasDelDia LEFT JOIN {$BaseDatos_telefonia}.dy_v_historico_descansos ON IdAgente = agente_id AND USUPAU_PausasId_b = tipo_descanso_id WHERE fecha_hora_inicio >= CURRENT_DATE() AND USUPAU_Tipo_b = 1 AND (HoraInicialProgramada > DATE_FORMAT(fecha_hora_inicio, '%H:%i:%S') OR DATE_FORMAT(fecha_hora_fin, '%H:%i:%S') > HoraFinalProgramada) AND USUARI_ConsInte__PROYEC_b = '{$intIdHuesped_p}' AND USUARI_ConsInte__b = '{$id_usuari}' ORDER BY USUARI_Nombre____b;";

            $intCantidadFilas_t = $mysqli->query($strSQLCantidadFilas_t)->fetch_object()->cantidad;

            $intCantidadPaginas = ceil($intCantidadFilas_t / $intLimite_p);

            // $strSQLReporte_t = "select USUARI_ConsInte__b as Id, USUARI_Nombre____b as Nombre, USUARI_Correo___b as Correo, tipo_descanso_nombre as Pausa, IF(HoraInicialProgramada > date_format(fecha_hora_inicio, '%H:%i:%S'), timediff(HoraInicialProgramada, date_format(fecha_hora_inicio, '%H:%i:%S')),null) as SalioAntesPor, IF(date_format(fecha_hora_fin , '%H:%i:%S') > HoraFinalProgramada, timediff(date_format(fecha_hora_fin , '%H:%i:%S'), HoraFinalProgramada),null) as LlegoTardePor, HoraInicialProgramada, date_format(fecha_hora_inicio, '%H:%i:%S') as HoraInicialReal, HoraFinalProgramada, date_format(fecha_hora_fin , '%H:%i:%S') as HoraFinalReal, timediff(timediff(fecha_hora_fin,fecha_hora_inicio), timediff(HoraFinalProgramada, HoraInicialProgramada)) as TiempoDiferencia, timediff(HoraFinalProgramada, HoraInicialProgramada) DuracionProgramada, timediff(fecha_hora_fin,fecha_hora_inicio) as DuracionReal FROM " . $BaseDatos_systema . ".qryPausasProgramadasDelDia left join {$BaseDatos_telefonia}.dy_v_historico_descansos ON IdAgente = agente_id and USUPAU_PausasId_b = tipo_descanso_id WHERE fecha_hora_inicio >= current_date() and USUPAU_Tipo_b = 1 and (HoraInicialProgramada > date_format(fecha_hora_inicio, '%H:%i:%S') or date_format(fecha_hora_fin , '%H:%i:%S') > HoraFinalProgramada) and USUARI_ConsInte__PROYEC_b = " . $intIdHuesped_p . " Order by USUARI_Nombre____b DESC LIMIT 0," . $intLimite_p;

            $strSQLReporte_t = "SELECT USUARI_ConsInte__b AS Id, USUARI_Nombre____b AS Nombre, USUARI_Correo___b AS Correo, tipo_descanso_nombre AS Pausa, IF(HoraInicialProgramada > DATE_FORMAT(fecha_hora_inicio, '%H:%i:%S'), TIMEDIFF(HoraInicialProgramada, DATE_FORMAT(fecha_hora_inicio, '%H:%i:%S')), NULL) AS SalioAntesPor, IF(DATE_FORMAT(fecha_hora_fin, '%H:%i:%S') > HoraFinalProgramada, TIMEDIFF(DATE_FORMAT(fecha_hora_fin, '%H:%i:%S'), HoraFinalProgramada), NULL) AS LlegoTardePor, HoraInicialProgramada, DATE_FORMAT(fecha_hora_inicio, '%H:%i:%S') AS HoraInicialReal, HoraFinalProgramada, DATE_FORMAT(fecha_hora_fin, '%H:%i:%S') AS HoraFinalReal, TIMEDIFF(TIMEDIFF(fecha_hora_fin, fecha_hora_inicio), TIMEDIFF(HoraFinalProgramada, HoraInicialProgramada)) AS TiempoDiferencia, TIMEDIFF(HoraFinalProgramada, HoraInicialProgramada) DuracionProgramada, TIMEDIFF(fecha_hora_fin, fecha_hora_inicio) AS DuracionReal FROM {$BaseDatos_systema}.qryPausasProgramadasDelDia LEFT JOIN {$BaseDatos_telefonia}.dy_v_historico_descansos ON IdAgente = agente_id AND USUPAU_PausasId_b = tipo_descanso_id WHERE fecha_hora_inicio >= CURRENT_DATE() AND USUPAU_Tipo_b = 1 AND USUARI_ConsInte__b = '{$id_usuari}' AND (HoraInicialProgramada > DATE_FORMAT(fecha_hora_inicio, '%H:%i:%S') OR DATE_FORMAT(fecha_hora_fin, '%H:%i:%S') > HoraFinalProgramada) AND USUARI_ConsInte__PROYEC_b = '{$intIdHuesped_p}' ORDER BY USUARI_Nombre____b DESC LIMIT 0 , {$intLimite_p}";

            $strSQLReporteNoLimit_t = "SELECT USUARI_ConsInte__b AS Id, USUARI_Nombre____b AS Nombre, USUARI_Correo___b AS Correo, tipo_descanso_nombre AS Pausa, IF(HoraInicialProgramada > DATE_FORMAT(fecha_hora_inicio, '%H:%i:%S'), TIMEDIFF(HoraInicialProgramada, DATE_FORMAT(fecha_hora_inicio, '%H:%i:%S')), NULL) AS SalioAntesPor, IF(DATE_FORMAT(fecha_hora_fin, '%H:%i:%S') > HoraFinalProgramada, TIMEDIFF(DATE_FORMAT(fecha_hora_fin, '%H:%i:%S'), HoraFinalProgramada), NULL) AS LlegoTardePor, HoraInicialProgramada, DATE_FORMAT(fecha_hora_inicio, '%H:%i:%S') AS HoraInicialReal, HoraFinalProgramada, DATE_FORMAT(fecha_hora_fin, '%H:%i:%S') AS HoraFinalReal, TIMEDIFF(TIMEDIFF(fecha_hora_fin, fecha_hora_inicio), TIMEDIFF(HoraFinalProgramada, HoraInicialProgramada)) AS TiempoDiferencia, TIMEDIFF(HoraFinalProgramada, HoraInicialProgramada) DuracionProgramada, TIMEDIFF(fecha_hora_fin, fecha_hora_inicio) AS DuracionReal FROM {$BaseDatos_systema}.qryPausasProgramadasDelDia LEFT JOIN {$BaseDatos_telefonia}.dy_v_historico_descansos ON IdAgente = agente_id AND USUPAU_PausasId_b = tipo_descanso_id WHERE fecha_hora_inicio >= CURRENT_DATE() AND USUPAU_Tipo_b = 1 AND USUARI_ConsInte__b = '{$id_usuari}' AND (HoraInicialProgramada > DATE_FORMAT(fecha_hora_inicio, '%H:%i:%S') OR DATE_FORMAT(fecha_hora_fin, '%H:%i:%S') > HoraFinalProgramada) AND USUARI_ConsInte__PROYEC_b = '{$intIdHuesped_p}' ORDER BY USUARI_Nombre____b DESC";

            return [$intCantidadFilas_t, $intCantidadPaginas, $strSQLReporte_t, $strSQLReporteNoLimit_t, "PausasConHorarioIncumplidas"];

            break;
        case '13':

            // $strSQLCantidadFilas_t = "SELECT COUNT(1) AS cantidad FROM " . $BaseDatos_systema . ".qryPausasProgramadasDelDia left join dyalogo_telefonia.dy_v_historico_descansos ON IdAgente = agente_id and USUPAU_PausasId_b = tipo_descanso_id WHERE fecha_hora_inicio >= current_date() and USUPAU_Tipo_b = 1 and (HoraInicialProgramada <= date_format(fecha_hora_inicio, '%H:%i:%S') and date_format(fecha_hora_fin , '%H:%i:%S') <= HoraFinalProgramada) and USUARI_ConsInte__PROYEC_b = " . $intIdHuesped_p . " Order by USUARI_Nombre____b";

            $strSQLCantidadFilas_t = "SELECT COUNT(1) AS cantidad FROM {$BaseDatos_systema}.qryPausasProgramadasDelDia LEFT JOIN {$BaseDatos_telefonia}.dy_v_historico_descansos ON IdAgente = agente_id AND USUPAU_PausasId_b = tipo_descanso_id WHERE fecha_hora_inicio >= CURRENT_DATE() AND USUPAU_Tipo_b = 1 AND (HoraInicialProgramada <= DATE_FORMAT(fecha_hora_inicio, '%H:%i:%S') AND DATE_FORMAT(fecha_hora_fin, '%H:%i:%S') <= HoraFinalProgramada) AND USUARI_ConsInte__PROYEC_b = '{$intIdHuesped_p}' AND USUARI_ConsInte__b = '{$id_usuari}' ORDER BY USUARI_Nombre____b";

            $intCantidadFilas_t = $mysqli->query($strSQLCantidadFilas_t)->fetch_object()->cantidad;

            $intCantidadPaginas = ceil($intCantidadFilas_t / $intLimite_p);

            // $strSQLReporte_t = "select USUARI_ConsInte__b as Id, USUARI_Nombre____b as Nombre, USUARI_Correo___b as Correo, tipo_descanso_nombre as Pausa, IF(HoraInicialProgramada > date_format(fecha_hora_inicio, '%H:%i:%S'),timediff(HoraInicialProgramada, date_format(fecha_hora_inicio, '%H:%i:%S')),null) as SalioAntesPor, IF(date_format(fecha_hora_fin , '%H:%i:%S') > HoraFinalProgramada, timediff(date_format(fecha_hora_fin, '%H:%i:%S'), HoraFinalProgramada),null) as LlegoTardePor, HoraInicialProgramada, date_format(fecha_hora_inicio, '%H:%i:%S') as HoraInicialReal, HoraFinalProgramada, date_format(fecha_hora_fin , '%H:%i:%S') as HoraFinalReal, timediff(timediff(fecha_hora_fin,fecha_hora_inicio), timediff(HoraFinalProgramada, HoraInicialProgramada)) as TiempoDiferencia, timediff(HoraFinalProgramada, HoraInicialProgramada) DuracionProgramada, timediff(fecha_hora_fin,fecha_hora_inicio) as DuracionReal FROM " . $BaseDatos_systema . ".qryPausasProgramadasDelDia left join dyalogo_telefonia.dy_v_historico_descansos ON IdAgente = agente_id and USUPAU_PausasId_b = tipo_descanso_id WHERE fecha_hora_inicio >= current_date() and USUPAU_Tipo_b = 1 and (HoraInicialProgramada <= date_format(fecha_hora_inicio, '%H:%i:%S') and date_format(fecha_hora_fin , '%H:%i:%S') <= HoraFinalProgramada) and USUARI_ConsInte__PROYEC_b = " . $intIdHuesped_p . " Order by USUARI_Nombre____b DESC LIMIT 0," . $intLimite_p;

            $strSQLReporte_t = "SELECT USUARI_ConsInte__b AS Id, USUARI_Nombre____b AS Nombre, USUARI_Correo___b AS Correo, tipo_descanso_nombre AS Pausa, IF(HoraInicialProgramada > DATE_FORMAT(fecha_hora_inicio, '%H:%i:%S'), TIMEDIFF(HoraInicialProgramada, DATE_FORMAT(fecha_hora_inicio, '%H:%i:%S')), NULL) AS SalioAntesPor, IF(DATE_FORMAT(fecha_hora_fin, '%H:%i:%S') > HoraFinalProgramada, TIMEDIFF(DATE_FORMAT(fecha_hora_fin, '%H:%i:%S'), HoraFinalProgramada), NULL) AS LlegoTardePor, HoraInicialProgramada, DATE_FORMAT(fecha_hora_inicio, '%H:%i:%S') AS HoraInicialReal, HoraFinalProgramada, DATE_FORMAT(fecha_hora_fin, '%H:%i:%S') AS HoraFinalReal, TIMEDIFF(TIMEDIFF(fecha_hora_fin, fecha_hora_inicio), TIMEDIFF(HoraFinalProgramada, HoraInicialProgramada)) AS TiempoDiferencia, TIMEDIFF(HoraFinalProgramada, HoraInicialProgramada) DuracionProgramada, TIMEDIFF(fecha_hora_fin, fecha_hora_inicio) AS DuracionReal FROM {$BaseDatos_systema}.qryPausasProgramadasDelDia LEFT JOIN {$BaseDatos_telefonia}.dy_v_historico_descansos ON IdAgente = agente_id AND USUPAU_PausasId_b = tipo_descanso_id WHERE fecha_hora_inicio >= CURRENT_DATE() AND USUPAU_Tipo_b = 1 AND (HoraInicialProgramada <= DATE_FORMAT(fecha_hora_inicio, '%H:%i:%S') AND DATE_FORMAT(fecha_hora_fin, '%H:%i:%S') <= HoraFinalProgramada) AND USUARI_ConsInte__PROYEC_b = '{$intIdHuesped_p}' AND USUARI_ConsInte__b = '{$id_usuari}' ORDER BY USUARI_Nombre____b DESC LIMIT 0 , {$intLimite_p}";

            $strSQLReporteNoLimit_t = "SELECT USUARI_ConsInte__b AS Id, USUARI_Nombre____b AS Nombre, USUARI_Correo___b AS Correo, tipo_descanso_nombre AS Pausa, IF(HoraInicialProgramada > DATE_FORMAT(fecha_hora_inicio, '%H:%i:%S'), TIMEDIFF(HoraInicialProgramada, DATE_FORMAT(fecha_hora_inicio, '%H:%i:%S')), NULL) AS SalioAntesPor, IF(DATE_FORMAT(fecha_hora_fin, '%H:%i:%S') > HoraFinalProgramada, TIMEDIFF(DATE_FORMAT(fecha_hora_fin, '%H:%i:%S'), HoraFinalProgramada), NULL) AS LlegoTardePor, HoraInicialProgramada, DATE_FORMAT(fecha_hora_inicio, '%H:%i:%S') AS HoraInicialReal, HoraFinalProgramada, DATE_FORMAT(fecha_hora_fin, '%H:%i:%S') AS HoraFinalReal, TIMEDIFF(TIMEDIFF(fecha_hora_fin, fecha_hora_inicio), TIMEDIFF(HoraFinalProgramada, HoraInicialProgramada)) AS TiempoDiferencia, TIMEDIFF(HoraFinalProgramada, HoraInicialProgramada) DuracionProgramada, TIMEDIFF(fecha_hora_fin, fecha_hora_inicio) AS DuracionReal FROM {$BaseDatos_systema}.qryPausasProgramadasDelDia LEFT JOIN {$BaseDatos_telefonia}.dy_v_historico_descansos ON IdAgente = agente_id AND USUPAU_PausasId_b = tipo_descanso_id WHERE fecha_hora_inicio >= CURRENT_DATE() AND USUPAU_Tipo_b = 1 AND (HoraInicialProgramada <= DATE_FORMAT(fecha_hora_inicio, '%H:%i:%S') AND DATE_FORMAT(fecha_hora_fin, '%H:%i:%S') <= HoraFinalProgramada) AND USUARI_ConsInte__PROYEC_b = '{$intIdHuesped_p}' AND USUARI_ConsInte__b = '{$id_usuari}' ORDER BY USUARI_Nombre____b DESC";

            return [$intCantidadFilas_t, $intCantidadPaginas, $strSQLReporte_t, $strSQLReporteNoLimit_t, "PausasConHorarioCumplidas"];

            break;
        case '14':

            // $strSQLCantidadFilas_t = "SELECT COUNT(1) AS cantidad, DuracionMaxima FROM " . $BaseDatos_systema . ".qryPausasProgramadasDelDia left join dyalogo_telefonia.dy_v_historico_descansos ON IdAgente = agente_id and USUPAU_PausasId_b = tipo_descanso_id WHERE fecha_hora_inicio >= current_date() and USUARI_ConsInte__PROYEC_b = " . $intIdHuesped_p . " and USUPAU_Tipo_b = 0 group by USUARI_ConsInte__b having time_to_sec(DuracionMaxima) < sum(time_to_sec(timediff(fecha_hora_fin,fecha_hora_inicio)))Order by USUARI_Nombre____b";

            $strSQLCantidadFilas_t = "SELECT COUNT(1) AS cantidad, DuracionMaxima FROM {$BaseDatos_systema}.qryPausasProgramadasDelDia LEFT JOIN {$BaseDatos_telefonia}.dy_v_historico_descansos ON IdAgente = agente_id AND USUPAU_PausasId_b = tipo_descanso_id WHERE fecha_hora_inicio >= CURRENT_DATE() AND USUARI_ConsInte__PROYEC_b = '{$intIdHuesped_p}' AND USUARI_ConsInte__b = '{$id_usuari}' AND USUPAU_Tipo_b = 0 GROUP BY USUARI_ConsInte__b HAVING TIME_TO_SEC(DuracionMaxima) < SUM(TIME_TO_SEC(TIMEDIFF(fecha_hora_fin, fecha_hora_inicio))) ORDER BY USUARI_Nombre____b";

            $intCantidadFilas_t = mysqli_query($mysqli, $strSQLCantidadFilas_t);
            $result = mysqli_fetch_object($intCantidadFilas_t);
            if (mysqli_num_rows($intCantidadFilas_t) > 0) {
                $intCantidadFilas_t = intval(json_encode($result->cantidad));
            } else {
                $result = new \stdClass;
                $intCantidadFilas_t = json_encode($result->cantidad = 0);
            }

            $intCantidadPaginas = ceil($intCantidadFilas_t / $intLimite_p);

            // $strSQLReporte_t = "select USUARI_ConsInte__b as Id, USUARI_Nombre____b as Nombre, USUARI_Correo___b as Correo, tipo_descanso_nombre as Pausa, timediff(sec_to_time(sum(time_to_sec(timediff(fecha_hora_fin,fecha_hora_inicio)))), DuracionMaxima) as Exceso, DuracionMaxima, sec_to_time(sum(time_to_sec(timediff(fecha_hora_fin,fecha_hora_inicio)))) as DuracionReal, count(USUARI_ConsInte__b) as CantidadReal FROM " . $BaseDatos_systema . ".qryPausasProgramadasDelDia left join {$BaseDatos_telefonia}.dy_v_historico_descansos ON IdAgente = agente_id and USUPAU_PausasId_b = tipo_descanso_id WHERE fecha_hora_inicio >= current_date() and USUARI_ConsInte__PROYEC_b = " . $intIdHuesped_p . " and USUPAU_Tipo_b = 0 group by USUARI_ConsInte__b having time_to_sec(DuracionMaxima) < sum(time_to_sec(timediff(fecha_hora_fin,fecha_hora_inicio)))Order by USUARI_Nombre____b DESC LIMIT 0," . $intLimite_p;

            $strSQLReporte_t = "SELECT USUARI_ConsInte__b AS Id, USUARI_Nombre____b AS Nombre, USUARI_Correo___b AS Correo, tipo_descanso_nombre AS Pausa, TIMEDIFF(SEC_TO_TIME(SUM(TIME_TO_SEC(TIMEDIFF(fecha_hora_fin, fecha_hora_inicio)))), DuracionMaxima) AS Exceso, DuracionMaxima, SEC_TO_TIME(SUM(TIME_TO_SEC(TIMEDIFF(fecha_hora_fin, fecha_hora_inicio)))) AS DuracionReal, COUNT(USUARI_ConsInte__b) AS CantidadReal FROM {$BaseDatos_systema}.qryPausasProgramadasDelDia LEFT JOIN {$BaseDatos_telefonia}.dy_v_historico_descansos ON IdAgente = agente_id AND USUPAU_PausasId_b = tipo_descanso_id WHERE fecha_hora_inicio >= CURRENT_DATE() AND USUARI_ConsInte__PROYEC_b = '{$intIdHuesped_p}' AND USUARI_ConsInte__b = '{$id_usuari}' AND USUPAU_Tipo_b = 0 GROUP BY USUARI_ConsInte__b HAVING TIME_TO_SEC(DuracionMaxima) < SUM(TIME_TO_SEC(TIMEDIFF(fecha_hora_fin, fecha_hora_inicio))) ORDER BY USUARI_Nombre____b DESC LIMIT 0 , {$intLimite_p}";

            $strSQLReporteNoLimit_t = "SELECT USUARI_ConsInte__b AS Id, USUARI_Nombre____b AS Nombre, USUARI_Correo___b AS Correo, tipo_descanso_nombre AS Pausa, TIMEDIFF(SEC_TO_TIME(SUM(TIME_TO_SEC(TIMEDIFF(fecha_hora_fin, fecha_hora_inicio)))), DuracionMaxima) AS Exceso, DuracionMaxima, SEC_TO_TIME(SUM(TIME_TO_SEC(TIMEDIFF(fecha_hora_fin, fecha_hora_inicio)))) AS DuracionReal, COUNT(USUARI_ConsInte__b) AS CantidadReal FROM {$BaseDatos_systema}.qryPausasProgramadasDelDia LEFT JOIN {$BaseDatos_telefonia}.dy_v_historico_descansos ON IdAgente = agente_id AND USUPAU_PausasId_b = tipo_descanso_id WHERE fecha_hora_inicio >= CURRENT_DATE() AND USUARI_ConsInte__PROYEC_b = '{$intIdHuesped_p}' AND USUARI_ConsInte__b = '{$id_usuari}' AND USUPAU_Tipo_b = 0 GROUP BY USUARI_ConsInte__b HAVING TIME_TO_SEC(DuracionMaxima) < SUM(TIME_TO_SEC(TIMEDIFF(fecha_hora_fin, fecha_hora_inicio))) ORDER BY USUARI_Nombre____b DESC";

            return [$intCantidadFilas_t, $intCantidadPaginas, $strSQLReporte_t, $strSQLReporteNoLimit_t, "PausasSinHorarioMuyLargas"];

            break;
        case '15':

            // $strSQLCantidadFilas_t = "SELECT COUNT(1) AS cantidad, CantidadMaxima FROM " . $BaseDatos_systema . ".qryPausasProgramadasDelDia left join dyalogo_telefonia.dy_v_historico_descansos ON IdAgente = agente_id and USUPAU_PausasId_b = tipo_descanso_id WHERE fecha_hora_inicio >= current_date() and USUPAU_Tipo_b = 0 and USUARI_ConsInte__PROYEC_b = " . $intIdHuesped_p . " group by USUARI_ConsInte__b having CantidadMaxima < count(USUARI_ConsInte__b) Order by USUARI_Nombre____b";

            $strSQLCantidadFilas_t = "SELECT COUNT(1) AS cantidad, CantidadMaxima FROM {$BaseDatos_systema}.qryPausasProgramadasDelDia LEFT JOIN {$BaseDatos_telefonia}.dy_v_historico_descansos ON IdAgente = agente_id AND USUPAU_PausasId_b = tipo_descanso_id WHERE fecha_hora_inicio >= CURRENT_DATE() AND USUPAU_Tipo_b = 0 AND USUARI_ConsInte__PROYEC_b = '{$intIdHuesped_p}' AND USUARI_ConsInte__b = '{$id_usuari}' GROUP BY USUARI_ConsInte__b HAVING CantidadMaxima < COUNT(USUARI_ConsInte__b) ORDER BY USUARI_Nombre____b";


            $intCantidadFilas_t = mysqli_query($mysqli, $strSQLCantidadFilas_t);
            $result = mysqli_fetch_object($intCantidadFilas_t);
            if (mysqli_num_rows($intCantidadFilas_t) > 0) {
                $intCantidadFilas_t = intval(json_encode($result->cantidad));
            } else {
                $result = new \stdClass;
                $intCantidadFilas_t = json_encode($result->cantidad = 0);
            }

            $intCantidadPaginas = ceil($intCantidadFilas_t / $intLimite_p);

            // $strSQLReporte_t = "select USUARI_ConsInte__b as Id, USUARI_Nombre____b as Nombre, USUARI_Correo___b as Correo, tipo_descanso_nombre as Pausa, count(USUARI_ConsInte__b) - CantidadMaxima as VecesDeMas, count(USUARI_ConsInte__b) as CantidadReal, CantidadMaxima, sec_to_time(sum(time_to_sec(timediff(fecha_hora_fin,fecha_hora_inicio)))) as DuracionReal, DuracionMaxima FROM " . $BaseDatos_systema . ".qryPausasProgramadasDelDia left join {$BaseDatos_telefonia}.dy_v_historico_descansos ON IdAgente = agente_id and USUPAU_PausasId_b = tipo_descanso_id WHERE fecha_hora_inicio >= current_date() and USUPAU_Tipo_b = 0 and USUARI_ConsInte__PROYEC_b = " . $intIdHuesped_p . " group by USUARI_ConsInte__b having CantidadMaxima < count(USUARI_ConsInte__b) Order by USUARI_Nombre____b DESC LIMIT 0," . $intLimite_p;


            $strSQLReporte_t = "SELECT USUARI_ConsInte__b AS Id, USUARI_Nombre____b AS Nombre, USUARI_Correo___b AS Correo, tipo_descanso_nombre AS Pausa, COUNT(USUARI_ConsInte__b) - CantidadMaxima AS VecesDeMas, COUNT(USUARI_ConsInte__b) AS CantidadReal, CantidadMaxima, SEC_TO_TIME(SUM(TIME_TO_SEC(TIMEDIFF(fecha_hora_fin, fecha_hora_inicio)))) AS DuracionReal, DuracionMaxima FROM {$BaseDatos_systema}.qryPausasProgramadasDelDia LEFT JOIN {$BaseDatos_telefonia}.dy_v_historico_descansos ON IdAgente = agente_id AND USUPAU_PausasId_b = tipo_descanso_id WHERE fecha_hora_inicio >= CURRENT_DATE() AND USUPAU_Tipo_b = 0 AND USUARI_ConsInte__PROYEC_b = '{$intIdHuesped_p}' AND USUARI_ConsInte__b = '{$id_usuari}' GROUP BY USUARI_ConsInte__b HAVING CantidadMaxima < COUNT(USUARI_ConsInte__b) ORDER BY USUARI_Nombre____b DESC LIMIT 0 , {$intLimite_p}";

            $strSQLReporteNoLimit_t = "SELECT USUARI_ConsInte__b AS Id, USUARI_Nombre____b AS Nombre, USUARI_Correo___b AS Correo, tipo_descanso_nombre AS Pausa, COUNT(USUARI_ConsInte__b) - CantidadMaxima AS VecesDeMas, COUNT(USUARI_ConsInte__b) AS CantidadReal, CantidadMaxima, SEC_TO_TIME(SUM(TIME_TO_SEC(TIMEDIFF(fecha_hora_fin, fecha_hora_inicio)))) AS DuracionReal, DuracionMaxima FROM {$BaseDatos_systema}.qryPausasProgramadasDelDia LEFT JOIN {$BaseDatos_telefonia}.dy_v_historico_descansos ON IdAgente = agente_id AND USUPAU_PausasId_b = tipo_descanso_id WHERE fecha_hora_inicio >= CURRENT_DATE() AND USUPAU_Tipo_b = 0 AND USUARI_ConsInte__PROYEC_b = '{$intIdHuesped_p}' AND USUARI_ConsInte__b = '{$id_usuari}' GROUP BY USUARI_ConsInte__b HAVING CantidadMaxima < COUNT(USUARI_ConsInte__b) ORDER BY USUARI_Nombre____b DESC";

            return [$intCantidadFilas_t, $intCantidadPaginas, $strSQLReporte_t, $strSQLReporteNoLimit_t, "PausasSinHorarioMuchasVeces"];

            break;
        case '16':

            // $strSQLCantidadFilas_t = "SELECT  COUNT(1) AS cantidad, DuracionMaxima, CantidadMaxima FROM " . $BaseDatos_systema . ".qryPausasProgramadasDelDia left join dyalogo_telefonia.dy_v_historico_descansos ON IdAgente = agente_id and USUPAU_PausasId_b = tipo_descanso_id WHERE fecha_hora_inicio >= current_date() and USUARI_ConsInte__PROYEC_b = " . $intIdHuesped_p . " and USUPAU_Tipo_b = 0 group by USUARI_ConsInte__b having time_to_sec(DuracionMaxima) >= sum(time_to_sec(timediff(fecha_hora_fin,fecha_hora_inicio))) and CantidadMaxima >= count(USUARI_ConsInte__b) Order by USUARI_Nombre____b";


            $strSQLCantidadFilas_t = "SELECT COUNT(1) AS cantidad, DuracionMaxima, CantidadMaxima FROM {$BaseDatos_systema}.qryPausasProgramadasDelDia LEFT JOIN {$BaseDatos_telefonia}.dy_v_historico_descansos ON IdAgente = agente_id AND USUPAU_PausasId_b = tipo_descanso_id WHERE fecha_hora_inicio >= CURRENT_DATE() AND USUARI_ConsInte__PROYEC_b = '{$intIdHuesped_p}' AND USUARI_ConsInte__b = '{$id_usuari}' AND USUPAU_Tipo_b = 0 GROUP BY USUARI_ConsInte__b HAVING TIME_TO_SEC(DuracionMaxima) >= SUM(TIME_TO_SEC(TIMEDIFF(fecha_hora_fin, fecha_hora_inicio))) AND CantidadMaxima >= COUNT(USUARI_ConsInte__b) ORDER BY USUARI_Nombre____b";

            $intCantidadFilas_t = mysqli_query($mysqli, $strSQLCantidadFilas_t);
            $result = mysqli_fetch_object($intCantidadFilas_t);
            if (mysqli_num_rows($intCantidadFilas_t) > 0) {
                $intCantidadFilas_t = intval(json_encode($result->cantidad));
            } else {
                $result = new \stdClass;
                $intCantidadFilas_t = json_encode($result->cantidad = 0);
            }

            $intCantidadPaginas = ceil($intCantidadFilas_t / $intLimite_p);

            // $strSQLReporte_t = "select USUARI_ConsInte__b as Id, USUARI_Nombre____b as Nombre, USUARI_Correo___b as Correo, tipo_descanso_nombre as Pausa, sec_to_time(sum(time_to_sec(timediff(fecha_hora_fin,fecha_hora_inicio)))) as DuracionReal, DuracionMaxima, count(USUARI_ConsInte__b) as CantidadReal, CantidadMaxima FROM " . $BaseDatos_systema . ".qryPausasProgramadasDelDia left join {$BaseDatos_telefonia}.dy_v_historico_descansos ON IdAgente = agente_id and USUPAU_PausasId_b = tipo_descanso_id WHERE fecha_hora_inicio >= current_date() and USUARI_ConsInte__PROYEC_b = " . $intIdHuesped_p . " and USUPAU_Tipo_b = 0 group by USUARI_ConsInte__b having time_to_sec(DuracionMaxima) >= sum(time_to_sec(timediff(fecha_hora_fin,fecha_hora_inicio))) and CantidadMaxima >= count(USUARI_ConsInte__b) Order by USUARI_Nombre____b DESC LIMIT 0," . $intLimite_p;

            $strSQLReporte_t =  "SELECT USUARI_ConsInte__b AS Id, USUARI_Nombre____b AS Nombre, USUARI_Correo___b AS Correo, tipo_descanso_nombre AS Pausa, SEC_TO_TIME(SUM(TIME_TO_SEC(TIMEDIFF(fecha_hora_fin, fecha_hora_inicio)))) AS DuracionReal, DuracionMaxima, COUNT(USUARI_ConsInte__b) AS CantidadReal, CantidadMaxima FROM {$BaseDatos_systema}.qryPausasProgramadasDelDia LEFT JOIN {$BaseDatos_telefonia}.dy_v_historico_descansos ON IdAgente = agente_id AND USUPAU_PausasId_b = tipo_descanso_id WHERE fecha_hora_inicio >= CURRENT_DATE() AND USUARI_ConsInte__PROYEC_b = '{$intIdHuesped_p}' and USUARI_ConsInte__b = '{$id_usuari}' AND USUPAU_Tipo_b = 0 GROUP BY USUARI_ConsInte__b HAVING TIME_TO_SEC(DuracionMaxima) >= SUM(TIME_TO_SEC(TIMEDIFF(fecha_hora_fin, fecha_hora_inicio))) AND CantidadMaxima >= COUNT(USUARI_ConsInte__b) ORDER BY USUARI_Nombre____b DESC LIMIT 0 , {$intLimite_p}";

            $strSQLReporteNoLimit_t = "SELECT USUARI_ConsInte__b AS Id, USUARI_Nombre____b AS Nombre, USUARI_Correo___b AS Correo, tipo_descanso_nombre AS Pausa, SEC_TO_TIME(SUM(TIME_TO_SEC(TIMEDIFF(fecha_hora_fin, fecha_hora_inicio)))) AS DuracionReal, DuracionMaxima, COUNT(USUARI_ConsInte__b) AS CantidadReal, CantidadMaxima FROM {$BaseDatos_systema}.qryPausasProgramadasDelDia LEFT JOIN {$BaseDatos_telefonia}.dy_v_historico_descansos ON IdAgente = agente_id AND USUPAU_PausasId_b = tipo_descanso_id WHERE fecha_hora_inicio >= CURRENT_DATE() AND USUARI_ConsInte__PROYEC_b = '{$intIdHuesped_p}' and USUARI_ConsInte__b = '{$id_usuari}' AND USUPAU_Tipo_b = 0 GROUP BY USUARI_ConsInte__b HAVING TIME_TO_SEC(DuracionMaxima) >= SUM(TIME_TO_SEC(TIMEDIFF(fecha_hora_fin, fecha_hora_inicio))) AND CantidadMaxima >= COUNT(USUARI_ConsInte__b) ORDER BY USUARI_Nombre____b DESC";

            return [$intCantidadFilas_t, $intCantidadPaginas, $strSQLReporte_t, $strSQLReporteNoLimit_t, "PausasSinHorarioOK"];

            break;
        case '17':
            // $strSQLCantidadFilas_t = "SELECT COUNT(1) AS cantidad FROM " . $BaseDatos_systema . ".qryUSUARI_usuarios_agentes WHERE USUARI_ConsInte__PROYEC_b = " . $intIdHuesped_p . " and HoraInicialDefinida IS NULL Order By USUARI_Nombre____b";

            // $strSQLCantidadFilas_t = "SELECT COUNT(1) AS cantidad FROM {$BaseDatos_systema}.qryUSUARI_usuarios_agentes WHERE USUARI_ConsInte__PROYEC_b = '{$intIdHuesped_p}' AND HoraInicialDefinida IS NULL ORDER BY USUARI_Nombre____b";

            $strSQLCantidadFilas_t = "SELECT COUNT(1) AS cantidad FROM {$BaseDatos_systema}.qryUSUARI_usuarios_agentes WHERE USUARI_ConsInte__PROYEC_b = '{$intIdHuesped_p}' AND USUARI_ConsInte__b = '{$id_usuari}' AND HoraInicialDefinida IS NULL ORDER BY USUARI_Nombre____b";

            $intCantidadFilas_t = $mysqli->query($strSQLCantidadFilas_t)->fetch_object()->cantidad;

            $intCantidadPaginas = ceil($intCantidadFilas_t / $intLimite_p);

            // $strSQLReporte_t = "select USUARI_ConsInte__b as Id, USUARI_Nombre____b as Nombre, USUARI_Correo___b as Correo FROM " . $BaseDatos_systema . ".qryUSUARI_usuarios_agentes WHERE USUARI_ConsInte__PROYEC_b = " . $intIdHuesped_p . " and HoraInicialDefinida IS NULL Order By USUARI_Nombre____b DESC LIMIT 0," . $intLimite_p;

            $strSQLReporte_t = "SELECT USUARI_ConsInte__b AS Id, USUARI_Nombre____b AS Nombre, USUARI_Correo___b AS Correo FROM {$BaseDatos_systema}.qryUSUARI_usuarios_agentes WHERE USUARI_ConsInte__PROYEC_b = '{$intIdHuesped_p}' AND USUARI_ConsInte__b = '{$id_usuari}' AND HoraInicialDefinida IS NULL ORDER BY USUARI_Nombre____b DESC LIMIT 0 , {$intLimite_p}";

            $strSQLReporteNoLimit_t = "SELECT USUARI_ConsInte__b AS Id, USUARI_Nombre____b AS Nombre, USUARI_Correo___b AS Correo FROM {$BaseDatos_systema}.qryUSUARI_usuarios_agentes WHERE USUARI_ConsInte__PROYEC_b = '{$intIdHuesped_p}' AND USUARI_ConsInte__b = '{$id_usuari}' AND HoraInicialDefinida IS NULL ORDER BY USUARI_Nombre____b DESC";

            return [$intCantidadFilas_t, $intCantidadPaginas, $strSQLReporte_t, $strSQLReporteNoLimit_t, "AGENTES SIN MALLA DEFINIDA"];

            break;
        default:
            # code...
            break;
    }

    // return $arrData;
}



/**
 *JDBD - En esta funcion armamos las condiciones de los filtros que recibimos.
 *@param .....
 *@return string. 
 */

function armarCondicion($strCampo_p,$strOperador_p,$strValor_p,$strTipo_p,$strCondicion_p, $strCierre_p){

    $strCampo_p = str_replace("\\", "", $strCampo_p);


    switch ($strTipo_p) {
        case '5':

            return $strCondicion_p." DATE(".$strCampo_p.") ".$strOperador_p." '".$strValor_p."' ".$strCierre_p." ";

            break;

        case '14':

            return $strCondicion_p." TIME(".$strCampo_p.") ".$strOperador_p." TIME('".$strValor_p."') ".$strCierre_p." ";

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

                case 'IN':
                    $strOperadorFinal_t  = "IN ";
                    $strValorFinal_t = "";
                    $strSeparador_t = " ";
                    foreach ($strValor_p as $key => $value) {
                        $strValorFinal_t .= "{$strSeparador_t}' {$value} '";
                        $strSeparador_t = ",";
                    }
                    $strValorFinal_t = "(".$strValorFinal_t.")";
                    break;


                default:

                    $strValorFinal_t = "'".$strValor_p."'";
                    $strOperadorFinal_t = $strOperador_p;

                    // Valido si el valor que viene es la palabra null y dependiendo de eso se hace el cambio de operador para buscar valores nulos
                    if($strValor_p == "NULL"){
                        if($strOperador_p == "="){
                            $strOperadorFinal_t = "IS";
                        }
                        if($strOperador_p == "!="){
                            $strOperadorFinal_t = "IS NOT";
                        }

                        $strValorFinal_t = $strValor_p;
                    }

                    break;            

                }

            return $strCondicion_p." ".$strCampo_p." ".$strOperadorFinal_t." ".$strValorFinal_t." ".$strCierre_p." ";

            break;
    }

}


/**
 *BGCR - Esta funcion retorna el alias de un campo en especifico sobre la vista
 *@param view = nombre de la visa, campo = id del campo
 *@return string. = nombre de alias
 */


function getNameColumnView(string $view,string $campo, int $intTipoCampo = null ):string
{
    global $mysqli;
    global $BaseDatos;
    $sql=$mysqli->query("SHOW CREATE VIEW {$BaseDatos}.{$view}");

    $exp='';
    if($sql){
        $sql=$sql->fetch_object();
        $var="Create View";

        if($intTipoCampo != 11){

            $exp=explode($campo,$sql->$var)[1];
            $exp=explode(" AS ",$exp)[1];
            $exp=explode(",",$exp)[0];
            $exp=explode("`",$exp)[1];
            $exp=str_replace("`","",$exp);

        }else{

            //Se obtiene el alias del join correspondiente a las lista auxiliar
            $alias=explode($campo,$sql->$var)[1];
            $alias=explode("`",$alias)[2];

            // Se obtiene el alias de la columna correspondiente
            $exp=explode($alias,$sql->$var)[1];
            $exp=explode(' AS ',$exp)[1];
            $exp=explode('`',$exp)[1];



            // Se obtiene el verdadero alias de la columna

            $idCampo = explode("_C",$campo)[1];
            $sqlAliasCampo =$mysqli->query("select PREGUN_Texto_____b as nombre from DYALOGOCRM_SISTEMA.PREGUN WHERE PREGUN_ConsInte__b = {$idCampo}");
            
            if($sqlAliasCampo){
                $sqlAliasCampo=$sqlAliasCampo->fetch_object();

                $strAlis = " AS '".$sqlAliasCampo->nombre."' ";
                
                $exp = $exp.$strAlis;

            }

        }
    }
    return $exp;
}


/**
 *BGCR - Esta funcion retorna el alias de un campo en especifico sobre la vista
 *@param view = nombre de la visa, campo = id del campo
 *@return string. = nombre de alias
 */


function getOptionName(string $idOption, string $intIdTipo_p = null):string
{
    global $mysqli;
    global $BaseDatos_systema;
    global $BaseDatos_telefonia;
    

    switch ($intIdTipo_p) {
        case 'estrat_paso':
            $sqlStrOption = "SELECT ESTPAS_Comentari_b AS nombre FROM  {$BaseDatos_systema}.ESTPAS WHERE ESTPAS_ConsInte__b = {$idOption} LIMIT 1;";
            break;
        
        case 'monoef':
            $sqlStrOption = "SELECT LISOPC_Nombre____b AS nombre FROM {$BaseDatos_systema}.LISOPC WHERE LISOPC_Clasifica_b = {$idOption} LIMIT 1;";
            break;

        case 'usu':

            if($idOption == ""){
                return "NULL";
            }

            $sqlStrOption = "SELECT USUARI_Nombre____b AS nombre FROM {$BaseDatos_systema}.USUARI WHERE USUARI_ConsInte__b = {$idOption} LIMIT 1;";
            break;

        case 'usu_tel':
            $sqlStrOption = "SELECT nombre FROM {$BaseDatos_telefonia}.dy_agentes WHERE id = {$idOption} LIMIT 1";
            break;

        case 'estado':
            $sqlStrOption = "SELECT LISOPC_Nombre____b AS nombre FROM {$BaseDatos_systema}.LISOPC WHERE LISOPC_ConsInte__b = {$idOption} LIMIT 1;";
            break;

        case 'clasi':
            switch ($idOption) {
                case '1':
                    return 'Devoluciones';
                    break;
                
                case '2':
                    return 'No contactable';
                    break;

                case '3':
                    return 'Sin gestion';
                    break;
                
                case '4':
                    return 'No contactado';
                    break;

                case '5':
                    return 'Contactado';
                    break;
                
                case '6':
                    return 'No efectivo';
                    break;
                    
                case '7':
                    return 'Efectivo';
                    break;
                
            }
            break;
    
        default:
            $sqlStrOption = "SELECT LISOPC_Nombre____b AS nombre FROM {$BaseDatos_systema}.LISOPC WHERE LISOPC_ConsInte__b = {$idOption} LIMIT 1";
            break;
    }

    $sql=$mysqli->query($sqlStrOption);

    if($sql){
        $option=$sql->fetch_object()->nombre;
        if($option == null){
            $option = "";
        }

    }
    return $option;
}


/**
 *BGCR - Esta funcion ayuda a validar si existe un columna en cierta tabla
 *@param view = nombre de la visa, strColumn_p = nombre de la columna
 *@return boolean
 */


function validColumnExist($strColumn_p, $strView_p){

    global $mysqli;
    global $BaseDatos;

    $sql=$mysqli->query("SHOW COLUMNS FROM {$BaseDatos}.{$strView_p} LIKE '{$strColumn_p}' ");

    if($sql->num_rows > 0){
        return true;
    }else{
        return false;
    }

}

function arrayTitle()  {
    return array(
        "Fecha" => "Fecha en la que se esta calculando el registro",
        "TSF_Tiempo" => "El tiempo en el que una comunicación se considera dentro del rango valido",
        "TSF_porcentaje" => "El porcentaje objetivo del nivel de servicio.",
        "Ofrecidas" => "Es la cantidad de comunicaciones que ingresaron a la cola de espera.",
        "Contestadas" => "Cantidad de comunicaciones contestadas sin importar el tiempo de espera.",
        "Cont_antes_tsf" => "Cantidad de comunicaciones contestadas antes del TSF_Tiempo (<=)",
        "Cont_despues_tsf" => "Comunicaciones contestadas despues del TSF_Tiempo (>)",
        "Aban_despues_tsf" => "Cantidad de comunicaciones abandonadas antes del tiempo configurado en la campaña (<=)",
        "Cont_porcentaje" => "Comunicaciones contestadas multiplicadas por 100 y el resultado se divide entre la cantidad de Ofrecidos",
        "TSF" => "Porcentaje de cumplimiento del nivel de servicio total (contestadas ante del tiempo / Ofrecidos)",
        "TSF_Cont_antes_tsf" => "Porcentaje de cumplimiento del nivel de servicio parcial (contestadas antes del tiempo / Contestadas)",
        "TSF_Cont_despues_tsf" => "Porcentaje de cumplimiento del nivel de servicio parcial (contestadas despues del tiempo / Contestadas)",
        "ASA" => "Tiempo de espera promedio",
        "ASAMin" => "Tiempo de espera minimo",
        "ASAMax" => "Tiempo de espera maximo",
        "TSA" => "Tiempo de espera total",
        "AHT" => "Tiempo promedio de la duracion de la conversacion",
        "THT" => "Tiempo total de la duracion de la conversacion",
        "Aban" => "Cantidad de comunicaciones abandonadas",
        "Aban_antes_tsf" => "Cantidad de comunicaciones abandonadas antes del tiempo configurado.",
        "Aban_porcentaje" => "Porcentaje de llamadas abandondas (total de abandonadas / cantidad de recibidos) * 100",
        "Aban_umbral_tsf" => "((abandonadas despues segundos tsf/(contestados abandonadas despues segundos tsf))*100)",
        "Aban_espera" => "Espera promedio abandono",
        "Aban_espera_total" => "Espera total abandono",
        "Aban_espera_min" => "Espera minima abandono",
        "Aban_espera_max" => "Espera maxima abandono.",
    );
}




