<!DOCTYPE html>
<html>
	<head>
		<title>Preview Estación contact center</title>
		<meta charset="utf-8">
		<meta name="tipo_contenido"  content="text/html;" http-equiv="content-type" charset="utf-8">
		<!-- Tell the browser to be responsive to screen width -->
		<meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
		<META HTTP-EQUIV="Access-Control-Allow-Origin" CONTENT="*">
		<!-- Bootstrap 3.3.6 -->
		<link rel="stylesheet" href="assets/bootstrap/css/bootstrap.min.css">
		<!-- Font Awesome -->

		<!-- Date Picker -->
		<link rel="stylesheet" href="assets/plugins/datepicker/datepicker3.css">
		<link rel="stylesheet" href="assets/css/alertify.core.css">
        <link rel="stylesheet" href="assets/css/alertify.default.css">
        <link rel="stylesheet" href="assets/plugins/select2/select2.min.css" />
        <link rel="stylesheet" href="assets/plugins/sweetalert/sweetalert.css" />
        <link rel="stylesheet" type="text/css" media="screen" href="assets/Guriddo_jqGrid_/css/ui.jqgrid-bootstrap.css" />
		<script src="assets/plugins/jQuery/jquery-2.2.3.min.js"></script>
		<style type="text/css">
            [class^='select2'] {
                border-radius: 0px !important;
            }

            .modal-lg {
                width: 1200px;
            }
        </style>
    </head>
	<body>
		<div class="row">
			<div class="col-md-2"></div>
			<div class="col-md-8">
				<?php 
					echo "<h3 style='color: rgb(110, 197, 255);'>CAMPO PRINCIPAL</h3>";
				?>
				<div class="row">
					<div class="col-md-12 col-xs-12">
						<div class="box">
							<div class="box-body">
								<table class="table table-bordered table-hover">
									<thead>
										<tr>
				                            <th colspan="4">
				                                Historico de gestiones
				                            </th>
				                        </tr>
										<tr>
											<th>Gesti&oacute;n</th>
											<th>Comentarios</th>
											<th>Fecha - hora</th>
											<th>Agente</th>
										</tr>
									</thead>
									<tbody>
										
									</tbody>
								</table>
							</div>
						</div>
					</div>
				</div> 	
				<!-- aqui la parte de pintar los campos -->

