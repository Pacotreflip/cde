@extends('app')

@section('content')
    <ol class="breadcrumb">
        <li><a href="{{ route('almacenes.index') }}">Almacenes</a></li>
        <li class="active">{{ $almacen->descripcion }}</li>
    </ol>

    <h1 class="page-header">Almacén</h1>

    <br/>

    <div class="panel panel-default">
        <div class="panel-heading">
            {!! link_to_route('almacenes.edit', 'Modificar este almacén', [$almacen], ['class' => 'btn btn-sm btn-primary pull-right']) !!}
            <h4>Datos Generales</h4>
        </div>

        <table class="table table-bordered">
            <tbody>
            <tr>
                <th>Numero Economico</th>
                <th>{{ $almacen->numero_economico }}</th>
            </tr>
            <tr>
                <th>Descripción</th>
                <th>{{ $almacen->descripcion }}</th>
            </tr>
            <tr>
                <th>Tipo de Almacén</th>
                <td>{{ $almacen->present()->tipo }}</td>
            </tr>
            <tr>
                <th>Tipo de Material</th>
                <td>
                    @if($almacen->material)
                        {{ $almacen->material->descripcion }}
                    @endif
                </td>
            </tr>
            <tr>
                <th>Categoria</th>
                <td>
                    @if($almacen->categoria)
                        {{ $almacen->categoria->descripcion }}
                    @endif
                </td>
            </tr>
            <tr>
                <th>Propiedad</th>
                <td>
                    @if($almacen->propiedad)
                        {{ $almacen->propiedad->descripcion }}
                    @endif
                </td>
            </tr>
            </tbody>
        </table>
    </div>

    <div class="panel panel-default">
        <div class="panel-heading">
            <h4>Equipos Ingresados
                <small><span class="glyphicon glyphicon-question-sign" data-toggle="tooltip" data-placement="right" title="Equipos que han entrado a este almacén." aria-hidden="true"></span></small>
            </h4>
        </div>
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Tipo de Material</th>
                    <th>Numero de Serie</th>
                    <th>Empresa</th>
                    <th>Fecha Entrada</th>
                    <th>Fecha Salida</th>
                </tr>
            </thead>
            <tbody>
                @foreach($almacen->equipos as $equipo)
                    <tr>
                        <td>{{ $equipo->material->descripcion}}</td>
                        <td>{{ $equipo->referencia }}</td>
                        <td>{{ $equipo->item->transaccion->empresa->razon_social }}</td>
                        <td>{{ $equipo->present()->fechaEntrada }}</td>
                        <td>{{ $equipo->present()->fechaSalida }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="panel panel-default" id="horas-mensuales">
        <div class="panel-heading">
            {!! link_to_route('horas-mensuales.create', 'Nuevo Registro', [$almacen], ['class' => 'btn btn-sm btn-success pull-right']) !!}
            <h4>Horas Mensuales
                <small><span class="glyphicon glyphicon-question-sign" data-toggle="tooltip" data-placement="right" title="Horas mensuales de los contratos" aria-hidden="true"></span></small>
            </h4>
        </div>
        @if(count($almacen->horasMensuales))
            @include('horas-mensuales.partials.horas-table', ['horas' => $almacen->horasMensuales])
        @else
            <div class="panel-body">
                <p class="alert alert-warning">
                    <span class="glyphicon glyphicon-warning-sign" aria-hidden="true"></span>
                    Este almacén no tiene horas mensuales registradas.
                </p>
            </div>
        @endif
    </div>
@stop