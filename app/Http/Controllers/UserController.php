<?php

namespace App\Http\Controllers;

use App\Mail\DeletedMail;
use App\Mail\RegisterMail;
use App\Mail\RestoredMail;
use App\Models\Departamento;
use App\Models\User;
    use Illuminate\Http\Request;
    use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
    use JWTAuth;
    // use Tymon\JWTAuth\Facades\JWTAuth;
    use Tymon\JWTAuth\Exceptions\JWTException;

class UserController extends Controller
{
    public function authenticate(Request $request)
    {
        $credentials = $request->only('email', 'password');
        try {
            if (! $token = JWTAuth::attempt($credentials)) {
                return response()->json(['error' => 'invalid_credentials'], 400);
            }
        } catch (JWTException $e) {
            return response()->json(['error' => 'could_not_create_token'], 500);
        }

        $usuario = auth()->user();

        return response()->json(compact('token', 'usuario'));
    }
    public function getAuthenticatedUser()
    {
        try {
            if (!$user = JWTAuth::parseToken()->authenticate()) {
                    return response()->json(['user_not_found'], 404);
            }
            } catch (Tymon\JWTAuth\Exceptions\TokenExpiredException $e) {
                    return response()->json(['token_expired'], $e->getStatusCode());
            } catch (Tymon\JWTAuth\Exceptions\TokenInvalidException $e) {
                    return response()->json(['token_invalid'], $e->getStatusCode());
            } catch (Tymon\JWTAuth\Exceptions\JWTException $e) {
                    return response()->json(['token_absent'], $e->getStatusCode());
            }
            return response()->json(compact('user'));
    }

    public function index()
    {
        $usuarioAc = auth()->user();

        if ($usuarioAc->rol_id == 1){

            $usuario = User::where('state', 1)->with('pertenece_roles','pertecene_departamento')->get(['id','name','rol_id','departament_id', 'state', 'created_at']);

            return response()->json(compact('usuario'), 200);
        }

        elseif ($usuarioAc->rol_id == 2) {

            $usuario = User::where('state', 1)->where('departament_id', $usuarioAc->departament_id)->with('pertenece_roles')->get(['id','name','rol_id', 'state', 'created_at']);

            return response()->json(compact('usuario'), 200);

        }
    }

    // FUNCION QUE SOLO SE USA EN AUDITORIA (ROL AUDITOR)
    public function index_audit()
    {
        $usuario = User::get(['id','name', 'state', 'created_at', 'user_updated','user_deleted','user_restored']);

        return response()->json(compact('usuario'), 200);
    }

    // FUNCION QUE SOLO TRAE REGISTROS ELIMINADOS TEMPORALMENTE
    public function index_trashed()
    {
        $usuarioAc = auth()->user();

        if ($usuarioAc->rol_id == 1){

            $usuario = User::where('state', 0)->with('pertenece_roles','pertecene_departamento')->get(['id','name','rol_id','departament_id', 'state', 'deleted_at']);

            return response()->json(compact('usuario'), 200);
        }

        elseif ($usuarioAc->rol_id == 2){

            $usuario = User::where('state', 0)->where('departament_id', $usuarioAc->departament_id)->with('pertenece_roles')->get(['id','name','rol_id', 'state', 'deleted_at']);

            return response()->json(compact('usuario'), 200);
        }

    }

    // FUNCION QUE TRAE LOS USUARIOS LISTOS PARA PROYECTOS

    public function index_proyect_user() {

        $usuarioAc = auth()->user();

        $usuario = User::where('state', 1)->where('departament_id', $usuarioAc->departament_id)->get(['id','name']);

            return response()->json(compact('usuario'), 200);
    }

    public function show($id)
    {
        $usuario = User::with('pertenece_roles','pertecene_departamento')->find($id);

        return response()->json(compact('usuario'), 200);
    }

