<?php

namespace App\Http\Controllers;

use App\Models\Evento;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;

class EventoController extends Controller
{
    public function index(Request $request) {

        // El mes que se muestra en el calendario (por defecto, el actual)
        $mes = Carbon::parse(($request->mes ?? date('Y-m')) . '-01');

        // Los eventos de ese mes, agrupados por dia: [ '2026-11-25' => [evento, evento], ... ]
        $eventosDelMes = Evento::whereYear('fecha', $mes->year)
                               ->whereMonth('fecha', $mes->month)
                               ->orderBy('hora')
                               ->get()
                               ->groupBy(function ($evento) {
                                   return $evento->fecha->format('Y-m-d');
                               });

        // Recordatorios: los eventos de hoy y de manana
        $recordatorios = Evento::whereBetween('fecha', [ date('Y-m-d'), date('Y-m-d', strtotime('+1 day')) ])
                               ->orderBy('fecha')
                               ->orderBy('hora')
                               ->get();

        return view('eventos/index', [
            'mes' => $mes,
            'eventosDelMes' => $eventosDelMes,
            'recordatorios' => $recordatorios
        ]);
    }

    public function store(Request $request) {

        $this->validar($request);

        $evento = new Evento();
        $this->llenar($evento, $request);

        Session::flash('message', 'Evento agregado');
        return redirect()->route('eventos.index', [ 'mes' => substr($request->fecha, 0, 7) ]);
    }

    public function edit($id) {

        $evento = Evento::find($id);

        if (empty($evento)) {

            Session::flash('message', 'El evento no existe');
            return redirect()->route('eventos.index');
        }

        return view('eventos/edit', [ 'evento' => $evento ]);
    }

    public function update(Request $request, $id) {

        $evento = Evento::find($id);

        if (empty($evento)) {

            Session::flash('message', 'El evento no existe');
            return redirect()->route('eventos.index');
        }

        $this->validar($request);
        $this->llenar($evento, $request);

        Session::flash('message', 'Evento actualizado');
        return redirect()->route('eventos.index', [ 'mes' => substr($request->fecha, 0, 7) ]);
    }

    public function destroy($id) {

        $evento = Evento::find($id);

        if ($evento) {
            $evento->delete();
            Session::flash('message', 'Evento eliminado');
        }

        return redirect()->route('eventos.index');
    }

    // Validacion compartida por crear y editar, para no repetirla
    private function validar(Request $request) {

        Validator::make($request->all(),
        [
            'titulo' => 'required|max:100',
            'descripcion' => 'nullable|max:255',
            'fecha' => 'required|date',
            'hora' => 'nullable|date_format:H:i'
        ],
        [
            'titulo.required' => 'El título es requerido',
            'titulo.max' => 'El título no puede ser mayor a :max caracteres',
            'descripcion.max' => 'La descripción no puede ser mayor a :max caracteres',
            'fecha.required' => 'La fecha es requerida',
            'fecha.date' => 'La fecha no es válida',
            'hora.date_format' => 'La hora no es válida'
        ])->validate();
    }

    // Asigna los campos del formulario al evento y lo guarda
    private function llenar(Evento $evento, Request $request) {

        $evento->titulo = $request->titulo;
        $evento->descripcion = $request->descripcion;
        $evento->fecha = $request->fecha;
        $evento->hora = $request->hora;
        $evento->save();
    }
}
