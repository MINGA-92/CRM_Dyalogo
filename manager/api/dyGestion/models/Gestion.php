<?php

class Gestion{
    private static $db;
    private $intIdCampanaCrm;
    private $strCanal;
    private $strOrigen;
    private $intSentido;
    private $intMiembro;
    private $intAgente;
    private $strIdComunicacion;
    private $strDatoContacto;
    private $intPasoId=0;
    private $fechaMiembroBD="NULL";
    private $intFormulario=0;
    private $intGuionBD=0;
    private $intCampoTipificacion=0;

    public function __construct()
    {
        self::$db = Helpers::connect();
    }

    public function getIdCampanaCrm():int
    {
        return $this->intIdCampanaCrm;
    }

    public function setIdCampanaCrm(int $id):void
    {
        $this->intIdCampanaCrm=self::$db->real_escape_string($id);
    }

    public function getstrCanal():string
    {
        return $this->strCanal;
    }

    public function setstrCanal(string $canal):void
    {
        $this->strCanal=self::$db->real_escape_string($canal);
    }

    public function getstrOrigen():string
    {
        return $this->strOrigen;
    }

    public function setstrOrigen(string $origen):void
    {
        $this->strOrigen=self::$db->real_escape_string($origen);
    }

    public function getintSentido():int
    {
        return $this->intSentido;
    }

    public function setintSentido(int $sentido):void
    {
        $this->intSentido=self::$db->real_escape_string($sentido);
    }

    public function getintMiembro():int
    {
        return $this->intMiembro;
    }

    public function setintMiembro(int $miembro):void
    {
        $this->intMiembro=self::$db->real_escape_string($miembro);
    }

    public function getintAgente():int
    {
        return $this->intAgente;
    }

    public function setintAgente(int $agente):void
    {
        $this->intAgente=self::$db->real_escape_string($agente);
    }

    public function getstrIdComunicacion():string
    {
        return $this->strIdComunicacion;
    }

    public function setstrIdComunicacion(string $comunicacion):void
    {
        $this->strIdComunicacion=self::$db->real_escape_string($comunicacion);
    }

    public function getstrDatoContacto():string
    {
        return $this->strDatoContacto;
    }

    public function setstrDatoContacto(string $datoContacto):void
    {
        $this->strDatoContacto=self::$db->real_escape_string($datoContacto);
    }


                        //INICIO METODOS


    // MOSTRAR ERROR CUANDO EL SISTEMA NO PUEDA COMPLETAR UNA SOLICITUD
    private function error():void
    {
        showResponse("No se pudo completar la solicitud",false,500,"500 Internal Server Error");
    }

    // OBTENGO LOS FORMULARIO DE LA CAMPAÑA
	private function getFormularios(int $campan):void
	{
        try {
            $sql=self::$db->query("SELECT CAMPAN_ConsInte__GUION__Gui_b AS formulario, CAMPAN_ConsInte__GUION__Pob_b AS poblacion FROM DYALOGOCRM_SISTEMA.CAMPAN WHERE CAMPAN_ConsInte__b={$campan}");
            if($sql && $sql->num_rows ==1){
                $sql=$sql->fetch_object();
                $this->intFormulario=$sql->formulario;
                $this->intGuionBD=$sql->poblacion;
            }else{
                $this->error();
            }
        } catch (\Throwable $th) {
            $this->error();
        }
	}

    // OBTENGO EL CAMPO TIPIFICACIÓN DEL FORMULARIO DE LA CAMPAÑA
	private function getCampoTipificacion(int $guion):void
    {
        try {
            $sql=self::$db->query("SELECT PREGUN_ConsInte__b FROM DYALOGOCRM_SISTEMA.PREGUN LEFT JOIN DYALOGOCRM_SISTEMA.SECCIO ON PREGUN_ConsInte__SECCIO_b=SECCIO_ConsInte__b WHERE PREGUN_ConsInte__GUION__b ={$guion} AND PREGUN_Texto_____b='Tipificación' AND SECCIO_TipoSecc__b=3");
            if($sql->num_rows > 0){
                $sql=$sql->fetch_object();
                $this->intCampoTipificacion=$sql->PREGUN_ConsInte__b;
            }else{
                $this->error();
            }
        } catch (\Throwable $th) {
            $this->error();
        }
	}

