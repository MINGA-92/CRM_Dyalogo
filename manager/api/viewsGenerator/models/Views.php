<?php


class Views
{
    protected static $db;
    private $intIdHuesped;
    private $intIdCampan;
    private $strTypeView;
    private $intIdEstrat;
    private $intIdProyecto;
    private $intIdPaso;
    private $infoPaso;
    private $infoEstrat;
    private $intIdGuion;
    private $objInfoGuion;
    private $objInfoCampan;
    private $intIdMuestra;


    public function __construct()
    {
        self::$db = Helpers::connect();
    }

    public function getIdHuesped(): int
    {
        return $this->intIdHuesped;
    }

    public function setIdHuesped(int $id): void
    {
        $this->intIdHuesped = self::$db->real_escape_string($id);
    }

    public function getIdCampan(): int
    {
        return $this->intIdCampan;
    }

    public function setIdCampan(int $id): void
    {
        $this->intIdCampan = self::$db->real_escape_string($id);
    }

    public function getTypeView(): string
    {
        return $this->strTypeView;
    }

    public function setTypeView(string $typeView): void
    {
        $this->strTypeView = self::$db->real_escape_string($typeView);
    }

    public function getIdEstrat(): ?int
    {
        return $this->intIdEstrat;
    }

    public function setIdEstrat(int $id): void
    {
        $this->intIdEstrat = self::$db->real_escape_string($id);
    }

    public function getIdPaso(): ?int
    {
        return $this->intIdPaso;
    }

    public function setIdPaso(int $id): void
    {
        $this->intIdPaso = self::$db->real_escape_string($id);
    }

    public function getIdGuion(): ?int
    {
        return $this->intIdGuion;
    }

    public function setIdGuion(int $id): void
    {
        $this->intIdGuion = self::$db->real_escape_string($id);
    }


    public function getIdMuestra(): ?int
    {
        return $this->intIdMuestra;
    }

    public function setIdMuestra(int $id): void
    {
        $this->intIdMuestra = self::$db->real_escape_string($id);
    }

    //INICIO METODOS


    // MOSTRAR ERROR CUANDO EL SISTEMA NO PUEDA COMPLETAR UNA SOLICITUD
    protected function error($messageError = "", $query = null): void
    {
        if ($messageError != "") {
            $finalMessage = ["error" => $messageError];
            if ($query != null) {
                $finalMessage["query"] = $query;
            }
            showResponse($finalMessage, false, 500, "500 Internal Server Error");
        } else {
            showResponse("No se pudo completar la solicitud.", false, 500, "500 Internal Server Error");
        }
    }

    protected function getIdProyecto(): ?int
    {
        try {
            if ($this->intIdProyecto == null) {
                $query = "SELECT id FROM dyalogo_telefonia.dy_proyectos WHERE id_huesped = {$this->intIdHuesped}";
                $sql = self::$db->query($query);
                if ($sql && $sql->num_rows == 1) {
                    $sql = $sql->fetch_object();
                    $this->intIdProyecto = $sql->id;
                } else {
                    $this->error(self::$db->error, $query);
                }
            }

            return $this->intIdProyecto;
        } catch (\Throwable $th) {
            $this->error($th->getMessage());
        }
    }

    // ESTE METODO GUARDA LA VISTA GENERADA ANTERIORMENTE EN vistas_generadas
    protected function saveViewGenerated(string $nombre, int $id_guion = null, int $id_campan = null, int $id_estrat = null, string $searshName = null): int
    {
        try {
            $idView = $this->verifyViewGenerated($this->getIdHuesped(), $id_guion, $id_campan, $id_estrat, $searshName);
            // PRIMERO VALIDAMOS SI LA VISTA YA EXISTE
            if (!$idView) {
                $id_campan = ($id_campan != null) ? $id_campan : 'NULL' ;
                $id_estrat = ($id_estrat != null) ? $id_estrat : 'NULL' ;
                if($id_guion == null){
                    // Si el guion viene nulo quiere decir que insertamos una vista
                    $sqlMinGuion = self::$db->query("SELECT MIN(id_guion) -1 AS id_guion FROM dyalogo_general.vistas_generadas");
                    if($sqlMinGuion){
                        $id_guion = $sqlMinGuion->fetch_object()->id_guion;
                    }
                }

                $query = "INSERT INTO dyalogo_general.vistas_generadas (id_guion, nombre, id_huesped, id_campan, id_estrat) VALUES ({$id_guion}, '{$nombre}', {$this->getIdHuesped()} , {$id_campan} , {$id_estrat} )";
                $sql = self::$db->query($query);
                if ($sql) {
                    $idInsertado = self::$db->insert_id;
                    return $idInsertado;
                } else {
                    $this->error(self::$db->error, $query);
                }
            } else {
                // SI YA EXISTE SOLO ME INTERESA ACTUALIZAR EL NOMBRE DE LA VISTA
                $query = "UPDATE dyalogo_general.vistas_generadas SET nombre = '{$nombre}' WHERE id = {$idView}";
                $sql = self::$db->query($query);
                if ($sql) {
                    return $idView;
                } else {
                    $this->error(self::$db->error, $query);
                }
            }
        } catch (\Throwable $th) {
            $this->error($th->getMessage());
        }
    }

