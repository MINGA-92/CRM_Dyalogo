<?php

class AtributosForm {

    protected static $db;

    // ****** FORMULARIO GENERAL ******************************
    private $idForm;
    private $idFormBPO;
    private $strNombre;
    private $intTipo;
    private $strHuesped;
    private $strDesc;
    private $intTipoBQ;
    private $insertAuto;
    private $permiteInsert;

    // ****** SECCION ******************************
    private $seccioNombre;
    private $seccioOrden;
    private $seccioVista;
    private $seccioTipo;
    private $seccioMini;
    private $numColumnas;
    private $idSeccion;
    private $seccioIdBPO;

    // ****** LISTAS ******************************
    private $intListaId;
    private $strListaNombre;
    private $intListaIdBPO;

    // ****** OPCIONLISTA ******************************
    private $intOpcionId;
    private $strOpcionNombre;
    private $intOpcionPosicion;
    private $intOpcionMonoef;
    private $intOpcionClasificacion;
    private $strOpcionRpta;
    private $intOpcionIdPadre;
    private $intOpcionIdLisopcBPO;

    // ****** CAMPOS ******************************
    private $intCampoId;
    private $strCampoNombre;
    private $intCampoTipo;
    private $strCampoGuionAux;
    private $strCampoLista;
    private $intCampoOrden;
    private $intCampoMinimoNumero;
    private $intCampoMaximoNumero;
    private $strCampoFechaMinimo;
    private $strCampoFechaMaximo;
    private $strCampoHoraMini;
    private $strCampoHoraMaximo;
    private $strCampoError;
    private $intCampoValorDefecto;
    private $intCampoVista;
    private $intCampoPermiteAdicion;
    private $strCampoPregunPadre;
    private $intCampoNumDefecto;
    private $strCampotextDefecto;
    private $intCampoTiempoMas;
    private $strCampoPeriodo;
    private $strCampoformula;
    private $intCampoTipoTel;
    private $intCampoSendMail;
    private $intCampoSendSms;
    private $intCampoTxtSms;
    private $intCampoPrefijoSms;
    private $intCampoBuscaMail;
    private $intCampoFormato;
    private $intCampoPosDecimales;
    private $intCampoLongitud;
    private $intCampoMostrarSubForm;
    private $intCampoWebForm;
    private $intCampoIdBPO;

    // ****** LISTASAUXILIARES ******************************
    private $intAuxIdCampo;
    private $intAuxIdGuion;
    private $intAuxCampoGuion;

    // ****** SUBFORMULARIO ******************************
    private $intSubFormGuionHijo;
    private $strSubFormNombre;
    private $intSubFormLlavePadre;
    private $intSubFormLlaveHijo;
    private $intSubFormTotalPadre;
    private $intSubFormTotalHijo;
    private $intSubFormOperTotal;

    // ****** ETIQUETADO ******************************
    private $intEtiquetaPrincipal;
    private $intEtiquetaSecundaria;
    private $intEtiquetaLlave;

    // ****** SALTOS ******************************
    private $intSaltoId;
    private $intSaltoBySeccion;
    
    // ****** ITEMSALTOS ******************************
    private $intItemSaltoIdPregunDet;
    private $intItemSaltoIdSeccionDet;
    private $intItemSaltoTipoPregunDet;
    private $intItemSaltoLimpiarDet;
    private $strItemSaltoNombreDet;

    public function __construct()
    {
        self::$db = Helpers::connect();
    }

    protected function error():void
    {
        showResponse("No se pudo completar la solicitud",false,500,"500 Internal Server Error");
    }

    protected function replaceName(string $nombre):string
    {
        $str = crc32(Date("Y-m-d H:i:s"));
        $str= unpack("I", $str);
        return $nombre."_CLON_".$str[1];
    }

    // ****** FORMULARIO GENERAL ******************************
    public function getStrNombre()
    {
        return $this->strNombre;
    }

    public function setStrNombre($strNombre)
    {
        $this->strNombre = $this->replaceName(self::$db->real_escape_string($strNombre));
        return $this;
    }

