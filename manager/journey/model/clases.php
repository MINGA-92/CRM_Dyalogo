<?php

//session_start();
//ini_set('display_errors', 'On');
//ini_set('display_errors', 1);
require_once('../../../../manager/pages/conexion.php');
require_once('../../../../manager/global/WSCoreClient.php');
require_once('../../../../manager/api/config/db.php');
require_once('../../../../manager/api/helpers/Utils.php');
require_once('../../../../helpers/parameters.php');

class TablaEstrategia{
  private static $db;
  private $id;
  private $idBd;
  private $fechaHora;
  private $duracion;
  private $agente;
  private $sentido;
  private $canal;
  private $datoContacto;
  private $nombreBola;
  private $tipificacion;
  private $clasificacion;
  private $reintento;
  private $comentario;
  private $linkGrabacion;
  //private $res;
 
  public function __construct($id, $idBd, $fechaHora, $duracion, $agente, $sentido, $canal, $datoContacto, $nombreBola, $tipificacion, $clasificacion, $reintento, $comentario, $linkGrabacion){
    $this->id = $id;
    $this->idBd = $idBd;
    $this->fechaHora = $fechaHora;
    $this->duracion = $duracion;
    $this->agente = $agente;
    $this->sentido = $sentido;
    $this->canal = $canal;
    $this->datoContacto = $datoContacto;
    $this->nombreBola = $nombreBola;
    $this->tipificacion = $tipificacion;
    $this->clasificacion = $clasificacion;
    $this->reintento = $reintento;
    $this->comentario = $comentario;
    $this->linkGrabacion = $linkGrabacion;
  }

  public function validaExisteTabla(){
    //global $BaseDatos_systema;
    //$Lsql = "SELECT ESTRAT_ConsInte__b AS ID_ESTRAT " . $BaseDatos_systema . ".AUACAD (AUACAD_Fecha_____b , AUACAD_Hora______b, AUACAD_Ejecutor__b, AUACAD_TipoAcci__b , AUACAD_SubTipAcc_b, AUACAD_Accion____b , AUACAD_Huesped___b ) VALUES ('" . date('Y-m-d H:s:i') . "', '" . date('Y-m-d H:s:i') . "', " . $_SESSION['IDENTIFICACION'] . ", 'USUARI', '" . $accion . "', '" . $superAccion . "', '" . $_SESSION['HUESPED'] . "' );";
    //$strSQLCamposDinamicos_t = "SELECT PREGUN_ConsInte__b AS ID FROM ".$BaseDatos_systema.".ESTRAT ";
    $db = Helpers::connect();
    $Lsql = "SELECT ESTRAT_ConsInte__b FROM DYALOGOCRM_SISTEMA.ESTRAT WHERE ESTRAT_ConsInte__b = '1016'";  
    $res = $db->query($Lsql);
    if($res){
      echo "Tiene datos la tabla";
      $crearTabla = TablaEstrategia::crearTablaEstrategia();
    }else{
      echo "No existe la tabla";
    }
  }

  //Crea la tabla de base de datos de la estrategia, de una estrategia nueva
  public function crearTablaEstrategia(){
    //return $this->id;
    // $BaseDatos = "DYALOGOCRM_WEB";   "G".$intBd_p."_C"
    $idNombre = "1016prueba";
    //global $BaseDatos_systema;
    $db = Helpers::connect();
    $create_Lsql = "CREATE TABLE DYALOGOCRM_WEB.G1016prueba_J (
      G".$idNombre."_J_ConsInte__b bigint(20) NOT NULL AUTO_INCREMENT, 
      G".$idNombre."_J_ConsInteBd__b bigint(20) NOT NULL,
      G".$idNombre."_J_FechaHora__b datetime DEFAULT NULL,                               
      G".$idNombre."_J_Duracion__b time DEFAULT NULL,
      G".$idNombre."_J_Agente__b varchar(90) DEFAULT NULL , 
      G".$idNombre."_J_Sentido__b varchar(20) DEFAULT NULL,
      G".$idNombre."_J_Canal__b varchar(20) DEFAULT NULL,
      G".$idNombre."_J_DatoContacto__b bigint(15) DEFAULT NULL,
      G".$idNombre."_J_NombreBola__b varchar(50) DEFAULT NULL, 
      G".$idNombre."_J_Tipificacion__b varchar(50) DEFAULT NULL,
      G".$idNombre."_J_Clasificacion__b varchar(50) DEFAULT NULL,
      G".$idNombre."_J_Reintento__b varchar(50) DEFAULT NULL,
      G".$idNombre."_J_Comentario__b longtext DEFAULT NULL,
      G".$idNombre."_J_LinkGrabacion__b varchar(700) DEFAULT NULL,
      PRIMARY KEY (G".$idNombre."_J_ConsInte__b)) ENGINE=InnoDB AUTO_INCREMENT=0;";
    $res = $db->query($create_Lsql);
    if($res){
      echo "Se creo la tabla la tabla";
    }else{
      echo "No existe la tabla";
    }
  }

  public function insertarDatosEstrategia(){
    global $BaseDatos_systema;
    $Lsql = "INSERT INTO " . $BaseDatos_systema . ".AUACAD (AUACAD_Fecha_____b , AUACAD_Hora______b, AUACAD_Ejecutor__b, AUACAD_TipoAcci__b , AUACAD_SubTipAcc_b, AUACAD_Accion____b , AUACAD_Huesped___b ) VALUES ('" . date('Y-m-d H:s:i') . "', '" . date('Y-m-d H:s:i') . "', " . $_SESSION['IDENTIFICACION'] . ", 'USUARI', '" . $accion . "', '" . $superAccion . "', '" . $_SESSION['HUESPED'] . "' );";
    $res = $mysqli->query($Lsql);
  }

  public function getId(){
    return $this->id;
  }
  public function getIdBd(){
    return $this->idBd;
  }
  public function getFechaHora(){
    return $this->fechaHora;
  }
  public function getDuracion(){
    return $this->duracion;
  }
  public function getAgente(){
    return $this->agente;
  }
  public function getSentido(){
    return $this->sentido;
  }
  public function getCanal(){
    return $this->canal;
  }
  public function getdDatoContacto(){
    return $this->datoContacto;
  }

}



?>