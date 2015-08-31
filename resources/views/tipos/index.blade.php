@extends('layout')

@section('content')
    <h1>Tipos de Area
        <a href="{{ route('tipos.create') }}" class="btn btn-success pull-right"><i class="fa fa-plus"></i> Nuevo Tipo</a>
    </h1>
    <hr>

    <ul class="list-group">
        @foreach($tipos as $tipo)
            <a href="{{ route('tipos.edit', [$tipo]) }}" class="list-group-item">{{ $tipo->nombre }}</a>
        @endforeach
    </ul>

    {!! $tipos->render() !!}
@stop