    public function getStrHuesped()
    {
        return $this->strHuesped;
    }

    public function setStrHuesped($strHuesped)
    {
        $this->strHuesped = Helpers::decodeId(self::$db->real_escape_string($strHuesped), 'id', 'dyalogo_general.huespedes');
        return $this;
    }
 
    public function getStrDesc()
    {
        return $this->strDesc;
    }

    public function setStrDesc($strDesc)
    {
        $this->strDesc = self::$db->real_escape_string($strDesc);
        return $this;
    }

    public function getIntTipo()
    {
        return $this->intTipo;
    }

    public function setIntTipo($intTipo)
    {
        $this->intTipo = self::$db->real_escape_string($intTipo);
        return $this;
    }

    public function getPermiteInsert()
    {
        return $this->permiteInsert;
    }

    public function setPermiteInsert($permiteInsert)
    {
        $this->permiteInsert = $permiteInsert;
        return $this;
    }

    public function getInsertAuto()
    {
        return $this->insertAuto;
    }

    public function setInsertAuto($insertAuto)
    {
        $this->insertAuto = $insertAuto;
        return $this;
    }

    public function getIntTipoBQ()
    {
        return $this->intTipoBQ;
    }

    public function setIntTipoBQ($intTipoBQ)
    {
        $this->intTipoBQ = $intTipoBQ;
        return $this;
    }

    public function getIdForm()
    {
        return $this->idForm;
    }

    public function setIdForm($idForm)
    {
        $this->idForm = Helpers::decodeId(self::$db->real_escape_string($idForm), 'GUION__ConsInte__b', 'DYALOGOCRM_SISTEMA.GUION_');
        return $this;
    }

    public function getIdFormBPO()
    {
        return $this->idFormBPO;
    }

    public function setIdFormBPO($idFormBPO)
    {
        if(is_null($idFormBPO)){
            $this->idFormBPO = 'NULL';
        }else{
            $this->idFormBPO=Helpers::decodeIdBPO(self::$db->real_escape_string($idFormBPO),'GUION__ConsInte__b','DYALOGOCRM_SISTEMA.GUION_');
        }
        return $this;
    }

    // ****** SECCION ******************************
    public function getSeccioNombre()
    {
        return $this->seccioNombre;
    }

    public function setSeccioNombre($seccioNombre)
    {
        $this->seccioNombre = $seccioNombre;
        return $this;
    }

    public function getSeccioOrden()
    {
        return $this->seccioOrden;
    }

    public function setSeccioOrden($seccioOrden)
    {
        $this->seccioOrden = $seccioOrden;
        return $this;
    }

    public function getSeccioVista()
    {
        return $this->seccioVista;
    }

    public function setSeccioVista($seccioVista)
    {
        $this->seccioVista = $seccioVista;
        return $this;
    }

    public function getSeccioTipo()
    {
        return $this->seccioTipo;
    }

    public function setSeccioTipo($seccioTipo)
    {
        $this->seccioTipo = $seccioTipo;
        return $this;
    }

    public function getSeccioMini()
    {
        return $this->seccioMini;
    }

    public function setSeccioMini($seccioMini)
    {
        $this->seccioMini = $seccioMini;
        return $this;
    }

    public function getNumColumnas()
    {
        return $this->numColumnas;
    }

    public function setNumColumnas($numColumnas)
    {
        $this->numColumnas = $numColumnas;
        return $this;
    }

    public function getIdSeccion()
    {
        return $this->idSeccion;
    }

    public function setIdSeccion($idSeccion)
    {
        $this->idSeccion = Helpers::decodeId(self::$db->real_escape_string($idSeccion), 'SECCIO_ConsInte__b', 'DYALOGOCRM_SISTEMA.SECCIO');
        return $this;
    }

    public function getSeccioIdBPO()
    {
        return $this->seccioIdBPO;
    }

    public function setSeccioIdBPO($seccioIdBPO)
    {
        if(is_null($seccioIdBPO)){
            $this->seccioIdBPO = 'NULL';
        }else{
            $this->seccioIdBPO = Helpers::decodeIdBPO(self::$db->real_escape_string($seccioIdBPO), 'SECCIO_ConsInte__b', 'DYALOGOCRM_SISTEMA.SECCIO');
        }
        return $this;
    }

