<?php

ini_set('display_errors', 'On');
ini_set('display_errors', 1);
require_once(__DIR__."/../pages/conexion.php");

class GenerarGuion{

    private $conn;
    private $bdSistema;

    public function __construct()
    {
        global $mysqli;
        global $BaseDatos_systema;

        $this->conn = $mysqli;
        $this->bdSistema = $BaseDatos_systema;
    }

    // Esta funcion se encargara de crear el formulario de la bd
    public function crearBd($nombre, $descripcion, $tipo, $iniciador, $huespedId = null):array {

        if(is_null($huespedId)){
            $huespedId = $_SESSION['HUESPED'];
        }

        $query = "INSERT INTO {$this->bdSistema}.GUION_ (GUION__Nombre____b, GUION__Descripci_b, GUION__ConsInte__PROYEC_b, GUION__Tipo______b, GUION_ByModulo_b) VALUES ('{$nombre}','{$descripcion}',{$huespedId}, {$tipo}, 1)";
        
        $res = $this->conn->query($query);

        $estado = false;
        $id = 0;

        if($res){
            $estado = true;
            $id = $this->conn->insert_id;

            // Si la base es de tipo 1 osea gestiones debo crear 3 secciones por defecto
            if($tipo == 1){
                $this->crearSeccionesPorDefectoFormularioAgente($id, $iniciador, $nombre);
            }
        }

        return ["estado" => $estado, "idBd" => $id];

    }

    public function crearSeccion(int $idBd, array $secciones):array {

        $response = [];
        $error = [];

        foreach($secciones as $seccion){
            // Crear una seccion por cada elemento del array de secciones

            $query = "INSERT INTO {$this->bdSistema}.SECCIO (SECCIO_Nombre____b, SECCIO_ConsInte__GUION__b, SECCIO_NumColumnas_b, SECCIO_VistPest__b, SECCIO_TipoSecc__b) VALUES ('{$seccion['nombre']}', {$idBd}, 2, 3, {$seccion['tipo']})";

            $res = $this->conn->query($query);

            if($res){
                $response[$seccion['nombre']] = $this->conn->insert_id;
            }else{
                array_push($error, "{$seccion['nombre']} : {$this->bd->error}");
            }

        }

        return ["error" => $error, "exito" => $response];
    }

    public function crearPregun(int $idBd, array $campos):array {

        $insertados = array();
        $error = array();

        if(count($campos) > 0){

            for ($i=0; $i < count($campos); $i++) { 
                
                $campo = $campos[$i];

                $opcion = 0;
                if(isset($campo['listaId'])){
                    $opcion = $campo['listaId'];
                }

                $defecto = 0;
                if(isset($campo['default'])){
                    $defecto = $campo['default'];
                }

                $lectura = 0;
                if(isset($campo['lectura'])){
                    $lectura = $campo['lectura'];
                }

                $requerido = 0;
                if(isset($campo['requerido'])){
                    $requerido = $campo['requerido'];
                }

                $subform = 0;

                $query = "INSERT INTO {$this->bdSistema}.PREGUN (PREGUN_ConsInte__GUION__b, PREGUN_Texto_____b, PREGUN_Tipo______b, PREGUN_ConsInte__SECCIO_b, PREGUN_OrdePreg__b, PREGUN_ConsInte__OPCION_B, PREGUN_Default___b, PREGUN_ContAcce__b, PREGUN_MostrarSubForm, PREGUN_IndiRequ__b, PREGUN_FueGener_b) 
                VALUES( {$idBd}, '{$campo['nombre']}', {$campo['tipo']}, {$campo['seccion']}, {$i}, {$opcion}, {$defecto}, {$lectura}, {$subform}, {$requerido}, 1)";

                $res = $this->conn->query($query);

                if($res){
                    $insertados[] = $this->conn->insert_id;
                }else{
                    array_push($error, "{$campo['nombre']} : {$this->conn->error}");
                }

            }

        }

        return array('exito' => $insertados, 'error' => $error);
    }

