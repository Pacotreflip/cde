@extends('layout')

@section('content')
  <h1>Artículos
    <a href="{{ route('articulos.create') }}" class="btn btn-success pull-right">
      <i class="fa fa-plus"></i> Agregar Artículo
    </a>
  </h1>
  <hr>

  @include('partials.search-form')
  <table class="table table-striped table-hover table-condensed">
    <thead>
      <tr>
        <th>No. Parte</th>
        <th>Descripción</th>
        <th>Unidad</th>
        <th>Familia</th>
        <th>Clasificación</th>
      </tr>
    </thead>
    <tbody>
      @foreach($materiales as $material)
        <tr>
            <th>{{ $material->numero_parte }}</th>
            <td>
              <a href="{{ route('articulos.edit', [$material]) }}">{{ $material->descripcion }}</a>
            </td>
            <td>{{ $material->unidad }}</td>
            <td>
              @if($material->familia())
                  {{ $material->familia()->descripcion }}
              @endif
            </td>
            <td>
              @if($material->clasificador)
                  {{ $material->clasificador->nombre }}
              @endif
            </td>
        </tr>
      @endforeach
    </tbody>
  </table>

  {!! $materiales->appends(['buscar' => Request::get('buscar')])->render() !!}
@stop