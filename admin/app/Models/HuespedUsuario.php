<?php

namespace App\models;

use Illuminate\Database\Eloquent\Model;

class HuespedUsuario extends Model
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
    protected $table = 'huespedes_usuarios';

    public $timestamps = false;
}
