@extends('layout')

@section('content')
  <ol class="breadcrumb">
    <li><a href="{{ route('articulos.index') }}">Artículos</a></li>
    <li class="active">Nuevo Artículo</li>
  </ol>

  <h1>Nuevo Artículo</h1>
  <hr>

  {!! Form::open(['route' => ['articulos.store'], 'method' => 'POST', 'files' => true]) !!}
    <!-- Descripción Form Input -->
    <div class="form-group">
      {!! Form::label('descripcion', 'Descripción:') !!}
      {!! Form::text('descripcion', null, ['class' => 'form-control']) !!}
    </div>
    
    <div class="row">
      <div class="col-xs-6">
        <!-- Numero de Parte Form Input -->
        <div class="form-group">
          {!! Form::label('numero_parte', 'Numero de Parte:') !!}
          {!! Form::text('numero_parte', null, ['class' => 'form-control']) !!}
        </div>
      </div>
      <div class="col-xs-6">
        <!-- Familia Form Input -->
        <div class="form-group">
          {!! Form::label('id_familia', 'Familia:') !!}
          {!! Form::select('id_familia', $familias, null, ['class' => 'form-control']) !!}
        </div>
      </div>
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
          {!! Form::text('nueva_unidad', null, ['class' => 'form-control', 
            'placeholder' => 'Escriba el nombre de la nueva unidad']) !!}
        </div>
      </div>
    </div>
    
    <!-- Clasificador Form Input -->
    <div class="form-group">
      {!! Form::label('id_clasificador', 'Clasificador:') !!}
      {!! Form::select('id_clasificador', $clasificadores, null, ['class' => 'form-control']) !!}
    </div>

    <!-- Descripción Larga Form Input -->
    <div class="form-group">
      {!! Form::label('descripcion_larga', 'Descripción Larga:') !!}
      {!! Form::textarea('descripcion_larga', null, ['class' => 'form-control', 'rows' => 3]) !!}
    </div>

    <!-- Ficha Tecnica Form Input -->
    <div class="form-group">
      {!! Form::label('ficha_tecnica', 'Ficha Técnica:') !!}
      {!! Form::file('ficha_tecnica', null, ['class' => 'form-control']) !!}
    </div>
    
    <hr>
    
    <div class="form-group">
      {!! Form::submit('Guardar', ['class' => 'btn btn-primary']) !!}
    </div>
  {!! Form::close() !!}

  @include('partials.errors')
@stop