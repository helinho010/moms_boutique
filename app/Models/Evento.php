<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Evento extends Model
{
    use HasFactory;
    protected $fillable = ['nombre', 'fecha_evento'];
    protected $casts = [
        'fecha_evento' => 'datetime:d/m/Y',
    ];

}
