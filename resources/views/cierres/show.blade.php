@extends('layout')

@section('content')
  <h1>Cierre de Áreas - <span># {{ $cierre->numero_folio }}</span></h1>
  
  <hr>
  <div class="row recepcion">
    <div class="col-sm-6">
      <div class="panel panel-default transaccion-detail">
        <div class="panel-heading">
            Detalles del Cierre
        </div>
        <div class="panel-body">
          <strong>Fecha Cierre:</strong> {{ $cierre->fecha_cierre->format('Y-m-d h:m') }} 
            <small class="text-muted">({{ $cierre->created_at->diffForHumans() }})</small> <br>
          <strong>Persona que Cierra:</strong> {{ $cierre->usuario->present()->nombreCompleto }} <br>
          <strong>Observaciones:</strong> {{ $cierre->observaciones }} <br>
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
        <th>No. de Artículos</th>
      </tr>
    </thead>
    <tbody>
      @foreach($cierre->partidas as $partida)
        <tr>
          <td>{{ $partida->area->clave }}</td>
          <td>{{ $partida->area->ruta }}</td>
          <td>{{ $partida->cantidad }}</td>
        </tr>
      @endforeach
    </tbody>
  </table>
@include('pdf/modal', ['modulo' => 'cierres', 'titulo' => 'Cierre de Áreas - # '.$cierre->numero_folio, 'ruta' => route('pdf.cierres', $cierre),])  
@stop
