
<?php

include_once(__DIR__."../../../../../pages/conexion.php");
$mysqli = new  mysqli($DB_Name, $DB_User, $DB_Pass);

$IdRuta= $_POST['IdRuta'];
$DiasEjecucion= $_POST['DiasEjecucion'];
$Accion= $_POST['Accion'];
$ValorAccion= $_POST['ValorAccion'];
$IdAccion= $_POST['IdAccion'];
$HoraInicial= $_POST['HoraInicial'];
$HoraFinal= $_POST['HoraFinal'];
$DiaInicial= $_POST['DiaInicial'];
$DiaFinal= $_POST['DiaFinal'];
$Orden= $_POST['Orden'];
$Troncal= $_POST['Troncal'];
if($DiasEjecucion == "3_ambos"){
    $AccionPorDefecto= '1';
    $Accion= $_POST['AccionFuera'];
    $ValorAccion= $_POST['ValorAccionFuera'];
    $IdAccion= $_POST['IdAccionFuera'];
    $Troncal= $_POST['TroncalFuera'];
}else{
    $AccionPorDefecto= '0';
}
if($Troncal == "null"){
    $Troncal= "0";
}
$DiaHabilMesInicio= '1';
$DiaHabilMesFin= '31';
$MesInicio= 'jan';
$MesFin= 'dec';


//Verificacion De Existencia Del Horario
$ConsultaSQL= "SELECT * FROM dyalogo_telefonia.dy_acciones_rutas_entrantes WHERE id_ruta_entrante= '". $IdRuta ."' AND dia_habil_semana_inicio= '". $DiaInicial ."' AND dia_habil_semana_fin= '". $DiaFinal ."';";
if ($ResultadoSQL= $mysqli->query($ConsultaSQL)) { 
    $CantidadResultados= $ResultadoSQL->num_rows;
    if($CantidadResultados > 0) {
        //Ya Existe
        while ($FilaResultado = $ResultadoSQL->fetch_assoc()) {
            $IdHorario= $FilaResultado['id'];
        }
        $ActualizarSQL = "UPDATE dyalogo_telefonia.dy_acciones_rutas_entrantes SET dias_ejecucion= '". $DiasEjecucion ."', accion= '". $Accion ."', 
        valor_accion= '". $ValorAccion ."', id_accion= '". $IdAccion ."', hora_inicial= '". $HoraInicial ."', hora_final= '". $HoraFinal ."', orden= '". $Orden ."', id_troncal='". $Troncal ."' WHERE id= '". $IdHorario ."';";
        if ($ResultadoSQL = $mysqli->query($ActualizarSQL)) {
            //Actualización correcta
            $php_response = array("msg" => "Ok");
            mysqli_close($mysqli);
            echo json_encode($php_response);
            exit;  
        }else {
            //Error en la Actualización
            $Falla = mysqli_error($mysqli);
            $php_response = array("msg" => "Error", "Falla" => $Falla);
            echo json_encode($php_response);
            mysqli_close($mysqli);
            exit;
        }

    }else{
        $InsercionSQL_2= "INSERT INTO dyalogo_telefonia.dy_acciones_rutas_entrantes(id_ruta_entrante, dias_ejecucion, accion, valor_accion, id_accion, accion_por_defecto, 
        hora_inicial, hora_final, dia_habil_semana_inicio, dia_habil_semana_fin, dia_habil_mes_inicio, dia_habil_mes_fin, mes_inicio, mes_fin, orden, id_troncal) 
        VALUES ('". $IdRuta ."', '". $DiasEjecucion ."', '". $Accion ."', '". $ValorAccion ."', '". $IdAccion ."', '". $AccionPorDefecto ."', '". $HoraInicial ."', '". $HoraFinal ."', '". $DiaInicial ."', '". $DiaFinal ."', 
        '". $DiaHabilMesInicio ."', '". $DiaHabilMesFin ."', '". $MesInicio ."', '". $MesFin ."', '". $Orden ."', '". $Troncal ."');";
        if ($ResultadoSQL= $mysqli->query($InsercionSQL_2)) {
            //inserción correcta
            $php_response= array("msg" => "Ok");
            echo json_encode($php_response);
            mysqli_close($mysqli);
            exit;
        }else {
            //Error en la Insercion
            $php_response= array("msg" => "Error InsercionSQL_2");
            $ErrorConsulta= mysqli_error($mysqli);
            mysqli_close($mysqli);
            echo $ErrorConsulta;
            exit;
        }
        
    }
}else {
    //Error en la Consulta
    $ErrorConsulta= mysqli_error($mysqli);
    mysqli_close($mysqli);
    echo $ErrorConsulta;
    exit;
}

?>
