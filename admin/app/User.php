<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;

    /**
    * The database connection used by the model.
    *
    * @var string
    */
    protected $connection = 'crm_sistema';

    /**
	 * The table associated with the model.
	 *
	 * @var string
	*/
    protected $table = 'USUARI';
    
    protected $primaryKey = 'USUARI_ConsInte__b';

	public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'USUARI_Nombre____b', 'USUARI_Correo___b', 'USUARI_Clave_____b', 'USUARI_Foto______b', 'USUARI_Identific_b', 'USUARI_HorIniLun_b',
		'USUARI_UsuaCBX___b', 'USUARI_HorFinLun_b', 'USUARI_HorIniLun_b', 'USUARI_HorIniMar_b', 'USUARI_HorFinLun_b', 'USUARI_HorFinMar_b',
		'USUARI_HorIniMar_b', 'USUARI_HorIniMie_b', 'USUARI_HorFinMar_b', 'USUARI_HorFinMie_b', 'USUARI_HorIniMie_b',
		'USUARI_HorIniJue_b', 'USUARI_HorFinMie_b', 'USUARI_HorFinJue_b', 'USUARI_HorIniJue_b', 'USUARI_HorIniVie_b',
		'USUARI_HorFinJue_b', 'USUARI_HorFinVie_b', 'USUARI_HorIniVie_b', 'USUARI_HorIniSab_b', 'USUARI_HorFinVie_b',
		'USUARI_HorFinSab_b', 'USUARI_HorIniSab_b', 'USUARI_HorIniDom_b', 'USUARI_HorFinSab_b', 'USUARI_HorFinDom_b',
		'USUARI_HorIniDom_b', 'USUARI_HorIniFes_b', 'USUARI_HorFinDom_b', 'USUARI_HorFinFes_b', 'USUARI_HorIniFes_b',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'USUARI_Clave_____b', 'remember_token'
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function huespedes(){
        return $this->belongsToMany('App\Models\Huesped', 'dyalogo_general.huespedes_usuarios', 'USUARI_UsuaCBX___b', 'id_huesped');
    }
}
