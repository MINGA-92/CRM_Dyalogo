
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

/*

if(count($JsonFlujograma->nodeDataArray) > 0){

    //Actualizar La Ubicación De Las Esferas Existentes
    foreach ($JsonFlujograma->nodeDataArray as $key => $value) {
        $ActualizarPosicionEsferas = "UPDATE DYALOGOCRM_SISTEMA.SECCIONES_IVRS SET COORDENADAS = '-641.0302734374999 119.70000381022695' 
        WHERE ID_IVR= '". $IdIVR ."' AND ID_SECCION_IVR= {$value->key}";
        print_r($ActualizarPosicionEsferas);
        print_r("\n");
        //$mysqli->query($ActualizarPosicionEsferas);

        $ActualizarPosicionFlechas = "UPDATE DYALOGOCRM_SISTEMA.SECCIONES_IVRS SET COORDENADAS = '-641.0302734374999 119.70000381022695' 
        WHERE ID_IVR= '". $IdIVR ."' AND ID_SECCION_IVR= {$value->key}";
        print_r($ActualizarPosicionFlechas);
        print_r("\n");
        //$mysqli->query($ActualizarPosicionFlechas);

    }
    exit;
}

*/
?>

