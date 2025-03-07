<?php

class ActividadActual
{
    protected static $db;

    public function __construct()
    {
        self::$db = Helpers::connect();
    }


    // Funcion que me retorna la info de la campaña buscandolo por el id

    public function getAllActivity()
    {
        $res = [];

        $strSQLActivity = "SELECT a.*, USUARI_Foto______b FROM dyalogo_general.actividad_actual a join DYALOGOCRM_SISTEMA.USUARI u on USUARI_UsuaCBX___b = id_usuario;";
        $resSQLActivity = self::$db->query($strSQLActivity);
        if ($resSQLActivity) {
            if ($resSQLActivity->num_rows > 0) {
                while ($objActivity = $resSQLActivity->fetch_object()) {
                    // Hacemos unos casteos y cambios de nombre en el objeto

                    $jsonAgen = (object)[
                        "id" => (int)$objActivity->id,
                        "idHuesped" => (int)$objActivity->id_huesped,
                        "idUsuario" => (int)$objActivity->id_usuario,
                        "nombreUsuario" => $objActivity->nombre_usuario,
                        "identificacionUsuario" => $objActivity->identificacion_usuario,
                        "estado" => $objActivity->estado,
                        "canalActual" => $objActivity->canal_actual,
                        "campanaActual" => $objActivity->campana_actual,
                        "pausa" => $objActivity->pausa,
                        "fechaHoraCambioEstado" => $objActivity->fecha_hora_cambio_estado,
                        "fechaHoraInicioGestion" => $objActivity->fecha_hora_inicio_gestion,
                        "idComunicacion" => $objActivity->id_comunicacion,
                        "datoPrincipal" => $objActivity->dato_principal,
                        "datoSecundario" => $objActivity->dato_secundario,
                        "sentido" => $objActivity->sentido,
                        "enConversacion" => ($objActivity->en_conversacion == "1") ? true : false,
                        "strFechaHoraCambioEstado_t" => $objActivity->fecha_hora_cambio_estado,
                        "strFechaHoraInicioGestion_t" => $objActivity->fecha_hora_inicio_gestion,
                        "strUSUARIFoto_t" => ($objActivity->USUARI_Foto______b != null && $objActivity->USUARI_Foto______b != "") ? $objActivity->USUARI_Foto______b : "Kakashi.fw.png"
                    ];

                    array_push($res, $jsonAgen);
                }
            }
        }

        return (count($res) > 0) ? $res : false;
    }



    // Funcion que me retorna el id de la campaña, buscandolo por nombre y id selecctivos

    public function getActualActivityCampan(): array
    {
        $res = [];

        $strSQLActivity = 'SELECT id_campana, nombre_interno, id_campana_crm FROM dyalogo_telefonia.dy_actividad_actual_campanas a join dyalogo_telefonia.dy_campanas c on c.id = a.id_campana  where tipo_campana = 1  group by id_campana ';

        $resSQLActivity = self::$db->query($strSQLActivity);
        if ($resSQLActivity) {
            if ($resSQLActivity->num_rows > 0) {
                while ($objActivity = $resSQLActivity->fetch_object()) {
                    array_push($res, $objActivity);
                }
            }
        }

        return (count($res) > 0) ? $res : false;
    }
}
