<?php

namespace App\Models\CanalesElectronicos;

use Illuminate\Database\Eloquent\Model;

class WaPlantillas extends Model
{
    /**
    * The database connection used by the model.
    *
    * @var string
    */
    protected $connection = 'canales_electronicos';
    /**
    * The database table used by the model.
    *
    * @var string
    */
    protected $table = 'dy_wa_plantillas';

    public $timestamps = false;
}
