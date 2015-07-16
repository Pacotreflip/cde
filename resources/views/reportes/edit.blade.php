@extends('app')

@section('content')
    <ol class="breadcrumb">
        <li><a href="{{ route('almacenes.index') }}">Almacenes</a></li>
        <li><a href="{{ route('almacenes.show', [$almacen]) }}">{{ $almacen->descripcion }}</a></li>
        <li><a href="{{ route('reportes.index', [$almacen]) }}">Reportes de actividad</a></li>
        <li><a href="{{ route('reportes.show', [$almacen, $reporte]) }}">{{ $reporte->present()->fecha }}</a></li>
        <li class="active">modificar</li>
    </ol>

    <h1 class="page-header">Reporte de Actividades</h1>

    @include('partials.errors')

    {!! Form::model($reporte, ['route' => ['reportes.update', $almacen, $reporte], 'method' => 'PATCH'] ) !!}

        <div class="row">
            <div class="col-sm-6">
                <!-- Fecha Form Input -->
                <div class="form-group">
                    {!! Form::label('fecha', 'Fecha:') !!}
                    <p class="form-control-static">{{ $reporte->present()->fecha }}</p>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-sm-6">
                <!-- Horometro Inicial Form Input -->
                <div class="form-group">
                    {!! Form::label('horometro_inicial', 'Horometro Inicial:') !!}
                    {!! Form::text('horometro_inicial', null, ['class' => 'form-control decimal']) !!}
                </div>
            </div>

            <div class="col-sm-6">
                <!-- Kilometraje Inicial Form Input -->
                <div class="form-group">
                    {!! Form::label('kilometraje_inicial', 'Kilometraje Inicial:') !!}
                    {!! Form::text('kilometraje_inicial', null, ['class' => 'form-control decimal']) !!}
                </div>
            </div>
        </div>

        <!-- Operador Form Input -->
        <div class="form-group">
            {!! Form::label('operador', 'Nombre del Operador:') !!}
            {!! Form::text('operador', null, ['class' => 'form-control', 'placeholder' => 'Nombre del operador']) !!}
        </div>

        <!-- Observaciones Form Input -->
        <div class="form-group">
            {!! Form::label('observaciones', 'Observaciones:') !!}
            {!! Form::textarea('observaciones', null, ['class' => 'form-control', 'rows' => 3]) !!}
        </div>

        <div class="form-group">
            {!! link_to_route('reportes.show', 'Cancelar', [$almacen, $reporte], ['class' => 'btn btn-md btn-danger']) !!}
            {!! Form::submit('Guardar', ['class' => 'btn btn-md btn-primary']) !!}
        </div>

    {!! Form::close() !!}
@stop

@section('scripts')
    <script>
        $('input.decimal').inputmask('decimal', {
            autoGroup: true,
            groupSeparator: ',',
            allowMinus: true,
            rightAlign: false,
            removeMaskOnSubmit: true
        });
    </script>
@stop