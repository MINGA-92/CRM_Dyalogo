
<?php

include_once(__DIR__."../../../../../pages/conexion.php");

$IdOpcion = $_POST['IdOpcion'];
$DatosAcciones= array();
$ConsultaSQL = "SELECT * FROM dyalogo_telefonia.dy_acciones_opcion_ivrs WHERE id_opcion_ivr= '". $IdOpcion ."' ORDER BY orden;";
if ($ResultadoSQL = $mysqli->query($ConsultaSQL)) {
    $CantidadResultados = $ResultadoSQL->num_rows;
    if($CantidadResultados > 0) {
        while ($FilaResultado = $ResultadoSQL->fetch_assoc()) {
            $IdAccion= $FilaResultado['id'];
            $Orden= $FilaResultado['orden'];
            $Accion= $FilaResultado['accion'];
            $ValorAccion= $FilaResultado['valor_accion'];

            array_push($DatosAcciones, array("0"=> $Orden, "1"=> $Accion, "2"=> $ValorAccion, "3"=> $IdAccion));

        }
    
        $php_response = array("msg" => "Si Existe", "Resultado" => $DatosAcciones);
        echo json_encode($php_response);
        mysqli_close($mysqli);
        exit;

    }else{
        //Sin Resultados
        $php_response = array("msg" => "Nada");
        echo json_encode($php_response);
        exit;
    }
}else{
    $Falla = mysqli_error($mysqli);
    $php_response = array("msg" => "Error", "Falla" => $Falla);
    echo json_encode($php_response);
    mysqli_close($mysqli);
    exit;
}

?>
