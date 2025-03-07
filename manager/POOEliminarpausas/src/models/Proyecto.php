<?php

namespace Dyalogo\Eliminarpausas\models;

use Dyalogo\Eliminarpausas\utils\Mysql;


class Proyecto
{
    private $proyectos;
    private $mysqli;

    function __construct()
    {
        var_dump("Se creo un nuevo objecto mysqli \n ", Mysql::mysqli(), "\n\n");

        $this->mysqli = Mysql::mysqli();
        $this->proyectos = [];
    }

    public function getProyectos(): array
    {
        $this->obtenerProyectos();
        return $this->proyectos;
    }


    public function setPoryectos(array $proyectos)
    {
        $this->proyectos = $proyectos;
    }

    public function getMysqli(): object
    {
        return $this->mysqli;
    }

    private function obtenerProyectos()
    {
        $consulta = "SELECT id, nombre FROM dyalogo_general.huespedes";

        $sql = mysqli_query(Mysql::mysqli(), $consulta);
        if ($sql && mysqli_num_rows($sql) > 0) {
            $result = mysqli_fetch_all($sql, MYSQLI_ASSOC);
            $this->setPoryectos($result);
        }
    }
}
