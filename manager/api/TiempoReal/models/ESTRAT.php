<?php

class ESTRAT
{
    protected static $db;

    public function __construct()
    {
        self::$db = Helpers::connect();
    }

    public function getAll()
    {

        $res = [];

        $strSQLAllEstrat = "select ESTRAT_ConsInte__b, ESTRAT_Nombre____b from DYALOGOCRM_SISTEMA.ESTRAT join dyalogo_telefonia.dy_proyectos on ESTRAT_ConsInte__PROYEC_b = id ORDER BY ESTRAT_ConsInte__b DESC ";
        $resSQLAllEstrat = self::$db->query($strSQLAllEstrat);
        if ($resSQLAllEstrat) {
            if ($resSQLAllEstrat->num_rows > 0) {
                while ($objEstrat = $resSQLAllEstrat->fetch_object()) {
                    array_push($res, $objEstrat);
                }
            }
        }

        return (count($res) > 0) ? $res : false;
    }


    public function getByHusped($idHuesped)
    {

        $res = [];
        
        // $strSQLAllEstrat = "SELECT e.ESTRAT_ConsInte__b, e.ESTRAT_Nombre____b, e.ESTRAT_Color____b FROM DYALOGOCRM_SISTEMA.ESTRAT e JOIN dyalogo_telefonia.dy_proyectos p ON e.ESTRAT_ConsInte__PROYEC_b = p.id JOIN dyalogo_general.huespedes h ON p.id_huesped = h.id WHERE h.id = {$idHuesped};";
        
        $strSQLAllEstrat = "SELECT e.ESTRAT_ConsInte__b, e.ESTRAT_Nombre____b, e.ESTRAT_Color____b FROM DYALOGOCRM_SISTEMA.ESTRAT e WHERE e.ESTRAT_ConsInte__PROYEC_b = '{$idHuesped}';";
        
        $resSQLAllEstrat = self::$db->query($strSQLAllEstrat);
        if ($resSQLAllEstrat) {
            if ($resSQLAllEstrat->num_rows > 0) {
                while ($objEstrat = $resSQLAllEstrat->fetch_object()) {
                    array_push($res, $objEstrat);
                }
            }
        }

        return (count($res) > 0) ? $res : false;
    }
}
