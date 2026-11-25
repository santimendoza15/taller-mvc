<?php

namespace App\Models;

/**
 * Modelo del generador de contraseñas.
 * No usa base de datos: guarda las opciones y arma la contraseña.
 */
class GeneradorContrasena
{
    public $longitud;
    public $mayusculas;
    public $minusculas;
    public $numeros;
    public $simbolos;

    public function __construct($longitud, $mayusculas, $minusculas, $numeros, $simbolos) {

        $this->longitud = $longitud;
        $this->mayusculas = $mayusculas;
        $this->minusculas = $minusculas;
        $this->numeros = $numeros;
        $this->simbolos = $simbolos;
    }

    public function generar() {

        // Los grupos de caracteres que el usuario marco
        $grupos = [];

        if ($this->mayusculas) { $grupos[] = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ'; }
        if ($this->minusculas) { $grupos[] = 'abcdefghijklmnopqrstuvwxyz'; }
        if ($this->numeros)    { $grupos[] = '0123456789'; }
        if ($this->simbolos)   { $grupos[] = '!@#$%&*?-_+='; }

        $contrasena = '';

        // Primero, un caracter de cada grupo marcado, para que no falte ninguno
        foreach ($grupos as $grupo) {

            $contrasena .= $grupo[random_int(0, strlen($grupo) - 1)];
        }

        // Despues, el resto de caracteres sacados de todos los grupos juntos
        $todos = implode('', $grupos);

        while (strlen($contrasena) < $this->longitud) {

            $contrasena .= $todos[random_int(0, strlen($todos) - 1)];
        }

        // Se revuelve para que los primeros caracteres no sigan siempre el mismo orden
        return str_shuffle($contrasena);
    }
}
