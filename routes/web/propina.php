<?php

use App\Http\Controllers\PropinaCalculadoraController;
use Illuminate\Support\Facades\Route;

Route::get('/tips' , [PropinaCalculadoraController::class, 'index'])->name('tips.index');
Route::post('/tips' , [PropinaCalculadoraController::class, 'calculate'])->name('tips.calculate');
