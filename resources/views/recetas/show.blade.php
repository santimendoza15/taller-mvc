@extends('layouts.app')

@section('content')

    <a href="{{ route('recetas.index') }}">&laquo; Volver a recetas</a>

    <div class="card mt-3">
        <div class="card-body">

            <span class="badge bg-success">{{ $receta->tipo }}</span>
            <h2 class="mt-2">{{ $receta->nombre }}</h2>

            <h5 class="mt-3">Ingredientes</h5>
            <p style="white-space: pre-line;">{{ $receta->ingredientes }}</p>

            <h5>Preparación</h5>
            <p style="white-space: pre-line;">{{ $receta->preparacion }}</p>

            <hr>

            <h5>Compartir</h5>
            <a class="btn btn-success" target="_blank"
               href="https://wa.me/?text={{ urlencode('Mira esta receta: ' . $receta->nombre . ' ' . url()->current()) }}">
                Compartir por WhatsApp
            </a>
            <input type="text" class="form-control mt-2" value="{{ url()->current() }}" readonly onclick="this.select()">
            <small class="text-muted">También puedes copiar este enlace.</small>

        </div>
    </div>

@endsection
