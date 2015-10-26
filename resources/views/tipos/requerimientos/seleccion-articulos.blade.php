@extends('layout')

@section('content')
  @include('tipos.partials.breadcrumb')

  <h1>Selección de Artículos Requeridos</h1>
  <hr>
  
  @include('partials.search-form')
  <br>

  {!! Form::open(['route' => ['requerimientos.store', $tipo]]) !!}
    <table class="table table-striped table-condensed">
      <thead>
        <tr>
          <th></th>
          <th>No. Parte</th>
          <th>Descripción</th>
          <th>Unidad</th>
        </tr>
      </thead>
      <tbody>
        @foreach($articulos as $material)
          <tr>
            <td><input type="checkbox" name="materiales[]" value="{{ $material->id_material }}"></td>
            <td>{{ $material->numero_parte }}</td>
            <td><a href="{{ route('articulos.edit', [$material]) }}">{{ $material->descripcion }}</a></td>
            <td>{{ $material->unidad }}</td>
          </tr>
        @endforeach
      </tbody>
    </table>

    <div class="form-group">
      <input class="btn btn-primary" type="submit" value="Agregar artículos seleccionados">
    </div>
  {!! Form::close() !!}

  {!! $articulos->appends(['buscar' => Request::get('buscar')])->render() !!}
@stop