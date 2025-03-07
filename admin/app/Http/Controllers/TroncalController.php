<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use DB;

use App\Models\Troncal;
use App\Models\TroncalConfiguracion;
use App\Models\PasosTroncales;
use App\Models\Dy_Proyectos;
use App\Models\TroncalPropiedades;

class TroncalController extends Controller
{

    /**
     * Obtine todas las troncales de un huesped en especifico
     * @param int $id_huesped
     * @return \Illuminate\Http\Response
     */
    public function getTroncales($id_huesped){
        // Traigo el id_proyecto
        $proyecto = Dy_Proyectos::where('id_huesped', $id_huesped)->first();

        $troncales = Troncal::where('id_proyecto', $proyecto->id)->get();

        $data = [
            'code' => 200,
            'status' => 'success',
            'troncales' => $troncales
        ];

        return response()->json($data, $data['code']);
    }

    /**
     * Obtinen una troncal en especifico
     * @param int $id_troncal
     * @return \Illuminate\Http\Response
     */
    public function getTroncal($id_troncal){

        $troncal = Troncal::where('id', $id_troncal)->with('configuraciones', 'configuraciones.propiedad')->first();

        $listaPropiedades = TroncalPropiedades::select('id','propiedad')->get();

        $data = [
            'code' => 200,
            'status' => 'success',
            'troncal' => $troncal,
            'propiedades' => $listaPropiedades
        ];

        return response()->json($data, $data['code']);
    }

