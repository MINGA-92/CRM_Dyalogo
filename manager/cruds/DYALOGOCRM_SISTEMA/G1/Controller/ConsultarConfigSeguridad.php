
<?php

session_start();
include_once(__DIR__."../../../../../pages/conexion.php");
require_once(__DIR__ . "../../../../../global/WSCoreClient.php");
require_once('../../../../../helpers/parameters.php');


$Huesped = $_POST['IdProyecto'];
$ListaConfigSeg = array();
$ConsultaSQL = "SELECT * FROM DYALOGOCRM_SISTEMA.SEGURIDAD WHERE SEGURIDAD_ConsInte__PROYEC_b= '". $Huesped ."' AND SEGURIDAD_ESTADO= 'Activo';";
if ($ResultadoSQL = $mysqli->query($ConsultaSQL)) {
    $CantidadResultados = $ResultadoSQL->num_rows;
    if($CantidadResultados > 0) {
        while ($FilaResultado = $ResultadoSQL->fetch_assoc()) {
            $ID = $FilaResultado['SEGURIDAD_ConsInte__b'];
            $ACTIVAR_CADUCIDAD_I = $FilaResultado['SEGURIDAD_ACTIVAR_CADUCIDAD'];
            $DIAS_VALIDEZ = $FilaResultado['SEGURIDAD_DIAS_VALIDEZ'];
            $DIAS_NOTIFICAR = $FilaResultado['SEGURIDAD_DIAS_NOTIFICAR'];
            $ACTIVAR_BLOQUEO_AUTOMATICO_I = $FilaResultado['SEGURIDAD_ACTIVAR_BLOQUEO_AUTOMATICO'];
            $INTENTOS_FALLIDOS = $FilaResultado['SEGURIDAD_INTENTOS_FALLIDOS'];
            $FECHA_REGISTRO = $FilaResultado['SEGURIDAD_FECHA_REGISTRO'];

            if($ACTIVAR_CADUCIDAD_I == 1){
                $ACTIVAR_CADUCIDAD= "true";
            }else{
                $ACTIVAR_CADUCIDAD= "false";
            }

            if($ACTIVAR_BLOQUEO_AUTOMATICO_I == 1){
                $ACTIVAR_BLOQUEO_AUTOMATICO= "true";
            }else{
                $ACTIVAR_BLOQUEO_AUTOMATICO= "false";
            }

            array_push($ListaConfigSeg, array("0" => $ACTIVAR_CADUCIDAD, "1" => $DIAS_VALIDEZ, "2" => $DIAS_NOTIFICAR, "3" => $ACTIVAR_BLOQUEO_AUTOMATICO, "4" => $INTENTOS_FALLIDOS, "5" => $FECHA_REGISTRO, "6" => $ID));
        }

        $php_response = array("msg" => "Ok", "Resultado" => $ListaConfigSeg);
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
