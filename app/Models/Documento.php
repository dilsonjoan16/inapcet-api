<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Documento extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'state',
        'departament_id',
        'proyect_id',
        'user_created',
        'user_updated',
        'user_deleted',
        'user_restored',
    ];

    // RELACIONES EN MODELO

    public function pertenece_departamento()
    {
        return $this->belongsTo(Departamento::class, 'departament_id');
    }

    public function pertenece_proyectos()
    {
        return $this->belongsTo(Proyecto::class, 'proyect_id'); //IMAGENES O VIDEOS DE PROYECTOS
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