    /**
     * Esta funcion se encarga de crear una troncal para un huesped en especifico
     * @param \Illuminate\Http\Request  $request
     * @param int $id_huesped
     * @return \Illuminate\Http\Response
     */
    public function storeTroncal(Request $request, $id_huesped){
        // Validar los datos
        $validate = \Validator::make($request->all(), [
            'troncal_nombre_usuario'        => 'required|max:200',
            'troncal_codigo_cuenta'         => 'max:200',
            'troncal_tipo'                  => 'required|max:200',
            'troncal_direccion_servidor'    => 'required|max:200',
            'troncal_usuario_defecto'       => 'max:200',
            'troncal_contrasena'            => 'max:200',
            'troncal_fuente'                => 'max:200',
            'troncal_compensar_rfc2833'     => 'required|max:200',
            'troncal_limite_llamadas'       => 'required|max:200',
            'troncal_contexto'              => 'required|max:200',
            'troncal_habilitar_puente_rtp'  => 'required|max:200',
            'troncal_autenticacion'         => 'required|max:200',
            'troncal_nat'                   => 'required|max:200',
            'troncal_permitir_verificacion' => 'required|max:200',
        ]);

        if($validate->fails()){
            $data = [
                'code' => 422,
                'status' => 'error',
                'message' => 'Los datos no son validos',
                'errors' => $validate->errors()
            ];
        }else{

            $con1 = DB::connection('general');
            $con2 = DB::connection('telefonia');

            $con1->beginTransaction();
            $con2->beginTransaction();

            try {

                // Traigo el id_proyecto
                $proyecto = Dy_Proyectos::where('id_huesped', $id_huesped)->first();

                $troncal = new Troncal();

                $troncal->nombre_interno = 'trx_'.$proyecto->id.'_troncal';
                $troncal->nombre_usuario = $request->troncal_nombre_usuario;
                $troncal->tipo = 'sip';
                $troncal->borrado = 0;
                $troncal->id_proyecto = $proyecto->id;
                $troncal->codigo_cuenta = $request->troncal_codigo_cuenta;
                $troncal->usar_codigo_antepuesto = ($request->usar_codigo_antepuesto) ? 1 : 0;
                $troncal->pbx_distribuido_proveedor = 'claro';

                if(isset($request->usar_codigo_antepuesto)){
                    $troncal->codigo_antepuesto = $request->troncal_codigo_antepuesto;
                }else{
                    $troncal->codigo_antepuesto = '';
                }

                $troncal->save();

                $troncal->nombre_interno = 'TRX_'.$troncal->id;
                $troncal->save();

                // Revisamos las propiedades y ordenamos
                $propiedades = [];
                $orden = 1;

                array_push($propiedades, ['id_troncal'=> $troncal->id, 'id_propiedad'=> 51, 'valor'=> $request->troncal_tipo, 'orden'=> $orden]);
                $orden++;

                array_push($propiedades,['id_troncal'=> $troncal->id, 'id_propiedad'=> 43, 'valor'=> $request->troncal_direccion_servidor, 'orden'=> $orden]);
                $orden++;

                // Verificamos si viene un usuario por defecto
                if(isset($request->troncal_usuario_defecto) && $request->troncal_usuario_defecto != ''){
                    array_push($propiedades,['id_troncal'=> $troncal->id, 'id_propiedad'=> 39, 'valor'=> ($request->troncal_usuario_defecto) ? $request->troncal_usuario_defecto:'', 'orden'=> $orden]);
                    $orden++;
                }

                // Verificamos si viene la fuente
                if(isset($request->troncal_fuente) && $request->troncal_fuente != ''){
                    array_push($propiedades,['id_troncal'=> $troncal->id, 'id_propiedad'=> 42, 'valor'=> ($request->troncal_fuente) ? $request->troncal_fuente:'', 'orden'=> $orden]);
                    $orden++;
                }

                // Verificamos si viene con contraseña
                if(isset($request->troncal_contrasena) && $request->troncal_contrasena != ''){
                    array_push($propiedades,['id_troncal'=> $troncal->id, 'id_propiedad'=> 49, 'valor'=> ($request->troncal_contrasena) ? $request->troncal_contrasena:'', 'orden'=> $orden]);
                    $orden++;
                }
                
                array_push($propiedades, ['id_troncal'=> $troncal->id, 'id_propiedad'=> 48, 'valor'=> $request->troncal_compensar_rfc2833, 'orden'=> $orden]);
                $orden++;

                array_push($propiedades, ['id_troncal'=> $troncal->id, 'id_propiedad'=> 35, 'valor'=> $request->troncal_limite_llamadas, 'orden'=> $orden]);
                $orden++;
                array_push($propiedades, ['id_troncal'=> $troncal->id, 'id_propiedad'=> 38, 'valor'=> $request->troncal_contexto, 'orden'=> $orden]);
                $orden++;
                array_push($propiedades, ['id_troncal'=> $troncal->id, 'id_propiedad'=> 37, 'valor'=> $request->troncal_habilitar_puente_rtp, 'orden'=> $orden]);
                $orden++;
                array_push($propiedades, ['id_troncal'=> $troncal->id, 'id_propiedad'=> 52, 'valor'=> $request->troncal_autenticacion, 'orden'=> $orden]);
                $orden++;
                array_push($propiedades, ['id_troncal'=> $troncal->id, 'id_propiedad'=> 44, 'valor'=> $request->troncal_nat, 'orden'=> $orden]);
                $orden++;
                array_push($propiedades, ['id_troncal'=> $troncal->id, 'id_propiedad'=> 40, 'valor'=> $request->troncal_mododtmf, 'orden'=> $orden]);
                $orden++;
                array_push($propiedades, ['id_troncal'=> $troncal->id, 'id_propiedad'=> 47, 'valor'=> $request->troncal_permitir_verificacion, 'orden'=> $orden]);
                $orden++;
                // Valido si las propiedades existen de lo contrario me toca crearla como nueva
                $propiedadTroncal1 = TroncalPropiedades::firstOrCreate(['propiedad' => 'Codec_U']);
                array_push($propiedades, ['id_troncal'=> $troncal->id, 'id_propiedad'=> $propiedadTroncal1->id, 'valor'=> ($request->troncal_codec_u) ? 1 : 0, 'orden'=> $orden]);
                $orden++;
                $propiedadTroncal2 = TroncalPropiedades::firstOrCreate(['propiedad' => 'Codec_A']);
                array_push($propiedades,['id_troncal'=> $troncal->id, 'id_propiedad'=> $propiedadTroncal2->id, 'valor'=> ($request->troncal_codec_a) ? 1 : 0, 'orden'=> $orden]);
                $orden++;
                $propiedadTroncal3 = TroncalPropiedades::firstOrCreate(['propiedad' => 'Codec_G729']);
                array_push($propiedades,['id_troncal'=> $troncal->id, 'id_propiedad'=> $propiedadTroncal3->id, 'valor'=> ($request->troncal_g729) ? 1 : 0, 'orden'=> $orden]);
                $orden++;

                // En esta seccion se registrara las propiedades de la troncal en dyalogo_telefonia.dy_configuracion_troncales
                $configuracionTroncal = TroncalConfiguracion::insert($propiedades);

                // Validamos si tenemos propiedades adicionales
                if(isset($request->troncal_prop_tipo)){

                    $nombreProp = $request->troncal_prop_tipo;
                    $valorProp = $request->troncal_prop_valor;
                    $idProp = $request->troncal_prop_id;

                    for ($i=0; $i < count($nombreProp); $i++) { 
                        
                        // Valido si el registro es nuevo o se edita
                        // Nueva
                        if($idProp[$i] == 0){
                            // Si se agrega valido si es una propiedad nueva o existente
                            if(is_numeric($nombreProp[$i])){
                                // Al ser numerica es una propiedad existente
                                TroncalConfiguracion::insert(['id_troncal'=> $troncal->id, 'id_propiedad'=> $nombreProp[$i], 'valor'=> $valorProp[$i], 'orden'=> $orden]);
                                $orden++;
                            }else{
                                // Si es una propiedad como tal nueva valido que exista
                                $propiedad = TroncalPropiedades::firstOrCreate(['propiedad' => $nombreProp[$i]], ['tipo' => '', 'valor_por_defecto' => '', 'archivo' => '', 'nombre' => $nombreProp[$i], 'descripcion' => '']);
                                TroncalConfiguracion::insert(['id_troncal'=> $troncal->id, 'id_propiedad'=> $propiedad->id, 'valor'=> $valorProp[$i], 'orden'=> $orden]);
                                $orden++;
                            }
                        }else{
                            // Existente
                            TroncalConfiguracion::where('id', $idProp[$i])->update(['id_propiedad' => $nombreProp[$i], 'valor' => $valorProp[$i], 'orden' => $orden]);
                            $orden++;
                        }
                    }

                }

                $con1->commit();
                $con2->commit();

                $generarRegistro = ($request->generarRegistro) ? true : false;

                // Llamade del web service de guardado y serializado
                $WSApi = new \WSRest();
                $dataApi = $WSApi->troncalPersistir($troncal->id, $generarRegistro);
                $json = json_decode($dataApi);

                $data = [
                    'code' => 200,
                    'status' => 'success',
                    'message' => 'Se ha creado la troncal',
                    'troncal' => $troncal,
                    'json' => $json
                ];

            } catch (\Throwable $th) {
                //throw $th;
                $con1->rollBack();
                $con2->rollBack();

                \Log::error($th);

                $data = [
                    'code' => 400,
                    'status' => 'error',
                    'message' => 'Se ha presentado un error al guardar los datos',
                ];
            }

        }

        return response()->json($data, $data['code']);

    }

