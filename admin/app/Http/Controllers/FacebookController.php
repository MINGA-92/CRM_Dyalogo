<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Helpers\ServiceDymdw as Dymdw;

class FacebookController extends Controller
{
    public function index($id_huesped){
        $api = new Dymdw();
        $dataApi = $api->getFacebookChannels($id_huesped);
        $json = json_decode($dataApi);

        if(!$json) $respuesta = 'fallo'; else $respuesta = $json;
        
        $data = [
            'code' => 200,
            'status' => 'success',
            'res' => $respuesta
        ];

        return response()->json($data, $data['code']);
    }

    public function show($id){
        $api = new Dymdw();
        $dataApi = $api->getFacebookChannel($id);
        $json = json_decode($dataApi);

        if(!$json) $respuesta = 'fallo'; else $respuesta = $json;
        
        $data = [
            'code' => 200,
            'status' => 'success',
            'res' => $respuesta
        ];

        return response()->json($data, $data['code']);
    }

    public function store(Request $request, $id_huesped){
        // Validar los datos
        $validate = \Validator::make($request->all(), [
            'f_nombre'      => 'required',
            'f_identificador'      => 'required',
            'f_token'   => 'required',
        ]);

        if($validate->fails()){
            $data = [
                'code' => 422,
                'status' => 'error',
                'message' => 'Los datos no son validos',
                'errors' => $validate->errors()
            ];
        }else{

            $api = new Dymdw();
            $dataApi = $api->storeFacebookChannels($request, $id_huesped);
            $json = json_decode($dataApi);

            if(!$json) $respuesta = 'fallo'; else $respuesta = $json;
            
            $data = [
                'code' => 200,
                'status' => 'success',
                'message' => 'Se ha creado el canal de whatsapp',
                'res' => $respuesta
            ];
        }

        return response()->json($data, $data['code']);
    }

    public function update(Request $request, $id){
        // Validar los datos
        $validate = \Validator::make($request->all(), [
            'f_nombre'      => 'required',
            'f_identificador'      => 'required',
            'f_token'   => 'required',
        ]);

        if($validate->fails()){
            $data = [
                'code' => 422,
                'status' => 'error',
                'message' => 'Los datos no son validos',
                'errors' => $validate->errors()
            ];
        }else{

            $api = new Dymdw();
            $dataApi = $api->updateFacebokChannels($request, $id);
            $json = json_decode($dataApi);

            if(!$json) $respuesta = 'fallo'; else $respuesta = $json;
            
            $data = [
                'code' => 200,
                'status' => 'success',
                'message' => 'Se ha actualizado el canal',
                'res' => $respuesta
            ];
        }

        return response()->json($data, $data['code']);
    }

    public function delete($id){
        $api = new Dymdw();
        $dataApi = $api->deleteChannelFacebook($id);
        $json = json_decode($dataApi);

        if(!$json) $respuesta = 'fallo'; else $respuesta = $json;
        
        $data = [
            'code' => 200,
            'status' => 'success',
            'message' => 'Se ha eliminado el canal de facebook',
            'res' => $respuesta
        ];

        return response()->json($data, $data['code']);
    }
}