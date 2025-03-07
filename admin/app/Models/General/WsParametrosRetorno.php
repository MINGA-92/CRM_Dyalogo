<?php

namespace App\Models\General;

use Illuminate\Database\Eloquent\Model;

class WsParametrosRetorno extends Model
{
    /**
    * The database connection used by the model.
    *
    * @var string
    */
    protected $connection = 'general';

    /**
    * The database table used by the model.
    *
    * @var string
    */
    protected $table = 'ws_parametros_retorno';

    public $timestamps = false;
}
