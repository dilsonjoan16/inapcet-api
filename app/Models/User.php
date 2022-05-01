<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
// use Laravel\Sanctum\HasApiTokens;
use Tymon\JWTAuth\Contracts\JWTSubject;

class User extends Authenticatable implements JWTSubject
{
    use /*HasApiTokens,*/ HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'code',
        'rol_id',
        'departament_id',
        'state',
        'user_created',
        'user_updated',
        'user_deleted',
        'user_restored',

    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        'code'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /*
    Añadiremos estos dos métodos
    */
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }


    public function getJWTCustomClaims()
    {
        return [];
    }

    // Relaciones en Modelo

    public function pertenece_roles()
    {
        return $this->belongsTo(Role::class, 'rol_id');
    }

    public function pertecene_departamento()
    {
        return $this->belongsTo(Departamento::class, 'departament_id');
    }

    public function tiene_proyectos()
    {
        return $this->hasMany(UserPivoteProyecto::class);   //TABLA PIVOTE
    }

    public function tiene_usuarios()
    {
        return $this->hasMany(User::class);
    }

    public function tiene_documentos()
    {
        return $this->hasMany(Documento::class);
    }

    public function usuario_creador()
    {
        return $this->belongsTo(User::class, 'user_created');
    }

    public function usuario_modificador()
    {
        return $this->belongsTo(User::class, 'user_updated');
    }

    public function usuario_eliminador()
    {
        return $this->belongsTo(User::class, 'user_deleted');
    }

    public function usuario_restaurador()
    {
        return $this->belongsTo(User::class, 'user_restored');
    }
}
