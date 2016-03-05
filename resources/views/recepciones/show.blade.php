@extends('layout')

@section('content')
  <h1>Recepción de Artículos - <span># {{ $recepcion->numero_folio }}</span></h1>

  <h2>
    <small>
      <a href="{{ route('compras.show', [$recepcion->compra]) }}">Orden de Compra # {{ $recepcion->compra->numero_folio }}</a>
    </small></h2>
  <hr>
  <div class="row recepcion">
    <div class="col-sm-6">
      <div class="panel panel-default transaccion-detail">
        <div class="panel-heading">
            Detalles de la Recepción
        </div>
        <div class="panel-body">
          <strong>Proveedor:</strong> {{ $recepcion->empresa->razon_social }} <br>
          <strong>Fecha Recepción:</strong> {{ $recepcion->fecha_recepcion->format('Y-m-d h:m') }} 
            <small class="text-muted">({{ $recepcion->created_at->diffForHumans() }})</small> <br>
          <strong>Persona que Recibió:</strong> {{ $recepcion->persona_recibio }} <br>
          <strong>Persona que Registró:</strong> {{ $recepcion->usuario_registro->present()->nombreCompleto }} <br>
          <strong>Observaciones:</strong> {{ $recepcion->observaciones }} <br>
        </div>
      </div>
    </div>
    <div class="col-sm-6">
      <div class="panel panel-default transaccion-detail">
        <div class="panel-heading">
            Referencias
        </div>
        <div class="panel-body">
          <strong>No. de Remisión o Factura:</strong> {{ $recepcion->numero_remision_factura }} <br>
          <strong>Orden de Embarque:</strong> {{ $recepcion->orden_embarque }} <br>
          <strong>Numero de Pedimento:</strong> {{ $recepcion->numero_pedimento }} <br>
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
        <th>Cantidad Recibida</th>
        <th>Area Destino</th>
      </tr>
    </thead>
    <tbody>
      @foreach($recepcion->items as $item)
        <tr>
          <td>{{ $item->material->numero_parte }}</td>
          <td>
            <a href="{{ route('articulos.edit', $item->material) }}">{{ $item->material->descripcion }}</a>
          </td>
          <td>{{ $item->material->unidad }}</td>
          <td>{{ $item->cantidad_recibida }}</td>
          <td>
            @if ($item->area)
              <a href="{{ route('areas.edit', $item->area) }}">{{ $item->area->ruta() }}</a>
            @else
              N/A
            @endif
          </td>
        </tr>
      @endforeach
    </tbody>
  </table>
  
  <hr>
   
  <button type="button" class="btn btn-success" data-toggle="modal" data-target="#PDFrecepciones"><i class="fa fa-file-pdf-o"></i> Generar PDF</button>
    
  <div class="modal fade" id="PDFrecepciones" tabindex="-1" role="dialog" aria-labelledby="PDF Recepciones">
    <div class="modal-dialog modal-lg" id="mdialTamanio">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Cerrar</span></button>
                <h4 class="modal-title">Orden de Compra # {{ $recepcion->compra->numero_folio }}</h4>
            </div>
            <div class="modal-body modal-lg" style="height: 800px ">
                <iframe src="{{ route('pdf.recepciones', $recepcion)}}"  frameborder="0" height="100%" width="99.6%"></iframe>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-dismiss="modal">Cerrar</button>
            </div>
      </div>
    </div>
  </div>
@stop
