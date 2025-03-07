
<?php

include_once(__DIR__."../../../../../pages/conexion.php");

$Nombre = $_POST['Nombre'];

$ConsultaSQL = "SELECT * FROM dyalogo_telefonia.dy_rutas_entrantes WHERE nombre= '". $Nombre ."';";
//$ConsultaSQL = "SELECT * FROM '. $BaseDatos_systema .' WHERE nombre= '". $Nombre ."';";
if ($ResultadoSQL = $mysqli->query($ConsultaSQL)) {
    $CantidadResultados = $ResultadoSQL->num_rows;
    if($CantidadResultados > 0) {
        while ($FilaResultado = $ResultadoSQL->fetch_assoc()) {
            $Resultado= $FilaResultado['nombre'];
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