<?php
	include ('pages/conexion.php');
	$id_a_generar = base64_decode($_GET['formulario']);
	$guion = 'G'.$id_a_generar;
	$alfabeto = array('a', 'b', 'c', 'd', 'e', 'f', 'g', 'h', 'i', 'j', 'k', 'l', 'm', 'n', 'o', 'p', 'q', 'r', 's', 't', 'u', 'v', 'w', 'x', 'y', 'z', 'ab', 'ac', 'ad', 'ae', 'af', 'ag', 'ah', 'ai', 'aj', 'ak', 'al', 'am', 'an', 'ao', 'ap', 'aq', 'ar', 'as', 'at', 'au', 'av', 'aw', 'ax', 'ay', 'az');

    $guion_c = $guion."_C";

	$LsqlCamposPrimairos = "SELECT GUION__ConsInte__PREGUN_Pri_b,  GUION__ConsInte__PREGUN_Sec_b, GUION__ConsInte__PREGUN_Tip_b, GUION__ConsInte__PREGUN_Rep_b, GUION__ConsInte__PREGUN_Fag_b, GUION__ConsInte__PREGUN_Hag_b, GUION__ConsInte__PREGUN_Com_b FROM ".$BaseDatos_systema.".GUION_ WHERE GUION__ConsInte__b = ".$id_a_generar ." AND GUION__Tipo______b = 1";



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
           echo '<div id="'.$seccionAqui->SECCIO_ConsInte__b.'" '.$stylo.'>';
        }else  if($seccionAqui->SECCIO_VistPest__b == 2){

            echo '<div id="'.$seccionAqui->SECCIO_ConsInte__b.'" '.$stylo.'>
    <h3 class="box box-title">'.($seccionAqui->SECCIO_Nombre____b).'</h3>';

        }else  if($seccionAqui->SECCIO_VistPest__b == 3){
            $colapse = '';
            if($seccionAqui->SECCIO_PestMini__b != 0){
                $colapse = 'in';
            }

            echo '
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

        }else{

            echo '
<div class="panel box box-primary" '.$stylo.'>
    <div class="box-header with-border">
        <h4 class="box-title">
            '.($seccionAqui->SECCIO_Nombre____b).'
        </h4>
    </div>
    <div class="box-body">'; 

        }
					

		$str_LsqlXD = "SELECT PREGUN_Texto_____b as titulo_pregunta , PREGUN_Tipo______b as tipo_Pregunta, PREGUN_ConsInte__b as id , PREGUN_ConsInte__GUION__PRE_B as guion, PREGUN_ConsInte__OPCION_B as lista, PREGUN_OrdePreg__b , PREGUN_NumeMini__b as minimoNumero, PREGUN_NumeMaxi__b as maximoNumero, PREGUN_FechMini__b as fechaMinimo, PREGUN_FechMaxi__b as fechaMaximo , PREGUN_HoraMini__b as horaMini , PREGUN_HoraMaxi__b as horaMaximo, PREGUN_TexErrVal_b as error , PREGUN_ConsInte__SECCIO_b, PREGUN_Default___b, PREGUN_ContAcce__b, PREGUN_PermiteAdicion_b FROM ".$BaseDatos_systema.".PREGUN WHERE PREGUN_ConsInte__GUION__b = ".$id_a_generar." AND PREGUN_ConsInte__SECCIO_b = ".$id_seccion." AND PREGUN_FueGener_b != 3 ORDER BY PREGUN_OrdePreg__b  ASC";

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
					echo '<div class="row">';
                }
            	echo '<div class="col-md-'.$checkColumnas.' col-xs-'.$checkColumnas.'">';
            	$valorPordefecto = $obj->PREGUN_Default___b;
        		$valoraMostrar = "";
        		$disableds = 'disabled';
        		switch ($valorPordefecto) {
                    case '501':
                        $valoraMostrar = date('Y-m-d');
                        break;

                    case '1001':
                        $valoraMostrar = date('H:i:s');
                        break;

                    case '102':
                        $valoraMostrar = 'Nombre del usuario';
                        break;
                    
                    case '105':
                        $valoraMostrar = 'Nombre de la campa&ntilde;a';
                        break;

                    default:
                        $valoraMostrar = null;
                        break;
                }
                if($obj->titulo_pregunta != 'ORIGEN_DY_WF' && $obj->titulo_pregunta != 'OPTIN_DY_WF'){
	                switch ($obj->tipo_Pregunta) {
	                	case '1':
							echo '<div class="form-group">
				            <label for="'.$guion_c.$obj->id.'" id="Lbl'.$guion_c.$obj->id.'">'.($obj->titulo_pregunta).'</label>
				            <input type="text" class="form-control input-sm" id="'.$guion_c.$obj->id.'" value="'.$valoraMostrar.'" '.$disableds.' name="'.$guion_c.$obj->id.'"  placeholder="'.($obj->titulo_pregunta).'">
				        </div>';
				        break;

				        case '2':
							echo '<div class="form-group">
				            <label for="'.$guion_c.$obj->id.'" id="Lbl'.$guion_c.$obj->id.'">'.($obj->titulo_pregunta).'</label>
				            <textarea class="form-control input-sm" name="'.$guion_c.$obj->id.'" id="'.$guion_c.$obj->id.'" '.$disableds.' value="'.$valoraMostrar.'" placeholder="'.($obj->titulo_pregunta).'"></textarea>
				        </div>';
				        break;

				        case '3':
							echo '<div class="form-group">
				            <label for="'.$guion_c.$obj->id.'" id="Lbl'.$guion_c.$obj->id.'">'.($obj->titulo_pregunta).'</label>
				            <input type="text" class="form-control input-sm Numerico" value="'.$valoraMostrar.'" '.$disableds.' name="'.$guion_c.$obj->id.'" id="'.$guion_c.$obj->id.'" placeholder="'.($obj->titulo_pregunta).'">
				        </div>';
	                    break;

	                    case '4':
							echo '<div class="form-group">
				            <label for="'.$guion_c.$obj->id.'" id="Lbl'.$guion_c.$obj->id.'">'.($obj->titulo_pregunta).'</label>
				            <input type="text" class="form-control input-sm Decimal" value="'.$valoraMostrar.'" '.$disableds.' name="'.$guion_c.$obj->id.'" id="'.$guion_c.$obj->id.'" placeholder="'.($obj->titulo_pregunta).'">
				        </div>';
	                    break;

	                    case '5':
							echo '<div class="form-group">
				            <label for="'.$guion_c.$obj->id.'" id="Lbl'.$guion_c.$obj->id.'">'.($obj->titulo_pregunta).'</label>
				            <input type="text" class="form-control input-sm Decimal" value="'.$valoraMostrar.'" '.$disableds.' name="'.$guion_c.$obj->id.'" id="'.$guion_c.$obj->id.'" placeholder="'.($obj->titulo_pregunta).'">
				        </div>';
	                    break;

	                    case '6':
							echo '<div class="form-group">
				            <label for="'.$guion_c.$obj->id.'" id="Lbl'.$guion_c.$obj->id.'">'.($obj->titulo_pregunta).'</label>
				            <select class="form-control input-sm" '.$disableds.' name="'.$guion_c.$obj->id.'" id="'.$guion_c.$obj->id.'" placeholder="'.($obj->titulo_pregunta).'">
				            	<option>Seleccione</option>
				            </select>
				        </div>';
	                    break;

						case '13':
							echo '<div class="form-group">
				            <label for="'.$guion_c.$obj->id.'" id="Lbl'.$guion_c.$obj->id.'">'.($obj->titulo_pregunta).'</label>
				            <select class="form-control input-sm" '.$disableds.' name="'.$guion_c.$obj->id.'" id="'.$guion_c.$obj->id.'" placeholder="'.($obj->titulo_pregunta).'">
				            	<option>Seleccione</option>
				            </select>
				        </div>';
				        	echo '<div class="form-group">
				            <label for="respuesta_'.$guion_c.$obj->id.'" id="Lbl'.$guion_c.$obj->id.'">'.($obj->titulo_pregunta).'</label>
				            <textarea class="form-control input-sm" '.$disableds.' id="respuesta_'.$guion_c.$obj->id.'" placeholder="Respuesta"></textarea>
				        </div>';
	                    break;


	                    case '11':
							echo '<div class="form-group">
				            <label for="'.$guion_c.$obj->id.'" id="Lbl'.$guion_c.$obj->id.'">'.($obj->titulo_pregunta).'</label>
				            <select class="form-control input-sm" '.$disableds.' name="'.$guion_c.$obj->id.'" id="'.$guion_c.$obj->id.'" placeholder="'.($obj->titulo_pregunta).'">
				            	<option>Seleccione</option>
				            </select>
				        </div>';
	                    break;

	                    case '8':
							echo '<div class="form-group">
				            <label for="'.$guion_c.$obj->id.'" id="Lbl'.$guion_c.$obj->id.'">
				            	<input type="checkbox" >'.($obj->titulo_pregunta).'
				            </label>
				        </div>';
	                    break;

	                    case '10':
							echo '<div class="bootstrap-timepicker">
				            <div class="form-group">
				                <label for="'.$guion_c.$obj->id.'" id="Lbl'.$guion_c.$obj->id.'">'.($obj->titulo_pregunta).'</label>
				                <div class="input-group">
				                    <input type="text" class="form-control input-sm Hora" '.$disableds.' name="'.$guion_c.$obj->id.'" id="'.$guion_c.$obj->id.'" placeholder="HH:MM:SS" >
				                    <div class="input-group-addon" id="TMP_'.$guion_c.$obj->id.'">
				                        <i class="fa fa-clock-o"></i>
				                    </div>
				                </div>
				            </div>
				        </div>';
	                    break;
	            	
						case '9':
							echo '<p style="text-align:justify;">'.($obj->titulo_pregunta).'</p>';
	                    break;

	                    default:
	              
	                    break;
	                }//Cierro el Swich
                }


				echo '</div>';    
                $filaActual += 1;
                if($filaActual >= $maxColumns){
                    $filaActual = 0;
                    echo '</div>';    
                }
            }//cierro el IF


        }//Cierro el Wile de secciones

        if($filaActual > 0){
            if($filaActual < $maxColumns){
                if($maxColumns % $filaActual != 0){
                    $filaActual = 0;
                    echo '</div>';
                }

                if($filaActual == 1){
                    $filaActual = 0;
                    echo '</div>';
                }
            }
        }
        


        //aqui cerramos las secciones y obtenemos un solo codigo
        if($seccionAqui->SECCIO_VistPest__b == 1){

            echo '</div>';
                
        }else  if($seccionAqui->SECCIO_VistPest__b == 2){

            echo '</div>';
               
        }else  if($seccionAqui->SECCIO_VistPest__b == 3){

            echo '</div>';
           	echo '</div>';
           	echo '</div>';
				    
        }else{
			echo '</div>';
           	echo '</div>';

        }
    }
