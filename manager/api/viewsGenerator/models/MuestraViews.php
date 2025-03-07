<?php

class MuestraViews extends Views
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

            $viewsResults = array();

            $viewsResults["idViewMuestra"] = $this->createView();

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
    private function createView()
    {
        try {
            // Primero validamos si el paso contiene alguna muestra
            if ($infoPaso = $this->getInfoPaso()) {
                $this->setIdEstrat($infoPaso->ESTPAS_ConsInte__ESTRAT_b);

                if ($infoPaso->ESTPAS_ConsInte__MUESTR_b != null) {
                    // Si entra aqui quiere decir que es un paso saliente, por lo cual debo de buscar el guion por la estrategia
                    $this->setIdMuestra($infoPaso->ESTPAS_ConsInte__MUESTR_b);
                    if ($infoEstrat  = $this->getInfoEstrat()) {
                        $this->setIdGuion($infoEstrat->ESTRAT_ConsInte_GUION_Pob);
                    }
                } else {
                    // Validamos si el paso es alguna campaña
                    if ((int)$infoPaso->ESTPAS_Tipo______b == 1 || (int)$infoPaso->ESTPAS_Tipo______b == 6) {
                        $this->setIdCampan((int)$infoPaso->ESTPAS_ConsInte__CAMPAN_b);
                        if ($infoCampan  = $this->getInfoCampan()) {
                            $this->setIdMuestra($infoCampan->CAMPAN_ConsInte__MUESTR_b);
                            $this->setIdGuion($infoCampan->CAMPAN_ConsInte__GUION__Pob_b);
                        }
                    }
                }

                if ($this->getIdMuestra() && $this->getIdGuion()) {
                    $viewObj = $this->getViewMuestra();
                } else {
                    $this->error("El paso no tienen ninguna muestra");
                }
            } else {
                $this->error("El paso no existe");
            }

            if ($viewObj->queryView != "") {
                $sql = self::$db->query($viewObj->queryView);
                if ($sql) {
                    return $this->saveViewGenerated($viewObj->nameView,null,null, $this->getIdEstrat(), "PASO_{$this->getIdMuestra()}" );
                } else {
                    $this->error(self::$db->error,$viewObj->queryView);
                }
            }
        } catch (\Throwable $th) {
            $this->error($th->__toString());
        }
    }


    private function getViewMuestra(): object
    {

        $nameView = "";
        $view = "";
        $infoPaso = $this->getInfoPaso();
        $nameView = "DY_V_H{$this->getIdHuesped()}_G{$this->getIdGuion()}_PASO_{$this->getIdMuestra()}_{$this->aliasColumna($infoPaso->ESTPAS_Comentari_b)}";
        $typeStep = $this->translateTypeStep($infoPaso->ESTPAS_Tipo______b);
        $defaultColumns = $this->getDefaultColumns();
        $objDinamicColumns = $this->buildDinamicColumns();

        // En el caso de la base no tengo algun orden en especial entonces concateno todo
        $strColumnsSpecial = "";
        foreach ($objDinamicColumns->arrColumnsSpecial as $valSpecial) {
            $strColumnsSpecial .= $valSpecial->columnName;
        }

        $view = "CREATE OR REPLACE VIEW `DYALOGOCRM_WEB`.`{$nameView}` AS
                        SELECT 
                            {$defaultColumns[0]}
                            ,'{$infoPaso->ESTPAS_Comentari_b}' AS `Paso`,
                            '{$typeStep}' AS `TipoDePaso`
                            {$strColumnsSpecial}
                            {$objDinamicColumns->strDinamicColumns} 
                            {$defaultColumns[1]}

                            FROM
                                `DYALOGOCRM_WEB`.`G{$this->getIdGuion()}_M{$this->getIdMuestra()}` `M`
                                LEFT JOIN `DYALOGOCRM_WEB`.`G{$this->getIdGuion()}` `G` ON `M`.`G{$this->getIdGuion()}_M{$this->getIdMuestra()}_CoInMiPo__b` = `G`.`G{$this->getIdGuion()}_ConsInte__b`
                                LEFT JOIN `DYALOGOCRM_SISTEMA`.`MONOEF` `MO1` ON `MO1`.`MONOEF_ConsInte__b` = `G`.`G{$this->getIdGuion()}_UltiGest__b`
                                LEFT JOIN `DYALOGOCRM_SISTEMA`.`MONOEF` `MO2` ON `MO2`.`MONOEF_ConsInte__b` = `G`.`G{$this->getIdGuion()}_GesMasImp_b`
                                LEFT JOIN `DYALOGOCRM_SISTEMA`.`USUARI` `USU` ON `USU`.`USUARI_ConsInte__b` = `G`.`G{$this->getIdGuion()}_UsuarioGMI_b`
                                LEFT JOIN `DYALOGOCRM_SISTEMA`.`LISOPC` `LIS` ON `LIS`.`LISOPC_ConsInte__b` = `G`.`G{$this->getIdGuion()}_EstadoGMI_b`
                                LEFT JOIN `DYALOGOCRM_SISTEMA`.`LISOPC` `LISUG` ON `LISUG`.`LISOPC_ConsInte__b` = `G`.`G{$this->getIdGuion()}_EstadoUG_b`
                                {$objDinamicColumns->strJOIN_t}
                            ORDER BY `G`.`G{$this->getIdGuion()}_ConsInte__b` DESC";

        return (object) ["queryView" =>  $view, "nameView" => $nameView];
    }

    private function getDefaultColumns(): array
    {

        $defaultColumns = [];

        $defaultColumns[] = "
            `M`.`G{$this->getIdGuion()}_M{$this->getIdMuestra()}_CoInMiPo__b` AS `ID`,
            `G`.`G{$this->getIdGuion()}_OrigenUltimoCargue` AS `ORIGEN_ULT_CARGUE`,
            `G`.`G{$this->getIdGuion()}_FechaUltimoCargue` AS `FECHA_ULT_CARGUE`";

        $defaultColumns[] = "
            ,DATE_FORMAT(`G`.`G{$this->getIdGuion()}_FechaInsercion`,'%Y-%m-%d %H:%i:%s') AS `FECHA_CREACION`,
            YEAR(`G`.`G{$this->getIdGuion()}_FechaInsercion`) AS `ANHO_CREACION`,
            MONTH(`G`.`G{$this->getIdGuion()}_FechaInsercion`) AS `MES_CREACION`,
            DAYOFMONTH(`G`.`G{$this->getIdGuion()}_FechaInsercion`) AS `DIA_CREACION`,
            HOUR(`G`.`G{$this->getIdGuion()}_FechaInsercion`) AS `HORA_CREACION`,
            MINUTE(`G`.`G{$this->getIdGuion()}_FechaInsercion`) AS `MINUTO_CREACION`,
            SECOND(`G`.`G{$this->getIdGuion()}_FechaInsercion`) AS `SEGUNDO_CREACION`,
            `dyalogo_general`.`fn_nombre_USUARI`(`M`.`G{$this->getIdGuion()}_M{$this->getIdMuestra()}_ConIntUsu_b`) AS `AGENTE_ASIGNADO`,
            `LIS`.`LISOPC_Nombre____b` AS `ESTADO_GMI`,
            `MO2`.`MONOEF_Texto_____b` AS `GESTION_MAS_IMPORTANTE`,
            `dyalogo_general`.`fn_clasificacion_traduccion`(`M`.`G{$this->getIdGuion()}_M{$this->getIdMuestra()}_CoGesMaIm_b`) AS `CLASIFICACION_GMI`,
            `dyalogo_general`.`fn_tipo_reintento_traduccion`(`M`.`G{$this->getIdGuion()}_M{$this->getIdMuestra()}_TipoReintentoGMI_b`) AS `REINTENTO_GMI`,
            DATE_FORMAT(`M`.`G{$this->getIdGuion()}_M{$this->getIdMuestra()}_FecHorAgeGMI_b`,
            '%Y-%m-%d %H:%i:%s') AS `FECHA_AGENDA_GMI`,
            YEAR(`M`.`G{$this->getIdGuion()}_M{$this->getIdMuestra()}_FecHorAgeGMI_b`) AS `ANHO_AGENDA_GMI`,
            MONTH(`M`.`G{$this->getIdGuion()}_M{$this->getIdMuestra()}_FecHorAgeGMI_b`) AS `MES_AGENDA_GMI`,
            DAYOFMONTH(`M`.`G{$this->getIdGuion()}_M{$this->getIdMuestra()}_FecHorAgeGMI_b`) AS `DIA_AGENDA_GMI`,
            HOUR(`M`.`G{$this->getIdGuion()}_M{$this->getIdMuestra()}_FecHorAgeGMI_b`) AS `HORA_AGENDA_GMI`,
            MINUTE(`M`.`G{$this->getIdGuion()}_M{$this->getIdMuestra()}_FecHorAgeGMI_b`) AS `MINUTO_AGENDA_GMI`,
            SECOND(`M`.`G{$this->getIdGuion()}_M{$this->getIdMuestra()}_FecHorAgeGMI_b`) AS `SEGUNDO_AGENDA_GMI`,
            `M`.`G{$this->getIdGuion()}_M{$this->getIdMuestra()}_ComentarioGMI_b` AS `COMENTARIO_GMI`,
            `dyalogo_general`.`fn_nombre_USUARI`(`M`.`G{$this->getIdGuion()}_M{$this->getIdMuestra()}_UsuarioGMI_b`) AS `AGENTE_GMI`,
            `M`.`G{$this->getIdGuion()}_M{$this->getIdMuestra()}_CanalGMI_b` AS `CANAL_GMI`,
            `M`.`G{$this->getIdGuion()}_M{$this->getIdMuestra()}_DetalleCanalUG_b` AS `DETALLE_CANAL_UG`,
            `M`.`G{$this->getIdGuion()}_M{$this->getIdMuestra()}_Activo____b` AS `ACTIVO`,
            `M`.`G{$this->getIdGuion()}_M{$this->getIdMuestra()}_SentidoGMI_b` AS `SENTIDO_GMI`,
            `M`.`G{$this->getIdGuion()}_M{$this->getIdMuestra()}_FecHorMinProGes__b` AS `FecHorMinProGes`,
            IF((`M`.`G{$this->getIdGuion()}_M{$this->getIdMuestra()}_NumeInte__b` >= 4),
            TRUE,
            FALSE) AS `SuperoLimiteIntentos`,
            DATE_FORMAT(`M`.`G{$this->getIdGuion()}_M{$this->getIdMuestra()}_FeGeMaIm__b`,
            '%Y-%m-%d %H:%i:%s') AS `FECHA_GMI`,
            YEAR(`M`.`G{$this->getIdGuion()}_M{$this->getIdMuestra()}_FeGeMaIm__b`) AS `ANHO_GMI`,
            MONTH(`M`.`G{$this->getIdGuion()}_M{$this->getIdMuestra()}_FeGeMaIm__b`) AS `MES_GMI`,
            DAYOFMONTH(`M`.`G{$this->getIdGuion()}_M{$this->getIdMuestra()}_FeGeMaIm__b`) AS `DIA_GMI`,
            HOUR(`M`.`G{$this->getIdGuion()}_M{$this->getIdMuestra()}_FeGeMaIm__b`) AS `HORA_GMI`,
            MINUTE(`M`.`G{$this->getIdGuion()}_M{$this->getIdMuestra()}_FeGeMaIm__b`) AS `MINUTO_GMI`,
            SECOND(`M`.`G{$this->getIdGuion()}_M{$this->getIdMuestra()}_FeGeMaIm__b`) AS `SEGUNDO_GMI`,
            (DATE_FORMAT(`M`.`G{$this->getIdGuion()}_M{$this->getIdMuestra()}_FeGeMaIm__b`,
            '%Y-%m-%d %H:%i:%S') - DATE_FORMAT(`G`.`G{$this->getIdGuion()}_FechaInsercion`,
            '%Y-%m-%d %H:%i:%S')) AS `DIAS_MADURACION_GMI`,
            `LISUG`.`LISOPC_Nombre____b` AS `ESTADO_UG`,
            `MO1`.`MONOEF_Texto_____b` AS `ULTIMA_GESTION`,
            `dyalogo_general`.`fn_clasificacion_traduccion`(`M`.`G{$this->getIdGuion()}_M{$this->getIdMuestra()}_ConUltGes_b`) AS `CLASIFICACION_UG`,
            `dyalogo_general`.`fn_tipo_reintento_traduccion`(`M`.`G{$this->getIdGuion()}_M{$this->getIdMuestra()}_Estado____b`) AS `REINTENTO_UG`,
            DATE_FORMAT(`M`.`G{$this->getIdGuion()}_M{$this->getIdMuestra()}_FecHorAge_b`,
            '%Y-%m-%d %H:%i:%s') AS `FECHA_AGENDA_UG`,
            YEAR(`M`.`G{$this->getIdGuion()}_M{$this->getIdMuestra()}_FecHorAge_b`) AS `ANHO_AGENDA_UG`,
            MONTH(`M`.`G{$this->getIdGuion()}_M{$this->getIdMuestra()}_FecHorAge_b`) AS `MES_AGENDA_UG`,
            DAYOFMONTH(`M`.`G{$this->getIdGuion()}_M{$this->getIdMuestra()}_FecHorAge_b`) AS `DIA_AGENDA_UG`,
            HOUR(`M`.`G{$this->getIdGuion()}_M{$this->getIdMuestra()}_FecHorAge_b`) AS `HORA_AGENDA_UG`,
            MINUTE(`M`.`G{$this->getIdGuion()}_M{$this->getIdMuestra()}_FecHorAge_b`) AS `MINUTO_AGENDA_UG`,
            SECOND(`M`.`G{$this->getIdGuion()}_M{$this->getIdMuestra()}_FecHorAge_b`) AS `SEGUNDO_AGENDA_UG`,
            `M`.`G{$this->getIdGuion()}_M{$this->getIdMuestra()}_Comentari_b` AS `COMENTARIO_UG`,
            `dyalogo_general`.`fn_nombre_USUARI`(`M`.`G{$this->getIdGuion()}_M{$this->getIdMuestra()}_UsuarioUG_b`) AS `AGENTE_UG`,
            `M`.`G{$this->getIdGuion()}_M{$this->getIdMuestra()}_CanalUG_b` AS `CANAL_UG`,
            `M`.`G{$this->getIdGuion()}_M{$this->getIdMuestra()}_SentidoUG_b` AS `SENTIDO_UG`,
            DATE_FORMAT(`M`.`G{$this->getIdGuion()}_M{$this->getIdMuestra()}_FecUltGes_b`,
            '%Y-%m-%d %H:%i:%s') AS `FECHA_UG`,
            YEAR(`M`.`G{$this->getIdGuion()}_M{$this->getIdMuestra()}_FecUltGes_b`) AS `ANHO_UG`,
            MONTH(`M`.`G{$this->getIdGuion()}_M{$this->getIdMuestra()}_FecUltGes_b`) AS `MES_UG`,
            DAYOFMONTH(`M`.`G{$this->getIdGuion()}_M{$this->getIdMuestra()}_FecUltGes_b`) AS `DIA_UG`,
            HOUR(`M`.`G{$this->getIdGuion()}_M{$this->getIdMuestra()}_FecUltGes_b`) AS `HORA_UG`,
            MINUTE(`M`.`G{$this->getIdGuion()}_M{$this->getIdMuestra()}_FecUltGes_b`) AS `MINUTO_UG`,
            SECOND(`M`.`G{$this->getIdGuion()}_M{$this->getIdMuestra()}_FecUltGes_b`) AS `SEGUNDO_UG`,
            `M`.`G{$this->getIdGuion()}_M{$this->getIdMuestra()}_LinkContenidoUG_b` AS `CONTENIDO_UG`,
            `M`.`G{$this->getIdGuion()}_M{$this->getIdMuestra()}_NumeInte__b` AS `CANTIDAD_INTENTOS`,
            `M`.`G{$this->getIdGuion()}_M{$this->getIdMuestra()}_LinkContenidoGMI_b` AS `CONTENIDO_GMI`,
            `M`.`G{$this->getIdGuion()}_M{$this->getIdMuestra()}_DetalleCanalGMI_b` AS `DETALLE_CANAL_GMI`,
            `M`.`G{$this->getIdGuion()}_M{$this->getIdMuestra()}_FechaCreacion_b` AS `FECHA_CREACION_MUESTRA_DY`,
            `M`.`G{$this->getIdGuion()}_M{$this->getIdMuestra()}_FechaReactivacion_b` AS `FECHA_REACTIVACION_MUESTRA_DY` ";


        return $defaultColumns;
    }
}
