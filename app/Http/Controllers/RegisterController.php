<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class RegisterController extends Controller
{
    public function index()
    {
        return view('auth.register');
    }

    public function store(Request $request)
    {
        //($request->get('email'));
        $validated = $request->validate(
        [
            'name' => 'required|min:5',
            'username' => 'required|unique:users|min:3|max:20',
            'email' => 'required|unique:users|email|max:60',
            'password' => 'required|min:8|confirmed'


        ]);


        User::create([

            'name' => $request->name,
            //slug lo convierte a string ideal para una URL, str to slug = Str::slug
            //elimina acentos, hace minusculas, elimina espacios
            'username' => Str::slug($request->username),
            'email' => $request->email,
            'password' => $request->password


        ]);


        auth()->attempt([

            'email' => $request->email,
            'password' => $request->password
        ]);

        return redirect()->route('posts.index');


        
    }
}

