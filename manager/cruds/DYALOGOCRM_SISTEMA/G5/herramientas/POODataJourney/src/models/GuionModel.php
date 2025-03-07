<?php

namespace DataJourney\models;

class GuionModel
{

    private $db = null;

    public function __construct($db)
    {
        $this->db = $db;
    }

    public function getPriSecFields($idG)
    {
        $statement = "select GUION__ConsInte__PREGUN_Pri_b AS PRI, GUION__ConsInte__PREGUN_Sec_b AS SEC from DYALOGOCRM_SISTEMA.GUION_ WHERE GUION__ConsInte__b = {$idG}";

        try {
            $resullSql = $this->db->query($statement);
            if ($resullSql) {
                if ($resullSql->num_rows > 0) {
                    $result = $resullSql->fetch_array(MYSQLI_ASSOC);
                } else {
                    $result = false;
                }
            } else {
                $result = false;
            }
        } catch (\Throwable $th) {
            var_dump($th);
        }
        return $result;
    }


    public function getPriSecValue($idG, $idUser, $priSec)
    {
        $statement = "SELECT G{$idG}_C{$priSec["PRI"]} as PRI, G{$idG}_C{$priSec["SEC"]} AS SEC FROM DYALOGOCRM_WEB.G{$idG} where G{$idG}_ConsInte__b = {$idUser}";

        try {
            $resullSql = $this->db->query($statement);
            if ($resullSql) {
                if ($resullSql->num_rows > 0) {
                    $result = $resullSql->fetch_array(MYSQLI_ASSOC);
                } else {
                    $result = null;
                }
            } else {
                $result = null;
            }
        } catch (\Throwable $th) {
            var_dump($th);
        }
        return $result;
    }

    public function getGuionType($idG){
        $statement = "select GUION__Tipo______b AS tipo from DYALOGOCRM_SISTEMA.GUION_ WHERE GUION__ConsInte__b = {$idG};";

        try {
            $resullSql = $this->db->query($statement);
            if ($resullSql) {
                if ($resullSql->num_rows > 0) {
                    $result = $resullSql->fetch_array(MYSQLI_ASSOC);
                    $result = $result["tipo"];
                } else {
                    $result = null;
                }
            } else {
                $result = null;
            }
        } catch (\Throwable $th) {
            var_dump($th);
        }
        return $result;
    }

    
}
