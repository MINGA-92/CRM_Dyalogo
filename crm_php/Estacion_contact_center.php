
<?php

	session_start();
	include("funciones.php");
	include("conexion.php");
	ini_set('display_errors', 'On');
	ini_set('display_errors', 1);
	date_default_timezone_set('America/Bogota');
//    if(!isset($_SESSION['LOGIN_OK']) && !isset($_GET['idUSUARI'])){
//        header('Location: login.php');
//    }

    $http = "http://".$_SERVER["HTTP_HOST"];
	if (isset($_SERVER['HTTPS'])) {
	    $http = "https://".$_SERVER["HTTP_HOST"];
    }

    $token  = isset($_GET['token']) ? $_GET["token"] : "tokenNoExiste";
    $userid = isset($_GET['idUSUARI']) ? $_GET["idUSUARI"] : "-10";
    $_GET['usuario'] = isset($_GET['usuario']) ? $_GET["usuario"] : $userid;


//        $tokenSql = "SELECT * FROM ".$BaseDatos_systema.".SESSIONS WHERE SESSIONS__USUARI_ConsInte__b = ". $userid ." AND SESSIONS__Token = '".$token."' AND SESSIONS__Estado__b = 1 ";
//        $query = $mysqli->query($tokenSql) or trigger_error($mysqli->error." [$tokenSql]"); ;
//        if($query->num_rows > 0) {
//
//        }else{
//            header('Location: message.php?token=false');
//        }

    $tiempoDesdeInicio = date('Y-m-d H:i:s');









?>
<!DOCTYPE html>
<html>
	<head>
		<title>EstaciÃ³n contact center</title>
		<meta charset="utf-8">
		<meta name="tipo_contenido"  content="text/html;" http-equiv="content-type" charset="utf-8">
		<!-- Tell the browser to be responsive to screen width -->
		<meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
		<META HTTP-EQUIV="Access-Control-Allow-Origin" CONTENT="*">
		<!-- Bootstrap 3.3.6 -->
		<link rel="stylesheet" href="assets/bootstrap/css/bootstrap.min.css">
		<!-- Font Awesome -->
		<link rel="stylesheet" href="assets/font-awesome/css/font-awesome.min.css">
		<!-- Date Picker -->
		<link rel="stylesheet" href="assets/plugins/datepicker/datepicker3.css">
		<link rel="stylesheet" href="assets/plugins/DateTimePicker/bootstrap-datetimepicker.min.css">
		<link rel="stylesheet" href="assets/css/alertify.core.css">
        <link rel="stylesheet" href="assets/css/alertify.default.css">
        <link rel="stylesheet" href="assets/plugins/select2/select2.min.css" />
        <link rel="stylesheet" href="assets/plugins/sweetalert/sweetalert.css" />
        <link rel="stylesheet" type="text/css" media="screen" href="assets/Guriddo_jqGrid_/css/ui.jqgrid-bootstrap.css" />

        <link rel="stylesheet" href="assets/css/AdminLTE.min.css">
        <link rel="stylesheet" href="assets/timepicker/jquery.timepicker.css"/>
		<script src="assets/plugins/jQuery/jquery-2.2.3.min.js"></script>
		<script src="assets/plugins/BlockUi/jquery.blockUI.js"></script>
		<script src="formularios/generados/funcioneslocalstorage.js?ver=<?php echo rand() ?>"></script>
		<input type="hidden" id="idGestionStorage" value="<?php echo $_GET["id_gestion_cbx"]?>">
		<script type="text/javascript">
