@extends('app')

@section('content')
    <ol class="breadcrumb">
        <li><a href="{{ route('almacenes.index') }}">Almacenes</a></li>
        <li><a href="{{ route('reportes.show', [$reporte->almacen->id_almacen]) }}">{{ $reporte->almacen->descripcion }}</a></li>
        <li><a href="{{ route('reportes.index', [$reporte->almacen->id_almacen]) }}">Reportes de actividad</a></li>
        <li><a href="{{ route('reportes.show', [$reporte->almacen->id_almacen, $reporte->id]) }}">{{ $reporte->present()->fecha }}</a></li>
        <li class="active">Reportar actividades</li>
    </ol>

    <h1 class="page-header">Reportar Actividades</h1>

    @include ('partials.errors')

    {!! Form::open(['route' => ['actividades.store', $reporte->almacen->id_almacen, $reporte->id], 'method' => 'POST']) !!}
        <div class="row">
            <div class="col-sm-6">
                <!-- Tipo Hora Form Input -->
                <div class="form-group">
                    {!! Form::label('tipo_hora', 'Tipo de Hora:') !!}
                    {!! Form::select('tipo_hora', $tiposHora, null, ['class' => 'form-control']) !!}
                </div>
            </div>
            <div class="col-sm-6">
                <!-- Cantidad Form Input -->
                <div class="form-group">
                    {!! Form::label('cantidad', 'Cantidad:') !!}
                    {!! Form::number('cantidad', null, ['class' => 'form-control']) !!}
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-sm-6">
                <!-- Hora Inicial Form Input -->
                <div class="form-group">
                    {!! Form::label('hora_inicial', 'Hora Inicial:') !!}
                    {!! Form::input('time', 'hora_inicial', null, ['class' => 'form-control']) !!}
                </div>
            </div>
            <div class="col-sm-6">
                <!-- Hora Final Form Input -->
                <div class="form-group">
                    {!! Form::label('hora_final', 'Hora Final:') !!}
                    {!! Form::input('time', 'hora_final', null, ['class' => 'form-control']) !!}
                </div>
            </div>
        </div>

        <!-- Actividad Form Input -->
        <div class="form-group">
            {!! Form::label('actividad', 'Actividad:') !!}
            {!! Form::text('actividad', null, ['class' => 'form-control', 'placeholder' => 'Digite código o nombre de actividad']) !!}
            {!! Form::hidden('id_concepto', null, ['class' => 'form-control', 'id' => 'id_concepto']) !!}
        </div>

        <div class="form-group">
            <div class="checkbox">
                <label>{!! Form::checkbox('con_cargo', 1); !!} Con cargo</label>
            </div>
        </div>

        <!-- Observaciones Form Input -->
        <div class="form-group">
            {!! Form::label('observaciones', 'Observaciones:') !!}
            {!! Form::textarea('observaciones', null, ['class' => 'form-control', 'rows' => 3]) !!}
        </div>

        <div class="form-group">
            {!! Form::submit('Guardar', ['class' => 'btn btn-primary']) !!}
        </div>
    {!! Form::close() !!}
@endsection

@section('scripts')
    <script>
        $('input#actividad').typeahead({
            hint: true,
            highlight: true,
            displayKey: 'value',
            minLength: 1
        }, {
            source: function(query, process)
            {
                $.getJSON('/api/conceptos', {search: query, medible: 1}, function(json) {
                    var conceptos = [];

                    $.each(json.data, function(i, concepto) {
                        conceptos.push({
                            value: '[' + concepto.clave + '] - ' + concepto.descripcion,
                            id: concepto.id
                        });
                    });

                    return process(conceptos);
                });
            }
        }).on('typeahead:selected', function(event, item, dataset) {
            $('#id_concepto').val(item.id);
        });
    </script>
@endsection