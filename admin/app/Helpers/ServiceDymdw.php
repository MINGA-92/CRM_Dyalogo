<?php
namespace App\Helpers;

use Illuminate\Support\Facades\DB;
use App\User;

class ServiceDymdw{

    private $host = 'http://127.0.0.1';
    private $remoteHost = 'http://127.0.0.1';

    public function __construct(){
        $this->remoteHost = env('DYMIDDLEWARE_HOST');
        $this->host = env('CUSTOM_HOST');
    }

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


    public function getChannels($id){
        $data = array(
            "intIdHuespedGeneralt" => $id,
            "strUsuario_t" => 'crm',
            "strToken_t" => 'D43dasd321',
            "strServer" => $this->host
        );
        return $this->conexionApi($this->remoteHost.'/dymdw/api/config/w/getChannels', $data);
    }

    public function getChannel($id){
        $data = array(
            "intId" => $id,
            "strUsuario_t" => 'crm',
            "strToken_t" => 'D43dasd321',
        );
        return $this->conexionApi($this->remoteHost.'/dymdw/api/config/w/getChannel', $data);
    }

    public function storeWhatsappChannels($request, $huesped){

        if($request->w_activo == 1){
            $whatsappActivo = 1;
        }else{
            $whatsappActivo = 0;
        }

        if($request->w_proveedor == 'botmaker'){
            $data = array(
                "strUsuario_t" => 'crm',
                "strToken_t" => 'D43dasd321',
                "intIdHuespedGeneralt" => $huesped,
                "activo" => $whatsappActivo,
                "nombre" => $request->w_nombre,
                "numero" => $request->w_numero,
                "proveedor" => $request->w_proveedor,
                "canal" => $request->w_numero,
                "app_id" => $request->p1ClienteId,
                "app_secret" => $request->p1SecretId,
                "token" => $request->p1Token,
                "vencimiento" => $request->w_vencimiento,
            );
        }else if($request->w_proveedor == 'iatech'){
            $data = array(
                "strUsuario_t" => 'crm',
                "strToken_t" => 'D43dasd321',
                "intIdHuespedGeneralt" => $huesped,
                "activo" => $whatsappActivo,
                "nombre" => $request->w_nombre,
                "numero" => $request->w_numero,
                "proveedor" => $request->w_proveedor,
                "canal" => $request->p2EscId,
                "app_id" => $request->p2Usuario,
                "app_secret" => $request->p2Contrasena,
                "token" => $request->w_token,
                "vencimiento" => $request->w_vencimiento,
            );
        }else if($request->w_proveedor = 'gupshup'){
            $data = array(
                "strUsuario_t" => 'crm',
                "strToken_t" => 'D43dasd321',
                "intIdHuespedGeneralt" => $huesped,
                "activo" => $whatsappActivo,
                "nombre" => $request->w_nombre,
                "numero" => $request->w_numero,
                "proveedor" => $request->w_proveedor,
                "canal" => $request->p3AppName,
                "app_id" => $request->p3AppId,
                "app_secret" => $request->p3Token,
                "token" => $request->p3ApiKey,
                "vencimiento" => '2030-12-31 00:00:00',
            );
        }else{
            $data = array(
                "strUsuario_t" => 'crm',
                "strToken_t" => 'D43dasd321',
                "intIdHuespedGeneralt" => $huesped,
                "activo" => $whatsappActivo,
                "nombre" => $request->w_nombre,
                "numero" => $request->w_numero,
                "canal" => $request->w_canal,
                "proveedor" => $request->w_proveedor,
                "app_id" => $request->w_appid,
                "app_secret" => $request->w_appsecret,
                "token" => $request->w_token,
                "vencimiento" => $request->w_vencimiento,
            );
        }
        $data['strServer'] = $this->host;

        return $this->conexionApi($this->remoteHost.'/dymdw/api/config/w/store', $data);
    }

