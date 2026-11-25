@extends('layouts.app')

@section('content')

    <h2>Generador de Contraseñas</h2>

    <div class="card mt-3">
        <div class="card-body">

            <form action="{{ route('contrasena.generar') }}" method="POST">
                @csrf

                <div class="mb-3">
                    <label class="form-label">Longitud (entre 4 y 64)</label>
                    <input type="number" name="longitud" class="form-control" min="4" max="64"
                           value="{{ old('longitud', $generador->longitud ?? 12) }}">
                </div>

                <label class="form-label">Tipos de caracteres</label>

                @php
                    $opciones = [
                        'mayusculas' => 'Mayúsculas (A-Z)',
                        'minusculas' => 'Minúsculas (a-z)',
                        'numeros' => 'Números (0-9)',
                        'simbolos' => 'Símbolos (!@#$...)'
                    ];
                    $marcados = old('tipos', request('tipos', ['mayusculas', 'minusculas', 'numeros']));
                @endphp

                @foreach ($opciones as $valor => $texto)
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="tipos[]" value="{{ $valor }}"
                               id="{{ $valor }}" @checked(in_array($valor, $marcados))>
                        <label class="form-check-label" for="{{ $valor }}">{{ $texto }}</label>
                    </div>
                @endforeach

                <button type="submit" class="btn btn-primary mt-3">Generar</button>
            </form>

        </div>
    </div>

    @isset($contrasena)
        <div class="card mt-3">
            <div class="card-body">
                <h4>Tu contraseña</h4>
                <p class="fs-3 font-monospace">{{ $contrasena }}</p>
                <small class="text-muted">{{ strlen($contrasena) }} caracteres</small>
            </div>
        </div>
    @endisset

@endsection
