<?php

/**
 *BGCR - Esta funcion llama al api traerme el dato de las llamadas en cola cuando una campaña esta con marcador predictivo
 *por medio de la api "llamadasEspera"
 *@param strIdCola_p - string del id de la cola
 *@return json - respuesda del api si fue exitoso o fallo. 
 */
function metricaColaPredictivo($strIdCola_p)
{
    include_once(__DIR__ . "/../config/parameters.php");
    $data = [
        "strUsuario_t"          =>  "crm",
        "strToken_t"            =>  "D43dasd321",
        "strColaACD_t"          =>  $strIdCola_p
    ];

    return consumirWSJSON( API_GESTION . "dyalogocore/api/info/voip/llamadasEspera", $data);
}

/**
 * Creacion metodo
 * Este etodo recibe los parametros del consumo de un web service y lo ejecuta
 * @param String strURL_p String con la informacion de la URL
 * @param Array arrayDatos_p Arreglo con los datos del consumo
 * @return String retorna la informacion y respuesta del consumo
 */

function consumirWSJSON($strURL_p, $arrayDatos_p)
{

    //Codificamos el arreglo en formato JSON
    $strDatosJSON_t = json_encode($arrayDatos_p);

    //Inicializamos la conexion CURL al web service local para ser consumido
    $objCURL_t = curl_init($strURL_p);

    //Asignamos todos los parametros del consumo
    curl_setopt($objCURL_t, CURLOPT_CUSTOMREQUEST, "POST");
    curl_setopt($objCURL_t, CURLOPT_POSTFIELDS, $strDatosJSON_t);
    curl_setopt($objCURL_t, CURLOPT_RETURNTRANSFER, true);
    curl_setopt(
        $objCURL_t,
        CURLOPT_HTTPHEADER,
        array(
            'Accept: application/json',
            'Content-Type: application/json',
            'Content-Length: ' . strlen($strDatosJSON_t)
        )
    );

    //Obtenemos la respuesta
    $objRespuestaCURL_t = curl_exec($objCURL_t);

    //Obtenemos el error 
    $objRespuestaError_t = curl_error($objCURL_t);

    //Cerramos la conexion
    curl_close($objCURL_t);

    //Validamos la respuesta y generamos el rerno
    if (isset($objRespuestaCURL_t)) {
        //Decodificamos la respuesta en JSON y la retornamos
        return $objRespuestaCURL_t;
    } else {
        return $objRespuestaError_t;
    }
}
