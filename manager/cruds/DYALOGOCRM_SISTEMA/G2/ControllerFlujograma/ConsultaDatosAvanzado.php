
<?php

include_once(__DIR__."../../../../../pages/conexion.php");

$IdOpcion = $_POST['IdOpcion'];
$DatosAvanzado= array();
$ConsultaSQL = "SELECT * FROM dyalogo_telefonia.dy_acciones_opcion_ivrs WHERE id_opcion_ivr= '". $IdOpcion ."' AND accion= '11' ORDER BY orden;";
if ($ResultadoSQL = $mysqli->query($ConsultaSQL)) {
    $CantidadResultados = $ResultadoSQL->num_rows;
    if($CantidadResultados > 0) {
        while ($FilaResultado = $ResultadoSQL->fetch_assoc()) {
            $IdAccion= $FilaResultado['id'];
            $Orden= $FilaResultado['orden'];
            $ValorAccion= $FilaResultado['valor_accion'];
            $Etiqueta= $FilaResultado['avanzado_etiqueta'];
            $IdAplicacion= $FilaResultado['avanzado_id_aplicacion'];
            $Parametros= $FilaResultado['avanzado_parametros'];

            array_push($DatosAvanzado, array("0"=> $Orden, "1"=> $ValorAccion, "2"=> $Etiqueta, "3"=> $IdAplicacion, "4"=> $Parametros, "5"=> $IdAccion));

        }
    
        $php_response = array("msg" => "Si Existe", "Resultado" => $DatosAvanzado);
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
