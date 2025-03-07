<?php
session_start();
ini_set('display_errors', 'On');
ini_set('display_errors', 1);
require "../../../carga/lib_excel/Classes/PHPExcel/Reader/Excel2007.php";
include(__DIR__ . "../../../../pages/conexion.php");
include(__DIR__ . "../../../../global/WSCoreClient.php");
include(__DIR__ . "../../../../generador/generar_tablas_bd.php");
include(__DIR__ . "/../reporteador.php");
require_once('../../../../helpers/parameters.php');
require_once(__DIR__ . "../../../../generador/busqueda_manual.php");
require_once(__DIR__ . "../../../../generador/busqueda_ani.php");
require_once(__DIR__ . "../../../../generador/busqueda_dato_adicional.php");
require_once(__DIR__ . "../../../../global/GenerarGuion.php");



/** BGCR
 * Esta funcion genera el guion para las gestiones del marcador (Ahorita no tiene ningun campo en espeacil)
 * @return int Id del guion
 * @param int $pasoId = id del paso del marcador
 * @param string $nombrePaso = nombre que se le asigno al marcador
 */

function generarTablaGestionesMarcador($pasoId, $nombrePaso){

    global $mysqli;
    global $BaseDatos_systema;


    $estpasNombre = (isset($nombrePaso)) ? $nombrePaso : null;


    // Me toca obtener el huesped
    $sqlHuesped = "SELECT ESTRAT_ConsInte__b AS id, ESTRAT_ConsInte__PROYEC_b AS huespedId from {$BaseDatos_systema}.ESTRAT
        INNER JOIN {$BaseDatos_systema}.ESTPAS ON ESTPAS_ConsInte__ESTRAT_b = ESTRAT_ConsInte__b
    WHERE ESTPAS_ConsInte__b = {$pasoId}";

    $resHuesped = $mysqli->query($sqlHuesped);
    if($resHuesped && $resHuesped->num_rows > 0){
        $hu = $resHuesped->fetch_object();
        $huespedId = $hu->huespedId;
    }else{
        $huespedId = null;
    }

    $guion = new GenerarGuion;

    // Debemos de crear el formulario completamente nuevo

    $respBd = $guion->crearBd('Gestiones Marcador Robotico ' . $estpasNombre, 'Creado desde el marcador Robotico ' . $estpasNombre, 1, 'Marcador Robotico', $huespedId);
    
    // Si falla la creacion de la bd detenemos el proceso
    if($respBd['estado'] === false){
        return;
    }

    $guionGestionId = $respBd['idBd'];

    // Creamos la seccion por defecto
    $arrSecciones = [
        ['nombre' => 'Principal Marcador', 'tipo' => 1]
    ];
    $respSeccion = $guion->crearSeccion($respBd['idBd'], $arrSecciones);

    // Si falla la creacion de la seccion por defecto detenemos el proceso
    if(!isset($respSeccion['exito']['Principal Marcador'])){
        return;
    }

    // Ya teniendo la bd y la seccion creamos un campo que almacenara el campo
    $campos = $guion->crearPregun($respBd['idBd'], [
        ['nombre' => 'Dato principal', 'seccion' => $respSeccion['exito']['Principal Marcador'], 'tipo' => 1],
    ]);

    // Actualizamos los campos principales y secundarios del formulario
    $guion->acutualizarCampoPrincipalSecundario($respBd['idBd'], $campos['exito'][0], $campos['exito'][0]);


    $guion->generarTabla($guionGestionId, 1);

    // Retornamos el id del formulario que se creo
    return $respBd['idBd'];
    
}



    function verificarReporteExistente($strReporte_p,$intPeriodicidad_p,$intIdHuesped_p,$intIdEstrat_p){

        global $mysqli;
        global $BaseDatos_general;

        if ($strReporte_p == "NORMAL") {

            $strSQLReporte_t = "SELECT COUNT(1) AS cantidad FROM ".$BaseDatos_general.".reportes_automatizados WHERE id_huesped = ".$intIdHuesped_p." AND id_estrategia = ".$intIdEstrat_p." AND tipo_periodicidad = ".$intPeriodicidad_p;
            
        }elseif($strReporte_p == "ADHERENCIA"){

            $strSQLReporte_t = "SELECT COUNT(1) AS cantidad FROM ".$BaseDatos_general.".reportes_automatizados WHERE id_huesped = ".$intIdHuesped_p." AND id_estrategia = ".$intIdEstrat_p." AND tipo_periodicidad = ".$intPeriodicidad_p." AND asunto LIKE '%_ADHERENCIA'";

        }

        $booExiste_t = false;

        if ($resSQLReporte_t = $mysqli->query($strSQLReporte_t)) {

            $objSQLReporte_t = $resSQLReporte_t->fetch_object();

            if ($objSQLReporte_t->cantidad > 0) {

                $booExiste_t = true;

            }

        }

        return $booExiste_t;

    }
                        
function guardar_auditoria($accion, $superAccion) {
    
    global $mysqli;
    global $BaseDatos_systema;

    $str_Lsql = "INSERT INTO " . $BaseDatos_systema . ".AUACAD (AUACAD_Fecha_____b , AUACAD_Hora______b, AUACAD_Ejecutor__b, AUACAD_TipoAcci__b , AUACAD_SubTipAcc_b, AUACAD_Accion____b , AUACAD_Huesped___b ) VALUES ('" . date('Y-m-d H:s:i') . "', '" . date('Y-m-d H:s:i') . "', " . $_SESSION['IDENTIFICACION'] . ", 'G10', '" . $accion . "', '" .$mysqli->real_escape_string($superAccion). "', " . $_SESSION['HUESPED'] . " );";
    $mysqli->query($str_Lsql);
}

function invocarCrm_CrearScripts($idCampan) {
    global $mysqli;
    global $BaseDatos_systema;

    $ch = curl_init($urlCrearScripts . 'crm_php/generarBusqueda.php');
    //especificamos el POST (tambien podemos hacer peticiones enviando datos por GET
    curl_setopt($ch, CURLOPT_POST, 1);

    //le decimos qué paramáetros enviamos (pares nombre/valor, también acepta un array)
    curl_setopt($ch, CURLOPT_POSTFIELDS, "generar=" . $idCampan);

    //le decimos que queremos recoger una respuesta (si no esperas respuesta, ponlo a false)
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

    $respuesta = curl_exec($ch);
    $error = curl_error($ch);
    curl_close($ch);
    echo " Respuesta => " . $respuesta;
    echo " Error => " . $error;
}

function getTextoPregun($id) {
    
    if (!is_numeric($id)) {

        if (strpos($id, "_ConsInte__b")) {

            return "ID";

        }else if (strpos($id, "_FechaInsercion")) {
            
            return "FECHA INSERCION";

        }

    }else{

        global $mysqli;
        global $BaseDatos_systema;
        $Lsql = "SELECT PREGUN_Texto_____b FROM " . $BaseDatos_systema . ".PREGUN WHERE PREGUN_ConsInte__b = " . $id;
        $res = $mysqli->query($Lsql);
        $datos = $res->fetch_array();
        return $datos['PREGUN_Texto_____b'];

        
    }

}

if(isset($_POST['opcion']) && $_POST['opcion'] == "borrarLogCargue"){
     $Lsql = "DELETE FROM ".$BaseDatos_systema.".LOG_CARGUE WHERE LOG_CARGUE_Token_b =".$_SESSION['TOKEN'];
     $res = $mysqli->query($Lsql);
}