    // OBTENGO LA FECHA DE INSERCIÓN DEL CÓDIGO MIEMBRO
    private function getFechaBD(int $intBD):void
    {
        try {
            $strFechaPoblacion = self::$db->query("SELECT G{$intBD}_FechaInsercion AS fechaBD FROM  DYALOGOCRM_WEB.G{$intBD} WHERE G{$intBD}_ConsInte__b = {$this->getintMiembro()}");
            if($strFechaPoblacion && $strFechaPoblacion->num_rows > 0){
                //validar fecha bd
                $strFechaPoblacion = $strFechaPoblacion->fetch_object();
                if(!is_null($strFechaPoblacion->fechaBD)){
                    $this->fechaMiembroBD = "'{$strFechaPoblacion->fechaBD}'";
                }
            }
        } catch (\Throwable $th) {
           $this->error();
        }
    }

    // OBTENGO EL ESTPAS DE LA CAMPAÑA
    private function getPasoId(int $campan):void
    {
        try {
            $sqlpasoId=self::$db->query("SELECT ESTPAS_ConsInte__b FROM DYALOGOCRM_SISTEMA.ESTPAS WHERE ESTPAS_ConsInte__CAMPAN_b = {$campan} AND (ESTPAS_Tipo______b = 1 OR ESTPAS_Tipo______b = 6)");            
            if($sqlpasoId){
               $array = $sqlpasoId->fetch_array();  
                if($array["ESTPAS_ConsInte__b"] != ''){
                    $this->intPasoId=$array["ESTPAS_ConsInte__b"];     
                }else{
                    $this->error();
                }
           }
        } catch (\Throwable $th) {
            $this->error();
        }
    }

