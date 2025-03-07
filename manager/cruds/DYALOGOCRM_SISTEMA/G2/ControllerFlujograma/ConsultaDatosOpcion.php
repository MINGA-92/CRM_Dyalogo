
<?php

include_once(__DIR__."../../../../../pages/conexion.php");


$IdFrom= $_POST['IdFrom'];
$IdTo= $_POST['IdTo'];

$DatosOpcion= array();
$ConsultaFlecha = "SELECT * FROM DYALOGOCRM_SISTEMA.CONEXIONES_IVRS WHERE ID_DESDE= '". $IdFrom ."' AND ID_HASTA= '". $IdTo ."';";
if($ResultadoFlecha = $mysqli->query($ConsultaFlecha)) {
    $CantidadResultados = $ResultadoFlecha->num_rows;
    if($CantidadResultados > 0) {
        while ($FilaResultado = $ResultadoFlecha->fetch_assoc()) {
            $IdFlecha= $FilaResultado['ID_CONEXIONES_IVRS'];
            $IdOpcion= $FilaResultado['ID_OPCION'];
        }
        
        $ConsultaOpcion = "SELECT * FROM dyalogo_telefonia.dy_opciones_ivrs WHERE id= '". $IdOpcion ."' AND id_opcion= '". $IdOpcion ."';";
        if($ResultadoOpcion = $mysqli->query($ConsultaOpcion)) {
            $CantidadResultados = $ResultadoOpcion->num_rows;
            if($CantidadResultados > 0) {
                while ($FilaResultado = $ResultadoOpcion->fetch_assoc()) {
                    $Opcion= $FilaResultado['nombre_interno_opcion'];
                    $NombreOpcion= $FilaResultado['nombre_usuario_opcion'];
                }

                array_push($DatosOpcion, array("0"=> $IdFlecha, "1"=> $IdOpcion, "2"=> $Opcion, "3"=> $NombreOpcion));

                $php_response = array("msg" => "Ok", "DatosOpcion" => $DatosOpcion);
                echo json_encode($php_response);
                mysqli_close($mysqli);
                exit;


            }else {
                //Sin Resultados
                $php_response = array("msg" => "Nada");
                mysqli_close($mysqli);
                echo json_encode($php_response);
                exit;
            }
        } else {
            mysqli_close($mysqli);
            $Falla = mysqli_error($mysqli);
            $php_response = array("msg" => "Error", "Falla" => $Falla);
            echo json_encode($php_response);
            exit;
        }

    }else {
        //Sin Resultados
        $php_response = array("msg" => "Nada");
        mysqli_close($mysqli);
        echo json_encode($php_response);
        exit;
    }
} else {
    mysqli_close($mysqli);
    $Falla = mysqli_error($mysqli);
    $php_response = array("msg" => "Error", "Falla" => $Falla);
    echo json_encode($php_response);
    exit;
}


?>
