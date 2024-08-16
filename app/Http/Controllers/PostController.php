<?php
namespace App\Http\Middleware; 
namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\File\File;

class PostController extends Controller
{
    //
   

    public function index(Request $request, $usuario)
    {
        $usuario = User::where('username', $usuario)->first();
        
       
        if (!$usuario) 
        {
            abort(404, 'Usuario no encontrado');
         
        }

         $posts = Post::where('user_id', $usuario->id)->paginate(5);
        
        return view('dashboard', ['user' => $usuario, 'posts' => $posts]);


    }


    public function create()
    {
        return view('post.create');
    }

    
public function store(Request $request)
{

    $request->validate(
        [
            'titulo' => 'required',
            'descripcion' => 'required',
            'imagen' => 'required'
        ]);


        

    $image = $request->file('imagen');
    $nombre = time() . "_" . $image->getClientOriginalName();

    $image->move('img/uploads', $nombre);


    // Post::create([

    //     'titulo' => $request->titulo,
    //     'descripcion' => $request->descripcion,
    //     'imagen' => $nombre,
    //     'user_id' => auth()->user()->id

    // ]);

    $request->user()->posts()->create(
        [
            'titulo' => $request->titulo,
            'descripcion' => $request->descripcion,
            'imagen' => $nombre,
            'user_id' => auth()->user()->id
        ]
    
        );

    return redirect()->route('posts.index');

    



   
}


public function show(User $user, Post $post)
{

    
    
    return view('post.show',
    [
        'post' => $post,
        'user' => $user
        
    ]);
}


public function destroy(Post $post)
{//authorize  sirve para ver si el que esta eliminando el post tiene permiso de hacerlo
    
   $this->authorize('delete', $post);
   $post->delete();
  
  
   return redirect()->route('posts.index', auth()->user()->username);
}
}
