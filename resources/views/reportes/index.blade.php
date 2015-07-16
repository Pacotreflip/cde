@extends('app')

@section('content')
    <ol class="breadcrumb">
        <li><a href="{{ route('almacenes.index') }}">Almacenes</a></li>
        <li><a href="{{ route('almacenes.show', $almacen) }}">{{ $almacen->descripcion }}</a></li>
        <li class="active">Reportes de actividad</li>
    </ol>

    <div>
        <a href="{{ route('reportes.create', [$almacen]) }}" class="btn btn-sm btn-success pull-right"><i class="fa fa-plus"></i> Nuevo Reporte</a>
        <h1 class="page-header">
            <i class="fa fa-list-ul"></i> Reportes de Actividad</h1>
    </div>

    @if(count($reportes))
        <div class="panel panel-default">
            <ul class="list-group">
                @foreach($reportes as $reporte)
                    <a class="list-group-item" href="{!! route('reportes.show', [$almacen, $reporte]) !!}">
                        <span><b>{{ $reporte->present()->fechaFormatoLocal }}</b></span>

                        @include('reportes.partials.estatus-label', compact('reporte'))

                        <span class="badge" data-toggle="tooltip" data-placement="top" title="Cantidad de horas reportadas en esta fecha" aria-hidden="true">
                            {{ $reporte->present()->sumaHoras }}
                        </span>
                    </a>
                @endforeach
            </ul>
        </div>
    @else
        <p class="alert alert-warning">Este almacén aun no tiene reportes de actividad.</p>
    @endif

    {!! $reportes->render() !!}
@stop