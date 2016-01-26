@extends('layout')

@section('content')
  @include('areas-tipo.partials.breadcrumb')

  <h1>Selección de Área</h1>
  <hr>
  
  @include('partials.search-form')
  <br>
@include('partials.errors')
  {!! Form::open(['route' => ['requerimientos.articulos.area.seleccionada', $tipo]]) !!}
    <table class="table table-striped table-condensed">
      <thead>
        <tr>
          <th></th>
          <th>Clave</th>
          <th>Nombre</th>
          <th>Descripción</th>
        </tr>
      </thead>
      <tbody>
        @foreach($tipos_area as $tipo_area)
          <tr>
            <td><input type="radio" name="id_tipo_area" value="{{ $tipo_area->id }}"></td>
            <td>{{ $tipo_area->clave }}</td>
            <td>{{ $tipo_area->getRutaAttribute()  }}<span class="badge" style="margin-left: 5px">{{ $tipo_area->conteoMateriales() }}</span></td>
            <td>{{ $tipo_area->descripcion }}</td>
          </tr>
        @endforeach
      </tbody>
    </table>

    <div class="form-group">
      <input class="btn btn-primary" type="submit" value="Continuar">
    </div>
  {!! Form::close() !!}

  {!! $tipos_area->appends(['buscar' => Request::get('buscar')])->render() !!}
   
@stop