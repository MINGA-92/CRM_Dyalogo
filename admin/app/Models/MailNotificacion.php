<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MailNotificacion extends Model
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
    protected $table = 'dy_email_smtp';

    public $timestamps = false;

    protected $fillable =['id_huesped', 'servidor_smtp','dominio','puerto','usuario','password','ttls','auth','servidor_imap','puerto_imap'];
}