if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
    //Funciones de la carga maestro y eso
    //Datos del formulario
    if (isset($_POST['CallDatos'])) {

        $str_Lsql = 'SELECT G10_ConsInte__b, G10_C71 as principal ,G10_C70,G10_C71,G10_C72,G10_C73,G10_C74,G10_C75,G10_C76,G10_C77,G10_C105,G10_C106,G10_C107,G10_C78,G10_C79,G10_C80,G10_C81,G10_C82,G10_C83,G10_C84,G10_C85,G10_C90,G10_C91,G10_C92,G10_C93,G10_C94,G10_C95,G10_C98,G10_C99,G10_C100,G10_C101,G10_C102,G10_C103,G10_C104,G10_C108,G10_C109,G10_C110,G10_C111,G10_C112,G10_C113,G10_C114,G10_C115,G10_C116,G10_C117,G10_C118,G10_C119,G10_C120,G10_C121,G10_C122,G10_C123,G10_C124,G10_C125,G10_C126,G10_C127,G10_C128,G10_C129,G10_C130,G10_C131, ESTPAS_ConsInte__b,G10_C331, G10_C332,G10_C333,G10_C334 FROM ' . $BaseDatos_systema . '.G10 LEFT JOIN ' . $BaseDatos_systema . '.ESTPAS ON ESTPAS_ConsInte__CAMPAN_b = G10_ConsInte__b WHERE G10_ConsInte__b =' . $_POST['id'];
        $result = $mysqli->query($str_Lsql);
        $datos = array();
        $i = 0;

        while ($key = $result->fetch_object()) {

            $datos[$i]['G10_C70'] = $key->G10_C70;

            $datos[$i]['G10_C71'] = $key->G10_C71;

            $datos[$i]['G10_C72'] = $key->G10_C72;

            $datos[$i]['G10_C73'] = $key->G10_C73;

            $datos[$i]['G10_C74'] = $key->G10_C74;

            $datos[$i]['G10_C75'] = $key->G10_C75;

            $datos[$i]['G10_C76'] = $key->G10_C76;

            $datos[$i]['G10_C77'] = $key->G10_C77;

            $datos[$i]['G10_C105'] = $key->G10_C105;

            $datos[$i]['G10_C106'] = $key->G10_C106;

            $datos[$i]['G10_C107'] = $key->G10_C107;

            $datos[$i]['G10_C78'] = $key->G10_C78;

            $datos[$i]['G10_C79'] = $key->G10_C79;

            $datos[$i]['G10_C80'] = $key->G10_C80;

            $datos[$i]['G10_C81'] = $key->G10_C81;

            $datos[$i]['G10_C82'] = $key->G10_C82;

            $datos[$i]['G10_C83'] = $key->G10_C83;

            $datos[$i]['G10_C84'] = $key->G10_C84;

            $datos[$i]['G10_C85'] = $key->G10_C85;

            $datos[$i]['G10_C90'] = $key->G10_C90;

            $datos[$i]['G10_C91'] = $key->G10_C91;

            $datos[$i]['G10_C92'] = $key->G10_C92;

            $datos[$i]['G10_C93'] = $key->G10_C93;

            $datos[$i]['G10_C94'] = $key->G10_C94;

            $datos[$i]['G10_C95'] = $key->G10_C95;

            $datos[$i]['G10_C98'] = $key->G10_C98;

            $datos[$i]['G10_C99'] = $key->G10_C99;

            $datos[$i]['G10_C100'] = $key->G10_C100;

            $datos[$i]['G10_C101'] = $key->G10_C101;

            $datos[$i]['G10_C102'] = $key->G10_C102;

            $datos[$i]['G10_C103'] = $key->G10_C103;

            $datos[$i]['G10_C104'] = $key->G10_C104;

            $datos[$i]['G10_C108'] = $key->G10_C108;

            $datos[$i]['G10_C333'] = $key->G10_C333;
            $datos[$i]['G10_C334'] = $key->G10_C334;

            if (!is_null($key->G10_C109)) {
                $datos[$i]['G10_C109'] = explode(' ', $key->G10_C109)[1];
            }

            if (!is_null($key->G10_C110)) {
                $datos[$i]['G10_C110'] = explode(' ', $key->G10_C110)[1];
            }

            $datos[$i]['G10_C111'] = $key->G10_C111;

            if (!is_null($key->G10_C112)) {
                $datos[$i]['G10_C112'] = explode(' ', $key->G10_C112)[1];
            }

            if (!is_null($key->G10_C113)) {
                $datos[$i]['G10_C113'] = explode(' ', $key->G10_C113)[1];
            }

            $datos[$i]['G10_C114'] = $key->G10_C114;

            if (!is_null($key->G10_C115)) {
                $datos[$i]['G10_C115'] = explode(' ', $key->G10_C115)[1];
            }

            if (!is_null($key->G10_C116)) {
                $datos[$i]['G10_C116'] = explode(' ', $key->G10_C116)[1];
            }

            $datos[$i]['G10_C117'] = $key->G10_C117;

            if (!is_null($key->G10_C118)) {
                $datos[$i]['G10_C118'] = explode(' ', $key->G10_C118)[1];
            }

            $datos[$i]['G10_C119'] = $key->G10_C119;

            if (!is_null($key->G10_C119)) {
                $datos[$i]['G10_C119'] = explode(' ', $key->G10_C119)[1];
            }

            $datos[$i]['G10_C120'] = $key->G10_C120;

            if (!is_null($key->G10_C121)) {
                $datos[$i]['G10_C121'] = explode(' ', $key->G10_C121)[1];
            }

            if (!is_null($key->G10_C122)) {
                $datos[$i]['G10_C122'] = explode(' ', $key->G10_C122)[1];
            }

            $datos[$i]['G10_C123'] = $key->G10_C123;

            if (!is_null($key->G10_C124)) {
                $datos[$i]['G10_C124'] = explode(' ', $key->G10_C124)[1];
            }

            if (!is_null($key->G10_C125)) {
                $datos[$i]['G10_C125'] = explode(' ', $key->G10_C125)[1];
            }

            $datos[$i]['G10_C126'] = $key->G10_C126;

            if (!is_null($key->G10_C127)) {
                $datos[$i]['G10_C127'] = explode(' ', $key->G10_C127)[1];
            }

            if (!is_null($key->G10_C128)) {
                $datos[$i]['G10_C128'] = explode(' ', $key->G10_C128)[1];
            }

            $datos[$i]['G10_C129'] = $key->G10_C129;

            if (!is_null($key->G10_C130)) {
                $datos[$i]['G10_C130'] = explode(' ', $key->G10_C130)[1];
            }

            if (!is_null($key->G10_C131)) {
                $datos[$i]['G10_C131'] = explode(' ', $key->G10_C131)[1];
            }

            // DLAB 20190715 - Se agrega el campo para colocar los canales del marcador robotico
            $datos[$i]['G10_C331'] = $key->G10_C331;
            
            $datos[$i]['G10_C332'] = $key->G10_C332;

            $datos[$i]['principal'] = $key->principal;

            $datos[$i]['id_estpas'] = $key->ESTPAS_ConsInte__b;

            $datos[$i]['G10_ConsInte__b'] = $key->G10_ConsInte__b;

            $i++;
        }
        echo json_encode($datos);
    }

    //Datos del formulario
    if (isset($_POST['CallDatos_2'])) {

        $str_Lsql = 'SELECT G10_ConsInte__b, G10_C71 as principal ,G10_C70,G10_C71,G10_C72,G10_C73,G10_C74,G10_C75,G10_C76,G10_C77,G10_C105,G10_C106,G10_C107,G10_C78,G10_C79,G10_C80,G10_C81,G10_C82,G10_C83,G10_C84,G10_C85,G10_C90,G10_C91,G10_C92,G10_C93,G10_C94,G10_C95,G10_C98,G10_C99,G10_C100,G10_C101,G10_C102,G10_C103,G10_C104,G10_C108,G10_C109,G10_C110,G10_C111,G10_C112,G10_C113,G10_C114,G10_C115,G10_C116,G10_C117,G10_C118,G10_C119,G10_C120,G10_C121,G10_C122,G10_C123,G10_C124,G10_C125,G10_C126,G10_C127,G10_C128,G10_C129,G10_C130,G10_C131,G10_C331,G10_C332,G10_C326,G10_C333,G10_C334 FROM ' . $BaseDatos_systema . '.G10 JOIN ' . $BaseDatos_systema . '.ESTPAS ON ESTPAS_ConsInte__CAMPAN_b = G10_ConsInte__b WHERE ESTPAS_ConsInte__b =' . $_POST['id'];
        $result = $mysqli->query($str_Lsql);
        $datos = array();
        $i = 0;

        $tiempoInabilitadoBoton = 15;
        $strCampana = "SELECT CAMPAN_TiemMinCol_b AS tiempoInabilitadoBotonColgado FROM {$BaseDatos_systema}.CAMPAN JOIN {$BaseDatos_systema}.ESTPAS ON ESTPAS_ConsInte__CAMPAN_b = CAMPAN_ConsInte__b WHERE ESTPAS_ConsInte__b = ".$_POST['id'];
        $resCampana = $mysqli->query($strCampana);

        if($resCampana && $resCampana->num_rows > 0){
            $dataCampana = $resCampana->fetch_object();
            $tiempoInabilitadoBoton = $dataCampana->tiempoInabilitadoBotonColgado;
        }

        while ($key = $result->fetch_object()) {

            $datos[$i]['G10_ConsInte__b'] = $key->G10_ConsInte__b;

            $datos[$i]['G10_C70'] = $key->G10_C70;

            $datos[$i]['G10_C71'] = $key->G10_C71;

            $datos[$i]['G10_C72'] = $key->G10_C72;

            $datos[$i]['G10_C73'] = $key->G10_C73;

            $datos[$i]['G10_C74'] = $key->G10_C74;

            $datos[$i]['G10_C75'] = $key->G10_C75;

            $datos[$i]['G10_C76'] = $key->G10_C76;

            $datos[$i]['G10_C77'] = $key->G10_C77;

            $datos[$i]['G10_C105'] = $key->G10_C105;

            $datos[$i]['G10_C106'] = $key->G10_C106;

            $datos[$i]['G10_C107'] = $key->G10_C107;

            $datos[$i]['G10_C78'] = $key->G10_C78;

            $datos[$i]['G10_C79'] = $key->G10_C79;

            $datos[$i]['G10_C80'] = $key->G10_C80;

            $datos[$i]['G10_C81'] = $key->G10_C81;

            $datos[$i]['G10_C82'] = $key->G10_C82;

            $datos[$i]['G10_C83'] = $key->G10_C83;

            $datos[$i]['G10_C84'] = $key->G10_C84;

            $datos[$i]['G10_C85'] = $key->G10_C85;

            $datos[$i]['G10_C90'] = $key->G10_C90;

            $datos[$i]['G10_C91'] = $key->G10_C91;

            $datos[$i]['G10_C92'] = $key->G10_C92;

            $datos[$i]['G10_C93'] = $key->G10_C93;

            $datos[$i]['G10_C94'] = $key->G10_C94;

            $datos[$i]['G10_C95'] = $key->G10_C95;

            $datos[$i]['G10_C98'] = $key->G10_C98;

            $datos[$i]['G10_C99'] = $key->G10_C99;

            $datos[$i]['G10_C100'] = $key->G10_C100;

            $datos[$i]['G10_C101'] = $key->G10_C101;

            $datos[$i]['G10_C102'] = $key->G10_C102;

            $datos[$i]['G10_C333'] = $key->G10_C333;

            $datos[$i]['G10_C334'] = $key->G10_C334;

            $datos[$i]['G10_C103'] = $key->G10_C103;

            $datos[$i]['G10_C104'] = $key->G10_C104;

            $datos[$i]['G10_C108'] = $key->G10_C108;

            if (!is_null($key->G10_C109)) {
                $datos[$i]['G10_C109'] = explode(' ', $key->G10_C109)[1];
            }

            if (!is_null($key->G10_C110)) {
                $datos[$i]['G10_C110'] = explode(' ', $key->G10_C110)[1];
            }

            $datos[$i]['G10_C111'] = $key->G10_C111;

            if (!is_null($key->G10_C112)) {
                $datos[$i]['G10_C112'] = explode(' ', $key->G10_C112)[1];
            }

            if (!is_null($key->G10_C113)) {
                $datos[$i]['G10_C113'] = explode(' ', $key->G10_C113)[1];
            }

            $datos[$i]['G10_C114'] = $key->G10_C114;

            if (!is_null($key->G10_C115)) {
                $datos[$i]['G10_C115'] = explode(' ', $key->G10_C115)[1];
            }

            if (!is_null($key->G10_C116)) {
                $datos[$i]['G10_C116'] = explode(' ', $key->G10_C116)[1];
            }

            $datos[$i]['G10_C117'] = $key->G10_C117;

            if (!is_null($key->G10_C118)) {
                $datos[$i]['G10_C118'] = explode(' ', $key->G10_C118)[1];
            }

            $datos[$i]['G10_C119'] = $key->G10_C119;

            if (!is_null($key->G10_C119)) {
                $datos[$i]['G10_C119'] = explode(' ', $key->G10_C119)[1];
            }

            $datos[$i]['G10_C120'] = $key->G10_C120;

            if (!is_null($key->G10_C121)) {
                $datos[$i]['G10_C121'] = explode(' ', $key->G10_C121)[1];
            }

            if (!is_null($key->G10_C122)) {
                $datos[$i]['G10_C122'] = explode(' ', $key->G10_C122)[1];
            }

            $datos[$i]['G10_C123'] = $key->G10_C123;

            if (!is_null($key->G10_C124)) {
                $datos[$i]['G10_C124'] = explode(' ', $key->G10_C124)[1];
            }

            if (!is_null($key->G10_C125)) {
                $datos[$i]['G10_C125'] = explode(' ', $key->G10_C125)[1];
            }

            $datos[$i]['G10_C126'] = $key->G10_C126;

            if (!is_null($key->G10_C127)) {
                $datos[$i]['G10_C127'] = explode(' ', $key->G10_C127)[1];
            }

            if (!is_null($key->G10_C128)) {
                $datos[$i]['G10_C128'] = explode(' ', $key->G10_C128)[1];
            }

            $datos[$i]['G10_C129'] = $key->G10_C129;

            if (!is_null($key->G10_C130)) {
                $datos[$i]['G10_C130'] = explode(' ', $key->G10_C130)[1];
            }

            if (!is_null($key->G10_C131)) {
                $datos[$i]['G10_C131'] = explode(' ', $key->G10_C131)[1];
            }

            // DLAB 20190715 - Se agrega el campo para colocar los canales del marcador robotico
            $datos[$i]['G10_C331'] = $key->G10_C331;
            
            $datos[$i]['G10_C332'] = $key->G10_C332;

            $datos[$i]['G10_C326'] = $key->G10_C326;

            $datos[$i]['principal'] = $key->principal;

            $datos[$i]['tiempoInabilitadoBotonColgado'] = $tiempoInabilitadoBoton;

            //$datos[$i]['G10_ConsInte__b'] = $key->G19_ConsInte__b;
            $i++;
        }
        echo json_encode($datos);
    }

    //Datos de la lista de la izquierda
    if (isset($_POST['CallDatosJson'])) {
        $str_Lsql = 'SELECT G10_ConsInte__b as id,  G10_C71 as camp1 , b.LISOPC_Nombre____b as camp2 ';
        $str_Lsql .= ' FROM ' . $BaseDatos_systema . '.G10   LEFT JOIN ' . $BaseDatos_systema . '.LISOPC as b ON b.LISOPC_ConsInte__b = G10_C76 ';
        if ($_POST['Busqueda'] != '' && !is_null($_POST['Busqueda'])) {
            $str_Lsql .= ' WHERE  G10_C71 like "%' . $_POST['Busqueda'] . '%" ';
            $str_Lsql .= ' OR b.LISOPC_Nombre____b like "%' . $_POST['Busqueda'] . '%" ';
        }

        $str_Lsql .= ' ORDER BY G10_ConsInte__b DESC LIMIT 0, 50';
        $result = $mysqli->query($str_Lsql);
        $datos = array();
        $i = 0;
        while ($key = $result->fetch_object()) {
            $datos[$i]['camp1'] = $key->camp1;
            $datos[$i]['camp2'] = $key->camp2;
            $datos[$i]['id'] = $key->id;
            $i++;
        }
        echo json_encode($datos);
    }

    //Esto ya es para cargar los combos en la grilla
    if (isset($_GET['CallDatosLisop_'])) {
        $lista = $_GET['idLista'];
        $comboe = $_GET['campo'];
        $str_Lsql = "SELECT LISOPC_ConsInte__b AS OPCION_ConsInte__b, LISOPC_Nombre____b AS OPCION_Nombre____b FROM " . $BaseDatos_systema . ".LISOPC WHERE LISOPC_ConsInte__OPCION_b = " . $lista . " ORDER BY LISOPC_Nombre____b";

        $combo = $mysqli->query($str_Lsql);
        echo '<select class="form-control input-sm"  name="' . $comboe . '" id="' . $comboe . '">';
        echo '<option value="0">Seleccione</option>';
        while ($obj = $combo->fetch_object()) {
            echo "<option value='" . $obj->OPCION_ConsInte__b . "'>" . $obj->OPCION_Nombre____b . "</option>";
        }
        echo '</select>';
    }

    if (isset($_GET['CallDatosCombo_Guion_G10_C73'])) {
        $Ysql = 'SELECT   G5_ConsInte__b as id , G5_C28 FROM ".$BaseDatos_systema.".G5';
        $guion = $mysqli->query($Ysql);
        echo '<select class="form-control input-sm"  name="G10_C73" id="G10_C73">';
        echo '<option >NOMBRE</option>';
        while ($obj = $guion->fetch_object()) {
            echo "<option value='" . $obj->id . "' dinammicos='0'>" . utf8_encode($obj->G5_C28) . "</option>";
        }
        echo '</select>';
    }

    if (isset($_GET['CallDatosCombo_Guion_G10_C74'])) {
        $Ysql = 'SELECT   G5_ConsInte__b as id , G5_C28 FROM ".$BaseDatos_systema.".G5';
        $guion = $mysqli->query($Ysql);
        echo '<select class="form-control input-sm"  name="G10_C74" id="G10_C74">';
        echo '<option >NOMBRE</option>';
        while ($obj = $guion->fetch_object()) {
            echo "<option value='" . $obj->id . "' dinammicos='0'>" . utf8_encode($obj->G5_C28) . "</option>";
        }
        echo '</select>';
    }

    if (isset($_GET['CallDatosCombo_Guion_G10_C90'])) {
        $Ysql = 'SELECT   G11_ConsInte__b as id , G11_C87 FROM ".$BaseDatos_systema.".G11';
        $guion = $mysqli->query($Ysql);
        echo '<select class="form-control input-sm"  name="G10_C90" id="G10_C90">';
        echo '<option >NOMBRE USUARIO</option>';
        while ($obj = $guion->fetch_object()) {
            echo "<option value='" . $obj->id . "' dinammicos='0'>" . utf8_encode($obj->G11_C87) . "</option>";
        }
        echo '</select>';
    }

    if (isset($_GET['CallDatosCombo_Guion_G10_C91'])) {
        $Ysql = 'SELECT   G11_ConsInte__b as id , G11_C87 FROM ".$BaseDatos_systema.".G11';
        $guion = $mysqli->query($Ysql);
        echo '<select class="form-control input-sm"  name="G10_C91" id="G10_C91">';
        echo '<option >NOMBRE USUARIO</option>';
        while ($obj = $guion->fetch_object()) {
            echo "<option value='" . $obj->id . "' dinammicos='0'>" . utf8_encode($obj->G11_C87) . "</option>";
        }
        echo '</select>';
    }

    if (isset($_GET['CallDatosCombo_Guion_G10_C98'])) {
        $Ysql = 'SELECT   G12_ConsInte__b as id , G12_C96 FROM ".$BaseDatos_systema.".G12';
        $guion = $mysqli->query($Ysql);
        echo '<select class="form-control input-sm"  name="G10_C98" id="G10_C98">';
        echo '<option >NOMBRE</option>';
        while ($obj = $guion->fetch_object()) {
            echo "<option value='" . $obj->id . "' dinammicos='0'>" . utf8_encode($obj->G12_C96) . "</option>";
        }
        echo '</select>';
    }

    if (isset($_GET['CallDatosCombo_Guion_G10_C101'])) {
        $Ysql = 'SELECT   G12_ConsInte__b as id , G12_C96 FROM ".$BaseDatos_systema.".G12';
        $guion = $mysqli->query($Ysql);
        echo '<select class="form-control input-sm"  name="G10_C101" id="G10_C101">';
        echo '<option >NOMBRE</option>';
        while ($obj = $guion->fetch_object()) {
            echo "<option value='" . $obj->id . "' dinammicos='0'>" . utf8_encode($obj->G12_C96) . "</option>";
        }
        echo '</select>';
    }

    if (isset($_GET['CallDatosCombo_Guion_G10_C103'])) {
        $Ysql = 'SELECT   G6_ConsInte__b as id , G6_C39 FROM ".$BaseDatos_systema.".G6';
        $guion = $mysqli->query($Ysql);
        echo '<select class="form-control input-sm"  name="G10_C103" id="G10_C103">';
        echo '<option >TEXTO</option>';
        while ($obj = $guion->fetch_object()) {
            echo "<option value='" . $obj->id . "' dinammicos='0'>" . utf8_encode($obj->G6_C39) . "</option>";
        }
        echo '</select>';
    }

    if (isset($_GET['CallDatosCombo_Guion_G10_C104'])) {
        $Ysql = 'SELECT   G12_ConsInte__b as id , G12_C96 FROM ".$BaseDatos_systema.".G12';
        $guion = $mysqli->query($Ysql);
        echo '<select class="form-control input-sm"  name="G10_C104" id="G10_C104">';
        echo '<option >NOMBRE</option>';
        while ($obj = $guion->fetch_object()) {
            echo "<option value='" . $obj->id . "' dinammicos='0'>" . utf8_encode($obj->G12_C96) . "</option>";
        }
        echo '</select>';
    }

    if (isset($_POST['CallEliminate'])) {
        if ($_POST['oper'] == 'del') {
            $str_Lsql = "DELETE FROM " . $BaseDatos_systema . ".G10 WHERE G10_ConsInte__b = " . $_POST['id'];
            if ($mysqli->query($str_Lsql) === TRUE) {
                echo "1";
            } else {
                echo "Error eliminado los registros : " . $mysqli->error;
            }
        }
    }

    if (isset($_POST['callDatosNuevamente'])) {
        $inicio = $_POST['inicio'];
        $fin = $_POST['fin'];
        $Zsql = 'SELECT  G10_ConsInte__b as id,  G10_C71 as camp1 , b.LISOPC_Nombre____b as camp2  FROM ' . $BaseDatos_systema . '.G10   LEFT JOIN ' . $BaseDatos_systema . '.LISOPC as b ON b.LISOPC_ConsInte__b = G10_C76 ORDER BY G10_ConsInte__b DESC LIMIT ' . $inicio . ' , ' . $fin;
        $result = $mysqli->query($Zsql);
        while ($obj = $result->fetch_object()) {
            echo "<tr class='CargarDatos' id='" . $obj->id . "'>
                    <td>
                        <p style='font-size:14px;'><b>" . ($obj->camp1) . "</b></p>
                        <p style='font-size:12px; margin-top:-10px;'>" . ($obj->camp2) . "</p>
                    </td>
                </tr>";
        }
    }

    if (isset($_POST['deleteOption'])) {
        $Lsql = "DELETE FROM " . $BaseDatos_systema . ".LISOPC WHERE LISOPC_ConsInte__b = " . $_POST['id'];
        $mysqli->query($Lsql);
        echo "1";
    }

    //Inserciones o actualizaciones
    if (isset($_POST["oper"]) && isset($_GET['insertarDatosGrilla'])) {

        $id_Guion = 0;
        $id_Muestras = 0;
        $id_scrip = 0;

        if (isset($_POST['G10_C74'])) {
            $id_Guion = $_POST['G10_C74'];
        }

        if (isset($_POST['G10_C75'])) {
            $id_Muestras = $_POST['G10_C75'];
        }

        if (isset($_POST['G10_C73'])) {
            $id_scrip = $_POST['G10_C73'];
        }

        //Base de datos G10_C74;

        if ($_POST["oper"] == 'add') {

            // Se valia si existe

            $Lsql_ValidaCampan = "SELECT G10_ConsInte__b FROM " . $BaseDatos_systema . ".G10 JOIN " . $BaseDatos_systema . ".ESTPAS ON ESTPAS_ConsInte__CAMPAN_b = G10_ConsInte__b WHERE ESTPAS_ConsInte__b =" . $_POST['id_paso'];

            $res_ValidaCampan = $mysqli->query($Lsql_ValidaCampan);

            if ($res_ValidaCampan && $res_ValidaCampan->num_rows < 1) {

                // Generamos el formulario estandar para el marcador
                $id_scrip = generarTablaGestionesMarcador($_POST['id_paso'], $_POST["G10_C71"]);

                //No existe Y pues toca crearla de una para que se pueda editar todo
                //echo "Si llega aqui y esta es la poblacion => ".$_GET['poblacion'];
                $id_Muestras = 0;
                //Base de datos G10_C74;
                $Lsql = "INSERT INTO " . $BaseDatos_systema . ".MUESTR (MUESTR_Nombre____b, MUESTR_ConsInte__GUION__b) VALUES ('" . $id_Guion . "_MUESTRA_" . rand() . "', '" . $id_Guion . "')";
                if ($mysqli->query($Lsql) === true) {
                    $id_Muestras = $mysqli->insert_id;
                    //echo "Entra aqui tambien y este es el id de la muestra".$id_Muestras;

                    $CreateMuestraLsql = "CREATE TABLE `".$BaseDatos."`.`G".$id_Guion."_M".$id_Muestras."` (
                                                  `G".$id_Guion."_M".$id_Muestras."_CoInMiPo__b` int(10) NOT NULL,
                                                  `G".$id_Guion."_M".$id_Muestras."_ConIntUsu_b` int(10) DEFAULT NULL,
                                                  `G".$id_Guion."_M".$id_Muestras."_Activo____b` smallint(5) DEFAULT '-1',
                                                  `G".$id_Guion."_M".$id_Muestras."_FecHorMinProGes__b` datetime DEFAULT NULL,
                                                  `G".$id_Guion."_M".$id_Muestras."_FechaCreacion_b` datetime DEFAULT NULL,
                                                  `G".$id_Guion."_M".$id_Muestras."_FechaAsignacion_b` datetime DEFAULT NULL,
                                                  `G".$id_Guion."_M".$id_Muestras."_FechaReactivacion_b` datetime DEFAULT NULL,
                                                  

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


                                                  PRIMARY KEY (`G" . $id_Guion . "_M" . $id_Muestras . "_CoInMiPo__b`),
                                                  KEY `G" . $id_Guion . "_M" . $id_Muestras . "_Estado____b_Indice` (`G" . $id_Guion . "_M" . $id_Muestras . "_Estado____b`),
                                                  KEY `G" . $id_Guion . "_M" . $id_Muestras . "_ConIntUsu_b_Indice` (`G" . $id_Guion . "_M" . $id_Muestras . "_ConIntUsu_b`)
                                                ) ENGINE=InnoDB DEFAULT CHARSET=utf8;";
                    if ($mysqli->query($CreateMuestraLsql) === true) {
                        //echo "Si creo la tabla";
                    } else {
                        echo $mysqli->error;
                    }

                    $str_Lsql = '';
                    $validar = 0;

                    $idIvr = (isset($_POST["G10_C90"])) ? $_POST["G10_C90"] : 0;

                    $str_LsqlI = "INSERT INTO " . $BaseDatos_systema . ".G10(G10_C73, G10_C74, G10_C72 , G10_C75, G10_C78, G10_C80, G10_C92 , G10_C93, G10_C94, G10_C99, G10_C109, G10_C110, G10_C112, G10_C113, G10_C115, G10_C116, G10_C118, G10_C119, G10_C121, G10_C122, G10_C76, G10_C77, G10_C108, G10_C111, G10_C114, G10_C117, G10_C120, G10_C71, G10_C126, G10_C127, G10_C128, G10_C129, G10_C130, G10_C131 , G10_C123, G10_C124, G10_C125, G10_C79, G10_C102, G10_C326, G10_C90, G10_C331)";
                    $str_LsqlV = " VALUES ('" . $id_scrip . "', '" . $id_Guion . "', -1, " . $id_Muestras . ", -1, 5, 0, 2, 30, 0, '" . date('Y-m-d') . " 00:00:01', '" . date('Y-m-d') . " 23:59:59', '" . date('Y-m-d') . " 00:00:01', '" . date('Y-m-d') . " 23:59:59', '" . date('Y-m-d') . " 00:00:01', '" . date('Y-m-d') . " 23:59:59', '" . date('Y-m-d') . " 00:00:01', '" . date('Y-m-d') . " 23:59:59', '" . date('Y-m-d') . " 00:00:01', '" . date('Y-m-d') . " 23:59:59', 8, -1 ,  -1, -1, -1, -1, -1, '" . $_POST['G10_C71'] . "', -1, '" . date('Y-m-d') . " 00:00:01', '" . date('Y-m-d') . " 23:59:59', -1, '" . date('Y-m-d') . " 00:00:01', '" . date('Y-m-d') . " 23:59:59', -1, '" . date('Y-m-d') . " 00:00:01', '" . date('Y-m-d') . " 23:59:59', '-1', '90', '25', '{$idIvr}', '2')";
                    $Lsql_Insertar = $str_LsqlI . $str_LsqlV;
                    //echo $Lsql_Insertar;
                    if ($mysqli->query($Lsql_Insertar) === TRUE) {
                        //echo "Creo la campaña";

                        $id_usuario = $mysqli->insert_id;

                        $Bdtraducir = $id_Guion;

                        $Script = $id_scrip;

                        //Ordenamiento
                        $Lsql_CAMCOR = "INSERT INTO " . $BaseDatos_systema . ".CAMORD (CAMORD_MUESPOBL__B, CAMORD_POBLCAMP__B, CAMORD_MUESCAMP__B, CAMORD_PRIORIDAD_B, CAMORD_ORDEN_____B, CAMORD_CONSINTE__CAMPAN_B) VALUES ('M', NULL, '_Estado____b', '1', 'ASC', " . $id_usuario . "), ('M', NULL, '_FecUltGes_b', '2', 'ASC', " . $id_usuario . ");";
                        if ($mysqli->query($Lsql_CAMCOR) === true) {
                            //echo "Creo el Id_paso con la campaña";
                        } else {
                            echo "ERROR CAMORD" . $mysqli->error;
                        }

                        //Este es la union de Paso, campaña y muestra
                        $Lsql = "UPDATE " . $BaseDatos_systema . ".ESTPAS SET ESTPAS_ConsInte__CAMPAN_b = " . $id_usuario . " , ESTPAS_ConsInte__MUESTR_b =  ".$id_Muestras." WHERE ESTPAS_ConsInte__b = " . $_POST['id_paso'];

                        if ($mysqli->query($Lsql) === true) {
                            //echo "Creo el Id_paso con la campaña";
                        } else {
                            echo "ESTPAS ERROR " . $mysqli->error;
                        }

                        $data = array(
                            "strUsuario_t" => 'local',
                            "strToken_t" => 'local',
                            "intIdESTPAS_t" => $_POST['id_paso']
                        );
                        $data_string = json_encode($data);
                        //echo $data_string;
                        $ch = curl_init($Api_Gestion . 'dyalogocore/api/campanas/voip/persistir');
                        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
                        curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
                        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
                        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                            'Content-Type: application/json',
                            'Content-Length: ' . strlen($data_string))
                        );
                        $respuesta = curl_exec($ch);
                        $error = curl_error($ch);
                        curl_close($ch);
                        //echo " Respuesta => ".$respuesta;
                        //echo " Error => ".$error;
                        $dyCampanId = "NULL";
                        if (!empty($respuesta) && !is_null($respuesta)) {
                            $json = json_decode($respuesta);

                            $dyCampanId = $json->objSerializar_t;

                            if ($json->strEstado_t == "ok") {
                                //en caso de que sea extoso

                                $UpdateSqlCampanCBX = "UPDATE " . $BaseDatos_systema . ".CAMPAN SET CAMPAN_IdCamCbx__b = ".$dyCampanId." WHERE CAMPAN_ConsInte__b = " . $id_usuario;
                                if ($mysqli->query($UpdateSqlCampanCBX) === true) {
                                    //si actualizo esta jugada bienvenido sea
                                }

                                $ESTPAS_CampanACD_b = "UPDATE ".$BaseDatos_systema.".ESTPAS SET ESTPAS_CampanACD_b = ".$dyCampanId." WHERE ESTPAS_ConsInte__b = ".$_POST['id_paso'];

                                if ($mysqli->query($ESTPAS_CampanACD_b) === true) {
                                
                                }
                            }
                        }


                        /* Manipular los datos de caminc */

                        if (isset($datosPoblacion)) {
                            for ($i = 0; $i < count($datosPoblacion); $i++) {
                                $strTextoPo_t=substr($datosPoblacion[$i]['CAMINC_TexPrePob_b'], 0,250);
                                $strTextoGion_t=substr($datosScript[$i]['CAMINC_TexPreGui_b'], 0,250);
                                $CamincLsql = "INSERT INTO " . $BaseDatos_systema . ".CAMINC(CAMINC_ConsInte__CAMPO_Pob_b,CAMINC_NomCamPob_b , CAMINC_TexPrePob_b,CAMINC_ConsInte__CAMPO_Gui_b,CAMINC_NomCamGui_b , CAMINC_TexPreGui_b, CAMINC_ConsInte__CAMPAN_b, CAMINC_ConsInte__GUION__Pob_b, CAMINC_ConsInte__GUION__Gui_b, CAMINC_ConsInte__MUESTR_b ) VALUES ( '" . $datosPoblacion[$i]['CAMINC_ConsInte__CAMPO_Pob_b'] . "' , '" . $datosPoblacion[$i]['CAMINC_NomCamPob_b'] . "' , '" . $strTextoPo_t . "' , '" . $datosScript[$i]['CAMINC_ConsInte__CAMPO_Gui_b'] . "' , '" . $datosScript[$i]['CAMINC_NomCamGui_b'] . "' , '" . $strTextoGion_t . "' , " . $id_usuario . " , '" . $datosPoblacion[$i]['CAMINC_ConsInte__GUION__Pob_b'] . "' , '" . $datosScript[$i]['CAMINC_ConsInte__GUION__Gui_b'] . "' , " . $id_Muestras . ")";

                                if ($mysqli->query($CamincLsql) === true) {
                                    
                                } else {
                                    echo "ERROR CAMINC" . $mysqli->error;
                                }
                            }
                        }


                        /* Manipular los datos de CAMCOM */
                        //var_dump($dtaosTelefonicos);
                        $datosTelefonicosLsql = "SELECT PREGUN_ConsInte__b ,PREGUN_Texto_____b, CAMPO__ConsInte__b  FROM " . $BaseDatos_systema . ".PREGUN JOIN " . $BaseDatos_systema . ".CAMPO_ ON CAMPO__ConsInte__PREGUN_b = PREGUN_ConsInte__b  WHERE  PREGUN_ConsInte__GUION__b = " . $Bdtraducir . " AND (PREGUN_Texto_____b like '%celular%' or PREGUN_Texto_____b like '%telefono%' or PREGUN_Texto_____b like '%teléfono%' or PREGUN_Texto_____b = 'cel' or PREGUN_Texto_____b = 'tel') AND (PREGUN_Tipo______b = 1 OR PREGUN_Tipo______b = 2 OR PREGUN_Tipo______b = 3 OR PREGUN_Tipo______b = 4 );";

                        $res_Tel = $mysqli->query($datosTelefonicosLsql);
                        $i = 0;
                        while ($key = $res_Tel->fetch_object()) {

                            //ar_dump($dtaosTelefonicos[$i]);
                            $LsqCamcom = "INSERT INTO " . $BaseDatos_systema . ".CAMCON (CAMCON_ConsInte__CAMPAN_b , CAMCON_ConsInte__GUION__Gui_b, CAMCON_ConsInte__GUION__Pob_b, CAMCON_ConsInte__MUESTR_b, CAMCON_Nombre____b, CAMCON_Orden_____b, CAMCON_ConsInte__PREGUN_b, CAMCON_TextPreg__b, CAMCON_ConsInte__CAMPO__Pob_b) VALUES (" . $id_usuario . " , " . $Script . " , " . $Bdtraducir . " , " . $id_Muestras . " , 'G" . $Bdtraducir . "_C" . $key->PREGUN_ConsInte__b . "' , " . ($i + 1) . ", " . $key->PREGUN_ConsInte__b . " , '" . $key->PREGUN_Texto_____b . "' ," . $key->CAMPO__ConsInte__b . ");";

                            if ($mysqli->query($LsqCamcom) === true) {
                                
                            } else {
                                echo "ERROR CAMCOM" . $mysqli->error;
                            }

                            $i++;
                        }


                        /* aqui toca cargar la base de datos */
                        if (isset($_POST['insertarDataBase']) && !empty($_FILES['newGuionFile']['tmp_name'])) {
                            /* insertar la base de datos */
                            $name = $_FILES['newGuionFile']['name'];
                            $tname = $_FILES['newGuionFile']['tmp_name'];
                            ini_set('memory_limit', '128M');




                            if ($_FILES['newGuionFile']["type"] == 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet') {
                                $objReader = new PHPExcel_Reader_Excel2007();
                                $objReader->setReadDataOnly(true);
                                $obj_excel = $objReader->load($tname);
                            } else if ($_FILES['newGuionFile']["type"] == 'application/vnd.ms-excel') {
                                $obj_excel = PHPExcel_IOFactory::load($tname);
                            }

                            $sheetData = $obj_excel->getActiveSheet()->toArray(null, true, true, true);
                            $arr_datos = array();
                            $highestColumm = $obj_excel->setActiveSheetIndex(0)->getHighestColumn();
                            $highestRow = $obj_excel->setActiveSheetIndex(0)->getHighestRow();



                            foreach ($sheetData as $index => $value) {
                                if ($index > 1) {
                                    if ((!is_null($value['A']) OR ! empty($value['A'])) &&
                                            (!is_null($value['B']) OR ! empty($value['B']))
                                    ) {

                                        $Lsql_InsertarBase = "INSERT INTO " . $BaseDatos . ".G" . $Bdtraducir . "(G" . $Bdtraducir . "_FechaInsercion";
                                        $Lsql_ValuesssBase = " VALUES ('" . date('Y-m-d H:s:i') . "'";

                                        for ($i = 0; $i < count($datosColumnasInsertar); $i++) {
                                            $Lsql_InsertarBase .= " , " . $datosColumnasInsertar[$i]['campo'];
                                            $Lsql_ValuesssBase .= " , '" . $value[$datosColumnasInsertar[$i]['column']] . "'";
                                        }

                                        $Lsql_Insercion = $Lsql_InsertarBase . ")" . $Lsql_ValuesssBase . ")";
                                        if ($mysqli->query($Lsql_Insercion) === true) {
                                            /* ahora enseguida a la muestra */
                                            $ultimoResgistroInsertado = $mysqli->insert_id;
                                            $muestraCompleta = "G" . $Bdtraducir . "_M" . $id_Muestras;
                                            $insertarMuestraLsql = "INSERT INTO  " . $BaseDatos . "." . $muestraCompleta . " (" . $muestraCompleta . "_CoInMiPo__b ,  " . $muestraCompleta . "_NumeInte__b, " . $muestraCompleta . "_Estado____b) VALUES (" . $ultimoResgistroInsertado . ", 0 , 0);";
                                            $mysqli->query($insertarMuestraLsql);
                                        } else {
                                            echo "Error Insertando Los Datos en la base " . $mysqli->error;
                                        }
                                    }
                                }
                            }
                        } else {

                            // Valido si la estrategia es tipo 3 para no ejecutar esto, pero tengo que traer la estrategia
                            $sqlEstrategia = "SELECT ESTRAT_ConsInte__b AS id, ESTRAT_ConsInte__TIPO_ESTRAT_b AS tipo, ESTRAT_Nombre____b AS nombre FROM ".$BaseDatos_systema.".ESTRAT JOIN ".$BaseDatos_systema.".ESTPAS ON ESTRAT_ConsInte__b = ESTPAS_ConsInte__ESTRAT_b WHERE ESTPAS_ConsInte__b = ".$_POST['id_paso']." LIMIT 1";
                            $resEstrategia = $mysqli->query($sqlEstrategia);
                            $estrategia = $resEstrategia->fetch_object();

                            if($estrategia->tipo == 1){

                                // Si la estrategia es tipo 2 campaña entrante simple o tipo 1 campaña saliente simple ejecute esto

                                $SelectLsql = "SELECT G" . $id_Guion . "_ConsInte__b as id FROM " . $BaseDatos . ".G" . $id_Guion . ";";
                                //echo $SelectLsql;
                                $ros = $mysqli->query($SelectLsql);
                                if ($ros) {
                                    //echo "Aqui";
                                    while ($key = $ros->fetch_object()) {
                                        $ultimoResgistroInsertado = $key->id;
                                        $muestraCompleta = "G" . $id_Guion . "_M" . $id_Muestras;
                                        $insertarMuestraLsql = "INSERT INTO  " . $BaseDatos . "." . $muestraCompleta . " (" . $muestraCompleta . "_CoInMiPo__b ,  " . $muestraCompleta . "_NumeInte__b, " . $muestraCompleta . "_Estado____b) VALUES (" . $ultimoResgistroInsertado . ", 0 , 0);";
                                        $mysqli->query($insertarMuestraLsql);
                                    }
                                }

                            }

                        }






                        /* le asignamos la base de datos a la estartegia */
                        $PasoLsql = "SELECT ESTPAS_ConsInte__ESTRAT_b FROM " . $BaseDatos_systema . ".ESTPAS WHERE ESTPAS_ConsInte__b = " . $_POST['id_paso'];
                        $resPAso = $mysqli->query($PasoLsql);
                        $Estrat = $resPAso->fetch_array();

                        $EstratLsql = "UPDATE " . $BaseDatos_systema . ".ESTRAT SET ESTRAT_ConsInte_GUION_Pob = " . $Bdtraducir . " WHERE ESTRAT_ConsInte__b = " . $Estrat['ESTPAS_ConsInte__ESTRAT_b'];
                        if ($mysqli->query($EstratLsql) === true) {
                            
                        } else {
                            echo "error Estrat => " . $mysqli->error;
                        }

                        $UpdatePass = "UPDATE " . $BaseDatos_systema . ".ESTPAS SET ESTPAS_Comentari_b = '" . $_POST['G10_C71'] . "', ESTPAS_activo = -1 WHERE ESTPAS_ConsInte__b = " . $_POST['id_paso'];
                        if ($mysqli->query($UpdatePass) === true) {
                            
                        } else {
                            echo "error ESTPAS => " . $mysqli->error;
                        }

                        //JDBD - se crean las vistas de las estrategias del huesped en MYSQL 
                        generarVistasPorHuesped($_SESSION['HUESPED']);
                        $intIdEstrat_t = $Estrat['ESTPAS_ConsInte__ESTRAT_b'];
                        $strNomEstrat_t = datosEstrat($intIdEstrat_t);
                        $strNombreReport_t = $_SESSION['PROYECTO'];
                        $strRutaArchivo_t = "/tmp/".$strNombreReport_t."_".$strNomEstrat_t;
                        $strRutaArchivo_t = str_replace(' ', '_', $strRutaArchivo_t);
                        $strRutaArchivo_t = quitarTildes($strRutaArchivo_t);

                        //JDBD - se crea reporte diario
                        if (verificarReporteExistente("NORMAL",1,$_SESSION['HUESPED'],$intIdEstrat_t)) {

                            insertarNuevoReporte($strNomEstrat_t."_DIARIO",$strRutaArchivo_t."_DIARIO",$_SESSION['CORREO'],'20:00','','1',$intIdEstrat_t,$_SESSION['HUESPED'],false);
                            
                        }else{

                            insertarNuevoReporte($strNomEstrat_t."_DIARIO",$strRutaArchivo_t."_DIARIO",$_SESSION['CORREO'],'20:00','','1',$intIdEstrat_t,$_SESSION['HUESPED'],true);

                        }

                        //JDBD - se crea reporte semanal
                        if (verificarReporteExistente("NORMAL",2,$_SESSION['HUESPED'],$intIdEstrat_t)) {

                            insertarNuevoReporte($strNomEstrat_t."_SEMANAL",$strRutaArchivo_t."_SEMANAL",$_SESSION['CORREO'],'07:00','','2',$intIdEstrat_t,$_SESSION['HUESPED'],false);

                        }else{

                            insertarNuevoReporte($strNomEstrat_t."_SEMANAL",$strRutaArchivo_t."_SEMANAL",$_SESSION['CORREO'],'07:00','','2',$intIdEstrat_t,$_SESSION['HUESPED'],true);

                        }

                        //JDBD - se crea reporte mensual
                        if (verificarReporteExistente("NORMAL",3,$_SESSION['HUESPED'],$intIdEstrat_t)) {

                            insertarNuevoReporte($strNomEstrat_t."_MENSUAL",$strRutaArchivo_t."_MENSUAL",$_SESSION['CORREO'],'07:00','','3',$intIdEstrat_t,$_SESSION['HUESPED'],false);

                        }else{

                            insertarNuevoReporte($strNomEstrat_t."_MENSUAL",$strRutaArchivo_t."_MENSUAL",$_SESSION['CORREO'],'07:00','','3',$intIdEstrat_t,$_SESSION['HUESPED'],true);

                        }
                        
                        //JDBD - se crea reporte adherencia
                        if (verificarReporteExistente("ADHERENCIA",1,$_SESSION['HUESPED'],$intIdEstrat_t)) {

                            ctrCrearReportesDirarioAdherencia($intIdEstrat_t,$_SESSION['HUESPED'], $_SESSION['CORREO'],null,null,$strNombreReport_t."_ADHERENCIA",'20:00',false);

                        }else{

                            ctrCrearReportesDirarioAdherencia($intIdEstrat_t,$_SESSION['HUESPED'], $_SESSION['CORREO'],null,null,$strNombreReport_t."_ADHERENCIA",'20:00',true);

                        }

                        //JDBD - definicion de metas.
                        
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

                        if(!isset($_POST['byCallback'])){
                            echo true;
                        }    
                    } else {
                        echo "ERROR CAMPANHA => " . $mysqli->error;
                    }
                } else {
                    echo "CREANDO LA MUESTRA => " . $mysqli->error;
                }
            } else {
                //echo "aja";
            }


            // Al final se generan las vistas

            
        }



        $str_Lsql = '';

        $validar = 0;
        $str_LsqlU = "UPDATE " . $BaseDatos_systema . ".G10 SET ";
        $str_LsqlI = "INSERT INTO " . $BaseDatos_systema . ".G10(";
        $str_LsqlV = " VALUES (";

        $G10_C70 = NULL;
        //este es de tipo numero no se deja ir asi '', si est avacio lo mejor es no mandarlo
        if (isset($_POST["G10_C70"])) {
            if ($_POST["G10_C70"] != '') {
                $separador = "";
                if ($validar == 1) {
                    $separador = ",";
                }

                //$G10_C70 = $_POST["G10_C70"];
                $G10_C70 = str_replace(".", "", $_POST["G10_C70"]);
                $G10_C70 = str_replace(",", ".", $G10_C70);
                $str_LsqlU .= $separador . " G10_C70 = '" . $G10_C70 . "'";
                $str_LsqlI .= $separador . " G10_C70";
                $str_LsqlV .= $separador . "'" . $G10_C70 . "'";
                $validar = 1;
            }
        }

        if (isset($_POST["G10_C71"])) {
            $separador = "";
            if ($validar == 1) {
                $separador = ",";
            }

            $str_LsqlU .= $separador . "G10_C71 = '" . $_POST["G10_C71"] . "'";
            $str_LsqlI .= $separador . "G10_C71";
            $str_LsqlV .= $separador . "'" . $_POST["G10_C71"] . "'";
            $validar = 1;
        }


        $G10_C72 = 0;
        //este es tipo check primeo si viene y de acuerdo a su valor se pone 1 o 0
        if (isset($_POST["G10_C72"])) {
            if ($_POST["G10_C72"] == 'Yes') {
                $G10_C72 = 1;
            } else if ($_POST["G10_C72"] == 'off') {
                $G10_C72 = 0;
            } else if ($_POST["G10_C72"] == 'on') {
                $G10_C72 = 1;
            } else if ($_POST["G10_C72"] == 'No') {
                $G10_C72 = 0;
            } else {
                $G10_C72 = $_POST["G10_C72"];
            }

            $separador = "";
            if ($validar == 1) {
                $separador = ",";
            }

            $str_LsqlU .= $separador . " G10_C72 = " . $G10_C72 . "";
            $str_LsqlI .= $separador . " G10_C72";
            $str_LsqlV .= $separador . $G10_C72;

            $validar = 1;
        } else {
            $separador = "";
            if ($validar == 1) {
                $separador = ",";
            }

            $str_LsqlU .= $separador . " G10_C72 = " . $G10_C72 . "";
            $str_LsqlI .= $separador . " G10_C72";
            $str_LsqlV .= $separador . $G10_C72;

            $validar = 1;
        }

        if (isset($_POST["G10_C73"])) {
            $separador = "";
            if ($validar == 1) {
                $separador = ",";
            }

            $str_LsqlU .= $separador . "G10_C73 = '" . $_POST["G10_C73"] . "'";
            $str_LsqlI .= $separador . "G10_C73";
            $str_LsqlV .= $separador . "'" . $_POST["G10_C73"] . "'";
            $validar = 1;
        }


        if (isset($_POST["G10_C74"])) {
            $separador = "";
            if ($validar == 1) {
                $separador = ",";
            }

            $str_LsqlU .= $separador . "G10_C74 = '" . $_POST["G10_C74"] . "'";
            $str_LsqlI .= $separador . "G10_C74";
            $str_LsqlV .= $separador . "'" . $_POST["G10_C74"] . "'";
            $validar = 1;
        }


        $G10_C75 = NULL;
        //este es de tipo numero no se deja ir asi '', si est avacio lo mejor es no mandarlo
        if (isset($_POST["G10_C75"])) {
            if ($_POST["G10_C75"] != '') {
                $separador = "";
                if ($validar == 1) {
                    $separador = ",";
                }

                //$G10_C75 = $_POST["G10_C75"];
                $G10_C75 = str_replace(".", "", $_POST["G10_C75"]);
                $G10_C75 = str_replace(",", ".", $G10_C75);
                $str_LsqlU .= $separador . " G10_C75 = '" . $G10_C75 . "'";
                $str_LsqlI .= $separador . " G10_C75";
                $str_LsqlV .= $separador . "'" . $G10_C75 . "'";
                $validar = 1;
            }
        }

        if (isset($_POST["G10_C76"])) {
            $separador = "";
            if ($validar == 1) {
                $separador = ",";
            }

            $str_LsqlU .= $separador . "G10_C76 = '" . $_POST["G10_C76"] . "'";
            $str_LsqlI .= $separador . "G10_C76";
            $str_LsqlV .= $separador . "'" . $_POST["G10_C76"] . "'";
            $validar = 1;
        }


        if (isset($_POST["G10_C77"])) {
            $separador = "";
            if ($validar == 1) {
                $separador = ",";
            }

            $str_LsqlU .= $separador . "G10_C77 = '" . $_POST["G10_C77"] . "'";
            $str_LsqlI .= $separador . "G10_C77";
            $str_LsqlV .= $separador . "'" . $_POST["G10_C77"] . "'";
            $validar = 1;

            if($_POST["G10_C77"]=='-1'){
                $desAgendas ="SELECT CAMPAN_ConsInte__GUION__Pob_b, CAMPAN_ConsInte__GUION__Gui_b, CAMPAN_ConsInte__MUESTR_b FROM " . $BaseDatos_systema . ".CAMPAN WHERE CAMPAN_ConsInte__b = " . $_POST['id'];
                $desAgendas=$mysqli->query($desAgendas);
                if($desAgendas){
                    $des=$desAgendas->fetch_object();
                    $desasignado="UPDATE DYALOGOCRM_WEB.G".$des->CAMPAN_ConsInte__GUION__Pob_b."_M".$des->CAMPAN_ConsInte__MUESTR_b." SET G".$des->CAMPAN_ConsInte__GUION__Pob_b."_M".$des->CAMPAN_ConsInte__MUESTR_b."_ConIntUsu_b=NULL where G".$des->CAMPAN_ConsInte__GUION__Pob_b."_M".$des->CAMPAN_ConsInte__MUESTR_b."_Estado____b !=2";
                    $desasignado=$mysqli->query($desasignado);
                }                
            }
        }


        $G10_C105 = NULL;
        //este es de tipo numero no se deja ir asi '', si est avacio lo mejor es no mandarlo
        if (isset($_POST["G10_C105"])) {
            if ($_POST["G10_C105"] != '') {
                $separador = "";
                if ($validar == 1) {
                    $separador = ",";
                }

                //$G10_C105 = $_POST["G10_C105"];
                $G10_C105 = str_replace(".", "", $_POST["G10_C105"]);
                $G10_C105 = str_replace(",", ".", $G10_C105);
                $str_LsqlU .= $separador . " G10_C105 = '" . $G10_C105 . "'";
                $str_LsqlI .= $separador . " G10_C105";
                $str_LsqlV .= $separador . "'" . $G10_C105 . "'";
                $validar = 1;
            }
        }

        $G10_C106 = NULL;
        //este es de tipo numero no se deja ir asi '', si est avacio lo mejor es no mandarlo
        if (isset($_POST["G10_C106"])) {
            if ($_POST["G10_C106"] != '') {
                $separador = "";
                if ($validar == 1) {
                    $separador = ",";
                }

                //$G10_C106 = $_POST["G10_C106"];
                $G10_C106 = str_replace(".", "", $_POST["G10_C106"]);
                $G10_C106 = str_replace(",", ".", $G10_C106);
                $str_LsqlU .= $separador . " G10_C106 = '" . $G10_C106 . "'";
                $str_LsqlI .= $separador . " G10_C106";
                $str_LsqlV .= $separador . "'" . $G10_C106 . "'";
                $validar = 1;
            }
        }

        $G10_C107 = NULL;
        //este es de tipo numero no se deja ir asi '', si est avacio lo mejor es no mandarlo
        if (isset($_POST["G10_C107"])) {
            if ($_POST["G10_C107"] != '') {
                $separador = "";
                if ($validar == 1) {
                    $separador = ",";
                }

                //$G10_C107 = $_POST["G10_C107"];
                $G10_C107 = str_replace(".", "", $_POST["G10_C107"]);
                $G10_C107 = str_replace(",", ".", $G10_C107);
                $str_LsqlU .= $separador . " G10_C107 = '" . $G10_C107 . "'";
                $str_LsqlI .= $separador . " G10_C107";
                $str_LsqlV .= $separador . "'" . $G10_C107 . "'";
                $validar = 1;
            }
        }

        $G10_C79 = 0;
        //este es tipo check primeo si viene y de acuerdo a su valor se pone 1 o 0
        if (isset($_POST["G10_C79"])) {
            if ($_POST["G10_C79"] == 'Yes') {
                $G10_C79 = 1;
            } else if ($_POST["G10_C79"] == 'off') {
                $G10_C79 = 0;
            } else if ($_POST["G10_C79"] == 'on') {
                $G10_C79 = 1;
            } else if ($_POST["G10_C79"] == 'No') {
                $G10_C79 = 0;
            } else {
                $G10_C79 = $_POST["G10_C79"];
            }

            $separador = "";
            if ($validar == 1) {
                $separador = ",";
            }

            $str_LsqlU .= $separador . " G10_C79 = " . $G10_C79 . "";
            $str_LsqlI .= $separador . " G10_C79";
            $str_LsqlV .= $separador . $G10_C79;

            $validar = 1;
        } else {
            $separador = "";
            if ($validar == 1) {
                $separador = ",";
            }

            $str_LsqlU .= $separador . " G10_C79 = " . $G10_C79 . "";
            $str_LsqlI .= $separador . " G10_C79";
            $str_LsqlV .= $separador . $G10_C79;

            $validar = 1;
        }

        $G10_C80 = NULL;
        //este es de tipo numero no se deja ir asi '', si est avacio lo mejor es no mandarlo
        if (isset($_POST["G10_C80"])) {
            if ($_POST["G10_C80"] != '') {
                $separador = "";
                if ($validar == 1) {
                    $separador = ",";
                }

                //$G10_C80 = $_POST["G10_C80"];
                $G10_C80 = str_replace(".", "", $_POST["G10_C80"]);
                $G10_C80 = str_replace(",", ".", $G10_C80);
                $str_LsqlU .= $separador . " G10_C80 = '" . $G10_C80 . "'";
                $str_LsqlI .= $separador . " G10_C80";
                $str_LsqlV .= $separador . "'" . $G10_C80 . "'";
                $validar = 1;
            }
        }

        $G10_C81 = 0;
        //este es tipo check primeo si viene y de acuerdo a su valor se pone 1 o 0
        if (isset($_POST["G10_C81"])) {
            if ($_POST["G10_C81"] == 'Yes') {
                $G10_C81 = 1;
            } else if ($_POST["G10_C81"] == 'off') {
                $G10_C81 = 0;
            } else if ($_POST["G10_C81"] == 'on') {
                $G10_C81 = 1;
            } else if ($_POST["G10_C81"] == 'No') {
                $G10_C81 = 0;
            } else {
                $G10_C81 = $_POST["G10_C81"];
            }

            $separador = "";
            if ($validar == 1) {
                $separador = ",";
            }

            $str_LsqlU .= $separador . " G10_C81 = " . $G10_C81 . "";
            $str_LsqlI .= $separador . " G10_C81";
            $str_LsqlV .= $separador . $G10_C81;

            $validar = 1;
        } else {
            $separador = "";
            if ($validar == 1) {
                $separador = ",";
            }

            $str_LsqlU .= $separador . " G10_C81 = " . $G10_C81 . "";
            $str_LsqlI .= $separador . " G10_C81";
            $str_LsqlV .= $separador . $G10_C81;

            $validar = 1;
        }

        $G10_C82 = NULL;
        //este es de tipo numero no se deja ir asi '', si est avacio lo mejor es no mandarlo
        if (isset($_POST["G10_C82"])) {
            if ($_POST["G10_C82"] != '') {
                $separador = "";
                if ($validar == 1) {
                    $separador = ",";
                }

                //$G10_C82 = $_POST["G10_C82"];
                $G10_C82 = str_replace(".", "", $_POST["G10_C82"]);
                $G10_C82 = str_replace(",", ".", $G10_C82);
                $str_LsqlU .= $separador . " G10_C82 = '" . $G10_C82 . "'";
                $str_LsqlI .= $separador . " G10_C82";
                $str_LsqlV .= $separador . "'" . $G10_C82 . "'";
                $validar = 1;
            }
        }

        $G10_C83 = NULL;
        //este es de tipo numero no se deja ir asi '', si est avacio lo mejor es no mandarlo
        if (isset($_POST["G10_C83"])) {
            if ($_POST["G10_C83"] != '') {
                $separador = "";
                if ($validar == 1) {
                    $separador = ",";
                }

                //$G10_C83 = $_POST["G10_C83"];
                $G10_C83 = str_replace(".", "", $_POST["G10_C83"]);
                $G10_C83 = str_replace(",", ".", $G10_C83);
                $str_LsqlU .= $separador . " G10_C83 = '" . $G10_C83 . "'";
                $str_LsqlI .= $separador . " G10_C83";
                $str_LsqlV .= $separador . "'" . $G10_C83 . "'";
                $validar = 1;
            }
        }

        $G10_C84 = NULL;
        //este es de tipo numero no se deja ir asi '', si est avacio lo mejor es no mandarlo
        if (isset($_POST["G10_C84"])) {
            if ($_POST["G10_C84"] != '') {
                $separador = "";
                if ($validar == 1) {
                    $separador = ",";
                }

                //$G10_C84 = $_POST["G10_C84"];
                $G10_C84 = str_replace(".", "", $_POST["G10_C84"]);
                $G10_C84 = str_replace(",", ".", $G10_C84);
                $str_LsqlU .= $separador . " G10_C84 = '" . $G10_C84 . "'";
                $str_LsqlI .= $separador . " G10_C84";
                $str_LsqlV .= $separador . "'" . $G10_C84 . "'";
                $validar = 1;
            }
        }

        $G10_C85 = 0;
        //este es tipo check primeo si viene y de acuerdo a su valor se pone 1 o 0
        if (isset($_POST["G10_C85"])) {
            if ($_POST["G10_C85"] == 'Yes') {
                $G10_C85 = 1;
            } else if ($_POST["G10_C85"] == 'off') {
                $G10_C85 = 0;
            } else if ($_POST["G10_C85"] == 'on') {
                $G10_C85 = 1;
            } else if ($_POST["G10_C85"] == 'No') {
                $G10_C85 = 0;
            } else {
                $G10_C85 = $_POST["G10_C85"];
            }

            $separador = "";
            if ($validar == 1) {
                $separador = ",";
            }

            $str_LsqlU .= $separador . " G10_C85 = " . $G10_C85 . "";
            $str_LsqlI .= $separador . " G10_C85";
            $str_LsqlV .= $separador . $G10_C85;

            $validar = 1;
        } else {
            $separador = "";
            if ($validar == 1) {
                $separador = ",";
            }

            $str_LsqlU .= $separador . " G10_C85 = " . $G10_C85 . "";
            $str_LsqlI .= $separador . " G10_C85";
            $str_LsqlV .= $separador . $G10_C85;

            $validar = 1;
        }

        if (isset($_POST["G10_C90"])) {
            $separador = "";
            if ($validar == 1) {
                $separador = ",";
            }

            $str_LsqlU .= $separador . "G10_C90 = '" . $_POST["G10_C90"] . "'";
            $str_LsqlI .= $separador . "G10_C90";
            $str_LsqlV .= $separador . "'" . $_POST["G10_C90"] . "'";
            $validar = 1;
        }


        if (isset($_POST["G10_C91"])) {
            $separador = "";
            if ($validar == 1) {
                $separador = ",";
            }

            $str_LsqlU .= $separador . "G10_C91 = '" . $_POST["G10_C91"] . "'";
            $str_LsqlI .= $separador . "G10_C91";
            $str_LsqlV .= $separador . "'" . $_POST["G10_C91"] . "'";
            $validar = 1;
        }


        $G10_C92 = NULL;
        //este es de tipo numero no se deja ir asi '', si est avacio lo mejor es no mandarlo
        if (isset($_POST["G10_C92"])) {
            if ($_POST["G10_C92"] != '') {
                $separador = "";
                if ($validar == 1) {
                    $separador = ",";
                }

                //$G10_C92 = $_POST["G10_C92"];
                $G10_C92 = str_replace(".", "", $_POST["G10_C92"]);
                $G10_C92 = str_replace(",", ".", $G10_C92);
                $str_LsqlU .= $separador . " G10_C92 = '" . $G10_C92 . "'";
                $str_LsqlI .= $separador . " G10_C92";
                $str_LsqlV .= $separador . "'" . $G10_C92 . "'";
                $validar = 1;
            }
        }

        $G10_C93 = NULL;
        //este es de tipo numero no se deja ir asi '', si est avacio lo mejor es no mandarlo
        if (isset($_POST["G10_C93"])) {
            if ($_POST["G10_C93"] != '') {
                $separador = "";
                if ($validar == 1) {
                    $separador = ",";
                }

                //$G10_C93 = $_POST["G10_C93"];
                $G10_C93 = str_replace(".", "", $_POST["G10_C93"]);
                $G10_C93 = str_replace(",", ".", $G10_C93);
                $str_LsqlU .= $separador . " G10_C93 = '" . $G10_C93 . "'";
                $str_LsqlI .= $separador . " G10_C93";
                $str_LsqlV .= $separador . "'" . $G10_C93 . "'";
                $validar = 1;
            }
        }

        $G10_C94 = NULL;
        //este es de tipo numero no se deja ir asi '', si est avacio lo mejor es no mandarlo
        if (isset($_POST["G10_C94"])) {
            if ($_POST["G10_C94"] != '') {
                $separador = "";
                if ($validar == 1) {
                    $separador = ",";
                }

                //$G10_C94 = $_POST["G10_C94"];
                $G10_C94 = str_replace(".", "", $_POST["G10_C94"]);
                $G10_C94 = str_replace(",", ".", $G10_C94);
                $str_LsqlU .= $separador . " G10_C94 = '" . $G10_C94 . "'";
                $str_LsqlI .= $separador . " G10_C94";
                $str_LsqlV .= $separador . "'" . $G10_C94 . "'";
                $validar = 1;
            }
        }

        $G10_C95 = 0;
        //este es tipo check primeo si viene y de acuerdo a su valor se pone 1 o 0
        if (isset($_POST["G10_C95"])) {
            if ($_POST["G10_C95"] == 'Yes') {
                $G10_C95 = 1;
            } else if ($_POST["G10_C95"] == 'off') {
                $G10_C95 = 0;
            } else if ($_POST["G10_C95"] == 'on') {
                $G10_C95 = 1;
            } else if ($_POST["G10_C95"] == 'No') {
                $G10_C95 = 0;
            } else {
                $G10_C95 = $_POST["G10_C95"];
            }

            $separador = "";
            if ($validar == 1) {
                $separador = ",";
            }

            $str_LsqlU .= $separador . " G10_C95 = " . $G10_C95 . "";
            $str_LsqlI .= $separador . " G10_C95";
            $str_LsqlV .= $separador . $G10_C95;

            $validar = 1;
        } else {
            $separador = "";
            if ($validar == 1) {
                $separador = ",";
            }

            $str_LsqlU .= $separador . " G10_C95 = " . $G10_C95 . "";
            $str_LsqlI .= $separador . " G10_C95";
            $str_LsqlV .= $separador . $G10_C95;

            $validar = 1;
        }

        if (isset($_POST["G10_C98"])) {
            $separador = "";
            if ($validar == 1) {
                $separador = ",";
            }

            $str_LsqlU .= $separador . "G10_C98 = '" . $_POST["G10_C98"] . "'";
            $str_LsqlI .= $separador . "G10_C98";
            $str_LsqlV .= $separador . "'" . $_POST["G10_C98"] . "'";
            $validar = 1;
        }


        $G10_C99 = NULL;
        //este es de tipo numero no se deja ir asi '', si est avacio lo mejor es no mandarlo
        if (isset($_POST["G10_C99"])) {
            if ($_POST["G10_C99"] != '') {
                $separador = "";
                if ($validar == 1) {
                    $separador = ",";
                }

                //$G10_C99 = $_POST["G10_C99"];
                $G10_C99 = str_replace(".", "", $_POST["G10_C99"]);
                $G10_C99 = str_replace(",", ".", $G10_C99);
                $str_LsqlU .= $separador . " G10_C99 = '" . $G10_C99 . "'";
                $str_LsqlI .= $separador . " G10_C99";
                $str_LsqlV .= $separador . "'" . $G10_C99 . "'";
                $validar = 1;
            }
        }

        $G10_C100 = 0;
        //este es tipo check primeo si viene y de acuerdo a su valor se pone 1 o 0
        if (isset($_POST["G10_C100"])) {
            if ($_POST["G10_C100"] == 'Yes') {
                $G10_C100 = 1;
            } else if ($_POST["G10_C100"] == 'off') {
                $G10_C100 = 0;
            } else if ($_POST["G10_C100"] == 'on') {
                $G10_C100 = 1;
            } else if ($_POST["G10_C100"] == 'No') {
                $G10_C100 = 0;
            } else {
                $G10_C100 = $_POST["G10_C100"];
            }

            $separador = "";
            if ($validar == 1) {
                $separador = ",";
            }

            $str_LsqlU .= $separador . " G10_C100 = " . $G10_C100 . "";
            $str_LsqlI .= $separador . " G10_C100";
            $str_LsqlV .= $separador . $G10_C100;

            $validar = 1;
        } else {
            $separador = "";
            if ($validar == 1) {
                $separador = ",";
            }

            $str_LsqlU .= $separador . " G10_C100 = " . $G10_C100 . "";
            $str_LsqlI .= $separador . " G10_C100";
            $str_LsqlV .= $separador . $G10_C100;

            $validar = 1;
        }

        if (isset($_POST["G10_C101"])) {
            $separador = "";
            if ($validar == 1) {
                $separador = ",";
            }

            $str_LsqlU .= $separador . "G10_C101 = '" . $_POST["G10_C101"] . "'";
            $str_LsqlI .= $separador . "G10_C101";
            $str_LsqlV .= $separador . "'" . $_POST["G10_C101"] . "'";
            $validar = 1;
        }


        $G10_C102 = NULL;
        //este es de tipo numero no se deja ir asi '', si est avacio lo mejor es no mandarlo
        if (isset($_POST["G10_C102"])) {
            if ($_POST["G10_C102"] != '') {
                $separador = "";
                if ($validar == 1) {
                    $separador = ",";
                }

                //$G10_C102 = $_POST["G10_C102"];
                $G10_C102 = str_replace(".", "", $_POST["G10_C102"]);
                $G10_C102 = str_replace(",", ".", $G10_C102);
                $str_LsqlU .= $separador . " G10_C102 = '" . $G10_C102 . "'";
                $str_LsqlI .= $separador . " G10_C102";
                $str_LsqlV .= $separador . "'" . $G10_C102 . "'";
                $validar = 1;
            }
        }


        $G10_C333 = NULL;
        //este es de tipo numero no se deja ir asi '', si est avacio lo mejor es no mandarlo
        if (isset($_POST["G10_C333"])) {
            if ($_POST["G10_C333"] != '') {
                $separador = "";
                if ($validar == 1) {
                    $separador = ",";
                }

                //$G10_C333 = $_POST["G10_C333"];
                $G10_C333 = str_replace(".", "", $_POST["G10_C333"]);
                $G10_C333 = str_replace(",", ".", $G10_C333);
                $str_LsqlU .= $separador . " G10_C333 = '" . $G10_C333 . "'";
                $str_LsqlI .= $separador . " G10_C333";
                $str_LsqlV .= $separador . "'" . $G10_C333 . "'";
                $validar = 1;
            }
        }


        $G10_C334 = NULL;
        //este es de tipo numero no se deja ir asi '', si est avacio lo mejor es no mandarlo
        if (isset($_POST["G10_C334"])) {
            if ($_POST["G10_C334"] != '') {
                $separador = "";
                if ($validar == 1) {
                    $separador = ",";
                }

                //$G10_C334 = $_POST["G10_C334"];
                $G10_C334 = str_replace(".", "", $_POST["G10_C334"]);
                $G10_C334 = str_replace(",", ".", $G10_C334);
                $str_LsqlU .= $separador . " G10_C334 = '" . $G10_C334 . "'";
                $str_LsqlI .= $separador . " G10_C334";
                $str_LsqlV .= $separador . "'" . $G10_C334 . "'";
                $validar = 1;
            }
        }

        if (isset($_POST["G10_C103"])) {
            $separador = "";
            if ($validar == 1) {
                $separador = ",";
            }

            $str_LsqlU .= $separador . "G10_C103 = '" . $_POST["G10_C103"] . "'";
            $str_LsqlI .= $separador . "G10_C103";
            $str_LsqlV .= $separador . "'" . $_POST["G10_C103"] . "'";
            $validar = 1;
        }


        if (isset($_POST["G10_C104"])) {
            $separador = "";
            if ($validar == 1) {
                $separador = ",";
            }

            $str_LsqlU .= $separador . "G10_C104 = '" . $_POST["G10_C104"] . "'";
            $str_LsqlI .= $separador . "G10_C104";
            $str_LsqlV .= $separador . "'" . $_POST["G10_C104"] . "'";
            $validar = 1;
        }


        $G10_C108 = 0;
        //este es tipo check primeo si viene y de acuerdo a su valor se pone 1 o 0
        if (isset($_POST["G10_C108"])) {
            if ($_POST["G10_C108"] == 'Yes') {
                $G10_C108 = 1;
            } else if ($_POST["G10_C108"] == 'off') {
                $G10_C108 = 0;
            } else if ($_POST["G10_C108"] == 'on') {
                $G10_C108 = 1;
            } else if ($_POST["G10_C108"] == 'No') {
                $G10_C108 = 0;
            } else {
                $G10_C108 = $_POST["G10_C108"];
            }

            $separador = "";
            if ($validar == 1) {
                $separador = ",";
            }

            $str_LsqlU .= $separador . " G10_C108 = " . $G10_C108 . "";
            $str_LsqlI .= $separador . " G10_C108";
            $str_LsqlV .= $separador . $G10_C108;

            $validar = 1;
        } else {
            $separador = "";
            if ($validar == 1) {
                $separador = ",";
            }

            $str_LsqlU .= $separador . " G10_C108 = " . $G10_C108 . "";
            $str_LsqlI .= $separador . " G10_C108";
            $str_LsqlV .= $separador . $G10_C108;

            $validar = 1;
        }

        $G10_C109 = NULL;
        //este es de tipo date hay que preguntar si esta vacia o no o esta undefined
        if (isset($_POST["G10_C109"])) {
            if ($_POST["G10_C109"] != '' && $_POST["G10_C109"] != 'undefined' && $_POST["G10_C109"] != 'null') {
                $separador = "";
                $fecha = date('Y-m-d');
                if ($validar == 1) {
                    $separador = ",";
                }

                $G10_C109 = "'" . $fecha . " " . str_replace(' ', '', $_POST["G10_C109"]) . "'";
                $str_LsqlU .= $separador . " G10_C109 = " . $G10_C109 . "";
                $str_LsqlI .= $separador . " G10_C109";
                $str_LsqlV .= $separador . $G10_C109;
                $validar = 1;
            }
        }

        $G10_C110 = NULL;
        //este es de tipo date hay que preguntar si esta vacia o no o esta undefined
        if (isset($_POST["G10_C110"])) {
            if ($_POST["G10_C110"] != '' && $_POST["G10_C110"] != 'undefined' && $_POST["G10_C110"] != 'null') {
                $separador = "";
                $fecha = date('Y-m-d');
                if ($validar == 1) {
                    $separador = ",";
                }

                $G10_C110 = "'" . $fecha . " " . str_replace(' ', '', $_POST["G10_C110"]) . "'";
                $str_LsqlU .= $separador . " G10_C110 = " . $G10_C110 . "";
                $str_LsqlI .= $separador . " G10_C110";
                $str_LsqlV .= $separador . $G10_C110;
                $validar = 1;
            }
        }

        $G10_C111 = 0;
        //este es tipo check primeo si viene y de acuerdo a su valor se pone 1 o 0
        if (isset($_POST["G10_C111"])) {
            if ($_POST["G10_C111"] == 'Yes') {
                $G10_C111 = 1;
            } else if ($_POST["G10_C111"] == 'off') {
                $G10_C111 = 0;
            } else if ($_POST["G10_C111"] == 'on') {
                $G10_C111 = 1;
            } else if ($_POST["G10_C111"] == 'No') {
                $G10_C111 = 0;
            } else {
                $G10_C111 = $_POST["G10_C111"];
            }

            $separador = "";
            if ($validar == 1) {
                $separador = ",";
            }

            $str_LsqlU .= $separador . " G10_C111 = " . $G10_C111 . "";
            $str_LsqlI .= $separador . " G10_C111";
            $str_LsqlV .= $separador . $G10_C111;

            $validar = 1;
        } else {
            $separador = "";
            if ($validar == 1) {
                $separador = ",";
            }

            $str_LsqlU .= $separador . " G10_C111 = " . $G10_C111 . "";
            $str_LsqlI .= $separador . " G10_C111";
            $str_LsqlV .= $separador . $G10_C111;

            $validar = 1;
        }

        $G10_C112 = NULL;
        //este es de tipo date hay que preguntar si esta vacia o no o esta undefined
        if (isset($_POST["G10_C112"])) {
            if ($_POST["G10_C112"] != '' && $_POST["G10_C112"] != 'undefined' && $_POST["G10_C112"] != 'null') {
                $separador = "";
                $fecha = date('Y-m-d');
                if ($validar == 1) {
                    $separador = ",";
                }

                $G10_C112 = "'" . $fecha . " " . str_replace(' ', '', $_POST["G10_C112"]) . "'";
                $str_LsqlU .= $separador . " G10_C112 = " . $G10_C112 . "";
                $str_LsqlI .= $separador . " G10_C112";
                $str_LsqlV .= $separador . $G10_C112;
                $validar = 1;
            }
        }

        $G10_C113 = NULL;
        //este es de tipo date hay que preguntar si esta vacia o no o esta undefined
        if (isset($_POST["G10_C113"])) {
            if ($_POST["G10_C113"] != '' && $_POST["G10_C113"] != 'undefined' && $_POST["G10_C113"] != 'null') {
                $separador = "";
                $fecha = date('Y-m-d');
                if ($validar == 1) {
                    $separador = ",";
                }

                $G10_C113 = "'" . $fecha . " " . str_replace(' ', '', $_POST["G10_C113"]) . "'";
                $str_LsqlU .= $separador . " G10_C113 = " . $G10_C113 . "";
                $str_LsqlI .= $separador . " G10_C113";
                $str_LsqlV .= $separador . $G10_C113;
                $validar = 1;
            }
        }

        $G10_C114 = 0;
        //este es tipo check primeo si viene y de acuerdo a su valor se pone 1 o 0
        if (isset($_POST["G10_C114"])) {
            if ($_POST["G10_C114"] == 'Yes') {
                $G10_C114 = 1;
            } else if ($_POST["G10_C114"] == 'off') {
                $G10_C114 = 0;
            } else if ($_POST["G10_C114"] == 'on') {
                $G10_C114 = 1;
            } else if ($_POST["G10_C114"] == 'No') {
                $G10_C114 = 0;
            } else {
                $G10_C114 = $_POST["G10_C114"];
            }

            $separador = "";
            if ($validar == 1) {
                $separador = ",";
            }

            $str_LsqlU .= $separador . " G10_C114 = " . $G10_C114 . "";
            $str_LsqlI .= $separador . " G10_C114";
            $str_LsqlV .= $separador . $G10_C114;

            $validar = 1;
        } else {
            $separador = "";
            if ($validar == 1) {
                $separador = ",";
            }

            $str_LsqlU .= $separador . " G10_C114 = " . $G10_C114 . "";
            $str_LsqlI .= $separador . " G10_C114";
            $str_LsqlV .= $separador . $G10_C114;

            $validar = 1;
        }

        $G10_C115 = NULL;
        //este es de tipo date hay que preguntar si esta vacia o no o esta undefined
        if (isset($_POST["G10_C115"])) {
            if ($_POST["G10_C115"] != '' && $_POST["G10_C115"] != 'undefined' && $_POST["G10_C115"] != 'null') {
                $separador = "";
                $fecha = date('Y-m-d');
                if ($validar == 1) {
                    $separador = ",";
                }

                $G10_C115 = "'" . $fecha . " " . str_replace(' ', '', $_POST["G10_C115"]) . "'";
                $str_LsqlU .= $separador . " G10_C115 = " . $G10_C115 . "";
                $str_LsqlI .= $separador . " G10_C115";
                $str_LsqlV .= $separador . $G10_C115;
                $validar = 1;
            }
        }

        $G10_C116 = NULL;
        //este es de tipo date hay que preguntar si esta vacia o no o esta undefined
        if (isset($_POST["G10_C116"])) {
            if ($_POST["G10_C116"] != '' && $_POST["G10_C116"] != 'undefined' && $_POST["G10_C116"] != 'null') {
                $separador = "";
                $fecha = date('Y-m-d');
                if ($validar == 1) {
                    $separador = ",";
                }

                $G10_C116 = "'" . $fecha . " " . str_replace(' ', '', $_POST["G10_C116"]) . "'";
                $str_LsqlU .= $separador . " G10_C116 = " . $G10_C116 . "";
                $str_LsqlI .= $separador . " G10_C116";
                $str_LsqlV .= $separador . $G10_C116;
                $validar = 1;
            }
        }

        $G10_C117 = 0;
        //este es tipo check primeo si viene y de acuerdo a su valor se pone 1 o 0
        if (isset($_POST["G10_C117"])) {
            if ($_POST["G10_C117"] == 'Yes') {
                $G10_C117 = 1;
            } else if ($_POST["G10_C117"] == 'off') {
                $G10_C117 = 0;
            } else if ($_POST["G10_C117"] == 'on') {
                $G10_C117 = 1;
            } else if ($_POST["G10_C117"] == 'No') {
                $G10_C117 = 0;
            } else {
                $G10_C117 = $_POST["G10_C117"];
            }

            $separador = "";
            if ($validar == 1) {
                $separador = ",";
            }

            $str_LsqlU .= $separador . " G10_C117 = " . $G10_C117 . "";
            $str_LsqlI .= $separador . " G10_C117";
            $str_LsqlV .= $separador . $G10_C117;

            $validar = 1;
        } else {
            $separador = "";
            if ($validar == 1) {
                $separador = ",";
            }

            $str_LsqlU .= $separador . " G10_C117 = " . $G10_C117 . "";
            $str_LsqlI .= $separador . " G10_C117";
            $str_LsqlV .= $separador . $G10_C117;

            $validar = 1;
        }

        $G10_C118 = NULL;
        //este es de tipo date hay que preguntar si esta vacia o no o esta undefined
        if (isset($_POST["G10_C118"])) {
            if ($_POST["G10_C118"] != '' && $_POST["G10_C118"] != 'undefined' && $_POST["G10_C118"] != 'null') {
                $separador = "";
                $fecha = date('Y-m-d');
                if ($validar == 1) {
                    $separador = ",";
                }

                $G10_C118 = "'" . $fecha . " " . str_replace(' ', '', $_POST["G10_C118"]) . "'";
                $str_LsqlU .= $separador . " G10_C118 = " . $G10_C118 . "";
                $str_LsqlI .= $separador . " G10_C118";
                $str_LsqlV .= $separador . $G10_C118;
                $validar = 1;
            }
        }

        if (isset($_POST["G10_C119"])) {
            $separador = "";
            $fecha = date('Y-m-d');
            if ($validar == 1) {
                $separador = ",";
            }

            $G10_C119 = "'" . $fecha . " " . str_replace(' ', '', $_POST["G10_C119"]) . "'";
            $str_LsqlU .= $separador . " G10_C119 = " . $G10_C119 . "";
            $str_LsqlI .= $separador . " G10_C119";
            $str_LsqlV .= $separador . $G10_C119;
            $validar = 1;
        }


        $G10_C120 = 0;
        //este es tipo check primeo si viene y de acuerdo a su valor se pone 1 o 0
        if (isset($_POST["G10_C120"])) {
            if ($_POST["G10_C120"] == 'Yes') {
                $G10_C120 = 1;
            } else if ($_POST["G10_C120"] == 'off') {
                $G10_C120 = 0;
            } else if ($_POST["G10_C120"] == 'on') {
                $G10_C120 = 1;
            } else if ($_POST["G10_C120"] == 'No') {
                $G10_C120 = 0;
            } else {
                $G10_C120 = $_POST["G10_C120"];
            }

            $separador = "";
            if ($validar == 1) {
                $separador = ",";
            }

            $str_LsqlU .= $separador . " G10_C120 = " . $G10_C120 . "";
            $str_LsqlI .= $separador . " G10_C120";
            $str_LsqlV .= $separador . $G10_C120;

            $validar = 1;
        } else {
            $separador = "";
            if ($validar == 1) {
                $separador = ",";
            }

            $str_LsqlU .= $separador . " G10_C120 = " . $G10_C120 . "";
            $str_LsqlI .= $separador . " G10_C120";
            $str_LsqlV .= $separador . $G10_C120;

            $validar = 1;
        }

        $G10_C121 = NULL;
        //este es de tipo date hay que preguntar si esta vacia o no o esta undefined
        if (isset($_POST["G10_C121"])) {
            if ($_POST["G10_C121"] != '' && $_POST["G10_C121"] != 'undefined' && $_POST["G10_C121"] != 'null') {
                $separador = "";
                $fecha = date('Y-m-d');
                if ($validar == 1) {
                    $separador = ",";
                }

                $G10_C121 = "'" . $fecha . " " . str_replace(' ', '', $_POST["G10_C121"]) . "'";
                $str_LsqlU .= $separador . " G10_C121 = " . $G10_C121 . "";
                $str_LsqlI .= $separador . " G10_C121";
                $str_LsqlV .= $separador . $G10_C121;
                $validar = 1;
            }
        }

        $G10_C122 = NULL;
        //este es de tipo date hay que preguntar si esta vacia o no o esta undefined
        if (isset($_POST["G10_C122"])) {
            if ($_POST["G10_C122"] != '' && $_POST["G10_C122"] != 'undefined' && $_POST["G10_C122"] != 'null') {
                $separador = "";
                $fecha = date('Y-m-d');
                if ($validar == 1) {
                    $separador = ",";
                }

                $G10_C122 = "'" . $fecha . " " . str_replace(' ', '', $_POST["G10_C122"]) . "'";
                $str_LsqlU .= $separador . " G10_C122 = " . $G10_C122 . "";
                $str_LsqlI .= $separador . " G10_C122";
                $str_LsqlV .= $separador . $G10_C122;
                $validar = 1;
            }
        }

        $G10_C123 = 0;
        //este es tipo check primeo si viene y de acuerdo a su valor se pone 1 o 0
        if (isset($_POST["G10_C123"])) {
            if ($_POST["G10_C123"] == 'Yes') {
                $G10_C123 = 1;
            } else if ($_POST["G10_C123"] == 'off') {
                $G10_C123 = 0;
            } else if ($_POST["G10_C123"] == 'on') {
                $G10_C123 = 1;
            } else if ($_POST["G10_C123"] == 'No') {
                $G10_C123 = 0;
            } else {
                $G10_C123 = $_POST["G10_C123"];
            }

            $separador = "";
            if ($validar == 1) {
                $separador = ",";
            }

            $str_LsqlU .= $separador . " G10_C123 = " . $G10_C123 . "";
            $str_LsqlI .= $separador . " G10_C123";
            $str_LsqlV .= $separador . $G10_C123;

            $validar = 1;
        } else {
            $separador = "";
            if ($validar == 1) {
                $separador = ",";
            }

            $str_LsqlU .= $separador . " G10_C123 = " . $G10_C123 . "";
            $str_LsqlI .= $separador . " G10_C123";
            $str_LsqlV .= $separador . $G10_C123;

            $validar = 1;
        }

        $G10_C124 = NULL;
        //este es de tipo date hay que preguntar si esta vacia o no o esta undefined
        if (isset($_POST["G10_C124"])) {
            if ($_POST["G10_C124"] != '' && $_POST["G10_C124"] != 'undefined' && $_POST["G10_C124"] != 'null') {
                $separador = "";
                $fecha = date('Y-m-d');
                if ($validar == 1) {
                    $separador = ",";
                }

                $G10_C124 = "'" . $fecha . " " . str_replace(' ', '', $_POST["G10_C124"]) . "'";
                $str_LsqlU .= $separador . " G10_C124 = " . $G10_C124 . "";
                $str_LsqlI .= $separador . " G10_C124";
                $str_LsqlV .= $separador . $G10_C124;
                $validar = 1;
            }
        }

        $G10_C125 = NULL;
        //este es de tipo date hay que preguntar si esta vacia o no o esta undefined
        if (isset($_POST["G10_C125"])) {
            if ($_POST["G10_C125"] != '' && $_POST["G10_C125"] != 'undefined' && $_POST["G10_C125"] != 'null') {
                $separador = "";
                $fecha = date('Y-m-d');
                if ($validar == 1) {
                    $separador = ",";
                }

                $G10_C125 = "'" . $fecha . " " . str_replace(' ', '', $_POST["G10_C125"]) . "'";
                $str_LsqlU .= $separador . " G10_C125 = " . $G10_C125 . "";
                $str_LsqlI .= $separador . " G10_C125";
                $str_LsqlV .= $separador . $G10_C125;
                $validar = 1;
            }
        }

        $G10_C126 = 0;
        //este es tipo check primeo si viene y de acuerdo a su valor se pone 1 o 0
        if (isset($_POST["G10_C126"])) {
            if ($_POST["G10_C126"] == 'Yes') {
                $G10_C126 = 1;
            } else if ($_POST["G10_C126"] == 'off') {
                $G10_C126 = 0;
            } else if ($_POST["G10_C126"] == 'on') {
                $G10_C126 = 1;
            } else if ($_POST["G10_C126"] == 'No') {
                $G10_C126 = 0;
            } else {
                $G10_C126 = $_POST["G10_C126"];
            }

            $separador = "";
            if ($validar == 1) {
                $separador = ",";
            }

            $str_LsqlU .= $separador . " G10_C126 = " . $G10_C126 . "";
            $str_LsqlI .= $separador . " G10_C126";
            $str_LsqlV .= $separador . $G10_C126;

            $validar = 1;
        } else {
            $separador = "";
            if ($validar == 1) {
                $separador = ",";
            }

            $str_LsqlU .= $separador . " G10_C126 = " . $G10_C126 . "";
            $str_LsqlI .= $separador . " G10_C126";
            $str_LsqlV .= $separador . $G10_C126;

            $validar = 1;
        }

        $G10_C127 = NULL;
        //este es de tipo date hay que preguntar si esta vacia o no o esta undefined
        if (isset($_POST["G10_C127"])) {
            if ($_POST["G10_C127"] != '' && $_POST["G10_C127"] != 'undefined' && $_POST["G10_C127"] != 'null') {
                $separador = "";
                $fecha = date('Y-m-d');
                if ($validar == 1) {
                    $separador = ",";
                }

                $G10_C127 = "'" . $fecha . " " . str_replace(' ', '', $_POST["G10_C127"]) . "'";
                $str_LsqlU .= $separador . " G10_C127 = " . $G10_C127 . "";
                $str_LsqlI .= $separador . " G10_C127";
                $str_LsqlV .= $separador . $G10_C127;
                $validar = 1;
            }
        }

        $G10_C128 = NULL;
        //este es de tipo date hay que preguntar si esta vacia o no o esta undefined
        if (isset($_POST["G10_C128"])) {
            if ($_POST["G10_C128"] != '' && $_POST["G10_C128"] != 'undefined' && $_POST["G10_C128"] != 'null') {
                $separador = "";
                $fecha = date('Y-m-d');
                if ($validar == 1) {
                    $separador = ",";
                }

                $G10_C128 = "'" . $fecha . " " . str_replace(' ', '', $_POST["G10_C128"]) . "'";
                $str_LsqlU .= $separador . " G10_C128 = " . $G10_C128 . "";
                $str_LsqlI .= $separador . " G10_C128";
                $str_LsqlV .= $separador . $G10_C128;
                $validar = 1;
            }
        }

        $G10_C129 = 0;
        //este es tipo check primeo si viene y de acuerdo a su valor se pone 1 o 0
        if (isset($_POST["G10_C129"])) {
            if ($_POST["G10_C129"] == 'Yes') {
                $G10_C129 = 1;
            } else if ($_POST["G10_C129"] == 'off') {
                $G10_C129 = 0;
            } else if ($_POST["G10_C129"] == 'on') {
                $G10_C129 = 1;
            } else if ($_POST["G10_C129"] == 'No') {
                $G10_C129 = 0;
            } else {
                $G10_C129 = $_POST["G10_C129"];
            }

            $separador = "";
            if ($validar == 1) {
                $separador = ",";
            }

            $str_LsqlU .= $separador . " G10_C129 = " . $G10_C129 . "";
            $str_LsqlI .= $separador . " G10_C129";
            $str_LsqlV .= $separador . $G10_C129;

            $validar = 1;
        } else {
            $separador = "";
            if ($validar == 1) {
                $separador = ",";
            }

            $str_LsqlU .= $separador . " G10_C129 = " . $G10_C129 . "";
            $str_LsqlI .= $separador . " G10_C129";
            $str_LsqlV .= $separador . $G10_C129;

            $validar = 1;
        }

        $G10_C130 = NULL;
        //este es de tipo date hay que preguntar si esta vacia o no o esta undefined
        if (isset($_POST["G10_C130"])) {
            if ($_POST["G10_C130"] != '' && $_POST["G10_C130"] != 'undefined' && $_POST["G10_C130"] != 'null') {
                $separador = "";
                $fecha = date('Y-m-d');
                if ($validar == 1) {
                    $separador = ",";
                }

                $G10_C130 = "'" . $fecha . " " . str_replace(' ', '', $_POST["G10_C130"]) . "'";
                $str_LsqlU .= $separador . " G10_C130 = " . $G10_C130 . "";
                $str_LsqlI .= $separador . " G10_C130";
                $str_LsqlV .= $separador . $G10_C130;
                $validar = 1;
            }
        }

        $G10_C131 = NULL;
        //este es de tipo date hay que preguntar si esta vacia o no o esta undefined
        if (isset($_POST["G10_C131"])) {
            if ($_POST["G10_C131"] != '' && $_POST["G10_C131"] != 'undefined' && $_POST["G10_C131"] != 'null') {
                $separador = "";
                $fecha = date('Y-m-d');
                if ($validar == 1) {
                    $separador = ",";
                }

                $G10_C131 = "'" . $fecha . " " . str_replace(' ', '', $_POST["G10_C131"]) . "'";
                $str_LsqlU .= $separador . " G10_C131 = " . $G10_C131 . "";
                $str_LsqlI .= $separador . " G10_C131";
                $str_LsqlV .= $separador . $G10_C131;
                $validar = 1;
            }
        }

        if (isset($_POST['G10_C326'])) {
            if ($_POST["G10_C326"] != '') {
                $separador = "";
                if ($validar == 1) {
                    $separador = ",";
                }

                //$G10_C326 = $_POST["G10_C326"];
                $G10_C326 = str_replace(".", "", $_POST["G10_C326"]);
                $G10_C326 = str_replace(",", ".", $G10_C326);
                $str_LsqlU .= $separador . " G10_C326 = '" . $G10_C326 . "'";
                $str_LsqlI .= $separador . " G10_C326";
                $str_LsqlV .= $separador . "'" . $G10_C326 . "'";
                $validar = 1;
            }
        }


        //DLAB 20190715 - ROBOT - Se agrega el campo para colocar los canales del marcador robotico
        if (isset($_POST['G10_C331'])) {
            if ($_POST["G10_C331"] != '') {
                $separador = "";
                if ($validar == 1) {
                    $separador = ",";
                }

                $G10_C331 = str_replace(".", "", $_POST["G10_C331"]);
                $G10_C331 = str_replace(",", ".", $G10_C331);
                $str_LsqlU .= $separador . " G10_C331 = " . $G10_C331 . "";
                $str_LsqlI .= $separador . " G10_C331";
                $str_LsqlV .= $separador . $G10_C331 ;
                $validar = 1;
            }
        }
        
        if (isset($_POST["G10_C332"])) {
            $separador = "";
            if ($validar == 1) {
                $separador = ",";
            }

            $str_LsqlU .= $separador . "G10_C332 = '" . $_POST["G10_C332"] . "'";
            $str_LsqlI .= $separador . "G10_C32";
            $str_LsqlV .= $separador . "'" . $_POST["G10_C332"] . "'";
            $validar = 1;
            
            if($_POST['G10_C332'] !='-1' && isset($_POST["G10_C77"]) && $_POST["G10_C77"]=='-1'){
                $desAgendas ="SELECT CAMPAN_ConsInte__GUION__Pob_b, CAMPAN_ConsInte__GUION__Gui_b, CAMPAN_ConsInte__MUESTR_b FROM " . $BaseDatos_systema . ".CAMPAN WHERE CAMPAN_ConsInte__b = " . $_POST['id'];
                $desAgendas=$mysqli->query($desAgendas);
                if($desAgendas){
                    $des=$desAgendas->fetch_object();
                    $desasignado="UPDATE DYALOGOCRM_WEB.G".$des->CAMPAN_ConsInte__GUION__Pob_b."_M".$des->CAMPAN_ConsInte__MUESTR_b." SET G".$des->CAMPAN_ConsInte__GUION__Pob_b."_M".$des->CAMPAN_ConsInte__MUESTR_b."_ConIntUsu_b=NULL where G".$des->CAMPAN_ConsInte__GUION__Pob_b."_M".$des->CAMPAN_ConsInte__MUESTR_b."_Estado____b=2";
                    $desasignado=$mysqli->query($desasignado);
                }
            }
        }       

        $padre = NULL;
        //este es de tipo date hay que preguntar si esta vacia o no
        if (isset($_POST["padre"])) {
            if ($_POST["padre"] != '0' && $_POST['padre'] != '') {
                $separador = "";
                if ($validar == 1) {
                    $separador = ",";
                }

                //primero hay que ir y buscar los campos
                $str_Lsql = "SELECT GUIDET_ConsInte__PREGUN_De1_b FROM " . $BaseDatos_systema . ".GUIDET WHERE GUIDET_ConsInte__GUION__Mae_b = " . $_POST['formpadre'] . " AND GUIDET_ConsInte__GUION__Det_b = " . $_POST['formhijo'];

                $GuidRes = $mysqli->query($str_Lsql);
                $campo = null;
                while ($ky = $GuidRes->fetch_object()) {
                    $campo = $ky->GUIDET_ConsInte__PREGUN_De1_b;
                }
                $valorG = "G10_C";
                $valorH = $valorG . $campo;
                $str_LsqlU .= $separador . " " . $valorH . " = " . $_POST["padre"];
                $str_LsqlI .= $separador . " " . $valorH;
                $str_LsqlV .= $separador . $_POST['padre'];
                $validar = 1;
            }
        }

        if (isset($_GET['id_gestion_cbx'])) {
            $separador = "";
            if ($validar == 1) {
                $separador = ",";
            }

            $str_LsqlU .= $separador . "G10_IdLlamada = '" . $_GET['id_gestion_cbx'] . "'";
            $str_LsqlI .= $separador . "G10_IdLlamada";
            $str_LsqlV .= $separador . "'" . $_GET['id_gestion_cbx'] . "'";
            $validar = 1;
        }



        if (isset($_POST['oper'])) {
            if ($_POST["oper"] == 'add') {
                /// $str_Lsql = $str_LsqlI." , G10_C75)" . $str_LsqlV.", ".$id_Muestras.")";
            } else if ($_POST["oper"] == 'edit') {
                $str_Lsql = $str_LsqlU . " WHERE G10_ConsInte__b =" . $_POST["id"];
            } else if ($_POST["oper"] == 'del') {
                $str_Lsql = "DELETE FROM " . $BaseDatos_systema . ".G10 WHERE G10_ConsInte__b = " . $_POST['id'];
                $validar = 1;
            }
        }

        //si trae algo que insertar inserta
        //echo $str_Lsql;
        if ($validar == 1 && $_POST['oper'] != 'add') {
            if ($mysqli->query($str_Lsql) === TRUE) {
                $id_usuario = $mysqli->insert_id;
                if ($_POST["oper"] == 'add') {
                    
                } else if ($_POST["oper"] == 'edit') {

                    //GUARDAR LOS PERMISOS DE BUSQUEDA
                    if(isset($_POST['insertRegistro'])){
                        $insertAuto=isset($_POST['insertAuto']) ? $_POST['insertAuto'] : 0;
                        $strSQLTipoBusMa="UPDATE {$BaseDatos_systema}.GUION_ SET GUION_PERMITEINSERTAR_b={$_POST['insertRegistro']}, GUION_INSERTAUTO_b={$insertAuto} WHERE GUION__ConsInte__b={$_POST['poblacion']}";
                        $strSQLTipoBusMa=$mysqli->query($strSQLTipoBusMa);
                    }

                    //GENERAR EL FORMULARIO DE BÚSQUEDA DE LA BD DE LA CAMPAÑA
                    if(isset($_POST['checkBusqueda'])){
                        $strSQLTipoBusMa="UPDATE {$BaseDatos_systema}.GUION_ SET GUION__TipoBusqu__Manual_b={$_POST['TipoBusqManual']} WHERE GUION__ConsInte__b={$_POST['poblacion']}";
                        $strSQLTipoBusMa=$mysqli->query($strSQLTipoBusMa);
                        generar_Busqueda_Manual($_POST['poblacion']);
                        generar_Busqueda_Dato_Adicional($_POST['poblacion']);
                        generar_Busqueda_Ani($_POST['poblacion']);
                    }

                    // Valido que almenos traiga el check
                    $checkActivoCampana = $_POST["G10_C72"] ?? 0;

                    $strSQLUpdateEstpas_t = "UPDATE ".$BaseDatos_systema.".ESTPAS SET ESTPAS_Comentari_b = '".$_POST["G10_C71"]."', ESTPAS_activo = ".$checkActivoCampana." WHERE ESTPAS_ConsInte__CAMPAN_b = ".$_POST["id"];

                    $mysqli->query($strSQLUpdateEstpas_t);

                    // Insertamos el campo tiempoInabilitadoBotonColgado
                    $strUpdateCampana = "UPDATE {$BaseDatos_systema}.CAMPAN SET CAMPAN_TiemMinCol_b  = ".$_POST['tiempoInabilitadoBotonColgado']." WHERE CAMPAN_ConsInte__b = ". $_POST['id'];
                    $mysqli->query($strUpdateCampana);

                    /* como cuando llega aca siempre sera para ctualizar */
                    //$carpeta = "/var/www/html/crm_php/formularios/G".$_POST['G10_C74'];
                    //if (!file_exists($carpeta)) {
                    //  echo "No existe";
                    //invocarCrm_CrearScripts($_POST["id"]);
                    //}//

                    $data = array(
                        "strUsuario_t" => 'local',
                        "strToken_t" => 'local',
                        "intIdESTPAS_t" => $_POST['id_estpas']
                    );
                    $data_string = json_encode($data);
                    echo $data_string;

                    $ch = curl_init($Api_Gestion . 'dyalogocore/api/campanas/voip/persistir');
                    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
                    curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                    curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                        'Content-Type: application/json',
                        'Content-Length: ' . strlen($data_string))
                    );
                    $respuesta = curl_exec($ch);
                    $error = curl_error($ch);
                    curl_close($ch);
                    echo " Respuesta => " . $respuesta;
                    echo " Error => " . $error;
                    if (!empty($respuesta) && !is_null($respuesta)) {
                        $json = json_decode($respuesta);

                        if ($json->strEstado_t == "ok") {
                            //en caso de que sea extoso

                            /* $UpdateSqlCampanCBX = "UPDATE ".$BaseDatos_systema.".CAMPAN SET CAMPAN_IdCamCbx__b = ".$json->objSerializar_t ." WHERE CAMPAN_ConsInte__b = ".$id_usuario;
                              if($mysqli->query($UpdateSqlCampanCBX) === true){
                              //si actualizo esta jugada bienvenido sea
                              } */
                        }
                    }

                    /* ahora toca crear y editar lso lisopc , primero es validar que venga algo que editar */
                    $ultimoLista = 0;
                    if (isset($_POST['idLisop'])) {

                        //var_dump($_POST['idLisop']);
                        /* vienen a edicion */
                        foreach ($_POST['idLisop'] as $key) {
                            $estados = 0;
                            if (isset($_POST['estado_' . $key])) {
                                $estados = $_POST['estado_' . $key];
                            }
                            $insertLisopc = "UPDATE " . $BaseDatos_systema . ".LISOPC  SET LISOPC_Nombre____b = '" . $_POST['opciones_' . $key] . "' , LISOPC_Valor____b = '" . $estados . "' WHERE LISOPC_ConsInte__b = " . $key;
                            if ($mysqli->query($insertLisopc) === true) {
                                
                            } else {
                                echo 'Error Actualizando las tipificaciones ' . $mysqli->error;
                            }

                            $Lip = "SELECT LISOPC_Clasifica_b , LISOPC_ConsInte__OPCION_b FROM " . $BaseDatos_systema . ".LISOPC WHERE LISOPC_ConsInte__b = " . $key;
                            // echo $Lip;
                            $res = $mysqli->query($Lip);
                            $data = $res->fetch_array();

                            $ultimoLista = $data['LISOPC_ConsInte__OPCION_b'];
                            //, MONOEF_Importanc_b = ".$_POST['inportancia_'.$key]."
                            $MONOEFLsql = "UPDATE " . $BaseDatos_systema . ".MONOEF SET MONOEF_Texto_____b = '" . $_POST['opciones_' . $key] . "', MONOEF_TipNo_Efe_b = " . $_POST['Tip_NoEfe_' . $key] . ", MONOEF_Contacto__b=" . $_POST['contacto_' . $key] . " , MONOEF_TipiCBX___b = " . $estados . " , MONOEF_CanHorProxGes__b=" . $_POST['txtHorasMas_' . $key] . " WHERE MONOEF_ConsInte__b = " . $data ['LISOPC_Clasifica_b'];

                            if ($mysqli->query($MONOEFLsql) === true) {
                                //$monoefNew = $mysqli->insert_id;
                                /* ahora si lo insertamos en el LISOPC */
                            } else {
                                echo 'Error actualizando el monoef ' . $mysqli->error;
                            }
                        }
                    }

                    //metemos los nuevos
                    if (isset($_POST['contador'])) {
                        $cuantoshay = 0;
                        for ($i = 0; $i < $_POST['contador']; $i++) {

                            if (isset($_POST['opciones_' . $i])) {
                                $cuantoshay++;
                                $importancia = 0;
                                if (isset($_POST['efectividad_' . $i])) {
                                    $importancia = -1;
                                }
                                //, '".$_POST['inportancia_'.$i]."'
                                //MONOEF_Importanc_b,
                                $estados = 0;
                                if (isset($_POST['estado_' . $i])) {
                                    $estados = $_POST['estado_' . $i];
                                }

                                $MONOEFLsql = "INSERT INTO " . $BaseDatos_systema . ".MONOEF (MONOEF_Texto_____b, MONOEF_EFECTIVA__B, MONOEF_TipNo_Efe_b,  MONOEF_FechCrea__b, MONOEF_UsuaCrea__b, MONOEF_Contacto__b, MONOEF_TipiCBX___b, MONOEF_CanHorProxGes__b) VALUES ('" . $_POST['opciones_' . $i] . "','" . $importancia . "' , '" . $_POST['Tip_NoEfe_' . $i] . "', '" . date('Y-m-d H:s:i') . "' , " . $_SESSION['IDENTIFICACION'] . " , '" . $_POST['contacto_' . $i] . "' , '" . $estados . "' , " . $_POST['txtHorasMas_' . $i] . ")";

                                if ($mysqli->query($MONOEFLsql) === true) {
                                    $monoefNew = $mysqli->insert_id;
                                    /* ahora si lo insertamos en el LISOPC */

                                    if (isset($_POST['IdListaMia'])) {
                                        $insertLisopc = "INSERT INTO " . $BaseDatos_systema . ".LISOPC (LISOPC_Nombre____b, LISOPC_ConsInte__OPCION_b, LISOPC_Posicion__b, LISOPC_Clasifica_b, LISOPC_Valor____b ) VALUES ('" . $_POST['opciones_' . $i] . "', " . $_POST['IdListaMia'] . ", 0, " . $monoefNew . " , '" . $estados . "' );";

                                        if ($mysqli->query($insertLisopc) === true) {
                                            //$correcto++;
                                        } else {
                                            echo "Cagada en LISOPC => " . $mysqli->error;
                                        }
                                    } else {
                                        /* No tenemos la puta lista creada */
                                    }
                                } else {
                                    echo $mysqli->error;
                                }
                            } else {
                                echo "No existe";
                            }
                        }
                    }

                    if (isset($_POST['contadorASociaciones']) && $_POST['contadorASociaciones'] != 0) {
                        //Guardamos las asociaciones de campos
                        $csql = "SELECT CAMPAN_ConsInte__GUION__Pob_b, CAMPAN_ConsInte__GUION__Gui_b, CAMPAN_ConsInte__MUESTR_b FROM " . $BaseDatos_systema . ".CAMPAN WHERE CAMPAN_ConsInte__b = " . $_POST['id'];
                        $res = $mysqli->query($csql);
                        $dataCam = $res->fetch_array();

                        for ($i = 0; $i < $_POST['contadorASociaciones']; $i++) {

                            if (isset($_POST['asocioa_' . $i]) && $_POST['asocioa_' . $i] != '0') {

                                if (isset($_POST['asocioaG_' . $i]) && $_POST['asocioaG_' . $i] != '0') {

                                    if (is_numeric($_POST['asocioa_'.$i])) {

                                        $CAMINC_NomCamPob_b = "G".$dataCam['CAMPAN_ConsInte__GUION__Pob_b']."_C".$_POST['asocioa_'.$i];
                                        $CAMINC_ConsInte__CAMPO_Pob_b = $_POST['asocioa_'.$i];

                                    }else{

                                        if (strpos($_POST['asocioa_'.$i], "_ConsInte__b")) {

                                            $CAMINC_ConsInte__CAMPO_Pob_b = "-1";
                                            $CAMINC_NomCamPob_b = "G".$dataCam['CAMPAN_ConsInte__GUION__Pob_b']."_ConsInte__b";

                                        }else if (strpos($_POST['asocioa_'.$i], "_FechaInsercion")) {

                                            $CAMINC_ConsInte__CAMPO_Pob_b = "-2";
                                            $CAMINC_NomCamPob_b = "G".$dataCam['CAMPAN_ConsInte__GUION__Pob_b']."_FechaInsercion";

                                        }


                                    }

                                }

                            }
                            
                            if (isset($_POST['asocioa_' . $i]) && $_POST['asocioa_' . $i] != '0') {
                                if (isset($_POST['asocioaG_' . $i]) && $_POST['asocioaG_' . $i] != '0') {


                                    $strTextoPo_t=substr(getTextoPregun($_POST['asocioa_' . $i]), 0,250);
                                    $strTextoGion_t=substr(getTextoPregun($_POST['asocioaG_' . $i]), 0,250);

                                    $CamincLsql = "INSERT INTO " . $BaseDatos_systema . ".CAMINC(CAMINC_ConsInte__CAMPO_Pob_b, CAMINC_NomCamPob_b , CAMINC_TexPrePob_b , CAMINC_ConsInte__CAMPO_Gui_b,CAMINC_NomCamGui_b , CAMINC_TexPreGui_b, CAMINC_ConsInte__CAMPAN_b, CAMINC_ConsInte__GUION__Pob_b, CAMINC_ConsInte__GUION__Gui_b, CAMINC_ConsInte__MUESTR_b ) VALUES (".$CAMINC_ConsInte__CAMPO_Pob_b.", '".$CAMINC_NomCamPob_b."' ,  '" . $strTextoPo_t . "' , " . $_POST['asocioaG_' . $i] . " , 'G" . $dataCam['CAMPAN_ConsInte__GUION__Gui_b'] . "_C" . $_POST['asocioaG_' . $i] . "' , '" . $strTextoGion_t . "' , " . $_POST['id'] . " , '" . $dataCam['CAMPAN_ConsInte__GUION__Pob_b'] . "' , '" . $dataCam['CAMPAN_ConsInte__GUION__Gui_b'] . "' , " . $dataCam['CAMPAN_ConsInte__MUESTR_b'] . ")";

                                    if ($mysqli->query($CamincLsql) === true) {

                                        
                                    } else {
                                        echo "ERROR CAMINC" . $mysqli->error;
                                    }
                                }
                            }

                            if (isset($_POST['idCamincYa_' . $i]) && $_POST['idCamincYa_' . $i] != '0') {
                                if (isset($_POST['datosPob_' . $i]) && $_POST['datosPob_' . $i] != '0') {
                                    if (isset($_POST['datosGui_' . $i]) && $_POST['datosGui_' . $i] != '0') {

                                        $CamincLsql = "UPDATE " . $BaseDatos_systema . ".CAMINC SET CAMINC_ConsInte__CAMPO_Pob_b = " . $CAMINC_ConsInte__CAMPO_Pob_b . " , CAMINC_NomCamPob_b = '".$CAMINC_NomCamPob_b."' , CAMINC_TexPrePob_b = '" . getTextoPregun($_POST['asocioa_' . $i]) . "' , CAMINC_ConsInte__CAMPO_Gui_b =  " . $_POST['asocioaG_' . $i] . " , CAMINC_NomCamGui_b = 'G" . $dataCam['CAMPAN_ConsInte__GUION__Gui_b'] . "_C" . $_POST['asocioaG_' . $i] . "' , CAMINC_TexPreGui_b = '" . getTextoPregun($_POST['asocioaG_' . $i]) . "' , CAMINC_ConsInte__CAMPAN_b = " . $_POST['id'] . " , CAMINC_ConsInte__GUION__Pob_b = '" . $dataCam['CAMPAN_ConsInte__GUION__Pob_b'] . "' , CAMINC_ConsInte__GUION__Gui_b = '" . $dataCam['CAMPAN_ConsInte__GUION__Gui_b'] . "' ,  CAMINC_ConsInte__MUESTR_b = " . $dataCam['CAMPAN_ConsInte__GUION__Gui_b'] . " WHERE CAMINC_ConsInte__b = " . $_POST['idCamincYa_' . $i];

                                        if ($mysqli->query($CamincLsql) === true) {
                                            
                                        } else {
                                            echo "ERROR CAMINC" . $mysqli->error;
                                        }
                                    }
                                }
                            }
                        }
                    }

                    if (isset($_POST['G10_C76']) && $_POST['G10_C76'] == '6') {
                        $data = array(
                            "strUsuario_t" => 'crm',
                            "strToken_t" => 'D43dasd321',
                            "intIdESTPAS_t" => $_POST['id_estpas']
                        );
                        $data_string = json_encode($data);
                        echo $data_string;

                        $ch = curl_init($Api_Gestion . 'dyalogocore/api/campanas/voip/sincronizar');
                        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
                        curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
                        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
                        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                            'Content-Type: application/json',
                            'Content-Length: ' . strlen($data_string))
                        );
                        $respuesta = curl_exec($ch);
                        $error = curl_error($ch);
                        curl_close($ch);
                        echo " Respuesta => " . $respuesta;
                        echo " Error => " . $error;
                        if (!empty($respuesta) && !is_null($respuesta)) {
                            $json = json_decode($respuesta);
                        }
                    }


                    /* le asignamos la base de datos a la estartegia */
                    $PasoLsql = "SELECT ESTPAS_ConsInte__ESTRAT_b FROM " . $BaseDatos_systema . ".ESTPAS WHERE ESTPAS_ConsInte__b = " . $_POST['id_paso'];
                    $resPAso = $mysqli->query($PasoLsql);
                    $Estrat = $resPAso->fetch_array();


                    $UpdatePass = "UPDATE " . $BaseDatos_systema . ".ESTPAS SET ESTPAS_Comentari_b = '" . $_POST['G10_C71'] . "' WHERE ESTPAS_ConsInte__CAMPAN_b = " . $_POST["id"];

                    if ($mysqli->query($UpdatePass) === true) {
                        
                    } else {
                        echo "error ESTPAS => " . $mysqli->error;
                    }

                    guardar_auditoria("ACTUALIZAR", "ACTUALIZO EL REGISTRO # " . $_POST["id"] . " EN G10");
                } else if ($_POST["oper"] == 'del') {
                    guardar_auditoria("ELIMINAR", "ELIMINO EL REGISTRO # " . $_POST['id'] . " EN G10");
                }

                echo $id_usuario;
            } else {
                echo "Error Hacieno el proceso los registros sQL DE INSERTAR: " . $mysqli->error;
            }
        }else{
            if(isset($_POST['byCallback'])){
                echo json_encode(array('id'=>$id_usuario,'estpas'=>md5(clave_get . $_POST['id_paso'])));
            }
        }

        refrescarCacheDistribuidor();
    }

    //Mostrar datos de por donde salen las llamadas
    if (isset($_GET["callDatosSubgrilla_0"])) {

        $id = $_GET['id'];
        $numero = $id;

        $SQL = "SELECT pasos_troncales.id as id ,tipos_destino.nombre as tipos_destino, a.nombre_usuario as troncal, b.nombre_usuario as troncal_desborde";
        $SQL .= " FROM " . $BaseDatos_telefonia . ".pasos_troncales ";
        $SQL .= " LEFT JOIN " . $BaseDatos_telefonia . ".tipos_destino ON tipos_destino.id = pasos_troncales.id_tipos_destino ";
        $SQL .= " LEFT JOIN " . $BaseDatos_telefonia . ".dy_troncales a ON a.id = pasos_troncales.id_troncal ";
        $SQL .= " LEFT JOIN " . $BaseDatos_telefonia . ".dy_troncales b ON b.id = pasos_troncales.id_troncal_desborde ";
        $SQL .= " WHERE pasos_troncales.id_campana = " . $numero;
        $SQL .= " ORDER BY tipos_destino.nombre";

        //  echo $SQL;
        if (stristr($_SERVER["HTTP_ACCEPT"], "application/xhtml+xml")) {
            header("Content-type: application/xhtml+xml;charset=utf-8");
        } else {
            header("Content-type: text/xml;charset=utf-8");
        }

        $et = ">";
        echo "<?xml version='1.0' encoding='utf-8'?$et\n";
        echo "<rows>"; // be sure to put text data in CDATA

        $result = $mysqli->query($SQL);
        while ($fila = $result->fetch_object()) {
            echo "<row asin='" . $fila->id . "'>";
            echo "<cell>" . ($fila->id) . "</cell>";
            echo "<cell>" . ($fila->tipos_destino) . "</cell>";
            echo "<cell>" . ($fila->troncal) . "</cell>";
            echo "<cell>" . ($fila->troncal_desborde) . "</cell>";
            echo "</row>";
        }
        echo "</rows>";
    }

    //Llenar datos de por donde salen las llamadas
    if (isset($_GET["insertarDatosSubgrilla_0"])) {

        if (isset($_POST["oper"])) {
            $Lsql = '';

            $validar = 0;
            $LsqlU = "UPDATE " . $BaseDatos_telefonia . ".pasos_troncales SET ";
            $LsqlI = "INSERT INTO " . $BaseDatos_telefonia . ".pasos_troncales(";
            $LsqlV = " VALUES (";


            if (isset($_POST["tipos_destino"])) {
                if ($_POST["tipos_destino"] != '0') {
                    $separador = "";
                    if ($validar == 1) {
                        $separador = ",";
                    }

                    $LsqlU .= $separador . "id_tipos_destino = '" . $_POST["tipos_destino"] . "'";
                    $LsqlI .= $separador . "id_tipos_destino";
                    $LsqlV .= $separador . "'" . $_POST["tipos_destino"] . "'";
                    $validar = 1;
                }
            }



            $troncal = NULL;
            //este es de tipo numero no se deja ir asi '', si est avacio lo mejor es no mandarlo
            if (isset($_POST["troncal"])) {
                if ($_POST["troncal"] != '0') {
                    $separador = "";
                    if ($validar == 1) {
                        $separador = ",";
                    }

                    $troncal = $_POST["troncal"];
                    $LsqlU .= $separador . "id_troncal = '" . $troncal . "'";
                    $LsqlI .= $separador . "id_troncal";
                    $LsqlV .= $separador . "'" . $troncal . "'";
                    $validar = 1;
                }
            }

            $troncal_desborde = NULL;
            //este es de tipo numero no se deja ir asi '', si est avacio lo mejor es no mandarlo
            if (isset($_POST["troncal_desborde"])) {
                if ($_POST["troncal_desborde"] != '0') {
                    $separador = "";
                    if ($validar == 1) {
                        $separador = ",";
                    }

                    $troncal_desborde = $_POST["troncal_desborde"];
                    $LsqlU .= $separador . " id_troncal_desborde = '" . $troncal_desborde . "'";
                    $LsqlI .= $separador . " id_troncal_desborde";
                    $LsqlV .= $separador . "'" . $troncal_desborde . "'";
                    $validar = 1;
                }
            }



            if (isset($_POST["Padre"])) {
                if ($_POST["Padre"] != '') {
                    //esto es porque el padre es el entero

                    $numero = $_POST["Padre"];


                    $G4_C23 = $numero;
                    $LsqlU .= ", id_campana = " . $G4_C23 . "";
                    $LsqlI .= ", id_campana";
                    $LsqlV .= "," . $_POST["Padre"];
                }
            }

            if (isset($_GET["Id_paso"])) {
                if ($_GET["Id_paso"] != '') {
                    //esto es porque el padre es el entero

                    $numero = $_GET["Id_paso"];


                    $G4_C23 = $numero;
                    $LsqlU .= ", id_estpas = " . $G4_C23 . "";
                    $LsqlI .= ", id_estpas";
                    $LsqlV .= "," . $_GET["Id_paso"];
                }
            }



            if (isset($_POST['oper'])) {
                if ($_POST["oper"] == 'add') {
                    $Lsql = $LsqlI . ")" . $LsqlV . ")";
                } else if ($_POST["oper"] == 'edit') {
                    $Lsql = $LsqlU . " WHERE id =" . $_POST["id"];
                } else if ($_POST['oper'] == 'del') {
                    $Lsql = "DELETE FROM  " . $BaseDatos_telefonia . ".pasos_troncales WHERE id = " . $_POST['id'];
                    $validar = 1;
                }
            }

            if ($validar == 1) {
                // echo $Lsql;
                $id_Usuario_Nuevo = 1;
                if ($mysqli->query($Lsql) === TRUE) {
                    $id_Usuario_Nuevo = $mysqli->insert_id;

                    if ($_POST["oper"] == 'add') {
                        guardar_auditoria("INSERTAR", "INSERTAR REGISTRO EN pasos_troncales");
                    } else if ($_POST["oper"] == 'edit') {
                        guardar_auditoria("ACTUALIZAR", "ACTUALIZO EL REGISTRO # " . $_POST["padre"] . " EN pasos_troncales");
                    } else if ($_POST["oper"] == 'del') {
                        guardar_auditoria("ELIMINAR", "ELIMINO EL REGISTRO # " . $_POST['id'] . " EN pasos_troncales");
                    }

                    echo $id_Usuario_Nuevo;
                } else {
                    echo "Error Hacieno el proceso los registros : " . $mysqli->error;
                }
            }
        }
    }

    //Llenar datos de CAMCON
    if (isset($_GET["callDatosSubgrilla_camcom"])) {

        $id = $_GET['id'];
        $numero = $id;

        $SQL = "SELECT G19_ConsInte__b, PREGUN_Texto_____b as G19_C202, G19_C203 FROM " . $BaseDatos_systema . ".G19 JOIN " . $BaseDatos_systema . ".PREGUN ON PREGUN_ConsInte__b = G19_C202 WHERE G19_C200 = " . $id;
        //$SQL .= " WHERE pasos_troncales.id_campana = ".$numero;
        $SQL .= " ORDER BY G19_C203;";

        //echo $SQL;
        if (stristr($_SERVER["HTTP_ACCEPT"], "application/xhtml+xml")) {
            header("Content-type: application/xhtml+xml;charset=utf-8");
        } else {
            header("Content-type: text/xml;charset=utf-8");
        }

        $et = ">";
        echo "<?xml version='1.0' encoding='utf-8'?$et\n";
        echo "<rows>"; // be sure to put text data in CDATA

        $result = $mysqli->query($SQL);
        while ($fila = $result->fetch_object()) {
            echo "<row asin='" . $fila->G19_ConsInte__b . "'>";
            echo "<cell>" . ($fila->G19_ConsInte__b) . "</cell>";
            echo "<cell>" . ($fila->G19_C202) . "</cell>";
            echo "<cell>" . ($fila->G19_C203) . "</cell>";
            echo "</row>";
        }
        echo "</rows>";
    }

    //insertar en CAMCON
    if (isset($_GET["insertarDatosCamcom"])) {

        if (isset($_POST["oper"])) {
            $Lsql = '';

            $validar = 0;
            $LsqlU = "UPDATE " . $BaseDatos_systema . ".CAMCON SET ";
            $LsqlI = "INSERT INTO " . $BaseDatos_systema . ".CAMCON(";
            $LsqlV = " VALUES (";


            if (isset($_POST["G19_C202"])) {
                $separador = "";
                if ($validar == 1) {
                    $separador = ",";
                }

                $LsqlU .= $separador . "CAMCON_ConsInte__PREGUN_b = '" . $_POST["G19_C202"] . "'";
                $LsqlI .= $separador . "CAMCON_ConsInte__PREGUN_b";
                $LsqlV .= $separador . "'" . $_POST["G19_C202"] . "'";
                $validar = 1;



                $LsqlU .= ", CAMCON_ConsInte__CAMPO__Pob_b = '" . $_POST["G19_C202"] . "'";
                $LsqlI .= ", CAMCON_ConsInte__CAMPO__Pob_b ";
                $LsqlV .= " , '" . $_POST["G19_C202"] . "'";


                $Lsql = "SELECT PREGUN_Texto_____b, PREGUN_ConsInte__GUION__b FROM " . $BaseDatos_systema . ".PREGUN WHERE PREGUN_ConsInte__b = " . $_POST["G19_C202"];
                $res = $mysqli->query($Lsql);
                $dataPre = $res->fetch_array();
            }



            $G19_C203 = NULL;





            if (isset($_POST["Padre"])) {
                if ($_POST["Padre"] != '') {
                    //esto es porque el padre es el entero
                    $separador = "";
                    if ($validar == 1) {
                        $separador = ",";
                    }

                    $numero = $_POST["Padre"];


                    $Padre = $numero;
                    $LsqlU .= ", CAMCON_ConsInte__CAMPAN_b = " . $Padre . "";
                    $LsqlI .= ", CAMCON_ConsInte__CAMPAN_b";
                    $LsqlV .= "," . $_POST["Padre"];


                    $Lsql_Campan = "SELECT CAMPAN_ConsInte__GUION__Pob_b, CAMPAN_ConsInte__MUESTR_b, CAMPAN_ConsInte__GUION__Gui_b  FROM " . $BaseDatos_systema . ".CAMPAN WHERE CAMPAN_ConsInte__b = " . $_POST["Padre"];
                    $res_Lsql_Campan = $mysqli->query($Lsql_Campan);
                    $datoCampan = $res_Lsql_Campan->fetch_array();
                    $str_Pobla_Campan = "G" . $datoCampan['CAMPAN_ConsInte__GUION__Pob_b'];
                    $int_Pobla_Camp_2 = $datoCampan['CAMPAN_ConsInte__GUION__Pob_b'];
                    $int_Muest_Campan = $datoCampan['CAMPAN_ConsInte__MUESTR_b'];
                    $int_Guion_Campan = $datoCampan['CAMPAN_ConsInte__GUION__Gui_b'];



                    $LsqlU .= ", CAMCON_ConsInte__GUION__Pob_b = " . $int_Pobla_Camp_2 . ", CAMCON_ConsInte__GUION__Gui_b = " . $int_Guion_Campan . " , CAMCON_ConsInte__MUESTR_b = " . $int_Muest_Campan;
                    $LsqlI .= ", CAMCON_ConsInte__GUION__Pob_b,  CAMCON_ConsInte__GUION__Gui_b , CAMCON_ConsInte__MUESTR_b";
                    $LsqlV .= "," . $int_Pobla_Camp_2 . " , " . $int_Guion_Campan . " , " . $int_Muest_Campan;

                    $validar = 1;
                    if ($validar == 1) {
                        $separador = ",";
                    }

                    if (isset($_POST['G19_C203'])) {

                        $G19_C203 = $_POST['G19_C203'];
                        $LsqlU .= $separador . "CAMCON_Orden_____b = '" . $G19_C203 . "'";
                        $LsqlI .= $separador . "CAMCON_Orden_____b";
                        $LsqlV .= $separador . "'" . $G19_C203 . "'";
                    } else {
                        $str_OrderSql = "SELECT MAX(CAMCON_Orden_____b) as max FROM " . $BaseDatos_systema . ".CAMCON WHERE CAMCON_ConsInte__CAMPAN_b = " . $_POST["Padre"];

                        $res_OrderSql = $mysqli->query($str_OrderSql);
                        $datos = $res_OrderSql->fetch_array();
                        $G19_C203 = ($datos["max"] + 1);
                        $LsqlU .= $separador . "CAMCON_Orden_____b = '" . $G19_C203 . "'";
                        $LsqlI .= $separador . "CAMCON_Orden_____b";
                        $LsqlV .= $separador . "'" . $G19_C203 . "'";
                    }
                }
            }




            if (isset($_POST['oper'])) {
                if ($_POST["oper"] == 'add') {
                    $Lsql = $LsqlI . ")" . $LsqlV . ")";
                } else if ($_POST["oper"] == 'edit') {
                    $Lsql = $LsqlU . " WHERE CAMCON_ConsInte__b =" . $_POST["id"];
                } else if ($_POST['oper'] == 'del') {
                    $Lsql = "DELETE FROM  " . $BaseDatos_systema . ".CAMCON WHERE CAMCON_ConsInte__b = " . $_POST['id'];
                    $validar = 1;
                }
            }

            if ($validar == 1) {
                echo $Lsql;
                $id_Usuario_Nuevo = 1;
                if ($mysqli->query($Lsql) === TRUE) {
                    $id_Usuario_Nuevo = $mysqli->insert_id;

                    if ($_POST["oper"] == 'add') {
                        guardar_auditoria("INSERTAR", "INSERTAR REGISTRO EN G19");
                    } else if ($_POST["oper"] == 'edit') {
                        guardar_auditoria("ACTUALIZAR", "ACTUALIZO EL REGISTRO # " . $_POST["id"] . " EN G19");
                    } else if ($_POST["oper"] == 'del') {
                        guardar_auditoria("ELIMINAR", "ELIMINO EL REGISTRO # " . $_POST['id'] . " EN G19");
                    }

                    echo $id_Usuario_Nuevo;
                } else {
                    echo "Error Hacieno el proceso los registros : " . $mysqli->error;
                }
            }
        }
    }

    //Llenar la tabla de CAMINC
    if (isset($_GET["callDatosSubgrilla_caminc"])) {

        $id = $_GET['id'];
        $numero = $id;

        $SQL = "SELECT * FROM " . $BaseDatos_systema . ".CAMINC WHERE CAMINC_ConsInte__CAMPAN_b = " . $numero;
        //$SQL .= " WHERE pasos_troncales.id_campana = ".$numero;
        $SQL .= " ORDER BY CAMINC_TexPrePob_b;";

        //echo $SQL;
        if (stristr($_SERVER["HTTP_ACCEPT"], "application/xhtml+xml")) {
            header("Content-type: application/xhtml+xml;charset=utf-8");
        } else {
            header("Content-type: text/xml;charset=utf-8");
        }

        $et = ">";
        echo "<?xml version='1.0' encoding='utf-8'?$et\n";
        echo "<rows>"; // be sure to put text data in CDATA

        $result = $mysqli->query($SQL);
        while ($fila = $result->fetch_object()) {
            echo "<row asin='" . $fila->CAMINC_ConsInte__b . "'>";
            echo "<cell>" . ($fila->CAMINC_ConsInte__b) . "</cell>";
            echo "<cell>" . ($fila->CAMINC_TexPrePob_b) . "</cell>";
            echo "<cell>" . utf8_decode($fila->CAMINC_TexPreGui_b) . "</cell>";
            echo "</row>";
        }
        echo "</rows>";
    }

    //Insertar Dtaos Caminc
    if (isset($_GET['insertarDatosCaminc'])) {
        if (isset($_POST["oper"])) {
            $Lsql = '';

            $validar = 0;
            $LsqlU = "UPDATE " . $BaseDatos_systema . ".CAMINC SET ";
            $LsqlI = "INSERT INTO " . $BaseDatos_systema . ".CAMINC(";
            $LsqlV = " VALUES (";


            if (isset($_POST["CAMINC_ConsInte__CAMPO_Pob_b"])) {
                $separador = "";
                if ($validar == 1) {
                    $separador = ",";
                }

                $LsqlU .= $separador . "CAMINC_ConsInte__CAMPO_Pob_b = '" . $_POST["CAMINC_ConsInte__CAMPO_Pob_b"];
                $LsqlI .= $separador . "CAMINC_ConsInte__CAMPO_Pob_b";
                $LsqlV .= $separador . "'" . $_POST["CAMINC_ConsInte__CAMPO_Pob_b"] . "'";

                $validar = 1;

                $OpcionSQl = "SELECT * FROM " . $BaseDatos_systema . ".PREGUN WHERE PREGUN_ConsInte__b = " . $_POST["CAMINC_ConsInte__CAMPO_Pob_b"];

                $res_OpcionSql = $mysqli->query($OpcionSQl);
                while ($key = $res_OpcionSql->fetch_object()) {
                    //OPCION_ConsInte__GUION__b
                    //OPCION_Nombre____b

                    if ($validar == 1) {
                        $separador = ",";
                    }

                    $LsqlU .= $separador . "CAMINC_NomCamPob_b = 'G" . $key->PREGUN_ConsInte__GUION__b . "_C" . $key->PREGUN_ConsInte__b . "' , CAMINC_TexPrePob_b = '" . $key->PREGUN_Texto_____b . "'";
                    $LsqlI .= $separador . "CAMINC_NomCamPob_b , CAMINC_TexPrePob_b";
                    $LsqlV .= $separador . "'G" . $key->PREGUN_ConsInte__GUION__b . "_C" . $key->PREGUN_ConsInte__b . "' , '" . $key->PREGUN_Texto_____b . "'";
                }
            }



            $G19_C203 = NULL;
            //este es de tipo numero no se deja ir asi '', si est avacio lo mejor es no mandarlo
            if (isset($_POST["CAMINC_ConsInte__CAMPO_Gui_b"])) {
                if ($_POST["CAMINC_ConsInte__CAMPO_Gui_b"] != '') {
                    $separador = "";
                    if ($validar == 1) {
                        $separador = ",";
                    }

                    $G19_C203 = $_POST["CAMINC_ConsInte__CAMPO_Gui_b"];
                    $LsqlU .= $separador . "CAMINC_ConsInte__CAMPO_Gui_b = '" . $G19_C203 . "'";
                    $LsqlI .= $separador . "CAMINC_ConsInte__CAMPO_Gui_b";
                    $LsqlV .= $separador . "'" . $G19_C203 . "'";
                    $validar = 1;

                    $OpcionSQl = "SELECT * FROM " . $BaseDatos_systema . ".PREGUN WHERE PREGUN_ConsInte__b = " . $_POST["CAMINC_ConsInte__CAMPO_Gui_b"];
                    $res_OpcionSql = $mysqli->query($OpcionSQl);
                    while ($key = $res_OpcionSql->fetch_object()) {
                        //OPCION_ConsInte__GUION__b
                        //OPCION_Nombre____b
                        if ($validar == 1) {
                            $separador = ",";
                        }

                        $LsqlU .= $separador . "CAMINC_NomCamGui_b = 'G" . $key->PREGUN_ConsInte__GUION__b . "_C" . $key->PREGUN_ConsInte__b . "' , CAMINC_TexPreGui_b = '" . $key->PREGUN_Texto_____b . "'";
                        $LsqlI .= $separador . "CAMINC_NomCamGui_b , CAMINC_TexPreGui_b";
                        $LsqlV .= $separador . "'G" . $key->PREGUN_ConsInte__GUION__b . "_C" . $key->PREGUN_ConsInte__b . "' , '" . $key->PREGUN_Texto_____b . "'";
                    }
                }
            }



            if (isset($_POST["Padre"])) {
                if ($_POST["Padre"] != '') {
                    //esto es porque el padre es el entero

                    $numero = $_POST["Padre"];


                    $Padre = $numero;
                    $LsqlU .= ", CAMINC_ConsInte__CAMPAN_b = " . $Padre . "";
                    $LsqlI .= ", CAMINC_ConsInte__CAMPAN_b";
                    $LsqlV .= "," . $_POST["Padre"];


                    $XSql = "SELECT G10_C73 as CAMPAN_ConsInte__GUION__Gui_b , G10_C74 as CAMPAN_ConsInte__GUION__Pob_b , G10_C75 as CAMPAN_ConsInte__MUESTR_b FROM " . $BaseDatos_systema . ".G10 WHERE G10_ConsInte__b = " . $Padre;
                    $res_XSql = $mysqli->query($XSql);
                    while ($key = $res_XSql->fetch_object()) {
                        $LsqlU .= ", CAMINC_ConsInte__GUION__Pob_b = " . $key->CAMPAN_ConsInte__GUION__Pob_b . ", CAMINC_ConsInte__GUION__Gui_b = " . $key->CAMPAN_ConsInte__GUION__Gui_b . " , CAMINC_ConsInte__MUESTR_b = " . $key->CAMPAN_ConsInte__MUESTR_b;
                        $LsqlI .= ", CAMINC_ConsInte__GUION__Pob_b, CAMINC_ConsInte__GUION__Gui_b, CAMINC_ConsInte__MUESTR_b ";
                        $LsqlV .= "," . $key->CAMPAN_ConsInte__GUION__Pob_b . " , " . $_GET['script'] . " , " . $key->CAMPAN_ConsInte__MUESTR_b;
                    }
                }
            }


            if (isset($_POST['oper'])) {
                if ($_POST["oper"] == 'add') {
                    $Lsql = $LsqlI . ")" . $LsqlV . ")";
                } else if ($_POST["oper"] == 'edit') {
                    $Lsql = $LsqlU . " WHERE CAMINC_ConsInte__b =" . $_POST["id"];
                } else if ($_POST['oper'] == 'del') {
                    $Lsql = "DELETE FROM  " . $BaseDatos_systema . ".CAMINC WHERE CAMINC_ConsInte__b = " . $_POST['id'];
                    $validar = 1;
                }
            }

            if ($validar == 1) {
                echo $Lsql;
                $id_Usuario_Nuevo = 1;
                if ($mysqli->query($Lsql) === TRUE) {
                    $id_Usuario_Nuevo = $mysqli->insert_id;

                    if ($_POST["oper"] == 'add') {
                        guardar_auditoria("INSERTAR", "INSERTAR REGISTRO EN CAMINC");
                    } else if ($_POST["oper"] == 'edit') {
                        guardar_auditoria("ACTUALIZAR", "ACTUALIZO EL REGISTRO # " . $_POST["id"] . " EN CAMINC");
                    } else if ($_POST["oper"] == 'del') {
                        guardar_auditoria("ELIMINAR", "ELIMINO EL REGISTRO # " . $_POST['id'] . " EN CAMINC");
                    }

                    echo $id_Usuario_Nuevo;
                } else {
                    echo "Error Hacieno el proceso los registros : " . $mysqli->error;
                }
            }
        }
    }

    if (isset($_GET['CallDatosCombo_tipos_destino'])) {
        $Ysql = 'SELECT   id , nombre FROM ' . $BaseDatos_telefonia . '.tipos_destino WHERE id_huesped = '.$_SESSION['HUESPED'];
        $guion = $mysqli->query($Ysql);
        echo '<select class="form-control input-sm"  name="tipos_destino" id="tipos_destino">';
        echo '<option value="0">Seleccione</option>';
        while ($obj = $guion->fetch_object()) {
            echo "<option value='" . $obj->id . "' dinammicos='0'>" . ($obj->nombre) . "</option>";
        }
        echo '</select>';
    }
  //.tome el valor de la troncal  'OR id_proyecto="-100"'
    if (isset($_GET['CallDatosCombo_troncales'])) {
        $HSlq = "SELECT id FROM " . $BaseDatos_telefonia . ".dy_proyectos WHERE id_huesped = " . $_SESSION['HUESPED'];
        $res = $mysqli->query($HSlq);
        $data = $res->fetch_array();
        $Ysql = 'SELECT  id , nombre_usuario FROM ' . $BaseDatos_telefonia . '.dy_troncales WHERE id_proyecto =' . $data['id'] . ' OR id_proyecto="-100"'. ' ORDER BY nombre_usuario ASC'; // WHERE id_proyecto = '.$_SESSION['HUESPED'];
        $guion = $mysqli->query($Ysql);
        echo '<select class="form-control input-sm"  name="troncal" id="troncal">';
        echo '<option value="0">Seleccione</option>';
        while ($obj = $guion->fetch_object()) {
            echo "<option value='" . $obj->id . "' dinammicos='0'>" . ($obj->nombre_usuario) . "</option>";
        }
        echo '</select>';
    }

    if (isset($_GET['CallDatosCombo_troncales2'])) {
        $HSlq = "SELECT id FROM " . $BaseDatos_telefonia . ".dy_proyectos WHERE id_huesped = " . $_SESSION['HUESPED'];
        $res = $mysqli->query($HSlq);
        $data = $res->fetch_array();
        $Ysql = 'SELECT  id , nombre_usuario FROM ' . $BaseDatos_telefonia . '.dy_troncales WHERE id_proyecto =' . $data['id'] . ' ORDER BY nombre_usuario ASC'; // WHERE id_proyecto = '.$_SESSION['HUESPED'];
        $guion = $mysqli->query($Ysql);
        echo '<select class="form-control input-sm"  name="troncal_desborde" id="troncal_desborde">';
        echo '<option value="0">Seleccione</option>';
        while ($obj = $guion->fetch_object()) {
            echo "<option value='" . $obj->id . "' dinammicos='0'>" . ($obj->nombre_usuario) . "</option>";
        }
        echo '</select>';
    }

    //Llenar la tabla de CAMINC
    if (isset($_GET["camord_guardar"])) {

        $intIdCampan_t = $_GET["intIdCampan_t"];
        $arrIdRows_t = explode(",",$_GET["arrIdRows_t"]);

        $strSQLDeleteCamord_t = "DELETE FROM ".$BaseDatos_systema.".CAMORD WHERE CAMORD_CONSINTE__CAMPAN_B = ".$intIdCampan_t;

        $strSQLInsertCamord_t = "INSERT INTO ".$BaseDatos_systema.".CAMORD (CAMORD_CONSINTE__CAMPAN_B,CAMORD_MUESPOBL__B,CAMORD_POBLCAMP__B,CAMORD_MUESCAMP__B,CAMORD_PRIORIDAD_B,CAMORD_ORDEN_____B) VALUES ";

        foreach ($arrIdRows_t as $row) {

            $strCamordBase_t = (isset($_POST["camord_base_".$row]) ? "'".$_POST["camord_base_".$row]."'" : "NULL");
            $strCamordCampana_t = (isset($_POST["camord_campana_".$row]) ? "'".$_POST["camord_campana_".$row]."'" : "NULL");

            $strSQLInsertCamord_t .= "('".$intIdCampan_t."','".$_POST["camord_basado_".$row]."',".$strCamordBase_t.",".$strCamordCampana_t.",'".$_POST["camord_prioridad_".$row]."','".$_POST["camord_orden_".$row]."'),";

        }

        $strSQLInsertCamord_t = substr($strSQLInsertCamord_t, 0, -1);

        $mysqli->query($strSQLDeleteCamord_t);
        $mysqli->query($strSQLInsertCamord_t);

    }    

    if (isset($_GET["callDatosSubgrilla_camord"])) {

        $intIdCampan_t = $_POST['intIdCampan_t'];

        $strSQLCamord_t = "SELECT CAMORD_CONSINTE__B, CAMORD_CONSINTE__CAMPAN_B, CAMORD_MUESPOBL__B, CAMORD_POBLCAMP__B, CAMORD_MUESCAMP__B, (CASE WHEN PREGUN_Tipo______b IS NOT NULL THEN PREGUN_Tipo______b ELSE (CASE WHEN CAMORD_POBLCAMP__B IS NOT NULL THEN (CASE WHEN CAMORD_POBLCAMP__B LIKE '%_ConsInte__b' THEN 3 WHEN CAMORD_POBLCAMP__B LIKE '%_FechaInsercion' THEN 5 END) ELSE (CASE WHEN CAMORD_MUESCAMP__B LIKE '%_Estado____b' THEN 3 WHEN CAMORD_MUESCAMP__B LIKE '%_FecUltGes_b' THEN 5 WHEN CAMORD_MUESCAMP__B LIKE '%_NumeInte__b' THEN 3 WHEN CAMORD_MUESCAMP__B LIKE '%_UltiGest__b' THEN 3 END) END) END) AS PREGUN_Tipo______b, CAMORD_PRIORIDAD_B, CAMORD_ORDEN_____B FROM ".$BaseDatos_systema.".CAMORD LEFT JOIN (SELECT CONCAT('G',PREGUN_ConsInte__GUION__b,'_C',PREGUN_ConsInte__b) AS id, PREGUN_Tipo______b, PREGUN_Texto_____b AS nombre FROM ".$BaseDatos_systema.".PREGUN) Pregun ON CAMORD_POBLCAMP__B = Pregun.id WHERE CAMORD_CONSINTE__CAMPAN_B = ".$intIdCampan_t;

        $intCantidadCamord_t = 0;
        $arrCarmord_t = ["arrCamord_t"=>[],"intCantidad_t"=>0];

        foreach ($mysqli->query($strSQLCamord_t) as $resSQLCamord_t) {

            $intCantidadCamord_t ++;

            $arrCarmord_t["arrCamord_t"][] = $resSQLCamord_t;


        }

        $arrCarmord_t["intCantidad_t"] = $intCantidadCamord_t;

        echo json_encode($arrCarmord_t);

    }

    //Insertar Dtaos Caminc
    if (isset($_GET['insertarDatosCamord'])) {
        if (isset($_POST["oper"])) {
            $Lsql = '';

            $validar = 0;
            $LsqlU = "UPDATE " . $BaseDatos_systema . ".CAMORD SET ";
            $LsqlI = "INSERT INTO " . $BaseDatos_systema . ".CAMORD(";
            $LsqlV = " VALUES (";

            if (isset($_POST["CAMORD_MUESCAMP__B"])) {
                if ($_POST["CAMORD_MUESCAMP__B"] != '0') {
                    $separador = "";
                    if ($validar == 1) {
                        $separador = ",";
                    }

                    $LsqlU .= $separador . "CAMORD_MUESCAMP__B = '" . $_POST["CAMORD_MUESCAMP__B"] . "'";
                    $LsqlI .= $separador . "CAMORD_MUESCAMP__B";
                    $LsqlV .= $separador . "'" . $_POST["CAMORD_MUESCAMP__B"] . "'";
                    $validar = 1;
                }
            }


            if (isset($_POST["CAMORD_MUESPOBL__B"])) {
                if ($_POST["CAMORD_MUESPOBL__B"] != '0') {
                    $separador = "";
                    if ($validar == 1) {
                        $separador = ",";
                    }

                    $LsqlU .= $separador . "CAMORD_MUESPOBL__B = '" . $_POST["CAMORD_MUESPOBL__B"] . "'";
                    $LsqlI .= $separador . "CAMORD_MUESPOBL__B";
                    $LsqlV .= $separador . "'" . $_POST["CAMORD_MUESPOBL__B"] . "'";
                    $validar = 1;
                }
            }

            if (isset($_POST["CAMORD_POBLCAMP__B"])) {
                if ($_POST["CAMORD_POBLCAMP__B"] != '0') {
                    $separador = "";
                    if ($validar == 1) {
                        $separador = ",";
                    }

                    $LsqlU .= $separador . "CAMORD_POBLCAMP__B = '" . $_POST["CAMORD_POBLCAMP__B"] . "'";
                    $LsqlI .= $separador . "CAMORD_POBLCAMP__B";
                    $LsqlV .= $separador . "'" . $_POST["CAMORD_POBLCAMP__B"] . "'";
                    $validar = 1;
                }
            }


            if (isset($_POST["CAMORD_PRIORIDAD_B"])) {
                $separador = "";
                if ($validar == 1) {
                    $separador = ",";
                }

                $LsqlU .= $separador . "CAMORD_PRIORIDAD_B = '" . $_POST["CAMORD_PRIORIDAD_B"] . "'";
                $LsqlI .= $separador . "CAMORD_PRIORIDAD_B";
                $LsqlV .= $separador . "'" . $_POST["CAMORD_PRIORIDAD_B"] . "'";
                $validar = 1;
            }


            if (isset($_POST["CAMORD_ORDEN_____B"])) {
                $separador = "";
                if ($validar == 1) {
                    $separador = ",";
                }

                $LsqlU .= $separador . "CAMORD_ORDEN_____B = '" . $_POST["CAMORD_ORDEN_____B"] . "'";
                $LsqlI .= $separador . "CAMORD_ORDEN_____B";
                $LsqlV .= $separador . "'" . $_POST["CAMORD_ORDEN_____B"] . "'";
                $validar = 1;
            }


            if (isset($_POST["Padre"])) {
                if ($_POST["Padre"] != '') {
                    //esto es porque el padre es el entero

                    $numero = $_POST["Padre"];
                    $Padre = $numero;
                    $LsqlU .= ", CAMORD_CONSINTE__CAMPAN_B = " . $Padre . "";
                    $LsqlI .= ", CAMORD_CONSINTE__CAMPAN_B";
                    $LsqlV .= "," . $_POST["Padre"];
                }
            }



            if (isset($_POST['oper'])) {
                if ($_POST["oper"] == 'add') {
                    $Lsql = $LsqlI . ")" . $LsqlV . ")";
                } else if ($_POST["oper"] == 'edit') {
                    $Lsql = $LsqlU . " WHERE CAMORD_ConsInte__b =" . $_POST["id"];
                } else if ($_POST['oper'] == 'del') {
                    $Lsql = "DELETE FROM  " . $BaseDatos_systema . ".CAMORD WHERE CAMORD_CONSINTE__B = " . $_POST['id'];
                    $validar = 1;
                }
            }

            if ($validar == 1) {
                //echo $Lsql;
                $id_Usuario_Nuevo = 1;
                if ($mysqli->query($Lsql) === TRUE) {
                    $id_Usuario_Nuevo = $mysqli->insert_id;

                    if ($_POST["oper"] == 'add') {
                        guardar_auditoria("INSERTAR", "INSERTAR REGISTRO EN CAMORD");
                    } else if ($_POST["oper"] == 'edit') {
                        guardar_auditoria("ACTUALIZAR", "ACTUALIZO EL REGISTRO # " . $_POST["id"] . " EN CAMORD");
                    } else if ($_POST["oper"] == 'del') {
                        guardar_auditoria("ELIMINAR", "ELIMINO EL REGISTRO # " . $_POST['id'] . " EN CAMORD");
                    }

                    echo $id_Usuario_Nuevo;
                } else {
                    echo "Error Hacieno el proceso los registros : " . $mysqli->error;
                }
            }
        }
    }

    if (isset($_GET["callDatosSubgrilla_UsuariosCampan"])) {

        $id = $_GET['id'];
        $numero = $id;

        $SQL = "SELECT ASITAR_ConsInte__b, USUARI_Nombre____b FROM " . $BaseDatos_systema . ".ASITAR JOIN " . $BaseDatos_systema . ".USUARI ON USUARI_ConsInte__b = ASITAR_ConsInte__USUARI_b WHERE ASITAR_ConsInte__CAMPAN_b = " . $id;
        //$SQL .= " WHERE pasos_troncales.id_campana = ".$numero;
        $SQL .= " ORDER BY USUARI_Nombre____b;";

        //echo $SQL;
        if (stristr($_SERVER["HTTP_ACCEPT"], "application/xhtml+xml")) {
            header("Content-type: application/xhtml+xml;charset=utf-8");
        } else {
            header("Content-type: text/xml;charset=utf-8");
        }

        $et = ">";
        echo "<?xml version='1.0' encoding='utf-8'?$et\n";
        echo "<rows>"; // be sure to put text data in CDATA

        $result = $mysqli->query($SQL);
        while ($fila = $result->fetch_object()) {
            echo "<row asin='" . $fila->ASITAR_ConsInte__b . "'>";
            echo "<cell>" . ($fila->ASITAR_ConsInte__b) . "</cell>";
            echo "<cell>" . ($fila->USUARI_Nombre____b) . "</cell>";
            echo "</row>";
        }
        echo "</rows>";
    }

    if (isset($_GET['insertarDatosUsuarioCampan'])) {
        if (isset($_POST["oper"])) {
            $Lsql = '';
            $Lsql_dy = '';

            $validar = 0;
            $LsqlU = "UPDATE " . $BaseDatos_systema . ".ASITAR SET ";
            $LsqlI = "INSERT INTO " . $BaseDatos_systema . ".ASITAR(";
            $LsqlV = " VALUES (";


            $LsqlU_dy = "UPDATE " . $BaseDatos_telefonia . ".dy_campanas_agentes SET ";
            $LsqlI_dy = "INSERT INTO " . $BaseDatos_telefonia . ".dy_campanas_agentes(";
            $LsqlV_dy = " VALUES (";

            if (isset($_POST["USUARI_Nombre____b"])) {
                if ($_POST["USUARI_Nombre____b"] != '0') {
                    $separador = "";
                    if ($validar == 1) {
                        $separador = ",";
                    }

                    $str_UsuarioCbx = "SELECT USUARI_UsuaCBX___b FROM " . $BaseDatos_systema . ".USUARI WHERE USUARI_ConsInte__b = " . $_POST["USUARI_Nombre____b"];
                    $res_strUsuariCbx = $mysqli->query($str_UsuarioCbx);
                    $datoSql = $res_strUsuariCbx->fetch_array();

                    $LsqlU .= $separador . "ASITAR_ConsInte__USUARI_b = '" . $_POST["USUARI_Nombre____b"] . "', ASITAR_UsuarioCBX_b = " . $datoSql['USUARI_UsuaCBX___b'];
                    $LsqlI .= $separador . "ASITAR_ConsInte__USUARI_b , ASITAR_UsuarioCBX_b";
                    $LsqlV .= $separador . "'" . $_POST["USUARI_Nombre____b"] . "' , " . $datoSql['USUARI_UsuaCBX___b'];

                    $validar = 1;

                    /* Insertar en dy_agentes_campañas */
                    $str_dyAgentesLsql = "SELECT id FROM " . $BaseDatos_telefonia . ".dy_agentes WHERE id_usuario_asociado = " . $datoSql['USUARI_UsuaCBX___b'];
                    $res_str_dyAgentesLsql = $mysqli->query($str_dyAgentesLsql);
                    $datos_DyAgentes = $res_str_dyAgentesLsql->fetch_array();
                    $LsqlI_dy .= $separador . "id_agente";
                    $LsqlV_dy .= $separador . "" . $datos_DyAgentes['id'];
                    $LsqlU_dy .= $separador . "id_agente = " . $datos_DyAgentes['id'];
                }
            }

            if (isset($_POST["Padre"])) {
                if ($_POST["Padre"] != '') {
                    //esto es porque el padre es el entero

                    $numero = $_POST["Padre"];
                    $Padre = $numero;
                    $LsqlU .= ", ASITAR_ConsInte__CAMPAN_b = " . $Padre . "";
                    $LsqlI .= ", ASITAR_ConsInte__CAMPAN_b";
                    $LsqlV .= "," . $_POST["Padre"];


                    /* obtenerlas muestar y todo eso */

                    $Lsql_Campan = "SELECT CAMPAN_ConsInte__GUION__Pob_b, CAMPAN_ConsInte__MUESTR_b, CAMPAN_ConsInte__GUION__Gui_b , CAMPAN_IdCamCbx__b FROM " . $BaseDatos_systema . ".CAMPAN WHERE CAMPAN_ConsInte__b = " . $_POST["Padre"];
                    $res_Lsql_Campan = $mysqli->query($Lsql_Campan);
                    $datoCampan = $res_Lsql_Campan->fetch_array();
                    $int_Pobla_Camp_2 = $datoCampan['CAMPAN_ConsInte__GUION__Pob_b'];
                    $int_Muest_Campan = $datoCampan['CAMPAN_ConsInte__MUESTR_b'];
                    $int_Guion_Campan = $datoCampan['CAMPAN_ConsInte__GUION__Gui_b'];
                    $int_Cbx___Campan = $datoCampan['CAMPAN_IdCamCbx__b'];


                    $LsqlU .= ", ASITAR_IndiConc__b = 0, ASITAR_Prioridad_b = 0, ASITAR_ConsInte__GUION__Gui_b = " . $int_Guion_Campan . ", ASITAR_ConsInte__GUION__Pob_b = " . $int_Pobla_Camp_2 . ", ASITAR_ConsInte__MUESTR_b = " . $int_Muest_Campan;
                    $LsqlI .= ", ASITAR_IndiConc__b, ASITAR_Prioridad_b, ASITAR_ConsInte__GUION__Gui_b, ASITAR_ConsInte__GUION__Pob_b, ASITAR_ConsInte__MUESTR_b";
                    $LsqlV .= ",0,0," . $int_Guion_Campan . "," . $int_Pobla_Camp_2 . "," . $int_Muest_Campan;

                    /* insertar en dy_agentes_campañas */

                    $LsqlI_dy .= ",id_campana";
                    $LsqlV_dy .= "," . $int_Cbx___Campan;
                    $LsqlU_dy .= ",id_campana = " . $int_Cbx___Campan;


                    $LsqlI_dy .= ",prioridad, fijo, responde_correos_electronicos, responde_chat, responde_llamadas";
                    $LsqlV_dy .= ",1, 0, 0, 0, 1";
                    $LsqlU_dy .= ",prioridad = 1, fijo = 0, responde_correos_electronicos = 0, responde_chat = 0, responde_llamadas = 1";
                }
            }

            /* */



            if (isset($_POST['oper'])) {
                if ($_POST["oper"] == 'add') {
                    $Lsql = $LsqlI . ")" . $LsqlV . ")";
                    $Lsql_dy = $LsqlI_dy . ")" . $LsqlV_dy . ")";
                } else if ($_POST["oper"] == 'edit') {
                    $Lsql = $LsqlU . " WHERE ASITAR_ConsInte__b =" . $_POST["id"];
                } else if ($_POST['oper'] == 'del') {
                    $Lsql = "DELETE FROM  " . $BaseDatos_systema . ".ASITAR WHERE ASITAR_ConsInte__b = " . $_POST['id'];
                    $validar = 1;


                    $getDatosLsql = "SELECT ASITAR_UsuarioCBX_b, ASITAR_ConsInte__CAMPAN_b FROM " . $BaseDatos_systema . ".ASITAR WHERE ASITAR_ConsInte__b = " . $_POST['id'];
                    $res_datosLSql = $mysqli->query($getDatosLsql);
                    $datosASItar = $res_datosLSql->fetch_array();
                    $Lsql_dy = "DELETE FROM  " . $BaseDatos_telefonia . ".dy_campanas_agentes WHERE id_agente = " . $datosASItar['ASITAR_UsuarioCBX_b'] . " AND id_campana = " . $datosASItar['ASITAR_ConsInte__CAMPAN_b'];
                }
            }

            if ($validar == 1) {
                echo $Lsql_dy;
                $id_Usuario_Nuevo = 1;
                if ($mysqli->query($Lsql) === TRUE) {
                    $id_Usuario_Nuevo = $mysqli->insert_id;

                    if ($_POST["oper"] == 'add') {
                        guardar_auditoria("INSERTAR", "INSERTAR REGISTRO EN ASITAR");
                    } else if ($_POST["oper"] == 'edit') {
                        guardar_auditoria("ACTUALIZAR", "ACTUALIZO EL REGISTRO # " . $_POST["id"] . " EN ASITAR");
                    } else if ($_POST["oper"] == 'del') {
                        guardar_auditoria("ELIMINAR", "ELIMINO EL REGISTRO # " . $_POST['id'] . " EN ASITAR");
                    }
                    echo $id_Usuario_Nuevo;
                } else {
                    echo "Error Hacieno el proceso los registros : " . $mysqli->error;
                }


                if ($mysqli->query($Lsql_dy) === true) {
                    
                } else {
                    echo "Error => " . $mysqli->error;
                }
            }
        }
    }

    function condiciones_mysql($name_p,$operador_p,$tipo_p,$valor_p,$condicion_p, $cierre_p = " "){

        $condicion_t = $condicion_p;

        // if ($condicion_p == "0") {

        //     $condicion_t = "OR";

        // }       

        if ($tipo_p == 5) {
            $CON = " ".$condicion_t." DATE_FORMAT(".$name_p.",'%Y-%m-%d') ";
        }elseif($tipo_p == 10){
            if (strlen($valor_p) == 5) {
                $valor_p .= ":00";
            }
            $CON = " ".$condicion_t." DATE_FORMAT(".$name_p.",'%H:%i:%s') ";
        }else{
            $CON = " ".$condicion_t." ".$name_p." ";
        }

        if (is_numeric($tipo_p)) {
            if ($tipo_p<3 || $tipo_p == 5 || $tipo_p == 10 || $tipo_p == 14) {
                if ($operador_p == "=") {
                    $CON .= "= '".$valor_p."' ";
                }elseif ($operador_p == "!=") {
                    $CON .= "!= '".$valor_p."' ";
                }elseif ($operador_p == "LIKE_1") {
                    $CON .= "LIKE '".$valor_p."%' ";
                }elseif ($operador_p == "LIKE_2") {
                    $CON .= "LIKE '%".$valor_p."%' ";
                }elseif ($operador_p == "LIKE_3") {
                    $CON .= "LIKE '%".$valor_p."' ";
                }elseif ($operador_p == ">") {
                    $CON .= "> '".$valor_p."' ";
                }elseif ($operador_p == "<") {
                    $CON .= "< '".$valor_p."' ";
                }
                    
            }else{
                if ($operador_p == "=") {
                    $CON .= "= ".$valor_p." ";
                }elseif ($operador_p == "!=") {
                    $CON .= "!= ".$valor_p." ";
                }elseif ($operador_p == ">") {
                    $CON .= "> ".$valor_p." ";
                }elseif ($operador_p == "<") {
                    $CON .= "< ".$valor_p." ";
                }
            }
        }else{
            if ($tipo_p == "_FecUltGes_b" || $tipo_p == "_FeGeMaIm__b") {
                if ($operador_p == "=") {
                    $CON .= "= '".$valor_p."' ";
                }elseif ($operador_p == "!=") {
                    $CON .= "!= '".$valor_p."' ";
                }elseif ($operador_p == ">") {
                    $CON .= "> '".$valor_p."' ";
                }elseif ($operador_p == "<") {
                    $CON .= "< '".$valor_p."' ";
                }
            }else{
                if ($operador_p == "=") {
                    $CON .= "= ".$valor_p." ";
                }elseif ($operador_p == "!=") {
                    $CON .= "!= ".$valor_p." ";
                }elseif ($operador_p == ">") {
                    $CON .= "> ".$valor_p." ";
                }elseif ($operador_p == "<") {
                    $CON .= "< ".$valor_p." ";
                }
            }
        }

        return $CON  . $cierre_p;

    }   

    if (isset($_GET['delete_base'])) {

        $intRadioCondiciones_t = $_POST["radio_condiciones_delete"];
        $intCampanaId_t = $_POST["id_campana_delete"];

        $strDatosCampana_t = "SELECT CAMPAN_ConsInte__GUION__Pob_b, CAMPAN_ConsInte__MUESTR_b 
                              FROM ".$BaseDatos_systema.".CAMPAN 
                              WHERE CAMPAN_ConsInte__b = ".$intCampanaId_t;
        $resSQLCampana_t = $mysqli->query($strDatosCampana_t);

        if ($resSQLCampana_t->num_rows>0) {

            $objCampana_t = $resSQLCampana_t->fetch_object();
            $intBase_t = $objCampana_t->CAMPAN_ConsInte__GUION__Pob_b;
            $intMuestra_t = $objCampana_t->CAMPAN_ConsInte__MUESTR_b;

            if ($intRadioCondiciones_t == 1) {

                $strDeleteBD_t = "DELETE FROM ".$BaseDatos.".G".$intBase_t;

                $strDeleteM_t = "DELETE FROM ".$BaseDatos.".G".$intBase_t."_M".$intMuestra_t;

                if ($mysqli->query($strDeleteBD_t)) {
                    guardar_auditoria("DeleteRegistros","POB: G".$intBase_t." TipL: ELIMINAR TODO | ".$strDeleteBD_t);
                    echo "Exito";
                }

                if ($mysqli->query($strDeleteM_t)) {
                    guardar_auditoria("DeleteRegistros","POB: G".$intBase_t."_M".$intMuestra_t." TipL: ELIMINAR TODO | ".$strDeleteM_t);
                    echo "Exito";
                }

            }elseif ($intRadioCondiciones_t == 2) {

                $arrFiltros_t = explode(",", $_POST["contador"]);
                $strCONDICION_t = "";

                foreach ($arrFiltros_t as $intCondicion_t) {
                    if ($intCondicion_t != "") {
                        $campFil_t = $_POST["pregun_".$intCondicion_t];
                        $strOperador_t = $_POST["condicion_".$intCondicion_t];
                        $valor_t = $_POST["valor_".$intCondicion_t];
                        $tipo_t = $_POST["tipo_campo_".$intCondicion_t];
                        $condicion_t = (isset($_POST["andOr_".$intCondicion_t]) ? $_POST["andOr_".$intCondicion_t] : "1");

                        if (is_numeric($campFil_t)) {
                            if ($campFil_t>0) {

                                $strComprobar_t = "SHOW COLUMNS FROM " . $BaseDatos . ".G".$intBase_t." WHERE Field = 'G".$intBase_t."_C".$campFil_t."'";
                                $resSQLUltComprobar_t = $mysqli->query($strComprobar_t);

                                if ($resSQLUltComprobar_t->num_rows > 0) {
                                    $strCONDICION_t .= condiciones_mysql("G".$intBase_t."_C".$campFil_t,$strOperador_t,$tipo_t,$valor_t,$condicion_t); 
                                }else{
                                    echo "error";
                                }

                            }
                        }else{
                            if ($campFil_t != "_FechaInsercion") {
                                $strCONDICION_t .= condiciones_mysql("G".$intBase_t."_M".$intMuestra_t.$campFil_t,$strOperador_t,$tipo_t,$valor_t,$condicion_t);
                            }else{
                                $strCONDICION_t .= condiciones_mysql("G".$intBase_t."_FechaInsercion",$strOperador_t,$tipo_t,$valor_t,$condicion_t);
                            }
                        }
                    }
                }

                if ($strCONDICION_t != "") {

                    $strDeleteId_t = "SELECT G".$intBase_t."_ConsInte__b AS ID 
                                          FROM ".$BaseDatos.".G".$intBase_t." 
                                          JOIN ".$BaseDatos.".G".$intBase_t."_M".$intMuestra_t." ON 
                                          G".$intBase_t.".G".$intBase_t."_ConsInte__b = G".$intBase_t."_M".$intMuestra_t.".G".$intBase_t."_M".$intMuestra_t."_CoInMiPo__b 
                                          WHERE TRUE ".$strCONDICION_t;

                    $resDeleteId_t = $mysqli->query($strDeleteId_t);

                    if ($resDeleteId_t) {
                        $strIdDelete_t = "";
                        if ($resDeleteId_t->num_rows > 0) {
                            while ($objDeleteId_t = $resDeleteId_t->fetch_object()) {
                                $strIdDelete_t .= $objDeleteId_t->ID.","; 
                            }
                            $strIdDelete_t = substr($strIdDelete_t, 0, -1);
                        }

                        if ($strIdDelete_t != "") {
                            
                            $strDeleteBD_t = "DELETE FROM ".$BaseDatos.".G".$intBase_t." 
                                            WHERE G".$intBase_t."_ConsInte__b IN (".$strIdDelete_t.")";

                            $strDeleteM_t = "DELETE FROM ".$BaseDatos.".G".$intBase_t."_M".$intMuestra_t." 
                                            WHERE G".$intBase_t."_M".$intMuestra_t."_CoInMiPo__b IN (".$strIdDelete_t.")";

                            if ($mysqli->query($strDeleteBD_t)) {
                                guardar_auditoria("DeleteRegistros","POB: G".$intBase_t." TipL: ELIMINAR CON CONDICION | ".$strDeleteBD_t);
                                echo "Exito";
                            }

                            if ($mysqli->query($strDeleteM_t)) {
                                guardar_auditoria("DeleteRegistros","POB: G".$intBase_t."_M".$intMuestra_t." TipL: ELIMINAR CON CONDICION | ".$strDeleteM_t);
                                echo "Exito";
                            }

                        }else{
                            echo "Exito";
                        }
                    }else{
                        echo "error_3";
                    }


                }else{
                    echo "error_2";
                }
            }elseif ($intRadioCondiciones_t == 3) {
                error_reporting(E_ALL ^ E_NOTICE);
                $intCampoAFiltrar_t = $_POST["campo_a_filtrar_delete"];
                $strFileName_t = $_FILES['listaExcell_delete']['name'];
                $filFile_t  = $_FILES['listaExcell_delete']['tmp_name'];
                $strFileType_t  = $_FILES['listaExcell_delete']['type'];

                if ($filFile_t && $intCampoAFiltrar_t != "0" && $intCampoAFiltrar_t != "-1") {

                    if($strFileType_t == 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'){
                        $objReader = new PHPExcel_Reader_Excel2007();
                        $objReader->setReadDataOnly(true);
                        $obj_excel = $objReader->load($filFile_t);
                    }else if($strFileType_t == 'application/vnd.ms-excel'){
                        $obj_excel = PHPExcel_IOFactory::load($filFile_t);
                    }

                    $sheetData = $obj_excel->getActiveSheet()->toArray(null,true,true,true);
                    $highestColumm = $obj_excel->setActiveSheetIndex(0)->getHighestColumn(); // e.g. "EL"
                    $highestRow = $obj_excel->setActiveSheetIndex(0)->getHighestRow();

                    $intTipoCampo_t = 0;

                    if ($intCampoAFiltrar_t != "_ConsInte__b" && $intCampoAFiltrar_t != 0 && $intCampoAFiltrar_t != -1) {
                        $strTipoCampo_t = "SELECT PREGUN_Tipo______b FROM ".$BaseDatos_systema.".PREGUN WHERE PREGUN_ConsInte__b = ".$intCampoAFiltrar_t;

                        $resTipoCampo_t = $mysqli->query($strTipoCampo_t);

                        if ($resTipoCampo_t->num_rows > 0) {
                            $objTipoCampo_t = $resTipoCampo_t->fetch_object();    
                            $intTipoCampo_t = $objTipoCampo_t->PREGUN_Tipo______b; 
                        }else{
                            echo "error_1";
                        }

                        $intCampoAFiltrar_t = "G".$intBase_t."_C".$intCampoAFiltrar_t;

                    }elseif($intCampoAFiltrar_t == "_ConsInte__b"){
                        $intTipoCampo_t = 3;
                        $intCampoAFiltrar_t = "G".$intBase_t."_ConsInte__b";
                    }

                    $strIN_t = "";

                    if (count($sheetData)>0) {
                        
                        foreach ($sheetData as $index => $value) {

                            $strIN_t .= "TRIM(".$intCampoAFiltrar_t.") = '".trim($value["A"])."' OR ";

                        }
                        $strIN_t = substr($strIN_t, 0, -3);
                    }

                    if ($strIN_t != "") {

                        $strDeleteId_t = "SELECT G".$intBase_t."_ConsInte__b AS ID 
                                          FROM ".$BaseDatos.".G".$intBase_t." 
                                          JOIN ".$BaseDatos.".G".$intBase_t."_M".$intMuestra_t." ON 
                                          G".$intBase_t.".G".$intBase_t."_ConsInte__b = G".$intBase_t."_M".$intMuestra_t.".G".$intBase_t."_M".$intMuestra_t."_CoInMiPo__b 
                                          WHERE ".$strIN_t;
                        $resDeleteId_t = $mysqli->query($strDeleteId_t);

                        if ($resDeleteId_t) {

                            $strIdDelete_t = "";
                            if ($resDeleteId_t->num_rows > 0) {
                                while ($objDeleteId_t = $resDeleteId_t->fetch_object()) {
                                    $strIdDelete_t .= $objDeleteId_t->ID.","; 
                                }
                                $strIdDelete_t = substr($strIdDelete_t, 0, -1);
                            }

                            if ($strIdDelete_t != "") {
                                
                                $strDeleteBD_t = "DELETE FROM ".$BaseDatos.".G".$intBase_t."
                                                  WHERE G".$intBase_t."_ConsInte__b IN (".$strIdDelete_t.")";

                                $strDeleteM_t = "DELETE FROM ".$BaseDatos.".G".$intBase_t."_M".$intMuestra_t."
                                                  WHERE G".$intBase_t."_M".$intMuestra_t."_CoInMiPo__b IN (".$strIdDelete_t.")";
                                
                                if ($mysqli->query($strDeleteBD_t)) {
                                    guardar_auditoria("DeleteRegistros","POB: G".$intBase_t." TipL: ELIMINAR CON CONDICION | ".$strDeleteBD_t);
                                    echo "Exito";
                                }

                                if ($mysqli->query($strDeleteM_t)) {
                                    guardar_auditoria("DeleteRegistros","POB: G".$intBase_t."_M".$intMuestra_t." TipL: ELIMINAR CON CONDICION | ".$strDeleteBD_t);
                                    echo "Exito";
                                }

                            }else{
                                echo "Exito";
                            }
                        }else{
                            echo "error_3";
                        }


                        
                    }else{
                        echo "error_5";
                    }

                }else{
                    echo "error_4";
                }
            }

        }else{
            echo "error_1";
        }
    }

    if (isset($_GET['administrar_base'])) {
 
        $intRadioCondiciones_t = $_POST["radio_condiciones"];
        $intCampanaId_t = $_POST["id_campana"];

        $intEstado_t = $_POST["sel_estados_acciones"];
        $intTipificacion_t = $_POST["sel_tipificaciones_acciones"];
        $intReintento_t = $_POST["sel_tipo_reintento_acciones"];
        $intActivo_t = $_POST["sel_activo_acciones"];
        $intExcluirOincluir_t = $_POST["sel_meter_sacar"];
        $intAgente_t = $_POST["sel_usuarios_asignacion"];
        $strFechaReintento_t = $_POST["txt_fecha_reintento_acciones"];
        $strHoraReintento_t = $_POST["txt_hora_reintento_acciones"];

        $strDatosCampana_t = "SELECT CAMPAN_ConsInte__GUION__Pob_b, CAMPAN_ConsInte__MUESTR_b FROM ".$BaseDatos_systema.".CAMPAN WHERE CAMPAN_ConsInte__b = ".$intCampanaId_t;
        $resSQLCampana_t = $mysqli->query($strDatosCampana_t);

        if ($resSQLCampana_t->num_rows>0) {

            $objCampana_t = $resSQLCampana_t->fetch_object();
            $intBase_t = $objCampana_t->CAMPAN_ConsInte__GUION__Pob_b;
            $intMuestra_t = $objCampana_t->CAMPAN_ConsInte__MUESTR_b;

            $strUPDATE_B_t = "";
            $strUPDATE_M_t = "";

            if ($intEstado_t > 0 || $intTipificacion_t > 0) {

                $strUPDATE_B_t .= "UPDATE ".$BaseDatos.".G".$intBase_t." 
                                   JOIN ".$BaseDatos.".G".$intBase_t."_M".$intMuestra_t." 
                                   ON G".$intBase_t.".G".$intBase_t."_ConsInte__b = G".$intBase_t."_M".$intMuestra_t.".G".$intBase_t."_M".$intMuestra_t."_CoInMiPo__b SET ";


                if ($intEstado_t > 0) {

                    $strCampoEstado_t = "SELECT PREGUN_ConsInte__b 
                                         FROM ".$BaseDatos_systema.".PREGUN 
                                         WHERE PREGUN_ConsInte__GUION__b = ".$intBase_t." 
                                         AND PREGUN_Texto_____b = 'ESTADO_DY';";

                    $resCampo_t = $mysqli->query($strCampoEstado_t);

                    if ($resCampo_t->num_rows > 0) {

                        $objCampo_t = $resCampo_t->fetch_object();
                        $strCampoEstado_id_t = $objCampo_t->PREGUN_ConsInte__b; 

                        $strComprobar_t = "SHOW COLUMNS FROM " . $BaseDatos . ".G".$intBase_t." WHERE Field = 'G".$intBase_t."_C".$strCampoEstado_id_t."'";

                        $resSQLUltComprobar_t = $mysqli->query($strComprobar_t);

                        if ($resSQLUltComprobar_t->num_rows > 0) {

                            $strUPDATE_B_t .= " G".$intBase_t."_C".$strCampoEstado_id_t." = ".$intEstado_t.",";
                        
                        }else{
                            echo "error_1";
                        }
                        

                    }


                }

                if ($intTipificacion_t > 0) { 

                        $strTipificacion_t = "SELECT MONOEF_ConsInte__b FROM " . $BaseDatos_systema . ".MONOEF
                            JOIN ".$BaseDatos_systema.".LISOPC ON MONOEF.MONOEF_ConsInte__b = LISOPC.LISOPC_Clasifica_b
                            WHERE LISOPC.LISOPC_ConsInte__b = ".$intTipificacion_t;

                        $resSQLTipificacionId_t = $mysqli->query($strTipificacion_t);
                        
                        if ($resSQLTipificacionId_t->num_rows > 0) {
                            
                            $objTipificacionId_t = $resSQLTipificacionId_t->fetch_object();
                            $intTipificacionId_t = $objTipificacionId_t->MONOEF_ConsInte__b;

                            $strUPDATE_B_t .= " G".$intBase_t."_UltiGest__b = ".$intTipificacionId_t.",";
                        }else{
                            echo "error_1";
                        }    
                        
                    
                }

                $strUPDATE_B_t = substr($strUPDATE_B_t, 0, -1);

                $strUPDATE_B_t .= " WHERE TRUE ";


            }

            if ($intReintento_t > 0 || $intActivo_t > -2 || $intAgente_t > -2) {
                
                $strUPDATE_M_t = "UPDATE ".$BaseDatos.".G".$intBase_t."_M".$intMuestra_t." JOIN ".$BaseDatos.".G".$intBase_t." ON G".$intBase_t."_M".$intMuestra_t.".G".$intBase_t."_M".$intMuestra_t."_CoInMiPo__b = G".$intBase_t.".G".$intBase_t."_ConsInte__b SET ";

                if ($intReintento_t == 1 || $intReintento_t == 3) {
                    $strUPDATE_M_t .= " G".$intBase_t."_M".$intMuestra_t."_Estado____b = ".$intReintento_t.",G".$intBase_t."_M".$intMuestra_t."_FecHorAge_b = NULL,";
                }
                if ($intReintento_t == 2) {
                    $strUPDATE_M_t .= " G".$intBase_t."_M".$intMuestra_t."_Estado____b = ".$intReintento_t.",";
                }
                if ($strFechaReintento_t != "" && $strHoraReintento_t != "") {
                    $strUPDATE_M_t .= " G".$intBase_t."_M".$intMuestra_t."_FecHorAge_b = '".$strFechaReintento_t." ".$strHoraReintento_t."',";
                }
                if ($intActivo_t > -2) {
                    $strUPDATE_M_t .= " G".$intBase_t."_M".$intMuestra_t."_Activo____b = ".$intActivo_t.",";
                }
                if ($intAgente_t > -2) {
                    if($intAgente_t==-1){$intAgente_t="NULL";}
                    $strUPDATE_M_t .= " G".$intBase_t."_M".$intMuestra_t."_ConIntUsu_b = ".$intAgente_t.",";
                }

                $strUPDATE_M_t = substr($strUPDATE_M_t, 0, -1);

                $strUPDATE_M_t .= " WHERE TRUE ";                
            }
            // TODO: INGRESA ACA POR PASAR A CAMPANA
            if($intExcluirOincluir_t == 1 || $intExcluirOincluir_t == 2){
                if($intExcluirOincluir_t == 1){
                    $sqlInsertMuestra_t="INSERT INTO {$BaseDatos}.G{$intBase_t}_M{$intMuestra_t} (G{$intBase_t}_M{$intMuestra_t}_CoInMiPo__b,G{$intBase_t}_M{$intMuestra_t}_Activo____b,G{$intBase_t}_M{$intMuestra_t}_Estado____b,G{$intBase_t}_M{$intMuestra_t}_TipoReintentoGMI_b,G{$intBase_t}_M{$intMuestra_t}_NumeInte__b,G{$intBase_t}_M{$intMuestra_t}_CantidadIntentosGMI_b,G{$intBase_t}_M{$intMuestra_t}_ConUltGes_b,G{$intBase_t}_M{$intMuestra_t}_CoGesMaIm_b,G{$intBase_t}_M{$intMuestra_t}_UltiGest__b,G{$intBase_t}_M{$intMuestra_t}_GesMasImp_b) SELECT G{$intBase_t}_ConsInte__b,-1,0,0,0,0,3,3,-14,-14 FROM {$BaseDatos}.G{$intBase_t} LEFT JOIN {$BaseDatos}.G{$intBase_t}_M{$intMuestra_t} ON G{$intBase_t}_ConsInte__b=G{$intBase_t}_M{$intMuestra_t}_CoInMiPo__b WHERE G{$intBase_t}_M{$intMuestra_t}_CoInMiPo__b IS NULL";
                }else{
                    $sqlDeleteMuestra="DELETE {$BaseDatos}.G{$intBase_t}_M{$intMuestra_t} FROM {$BaseDatos}.G{$intBase_t}_M{$intMuestra_t} LEFT JOIN {$BaseDatos}.G{$intBase_t} ON G{$intBase_t}_M{$intMuestra_t}_CoInMiPo__b = G{$intBase_t}_ConsInte__b WHERE TRUE";
                }
            }

            if ($intRadioCondiciones_t == 1) {

                if ($strUPDATE_B_t != "") {
                    if ($mysqli->query($strUPDATE_B_t)) {
                        guardar_auditoria("AdmRegistrosGestionarBD","POB: G".$intBase_t." TipL: ACTUALIZACION APLICADA A TODOS LOS REGISTROS | ".$strUPDATE_B_t);
                        echo "Exito";
                    }
                }

                if ($strUPDATE_M_t != "") {
                    if ($mysqli->query($strUPDATE_M_t)) {
                        guardar_auditoria("AdmRegistrosGestionarBD","POB: G".$intBase_t."_M".$intMuestra_t." TipL: ACTUALIZACION APLICADA A TODOS LOS REGISTROS | ".$strUPDATE_M_t);
                        echo "Exito";
                    }
                }

                if(isset($sqlInsertMuestra_t) && $mysqli->query($sqlInsertMuestra_t)){
                    guardar_auditoria("AdmRegistrosGestionarBD","POB: G".$intBase_t."_M".$intMuestra_t." TipL: INSERTAR TODOS LOS REGISTROS DE LA BD A LA MUESTRA| ".$sqlInsertMuestra_t);
                    echo "Exito";
                }

                if(isset($sqlDeleteMuestra) && $mysqli->query($sqlDeleteMuestra)){
                    guardar_auditoria("AdmRegistrosGestionarBD","POB: G".$intBase_t."_M".$intMuestra_t." TipL: ELIMINAR TODOS LOS REGISTROS DE LA MUESTRA| ".$sqlDeleteMuestra);
                    echo "Exito";
                }

            }

            if ($intRadioCondiciones_t == 2) {

                $arrFiltros_t = explode(",", $_POST["contador"]);
                $strCONDICION_t = "";

                foreach ($arrFiltros_t as $intCondicion_t) {
                    if ($intCondicion_t != "") {
                        $campFil_t = $_POST["pregun_".$intCondicion_t];
                        $strOperador_t = $_POST["condicion_".$intCondicion_t];
                        $valor_t = $_POST["valor_".$intCondicion_t];
                        $tipo_t = $_POST["tipo_campo_".$intCondicion_t];
                        $condicion_t = (isset($_POST["andOr_".$intCondicion_t]) ? $_POST["andOr_".$intCondicion_t] : " AND ");
                        $cierre_t = (isset($_POST["cierre".$intCondicion_t]) ? $_POST["cierre".$intCondicion_t] : null);
                        
                        if (is_numeric($campFil_t)) {
                            if ($campFil_t>0) {

                                $strComprobar_t = "SHOW COLUMNS FROM " . $BaseDatos . ".G".$intBase_t." WHERE Field = 'G".$intBase_t."_C".$campFil_t."'";
                                $resSQLUltComprobar_t = $mysqli->query($strComprobar_t);

                                if ($resSQLUltComprobar_t->num_rows > 0) {
                                    $strCONDICION_t .= condiciones_mysql("G".$intBase_t."_C".$campFil_t,$strOperador_t,$tipo_t,$valor_t,$condicion_t, $cierre_t); 
                                }

                            }
                        }else{
                            if ($campFil_t != "_FechaInsercion") {
                                $strCONDICION_t .= condiciones_mysql("G".$intBase_t."_M".$intMuestra_t.$campFil_t,$strOperador_t,$tipo_t,$valor_t,$condicion_t, $cierre_t);
                            }else{
                                $strCONDICION_t .= condiciones_mysql("G".$intBase_t."_FechaInsercion",$strOperador_t,$tipo_t,$valor_t,$condicion_t, $cierre_t);
                            }
                        }
                    }
                }

                if ($strCONDICION_t != "") {

                    // Hago un formateo para que las condiciones queden dentro de un parentesis
                    $strCONDICION_t = "AND TRUE AND " . $strCONDICION_t ;

                    // Esta parte ya es inecesaria debido a que ahora ya no se recibe el primer AND del fomulario
                    $strCONDICION_t = str_replace("AND TRUE AND", " AND (", $strCONDICION_t);
                    $strCONDICION_t = str_replace("AND TRUE OR", " OR (", $strCONDICION_t);
                    $strCONDICION_t = $strCONDICION_t . " )";

                    if ($strUPDATE_B_t != "") {

                        $strUPDATE_B_t = $strUPDATE_B_t.$strCONDICION_t;

                        if ($mysqli->query($strUPDATE_B_t)) {
                            guardar_auditoria("AdmRegistrosGestionarBD","POB: G".$intBase_t." TipL: ACTUALIZACION APLICADA CON CONDICION | ".$strUPDATE_B_t);
                            echo "Exito";
                        }else{
                            echo "Fallo";
                        }

                    }

                    if ($strUPDATE_M_t != "") {

                        $strUPDATE_M_t = $strUPDATE_M_t.$strCONDICION_t;

                        if ($mysqli->query($strUPDATE_M_t)) {
                            guardar_auditoria("AdmRegistrosGestionarBD","POB: G".$intBase_t."_M".$intMuestra_t." TipL: ACTUALIZACION APLICADA CON CONDICION | ".$strUPDATE_M_t);
                            echo "Exito";
                        }else{
                            echo "Fallo";
                        }
                    }

                    if(isset($sqlInsertMuestra_t)){
                        $sqlInsertMuestra_t=$sqlInsertMuestra_t.$strCONDICION_t;
                        if($mysqli->query($sqlInsertMuestra_t)){
                            guardar_auditoria("AdmRegistrosGestionarBD","POB: G".$intBase_t."_M".$intMuestra_t." TipL: INSERCION APLICADA CON CONDICION | ".$sqlInsertMuestra_t);
                            echo "Exito";
                        }else{
                            echo "Fallo";
                        }
                    }

                    if(isset($sqlDeleteMuestra)){
                        $sqlDeleteMuestra=$sqlDeleteMuestra.$strCONDICION_t;
                        if($mysqli->query($sqlDeleteMuestra)){
                            guardar_auditoria("AdmRegistrosGestionarBD","POB: G".$intBase_t."_M".$intMuestra_t." TipL: ELIMINAR REGISTROS CON CONDICION | ".$sqlDeleteMuestra);
                            echo "Exito";
                        }else{
                            echo "Fallo";
                        }
                    }
                }else{
                    echo "error_2";
                }
            }

            if ($intRadioCondiciones_t == 3) {
                error_reporting(E_ALL ^ E_NOTICE);
                $intCampoAFiltrar_t = $_POST["campo_a_filtrar"];
                $strFileName_t = $_FILES['listaExcell']['name'];
                $filFile_t  = $_FILES['listaExcell']['tmp_name'];
                $strFileType_t  = $_FILES['listaExcell']['type'];

                if ($filFile_t && $intCampoAFiltrar_t != "0" && $intCampoAFiltrar_t != "-1") {

                    if($strFileType_t == 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'){
                        $objReader = new PHPExcel_Reader_Excel2007();
                        $objReader->setReadDataOnly(true);
                        $obj_excel = $objReader->load($filFile_t);
                    }else if($strFileType_t == 'application/vnd.ms-excel'){
                        $obj_excel = PHPExcel_IOFactory::load($filFile_t);
                    }

                    $sheetData = $obj_excel->getActiveSheet()->toArray(null,true,true,true);
                    $highestColumm = $obj_excel->setActiveSheetIndex(0)->getHighestColumn(); // e.g. "EL"
                    $highestRow = $obj_excel->setActiveSheetIndex(0)->getHighestRow();

                    $intTipoCampo_t = 0;

                    if ($intCampoAFiltrar_t != "_ConsInte__b" && $intCampoAFiltrar_t != 0 && $intCampoAFiltrar_t != -1) {
                        $strTipoCampo_t = "SELECT PREGUN_Tipo______b FROM ".$BaseDatos_systema.".PREGUN WHERE PREGUN_ConsInte__b = ".$intCampoAFiltrar_t;

                        $resTipoCampo_t = $mysqli->query($strTipoCampo_t);

                        if ($resTipoCampo_t->num_rows > 0) {
                            $objTipoCampo_t = $resTipoCampo_t->fetch_object();    
                            $intTipoCampo_t = $objTipoCampo_t->PREGUN_Tipo______b; 
                        }

                        $intCampoAFiltrar_t = "G".$intBase_t."_C".$intCampoAFiltrar_t;

                    }elseif($intCampoAFiltrar_t == "_ConsInte__b"){
                        $intTipoCampo_t = 3;
                        $intCampoAFiltrar_t = "G".$intBase_t."_ConsInte__b";
                    }else{
                        echo "error_4";
                    }

                    $strIN_t = "";

                    if (count($sheetData)>0) {
                        
                        $strIN_t .= " AND (";
                        foreach ($sheetData as $index => $value) {
                            if($value["A"] != ''){
                                $strIN_t .= "TRIM(".$intCampoAFiltrar_t.") = '".trim($value["A"])."' OR ";
                            }

                        }
                        $strIN_t = substr($strIN_t, 0, -3);
                        $strIN_t .= ") ";
                    }

                    if ($strIN_t != "") {

                        if ($strUPDATE_B_t != "") {

                            $strUPDATE_B_t = $strUPDATE_B_t.$strIN_t;

                            if ($mysqli->query($strUPDATE_B_t)) {
                                guardar_auditoria("AdmRegistrosGestionarBD","POB: G".$intBase_t." TipL: ACTUALIZACION APLICADA CON LISTA DE REGISTROS | ".$strUPDATE_B_t);
                                echo "Exito";
                            }

                        }

                        if ($strUPDATE_M_t != "") {

                            $strUPDATE_M_t = $strUPDATE_M_t.$strIN_t;

                            if ($mysqli->query($strUPDATE_M_t)) {
                                guardar_auditoria("AdmRegistrosGestionarBD","POB: G".$intBase_t."_M".$intMuestra_t." TipL: ACTUALIZACION APLICADA CON LISTA DE REGISTOS | ".$strUPDATE_M_t);
                                echo "Exito";
                            }
                        }

                        if(isset($sqlInsertMuestra_t)){
                            $sqlInsertMuestra_t= $sqlInsertMuestra_t.$strIN_t;
                            if($mysqli->query($sqlInsertMuestra_t)){
                                guardar_auditoria("AdmRegistrosGestionarBD","POB: G".$intBase_t."_M".$intMuestra_t." TipL: INSERTAR REGISTROS CON LISTA DE REGISTROS| ".$sqlInsertMuestra_t);
                                echo "Exito";
                            }
                        }
        
                        if(isset($sqlDeleteMuestra)){
                            $sqlDeleteMuestra= $sqlDeleteMuestra.$strIN_t;
                            if($mysqli->query($sqlDeleteMuestra)){
                                guardar_auditoria("AdmRegistrosGestionarBD","POB: G".$intBase_t."_M".$intMuestra_t." TipL: ELIMINAR REGISTROS CON LISTA DE REGISTROS| ".$sqlDeleteMuestra);
                                echo "Exito";                                
                            }
                        }
                        
                    }else{
                        echo "error_5";
                    }

                }else{
                    echo "error_4";
                }
            }

        }else{
            echo "error_1";
        }

        
    }
}

