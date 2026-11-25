<?php

namespace App\Http\Controllers;

use App\Models\Nota;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;

class NotaController extends Controller
{
    // Lista las notas. Si escribieron algo en el buscador o eligieron categoria, filtra.
    public function index(Request $request) {

        $consulta = Nota::orderBy('created_at', 'desc');

        if ($request->buscar) {

            $consulta->where(function ($q) use ($request) {
                $q->where('titulo', 'like', '%' . $request->buscar . '%')
                  ->orWhere('contenido', 'like', '%' . $request->buscar . '%');
            });
        }

        if ($request->categoria) {

            $consulta->where('categoria', $request->categoria);
        }

        return view('notas/index', [
            'notas' => $consulta->get(),
            'categorias' => Nota::CATEGORIAS
        ]);
    }

    public function store(Request $request) {

        Validator::make($request->all(),
        [
            'titulo' => 'required|max:100',
            'contenido' => 'required',
            'categoria' => 'required|in:' . implode(',', Nota::CATEGORIAS)
        ],
        [
            'titulo.required' => 'El título es requerido',
            'titulo.max' => 'El título no puede ser mayor a :max caracteres',
            'contenido.required' => 'Escriba el contenido de la nota',
            'categoria.required' => 'Elija una categoría',
            'categoria.in' => 'La categoría no es válida'
        ])->validate();

        $nota = new Nota();
        $nota->titulo = $request->titulo;
        $nota->contenido = $request->contenido;
        $nota->categoria = $request->categoria;
        $nota->save();

        Session::flash('message', 'Nota guardada');
        return redirect()->route('notas.index');
    }

    public function destroy($id) {

        $nota = Nota::find($id);

        if ($nota) {
            $nota->delete();
            Session::flash('message', 'Nota eliminada');
        }

        return redirect()->route('notas.index');
    }
}
