<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Departamento;

class DepartamentoController extends Controller
{
    public function index()
    {
        $departamento = Departamento::where('state', 1)->get('id','name', 'state');

        return response()->json(compact('departamento'), 200);
    }
    // FUNCION QUE SOLO TRAE REGISTROS ELIMINADOS TEMPORALMENTE
    public function index_trashed()
    {
        $departamento = Departamento::where('state', 0)->get('id','name', 'state');

        return response()->json(compact('departamento'), 200);
    }

    public function show($id)
    {
        $departamento = Departamento::find($id);

        return response()->json(compact('departamento'), 200);
    }

    public function store(Request $request)
    {
        $usuario = auth()->user();

        $request->validate([
            'name' => 'required|string|unique:departamentos',
            'state' => 'required|integer'
        ]);

        $departamento = new Departamento();
        $departamento->name = $request->get('name');
        $departamento->state = $request->get('state');
        $departamento->user_created = $usuario->id;
        $departamento->save();

        return response()->json(compact('departamento'), 201);
    }

    public function update(Request $request, $id)
    {
        $usuario = auth()->user();

        $request->validate([
            'name' => 'string|unique:departamentos',
            'state' => 'integer'
        ]);

        $departamento = Departamento::find($id);
        $departamento->name = $request->get('name') == null ? $departamento->name : $request->get('name');
        $departamento->state = $request->get('state') == null ? $departamento->state : $request->get('state');
        $departamento->user_updated = $usuario->id;
        $departamento->update();

        return response()->json(compact('departamento'), 200);
    }
    //FUNCION PARA ELIMINADO TEMPORAL
    public function soft_delete($id)
    {
        $usuario = auth()->user();

        $departamento = Departamento::find($id);
        $departamento->user_deleted = $usuario->id;
        $departamento->deleted_at = time();
        $departamento->state = 0;
        $departamento->update();

        return response()->json(compact('departamento'), 200);
    }
    // FUNCION PARA RESTAURAR ELIMINADOS TEMPORALES A TRAVES DEL $ID
    public function restore($id)
    {
        $usuario = auth()->user();

        $departamento = Departamento::find($id);
        $departamento->user_restored = $usuario->id;
        $departamento->restored_at = time();
        $departamento->state = 1;
        $departamento->update();

        return response()->json(compact('departamento'), 200);
    }
    // FUNCION PARA RESTAURAR ELIMINADOS TEMPORALES MASIVAMENTE
    public function restore_massive()
    {
        $usuario = auth()->user();

        $departamento = Departamento::where('state', 0)->get();

        foreach ($departamento as $d) {
            $d->user_restored = $usuario->id;
            $d->restored_at = time();
            $d->state = 1;
            $d->update();
        }

        return response()->json(compact('Restauracion completa'), 200);
    }
    // FUNCION PARA ELIMINAR PERMANENTEMENTE ALGUN REGISTRO
    public function force_delete(Request $request,$id)
    {
        $usuario = auth()->user();

        if ($request->get('code') !== null && $request->get('code') == $usuario->code) {
            $departamento = Departamento::find($id);
            $departamento->delete();

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