    // ****** CAMPOS ******************************
    public function getIntCampoId()
    {
        return $this->intCampoId;
    }

    public function setIntCampoId($intCampoId)
    {
        $this->intCampoId = Helpers::decodeId(self::$db->real_escape_string($intCampoId), 'PREGUN_ConsInte__b', 'DYALOGOCRM_SISTEMA.PREGUN');
        return $this;
    }

    public function getStrCampoNombre()
    {
        return $this->strCampoNombre;
    }

    public function setStrCampoNombre($strCampoNombre)
    {
        $this->strCampoNombre = $strCampoNombre;
        return $this;
    }

    public function getIntCampoTipo()
    {
        return $this->intCampoTipo;
    }

    public function setIntCampoTipo($intCampoTipo)
    {
        $this->intCampoTipo = $intCampoTipo;
        return $this;
    }

    public function getStrCampoGuionAux()
    {
        return $this->strCampoGuionAux;
    }

    public function setStrCampoGuionAux($strCampoGuionAux)
    {
        if(is_null($strCampoGuionAux)){
            $this->strCampoGuionAux = 'NULL';
        }else{
            $this->strCampoGuionAux = Helpers::decodeId(self::$db->real_escape_string($strCampoGuionAux), 'GUION__ConsInte__b', 'DYALOGOCRM_SISTEMA.GUION_');
        }
        return $this;
    }

    public function getStrCampoLista()
    {
        return $this->strCampoLista;
    }

    public function setStrCampoLista($strCampoLista)
    {
        if(is_null($strCampoLista)){
            $this->strCampoLista = 'NULL';
        }else{
            $this->strCampoLista = Helpers::decodeId(self::$db->real_escape_string($strCampoLista), 'OPCION_ConsInte__b', 'DYALOGOCRM_SISTEMA.OPCION');
        }
        return $this;
    }

    public function getIntCampoOrden()
    {
        return $this->intCampoOrden;
    }

    public function setIntCampoOrden($intCampoOrden)
    {
        $this->intCampoOrden = $intCampoOrden;
        return $this;
    }

    public function getIntCampoMinimoNumero()
    {
        return $this->intCampoMinimoNumero;
    }

    public function setIntCampoMinimoNumero($intCampoMinimoNumero)
    {
        if(is_null($intCampoMinimoNumero)){
            $this->intCampoMinimoNumero = 'NULL';
        }else{
            $this->intCampoMinimoNumero = $intCampoMinimoNumero;
        }
        return $this;
    }

    public function getIntCampoMaximoNumero()
    {
        return $this->intCampoMaximoNumero;
    }

    public function setIntCampoMaximoNumero($intCampoMaximoNumero)
    {
        if(is_null($intCampoMaximoNumero)){
            $this->intCampoMaximoNumero = 'NULL';
        }else{
            $this->intCampoMaximoNumero = $intCampoMaximoNumero;
        }
        return $this;
    }

    public function getStrCampoFechaMinimo()
    {
        return $this->strCampoFechaMinimo;
    }

    public function setStrCampoFechaMinimo($strCampoFechaMinimo)
    {
        if(is_null($strCampoFechaMinimo)){
            $this->strCampoFechaMinimo = 'NULL';
        }else{
            $this->strCampoFechaMinimo = "'{$strCampoFechaMinimo}'";
        }
        return $this;
    }

    public function getStrCampoFechaMaximo()
    {
        return $this->strCampoFechaMaximo;
    }

    public function setStrCampoFechaMaximo($strCampoFechaMaximo)
    {
        if(is_null($strCampoFechaMaximo)){
            $this->strCampoFechaMaximo = 'NULL';
        }else{
            $this->strCampoFechaMaximo = "'{$strCampoFechaMaximo}'";
        }
        return $this;
    }

    public function getStrCampoHoraMini()
    {
        return $this->strCampoHoraMini;
    }

