@extends('layout')

@section('content')
    <h1>Areas</h1>
    <hr>

    <ul class="list-group">
        @foreach($areas as $area)
            <a href="{{ route('areas.index', ['area='.$area->id]) }}" class="list-group-item">{{ $area->nombre }}</a>
        @endforeach
    </ul>
@stop