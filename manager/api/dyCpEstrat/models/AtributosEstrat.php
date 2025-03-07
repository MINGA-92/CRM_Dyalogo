<?php
class AtributosEstrat
{
    protected static $db;

    // ****** ESTRATEGIA ******************************
    private $idEstrat;
    private $strIdHuesped;
    private $strEstratNombre;
    private $strEstratComentari;
    private $strEstratColor;
    private $strEstratIdGuion;

    // ****** ESTPAS ******************************
    private $strIdEstpas;
    private $strEstpasNombre;
    private $strEstpasTipo;
    private $strEstpasComentario;
    private $intEstpasActivo;
    private $intEstpasBySistema;
    private $strEstpasLoca;
    private $arrData;

    // ****** ESTCON ******************************
    private $strIdEstcon;
    private $strEstconNombre;
    private $strEstconIdOrigen;
    private $strEstconIdDestino;
    private $strEstconPortOrigen;
    private $strEstconPortDestino;
    private $strEstconCoordenadas;
    private $strEstconComentario;
    
    // ****** FORMULARIO ******************************
    private $idForm;
    private $idSeccion;
    private $idopcion;
    private $idSubForm;
    private $idPregun;
    
    public function __construct(bool $bpo=false)
    {
        if($bpo){
            self::$db = Helpers::connectBPO();
        }else{
            self::$db = Helpers::connect();
        }
    }

    // MOSTRAR ERROR CUANDO EL SISTEMA NO PUEDA COMPLETAR UNA SOLICITUD
    protected function error():void
    {
        showResponse("No se pudo completar la solicitud",false,500,"500 Internal Server Error");
    }

