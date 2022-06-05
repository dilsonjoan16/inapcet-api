<?php

namespace App\Http\Controllers;

use App\Models\Proyecto;
use App\Models\Documento;
use App\Models\User;
use App\Models\UserPivoteProyecto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class ProyectosController extends Controller
{
    public function index()
    {
        $proyecto = Proyecto::where('state', 1)->get(['id','name','description','duration','estimated','stage','state', 'created_at']);

        return response()->json(compact('proyecto'), 200);
    }
    // FUNCION QUE SOLO TRAE REGISTROS ELIMINADOS TEMPORALMENTE
    public function index_trashed()
    {
        $proyecto = Proyecto::where('state', 0)->get(['id','name','description','duration','estimated','stage','state', 'deleted_at']);

        return response()->json(compact('proyecto'), 200);
    }

    public function index_audit()
    {
        $proyecto = Proyecto::get(['id', 'name', 'state', 'created_at', 'updated_at', 'deleted_at', 'restored_at']);

        return response()->json(compact('proyecto'), 200);
    }

    public function show($id)
    {
        $proyecto = Proyecto::where('id', $id)->with('tiene_usuarios', 'tiene_multimedia' ,'usuario_creador','usuario_modificador','usuario_eliminador','usuario_restaurador')->get(['id', 'name','description','duration','estimated', 'stage','state','user_created','user_updated','user_deleted','user_restored',]);

        // $usuarios = UserPivoteProyecto::where('proyect_id', $id)->with('pertenece_usuarios')->get(['user_id', 'proyect_id']);
        // dd($usuarios);


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

        if ($request->get('code') !== null) {
            if (Hash::check($request->get('code'), $usuario->code)) {

                $proyecto = new Proyecto;
                $proyecto->name = $request->get('name');
                $proyecto->description = $request->get('description');
                $proyecto->duration = $request->get('duration');
                $proyecto->estimated = $request->get('estimated');
                $proyecto->stage = $request->get('stage');
                $proyecto->state = $request->get('state');
                $proyecto->user_created = $usuario->id;
                $proyecto->save();

            if ($request->get('participantes') !== null || count($request->get('participantes')) !== 0) {

                foreach ($request->get('participantes') as $participante) {
                    $usuarios = new UserPivoteProyecto;
                    $usuarios->user_id = $participante;
                    $usuarios->proyect_id = $proyecto->id;
                    $usuarios->save();
                }
            }

                return response()->json(compact('proyecto'), 201);
            }
            else{
                return response()->json(compact('El codigo es incorrecto'), 400);
            }
        }

        if ($request->get('code') == null) {

            return response()->json(compact('Necesita ingresar el codigo'), 400);
        }

    }

    public function update(Request $request, $id, $state)
    {
        $usuario = auth()->user();

        // $request->validate([
            // 'name' => 'string|unique:proyectos',
            // 'description' => 'string|max:256',
            // 'duration' => 'integer',
            // 'estimated' => 'integer',
            // 'stage' => 'string',
            // 'state' => 'integer'
        // ]);
        if ($request->get('code') !== null) {

            if (Hash::check($request->get('code'), $usuario->code)) {

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
                case '1':       /* CASO O FUNCION PARA CREAR O AÑADIR NUEVOS PARTICIPANTES EN EL PROYECTO */
                    foreach ($request->get('participantes') as $participante) {
                        $usuarios = new UserPivoteProyecto;
                        $usuarios->user_id = $participante;
                        $usuarios->proyect_id = $proyecto->id;
                        $usuarios->save();
                    }
                    break;
                case '2':       /* CASO O FUNCION PARA ELIMINAR A ALGUN PARTICIPANTE DEL PROYECTO */
                    foreach ($request->get('participantes') as $participante) {
                        $usuarios = UserPivoteProyecto::where('user_id',$participante)->get('id');
                        $usuarios_del = UserPivoteProyecto::find($usuarios);
                        $usuarios_del->each->delete();
                    }
                    break;
                case '3':
                    $message = "No se realizo modificacion en el personal";
                    break;
                default:
                    echo('Debe ser un estado valido!');
                    break;
                }

            return response()->json(compact('proyecto'), 200);
            }
            else{
                return response()->json(compact('El codigo es incorrecto'), 400);
            }
        }

        if ($request->get('code') == null) {

            return response()->json(compact('Necesita ingresar el codigo'), 400);
        }
    }
    //FUNCION PARA ELIMINADO TEMPORAL
    public function soft_delete($id)
    {
        $usuario = auth()->user();

        $proyecto = Proyecto::find($id);
        $proyecto->user_deleted = $usuario->id;
        $proyecto->deleted_at = date_create();
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
        $proyecto->restored_at = date_create();
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
            $p->restored_at = date_create();
            $p->state = 1;
            $p->update();
        }

        return response()->json(compact('Restauracion completa'), 200);
    }
    // FUNCION PARA ELIMINAR PERMANENTEMENTE ALGUN REGISTRO
    public function force_delete(Request $request,$id)
    {
        $usuario = auth()->user();

        if ($request->get('code') !== null) {
            if (Hash::check($request->get('code'), $usuario->code)) {
                $proyecto = Proyecto::find($id);
                $proyecto->delete();
            }else{
                return response()->json(compact('El codigo es incorrecto'), 400);
            }

        return response()->json(compact('Eliminacion completa'), 200);
        }

        if ($request->get('code') == null) {

        return response()->json(compact('Necesita ingresar el codigo'), 400);
        }
    }
}
