<?php
    function getSubForms(int $idBd):array
    {
        global $mysqli;
        global $BaseDatos_systema;
        (Array) $arrSubForms=array();

        $sqlSubforms=$mysqli->query("SELECT GUIDET_ConsInte__GUION__Det_b,GUIDET_Nombre____b,GUIDET_ConsInte__PREGUN_Ma1_b,GUIDET_ConsInte__PREGUN_De1_b FROM {$BaseDatos_systema}.GUIDET LEFT JOIN {$BaseDatos_systema}.GUION_ ON GUIDET_ConsInte__GUION__Det_b=GUION__ConsInte__b WHERE GUIDET_ConsInte__GUION__Mae_b ={$idBd} AND GUION__Tipo______b=2 GROUP BY GUIDET_ConsInte__GUION__Det_b");

        if($sqlSubforms && $sqlSubforms->num_rows > 0){
            while($row = $sqlSubforms->fetch_object()){
                array_push($arrSubForms,array("guion"=>$row->GUIDET_ConsInte__GUION__Det_b,"nombre"=>"Campos del subformulario ".$row->GUIDET_Nombre____b,"campoBD"=>$row->GUIDET_ConsInte__PREGUN_Ma1_b,"campoSub"=>$row->GUIDET_ConsInte__PREGUN_De1_b));
            }
        }

        return $arrSubForms;
    }

	function generar_Busqueda_Manual($id_busqueda){

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

			$strLsqlCaminc = "SELECT PREGUN_OrdePreg__b, PREGUN_ConsInte__b as id, C.CAMINC_ConsInte__GUION__Gui_b as script, C.CAMINC_ConsInte__CAMPO_Gui_b as colScript FROM ".$BaseDatos_systema.".PREGUN JOIN DYALOGOCRM_SISTEMA.CAMINC as C ON C.CAMINC_ConsInte__CAMPO_Pob_b = PREGUN_ConsInte__b WHERE PREGUN_ConsInte__GUION__b = ".$id_busqueda." AND PREGUN_IndiBusc__b != 0 GROUP BY CAMINC_ConsInte__CAMPO_Gui_b ORDER BY PREGUN_OrdePreg__b;";
            
            /*NBG*2020-05* Buscamos el tipo de busqueda a generar*/
            $intTipoBusqueda_t=1;
            $strSQLTipoBusqueda="SELECT GUION__TipoBusqu__Manual_b,GUION_PERMITEINSERTAR_b,GUION_INSERTAUTO_b FROM {$BaseDatos_systema}.GUION_ WHERE GUION__ConsInte__b={$id_busqueda}";
            $strSQLTipoBusqueda=$mysqli->query($strSQLTipoBusqueda);
            if($strSQLTipoBusqueda->num_rows>0){
                $strSQLTipoBusqueda=$strSQLTipoBusqueda->fetch_object();
                $intTipoBusqueda_t=$strSQLTipoBusqueda->GUION__TipoBusqu__Manual_b;
                $intPermiteInsertar=$strSQLTipoBusqueda->GUION_PERMITEINSERTAR_b;
                $intInsertAuto=$strSQLTipoBusqueda->GUION_INSERTAUTO_b;
            }            
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
		    
		    $campo ='
<?php
	$http = "http://".$_SERVER["HTTP_HOST"];
	if (isset($_SERVER[\'HTTPS\'])) {
	    $http = "https://".$_SERVER["HTTP_HOST"];
	}
	if(isset($_GET["accion_manual"])){
		$idMonoef=-19;
		$texto = "Busqueda No Aplica";
	}else{
		$idMonoef =-18;
		$texto = "No Suministra Información";
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

<link rel="stylesheet" href="assets/plugins/WinPicker/dist/wickedpicker.min.css">
<script type="text/javascript" src="assets/plugins/WinPicker/dist/wickedpicker.min.js"></script>
<style>
.sweet-alert{
    position:absolute !important;
    margin-top: 0px !important;
    top:100px;
}
</style>

<div class="row" id="buscador">
	<div class="col-md-1">&nbsp;</div>
	<div class="col-md-10">
		<div class="row">
			<form id="formId">';

			$str_tablaCampos = '';
			$str_tablaCuerpo = '';
			$where = '';
            $strCamposSelect='';
            $arrGuiones=[];
			$arrCamposBusMan_t = [];
            $strTimepicker='';
            $strDatepicker='';
            $strValidaCorreo_t='';
			$strValidaDecimal="";
			$strValidaEntero="";
            $strRegistroAuto="";
            $mensajeRegistro=$intPermiteInsertar == -1 ? ", desea adicionar un registro" : "";
            $showConfirmButton=$intPermiteInsertar == -1 ? "true" : "false";
			/* escribimos la variable que creamos arriba campo */

            if($intInsertAuto == -1){
                $strRegistroAuto='
                    <?php if(isset($_GET["datonuevo"])){ ?>
                        nuevoRegistro();
                    <?php } ?>
                ';
            }
			
		    fputs($fp , $campo);
			fputs($fp , chr(13).chr(10)); // Genera saldo de linea 

			$funcionesCampoGuion='';
			$select2='';
			$joins='';
			$camposJoins='';
            $relacionesCaminc = $mysqli->query($strLsqlCaminc);
		    while ($objC = $relacionesCaminc->fetch_object()) {
                //JDBD - Aqui llenamos el array con  id de los campos elegidos para la busqueda manual.
                $arrCamposBusMan_t[]=[
                    "script" => $objC->script,
                    "colScript" => $objC->colScript,
                    "camp" => $str_guion_c.$objC->id
                ];
		    }
            

            //BUSCAR LOS CAMPOS DE LOS SUBFORMULARIOS ASOCIADOS A ESTA BD
			(array) $subForms=getSubForms($id_busqueda);


			/* buscamos los campos que se deben colocar para el buscador los que tengan PREGUN_IndiBusc__b != 0 */

            for($i=-1; $i<count($subForms); $i++){
                $divisor="<div class='col-xs-12 col-md-12 col-lg-12'>";
                if($i<0){
                    $guion=$id_busqueda;
                    $divisor.="<h4>Campos de la base de datos</h4>";
                }else{
                    $guion=$subForms[$i]['guion'];
                    $divisor.="<br>";
                    $divisor.="<h4>{$subForms[$i]['nombre']}</h4>";
                    array_push($arrGuiones,$guion);
                }
                $divisor.="<hr style='margin-top:0px !important; border-top:3px solid #eee !important;'>";
                $divisor.="</div>";
                
                
                fputs($fp , $divisor);
                fputs($fp , chr(13).chr(10)); // Genera saldo de linea 
                
                $str_guion_c="G{$guion}_C";
                $str_Lsql = "SELECT PREGUN_Texto_____b as titulo_pregunta , PREGUN_Tipo______b as tipo_Pregunta, PREGUN_ConsInte__b as id , PREGUN_ConsInte__GUION__PRE_B as guion, PREGUN_ConsInte__OPCION_B as lista, PREGUN_OrdePreg__b , PREGUN_NumeMini__b as minimoNumero, PREGUN_NumeMaxi__b as maximoNumero, PREGUN_FechMini__b as fechaMinimo, PREGUN_FechMaxi__b as fechaMaximo , PREGUN_HoraMini__b as horaMini , PREGUN_HoraMaxi__b as horaMaximo, PREGUN_TexErrVal_b as error FROM ".$BaseDatos_systema.".PREGUN WHERE PREGUN_ConsInte__GUION__b = ".$guion." AND PREGUN_IndiBusc__b != 0 ORDER BY PREGUN_OrdePreg__b";
                $campos = $mysqli->query($str_Lsql);
                $i9 = 0;
                $final = $campos->num_rows;
                
	      	while($obj = $campos->fetch_object()){
	      		/* esta parte es la tabla que se muestra cuando hay mas de un dato */
	      		
	      		$str_tablaCampos .= '<th>'.($obj->titulo_pregunta).'</th>';
	      		$str_tablaCuerpo .= '<td>\'+ item.'.$str_guion_c.$obj->id.' +\'</td>';

	      		/* aqui ya entramos a la busqueda en el Sql de PHP */
                $where .= '
                if(isset($_POST[\''.$str_guion_c.$obj->id.'\']) && !is_null($_POST[\''.$str_guion_c.$obj->id.'\']) && $_POST[\''.$str_guion_c.$obj->id.'\'] != \'\' && $_POST[\''.$str_guion_c.$obj->id.'\'] != \'0\'){
                    $and = " AND ";
                    if($usados == 1){
                        $and = " AND ";
                    }
                    $Where .= $and." '.$str_guion_c.$obj->id.' LIKE \'%". trim($_POST[\''.$str_guion_c.$obj->id.'\']) ."%\' ";
                    $usados = 1;
                }';
                


                
                switch ($obj->tipo_Pregunta) {
                    case '1':
						$campo = ' 
				<!-- CAMPO TIPO TEXTO -->
				<div class="col-xs-4">
                <div class="form-group">
                <label for="'.$str_guion_c.$obj->id.'" id="Lbl'.$str_guion_c.$obj->id.'">'.($obj->titulo_pregunta).'</label>
					    <input type="text" class="form-control input-sm" id="'.$str_guion_c.$obj->id.'" name="'.$str_guion_c.$obj->id.'"  placeholder="'.($obj->titulo_pregunta).'">
                        </div>
                        </div>
                        <!-- FIN DEL CAMPO TIPO TEXTO -->';
                        
                        fputs($fp , $campo);
                        fputs($fp , chr(13).chr(10)); // Genera saldo de linea 
                        $strCamposSelect.=",{$str_guion_c}{$obj->id}";
	       				break;

	       			case '2':
						$campo = ' 	
                        <!-- CAMPO TIPO MEMO -->
				<div class="col-xs-4">
                <div class="form-group">
                <label for="'.$str_guion_c.$obj->id.'" id="Lbl'.$str_guion_c.$obj->id.'">'.($obj->titulo_pregunta).'</label>
                <textarea class="form-control input-sm" name="'.$str_guion_c.$obj->id.'" id="'.$str_guion_c.$obj->id.'"   placeholder="'.($obj->titulo_pregunta).'"></textarea>
                </div>
				</div>
				<!-- FIN DEL CAMPO TIPO MEMO -->';
                fputs($fp , $campo);
                fputs($fp , chr(13).chr(10)); // Genera saldo de linea 
                $strCamposSelect.=",{$str_guion_c}{$obj->id}";
                break;
                
                case '3':
                    $campo = ' 
                    <!-- CAMPO TIPO ENTERO -->
                    <!-- Estos campos siempre deben llevar Numerico en la clase asi class="form-control input-sm Numerico" , de l contrario seria solo un campo de texto mas -->
                    <div class="col-xs-4">
					<div class="form-group">
                    <label for="'.$str_guion_c.$obj->id.'" id="Lbl'.$str_guion_c.$obj->id.'">'.($obj->titulo_pregunta).'</label>
                    <input type="text" class="form-control input-sm Numerico"  name="'.$str_guion_c.$obj->id.'" id="'.$str_guion_c.$obj->id.'" placeholder="'.($obj->titulo_pregunta).'">
					</div>
                    </div>
                    <!-- FIN DEL CAMPO TIPO ENTERO -->';
                    $strValidaEntero.='$("#'.$str_guion_c.$obj->id.'").numeric();';
                    fputs($fp , $campo);
                    fputs($fp , chr(13).chr(10)); // Genera saldo de linea 
                    $strCamposSelect.=",{$str_guion_c}{$obj->id}";
                    break;
                    
                    case '4':
						$campo = ' 	
				<!-- CAMPO TIPO DECIMAL -->
				<!-- Estos campos siempre deben llevar Decimal en la clase asi class="form-control input-sm Decimal" , de l contrario seria solo un campo de texto mas -->
				<div class="col-xs-4">
					<div class="form-group">
					    <label for="'.$str_guion_c.$obj->id.'" id="Lbl'.$str_guion_c.$obj->id.'">'.($obj->titulo_pregunta).'</label>
					    <input type="text" class="form-control input-sm Decimal"  name="'.$str_guion_c.$obj->id.'" id="'.$str_guion_c.$obj->id.'" placeholder="'.($obj->titulo_pregunta).'">
					</div>
				</div>
				<!-- FIN DEL CAMPO TIPO DECIMAL -->';
				$strValidaDecimal .='$("#'.$str_guion_c.$obj->id.'").numeric({
                    decimal : ".",  negative : false, scale: 4
				});';
                fputs($fp , $campo);
                fputs($fp , chr(13).chr(10)); // Genera saldo de linea 
                $strCamposSelect.=",{$str_guion_c}{$obj->id}";
                break;
                
	       			case '5':
						$campo = ' 	
                        <!-- CAMPO TIPO FECHA -->
                        <!-- Estos campos siempre deben llevar Fecha en la clase asi class="form-control input-sm Fecha" , de l contrario seria solo un campo de texto mas -->
				<div class="col-xs-4">
                <div class="form-group">
					    <label for="'.$str_guion_c.$obj->id.'" id="Lbl'.$str_guion_c.$obj->id.'">'.($obj->titulo_pregunta).'</label>
					    <input type="text" class="form-control input-sm Fecha"  name="'.$str_guion_c.$obj->id.'" id="'.$str_guion_c.$obj->id.'" placeholder="YYYY-MM-DD">
                        </div>
                        </div>
				<!-- FIN DEL CAMPO TIPO FECHA-->';
                $strCamposSelect.=",DATE({$str_guion_c}{$obj->id}) AS {$str_guion_c}{$obj->id}";
                
                //Armamos los Datepicker        
                $strDatepicker .='
                $("#'.$str_guion_c.$obj->id.'").datepicker({
                    language: "es",
                    autoclose: true,
                    todayHighlight: true,
                    format:"yyyy-mm-dd"
                });
                ';                        
		                   
		                    fputs($fp , $campo);
		  					fputs($fp , chr(13).chr(10)); // Genera saldo de linea 
	       				break;
                           
	       			case '10':
						$campo = ' 	
                        <!-- CAMPO TIMEPICKER -->
                        <!-- Estos campos siempre deben llevar Hora en la clase asi class="form-control input-sm Hora" , de l contrario seria solo un campo de texto mas -->
				<div class="col-xs-4">
                <div class="bootstrap-timepicker">
					    <div class="form-group">
					        <label for="'.$str_guion_c.$obj->id.'" id="Lbl'.$str_guion_c.$obj->id.'">'.($obj->titulo_pregunta).'</label>
					        <div class="input-group">
					            <input type="text" class="form-control input-sm Hora"  name="'.$str_guion_c.$obj->id.'" id="'.$str_guion_c.$obj->id.'" placeholder="HH:MM:SS" >
					            <div class="input-group-addon">
					                <i class="fa fa-clock-o"></i>
					            </div>
					        </div>
					        <!-- /.input group -->
					    </div>
					    <!-- /.form group -->
                        </div>
				</div>
				<!-- FIN DEL CAMPO TIMEPICKER -->';
                
                //Armamos los Timepicker
                        
                $strTimepicker .='
                var options = { //hh:mm 24 hour format only, defaults to current time
                    twentyFour: true, //Display 24 hour format, defaults to false
                    title: \''.$obj->titulo_pregunta.'\', //The Wickedpicker\'s title,
                    showSeconds: true, //Whether or not to show seconds,
                    secondsInterval: 1, //Change interval for seconds, defaults to 1
                    minutesInterval: 1, //Change interval for minutes, defaults to 1
                    beforeShow: null, //A function to be called before the Wickedpicker is shown
                    show: null, //A function to be called when the Wickedpicker is shown
                    clearable: false, //Make the picker\'s input clearable (has clickable "x")
                }; 
                $("#'.$str_guion_c.$obj->id.'").wickedpicker(options);
                $("#'.$str_guion_c.$obj->id.'").val("");
                ';                        
                
                fputs($fp , $campo);
                fputs($fp , chr(13).chr(10)); // Genera saldo de linea 
                $strCamposSelect.=",TIME({$str_guion_c}{$obj->id}) AS {$str_guion_c}{$obj->id}";
                break;
                
	   				case '6':

						$campo = '
				<!-- CAMPO DE TIPO LISTA -->
				<div class="col-xs-4">
					<div class="form-group">
					    <label for="'.$str_guion_c.$obj->id.'" id="Lbl'.$str_guion_c.$obj->id.'">'.($obj->titulo_pregunta).'</label>
					    <select class="form-control input-sm"  name="'.$str_guion_c.$obj->id.'" id="'.$str_guion_c.$obj->id.'">
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
				</div>
				<!-- FIN DEL CAMPO TIPO LISTA -->';
	                    
	              			fputs($fp , $campo);
                              fputs($fp , chr(13).chr(10)); // Genera saldo de linea 
                        $strCamposSelect.=",dyalogo_general.fn_item_lisopc({$str_guion_c}{$obj->id}) AS {$str_guion_c}{$obj->id}";
                        break;
                        
	       			case '11':
                        $campoGuion = $obj->id; //JDBD-20-05-11: Este es el id del campo actual.
                        $guionSelect2 = $obj->guion; //JDBD-20-05-11: Este es el id de la base de donde proviene la lista compleja.
                        $campo = '
				<!-- JDBD-20-05-11: CAMPO DE TIPO LISTA COMPLEJA -->
				<div class="col-xs-4">
					<div class="form-group">
						<label for="'.$str_guion_c.$obj->id.'" id="Lbl'.$str_guion_c.$obj->id.'">'.($obj->titulo_pregunta).'</label>
						<select class="form-control input-sm select2" style="width: 100%;"  name="'.$str_guion_c.$obj->id.'" id="'.$str_guion_c.$obj->id.'">
                        <option value="0">Seleccione</option>
						</select>
					</div>
				</div>
				<!-- JDBD-20-05-11: FIN DEL CAMPO TIPO LISTA COMPLEJA -->';
                
                        $strSQLPregui_t = "SELECT A.PREGUI_ConsInte__b AS id, B.CAMPO__Nombre____b, C.CAMPO__Nombre____b AS campo_2, A.PREGUI_Consinte__CAMPO__GUI_B FROM ".$BaseDatos_systema.".PREGUI A JOIN ".$BaseDatos_systema.".CAMPO_ B ON A.PREGUI_ConsInte__CAMPO__b = B.CAMPO__ConsInte__b JOIN ".$BaseDatos_systema.".CAMPO_ C ON A.PREGUI_Consinte__CAMPO__GUI_B = C.CAMPO__ConsInte__b WHERE A.PREGUI_ConsInte__PREGUN_b = ".$campoGuion." AND A.PREGUI_Consinte__CAMPO__GUI_B != 0";
                        $resSQLPregui_t = $mysqli->query($strSQLPregui_t);
                        $strLlenadoDinamico_t = '';

                        if ($resSQLPregui_t->num_rows > 0) {
                            while ($row = $resSQLPregui_t->fetch_object()) {
                                $strLlenadoDinamico_t .= '
                                $("#'.$row->campo_2.'").val(data.'.$row->CAMPO__Nombre____b.');';
                            }
                        }

                        //JDBD-20-05-11: Vamos a traer las opciones del campo de la base de datos elegida para esta lista compleja.
                        $strSQLPregui_t = "SELECT MIN(A.PREGUI_ConsInte__b) AS id, B.CAMPO__Nombre____b, C.PREGUN_ConsInte__GUION__b AS base FROM ".$BaseDatos_systema.".PREGUI A JOIN ".$BaseDatos_systema.".CAMPO_ B ON A.PREGUI_ConsInte__CAMPO__b = B.CAMPO__ConsInte__b JOIN ".$BaseDatos_systema.".PREGUN C ON B.CAMPO__ConsInte__PREGUN_b = C.PREGUN_ConsInte__b WHERE A.PREGUI_ConsInte__PREGUN_b = ".$campoGuion." AND A.PREGUI_Consinte__CAMPO__GUI_B = 0";
                        $resSQLPregui_t = $mysqli->query($strSQLPregui_t);
                        
                        if ($resSQLPregui_t->num_rows > 0) {
                            
                            $objSQLPregui_t = $resSQLPregui_t->fetch_object();

                            $select2 .= '
                            $("#'.$str_guion_c.$obj->id.'").select2({
                                placeholder: "Buscar",
                                allowClear: false,
                                minimumInputLength: 3,
                                ajax:{
                                    url: \'formularios/'.$str_guion.'/'.$str_guion.'_Funciones_Busqueda_Manual.php?CallDatosCombo_Guion_'.$str_guion_c.$obj->id.'=si\',
                                    dataType: \'json\',
                                    type : \'post\',
                                    delay: 250,
                                    data: function (params) {
                                        return {
                                            q: params.term
                                        };
                                    },
                                    processResults: function(data) {
                                        try{
                                            try{
                                                after_select_'.$str_guion_c.$obj->id.'(data,document.getElementsByClassName(\'select2-search__field\')[0].value);
                                            }catch{
                                                console.log(\'error\');
                                            }
                                            return {
                                                results: $.map(data, function(obj) {
                                                    return {id: obj.id,text: obj.text};
                                                })
                                            };
                                        }catch{
                                            console.log(\'error\');
                                        }
                                    },
                                    cache: true
                                }
                            });

                            $("#'.$str_guion_c.$obj->id.'").change(function(){
                                var valor = $(this).attr("opt");
                                if ($(this).val() && $(this).val() !==0) {
                                    valor = $(this).val();
                                }
                                $.ajax({
                                    url   : "formularios/'.$str_guion.'/'.$str_guion.'_Funciones_Busqueda_Manual.php",
                                    data  : { dameValoresCamposDinamicos_Guion_'.$str_guion_c.$obj->id.' : valor},
                                    type  : "post",
                                    dataType : "json",
                                    success  : function(data){
                                        $("#'.$str_guion_c.$obj->id.'").html(\'<option value="\'+data.G'.$guionSelect2.'_ConsInte__b+\'" >\'+data.'.$objSQLPregui_t->CAMPO__Nombre____b.'+\'</option>\');
                                        '.$strLlenadoDinamico_t.'
                                    }
                                });
                            });';

							$joins.="LEFT JOIN {$BaseDatos}.G{$objSQLPregui_t->base} ON {$str_guion_c}{$obj->id}=G{$objSQLPregui_t->base}_ConsInte__b";
							$camposJoins.=",{$objSQLPregui_t->CAMPO__Nombre____b}";
                            $funcionesCampoGuion .= '
                            if(isset($_GET[\'CallDatosCombo_Guion_'.$str_guion_c.$obj->id.'\'])){
                                $Ysql = "SELECT G'.$guionSelect2.'_ConsInte__b as id, '.$objSQLPregui_t->CAMPO__Nombre____b.' as text FROM ".$BaseDatos.".G'.$guionSelect2.' WHERE '.$objSQLPregui_t->CAMPO__Nombre____b.' LIKE \'%".$_POST[\'q\']."%\'";
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
                            if(isset($_POST["dameValoresCamposDinamicos_Guion_'.$str_guion_c.$obj->id.'"])){
                                 $strSQLOpt_t = "SELECT * FROM ".$BaseDatos.".G'.$objSQLPregui_t->base.' WHERE G'.$objSQLPregui_t->base.'_ConsInte__b = ".$_POST["dameValoresCamposDinamicos_Guion_'.$str_guion_c.$obj->id.'"];
                                $resSQLOpt_t = $mysqli->query($strSQLOpt_t);

                                if ($resSQLOpt_t) {
                                    if ($resSQLOpt_t->num_rows > 0) {
                                        $objSQLOpt_t = $resSQLOpt_t->fetch_object();
                                        echo json_encode($objSQLOpt_t);
                                    }
                                }

                            }';
	             
		                    fputs($fp , $campo);
		                    fputs($fp , chr(13).chr(10)); // Genera saldo de linea */
                            $strCamposSelect.=",{$str_guion_c}{$obj->id}";
						}
                        break;

	       			case '8':
						$campo = ' 	
                        <!-- CAMPO DE TIPO CHECKBOX O SI/NO -->
				<div class="col-xs-4">
					<div class="form-group">
                    <label for="'.$str_guion_c.$obj->id.'" id="Lbl'.$str_guion_c.$obj->id.'">'.($obj->titulo_pregunta).'</label>
					    <div class="checkbox">
                        <label>
					            <input type="checkbox" value="1" name="'.$str_guion_c.$obj->id.'" id="'.$str_guion_c.$obj->id.'" data-error="Before you wreck yourself"  > 
                                </label>
                                </div>
                                </div>
				</div>
				<!-- FIN DEL CAMPO SI/NO -->';
                
                fputs($fp , $campo);
                fputs($fp , chr(13).chr(10)); // Genera saldo de linea 
                $strCamposSelect.=",{$str_guion_c}{$obj->id}";
                break;
                
                
                
                case '9':
                    $campo = ' 	
                    <!-- lIBRETO O LABEL -->
                    <div class="col-xs-4">
					<h3>'.($obj->titulo_pregunta).'</h3>
                    </div>
                    <!-- FIN LIBRETO -->';
                    fputs($fp , $campo);
                    fputs($fp , chr(13).chr(10)); // Genera saldo de linea 
                    $strCamposSelect.=",{$str_guion_c}{$obj->id}";
                    
                    break;
                    
                    case '14':
						$campo = ' 
                        <!-- CAMPO TIPO TEXTO -->
                        <div class="col-xs-4">
                        <div class="form-group">
					    <label for="'.$str_guion_c.$obj->id.'" id="Lbl'.$str_guion_c.$obj->id.'">'.($obj->titulo_pregunta).'</label>
					    <input type="email" class="form-control input-sm" id="'.$str_guion_c.$obj->id.'" name="'.$str_guion_c.$obj->id.'"  placeholder="'.($obj->titulo_pregunta).'">
                        </div>
                        </div>
                        <!-- FIN DEL CAMPO TIPO TEXTO -->';
                        
                //Armamos la validación de tipo email
                $strValidaCorreo_t .="
                if($('#".$str_guion_c.$obj->id."').val() != ''){                
                    var escorreo=/^[^@\s]+@[^@\.\s]+(\.[^@\.\s]+)+$/;         
                    if (!(escorreo.test($('#".$str_guion_c.$obj->id."').val()))) { 
                        alertify.error('Digite un correo valido');
                        $('#".$str_guion_c.$obj->id."').focus();
                        valido=1;
                    }
                }
                ";
                
                fputs($fp , $campo);
                fputs($fp , chr(13).chr(10)); // Genera saldo de linea 
                $strCamposSelect.=",{$str_guion_c}{$obj->id}";
	       				break;                      
	       			default:
	       				
	       				break;
	       		}
	       	}
        }
   	$strCamps_t = '';
   	$strSendCamps_t = '';
   	foreach ($arrCamposBusMan_t as $camp) {
		$strCamps_t .= 'var G'.$camp["script"].'_C'.$camp["colScript"].' = $("#'.$camp["camp"].'").val();'."\n\t\t\t";
   		$strSendCamps_t .= '&G'.$camp["script"].'_C'.$camp["colScript"].'=\'+G'.$camp["script"].'_C'.$camp["colScript"].'+\'';
   	}

   	$strCamps_t = substr($strCamps_t, 0, -4);
   	$strSendCamps_t = substr($strSendCamps_t, 0, -2);

   	if ($strSendCamps_t == '') {
	   	$strCamps_t = 'var SinEnlace = "No";';
	   	$strSendCamps_t = '&SinEnlace=\'+SinEnlace';
	}
	$campo = ' 	
			</form>
		</div>
	</div>
	<div class="col-md-1">&nbsp;</div>
</div>
<div class="row" id="botones">
	<div class="col-md-6 col-xs-6">
		<button class="btn btn-block btn-danger" id="btnCancelar" type="button"><?php echo $texto; ?></button>
	</div>
	<div class="col-md-6 col-xs-6">
		<button class="btn btn-block btn-success" id="btnBuscar" type="button"><i class="fa fa-search"></i>';
    switch($intTipoBusqueda_t){
        case '1':
            $campo .='Buscar en toda la base de datos';
            break;
        
        case '2':
            $campo .='Buscar sobre los registros incluidos en la campaña';
            break;
        
        case '3':
            $campo .='Buscar en los registros incluidos en la campaña y asignados a mi';
            break;    
    }        
    $campo .='</button>
	</div>
</div>
<br/>
<div class="row" id="resulados">
	<div class="col-md-12 col-xs-12" id="resultadosBusqueda">

	</div>
</div>
<div class="row">
	<div class="col-md-12" id="gestiones">
		<iframe id="frameContenedor" src="" style="width: 100%; height: auto;"  marginheight="0" marginwidth="0" noresize  frameborder="0" onload="autofitIframe(this);">
              
        </iframe>
	</div>
</div>
<input type="hidden" id="id_gestion_cbx" value="<?php echo $_GET["id_gestion_cbx"];?>">
<script type="text/javascript">
<?php 
    $id_campan=\'\';
    if(isset($_GET[\'id_campana_crm\']) && $_GET[\'id_campana_crm\'] != \'0\'){
        $id_campan=\'&id_campana_crm=\'.$_GET[\'id_campana_crm\'];
    } 
?>

    function bindEvent(element, eventName, eventHandler) {
        if (element.addEventListener) {
            element.addEventListener(eventName, eventHandler, false);
        } else if (element.attachEvent) {
            element.attachEvent(\'on\' + eventName, eventHandler);
        }
    }


    // Listen to message from child window
    bindEvent(window, \'message\', function (e) {
        if(Array.isArray(e.data)){
            console.log(e.data);
            var keys=Object.keys(e.data[0].camposivr);
            var key=0;
            $.each(e.data[0].camposivr, function(i, valor){
                if($("#"+keys[key]).length > 0){
                    $("#"+keys[key]).val(valor); 
                }
                key++;
            });
            if(e.data[0].cantidad_registros != 0){
                buscarRegistros(e.data);
            }
//          $("#btnBuscar").click();
        }
        
        if(typeof(e.data)== \'object\'){
            switch (e.data["accion"]){
                case "llamadaDesdeG" :
                    parent.postMessage(e.data, \'*\');
                    break;
                case "cerrargestion":
                    var origen="formulario"
                    finalizarGestion(e.data[datos],origen);
                    break;
            }
        }
    });
    
	$(function(){
        '.$strRegistroAuto.'
    ';
    
        $campo .="
        //DATEPICKER
        {$strDatepicker}
        "; 
            
        $campo .="
        //TIMEPICKER
        {$strTimepicker}
        ";
		$campo .="
        //TIMEPICKER
        {$strValidaDecimal}
        ";
		$campo .="
        //TIMEPICKER
        {$strValidaEntero}
        ";                                    
    
		$campo .='var ObjDataGestion= new Object();
        ObjDataGestion.server         = \'<?php echo $http; ?>\';
        ObjDataGestion.canal          = \'<?php if (isset($_GET["canal"])) { echo $_GET["canal"]; }else{ echo "Sin canal"; }?>\';
        ObjDataGestion.token          = \'<?php echo $_GET["token"];?>\';
        ObjDataGestion.predictiva     = \'<?php if(isset($_GET[\'predictiva\'])) { echo $_GET[\'predictiva\']; } else{ echo \'null\'; }?>\';
        ObjDataGestion.consinte       = \'<?php if(isset($_GET[\'consinte\'])) { echo $_GET[\'consinte\']; } else{ echo \'null\'; }?>\';
        ObjDataGestion.id_campana_crm = \'<?php if(isset($_GET[\'id_campana_crm\'])) { echo $_GET[\'id_campana_crm\']; } else{ echo \'null\'; }?>\';
        ObjDataGestion.sentido        = \'<?php if(isset($_GET[\'sentido\'])) { echo $_GET[\'sentido\']; } else{ echo \'null\'; }?>\';
        ObjDataGestion.usuario        = \'<?php if(isset($_GET[\'usuario\'])) { echo $_GET[\'usuario\']; } else{ echo \'0\'; }?>\';
        ObjDataGestion.origen         = \'<?php if(isset($_GET[\'origen\'])) { echo $_GET[\'origen\']; } else{ echo \'Sin origen\'; }?>\';
        ObjDataGestion.ani            = \'<?php if(isset($_GET[\'ani\'])) { echo $_GET[\'ani\']; } else{ echo \'0\'; }?>\';

        $("#btnBuscar").click(function(){
            var valido=0;
            '.$strValidaCorreo_t.'
            
            if(valido==0){
                var datos = $("#formId").serialize();
                $.ajax({
                    url     	: \'formularios/'.$str_guion.'/'.$str_guion.'_Funciones_Busqueda_Manual.php?action=GET_DATOS<?=$id_campan?>&agente=<?=$_GET["usuario"]?>\',
                    type		: \'post\',
                    dataType	: \'json\',
                    data		: datos,
                    success 	: function(datosq){
                        if(datosq[0].cantidad_registros > 1){
                            buscarRegistros(datosq);
                        }else if(datosq[0].cantidad_registros == 1){
                            $("#buscador").hide();
                            $("#botones").hide();
                            $("#resulados").hide();
                            let id = datosq[0].registros[0].'.$str_guion.'_ConsInte__b;
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
														$("#frameContenedor").attr(\'src\', \'<?php echo $http ;?>/crm_php/Estacion_contact_center.php?campan=true&user=\'+ id +\'&view=si&canal=<?php if (isset($_GET["canal"])) { echo $_GET["canal"]; }else{ echo "Sin canal"; } ?>&token=<?php echo $_GET["token"];?>&id_gestion_cbx=\'+ $("#id_gestion_cbx").val() + \'<?php if(isset($_GET[\'predictiva\'])) { echo "&predictiva=".$_GET[\'predictiva\']; }?>&consinte=\'+ id +\'<?php if(isset($_GET[\'id_campana_crm\'])) { echo "&campana_crm=".$_GET[\'id_campana_crm\']; }?><?php if(isset($_GET[\'sentido\'])) { echo "&sentido=".$_GET[\'sentido\']; }?><?php if(isset($_GET["usuario"])) { echo "&usuario=".$_GET["usuario"]; }?><?php if(isset($_GET[\'origen\'])){echo \'&origen=\'.$_GET[\'origen\'];}else{echo \'&origen=BusquedaManual\';} ?>\');
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
												$("#frameContenedor").attr(\'src\', \'<?php echo $http ;?>/crm_php/Estacion_contact_center.php?campan=true&user=\'+ id +\'&view=si&canal=<?php if (isset($_GET["canal"])) { echo $_GET["canal"]; }else{ echo "Sin canal"; } ?>&token=<?php echo $_GET["token"];?>&id_gestion_cbx=\'+ $("#id_gestion_cbx").val() + \'<?php if(isset($_GET[\'predictiva\'])) { echo "&predictiva=".$_GET[\'predictiva\']; }?>&consinte=\'+ id +\'<?php if(isset($_GET[\'id_campana_crm\'])) { echo "&campana_crm=".$_GET[\'id_campana_crm\']; }?><?php if(isset($_GET[\'sentido\'])) { echo "&sentido=".$_GET[\'sentido\']; }?><?php if(isset($_GET["usuario"])) { echo "&usuario=".$_GET["usuario"]; }?><?php if(isset($_GET[\'origen\'])){echo \'&origen=\'.$_GET[\'origen\'];}else{echo \'&origen=BusquedaManual\';} ?>\');
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
                            adicionarRegistro(2,datosq[0].mensaje);
                        }
                    }
                });
            }
		});

		$("#btnCancelar").click(function(){
            $("#btnCancelar").attr("disabled",true);
            $.ajax({
                url     	: \'formularios/generados/PHP_Cerrar_Cancelar.php?consinte=\'+obtenerIdInsertado()+\'&canal=<?php if (isset($_GET["canal"])) { echo $_GET["canal"]; }else{ echo "Sin canal"; } ?>&token=<?php echo $_GET[\'token\'];?>&id_gestion_cbx=\'+ $("#id_gestion_cbx").val() + \'<?php if(isset($_GET[\'id_campana_crm\'])) { echo "&campana_crm=".$_GET[\'id_campana_crm\']; }?><?php if(isset($_GET[\'sentido\'])) { echo "&sentido=".$_GET[\'sentido\']; }?><?php if(isset($_GET[\'origen\'])){echo \'&origen=\'.$_GET[\'origen\'];}else{echo \'&origen=BusquedaManual\';} ?><?php echo "&idMonoef=".$idMonoef;?><?php echo "&tiempoInicio=".date("Y-m-d H:i:s");?><?php if(isset($_GET["usuario"])) { echo "&usuario=".$_GET["usuario"]; }?>\',
                type		: \'post\',
                dataType	: \'json\',
                success 	: function(data){
                    var origen="bqmanual";
                    finalizarGestion(data,origen);    
                }
            });    
		});

		'.$select2.'
	});

    function buscarRegistros(datosq){
        var ObjDataGestion= new Object();
        ObjDataGestion.server         = \'<?php echo $http; ?>\';
        ObjDataGestion.canal          = \'<?php if (isset($_GET["canal"])) { echo $_GET["canal"]; }else{ echo "sin canal"; }?>\';
        ObjDataGestion.token          = \'<?php echo $_GET["token"];?>\';
        ObjDataGestion.predictiva     = \'<?php if(isset($_GET[\'predictiva\'])) { echo $_GET[\'predictiva\']; } else{ echo \'null\'; }?>\';
        ObjDataGestion.consinte       = \'<?php if(isset($_GET[\'consinte\'])) { echo $_GET[\'consinte\']; } else{ echo \'null\'; }?>\';
        ObjDataGestion.id_campana_crm = \'<?php if(isset($_GET[\'id_campana_crm\'])) { echo $_GET[\'id_campana_crm\']; } else{ echo \'null\'; }?>\';
        ObjDataGestion.sentido        = \'<?php if(isset($_GET[\'sentido\'])) { echo $_GET[\'sentido\']; } else{ echo \'null\'; }?>\';
        ObjDataGestion.usuario        = \'<?php if(isset($_GET[\'usuario\'])) { echo $_GET[\'usuario\']; } else{ echo \'0\'; }?>\';
        ObjDataGestion.origen         = \'<?php if(isset($_GET[\'origen\'])) { echo $_GET[\'origen\']; } else{ echo \'Sin origen\'; }?>\';
        ObjDataGestion.ani            = \'<?php if(isset($_GET[\'ani\'])) { echo $_GET[\'ani\']; } else{ echo \'0\'; }?>\';        
        var valores = null;
        var tabla_a_mostrar = \'<div class="box box-default">\'+
        \'<div class="box-header">\'+
            \'<h3 class="box-title">RESULTADOS DE LA BUSQUEDA</h3>\'+
        \'</div>\'+
        \'<div class="box-body">\'+
            \'<table class="table table-hover table-bordered" style="width:100%;">\';
        tabla_a_mostrar += \'<thead>\';
        tabla_a_mostrar += \'<tr>\';
        tabla_a_mostrar += \' '.$str_tablaCampos.' \';
        tabla_a_mostrar += \'</tr>\';
        tabla_a_mostrar += \'</thead>\';
        tabla_a_mostrar += \'<tbody>\';
        $.each(datosq[0].registros, function(i, item) {
            tabla_a_mostrar += \'<tr idMuestra="\'+ item.id_muestra +\'" ConsInte="\'+ item.'.$str_guion.'_ConsInte__b +\'" class="EditRegistro">\';
            tabla_a_mostrar += \''.$str_tablaCuerpo.'\';
            tabla_a_mostrar += \'</tr>\';
        });
        tabla_a_mostrar += \'</tbody>\';
        tabla_a_mostrar += \'</table></div></div>\';

        $("#resultadosBusqueda").html(tabla_a_mostrar);

        $(".EditRegistro").dblclick(function(){
            let id = $(this).attr("ConsInte");
            let muestra=$(this).attr("idMuestra");
            swal({
                html : true,
                title: "Información - Dyalogo CRM",
                text: \'Esta seguro de editar este registro?\',
                type: "warning",
                confirmButtonText: "Editar registro",
                cancelButtonText : "No Editar registro",
                showCancelButton : true,
                closeOnConfirm : true
            },
                function(isconfirm){
                    if(isconfirm){
                        $("#buscador").hide();
                        $("#botones").hide();
                        $("#resulados").hide();
                        if(muestra == \'null\'){
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
													$("#frameContenedor").attr(\'src\', \'<?php echo $http ;?>/crm_php/Estacion_contact_center.php?campan=true&user=\'+ id +\'&view=si&canal=<?php if (isset($_GET["canal"])) { echo $_GET["canal"]; }else{ echo "Sin canal"; } ?>&token=<?php echo $_GET["token"];?>&id_gestion_cbx=\'+ $("#id_gestion_cbx").val() + \'<?php if(isset($_GET[\'predictiva\'])) { echo "&predictiva=".$_GET[\'predictiva\']; }?>&consinte=\'+ id +\'<?php if(isset($_GET[\'id_campana_crm\'])) { echo "&campana_crm=".$_GET[\'id_campana_crm\']; }?><?php if(isset($_GET[\'sentido\'])) { echo "&sentido=".$_GET[\'sentido\']; }?><?php if(isset($_GET["usuario"])) { echo "&usuario=".$_GET["usuario"]; }?><?php if(isset($_GET[\'origen\'])){echo \'&origen=\'.$_GET[\'origen\'];}else{echo \'&origen=BusquedaManual\';} ?>\');
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
											$("#frameContenedor").attr(\'src\', \'<?php echo $http ;?>/crm_php/Estacion_contact_center.php?campan=true&user=\'+ id +\'&view=si&canal=<?php if (isset($_GET["canal"])) { echo $_GET["canal"]; }else{ echo "Sin canal"; } ?>&token=<?php echo $_GET["token"];?>&id_gestion_cbx=\'+ $("#id_gestion_cbx").val() + \'<?php if(isset($_GET[\'predictiva\'])) { echo "&predictiva=".$_GET[\'predictiva\']; }?>&consinte=\'+ id +\'<?php if(isset($_GET[\'id_campana_crm\'])) { echo "&campana_crm=".$_GET[\'id_campana_crm\']; }?><?php if(isset($_GET[\'sentido\'])) { echo "&sentido=".$_GET[\'sentido\']; }?><?php if(isset($_GET["usuario"])) { echo "&usuario=".$_GET["usuario"]; }?><?php if(isset($_GET[\'origen\'])){echo \'&origen=\'.$_GET[\'origen\'];}else{echo \'&origen=BusquedaManual\';} ?>\');
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
                        $("#buscador").show();
                        $("#botones").show();
                        $("#resulados").show();
                    }
                });
            });    
    }
    
	function adicionarRegistro(tipo,mensaje){
        var ObjDataGestion= new Object();
        ObjDataGestion.server         = \'<?php echo $http; ?>\';
        ObjDataGestion.canal          = \'<?php if (isset($_GET["canal"])) { echo $_GET["canal"]; }else{ echo "Sin canal"; }?>\';
        ObjDataGestion.token          = \'<?php echo $_GET["token"];?>\';
        ObjDataGestion.predictiva     = \'<?php if(isset($_GET[\'predictiva\'])) { echo $_GET[\'predictiva\']; } else{ echo \'null\'; }?>\';
        ObjDataGestion.consinte       = \'<?php if(isset($_GET[\'consinte\'])) { echo $_GET[\'consinte\']; } else{ echo \'null\'; }?>\';
        ObjDataGestion.id_campana_crm = \'<?php if(isset($_GET[\'id_campana_crm\'])) { echo $_GET[\'id_campana_crm\']; } else{ echo \'null\'; }?>\';
        ObjDataGestion.sentido        = \'<?php if(isset($_GET[\'sentido\'])) { echo $_GET[\'sentido\']; } else{ echo \'null\'; }?>\';
        ObjDataGestion.usuario        = \'<?php if(isset($_GET[\'sentido\'])) { echo $_GET[\'usuario\']; } else{ echo \'0\'; }?>\';
        ObjDataGestion.origen         = \'<?php if(isset($_GET[\'origen\'])) { echo $_GET[\'origen\']; } else{ echo \'Sin origen\'; }?>\';
        ObjDataGestion.ani            = \'<?php if(isset($_GET[\'ani\'])) { echo $_GET[\'ani\']; } else{ echo \'0\'; }?>\';        
    
        if(mensaje == null ){
            if(tipo == "1"){
                mensaje = "Desea adicionar un registro";
            }
            if(tipo == "2"){
                mensaje = "No se encontraron datos'.$mensajeRegistro.'";
            }
            swal({
                html : true,
                title: "Información - Dyalogo CRM",
                text: mensaje,
                type: "warning",
                 confirmButtonText: "Adicionar registro",
                cancelButtonText : "Hacer otra busqueda",
                showCancelButton : true,
                showConfirmButton : '.$showConfirmButton.',
                closeOnConfirm : true
            },
            function(isconfirm){
                if(isconfirm){
                    nuevoRegistro();
                }else{
                    limpiar();
                }
            });
        }else{
            swal("", mensaje, "warning");
        }
	}

    function nuevoRegistro(){
        //JDBD - obtenemos los valores de la busqueda manual para enviarselos al formulario
        '.$strCamps_t.'
        $.ajax({
            url     	: \'formularios/generados/PHP_Ejecutar.php?action=ADD<?php if(isset($_GET[\'canal\'])){ echo "&canal={$_GET[\'canal\']}";}?>&campana_crm=<?php echo $_GET[\'id_campana_crm\'];?><?php if(isset($_GET[\'idUSUARI\'])){ echo "&idUSUARI={$_GET[\'idUSUARI\']}";}?>\',
            type		: \'post\',
            dataType	: \'json\',
            success 	: function(numeroIdnuevo){
                $("#buscador").hide();
                $("#botones").hide();
                $("#resulados").hide();
                <?php if(isset($_GET[\'token\'])){ ?>
                $("#frameContenedor").attr(\'src\', \'<?php echo $http ;?>/crm_php/Estacion_contact_center.php?campan=true&user=\'+ numeroIdnuevo +\'&view=si&canal=<?php if (isset($_GET["canal"])) { echo $_GET["canal"]; }else{ echo "Sin canal"; } ?>&token=<?php echo $_GET["token"];?>&id_gestion_cbx=\'+ $("#id_gestion_cbx").val() + \'<?php if(isset($_GET[\'predictiva\'])) { echo "&predictiva=".$_GET[\'predictiva\']; }?>&consinte=\'+ numeroIdnuevo +\'<?php if(isset($_GET[\'id_campana_crm\'])) { echo "&campana_crm=".$_GET[\'id_campana_crm\']; }?><?php if(isset($_GET[\'sentido\'])) { echo "&sentido=".$_GET[\'sentido\']; }?><?php if(isset($_GET[\'origen\'])){echo \'&origen=\'.$_GET[\'origen\'];}else{echo \'&origen=BusquedaManual\';} ?><?php if(isset($_GET["usuario"])) { echo "&usuario=".$_GET["usuario"]; }?>&nuevoregistro=true'.$strSendCamps_t.');
                <?php } ?>
            }
        });
    }
    
	function limpiar(){
		$("#buscador").show();
		$("#buscador :input").each(function(){
			$(this).val("");
		});
		$("#resultadosBusqueda").html("");
		$("#botones").show();
		$("#resulados").show();
	}
</script>';
			
			/* Escribimos lo que hemos generado arriba el Html , PHP , JAVASCRIPT */
			
			fputs($fp , $campo);
			fputs($fp , chr(13).chr(10)); // Genera saldo de linea 
			fclose($fp); //cerramos el editor de ese archivo

		
			
			/* Abrimos un editor para el archivo que hara las busquedas */

			$fp2 = fopen($carpeta."/".$str_guion."_Funciones_Busqueda_Manual.php" , "w");
			//chmod($carpeta."/".$str_guion."_Funciones_Busqueda_Manual.php" , 0777); 
			$campo = '<?php
	ini_set(\'display_errors\', \'On\');
	ini_set(\'display_errors\', 1);
	include(__DIR__."/../../conexion.php");
	date_default_timezone_set(\'America/Bogota\');
    if (!empty($_SERVER[\'HTTP_X_REQUESTED_WITH\']) && strtolower($_SERVER[\'HTTP_X_REQUESTED_WITH\']) == \'xmlhttprequest\') {
		if(isset($_GET[\'action\']) && $_GET[\'action\'] == "GET_DATOS"){

			//JDBD - Busqueda manual.          
            if(isset($_GET["id_campana_crm"]) && $_GET["id_campana_crm"] != 0){

                $querymuestra="SELECT CAMPAN_ConsInte__GUION__Pob_b, CAMPAN_ConsInte__MUESTR_b FROM ".$BaseDatos_systema.".CAMPAN where CAMPAN_ConsInte__b=".$_GET["id_campana_crm"];

                $querymuestra=$mysqli->query($querymuestra);

                if($querymuestra && $querymuestra->num_rows > 0){
                    $datoCampan=$querymuestra->fetch_array();

                    $str_Pobla_Campan = "G".$datoCampan["CAMPAN_ConsInte__GUION__Pob_b"];
                    $intMuestra= $str_Pobla_Campan."_M".$datoCampan["CAMPAN_ConsInte__MUESTR_b"];
                    
                }        
            }
            ';

        //ARMAR LOS JOINS CON CADA SUBFORMULARIO
        $joinsSubForm='';
        foreach($subForms as $subForm){
            $joinsSubForm.=" LEFT JOIN DYALOGOCRM_WEB.G{$subForm['guion']} ON G{$id_busqueda}_C{$subForm['campoBD']} = G{$subForm['guion']}_C{$subForm['campoSub']}";
        }

        $intAgente_t=0;
        switch($intTipoBusqueda_t){
            case '1':
                $campo .='$str_Lsql = " SELECT ".$intMuestra."_CoInMiPo__b AS id_muestra, ".$intMuestra."_ConIntUsu_b AS agente, G'.$id_busqueda.'_ConsInte__b '.$strCamposSelect. ' ' .$camposJoins.' FROM ".$BaseDatos.".".$str_Pobla_Campan." LEFT JOIN ".$BaseDatos.".".$intMuestra." ON ".$str_Pobla_Campan."_ConsInte__b = ".$intMuestra."_CoInMiPo__b';
                break;

            case '2':
                $campo .='$str_Lsql = " SELECT ".$intMuestra."_CoInMiPo__b AS id_muestra, ".$intMuestra."_ConIntUsu_b AS agente, G'.$id_busqueda.'_ConsInte__b '.$strCamposSelect. ' ' .$camposJoins.' FROM ".$BaseDatos.".".$str_Pobla_Campan." JOIN ".$BaseDatos.".".$intMuestra." ON ".$str_Pobla_Campan."_ConsInte__b = ".$intMuestra."_CoInMiPo__b';                
                break;

            case '3':
                $campo .='$str_Lsql = " SELECT ".$intMuestra."_CoInMiPo__b AS id_muestra, ".$intMuestra."_ConIntUsu_b AS agente, G'.$id_busqueda.'_ConsInte__b '.$strCamposSelect. ' ' .$camposJoins.' FROM ".$BaseDatos.".".$str_Pobla_Campan." JOIN ".$BaseDatos.".".$intMuestra." ON ".$str_Pobla_Campan."_ConsInte__b = ".$intMuestra."_CoInMiPo__b';
                $intAgente_t=1;
                break;
            default:
                $campo .='$str_Lsql = " SELECT ".$intMuestra."_CoInMiPo__b AS id_muestra, ".$intMuestra."_ConIntUsu_b AS agente, G'.$id_busqueda.'_ConsInte__b '.$strCamposSelect. ' ' .$camposJoins.' FROM ".$BaseDatos.".".$str_Pobla_Campan." LEFT JOIN ".$BaseDatos.".".$intMuestra." ON ".$str_Pobla_Campan."_ConsInte__b=".$intMuestra."_CoInMiPo__b';
                break;
                
        }
            $campo .=$joinsSubForm;
			$campo.=" {$joins}\";"."\n";
            $campo .='$Where = " WHERE ".$str_Pobla_Campan."_ConsInte__b > 0 ";
			$usados = 0;'.$where.'
            ';
            
            $strValidaAgente="\$arrayDatos[] = \$key;";
            $strBlockAgent='';
            if($intAgente_t==1){
                $strValidaAgente="if(\$key['agente']== \$_GET['agente']){
                    \$arrayDatos[] = \$key;
                }";

                $strBlockAgent='
                else{
                    if($usados == 1 && $resultado->num_rows == 1){   
                        $newJson[0][\'mensaje\'] = "No tienes permiso para acceder a este registro";
                    }
                }
                ';
            }
            
			$campo .='
            $str_Lsql .= $Where. " GROUP BY G'.$id_busqueda.'_ConsInte__b LIMIT 20";
            //echo $str_Lsql;
			$resultado = $mysqli->query($str_Lsql);
			$arrayDatos = array();
			while ($key = $resultado->fetch_assoc()) {
				'.$strValidaAgente.'
			}'.$strBlockAgent.'

			$newJson = array();
			$newJson[0][\'cantidad_registros\'] = count($arrayDatos);
			$newJson[0][\'registros\'] = $arrayDatos;
            if($usados == 0 && $resultado->num_rows == 0){
                $newJson[0][\'mensaje\'] = "No se encontraron registros para este tipo de busqueda";
            }

			echo json_encode($newJson);
		}

		'.$funcionesCampoGuion.'
	}
?>';		/* Escribimos lo que hemos generado arriba */

			fputs($fp2 , $campo);
			fputs($fp2 , chr(13).chr(10)); // Genera saldo de linea 
			fclose($fp2); 

		}else{
			echo "no se puede generar si no me envias nada";
		}
	}
