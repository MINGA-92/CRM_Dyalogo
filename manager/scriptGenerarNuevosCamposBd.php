<?php

ini_set('display_errors', 'On');
ini_set('display_errors', 1);
include_once(__DIR__."/pages/conexion.php");

// Buscamos todas las bases de datos
$sql = "select GUION__ConsInte__b AS id from DYALOGOCRM_SISTEMA.GUION_ where GUION__Tipo______b = 1";
$res = $mysqli->query($sql);

if($res && $res->num_rows > 0){

    while ($row = $res->fetch_object()) {

        echo "########## Ejecutando {$row->id}";
        
        // generamos el sql para agregar los  nuevos campos
        $sqlTemplate = "ALTER TABLE `DYALOGOCRM_WEB`.`G{$row->id}` ADD COLUMN `G{$row->id}_Template_b` INT(11) NULL";
        $res1 = $mysqli->query($sqlTemplate);

        if($res1){
            echo "### Ejecucion correcta campo template {$row->id}";
        }else{
            echo "### FALLO al ejecutar la consulta {$row->id} -> {$mysqli->error}";
        }

        $sqlFecha = "ALTER TABLE `DYALOGOCRM_WEB`.`G{$row->id}` ADD COLUMN `G{$row->id}_TemplateFechaEnvio_b` DATETIME NULL;";
        $res2 = $mysqli->query($sqlFecha);

        if($res2){
            echo "### Ejecucion correcta campo fecha {$row->id}";
        }else{
            echo "### FALLO al ejecutar la fecha {$row->id} -> {$mysqli->error}";
        }

    }

}

?>