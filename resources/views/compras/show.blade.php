@extends('layout')

@section('content')
  <h1>Compra</h1>
  <hr>
  
  <p class="well">
    <strong>No. Folio:</strong>
    {{ $compra->numero_folio }} <br><br>

    <strong>Fecha:</strong>
    {{ $compra->fecha->format('d-m-Y') }} <span class="text-muted">({{ $compra->fecha->diffForHumans() }})</span> <br><br>
    
    <strong>Proveedor:</strong>
    {{ $compra->empresa->razon_social }} <br><br>

    <strong>Observaciones:</strong> <br>
    {{ $compra->observaciones }}
  </p>
  
  <h3>Artículos Adquiridos</h3>
  
  <div class="table-responsive">
    <table class="table table-striped table-hover table-condensed">
      <thead>
        <tr>
          <th>Descripción</th>
          <th>Unidad</th>
          <th>Adquirido</th>
          <th>Precio</th>
          <th>Importe</th>
          <th>Entrega</th>
          <th>Recibido</th>
          <th>% Recibido</th>
        </tr>
      </thead>
      <tbody>
          @foreach($compra->items as $item)
            @foreach($item->recepciones as $itemRecepcion)
              <tr>
                <td>{{ $item->material->descripcion }}</td>
                <td>{{ $item->unidad }}</td>
                <td>{{ $item->cantidad }}</td>
                <td>{{ $item->precio_unitario }}</td>
                <td>{{ $item->importe }}</td>
                <td>{{ $itemRecepcion->recepcion->fecha_recepcion->format('d-m-Y') }}</td>
                <td>{{ $item->cantidad_recibida }}</td>
                <td>
                  <div class="progress">
                    <div
                      class="progress-bar progress-bar-striped{{ round(($item->cantidad_recibida / $itemRecepcion->cantidad_recibida) * 100) == 100 ? ' progress-bar-success' : '' }}" 
                      role="progressbar"
                      aria-valuenow="{{ ($itemRecepcion->cantidad_recibida / $item->cantidad) * 100 }}"
                      aria-valuemin="0"
                      aria-valuemax="100"
                      style="min-width: 2.5em; width: {{ round(($item->cantidad_recibida / $itemRecepcion->cantidad_recibida) * 100) < 100 ?: 100 }}%;">
                      {{ round(($item->cantidad_recibida / $itemRecepcion->cantidad_recibida) * 100) }}%
                    </div>
                  </div>
                </td>
              </tr>
            @endforeach
          @endforeach
      </tbody>
    </table>
  </div>
@stop