@extends('app')

@section('content')
    <ol class="breadcrumb">
        <li><a href="{{ route('conciliacion.proveedores') }}">Proveedores</a></li>
        <li>{{ $empresa->razon_social }}</li>
    </ol>

    <h1 class="page-header"><i class="fa fa-archive"></i> Almacenes</h1>

    <div class="panel panel-default">
        <ul class="list-group">
            @forelse($almacenes as $almacen)
                {!! link_to_route('conciliacion.index', $almacen->descripcion, [$empresa->id_empresa, $almacen->id_almacen], ['class' => 'list-group-item']) !!}
            @empty
                <li class="list-group-item text-danger">No se encontraron almacenes.</li>
            @endforelse
        </ul>
    </div>
@stop