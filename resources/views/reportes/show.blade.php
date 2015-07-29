@extends('app')

@section('content')
    <ol class="breadcrumb">
        <li><a href="{{ route('almacenes.index') }}">Almacenes</a></li>
        <li><a href="{{ route('almacenes.show', [$almacen]) }}">{{ $almacen->descripcion }}</a></li>
        <li><a href="{{ route('reportes.index', [$almacen]) }}">Reportes de actividad</a></li>
        <li class="active">{{ $reporte->present()->fecha }}</li>
    </ol>

    <h1 class="page-header">
        <span class="fa fa-calendar"></span> Reporte de Actividades
        <span class="text-danger">{{ $reporte->present()->fechaFormatoLocal }}</span>
    </h1>

    <div class="row">
        <div class="col-sm-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    @unless ($reporte->aprobado)
                        <div class="pull-right">
                            <a href="{{ route('reportes.edit', [$almacen, $reporte]) }}" class="btn btn-sm btn-primary">
                                <span class="fa fa-fw fa-edit"></span> Modificar este reporte
                            </a>
                            <a href="{{ route('reportes.aprobar', [$almacen, $reporte]) }}" class="btn btn-sm btn-warning">
                                <span class="fa fa-fw fa-check"></span> Aprobar este reporte
                            </a>
                        </div>
                    @endunless
                    <h4>Datos Generales</h4>
                </div>

                <table class="table table-condensed table-bordered">
                    <tbody>
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

    <div>
        @unless ($reporte->aprobado)
            <a href="{{ route('actividades.create', [$almacen, $reporte]) }}" class="btn btn-sm btn-success pull-right">
                <i class="fa fa-fw fa-clock-o"></i> Reportar Actividades
            </a>
        @endunless
        <h2 class="page-header" id="actividades-reportadas"><i class="fa fa-clock-o"></i> Actividades Reportadas</h2>
    </div>

    @if (count($reporte->actividades))
        @include('reportes.partials.actividades')
    @else
        <p class="alert alert-warning">Este reporte aun no tiene actividades reportadas.</p>
    @endif

    @unless ($reporte->aprobado)
        <hr>
        {!! Form::open(['route' => ['reportes.destroy', $almacen, $reporte], 'method' => 'DELETE']) !!}
            @unless ($reporte->conciliado)
                <button type="submit" class="btn btn-sm btn-danger">
                    <i class="fa fa-times"></i> Eliminar este reporte
                </button>
            @endunless
        {!! Form::close() !!}
    @endunless
    <br/>
@stop
