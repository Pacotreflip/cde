@extends('layout')

@section('content')
    <ol class="breadcrumb">
        <li><a href="{{ route('areas.index', ['area' => Request::get('dentro_de')]) }}">Areas</a></li>
        <li class="active">Nueva Area</li>
    </ol>

    <h1>Nueva Area</h1>
    <hr>
    
    {!! Form::open(['route' => ['areas.store'], 'method' => 'POST']) !!}
        @include('areas.partials.create-fields')
    {!! Form::close() !!}

    @include('partials.errors')
@stop