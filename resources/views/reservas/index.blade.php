@extends('layouts.app')

@use('App\Models\Reserva')

@section('content')

    <h2>Sistema de Reservas</h2>

    @if (session('message'))
        <div class="alert alert-success">{{ session('message') }}</div>
    @endif
    @if (session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    {{-- Elegir el dia --}}
    <form action="{{ route('reservas.index') }}" method="GET" class="d-flex gap-2 mt-3">
        <input type="date" name="fecha" class="form-control w-auto" value="{{ $fecha }}">
        <button type="submit" class="btn btn-secondary">Ver disponibilidad</button>
    </form>

    {{-- Disponibilidad del dia --}}
    <div class="card mt-3">
        <div class="card-body">
            <h5>Disponibilidad del {{ date('d/m/Y', strtotime($fecha)) }}</h5>

            <div class="d-flex flex-wrap gap-2">
                @foreach (Reserva::HORARIOS as $hora)
                    @if (in_array($hora, $ocupadas))
                        <span class="badge bg-danger fs-6">{{ $hora }} · Ocupado</span>
                    @else
                        <span class="badge bg-success fs-6">{{ $hora }} · Libre</span>
                    @endif
                @endforeach
            </div>
        </div>
    </div>

    {{-- Formulario para reservar --}}
    <div class="card mt-3">
        <div class="card-body">
            <h5>Reservar</h5>

            @if (empty($libres))
                <p class="text-muted">No quedan horarios libres este día.</p>
            @else
                <form action="{{ route('reservas.store') }}" method="POST" class="row g-2">
                    @csrf
                    <input type="hidden" name="fecha" value="{{ $fecha }}">

                    <div class="col-md-4">
                        <input type="text" name="cliente" class="form-control" placeholder="Tu nombre" value="{{ old('cliente') }}">
                    </div>
                    <div class="col-md-3">
                        <select name="servicio" class="form-select">
                            <option value="">Servicio...</option>
                            @foreach ($servicios as $servicio)
                                <option value="{{ $servicio }}" @selected(old('servicio') == $servicio)>{{ $servicio }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <select name="hora" class="form-select">
                            <option value="">Hora...</option>
                            @foreach ($libres as $hora)
                                <option value="{{ $hora }}">{{ $hora }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2 d-grid">
                        <button type="submit" class="btn btn-primary">Reservar</button>
                    </div>
                </form>
            @endif
        </div>
    </div>

    {{-- Reservas del dia --}}
    <div class="card mt-3">
        <div class="card-body">
            <h5>Reservas del día</h5>

            @if ($reservas->isEmpty())
                <p class="text-muted">No hay reservas este día.</p>
            @else
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Hora</th>
                            <th>Cliente</th>
                            <th>Servicio</th>
                            <th>Estado</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($reservas as $reserva)
                            <tr>
                                <td>{{ $reserva->hora }}</td>
                                <td>{{ $reserva->cliente }}</td>
                                <td>{{ $reserva->servicio }}</td>
                                <td>
                                    @if ($reserva->confirmada)
                                        <span class="badge bg-success">Confirmada</span>
                                    @else
                                        <span class="badge bg-warning text-dark">Pendiente</span>
                                    @endif
                                </td>
                                <td class="d-flex gap-2">
                                    @if (!$reserva->confirmada)
                                        <form action="{{ route('reservas.confirmar', $reserva->id) }}" method="POST">
                                            @csrf
                                            @method('PUT')
                                            <button type="submit" class="btn btn-sm btn-success">Confirmar</button>
                                        </form>
                                    @endif
                                    <form action="{{ route('reservas.destroy', $reserva->id) }}" method="POST"
                                          onsubmit="return confirm('¿Cancelar esta reserva?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger">Cancelar</button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @endif
        </div>
    </div>

@endsection