    public function setStrCampoHoraMini($strCampoHoraMini)
    {
        if(is_null($strCampoHoraMini)){
            $this->strCampoHoraMini = 'NULL';
        }else{
            $this->strCampoHoraMini = "'{$strCampoHoraMini}'";
        }
        return $this;
    }

    public function getStrCampoHoraMaximo()
    {
        return $this->strCampoHoraMaximo;
    }

    public function setStrCampoHoraMaximo($strCampoHoraMaximo)
    {
        if(is_null($strCampoHoraMaximo)){
            $this->strCampoHoraMaximo = 'NULL';
        }else{
            $this->strCampoHoraMaximo = "'{$strCampoHoraMaximo}'";
        }
        return $this;
    }

    public function getStrCampoError()
    {
        return $this->strCampoError;
    }

    public function setStrCampoError($strCampoError)
    {
        if(is_null($strCampoError)){
            $this->strCampoError = 'NULL';
        }else{
            $this->strCampoError = "'{$strCampoError}'";
        }
        return $this;
    }

    public function getIntCampoValorDefecto()
    {
        return $this->intCampoValorDefecto;
    }

    public function setIntCampoValorDefecto($intCampoValorDefecto)
    {
        if(is_null($intCampoValorDefecto)){
            $this->intCampoValorDefecto = 'NULL';
        }else{
            $this->intCampoValorDefecto = $intCampoValorDefecto;
        }
        return $this;
    }

    public function getIntCampoVista()
    {
        return $this->intCampoVista;
    }

    public function setIntCampoVista($intCampoVista)
    {
        $this->intCampoVista = $intCampoVista;
        return $this;
    }

    public function getIntCampoPermiteAdicion()
    {
        return $this->intCampoPermiteAdicion;
    }

    public function setIntCampoPermiteAdicion($intCampoPermiteAdicion)
    {
        $this->intCampoPermiteAdicion = $intCampoPermiteAdicion;
        return $this;
    }

    public function getStrCampoPregunPadre()
    {
        return $this->strCampoPregunPadre;
    }

    public function setStrCampoPregunPadre($strCampoPregunPadre)
    {
        if(is_null($strCampoPregunPadre)){
            $this->strCampoPregunPadre = 'NULL';
        }else{
            $this->strCampoPregunPadre = Helpers::decodeId(self::$db->real_escape_string($strCampoPregunPadre), 'PREGUN_ConsInte__b', 'DYALOGOCRM_SISTEMA.PREGUN');
        }
        return $this;
    }

    public function getIntCampoNumDefecto()
    {
        return $this->intCampoNumDefecto;
    }

    public function setIntCampoNumDefecto($intCampoNumDefecto)
    {
        if(is_null($intCampoNumDefecto)){
            $this->intCampoNumDefecto = 'NULL';
        }else{
            $this->intCampoNumDefecto = $intCampoNumDefecto;
        }
        return $this;
    }

    public function getStrCampotextDefecto()
    {
        return $this->strCampotextDefecto;
    }

    public function setStrCampotextDefecto($strCampotextDefecto)
    {
        if(is_null($strCampotextDefecto)){
            $this->strCampotextDefecto = 'NULL';
        }else{
            $this->strCampotextDefecto = $strCampotextDefecto;
        }
        return $this;
    }

    public function getIntCampoTiempoMas()
    {
        return $this->intCampoTiempoMas;
    }

    public function setIntCampoTiempoMas($intCampoTiempoMas)
    {
        if(is_null($intCampoTiempoMas)){
            $this->intCampoTiempoMas = 'NULL';
        }else{
            $this->intCampoTiempoMas = $intCampoTiempoMas;
        }
        return $this;
    }

    public function getStrCampoPeriodo()
    {
        return $this->strCampoPeriodo;
    }

    public function setStrCampoPeriodo($strCampoPeriodo)
    {
        if(is_null($strCampoPeriodo)){
            $this->strCampoPeriodo = 'NULL';
        }else{
            $this->strCampoPeriodo = $strCampoPeriodo;
        }
        return $this;
    }

    public function getStrCampoformula()
    {
        return $this->strCampoformula;
    }

