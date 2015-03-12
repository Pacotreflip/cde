@extends('layouts.default')

@section('content')
    <div class="col-sm-10 col-sm-offset-1">
        <h2>Reportar Horas del {{ $reporte->present()->fechaFormatoLocal }}</h2>

        @include ('layouts.partials.errors')

        {!! Form::open() !!}

            <div class="row">
                <div class="col-sm-6">
                    <!-- Tipo Hora Form Input -->
                    <div class="form-group">
                        {!! Form::label('tipo_hora', 'Tipo de Hora:') !!}
                        {!! Form::select('tipo_hora', $tipoHora, null, ['class' => 'form-control']) !!}
                    </div>
                </div>
                <div class="col-sm-6">
                    <!-- Cantidad Form Input -->
                    <div class="form-group">
                        {!! Form::label('cantidad', 'Cantidad:') !!}
                        {!! Form::text('cantidad', null, ['class' => 'form-control']) !!}
                    </div>
                </div>
            </div>

            <!-- Actividad Form Input -->
            <div class="form-group">
                {!! Form::label('actividad', 'Actividad:') !!}
                {!! Form::text('actividad', null, ['class' => 'form-control', 'placeholder' => 'Digite codigo o nombre de actividad']) !!}
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
                {!! link_to_route('operacion.show', 'Cancelar',
                    [$reporte->equipo->id_almacen, $reporte->present()->fechaFormato],
                    ['class' => 'btn btn-danger']) !!}

                {!! Form::submit('Guardar', ['class' => 'btn btn-success']) !!}
            </div>
        {!! Form::close() !!}
    </div>
@stop

@section('scripts')
    <script>
        $('#actividad').typeahead({
            hint: true,
            highlight: true,
            displayKey: 'value',
            minLength: 1
        }, {
            source: function(query, process)
            {
                $.getJSON('/api/conceptos', {q: query, medible: 3}, function(json) {
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
@stop