<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use DB;
use App\Helpers\ServiceDymdw as Dymdw;
use App\Helpers\ServiceGupshup as Gupshup;

use App\Models\CanalesElectronicos\WaPlantillas;
use App\Models\CanalesElectronicos\WaVariablesPlantillas;

class WhatsappPlantillaController extends Controller
{

    public function index($id_huesped){

        $api = new Dymdw();
        $dataApi = $api->getChannels($id_huesped);
        $json = json_decode($dataApi);

        if(!$json) $respuesta = 'fallo'; else $respuesta = $json;

        $plantillas = [];

        // Recorremos los canales para traer las plantillas asociadas a ella
        foreach ($respuesta->channels as $key => $value) {
            $obj = WaPlantillas::where('id_canal_whatsapp', $value->id)->get();

            if(count($obj) > 0){

                for ($i=0; $i < count($obj); $i++) {

                    // Traemos la informacion del api solo si la cuenta es gupshup y validamos si el estado de la plantilla es PENDING
                    if($value->proveedor == 'gupshup' && !is_null($obj[$i]->id_plantilla_facebook) && $obj[$i]->estado == 'PENDING'){
                        // Me toca buscar el canal en especifico para obtener el token para hacer la busqueda

                        $dataWha = $api->getChannel($obj[$i]->id_canal_whatsapp);
                        $canalWhatsapp = json_decode($dataWha);

                        $gupshup = new Gupshup;

                        // Teniendo el canal ya podemos traer la lista de plantillas de gupshup
                        $listaPlantillas = $gupshup->getAllTemplates($canalWhatsapp->channel->channelid, $canalWhatsapp->channel->token);

                        // Buscamos la plantilla creada para obtener el estado de esta misma
                        if($listaPlantillas->status == 'success'){

                            $templates_ids = array_column($listaPlantillas->templates, 'id');
                            $found_key = array_search($obj[$i]->id_plantilla_facebook, $templates_ids);

                            if($found_key !== false){
                                $obj[$i]->estado = $listaPlantillas->templates[$found_key]->status;
                                // Ademas actualizo el estado
                                $plantillaUpdate = WaPlantillas::where('id', $obj[$i]->id)->first();
                                $plantillaUpdate->estado = $listaPlantillas->templates[$found_key]->status;
                                $plantillaUpdate->save();
                            }
                        }
                    }
                    array_push($plantillas, ["id" => $obj[$i]->id, "nombre" => $obj[$i]->nombre, "estado" => $obj[$i]->estado, "canal" => $value->proveedor, "nombreCuenta" => $value->nombre]);
                }
            }
        }

        $data = [
            'code' => 200,
            'status' => 'success',
            'plantillas' => $plantillas
        ];

        return response()->json($data, $data['code']);
    }

    public function show($id){

        $plantilla = WaPlantillas::find($id);

        $parametros = WaVariablesPlantillas::where('id_wa_plantilla', $id)->get();


        $data = [
            'code' => 200,
            'status' => 'success',
            'plantilla' => $plantilla,
            'parametros' => $parametros
        ];

        return response()->json($data, $data['code']);

    }