    public function setStrCampoformula($strCampoformula)
    {
        if(is_null($strCampoformula)){
            $this->strCampoformula = 'NULL';
        }else{
            $this->strCampoformula = $strCampoformula;
        }
        return $this;
    }

    public function getIntCampoTipoTel()
    {
        return $this->intCampoTipoTel;
    }

    public function setIntCampoTipoTel($intCampoTipoTel)
    {
        $this->intCampoTipoTel = $intCampoTipoTel;
        return $this;
    }

    public function getIntCampoSendMail()
    {
        return $this->intCampoSendMail;
    }

    public function setIntCampoSendMail($intCampoSendMail)
    {
        $this->intCampoSendMail = $intCampoSendMail;
        return $this;
    }

    public function getIntCampoSendSms()
    {
        return $this->intCampoSendSms;
    }

    public function setIntCampoSendSms($intCampoSendSms)
    {
        $this->intCampoSendSms = $intCampoSendSms;
        return $this;
    }

    public function getIntCampoTxtSms()
    {
        return $this->intCampoTxtSms;
    }

    public function setIntCampoTxtSms($intCampoTxtSms)
    {
        $this->intCampoTxtSms = $intCampoTxtSms;
        return $this;
    }

    public function getIntCampoPrefijoSms()
    {
        return $this->intCampoPrefijoSms;
    }

    public function setIntCampoPrefijoSms($intCampoPrefijoSms)
    {
        if(is_null($intCampoPrefijoSms)){
            $this->intCampoPrefijoSms = 'NULL';
        }else{
            $this->intCampoPrefijoSms = $intCampoPrefijoSms;
        }
        return $this;
    }

    public function getIntCampoBuscaMail()
    {
        return $this->intCampoBuscaMail;
    }

    public function setIntCampoBuscaMail($intCampoBuscaMail)
    {
        $this->intCampoBuscaMail = $intCampoBuscaMail;
        return $this;
    }

    public function getIntCampoFormato()
    {
        return $this->intCampoFormato;
    }

    public function setIntCampoFormato($intCampoFormato)
    {
        $this->intCampoFormato = $intCampoFormato;
        return $this;
    }

    public function getIntCampoPosDecimales()
    {
        return $this->intCampoPosDecimales;
    }

    public function setIntCampoPosDecimales($intCampoPosDecimales)
    {
        $this->intCampoPosDecimales = $intCampoPosDecimales;
        return $this;
    }

    public function getIntCampoLongitud()
    {
        return $this->intCampoLongitud;
    }

    public function setIntCampoLongitud($intCampoLongitud)
    {
        $this->intCampoLongitud = $intCampoLongitud;
        return $this;
    }

    public function getIntCampoMostrarSubForm()
    {
        return $this->intCampoMostrarSubForm;
    }

    public function setIntCampoMostrarSubForm($intCampoMostrarSubForm)
    {
        $this->intCampoMostrarSubForm = $intCampoMostrarSubForm;
        return $this;
    }
    public function getIntCampoWebForm()
    {
        return $this->intCampoWebForm;
    }

    public function setIntCampoWebForm($intCampoWebForm)
    {
        $this->intCampoWebForm = $intCampoWebForm;
        return $this;
    }

    public function getIntCampoIdBPO()
    {
        return $this->intCampoIdBPO;
    }

    public function setIntCampoIdBPO($intCampoIdBPO)
    {
        if(is_null($intCampoIdBPO)){
            $this->intCampoIdBPO = "NULL";
        }else{
            $this->intCampoIdBPO = Helpers::decodeIdBPO(self::$db->real_escape_string($intCampoIdBPO), 'PREGUN_ConsInte__b', 'DYALOGOCRM_SISTEMA.PREGUN');
        }
        return $this;
    }

    // ****** LISTAS ******************************
    public function getIntListaId()
    {
        return $this->intListaId;
    }

    public function setIntListaId($intListaId)
    {
        $this->intListaId = Helpers::decodeId(self::$db->real_escape_string($intListaId), 'OPCION_ConsInte__b', 'DYALOGOCRM_SISTEMA.OPCION');
        return $this;
    }

