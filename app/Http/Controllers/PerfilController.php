<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Auth\Events\Validated;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;

class PerfilController extends Controller
{
    //

    public function index(Request $request)
    {

        if($request->user !== auth()->user()->username)
        {
            return back();
        }

        return view('perfil.index');
        
    }


    public function store(Request $request)
    {

        $request->request->add(['username' => Str::slug($request->username)]);

        $request->validate([
            //not_in es una lista negra de inputs que no se podran procesar
            //en este caso esos usuarios no se pueden registrar si tienen ese nombre

            //otro dato: si tenemos mas de 3 reglas las podemos meter en un arreglo par no usar |
            'username' => ['required','unique:users,username,'.auth()->user()->id,'min:3','max:20','not_in:elonmusk,editar-perfil,index'],
            'email' => 'required|unique:users,email,'.auth()->user()-> id,
            'pass' => 'confirmed',
            'password' => 'required'
        ]);


        $user = Auth::user();

        // Verifica si la contraseña actual es correcta
        if (!Hash::check($request->password, $user->password)) {
            return back()->with('mensaje', 'Contraseña actual incorrecta');
        }


        if($request->imagen)
        {
            $image = $request->file('imagen');
            $nombre = time() ."_". $image->getClientOriginalName();
            $image->move('img/usuarios', $nombre);
        }



        //guardando cambios
        $usuario = User::find(auth()->user()->id);//aqui le estamos diciendo en cual usuario haremos la inyeccion
        $usuario->username = $request->username;
        $usuario->imagen = $nombre ?? auth()->user()->imagen ?? null;
          //aqui le decimos que si no se subio una imagen entonces use la que ya tiene y si no tiene una entonces lo deje null 
        $usuario->email = $request->email;
        $usuario->password = $request->pass ?? auth()->user()->password;
        $usuario->save(); //con usuario->save() estamos guardando los datos en la BDD

       

        return redirect()->route('posts.index', $usuario->username);





    }
}

