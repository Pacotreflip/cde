@extends('app')

@section('content')
    <ol class="breadcrumb">
        <li><a href="{{ route('almacenes.index') }}">Almacenes</a></li>
        <li><a href="{{ route('almacenes.show', [$almacen]) }}">{{ $almacen->descripcion }}</a></li>
        <li><a href="{{ route('reportes.index', [$almacen]) }}">Reportes de actividad</a></li>
        <li class="active">Inicio de actividades</li>
    </ol>

    <h1 class="page-header">Inicio de Actividades</h1>

    @include('partials.errors')

    {!! Form::open(['route' => ['reportes.store', $almacen]] ) !!}

        <div class="row">
            <div class="col-sm-6">
                <!-- Fecha Form Input -->
                <div class="form-group">
                    {!! Form::label('fecha', 'Fecha:') !!}
                    <div class="input-group">
                        {!! Form::input('date', 'fecha', date('Y-m-d'),
                            ['class' => 'form-control pad', 'placeholder' => 'dd-mm-aaaa', 'required'])!!}
                        <span class="input-group-addon"><i class="glyphicon glyphicon-calendar"></i></span>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-sm-6">
                <!-- Horometro Inicial Form Input -->
                <div class="form-group">
                    {!! Form::label('horometro_inicial', 'Horometro Inicial:') !!}
                    {!! Form::text('horometro_inicial', null, ['class' => 'form-control decimal', 'placeholder' => '0']) !!}
                </div>
            </div>

            <div class="col-sm-6">
                <!-- Kilometraje Inicial Form Input -->
                <div class="form-group">
                    {!! Form::label('kilometraje_inicial', 'Kilometraje Inicial:') !!}
                    {!! Form::text('kilometraje_inicial', null, ['class' => 'form-control decimal', 'placeholder' => '0']) !!}
                </div>
            </div>
        </div>

        <!-- Operador Form Input -->
        <div class="form-group">
            {!! Form::label('operador', 'Operador:') !!}
            {!! Form::text('operador', null, ['class' => 'form-control', 'placeholder' => 'Nombre del operador']) !!}
        </div>

         <!-- Observaciones Form Input -->
         <div class="form-group">
             {!! Form::label('observaciones', 'Observaciones:') !!}
             {!! Form::textarea('observaciones', null, ['class' => 'form-control', 'rows' => 3]) !!}
         </div>

        <div class="form-group">
            {!! link_to_route('reportes.index', 'Cancelar', [$almacen], ['class' => 'btn btn-danger']) !!}
            {!! Form::submit('Guardar', ['class' => 'btn btn-primary']) !!}
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

        $('input.decimal').inputmask('decimal', {
            autoGroup: true,
            groupSeparator: ',',
            allowMinus: true,
            rightAlign: false,
            removeMaskOnSubmit: true
        });
    </script>
@stop
