<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('home');
});

include('web/propina.php');
include('web/contrasena.php');
include('web/tareas.php');
include('web/notas.php');
include('web/gastos.php');
include('web/cronometro.php');
include('web/eventos.php');
