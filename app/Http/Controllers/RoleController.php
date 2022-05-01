<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Role;

class RoleController extends Controller
{
    public function index()
    {
        $role = Role::where('state', 1)->get('id','name','state');

        return response()->json(compact('role'), 200);
    }
    // FUNCION QUE SOLO TRAE REGISTROS ELIMINADOS TEMPORALMENTE
    public function index_trashed()
    {
        $role = Role::where('state', 0)->get('id','name','state');

        return response()->json(compact('role'), 200);
    }

    public function show($id)
    {
        $role = Role::find($id);

        return response()->json(compact('role'), 200);
    }

    public function store(Request $request)
    {
        $usuario = auth()->user();

        $request->validate([
            'name' => 'required|string|unique:roles',
            'state' => 'required|integer'
        ]);

        $role = new Role;
        $role->name = $request->get('name');
        $role->state = $request->get('state');
        $role->user_created = $usuario->id;
        $role->save();

        return response()->json(compact('role'), 201);
    }

    public function update(Request $request, $id)
    {
        $usuario = auth()->user();

        $request->validate([
            'name' => 'string|unique:roles',
            'state' => 'integer'
        ]);

        $role = Role::find($id);
        $role->name = $request->get('name') == null ? $role->name : $request->get('name');
        $role->state = $request->get('state') == null ? $role->state : $request->get('state');
        $role->user_updated = $usuario->id;
        $role->update();

        return response()->json(compact('role'), 200);
    }
    //FUNCION PARA ELIMINADO TEMPORAL
    public function soft_delete($id)
    {
        $usuario = auth()->user();

        $role = Role::find($id);
        $role->user_deleted = $usuario->id;
        $role->deleted_at = time();
        $role->state = 0;
        $role->update();

        return response()->json(compact('role'), 200);
    }
    // FUNCION PARA RESTAURAR ELIMINADOS TEMPORALES A TRAVES DEL $ID
    public function restore($id)
    {
        $usuario = auth()->user();

        $role = Role::find($id);
        $role->user_restored = $usuario->id;
        $role->restored_at = time();
        $role->state = 1;
        $role->update();

        return response()->json(compact('role'), 200);
    }
    // FUNCION PARA RESTAURAR ELIMINADOS TEMPORALES MASIVAMENTE
    public function restore_massive()
    {
        $usuario = auth()->user();

        $role = Role::where('state', 0)->get();

        foreach ($role as $r) {
            $r->user_restored = $usuario->id;
            $r->restored_at = time();
            $r->state = 1;
            $r->update();
        }

        return response()->json(compact('Restauracion completa'), 200);
    }
    // FUNCION PARA ELIMINAR PERMANENTEMENTE ALGUN REGISTRO
    public function force_delete(Request $request,$id)
    {
        $usuario = auth()->user();

        if ($request->get('code') !== null && $request->get('code') == $usuario->code) {
            $role = Role::find($id);
            $role->delete();

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
