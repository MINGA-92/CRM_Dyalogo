<?php
	session_start();
    ini_set('display_errors', 'On');
    ini_set('display_errors', 1);
	require_once('../conexion.php');
	
	date_default_timezone_set('America/Bogota');
	if(isset($_POST['idioma'])){
		switch ($_POST['idioma']) {
			case 'en':
				include ('../../idiomas/text_en.php');
				break;

			case 'es':
				include ('../../idiomas/text_es.php');
				break;

			default:
				include ('../../idiomas/text_en.php');
				break;
		}
	}

	$color_fondo_estado = 'gray';

	if(isset($_SESSION['HUESPED'])){
		$order = "fecha_hora_cambio_estado";
		
		if(isset($_SESSION['ORDEN_USERS'])){
            if($_SESSION['ORDEN_USERS'] == "AA"){
                $order = " nombre_usuario ASC ";
            }elseif($_SESSION['ORDEN_USERS'] == "EA"){
                $order = " estado ASC ";
            }elseif($_SESSION['ORDEN_USERS'] == "AD"){
                $order = " nombre_usuario DESC ";
            }elseif($_SESSION['ORDEN_USERS'] == "ED"){
                $order = " estado DESC ";
            }
        }

        

        $data = array(  
		            "strUsuario_t"              =>  'david',
		            "strToken_t"                =>  'david',
		            "intIdHuespedGeneralt"      =>  $_SESSION['HUESPED'],
		        );                                                                    
        $data_string = json_encode($data);   
        //echo $data_string; 
        $ch = curl_init($url_real_time);
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
        echo " Respuesta => ".$respuesta;
        //echo " Error => ".$error;
        if(!empty($respuesta) && !is_null($respuesta)){
            $json = json_decode($respuesta);
            echo '<div class="row" >';
            foreach ($json->objSerializar_t as $key => $value) {
            	$date1 = new DateTime($value['fecha_hora_cambio_estado']);
				$date2 = new DateTime("now");
				$diff = $date1->diff($date2);
				//$fecha = ( ($diff->days * 24 ) * 60 ) + ( $diff->i );
				$fecha = $diff->format("%H:%I:%S");
				$superFEcha = $date1->format("Y/m/d");
				$otraFecha = $diff->format($superFEcha." %H:%I:%S");
				
				$imagenUser = "assets/img/Kakashi.fw.png";
				if(file_exists("/var/../Dyalogo/img_usuarios/usr".$value['id_usuario'].".jpg")){
					$imagenUser = "/DyalogoImagenes/usr".$value['id_usuario'].".jpg";
				}

				if(file_exists("/var/../Dyalogo/img_usuarios/usr".$value['id_usuario'].".png")){
					$imagenUser = "/DyalogoImagenes/usr".$value['id_usuario'].".jpg";
				}





				$color = 'green';
				$canal = '';
				$style = 'style="border:solid green 4px; width: 61px; height: 61px;"';
				$pausa = '';
				if($value['estado'] == 'Disponible'){ //VERDE
					$color = 'green';
					$style = 'style="border:solid green 4px; width: 61px; height: 61px;"';
				}else if($value['estado'] == 'Inicial' || $value['estado'] == 'No disponible'){ //GRIS
					$color = 'gray';
					$style = 'style="border:solid gray 4px; width: 61px; height: 61px;"';

				}else if($value['estado'] == 'Pausado'){ //AMARILLO
					$color = '#f39c12';
					$style = 'style="border:solid #f39c12 4px; width: 61px; height: 61px;"';
					$pausa = "<tr>
									<th>".$str_tipo_pausa."</th>
									<td style='color:#f39c12;'>".strtoupper(($value['pausa']))."</td>
								</tr>";
				}else{
					$color = 'red';
					$style = 'style="border:solid red 4px; width: 61px; height: 61px;"';
					$pausa = "<tr>
									<th>".$str_proceso_actual."</th>
									<td style='color:red' width='50%'>".strtoupper(($value['id_estrategia']))."</td>
								</tr>
								<tr>
									<th>".$str_campana_actual."</th>
									<td style='color:red' width='50%'>".strtoupper(($value['campana_actual']))."</td>
								</tr>
								<tr>
									<th>".$str_dato_principal."</th>
									<td style='color:red' width='50%'>".strtoupper(($value['dato_principal']))."</td>
								</tr>
								<tr>
									<th>".$str_dato_segundario."</th>
									<td style='color:red' width='50%'>".strtoupper(($value['dato_secundario']))."</td>
								</tr>";

					
					switch ($value['canal_actual']) {
						case 'voz':
							if($value['sentido'] == 'in'){
								$canal = "<i class='fa fa-arrow-right'></i>&nbsp;<i class='fa fa-phone'></i>";
							}else{
								$canal = "<i class='fa fa-phone'></i>&nbsp;<i class='fa fa-arrow-right'></i>";
							}

							
							break;

						case 'correo':
							if($value['sentido'] == 'in'){
								$canal = "<i class='fa fa-arrow-right'></i>&nbsp;<i class='fa fa-envelope-o'></i>";
							}else{
								$canal = "<i class='fa fa-envelope-o'></i>&nbsp;<i class='fa fa-arrow-right'></i>";
							}

							
							break;

						case 'chat':
							if($value['sentido'] == 'in'){
								$canal = "<i class='fa fa-arrow-right'></i>&nbsp;<i class='fa fa-comment-o'></i>";
							}else{
								$canal = "<i class='fa fa-comment-o'></i>&nbsp;<i class='fa fa-arrow-right'></i>";
							}

							
							break;
							
					}
				}

				$datosHtml = "<table class='table' style='font-size:12px;' width='300px'>
								<tr>
									<td>
										<img src='".$imagenUser."' class='img-circle' style='width:100px; height:100px; border:solid ".$color." 4px;'>
									</td>
									<td width='50%'>
										<table class='table table-bordered' width='100%'>
											<tr>
												<td>".$value['identificacion_usuario']."</td>
											</tr>
											<tr>
												<td style='color:".$color.";'>".$value['estado']."</td>
											</tr>
											<tr>
												<td style='color:".$color.";' id='relojTDJose_".$value['id_usuario']."'>".$fecha."</td>
											</tr>
											<tr>
												<td style='color:".$color_fondo_estado.";'>".$canal."</td>
											</tr>
										</table>
									</td>
								</tr>".$pausa."
							</table>";

				if(isset($_SESSION['TAMANHO_CAMPAN'])){
	                $tamanho = $_SESSION['TAMANHO_CAMPAN'];
	                if( $tamanho == 3){
	                	echo '	<div class="col-md-2 col-xs-12" style="text-align:center;padding-bottom:2px;">';
	                }elseif ( $tamanho == 4){
	                	echo '	<div class="col-md-2 col-xs-12" style="text-align:center;padding-bottom:2px;">';
	                }elseif ( $tamanho == 6){
	                	echo '	<div class="col-md-3 col-xs-12" style="text-align:center;padding-bottom:2px;">';
	                }elseif ( $tamanho == 9){
	                	echo '	<div class="col-md-6 col-xs-12" style="text-align:center;padding-bottom:2px;">';
	                }elseif( $tamanho == 0){
	                   	echo '	<div class="col-md-2 col-xs-12" style="text-align:center;margin:0 auto;">';

	                }elseif( $tamanho == 12){
	                   	echo '	<div class="col-md-12 col-xs-12" style="text-align:center;padding-bottom:2px;">';
	                }
	            }else{
	            	echo '	<div class="col-md-2 col-xs-12" style="text-align:center;padding-bottom:2px;">';
	            }
			

				echo '
			              	<img src="'.$imagenUser.'"  data-toggle="popover" data-trigger="hover" title="<b>'.strtoupper(($value['nombre_usuario'])).'</b>" data-content="'.$datosHtml.'"  '.$style.' alt="'.($value['nombre_usuario']).'" title="'.($value['nombre_usuario']).'" class="imagenuUsuarios img-circle" idUsuario="'.$value['id_usuario'].'">
			              	<a class="users-list-name" style="color:'.$color.';"  href="#">'.strtoupper(($value['nombre_usuario'])).'</a>
			              	<span class="users-list-date"  style="color:'.$color_fondo_estado.';" >'.$canal.' &nbsp; <span style="color:'.$color.';" id="relojJose_'.$value['id_usuario'].'">'.$fecha.'</span></span>
			          	</div>';

				$y = $diff->format("%Y");
				$m = $diff->format("%M");
				$d = $diff->format("%D");
				$h = $diff->format("%H");
				$is = $diff->format("%I");
				$s = $diff->format("%S");
				//'".$y."','".$m."','".$d."','".$h."','".$is."','".$s."'
				echo "	<script type='text/javascript'>
							function reloj_".$value['id_usuario']."() {
							    var date1 = new Date();
							    var date2 = new Date(\"".$value['fecha_hora_cambio_estado']."\");
							    //Customise date2 for your required future time

							    var diff = (date2 - date1)/1000;
							    var diff = Math.abs(Math.floor(diff));

							    var days = Math.floor(diff/(24*60*60));
							    var leftSec = diff - days * 24*60*60;

							    var hrs = Math.floor(leftSec/(60*60));
							    var leftSec = leftSec - hrs * 60*60;

							    var min = Math.floor(leftSec/(60));
							    var leftSec = leftSec - min * 60;
							    hrs = colocarCero_".$value['id_usuario']."(hrs);
							    min = colocarCero_".$value['id_usuario']."(min);
							    leftSec = colocarCero_".$value['id_usuario']."(leftSec);
							    $('#relojJose_".$value['id_usuario']."').html(hrs+\":\"+min+\":\"+leftSec);
							    $('#relojTDJose_".$value['id_usuario']."').html(hrs+\":\"+min+\":\"+leftSec);

							    var t = setTimeout(function(){
							    			reloj_".$value['id_usuario']."();
							    		},1000);
							}

							function colocarCero_".$value['id_usuario']."(i){
								if(i < 10){ i = '0'+ i ;}
								return i;
							}

							$(function(){
								reloj_".$value['id_usuario']."();
							});
						</script>";
            }
    		echo '</div>';

        }

		


			

	}

	function getFile($id){
		$mime_type = mime_content_type("/Dyalogo/img_usuarios/usr".$id.".jpg");
   		header('Content-Type: '.$mime_type);
    	return readfile("/Dyalogo/img_usuarios/usr".$id.".jpg");
	}
?>
