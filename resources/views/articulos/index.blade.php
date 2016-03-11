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
        <th>% Suministro</th>
        <th>% Asignacion</th>
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
            <td>
              @if($material->getTotalEsperado($idobra) > 0)
                
                
                <div class="progress">
                    <div
                      class="progress-bar progress-bar-striped{{ round($material->porcentaje_suministro($idobra)) == 100 ? ' progress-bar-success' : '' }}" 
                      role="progressbar"
                      aria-valuenow="{{ $material->porcentaje_suministro($idobra) }}"
                      aria-valuemin="0"
                      aria-valuemax="100"
                      style="min-width: 2.5em; width: {{ round($material->porcentaje_suministro($idobra)) }}%;">
                      {{ round($material->porcentaje_suministro($idobra)) }}%
                    </div>
                  </div>
          
                @endif
            </td>
            <td>
              @if($material->cantidad_esperada() > 0)
          
                   
                   <div class="progress">
                    <div
                      class="progress-bar progress-bar-striped{{ round($material->porcentaje_asignacion()) == 100 ? ' progress-bar-success' : '' }}" 
                      role="progressbar"
                      aria-valuenow="{{ $material->porcentaje_asignacion() }}"
                      aria-valuemin="0"
                      aria-valuemax="100"
                      style="min-width: 2.5em; width: {{ round($material->porcentaje_asignacion()) }}%;">
                      {{ round($material->porcentaje_asignacion()) }}%
                    </div>
                  </div>
                   
                   
                   
                   @endif
            </td>
        </tr>
      @endforeach
    </tbody>
  </table>

  {!! $materiales->appends(['buscar' => Request::get('buscar')])->render() !!}
@stop