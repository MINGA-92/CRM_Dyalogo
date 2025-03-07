<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DyTipoDescanso extends Model
{
    /**
    * The database connection used by the model.
    *
    * @var string
    */
    protected $connection = 'telefonia';

    /**
    * The database table used by the model.
    *
    * @var string
    */
    protected $table = 'dy_tipos_descanso';

    /**
     * The primary key associated with the table.
     *
     * @var string
    */
    protected $primaryKey = 'id';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['tipo', 'codigo', 'descanso', 'id_proyecto', 'duracion_maxima', 'cantidad_maxima_evento_dia', 'hora_inicial_por_defecto', 'hora_final_por_defecto', 'tipo_pausa'];

    public $timestamps = false;

    public static function crearNuevaPausa($arrData){
        $pausa = new DyTipoDescanso();
        $pausa->tipo = $arrData['tipo'];
        $pausa->codigo = $arrData['codigo'];
        $pausa->descanso = $arrData['descanso'];
        $pausa->id_proyecto = $arrData['id_proyecto'];
        $pausa->tipo = $arrData['tipo'];
        $pausa->duracion_maxima = $arrData['duracion_maxima'];
        $pausa->cantidad_maxima_evento_dia = $arrData['cantidad_maxima_evento_dia'];
        $pausa->hora_inicial_por_defecto = $arrData['hora_inicial_por_defecto'];
        $pausa->hora_final_por_defecto = $arrData['hora_final_por_defecto'];
        $pausa->tipo_pausa = $arrData['tipo_pausa'];
        $pausa->save();

        return $pausa;
    }
}