//            var strReferencia_t = new String(document.referrer);
//            if (strReferencia_t.includes('dyalogo.cloud')) {
//                //SI LLEGA HASTA AQUI ES UN DOMINIO VALIDO PARA PODER REALIZAR LA OPERACION
//            } else {
//                window.location.href = "message.php?token=false";
//            }        

			var campanaStorage=null;
			<?php if(isset($_GET["id_campana_crm"])) : ?>
				campanaStorage=<?php echo $_GET["id_campana_crm"]; ?>;
			 	<?php 
				 	$LsqL = "SELECT * FROM ".$BaseDatos_systema.".CAMPAN WHERE CAMPAN_ConsInte__b = ".$_GET['id_campana_crm']; 
				?>
			<?php endif; ?>

			<?php if(isset($_GET["campana_crm"])) : ?>
				campanaStorage=<?php echo $_GET["campana_crm"]; ?>;
		    	<?php
					$LsqL = "SELECT * FROM ".$BaseDatos_systema.".CAMPAN WHERE CAMPAN_ConsInte__b = ".$_GET['campana_crm'];
				?>
			<?php endif; ?>

			<?php
		    	$res = $mysqli->query($LsqL);
		    	$rs = $res->fetch_array();
				$consinte='-1';

				if(isset($_GET['consinte'])){
					$consinte=$_GET['consinte'];
				}

				if(isset($_GET['user'])){
					$consinte=$_GET['user'];
				}

			?>
			console.log('codigo 1:<?=$consinte?>');
		</script>
		<style type="text/css">
            [class^='select2'] {
                border-radius: 0px !important;
            }

            .modal-lg {
                width: 1200px;
            }
        </style>
		<?php
			if($consinte != '-1'){
				$sql=$mysqli->query("SELECT GUION__ConsInte__PREGUN_Pri_b,GUION__ConsInte__PREGUN_Sec_b FROM {$BaseDatos_systema}.GUION_ WHERE GUION__ConsInte__b={$rs['CAMPAN_ConsInte__GUION__Pob_b']} AND GUION__ConsInte__PREGUN_Pri_b IS NOT NULL AND GUION__ConsInte__PREGUN_Sec_b IS NOT NULL");
				if($sql && $sql->num_rows == 1){
					$sql=$sql->fetch_object();
					$strGuion="G{$rs['CAMPAN_ConsInte__GUION__Pob_b']}";
					$strCampoP="{$strGuion}_C{$sql->GUION__ConsInte__PREGUN_Pri_b}";
					$strCampoS="{$strGuion}_C{$sql->GUION__ConsInte__PREGUN_Sec_b}";
					$sqlG=$mysqli->query("SELECT {$strCampoP} AS pri, {$strCampoS} AS sec FROM DYALOGOCRM_WEB.{$strGuion} WHERE {$strGuion}_ConsInte__b={$consinte}");
					if($sqlG && $sqlG->num_rows == 1){
						$sqlG=$sqlG->fetch_object();
						echo "<div id='divdatosPri' style='position: absolute;width: 100%;display:flex;margin-left: 15px;z-index:1000;background:white'>
							<div class='form-group' style='display:flex;margin-right:150px'>
								<p class='text-muted' style='margin:3 0 0px'>Dato principal:</p>
								<h3 style='margin:0px; color: rgb(110, 197, 255);'>{$sqlG->pri}</h3>
							</div>
							<div class='form-group' style='display:flex;margin-right:150px'>
								<p class='text-muted' style='margin:3 0 0px'>Dato secundario:</p>
								<h4 style='margin:0px;margin-top:2px'>{$sqlG->sec}</h4>
							</div>
							<div class='form-group' style='display:flex;margin-right:150px'>
								<p class='text-muted'>Campaña: {$rs['CAMPAN_Nombre____b']}</p>
							</div>
						</div>";
					}
				}
			}
		?>
        <script type="text/javascript">
            function bindEvent(element, eventName, eventHandler) {
                if (element.addEventListener) {
                    element.addEventListener(eventName, eventHandler, false);
                } else if (element.attachEvent) {
                    element.attachEvent('on' + eventName, eventHandler);
                }
            }


            // Listen to message from child window
            bindEvent(window, 'message', function (e) {
                /*console.log(e.data);*/
                window.parent.postMessage(e.data, '*');
            });         

        	if (window.addEventListener) {
			    // For standards-compliant web browsers
			    window.addEventListener("message", displayMessage, false);
			    		       
			}
			else {
			    window.attachEvent("onmessage", displayMessage);
			   
			}

		    
		    async function displayMessage(e){
		    	
		    	<?php
		    		if(isset($_GET['campana_crm'])){
		    			$LsqL = "SELECT * FROM ".$BaseDatos_systema.".CAMPAN WHERE CAMPAN_ConsInte__b = ".$_GET['campana_crm'];
		    		}else{
		    			$LsqL = "SELECT * FROM ".$BaseDatos_systema.".CAMPAN WHERE CAMPAN_ConsInte__b = ".$_GET['id_campana_crm'];
		    		}
		    		
		    		$res = $mysqli->query($LsqL);
		    		$rs = $res->fetch_array();
		    		$url_crud = "formularios/G".$rs['CAMPAN_ConsInte__GUION__Gui_b']."/G".$rs['CAMPAN_ConsInte__GUION__Gui_b']."_CRUD.php";
		    		$intG = $rs['CAMPAN_ConsInte__GUION__Gui_b'];

		    	?>
		        if(e.data == 'FinalizacionDesdeCBXBLEND'){
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
		        	//var bol_respuesta = before_save();
		        	var d = new Date();
                    var n = new Date();
                    n.setFullYear(n.getFullYear() -10);
		            var h = d.getHours();
		            var horas = (h < 10) ? '0' + h : h;
		            var dia = d.getDate();
		            var dias = (dia < 10) ? '0' + dia : dia;
		            var fechaFinal = d.getFullYear() + '-' + meses[d.getMonth()] + '-' + dias + ' '+ horas +':'+d.getMinutes()+':'+d.getSeconds();
		            $("#FechaFinal").val(fechaFinal);
                    var fechaAgenda= n.getFullYear() + '-' + meses[d.getMonth()] + '-' + dias;
		            var valido = 0;



		            var form = $("#FormularioDatos");
		            //Se crean un array con los datos a enviar, apartir del formulario 
		            var formData = new FormData($("#FormularioDatos")[0]);
                    
                    var formData=new FormData($("#frameContenedor").contents().find("#FormularioDatos")[0]);
		            formData.append('Efectividad', 0);
		            formData.append('MonoEf', -1);
		            formData.append('TipNoEF', 2);
		            formData.append('FechaInicio', '<?php echo $tiempoDesdeInicio;?>');
		            formData.append('FechaFinal', fechaFinal);
		            formData.append('MonoEfPeso', 100);
		            formData.append('ContactoMonoEf', 5);
		            formData.append('reintento', -1);
                    var tipificacion=formData.get("tipificacion");
                    if(tipificacion == 0 || tipificacion == null){
                        formData.append('tipificacion', -1);
                    }
		            formData.append('TxtFechaReintento', fechaAgenda);
		            formData.append('TxtHoraReintento', '08:00:00');
		            formData.append('textAreaComentarios', 'Registro cerrado por Blend');
		            formData.append('hidId', '<?php if(isset($_GET['consinte'])) { echo $_GET["consinte"]; }else{ echo "0";  } ?>');
                    let idInsertado=await obtenerIdInsertado();
                    console.log('blend:validar id insertado',idInsertado);
                    if(!idInsertado){
                        formData.append('oper', 'add');
                    }else{
                        formData.append('id',idInsertado);
                        formData.append('oper', 'edit');
                    }
		            // <?php // if(isset($_GET['consinte']) && $_GET['consinte'] > 0) { echo "intConsInteBd=".$_GET["consinte"].";"; }else{ ?> if (formData.get("codigoMiembro")) { intConsInteBd = formData.get("codigoMiembro"); }else{ intConsInteBd = -1; } <?php // } ?>

					<?php
						$consinte='-1';

						if(isset($_GET['consinte'])){
							$consinte=$_GET['consinte'];
						}

						if(isset($_GET['user'])){
							$consinte=$_GET['user'];
						}
					?>

					let codigoMiembro=await obtenerCodigoMiembro();
		            //strCanal_t = canal
		            <?php if (isset($_GET["canal"])) {

		            	echo "formData.append('strCanal_t','".$_GET["canal"]."');";

		            }else { ?>

		            	if (formData.get("strCanal_t")) {

		            		formData.append('strCanal_t',formData.get("strCanal_t"));

		            	}

		            <?php } ?>
		            //origenGestion = origen
		            <?php if (isset($_GET["origen"])) {

		            	echo "formData.append('origenGestion','".$_GET["origen"]."');";

		            }else { ?>

		            	if (formData.get("origenGestion")) {

		            		formData.append('origenGestion',formData.get("origenGestion"));
		            		
		            	}

		            <?php } ?>
		            <?php if (isset($_GET["id_gestion_cbx"])) {

		            	echo "formData.append('id_gestion_cbx','".$_GET["id_gestion_cbx"]."');";

		            }else { ?>

		            	if (formData.get("cbx_canal__")) {

		            		formData.append('id_gestion_cbx',formData.get("id_gestion_cbx"));
		            		
		            	}

		            <?php } ?>
		            //cbx_sentido = sentido
		            <?php if (isset($_GET["sentido"])) {

		            	echo "formData.append('cbx_sentido','".$_GET["sentido"]."');";

		            }else { ?>

		            	if (formData.get("cbx_sentido")) {

		            		formData.append('cbx_sentido',formData.get("cbx_sentido"));
		            		
		            	}else{
		            		
		            		formData.append('cbx_sentido','0');

		            	}

		            <?php } ?>
					//cbx_canal__ = id_gestion_cbx
		            <?php if (isset($_GET["id_gestion_cbx"])) {

		            	echo "formData.append('cbx_canal__','".$_GET["id_gestion_cbx"]."');";

		            }else { ?>

		            	if (formData.get("cbx_canal__")) {

		            		formData.append('cbx_canal__',formData.get("cbx_canal__"));
		            		
		            	}else{
		            		
		            		formData.append('cbx_canal__','0');

		            	}

		            <?php } ?>
		            formData.append('Padre', 0);

		            //datoContacto = ani
		            <?php if (isset($_GET["ani"])) {

		            	echo "formData.append('datoContacto','".$_GET["ani"]."');";

		            }else { ?>

		            	if (formData.get("datoContacto")) {

		            		formData.append('datoContacto',formData.get("datoContacto"));
		            		
		            	}else{

		            		formData.append('datoContacto','0');

		            	}

		            <?php } ?>

					let idGestionCBX="";
					if($("#id_gestion_cbx").val()){
						idGestionCBX=$("#id_gestion_cbx").val();
					}else if($("#frameContenedor").contents().find("#id_gestion_cbx").val()){
						idGestionCBX=$("#frameContenedor").contents().find("#id_gestion_cbx").val();
					}else if($("#main #frameContenedor").contents().find("#id_gestion_cbx").val()){
						idGestionCBX=$("#main #frameContenedor").contents().find("#id_gestion_cbx").val();
					}else{
						idGestionCBX=$("#frameContenedor").contents().find("#FormularioDatos").contents().find("#id_gestion_cbx").val();
					}

					formData.append('id_gestion_cbx',idGestionCBX);
					formData.append('cbx_canal__',idGestionCBX);
					formData.append('codigoMiembro',codigoMiembro);
		            $.ajax({
						url: '<?=$url_crud;?>?insertarDatosGrilla=si&usuario=<?php echo $_GET['usuario']; ?>&CodigoMiembro='+codigoMiembro+'<?php if(isset($_GET['id_gestion_cbx'])){ echo "&id_gestion_cbx=".$_GET['id_gestion_cbx']; }?><?php if(isset($token)){ echo "&token=".$token; }?>&LlamadoExterno=si',  
		                type: 'POST',
						dataType: 'JSON',
		                data: formData,
		                cache: false,
		                contentType: false,
		                processData: false,
		                //una vez finalizado correctamente
		                success: function(data){
							if(typeof(data)=='object'){
								data=data.mensaje;
							}
							console.log(data);
		                    if(data){
	                        	$.ajax({
	                        		url   : 'formularios/generados/PHP_Ejecutar.php?action=EDIT&tiempo=<?php echo $tiempoDesdeInicio;?>&usuario=<?php echo $_GET['usuario']; ?>&CodigoMiembro='+codigoMiembro+'&ConsInteRegresado='+ data +'<?php if(isset($_GET['token'])) { echo "&token=".$_GET['token']; }?><?php if(isset($_GET['id_gestion_cbx'])) { echo "&id_gestion_cbx=".$_GET['id_gestion_cbx']; }?>&campana_crm=<?php if(isset($_GET['id_campana_crm'])){ echo $_GET['id_campana_crm']; }else{ echo $_GET['campana_crm']; } ?><?php if(isset($_GET['predictiva'])) { echo "&predictiva=".$_GET['predictiva'];}?><?php if(isset($_GET['consinte'])) { echo "&consinte=".$_GET['consinte']; }?>&cerrarViaPost=true',
	                        		type  : "post",
	                        		data  : formData,
                                    dataType: 'json',
	                    		 	cache: false,
				                    contentType: false,
				                    processData: false,
	                        		success : function(xt){
                                        var origen="blend";
	                        			console.log("Esta gestión fue cerrada por Blend hora de cierre <?php echo date('Y-m-d H:i:s');?>");
										borrarStorage(idGestionCBX);
	                        			//finalizarGestion(xt,origen);
	                        		}
	                        	});
				                        	           
		                    }               
		                },
		                //si ha ocurrido un error
		                error: function(){
		                    after_save_error();
		                    alertify.error('Ocurrio un error relacionado con la red, al momento de guardar, intenta mas tarde');
		                }
		            });
          		
		        }else if(e.data == 'FinalizacionDesdeCBXTIMEOUT'){
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
			        	//var bol_respuesta = before_save();
			        	var d = new Date();
			            var h = d.getHours();
			            var horas = (h < 10) ? '0' + h : h;
			            var dia = d.getDate();
			            var dias = (dia < 10) ? '0' + dia : dia;
			            var fechaFinal = d.getFullYear() + '-' + meses[d.getMonth()] + '-' + dias + ' '+ horas +':'+d.getMinutes()+':'+d.getSeconds();

			           


			            var form = $("#FormularioDatos");
			            //Se crean un array con los datos a enviar, apartir del formulario 
			            //var formData = new FormData($("#FormularioDatos")[0]);

			            var formData=new FormData($("#frameContenedor").contents().find("#FormularioDatos")[0]);
			           
			            var Efectividad = 0;
			            var MonoEf = -2;
			            var TipNoEF = 1;
			            var FechaInicio = '<?php echo $tiempoDesdeInicio;?>';
			            var FechaFinal = fechaFinal;
			            var MonoEfPeso = 100;
			            var ContactoMonoEf = 4;

			            var tipificacion = -2;
			            var reintento = 3;
			            var TxtFechaReintento = "";
			            var TxtHoraReintento = "";
			            var textAreaComentarios = "Registro cerrado por TimeOut";

			            if (formData.get("Efectividad") != 0 && formData.get("Efectividad") != null) {
			            	Efectividad = formData.get("Efectividad");
			            }
			            if (formData.get("MonoEf") != 0 && formData.get("MonoEf") != null) {
			            	MonoEf = formData.get("MonoEf");
			            }
			            if (formData.get("TipNoEF") != 0 && formData.get("TipNoEF") != null) {
		            		reintento = formData.get("TipNoEF");
			            	if (reintento == 2) {
			            		TxtFechaReintento = formData.get("TxtFechaReintento");
			            		TxtHoraReintento = formData.get("TxtHoraReintento");
			            	}
			            	TipNoEF = formData.get("TipNoEF");
			            }
			            if (formData.get("MonoEfPeso") != 0 && formData.get("MonoEfPeso") != null) {
			            	MonoEfPeso = formData.get("MonoEfPeso");
			            }
			            if (formData.get("ContactoMonoEf") != 0 && formData.get("ContactoMonoEf") != null) {
			            	ContactoMonoEf = formData.get("ContactoMonoEf");
			            }
			            if (formData.get("tipificacion") != 0 && formData.get("tipificacion") != null) {
			            	tipificacion = formData.get("tipificacion");
			            }
			            if (formData.get("textAreaComentarios") != "" && formData.get("textAreaComentarios") != null) {
			            	textAreaComentarios = formData.get("textAreaComentarios");
			            }

			            // <?php // if(isset($_GET['consinte']) && $_GET['consinte'] > 0) { echo "intConsInteBd=".$_GET["consinte"].";"; }else{ ?> if (formData.get("codigoMiembro")) { intConsInteBd = formData.get("codigoMiembro"); }else{ intConsInteBd = -1; } <?php // } ?>

						<?php
							$consinte='-1';

							if(isset($_GET['consinte'])){
								$consinte=$_GET['consinte'];
							}

							if(isset($_GET['user'])){
								$consinte=$_GET['user'];
							}
						?>

						let codigoMiembro=await obtenerCodigoMiembro();

			            formData.append('Efectividad', Efectividad);
			            formData.append('MonoEf', MonoEf);
			            formData.append('TipNoEF', TipNoEF);
			            formData.append('FechaInicio', FechaInicio);
			            formData.append('FechaFinal', FechaFinal);
			            formData.append('MonoEfPeso', MonoEfPeso);
			            formData.append('ContactoMonoEf', ContactoMonoEf);

			            formData.append('tipificacion', tipificacion);
			            formData.append('reintento', reintento);
			            formData.append('TxtFechaReintento', TxtFechaReintento);
			            formData.append('TxtHoraReintento', TxtHoraReintento);
			            formData.append('textAreaComentarios', textAreaComentarios);

                        let idInsertado=await obtenerIdInsertado();
                        console.log('timeOut: validar id insertado',idInsertado);
                        if(!idInsertado){
                            formData.append('oper', 'add');
                        }else{
                            formData.append('id',idInsertado);
                            formData.append('oper', 'edit');
                        }

			            //cbx_canal__ = id_gestion_cbx
			            // <?php if (isset($_GET["id_gestion_cbx"])) {

			            // 	echo "formData.append('cbx_canal__',$('#id_gestion_cbx').val());";

			            // }else { ?>

			            // 	if (formData.get("cbx_canal__")) {

			            // 		formData.append('cbx_canal__',formData.get("cbx_canal__"));
			            		
			            // 	}else{
			            		
			            // 		formData.append('cbx_canal__','0');

			            // 	}

			            // <?php } ?>
			            //cbx_sentido = sentido
			            <?php if (isset($_GET["sentido"])) {

			            	echo "formData.append('cbx_sentido','".$_GET["sentido"]."');";

			            }else { ?>

			            	if (formData.get("cbx_sentido")) {

			            		formData.append('cbx_sentido',formData.get("cbx_sentido"));
			            		
			            	}else{
			            		
			            		formData.append('cbx_sentido','0');

			            	}

			            <?php } ?>
			            //id_gestion_cbx = id_gestion_cbx
			            // <?php if (isset($_GET["id_gestion_cbx"])) {

			            // 	echo "formData.append('id_gestion_cbx',$('#id_gestion_cbx').val());";

			            // }else { ?>

			            // 	if (formData.get("cbx_canal__")) {

			            // 		formData.append('id_gestion_cbx',$('#id_gestion_cbx').val());
			            		
			            // 	}

			            // <?php } ?>
			            formData.append('Padre', 0);
			            //datoContacto = ani
			            <?php if (isset($_GET["ani"])) {

			            	echo "formData.append('datoContacto','".$_GET["ani"]."');";

			            }else { ?>

			            	if (formData.get("datoContacto")) {

			            		formData.append('datoContacto',formData.get("datoContacto"));
			            		
			            	}else{

			            		formData.append('datoContacto','0');

			            	}

			            <?php } ?>
			            //codigoMiembro = user
			            // <?php if (isset($_GET["user"])) {

			            // 	echo "formData.append('codigoMiembro',codigoMiembro);";

			            // }else { ?>

			            // 	if (formData.get("codigoMiembro")) {

			            // 		formData.append('codigoMiembro',formData.get("codigoMiembro"));
			            		
			            // 	}else{

			            // 		formData.append('codigoMiembro','-1');

			            // 	}

			            // <?php } ?>
			            //origenGestion = origen
			            <?php if (isset($_GET["origen"])) {

			            	echo "formData.append('origenGestion','".$_GET["origen"]."');";

			            }else { ?>

			            	if (formData.get("origenGestion")) {

			            		formData.append('origenGestion',formData.get("origenGestion"));
			            		
			            	}else{

			            		formData.append('origenGestion','BusquedaManual');

			            	}

			            <?php } ?>

			            //strCanal_t = canal
			            <?php if (isset($_GET["canal"])) {

			            	echo "formData.append('strCanal_t','".$_GET["canal"]."');";

			            }else { ?>

			            	if (formData.get("strCanal_t")) {

			            		formData.append('strCanal_t',formData.get("strCanal_t"));

			            	}else{

			            		formData.append('strCanal_t','Sin canal');

			            	}

			            <?php } ?>
						
						let idGestionCBX="";
						if($("#id_gestion_cbx").val()){
							idGestionCBX=$("#id_gestion_cbx").val();
						}else if($("#frameContenedor").contents().find("#id_gestion_cbx").val()){
							idGestionCBX=$("#frameContenedor").contents().find("#id_gestion_cbx").val();
						}else if($("#main #frameContenedor").contents().find("#id_gestion_cbx").val()){
							idGestionCBX=$("#main #frameContenedor").contents().find("#id_gestion_cbx").val();
						}else{
							idGestionCBX=$("#frameContenedor").contents().find("#FormularioDatos").contents().find("#id_gestion_cbx").val();
						}
						formData.append('id_gestion_cbx',idGestionCBX);
						formData.append('cbx_canal__',idGestionCBX);
						formData.append('codigoMiembro',codigoMiembro);
			            $.ajax({
			            	url: '<?=$url_crud;?>?insertarDatosGrilla=si&usuario=<?php echo $_GET['usuario']; ?>&CodigoMiembro='+codigoMiembro+'&id_gestion_cbx='+ idGestionCBX+'<?php if(isset($token)){ echo "&token=".$token; }?>&LlamadoExterno=si',
			                type: 'POST',
			                data: formData,
							dataType:'JSON',
			                cache: false,
			                contentType: false,
			                processData: false,
			                //una vez finalizado correctamente
			                success: function(data){
			                	if(typeof(data)=='object'){
									data=data.mensaje;
								}
								console.log(data);
			                    if(data){
		                        	$.ajax({
		                        		url   : 'formularios/generados/PHP_Ejecutar.php?action=EDIT&tiempo=<?php echo $tiempoDesdeInicio;?>&usuario=<?php echo $_GET['usuario']; ?>&CodigoMiembro='+codigoMiembro+'&ConsInteRegresado='+ data +'<?php if(isset($_GET['token'])) { echo "&token=".$_GET['token']; }?>&id_gestion_cbx='+ idGestionCBX +'&campana_crm=<?php if(isset($_GET['id_campana_crm'])){ echo $_GET['id_campana_crm']; }else{ echo $_GET['campana_crm']; } ?><?php if(isset($_GET['predictiva'])) { echo "&predictiva=".$_GET['predictiva'];}?><?php if(isset($_GET['consinte'])) { echo "&consinte=".$_GET['consinte']; }?>&cerrarViaPost=true',
		                        		type  : "post",
		                        		data  : formData,
                                        dataType: 'json',
		                    		 	cache: false,
					                    contentType: false,
					                    processData: false,
		                        		success : function(xt){
                                            var origen="timeout";
		                        			finalizarGestion(xt,origen);
											borrarStorage(idGestionCBX);
		                        		}
		                        	});
					                        	           
			                    }               
			                },
			                //si ha ocurrido un error
			                error: function(){
			                    after_save_error();
			                    alertify.error('Ocurrio un error relacionado con la red, al momento de guardar, intenta mas tarde');
			                }
			            });
		        	
		        
		        }else{
		        	eval(e.data);
		        }
		    }

		    /**YCR 2019-10-24
			*Funcion que cambia el Id de la llamada
		    */
		    function cambiarUniqueId(id){
		    	$("#idLlamada").val(id);
				$("#id_gestion_cbx").val('telefonia_'+id);
				$("#CampoIdGestionCbx").val('telefonia_'+id);
				$("#idGestionStorage").val('telefonia_'+id);
				$("#strCanal_t").val('telefonia');
				
		    	$("#frameContenedor").contents().find("#idLlamada").val(id);
		    	$("#frameContenedor").contents().find("#id_gestion_cbx").val('telefonia_'+id);
		    	$("#frameContenedor").contents().find("#CampoIdGestionCbx").val('telefonia_'+id);
		    	$("#frameContenedor").contents().find("#idGestionStorage").val('telefonia_'+id);
				$("#frameContenedor").contents().find("#strCanal_t").val('telefonia');
				
		    	$("#main #frameContenedor").contents().find("#idLlamada").val(id);
		    	$("#main #frameContenedor").contents().find("#id_gestion_cbx").val('telefonia_'+id);
		    	$("#main #frameContenedor").contents().find("#CampoIdGestionCbx").val('telefonia_'+id);
		    	$("#main #frameContenedor").contents().find("#idGestionStorage").val('telefonia_'+id);
				$("#main #frameContenedor").contents().find("#strCanal_t").val('telefonia');

				$("#frameContenedor").contents().find("#FormularioDatos").contents().find("#idLlamada").val(id);
				$("#frameContenedor").contents().find("#FormularioDatos").contents().find("#id_gestion_cbx").val('telefonia_'+id);
				$("#frameContenedor").contents().find("#FormularioDatos").contents().find("#CampoIdGestionCbx").val('telefonia_'+id);
				$("#frameContenedor").contents().find("#FormularioDatos").contents().find("#idGestionStorage").val('telefonia_'+id);
				$("#frameContenedor").contents().find("#FormularioDatos").contents().find("#strCanal_t").val('telefonia');

				let formulario=$("#frameContenedor").contents().find("#script").val();
				if(formulario == undefined || formulario == 'undefined' || formulario == null){
					formulario=$("#script").val();
					if(formulario == undefined || formulario == 'undefined' || formulario == null){
						formulario=$("#main #frameContenedor").contents().find("#script").val();
						if(formulario == undefined || formulario == 'undefined' || formulario == null){
							formulario=$("#frameContenedor").contents().find("#FormularioDatos").contents().find("#script").val();
							if(formulario == undefined || formulario == 'undefined' || formulario == null){
								formulario='-1';
							}
						}
					}
				}
				

				guardarStorage('telefonia_'+id,null,null,null,formulario,null,null,null,null,null,null,false,true);
				console.log("llamar al storage al cambiar el id de comunicación");
		    }

			function llamadaTransferida(){
				let formData=new FormData($("#frameContenedor").contents().find("#FormularioDatos")[0]);
				let strGestion=formData.get("id_gestion_cbx");
				if(!isNullIdLlamada(strGestion)){
					strGestion=$("#idGestionStorage").val();
				}

				console.log('valor de la llamada transferida',strGestion);
				<?php
					$thisCampan=isset($_GET['id_campana_crm']) ? $_GET['id_campana_crm'] : $_GET['campana_crm'];
				?>
				try {
					tipocanal=strGestion.split("telefonia");
					if(tipocanal.length > 1){
						$.ajax({
							url: 'formularios/generados/PHP_Ejecutar.php',
							type:'POST',
							dataType:'json',
							data:{llamadaTrasferida:'si', id:strGestion, campan:'<?=$thisCampan?>'},
							success:function(data){
								if(data.estado=='ok'){
									console.log("Se proceso la petición de la llamada transferida");
								}else{
									console.log("No se proceso la petición de la llamada transferida");
								}
							},
							error:function(){
								console.log("Error al enviar la petición de la llamada transferida");
							}
						});
					}
				} catch (error) {
					console.error("no se obtuvo el valor de la llamada trasnferida");
				}
			}

			function isNullIdLlamada(id){
				if(id == 'undefined' || id == null || id == '' || id == undefined){
					return false;
				}
				return true;
			}
            
            function getDatoContacto(callId){
		    	console.log(callId);
		    	$("#frameContenedor").contents().find("#datoContacto").val(callId);
		    }
		    /*YCR 2019-10-21
			*Function que tipifica despues de presinar el boton verdel telefono
		    **/
		    async function guardarGestionParaNuevaLlamada(intIdLisopc_p,intIdMonoef_p,intTipoReintento_p,datFechaAgenda_p,datHoraAgenda_p,strObservaciones_p){

		    	var intIdLisopc_t =  traerIdLisopc(intIdMonoef_p);

				<?php
					$consinte='-1';

					if(isset($_GET['consinte'])){
						$consinte=$_GET['consinte'];
					}

					if(isset($_GET['user'])){
						$consinte=$_GET['user'];
					}
				?>

				let codigoMiembro=await obtenerCodigoMiembro();

		    	var tiempoInicio = $("#frameContenedor").contents().find("#tiempoInicio").val();
		    	var fechaAgenda ='';
		    	var horaAgenda = '';
		    	var observaciones = '';
		    	
		    	if(intTipoReintento_p == '2'){
		    		fechaAgenda = datFechaAgenda_p;
		    		horaAgenda = datHoraAgenda_p;
		    	}
		    	
		    	if(strObservaciones_p != '' && strObservaciones_p != null){
		    		observaciones = strObservaciones_p;
		    	}
		    	
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
	            var horas = (h < 10) ? '0' + h : h;
	            var dia = d.getDate();
	            var dias = (dia < 10) ? '0' + dia : dia;
	            var fechaFinal = d.getFullYear() + '-' + meses[d.getMonth()] + '-' + dias + ' '+ horas +':'+d.getMinutes()+':'+d.getSeconds();

	            var formData=new FormData($("#frameContenedor").contents().find("#FormularioDatos")[0]);
	            formData.append('Efectividad', 0);
	            formData.append('MonoEf', intIdMonoef_p);
	            formData.append('TipNoEF', intTipoReintento_p);
	            formData.append('FechaInicio', '<?php echo $tiempoDesdeInicio;?>');
	            formData.append('FechaFinal', fechaFinal);
	            formData.append('MonoEfPeso', 100);
	            formData.append('ContactoMonoEf', 0);
	            formData.append('reintento', intTipoReintento_p);
	            formData.append('tipificacion', intIdLisopc_t);
	            formData.append('TxtFechaReintento', fechaAgenda);
	            formData.append('TxtHoraReintento', horaAgenda);
	            formData.append('textAreaComentarios', observaciones);
                let idInsertado=await obtenerIdInsertado();
                console.log('reLlamada: validar id insertado',idInsertado);
                if(!idInsertado){
                    formData.append('oper', 'add');
                }else{
                    formData.append('id',idInsertado);
                    formData.append('oper', 'edit');
                }

	            //strCanal_t = canal
	            <?php if (isset($_GET["canal"])) {

	            	echo "formData.append('strCanal_t','".$_GET["canal"]."');";

	            }else { ?>

	            	if (formData.get("strCanal_t")) {

	            		formData.append('strCanal_t',formData.get("strCanal_t"));

	            	}else{

	            		formData.append('strCanal_t','Sin Canal');

	            	}

	            <?php } ?>
			            
	            //origenGestion = origen
	            <?php if (isset($_GET["origen"])) {

	            	echo "formData.append('origenGestion','".$_GET["origen"]."');";

	            }else { ?>

	            	if (formData.get("origenGestion")) {

	            		formData.append('origenGestion',formData.get("origenGestion"));
	            		
	            	}else{

	            		formData.append('origenGestion','BusquedaManual');

	            	}

	            <?php } ?>

	            //id_gestion_cbx = id_gestion_cbx
	            // <?php if (isset($_GET["id_gestion_cbx"])) {

	            // 	echo "formData.append('id_gestion_cbx','".$_GET["id_gestion_cbx"]."');";

	            // }else { ?>

	            // 	if (formData.get("cbx_canal__")) {

	            // 		formData.append('id_gestion_cbx',formData.get("id_gestion_cbx"));
	            		
	            // 	}

	            // <?php } ?>

	            //cbx_sentido = sentido
	            <?php if (isset($_GET["sentido"])) {

	            	echo "formData.append('cbx_sentido','".$_GET["sentido"]."');";

	            }else { ?>

	            	if (formData.get("cbx_sentido")) {

	            		formData.append('cbx_sentido',formData.get("cbx_sentido"));
	            		
	            	}else{
	            		
	            		formData.append('cbx_sentido','0');

	            	}

	            <?php } ?>

	            //cbx_canal__ = id_gestion_cbx
	            <?php if (isset($_GET["id_gestion_cbx"])) {

	            	echo "formData.append('cbx_canal__','".$_GET["id_gestion_cbx"]."');";

	            }else { ?>

	            	if (formData.get("cbx_canal__")) {

	            		formData.append('cbx_canal__',formData.get("cbx_canal__"));
	            		
	            	}else{
	            		
	            		formData.append('cbx_canal__','0');

	            	}

	            <?php } ?>

	            formData.append('Padre', 0);

	            //datoContacto = ani
	            <?php if (isset($_GET["ani"])) {

	            	echo "formData.append('datoContacto','".$_GET["ani"]."');";

	            }else { ?>

	            	if (formData.get("datoContacto")) {

	            		formData.append('datoContacto',formData.get("datoContacto"));
	            		
	            	}else{

	            		formData.append('datoContacto','0');

	            	}

	            <?php } ?>

				let idGestionCBX="";
				if($("#id_gestion_cbx").val()){
					idGestionCBX=$("#id_gestion_cbx").val();
				}else if($("#frameContenedor").contents().find("#id_gestion_cbx").val()){
					idGestionCBX=$("#frameContenedor").contents().find("#id_gestion_cbx").val();
				}else if($("#main #frameContenedor").contents().find("#id_gestion_cbx").val()){
					idGestionCBX=$("#main #frameContenedor").contents().find("#id_gestion_cbx").val();
				}else{
					idGestionCBX=$("#frameContenedor").contents().find("#FormularioDatos").contents().find("#id_gestion_cbx").val();
				}

				formData.append('id_gestion_cbx',idGestionCBX);
				formData.append('cbx_canal__',idGestionCBX);
				formData.append('codigoMiembro',codigoMiembro);
	            formData.append('llamarApi', 'no');

	            $.ajax({
	            	url: '<?=$url_crud;?>?insertarDatosGrilla=si&usuario=<?=$_GET['usuario']?>&CodigoMiembro='+codigoMiembro+'&id_gestion_cbx='+idGestionCBX+'<?php if(isset($token)){ echo "&token=".$token; }?>&LlamadoExterno=si', 
                    
	                type: 'POST',
	                data: formData,
					dataType:'JSON',
	                cache: false,
	                contentType: false,
	                processData: false,
	                //una vez finalizado correctamente
	                success: function(data){
	                    if(data){
							if(typeof(data)=='object'){
								data=data.mensaje;
							}
	                    	$.ajax({
	                    		url   : 'formularios/generados/PHP_Ejecutar.php?action=EDIT&tiempo='+tiempoInicio+'&usuario=<?=$_GET['usuario']?>&CodigoMiembro='+codigoMiembro+'&ConsInteRegresado='+ data +'<?php if(isset($_GET['token'])) { echo "&token=".$_GET['token']; }?>&id_gestion_cbx='+idGestionCBX+'&campana_crm=<?php if(isset($_GET['id_campana_crm'])){ echo $_GET['id_campana_crm']; }else{ echo $_GET['campana_crm']; } ?><?php if(isset($_GET['predictiva'])) { echo "&predictiva=".$_GET['predictiva'];}?>&consinte='+codigoMiembro,
	                    		type  : "post",
	                    		data  : formData,
	                		 	cache: false,
								async:false,
			                    contentType: false,
			                    processData: false,
	                    		success : function(xt){
									var campanaStorage=null;
									<?php if(isset($_GET["id_campana_crm"])) : ?>
										campanaStorage=<?=$_GET["id_campana_crm"]?>;
									<?php endif; ?>

									<?php if(isset($_GET["campana_crm"])) : ?>
										campanaStorage=<?=$_GET["campana_crm"]?>;
									<?php endif; ?>

	                    			$("#main #frameContenedor").attr('src', '<?php echo $http ;?>/crm_php/Estacion_contact_center.php?consinte='+codigoMiembro+'&campan=true&user='+ codigoMiembro +'&view=si&token=<?php echo $_GET["token"];?>&id_gestion_cbx=&id_gestion_cbx='+idGestionCBX+'<?php if(isset($_GET["predictiva"])) { echo "&predictiva=".$_GET["predictiva"]; }?><?php if(isset($_GET["id_campana_crm"])) { echo "&campana_crm=".$_GET["id_campana_crm"]; }else{echo "&campana_crm=".$_GET["campana_crm"];}?><?php if(isset($_GET["sentido"])) { echo "&sentido=".$_GET["sentido"]; }?><?php if(isset($_GET["ani"])){ echo "&ani=".$_GET["ani"]; }?>&usuario=<?=$_GET['usuario']?>&id_campana_cbx=<?php if(isset($_GET['id_campana_cbx']) && $_GET['id_campana_cbx'] !="" && $_GET['id_campana_cbx'] !=0){echo $_GET['id_campana_cbx'];}else{echo '0';}?>&canal=telefonia&origen=Reintento_agente');
	                    		}
	                    	});
			                        	           
	                    }               
	                },
	                //si ha ocurrido un error
	                error: function(){
	                    after_save_error();
	                    alertify.error('Ocurrio un error relacionado con la red, al momento de guardar, intenta mas tarde');
	                }
	            });

		    }
		    
		    /**YCR 2019-10-24
			*Funcion para identificar cuando un usuario esta ubicado en un script
			*@return - si devuelve true, es que esta ubicado en un script
		    */
		    function estaEnScript(){
		    	let strScript = $("#frameContenedor").contents().find("#inscript").val();
		    	let bolScript = false;
		    	let data;
		    	if(strScript == 'script'){
		    		bolScript = true;
		    	}else{
					strScript=$("#inscript").val();
					if(strScript == 'script'){
						bolScript = true;
					}else{
						strScript=$("#main #frameContenedor").contents().find("#inscript").val();
						if(strScript == 'script'){
							bolScript = true;
						}else{
							strScript=$("#frameContenedor").contents().find("#FormularioDatos").contents().find("#inscript").val(id);
							if(strScript == 'script'){
								bolScript = true;
							}
						}
					}
				}
                
                data={
                    accion:'agenteEnScript',
                    valor: bolScript
                };
		    	window.parent.postMessage(data, '*');	
			}
            
			function traerIdLisopc(intIdMonoef_p){

				intIdMonoef_t = $.ajax({
				                        url      : "formularios/generados/PHP_Ejecutar.php?traerMonoef=si",
				                        type     : "POST",
				                        data     : {intIdMonoef_t : intIdMonoef_p},
										cache : false,
				                        async    :false,
				                        success  : function(data) {
				                            return data;
				                        }
				                     }).responseText;

				return intIdMonoef_t;
			}
            
            function finalizarGestion(arrDatos,origen){
                if(typeof(arrDatos) == 'object'){
                    var data;                  
                    if(origen == "BusquedaManual" || origen == 'bq_manual' || origen == 'bqmanual'){
                        data={
                            accion:'finalizarGestionCRM',
                            booForzarCierre_t:true,
                            intConsInte_t: arrDatos.intConsInte_t,
                            intTipoReintento_t:'-1',
                            strFechaHoraAgenda_t:'-1',
                            intMonoefEfectiva_t:'-1',
                            intConsinteTipificacion_t:'-1',
                            intMonoefContacto_t:'-1'
                        };
                    }else{
                        data={
                            accion:'finalizarGestionCRM',
                            booForzarCierre_t:true,
                            intConsInte_t: arrDatos.dataGestion["intConsInte_t"],
                            intTipoReintento_t:arrDatos.dataGestion["intTipoReintento_t"],
                            strFechaHoraAgenda_t:arrDatos.dataGestion["strFechaHoraAgenda_t"],
                            intMonoefEfectiva_t:arrDatos.dataGestion["intMonoefEfectiva_t"],
                            intConsinteTipificacion_t:arrDatos.dataGestion["intConsInteTipificacion_t"],
                            intMonoefContacto_t:arrDatos.dataGestion["intMonoefContacto_t"]
                        };
                    }
                }                    
                console.log(arrDatos,data);
                window.parent.postMessage(data, '*');
				$("body").html("<div></div>");
				try {
					$.blockUI({
						baseZ: 2000,
						message: '<img src="assets/img/clock.gif" /> Cargando...'
					});
            	} catch (error) {}
            }
            
            function validaCierreGestion(querys){
                var valido=1;
                if(querys["BD"] != "OK"){
                    
                }

                if(querys["SCRIPT"] != "OK"){

                }

                if(querys["MUESTRA"] != "OK"){

                }

                if(querys["CONDIA"] != "OK"){

                }                 
            }
            
			function obtenerCodigoMiembro(){
				return new Promise((resolve, reject)=> {
					let allGestiones= {};
					let regGestionado = '';                
					let registroInsertado=''; 
					let gestion='';       
					let getGestiones=sessionStorage.getItem("gestiones");
					if (getGestiones !== null && getGestiones !== "" && getGestiones !== false && getGestiones !== 'undefined') {
						gestion = JSON.parse(getGestiones);
						$.each(gestion, function(index, value){
							allGestiones[index] = value;
							if(typeof(allGestiones[index]) == 'object'){
								if(allGestiones[index].hasOwnProperty('FormAbierto') && allGestiones[index].FormAbierto){
									let formData=new FormData($("#frameContenedor").contents().find("#FormularioDatos")[0]);
									let strGestion=formData.get("id_gestion_cbx");
	
									if(strGestion == 'undefined' || strGestion== undefined || strGestion == '0' || strGestion == null || strGestion ==''){
										strGestion=$("#main #FormularioDatos").contents().find("#id_gestion_cbx").val();
										if(strGestion == 'undefined' || strGestion== undefined || strGestion == '0' || strGestion == null || strGestion ==''){
											strGestion='<?php echo $_GET['id_gestion_cbx'];?>';
										}
									}
	
									if(allGestiones[index].id_gestion == strGestion){                   
										if(allGestiones[index].hasOwnProperty('id_user')){
												regGestionado=value.id_user;
										}else{
											regGestionado=-1;
										}                     
									}
	
								}else{
									if(allGestiones[index].id_gestion == $("#idGestionStorage").val()){                        
										if(allGestiones[index].hasOwnProperty('id_user')){
												regGestionado=value.id_user;
										}else{
											regGestionado=-1;
										}                     
									}
								}
							}
						});
					}else{
						reject("No existe el objeto de storage");
					}
					resolve(regGestionado);	
				}); 
			}


            function obtenerIdInsertado(){
				return new Promise((resolve,reject) => {
					console.log('obtiene el id insertado del storage');
					let allGestiones= {};
					let regGestionado = '';                
					let registroInsertado=''; 
					let gestion='';       
					let getGestiones=sessionStorage.getItem("gestiones");
					console.log('storage gestiones',sessionStorage.getItem("gestiones"));
					if (getGestiones !== null && getGestiones !== "" && getGestiones !== false && getGestiones !== 'undefined') {
						gestion = JSON.parse(getGestiones);
						$.each(gestion, function(index, value){
							allGestiones[index] = value;
							if(typeof(allGestiones[index]) == 'object'){
								if(allGestiones[index].hasOwnProperty('FormAbierto') && allGestiones[index].FormAbierto){
									console.log('entro al storage porque SII se abrio el formulario');
									let formData=new FormData($("#frameContenedor").contents().find("#FormularioDatos")[0]);
									let strGestion=formData.get("id_gestion_cbx");
	
									console.log('storage valor gestion campo formData',strGestion);
	
									if(strGestion == 'undefined' || strGestion== undefined || strGestion == '0' || strGestion == null || strGestion ==''){
										strGestion=$("#main #FormularioDatos").contents().find("#id_gestion_cbx").val();
										console.log('storage valor gestion campo main',strGestion);
										if(strGestion == 'undefined' || strGestion== undefined || strGestion == '0' || strGestion == null || strGestion ==''){
											strGestion='<?php echo $_GET['id_gestion_cbx'];?>';
											console.log('storage valor gestion php',strGestion);
										}
									}
	
									console.log('storage valor gestion campo',strGestion);
									if(allGestiones[index].id_gestion == strGestion){                   
										if(allGestiones[index].hasOwnProperty('RegistroInsertado')){
												regGestionado=value.RegistroInsertado;
												// borrarStorage(strGestion);
										}else{
											reject("No se encontro el código identificador del registro gestionado");
										}                     
									}
	
								}else{
									if(allGestiones[index].id_gestion == $("#idGestionStorage").val()){
										console.log('entro al storage porque NOO se abrio el formulario');                        
										if(allGestiones[index].hasOwnProperty('RegistroInsertado')){
												regGestionado=value.RegistroInsertado;
												// borrarStorage($("#idGestionStorage").val());
										}else{
											reject("No se encontro el código identificador del registro gestionado");
										}                     
									}
								}
							}
						});
					}else{
						reject("No existe el objeto de storage");
					}
					resolve(regGestionado);
				});
            }
        </script>
	</head>
	<body id="main">
		<?php

			if(isset($_GET['id_campana_crm'])){

				if(!is_null($_GET['id_campana_crm']) && $_GET['id_campana_crm'] != 'null'){
					$Lsql = "SELECT CAMPAN_TipoCamp__b, CAMPAN_ConsInte__GUION__Gui_b, CAMPAN_ConsInte__GUION__Pob_b, CAMPAN_Nombre____b FROM ".$BaseDatos_systema.".CAMPAN WHERE CAMPAN_ConsInte__b =".$_GET['id_campana_crm'];
			        $resultado = $mysqli->query($Lsql);
			        $CAMPAN_TipoCamp__b = 1;
			        $CAMPAN_ConsInte__GUION__Gui_b = null;
			        $CAMPAN_ConsInte__GUION__Pob_b = null;
			        $nombreCampana = Null;
			        while ($key = $resultado->fetch_object()) {
			            $CAMPAN_TipoCamp__b = $key->CAMPAN_TipoCamp__b;
			            $CAMPAN_ConsInte__GUION__Gui_b = $key->CAMPAN_ConsInte__GUION__Gui_b;
			            $CAMPAN_ConsInte__GUION__Pob_b = $key->CAMPAN_ConsInte__GUION__Pob_b;
			            $nombreCampana = $key->CAMPAN_Nombre____b;
			        }

					if(isset($_GET['busqueda_manual_forzada'])){
						if($_GET['busqueda_manual_forzada'] == 'true'){

							include ('formularios/G'.$CAMPAN_ConsInte__GUION__Pob_b.'/G'.$CAMPAN_ConsInte__GUION__Pob_b.'_Busqueda_Manual.php');
						}else{
							mostrar_guion($CAMPAN_TipoCamp__b, $CAMPAN_ConsInte__GUION__Pob_b);
						}
					}else{
						mostrar_guion($CAMPAN_TipoCamp__b , $CAMPAN_ConsInte__GUION__Pob_b);
			        }
				}else{
					echo "<div class='row'>
							<div style='text-align:center;' class='col-md-12'>
								<div class='alert alert-info'>
									Lo sentimos, pero el identificador de la campaÃ±a que se ha enviado, esta vacio.
								</div>
							</div>
						</div>";
				}
				
		    }

		    if(isset($_GET['formulario'])){
				$LsqlGUION = "SELECT GUION__ConsInte__b, GUION__Nombre____b FROM ".$BaseDatos_systema.".GUION_ WHERE GUION__ConsInte__b =".$_GET['formulario'];
				$results = $mysqli->query($LsqlGUION);
				while($key = $results->fetch_object()){
					$GION_TITLE = utf8_encode($key->GUION__Nombre____b);
				} 
		        if(isset($_GET['busqueda'])){
		            include ('formularios/G'.$_GET['formulario'].'/G'.$_GET['formulario'].'_Busqueda.php');
		        }else{
		            include ('formularios/G'.$_GET['formulario'].'/G'.$_GET['formulario'].'.php');
		        }
		        
		    }


		    if(isset($_GET['campan'])){
	            $Guion = 0;//id de la campaÃ±a
	            $tabla = 0;// $_GET['user'];//idel usuario
	            $Lsql = "SELECT CAMPAN_ConsInte__GUION__Gui_b, CAMPAN_ConsInte__GUION__Pob_b FROM ".$BaseDatos_systema.".CAMPAN WHERE CAMPAN_ConsInte__b = ".$_GET['campana_crm'];

	            $result = $mysqli->query($Lsql);
	            while($obj = $result->fetch_object()){
	                $Guion = $obj->CAMPAN_ConsInte__GUION__Gui_b;
	                $tabla = $obj->CAMPAN_ConsInte__GUION__Pob_b;
	            } 
	            //SELECT de la camic
	            $campSql = "SELECT CAMINC_NomCamPob_b, CAMINC_NomCamGui_b, CAMINC_ConsInte__CAMPO_Gui_b FROM ".$BaseDatos_systema.".CAMINC WHERE CAMINC_ConsInte__CAMPAN_b = ".$_GET['campana_crm'];


	            

				
	            
	            
				$LsqlGUION = "SELECT GUION__ConsInte__b, GUION__Nombre____b FROM ".$BaseDatos_systema.".GUION_ WHERE GUION__ConsInte__b =".$Guion;
			
				$results = $mysqli->query($LsqlGUION);
				//var_dump($results->fetch_object());

			

				while($key = $results->fetch_object()){
					$GION_TITLE = utf8_encode($key->GUION__Nombre____b);

				} 
	            if(isset($_GET['busqueda'])){
	                include ('formularios/G'.$Guion.'/G'.$Guion.'_Busqueda.php');
	            }else{
	                include ('formularios/G'.$Guion.'/G'.$Guion.'.php');
	            }
	        }

	        function mostrar_guion($CAMPAN_TipoCamp__b, $CAMPAN_ConsInte__GUION__Pob_b ){
	        	
	        	if(file_exists('formularios/G'.$CAMPAN_ConsInte__GUION__Pob_b.'/G'.$CAMPAN_ConsInte__GUION__Pob_b.'_Busqueda_Manual.php')){
	        		switch ($CAMPAN_TipoCamp__b) {

			            case 1:
			                include ('formularios/G'.$CAMPAN_ConsInte__GUION__Pob_b.'/G'.$CAMPAN_ConsInte__GUION__Pob_b.'_Busqueda_Manual.php');
			                break;
			            case 2:
			                include ('formularios/G'.$CAMPAN_ConsInte__GUION__Pob_b.'/G'.$CAMPAN_ConsInte__GUION__Pob_b.'_Busqueda_Telefono.php');
			                break;
			            case 3:
			                if(isset($_GET['consinte']) && $_GET['consinte'] == ''  ){
			            		 include ('formularios/G'.$CAMPAN_ConsInte__GUION__Pob_b.'/G'.$CAMPAN_ConsInte__GUION__Pob_b.'_Busqueda_Manual.php');
			                	
			            	}else{
			            		include ('formularios/G'.$CAMPAN_ConsInte__GUION__Pob_b.'/G'.$CAMPAN_ConsInte__GUION__Pob_b.'_Busqueda_ani.php');
			            	}
			                break;
			           	case 4:
			                include ('formularios/G'.$CAMPAN_ConsInte__GUION__Pob_b.'/G'.$CAMPAN_ConsInte__GUION__Pob_b.'_Busqueda_ani.php');
			                break;
			            case 5:
			            	

			                 include ('formularios/G'.$CAMPAN_ConsInte__GUION__Pob_b.'/G'.$CAMPAN_ConsInte__GUION__Pob_b.'_Busqueda_ani.php');
			                break;

			            case 18:
			                include ('formularios/G'.$CAMPAN_ConsInte__GUION__Pob_b.'/G'.$CAMPAN_ConsInte__GUION__Pob_b.'_Busqueda_ani.php');
			                break;

			            case 6:
			                include ('formularios/G'.$CAMPAN_ConsInte__GUION__Pob_b.'/G'.$CAMPAN_ConsInte__GUION__Pob_b.'_Busqueda_ani.php');
			                break;
			            case 7:
			                include ('formularios/G'.$CAMPAN_ConsInte__GUION__Pob_b.'/G'.$CAMPAN_ConsInte__GUION__Pob_b.'_Busqueda_ani.php');
			                break;
			        }
	        	}else{
	        		echo "<div class='row'>
							<div style='text-align:center;' class='col-md-12'>
								<div class='alert alert-info'>
									Lo sentimos, por favor genere las carpetas de esta campaÃ±a, no se han generado los formularios.
								</div>
							</div>
						</div>";
	        	}
	        	
				
	        }
		?>	




		<!-- Scripts de las paginas de busqueda -->
		<!-- Resolve conflict in jQuery UI tooltip with Bootstrap tooltip -->
		 <!-- jQuery UI 1.11.4 -->
        
        <!-- Bootstrap 3.3.6 -->
        <script src="assets/bootstrap/js/bootstrap.min.js"></script>
        <!-- Select2 -->
        <script src="assets/plugins/select2/select2.full.min.js"></script>
        
        <!-- daterangepicker -->
        <script src="assets/js/moment.min.js"></script>
        <script src="assets/plugins/daterangepicker/daterangepicker.js"></script>
        <!-- datepicker -->
        <script src="assets/plugins/datepicker/bootstrap-datepicker.js"></script>
        <!-- Date Picker -->
		<script src="assets/timepicker/jquery.timepicker.js"></script>
        <script src="assets/plugins/sweetalert/sweetalert.min.js"></script>      

		<script type="text/javascript" src="assets/plugins/DateTimePicker/bootstrap-datetimepicker.min.js"></script>
        <script src="assets/Guriddo_jqGrid_/js/i18n/grid.locale-es.js" type="text/javascript"></script>
        <script src="assets/Guriddo_jqGrid_/js/jquery.jqGrid.min.js" type="text/javascript"></script>

        <!-- AdminLTE App -->
        <script src="assets/js/numeric.js"></script>
        <script src="assets/js/alertify.js"></script>
        <script type="text/javascript">
        	$(document).ready(function() {
				let ObjDataGestion= new Object();
				ObjDataGestion.server         = '<?php echo $http; ?>';
				ObjDataGestion.canal          = '<?php if (isset($_GET["canal"])) { echo $_GET["canal"]; }else{ echo "sin canal"; }?>';
				ObjDataGestion.token          = '<?php echo $_GET["token"];?>';
				ObjDataGestion.predictiva     = '<?php if(isset($_GET['predictiva'])) { echo $_GET['predictiva']; } else{ echo 'null'; }?>';
				ObjDataGestion.consinte       = '<?=$consinte?>';
				ObjDataGestion.id_campana_crm = '<?php if(isset($_GET['id_campana_crm'])) { echo $_GET['id_campana_crm']; } else{ echo $_GET['campana_crm']; }?>';
				ObjDataGestion.sentido        = '<?php if(isset($_GET['sentido'])) { echo $_GET['sentido']; } else{ echo 'null'; }?>';
				ObjDataGestion.usuario        = '<?php if(isset($_GET['usuario'])) { echo $_GET['usuario']; } else{ echo $_GET['idUsuari']; }?>';
				ObjDataGestion.origen         = '<?php if(isset($_GET['origen'])) { echo $_GET['origen']; } else{ echo 'Sin origen'; }?>';
				ObjDataGestion.ani            = '<?php if(isset($_GET['ani'])) { echo $_GET['ani']; } else{ echo '0'; }?>';
				ObjDataGestion.formulario     = '<?=$rs['CAMPAN_ConsInte__GUION__Gui_b']?>';

				console.log('llamar al storage por la estación','<?php echo $_GET["id_gestion_cbx"]?>',<?=$consinte?>,ObjDataGestion,null,'<?=$rs['CAMPAN_ConsInte__GUION__Gui_b']?>','<?=isset($_GET['idUSUARI']) ? $_GET['idUSUARI'] : $_GET['usuario']?>','<?=isset($_GET['ani']) ? $_GET['ani'] : null?>','<?=isset($_GET["canal"]) ? $_GET["canal"] : 'Sin canal'?>','<?=isset($_GET["origen"]) ? $_GET["origen"] : 'Sin origen'?>','<?=isset($_GET["sentido"]) ? $_GET["sentido"] : '0'?>',campanaStorage,false,true);
				guardarStorage('<?php echo $_GET["id_gestion_cbx"]?>',<?=$consinte?>,ObjDataGestion,null,'<?=$rs['CAMPAN_ConsInte__GUION__Gui_b']?>','<?=isset($_GET['idUSUARI']) ? $_GET['idUSUARI'] : $_GET['usuario']?>','<?=isset($_GET['ani']) ? $_GET['ani'] : null?>','<?=isset($_GET["canal"]) ? $_GET["canal"] : 'Sin canal'?>','<?=isset($_GET["origen"]) ? $_GET["origen"] : 'Sin origen'?>','<?=isset($_GET["sentido"]) ? $_GET["sentido"] : '0'?>',campanaStorage,false,true);
				//LLAMAR A LA FUNCIÓN DEL STORAGE PARA QUE DETECTE LOS CAMBIOS DE LOS CAMPOS EN LOS FORMULARIOS DE BUSQUEDA MANUAL PARA QUE EL STORAGE PUEDA PERSISTER LA INFORMACIÓN
				agregarEventos();
        		//document.getElementById("FormularioDatos").contentWindow.document.getElementById('Save').click();
    		 	/*$("#FormularioDatos").load(function () {                        
			        frames["FormularioDatos"].getElementById('Save').click();
			    });*/
        	});


        </script>


	</body>
</html>