    /**
     * Actualiza los datos de una troncal
     * @param \Illuminate\Http\Request  $request
     * @param int $id_huesped
     * @return \Illuminate\Http\Response
     */
    public function updateTroncal(Request $request, $id_troncal){
        // Validar los datos
        $validate = \Validator::make($request->all(), [
            'troncal_nombre_usuario'        => 'required|max:200',
            'troncal_codigo_cuenta'         => 'max:200',
            'troncal_tipo'                  => 'required|max:200',
            'troncal_direccion_servidor'    => 'required|max:200',
            'troncal_usuario_defecto'       => 'max:200',
            'troncal_contrasena'            => 'max:200',
            'troncal_fuente'                => 'max:200',
            'troncal_compensar_rfc2833'     => 'required|max:200',
            'troncal_limite_llamadas'       => 'required|max:200',
            'troncal_contexto'              => 'required|max:200',
            'troncal_habilitar_puente_rtp'  => 'required|max:200',
            'troncal_autenticacion'         => 'required|max:200',
            'troncal_nat'                   => 'required|max:200',
            'troncal_permitir_verificacion' => 'required|max:200',
        ]);

        if($validate->fails()){
            $data = [
                'code' => 422,
                'status' => 'error',
                'message' => 'Los datos no son validos',
                'errors' => $validate->errors()
            ];
        }else{

            $con1 = DB::connection('general');
            $con2 = DB::connection('telefonia');

            $con1->beginTransaction();
            $con2->beginTransaction();

            try {

                $troncal = Troncal::find($id_troncal);

                $troncal->nombre_usuario = $request->troncal_nombre_usuario;
                $troncal->codigo_cuenta = $request->troncal_codigo_cuenta;
                $troncal->usar_codigo_antepuesto = ($request->usar_codigo_antepuesto) ? 1 : 0;
                $troncal->nombre_interno = 'TRX_'.$troncal->id;

                if(isset($request->usar_codigo_antepuesto)){
                    $troncal->codigo_antepuesto = $request->troncal_codigo_antepuesto;
                }else{
                    $troncal->codigo_antepuesto = '';
                }

                $troncal->save();

                $orden = 1;

                // Metodo para actualizar los campos de propiedades de la troncal
                TroncalConfiguracion::where('id_troncal', $id_troncal)->where('id_propiedad', 51)->update(['valor' => $request->troncal_tipo, 'orden' => $orden]);
                $orden++;
                TroncalConfiguracion::where('id_troncal', $id_troncal)->where('id_propiedad', 43)->update(['valor' => $request->troncal_direccion_servidor, 'orden' => $orden]);
                $orden++;

                // Valido si viene la propiedad de usuario
                if(isset($request->troncal_usuario_defecto) && $request->troncal_usuario_defecto != ''){
                    TroncalConfiguracion::updateOrInsert(['id_troncal' => $id_troncal, 'id_propiedad' => 39], ['valor' => ($request->troncal_usuario_defecto) ? $request->troncal_usuario_defecto:'', 'orden' => $orden]);
                    $orden++;
                }else{
                    TroncalConfiguracion::where('id_troncal', $id_troncal)->where('id_propiedad', 39)->delete();
                }

                // Valido si existe fuente 
                if(isset($request->troncal_fuente) && $request->troncal_fuente != ''){
                    TroncalConfiguracion::updateOrInsert(['id_troncal' => $id_troncal, 'id_propiedad' => 42], ['valor' => ($request->troncal_fuente) ? $request->troncal_fuente:'', 'orden' => $orden]);
                    $orden++;
                }else{
                    TroncalConfiguracion::where('id_troncal', $id_troncal)->where('id_propiedad', 42)->delete();
                }

                // Valido si existe la contraseña
                if(isset($request->troncal_contrasena) && $request->troncal_contrasena != ''){
                    TroncalConfiguracion::updateOrInsert(['id_troncal' => $id_troncal, 'id_propiedad' => 49], ['valor' => ($request->troncal_contrasena) ? $request->troncal_contrasena:'', 'orden' => $orden]);
                    $orden++;
                }else{
                    TroncalConfiguracion::where('id_troncal', $id_troncal)->where('id_propiedad', 49)->delete();
                }
                
                TroncalConfiguracion::where('id_troncal', $id_troncal)->where('id_propiedad', 48)->update(['valor' => $request->troncal_compensar_rfc2833, 'orden' => $orden]);
                $orden++;
                TroncalConfiguracion::where('id_troncal', $id_troncal)->where('id_propiedad', 35)->update(['valor' => $request->troncal_limite_llamadas, 'orden' => $orden]);
                $orden++;
                TroncalConfiguracion::where('id_troncal', $id_troncal)->where('id_propiedad', 38)->update(['valor' => $request->troncal_contexto, 'orden' => $orden]);
                $orden++;
                TroncalConfiguracion::where('id_troncal', $id_troncal)->where('id_propiedad', 37)->update(['valor' => $request->troncal_habilitar_puente_rtp, 'orden' => $orden]);
                $orden++;
                TroncalConfiguracion::where('id_troncal', $id_troncal)->where('id_propiedad', 52)->update(['valor' => $request->troncal_autenticacion, 'orden' => $orden]);
                $orden++;
                TroncalConfiguracion::where('id_troncal', $id_troncal)->where('id_propiedad', 40)->update(['valor' => $request->troncal_mododtmf, 'orden' => $orden]);
                $orden++;
                TroncalConfiguracion::where('id_troncal', $id_troncal)->where('id_propiedad', 44)->update(['valor' => $request->troncal_nat, 'orden' => $orden]);
                $orden++;
                TroncalConfiguracion::where('id_troncal', $id_troncal)->where('id_propiedad', 47)->update(['valor' => $request->troncal_permitir_verificacion, 'orden' => $orden]);
                $orden++;

                // Valido si las propiedades existen de lo contrario me toca crearla como nueva
                $propiedadTroncal1 = TroncalPropiedades::firstOrCreate(['propiedad' => 'Codec_U']);
                TroncalConfiguracion::where('id_troncal', $id_troncal)->where('id_propiedad', $propiedadTroncal1->id)->update(['valor' => ($request->troncal_codec_u) ? 1 : 0, 'orden' => $orden]);
                $orden++;
                $propiedadTroncal2 = TroncalPropiedades::firstOrCreate(['propiedad' => 'Codec_A']);
                TroncalConfiguracion::where('id_troncal', $id_troncal)->where('id_propiedad', $propiedadTroncal2->id)->update(['valor' => ($request->troncal_codec_a) ? 1 : 0, 'orden' => $orden]);
                $orden++;
                $propiedadTroncal3 = TroncalPropiedades::firstOrCreate(['propiedad' => 'Codec_G729']);
                TroncalConfiguracion::where('id_troncal', $id_troncal)->where('id_propiedad', $propiedadTroncal3->id)->update(['valor' => ($request->troncal_g729) ? 1 : 0, 'orden' => $orden]);
                $orden++;

                // Validamos si tenemos propiedades adicionales
                if(isset($request->troncal_prop_tipo)){

                    $nombreProp = $request->troncal_prop_tipo;
                    $valorProp = $request->troncal_prop_valor;
                    $idProp = $request->troncal_prop_id;

                    for ($i=0; $i < count($nombreProp); $i++) { 
                        
                        // Valido si el registro es nuevo o se edita
                        // Nueva
                        if($idProp[$i] == 0){
                            // Si se agrega valido si es una propiedad nueva o existente
                            if(is_numeric($nombreProp[$i])){
                                // Al ser numerica es una propiedad existente
                                TroncalConfiguracion::insert(['id_troncal'=> $troncal->id, 'id_propiedad'=> $nombreProp[$i], 'valor'=> $valorProp[$i], 'orden'=> $orden]);
                                $orden++;
                            }else{
                                // Si es una propiedad como tal nueva valido que exista
                                $propiedad = TroncalPropiedades::firstOrCreate(['propiedad' => $nombreProp[$i]], ['tipo' => '', 'valor_por_defecto' => '', 'archivo' => '', 'nombre' => $nombreProp[$i], 'descripcion' => '']);
                                TroncalConfiguracion::insert(['id_troncal'=> $troncal->id, 'id_propiedad'=> $propiedad->id, 'valor'=> $valorProp[$i], 'orden'=> $orden]);
                                $orden++;
                            }
                        }else{
                            // Existente
                            TroncalConfiguracion::where('id', $idProp[$i])->update(['id_propiedad' => $nombreProp[$i], 'valor' => $valorProp[$i], 'orden' => $orden]);
                            $orden++;
                        }
                    }

                }

                $con1->commit();
                $con2->commit();

                $generarRegistro = ($request->generarRegistro) ? true : false;

                // Llamade del web service de guardado y serializado
                $WSApi = new \WSRest();
                $dataApi = $WSApi->troncalPersistir($troncal->id, $generarRegistro);
                $json = json_decode($dataApi);

                $data = [
                    'code' => 200,
                    'status' => 'success',
                    'message' => 'Se ha actualizado el registro exitosamente',
                    'troncal' => $troncal,
                    'json' => $json
                ];

            } catch (\Throwable $th) {
                //throw $th;
                $con1->rollBack();
                $con2->rollBack();

                $data = [
                    'code' => 400,
                    'status' => 'error',
                    'message' => 'Se ha presentado un error al guardar los datos',
                ];
            }

        }
        return response()->json($data, $data['code']);
    }

