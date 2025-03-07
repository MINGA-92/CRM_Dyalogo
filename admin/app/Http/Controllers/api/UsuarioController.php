<?php

namespace App\Http\Controllers\api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Helpers\GenerarPassword;
use App\Helpers\EncriptarPassword;
use App\Helpers\webService\DatosRespuestaGenerica;

use App\User;

class UsuarioController extends Controller
{
    public function recordarClave(Request $request){
        
        $data = json_decode($request->getContent(), true);

        $dataResponse = new DatosRespuestaGenerica('fallo', 'No se pudo restablecer la contrasena');

        if(isset($data['strCorreo_t']) && $data['strCorreo_t'] != ''){

            if(filter_var($data['strCorreo_t'], FILTER_VALIDATE_EMAIL)){
                
                $user = User::where('USUARI_Correo___b', $data['strCorreo_t'])->first();
                
                if($user === null){
                    $dataResponse->setStrEstado_t('fallo');
                    $dataResponse->setStrMensaje_t('Correo electrónico no encontrado');
                }else{
                    $password = GenerarPassword::run();
                    $passwordEncriptado = EncriptarPassword::run($password);

                    // Actualizo el password
                    $user->USUARI_Clave_____b = $passwordEncriptado;
                    $user->USUARI_Clave_Temp_____b = -1;
                    $user->USUARI_FechUpdate_Clave______b = now();

                    if($user->save()){
                        //persistir usuario
                        $WSApi = new \WSRest();
                        $dataApi = $WSApi->usuarioPersistir(
                            $user->USUARI_Nombre____b,
                            $data['strCorreo_t'],
                            $user->USUARI_Identific_b,
                            $password,
                            $user->USUARI_ConsInte__PROYEC_b,
                            $user->USUARI_UsuaCBX___b
                        );

                        if(!empty($dataApi) && !is_null($dataApi)){

                            $json = json_decode($dataApi);

                            if(isset($json->strEstado_t)){
                                if($json->strEstado_t == 'ok'){
        
                                    // Envio de mensaje
                                    $dataMail = $WSApi->sendMailFromReset($data['strCorreo_t'], $user->USUARI_Nombre____b, $password);
        
                                    if(!empty($dataMail) && !is_null($dataMail)){
        
                                        $jsonMail = json_decode($dataMail);
        
                                        if($jsonMail->strEstado_t == 'ok'){
                                            $dataResponse->setStrEstado_t('ok');
                                            $dataResponse->setStrMensaje_t('Nueva contraseña generada, Por favor revise su correo electrónico '.$data['strCorreo_t']);
                                        }else{
                                            $dataResponse->setStrEstado_t('fallo');
                                            $dataResponse->setStrMensaje_t($jsonMail->strMensaje_t);
                                        }
                                    }else{
                                        $dataResponse->setStrEstado_t('fallo');
                                        $dataResponse->setStrMensaje_t('No se puede enviar la contraseña al correo electrónico');
                                    }
        
                                }else{
                                    $dataResponse->setStrEstado_t('fallo');
                                    $dataResponse->setStrMensaje_t($json->strMensaje_t);
                                }
                            }else{
                                $dataResponse->setStrEstado_t('fallo');
                                $dataResponse->setStrMensaje_t('No se pudo persistir el usuario, el payara fallo');    
                            }

                        }else{
                            $dataResponse->setStrEstado_t('fallo');
                            $dataResponse->setStrMensaje_t('No se pudo persistir el usuario');
                        }
                    }else{
                        $dataResponse->setStrEstado_t('fallo');
                        $dataResponse->setStrMensaje_t('No se pudo actualizar la contraseña debido a un fallo en el sistema');
                    }
                }
            }else{
                $dataResponse->setStrEstado_t('fallo');
                $dataResponse->setStrMensaje_t('Este correo no es valido');
            }
        }else{
            $dataResponse->setStrEstado_t('fallo');
            $dataResponse->setStrMensaje_t('Ingrese un correo valido');
        }
        
        $respuesta = [
            'strEstado_t' => $dataResponse->getStrEstado_t(),
            'strMensaje_t' => $dataResponse->getStrMensaje_t(),
            'objSerializar_t' => $dataResponse->getObjSerializar_t(),
        ];

        return response()->json($respuesta, 200);
    }
}
