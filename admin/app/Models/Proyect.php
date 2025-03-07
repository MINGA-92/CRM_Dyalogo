<?php

namespace App\models;

use Illuminate\Database\Eloquent\Model;

class Proyect extends Model
{
    /**
    * The database connection used by the model.
    *
    * @var string
    */
    protected $connection = 'crm_sistema';

    /**
    * The database table used by the model.
    *
    * @var string
    */
    protected $table = 'PROYEC';

    /**
    * The primary key associated with the table.
    *
    * @var string
    */
    protected $primaryKey = 'PROYEC_ConsInte__b';

    public $timestamps = false;
}
