<?php

namespace App\models;

use Illuminate\Database\Eloquent\Model;

class HuespedContacto extends Model
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
    protected $table = 'huespedes_contactos';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['id_huesped', 'nombre', 'email', 'tipo', 'telefono1', 'telefono2'];

    public $timestamps = false;

    /**
     * Get the post that owns the huesped.
    */
    public function huesped(){
        return $this->belongsTo('App\Models\Huesped', 'id_huesped', 'id');
    }
}
