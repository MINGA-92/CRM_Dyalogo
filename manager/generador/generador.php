<?php
	ini_set('display_errors', 1);
	error_reporting(E_ALL);
	date_default_timezone_set('America/Bogota');
	

	function generar_formularios_busquedas($idFormulario, $generar_formulario,  $generar_busqueda, $generar_web_form){

		global $mysqli;
		global $BaseDatos;
		global $BaseDatos_systema;
		global $BaseDatos_telefonia;
		global $dyalogo_canales_electronicos;
		global $BaseDatos_general;
		global $permisosCarpeta;

		$Lsql = "SELECT GUION__Tipo______b FROM ".$BaseDatos_systema.".GUION_ WHERE GUION__ConsInte__b = ".$idFormulario;
		$res_Lsql = $mysqli->query($Lsql);
		$datoArray = $res_Lsql->fetch_array();
		if($datoArray['GUION__Tipo______b'] == 2){
			/* es de tipo Base de datos toca generarle la busqueda manual, el formulario para Backoffice y el index.php para Formulario Web Contacto */
			/* generar busqueda manual */
			
			if($generar_busqueda != 0){
				generar_Busqueda_Manual($idFormulario);

				/* Generar el form de busquedas Ani - Datos adicionales */
				generar_Busqueda_Ani($idFormulario);


				/* Generar la busqueda Telefonica */
				generar_Busqueda_Dato_Adicional($idFormulario);
			
			}
			
			if($generar_web_form != 0){
				/* generar fomulario web */
				
				generar_Web_Form($idFormulario);
				
			}
			
			/* generar formulario para Backofice */
			
			if($generar_formulario != 0){
				generar_Formulario_Script($idFormulario);
			}

		}else{
			/* solo toca generarlos como scripts */
			if($generar_formulario != 0){
				if($datoArray['GUION__Tipo______b'] == 1 || $datoArray['GUION__Tipo______b'] == 3 ){
					generar_Formulario_Script($idFormulario);
					generar_Script_Web($idFormulario);
				}else{
					generar_Formulario_Script_backoffice($idFormulario);
					generar_Busqueda_Manual_Backoffice($idFormulario);
					generar_Script_Web($idFormulario);
				}
				
			}
		}

		/* Cambiar los permisos de la puta carpeta */
		
		$carpeta = $permisosCarpeta.'G'.$idFormulario;

		//$cpu = shell_exec($carpeta);
		    
	}

	/* busqueda manual */
	
	include ("busqueda_manual.php");

	/* busqueda Ani */
	include ("busqueda_ani.php");

	/* Generar Formularios */
	
	include ("busqueda_dato_adicional.php");

	/* Generar Formularios */
	
	include ("generacion_formularios.php");	


	/* Generar Formularios BAckoffices*/
	
	include ("generacion_formularios_Backoffice.php");	


	/* Generar Formularios BAckoffices buqueda*/
	
	include ("busqueda_manual_backoffice.php");	

	/* Generar Web forms */
	
	include ("generar_web_form.php");	


	/* Generar Web Script */
	
	include ("generar_script_web.php");	
