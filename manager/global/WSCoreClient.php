
<?php

/**
 * JDBD - Se obtienen la lista de agentes en linea.
 * Este metodo llama al web service : 
 * dy_servicios_adicionales/svrs/actividadActual/lista que sirve para realizar las vistas de metas.
 * @param Integer id del Usuario que esta log e la pagina.
 * @return array - con toda la informacion de cada paso y sus metas.
 */
function listaAgentesTiempoReal($intIdUsuario_p)
{
    include(__DIR__ . "/../configuracion.php");
    $data = [
        "strUsuario_t"   => 'crm',
        "strToken_t"     => 'D43dasd321',
        "intIdHuesped_t" => null,
        "intUsuariId_t"  => (int)$intIdUsuario_p
    ];

    return consumirWSJSON($API_WS_TIEMPO_REAL . "/dy_servicios_adicionales/svrs/actividadActual/lista", $data);
}
/**
 * JDBD - Se obtienen la cantidad de estados por tipo en lista agentes tiempó real.
 * Este metodo llama al web service : 
 * dy_servicios_adicionales/svrs/actividadActual/agrupacionEstados que sirve para realizar las vistas de metas.
 * @param Integer id del Usuario que esta log e la pagina.
 * @return array - con toda la informacion de cada paso y sus metas.
 */
function agrupacionEstadosTiempoReal($intIdUsuario_p)
{
    include(__DIR__ . "/../configuracion.php");
    $data = [
        "strUsuario_t"   =>  'crm',
        "strToken_t"     =>  'D43dasd321',
        "intIdHuesped_t" =>  null,
        "intUsuariId_t"  => (int)$intIdUsuario_p
    ];

    return consumirWSJSON($API_WS_TIEMPO_REAL . "/dy_servicios_adicionales/svrs/actividadActual/agrupacionEstados", $data);
}


/**
 * Refresca el cache del distribuidor
 * DALB20200901 - Creacion de la funcion que limpia el cache 
 */
function refrescarCacheDistribuidor()
{
    include(__DIR__ . "/../configuracion.php");
    global $IP_SERVICIO_DISTRIBUCION;

    $data = [
        "strUsuario_t"   =>  'local',
        "strToken_t"     =>  'local'
    ];

    return consumirWSJSON("http://" . $IP_SERVICIO_DISTRIBUCION . ":8080/dy_distribuidor_trabajo/api/cache/refresca", $data);
}

/**
 * JDBD - Se obtienen las metricas de cada paso que tiene la estrategia.
 * Este metodo llama al web service : 
 * dy_servicios_adicionales/svrs/actividadActual/infoEstrategiaMetas que sirve para realizar las vistas de metas.
 * @param Integer id del Usuario que esta log e la pagina.
 * @return array - con toda la informacion de cada paso y sus metas.
 */
function metricasTiempoReal($intIdUsuario_p)
{
    include(__DIR__ . "/../configuracion.php");
    $data = [
        "strUsuario_t" => "crm",
        "strToken_t" => "D43dasd321",
        "intIdUsuario_t" => (int)$intIdUsuario_p,
        "intIdEstrat_t" => NULL,
        "intIdEstpas_t" => NULL
    ];

    return consumirWSJSON($API_WS_TIEMPO_REAL . "/dy_servicios_adicionales/svrs/actividadActual/infoEstrategiaMetas", $data);
}

/**
 *JDBD - Esta funcion crea las vistas para los reportes automaticos en la BD
 *por medio de la api "generateByTennant" con el id del huesped.
 *@param int - Id del huesped actual.
 *@return json - respuesda del api si fue exitoso o fallo. 
 */
function generarVistasPorHuesped($intIdGuion_p = null, $intIdHuesped_p = null)
{
    include(__DIR__ . "/../configuracion.php");
    $data = [
        "strUsuario_t"          =>  "crm",
        "strToken_t"            =>  "D43dasd321",
        "intIdGeneralTennant_t" =>  $intIdHuesped_p,
        "intIdEstrategia_t"     =>  null,
        "intIdGuion_t"           => $intIdGuion_p
    ];

    return consumirWSJSON($Api_Gestion . "dyalogocore/api/bi/generator/views/generateByTennant", $data);
}


