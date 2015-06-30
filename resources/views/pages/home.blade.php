@extends('app')

@section('content')
    @unless(Auth::check())
        <div class="jumbotron">
            <h1>Bienvenido a Grupo Hermes!</h1>
        </div>
    @endunless
@stop