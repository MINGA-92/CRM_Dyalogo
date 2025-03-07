<?php
    session_start();
    //include(__DIR__."/../../conexion.php");
    include(__DIR__."/../../conexion.php");
    if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
        if($_GET['action'] == "GET_DATOS"){

            $PEOBUS_VeRegPro__b = 0 ;
            $idUsuario = $_SESSION['IDENTIFICACION'];
            $peobus = "SELECT * FROM ".$BaseDatos_systema.".PEOBUS WHERE PEOBUS_ConsInte__USUARI_b = ".$idUsuario." AND PEOBUS_ConsInte__GUION__b = 25";
            $query = $mysqli->query($peobus);
            

            while ($key =  $query->fetch_object()) {
                $PEOBUS_VeRegPro__b = $key->PEOBUS_VeRegPro__b ;
            }


            $str_Lsql = " SELECT * FROM ".$BaseDatos.".G25 ";
            $Where = " WHERE ";
            $usados = 0;

           

            if($usados == 0){
                if($PEOBUS_VeRegPro__b != 0){
                    $Where .= " G25_Usuario = ".$idUsuario;
                }else{
                    $Where = " WHERE 1 ";
                }
            }else{
                if($PEOBUS_VeRegPro__b != 0){
                    $Where .= " AND G25_Usuario = ".$idUsuario;
                }
            }

            $str_Lsql .= $Where;
            //echo $str_Lsql;
            $resultado = $mysqli->query($str_Lsql);
            $arrayDatos = array();
            while ($key = $resultado->fetch_assoc()) {
                $arrayDatos[] = $key;
            }

            $newJson = array();
            $newJson[0]['cantidad_registros'] = $resultado->num_rows;
            $newJson[0]['registros'] = $arrayDatos;

            echo json_encode($newJson);
        }
    }
