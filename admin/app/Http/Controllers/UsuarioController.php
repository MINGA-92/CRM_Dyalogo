<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use DB;

use App\Models\Huesped;
use App\User;
use App\Models\HuespedUsuario;

class UsuarioController extends Controller
{
    public function __construct(){
        $this->middleware('auth');
    }

    /**
     * Obtine todos los usuarios que estan relacionados con un huesped
     * @param  int $id_huesped
     * @return \Illuminate\Http\Response
     */
    public function getUsuarios($id_huesped){

        $usuarios = Huesped::join('dyalogo_general.huespedes_usuarios', 'huespedes_usuarios.id_huesped', '=', 'huespedes.id')
                            ->join('DYALOGOCRM_SISTEMA.USUARI', 'huespedes_usuarios.id_usuario', '=', 'USUARI.USUARI_UsuaCBX___b')
                            ->select('huespedes_usuarios.*', 'USUARI.USUARI_Nombre____b', 'USUARI.USUARI_Correo___b')
                            ->where('huespedes.id', $id_huesped)
                            ->where('USUARI_ConsInte__PROYEC_b', '<>', $id_huesped)
                            ->where(function($query){
                                $query->where('USUARI_Correo___b', 'like', '%@dyalogo.com')
                                    ->orWhere('USUARI_Correo___b', 'like', '%@vozsobreip.com.co');
                            })
                            ->orderBy('USUARI_Nombre____b', 'asc')
                            ->get();

        $data = [
            'code' => 200,
            'status' => 'success',
            'usuarios' => $usuarios
        ];

        return response()->json($data, $data['code']);
    }

    /**
     * Obtine una lista de los usuarios que tienen el correo @dyalogo.com
     * @return \Illuminate\Http\Response
     */
    public function getUsuariosDyalogo($id_huesped){
        // $usuarios = User::where('USUARI_Correo___b', 'like', '%@dyalogo.com')
        //             ->orWhere('USUARI_Correo___b', 'like', '%@vozsobreip.com.co')
        //             ->select('USUARI.USUARI_Nombre____b', 'USUARI.USUARI_Correo___b', 'USUARI_UsuaCBX___b')
        //             ->orderBy('USUARI_Correo___b', 'asc')
        //             ->get();

        $usuarios = DB::select(
            DB::raw("SELECT A.USUARI_Nombre____b, A.USUARI_Correo___b, A.USUARI_UsuaCBX___b FROM DYALOGOCRM_SISTEMA.USUARI AS A LEFT JOIN (SELECT * FROM dyalogo_general.huespedes_usuarios WHERE id_huesped = $id_huesped) AS B ON A.USUARI_UsuaCBX___b = B.id_usuario WHERE (USUARI_Correo___b like '%@dyalogo.com' || USUARI_Correo___b like '%@vozsobreip.com.co') AND B.id IS NULL ORDER BY USUARI_Correo___b ASC")
        );

        $data = [
            'code' => 200,
            'status' => 'success',
            'usuarios' => $usuarios
        ];

        return response()->json($data, $data['code']);
    }

    /**
     * Esta funcion se encarga de asignar el usuario a un huesped en especifico
     * @param \Illuminate\Http\Request  $request
     * @param int $id_huesped
     * @return \Illuminate\Http\Response
     */
    public function asignarUsuario(Request $request, $id_huesped){
        // Validar los datos
        $validate = \Validator::make($request->all(), [
            'usuarioAsignar' => 'required'
        ]);

        if($validate->fails()){
            $data = [
                'code' => 422,
                'status' => 'error',
                'message' => 'Los datos no son validos',
                'errors' => $validate->errors()
            ];
        }else{
            $verificarActivo = DB::connection('general')->table('huespedes_usuarios')
                            ->where('id_huesped', $id_huesped)
                            ->where('id_usuario', $request->usuarioAsignar)
                            ->get();

            if(!$verificarActivo->isEmpty()){
                $data = [
                    'code' => 200,
                    'status' => 'exist',
                    'message' => 'Este usuario ya se encuentra asignada en este huesped',
                ];
            }else{
                $insertar = DB::connection('general')->table('huespedes_usuarios')->insert(
                    ['id_huesped' => $id_huesped, 'id_usuario' => $request->usuarioAsignar]
                );

                $data = [
                    'code' => 200,
                    'status' => 'success',
                    'message' => 'El usuario se ha asigando al huesped satisfactoriamente',
                ];
            }
        }

        return response()->json($data, $data['code']);
    }

