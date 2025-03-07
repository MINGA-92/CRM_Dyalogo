<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class HuespedCreateRequest extends FormRequest
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
            'nombre'                        => 'required|max:255',
            'razon_social'                  => 'required|max:255',
            'nit'                           => 'required|max:50',
            'pais'                          => 'required',
            'ciudad'                        => 'required',
            'direccion'                     => 'required|max:255',
            'telefono1'                     => 'required|max:15',
            'telefono2'                     => 'max:15',
            'telefono3'                     => 'max:15',
            'contacto_nombre.*'             => 'required|max:255',
            'contacto_email.*'              => 'required|email|max:255',
            'contacto_tipo.*'               => 'required|max:1',
            'contacto_telefono1.*'          => 'required|max:15',
            'contacto_telefono2.*'          => 'max:15',
            'camara_comercio'               => 'required|max:4000|mimes:pdf',
            'rut'                           => 'required|max:4000|mimes:pdf',
            'certificacion_bancaria'        => 'required|max:4000|mimes:pdf',
            'orden_compra'                  => 'required|max:4000|mimes:pdf',
            'alcances'                      => 'required|max:4000|mimes:pdf',
            'horaEntrada'                   => 'required|date_format:H:i:s',
            'horaSalida'                    => 'required|date_format:H:i:s',
            'cantidadMaxAgentesSimultaneos' => 'required|numeric',
            'cantidadMaximaSupervisores'    => 'required|numeric',
            'cantidadMaximaBackoffice'      => 'required|numeric',
            'cantidadMaximaCalidad'         => 'required|numeric',
            'notificacion_usuario'          => 'required|email|max:255',
            'notificacion_password'         => 'required|max:255',
            'notificacion_servidor_smtp'    => 'required|max:255',
            'notificacion_dominio'          => 'required|max:255',
            'notificacion_puerto'           => 'required|numeric',
            'notificar_incumplimientos'     => 'required',
            'emails_notificar_incumplimientos' => 'required'
        ];
    }
}