    /**
     * Elimina la troncal especifica
     * @param int $id_troncal
     * @return \Illuminate\Http\Response
     */
    public function deleteTroncal($id_troncal){

        // Primero borro los pasos asociados a este patron
        $deleteRows = PasosTroncales::where('id_troncal', $id_troncal)->delete();

        // Llamade del web service de guardado y serializado
        $WSApi = new \WSRest();
        $dataApi = $WSApi->troncalBorrar($id_troncal);
        $json = json_decode($dataApi);

        TroncalConfiguracion::where('id_troncal', $id_troncal)->delete();

        Troncal::find($id_troncal)->delete();

        $data = [
            'code' => 200,
            'status' => 'success',
            'message' => 'Su registro ha sido eliminado',
            'json' => $json
        ];

        return response()->json($data, $data['code']);
    }

    /**
     * Esta funcion se encarga de obtener los estados de las troncales de un huesped en especifico
     * @param int $id_huesped
     * @return \Illuminate\Http\Response
     */
    public function getEstadoTroncales($id_huesped){
        // Traigo el id_proyecto
        $proyecto = Dy_Proyectos::where('id_huesped', $id_huesped)->first();

        $troncales = Troncal::where('id_proyecto', $proyecto->id)->get();

        $troncalEstado = [];

        foreach ($troncales as $troncal) {

            $WSApi = new \WSRest();
            $dataApi = $WSApi->troncalEstado($troncal->id);
            $json = json_decode($dataApi);

            if(!$json){
                $troncalEstado[$troncal->id] = 'fallo';
            }else{
                $troncalEstado[$troncal->id] = $json->objSerializar_t;
            }
        }

        $data = [
            'code' => 200,
            'status' => 'success',
            'troncalEstado' => $troncalEstado
        ];

        return response()->json($data, $data['code']);
    }

    public function deletePropiedadTroncal(Request $request){

        TroncalConfiguracion::where('id', $request->propiedadId)->delete();

        $data = [
            'code' => 200,
            'status' => 'success',
            'message' => 'La propiedad ha sido borrada'
        ];

        return response()->json($data, $data['code']);

    }

    public function getPropiedades(){
        $listaPropiedades = TroncalPropiedades::select('id','propiedad')->get();

        $data = [
            'code' => 200,
            'status' => 'success',
            'propiedades' => $listaPropiedades
        ];

        return response()->json($data, $data['code']);
    }
}
