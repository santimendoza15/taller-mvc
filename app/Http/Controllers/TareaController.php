<?php

namespace App\Http\Controllers;

use App\Models\Tarea;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;

class TareaController extends Controller
{
    // Lista todas las tareas: primero las pendientes, despues las completadas
    public function index() {

        $tareas = Tarea::orderBy('completada')->orderBy('created_at', 'desc')->get();

        return view('tareas/index', [ 'tareas' => $tareas ]);
    }

    // Agrega una tarea nueva
    public function store(Request $request) {

        Validator::make($request->all(),
        [
            'titulo' => 'required|max:150'
        ],
        [
            'titulo.required' => 'Escriba la tarea',
            'titulo.max' => 'La tarea no puede ser mayor a :max caracteres'
        ])->validate();

        $tarea = new Tarea();
        $tarea->titulo = $request->titulo;
        $tarea->completada = false;
        $tarea->save();

        Session::flash('message', 'Tarea agregada');
        return redirect()->route('tareas.index');
    }

    // Marca la tarea como completada, o la vuelve a dejar pendiente
    public function toggle($id) {

        $tarea = Tarea::find($id);

        if (empty($tarea)) {

            Session::flash('message', 'La tarea no existe');
            return redirect()->route('tareas.index');
        }

        $tarea->completada = !$tarea->completada;
        $tarea->save();

        return redirect()->route('tareas.index');
    }

    // Elimina la tarea
    public function destroy($id) {

        $tarea = Tarea::find($id);

        if (empty($tarea)) {

            Session::flash('message', 'La tarea no existe');
            return redirect()->route('tareas.index');
        }

        $tarea->delete();

        Session::flash('message', 'Tarea eliminada');
        return redirect()->route('tareas.index');
    }
}
