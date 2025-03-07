<?php
ini_set('display_errors', 'On');
ini_set('display_errors', 1);
require_once("pages/conexion.php");

// SELECCIONAR EL MAX(ID) DE LA BASE DE DATOS PARA ACTUALIZAR EL CÓDIGO MIEMBRO EN LAS GESTIONES
$campan=isset($_GET['campan']) && is_numeric($_GET['campan']) ? $_GET['campan'] : false;
$llave=isset($_GET['llave']) ? $_GET['llave'] : false;

if($campan && $llave){
    //BUSCAMOS LAS TABLAS DE LA CAMPAÑA
    $sql=$mysqli->query("SELECT CAMPAN_ConsInte__GUION__Gui_b AS guion, CAMPAN_ConsInte__GUION__Pob_b AS bd, CAMPAN_ConsInte__MUESTR_b AS muestra FROM DYALOGOCRM_SISTEMA.CAMPAN WHERE CAMPAN_ConsInte__b={$campan}");
    if($sql && $sql->num_rows> 0){
        $sql=$sql->fetch_object();
        $guion="G{$sql->guion}";
        $bd="G{$sql->bd}";
        $muestra="{$bd}_M{$sql->muestra}";

        // contador
        $i = 0;
        
        // 1. BUSCAMOS EL MAX(ID) DE CADA GRUPO DE REGISTROS AGUPADOS POR CEDULA EN LA BD
        $sqlMax=$mysqli->query("SELECT COUNT(1) AS veces, {$llave} AS llave, MAX({$bd}_ConsInte__b) AS id FROM DYALOGOCRM_WEB.{$bd} WHERE {$llave} !='' AND {$llave} IS NOT NULL GROUP BY {$llave} HAVING veces > 1");
        if($sqlMax && $sqlMax->num_rows > 0){
            while($fila=$sqlMax->fetch_object()){
                echo  $i . ". Ejecutando el registro de -> " . $fila->llave . "<br>";
                $valido=false;
                // 2.ACTUALIZAR LA TABLA DE GESTIONES
                $sqlUpGestion=$mysqli->query("UPDATE DYALOGOCRM_WEB.{$guion} LEFT JOIN DYALOGOCRM_WEB.{$bd} ON {$guion}_CodigoMiembro={$bd}_ConsInte__b SET {$guion}_CodigoMiembro={$fila->id} WHERE {$llave}='{$fila->llave}' AND {$guion}_CodigoMiembro IS NOT NULL");

                $query = "UPDATE DYALOGOCRM_WEB.{$guion} LEFT JOIN DYALOGOCRM_WEB.{$bd} ON {$guion}_CodigoMiembro={$bd}_ConsInte__b SET {$guion}_CodigoMiembro={$fila->id} WHERE {$llave}='{$fila->llave}' AND {$guion}_CodigoMiembro IS NOT NULL"."<br>";
                // $valido=true;
                if($sqlUpGestion){
                    $valido=true;
                }else{
                    echo "Fallo al actualizar la gestion -> " . $mysqli->error . "  --- consulta -> " . $query;
                }

                if($valido){
                    $valido=false;
                    // 3.ACTUALIZAR TABLA DE CONDIA
                    $sqlUpCondia=$mysqli->query("UPDATE DYALOGOCRM_SISTEMA.CONDIA LEFT JOIN DYALOGOCRM_WEB.{$bd} ON CONDIA_CodiMiem__b={$bd}_ConsInte__b SET CONDIA_CodiMiem__b={$fila->id} WHERE CONDIA_ConsInte__CAMPAN_b={$campan}");

                    $query = "UPDATE DYALOGOCRM_SISTEMA.CONDIA LEFT JOIN DYALOGOCRM_WEB.{$bd} ON CONDIA_CodiMiem__b={$bd}_ConsInte__b SET CONDIA_CodiMiem__b={$fila->id} WHERE CONDIA_ConsInte__CAMPAN_b={$campan} AND {$llave}='{$fila->llave}'"."<br>";
                    // $valido=true;

                    if($sqlUpCondia){
                        $valido=true;
                    }else{
                        echo "Fallo al actualizar la condia -> " . $mysqli->error . "  --- consulta -> " . $query;
                    }
                }
                
                if($valido){
                    $valido=false;
                    // 4.BORRAR DE LA MUESTRA
                    $query = "DELETE DYALOGOCRM_WEB.{$muestra} FROM DYALOGOCRM_WEB.{$muestra} LEFT JOIN DYALOGOCRM_WEB.{$bd} ON {$muestra}_CoInMiPo__b={$bd}_ConsInte__b WHERE {$llave}='{$fila->llave}' AND {$muestra}_CoInMiPo__b !={$fila->id}";
                    $sqlDelMuestra=$mysqli->query($query);

                    // $valido=true;

                    if($sqlDelMuestra){
                        $valido=true;
                    }else{
                        echo "Fallo al actualizar la muestra -> " . $mysqli->error . "  --- consulta -> " . $query;
                    }
                }

                if($valido){
                    $valido=false;
                    // 5.BORRAR DE LA BASE DE DATO
                    $sqlDelBd=$mysqli->query("DELETE FROM DYALOGOCRM_WEB.{$bd} WHERE {$llave}='{$fila->llave}' AND {$bd}_ConsInte__b !={$fila->id}");

                    $query = "DELETE FROM DYALOGOCRM_WEB.{$bd} WHERE {$llave}='{$fila->llave}' AND {$bd}_ConsInte__b !={$fila->id}";
                    // $valido=true;
                    if($sqlDelBd){
                        $valido=true;
                    }else{
                        echo "Fallo al actualizar la bd -> " . $mysqli->error . "  --- consulta -> " . $query;
                    }
                }

                if($valido){
                    echo "<h1>Registros eliminados con exito</h1>";
                }

                $i++;
                // die();
            }
        }else{
            echo "No hay registros duplicados";
        }

    }else{
        echo "<h1>La campaña no existe</h1>";
    }
    
}else{
    echo "<h1>No se identifico la campaña o la llave</h1>";
}
?>