    // METODO PRA VALIDAR SI LA VISTA YA EXISTIA ANTERIORMENTE
    protected function verifyViewGenerated($id_huesped, $id_guion = null, $id_campan = null, $id_estrat = null, $nombre = null): int
    {
        try {
            $where = "";
            $separador = "";

            if ($id_huesped != null) {
                $where .= $separador . " id_huesped = '" . $id_huesped . "'";
                $separador = " AND ";
            }
            if ($id_guion != null) {
                $where .= $separador . " id_guion = '" . $id_guion . "'";
                $separador = " AND ";
            }
            if ($id_campan != null) {
                $where .= $separador . " id_campan = '" . $id_campan . "'";
                $separador = " AND ";
            }
            if ($id_estrat != null) {
                $where .= $separador . " id_estrat = '" . $id_estrat . "'";
                $separador = " AND ";
            }
            if ($nombre != null) {
                $where .= $separador . " nombre like '%" . $nombre . "%'";
                $separador = " AND ";
            }

            $query = "SELECT id FROM dyalogo_general.vistas_generadas WHERE {$where};";
            $sql = self::$db->query($query);
            if ($sql) {
                if ($sql->num_rows > 0) {
                    $sql = $sql->fetch_object();
                    return $sql->id;
                } else {
                    return 0;
                }
            } else {
                $this->error(self::$db->error, $query);
            }
        } catch (\Throwable $th) {
            $this->error($th->getMessage());
        }
    }


    protected function aliasColumna($strNombre_p)
    {

        $strNombreCorregido_p = preg_replace("/\s+/", "_", $strNombre_p);

        $strNombreCorregido_p = preg_replace('([^0-9a-zA-Z_])', "", $strNombreCorregido_p);

        $strNombreCorregido_p = strtoupper($strNombreCorregido_p);

        $strNombreCorregido_p = rtrim($strNombreCorregido_p, "_");

        $strNombreCorregido_p = substr($strNombreCorregido_p, 0, 40);

        return $strNombreCorregido_p;
    }

    protected function getInfoPaso(): ?object
    {
        try {
            if ($this->infoPaso == null) {
                $query = "SELECT * FROM DYALOGOCRM_SISTEMA.ESTPAS where ESTPAS_ConsInte__b = {$this->getIdPaso()} LIMIT 1";
                $sql = self::$db->query($query);
                if ($sql) {
                    if ($sql->num_rows > 0) {
                        $this->infoPaso = $sql->fetch_object();
                    }
                } else {
                    $this->error(self::$db->error, $query);
                }
            }

            return $this->infoPaso;
        } catch (\Throwable $th) {
            $this->error($th->getMessage());
        }
    }

    protected function getInfoEstrat(): ?object
    {
        try {

            if ($this->infoEstrat == null) {
                $query = "SELECT ESTRAT_Nombre____b,ESTRAT_ConsInte_GUION_Pob  FROM DYALOGOCRM_SISTEMA.ESTRAT WHERE ESTRAT_ConsInte__b  = {$this->getIdEstrat()} LIMIT 1";
                $sql = self::$db->query($query);
                if ($sql) {
                    if ($sql->num_rows > 0) {
                        $this->infoEstrat = $sql->fetch_object();
                    }
                } else {
                    $this->error(self::$db->error, $query);
                }
            }

            return $this->infoEstrat;
        } catch (\Throwable $th) {
            $this->error($th->getMessage());
        }
    }


    protected function getInfoGuion(): ?object
    {
        try {
            if ($this->objInfoGuion == null) {
                $query = "SELECT GUION__Nombre____b, GUION__Tipo______b FROM DYALOGOCRM_SISTEMA.GUION_ WHERE GUION__ConsInte__b = {$this->getIdGuion()} LIMIT 1";
                $sql = self::$db->query($query);
                if ($sql) {
                    if ($sql->num_rows > 0) {
                        $this->objInfoGuion = $sql->fetch_object();
                    }
                } else {
                    $this->error(self::$db->error, $query);
                }
            }

            return $this->objInfoGuion;
        } catch (\Throwable $th) {
            $this->error($th->getMessage());
        }
    }

