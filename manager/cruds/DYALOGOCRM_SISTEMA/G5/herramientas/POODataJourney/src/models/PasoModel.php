<?php

namespace DataJourney\models;

class PasoModel
{

    private $db = null;

    public function __construct($db)
    {
        $this->db = $db;
    }

    public function getDataPaso($paso)
    {
        $statement = "SELECT ESTPAS_Comentari_b AS nombre, ESTPAS_Tipo______b AS tipo FROM DYALOGOCRM_SISTEMA.ESTPAS WHERE ESTPAS_ConsInte__b = {$paso};";

        try {
            $resullSql = $this->db->query($statement);
            if ($resullSql) {
                if ($resullSql->num_rows > 0) {
                    $result = $resullSql->fetch_object();
                } else {
                    $result = [
                        "status" => "error",
                        "message" => "paso no existe"
                    ];
                }
            } else {
                $result = [
                    "status" => "error",
                    "message" => $this->db->error
                ];
            }
        } catch (\Throwable $th) {
            var_dump($th);
        }
        return $result;
    }
}
