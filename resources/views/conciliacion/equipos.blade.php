@extends('layouts.default')

@section('content')

    <ol class="breadcrumb">
        <li class="active">{{ $proveedor->razon_social }}</li>
    </ol>

    <div class="panel panel-success">
        <div class="panel-heading">
            <h3 class="panel-title">Equipos</h3>
        </div>

        <ul class="list-group">
            @forelse($equipos as $equipo)
                {!! link_to_route('conciliacion.index', $equipo->descripcion, [$proveedor->id_empresa, $equipo->id_almacen], ['class' => 'list-group-item']) !!}
            @empty
                <li class="list-group-item text-danger">No se encontraron equipos.</li>
            @endforelse
        </ul>
    </div>
@stop