    protected function getInfoCampan(): ?object
    {
        try {
            if ($this->objInfoCampan == null) {
                $query = "SELECT CAMPAN_Nombre____b,CAMPAN_ConsInte__GUION__Gui_b, CAMPAN_ConsInte__GUION__Pob_b, CAMPAN_ConsInte__MUESTR_b FROM DYALOGOCRM_SISTEMA.CAMPAN WHERE CAMPAN_ConsInte__b = {$this->getIdCampan()} LIMIT 1";
                $sql = self::$db->query($query);
                if ($sql) {
                    if ($sql->num_rows > 0) {
                        $this->objInfoCampan = $sql->fetch_object();
                    }
                } else {
                    $this->error(self::$db->error, $query);
                }
            }

            return $this->objInfoCampan;
        } catch (\Throwable $th) {
            $this->error($th->getMessage());
        }
    }


    protected function buildDinamicColumns()
    {

        $strDinamicColumns = "";
        $strJOIN_t = "";
        $objDinamicColumns_t = $this->getDinamicColumns();


        if (count($objDinamicColumns_t) > 0) {

            //Primero que todo necesitamos traer las referencias de las listas auxiliares para evitar repetir nombres
            $arrAuxList = array_filter($objDinamicColumns_t, function ($v) {
                return $v->tipo == "11";
            });
            foreach ($arrAuxList as $value) {
                foreach ($this->getRefList($value->ID) as $refvalue) {
                    array_push($objDinamicColumns_t, $refvalue);
                }
            }

            // Antes de crear los columnas de las vistas primero necesito crear los alias
            $objDinamicColumns_t = array_map(function (object $obj) {
                $obj->{"alias"} = $this->aliasColumna($obj->nombre);
                return $obj;
            }, $objDinamicColumns_t);

            //Ahora tenemos que validar que no hayan alias repetidos
            $dupAlias = $this->validDuplicateAlias($objDinamicColumns_t);
            $objDinamicColumns_t = $this->replaceAliasDup($dupAlias, $objDinamicColumns_t);

            // Ya teniendo creado los alias ahora traemos las sentecias de las columnas de mysql y los joins que necesitemos
            $objDinamicColumnsFinal =  $this->translateColumn($objDinamicColumns_t);
            if (count($objDinamicColumnsFinal->arrFinal) > 0) {
                foreach ($objDinamicColumnsFinal->arrFinal as $dinamicColumnsVal) {
                    $strDinamicColumns .= $dinamicColumnsVal->columnName;
                    $strJOIN_t .= $dinamicColumnsVal->join;
                }
            }
        }

        return (object)["strDinamicColumns" => $strDinamicColumns, "strJOIN_t" => $strJOIN_t, "arrColumnsSpecial" => $objDinamicColumnsFinal->arrColumnsSpecial];
    }


    protected function getDinamicColumns(): array
    {
        try {
            $objDinamicColumns_t = [];
            $query = "SELECT PREGUN_ConsInte__b AS ID, CONCAT('G',PREGUN_ConsInte__GUION__b,'_C',PREGUN_ConsInte__b) AS campoId, IF(PREGUN_Texto_____b = '', 'COLUMNA_SIN_NOMBRE', PREGUN_Texto_____b) AS nombre, PREGUN_Tipo______b AS tipo, SECCIO_TipoSecc__b AS seccion, PREGUN_ConsInte__GUION__PRE_B AS glista FROM DYALOGOCRM_SISTEMA.PREGUN LEFT JOIN DYALOGOCRM_SISTEMA.SECCIO ON PREGUN_ConsInte__SECCIO_b = SECCIO_ConsInte__b WHERE PREGUN_ConsInte__GUION__b = {$this->getIdGuion()} AND PREGUN_Tipo______b NOT IN (9,12,16,17) ORDER BY PREGUN_OrdePreg__b ASC";
            $sql = self::$db->query($query);
            if ($sql) {
                if ($sql->num_rows > 0) {
                    while ($obj = $sql->fetch_object()) {
                        $objDinamicColumns_t[] = $obj;
                    }
                }
            } else {
                $this->error(self::$db->error, $query);
            }

            return $objDinamicColumns_t;
        } catch (\Throwable $th) {
            $this->error($th->getMessage());
        }
    }


