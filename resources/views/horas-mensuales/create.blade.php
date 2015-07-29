@extends('app')

@section('content')
    <ol class="breadcrumb">
        <li><a href="{{ route('almacenes.index') }}">Almacenes</a></li>
        <li><a href="{{ route('almacenes.show', [$almacen]) }}">{{ $almacen->descripcion }}</a></li>
        <li class="active">Registro de horas mensuales</li>
    </ol>

    <h1 class="page-header">Registro de Horas Mensuales</h1>

    @include('partials.errors')

    {!! Form::open(['route' => ['horas-mensuales.store', $almacen], 'method' => 'POST', 'class' => 'form-horizontal']) !!}

        @include('horas-mensuales.partials.form-fields')

        <div class="form-group">
            <div class="col-sm-offset-2 col-sm-4">
                {!! Form::submit('Guardar', ['class' => 'btn btn-primary']) !!}
            </div>
        </div>

    {!! Form::close() !!}
@stop

@section('scripts')
    <script>
        if (! Modernizr.inputtypes.date) {
            $('.pad').pickadate({
                format: 'dd/mm/yyyy',
                formatSubmit: 'yyyy-mm-dd',
                hiddenName: true,
                selectYears: true,
                selectMonths: true,
                labelMonthNext: 'Vaya al mes siguiente',
                labelMonthPrev: 'Vaya al mes anterior',
                labelMonthSelect: 'Elija un mes de la lista',
                labelYearSelect: 'Elija un año de la lista'
            });
        }
    </script>
@stop