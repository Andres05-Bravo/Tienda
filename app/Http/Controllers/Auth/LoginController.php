<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Session;
use Redirect;
use App\User;
use Auth;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
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
        $this->middleware('guest')->except('getLogout');
    }

    public function vistaLogin(){
        return view('inicio.login');
    }

    public function loginPost(Request $request){
        $rules = [
            'email' => 'required',
            'password' => 'required',
        ];
        $messages = [
            'email.required' => 'Debes enviar un correo electronico.',
            'password.required' =>'Debes enviar tu contraseña.',
        ];
        $this->validate($request,$rules,$messages);

        $credentials = $request->only('email', 'password');

        $users = User::Where('email', $request->email)->first();

        if (!empty($users)){
            if (Auth::attempt($credentials)) {
                return redirect()->route('tiendaIndex');
            }else{
                Session::flash('message_error', 'Usuario o Contraseña incorrecta');
                return redirect()->route('login');
            }
        }else{
            Session::flash('message_error', 'Este usuario no existe');
            return redirect()->route('login');
        }
    }

    protected function getLogout()
    {
        Auth::logout();
        Session::flush();
        return Redirect::to('/');
    }
}
