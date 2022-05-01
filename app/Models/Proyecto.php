<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Proyecto extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'duration',
        'estimated', //Presupuesto en $
        'stage', //Etapa del Proyecto
        'state',
        'user_created',
        'user_updated',
        'user_deleted',
        'user_restored',
    ];

    // RELACIONES EN MODELO

    public function tiene_usuarios()
    {
        return $this->hasMany(UserPivoteProyecto::class);   //TABLA PIVOTE
    }

    public function tiene_multimedia()
    {
        return $this->hasMany(Documento::class);    //IMAGENES Y VIDEOS DE LOS PROYECTOS
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