function sanear_string($string) {

    // $string = utf8_decode($string);

    $string = str_replace(array('á', 'à', 'ä', 'â', 'ª', 'Á', 'À', 'Â', 'Ä'), array('a', 'a', 'a', 'a', 'a', 'A', 'A', 'A', 'A'), $string);
    $string = str_replace(array('é', 'è', 'ë', 'ê', 'É', 'È', 'Ê', 'Ë'), array('e', 'e', 'e', 'e', 'E', 'E', 'E', 'E'), $string);
    $string = str_replace(array('í', 'ì', 'ï', 'î', 'Í', 'Ì', 'Ï', 'Î'), array('i', 'i', 'i', 'i', 'I', 'I', 'I', 'I'), $string);
    $string = str_replace(array('ó', 'ò', 'ö', 'ô', 'Ó', 'Ò', 'Ö', 'Ô'), array('o', 'o', 'o', 'o', 'O', 'O', 'O', 'O'), $string);
    $string = str_replace(array('ú', 'ù', 'ü', 'û', 'Ú', 'Ù', 'Û', 'Ü'), array('u', 'u', 'u', 'u', 'U', 'U', 'U', 'U'), $string);
    $string = str_replace(array('ñ', 'Ñ', 'ç', 'Ç'), array('n', 'N', 'c', 'C',), $string);
    //Esta parte se encarga de eliminar cualquier caracter extraño
    $string = str_replace(array("\\", "¨", "º", "-", "~", "#", "@", "|", "!", "\"", "·", "$", "%", "&", "/", "(", ")", "?", "'", "¡", "¿", "[", "^", "`", "]", "+", "}", "{", "¨", "´", ">“, “< ", ";", ",", ":", "."), '', $string);
    return $string;
}

