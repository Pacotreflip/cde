@extends('layout')

@section('content')
  <h1>Asignación de Artículos - <span># {{ $asignacion->numero_folio }}</span></h1>
  
  <hr>
  <div class="row recepcion">
    <div class="col-sm-6">
      <div class="panel panel-default transaccion-detail">
        <div class="panel-heading">
            Detalles de la Asignación
        </div>
        <div class="panel-body">
          <strong>Fecha Asignación:</strong> {{ $asignacion->fecha_asignacion->format('Y-m-d h:m') }} 
            <small class="text-muted">({{ $asignacion->created_at->diffForHumans() }})</small> <br>
          <strong>Persona que Asigna:</strong> {{ $asignacion->usuario_registro->present()->nombreCompleto }} <br>
          <strong>Persona que Valida:</strong>  <br>
          <strong>Observaciones:</strong> {{ $asignacion->observaciones }} <br>
        </div>
      </div>
    </div>
    <div class="col-sm-6">
        @if($asignacion->recepcion)
      <div class="panel panel-default transaccion-detail">
        <div class="panel-heading">
            Referencias
        </div>
        <div class="panel-body">
          <strong>No. de Recepción:</strong> #{{ $asignacion->recepcion->numero_folio }} <br>
        </div>
      </div>
        @endif
    </div>
  </div>
  
  <hr>

  <h3>Artículos Asignados</h3>
  <table class="table table-striped">
    <thead>
      <tr>
        <th>No. Parte</th>
        <th>Descripción</th>
        <th>Unidad</th>
        <th>Cantidad Asignada</th>
        <th>Área Origen</th>
        <th>Área Destino</th>
      </tr>
    </thead>
    <tbody>
      @foreach($asignacion->items as $item)
        <tr>
          <td>{{ $item->material->numero_parte }}</td>
          <td>
            <a href="{{ route('articulos.edit', $item->material) }}">{{ $item->material->descripcion }}</a>
          </td>
          <td>{{ $item->material->unidad }}</td>
          <td>{{ $item->cantidad_asignada }}</td>
          <td>
            @if ($item->area_origen)
              <a href="{{ route('areas.edit', $item->area_origen) }}">{{ $item->area_origen->ruta() }}</a>
            @else
              N/A
            @endif
          </td>
          <td>
            @if ($item->area_destino)
              <a href="{{ route('areas.edit', $item->area_destino) }}">{{ $item->area_destino->ruta() }}</a>
            @else
              N/A
            @endif
          </td>
        </tr>
      @endforeach
    </tbody>
  </table>
<div id="errores"></div>
  <form method="post" id="cancela_asignacion"  action="{{ route('asignaciones.delete', $asignacion->id) }}" style="float: right">
        {{ csrf_field() }}
        
        <input type="hidden" name="_method" value="delete">
        <button type="submit" class="btn btn-sm btn-danger">
            <span class="glyphicon glyphicon-ban-circle" style="margin-right: 5px"></span>Cancelar
        </button>
    </form>
  <button type="button" style="margin-right: 5px" class="btn btn-sm btn-success pull-right" onclick="muestraComprobante('{{  route('pdf.asignaciones', $asignacion)}}')"><i class="fa fa-file-pdf-o" style="margin-right: 5px"></i> Ver Formato PDF</button>

@include('pdf/modal_vacia', ['titulo' => 'Consulta de formatos',]) 
@stop

@section('scripts')
  <script>
    
    
    function muestraComprobante(ruta){
        $("#PDFModal .modal-body").html('<iframe src="'+ruta+'"  frameborder="0" height="100%" width="99.6%">d</iframe>');
        $("#PDFModal").modal("show");
    }
    $("#cancela_asignacion").off().on("submit", function(e){
        var formURL = $(this).attr("action");
        swal({
            title: "¿Desea continuar con la cancelación?",
            text: "¿Esta seguro de cancelar la asignación?",
            type: "warning",
            showCancelButton: true,
            confirmButtonText: "Si",
            cancelButtonText: "No",
            confirmButtonColor: "#ec6c62"
        }, function () {
            $.ajax({
                url: formURL,
                type: "DELETE",
                success: function (data)
                {
                    window.location = "{{ route('asignaciones.index') }}" ;
                },
                error: function (xhr, textStatus, thrownError)
                {
                    var ind1 = xhr.responseText.indexOf('<span class="exception_message">');

                    if(ind1 === -1){
                        var salida = '<div class="alert alert-danger" role="alert"><strong>Errores: </strong> <br> <br><ul >';
                        $.each($.parseJSON(xhr.responseText), function (ind, elem) { 
                            salida += '<li>'+elem+'</li>';
                        });
                        salida += '</ul></div>';
                        $("div#errores").html(salida);
                    }else{
                        var salida = '<div class="alert alert-danger" role="alert"><strong>Errores: </strong> <br> <br><ul >';
                        var ind1 = xhr.responseText.indexOf('<span class="exception_message">');
                        var cad1 = xhr.responseText.substring(ind1);
                        var ind2 = cad1.indexOf('</span>');
                        var cad2 = cad1.substring(32,ind2);
                        if(cad2 !== ""){
                            salida += '<li><p><strong>¡ERROR GRAVE!: </strong></p><p>'+cad2+'</p></li>';
                        }else{
                            salida += '<li>Un error grave ocurrió. Por favor intente otra vez.</li>';
                        }
                        salida += '</ul></div>';
                        $("div#errores").html(salida);
                    }
                }
            });
        });
        e.preventDefault();
    });
    $(function () {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $('[data-toggle="tooltip"]').tooltip(); 
    });
  </script>
@stop