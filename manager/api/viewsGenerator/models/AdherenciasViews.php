<?php

class AdherenciasViews extends Views
{

    public function __construct()
    {
        parent::__construct();
    }

    private function createViewSalidasTempranoHoy() : int
    {
        try {
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
                        AND (`qryUSUARI_usuarios_agentes`.`USUARI_ConsInte__PROYEC_b` = {$this->getIdProyecto()}))
                ORDER BY `qryUSUARI_usuarios_agentes`.`USUARI_Nombre____b`";

            $sql = self::$db->query($view);
            if ($sql) {
                return $this->saveViewGenerated(-2, $nameView, -100);
            } else {
                $this->error(self::$db->error, $view);
            }
        } catch (\Throwable $th) {
            $this->error();
        }
    }


    private function createViewPausasSinHorarioMuyLargas() : int
    {
        try {
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
                        AND (`qryUSUARI_usuarios_agentes`.`USUARI_ConsInte__PROYEC_b` = {$this->getIdProyecto()}))
                ORDER BY `qryUSUARI_usuarios_agentes`.`USUARI_Nombre____b`";

            $sql = self::$db->query($view);
            if ($sql) {
                return $this->saveViewGenerated(-2, $nameView, -100);
            } else {
                $this->error(self::$db->error, $view);
            }
        } catch (\Throwable $th) {
            $this->error();
        }
    }

    // Se remplaza el metodo generate views
    public function generateViews(): array
    {
        try {
            (array)$response = array();
            $response['estado'] = 'error';
            $response['mensaje'] = array();

            $idViewSalidasTempranoHoy = $this->createViewSalidasTempranoHoy();

            if($idViewSalidasTempranoHoy){
                $response['estado'] = 'ok';
                $response['mensaje']["SalidasTempranoHoy"] = $idViewSalidasTempranoHoy;
            }

            return $response;
        } catch (\Throwable $th) {
            $this->error();
        }
    }
}
