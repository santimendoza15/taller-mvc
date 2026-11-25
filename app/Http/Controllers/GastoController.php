<?php

namespace App\Http\Controllers;

use App\Models\Gasto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;

class GastoController extends Controller
{
    public function index(Request $request) {

        // El mes que se quiere ver, en formato 2026-11. Si no eligen ninguno, el mes actual.
        $mes = $request->mes ?? date('Y-m');

        // Los gastos de ese mes: los que tienen una fecha que empieza con "2026-11"
        $gastos = Gasto::where('fecha', 'like', $mes . '%')
                       ->orderBy('fecha', 'desc')
                       ->get();

        // El total del mes
        $total = $gastos->sum('monto');

        // El total de cada categoria: agrupa los gastos por categoria y suma cada grupo
        $porCategoria = $gastos->groupBy('categoria')->map(function ($grupo) {
            return $grupo->sum('monto');
        });

        return view('gastos/index', [
            'gastos' => $gastos,
            'total' => $total,
            'porCategoria' => $porCategoria,
            'mes' => $mes,
            'categorias' => Gasto::CATEGORIAS
        ]);
    }

    public function store(Request $request) {

        Validator::make($request->all(),
        [
            'descripcion' => 'required|max:100',
            'monto' => 'required|numeric|min:1',
            'categoria' => 'required|in:' . implode(',', Gasto::CATEGORIAS),
            'fecha' => 'required|date'
        ],
        [
            'descripcion.required' => 'La descripción es requerida',
            'descripcion.max' => 'La descripción no puede ser mayor a :max caracteres',
            'monto.required' => 'El monto es requerido',
            'monto.numeric' => 'El monto debe ser un número',
            'monto.min' => 'El monto debe ser mayor a 0',
            'categoria.required' => 'Elija una categoría',
            'categoria.in' => 'La categoría no es válida',
            'fecha.required' => 'La fecha es requerida',
            'fecha.date' => 'La fecha no es válida'
        ])->validate();

        $gasto = new Gasto();
        $gasto->descripcion = $request->descripcion;
        $gasto->monto = $request->monto;
        $gasto->categoria = $request->categoria;
        $gasto->fecha = $request->fecha;
        $gasto->save();

        Session::flash('message', 'Gasto registrado');

        // Vuelve al mes del gasto que se acaba de registrar
        return redirect()->route('gastos.index', [ 'mes' => substr($request->fecha, 0, 7) ]);
    }

    public function destroy($id) {

        $gasto = Gasto::find($id);
        $mes = null;

        if ($gasto) {
            $mes = $gasto->fecha->format('Y-m');
            $gasto->delete();
            Session::flash('message', 'Gasto eliminado');
        }

        return redirect()->route('gastos.index', [ 'mes' => $mes ]);
    }
}
