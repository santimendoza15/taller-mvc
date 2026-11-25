<?php

use App\Http\Controllers\GastoController;
use Illuminate\Support\Facades\Route;

Route::get('/gastos' , [GastoController::class, 'index'])->name('gastos.index');
Route::post('/gastos' , [GastoController::class, 'store'])->name('gastos.store');
Route::delete('/gastos/{id}' , [GastoController::class, 'destroy'])->name('gastos.destroy');
