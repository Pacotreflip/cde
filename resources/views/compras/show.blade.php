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
          <th>No. de Parte</th>
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
                <td>{{ $item->material->numero_parte }}</td>
                <td>
                     <a href="{{ route('articulos.edit', [$item->material]) }}">{{ $item->material->descripcion }}</a>
                </td>
                <td>{{ $item->unidad }}</td>
                <td><a ruta="{{ route('entregas_programadas.index', ['id_item' => $item->id_item]) }}" class="adquirido" title="Ver detalle de entregas programadas" href="#" >{{ $item->cantidad }}</a></td>
                <td>{{ number_format($item->precio_unitario,2) }}</td>
                <td>{{ number_format($item->importe,2) }}</td>
                @if($item->antecedente->entregas)
                @if($item->antecedente->entregas[0]->concepto)
                <td>{{ $item->antecedente->entregas[0]->concepto->ruta }}</td>
                @else
                <td></td>
                @endif
                
                @else
                <td>&nbsp;</td>
                @endif
                
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
      <div id="div_modal">
          
      </div>
  </div>
  <form id="descargaExcel" action="{{ route("comparativa_compra.xls", $compra) }}"></form>
  <div><button type="button" style="margin-left: 5px" class="btn btn-sm btn-success pull-right" onclick="muestraComprobante('{{  route('pdf.compras', $compra)}}')"><i class="fa fa-file-pdf-o" style="margin-right: 5px"></i> Ver Formato PDF</button>
  <button type="button" class="btn btn-sm btn-primary pull-right descargar_excel" style="margin-left: 5px"><span class="fa fa-table" style="margin-right: 5px"></span>Descarga Excel Análisis Desviación</button>
  <button type="button" class="btn btn-sm btn-info pull-right fechas_pago" style="margin-left: 5px" ruta="{{ route("compra.pagos_programados.index", $compra) }}"><i class="fa fa-money"></i> Fechas de Pago</button>
  <a type="button" class="btn btn-sm btn-default pull-right" style="margin-left: 5px" href="{{ route("compras.relacionar_presupuesto.create", $compra) }}"><i class="fa fa-calculator"></i> Relacionar Presupuesto</a>
  </div>
  @include('pdf/modal_vacia', ['titulo' => 'Consulta de formatos',]) 
