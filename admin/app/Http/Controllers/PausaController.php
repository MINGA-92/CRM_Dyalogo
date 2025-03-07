<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;

use App\Models\DyTipoDescanso;
use App\Models\Dy_Proyectos;

class PausaController extends Controller
{
    public function __construct(){
        $this->middleware('auth');
    }

    /**
     * Retorna una lista de todas las pausas de un huesped en espepecifico.
     * @param int $id_huesped
     * @return \Illuminate\Http\Response
     */
    public function listarPausas($id_huesped){
        $proyecto = Dy_Proyectos::select('id','nombre', 'id_huesped')->where('id_huesped', $id_huesped)->first();
        $listaPausas = DyTipoDescanso::where('id_proyecto', $proyecto->id)->get();
        
        $data = [
            'code' => 200,
            'status' => 'success',
            'listaPausas' => $listaPausas
        ];

        return response()->json($data, $data['code']);
    }

     /**
     * Actualiza una lista de pausas de un huesped en especifico.
     * @param int $id_huesped
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function storePausa(Request $request, $id_huesped){

        // Validar los datos
        $validate = \Validator::make($request->all(), [
            'nombre_pausa.*' => 'required',
            'clasificacion.*' => 'required',
            'tipo.*' => 'required',
            'hora_inicial.*' => 'required_if:tipo.*,1|date_format:H:i:s|nullable',
            'hora_final.*' => 'required_if:tipo.*,1|date_format:H:i:s|nullable',
            'duracion_maxima.*' => 'required_if:tipo.*,0|numeric|nullable',
            'cantidad_maxima.*' => 'required_if:tipo.*,0|numeric|nullable'
        ]);

        if($validate->fails()){
            $data = [
                'code' => 422,
                'status' => 'error',
                'message' => 'Los datos no son validos',
                'errors' => $validate->errors()
            ];
        }else{
            $proyecto = Dy_Proyectos::select('id','nombre', 'id_huesped')->where('id_huesped', $id_huesped)->first();
            $PausasToKeep = [];

            if($request->nombre_pausa){
                for ($i=0; $i < count($request->nombre_pausa); $i++) { 

                    // Trae el ultimo codigo descanso
                    $last_codigo_descanso = DB::connection('telefonia')
                    ->table('dy_tipos_descanso')
                    ->max(DB::raw('CAST(codigo AS UNSIGNED)'));

                    $pausa = DyTipoDescanso::where([
                        'id' => $request->id_pausa[$i],
                        'id_proyecto' => $proyecto->id
                    ])->first();

                    if($pausa){
                        $pausa->update([
                            'tipo' => $request->nombre_pausa[$i],
                            'descanso' => $request->clasificacion[$i],
                            'tipo_pausa' => $request->tipo[$i],
                            'hora_inicial_por_defecto' => $request->hora_inicial[$i],
                            'hora_final_por_defecto' => $request->hora_final[$i],
                            'duracion_maxima' => $request->duracion_maxima[$i],
                            'cantidad_maxima_evento_dia' => $request->cantidad_maxima[$i],
                            
                        ]);
                    }else{
                        $pausa = DyTipoDescanso::firstOrCreate([
                            'id' => $request->id_pausa[$i],
                            'id_proyecto' => $proyecto->id
                        ],[
                            'tipo' => $request->nombre_pausa[$i],
                            'descanso' => $request->clasificacion[$i],
                            'tipo_pausa' => $request->tipo[$i],
                            'hora_inicial_por_defecto' => $request->hora_inicial[$i],
                            'hora_final_por_defecto' => $request->hora_final[$i],
                            'duracion_maxima' => $request->duracion_maxima[$i],
                            'cantidad_maxima_evento_dia' => $request->cantidad_maxima[$i],
                            'codigo'=> $last_codigo_descanso + 1
                        ]);
                    }

                    

                    $PausasToKeep[] = $pausa->id;
                }
            }

            // Eliminacion de las pausas que no se crearon o actualizaron
            $getPausas = DyTipoDescanso::where('id_proyecto', $proyecto->id)->get();

            $getPausas->each(function ($item, $key) use ($PausasToKeep) {
                if (! in_array($item->id, $PausasToKeep)) {
                    $item->delete();
                }
            });
            
            $pausas = DyTipoDescanso::where('id_proyecto', $proyecto->id)->get();
            
            $data = [
                'code' => 200,
                'status' => 'success',
                'message' => 'Se han guardado los datos correctamente',
                'pausas' => $pausas
            ];
        }

        return response()->json($data, $data['code']);
    }
}
