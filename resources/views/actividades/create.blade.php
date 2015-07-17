@extends('app')

@section('content')
    <ol class="breadcrumb">
        <li><a href="{{ route('almacenes.index') }}">Almacenes</a></li>
        <li><a href="{{ route('almacenes.show', [$almacen]) }}">{{ $reporte->almacen->descripcion }}</a></li>
        <li><a href="{{ route('reportes.index', [$almacen]) }}">Reportes de actividad</a></li>
        <li><a href="{{ route('reportes.show', [$almacen, $reporte]) }}">{{ $reporte->present()->fecha }}</a></li>
        <li class="active">Reportar actividades</li>
    </ol>

    <h1 class="page-header">Reportar Actividades</h1>

    @include('partials.errors')

    {!! Form::open(['route' => ['actividades.store', $almacen, $reporte], 'method' => 'POST']) !!}

        <div class="row">
            <div class="col-sm-6">
                <!-- Tipo Hora Form Input -->
                <div class="form-group">
                    {!! Form::label('tipo_hora', 'Tipo de Hora:') !!}
                    {!! Form::select('tipo_hora', $tipos_hora, null, ['class' => 'form-control']) !!}
                </div>
            </div>
            <div class="col-sm-6">
                <!-- Cantidad Form Input -->
                <div class="form-group">
                    {!! Form::label('cantidad', 'Cantidad:') !!}
                    {!! Form::text('cantidad', null, ['class' => 'form-control decimal', 'placeholder' => '0']) !!}
                </div>
            </div>
        </div>

        <div class="form-group">
            <label>Turno:</label>
            <label class="radio-inline">{!! Form::radio('turno', 1) !!} Primero</label>
            <label class="radio-inline">{!! Form::radio('turno', 2) !!} Segundo</label>
            <label class="radio-inline">{!! Form::radio('turno', 3) !!} Tercero</label>
        </div>

        <div class="row">
            <div class="col-sm-6">
                <!-- Hora Inicial Form Input -->
                <div class="form-group">
                    {!! Form::label('hora_inicial', 'Hora Inicial:') !!}
                    {!! Form::input('time', 'hora_inicial', null, ['class' => 'form-control time', 'placeholder' => 'hh:mm']) !!}
                </div>
            </div>
            <div class="col-sm-6">
                <!-- Hora Final Form Input -->
                <div class="form-group">
                    {!! Form::label('hora_final', 'Hora Final:') !!}
                    {!! Form::input('time', 'hora_final', null, ['class' => 'form-control time', 'placeholder' => 'hh:mm']) !!}
                </div>
            </div>
        </div>

        <!-- Actividad Form Input -->
        <div class="form-group">
            {!! Form::label('actividad', 'Actividad:') !!}
            <div class="input-group">
                {!! Form::text('actividad', null, ['class' => 'form-control', 'placeholder' => 'Digite clave o nombre de actividad destino']) !!}
                    <div type="button" data-toggle="modal" data-target="#myModal" class="input-group-addon btn">
                        <i class="fa fa-fw fa-sitemap"></i>
                    </div>
                {!! Form::hidden('id_concepto', null, ['class' => 'form-control', 'id' => 'id_concepto']) !!}
            </div>
        </div>

        <div class="form-group">
            <div class="checkbox">
                <label>{!! Form::checkbox('con_cargo_empresa', 1); !!} Con cargo a la empresa?</label>
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

    <!-- Modal -->
    <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    <h3 class="modal-title" id="myModalLabel">Presupuesto de Obra</h3>
                    <p class="alert alert-warning text-center">Seleccione un concepto y de clic en cerrar para asignarlo.</p>
                </div>
                <div class="modal-body">
                    <div id="jstree"></div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
                </div>
            </div>
        </div>
    </div>
@stop

@section('scripts')
    <script>
        if (! Modernizr.inputtypes.time) {
            $('input.time').inputmask('hh:mm', {
                rightAlign: false
            });
        }

        $('input#actividad').typeahead({
            highlight: true,
            limit: 10,
            displayKey: 'value',
            minLength: 1
        }, {
            source: function(query, process) {
                $.getJSON('/api/conceptos', {search: query}, function(json) {
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

        // JsTree Configuration
        var jstreeConf = {
            'core' : {
                'multiple': false,
                'data': {
                    "url": function(node) {
                        if (node.id === "#") {
                            return "{{ url('api/conceptos/jstree') }}";
                        }
                        return '/api/conceptos/' + node.id + '/jstree';
                    },
                    "data": function (node) {
                        return { "id" : node.id };
                    }
                }
            },
            'types': {
                'default': {
                    'icon': 'fa fa-folder-o text-success'
                },
                'medible': {
                    'icon': 'fa fa-file-text'
                },
                'material' : {
                    'icon': 'fa fa-briefcase'
                },
                'opened' : {
                    'icon': 'fa fa-folder-open-o text-success'
                }
            },
            'plugins': ['types']
        };

        $('#jstree').on("after_open.jstree", function (e, data) {
            if (data.instance.get_type(data.node) == 'default') {
                data.instance.set_type(data.node, 'opened');
            }
        }).on("after_close.jstree", function (e, data) {
            if (data.instance.get_type(data.node) == 'opened') {
                data.instance.set_type(data.node, 'default');
            }
        });

        // On hide the BS modal, get the selected node and destroy the jstree
        $('#myModal').on('shown.bs.modal', function (e) {
            $('#jstree').jstree(jstreeConf);
        }).on('hidden.bs.modal', function (e) {
            var jstree = $('#jstree').jstree(true);
            var node = jstree.get_selected(true)[0];

            if (node) {
                $('#id_concepto').val(node.id);
                $('#actividad').val(node.text);
            }

            jstree.destroy();
        });
    </script>
@stop