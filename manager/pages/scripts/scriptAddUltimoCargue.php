<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once("../conexion.php");



if (isset($_GET["idG"])) {
    $idG = ($_GET["idG"] != null) ? $_GET["idG"] : "0";
    $valido = false;

    // Si mandan el G en parametro quiere decir que quieren actualizar la info con la que ya hay existente

    // Primero validamos si ya exiten las columnas si no pues se adicionan

    if($idG != 0){
        $strSqlVerifyColumns = "SHOW COLUMNS FROM DYALOGOCRM_WEB.G{$idG} LIKE 'G{$idG}_FechaUltimoCargue'";
        $resSqlVerifyColumns = $mysqli->query($strSqlVerifyColumns);
    
        if($resSqlVerifyColumns){
            if ($resSqlVerifyColumns->num_rows == 0) {
                //Si no existen se intenta crear
                $strSQLAlterFechaBD = "ALTER TABLE " . $BaseDatos . ".G" . $idG . " ADD G" . $idG . "_FechaUltimoCargue datetime DEFAULT NULL AFTER G" . $idG . "_FechaInsercion, ADD G" . $idG . "_OrigenUltimoCargue varchar(100) DEFAULT NULL AFTER G" . $idG . "_FechaUltimoCargue";
                $resSQLAlterFechaBD = $mysqli->query($strSQLAlterFechaBD);
    
                if ($resSQLAlterFechaBD) {
                    $valido = true;
                    echo "Se adicionaron la columnas G" . $idG . "_FechaUltimoCargue y  G" . $idG . "_OrigenUltimoCargue </br>";
                } else {
                    echo "Error al adicionar la columna G" . $idG . "_FechaUltimoCargue y  G" . $idG . "_OrigenUltimoCargue : " . $mysqli->error . "</br></br>";
                }
            }else{
                $valido = true;
            }
        }
    
    
        if($valido){

            $idCampoOrigen = 0; 

            // Ya cuando se valide si se llena la info con la que ya existe
            $strSqlCampoOrigen = "SELECT PREGUN_ConsInte__b AS id_origen from {$BaseDatos_systema}.PREGUN WHERE PREGUN_ConsInte__GUION__b = {$idG} AND PREGUN_Texto_____b = 'ORIGEN_DY_WF' limit 1";
            $resSqlCampoOrigen = $mysqli->query($strSqlCampoOrigen);
            if($resSqlCampoOrigen){
                if($resSqlCampoOrigen->num_rows > 0){
                    $idCampoOrigen = $resSqlCampoOrigen->fetch_object()->id_origen;
                }
            }


            if($idCampoOrigen != 0){
                $strSqlupdate = "UPDATE DYALOGOCRM_WEB.G{$idG} set G{$idG}_FechaUltimoCargue = G{$idG}_FechaInsercion, G{$idG}_OrigenUltimoCargue = G{$idG}_C{$idCampoOrigen};";
                $resSqlupdate = $mysqli->query($strSqlupdate);

                if($resSqlupdate){
                    echo "Se actualizo toda la informacion correctamente Num de filas afectadas: {$mysqli->affected_rows}";
                }else{
                    echo "No se pudo actualizar la info error: {$mysqli->error} </br></br>";
                }

            }
    
        }
    }


} else {
    // // BGCR - Se consultan todos los id de los huespedes

    $strSQLidTablaBD = "SELECT GUION__ConsInte__b AS idTablaDB FROM {$BaseDatos_systema}.GUION_ WHERE GUION__Tipo______b = 2";
    $resSQLidTablaBD = $mysqli->query($strSQLidTablaBD);

    if ($resSQLidTablaBD) {
        if ($resSQLidTablaBD->num_rows > 0) {
            while ($idTablaDB = $resSQLidTablaBD->fetch_object()->idTablaDB) {

                if(isset($_GET["alterDefault"])){
                    // Por cada id se ejecuta un alter para adicionar el defaul a la columnas GXXXX_FechaUltimoCargue
                    $strSQLAlterFechaBD = "ALTER TABLE " . $BaseDatos . ".G" . $idTablaDB . " MODIFY G" . $idTablaDB . "_FechaUltimoCargue datetime DEFAULT NOW();";
                    $resSQLAlterFechaBD = $mysqli->query($strSQLAlterFechaBD);

                    if ($resSQLAlterFechaBD) {
                        echo "Se modifico la columnas G" . $idTablaDB . "_FechaUltimoCargue </br>";
                    } else {
                        echo "Error al modificar la columna G" . $idTablaDB . "_FechaUltimoCargue : " . $mysqli->error . "</br></br>";
                    }
                }else{
                    // Por cada id se ejecuta un alter para adicionar las columnas GXXXX_FechaUltimoCargue y GXXXX_OrigenUltimoCargue 
                    $strSQLAlterFechaBD = "ALTER TABLE " . $BaseDatos . ".G" . $idTablaDB . " ADD G" . $idTablaDB . "_FechaUltimoCargue datetime DEFAULT NULL AFTER G" . $idTablaDB . "_FechaInsercion, ADD G" . $idTablaDB . "_OrigenUltimoCargue varchar(100) DEFAULT NULL AFTER G" . $idTablaDB . "_FechaUltimoCargue";
                    $resSQLAlterFechaBD = $mysqli->query($strSQLAlterFechaBD);

                    if ($resSQLAlterFechaBD) {
                        echo "Se adicionaron la columnas G" . $idTablaDB . "_FechaUltimoCargue y  G" . $idTablaDB . "_OrigenUltimoCargue </br>";
                    } else {
                        echo "Error al adicionar la columna G" . $idTablaDB . "_FechaUltimoCargue y  G" . $idTablaDB . "_OrigenUltimoCargue : " . $mysqli->error . "</br></br>";
                    }
                }
            }
        }
    }
}
