@extends('layout')

@section('content')
  <h1>Compras</h1>
  <hr>

  @include('partials.search-form')
<form id="descargaExcel" action="{{ route("compras.xls") }}"></form>
      <button type="button" class="btn btn-primary pull-left descargar_excel" style="margin-left: 5px"><span class="fa fa-table" style="margin-right: 5px"></span>Descarga Excel</button>

  <table class="table table-striped table-hover">
    <thead>
      <tr>
        <th>Folio</th>
        <th>Fecha</th>
        <th>Proveedor</th>
        <th>Observaciones</th>
        <th>% Recibido</th>
      </tr>
    </thead>
    <tbody>
      @foreach($compras as $compra)
        <tr>
          <td><a href="{{ route('compras.show', $compra->id_transaccion) }}"># {{ $compra->numero_folio }}</a></td>
          <td>{{ $compra->fecha->format('d-m-Y') }}</td>
          <td>{{ $compra->empresa->razon_social }}</td>
          <td>{{ str_limit($compra->observaciones, 70) }}</td>
          <td>
        @if($compra->items()->sum('cantidad') > 0)
        <div class="progress">
            <div
              class="progress-bar progress-bar-striped {{ $compra->progress_bar_estado_recepcion_class }}" 
              role="progressbar"
              aria-valuenow="{{ $compra->cantidad_recibida / $compra->items()->sum('cantidad') * 100 }}"
              aria-valuemin="0"
              aria-valuemax="100"
              style="min-width: 2.5em; width: {{ round($compra->cantidad_recibida / $compra->items()->sum('cantidad') * 100) }}%;">
              {{ round($compra->cantidad_recibida / $compra->items()->sum('cantidad') * 100) }}%
            </div>
        </div>
        @endif    
          </td>
        </tr>
      @endforeach
    </tbody>
  </table>

  {!! $compras->appends(Request::only('buscar'))->render() !!}
@stop
@section('scripts')
  <script>
    $("button.descargar_excel").off().on("click", function(e){
        $("form#descargaExcel").submit();
    });
  </script>
@stop