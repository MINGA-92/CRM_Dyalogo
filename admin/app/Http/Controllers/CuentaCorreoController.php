<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\CuentaCorreoCreateRequest;
use App\Http\Requests\CuentaCorreoUpdateRequest;

use Carbon\Carbon;

use App\Models\CuentaCorreo;

class CuentaCorreoController extends Controller
{

    /**
     * muestra un registro de una cuenta de correo de un huesped.
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function showCuentaCorreo($id){
        $cuentaCorreo = CuentaCorreo::find($id);

        $data = [
            'code' => 200,
            'status' => 'success',
            'cuentaCorreo' => $cuentaCorreo
        ];

        return response()->json($data, $data['code']);
    }

    /**
     * Muestra todas las cuentas de correo de un huesped.
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function showAllCuentaCorreo($id){
        $cuentasCorreo = CuentaCorreo::where('id_huesped', $id)->get();

        $data = [
            'code' => 200,
            'status' => 'success',
            'cuentasCorreo' => $cuentasCorreo
        ];

        return response()->json($data, $data['code']);
    }

    /**
     * Guarda el registro de una cuenta de correo de un huesped.
     * @param int $id
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function storeCuentaCorreo(CuentaCorreoCreateRequest $request, $id){

        // Instancia de la api    
        $WSApi = new \WSRest();
        $dataApi = $WSApi->encript($request->contrasena);
        $json = json_decode($dataApi);

        if($json->strEstado_t == 'ok'){

            $registro = CuentaCorreo::guardar($request, $id, $json->objSerializar_t);
    
            if($registro){
                $data = [
                    'code' => 200,
                    'status' => 'success',
                    'message' => 'Se ha creado el registro exitosamente',
                ];
            }else{
                $data = [
                    'code' => 400,
                    'status' => 'error',
                    'message' => 'Se ha presentado un error al guardar los datos',
                ];
            }
        }else{
            $data = [
                'code' => 400,
                'status' => 'error',
                'message' => 'Se ha presentado un problema al encriptar la contraseña',
            ];
        }
        
        return response()->json($data, $data['code']);
    }

    /**
     * Actualiza el registro de una cuenta de correo de un huesped.
     * @param int $id
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function updateCuentaCorreo(CuentaCorreoUpdateRequest $request, $id){
        
        $cuentaCorreo = CuentaCorreo::find($id);

        $strEncript = null;
        // Instancia de la api
        if($request->contrasena){
            $WSApi = new \WSRest();
            $dataApi = $WSApi->encript($request->contrasena);
            $json = json_decode($dataApi);

            if($json->strEstado_t == 'ok'){
                $strEncript = $json->objSerializar_t;
            }

        }

        $registro = CuentaCorreo::actualizar($request, $cuentaCorreo, $strEncript);

        if($registro){
            $data = [
                'code' => 200,
                'status' => 'success',
                'message' => 'Se ha actualizado el registro exitosamente',
                'cuentaCorreo' => $registro
            ];
        }else{
            $data = [
                'code' => 400,
                'status' => 'error',
                'message' => 'Se ha presentado un error al guardar los datos',
            ];
        }
        return response()->json($data, $data['code']);
    }

    /**
     * elimina el registro de una cuenta de correo de un huesped.
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function deleteCuentaCorreo($id){
        CuentaCorreo::find($id)->delete();
        $data = [
            'code' => 200,
            'status' => 'success',
            'message' => 'Su registro ha sido eliminado'
        ];

        return response()->json($data, $data['code']);
    }

    /**
     * Esta funcion se encarga de consumir el api para hacer pruebas de salidas de correos.
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function testSendMailService(Request $request){
        if(!$request->ajax()){
            return redirect('/huesped'); 
        }

        // Validar los datos
        $validate = \Validator::make($request->all(), [
            'destinatario' => 'required|email',
        ]);

        if($validate->fails()){
            $data = [
                'code' => 422,
                'status' => 'error',
                'message' => 'Los datos no son validos :)',
                'errors' => $validate->errors()
            ];
        }else{
            // Instancia de la api
            $WSApi = new \WSRest();
            $dataApi = $WSApi->sendMailService($request->destinatario, $request->id_cuentaCorreo, $request->cuentaCorreoUsuario, $request->id_huesped);
            $json = json_decode($dataApi);

            $data = [
                'code' => 200,
                'status' => 'success',
                'message' => 'Se ha ejecutado el test ',
                'json' => $json
            ];
        }

        return response()->json($data, $data['code']);
    }

    /**
     * Esta funcion se encarga de consumir el api para hacer pruebas de entrada de correo
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function testEntrada(Request $request){
        // Instancia de la api
        $WSApi = new \WSRest();
        $dataApi = $WSApi->testIn($request->id_cuentaCorreo);
        $json = json_decode($dataApi);

        $data = [
            'code' => 200,
            'status' => 'success',
            'message' => 'Se ha ejecutado la prueba',
            'json' => $json
        ];

        return response()->json($data, $data['code']);
    }
}
