<?php
ini_set('display_errors', 'On');
ini_set('display_errors', 1);
// En esta clase sera usado para generar las flechas automaticamente desde una bola

class GeneradorDeFlechas{

    private $pasoId;
    private $mysqli;
    public function __construct(){
        global $mysqli;
        $this->mysqli = $mysqli;
        // $this->pasoId = $pasoId;

    }

    public function generarPuerto($pasoDesde, $pasoHasta, $tipo){

        if($tipo == 'flujograma'){
            $sql1 = "SELECT ESTPAS_ConsInte__b AS id, ESTPAS_Loc______b AS loc FROM DYALOGOCRM_SISTEMA.ESTPAS WHERE ESTPAS_ConsInte__b = {$pasoDesde}";
            $sql2 = "SELECT ESTPAS_ConsInte__b AS id, ESTPAS_Loc______b AS loc FROM DYALOGOCRM_SISTEMA.ESTPAS WHERE ESTPAS_ConsInte__b = {$pasoHasta}";
        }else if($tipo == 'bot'){
            $sql1 = "SELECT * FROM dyalogo_canales_electronicos.secciones_bot WHERE id = {$pasoDesde}";
            $sql2 = "SELECT * FROM dyalogo_canales_electronicos.secciones_bot WHERE id = {$pasoHasta}";
        }

        // Trago las coordenadas del primer paso
        $res1 = $this->mysqli->query($sql1);
        $data1 = $res1->fetch_object();

        // Traigo la coordenadas del paso 2
        $res2 = $this->mysqli->query($sql2);
        $data2 = $res2->fetch_object();

        if(!is_null($data1->loc) && !is_null($data2->loc)){

            // Reemplazo los + en las coordenadas por espacios

            $coord1 = str_replace('+', ' ', $data1->loc);
            $coord2 = str_replace('+', ' ', $data2->loc);

            $coordenadas1 = explode(" ", $coord1);
            $x1 = $coordenadas1[0];
            $y1 = $coordenadas1[1];
        
            $coordenadas2 = explode(" ", $coord2);
            $x2 = $coordenadas2[0];
            $y2 = $coordenadas2[1];
        
            $puertoY = 'B';
            $puertoX = 'R';
        
            // Calculamos
            if($y1 < $y2){
                $puertoY = 'B';
            }else{
                $puertoY = 'T';
            }
        
            if($x1 > $x2){
                $puertoX = 'L';
            }else{
                $puertoX = 'R';
            }
        
            $rand = rand(1,2);
        
            if($rand == 1){
                return $puertoX;
            }else{
                return $puertoY;
            }
        }else{
            $rand = rand(1,4);

            $puerto = 'T';

            switch ($rand) {
                case '1':
                    $puerto = 'T';
                    break;
                case '2':
                    $puerto = 'B';
                    break;
                case '3':
                    $puerto = 'L';
                    break;
                case '4':
                    $puerto = 'R';
                    break;
                default:
                    $puerto = 'B';
                    break;
            }

            return $puerto;
        }
    }

    // Esta metodo genera las flechas que salen de un bot
    public function generarFlechaBot(){

        // Traigo todos los registros del bot 
        $botcampos = $this->obtenerContenidoBotTransfereciaOtroPaso();
    
        if(count($botcampos) > 0){
            foreach ($botcampos as $key => $row) {
                $idPasoEnd = 0;
                $idEstrategia = 0;

                // Busco el id del paso de la bola destino
                if($row->accion == 1){
                    
                    $resPasoEnd = $this->obtenerEstpasSiEsCampana($row->id_campana);
                    
                    if($resPasoEnd && $resPasoEnd->num_rows > 0){
                        $pasoEnd = $resPasoEnd->fetch_object();
                        $idPasoEnd = $pasoEnd->id;
                        $idEstrategia = $pasoEnd->estrategia;
                    }
                }else if($row->accion == 2){
                    
                    $resPasoEnd = $this->obtenerEstpasSiEsBot($row->id_base_transferencia);
                    
                    if($resPasoEnd && $resPasoEnd->num_rows > 0){
                        $pasoEnd = $resPasoEnd->fetch_object();
                        $idPasoEnd = $pasoEnd->id;
                        $idEstrategia = $pasoEnd->estrategia;
                    }
                }

                // Si hay un paso final validar si existe en estcon
                if($idPasoEnd != 0){
                    if($idPasoEnd != $this->pasoId){
                        
                        if(!$this-> validarSiExisteEstcon($this->pasoId, $idPasoEnd)){
                            $this->insertarEstcon($this->pasoId, $idPasoEnd, $idEstrategia);       
                        }
                        
                    }
                }

            }
        }
    }

