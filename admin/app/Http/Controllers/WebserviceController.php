<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\General\WsGeneral;
use App\Models\General\WsHeaders;
use App\Models\General\WsParametros;
use App\Models\General\WsParametrosRetorno;

class WebserviceController extends Controller
{

    public function index($id_huesped){

        $webservices = WsGeneral::where('id_huesped', $id_huesped)->get();

        $data = [
            'code' => 200,
            'status' => 'success',
            'webservices' => $webservices
        ];

        return response()->json($data, $data['code']);
    }

    public function show($id){

        $webservice = WsGeneral::find($id);

        $headers = WsHeaders::where('id_ws', $id)->get();

        $parametros = WsParametros::where('id_ws', $id)->where('sentido', 'IN')->get();

        $parametrosRetorno = WsParametros::where('id_ws', $id)->where('sentido', 'OUT')->get();

        $data = [
            'code' => 200,
            'status' => 'success',
            'webservice' => $webservice,
            'headers' => $headers,
            'parametros' => $parametros,
            'parametrosRetorno' => $parametrosRetorno
        ];

        return response()->json($data, $data['code']);

    }

    public function store(Request $request, $id_huesped){

        if(!$request->ajax()){
            return redirect('/huesped');
        }

        try {
            // Primero valido si tengo que crear o actualizar la tabla ws_general
            if($request->wsAccion == 'add'){
                $ws_general = new WsGeneral();
                $ws_general->nombre = $request->ws_nombre;
                $ws_general->metodo = $request->wsMetodo;
                $ws_general->url = $request->wsUrl;
                $ws_general->id_huesped = $id_huesped;

                $ws_general->save();
            }else{

                $ws_general = WsGeneral::where('id', $request->wsId)->first();
                $ws_general->nombre = $request->ws_nombre;
                $ws_general->metodo = $request->wsMetodo;
                $ws_general->url = $request->wsUrl;
                $ws_general->funcion_requerida = isset($request->funcionRequerida) ? $request->funcionRequerida : NULL;

                $ws_general->save();
            }

            // Luego recorro los headers para actualizar o insertar
            for ($i=0; $i < $request->wsCantHeaders; $i++) {

                $oper = $request->has('wsCampo_edit_'.$i) ? 'edit' : 'add';

                $nombre = $request->input('wsHeaderNombre_'.$oper.'_'.$i);
                $value = $request->input('wsHeaderValor_'.$oper.'_'.$i);
                $descripcion = $request->input('wsHeaderDescripcion_'.$oper.'_'.$i);

                if($oper == 'add'){
                    $header = new WsHeaders();
                    $header->nombre = $nombre;
                    $header->valor = $value;
                    $header->descripcion = $descripcion;
                    $header->id_ws =  $ws_general->id;

                    $header->save();
                }else{
                    $id_header = $request->input('wsCampo_edit_'.$i);

                    $header = WsHeaders::where('id', $id_header)->first();
                    $header->nombre = $nombre;
                    $header->valor = $value;
                    $header->descripcion = $descripcion;

                    $header->save();
                }
            }

            // Ahora actualizo los parametros
            for ($i=0; $i < $request->wsCantParametros; $i++) {

                $oper = $request->has('wsParametro_edit_'.$i) ? 'edit' : 'add';

                $nombre = $request->input('wsParametroNombre_'.$oper.'_'.$i);
                $tipo = $request->input('wsParametroTipo_'.$oper.'_'.$i);

                if(!is_null($nombre)){

                    if($oper == 'add'){
                        $parametro = new WsParametros();
                        $parametro->parametro = $nombre;
                        $parametro->id_ws =  $ws_general->id;
                        $parametro->sentido = 'IN';
                        $parametro->tipo = $tipo;

                        $parametro->save();
                    }else{
                        $id_parametro = $request->input('wsParametro_edit_'.$i);

                        $parametro = WsParametros::where('id', $id_parametro)->first();
                        $parametro->parametro = $nombre;
                        $parametro->sentido = 'IN';
                        $parametro->tipo = $tipo;

                        $parametro->save();
                    }

                }

            }

            // Ahora actualizo los parametros de retorno
            for ($i=0; $i < $request->wsCantParametrosRetorno; $i++) {

                $oper = $request->has('wsParametroRetorno_edit_'.$i) ? 'edit' : 'add';

                $nombre = $request->input('wsParametroRetornoNombre_'.$oper.'_'.$i);
                $tipo = $request->input('wsParametroRetornoTipo_'.$oper.'_'.$i);

                if(!is_null($nombre)){

                    if($oper == 'add'){
                        $parametroRetorno = new WsParametros();
                        $parametroRetorno->parametro = $nombre;
                        $parametroRetorno->id_ws =  $ws_general->id;
                        $parametroRetorno->sentido = 'OUT';
                        $parametroRetorno->tipo = $tipo;

                        $parametroRetorno->save();
                    }else{
                        $id_parametro = $request->input('wsParametroRetorno_edit_'.$i);

                        $parametroRetorno = WsParametros::where('id', $id_parametro)->first();
                        $parametroRetorno->parametro = $nombre;
                        $parametroRetorno->sentido = 'OUT';
                        $parametroRetorno->tipo = $tipo;

                        $parametroRetorno->save();
                    }

                }

            }

            return response()->json([
                "estado" => "ok",
                'mensaje'=> 'Se ha guardado la informacion del webservice'
            ]);
        } catch (\Throwable $th) {
            //throw $th;
            return response()->json([
                'estado'=> 'fallo',
                'mensaje'=> 'Se ha producido un error al guardar la informacion',
                'error'=> $th
            ]);
        }

    }

    public function delete($id){

        WsHeaders::where('id_ws', $id)->delete();
        WsParametros::where('id_ws', $id)->delete();
        WsParametros::where('id_ws', $id)->delete();

        WsGeneral::where('id', $id)->delete();

        $data = [
            'code' => 200,
            'status' => 'success',
            'message' => 'Su registro ha sido eliminado'
        ];

        return response()->json($data, $data['code']);
    }

    public function deleteHeader($id){

        WsHeaders::where('id', $id)->delete();

        $data = [
            'code' => 200,
            'status' => 'success',
            'message' => 'Su registro ha sido eliminado'
        ];

        return response()->json($data, $data['code']);
    }

    public function deleteParametro($id){

        WsParametros::where('id', $id)->delete();

        $data = [
            'code' => 200,
            'status' => 'success',
            'message' => 'Su registro ha sido eliminado'
        ];

        return response()->json($data, $data['code']);
    }

    public function deleteParametroRetorno($id){
        WsParametros::where('id', $id)->delete();

        $data = [
            'code' => 200,
            'status' => 'success',
            'message' => 'Su registro ha sido eliminado'
        ];

        return response()->json($data, $data['code']);
    }
}
