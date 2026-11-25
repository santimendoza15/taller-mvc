@extends('layouts.app')

@section('content')

    <h2>Cronómetro</h2>

    @if (session('message'))
        <div class="alert alert-success">{{ session('message') }}</div>
    @endif

    <div class="card mt-3">
        <div class="card-body text-center">

            <div id="pantalla" class="display-3 font-monospace">00:00.00</div>

            <div class="mt-3">
                <button type="button" id="btnIniciar" class="btn btn-success">Iniciar</button>
                <button type="button" id="btnPausar" class="btn btn-warning" disabled>Pausar</button>
                <button type="button" id="btnVuelta" class="btn btn-primary" disabled>Vuelta</button>
                <button type="button" id="btnReiniciar" class="btn btn-secondary">Reiniciar</button>
            </div>

            <ol id="listaVueltas" class="list-group list-group-numbered mt-3 text-start"></ol>

            <form action="{{ route('cronometro.store') }}" method="POST" class="row g-2 justify-content-center mt-3">
                @csrf
                <input type="hidden" name="total_ms" id="totalMs">
                <input type="hidden" name="vueltas" id="vueltas">

                <div class="col-md-5">
                    <input type="text" name="nombre" class="form-control" placeholder="Nombre (ej: Carrera 100m)"
                           value="{{ old('nombre') }}">
                </div>
                <div class="col-md-3 d-grid">
                    <button type="submit" class="btn btn-dark">Guardar tiempo</button>
                </div>
            </form>

        </div>
    </div>

    <div class="card mt-3">
        <div class="card-body">
            <h4>Tiempos guardados</h4>

            @if ($tiempos->isEmpty())
                <p class="text-muted">Todavía no hay tiempos guardados.</p>
            @else
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Nombre</th>
                            <th>Tiempo total</th>
                            <th>Vueltas</th>
                            <th>Fecha</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($tiempos as $tiempo)
                            <tr>
                                <td>{{ $tiempo->nombre }}</td>
                                <td class="font-monospace">{{ $tiempo->totalFormateado() }}</td>
                                <td>
                                    @foreach ($tiempo->vueltas ?? [] as $i => $vuelta)
                                        <span class="badge bg-secondary">V{{ $i + 1 }}: {{ \App\Models\Tiempo::formatear($vuelta) }}</span>
                                    @endforeach
                                </td>
                                <td>{{ $tiempo->created_at->format('d/m/Y h:i a') }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @endif
        </div>
    </div>

    <script>
        let inicio = 0;          // momento en que se dio Iniciar
        let acumulado = 0;       // tiempo guardado de las pausas anteriores
        let intervalo = null;    // el "reloj" que actualiza la pantalla
        let vueltas = [];        // tiempos de cada vuelta, en milisegundos

        const pantalla = document.getElementById('pantalla');

        function tiempoActual() {
            return intervalo ? acumulado + (Date.now() - inicio) : acumulado;
        }

        function formatear(ms) {
            const minutos = Math.floor(ms / 60000);
            const segundos = Math.floor((ms % 60000) / 1000);
            const centesimas = Math.floor((ms % 1000) / 10);
            return String(minutos).padStart(2, '0') + ':' +
                   String(segundos).padStart(2, '0') + '.' +
                   String(centesimas).padStart(2, '0');
        }

        function actualizar() {
            pantalla.textContent = formatear(tiempoActual());
            document.getElementById('totalMs').value = tiempoActual();
            document.getElementById('vueltas').value = JSON.stringify(vueltas);
        }

        document.getElementById('btnIniciar').onclick = function () {
            inicio = Date.now();
            intervalo = setInterval(actualizar, 10);
            this.disabled = true;
            document.getElementById('btnPausar').disabled = false;
            document.getElementById('btnVuelta').disabled = false;
        };

        document.getElementById('btnPausar').onclick = function () {
            acumulado = tiempoActual();
            clearInterval(intervalo);
            intervalo = null;
            this.disabled = true;
            document.getElementById('btnIniciar').disabled = false;
            document.getElementById('btnVuelta').disabled = true;
            actualizar();
        };

        document.getElementById('btnVuelta').onclick = function () {
            const ms = tiempoActual();
            vueltas.push(ms);
            const item = document.createElement('li');
            item.className = 'list-group-item font-monospace';
            item.textContent = formatear(ms);
            document.getElementById('listaVueltas').appendChild(item);
            actualizar();
        };

        document.getElementById('btnReiniciar').onclick = function () {
            clearInterval(intervalo);
            intervalo = null;
            acumulado = 0;
            vueltas = [];
            document.getElementById('listaVueltas').innerHTML = '';
            document.getElementById('btnIniciar').disabled = false;
            document.getElementById('btnPausar').disabled = true;
            document.getElementById('btnVuelta').disabled = true;
            actualizar();
        };

        actualizar();
    </script>

@endsection
