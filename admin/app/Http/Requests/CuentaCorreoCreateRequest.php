<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CuentaCorreoCreateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'nombre'                             => 'required|unique:canales_electronicos.dy_ce_configuracion|max:255',
            'direccion_correo_electronico'       => 'required|max:320',
            'estado'                             => 'required|numeric',
            'usuario'                            => 'required|max:320',
            'contrasena'                         => 'required|max:255',
            'intervalo_refresque'                => 'required|numeric',
            'buzon'                              => 'required|max:255',
            'proveedor'                          => 'required|max:255',
            'saliente_responder_a'               => 'required|max:255',
            'saliente_nombre_remitente'          => 'required|max:255',
            'servidor_saliente_direccion'        => 'required|max:255',
            'servidor_saliente_tipo'             => 'required',
            'servidor_saliente_puerto'           => 'required|numeric',
            'servidor_entrante_direccion'        => 'required|max:255',
            'servidor_entrante_tipo'             => 'required',
            'servidor_entrante_puerto'           => 'required|numeric',
        ];
    }

    public function attributes()
    {
        return [
            'nombre'                             => 'Nombre',
            'direccion_correo_electronico'       => 'Correo electrónico',
            'estado'                             => 'Estado',
            'estado_servicio'                    => 'Estado servicio',
            'usuario'                            => 'Usuario',
            'contrasena'                         => 'Contraseña',
            'intervalo_refresque'                => 'Intervalo de refresque',
            'buzon'                              => 'Buzon',
            'mensajes_estado'                    => 'Mensajes de estado',
            'saliente_responder_a'               => 'Responder a',
            'saliente_nombre_remitente'          => 'Nombre remitente',
            'servidor_saliente_direccion'        => 'Direccion',
            'servidor_saliente_tipo'             => 'Tipo',
            'servidor_saliente_puerto'           => 'Puerto',
            'servidor_entrante_direccion'        => 'Direccion',
            'servidor_entrante_tipo'             => 'Tipo',
            'servidor_entrante_puerto'           => 'Puerto',
        ];
    }
}
