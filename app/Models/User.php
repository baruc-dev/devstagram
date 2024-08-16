<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'username'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }



    public function posts()
    {
        return $this->hasMany(Post::class);
    }


    public function likes()
    {
        return $this->hasMany(Like::class);
    }

    //en este metodo usamos User porque en la tabla de followers le decimos que followers esta relacionado a usuarios
    

    public function followers()
    { //le damos el nombre de la tabla para que se relacione y tambien las columas donde metera datos
        return $this->belongsToMany(User::class,'followers','user_id','follower_id');
    }


    public function followings()
    { //le damos el nombre de la tabla para que se relacione y tambien las columas donde metera datos
        return $this->belongsToMany(User::class,'followers','follower_id','user_id');
    }


    //comprobar si un usuario ya sigue a otro
    public function siguiendo(User $user)
    {
        return $this->followers->contains($user->id);
    }
}
