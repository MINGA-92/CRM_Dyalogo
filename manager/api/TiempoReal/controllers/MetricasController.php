<?php


include_once(__DIR__ . "/../models/ESTRAT.php");
include_once(__DIR__ . "/../models/CAMPAN.php");
include_once(__DIR__ . "/../models/ESTPAS.php");
include_once(__DIR__ . "/../models/Gxxxx.php");
include_once(__DIR__ . "/../models/ESTCON.php");
include_once(__DIR__ . "/../models/HUESPED.php");
include_once(__DIR__ . "/../models/TSF.php");
include_once(__DIR__ . "/./Dysingleton.php");



class MetricasController
{

    private $estratOBJ;
    private $estpasOBJ;
    private $campanOBJ;
    private $GxxxxOBJ;
    private $estconOBJ;
    private $huespedOBJ;
    private $dySingleton;
    private $tsfOBJ;

    public function __construct()
    {
        
        $this->estratOBJ = new ESTRAT();
        $this->estpasOBJ = new ESTPAS();
        $this->campanOBJ = new CAMPAN();
        $this->GxxxxOBJ = new Gxxxx();
        $this->estconOBJ = new ESTCON();
        $this->huespedOBJ = new HUESPED();
        $this->tsfOBJ = new TSF();

        include_once(__DIR__ . "/./token.php");
        $this->dySingleton = new Dysingleton($token);
    }

