<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TroncalConfiguracion extends Model
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
    protected $table = 'dy_configuracion_troncales';

    /**
    * The primary key associated with the table.
    *
    * @var string
    */
    protected $primaryKey = 'id';

    public $timestamps = false;

    protected $fillable = ['id_troncal', 'id_propiedad', 'valor', 'orden'];

    public function troncal(){
        return $this->belongsTo('App\Models\Troncal', 'id_troncal', 'id');
    }

    public function propiedad(){
        return $this->belongsTo('App\Models\TroncalPropiedades', 'id_propiedad', 'id');
    }
}
