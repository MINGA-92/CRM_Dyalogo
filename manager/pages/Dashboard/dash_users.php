<?php
	session_start();
    ini_set('display_errors', 'On');
    ini_set('display_errors', 1);
    require_once('../conexion.php');
    include("../../global/WSCoreClient.php");
    include("../../global/funcionesGenerales.php");
	include("./MetricasTiempoReal/getMetricas.php");
    date_default_timezone_set('America/Bogota');

    function unique_multidim_array($array, $key) { 

       $array = (array)$array;

	   $temp_array = array(); 
	   $i = 0; 
	   $key_array = array(); 

	   foreach($array as $val) { 

	   		$val = (array)$val;

	       if (!in_array($val[$key], $key_array)) { 
	           $key_array[$i] = $val[$key]; 
	           $temp_array[$i] = $val; 
	       } 
	       $i++; 
	   } 
	   return $temp_array; 
	} 

	if(isset($_SESSION['TAMANHO_CAMPAN'])){
		$tamanho = $_SESSION['TAMANHO_CAMPAN'];
	}else{
		$tamanho = 6;
	}

	// if (isset($_GET['contador'])) {

    //             $json = json_decode(agrupacionEstadosTiempoReal($_SESSION['IDENTIFICACION']));

    //             $dis = 0;
    //             $pau = 0;
    //             $ocu = 0;
    //             $nodis = 0;
				
	// 			$value = isset($json->objSerializar_t) ? $json->objSerializar_t : false; 
	// 			if ($value) {
    //             foreach ($json->objSerializar_t as $key) {
    //               if($key->strDato_t=="Disponible"){
    //                     $dis = $key->intIntCantidad_t;
    //               }elseif($key->strDato_t == 'Pausado'){
    //                     $pau = $key->intIntCantidad_t;
    //               }elseif(stristr($key->strDato_t, 'Ocupado')){
    //                     $ocu = $key->intIntCantidad_t;
    //               }else{
    //                     $nodis = $key->intIntCantidad_t;
    //               }
    //             }
	// 		}


    //             echo json_encode(["dis"=>$dis,"pau"=>$pau,"ocu"=>$ocu,"nodis"=>$nodis]);

    //     }else{

	
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

        // Ahora se obtiene la info desde el singleton
        $respuesta = getAgents();
		$dis = 0;
		$pau = 0;
		$ocu = 0;
		$nodis = 0;
		
        if(!empty($respuesta) && !is_null($respuesta)){
            $json = json_decode($respuesta);

			// Primero filtramos los usuarios que necesitamos mostrar
			if($_SESSION["UNO"]){
				$json->objSerializar_t = array_filter( $json->objSerializar_t, function( $v ) { return $v->idHuesped == $_SESSION["HUESPED_CRM"] ; } );
			}else{
				$options = [];
				foreach ($_SESSION["HUESPED_LIST"] as  $value) {
				array_push($options, (int)$value);
				}

				$json->objSerializar_t = array_filter( $json->objSerializar_t, function( $v ) use ($options) { return in_array($v->idHuesped, $options); } );
			}

            $arrAgentes_t = $json->objSerializar_t;

            $arrAgentes_t = unique_multidim_array($arrAgentes_t,"idUsuario");

            echo '<div class="row" id="divAge" style="padding: 0;">';
            foreach ($arrAgentes_t as $key) {
            	$key = (object)$key;

				switch (explode(" :: ", $key->estado )[0]) {
					case 'Disponible':
						$dis++;
						break;
					case 'Ocupado':
						$ocu ++;
						break;
					case 'Pausado':
						$pau++;
						break;
					case 'No disponible':
						$nodis++;
						break;
				}


				$date1 = new DateTime($key->strFechaHoraCambioEstado_t);
				$date2 = new DateTime("now");
				$diff = $date1->diff($date2);
				$fecha = $diff->format("%H:%I:%S");

				$changeTime = (isset($key->strFechaHoraCambioEstado_t)) ? $key->strFechaHoraCambioEstado_t : null;
				
				$imagenUser = "assets/img/Kakashi.fw.png";
				if(file_exists("/var/../Dyalogo/img_usuarios/usr".$key->idUsuario.".jpg")){
					$strFileName = (isset($key->strUSUARIFoto_t)) ? $key->strUSUARIFoto_t : $key->idUsuario.".jpg" ;
					$imagenUser = "/DyalogoImagenes/usr".$strFileName;
				}

				if(file_exists("/var/../Dyalogo/img_usuarios/usr".$key->idUsuario.".png")){
					$tempVar = explode("?",$key->strUSUARIFoto_t)[1];
					$imagenUser = "/DyalogoImagenes/usr".$key->idUsuario.".png?".$tempVar;
				}

				$color = 'green';
				$canal = '';
				$style = 'style="border:solid green 4px; width: 60px; height: 60px;"';
				$pausa = '';
				if($key->estado == 'Disponible'){ //VERDE
					$color = 'green';
					$style = 'style="border:solid green 4px; width: 60px; height: 60px;"';
				}else if($key->estado == 'Pausado'){ //AMARILLO
					$color = '#f39c12';
					$style = 'style="border:solid #f39c12 4px; width: 60px; height: 60px;"';
					$pausa = "<tr>
									<th>".$str_tipo_pausa."</th>
									<td style='color:#f39c12;'>".strtoupper(($key->pausa))."</td>
								</tr>";
				}elseif(stristr($key->estado, 'Ocupado')){
					$color = 'red';
					$style = 'style="border:solid red 4px; width: 60px; height: 60px;"';
					$pausa = "
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
								$canal = "<i class='fa fa-arrow-right' style = 'font-size:7px'></i>&nbsp;<i class='fa fa-phone' style = 'font-size:7px'></i>";
								
							}else{
								$canal = "<i class='fa fa-phone' style = 'font-size:7px'></i>&nbsp;<i class='fa fa-arrow-right' style = 'font-size:7px'></i>";
								
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
					$style = 'style="border:solid gray 4px; width: 60px; height: 60px;"';
				}

				$datosHtml = "<table class='table' style='font-size:12px;' width='300px'>
								<tr>
									<td>
										<img src='".$imagenUser."' class='img-circle' style='width:100px; height:100px; border:solid ".$color." 4px;'>
									</td>
									<td width='50%'>
										<table class='table table-bordered' width='100%'>
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

	                if ( $tamanho == 5){
	                	echo '	<div estado="'.ordenPorEstado($key->estado).'" class="col-md-2 col-xs-12" style="text-align:center;padding-bottom:2px;padding:6px">';
	                }elseif ( $tamanho == 6){
	                	echo '	<div estado="'.ordenPorEstado($key->estado).'" class="col-md-2 col-xs-12" style="text-align:center;padding-bottom:2px;padding:6px">';
	                }elseif ( $tamanho == 9){
	                	echo '	<div estado="'.ordenPorEstado($key->estado).'" class="col-md-3 col-xs-12" style="text-align:center;padding-bottom:2px;padding:6px">';
	                }elseif( $tamanho == 0){
	                   	echo '	<div estado="'.ordenPorEstado($key->estado).'" class="col-md-1 col-xs-12" style="text-align:center;margin:0 auto;padding:6px">';

	                }elseif( $tamanho == 12){
	                   	echo '	<div estado="'.ordenPorEstado($key->estado).'" class="col-md-12 col-xs-12" style="text-align:center;padding-bottom:2px;padding:6px">';
	                }
			

				echo '
			              	<img src="'.$imagenUser.'"  data-toggle="popover" data-trigger="hover" alt="Dyalogo" data-content="'.$datosHtml.'"  '.$style.' alt="'.($key->nombreUsuario).'" title="'.($key->nombreUsuario).'" class="imagenuUsuarios img-circle" idUsuario="'.$key->idUsuario.'">
			              	<a class="users-list-name" style="color:'.$color.';font-size:9px;"  href="#">'.strtoupper(($key->nombreUsuario)).'</a>
			              	<span class="users-list-date"  style="color:'.$color_fondo_estado.';" >'.$canal.' &nbsp; <span style="color:'.$color.';font-size:10px;" id="relojJose_'.$key->idUsuario.'"></span></span>
							<input id="reloj_ChangeDate'.$key->idUsuario.'" value="'.$changeTime.'" hidden ></input>
			          	</div>';

            }
    		echo '</div>';


			// Aqui se dejan los conteos de agentes, para evitar hacer un request inecesario al singleton
			echo "<input hidden id='dis'value='".$dis."'></input>";
			echo "<input hidden id='pau'value='".$pau."'></input>";
			echo "<input hidden id='ocu'value='".$ocu."'></input>";
			echo "<input hidden id='nodis'value='".$nodis."'></input>";

        }

		


			

	}

	function getFile($id){
		$mime_type = mime_content_type("/Dyalogo/img_usuarios/usr".$id.".jpg");
   		header('Content-Type: '.$mime_type);
    	return readfile("/Dyalogo/img_usuarios/usr".$id.".jpg");
	}
	// }
?>
