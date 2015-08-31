@extends('layout')

@section('content')
    <ol class="breadcrumb">
        <li><a href="{{ route('tipos.index') }}">Tipos de Area</a></li>
        <li><a href="{{ route('tipos.edit', [$tipo]) }}">{{ $tipo->nombre }}</a></li>
        <li class="active">Subtipos</li>
        <li class="active">Agregar Subtipo</li>
    </ol>

    <div class="row">
        <div class="col-md-6">
            <h1>Nuevo Subtipo de Area</h1>
            <hr>

            {!! Form::open(['route' => ['subtipos.store', $tipo], 'method' => 'POST']) !!}
                @include('subtipos.partials.fields')
                <hr>

                <div class="form-group">
                    {!! Form::submit('Guardar', ['class' => 'btn btn-primary']) !!}
                </div>
            {!! Form::close() !!}

            @include('partials.errors')
        </div>
    </div>
@stop