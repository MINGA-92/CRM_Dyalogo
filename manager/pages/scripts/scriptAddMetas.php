<?php 

require_once ("../conexion.php");
require_once("../../cruds/DYALOGOCRM_SISTEMA/reporteador.php");

// // BGCR - Se consultan todos los pasos de tipo correo y chat

$strSQLidPasos = "select ESTPAS_ConsInte__b as id, ESTPAS_ConsInte__ESTRAT_b as estrategia from {$BaseDatos_systema}.ESTPAS WHERE ESTPAS_Tipo______b = 17 OR  ESTPAS_Tipo______b = 14"; 
$resSQLidPasos = $mysqli->query($strSQLidPasos);

if ($resSQLidPasos) {
    if ($resSQLidPasos->num_rows > 0) {
        while ($Paso = $resSQLidPasos->fetch_object()) {
            // Por cada id se jecuta la funcion de crear las metas

                insertarMetas($Paso->estrategia, $Paso->id ,1);
        }
    }
}



?>