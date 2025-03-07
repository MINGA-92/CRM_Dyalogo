<?php

class GuionViews extends Views
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

            $viewsResults["idViewGuion"] = $this->createView();

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
            // Primero necesito saber si es una base o un formulario
            if ($infoGuion = $this->getInfoGuion()) {
                if ($infoGuion->GUION__Tipo______b == 2) {
                    $viewObj = $this->getViewBase();
                } else {
                    $viewObj = $this->getViewFormulario();
                }
            } else {
                $this->error("El guion no existe");
            }


            if ($viewObj->queryView != "") {
                $sql = self::$db->query($viewObj->queryView);
                if ($sql) {
                    return $this->saveViewGenerated($viewObj->nameView, $this->getIdGuion());
                } else {
                    $this->error(
                        self::$db->error,
                        $viewObj->queryView
                    );
                }
            }
        } catch (\Throwable $th) {
            $this->error($th->__toString());
        }
    }


    private function getViewBase(): object
    {

        $nameView = "";
        $view = "";
        $infoGuion = $this->getInfoGuion();
        $nameView = "DY_V_H{$this->getIdHuesped()}_T_BD_G{$this->getIdGuion()}_{$this->aliasColumna($infoGuion->GUION__Nombre____b)}";
        $defaultColumns = $this->getDefaultColumns($infoGuion->GUION__Tipo______b)[0];
        $objDinamicColumns = $this->buildDinamicColumns();

        // En el caso de la base no tengo algun orden en especial entonces concateno todo
        $strColumnsSpecial = "";
        foreach ($objDinamicColumns->arrColumnsSpecial as $valSpecial) {
            $strColumnsSpecial .= $valSpecial->columnName;
        }

        $view = "CREATE OR REPLACE VIEW `DYALOGOCRM_WEB`.`{$nameView}` AS
                        SELECT 
                            {$defaultColumns}
                            {$strColumnsSpecial}
                            {$objDinamicColumns->strDinamicColumns} 
                        FROM
                            `DYALOGOCRM_WEB`.`G{$this->getIdGuion()}` `G`
                            LEFT JOIN `DYALOGOCRM_SISTEMA`.`MONOEF` `MO1` ON `MO1`.`MONOEF_ConsInte__b` = `G`.`G{$this->getIdGuion()}_UltiGest__b`
                            LEFT JOIN `DYALOGOCRM_SISTEMA`.`MONOEF` `MO2` ON `MO2`.`MONOEF_ConsInte__b` = `G`.`G{$this->getIdGuion()}_GesMasImp_b`
                            LEFT JOIN `DYALOGOCRM_SISTEMA`.`USUARI` `USU` ON `USU`.`USUARI_ConsInte__b` = `G`.`G{$this->getIdGuion()}_UsuarioGMI_b`
                            LEFT JOIN `DYALOGOCRM_SISTEMA`.`LISOPC` `LIS` ON `LIS`.`LISOPC_ConsInte__b` = `G`.`G{$this->getIdGuion()}_EstadoGMI_b`
                            LEFT JOIN `DYALOGOCRM_SISTEMA`.`LISOPC` `LISUG` ON `LISUG`.`LISOPC_ConsInte__b` = `G`.`G{$this->getIdGuion()}_EstadoUG_b` 
                            {$objDinamicColumns->strJOIN_t}
                        ORDER BY `G`.`G{$this->getIdGuion()}_ConsInte__b` DESC";

        return (object) ["queryView" =>  $view,"nameView" => $nameView];
    }



    private function getViewFormulario(): object
    {

        $nameView = "";
        $view = "";
        $infoGuion = $this->getInfoGuion();
        $nameView = "DY_V_H{$this->getIdHuesped()}_T_FA_G{$this->getIdGuion()}_{$this->aliasColumna($infoGuion->GUION__Nombre____b)}";
        $defaultColumns = $this->getDefaultColumns($infoGuion->GUION__Tipo______b);
        $objDinamicColumns = $this->buildDinamicColumns();


        $tipificacion = array_search("Tipificacion",array_column($objDinamicColumns->arrColumnsSpecial,"name"));
        $hora = array_search("Hora",array_column($objDinamicColumns->arrColumnsSpecial,"name"));
        $campan = array_search("Campaña",array_column($objDinamicColumns->arrColumnsSpecial,"name"));
        $fechaAgenda = array_search("Fecha Agenda",array_column($objDinamicColumns->arrColumnsSpecial,"name"));
        $horaAgenda = array_search("Hora Agenda",array_column($objDinamicColumns->arrColumnsSpecial,"name"));
        $reintento = array_search("Reintento",array_column($objDinamicColumns->arrColumnsSpecial,"name"));
        $Observacion = array_search("Observacion",array_column($objDinamicColumns->arrColumnsSpecial,"name"));


        $view = "CREATE OR REPLACE VIEW `DYALOGOCRM_WEB`.`{$nameView}` AS
                        SELECT 
                            {$defaultColumns[0]}
                            {$objDinamicColumns->arrColumnsSpecial[$reintento]->columnName}
                            {$objDinamicColumns->arrColumnsSpecial[$fechaAgenda]->columnName}
                            {$objDinamicColumns->arrColumnsSpecial[$horaAgenda]->columnName}
                            {$objDinamicColumns->arrColumnsSpecial[$Observacion]->columnName}
                            {$defaultColumns[1]}
                            {$objDinamicColumns->arrColumnsSpecial[$tipificacion]->columnName}
                            {$objDinamicColumns->arrColumnsSpecial[$hora]->columnName}
                            {$objDinamicColumns->arrColumnsSpecial[$campan]->columnName}

                            {$objDinamicColumns->strDinamicColumns} 


                            FROM
                                `DYALOGOCRM_WEB`.`G{$this->getIdGuion()}` `G`
                                LEFT JOIN `DYALOGOCRM_SISTEMA`.`LISOPC` `ug` ON `G`.`{$objDinamicColumns->arrColumnsSpecial[$tipificacion]->campoId}` = `ug`.`LISOPC_ConsInte__b`
                                LEFT JOIN `DYALOGOCRM_SISTEMA`.`MONOEF` `M` ON `ug`.`LISOPC_Clasifica_b` = `M`.`MONOEF_ConsInte__b`
                                {$objDinamicColumns->strJOIN_t}
                            ORDER BY `G`.`G{$this->getIdGuion()}_ConsInte__b` DESC";

        return (object) ["queryView" =>  $view,"nameView" => $nameView];
    }

    private function getDefaultColumns(int $typeGuion): array
    {

        $defaultColumns = [];
        switch ($typeGuion) {
            case 2:
                $defaultColumns[] = " `G`.`G{$this->getIdGuion()}_ConsInte__b` AS `ID`,
                    DATE_FORMAT(`G`.`G{$this->getIdGuion()}_FechaInsercion`,'%Y-%m-%d %H:%i:%s') AS `FECHA_CREACION`,
                    YEAR(`G`.`G{$this->getIdGuion()}_FechaInsercion`) AS `ANHO_CREACION`,
                    MONTH(`G`.`G{$this->getIdGuion()}_FechaInsercion`) AS `MES_CREACION`,
                    DAYOFMONTH(`G`.`G{$this->getIdGuion()}_FechaInsercion`) AS `DIA_CREACION`,
                    HOUR(`G`.`G{$this->getIdGuion()}_FechaInsercion`) AS `HORA_CREACION`,
                    MINUTE(`G`.`G{$this->getIdGuion()}_FechaInsercion`) AS `MINUTO_CREACION`,
                    SECOND(`G`.`G{$this->getIdGuion()}_FechaInsercion`) AS `SEGUNDO_CREACION`,
                    `G`.`G{$this->getIdGuion()}_OrigenUltimoCargue` AS `ORIGEN_ULT_CARGUE`,
                    `G`.`G{$this->getIdGuion()}_FechaUltimoCargue` AS `FEC_ULT_CARGUE`,
                    `dyalogo_general`.`fn_nombre_paso`(`G`.`G{$this->getIdGuion()}_PasoGMI_b`) AS `PASO_GMI`,
                    `LIS`.`LISOPC_Nombre____b` AS `ESTADO_GMI`,
                    `MO2`.`MONOEF_Texto_____b` AS `GESTION_MAS_IMPORTANTE`,
                    `dyalogo_general`.`fn_clasificacion_traduccion`(`G`.`G{$this->getIdGuion()}_ClasificacionGMI_b`) AS `CLASIFICACION_GMI`,
                    `dyalogo_general`.`fn_tipo_reintento_traduccion`(`G`.`G{$this->getIdGuion()}_TipoReintentoGMI_b`) AS `REINTENTO_GMI`,
                    DATE_FORMAT(`G`.`G{$this->getIdGuion()}_FecHorAgeGMI_b`,
                    '%Y-%m-%d %H:%i:%s') AS `FECHA_AGENDA_GMI`,
                    YEAR(`G`.`G{$this->getIdGuion()}_FecHorAgeGMI_b`) AS `ANHO_AGENDA_GMI`,
                    MONTH(`G`.`G{$this->getIdGuion()}_FecHorAgeGMI_b`) AS `MES_AGENDA_GMI`,
                    DAYOFMONTH(`G`.`G{$this->getIdGuion()}_FecHorAgeGMI_b`) AS `DIA_AGENDA_GMI`,
                    HOUR(`G`.`G{$this->getIdGuion()}_FecHorAgeGMI_b`) AS `HORA_AGENDA_GMI`,
                    MINUTE(`G`.`G{$this->getIdGuion()}_FecHorAgeGMI_b`) AS `MINUTO_AGENDA_GMI`,
                    SECOND(`G`.`G{$this->getIdGuion()}_FecHorAgeGMI_b`) AS `SEGUNDO_AGENDA_GMI`,
                    `G`.`G{$this->getIdGuion()}_ComentarioGMI_b` AS `COMENTARIO_GMI`,
                    `USU`.`USUARI_Nombre____b` AS `AGENTE_GMI`,
                    `G`.`G{$this->getIdGuion()}_CanalGMI_b` AS `CANAL_GMI`,
                    `G`.`G{$this->getIdGuion()}_SentidoGMI_b` AS `SENTIDO_GMI`,
                    DATE_FORMAT(`G`.`G{$this->getIdGuion()}_FeGeMaIm__b`,'%Y-%m-%d %H:%i:%s') AS `FECHA_GMI`,
                    YEAR(`G`.`G{$this->getIdGuion()}_FeGeMaIm__b`) AS `ANHO_GMI`,
                    MONTH(`G`.`G{$this->getIdGuion()}_FeGeMaIm__b`) AS `MES_GMI`,
                    DAYOFMONTH(`G`.`G{$this->getIdGuion()}_FeGeMaIm__b`) AS `DIA_GMI`,
                    HOUR(`G`.`G{$this->getIdGuion()}_FeGeMaIm__b`) AS `HORA_GMI`,
                    MINUTE(`G`.`G{$this->getIdGuion()}_FeGeMaIm__b`) AS `MINUTO_GMI`,
                    SECOND(`G`.`G{$this->getIdGuion()}_FeGeMaIm__b`) AS `SEGUNDO_GMI`,
                    (DATE_FORMAT(`G`.`G{$this->getIdGuion()}_FeGeMaIm__b`,'%Y-%m-%d %H:%i:%S') - DATE_FORMAT(`G`.`G{$this->getIdGuion()}_FechaInsercion`,'%Y-%m-%d %H:%i:%S')) AS `DIAS_MADURACION_GMI`,
                    `dyalogo_general`.`fn_nombre_paso`(`G`.`G{$this->getIdGuion()}_PasoUG_b`) AS `PASO_UG`,
                    `LISUG`.`LISOPC_Nombre____b` AS `ESTADO_UG`,
                    `MO1`.`MONOEF_Texto_____b` AS `ULTIMA_GESTION`,
                    `dyalogo_general`.`fn_clasificacion_traduccion`(`G`.`G{$this->getIdGuion()}_ClasificacionUG_b`) AS `CLASIFICACION_UG`,
                    `dyalogo_general`.`fn_tipo_reintento_traduccion`(`G`.`G{$this->getIdGuion()}_TipoReintentoUG_b`) AS `REINTENTO_UG`,
                    DATE_FORMAT(`G`.`G{$this->getIdGuion()}_FecHorAgeUG_b`,
                    '%Y-%m-%d %H:%i:%s') AS `FECHA_AGENDA_UG`,
                    YEAR(`G`.`G{$this->getIdGuion()}_FecHorAgeUG_b`) AS `ANHO_AGENDA_UG`,
                    MONTH(`G`.`G{$this->getIdGuion()}_FecHorAgeUG_b`) AS `MES_AGENDA_UG`,
                    DAYOFMONTH(`G`.`G{$this->getIdGuion()}_FecHorAgeUG_b`) AS `DIA_AGENDA_UG`,
                    HOUR(`G`.`G{$this->getIdGuion()}_FecHorAgeUG_b`) AS `HORA_AGENDA_UG`,
                    MINUTE(`G`.`G{$this->getIdGuion()}_FecHorAgeUG_b`) AS `MINUTO_AGENDA_UG`,
                    SECOND(`G`.`G{$this->getIdGuion()}_FecHorAgeUG_b`) AS `SEGUNDO_AGENDA_UG`,
                    `G`.`G{$this->getIdGuion()}_ComentarioUG_b` AS `COMENTARIO_UG`,
                    `dyalogo_general`.`fn_nombre_USUARI`(`G`.`G{$this->getIdGuion()}_UsuarioUG_b`) AS `AGENTE_UG`,
                    `G`.`G{$this->getIdGuion()}_Canal_____b` AS `CANAL_UG`,
                    `G`.`G{$this->getIdGuion()}_Sentido___b` AS `SENTIDO_UG`,
                    DATE_FORMAT(`G`.`G{$this->getIdGuion()}_FecUltGes_b`,
                    '%Y-%m-%d %H:%i:%s') AS `FECHA_UG`,
                    YEAR(`G`.`G{$this->getIdGuion()}_FecUltGes_b`) AS `ANHO_UG`,
                    MONTH(`G`.`G{$this->getIdGuion()}_FecUltGes_b`) AS `MES_UG`,
                    DAYOFMONTH(`G`.`G{$this->getIdGuion()}_FecUltGes_b`) AS `DIA_UG`,
                    HOUR(`G`.`G{$this->getIdGuion()}_FecUltGes_b`) AS `HORA_UG`,
                    MINUTE(`G`.`G{$this->getIdGuion()}_FecUltGes_b`) AS `MINUTO_UG`,
                    SECOND(`G`.`G{$this->getIdGuion()}_FecUltGes_b`) AS `SEGUNDO_UG`,
                    `G`.`G{$this->getIdGuion()}_LinkContenidoUG_b` AS `CONTENIDO_UG`,
                    `G`.`G{$this->getIdGuion()}_EstadoDiligenciamiento` AS `LLAVE_CARGUE`,
                    (DAYOFMONTH((DATE_FORMAT(`G`.`G{$this->getIdGuion()}_FechaInsercion`,'%Y-%m-%d %H:%i:%S') - DATE_FORMAT(`G`.`G{$this->getIdGuion()}_FecUltGes_b`,'%Y-%m-%d %H:%i:%S'))) - DAYOFMONTH((DATE_FORMAT(`G`.`G{$this->getIdGuion()}_FechaInsercion`,'%Y-%m-%d %H:%i:%S') - DATE_FORMAT(`G`.`G{$this->getIdGuion()}_FeGeMaIm__b`,'%Y-%m-%d %H:%i:%S')))) AS `DIAS_SIN_CONTACTO`,
                    `G`.`G{$this->getIdGuion()}_CantidadIntentos` AS `CANTIDAD_INTENTOS`,
                    `G`.`G{$this->getIdGuion()}_LinkContenidoGMI_b` AS `CONTENIDO_GMI` ";
                break;

            case 1:
                $defaultColumns[] = "
                    `G`.`G{$this->getIdGuion()}_ConsInte__b` AS `ID`,
                    `G`.`G{$this->getIdGuion()}_CodigoMiembro` AS `ID_BD`,
                    DATE_FORMAT(`G`.`G{$this->getIdGuion()}_FechaInsercion`,'%Y-%m-%d %H:%i:%s') AS `FECHA_GESTION`,
                    YEAR(`G`.`G{$this->getIdGuion()}_FechaInsercion`) AS `ANHO_GESTION`,
                    MONTH(`G`.`G{$this->getIdGuion()}_FechaInsercion`) AS `MES_GESTION`,
                    DAYOFMONTH(`G`.`G{$this->getIdGuion()}_FechaInsercion`) AS `DIA_GESTION`,
                    HOUR(`G`.`G{$this->getIdGuion()}_FechaInsercion`) AS `HORA_GESTION`,
                    MINUTE(`G`.`G{$this->getIdGuion()}_FechaInsercion`) AS `MINUTO_GESTION`,
                    SECOND(`G`.`G{$this->getIdGuion()}_FechaInsercion`) AS `SEGUNDO_GESTION`,
                    DATE_FORMAT(`G`.`G{$this->getIdGuion()}_FechaInsercionBD_b`,'%Y-%m-%d %H:%i:%s') AS `FECHA_CREACION`,
                    YEAR(`G`.`G{$this->getIdGuion()}_FechaInsercionBD_b`) AS `ANHO_CREACION`,
                    MONTH(`G`.`G{$this->getIdGuion()}_FechaInsercionBD_b`) AS `MES_CREACION`,
                    DAYOFMONTH(`G`.`G{$this->getIdGuion()}_FechaInsercionBD_b`) AS `DIA_CREACION`,
                    HOUR(`G`.`G{$this->getIdGuion()}_FechaInsercionBD_b`) AS `HORA_CREACION`,
                    MINUTE(`G`.`G{$this->getIdGuion()}_FechaInsercionBD_b`) AS `MINUTO_CREACION`,
                    SECOND(`G`.`G{$this->getIdGuion()}_FechaInsercionBD_b`) AS `SEGUNDO_CREACION`,
                    DATE_FORMAT(`G`.`G{$this->getIdGuion()}_Duracion___b`,'%H:%i:%S') AS `DURACION_GESTION`,
                    TIME_TO_SEC(`G`.`G{$this->getIdGuion()}_Duracion___b`) AS `DURACION_GESTION_SEG`,
                    `dyalogo_general`.`fn_nombre_USUARI`(`G`.`G{$this->getIdGuion()}_Usuario`) AS `AGENTE`,
                    `G`.`G{$this->getIdGuion()}_Sentido___b` AS `SENTIDO`,
                    `G`.`G{$this->getIdGuion()}_Canal_____b` AS `CANAL`,
                    `ug`.`LISOPC_Nombre____b` AS `ULTIMA_GESTION`,
                    `dyalogo_general`.`fn_clasificacion_traduccion`(`G`.`G{$this->getIdGuion()}_Clasificacion`) AS `CLASIFICACION`";

                $defaultColumns[] = ",
                    `G`.`G{$this->getIdGuion()}_LinkContenido` AS `DESCARGAGRABACION`,
                    `G`.`G{$this->getIdGuion()}_Paso` AS `Paso`,
                    `G`.`G{$this->getIdGuion()}_Origen_b` AS `OrigenGestion`,
                    `G`.`G{$this->getIdGuion()}_DatoContacto` AS `DatoContacto`,
                    `dyalogo_telefonia`.`fn_datos_encuesta_resultados_totales`(`G`.`G{$this->getIdGuion()}_IdLlamada`) AS `RESULTADO_ENCUESTAS`";

                break;
        }

        return $defaultColumns;
    }
}
