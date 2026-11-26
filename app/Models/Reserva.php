<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Reserva extends Model
{
    protected $table = 'reservas';

    protected $casts = [
        'fecha' => 'date',
        'confirmada' => 'boolean',
    ];

    // Horarios de atencion: de 8 a.m. a 5 p.m., una cita por hora
    public const HORARIOS = ['08:00', '09:00', '10:00', '11:00', '14:00', '15:00', '16:00', '17:00'];

    public const SERVICIOS = ['Corte de cabello', 'Manicure', 'Masaje', 'Consulta'];
}
