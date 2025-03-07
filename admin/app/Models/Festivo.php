<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Festivo extends Model
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
    protected $table = 'dy_festivos';

    public $timestamps = false;

    protected $fillable = ['id_lista', 'fecha', 'nombre'];

    public function listaFestivo(){
        return $this->belongsTo('App\Models\ListaFestivo', 'id_lista');
    }
}
