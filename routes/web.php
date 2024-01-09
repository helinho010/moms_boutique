<?php

use App\Http\Controllers\CategoriaController;
use Illuminate\Support\Facades\Route;

require(base_path('routes/route-list/route-auth.php'));

// Categoria
Route::get('/categoria', [CategoriaController::class,'index'])->name('home_categoria');
Route::post('/categoria', [CategoriaController::class,'buscar'])->name('buscar_categoria');
Route::post('/nueva_categoria',[CategoriaController::class,'store'])->name('nueva_categoria');

Route::get('/', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

