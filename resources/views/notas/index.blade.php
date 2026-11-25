@extends('layouts.app')

@section('content')

    <h2>Gestor de Notas</h2>

    @if (session('message'))
        <div class="alert alert-success">{{ session('message') }}</div>
    @endif

    <div class="card mt-3">
        <div class="card-body">
            <h5>Nueva nota</h5>

            <form action="{{ route('notas.store') }}" method="POST">
                @csrf

                <div class="row g-2">
                    <div class="col-md-8">
                        <input type="text" name="titulo" class="form-control" placeholder="Título"
                               value="{{ old('titulo') }}">
                    </div>
                    <div class="col-md-4">
                        <select name="categoria" class="form-select">
                            <option value="">Categoría...</option>
                            @foreach ($categorias as $categoria)
                                <option value="{{ $categoria }}" @selected(old('categoria') == $categoria)>{{ $categoria }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-12">
                        <textarea name="contenido" class="form-control" rows="3" placeholder="Escribe tu nota...">{{ old('contenido') }}</textarea>
                    </div>
                </div>

                <button type="submit" class="btn btn-primary mt-2">Guardar</button>
            </form>
        </div>
    </div>

    <div class="card mt-3">
        <div class="card-body">

            <form action="{{ route('notas.index') }}" method="GET" class="row g-2 mb-3">
                <div class="col-md-6">
                    <input type="text" name="buscar" class="form-control" placeholder="Buscar..."
                           value="{{ request('buscar') }}">
                </div>
                <div class="col-md-4">
                    <select name="categoria" class="form-select">
                        <option value="">Todas las categorías</option>
                        @foreach ($categorias as $categoria)
                            <option value="{{ $categoria }}" @selected(request('categoria') == $categoria)>{{ $categoria }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2 d-grid">
                    <button type="submit" class="btn btn-secondary">Buscar</button>
                </div>
            </form>

            @if ($notas->isEmpty())
                <p class="text-muted text-center">No hay notas.</p>
            @endif

            <div class="row g-3">
                @foreach ($notas as $nota)
                    <div class="col-md-4">
                        <div class="card h-100">
                            <div class="card-body">
                                <span class="badge bg-info text-dark">{{ $nota->categoria }}</span>
                                <h5 class="mt-2">{{ $nota->titulo }}</h5>
                                <p>{{ $nota->contenido }}</p>
                                <small class="text-muted">{{ $nota->created_at->format('d/m/Y') }}</small>
                            </div>
                            <div class="card-footer">
                                <form action="{{ route('notas.destroy', $nota->id) }}" method="POST"
                                      onsubmit="return confirm('¿Eliminar esta nota?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger">Eliminar</button>
                                </form>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

        </div>
    </div>

@endsection
