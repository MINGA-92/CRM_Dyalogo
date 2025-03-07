<?php


class UsuariosGateway
{
    // private PDO $cann;

    public function __construct(Database $database)
    {
        $this->cann = $database->getConnection();
        $this->BaseDatos = $database->BaseDatos;
    }


    public static function toString(string $strSQL, int $intTotal, int $intCount, array $arrRegistro): string
    {
        $info = "\nSLQ: {$strSQL} \n\n";
        $info .= "\nREGISTRO ACTULIZADO: " . print_r($arrRegistro) . "\n\n";
        $info .= "TOTAL REGISTROS PARA ACTULIZAR : {$intTotal} \n\n";
        $info .= "COUNT: {$intCount} \n\n";
        $info .= "==============================================================================================\n\n";

        return $info;
    }


    public function getAllUsuarios(bool $param): array
    {
        # code...
        $param === true ? $sql = "SELECT agen.identificacion, agen.id, agen.nombre, agen.id_proyecto, agen.email, agen.contrasena AS pass_agente, usua.contrasena AS pass_usuarios, usu.USUARI_Clave_____b AS pass_usuari, agen.id_usuario_asociado FROM dyalogo_telefonia.dy_agentes AS agen LEFT JOIN dyalogo_telefonia.dy_usuarios AS usua ON agen.id_usuario_asociado = usua.id LEFT JOIN DYALOGOCRM_SISTEMA.USUARI AS usu ON agen.id_usuario_asociado = usu.USUARI_UsuaCBX___b WHERE agen.contrasena <> '' AND agen.contrasena IS NOT NULL AND usu.USUARI_UsuaCBX___b <> '' AND usu.USUARI_UsuaCBX___b IS NOT NULL" : null;

        $stmt = $this->cann->query($sql);
        $data = [];

        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            # code...
            array_push($data, $row);
        }

        return $data;
    }


    public function updateUsuios(array $usuarios): void
    {
        $total = count($usuarios);
        $count = 1;
        foreach ($usuarios as $key => $value) {
            # code...
            // $sql = "SELECT agen.identificacion, agen.id, agen.nombre, agen.id_proyecto, agen.email, agen.contrasena AS pass_agente, usua.contrasena AS pass_usuario, usu.USUARI_Clave_____b AS pass_usu, agen.id_usuario_asociado FROM dyalogo_telefonia.dy_agentes AS agen LEFT JOIN dyalogo_telefonia.dy_usuarios AS usua ON agen.id_usuario_asociado = usua.id LEFT JOIN DYALOGOCRM_SISTEMA.USUARI AS usu ON agen.id_usuario_asociado = usu.USUARI_UsuaCBX___b WHERE agen.contrasena <> '' AND agen.contrasena IS NOT NULL AND usu.USUARI_UsuaCBX___b <> '' AND usu.USUARI_UsuaCBX___b IS NOT NULL AND agen.id_usuario_asociado = :id ";

            $sql = "UPDATE dyalogo_telefonia.dy_agentes AS agen LEFT JOIN dyalogo_telefonia.dy_usuarios AS usua ON agen.id_usuario_asociado = usua.id LEFT JOIN DYALOGOCRM_SISTEMA.USUARI AS usu ON agen.id_usuario_asociado = usu.USUARI_UsuaCBX___b SET usua.contrasena = agen.contrasena, usu.USUARI_Clave_____b = agen.contrasena WHERE agen.contrasena <> '' AND agen.contrasena IS NOT NULL AND usu.USUARI_UsuaCBX___b <> '' AND usu.USUARI_UsuaCBX___b IS NOT NULL AND agen.id_usuario_asociado = :id";


            $stmt = $this->cann->prepare($sql);

            $stmt->bindValue(":id", $value['id_usuario_asociado'], PDO::PARAM_INT);

            $stmt->execute();

            echo UsuariosGateway::toString($sql, $total, $count, $value);

            $count++;
        }
    }
}
