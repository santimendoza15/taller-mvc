<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Taller MVC</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <nav class="navbar navbar-expand bg-dark navbar-dark">
        <div class="container">
            <a class="navbar-brand" href="{{ url('/') }}">Taller MVC</a>
            <ul class="navbar-nav">
                <li class="nav-item"><a class="nav-link" href="{{ route('tips.index') }}">Propinas</a></li>
                <li class="nav-item"><a class="nav-link" href="{{ route('contrasena.index') }}">Contraseñas</a></li>
                <li class="nav-item"><a class="nav-link" href="{{ route('tareas.index') }}">Tareas</a></li>
                <li class="nav-item"><a class="nav-link" href="{{ route('notas.index') }}">Notas</a></li>
                <li class="nav-item"><a class="nav-link" href="{{ route('gastos.index') }}">Gastos</a></li>
                <li class="nav-item"><a class="nav-link" href="{{ route('cronometro.index') }}">Cronómetro</a></li>
            </ul>
        </div>
    </nav>

    <div class="container my-4">

        @if ($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @yield('content')
    </div>
</body>
</html>
