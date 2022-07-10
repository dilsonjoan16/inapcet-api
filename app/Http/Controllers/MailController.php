<?php

namespace App\Http\Controllers;

use App\Mail\AnnounceMail;
use App\Mail\CodeMail;
use Illuminate\Http\Request;
use App\Mail\RecoveryMail;
use App\Models\User;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;

class MailController extends Controller
{
    public function passwordRecovery(Request $request) {

    $newpassword = Str::random(8);
    $hashed_random_password = Hash::make($newpassword);
    //dd($hashed_random_password);
    $email = $request->get('email');

    $request->validate([
        'email' => 'required|email',
    ]);

    $user = User::where('email', $email)->first();
    if (!$user)
        return response()->json('No se encontro algun usuario con el correo ingresado', 404);
    //dd($user);

        if ($request->get('code') !== null) {
            if (Hash::check($request->get('code'), $user->code)) {

                $user->password = $hashed_random_password;
                $user->update();

                $datos = new RecoveryMail([$newpassword,$user->name]);
                //dd($datos);

                set_time_limit(300);

                Mail::to($email)->send($datos);
                // Mail::to('dilsonjoan16@gmail.com')->send($datos);

            return response()->json("Mensaje enviado con exito", 200);

            }else{

                return response()->json('El codigo es incorrecto', 400);
            }
        }

        if ($request->get('code') == null) {

        return response()->json('Necesita ingresar el codigo', 400);
        }


    // $details = [
    //     'title' => 'Correo de recuperacion',
    //     'body' => 'Este es un ejemplo'
    // ];

    // Mail::to('dilsonjoan16@gmail.com')->send(new RecoveryMail($details));
    // return "Correo enviado";
    }

    public function codeRecovery(Request $request) {

        $newcode = random_int(100000, 999999);
        $hashed_random_code = Hash::make($newcode);

        $email = $request->get('email');

    $request->validate([
        'email' => 'required|email',
    ]);

    $user = User::where('email', $email)->first();
    if (!$user)
        return response()->json('No se encontro algun usuario con el correo ingresado', 404);
    //dd($user);

        if ($request->get('password') !== null) {
            if (Hash::check($request->get('password'), $user->password)) {

                $user->code = $hashed_random_code;
                $user->update();

                $datos = new CodeMail([$newcode,$user->name]);
                //dd($datos);

                set_time_limit(300);

                Mail::to($email)->send($datos);
                // Mail::to('dilsonjoan16@gmail.com')->send($datos);

            return response()->json("Mensaje enviado con exito", 200);

            }else{

                return response()->json('El password es incorrecto', 400);
            }
        }

        if ($request->get('password') == null) {

        return response()->json('Necesita ingresar el password', 400);
        }
    }

    public function anuncio(Request $request) {

        $user = auth()->user();

        $request->validate([
            "title" => "required",
            "body" => "required"
        ]);

        if ($request->get('code') !== null) {
            if (Hash::check($request->get('code'), $user->code)) {

                $datos = new AnnounceMail([$user->name, $request->get('title'), $request->get('body'), $user->email]);
                //dd($datos);
                set_time_limit(600);

                foreach ($request->get('usuarios') as $u) {

                    $person = User::find($u);

                    Mail::to($person->email)->send($datos);
                }
                // Mail::to('dilsonjoan16@gmail.com')->send($datos);

            return response()->json("Mensaje enviado con exito", 200);

            }else{

                return response()->json('El codigo es incorrecto', 400);
            }
        }

        if ($request->get('code') == null) {

        return response()->json('Necesita ingresar el codigo', 400);
        }
    }
}
