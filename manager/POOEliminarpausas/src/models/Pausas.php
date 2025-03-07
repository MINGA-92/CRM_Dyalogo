<?php

namespace Dyalogo\Eliminarpausas\models;

use Dyalogo\Eliminarpausas\utils\Uuid;
use Dyalogo\Eliminarpausas\utils\Mysql;
use mysqli;

class Pausas extends Proyecto
{
    private $pausas;

    function __construct()
    {
        $this->pausas = array();
        $this->uuId = Uuid::getUuid();
    }

    public function getPausas()
    {
        $this->obtenerPausas();
        return $this->pausas;
    }

    public function setPausas(array $pausas)
    {
        // $this->pausas = $pausas;
        array_push($this->pausas, $pausas);
    }

    private function obtenerPausas()
    {
        $contador = 0;
        foreach ($this->getProyectos() as $value) {

            $consulta = "SELECT COUNT(tipo) AS veces, id_proyecto, id, tipo FROM dyalogo_telefonia.dy_tipos_descanso WHERE id_proyecto = '{$value['id']}' GROUP BY tipo HAVING veces > 1";

            echo $consulta;
            echo "\n\n ------------------------------- \n\n";

            echo "\n\n contador ", $contador++, "\n\n";

            $sql = mysqli_query(Mysql::mysqli(), $consulta);
            if ($sql && mysqli_num_rows($sql) > 0) {
                $result = mysqli_fetch_all($sql, MYSQLI_ASSOC);
                echo "\n\n result=>", json_encode($result), "\n\n";
                $this->setPausas($result);
            }
        }
    }


    public function unificarPausas(array $duplicadas)
    {
        $contador = 0;

        // echo "\n duplicadas=>", json_encode($duplicadas), "\n\n";


        foreach ($duplicadas as $pausas) {

            // echo "\n\n empty(value->tipo)", empty($value->tipo) == true ? "esta vacio" : "lle no", "\n\n";
            // echo "\n value =>", json_encode($value), "\n\n";
            // echo "\n\n contador ", $contador++, "\n\n";
            // $this->actulizarPausas($value);  

            array_filter(
                $pausas,
                function ($pausa) {
                    // echo "\n pausa =>", json_encode($pausa), "\n\n";
                    $pausa['tipo'] === "" ? $this->eliminarPausas($pausa) : $this->actulizarPausas($pausa);
                }
            );
        }
    }

    public function actulizarPausas(array $pausa)
    {
        // echo "\n actulizarPausas->pausa =>", json_encode($pausa), "\n\n";

        $consulta = "UPDATE dyalogo_telefonia.dy_tipos_descanso SET tipo = '{$pausa['tipo']}{$this->uuId}' WHERE id_proyecto = '{$pausa['id_proyecto']}' AND tipo = '{$pausa['tipo']}' AND id <> '{$pausa['id']}'";

        // echo "\n actulizarPausas->consulta=> $consulta \n\n";
        // echo "\n\n ------------------------------- \n\n";


        echo mysqli_query(Mysql::mysqli(), $consulta) == true ?  $this->menssage($consulta, true) : $this->menssage($consulta, false);
    }

    private function eliminarPausas(array $pausa)
    {
        // echo "\n eliminarPausas->pausa =>", json_encode($pausa), "\n\n";
        $consulta = "DELETE FROM dyalogo_telefonia.dy_tipos_descanso WHERE tipo = '{$pausa['tipo']}' AND id_proyecto = '{$pausa['id_proyecto']}'";

        // echo "\n eliminarPausas->consulta=> $consulta \n\n";
        // echo "\n\n ------------------------------- \n\n";

        echo mysqli_query(Mysql::mysqli(), $consulta) == true ? $this->menssage($consulta, true) : $this->menssage($consulta, false);
    }


    public function menssage(string $consulta, bool $value): string
    {
        # code...
        if ($value) {
            $menssage = "\n\n La consulta se ejecuto correctamente: \n {$consulta} \n";
        } else {
            $menssage = "\n\n La consulta fallo \n {$consulta} \n";
        }
        return $menssage;
    }
}
