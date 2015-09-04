@extends('layout')

@section('content')
    <h1>Nuevo Articulo</h1>
    <hr>

    {!! Form::open(['route' => ['articulos.store'], 'method' => 'POST', 'files' => true]) !!}
        <!-- Nombre Form Input -->
        <div class="form-group">
            {!! Form::label('nombre', 'Nombre:') !!}
            {!! Form::text('nombre', null, ['class' => 'form-control']) !!}
        </div>

        <!-- Numero de Parte Form Input -->
        <div class="form-group">
            {!! Form::label('numero_parte', 'Numero de Parte:') !!}
            {!! Form::text('numero_parte', null, ['class' => 'form-control']) !!}
        </div>

        <div class="row">
            <div class="col-xs-6">
                <!-- Unidad Form Input -->
                <div class="form-group">
                    {!! Form::label('unidad', 'Unidad:') !!}
                    {!! Form::select('unidad', $unidades, null, ['class' => 'form-control']) !!}
                </div>
            </div>
            <div class="col-xs-6">
                <!-- Unidad Form Input -->
                <div class="form-group">
                    {!! Form::label('nueva_unidad', 'Nueva Unidad:') !!}
                    {!! Form::text('nueva_unidad', null, ['class' => 'form-control', 'placeholder' => 'Escriba el nombre de la nueva unidad']) !!}
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-xs-6">
                <!-- Clasificador Form Input -->
                <div class="form-group">
                    {!! Form::label('clasificador', 'Clasificador:') !!}
                    {!! Form::select('clasificador', $clasificadores, null, ['class' => 'form-control']) !!}
                </div>
            </div>
            <div class="col-xs-6">
                <!-- Nuevo Clasificador Form Input -->
                <div class="form-group">
                    {!! Form::label('nuevo_clasificador', 'Nuevo Clasificador:') !!}
                    {!! Form::text('nuevo_clasificador', null, ['class' => 'form-control', 'placeholder' => 'Escriba el nombre del nuevo clasificador']) !!}
                </div>
            </div>
        </div>

        <!-- Descripcion Form Input -->
        <div class="form-group">
            {!! Form::label('descripcion', 'Descripcion:') !!}
            {!! Form::textarea('descripcion', null, ['class' => 'form-control', 'rows' => 3]) !!}
        </div>

        <!-- Ficha Tecnica Form Input -->
        <div class="form-group">
            {!! Form::label('ficha_tecnica', 'Ficha Tecnica:') !!}
            {!! Form::file('ficha_tecnica', null, ['class' => 'form-control']) !!}
        </div>
        <hr>
        <div class="form-group">
            {!! Form::submit('Guardar', ['class' => 'btn btn-primary']) !!}
        </div>
    {!! Form::close() !!}

    @include('partials.errors')
@stop