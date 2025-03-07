<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use DB;
use Carbon\Carbon;
use App\Http\Requests\HuespedCreateRequest;
use App\Http\Requests\HuespedUpdateRequest;

use App\Models\Huesped;
use App\Models\HuespedContacto;
use App\Models\Dy_Proyectos;
use App\Models\DyTipoDescanso;
use App\Models\Proyect;
use App\User;
use App\Models\HuespedUsuario;
use App\Models\MailNotificacion;
use App\Models\ListaFestivo;
use App\Models\Festivo;
use App\Models\Troncal;

class HuespedController extends Controller
{
    
    /**
     * Busca un huesped en especifico
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function search(Request $request){
        // Verificar si la consulta es ajax
        if(!$request->ajax()){
            return redirect('/huesped');
        }

        $name = $request->texto;
        $huespedes = Huesped::orderBy('nombre', 'ASC')->name($name)->get();

        $data = [
            'code' => 200,
            'status' => 'success',
            'huespedes' => $huespedes
        ];

        return response()->json($data, $data['code']);

    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(){

        $huespedes = Huesped::orderBy('nombre', 'ASC')->get();

        $paises = DB::connection('telefonia')
            ->table('dy_pais_ciudad')
            ->select('pais')
            ->groupBy('pais')
            ->get();

        $data = [
            'huespedes' => $huespedes,
            'paises' => $paises,
        ];

        return view('huesped.index', $data);
    }

    /**
     * Show the data for the given huesped.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id){
        $huesped = Huesped::find($id)->load('contactos', 'proyecto', 'mailNotificacion', 'pausas');

        // Necesito buscar por id_proyecto especifico, por eso hago la busqueda por huesped
        $proyecto = Dy_Proyectos::where('id_huesped', $id)->first();
        $troncales = Troncal::where('id_proyecto', $proyecto->id)->get();

        if($huesped){
            $data = [
                'code' => 200,
                'status' => 'success',
                'huesped' => $huesped,
                'troncales' => $troncales
            ];
        }else{
            $data = [
                'code' => 404,
                'status' => 'error',
                'message' => 'No se pudo obtener los datos del huésped'
            ];
        }
        return response()->json($data, $data['code']);
    }

    /**
     * Store a new huesped.
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(HuespedCreateRequest $request){
        // Verificar si la consulta es ajax
        if(!$request->ajax()){
            return redirect('/huesped');
        }

        $con1 = DB::connection('general');
        $con2 = DB::connection('telefonia');
        $con3 = DB::connection('crm_sistema');

        $con1->beginTransaction();
        $con2->beginTransaction();
        $con3->beginTransaction();

        try {
            // Registro de dyalogo_general.huespedes
            $huesped = new Huesped();
            $huesped->nombre = str_replace('-', '_', $request->nombre);
            $huesped->notificar_pausas = ($request->notificar_pausas) ? 1 : 0;
            $huesped->notificar_sesiones = ($request->notificar_sesiones) ? 1 : 0;
            $huesped->notificar_incumplimientos = ($request->notificar_incumplimientos) ? 1 : 0;
            $huesped->emails_notificar_pausas = $request->emails_notificar_pausas;
            $huesped->emails_notificar_sesiones = $request->emails_notificar_sesiones;
            $huesped->emails_notificar_incumplimientos = $request->emails_notificar_incumplimientos;
            $huesped->razon_social = $request->razon_social;
            $huesped->nit = $request->nit;
            $huesped->id_pais_ciudad = $request->ciudad;
            $huesped->direccion = $request->direccion;
            $huesped->telefono1 = $request->telefono1;
            $huesped->telefono2 = $request->telefono2;
            $huesped->telefono3 = $request->telefono3;
            $huesped->malla_turno_requerida = ($request->mallaTurnoRequerida) ? 1 : 0;
            $huesped->malla_turno_horario_por_defecto = ($request->mallaTurnoHorarioDefecto) ? 1 : 0;
            $huesped->hora_entrada_por_defecto = $request->horaEntrada;
            $huesped->hora_salida_por_defecto = $request->horaSalida;
            $huesped->cantidad_max_supervisores = $request->cantidadMaximaSupervisores;
            $huesped->cantidad_max_bo = $request->cantidadMaximaBackoffice;
            $huesped->cantidad_max_calidad = $request->cantidadMaximaCalidad;
            $huesped->mensaje = $request->mensajeActual;
            $huesped->foto_usuario_obligatoria = ($request->fotoUsuarioObligatoria) ? 1 : 0;
            $huesped->doble_factor_autenticacion = ($request->dobleFactorAuth) ? 1 : 0;
            $huesped->pass_longitud_requerida = ($request->passLongitudRequerida) ? 1 : 0;
            $huesped->pass_mayuscula_requerida = ($request->passMayusculaRequerida) ? 1 : 0;
            $huesped->pass_numero_requerido = ($request->passNumeroRequerido) ? 1 : 0;
            $huesped->pass_simbolo_requerido = ($request->passSimboloRequerido) ? 1 : 0;
            $huesped->pass_cambio_login_requerido = ($request->passCambioObligatorioRequerido) ? 1 : 0;
            $huesped->pass_historico_requerido = ($request->passHistoricoRequerido) ? 1 : 0;
            $huesped->pass_cambio_periodico_requerido = ($request->passCambioPeriodicoRequerido) ? 1 : 0;
            $huesped->pass_dias_cambio_periodico = ($request->passDiasCambioPeriodico) ? $request->passDiasCambioPeriodico : 90;
            $huesped->tipo_gestion_pausa = $request->tipoPausa;

            $huesped->save();

            // Registro de dyalogo_general.huespedes_contactos
            for ($i=0; $i < count($request->contacto_nombre) ; $i++) {
                $contacto = new HuespedContacto();
                $contacto->id_huesped = $huesped->id;
                $contacto->nombre = $request->contacto_nombre[$i];
                $contacto->email = $request->contacto_email[$i];
                $contacto->tipo = $request->contacto_tipo[$i];
                $contacto->telefono1 = $request->contacto_telefono1[$i];
                $contacto->telefono2 = $request->contacto_telefono2[$i];
                $contacto->save();
            }

            // Replicacion en otras tablas - dyalogo_telefonia.dy_proyectos
            $dyProyecto = new Dy_Proyectos();
            $dyProyecto->id = $huesped->id;
            $dyProyecto->nombre = $huesped->nombre;
            $dyProyecto->fecha_inicial = Carbon::now();
            $dyProyecto->fecha_final = Carbon::now()->addYears(100);
            $dyProyecto->cantidad_max_agentes_simultaneos = $request->cantidadMaxAgentesSimultaneos;
            $dyProyecto->id_huesped = $huesped->id;
            $dyProyecto->save();

            // Replicacion en otras tablas - DYALOGOCRM_SISTEMA.PROYECT
            $proyect = new Proyect();
            $proyect->PROYEC_ConsInte__b = $huesped->id;
            $proyect->PROYEC_NomProyec_b = $huesped->nombre;
            $proyect->PROYEC_FechCrea__b = Carbon::now();
            $proyect->PROYEC_UsuaCrea__b = Auth::user()->USUARI_ConsInte__b;
            $proyect->save();

            $mailNotificacion = MailNotificacion::updateOrCreate(
                ['id_huesped'=> $huesped->id],
                [
                    'servidor_smtp'=> 'smtp.gmail.com',
                    'dominio' =>  'gmail.com',
                    'puerto' => '587',
                    'usuario' => $request->notificacion_usuario,
                    'password' => $request->notificacion_password,
                    'ttls' => 1,
                    'auth' => 1,
                ]
            );

            // Asignacion de usuario_huesped predeterminado
            $huespedUsuari1 = new HuespedUsuario();
            $huespedUsuari1->id_huesped = $huesped->id;
            $huespedUsuari1->id_usuario = 41;
            $huespedUsuari1->save();
            // $hU1 = User::where('USUARI_Correo___b', 'jefe.mesadeayuda@dyalogo.com')->select('USUARI_Correo___b', 'USUARI_UsuaCBX___b')->get();
            // $hU2 = User::where('USUARI_Correo___b', 'mesadeayuda2@dyalogo.com')->select('USUARI_Correo___b', 'USUARI_UsuaCBX___b')->get();
            // $hU3 = User::where('USUARI_Correo___b', 'mesadeayuda3@dyalogo.com')->select('USUARI_Correo___b', 'USUARI_UsuaCBX___b')->get();
            // $hU4 = User::where('USUARI_Correo___b', 'mesadeayuda4@dyalogo.com')->select('USUARI_Correo___b', 'USUARI_UsuaCBX___b')->get();
            // $hU5 = User::where('USUARI_Correo___b', 'dev1@dyalogo.com')->select('USUARI_Correo___b', 'USUARI_UsuaCBX___b')->get();
            // $hU6 = User::where('USUARI_Correo___b', 'atencionalcliente@dyalogo.com')->select('USUARI_Correo___b', 'USUARI_UsuaCBX___b')->get();
            // HuespedUsuario::insert([
            //     ['id_huesped' => $huesped->id, 'id_usuario' => $hU1->USUARI_UsuaCBX___b],
            //     ['id_huesped' => $huesped->id, 'id_usuario' => $hU2->USUARI_UsuaCBX___b],
            //     ['id_huesped' => $huesped->id, 'id_usuario' => $hU3->USUARI_UsuaCBX___b],
            //     ['id_huesped' => $huesped->id, 'id_usuario' => $hU4->USUARI_UsuaCBX___b],
            //     ['id_huesped' => $huesped->id, 'id_usuario' => $hU5->USUARI_UsuaCBX___b],
            //     ['id_huesped' => $huesped->id, 'id_usuario' => $hU6->USUARI_UsuaCBX___b],
            // ]);

            // Obtener el cogido con el valor maximo de la tabla dy_tipos_descanso
            $last_codigo_descanso = DB::connection('telefonia')
                ->table('dy_tipos_descanso')
                ->max(DB::raw('CAST(codigo AS UNSIGNED)'));

            // Creacion de las pausas por defecto predeterminadas
            // Con horario
            $arrPausa1 = ['tipo' => $huesped->nombre.' - Break mañana', 'codigo'=> $last_codigo_descanso + 1, 'descanso'=> true,  'id_proyecto'=> $huesped->id, 'duracion_maxima' => null, 'cantidad_maxima_evento_dia'=> null, 'hora_inicial_por_defecto'=> '10:00:00', 'hora_final_por_defecto'=> '10:15:00', 'tipo_pausa'=> true];
            $arrPausa2 = ['tipo' => $huesped->nombre.' - Almuerzo',     'codigo'=> $last_codigo_descanso + 2, 'descanso'=> true,  'id_proyecto'=> $huesped->id, 'duracion_maxima' => null, 'cantidad_maxima_evento_dia'=> null, 'hora_inicial_por_defecto'=> '12:30:00', 'hora_final_por_defecto'=> '13:30:00', 'tipo_pausa'=> true];
            $arrPausa3 = ['tipo' => $huesped->nombre.' - Break tarde',  'codigo'=> $last_codigo_descanso + 3, 'descanso'=> true,  'id_proyecto'=> $huesped->id, 'duracion_maxima' => null, 'cantidad_maxima_evento_dia'=> null, 'hora_inicial_por_defecto'=> '16:00:00', 'hora_final_por_defecto'=> '16:15:00', 'tipo_pausa'=> true];
            // Sin horario
            $arrPausa4 = ['tipo' => $huesped->nombre.' - Reunión',      'codigo'=> $last_codigo_descanso + 4, 'descanso'=> false, 'id_proyecto'=> $huesped->id, 'duracion_maxima' => 1,    'cantidad_maxima_evento_dia'=> 1,    'hora_inicial_por_defecto'=> null,       'hora_final_por_defecto'=> null,       'tipo_pausa'=> false];
            $arrPausa5 = ['tipo' => $huesped->nombre.' - Capacitación', 'codigo'=> $last_codigo_descanso + 5, 'descanso'=> false, 'id_proyecto'=> $huesped->id, 'duracion_maxima' => 1,    'cantidad_maxima_evento_dia'=> 1,    'hora_inicial_por_defecto'=> null,       'hora_final_por_defecto'=> null,       'tipo_pausa'=> false];
            $arrPausa6 = ['tipo' => $huesped->nombre.' - Gestión',      'codigo'=> $last_codigo_descanso + 6, 'descanso'=> false, 'id_proyecto'=> $huesped->id, 'duracion_maxima' => 1,    'cantidad_maxima_evento_dia'=> 1,    'hora_inicial_por_defecto'=> null,       'hora_final_por_defecto'=> null,       'tipo_pausa'=> false];
            $arrPausa7 = ['tipo' => $huesped->nombre.' - Baño',         'codigo'=> $last_codigo_descanso + 7, 'descanso'=> true,  'id_proyecto'=> $huesped->id, 'duracion_maxima' => 10,   'cantidad_maxima_evento_dia'=> 3,    'hora_inicial_por_defecto'=> null,       'hora_final_por_defecto'=> null,       'tipo_pausa'=> false];

            $pausa1 = DyTipoDescanso::crearNuevaPausa($arrPausa1);
            $pausa2 = DyTipoDescanso::crearNuevaPausa($arrPausa2);
            $pausa3 = DyTipoDescanso::crearNuevaPausa($arrPausa3);
            $pausa4 = DyTipoDescanso::crearNuevaPausa($arrPausa4);
            $pausa5 = DyTipoDescanso::crearNuevaPausa($arrPausa5);
            $pausa6 = DyTipoDescanso::crearNuevaPausa($arrPausa6);
            $pausa7 = DyTipoDescanso::crearNuevaPausa($arrPausa7);

            // Actualizar tabla dyalogo_general.huespedes
            $huesped->pausa_por_defecto_1 = $pausa1->id;
            $huesped->pausa_por_defecto_2 = $pausa2->id;
            $huesped->pausa_por_defecto_3 = $pausa3->id;
            $huesped->save();

            // Guardar los diferentes tipos de archivos
            $this->uploadFile($request->camara_comercio, ''.$huesped->id.'/camara_comercio');
            $this->uploadFile($request->rut, ''.$huesped->id.'/rut');
            $this->uploadFile($request->certificacion_bancaria, ''.$huesped->id.'/certificacion_bancaria');
            $this->uploadFile($request->orden_compra, ''.$huesped->id.'/orden_compra');
            $this->uploadFile($request->alcances, ''.$huesped->id.'/alcances');

            $con1->commit();
            $con2->commit();
            $con3->commit();

            $data = [
                'code' => 200,
                'status' => 'success',
                'message' => 'Se ha creado el huésped exitosamente',
                'huesped' => $huesped
            ];

        } catch (\Exception $e) {
            $con1->rollBack();
            $con2->rollBack();
            $con3->rollBack();
            // return $e;
            $data = [
                'code' => 400,
                'status' => 'error',
                'message' => 'Error al guardar los datos en el servidor',
                'excepcion' => $e
            ];
        }

        return response()->json($data, $data['code']);
    }

    /**
     * Update the specified resource in storage.
     * @param  \Illuminate\Http\Request $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update($id, HuespedUpdateRequest $request)
    {
        DB::beginTransaction();

        try {

            $nombreHuesped = str_replace('-', '_', $request->nombre);

            // Busqueda y actualizacion de datos del huesped
            $huesped = Huesped::find($id);
            $huesped->nombre = $nombreHuesped;
            $huesped->notificar_pausas = ($request->notificar_pausas) ? 1 : 0;
            $huesped->notificar_sesiones = ($request->notificar_sesiones) ? 1 : 0;
            $huesped->notificar_incumplimientos = ($request->notificar_incumplimientos) ? 1 : 0;
            $huesped->emails_notificar_pausas = $request->emails_notificar_pausas;
            $huesped->emails_notificar_sesiones = $request->emails_notificar_sesiones;
            $huesped->emails_notificar_incumplimientos = $request->emails_notificar_incumplimientos;
            $huesped->razon_social = $request->razon_social;
            $huesped->nit = $request->nit;
            $huesped->id_pais_ciudad = $request->ciudad;
            $huesped->direccion = $request->direccion;
            $huesped->telefono1 = $request->telefono1;
            $huesped->telefono2 = $request->telefono2;
            $huesped->telefono3 = $request->telefono3;
            $huesped->malla_turno_requerida = ($request->mallaTurnoRequerida) ? 1 : 0;
            $huesped->malla_turno_horario_por_defecto = ($request->mallaTurnoHorarioDefecto) ? 1 : 0;
            $huesped->hora_entrada_por_defecto = $request->horaEntrada;
            $huesped->hora_salida_por_defecto = $request->horaSalida;
            $huesped->cantidad_max_supervisores = $request->cantidadMaximaSupervisores;
            $huesped->cantidad_max_bo = $request->cantidadMaximaBackoffice;
            $huesped->cantidad_max_calidad = $request->cantidadMaximaCalidad;
            $huesped->mensaje = $request->mensajeActual;
            $huesped->foto_usuario_obligatoria = ($request->fotoUsuarioObligatoria) ? 1 : 0;
            $huesped->doble_factor_autenticacion = ($request->dobleFactorAuth) ? 1 : 0;
            $huesped->pass_longitud_requerida = ($request->passLongitudRequerida) ? 1 : 0;
            $huesped->pass_mayuscula_requerida = ($request->passMayusculaRequerida) ? 1 : 0;
            $huesped->pass_numero_requerido = ($request->passNumeroRequerido) ? 1 : 0;
            $huesped->pass_simbolo_requerido = ($request->passSimboloRequerido) ? 1 : 0;
            $huesped->pass_cambio_login_requerido = ($request->passCambioObligatorioRequerido) ? 1 : 0;
            $huesped->pass_historico_requerido = ($request->passHistoricoRequerido) ? 1 : 0;
            $huesped->pass_cambio_periodico_requerido = ($request->passCambioPeriodicoRequerido) ? 1 : 0;
            $huesped->pass_dias_cambio_periodico = ($request->passDiasCambioPeriodico) ? $request->passDiasCambioPeriodico : 90;
            $huesped->tipo_gestion_pausa = $request->tipoPausa;

            $huesped->save();

            $proyecto = Dy_Proyectos::where('id_huesped', $huesped->id)->first();
            $proyecto->cantidad_max_agentes_simultaneos = $request->cantidadMaxAgentesSimultaneos;
            $proyecto->nombre = $nombreHuesped;
            $proyecto->save();

            $proyec = Proyect::where('PROYEC_ConsInte__b', $huesped->id)->first();
            $proyec->PROYEC_NomProyec_b = $nombreHuesped;
            $proyec->save();

            // Mail de notificaciones internas para el huesped
            $mailNotificacion = MailNotificacion::updateOrCreate(
                ['id_huesped'=> $huesped->id],
                [
                    'servidor_smtp'=> 'smtp.gmail.com',
                    'dominio' =>  'gmail.com',
                    'puerto' => '587',
                    'usuario' => $request->notificacion_usuario,
                    'password' => $request->notificacion_password,
                    'ttls' => 1,
                    'auth' => 1,
                ]
            );

            // Actualizacion de los contactos del huesped
            $contactosToKeep = [];

            if($request->contacto_nombre){
                for ($i=0; $i < count($request->contacto_nombre) ; $i++) {

                    $contacto = HuespedContacto::updateOrCreate([
                        'id' =>  $request->contacto_id[$i],
                        'id_huesped' => $huesped->id
                    ],[
                        'nombre' => $request->contacto_nombre[$i],
                        'email' =>  $request->contacto_email[$i],
                        'tipo' =>  $request->contacto_tipo[$i],
                        'telefono1' => $request->contacto_telefono1[$i],
                        'telefono2' => $request->contacto_telefono2[$i],
                    ]);

                    $contactosToKeep[] = $contacto->id;
                }
            }

            // Eliminacion de los contactos del huesped
            $all_contactos = HuespedContacto::where('id_huesped', $huesped->id)->get();

            $all_contactos->each(function ($item, $key) use ($contactosToKeep) {
                if (! in_array($item->id, $contactosToKeep)) {
                    $item->delete();
                }
            });

            // Guardar los archivos que se enviaron
            $this->uploadFile($request->camara_comercio, ''.$huesped->id.'/camara_comercio');
            $this->uploadFile($request->rut, ''.$huesped->id.'/rut');
            $this->uploadFile($request->certificacion_bancaria, ''.$huesped->id.'/certificacion_bancaria');
            $this->uploadFile($request->orden_compra, ''.$huesped->id.'/orden_compra');
            $this->uploadFile($request->alcances, ''.$huesped->id.'/alcances');

            DB::commit();

            $data = [
                'code' => 200,
                'status' => 'success',
                'message' => 'Los datos del huésped han sido actualizados',
                'huesped' => $huesped->load('contactos', 'proyecto', 'mailNotificacion', 'pausas')
            ];

        } catch (\Exception $e) {
            DB::rollBack();
            return $e;
            $data = [
                'code' => 400,
                'status' => 'error',
                'message' => 'Se ha presentado un error al guardar los datos',
            ];
        }

        return response()->json($data, $data['code']);
    }

    /**
     * Añade un nuevo contacto a un huesped
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function addNewContacto(Request $request){

        $contacto = new HuespedContacto();
        $contacto->id_huesped = $request->id_huesped;
        $contacto->nombre = $request->contacto_nombre;
        $contacto->email = $request->contacto_email;
        $contacto->tipo = $request->contacto_tipo;
        $contacto->telefono1 = $request->contacto_telefono1;
        $contacto->telefono2 = $request->contacto_telefono2;
        $contacto->save();

        return response()->json($contacto, 200);
    }

    /**
     * Guardar archivos tipo file
     * @param  string $file
     * @param  string $ruta
     * @return \Illuminate\Http\Response
     */
    public function uploadFile($file, $ruta){
        // Recoger la el archivo de la peticion
        $f = $file;

        // Guardar el archivo
        if(!$f){
            $data = [
                'code' => 400,
                'status' => 'error',
                'message' => 'Error al subir el archivo'
            ];
        }else{
            $file_name = $ruta.'.'.$f->getClientOriginalExtension();

            \Storage::disk('huesped')->put($file_name, \File::get($f));

            $data = [
                'code' => 200,
                'status' => 'success',
                'image' => $file_name
            ];
        }
    }

