@extends('layout')

@section('content')
  <h1>Adquisición</h1>
  <hr>
  
  <section class="form-horizontal well">
    <div class="form-group">
      <label class="col-sm-2 control-label">No. Folio:</label>
      <div class="col-sm-10">
        <p class="form-control-static">{{ $orden->numero_folio }}</p>
      </div>
    </div>

    <div class="form-group">
      <label class="col-sm-2 control-label">Fecha:</label>
      <div class="col-sm-10">
        <p class="form-control-static">
          {{ $orden->fecha->format('d-m-Y') }}
          <span class="text-muted">({{ $orden->fecha->diffForHumans() }})</span></p>
      </div>
    </div>

    <div class="form-group">
      <label class="col-sm-2 control-label">Proveedor:</label>
      <div class="col-sm-10">
        <p class="form-control-static">{{ $orden->empresa->razon_social }}</p>
      </div>
    </div>

    <div class="form-group">
      <label class="col-sm-2 control-label">Observaciones:</label>
      <div class="col-sm-10">
        <p class="form-control-static">{{ $orden->observaciones }}</p>
      </div>
    </div>
  </section>
  
  <hr>

  <h3>Artículos Adquiridos</h3>

  <table class="table table-striped table-hover">
    <thead>
      <tr>
        <th>Descripción</th>
        <th>Unidad</th>
        <th>Adquirido</th>
        <th>Precio</th>
        <th>Importe</th>
        <th>Entrega</th>
        <th>Recibido</th>
      </tr>
    </thead>
    <tbody>
        @foreach($orden->items as $item)
          @foreach($item->entregas as $entrega)
          <tr>
            <td>{{ $item->material->descripcion }}</td>
            <td>{{ $item->unidad }}</td>
            <td>{{ $entrega->cantidad }}</td>
            <td>{{ $item->precio_unitario }}</td>
            <td>{{ $item->importe }}</td>
            <td>{{ $entrega->fecha->format('d-m-Y') }}</td>
            <td>{{ $item->cantidad_recibida }}</td>
          </tr>
          @endforeach
        @endforeach
    </tbody>
  </table>
@stop