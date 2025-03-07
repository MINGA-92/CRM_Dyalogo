<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TiposDestino extends Model
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
    protected $table = 'tipos_destino';

    /**
    * The primary key associated with the table.
    *
    * @var string
    */
    protected $primaryKey = 'id';

    public $timestamps = false;
    
}