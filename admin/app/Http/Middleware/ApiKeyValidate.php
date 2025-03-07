<?php

namespace App\Http\Middleware;

use Closure;
use App\Helpers\webService\DatosSolicitudGeneral;

class ApiKeyValidate extends DatosSolicitudGeneral
{
    public $arrUnauthorized = [
        'strEstado_t'=> 'fallo',
        'strMensaje_t' => 'unauthorized',
    ];

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {

        // SE VALIDA SI LA INFORMACION VIENE EN JSON
        
        if($request->header('Content-Type') == "application/json"){
            if(!$request->has('strUsuario_t') && !$request->has('strToken_t')){
                return response()->json($this->arrUnauthorized, 401);
            }

            $data = json_decode($request->getContent(), true);
        
            $this->setStrUsuario_t($data['strUsuario_t']);
            $this->setStrToken_t($data['strToken_t']);

        }else{

            // DE LO CONTRARIO SE TRATA LA INFORMACION COMO UN OBJETO EN CASO DE QUE VENGA DE UN FORMULARIO
            if(!$request->strUsuario_t && !$request->strToken_t){
                return response()->json($this->arrUnauthorized, 401);
            }

            $this->setStrUsuario_t($request->strUsuario_t);
            $this->setStrToken_t($request->strToken_t);

        }



        // Estos datos deben ser sacados de una lista de tokens disponibles pero aun no lo implementare
        $whiteIps = ['127.0.0.1', '::1'];
        $validarPorIp = false;
        $token = 'PGbtywunzaCwCLGSo7zj9CGLV9QxiVgJ';

        if($validarPorIp){
            if(!in_array($request->getClientIp(), $whiteIps)){
                return response()->json($this->arrUnauthorized, 401);
            }
        }

        if($this->getStrToken_t()){
            if($this->getStrToken_t() != $token){
                return response()->json($this->arrUnauthorized, 401);
            }
        }else{
            return response()->json($this->arrUnauthorized, 401);
        }

        return $next($request);
    }
}
