<?php

namespace App\Http\Controllers;

use App\Models\Reserva;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;

class ReservaController extends Controller
{
    public function index(Request $request) {

        // El dia que se quiere ver (por defecto, hoy)
        $fecha = $request->fecha ?? date('Y-m-d');

        // Las reservas de ese dia, ordenadas por hora
        $reservas = Reserva::whereDate('fecha', $fecha)->orderBy('hora')->get();

        // Las horas ocupadas ese dia, y las que quedan libres
        $ocupadas = $reservas->pluck('hora')->toArray();
        $libres = array_diff(Reserva::HORARIOS, $ocupadas);

        return view('reservas/index', [
            'fecha' => $fecha,
            'reservas' => $reservas,
            'ocupadas' => $ocupadas,
            'libres' => $libres,
            'servicios' => Reserva::SERVICIOS
        ]);
    }

    public function store(Request $request) {

        Validator::make($request->all(),
        [
            'cliente' => 'required|max:100',
            'servicio' => 'required|in:' . implode(',', Reserva::SERVICIOS),
            'fecha' => 'required|date|after_or_equal:today',
            'hora' => 'required|in:' . implode(',', Reserva::HORARIOS)
        ],
        [
            'cliente.required' => 'Escriba su nombre',
            'cliente.max' => 'El nombre no puede ser mayor a :max caracteres',
            'servicio.required' => 'Elija un servicio',
            'servicio.in' => 'El servicio no es válido',
            'fecha.required' => 'La fecha es requerida',
            'fecha.date' => 'La fecha no es válida',
            'fecha.after_or_equal' => 'No se puede reservar en una fecha pasada',
            'hora.required' => 'Elija una hora',
            'hora.in' => 'La hora no es válida'
        ])->validate();

        // Revisa que ese horario siga libre
        $ocupado = Reserva::whereDate('fecha', $request->fecha)->where('hora', $request->hora)->exists();

        if ($ocupado) {

            Session::flash('error', 'Ese horario ya está reservado. Elija otro.');
            return redirect()->route('reservas.index', [ 'fecha' => $request->fecha ]);
        }

        $reserva = new Reserva();
        $reserva->cliente = $request->cliente;
        $reserva->servicio = $request->servicio;
        $reserva->fecha = $request->fecha;
        $reserva->hora = $request->hora;
        $reserva->confirmada = false;
        $reserva->save();

        Session::flash('message', 'Reserva creada. Queda pendiente de confirmación.');
        return redirect()->route('reservas.index', [ 'fecha' => $request->fecha ]);
    }

    // Confirma la reserva
    public function confirmar($id) {

        $reserva = Reserva::find($id);

        if ($reserva) {
            $reserva->confirmada = true;
            $reserva->save();
            Session::flash('message', 'Reserva confirmada');
        }

        return redirect()->route('reservas.index', [ 'fecha' => $reserva?->fecha->format('Y-m-d') ]);
    }

    // Cancela la reserva y deja el horario libre otra vez
    public function destroy($id) {

        $reserva = Reserva::find($id);
        $fecha = null;

        if ($reserva) {
            $fecha = $reserva->fecha->format('Y-m-d');
            $reserva->delete();
            Session::flash('message', 'Reserva cancelada');
        }

        return redirect()->route('reservas.index', [ 'fecha' => $fecha ]);
    }
}
