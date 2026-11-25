<?php

use App\Http\Controllers\CronometroController;
use Illuminate\Support\Facades\Route;

Route::get('/cronometro' , [CronometroController::class, 'index'])->name('cronometro.index');
Route::post('/cronometro' , [CronometroController::class, 'store'])->name('cronometro.store');
