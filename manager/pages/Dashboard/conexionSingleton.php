<?php

	class conexionSingleton{
		private static $instancia;
		private $tiempoReal = array();
		private $tiempoRealDash = array();
		private $actividaActual = array();
		private $dbh;


		// Un constructor privado evita la creación de un nuevo objeto
	    private function __construct() {
			$this->dbh = new PDO("mysql:host=localhost;dbname=dyalogo_general", "dyalogoadm", "dyalogoadm*bd");
	    }

	    // método singleton
	    public static function singleton()
	    {
	        if (!isset(self::$instancia)) {
	            $miclase = __CLASS__;
	            self::$instancia = new $miclase;
	        } 
	        return self::$instancia;
	    }

	    public function realTimeUsers($huesped = null)
		{	
			$query3    = "SELECT fecha_hora_cambio_estado FROM dyalogo_general.actividad_actual ";
			if($huesped != null){
				$query3 .= " WHERE id > 0 AND id_huesped = ".$huesped;
			}else{
				$query3 .= " WHERE id > 0 ";
			}
			
			$query3   .= " ORDER BY fecha_hora_cambio_estado DESC LIMIT 1";
			$consulta = $this->dbh->prepare($query3);
			$consulta->execute();
            $reg = $consulta->fetch();
            return $reg;     
            
        }

		public function realTimeUsersDash($huesped = null)
		{	
			$query       = "SELECT * FROM actividad_actual";
			if($huesped != null){
				$query .= " WHERE id > 0 AND id_huesped = ".$huesped;
			}else{
				$query .= " WHERE id > 0 ";
			}
			$query      .= " ORDER BY fecha_hora_cambio_estado DESC LIMIT 1";
			$consulta = $this->dbh->prepare($query);
			$consulta->execute();
			if ($consulta->rowCount() > 0) 
			{      
				$reg = $consulta->fetch();
				 	
	            return $reg;     
	        }

	        
		}


		public function getDashBoard($huesped, $order ){
			
	        $query  = "SELECT * FROM actividad_actual JOIN huespedes_usuarios ON actividad_actual.id_usuario = huespedes_usuarios.id_usuario WHERE huespedes_usuarios.id_huesped = ".$huesped." AND actividad_actual.id > 0 ORDER BY ".$order;
	        $consulta = $this->dbh->prepare($query);
			$consulta->execute();
			if ($consulta->rowCount() > 0) 
			{
	            return $consulta->fetchAll(); 
	        }

	        
	        
		}

		public function getColorEstrategia($id){
			$query = "SELECT ESTRAT_Color____b FROM DYALOGOCRM_SISTEMA.ESTRAT WHERE ESTRAT_ConsInte__b = ".$id;
         	$consulta = $this->dbh->prepare($query);
			$consulta->execute();
			if ($consulta->rowCount() > 0) 
			{
	            return $consulta->fetch(); 
	        }

	        
		}


		public function getRealTimeEstrategia($estrategia){
			$query =  "SELECT * FROM dyalogo_general.actividad_actual WHERE id_estrategia = ".$estrategia;
         	$consulta = $this->dbh->prepare($query);
			$consulta->execute();
			if ($consulta->rowCount() > 0) 
			{
	            return $consulta->fetchAll();
	        }

	        
		}


		function getCountDisponibles($huesped){
			$query = "SELECT COUNT(*) as total FROM dyalogo_general.actividad_actual JOIN dyalogo_general.huespedes_usuarios ON actividad_actual.id_usuario = huespedes_usuarios.id_usuario WHERE huespedes_usuarios.id_huesped = ".$huesped." AND estado = 'Disponible' ";
         	$consulta = $this->dbh->prepare($query);
			$consulta->execute();
            return $consulta->fetch(); 
            
		}

		function getCountPausados($huesped){
			$query = "SELECT COUNT(*) as total FROM dyalogo_general.actividad_actual JOIN dyalogo_general.huespedes_usuarios ON actividad_actual.id_usuario = huespedes_usuarios.id_usuario WHERE huespedes_usuarios.id_huesped = ".$huesped." AND estado = 'Pausado' ";
         	$consulta = $this->dbh->prepare($query);
			$consulta->execute();
           	return $consulta->fetch(); 
           	
		}

		function getCountOcupados($huesped){
			$query = "SELECT COUNT(*) as total FROM dyalogo_general.actividad_actual JOIN dyalogo_general.huespedes_usuarios ON actividad_actual.id_usuario = huespedes_usuarios.id_usuario WHERE huespedes_usuarios.id_huesped = ".$huesped." AND (estado like  '%Ocupado%' OR estado = 'Timbrando') ";
         	$consulta = $this->dbh->prepare($query);
			$consulta->execute();
	        return $consulta->fetch(); 
	        

		}

		function getCountInicial($huesped){
			$query = "SELECT COUNT(*) as total FROM dyalogo_general.actividad_actual JOIN dyalogo_general.huespedes_usuarios ON actividad_actual.id_usuario = huespedes_usuarios.id_usuario WHERE huespedes_usuarios.id_huesped = ".$huesped." AND (estado = 'Inicial' or estado='No disponible') ";
         	$consulta = $this->dbh->prepare($query);
			$consulta->execute();
			return $consulta->fetch(); 
			
		}
	   
	    // Evita que el objeto se pueda clonar
	    public function __clone()
	    {
	        trigger_error('La clonación de este objeto no está permitida', E_USER_ERROR);
	    }
	}