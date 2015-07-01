@extends('app')

@section('content')
    <ol class="breadcrumb">
        <li><a href="{{ route('almacenes.index') }}">Almacenes</a></li>
        <li><a href="{{ route('almacenes.show', [$almacen]) }}">{{ $almacen->descripcion }}</a></li>
        <li class="active">Registro de horas mensuales</li>
    </ol>

    <h1 class="page-header">Registro de Horas Mensuales</h1>

    <br/>

    @include('partials.errors')

    {!! Form::open(['route' => ['horas-mensuales.store', $almacen], 'method' => 'POST', 'class' => 'form-horizontal']) !!}

        <!-- Inicio Vigencia Form Input -->
        <div class="form-group">
            {!! Form::label('inicio_vigencia', 'Vigente a Partir de:', ['class' => 'col-sm-2 control-label']) !!}
            <div class="col-sm-4">
                <div class="input-group">
                    {!! Form::input('date', 'inicio_vigencia', date('Y-m-d'),
                        ['class' => 'form-control pad', 'placeholder' => 'dd-mm-aaaa', 'required']) !!}
                    <span class="input-group-addon"><i class="glyphicon glyphicon-calendar"></i></span>
                </div>
            </div>
        </div>

        <!-- Horas Contrato Form Input -->
        <div class="form-group">
            {!! Form::label('horas_contrato', 'Horas de Contrato:', ['class' => 'col-sm-2 control-label']) !!}
            <div class="col-sm-4">
                {!! Form::text('horas_contrato', null, ['class' => 'form-control integer', 'placeholder' => '0']) !!}
            </div>
        </div>

        <!-- Horas Operacion Form Input -->
        <div class="form-group">
            {!! Form::label('horas_operacion', 'Horas de Operacion:', ['class' => 'col-sm-2 control-label']) !!}
            <div class="col-sm-4">
                {!! Form::text('horas_operacion', null, ['class' => 'form-control integer', 'placeholder' => '0']) !!}
            </div>
        </div>

        <!-- Horas Programa Form Input -->
        <div class="form-group">
            {!! Form::label('horas_programa', 'Horas de Programa:', ['class' => 'col-sm-2 control-label']) !!}
            <div class="col-sm-4">
                {!! Form::text('horas_programa', null, ['class' => 'form-control integer', 'placeholder' => '0']) !!}
            </div>
        </div>

        <!-- Horas Programa Form Input -->
        <div class="form-group">
            {!! Form::label('observaciones', 'Observaciones:', ['class' => 'col-sm-2 control-label']) !!}
            <div class="col-sm-4">
                {!! Form::textarea('observaciones', null, ['class' => 'form-control integer', 'rows' => 3]) !!}
            </div>
        </div>

        <div class="form-group">
            <div class="col-sm-offset-2 col-sm-4">
                {!! Form::submit('Guardar', ['class' => 'btn btn-primary']) !!}
            </div>
        </div>

    {!! Form::close() !!}
@stop

@section('scripts')
    <script>
        if ( ! Modernizr.inputtypes.date) {
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

        $('input.integer').inputmask('integer', {
            autoGroup: true,
            groupSeparator: ',',
            rightAlign: false,
            removeMaskOnSubmit: true
        });
    </script>
@stop