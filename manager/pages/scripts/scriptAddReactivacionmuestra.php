<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once("../conexion.php");




$strSQLidTablaMuestra = "SELECT MUESTR_ConsInte__b, MUESTR_ConsInte__GUION__b FROM {$BaseDatos_systema}.MUESTR order by MUESTR_ConsInte__b desc;";
$resSQLidTablaMuestra = $mysqli->query($strSQLidTablaMuestra);

if ($resSQLidTablaMuestra) {
    if ($resSQLidTablaMuestra->num_rows > 0) {
        while ($idMuestra = $resSQLidTablaMuestra->fetch_object()) {

            // primero validamos si existe la muestra

            $muestraName = "G{$idMuestra->MUESTR_ConsInte__GUION__b}_M{$idMuestra->MUESTR_ConsInte__b}";

            if (validTableExist($muestraName)) {

                if(validColumnExist($muestraName)){
                    // Si existe se hace un alter para el default

                    $strSQLAlterFechaReactivacion= "ALTER TABLE " . $BaseDatos . ".".$muestraName." MODIFY ".$muestraName."_FechaReactivacion_b datetime DEFAULT NOW();";
                    $resSQLAlterFechaReactivacion = $mysqli->query($strSQLAlterFechaReactivacion);

                    if ($resSQLAlterFechaReactivacion) {
                        echo "Se modifico la columnas " . $muestraName . "_FechaReactivacion_b </br>";
                    } else {
                        echo "Error al modificar la columna " . $muestraName . "_FechaReactivacion_b : " . $mysqli->error . "</br></br>";
                    }


                    $strSQLAlterFechaCreacion = "ALTER TABLE " . $BaseDatos . ".".$muestraName." MODIFY ".$muestraName."_FechaCreacion_b datetime DEFAULT NOW();";
                    $resSQLAlterFechaCreacion = $mysqli->query($strSQLAlterFechaCreacion);

                    if ($resSQLAlterFechaCreacion) {
                        echo "Se modifico la columnas " . $muestraName . "_FechaCreacion_b </br>";
                    } else {
                        echo "Error al modificar la columna " . $muestraName . "_FechaCreacion_b : " . $mysqli->error . "</br></br>";
                    }

                }else{
                    // Si no existe se crea

                    $strSQLAddReactivacion = "ALTER TABLE " . $BaseDatos . "." . $muestraName . " ADD " . $muestraName . "_FechaCreacion_b datetime DEFAULT NOW() AFTER " . $muestraName . "_Activo____b, ADD " . $muestraName . "_FechaReactivacion_b datetime DEFAULT NOW() AFTER " . $muestraName . "_FechaCreacion_b";
                    $resSQLAddReactivacion = $mysqli->query($strSQLAddReactivacion);

                    if ($resSQLAddReactivacion) {
                        echo "Se adicionaron la columnas " . $muestraName . "_FechaCreacion_b y  " . $muestraName . "_FechaReactivacion_b </br>";
                    } else {
                        echo "Error al adicionar la columna " . $muestraName . "_FechaCreacion_b y  " . $muestraName . "_FechaReactivacion_b : " . $mysqli->error . "</br></br>";
                    }
                }

            }
        }
    }
}


function validTableExist($muestraName)
{
    global $mysqli;

    $strSQLValidTable = "SELECT TABLE_SCHEMA, TABLE_NAME,TABLE_TYPE FROM information_schema.TABLES WHERE TABLE_SCHEMA LIKE 'DYALOGOCRM_WEB' AND  TABLE_TYPE LIKE 'BASE TABLE' AND TABLE_NAME = '$muestraName';";
    $resSQLValidTable = $mysqli->query($strSQLValidTable);

    if ($resSQLValidTable && $resSQLValidTable->num_rows > 0) {
        return true;
    }

    return false;
}


function validColumnExist($muestraName)
{
    global $mysqli;

    $strSQLValidColumn = "SHOW COLUMNS FROM DYALOGOCRM_WEB.{$muestraName} LIKE '{$muestraName}_FechaReactivacion_b';";
    $resSQLValidColumn = $mysqli->query($strSQLValidColumn);

    if ($resSQLValidColumn && $resSQLValidColumn->num_rows > 0) {
        return true;
    }

    return false;
}
