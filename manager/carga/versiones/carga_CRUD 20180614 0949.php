<?php
    session_start();
	require "Excel.php";
	ini_set('display_errors', 'On');
    ini_set('display_errors', 1);
    include(__DIR__."/../pages/conexion.php");

	if(isset($_POST['llenarDatosGs'])){

		$baseDeDatos = $_POST['cmbControl'];
		$LsqlDetalle = "SELECT PREGUN_Texto_____b as titulo_pregunta , PREGUN_Tipo______b as tipo_Pregunta, PREGUN_ConsInte__b as id , PREGUN_ConsInte__GUION__PRE_B as guion, PREGUN_ConsInte__OPCION_B as lista, PREGUN_OrdePreg__b , PREGUN_NumeMini__b as minimoNumero, PREGUN_NumeMaxi__b as maximoNumero, PREGUN_FechMini__b as fechaMinimo, PREGUN_FechMaxi__b as fechaMaximo , PREGUN_HoraMini__b as horaMini , PREGUN_HoraMaxi__b as horaMaximo, PREGUN_TexErrVal_b as error FROM ".$BaseDatos_systema.".PREGUN WHERE PREGUN_ConsInte__GUION__b = ".$baseDeDatos." ORDER BY PREGUN_OrdePreg__b";

        //echo $LsqlDetalle;
		$campos = $mysqli->query($LsqlDetalle);
        echo "<option value=\"NONE\">SELECCIONE</option>";
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
			    }
			    $datasets[] = $dataset;
			}

		$datos  = array();
		for($i = 0; $i < count($datasets[0]); $i++){
			$datos[$i]['valor'] = $i;
			$datos[$i]['Nombres'] = $datasets[0][$i];
		}

		echo json_encode(array('total' => count($datasets[0]) , 'opciones' => $datos));
	}

	if(isset($_GET['llenarDatos'])){
		$name   = $_FILES['arcExcell']['name'];
        $tname  = $_FILES['arcExcell']['tmp_name'];
        ini_set('memory_limit','128M');

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
    
        $sheetData = $obj_excel->getActiveSheet()->toArray(null,true,true,true);
        $arr_datos = array();
        $highestColumm = $obj_excel->setActiveSheetIndex(0)->getHighestColumn(); // e.g. "EL"
		$highestRow = $obj_excel->setActiveSheetIndex(0)->getHighestRow();

		$baseDeDatos 		= $_POST['cmbControl'];
		$ColumnaPrincipal 	= $_POST['cmbColumnaP'];
		$ColumnaPrincipald  = $_POST['cmbColumnaD'];
		$TotalColumnas		= $_POST['totales'];
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
                            $confDinamica = $key->CAMPAN_ConfDinam_b;
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

                $Lsql = "SELECT CAMPAN_ConsInte__b, CAMPAN_ConfDinam_b FROM ".$BaseDatos_systema.".CAMPAN WHERE CAMPAN_ConsInte__MUESTR_b = ".$muestraAlaquetocainsertar." AND CAMPAN_ConsInte__GUION__Pob_b = ".$baseDeDatos;
                $res = $mysqli->query($Lsql);
                $datoLsql = $res->fetch_array();
                $campana = $datoLsql['CAMPAN_ConsInte__b']; 
                $confDinamica =  $datoLsql['CAMPAN_ConfDinam_b']; 
            }
        }

        $total  = 0;
		$nuevos = 0;
		$existe = 0;
		$totalR = 0;
		$exitos = 0;
		$falla  = 0;	
		$fallaValidacion = 0;
        $archivosCSV = array();
        $csvReal = array();


         //Aqui empiezo la Jugada de la carga de EX y eso
        if($muestraAlaquetocainsertar == ''){
            $XsqlSIDAEX = "SELECT SIDAEX_ConsInte__b FROM ".$BaseDatos_systema.".SIDAEX WHERE SIDAEX_Destino___b = ".$baseDeDatos;
        }else{
            $XsqlSIDAEX = "SELECT SIDAEX_ConsInte__b FROM ".$BaseDatos_systema.".SIDAEX WHERE SIDAEX_Muestra___b = ".$muestraAlaquetocainsertar;
        }

        $res_XsqlSIDAEX = $mysqli->query($XsqlSIDAEX);
        //$cur_XsqlSIDAEX = $res_XsqlSIDAEX->fetch_array();

        foreach ($res_XsqlSIDAEX as $key => $cur_XsqlSIDAEX) {
            //Primero Limpio todo eso para no estra preguntando por actualizaciones
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

            $LsqL = "SELECT * FROM ".$BaseDatos_systema.".PREGUN WHERE PREGUN_ConsInte__b = ".$ColumnaPrincipald;
            //echo $LsqL;
            $resultaado = $mysqli->query($LsqL);
            $res_cur = $resultaado->fetch_array();

            $InsertSIEXLL = "INSERT INTO ".$BaseDatos_systema.".SIEXLL (SIEXLL_ConsInte__SIDAEX_b, SIEXLL_LlavOrig__b, SIEXLL_LlavDest__b, SIEXLL__TipoCam__b, SIEXLL__Texto_____b) VALUES(".$ultimoSidaex.", '".$_POST['txtLlaveExcell']."', ".$ColumnaPrincipald.", ".$res_cur['PREGUN_Tipo______b'].", '".$res_cur['PREGUN_Texto_____b']."')";
            $mysqli->query($InsertSIEXLL);


            //Aqui empezamos la jugada de los campos parra insertar u Actualizar        
            for ($i=0; $i < $TotalColumnas; $i++) {
                if($_POST['selDB'.$i] != 'NONE'){
                    $LsqL = "SELECT * FROM ".$BaseDatos_systema.".PREGUN WHERE PREGUN_ConsInte__b = ".$_POST['selDB'.$i];
                    //echo $LsqL;
                    $resultaado = $mysqli->query($LsqL);
                    $res_cur = $resultaado->fetch_array();

                    $InsetarSIEXIN = "INSERT INTO ".$BaseDatos_systema.".SIEXIN ( SIEXIN_ConsInte__SIDAEX_b , SIEXIN_CampOrig__b , SIEXIN_CampDest__b , SIEXIN__TipoCam__b , SIEXIN__Texto_____b, SIEXIN_Validacion_b ) VALUES (".$ultimoSidaex.", '".$_POST['nombreExell'.$i]."',".$_POST['selDB'.$i].", ".$res_cur['PREGUN_Tipo______b'].", '".$res_cur['PREGUN_Texto_____b']."' , ".$_POST['valDB'.$i].");";

                    $mysqli->query($InsetarSIEXIN);    
                }
            }
        }

     
        $x = 0;
		foreach ($sheetData as $index => $value) {
            if ( $index > 1 ){
                if((!is_null($value['A']) OR !empty($value['A'])) && 
                    (!is_null($value['B']) OR !empty($value['B']))
                ){

              
                	$total++;
                	$validador = 1;
                    $archivosCSV = '';
                    $validacion_S = '';
            		//Primero declaramos una variable, para los inserts
            		$INSERT = "INSERT INTO ".$BaseDatos.".".$guion." (".$guion."_FechaInsercion ";
    				$VALUES = " VALUES ('".date('Y-m-d H:i:s')."'";

    				$UPDATE = "UPDATE ".$BaseDatos.".".$guion." SET ";
            		$VALUES_UPDATE = "";
            		$separador = "";

    				//Recorremos el Excel
                	for ($i=0; $i < $TotalColumnas; $i++) {
                		if($i != 0){
                			$separador = " ,";
                		}
                       
                		//preguntamos por el numero de campos en la base de datos en PREGUN
                		$LsqL = "SELECT * FROM ".$BaseDatos_systema.".PREGUN WHERE PREGUN_ConsInte__b = ".$_POST['selDB'.$i];
                        //echo $LsqL;
                		$resultaado = $mysqli->query($LsqL);
                        //echo "Variable => ".$datosArray[$_POST['selExcel'.$i]];
                        //echo $_POST['selExcel'.$i];
                        if($_POST['selExcel'.$i] != 'NONE'){
                            
                            $variable = $value[$datosArray[$_POST['selExcel'.$i]]];                            

                            //$archivosCSV[$i][$datosArray[$_POST['selExcel'.$i]]] = $value[$datosArray[$_POST['selExcel'.$i]]] ;
                            //Recorro la jugada para ver que tipos de campos tengo
                            while ($key = $resultaado->fetch_object()) {
                                if($key->PREGUN_Tipo______b == '6'){ // si es de tipo Lisopc

                                    /*$LsqlLisopC = "SELECT LISOPC_ConsInte__b AS OPCION_ConsInte__b, LISOPC_Nombre____b AS OPCION_Nombre____b FROM LISOPC WHERE LISOPC_ConsInte__OPCION_b = ".$key->PREGUN_ConsInte__OPCION_B;
                                    $LisopResult = $mysqli->query($LsqlLisopC);
                                    while ($Lisop = $LisopResult->fetch_object()) {
                                        if(strtoupper($value[$datosArray($_POST['selExcel'.$i])]) == strtoupper($Lisop->OPCION_Nombre____b)){
                                            $variable = $Lisop->OPCION_ConsInte__b;
                                        }
                                    }*/

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

                                if($key->PREGUN_Tipo______b == '11'){//Ojala que no , pero si es un pinche GUION
                                    $Lsql1 = "SELECT * FROM ".$BaseDatos_systema.".GUION_ WHERE GUION__ConsInte__b = ".$key->PREGUN_ConsInte__GUION__PRE_B;
                                    $res = $mysqli->query($Lsql1);
                                    $ColumnaPrincipalGuion = $res->fetch_array();

                                    $Lsql2 = "SELECT G".$key->PREGUN_ConsInte__GUION__PRE_B."_ConsInte__b as id , ".$ColumnaPrincipalGuion['GUION__ConsInte__PREGUN_Pri_b']." as campoPrincipal FROM ".$BaseDatos.".G".$key->PREGUN_ConsInte__GUION__PRE_B;

                                    //echo $Lsql2;
                                    $guionResult = $mysqli->query($Lsql2);

                                    while ($guionG = $guionResult->fetch_object()) {
                                        if(strtoupper(utf8_encode($guionG->campoPrincipal)) == strtoupper(utf8_encode($variable)) ){
                                            $variable = $guionG->id;
                                        }
                                    }
                                    
                                }

                                if($key->PREGUN_Tipo______b == '5'){
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


                            if($_POST['valDB'.$i] != 1){ // Si es 1 es que no hay que validar
                                switch ($_POST['valDB'.$i]) {
                                    case '2': //validar el telefono sea fijo o Celular
                                        $validacion_S = 'No es un telefono fijo o celular';
                                        $total = count($variable);
                                        if($total == 10){//si el numero telefonico tiene 10 digitos es un celular
                                            $numero_uno = substr($total, 0, 1);
                                            if($numero_uno != '3'){//Si no empieza por 3 No lo dejamos pasar
                                                $validador = 0;

                                            }else{
                                                $validador = 1;
                                            }

                                        }elseif($total == 8){//Si tiene 8 digitos es un fijo
                                            $numero_uno = substr($total, 0, 1);
                                            //si inicia con alguno de estos 0, 3, 9 no es valido
                                            if($numero_uno == '0' || $numero_uno == '3' || $numero_uno == '9'){
                                                $validador = 0;
                                            }else{
                                                $validador = 1;
                                            }
                                        }elseif($total == 7){//si tiene 7 digitos es un fijo
                                            $numero_uno = substr($total, 0, 1);
                                            //si inicia con alguno de estos 0, 1 no es valido
                                            if($numero_uno == '0' || $numero_uno == '1'){
                                                $validador = 0;
                                            }else{
                                                $validador = 1;
                                            }
                                        }else{//si no cumple la condiciones de longitud no es valido
                                            $validador = 0;
                                        }

                                        break;

                                    case '3': // validar que le telefono sea un fijo
                                        $validacion_S = 'No es un telefono fijo';
                                        $total = count($variable);
                                        if($total == 8){//Si tiene 8 digitos es un fijo
                                            $numero_uno = substr($total, 0, 1);
                                            //si inicia con alguno de estos 0, 3, 9 no es valido
                                            if($numero_uno == '0' || $numero_uno == '3' || $numero_uno == '9'){
                                                $validador = 0;
                                            }else{
                                                $validador = 1;
                                            }
                                        }elseif($total == 7){//si tiene 7 digitos es un fijo
                                            $numero_uno = substr($total, 0, 1);
                                            //si inicia con alguno de estos 0, 1 no es valido
                                            if($numero_uno == '0' || $numero_uno == '1'){
                                                $validador = 0;
                                            }else{
                                                $validador = 1;
                                            }
                                        }else{//si no cumple la condiciones de longitud no es valido
                                            $validador = 0;
                                        }
                                        break;

                                    case '4':// validar que el telefono sea un celular
                                        $validacion_S = 'No es un telefono  celular';
                                        $total = strlen($variable);
                                        
                                        if($total == 10){//si el numero telefonico tiene 10 digitos es un celular
                                            $numero_uno = substr($total, 0, 1);
                                            if($numero_uno != '3'){//Si no empieza por 3 No lo dejamos pasar
                                                $validador = 0;
                                            }else{
                                                $validador = 1;
                                            }
                                        }else{//si no cumple la condiciones de longitud no es valido
                                            $validador = 0;
                                        }
                                        break;

                                    case '5'://validar que sea un email, que cumpla con los rquisitos nombre@loquesea.lo
                                        $validacion_S = 'No es un correo valido';
                                        $email = $variable;
                                        if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
                                            $validador = 1;
                                        }else{
                                            $validador = 0;
                                        }
                                        break;

                                    case '6': //validacion avanzada de celular
                                        $validacion_S = 'No es paso la validacion avanzada de numero celular';
                                        //primero validamos que sea un celular
                                        $total = count($variable);
                                        if($total == 10){//si el numero telefonico tiene 10 digitos es un celular
                                            $numero_uno = substr($total, 0, 1);
                                            if($numero_uno != '3'){//Si no empieza por 3 No lo dejamos pasar
                                                $validador = 0;
                                            }else{
                                                //Si es un celular, incoamos el servicio para validarlo por alla
                                                $data = array(  
                                                                "strUsuario_t"        =>  'crm',
                                                                "strToken_t"          =>  'D43dasd321',
                                                                "strTelefono"         =>  $variable
                                                            );                                                                    
                                                $data_string = json_encode($data);   
                                                //echo $data_string; 
                                                $ch = curl_init($Api_Gestion.'dyalogocore/api/bi/validaciones/telefono');
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

                                                    if($json->strEstado_t == "ok"){
                                                        $validador = 1;  
                                                    }else{      
                                                        $validador = 0;
                                                    }
                                                }
                                            }
                                        }else{//si no cumple la condiciones de longitud no es valido
                                            $validador = 0;
                                        }
                                        break;

                                    case '7'://validacion avanzada de correo
                                        $validacion_S = 'No paso la validacion de email';
                                        $email = $variable;
                                        if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
                                            //si cumple con el estandar , llamamos al servicio para comprobar que si es un correo real
                                            //Si es un celular, incoamos el servicio para validarlo por alla
                                            $data = array(  
                                                            "strUsuario_t"                  =>  'crm',
                                                            "strToken_t"                    =>  'D43dasd321',
                                                            "strCorreoElectronico_t"        =>  $variable
                                                        );                                                                    
                                            $data_string = json_encode($data);   
                                            //echo $data_string; 
                                            $ch = curl_init($Api_Gestion.'dyalogocore/api/bi/validaciones/correo');
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

                                                if($json->strEstado_t == "ok"){
                                                    $validador = 1;  
                                                }else{      
                                                    $validador = 0;
                                                }
                                            }
                                        }else{
                                            $validador = 0;
                                        }
                                        break;
                                }
                            }else{
                                $validador = 1;
                            }




                            if($validador == 1){
                                //Termino de armar la consulta a insertar
                                
                                //Si esto viene null, par ano ponerle las comillas y qu eno se explote la consulta
                                if(!is_null($variable) && $variable != '' && !empty($variable) && $variable != 'NULL'){
                                    $INSERT .= " , ".$guion."_C".$_POST['selDB'.$i];
                                    $VALUES .= " , '".$variable."'";
                                    $UPDATE .= $separador." ".$guion."_C".$_POST['selDB'.$i]." = '".$variable."'";
                                }
                            }else{
                                $archivosCSV .= "-".$datosArray[$_POST['selExcel'.$i]];
                                break;
                            }
                        }
         			}	
         			//Cierro la jugada de las consultas
            		$INSERT .= " ) ";
                	$VALUES .= " ) ";
                	//Ejecuto mi consulta
    	            if($validador == 1){
    	            	
    					$Lsql = "SELECT * FROM ".$BaseDatos.".".$guion." WHERE ".$guion."_C".$ColumnaPrincipald." = '".$value[$datosArray[$ColumnaPrincipal]]."';";
                     
    					$res = $mysqli->query($Lsql);

    					if($res->num_rows > 0){// Si ese registro existe
    						if(($_POST['cmbAction'] == '2') || ($_POST['cmbAction'] == '3')){
    							$existe++;
    							$Lsql = $UPDATE." WHERE ".$guion."_C".$ColumnaPrincipald." = '".$value[$datosArray[$ColumnaPrincipal]]."';";
                                
    							if($mysqli->query($Lsql) === true){
    			            		$exitos++;
    			            	}else{
                                    //echo $mysqli->error;
    			            		$falla++;
                                    $LsqlInsertLog = "INSERT INTO ".$BaseDatos_systema.".LOG_CARGUE (LOG_CARGUE_Fila_b, LOG_CARGUE_Mensaje_b, LOG_CARGUE_Token_b) VALUES (".$index.", '".$Lsql." -Error- ".str_replace("'", "", $mysqli->error)."', '".$_SESSION['TOKEN']."')";
                                    //echo $LsqlInsertLog;
                                    $mysqli->query($LsqlInsertLog);
    			            	}	
    						}
    					}else{//si no existe ese registro
    						$nuevos++;
    						if(($_POST['cmbAction'] == '1') || ($_POST['cmbAction'] == '3')){
                                $Lsql = $INSERT.$VALUES;
                                
                               // echo $Lsql;     
    							if($mysqli->query($Lsql) === true){
                                    $ultimoResgistroInsertado = $mysqli->insert_id;
                                    /* preguntamos si toca o no meterlo a la muestra */
                                    if($hayqueinsertarMuestra == true){
                                        /* nos toca meter en la muestra tambien */
                                        /* Wilson te odio por hacerme escribir todo este codigo ah!!*/
                                        $muestraCompleta = $guion."_M".$muestraAlaquetocainsertar;

                                        if($confDinamica == '-1'){
                                            $insertarMuestraLsql = "INSERT INTO  ".$BaseDatos.".".$muestraCompleta." (".$muestraCompleta."_CoInMiPo__b ,  ".$muestraCompleta."_NumeInte__b, ".$muestraCompleta."_Estado____b) VALUES (".$ultimoResgistroInsertado.", 0 , 0);";
                                            $mysqli->query($insertarMuestraLsql);  
                                        }else{
                                            $Xlsql = "SELECT ASITAR_ConsInte__USUARI_b, COUNT(".$muestraCompleta."_ConIntUsu_b) AS total FROM ".$BaseDatos_systema.".ASITAR LEFT JOIN ".$BaseDatos.".".$muestraCompleta." ON ASITAR_ConsInte__USUARI_b = ".$muestraCompleta."_ConIntUsu_b WHERE ASITAR_ConsInte__CAMPAN_b = ".$campana." AND (".$muestraCompleta."_Estado____b <> 3 or (".$muestraCompleta."_Estado____b IS NULL)) GROUP BY ASITAR_ConsInte__USUARI_b ORDER BY COUNT(".$muestraCompleta."_ConIntUsu_b) LIMIT 1;";
                                            $res = $mysqli->query($Xlsql);
                                            $datoLsql = $res->fetch_array();

                                            /* ahora insertamos los datos en la muestra */
                                            
                                            $insertarMuestraLsql = "INSERT INTO  ".$BaseDatos.".".$muestraCompleta." (".$muestraCompleta."_CoInMiPo__b ,  ".$muestraCompleta."_NumeInte__b, ".$muestraCompleta."_Estado____b , ".$muestraCompleta."_ConIntUsu_b) VALUES (".$ultimoResgistroInsertado.", 0 , 0, ".$datoLsql['ASITAR_ConsInte__USUARI_b'].");";
                                            $mysqli->query($insertarMuestraLsql); 
                                        }                                       
                                    }
    			            		$exitos++;
    			            	}else{
                                    //echo $mysqli->error;
    			            		$falla++;
                                    $LsqlInsertLog = "INSERT INTO ".$BaseDatos_systema.".LOG_CARGUE (LOG_CARGUE_Fila_b, LOG_CARGUE_Mensaje_b, LOG_CARGUE_Token_b) VALUES (".$index.", '".str_replace("'", "", $mysqli->error)."', '".$_SESSION['TOKEN']."')";
                                    //echo $LsqlInsertLog;
                                    $mysqli->query($LsqlInsertLog);
    			            	}	
    						}
    						
    					}

    	            	
    	            }else{
            			$fallaValidacion++;
                        $LsqlInsertLog = "INSERT INTO ".$BaseDatos_systema.".LOG_CARGUE (LOG_CARGUE_Fila_b, LOG_CARGUE_Columna_b, LOG_CARGUE_Mensaje_b, LOG_CARGUE_Token_b) VALUES (".$index.",'".$archivosCSV."' ,'No paso la validacion solicitada ".$validacion_S."', '".$_SESSION['TOKEN']."')";
                        $mysqli->query($LsqlInsertLog);
            		}
                }

            }
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
        

        echo json_encode(array(
        					'int_code' => 1, 
        					'str_messaje' => 'Terminado' , 
        					'int_numeroRegistros' => $total,
        					'int_fallas_validacion' => $fallaValidacion,
        					'int_nuevos' => $nuevos, 
        					'int_exitos' => $exitos, 
        					'int_fallas' => $falla , 
        					'int_existentes' => $existe
        				)
    				);
	}
?>
