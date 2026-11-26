@extends('layouts.app')

@section('content')

    <h2>Editar Evento</h2>

    <div class="card mt-3">
        <div class="card-body">

            <form action="{{ route('eventos.update', $evento->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="mb-2">
                    <label class="form-label">Título</label>
                    <input type="text" name="titulo" class="form-control" value="{{ old('titulo', $evento->titulo) }}">
                </div>
                <div class="mb-2">
                    <label class="form-label">Descripción</label>
                    <input type="text" name="descripcion" class="form-control" value="{{ old('descripcion', $evento->descripcion) }}">
                </div>
                <div class="row g-2 mb-3">
                    <div class="col-md-6">
                        <label class="form-label">Fecha</label>
                        <input type="date" name="fecha" class="form-control" value="{{ old('fecha', $evento->fecha->format('Y-m-d')) }}">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Hora</label>
                        <input type="time" name="hora" class="form-control" value="{{ old('hora', $evento->hora ? substr($evento->hora, 0, 5) : '') }}">
                    </div>
                </div>

                <button type="submit" class="btn btn-primary">Guardar cambios</button>
                <a href="{{ route('eventos.index', ['mes' => $evento->fecha->format('Y-m')]) }}" class="btn btn-secondary">Volver</a>
            </form>

            <hr>

            <form action="{{ route('eventos.destroy', $evento->id) }}" method="POST"
                  onsubmit="return confirm('¿Eliminar este evento?')">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-danger">Eliminar evento</button>
            </form>

        </div>
    </div>

@endsection
