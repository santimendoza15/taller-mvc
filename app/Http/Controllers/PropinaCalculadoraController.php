<?php

namespace App\Http\Controllers;

use App\Models\PropinaCalculadora;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PropinaCalculadoraController extends Controller
{
    // Muestra el formulario vacio
    public function index() {

        return view('propina/index');
    }

    // Recibe el formulario, calcula y vuelve a mostrar la pagina con el resultado
    public function calculate(Request $request) {

        Validator::make($request->all(),
        [
            'amount' => 'required|numeric|min:1',
            'percentage' => 'required|in:5,10,15,20,25'
        ],
        [
            'amount.required' => 'El monto de la cuenta es requerido',
            'amount.numeric' => 'El monto debe ser un número',
            'amount.min' => 'El monto debe ser mayor a 0',
            'percentage.required' => 'Debe elegir un porcentaje',
            'percentage.in' => 'El porcentaje no es válido'
        ])->validate();

        $calculator = new PropinaCalculadora($request->amount, $request->percentage);

        return view('propina/index', [ 'calculator' => $calculator ]);
    }
}