    public function store(Request $request, $id_huesped){

        if(!$request->ajax()){
            return redirect('/huesped');
        }

        try {

            $plantilla = new WaPlantillas();
            $plantilla->id_canal_whatsapp = $request->pCuenta;
            $plantilla->nombre = $request->pNombre;
            $plantilla->categoria = $request->pCategoria;
            $plantilla->etiqueta = $request->pEtiqueta;
            $plantilla->idioma = $request->pIdioma;

            $plantilla->tipo = $request->pTipo;
            $plantilla->proveedor = $request->pProveedor;

            // Dependiendo del tipo de plantilla debo guardar ciertos datos
            if($plantilla->tipo == 'TEXT'){

                // Valido que haya almenos algo en la cabecera
                if($request->pCabeceraTexto != ''){
                    $plantilla->cabecera = $request->pCabeceraTexto;
                }

            }else if($plantilla->tipo == 'IMAGE' || $plantilla->tipo == 'VIDEO' || $plantilla->tipo == 'DOCUMENT'){
                // TODO: Si es tipo media tendria que almacenar lo que llega y subirlo al proveedor
                $plantilla->tipo_media_ejemplo = 'https://....';
            }

            $plantilla->texto = $request->pContenido;

            if($request->pFooterTexto != ''){
                $plantilla->pie_pagina = $request->pFooterTexto;
            }

            // Analizamos si agregara botones
            if($request->pUsarBotones != 'ninguno'){

                $cuerpoBoton = [];

                if($request->pUsarBotones == 'llamada_a_la_accion'){
                    if(isset($request->pBotonTipoAccion)){

                        $tipoBotones = $request->pBotonTipoAccion;
                        $nombreBoton = $request->pTextoBoton;
                        $codPais = $request->pCodPais;
                        $numTelefono = $request->pNumTelefono;
                        $url = $request->pUrl;

                        // Recorro la lista de botones
                        for ($i=0; $i < count($tipoBotones); $i++) {

                            if($tipoBotones[$i] == 'telefono'){
                                $cuerpoBoton[$i]['type'] = 'PHONE_NUMBER';
                                $cuerpoBoton[$i]['text'] = $nombreBoton[$i];
                                $cuerpoBoton[$i]['phone_number'] = '+'.$codPais[$i].$numTelefono[$i];
                            }else{
                                $cuerpoBoton[$i]['type'] = 'URL';
                                $cuerpoBoton[$i]['text'] = $nombreBoton[$i];
                                $cuerpoBoton[$i]['url'] = $url[$i];
                                // $cuerpoBoton[$i]['example'] = 'https://bookins.gupshup.io/abc';
                            }

                        }

                        $plantilla->botones = json_encode($cuerpoBoton);
                    }
                }

                if($request->pUsarBotones == 'respuesta_rapida'){
                    if(isset($request->pBotonRespuestaRapida)){

                        $respuestasRapidas = $request->pBotonRespuestaRapida;

                        for ($i=0; $i < count($respuestasRapidas); $i++) {

                            $cuerpoBoton[$i]['type'] = 'QUICK_REPLY';
                            $cuerpoBoton[$i]['text'] = $respuestasRapidas[$i];
                        }

                        $plantilla->botones = json_encode($cuerpoBoton);
                    }
                }
            }


            $plantilla->texto_ejemplo = $request->pContenidoEjemplo;

            // Me toca extraer la informacion de la cuenta
            $dymdw = new Dymdw();
            $dataWha = $dymdw->getChannel($plantilla->id_canal_whatsapp);
            $canalWhatsapp = json_decode($dataWha);
            
            // Esto aplica solo si el proveedor es gupshup
            if($plantilla->proveedor == 'gupshup'){
    
                // Instancio el canal de gupshup
                $gupshup = new Gupshup;
    
                // Valido el tipo de plantilla, si tengo que crearla desde cero o ya estaba creada con anterioridad
                if($request->pAccionPlantilla == 'registrarNuevo'){
    
                    // Inicio el proceso para crear una nueva plantilla de gupshup
                    $plantillaGupshup = $gupshup->saveTemplate($plantilla, $canalWhatsapp->channel);
    
                    $plantilla->respuesta_api = json_encode($plantillaGupshup);
    
                    // Si todo nos fue bien
                    if($plantillaGupshup->status == 'success'){
                        $plantilla->id_plantilla_facebook = $plantillaGupshup->template->id;
                        $plantilla->estado = $plantillaGupshup->template->status;
                    }
    
                }else{
                    // Sino solo registro el id de la plantilla
                    $plantilla->id_plantilla_facebook = $request->pPlantillaId;
    
                    $listaPlantillas = $gupshup->getAllTemplates($canalWhatsapp->channel->channelid, $canalWhatsapp->channel->token);
    
                    // Buscamos la plantilla creada para obtener el estado de esta misma
                    if($listaPlantillas->status == 'success'){
    
                        for ($i=0; $i < count($listaPlantillas->templates); $i++) {
    
                            if($listaPlantillas->templates[$i]->id == $plantilla->id_plantilla_facebook){
                                $plantilla->estado = $listaPlantillas->templates[$i]->status;
                                break;
                            }
    
                        }
    
                    }
                }
            }

            if($plantilla->proveedor == 'infobip'){
                $plantilla->estado = 'REGISTRADO_EN_DYALOGO';
            }


            $plantilla->save();

            // https://blog.cpming.top/p/php-curl-x-www-form-urlencoded


                // Actializacion
                // $wa_plantillas = WaPlantillas::where('id', $request->plantillaId)->first();
                // $wa_plantillas->id_cuenta_whatsapp = $request->pCuenta;
                // $wa_plantillas->nombre_plantilla = $request->pNombre;
                // $wa_plantillas->contenido_plantilla = $request->pContenido;

                // $wa_plantillas->save();


            // Ahora actualizo los parametros
            for ($i=0; $i < $request->contParametrosPlnatilla; $i++) {

                $oper = $request->has('pParametro_edit_'.$i) ? 'edit' : 'add';

                $nombre = $request->input('pParametroNombre_'.$oper.'_'.$i);

                if(!is_null($nombre)){

                    if($oper == 'add'){
                        $parametro = new WaVariablesPlantillas();
                        $parametro->nombre = $nombre;
                        $parametro->id_wa_plantilla =  $plantilla->id;

                        $parametro->save();
                    }else{
                        // $id_parametro = $request->input('pParametro_edit_'.$i);

                        // $parametro = WaVariablesPlantillas::where('id', $id_parametro)->first();
                        // $parametro->nombre = $nombre;

                        // $parametro->save();
                    }
                }
            }

            // Creo un parametro especifico que almacenara ese valor cuando se invoque la plantilla
            if($plantilla->tipo == 'IMAGE' || $plantilla->tipo == 'VIDEO' || $plantilla->tipo == 'DOCUMENT' || $plantilla->tipo == 'LOCATION'){
                $parametro = new WaVariablesPlantillas();
                $parametro->nombre = 'DY_'.$plantilla->tipo;
                $parametro->id_wa_plantilla =  $plantilla->id;

                $parametro->save();
            }

            return response()->json([
                "estado" => "ok",
                'mensaje'=> 'Se ha guardado la informacion de la plantilla',
                "plantilla" => [
                    "nombre" => $plantilla->nombre,
                    "nombreCuenta" => $canalWhatsapp->channel->nombre,
                    "canal" => $canalWhatsapp->channel->proveedor_c,
                    "estado" => $plantilla->estado,
                    "id" => $plantilla->id
                ]
            ]);

        } catch (\Throwable $th) {
            // throw $th;
            return response()->json([
                'estado'=> 'fallo',
                'mensaje'=> 'Se ha producido un error al guardar la informacion',
                'error'=> $th
            ]);
        }

    }

    public function delete(Request $request, $id){

        $plantillaId = $id;
        $canal = $request->canal;

        // Si el canal es gupshup el service que eliminaria el canal definitivamente
        /*
        if($canal == 'gupshup'){

            $plantilla = WaPlantillas::where('id', $id)->get();

            $dymdw = new Dymdw();
            $dataWha = $dymdw->getChannel($plantilla->id_canal_whatsapp);
            $canalWhatsapp = json_decode($dataWha);

            $gupshup = new Gupshup;
            $gupshup->deleteTemplate($plantilla, $canalWhatsapp->channel);
        }*/

        WaVariablesPlantillas::where('id_wa_plantilla', $id)->delete();
        WaPlantillas::where('id', $id)->delete();

        $data = [
            'code' => 200,
            'status' => 'success',
            'message' => 'Su registro ha sido eliminado'
        ];

        return response()->json($data, $data['code']);
    }
}
