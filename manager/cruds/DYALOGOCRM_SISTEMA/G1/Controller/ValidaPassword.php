
<?php

session_start();
include_once(__DIR__."../../../../../pages/conexion.php");
require_once(__DIR__ . "../../../../../global/WSCoreClient.php");
require_once('../../../../../helpers/parameters.php');

function encriptaPassword($password) {
    $method = 'sha256';
    $encrypted = hash($method, $password, false);
    return $encrypted;
}

$IdUsuario = $_POST['IdUsuario'];
$IdHuesped = $_POST['IdHuesped'];
$ActualPassword = $_POST['ActualPassword'];
$PasswordEncrip= encriptaPassword($ActualPassword);

$Resultado = [];
$ConsultaSQL = "SELECT * FROM DYALOGOCRM_SISTEMA.USUARI WHERE USUARI_ConsInte__b= '". $IdUsuario ."' AND USUARI_ConsInte__PROYEC_b= '". $IdHuesped ."';";
if ($ResultadoSQL = $mysqli->query($ConsultaSQL)) {
    $CantidadResultados = $ResultadoSQL->num_rows;
    if($CantidadResultados > 0) {
        while ($FilaResultado = $ResultadoSQL->fetch_assoc()) {
            $Clave = $FilaResultado['USUARI_Clave_____b'];
            array_push($Resultado, array("0" => $Clave, "1" => $PasswordEncrip));
        }
        $php_response = array("msg" => "Ok", "Resultado" => $Resultado);
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