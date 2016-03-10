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
  <div class="row">
      <div class="col-md-6">
      <h1>Asignados</h1>
<hr>
<br>

<table class="table table-condensed table-striped">
    <thead>
        <tr>
            <th>Área</th>  
            <th>Cantidad</th>
        </tr>
    </thead>
    <tbody>
        
        
        <tr>
        </tr>
    </tbody>
</table>

    </div>
      <div class="col-md-6">
      <h1>Almacenados</h1>
<hr>
<br>
<table class="table table-condensed table-striped">
    <thead>
        <tr>
            <th>Área</th>  
            <th>Cantidad</th>
        </tr>
    </thead>
    <tbody>
        
        
        <tr>
        </tr>
    </tbody>
</table>
    </div>
  </div>
  
@stop

@section('scripts')
  <script>
    Dropzone.options.dropzone = {
      paramName: "foto",
      dictDefaultMessage: "Arrastra fotografias aqui para subirlas"
    };
  </script>
@stop