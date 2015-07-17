@extends('app')

@section('content')
    <ol class="breadcrumb">
        <li><a href="{{ route('almacenes.index') }}">Almacenes</a></li>
        <li class="active">{{ $almacen->descripcion }}</li>
    </ol>

    <h1 class="page-header"><span class="fa fw fa-archive"></span> Almacén</h1>

    <div class="panel panel-default">
        <div class="panel-heading">
            <a href="{{ route('almacenes.edit', [$almacen]) }}" class="btn btn-sm btn-primary pull-right">
                <span class="fa fa-fw fa-pencil"></span> Modificar este almacén
            </a>
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
                    <th>Clasificación</th>
                    <td>{{ $almacen->clasificacion }}</td>
                </tr>
                <tr>
                    <th>Propiedad</th>
                    <td>{{ $almacen->propiedad }}</td>
                </tr>
            </tbody>
        </table>
    </div>

    @include('almacenes.partials.equipos')
    <br>
    @include('almacenes.partials.horas-mensuales')
@stop