<?php 

require_once ("../conexion.php");
include("../../global/WSCoreClient.php");


// // BGCR - Se consultan todos los id de los huespedes

$strSQLHuesped = "SELECT id FROM {$BaseDatos_general}.huespedes"; 
$resSQLHuesped = $mysqli->query($strSQLHuesped);

if ($resSQLHuesped) {
    if ($resSQLHuesped->num_rows > 0) {
        while ($huesped = $resSQLHuesped->fetch_object()->id) {
            // Por cada huesped se consume el WebService de Java para generar las vistas

            generarVistasPorHuesped(null,$huesped);
            echo "Se genero las vistas del huesped {$huesped}";
        }
    }
}



?>