    public function calculateMet()
    {

        date_default_timezone_set("America/Bogota");

        $saltoLinea = (php_sapi_name() == "cli") ? "{\n}" : "</br>";

        $huespedes = $this->huespedOBJ->getAll();

        foreach ($huespedes as $keyHuesped => $valueHuesped) {

            $activityInHuesped = false;
            $arrMetHuesped = [];

            // Ahora obtengo las estrategias de ese huesped
            $estrategies = $this->estratOBJ->getByHusped($valueHuesped->id);

            // Por cada estrategia reviso los pasos que tenga
            if ($estrategies) {
                foreach ($estrategies as $keyEstrat => $valueEstrat) {

                    $estpasArr = $this->estpasOBJ->getAll($valueEstrat->ESTRAT_ConsInte__b);
                    if ($estpasArr) {

                        $activityInEstrat = $this->validateAnyActivity($estpasArr);

                        echo ("La estrategia Id: {$valueEstrat->ESTRAT_ConsInte__b} tiene actividad : ".json_encode($activityInEstrat).$saltoLinea);
                        
                        // VALIDAMOS SI EN LA ESTRATEGIA ALGUNA CAMPAÑA TIENE ALGUNA ACTIVIDAD HOY
                        if ($activityInEstrat) {

                            $activityInHuesped = ($activityInHuesped == false && $activityInEstrat == true) ? true : $activityInHuesped;

                            $arrMetricasEstrat = [];

                            foreach ($estpasArr as $estpasKey => $estpasValue) {

                                echo ("Calculando metricas para el paso Id: {$estpasValue->ESTPAS_ConsInte__b} Tipo: {$estpasValue->ESTPAS_Tipo______b} {$saltoLinea}");

                                // SI EL PASO ES UNA CAMPAÑA ENTONCES CALCULAMOS LAS METRICAS
                                if ($estpasValue->ESTPAS_Tipo______b == 1 || $estpasValue->ESTPAS_Tipo______b == 6) {
                                    $infoCampan = $this->campanOBJ->getInfoById($estpasValue->ESTPAS_ConsInte__CAMPAN_b);
                                    if ($infoCampan) {

                                        $campanActivity = $this->GxxxxOBJ->validateActivityInCampan($infoCampan->CAMPAN_ConsInte__GUION__Pob_b, $infoCampan->CAMPAN_ConsInte__MUESTR_b);

                                        echo ("La campaña id: {$estpasValue->ESTPAS_ConsInte__CAMPAN_b}  tiene actividad? : ".json_encode($campanActivity)." {$saltoLinea}");

                                        if ($campanActivity) {

                                            $metricasConteos = (Object)[];

                                            if($estpasValue->ESTPAS_Tipo______b != 6){
                                                 // SI ES UNA CAMPAÑA ENTRANTE ENTONCES PRIMERO BUSCAMOS LAS METRICAS DESDE EL REPORTE ACD
                                                 $metricasConteos = $this->replaceACD($metricasConteos, $infoCampan);
                                            }

                                            // VALIDAMOS SI SE LLENO LA INFORMACION SI NO ENTONCES VAMOS A LA BD Y HACEMOS CONTEOS COMPLETOS
                                            if(isset($metricasConteos->{"#Recibidas"}) && isset($metricasConteos->{"#Contestadas"} )){
                                                
                                                $metricasConteos = $this->GxxxxOBJ->calculateConteos($infoCampan, $estpasValue->ESTPAS_Tipo______b, $estpasValue->ESTPAS_ConsInte__b, $estpasValue->ESTPAS_ConsInte__b, false);
                                            }else{
                                                $metricasConteos = $this->GxxxxOBJ->calculateConteos($infoCampan, $estpasValue->ESTPAS_Tipo______b, $estpasValue->ESTPAS_ConsInte__b, $estpasValue->ESTPAS_ConsInte__b, true);
                                            }


                                            if($estpasValue->ESTPAS_Tipo______b != 6){
                                                // SI ES UNA CAMPAÑA ENTRNATE CALCULAMOS TSF
                                                $metricasConteos = $this->calcTSF($metricasConteos, $estpasValue->ESTPAS_Tipo______b, $infoCampan);
                                                $metricasConteos->{"#En cola"} = 0;

                                            }

                                            $metricasConteos = $this->calcPercentages($metricasConteos, $estpasValue->ESTPAS_Tipo______b);
                                            
                                            array_push($arrMetricasEstrat, (object) ["objEstpas_t" => $estpasValue, "objCampan_t" => $infoCampan, "lstDefinicionMetricas_t" => [(object) ["values" => $metricasConteos, "CampanId" => $estpasValue->ESTPAS_ConsInte__b]]]);
                                        }
                                    }
                                } else {
                                    // SI NO ENTONCES BUSCAMOS LA CAMPAÑA QUE CORRESPONDE AL PASO

                                    $campanPaso = $this->estconOBJ->searchCampan($estpasValue->ESTPAS_ConsInte__b);
                                    $metPaso = [];

                                    foreach ($campanPaso as $keyCampanPaso => $valueCampanPaso) {
                                        $infoCampan = $this->campanOBJ->getInfoByIdEstPas($valueCampanPaso);
                                        if ($infoCampan) {
                                            $onlyMet = $this->GxxxxOBJ->calculateConteos($infoCampan, $estpasValue->ESTPAS_Tipo______b, $valueCampanPaso, $estpasValue->ESTPAS_ConsInte__b, true);
                                            $onlyMet = $this->calcPercentages($onlyMet, $estpasValue->ESTPAS_Tipo______b);
                                            $onlyMet = $this->calcTSF($onlyMet, $estpasValue->ESTPAS_Tipo______b, $infoCampan);
                                            $onlyMet->{"#En cola"} = 0;

                                            array_push($metPaso, (object) ["values" => $onlyMet, "CampanId" => $valueCampanPaso]);
                                        }
                                    }

                                    array_push($arrMetricasEstrat, (object) ["objEstpas_t" => $estpasValue, "lstDefinicionMetricas_t" => $metPaso]);
                                }
                            }

                            array_push($arrMetHuesped, ["lstPasos_t" => $arrMetricasEstrat, "objEstrat_t" => $valueEstrat]);
                        }
                    }
                }
            }



            if ($activityInHuesped) {
                $metricas = ["lstEstrat_t" => $arrMetHuesped, "objHuesped_t" => $valueHuesped];

                // POR CADA HUESPED SE GUARDA UN DATO DIFERENTE
                $this->dySingleton->saveInfo($metricas, null, $valueHuesped->id );
            }
        }
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


    /**
     *BGCR - ESTA FUNCIONA ADICIONA LOS PORCENTAJES AL CONJUNTO DE METRIAS QUE LE LLEGUE
    *@param metricasConteos - Objeto de las metricas calculadas anteriormente
    *@param typeChannel - Tipo de canal del paso
    *@return object - Objeto con las metrias adicionadas
    */

    private function calcPercentages($metricasConteos, $typeChannel)
    {

        $percContactados = 0;
        $percEfectivos = 0;

        // SI EL PASO ES UNA CAMPAÑA SALIENTE EL NOMBRE DE LAS METRICAS CAMBIA
        if ($typeChannel == 6) {

            if ($metricasConteos->{"#Gestiones"} > 0) {
                $percContactados = ($metricasConteos->{"#Contactados"} / $metricasConteos->{"#Gestiones"}) * 100;
                $percEfectivos = ($metricasConteos->{"#Efectivos"} / $metricasConteos->{"#Gestiones"}) * 100;
            }

            $metricasConteos->{"%Contactados"} = $percContactados;
            $metricasConteos->{"%Efectivos"} = $percEfectivos;
        } else {

            if ($metricasConteos->{"#Recibidas"} > 0) {
                $percContactados = ($metricasConteos->{"#Contestadas"} / $metricasConteos->{"#Recibidas"}) * 100;
                $percEfectivos = ($metricasConteos->{"#Efectivos"} / $metricasConteos->{"#Recibidas"}) * 100;
            }

            $metricasConteos->{"%Contestadas"} = $percContactados;
            $metricasConteos->{"%Efectivos"} = $percEfectivos;
        }


        return $metricasConteos;
    }



    
    /**
     *BGCR -  ESTA FUNCION ADICIONA LAS COLAS AL CONJUNTO DE METRICAS QUE LE LLEGUE
    *@param metricasConteos - Objeto de las metricas calculadas anteriormente
    *@param tipoPaso - Tipo de canal del paso
    *@param infoCampan - Objeto con informacion de la campaña al que llega el paso
    *@return object - Objeto con las metrias adicionadas
    */


    private function calcTSF($metricasConteos, $tipoPaso, $infoCampan)
    {

        $tsfCon = 0;

        // SE VALIDA EL TIPO DE PASO PARA CALCULAR U OBTENER LAS COLAS
        switch ($tipoPaso){
            case 1: case 6:
                $tsfCon = $this->tsfOBJ->getTsfTel($infoCampan->CAMPAN_IdCamCbx__b);
                break;

            case 14: case 15: case 16: case 20:
                $tsfCon = $this->tsfOBJ->getTsfChat($infoCampan->CAMPAN_IdCamCbx__b,  $tipoPaso, $infoCampan->CAMPAN_TiempoNivelServ_b);
                break;
            case 17: case 19:
                $tsfCon = $this->tsfOBJ->getTsfMail($infoCampan->CAMPAN_IdCamCbx__b, $tipoPaso, $infoCampan->CAMPAN_TiempoNivelServ_b );
                break;
        }


        $metricasConteos->{"%TSF"} = $tsfCon;

        return $metricasConteos;
    }



       
    /**
     *BGCR -  ESTA FUNCION REMPLAZA LA INFO QUE LLEGA DE LA CAMPAÑA ENTRANTE POR LOS QUE SE TIENE EN EL ACD
    *@param metricasConteos - Objeto de las metricas calculadas anteriormente
    *@param infoCampan - Objeto con informacion de la campaña al que llega el paso
    *@return object - Objeto con las metrias adicionadas
    */


    private function replaceACD($metricasConteos, $infoCampan) : object
    {

        $infoACD = $this->campanOBJ->getInfoACD($infoCampan->CAMPAN_IdCamCbx__b);

        if($infoACD){
            $metricasConteos->{"#Recibidas"} = $infoACD->recibidas;
            $metricasConteos->{"#Contestadas"} = $infoACD->contestadas;
        }

        return $metricasConteos;
    }


    
}