function crearSeccionesBD($ultimoGuion,$tipoGuion=0) {
    global $mysqli;
    global $BaseDatos_systema;

    //Seccio control
    $Lsql_Control = "INSERT INTO " . $BaseDatos_systema . ".G7(G7_C60, G7_C38, G7_C36 , G7_C34, G7_C33, G7_C35) VALUES(" . $ultimoGuion . ", 4, 1, 2, 'CONTROL', 1)";

    if ($mysqli->query($Lsql_Control) === true) {
        $control = $mysqli->insert_id;
        $Lsql_Agente_origen = "INSERT INTO " . $BaseDatos_systema . ".PREGUN(PREGUN_Texto_____b, PREGUN_Tipo______b, PREGUN_IndiRequ__b, PREGUN_ConsInte__SECCIO_b, PREGUN_ConsInte__GUION__b, PREGUN_ContAcce__b, PREGUN_FueGener_b ) VALUES ('ORIGEN_DY_WF', 1, 0, " . $control . ", " . $ultimoGuion . ", 2, 1);";
        if ($mysqli->query($Lsql_Agente_origen) === true) {
            
        }

        $Lsql_Agente_OPtin = "INSERT INTO " . $BaseDatos_systema . ".PREGUN(PREGUN_Texto_____b, PREGUN_Tipo______b, PREGUN_IndiRequ__b, PREGUN_ConsInte__SECCIO_b, PREGUN_ConsInte__GUION__b, PREGUN_ContAcce__b, PREGUN_FueGener_b ) VALUES ('OPTIN_DY_WF', 1, 0, " . $control . ", " . $ultimoGuion . ", 2, 1);";
        if ($mysqli->query($Lsql_Agente_OPtin) === true) {
            
        }

        $insertLsql = "INSERT INTO " . $BaseDatos_systema . ".OPCION (OPCION_ConsInte__GUION__b, OPCION_Nombre____b, OPCION_ConsInte__PROYEC_b, OPCION_FechCrea__b, OPCION_UsuaCrea__b) VALUES (" . $ultimoGuion . ", 'ESTADO_DY_" . $ultimoGuion . "', " . $_SESSION['HUESPED'] . ", '" . date('Y-m-d H:s:i') . "', " . $_SESSION['IDENTIFICACION'] . ");";
        $ultimoLista = 0;
        if ($mysqli->query($insertLsql) === true) {
            $ultimoLista = $mysqli->insert_id;
            $array = array('1. No aplica', '2. Sin definir ', '3. No interesado', '4. Interesado', '5. Oportunidad', '6. No exitoso', '7. Exitoso');

            for ($i = 0; $i < 7; $i++) {
                $insertLisopc = "INSERT INTO " . $BaseDatos_systema . ".LISOPC (LISOPC_Nombre____b, LISOPC_ConsInte__OPCION_b, LISOPC_Posicion__b) VALUES ('" . $array[$i] . "', " . $ultimoLista . ", " . $i . ");";
                if ($mysqli->query($insertLisopc) === true) {
                    
                } else {
                    echo $mysqli->error;
                }
            }

            /* ahora insert la pregunta ESTADO_DY */
            $Lsql_Estado_campo = "INSERT INTO " . $BaseDatos_systema . ".G6(G6_C39, G6_C40, G6_C51, G6_C32, G6_C207, G6_C209, G6_C44) VALUES ('ESTADO_DY', 6, 1, " . $control . ", " . $ultimoGuion . ", 1, " . $ultimoLista . ");";
            if ($mysqli->query($Lsql_Estado_campo) === true) {
                
            } else {
                echo "Error generando Estado " . $mysqli->error;
            }
        }
    }

    generar_tablas_bd($ultimoGuion, 1, 1, 1, 1,$tipoGuion);
}

