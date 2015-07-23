@extends('app')

@section('content')
    <ol class="breadcrumb">
        <li><a href="{{ route('conciliacion.proveedores') }}">Proveedores</a></li>
        <li><a href="{{ route('conciliacion.almacenes', [$empresa]) }}">{{ $empresa->razon_social }}</a></li>
        <li>{!! link_to_route('conciliacion.index', $almacen->descripcion, [$empresa, $almacen]) !!}</li>
        <li class="active">Conciliar</li>
    </ol>

    <h1 class="page-header">Nueva Conciliación</h1>

    @include('partials.errors')

    {!! Form::open(['route' => ['conciliacion.store', $empresa, $almacen], 'method' => 'POST']) !!}

        <div class="row">
            <div class="col-sm-6">
                <!-- Fecha Inicial Form Input -->
                <div class="form-group">
                    {!! Form::label('fecha_inicial', 'Fecha Inicial:') !!}
                    {!! Form::date('fecha_inicial', date('Y-m-d'),
                        ['class' => 'form-control pad', 'placeholder' => 'dd-mm-aaaa', 'data-value' => date('Y-m-d')]) !!}
                </div>
            </div>

            <div class="col-sm-6">
                <!-- Fecha Final Form Input -->
                <div class="form-group">
                    {!! Form::label('fecha_final', 'Fecha Final:') !!}
                    {!! Form::date('fecha_final', date('Y-m-d'),
                        ['class' => 'form-control pad', 'placeholder' => 'dd-mm-aaaa', 'data-value' => date('Y-m-d')]) !!}
                </div>
            </div>
        </div>

        <!-- Observaciones Form Input -->
        <div class="form-group">
            {!! Form::label('observaciones', 'Observaciones:') !!}
            {!! Form::textarea('observaciones', null, ['class' => 'form-control', 'rows' => 3]) !!}
        </div>
        
        <div class="form-group">
            {!! Form::submit('Conciliar', ['class' => 'btn btn-primary']) !!}
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