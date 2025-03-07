<?php

namespace DataJourney\models;

use DataJourney\models\PasoModel;
use DataJourney\models\GuionModel;

class JourneyModel
{

    private $db = null;
    private $pasoModel = null;
    private $guionModel = null;

    public function __construct($db)
    {
        $this->db = $db;
        $this->pasoModel = new PasoModel($db);
        $this->guionModel = new GuionModel($db);
    }

    public function getData(int $userIdBd, int $idG, string $from = null)
    {

        // SI LA SOLICITUD LLEGA DE UN SCRIPT DEBEMOS DE AVERIGUAR EL G DE LA BASE DE DATOS Y EL CODIGO DE LA POBLACION
        if($this->guionModel->getGuionType($idG) == 1){
            if($from == "estacion"){
                $strGPoblSql = "SELECT ESTRAT_ConsInte_GUION_Pob , G{$idG}_CodigoMiembro FROM DYALOGOCRM_WEB.G{$idG}  JOIN DYALOGOCRM_SISTEMA.ESTPAS ON G{$idG}_Paso = ESTPAS_ConsInte__b JOIN DYALOGOCRM_SISTEMA.ESTRAT ON ESTPAS_ConsInte__ESTRAT_b = ESTRAT_ConsInte__b WHERE G{$idG}_CodigoMiembro = {$userIdBd} LIMIT 1";
            }else{
                $strGPoblSql = "SELECT ESTRAT_ConsInte_GUION_Pob , G{$idG}_CodigoMiembro FROM DYALOGOCRM_WEB.G{$idG}  JOIN DYALOGOCRM_SISTEMA.ESTPAS ON G{$idG}_Paso = ESTPAS_ConsInte__b JOIN DYALOGOCRM_SISTEMA.ESTRAT ON ESTPAS_ConsInte__ESTRAT_b = ESTRAT_ConsInte__b WHERE G{$idG}_ConsInte__b = {$userIdBd} LIMIT 1";
            }

            try {
                $resGPoblSql = $this->db->query($strGPoblSql);
                if ($resGPoblSql) {
                    if ($resGPoblSql->num_rows > 0) {
                        $resGPoblSql = $resGPoblSql->fetch_object();
                        // SE OBTIENE LA INFO DEL PASO
                        $userIdBd = $resGPoblSql->{"G{$idG}_CodigoMiembro"};
                        $idG = $resGPoblSql->ESTRAT_ConsInte_GUION_Pob;
                    }
                } else {
                    $result = [
                        "status" => "error",
                        "message" => $this->db->error
                    ];
                    return $result;
                }
            } catch (\Throwable $th) {
                var_dump($th);
            }
        }

        // NECESITO OBTENER LA INFORMACION DE LOS CAMPOS PRIMARIOS Y SECUNDARIOS DEL REGISTRO

        $priSec = $this->guionModel->getPriSecFields($idG);
        $infoTittle = null;
        if($priSec){
            $infoTittle = $this->guionModel->getPriSecValue($idG, $userIdBd, $priSec);
        }



        $statement = "SELECT G{$idG}_J_ConsInte_b as id, DATE(G{$idG}_J_Fecha_Hora_b) AS fecha, TIME(G{$idG}_J_Fecha_Hora_b) AS hora, G{$idG}_J_Duracion___b AS duracion, dyalogo_general.fn_nombre_USUARI(G{$idG}_J_Agente) as agente, G{$idG}_J_Sentido___b as sentido, G{$idG}_J_Canal_____b as canal, G{$idG}_J_DatoContacto as datoContacto, dyalogo_general.fn_item_lisopc(G{$idG}_J_Tipificacion_b)  as tipificacion, dyalogo_general.fn_clasificacion_traduccion(G{$idG}_J_Clasificacion_b) as clasificacion, dyalogo_general.fn_tipo_reintento_traduccion(G{$idG}_J_TipoReintento_b) as reintento, G{$idG}_J_Comentario_b as comentario, G{$idG}_J_LinkContenido_b as link, G{$idG}_J_IdPaso as idPaso FROM DYALOGOCRM_WEB.G{$idG}_J Where G{$idG}_J_ConsInte_Miembro_Pob_b = {$userIdBd} ORDER BY G{$idG}_J_Fecha_Hora_b ASC";


        try {
            $resullSql = $this->db->query($statement);
            if ($resullSql) {
                if ($resullSql->num_rows > 0) {
                    $data = [];
                    while ($resJourney = $resullSql->fetch_array(MYSQLI_ASSOC)) {
                        // SE OBTIENE LA INFO DEL PASO
                        $resJourney["pasoInfo"] = $this->pasoModel->getDataPaso($resJourney["idPaso"]);
                        array_push($data, $resJourney);
                    }
                    $result = [
                        "status" => "ok",
                        "message" => $data,
                        "infoTittle" => $infoTittle
                    ];
                } else {
                    $result = [
                        "status" => "error",
                        "message" => "noInfo"
                    ];
                }
            } else {
                $result = [
                    "status" => "error",
                    "message" => $this->db->error
                ];
            }

            return $result;
        } catch (\Throwable $th) {
            var_dump($th);
        }
    }
}
