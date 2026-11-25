<?php

use App\Http\Controllers\ContrasenaController;
use Illuminate\Support\Facades\Route;

Route::get('/contrasena' , [ContrasenaController::class, 'index'])->name('contrasena.index');
Route::post('/contrasena' , [ContrasenaController::class, 'generar'])->name('contrasena.generar');
