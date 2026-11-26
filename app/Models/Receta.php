<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Receta extends Model
{
    protected $table = 'recetas';

    public const TIPOS = ['Desayuno', 'Almuerzo', 'Cena', 'Postre', 'Bebida'];
}
