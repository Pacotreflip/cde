@extends('layout')

@section('content')
    <h1>Proyectos</h1>
    <hr>

    <ul class="list-group">
        @foreach($obras->groupBy('databaseName') as $baseDatos => $obrasBd)
            <li class="list-group-item disabled">
                <i class="fa fa-database fa-fw"></i> <b>{{ $baseDatos }}</b>
            </li>
            @foreach($obrasBd as $obra)
                <a class="list-group-item" href="{{ route('context.set', [$obra->databaseName, $obra]) }}">
                    {{ mb_strtoupper($obra->nombre) }}
                </a>
            @endforeach
        @endforeach
    </ul>

    {!! $obras->render() !!}
@stop