<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\loginRequest;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;


class loginController extends Controller
{
    public function index():View|RedirectResponse
    {   

        if(Auth::check()){
            return redirect()->route('panel');
        }
        return view('auth.login');

    }
    public function login(loginRequest $request): RedirectResponse
    {

        // dd($request);
        //validar credenciales
        if(!Auth::validate($request->only('email','password'))){
            return redirect()->to('login')->withErrors('Credenciales incorrectas');
        }

        //crear una sesion
        $user = Auth::getProvider()->retrieveByCredentials($request->only('email','password'));
        Auth::login($user);

        return redirect()->route('panel')->with('login', 'Bienvenido '.$user->name);

    }
}
