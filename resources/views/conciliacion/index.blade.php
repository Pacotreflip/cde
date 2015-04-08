@extends('app')

@section('content')
    <ol class="breadcrumb">
        <li><a href="">Conciliación</a></li>
        <li>{!! link_to_route('conciliacion.almacenes', $empresa->razon_social, [$empresa->id_empresa]) !!}</li>
        <li class="active">{{ $almacen->descripcion }}</li>
    </ol>

    <div>
        {!! link_to_route('conciliacion.conciliar', 'Nueva conciliación', [$empresa->id_empresa, $almacen->id_almacen], ['class' => 'btn btn-primary pull-right']) !!}
        <h1 class="page-header">Conciliaciones</h1>
    </div>

    <div class="panel panel-default">
        <ul class="list-group">
            @foreach($conciliaciones as $conciliacion)
                <a class="list-group-item" href="{!! route('conciliacion.edit', [$empresa->id_empresa, $almacen->id_almacen, $conciliacion->id]) !!}">
                    <span>{{ $conciliacion->present()->periodo }}</span>

                    {!! $conciliacion->present()->statusLabel !!}
                </a>
            @endforeach
        </ul>
    </div>
@endsection
