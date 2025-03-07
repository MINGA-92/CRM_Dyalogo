<?php

namespace App\models;

use Illuminate\Database\Eloquent\Model;

class Dy_Proyectos extends Model
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
    protected $table = 'dy_proyectos';

    public $timestamps = false;

    public function huesped(){
        return $this->belongsTo('App\Models\Huesped', 'id_huesped', 'id');
        
    }
}
