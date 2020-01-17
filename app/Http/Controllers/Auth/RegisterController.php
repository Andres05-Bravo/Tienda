<?php

namespace App\Http\Controllers\Auth;

use App\User;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Http\Request;
use Session;
use Redirect;


class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = '/administrador';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    public function vistaRegistro(){
        return view("inicio.registro");
    }

    public function registroPost(Request $request){
        $rules = [
            'nombre' => 'required',
            'email' => 'required|unique:users',
            'password' => 'required',
        ];
        $messages = [
            'email.required' => 'Debes enviar un correo electronico.',
            'email.unique' => 'Este correo ya existe en nuestro sistema.',
            'password.required' =>'Debes enviar tu contraseña.',
        ];
        $this->validate($request,$rules,$messages);

        $newUser = new User;
        $newUser->name = $request->nombre;
        $newUser->email = $request->email;
        $newUser->password = Hash::make($request->password);

        if($newUser->save()){
            Session::flash('message_success', 'Registro exitoso, por favor inicia sesión con tus credenciales');
            return redirect()->route('login');
        }else{
            Session::flash('message_error', 'Hubo un error al registrate, por favor intente mas tarde.');
            return back();
        }
    }
}