?>
<!--Validamos el maestro detalle de este formulario -->
<?php
	$LsqlMaestro = "SELECT * FROM ".$BaseDatos_systema.".GUIDET WHERE GUIDET_ConsInte__GUION__Mae_b = ".$id_a_generar;
	$EsONoEs = 0;
	$Kaka = 0;
	$EjecutarMaetsro = $mysqli->query($LsqlMaestro);
	if($EjecutarMaetsro->num_rows > 0){
		$tabsDeMaestro = '<hr/>
		<div class="nav-tabs-custom">';
		$contenidoMaestro = "\n".'
		<ul class="nav nav-tabs">';
		$tabscontenidoMaestro = "\n".'
		<div class="tab-content">';
		while ($key = $EjecutarMaetsro->fetch_object()) {
			$activo = '';
			if($Kaka == 0){
			   $activo = 'active';
			}

			$contenidoMaestro .=  "\n".'
			<li class="'.$activo.'">
				<a href="#tab_'.$Kaka.'" data-toggle="tab" id="tabs_click_'.$Kaka.'">'.$key->GUIDET_Nombre____b.'</a>
			</li>';

			$tabscontenidoMaestro .= "\n".'
			<div class="tab-pane '.$activo.'" id="tab_'.$Kaka.'"> 
				<div class="row">
					<div class="col-md-12">
						<table class="table table-hover table-bordered" id="tablaDatosDetalless'.$Kaka.'" width="100%">
							<thead>
								<tr>';


			$LsqlDetalle = "SELECT PREGUN_Texto_____b as titulo_pregunta , PREGUN_ConsInte__b as id FROM ".$BaseDatos_systema.".PREGUN LEFT JOIN  ".$BaseDatos_systema.".SECCIO ON SECCIO_ConsInte__b = PREGUN_ConsInte__SECCIO_b  WHERE PREGUN_ConsInte__GUION__b = ".$key->GUIDET_ConsInte__GUION__Det_b." AND PREGUN_Tipo______b != 9 AND PREGUN_Tipo______b != 12  ORDER BY SECCIO_Orden_____b ASC, PREGUN_OrdePreg__b ASC";
					
			$campos = $mysqli->query($LsqlDetalle);
			$i = 0;
			$titulos ='';
			$orden = '';

		    // echo $LsqlDetalle;
			while ($key3 = $campos->fetch_object()){
				$tabscontenidoMaestro .= '<th>'.($key3->titulo_pregunta).'</th>';
				$i++;
			}


			$tabscontenidoMaestro .= "\n".'	
								</tr>
							</thead>
							<tbody>';

			for($k = 0; $k < 10 ; $k++){
				$tabscontenidoMaestro .= '<tr>';
				for ($j=0; $j < $i; $j++) { 
					$tabscontenidoMaestro .= '<td>Lorem ipsum dolor sit amet</td>';
				}	
				$tabscontenidoMaestro .= '</th>';
			}
			

			$tabscontenidoMaestro .= "\n".'
							</tbody>
						</table>
					</div>
				</div>
				<div id="pagerDetalles'.$Kaka.'">
				</div> 
			</div>';

			$Kaka++;
		}

		$tabscontenidoMaestro .= "\n".'
    </div>';
        	$contenidoMaestro .= "\n".'
    </ul>';
        	$tabsDeMaestro .= $contenidoMaestro ."\n". $tabscontenidoMaestro;
        	$tabsDeMaestro .= "\n".'
</div>';

		echo $tabsDeMaestro;

	}
	