    public function getStrListaNombre()
    {
        return $this->strListaNombre;
    }

    public function setStrListaNombre($strListaNombre)
    {
        $this->strListaNombre = $strListaNombre;
        return $this;
    }

    public function getIntListaIdBPO()
    {
        return $this->intListaIdBPO;
    }

    public function setIntListaIdBPO($intListaIdBPO)
    {
        if(is_null($intListaIdBPO)){
            $this->intListaIdBPO = 'NULL';
        }else{
            $this->intListaIdBPO=Helpers::decodeIdBPO(self::$db->real_escape_string($intListaIdBPO),'OPCION_ConsInte__b','DYALOGOCRM_SISTEMA.OPCION');
        }
        return $this;
    }

    // ****** OPCIONLISTA ******************************
    public function getIntOpcionId()
    {
        return $this->intOpcionId;
    }

    public function setIntOpcionId($intOpcionId)
    {
        $this->intOpcionId = Helpers::decodeId(self::$db->real_escape_string($intOpcionId), 'LISOPC_ConsInte__b', 'DYALOGOCRM_SISTEMA.LISOPC');
        return $this;
    }

    public function getStrOpcionNombre()
    {
        return $this->strOpcionNombre;
    }

    public function setStrOpcionNombre($strOpcionNombre)
    {
        $this->strOpcionNombre = $strOpcionNombre;
        return $this;
    }

    public function getIntOpcionPosicion()
    {
        return $this->intOpcionPosicion;
    }

    public function setIntOpcionPosicion($intOpcionPosicion)
    {
        $this->intOpcionPosicion = $intOpcionPosicion;
        return $this;
    }

    public function getIntOpcionMonoef()
    {
        return $this->intOpcionMonoef;
    }

    public function setIntOpcionMonoef($intOpcionMonoef)
    {
        if(is_null($intOpcionMonoef)){
            $this->intOpcionMonoef = 'NULL';
        }else{
            $this->intOpcionMonoef = $intOpcionMonoef;
        }
        return $this;
    }

    public function getIntOpcionClasificacion()
    {
        return $this->intOpcionClasificacion;
    }

    public function setIntOpcionClasificacion($intOpcionClasificacion)
    {
        if(is_null($intOpcionClasificacion)){
            $this->intOpcionClasificacion = 'NULL';
        }else{
            $this->intOpcionClasificacion = $intOpcionClasificacion;
        }
        return $this;
    }

    public function getStrOpcionRpta()
    {
        return $this->strOpcionRpta;
    }

    public function setStrOpcionRpta($strOpcionRpta)
    {
        if(is_null($strOpcionRpta)){
            $this->strOpcionRpta = 'NULL';
        }else{
            $this->strOpcionRpta = "'{$strOpcionRpta}'";
        }
        return $this;
    }

    public function getIntOpcionIdPadre()
    {
        return $this->intOpcionIdPadre;
    }

    public function setIntOpcionIdPadre($intOpcionIdPadre)
    {
        if(is_null($intOpcionIdPadre)){
            $this->intOpcionIdPadre = 'NULL';
        }else{
            $this->intOpcionIdPadre = $intOpcionIdPadre;
        }
        return $this;
    }

    public function getIntOpcionIdLisopcBPO()
    {
        return $this->intOpcionIdLisopcBPO;
    }

    public function setIntOpcionIdLisopcBPO($intOpcionIdLisopcBPO)
    {
        if(is_null($intOpcionIdLisopcBPO)){
            $this->intOpcionIdLisopcBPO = "NULL";
        }else{
            $this->intOpcionIdLisopcBPO = Helpers::decodeIdBPO(self::$db->real_escape_string($intOpcionIdLisopcBPO), 'LISOPC_ConsInte__b', 'DYALOGOCRM_SISTEMA.LISOPC');
        }
        return $this;
    }

    // ****** LISTASAUXILIARES ******************************
    public function getIntAuxIdCampo()
    {
        return $this->intAuxIdCampo;
    }

