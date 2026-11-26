<?php

use App\Http\Controllers\EventoController;
use Illuminate\Support\Facades\Route;

Route::get('/eventos' , [EventoController::class, 'index'])->name('eventos.index');
Route::post('/eventos' , [EventoController::class, 'store'])->name('eventos.store');
Route::get('/eventos/{id}/editar' , [EventoController::class, 'edit'])->name('eventos.edit');
Route::put('/eventos/{id}' , [EventoController::class, 'update'])->name('eventos.update');
Route::delete('/eventos/{id}' , [EventoController::class, 'destroy'])->name('eventos.destroy');
