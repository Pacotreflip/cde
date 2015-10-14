@extends('layout')

@section('content')
  <h1>Transferencia de Artículos</h1>
  <hr>
  
  <div class="row">
    <div class="col-sm-6">
      <div class="panel panel-default transaccion-detail">
        <div class="panel-heading">
          Detalle de la Transferencia
        </div>

        <div class="panel-body">
          <strong>No. Folio:</strong> #{{ $transferencia->numero_folio }} <br>
          <strong>Fecha:</strong> {{ $transferencia->fecha->format('d-M-Y h:m') }} <br>
          <strong>Observaciones:</strong> {{ $transferencia->observaciones }} <br>
          <strong>Creada Por:</strong> {{ $transferencia->creado_por }} <br>
        </div>
      </div>
    </div>
    <div class="col-sm-6">
      <div class="panel panel-default transaccion-detail">
        <div class="panel-heading">
          Area Origen
        </div>

        <div class="panel-body">
          @include('partials.path-area', ['area' => $transferencia->area])
        </div>
      </div>
    </div>
  </div>


  <h3>Articulos Transferidos</h3>
  <hr>
  <div class="table-responsive">
    <table class="table table-striped">
      <thead>
        <tr>
          <th>No. Parte</th>
          <th>Descripción</th>
          <th>Unidad</th>
          <th>Cantidad</th>
          <th>Area Destino</th>
        </tr>
      </thead>
      <tbody>
        @foreach($transferencia->items as $item)
          <tr>
            <td>{{ $item->material->numero_parte }}</td>
            <td>{{ $item->material->descripcion }}</td>
            <td>{{ $item->material->unidad }}</td>
            <td>{{ $item->cantidad }}</td>
            <td>
              @include('partials.path-area', ['area' => $item->destino])
            </td>
          </tr>
        @endforeach
      </tbody>
    </table>
  </div>
@stop