    public function setIntAuxIdCampo($intAuxIdCampo)
    {
        if(!$intAuxIdCampo){
            $this->intAuxIdCampo = $intAuxIdCampo;
        }else{
            $this->intAuxIdCampo = Helpers::decodeId(self::$db->real_escape_string($intAuxIdCampo), 'PREGUN_ConsInte__b', 'DYALOGOCRM_SISTEMA.PREGUN');
        }
        return $this;
    }

    public function getIntAuxIdGuion()
    {
        return $this->intAuxIdGuion;
    }

    public function setIntAuxIdGuion($intAuxIdGuion)
    {
        $this->intAuxIdGuion = Helpers::decodeId(self::$db->real_escape_string($intAuxIdGuion), 'GUION__ConsInte__b', 'DYALOGOCRM_SISTEMA.GUION_');
        return $this;
    }

    public function getIntAuxCampoGuion()
    {
        return $this->intAuxCampoGuion;
    }

    public function setIntAuxCampoGuion($intAuxCampoGuion)
    {
        $this->intAuxCampoGuion = Helpers::decodeId(self::$db->real_escape_string($intAuxCampoGuion), 'PREGUN_ConsInte__b', 'DYALOGOCRM_SISTEMA.PREGUN');
        return $this;
    }

    // ****** SUBFORMULARIO ******************************
    public function getIntSubFormOperTotal()
    {
        return $this->intSubFormOperTotal;
    }

    public function setIntSubFormOperTotal($intSubFormOperTotal)
    {
        if(is_null($intSubFormOperTotal)){
            $this->intSubFormOperTotal = 'NULL';
        }else{
            $this->intSubFormOperTotal = $intSubFormOperTotal;
        }
        return $this;
    }

    public function getIntSubFormTotalHijo()
    {
        return $this->intSubFormTotalHijo;
    }

    public function setIntSubFormTotalHijo($intSubFormTotalHijo)
    {
        if(is_null($intSubFormTotalHijo)){
            $this->intSubFormTotalHijo = 'NULL';
        }else{
            $this->intSubFormTotalHijo = Helpers::decodeId(self::$db->real_escape_string($intSubFormTotalHijo), 'PREGUN_ConsInte__b', 'DYALOGOCRM_SISTEMA.PREGUN');
        }
        return $this;
    }

    public function getIntSubFormTotalPadre()
    {
        return $this->intSubFormTotalPadre;
    }

    public function setIntSubFormTotalPadre($intSubFormTotalPadre)
    {
        if(is_null($intSubFormTotalPadre)){
            $this->intSubFormTotalPadre = 'NULL';
        }else{
            $this->intSubFormTotalPadre = Helpers::decodeId(self::$db->real_escape_string($intSubFormTotalPadre), 'PREGUN_ConsInte__b', 'DYALOGOCRM_SISTEMA.PREGUN');
        }
        return $this;
    }

    public function getIntSubFormLlaveHijo()
    {
        return $this->intSubFormLlaveHijo;
    }

    public function setIntSubFormLlaveHijo($intSubFormLlaveHijo)
    {
        $this->intSubFormLlaveHijo = Helpers::decodeId(self::$db->real_escape_string($intSubFormLlaveHijo), 'PREGUN_ConsInte__b', 'DYALOGOCRM_SISTEMA.PREGUN');
        return $this;
    }

    public function getIntSubFormLlavePadre()
    {
        return $this->intSubFormLlavePadre;
    }

    public function setIntSubFormLlavePadre($intSubFormLlavePadre)
    {
        $this->intSubFormLlavePadre = Helpers::decodeId(self::$db->real_escape_string($intSubFormLlavePadre), 'PREGUN_ConsInte__b', 'DYALOGOCRM_SISTEMA.PREGUN');
        return $this;
    }

    public function getStrSubFormNombre()
    {
        return $this->strSubFormNombre;
    }

    public function setStrSubFormNombre($strSubFormNombre)
    {
        $this->strSubFormNombre = $strSubFormNombre;
        return $this;
    }

    public function getIntSubFormGuionHijo()
    {
        return $this->intSubFormGuionHijo;
    }

