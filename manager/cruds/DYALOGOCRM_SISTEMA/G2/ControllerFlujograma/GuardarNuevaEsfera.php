
<?php

include_once(__DIR__."../../../../../pages/conexion.php");


$IdIVR= $_POST['IdIVR'];
$IdHuesped= $_POST['IdHuesped'];
$Categoria= $_POST['Categoria'];
$TipoEsfera= $_POST['TipoEsfera'];
$IdEstrategia= $_POST['IdEstrategia'];
$StringFlujograma= $_POST['StringFlujograma'];


//Esta funcion traduce lo que llega como strin a un json
function traducirFljujograma($stringFlujograma){

    $stringFlujograma = str_replace("\\n", "", $stringFlujograma);
    $stringFlujograma = str_replace("\\r", "", $stringFlujograma);
    $stringFlujograma = str_replace('\\', "", $stringFlujograma);
    $jsonFlujo = json_decode($stringFlujograma);

    return $jsonFlujo;
}
$JsonFlujograma = traducirFljujograma($StringFlujograma);

//Guardar Esfera Nueva
foreach ($JsonFlujograma->nodeDataArray as $key => $value) {

    $NombreEsfera= $value->category;
    $Coordenadas= $value->loc;
    //Actualizar Posicion Esfera InicioIVR
    if($NombreEsfera == "InicioIVR"){
        $ActualizarPosicion= "UPDATE DYALOGOCRM_SISTEMA.SECCIONES_IVRS SET COORDENADAS= '". $Coordenadas ."' WHERE ID_IVR= '". $IdIVR ."' AND TIPO_SECCION= '8';";
        $Resultado= $mysqli->query($ActualizarPosicion);
    }
    //Actualizar Posicion Esfera FinalIVR
    if($NombreEsfera == "FinalIVR"){
        $ActualizarPosicion_2= "UPDATE DYALOGOCRM_SISTEMA.SECCIONES_IVRS SET COORDENADAS= '". $Coordenadas ."' WHERE ID_IVR= '". $IdIVR ."' AND TIPO_SECCION= '9';";
        $Resultado_2= $mysqli->query($ActualizarPosicion_2);
    }

    //Actualizar La Ubicación De Las Demas Esferas Existentes
    $ActualizarPosicion_3 = "UPDATE DYALOGOCRM_SISTEMA.SECCIONES_IVRS SET COORDENADAS = '". $Coordenadas ."' WHERE ID_IVR= '". $IdIVR ."' AND ID_SECCION_IVR= {$value->key} AND TIPO_SECCION != '8'";
    $Resultado_3= $mysqli->query($ActualizarPosicion_3);
    

    // Si la key es un valor negativo realizo la creacion
    if($value->key < 0){

        $TipoEsfera= $value->tipoPaso;
        $NombreEsfera= $value->category;
        $Coordenadas= $value->loc;
        
        $InsertEsfera= "INSERT INTO DYALOGOCRM_SISTEMA.SECCIONES_IVRS (ID_HUESPED, ID_ESTPAS, ID_IVR, NOMBRE_SECCION, TIPO_SECCION, COORDENADAS, POR_DEFECTO) 
        VALUES ('". $IdHuesped ."', '". $IdEstrategia ."', '". $IdIVR ."', '". $NombreEsfera ."', '". $TipoEsfera ."', '". $Coordenadas ."', '0');";
        if ($ResultadoSQL= $mysqli->query($InsertEsfera)) {
            $IdEsfera = $mysqli->insert_id;

            //Inserción correcta
            $php_response= array("msg" => "Ok", "IdEsfera" => $IdEsfera);
            echo json_encode($php_response);
            mysqli_close($mysqli);
            exit;

        }else{
            //Error en la Insercion
            $php_response= array("msg" => "Error Insert Esfera");
            $ErrorConsulta= mysqli_error($mysqli);
            mysqli_close($mysqli);
            echo $ErrorConsulta;
            exit;
        }
        
    }

}


?>

