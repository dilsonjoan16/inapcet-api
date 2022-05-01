<?php

namespace App\Http\Controllers;
use App\Models\Documento;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class DocumentosController extends Controller
{
    public function index()
    {
        $documento = Documento::where('state', 1)->with('pertenece_departamento')->get('id','name','state','departament_id');

        return response()->json(compact('documento'), 200);
    }
    // FUNCION QUE SOLO TRAE REGISTROS ELIMINADOS TEMPORALMENTE
    public function index_trashed()
    {
        $documento = Documento::where('state', 0)->with('pertenece_departamento')->get('id','name','state','departament_id');

        return response()->json(compact('documento'), 200);
    }

    public function show($file)
    {
        $usuario = auth()->user();

        // $documento = Documento::find($id);

        // return response()->json(compact('documento'), 200);

        return Storage::response("archivos/$usuario->name/$file");
    }

    public function store(Request $request)
    {
        $usuario = auth()->user();

        $request->validate([
            'archivo' => 'required|file',
            'state' => 'required|integer',
            'departament_id' => 'required|integer',
            'proyect_id' => 'integer'
        ]);

        $file = time().'-'.$request->file('archivo')->getClientOriginalName();
        $request->file('archivo')->storeAs("archivos/$usuario->name/", $file);

        $saveFile = new Documento;
        $saveFile->name = $file;
        $saveFile->state = $request->get('state');
        $saveFile->departament_id = $request->get('departament_id');
        $saveFile->proyect_id = $request->get('proyect_id') == null ? null : $request->get('proyect_id');
        $saveFile->user_created = $usuario->id;
        $saveFile->save();

        return response()->json(compact('saveFile'), 201);
    }

    public function update(Request $request, $id)
    {
        $usuario = auth()->user();

        $request->validate([
            'archivo' => 'file',
            'state' => 'integer',
            'departament_id' => 'integer',
            'proyect_id' => 'integer'
        ]);

        if ($request->file('archivo') != null || $request->hasFile('archivo')) {
            $file = Documento::find($id);

            Storage::delete("archivos/$usuario->name/$file->name");

            $file_name = time().'-'.$request->file('archivo')->getClientOriginalName();
            $request->file('archivo')->storeAs("archivos/$usuario->name/", $file_name);

            // $saveFile = new Documento;
            $file->name = $file_name;
            $file->state = $request->get('state') == null ? $file->state : $request->get('state');
            $file->departament_id = $request->get('departament_id') == null ? $file->departament_id : $request->get('departament_id');
            $file->proyect_id = $request->get('proyect_id') == null ? $file->proyect_id : $request->get('proyect_id');
            $file->user_updated = $usuario->id;
            $file->update();

            // $file->delete();
        }

        return response()->json(compact('file'), 201);
    }

    // FUNCION PARA DESCARGAR EL ARCHIVO
    public function download($file)
    {
        $usuario = auth()->user();

        return Storage::download("archivos/$usuario->name/$file");
    }

    //FUNCION PARA ELIMINADO TEMPORAL
    public function soft_delete($id)
    {
        $usuario = auth()->user();

        $documento = Documento::find($id);
        $documento->user_deleted = $usuario->id;
        $documento->deleted_at = time();
        $documento->state = 0;
        $documento->update();

        return response()->json(compact('documento'), 200);
    }
    // FUNCION PARA RESTAURAR ELIMINADOS TEMPORALES A TRAVES DEL $ID
    public function restore($id)
    {
        $usuario = auth()->user();

        $documento = Documento::find($id);
        $documento->user_restored = $usuario->id;
        $documento->restored_at = time();
        $documento->state = 1;
        $documento->update();

        return response()->json(compact('documento'), 200);
    }
    // FUNCION PARA RESTAURAR ELIMINADOS TEMPORALES MASIVAMENTE
    public function restore_massive()
    {
        $usuario = auth()->user();

        $documento = Documento::where('state', 0)->get();

        foreach ($documento as $d) {
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
            $documento = Documento::find($id);

            $busqueda = User::where('id', $documento->user_created)->pluck('name');

            Storage::delete("archivos/$busqueda/$documento->name");

            $documento->delete();

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