    public function setIntSubFormGuionHijo($intSubFormGuionHijo)
    {
        $this->intSubFormGuionHijo = Helpers::decodeId(self::$db->real_escape_string($intSubFormGuionHijo), 'GUION__ConsInte__b', 'DYALOGOCRM_SISTEMA.GUION_');
        return $this;
    }

    // ****** ETIQUETADO ******************************
    public function getIntEtiquetaPrincipal()
    {
        return $this->intEtiquetaPrincipal;
    }

    public function setIntEtiquetaPrincipal($intEtiquetaPrincipal)
    {
        $this->intEtiquetaPrincipal = Helpers::decodeId(self::$db->real_escape_string($intEtiquetaPrincipal), 'PREGUN_ConsInte__b', 'DYALOGOCRM_SISTEMA.PREGUN');
        return $this;
    }

    public function getIntEtiquetaSecundaria()
    {
        return $this->intEtiquetaSecundaria;
    }

    public function setIntEtiquetaSecundaria($intEtiquetaSecundaria)
    {
        $this->intEtiquetaSecundaria = Helpers::decodeId(self::$db->real_escape_string($intEtiquetaSecundaria), 'PREGUN_ConsInte__b', 'DYALOGOCRM_SISTEMA.PREGUN');
        return $this;
    }

    public function getIntEtiquetaLlave()
    {
        return $this->intEtiquetaLlave;
    }

    public function setIntEtiquetaLlave($intEtiquetaLlave)
    {
        if(!is_null($intEtiquetaLlave)){
            $this->intEtiquetaLlave = Helpers::decodeId(self::$db->real_escape_string($intEtiquetaLlave), 'PREGUN_ConsInte__b', 'DYALOGOCRM_SISTEMA.PREGUN');
        }else{
            $this->intEtiquetaLlave='NULL';
        }
        return $this;
    }

    // ****** SALTOS ******************************
    public function getIntSaltoId()
    {
        return $this->intSaltoId;
    }

    public function setIntSaltoId($intSaltoId)
    {
        $this->intSaltoId = $intSaltoId;
        return $this;
    }

    public function getIntSaltoBySeccion()
    {
        return $this->intSaltoBySeccion;
    }

    public function setIntSaltoBySeccion($intSaltoBySeccion)
    {
        $this->intSaltoBySeccion = $intSaltoBySeccion;
        return $this;
    }

    // ****** ITEMSALTOS ******************************
    public function getIntItemSaltoIdPregunDet()
    {
        return $this->intItemSaltoIdPregunDet;
    }

    public function setIntItemSaltoIdPregunDet($intItemSaltoIdPregunDet)
    {
        $this->intItemSaltoIdPregunDet = $intItemSaltoIdPregunDet;
        return $this;
    }

    public function getIntItemSaltoIdSeccionDet()
    {
        return $this->intItemSaltoIdSeccionDet;
    }

    public function setIntItemSaltoIdSeccionDet($intItemSaltoIdSeccionDet)
    {
        $this->intItemSaltoIdSeccionDet = $intItemSaltoIdSeccionDet;
        return $this;
    }

    public function getIntItemSaltoTipoPregunDet()
    {
        return $this->intItemSaltoTipoPregunDet;
    }

    public function setIntItemSaltoTipoPregunDet($intItemSaltoTipoPregunDet)
    {
        $this->intItemSaltoTipoPregunDet = $intItemSaltoTipoPregunDet;
        return $this;
    }

    public function getIntItemSaltoLimpiarDet()
    {
        return $this->intItemSaltoLimpiarDet;
    }

    public function setIntItemSaltoLimpiarDet($intItemSaltoLimpiarDet)
    {
        $this->intItemSaltoLimpiarDet = $intItemSaltoLimpiarDet;
        return $this;
    }

    public function getStrItemSaltoNombreDet()
    {
        return $this->strItemSaltoNombreDet;
    }

    public function setStrItemSaltoNombreDet($strItemSaltoNombreDet)
    {
        $this->strItemSaltoNombreDet = $strItemSaltoNombreDet;
        return $this;
    }
}