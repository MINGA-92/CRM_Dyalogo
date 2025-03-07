
<?php

include_once(__DIR__."../../../../../pages/conexion.php");


$IdOpcion= $_POST['IdOpcion'];
$NombreEtiqueta= $_POST['Etiqueta'];

$ConsultaSQL= "SELECT * FROM dyalogo_telefonia.dy_acciones_opcion_ivrs WHERE accion= '11' AND id_opcion_ivr= '". $IdOpcion ."' AND avanzado_etiqueta= '". $NombreEtiqueta ."';";
if ($ResultadoSQL = $mysqli->query($ConsultaSQL)) {
    $CantidadResultados = $ResultadoSQL->num_rows;
    if($CantidadResultados > 0) {
        while ($FilaResultado = $ResultadoSQL->fetch_assoc()) {
            $Resultado= $FilaResultado['avanzado_etiqueta'];
        }
        $php_response = array("msg" => "Ya Existe", "Resultado" => $Resultado);
        echo json_encode($php_response);
        mysqli_close($mysqli);
        exit;
    }else {
        //Sin Resultados
        $php_response = array("msg" => "Ok");
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
