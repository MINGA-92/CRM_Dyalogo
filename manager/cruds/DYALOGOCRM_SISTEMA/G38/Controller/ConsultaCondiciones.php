
<?php

include_once(__DIR__."../../../../../pages/conexion.php");

$IdAccion= $_POST['IdAccion'];
//Consulta Condiciones
$ListaCondicion = array();
$ConsultaCondicion = "SELECT * FROM DYALOGOCRM_SISTEMA.TARCON WHERE TARCON_ConsInte_TARPRO____b= '". $IdAccion ."';";
if ($ResultadoCondicion = $mysqli->query($ConsultaCondicion)) {
    $CantidadCondiciones = $ResultadoCondicion->num_rows;
    if($CantidadCondiciones > 0) {
        while ($FilaResultado = $ResultadoCondicion->fetch_assoc()) {
            $IdCondicion = $FilaResultado['TARCON_ConsInte__b'];
            $Separador= $FilaResultado['TARCON_Separador____b'];
            $Campo = $FilaResultado['TARCON_Campo____b'];
            $Condicion_1 = $FilaResultado['TARCON_Condicion____b'];
            $Valor = $FilaResultado['TARCON_Valor____b'];
            $SeparadorFinal = $FilaResultado['TARCON_Separador_Final____b'];

            array_push($ListaCondicion, array("0" => $IdCondicion, "1" => $Separador, "2" => $Campo, "3" => $Condicion_1, "4" => $Valor, "5" => $SeparadorFinal));
        }
        $php_response = array("msg" => "Ok", "ListaCondicion" => $ListaCondicion);
        echo json_encode($php_response);
        mysqli_close($mysqli);
        exit;
    }else {
        //Sin Resultados
        $IdCondicion = "";
    }
}else {
    mysqli_close($mysqli);
    $Falla = mysqli_error($mysqli);
    $php_response = array("msg" => "Error", "Falla" => $Falla);
    echo json_encode($php_response);
    exit;
}

?>
