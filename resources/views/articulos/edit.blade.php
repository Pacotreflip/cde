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
      @if(count($material->areas_asignacion()))
      <div class="col-md-6">
      <h2>Asignados</h2>
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
        
        @foreach($material->areas_asignacion() as $area)
        <tr>
            <td>{{$area->nombre}}</td>
            <td style="text-align: right">{{$area->cantidad_asignada($material->id_material)}}</td>
        </tr>
        @endforeach
    </tbody>
</table>

    </div>
      @endif
      @if(count($material->areas_almacenacion()))
      
      
      <div class="col-md-6">
      <h2>Almacenados</h2>
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
        @foreach($material->areas_almacenacion() as $area)
        <tr>
            <td>{{$area->nombre}}</td>
            <td style="text-align: right">{{$area->getInventarioDeMaterial($material)->cantidad_existencia}}</td>
        </tr>
        @endforeach
    </tbody>
</table>
    </div>
      @endif
  </div>
  
@stop

@section('scripts')
  <script>
    Dropzone.options.dropzone = {
      paramName: "foto",
      dictDefaultMessage: "<h2 style='color:#bbb'><span class='glyphicon glyphicon-picture' style='padding-right:5px'></span>Arraste las fotografías a esta zona para asociarlas al artículo.</h2>"
    };
  </script>
@stop