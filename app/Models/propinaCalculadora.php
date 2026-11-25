<?php

namespace App\Models;

/**
 * Modelo de la calculadora de propinas.
 * No usa base de datos: solo guarda los datos y hace los calculos.
 */
class PropinaCalculadora
{
    public $amount;
    public $percentage;

    public function __construct($amount, $percentage) {

        $this->amount = $amount;
        $this->percentage = $percentage;
    }

    // Cuanto es la propina: monto x porcentaje / 100
    public function tip() {

        return round($this->amount * $this->percentage / 100, 2);
    }

    // Lo que se paga en total: la cuenta mas la propina
    public function total() {

        return round($this->amount + $this->tip(), 2);
    }
}