/**
 *BGCR - Esta funcion invoca el generador de vistas del addons, y genera las vistas necesarias de lo que se solicite
 *@param int $intTypeView - Tipo de vista a generar 1 - ACD, 2 -Vistas de G, 3 - Adherecias, 4 - Muestras
*@param int $intIdHuesped - Id del huesped
*@param int $intIdPaso - Id del paso necesario para ACD y Muestras
*@param int $intIdGuion - Id del guion necesario para vistas de tipo 2
 *@return json - respuesda del api si fue exitoso o fallo. 
 */
function generarVistasUnicas($intTypeView, $intIdHuesped, $intIdPaso = null, $intIdGuion = null)
{
    include(__DIR__ . "/../configuracion.php");

    $data = [
        "strUsuario_t"          =>  "crm",
        "strToken_t"            =>  "D43dasd321",
        "intIdHuesped"        => $intIdHuesped,
        "intIdPaso"             => $intIdPaso,
        "intIdGuion"            => $intIdGuion,
        "intTypeView"          => $intTypeView

    ];
    return consumirWSJSON($URL_SERVER_ADDONS . "/api/Views/generateViews", $data);
}


/**
 *NBG - Esta funcion llama al api para regenerar contraseñas
 *por medio de la api "recordarclave" con el correo del usuario.
 *@param str - correo del agente.
 *@return json - respuesda del api si fue exitoso o fallo. 
 */

function generarpassword($strCorreo_t)
{
    include(__DIR__ . "/../configuracion.php");
    $data = [
        "strCorreo_t" => $strCorreo_t,
        "strUsuario_t" => 'adminApi',
        "strToken_t" => 'PGbtywunzaCwCLGSo7zj9CGLV9QxiVgJ'
    ];

    return consumirWSJSON("127.0.0.1/admin/public/api/usuarios/recordarclave", $data);
}

/**
 *NBG - Esta funcion llama al api para capturar la foto actual del agente
 *por medio de la api "tomarFoto" con el token del usuario.
 *@param str - token del agente.
 *@return json - respuesda del api si fue exitoso o fallo. 
 */
function capturarFoto($token)
{
    include(__DIR__ . "/../configuracion.php");
    $data = [
        "strToken_t" => $token
    ];

    return consumirWSJSON("http://127.0.0.1:8080/dyalogocbx/api/controlAgente/tomarFoto", $data);
}

/**
 *NBG - Esta funcion llama al api para generarle a un huesped las credenciales de autenticacion contra los web service de dyalogo
 *por medio de la api "crearToken" con el id del proyecto encriptado.
 *@param usuario - string del usuario a autenticar
 *@return json - respuesda del api si fue exitoso o fallo. 
 */
function crearToken($usuario, $fecha)
{
    include(__DIR__ . "/../configuracion.php");
    $data = [
        "strUsuario_t" => "crm",
        "strToken_t" => "D43dasd321",
        "strUsuarioAPI_t" => $usuario,
        "strVigencia_t" => $fecha,
        "booValidarIP_t" => false,
        "strIPsAutorizadas_t" => "*",
        "intLimiteMensual_t" => 30,
        "intLimiteDiario_t" => 10
    ];

    return consumirWSJSON("http://127.0.0.1:8080/dyalogocore/api/security/crearToken", $data);
}


/**
 *BGCR - Esta funcion llama al api traerme el dato de las llamadas en cola cuando una campaña esta con marcador predictivo
 *por medio de la api "llamadasEspera"
 *@param strIdCola_p - string del id de la cola
 *@return json - respuesda del api si fue exitoso o fallo. 
 */
function metricaColaPredictivo($strIdCola_p)
{
    include(__DIR__ . "/../configuracion.php");
    $data = [
        "strUsuario_t"          =>  "crm",
        "strToken_t"            =>  "D43dasd321",
        "strColaACD_t"          =>  $strIdCola_p
    ];

    return consumirWSJSON($Api_Gestion . "dyalogocore/api/info/voip/llamadasEspera", $data);
}



/**
 *BGCR - Esta funcion llama nos ayuda a traer la informacion de las llamadas en curso para un marcador predictivo 
 *por medio de la api "llamadasEnProgreso"
 *@param intIdCampan_p - id de la campaña en CRM
 *@return json - respuesda del api si fue exitoso o fallo. 
 */
function metricaLlamadasCursoPredictivo($intIdCampan_p)
{
    include(__DIR__ . "/../configuracion.php");
    $data = [
        "strUsuario_t"          =>  "crm",
        "strToken_t"            =>  "D43dasd321",
        "intIdCampanCRM"        =>  $intIdCampan_p
    ];

    return consumirWSJSON($API_WS_TIEMPO_REAL . "/dy_servicios_adicionales/svrs/marcador/llamadasEnProgreso", $data);
}

