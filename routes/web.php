<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('home');
});

include('web/propina.php');
include('web/contrasena.php');
