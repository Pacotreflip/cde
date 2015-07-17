@extends('app')

@section('content')
    <ol class="breadcrumb">
        <li><a href="{{ route('conciliacion.proveedores') }}">Proveedores</a></li>
        <li><a href="{{ route('conciliacion.almacenes', [$empresa]) }}">{{ $empresa->razon_social }}</a></li>
        <li class="active">{{ $almacen->descripcion }}</li>
    </ol>

    <div>
        <a href="{{ route('conciliacion.conciliar', [$empresa, $almacen]) }}" class="btn btn-sm btn-success pull-right">
            <i class="fa fa-fw fa-plus"></i> Nueva Conciliación
        </a>
        <h1 class="page-header"><i class="fa fa-fw fa-calculator"></i> Conciliaciones</h1>
    </div>

    <div class="panel panel-default">
        <ul class="list-group">
            @foreach($conciliaciones as $conciliacion)
                <a class="list-group-item" href="{!! route('conciliacion.edit', [$empresa, $almacen, $conciliacion]) !!}">
                    <span>{{ $conciliacion->present()->periodo }}</span>

                    {!! $conciliacion->present()->statusLabel !!}
                </a>
            @endforeach
        </ul>
    </div>
@stop
