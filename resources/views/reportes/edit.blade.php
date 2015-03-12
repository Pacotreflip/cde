@extends('app')

@section('content')
    <h1>Reporte de Actividades <small>{{ $reporte->present()->fecha }}</small></h1>

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

        <h2>Actividades reportadas</h2>

        <div class="panel panel-default">
            <table class="table table-striped table-bordered">
                <thead>
                    <tr>
                        <th>Tipo</th>
                        <th>Cantidad</th>
                        <th>Actividad</th>
                        <th>Con cargo</th>
                        <th>Observaciones</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($reporte->actividades as $actividad)
                        <tr>
                            <td>{{ $actividad->tipoHora->descripcion }}</td>
                            <td>
                                {!! Form::text("actividades[$actividad->id][cantidad]", $actividad->cantidad, ['class' => 'form-control']) !!}
                            </td>
                            <td>
                                @if ($actividad->concepto)
                                    {{ $actividad->concepto->present()->descripcionConClave }}
                                @endif
                            </td>
                            <td class="text-center">
                                @if ($actividad->con_cargo)
                                    {!! Form::checkbox("actividades[$actividad->id][con_cargo]", 1, true) !!}
                                @else
                                    {!! Form::checkbox("actividades[$actividad->id][con_cargo]", 1) !!}
                                @endif
                            </td>
                            <td>
                                {!! Form::textarea("actividades[$actividad->id][observaciones]", $actividad->observaciones, ['class' => 'form-control', 'rows' => 2]) !!}
                            </td>
                            <td class="text-center">
                                {!! Form::checkbox("actividades[$actividad->id][borrar]", 1) !!}
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="form-group">
            {!! link_to_route('reportes.show', 'Cancelar', [$reporte->id_almacen, $reporte->id], ['class' => 'btn btn-md btn-danger']) !!}
            {!! Form::submit('Guardar', ['class' => 'btn btn-md btn-primary']) !!}
        </div>
    {!! Form::close() !!}
@endsection