<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Huesped extends Model
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
    protected $table = 'huespedes';

    public $timestamps = false;

    /**
     * Get the contactos for the huesped.
    */
    public function contactos(){
        return $this->hasMany('App\Models\HuespedContacto', 'id_huesped', 'id');
    }

    public function pausas(){
        return $this->hasMany('App\Models\DyTipoDescanso', 'id_proyecto', 'id');
    }

    public function proyecto(){
        return $this->hasOne('App\Models\Dy_Proyectos', 'id_huesped', 'id');
    }

    public function mailNotificacion(){
        return $this->hasOne('App\Models\MailNotificacion', 'id_huesped', 'id');
    }

    public function troncales(){
        return $this->hasMany('App\Models\Troncal', 'id_proyecto', 'id');
    }

    // Query Scope
    public function scopeName($query, $name){
        if($name)
            return $query->where('nombre', 'LIKE', "%$name%");
    }
}
