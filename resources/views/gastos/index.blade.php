@extends('layouts.app')

@section('content')

    <h2>Gestor de Gastos</h2>

    @if (session('message'))
        <div class="alert alert-success">{{ session('message') }}</div>
    @endif

    <div class="card mt-3">
        <div class="card-body">
            <h5>Registrar gasto</h5>

            <form action="{{ route('gastos.store') }}" method="POST" class="row g-2">
                @csrf

                <div class="col-md-4">
                    <input type="text" name="descripcion" class="form-control" placeholder="Descripción"
                           value="{{ old('descripcion') }}">
                </div>
                <div class="col-md-2">
                    <input type="number" name="monto" class="form-control" placeholder="Monto"
                           value="{{ old('monto') }}">
                </div>
                <div class="col-md-2">
                    <select name="categoria" class="form-select">
                        <option value="">Categoría...</option>
                        @foreach ($categorias as $categoria)
                            <option value="{{ $categoria }}" @selected(old('categoria') == $categoria)>{{ $categoria }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <input type="date" name="fecha" class="form-control" value="{{ old('fecha', date('Y-m-d')) }}">
                </div>
                <div class="col-md-2 d-grid">
                    <button type="submit" class="btn btn-primary">Registrar</button>
                </div>
            </form>
        </div>
    </div>

    <div class="card mt-3">
        <div class="card-body">

            <form action="{{ route('gastos.index') }}" method="GET" class="d-flex gap-2 mb-3">
                <input type="month" name="mes" class="form-control w-auto" value="{{ $mes }}">
                <button type="submit" class="btn btn-secondary">Ver mes</button>
            </form>

            <h5>Resumen del mes</h5>

            <div class="row g-2 mb-3">
                <div class="col-md-3">
                    <div class="border rounded p-2 bg-light">
                        <small class="text-muted">Total</small>
                        <div class="fs-4 fw-bold">${{ number_format($total, 0, ',', '.') }}</div>
                    </div>
                </div>
                @foreach ($porCategoria as $categoria => $suma)
                    <div class="col-md-3">
                        <div class="border rounded p-2">
                            <small class="text-muted">{{ $categoria }}</small>
                            <div class="fs-5">${{ number_format($suma, 0, ',', '.') }}</div>
                        </div>
                    </div>
                @endforeach
            </div>

            @if ($gastos->isEmpty())
                <p class="text-muted text-center">No hay gastos en este mes.</p>
            @else
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Fecha</th>
                            <th>Descripción</th>
                            <th>Categoría</th>
                            <th>Monto</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($gastos as $gasto)
                            <tr>
                                <td>{{ $gasto->fecha->format('d/m/Y') }}</td>
                                <td>{{ $gasto->descripcion }}</td>
                                <td>{{ $gasto->categoria }}</td>
                                <td>${{ number_format($gasto->monto, 0, ',', '.') }}</td>
                                <td>
                                    <form action="{{ route('gastos.destroy', $gasto->id) }}" method="POST"
                                          onsubmit="return confirm('¿Eliminar este gasto?')">
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
