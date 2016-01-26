@extends('layout')

@section('content')
  @include('areas-tipo.partials.breadcrumb')

  <h1>Lista de Artículos de Área Tipo: {{$tipo_origen->nombre}}</h1>
  <hr>
  
 
  <br>
    <br>
  {!! Form::open(['route' => ['requerimientos.copia.desde.area.store', $tipo]]) !!}
  <input name="idarea_tipo_origen" id="idarea_tipo_origen" type="hidden" value="{{$tipo_origen->id}}" />
    <table class="table table-striped table-condensed">
      <thead>
        <tr>
          <th>No. Parte</th>
          <th>Descripción</th>
          <th>Unidad</th>
          <th>Cantidad</th>
          <th>Precio</th>
          <th>Moneda</th>
          <th>Cantidad Comparativa</th>
          <th>Precio Comparativa</th>
          <th>Moneda Comparativa</th>
        </tr>
      </thead>
      <tbody>
        @foreach($articulos as $material)
          <tr>
            <td>{{ $material->numero_parte }}</td>
            <td>{{ $material->descripcion }}</td>
            <td>{{ $material->unidad }}</td>
            <td style="text-align: right">{{ $material->cantidad_requerida }}</td>
            <td style="text-align: right">{{ $material->precio }}</td>
            <td>{{ $material->moneda }}</td>
            <td style="text-align: right">{{ $material->cantidad_comparativa }}</td>
            <td style="text-align: right">{{ $material->precio_comparativa }}</td>
            <td>{{ $material->moneda_comparativa }}</td>
          </tr>
        @endforeach
      </tbody>
    </table>

    <div class="form-group">
      <input class="btn btn-primary" type="submit" value="Agregar Artículos">
    </div>
  {!! Form::close() !!}

@stop