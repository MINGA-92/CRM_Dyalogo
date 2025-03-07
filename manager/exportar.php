<?php 
    session_start();
    
    include("pages/conexion.php");

    $strTablaTemp_t = $_GET["strTablaTemp_t"];

    $strCampoUnion_t = $_GET["strCampoUnion_t"];

    $query = "SELECT RESULTADO AS Errores,".$strCampoUnion_t." AS CampoUnion FROM dy_cargue_datos.".$strTablaTemp_t." WHERE (ESTADO = 2 OR ESTADO = 3)";
    $result = $mysqli->query($query);
    $records = array();
    while( $rows = mysqli_fetch_assoc($result) ) {
    $records[] = $rows;
    }

    $csv_file = "Errores_encontrados_".date('Ymd') . ".csv";
    header('Content-Type: application/octet-stream');
    header("Content-Transfer-Encoding: Binary");
    header("Content-Disposition: attachment; filename=".$csv_file."");
    $fh = fopen( 'php://output', 'w' );
    $is_coloumn = true;
    if(!empty($records)) {
        foreach($records as &$record) {
            if($is_coloumn) {
                fputcsv($fh, array_keys($record),';');
                $is_coloumn = false;
            }
            fputcsv($fh, array_values($record),';');
        }
    }
        fclose($fh);
    exit;

?>
