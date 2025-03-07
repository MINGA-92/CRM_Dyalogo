<?php
ini_set('display_errors', 'On');
ini_set('display_errors', 1);
require_once(__DIR__."/../pages/conexion.php");

class CalcularEjecucionProximaTarea{

    private static $conn;

    public function __construct(){
        global $mysqli;
        self::$conn = $mysqli;
    }

    public function __invoke($taskId)
    {
        // Obtengo la informacion de la tarea
        $tarea = $this->getTaskById($taskId);

        // Si no encuentra tarea se termina la ejecución
        if(is_null($tarea)){
            echo "Tarea no existe";
            return;
        }

        // Obtengo la fecha hora actual 
        date_default_timezone_set('America/Bogota');
        $fechaHoraActual = new DateTime();

        $fechaEjecucion = null;

        // Ahora se calcula la fecha de la proxima gestion dependiedo de la periocidad
        switch ($tarea->TARHOR_Unidad____b) {
            case '1':
                // Dia
                $fechaEjecucion = $this->obtenerProximaFechaPorDia($fechaHoraActual, $tarea);
                # code...
                break;
            case '2':
                // Semana
                $fechaEjecucion = $this->obtenerProximaFechaPorSemana($fechaHoraActual, $tarea);
                break;
            case '3':
                // Mes
                # code...
                break;
            case '4':
                // Año
                # code...
                break;
            
            default:
                # code...
                break;
        }

        // Teniendo la fecha de ejecucion procedo a actualizarla
        if(($tarea->TARHOR_Unidad____b == 1) || ($tarea->TARHOR_Unidad____b == 2)) {
            $this->updateFechaEjecucion($fechaEjecucion, $tarea);
        }

    }

    private function obtenerProximaFechaPorDia($fechaInicio, $tarea){

        // Obtengo la hora configurada
        $datetimeEjecucion = new DateTime($tarea->TARHOR_Hora_Ejecucion____b);
        $horaEjecucion = $datetimeEjecucion->format('H:i:s');

        // Reasigno el datetimeEjecucion con fecha hora actual
        $datetimeEjecucion = new DateTime($horaEjecucion);

        // Comparo si la hora actual es mayor a la hora configurada
        if($fechaInicio > $datetimeEjecucion){
            
            // Si la hora actual es mayor a la hora configurada significa que ya paso la ejecucion y se debe realizar para el dia siguiente
            $diasSumar = $tarea->TARHOR_Cantidad____b;

            $datetimeEjecucion->modify("+{$diasSumar} days");
        }

        return $datetimeEjecucion->format('Y-m-d H:i:s');
    }

    private function obtenerProximaFechaPorSemana($fechaInicio, $tarea){

        // Traer los dias de ejecucion en un array
        $arrDias = $this->obtenerArrayDias($tarea);

        // Si no hay ningun dia configurado no devuelva nada
        if(count($arrDias) === 0){
            return null;
        }

        // Hora de ejecucion
        $datetimeEjecucion = new DateTime($tarea->TARHOR_Hora_Ejecucion____b);
        $horaEjecucion = $datetimeEjecucion->format('H:i:s');

        // Obtengo el dia de la semana actual
        $diaActual = $fechaInicio->format('l');

        // Comprobamos si el dia actual esta en la lista de dias de ejecucion
        if(in_array($diaActual, $arrDias)){
            
            // Como el dia actual esta en la lista de dias de ejecucion entonces miramos si la hora de ejecucion no se nos paso
            if($fechaInicio->format('H:i:s') > $horaEjecucion){
                $fechaInicio = $this->obtenerSiguienteDia($diaActual, $arrDias, $fechaInicio);
            }

        }else{
            // Si el dia actual no esta en la lista, avanzo al dia siguiente hasta encontrar el disponible
            $fechaInicio = $this->obtenerSiguienteDia($diaActual, $arrDias, $fechaInicio);
        }

        // Imprimir la próxima fecha y hora de ejecución
        return $fechaInicio->format('Y-m-d') . " " . $horaEjecucion;
    }
    
    private function obtenerArrayDias($tarea){
        $arr = [];

        if($tarea->TARHOR_Lunes____b){
            $arr[] = 'Monday';
        }
        
        if($tarea->TARHOR_Martes____b){
            $arr[] = 'Tuesday';
        }

        if($tarea->TARHOR_Miercoles____b){
            $arr[] = 'Wednesday';
        }

        if($tarea->TARHOR_Jueves____b){
            $arr[] = 'Thursday';
        }

        if($tarea->TARHOR_Viernes____b){
            $arr[] = 'Friday';
        }

        if($tarea->TARHOR_Sabado____b){
            $arr[] = 'Saturday';
        }

        if($tarea->TARHOR_Domingo____b){
            $arr[] = 'Sunday';
        }

        return $arr;
    }

    private function obtenerSiguienteDia($diaActual, $arrDias, $fecha){

        // Si el dia actual esta en el array obtengo la siguiente posicion
        if(in_array($diaActual, $arrDias)){
            $index = array_search($diaActual, $arrDias);
            $sigueinteDiaIndex = ($index+1) % count($arrDias);
            $fecha->modify('next ' . $arrDias[$sigueinteDiaIndex]);
        }else{

            // Si el dia no esta me toca ir dia a dia hasta que encuentre uno que si esta
            do{
                $fecha->modify('+1 day');
                $diaActual = $fecha->format('l');
            }while(!in_array($diaActual, $arrDias));

        }

        return $fecha;
    }

    private function getTaskById($taskId){

        $query = "SELECT * FROM DYALOGOCRM_SISTEMA.TARHOR WHERE TARHOR_ConsInte__b = {$taskId} LIMIT 1";

        $res = self::$conn->query($query);

        if($res && $res->num_rows > 0){
            return $res->fetch_object();
        }

        return null;
    }

    private function updateFechaEjecucion($fecha, $tarea){

        $query = "UPDATE DYALOGOCRM_SISTEMA.TARHOR SET TARHOR_FecProxEje_b = '{$fecha}' WHERE TARHOR_ConsInte__b = {$tarea->TARHOR_ConsInte__b}";
        if(self::$conn->query($query) === false){
            echo self::$conn->error;
        }
    }

}