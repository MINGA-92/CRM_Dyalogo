<?php
    session_start();
    include(__DIR__."/../../conexion.php");
    if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
        if($_GET['action'] == "GET_DATOS"){

            $PEOBUS_VeRegPro__b = 0 ;
            $idUsuario = $_SESSION['IDENTIFICACION'];
            $peobus = "SELECT * FROM ".$BaseDatos_systema.".PEOBUS WHERE PEOBUS_ConsInte__USUARI_b = ".$idUsuario." AND PEOBUS_ConsInte__GUION__b = 4";
            $query = $mysqli->query($peobus);
            

            while ($key =  $query->fetch_object()) {
                $PEOBUS_VeRegPro__b = $key->PEOBUS_VeRegPro__b ;
            }


            $Lsql = " SELECT * FROM ".$BaseDatos_systema.".G4 ";
            $Where = " WHERE ";
            $usados = 0;

           

            if($usados == 0){
                if($PEOBUS_VeRegPro__b != 0){
                    $Where .= " G4_Usuario = ".$idUsuario;
                }else{
                    $Where = " WHERE 1 ";
                }
            }else{
                if($PEOBUS_VeRegPro__b != 0){
                    $Where .= " AND G4_Usuario = ".$idUsuario;
                }
            }

            $Lsql .= $Where;
            //echo $Lsql;
            $resultado = $mysqli->query($Lsql);
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
