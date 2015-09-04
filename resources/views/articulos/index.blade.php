@extends('layout')

@section('content')
    <h1>Articulos
        <a href="{{ route('articulos.create') }}" class="btn btn-success pull-right">Agregar Articulo</a>
    </h1>
    <hr>

    <div class="search-bar">
        {!! Form::open(['route' => ['articulos.index'], 'method' => 'GET']) !!}
            <div class="form-group">
                {!! Form::text('busqueda', null, ['class' => 'form-control', 'placeholder' => 'escriba algo para buscar']) !!}
            </div>
        {!! Form::close() !!}
    </div>

    <ul class="list-group">
        @foreach($articulos as $articulo)
            <a href="{{ route('articulos.edit', [$articulo]) }}" class="list-group-item">{{ $articulo->nombre }}</a>
        @endforeach
    </ul>

    {{ $articulos->render() }}
@stop