$strApellido_p= null;
$booEncriptarContrasena_p= false;
function usuarioPersistir($strNombre_p, $strApellido_p, $strCorreo_p, $strIdentificacion_p, $strPassRandom_p, $booEncriptarContrasena_p, $intIdHuesped_p, $intIdUsuari_p)
{
    include(__DIR__ . "/../configuracion.php");

    $data = array(
        "strNombre_t" => $strNombre_p,
        "strApellido_t" => $strApellido_p,
        "strCorreoElectronico_t" => trim($strCorreo_p),
        "strContrasena_t" => $strPassRandom_p,
        "booEncriptarContrasena_t" => $booEncriptarContrasena_p,
        "strIdentificacion_t" => $strIdentificacion_p,
        "intRol_t" => 6,
        "strUsuario_t" => 'crm',
        "strToken_t" => 'D43dasd321',
        "intIdHuespedGeneralt" => $intIdHuesped_p,
        "intIdUsuario_t" => $intIdUsuari_p
    );

    // echo "usuarioPersistir->data", json_encode($data);

    return consumirWSJSON($Api_Gestion . "dyalogocore/api/usuarios/persistir", $data);
}

function sendMailPassword($strDestinatario_p, $strNombre_p, $strPassword_p)
{
    include(__DIR__ . "/../configuracion.php");
    $strUrlBase_t = $_SERVER["HTTP_HOST"];

    $strCuerpo_t = "<html> <body> <font face=\"arial\" size=\"3\"> <p style=\"text-align:justify;\">Hola {$strNombre_p}, Tus datos de acceso a Dyalogo son </p> <p style=\"text-align:justify;\"> Usuario  : {$strDestinatario_p} </p> <p style=\"text-align:justify;\"> Password : {$strPassword_p} </p> <p style=\"text-align:justify;\"> Url de ingreso : https://{$strUrlBase_t}/ </p> <p style=\"text-align:justify;\">Cualquier duda consulta  <a href='https://www.dyalogo.com/enviar-ticket-soporte'>www.dyalogo.com/enviar-ticket-soporte</p> </font> </body> </html>";

    $data = array(
        "strUsuario_t" => 'crm',
        "strToken_t" => 'D43dasd321',
        "strIdCfg_t" => 18,
        "strTo_t" => $strDestinatario_p,
        "strCC_t" => null,
        "strCCO_t" => null,
        "strSubject_t" => "Dyalogo - contraseña Agente",
        "strMessage_t" => $strCuerpo_t,
        "strListaAdjuntos_t" => null
    );

    return consumirWSJSON($Api_Gestion . "dyalogocore/api/ce/correo/sendmailservice", $data);
}


/**
 *BGCR - Este consumo nos trae las consultas que hace el distribuido para cada campaña
 *@param intIdCampan_p - string del id de la cola
 *@return json - respuesda del api si fue exitoso o fallo. 
 */
function consultasDistribuidor(int $intIdCampan_p)
{
    include(__DIR__ . "/../configuracion.php");
    $data = [
        "strUsuario_t"          =>  "dymovilusuappios",
        "strToken_t"            =>  "8kgGfdCaGusvu5PV9Qk42S1xi0pSIDTQ",
        "intIdCampan_t"          =>  $intIdCampan_p
    ];

    return consumirWSJSON($API_WS_TIEMPO_REAL . "/dy_distribuidor_trabajo/api/info/consultas", $data);
}



/**
 * DLAB - 20190815 - Creacion metodo
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


//Consumo WS - Ruta Entrante
function RutaEntrante($intIdRutaEntrante_t){

    include(__DIR__ . "/../configuracion.php");
    $data = [
        "strUsuario_t" => "crm",
        "strToken_t" =>  "D43dasd321",
        "intIdRutaEntrante_t" => $intIdRutaEntrante_t
    ];

    return consumirWSJSON($Api_Gestion . "dyalogocore/api/voip/re/persistir", $data);
}


//Consumo WS - IVR's
function IVR($intIdIVR_t){

    include(__DIR__ . "/../configuracion.php");
    $data = [
        "strUsuario_t" => "crm",
        "strToken_t" =>  "D43dasd321",
        "intIdIVR_t" => $intIdIVR_t
    ];

    return consumirWSJSON($Api_Gestion . "dyalogocore/api/voip/ivrs/persistir", $data);
}

