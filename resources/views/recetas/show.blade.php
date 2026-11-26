@extends('layouts.app')

@section('content')

    <h2>Recetas</h2>

    @if (session('message'))
        <div class="alert alert-success">{{ session('message') }}</div>
    @endif

    <div class="card mt-3">
        <div class="card-body">
            <h5>Nueva receta</h5>

            <form action="{{ route('recetas.store') }}" method="POST">
                @csrf
                <div class="row g-2">
                    <div class="col-md-8">
                        <input type="text" name="nombre" class="form-control" placeholder="Nombre de la receta" value="{{ old('nombre') }}">
                    </div>
                    <div class="col-md-4">
                        <select name="tipo" class="form-select">
                            <option value="">Tipo de comida...</option>
                            @foreach ($tipos as $tipo)
                                <option value="{{ $tipo }}" @selected(old('tipo') == $tipo)>{{ $tipo }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-6">
                        <textarea name="ingredientes" class="form-control" rows="4" placeholder="Ingredientes (uno por línea)">{{ old('ingredientes') }}</textarea>
                    </div>
                    <div class="col-md-6">
                        <textarea name="preparacion" class="form-control" rows="4" placeholder="Preparación">{{ old('preparacion') }}</textarea>
                    </div>
                </div>
                <button type="submit" class="btn btn-primary mt-2">Guardar receta</button>
            </form>
        </div>
    </div>

    <div class="card mt-3">
        <div class="card-body">

            <form action="{{ route('recetas.index') }}" method="GET" class="row g-2 mb-3">
                <div class="col-md-4">
                    <input type="text" name="nombre" class="form-control" placeholder="Buscar por nombre" value="{{ request('nombre') }}">
                </div>
                <div class="col-md-3">
                    <input type="text" name="ingrediente" class="form-control" placeholder="Ingrediente" value="{{ request('ingrediente') }}">
                </div>
                <div class="col-md-3">
                    <select name="tipo" class="form-select">
                        <option value="">Todos los tipos</option>
                        @foreach ($tipos as $tipo)
                            <option value="{{ $tipo }}" @selected(request('tipo') == $tipo)>{{ $tipo }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2 d-grid">
                    <button type="submit" class="btn btn-secondary">Filtrar</button>
                </div>
            </form>

            @if ($recetas->isEmpty())
                <p class="text-muted text-center">No se encontraron recetas.</p>
            @else
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Nombre</th>
                            <th>Tipo</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($recetas as $receta)
                            <tr>
                                <td>{{ $receta->nombre }}</td>
                                <td>{{ $receta->tipo }}</td>
                                <td class="d-flex gap-2">
                                    <a href="{{ route('recetas.show', $receta->id) }}" class="btn btn-sm btn-primary">Ver</a>
                                    <form action="{{ route('recetas.destroy', $receta->id) }}" method="POST"
                                          onsubmit="return confirm('¿Eliminar esta receta?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger">Eliminar</button>
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
