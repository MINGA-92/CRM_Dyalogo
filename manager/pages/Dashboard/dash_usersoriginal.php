<?php
	session_start();
    ini_set('display_errors', 'On');
    ini_set('display_errors', 1);
    require_once('../conexion.php');
    include("../../global/WSCoreClient.php");
    date_default_timezone_set('America/Bogota');

	
	if (isset($_GET['contador'])) {

                $json = json_decode(agrupacionEstadosTiempoReal($_SESSION['IDENTIFICACION']));

                $dis = 0;
                $pau = 0;
                $ocu = 0;
                $nodis = 0;

                foreach ($json->objSerializar_t as $key) {
                  if($key->strDato_t=="Disponible"){
                        $dis = $key->intIntCantidad_t;
                  }elseif($key->strDato_t == 'Pausado'){
                        $pau = $key->intIntCantidad_t;
                  }elseif(stristr($key->strDato_t, 'Ocupado')){
                        $ocu = $key->intIntCantidad_t;
                  }else{
                        $nodis = $key->intIntCantidad_t;
                  }
                }


                echo json_encode(["dis"=>$dis,"pau"=>$pau,"ocu"=>$ocu,"nodis"=>$nodis]);

        }else{

	
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

        

        $respuesta = listaAgentesTiempoReal($_SESSION['IDENTIFICACION']);
        
        if(!empty($respuesta) && !is_null($respuesta)){
            $json = json_decode($respuesta);
            echo '<div class="row" >';
            foreach ($json->objSerializar_t as $key) {
            	$date1 = new DateTime($key->strFechaHoraCambioEstado_t);
				$date2 = new DateTime("now");
				$diff = $date1->diff($date2);
				$fecha = $diff->format("%H:%I:%S");
				$superFEcha = $date1->format("Y/m/d");
				$otraFecha = $diff->format($superFEcha." %H:%I:%S");
				
				$imagenUser = "assets/img/Kakashi.fw.png";
				if(file_exists("/var/../Dyalogo/img_usuarios/usr".$key->idUsuario.".jpg")){
					$imagenUser = "/DyalogoImagenes/usr".$key->idUsuario.".jpg";
				}

				if(file_exists("/var/../Dyalogo/img_usuarios/usr".$key->idUsuario.".png")){
					$imagenUser = "/DyalogoImagenes/usr".$key->idUsuario.".jpg";
				}





				$color = 'green';
				$canal = '';
				$style = 'style="border:solid green 4px; width: 61px; height: 61px;"';
				$pausa = '';
				if($key->estado == 'Disponible'){ //VERDE
					$color = 'green';
					$style = 'style="border:solid green 4px; width: 61px; height: 61px;"';
				}else if($key->estado == 'Pausado'){ //AMARILLO
					$color = '#f39c12';
					$style = 'style="border:solid #f39c12 4px; width: 61px; height: 61px;"';
					$pausa = "<tr>
									<th>".$str_tipo_pausa."</th>
									<td style='color:#f39c12;'>".strtoupper(($key->pausa))."</td>
								</tr>";
				}elseif(stristr($key->estado, 'Ocupado')){
					$color = 'red';
					$style = 'style="border:solid red 4px; width: 61px; height: 61px;"';
					$pausa = "<tr>
									<th>".$str_proceso_actual."</th>
									<td style='color:red' width='50%'>".strtoupper(($key->idEstrategia))."</td>
								</tr>
								<tr>
									<th>".$str_campana_actual."</th>
									<td style='color:red' width='50%'>".strtoupper(($key->campanaActual))."</td>
								</tr>
								<tr>
									<th>".$str_dato_principal."</th>
									<td style='color:red' width='50%'>".strtoupper(($key->datoPrincipal))."</td>
								</tr>
								<tr>
									<th>".$str_dato_segundario."</th>
									<td style='color:red' width='50%'>".strtoupper(($key->datoSecundario))."</td>
								</tr>";

					
					switch ($key->canalActual) {
						case 'voz':
							if($key->sentido == 'in'){
								$canal = "<i class='fa fa-arrow-right'></i>&nbsp;<i class='fa fa-phone'></i>";
							}else{
								$canal = "<i class='fa fa-phone'></i>&nbsp;<i class='fa fa-arrow-right'></i>";
							}

							
							break;

						case 'correo':
							if($key->sentido == 'in'){
								$canal = "<i class='fa fa-arrow-right'></i>&nbsp;<i class='fa fa-envelope-o'></i>";
							}else{
								$canal = "<i class='fa fa-envelope-o'></i>&nbsp;<i class='fa fa-arrow-right'></i>";
							}

							
							break;

						case 'chat':
							if($key->sentido == 'in'){
								$canal = "<i class='fa fa-arrow-right'></i>&nbsp;<i class='fa fa-comment-o'></i>";
							}else{
								$canal = "<i class='fa fa-comment-o'></i>&nbsp;<i class='fa fa-arrow-right'></i>";
							}

							
							break;
							
					}
				}else{
					$color = 'gray';
					$style = 'style="border:solid gray 4px; width: 61px; height: 61px;"';
				}

				$datosHtml = "<table class='table' style='font-size:12px;' width='300px'>
								<tr>
									<td>
										<img src='".$imagenUser."' class='img-circle' style='width:100px; height:100px; border:solid ".$color." 4px;'>
									</td>
									<td width='50%'>
										<table class='table table-bordered' width='100%'>
											<tr>
												<td>".$key->nombreUsuario."</td>
											</tr>
											<tr>
												<td style='color:".$color.";'>".$key->estado."</td>
											</tr>
											<tr>
												<td style='color:".$color.";' id='relojTDJose_".$key->idUsuario."'>".$fecha."</td>
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
	                	echo '	<div class="col-md-2 col-xs-5" style="text-align:center;padding-bottom:2px;">';
	                }elseif ( $tamanho == 7){
	                	echo '	<div class="col-md-3 col-xs-12" style="text-align:center;padding-bottom:2px;">';
	                }elseif ( $tamanho == 9){
	                	echo '	<div class="col-md-6 col-xs-12" style="text-align:center;padding-bottom:2px;">';
	                }elseif( $tamanho == 0){
	                   	echo '	<div class="col-md-2 col-xs-12" style="text-align:center;margin:0 auto;">';

	                }elseif( $tamanho == 12){
	                   	echo '	<div class="col-md-12 col-xs-12" style="text-align:center;padding-bottom:2px;">';
	                }
	            }else{
	            	echo '	<div class="col-md-3 col-xs-12" style="text-align:center;padding-bottom:2px;">';
	            }
			

				echo '
			              	<img src="'.$imagenUser.'"  data-toggle="popover" data-trigger="hover" alt="Dyalogo" data-content="'.$datosHtml.'"  '.$style.' alt="'.($key->nombreUsuario).'" title="'.($key->nombreUsuario).'" class="imagenuUsuarios img-circle" idUsuario="'.$key->idUsuario.'">
			              	<a class="users-list-name" style="color:'.$color.';"  href="#">'.strtoupper(($key->nombreUsuario)).'</a>
			              	<span class="users-list-date"  style="color:'.$color_fondo_estado.';" >'.$canal.' &nbsp; <span style="color:'.$color.';" id="relojJose_'.$key->idUsuario.'">'.$fecha.'</span></span>
			          	</div>';

				$y = $diff->format("%Y");
				$m = $diff->format("%M");
				$d = $diff->format("%D");
				$h = $diff->format("%H");
				$is = $diff->format("%I");
				$s = $diff->format("%S");
				//'".$y."','".$m."','".$d."','".$h."','".$is."','".$s."'
				echo "	<script type='text/javascript'>
							function reloj_".$key->idUsuario."() {
							    var date1 = new Date();
							    var date2 = new Date(\"".$key->strFechaHoraCambioEstado_t."\");
							    //Customise date2 for your required future time

							    var diff = (date2 - date1)/1000;
							    var diff = Math.abs(Math.floor(diff));

							    var days = Math.floor(diff/(24*60*60));
							    var leftSec = diff - days * 24*60*60;

							    var hrs = Math.floor(leftSec/(60*60));
							    var leftSec = leftSec - hrs * 60*60;

							    var min = Math.floor(leftSec/(60));
							    var leftSec = leftSec - min * 60;
							    hrs = colocarCero_".$key->idUsuario."(hrs);
							    min = colocarCero_".$key->idUsuario."(min);
							    leftSec = colocarCero_".$key->idUsuario."(leftSec);
							    $('#relojJose_".$key->idUsuario."').html(hrs+\":\"+min+\":\"+leftSec);
							    $('#relojTDJose_".$key->idUsuario."').html(hrs+\":\"+min+\":\"+leftSec);

							    var t = setTimeout(function(){
							    			reloj_".$key->idUsuario."();
							    		},1000);
							}

							function colocarCero_".$key->idUsuario."(i){
								if(i < 10){ i = '0'+ i ;}
								return i;
							}

							$(function(){
								reloj_".$key->idUsuario."();
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
	}
?>
