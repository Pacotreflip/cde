@extends('app')

@section('content')
    <ol class="breadcrumb">
        <li><a href="{{ route('almacenes.index') }}">Almacenes</a></li>
        <li><a href="{{ route('almacenes.show', [$almacen]) }}">{{ $almacen->descripcion }}</a></li>
        <li><a href="{{ route('reportes.index', [$almacen]) }}">Reportes de actividad</a></li>
        <li><a href="{{ route('reportes.show', [$almacen, $reporte]) }}">{{ $reporte->present()->fecha }}</a></li>
        <li class="active">cerrar</li>
    </ol>

    <h1 class="page-header">Cierre de Actividades</h1>

    @include('partials.errors')

    <div class="col-sm-12 alert alert-info text-center">
        <strong>Este reporte de actividades contiene {{ $reporte->present()->sumaHoras }} reportadas</strong>
    </div>

    {!! Form::model($reporte, ['route' => ['reportes.update', $almacen, $reporte], 'method' => 'PATCH']) !!}

        {!! Form::hidden('cerrar', true) !!}

        <div class="row">
            <div class="col-sm-6">
                <!-- Horometro Inicial Form Input -->
                <div class="form-group">
                    {!! Form::label('horometro_inicial', 'Horometro Inicial:') !!}
                    <p class="form-control" readonly>{{ $reporte->horometro_inicial }}</p>
                </div>
            </div>
            <div class="col-sm-6">
                <!-- Kilometraje Inicial Form Input -->
                <div class="form-group">
                    {!! Form::label('kilometraje_inicial', 'Kilometraje Inicial:') !!}
                    <p class="form-control" readonly>{{ $reporte->kilometraje_inicial }}</p>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-6">
                <!-- Horometro Final Form Input -->
                <div class="form-group">
                    {!! Form::label('horometro_final', 'Horometro Final:') !!}
                    {!! Form::text('horometro_final', null, ['class' => 'form-control']) !!}
                </div>
            </div>
            <div class="col-sm-6">
                <!-- Kilometraje Final Form Input -->
                <div class="form-group">
                    {!! Form::label('kilometraje_final', 'Kilometraje Final:') !!}
                    {!! Form::text('kilometraje_final', null, ['class' => 'form-control']) !!}
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
            {!! link_to_route('reportes.show', 'Cancelar', [$almacen, $reporte], ['class' => 'btn btn-danger']) !!}
            {!! Form::submit('Cerrar Reporte', ['class' => 'btn btn-success']) !!}
        </div>

    {!! Form::close() !!}
@stop