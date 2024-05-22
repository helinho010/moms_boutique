<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Webpatser\Uuid\Uuid;

class Producto extends Model
{
    use HasFactory;
    protected $fillable = ['nombre', 'talla', 'precio'];

    public static function boot()
    {
        parent::boot();
        self::creating(function ($model) {
            $model->codigo_producto = (string) Uuid::generate();
        });
    }
    
    public function getRouteKeyName()
    {
        return 'uuid';
    }
}
