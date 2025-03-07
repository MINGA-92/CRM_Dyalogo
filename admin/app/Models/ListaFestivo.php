<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ListaFestivo extends Model
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
    protected $table = 'dy_listas_festivos';

    public $timestamps = false;

    protected $fillable = ['id_proyecto', 'nombre'];

    public function festivos(){
        return $this->hasMany('App\Models\Festivo', 'id_lista', 'id');
    }
}
