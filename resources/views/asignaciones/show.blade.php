@extends('layout')

@section('content')
  <h1>Asignación de Artículos - <span># {{ $asignacion->numero_folio }}</span></h1>
  
  <hr>
  <div class="row recepcion">
    <div class="col-sm-6">
      <div class="panel panel-default transaccion-detail">
        <div class="panel-heading">
            Detalles de la Asignación
        </div>
        <div class="panel-body">
          <strong>Fecha Asignación:</strong> {{ $asignacion->fecha_asignacion->format('Y-m-d h:m') }} 
            <small class="text-muted">({{ $asignacion->created_at->diffForHumans() }})</small> <br>
          <strong>Persona que Asigna:</strong> {{ $asignacion->creado_por }} <br>
          <strong>Persona que Valida:</strong>  <br>
          <strong>Observaciones:</strong> {{ $asignacion->observaciones }} <br>
        </div>
      </div>
    </div>
    <div class="col-sm-6">
      <div class="panel panel-default transaccion-detail">
        <div class="panel-heading">
            Referencias
        </div>
        <div class="panel-body">
          <strong>No. de Recepción:</strong> #{{ $asignacion->recepcion->numero_folio }} <br>
        </div>
      </div>
    </div>
  </div>
  
  <hr>

  <h3>Artículos Asignados</h3>
  <table class="table table-striped">
    <thead>
      <tr>
        <th>No. Parte</th>
        <th>Descripción</th>
        <th>Unidad</th>
        <th>Cantidad Asignada</th>
        <th>Área Origen</th>
        <th>Área Destino</th>
      </tr>
    </thead>
    <tbody>
      @foreach($asignacion->items as $item)
        <tr>
          <td>{{ $item->material->numero_parte }}</td>
          <td>
            <a href="{{ route('articulos.edit', $item->material) }}">{{ $item->material->descripcion }}</a>
          </td>
          <td>{{ $item->material->unidad }}</td>
          <td>{{ $item->cantidad_asignada }}</td>
          <td>
            @if ($item->area_origen)
              <a href="{{ route('areas.edit', $item->area_origen) }}">{{ $item->area_origen->ruta() }}</a>
            @else
              N/A
            @endif
          </td>
          <td>
            @if ($item->area_destino)
              <a href="{{ route('areas.edit', $item->area_destino) }}">{{ $item->area_destino->ruta() }}</a>
            @else
              N/A
            @endif
          </td>
        </tr>
      @endforeach
    </tbody>
  </table>
@stop