<?php

use App\Http\Controllers\NotaController;
use Illuminate\Support\Facades\Route;

Route::get('/notas' , [NotaController::class, 'index'])->name('notas.index');
Route::post('/notas' , [NotaController::class, 'store'])->name('notas.store');
Route::delete('/notas/{id}' , [NotaController::class, 'destroy'])->name('notas.destroy');
