<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CuentaCorreo extends Model
{
    /**
    * The database connection used by the model.
    *
    * @var string
    */
    protected $connection = 'canales_electronicos';

    /**
    * The database table used by the model.
    *
    * @var string
    */
    protected $table = 'dy_ce_configuracion';

    public $timestamps = false;

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'contrasena'
    ];

    public static function guardar($request, $huesped_id, $contrasenaEncript){

        $newCuenta = new CuentaCorreo();

        $newCuenta->nombre = $request->nombre;
        $newCuenta->direccion_correo_electronico = $request->direccion_correo_electronico;
        $newCuenta->id_huesped = $huesped_id;

        $newCuenta->proveedor = $request->proveedor;
        $newCuenta->estado = $request->estado;
        $newCuenta->estado_servicio = $request->estado_servicio;
        $newCuenta->usuario = $request->usuario;
        $newCuenta->contrasena = $contrasenaEncript;
        $newCuenta->intervalo_refresque = $request->intervalo_refresque;
        $newCuenta->buzon = $request->buzon;
        $newCuenta->borrar_correos_procesados = ($request->borrar_correos_procesados) ? 1 : 0;
        $newCuenta->mensajes_estado = $request->mensajes_estado;

        $newCuenta->saliente_responder_a = $request->saliente_responder_a;
        $newCuenta->saliente_nombre_remitente = $request->saliente_nombre_remitente;

        $newCuenta->servidor_saliente_direccion = $request->servidor_saliente_direccion;
        $newCuenta->servidor_saliente_tipo = $request->servidor_saliente_tipo;
        $newCuenta->servidor_saliente_puerto = $request->servidor_saliente_puerto;
        $newCuenta->servidor_saliente_usar_auth = ($request->servidor_saliente_usar_auth) ? 1 : 0;
        $newCuenta->servidor_saliente_usar_start_ttls = ($request->servidor_saliente_usar_start_ttls) ? 1 : 0;
        $newCuenta->servidor_saliente_usar_ssl = ($request->servidor_saliente_usar_ssl) ? 1 : 0;

        $newCuenta->servidor_entrante_direccion = $request->servidor_entrante_direccion;
        $newCuenta->servidor_entrante_tipo = $request->servidor_entrante_tipo;
        $newCuenta->servidor_entrante_puerto = $request->servidor_entrante_puerto;
        $newCuenta->servidor_entrante_usar_auth = ($request->servidor_entrante_usar_auth) ? 1 : 0;
        $newCuenta->servidor_entrante_usar_start_ttls = ($request->servidor_entrante_usar_start_ttls) ? 1 : 0;
        $newCuenta->servidor_entrante_usar_ssl = ($request->servidor_entrante_usar_ssl) ? 1 : 0;

        $newCuenta->servidor_entrante_protocolo = $request->protocoloEntrada;

        $newCuenta->save();

        return $newCuenta;
    }

    public static function actualizar($request, $cuentaCorreo, $contrasenaEncript){

        $updateCuenta = $cuentaCorreo;
        $updateCuenta->nombre = $request->nombre;
        $updateCuenta->direccion_correo_electronico = $request->direccion_correo_electronico;

        $updateCuenta->estado = $request->estado;
        $updateCuenta->estado_servicio = $request->estado_servicio;
        $updateCuenta->usuario = $request->usuario;
        
        if($request->contrasena){
            $updateCuenta->contrasena = $contrasenaEncript;
        }
        $updateCuenta->intervalo_refresque = $request->intervalo_refresque;
        $updateCuenta->buzon = $request->buzon;
        $updateCuenta->borrar_correos_procesados = ($request->borrar_correos_procesados) ? 1 : 0;
        $updateCuenta->mensajes_estado = $request->mensajes_estado;

        $updateCuenta->proveedor = $request->proveedor;
        $updateCuenta->saliente_responder_a = $request->saliente_responder_a;
        $updateCuenta->saliente_nombre_remitente = $request->saliente_nombre_remitente;

        $updateCuenta->servidor_saliente_direccion = $request->servidor_saliente_direccion;
        $updateCuenta->servidor_saliente_tipo = $request->servidor_saliente_tipo;
        $updateCuenta->servidor_saliente_puerto = $request->servidor_saliente_puerto;
        $updateCuenta->servidor_saliente_usar_auth = ($request->servidor_saliente_usar_auth) ? 1 : 0;
        $updateCuenta->servidor_saliente_usar_start_ttls = ($request->servidor_saliente_usar_start_ttls) ? 1 : 0;
        $updateCuenta->servidor_saliente_usar_ssl = ($request->servidor_saliente_usar_ssl) ? 1 : 0;

        $updateCuenta->servidor_entrante_direccion = $request->servidor_entrante_direccion;
        $updateCuenta->servidor_entrante_tipo = $request->servidor_entrante_tipo;
        $updateCuenta->servidor_entrante_puerto = $request->servidor_entrante_puerto;
        $updateCuenta->servidor_entrante_usar_auth = ($request->servidor_entrante_usar_auth) ? 1 : 0;
        $updateCuenta->servidor_entrante_usar_start_ttls = ($request->servidor_entrante_usar_start_ttls) ? 1 : 0;
        $updateCuenta->servidor_entrante_usar_ssl = ($request->servidor_entrante_usar_ssl) ? 1 : 0;

        $updateCuenta->servidor_entrante_protocolo = $request->protocoloEntrada;

        $updateCuenta->save();

        return $updateCuenta;
    }
}