    public function borrarFlechas(){
        $sql = "DELETE FROM DYALOGOCRM_SISTEMA.ESTCON WHERE ESTCON_ConsInte__ESTPAS_Des_b = {$this->pasoId}";
        $this->mysqli->query($sql);
    }

    private function obtenerContenidoBotTransfereciaOtroPaso(){

        //Seleccione todas las opciondes de bot que sean 1 o 2
        $sql = "SELECT * FROM dyalogo_canales_electronicos.dy_base_autorespuestas_contenidos A INNER JOIN dyalogo_canales_electronicos.dy_base_autorespuestas B ON A.id_base_autorespuestas = B.id WHERE B.id_estpas = {$this->pasoId} AND (A.accion = 1 OR A.accion = 2)";
        $res = $this->mysqli->query($sql);
        
        $data = [];

        while ($row = $res->fetch_object()) {
            $data[] = $row;
        }

        return $data;
    }

    private function obtenerEstpasSiEsCampana($campanaId){
        $sql = "SELECT ESTPAS_ConsInte__b AS id, ESTPAS_ConsInte__ESTRAT_b AS estrategia FROM DYALOGOCRM_SISTEMA.ESTPAS INNER JOIN DYALOGOCRM_SISTEMA.CAMPAN ON ESTPAS_ConsInte__CAMPAN_b = CAMPAN_ConsInte__b WHERE CAMPAN_IdCamCbx__b = ".$campanaId;
        $res = $this->mysqli->query($sql);

        return $res;
    }

    private function obtenerEstpasSiEsBot($baseTransferenciaId){
        $sql = "SELECT ESTPAS_ConsInte__b AS id, ESTPAS_ConsInte__ESTRAT_b AS estrategia FROM DYALOGOCRM_SISTEMA.ESTPAS INNER JOIN dyalogo_canales_electronicos.dy_base_autorespuestas ON ESTPAS_ConsInte__b = id_estpas WHERE id = ".$baseTransferenciaId;
        $res = $this->mysqli->query($sql);

        return $res;
    }

    private function validarSiExisteEstcon($pasoFrom, $pasoTo){
        $sql = "SELECT * FROM DYALOGOCRM_SISTEMA.ESTCON WHERE ESTCON_ConsInte__ESTPAS_Des_b = {$pasoFrom} AND ESTCON_ConsInte__ESTPAS_Has_b = {$pasoTo}";
        $res = $this->mysqli->query($sql);

        if($res && $res->num_rows > 0){
            return true;
        }else{
            return false;
        }
    }

    private function insertarEstcon($pasoFrom, $pasoTo, $estrategia){

        // $this->calcularPosicionParaInsertarFlecha($pasoFrom, $pasoTo);


        $sql = "INSERT INTO DYALOGOCRM_SISTEMA.ESTCON (ESTCON_Nombre____b, ESTCON_ConsInte__ESTPAS_Des_b, ESTCON_ConsInte__ESTPAS_Has_b, ESTCON_ConsInte__ESTRAT_b, ESTCON_Tipo_Consulta_b, ESTCON_Tipo_Insercion_b,ESTCON_ConsInte_PREGUN_Fecha_b,ESTCON_ConsInte_PREGUN_Hora_b,ESTCON_Operacion_Fecha_b,ESTCON_Operacion_Hora_b,ESTCON_Cantidad_Fecha_b,ESTCON_Cantidad_Hora_b,ESTCON_Estado_cambio_b,ESTCON_Sacar_paso_anterior_b,ESTCON_resucitar_registro) VALUES ('conector', {$pasoFrom}, {$pasoTo}, {$estrategia} , 1,0,-1,-1,1,1,0,0,0,0,0)";
        try {
            $insert = $this->mysqli->query($sql);
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    private function calcularPosicionParaInsertarFlecha($pasoFrom, $pasoTo){
        $coordenadasPasoFrom = $this->obtenerCoordenadasPaso($pasoFrom);
        $coordenadasPasoTo = $this->obtenerCoordenadasPaso($pasoTo);
    }

    private function obtenerCoordenadasPaso($paso){
        $sql = "SELECT ESTPAS_Loc______b AS loc FROM DYALOGOCRM_SISTEMA.ESTPAS WHERE ESTPAS_ConsInte__b = {$paso} LIMIT 1";
        $res = $this->mysqli->query($sql);

        $data = $res->fetch_object();

        $coordenadas = explode(" ", $data->loc);

        return ["x" => $coordenadas[0], "y" => $coordenadas[1]];

    }
}

?>