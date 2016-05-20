<!DOCTYPE html>
<html lang="es">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <title>Control de Equipamiento - Grupo Hermes Infraestructura</title>
    <link rel="stylesheet" href="{{ asset(elixir('css/app.css')) }}">
    <link rel="stylesheet" href="{{ asset(elixir('css/tree.css')) }}">
    @include('scripts.globals')
<style>
.venntooltip {
  position: absolute;
  text-align: center;
  width: 128px;
  height: 25px;
  background: #333;
  color: #ddd;
  padding: 2px;
  border: 0px;
  border-radius: 8px;
  opacity: 0;
}
</style>
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