    public function consumirFormData($strURL_p, $postFields_p)
    {

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL,$strURL_p);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'Content-Type: application/x-www-form-urlencoded',
            'X-Requested-With: XMLHttpRequest'
        ));
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $postFields_p);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER,true);
        curl_setopt($ch, CURLOPT_VERBOSE,true);
	    //Obtenemos la respuesta
	    $objRespuestaCURL_t = curl_exec($ch);

	    //Obtenemos el error 
	    $objRespuestaError_t = curl_error($ch);

	    //Cerramos la conexion
	    curl_close ($ch);

	    //Validamos la respuesta y generamos el rerno
	    if (isset($objRespuestaCURL_t)) {
	        //Decodificamos la respuesta en JSON y la retornamos
	        return $objRespuestaCURL_t;
	    }else {
	        return $objRespuestaError_t;
	    }
    }

    //  INVOCAR FUNCIONALIDADES DE LOS CRUDS
    protected function consumirWSJSON($strURL_p, $arrayDatos_p)
    {

	    //Codificamos el arreglo en formato JSON
	    $strDatosJSON_t = json_encode($arrayDatos_p);
	    
	    //Inicializamos la conexion CURL al web service local para ser consumido
	    $objCURL_t = curl_init($strURL_p);
	    
	    //Asignamos todos los parametros del consumo
	    curl_setopt($objCURL_t, CURLOPT_CUSTOMREQUEST, "POST");                                                                     
	    curl_setopt($objCURL_t, CURLOPT_POSTFIELDS, $strDatosJSON_t); 
	    curl_setopt($objCURL_t,CURLOPT_RETURNTRANSFER,true);
	    curl_setopt($objCURL_t, CURLOPT_HTTPHEADER, array(
	        'Accept: application/json',
	        'Content-Type: application/json',
	        'Content-Length: ' . strlen($strDatosJSON_t))                                                                      
	    );

	    //Obtenemos la respuesta
	    $objRespuestaCURL_t = curl_exec($objCURL_t);

	    //Obtenemos el error 
	    $objRespuestaError_t = curl_error($objCURL_t);

	    //Cerramos la conexion
	    curl_close ($objCURL_t);

	    //Validamos la respuesta y generamos el rerno
	    if (isset($objRespuestaCURL_t)) {
	        //Decodificamos la respuesta en JSON y la retornamos
	        return $objRespuestaCURL_t;
	    }else {
	        return $objRespuestaError_t;
	    }
	}

    // ****** ESTRATEGIA ******************************
    public function getIdEstrat()
    {
        return $this->idEstrat;
    }

    public function setIdEstrat($idEstrat, $bpo=false)
    {
        if($bpo){
            $this->idEstrat = Helpers::decodeIdBPO($idEstrat,'ESTRAT_ConsInte__b','DYALOGOCRM_SISTEMA.ESTRAT');
        }else{
            $this->idEstrat = Helpers::decodeId($idEstrat,'ESTRAT_ConsInte__b','DYALOGOCRM_SISTEMA.ESTRAT');
        }
        return $this;
    }

    public function getStrIdHuesped()
    {
        return $this->strIdHuesped;
    }

    public function setStrIdHuesped($strIdHuesped)
    {
        $this->strIdHuesped = Helpers::decodeId(self::$db->real_escape_string($strIdHuesped), 'id', 'dyalogo_general.huespedes');
        return $this;
    }

    public function getStrEstratNombre()
    {
        return $this->strEstratNombre;
    }

    public function setStrEstratNombre($strEstratNombre)
    {
        $this->strEstratNombre = $strEstratNombre;
        return $this;
    }

    public function getStrEstratComentari()
    {
        return $this->strEstratComentari;
    }

    public function setStrEstratComentari($strEstratComentari)
    {
        if(is_null($strEstratComentari)){
            $this->strEstratComentari= 'NULL';
        }else{
            $this->strEstratComentari = "'{$strEstratComentari}'";
        }
        return $this;
    }

    public function getStrEstratColor()
    {
        return $this->strEstratColor;
    }

    public function setStrEstratColor($strEstratColor)
    {
        $this->strEstratColor = $strEstratColor;
        return $this;
    }

    public function getStrEstratIdGuion()
    {
        return $this->strEstratIdGuion;
    }

    public function setStrEstratIdGuion($strEstratIdGuion, $bpo=false)
    {
        if(!is_null($strEstratIdGuion)){
            if($bpo){
                $this->strEstratIdGuion = Helpers::decodeIdBPO($strEstratIdGuion,'GUION__ConsInte__b','DYALOGOCRM_SISTEMA.GUION_');
            }else{
                $this->strEstratIdGuion = Helpers::decodeId($strEstratIdGuion,'GUION__ConsInte__b','DYALOGOCRM_SISTEMA.GUION_');
            }
            return $this;
        }
    }

    // ****** ESTPAS ******************************
    public function getStrIdEstpas()
    {
        return $this->strIdEstpas;
    }

    public function setStrIdEstpas($strIdEstpas, $bpo=false)
    {
        if($bpo){
            $this->strIdEstpas = Helpers::decodeIdBPO($strIdEstpas,'ESTPAS_ConsInte__b','DYALOGOCRM_SISTEMA.ESTPAS');
        }else{
            $this->strIdEstpas = Helpers::decodeId($strIdEstpas,'ESTPAS_ConsInte__b','DYALOGOCRM_SISTEMA.ESTPAS');
        }
        return $this;
    }

    public function getStrEstpasNombre()
    {
        return $this->strEstpasNombre;
    }

    public function setStrEstpasNombre($strEstpasNombre)
    {
        $this->strEstpasNombre = $strEstpasNombre;
        return $this;
    }

    public function getStrEstpasTipo()
    {
        return $this->strEstpasTipo;
    }

    public function setStrEstpasTipo($strEstpasTipo)
    {
        $this->strEstpasTipo = $strEstpasTipo;
        return $this;
    }

    public function getStrEstpasComentario()
    {
        return $this->strEstpasComentario;
    }

    public function setStrEstpasComentario($strEstpasComentario)
    {
        if(is_null($strEstpasComentario)){
            $this->strEstpasComentario = 'NULL';
        }else{
            $this->strEstpasComentario = "'{$strEstpasComentario}'";
        }
        return $this;
    }

    public function getIntEstpasActivo()
    {
        return $this->intEstpasActivo;
    }

    public function setIntEstpasActivo($intEstpasActivo)
    {
        $this->intEstpasActivo = $intEstpasActivo;
        return $this;
    }

    public function getIntEstpasBySistema()
    {
        return $this->intEstpasBySistema;
    }

    public function setIntEstpasBySistema($intEstpasBySistema)
    {
        $this->intEstpasBySistema = $intEstpasBySistema;
        return $this;
    }

    public function getStrEstpasLoca()
    {
        return $this->strEstpasLoca;
    }

    public function setStrEstpasLoca($strEstpasLoca)
    {
        if(is_null($strEstpasLoca)){
            $this->strEstpasLoca = 'NULL';
        }else{
            $this->strEstpasLoca = "'{$strEstpasLoca}'";
        }
        return $this;
    }

    public function getArrData()
    {
        return $this->arrData;
    }
    public function setArrData($arrData)
    {
        $this->arrData = $arrData;
        return $this;
    }

    // ****** ESTCON ******************************
    public function getStrIdEstcon()
    {
        return $this->strIdEstcon;
    }

    public function setStrIdEstcon($strIdEstcon,$bpo=false)
    {
        if($bpo){
            $this->strIdEstcon = Helpers::decodeIdBPO($strIdEstcon,'ESTCON_ConsInte__b','DYALOGOCRM_SISTEMA.ESTCON');
        }else{
            $this->strIdEstcon = Helpers::decodeId($strIdEstcon,'ESTCON_ConsInte__b','DYALOGOCRM_SISTEMA.ESTCON');
        }
        return $this;
    }

    public function getStrEstconNombre()
    {
        return $this->strEstconNombre;
    }

    public function setStrEstconNombre($strEstconNombre)
    {
        $this->strEstconNombre = $strEstconNombre;
        return $this;
    }

    public function getStrEstconIdOrigen()
    {
        return $this->strEstconIdOrigen;
    }

    public function setStrEstconIdOrigen($strEstconIdOrigen,$bpo=false)
    {
        if($bpo){
            $this->strEstconIdOrigen = Helpers::decodeIdBPO($strEstconIdOrigen,'ESTPAS_ConsInte__b','DYALOGOCRM_SISTEMA.ESTPAS');
        }else{
            $this->strEstconIdOrigen = Helpers::decodeId($strEstconIdOrigen,'ESTPAS_ConsInte__b','DYALOGOCRM_SISTEMA.ESTPAS');
        }
        return $this;
    }

    public function getStrEstconIdDestino()
    {
        return $this->strEstconIdDestino;
    }

    public function setStrEstconIdDestino($strEstconIdDestino,$bpo=false)
    {
        if($bpo){
            $this->strEstconIdDestino = Helpers::decodeIdBPO($strEstconIdDestino,'ESTPAS_ConsInte__b','DYALOGOCRM_SISTEMA.ESTPAS');
        }else{
            $this->strEstconIdDestino = Helpers::decodeId($strEstconIdDestino,'ESTPAS_ConsInte__b','DYALOGOCRM_SISTEMA.ESTPAS');
        }
        return $this;
    }

    public function getStrEstconPortOrigen()
    {
        return $this->strEstconPortOrigen;
    }

    public function setStrEstconPortOrigen($strEstconPortOrigen)
    {
        $this->strEstconPortOrigen = $strEstconPortOrigen;
        return $this;
    }

    public function getStrEstconPortDestino()
    {
        return $this->strEstconPortDestino;
    }

    public function setStrEstconPortDestino($strEstconPortDestino)
    {
        $this->strEstconPortDestino = $strEstconPortDestino;
        return $this;
    }

    public function getStrEstconCoordenadas()
    {
        return $this->strEstconCoordenadas;
    }

    public function setStrEstconCoordenadas($strEstconCoordenadas)
    {
        if(is_null($strEstconCoordenadas)){
            $this->strEstconCoordenadas = 'NULL';
        }else{
            $this->strEstconCoordenadas = $strEstconCoordenadas;
        }
        return $this;
    }

    public function getStrEstconComentario()
    {
        return $this->strEstconComentario;
    }

    public function setStrEstconComentario($strEstconComentario)
    {
        if(is_null($strEstconComentario)){
            $this->strEstconComentario = "NULL";
        }else{
            $this->strEstconComentario = $strEstconComentario;
        }
        return $this;
    }

    // ****** FORMULARIO ******************************
    public function getIdForm()
    {
        return $this->idForm;
    }

    public function setIdForm($idForm, $bpo=false)
    {
        if(!is_null($idForm)){
            if($bpo){
                $this->idForm = Helpers::decodeIdBPO($idForm,'GUION__ConsInte__b','DYALOGOCRM_SISTEMA.GUION_');
            }else{
                $this->idForm = Helpers::decodeId($idForm,'GUION__ConsInte__b','DYALOGOCRM_SISTEMA.GUION_');
            }
            return $this;
        }
    }

    public function getIdSeccion()
    {
        return $this->idSeccion;
    }

    public function setIdSeccion($idSeccion, $bpo=false)
    {
        if($bpo){
            $this->idSeccion = Helpers::decodeIdBPO($idSeccion,'SECCIO_ConsInte__b','DYALOGOCRM_SISTEMA.SECCIO');
        }else{
            $this->idSeccion = Helpers::decodeId($idSeccion,'SECCIO_ConsInte__b','DYALOGOCRM_SISTEMA.SECCIO');
        }
        return $this;
    }

    public function getIdopcion()
    {
        return $this->idopcion;
    }

    public function setIdopcion($idopcion, $bpo=false)
    {
        if($bpo){
            $this->idopcion = Helpers::decodeIdBPO($idopcion,'OPCION_ConsInte__b','DYALOGOCRM_SISTEMA.OPCION');
        }else{
            $this->idopcion = Helpers::decodeId($idopcion,'OPCION_ConsInte__b','DYALOGOCRM_SISTEMA.OPCION');
        }
        return $this;
    }

    public function getIdSubForm()
    {
        return $this->idSubForm;
    }

    public function setIdSubForm($idSubForm, $bpo=false)
    {
        if($bpo){
            $this->idSubForm = Helpers::decodeIdBPO($idSubForm,'GUION__ConsInte__b','DYALOGOCRM_SISTEMA.GUION_');
        }else{
            $this->idSubForm = Helpers::decodeId($idSubForm,'GUION__ConsInte__b','DYALOGOCRM_SISTEMA.GUION_');
        }
        return $this;
    }

    public function getIdPregun()
    {
        return $this->idPregun;
    }

    public function setIdPregun($idPregun, $bpo=false)
    {
        if($bpo){
            $this->idPregun = Helpers::decodeIdBPO($idPregun,'PREGUN_ConsInte__b','DYALOGOCRM_SISTEMA.PREGUN');
        }else{
            $this->idPregun = Helpers::decodeId($idPregun,'PREGUN_ConsInte__b','DYALOGOCRM_SISTEMA.PREGUN');
        }
        return $this;
    }
}