@extends('app')

@section('content')
    <h1 class="page-header">
        <i class="fa fa-list-ul"></i> Almacenes de Maquinaria
    </h1>

    @if(count($almacenes))
        <div class="panel-default">
            <ul class="list-group">
                @foreach($almacenes as $almacen)
                    {!! link_to_route('almacenes.show', $almacen->descripcion, [$almacen], ['class' => 'list-group-item']) !!}
                @endforeach
            </ul>
        </div>
    @else
        <p class="alert alert-warning">No se encontraron almacenes registrados.</p>
    @endif

    {!! $almacenes->render() !!}
@stop