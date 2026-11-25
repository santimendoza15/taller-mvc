<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tiempo extends Model
{
    protected $table = 'tiempos';

    // Las vueltas se guardan como texto JSON y Laravel las convierte en lista
    protected $casts = [
        'vueltas' => 'array',
    ];

    // Convierte milisegundos al formato 00:00.00 (minutos:segundos.centesimas)
    public static function formatear($ms) {

        $minutos = floor($ms / 60000);
        $segundos = floor(($ms % 60000) / 1000);
        $centesimas = floor(($ms % 1000) / 10);

        return sprintf('%02d:%02d.%02d', $minutos, $segundos, $centesimas);
    }

    public function totalFormateado() {

        return self::formatear($this->total_ms);
    }
}
