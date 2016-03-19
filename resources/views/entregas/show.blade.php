@extends('layout')

@section('content')
  <h1>Entrega de Áreas - <span># {{ $entrega->numero_folio }}</span></h1>
  
  <hr>
  <div class="row recepcion">
    <div class="col-sm-6">
      <div class="panel panel-default transaccion-detail">
        <div class="panel-heading">
            Detalles de la Entrega
        </div>
        <div class="panel-body">
          <strong>Fecha Entrega:</strong> {{ $entrega->fecha_entrega->format('Y-m-d h:m') }} 
            <small class="text-muted">({{ $entrega->created_at->diffForHumans() }})</small> <br>
          <strong>Persona que Entrega:</strong> {{ $entrega->entrega }} <br>
          <strong>Persona que Recibe:</strong> {{ $entrega->recibe }} <br>
          <strong>Concepto:</strong> {{ $entrega->concepto }} <br>
          <strong>Persona que Registro Entrega:</strong> {{ $entrega->usuario->present()->nombreCompleto }} <br>
        </div>
      </div>
    </div>
    
  </div>
  
  <hr>

  <h3>Artículos Entregados</h3>
  <table class="table table-striped">
    <thead>
      <tr>
        <th style="text-align: center; width: 20px">#</th>
        <th style="text-align: center">Familia</th>
        <th style="text-align: center">Descripción</th>
        <th style="text-align: center">Unidad</th>
        <th style="text-align: center; width: 150px">Cantidad Entregada</th>
        <th style="text-align: center">Ubicación</th>
      </tr>
    </thead>
    <tbody>
      @foreach($entrega->partida_articulos() as $articulo)
        <tr>
            <td>
                {{$articulo["i"]}}
            </td>
            <td>
                {{$articulo["familia"]}}
            </td>
            <td>
                {{$articulo["descripcion"]}}
            </td>
            <td style="text-align: right">
                {{$articulo["unidad"]}}
            </td>
            <td style="text-align: right">
                {{$articulo["cantidad_asignada"]}}
            </td>
            <td style="text-align: right">

               {{$articulo["ubicacion_asignada"]}}
            </td>
          
        </tr>
      @endforeach
    </tbody>
  </table>
@include('pdf/modal', ['modulo' => 'entregas', 'titulo' => 'Entrega de Áreas - # '.$entrega->numero_folio, 'ruta' => route('pdf.entregas', $entrega),])  
@stop