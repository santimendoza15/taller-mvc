<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Nota extends Model
{
    protected $table = 'notas';

    // Las categorias que se pueden elegir
    public const CATEGORIAS = ['Personal', 'Estudio', 'Trabajo', 'Otros'];
}
