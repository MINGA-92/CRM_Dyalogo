<?php
	include ('../conexion.php');
	set_time_limit(0); //Establece el número de segundos que se permite la ejecución de un script.
	date_default_timezone_set('America/Bogota');
	$fecha_ac = isset($_POST['timestamp']) ? $_POST['timestamp']:0;
	$fecha_bd = null;
	

	while( $fecha_bd <= $fecha_ac )
	{	
		$query3    = "SELECT METMED_FecHoraAct_b FROM ".$BaseDatos_systema.".METMED";
		$where     = " WHERE METMED_Consinte__b > 1";
		if(isset($_POST['estrategia'])){
			$query3 .= " JOIN ".$BaseDatos_systema.".METDEF ON METMED_Consinte__METDEF_b = METMED_Consinte__b ";
			$query3 .= $where." AND METDEF_Consinte__ESTRAT_b = ".$_POST['estrategia'];
			$query3   .= " ORDER BY METMED_FecHoraAct_b DESC LIMIT 1";
		}else{
			$query3 = $query3.$where." ORDER BY METMED_FecHoraAct_b DESC LIMIT 1";
		}

	
		$con       = $mysqli->query($query3);
		$ro        = $con->fetch_array();
		
		usleep(100000);//anteriormente 10000
		clearstatcache();
		$fecha_bd  = strtotime($ro['METMED_FecHoraAct_b']);
	}

	$query       = "SELECT * FROM ".$BaseDatos_systema.".METMED";
	$where 		 = " WHERE METMED_Consinte__b > 1";
	if(isset($_POST['estrategia'])){
		$query .= " JOIN ".$BaseDatos_systema.".METDEF ON METMED_Consinte__METDEF_b = METMED_Consinte__b ";
		$query .= $where." AND METDEF_Consinte__ESTRAT_b = ".$_POST['estrategia']." ORDER BY METMED_FecHoraAct_b DESC LIMIT 1";
	}else{
		$query = $query.$where." ORDER BY METMED_FecHoraAct_b DESC LIMIT 1";
	}
	
	
	$datos_query = $mysqli->query($query);
	while($row = $datos_query->fetch_array())
	{
		$ar["timestamp"]          					= strtotime($row['METMED_FecHoraAct_b']);	
		$ar["METMED_Consinte__b"] 	 		  		= $row['METMED_Consinte__b'];	
		$ar["METMED_Nombre___b"] 		         	= $row['METMED_Nombre___b'];	
		$ar["METMED_Valor____b"]          	  		= $row['METMED_Valor____b'];	
		$ar["METMED_Consinte__METDEF_b"]       		= $row['METMED_Consinte__METDEF_b'];
		$ar["METMED_Consinte__USUARI_b"]          	= $row['METMED_Consinte__USUARI_b'];	

		$fecha_bd 									= strtotime($row['METMED_FecHoraAct_b']);

	}

	$dato_json   = json_encode($ar);
	echo $dato_json;

?>