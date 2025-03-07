
<?php

include_once(__DIR__."../../../../../pages/conexion.php");

$IdOpcion = $_POST['IdOpcion'];
$DatosOpcion= array();
$ConsultaSQL = "SELECT * FROM dyalogo_telefonia.dy_opciones_ivrs WHERE id= '". $IdOpcion ."';";
if ($ResultadoSQL = $mysqli->query($ConsultaSQL)) {
    $CantidadResultados = $ResultadoSQL->num_rows;
    if($CantidadResultados > 0) {
        while ($FilaResultado = $ResultadoSQL->fetch_assoc()) {
            $IdOpcion= $FilaResultado['id'];
            $NombreInterno= $FilaResultado['nombre_interno_opcion'];
            $NombreUsuario= $FilaResultado['nombre_usuario_opcion'];
            $OpcionPorDefecto= $FilaResultado['opcion_por_defecto'];
            $Valida= $FilaResultado['valida'];

            array_push($DatosOpcion, array("0"=> $NombreInterno, "1"=> $NombreUsuario, "2"=> $OpcionPorDefecto, "3"=> $Valida, "4"=> $IdOpcion));

        }
    
        $php_response = array("msg" => "Ok", "Resultado" => $DatosOpcion);
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