    /**
     * Obtener el archivo solicitado
     * @param  int $id
     * @param  string $tipo
     * @return \Illuminate\Http\Response
     */
    public function getFile($id, $tipo){
        // Comprobar si existe el fichero
        $isset = \Storage::disk('huesped')->exists('/'.$id.'/'.$tipo);

        if($isset){
            // conseguir la imagen
            return \Storage::disk('huesped')->response($id.'/'.$tipo);

        }else{
            $data = [
                'code' => 404,
                'status' => 'error',
                'message' => 'No se ha podido encontrar el documento en el sistema'
            ];

            return view('errors.notFoundIn', $data);
        }
        // Mostrar error
        return response()->json($data, $data['code']);
    }

    /**
     * Esta funcion se encarga de realizar un test de la seccion de notificaciones
     * @param  \Illuminate\Http\Request $request
     * @param  int  $idEmail
     * @return \Illuminate\Http\Response
     */
    public function testCuentaNotificaciones(Request $request, $idEmail){
        // Instancia de la api
        $WSApi = new \WSRest();
        $dataApi = $WSApi->testoutn($idEmail);
        $json = json_decode($dataApi);

        $data = [
            'code' => 200,
            'status' => 'success',
            'message' => 'Se ha ejecutado la prueba',
            'json' => $json,
        ];

        return response()->json($data, $data['code']);
    }



