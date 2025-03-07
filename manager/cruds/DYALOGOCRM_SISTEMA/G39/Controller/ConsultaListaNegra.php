
<?php

include_once(__DIR__."../../../../../pages/conexion.php");
$mysqli = new  mysqli($DB_Name, $DB_User, $DB_Pass);


$Huesped = $_POST['Huesped'];
$ListaNegra = array();
$ConsultaSQL = "SELECT dy_lista_negra.id AS Id, dy_lista_negra.nombre AS Nombre, dy_lista_negra_numeros.numero AS Numero, dy_lista_negra.comparador AS Comparador FROM dyalogo_telefonia.dy_lista_negra_numeros INNER JOIN dyalogo_telefonia.dy_lista_negra ON dyalogo_telefonia.dy_lista_negra_numeros.id_lista_negra=dyalogo_telefonia.dy_lista_negra.id WHERE id_proyecto= '". $Huesped ."';";
if ($ResultadoSQL = $mysqli->query($ConsultaSQL)) {
    $CantidadResultados = $ResultadoSQL->num_rows;
    if($CantidadResultados > 0) {
        while ($FilaResultado = $ResultadoSQL->fetch_assoc()) {
            $Id = $FilaResultado['Id'];
            $Nombre = $FilaResultado['Nombre'];
            $Numero = $FilaResultado['Numero'];
            $Comparador = $FilaResultado['Comparador'];

            array_push($ListaNegra, array("0" => $Nombre, "1" => $Numero, "2" => $Comparador, "3" => $Id, "4" => $Id));
        }
        $php_response = array("msg" => "Ok", "ListaNegra" => $ListaNegra);
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