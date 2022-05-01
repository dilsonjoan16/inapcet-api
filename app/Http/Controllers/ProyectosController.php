<?php

namespace App\Http\Controllers;

use App\Models\Proyecto;
use App\Models\Documento;
use App\Models\UserPivoteProyecto;
use Illuminate\Http\Request;

class ProyectosController extends Controller
{
    public function index()
    {
        $proyecto = Proyecto::where('state', 1)->get('id','name','description','duration','estimated','stage','state',);

        return response()->json(compact('proyecto'), 200);
    }
    // FUNCION QUE SOLO TRAE REGISTROS ELIMINADOS TEMPORALMENTE
    public function index_trashed()
    {
        $proyecto = Proyecto::where('state', 0)->get('id','name','description','duration','estimated','stage','state',);

        return response()->json(compact('proyecto'), 200);
    }

    public function show($id)
    {
        $proyecto = Proyecto::find($id);

        return response()->json(compact('proyecto'), 200);
    }

    public function store(Request $request)
    {
        $usuario = auth()->user();

        $request->validate([
            'name' => 'required|string|unique:proyectos',
            'description' => 'required|string|max:256',
            'duration' => 'integer',
            'estimated' => 'integer',
            'stage' => 'required|string',
            'state' => 'required|integer',
        ]);

        if ($request->get('code') !== null && $request->get('code') == $usuario->code) {

            $proyecto = new Proyecto;
            $proyecto->name = $request->get('name');
            $proyecto->description = $request->get('description');
            $proyecto->duration = $request->get('duration');
            $proyecto->estimated = $request->get('estimated');
            $proyecto->stage = $request->get('stage');
            $proyecto->state = $request->get('state');
            $proyecto->user_created = $usuario->id;
            $proyecto->save();

            foreach ($request->get('participantes') as $participante) {
                $usuarios = new UserPivoteProyecto;
                $usuarios->user_id = $participante;
                $usuarios->proyect_id = $proyecto->id;
                $usuarios->save();
            }

            return response()->json(compact('proyecto'), 201);
        }

        if ($request->get('code') == null) {

            return response()->json(compact('Necesita ingresar el codigo'), 400);
        }

        if ($request->get('code') !== $usuario->code) {

            return response()->json(compact('El codigo es incorrecto'), 400);
        }

    }

    public function update(Request $request, $id, $state)
    {
        $usuario = auth()->user();

        $request->validate([
            'name' => 'required|string|unique:proyectos',
            'description' => 'required|string|max:256',
            'duration' => 'integer',
            'estimated' => 'integer',
            'stage' => 'required|string',
            'state' => 'required|integer'
        ]);
        if ($request->get('code') !== null && $request->get('code') == $usuario->code) {

            $proyecto = Proyecto::find($id);
            $proyecto->name = $request->get('name') == null ? $proyecto->name : $request->get('name');
            $proyecto->description = $request->get('description') == null ? $proyecto->description : $request->get('description');
            $proyecto->duration = $request->get('duration') == null ? $proyecto->duration : $request->get('duration');
            $proyecto->estimated = $request->get('estimated') == null ? $proyecto->estimated : $request->get('estimated');
            $proyecto->stage = $request->get('stage') == null ? $proyecto->stage : $request->get('stage');
            $proyecto->state = $request->get('state') == null ? $proyecto->state : $request->get('state');
            $proyecto->user_updated = $usuario->id;
            $proyecto->update();

            switch ($state) {
                case '1':
                    foreach ($request->get('participantes') as $participante) {
                        $usuarios = new UserPivoteProyecto;
                        $usuarios->user_id = $participante;
                        $usuarios->proyect_id = $proyecto->id;
                        $usuarios->save();
                    }
                    break;
                case '2':
                    foreach ($request->get('participantes') as $participante) {
                        $usuarios = UserPivoteProyecto::find($participante);
                        $usuarios->delete();
                    }
                    break;

                default:
                    echo('Debe ser un estado valido!');
                    break;
            }

            return response()->json(compact('proyecto'), 200);
        }

        if ($request->get('code') == null) {

            return response()->json(compact('Necesita ingresar el codigo'), 400);
        }

        if ($request->get('code') !== $usuario->code) {

            return response()->json(compact('El codigo es incorrecto'), 400);
        }

    }
    //FUNCION PARA ELIMINADO TEMPORAL
    public function soft_delete($id)
    {
        $usuario = auth()->user();

        $proyecto = Proyecto::find($id);
        $proyecto->user_deleted = $usuario->id;
        $proyecto->deleted_at = time();
        $proyecto->state = 0;
        $proyecto->update();

        return response()->json(compact('proyecto'), 200);
    }
    // FUNCION PARA RESTAURAR ELIMINADOS TEMPORALES A TRAVES DEL $ID
    public function restore($id)
    {
        $usuario = auth()->user();

        $proyecto = Proyecto::find($id);
        $proyecto->user_restored = $usuario->id;
        $proyecto->restored_at = time();
        $proyecto->state = 1;
        $proyecto->update();

        return response()->json(compact('proyecto'), 200);
    }
    // FUNCION PARA RESTAURAR ELIMINADOS TEMPORALES MASIVAMENTE
    public function restore_massive()
    {
        $usuario = auth()->user();

        $proyecto = Proyecto::where('state', 0)->get();

        foreach ($proyecto as $p) {
            $p->user_restored = $usuario->id;
            $p->restored_at = time();
            $p->state = 1;
            $p->update();
        }

        return response()->json(compact('Restauracion completa'), 200);
    }
    // FUNCION PARA ELIMINAR PERMANENTEMENTE ALGUN REGISTRO
    public function force_delete(Request $request,$id)
    {
        $usuario = auth()->user();

        if ($request->get('code') !== null && $request->get('code') == $usuario->code) {
            $proyecto = Proyecto::find($id);
            $proyecto->delete();

        return response()->json(compact('Eliminacion completa'), 200);
        }

        if ($request->get('code') == null) {

        return response()->json(compact('Necesita ingresar el codigo'), 400);
        }

        if ($request->get('code') !== $usuario->code) {

        return response()->json(compact('El codigo es incorrecto'), 400);
        }
    }
}
