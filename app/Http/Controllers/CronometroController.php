<?php

namespace App\Http\Controllers;

use App\Models\Tiempo;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;

class CronometroController extends Controller
{
    public function index() {

        // Los ultimos 10 tiempos guardados, el mas reciente primero
        $tiempos = Tiempo::orderBy('created_at', 'desc')->take(10)->get();

        return view('cronometro/index', [ 'tiempos' => $tiempos ]);
    }

    public function store(Request $request) {

        Validator::make($request->all(),
        [
            'nombre' => 'required|max:80',
            'total_ms' => 'required|integer|min:1',
            'vueltas' => 'nullable|json'
        ],
        [
            'nombre.required' => 'Escriba un nombre para el tiempo',
            'nombre.max' => 'El nombre no puede ser mayor a :max caracteres',
            'total_ms.required' => 'Primero inicie el cronómetro',
            'total_ms.min' => 'Primero inicie el cronómetro',
            'vueltas.json' => 'Las vueltas no son válidas'
        ])->validate();

        try {

            $tiempo = new Tiempo();
            $tiempo->nombre = $request->nombre;
            $tiempo->total_ms = $request->total_ms;
            $tiempo->vueltas = json_decode($request->vueltas ?? '[]', true);
            $tiempo->save();

            Session::flash('message', 'Tiempo guardado con éxito');
            return redirect()->route('cronometro.index');

        } catch (Exception $ex) {

            Log::error($ex);
            Session::flash('message', 'Ha ocurrido un error');
            return redirect()->back();
        }
    }
}
