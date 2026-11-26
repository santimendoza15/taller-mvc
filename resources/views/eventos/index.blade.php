@extends('layouts.app')

@section('content')

    <h2>Calendario de Eventos</h2>

    @if (session('message'))
        <div class="alert alert-success">{{ session('message') }}</div>
    @endif

    {{-- Recordatorios: eventos de hoy y de manana --}}
    @if ($recordatorios->isNotEmpty())
        <div class="alert alert-warning">
            <b>🔔 Recordatorios</b>
            <ul class="mb-0">
                @foreach ($recordatorios as $evento)
                    <li>
                        {{ $evento->fecha->isToday() ? 'Hoy' : 'Mañana' }}
                        {{ $evento->hora ? 'a las ' . substr($evento->hora, 0, 5) : '' }}:
                        <b>{{ $evento->titulo }}</b>
                    </li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="card mt-3">
        <div class="card-body">
            <h5>Nuevo evento</h5>

            <form action="{{ route('eventos.store') }}" method="POST" class="row g-2">
                @csrf
                <div class="col-md-3">
                    <input type="text" name="titulo" class="form-control" placeholder="Título" value="{{ old('titulo') }}">
                </div>
                <div class="col-md-3">
                    <input type="text" name="descripcion" class="form-control" placeholder="Descripción (opcional)" value="{{ old('descripcion') }}">
                </div>
                <div class="col-md-2">
                    <input type="date" name="fecha" class="form-control" value="{{ old('fecha', date('Y-m-d')) }}">
                </div>
                <div class="col-md-2">
                    <input type="time" name="hora" class="form-control" value="{{ old('hora') }}">
                </div>
                <div class="col-md-2 d-grid">
                    <button type="submit" class="btn btn-primary">Agregar</button>
                </div>
            </form>
        </div>
    </div>

    <div class="card mt-3">
        <div class="card-body">

            {{-- Navegacion entre meses --}}
            <div class="d-flex justify-content-between align-items-center mb-3">
                <a class="btn btn-outline-secondary" href="{{ route('eventos.index', ['mes' => $mes->copy()->subMonth()->format('Y-m')]) }}">&laquo; Anterior</a>
                <h4 class="m-0 text-capitalize">{{ $mes->locale('es')->translatedFormat('F Y') }}</h4>
                <a class="btn btn-outline-secondary" href="{{ route('eventos.index', ['mes' => $mes->copy()->addMonth()->format('Y-m')]) }}">Siguiente &raquo;</a>
            </div>

            @php
                // Cuantas casillas vacias van antes del dia 1 (lunes = 0 ... domingo = 6)
                $vacias = $mes->dayOfWeekIso - 1;
                $diasDelMes = $mes->daysInMonth;
            @endphp

            <table class="table table-bordered" style="table-layout: fixed;">
                <thead>
                    <tr class="text-center">
                        <th>Lun</th><th>Mar</th><th>Mié</th><th>Jue</th><th>Vie</th><th>Sáb</th><th>Dom</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        {{-- Casillas vacias antes del primer dia --}}
                        @for ($i = 0; $i < $vacias; $i++)
                            <td></td>
                        @endfor

                        @for ($dia = 1; $dia <= $diasDelMes; $dia++)
                            @php
                                $fecha = $mes->copy()->day($dia)->format('Y-m-d');
                            @endphp

                            <td style="height: 100px; vertical-align: top;" class="{{ $fecha == date('Y-m-d') ? 'table-info' : '' }}">
                                <b>{{ $dia }}</b>

                                @foreach ($eventosDelMes[$fecha] ?? [] as $evento)
                                    <div class="badge bg-primary d-block text-start text-truncate mt-1">
                                        <a href="{{ route('eventos.edit', $evento->id) }}" class="text-white text-decoration-none">
                                            {{ $evento->hora ? substr($evento->hora, 0, 5) : '' }} {{ $evento->titulo }}
                                        </a>
                                    </div>
                                @endforeach
                            </td>

                            {{-- Al llegar al domingo, cierra la fila y abre una nueva --}}
                            @if (($dia + $vacias) % 7 == 0 && $dia < $diasDelMes)
                                </tr><tr>
                            @endif
                        @endfor
                    </tr>
                </tbody>
            </table>

            <small class="text-muted">Haz clic en un evento para editarlo o eliminarlo.</small>
        </div>
    </div>

@endsection