@stop
@section('scripts')
<script>
    $("button.fechas_pago").off().on("click", function(e) {
        e.preventDefault();
        showModal($(this).attr('ruta'));          
    });
    $("button.descargar_excel").off().on("click", function(e){
        $("form#descargaExcel").submit();
    });
    function muestraComprobante(ruta){
        $("#PDFModal .modal-body").html('<iframe src="'+ruta+'"  frameborder="0" height="100%" width="99.6%">d</iframe>');
        $("#PDFModal").modal("show");
    }
    $('.adquirido').tooltip(); 
    $('.adquirido').off().on('click', function (e) {
      e.preventDefault();
      showModal($(this).attr('ruta'), this);
    });
    
    function showModal(ruta, element = null) {
      $.ajax({
        url: ruta,
        method: 'GET',
        
        beforeSend: function() {
          if(element)
            $(element).closest('td').LoadingOverlay("show");  
        },
        afterSend: function() {
          if(element)
            $(element).closest('td').LoadingOverlay("hide");   
        },
        success: function (source) {
          $('#div_modal').html(source);
          $('#modal').modal('show');
          if(element)
            $(element).closest('td').LoadingOverlay("hide");   
        },
        error: function (error) {
          console.log(error);
          if(element)
            $(element).closest('td').LoadingOverlay("hide");
        }
      });
    }
    
    function agregar(id){
      $.ajax({
        type: 'GET',
        url: App.host + '/entregas_programadas/create/' + id,
        success: function(source) {
          if(source.error) {
            swal('','No hay cantidad faltante por programar.','info');
          } else {
            $('#modal').html(source);
          }
        },
        error: function(error) {
          console.log(error);
        }
      });
    }
    
    function agregarPago(){
      $.ajax({
        type: 'GET',
        url: '{{ route("compra.pagos_programados.create", $compra) }}',
        success: function(source) {
          if(source.error) {
            swal('','No hay monto faltante por programar.','info');
          } else {
            $('#modal').html(source);
          }
        },
        error: function(error) {
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
    
    function borrarPago(id_compra, id_pago, element) {
      swal({   
        title: "¿Eliminar Pago Programado?",   
        type: "warning",   
        showCancelButton: true,   
        confirmButtonText: "Si, eliminar",
        cancelButtonText: "No, cancelar",
        closeOnConfirm: false,   
        showLoaderOnConfirm: true
      }, 
      function(){   
        $.ajax({
          url: App.host + '/compra/' + id_compra + '/pagos_programados/' + id_pago,
          method: 'DELETE',
          success: function(response) {
            $(element).closest('tr').remove();
            $('#totalProgramado').text(response.totalProgramado);
            $('#cantidad').text(response.monto);
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
    
    function store(id){
      $("#errores").empty();
      $.ajax({
        url: App.host + '/entregas_programadas/store/' + id,
        type: 'POST',
        data: 
          $('#entrega_programada_form').serialize()
        ,
        success: function(response) {
          $('#modal').modal('hide');
          showModal(App.host + '/entregas_programadas/index/' + id);
          swal({
            type: "success",
            title: response.Mensaje,   
            timer: 1000,   
            showConfirmButton: false 
          });
        },
        error: function(xhr, responseText, thrownError) {
          var ind1 = xhr.responseText.indexOf('<span class="exception_message">');
          if(ind1 === -1) {
            var salida = '<div class="alert alert-danger" role="alert"><strong>Errores: </strong> <br> <br><ul >';
            $.each($.parseJSON(xhr.responseText), function (ind, elem) { 
              salida += '<li>'+elem+'</li>';
            });
            salida += '</ul></div>';
            $("#errores").html(salida);
          } else {
            var salida = '<div class="alert alert-danger" role="alert"><strong>Errores: </strong> <br> <br><ul >';
            var ind1 = xhr.responseText.indexOf('<span class="exception_message">');
            var cad1 = xhr.responseText.substring(ind1);
            var ind2 = cad1.indexOf('</span>');
            var cad2 = cad1.substring(32,ind2);
            if(cad2 !== "") {
              salida += '<li><p><strong>¡ERROR GRAVE!: </strong></p><p>'+cad2+'</p></li>';
            } else {
              salida += '<li>Un error grave ocurrió. Por favor intente otra vez.</li>';
            }
            salida += '</ul></div>';
            $("#errores").html(salida);
          }
        }
      });
    }
    
    function storePago(id_compra){
      $("#errores").empty();
      $.ajax({
        url: App.host + '/compra/' + id_compra + '/pagos_programados/store',
        type: 'POST',
        data: 
          $('#pago_programado_form').serialize()
        ,
        success: function(response) {
          $('#modal').modal('hide');
          showModal(App.host + '/compra/' + id_compra + '/pagos_programados/index');
          swal({
            type: "success",
            title: response.Mensaje,   
            timer: 1000,   
            showConfirmButton: false 
          });
        },
        error: function(xhr, responseText, thrownError) {
          var ind1 = xhr.responseText.indexOf('<span class="exception_message">');
          if(ind1 === -1) {
            var salida = '<div class="alert alert-danger" role="alert"><strong>Errores: </strong> <br> <br><ul >';
            $.each($.parseJSON(xhr.responseText), function (ind, elem) { 
              salida += '<li>'+elem+'</li>';
            });
            salida += '</ul></div>';
            $("#errores").html(salida);
          } else {
            var salida = '<div class="alert alert-danger" role="alert"><strong>Errores: </strong> <br> <br><ul >';
            var ind1 = xhr.responseText.indexOf('<span class="exception_message">');
            var cad1 = xhr.responseText.substring(ind1);
            var ind2 = cad1.indexOf('</span>');
            var cad2 = cad1.substring(32,ind2);
            if(cad2 !== "") {
              salida += '<li><p><strong>¡ERROR GRAVE!: </strong></p><p>'+cad2+'</p></li>';
            } else {
              salida += '<li>Un error grave ocurrió. Por favor intente otra vez.</li>';
            }
            salida += '</ul></div>';
            $("#errores").html(salida);
          }
        }
      });
    }
    
    function editar(id) {
      $.ajax({
        url: App.host + '/entregas_programadas/' + id + '/edit',
        type: 'GET',
        success: function (source) {
          $('#modal').html(source);
        },
        error: function(error) {
            console.log(error);
        }
      });
    }
    
    function editarPago(id_compra, id_pago) {
      $.ajax({
        url: App.host + '/compra/' + id_compra + '/pagos_programados/' + id_pago + '/edit',
        type: 'GET',
        success: function (source) {
          $('#modal').html(source);
        },
        error: function(error) {
            console.log(error);
        }
      });
    }
    
    function update(id, id_item) {
      $.ajax({
        type: 'POST',
        data: $('#edit_entrega_programada_form').serialize(),
        url: App.host  + '/entregas_programadas/' + id,
        success: function(response) {
          $('#modal').modal('hide');
          showModal(App.host + '/entregas_programadas/index/' + id_item);
          swal({
            type: "success",
            title: response.Mensaje,   
            timer: 1000,   
            showConfirmButton: false 
          });
        },
        error: function(xhr, responseText, thrownError) {
          var ind1 = xhr.responseText.indexOf('<span class="exception_message">');
          if(ind1 === -1) {
            var salida = '<div class="alert alert-danger" role="alert"><strong>Errores: </strong> <br> <br><ul >';
            $.each($.parseJSON(xhr.responseText), function (ind, elem) { 
              salida += '<li>'+elem+'</li>';
            });
            salida += '</ul></div>';
            $("#errores").html(salida);
          } else {
            var salida = '<div class="alert alert-danger" role="alert"><strong>Errores: </strong> <br> <br><ul >';
            var ind1 = xhr.responseText.indexOf('<span class="exception_message">');
            var cad1 = xhr.responseText.substring(ind1);
            var ind2 = cad1.indexOf('</span>');
            var cad2 = cad1.substring(32,ind2);
            if(cad2 !== "") {
              salida += '<li><p><strong>¡ERROR GRAVE!: </strong></p><p>'+cad2+'</p></li>';
            } else {
              salida += '<li>Un error grave ocurrió. Por favor intente otra vez.</li>';
            }
            salida += '</ul></div>';
            $("#errores").html(salida);
          }
        }
      });  
    }
    
    function updatePago(id_compra, id_pago) {
      $.ajax({
        type: 'POST',
        data: $('#edit_pago_programado_form').serialize(),
        url: App.host  + '/compra/' + id_compra + '/pagos_programados/' + id_pago,
        success: function(response) {
          $('#modal').modal('hide');
          showModal(App.host + '/compra/' + id_compra + '/pagos_programados/index');
          swal({
            type: "success",
            title: response.Mensaje,   
            timer: 1000,   
            showConfirmButton: false 
          });
        },
        error: function(xhr, responseText, thrownError) {
          var ind1 = xhr.responseText.indexOf('<span class="exception_message">');
          if(ind1 === -1) {
            var salida = '<div class="alert alert-danger" role="alert"><strong>Errores: </strong> <br> <br><ul >';
            $.each($.parseJSON(xhr.responseText), function (ind, elem) { 
              salida += '<li>'+elem+'</li>';
            });
            salida += '</ul></div>';
            $("#errores").html(salida);
          } else {
            var salida = '<div class="alert alert-danger" role="alert"><strong>Errores: </strong> <br> <br><ul >';
            var ind1 = xhr.responseText.indexOf('<span class="exception_message">');
            var cad1 = xhr.responseText.substring(ind1);
            var ind2 = cad1.indexOf('</span>');
            var cad2 = cad1.substring(32,ind2);
            if(cad2 !== "") {
              salida += '<li><p><strong>¡ERROR GRAVE!: </strong></p><p>'+cad2+'</p></li>';
            } else {
              salida += '<li>Un error grave ocurrió. Por favor intente otra vez.</li>';
            }
            salida += '</ul></div>';
            $("#errores").html(salida);
          }
        }
      });  
    }
</script>
@stop
