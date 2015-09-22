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
        <p class="form-control-static">{{ $orden->fecha->format('d-m-Y') }}</p>
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

  <h3>Artículos</h3>

  <table class="table table-striped table-hover">
    <thead>
      <tr>
        <th>Descripción</th>
        <th>Unidad</th>
        <th>Cantidad</th>
        <th>Fecha de Entrega</th>
      </tr>
    </thead>
    <tbody>
        @foreach($orden->items as $item)
          @foreach($item->entregas as $entrega)
          <tr>
            <td>{{ $item->material->descripcion }}</td>
            <td>{{ $item->unidad }}</td>
            <td>{{ $entrega->cantidad }}</td>
            <td>{{ $entrega->fecha->format('d-m-Y') }}</td>
          </tr>
          @endforeach
        @endforeach
    </tbody>
  </table>
@stop