    public function updateWhatsappChannels($request, $id){

        if($request->w_activo == 1){
            $whatsappActivo = 1;
        }else{
            $whatsappActivo = 0;
        }

        if($request->w_proveedor == 'botmaker'){
            $data = array(
                "strUsuario_t" => 'crm',
                "strToken_t" => 'D43dasd321',
                "id" => $id,
                "activo" => $whatsappActivo,
                "nombre" => $request->w_nombre,
                "numero" => $request->w_numero,
                "proveedor" => $request->w_proveedor,
                "canal" => $request->w_numero,
                "app_id" => $request->p1ClienteId,
                "app_secret" => $request->p1SecretId,
                "token" => $request->p1Token,
                "vencimiento" => $request->w_vencimiento,
            );

        }else if($request->w_proveedor == 'iatech'){
            $data = array(
                "strUsuario_t" => 'crm',
                "strToken_t" => 'D43dasd321',
                "id" => $id,
                "activo" => $whatsappActivo,
                "nombre" => $request->w_nombre,
                "numero" => $request->w_numero,
                "proveedor" => $request->w_proveedor,
                "canal" => $request->p2EscId,
                "app_id" => $request->p2Usuario,
                "app_secret" => $request->p2Contrasena,
                "token" => $request->w_token,
                "vencimiento" => $request->w_vencimiento,
            );
        }else if($request->w_proveedor == 'gupshup'){
            $data = array(
                "strUsuario_t" => 'crm',
                "strToken_t" => 'D43dasd321',
                "id" => $id,
                "activo" => $whatsappActivo,
                "nombre" => $request->w_nombre,
                "numero" => $request->w_numero,
                "proveedor" => $request->w_proveedor,
                "canal" => $request->p3AppName,
                "app_id" => $request->p3AppId,
                "app_secret" => $request->p3Token,
                "token" => $request->p3ApiKey,
                "vencimiento" => '2030-12-31 00:00:00',
            );
        }else{
            $data = array(
                "strUsuario_t" => 'crm',
                "strToken_t" => 'D43dasd321',
                "id" => $id,
                "activo" => $whatsappActivo,
                "nombre" => $request->w_nombre,
                "numero" => $request->w_numero,
                "canal" => $request->w_canal,
                "proveedor" => $request->w_proveedor,
                "app_id" => $request->w_appid,
                "app_secret" => $request->w_appsecret,
                "token" => $request->w_token,
                "vencimiento" => $request->w_vencimiento,
            );
        }

        $data['strServer'] = $this->host;

        return $this->conexionApi($this->remoteHost.'/dymdw/api/config/w/update', $data);
    }

    public function deleteChannelWhatsapp($id){
        $data = array(
            "intId" => $id,
            "strUsuario_t" => 'crm',
            "strToken_t" => 'D43dasd321',
        );
        return $this->conexionApi($this->remoteHost.'/dymdw/api/config/w/delete', $data);
    }

    public function activarTest($id){
        $data = array(
            "intId" => $id,
            "strUsuario_t" => 'crm',
            "strToken_t" => 'D43dasd321',
        );
        return $this->conexionApi($this->remoteHost.'/dymdw/api/config/w/activarTest', $data);
    }

    public function desactivarTest($id){
        $data = array(
            "intId" => $id,
            "strUsuario_t" => 'crm',
            "strToken_t" => 'D43dasd321',
        );
        return $this->conexionApi($this->remoteHost.'/dymdw/api/config/w/desactivarTest', $data);
    }

    public function enviarMensajeTest($to, $from){
        $data = array(
            "from" => $from,
            "message" => "Mensaje de prueba desde la aplicacion de admininstrador",
            "to" => $to,
            "mediaType" => "",
            "media" => "",
            "strUsuario_t" => 'crm',
            "strToken_t" => 'D43dasd321',
        );
        return $this->conexionApi($this->remoteHost.'/dymdw/api/whatsapp/msgout', $data);
    }

