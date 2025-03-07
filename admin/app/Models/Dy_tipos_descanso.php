<?php

namespace App\models;

use Illuminate\Database\Eloquent\Model;

class Dy_tipos_descanso extends Model
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
    protected $fillable = ['tipo', 'codigo', 'descanso', 'id_proyecto', 'duracion_maxima', 'cantidad_maxima_evento_dia'];

    public $timestamps = false;
}
