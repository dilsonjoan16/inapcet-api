<?php

namespace App\Http\Controllers;
use App\Models\Documento;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use ZipArchive;

class DocumentosController extends Controller
{
    public function index()
    {
        $documento = Documento::where('state', 1)->with('pertenece_departamento', 'pertenece_proyectos','usuario_creador','usuario_modificador','usuario_eliminador','usuario_restaurador')->get(['id','name','state','created_at', 'updated_at', 'deleted_at', 'restored_at','departament_id', 'proyect_id', 'user_created','user_updated','user_deleted','user_restored']);

        return response()->json(compact('documento'), 200);

    }

    // FUNCION QUE SOLO TRAE REGISTROS DEL USUARIO
    public function index_single()
    {
        $usuario = auth()->user();

        $documento = Documento::where('user_created', $usuario->id)->where('state', 1)->with('pertenece_departamento', 'pertenece_proyectos')->get(['id','name','state','created_at','departament_id', 'proyect_id']);

        return response()->json(compact('documento'), 200);
    }

    //FUNCION QUE SOLO TRAE REGISTROS CON EL DEPARTAMENTO EN COMUN
    public function index_departament()
    {
        $usuario = auth()->user();
        if ($usuario->rol_id == 2) {
            $documento = Documento::where('departament_id', $usuario->departament_id)->where('state', 1)->with('pertenece_proyectos','usuario_creador')->get(['id','name','state','created_at','proyect_id', 'user_created']);

            return response()->json(compact('documento'), 200);
        }

        $documento = Documento::where('departament_id', $usuario->departament_id)->where('state', 1)->with('pertenece_proyectos')->get(['id','name','state','proyect_id']);

        return response()->json(compact('documento'), 200);
    }

    // FUNCION QUE SOLO TRAE REGISTROS ELIMINADOS TEMPORALMENTE
    public function index_trashed()
    {
        $usuario = auth()->user();

        if ($usuario->rol_id == 1) {
            $documento = Documento::where('state', 0)->with('pertenece_departamento','pertenece_proyectos','usuario_eliminador')->get(['id','name','deleted_at','departament_id', 'proyect_id', 'user_deleted']);

            return response()->json(compact('documento'), 200);
        }

        $documento = Documento::where('state', 0)->where('departament_id', $usuario->departament_id)->with('pertenece_proyectos','usuario_eliminador')->get(['id','name','deleted_at', 'proyect_id', 'user_deleted']);

        return response()->json(compact('documento'), 200);
    }

    public function show(/*$file*/$id)
    {
        $usuario = auth()->user();

        $documento = Documento::with('pertenece_departamento','pertenece_proyectos')->find($id);

        return response()->json(compact('documento'), 200);

        // return Storage::response("archivos/$usuario->name/$file");
    }

    public function store(Request $request)
    {
        $usuario = auth()->user();

        $request->validate([
            'archivo' => 'required|file',
            'state' => 'required|integer',
            'departament_id' => 'required|integer',
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
            // 'archivo' => 'file',
            'state' => 'integer',
            'departament_id' => 'integer',
        ]);

        $file = Documento::find($id);


        if ($request->file('archivo') != null || $request->hasFile('archivo')) {

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
            return response()->json(compact('file'), 201);
        }
        if ($request->file('archivo') == null) {

            $file->name = $file->name;
            $file->state = $request->get('state') == null ? $file->state : $request->get('state');
            $file->departament_id = $request->get('departament_id') == null ? $file->departament_id : $request->get('departament_id');
            $file->proyect_id = $request->get('proyect_id') == null ? $file->proyect_id : $request->get('proyect_id');
            $file->user_updated = $usuario->id;
            $file->update();

            // $file->delete();
            return response()->json(compact('file'), 201);
        }

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
        $documento->deleted_at = date_create();
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
        $documento->restored_at = date_create();
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
            $d->restored_at = date_create();
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

        return response()->json('Eliminacion Completa');
        }

        if ($request->get('code') == null) {

        return response()->json(compact('Necesita ingresar el codigo'), 400);
        }

        if ($request->get('code') !== $usuario->code) {

        return response()->json(compact('El codigo es incorrecto'), 400);
        }
    }

    public function download_zip()
    {
        $usuario = auth()->user();

        $zip = new ZipArchive();
        $zip->open('ComprimidoINAPCET.zip', ZipArchive::CREATE);
        $zip->addGlob("archivos/$usuario->name/*");
        $zip->close();

        // $headers = array(
        //     'Content-Type' => 'application/octet-stream',
        // );

        return response()->download($zip);
    }
}
