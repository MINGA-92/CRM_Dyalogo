<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\ListaFestivo;
use App\Models\Festivo;
use App\Models\Huesped;

class FestivosController extends Controller
{

    /**
     * Retorna una lista de todos los festivos de un huesped en espepecifico.
     * @param int $id_huesped
     * @return \Illuminate\Http\Response
     */
    public function listarFestivos($id_huesped){
        $listaFestivos = ListaFestivo::where('id_proyecto', $id_huesped)->with('festivos')->first();
        
        $data = [
            'code' => 200,
            'status' => 'success',
            'listaFestivos' => $listaFestivos
        ];

        return response()->json($data, $data['code']);
    }

     /**
     * Guarda o actualiza la lista de festivos de un huesped.
     * @param int $id_huesped
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function storeFestivos(Request $request, $id_huesped){

        // Validar los datos
        $validate = \Validator::make($request->all(), [
            'fecha_festivo.*' => 'required|date|distinct',
            'nombre_festivo.*' => 'required|max:255',

        ]);

        if($validate->fails()){
            $data = [
                'code' => 422,
                'status' => 'error',
                'message' => 'Los datos no son validos',
                'errors' => $validate->errors()
            ];
        }else{
            $huesped = Huesped::find($id_huesped);
            
            $listaFestivo = ListaFestivo::firstOrCreate(
                ['id_proyecto' => $id_huesped],
                ['nombre' => 'Festivos '.$huesped->nombre]
            );

            $festivosToKeep = [];

            if($request->nombre_festivo){
                for ($i=0; $i < count($request->nombre_festivo); $i++) { 
                    $festivo = Festivo::updateOrCreate([
                        'id' => $request->festivo_id[$i],
                        'id_lista' => $listaFestivo->id
                    ],[
                        'fecha' => $request->fecha_festivo[$i],
                        'nombre' => $request->nombre_festivo[$i]
                    ]);

                    $festivosToKeep[] = $festivo->id;
                }
            }

            // Eliminacion de los festivos que no se crearon o actualizaron
            $getFestivos = Festivo::where('id_lista', $listaFestivo->id)->get();

            $getFestivos->each(function ($item, $key) use ($festivosToKeep) {
                if (! in_array($item->id, $festivosToKeep)) {
                    $item->delete();
                }
            });
            
            $festivos = Festivo::where('id_lista', $listaFestivo->id)->get();
            // $festivo = new Festivo();
            
            // $festivo->id_lista = $listaFestivo->id;
            // $festivo->fecha = $request->fecha_festivo;
            // $festivo->nombre = $request->nombre_festivo;
            // $festivo->save();
            
            $data = [
                'code' => 200,
                'status' => 'success',
                'message' => 'Se han guardado los datos correctamente',
                'festivos' => $festivos
            ];
        }

        return response()->json($data, $data['code']);
    }

    public function generarFestivos($id_huesped){
        $anoActual = date("Y");
        $anoSiguente = date("Y") + 1;

        $ano1 = json_decode( file_get_contents('https://kayaposoft.com/enrico/json/v2.0?action=getHolidaysForYear&year='.$anoActual.'&country=col&holidayType=all'));
    
        $huesped = Huesped::find($id_huesped);

        $listaFestivo = ListaFestivo::firstOrCreate(
            ['id_proyecto' => $huesped->id],
            ['nombre' => 'Festivos '.$huesped->nombre]
        );

        foreach($ano1 as $festivo){
            $nombreFestivo = '';
            $fechaFestivo = '';
            
            $nombreFestivo = $festivo->name[0]->text;
            $fechaFestivo = $festivo->date->year.'-'.$festivo->date->month.'-'.$festivo->date->day;

            $festivo = new Festivo();
            $festivo->id_lista = $listaFestivo->id;
            $festivo->fecha = $fechaFestivo;
            $festivo->nombre = $nombreFestivo;
            $festivo->save();
        }

        $ano2 = json_decode( file_get_contents('https://kayaposoft.com/enrico/json/v2.0?action=getHolidaysForYear&year='.$anoSiguente.'&country=col&holidayType=all'));

        foreach($ano2 as $festivo){
            $nombreFestivo = '';
            $fechaFestivo = '';
            
            $nombreFestivo = $festivo->name[0]->text;
            $fechaFestivo = $festivo->date->year.'-'.$festivo->date->month.'-'.$festivo->date->day;

            $festivo = new Festivo();
            $festivo->id_lista = $listaFestivo->id;
            $festivo->fecha = $fechaFestivo;
            $festivo->nombre = $nombreFestivo;
            $festivo->save();
        }

        $data = [
            'code' => 200,
            'status' => 'success',
            'message' => 'Se ha generado los festivos',
        ];

        return response()->json($data, $data['code']);
    }
}
