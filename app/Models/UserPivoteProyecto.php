<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserPivoteProyecto extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'proyect_id'
    ];

    // RELACIONES EN MODELO

    public function pertenece_proyectos()
    {
        return $this->hasMany(Proyecto::class, 'id', 'proyect_id');
    }

    public function pertenece_usuarios()
    {
        return $this->hasMany(User::class, 'id', 'user_id');
    }
}
