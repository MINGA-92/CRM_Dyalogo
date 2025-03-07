
<?php

include_once(__DIR__."../../../../../pages/conexion.php");


$IdEsfera= $_POST['IdEsfera'];

$DatosConexion= array();
$ConsultaConexion = "SELECT * FROM DYALOGOCRM_SISTEMA.CONEXIONES_IVRS WHERE ID_HASTA= '". $IdEsfera ."';";
if($ResultadoConexion = $mysqli->query($ConsultaConexion)) {
    $CantidadResultados = $ResultadoConexion->num_rows;
    if($CantidadResultados > 0) {
        while ($FilaResultado = $ResultadoConexion->fetch_assoc()) {
            $IdFlecha= $FilaResultado['ID_CONEXIONES_IVRS'];
            $IdOpcion= $FilaResultado['ID_OPCION'];
            $PorDefecto= $FilaResultado['POR_DEFECTO'];

            array_push($DatosConexion, array("0"=> $IdFlecha, "1"=> $IdOpcion, "2"=> $PorDefecto));

        }

        $php_response = array("msg" => "Ok", "DatosConexion" => $DatosConexion);
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


?>
