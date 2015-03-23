@extends('app')

@section('nav-sub')
    @include('partials.nav-sub', ['almacen' => $reporte->almacen])
@endsection

@section('content')
    <ol class="breadcrumb">
        <li><a href="{{ route('almacenes.index') }}">Almacenes</a></li>
        <li><a href="{{ route('almacenes.show', [$reporte->almacen->id_almacen]) }}">{{ $reporte->almacen->descripcion }}</a></li>
        <li><a href="{{ route('reportes.index', [$reporte->almacen->id_almacen]) }}">Reportes de actividad</a></li>
        <li class="active">{{ $reporte->present()->fecha }}</li>
    </ol>

    <h1 class="page-header">Reporte de Actividades</h1>

    <div class="row">
        <div class="col-sm-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    @if( ! $reporte->cerrado)
                        {!! Form::open(['route' => ['reportes.destroy', $reporte->almacen->id_almacen, $reporte->id], 'method' => 'DELETE', 'class' => 'pull-right']) !!}
                            {!! link_to_route('reportes.edit', 'Modificar', [$reporte->almacen->id_almacen, $reporte->id], ['class' => 'btn btn-sm btn-primary']) !!}
                            {!! link_to_route('reportes.cierre', 'Cerrar', [$reporte->almacen->id_almacen, $reporte->id], ['class' => 'btn btn-sm btn-success']) !!}

                            @if ( ! $reporte->conciliado)
                                {!! Form::submit('Borrar', ['class' => 'btn btn-sm btn-danger']) !!}
                            @endif
                        {!! Form::close() !!}
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
                        <th>{{ $reporte->horometro_inicial }}</th>
                    </tr>
                    <tr>
                        <th>Horometro Final</th>
                        <th>{{ $reporte->horometro_final }}</th>
                    </tr>
                    <tr>
                        <th>Kilometraje Inicial</th>
                        <td>{{ $reporte->kilometraje_inicial }}</td>
                    </tr>
                    <tr>
                        <th>Kilometraje Final</th>
                        <td>{{ $reporte->kilometraje_final }}</td>
                    </tr>
                    <tr>
                        <th>Operador</th>
                        <td>{{ $reporte->operador }}</td>
                    </tr>
                    <tr>
                        <th>Creado por</th>
                        <td>{{ $reporte->creadoPor->usuario }}</td>
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
                {!! link_to_route('actividades.create', 'Reportar actividades', [$reporte->almacen->id_almacen, $reporte->id],
                    ['class' => 'btn btn-sm btn-primary pull-right']) !!}
            </p>
        @endunless
        <h3>Actividades Reportadas</h3>
    </div>

    @if(count($reporte->actividades))
        <div class="panel panel-default">
                @include('reportes.partials.actividades', ['reporte' => $reporte])
        </div>
    @else
        <p class="alert alert-warning"><strong>No existen actividades reportadas.</strong></p>
    @endif

@endsection