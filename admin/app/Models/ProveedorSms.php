<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProveedorSms extends Model
{
     /**
    * The database connection used by the model.
    *
    * @var string
    */
    protected $connection = 'dy_sms';

    /**
    * The database table used by the model.
    *
    * @var string
    */
    protected $table = 'configuracion';

    public $timestamps = false;

    protected $hidden = [
        'api_secret'
    ];
}
