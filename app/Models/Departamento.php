<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Departamento extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'state',
        'user_created',
        'user_updated',
        'user_deleted',
        'user_restored',
    ];

    // Relaciones en Modelo

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
