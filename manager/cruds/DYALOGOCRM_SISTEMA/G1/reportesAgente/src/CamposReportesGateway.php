<?php


class CamposReportesGateway
{
    private PDO $cann;

    public function __construct(Database $database)
    {
        $this->cann = $database->getConnection();
    }

    public function getAll(): array
    {
        # code...
        $sql = "SHOW FIELDS FROM DYALOGOCRM_SISTEMA.CONDIA WHERE field IN('CONDIA_TiemDura__b', 'CONDIA_Fecha_____b', 'CONDIA_ConsInte__CAMPAN_b', 'CONDIA_ConsInte__USUARI_b', 'CONDIA_ConsInte__GUION__Gui_b', 'CONDIA_ConsInte__GUION__Pob_b', 'CONDIA_Observacio_b', 'CONDIA_Canal_b')";

        $stmt = $this->cann->query($sql);
        $data = [];





        while ($row = $stmt->fetch(PDO::FETCH_OBJ)) {
            # code...
            // var_dump($row);
            $row = array(
                "campoId" => $row->Field,
                "nombre" => "",
                "tipo" => $row->Type
            );

            array_push($data, $row);
        }


        return $data;
    }

    public function get(string $id): array
    {
        # id = 2007
        $sql = "SELECT * FROM DYALOGOCRM_SISTEMA.CONDIA WHERE CONDIA_ConsInte__USUARI_b = :id";

        $stmt = $this->cann->prepare($sql);

        $stmt->bindValue(":id", $id, PDO::PARAM_INT);

        $stmt->execute();

        $data = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            # code...
            array_push($data, $row);
        }

        return $data;
    }
}
