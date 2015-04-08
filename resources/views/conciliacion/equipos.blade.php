@extends('app')

@section('content')
    <ol class="breadcrumb">
        <li><a href="">Conciliación</a></li>
        <li>{!! link_to_route('conciliacion.almacenes', $empresa->razon_social, [$empresa->id_empresa]) !!}</li>
    </ol>

    <h1 class="page-header">Almacenes</h1>

    <div class="panel panel-success">
        <ul class="list-group">
            @forelse($almacenes as $almacen)
                {!! link_to_route('conciliacion.index', $almacen->descripcion, [$empresa->id_empresa, $almacen->id_almacen], ['class' => 'list-group-item']) !!}
            @empty
                <li class="list-group-item text-danger">No se encontraron almacenes.</li>
            @endforelse
        </ul>
    </div>
@endsection