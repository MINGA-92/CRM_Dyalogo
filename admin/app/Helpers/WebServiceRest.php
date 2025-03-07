<?php
namespace App\Helpers;

use Illuminate\Support\Facades\DB;
use App\User;

/**
 * Esta clase se encarga de consumir el Web Service Rest a la hora de 
 * crear los usuarios
 * @author Breiner Sanchez
 */
class WebServiceRest{

    /**
     * Este metodo se encarga de realizar la conexion con al api y retornar
     * su respectiva respueste
     * @param String - strUrl_p string con la informacion de la url destino
     * @param Array - arrayDatos_p arreglo con los datos de consumo
     */
    public function conexionApi($strUrl_p, $arrayDatos_p){

        // Se codifica el arreglo en formato JSON
        $dataString = json_encode($arrayDatos_p);
        
        // Se inicia la conexion CURL al web service para ser consumido
        $ch = curl_init($strUrl_p);

        // Se asignan todos los parametros del consumo
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_POSTFIELDS, $dataString);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'Content-Type: application/json',
            'Content-Length: ' . strlen($dataString))
        );

        // Se obtiene la respuesta
        $respuesta = curl_exec($ch);

        // Se obtiene el error
        $error = curl_error($ch);

        // Cerramos la conexion
        curl_close($ch);
        
        // Si se obtine una respuesta esta se retorna de lo contrario se retorna el error
        if($respuesta){
            return $respuesta;
        }else if($error){
            return $error;
        }
    }

    /**
     * Este metodo se encarga de llamar al metodo conexionApi y le envia los parametros del
     * usuario y la url del api a ser consumida
     * @param String - strNombre_p nombre del usuario
     * @param String - strCorreo_p correo del usuario
     * @param String - strIdentificacion_p identificacion del usuario
     * @param Int - intIdHuesped_p id del huesped creado
     * @return json - respuesta del api si fue exitosa o fallo
     */
    public function usuarioPersistir($strNombre_p, $strCorreo_p, $strIdentificacion_p, $strPassRandom_p, $intIdHuesped_p, $intIdUsuari_p){
        
        $data = array(
            "strNombre_t" => $strNombre_p,
            "strApellido_t" => NULL,
            "strCorreoElectronico_t" => trim($strCorreo_p),
            "strContrasena_t" => $strPassRandom_p,
            "booEncriptarContrasena_t" => true,
            "strIdentificacion_t" => $strIdentificacion_p,
            "intRol_t" => 6,
            "strUsuario_t" => 'crm',
            "strToken_t" => 'D43dasd321',
            "intIdHuespedGeneralt" => $intIdHuesped_p,
            "intIdUsuario_t" => $intIdUsuari_p
        );

        return $this->conexionApi('http://localhost:8080/dyalogocore/api/usuarios/persistir', $data);
    }

    /**
     * Este metodo se encarga de encriptar un string de datos que recibe
     * @param String - strCadenaEncriptar_p cadena que sera encriptada
     * @return json - respuesta del api si fue exitosa o fallo
     */
    public function encript($strCadenaEncriptar_p){
        $data = array(
            "strUsuario_t" => "local",
            "strToken_t" => "local",
            "strEncriptar_t" => $strCadenaEncriptar_p
        );

        return $this->conexionApi('http://127.0.0.1:8080/dyalogocore/api/security/encrypt', $data);
    }

    /**
     * Este metodo se encarga de hacer la prueba de salida de correo
     * @param String - strNumeroCelular_p numero de celular
     * @param Int - intIdConfiguracion_p id del registro de configuracion
     * @param Int - intIdHuesped_p id del huesped
     * @return json - respuesta del api si fue exitosa o fallo
     */
    public function enviarSms($strNumeroCelular_p, $intIdConfiguracion_p, $intIdHuesped_p){
        $data = array(
            "strUsuario_t" => "crm",
            "strToken_t" => "D43dasd321",
            "strTelefono_t" => "$strNumeroCelular_p",
            "strMensaje_t" => "Prueba SMS",
            "intIdConfiguracion_t" => $intIdConfiguracion_p,
            "intIdEstPas_t" => null,
            "intConsinteMiembro_t" => null,
            "intIdHuesped_t" => $intIdHuesped_p
        );
        
        return $this->conexionApi('http://localhost:8080/dyalogocore/api/bi/enviarSMS', $data);
    }

    /**
     * Este metodo se encarga de enviar las credenciales de un nuevo usuario
     * @param String - strDestinatario_p direccion del destinatario
     * @param Int - intIdTablaCrud_p id de la configuracion
     * @param String - strTablaUsuario_p direccion del usuario
     * @param Int - intIdHuesped_p id del huesped
     * @return json - respuesta del api si fue exitosa o fallo
     */
    public function sendMailPassword($strDestinatario_p, $strNombre_p, $strPassword_p){
        $strUrlBase_t = $_SERVER["HTTP_HOST"];

        $strCuerpo_t = "<html><body><p>Hola ".$strNombre_p.", Tus datos de acceso a Dyalogo son</p><p>Usuario : ".$strDestinatario_p." </p><p>Password : ".$strPassword_p." </p><p>Url de ingreso : https://".$strUrlBase_t."</p><p>Cualquier duda te puedes contactar con soporte@dyalogo.com</p></body></html>";

        $data = array(
            "strUsuario_t" => "crm",
            "strToken_t" => "D43dasd321",
            "strIdCfg_t" => "18",
            "strTo_t" => "$strDestinatario_p",
            "strCC_t" => null,
            "strCCO_t" => null,
            "strSubject_t" => "Dyalogo - contraseña Agente",
            "strMessage_t" => $strCuerpo_t,
            "strListaAdjuntos_t" => null
        );
        
        return $this->conexionApi('http://localhost:8080/dyalogocore/api/ce/correo/sendmailservice', $data);
    }

    /**
     * Este metodo se encarga de hacer la prueba de entrada de correo
     * @param String - strDestinatario_p direccion del destinatario
     * @param Int - intIdTablaCrud_p id de la configuracion
     * @param String - strTablaUsuario_p direccion del usuario
     * @param Int - intIdHuesped_p id del huesped
     * @return json - respuesta del api si fue exitosa o fallo
     */
    public function sendMailService($strDestinatario_p, $intIdTablaCrud_p, $strTablaUsuario_p, $intIdHuesped_p){
        $data = array(
            "strUsuario_t" => "crm",
            "strToken_t" => "D43dasd321",
            "strIdCfg_t" => "$intIdTablaCrud_p",
            "strTo_t" => "$strDestinatario_p ",
            "strCC_t" => "$strTablaUsuario_p",
            "strCCO_t" => null,
            "strSubject_t" => "Prueba envio correo",
            "strMessage_t" => "<html><body><h1>Mensaje de prueba HTML</h1>Este es un mensaje de prueba de la configuracion $strDestinatario_p PDYECE</body></html>",
            "strListaAdjuntos_t" => null,
            "intIdEstPas_t" => null,
            "intConsinteMiembro_t" => null,
            "intIdAgente_t" => null,
            "intIdHuesped_t" => $intIdHuesped_p
        );
        
        return $this->conexionApi('http://localhost:8080/dyalogocore/api/ce/correo/sendmailservice', $data);
    }

    /**
     * Este metodo se encarga de realizar el test de entrada de correo
     * @param Int - intIdDyCeConfiguracion_p id de la tabla de configuracion
     * @return json - respuesta del api si fue exitosa o fallo
     */
    public function testIn($intIdDyCeConfiguracion_p){
        $data = array(
            "strUsuario_t" => "crm",
            "strToken_t" => "D43dasd321",
            "idCfg_t" => "$intIdDyCeConfiguracion_p"
        );
        
        return $this->conexionApi('http://localhost:8080/dyalogocore/api/ce/correo/testin', $data);
    }

    /**
     * Este metodo se encarga de hacer la prueba de la cuenta de notificaciones
     * @param String - intIdHuesped id del huesped
     * @return json - respuesta del api si fue exitosa o fallo
     */
    public function testoutn($intIdHuesped_p){
        $data = array(
            "strUsuario_t" => "local",
            "strToken_t" => "local",
	        "idHuesped_t" => "$intIdHuesped_p",
	        "strPara_t" => "david.andrade@dyalogo.com",
        );

        return $this->conexionApi('http://localhost:8080/dyalogocore/api/ce/correo/testout', $data);
    }

    /**
     * Este metodo se encarga de crear la relacion de la troncal
     * @param String - intIdTroncal_p id de la troncal
     * @return json - respuesta del api si fue exitosa o fallo
     */
    public function troncalPersistir($intIdTroncal_p, $booGenerarRegistro_p){
        $data = array(
            "strUsuario_t" => 'local',
            "strToken_t" => 'local',
            "intIdTroncal_t" => $intIdTroncal_p,
            "booGenerarRegistroSIP_t" => $booGenerarRegistro_p
        );

        return $this->conexionApi('http://localhost:8080/dyalogocore/api/voip/troncales/persistir', $data);
    }

    /**
     * Este metodo se encarga de eliminar la relacion de la troncal
     * @param String - intIdTroncal_p id de la troncal
     * @return json - respuesta del api si fue exitosa o fallo
     */
    public function troncalBorrar($intIdTroncal_p){
        $data = array(
            "strUsuario_t" => 'crm',
            "strToken_t" => 'D43dasd321',
            "intIdTroncal_t" => $intIdTroncal_p
        );

        return $this->conexionApi('http://localhost:8080/dyalogocore/api/voip/troncales/borrar', $data);
    }

    /**
     * Este metodo se encarga de retornar el estado de la troncal
     * @param String - intIdTroncal_p id de la troncal
     * @return json - respuesta del api si fue exitosa o fallo
     */
    public function troncalEstado($intIdTroncal_p){
        $data = array(
            "strUsuario_t" => 'local',
            "strToken_t" => 'local',
            "intIdCfg_t" => $intIdTroncal_p
        );

        return $this->conexionApi('http://localhost:8080/dyalogocore/api/voip/troncales/estado', $data);
    }

    public function sendMailFromReset($to, $nombre, $pass){

        $miHost = env('CUSTOM_HOST');

        $strCuerpo_t = "<html><body><font face=\"arial\" size=\"3\">
        <p style=\"text-align:justify;\">Hola " . $nombre . ", Tus datos de acceso a Dyalogo son </p><p style=\"text-align:justify;\"> Usuario  : " . $to . " </p><p style=\"text-align:justify;\"> Password : " . $pass . " </p><p style=\"text-align:justify;\"> Url de ingreso : https://" . $miHost . "/ </p><p style=\"text-align:justify;\">Cualquier duda consulta  <a href='https://www.dyalogo.com/enviar-ticket-soporte'>www.dyalogo.com/enviar-ticket-soporte</></p>";

        $data = array(
            "strUsuario_t" => 'crm',
            "strToken_t" => 'D43dasd321',
            "strIdCfg_t" => 18,
            "strTo_t" => $to,
            "strCC_t" => null,
            "strCCO_t" => null,
            "strSubject_t" => "Dyalogo - contraseña Agente",
            "strMessage_t" => $strCuerpo_t,
            "strListaAdjuntos_t" => null
        );

        return $this->conexionApi('http://localhost:8080/dyalogocore/api/ce/correo/sendmailservice', $data);

    }
}
