<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Auth;
use DB;

class PaisCiudadController extends Controller
{
    /**
     * Obtiene lista de todos los paises
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function getPais(Request $request){
        if(!$request->ajax()){
            $paises = DB::connection('telefonia')
                ->table('dy_pais_ciudad')
                ->select('pais')
                ->groupBy('pais')
                ->get();

            return response()->json($paises, 200);
        }
    }

    /**
     * Devuelve lista de la ciudades de un pais
     * @param  \Illuminate\Http\Request  $request
     * @param int $pais
     * @return \Illuminate\Http\Response
     */
    public function getCiudadPais(Request $request, $pais){
        if($request->ajax()){
            $cuidades = DB::connection('telefonia')
                ->table('dy_pais_ciudad')
                ->where('pais', $pais)
                ->orderBy('ciudad', 'ASC')
                ->get();

            return response()->json($cuidades, 200);
        }
    }

    /**
     * Obtiene el objeto de la ciudad
     * @param  \Illuminate\Http\Request  $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function getCiudad(Request $request, $id){
        $ciudad = DB::connection('telefonia')
            ->table('dy_pais_ciudad')
            ->where('id', $id)
            ->first();

        return response()->json($ciudad, 200);
    }
}
