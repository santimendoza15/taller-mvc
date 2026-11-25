@extends('layouts.app')

@section('content')

    <h2>Calculadora de Propinas</h2>

    <div class="card mt-3">
        <div class="card-body">

            <form action="{{ route('tips.calculate') }}" method="POST">
                @csrf

                <div class="mb-3">
                    <label class="form-label">Monto total de la cuenta</label>
                    <input type="number" name="amount" class="form-control" step="0.01"
                           value="{{ old('amount', $calculator->amount ?? '') }}">
                </div>

                <div class="mb-3">
                    <label class="form-label">Porcentaje de propina</label>
                    <select name="percentage" class="form-select">
                        @foreach ([5, 10, 15, 20, 25] as $option)
                            <option value="{{ $option }}"
                                @selected(old('percentage', $calculator->percentage ?? 10) == $option)>
                                {{ $option }}%
                            </option>
                        @endforeach
                    </select>
                </div>

                <button type="submit" class="btn btn-primary">Calcular</button>
            </form>

        </div>
    </div>

    @isset($calculator)
        <div class="card mt-3">
            <div class="card-body">
                <h4>Resultado</h4>
                <p>Cuenta: <b>${{ number_format($calculator->amount, 0, ',', '.') }}</b></p>
                <p>Propina ({{ $calculator->percentage }}%): <b>${{ number_format($calculator->tip(), 0, ',', '.') }}</b></p>
                <p class="fs-4">Total a pagar: <b>${{ number_format($calculator->total(), 0, ',', '.') }}</b></p>
            </div>
        </div>
    @endisset

@endsection
