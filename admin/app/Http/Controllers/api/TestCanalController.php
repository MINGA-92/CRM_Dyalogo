<?php

namespace App\Http\Controllers\api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Log;

use App\Helpers\ServiceDymdw as Dymdw;

class TestCanalController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function inMessage(Request $request)
    {
        $fecha = date("Y-m-d H:i:s");
        $file = fopen("tests/channel_".$request->id.".log", "a");
        fwrite($file, "[ $fecha ] Message.INFO: Mensaje: $request->message" . PHP_EOL);
        fwrite($file, "[ $fecha ] Message.INFO: De: $request->from" . PHP_EOL);
        fclose($file);

        return response()->json([
            'status' => 'success',
            'message' => 'Se ha creado el canal de whatsapp',
        ], 200);
    }

    public function outMessage(Request $request, $id){
        
        $data = '';
        $ruta = "tests/channel_".$id.".log";

        if (file_exists($ruta)) {
            
            $file = fopen($ruta, "r");
            while(!feof($file)) {
                $data.= fgets($file);
            }
            fclose($file);

            unlink($ruta);

            $res = [
                'status' => 'success',
                'message' => 'Obteniendo mensajes',
                'data' => $data
            ];
        } else {
            $res = [
                'status' => 'success',
                'message' => 'Obteniendo mensajes',
                'data' => 'No se han recibido mensajes'
            ];
        }       

        return response()->json($res, 200);
    }

    public function activate($id){
        $api = new Dymdw();
        $dataApi = $api->activarTest($id);
        $json = json_decode($dataApi);

        if(!$json) $respuesta = 'fallo'; else $respuesta = $json;
        
        $data = [
            'code' => 200,
            'status' => 'success',
            'res' => $respuesta
        ];

        return response()->json($data, $data['code']);
    }

    public function deactivate($id){
        $api = new Dymdw();
        $dataApi = $api->desactivarTest($id);
        $json = json_decode($dataApi);

        if(!$json) $respuesta = 'fallo'; else $respuesta = $json;
        
        $data = [
            'code' => 200,
            'status' => 'success',
            'res' => $respuesta
        ];

        return response()->json($data, $data['code']);
    }

    public function enviarTest(Request $request){
        
        $api = new Dymdw();
        $dataApi = $api->enviarMensajeTest($request->to, $request->from);
        $json = json_decode($dataApi);

        if(!$json) $respuesta = 'fallo'; else $respuesta = $json;
        
        $data = [
            'code' => 200,
            'status' => 'success',
            'res' => $respuesta
        ];

        return response()->json($data, $data['code']);
    }

    /** Facebook methods */
    public function inMessageF(Request $request)
    {
        $fecha = date("Y-m-d H:i:s");
        $file = fopen("tests/channelf_".$request->id.".log", "a");
        fwrite($file, "[ $fecha ] Message.INFO: Mensaje: $request->message" . PHP_EOL);
        fwrite($file, "[ $fecha ] Message.INFO: De: $request->from" . PHP_EOL);
        fclose($file);

        return response()->json([
            'status' => 'success',
            'message' => 'Se ha creado el canal de whatsapp',
        ], 200);
    }

    public function outMessageF(Request $request, $id){
        
        $data = '';
        $ruta = "tests/channelf_".$id.".log";

        if (file_exists($ruta)) {
            
            $file = fopen($ruta, "r");
            while(!feof($file)) {
                $data.= fgets($file);
            }
            fclose($file);

            unlink($ruta);

            $res = [
                'status' => 'success',
                'message' => 'Obteniendo mensajes',
                'data' => $data
            ];
        } else {
            $res = [
                'status' => 'success',
                'message' => 'Obteniendo mensajes',
                'data' => 'No se han recibido mensajes, '.$ruta
            ];
        }       

        return response()->json($res, 200);
    }

    public function activateF($id){
        $api = new Dymdw();
        $dataApi = $api->activarTestF($id);
        $json = json_decode($dataApi);

        if(!$json) $respuesta = 'fallo'; else $respuesta = $json;
        
        $data = [
            'code' => 200,
            'status' => 'success',
            'res' => $respuesta
        ];

        return response()->json($data, $data['code']);
    }

    public function deactivateF($id){
        $api = new Dymdw();
        $dataApi = $api->desactivarTestF($id);
        $json = json_decode($dataApi);

        if(!$json) $respuesta = 'fallo'; else $respuesta = $json;
        
        $data = [
            'code' => 200,
            'status' => 'success',
            'res' => $respuesta
        ];

        return response()->json($data, $data['code']);
    }

    public function enviarTestF(Request $request){
        
        $api = new Dymdw();
        $dataApi = $api->enviarMensajeTestF($request->to, $request->from);
        $json = json_decode($dataApi);

        if(!$json) $respuesta = 'fallo'; else $respuesta = $json;
        
        $data = [
            'code' => 200,
            'status' => 'success',
            'res' => $respuesta
        ];

        return response()->json($data, $data['code']);
    }
}
