@extends('layout')

@section('content')
  <ol class="breadcrumb">
    <li><a href="{{ route('articulos.index') }}">Artículos</a></li>
    <li class="active">{{ $material->descripcion }}</li>
  </ol>

  <h1>Artículo</h1>
  <hr>

  <div class="row">
    <div class="col-md-6">
      @include('partials.errors')
      {!! Form::model($material, ['route' => ['articulos.update', $material], 'method' => 'PATCH', 'files' => true]) !!}
        @include('articulos.partials.edit-fields')
      {!! Form::close() !!}
      <br>
    </div>
    <div class="col-md-6 gallery">
      @include('articulos.partials.fotos')
    </div>
  </div>

  <hr>

  <form action="{{ route('articulos.fotos', [$material]) }}" class="dropzone" 
    id="dropzone" method="POST" enctype="multipart/form-data">
      {{ csrf_field() }}
  </form>

  <br>
@stop

@section('scripts')
  <script>
    Dropzone.options.dropzone = {
      paramName: "foto",
      dictDefaultMessage: "Arrastra fotografias aqui para subirlas"
    };
  </script>
@stop