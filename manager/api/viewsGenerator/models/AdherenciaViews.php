<?php

class AdherenciaViews extends Views
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

            $adherenciaViews = [
                (object)["idType" => -1, "typeName" => "LlegadasTardeHoy"],
                (object)["idType" => -2, "typeName" => "SalidasTempranoHoy"],
                (object)["idType" => -3, "typeName" => "PausasConHorarioMuyLargas"],
                (object)["idType" => -4, "typeName" => "PausasConHorarioIncumplidas"],
                (object)["idType" => -5, "typeName" => "PausasSinHorarioMuyLargas"],
                (object)["idType" => -6, "typeName" => "PausasSinHorarioMuchasVeces"],

            ];

            $viewsResults = array();
            foreach ($adherenciaViews as  $adhValue) {
                $viewsResults["id" . $adhValue->typeName] = $this->createView($adhValue);
            }


            if(count($viewsResults) > 0){
                $response['estado'] = 'ok';
                $response['mensaje'] = $viewsResults;
            }


            return $response;
        } catch (\Throwable $th) {
            $this->error($th->getMessage());
        }
    }


    // Este metodo me llama la vista que necesito generar y la guarda en la DB
    private function createView(object $adherenciaType)
    {
        try {
            $viewObj = $this->{"getView" . $adherenciaType->typeName}();

            $sql = self::$db->query($viewObj->queryView);
            if ($sql) {
                return $this->saveViewGenerated( $viewObj->nameView, $adherenciaType->idType, -100);
            } else {
                $this->error(self::$db->error, $viewObj->queryView);
            }
        } catch (\Throwable $th) {
            $this->error($th->__toString());
        }
    }


    private function getViewSalidasTempranoHoy(): object
    {

        $nameView = "DY_V_H{$this->getIdHuesped()}_SalidasTempranoHoy";
        $view = "CREATE OR REPLACE VIEW `DYALOGOCRM_WEB`.`{$nameView}` AS
        SELECT 
            `qryUSUARI_usuarios_agentes`.`USUARI_ConsInte__b` AS `Id`,
            `qryUSUARI_usuarios_agentes`.`IdAgente` AS `IdAInterno`,
            `qryUSUARI_usuarios_agentes`.`USUARI_Nombre____b` AS `Nombre`,
            `qryUSUARI_usuarios_agentes`.`USUARI_Correo___b` AS `Correo`,
            DATE_FORMAT(TIMEDIFF(`qryUSUARI_usuarios_agentes`.`HoraFinalDefinida`,
                            `qrySesionesDelDia`.`HoraFin`),
                    '%H:%i:%S') AS `TiempoFaltante`,
            `qryUSUARI_usuarios_agentes`.`HoraFinalDefinida` AS `HoraFinalDefinida`,
            `qrySesionesDelDia`.`HoraFin` AS `HoraFinalReal`
        FROM
            (`DYALOGOCRM_SISTEMA`.`qryUSUARI_usuarios_agentes`
            JOIN `DYALOGOCRM_SISTEMA`.`qrySesionesDelDia` ON ((`qryUSUARI_usuarios_agentes`.`IdAgente` = `qrySesionesDelDia`.`agente_id`)))
        WHERE
            ((`qrySesionesDelDia`.`HoraFin` < `qryUSUARI_usuarios_agentes`.`HoraFinalDefinida`)
                AND (`qryUSUARI_usuarios_agentes`.`USUARI_ConsInte__PROYEC_b` = {$this->getIdHuesped()}))
        ORDER BY `qryUSUARI_usuarios_agentes`.`USUARI_Nombre____b`";

        return (object) ["queryView" =>  $view, "nameView" => $nameView];
    }


    private function getViewPausasSinHorarioMuyLargas(): object
    {
        $nameView = "DY_V_H{$this->getIdHuesped()}_PausasSinHorarioMuyLargas";
        $view = "CREATE OR REPLACE VIEW `DYALOGOCRM_WEB`.`{$nameView}` AS
            SELECT 
                `qryPausasProgramadasDelDia`.`USUARI_ConsInte__b` AS `Id`,
                `dy_v_historico_descansos`.`agente_id` AS `IdAInterno`,
                `qryPausasProgramadasDelDia`.`USUARI_Nombre____b` AS `Nombre`,
                `qryPausasProgramadasDelDia`.`USUARI_Correo___b` AS `Correo`,
                `dy_v_historico_descansos`.`tipo_descanso_nombre` AS `Pausa`,
                TIMEDIFF(SEC_TO_TIME(SUM(TIME_TO_SEC(TIMEDIFF(`dy_v_historico_descansos`.`fecha_hora_fin`,
                                                `dy_v_historico_descansos`.`fecha_hora_inicio`)))),
                        `qryPausasProgramadasDelDia`.`DuracionMaxima`) AS `Exceso`,
                `qryPausasProgramadasDelDia`.`DuracionMaxima` AS `DuracionMaxima`,
                SEC_TO_TIME(SUM(TIME_TO_SEC(TIMEDIFF(`dy_v_historico_descansos`.`fecha_hora_fin`,
                                        `dy_v_historico_descansos`.`fecha_hora_inicio`)))) AS `DuracionReal`,
                COUNT(`qryPausasProgramadasDelDia`.`USUARI_ConsInte__b`) AS `CantidadReal`
            FROM
                (`DYALOGOCRM_SISTEMA`.`qryPausasProgramadasDelDia`
                LEFT JOIN `dyalogo_telefonia`.`dy_v_historico_descansos` ON (((`qryPausasProgramadasDelDia`.`IdAgente` = `dy_v_historico_descansos`.`agente_id`)
                    AND (`qryPausasProgramadasDelDia`.`USUPAU_PausasId_b` = `dy_v_historico_descansos`.`tipo_descanso_id`))))
            WHERE
                ((`dy_v_historico_descansos`.`fecha_hora_inicio` >= CURDATE())
                    AND (`qryPausasProgramadasDelDia`.`USUARI_ConsInte__PROYEC_b` = {$this->getIdHuesped()})
                    AND (`qryPausasProgramadasDelDia`.`USUPAU_Tipo_b` = 0))
            GROUP BY `qryPausasProgramadasDelDia`.`USUARI_ConsInte__b`
            HAVING (TIME_TO_SEC(`qryPausasProgramadasDelDia`.`DuracionMaxima`) < SUM(TIME_TO_SEC(TIMEDIFF(`dy_v_historico_descansos`.`fecha_hora_fin`,
                            `dy_v_historico_descansos`.`fecha_hora_inicio`))))
            ORDER BY `qryPausasProgramadasDelDia`.`USUARI_Nombre____b`";

        return (object)["queryView" =>  $view, "nameView" => $nameView];
    }


    private function getViewPausasSinHorarioMuchasVeces(): object
    {
        $nameView = "DY_V_H{$this->getIdHuesped()}_PausasSinHorarioMuchasVeces";
        $view = "CREATE OR REPLACE VIEW `DYALOGOCRM_WEB`.`{$nameView}` AS
                SELECT 
                    `qryPausasProgramadasDelDia`.`USUARI_ConsInte__b` AS `Id`,
                    `dy_v_historico_descansos`.`agente_id` AS `IdAInterno`,
                    `qryPausasProgramadasDelDia`.`USUARI_Nombre____b` AS `Nombre`,
                    `qryPausasProgramadasDelDia`.`USUARI_Correo___b` AS `Correo`,
                    `dy_v_historico_descansos`.`tipo_descanso_nombre` AS `Pausa`,
                    (COUNT(`qryPausasProgramadasDelDia`.`USUARI_ConsInte__b`) - `qryPausasProgramadasDelDia`.`CantidadMaxima`) AS `VecesDeMas`,
                    COUNT(`qryPausasProgramadasDelDia`.`USUARI_ConsInte__b`) AS `CantidadReal`,
                    `qryPausasProgramadasDelDia`.`CantidadMaxima` AS `CantidadMaxima`,
                    SEC_TO_TIME(SUM(TIME_TO_SEC(TIMEDIFF(`dy_v_historico_descansos`.`fecha_hora_fin`,
                                            `dy_v_historico_descansos`.`fecha_hora_inicio`)))) AS `DuracionReal`,
                    `qryPausasProgramadasDelDia`.`DuracionMaxima` AS `DuracionMaxima`
                FROM
                    (`DYALOGOCRM_SISTEMA`.`qryPausasProgramadasDelDia`
                    LEFT JOIN `dyalogo_telefonia`.`dy_v_historico_descansos` ON (((`qryPausasProgramadasDelDia`.`IdAgente` = `dy_v_historico_descansos`.`agente_id`)
                        AND (`qryPausasProgramadasDelDia`.`USUPAU_PausasId_b` = `dy_v_historico_descansos`.`tipo_descanso_id`))))
                WHERE
                    ((`dy_v_historico_descansos`.`fecha_hora_inicio` >= CURDATE())
                        AND (`qryPausasProgramadasDelDia`.`USUPAU_Tipo_b` = 0)
                        AND (`qryPausasProgramadasDelDia`.`USUARI_ConsInte__PROYEC_b` = {$this->getIdHuesped()}))
                GROUP BY `qryPausasProgramadasDelDia`.`USUARI_ConsInte__b`
                HAVING (`qryPausasProgramadasDelDia`.`CantidadMaxima` < COUNT(`qryPausasProgramadasDelDia`.`USUARI_ConsInte__b`))
                ORDER BY `qryPausasProgramadasDelDia`.`USUARI_Nombre____b`";

        return (object)["queryView" =>  $view, "nameView" => $nameView];
    }

    private function getViewPausasConHorarioMuyLargas(): object
    {

        $nameView = "DY_V_H{$this->getIdHuesped()}_PausasConHorarioMuyLargas";
        $view = "CREATE OR REPLACE VIEW `DYALOGOCRM_WEB`.`{$nameView}` AS
                SELECT 
                    `qryPausasProgramadasDelDia`.`USUARI_ConsInte__b` AS `Id`,
                    `dy_v_historico_descansos`.`agente_id` AS `IdAInterno`,
                    `qryPausasProgramadasDelDia`.`USUARI_Nombre____b` AS `Nombre`,
                    `qryPausasProgramadasDelDia`.`USUARI_Correo___b` AS `Correo`,
                    `dy_v_historico_descansos`.`tipo_descanso_nombre` AS `Pausa`,
                    TIMEDIFF(TIMEDIFF(`dy_v_historico_descansos`.`fecha_hora_fin`,
                                    `dy_v_historico_descansos`.`fecha_hora_inicio`),
                            TIMEDIFF(`qryPausasProgramadasDelDia`.`HoraFinalProgramada`,
                                    `qryPausasProgramadasDelDia`.`HoraInicialProgramada`)) AS `Exceso`,
                    TIMEDIFF(`qryPausasProgramadasDelDia`.`HoraFinalProgramada`,
                            `qryPausasProgramadasDelDia`.`HoraInicialProgramada`) AS `DuracionProgramada`,
                    TIMEDIFF(`dy_v_historico_descansos`.`fecha_hora_fin`,
                            `dy_v_historico_descansos`.`fecha_hora_inicio`) AS `DuracionReal`,
                    `qryPausasProgramadasDelDia`.`HoraInicialProgramada` AS `HoraInicialProgramada`,
                    DATE_FORMAT(`dy_v_historico_descansos`.`fecha_hora_inicio`,
                            '%H:%i:%S') AS `HoraInicialReal`,
                    `qryPausasProgramadasDelDia`.`HoraFinalProgramada` AS `HoraFinalProgramada`,
                    DATE_FORMAT(`dy_v_historico_descansos`.`fecha_hora_fin`,
                            '%H:%i:%S') AS `HoraFinalReal`
                FROM
                    (`DYALOGOCRM_SISTEMA`.`qryPausasProgramadasDelDia`
                    LEFT JOIN `dyalogo_telefonia`.`dy_v_historico_descansos` ON (((`qryPausasProgramadasDelDia`.`IdAgente` = `dy_v_historico_descansos`.`agente_id`)
                        AND (`qryPausasProgramadasDelDia`.`USUPAU_PausasId_b` = `dy_v_historico_descansos`.`tipo_descanso_id`))))
                WHERE
                    ((`dy_v_historico_descansos`.`fecha_hora_inicio` >= CURDATE())
                        AND (`qryPausasProgramadasDelDia`.`USUPAU_Tipo_b` = 1)
                        AND (TIMEDIFF(`qryPausasProgramadasDelDia`.`HoraFinalProgramada`,
                            `qryPausasProgramadasDelDia`.`HoraInicialProgramada`) < TIMEDIFF(`dy_v_historico_descansos`.`fecha_hora_fin`,
                            `dy_v_historico_descansos`.`fecha_hora_inicio`))
                        AND (`qryPausasProgramadasDelDia`.`USUARI_ConsInte__PROYEC_b` = {$this->getIdHuesped()}))
                ORDER BY `qryPausasProgramadasDelDia`.`USUARI_Nombre____b`";

        return (object)["queryView" =>  $view, "nameView" => $nameView];
    }


    private function getViewPausasConHorarioIncumplidas(): object
    {

        $nameView = "DY_V_H{$this->getIdHuesped()}_PausasConHorarioIncumplidas";
        $view = "CREATE OR REPLACE VIEW `DYALOGOCRM_WEB`.`{$nameView}` AS
                SELECT 
                    `qryPausasProgramadasDelDia`.`USUARI_ConsInte__b` AS `Id`,
                    `dy_v_historico_descansos`.`agente_id` AS `IdAInterno`,
                    `qryPausasProgramadasDelDia`.`USUARI_Nombre____b` AS `Nombre`,
                    `qryPausasProgramadasDelDia`.`USUARI_Correo___b` AS `Correo`,
                    `dy_v_historico_descansos`.`tipo_descanso_nombre` AS `Pausa`,
                    IF((`qryPausasProgramadasDelDia`.`HoraInicialProgramada` > DATE_FORMAT(`dy_v_historico_descansos`.`fecha_hora_inicio`,
                                '%H:%i:%S')),
                        TIMEDIFF(`qryPausasProgramadasDelDia`.`HoraInicialProgramada`,
                                DATE_FORMAT(`dy_v_historico_descansos`.`fecha_hora_inicio`,
                                        '%H:%i:%S')),
                        NULL) AS `SalioAntesPor`,
                    IF((DATE_FORMAT(`dy_v_historico_descansos`.`fecha_hora_fin`,
                                '%H:%i:%S') > `qryPausasProgramadasDelDia`.`HoraFinalProgramada`),
                        TIMEDIFF(DATE_FORMAT(`dy_v_historico_descansos`.`fecha_hora_fin`,
                                        '%H:%i:%S'),
                                `qryPausasProgramadasDelDia`.`HoraFinalProgramada`),
                        NULL) AS `LlegoTardePor`,
                    `qryPausasProgramadasDelDia`.`HoraInicialProgramada` AS `HoraInicialProgramada`,
                    DATE_FORMAT(`dy_v_historico_descansos`.`fecha_hora_inicio`,
                            '%H:%i:%S') AS `HoraInicialReal`,
                    `qryPausasProgramadasDelDia`.`HoraFinalProgramada` AS `HoraFinalProgramada`,
                    DATE_FORMAT(`dy_v_historico_descansos`.`fecha_hora_fin`,
                            '%H:%i:%S') AS `HoraFinalReal`,
                    TIMEDIFF(TIMEDIFF(`dy_v_historico_descansos`.`fecha_hora_fin`,
                                    `dy_v_historico_descansos`.`fecha_hora_inicio`),
                            TIMEDIFF(`qryPausasProgramadasDelDia`.`HoraFinalProgramada`,
                                    `qryPausasProgramadasDelDia`.`HoraInicialProgramada`)) AS `TiempoDiferencia`,
                    TIMEDIFF(`qryPausasProgramadasDelDia`.`HoraFinalProgramada`,
                            `qryPausasProgramadasDelDia`.`HoraInicialProgramada`) AS `DuracionProgramada`,
                    TIMEDIFF(`dy_v_historico_descansos`.`fecha_hora_fin`,
                            `dy_v_historico_descansos`.`fecha_hora_inicio`) AS `DuracionReal`
                FROM
                    (`DYALOGOCRM_SISTEMA`.`qryPausasProgramadasDelDia`
                    LEFT JOIN `dyalogo_telefonia`.`dy_v_historico_descansos` ON (((`qryPausasProgramadasDelDia`.`IdAgente` = `dy_v_historico_descansos`.`agente_id`)
                        AND (`qryPausasProgramadasDelDia`.`USUPAU_PausasId_b` = `dy_v_historico_descansos`.`tipo_descanso_id`))))
                WHERE
                    ((`dy_v_historico_descansos`.`fecha_hora_inicio` >= CURDATE())
                        AND (`qryPausasProgramadasDelDia`.`USUPAU_Tipo_b` = 1)
                        AND ((`qryPausasProgramadasDelDia`.`HoraInicialProgramada` > DATE_FORMAT(`dy_v_historico_descansos`.`fecha_hora_inicio`,
                            '%H:%i:%S'))
                        OR (DATE_FORMAT(`dy_v_historico_descansos`.`fecha_hora_fin`,
                            '%H:%i:%S') > `qryPausasProgramadasDelDia`.`HoraFinalProgramada`))
                        AND (`qryPausasProgramadasDelDia`.`USUARI_ConsInte__PROYEC_b` = {$this->getIdHuesped()}))
                ORDER BY `qryPausasProgramadasDelDia`.`USUARI_Nombre____b`";

        return (object)["queryView" =>  $view, "nameView" => $nameView];
    }

    
    private function getViewLlegadasTardeHoy(): object
    {

        $nameView = "DY_V_H{$this->getIdHuesped()}_LlegadasTardeHoy";
        $view = "CREATE OR REPLACE VIEW `DYALOGOCRM_WEB`.`{$nameView}` AS
        SELECT 
            `qryUSUARI_usuarios_agentes`.`USUARI_ConsInte__b` AS `Id`,
            `qryUSUARI_usuarios_agentes`.`IdAgente` AS `IdAInterno`,
            `qryUSUARI_usuarios_agentes`.`USUARI_Nombre____b` AS `Nombre`,
            `qryUSUARI_usuarios_agentes`.`USUARI_Correo___b` AS `Correo`,
            DATE_FORMAT(TIMEDIFF(`qrySesionesDelDia`.`HoraInicio`,
                            `qryUSUARI_usuarios_agentes`.`HoraInicialDefinida`),
                    '%H:%i:%S') AS `Retraso`,
            `qryUSUARI_usuarios_agentes`.`HoraInicialDefinida` AS `HoraInicialDefinida`,
            `qrySesionesDelDia`.`HoraInicio` AS `HoraInicialReal`
        FROM
            (`DYALOGOCRM_SISTEMA`.`qryUSUARI_usuarios_agentes`
            JOIN `DYALOGOCRM_SISTEMA`.`qrySesionesDelDia` ON ((`qryUSUARI_usuarios_agentes`.`IdAgente` = `qrySesionesDelDia`.`agente_id`)))
        WHERE
            ((`qrySesionesDelDia`.`HoraInicio` > `qryUSUARI_usuarios_agentes`.`HoraInicialDefinida`)
                AND (`qryUSUARI_usuarios_agentes`.`USUARI_ConsInte__PROYEC_b` = {$this->getIdHuesped()}))
        ORDER BY `qryUSUARI_usuarios_agentes`.`USUARI_Nombre____b`";

        return (object) ["queryView" =>  $view, "nameView" => $nameView];
    }

}
