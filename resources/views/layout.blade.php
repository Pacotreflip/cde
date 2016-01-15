<!DOCTYPE html>
<html lang="es">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Control de Equipamiento - Grupo Hermes Infraestructura</title>
    <link rel="stylesheet" href="{{ asset(elixir('css/app.css')) }}">
    @include('scripts.globals')

  </head>
  <body>
    @include('partials.nav')

    <div class="container" style="width: 98%;">
        @include('flash::message')
        @yield('content')
    </div>

    <script src="{{ asset("js/app.js") }}"></script>
    <script src="{{ asset("js/jquery-ui.js") }}"></script>
    <script src="{{ asset("js/jquery.tablesorter.js") }}"></script>
    <script src="{{ asset("js/jquery.tablesorter.widgets.js") }}"></script>
    @yield('scripts')
  </body>
</html>