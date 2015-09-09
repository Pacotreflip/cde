@extends('layout')

@section('content')
    <ol class="breadcrumb">
        <li><a href="{{ route('tipos.index', ['tipo' => Request::get('dentro_de')]) }}">Tipos de Area</a></li>
        <li class="active">Nuevo Tipo</li>
    </ol>

    <div class="row">
        <div class="col-md-6">
            <h1>Nuevo Tipo de Area</h1>
            <hr>

            {!! Form::open(['route' => ['tipos.store'], 'method' => 'POST']) !!}
                @include('tipos.partials.fields')
                <hr>

                <div class="form-group">
                    {!! Form::submit('Guardar', ['class' => 'btn btn-primary']) !!}
                </div>

                @include('partials.errors')

            {!! Form::close() !!}
        </div>
    </div>
@stop