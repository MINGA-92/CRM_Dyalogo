<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use DB;
use App\Models\TiposDestino;
use App\Models\PasosTroncales;

class TiposDestinoController extends Controller
{

    public function index($id_huesped){

        $tiposDestino = TiposDestino::where('id_huesped', $id_huesped)->get();

        $data = [
            'code' => 200,
            'status' => 'success',
            'tiposDestino' => $tiposDestino
        ];

        return response()->json($data, $data['code']);
    }

    public function store($id_huesped, Request $request){
        // Validar los datos
        $validate = \Validator::make($request->all(), [
            'nombre' => 'required',
            'patron' => 'required'
        ]);

        if($validate->fails()){
            $data = [
                'code' => 422,
                'status' => 'error',
                'message' => 'Los datos no son validos',
                'errors' => $validate->errors()
            ];
        }else{
            $patron = new TiposDestino();
            $patron->codigo_antepuesto = $request->codigo;
            $patron->id_huesped = $id_huesped;
            $patron->patron_ejemplo = $request->ejemplo;

            if($request->agregarCodigoPais == 1){
                $nuevoPatron = $request->codigo . $request->patron;
                $nuevoNombre = $request->nombre.' con codigo de pais';
            }else{
                $nuevoPatron = $request->patron;
                $nuevoNombre = $request->nombre;
            }

            $patron->nombre = $nuevoNombre;
            $patron->patron = $nuevoPatron;

            $patron->patron_validacion = $this->expresionRegularPatron($nuevoPatron);

            $patron->save();

            $data = [
                'code' => 200,
                'status' => 'success',
                'message' => 'Patron creado',
                'patron' => $patron
            ];
        }

        return response()->json($data, $data['code']);
    }

    public function update(Request $request, $id_patron){
        // Validar los datos
        $validate = \Validator::make($request->all(), [
            'nombre' => 'required',
            'patron' => 'required'
        ]);

        if($validate->fails()){
            $data = [
                'code' => 422,
                'status' => 'error',
                'message' => 'Los datos no son validos',
                'errors' => $validate->errors()
            ];
        }else{
            $patron = TiposDestino::find($id_patron);
            // $patron->nombre = $request->nombre;
            // $patron->patron = $request->patron;
            $patron->codigo_antepuesto = $request->codigo;
            // $patron->patron_validacion = $this->expresionRegularPatron($request->patron);

            $patron->save();

            $data = [
                'code' => 200,
                'status' => 'success',
                'message' => 'Patron actualizado',
                'patron' => $patron
            ];
        }

        return response()->json($data, $data['code']);
    }

    public function delete($id_patron){

        // Primero borro los pasos asociados a este patron
        $deleteRows = PasosTroncales::where('id_tipos_destino', $id_patron)->delete();

        $deletePatron = TiposDestino::find($id_patron)->delete();

        $data = [
            'code' => 200,
            'status' => 'success',
            'message' => 'Su registro ha sido eliminado'
        ];

        return response()->json($data, $data['code']);
    }

    public function patronByPais($codigo){

        $patrones = DB::connection('telefonia')
            ->table('patrones_por_pais')
            ->where('id_pais', $codigo)
            ->get();

            return response()->json([
                'status' => 'success',
                'message' => 'Obteniendo patrones',
                'patrones' => $patrones
            ], 200);
    }

    public function expresionRegularPatron($strPatron_p){
        $strExpresion_t = "'^";

        for ($i=0; $i < strlen($strPatron_p); $i++) {

            if (is_numeric($strPatron_p[$i])) {

                $strExpresion_t .= "[".$strPatron_p[$i]."]";

            }elseif ($strPatron_p[$i] == "X") {

                $strExpresion_t .= "[0-9]{1,1}";

            }elseif($strPatron_p[$i] == "N"){

                $strExpresion_t .= "[2-9]{1,1}";

            }elseif($strPatron_p[$i] == "Z"){

                $strExpresion_t .= "[1-9]{1,1}";

            }elseif($strPatron_p[$i] == "."){

                $strExpresion_t .= "[0-9]{1,}";

            }

        }

        $strExpresion_t .= "$'";

        return	$strExpresion_t;
    }
}
