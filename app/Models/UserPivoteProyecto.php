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
        return $this->belongsToMany(Proyecto::class, 'proyect_id');
    }

    public function pertenece_usuarios()
    {
        return $this->belongsToMany(User::class, 'user_id');
    }
}
