<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TroncalPropiedades extends Model
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
    protected $table = 'dy_propiedades_troncales';

    /**
    * The primary key associated with the table.
    *
    * @var string
    */
    protected $primaryKey = 'id';

    public $timestamps = false;

    protected $fillable = ['propiedad', 'tipo', 'valor_por_defecto', 'archivo', 'nombre', 'descripcion'];
}
