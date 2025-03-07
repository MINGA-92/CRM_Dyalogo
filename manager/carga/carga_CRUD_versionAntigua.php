<?php
    session_start();
    require "Excel.php";
    ini_set('display_errors', 'On');
    ini_set('display_errors', 1);
    include(__DIR__."/../pages/conexion.php"); 
    include(__DIR__."/../global/funcionesGenerales.php");   

    if(isset($_POST['llenarDatosGs'])){

        $baseDeDatos = $_POST['cmbControl'];
        $LsqlDetalle = "SELECT PREGUN_Texto_____b as titulo_pregunta , PREGUN_Tipo______b as tipo_Pregunta, PREGUN_ConsInte__b as id , PREGUN_ConsInte__GUION__PRE_B as guion, PREGUN_ConsInte__OPCION_B as lista, PREGUN_OrdePreg__b , PREGUN_NumeMini__b as minimoNumero, PREGUN_NumeMaxi__b as maximoNumero, PREGUN_FechMini__b as fechaMinimo, PREGUN_FechMaxi__b as fechaMaximo , PREGUN_HoraMini__b as horaMini , PREGUN_HoraMaxi__b as horaMaximo, PREGUN_TexErrVal_b as error FROM ".$BaseDatos_systema.".PREGUN WHERE PREGUN_ConsInte__GUION__b = ".$baseDeDatos." ORDER BY PREGUN_OrdePreg__b";
        //echo $LsqlDetalle;
        $campos = $mysqli->query($LsqlDetalle);
        echo "<option value=\"NONE\">SELECCIONE</option>";
        echo "<option value='ConsInte__b'>Llave Interna</option>";
        while ($key3 = $campos->fetch_object()){
            if($key3->tipo_Pregunta != '9'){
                echo "<option value='".$key3->id."'>".utf8_encode($key3->titulo_pregunta)."</option>";
            }
        }

    }

    if(isset($_POST['validar_campos_primarios'])){
        $Lslq_Siexll = "SELECT SIEXLL_LlavDest__b, SIEXLL_LlavOrig__b FROM ".$BaseDatos_systema.".SIEXLL JOIN   ".$BaseDatos_systema.".SIDAEX ON SIEXLL_ConsInte__SIDAEX_b = SIDAEX_ConsInte__b WHERE SIDAEX_Destino___b = ".$_POST['cmbControl'];
        $result_Siexll = $mysqli->query($Lslq_Siexll);
        $cur_Siexll = $result_Siexll->fetch_array();
         echo json_encode(array('Principal_origen' => $cur_Siexll['SIEXLL_LlavOrig__b'] , 'Principal_Destino' => $cur_Siexll['SIEXLL_LlavDest__b']));
    }

    if(isset($_POST['validar_configuraciones'])){
        $baseDeDatos = $_POST['cmbControl'];
        $Lsq_Siexin = "SELECT  SIEXIN_CampOrig__b, SIEXIN_CampDest__b, SIEXIN_Validacion_b FROM ".$BaseDatos_systema.".SIEXIN JOIN   ".$BaseDatos_systema.".SIDAEX ON SIEXIN_ConsInte__SIDAEX_b = SIDAEX_ConsInte__b WHERE SIDAEX_Destino___b = ".$baseDeDatos;
        //echo $Lsq_Siexin;
        $res_Siexin = $mysqli->query($Lsq_Siexin);
        $i = 0;

        while ($key = $res_Siexin->fetch_object()) {
            echo '<option value="'.$i.'">'.utf8_encode($key->SIEXIN_CampOrig__b).'</option>';
            $i++;
        } 
    }

    if(isset($_POST['obtener_configuraciones'])){
        include '../idioma.php';

        $baseDeDatos = $_POST['cmbControl'];
        $destinos = array();
        $validaciones_array = array();
        $Lsq_Siexin = "SELECT  SIEXIN_CampOrig__b, SIEXIN_CampDest__b, SIEXIN_Validacion_b FROM ".$BaseDatos_systema.".SIEXIN JOIN   ".$BaseDatos_systema.".SIDAEX ON SIEXIN_ConsInte__SIDAEX_b = SIDAEX_ConsInte__b WHERE SIDAEX_Destino___b = ".$baseDeDatos;
        //echo $Lsq_Siexin;
        $res_Siexin = $mysqli->query($Lsq_Siexin);
        $i = 0;
        while ($key = $res_Siexin->fetch_object()) {
            $destinos[$i] = $key->SIEXIN_CampDest__b;
            $validaciones_array[$i] = $key->SIEXIN_Validacion_b;
            $i++;
        } 

        if($res_Siexin->num_rows > 0){
            $LsqlDetalle = "SELECT PREGUN_Texto_____b as titulo_pregunta , PREGUN_Tipo______b as tipo_Pregunta, PREGUN_ConsInte__b as id , PREGUN_ConsInte__GUION__PRE_B as guion, PREGUN_ConsInte__OPCION_B as lista, PREGUN_OrdePreg__b , PREGUN_NumeMini__b as minimoNumero, PREGUN_NumeMaxi__b as maximoNumero, PREGUN_FechMini__b as fechaMinimo, PREGUN_FechMaxi__b as fechaMaximo , PREGUN_HoraMini__b as horaMini , PREGUN_HoraMaxi__b as horaMaximo, PREGUN_TexErrVal_b as error FROM ".$BaseDatos_systema.".PREGUN WHERE PREGUN_ConsInte__GUION__b = ".$baseDeDatos." ORDER BY PREGUN_OrdePreg__b";

            $campos_contador = $mysqli->query($LsqlDetalle);
            for($i = 0; $i < $campos_contador->num_rows; $i++){
?>
    <tr>
        <td width="33%">
            <select class="form-control select_excel" numero="<?php echo $i;?>" id="selExcel<?php echo $i;?>" name="selExcel<?php echo $i;?>">
                <option value="NONE">SELECCIONE</option>
                <?php
                    $Lsq_Siexin = "SELECT  SIEXIN_CampOrig__b, SIEXIN_CampDest__b FROM ".$BaseDatos_systema.".SIEXIN JOIN   ".$BaseDatos_systema.".SIDAEX ON SIEXIN_ConsInte__SIDAEX_b = SIDAEX_ConsInte__b WHERE SIDAEX_Destino___b = ".$baseDeDatos;
                        
                        $res_Siexin2 = $mysqli->query($Lsq_Siexin);
                        $x = 0;
                        $nombreExell = "NONE";

                        while ($key2 = $res_Siexin2->fetch_object()) {
                            if($x == $i){
                                $nombreExell = ($key2->SIEXIN_CampOrig__b);
                                echo '<option value="'.$x.'" selected>'.($key2->SIEXIN_CampOrig__b).'</option>';
                                
                            }else{
                                echo '<option value="'.$x.'">'.($key2->SIEXIN_CampOrig__b).'</option>';
                            }
                            
                            $x++;
                        } 
                ?>
            </select>
            <input type="hidden" name="nombreExell<?php echo $i;?>" id="nombreExell<?php echo $i;?>" value="<?php echo $nombreExell;?>">
        </td>
        <td width="34%">
            <select class="form-control" id="selDB<?php echo $i;?>" name="selDB<?php echo $i;?>">
                <?php
                    $LsqlDetalle = "SELECT PREGUN_Texto_____b as titulo_pregunta , PREGUN_Tipo______b as tipo_Pregunta, PREGUN_ConsInte__b as id , PREGUN_ConsInte__GUION__PRE_B as guion, PREGUN_ConsInte__OPCION_B as lista, PREGUN_OrdePreg__b , PREGUN_NumeMini__b as minimoNumero, PREGUN_NumeMaxi__b as maximoNumero, PREGUN_FechMini__b as fechaMinimo, PREGUN_FechMaxi__b as fechaMaximo , PREGUN_HoraMini__b as horaMini , PREGUN_HoraMaxi__b as horaMaximo, PREGUN_TexErrVal_b as error FROM ".$BaseDatos_systema.".PREGUN WHERE PREGUN_ConsInte__GUION__b = ".$baseDeDatos." ORDER BY PREGUN_OrdePreg__b";

                    $campos = $mysqli->query($LsqlDetalle);
                    echo "<option value=\"NONE\">SELECCIONE</option>";
                    while ($key3 = $campos->fetch_object()){
                        if($key3->tipo_Pregunta != '9'){
                            if($key3->id == $destinos[$i]){
                                echo "<option value='".$key3->id."' selected>".$key3->titulo_pregunta."</option>";    
                            }else{
                                echo "<option value='".$key3->id."'>".$key3->titulo_pregunta."</option>";
                            }
                            
                        }
                    }
                ?>
            </select>
        </td>
        <td width="33%">
            <select class="form-control" id="valDB<?php echo $i;?>" name="valDB<?php echo $i;?>">
                <option value='1'  <?php if(count($validaciones_array) > $i) { if($validaciones_array[$i] == 1){ echo "selected"; } } ?>><?php echo $str_validacion_1;?></option>
                <option value='2'  <?php if(count($validaciones_array) > $i) { if($validaciones_array[$i] == 2){ echo "selected"; } }?>><?php echo $str_validacion_2;?></option>
                 <option value='3'  <?php if(count($validaciones_array) > $i) { if($validaciones_array[$i] == 3){ echo "selected"; } }?>><?php echo $str_validacion_3;?></option>
                <option value='4'  <?php if(count($validaciones_array) > $i) { if($validaciones_array[$i] == 4){ echo "selected"; } }?>><?php echo $str_validacion_4;?></option>
                <option value='5'  <?php if(count($validaciones_array) > $i) { if($validaciones_array[$i] == 5){ echo "selected"; } }?>><?php echo $str_validacion_5;?></option>
                <option value='6'  <?php if(count($validaciones_array) > $i) { if($validaciones_array[$i] == 6){ echo "selected"; } }?>><?php echo $str_validacion_6;?></option>
                <option value='7'  <?php if(count($validaciones_array) > $i) { if($validaciones_array[$i] == 7){ echo "selected"; } }?>><?php echo $str_validacion_7;?></option> 
            </select>
        </td>
    </tr>
<?php
            }
        }

        $SIDAEXLSQL = "SELECT SIDAEX_RutaArchi_b FROM ".$BaseDatos_systema.".SIDAEX WHERE SIDAEX_Destino___b = ".$baseDeDatos;
        $resSLql  = $mysqli->query($SIDAEXLSQL);
        $datos = $resSLql->fetch_array();
        echo "<script type='text/javascript'>$('#NombrearcExcell__BD').val('".$datos['SIDAEX_RutaArchi_b']."');</script>";
    }


    /** YCR - 2019-08-30 
    * Obtenemos columnbas del excel
    */
    if(isset($_GET['getcolumns'])){

        $name   = $_FILES['arcExcell']['name'];
        $tname  = $_FILES['arcExcell']['tmp_name'];
        ini_set('memory_limit','128M');

        if($_FILES['arcExcell']["type"] == 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'){
            $objReader = new PHPExcel_Reader_Excel2007();
            $objReader->setReadDataOnly(true);
            $obj_excel = $objReader->load($tname);
        }else if($_FILES['arcExcell']["type"] == 'application/vnd.ms-excel'){
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
                $datasets[$row-1] = $dataset;
            }
        }
        $datos  = array();
        for($i = 0; $i < count($datasets[0]); $i++){
            $datos[$i]['valor'] = $i;
            $datos[$i]['Nombres'] = $datasets[0][$i];
        }

        echo json_encode(array('total' => count($datasets[0]) , 'opciones' => $datos));
    }
    /** YCR - 2019-08-28
    * Aqui hacemos el llenado de datos 
    */
    if(isset($_GET['llenarDatos'])){

        $name   = $_FILES['arcExcell']['name'];
        $tname  = $_FILES['arcExcell']['tmp_name'];
        ini_set('memory_limit','1024M');
        ini_set('max_execution_time', 300);


        $datosArray = array(
        'A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z', 
        'AA', 'AB', 'AC', 'AD', 'AE', 'AF', 'AG', 'AH', 'AI', 'AJ', 'AK', 'AL', 'AM', 'AN', 'AO', 'AP', 'AQ', 'AR', 'AS', 'AT', 'AU', 'AV', 'AW', 'AX', 'AY', 'AZ', 
        'BA', 'BB', 'BC', 'BD', 'BE', 'BF', 'BG', 'BH', 'BI', 'BJ', 'BK', 'BL', 'BM', 'BN', 'BO', 'BP', 'BQ', 'BR', 'BS', 'BT', 'BU', 'BV', 'BW', 'BX', 'BY', 'BZ', 
        'CA', 'CB', 'CC', 'CD', 'CE', 'CF', 'CG', 'CH', 'CI', 'CJ', 'CK', 'CL', 'CM', 'CN', 'CO', 'CP', 'CQ', 'CR', 'CS', 'CT', 'CU', 'CV', 'CW', 'CX', 'CY', 'CZ', 
        'DA', 'DB', 'DC', 'DD', 'DE', 'DF', 'DG', 'DH', 'DI', 'DJ', 'DK', 'DL', 'DM', 'DN', 'DO', 'DP', 'DQ', 'DR', 'DS', 'DT', 'DU', 'DV', 'DW', 'DX', 'DY', 'DZ', 
        'EA', 'EB', 'EC', 'ED', 'EE', 'EF', 'EG', 'EH', 'EI', 'EJ', 'EK', 'EL', 'EM', 'EN', 'EO', 'EP', 'EQ', 'ER', 'ES', 'ET', 'EU', 'EV', 'EW', 'EX', 'EY', 'EZ');

        if($_FILES['arcExcell']["type"] == 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'){
            $objReader = new PHPExcel_Reader_Excel2007();
            $objReader->setReadDataOnly(true);
            $obj_excel = $objReader->load($tname);
        }else if($_FILES['arcExcell']["type"] == 'application/vnd.ms-excel'){
            $obj_excel = PHPExcel_IOFactory::load($tname);
        }
       
        /** YCR - 2019-08-28
        *  objeto con registros de excel
        */
        $sheetData = $obj_excel->getActiveSheet()->toArray(null,true,true,true);
        $arr_datos = array();
        $highestColumm = $obj_excel->setActiveSheetIndex(0)->getHighestColumn(); // e.g. "EL"
        $highestRow = $obj_excel->setActiveSheetIndex(0)->getHighestRow();

        $baseDeDatos        = $_POST['cmbControl'];
        //columna principal Excel
        $ColumnaPrincipal   = $_POST['cmbColumnaP'];
        //columna prinicpal BD
        $ColumnaPrincipald  = $_POST['cmbColumnaD'];
        if(isset($_POST['cmbAgent'])){
            if($_POST['cmbAgent'] != 'NONE'){
                $ColumnaAgente  = $_POST['cmbAgent'];
            }
        }

        $TotalColumnas      = $_POST['totales'];
        $idPaso             = $_POST['pasoId'];
        $guion = 'G'.$baseDeDatos;
        $esUnaBaseDedatos  = false;
        $hayqueinsertarMuestra = false;
        $muestraAlaquetocainsertar = '';
        $confDinamica = 0;
        $campana = 0;


        /* validar si ese escript es de tipo poblacion */
        $guionLsql = "SELECT GUION__Tipo______b FROM ".$BaseDatos_systema.".GUION_ WHERE GUION__ConsInte__b = ".$baseDeDatos;
        $res_GuionLsql = $mysqli->query($guionLsql);
        $arrayGuionLql = $res_GuionLsql->fetch_array();
        if($arrayGuionLql['GUION__Tipo______b'] == 2){
            $esUnaBaseDedatos = true;
        }

        if($esUnaBaseDedatos == true){
            /* preguntamos si un paso siguiente es campaña */
            $connectorLsql = "SELECT ESTCON_ConsInte__ESTPAS_Has_b FROM ".$BaseDatos_systema.".ESTCON WHERE ESTCON_ConsInte__ESTPAS_Des_b = ".$idPaso;

            $resConnectorLsql = $mysqli->query($connectorLsql);
            /* recorremos la consulta */
            while ($key = $resConnectorLsql->fetch_object()) {
                /* preguntamos si ese paso es de tipo campaña saliente u entrante */
                $estpasLsql = "SELECT ESTPAS_Tipo______b, ESTPAS_ConsInte__CAMPAN_b FROM ".$BaseDatos_systema.".ESTPAS WHERE ESTPAS_ConsInte__b = ".$key->ESTCON_ConsInte__ESTPAS_Has_b;
                $resEstpasLsql = $mysqli->query($estpasLsql);
                $arrayEstpasLsql = $resEstpasLsql->fetch_array();
                /* Preguntamos si ese paso es de tipo llamada */
                if($arrayEstpasLsql['ESTPAS_Tipo______b'] == 6 || $arrayEstpasLsql['ESTPAS_Tipo______b'] == 1 ){
                    /* si cumple con estas condiciones ese paso que sigue es una campaña */
                    $campanLsql = "SELECT CAMPAN_ConsInte__GUION__Pob_b, CAMPAN_ConsInte__MUESTR_b, CAMPAN_ConfDinam_b FROM ".$BaseDatos_systema.".CAMPAN WHERE CAMPAN_ConsInte__b = ".$arrayEstpasLsql['ESTPAS_ConsInte__CAMPAN_b'];
                    $campana = $arrayEstpasLsql['ESTPAS_ConsInte__CAMPAN_b'];
                    $resCampanLsql = $mysqli->query($campanLsql);
                    /* recorremos la consulta de la campaña */
                    while ($keyCampan = $resCampanLsql->fetch_object()) {
                        if ($keyCampan->CAMPAN_ConsInte__GUION__Pob_b == $baseDeDatos) {
                            /* es la base de datos que estamos cargando */
                            $hayqueinsertarMuestra = true;
                            /* asignamos la muestra para saber cual es */
                            $muestraAlaquetocainsertar = $keyCampan->CAMPAN_ConsInte__MUESTR_b;
                            $confDinamica = $keyCampan->CAMPAN_ConfDinam_b;
                        }
                    }
                }
            }    
        }

        if(isset($_POST['muestraAInsertar'])){
            if($_POST['muestraAInsertar'] == 'no'){
                $hayqueinsertarMuestra = false;
            }else{
                $hayqueinsertarMuestra = true;
                $muestraAlaquetocainsertar = $_POST['muestraAInsertar']; 

                $Lsql = "SELECT CAMPAN_ConsInte__b, CAMPAN_ConfDinam_b, CAMPAN_ConsInte__GUION__Gui_b, CAMPAN_ConsInte__GUION__Pob_b, CAMPAN_ConsInte__MUESTR_b FROM ".$BaseDatos_systema.".CAMPAN WHERE CAMPAN_ConsInte__MUESTR_b = ".$muestraAlaquetocainsertar." AND CAMPAN_ConsInte__GUION__Pob_b = ".$baseDeDatos;
               //obtenemos la informacion de la campaña
                $res = $mysqli->query($Lsql);
                $datoLsql = $res->fetch_array();
                $campana = $datoLsql['CAMPAN_ConsInte__b'];
                $Guion         = $datoLsql['CAMPAN_ConsInte__GUION__Gui_b'];
                $Poblacion     = $datoLsql['CAMPAN_ConsInte__GUION__Pob_b'];
                $Muestra       = $datoLsql['CAMPAN_ConsInte__MUESTR_b'];
                $confDinamica  = $datoLsql['CAMPAN_ConfDinam_b']; 
            }
        }

        $total  = 0;
        $nuevos = 0;
        $existe = 0;
        $totalR = 0;
        $exitos = 0;
        $falla  = 0;    
        $fallaValidacion = 0;
        $csvReal = array();
        $fechaInsercion=date('Y-m-d H:i:s');
        $origenDY=$fechaInsercion."_".$_POST['NombrearcExcell'];
        $llaveCargue=time();
        $idPreguntOrigen='';
        $existeOrigen=0;
        $estadoValidacion=$_POST['estadoValidacion'];
        $tipoCampan="";



        //Aqui empieza la carga del excel
        if($muestraAlaquetocainsertar == ''){
            $XsqlSIDAEX = "SELECT SIDAEX_ConsInte__b FROM ".$BaseDatos_systema.".SIDAEX WHERE SIDAEX_Destino___b = ".$baseDeDatos;
        }else{

            $XsqlSIDAEX = "SELECT SIDAEX_ConsInte__b FROM ".$BaseDatos_systema.".SIDAEX WHERE SIDAEX_Muestra___b = ".$muestraAlaquetocainsertar;
        }

        $res_XsqlSIDAEX = $mysqli->query($XsqlSIDAEX);
        //$cur_XsqlSIDAEX = $res_XsqlSIDAEX->fetch_array();

        foreach ($res_XsqlSIDAEX as $key => $cur_XsqlSIDAEX) {
            
            $DeleteSql1 = "DELETE FROM  ".$BaseDatos_systema.".SIDAEX WHERE SIDAEX_ConsInte__b = ".$cur_XsqlSIDAEX['SIDAEX_ConsInte__b'];
            $mysqli->query($DeleteSql1);
            $DeleteSql2 = "DELETE FROM  ".$BaseDatos_systema.".SIEXLL WHERE SIEXLL_ConsInte__SIDAEX_b = ".$cur_XsqlSIDAEX['SIDAEX_ConsInte__b'];
            $mysqli->query($DeleteSql2);
            $DeleteSql3 = "DELETE FROM  ".$BaseDatos_systema.".SIEXIN WHERE SIEXIN_ConsInte__SIDAEX_b = ".$cur_XsqlSIDAEX['SIDAEX_ConsInte__b'];
            $mysqli->query($DeleteSql3);
            $DeleteSql4 = "DELETE FROM  ".$BaseDatos_systema.".SIEXAC WHERE SIEXAC_ConsInte__SIDAEX_b = ".$cur_XsqlSIDAEX['SIDAEX_ConsInte__b'];
            $mysqli->query($DeleteSql4);
        }


        //Primero metemos los datos en SIDAEX Para crear la Llave primaria
        $InsertSIDAEX = "INSERT INTO ".$BaseDatos_systema.".SIDAEX (SIDAEX_Nombre____b, SIDAEX_Destino___b, SIDAEX_TipoOrig__b, SIDAEX_TipoInse_b, SIDAEX_RutaArchi_b, SIDAEX_Muestra___b) VALUES ('Cargue de datos G".$baseDeDatos."', ".$baseDeDatos.", 2 , ".$_POST['cmbAction'].", '".$_POST['NombrearcExcell']."' , '".$_POST['muestraAInsertar']."')";

        if($mysqli->query($InsertSIDAEX) === true){

            $ultimoSidaex = $mysqli->insert_id;

            if ($ColumnaPrincipald != "ConsInte__b") {            
                $LsqL = "SELECT * FROM ".$BaseDatos_systema.".PREGUN WHERE PREGUN_ConsInte__b = ".$ColumnaPrincipald;
                $resultaado = $mysqli->query($LsqL);
                $res_cur = $resultaado->fetch_array();

                $InsertSIEXLL = "INSERT INTO ".$BaseDatos_systema.".SIEXLL (SIEXLL_ConsInte__SIDAEX_b, SIEXLL_LlavOrig__b, SIEXLL_LlavDest__b, SIEXLL__TipoCam__b, SIEXLL__Texto_____b) VALUES(".$ultimoSidaex.", '".$_POST['txtLlaveExcell']."', ".$ColumnaPrincipald.", ".$res_cur['PREGUN_Tipo______b'].", '".$res_cur['PREGUN_Texto_____b']."')";
                $mysqli->query($InsertSIEXLL);
            }


            //Aqui empezamos la jugada de los campos parra insertar u Actualizar        
            for ($i=0; $i < $TotalColumnas; $i++) {
                if($_POST['selDB'.$i] != 'NONE'){
                    $LsqL = "SELECT * FROM ".$BaseDatos_systema.".PREGUN WHERE PREGUN_ConsInte__b = ".$_POST['selDB'.$i];
                    
                    $resultaado = $mysqli->query($LsqL);
                    $res_cur = $resultaado->fetch_array();

                    $InsetarSIEXIN = "INSERT INTO ".$BaseDatos_systema.".SIEXIN ( SIEXIN_ConsInte__SIDAEX_b , SIEXIN_CampOrig__b , SIEXIN_CampDest__b , SIEXIN__TipoCam__b , SIEXIN__Texto_____b, SIEXIN_Validacion_b ) VALUES (".$ultimoSidaex.", '".$_POST['nombreExell'.$i]."',".$_POST['selDB'.$i].", ".$res_cur['PREGUN_Tipo______b'].", '".$res_cur['PREGUN_Texto_____b']."' , ".$_POST['valDB'.$i].");";


                    $mysqli->query($InsetarSIEXIN);    
                }
            }
        }

        /**YCR 2019-09-20
        *Obtenemos los patrones de marcacion
        */
        $arrayPatron = [];
        $p = 0;
        $Lsql="SELECT td.patron FROM dyalogo_telefonia.pasos_troncales as p
                join dyalogo_telefonia.tipos_destino as td
                on p.id_tipos_destino = td.id
                where  id_campana =".$_POST['strIdCampan'];


        if( ($results = $mysqli->query($Lsql))  == TRUE ){
            if( $results->num_rows > 0 ){
            while($key = $results->fetch_object()){
                    $arrayPatron[$p] = $key->patron;
                    $p++;
                }
                
            }
        }
        
        $x = 0;
        /** YCR - 2019-08-28
        *  recorremos objeto que tiene registros del excel
        */
        foreach ($sheetData as $index => $value) {
            if ( $index > 1 ){
                if((!is_null($value['A']) OR !empty($value['A']))){
                
                    $total++;
                    $InsertarRegistro = 1;
                    $validador = 1;
                    $columnaCSV = "";
                    $validacion_S = "";
                    $estado = $estadoValidacion;
                    $arrayNombres=[];
                    $arrayData=[];
                    $estadoLog=1;
                    $separador = " , ";
                    //consulta para traer datos de cargue por defecto
                    
                    //Primero declaramos una variable, para los inserts
                    if( isset($_POST['origenDY']) && $_POST['origenDY'] == '1' ){
                        //existe ORIGEN_DY
                        $INSERT = "INSERT INTO ".$BaseDatos.".".$guion." (".$guion."_FechaInsercion , ".$guion."_EstadoDiligenciamiento ";
                        $VALUES = " VALUES ('".$fechaInsercion."' , '".$llaveCargue."' ";
                        $UPDATE = "UPDATE ".$BaseDatos.".".$guion." SET  ".$guion."_EstadoDiligenciamiento  = '".$llaveCargue."' ";
                        
                    }else{
                        //no biene un origen
                        $existeOrigen=1;
                        $query = "SELECT PREGUN_Texto_____b ,PREGUN_ConsInte__b FROM  ".$BaseDatos_systema.".PREGUN WHERE PREGUN_ConsInte__GUION__b =".$baseDeDatos;
                        
                        $results = $mysqli->query($query);

                        while($key = $results->fetch_object() ){

                            if($key->PREGUN_Texto_____b == 'ORIGEN_DY_WF'){
                                $idPreguntOrigen= $key->PREGUN_ConsInte__b;
                            }
                        
                        }

                        $INSERT = "INSERT INTO ".$BaseDatos.".".$guion." (".$guion."_FechaInsercion, ".$guion."_C".$idPreguntOrigen.",".$guion."_EstadoDiligenciamiento ";
                        $VALUES = " VALUES ('".$fechaInsercion."' , '".$origenDY."' , '".$llaveCargue."' ";
                        $UPDATE = "UPDATE ".$BaseDatos.".".$guion." SET  ".$guion."_C".$idPreguntOrigen." = '".$origenDY."'  , ".$guion."_EstadoDiligenciamiento  = '".$llaveCargue."' ";                              
                        
                    }                                         


                    /** YCR - 2019-08-28
                    *  recorremos cada fila del excel
                    */
                    for ($i=0; $i < $TotalColumnas; $i++) {                        

                        if($_POST['selExcel'.$i] != 'NONE'  && $_POST['selDB'.$i] != 'NONE'){
                            $LsqL = "SELECT * FROM ".$BaseDatos_systema.".PREGUN WHERE PREGUN_ConsInte__b = ".$_POST['selDB'.$i];
                            $resultaado = $mysqli->query($LsqL);
                            $arrayNombres[$i]=$_POST['nombreExell'.$i];
                            $arrayData[$i]=$value[$datosArray[$_POST['selExcel'.$i]]];
                            $variable = $value[$datosArray[$_POST['selExcel'.$i]]];

                            /** ciclo para verificar los tipos de campos  */
                            while ($key = $resultaado->fetch_object()) {

                                if($key->PREGUN_Tipo______b == '6' || $key->PREGUN_Tipo______b == '13'){ // si es de tipo lisopc                                   

                                    $LsqlLisopC = "SELECT LISOPC_ConsInte__b AS OPCION_ConsInte__b, LISOPC_Nombre____b AS OPCION_Nombre____b FROM ".$BaseDatos_systema.".LISOPC WHERE LISOPC_ConsInte__OPCION_b = ".$key->PREGUN_ConsInte__OPCION_B;
                                    //echo $LsqlLisopC."\n";
                                    $LisopResult = $mysqli->query($LsqlLisopC);
                                    while ($Lisop = $LisopResult->fetch_object()) {
                                        //echo "Valor => ".$variable;
                                        //echo "BaseDatos => ". strtoupper($Lisop->OPCION_Nombre____b);
                                        if(strtoupper($variable) == strtoupper($Lisop->OPCION_Nombre____b)){
                                            $variable = $Lisop->OPCION_ConsInte__b;
                                        }
                                    }

                                }   

                                if($key->PREGUN_Tipo______b == '11'){//si es un pinche GUION
                                    $Lsql1 = "SELECT * FROM ".$BaseDatos_systema.".GUION_ WHERE GUION__ConsInte__b = ".$key->PREGUN_ConsInte__GUION__PRE_B;
                                    $res = $mysqli->query($Lsql1);
                                    $ColumnaPrincipalGuion = $res->fetch_array();

                                    $Lsql2 = "SELECT G".$key->PREGUN_ConsInte__GUION__PRE_B."_ConsInte__b as id , G".$key->PREGUN_ConsInte__GUION__PRE_B."_C".$ColumnaPrincipalGuion['GUION__ConsInte__PREGUN_Pri_b']." as campoPrincipal FROM ".$BaseDatos.".G".$key->PREGUN_ConsInte__GUION__PRE_B;

                                    //echo $Lsql2;
                                    $guionResult = $mysqli->query($Lsql2);

                                    while ($guionG = $guionResult->fetch_object()) {
                                        if(strtoupper(utf8_encode($guionG->campoPrincipal)) == strtoupper(utf8_encode($variable)) ){
                                            $variable = $guionG->id;
                                        }
                                    }
                                    
                                }

                                if($key->PREGUN_Tipo______b == '5'){//tipo fecha

                                    if(is_numeric($variable)){
                                        $UNIX_DATE = ($variable - 25569) * 86400;
                                        $variable = gmdate("Y-m-d H:i:s", $UNIX_DATE);

                                    }else{
                                        $arrayVariable = explode('/', $variable);
                                    
                                        if(count($arrayVariable) > 1){
                                            $totalLongitud = $arrayVariable[0];
                                            
                                            if($totalLongitud < 10){
                                                $totalLongitud = "0".$arrayVariable[0];
                                            }
                                            $variable = $arrayVariable[2] ."-".$arrayVariable[1]."-".$totalLongitud;
                                        }
                                    }
                                    
                                    
                                }

                                if($key->PREGUN_Tipo______b == '10'){//tipo hora
                                    $fecha = date('Y-m-d');
                                    if(is_numeric($variable)){
                                        $variable=$variable * 86400;
                                        $variable = gmdate("H:i:s",$variable); 
                                        $variable = $fecha." ".$variable;

                                    }else{

                                        $variable = $fecha." ".$variable;
                                    
                                    }     
                                    
                                }

                                if($key->PREGUN_Tipo______b == '8'){//tipo hora
                                    if(strtoupper($variable) == 'SI' || strtoupper($variable) == 'TRUE' || strtoupper($variable) == '1'){
                                        $variable = 1;
                                    }else if(strtoupper($variable) == 'NO' || strtoupper($variable) == 'FALSE' || strtoupper($variable) == '0'){
                                        $variable = -1;
                                    }       
                                                                        
                                }



                            }



                            if($_POST['valDB'.$i] != 1){ // Si es 1 es que no hay que validar
                                switch ($_POST['valDB'.$i]) {                        

                                    case '2': // validar que sea un telefono o celular
                                        $columnaCSV .= "[".$_POST['nombreExell'.$i]."]";                                                
                                        if( is_numeric($variable) && strpos($variable,'.') == false ){

                                            if( count($arrayPatron) > 0 ){
                                                foreach ($arrayPatron as $key => $strPatron) {
                                                   if(strlen($strPatron) == strlen($variable)){
                                                        $estado=1;                                                                  
                                                    } 
                                                }

                                            }else{
                                                echo json_encode(array('mensaje_error' => "Esta campana no tinene una troncal configurada,por favor configurala y vuelve a subir el archivo"));
                                                return false; 
                                            }                                             
                                                                                      
                                            
                                        }  

                                    break;

                                }
                            }


                            
                            //Terminamos  de armar la consulta a insertar                                
                            //Si esto viene null o vacio  , no lo agregamos a la consulta 
                            if(!is_null($variable) && $variable != '' && !empty($variable) && $variable != 'NULL'){
                                                                                //si el campo es unico
                                $Lsql = "SELECT PREGUN_IndiUnic__b, PREGUN_Texto_____b FROM ".$BaseDatos_systema.".PREGUN WHERE PREGUN_ConsInte__b = ".$_POST['selDB'.$i].";";
                                $res = $mysqli->query($Lsql);
                                if($res->num_rows > 0){
                                    $datoLsql = $res->fetch_array();
                                    if($datoLsql['PREGUN_IndiUnic__b'] == 1){
                                        //valida que el valor que esta insertando no este en la BD
                                        $Lsql = "SELECT * FROM ".$BaseDatos.".".$guion." WHERE ".$guion."_C".$_POST['selDB'.$i]." = '".$variable."';";
                                        $res = $mysqli->query($Lsql);
                                        if($res->num_rows > 0){
                                            $estado=0;
                                            $fallaValidacion++;
                                            $InsertarRegistro = 0;
                                                $LsqlInsertLog = "INSERT INTO ".$BaseDatos_systema.".LOG_CARGUE (LOG_CARGUE_Fila_b,LOG_CARGUE_Columna_b,LOG_CARGUE_Mensaje_b, LOG_CARGUE_Token_b,LOG_CARGUE_Nombres_b,LOG_CARGUE_Data_b) VALUES (".$index." , '".$columnaCSV."' ,'".$validacion_S."', '".$_SESSION['TOKEN']."'  , '".json_encode($arrayNombres)."' , '".json_encode($arrayData)."' )";
                                            //echo $LsqlInsertLog;
                                            $mysqli->query($LsqlInsertLog);
                                        }
                                    }
                                }
                                
                                $INSERT .= " , ".$guion."_C".$_POST['selDB'.$i];
                                $VALUES .= " , '".$variable."'";
                                $UPDATE .= $separador." ".$guion."_C".$_POST['selDB'.$i]." = '".$variable."'";                                 
                            }
                            
                        }

                        
                    }                        

                    if ($ColumnaPrincipald == "ConsInte__b") {
                        $Lsql = "SELECT * FROM ".$BaseDatos.".".$guion." WHERE ".$guion."_ConsInte__b = '".$value[$datosArray[$ColumnaPrincipal]]."';";    
                    }else{
                        $Lsql = "SELECT * FROM ".$BaseDatos.".".$guion." WHERE ".$guion."_C".$ColumnaPrincipald." = '".$value[$datosArray[$ColumnaPrincipal]]."';";
                    }
                        
                    
                    $res = $mysqli->query($Lsql);

                    if($res && $res->num_rows > 0){ // Si ese registro existe

                        if( ($_POST['cmbAction'] == '3')){                                                                            
                            
                            if($estado == 1){
                                $columnaCSV = "";                               
                                $Lsql="SELECT * FROM ".$BaseDatos_systema.".MONOEF WHERE MONOEF_ConsInte__b = -16";
                                $res = $mysqli->query($Lsql);
                                $DataDefault = $res->fetch_array();

                                $UPDATE .= $separador." ".$guion."_UltiGest__b = '".$DataDefault['MONOEF_ConsInte__b']."' , ".$guion."_TipoReintentoUG_b = '".$DataDefault['MONOEF_TipNo_Efe_b']."', ".$guion."_ClasificacionUG_b = '".$DataDefault['MONOEF_Contacto__b']."' ,".$guion."_EstadoUG_b = '".$DataDefault['MONOEF_TipiCBX___b']."' , ".$guion."_CantidadIntentos = 0"; 
                                
                                if ($ColumnaPrincipald == "ConsInte__b") {
                                    $Lsql = $UPDATE." WHERE ".$guion."_ConsInte__b = '".$value[$datosArray[$ColumnaPrincipal]]."';";
                                }else{
                                    $Lsql = $UPDATE." WHERE ".$guion."_C".$ColumnaPrincipald." = '".$value[$datosArray[$ColumnaPrincipal]]."';";
                                }

                                if($mysqli->query($Lsql) === true){
                                    $existe++;
                                    /*Ahora debemos activar estos registros*/
                                    if($_POST['cheRegistrosCargarNuevo'] == "1"){
                                    /*Debemos obtener el id del registro*/
                                        if ($ColumnaPrincipald == "ConsInte__b") {
                                            $Lsql_OId = "SELECT ".$guion."_ConsInte__b as id FROM ".$BaseDatos.".".$guion." WHERE ".$guion."_ConsInte__b = '".$value[$datosArray[$ColumnaPrincipal]]."';";        
                                        }else{
                                            $Lsql_OId = "SELECT ".$guion."_ConsInte__b as id FROM ".$BaseDatos.".".$guion." WHERE ".$guion."_C".$ColumnaPrincipald." = '".$value[$datosArray[$ColumnaPrincipal]]."';";
                                        }

                                        $res_OId = $mysqli->query($Lsql_OId);
                                        if($res_OId){
                                            $ArrayDatos = $res_OId->fetch_array();
                                            $muestraCompleta = $guion."_M".$muestraAlaquetocainsertar;

                                            $Lsql_Valida_M = "SELECT * FROM ".$BaseDatos.".".$muestraCompleta." WHERE  ".$muestraCompleta."_CoInMiPo__b = ".$ArrayDatos['id'];
                                            $res_Valida_M = $mysqli->query($Lsql_Valida_M);
                                            if($res_Valida_M){
                                                if($res_Valida_M->num_rows > 0){

                                                    $regM = $res_Valida_M->fetch_array();

                                                    if ($regM[$muestraCompleta."_Estado____b"] == 3 || $regM[$muestraCompleta."_Estado____b"] == 0) {
                                                        $status = " ,".$muestraCompleta."_Estado____b = 1 " ;
                                                    }else{
                                                        $status = "";
                                                    }


                                                    /*La muestra existe, procedemos a actualizar*/
                                                    
                                                    $insertarMuestraLsql = "UPDATE ".$BaseDatos.".".$muestraCompleta." SET ".$muestraCompleta."_NumeInte__b = 0, ".$muestraCompleta."_Activo____b = -1 ".$status." WHERE  ".$muestraCompleta."_CoInMiPo__b = ".$ArrayDatos['id'];
                                                    $mysqli->query($insertarMuestraLsql);
                                                    
                                                }else{
                                                    /**
                                                     * La muestra no existe y nos toca insertarla
                                                     */
                                                    // si es asignacion definida asigna el usuario en la muestra
                                                    if(isset($ColumnaAgente)){
                                                        if($ColumnaAgente != 'NONE'){
                                                            //valida que el usuario existe por el correo
                                                            $Lsql = 'SELECT USUARI_ConsInte__b, USUARI_UsuaCBX___b FROM '.$BaseDatos_systema.'.USUARI WHERE USUARI_Correo___b = "'.$value[$datosArray[$ColumnaAgente]].'";';
                                                            $res = $mysqli->query($Lsql);
                                                            if($res->num_rows > 0 && $value[$datosArray[$ColumnaAgente]] != '' && !is_null($value[$datosArray[$ColumnaAgente]])){
                                                                $datoLsql = $res->fetch_array();
                                                                //valida que el usuario este asignado a la campaña
                                                                $Lsql = 'SELECT ASITAR_ConsInte__USUARI_b FROM '.$BaseDatos_systema.'.ASITAR WHERE ASITAR_ConsInte__USUARI_b = '.$datoLsql['USUARI_ConsInte__b'].' AND ASITAR_ConsInte__CAMPAN_b = '.$campana.';';
                                                                $res = $mysqli->query($Lsql);
                                                                if($res->num_rows == 0){
                                                                    //el usuario no esta asignado a la campaña, se procede a asignar
                                                                    $Lsql = 'INSERT INTO '.$BaseDatos_systema.'.ASITAR (ASITAR_ConsInte__CAMPAN_b,ASITAR_ConsInte__USUARI_b,ASITAR_ConsInte__GUION__Gui_b,ASITAR_ConsInte__GUION__Pob_b,ASITAR_ConsInte__MUESTR_b,ASITAR_UsuarioCBX_b) VALUES ('.$campana.','.$datoLsql['USUARI_ConsInte__b'].','.$Guion.','.$Poblacion.','.$Muestra.','.$datoLsql['USUARI_UsuaCBX___b'].');';
                                                                    $mysqli->query($Lsql);
                                                                }
                                                                /* ahora insertamos los datos en la muestra */
                                                    
                                                                $insertarMuestraLsql = "INSERT INTO  ".$BaseDatos.".".$muestraCompleta." (".$muestraCompleta."_CoInMiPo__b ,  ".$muestraCompleta."_NumeInte__b, ".$muestraCompleta."_Estado____b , ".$muestraCompleta."_ConIntUsu_b) VALUES (".$ArrayDatos['id'].", 0 , 0, ".$datoLsql['USUARI_ConsInte__b'].");";
                                                                $mysqli->query($insertarMuestraLsql);
                                                            }
                                                        }
                                                    }else{
                                                        $Xlsql = "SELECT ASITAR_ConsInte__USUARI_b, COUNT(".$muestraCompleta."_ConIntUsu_b) AS total FROM ".$BaseDatos_systema.".ASITAR LEFT JOIN ".$BaseDatos.".".$muestraCompleta." ON ASITAR_ConsInte__USUARI_b = ".$muestraCompleta."_ConIntUsu_b WHERE ASITAR_ConsInte__CAMPAN_b = ".$campana." AND (".$muestraCompleta."_Estado____b <> 3 or (".$muestraCompleta."_Estado____b IS NULL)) GROUP BY ASITAR_ConsInte__USUARI_b ORDER BY COUNT(".$muestraCompleta."_ConIntUsu_b) LIMIT 1;";
                                                        $res = $mysqli->query($Xlsql);
                                                        if($res){
                                                            $datoLsql = $res->fetch_array();

                                                            /* ahora insertamos los datos en la muestra */
                                                        
                                                            $insertarMuestraLsql = "INSERT INTO  ".$BaseDatos.".".$muestraCompleta." (".$muestraCompleta."_CoInMiPo__b ,  ".$muestraCompleta."_NumeInte__b, ".$muestraCompleta."_Estado____b , ".$muestraCompleta."_ConIntUsu_b) VALUES (".$ArrayDatos['id'].", 0 , 0, ".$datoLsql['ASITAR_ConsInte__USUARI_b'].");";
                                                            $mysqli->query($insertarMuestraLsql);
                                                        }else{
                                                            $insertarMuestraLsql = "INSERT INTO  ".$BaseDatos.".".$muestraCompleta." (".$muestraCompleta."_CoInMiPo__b ,  ".$muestraCompleta."_NumeInte__b, ".$muestraCompleta."_Estado____b) VALUES (".$ArrayDatos['id'].", 0 , 0);";
                                                        }
                                                        
                                                    }
                                                }
                                            }                                            
                                        }
                                    } // JDBD - cierre if check marcar de nuevo.   

                                    $exitos++;
                                }else{                    
                                    $fallaValidacion++;
                                    $estadoLog=0;                                         
                                    $error=str_replace("'", "", $mysqli->error);    
                                    $arrayErrores=buscadorErrores("actualizar",$error);
                                    $columnaCSV .=$arrayErrores[0];
                                    $validacion_S .=$arrayErrores[1];                                               
                                } 
                            }else{
                                $estadoLog=0;
                                $fallaValidacion++; 
                                $validacion_S="[Verifique que el telefono sea valido , que  no tenga caracteres especiales,tenga espacios, o este vacio]";                                            
                            }  
                        }
                        // si es asignacion definida asigna el usuario en la muestra
                        if($estado == 1){
                            if(isset($ColumnaAgente)){
                                if($ColumnaAgente != 'NONE'){
                                    //valida que el usuario existe por el correo
                                    $Lsql = 'SELECT USUARI_ConsInte__b, USUARI_UsuaCBX___b FROM '.$BaseDatos_systema.'.USUARI WHERE USUARI_Correo___b = "'.$value[$datosArray[$ColumnaAgente]].'";';
                                    $res = $mysqli->query($Lsql);
                                    if($res->num_rows > 0 && $value[$datosArray[$ColumnaAgente]] != '' && !is_null($value[$datosArray[$ColumnaAgente]])){
                                        $datoLsql = $res->fetch_array();
                                        //valida que el usuario este asignado a la campaña
                                        $Lsql = 'SELECT ASITAR_ConsInte__USUARI_b FROM '.$BaseDatos_systema.'.ASITAR WHERE ASITAR_ConsInte__USUARI_b = '.$datoLsql['USUARI_ConsInte__b'].' AND ASITAR_ConsInte__CAMPAN_b = '.$campana.';';
                                        $res = $mysqli->query($Lsql);
                                        if($res->num_rows == 0){
                                            //el usuario no esta asignado a la campaña, se procede a asignar
                                            $Lsql = 'INSERT INTO '.$BaseDatos_systema.'.ASITAR (ASITAR_ConsInte__CAMPAN_b,ASITAR_ConsInte__USUARI_b,ASITAR_ConsInte__GUION__Gui_b,ASITAR_ConsInte__GUION__Pob_b,ASITAR_ConsInte__MUESTR_b,ASITAR_UsuarioCBX_b) VALUES ('.$campana.','.$datoLsql['USUARI_ConsInte__b'].','.$Guion.','.$Poblacion.','.$Muestra.','.$datoLsql['USUARI_UsuaCBX___b'].');';
                                            $mysqli->query($Lsql);
                                        }

                                        if ($ColumnaPrincipald == "ConsInte__b") { 
                                            $Lsql = 'UPDATE '.$BaseDatos.'.G'.$Poblacion.'_M'.$Muestra.' LEFT JOIN '.$BaseDatos.'.G'.$Poblacion.' ON G'.$Poblacion.'_M'.$Muestra.'_CoInMiPo__b = G'.$Poblacion.'_ConsInte__b SET G'.$Poblacion.'_M'.$Muestra.'_ConIntUsu_b = '.$datoLsql['USUARI_ConsInte__b'].' WHERE G'.$Poblacion.'_M'.$Muestra.'_CoInMiPo__b = "'.$value[$datosArray[$ColumnaPrincipal]].'";';
                                        }else{
                                            $Lsql = 'UPDATE '.$BaseDatos.'.G'.$Poblacion.'_M'.$Muestra.' LEFT JOIN '.$BaseDatos.'.G'.$Poblacion.' ON G'.$Poblacion.'_M'.$Muestra.'_CoInMiPo__b = G'.$Poblacion.'_ConsInte__b SET G'.$Poblacion.'_M'.$Muestra.'_ConIntUsu_b = '.$datoLsql['USUARI_ConsInte__b'].' WHERE G'.$Poblacion.'_C'.$ColumnaPrincipald.' = "'.$value[$datosArray[$ColumnaPrincipal]].'";';
                                        }
                                        $mysqli->query($Lsql);
                                    }
                                }
                            }
                        }

                        if($estadoLog == 0){
                            $LsqlInsertLog = "INSERT INTO ".$BaseDatos_systema.".LOG_CARGUE (LOG_CARGUE_Fila_b,LOG_CARGUE_Columna_b,LOG_CARGUE_Mensaje_b, LOG_CARGUE_Token_b,LOG_CARGUE_Nombres_b,LOG_CARGUE_Data_b) VALUES (".$index." , '".$columnaCSV."' ,'".$validacion_S."', '".$_SESSION['TOKEN']."'  , '".json_encode($arrayNombres)."' , '".json_encode($arrayData)."' )";                             
                                $mysqli->query($LsqlInsertLog);
                        }

                    }else{//si no existe ese registro
                        if($InsertarRegistro == 1){
                                
                            if( ($_POST['cmbAction'] == '3')){


                                if($estado == 1){
                                                                            
                                    $Lsql="SELECT * FROM ".$BaseDatos_systema.".MONOEF WHERE MONOEF_ConsInte__b = -14";
                                    $columnaCSV = "";             
                                    
                                }else{
                                    
                                    $Lsql="SELECT * FROM ".$BaseDatos_systema.".MONOEF WHERE MONOEF_ConsInte__b = -15";  
                                    $validacion_S.="[Verifique que el telefono sea valido, que  no tenga caracteres especiales,tenga espacios, o este vacio]";  
                                }

                                $res = $mysqli->query($Lsql);
                                $DataDefault = $res->fetch_array();
                                $INSERT .= " , ".$guion."_TipoReintentoUG_b,".$guion."_TipoReintentoGMI_b,".$guion."_ClasificacionUG_b,".$guion."_ClasificacionGMI_b,".$guion."_EstadoUG_b,".$guion."_EstadoGMI_b,".$guion."_UltiGest__b,".$guion."_GesMasImp_b";

                                $VALUES .= " , '".$DataDefault['MONOEF_TipNo_Efe_b']."', '".$DataDefault['MONOEF_TipNo_Efe_b']."', '".$DataDefault['MONOEF_Contacto__b']."', '".$DataDefault['MONOEF_Contacto__b']."', '".$DataDefault['MONOEF_TipiCBX___b']."', '".$DataDefault['MONOEF_TipiCBX___b']."', '".$DataDefault['MONOEF_ConsInte__b']."' , '".$DataDefault['MONOEF_ConsInte__b']."' ";

                                    $INSERT .= " ) ";
                                    $VALUES .= " ) ";                                           


                                $Lsql = $INSERT.$VALUES.";";


                                if($mysqli->query($Lsql) === true){

                                    if($estado == 1){
                                        $nuevos++; 
                                    }else{
                                        $fallaValidacion++;
                                        $estadoLog=0;
                                    }                                             
                                    $ultimoResgistroInsertado = $mysqli->insert_id;
                                    /* preguntamos si toca o no meterlo a la muestra */
                                    if($hayqueinsertarMuestra == true){
                                        /* nos toca meter en la muestra tambien */
                                        $muestraCompleta = $guion."_M".$muestraAlaquetocainsertar;

                                        if($confDinamica == '-1'){
                                            $insertarMuestraLsql = "INSERT INTO  ".$BaseDatos.".".$muestraCompleta." (".$muestraCompleta."_CoInMiPo__b ,  ".$muestraCompleta."_NumeInte__b, ".$muestraCompleta."_Estado____b) VALUES (".$ultimoResgistroInsertado.", 0 , ".$DataDefault['MONOEF_TipNo_Efe_b'].");";
                                            $mysqli->query($insertarMuestraLsql);  
                                        }else{
                                            // si es asignacion definida asigna el usuario en la muestra
                                            if(isset($ColumnaAgente)){
                                                if($ColumnaAgente != 'NONE'){
                                                    //valida que el usuario existe por el correo
                                                    $Lsql = 'SELECT USUARI_ConsInte__b, USUARI_UsuaCBX___b FROM '.$BaseDatos_systema.'.USUARI WHERE USUARI_Correo___b = "'.$value[$datosArray[$ColumnaAgente]].'";';
                                                    $res = $mysqli->query($Lsql);
                                                    if($res->num_rows > 0 && $value[$datosArray[$ColumnaAgente]] != '' && !is_null($value[$datosArray[$ColumnaAgente]])){
                                                        $datoLsql = $res->fetch_array();
                                                        //valida que el usuario este asignado a la campaña
                                                        $Lsql = 'SELECT ASITAR_ConsInte__USUARI_b FROM '.$BaseDatos_systema.'.ASITAR WHERE ASITAR_ConsInte__USUARI_b = '.$datoLsql['USUARI_ConsInte__b'].' AND ASITAR_ConsInte__CAMPAN_b = '.$campana.';';
                                                        $res = $mysqli->query($Lsql);
                                                        if($res->num_rows == 0){
                                                            //el usuario no esta asignado a la campaña, se procede a asignar
                                                            $Lsql = 'INSERT INTO '.$BaseDatos_systema.'.ASITAR (ASITAR_ConsInte__CAMPAN_b,ASITAR_ConsInte__USUARI_b,ASITAR_ConsInte__GUION__Gui_b,ASITAR_ConsInte__GUION__Pob_b,ASITAR_ConsInte__MUESTR_b,ASITAR_UsuarioCBX_b) VALUES ('.$campana.','.$datoLsql['USUARI_ConsInte__b'].','.$Guion.','.$Poblacion.','.$Muestra.','.$datoLsql['USUARI_UsuaCBX___b'].');';
                                                            $mysqli->query($Lsql);
                                                        }
                                                        /* ahora insertamos los datos en la muestra */
                                            
                                                        $insertarMuestraLsql = "INSERT INTO  ".$BaseDatos.".".$muestraCompleta." (".$muestraCompleta."_CoInMiPo__b ,  ".$muestraCompleta."_NumeInte__b, ".$muestraCompleta."_Estado____b , ".$muestraCompleta."_ConIntUsu_b) VALUES (".$ultimoResgistroInsertado.", 0 , ".$DataDefault['MONOEF_TipNo_Efe_b'].", ".$datoLsql['USUARI_ConsInte__b'].");";
                                                        $mysqli->query($insertarMuestraLsql);
                                                    }
                                                }
                                            }else{

                                                $Xlsql = "SELECT ASITAR_ConsInte__USUARI_b, COUNT(".$muestraCompleta."_ConIntUsu_b) AS total FROM ".$BaseDatos_systema.".ASITAR LEFT JOIN ".$BaseDatos.".".$muestraCompleta." ON ASITAR_ConsInte__USUARI_b = ".$muestraCompleta."_ConIntUsu_b WHERE ASITAR_ConsInte__CAMPAN_b = ".$campana." AND (".$muestraCompleta."_Estado____b <> 3 or (".$muestraCompleta."_Estado____b IS NULL)) GROUP BY ASITAR_ConsInte__USUARI_b ORDER BY COUNT(".$muestraCompleta."_ConIntUsu_b) LIMIT 1;";

                                                $res = $mysqli->query($Xlsql);

                                                if($res == TRUE){
                                                    if($res->num_rows > 0){
                                                    $datoLsql = $res->fetch_array();

                                                    /* ahora insertamos los datos en la muestra */
                                                
                                                    $insertarMuestraLsql = "INSERT INTO  ".$BaseDatos.".".$muestraCompleta." (".$muestraCompleta."_CoInMiPo__b ,  ".$muestraCompleta."_NumeInte__b, ".$muestraCompleta."_Estado____b , ".$muestraCompleta."_ConIntUsu_b) VALUES (".$ultimoResgistroInsertado.", 0 , ".$DataDefault['MONOEF_TipNo_Efe_b'].", ".$datoLsql['ASITAR_ConsInte__USUARI_b'].");";

                                                        $mysqli->query($insertarMuestraLsql);
                                                    }else{
                                                        $insertarMuestraLsql = "INSERT INTO  ".$BaseDatos.".".$muestraCompleta." (".$muestraCompleta."_CoInMiPo__b ,  ".$muestraCompleta."_NumeInte__b, ".$muestraCompleta."_Estado____b) VALUES (".$ultimoResgistroInsertado.", 0 , 0)";
                                                        
                                                        $mysqli->query($insertarMuestraLsql);



                                                    }
                                                }
                                                
                                            }
                                        }                                       
                                    }
                                    $exitos++;

                                }else{
                                    $fallaValidacion++;
                                    $estadoLog=0;                                         
                                    $error=str_replace("'", "", $mysqli->error);    
                                    $arrayErrores=buscadorErrores("registrar",$error);
                                    $columnaCSV .=$arrayErrores[0];
                                    $validacion_S .=$arrayErrores[1];
                                }  


                                if($estadoLog == 0){
                                    $LsqlInsertLog = "INSERT INTO ".$BaseDatos_systema.".LOG_CARGUE (LOG_CARGUE_Fila_b,LOG_CARGUE_Columna_b,LOG_CARGUE_Mensaje_b, LOG_CARGUE_Token_b,LOG_CARGUE_Nombres_b,LOG_CARGUE_Data_b) VALUES (".$index." , '".$columnaCSV."' ,'".$validacion_S."', '".$_SESSION['TOKEN']."'  , '".json_encode($arrayNombres)."' , '".json_encode($arrayData)."' )";                            
                                    $mysqli->query($LsqlInsertLog);
                                }

                            }
                        }
                    }                        
                    
                }

            }

        }

        if ($_POST['cheRegistrosCargarNuevo'] == "2") {

            $strOrigen_t = "SELECT PREGUN_ConsInte__b FROM  ".$BaseDatos_systema.".PREGUN 
                            WHERE PREGUN_Texto_____b = 'ORIGEN_DY_WF' AND PREGUN_ConsInte__GUION__b = ".$baseDeDatos;

            $resOrigen_t = $mysqli->query($strOrigen_t);

            $objOrigen_t = $resOrigen_t->fetch_object();

            $intOrigen_t = $objOrigen_t->PREGUN_ConsInte__b; 

            $strUpdate_t = "UPDATE ".$BaseDatos.".".$guion."_M".$muestraAlaquetocainsertar."
                            JOIN ".$BaseDatos.".".$guion." ON ".$guion."_M".$muestraAlaquetocainsertar.".".$guion."_M".$muestraAlaquetocainsertar."_CoInMiPo__b = ".$guion.".".$guion."_ConsInte__b
                            SET ".$guion."_M".$muestraAlaquetocainsertar."_Activo____b = 0 WHERE ".$guion."_C".$intOrigen_t." != '".$origenDY."';";

            $resUpdate_t = $mysqli->query($strUpdate_t);
        }
        
       

        if(isset($_POST['sincronizar']) && $_POST['sincronizar'] != 0){
            $data = array(  
                        "strUsuario_t"          =>  'local',
                        "strToken_t"            =>  'local',
                        "intIdESTPAS_t"         =>  $_POST['pasoId']
                    );                                                             
            $data_string = json_encode($data);   
            //echo $data_string; 

            $ch = curl_init($Api_Gestion.'dyalogocore/api/campanas/voip/sincronizar');
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");                                                                     
            curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string); 
            curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, array(                                                                          
                'Content-Type: application/json',                                                                                
                'Content-Length: ' . strlen($data_string))                                                                      
            ); 
            $respuesta = curl_exec ($ch);
            $error = curl_error($ch);
            curl_close ($ch);
            //echo " Respuesta => ".$respuesta;
            //echo " Error => ".$error;
            if(!empty($respuesta) && !is_null($respuesta)){
                $json = json_decode($respuesta);
                    
            }
        }

        if( isset($_POST['strIdCampan']) ){
            $idCampan=$_POST['strIdCampan'];
        }else{
            $idCampan=0;
        }

        echo json_encode(array(
                        'fechaInsercion'=>$llaveCargue,
                        'idCampan'=>$baseDeDatos,
                        'int_code' => 1, 
                        'str_messaje' => 'Terminado' , 
                        'int_numeroRegistros' => $total,
                        'int_fallas_validacion' => $fallaValidacion,
                        'int_nuevos' => $nuevos, 
                        'int_exitos' => $exitos, 
                        'int_fallas' => $falla , 
                        'int_existentes' => $existe));
                   


    }
?>
