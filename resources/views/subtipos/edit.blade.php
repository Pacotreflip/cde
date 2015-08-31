@extends('layout')

@section('content')
    <ol class="breadcrumb">
        <li><a href="{{ route('tipos.index') }}">Tipos de Area</a></li>
        <li><a href="{{ route('tipos.edit', [$tipo]) }}">{{ $tipo->nombre }}</a></li>
        <li class="active">Subtipos</li>
        <li class="active">{{ $subtipo->nombre }}</li>
    </ol>

    <div class="row">
        <div class="col-md-6">
            <h1>Subtipo de Area</h1>
            <hr>

            {!! Form::model($subtipo, ['route' => ['subtipos.update', $tipo, $subtipo], 'method' => 'PATCH']) !!}
                @include('subtipos.partials.fields')
                <hr>

                <div class="form-group">
                    {!! Form::submit('Guardar Cambios', ['class' => 'btn btn-primary']) !!}
                </div>
            {!! Form::close() !!}

            @include('partials.errors')

            <br>
            <hr>
            {!! Form::open(['route' => ['subtipos.delete', $tipo, $subtipo], 'method' => 'DELETE']) !!}
                <div class="form-group">
                    {!! Form::submit('Borrar este subtipo', ['class' => 'btn btn-sm btn-danger pull-right']) !!}
                </div>
            {!! Form::close() !!}
        </div>
    </div>
@stop