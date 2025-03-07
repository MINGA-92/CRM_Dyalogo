/*Esto es para el script */
if(isset($_POST['CrearScript'])){

    /* creamos el script */
    $nombreScript = $_POST['G5_C28']." - BD";
    $ScriptLsql = "INSERT INTO ".$BaseDatos_systema.".G5(G5_C28, G5_C29, G5_C30) VALUES ('".$nombreScript."' , 2 , 'Generado automaticamente atravez de un excel')";
    if($mysqli->query($ScriptLsql) === true){
        $ultimoGuion = $mysqli->insert_id;
        /* creamos la general */
        $Lsql_General = "INSERT INTO ".$BaseDatos_systema.".G7(G7_C60, G7_C38, G7_C36 , G7_C34, G7_C33, G7_C35) VALUES(".$ultimoGuion.", 1, 1, 1, 'GENERAL', 2)";
        if($mysqli->query($Lsql_General) === true){

            /* mandaron a generar desde el Excel */
            /* lo pirmero es obtener los nombres del Excel y meterlos en general */
            require "../../../carga/Excel.php";
            if(isset($_FILES['newGuionFile']['tmp_name']) && !empty($_FILES['newGuionFile']['tmp_name'])){

                $name   = $_FILES['newGuionFile']['name'];
                $tname  = $_FILES['newGuionFile']['tmp_name'];
                ini_set('memory_limit','128M');

                if($_FILES['newGuionFile']["type"] == 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'){
                    $objReader = new PHPExcel_Reader_Excel2007();
                    $objReader->setReadDataOnly(true);
                    $obj_excel = $objReader->load($tname);
                }else if($_FILES['newGuionFile']["type"] == 'application/vnd.ms-excel'){
                    $obj_excel = PHPExcel_IOFactory::load($tname);
                }
               
                
                $sheetData = $obj_excel->getActiveSheet()->toArray(null,true,true,true);
                $arr_datos = array();
                $highestColumm = $obj_excel->setActiveSheetIndex(0)->getHighestColumn(); // e.g. "EL"
                $highestRow = $obj_excel->setActiveSheetIndex(0)->getHighestRow();
                $highestColumm++;
                $datasets = array();
                for ($row = 1; $row < $highestRow + 1; $row++) {
                    $dataset = array();
                    for ($column = 'A'; $column != $highestColumm; $column++) {
                        $dataset[] = $obj_excel->setActiveSheetIndex(0)->getCell($column . $row)->getValue();
                    }
                    $datasets[] = $dataset;
                }
                for($i = 0; $i < count($datasets[0]); $i++){
                    /* aqui si empezamosa meter datos */
                    $Lsql_campa_campo = "INSERT INTO ".$BaseDatos_systema.".PREGUN(PREGUN_Texto_____b, PREGUN_Tipo______b, PREGUN_IndiRequ__b, PREGUN_ConsInte__SECCIO_b, PREGUN_ConsInte__GUION__b,   PREGUN_FueGener_b, PREGUN_OrdePreg__b) VALUES ('".$datasets[0][$i]."', 1, 0, ".$general.", ".$ultimoGuion.", 1, ".($i+1).");";
                    if($mysqli->query($Lsql_campa_campo) === true){

                        $lasrt = $mysqli->insert_id;
                        $Lsql_Campo = "INSERT INTO ".$BaseDatos_systema.".CAMPO_ (CAMPO__Nombre____b, CAMPO__Tipo______b, CAMPO__ConsInte__PREGUN_b) VALUES ('G".$ultimoGuion."_C".$lasrt."' , 1 , ".$lasrt.")";

                        $mysqli->query($Lsql_Campo);

                        if($i == 0){
                            $primariLsql = "UPDATE ".$BaseDatos_systema.".G5 SET G5_C31 = ".$lasrt." WHERE G5_ConsInte__b = ".$ultimoGuion;
                            if($mysqli->query($primariLsql) === true){

                            }else{
                                echo "Error Insertanndo el principal => ".$mysqli->error;
                            }
                        }


                        if($i == 1){
                            $secundariLsql = "UPDATE ".$BaseDatos_systema.".G5 SET G5_C31 = ".$lasrt." WHERE G5_ConsInte__b = ".$ultimoGuion;
                            if($mysqli->query($secundariLsql) === true){

                            }else{
                                echo "Error Insertanndo el principal => ".$mysqli->error;
                            }
                        }
                    }
                }
            }
        }



        $Lsql_Control = "INSERT INTO ".$BaseDatos_systema.".G7(G7_C60, G7_C38, G7_C36 , G7_C34, G7_C33, G7_C35) VALUES(".$ultimoGuion.", 4, 1, 2, 'CONTROL', 1)";

        if($mysqli->query($Lsql_Control) === true){
            $control = $mysqli->insert_id;
            $Lsql_Agente_origen = "INSERT INTO ".$BaseDatos_systema.".PREGUN(PREGUN_Texto_____b, PREGUN_Tipo______b, PREGUN_IndiRequ__b, PREGUN_ConsInte__SECCIO_b, PREGUN_ConsInte__GUION__b, PREGUN_ContAcce__b, PREGUN_FueGener_b ) VALUES ('ORIGEN_DY_WF', 1, 0, ".$control.", ".$ultimoGuion.", 2, 1);";
            if($mysqli->query($Lsql_Agente_origen) === true){
                
            }

            $Lsql_Agente_OPtin = "INSERT INTO ".$BaseDatos_systema.".PREGUN(PREGUN_Texto_____b, PREGUN_Tipo______b, PREGUN_IndiRequ__b, PREGUN_ConsInte__SECCIO_b, PREGUN_ConsInte__GUION__b, PREGUN_ContAcce__b, PREGUN_FueGener_b ) VALUES ('OPTIN_DY_WF', 1, 0, ".$control.", ".$ultimoGuion.", 2, 1);";
            if($mysqli->query($Lsql_Agente_OPtin) === true){
                
            }
        }
    }
}



