<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Str;



class LoginController extends Controller
{
    //

    public function index()
    {
        return view('auth.login');
    }


    public function store(Request $request)
    {

      

        $this->validate($request, [

            'email' => 'required|email',
            'password' => 'required'
        ]);



        if ($request->remember == null) 
        {
            $credentials = $request->only('email', 'password');
            if (auth()->attempt($credentials)) 
            {
                return redirect()->route('posts.index');
            } else 
            {
                return redirect()->back()->with('mensaje', 'Credenciales incorrectas');
            }
        } else 
        {
             
            if (auth()->attempt($request->only('email', 'password'),$request->remember)) 
            {
                return redirect()->route('posts.index');
            } else 
            {
                return redirect()->back()->with('mensaje', 'Credenciales incorrectas');
            }   
        }


        
    //     if($request->remember != null)
    //     {
    //         auth()->attempt($request->only('email', 'password','remember'));
    //     }
 

    //    if(!auth()->attempt($request->only('email', 'password')))
    //    {
    //     return back()->with('mensaje', 'Credenciales Incorrectas');
    //    }


       
      
       //return redirect()->route('posts.index');
       
       
    }



   
}
