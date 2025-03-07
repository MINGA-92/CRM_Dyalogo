<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Carbon\Carbon;

use App\Models\ProveedorSms;

class ProveedorSmsController extends Controller
{
    /**
     * Retorna el registro de un proveedor.
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function ShowProveedorSms($id){
        $proveedorSms = ProveedorSms::find($id);

        $data = [
            'code' => 200,
            'status' => 'success',
            'proveedorSms' => $proveedorSms
        ];

        return response()->json($data, $data['code']);
    }

    /**
     * Retorna una lista de todos los proveedores de sms de un huesped en espepecifico.
     * @param int $id_huesped
     * @return \Illuminate\Http\Response
     */
    public function showAllProveedorSms($id_huesped){
        $proveedoresSms = ProveedorSms::where('id_huesped', $id_huesped)->orWhere('id_huesped', '-1')->get();

        $data = [
            'code' => 200,
            'status' => 'success',
            'proveedoresSms' => $proveedoresSms
        ];

        return response()->json($data, $data['code']);
    }

    /**
     * Guarda el registro de un proveedor de sms.
     * @param int $id_huesped
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function storeProveedorSms(Request $request, $id_huesped){
        
        if(!$request->ajax()){
            return redirect('/huesped'); 
        }

        // Validar los datos
        $validate = \Validator::make($request->all(), [
            'proveedor'      => 'required|max:255',
            // 'url_api'        => 'required|max:255',
            // 'url_api_ssl'    => 'required|max:255',
            // 'api_key'        => 'required|max:40',
            'api_secret'     => 'required|max:255',
            'nombre'         => 'required|max:255',
            'longitud_maxima'=> 'required|numeric',
        ]);

        if($validate->fails()){
            $data = [
                'code' => 422,
                'status' => 'error',
                'message' => 'Los datos no son validos :)',
                'errors' => $validate->errors()
            ];
        }else{
            $WSApi = new \WSRest();
            $dataApi = $WSApi->encript($request->api_secret);
            
            $json = json_decode($dataApi);

            if($json->strEstado_t == 'ok'){
                $nuevoProveedor = new ProveedorSms();

                $nuevoProveedor->api_secret = $json->objSerializar_t;

                $nuevoProveedor->id_huesped = $id_huesped;
                $nuevoProveedor->proveedor = $request->proveedor;
                $nuevoProveedor->fecha_creacion = Carbon::now();
                $nuevoProveedor->url_api = $request->url_api;
                $nuevoProveedor->url_api_ssl = $request->url_api_ssl;
                $nuevoProveedor->api_key = $request->api_key;
                $nuevoProveedor->nombre = $request->nombre;
                $nuevoProveedor->longitud_maxima_sms = $request->longitud_maxima;
    
                $nuevoProveedor->save();
    
                $data = [
                    'code' => 200,
                    'status' => 'success',
                    'message' => 'Se ha creado el registro de manera exitosa',
                    'nuevoProveedor' => $nuevoProveedor
                ];
            }else{
                $data = [
                    'code' => 400,
                    'status' => 'error',
                    'message' => 'Ha habido un error al encriptar el API KEY'
                ];
            }
        }

        return response()->json($data, $data['code']);
    }

    /**
     * actualiza el registro de un proveedor de sms.
     * @param int $id_huesped
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function updateProveedorSms(Request $request, $id){

        if(!$request->ajax()){
            return redirect('/huesped'); 
        }
        
        // Validar los datos
        $validate = \Validator::make($request->all(), [
            'proveedor'      => 'required|max:255',
            // 'url_api'        => 'required|max:255',
            // 'url_api_ssl'    => 'required|max:255',
            // 'api_key'        => 'required|max:40',
            'nombre'         => 'required|max:255',
            'longitud_maxima'=> 'required|numeric',
        ]);

        if($validate->fails()){
            $data = [
                'code' => 422,
                'status' => 'error',
                'message' => 'Los datos no son validos',
                'errors' => $validate->errors()
            ];
        }else{

            $proveedorSms = ProveedorSms::find($id);
            
            // Si se ha recibido una nueva api_secret se hace denuevo el consumo
            if($request->api_secret){
                $WSApi = new \WSRest();
                $dataApi = $WSApi->encript($request->api_secret);
                $json = json_decode($dataApi);
                
                if($json->strEstado_t == 'ok'){
                    $proveedorSms->api_secret = $json->objSerializar_t;
                }
            }
            $proveedorSms->proveedor = $request->proveedor;
            $proveedorSms->url_api = $request->url_api;
            $proveedorSms->url_api_ssl = $request->url_api_ssl;
            $proveedorSms->api_key = $request->api_key;
            $proveedorSms->nombre = $request->nombre;
            $proveedorSms->longitud_maxima_sms = $request->longitud_maxima;
    
            $proveedorSms->save();

            $data = [
                'code' => 200,
                'status' => 'success',
                'message' => 'Se ha actualizado el registro de manera exitosa',
                'proveedorSms' => $proveedorSms
            ];
        }
        return response()->json($data, $data['code']);
    }

    /**
     * elimina el registro de un proveedor de sms.
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function deleteProveedorSms($id){
        ProveedorSms::find($id)->delete();
        $data = [
            'code' => 200,
            'status' => 'success',
            'message' => 'Su registro ha sido eliminado'
        ];

        return response()->json($data, $data['code']);
    }

    /**
     * Esta funcion se encarga de consumir el api para hacer pruebas de envios de sms.
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function pruebaEnviarSms(Request $request){
        if(!$request->ajax()){
            return redirect('/huesped'); 
        }

        // Validar los datos
        $validate = \Validator::make($request->all(), [
            'sms_telefono' => 'required|numeric',
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
            $dataApi = $WSApi->enviarSms($request->sms_telefono, $request->id_proveedorSms, $request->id_huesped);
            $json = json_decode($dataApi);
            
            $data = [
                'code' => 200,
                'status' => 'success',
                'message' => 'Se ha ejecutado la prueba exitosamente',
                'json' => $json
            ];
        }

        return response()->json($data, $data['code']);
    }
}
