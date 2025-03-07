<?php


class Database
{
    //  $mysqli;
    // public function __construct(object $mysqli, array $bd)


    public function __construct(string $host, string $user, string $pass, array $bd)
    {
        // $this->mysqli = $mysqli;
        $this->host = $host;
        $this->user = $user;
        $this->pass = $pass;
        $this->Basedatos_systema = $bd['Basedatos_systema'];
        $this->BaseDatos = $bd['BaseDatos'];
        // echo $bd['Basedatos_systema'];
    }

    public function getConnection(): PDO
    {
        $dns = "mysql:host={$this->host};charset=utf8";

        return  new PDO($dns, $this->user, $this->pass, [
            // PDO::ATTR_EMULATE_PREPARES => false,
            PDO::ATTR_STRINGIFY_FETCHES => false
        ]);
    }
}
