<?php 
    session_start();
    ini_set('display_errors', 'On');
    ini_set('display_errors', 1);
    include_once(__DIR__."../../../../pages/conexion.php");

    function guardar_auditoria($accion, $superAccion){
        global $mysqli;
        global $BaseDatos_systema;
        $str_Lsql = "INSERT INTO ".$BaseDatos_systema."AUACAD (AUACAD_Fecha_____b , AUACAD_Hora______b, AUACAD_Ejecutor__b, AUACAD_TipoAcci__b , AUACAD_SubTipAcc_b, AUACAD_Accion____b , AUACAD_Huesped___b ) VALUES ('".date('Y-m-d H:s:i')."', '".date('Y-m-d H:s:i')."', ".$_SESSION['IDENTIFICACION'].", 'G14', '".$accion."', '".$superAccion."', ".$_SESSION['HUESPED']." );";
        $mysqli->query($str_Lsql);
    }

    if(!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest'){

        if(isset($_GET['getDatos']) && $_GET['getDatos'] == true){

            $pasoId = $_POST['pasoId'];
            // $huespedId = $_POST['huesped'];
            // $bdId = $_POST['bd'];

            // Obtenemos el paso del registro
            $sql = "SELECT ESTPAS_ConsInte__b as id, ESTPAS_Comentari_b as nombre, ESTPAS_activo as activo, ESTPAS_ConsInte__ESTRAT_b AS est FROM ".$BaseDatos_systema.".ESTPAS WHERE ESTPAS_ConsInte__b = ".$pasoId;
            $res = $mysqli->query($sql);
            $paso = $res->fetch_object();

            echo json_encode([
                "estado" => "ok",
                "paso" => $paso
            ]);
            exit;
        }

        if(isset($_GET['insertarDatos']) && isset($_POST["oper"])){

            $pasoId = $_POST['id_paso'];
            // $huespedId = $_POST['huesped'];

            $nombre = $_POST['nombre'];
            $activo = isset($_POST['pasoActivo']) ? $_POST['pasoActivo'] : 0;

            // Actualizamos el paso
            $sql = "UPDATE ".$BaseDatos_systema.".ESTPAS SET ESTPAS_Comentari_b = '".$nombre."', ESTPAS_activo = '".$activo."' WHERE ESTPAS_ConsInte__b = ".$pasoId;
            $mysqli->query($sql);


            if($_POST["oper"] == "add"){

                // Por el momento no se tiene guardado de info extra

            }

            if($_POST["oper"] == "edit"){

                // Por el momento no se tiene guardado de info extra

            }

            echo json_encode(["estado" => "registrado"]);
        }
    }

?>