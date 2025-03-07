<?php

	function generar_Formulario_Script_backoffice($id_a_generar){

		global $mysqli;
		global $BaseDatos;
		global $BaseDatos_systema;
		global $BaseDatos_telefonia;
		global $dyalogo_canales_electronicos;
		global $BaseDatos_general;
		
		if($id_a_generar != 0){
			
			$funciones_js = '';
			$funciones_jsx = '';
			$funciones_jsY = '';
    		$guion = 'G'.$id_a_generar;
    		$alfabeto = array('a', 'b', 'c', 'd', 'e', 'f', 'g', 'h', 'i', 'j', 'k', 'l', 'm', 'n', 'o', 'p', 'q', 'r', 's', 't', 'u', 'v', 'w', 'x', 'y', 'z','aa', 'ab', 'ac', 'ad', 'ae', 'af', 'ag', 'ah', 'ai', 'aj', 'ak', 'al', 'am', 'an', 'ao', 'ap', 'aq', 'ar', 'as', 'at', 'au', 'av', 'aw', 'ax', 'ay', 'az','ba','bb','bc','bd','be','bf','bg','bh','bi','bj','bk','bl','bm','bn','bo','bp','bq','br','bs','bt','bu','bv','bw','bx','by','bz','ca','cb','cc','cd','ce','cf','cg','ch','ci','cj','ck','cl','cm','cn','co','cp','cq','cr','cs','ct','cu','cv','cw','cx','cy','cz','da','db','dc','dd','de','df','dg','dh','di','dj','dk','dl','dm','dn','do','dp','dq','dr','ds','dt','du','dv','dw','dx','dy','dz','ea','eb','ec','ed','ee','ef','eg','eh','ei','ej','ek','el','em','en','eo','ep','eq','er','es','et','eu','ev','ew','ex','ey','ez','fa','fb','fc','fd','fe','ff','fg','fh','fi','fj','fk','fl','fm','fn','fo','fp','fq','fr','fs','ft','fu','fv','fw','fx','fy','fz','ga','gb','gc','gd','ge','gf','gg','gh','gi','gj','gk','gl','gm','gn','go','gp','gq','gr','gs','gt','gu','gv','gw','gx','gy','gz','ha','hb','hc','hd','he','hf','hg','hh','hi','hj','hk','hl','hm','hn','ho','hp','hq','hr','hs','ht','hu','hv','hw','hx','hy','hz','ia','ib','ic','id','ie','if','ig','ih','ii','ij','ik','il','im','in','io','ip','iq','ir','is','it','iu','iv','iw','ix','iy','iz','ja','jb','jc','jd','je','jf','jg','jh','ji','jj','jk','jl','jm','jn','jo','jp','jq','jr','js','jt','ju','jv','jw','jx','jy','jz');

		    $guion_c = $guion."_C";
		    $guionSelect2 ='';
		    $Lsql = "SELECT PREGUN_Texto_____b as titulo_pregunta , PREGUN_Tipo______b as tipo_Pregunta, PREGUN_ConsInte__b as id , PREGUN_ConsInte__GUION__PRE_B as guion, PREGUN_ConsInte__OPCION_B as lista, PREGUN_OrdePreg__b , PREGUN_NumeMini__b as minimoNumero, PREGUN_NumeMaxi__b as maximoNumero, PREGUN_FechMini__b as fechaMinimo, PREGUN_FechMaxi__b as fechaMaximo , PREGUN_HoraMini__b as horaMini , PREGUN_HoraMaxi__b as horaMaximo, PREGUN_TexErrVal_b as error , PREGUN_ConsInte__SECCIO_b, PREGUN_Default___b, PREGUN_ContAcce__b , PREGUN_DefaNume__b, PREGUN_OperEntreCamp_____b FROM ".$BaseDatos_systema.".PREGUN JOIN ".$BaseDatos_systema.".SECCIO ON SECCIO_ConsInte__b = PREGUN_ConsInte__SECCIO_b WHERE PREGUN_ConsInte__GUION__b = ".$id_a_generar." AND PREGUN_Tipo______b != 12 AND SECCIO_TipoSecc__b != 2  ORDER BY PREGUN_ConsInte__SECCIO_b ASC, PREGUN_OrdePreg__b ASC";

    		$Lsql2 = "SELECT PREGUN_Texto_____b as titulo_pregunta , PREGUN_Tipo______b as tipo_Pregunta, PREGUN_ConsInte__b as id , PREGUN_ConsInte__GUION__PRE_B as guion, PREGUN_ConsInte__OPCION_B as lista, PREGUN_OrdePreg__b, PREGUN_ConsInte__SECCIO_b, PREGUN_Default___b, PREGUN_ContAcce__b, PREGUN_DefaNume__b , PREGUN_OperEntreCamp_____b FROM ".$BaseDatos_systema.".PREGUN JOIN ".$BaseDatos_systema.".SECCIO ON SECCIO_ConsInte__b = PREGUN_ConsInte__SECCIO_b WHERE PREGUN_ConsInte__GUION__b = ".$id_a_generar." AND PREGUN_Tipo______b != 12  AND SECCIO_TipoSecc__b != 2 ORDER BY PREGUN_ConsInte__SECCIO_b ASC, PREGUN_OrdePreg__b ASC";

    		$LsqlCamposPrimairos = "SELECT GUION__ConsInte__PREGUN_Pri_b,  GUION__ConsInte__PREGUN_Sec_b, GUION__ConsInte__PREGUN_Tip_b, GUION__ConsInte__PREGUN_Rep_b, GUION__ConsInte__PREGUN_Fag_b, GUION__ConsInte__PREGUN_Hag_b, GUION__ConsInte__PREGUN_Com_b FROM ".$BaseDatos_systema.".GUION_ WHERE GUION__ConsInte__b = ".$id_a_generar ." AND GUION__Tipo______b = 4";



		    $GUION__ConsInte__PREGUN_Pri_b = null;
		    $GUION__ConsInte__PREGUN_Sec_b = null;
		    $GUION__ConsInte__PREGUN_Tip_b = null;
		    $GUION__ConsInte__PREGUN_Rep_b = null;
		    $GUION__ConsInte__PREGUN_Fag_b = null;
		    $GUION__ConsInte__PREGUN_Hag_b = null;
		    $GUION__ConsInte__PREGUN_Com_b = null;

		    //echo $LsqlCamposPrimairos;
		    $camposBuscadosIzquierda = $mysqli->query($LsqlCamposPrimairos);
		    while($key = $camposBuscadosIzquierda->fetch_object()){
				$GUION__ConsInte__PREGUN_Tip_b = $key->GUION__ConsInte__PREGUN_Tip_b;
				$GUION__ConsInte__PREGUN_Rep_b = $key->GUION__ConsInte__PREGUN_Rep_b;
				$GUION__ConsInte__PREGUN_Fag_b = $key->GUION__ConsInte__PREGUN_Fag_b;
				$GUION__ConsInte__PREGUN_Hag_b = $key->GUION__ConsInte__PREGUN_Hag_b;
				$GUION__ConsInte__PREGUN_Com_b = $key->GUION__ConsInte__PREGUN_Com_b;
		    }

    		$LsqlCamposPrimairos = "SELECT GUION__ConsInte__PREGUN_Pri_b,  GUION__ConsInte__PREGUN_Sec_b FROM ".$BaseDatos_systema.".GUION_ WHERE GUION__ConsInte__b =".$id_a_generar;

   			//echo $LsqlCamposPrimairos." Estos ERAN ";

			$camposBuscadosIzquierda = $mysqli->query($LsqlCamposPrimairos);
		    while($key = $camposBuscadosIzquierda->fetch_object()){
		    	$GUION__ConsInte__PREGUN_Pri_b = $key->GUION__ConsInte__PREGUN_Pri_b;
		    	$GUION__ConsInte__PREGUN_Sec_b = $key->GUION__ConsInte__PREGUN_Sec_b;
		    }

		    /*echo " SON ESTOS PRIMARIO => ".$GUION__ConsInte__PREGUN_Pri_b."\n".
		    "SON ESTOS GUION__ConsInte__PREGUN_Sec_b => ".$GUION__ConsInte__PREGUN_Sec_b."\n".
		    "SON ESTOS GUION__ConsInte__PREGUN_Tip_b => ".$GUION__ConsInte__PREGUN_Tip_b."\n".
		    "SON ESTOS GUION__ConsInte__PREGUN_Rep_b => ".$GUION__ConsInte__PREGUN_Rep_b."\n".
		    "SON ESTOS GUION__ConsInte__PREGUN_Fag_b => ".$GUION__ConsInte__PREGUN_Fag_b."\n".
		    "SON ESTOS GUION__ConsInte__PREGUN_Hag_b => ".$GUION__ConsInte__PREGUN_Hag_b."\n".
		    "SON ESTOS GUION__ConsInte__PREGUN_Com_b => ".$GUION__ConsInte__PREGUN_Com_b."\n";*/
			
			if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
			    $carpeta = "C:/xampp/htdocs/crm_php/formularios/".$guion;
			} else {
			    $carpeta = "/var/www/html/crm_php/formularios/".$guion;
			}
			
		    if (!file_exists($carpeta)) {
		        mkdir($carpeta, 0777);
		    }

		    /* abrimos el archivo que vamos a crear */
		    $fp = fopen($carpeta."/".$guion.".php" , "w");
		    //chmod($carpeta."/".$guion.".php" , 0777);

		    //echo "Va a ser esto  Linea 2331 => ".$Lsql2;
		    $campos_6 = $mysqli->query($Lsql2);
		    $camposTabla = '';
		    $ordenTabla = '';
		    $campTabla = '';
		    $joinsTabla = '';
			$j = 0;

			while($key = $campos_6->fetch_object()){
		        if($key->id == $GUION__ConsInte__PREGUN_Pri_b){
		            if($key->tipo_Pregunta == '6'){
		                if($j == 0){
		                    $camposTabla .= $alfabeto[$j].'.LISOPC_Nombre____b as camp1';
		                    $ordenTabla .= $guion_c.$key->id;
		                }else{
		                    $camposTabla .= ' , '.$alfabeto[$j].'.LISOPC_Nombre____b as camp1';
		                    $campTabla .=    $guion_c.$key->id;
		                }
		                $joinsTabla .= ' LEFT JOIN ".$BaseDatos_systema.".LISOPC as '.$alfabeto[$j].' ON '.$alfabeto[$j].'.LISOPC_ConsInte__b = '. $guion_c.$key->id;
		            }else if($key->tipo_Pregunta == '11'){
		                $LsqlCamposPrimairos_2 = "SELECT GUION__ConsInte__PREGUN_Pri_b FROM ".$BaseDatos_systema.".GUION_ WHERE GUION__ConsInte__b =".$key->guion." ";
		                $campoPrimario = '';
		                $camposBuscadosIzquierda_2 = $mysqli->query($LsqlCamposPrimairos_2);

		                while($key2 = $camposBuscadosIzquierda_2->fetch_object()){
		                    $campoPrimario = $key2->GUION__ConsInte__PREGUN_Pri_b;
		                }

		                if($j == 0){
		                    $camposTabla .='G'.$key->guion.'_C'.$campoPrimario.' as camp1';
		                    $ordenTabla .= $guion_c.$key->id;
		                }else{
		                    $camposTabla .= 'G'.$key->guion.'_C'.$campoPrimario.' as camp1';
		                    $campTabla .=   $guion_c.$key->id;
		                }
		                $joinsTabla .= ' LEFT JOIN ".$BaseDatos.".G'.$key->guion.' ON G'.$key->guion.'_ConsInte__b = '.$guion_c.$key->id;
		            }else{
		                if($j == 0){
		                    $camposTabla .= $guion_c.$key->id.' as camp1';
		                    $ordenTabla = $guion_c.$key->id;
		                }else{
		                    $camposTabla .= ' , '.$guion_c.$key->id.' as camp1';
		                    $campTabla .=    $guion_c.$key->id;
		                }
		            }
		            $j++;
		        }

		        if($key->id == $GUION__ConsInte__PREGUN_Sec_b){
		            if($key->tipo_Pregunta == '6'){
		                if($j == 0){
		                    $camposTabla .= $alfabeto[$j].'.LISOPC_Nombre____b as camp2';
		                    $ordenTabla .= $guion_c.$key->id;
		                }else{
		                    $camposTabla .= ' , '.$alfabeto[$j].'.LISOPC_Nombre____b as camp2';
		                    $campTabla .=    $guion_c.$key->id;
		                }
		                $joinsTabla .= ' LEFT JOIN ".$BaseDatos_systema.".LISOPC as '.$alfabeto[$j].' ON '.$alfabeto[$j].'.LISOPC_ConsInte__b = '. $guion_c.$key->id;

		            }else if($key->tipo_Pregunta == '11'){
		                $LsqlCamposPrimairos_2 = "SELECT GUION__ConsInte__PREGUN_Pri_b FROM ".$BaseDatos_systema.".GUION_ WHERE GUION__ConsInte__b =".$key->guion." ";
		                $campoPrimario = '';
		                $camposBuscadosIzquierda_2 = $mysqli->query($LsqlCamposPrimairos_2);

		                while($key2 = $camposBuscadosIzquierda_2->fetch_object()){
		                    $campoPrimario = $key2->GUION__ConsInte__PREGUN_Pri_b;
		                }

		                if($j == 0){
		                    $camposTabla .='G'.$key->guion.'_C'.$campoPrimario.' as camp1';
		                    $ordenTabla .= $guion_c.$key->id;
		                }else{
		                    $camposTabla .= 'G'.$key->guion.'_C'.$campoPrimario.' as camp1';
		                    $campTabla .=   $guion_c.$key->id;
		                }
		                $joinsTabla .= ' LEFT JOIN ".$BaseDatos.".G'.$key->guion.' ON G'.$key->guion.'_ConsInte__b = '.$guion_c.$key->id;

		            
		            }else{
		                if($j == 0){
		                    $camposTabla .= $guion_c.$key->id.' as camp2';
		                    $ordenTabla .= $guion_c.$key->id;
		                }else{
		                    $camposTabla .= ' , '.$guion_c.$key->id.' as camp2';
		                    $campTabla =    $guion_c.$key->id;
		                }
		            }
		            $j++;
		        }
		    }

		    $camposValidaciones = '';
		    $valoresValidados = $mysqli->query($Lsql);
		    $fechaValidadaOno = '';
		    $horaValidadaOno = '';
		    $botonSalvar = '';
		    $hayqueValidar = 0;
		    $select2 = '';
		    $funcionesCampoGuion = '';
		    $numeroFuncion = '';
		    $decimalFuncion = '';

		    $primerCamposJoin ='0';
		    $joins = '';

		    while ($key = $valoresValidados->fetch_object()) {
		        if($key->tipo_Pregunta == '3' ){
		        $numeroFuncion .= '
    	$("#'.$guion_c.$key->id.'").numeric();
		        ';    
		        }

		        if($key->tipo_Pregunta == '4' ){
		        $decimalFuncion .= '
        $("#'.$guion_c.$key->id.'").numeric({ decimal : ".",  negative : false, scale: 4 });
		        ';
		        }

		        if($key->tipo_Pregunta == '3' || $key->tipo_Pregunta == '4'){
		            if(!is_null($key->minimoNumero) && !is_null($key->maximoNumero) ){
		                $camposValidaciones .= "\n".'
            if($("#'.$guion_c.$key->id.'").val().length > 0){
                if( $("#'.$guion_c.$key->id.'").val() > '.($key->minimoNumero - 1).' && $("#'.$guion_c.$key->id.'").val() < '.($key->maximoNumero + 1).'){

                }else{
                    alertify.error(\''.$key->error.'\');
                    $("#'.$guion_c.$key->id.'").focus();
                    valido =1;
                }    
            }';
		            }else if(!is_null($key->minimoNumero) && is_null($key->maximoNumero)){
		                $camposValidaciones .= "\n".'
            if($("#'.$guion_c.$key->id.'").val().length > 0){
                if( $("#'.$guion_c.$key->id.'").val() > '.($key->minimoNumero - 1).'){
                    
                }else{
                    alertify.error(\''.$key->error.'\');
                    $("#'.$guion_c.$key->id.'").focus();
                    valido =1;
                }    
            }';
		            }else if(is_null($key->minimoNumero) && !is_null($key->maximoNumero)){
		                $camposValidaciones .= "\n".'
            if($("#'.$guion_c.$key->id.'").val().length > 0){
                if(  $("#'.$guion_c.$key->id.'").val() < '.($key->maximoNumero + 1).'){
                    
                }else{
                    alertify.error(\''.$key->error.'\');
                    $("#'.$guion_c.$key->id.'").focus();
                    valido =1;
                }    
            }';
		            }
		        }



		        if($key->tipo_Pregunta == '5'){
		            if(!is_null($key->fechaMinimo) && !is_null($key->fechaMaximo) ){
		                $fechaValidadaOno .= "\n".'
        var startDate = new Date(\''.$key->fechaMinimo.'\');
        var FromEndDate = new Date(\''.$key->fechaMaximo.'\');
        $("#'.$guion_c.$key->id.'").datepicker({
            language: "es",
            autoclose: true,
            startDate: startDate,
            endDate : FromEndDate,
            todayHighlight: true
        });';
		              }else if(!is_null($key->fechaMinimo) && is_null($key->fechaMaximo)){
		                $fechaValidadaOno .= "\n".'
        var startDate = new Date(\''.$key->fechaMinimo.'\');
        $("#'.$guion_c.$key->id.'").datepicker({
            language: "es",
            autoclose: true,
            startDate: startDate,
            todayHighlight: true
        });';
		            }else if(is_null($key->fechaMinimo) && !is_null($key->fechaMaximo)){
		                $fechaValidadaOno .= "\n".'
        var FromEndDate = new Date(\''.$key->fechaMaximo.'\');
        $("#'.$guion_c.$key->id.'").datepicker({
            language: "es",
            autoclose: true,
            endDate : FromEndDate,
            todayHighlight: true
        });';
		            }else{
		                $fechaValidadaOno .= "\n".'
        $("#'.$guion_c.$key->id.'").datepicker({
            language: "es",
            autoclose: true,
            todayHighlight: true
        });';
		            }
		        }

		        if($key->tipo_Pregunta == '10'){
		            if(!is_null($key->horaMini) && !is_null($key->horaMaximo)){
		                $horaValidadaOno .= "\n".'
        //Timepicker
        var options = {  //hh:mm 24 hour format only, defaults to current time
            twentyFour: true, //Display 24 hour format, defaults to false
            title: \''.$key->titulo_pregunta.'\', //The Wickedpicker\'s title,
            showSeconds: true, //Whether or not to show seconds,
            secondsInterval: 1, //Change interval for seconds, defaults to 1
            minutesInterval: 1, //Change interval for minutes, defaults to 1
            beforeShow: null, //A function to be called before the Wickedpicker is shown
            show: null, //A function to be called when the Wickedpicker is shown
            clearable: false, //Make the picker\'s input clearable (has clickable "x")
        }; 
        $("#'.$guion_c.$key->id.'").wickedpicker(options);';
		            }else if(!is_null($key->horaMini) && is_null($key->horaMaximo)){
		                $horaValidadaOno .= "\n".'
        //Timepicker
        var options = {  //hh:mm 24 hour format only, defaults to current time
            twentyFour: true, //Display 24 hour format, defaults to false
            title: \''.$key->titulo_pregunta.'\', //The Wickedpicker\'s title,
            showSeconds: true, //Whether or not to show seconds,
            secondsInterval: 1, //Change interval for seconds, defaults to 1
            minutesInterval: 1, //Change interval for minutes, defaults to 1
            beforeShow: null, //A function to be called before the Wickedpicker is shown
            show: null, //A function to be called when the Wickedpicker is shown
            clearable: false, //Make the picker\'s input clearable (has clickable "x")
        }; 
        $("#'.$guion_c.$key->id.'").wickedpicker(options);';
		            }else if(is_null($key->horaMini) && !is_null($key->horaMaximo)){
		                $horaValidadaOno .= "\n".'
        //Timepicker
        var options = { //hh:mm 24 hour format only, defaults to current time
            twentyFour: true, //Display 24 hour format, defaults to false
            title: \''.$key->titulo_pregunta.'\', //The Wickedpicker\'s title,
            showSeconds: true, //Whether or not to show seconds,
            secondsInterval: 1, //Change interval for seconds, defaults to 1
            minutesInterval: 1, //Change interval for minutes, defaults to 1
            beforeShow: null, //A function to be called before the Wickedpicker is shown
            show: null, //A function to be called when the Wickedpicker is shown
            clearable: false, //Make the picker\'s input clearable (has clickable "x")
        }; 
        $("#'.$guion_c.$key->id.'").wickedpicker(options);';
		            }else{
		                $horaValidadaOno .= "\n".'
        //Timepicker
        var options = { //hh:mm 24 hour format only, defaults to current time
            twentyFour: true, //Display 24 hour format, defaults to false
            title: \''.$key->titulo_pregunta.'\', //The Wickedpicker\'s title,
            showSeconds: true, //Whether or not to show seconds,
            secondsInterval: 1, //Change interval for seconds, defaults to 1
            minutesInterval: 1, //Change interval for minutes, defaults to 1
            beforeShow: null, //A function to be called before the Wickedpicker is shown
            show: null, //A function to be called when the Wickedpicker is shown
            clearable: false, //Make the picker\'s input clearable (has clickable "x")
        }; 
        $("#'.$guion_c.$key->id.'").wickedpicker(options);';
		            }
		        }

		        if(!is_null($key->minimoNumero) || !is_null($key->maximoNumero)){
		            $hayqueValidar += 1;
		        }
		    }

		    /* preguntamos si toca validar los campos obligatorios o no */
		    
		    if($hayqueValidar > 0){
		        $botonSalvar = "\n".'
        $("#Save").click(function(){
        	
        	var d = new Date();
            var h = d.getHours();
            var horas = (h < 10) ? \'0\' + h : h;
            var dia = d.getDate();
            var dias = (dia < 10) ? \'0\' + dia : dia;
            var fechaFinal = d.getFullYear() + \'-\' + meses[d.getMonth()] + \'-\' + dias + \' \'+ horas +\':\'+d.getMinutes()+\':\'+d.getSeconds();
            $("#FechaFinal").val(fechaFinal);

            var bol_respuesta = before_save();
            var valido = 0;
            '.$camposValidaciones.'

            if($(".tipificacion").val() == \'0\'){
            	alertify.error("Es necesaria la tipificación!");
            	valido = 1;
            }

            if($(".reintento").val() == \'2\'){
            	if($(".TxtFechaReintento").val().length < 1){
            		alertify.error("Es necesario llenar la fecha de reintento!");
            		$(".TxtFechaReintento").focus();
            		valido = 1;
            	}

            	if($(".TxtHoraReintento").val().length < 1){
            		alertify.error("Es necesario llenar la hora de reintento!");
            		$(".TxtHoraReintento").focus();
            		valido = 1;
            	}
            }

            if(valido == \'0\'){
            	if(bol_respuesta){
	                var form = $("#FormularioDatos");
	                //Se crean un array con los datos a enviar, apartir del formulario 
	                var formData = new FormData($("#FormularioDatos")[0]);

	                if($("#'.$guion_c.$GUION__ConsInte__PREGUN_Tip_b.' option:selected").text() == \'Devuelta\'){
	                	formData.append("tareaDevuelta","SI");
	                }
	                $.ajax({
	                    url: \'<?=$url_crud;?>?insertarDatosGrilla=si&usuario=<?php echo getIdentificacionUser($token);?>&CodigoMiembro=<?php if(isset($_GET[\'user\'])) { echo $_GET["user"]; }else{ echo "0";  } ?><?php if(isset($_GET[\'id_gestion_cbx\'])){ echo "&id_gestion_cbx=".$_GET[\'id_gestion_cbx\']; }?><?php if(!empty($token)){ echo "&token=".$token; }?>&campana_crm=<?php if(isset($_GET[\'campana_crm\'])){ echo $_GET[\'campana_crm\']; } else{ echo "0"; }?>\',  
	                    type: \'POST\',
	                    data: formData,
	                    cache: false,
	                    contentType: false,
	                    processData: false,
	                    //una vez finalizado correctamente
	                    success: function(data){
	                        if(data != \'0\'){
	                            $("#Save").attr(\'disabled\', false);
			                    alertify.success(\'Información guardada con exito\');           
	                        }else{
	                            //Algo paso, hay un error
	                            $("#Save").attr(\'disabled\', false);
	                            alertify.error(\'Un error ha ocurrido y no pudimos guardar la información\');
	                        }
	                                           
	                                            
	                    },
	                    //si ha ocurrido un error
	                    error: function(){
	                        after_save_error();
	                        $("#Save").attr(\'disabled\', false);
	                        alertify.error(\'Ocurrio un error relacionado con la red, al momento de guardar, intenta mas tarde\');
	                    }
	                });
	            }
            }                 
        });
    });';
		    }else{
		        $botonSalvar = "\n".'
        $("#Save").click(function(){
        	
        	var bol_respuesta = before_save();
        	var d = new Date();
            var h = d.getHours();
            var horas = (h < 10) ? \'0\' + h : h;
            var dia = d.getDate();
            var dias = (dia < 10) ? \'0\' + dia : dia;
            var fechaFinal = d.getFullYear() + \'-\' + meses[d.getMonth()] + \'-\' + dias + \' \'+ horas +\':\'+d.getMinutes()+\':\'+d.getSeconds();
            $("#FechaFinal").val(fechaFinal);
            var valido = 0;
            
            if($(".tipificacion").val() == \'0\'){
            	alertify.error("Es necesaria la tipificación!");
            	valido = 1;
            }

            if($(".reintento").val() == \'2\'){
            	if($(".TxtFechaReintento").val().length < 1){
            		alertify.error("Es necesario llenar la fecha de reintento!");
            		$(".TxtFechaReintento").focus();
            		valido = 1;
            	}

            	if($(".TxtHoraReintento").val().length < 1){
            		alertify.error("Es necesario llenar la hora de reintento!");
            		$(".TxtHoraReintento").focus();
            		valido = 1;
            	}
            }

            if(valido == \'0\'){
	        	if(bol_respuesta){            
		            var form = $("#FormularioDatos");
		            //Se crean un array con los datos a enviar, apartir del formulario 
		            var formData = new FormData($("#FormularioDatos")[0]);
		            if($("#'.$guion_c.$GUION__ConsInte__PREGUN_Tip_b.' option:selected").text() == \'Devuelta\'){
	                	formData.append("tareaDevuelta","SI");
	                }
		            $.ajax({
		               url: \'<?=$url_crud;?>?insertarDatosGrilla=si&usuario=<?php echo getIdentificacionUser($token);?>&CodigoMiembro=<?php if(isset($_GET[\'user\'])) { echo $_GET["user"]; }else{ echo "0";  } ?><?php if(isset($_GET[\'id_gestion_cbx\'])){ echo "&id_gestion_cbx=".$_GET[\'id_gestion_cbx\']; }?><?php if(!empty($token)){ echo "&token=".$token; }?>&campana_crm=<?php if(isset($_GET[\'campana_crm\'])){ echo $_GET[\'campana_crm\']; } else{ echo "0"; } ?>\',  
		                type: \'POST\',
		                data: formData,
		                cache: false,
		                contentType: false,
		                processData: false,
		                //una vez finalizado correctamente
		                success: function(data){
		                    if(data != \'0\'){
		                    	$("#Save").attr(\'disabled\', false);
			                    alertify.success(\'Información guardada con exito\');   
		                    }else{
		                        //Algo paso, hay un error
		                        $("#Save").attr(\'disabled\', false);
		                        alertify.error(\'Un error ha ocurrido y no pudimos guardar la información\');
		                    }                
		                },
		                //si ha ocurrido un error
		                error: function(){
		                    after_save_error();
		                    $("#Save").attr(\'disabled\', false);
		                    alertify.error(\'Ocurrio un error relacionado con la red, al momento de guardar, intenta mas tarde\');
		                }
		            });
          		}
          	}
        });
    });';
		    }

		    $botonCerrarErronea = '';

		    if(!empty($CAMPAN_TipificacionErrada_b)){
    			$botonCerrarErronea .= '
		$("#errorGestion").click(function(event){
			event.preventDefault();
			$("#'.$guion_c.$GUION__ConsInte__PREGUN_Tip_b.'").val('.$CAMPAN_TipificacionErrada_b.').change();
			var meses = new Array(12);
	    	meses[0] = "01";
	    	meses[1] = "02";
	    	meses[2] = "03";
	    	meses[3] = "04";
	    	meses[4] = "05";
	    	meses[5] = "06";
	    	meses[6] = "07";
	    	meses[7] = "08";
	    	meses[8] = "09";
	    	meses[9] = "10";
	    	meses[10] = "11";
	    	meses[11] = "12";
	    	var d = new Date();
	        var h = d.getHours();
	        var horas = (h < 10) ? \'0\' + h : h;
	        var dia = d.getDate();
	        var dias = (dia < 10) ? \'0\' + dia : dia;
	        var fechaFinal = d.getFullYear() + \'-\' + meses[d.getMonth()] + \'-\' + dias + \' \'+ horas +\':\'+d.getMinutes()+\':\'+d.getSeconds();
	        $("#FechaFinal").val(fechaFinal);
	        
	        var form = $("#FormularioDatos");
	        //Se crean un array con los datos a enviar, apartir del formulario 
	        var formData = new FormData($("#FormularioDatos")[0]);
	        $.ajax({
	           url: \'<?=$url_crud;?>?insertarDatosGrilla=si&usuario=<?php echo getIdentificacionUser($token);?>&CodigoMiembro=<?php if(isset($_GET[\'user\'])) { echo $_GET["user"]; }else{ echo "0";  } ?><?php if(isset($_GET[\'id_gestion_cbx\'])){ echo "&id_gestion_cbx=".$_GET[\'id_gestion_cbx\']; }?><?php if(!empty($token)){ echo "&token=".$token; }?>\',  
	            type: \'POST\',
	            data: formData,
	            cache: false,
	            contentType: false,
	            processData: false,
	            //una vez finalizado correctamente
	            success: function(data){
	            	<?php 
	            	if(!isset($_GET[\'formulario\'])){
	                ?>

	            	$.ajax({
	            		url   : \'formularios/generados/PHP_Ejecutar.php?action=EDIT&tiempo=<?php echo $tiempoDesdeInicio;?>&usuario=<?php echo getIdentificacionUser($token);?>&CodigoMiembro=<?php if(isset($_GET[\'user\'])) { echo $_GET["user"]; }else{ echo "0";  } ?>&ConsInteRegresado=\'+data +\'<?php if(isset($_GET[\'token\'])) { echo "&token=".$_GET[\'token\']; }?><?php if(isset($_GET[\'id_gestion_cbx\'])) { echo "&id_gestion_cbx=".$_GET[\'id_gestion_cbx\']; }?>&campana_crm=<?php if(isset($_GET[\'campana_crm\'])){ echo $_GET[\'campana_crm\']; } else{ echo "0"; } ?><?php if(isset($_GET[\'predictiva\'])) { echo "&predictiva=".$_GET[\'predictiva\'];}?><?php if(isset($_GET[\'consinte\'])) { echo "&consinte=".$_GET[\'consinte\']; }?>\',
	            		type  : "post",
	            		data  : formData,
	        		 	cache: false,
	                    contentType: false,
	                    processData: false,
	            		success : function(xt){
	            			console.log(xt);
	            			window.location.href = "http://<?php echo $_SERVER["HTTP_HOST"];?>/crm_php/Estacion_contact_center.php?busqueda_manual_forzada=true<?php if(isset($_GET[\'campana_crm\'])){ echo "&id_campana_crm=".$_GET[\'campana_crm\']; }?>";
	            		}
	            	});
	            	
			
	            	<?php } ?>    
	            	
	                          
	            },
	            //si ha ocurrido un error
	            error: function(){
	                after_save_error();
	                alertify.error(\'Ocurrio un error relacionado con la red, al momento de guardar, intenta mas tarde\');
	            }
	        });
	    });';
    		}



      		fputs($fp , '
<?php date_default_timezone_set(\'America/Bogota\'); ?>
<div class="modal fade-in" id="editarDatos" data-backdrop="static" data-keyboard="false" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" id="refrescarGrillas">&times;</button>
                <h4 class="modal-title">Edición</h4>
            </div>
            <div class="modal-body">
                <iframe id="frameContenedor" src="" style="width: 100%; height: 900px;"  marginheight="0" marginwidth="0" noresize  frameborder="0">
                  
                </iframe>
            </div>
        </div>
    </div>
</div>');
      		//echo "Son estos los campos a bucar => ".$camposTabla;

      		$PEOBUS = '
	$PEOBUS_Escritur__b = 1 ;
    $PEOBUS_Adiciona__b = 1 ;
    $PEOBUS_Borrar____b = 1 ;

	if(!isset($_GET[\'view\'])){
        $idUsuario = getIdentificacionUser($token);
        $peobus = "SELECT * FROM ".$BaseDatos_systema.".PEOBUS WHERE PEOBUS_ConsInte__USUARI_b = ".$idUsuario." AND PEOBUS_ConsInte__GUION__b = ".$_GET[\'formulario\'];
        $query = $mysqli->query($peobus);
        $PEOBUS_VeRegPro__b = 0 ;
        
        while ($key =  $query->fetch_object()) {
            $PEOBUS_VeRegPro__b = $key->PEOBUS_VeRegPro__b ;
            $PEOBUS_Escritur__b = $key->PEOBUS_Escritur__b ;
            $PEOBUS_Adiciona__b = $key->PEOBUS_Adiciona__b ;
            $PEOBUS_Borrar____b = $key->PEOBUS_Borrar____b ;
        }

        if($PEOBUS_VeRegPro__b != 0){
            $Zsql = "SELECT '.$guion.'_ConsInte__b as id, '.$camposTabla.', LISOPC_Nombre____b as estado FROM ".$BaseDatos.".'.$guion.' JOIN ".$BaseDatos_systema.".LISOPC ON LISOPC_ConsInte__b = '.$guion_c.$GUION__ConsInte__PREGUN_Rep_b.' '.$joinsTabla.' WHERE '.$guion.'_Usuario = ".$idUsuario." ORDER BY '.$guion.'_ConsInte__b DESC LIMIT 0, 50";
        }else{
            $Zsql = "SELECT '.$guion.'_ConsInte__b as id, '.$camposTabla.', LISOPC_Nombre____b as estado FROM ".$BaseDatos.".'.$guion.' JOIN ".$BaseDatos_systema.".LISOPC ON LISOPC_ConsInte__b = '.$guion_c.$GUION__ConsInte__PREGUN_Rep_b.' '.$joinsTabla.' ORDER BY '.$guion.'_ConsInte__b DESC LIMIT 0, 50";
        }
    }else{
        $Zsql = "SELECT '.$guion.'_ConsInte__b as id, '.$camposTabla.',  LISOPC_Nombre____b as estado FROM ".$BaseDatos.".'.$guion.' JOIN ".$BaseDatos_systema.".LISOPC ON LISOPC_ConsInte__b = '.$guion_c.$GUION__ConsInte__PREGUN_Rep_b.' '.$joinsTabla.' ORDER BY '.$guion.'_ConsInte__b DESC LIMIT 0, 50";
    }
';
      
			fputs($fp , chr(13).chr(10)); // Genera saldo de linea
			fputs($fp , '<?php');
			fputs($fp , chr(13).chr(10)); // Genera saldo de linea    
			fputs($fp , '   //SECCION : Definicion urls');
			fputs($fp , chr(13).chr(10)); // Genera saldo de linea   
			fputs($fp , '   $url_crud = "formularios/'.$guion.'/'.$guion.'_CRUD.php";');
			fputs($fp , chr(13).chr(10)); // Genera saldo de linea  
			fputs($fp , '   //SECCION : CARGUE DATOS LISTA DE NAVEGACIÓN');
			fputs($fp , chr(13).chr(10)); // Genera saldo de linea  
			fputs($fp , $PEOBUS );
			fputs($fp , chr(13).chr(10)); // Genera saldo de linea  
			fputs($fp , '   $result = $mysqli->query($Zsql);');
			fputs($fp , chr(13).chr(10)); // Genera saldo de linea  

			fputs($fp , chr(13).chr(10)); // Genera saldo de linea   
			fputs($fp , '?>');
			fputs($fp , chr(13).chr(10)); // Genera saldo de linea 
			//Esto es ara agregar los tabs y la tabla
			fputs($fp , '<?php include(__DIR__ ."/../cabecera.php");?>');
			fputs($fp , chr(13).chr(10)); // Genera saldo de linea 

			$camposconsultaGuion = '';
			$camposAmostrar = '';
			$valordelArray = 0;
			$nombresDeCampos = '';
			$select2_hojadeDatos = '';
			$JavascriptTipificaciones = '';

			if($GUION__ConsInte__PREGUN_Tip_b != NULL && !is_null($GUION__ConsInte__PREGUN_Tip_b)){
	      	 	$Lxsql = "SELECT PREGUN_ConsInte__OPCION_B FROM ".$BaseDatos_systema.".PREGUN WHERE PREGUN_ConsInte__b = ".$GUION__ConsInte__PREGUN_Tip_b;
	      	 	$capo  = $mysqli->query($Lxsql);
	      	 	$valorLista = NULL;
	      	 	while ($kay = $capo->fetch_object()) {
	      	 	 	$valorLista = $kay->PREGUN_ConsInte__OPCION_B;
	      	 	} 

	      	 	$campo  = '
<h3 id=\'h3mio\' style=\'color : rgb(110, 197, 255);\'></h3>

<div class="row">
	<div class="col-md-12 col-xs-12">
		<div class="box box-primary box-solid">
			<div class="box-header">
                <h3 class="box-title">Historico de gestiones</h3>
            </div>
			<div class="box-body">
				<table class="table table-bordered table-hover">
					<thead>
						<tr>
							<th>Gesti&oacute;n</th>
							<th>Comentarios</th>
							<th>Fecha - hora</th>
							<th>Agente</th>
						</tr>
					</thead>
					<tbody id="tablaGestiones">
						
					</tbody>
				</table>
			</div>
		</div>
	</div>
</div>';		
				fputs($fp , $campo);
				fputs($fp , chr(13).chr(10)); // Genera saldo de linea 
			}	

      	//aqui es donde va la jugada de los campos 
        $SeccionSsql = "SELECT SECCIO_ConsInte__b, SECCIO_TipoSecc__b, SECCIO_Nombre____b, SECCIO_PestMini__b, SECCIO_NumColumnas_b, SECCIO_VistPest__b FROM ".$BaseDatos_systema.".SECCIO WHERE SECCIO_ConsInte__GUION__b =  ".$id_a_generar." AND SECCIO_TipoSecc__b != 2 ORDER BY SECCIO_Orden_____b ASC ";

        $Secciones = $mysqli->query($SeccionSsql);
        $Columnas = 1;

        while ($seccionAqui = $Secciones->fetch_object()) {

            $id_seccion = $seccionAqui->SECCIO_ConsInte__b;
            if(!empty($seccionAqui->SECCIO_NumColumnas_b)){
                $Columnas = $seccionAqui->SECCIO_NumColumnas_b ;
            }
            $stylo = '';
            if($seccionAqui->SECCIO_TipoSecc__b == 4){
            	$stylo = 'style=\'display:none;\'';
            }
            
            //En un principio se abren las secciones
            if($seccionAqui->SECCIO_VistPest__b == 1){

                $str_Campo = '
<div id="'.$seccionAqui->SECCIO_ConsInte__b.'" '.$stylo.'>
';
                fputs($fp , $str_Campo);
                fputs($fp , chr(13).chr(10)); // Genera saldo de linea 

            }else  if($seccionAqui->SECCIO_VistPest__b == 2){

                 $str_Campo = '
<div id="'.$seccionAqui->SECCIO_ConsInte__b.'" '.$stylo.'>
    <h3 class="box box-title">'.($seccionAqui->SECCIO_Nombre____b).'</h3>';
                fputs($fp , $str_Campo);
                fputs($fp , chr(13).chr(10)); // Genera saldo de linea 


            }else  if($seccionAqui->SECCIO_VistPest__b == 3){
                $colapse = '';
                if($seccionAqui->SECCIO_PestMini__b != 0){
                    $colapse = 'in';
                }

                $str_Campo ='
<div class="panel box box-primary" '.$stylo.'>
    <div class="box-header with-border">
        <h4 class="box-title">
            <a data-toggle="collapse" data-parent="#accordion" href="#s_'.$seccionAqui->SECCIO_ConsInte__b.'">
                '.($seccionAqui->SECCIO_Nombre____b).'
            </a>
        </h4>
    </div>
    <div id="s_'.$seccionAqui->SECCIO_ConsInte__b.'" class="panel-collapse collapse '.$colapse.'">
        <div class="box-body">';
            fputs($fp , $str_Campo);
            fputs($fp , chr(13).chr(10)); // Genera saldo de linea

            }else{


                $str_Campo ='
<div class="panel box box-primary" '.$stylo.'>
    <div class="box-header with-border">
        <h4 class="box-title">
            '.($seccionAqui->SECCIO_Nombre____b).'
        </h4>
    </div>
    <div class="box-body">';
        fputs($fp , $str_Campo);
        fputs($fp , chr(13).chr(10)); // Genera saldo de linea 

            }









        //Aqui hacemos el dibujo de los str_Campos
        $str_LsqlXD = "SELECT PREGUN_Texto_____b as titulo_pregunta , PREGUN_Tipo______b as tipo_Pregunta, PREGUN_ConsInte__b as id , PREGUN_ConsInte__GUION__PRE_B as guion, PREGUN_ConsInte__OPCION_B as lista, PREGUN_OrdePreg__b , PREGUN_NumeMini__b as minimoNumero, PREGUN_NumeMaxi__b as maximoNumero, PREGUN_FechMini__b as fechaMinimo, PREGUN_FechMaxi__b as fechaMaximo , PREGUN_HoraMini__b as horaMini , PREGUN_HoraMaxi__b as horaMaximo, PREGUN_TexErrVal_b as error , PREGUN_ConsInte__SECCIO_b, PREGUN_Default___b, PREGUN_ContAcce__b, PREGUN_PermiteAdicion_b , PREGUN_ConsInte_PREGUN_Depende_b , PREGUN_DefaNume__b , PREGUN_OperEntreCamp_____b FROM ".$BaseDatos_systema.".PREGUN WHERE PREGUN_ConsInte__GUION__b = ".$id_a_generar." AND PREGUN_ConsInte__SECCIO_b = ".$id_seccion." AND PREGUN_FueGener_b != 3 ORDER BY PREGUN_OrdePreg__b  ASC";

            $str_Campos = $mysqli->query($str_LsqlXD);
            $rowsss = 0;

            $seccion = '';
            $seccionActual = '';

            $maxColumns = $Columnas;
            $filaActual = 0;
            $checkColumnas = (12 / $Columnas);
            while($obj = $str_Campos->fetch_object()){
                
                if( $obj->id != $GUION__ConsInte__PREGUN_Tip_b && 
                    $obj->id != $GUION__ConsInte__PREGUN_Rep_b &&
                    $obj->id != $GUION__ConsInte__PREGUN_Fag_b && 
                    $obj->id != $GUION__ConsInte__PREGUN_Hag_b &&
                    $obj->id != $GUION__ConsInte__PREGUN_Com_b){
                  ///  $seccion = $obj->PREGUN_ConsInte__SECCIO_b; 

                //  
                if( $filaActual == 0 ){
                    $str_Campo = '
        <div class="row">
        ';
        fputs($fp , $str_Campo);
        fputs($fp , chr(13).chr(10)); // Genera saldo de linea 

                }


$str_Campo = '
            <div class="col-md-'.$checkColumnas.' col-xs-'.$checkColumnas.'">
';
fputs($fp , $str_Campo);
fputs($fp , chr(13).chr(10)); // Genera saldo de linea 


                $valorPordefecto = $obj->PREGUN_Default___b;
                $valoraMostrar = "";
                $disableds = '';
                if($obj->PREGUN_ContAcce__b == 2){
                    $disableds = 'readonly';
                }
                switch ($valorPordefecto) {
                    case '501':
                        $valoraMostrar = '<?php echo date(\'Y-m-d\');?>';
                        break;

                    case '1001':
                        $valoraMostrar = '<?php echo date(\'H:i:s\');?>';
                        break;

                    case '102':
                        $valoraMostrar = '<?php echo getNombreUser($token);?>';
                        break;
                    
                    case '105':
                        $valoraMostrar = '<?php if(isset($_GET["campana_crm"])){ $cmapa = "SELECT CAMPAN_Nombre____b FROM ".$BaseDatos_systema.".CAMPAN WHERE CAMPAN_ConsInte__b = ".$_GET["campana_crm"];
                $resCampa = $mysqli->query($cmapa);
                $dataCampa = $resCampa->fetch_array(); echo $dataCampa["CAMPAN_Nombre____b"];}else{
                	echo "SIN CAMPAÑA";}?>';
                        break;

                    case '3001':

                        $valoraMostrar = $obj->PREGUN_DefaNume__b;

                        break;

                    case '3002':

                    	//Es el autonumerico
                        $valoraMostrar = '<?php $Lsql = "SELECT CONTADORES_Valor_b FROM ".$BaseDatos_systema.".CONTADORES WHERE CONTADORES_ConsInte__PREGUN_b = '.$obj->id.'"; $res = $mysqli->query($Lsql); $dato = $res->fetch_array(); echo ($dato["CONTADORES_Valor_b"] + 1); $XLsql = "UPDATE ".$BaseDatos_systema.".CONTADORES SET CONTADORES_Valor_b = CONTADORES_Valor_b + 1 WHERE CONTADORES_ConsInte__PREGUN_b = '.$obj->id.'"; $mysqli->query($XLsql);?>';
                        
                        break;

                    default:
                        $valoraMostrar = null;
                        break;
                }
                switch ($obj->tipo_Pregunta) {
                    case '1':
                    	if($obj->titulo_pregunta != 'PASO_ID' && $obj->titulo_pregunta != 'REGISTRO_ID'){
$campo = ' 
			        <!-- CAMPO TIPO TEXTO -->
			        <div class="form-group">
			            <label for="'.$guion_c.$obj->id.'" id="Lbl'.$guion_c.$obj->id.'">'.($obj->titulo_pregunta).'</label>
			            <input type="text" class="form-control input-sm" id="'.$guion_c.$obj->id.'" value="'.$valoraMostrar.'" '.$disableds.' name="'.$guion_c.$obj->id.'"  placeholder="'.($obj->titulo_pregunta).'">
			        </div>
			        <!-- FIN DEL CAMPO TIPO TEXTO -->';
                   $funciones_js .= "\n".'
    //function para '.($obj->titulo_pregunta).' '."\n".'
    $("#'.$guion_c.$obj->id.'").on(\'blur\',function(e){});';
                   fputs($fp , $campo);
                   fputs($fp , chr(13).chr(10)); // Genera saldo de linea 
               			}
                    break;

                    case '2':
$campo = '  
			        <!-- CAMPO TIPO MEMO -->
			        <div class="form-group">
			            <label for="'.$guion_c.$obj->id.'" id="Lbl'.$guion_c.$obj->id.'">'.($obj->titulo_pregunta).'</label>
			            <textarea class="form-control input-sm" name="'.$guion_c.$obj->id.'" id="'.$guion_c.$obj->id.'" '.$disableds.' value="'.$valoraMostrar.'" placeholder="'.($obj->titulo_pregunta).'"></textarea>
			        </div>
			        <!-- FIN DEL CAMPO TIPO MEMO -->';
                    $funciones_js .= "\n".'
    //function para '.($obj->titulo_pregunta).' '."\n".'
    $("#'.$guion_c.$obj->id.'").on(\'blur\',function(e){});';
                    fputs($fp , $campo);
            fputs($fp , chr(13).chr(10)); // Genera saldo de linea 
                    break;

                    case '3':
                    	if($obj->titulo_pregunta != 'PASO_ID' && $obj->titulo_pregunta != 'REGISTRO_ID'){
$campo = ' 
			        <!-- CAMPO TIPO ENTERO -->
			        <!-- Estos campos siempre deben llevar Numerico en la clase asi class="form-control input-sm Numerico" , de l contrario seria solo un campo de texto mas -->
			        <div class="form-group">
			            <label for="'.$guion_c.$obj->id.'" id="Lbl'.$guion_c.$obj->id.'">'.($obj->titulo_pregunta).'</label>
			            <input type="text" class="form-control input-sm Numerico" value="'.$valoraMostrar.'" '.$disableds.' name="'.$guion_c.$obj->id.'" id="'.$guion_c.$obj->id.'" placeholder="'.($obj->titulo_pregunta).'">
			        </div>
			        <!-- FIN DEL CAMPO TIPO ENTERO -->';
                    
                    fputs($fp , $campo);
            		fputs($fp , chr(13).chr(10)); // Genera saldo de linea 

    				$LsqlCadena = "SELECT PREGUN_ConsInte__b, PREGUN_OperEntreCamp_____b FROM ".$BaseDatos_systema.".PREGUN WHERE PREGUN_ConsInte__GUION__b = ".$id_a_generar."  AND (PREGUN_Tipo______b = 3 OR PREGUN_Tipo______b = 4) AND PREGUN_OperEntreCamp_____b IS NOT NULL "; 
    				$cur_result = $mysqli->query($LsqlCadena);
    				$itsCadena = false;
			        while ($key = $cur_result->fetch_object()) {	
			        	/* ahora toca buscar el valor de esos campos en la jugada */
			            $dato = str_replace(' ', '_', ($obj->titulo_pregunta));

			        	$buscar = '${'.substr(sanear_stringsXYZ($dato), 0, 20).'}';

			        
			        	//$pos = strpos($key->PREGUN_OperEntreCamp_____b, $buscar);
			        	
			        	
			        	if (stristr(trim($key->PREGUN_OperEntreCamp_____b), $buscar) !== false) {
						   	$itsCadena = true;
						   	//echo "Es esto => ".$buscar." Aqui => ".$key->PREGUN_OperEntreCamp_____b;
						   	/* Toca hacer todo el frito */
						   	$funciones_jsY .= "\n".'
	    //function para '.($obj->titulo_pregunta).' '."\n".'
    $("#'.$guion_c.$obj->id.'").on(\'blur\',function(e){';

						   	$LsqlCadenaX = "SELECT PREGUN_Texto_____b, PREGUN_ConsInte__b, PREGUN_OperEntreCamp_____b FROM ".$BaseDatos_systema.".PREGUN WHERE PREGUN_ConsInte__GUION__b = ".$id_a_generar."  AND (PREGUN_Tipo______b = 3 OR PREGUN_Tipo______b = 4)"; 
							$cadenaFinalX = trim(str_replace('=', '', $key->PREGUN_OperEntreCamp_____b));

	        				$cur_resultX = $mysqli->query($LsqlCadenaX);

					        while ($keyX = $cur_resultX->fetch_object()) {

					        	/* ahora toca buscar el valor de esos campos en la jugada */
					            $datoX = str_replace(' ', '_', ($keyX->PREGUN_Texto_____b));

					        	$buscarX = '${'.substr(sanear_stringsXYZ($datoX), 0, 20).'}';

					        	$reemplazo = 'Number($("#'.$guion_c.$keyX->PREGUN_ConsInte__b.'").val())';

					        	$cadenaFinalX = str_replace($buscarX, $reemplazo , $cadenaFinalX);
					        }

					    

					       	$funciones_jsY .= "\n".'

    	$("#'.$guion_c.$key->PREGUN_ConsInte__b.'").val('.$cadenaFinalX.');';

	    					$funciones_jsY .= "\n".'
    });';
						}
			        }

			        if($itsCadena == false){
			        	/* No esta metido en ningun lado */
			        	$funciones_js .= "\n".'
    //function para '.($obj->titulo_pregunta).' '."\n".'
    $("#'.$guion_c.$obj->id.'").on(\'blur\',function(e){});';

			        }
			        	}

                    break;

                    case '4':
                    	if($obj->titulo_pregunta != 'PASO_ID' && $obj->titulo_pregunta != 'REGISTRO_ID'){
$campo = '  
			        <!-- CAMPO TIPO DECIMAL -->
			        <!-- Estos campos siempre deben llevar Decimal en la clase asi class="form-control input-sm Decimal" , de l contrario seria solo un campo de texto mas -->
			        <div class="form-group">
			            <label for="'.$guion_c.$obj->id.'" id="Lbl'.$guion_c.$obj->id.'">'.($obj->titulo_pregunta).'</label>
			            <input type="text" class="form-control input-sm Decimal" value="'.$valoraMostrar.'" '.$disableds.' name="'.$guion_c.$obj->id.'" id="'.$guion_c.$obj->id.'" placeholder="'.($obj->titulo_pregunta).'">
			        </div>
			        <!-- FIN DEL CAMPO TIPO DECIMAL -->';
                    
                    fputs($fp , $campo);
                     fputs($fp , chr(13).chr(10)); // Genera saldo de linea 

                     $LsqlCadena = "SELECT PREGUN_ConsInte__b, PREGUN_OperEntreCamp_____b FROM ".$BaseDatos_systema.".PREGUN WHERE PREGUN_ConsInte__GUION__b = ".$id_a_generar."  AND (PREGUN_Tipo______b = 3 OR PREGUN_Tipo______b = 4) AND PREGUN_OperEntreCamp_____b IS NOT NULL "; 
    				$cur_result = $mysqli->query($LsqlCadena);
    				$itsCadena = false;
			        while ($key = $cur_result->fetch_object()) {	
			        	/* ahora toca buscar el valor de esos campos en la jugada */
			            $dato = str_replace(' ', '_', ($obj->titulo_pregunta));

			        	$buscar = '${'.substr(sanear_stringsXYZ($dato), 0, 20).'}';

			        
			        	//$pos = strpos($key->PREGUN_OperEntreCamp_____b, $buscar);
			        	
			        	
			        	if (stristr(trim($key->PREGUN_OperEntreCamp_____b), $buscar) !== false) {
						   	$itsCadena = true;
						   	//echo "Es esto => ".$buscar." Aqui => ".$key->PREGUN_OperEntreCamp_____b;
						   	/* Toca hacer todo el frito */
						   	$funciones_jsY .= "\n".'
	    //function para '.($obj->titulo_pregunta).' '."\n".'
    $("#'.$guion_c.$obj->id.'").on(\'blur\',function(e){';

						   	$LsqlCadenaX = "SELECT PREGUN_Texto_____b, PREGUN_ConsInte__b, PREGUN_OperEntreCamp_____b FROM ".$BaseDatos_systema.".PREGUN WHERE PREGUN_ConsInte__GUION__b = ".$id_a_generar."  AND (PREGUN_Tipo______b = 3 OR PREGUN_Tipo______b = 4)"; 
							$cadenaFinalX = trim(str_replace('=', '', $key->PREGUN_OperEntreCamp_____b));

	        				$cur_resultX = $mysqli->query($LsqlCadenaX);

					        while ($keyX = $cur_resultX->fetch_object()) {

					        	/* ahora toca buscar el valor de esos campos en la jugada */
					            $datoX = str_replace(' ', '_', ($keyX->PREGUN_Texto_____b));

					        	$buscarX = '${'.substr(sanear_stringsXYZ($datoX), 0, 20).'}';

					        	$reemplazo = 'Number($("#'.$guion_c.$keyX->PREGUN_ConsInte__b.'").val())';

					        	$cadenaFinalX = str_replace($buscarX, $reemplazo , $cadenaFinalX);
					        }

					       	$funciones_jsY .= "\n".'

    	$("#'.$guion_c.$key->PREGUN_ConsInte__b.'").val('.$cadenaFinalX.');';

	    					$funciones_jsY .= "\n".'
    });';
						}
			        }

			        if($itsCadena == false){
			        	/* No esta metido en ningun lado */
			        	$funciones_js .= "\n".'
    //function para '.($obj->titulo_pregunta).' '."\n".'
    $("#'.$guion_c.$obj->id.'").on(\'blur\',function(e){});';

			        }

			        	}
                    break;

                    case '5':
$campo = '  
			        <!-- CAMPO TIPO FECHA -->
			        <!-- Estos campos siempre deben llevar Fecha en la clase asi class="form-control input-sm Fecha" , de l contrario seria solo un campo de texto mas -->
			        <div class="form-group">
			            <label for="'.$guion_c.$obj->id.'" id="Lbl'.$guion_c.$obj->id.'">'.($obj->titulo_pregunta).'</label>
			            <input type="text" class="form-control input-sm Fecha" value="'.$valoraMostrar.'" '.$disableds.' name="'.$guion_c.$obj->id.'" id="'.$guion_c.$obj->id.'" placeholder="YYYY-MM-DD">
			        </div>
			        <!-- FIN DEL CAMPO TIPO FECHA-->';
                    $funciones_js .= "\n".'
    //function para '.($obj->titulo_pregunta).' '."\n".'
    $("#'.$guion_c.$obj->id.'").on(\'blur\',function(e){});';
                    fputs($fp , $campo);
            fputs($fp , chr(13).chr(10)); // Genera saldo de linea 
                    break;
            
                    case '10':
$campo = '  
			        <!-- CAMPO TIMEPICKER -->
			        <!-- Estos campos siempre deben llevar Hora en la clase asi class="form-control input-sm Hora" , de l contrario seria solo un campo de texto mas -->
			        <div class="bootstrap-timepicker">
			            <div class="form-group">
			                <label for="'.$guion_c.$obj->id.'" id="Lbl'.$guion_c.$obj->id.'">'.($obj->titulo_pregunta).'</label>
			                <div class="input-group">
			                    <input type="text" class="form-control input-sm Hora" '.$disableds.' name="'.$guion_c.$obj->id.'" id="'.$guion_c.$obj->id.'" placeholder="HH:MM:SS" >
			                    <div class="input-group-addon" id="TMP_'.$guion_c.$obj->id.'">
			                        <i class="fa fa-clock-o"></i>
			                    </div>
			                </div>
			                <!-- /.input group -->
			            </div>
			            <!-- /.form group -->
			        </div>
			        <!-- FIN DEL CAMPO TIMEPICKER -->';
                     $funciones_js .= "\n".'
    //function para '.($obj->titulo_pregunta).' '."\n".'
    $("#'.$guion_c.$obj->id.'").on(\'blur\',function(e){});';
                    fputs($fp , $campo);
            fputs($fp , chr(13).chr(10)); // Genera saldo de linea 
                    break;

                    case '6':

                    if($obj->PREGUN_ContAcce__b != 2){
                    	$select2 .= "\n".'
    $("#'.$guion_c.$obj->id.'").select2();';
    				}

$campo = '
			        <!-- CAMPO DE TIPO LISTA -->
			        <div class="form-group">
			            <label for="'.$guion_c.$obj->id.'" id="Lbl'.$guion_c.$obj->id.'">'.($obj->titulo_pregunta).'</label>
			            <select class="form-control input-sm select2"  style="width: 100%;" name="'.$guion_c.$obj->id.'" '.$disableds.' id="'.$guion_c.$obj->id.'">
			                <option value="0">Seleccione</option>
			                <?php
			                    /*
			                        SE RECORRE LA CONSULTA QUE SE CARGO CON ANTERIORIDAD EN LA SECCION DE CARGUE LISTAS DESPLEGABLES
			                    */
			                    $Lsql = "SELECT LISOPC_ConsInte__b AS OPCION_ConsInte__b, LISOPC_Nombre____b AS OPCION_Nombre____b FROM ".$BaseDatos_systema.".LISOPC WHERE LISOPC_ConsInte__OPCION_b = '.$obj->lista.' ORDER BY LISOPC_Nombre____b ASC";

			                    $obj = $mysqli->query($Lsql);
			                    while($obje = $obj->fetch_object()){
			                        echo "<option value=\'".$obje->OPCION_ConsInte__b."\'>".($obje->OPCION_Nombre____b)."</option>";

			                    }    
			                    
			                ?>
			            </select>
			        </div>
			        <!-- FIN DEL CAMPO TIPO LISTA -->';

$GuionsSql = "SELECT PRSADE_ConsInte__OPCION_b  FROM ".$BaseDatos_systema.".PRSADE WHERE PRSADE_ConsInte__PREGUN_b = '".$obj->id."' GROUP BY PRSADE_ConsInte__OPCION_b";
$result = $mysqli->query($GuionsSql);
$saltos = '';
if($result->num_rows > 0){
    $sinsalto = '';
    $tamanho = 0;
    $SqlLosquenoEstan = "SELECT PREGUN_ConsInte__b FROM ".$BaseDatos_systema.".PREGUN WHERE PREGUN_ConsInte__GUION__b = ".$id_a_generar." AND PREGUN_ContAcce__b IS NULL;";
   // echo $SqlLosquenoEstan;
    $ResultLosqueNoestan = $mysqli->query($SqlLosquenoEstan);
    while ($newObjrQueNoestan = $ResultLosqueNoestan->fetch_object()) {
        $saltos .= '
            $("#'.$guion_c.$newObjrQueNoestan->PREGUN_ConsInte__b.'").prop(\'disabled\', false);
        ';
    }
    while($objr = $result->fetch_object()){
        /* Ya tengo la opcion ahora vamos a buscar los PRSASA de estas opciones */
        $saltos .= '
            if($(this).val() == \''.$objr->PRSADE_ConsInte__OPCION_b.'\'){';
        $newSql = "SELECT * FROM ".$BaseDatos_systema.".PRSASA JOIN ".$BaseDatos_systema.".PRSADE ON PRSADE_ConsInte__b = PRSASA_ConsInte__PRSADE_b WHERE PRSADE_ConsInte__PREGUN_b = ".$obj->id." AND PRSADE_ConsInte__OPCION_b = ".$objr->PRSADE_ConsInte__OPCION_b.";";
        $newResult = $mysqli->query($newSql);
        while ($newObjr = $newResult->fetch_object()) {
          $saltos .= '
            $("#'.$newObjr->PRSASA_NombCont__b.'").prop(\'disabled\', true); 
          ';
        }
        $saltos .= '
            }
        ';
    }
}

//Aqui lo que estamos haciendo es validar que la pregunta tenga o no hijos o dependencias
$validarSiEsPadre = "SELECT PREGUN_ConsInte__b, PREGUN_ConsInte__OPCION_B FROM ".$BaseDatos_systema.".PREGUN WHERE PREGUN_ConsInte_PREGUN_Depende_b = ".$obj->id;
$resEsPadre = $mysqli->query($validarSiEsPadre);
$hijosdeEsteGuion = "\n";
if($resEsPadre->num_rows > 0){
	while ($keyPadre = $resEsPadre->fetch_object()) {
		$hijosdeEsteGuion .='
		$.ajax({
			url    : \'<?php echo $url_crud; ?>\',
			type   : \'post\',
			data   : { getListaHija : true , opcionID : \''.$keyPadre->PREGUN_ConsInte__OPCION_B.'\' , idPadre : $(this).val() },
			success : function(data){
				$("#'.$guion_c.$keyPadre->PREGUN_ConsInte__b.'").html(data);
			}
		});
		';
	}	
}

                    $funciones_jsx .="\n".'
    //function para '.($obj->titulo_pregunta).' '. "\n".'
    $("#'.$guion_c.$obj->id.'").change(function(){ '.$saltos.' 
    	//Esto es la parte de las listas dependientes
    	'.$hijosdeEsteGuion.'
    });';


                    
                  fputs($fp , $campo);
                  fputs($fp , chr(13).chr(10)); // Genera saldo de linea 
                    break;


                    case '13':

                    if($obj->PREGUN_ContAcce__b != 2){
                    	$select2 .= "\n".'
    $("#'.$guion_c.$obj->id.'").select2();';
    				}

$campo = '
			        <!-- CAMPO DE TIPO LISTA / Respuesta -->
			        <div class="form-group">
			            <label for="'.$guion_c.$obj->id.'" id="Lbl'.$guion_c.$obj->id.'">'.($obj->titulo_pregunta).'</label>
			            <select class="form-control input-sm select2"  style="width: 100%;" name="'.$guion_c.$obj->id.'" '.$disableds.' id="'.$guion_c.$obj->id.'">
			                <option value="0">Seleccione</option>
			                <?php
			                    /*
			                        SE RECORRE LA CONSULTA QUE SE CARGO CON ANTERIORIDAD EN LA SECCION DE CARGUE LISTAS DESPLEGABLES
			                    */
			                    $Lsql = "SELECT LISOPC_ConsInte__b AS OPCION_ConsInte__b, LISOPC_Nombre____b AS OPCION_Nombre____b, LISOPC_Respuesta_b FROM ".$BaseDatos_systema.".LISOPC WHERE LISOPC_ConsInte__OPCION_b = '.$obj->lista.' ORDER BY LISOPC_Nombre____b ASC";

			                    $obj = $mysqli->query($Lsql);
			                    while($obje = $obj->fetch_object()){
			                        echo "<option value=\'".$obje->OPCION_ConsInte__b."\' respuesta = \'".$obje->LISOPC_Respuesta_b."\'>".($obje->OPCION_Nombre____b)."</option>";

			                    }    
			                    
			                ?>
			            </select>
			        </div>
			        <div class="form-group">
			            <label for="respuesta_'.$guion_c.$obj->id.'" id="respuesta_Lbl'.$guion_c.$obj->id.'">Respuesta</label>
			            <textarea id="respuesta_'.$guion_c.$obj->id.'" class="form-control" placeholder="Respuesta"></textarea>
			        </div>
			        <!-- FIN DEL CAMPO TIPO LISTA  / Respuesta -->';

$GuionsSql = "SELECT PRSADE_ConsInte__OPCION_b  FROM ".$BaseDatos_systema.".PRSADE WHERE PRSADE_ConsInte__PREGUN_b = '".$obj->id."' GROUP BY PRSADE_ConsInte__OPCION_b";
$result = $mysqli->query($GuionsSql);
$saltos = '';
if($result->num_rows > 0){
    $sinsalto = '';
    $tamanho = 0;
    $SqlLosquenoEstan = "SELECT PREGUN_ConsInte__b FROM ".$BaseDatos_systema.".PREGUN WHERE PREGUN_ConsInte__GUION__b = ".$id_a_generar." AND PREGUN_ContAcce__b IS NULL;";
   // echo $SqlLosquenoEstan;
    $ResultLosqueNoestan = $mysqli->query($SqlLosquenoEstan);
    while ($newObjrQueNoestan = $ResultLosqueNoestan->fetch_object()) {
        $saltos .= '
            $("#'.$guion_c.$newObjrQueNoestan->PREGUN_ConsInte__b.'").prop(\'disabled\', false);
        ';
    }
    while($objr = $result->fetch_object()){
        /* Ya tengo la opcion ahora vamos a buscar los PRSASA de estas opciones */
        $saltos .= '
            if($(this).val() == \''.$objr->PRSADE_ConsInte__OPCION_b.'\'){';
        $newSql = "SELECT * FROM ".$BaseDatos_systema.".PRSASA JOIN ".$BaseDatos_systema.".PRSADE ON PRSADE_ConsInte__b = PRSASA_ConsInte__PRSADE_b WHERE PRSADE_ConsInte__PREGUN_b = ".$obj->id." AND PRSADE_ConsInte__OPCION_b = ".$objr->PRSADE_ConsInte__OPCION_b.";";
        $newResult = $mysqli->query($newSql);
        while ($newObjr = $newResult->fetch_object()) {
          $saltos .= '
            $("#'.$newObjr->PRSASA_NombCont__b.'").prop(\'disabled\', true); 
          ';
        }
        $saltos .= '
            }
        ';
    }
}

//Aqui lo que estamos haciendo es validar que la pregunta tenga o no hijos o dependencias
$validarSiEsPadre = "SELECT PREGUN_ConsInte__b, PREGUN_ConsInte__OPCION_B FROM ".$BaseDatos_systema.".PREGUN WHERE PREGUN_ConsInte_PREGUN_Depende_b = ".$obj->id;
$resEsPadre = $mysqli->query($validarSiEsPadre);
$hijosdeEsteGuion = "\n";
if($resEsPadre->num_rows > 0){
	while ($keyPadre = $resEsPadre->fetch_object()) {
		$hijosdeEsteGuion .='
		$.ajax({
			url    : \'<?php echo $url_crud; ?>\',
			type   : \'post\',
			data   : { getListaHija : true , opcionID : \''.$keyPadre->PREGUN_ConsInte__OPCION_B.'\' , idPadre : $(this).val() },
			success : function(data){
				$("#'.$guion_c.$keyPadre->PREGUN_ConsInte__b.'").html(data);
			}
		});
		';
	}	
}

                    $funciones_jsx .="\n".'
    //function para '.($obj->titulo_pregunta).' '. "\n".'
    $("#'.$guion_c.$obj->id.'").change(function(){ '.$saltos.' 
    	//Esto es la parte de las listas dependientes
    	'.$hijosdeEsteGuion.'
    });';



                    
                  fputs($fp , $campo);
                  fputs($fp , chr(13).chr(10)); // Genera saldo de linea 
                    break;




                    case '11':

                //Primero necesitamos obener el campo que vamos a usar
                $campoGuion = $obj->id;
                $guionSelect2 = $obj->guion;


$campo = ' 
			       
			        <!-- CAMPO DE TIPO GUION -->
			        <div class="form-group">
			            <label for="'.$guion_c.$obj->id.'" id="Lbl'.$guion_c.$obj->id.'">'.($obj->titulo_pregunta).'</label>
			            <select class="form-control input-sm select2" style="width: 100%;" '.$disableds.'  name="'.$guion_c.$obj->id.'" id="'.$guion_c.$obj->id.'">
			                <option value="0" id="opcionVacia">Seleccione</option>
			            </select>
			        </div>
			        <!-- FIN DEL CAMPO TIPO LISTA -->';

		//Obtener el campo principal del guion
		$Lsql_Principal_Guion = "SELECT GUION__ConsInte__PREGUN_Pri_b FROM ".$BaseDatos_systema.".GUION_ WHERE GUION__ConsInte__b = ".$guionSelect2;
		$resX = $mysqli->query($Lsql_Principal_Guion);
		$datosX = $resX->fetch_array();

		/* Neceistamos obtener los campos que vamos a llenar dinamicamente */
		$dtosaPoner = '';
		$datosPonerFinal = '';
		$CampoSql = "SELECT * FROM ".$BaseDatos_systema.".PREGUI WHERE PREGUI_ConsInte__PREGUN_b = ".$campoGuion;
		//recorremos esos campos para ir a buscarlos en la tabla campo_
        $CampoSqlR = $mysqli->query($CampoSql);
        while ($jkey = $CampoSqlR->fetch_object()) {
        	if($jkey->PREGUI_Consinte__CAMPO__GUI_B != 0){
                //Consulto por el id del campo que necesitamos y se repite las veces que sean necesarias
                $campoamostrarSql = 'SELECT CAMPO__Nombre____b FROM '.$BaseDatos_systema.'.CAMPO_ WHERE CAMPO__ConsInte__b = '.$jkey->PREGUI_Consinte__CAMPO__GUI_B;
                $campoamostrarSqlE = $mysqli->query($campoamostrarSql);
                while($campoNombres = $campoamostrarSqlE->fetch_object()){
                    $dtosaPoner .= "\n".'$data[$i][\''.$campoNombres->CAMPO__Nombre____b.'\']';
                    $datosPonerFinal .= "\n".'$("#'.$campoNombres->CAMPO__Nombre____b.'")';
                }

                $campoamostrarSql = 'SELECT CAMPO__Nombre____b FROM '.$BaseDatos_systema.'.CAMPO_ WHERE CAMPO__ConsInte__b = '.$jkey->PREGUI_ConsInte__CAMPO__b;
                $campoamostrarSqlE = $mysqli->query($campoamostrarSql);
                while($campoNombres = $campoamostrarSqlE->fetch_object()){
                    $dtosaPoner .= ' = $key->'.$campoNombres->CAMPO__Nombre____b.';';
                    $datosPonerFinal .= '.val(item.'.$campoNombres->CAMPO__Nombre____b.');';
                }
            }
        }

    	/* Neceistamos obtener los campos que vamos a llenar dinamicamente */
		$SqlCamposAMostrar = "SELECT PREGUI_ConsInte__PREGUN_b, Camp1.CAMPO__Nombre____b as PREGUI_ConsInte__CAMPO__b , Camp2.CAMPO__Nombre____b as PREGUI_Consinte__CAMPO__GUI_B  FROM  ".$BaseDatos_systema.".PREGUI JOIN ".$BaseDatos_systema.".CAMPO_ as Camp1 ON Camp1.CAMPO__ConsInte__b =  PREGUI_ConsInte__CAMPO__b JOIN ".$BaseDatos_systema.".CAMPO_ as Camp2 ON Camp2.CAMPO__ConsInte__b =  PREGUI_Consinte__CAMPO__GUI_B WHERE PREGUI_Consinte__GUION__b = ".$guionSelect2;

		$CamposQuevaAmostrarEnconsulta = $mysqli->query($SqlCamposAMostrar);
		$verdaderoscamposAmostrar = " G".$guionSelect2."_ConsInte__b as id ";
		$verdaderosCamposAllenar  = '';
		$verdaderosCamposAllenarGuion  = '';
		while ($keyXMostar = $CamposQuevaAmostrarEnconsulta->fetch_object()) {
			$verdaderoscamposAmostrar .=  ", ".$keyXMostar->PREGUI_ConsInte__CAMPO__b;
			$verdaderosCamposAllenar  .= '
			$data[$i][\''.$keyXMostar->PREGUI_Consinte__CAMPO__GUI_B.'\'] = $key->'.$keyXMostar->PREGUI_ConsInte__CAMPO__b.";\n";
			$verdaderosCamposAllenarGuion  .= "
			$('#".$keyXMostar->PREGUI_Consinte__CAMPO__GUI_B."').val(data[0].".$keyXMostar->PREGUI_Consinte__CAMPO__GUI_B.");\n";
		}


                    $funcionesCampoGuion .= "\n".'
        if(isset($_GET[\'MostrarCombo_Guion_'.$guion_c.$obj->id.'\'])){
            echo \'<select class="form-control input-sm"  name="'.$guion_c.$obj->id.'" id="'.$guion_c.$obj->id.'">\';
            echo \'<option >Buscar</option>\';
            echo \'</select>\';
        }

        if(isset($_GET[\'CallDatosCombo_Guion_'.$guion_c.$obj->id.'\'])){
            $Ysql = "SELECT G'.$guionSelect2.'_ConsInte__b as id,  G'.$guionSelect2.'_C'.$datosX['GUION__ConsInte__PREGUN_Pri_b'].' as text FROM ".$BaseDatos.".G'.$obj->guion.' WHERE G'.$guionSelect2.'_C'.$datosX['GUION__ConsInte__PREGUN_Pri_b'].' LIKE \'%".$_POST[\'q\']."%\'";
            $guion = $mysqli->query($Ysql);
            $i = 0;
            $datos = array();
            while($obj = $guion->fetch_object()){
                $datos[$i][\'id\'] = $obj->id;
                $datos[$i][\'text\'] = $obj->text;
                $i++;
            } 
            echo json_encode($datos);
        }

        if(isset($_POST[\'dameValoresCamposDinamicos_Guion_'.$guion_c.$obj->id.'\'])){
             $Lsql = "SELECT '.$verdaderoscamposAmostrar.' FROM ".$BaseDatos.".G'.$guionSelect2.' WHERE G'.$guionSelect2.'_ConsInte__b = ".$_POST[\'dameValoresCamposDinamicos_Guion_'.$guion_c.$obj->id.'\'];
            $res = $mysqli->query($Lsql);
            $data = array();
            $i = 0;
            while ($key = $res->fetch_object()) {
	            '.$verdaderosCamposAllenar.'
                $i++;
            }
            
            echo json_encode($data);
        }
        ';

                    


        			if($obj->PREGUN_ContAcce__b != 2){
                    	$select2 .= "\n".'
        $("#'.$guion_c.$obj->id.'").select2({
        	placeholder: "Buscar",
	        allowClear: false,
	        minimumInputLength: 3,
	        ajax: {
	            url: \'<?=$url_crud;?>?CallDatosCombo_Guion_'.$guion_c.$obj->id.'=si\',
	            dataType: \'json\',
	            type : \'post\',
	            delay: 250,
	            data: function (params) {
	                return {
	                    q: params.term
	                };
	            },
              	processResults: function(data) {
				  	return {
				    	results: $.map(data, function(obj) {
				      		return {
				        		id: obj.id,
				        		text: obj.text
				      		};
				    	})
				  	};
				},
	            cache: true
	        }
    	});                              
        
        $("#'.$guion_c.$obj->id.'").change(function(){
            $.ajax({
        		url   : \'<?php echo $url_crud;?>\',
        		data  : { dameValoresCamposDinamicos_Guion_'.$guion_c.$obj->id.' : $(this).val() },
        		type  : \'post\',
        		dataType : \'json\',
        		success  : function(data){
        			'.$verdaderosCamposAllenarGuion.'	
        		}
        	});
        });
                                      ';

              		}
                    $select2_hojadeDatos .= "\n".'
                    $("#"+ rowid +"_'.$guion_c.$obj->id.'").change(function(){
                        var valores = $("#"+ rowid +"_'.$guion_c.$obj->id.' option:selected").attr("llenadores");
                        var campos = $("#"+ rowid +"_'.$guion_c.$obj->id.' option:selected").attr("dinammicos");
                        var r = valores.split(\'|\');

                        if(r.length > 1){

                            var c = campos.split(\'|\');
                            for(i = 1; i < r.length; i++){
                                if(!$("#"+c[i]).is("select")) {
                                // the input field is not a select
                                    $("#"+ rowid +"_"+c[i]).val(r[i]);
                                }else{
                                    var change = r[i].replace(\' \', \'\'); 
                                    $("#"+ rowid +"_"+c[i]).val(change).change();
                                }
                                
                            }
                        }
                    });';
                    $funciones_js .="\n".'
    //function para '.($obj->titulo_pregunta).' '. "\n".'
    $("#'.$guion_c.$obj->id.'").change(function(){});';
                    fputs($fp , $campo);
                    fputs($fp , chr(13).chr(10)); // Genera saldo de linea */
                    break;

                    case '8':
$campo = '  
			        <!-- CAMPO DE TIPO CHECKBOX O SI/NO -->
			        <div class="form-group">
			            <label for="'.$guion_c.$obj->id.'" id="Lbl'.$guion_c.$obj->id.'">'.($obj->titulo_pregunta).'</label>
			            <div class="checkbox">
			                <label>
			                    <input type="checkbox" value="1" name="'.$guion_c.$obj->id.'" id="'.$guion_c.$obj->id.'" '.$disableds.' data-error="Before you wreck yourself"  > 
			                </label>
			            </div>
			        </div>
			        <!-- FIN DEL CAMPO SI/NO -->';
                    $funciones_js .="\n".'
    //function para '.($obj->titulo_pregunta).' '. "\n".'
    $("#'.$guion_c.$obj->id.'").change(function(){
        if($(this).is(":checked")){

        }else{

        }
    });';
    fputs($fp , $campo);
    fputs($fp , chr(13).chr(10)); // Genera saldo de linea 
                    break;
            
            

                    case '9':
$campo = '  
			        <!-- lIBRETO O LABEL -->
			        <p style="text-align:justify;">'.($obj->titulo_pregunta).'</p>
			        <!-- FIN LIBRETO -->';
                    fputs($fp , $campo);
            fputs($fp , chr(13).chr(10)); // Genera saldo de linea 

                    break;
                    default:
              
                    break;
                }//Cierro el Swich

$campo = '  
            </div>
';    
fputs($fp , $campo);
fputs($fp , chr(13).chr(10)); // Genera saldo de linea 
                
                $filaActual += 1;
                if($filaActual >= $maxColumns){
                    $filaActual = 0;
                    $campo = '  
        </div>
';    
fputs($fp , $campo);
fputs($fp , chr(13).chr(10)); // Genera saldo de linea 
                }

                }//cierro el IF


        }//Cierro el Wile de secciones

        if($filaActual > 0){
            if($filaActual < $maxColumns){
                if($maxColumns % $filaActual != 0){
                    $filaActual = 0;
                    $campo = '
        </div>
';
fputs($fp , $campo);
fputs($fp , chr(13).chr(10)); // Genera saldo de linea
                }

                if($filaActual == 1){
                    $filaActual = 0;
                    $campo = '
        </div>
';
fputs($fp , $campo);
fputs($fp , chr(13).chr(10)); // Genera saldo de linea
                }
            }
        }
        

          /*              $campo = '
            </div>
';    
            fputs($fp , $campo);
            fputs($fp , chr(13).chr(10)); // Genera saldo de linea 

*/

            //aqui cerramos las secciones y obtenemos un solo codigo
            if($seccionAqui->SECCIO_VistPest__b == 1){

                $campo = '
</div>';
                fputs($fp , $campo);
                fputs($fp , chr(13).chr(10)); // Genera saldo de linea 

            }else  if($seccionAqui->SECCIO_VistPest__b == 2){

                 $campo = '
</div>';
                fputs($fp , $campo);
                fputs($fp , chr(13).chr(10)); // Genera saldo de linea 


            }else  if($seccionAqui->SECCIO_VistPest__b == 3){

            $campo = '
        </div>
    </div>
</div>';
            fputs($fp , $campo);
            fputs($fp , chr(13).chr(10)); // Genera saldo de linea 


            }else{


        $campo = '
    </div>
</div>';

fputs($fp , $campo);
fputs($fp , chr(13).chr(10)); 

            }
        }



			$tabsDeMaestro = '
<!-- SI ES MAESTRO - DETALLE CREO LAS TABS --> '."\n".'
<hr/>
<div class="nav-tabs-custom">';
        	$contenidoMaestro = "\n".'
    <ul class="nav nav-tabs">';
        	$tabscontenidoMaestro = "\n".'
    <div class="tab-content">';

	        $LsqlMaestro = "SELECT * FROM ".$BaseDatos_systema.".GUIDET WHERE GUIDET_ConsInte__GUION__Mae_b = ".$id_a_generar;
	        $EsONoEs = 0;
	        $EjecutarMaetsro = $mysqli->query($LsqlMaestro);
	        $Kaka = 0;
	        


	        $funcionCargarFinal = '';
	        $functionDescargarFinal = '';
	        $functionRecargarFinal ='';
	        $funcionDeguardadoDeLagrillaFinal ='';
	        

	        $funcionDeCargadoDelaGrillaFinal = '';
	        $functionLlamarAloshijosLuegoDeCargar = '';
	        $funcionCargarComboCuandoSeaMaestroFinal = '';
	        $camposXmlParallenarFinal = '';
	        $camposSubgrillaFinal ='';
	        $tabsFinalOperacions = '';

	        $ModalesFinal = '';
	        $limpiadordeGrillas = '';

	        $funcionCargarDatosDeLasPutasGrillas = '';
	        $darlePadreAlHijo = '';
	        $darlePadreAlHijo_2 = '';
	        $darlePadreAlHijo_3 = '';

	        $subgrilla = '
	            ,subGrid: true,
	            subGridRowExpanded: function(subgrid_id, row_id) { 
	                // we pass two parameters 
	                // subgrid_id is a id of the div tag created whitin a table data 
	                // the id of this elemenet is a combination of the "sg_" + id of the row 
	                // the row_id is the id of the row 
	                // If we wan to pass additinal parameters to the url we can use 
	                // a method getRowData(row_id) - which returns associative array in type name-value 
	                // here we can easy construct the flowing 
	                $("#"+subgrid_id).html(\'\');';

	        while ($key = $EjecutarMaetsro->fetch_object()) {
	            $activo = '';
	            if($Kaka == 0){
	               $activo = 'active';
	            }//cierro el ide  $Kaka == 0


	            $funcionCargar = '';
	            $functionDescargar = '';
	            $functionRecargar ='';
	            $funcionDeguardadoDeLagrilla ='';
	            

	            $funcionDeCargadoDelaGrilla = '';
	            $funcionCargarComboCuandoSeaMaestro = '';
	            $camposXmlParallenar = '';
	            $camposSubgrilla ='';
	            $tabsFinal = '';
	            $EsONoEs = 1; 

	            if($hayqueValidar > 0){
        			$botonSalvar2= "\n".'
            if($("#oper").val() == \'add\'){
                if(before_save()){
                    $("#frameContenedor").attr(\'src\', \'http://<?php echo $_SERVER["HTTP_HOST"];?>/crm_php/new_index.php?formulario='.$key->GUIDET_ConsInte__GUION__Det_b.'&view=si&formaDetalle=si&formularioPadre='.$id_a_generar.'&yourfather=\'+ idTotal +\'&pincheCampo='.$key->GUIDET_ConsInte__PREGUN_De1_b.'<?php if(isset($_GET[\'token\'])){ echo "&token=".$_GET[\'token\']; }?>\');
                    $("#editarDatos").modal(\'show\');
                    $("#oper").val(\'edit\');
                }else{
                    var d = new Date();
                    var h = d.getHours();
                    var horas = (h < 10) ? \'0\' + h : h;
                    var dia = d.getDate();
                    var dias = (dia < 10) ? \'0\' + dia : dia;
                    var fechaFinal = d.getFullYear() + \'-\' + meses[d.getMonth()] + \'-\' + dias + \' \'+ horas +\':\'+d.getMinutes()+\':\'+d.getSeconds();
                    $("#FechaFinal").val(fechaFinal);

                    before_save();
                    var valido = 0;
                    '.$camposValidaciones.'
                    if(valido == \'0\'){
                        var form = $("#FormularioDatos");
                        //Se crean un array con los datos a enviar, apartir del formulario 
                        var formData = new FormData($("#FormularioDatos")[0]);
                        $.ajax({
                            url: \'<?=$url_crud;?>?insertarDatosGrilla=si&usuario=<?php echo getIdentificacionUser($token);?>&CodigoMiembro=<?php if(isset($_GET[\'user\'])) { echo $_GET["user"]; }else{ echo "0";  } ?><?php if(isset($_GET[\'id_gestion_cbx\'])){ echo "&id_gestion_cbx=".$_GET[\'id_gestion_cbx\']; }?><?php if(!empty($token)){ echo "&token=".$token; }?>\',  
                            type: \'POST\',
                            data: formData,
                            cache: false,
                            contentType: false,
                            processData: false,
                            //una vez finalizado correctamente
                            success: function(data){
                                if(data){
                                    //Si realizo la operacion ,perguntamos cual es para posarnos sobre el nuevo registro
                                    if($("#oper").val() == \'add\'){
                                        idTotal = data;
                                    }else{
                                        idTotal= $("#hidId").val();
                                    }

                                    $("#hidId").val(idTotal);
                                    $("#oper").val(\'edit\');
                                    int_guardo = 1;
                                    $(".llamadores").attr(\'padre\', idTotal);
                                    $("#frameContenedor").attr(\'src\', \'http://<?php echo $_SERVER["HTTP_HOST"];?>/crm_php/new_index.php?formulario='.$key->GUIDET_ConsInte__GUION__Det_b.'&view=si&formaDetalle=si&formularioPadre='.$id_a_generar.'&yourfather=\'+ idTotal +\'&pincheCampo='.$key->GUIDET_ConsInte__PREGUN_De1_b.'&action=add<?php if(isset($_GET[\'token\'])){ echo "&token=".$_GET[\'token\']; }?>\');
                                    $("#editarDatos").modal(\'show\');
                                    $("#oper").val(\'edit\');
                                }                
                            },
                            //si ha ocurrido un error
                            error: function(){
                                after_save_error();
                                alertify.error(\'Ocurrio un error relacionado con la red, al momento de guardar, intenta mas tarde\');
                            }
                        });
                    }
                }
            }else{
                $("#frameContenedor").attr(\'src\', \'http://<?php echo $_SERVER["HTTP_HOST"];?>/crm_php/new_index.php?formulario='.$key->GUIDET_ConsInte__GUION__Det_b.'&view=si&yourfather=\'+ $(this).attr(\'padre\') +\'&formaDetalle=si&formularioPadre='.$id_a_generar.'&pincheCampo='.$key->GUIDET_ConsInte__PREGUN_De1_b.'&action=add<?php if(isset($_GET[\'token\'])){ echo "&token=".$_GET[\'token\']; }?>\');
                $("#editarDatos").modal(\'show\');
            }';
    			}else{
        			$botonSalvar2 = "\n".'

           
            if($("#oper").val() == \'add\'){
                if(before_save()){
                    $("#frameContenedor").attr(\'src\', \'http://<?php echo $_SERVER["HTTP_HOST"];?>/crm_php/new_index.php?formulario='.$key->GUIDET_ConsInte__GUION__Det_b.'&view=si&formaDetalle=si&formularioPadre='.$id_a_generar.'&yourfather=\'+ idTotal +\'&pincheCampo='.$key->GUIDET_ConsInte__PREGUN_De1_b.'<?php if(isset($_GET[\'token\'])){ echo "&token=".$_GET[\'token\']; }?>\');
                    $("#editarDatos").modal(\'show\');
                    $("#oper").val(\'edit\');
                }else{
                    before_save();
                    var d = new Date();
                    var h = d.getHours();
                    var horas = (h < 10) ? \'0\' + h : h;
                    var dia = d.getDate();
                    var dias = (dia < 10) ? \'0\' + dia : dia;
                    var fechaFinal = d.getFullYear() + \'-\' + meses[d.getMonth()] + \'-\' + dias + \' \'+ horas +\':\'+d.getMinutes()+\':\'+d.getSeconds();
                    $("#FechaFinal").val(fechaFinal);
                    
                    var form = $("#FormularioDatos");
                    //Se crean un array con los datos a enviar, apartir del formulario 
                    var formData = new FormData($("#FormularioDatos")[0]);
                    $.ajax({
                       url: \'<?=$url_crud;?>?insertarDatosGrilla=si&usuario=<?php echo getIdentificacionUser($token);?>&CodigoMiembro=<?php if(isset($_GET[\'user\'])) { echo $_GET["user"]; }else{ echo "0";  } ?><?php if(isset($_GET[\'id_gestion_cbx\'])){ echo "&id_gestion_cbx=".$_GET[\'id_gestion_cbx\']; }?><?php if(!empty($token)){ echo "&token=".$token; }?>\',  
                        type: \'POST\',
                        data: formData,
                        cache: false,
                        contentType: false,
                        processData: false,
                        //una vez finalizado correctamente
                        success: function(data){
                            if(data){
                                //Si realizo la operacion ,perguntamos cual es para posarnos sobre el nuevo registro
                                if($("#oper").val() == \'add\'){
                                    idTotal = data;
                                }else{
                                    idTotal= $("#hidId").val();
                                }
                                $("#hidId").val(idTotal);
                                $("#oper").val(\'edit\');

                                int_guardo = 1;
                                $(".llamadores").attr(\'padre\', idTotal);
                                $("#frameContenedor").attr(\'src\', \'http://<?php echo $_SERVER["HTTP_HOST"];?>/crm_php/new_index.php?formulario='.$key->GUIDET_ConsInte__GUION__Det_b.'&view=si&formaDetalle=si&formularioPadre='.$id_a_generar.'&yourfather=\'+ idTotal +\'&pincheCampo='.$key->GUIDET_ConsInte__PREGUN_De1_b.'&action=add<?php if(isset($_GET[\'token\'])){ echo "&token=".$_GET[\'token\']; }?>\');
                                $("#editarDatos").modal(\'show\');
                                $("#oper").val(\'edit\');

                            }else{
                                //Algo paso, hay un error
                                alertify.error(\'Un error ha ocurrido\');
                            }                
                        },
                        //si ha ocurrido un error
                        error: function(){
                            after_save_error();
                            alertify.error(\'Ocurrio un error relacionado con la red, al momento de guardar, intenta mas tarde\');
                        }
                    });
                }
            }else{

                $("#frameContenedor").attr(\'src\', \'http://<?php echo $_SERVER["HTTP_HOST"];?>/crm_php/new_index.php?formulario='.$key->GUIDET_ConsInte__GUION__Det_b.'&view=si&yourfather=\'+ padre +\'&formaDetalle=si&formularioPadre='.$id_a_generar.'&pincheCampo='.$key->GUIDET_ConsInte__PREGUN_De1_b.'&action=add<?php if(isset($_GET[\'token\'])){ echo "&token=".$_GET[\'token\']; }?>\');
                $("#editarDatos").modal(\'show\');
            }';
    			}//Este es el if de hay que validar


    			$contenidoMaestro .=  "\n".'
        <li class="'.$activo.'">
            <a href="#tab_'.$Kaka.'" data-toggle="tab" id="tabs_click_'.$Kaka.'">'.$key->GUIDET_Nombre____b.'</a>
        </li>';

        		$botonLlamado = '';
	            if($key->GUIDET_Modo______b != 1){
	                $botonLlamado = '<button title="Crear '.$key->GUIDET_Nombre____b.'" class="btn btn-primary btn-sm llamadores" padre="\'<?php if(isset($_GET[\'yourfather\'])){ echo $_GET[\'yourfather\']; }else{ echo "0"; }?>\' " id="btnLlamar_'.$Kaka.'"><i class="fa fa-plus"></i></button>';
	                $darlePadreAlHijo .= '$("#btnLlamar_'.$Kaka.'").attr(\'padre\', <?php echo $_GET[\'registroId\'];?>);';
	               	$darlePadreAlHijo_2 .= '$("#btnLlamar_'.$Kaka.'").attr(\'padre\', id);';
	               	$darlePadreAlHijo_3 .= '$("#btnLlamar_'.$Kaka.'").attr(\'padre\', <?php echo $_GET[\'user\'];?>);';
	            }//cierro el if de $key->GUIDET_Modo______b != 1

            	$tabscontenidoMaestro .= "\n".'
        <div class="tab-pane '.$activo.'" id="tab_'.$Kaka.'"> 
            <table class="table table-hover table-bordered" id="tablaDatosDetalless'.$Kaka.'" width="100%">
            </table>
            <div id="pagerDetalles'.$Kaka.'">
            </div> 
            '.$botonLlamado.'
        </div>';

        		$tabsFinal .= "\n".'
        $("#tabs_click_'.$Kaka.'").click(function(){ 
            $.jgrid.gridUnload(\'#tablaDatosDetalless'.$Kaka.'\'); 
            cargarHijos_'.$Kaka.'(idTotal);
        });

        $("#btnLlamar_'.$Kaka.'").click(function( event ) {
            event.preventDefault(); 
            var padre = $(this).attr(\'padre\');
            '.$botonSalvar2.'
        });';

        		$funcionCargarDatosDeLasPutasGrillas .= "\n".'
            $.jgrid.gridUnload(\'#tablaDatosDetalless'.$Kaka.'\'); 
            cargarHijos_'.$Kaka.'(id);';



            	$limpiadordeGrillas .= "\n".'
            $.jgrid.gridUnload(\'#tablaDatosDetalless'.$Kaka.'\');';

            	$LsqlDetalle = "SELECT PREGUN_Texto_____b as titulo_pregunta , PREGUN_Tipo______b as tipo_Pregunta, PREGUN_ConsInte__b as id , PREGUN_ConsInte__GUION__PRE_B as guion, PREGUN_ConsInte__OPCION_B as lista, PREGUN_OrdePreg__b , PREGUN_NumeMini__b as minimoNumero, PREGUN_NumeMaxi__b as maximoNumero, PREGUN_FechMini__b as fechaMinimo, PREGUN_FechMaxi__b as fechaMaximo , PREGUN_HoraMini__b as horaMini , PREGUN_HoraMaxi__b as horaMaximo, PREGUN_TexErrVal_b as error, PREGUN_OperEntreCamp_____b FROM ".$BaseDatos_systema.".PREGUN LEFT JOIN  ".$BaseDatos_systema.".SECCIO ON SECCIO_ConsInte__b = PREGUN_ConsInte__SECCIO_b  WHERE PREGUN_ConsInte__GUION__b = ".$key->GUIDET_ConsInte__GUION__Det_b." ORDER BY SECCIO_Orden_____b ASC, PREGUN_OrdePreg__b ASC";

            	$LsqlDetalle2 = "SELECT PREGUN_Texto_____b as titulo_pregunta , PREGUN_Tipo______b as tipo_Pregunta, PREGUN_ConsInte__b as id , PREGUN_ConsInte__GUION__PRE_B as guion, PREGUN_ConsInte__OPCION_B as lista, PREGUN_OrdePreg__b, PREGUN_OperEntreCamp_____b FROM ".$BaseDatos_systema.".PREGUN  WHERE PREGUN_ConsInte__GUION__b = ".$key->GUIDET_ConsInte__GUION__Det_b." ORDER BY PREGUN_OrdePreg__b LIMIT 0,2";

                          
            	$functionDescargar .= "\n".'
            $.jgrid.gridUnload(\'#tablaDatosDetalless'.$Kaka.'\'); //funcion descargar ';
            	$functionRecargar .= "\n".'
            $.jgrid.gridUnload(\'#tablaDatosDetalless'.$Kaka.'\'); //funcion Recargar 
            cargarHijos_'.$Kaka.'(id);';

	            $campos = $mysqli->query($LsqlDetalle);
	            $i = 0;
	            $titulos ='';
	            $orden = '';

	           // echo $LsqlDetalle;
	            while ($key3 = $campos->fetch_object()){
	                if($key3->tipo_Pregunta != '9' && $key3->tipo_Pregunta != '12'){
	                    if($i==0){
	                        $titulos = '\''.($key3->titulo_pregunta).'\'';
	                        $orden = 'G'.$key->GUIDET_ConsInte__GUION__Det_b.'_C'.$key3->id;
	                    }else{
	                        $titulos .= ',\''.($key3->titulo_pregunta).'\'';
	                    }//if de $i ==0
	                    $i++;
	                }// if $key3->tipo_Pregunta != '9'
	                
	            }//while key3 = $campos->fetch_object()

            	$camposDelgguardadodeLagrilla = '
        if(isset($_POST["oper"])){
            $Lsql  = \'\';

            $validar = 0;
            $LsqlU = "UPDATE ".$BaseDatos.".G'.$key->GUIDET_ConsInte__GUION__Det_b.' SET "; 
            $LsqlI = "INSERT INTO ".$BaseDatos.".G'.$key->GUIDET_ConsInte__GUION__Det_b.'(";
            $LsqlV = " VALUES ("; '."\n";

            	$subgrilla .= "\n".'
                var subgrid_table_id_'.$Kaka.', pager_id_'.$Kaka.'; 

                subgrid_table_id_'.$Kaka.' = subgrid_id+"_t_'.$Kaka.'"; 

                pager_id_ = "p_"+subgrid_table_id_'.$Kaka.'; 

                $("#"+subgrid_id).append("<table id=\'"+subgrid_table_id_'.$Kaka.'+"\' class=\'scroll\'></table><div id=\'"+pager_id_'.$Kaka.'+"\' class=\'scroll\'></div>"); 

                jQuery("#"+subgrid_table_id_'.$Kaka.').jqGrid({ 
                    url:\'<?=$url_crud;?>?callDatosSubgrilla_'.$Kaka.'=si&id=\'+row_id,
                    datatype: \'xml\',
                    mtype: \'POST\',
                    colNames:[\'id\','.$titulos.', \'padre\'],
                    colModel: [ 
                        {    
                            name:\'providerUserId\',
                            index:\'providerUserId\', 
                            width:100,editable:true, 
                            editrules:{
                                required:false, 
                                edithidden:true
                            },
                            hidden:true, 
                            editoptions:{ 
                                dataInit: function(element) {                     
                                    $(element).attr("readonly", "readonly"); 
                                } 
                            }
                        }';
            	$functionsBeforeSelect = '';
            	$funcionCargar .= "\n".'
    function cargarHijos_'.$Kaka.'(id_'.$Kaka.'){
        $.jgrid.defaults.width = \'1225\';
        $.jgrid.defaults.height = \'650\';
        $.jgrid.defaults.responsive = true;
        $.jgrid.defaults.styleUI = \'Bootstrap\';
        var lastSels;
        $("#tablaDatosDetalless'.$Kaka.'").jqGrid({
            url:\'<?=$url_crud;?>?callDatosSubgrilla_'.$Kaka.'=si&id=\'+id_'.$Kaka.',
            datatype: \'xml\',
            mtype: \'POST\',
            xmlReader: { 
                root:"rows", 
                row:"row",
                cell:"cell",
                id : "[asin]"
            },
            colNames:[\'id\','.$titulos.', \'padre\'],
            colModel:[

                {
                    name:\'providerUserId\',
                    index:\'providerUserId\', 
                    width:100,editable:true, 
                    editrules:{
                        required:false, 
                        edithidden:true
                    },
                    hidden:true, 
                    editoptions:{ 
                        dataInit: function(element) {                     
                            $(element).attr("readonly", "readonly"); 
                        } 
                    }
                }';

                $campos_2 = $mysqli->query($LsqlDetalle);
                $camposAbuscar = 'SELECT G'.$key->GUIDET_ConsInte__GUION__Det_b.'_ConsInte__b';
                $joins2 = '';
                $otraTabla = '';
                $Kakaroto =0;

                while ($key2 = $campos_2->fetch_object()){
					switch ($key2->tipo_Pregunta) {
                        case '1':
							$funcionCargar .= "\n".'
                ,
                { 
                    name:\'G'.$key->GUIDET_ConsInte__GUION__Det_b.'_C'.$key2->id.'\', 
                    index: \'G'.$key->GUIDET_ConsInte__GUION__Det_b.'_C'.$key2->id.'\', 
                    width:160, 
                    resizable:false, 
                    sortable:true , 
                    editable: true 
                }';

							$camposSubgrilla .= "\n".'
                        ,
                        { 
                            name:\'G'.$key->GUIDET_ConsInte__GUION__Det_b.'_C'.$key2->id.'\', 
                            index: \'G'.$key->GUIDET_ConsInte__GUION__Det_b.'_C'.$key2->id.'\', 
                            width:160, 
                            resizable:false, 
                            sortable:true , 
                            editable: true 
                        }';

							$camposAbuscar .= ', G'.$key->GUIDET_ConsInte__GUION__Det_b.'_C'.$key2->id;
							$camposXmlParallenar .= "\n".'
            echo "<cell>". ($fila->G'.$key->GUIDET_ConsInte__GUION__Det_b.'_C'.$key2->id.')."</cell>";';  
                            if($key->GUIDET_ConsInte__PREGUN_De1_b != $key2->id){
                                $camposDelgguardadodeLagrilla .= ' 
                                                                         
            if(isset($_POST["G'.$key->GUIDET_ConsInte__GUION__Det_b.'_C'.$key2->id.'"])){
                $separador = "";
                if($validar == 1){
                    $separador = ",";
                }

                $LsqlU .= $separador."G'.$key->GUIDET_ConsInte__GUION__Det_b.'_C'.$key2->id.' = \'".$_POST["G'.$key->GUIDET_ConsInte__GUION__Det_b.'_C'.$key2->id.'"]."\'";
                $LsqlI .= $separador."G'.$key->GUIDET_ConsInte__GUION__Det_b.'_C'.$key2->id.'";
                $LsqlV .= $separador."\'".$_POST["G'.$key->GUIDET_ConsInte__GUION__Det_b.'_C'.$key2->id.'"]."\'";
                $validar = 1;
            }
                                                                          
                                                                           '."\n";    
                            }//cierro el if $key->GUIDET_ConsInte__PREGUN_De1_b != $key2->id               

						break;

	                    case '2':
                            $funcionCargar .="\n". '
                ,
                {
                    name:\'G'.$key->GUIDET_ConsInte__GUION__Det_b.'_C'.$key2->id.'\', 
                    index:\'G'.$key->GUIDET_ConsInte__GUION__Det_b.'_C'.$key2->id.'\', 
                    width:150, 
                    editable: true 
                }';

                          	$camposSubgrilla .="\n". '
                        ,
                        { 
                            name:\'G'.$key->GUIDET_ConsInte__GUION__Det_b.'_C'.$key2->id.'\', 
                            index:\'G'.$key->GUIDET_ConsInte__GUION__Det_b.'_C'.$key2->id.'\', 
                            width:150, 
                            editable: true 
                        }';
							$camposAbuscar .= ', G'.$key->GUIDET_ConsInte__GUION__Det_b.'_C'.$key2->id;
                            $camposXmlParallenar .=  "\n".'
            echo "<cell><![CDATA[". ($fila->G'.$key->GUIDET_ConsInte__GUION__Det_b.'_C'.$key2->id.')."]]></cell>";';  
                            
                            if($key->GUIDET_ConsInte__PREGUN_De1_b != $key2->id){
                                $camposDelgguardadodeLagrilla .= '  

            if(isset($_POST["G'.$key->GUIDET_ConsInte__GUION__Det_b.'_C'.$key2->id.'"])){
                $separador = "";
                if($validar == 1){
                    $separador = ",";
                }

                $LsqlU .= $separador."G'.$key->GUIDET_ConsInte__GUION__Det_b.'_C'.$key2->id.' = \'".$_POST["G'.$key->GUIDET_ConsInte__GUION__Det_b.'_C'.$key2->id.'"]."\'";
                $LsqlI .= $separador."G'.$key->GUIDET_ConsInte__GUION__Det_b.'_C'.$key2->id.'";
                $LsqlV .= $separador."\'".$_POST["G'.$key->GUIDET_ConsInte__GUION__Det_b.'_C'.$key2->id.'"]."\'";
                $validar = 1;
            }
                                                                           '."\n";  
                            }//cierro el if de $key->GUIDET_ConsInte__PREGUN_De1_b != $key2->id
						break;

						case '3':
							$funcionCargar .="\n".' 
                ,
                {  
                    name:\'G'.$key->GUIDET_ConsInte__GUION__Det_b.'_C'.$key2->id.'\', 
                    index:\'G'.$key->GUIDET_ConsInte__GUION__Det_b.'_C'.$key2->id.'\', 
                    width:80 ,
                    editable: true, 
                    searchoptions: {
                        sopt: [\'eq\', \'ne\', \'lt\', \'le\', \'gt\', \'ge\']
                    }, 
                    editoptions:{
                        size:20,';
                        

                            //

                        	 $LsqlCadena = "SELECT PREGUN_ConsInte__b, PREGUN_OperEntreCamp_____b FROM ".$BaseDatos_systema.".PREGUN WHERE PREGUN_ConsInte__GUION__b = ".$key->GUIDET_ConsInte__GUION__Det_b."  AND (PREGUN_Tipo______b = 3 OR PREGUN_Tipo______b = 4) AND PREGUN_OperEntreCamp_____b IS NOT NULL "; 
    				$cur_result = $mysqli->query($LsqlCadena);
    				$itsCadena = false;
			        while ($key22 = $cur_result->fetch_object()) {	
			        	/* ahora toca buscar el valor de esos campos en la jugada */
			            $dato = str_replace(' ', '_', ($key2->titulo_pregunta));

			        	$buscar = '${'.substr(sanear_stringsXYZ($dato), 0, 20).'}';

			        
			        	//$pos = strpos($key->PREGUN_OperEntreCamp_____b, $buscar);
			        	
			        	
			        	if (stristr(trim($key22->PREGUN_OperEntreCamp_____b), $buscar) !== false) {
						   	$itsCadena = true;
						   	//echo "Es esto => ".$buscar." Aqui => ".$key->PREGUN_OperEntreCamp_____b;
						   	/* Toca hacer todo el frito */
						   	$funcionCargar .= "\n".'
			        		dataInit:function(el){
    							$(el).numeric();
    						},
    						dataEvents: [
	                            {  
	                                type: \'change\',
	                                fn: function(e) {
	                                    var r = this.id;
	                                    var rId = r.split(\'_\');';
		                            	
	                                

						   	$LsqlCadenaX = "SELECT PREGUN_Texto_____b, PREGUN_ConsInte__b, PREGUN_OperEntreCamp_____b FROM ".$BaseDatos_systema.".PREGUN WHERE PREGUN_ConsInte__GUION__b = ".$key->GUIDET_ConsInte__GUION__Det_b."  AND (PREGUN_Tipo______b = 3 OR PREGUN_Tipo______b = 4)"; 
							$cadenaFinalX = trim(str_replace('=', '', $key22->PREGUN_OperEntreCamp_____b));

	        				$cur_resultX = $mysqli->query($LsqlCadenaX);

					        while ($keyX = $cur_resultX->fetch_object()) {

					        	/* ahora toca buscar el valor de esos campos en la jugada */
					            $datoX = str_replace(' ', '_', ($keyX->PREGUN_Texto_____b));

					        	$buscarX = '${'.substr(sanear_stringsXYZ($datoX), 0, 20).'}';

					        	$reemplazo = 'Number($("#"+rId[0]+"_G'.$key->GUIDET_ConsInte__GUION__Det_b.'_C'.$keyX->PREGUN_ConsInte__b.'").val())';

					        	$cadenaFinalX = str_replace($buscarX, $reemplazo , $cadenaFinalX);
					        }

				       		$funcionCargar .= "\n".'

    	var totales = '.$cadenaFinalX.';
    	$("#"+rId[0]+"_G'.$key->GUIDET_ConsInte__GUION__Det_b.'_C'.$key22->PREGUN_ConsInte__b.'").val(totales);';

							$funcionCargar .= "\n".'
    								}
	                            }
	                        ]';
						}
			        }

			        if($itsCadena == false){
			        	/* No esta metido en ningun lado */
			        	$funcionCargar .= "\n".'
			        		dataInit:function(el){
    							$(el).numeric();
    						}';
			        }

                        	$funcionCargar .= '
                    }

                }';

							$camposSubgrilla .="\n".' 
                        ,
                        {  
                            name:\'G'.$key->GUIDET_ConsInte__GUION__Det_b.'_C'.$key2->id.'\', 
                            index:\'G'.$key->GUIDET_ConsInte__GUION__Det_b.'_C'.$key2->id.'\', 
                            width:80 ,
                            editable: true, 
                            searchoptions: {
                                sopt: [\'eq\', \'ne\', \'lt\', \'le\', \'gt\', \'ge\']
                            }, 
                            editoptions:{
                                size:20,
                                dataInit:function(el){
                                    $(el).numeric();
                                }
                            }

                        }';

							$camposAbuscar .= ', G'.$key->GUIDET_ConsInte__GUION__Det_b.'_C'.$key2->id;	        
							$camposXmlParallenar .= "\n".'
            echo "<cell>". $fila->G'.$key->GUIDET_ConsInte__GUION__Det_b.'_C'.$key2->id.'."</cell>"; ';
							if($key->GUIDET_ConsInte__PREGUN_De1_b != $key2->id){
								$camposDelgguardadodeLagrilla .= ' 
            $G'.$key->GUIDET_ConsInte__GUION__Det_b.'_C'.$key2->id .'= NULL;
            //este es de tipo numero no se deja ir asi \'\', si est avacio lo mejor es no mandarlo
            if(isset($_POST["G'.$key->GUIDET_ConsInte__GUION__Det_b.'_C'.$key2->id.'"])){    
                if($_POST["G'.$key->GUIDET_ConsInte__GUION__Det_b.'_C'.$key2->id.'"] != \'\'){
                    $separador = "";
                    if($validar == 1){
                        $separador = ",";
                    }

                    $G'.$key->GUIDET_ConsInte__GUION__Det_b.'_C'.$key2->id.' = $_POST["G'.$key->GUIDET_ConsInte__GUION__Det_b.'_C'.$key2->id.'"];
                    $LsqlU .= $separador." G'.$key->GUIDET_ConsInte__GUION__Det_b.'_C'.$key2->id.' = \'".$G'.$key->GUIDET_ConsInte__GUION__Det_b.'_C'.$key2->id.'."\'";
                    $LsqlI .= $separador." G'.$key->GUIDET_ConsInte__GUION__Det_b.'_C'.$key2->id.'";
                    $LsqlV .= $separador."\'".$G'.$key->GUIDET_ConsInte__GUION__Det_b.'_C'.$key2->id.'."\'";
                    $validar = 1;
                }
            }'."\n";
							}//cierro el if $key->GUIDET_ConsInte__PREGUN_De1_b != $key2->id
                      	break;

                      	case '4':
							$funcionCargar .="\n".'
                ,
                {  
                    name:\'G'.$key->GUIDET_ConsInte__GUION__Det_b.'_C'.$key2->id.'\', 
                    index:\'G'.$key->GUIDET_ConsInte__GUION__Det_b.'_C'.$key2->id.'\', 
                    width:80 ,
                    editable: true, 
                    searchoptions: {
                        sopt: [\'eq\', \'ne\', \'lt\', \'le\', \'gt\', \'ge\']
                    }, 
                    editoptions:{
                        size:20,';

                        $LsqlCadena = "SELECT PREGUN_ConsInte__b, PREGUN_OperEntreCamp_____b FROM ".$BaseDatos_systema.".PREGUN WHERE PREGUN_ConsInte__GUION__b = ".$key->GUIDET_ConsInte__GUION__Det_b."  AND (PREGUN_Tipo______b = 3 OR PREGUN_Tipo______b = 4) AND PREGUN_OperEntreCamp_____b IS NOT NULL "; 
    				$cur_result = $mysqli->query($LsqlCadena);
    				$itsCadena = false;
			        while ($key22 = $cur_result->fetch_object()) {	
			        	/* ahora toca buscar el valor de esos campos en la jugada */
			            $dato = str_replace(' ', '_', ($key2->titulo_pregunta));

			        	$buscar = '${'.substr(sanear_stringsXYZ($dato), 0, 20).'}';

			        
			        	//$pos = strpos($key->PREGUN_OperEntreCamp_____b, $buscar);
			        	
			        	
			        	if (stristr(trim($key22->PREGUN_OperEntreCamp_____b), $buscar) !== false) {
						   	$itsCadena = true;
						   	//echo "Es esto => ".$buscar." Aqui => ".$key->PREGUN_OperEntreCamp_____b;
						   	/* Toca hacer todo el frito */
						   	$funcionCargar .= "\n".'
			        		dataInit:function(el){
    							$(el).numeric();
    						},
    						dataEvents: [
	                            {  
	                                type: \'change\',
	                                fn: function(e) {
	                                    var r = this.id;
	                                    var rId = r.split(\'_\');';
		                            	
	                                

						   	$LsqlCadenaX = "SELECT PREGUN_Texto_____b, PREGUN_ConsInte__b, PREGUN_OperEntreCamp_____b FROM ".$BaseDatos_systema.".PREGUN WHERE PREGUN_ConsInte__GUION__b = ".$key->GUIDET_ConsInte__GUION__Det_b."  AND (PREGUN_Tipo______b = 3 OR PREGUN_Tipo______b = 4)"; 
							$cadenaFinalX = trim(str_replace('=', '', $key22->PREGUN_OperEntreCamp_____b));

	        				$cur_resultX = $mysqli->query($LsqlCadenaX);

					        while ($keyX = $cur_resultX->fetch_object()) {

					        	/* ahora toca buscar el valor de esos campos en la jugada */
					            $datoX = str_replace(' ', '_', ($keyX->PREGUN_Texto_____b));

					        	$buscarX = '${'.substr(sanear_stringsXYZ($datoX), 0, 20).'}';

					        	$reemplazo = 'Number($("#"+rId[0]+"_G'.$key->GUIDET_ConsInte__GUION__Det_b.'_C'.$keyX->PREGUN_ConsInte__b.'").val())';

					        	$cadenaFinalX = str_replace($buscarX, $reemplazo , $cadenaFinalX);
					        }

				       		$funcionCargar .= "\n".'
		var totales = '.$cadenaFinalX.';
    	$("#"+rId[0]+"_G'.$key->GUIDET_ConsInte__GUION__Det_b.'_C'.$key22->PREGUN_ConsInte__b.'").val(totales);';

							$funcionCargar .= "\n".'
    								}
	                            }
	                        ]';
						}
			        }

			        if($itsCadena == false){
			        	/* No esta metido en ningun lado */
			        	$funcionCargar .= "\n".'
			        		dataInit:function(el){
    							$(el).numeric();
    						}';
			        }

                        	$funcionCargar .= '
                    }

                }';

							$camposSubgrilla .="\n".'
                        ,
                        { 
                            name:\'G'.$key->GUIDET_ConsInte__GUION__Det_b.'_C'.$key2->id.'\', 
                            index:\'G'.$key->GUIDET_ConsInte__GUION__Det_b.'_C'.$key2->id.'\', 
                            width:80 ,
                            editable: true, 
                            searchoptions: {
                                sopt: [\'eq\', \'ne\', \'lt\', \'le\', \'gt\', \'ge\']
                            }, 
                            editoptions:{
                                size:20,
                                dataInit:function(el){
                                    $(el).numeric({ decimal : ".",  negative : false, scale: 4 });
                                }
                            } 
                        }';

							$camposAbuscar .= ', G'.$key->GUIDET_ConsInte__GUION__Det_b.'_C'.$key2->id;
                            $camposXmlParallenar .= "\n".'
            echo "<cell>". $fila->G'.$key->GUIDET_ConsInte__GUION__Det_b.'_C'.$key2->id.'."</cell>"; ';
                        	
                        	if($key->GUIDET_ConsInte__PREGUN_De1_b != $key2->id){
                                $camposDelgguardadodeLagrilla .= '  
            $G'.$key->GUIDET_ConsInte__GUION__Det_b.'_C'.$key2->id.' = NULL;
            //este es de tipo numero no se deja ir asi \'\', si est avacio lo mejor es no mandarlo
            if(isset($_POST["G'.$key->GUIDET_ConsInte__GUION__Det_b.'_C'.$key2->id.'"])){    
                if($_POST["G'.$key->GUIDET_ConsInte__GUION__Det_b.'_C'.$key2->id.'"] != \'\'){
                    $separador = "";
                    if($validar == 1){
                        $separador = ",";
                    }

                    $G'.$key->GUIDET_ConsInte__GUION__Det_b.'_C'.$key2->id.' = $_POST["G'.$key->GUIDET_ConsInte__GUION__Det_b.'_C'.$key2->id.'"];
                    $LsqlU .= $separador." G'.$key->GUIDET_ConsInte__GUION__Det_b.'_C'.$key2->id.' = \'".$G'.$key->GUIDET_ConsInte__GUION__Det_b.'_C'.$key2->id.'."\'";
                    $LsqlI .= $separador." G'.$key->GUIDET_ConsInte__GUION__Det_b.'_C'.$key2->id.'";
                    $LsqlV .= $separador."\'".$G'.$key->GUIDET_ConsInte__GUION__Det_b.'_C'.$key2->id.'."\'";
                    $validar = 1;
                }
            }'."\n";
                        	
                        	}//cierro el if de $key->GUIDET_ConsInte__PREGUN_De1_b != $key2->id
                        break;

                        case '5':
                            $funcionCargar .="\n".'
                ,
                {  
                    name:\'G'.$key->GUIDET_ConsInte__GUION__Det_b.'_C'.$key2->id.'\', 
                    index:\'G'.$key->GUIDET_ConsInte__GUION__Det_b.'_C'.$key2->id.'\', 
                    width:120 ,
                    editable: true ,
                    formatter: \'text\', 
                    searchoptions: {
                        sopt: [\'eq\', \'ne\', \'lt\', \'le\', \'gt\', \'ge\']
                    }, 
                    editoptions:{
                        size:20,
                        dataInit:function(el){
                            $(el).datepicker({
                                language: "es",
                                autoclose: true,
                                todayHighlight: true
                            });
                        },
                        defaultValue: function(){
                            var currentTime = new Date();
                            var month = parseInt(currentTime.getMonth() + 1);
                            month = month <= 9 ? "0"+month : month;
                            var day = currentTime.getDate();
                            day = day <= 9 ? "0"+day : day;
                            var year = currentTime.getFullYear();
                            return year+"-"+month + "-"+day;
                        }
                    }
                }';
                            $camposSubgrilla .="\n".'
                        ,
                        {  
                            name:\'G'.$key->GUIDET_ConsInte__GUION__Det_b.'_C'.$key2->id.'\', 
                            index:\'G'.$key->GUIDET_ConsInte__GUION__Det_b.'_C'.$key2->id.'\', 
                            width:120 ,
                            editable: true ,
                            formatter: \'text\', 
                            searchoptions: {
                                sopt: [\'eq\', \'ne\', \'lt\', \'le\', \'gt\', \'ge\']
                            }, 
                            editoptions:{
                                size:20,
                                dataInit:function(el){
                                    $(el).datepicker({
                                        language: "es",
                                        autoclose: true,
                                        todayHighlight: true
                                    });
                                },
                                defaultValue: function(){
                                    var currentTime = new Date();
                                    var month = parseInt(currentTime.getMonth() + 1);
                                    month = month <= 9 ? "0"+month : month;
                                    var day = currentTime.getDate();
                                    day = day <= 9 ? "0"+day : day;
                                    var year = currentTime.getFullYear();
                                    return year+"-"+month + "-"+day;
                                }
                            }
                        }';

                            $camposAbuscar .= ', G'.$key->GUIDET_ConsInte__GUION__Det_b.'_C'.$key2->id;
                            
                            $camposXmlParallenar .= "\n".'
            if($fila->G'.$key->GUIDET_ConsInte__GUION__Det_b.'_C'.$key2->id.' != \'\'){
                echo "<cell>". explode(\' \', $fila->G'.$key->GUIDET_ConsInte__GUION__Det_b.'_C'.$key2->id.')[0]."</cell>";
            }else{
                echo "<cell></cell>";
            }'; 
                            if($key->GUIDET_ConsInte__PREGUN_De1_b != $key2->id){                        
                                $camposDelgguardadodeLagrilla .= '
            $G'.$key->GUIDET_ConsInte__GUION__Det_b.'_C'.$key2->id.' = NULL;
            //este es de tipo date hay que preguntar si esta vacia o no
            if(isset($_POST["G'.$key->GUIDET_ConsInte__GUION__Det_b.'_C'.$key2->id.'"])){    
                if($_POST["G'.$key->GUIDET_ConsInte__GUION__Det_b.'_C'.$key2->id.'"] != \'\'){
                    $separador = "";
                    if($validar == 1){
                        $separador = ",";
                    }

                    $G'.$key->GUIDET_ConsInte__GUION__Det_b.'_C'.$key2->id.' = "\'".str_replace(\' \', \'\',$_POST["G'.$key->GUIDET_ConsInte__GUION__Det_b.'_C'.$key2->id.'"])." 00:00:00\'";
                    $LsqlU .= $separador." G'.$key->GUIDET_ConsInte__GUION__Det_b.'_C'.$key2->id.' = ".$G'.$key->GUIDET_ConsInte__GUION__Det_b.'_C'.$key2->id.';
                    $LsqlI .= $separador." G'.$key->GUIDET_ConsInte__GUION__Det_b.'_C'.$key2->id.'";
                    $LsqlV .= $separador.$G'.$key->GUIDET_ConsInte__GUION__Det_b.'_C'.$key2->id.';
                    $validar = 1;
                }
            }'."\n";
                            }//$key->GUIDET_ConsInte__PREGUN_De1_b != $key2->id
                        break;
                                                        
                        case '10':
                            
                            $funcionCargar .="\n".'
                ,
                {  
                    name:\'G'.$key->GUIDET_ConsInte__GUION__Det_b.'_C'.$key2->id.'\', 
                    index:\'G'.$key->GUIDET_ConsInte__GUION__Det_b.'_C'.$key2->id.'\', 
                    width:70 ,
                    editable: true ,
                    formatter: \'text\', 
                    editoptions:{
                        size:20,
                        dataInit:function(el){
                            //Timepicker
                            var options = {  //hh:mm 24 hour format only, defaults to current time
                                timeFormat: \'HH:mm:ss\',
                                interval: 5,
                                minTime: \'10\',
                                dynamic: false,
                                dropdown: true,
                                scrollbar: true
                            }; 
                            $(el).timepicker(options);
                            $(".timepicker").css("z-index", 99999 );
                        }
                    }
                }';

                            $camposSubgrilla .="\n".'
                        ,
                        {  
                            name:\'G'.$key->GUIDET_ConsInte__GUION__Det_b.'_C'.$key2->id.'\', 
                            index:\'G'.$key->GUIDET_ConsInte__GUION__Det_b.'_C'.$key2->id.'\', 
                            width:70 ,
                            editable: true ,
                            formatter: \'text\', 
                            editoptions:{
                                size:20,
                                dataInit:function(el){
                                    //Timepicker
                                     var options = {  //hh:mm 24 hour format only, defaults to current time
                                        timeFormat: \'HH:mm:ss\',
                                        interval: 5,
                                        minTime: \'10\',
                                        dynamic: false,
                                        dropdown: true,
                                        scrollbar: true
                                    }; 
                                    $(el).timepicker(options);
                    

                                }
                            }
                        }';

                            $camposAbuscar .= ', G'.$key->GUIDET_ConsInte__GUION__Det_b.'_C'.$key2->id;
                            
                            $camposXmlParallenar .= "\n".'
            if($fila->G'.$key->GUIDET_ConsInte__GUION__Det_b.'_C'.$key2->id.' != \'\'){
                echo "<cell>". explode(\' \', $fila->G'.$key->GUIDET_ConsInte__GUION__Det_b.'_C'.$key2->id.')[1]."</cell>";
            }else{
                echo "<cell></cell>";
            }'; 
                            if($key->GUIDET_ConsInte__PREGUN_De1_b != $key2->id){                            
                                $camposDelgguardadodeLagrilla .= ' 
            $G'.$key->GUIDET_ConsInte__GUION__Det_b.'_C'.$key2->id.' = NULL;
            //este es de tipo date hay que preguntar si esta vacia o no o esta undefined
            if(isset($_POST["G'.$key->GUIDET_ConsInte__GUION__Det_b.'_C'.$key2->id.'"])){    
                if($_POST["G'.$key->GUIDET_ConsInte__GUION__Det_b.'_C'.$key2->id.'"] != \'\' && $_POST["G'.$key->GUIDET_ConsInte__GUION__Det_b.'_C'.$key2->id.'"] != \'undefined\' && $_POST["G'.$key->GUIDET_ConsInte__GUION__Det_b.'_C'.$key2->id.'"] != \'null\'){
                    $separador = "";
                    $fecha = date(\'Y-m-d\');
                    if($validar == 1){
                        $separador = ",";
                    }

                    $G'.$key->GUIDET_ConsInte__GUION__Det_b.'_C'.$key2->id.' = "\'".$fecha." ".str_replace(\' \', \'\',$_POST["G'.$key->GUIDET_ConsInte__GUION__Det_b.'_C'.$key2->id.'"])."\'";
                    $LsqlU .= $separador."  G'.$key->GUIDET_ConsInte__GUION__Det_b.'_C'.$key2->id.' = ".$G'.$key->GUIDET_ConsInte__GUION__Det_b.'_C'.$key2->id.'."";
                    $LsqlI .= $separador."  G'.$key->GUIDET_ConsInte__GUION__Det_b.'_C'.$key2->id.'";
                    $LsqlV .= $separador.$G'.$key->GUIDET_ConsInte__GUION__Det_b.'_C'.$key2->id.';
                    $validar = 1;
                }
            }'."\n";  
                            }//cierro el If de $key->GUIDET_ConsInte__PREGUN_De1_b != $key2->id
                        break;

                        case '6':
                            $funcionCargar .="\n".'
                ,
                {  
                    name:\'G'.$key->GUIDET_ConsInte__GUION__Det_b.'_C'.$key2->id.'\', 
                    index:\'G'.$key->GUIDET_ConsInte__GUION__Det_b.'_C'.$key2->id.'\', 
                    width:120 ,
                    editable: true, 
                    edittype:"select" , 
                    editoptions: {
                        dataUrl: \'<?=$url_crud;?>?CallDatosLisop_=si&idLista='.$key2->lista.'&campo=G'.$key->GUIDET_ConsInte__GUION__Det_b.'_C'.$key2->id.'\'
                    }
                }';

                            $camposSubgrilla .="\n".'
                        ,
                        {  
                            name:\'G'.$key->GUIDET_ConsInte__GUION__Det_b.'_C'.$key2->id.'\', 
                            index:\'G'.$key->GUIDET_ConsInte__GUION__Det_b.'_C'.$key2->id.'\', 
                            width:120 ,
                            editable: true, 
                            edittype:"select" , 
                            editoptions: {
                                dataUrl: \'<?=$url_crud;?>?CallDatosLisop_=si&idLista='.$key2->lista.'&campo=G'.$key->GUIDET_ConsInte__GUION__Det_b.'_C'.$key2->id.'\'
                            }
                        }';
                            $camposAbuscar .= ', '.$alfabeto[$Kakaroto].'.LISOPC_Nombre____b as  G'.$key->GUIDET_ConsInte__GUION__Det_b.'_C'.$key2->id;

                            $joins2 .= ' LEFT JOIN ".$BaseDatos_systema.".LISOPC as '.$alfabeto[$Kakaroto].' ON '.$alfabeto[$Kakaroto].'.LISOPC_ConsInte__b =  G'.$key->GUIDET_ConsInte__GUION__Det_b.'_C'.$key2->id;

                            $camposXmlParallenar .= "\n".'
            echo "<cell>". ($fila->G'.$key->GUIDET_ConsInte__GUION__Det_b.'_C'.$key2->id.')."</cell>";';  
                            if($key->GUIDET_ConsInte__PREGUN_De1_b != $key2->id){
                                $camposDelgguardadodeLagrilla .= ' 
            if(isset($_POST["G'.$key->GUIDET_ConsInte__GUION__Det_b.'_C'.$key2->id.'"])){
                $separador = "";
                if($validar == 1){
                    $separador = ",";
                }

                $LsqlU .= $separador."G'.$key->GUIDET_ConsInte__GUION__Det_b.'_C'.$key2->id.' = \'".$_POST["G'.$key->GUIDET_ConsInte__GUION__Det_b.'_C'.$key2->id.'"]."\'";
                $LsqlI .= $separador."G'.$key->GUIDET_ConsInte__GUION__Det_b.'_C'.$key2->id.'";
                $LsqlV .= $separador."\'".$_POST["G'.$key->GUIDET_ConsInte__GUION__Det_b.'_C'.$key2->id.'"]."\'";
                $validar = 1;
            }'."\n";  
                            }//cierro el if de $key->GUIDET_ConsInte__PREGUN_De1_b != $key2->id
                        break;



                        case '13':
                            $funcionCargar .="\n".'
                ,
                {  
                    name:\'G'.$key->GUIDET_ConsInte__GUION__Det_b.'_C'.$key2->id.'\', 
                    index:\'G'.$key->GUIDET_ConsInte__GUION__Det_b.'_C'.$key2->id.'\', 
                    width:120 ,
                    editable: true, 
                    edittype:"select" , 
                    editoptions: {
                        dataUrl: \'<?=$url_crud;?>?CallDatosLisop_=si&idLista='.$key2->lista.'&campo=G'.$key->GUIDET_ConsInte__GUION__Det_b.'_C'.$key2->id.'\'
                    }
                }';

                            $camposSubgrilla .="\n".'
                        ,
                        {  
                            name:\'G'.$key->GUIDET_ConsInte__GUION__Det_b.'_C'.$key2->id.'\', 
                            index:\'G'.$key->GUIDET_ConsInte__GUION__Det_b.'_C'.$key2->id.'\', 
                            width:120 ,
                            editable: true, 
                            edittype:"select" , 
                            editoptions: {
                                dataUrl: \'<?=$url_crud;?>?CallDatosLisop_=si&idLista='.$key2->lista.'&campo=G'.$key->GUIDET_ConsInte__GUION__Det_b.'_C'.$key2->id.'\'
                            }
                        }';
                            $camposAbuscar .= ', '.$alfabeto[$Kakaroto].'.LISOPC_Nombre____b as  G'.$key->GUIDET_ConsInte__GUION__Det_b.'_C'.$key2->id;

                            $joins2 .= ' LEFT JOIN ".$BaseDatos_systema.".LISOPC as '.$alfabeto[$Kakaroto].' ON '.$alfabeto[$Kakaroto].'.LISOPC_ConsInte__b =  G'.$key->GUIDET_ConsInte__GUION__Det_b.'_C'.$key2->id;

                            $camposXmlParallenar .= "\n".'
            echo "<cell>". ($fila->G'.$key->GUIDET_ConsInte__GUION__Det_b.'_C'.$key2->id.')."</cell>";';  
                            if($key->GUIDET_ConsInte__PREGUN_De1_b != $key2->id){
                                $camposDelgguardadodeLagrilla .= ' 
            if(isset($_POST["G'.$key->GUIDET_ConsInte__GUION__Det_b.'_C'.$key2->id.'"])){
                $separador = "";
                if($validar == 1){
                    $separador = ",";
                }

                $LsqlU .= $separador."G'.$key->GUIDET_ConsInte__GUION__Det_b.'_C'.$key2->id.' = \'".$_POST["G'.$key->GUIDET_ConsInte__GUION__Det_b.'_C'.$key2->id.'"]."\'";
                $LsqlI .= $separador."G'.$key->GUIDET_ConsInte__GUION__Det_b.'_C'.$key2->id.'";
                $LsqlV .= $separador."\'".$_POST["G'.$key->GUIDET_ConsInte__GUION__Det_b.'_C'.$key2->id.'"]."\'";
                $validar = 1;
            }'."\n";  
                            }//cierro el if de $key->GUIDET_ConsInte__PREGUN_De1_b != $key2->id
                        break;

                        case '14':
                            $funcionCargar .="\n".'
                ,
                {  
                    name:\'G'.$key->GUIDET_ConsInte__GUION__Det_b.'_C'.$key2->id.'\', 
                    index:\'G'.$key->GUIDET_ConsInte__GUION__Det_b.'_C'.$key2->id.'\', 
                    width:120 ,
                    editable: true, 
                    edittype:"select" , 
                    editoptions: {
                        dataUrl: \'<?=$url_crud;?>?CallDatosLisop_=si&idLista='.$key2->lista.'&campo=G'.$key->GUIDET_ConsInte__GUION__Det_b.'_C'.$key2->id.'\'
                    }
                }';

                            $camposSubgrilla .="\n".'
                        ,
                        {  
                            name:\'G'.$key->GUIDET_ConsInte__GUION__Det_b.'_C'.$key2->id.'\', 
                            index:\'G'.$key->GUIDET_ConsInte__GUION__Det_b.'_C'.$key2->id.'\', 
                            width:120 ,
                            editable: true, 
                            edittype:"select" , 
                            editoptions: {
                                dataUrl: \'<?=$url_crud;?>?CallDatosLisop_=si&idLista='.$key2->lista.'&campo=G'.$key->GUIDET_ConsInte__GUION__Det_b.'_C'.$key2->id.'\'
                            }
                        }';
                            $camposAbuscar .= ', '.$alfabeto[$Kakaroto].'.LISOPC_Nombre____b as  G'.$key->GUIDET_ConsInte__GUION__Det_b.'_C'.$key2->id;

                            $joins2 .= ' LEFT JOIN ".$BaseDatos_systema.".LISOPC as '.$alfabeto[$Kakaroto].' ON '.$alfabeto[$Kakaroto].'.LISOPC_ConsInte__b =  G'.$key->GUIDET_ConsInte__GUION__Det_b.'_C'.$key2->id;

                            $camposXmlParallenar .= "\n".'
            echo "<cell>". ($fila->G'.$key->GUIDET_ConsInte__GUION__Det_b.'_C'.$key2->id.')."</cell>";';  
                            if($key->GUIDET_ConsInte__PREGUN_De1_b != $key2->id){
                                $camposDelgguardadodeLagrilla .= ' 
            if(isset($_POST["G'.$key->GUIDET_ConsInte__GUION__Det_b.'_C'.$key2->id.'"])){
                $separador = "";
                if($validar == 1){
                    $separador = ",";
                }

                $LsqlU .= $separador."G'.$key->GUIDET_ConsInte__GUION__Det_b.'_C'.$key2->id.' = \'".$_POST["G'.$key->GUIDET_ConsInte__GUION__Det_b.'_C'.$key2->id.'"]."\'";
                $LsqlI .= $separador."G'.$key->GUIDET_ConsInte__GUION__Det_b.'_C'.$key2->id.'";
                $LsqlV .= $separador."\'".$_POST["G'.$key->GUIDET_ConsInte__GUION__Det_b.'_C'.$key2->id.'"]."\'";
                $validar = 1;
            }'."\n";  
                            }//cierro el if de $key->GUIDET_ConsInte__PREGUN_De1_b != $key2->id
                        break;

                        case '11':

                        	/* Neceistamos obtener los campos que vamos a llenar dinamicamente */
                        	$campoGuion = $key2->id;
        					$guionSelect2 = $key2->guion;

        					$Lsql_Principal_Guion = "SELECT GUION__ConsInte__PREGUN_Pri_b FROM ".$BaseDatos_systema.".GUION_ WHERE GUION__ConsInte__b = ".$guionSelect2;
							$resX = $mysqli->query($Lsql_Principal_Guion);
							$datosX = $resX->fetch_array();


							/* Neceistamos obtener los campos que vamos a llenar dinamicamente */
							$SqlCamposAMostrar = "SELECT PREGUI_ConsInte__PREGUN_b, Camp1.CAMPO__Nombre____b as PREGUI_ConsInte__CAMPO__b , Camp2.CAMPO__Nombre____b as PREGUI_Consinte__CAMPO__GUI_B  FROM  ".$BaseDatos_systema.".PREGUI JOIN ".$BaseDatos_systema.".CAMPO_ as Camp1 ON Camp1.CAMPO__ConsInte__b =  PREGUI_ConsInte__CAMPO__b JOIN ".$BaseDatos_systema.".CAMPO_ as Camp2 ON Camp2.CAMPO__ConsInte__b =  PREGUI_Consinte__CAMPO__GUI_B WHERE PREGUI_Consinte__GUION__b = ".$key->GUIDET_ConsInte__GUION__Det_b;

							$CamposQuevaAmostrarEnconsulta = $mysqli->query($SqlCamposAMostrar);
							$verdaderoscamposAmostrar = " G".$guionSelect2."_ConsInte__b as id ";
							$verdaderosCamposAllenar  = '';
							$verdaderosCamposAllenarGuion  = '';
							while ($keyXMostar = $CamposQuevaAmostrarEnconsulta->fetch_object()) {
								$verdaderoscamposAmostrar .=  ", ".$keyXMostar->PREGUI_ConsInte__CAMPO__b;
								$verdaderosCamposAllenar  .= '
				$data[$i][\''.$keyXMostar->PREGUI_Consinte__CAMPO__GUI_B.'\'] = $key->'.$keyXMostar->PREGUI_ConsInte__CAMPO__b.";\n";
								$verdaderosCamposAllenarGuion  .= "
											$('#'+rId[0]+'_".$keyXMostar->PREGUI_Consinte__CAMPO__GUI_B."').val(item.".$keyXMostar->PREGUI_Consinte__CAMPO__GUI_B.");\n";
							}

							$funcionesCampoGuion .= "\n".'
		if(isset($_GET[\'MostrarCombo_Guion_'.$key->GUIDET_ConsInte__GUION__Det_b.'_C'.$key2->id.'\'])){
            echo \'<select class="form-control input-sm"  name="'.$key->GUIDET_ConsInte__GUION__Det_b.'_C'.$key2->id.'" id="'.$key->GUIDET_ConsInte__GUION__Det_b.'_C'.$key2->id.'">\';
            echo \'<option >Buscar</option>\';
            echo \'</select>\';
        }

        if(isset($_GET[\'CallDatosCombo_Guion_'.$key->GUIDET_ConsInte__GUION__Det_b.'_C'.$key2->id.'\'])){
            $Ysql = "SELECT G'.$guionSelect2.'_ConsInte__b as id,  G'.$guionSelect2.'_C'.$datosX['GUION__ConsInte__PREGUN_Pri_b'].' as text FROM ".$BaseDatos.".G'.$guionSelect2.' WHERE G'.$guionSelect2.'_C'.$datosX['GUION__ConsInte__PREGUN_Pri_b'].' LIKE \'%".$_POST[\'q\']."%\'";
            $guion = $mysqli->query($Ysql);
            $i = 0;
            $datos = array();
            while($obj = $guion->fetch_object()){
                $datos[$i][\'id\'] = $obj->id;
                $datos[$i][\'text\'] = $obj->text;
                $i++;
            } 
            echo json_encode($datos);
        }

        if(isset($_POST[\'dameValoresCamposDinamicos_Guion_'.$key->GUIDET_ConsInte__GUION__Det_b.'_C'.$key2->id.'\'])){
            $Lsql = "SELECT '.$verdaderoscamposAmostrar.' FROM ".$BaseDatos.".G'.$guionSelect2.' WHERE G'.$guionSelect2.'_ConsInte__b = ".$_POST[\'dameValoresCamposDinamicos_Guion_'.$key->GUIDET_ConsInte__GUION__Det_b.'_C'.$key2->id.'\'];
            $res = $mysqli->query($Lsql);
            $data = array();
            $i = 0;
            while ($key = $res->fetch_object()) {
	     		'.$verdaderosCamposAllenar.'
                $i++;
            }
            
            echo json_encode($data);
        }
        ';

                            $funcionCargar .="\n".'
                ,
                {  
                    name:\'G'.$key->GUIDET_ConsInte__GUION__Det_b.'_C'.$key2->id.'\', 
                    index:\'G'.$key->GUIDET_ConsInte__GUION__Det_b.'_C'.$key2->id.'\', 
                    width:300 ,
                    editable: true, 
                    edittype:"select" , 
                    editoptions: {
                        dataUrl: \'<?=$url_crud;?>?MostrarCombo_Guion_'.$key->GUIDET_ConsInte__GUION__Det_b.'_C'.$key2->id.'=si\',
                        dataInit:function(el){
                            $(el).select2({ 
					        	placeholder: "Buscar",
						        allowClear: false,
						        minimumInputLength: 3,
						        ajax: {
						            url: \'<?=$url_crud;?>?CallDatosCombo_Guion_'.$key->GUIDET_ConsInte__GUION__Det_b.'_C'.$key2->id.'=si\',
						            dataType: \'json\',
						            type : \'post\',
						            delay: 250,
						            data: function (params) {
						                return {
						                    q: params.term
						                };
						            },
					              	processResults: function(data) {
									  	return {
									    	results: $.map(data, function(obj) {
									      		return {
									        		id: obj.id,
									        		text: obj.text
									      		};
									    	})
									  	};
									},
						            cache: true
						        }
					    	});  
                        },
                        dataEvents: [
                            {  
                                type: \'change\',
                                fn: function(e) {
                                    var r = this.id;
                                    var rId = r.split(\'_\');

                                    $.ajax({
									    url   : \'<?php echo $url_crud;?>\',
									    data  : { dameValoresCamposDinamicos_Guion_'.$key->GUIDET_ConsInte__GUION__Det_b.'_C'.$key2->id.' : this.value },
									    type  : \'post\',
									    dataType : \'json\',
									    success  : function(data){
									    	$.each(data, function(i, item){
									        '.$verdaderosCamposAllenarGuion.'
									    	});
									    }
									});
                                }
                            }
                        ]
                    }
                }';

                          	$camposSubgrilla .="\n".'
                        ,
                        {  
                            name:\'G'.$key->GUIDET_ConsInte__GUION__Det_b.'_C'.$key2->id.'\', 
                            index:\'G'.$key->GUIDET_ConsInte__GUION__Det_b.'_C'.$key2->id.'\', 
                            width:300 ,
                            editable: true, 
                            edittype:"select" , 
                            editoptions: {
                                dataUrl: \'<?=$url_crud;?>?CallDatosCombo_Guion_G'.$key->GUIDET_ConsInte__GUION__Det_b.'_C'.$key2->id.'=si\',
                                dataInit:function(el){
                                    $(el).select2({ 
                                        templateResult: function(data) {
                                            var r = data.text.split(\'|\');
                                            var row = \'<div class="row">\';
                                            var totalRows = 12 / r.length;
                                            for(i= 0; i < r.length; i++){
                                                row += \'<div class="col-md-\'+ Math.round(totalRows) +\'">\' + r[i] + \'</div>\';
                                            }
                                            row += \'</div>\';
                                            var $result = $(row);
                                            return $result;
                                        },
                                        templateSelection : function(data){
                                            var r = data.text.split(\'|\');
                                            return r[0];
                                        }
                                    });
                                    $(el).change(function(){
                                        var valores = $(el + " option:selected").text();
                                        var campos =  $(el + " option:selected").attr("dinammicos");
                                        var r = valores.split(\'|\');
                                        if(r.length > 1){

                                            var c = campos.split(\'|\');
                                            for(i = 1; i < r.length; i++){
                                               if(!$("#"+ rowid +"_"+c[i]).is("select")) {
                                                // the input field is not a select
                                                    $("#"+ rowid +"_"+c[i]).val(r[i]);
                                                }else{
                                                    var change = r[i].replace(\' \', \'\'); 
                                                    $("#"+ rowid +"_"+c[i]).val(change).change();
                                                }
                                            }
                                        }
                                    });
                                    //campos sub grilla
                                }
                            }
                        }';

                      		$CampoSql = "SELECT * FROM ".$BaseDatos_systema.".PREGUI WHERE PREGUI_ConsInte__PREGUN_b = ".$key2->id." ORDER BY PREGUI_ConsInte__b ASC LIMIT 0, 1 ";
                          	$campoSqlE = $mysqli->query($CampoSql);

							while ($cam = $campoSqlE->fetch_object()) {
								//Consulto por el id del campo que necesitamos y se repite las veces que sean necesarias
								$campoObtenidoSql = 'SELECT * FROM '.$BaseDatos_systema.'.CAMPO_ WHERE CAMPO__ConsInte__b = '.$cam->PREGUI_ConsInte__CAMPO__b;
								//echo $campoObtenidoSql;
								$resultCamposObtenidos = $mysqli->query($campoObtenidoSql);

								while ($o = $resultCamposObtenidos->fetch_object()) {
									$camposAbuscar .= ', '.$o->CAMPO__Nombre____b .' as G'.$key->GUIDET_ConsInte__GUION__Det_b.'_C'.$key2->id;
								}//cierro el While $o = $resultCamposObtenidos->fetch_object()
							}//Cierro el While de $cam = $campoSqlE->fetch_object()
                                                              
                          	$joins2 .= ' LEFT JOIN ".$BaseDatos.".G'.$key2->guion.' ON G'.$key2->guion.'_ConsInte__b  =  G'.$key->GUIDET_ConsInte__GUION__Det_b.'_C'.$key2->id;
                            $camposXmlParallenar .= "\n".'
            echo "<cell>". ($fila->G'.$key->GUIDET_ConsInte__GUION__Det_b.'_C'.$key2->id.')."</cell>";';  
                                                             // $camposAbuscar .= ', G'.$key->GUIDET_ConsInte__GUION__Det_b.'_C'.$key2->id;



							//Esto es para cargar ese pinche gion en el crud
							//Primero necesitamos obener el campo que vamos a usar
							$campoGuion = $key2->id;
							$guionSelect2 = $key2->guion;
							//Luego buscamos los campos en la tabla Pregui
							$CampoSql = "SELECT * FROM ".$BaseDatos_systema.".PREGUI WHERE PREGUI_ConsInte__PREGUN_b = ".$campoGuion;
							//recorremos esos campos para ir a buscarlos en la tabla campo_
							$CampoSqlR = $mysqli->query($CampoSql);
							$camposconsultaGuion = ' G'.$key2->guion.'_ConsInte__b as id ';


							$camposAmostrar = '';
							$valordelArray = 0;
							$nombresDeCampos = '';
							$camposAcolocarDinamicamente = '0';

							while($objet = $CampoSqlR->fetch_object()){
								//aqui obtenemos los campos que se colocara el valor dinamicamente al seleccionar una opcion del guion, ejemplo ciudad - departamento- pais..
								if($objet->PREGUI_Consinte__CAMPO__GUI_B != 0){
									//Consulto por el id del campo que necesitamos y se repite las veces que sean necesarias
									$campoamostrarSql = 'SELECT * FROM '.$BaseDatos_systema.'.CAMPO_ WHERE CAMPO__ConsInte__b = '.$objet->PREGUI_Consinte__CAMPO__GUI_B;
									$campoamostrarSqlE = $mysqli->query($campoamostrarSql);
									while($campoNombres = $campoamostrarSqlE->fetch_object()){
										$camposAcolocarDinamicamente .= '|'.$campoNombres->CAMPO__Nombre____b;
									}//cierro el while de ($campoNombres = $campoamostrarSqlE->fetch_object()
								}//cierro el if de $objet->PREGUI_Consinte__CAMPO__GUI_B != 0

								//Consulto por el id del campo que necesitamos y se repite las veces que sean necesarias
								$campoObtenidoSql = 'SELECT * FROM '.$BaseDatos_systema.'.CAMPO_ WHERE CAMPO__ConsInte__b = '.$objet->PREGUI_ConsInte__CAMPO__b;
								//echo $campoObtenidoSql;
								$resultCamposObtenidos = $mysqli->query($campoObtenidoSql);
								while ($objCampo = $resultCamposObtenidos->fetch_object()) {

									//Busco el nombre del campo para el nombre de las columnas
									$selectGuion = "SELECT PREGUN_Texto_____b as titulo_pregunta FROM ".$BaseDatos_systema.".PREGUN WHERE PREGUN_ConsInte__b =".$objCampo->CAMPO__ConsInte__PREGUN_b;
									$selectGuionE = $mysqli->query($selectGuion);
									while($o = $selectGuionE->fetch_object()){
										if($valordelArray == 0){
											$nombresDeCampos .= ($o->titulo_pregunta);
										}else{
											$nombresDeCampos .= ' | '.($o->titulo_pregunta).'';
										}
									}//cierro el while de $o = $selectGuionE->fetch_object()

									//añadimos los campos a la consulta que se necesita para cargar el guion
									$camposconsultaGuion .=', '.$objCampo->CAMPO__Nombre____b;
									if($valordelArray == 0){
										$camposAmostrar .= '".utf8_encode($obj->'.$objCampo->CAMPO__Nombre____b.')."';
									}else{
										$camposAmostrar .= ' | ".utf8_encode($obj->'.$objCampo->CAMPO__Nombre____b.')."';
									}//cierro el if de $valordelArray == 0
                                                                     
									$valordelArray++;
								}//cierro el while de $objCampo = $resultCamposObtenidos->fetch_object()
							}//cierro el while de $objet = $CampoSqlR->fetch_object()


							
							
                           	if($key->GUIDET_ConsInte__PREGUN_De1_b != $key2->id){                              
                                $camposDelgguardadodeLagrilla .= '  
            if(isset($_POST["G'.$key->GUIDET_ConsInte__GUION__Det_b.'_C'.$key2->id.'"])){
                $separador = "";
                if($validar == 1){
                    $separador = ",";
                }

                $LsqlU .= $separador."G'.$key->GUIDET_ConsInte__GUION__Det_b.'_C'.$key2->id.' = \'".$_POST["G'.$key->GUIDET_ConsInte__GUION__Det_b.'_C'.$key2->id.'"]."\'";
                $LsqlI .= $separador."G'.$key->GUIDET_ConsInte__GUION__Det_b.'_C'.$key2->id.'";
                $LsqlV .= $separador."\'".$_POST["G'.$key->GUIDET_ConsInte__GUION__Det_b.'_C'.$key2->id.'"]."\'";
                $validar = 1;
            }
            '."\n";  
                            }//Cierro el If de $key->GUIDET_ConsInte__PREGUN_De1_b != $key2->id

						break;

                        case '8':
							$funcionCargar .="\n". '
                , 
                { 
                    name:\'G'.$key->GUIDET_ConsInte__GUION__Det_b.'_C'.$key2->id.'\', 
                    index:\'G'.$key->GUIDET_ConsInte__GUION__Det_b.'_C'.$key2->id.'\', 
                    width:70 ,
                    editable: true, 
                    edittype:"checkbox",
                    editoptions: {
                        value:"1:0"
                    } 
                }';
							$camposSubgrilla .="\n". '
                        ,
                        { 
                            name:\'G'.$key->GUIDET_ConsInte__GUION__Det_b.'_C'.$key2->id.'\', 
                            index:\'G'.$key->GUIDET_ConsInte__GUION__Det_b.'_C'.$key2->id.'\', 
                            width:70 ,
                            editable: true, 
                            edittype:"checkbox",
                            editoptions: {
                                value:"1:0"
                            } 
                        }';  
							$camposAbuscar .= ', G'.$key->GUIDET_ConsInte__GUION__Det_b.'_C'.$key2->id;
                    		$camposXmlParallenar .= "\n".'
            echo "<cell>". $fila->G'.$key->GUIDET_ConsInte__GUION__Det_b.'_C'.$key2->id.'."</cell>"; ';
							if($key->GUIDET_ConsInte__PREGUN_De1_b != $key2->id){
                                $camposDelgguardadodeLagrilla .= ' 
            $G'.$key->GUIDET_ConsInte__GUION__Det_b.'_C'.$key2->id.' = 0;
            //este es tipo check primeo si viene y de acuerdo a su valor se pone 1 o 0
            if(isset($_POST["G'.$key->GUIDET_ConsInte__GUION__Det_b.'_C'.$key2->id.'"])){
                if($_POST["G'.$key->GUIDET_ConsInte__GUION__Det_b.'_C'.$key2->id.'"] == \'Yes\'){
                    $G'.$key->GUIDET_ConsInte__GUION__Det_b.'_C'.$key2->id.' = 1;
                }else if($_POST["G'.$key->GUIDET_ConsInte__GUION__Det_b.'_C'.$key2->id.'"] == \'off\'){
                    $G'.$key->GUIDET_ConsInte__GUION__Det_b.'_C'.$key2->id.' = 0;
                }else if($_POST["G'.$key->GUIDET_ConsInte__GUION__Det_b.'_C'.$key2->id.'"] == \'on\'){
                    $G'.$key->GUIDET_ConsInte__GUION__Det_b.'_C'.$key2->id.' = 1;
                }else if($_POST["G'.$key->GUIDET_ConsInte__GUION__Det_b.'_C'.$key2->id.'"] == \'No\'){
                    $G'.$key->GUIDET_ConsInte__GUION__Det_b.'_C'.$key2->id.' = 1;
                }else{
                    $G'.$key->GUIDET_ConsInte__GUION__Det_b.'_C'.$key2->id.' = $_POST["G'.$key->GUIDET_ConsInte__GUION__Det_b.'_C'.$key2->id.'"] ;
                }   

                $separador = "";
                if($validar == 1){
                    $separador = ",";
                }

                $LsqlU .= $separador." G'.$key->GUIDET_ConsInte__GUION__Det_b.'_C'.$key2->id.' = ".$G'.$key->GUIDET_ConsInte__GUION__Det_b.'_C'.$key2->id.'."";
                $LsqlI .= $separador." G'.$key->GUIDET_ConsInte__GUION__Det_b.'_C'.$key2->id.'";
                $LsqlV .= $separador.$G'.$key->GUIDET_ConsInte__GUION__Det_b.'_C'.$key2->id.';

                $validar = 1;
            }else{
                $separador = "";
                if($validar == 1){
                    $separador = ",";
                }

                $LsqlU .= $separador." G'.$key->GUIDET_ConsInte__GUION__Det_b.'_C'.$key2->id.' = ".$G'.$key->GUIDET_ConsInte__GUION__Det_b.'_C'.$key2->id.'."";
                $LsqlI .= $separador." G'.$key->GUIDET_ConsInte__GUION__Det_b.'_C'.$key2->id.'";
                $LsqlV .= $separador.$G'.$key->GUIDET_ConsInte__GUION__Det_b.'_C'.$key2->id.';

                $validar = 1;

            }'."\n";
							}
                                                             

						break;
    	                default:
                                                          
						break;
					}//cierro el swich de $key2->tipo_Pregunta
					$Kakaroto++;
                }//cierroe el while de $key2 = $campos_2->fetch_object()

             	$subgrilla .= $camposSubgrilla."\n".'
                        ,
                        { 
                            name: \'Padre\', 
                            index:\'Padre\', 
                            hidden: true , 
                            editable: false, 
                            editrules: { 
                                edithidden:true 
                            },
		                    editoptions:{ 
		                        dataInit: function(element) {                     
		                            $(element).val(id); 
		                        } 
		                    }
                        }
                    ], 
                    rowNum:20, 
                    pager: pager_id_'.$Kaka.', 
                    sortname: \'num\', 
                    sortorder: "asc",
                    height: \'100%\' 
                }); 

                jQuery("#"+subgrid_table_id_'.$Kaka.').jqGrid(\'navGrid\',"#"+pager_id_'.$Kaka.',{edit:false,add:false,del:false}) ';                   

                $dobleclick = '';
                if($key->GUIDET_Modo______b != 1){
                    $dobleclick .= ',

            ondblClickRow: function(rowId) {
                $("#frameContenedor").attr(\'src\', \'http://<?php echo $_SERVER["HTTP_HOST"];?>/crm_php/new_index.php?formulario='.$key->GUIDET_ConsInte__GUION__Det_b.'&view=si&registroId=\'+ rowId +\'&formaDetalle=si&yourfather=\'+ idTotal +\'&pincheCampo='.$key->GUIDET_ConsInte__PREGUN_De1_b.'&formularioPadre='.$id_a_generar.'<?php if(isset($_GET[\'token\'])){ echo "&token=".$_GET[\'token\']; }?>\');
                $("#editarDatos").modal(\'show\');

            }';
                }//cierro el if de $key->GUIDET_Modo______b != 1

                $funcionCargar .= '
                ,
                { 
                    name: \'Padre\', 
                    index:\'Padre\', 
                    hidden: true , 
                    editable: true, 
                    editrules: {
                        edithidden:true
                    },
                    editoptions:{ 
                        dataInit: function(element) {                     
                            $(element).val(id_'.$Kaka.'); 
                        } 
                    }
                }
            ],
            rowNum: 40,
            pager: "#pagerDetalles'.$Kaka.'",
            rowList: [40,80],
            sortable: true,
            sortname: \''.$orden.'\',
            sortorder: \'asc\',
            viewrecords: true,
            caption: \''.$key->GUIDET_Nombre____b.'\',
            editurl:"<?=$url_crud;?>?insertarDatosSubgrilla_'.$Kaka.'=si&usuario=<?php echo getIdentificacionUser($token);?>",
            height:\'250px\',
            beforeSelectRow: function(rowid){
                if(rowid && rowid!==lastSels){
                    '.$functionsBeforeSelect.'
                }
                lastSels = rowid;
            }
            '.$dobleclick.'
        });';

              	if($key->GUIDET_Modo______b == 1){
                 	$funcionCargar .= '
        $(\'#tablaDatosDetalless'.$Kaka.'\').navGrid("#pagerDetalles'.$Kaka.'", { add:false, del: true , edit: false });


        $(\'#tablaDatosDetalless'.$Kaka.'\').inlineNav(\'#pagerDetalles'.$Kaka.'\',
        // the buttons to appear on the toolbar of the grid
        { 
            edit: true, 
            add: true, 
            del: true, 
            cancel: true,
            editParams: {
                keys: true,
            },
            addParams: {
                keys: true
            }
        });';
	          	}else{//$key->GUIDET_Modo______b == 1
	              	//$funcionCargar .='jQuery("#tablaDatosDetalless'.$Kaka.'").jqGrid(\'navGrid\',\'#pagerDetalles'.$Kaka.'\',{});';
	          	}//cierrro el if $key->GUIDET_Modo______b == 1

                                        

         		$funcionCargar .= ' 

        $(window).bind(\'resize\', function() {
            $("#tablaDatosDetalless'.$Kaka.'").setGridWidth($(window).width());
        }).trigger(\'resize\');
    }';

    			$estavaina = '';

            	if(!is_null($key->GUIDET_ConsInte__PREGUN_Ma1_b)){
                	$estavaina .= '
        $Lsql = \'SELECT '.$guion_c.$key->GUIDET_ConsInte__PREGUN_Ma1_b.' FROM \'.$BaseDatos.\'.'.$guion.' WHERE '.$guion.'_ConsInte__b =\'.$id;
        // echo $Lsql;
        $resultado = $mysqli->query($Lsql);
        $numero = 0;

        while( $fila2 = $resultado->fetch_object() ) {
            $numero = $fila2->'.$guion_c.$key->GUIDET_ConsInte__PREGUN_Ma1_b.';
        }//Lql de esta vaina';

            	}else{//!is_null($key->GUIDET_ConsInte__PREGUN_Ma1_b
                	$estavaina .= '
        $numero = $id; // esta linea es la del padre';
            	}//!is_null($key->GUIDET_ConsInte__PREGUN_Ma1_b


            	if(!is_null($key->GUIDET_ConsInte__PREGUN_Ma1_b)){
            		$functionLlamarAloshijosLuegoDeCargar .= '
    	$("#btnLlamar_'.$Kaka.'").attr(\'padre\', $("#'.$guion_c.$key->GUIDET_ConsInte__PREGUN_Ma1_b.'").val());
    		var id_'.$Kaka.' = $("#'.$guion_c.$key->GUIDET_ConsInte__PREGUN_Ma1_b.'").val();
    		cargarHijos_'.$Kaka.'(id_'.$Kaka.');';
			
				}else{//!is_null($key->GUIDET_ConsInte__PREGUN_Ma1_b)
					$functionLlamarAloshijosLuegoDeCargar .= '
		$("#btnLlamar_'.$Kaka.'").attr(\'padre\', <?php if(isset($_GET[\'user\'])){ echo $_GET[\'user\']; }else{echo \'$("#hidId").val()\';};?>);
    		var id_'.$Kaka.' = <?php if(isset($_GET[\'user\'])){ echo $_GET[\'user\']; }else{echo \'$("#hidId").val()\';};?>;
    		cargarHijos_'.$Kaka.'(id_'.$Kaka.');';
			
				}//!is_null($key->GUIDET_ConsInte__PREGUN_Ma1_b)

				$funcionDeCargadoDelaGrilla .= "\n".'
    if(isset($_GET["callDatosSubgrilla_'.$Kaka.'"])){

        $numero = $_GET[\'id\']; 

        $SQL = "'.$camposAbuscar.' FROM ".$BaseDatos.".G'.$key->GUIDET_ConsInte__GUION__Det_b.' '.$joins2.' ";

        $SQL .= " WHERE G'.$key->GUIDET_ConsInte__GUION__Det_b.'_C'.$key->GUIDET_ConsInte__PREGUN_De1_b.' = \'".$numero."\'"; 

        $SQL .= " ORDER BY '.$orden.'";

        // echo $SQL;
        if ( stristr($_SERVER["HTTP_ACCEPT"],"application/xhtml+xml") ) { 
            header("Content-type: application/xhtml+xml;charset=utf-8"); 
        } else { 
            header("Content-type: text/xml;charset=utf-8"); 
        } 

        $et = ">"; 
        echo "<?xml version=\'1.0\' encoding=\'utf-8\'?$et\n"; 
        echo "<rows>"; // be sure to put text data in CDATA
        $result = $mysqli->query($SQL);
        while( $fila = $result->fetch_object() ) {
            echo "<row asin=\'".$fila->G'.$key->GUIDET_ConsInte__GUION__Det_b.'_ConsInte__b."\'>"; 
            echo "<cell>". ($fila->G'.$key->GUIDET_ConsInte__GUION__Det_b.'_ConsInte__b)."</cell>"; 
            '.$camposXmlParallenar.'
            echo "</row>"; 
        } 
        echo "</rows>"; 
    }';

    			$guardarPadre = '';
        		if(!is_null($key->GUIDET_ConsInte__PREGUN_Ma1_b)){

        			$guardarPadre = '$Lsql = \'SELECT '.$guion_c.$key->GUIDET_ConsInte__PREGUN_Ma1_b.' FROM \'.$BaseDatos_systema.\'.'.$guion.' WHERE '.$guion.'_ConsInte__b =\'.$_POST["Padre"];
                    // echo $Lsql;
                    $resultado = $mysqli->query($Lsql);
                    $numero = 0;

                    while( $fila2 = $resultado->fetch_object() ) {
                        $numero = $fila2->G547_C7118;
                    } // gusrdar Padre';
    			}else{//!is_null($key->GUIDET_ConsInte__PREGUN_Ma1_b)
        			$guardarPadre  ='
                    $numero = $_POST["Padre"];';    
        		}//!is_null($key->GUIDET_ConsInte__PREGUN_Ma1_b)


        		$funcionDeguardadoDeLagrilla .= '
    if(isset($_GET["insertarDatosSubgrilla_'.$Kaka.'"])){
        '.$camposDelgguardadodeLagrilla.'
            if(isset($_POST["Padre"])){
                if($_POST["Padre"] != \'\'){
                    //esto es porque el padre es el entero
                    $numero = $_POST["Padre"];

                    $G'.$key->GUIDET_ConsInte__GUION__Det_b.'_C'.$key->GUIDET_ConsInte__PREGUN_De1_b.' = $numero;
                    $LsqlU .= ", G'.$key->GUIDET_ConsInte__GUION__Det_b.'_C'.$key->GUIDET_ConsInte__PREGUN_De1_b.' = ".$G'.$key->GUIDET_ConsInte__GUION__Det_b.'_C'.$key->GUIDET_ConsInte__PREGUN_De1_b.'."";
                    $LsqlI .= ", G'.$key->GUIDET_ConsInte__GUION__Det_b.'_C'.$key->GUIDET_ConsInte__PREGUN_De1_b.'";
                    $LsqlV .= ",".$_POST["Padre"];
                }
            }  



            if(isset($_POST[\'oper\'])){
                if($_POST["oper"] == \'add\' ){
                    $LsqlI .= ",  G'.$key->GUIDET_ConsInte__GUION__Det_b.'_Usuario ,  G'.$key->GUIDET_ConsInte__GUION__Det_b.'_FechaInsercion";
                    $LsqlV .= ", ".$_GET[\'usuario\']." , \'".date(\'Y-m-d H:i:s\')."\'";
                    $Lsql = $LsqlI.")" . $LsqlV.")";
                }else if($_POST["oper"] == \'edit\' ){
                    $Lsql = $LsqlU." WHERE G'.$key->GUIDET_ConsInte__GUION__Det_b.'_ConsInte__b =".$_POST["providerUserId"]; 
                }else if($_POST[\'oper\'] == \'del\'){
                    $Lsql = "DELETE FROM  ".$BaseDatos.".G'.$key->GUIDET_ConsInte__GUION__Det_b.' WHERE  G'.$key->GUIDET_ConsInte__GUION__Det_b.'_ConsInte__b = ".$_POST[\'id\'];
                    $validar = 1;
                }
            }

            if($validar == 1){
                // echo $Lsql;
                if ($mysqli->query($Lsql) === TRUE) {
                    echo $mysqli->insert_id;
                } else {
                    echo "Error Hacieno el proceso los registros : " . $mysqli->error;
                }  
            }  
        }
    }';


	    		$funcionCargarFinal .= $funcionCargar;
	            $functionDescargarFinal .= $functionDescargar;
	            $functionRecargarFinal .= $functionRecargar;
	            $funcionDeguardadoDeLagrillaFinal .= $funcionDeguardadoDeLagrilla;
	            

	            $funcionDeCargadoDelaGrillaFinal .= $funcionDeCargadoDelaGrilla;
	            $funcionCargarComboCuandoSeaMaestroFinal .= $funcionCargarComboCuandoSeaMaestro;
	            $camposXmlParallenarFinal .= $camposXmlParallenar;
	            $camposSubgrillaFinal .= $camposSubgrilla;
	            $tabsFinalOperacions .= $tabsFinal;

	            //generar_extra($key->GUIDET_ConsInte__GUION__Det_b);  
	            generar_Formulario_Script($key->GUIDET_ConsInte__GUION__Det_b);
	            $Kaka++;
        	}//cierro el while $key = $EjecutarMaetsro->fetch_object()

        	$subgrilla .= "\n".'
            }, 
            subGridRowColapsed: function(subgrid_id, row_id) { 
                // this function is called before removing the data 
                //var subgrid_table_id; 
                //subgrid_table_id = subgrid_id+"_t"; 
                //jQuery("#"+subgrid_table_id).remove(); 
            }';

        	$tabscontenidoMaestro .= "\n".'
    </div>';
        	$contenidoMaestro .= "\n".'
    </ul>';
        	$tabsDeMaestro .= $contenidoMaestro ."\n". $tabscontenidoMaestro;
        	$tabsDeMaestro .= "\n".'
</div>';
			/* si no es maestro no debo escribirlo solo borrar lso cpntenidos de subgrilla */
			if($EsONoEs > 0){
	            fputs($fp , $tabsDeMaestro);
	            fputs($fp , chr(13).chr(10)); // Genera saldo de linea 
	        }else{
	            $subgrilla = '';
	        }//Cierro el if de $EsONoEs > 0
			

			if($GUION__ConsInte__PREGUN_Rep_b != NULL && !is_null($GUION__ConsInte__PREGUN_Rep_b)){
				$Lxsql = "SELECT PREGUN_ConsInte__OPCION_B FROM ".$BaseDatos_systema.".PREGUN WHERE PREGUN_ConsInte__b = ".$GUION__ConsInte__PREGUN_Rep_b;
	      	 	$capo  = $mysqli->query($Lxsql);
	      	 	$valorListaEtado = NULL;
	      	 	while ($kay = $capo->fetch_object()) {
	      	 	 	$valorListaEtado = $kay->PREGUN_ConsInte__OPCION_B;
	      	 	} 
			}

        	if($GUION__ConsInte__PREGUN_Tip_b != NULL && !is_null($GUION__ConsInte__PREGUN_Tip_b)){
//echo "Si llega  esta parte";
				$campo = '
<input type="hidden" name="campana_crm" id="campana_crm" value="<?php if(isset($_GET["campana_crm"])){echo $_GET["campana_crm"];}else{ echo "0";}?>">
<div class="row" style="background-color: #FAFAFA; ">
	<br/>
    <div class="col-md-5 col-xs-5">
        <div class="form-group">
       		<label for="'.$guion_c.$GUION__ConsInte__PREGUN_Tip_b.'">Tipificaci&oacute;n</label>
            <select class="form-control input-sm tipificacionBackOffice" name="tipificacion" id="'.$guion_c.$GUION__ConsInte__PREGUN_Tip_b.'">
                <option value="0">Seleccione</option>
                <?php
                $Lsql = "SELECT LISOPC_ConsInte__b AS OPCION_ConsInte__b, LISOPC_Nombre____b AS OPCION_Nombre____b, MONOEF_EFECTIVA__B,  MONOEF_ConsInte__b, MONOEF_TipNo_Efe_b, MONOEF_Importanc_b, LISOPC_CambRepr__b , MONOEF_Contacto__b FROM ".$BaseDatos_systema.".LISOPC 
                        JOIN ".$BaseDatos_systema.".MONOEF ON MONOEF.MONOEF_ConsInte__b = LISOPC.LISOPC_Clasifica_b
                        WHERE LISOPC.LISOPC_ConsInte__OPCION_b = '.$valorLista.';";
                $obj = $mysqli->query($Lsql);
                while($obje = $obj->fetch_object()){
                    echo "<option value=\'".$obje->OPCION_ConsInte__b."\' efecividad = \'".$obje->MONOEF_EFECTIVA__B."\' monoef=\'".$obje->MONOEF_ConsInte__b."\' TipNoEF = \'".$obje->MONOEF_TipNo_Efe_b."\' cambio=\'".$obje->LISOPC_CambRepr__b."\' importancia = \'".$obje->MONOEF_Importanc_b."\' contacto=\'".$obje->MONOEF_Contacto__b."\' >".($obje->OPCION_Nombre____b)."</option>";

                }            
                ?>
            </select>
            <input type="hidden" name="Efectividad" id="Efectividad" value="0">
            <input type="hidden" name="MonoEf" id="MonoEf" value="0">
            <input type="hidden" name="TipNoEF" id="TipNoEF" value="0">
            <input type="hidden" name="FechaInicio" id="FechaInicio" value="0">
            <input type="hidden" name="FechaFinal" id="FechaFinal" value="0">
            <input type="hidden" name="MonoEfPeso" id="MonoEfPeso" value="0">
            <input type="hidden" name="ContactoMonoEf" id="ContactoMonoEf" value="0">
        </div>
    </div>
    <div class="col-md-5 col-xs-5">
        <div class="form-group">
        	<label for="'.$guion_c.$GUION__ConsInte__PREGUN_Rep_b.'">Estado de la tarea</label>
            <select class="form-control input-sm reintento" name="reintento" id="'.$guion_c.$GUION__ConsInte__PREGUN_Rep_b.'">
                <option value="0">Seleccione</option>
                <?php
                	$Lsql = "SELECT LISOPC_ConsInte__b AS OPCION_ConsInte__b, LISOPC_Nombre____b AS OPCION_Nombre____b FROM ".$BaseDatos_systema.".LISOPC 
                        WHERE LISOPC.LISOPC_ConsInte__OPCION_b = '.$valorListaEtado.';";
	                $obj = $mysqli->query($Lsql);
	                while($obje = $obj->fetch_object()){
	                    echo "<option value=\'".$obje->OPCION_ConsInte__b."\'>".($obje->OPCION_Nombre____b)."</option>";
	                }      
                ?>
            </select>     
        </div>
    </div>
    <div class="col-md-2 col-xs-2" style="text-align: center;">
    	<label for="'.$guion_c.$GUION__ConsInte__PREGUN_Rep_b.'" style="visibility:hidden;">Estado de la tarea</label>
        <button class="btn btn-primary btn-block" id="Save" type="button">
            Guardar Gesti&oacute;n
        </button>
    </div>
</div>
<div class="row" style="background-color: #FAFAFA;">
    <div class="col-md-12 col-xs-12">
        <div class="form-group">
            <textarea class="form-control input-sm textAreaComentarios" name="textAreaComentarios" id="'.$guion_c.$GUION__ConsInte__PREGUN_Com_b.'" placeholder="Observaciones"></textarea>
        </div>
    </div>
</div>';

				fputs($fp , $campo);
				fputs($fp , chr(13).chr(10)); 
			}

			fputs($fp , '<!-- SECCION : PAGINAS INCLUIDAS -->');
      		fputs($fp , chr(13).chr(10)); // Genera saldo de linea 
      
			fputs($fp , '<?php include(__DIR__ ."/../pies.php");?>');
			fputs($fp , chr(13).chr(10)); // Genera saldo de linea 
			fputs($fp , '<script type="text/javascript" src="formularios/'.$guion.'/'.$guion.'_eventos.js"></script>');

			/*Armamos el Javascriot de la busqueda */
			$str_Lsql = "SELECT PREGUN_Texto_____b as titulo_pregunta , PREGUN_Tipo______b as tipo_Pregunta, PREGUN_ConsInte__b as id , PREGUN_ConsInte__GUION__PRE_B as guion, PREGUN_ConsInte__OPCION_B as lista, PREGUN_OrdePreg__b , PREGUN_NumeMini__b as minimoNumero, PREGUN_NumeMaxi__b as maximoNumero, PREGUN_FechMini__b as fechaMinimo, PREGUN_FechMaxi__b as fechaMaximo , PREGUN_HoraMini__b as horaMini , PREGUN_HoraMaxi__b as horaMaximo, PREGUN_TexErrVal_b as error FROM ".$BaseDatos_systema.".PREGUN WHERE PREGUN_ConsInte__GUION__b = ".$id_a_generar." AND PREGUN_IndiBusc__b != 0 ORDER BY PREGUN_OrdePreg__b";

    		$respuestaBusqueda = $mysqli->query($str_Lsql);
    		$whereJavascript = '';
    		while ($keyBusqueda = $respuestaBusqueda->fetch_object()) {
    			$whereJavascript .= ' , '.$guion_c.$keyBusqueda->id.' : $("#busq_'.$guion_c.$keyBusqueda->id.'").val()';
    		}


			//esto es escribir el JavaScript
			$datoJavascript = ' 
<script type="text/javascript">
    $(function(){

    	var meses = new Array(12);
    	meses[0] = "01";
    	meses[1] = "02";
    	meses[2] = "03";
    	meses[3] = "04";
    	meses[4] = "05";
    	meses[5] = "06";
    	meses[6] = "07";
    	meses[7] = "08";
    	meses[8] = "09";
    	meses[9] = "10";
    	meses[10] = "11";
    	meses[11] = "12";

    	var d = new Date();
        var h = d.getHours();
        var horas = (h < 10) ? \'0\' + h : h;
        var dia = d.getDate();
        var dias = (dia < 10) ? \'0\' + dia : dia;
        var fechaInicial = d.getFullYear() + \'-\' + meses[d.getMonth()] + \'-\' + dias + \' \'+ horas +\':\'+d.getMinutes()+\':\'+d.getSeconds();
        $("#FechaInicio").val(fechaInicial);
            

        //Esta es por si lo llaman en modo formulario de edicion LigthBox
        <?php if(isset($_GET[\'registroId\'])){ ?>
        $.ajax({
            url      : \'<?=$url_crud;?>\',
            type     : \'POST\',
            data     : { CallDatos : \'SI\', id : <?php echo $_GET[\'registroId\']; ?> },
            dataType : \'json\',
            success  : function(data){
                //recorrer datos y enviarlos al formulario
                $.each(data, function(i, item) {
                    '.$functionRecargarFinal;

           	$campos_3 = $mysqli->query($Lsql);
			while ($key = $campos_3->fetch_object()){
			    if($key->tipo_Pregunta != '8' && $key->tipo_Pregunta != '9'){
			      	$datoJavascript .= "\n".' 
                    $("#'.$guion_c.$key->id.'").val(item.'.$guion_c.$key->id.');';
			      	if($key->tipo_Pregunta == '11'){
			        	$datoJavascript .="\n".' 
                    $("#'.$guion_c.$key->id.'").val(item.'.$guion_c.$key->id.').trigger("change"); ';
			      	}//cierro el if de $key->tipo_Pregunta == '11'
			    }else{
			        $datoJavascript .= "\n".'   
                    if(item.'.$guion_c.$key->id.' == 1){
                        $("#'.$guion_c.$key->id.'").attr(\'checked\', true);
                    } ';
			    }//cierro el if de $key->tipo_Pregunta != '8' && $key->tipo_Pregunta != '9'
			}//cierro el while $key = $campos_3->fetch_object()


			$datoJavascript .= '

					$("#h3mio").html(item.principal);

                });
                //Deshabilitar los campos

                //Habilitar todos los campos para edicion
                $(\'#FormularioDatos :input\').each(function(){
                    $(this).attr(\'disabled\', true);
                });

                              

                //Habilidar los botones de operacion, add, editar, eliminar
                $("#add").attr(\'disabled\', false);
                $("#edit").attr(\'disabled\', false);
                $("#delete").attr(\'disabled\', false);

                //Desahabiliatra los botones de salvar y seleccionar_registro
                $("#cancel").attr(\'disabled\', true);
                $("#Save").attr(\'disabled\', true);
            } 
        });

        $("#hidId").val(<?php echo $_GET[\'registroId\'];?>);
        idTotal = <?php echo $_GET[\'registroId\'];?>;

     	$("#TxtFechaReintento").attr(\'disabled\', true);
        $("#TxtHoraReintento").attr(\'disabled\', true); 
        '.$darlePadreAlHijo.'

        vamosRecargaLasGrillasPorfavor(<?php echo $_GET[\'registroId\'];?>)

        <?php } ?>

        <?php if(isset($_GET[\'user\'])){ ?>
        	/*'.$darlePadreAlHijo_3.'
        	vamosRecargaLasGrillasPorfavor(\'<?php echo $_GET[\'user\'];?>\');
        	idTotal = <?php echo $_GET[\'user\'];?>; */
        <?php } ?>

        $("#refrescarGrillas").click(function(){
            '.$limpiadordeGrillas.'
            '.$functionLlamarAloshijosLuegoDeCargar.'
        });

        //Esta es la funcionalidad de los Tabs
        '."\n".' '.$tabsFinalOperacions.'
        //Select2 estos son los guiones
        '."\n".$select2.'
        //datepickers
        '.$fechaValidadaOno.'

        //Timepickers
        '."\n".$horaValidadaOno.'

        //Validaciones numeros Enteros
        '."\n".$numeroFuncion.'

        //Validaciones numeros Decimales
        '."\n".$decimalFuncion.'

        /* Si son d formulas */
        '."\n".$funciones_jsY.'

        //Si tienen dependencias

        '."\n".$funciones_jsx.'


        $("#'.$guion_c.$GUION__ConsInte__PREGUN_Tip_b.'").change(function(){
        	var id = $(this).attr(\'id\');
            var valor = $("#"+ id +" option:selected").attr(\'efecividad\');
            var monoef = $("#"+ id +" option:selected").attr(\'monoef\');
            var TipNoEF = $("#"+ id +" option:selected").attr(\'TipNoEF\');
            var cambio = $("#"+ id +" option:selected").attr(\'cambio\');
            var importancia = $("#"+ id + " option:selected").attr(\'importancia\');
            var contacto = $("#"+id+" option:selected").attr(\'contacto\');
            $(".reintento").val(TipNoEF).change();
            $("#Efectividad").val(valor);
            $("#MonoEf").val(monoef);
            $("#TipNoEF").val(TipNoEF);
            $("#MonoEfPeso").val(importancia);
            $("#ContactoMonoEf").val(contacto);
    	});
        
        //Funcionalidad del botob guardar
        '."\n".$botonSalvar.'

        //funcionalidad del boton Gestion botonCerrarErronea
        '.$botonCerrarErronea.'




    <?php if(!isset($_GET[\'view\'])) { ?>
    //SECICON : CARGUE INFORMACION EN HOJA DE DATOS
    //Cargar datos de la hoja de datos
    function cargar_hoja_datos(){
        $.jgrid.defaults.width = \'1225\';
        $.jgrid.defaults.height = \'650\';
        $.jgrid.defaults.responsive = true;
        $.jgrid.defaults.styleUI = \'Bootstrap\';
        var lastsel2;
        $("#tablaDatos").jqGrid({
            url:\'<?=$url_crud;?>?CallDatosJson=si\',
            datatype: \'json\',
            mtype: \'POST\',';

			$campos = $mysqli->query($Lsql);
			$i = 0;
			$titulos='';
			$orden = '';
			while ($key = $campos->fetch_object()){
				if(	$key->id != $GUION__ConsInte__PREGUN_Tip_b && 
							$key->id != $GUION__ConsInte__PREGUN_Rep_b &&
							$key->id != $GUION__ConsInte__PREGUN_Fag_b && 
							$key->id != $GUION__ConsInte__PREGUN_Hag_b &&
							$key->id != $GUION__ConsInte__PREGUN_Com_b){
					if($key->tipo_Pregunta != '9' && $key->tipo_Pregunta != '12'){
						if($i==0){
							$titulos = '\''.($key->titulo_pregunta).'\'';
							$orden = $guion_c.$GUION__ConsInte__PREGUN_Pri_b;
						}else{
							$titulos .= ',\''.($key->titulo_pregunta).'\'';
						}//cierro el if $i==0
						$i++;
					}//cierro el if $key->tipo_Pregunta != '9'
				}//cierro el if $$key->id != $GUION__ConsInte__PREGUN_Tip_b && $key->id != $GUION__ConsInte__PREGUN_Rep_b && $key->id != $GUION__ConsInte__PREGUN_Fag_b && $key->id != $GUION__ConsInte__PREGUN_Hag_b && $key->id != $GUION__ConsInte__PREGUN_Com_b			    
			}//cierro el while $key = $campos->fetch_object()

			$datoJavascript .= '
            colNames:[\'id\','.$titulos.'],
            colModel:[
                //Traigo los datos de la base de dtaos y los defino en que columna va cada uno, tambien definimos con es su forma de edicion, sea Tipo listas, tipo Textos, etc.
                {
                    name:\'providerUserId\',
                    index:\'providerUserId\', 
                    width:100,
                    editable:true, 
                    editrules:{
                        required:false, 
                        edithidden:true
                    },
                    hidden:true, 
                    editoptions:{ 
                        dataInit: function(element) {                     
                          $(element).attr("readonly", "readonly"); 
                        } 
                    }
                }';
			$campos_2 = $mysqli->query($Lsql);
			
			while ($key = $campos_2->fetch_object()){
				if(	$key->id != $GUION__ConsInte__PREGUN_Tip_b && 
					$key->id != $GUION__ConsInte__PREGUN_Rep_b &&
					$key->id != $GUION__ConsInte__PREGUN_Fag_b && 
					$key->id != $GUION__ConsInte__PREGUN_Hag_b &&
					$key->id != $GUION__ConsInte__PREGUN_Com_b){
	  				switch ($key->tipo_Pregunta) {
	      				case '1':
	          				$datoJavascript .= "\n".'
	                ,
	                { 
	                    name:\''.$guion_c.$key->id.'\', 
	                    index: \''.$guion_c.$key->id.'\', 
	                    width:160, 
	                    resizable:false, 
	                    sortable:true , 
	                    editable: true 
	                }';
	          			break;

	      				case '2':
	          				$datoJavascript .="\n". '
	                ,
	                { 
	                    name:\''.$guion_c.$key->id.'\', 
	                    index:\''.$guion_c.$key->id.'\', 
	                    width:150, 
	                    editable: true 
	                }';
      					break;

	     				case '3':
	         	 			$datoJavascript .="\n".' 
	                ,
	                {  
	                    name:\''.$guion_c.$key->id.'\', 
	                    index:\''.$guion_c.$key->id.'\', 
	                    width:80 ,
	                    editable: true, 
	                    searchoptions: {
	                        sopt: [\'eq\', \'ne\', \'lt\', \'le\', \'gt\', \'ge\']
	                    }, 
	                    editoptions:{
	                        size:20,
	                        dataInit:function(el){
	                            $(el).numeric();
	                        }
	                    } 
	                }';
	      				break;

	      				case '4':
	          				$datoJavascript .="\n".'
	                ,
	                {  
	                    name:\''.$guion_c.$key->id.'\', 
	                    index:\''.$guion_c.$key->id.'\', 
	                    width:80 ,
	                    editable: true, 
	                    searchoptions: {
	                        sopt: [\'eq\', \'ne\', \'lt\', \'le\', \'gt\', \'ge\']
	                    }, 
	                    editoptions:{
	                        size:20,
	                        dataInit:function(el){
	                            $(el).numeric({ decimal : ".",  negative : false, scale: 4 });
	                        }
	                    } 
	                }';
	      				break;

	      				case '5':
	          				$datoJavascript .="\n".'
	                ,
	                {  
	                    name:\''.$guion_c.$key->id.'\', 
	                    index:\''.$guion_c.$key->id.'\', 
	                    width:120 ,
	                    editable: true ,
	                    formatter: \'text\', 
	                    searchoptions: {
	                        sopt: [\'eq\', \'ne\', \'lt\', \'le\', \'gt\', \'ge\']
	                    }, 
	                    editoptions:{
	                        size:20,
	                        dataInit:function(el){
	                            $(el).datepicker({
	                                language: "es",
	                                autoclose: true,
	                                todayHighlight: true
	                            });
	                        },
	                        defaultValue: function(){
	                            var currentTime = new Date();
	                            var month = parseInt(currentTime.getMonth() + 1);
	                            month = month <= 9 ? "0"+month : month;
	                            var day = currentTime.getDate();
	                            day = day <= 9 ? "0"+day : day;
	                            var year = currentTime.getFullYear();
	                            return year+"-"+month + "-"+day;
	                        }
	                    }
	                }';
	      				break;
	        
	      				case '10':
	          				$datoJavascript .="\n".'
	                ,
	                {  
	                    name:\''.$guion_c.$key->id.'\', 
	                    index:\''.$guion_c.$key->id.'\', 
	                    width:70 ,
	                    editable: true ,
	                    formatter: \'text\', 
	                    editoptions:{
	                        size:20,
	                        dataInit:function(el){
	                            //Timepicker
	                            var options = { 
	                                now: "15:00:00", //hh:mm 24 hour format only, defaults to current time
	                                twentyFour: true, //Display 24 hour format, defaults to false
	                                title: \''.$key->titulo_pregunta.'\', //The Wickedpicker\'s title,
	                                showSeconds: true, //Whether or not to show seconds,
	                                secondsInterval: 1, //Change interval for seconds, defaults to 1
	                                minutesInterval: 1, //Change interval for minutes, defaults to 1
	                                beforeShow: null, //A function to be called before the Wickedpicker is shown
	                                show: null, //A function to be called when the Wickedpicker is shown
	                                clearable: false, //Make the picker\'s input clearable (has clickable "x")
	                            }; 
	                            $(el).wickedpicker(options);
	                        }
	                    }
	                }';
	      				break;

	      				case '6':
	          				$datoJavascript .="\n".'
	                ,
	                { 
	                    name:\''.$guion_c.$key->id.'\', 
	                    index:\''.$guion_c.$key->id.'\', 
	                    width:120 ,
	                    editable: true, 
	                    edittype:"select" , 
	                    editoptions: {
	                        dataUrl: \'<?=$url_crud;?>?CallDatosLisop_=si&idLista='.$key->lista.'&campo='.$guion_c.$key->id.'\'
	                    }
	                }';
	      
	      				break;

	      				case '11':

	          				$datoJavascript .="\n".'
	                ,
	                { 
	                    name:\''.$guion_c.$key->id.'\', 
	                    index:\''.$guion_c.$key->id.'\', 
	                    width:300 ,
	                    editable: true, 
	                    edittype:"select" , 
	                    editoptions: {
	                        dataUrl: \'<?=$url_crud;?>?CallDatosCombo_Guion_'.$guion_c.$key->id.'=si\',
	                        dataInit:function(el){
	                        	$(el).select2();
	                            /*$(el).select2({ 
	                                templateResult: function(data) {
	                                    var r = data.text.split(\'|\');
	                                    var row = \'<div class="row">\';
	                                    var totalRows = 12 / r.length;
	                                    for(i= 0; i < r.length; i++){
	                                        row += \'<div class="col-md-\'+ Math.round(totalRows) +\'">\' + r[i] + \'</div>\';
	                                    }
	                                    row += \'</div>\';
	                                    var $result = $(row);
	                                    return $result;
	                                },
	                                templateSelection : function(data){
	                                    var r = data.text.split(\'|\');
	                                    return r[0];
	                                }
	                            });*/
	                            $(el).change(function(){
	                                var valores = $(el + " option:selected").attr("llenadores");
	                                var campos =  $(el + " option:selected").attr("dinammicos");
	                                var r = valores.split(\'|\');
	                                if(r.length > 1){

	                                    var c = campos.split(\'|\');
	                                    for(i = 1; i < r.length; i++){
	                                        $("#"+ rowid +"_"+c[i]).val(r[i]);
	                                    }
	                                }
	                            });
	                        }
	                    }
	                }';
	      				break;

	      				case '8':
	          				$datoJavascript .="\n". '
	                ,
	                { 
	                    name:\''.$guion_c.$key->id.'\', 
	                    index:\''.$guion_c.$key->id.'\', 
	                    width:70 ,
	                    editable: true, 
	                    edittype:"checkbox",
	                    editoptions: {
	                        value:"1:0"
	                    } 
	                }';
	      				break;
	        			
	        			default:
	          
	          			break;
	 				}//cierro el Swich $key->tipo_Pregunta
				}//cierro el If $key->id != $GUION__ConsInte__PREGUN_Tip_b && $key->id != $GUION__ConsInte__PREGUN_Rep_b && $key->id != $GUION__ConsInte__PREGUN_Fag_b && 					$key->id != $GUION__ConsInte__PREGUN_Hag_b && $key->id != $GUION__ConsInte__PREGUN_Com_b
			}//cierro el While $key = $campos_2->fetch_object()
 			
 			$datoJavascript .= '
            ],
            pager: "#pager" ,
            beforeSelectRow: function(rowid){
                if(rowid && rowid!==lastsel2){
                    '.$select2_hojadeDatos.'
                }
                lastsel2=rowid;
            },
            rowNum: 50,
            rowList:[50,100],
            loadonce: false,
            sortable: true,
            sortname: \''.$orden.'\',
            sortorder: \'asc\',
            viewrecords: true,
            caption: \'PRUEBAS\',
            editurl:"<?=$url_crud;?>?insertarDatosGrilla=si&usuario=<?php echo getIdentificacionUser($token);?>",
            autowidth: true
            '.$subgrilla.'
        });

        $(\'#tablaDatos\').navGrid("#pager", { add:false, del: true , edit: false });
        $(\'#tablaDatos\').inlineNav(\'#pager\',
        // the buttons to appear on the toolbar of the grid
        { 
            edit: true, 
            add: true, 
            cancel: true,
            editParams: {
                keys: true,
            },
            addParams: {
                keys: true
            }
        });
      
        //para cuando se Maximice o minimize la pantalla.
        $(window).bind(\'resize\', function() {
            $("#tablaDatos").setGridWidth($(window).width());
        }).trigger(\'resize\'); 
    }

    //SECCION  : Manipular Lista de Navegacion

    //buscar registro en la Lista de navegacion
    function llenar_lista_navegacion(x){
        var tr = \'\';
        $.ajax({
            url      : \'<?=$url_crud;?>\',
            type     : \'POST\',
            data     : { CallDatosJson : \'SI\', Busqueda : x '.$whereJavascript.' },
            dataType : \'json\',
            success  : function(data){
                //Cargar la lista con los datos obtenidos en la consulta
                $.each(data, function(i, item) {
                    var strIconoBackOffice = \'\';
                    if(data[i].estado == \'1\'){
                        strIconoBackOffice = \'En gestión\';
                    }else if(data[i].estado == \'2\'){
                        strIconoBackOffice = \'Cerrada\';
                    }else if(data[i].estado == \'3\'){
                        strIconoBackOffice = \'Devuelta\';
                    }
                    

                    tr += "<tr class=\'CargarDatos\' id=\'"+data[i].id+"\'>";
                    tr += "<td>";
                    tr += "<p style=\'font-size:14px;\'><b>"+data[i].camp1+"</b></p>";
                    tr += "<p style=\'font-size:12px; margin-top:-10px;\'>"+data[i].camp2+"<span style=\'position: relative;right: 2px;float: right;font-size:10px;"+ data[i].color+ "\'>"+strIconoBackOffice+"</span></p>";
                    tr += "</td>";
                    tr += "</tr>";
                });
                $("#tablaScroll").html(tr);
                //aplicar funcionalidad a la Lista de navegacion
                busqueda_lista_navegacion();

                //SI el Id existe, entonces le damos click,  para traer sis datos y le damos la clase activa
                if ( $("#"+idTotal).length > 0) {
                    $("#"+idTotal).click();   
                    $("#"+idTotal).addClass(\'active\'); 
                }else{
                    //Si el id no existe, se selecciona el primer registro de la Lista de navegacion
                    $(".CargarDatos :first").click();
                }

            } 
        });
    }

    //poner en el formulario de la derecha los datos del registro seleccionado a la izquierda, funcionalidad de la lista de navegacion
    function busqueda_lista_navegacion(){

        $(".CargarDatos").click(function(){
            //remover todas las clases activas de la lista de navegacion
            $(".CargarDatos").each(function(){
                $(this).removeClass(\'active\');
            });
            
            //add la clase activa solo ala celda que le dimos click.
            $(this).addClass(\'active\');
              
              
            var id = $(this).attr(\'id\');
            '.$darlePadreAlHijo_2.'
            //buscar los datos
            $.ajax({
                url      : \'<?=$url_crud;?>\',
                type     : \'POST\',
                data     : { CallDatos : \'SI\', id : id },
                dataType : \'json\',
                success  : function(data){
                    //recorrer datos y enviarlos al formulario
                    $.each(data, function(i, item) {
                        '.$functionRecargarFinal;

            $campos_3 = $mysqli->query($Lsql);
			while ($key = $campos_3->fetch_object()){
			    if($key->tipo_Pregunta != '8' && $key->tipo_Pregunta != '9'){
			      $datoJavascript .= "\n".'
                        $("#'.$guion_c.$key->id.'").val(item.'.$guion_c.$key->id.');';
			      	if( $key->tipo_Pregunta == '11' || $key->tipo_Pregunta == '6' || $key->tipo_Pregunta == '13'){
			        	$datoJavascript .="\n".' 
        	            $("#'.$guion_c.$key->id.'").val(item.'.$guion_c.$key->id.').trigger("change"); ';
			      	}//cierro este if $key->tipo_Pregunta == '11'
			    

			    }else{
			        $datoJavascript .= "\n".'    
                        if(item.'.$guion_c.$key->id.' == 1){
                           $("#'.$guion_c.$key->id.'").attr(\'checked\', true);
                        } ';
			    }//cierro el if $key->tipo_Pregunta != '8' && $key->tipo_Pregunta != '9'
			}//cierro el while $key = $campos_3->fetch_object()



			$datoJavascript .= '
        				$("#h3mio").html(item.principal);
        				
                    });

               
                } 
            });

            $("#hidId").val(id);
            idTotal = $("#hidId").val();

            $.ajax({
	        	url   : \'<?php echo $url_crud; ?>\',
	        	type  : \'post\',
	        	data  : { DameHistorico : \'si\', user_codigo_mien : idTotal, campana_crm : \'<?php if(isset($_GET[\'campana_crm\'])) { echo $_GET[\'campana_crm\']; } else { echo "0"; } ?>\' },
	        	dataType : \'html\',
	        	success : function(data){
	        		$("#tablaGestiones").html(\'\');
	        		$("#tablaGestiones").html(data);
	        	}

	    	});
        });
    }

    function seleccionar_registro(){
        //Seleccinar loos registros de la Lista de navegacion, 
        if ( $("#"+idTotal).length > 0) {
            $("#"+idTotal).click();   
            $("#"+idTotal).addClass(\'active\'); 
            idTotal = 0;
        }else{
            $(".CargarDatos :first").click();
        } 
        '.$functionDescargarFinal.'
    } 

    <?php } ?>


    '.$funcionCargarFinal.'

    function vamosRecargaLasGrillasPorfavor(id){
        '.$functionLlamarAloshijosLuegoDeCargar.'
    }
</script>
<script type="text/javascript">
	$(document).ready(function() {
		<?php
            if(isset($campSql)){
                //recorro la campaña para tener los datos que necesito
                /*$resultcampSql = $mysqli->query($campSql);
                while($key = $resultcampSql->fetch_object()){
                    

                    //consulta de datos del usuario
                    $DatosSql = " SELECT ".$key->CAMINC_NomCamPob_b." as campo FROM ".$BaseDatos.".G".$tabla." WHERE G".$tabla."_ConsInte__b=".$_GET[\'user\'];

                    //echo $DatosSql;
                    //recorro la tabla de donde necesito los datos
                    $resultDatosSql = $mysqli->query($DatosSql);
                    if($resultDatosSql){
                		while($objDatos = $resultDatosSql->fetch_object()){ ?>
                        	document.getElementById("<?=$key->CAMINC_NomCamGui_b;?>").value = \'<?=trim($objDatos->campo);?>\';
             		<?php  
             			}	
                    }
                    
                } */  
            }
        ?>
    	<?php if(isset($_GET[\'user\'])){ ?>
        	'.$functionLlamarAloshijosLuegoDeCargar.'
        	idTotal = <?php echo $_GET[\'user\'];?>; 
        <?php } ?>
		
	});
</script>';

		    fputs($fp , $datoJavascript); 
		    fputs($fp , chr(13).chr(10)); // Genera saldo de linea    
		    fclose($fp);

		    $nombre_fichero = $carpeta."/".$guion."_eventos.js";
    		if (!file_exists($nombre_fichero)) {
        		$fjs = fopen($carpeta."/".$guion."_eventos.js", "w");
        		$nuewJs = '$(function(){'.$funciones_js.' '."\n".'});';
        		$nuewJs .= "\n".'
function before_save(){ return true; }'."\n".'
function after_save(){}'."\n".'
function after_save_error(){}';
    			$nuewJs .= "\n".'
function before_edit(){}'."\n".'
function after_edit(){}';
    			$nuewJs .= "\n".'
function before_add(){}'."\n".'
function after_add(){}';
    			$nuewJs .= "\n".'
function before_delete(){}'."\n".'
function after_delete(){}'."\n".'
function after_delete_error(){}';
        		fputs($fjs, $nuewJs);
    			fclose($fjs);
			}//cierro el if !file_exists($nombre_fichero)

			$nombre_fichero2 = $carpeta."/".$guion."_extender_funcionalidad.php";
    		if (!file_exists($nombre_fichero2)) {
        		$fjss = fopen($carpeta."/".$guion."_extender_funcionalidad.php", "w");
        		$nuewJss = '<?php';
        		$nuewJss .= "\n".'
    include(__DIR__."/../../conexion.php");';
        		$nuewJss .= "\n".'
    //Este archivo es para agregar funcionalidades al G, y que al momento de generar de nuevo no se pierdan';
        		$nuewJss .= "\n".'
    //Cosas como nuevas consultas, nuevos Inserts, Envio de correos, etc, en fin extender mas los formularios en PHP';
        		$nuewJss .= "\n".'
    
        		';
        		$nuewJss .= "\n".'?>';
        		fputs($fjss, $nuewJss);
        		fclose($fjss);
    		}//cierro el if de !file_exists($nombre_fichero2)	

    		//Este es el crud
    		$fcrud = fopen($carpeta."/".$guion."_CRUD.php" , "w");
    		//chmod($carpeta."/".$guion."_CRUD.php" , 0777);

    		//Esta consulta la hago para los campos del select
    		$campos_4 = $mysqli->query($Lsql);
    		$camposconsulta12 = '';
    		$camposconsulta1 = '
            $Lsql = \'SELECT '.$guion.'_ConsInte__b, '.$guion.'_FechaInsercion , '.$guion.'_Usuario ,  '.$guion.'_CodigoMiembro  , '.$guion.'_PoblacionOrigen , '.$guion.'_EstadoDiligenciamiento ,  '.$guion.'_IdLlamada , '.$guion_c.$GUION__ConsInte__PREGUN_Pri_b.' as principal ';
    		$camposconsulta12 = $camposconsulta1;
    		$joins = '';
        	$alfa = 0;
    		$camposGrid = '';
    		$horas = 0;
    		while($key = $campos_4->fetch_object()){

      			if($key->tipo_Pregunta != 9){
        			$camposconsulta1 .= ','.$guion_c.$key->id;
        
        			if($key->tipo_Pregunta == '5'){
            			$camposGrid .= ', explode(\' \', $fila->'.$guion_c.$key->id.')[0] ';
        			}else if($key->tipo_Pregunta == '10'){
            			$camposGrid .= ', $hora_'.$alfabeto[$horas].' ';
            			$horas++;
        			}else if($key->tipo_Pregunta =='11'){
            			$CampoSql = "SELECT * FROM ".$BaseDatos_systema.".PREGUI WHERE PREGUI_ConsInte__PREGUN_b = ".$key->id." ORDER BY PREGUI_ConsInte__b ASC LIMIT 0, 1 ";
            			$campoSqlE = $mysqli->query($CampoSql);

            			while ($cam = $campoSqlE->fetch_object()) {
                			//Consulto por el id del campo que necesitamos y se repite las veces que sean necesarias
                			$campoObtenidoSql = 'SELECT * FROM '.$BaseDatos_systema.'.CAMPO_ WHERE CAMPO__ConsInte__b = '.$cam->PREGUI_ConsInte__CAMPO__b;
                			//echo $campoObtenidoSql;
                			$resultCamposObtenidos = $mysqli->query($campoObtenidoSql);

                			while ($o = $resultCamposObtenidos->fetch_object()) {
                     			$camposGrid .= ', ($fila->'.$o->CAMPO__Nombre____b.') ';
                			}//cierro este While $o = $resultCamposObtenidos->fetch_object()
            			}//While $cam = $campoSqlE->fetch_object()

			        }else{
			            $camposGrid .= ', ($fila->'.$guion_c.$key->id.') ';
			        }

        			if($key->tipo_Pregunta == '6'){
          				$camposconsulta12 .= ', '.$alfabeto[$alfa].'.LISOPC_Nombre____b as '.$guion_c.$key->id;
          				$joins .= ' LEFT JOIN \'.$BaseDatos_systema.\'.LISOPC as '.$alfabeto[$alfa].' ON '.$alfabeto[$alfa].'.LISOPC_ConsInte__b =  '.$guion_c.$key->id;
          				$alfa++;
        			}else if($key->tipo_Pregunta =='11'){
            			$CampoSql = "SELECT * FROM ".$BaseDatos_systema.".PREGUI WHERE PREGUI_ConsInte__PREGUN_b = ".$key->id." ORDER BY PREGUI_ConsInte__b ASC LIMIT 0, 1 ";
            			$campoSqlE = $mysqli->query($CampoSql);

            			while ($cam = $campoSqlE->fetch_object()) {
               				//Consulto por el id del campo que necesitamos y se repite las veces que sean necesarias
                			$campoObtenidoSql = 'SELECT * FROM '.$BaseDatos_systema.'.CAMPO_ WHERE CAMPO__ConsInte__b = '.$cam->PREGUI_ConsInte__CAMPO__b;
                			//echo $campoObtenidoSql;
                			$resultCamposObtenidos = $mysqli->query($campoObtenidoSql);

                			while ($o = $resultCamposObtenidos->fetch_object()) {
                    			$camposconsulta12 .= ', '.$o->CAMPO__Nombre____b;
                			}//While $o = $resultCamposObtenidos->fetch_object()
            			}//While $cam = $campoSqlE->fetch_object()
            
            			$joins .= ' LEFT JOIN \'.$BaseDatos.\'.G'.$key->guion.' ON G'.$key->guion.'_ConsInte__b  =  '.$guion_c.$key->id;
        			}else{
            			$camposconsulta12 .= ','.$guion_c.$key->id;
        			}
      			}//cierro este if $key->tipo_Pregunta != 9
    		}//cierro este while $key = $campos_4->fetch_object()

    		$camposconsulta1 .= ' FROM \'.$BaseDatos.\'.'.$guion;
    		$camposconsulta12 .= ' FROM \'.$BaseDatos.\'.'.$guion;

    		$camposconsulta1 .= ' WHERE '.$guion.'_ConsInte__b =\'.$_POST[\'id\'];';

    		$LsqlHora = "SELECT PREGUN_Texto_____b as titulo_pregunta , PREGUN_Tipo______b as tipo_Pregunta, PREGUN_ConsInte__b as id , PREGUN_ConsInte__GUION__PRE_B as guion, PREGUN_ConsInte__OPCION_B as lista, PREGUN_OrdePreg__b FROM ".$BaseDatos_systema.".PREGUN JOIN ".$BaseDatos_systema.".SECCIO ON SECCIO_ConsInte__b = PREGUN_ConsInte__SECCIO_b WHERE PREGUN_ConsInte__GUION__b = ".$id_a_generar." AND PREGUN_Tipo______b = 10   ORDER BY PREGUN_OrdePreg__b";


    		$esHora = $mysqli->query($LsqlHora);
    		$variablesDeLahora = '';
    		$horas = 0;
    		while ($key = $esHora->fetch_object()) {
      			$variablesDeLahora .= "\n".'
                $hora_'.$alfabeto[$horas].' = \'\';
                //esto es para todo los tipo fecha, para que no muestre la parte de la hora
                if(!is_null($fila->'.$guion_c.$key->id.')){
                    $hora_'.$alfabeto[$horas].' = explode(\' \', $fila->'.$guion_c.$key->id.')[1];
                }';
      			$horas++;
    		}//cierro este while $key = $esHora->fetch_object()




    		/* busqueda */
    		$str_Lsql = "SELECT PREGUN_Texto_____b as titulo_pregunta , PREGUN_Tipo______b as tipo_Pregunta, PREGUN_ConsInte__b as id , PREGUN_ConsInte__GUION__PRE_B as guion, PREGUN_ConsInte__OPCION_B as lista, PREGUN_OrdePreg__b , PREGUN_NumeMini__b as minimoNumero, PREGUN_NumeMaxi__b as maximoNumero, PREGUN_FechMini__b as fechaMinimo, PREGUN_FechMaxi__b as fechaMaximo , PREGUN_HoraMini__b as horaMini , PREGUN_HoraMaxi__b as horaMaximo, PREGUN_TexErrVal_b as error FROM ".$BaseDatos_systema.".PREGUN WHERE PREGUN_ConsInte__GUION__b = ".$id_a_generar." AND PREGUN_IndiBusc__b != 0 ORDER BY PREGUN_OrdePreg__b";

    		$respuestaBusqueda = $mysqli->query($str_Lsql);
    		$where = '';
    		while ($keyBusqueda = $respuestaBusqueda->fetch_object()) {
    			$where .= '
			if(!is_null($_POST[\''.$guion_c.$keyBusqueda->id.'\']) && $_POST[\''.$guion_c.$keyBusqueda->id.'\'] != \'\'){
				$Lsql .= " AND '.$guion_c.$keyBusqueda->id.' LIKE \'%". $_POST[\''.$guion_c.$keyBusqueda->id.'\'] ."%\' ";
			}';
    		}


    		$crud = '<?php
    session_start();
    ini_set(\'display_errors\', \'On\');
	ini_set(\'display_errors\', 1);
    include(__DIR__."/../../conexion.php");
    include(__DIR__."/../../funciones.php");
    date_default_timezone_set(\'America/Bogota\');
    if (!empty($_SERVER[\'HTTP_X_REQUESTED_WITH\']) && strtolower($_SERVER[\'HTTP_X_REQUESTED_WITH\']) == \'xmlhttprequest\') {
      //Datos del formulario
      	if(isset($_POST[\'CallDatos\'])){
          '.$camposconsulta1.'
            $result = $mysqli->query($Lsql);
            $datos = array();
            $i = 0;

            while($key = $result->fetch_object()){'."\n";
			$campos_5 = $mysqli->query($Lsql);
			while($key = $campos_5->fetch_object()){
    			if($key->tipo_Pregunta == '10'){
        			$crud .= '  
                $hora = \'\';
                if(!is_null($key->'.$guion_c.$key->id.')){
                    $hora = explode(\' \', $key->'.$guion_c.$key->id.')[1];
                }'."\n";
    			}else if($key->tipo_Pregunta == '5'){
        			$crud .= '
                $datos[$i][\''.$guion_c.$key->id.'\'] = explode(\' \', $key->'.$guion_c.$key->id.')[0];'."\n";
    			}else if($key->tipo_Pregunta != '9'){
        			$crud .= '
                $datos[$i][\''.$guion_c.$key->id.'\'] = $key->'.$guion_c.$key->id.';'."\n";
    			}//cierro $key->tipo_Pregunta == '10'
			}//cierro el While $key = $campos_5->fetch_object()

			$crud .= '      
				$datos[$i][\'principal\'] = $key->principal;
                $i++;
            }
            echo json_encode($datos);
        }


        /*Esto es para traer el historico del BackOffice*/
        if(isset($_POST[\'DameHistorico\'])){

        	if(isset($_POST[\'campana_crm\']) && $_POST[\'campana_crm\'] != 0){
        		$Lsql_Campan = "SELECT CAMPAN_ConsInte__GUION__Pob_b, CAMPAN_ConsInte__MUESTR_b, CAMPAN_ConsInte__GUION__Gui_b, CAMPAN_ConfDinam_b FROM ".$BaseDatos_systema.".CAMPAN WHERE CAMPAN_ConsInte__b = ".$_POST[\'campana_crm\'];
	            $res_Lsql_Campan = $mysqli->query($Lsql_Campan);
	            $datoCampan = $res_Lsql_Campan->fetch_array();
	            $int_Pobla_Camp_2 = $datoCampan[\'CAMPAN_ConsInte__GUION__Pob_b\'];
	            $int_Muest_Campan = $datoCampan[\'CAMPAN_ConsInte__MUESTR_b\'];
	            $int_Guion_Campan = $datoCampan[\'CAMPAN_ConsInte__GUION__Gui_b\'];


	            $Lsql = "SELECT * FROM ".$BaseDatos_systema.".CONDIA_BACKOFFICE JOIN ".$BaseDatos_systema.".USUARI ON CONDIA_ConsInte__USUARI_b = USUARI_ConsInte__b LEFT JOIN ".$BaseDatos_systema.".MONOEF ON CONDIA_ConsInte__MONOEF_b = MONOEF_ConsInte__b WHERE CONDIA_ConsInte__CAMPAN_b = ".$_POST[\'campana_crm\']." AND CONDIA_ConsInte__GUION__Gui_b = ".$int_Guion_Campan." AND CONDIA_ConsInte__GUION__Pob_b = ".$int_Pobla_Camp_2." AND CONDIA_ConsInte__MUESTR_b = ".$int_Muest_Campan." AND CONDIA_CodiMiem__b = ".$_POST[\'user_codigo_mien\']." ORDER BY CONDIA_Fecha_____b DESC LIMIT 5;";
	            $res = $mysqli->query($Lsql);
	            while($key = $res->fetch_object()){
	                echo "<tr>";
	                echo "<td>".$key->MONOEF_Texto_____b."</td>";
	                echo "<td>".$key->CONDIA_Observacio_b."</td>";
	                echo "<td>".$key->CONDIA_Fecha_____b."</td>";
	                echo "<td>".$key->USUARI_Nombre____b."</td>";
	                echo "</tr>";
	            }
        	}
            
        }


        //Datos de la lista de la izquierda
        if(isset($_POST[\'CallDatosJson\'])){
            $Lsql = "SELECT '.$guion.'_ConsInte__b as id,  '.$camposTabla.' , LISOPC_Nombre____b as estado FROM ".$BaseDatos.".'.$guion.' JOIN ".$BaseDatos_systema.".LISOPC ON LISOPC_ConsInte__b = '.$guion_c.$GUION__ConsInte__PREGUN_Rep_b.' '.$joinsTabla.' WHERE '.$guion.'_ConsInte__b IS NOT NULL ";
            if($_POST[\'Busqueda\'] != \'\' && !is_null($_POST[\'Busqueda\'])){
                $Lsql .= " AND ( '.$ordenTabla.' like \'%".$_POST[\'Busqueda\']."%\' ";
                $Lsql .= " OR '.$campTabla.' like \'%".$_POST[\'Busqueda\']."%\' ) ";
            }

            '.$where.'

            $Lsql .= " ORDER BY '.$guion.'_ConsInte__b DESC LIMIT 0, 50 "; 
            $result = $mysqli->query($Lsql);
            $datos = array();
            $i = 0;
            while($key = $result->fetch_object()){

            	$color = \'\';
                $strIconoBackOffice = \'\';
                if($key->estado == \'En gestión\' || $key->estado == \'En gestión por devolución\'){
                    $color = \'color:blue;\';
                    $strIconoBackOffice = \'1\';
                }else if($key->estado == \'Cerrada\'){
                    $color = \'color:green;\';
                    $strIconoBackOffice = \'2\';
                }else if($key->estado == \'Devuelta\'){
                    $color = \'color:red;\';
                    $strIconoBackOffice = \'3\';
                }

                $datos[$i][\'camp1\'] = mb_strtoupper(($key->camp1));
                $datos[$i][\'camp2\'] = mb_strtolower(($key->camp2));
                $datos[$i][\'estado\'] = $strIconoBackOffice;
                $datos[$i][\'color\'] = $color;
                $datos[$i][\'id\'] = $key->id;
                $i++;
            }
            echo json_encode($datos);
        }

        if(isset($_POST[\'getListaHija\'])){
	    	$Lsql = "SELECT LISOPC_ConsInte__b , LISOPC_Nombre____b FROM ".$BaseDatos_systema.".LISOPC WHERE LISOPC_ConsInte__LISOPC_Depende_b = ".$_POST[\'idPadre\']." AND LISOPC_ConsInte__OPCION_b = ".$_POST[\'opcionID\']." ORDER BY LISOPC_Nombre____b ASC";
	    	$res = $mysqli->query($Lsql);
	    	echo "<option value=\'0\'>Seleccione</option>";
	    	while($key = $res->fetch_object()){
	    		echo "<option value=\'".$key->LISOPC_ConsInte__b."\'>".$key->LISOPC_Nombre____b."</option>";
	    	}
	    }


        //Esto ya es para cargar los combos en la grilla

        if(isset($_GET[\'CallDatosLisop_\'])){
            $lista = $_GET[\'idLista\'];
            $comboe = $_GET[\'campo\'];
            $Lsql = "SELECT LISOPC_ConsInte__b AS OPCION_ConsInte__b, LISOPC_Nombre____b AS OPCION_Nombre____b FROM ".$BaseDatos_systema.".LISOPC WHERE LISOPC_ConsInte__OPCION_b = ".$lista." ORDER BY LISOPC_Nombre____b";
            
            $combo = $mysqli->query($Lsql);
            echo \'<select class="form-control input-sm"  name="\'.$comboe.\'" id="\'.$comboe.\'">\';
            echo \'<option value="0">Seleccione</option>\';
            while($obj = $combo->fetch_object()){
                echo "<option value=\'".$obj->OPCION_ConsInte__b."\'>".$obj->OPCION_Nombre____b."</option>";
            }   
            echo \'</select>\'; 
        } 

        '.$funcionesCampoGuion.'


        // esto carga los datos de la grilla CallDatosJson
        if(isset($_GET[\'CallDatosJson\'])){
            $page = $_POST[\'page\'];  // Almacena el numero de pagina actual
            $limit = $_POST[\'rows\']; // Almacena el numero de filas que se van a mostrar por pagina
            $sidx = $_POST[\'sidx\'];  // Almacena el indice por el cual se hará la ordenación de los datos
            $sord = $_POST[\'sord\'];  // Almacena el modo de ordenación
            if(!$sidx) $sidx =1;
            //Se hace una consulta para saber cuantos registros se van a mostrar
            $result = $mysqli->query("SELECT COUNT(*) AS count FROM ".$BaseDatos.".'.$guion.'");
            // Se obtiene el resultado de la consulta
            $fila = $result->fetch_array();
            $count = $fila[\'count\'];
            //En base al numero de registros se obtiene el numero de paginas
            if( $count >0 ) {
                $total_pages = ceil($count/$limit);
            } else {
                $total_pages = 0;
            }
            if ($page > $total_pages)
                $page=$total_pages;

            //Almacena numero de registro donde se va a empezar a recuperar los registros para la pagina
            $start = $limit*$page - $limit; 
            //Consulta que devuelve los registros de una sola pagina'."\n".$camposconsulta12.$joins.'\';
            if ($_REQUEST["_search"] == "false") {
                $where = " where 1";
            } else {
                $operations = array(
                    \'eq\' => "= \'%s\'",            // Equal
                    \'ne\' => "<> \'%s\'",           // Not equal
                    \'lt\' => "< \'%s\'",            // Less than
                    \'le\' => "<= \'%s\'",           // Less than or equal
                    \'gt\' => "> \'%s\'",            // Greater than
                    \'ge\' => ">= \'%s\'",           // Greater or equal
                    \'bw\' => "like \'%s%%\'",       // Begins With
                    \'bn\' => "not like \'%s%%\'",   // Does not begin with
                    \'in\' => "in (\'%s\')",         // In
                    \'ni\' => "not in (\'%s\')",     // Not in
                    \'ew\' => "like \'%%%s\'",       // Ends with
                    \'en\' => "not like \'%%%s\'",   // Does not end with
                    \'cn\' => "like \'%%%s%%\'",     // Contains
                    \'nc\' => "not like \'%%%s%%\'", // Does not contain
                    \'nu\' => "is null",           // Is null
                    \'nn\' => "is not null"        // Is not null
                ); 
                $value = $mysqli->real_escape_string($_REQUEST["searchString"]);
                $where = sprintf(" where %s ".$operations[$_REQUEST["searchOper"]], $_REQUEST["searchField"], $value);
            }
            $Lsql .= $where.\' ORDER BY \'.$sidx.\' \'.$sord.\' LIMIT \'.$start.\',\'.$limit;
            $result = $mysqli->query($Lsql);
            $respuesta = array();
            $respuesta[\'page\'] = $page;
            $respuesta[\'total\'] = $total_pages;
            $respuesta[\'records\'] = $count;
            $i=0;
            while( $fila = $result->fetch_object() ) {  
                '.$variablesDeLahora.'
                $respuesta[\'rows\'][$i][\'id\']=$fila->'.$guion.'_ConsInte__b;
                $respuesta[\'rows\'][$i][\'cell\']=array($fila->'.$guion.'_ConsInte__b '.$camposGrid.');
                $i++;
            }
            // La respuesta se regresa como json
            echo json_encode($respuesta);
        }

        if(isset($_POST[\'CallEliminate\'])){
            if($_POST[\'oper\'] == \'del\'){
                $Lsql = "DELETE FROM ".$BaseDatos.".'.$guion.' WHERE '.$guion.'_ConsInte__b = ".$_POST[\'id\'];
                if ($mysqli->query($Lsql) === TRUE) {
                    echo "1";
                } else {
                    echo "Error eliminado los registros : " . $mysqli->error;
                }
            }
        }

        if(isset($_POST[\'callDatosNuevamente\'])){
            $inicio = $_POST[\'inicio\'];
            $fin = $_POST[\'fin\'];
            $Zsql = \'SELECT  '.$guion.'_ConsInte__b as id,  '.$camposTabla.'  FROM \'.$BaseDatos.\'.'.$guion.' ORDER BY '.$guion.'_ConsInte__b DESC LIMIT \'.$inicio.\' , \'.$fin;
            $result = $mysqli->query($Zsql);
            while($obj = $result->fetch_object()){
                echo "<tr class=\'CargarDatos\' id=\'".$obj->id."\'>
                    <td>
                        <p style=\'font-size:14px;\'><b>".strtoupper(($obj->camp1))."</b></p>
                        <p style=\'font-size:12px; margin-top:-10px;\'>".strtoupper(($obj->camp2))."</p>
                    </td>
                </tr>";
            } 
        }
              
        //Inserciones o actualizaciones
        if(isset($_POST["oper"]) && isset($_GET[\'insertarDatosGrilla\'])){
            $Lsql  = \'\';

            $validar = 0;
            $LsqlU = "UPDATE ".$BaseDatos.".'.$guion.' SET "; '."\n";

            $campos_7 = $mysqli->query($Lsql);

			while ($key = $campos_7->fetch_object()) {
				if(	$key->id == $GUION__ConsInte__PREGUN_Tip_b ){
					$crud .= ' 
            $'.$guion_c.$key->id.' = NULL;
            //este es de tipo date hay que preguntar si esta vacia o no
            if(isset($_POST["tipificacion"])){    
                if($_POST["tipificacion"] != \'\'){
                    $separador = "";
                    if($validar == 1){
                        $separador = ",";
                    }
                    $'.$guion_c.$key->id.' = str_replace(\' \', \'\',$_POST["tipificacion"]);
                    $LsqlU .= $separador." '.$guion_c.$key->id.' = ".$'.$guion_c.$key->id.';
                    $validar = 1;

                    
                }
            }'."\n";
				}//cierro el if $key->id == $GUION__ConsInte__PREGUN_Tip_b

				if(	$key->id == $GUION__ConsInte__PREGUN_Rep_b ){
					$crud .= ' 
            $'.$guion_c.$key->id.' = NULL;
            //este es de tipo date hay que preguntar si esta vacia o no
            if(isset($_POST["reintento"])){    
                if($_POST["reintento"] != \'\'){
                    $separador = "";
                    if($validar == 1){
                        $separador = ",";
                    }
                    $'.$guion_c.$key->id.' = str_replace(\' \', \'\',$_POST["reintento"]);
                    $LsqlU .= $separador." '.$guion_c.$key->id.' = ".$'.$guion_c.$key->id.';
                    $validar = 1;
                }
            }'."\n";
				}//Cierro el if $key->id == $GUION__ConsInte__PREGUN_Rep_b

				
				if(	$key->id == $GUION__ConsInte__PREGUN_Com_b ){
					$crud .= ' 
            $'.$guion_c.$key->id.' = NULL;
            //este es de tipo date hay que preguntar si esta vacia o no
            if(isset($_POST["textAreaComentarios"])){    
                if($_POST["textAreaComentarios"] != \'\'){
                    $separador = "";
                    if($validar == 1){
                        $separador = ",";
                    }
                    $'.$guion_c.$key->id.' = "\'".$_POST["textAreaComentarios"]."\'";
                    $LsqlU .= $separador." '.$guion_c.$key->id.' = ".$'.$guion_c.$key->id.';
                    $validar = 1;
                }
            }'."\n";
				}//cierro el if de $key->id == $GUION__ConsInte__PREGUN_Com_b


				if(	$key->id != $GUION__ConsInte__PREGUN_Tip_b && 
					$key->id != $GUION__ConsInte__PREGUN_Rep_b &&
					$key->id != $GUION__ConsInte__PREGUN_Fag_b && 
					$key->id != $GUION__ConsInte__PREGUN_Hag_b &&
					$key->id != $GUION__ConsInte__PREGUN_Com_b){
			
					$valorPordefecto = $key->PREGUN_Default___b;				


						if($key->tipo_Pregunta == 5){ // tipo fecha
    						$crud .= ' 
            $'.$guion_c.$key->id.' = NULL;
            //este es de tipo date hay que preguntar si esta vacia o no
            if(isset($_POST["'.$guion_c.$key->id.'"])){    
                if($_POST["'.$guion_c.$key->id.'"] != \'\'){
                    $separador = "";
                    if($validar == 1){
                        $separador = ",";
                    }

                    $tieneHora = explode(\' \' , $_POST["'.$guion_c.$key->id.'"]);
                    if(count($tieneHora) > 1){
                    	$'.$guion_c.$key->id.' = "\'".$_POST["'.$guion_c.$key->id.'"]."\'";
                    }else{
                    	$'.$guion_c.$key->id.' = "\'".str_replace(\' \', \'\',$_POST["'.$guion_c.$key->id.'"])." 00:00:00\'";
                    }


                    $LsqlU .= $separador." '.$guion_c.$key->id.' = ".$'.$guion_c.$key->id.';
                    $validar = 1;
                }
            }'."\n";
  						}else if($key->tipo_Pregunta == 10){ // tipo timer
    						$crud .= '  
            $'.$guion_c.$key->id.' = NULL;
            //este es de tipo date hay que preguntar si esta vacia o no o esta undefined
            if(isset($_POST["'.$guion_c.$key->id.'"])){   
                if($_POST["'.$guion_c.$key->id.'"] != \'\' && $_POST["'.$guion_c.$key->id.'"] != \'undefined\' && $_POST["'.$guion_c.$key->id.'"] != \'null\'){
                    $separador = "";
                    $fecha = date(\'Y-m-d\');
                    if($validar == 1){
                        $separador = ",";
                    }

                    $'.$guion_c.$key->id.' = "\'".$fecha." ".str_replace(\' \', \'\',$_POST["'.$guion_c.$key->id.'"])."\'";
                    $LsqlU .= $separador." '.$guion_c.$key->id.' = ".$'.$guion_c.$key->id.'."";
                    $validar = 1;
                }
            }'."\n";
  						}else if($key->tipo_Pregunta == 3){ // tipo Entero
   	 						$crud .= '  
            $'.$guion_c.$key->id.' = NULL;
            //este es de tipo numero no se deja ir asi \'\', si est avacio lo mejor es no mandarlo
            if(isset($_POST["'.$guion_c.$key->id.'"])){
                if($_POST["'.$guion_c.$key->id.'"] != \'\'){
                    $separador = "";
                    if($validar == 1){
                        $separador = ",";
                    }

                    $'.$guion_c.$key->id.' = $_POST["'.$guion_c.$key->id.'"];
                    $LsqlU .= $separador." '.$guion_c.$key->id.' = ".$'.$guion_c.$key->id.'."";
                    $validar = 1;
                }
            }'."\n";
  						}else if($key->tipo_Pregunta == 4){ // tipo Decimal
    						$crud .= '  
            $'.$guion_c.$key->id.' = NULL;
            //este es de tipo numero no se deja ir asi \'\', si est avacio lo mejor es no mandarlo
            if(isset($_POST["'.$guion_c.$key->id.'"])){
                if($_POST["'.$guion_c.$key->id.'"] != \'\'){
                    $separador = "";
                    if($validar == 1){
                        $separador = ",";
                    }

                    $'.$guion_c.$key->id.' = $_POST["'.$guion_c.$key->id.'"];
                    $LsqlU .= $separador." '.$guion_c.$key->id.' = ".$'.$guion_c.$key->id.'."";
                    $validar = 1;
                }
            }'."\n";

  						}else if($key->tipo_Pregunta == 8){ // tipo Check
    						$crud .= '  
            $'.$guion_c.$key->id.' = 0;
            //este es tipo check primeo si viene y de acuerdo a su valor se pone 1 o 0
            if(isset($_POST["'.$guion_c.$key->id.'"])){
                if($_POST["'.$guion_c.$key->id.'"] == \'Yes\'){
                    $'.$guion_c.$key->id.' = 1;
                }else if($_POST["'.$guion_c.$key->id.'"] == \'off\'){
                    $'.$guion_c.$key->id.' = 0;
                }else if($_POST["'.$guion_c.$key->id.'"] == \'on\'){
                    $'.$guion_c.$key->id.' = 1;
                }else if($_POST["'.$guion_c.$key->id.'"] == \'No\'){
                    $'.$guion_c.$key->id.' = 1;
                }else{
                    $'.$guion_c.$key->id.' = $_POST["'.$guion_c.$key->id.'"] ;
                }   

                $separador = "";
                if($validar == 1){
                    $separador = ",";
                }

                $LsqlU .= $separador." '.$guion_c.$key->id.' = ".$'.$guion_c.$key->id.'."";

                $validar = 1;
            }'."\n";

  						}else{ // tipos norrmales
    
    						$crud .= '  
            if(isset($_POST["'.$guion_c.$key->id.'"])){
                $separador = "";
                if($validar == 1){
                    $separador = ",";
                }

                $LsqlU .= $separador."'.$guion_c.$key->id.' = \'".$_POST["'.$guion_c.$key->id.'"]."\'";
                $validar = 1;
            }
             '."\n";
    
    
						}//Cierro el if de los tipos de preguntas

					}else{ // cierro el if de $key->PREGUN_ContAcce__b != 2
						switch ($valorPordefecto) {
							case '501':
								$crud .= ' 
            $'.$guion_c.$key->id.' = NULL;
            //este es de tipo date hay que preguntar si esta vacia o no
            $separador = "";
            if($validar == 1){
                $separador = ",";
            }
            $'.$guion_c.$key->id.' = "\'".date(\'Y-m-d H:i:s\')."\'";
            $LsqlU .= $separador." '.$guion_c.$key->id.' = ".$'.$guion_c.$key->id.';
            $validar = 1;
           '."\n";					
							break;

							case '1001':
								$crud .= ' 
            $'.$guion_c.$key->id.' = NULL;
            //este es de tipo date hay que preguntar si esta vacia o no
            $separador = "";
            if($validar == 1){
                $separador = ",";
            }
            $'.$guion_c.$key->id.' = "\'".date(\'Y-m-d H:i:s\')."\'";
            $LsqlU .= $separador." '.$guion_c.$key->id.' = ".$'.$guion_c.$key->id.';
            $validar = 1;
           '."\n";	
							break;

							case '102':
								$crud .= '  
                $separador = "";
                if($validar == 1){
                    $separador = ",";
                }

                $LsqlU .= $separador."'.$guion_c.$key->id.' = \'".getNombreUser($_GET[\'token\'])."\'";
                $validar = 1;
            
             '."\n";
							break;
						
							case '105':
								$crud .= '  
                $separador = "";
                if($validar == 1){
                    $separador = ",";
                }

                $cmapa = "SELECT CAMPAN_Nombre____b FROM ".$BaseDatos_systema.".CAMPAN WHERE CAMPAN_ConsInte__b = ".$_POST["campana_crm"];
                $resCampa = $mysqli->query($cmapa);
                $dataCampa = $resCampa->fetch_array();
                $LsqlU .= $separador."'.$guion_c.$key->id.' = \'".$dataCampa["CAMPAN_Nombre____b"]."\'";
                $validar = 1;
            
             '."\n";
							break;

							case '3001':

								$crud .= '  
                $separador = "";
                if($validar == 1){
                    $separador = ",";
                }

                $LsqlU .= $separador."'.$guion_c.$key->id.' = \''. $key->PREGUN_DefaNume__b.'\'";
                $validar = 1;
            
             '."\n";

	                        break;

                   			case '3002':

	                    	//Es el autonumerico
	                      	$crud .= '  
                if(isset($_POST["'.$guion_c.$key->id.'"])){    
	                if($_POST["'.$guion_c.$key->id.'"] != \'\'){
	                    $separador = "";
	                    if($validar == 1){
	                        $separador = ",";
	                    }
	                    $'.$guion_c.$key->id.' = "\'".$_POST["'.$guion_c.$key->id.'"]."\'";
	                    $LsqlU .= $separador." '.$guion_c.$key->id.' = ".$'.$guion_c.$key->id.';
	                    $validar = 1;
	                }
	            }'."\n";
	                        
	                        break;

							default:

							break;
						}//cierro el switch ($valorPordefecto) {

  		  	
  				}//cierro el if de 	$key->id != $GUION__ConsInte__PREGUN_Tip_b && $key->id != $GUION__ConsInte__PREGUN_Rep_b && $key->id != $GUION__ConsInte__PREGUN_Fag_b && 					$key->id != $GUION__ConsInte__PREGUN_Hag_b && 					$key->id != $GUION__ConsInte__PREGUN_Com_b
			}//cierro el while de $key = $campos_7->fetch_object()

			$crud .= '
			if(isset($_GET[\'id_gestion_cbx\'])){
				$separador = "";
                if($validar == 1){
                    $separador = ",";
                }

                $LsqlU .= $separador."'.$guion.'_IdLlamada = \'".$_GET[\'id_gestion_cbx\']."\'";
                $validar = 1;
			}';

            
       
			$Lsql_Tipo = "SELECT GUION__Tipo______b FROM ".$BaseDatos_systema.".GUION_ WHERE GUION__ConsInte__b = ".$id_a_generar;
			$res_Lsql_Tipo = $mysqli->query($Lsql_Tipo);
			$datoArray = $res_Lsql_Tipo->fetch_array();

				$crud .= '
			if(isset($_POST[\'oper\'])){
                $Lsql = $LsqlU." WHERE '.$guion.'_ConsInte__b =".$_POST["id"]; 
            }';
			

            $crud .= '
            //si trae algo que insertar inserta

            //echo $Lsql;
            if($validar == 1){
                if ($mysqli->query($Lsql) === TRUE) {

                	if(isset($_POST["campana_crm"]) && $_POST["campana_crm"] != 0){
                		/*Ahor ainsertamos en CONDIA BACKOFICE*/
	                    $Lsql_Campan = "SELECT CAMPAN_ConsInte__GUION__Pob_b, CAMPAN_ConsInte__MUESTR_b, CAMPAN_ConsInte__GUION__Gui_b , CAMPAN_ActPobGui_b FROM ".$BaseDatos_systema.".CAMPAN WHERE CAMPAN_ConsInte__b = ".$_POST["campana_crm"];
	                    //echo $Lsql_Campan;
	                    $res_Lsql_Campan = $mysqli->query($Lsql_Campan);
	                    $datoCampan = $res_Lsql_Campan->fetch_array();
	                    $str_Pobla_Campan = "G".$datoCampan[\'CAMPAN_ConsInte__GUION__Pob_b\'];
	                    $int_Pobla_Camp_2 = $datoCampan[\'CAMPAN_ConsInte__GUION__Pob_b\'];
	                    $int_Muest_Campan = $datoCampan[\'CAMPAN_ConsInte__MUESTR_b\'];
	                    $int_Guion_Campan = $datoCampan[\'CAMPAN_ConsInte__GUION__Gui_b\'];
	                    $intActualiza_oNo = $datoCampan[\'CAMPAN_ActPobGui_b\']; 

	                    /* PARA SABER SI ACTUALIZAMOS O NO*/
	                    if($intActualiza_oNo == \'-1\'){
	                        /* toca hacer actualizacion desde Script */
	                        
	                        $campSql = "SELECT CAMINC_NomCamPob_b, CAMINC_NomCamGui_b FROM ".$BaseDatos_systema.".CAMINC WHERE CAMINC_ConsInte__CAMPAN_b = ".$_POST["campana_crm"];
	                        $resultcampSql = $mysqli->query($campSql);
	                        $Lsql = \'UPDATE \'.$BaseDatos.\'.\'.$str_Pobla_Campan.\' , \'.$BaseDatos.\'.G\'.$int_Guion_Campan.\' SET \';
	                        $i=0;
	                        while($key = $resultcampSql->fetch_object()){
	                            $validoparaedicion = false;
	                            $valorScript = $key->CAMINC_NomCamGui_b;

	                            $LsqlShow = "SHOW COLUMNS FROM ".$BaseDatos.".".$str_Pobla_Campan." WHERE Field = \'".$key->CAMINC_NomCamPob_b."\'";

	                            $resultShow = $mysqli->query($LsqlShow);
	                            if($resultShow->num_rows === 0){
	                                //comentario el campo no existe
	                                $validoparaedicion = false;
	                            }else{
	                                $validoparaedicion = true;
	                            } 

	                            $LsqlShow = "SHOW COLUMNS FROM ".$BaseDatos.".G".$int_Guion_Campan." WHERE Field = \'".$key->CAMINC_NomCamGui_b."\'";
	                            //echo $LsqlShow;
	                            $resultShow = $mysqli->query($LsqlShow);
	                            if($resultShow->num_rows === 0 ){
	                                //comentario el campo no existe
	                                $validoparaedicion = false;
	                            }else{
	                                $validoparaedicion = true;
	                            } 

	                            $LsqlPAsaNull = "SELECT ".$key->CAMINC_NomCamGui_b." as Campo_valido FROM ".$BaseDatos.".G".$int_Guion_Campan." WHERE  G".$int_Guion_Campan.\'_ConsInte__b = \'.$_POST["id"];
	                            $LsqlRes = $mysqli->query($LsqlPAsaNull);
	                            if($LsqlRes){
	                                $sata = $LsqlRes->fetch_array();
	                                if($sata[\'Campo_valido\'] != \'\' && $sata[\'Campo_valido\'] != null){

	                                }else{
	                                    $valorScript = \'NULL\';
	                                }
	                            }

	                            if($validoparaedicion){
	                                if($i == 0){
	                                    $Lsql .= $key->CAMINC_NomCamPob_b . \' = \'.$valorScript;
	                                }else{
	                                    $Lsql .= " , ".$key->CAMINC_NomCamPob_b . \' = \'.$valorScript;
	                                }
	                                $i++;    
	                            }
	                            
	                        } 
	                        $Lsql .= \' WHERE  G\'.$int_Guion_Campan.\'_ConsInte__b = \'.$_POST["id"].\' AND G\'.$int_Guion_Campan.\'_CodigoMiembro = \'.$str_Pobla_Campan.\'_ConsInte__b\'; 
	                        //echo "Esta ".$Lsql;
	                        if($mysqli->query($Lsql) === TRUE ){

	                        }else{
	                            echo "NO SE ACTALIZO LA BASE DE DATOS ".$mysqli->error;
	                        }
	                    }

	                    /* AHora toca insertar en Condia */
	                    $fecha_gestion = date(\'Y-m-d H:i:s\');
	                    $CondiaSql = "INSERT INTO ".$BaseDatos_systema.".CONDIA_BACKOFFICE (
	                        CONDIA_IndiEfec__b, 
	                        CONDIA_TipNo_Efe_b, 
	                        CONDIA_ConsInte__MONOEF_b, 
	                        CONDIA_TiemDura__b, 
	                        CONDIA_Fecha_____b, 
	                        CONDIA_ConsInte__CAMPAN_b, 
	                        CONDIA_ConsInte__USUARI_b, 
	                        CONDIA_ConsInte__GUION__Gui_b, 
	                        CONDIA_ConsInte__GUION__Pob_b, 
	                        CONDIA_ConsInte__MUESTR_b, 
	                        CONDIA_CodiMiem__b, 
	                        CONDIA_Observacio_b) 
	                        VALUES (
	                        \'".$_POST[\'tipificacion\']."\', 
	                        \'".$_POST[\'reintento\']."\',
	                        \'".$_POST[\'MonoEf\']."\',
	                        \'".$fecha_gestion."\',
	                        \'".$fecha_gestion."\',
	                        \'".$_POST["campana_crm"]."\',
	                        \'".$_SESSION[\'IDENTIFICACION\']."\',
	                        \'".$int_Guion_Campan."\',
	                        \'".$int_Pobla_Camp_2."\',
	                        \'".$int_Muest_Campan."\',
	                        \'".$_POST["id"]."\',
	                        \'".$_POST[\'textAreaComentarios\']."\'
	                    )";

	                    echo $CondiaSql;
	                    if($mysqli->query($CondiaSql) === true){

	                    }else{
	                        echo "Error insertando Condia => ".$mysqli->error;
	                    }

	                    include \'../funcion_Devolver_tarea.php\';
                    	devolverTarea($int_Guion_Campan, $_POST[\'tipificacion\'], $_POST[\'reintento\'], $_POST[\'id\']);
                	}

                	
                	
                    echo $_POST["id"];
                } else {
                	echo \'0\';
                    //echo "Error Hacieno el proceso los registros : " . $mysqli->error;
                }
            }
        }
    }
  '.$funcionDeCargadoDelaGrillaFinal."\n".'
  '.$funcionDeguardadoDeLagrillaFinal.'
?>
';    
            
      		fputs($fcrud , $crud);      
      		fclose($fcrud);   
		}else{
			echo "no se puede generar si no me envias nada";
		}
	}


/* Esto es mi firma personal Jose Giron */

// _____Sexy?Sex 
// ____?Sexy?Sexy 
// ___y?Sexy?Sexy? 
// ___?Sexy?Sexy?S 
// ___?Sexy?Sexy?S 
// __?Sexy?Sexy?Se 
// _?Sexy?Sexy?Se 
// _?Sexy?Sexy?Se 
// _?Sexy?Sexy?Sexy? 
// ?Sexy?Sexy?Sexy?Sexy 
// ?Sexy?Sexy?Sexy?Sexy?Se 
// ?Sexy?Sexy?Sexy?Sexy?Sex 
// _?Sexy?__?Sexy?Sexy?Sex 
// ___?Sex____?Sexy?Sexy? 
// ___?Sex_____?Sexy?Sexy 
// ___?Sex_____?Sexy?Sexy 
// ____?Sex____?Sexy?Sexy 
// _____?Se____?Sexy?Sex 
// ______?Se__?Sexy?Sexy 
// _______?Sexy?Sexy?Sex 
// ________?Sexy?Sexy?sex 
// _______?Sexy?Sexy?Sexy?Se 
// _______?Sexy?Sexy?Sexy?Sexy? 
// _______?Sexy?Sexy?Sexy?Sexy?Sexy 
// _______?Sexy?Sexy?Sexy?Sexy?Sexy?S 
// ________?Sexy?Sexy____?Sexy?Sexy?se 
// _________?Sexy?Se_______?Sexy?Sexy? 
// _________?Sexy?Se_____?Sexy?Sexy? 
// _________?Sexy?S____?Sexy?Sexy 
// _________?Sexy?S_?Sexy?Sexy 
// ________?Sexy?Sexy?Sexy 
// ________?Sexy?Sexy?S 
// ________?Sexy?Sexy 
// _______?Sexy?Se 
// _______?Sexy? 
// ______?Sexy? 
// ______?Sexy? 
// ______?Sexy? 
// ______?Sexy 
// ______?Sexy 
// _______?Sex 
// _______?Sex 
// _______?Sex 
// ______?Sexy 
// ______?Sexy 
// _______Sexy 
// _______ Sexy? 
// ________SexY 