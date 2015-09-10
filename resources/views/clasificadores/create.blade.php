@extends('layout')

@section('content')
    <ol class="breadcrumb">
        <li><a href="{{ route('clasificadores.index', Request::has('dentro_de') ? ['clasificador' => Request::get('dentro_de')] : []) }}">Clasificadores de Articulo</a></li>
        <li class="active">Nuevo Clasificador</li>
    </ol>

    <div class="row">
        <div class="col-md-6">
            <h1>Nuevo Clasificador</h1>
            <hr>

            {!! Form::open(['route' => ['clasificadores.store'], 'method' => 'POST']) !!}
                @include('clasificadores.partials.fields')
                <hr>

                <div class="form-group">
                    {!! Form::submit('Guardar', ['class' => 'btn btn-primary']) !!}
                </div>

                @include('partials.errors')

            {!! Form::close() !!}
        </div>
    </div>
@stop