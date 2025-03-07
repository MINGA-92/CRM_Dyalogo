
<?php

include_once(__DIR__."../../../../../pages/conexion.php");

$IdEditar = $_POST['IdEditar'];
$ListaAccion = array();
$ConsultaSQL = "SELECT * FROM DYALOGOCRM_SISTEMA.TARPRO WHERE TARPRO_ConsInte__b= '". $IdEditar ."';";
if ($ResultadoSQL = $mysqli->query($ConsultaSQL)) {
    $CantidadResultados = $ResultadoSQL->num_rows;
    if($CantidadResultados > 0) {
        while ($FilaResultado = $ResultadoSQL->fetch_assoc()) {
            $IdAccion = $FilaResultado['TARPRO_ConsInte__b'];
            $IdTarea = $FilaResultado['TARPRO_ConsInte_TARHOR____b'];
            $TipoTarea = $FilaResultado['TARPRO_Tipo_De_Tarea____b'];
            $ParaQueRegistros = $FilaResultado['TARPRO_Para_Que_Registros____b'];
            $IdUsuario = $FilaResultado['TARPRO_ConsInte__USUARI____b'];
            $Limite= $FilaResultado['TARPRO_Aplicar_Limite_Cantidad____b'];
            $CantidadLimite = $FilaResultado['TARPRO_Cantidad_Aplicar_Limite____b'];
            $SentenciaSQL= $FilaResultado['TARPRO_Consulta_sql_b'];

            array_push($ListaAccion, array("0" => $IdAccion, "1" => $IdTarea, "2" => $TipoTarea, "3" => $ParaQueRegistros, "4" => $IdUsuario, "5" => $Limite, "6" => $CantidadLimite, "7" => $SentenciaSQL));
        }
        //Consuta Usuario
        if(($IdUsuario != 0) || ($IdUsuario != null)) {
            $ConsultaUsuario = "SELECT * FROM DYALOGOCRM_SISTEMA.USUARI WHERE USUARI_ConsInte__b= '". $IdUsuario ."';";
            if ($ResultadoUsuario = $mysqli->query($ConsultaUsuario)) {
                $CantidadResultados = $ResultadoUsuario->num_rows;
                if($CantidadResultados > 0) {
                    while ($FilaResultado = $ResultadoUsuario->fetch_assoc()) {
                        $NombreUsuario = $FilaResultado['USUARI_Nombre____b'];
                    }
                }else {
                    //Sin Resultados
                    $php_response = array("msg" => "Nada");
                    mysqli_close($mysqli);
                    echo json_encode($php_response);
                    exit;
                }
            }else {
                mysqli_close($mysqli);
                $Falla = mysqli_error($mysqli);
                $php_response = array("msg" => "Error", "Falla" => $Falla);
                echo json_encode($php_response);
                exit;
            }
        }else{
            $NombreUsuario = "Elige Una Opción";
        }
        $php_response = array("msg" => "Ok", "ListaAccion" => $ListaAccion, "NombreUsuario" => $NombreUsuario);
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
