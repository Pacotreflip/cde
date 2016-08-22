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
    <link rel="stylesheet" href="{{ asset('css/select2.css') }}">
    <link rel="stylesheet" href="{{ asset('css/dependent-dropdown.css') }}">
    <link rel="stylesheet" href="{{ asset('tableFilter/tablefilter.css') }}">
    @include('scripts.globals')

@yield('styles')
  </head>
  <body>
    @include('partials.nav')

    <div class="container" style="width: 98%;">
        @include('flash::message')
        @yield('content')
    </div>
    <br>
    <script src="{{ asset("js/app.js") }}"></script>
    <script src="{{ asset("js/jquery-ui.js") }}"></script>
    <script src="{{ asset("js/jquery.tablesorter.js") }}"></script>
    <script src="{{ asset("js/jquery.tablesorter.widgets.js") }}"></script>
    <script src="{{ asset("js/loadingoverlay.js") }}"></script>
    <script src="{{ asset("js/select2.full.js") }}"></script>
    <script src="{{ asset("js/dependent-dropdown.js") }}"></script>
    <script src="{{ asset("js/depdrop_locale_es.js") }}"></script>
    <script src="{{ asset("tablefilter/tablefilter.js") }}"></script>
    <script>
      $(function () {
        $.ajaxSetup({
          headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
          }
        });    
      });
    </script>
    @yield('scripts')
  </body>
</html>