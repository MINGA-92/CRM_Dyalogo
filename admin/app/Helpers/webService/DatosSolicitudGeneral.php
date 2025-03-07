<?php 

namespace App\Helpers\webService;

abstract class DatosSolicitudGeneral{

    private $strUsuario_t;
    private $strToken_t;

    public function getStrUsuario_t(){
        return $this->strUsuario_t;
    }

    public function setStrUsuario_t($strUsuario_t){
        $this->strUsuario_t = $strUsuario_t;
    }

    public function getStrToken_t(){
        return $this->strToken_t;
    }

    public function setStrToken_t($strToken_t){
        $this->strToken_t = $strToken_t;
    }

}