    // ESTE METODO ME AYUDA A VALIDAR QUE ALIAS TENEMOS REPETIDOS ASI PODEMOS PONER LA NUMERACION RESPECTIVA
    protected function validDuplicateAlias(array $arrDinamicColumns): array
    {

        $arrAlias = array_column($arrDinamicColumns, 'alias');
        $countAlias = array_count_values($arrAlias);

        $aliasDup = [];
        foreach ($countAlias as $alias => $count) {
            if ($count > 1) {
                $aliasDup[] = $alias;
            }
        }

        $indexDup = [];
        foreach ($arrDinamicColumns as $index => $column) {
            if (in_array($column->alias, $aliasDup)) {
                $indexDup[$column->alias][] = $index;
            }
        }

        return $indexDup;
    }

    // ESTE METODO NOS AYUDA A REMPLAZAR LOS ALIAS QUE TENEMOS IGUALES, PONIENDOLE AL FINAL UN NUMERO CONSECUTIVO
    protected function replaceAliasDup(array $dupAlias, array $objDinamicColumns_t): array
    {
        if (count($dupAlias) > 0) {
            foreach ($dupAlias as $alias => $arrIndexs) {
                $counter = 1;
                foreach ($arrIndexs as $indexInObjDinamicColumn) {
                    $objDinamicColumns_t[$indexInObjDinamicColumn]->alias = $alias . "_" . $counter;
                    $counter++;
                }
            }
        }

        return $objDinamicColumns_t;
    }

    // ESTE METODO TOMA LAS COLUMNAS DINAMICAS PARA TRADUCIRLO A SQL
    protected function translateColumn(array $objDinamicColumns_t): object
    {
        $arrFinal = [];
        $arrColumnsSpecial = [];
        $counterJoins = 0;

        foreach ($objDinamicColumns_t as $key => $columnObj) {
            $strColumn = "";
            $strJoin = "";
            // Se excluyen los objetos de tipo ref, ya que son las referencias de las listas auxiliares
            if ($columnObj->tipo != "ref") {

                if ($columnObj->seccion != 3 && $columnObj->seccion != 4) {
                    switch ($columnObj->tipo) {
                        case '5':
                            // Tipo 5 es una fecha
                            $strColumn = ",DATE_FORMAT(`G`.`{$columnObj->campoId}`, '%Y-%m-%d') AS `FECHA_{$columnObj->alias}`,YEAR(`G`.`{$columnObj->campoId}`) AS `ANHO_{$columnObj->alias}`,MONTH(`G`.`{$columnObj->campoId}`) AS `MES_{$columnObj->alias}`,DAYOFMONTH(`G`.`{$columnObj->campoId}`) AS `DIA_{$columnObj->alias}`";
                            break;
                        case '6':
                        case '13':
                            // Tipo 6 es una lista
                            $strColumn = ", `dyalogo_general`.`fn_item_lisopc`(`G`.`{$columnObj->campoId}`) AS `{$columnObj->alias}`";
                            break;
                        case '10':
                            // Tipo 10 es una hora
                            $strColumn = ",HOUR(`G`.`{$columnObj->campoId}`) AS `HORA_{$columnObj->alias}`,MINUTE(`G`.`{$columnObj->campoId}`) AS `MINUTO_{$columnObj->alias}`,SECOND(`G`.`{$columnObj->campoId}`) AS `SEGUNDO_{$columnObj->alias}`";
                            break;
                        case '11':
                            // Tipo 11 es una lista auxiliar, necesitamos filtrar el array original los campos de tipo ref, que pertenecen a este campo
                            $counterJoins++;

                            $arrRefColumns = array_filter($objDinamicColumns_t, function ($v) use ($columnObj) {
                                return $v->ID == "ref" . $columnObj->ID;
                            });

                            foreach ($arrRefColumns as $refValue) {
                                $strColumn .= ",`T{$counterJoins}`.`{$refValue->campoId}` AS `{$refValue->alias}`";
                            }

                            $strJoin = " LEFT JOIN `DYALOGOCRM_WEB`.`G{$columnObj->glista}` `T{$counterJoins}` ON ((`G`.`{$columnObj->campoId}` = `T{$counterJoins}`.`G{$columnObj->glista}_ConsInte__b`)) ";

                            break;

                        default:
                            // Los demas tipos son campos solo de texto
                            $strColumn = ",`G`.`{$columnObj->campoId}` AS `{$columnObj->alias}`";
                            break;
                    }

                    array_push($arrFinal, (object)["columnName" => $strColumn, "join" => $strJoin]);
                }


                if ($columnObj->seccion == 3 || $columnObj->seccion == 4) {


                    switch ($columnObj->nombre) {
                        case 'Tipificación':
                        case 'Tipificacion':
                        case 'ESTADO_DY':
                            $strColumn = ", `dyalogo_general`.`fn_item_lisopc`(`G`.`{$columnObj->campoId}`) AS `{$columnObj->alias}`";
                            $columnObj->nombre = ($columnObj->nombre == "Tipificación") ? "Tipificacion" : $columnObj->nombre;
                            break;

                        case 'Reintento':
                            $strColumn = ",`dyalogo_general`.`fn_tipo_reintento_traduccion`(`G`.`{$columnObj->campoId}`) AS `{$columnObj->alias}`";
                            break;

                        case 'Fecha Agenda':
                        case 'Agenda':
                            $strColumn = ",DATE_FORMAT(`G`.`{$columnObj->campoId}`, '%Y-%m-%d') AS `FECHA_AGENDA`,YEAR(`G`.`{$columnObj->campoId}`) AS `ANHO_AGENDA`,MONTH(`G`.`{$columnObj->campoId}`) AS `MES_AGENDA`,DAYOFMONTH(`G`.`{$columnObj->campoId}`) AS `DIA_AGENDA`";
                            $columnObj->nombre = "Fecha Agenda";
                            break;
                        case 'Hora Agenda':
                            $strColumn = ",HOUR(`G`.`{$columnObj->campoId}`) AS `HORA_AGENDA`,MINUTE(`G`.`{$columnObj->campoId}`) AS `MINUTO_AGENDA`,SECOND(`G`.`{$columnObj->campoId}`) AS `SEGUNDO_AGENDA`";
                            break;

                        case "Observacion":
                        case "Hora":
                        case "Campaña":
                        case "ORIGEN_DY_WF":
                        case "OPTIN_DY_WF":
                            $strColumn = ",`G`.`{$columnObj->campoId}` AS `{$columnObj->alias}`";
                            break;
                    }

                    if ($strColumn != '') array_push($arrColumnsSpecial, (object)["columnName" => $strColumn, "join" => $strJoin, "name" => $columnObj->nombre, "campoId" => $columnObj->campoId]);
                }
            }
        }
        return (object)["arrFinal" => $arrFinal, "arrColumnsSpecial" => $arrColumnsSpecial];
    }

