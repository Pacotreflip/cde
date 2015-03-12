@extends('app')

@section('content')
    <ol class="breadcrumb">
        <li><a href="{{ route('reportes.almacenes') }}">Almacenes</a></li>
        <li class="active">Reportes de actividad</li>
    </ol>

    <div>
        {!! link_to_route('reportes.create', 'Nuevo reporte', [$almacen->id_almacen], ['class' => 'btn btn-sm btn-primary pull-right']) !!}
        <h1>Reportes de Actividad <small>{{ $almacen->descripcion }}</small></h1>
    </div>

    @if(count($reportes))
        <div class="panel panel-default">
            <ul class="list-group">
                @foreach($reportes as $reporte)
                    <a class="list-group-item" href="{!! route('reportes.show', [$reporte->id_almacen, $reporte->id]) !!}">
                        <span>{{ $reporte->present()->fechaFormatoLocal }}</span>

                        @include('reportes.partials.estatus-label', compact('reporte'))

                        <span class="badge">{{ $reporte->present()->sumaHoras }}</span>
                    </a>
                @endforeach
            </ul>
        </div>
    @else
        <p class="alert alert-warning">No existen reportes de actividad registrados.</p>
    @endif

    {!! $reportes->render() !!}
@stop