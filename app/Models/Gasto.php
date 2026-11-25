<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Gasto extends Model
{
    protected $table = 'gastos';

    protected $casts = [
        'fecha' => 'date',
    ];

    // Las categorias que se pueden elegir
    public const CATEGORIAS = ['Comida', 'Transporte', 'Servicios', 'Entretenimiento', 'Otros'];
}
