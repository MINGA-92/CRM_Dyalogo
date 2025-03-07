<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CuentaCorreoUpdateRequest extends FormRequest
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
            'nombre'                             => 'required|max:255|unique:canales_electronicos.dy_ce_configuracion,nombre,'.$this->id,
            'direccion_correo_electronico'       => 'required|max:320',
            'estado'                             => 'required|numeric',
            'usuario'                            => 'required|max:320',
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
}
