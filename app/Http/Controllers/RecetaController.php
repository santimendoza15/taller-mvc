<?php

namespace App\Http\Controllers;

use App\Models\Receta;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;

class RecetaController extends Controller
{
    // Lista las recetas, con filtro por nombre, ingrediente y tipo de comida
    public function index(Request $request) {

        $consulta = Receta::orderBy('nombre');

        if ($request->nombre) {
            $consulta->where('nombre', 'like', '%' . $request->nombre . '%');
        }

        if ($request->ingrediente) {
            $consulta->where('ingredientes', 'like', '%' . $request->ingrediente . '%');
        }

        if ($request->tipo) {
            $consulta->where('tipo', $request->tipo);
        }

        return view('recetas/index', [
            'recetas' => $consulta->get(),
            'tipos' => Receta::TIPOS
        ]);
    }

    // Detalle de una receta (esta es la pagina que se comparte)
    public function show($id) {

        $receta = Receta::find($id);

        if (empty($receta)) {

            Session::flash('message', 'La receta no existe');
            return redirect()->route('recetas.index');
        }

        return view('recetas/show', [ 'receta' => $receta ]);
    }

    public function store(Request $request) {

        Validator::make($request->all(),
        [
            'nombre' => 'required|max:100',
            'tipo' => 'required|in:' . implode(',', Receta::TIPOS),
            'ingredientes' => 'required',
            'preparacion' => 'required'
        ],
        [
            'nombre.required' => 'El nombre es requerido',
            'nombre.max' => 'El nombre no puede ser mayor a :max caracteres',
            'tipo.required' => 'Elija el tipo de comida',
            'tipo.in' => 'El tipo de comida no es válido',
            'ingredientes.required' => 'Escriba los ingredientes',
            'preparacion.required' => 'Escriba la preparación'
        ])->validate();

        $receta = new Receta();
        $receta->nombre = $request->nombre;
        $receta->tipo = $request->tipo;
        $receta->ingredientes = $request->ingredientes;
        $receta->preparacion = $request->preparacion;
        $receta->save();

        Session::flash('message', 'Receta guardada');
        return redirect()->route('recetas.index');
    }

    public function destroy($id) {

        $receta = Receta::find($id);

        if ($receta) {
            $receta->delete();
            Session::flash('message', 'Receta eliminada');
        }

        return redirect()->route('recetas.index');
    }
}