    public function acutualizarCampoPrincipalSecundario($id, $primario, $secundario):bool {

        $query = "UPDATE {$this->bdSistema}.GUION_ SET GUION__ConsInte__PREGUN_Pri_b = {$primario}, GUION__ConsInte__PREGUN_Sec_b = {$secundario} WHERE GUION__ConsInte__b = {$id}";

        $res = $this->conn->query($query);

        return $res ? true : false;
    }

    public function generarTabla($idBd, $tipo){
        include_once(__DIR__."/../generador/generar_tablas_bd.php");
        return generar_tablas_bd($idBd, 1, 1, 0, 0, $tipo);
    }

    private function crearSeccionesPorDefectoFormularioAgente(int $idBd, string $iniciador, string $nombre):void {

        $arrSecciones = [
            ["nombre" => "TIPIFICACION", "tipo" => 3],
            ["nombre" => "CONTROL", "tipo" => 4],
            ["nombre" => "CALIDAD", "tipo" => 2]
        ];

        if($iniciador == 'Marcador Robotico'){
            $arrSecciones[0]["tipo"] = 4;
        }

        $res = $this->crearSeccion($idBd, $arrSecciones);

        // Si trae secciones entonces nos ponemos a agregar los campos
        if(count($res['exito'])){

            foreach ($arrSecciones as $seccion) {
                if(isset($res['exito'][$seccion['nombre']])){
                    
                    $seccionId = $res['exito'][$seccion['nombre']];

                    if($seccion['nombre'] == 'TIPIFICACION'){

                        // Me toco crear LISOPC y MONOEF para la lista de gestiones
                        $listaId = $this->crearListaTipificacionesPorDefecto($iniciador, $idBd, $nombre);

                        $arrCampos = [
                            ["nombre" => "Tipificacion", "seccion" => $seccionId, "tipo" => 6, "listaId" => $listaId, "requerido" => 1],
                            ["nombre" => "Reintento", "seccion" => $seccionId, "tipo" => 6, "requerido" => 1],
                            ["nombre" => "Fecha Agenda", "seccion" => $seccionId, "tipo" => 5, "requerido" => 1],
                            ["nombre" => "Hora Agenda", "seccion" => $seccionId, "tipo" => 10, "requerido" => 1],
                            ["nombre" => "Observacion", "seccion" => $seccionId, "tipo" => 2, "requerido" => 1]
                        ];

                    }

                    if($seccion['nombre'] == 'CONTROL'){
                        $arrCampos = [
                            ["nombre" => "Agente", "seccion" => $seccionId, "tipo" => 1, "default" => 102, "lectura" => 2],
                            ["nombre" => "Fecha", "seccion" => $seccionId, "tipo" => 1, "default" => 501, "lectura" => 2],
                            ["nombre" => "Hora", "seccion" => $seccionId, "tipo" => 1, "default" => 1001, "lectura" => 2],
                            ["nombre" => "Campaña", "seccion" => $seccionId, "tipo" => 1, "default" => 105, "lectura" => 2]
                        ];
                    }

                    if($seccion['nombre'] == 'CALIDAD'){
                        $arrCampos = [];
                    }

                    $idsCampos = $this->crearPregun($idBd, $arrCampos);

                    // Si es control debo actualizar GUION_ con los campos creados
                    if($seccion['nombre'] == 'CONTROL' && count($idsCampos) > 0){
                        $updateGuion="UPDATE  {$this->bdSistema}.GUION_ 
                            SET GUION__ConsInte__PREGUN_Age_b = ".$idsCampos['exito'][0].", GUION__ConsInte__PREGUN_Fec_b = ". $idsCampos['exito'][1].", GUION__ConsInte__PREGUN_Hor_b = ".$idsCampos['exito'][2]."
                        WHERE GUION__ConsInte__b =".$idBd;
                        $this->conn->query($updateGuion);
                    }


                    if($iniciador == 'Marcador Robotico'){
                        // Si es tipificacion debo actualizar GUION_ con los campos creados
                        if($seccion['nombre'] == 'TIPIFICACION' && count($idsCampos) > 0){
                            $updateGuion="UPDATE  {$this->bdSistema}.GUION_ 
                                SET GUION__ConsInte__PREGUN_Tip_b = ".$idsCampos['exito'][0].", GUION__ConsInte__PREGUN_Rep_b = ". $idsCampos['exito'][1].", GUION__ConsInte__PREGUN_Fag_b = ".$idsCampos['exito'][2]." , GUION__ConsInte__PREGUN_Hag_b = ".$idsCampos['exito'][3]." , GUION__ConsInte__PREGUN_Com_b = ".$idsCampos['exito'][4]."  
                            WHERE GUION__ConsInte__b =".$idBd;
                            $this->conn->query($updateGuion);
                        }
                    }

                }
            }

        }


    }

