@extends('layout')

@section('content')
  <h1>Transferencia de Artículos</h1>
  <hr>
  
  <div class="panel panel-default transaccion-detail">
    <div class="panel-heading">
      Detalle de la Transferencia
    </div>

    <div class="panel-body">
      <strong>No. Folio:</strong> #{{ $transferencia->numero_folio }} <br>
      <strong>Fecha:</strong> {{ $transferencia->fecha_transferencia->format('d-M-Y h:m') }} <br>
      <strong>Observaciones:</strong> {{ $transferencia->observaciones }} <br>
      <strong>Creada Por:</strong> {{ $transferencia->creado_por }} <br>
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
          <th>Cantidad Transferida</th>
          <th>Origen</th>
          <th>Destino</th>
        </tr>
      </thead>
      <tbody>
        @foreach($transferencia->items as $item)
          <tr>
            <td>{{ $item->material->numero_parte }}</td>
            <td>{{ $item->material->descripcion }}</td>
            <td>{{ $item->material->unidad }}</td>
            <td>{{ $item->cantidad_transferida }}</td>
            <td>
              @include('partials.path-area', ['area' => $item->origen])
            </td>
            <td>
              @include('partials.path-area', ['area' => $item->destino])
            </td>
          </tr>
        @endforeach
      </tbody>
    </table>
  </div>
  
  <hr>
  
  <button type="button" class="btn btn-success" data-toggle="modal" data-target="#PDFTransferencia"><i class="fa fa-file-pdf-o"></i> Generar PDF</button>
    
  <div class="modal fade" id="PDFTransferencia" tabindex="-1" role="dialog" aria-labelledby="PDF Transferencia">
    <div class="modal-dialog modal-lg" id="mdialTamanio">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Cerrar</span></button>
                <h4 class="modal-title">Transferencia de Artículos</h4>
            </div>
            <div class="modal-body modal-lg" style="height: 800px ">
                <iframe src="{{ route('pdf.transferencias', $transferencia)}}"  frameborder="0" height="100%" width="99.6%"></iframe>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-dismiss="modal">Cerrar</button>
            </div>
      </div>
    </div>
  </div>
@stop