@extends('areas-tipo.layout')

@section('main-content')
  
  <div class="row">
    <div class="col-md-6">
      <div class="panel panel-default">
        <div class="panel-heading text-center">
          <strong># de Areas Asignadas</strong>
        </div>
        <div class="panel-body">
          <p class="h2 text-center">
            {{ $tipo->conteoAreas() }}
          </p>
        </div>
      </div>
    </div>

    <div class="col-md-6">
      <div class="panel panel-default">
        <div class="panel-heading text-center">
          <strong>Costo Estimado por Area</strong>
        </div>
        <div class="panel-body">
          <p class="h2 text-center">
            {{ $tipo->costoEstimado() }}
          </p>
        </div>
      </div>
    </div>
  </div>
  <hr>
  
  <h4>Areas Asignadas</h4>
  <ul class="list-group">
    @foreach($tipo->areas as $area)
      <a href="{{ route('areas.edit', $area) }}" class="list-group-item">
        {{ $area->ruta() }}
      </a>
    @endforeach
  </ul>
@stop
