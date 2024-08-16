<?php

namespace App\Http\Controllers;

use App\Models\Comentario;
use App\Models\Post;
use App\Models\User;
use Illuminate\Http\Request;

class ComentarioController extends Controller
{
    //


    public function store(Request $request, User $user, Post $post)
    {
        
      

        //validando

       $request->validate(
            [

                'comentario' => 'required|max:100'

            ]);
        



        //almacenando resultado

        Comentario::create([

            'user_id' => auth()->user()->id,
            'post_id' => $post->id,
            'comentario' => $request->comentario
            

        ]);


        //imprimir un mensaje

        return back()->with('mensaje', 'Comentario Agregado Correctamente');
    }
}
