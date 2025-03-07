<?php

class ACDViews extends Views
{

    public function __construct()
    {
        parent::__construct();
    }


    // Se remplaza el metodo generate views
    public function generateViews(): array
    {
        try {
            (array)$response = array();
            $response['estado'] = 'error';

            $acdViews = [
                (object)["idType" => -20, "typeName" => "AcdDia"],
                (object)["idType" => -21, "typeName" => "AcdHora"]
            ];

            $viewsResults = array();
            foreach ($acdViews as  $acdValue) {
                $viewsResults["id" . $acdValue->typeName] = $this->createView($acdValue);
            }


            if (count($viewsResults) > 0) {
                $response['estado'] = 'ok';
                $response['mensaje'] = $viewsResults;
            }


            return $response;
        } catch (\Throwable $th) {
            $this->error($th->getMessage());
        }
    }


    // Este metodo me llama la vista que necesito generar y la guarda en la DB
    private function createView(object $acdValue)
    {
        try {
            $viewObj = $this->{"getView" . $acdValue->typeName}();

            if ($viewObj->queryView != "") {
                $sql = self::$db->query($viewObj->queryView);
                if ($sql) {
                    return $this->saveViewGenerated($viewObj->nameView, $acdValue->idType, $this->getInfoPaso()->ESTPAS_ConsInte__CAMPAN_b, $this->getIdPaso());
                } else {
                    $this->error(self::$db->error, $viewObj->queryView);
                }
            }
        } catch (\Throwable $th) {
            $this->error($th->__toString());
        }
    }


