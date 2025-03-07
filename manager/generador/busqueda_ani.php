<?php
	function generar_Busqueda_Ani($idFormulario_Crear){

		global $mysqli;
		global $BaseDatos;
		global $BaseDatos_systema;
		global $BaseDatos_telefonia;
		global $dyalogo_canales_electronicos;
		global $BaseDatos_general;
		
		if(!is_null($idFormulario_Crear)){

			if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
			    $carpeta = "C:/xampp/htdocs/crm_php/formularios/G".$idFormulario_Crear;
			} else {
			    $carpeta = "/var/www/html/crm_php/formularios/G".$idFormulario_Crear;
			}

			
		    if (!file_exists($carpeta)) {
		        mkdir($carpeta, 0777);
		    }

		    $fp = fopen($carpeta."/G".$idFormulario_Crear."_Busqueda_Telefono.php" , "w");
		    //chmod($carpeta."/G".$idFormulario_Crear."_Busqueda_Telefono.php" , 0777); 


		    $tablaCuerpo = null;
			$tablaCampos = null;
			$datos = 'buscarAni:true,<?php if(isset($_GET[\'consinte\'])){ if($_GET[\'consinte\'] != \'-1\' ){ echo "consinte:". $_GET[\'consinte\']." , ";} } ?><?php if(isset($_GET[\'dato_adicional_1\']) && $_GET[\'dato_adicional_1\'] != \'\'){ echo " dato_adicional_1 : \'". $_GET[\'dato_adicional_1\']."\',"; }?><?php if(isset($_GET[\'dato_adicional_2\']) && $_GET[\'dato_adicional_2\'] != \'\' ){ echo " dato_adicional_2 : \'". $_GET[\'dato_adicional_2\']."\',"; }?><?php if(isset($_GET[\'dato_adicional_3\']) && $_GET[\'dato_adicional_3\'] != \'\' ){ echo " dato_adicional_3 : \'". $_GET[\'dato_adicional_3\']."\',"; }?><?php if(isset($_GET[\'dato_adicional_4\']) && $_GET[\'dato_adicional_4\'] != \'\' ){ echo "dato_adicional_4: \'". $_GET[\'dato_adicional_4\']."\',"; }?><?php if(isset($_GET[\'dato_adicional_5\']) && $_GET[\'dato_adicional_5\'] != \'\'){ echo " dato_adicional_5 : \'". $_GET[\'dato_adicional_5\']."\',"; }?><?php if (isset($_GET[\'canal\'])) { echo " canal : \'".$_GET[\'canal\']."\',";}?><?php if (isset($_GET[\'ani\'])) { echo " ani : \'".$_GET[\'ani\']."\',"; } ?>';

			
			$strDatoNuevo="";
			$Guionsqli = "SELECT * FROM ".$BaseDatos_systema.".GUION_ WHERE GUION__ConsInte__b = ".$idFormulario_Crear;
			//echo "Primeros => ".$Guionsqli."\n";
			$r = $mysqli->query($Guionsqli);
			if($r && $r->num_rows == 1){
				$r=$r->fetch_object();
				$strDatoNuevo=$r->GUION_INSERTAUTO_b == -1 ? "&datonuevo=si" : "";
			}
			// while ($key = $r->fetch_object()) {
			// 	$tablaCuerpo .= '<td>\'+ item.G'.$idFormulario_Crear.'_C'.$key->GUION__ConsInte__PREGUN_Pri_b.'+\'</td>';
			// 	$tablaCuerpo .= '<td>\'+ item.G'.$idFormulario_Crear.'_C'.$key->GUION__ConsInte__PREGUN_Sec_b.'+\'</td>';

			// 	$LsqlPregun = "SELECT PREGUN_Texto_____b FROM ".$BaseDatos_systema.".PREGUN WHERE PREGUN_ConsInte__b = ".$key->GUION__ConsInte__PREGUN_Pri_b;
			// 	//echo "Jose => " .$LsqlPregun;
			// 	$r1 = $mysqli->query($LsqlPregun);
			// 	while ($key1 = $r1->fetch_object()) {
			// 		$tablaCampos .= '<th>'.($key1->PREGUN_Texto_____b).'</th>';
			// 	}
			// 	$LsqlPregun2 = "SELECT PREGUN_Texto_____b FROM ".$BaseDatos_systema.".PREGUN WHERE PREGUN_ConsInte__b = ".$key->GUION__ConsInte__PREGUN_Sec_b;
			// 	$r2 = $mysqli->query($LsqlPregun2);
			// 	while ($key2 = $r2->fetch_object()) {
			// 		$tablaCampos .= '<th>'.($key2->PREGUN_Texto_____b).'</th>';
			// 	}
			// }

		    $campo = ' 	
<?php
	$http = "http://".$_SERVER["HTTP_HOST"];
	if (isset($_SERVER[\'HTTPS\'])) {
	    $http = "https://".$_SERVER["HTTP_HOST"];
	}
?>
<script type="text/javascript">
	function autofitIframe(id){
		if (!window.opera && document.all && document.getElementById){
			id.style.height=id.contentWindow.document.body.scrollHeight;
		} else if(document.getElementById) {
			id.style.height=id.contentDocument.body.scrollHeight+"px";
		}
	}
</script>
<div class="row">
	<div class="col-md-12" id="resultadosBusqueda">
		
	</div>
</div>
<div class="row">
	<div class="col-md-12" id="gestiones">
		<iframe id="frameContenedor" src="" style="width: 100%; height: 2500px;"  marginheight="0" marginwidth="0" noresize  frameborder="0" onload="autofitIframe(this);">
              
        </iframe>
	</div>
</div>
<script type="text/javascript">
	$(function(){
		buscar_valores();
	});
    
    function bindEvent(element, eventName, eventHandler) {
        if (element.addEventListener) {
            element.addEventListener(eventName, eventHandler, false);
        } else if (element.attachEvent) {
            element.attachEvent(\'on\' + eventName, eventHandler);
        }
    }

    function llamarHijo(datos){
        setTimeout(function(){
           var formDatas = datos;
            var iframe = document.getElementById(\'frameContenedor\'); 
            iframe.contentWindow.postMessage(formDatas, \'*\');                                 
        },2000);  
    }    

	function buscar_valores(){

		$.ajax({
			url     	: \'formularios/generados/PHP_Busqueda_Ani.php?action=GET_DATOS&campana_crm=<?php echo $_GET[\'id_campana_crm\'];?>	\',
			type		: \'post\',
			dataType	: \'json\',
			data		: { '.$datos.' },
			success 	: function(datosq){
				//alert(datosq[0].cantidad_registros);
				if(datosq[0].cantidad_registros > 1){
                
                    var iframe = document.getElementById(\'frameContenedor\'); 
                    iframe.contentWindow.postMessage(datosq, \'*\');                                 
                   
                    $("#resulados").hide();
                    <?php if(isset($_GET[\'token\'])){ ?>
                    $("#frameContenedor").attr(\'src\', \'<?php echo $http ;?>/crm_php/Estacion_contact_center.php?canal=<?php if (isset($_GET["canal"])) { echo $_GET["canal"]; }else{ echo "sin canal"; } ?>&token=<?php echo $_GET["token"];?>&id_gestion_cbx=<?php echo $_GET["id_gestion_cbx"];?>&id_campana_crm=<?php echo $_GET[\'id_campana_crm\'];?><?php if(isset($_GET[\'predictiva\'])) { echo "&predictiva=".$_GET[\'predictiva\']; }?><?php if(isset($_GET[\'consinte\'])) { echo "&consinte=".$_GET[\'consinte\']; }?><?php if(isset($_GET[\'sentido\'])) { echo "&sentido=".$_GET[\'sentido\']; }?><?php if(isset($_GET["usuario"])) { echo "&usuario=".$_GET["usuario"]; }?><?php if(isset($_GET[\'ani\'])){ echo "&ani=".$_GET[\'ani\'];}?>&busqueda_manual_forzada=true&dato_adicional_1=Llamada<?php if(isset($_GET[\'origen\'])) { echo \'&origen=\'.$_GET[\'origen\']; }?><?php if(isset($_GET[\'id_campana_cbx\'])){ echo "&id_campana_cbx=".$_GET[\'id_campana_cbx\'];}?>\');
                    <?php } ?>
                    
                    llamarHijo(datosq);
                    
				}else if(datosq[0].cantidad_registros == 1){
					$("#buscador").hide();
					$("#botones").hide();
					$("#resulados").hide();
					let id = datosq[0].registros[0].G'.$idFormulario_Crear.'_ConsInte__b;
					if(datosq[0].registros[0].id_muestra == null){
						$.ajax({
							url     	: \'formularios/generados/PHP_Ejecutar.php?action=ADD_MUESTRA&campana_crm=<?php echo $_GET[\'id_campana_crm\'];?>\',
							type		: \'post\',
							data        : {id:id},
							success 	: function(data){
								<?php if(isset($_GET[\'token\'])) { ?>
									$.ajax({
										url     	: \'formularios/generados/PHP_Ejecutar.php?action=UP_MUESTRA&campana_crm=<?php echo $_GET[\'id_campana_crm\'];?>&agente=<?=$_GET["usuario"]?>\',
										type		: \'post\',
										data        : {id:id},
										success 	: function(data){
											if(data == "1"){
												$("#frameContenedor").attr(\'src\', \'<?php echo $http ;?>/crm_php/Estacion_contact_center.php?campan=true&user=\'+ id +\'&view=si&canal=<?php if (isset($_GET["canal"])) { echo $_GET["canal"]; }else{ echo "Sin canal"; } ?>&token=<?php echo $_GET["token"];?>&id_gestion_cbx=<?php echo $_GET[\'id_gestion_cbx\'];?><?php if(isset($_GET[\'predictiva\'])) { echo "&predictiva=".$_GET[\'predictiva\']; }?>&consinte=\'+ id +\'<?php if(isset($_GET[\'id_campana_crm\'])) { echo "&campana_crm=".$_GET[\'id_campana_crm\']; }?><?php if(isset($_GET[\'sentido\'])) { echo "&sentido=".$_GET[\'sentido\']; }?><?php if(isset($_GET["usuario"])) { echo "&usuario=".$_GET["usuario"]; }?><?php if(isset($_GET[\'origen\'])){echo \'&origen=\'.$_GET[\'origen\'];}else{echo \'&origen=BusquedaManual\';} ?>\');
											}else{
												$("#buscador").show();
												$("#botones").show();
												$("#resulados").show();
												swal({
													html : true,
													title : "Registro ocupado",
													text : "Este registro esta siendo gestionado por otro agente",
													type: "warning",
													confirmButtonText:"Gestionar otro registro",
													closeOnConfirm : true
												});
											}
										}
									});
								<?php } ?>
							}
						});
					}else{
						<?php if(isset($_GET[\'token\'])) { ?>
							$.ajax({
								url     	: \'formularios/generados/PHP_Ejecutar.php?action=UP_MUESTRA&campana_crm=<?php echo $_GET[\'id_campana_crm\'];?>&agente=<?=$_GET["usuario"]?>\',
								type		: \'post\',
								data        : {id:id},
								success 	: function(data){
									if(data == "1"){
										$("#frameContenedor").attr(\'src\', \'<?php echo $http ;?>/crm_php/Estacion_contact_center.php?campan=true&user=\'+ id +\'&view=si&canal=<?php if (isset($_GET["canal"])) { echo $_GET["canal"]; }else{ echo "Sin canal"; } ?>&token=<?php echo $_GET["token"];?>&id_gestion_cbx=<?php echo $_GET[\'id_gestion_cbx\'];?><?php if(isset($_GET[\'predictiva\'])) { echo "&predictiva=".$_GET[\'predictiva\']; }?>&consinte=\'+ id +\'<?php if(isset($_GET[\'id_campana_crm\'])) { echo "&campana_crm=".$_GET[\'id_campana_crm\']; }?><?php if(isset($_GET[\'sentido\'])) { echo "&sentido=".$_GET[\'sentido\']; }?><?php if(isset($_GET["usuario"])) { echo "&usuario=".$_GET["usuario"]; }?><?php if(isset($_GET[\'origen\'])){echo \'&origen=\'.$_GET[\'origen\'];}else{echo \'&origen=BusquedaManual\';} ?>\');
									}else{
										$("#buscador").show();
										$("#botones").show();
										$("#resulados").show();
										swal({
											html : true,
											title : "Registro ocupado",
											text : "Este registro esta siendo gestionado por otro agente",
											type: "warning",
											confirmButtonText:"Gestionar otro registro",
											closeOnConfirm : true
										});
									}
								}
							});
						<?php } ?>                        
					}
				}else{
                    var iframe = document.getElementById(\'frameContenedor\'); 
                    iframe.contentWindow.postMessage(datosq, \'*\');       
                    $("#resulados").hide();
                    <?php if(isset($_GET[\'token\'])){ ?>
                    $("#frameContenedor").attr(\'src\', \'<?php echo $http ;?>/crm_php/Estacion_contact_center.php?canal=<?php if (isset($_GET["canal"])) { echo $_GET["canal"]; }else{ echo "sin canal ani"; } ?>&token=<?php echo $_GET["token"];?>&id_gestion_cbx=<?php echo $_GET["id_gestion_cbx"];?>&id_campana_crm=<?php echo $_GET[\'id_campana_crm\'];?><?php if(isset($_GET[\'predictiva\'])) { echo "&predictiva=".$_GET[\'predictiva\']; }?><?php if(isset($_GET[\'consinte\'])) { echo "&consinte=".$_GET[\'consinte\']; }?><?php if(isset($_GET[\'sentido\'])) { echo "&sentido=".$_GET[\'sentido\']; }?><?php if(isset($_GET["usuario"])) { echo "&usuario=".$_GET["usuario"]; }?><?php if(isset($_GET[\'ani\'])){ echo "&ani=".$_GET[\'ani\'];}?>&busqueda_manual_forzada=true&dato_adicional_1=Llamada<?php if(isset($_GET[\'origen\'])) { echo \'&origen=\'.$_GET[\'origen\']; }?><?php if(isset($_GET[\'id_campana_cbx\'])){ echo "&id_campana_cbx=".$_GET[\'id_campana_cbx\'];}?>'.$strDatoNuevo.'\');
                    <?php } ?>
					llamarHijo(datosq);
				}
			}
		});
	}
</script>';
			/* Escribimos en el archivo todo el codigo que generamos arriba */
		
			/* Escribimos la funcion de busqueda y cerramos el editor */

			fputs($fp , $campo);
			fputs($fp , chr(13).chr(10)); // Genera saldo de linea 
			fclose($fp); 

		}else{
			echo "no se puede generar si no me envias nada";
		}
	}
