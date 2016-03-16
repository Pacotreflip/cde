@extends('layout')

@section('content')
  <h1>Entrega de Áreas - <span># {{ $entrega->numero_folio }}</span></h1>
  
  <hr>
  <div class="row recepcion">
    <div class="col-sm-6">
      <div class="panel panel-default transaccion-detail">
        <div class="panel-heading">
            Detalles del Entrega
        </div>
        <div class="panel-body">
          <strong>Fecha Entrega:</strong> {{ $entrega->fecha_entrega->format('Y-m-d h:m') }} 
            <small class="text-muted">({{ $entrega->created_at->diffForHumans() }})</small> <br>
          <strong>Persona que Cierra:</strong> {{ $entrega->usuario->present()->nombreCompleto }} <br>
          <strong>Observaciones:</strong> {{ $entrega->observaciones }} <br>
        </div>
      </div>
    </div>
    
  </div>
  
  <hr>

  <h3>Áreas Cerradas</h3>
  <table class="table table-striped">
    <thead>
      <tr>
        <th>Clave</th>
        <th>Área</th>
        <th>No. Artículos Validados</th>
      </tr>
    </thead>
    <tbody>
      @foreach($entrega->partidas as $partida)
        <tr>
          <td>{{ $partida->area->clave }}</td>
          <td>{{ $partida->area->ruta }}</td>
          <td>{{ $partida->area->cantidad_validada() }}</td>
          
        </tr>
      @endforeach
    </tbody>
  </table>
@include('pdf/modal', ['modulo' => 'entregas', 'titulo' => 'Entrega de área - # '.$entrega->numero_folio, 'ruta' => route('pdf.entregas', $entrega),])  
@stop