    private function getViewAcdDia(): object
    {

        $nameView = "";
        $view = "";

        if ($this->getIdPaso()) {
            if ($objPaso = $this->getInfoPaso()) {
                $this->setIdEstrat($objPaso->ESTPAS_ConsInte__ESTRAT_b);
                $objEstrat = $this->getInfoEstrat();

                // Si no hay una campaña configurada entonces no se crea la vista
                if ($objPaso->ESTPAS_ConsInte__CAMPAN_b != null && $objEstrat) {
                    $nameView = "DY_V_H{$this->getIdHuesped()}_P{$this->getIdPaso()}_ACD_DIA_{$this->aliasColumna($objPaso->ESTPAS_Comentari_b)}";

                    $view = "CREATE OR REPLACE VIEW `DYALOGOCRM_WEB`.`{$nameView}` AS
                        SELECT 
                            `dyalogo_telefonia`.`dy_informacion_actual_campanas`.`fecha` AS `Fecha`,
                            '{$objEstrat->ESTRAT_Nombre____b}' AS `Estrategia`,
                            `dyalogo_telefonia`.`dy_informacion_actual_campanas`.`segundos_tsf` AS `TSF_Tiempo`,
                            `dyalogo_telefonia`.`dy_informacion_actual_campanas`.`meta_tsf` AS `TSF_porcentaje`,
                            `dyalogo_telefonia`.`dy_informacion_actual_campanas`.`recibidas` AS `Ofrecidas`,
                            `dyalogo_telefonia`.`dy_informacion_actual_campanas`.`contestadas` AS `Contestadas`,
                            `dyalogo_telefonia`.`dy_informacion_actual_campanas`.`contestadas_ns` AS `Cont_antes_tsf`,
                            `dyalogo_telefonia`.`dy_informacion_actual_campanas`.`contestadas_despues_s_tsf` AS `Cont_despues_tsf`,
                            `dyalogo_telefonia`.`dy_informacion_actual_campanas`.`abandonadas_despues_s_tsf` AS `Aban_despues_tsf`,
                            REPLACE(ROUND(((`dyalogo_telefonia`.`dy_informacion_actual_campanas`.`contestadas` * 100) / `dyalogo_telefonia`.`dy_informacion_actual_campanas`.`recibidas`),
                                        2),
                                '.00',
                                '') AS `Cont_porcentaje`,
                            `dyalogo_telefonia`.`dy_informacion_actual_campanas`.`nivel_servicio` AS `TSF`,
                            `dyalogo_telefonia`.`dy_informacion_actual_campanas`.`nivel_servicio_contestadas` AS `TSF_Cont_antes_tsf`,
                            `dyalogo_telefonia`.`dy_informacion_actual_campanas`.`porcentaje_participacion` AS `TSF_Cont_despues_tsf`,
                            SEC_TO_TIME(FLOOR(`dyalogo_telefonia`.`dy_informacion_actual_campanas`.`espera_promedio`)) AS `ASA`,
                            `dyalogo_telefonia`.`dy_informacion_actual_campanas`.`espera_promedio` AS `ASA_SEC`,
                            SEC_TO_TIME(FLOOR(`dyalogo_telefonia`.`dy_informacion_actual_campanas`.`espera_minimo`)) AS `ASAMin`,
                            `dyalogo_telefonia`.`dy_informacion_actual_campanas`.`espera_minimo` AS `ASAMin_SEC`,
                            SEC_TO_TIME(FLOOR(`dyalogo_telefonia`.`dy_informacion_actual_campanas`.`espera_maximo`)) AS `ASAMax`,
                            `dyalogo_telefonia`.`dy_informacion_actual_campanas`.`espera_maximo` AS `ASAMax_SEC`,
                            SEC_TO_TIME(FLOOR(`dyalogo_telefonia`.`dy_informacion_actual_campanas`.`espera_total`)) AS `TSA`,
                            `dyalogo_telefonia`.`dy_informacion_actual_campanas`.`espera_total` AS `TSA_SEC`,
                            SEC_TO_TIME(`dyalogo_telefonia`.`dy_informacion_actual_campanas`.`aht`) AS `AHT`,
                            `dyalogo_telefonia`.`dy_informacion_actual_campanas`.`aht` AS `AHT_SEC`,
                            SEC_TO_TIME(`dyalogo_telefonia`.`dy_informacion_actual_campanas`.`tiempo_conversacion_total`) AS `THT`,
                            `dyalogo_telefonia`.`dy_informacion_actual_campanas`.`tiempo_conversacion_total` AS `THT_SEC`,
                            `dyalogo_telefonia`.`dy_informacion_actual_campanas`.`abandonadas_total` AS `Aban`,
                            `dyalogo_telefonia`.`dy_informacion_actual_campanas`.`abandonadas` AS `Aban_antes_tsf`,
                            ((`dyalogo_telefonia`.`dy_informacion_actual_campanas`.`abandonadas_total` / `dyalogo_telefonia`.`dy_informacion_actual_campanas`.`recibidas`) * 100) AS `Aban_porcentaje`,
                            `dyalogo_telefonia`.`dy_informacion_actual_campanas`.`nivel_abandono_umbral` AS `Aban_umbral_tsf`,
                            SEC_TO_TIME(`dyalogo_telefonia`.`dy_informacion_actual_campanas`.`espera_promedio_abandono`) AS `Aban_espera`,
                            `dyalogo_telefonia`.`dy_informacion_actual_campanas`.`espera_promedio_abandono` AS `Aban_espera_SEC`,
                            SEC_TO_TIME(`dyalogo_telefonia`.`dy_informacion_actual_campanas`.`espera_total_abandono`) AS `Aban_espera_total`,
                            `dyalogo_telefonia`.`dy_informacion_actual_campanas`.`espera_total_abandono` AS `Aban_espera_total_SEC`,
                            SEC_TO_TIME(`dyalogo_telefonia`.`dy_informacion_actual_campanas`.`espera_minima_abandono`) AS `Aban_espera_min`,
                            `dyalogo_telefonia`.`dy_informacion_actual_campanas`.`espera_minima_abandono` AS `Aban_espera_min_SEC`,
                            `dyalogo_telefonia`.`dy_informacion_actual_campanas`.`espera_maxima_abandono` AS `Aban_espera_max_SEC`,
                            SEC_TO_TIME(`dyalogo_telefonia`.`dy_informacion_actual_campanas`.`espera_maxima_abandono`) AS `Aban_espera_max`,
                            `dyalogo_telefonia`.`dy_informacion_actual_campanas`.`transferidas` AS `transfer`
                        FROM
                            `dyalogo_telefonia`.`dy_informacion_actual_campanas`
                        WHERE
                            (`dyalogo_telefonia`.`dy_informacion_actual_campanas`.`id_campana` = {$objPaso->ESTPAS_CampanACD_b})
                        ORDER BY `dyalogo_telefonia`.`dy_informacion_actual_campanas`.`fecha` DESC";
                } else {
                    $this->error("Campaña no configurada");
                }
            } else {
                $this->error("No se encuentra el paso");
            }
        }

        return (object) ["queryView" =>  $view, "nameView" => $nameView];
    }



