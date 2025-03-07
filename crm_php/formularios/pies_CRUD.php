<?php
session_start();
ini_set('display_errors', 'On');
ini_set('display_errors', 1);
include("../conexion.php");
require_once ("../../helpers/parameters.php");
date_default_timezone_set('America/Bogota');

function limpiarNombre($strNombre_p){

    $strPatron_t = "/[\<\>\&\'\"]/";

    return preg_replace($strPatron_t, "", $strNombre_p);

}

function guardar_auditoria($accion, $superAccion){
    global $mysqli;
    global $BaseDatos;
    global $BaseDatos_systema;
    global $BaseDatos_telefonia;
    global $dyalogo_canales_electronicos;
    global $BaseDatos_general;
    $str_Lsql = "INSERT INTO ".$BaseDatos_systema.".AUACAD (AUACAD_Fecha_____b , AUACAD_Hora______b, AUACAD_Ejecutor__b, AUACAD_TipoAcci__b , AUACAD_SubTipAcc_b, AUACAD_Accion____b , AUACAD_Huesped___b ) VALUES ('".date('Y-m-d H:i:s')."', '".date('Y-m-d H:i:s')."', ".$_SESSION['IDENTIFICACION'].", 'BD', '".$accion."', '".$superAccion."', ".$_SESSION['HUESPED']." );";
    $mysqli->query($str_Lsql);
}