/* Esto es para el Guion */


if(isset($_POST['CrearScript'])){

    /* creamos el script */
    $nombreScript = $_POST['G5_C28']." - SCRIPT";
    $ScriptLsql = "INSERT INTO ".$BaseDatos_systema.".G5(G5_C28, G5_C29, G5_C30) VALUES ('".$nombreScript."' , 1 , 'Generado automaticamente atravez de un excel')";
    if($mysqli->query($ScriptLsql) === true){
        $ultimoGuion = $mysqli->insert_id;
        /* creamos la general */
        $Lsql_General = "INSERT INTO ".$BaseDatos_systema.".G7(G7_C60, G7_C38, G7_C36 , G7_C34, G7_C33, G7_C35) VALUES(".$ultimoGuion.", 1, 1, 1, 'GENERAL', 2)";
        if($mysqli->query($Lsql_General) === true){
            $general = $mysqli->insert_id;

            /* mandaron a generar desde el Excel */
            /* lo pirmero es obtener los nombres del Excel y meterlos en general */
            require "../../../carga/Excel.php";
            if(isset($_FILES['newGuionFile']['tmp_name']) && !empty($_FILES['newGuionFile']['tmp_name'])){

                $name   = $_FILES['newGuionFile']['name'];
                $tname  = $_FILES['newGuionFile']['tmp_name'];
                ini_set('memory_limit','128M');

                if($_FILES['newGuionFile']["type"] == 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'){
                    $objReader = new PHPExcel_Reader_Excel2007();
                    $objReader->setReadDataOnly(true);
                    $obj_excel = $objReader->load($tname);
                }else if($_FILES['newGuionFile']["type"] == 'application/vnd.ms-excel'){
                    $obj_excel = PHPExcel_IOFactory::load($tname);
                }
               
                
                $sheetData = $obj_excel->getActiveSheet()->toArray(null,true,true,true);
                $arr_datos = array();
                $highestColumm = $obj_excel->setActiveSheetIndex(0)->getHighestColumn(); // e.g. "EL"
                $highestRow = $obj_excel->setActiveSheetIndex(0)->getHighestRow();
                $highestColumm++;
                $datasets = array();
                for ($row = 1; $row < $highestRow + 1; $row++) {
                    $dataset = array();
                    for ($column = 'A'; $column != $highestColumm; $column++) {
                        $dataset[] = $obj_excel->setActiveSheetIndex(0)->getCell($column . $row)->getValue();
                    }
                    $datasets[] = $dataset;
                }
                for($i = 0; $i < count($datasets[0]); $i++){
                    /* aqui si empezamosa meter datos */
                    $Lsql_campa_campo = "INSERT INTO ".$BaseDatos_systema.".PREGUN(PREGUN_Texto_____b, PREGUN_Tipo______b, PREGUN_IndiRequ__b, PREGUN_ConsInte__SECCIO_b, PREGUN_ConsInte__GUION__b,   PREGUN_FueGener_b, PREGUN_OrdePreg__b) VALUES ('".$datasets[0][$i]."', 1, 0, ".$general.", ".$ultimoGuion.", 1, ".($i+1).");";

                    if($mysqli->query($Lsql_campa_campo) === true){
                        $lasrt = $mysqli->insert_id;
                        $Lsql_Campo = "INSERT INTO ".$BaseDatos_systema.".CAMPO_ (CAMPO__Nombre____b, CAMPO__Tipo______b, CAMPO__ConsInte__PREGUN_b) VALUES ('G".$ultimoGuion."_C".$lasrt."' , 1 , ".$lasrt.")";

                        $mysqli->query($Lsql_Campo);
                
                        if($i == 0){
                            $primariLsql = "UPDATE ".$BaseDatos_systema.".G5 SET G5_C31 = ".$lasrt ." WHERE G5_ConsInte__b = ".$ultimoGuion;
                            if($mysqli->query($primariLsql) === true){

                            }else{
                                echo "Error Insertanndo el principal => ".$mysqli->error;
                            }
                        }


                        if($i == 1){
                            $secundariLsql = "UPDATE ".$BaseDatos_systema.".G5 SET G5_C31 = ".$lasrt ." WHERE G5_ConsInte__b = ".$ultimoGuion;
                            if($mysqli->query($secundariLsql) === true){

                            }else{
                                echo "Error Insertanndo el principal => ".$mysqli->error;
                            }
                        }
                    }
                }
            }
        }

        crearSecciones($ultimoGuion);
    }
}