    /**
     * Esta funcion actualiza solamente la seccion de la malla de turno del huesped
     * @param  \Illuminate\Http\Request $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function updateShiftMesh($id, Request $request)
    {
        DB::beginTransaction();

        try {
            // Busqueda y actualizacion de datos del huesped
            $huesped = Huesped::find($id);

            $huesped->malla_turno_requerida = ($request->mallaTurnoRequerida) ? 1 : 0;
            $huesped->malla_turno_horario_por_defecto = ($request->mallaTurnoHorarioDefecto) ? 1 : 0;
            $huesped->hora_entrada_por_defecto = $request->horaEntrada;
            $huesped->hora_salida_por_defecto = $request->horaSalida;


            $huesped->save();

            DB::commit();

            $data = [
                'code' => 200,
                'status' => 'success',
                'message' => 'Los datos de la malla de turno han sido actualizados',
                'huesped' => $huesped
            ];

        } catch (\Exception $e) {
            DB::rollBack();
            return $e;
            $data = [
                'code' => 400,
                'status' => 'error',
                'message' => 'Se ha presentado un error al guardar los datos',
            ];
        }

        return response()->json($data, $data['code']);
    }



      /**
     * Esta funcion actualiza solamente la seccion de notificaciones del huesped
     * @param  \Illuminate\Http\Request $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function updateNotifications($id, Request $request)
    {
        DB::beginTransaction();

        try {

            
            // Busqueda y actualizacion de datos del huesped
            $huesped = Huesped::find($id);
            $huesped->notificar_pausas = ($request->notificar_pausas) ? 1 : 0;
            $huesped->notificar_sesiones = ($request->notificar_sesiones) ? 1 : 0;
            $huesped->notificar_incumplimientos = ($request->notificar_incumplimientos) ? 1 : 0;
            $huesped->emails_notificar_pausas = $request->emails_notificar_pausas;
            $huesped->emails_notificar_sesiones = $request->emails_notificar_sesiones;
            $huesped->emails_notificar_incumplimientos = $request->emails_notificar_incumplimientos;

            $huesped->save();

            // Mail de notificaciones internas para el huesped
            $mailNotificacion = MailNotificacion::updateOrCreate(
                ['id_huesped'=> $huesped->id],
                [
                    'servidor_smtp'=> 'smtp.gmail.com',
                    'dominio' =>  'gmail.com',
                    'puerto' => '587',
                    'usuario' => $request->notificacion_usuario,
                    'password' => $request->notificacion_password,
                    'ttls' => 1,
                    'auth' => 1,
                ]
            );

            DB::commit();

            $data = [
                'code' => 200,
                'status' => 'success',
                'message' => 'Los datos de las notificaciones del huésped han sido actualizados',
                'huesped' => $huesped->load('mailNotificacion')
            ];

        } catch (\Exception $e) {
            DB::rollBack();
            return $e;
            $data = [
                'code' => 400,
                'status' => 'error',
                'message' => 'Se ha presentado un error al guardar los datos',
            ];
        }

        return response()->json($data, $data['code']);
    }


}
