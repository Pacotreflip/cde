@extends('layout')

@section('content')
  <h1>Recepción de Artículos <small># {{ $recepcion->numero_folio }}</small></h1>

  <h2>
    <small>
      <a href="{{ route('adquisiciones.show', [$recepcion->ordenCompra]) }}">Orden de Compra # {{ $recepcion->ordenCompra->numero_folio }}</a>
    </small></h2>
  <hr>
  <div class="row recepcion">
    <div class="col-sm-4">
      <div class="panel panel-default recepcion-detail">
        <div class="panel-heading">
            Detalles de la Recepción
        </div>
        <div class="panel-body">
          <strong>Proveedor:</strong> {{ $recepcion->empresa->razon_social }} <br>
          <strong>Fecha Recepción:</strong> {{ $recepcion->fecha_recepcion->format('Y-m-d') }} 
            <span class="text-muted">({{ $recepcion->created_at->diffForHumans() }})</span> <br>
          <strong>Persona que Recibió:</strong> {{ $recepcion->persona_recibe }} <br>
          <strong>Observaciones:</strong> {{ $recepcion->observaciones }} <br>
        </div>
      </div>
    </div>
    <div class="col-sm-4">
      <div class="panel panel-default recepcion-detail">
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
      <div class="panel panel-default recepcion-detail">
        <div class="panel-heading">
          Area de Almacenamiento
        </div>
        <div class="panel-body">
          @include('recepciones.partials.path-almacenamiento', ['area' => $recepcion->area])
        </div>
      </div>
    </div>
  </div>
  
  <hr>

  <h3 class="text-center">Artículos Recibidos</h3>
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
      @foreach($recepcion->articulos as $articulo)
        <tr>
          <td>{{ $articulo->numero_parte }}</td>
          <td>{{ $articulo->descripcion }}</td>
          <td>{{ $articulo->unidad }}</td>
          <td>{{ $articulo->pivot->cantidad }}</td>
        </tr>
      @endforeach
    </tbody>
  </table>
@stop