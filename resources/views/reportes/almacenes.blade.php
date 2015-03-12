@extends('app')

@section('content')
    <ol class="breadcrumb">
        <li class="active">Almacenes</li>
    </ol>

    <h1>Almacenes</h1>

    @if(count($almacenes))
        <div class="panel-default">
            <ul class="list-group">
                @foreach($almacenes as $almacen)
                    {!! link_to_route('reportes.index', $almacen->descripcion, [$almacen->id_almacen], ['class' => 'list-group-item']) !!}
                @endforeach
            </ul>
        </div>
    @else
        <p class="alert alert-warning">No se encontraron almacenes registrados.</p>
    @endif

    {!! $almacenes->render() !!}
@endsection