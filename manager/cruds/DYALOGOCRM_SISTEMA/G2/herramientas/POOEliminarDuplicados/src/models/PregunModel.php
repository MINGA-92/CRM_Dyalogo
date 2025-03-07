<?php

namespace Dyalogo\Script\models;

use Dyalogo\Script\models\Models;

class PregunModel extends Models
{

    public function __construct()
    {
        parent::__construct();
    }

    // obtengo los campos pregun por id, de la base datos
    public function getPregun(int $id): array
    {
        # code...

        // echo "pregun $id"; 

        $consulta = "SELECT PREGUN_Tipo______b AS id_tipo, PREGUN_ConsInte__GUION__b AS id_guion, PREGUN_ConsInte__b AS id_pregun, PREGUN_Texto_____b AS nombre FROM DYALOGOCRM_SISTEMA.PREGUN WHERE PREGUN_ConsInte__GUION__b = {$id} AND PREGUN_Tipo______b <> 1 AND PREGUN_Tipo______b <> 6 ";

        $sql = mysqli_query($this->mysqli, $consulta);

        if ($sql && mysqli_num_rows($sql)) {

            $result = mysqli_fetch_all($sql, MYSQLI_ASSOC);
        } else {
            $result = [];
        }

        return $result;
    }
}