    private function crearListaTipificacionesPorDefecto($iniciador, $idBd, $nombre):int {

        $arrOpciones = [
            ['No contesta', 4, 1, 3, 6],
            ['Ocupado', 4, 1 , 4, 2],
            ['Fallida', 2, 3 , 5, 0],
            ['No lo conocen', 5, 3 , 0, 0],
            ['Llamar luego', 6, 2, 0, 6],
            ['No exitoso ', 6, 3, 0, 0],
            ['Exitoso', 7, 3, 0, 0]
        ];
        
        // Me toca buscar el huesped
        $sql = "SELECT GUION__ConsInte__b AS id, GUION__ConsInte__PROYEC_b AS huespedId from DYALOGOCRM_SISTEMA.GUION_ WHERE GUION__ConsInte__b = {$idBd}";
        $resGuion = $this->conn->query($sql);

        if($resGuion && $resGuion->num_rows > 0){
            $guion = $resGuion->fetch_object();
            $huespedId = $guion->huespedId;
        }else{
            $huespedId = 0;
        }

        if(isset($_SESSION['IDENTIFICACION'])){
            $identificacion = $_SESSION['IDENTIFICACION'];
        }else{
            $identificacion = 1;
        }

        // Creamos la lista 
        $insertOpcion = "INSERT INTO {$this->bdSistema}.OPCION (OPCION_ConsInte__GUION__b, OPCION_Nombre____b, OPCION_ConsInte__PROYEC_b, OPCION_FechCrea__b, OPCION_UsuaCrea__b) VALUES ({$idBd}, 'Tipificaciones - {$nombre}', ".$huespedId.", '".date('Y-m-d H:s:i')."', ".$identificacion.")";
        $resOpcion = $this->conn->query($insertOpcion);

        if($resOpcion){

            $listaId = $this->conn->insert_id;

            for ($i=0; $i < count($arrOpciones); $i++) { 
                
                $insertMonoef = "INSERT INTO ".$this->bdSistema.".MONOEF (MONOEF_Texto_____b, MONOEF_EFECTIVA__B, MONOEF_TipNo_Efe_b, MONOEF_Importanc_b, MONOEF_FechCrea__b, MONOEF_UsuaCrea__b, MONOEF_Contacto__b, MONOEF_CanHorProxGes__b) VALUES ('".$arrOpciones[$i][0]."','0', '".$arrOpciones[$i][2]."', '".($i+1)."' , '".date('Y-m-d H:s:i')."', ".$identificacion.", '".$arrOpciones[$i][1]."' , '".$arrOpciones[$i][4]."')";

                if ($this->conn->query($insertMonoef) === true){

                    $monoefId = $this->conn->insert_id;

                    $insertLisopc = "INSERT INTO ".$this->bdSistema.".LISOPC (LISOPC_Nombre____b, LISOPC_ConsInte__OPCION_b, LISOPC_Posicion__b, LISOPC_Clasifica_b) VALUES ('".$arrOpciones[$i][0]."', ".$listaId.", 0, ".$monoefId.")";
                    
                    $this->conn->query($insertLisopc);

                }

            }

            return $listaId;
        }

        return 0;
    }

}