    public function show_audit($id)
    {
        $usuario = User::where('id', $id)->with('pertenece_roles','pertecene_departamento','tiene_proyectos','tiene_usuarios','tiene_documentos','usuario_creador','usuario_modificador','usuario_eliminador','usuario_restaurador')->get(['name','email','password','code','rol_id','departament_id','state','user_created','user_updated','user_deleted','user_restored','created_at', 'updated_at', 'deleted_at', 'restored_at']);

        return response()->json(compact('usuario'), 200);
    }
    // FUNCION PARA CREAR USUARIOS SIN ASIGNACION
    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|max:12|regex:/[a-z]/|regex:/[A-Z]/|regex:/[0-9]/|regex:/[@$!%*#?&]/',
            'code' => 'required|integer|digits:6',
            'departament_id' => 'required|integer',
        ]);

        $user = new User;
        $user->name = $request->get('name');
        $user->email = $request->get('email');
        $user->password = Hash::make($request->get('password'));
        $user->code = Hash::make($request->get('code'));
        $user->rol_id = 3;
        $user->departament_id = $request->get('departament_id');
        $user->state = 0;
        $user->save();

        $token = JWTAuth::fromUser($user);

        $departamento = Departamento::where('id', $user->departament_id)->first();

        $datos = new RegisterMail([$user->name, $user->email, $departamento, $user->created_at]);

        $gerente = User::where('rol_id', 2)->where('departament_id', $user->departament_id)->get('email');

        foreach ($gerente as $g) {
           Mail::to($g)->send($datos);
        }

        return response()->json(compact('user','token'),201);
    }

    // FUNCION PARA CREAR USUARIOS POR ALGUN ADMINISTRADOR
    public function store(Request $request)
        {
            $usuario = auth()->user();

                $validator = Validator::make($request->all(), [
                'name' => 'required|string|max:255',
                'email' => 'required|string|email|max:255|unique:users',
                'password' => 'required|string|min:8|max:12|regex:/[a-z]/|regex:/[A-Z]/|regex:/[0-9]/|regex:/[@$!%*#?&]/',
                'code' => 'required|integer|digits:6',
                'rol_id' => 'required|integer',
                'departament_id' => 'required|integer',
                'state' => 'required|integer',
            ]);

            if($validator->fails()){
                    return response()->json($validator->errors()->toJson(), 400);
            }

            $user = User::create([
                'name' => $request->get('name'),
                'email' => $request->get('email'),
                'password' => Hash::make($request->get('password')),
                'code' => Hash::make($request->get('code')),
                'rol_id' => $request->get('rol_id'),
                'departament_id' => $request->get('departament_id'),
                'state' => $request->get('state'),
                'user_created' => $usuario->id,
            ]);

            $token = JWTAuth::fromUser($user);

            return response()->json(compact('user','token'),201);
        }

        // FUNCION PARA CREAR EL USUARIO MAESTRO
        public function register_pro(Request $request)
        {
                $validator = Validator::make($request->all(), [
                'name' => 'required|string|max:255',
                'email' => 'required|string|email|max:255|unique:users',
                'password' => 'required|string|min:8|max:12|regex:/[a-z]/|regex:/[A-Z]/|regex:/[0-9]/|regex:/[@$!%*#?&]/',
                'code' => 'required|integer|digits:6',
            ]);

            if($validator->fails()){
                    return response()->json($validator->errors()->toJson(), 400);
            }

            $user = User::create([
                'name' => $request->get('name'),
                'email' => $request->get('email'),
                'password' => Hash::make($request->get('password')),
                'code' => Hash::make($request->get('code')),
                'rol_id' => 1,
                'departament_id' => 1,
                'state' => 1,
                'user_created' => 1,
            ]);

            $token = JWTAuth::fromUser($user);

            return response()->json(compact('user','token'),201);
        }

    public function update($id, Request $request)
    {
        $usuario = auth()->user();

        if ($request->get('email') !== null) {
            $request->validate(['email' => 'email|max:255|unique:users']);
        }
        if ($request->get('name') !== null) {
            $request->validate(['name' => 'string|max:255']);
        }
        if ($request->get('password') !== null) {
            $request->validate(['password' => 'string|min:8|max:12|regex:/[a-z]/|regex:/[A-Z]/|regex:/[0-9]/|regex:/[@$!%*#?&]/']);
        }
        if ($request->get('code') !== null) {
            $request->validate(['code' => 'integer|digits:6']);
        }
        if ($request->get('rol_id') !== null) {
            $request->validate(['rol_id' => 'integer']);
        }
        if ($request->get('departament_id') !== null) {
            $request->validate(['departament_id' => 'integer']);
        }
        if ($request->get('state') !== null) {
            $request->validate(['state' => 'integer']);
        }
        // $request->validate([
        //     'name' => 'string|max:255',
        //     'email' => 'email|max:255|unique:users',
        //     'password' => 'string|min:8|max:12|regex:/[a-z]/|regex:/[A-Z]/|regex:/[0-9]/|regex:/[@$!%*#?&]/',
        //     'code' => 'integer|digits:6',
        //     'rol_id' => 'integer',
        //     'departament_id' => 'integer',
        //     'state' => 'integer',
        // ]);

        $user = User::find($id);
        $user->name = $request->get('name') == null ? $user->name : $request->get('name');
        $user->email = $request->get('email') == null ? $user->email : $request->get('email');
        $user->password = $request->get('password') == null ? $user->password : Hash::make($request->get('password'));
        $user->code = $request->get('code') == null ? $user->code : Hash::make($request->get('code'));
        $user->rol_id = $request->get('rol_id') == null ? $user->rol_id : $request->get('rol_id');
        $user->departament_id = $request->get('departament_id') == null ? $user->departament_id : $request->get('departament_id');
        $user->state = $request->get('state') == null ? $user->state : $request->get('state');
        $user->user_updated = $usuario->id;
        $user->update();

        return response()->json(compact('user'), 200);

    }
    //FUNCION PARA ELIMINADO TEMPORAL
    public function soft_delete($id)
    {
        $usuario = auth()->user();

        $user = User::find($id);
        $user->user_deleted = $usuario->id;
        $user->deleted_at = date_create();
        $user->state = 0;
        $user->update();

        $datos = new DeletedMail([$user->name, $user->email, $user->restored_at, $usuario->name]);

        Mail::to($user->email)->send($datos);

        // Mail::to('dilsonjoan16@gmail.com')->send($datos);

        return response()->json(compact('user'), 200);
    }
    // FUNCION PARA RESTAURAR ELIMINADOS TEMPORALES A TRAVES DEL $ID
    public function restore($id)
    {
        $usuario = auth()->user();

        $user = User::find($id);
        $user->user_restored = $usuario->id;
        $user->restored_at = date_create();
        $user->state = 1;
        $user->update();

        $datos = new RestoredMail([$user->name, $user->email, $user->restored_at, $usuario->name]);

        // Mail::to($user->email)->send($datos);

        Mail::to('dilsonjoan16@gmail.com')->send($datos);

        return response()->json(compact('user'), 200);
    }
    // FUNCION PARA RESTAURAR ELIMINADOS TEMPORALES MASIVAMENTE
    public function restore_massive()
    {
        $usuario = auth()->user();

        $user = User::where('state', 0)->get();

        foreach ($user as $u) {
            $u->user_restored = $usuario->id;
            $u->restored_at = date_create();
            $u->state = 1;
            $u->update();
        }

        return response()->json(compact('Restauracion completa'), 200);
    }
    // FUNCION PARA ELIMINAR PERMANENTEMENTE ALGUN REGISTRO
    public function force_delete(Request $request,$id)
    {
        $usuario = auth()->user();

        if ($request->get('code') !== null) {
            if (Hash::check($request->get('code'), $usuario->code)) {

                $user = User::find($id);
                $user->delete();

            return response()->json(compact('Eliminacion completa'), 200);
            }else{

                return response()->json(compact('El codigo es incorrecto'), 400);
            }
        }

        if ($request->get('code') == null) {

        return response()->json(compact('Necesita ingresar el codigo'), 400);
        }
    }

    public function logout()
    {
        auth()->logout();

        return response()->json(['message' => 'Successfully logged out']);
    }
}
