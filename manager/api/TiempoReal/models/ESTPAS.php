<?php 

class ESTPAS {
    protected static $db;

    public function __construct()
    {
        self::$db = Helpers::connect();
    }


    // SE OBTIENE TODOS LOS PASOS AMARILLOS Y CAMPAÑAS DE CADA ESTRATEGIA

    public function getAll($idEstrat){

        $res = [];

        $strSQLAllEstpas = "select ESTPAS_ConsInte__b,ESTPAS_Comentari_b,ESTPAS_ConsInte__CAMPAN_b,ESTPAS_Tipo______b from DYALOGOCRM_SISTEMA.ESTPAS WHERE ESTPAS_ConsInte__ESTRAT_b = '{$idEstrat}' AND ESTPAS_activo = -1 AND (ESTPAS_Tipo______b = 1 OR ESTPAS_Tipo______b = 6 OR ESTPAS_Tipo______b = 14 OR ESTPAS_Tipo______b = 15 OR ESTPAS_Tipo______b = 16 OR ESTPAS_Tipo______b = 17 OR ESTPAS_Tipo______b = 19 OR ESTPAS_Tipo______b = 20 ) ";
        $resSQLAllEstpas = self::$db->query($strSQLAllEstpas);
        if($resSQLAllEstpas){
            if($resSQLAllEstpas->num_rows > 0){
                while($objEstpas = $resSQLAllEstpas->fetch_object()){
                    array_push($res, $objEstpas);
                }
            }
        }

        return (count($res) > 0 ) ? $res : false ;

    }

    // SE OBTIENE TODOS LOS PASOS DE CAMPAÑAS DE CADA ESTRATEGIA

    
    public function getAllCampan($idEstrat){

        $res = [];

        $strSQLAllEstpas = "select ESTPAS_ConsInte__b,ESTPAS_Comentari_b,ESTPAS_ConsInte__CAMPAN_b,ESTPAS_Tipo______b from DYALOGOCRM_SISTEMA.ESTPAS WHERE ESTPAS_ConsInte__ESTRAT_b = '{$idEstrat}' AND ESTPAS_activo = -1 AND (ESTPAS_Tipo______b = 1  ) ";
        $resSQLAllEstpas = self::$db->query($strSQLAllEstpas);
        if($resSQLAllEstpas){
            if($resSQLAllEstpas->num_rows > 0){
                while($objEstpas = $resSQLAllEstpas->fetch_object()){
                    array_push($res, $objEstpas);
                }
            }
        }

        return (count($res) > 0 ) ? $res : false ;

    }

    public function getByCbxId($idCbx){
        $strSQLcampan = "select ESTPAS_ConsInte__b from dyalogo_telefonia.dy_campanas b join DYALOGOCRM_SISTEMA.CAMPAN c ON c.CAMPAN_IdCamCbx__b = b.id JOIN  DYALOGOCRM_SISTEMA.ESTPAS e on e.ESTPAS_ConsInte__CAMPAN_b = CAMPAN_ConsInte__b WHERE b.id = '{$idCbx}'";
        $resSQLcampan = self::$db->query($strSQLcampan);
        if ($resSQLcampan) {
            if ($resSQLcampan->num_rows > 0) {
                return $resSQLcampan->fetch_object()->ESTPAS_ConsInte__b;
            }
        }
        return false;
    }


}