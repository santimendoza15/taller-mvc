<?php

use App\Http\Controllers\RecetaController;
use Illuminate\Support\Facades\Route;

Route::get('/recetas' , [RecetaController::class, 'index'])->name('recetas.index');
Route::post('/recetas' , [RecetaController::class, 'store'])->name('recetas.store');
Route::get('/recetas/{id}' , [RecetaController::class, 'show'])->name('recetas.show');
Route::delete('/recetas/{id}' , [RecetaController::class, 'destroy'])->name('recetas.destroy');
