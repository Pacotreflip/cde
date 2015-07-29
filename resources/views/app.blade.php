<!doctype html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Control de Maquinaria - Grupo Hermes Infraestructura</title>

    <link href="{{ asset('favicon.ico') }}" rel="shortcut icon" type="image/x-icon" />
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">

    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
        <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
</head>
<body>
    @include('partials.nav')

    <div class="container">
        @include('flash::message')

        @yield('content')
    </div>

    <script src="{{ asset('js/app.js') }}"></script>

    <script>
        $('#flash-overlay-modal').modal();

        $(function () {
            $('[data-toggle="tooltip"]').tooltip()

            $('input.decimal').inputmask('decimal', {
                autoGroup: true,
                groupSeparator: ',',
                allowMinus: true,
                rightAlign: false,
                removeMaskOnSubmit: true
            });

            $('input.integer').inputmask('integer', {
                autoGroup: true,
                groupSeparator: ',',
                allowMinus: true,
                rightAlign: false,
                removeMaskOnSubmit: true
            });
        });
    </script>

    @yield('scripts')
</body>
</html>