    private function getViewAcdHora(): object
    {

        $nameView = "";
        $view = "";

        if ($this->getIdPaso()) {
            if ($objPaso = $this->getInfoPaso()) {
                $this->setIdEstrat($objPaso->ESTPAS_ConsInte__ESTRAT_b);
                $objEstrat = $this->getInfoEstrat();

                // Si no hay una campaña configurada entonces no se crea la vista
                if ($objPaso->ESTPAS_ConsInte__CAMPAN_b != null && $objEstrat) {
                    $nameView = "DY_V_H{$this->getIdHuesped()}_P{$this->getIdPaso()}_ACD_HORA_{$this->aliasColumna($objPaso->ESTPAS_Comentari_b)}";

                    $view = "CREATE OR REPLACE VIEW `DYALOGOCRM_WEB`.`{$nameView}` AS
                        SELECT 
                            `dyalogo_telefonia`.`dy_informacion_intervalos_h`.`fecha` AS `Fecha`,
                            `dyalogo_telefonia`.`dy_informacion_intervalos_h`.`intervalo_traducido` AS `Intervalo`,
                            'NEW_WEBFORM' AS `Estrategia`,
                            `dyalogo_telefonia`.`dy_informacion_intervalos_h`.`segundos_tsf` AS `TSF_Tiempo`,
                            `dyalogo_telefonia`.`dy_informacion_intervalos_h`.`meta_tsf` AS `TSF_porcentaje`,
                            `dyalogo_telefonia`.`dy_informacion_intervalos_h`.`recibidas` AS `Ofrecidas`,
                            `dyalogo_telefonia`.`dy_informacion_intervalos_h`.`contestadas` AS `Contestadas`,
                            `dyalogo_telefonia`.`dy_informacion_intervalos_h`.`contestadas_ns` AS `Cont_antes_tsf`,
                            `dyalogo_telefonia`.`dy_informacion_intervalos_h`.`contestadas_despues_s_tsf` AS `Cont_despues_tsf`,
                            `dyalogo_telefonia`.`dy_informacion_intervalos_h`.`abandonadas_despues_s_tsf` AS `Aban_despues_tsf`,
                            REPLACE(ROUND(((`dyalogo_telefonia`.`dy_informacion_intervalos_h`.`contestadas` * 100) / `dyalogo_telefonia`.`dy_informacion_intervalos_h`.`recibidas`),
                                        2),
                                '.00',
                                '') AS `Cont_porcentaje`,
                            `dyalogo_telefonia`.`dy_informacion_intervalos_h`.`nivel_servicio` AS `TSF`,
                            `dyalogo_telefonia`.`dy_informacion_intervalos_h`.`nivel_servicio_contestadas` AS `TSF_Cont_antes_tsf`,
                            `dyalogo_telefonia`.`dy_informacion_intervalos_h`.`porcentaje_participacion` AS `TSF_Cont_despues_tsf`,
                            SEC_TO_TIME(FLOOR(`dyalogo_telefonia`.`dy_informacion_intervalos_h`.`espera_promedio`)) AS `ASA`,
                            `dyalogo_telefonia`.`dy_informacion_intervalos_h`.`espera_promedio` AS `ASA_SEC`,
                            SEC_TO_TIME(FLOOR(`dyalogo_telefonia`.`dy_informacion_intervalos_h`.`espera_minimo`)) AS `ASAMin`,
                            `dyalogo_telefonia`.`dy_informacion_intervalos_h`.`espera_minimo` AS `ASAMin_SEC`,
                            SEC_TO_TIME(FLOOR(`dyalogo_telefonia`.`dy_informacion_intervalos_h`.`espera_maximo`)) AS `ASAMax`,
                            `dyalogo_telefonia`.`dy_informacion_intervalos_h`.`espera_maximo` AS `ASAMax_SEC`,
                            SEC_TO_TIME(FLOOR(`dyalogo_telefonia`.`dy_informacion_intervalos_h`.`espera_total`)) AS `TSA`,
                            `dyalogo_telefonia`.`dy_informacion_intervalos_h`.`espera_total` AS `TSA_SEC`,
                            SEC_TO_TIME(`dyalogo_telefonia`.`dy_informacion_intervalos_h`.`aht`) AS `AHT`,
                            `dyalogo_telefonia`.`dy_informacion_intervalos_h`.`aht` AS `AHT_SEC`,
                            SEC_TO_TIME(`dyalogo_telefonia`.`dy_informacion_intervalos_h`.`tiempo_conversacion_total`) AS `THT`,
                            `dyalogo_telefonia`.`dy_informacion_intervalos_h`.`tiempo_conversacion_total` AS `THT_SEC`,
                            `dyalogo_telefonia`.`dy_informacion_intervalos_h`.`abandonadas_total` AS `Aban`,
                            `dyalogo_telefonia`.`dy_informacion_intervalos_h`.`abandonadas` AS `Aban_antes_tsf`,
                            ((`dyalogo_telefonia`.`dy_informacion_intervalos_h`.`abandonadas_total` / `dyalogo_telefonia`.`dy_informacion_intervalos_h`.`recibidas`) * 100) AS `Aban_porcentaje`,
                            `dyalogo_telefonia`.`dy_informacion_intervalos_h`.`nivel_abandono_umbral` AS `Aban_umbral_tsf`,
                            SEC_TO_TIME(`dyalogo_telefonia`.`dy_informacion_intervalos_h`.`espera_promedio_abandono`) AS `Aban_espera`,
                            `dyalogo_telefonia`.`dy_informacion_intervalos_h`.`espera_promedio_abandono` AS `Aban_espera_SEC`,
                            SEC_TO_TIME(`dyalogo_telefonia`.`dy_informacion_intervalos_h`.`espera_total_abandono`) AS `Aban_espera_total`,
                            `dyalogo_telefonia`.`dy_informacion_intervalos_h`.`espera_total_abandono` AS `Aban_espera_total_SEC`,
                            SEC_TO_TIME(`dyalogo_telefonia`.`dy_informacion_intervalos_h`.`espera_minima_abandono`) AS `Aban_espera_min`,
                            `dyalogo_telefonia`.`dy_informacion_intervalos_h`.`espera_minima_abandono` AS `Aban_espera_min_SEC`,
                            `dyalogo_telefonia`.`dy_informacion_intervalos_h`.`espera_maxima_abandono` AS `Aban_espera_max_SEC`,
                            SEC_TO_TIME(`dyalogo_telefonia`.`dy_informacion_intervalos_h`.`espera_maxima_abandono`) AS `Aban_espera_max`
                        FROM
                            `dyalogo_telefonia`.`dy_informacion_intervalos_h`
                        WHERE
                            (`dyalogo_telefonia`.`dy_informacion_intervalos_h`.`id_campana` = {$objPaso->ESTPAS_CampanACD_b})
                        ORDER BY `dyalogo_telefonia`.`dy_informacion_intervalos_h`.`fecha` DESC";
                } else {
                    $this->error("Campaña no configurada");
                }
            } else {
                $this->error("No se encuentra el paso");
            }
        }
        
        return (object) ["queryView" =>  $view, "nameView" => $nameView];
    }
}
