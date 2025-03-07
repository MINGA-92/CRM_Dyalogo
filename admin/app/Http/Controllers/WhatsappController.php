<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use DB;
use App\Helpers\ServiceDymdw as Dymdw;

class WhatsappController extends Controller
{
    public function index($id_huesped){
        $api = new Dymdw();
        $dataApi = $api->getChannels($id_huesped);
        $json = json_decode($dataApi);

        if(!$json) $respuesta = 'fallo'; else $respuesta = $json;

        $data = [
            'code' => 200,
            'status' => 'success',
            'res' => $respuesta,
            'otro' => $dataApi
        ];

        return response()->json($data, $data['code']);
    }

    public function show($id){
        $api = new Dymdw();
        $dataApi = $api->getChannel($id);
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
            'w_nombre'      => 'required',
            'w_numero'      => 'required',
            'w_proveedor'   => 'required',
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
            $dataApi = $api->storeWhatsappChannels($request, $id_huesped);
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
            'w_nombre'      => 'required',
            'w_numero'      => 'required',
            'w_proveedor'   => 'required',
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
            $dataApi = $api->updateWhatsappChannels($request, $id);
            $json = json_decode($dataApi);

            if(!$json) $respuesta = 'fallo'; else $respuesta = $json;

            $data = [
                'code' => 200,
                'status' => 'success',
                'message' => 'Se ha actualizado el canal de whatsapp',
                'res' => $respuesta
            ];
        }

        return response()->json($data, $data['code']);
    }

    public function delete($id){

        $api = new Dymdw();
        $dataApi = $api->deleteChannelWhatsapp($id);
        $json = json_decode($dataApi);

        if(!$json) $respuesta = 'fallo'; else $respuesta = $json;

        $data = [
            'code' => 200,
            'status' => 'success',
            'message' => 'Se ha eliminado el canal de whatsapp',
            'res' => $respuesta
        ];

        return response()->json($data, $data['code']);
    }
}
