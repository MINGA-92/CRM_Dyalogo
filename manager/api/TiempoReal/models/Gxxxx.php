<?php

class Gxxxx
{

    private $saltoLinea;
    protected static $db;

    public function __construct()
    {
        self::$db = Helpers::connect();
        $this->saltoLinea = (php_sapi_name() == "cli") ? "{\n}" : "</br>";
    }


    public function calculateConteos($infoCampan, $typeCampan, $pasoCampan, $idPaso, $calcComplete = false)
    {

        $strChannel = "";
        $guionGest = (isset($infoCampan->CAMPAN_ConsInte__GUION__Gui_b)) ? $infoCampan->CAMPAN_ConsInte__GUION__Gui_b : null;
        $finalRes = ($typeCampan == 6) ? (Object) ["#Sin gestion" => 0,"#Gestiones" => 0,"#Contactados" => 0,"#Efectivos" => 0] : (Object) ["#Recibidas" => 0,"#Contestadas" => 0,"#Efectivos" => 0,"TMO" => 0,];

        switch ($typeCampan) {
            case 1:
            case 6:
                $strChannel = "telefonia";
                break;

            case 14:
                $strChannel = "Chat_web";
                break;

            case 15:
                $strChannel = "Whatsapp";
                break;

            case 16:
                $strChannel = "Facebook_messenger";
                break;

            case 17:
                $strChannel = "email";
                break;

            case 19:
                $strChannel = "webform";
                break;

            case 20:
                $strChannel = "CHAT_instagram";
                break;
        }

        // Se valida si se quiere calcular completamente la informacion, si no se obtiene unicamente los efectivos y el tmo (esto por temas de rendimiento)
        if($calcComplete){
            $strSQLConteos = "SELECT SUM(G{$guionGest}_Clasificacion <= 7) AS Recibidas,
            SUM(G{$guionGest}_Clasificacion >= 5) AS Contactados,
            SUM(G{$guionGest}_Clasificacion = 7) AS Efectivos,
            AVG(TIME_TO_SEC(G{$guionGest}_Duracion___b)) AS TMO FROM DYALOGOCRM_WEB.G{$guionGest} WHERE G{$guionGest}_Canal_____b LIKE '%{$strChannel}%' and G{$guionGest}_FechaInsercion>= '".date('Y-m-d')." 00:00:00' and G{$guionGest}_Paso='{$pasoCampan}'";
        }else{
            $strSQLConteos = "SELECT SUM(G{$guionGest}_Clasificacion = 7) AS Efectivos,
            AVG(TIME_TO_SEC(G{$guionGest}_Duracion___b)) AS TMO FROM DYALOGOCRM_WEB.G{$guionGest} WHERE G{$guionGest}_Canal_____b LIKE '%{$strChannel}%' and G{$guionGest}_FechaInsercion>= '".date('Y-m-d')." 00:00:00' and G{$guionGest}_Paso='{$pasoCampan}'";
        }


        echo ("Consulta SQL:  Conteos Paso: {$idPaso} Canal: {$typeCampan} =  {$strSQLConteos} {$this->saltoLinea}");


        // SI ES UNA CAMPAÑA SALIENTE DEBEMOS DE CALCULAR LOS REGISTROS SIN GESTION
        if ($typeCampan == 6) {

            $sinGestion = 0;
            $guionPob = (isset($infoCampan->CAMPAN_ConsInte__GUION__Pob_b)) ? $infoCampan->CAMPAN_ConsInte__GUION__Pob_b : null;
            $muestra = (isset($infoCampan->CAMPAN_ConsInte__MUESTR_b)) ? $infoCampan->CAMPAN_ConsInte__MUESTR_b : null;
            $limitReint = (isset($infoCampan->CAMPAN_LimiRein__b)) ? $infoCampan->CAMPAN_LimiRein__b : null;

            if($this->validExistTable("G{$guionPob}_M{$muestra}")){
                $sinGestion = $this->calcWithoutManagement($guionPob, $muestra, $limitReint);
            }
        }



        // echo ("Sentencia SQL para el paso {$pasoCampan}: {$strSQLConteos} </br></br>");
        $resSQLConteos = self::$db->query($strSQLConteos);
        if ($resSQLConteos) {
            if ($resSQLConteos->num_rows > 0) {

                $res = $resSQLConteos->fetch_object();

                if ($typeCampan == 6) {
                    $finalRes->{"#Sin gestion"} = intval($sinGestion);

                    if($calcComplete){
                        $finalRes->{"#Gestiones"} = intval($res->Recibidas);
                        $finalRes->{"#Contactados"} = intval($res->Contactados);
                    }

                    $finalRes->{"#Efectivos"} = intval($res->Efectivos);
                    $finalRes->{"TMO"} = intval($res->TMO);
                }else {
                    if($calcComplete){
                        $finalRes->{"#Recibidas"} = intval($res->Recibidas);
                        $finalRes->{"#Contestadas"} = intval($res->Contactados);
                    }
                    $finalRes->{"#Efectivos"} = intval($res->Efectivos);
                    $finalRes->{"TMO"} = intval($res->TMO);
                }
            }
        }

        return $finalRes;
    }