?>

<?php if(!is_null($GUION__ConsInte__PREGUN_Tip_b) && !empty($GUION__ConsInte__PREGUN_Tip_b)){ ?>
				<!-- Esto es tipificaciones -->
				<div class="row" style="background-color: #FAFAFA; ">
					<div class="col-md-12 col-xs-12">
						<div class="form-group">
							<select class="form-control input-sm tipificacion" disabled name="tipificacion" id="G729_C9612">
								<option value="0">Tipificaci&oacute;n</option>
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
					<div class="col-md-4 col-xs-4">
						<div class="form-group">
							<select class="form-control input-sm reintento" disabled name="reintento" id="G729_C9606">
								<option value="0">Reintento</option>
							</select>     
						</div>
					</div>
					<div class="col-md-4 col-xs-4">
						<div class="form-group">
							<input type="text" name="TxtFechaReintento" id="G729_C9613" disabled class="form-control input-sm TxtFechaReintento" placeholder="Fecha Reintento"  >
						</div>
					</div>
					<div class="col-md-4 col-xs-4" style="text-align: left;">
						<div class="form-group">
							<input type="text" name="TxtHoraReintento" id="G729_C9614" disabled class="form-control input-sm TxtHoraReintento" placeholder="Hora Reintento">
						</div>
					</div>
					<div class="col-md-12 col-xs-12">
						<div class="form-group">
							<textarea class="form-control input-sm textAreaComentarios" disabled name="textAreaComentarios" id="G729_C9611" placeholder="Observaciones"></textarea>
						</div>
					</div>
				</div>
<?php   } ?>
			</div>
		</div>
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
        <script src="assets/plugins/timepicker/bootstrap-timepicker.min.js"></script>
        <script src="assets/plugins/sweetalert/sweetalert.min.js"></script>      

        <script src="assets/Guriddo_jqGrid_/js/i18n/grid.locale-es.js" type="text/javascript"></script>
        <script src="assets/Guriddo_jqGrid_/js/jquery.jqGrid.min.js" type="text/javascript"></script>

        <!-- AdminLTE App -->
        <script src="assets/js/numeric.js"></script>
        <script src="assets/js/alertify.js"></script>

	</body>
</html>