    /**
     * Esta funcion se encarga de crear un usuario y asignarlo al huesped especifico
     * @param int $id_huesped
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function storeUsuario($id_huesped, Request $request){

        $validate = \Validator::make($request->all(), [
            'usu_nombre'            => 'required|max:255',
            'usu_correo'            => 'required|max:255|email',
            'usu_identificacion'    => 'required|max:50',
        ]);

        if($validate->fails()){
            $data = [
                'code' => 422,
                'status' => 'error',
                'message' => 'Los datos nos son validos',
                'errors' => $validate->errors()
            ];
        }else{

            $strPassRandom = $this->crearPassword();
    
            $usuari = new User();
            $usuari->USUARI_Correo___b = $request->usu_correo;
            $usuari->USUARI_Nombre____b = $request->usu_nombre;
            $usuari->USUARI_Identific_b = $request->usu_identificacion;
            $usuari->USUARI_Cargo_____b = "administrador";
            $usuari->USUARI_Clave_____b = $this->encriptaPassword($strPassRandom);
            $usuari->USUARI_ConsInte__PROYEC_b = $id_huesped;
            $usuari->save();

            // Instancia de la api
            $WSApi = new \WSRest();
            // Consumo de WSApi
            $dataApi = $WSApi->usuarioPersistir($usuari->USUARI_Nombre____b, $usuari->USUARI_Correo___b, $usuari->USUARI_Identific_b, $strPassRandom, $id_huesped, $usuari->USUARI_ConsInte__b);
            
            if(!empty($dataApi)  && !is_null($dataApi)){
                
                $json = json_decode($dataApi);
                
                // Validar si se obtubo la informacion correcta de la api
                if($json->strEstado_t == 'ok'){
                    // Asignacion de USUARI_UsuaCBX al Usuario creado
                    $usuari->USUARI_UsuaCBX___b = $json->intIdCreacion_t;
                    $usuari->save();
                    // Asignacino de usuario_huesped recibido de la api
                    $huespedUsuari = new HuespedUsuario();
                    $huespedUsuari->id_huesped = $id_huesped;
                    $huespedUsuari->id_usuario = $usuari->USUARI_UsuaCBX___b;
                    $huespedUsuari->save();
    
                    $dataPassword = $WSApi->sendMailPassword($usuari->USUARI_Correo___b, $usuari->USUARI_Nombre____b, $strPassRandom);
                    $jsonPass = json_decode($dataPassword);

                    $data = [
                        'code' => 200,
                        'status' => 'success',
                        'message' => 'Usuario creado con éxito',
                        'usuario' => $usuari,
                        'api' => $json,
                        'apiPass' => $jsonPass
                    ];                     
    
                }else{
    
                    $data = [
                        'code' => 400,
                        'status' => 'error',
                        'message' => 'Se ha presentado un error al consumir la api',
                        'api' => $json,                            
                    ]; 
                    
                }
            } 
        }       
        return response()->json($data, $data['code']);
    }

    /**
     * Borra la relacion que hay entre un usuario y el huesped, para que se elimine el usuario debe tener la 
     * extencion de correo @dyalogo.com
     * @param int $id_huesped
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function desvincularUsuario($id_usuario, Request $request){

        $extension = '@dyalogo.com';
        $extension2 = '@vozsobreip.com.co';

        $usuario = User::where('USUARI_UsuaCBX___b', $id_usuario)->first();

        // $pos = strpos(strtolower($usuario->USUARI_Correo___b), $extension);
        // $pos2 = strpos(strtolower($usuario->USUARI_Correo___b), $extension2);

        // if($pos || $pos2){
            // Dejo que se desvinculen todos los usuarios desde admin
            $huespedUsuario = HuespedUsuario::where('id_huesped', $request->id_huesped)
                ->where('id_usuario', $request->id_usuario)
                ->delete();
            
            $data = [
                'code' => 200,
                'status' => 'success',
                'message' => 'El usuario se ha desvinculado de este huésped'              
            ]; 
        // }else{
        //     $data = [
        //         'code' => 200,
        //         'status' => 'info',
        //         'message' => 'No se puede desvincular usuarios que tengan una extensión de correo diferente a '.$extension              
        //     ];
        // }
        return response()->json($data, $data['code']);
    }
}
