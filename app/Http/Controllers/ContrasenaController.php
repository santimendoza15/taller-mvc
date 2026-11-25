<?php

namespace App\Http\Controllers;

use App\Models\GeneradorContrasena;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ContrasenaController extends Controller
{
    public function index() {

        return view('contrasena/index');
    }

    public function generar(Request $request) {

        Validator::make($request->all(),
        [
            'longitud' => 'required|integer|min:4|max:64',
            'tipos' => 'required|array|min:1'
        ],
        [
            'longitud.required' => 'La longitud es requerida',
            'longitud.integer' => 'La longitud debe ser un número entero',
            'longitud.min' => 'La longitud mínima es :min caracteres',
            'longitud.max' => 'La longitud máxima es :max caracteres',
            'tipos.required' => 'Debe marcar al menos un tipo de caracter',
            'tipos.min' => 'Debe marcar al menos un tipo de caracter'
        ])->validate();

        $tipos = $request->tipos;

        $generador = new GeneradorContrasena(
            $request->longitud,
            in_array('mayusculas', $tipos),
            in_array('minusculas', $tipos),
            in_array('numeros', $tipos),
            in_array('simbolos', $tipos)
        );

        return view('contrasena/index', [
            'generador' => $generador,
            'contrasena' => $generador->generar()
        ]);
    }
}
