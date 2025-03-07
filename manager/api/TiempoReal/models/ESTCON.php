 <?php

    class ESTCON
    {


        protected static $db;
        private $estpasObj;

        public function __construct()
        {
            self::$db = Helpers::connect();
            $this->estpasObj = new ESTPAS();
        }

        public function searchCampan($idPasoOrigen)
        {

            $arrCampan = [];
            $newOtherSteps = [(object)["ESTCON_ConsInte__ESTPAS_Has_b" => $idPasoOrigen]];
            $repeat = true;

            do {

                foreach ($newOtherSteps as $otheStepKey => $otheStepValue) {
                    $arrNextEstCon = $this->validNextEstCon($otheStepValue->ESTCON_ConsInte__ESTPAS_Has_b);

                    unset($newOtherSteps[$otheStepKey]);

                    foreach ($arrNextEstCon  as $keyEstCon => $valueEstCon) {
                        if ($valueEstCon->ESTPAS_Tipo______b == 1 || $valueEstCon->ESTPAS_Tipo______b == 6) {
                            array_push($arrCampan, $valueEstCon->ESTCON_ConsInte__ESTPAS_Has_b);
                        } else if($valueEstCon->ESTPAS_Tipo______b == 12){
                            $arrCampansInBot = $this->validCampanInBot($valueEstCon->ESTCON_ConsInte__ESTPAS_Has_b);
                            foreach ($arrCampansInBot as $valueCampanInBot) {
                                array_push($arrCampan, $valueCampanInBot);
                            }
                        } else {
                            array_push($newOtherSteps, $valueEstCon);
                        }
                    }
                }



                if (count($newOtherSteps) == 0) {
                    $repeat = false;
                }
            } while ($repeat);


            return $arrCampan;
        }

        private function validNextEstCon($idPasoOrigen)
        {

            $arrNextEstCon = [];

            $strSqlFlechas = "SELECT ESTCON_ConsInte__ESTPAS_Has_b, ESTPAS_Tipo______b FROM DYALOGOCRM_SISTEMA.ESTCON JOIN DYALOGOCRM_SISTEMA.ESTPAS ON ESTPAS_ConsInte__b = ESTCON_ConsInte__ESTPAS_Has_b WHERE ESTCON_ConsInte__ESTPAS_Des_b = '{$idPasoOrigen}' AND ESTPAS_Tipo______b != 121";
            $resSqlFlechas = self::$db->query($strSqlFlechas);
            if ($resSqlFlechas) {
                if ($resSqlFlechas->num_rows > 0) {
                    while ($objNextEstCon = $resSqlFlechas->fetch_object()) {
                        array_push($arrNextEstCon, $objNextEstCon);
                    }
                }
            }

            return $arrNextEstCon;
        }


        private function validCampanInBot($idBot, $botsAnalizedBerore = null){

            // Primero necesitamos saber el id del bot que vamos a analizar para no volver a analizarlo
            $idBotInitial = $this->getIdBot($idBot);
            $botAnalized = ($idBotInitial != false) ? [$idBotInitial] : [];

            $botAnalized = ($botsAnalizedBerore != null) ? array_merge($botAnalized, $botsAnalizedBerore) : $botAnalized;

            $arrCampansInBot = [];

            $strSqlFlechas = "SELECT s.tipo_seccion, s.id_paso_externo FROM dyalogo_canales_electronicos.secciones_conexiones_bot c join dyalogo_canales_electronicos.secciones_bot s on s.id = c.hasta  where c.id_estpas = '{$idBot}' AND (tipo_seccion = 24 or  tipo_seccion = 25) GROUP BY id_paso_externo";
            $resSqlFlechas = self::$db->query($strSqlFlechas);
            if ($resSqlFlechas) {
                if ($resSqlFlechas->num_rows > 0) {
                    while ($objConBot = $resSqlFlechas->fetch_object()) {
                        if($objConBot->id_paso_externo != null || $objConBot->id_paso_externo != ""){
                            if($objConBot->tipo_seccion == 24){
                                array_push($arrCampansInBot, $this->estpasObj->getByCbxId($objConBot->id_paso_externo)) ;
                            }else if($objConBot->tipo_seccion == 25){
                                if(!$this->analiceIfExistinArray($botAnalized, $objConBot->id_paso_externo)){
                                    $campansInOtherSec = $this->validCampaninOtheBot($objConBot->id_paso_externo, $botAnalized);
                                    foreach ($campansInOtherSec as $keyCampan => $valueCampan) {
                                        array_push($arrCampansInBot,$valueCampan);
                                    }
                                }
                            }
                        }
                    }
                }
            }

            return $arrCampansInBot;
        }

        private function getIdBot($idEstpas){
            $strSQLbot = "SELECT id FROM dyalogo_canales_electronicos.dy_bot WHERE id_estpas = '{$idEstpas}';";
            $resSQLbot = self::$db->query($strSQLbot);
            if ($resSQLbot) {
                if ($resSQLbot->num_rows > 0) {
                    return $resSQLbot->fetch_object()->id;
                }
            }
            return false;
        }

        private function analiceIfExistinArray($array, $valueToSearsh){
            foreach ($array as $value) {
                if($valueToSearsh == $value){
                    return true;
                }
            }

            return false;
        }

        private function validCampaninOtheBot($idBot, $botsAnalized = null){
            $strSqlEstpasBot = "SELECT id_estpas FROM dyalogo_canales_electronicos.dy_bot where id = '{$idBot}'";
            $resSqlEstpasBot = self::$db->query($strSqlEstpasBot);
            if ($resSqlEstpasBot) {
                if ($resSqlEstpasBot->num_rows > 0) {
                    while ($objEstpasBot = $resSqlEstpasBot->fetch_object()) {
                        return $this->validCampanInBot($objEstpasBot->id_estpas, $botsAnalized);
                    }
                }
            }

            return [];
        }
    }
