<?php 

require_once ("../conexion.php");



// // BGCR - Se consultan todos los id de los huespedes

$strSQLidTablaGestion = "SELECT G5_ConsInte__b as idTableGestion FROM {$BaseDatos_systema}.G5 where G5_C29 = 1 ORDER BY G5_ConsInte__b DESC"; 
$resSQLidTablaGestion = $mysqli->query($strSQLidTablaGestion);

if ($resSQLidTablaGestion) {
    if ($resSQLidTablaGestion->num_rows > 0) {
        while ($idTableGestion = $resSQLidTablaGestion->fetch_object()->idTableGestion) {
            // Por cada id se ejecuta un alter para adicionar la columna de fecha BD

            $strSQLAlterFechaBD = "ALTER TABLE ".$BaseDatos.".G".$idTableGestion." ADD G".$idTableGestion."_FechaInsercionBD_b datetime DEFAULT NULL AFTER G".$idTableGestion."_FechaInsercion";
            $resSQLAlterFechaBD = $mysqli->query($strSQLAlterFechaBD);

            if($resSQLAlterFechaBD){
                echo "Se adiciono la columna en el G".$idTableGestion." </br>";
            }else{
                echo "Error al adicionar la columna en el G".$idTableGestion." : ".$mysqli->error."</br>";
            }
        }
    }
}



?>