    public function validateActivityInCampan($guion, $muestra): bool
    {
        $guion = ($guion != null || $guion != "") ? $guion : null;
        $muestra = ($muestra != null || $muestra != "") ? $muestra : null;

        $strNombreMuestra = "G{$guion}_M{$muestra}";

        if ($this->validExistTable($strNombreMuestra)) {

            $strSQLvalidActivity = "SELECT COUNT(*) AS conteo FROM DYALOGOCRM_WEB.{$strNombreMuestra} WHERE DATE({$strNombreMuestra}_FecUltGes_b) >= DATE(NOW()) ";
            echo("Consulta SQL Validar actividad : {$strSQLvalidActivity} ");
            $resSQLvalidActivity = self::$db->query($strSQLvalidActivity);
            if ($resSQLvalidActivity) {
                if ($resSQLvalidActivity->num_rows > 0) {
                    return ($resSQLvalidActivity->fetch_object()->conteo > 0) ? true : false;
                }
            }
        }

        return false;
    }

    private function validExistTable($nameTable)
    {
        $strSQLtableExist = "SELECT COUNT(1) as conteo FROM information_schema.TABLES  WHERE TABLE_SCHEMA = 'DYALOGOCRM_WEB' AND TABLE_NAME = '{$nameTable}'";
        $resSQLtableExist = self::$db->query($strSQLtableExist);
        if ($resSQLtableExist) {
            if ($resSQLtableExist->num_rows > 0) {
                return ($resSQLtableExist->fetch_object()->conteo > 0) ? true : false;
            }
        }
        return false;
    }

    // ESTA FUNCION NOS AYUDA A CONTAR LOS REGISTROS SIN GESTION EN LA MUESTRA DE LA CAMPAÑA
    private function calcWithoutManagement($guionPob, $muestra, $limitReint)
    {
        $limitReint = ($limitReint != null || $limitReint != "") ? $limitReint : 0;
        
        $strSQLcount = "SELECT COUNT(*) as conteo FROM DYALOGOCRM_WEB.G{$guionPob}_M{$muestra} WHERE G{$guionPob}_M{$muestra}_Activo____b =-1 AND (G{$guionPob}_M{$muestra}_Estado____b = 0 OR G{$guionPob}_M{$muestra}_Estado____b IS NULL) AND (G{$guionPob}_M{$muestra}_ConUltGes_b IS NULL OR G{$guionPob}_M{$muestra}_ConUltGes_b != 2) AND G{$guionPob}_M{$muestra}_NumeInte__b < '{$limitReint}';";
        
        echo ("Consulta SQL:  Conteo Sin Gestion: {$strSQLcount} {$this->saltoLinea}");
        
        $resSQLcount = self::$db->query($strSQLcount);
        if ($resSQLcount) {
            if ($resSQLcount->num_rows > 0) {
                return $resSQLcount->fetch_object()->conteo;
            }
        }

        return 0;
    }
}
