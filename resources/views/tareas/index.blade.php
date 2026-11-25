@extends('layouts.app')

@section('content')

    <h2>Lista de Tareas</h2>

    @if (session('message'))
        <div class="alert alert-success">{{ session('message') }}</div>
    @endif

    <div class="card mt-3">
        <div class="card-body">

            <form action="{{ route('tareas.store') }}" method="POST" class="d-flex gap-2">
                @csrf
                <input type="text" name="titulo" class="form-control" placeholder="¿Qué tienes que hacer?"
                       value="{{ old('titulo') }}">
                <button type="submit" class="btn btn-primary">Agregar</button>
            </form>

        </div>
    </div>

    <div class="card mt-3">
        <div class="card-body">

            @php
                $pendientes = $tareas->where('completada', false)->count();
            @endphp

            <p class="text-muted">{{ $pendientes }} pendiente(s) de {{ $tareas->count() }} tarea(s)</p>

            @if ($tareas->isEmpty())
                <p class="text-muted text-center">No hay tareas. ¡Agrega la primera!</p>
            @else
                <ul class="list-group">
                    @foreach ($tareas as $tarea)
                        <li class="list-group-item d-flex justify-content-between align-items-center">

                            <span class="{{ $tarea->completada ? 'text-decoration-line-through text-muted' : '' }}">
                                {{ $tarea->titulo }}
                            </span>

                            <div class="d-flex gap-2">
                                <form action="{{ route('tareas.toggle', $tarea->id) }}" method="POST">
                                    @csrf
                                    @method('PUT')
                                    <button type="submit" class="btn btn-sm {{ $tarea->completada ? 'btn-secondary' : 'btn-success' }}">
                                        {{ $tarea->completada ? 'Desmarcar' : 'Completar' }}
                                    </button>
                                </form>

                                <form action="{{ route('tareas.destroy', $tarea->id) }}" method="POST"
                                      onsubmit="return confirm('¿Eliminar esta tarea?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger">Eliminar</button>
                                </form>
                            </div>

                        </li>
                    @endforeach
                </ul>
            @endif

        </div>
    </div>

@endsection
