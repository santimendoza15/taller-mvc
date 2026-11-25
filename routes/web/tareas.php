<?php

use App\Http\Controllers\TareaController;
use Illuminate\Support\Facades\Route;

Route::get('/tareas' , [TareaController::class, 'index'])->name('tareas.index');
Route::post('/tareas' , [TareaController::class, 'store'])->name('tareas.store');
Route::put('/tareas/{id}/toggle' , [TareaController::class, 'toggle'])->name('tareas.toggle');
Route::delete('/tareas/{id}' , [TareaController::class, 'destroy'])->name('tareas.destroy');