    /**
     * Comunicacion con facebook
     */
    public function getFacebookChannels($id){
        $data = array(
            "intIdHuespedGeneralt" => $id,
            "strUsuario_t" => 'crm',
            "strToken_t" => 'D43dasd321',
            "strServer" => $this->host
        );
        return $this->conexionApi($this->remoteHost.'/dymdw/api/config/f/getChannels', $data);
    }

    public function getFacebookChannel($id){
        $data = array(
            "intId" => $id,
            "strUsuario_t" => 'crm',
            "strToken_t" => 'D43dasd321',
        );
        return $this->conexionApi($this->remoteHost.'/dymdw/api/config/f/getChannel', $data);
    }

    public function storeFacebookChannels($request, $huesped){

        $data = array(
            "strUsuario_t" => 'crm',
            "strToken_t" => 'D43dasd321',
            "huesped" => $huesped,
            "nombre" => $request->f_nombre,
            "identificador" => $request->f_identificador,
            "token" => $request->f_token,
            "server" => $this->host
        );

        return $this->conexionApi($this->remoteHost.'/dymdw/api/config/f/store', $data);
    }

    public function updateFacebokChannels($request, $id){
        $data = array(
            "strUsuario_t" => 'crm',
            "strToken_t" => 'D43dasd321',
            "id" => $id,
            "nombre" => $request->f_nombre,
            "identificador" => $request->f_identificador,
            "token" => $request->f_token,
        );

        return $this->conexionApi($this->remoteHost.'/dymdw/api/config/f/update', $data);
    }

    public function deleteChannelFacebook($id){
        $data = array(
            "intId" => $id,
            "strUsuario_t" => 'crm',
            "strToken_t" => 'D43dasd321',
        );
        return $this->conexionApi($this->remoteHost.'/dymdw/api/config/f/delete', $data);
    }

    public function activarTestF($id){
        $data = array(
            "intId" => $id,
            "strUsuario_t" => 'crm',
            "strToken_t" => 'D43dasd321',
        );
        return $this->conexionApi($this->remoteHost.'/dymdw/api/config/f/activarTest', $data);
    }

    public function desactivarTestF($id){
        $data = array(
            "intId" => $id,
            "strUsuario_t" => 'crm',
            "strToken_t" => 'D43dasd321',
        );
        return $this->conexionApi($this->remoteHost.'/dymdw/api/config/f/desactivarTest', $data);
    }

    public function enviarMensajeTestF($to, $from){
        $data = array(
            "from" => $from,
            "message" => "Mensaje de prueba desde la aplicacion de admininstrador",
            "to" => $to,
            "mediaType" => "",
            "media" => "",
            "strUsuario_t" => 'crm',
            "strToken_t" => 'D43dasd321',
        );
        return $this->conexionApi($this->remoteHost.'/dymdw/api/facebook/msgout', $data);
    }

    // Instagram
    public function storeInstagramChannel($request, $huesped){

        $data = array(
            "strUsuario_t" => 'crm',
            "strToken_t" => 'D43dasd321',
            "huesped" => $huesped,
            "nombre" => $request->i_nombre,
            "identificador" => $request->i_identificador,
            "token" => $request->i_token,
            "server" => $this->host,
            "activo" => isset($request->i_activo) ? 1:0
        );

        return $this->conexionApi($this->remoteHost.'/dymdw/api/config/i/store', $data);
    }

    public function updateInstagramChannel($request, $id){

        $data = array(
            "strUsuario_t" => 'crm',
            "strToken_t" => 'D43dasd321',
            "id" => $id,
            "nombre" => $request->i_nombre,
            "identificador" => $request->i_identificador,
            "token" => $request->i_token,
            "activo" => isset($request->i_activo) ? 1:0
        );

        return $this->conexionApi($this->remoteHost.'/dymdw/api/config/i/update', $data);
    }

    public function deleteInstagramChannel($id){
        $data = array(
            "id" => $id,
            "strUsuario_t" => 'crm',
            "strToken_t" => 'D43dasd321',
        );
        return $this->conexionApi($this->remoteHost.'/dymdw/api/config/i/delete', $data);
    }
}
