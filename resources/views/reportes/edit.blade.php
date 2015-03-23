@extends('app')

@section('nav-sub')
    @include('partials.nav-sub', ['almacen' => $reporte->almacen])
@endsection

@section('content')
    <ol class="breadcrumb">
        <li><a href="{{ route('almacenes.index') }}">Almacenes</a></li>
        <li><a href="{{ route('almacenes.show', [$reporte->almacen->id_almacen]) }}">{{ $reporte->almacen->descripcion }}</a></li>
        <li><a href="{{ route('reportes.index', [$reporte->almacen->id_almacen]) }}">Reportes de actividad</a></li>
        <li><a href="{{ route('reportes.show', [$reporte->almacen->id_almacen, $reporte->id]) }}">{{ $reporte->present()->fecha }}</a></li>
        <li class="active">modificar</li>
    </ol>

    <h1 class="page-header">Reporte de Actividades</h1>

    @include('partials.errors')

    {!! Form::model($reporte, ['route' => ['reportes.update', $reporte->id_almacen, $reporte->id], 'method' => 'PATCH'] ) !!}

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
                    {!! Form::text('horometro_inicial', null, ['class' => 'form-control']) !!}
                </div>
            </div>

            <div class="col-sm-6">
                <!-- Kilometraje Inicial Form Input -->
                <div class="form-group">
                    {!! Form::label('kilometraje_inicial', 'Kilometraje Inicial:') !!}
                    {!! Form::text('kilometraje_inicial', null, ['class' => 'form-control']) !!}
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

        {{--<h2>Actividades reportadas</h2>--}}

        {{--<div class="panel panel-default">--}}
            {{--<table class="table table-striped table-bordered">--}}
                {{--<thead>--}}
                    {{--<tr>--}}
                        {{--<th>Tipo</th>--}}
                        {{--<th>Cantidad</th>--}}
                        {{--<th>Actividad</th>--}}
                        {{--<th>Con cargo</th>--}}
                        {{--<th>Observaciones</th>--}}
                        {{--<th></th>--}}
                    {{--</tr>--}}
                {{--</thead>--}}
                {{--<tbody>--}}
                    {{--@foreach($reporte->actividades as $actividad)--}}
                        {{--<tr>--}}
                            {{--<td>{{ $actividad->tipoHora->descripcion }}</td>--}}
                            {{--<td>--}}
                                {{--{!! Form::text("actividades[$actividad->id][cantidad]", $actividad->cantidad, ['class' => 'form-control']) !!}--}}
                            {{--</td>--}}
                            {{--<td>--}}
                                {{--@if ($actividad->concepto)--}}
                                    {{--{{ $actividad->concepto->present()->descripcionConClave }}--}}
                                {{--@endif--}}
                            {{--</td>--}}
                            {{--<td class="text-center">--}}
                                {{--@if ($actividad->con_cargo)--}}
                                    {{--{!! Form::checkbox("actividades[$actividad->id][con_cargo]", 1, true) !!}--}}
                                {{--@else--}}
                                    {{--{!! Form::checkbox("actividades[$actividad->id][con_cargo]", 1) !!}--}}
                                {{--@endif--}}
                            {{--</td>--}}
                            {{--<td>--}}
                                {{--{!! Form::textarea("actividades[$actividad->id][observaciones]", $actividad->observaciones, ['class' => 'form-control', 'rows' => 2]) !!}--}}
                            {{--</td>--}}
                            {{--<td class="text-center">--}}
                                {{--{!! Form::checkbox("actividades[$actividad->id][borrar]", 1) !!}--}}
                            {{--</td>--}}
                        {{--</tr>--}}
                    {{--@endforeach--}}
                {{--</tbody>--}}
            {{--</table>--}}
        {{--</div>--}}

        <div class="form-group">
            {!! link_to_route('reportes.show', 'Cancelar', [$reporte->id_almacen, $reporte->id], ['class' => 'btn btn-md btn-danger']) !!}
            {!! Form::submit('Guardar', ['class' => 'btn btn-md btn-primary']) !!}
        </div>
    {!! Form::close() !!}
@endsection