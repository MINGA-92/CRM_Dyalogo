
<?php

include_once(__DIR__."../../../../../pages/conexion.php");

$IdEstrat= $_POST['IdEstpas'];
//Consulta campaña
$ConsultaSQL= "SELECT ESTPAS_ConsInte__CAMPAN_b AS IdCampana from DYALOGOCRM_SISTEMA.ESTPAS WHERE ESTPAS_ConsInte__b = '". $IdEstrat ."';";
if ($ResultadoSQL = $mysqli->query($ConsultaSQL)) {
    $CantidadResultados = $ResultadoSQL->num_rows;
    if($CantidadResultados > 0) {
        while ($FilaResultado = $ResultadoSQL->fetch_assoc()) {
            $IdCampana= $FilaResultado['IdCampana'];
        }
        //Consulta Usuario
        $ListaAgentes = "";
        $ConsultaSQL_2= "SELECT USUARI_ConsInte__b AS IdUsuario, USUARI_Nombre____b AS NombreUsuario FROM DYALOGOCRM_SISTEMA.ASITAR INNER JOIN DYALOGOCRM_SISTEMA.USUARI ON ASITAR_ConsInte__USUARI_b = USUARI_ConsInte__b WHERE ASITAR_ConsInte__CAMPAN_b = '". $IdCampana ."'";
        if ($ResultadoSQL_2 = $mysqli->query($ConsultaSQL_2)) {
            $CantidadResultados_2 = $ResultadoSQL_2->num_rows;
            if($CantidadResultados_2 > 0) {
                while ($FilaResultado = $ResultadoSQL_2->fetch_assoc()) {
                    $IdUsuario= $FilaResultado['IdUsuario'];
                    $NombreUsuario= $FilaResultado['NombreUsuario'];
                    $ListaAgentes = $ListaAgentes . '<option value="' . $FilaResultado['IdUsuario'] . '">' . $FilaResultado['NombreUsuario'] . '</option>';
                }
                
                //Si realizo Consulta
                $php_response = array("msg" => "Ok", "Respuesta" => $ListaAgentes, "Respuesta_2" => $IdUsuario, "Respuesta_3" => $NombreUsuario);
                echo json_encode($php_response);
                mysqli_close($mysqli);
                exit;

            }else{
                $ErrorConsulta = mysqli_error($mysqli);
                mysqli_close($mysqli);
                echo '<script>alert("Error Falla -> ' . $ErrorConsulta . '");</script>';
                exit;
            }
        }else{
            //Sin Resultados
            $php_response = array("msg" => "Nada");
            mysqli_close($mysqli);
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
