<?php

class ASITAR
{
    protected static $db;

    public function __construct()
    {
        self::$db = Helpers::connect();
    }


    // Funcion que me retorna la info de la campaña buscandolo por el id

    public function getCampanUsuari()
    {
        $res = [];

        $arrAgents = $this->getAllAgents();

        foreach ($arrAgents as $valAgent) {
            $strSQLCampanAso = "SELECT ASITAR_ConsInte__CAMPAN_b AS idCampan, CAMPAN_Nombre____b AS nameCampan FROM DYALOGOCRM_SISTEMA.ASITAR JOIN DYALOGOCRM_SISTEMA.CAMPAN ON ASITAR_ConsInte__CAMPAN_b = CAMPAN_ConsInte__b WHERE ASITAR_ConsInte__USUARI_b = (SELECT USUARI_ConsInte__b FROM DYALOGOCRM_SISTEMA.USUARI WHERE USUARI_UsuaCBX___b = '{$valAgent}' GROUP BY USUARI_UsuaCBX___b)";

            $resAgent = (Object)["idAgent" => $valAgent, "lstCampanasAsignadas_t" => []];

            $resSQLCampanAso = self::$db->query($strSQLCampanAso);
            if ($resSQLCampanAso) {
                if ($resSQLCampanAso->num_rows > 0) {
                    while ($objActivity = $resSQLCampanAso->fetch_object()) {
                        array_push($resAgent->lstCampanasAsignadas_t, (Object)["idCampan" => (int)$objActivity->idCampan, "nameCampan" => $objActivity->nameCampan]);
                    }
                    array_push($res,$resAgent );
                }
            }

            
        }

        return (count($res) > 0) ? $res : false;

    }

    private function getAllAgents(){
        $res = [];

        $strSqlUsuari = 'SELECT USUARI_UsuaCBX___b as idUsuCBX  FROM DYALOGOCRM_SISTEMA.USUARI where USUARI_IndiActi__b = -1 and USUARI_ConsInte__b != -10';

        $resSqlUsuari = self::$db->query($strSqlUsuari);
        if ($resSqlUsuari) {
            if ($resSqlUsuari->num_rows > 0) {
                while ($objUsuari = $resSqlUsuari->fetch_object()) {
                    array_push($res, (int)$objUsuari->idUsuCBX);
                }
            }
        }

        return (count($res) > 0) ? $res : false;
    }

}
