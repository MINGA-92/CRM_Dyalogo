<?php

namespace Dyalogo\Poomuestras\models;

use Dyalogo\Poomuestras\utils\Mysql;

class Muestras
{
    private $muestras;
    private $BaseDatos;
    private $BaseDatosSistema;

    public function __construct()
    {
        var_dump("Se creo un nuevo objecto mysqli", Mysql::getmysqli(), "\n\n");
        $this->mysqli = Mysql::getmysqli();
        $this->muestras = [];
        $this->BaseDatos = "DYALOGOCRM_WEB";
        $this->BaseDatosSistema = "DYALOGOCRM_SISTEMA";
    }

    public function ejecutar()
    {
        $this->obtenerMuestras();
        $this->addCamposTabla();
    }

    public function getMuestra()
    {

        return $this->muestras;
    }

    public function setMuestras(array $muestras)
    {
        $this->muestras = $muestras;
    }

    private function obtenerMuestras()
    {
        $consulta = "SELECT MUESTR_ConsInte__b AS id_muestra, MUESTR_ConsInte__GUION__b AS id_BD FROM {$this->BaseDatosSistema}.MUESTR WHERE MUESTR_ConsInte__GUION__b IS NOT NULL AND MUESTR_ConsInte__GUION__b != '' HAVING MUESTR_ConsInte__b IS NOT NULL AND MUESTR_ConsInte__b != ''";

        $sql = mysqli_query($this->mysqli, $consulta);

        if (mysqli_num_rows($sql) > 0) {
            $result = mysqli_fetch_all($sql, MYSQLI_ASSOC);
            // Url::urlSegura($sql->id_muestra);
            $this->setMuestras($result);
        } else {
            $result = [];
            $this->setMuestras($result);
        }
    }

    protected function addCamposTabla()
    {
        $count = 1;
        try {

            foreach ($this->muestras as $value) {
                $muestra = "G{$value['id_BD']}_M{$value['id_muestra']}";
                // echo " addCamposTabla->value => ", json_encode($muestra), "\n\n";
                echo "muestra =>", $muestra, "\n\n";
                echo "count =>", $count++, "\n\n";
    
                // print("Existe la muestra: " . $this->validarTabla($muestra) . "\n\n");
                $this->validarTabla($muestra) == 1 ? $this->validarCampos($muestra) : null;
            }
        } catch (\Throwable $th) {
            throw $th;
        }
    }


    /**
     * ACA AGREGAMOS LOS CAMPOS NUEVOS EN LAS MUESTRAS
     */
    private function validarCampos($muestra)
    {
        # validamos si existe el campo [_FechaCreacion_b] en la muestra si no, lo agregamos
        $consultaShow = "SHOW COLUMNS FROM {$this->BaseDatos}.{$muestra} WHERE Field = '{$muestra}_FechaCreacion_b'";

        // echo "\n consulta->1[_FechaCreacion_b] => {$consultaShow}  \n\n";
        $sql = mysqli_query($this->mysqli, $consultaShow);
        $this->menssage($sql, $consultaShow);
        if ($sql == true && mysqli_num_rows($sql) == 0) {
            $consultaAlter = "ALTER TABLE {$this->BaseDatos}.{$muestra} ADD {$muestra}_FechaCreacion_b datetime DEFAULT NULL";
            // echo "\n consulta->2[_FechaCreacion_b] => $consultaAlter \n\n";

            $this->menssage(mysqli_query($this->mysqli, $consultaAlter), $consultaAlter);
        }

        # validamos si existe el campo [_FechaReactivacion_b] en la muestra si no, lo agregamos
        $consultaShow = "SHOW COLUMNS FROM {$this->BaseDatos}.{$muestra} WHERE Field = '{$muestra}_FechaReactivacion_b'";
        // echo "\n consulta->1[_FechaReactivacion_b] => {$consultaShow} \n\n";
        $sql = mysqli_query($this->mysqli, $consultaShow);
        $this->menssage($sql, $consultaShow);
        if ($sql == true && mysqli_num_rows($sql) == 0) {
            $consultaAlter = "ALTER TABLE {$this->BaseDatos}.{$muestra} ADD {$muestra}_FechaReactivacion_b datetime DEFAULT NULL";
            // echo "\n consulta->2[_FechaReactivacion_b] => {$consultaAlter} \n\n";

            $this->menssage(mysqli_query($this->mysqli, $consultaAlter), $consultaAlter);
        }

         # validamos si existe el campo [_FechaAsignacion_b] en la muestra si no, lo agregamos
         $consultaShow = "SHOW COLUMNS FROM {$this->BaseDatos}.{$muestra} WHERE Field = '{$muestra}_FechaAsignacion_b'";
         // echo "\n consulta->1[_FechaAsignacion_b] => {$consultaShow} \n\n";
         $sql = mysqli_query($this->mysqli, $consultaShow);
         $this->menssage($sql, $consultaShow);
         if ($sql == true && mysqli_num_rows($sql) == 0) {
             $consultaAlter = "ALTER TABLE {$this->BaseDatos}.{$muestra} ADD {$muestra}_FechaAsignacion_b datetime DEFAULT NULL";
             // echo "\n consulta->2[_FechaAsignacion_b] => {$consultaAlter} \n\n";
 
             $this->menssage(mysqli_query($this->mysqli, $consultaAlter), $consultaAlter);
         }
    }


    private function validarTabla(string $muestra): bool
    {
        # true = 1 y false = vacio
        $consulta = "DESC {$this->BaseDatos}.{$muestra}";
        return mysqli_query($this->mysqli, $consulta) == true ? true : false;
    }


    static function menssage($sql, $consulta)
    {
        if ($sql) {
            echo "message: La consulta se ejecuto correctamente ", $consulta, "\n\n";
        } else {
            echo "message: La consulta fallo o no existe la tabla ", $consulta, "\n\n";
        }
    }
}
