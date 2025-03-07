<?php

namespace App\Models\CanalesElectronicos;

use Illuminate\Database\Eloquent\Model;

class CanalesDymdw extends Model
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
    protected $table = 'dy_canales_dymdw';

    public $timestamps = false;
}