    // INSERTO SOLO CUANDO HAY UN ID DE COMUNICACIÓN QUE NO EXISTE EN EL FORMULARIO
    public function saveGestion():array
    {
        try {
            $this->getFormularios($this->getIdCampanaCrm());
            $this->getPasoId($this->getIdCampanaCrm());
            $this->getCampoTipificacion($this->intFormulario);
            $this->getFechaBD($this->intGuionBD);
            (string) $sentido='Saliente';
            if($this->getintSentido() == 2){
                $sentido = 'Entrante';
            }
    
            (array)$response=array();
            $response['estado']='error';
    
            //VALIDAR SI EL ID DE COMUNICACIÓN YA EXISTE
            $sqlValidate=self::$db->query("SELECT G{$this->intFormulario}_ConsInte__b AS id FROM DYALOGOCRM_WEB.G{$this->intFormulario} WHERE G{$this->intFormulario}_IdLlamada='{$this->getstrIdComunicacion()}' AND G{$this->intFormulario}_C{$this->intCampoTipificacion}=-22");
            if($sqlValidate){
                if($sqlValidate->num_rows==0){
                    $sql=self::$db->query("INSERT INTO DYALOGOCRM_WEB.G{$this->intFormulario} (G{$this->intFormulario}_ConsInte__b,G{$this->intFormulario}_Usuario,G{$this->intFormulario}_FechaInsercion,G{$this->intFormulario}_DatoContacto,G{$this->intFormulario}_C{$this->intCampoTipificacion},G{$this->intFormulario}_Canal_____b,G{$this->intFormulario}_Origen_b,G{$this->intFormulario}_Sentido___b,G{$this->intFormulario}_Clasificacion,G{$this->intFormulario}_IdLlamada,G{$this->intFormulario}_Paso,G{$this->intFormulario}_DetalleCanal,G{$this->intFormulario}_CodigoMiembro, G{$this->intFormulario}_FechaInsercionBD_b ) VALUES (NULL,{$this->getintAgente()},NOW(),'{$this->getstrDatoContacto()}','-22','{$this->getstrCanal()}','{$this->getstrOrigen()}','{$sentido}','1','{$this->getstrIdComunicacion()}',{$this->intPasoId},{$this->getintAgente()},{$this->getintMiembro()}, {$this->fechaMiembroBD})");
                    if($sql){
                        $idInsertado=self::$db->insert_id;
                        //INSERTAMOS LA ACCION EN EL LOG DEL STORAGE
                        $sql=self::$db->query("INSERT INTO DYALOGOCRM_SISTEMA.LOGSTORAGE VALUES(NULL,NOW(),{$idInsertado},'{$this->getstrOrigen()}','{$this->getstrIdComunicacion()}',{$this->intPasoId},{$this->intFormulario},{$this->getintMiembro()},{$this->getintAgente()},'Se inserta una gestión vacia al abrir el formulario por primera vez -- WS')");
                        $response['estado']='ok';
                        $response['mensaje']=$idInsertado;
                    }else{
                        //INSERTAR EN EL LOG DEL STORAGE
                        $error=self::$db->error;
                        $sql="INSERT INTO DYALOGOCRM_WEB.G{$this->intFormulario} (G{$this->intFormulario}_ConsInte__b,G{$this->intFormulario}_Usuario,G{$this->intFormulario}_FechaInsercion,G{$this->intFormulario}_DatoContacto,G{$this->intFormulario}_C{$this->intCampoTipificacion},G{$this->intFormulario}_Canal_____b,G{$this->intFormulario}_Origen_b,G{$this->intFormulario}_Sentido___b,G{$this->intFormulario}_Clasificacion,G{$this->intFormulario}_IdLlamada,G{$this->intFormulario}_Paso,G{$this->intFormulario}_DetalleCanal,G{$this->intFormulario}_CodigoMiembro, G{$this->intFormulario}_FechaInsercionBD_b ) VALUES (NULL,{$this->getintAgente()},NOW(),'{$this->getstrDatoContacto()}','-22','{$this->getstrCanal()}','{$this->getstrOrigen()}','{$sentido}','1','{$this->getstrIdComunicacion()}',{$this->intPasoId},{$this->getintAgente()},{$this->getintMiembro()}, {$this->fechaMiembroBD})";
    
                        self::$db->query("INSERT INTO DYALOGOCRM_SISTEMA.LOGGEST VALUES(NULL,NOW(),\"{$sql}\",'{$error}','Insercion desde el storage -- WS')");
                        self::$db->query("INSERT INTO DYALOGOCRM_SISTEMA.LOGSTORAGE VALUES(NULL,NOW(),NULL,'{$this->getstrOrigen()}','{$this->getstrIdComunicacion()}',{$this->intPasoId},{$this->intFormulario},{$this->getintMiembro()},{$this->getintAgente()},'Fallo al insertar la gestión vacia -- WS')");
                        $this->error();
                    }
                }else{
                    //INSERTAR EN EL LOG DEL STORAGE
                    $sqlValidate=$sqlValidate->fetch_object();
                    self::$db->query("INSERT INTO DYALOGOCRM_SISTEMA.LOGSTORAGE VALUES(NULL,NOW(),$sqlValidate->id,'{$this->getstrOrigen()}','{$this->getstrIdComunicacion()}',{$this->intPasoId},{$this->intFormulario},{$this->getintMiembro()},{$this->getintAgente()},'Ya se había insertado la gestión vacia anteriormente -- WS')");
                    $response['estado']='ok';
                    $response['mensaje']=$sqlValidate->id;
                }
            }else{
                //INSERTAR EN EL LOG DEL STORAGE
                self::$db->query("INSERT INTO DYALOGOCRM_SISTEMA.LOGSTORAGE VALUES(NULL,NOW(),NULL,'{$this->getstrOrigen()}','{$this->getstrIdComunicacion()}',{$this->intPasoId},{$this->intFormulario},{$this->getintMiembro()},{$this->getintAgente()},'Fallo al insertar la gestión vacia porque no se detecto el id de la comunicación -- WS')");
                $this->error();
            }
            return $response;
        } catch (\Throwable $th) {
            $this->error();
        }
    }
}