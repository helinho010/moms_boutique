<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;
use Webpatser\Uuid\Uuid;

use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, HasRoles;

    protected $fillable = ['name', 'username', 'email', 'password', 'usertype_id', 'uuid'];
    protected $hidden = ['password', 'remember_token'];
    protected $casts = ['email_verified_at' => 'datetime'];

    public static function boot()
    {
        parent::boot();
        self::creating(function ($model) {
            $model->uuid = (string) Uuid::generate();
        });
    }
    
    public function getRouteKeyName()
    {
        return 'uuid';
    }

    public function Usertype()
    {
        return $this->belongsTo(Usertype::class);
    }

    public static function getUsersRoles($id_usuario=1)
    {
        $roles = self::find($id_usuario)->getRoleNames();
        return $roles;
    }

    public static function buscar(string $cadenaBusqueda, int $paginate = 5)
    {
        return self::selectRaw('
                                users.id as id_usuario,
                                users.name as nombre_usuario,
                                users.username as usuario,
                                users.email as email_usuario,
                                users.estado as estado_usuario,
                                users.updated_at as updated_at_usuario
                            ')
                        ->where('users.estado',1)
                        ->where('users.name','like', '%'.$cadenaBusqueda.'%')
                        ->orWhere('users.username', 'like','%'.$cadenaBusqueda.'%')
                        ->orWhere('users.created_at', 'like','%'.$cadenaBusqueda.'%')
                        ->orWhere('users.updated_at', 'like','%'.$cadenaBusqueda.'%')
                        ->orderBy('users.updated_at','desc')
                        ->paginate($paginate);    
    }


}
