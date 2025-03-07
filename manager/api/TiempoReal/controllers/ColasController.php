<?php


include_once(__DIR__ . "/../models/CAMPAN.php");
include_once(__DIR__ . "/../models/Gxxxx.php");
include_once(__DIR__ . "/../models/ESTCON.php");
include_once(__DIR__ . "/../models/Colas.php");
include_once(__DIR__ . "/../models/TSF.php");
include_once(__DIR__ . "/../models/ActividadActual.php");
include_once(__DIR__ . "/./Dysingleton.php");



class ColasController
{

    private $campanOBJ;
    private $GxxxxOBJ;
    private $dySingleton;
    private $colaOBJ;
    private $ActividadActualOBJ;

    public function __construct()
    {
        $this->campanOBJ = new CAMPAN();
        $this->GxxxxOBJ = new Gxxxx();
        $this->colaOBJ = new Colas();
        $this->ActividadActualOBJ = new ActividadActual();
        $this->dySingleton = new Dysingleton();
    }

    public function calculateColas()
    {

        $arrColas = [];

        // Obtenemos las campañas que tiene actividad
        $arrCampan = $this->ActividadActualOBJ->getActualActivityCampan();

        if (count($arrCampan) > 0) {
            foreach ($arrCampan as $campan) {
                echo ("Obteniendo colas de la campaña {$campan->id_campana_crm} \n");
                $metricasColas = $this->colaOBJ->getAllColas($campan);
                array_push($arrColas, (object) ["CAMPAN_ConsInte__b" => $campan->id_campana_crm, "lstDefinicionColas_t" => $metricasColas]);
            }
        }

        $this->dySingleton->saveInfo($arrColas, "colas", null);
    }


    /**
     *BGCR - ESTA FUNCION VALIDA SI LA ESTRATEGIA TIENE ALGUN ACTIVIDAD EN ALGUNA CAMPAÑA
     *@param arrEstpas - Array de todos los pasos de la estrategia
     *@return bool - respuesda si tiene o no actividad
     */

    private function validateAnyActivity($arrEstpas)
    {
        foreach ($arrEstpas as $estpasValue) {
            if ($estpasValue->ESTPAS_Tipo______b == 1 || $estpasValue->ESTPAS_Tipo______b == 6) {
                $infoCampan = $this->campanOBJ->getInfoById($estpasValue->ESTPAS_ConsInte__CAMPAN_b);
                if ($infoCampan) {
                    if ($this->GxxxxOBJ->validateActivityInCampan($infoCampan->CAMPAN_ConsInte__GUION__Pob_b, $infoCampan->CAMPAN_ConsInte__MUESTR_b)) {
                        return true;
                    }
                }
            }
        }

        return false;
    }
}
