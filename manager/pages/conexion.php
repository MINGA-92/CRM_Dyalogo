<?php

use phpDocumentor\Reflection\Types\Boolean;

    include(__DIR__."/../configuracion.php");
    require_once(__DIR__."/../../helpers/ClearData.php");
    require_once(__DIR__."/../../helpers/parameters.php");
    require_once(__DIR__."/../../helpers/Auditoria.php");
    require_once(__DIR__."/../utils.php");
    
    $mysqli = new  mysqli($DB_Name, $DB_User, $DB_Pass);
    
    /* comprobar la conexiÃ³n */
    if ($mysqli->connect_errno) {
        printf("FallÃ³ la conexiÃ³n: %s\n", $mysqli->connect_error);
        exit();
    }
    
    if (!$mysqli->set_charset("utf8mb4")) {
        printf("Error cargando el conjunto de caracteres utf8mb4: %s\n", $mysqli->error);
        exit();
    } else {
        
    }
    
    $limpiar = new ClearData($mysqli);
    
    if($_POST){
        $_POST = $limpiar->sanearInput($_POST);         
    }
    if($_GET){
        $_GET = $limpiar->sanearInput($_GET);
    }

    function saveAuditoria(string $accion, string $superAccion, string $modulo, string $subModulo, int $id, string $where, string $database, string $tabla):Bool
    {
        global $mysqli;

        $objAuditoria=new Auditoria($mysqli);
        $objAuditoria->setAccion($accion);
        $objAuditoria->setSuperAccion($superAccion);
        $objAuditoria->setModulo($modulo);
        $objAuditoria->setSubModulo($subModulo);
        $objAuditoria->setId($id);
        $objAuditoria->setWhere($where);
        $objAuditoria->setBd($database);
        $objAuditoria->setTabla($tabla);
        return $objAuditoria->saveAuditoria();
    }
    
    
?>
