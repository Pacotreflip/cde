@extends('app')

@section('content')
    <div class="alert alert-info alert-dismissible" role="alert">
        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <strong>Hola!</strong> Actualmente solo tienes acceso a estas obras.
    </div>

    <h1>Obras</h1>

    <div class="list-group">
        @foreach($obras as $obra)
            <a class="list-group-item" href="{{ route('context.set', [$obra->databaseName, $obra->id_obra]) }}">{{ $obra->nombre }}</a>
        @endforeach
    </div>
@endsection