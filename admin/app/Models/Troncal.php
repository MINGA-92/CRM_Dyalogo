<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Troncal extends Model
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
    protected $table = 'dy_troncales';

    /**
    * The primary key associated with the table.
    *
    * @var string
    */
    protected $primaryKey = 'id';

    public $timestamps = false;

    public function huesped(){
        return $this->belongsTo('App\Models\Huesped', 'id_proyecto', 'id');
    }

    public function configuraciones(){
        return $this->hasMany('App\Models\TroncalConfiguracion', 'id_troncal', 'id')->orderBy('orden');
    }
    
}
