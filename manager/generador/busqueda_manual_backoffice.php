<?php
	function generar_Busqueda_Manual_Backoffice($id_busqueda){

		global $mysqli;
		global $BaseDatos;
		global $BaseDatos_systema;
		global $BaseDatos_telefonia;
		global $dyalogo_canales_electronicos;
		global $BaseDatos_general;
		
		if(!is_null($id_busqueda)){
			/* si no viene vacio el id procedemos */
			$str_guion   = 'G'.$id_busqueda;
			$str_guion_c = $str_guion."_C";

			/* buscamos los campos que se deben colocar para el buscador los que tengan PREGUN_IndiBusc__b != 0 */
			
			$str_Lsql = "SELECT PREGUN_Texto_____b as titulo_pregunta , PREGUN_Tipo______b as tipo_Pregunta, PREGUN_ConsInte__b as id , PREGUN_ConsInte__GUION__PRE_B as guion, PREGUN_ConsInte__OPCION_B as lista, PREGUN_OrdePreg__b , PREGUN_NumeMini__b as minimoNumero, PREGUN_NumeMaxi__b as maximoNumero, PREGUN_FechMini__b as fechaMinimo, PREGUN_FechMaxi__b as fechaMaximo , PREGUN_HoraMini__b as horaMini , PREGUN_HoraMaxi__b as horaMaximo, PREGUN_TexErrVal_b as error FROM ".$BaseDatos_systema.".PREGUN WHERE PREGUN_ConsInte__GUION__b = ".$id_busqueda." AND PREGUN_IndiBusc__b != 0 ORDER BY PREGUN_OrdePreg__b";

			/* La carpeta donde van a quedar alojados estos archivos  */
			
			if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
			    $carpeta = "C:/xampp/htdocs/crm_php/formularios/".$str_guion;
			} else {
			    $carpeta = "/var/www/html/crm_php/formularios/".$str_guion;
			}
			
		    if (!file_exists($carpeta)) {
		        mkdir($carpeta, 0777);
		    }

		    /* abrimos el archivo que vamos a crear */
		    $fp = fopen($carpeta."/".$str_guion."_Busqueda_Manual.php" , "w");
		    //chmod($carpeta."/".$str_guion."_Busqueda_Manual.php" , 0777); 

		    /* iniciamos la codificacion de la pagina */
		    
		    $campo ='';
		    $campos = $mysqli->query($str_Lsql);
		    $i9 = 0;
		    $final = $campos->num_rows;
	      	while($obj = $campos->fetch_object()){
	       		switch ($obj->tipo_Pregunta) {
	       			case '1':
						$campo = ' 
				<!-- CAMPO TIPO TEXTO -->
					<div class="form-group">
					    <input type="text" class="form-control input-sm" id="busq_'.$str_guion_c.$obj->id.'" name="busq_'.$str_guion_c.$obj->id.'"  placeholder="'.($obj->titulo_pregunta).'">
					</div>
			
				<!-- FIN DEL CAMPO TIPO TEXTO -->';
                   
							fputs($fp , $campo);
							fputs($fp , chr(13).chr(10)); // Genera saldo de linea 
	       				break;

	       			case '2':
						$campo = ' 	
				<!-- CAMPO TIPO MEMO -->
					<div class="form-group">
					    <textarea class="form-control input-sm" name="busq_'.$str_guion_c.$obj->id.'" id="busq_'.$str_guion_c.$obj->id.'"   placeholder="'.($obj->titulo_pregunta).'"></textarea>
					</div>
			
				<!-- FIN DEL CAMPO TIPO MEMO -->';
		                    fputs($fp , $campo);
		  					fputs($fp , chr(13).chr(10)); // Genera saldo de linea 
	       				break;

	       			case '3':
						$campo = ' 
				<!-- CAMPO TIPO ENTERO -->
				<!-- Estos campos siempre deben llevar Numerico en la clase asi class="form-control input-sm Numerico" , de l contrario seria solo un campo de texto mas -->
					<div class="form-group">
					    <input type="text" class="form-control input-sm Numerico"  name="busq_'.$str_guion_c.$obj->id.'" id="busq_'.$str_guion_c.$obj->id.'" placeholder="'.($obj->titulo_pregunta).'">
					</div>
			
				<!-- FIN DEL CAMPO TIPO ENTERO -->';
	                     
		                    fputs($fp , $campo);
		  					fputs($fp , chr(13).chr(10)); // Genera saldo de linea 
	       				break;

	   				case '4':
						$campo = ' 	
				<!-- CAMPO TIPO DECIMAL -->
				<!-- Estos campos siempre deben llevar Decimal en la clase asi class="form-control input-sm Decimal" , de l contrario seria solo un campo de texto mas -->
					<div class="form-group">
					    <input type="text" class="form-control input-sm Decimal"  name="busq_'.$str_guion_c.$obj->id.'" id="busq_'.$str_guion_c.$obj->id.'" placeholder="'.($obj->titulo_pregunta).'">
					</div>
			
				<!-- FIN DEL CAMPO TIPO DECIMAL -->';
		                    
							fputs($fp , $campo);
							fputs($fp , chr(13).chr(10)); // Genera saldo de linea 
	       				break;

	       			case '5':
						$campo = ' 	
				<!-- CAMPO TIPO FECHA -->
				<!-- Estos campos siempre deben llevar Fecha en la clase asi class="form-control input-sm Fecha" , de l contrario seria solo un campo de texto mas -->
					<div class="form-group">
					    <input type="text" class="form-control input-sm Fecha"  name="busq_'.$str_guion_c.$obj->id.'" id="busq_'.$str_guion_c.$obj->id.'" placeholder="YYYY-MM-DD">
					</div>

				<!-- FIN DEL CAMPO TIPO FECHA-->';
		                   
		                    fputs($fp , $campo);
		  					fputs($fp , chr(13).chr(10)); // Genera saldo de linea 
	       				break;
	       			
	       			case '10':
						$campo = ' 	
				<!-- CAMPO TIMEPICKER -->
				<!-- Estos campos siempre deben llevar Hora en la clase asi class="form-control input-sm Hora" , de l contrario seria solo un campo de texto mas -->
					<div class="bootstrap-timepicker">
					    <div class="form-group">
			
					        <div class="input-group">
					            <input type="text" class="form-control input-sm Hora"  name="busq_'.$str_guion_c.$obj->id.'" id="busq_'.$str_guion_c.$obj->id.'" placeholder="HH:MM:SS" >
					            <div class="input-group-addon">
					                <i class="fa fa-clock-o"></i>
					            </div>
					        </div>
					        <!-- /.input group -->
					    </div>
					    <!-- /.form group -->
					</div>

				<!-- FIN DEL CAMPO TIMEPICKER -->';

		                    fputs($fp , $campo);
		  					fputs($fp , chr(13).chr(10)); // Genera saldo de linea 
	       				break;

	   				case '6':

						$campo = '
				<!-- CAMPO DE TIPO LISTA -->
					<div class="form-group">
					    <select class="form-control input-sm"  name="busq_'.$str_guion_c.$obj->id.'" id="busq_'.$str_guion_c.$obj->id.'">
					        <option value="0">Seleccione</option>
					        <?php
					            /*
					                SE RECORRE LA CONSULTA QUE SE CARGO CON ANTERIORIDAD EN LA SECCION DE CARGUE LISTAS DESPLEGABLES
					            */
					            $str_Lsql = "SELECT LISOPC_ConsInte__b AS OPCION_ConsInte__b, LISOPC_Nombre____b AS OPCION_Nombre____b FROM ".$BaseDatos_systema.".LISOPC WHERE LISOPC_ConsInte__OPCION_b = '.$obj->lista.'";

					            $obj = $mysqli->query($str_Lsql);
					            while($obje = $obj->fetch_object()){
					                echo "<option value=\'".$obje->OPCION_ConsInte__b."\'>".($obje->OPCION_Nombre____b)."</option>";

					            }    
					            
					        ?>
					    </select>
					</div>

				<!-- FIN DEL CAMPO TIPO LISTA -->';
	                    
	              			fputs($fp , $campo);
	    					fputs($fp , chr(13).chr(10)); // Genera saldo de linea 
	       				break;

	       			case '11':

		                //Primero necesitamos obener el campo que vamos a usar
		                $campoGuion = $obj->id;
		                $guionSelect2 = $obj->guion;
		                //Luego buscamos los campos en la tabla Pregui
		                $CampoSql = "SELECT * FROM ".$BaseDatos_systema.".PREGUI WHERE PREGUI_ConsInte__PREGUN_b = ".$campoGuion;
		                //recorremos esos campos para ir a buscarlos en la tabla campo_
		                $CampoSqlR = $mysqli->query($CampoSql);
		                $camposconsultaGuion = ' G'.$obj->guion.'_ConsInte__b as id ';


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
		                        }
		                    }

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
		                        }
		                        //añadimos los campos a la consulta que se necesita para cargar el guion
		                        $camposconsultaGuion .=', '.$objCampo->CAMPO__Nombre____b;
		                        if($valordelArray == 0){
		                            $camposAmostrar .= '".($obj->'.$objCampo->CAMPO__Nombre____b.')."';
		                        }else{
		                            $camposAmostrar .= ' | ".($obj->'.$objCampo->CAMPO__Nombre____b.')."';
		                        }
		                        
		                        

		                        $valordelArray++;
		                    }
		                }

						$campo = ' 
				<?php	
				$str_Lsql = "SELECT '.$camposconsultaGuion.' FROM ".$BaseDatos.".G'.$obj->guion.'";
				?>
				<!-- CAMPO DE TIPO LISTA -->
					<div class="form-group">
					    <select class="form-control input-sm"  name="busq_'.$str_guion_c.$obj->id.'" id="busq_'.$str_guion_c.$obj->id.'">
					        <option>'.$nombresDeCampos.'</option>
					        <?php
					            /*
					                SE RECORRE LA CONSULTA QUE TRAE LOS CAMPOS DEL GUIÓN
					            */
					            $combo = $mysqli->query($str_Lsql);
					            while($obj = $combo->fetch_object()){
					                echo "<option value=\'".$obj->id."\' dinammicos=\''.$camposAcolocarDinamicamente.'\'>'.$camposAmostrar.'</option>";

					            }    
					            
					        ?>
					    </select>
					</div>

				<!-- FIN DEL CAMPO TIPO LISTA -->';

	             
		                    fputs($fp , $campo);
		                    fputs($fp , chr(13).chr(10)); // Genera saldo de linea */
	       				break;

	       			case '8':
						$campo = ' 	
				<!-- CAMPO DE TIPO CHECKBOX O SI/NO -->
					<div class="form-group">
					    <div class="checkbox">
					        <label>
					            <input type="checkbox" value="1" name="busq_'.$str_guion_c.$obj->id.'" id="busq_'.$str_guion_c.$obj->id.'" data-error="Before you wreck yourself"  > 
					        </label>
					    </div>
					</div>

				<!-- FIN DEL CAMPO SI/NO -->';
	                   
							fputs($fp , $campo);
							fputs($fp , chr(13).chr(10)); // Genera saldo de linea 
	       				break;

	       			default:
	       				
	       				break;
	       		}
	       	}
			fclose($fp); 

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