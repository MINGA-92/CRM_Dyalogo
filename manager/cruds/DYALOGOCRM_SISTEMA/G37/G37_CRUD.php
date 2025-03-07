<?php
ini_set('display_errors', 'On');
ini_set('display_errors', 1);
include_once(__DIR__."../../../../pages/conexion.php");
include_once(__DIR__."../../../../../helpers/parameters.php");
date_default_timezone_set('America/Bogota');

if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {

    if(isset($_POST['getCamposLista'])){
        $guion=isset($_POST['guion']) ? $_POST['guion'] : 0;
        $estado=false;
        $mensaje='No se identifico el guion para obtener la lista de campos';
        if($guion > 0){
            $sqlPregun = "SELECT * FROM {$BaseDatos_systema}.PREGUN WHERE PREGUN_ConsInte__GUION__b = {$guion} AND PREGUN_Tipo______b = 6";
            $respPregun = $mysqli->query($sqlPregun);
            if($respPregun){
                if($respPregun ->num_rows > 0){
                    $estado=true;
                    $mensaje=array();
                    while ($item = $respPregun->fetch_object()){
                        array_push($mensaje,array("id"=>$item->PREGUN_ConsInte__b, "nombre"=>$item->PREGUN_Texto_____b));
                    }
                }else{
                    $mensaje="El guion de la campaña origen no tiene campos tipo lista definidos";
                }
            }
        }

        echo json_encode(array("estado"=>$estado,"mensaje"=>$mensaje));
    }

    if(isset($_POST['getForm'])){
        $id=isset($_POST['paso']) ? $_POST['paso'] : 0;
        $estado=false;
        $mensaje='No se identifico la campaña conectada a este paso';
        if($id){
            $sql=$mysqli->query("SELECT CAMPAN_ConsInte__GUION__Gui_b FROM {$BaseDatos_systema}.CAMPAN WHERE CAMPAN_ConsInte__b = (SELECT ESTPAS_ConsInte__CAMPAN_b FROM {$BaseDatos_systema}.ESTPAS WHERE ESTPAS_ConsInte__b={$id})");
            if($sql && $sql->num_rows > 0){
                $sql=$sql->fetch_object();
                $mensaje=$sql->CAMPAN_ConsInte__GUION__Gui_b;
                $estado=true;
            }else{
                $mensaje="Configurar la campaña que esta conectada a este paso";
            }
        }

        echo json_encode(array("estado"=>$estado,"mensaje"=>$mensaje));
    }
}