if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {

    if (isset($_GET["traerOpcionesLista"])) {

        $intIdCampo_t = $_POST["intIdCampo_t"];

        //JDBD-2020-05-03 : Traemos las opciones de la lista.
        $strSQLOpcionesLista_t = "SELECT C.LISOPC_ConsInte__b AS id, C.LISOPC_Nombre____b AS opcion FROM ".$BaseDatos_systema.".PREGUN A JOIN ".$BaseDatos_systema.".OPCION B ON A.PREGUN_ConsInte__OPCION_B = B.OPCION_ConsInte__b JOIN ".$BaseDatos_systema.".LISOPC C ON B.OPCION_ConsInte__b = C.LISOPC_ConsInte__OPCION_b WHERE A.PREGUN_ConsInte__b = ".$intIdCampo_t;

        $resSQLOpcionesLista_t = $mysqli->query($strSQLOpcionesLista_t);

        echo '<option value="0">Seleccione</option>';
        while ($row = $resSQLOpcionesLista_t->fetch_object()) {
            echo '<option value="'.$row->id.'">'.$row->opcion.'</option>';
        }

    }

    if (isset($_GET["traerCamposDelGuion"])) {

        $intIdGuion_t = $_POST["intIdGuion_t"];

        //JDBD-2020-05-03 : Verificamos que el cargo que tiene el usuario en sesion tenga permisos de calidad para traer los campos de dicho seccion.
        if ($_POST["strSessionCargo_t"] == "calidad" || $_POST["strSessionCargo_t"] == "administrador" || $_POST["strSessionCargo_t"] == "super-administrador") {
            $strSQLCampos_t = "SELECT PREGUN_ConsInte__b AS id, PREGUN_Texto_____b AS nombre, PREGUN_Tipo______b AS tipo FROM ".$BaseDatos_systema.".PREGUN JOIN DYALOGOCRM_SISTEMA.SECCIO ON PREGUN_ConsInte__SECCIO_b = SECCIO_ConsInte__b WHERE SECCIO_TipoSecc__b != 4 AND PREGUN_ConsInte__GUION__b = ".$intIdGuion_t." AND PREGUN_Tipo______b NOT IN (12,9,8,13,11) AND PREGUN_Texto_____b NOT IN ('ORIGEN_DY_WF','OPTIN_DY_WF','ESTADO_DY') ORDER BY PREGUN_ConsInte__b ASC";
        }else{
            $strSQLCampos_t = "SELECT PREGUN_ConsInte__b AS id, PREGUN_Texto_____b AS nombre, PREGUN_Tipo______b AS tipo FROM ".$BaseDatos_systema.".PREGUN JOIN DYALOGOCRM_SISTEMA.SECCIO ON PREGUN_ConsInte__SECCIO_b = SECCIO_ConsInte__b WHERE SECCIO_TipoSecc__b NOT IN (2,4) AND PREGUN_ConsInte__GUION__b = ".$intIdGuion_t." AND PREGUN_Tipo______b NOT IN (12,9,8,13,11) AND PREGUN_Texto_____b NOT IN ('ORIGEN_DY_WF','OPTIN_DY_WF','ESTADO_DY') ORDER BY PREGUN_ConsInte__b ASC";
        }

            

        $resSQLCampos_t = $mysqli->query($strSQLCampos_t);

        $arrDatosCampos_t = [];

        echo '<option value="0" tipo="3">Seleccione</option>';
        echo '<option value="G'.$intIdGuion_t.'_FechaInsercion" tipo="5">Fecha creacion</option>';
        while ($row = $resSQLCampos_t->fetch_object()) {
            echo '<option value="'.$row->id.'" tipo="'.$row->tipo.'">'.limpiarNombre($row->nombre).'</option>';
        }

    }

    if(isset($_GET['getTipoGuion'])){
        $id=isset($_POST['id']) ? $_POST['id'] : false;

        if($id){
            $where="GUION__ConsInte__b={$id}";
            $sql=$mysqli->query("SELECT GUION__Tipo______b FROM {$BaseDatos_systema}.GUION_ WHERE {$where}");
            if($sql && $sql->num_rows == 1){
                $sql=$sql->fetch_object();
                echo $sql->GUION__Tipo______b;
            }else{
                echo "No se identifico el tipo de guion";
            }
        }else{
            echo "No se identifico el tipo de guion";
        }
    }

    if(isset($_POST['deleteAllEstrat'])){
        $id=isset($_POST['id']) ? $_POST['id'] : false;
        $bd=isset($_POST['bd']) ? $_POST['bd'] : false;

        if($id && $bd){
            $where="ESTRAT_ConsInte_GUION_Pob={$bd}";

            $sqlEstrat=$mysqli->query("SELECT ESTRAT_ConsInte__b,ESTRAT_ConsInte_GUION_Pob FROM {$BaseDatos_systema}.ESTRAT WHERE {$where}");
            if($sqlEstrat && $sqlEstrat->num_rows > 0){
                while($row = $sqlEstrat->fetch_object()){
                    $sqlEstrat=$mysqli->query("SELECT CASE WHEN ESTPAS_ConsInte__MUESTR_b IS NOT NULL AND CAMPAN_ConsInte__MUESTR_b IS NOT NULL THEN ESTPAS_ConsInte__MUESTR_b WHEN ESTPAS_ConsInte__MUESTR_b IS NOT NULL AND CAMPAN_ConsInte__MUESTR_b IS NULL THEN ESTPAS_ConsInte__MUESTR_b WHEN ESTPAS_ConsInte__MUESTR_b IS NULL AND CAMPAN_ConsInte__MUESTR_b IS NOT NULL THEN CAMPAN_ConsInte__MUESTR_b END AS C FROM DYALOGOCRM_SISTEMA.ESTPAS LEFT JOIN DYALOGOCRM_SISTEMA.CAMPAN ON ESTPAS_ConsInte__CAMPAN_b=CAMPAN_ConsInte__b WHERE ESTPAS_ConsInte__MUESTR_b IS NOT NULL OR CAMPAN_ConsInte__MUESTR_b IS NOT NULL AND ESTPAS_ConsInte__ESTRAT_b={$row->ESTRAT_ConsInte__b}");

                    if($sqlEstrat && $sqlEstrat->num_rows > 0){
                        while($rowE = $sqlEstrat->fetch_object()){
                            $muestra="G{$row->ESTRAT_ConsInte_GUION_Pob}_M{$rowE->C}";
                            $mysqli->query("DELETE FROM DYALOGOCRM_WEB.{$muestra} WHERE {$muestra}_CoInMiPo__b={$id}");
                        }
                    }
                }

                guardar_auditoria("ELIMINAR","ELIMINO EL REGISTRO #{$id} DE LA BASE DE DATOS #{$bd}");
                echo 'ok';
            }else{
                echo "No se identifico el registro a eliminar(1)";
            }
        }else{
            echo "No se identifico el registro a eliminar";
        }
    }
}

?>