    // ESTE METODO TRADUCE EL TIPO DEL PASO PARA LAS MUESTRAS
    protected function translateTypeStep(int $typeStep): string
    {
        $strTypeStep = "";
        switch ($typeStep) {
            case 1:
                $strTypeStep = "Llamadas-IN";
                break;

            case 6:
                $strTypeStep = "Llamadas-OUT";
                break;

            case 7:
                $strTypeStep = "Email-OUT";
                break;

            case 8:
                $strTypeStep = "SMS-OUT";
                break;

            case 9:
                $strTypeStep = "BACKOFFICE";
                break;

            case 13:
                $strTypeStep = "Whatsapp-OUT";
                break;

            case 22:
                $strTypeStep = "Marcador-OUT";
                break;
        }

        return $strTypeStep;
    }


    // Este metodo trae la configuracion de las listas auxiliares
    protected function getRefList(string $idCampo)
    {
        try {
            $arrRefColumns = [];
            if ($idCampo != null) {
                $query = "SELECT CAMPO__Nombre____b as campoId, PREGUN_Texto_____b AS nombre FROM DYALOGOCRM_SISTEMA.PREGUI JOIN DYALOGOCRM_SISTEMA.CAMPO_ C ON CAMPO__ConsInte__b =  PREGUI_ConsInte__CAMPO__b JOIN DYALOGOCRM_SISTEMA.PREGUN ON CAMPO__ConsInte__PREGUN_b = PREGUN_ConsInte__b where PREGUI_ConsInte__PREGUN_b = {$idCampo} ORDER BY PREGUI_ConsInte__b";
                $sql = self::$db->query($query);
                if ($sql) {
                    if ($sql->num_rows > 0) {
                        while ($obj = $sql->fetch_object()) {
                            $obj->{"tipo"} = "ref";
                            $obj->nombre = "ref " . $obj->nombre;
                            $obj->ID = "ref" . $idCampo;
                            $arrRefColumns[] = $obj;
                        }
                    }
                } else {
                    $this->error(self::$db->error, $query);
                }
            }

            return $arrRefColumns;
        } catch (\Throwable $th) {
            $this->error($th->getMessage());
        }
    }

    // ESTE METODO ES PARA GENERAR LAS VISTAS, PERO DESDE AQUI NO FUNCIONA ES NECESARIO SOBREESCRBIRLO EN LAS CLASES EN LAS QUE SE HEREDE
    public function generateViews(): array
    {
        try {
            (array)$response = array();
            $response['estado'] = 'error';

            return $response;
        } catch (\Throwable $th) {
            $this->error($th->getMessage());
        }
    }
}
