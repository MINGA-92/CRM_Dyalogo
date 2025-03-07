
<?php

include_once(__DIR__."../../../../../pages/conexion.php");
$mysqli = new  mysqli($DB_Name, $DB_User, $DB_Pass);


$Huesped = $_POST['Huesped'];
$IdEditar = $_POST['IdEditar'];
$NumeroEditar = array();
$ConsultaSQL = "SELECT dy_lista_negra.id AS Id, dy_lista_negra.nombre AS Nombre, dy_lista_negra_numeros.numero AS Numero, dy_lista_negra.comparador AS Comparador FROM dyalogo_telefonia.dy_lista_negra_numeros INNER JOIN dyalogo_telefonia.dy_lista_negra ON dyalogo_telefonia.dy_lista_negra_numeros.id_lista_negra=dyalogo_telefonia.dy_lista_negra.id WHERE id_proyecto= '". $Huesped ."' AND dy_lista_negra.id= ". $IdEditar .";";
if ($ResultadoSQL = $mysqli->query($ConsultaSQL)) {
    $CantidadResultados = $ResultadoSQL->num_rows;
    if($CantidadResultados > 0) {
        while ($FilaResultado = $ResultadoSQL->fetch_assoc()) {
            $Id = $FilaResultado['Id'];
            $Nombre = $FilaResultado['Nombre'];
            $Numero = $FilaResultado['Numero'];
            $Comparador = $FilaResultado['Comparador'];

            array_push($NumeroEditar, array("0" => $Nombre, "1" => $Numero, "2" => $Comparador, "3" => $Id));
        }
        $php_response = array("msg" => "Ok", "NumeroEditar" => $NumeroEditar);
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