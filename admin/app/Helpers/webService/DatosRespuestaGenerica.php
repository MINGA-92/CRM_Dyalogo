<?php

namespace App\Helpers\webService;

class DatosRespuestaGenerica{

    private $strEstado_t;
    private $strMensaje_t;
    private $objSerializar_t;

    public function __construct($strEstado_t = null, $strMensaje_t = null, $objSerializar_t = null){
        $this->strEstado_t = $strEstado_t;
        $this->strMensaje_t = $strMensaje_t;
        $this->objSerializar_t = $objSerializar_t;
    }

    public function getStrEstado_t(){
        return $this->strEstado_t;
    }

    public function setStrEstado_t($strEstado_t){
        $this->strEstado_t = $strEstado_t;
    }

    public function getStrMensaje_t(){
        return $this->strMensaje_t;
    }

    public function setStrMensaje_t($strMensaje_t){
        $this->strMensaje_t = $strMensaje_t;
    }

    public function getObjSerializar_t(){
        return $this->objSerializar_t;
    }

    public function setObjSerializar_t($objSerializar_t){
        $this->objSerializar_t = $objSerializar_t;
    }
}