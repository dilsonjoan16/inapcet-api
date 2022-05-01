<?php

namespace App\Http\Controllers;

    use App\Models\User;
    use Illuminate\Http\Request;
    use Illuminate\Support\Facades\Hash;
    use Illuminate\Support\Facades\Validator;
    use JWTAuth;
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
        $usuario = User::where('state', 1)->with('pertenece_roles','pertecene_departamento')->get('id','name','rol_id','departament_id', 'state');

        return response()->json(compact('usuario'), 200);
    }
    // FUNCION QUE SOLO TRAE REGISTROS ELIMINADOS TEMPORALMENTE
    public function index_trashed()
    {
        $usuario = User::where('state', 0)->with('pertenece_roles','pertecene_departamento')->get('id','name','rol_id','departament_id', 'state');

        return response()->json(compact('usuario'), 200);
    }

    public function show($id)
    {
        $usuario = User::with('pertenece_roles','pertecene_departamento')->find($id);

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
        ]);

        $user = new User;
        $user->name = $request->get('name');
        $user->email = $request->get('email');
        $user->password = Hash::make($request->get('password'));
        $user->code = Hash::make($request->get('code'));
        $user->rol_id = 2;
        $user->state = 0;
        $user->save();

        $token = JWTAuth::fromUser($user);

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
        public function master_store(Request $request)
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

        $request->validate([
            'name' => 'string|max:255',
            'email' => 'email|max:255|unique:users',
            'password' => 'string|min:8|max:12|regex:/[a-z]/|regex:/[A-Z]/|regex:/[0-9]/|regex:/[@$!%*#?&]/',
            'code' => 'integer|digits:6',
            'rol_id' => 'integer',
            'departament_id' => 'integer',
            'state' => 'integer',
        ]);

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
        $user->deleted_at = time();
        $user->state = 0;
        $user->update();

        return response()->json(compact('user'), 200);
    }
    // FUNCION PARA RESTAURAR ELIMINADOS TEMPORALES A TRAVES DEL $ID
    public function restore($id)
    {
        $usuario = auth()->user();

        $user = User::find($id);
        $user->user_restored = $usuario->id;
        $user->restored_at = time();
        $user->state = 1;
        $user->update();

        return response()->json(compact('user'), 200);
    }
    // FUNCION PARA RESTAURAR ELIMINADOS TEMPORALES MASIVAMENTE
    public function restore_massive()
    {
        $usuario = auth()->user();

        $user = User::where('state', 0)->get();

        foreach ($user as $u) {
            $u->user_restored = $usuario->id;
            $u->restored_at = time();
            $u->state = 1;
            $u->update();
        }

        return response()->json(compact('Restauracion completa'), 200);
    }
    // FUNCION PARA ELIMINAR PERMANENTEMENTE ALGUN REGISTRO
    public function force_delete(Request $request,$id)
    {
        $usuario = auth()->user();

        if ($request->get('code') !== null && $request->get('code') == $usuario->code) {
            $user = User::find($id);
            $user->delete();

        return response()->json(compact('Eliminacion completa'), 200);
        }

        if ($request->get('code') == null) {

        return response()->json(compact('Necesita ingresar el codigo'), 400);
        }

        if ($request->get('code') !== $usuario->code) {

        return response()->json(compact('El codigo es incorrecto'), 400);
        }
    }

    public function logout()
    {
        auth()->logout();

        return response()->json(['message' => 'Successfully logged out']);
    }
}
