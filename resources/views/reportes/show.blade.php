@extends('app')

@section('content')
    <ol class="breadcrumb">
        <li><a href="{{ route('almacenes.index') }}">Almacenes</a></li>
        <li><a href="{{ route('almacenes.show', [$almacen]) }}">{{ $almacen->descripcion }}</a></li>
        <li><a href="{{ route('reportes.index', [$almacen]) }}">Reportes de actividad</a></li>
        <li class="active">{{ $reporte->present()->fecha }}</li>
    </ol>

    <h1 class="page-header">Reporte de Actividades</h1>

    <div class="row">
        <div class="col-sm-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    @if (! $reporte->cerrado)
                        <div class="pull-right">
                            {!! link_to_route('reportes.edit', 'Modificar este reporte', [$almacen, $reporte], ['class' => 'btn btn-sm btn-primary']) !!}
                            {!! link_to_route('reportes.cierre', 'Cerrar este reporte', [$almacen, $reporte], ['class' => 'btn btn-sm btn-warning']) !!}
                        </div>
                    @endif
                    <h4>Datos Generales</h4>
                </div>

                <table class="table table-condensed table-bordered">
                    <tbody>
                        <tr>
                            <th>Fecha</th>
                            <th>{{ $reporte->present()->fecha }}</th>
                        </tr>
                        <tr>
                            <th>Estado</th>
                            <th>@include('reportes.partials.estatus-label', compact('reporte'))</th>
                        </tr>
                        <tr>
                            <th>Horometro Inicial</th>
                            <th class="decimal">{{ $reporte->horometro_inicial }}</th>
                        </tr>
                        <tr>
                            <th>Horometro Final</th>
                            <th class="decimal">{{ $reporte->horometro_final }}</th>
                        </tr>
                        <tr>
                            <th>Kilometraje Inicial</th>
                            <td class="decimal">{{ $reporte->kilometraje_inicial }}</td>
                        </tr>
                        <tr>
                            <th>Kilometraje Final</th>
                            <td class="decimal">{{ $reporte->kilometraje_final }}</td>
                        </tr>
                        <tr>
                            <th>Operador</th>
                            <td>{{ $reporte->operador }}</td>
                        </tr>
                        <tr>
                            <th>Creado por</th>
                            <td>{{ $reporte->creado_por }}</td>
                        </tr>
                        <tr>
                            <th>Observaciones</th>
                            <td>{{ $reporte->observaciones }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <br/>

    <div>
        @unless($reporte->cerrado)
            <p>
                {!! link_to_route('actividades.create', 'Reportar Actividades', [$almacen, $reporte],
                    ['class' => 'btn btn-sm btn-success pull-right']) !!}
            </p>
        @endunless
        <h3 class="page-header" id="actividades-reportadas">Actividades Reportadas</h3>
    </div>

    @if(count($reporte->actividades))
        <div class="panel panel-default">
                @include('reportes.partials.actividades')
        </div>
    @else
        <p class="alert alert-warning">Este reporte aun no tiene actividades reportadas.</p>
    @endif

    @if (! $reporte->cerrado)
        {!! Form::open(['route' => ['reportes.destroy', $almacen, $reporte], 'method' => 'DELETE']) !!}

        @if (! $reporte->conciliado)
            {!! Form::submit('Eliminar este reporte', ['class' => 'btn btn-sm btn-danger']) !!}
        @endif
        {!! Form::close() !!}
    @endif
    <br/>
@stop

@section('scripts')
    @parent
    <script>
        $('.decimal').inputmask('decimal', {
            autoGroup: true,
            groupSeparator: ',',
            allowMinus: true,
            rightAlign: false,
            removeMaskOnSubmit: true
        });
    </script>
@stop