function crearSecciones($ultimoGuion, $nombre, $guionBd, $tipoGuion=0) {

        global $mysqli;
        global $BaseDatos_systema;
    //* una vez creada la tabla procedemos a generar lo que toca generar */
    // include(__DIR__."../../../../generador/generar_tablas_bd.php");
    /*
      El tipo de Guión debe ser script.
      Primero debemos crear la seccion Tipificación
     */

    $Lsql_Tipificacion = "INSERT INTO " . $BaseDatos_systema . ".G7(G7_C60, G7_C38, G7_C36 , G7_C34, G7_C33) VALUES(" . $ultimoGuion . ", 3, 1, 4, 'TIPIFICACION')";

    if ($mysqli->query($Lsql_Tipificacion) === true) {

        $tipificacion = $mysqli->insert_id;

        $LsqlEstados_Search = "SELECT PREGUN_ConsInte__OPCION_B FROM " . $BaseDatos_systema . ".PREGUN WHERE PREGUN_ConsInte__GUION__b = " . $guionBd . " AND PREGUN_Texto_____b = 'ESTADO_DY'; ";
        $EstadoOpcion = $mysqli->query($LsqlEstados_Search);

        $tipificacionEstad1 = 0;
        $tipificacionEstad2 = 0;
        $tipificacionEstad3 = 0;
        $tipificacionEstad4 = 0;
        $tipificacionEstad5 = 0;
        $tipificacionEstad6 = 0;
        $tipificacionEstad7 = 0;


        if ($EstadoOpcion->num_rows > 0) {
            $datoOPcionEstado = $EstadoOpcion->fetch_array();
            $LsqlEstados = "SELECT LISOPC_ConsInte__b as id, LISOPC_Nombre____b as texto FROM " . $BaseDatos_systema . ".LISOPC WHERE LISOPC_ConsInte__OPCION_b = " . $datoOPcionEstado['PREGUN_ConsInte__OPCION_B'] . " ORDER BY LISOPC_Nombre____b ASC";
            $resOpcionesEstado = $mysqli->query($LsqlEstados);
            while ($resKey = $resOpcionesEstado->fetch_object()) {

                if ($resKey->texto == 'No contactable') {
                    $tipificacionEstad2 = $resKey->id;
                }

                if ($resKey->texto == 'No interesado') {
                    $tipificacionEstad3 = $resKey->id;
                }

                if ($resKey->texto == 'Interesado') {
                    $tipificacionEstad4 = $resKey->id;
                }

                if ($resKey->texto == 'No exitoso') {
                    $tipificacionEstad5 = $resKey->id;
                }

                if ($resKey->texto == 'Exitoso') {
                    $tipificacionEstad6 = $resKey->id;
                }
            }
        }

        /* priemro creamos la lista de las tipifiaciones */
        $insertLsql = "INSERT INTO " . $BaseDatos_systema . ".OPCION (OPCION_ConsInte__GUION__b, OPCION_Nombre____b, OPCION_ConsInte__PROYEC_b, OPCION_FechCrea__b, OPCION_UsuaCrea__b) VALUES (" . $ultimoGuion . ", 'Tipificaciones - " . $nombre . "', " . $_SESSION['HUESPED'] . ", '" . date('Y-m-d H:s:i') . "', " . $_SESSION['IDENTIFICACION'] . ");";
        //MONOEF_Texto_____b , MONOEF_Contacto__b,  MONOEF_TipNo_Efe_b, LISOPC_Clasifica_b
        $array = array(
            array('No contesta', 4, 1, 3, 6, 0),
            array('Ocupado', 4, 1, 4, 2, 0),
            array('Fallida', 2, 3, 5, 0, $tipificacionEstad2),
            array('No lo conocen', 5, 3, 0, 0, $tipificacionEstad2),
            array('Llamar luego', 6, 2, 0, 6, $tipificacionEstad4),
            array('No exitoso ', 6, 3, 0, 0, $tipificacionEstad5),
            array('Exitoso', 7, 3, 0, 0, $tipificacionEstad6)
        );
        $tamanho = 7;

        $ultimoLista = 0;
        if ($mysqli->query($insertLsql) === true) {
            /* Se inserto la lista perfectamente */
            $ultimoLista = $mysqli->insert_id;
            /* toca meterlo en MONOEF */
            /* Primero lo pirmero crear el MonoEf */
            for ($i = 0; $i < $tamanho; $i++) {

                $MONOEFLsql = "INSERT INTO " . $BaseDatos_systema . ".MONOEF (MONOEF_Texto_____b, MONOEF_EFECTIVA__B, MONOEF_TipNo_Efe_b, MONOEF_Importanc_b, MONOEF_FechCrea__b, MONOEF_UsuaCrea__b, MONOEF_Contacto__b, MONOEF_CanHorProxGes__b, MONOEF_TipiCBX___b) VALUES ('" . $array[$i][0] . "','0', '" . $array[$i][2] . "', '" . ($i + 1) . "' , '" . date('Y-m-d H:s:i') . "', " . $_SESSION['IDENTIFICACION'] . ", '" . $array[$i][1] . "' , '" . $array[$i][4] . "' , '" . $array[$i][5] . "')";

                if ($mysqli->query($MONOEFLsql) === true) {
                    $monoefNew = $mysqli->insert_id;
                    /* ahora si lo insertamos en el LISOPC */
                    $insertLisopc = "INSERT INTO " . $BaseDatos_systema . ".LISOPC (LISOPC_Nombre____b, LISOPC_ConsInte__OPCION_b, LISOPC_Posicion__b, LISOPC_Clasifica_b, LISOPC_Valor____b) VALUES ('" . $array[$i][0] . "', " . $ultimoLista . ", 0, " . $monoefNew . ", '" . $array[$i][5] . "');";
                    if ($mysqli->query($insertLisopc) === true) {
                        
                    } else {
                        echo $mysqli->error;
                    }
                } else {
                    echo $mysqli->error;
                }
            }
        } else {
            
        }

        /* Ahora toca crear los campos de la tipificacion */
        $int_Tipificacion_campo = 0;
        $int_Reintento_campo = 0;
        $int_Fecha_Agenda_campo = 0;
        $int_Hora_Agenda_campo = 0;
        $int_Observacion_campo = 0;

        $Lsql_Tipificacion_campo = "INSERT INTO " . $BaseDatos_systema . ".G6(G6_C39, G6_C40, G6_C51, G6_C32, G6_C207, G6_C209, G6_C44) VALUES ('Tipificación', 6, 1, " . $tipificacion . ", " . $ultimoGuion . ", 1, " . $ultimoLista . ");";
        if ($mysqli->query($Lsql_Tipificacion_campo) === true) {
            $int_Tipificacion_campo = $mysqli->insert_id;
        }

        $Lsql_Reintento_campo = "INSERT INTO " . $BaseDatos_systema . ".G6(G6_C39, G6_C40, G6_C51, G6_C32, G6_C207, G6_C209) VALUES ('Reintento', 6, 1, " . $tipificacion . ", " . $ultimoGuion . ", 1);";
        if ($mysqli->query($Lsql_Reintento_campo) === true) {
            $int_Reintento_campo = $mysqli->insert_id;
        }

        $Lsql_Fecha_Agenda_campo = "INSERT INTO " . $BaseDatos_systema . ".G6(G6_C39, G6_C40, G6_C51, G6_C32, G6_C207, G6_C209) VALUES ('Fecha Agenda', 5, 1, " . $tipificacion . ", " . $ultimoGuion . ", 1);";
        if ($mysqli->query($Lsql_Fecha_Agenda_campo) === true) {
            $int_Fecha_Agenda_campo = $mysqli->insert_id;
        }

        $Lsql_Hora_Agenda_campo = "INSERT INTO " . $BaseDatos_systema . ".G6(G6_C39, G6_C40, G6_C51, G6_C32, G6_C207, G6_C209) VALUES ('Hora Agenda', 10, 1, " . $tipificacion . ", " . $ultimoGuion . ", 1);";
        if ($mysqli->query($Lsql_Hora_Agenda_campo) === true) {
            $int_Hora_Agenda_campo = $mysqli->insert_id;
        }

        $Lsql_Observacion_campo = "INSERT INTO " . $BaseDatos_systema . ".G6(G6_C39, G6_C40, G6_C51, G6_C32, G6_C207, G6_C209) VALUES ('Observacion', 2, 1, " . $tipificacion . ", " . $ultimoGuion . " , 1);";
        if ($mysqli->query($Lsql_Observacion_campo) === true) {
            $int_Observacion_campo = $mysqli->insert_id;
        }


        $Lsql_Editar_Guion = "UPDATE " . $BaseDatos_systema . ".G5 SET G5_C313 = " . $int_Fecha_Agenda_campo . ", G5_C314 = " . $int_Hora_Agenda_campo . " , G5_C315 = " . $int_Observacion_campo . " , G5_C311 = " . $int_Tipificacion_campo . " , G5_C312 = " . $int_Reintento_campo . " WHERE G5_ConsInte__b = " . $ultimoGuion;

        if ($mysqli->query($Lsql_Editar_Guion) !== true) {
            echo "error => " . $mysqli->error;
        }
    } else {
        echo "TipificacioM  " . $mysqli->error;
    }

    //Seccio control
    $Lsql_Control = "INSERT INTO " . $BaseDatos_systema . ".G7(G7_C60, G7_C38, G7_C36 , G7_C34, G7_C33, G7_C35) VALUES(" . $ultimoGuion . ", 4, 1, 2, 'CONTROL', 1)";

    if ($mysqli->query($Lsql_Control) === true) {
        $control = $mysqli->insert_id;

        /* insertar todos los campos de control */
        //Agente:texto con valor por defecto nombre del agente activo, fecha:fecha con valor por defecto fecha actual, hora:hora con valor por defecto hora actual, campaña:texto con valor por defecto nombre de la campaña activa
        //PREGUN_Default___b
        //PREGUN_ContAcce__b
        $int_Agente_campo=null;
        $int_Fecha_campo=null;
        $int_Hora_campo=null;
        $Lsql_Agente_campo = "INSERT INTO " . $BaseDatos_systema . ".PREGUN(PREGUN_Texto_____b, PREGUN_Tipo______b, PREGUN_IndiRequ__b, PREGUN_ConsInte__SECCIO_b, PREGUN_ConsInte__GUION__b, PREGUN_Default___b , PREGUN_ContAcce__b, PREGUN_FueGener_b ) VALUES ('Agente', 1, 0, " . $control . ", " . $ultimoGuion . ", 102, 2, 1);";
        if ($mysqli->query($Lsql_Agente_campo) === true) {
             $int_Agente_campo = $mysqli->insert_id;
        }

        $Lsql_fecha_campo = "INSERT INTO " . $BaseDatos_systema . ".PREGUN(PREGUN_Texto_____b, PREGUN_Tipo______b, PREGUN_IndiRequ__b, PREGUN_ConsInte__SECCIO_b, PREGUN_ConsInte__GUION__b, PREGUN_Default___b , PREGUN_ContAcce__b , PREGUN_FueGener_b) VALUES ('Fecha', 1, 0, " . $control . ", " . $ultimoGuion . ", 501, 2, 1);";
        if ($mysqli->query($Lsql_fecha_campo) === true) {
             $int_Fecha_campo = $mysqli->insert_id;
        }


        $Lsql_hora_campo = "INSERT INTO " . $BaseDatos_systema . ".PREGUN(PREGUN_Texto_____b, PREGUN_Tipo______b, PREGUN_IndiRequ__b, PREGUN_ConsInte__SECCIO_b, PREGUN_ConsInte__GUION__b, PREGUN_Default___b , PREGUN_ContAcce__b , PREGUN_FueGener_b) VALUES ('Hora', 1, 0, " . $control . ", " . $ultimoGuion . ", 1001, 2, 1);";
        if ($mysqli->query($Lsql_hora_campo) === true) {
            $int_Hora_campo = $mysqli->insert_id;
        }

        $Lsql_campa_campo = "INSERT INTO " . $BaseDatos_systema . ".PREGUN(PREGUN_Texto_____b, PREGUN_Tipo______b, PREGUN_IndiRequ__b, PREGUN_ConsInte__SECCIO_b, PREGUN_ConsInte__GUION__b, PREGUN_Default___b , PREGUN_ContAcce__b , PREGUN_FueGener_b) VALUES ('Campaña', 1, 0, " . $control . ", " . $ultimoGuion . ", 105, 2, 1);";
        if ($mysqli->query($Lsql_campa_campo) === true) {
            
        }

         $Lsql="UPDATE  ".$BaseDatos_systema.".GUION_ 
            SET GUION__ConsInte__PREGUN_Age_b = ".$int_Agente_campo.",GUION__ConsInte__PREGUN_Fec_b = ". $int_Fecha_campo.",GUION__ConsInte__PREGUN_Hor_b = ".$int_Hora_campo."
            WHERE GUION__ConsInte__b =".$ultimoGuion;

            if($mysqli->query($Lsql) === true){
            
            }
    } else {
        echo "Control  " . $mysqli->error;
    }


    /* seccion para calidad */

    $Lsql_Calidad = "INSERT INTO " . $BaseDatos_systema . ".G7(G7_C60, G7_C38, G7_C36 , G7_C34, G7_C33, G7_C35) VALUES(" . $ultimoGuion . ", 2, 1, 3, 'CALIDAD', 2)";
    if ($mysqli->query($Lsql_Calidad) === true) {
        $calidad = $mysqli->insert_id;

        /* insetar todos los campos de calidad */
    } else {
        echo "Calidad  " . $mysqli->error;
    }


    //Seccio coonversacion
    $Lsql_Converacion = "INSERT INTO " . $BaseDatos_systema . ".G7(G7_C60, G7_C38, G7_C36 , G7_C34, G7_C33, G7_C35) VALUES(" . $ultimoGuion . ", 1, 1, 5, 'CONVERSACION', 1)";

    if ($mysqli->query($Lsql_Converacion) === true) {
        $control = $mysqli->insert_id;

        /* insertar todos los campos de control */
        //Agente:texto con valor por defecto nombre del agente activo, fecha:fecha con valor por defecto fecha actual, hora:hora con valor por defecto hora actual, campaña:texto con valor por defecto nombre de la campaña activa
        //PREGUN_Default___b
        //PREGUN_ContAcce__b
        $Lsql_Agente_campo = "INSERT INTO " . $BaseDatos_systema . ".PREGUN(PREGUN_Texto_____b, PREGUN_Tipo______b, PREGUN_IndiRequ__b, PREGUN_ConsInte__SECCIO_b, PREGUN_ConsInte__GUION__b,  PREGUN_ContAcce__b, PREGUN_FueGener_b ) VALUES ('Buenos días|tardes|noches, podría comunicarme con el señor(a) |NombreCliente|', 9, 0, " . $control . ", " . $ultimoGuion . ", 2, 1);";
        if ($mysqli->query($Lsql_Agente_campo) === true) {
            
        } else {
            echo "Error conversacion => " . $mysqli->error;
        }

        $Lsql_Agente_campo = "INSERT INTO " . $BaseDatos_systema . ".PREGUN(PREGUN_Texto_____b, PREGUN_Tipo______b, PREGUN_IndiRequ__b, PREGUN_ConsInte__SECCIO_b, PREGUN_ConsInte__GUION__b,  PREGUN_ContAcce__b, PREGUN_FueGener_b ) VALUES ('Mi nombre es |Agente|, le estoy llamando de |Empresa| con el fin de ...', 9, 0, " . $control . ", " . $ultimoGuion . ", 2, 1);";
        if ($mysqli->query($Lsql_Agente_campo) === true) {
            
        } else {
            echo "Error conversacion => " . $mysqli->error;
        }
    } else {
        echo "Control  " . $mysqli->error;
    }

    generar_tablas_bd($ultimoGuion, 1, 1, 0, 0,$tipoGuion);
}

?>