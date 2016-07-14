@extends('layout')

@section('content')
  <h1>Compra</h1>
  <hr>
  
  <p class="well">
    <strong>No. Folio:</strong>
    {{ $compra->numero_folio }} <br><br>
    
    <strong>No. Folio Solicitud:</strong>
    {{ $compra->antecedente->numero_folio }} <br><br>

    <strong>Fecha:</strong>
    {{ $compra->fecha->format('d-m-Y') }} <span class="text-muted">({{ $compra->fecha->diffForHumans() }})</span> <br><br>
    
    <strong>Fecha Acordada Entrega:</strong>
    {{ $compra->fecha->format('d-m-Y') }} <span class="text-muted">({{ $compra->fecha->diffForHumans() }})</span> <br><br>
    
    <strong>Proveedor:</strong>
    {{ $compra->empresa->razon_social }} <br><br>

    <strong>Observaciones:</strong> <br>
    {{ $compra->observaciones }}
  </p>
  
  <h3>Artículos Adquiridos</h3>
  
  <div class="table-responsive">
    <table class="table table-striped table-hover table-condensed">
      <thead>
        <tr>
          <th>Descripción</th>
          <th>Unidad</th>
          <th>Adquirido</th>
          <th>Precio</th>
          <th>Importe</th>
          <th>Concepto</th>
          <th>Recibido</th>
          <th>% Recibido</th>
        </tr>
      </thead>
      <tbody>
          @foreach($compra->items as $item)
              <tr>
                <td>{{ $item->material->descripcion }}</td>
                <td>{{ $item->unidad }}</td>
                <td><a onclick="showModal('{{ route('entregas_programadas.index', ['id_item' => $item->id_item]) }}')" class="adquirido" title="Ver detalle de entregas programadas" href="#" >{{ $item->cantidad }}</a></td>
                <td>{{ number_format($item->precio_unitario,2) }}</td>
                <td>{{ number_format($item->importe,2) }}</td>
                <td>{{ $item->antecedente->entregas[0]->concepto->ruta }}</td>
                <td>{{ $item->cantidad_recibida }}</td>
                <td>
                @if($item->cantidad > 0)
                <div class="progress">
                    <div
                      class="progress-bar progress-bar-striped{{ round(($item->cantidad_recibida / $item->cantidad)*100) == 100 ? ' progress-bar-success' : '' }}" 
                      role="progressbar"
                      aria-valuenow="{{ $item->cantidad_recibida / $item->cantidad * 100 }}"
                      aria-valuemin="0"
                      aria-valuemax="100"
                      style="min-width: 2.5em; width: {{ round($item->cantidad_recibida / $item->cantidad * 100) }}%;">
                      {{ round($item->cantidad_recibida / $item->cantidad * 100) }}%
                    </div>
                </div>
                @endif
                </td>
              </tr>
          @endforeach
      </tbody>
    </table>
      <div id="modal">
          
      </div>
  </div>
@include('pdf/modal', ['modulo' => 'compras', 'titulo' => 'Compra de Artículos', 'ruta' => route('pdf.compras', $compra),])
@stop
@section('scripts')
<script>
  $('.adquirido').tooltip();
  $(function () {
    $.ajaxSetup({
      headers: {
        'X-CSRF-TOKEN': App.csrfToken
      }
    });
  });
  
  function showModal(ruta) {
      $.ajax({
        url: ruta,
        success: function (source) {
          $('#modal').html(source);
          $('#entregas_programadas_modal').modal('show');
        },
        error: function (error) {
          console.log(error);
        }
      });
  }
  
  function borrar(id, element) {
      swal({   
          title: "¿Eliminar Entrega Programada?",   
          type: "warning",   
          showCancelButton: true,   
          confirmButtonText: "Si, eliminar",
          cancelButtonText: "No, cancelar",
          closeOnConfirm: false,   
          showLoaderOnConfirm: true
      }, 
      function(){   
          $.ajax({
            url: App.host + '/entregas_programadas/' + id,
            method: 'DELETE',
            success: function(response) {
                $(element).closest('tr').remove();
                $('#totalProgramado').text(response.totalProgramado);
                $('#cantidad').text(response.cantidad);
                $('#faltante').text(response.faltante);
                swal({
                    type: "info",
                    title: response.Mensaje,   
                    timer: 1000,   
                    showConfirmButton: false 
                });
            },
            error: function(error) {
                console.log(error);
            }
          });
      });
  }
</script>
@stop
