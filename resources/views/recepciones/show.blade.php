@extends('layout')

@section('content')
  <h1>Recepción de Artículos <small># {{ $recepcion->numero_folio }}</small></h1>

  <h2>
    <small>
      <a href="{{ route('compras.show', [$recepcion->compra]) }}">Orden de Compra # {{ $recepcion->compra->numero_folio }}</a>
    </small></h2>
  <hr>
  <div class="row recepcion">
    <div class="col-sm-4">
      <div class="panel panel-default transaccion-detail">
        <div class="panel-heading">
            Detalles de la Recepción
        </div>
        <div class="panel-body">
          <strong>Proveedor:</strong> {{ $recepcion->empresa->razon_social }} <br>
          <strong>Fecha Recepción:</strong> {{ $recepcion->fecha_recepcion->format('Y-m-d h:m') }} 
            <small class="text-muted">({{ $recepcion->created_at->diffForHumans() }})</small> <br>
          <strong>Persona que Recibió:</strong> {{ $recepcion->persona_recibe }} <br>
          <strong>Observaciones:</strong> {{ $recepcion->observaciones }} <br>
        </div>
      </div>
    </div>
    <div class="col-sm-4">
      <div class="panel panel-default transaccion-detail">
        <div class="panel-heading">
            Referencias
        </div>
        <div class="panel-body">
          <strong>Referencia Documento:</strong> {{ $recepcion->referencia_documento }} <br>
          <strong>Orden de Embarque:</strong> {{ $recepcion->orden_embarque }} <br>
          <strong>Numero de Pedido:</strong> {{ $recepcion->numero_pedido }} <br>
        </div>
      </div>
    </div>
    <div class="col-sm-4">
      <div class="panel panel-default transaccion-detail">
        <div class="panel-heading">
          Area de Almacenamiento
        </div>
        <div class="panel-body">
          @include('partials.path-area', ['area' => $recepcion->area])
        </div>
      </div>
    </div>
  </div>
  
  <hr>

  <h3>Artículos Recibidos</h3>
  <table class="table table-striped">
    <thead>
      <tr>
        <th>No. Parte</th>
        <th>Descripción</th>
        <th>Unidad</th>
        <th>Cantidad</th>
      </tr>
    </thead>
    <tbody>
      @foreach($recepcion->items as $item)
        <tr>
          <td>{{ $item->material->numero_parte }}</td>
          <td>{{ $item->material->descripcion }}</td>
          <td>{{ $item->material->unidad }}</td>
          <td>{{ $item->cantidad }}</td>
        </tr>
      @endforeach
    </tbody>
  </table>
@stop