<?php

ini_set('display_errors', 'On');
ini_set('display_errors', 1);
include_once(__DIR__."/pages/conexion.php");

$sql = "SELECT ESTCON_ConsInte__b AS id, ESTCON_Consulta_sql_b AS consulta, ESTPAS_ConsInte__b AS pasoId, ESTPAS_activo AS pasoActivo, ESTCON_Activo_b AS estconActivo FROM DYALOGOCRM_SISTEMA.ESTCON 
    JOIN DYALOGOCRM_SISTEMA.ESTPAS ON ESTCON_ConsInte__ESTPAS_Has_b = ESTPAS_ConsInte__b WHERE ESTCON_Consulta_sql_b IS NOT NULL AND ESTPAS_activo = -1";
$res = $mysqli->query($sql);

if($res && $res->num_rows > 0){

    while($row = $res->fetch_object()){

        if($row->pasoActivo == "-1"){
            $uSql = "UPDATE DYALOGOCRM_SISTEMA.ESTCON SET ESTCON_Activo_b = -1 WHERE ESTCON_ConsInte__b = {$row->id}";

            if($mysqli->query($uSql)){
                echo "Actualizado $row->id <br>";
            }else{
                echo "Error al actualizar $uSql <br>";
            }
        }

    }

}