<?php

class CAMPAN
{

    private $saltoLinea;
    protected static $db;

    public function __construct()
    {
        self::$db = Helpers::connect();
        $this->saltoLinea = (php_sapi_name() == "cli") ? "{\n}" : "</br>";
    }


    // Funcion que me retorna la info de la campaña buscandolo por el id

    public function getInfoById($idCampan)
    {

        $strSQLcampan = "SELECT CAMPAN_ConsInte__b, CAMPAN_TipoCamp__b, CAMPAN_Nombre____b, CAMPAN_ConsInte__GUION__Gui_b, CAMPAN_ConsInte__GUION__Pob_b, CAMPAN_ConsInte__MUESTR_b, CAMPAN_LimiRein__b, CAMPAN_IdCamCbx__b, nombre_interno, CAMPAN_TiempoNivelServ_b FROM DYALOGOCRM_SISTEMA.CAMPAN c join dyalogo_telefonia.dy_campanas t on t.id = CAMPAN_IdCamCbx__b  WHERE CAMPAN_ConsInte__b = '{$idCampan}'; ";
        $resSQLcampan = self::$db->query($strSQLcampan);
        if ($resSQLcampan) {
            if ($resSQLcampan->num_rows > 0) {
                return $resSQLcampan->fetch_object();
            }
        }
        return false;
    }


    // Funcion que me retorna la info de la campaña buscandolo por el id

    public function getInfoByIdEstPas($idPaso)
    {

        $strSQLcampan = "SELECT CAMPAN_ConsInte__b, CAMPAN_TipoCamp__b, CAMPAN_Nombre____b, CAMPAN_ConsInte__GUION__Gui_b, CAMPAN_ConsInte__GUION__Pob_b, CAMPAN_ConsInte__MUESTR_b, CAMPAN_LimiRein__b, CAMPAN_IdCamCbx__b, nombre_interno, CAMPAN_TiempoNivelServ_b FROM DYALOGOCRM_SISTEMA.ESTPAS JOIN DYALOGOCRM_SISTEMA.CAMPAN ON CAMPAN_ConsInte__b = ESTPAS_ConsInte__CAMPAN_b JOIN dyalogo_telefonia.dy_campanas t on t.id = CAMPAN_IdCamCbx__b WHERE ESTPAS_ConsInte__b = '{$idPaso}'";
        
        $resSQLcampan = self::$db->query($strSQLcampan);
        if ($resSQLcampan) {
            if ($resSQLcampan->num_rows > 0) {
                return $resSQLcampan->fetch_object();
            }
        }
        return false;
    }


    // Funcion que me retorna la info del reporte ACD de las campañas

    public function getInfoACD($idCampanCBX)
    {

        $strSQLcampan = "SELECT recibidas, contestadas FROM dyalogo_telefonia.dy_informacion_actual_campanas where fecha = date(now()) and id_campana = '{$idCampanCBX}' limit 1";

        echo ("Se obtiene la informacion del reporte ACD Consulta: {$strSQLcampan} {$this->saltoLinea} ");
        
        $resSQLcampan = self::$db->query($strSQLcampan);
        if ($resSQLcampan) {
            if ($resSQLcampan->num_rows > 0) {
                return $resSQLcampan->fetch_object();
            }
        }
        return false;
    }


    // Funcion que me retorna el id de la campaña, buscandolo por nombre y id selecctivos

    public function getIdCampanByName(string $campanName, array $idsCampan): int
    {

        if(count($idsCampan) > 0){
            $whereIdCampan = "";
            $separador = " ";
            foreach ($idsCampan as $value) {
                $whereIdCampan .= $separador.$value;
                $separador = ",";
            }
    
            $strSQLcampan = "SELECT CAMPAN_ConsInte__b FROM DYALOGOCRM_SISTEMA.CAMPAN WHERE CAMPAN_ConsInte__b IN({$whereIdCampan}) AND CAMPAN_Nombre____b = '{$campanName}'";
            $resSQLcampan = self::$db->query($strSQLcampan);
            if ($resSQLcampan) {
                if ($resSQLcampan->num_rows > 0) {
                    return $resSQLcampan->fetch_object()->CAMPAN_ConsInte__b;
                }
            } 
        }
        return false;
    }


}
