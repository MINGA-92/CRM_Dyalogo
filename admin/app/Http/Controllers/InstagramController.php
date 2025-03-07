<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Helpers\ServiceDymdw as Dymdw;
use App\Models\CanalesElectronicos\CanalesDymdw;

class InstagramController extends Controller
{

    public function index($id){

        $canales = CanalesDymdw::where('tipo_canal', 'instagram')->where('id_huesped', $id)->get();

        return response()->json([
            'code' => 200,
            'status' => 'success',
            'canales' => $canales
        ], 200);
    }

    public function show($id){

        $canal = CanalesDymdw::find($id);

        return response()->json([
            'code' => 200,
            'status' => 'success',
            'canal' => $canal
        ], 200);
    }

    public function store(Request $request, $huesped_id){

        // Validamos los campos
        $validate = \Validator::make($request->all(), [
            'i_nombre' => 'required',
            'i_identificador' => 'required',
            'i_token' => 'required'
        ]);

        if($validate->fails()){
            return response()->json([
                'code' => 422, 'status' => 'error', 'message' => 'Los datos no son validos', 'errors' => $validate->errors()
            ], 422);
        }

        // Instanciamos el objeto de la conexion con dymdw
        $dymdw = new Dymdw();
        $resPeticion = $dymdw->storeInstagramChannel($request, $huesped_id);
        $peticionDecoded = json_decode($resPeticion);

        // Si no se obtiene nada o no se puede conectar al api externa
        if(is_null($peticionDecoded)){
            return response()->json([
                'code' => 200,
                'status' => 'fallo',
                'message' => 'No se ha podido crear el canal -> ' . $resPeticion
            ], 200);
        }

        if($peticionDecoded->strEstado_t == 'fallo'){
            return response()->json([
                'code' => 200,
                'status' => 'fallo',
                'message' => 'No se ha podido crear el canal'
            ], 200);
        }

        $instagram = new CanalesDymdw;
        
        $instagram->id_dymdw = 0;
        $instagram->nombre = $request->i_nombre; 
        $instagram->tipo_canal = 'instagram';
        $instagram->identificador = $request->i_identificador; 
        $instagram->token = $request->i_token; 
        $instagram->id_huesped = $huesped_id;
        $instagram->activo = isset($request->i_activo) ? 1:0;
        $instagram->id_cfg_chat = 0;

        if(!is_null($peticionDecoded) && isset($peticionDecoded->objSerializar_t) && is_numeric($peticionDecoded->objSerializar_t)){
            $instagram->id_dymdw = $peticionDecoded->objSerializar_t;
        }

        $instagram->save();

        return response()->json([
            'code' => 200,
            'status' => 'success',
            'message' => 'Se ha creado el canal de instagram',
            'canal' => $instagram
        ], 200);
    }

    public function update(Request $request, $id){

        // Validamos los campos
        $validate = \Validator::make($request->all(), [
            'i_nombre' => 'required',
            'i_identificador' => 'required',
            'i_token' => 'required'
        ]);

        if($validate->fails()){
            return response()->json([
                'code' => 422, 'status' => 'error', 'message' => 'Los datos no son validos', 'errors' => $validate->errors()
            ], 422);
        }

        // Instanciamos el objeto de la conexion con dymdw
        $instagram = CanalesDymdw::find($id);
        $dymdw = new Dymdw();

        // Dependiendo si esta sincronizado o no actualizamos o creamos en dymdw
        if($instagram->id_dymdw == 0){
            $resPeticion = $dymdw->storeInstagramChannel($request, $instagram->id_huesped);
        }else{
            $resPeticion = $dymdw->updateInstagramChannel($request, $instagram->id_dymdw);
        }

        $peticionDecoded = json_decode($resPeticion);

        // Si no se obtiene nada o no se puede conectar al api externa
        if(is_null($peticionDecoded)){
            return response()->json([
                'code' => 200,
                'status' => 'fallo',
                'message' => 'No se ha podido sincronizar canal -> ' . $resPeticion
            ], 200);
        }

        if($peticionDecoded->strEstado_t == 'fallo'){
            return response()->json([
                'code' => 200,
                'status' => 'fallo',
                'message' => 'No se ha podido sincronizar el canal ' . $peticionDecoded->strMensaje_t
            ], 200);
        }

        if(!is_null($peticionDecoded) && isset($peticionDecoded->objSerializar_t) && is_numeric($peticionDecoded->objSerializar_t)){
            $instagram->id_dymdw = $peticionDecoded->objSerializar_t;
        }

        $instagram->nombre = $request->i_nombre;
        $instagram->identificador = $request->i_identificador; 
        $instagram->token = $request->i_token; 
        $instagram->activo = isset($request->i_activo) ? 1:0;

        $instagram->save();

        return response()->json([
            'code' => 200,
            'status' => 'success',
            'message' => 'Se ha actualizado el canal de instagram',
            'canal' => $instagram
        ], 200);
    }

    public function delete($id){

        // Buscamos el registro a eliminar
        $instagram = CanalesDymdw::find($id);

        if($instagram->id_dymdw != 0){

            // Instanciamos el objeto de la conexion con dymdw
            $dymdw = new Dymdw();
            $resPeticion = $dymdw->deleteInstagramChannel($instagram->id_dymdw);
            $peticionDecoded = json_decode($resPeticion);
    
            // Si no se obtiene nada o no se puede conectar al api externa
            if(is_null($peticionDecoded)){
                return response()->json([
                    'code' => 200,
                    'status' => 'fallo',
                    'message' => 'No se ha podido eliminar canal -> ' . $resPeticion
                ], 200);
            }
    
            if($peticionDecoded->strEstado_t == 'fallo'){
                return response()->json([
                    'code' => 200,
                    'status' => 'fallo',
                    'message' => 'No se ha podido eliminar el canal'
                ], 200);
            }
            
        }

        $instagram->delete();

        return response()->json([
            'code' => 200,
            'status' => 'success',
            'message' => 'Se ha eliminado el canal de Instagram',
        ], 200);

    }
}