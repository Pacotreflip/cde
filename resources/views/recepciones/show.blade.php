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
    <div class="col-md-6 col-sm-6 gallery">
      <div class="row">
        @include('recepciones.partials.comprobantes')
      </div>
    </div>
  </div>
  <div class="row">
    <div class="col-md-offset-2 col-md-8 col-sm-offset-2 col-sm-8">
      <form action="{{ route('recepciones.comprobantes', [$recepcion]) }}" class="dropzone" id="dropzone" method="POST" enctype="multipart/form-data">
        {{ csrf_field() }}
      </form>
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
  <form method="post" id="cancela_recepcion"  action="{{ route('recepciones.delete', $recepcion->id) }}" style="float: right">
        {{ csrf_field() }}
        <input type="hidden" name="_method" value="delete">
        <button type="submit" class="btn btn-sm btn-danger">
            <span class="glyphicon glyphicon-ban-circle" style="margin-right: 5px"></span>Cancelar
        </button>
    </form>
  <button type="button" style="margin-right: 5px" class="btn btn-sm btn-success pull-right" onclick="muestraComprobante('{{  route('pdf.recepciones', $recepcion)}}')"><i class="fa fa-file-pdf-o" style="margin-right: 5px"></i> Ver Formato PDF</button>

@include('pdf/modal_vacia', ['titulo' => 'Consulta de formatos',]) 
@stop

@section('scripts')
  <script>
    Dropzone.options.dropzone = {
      paramName: "comprobante",
      dictDefaultMessage: "<h2 style='color:#bbb'><span class='glyphicon glyphicon-picture' style='padding-right:5px'></span>Arraste los comprobantes a esta zona para asociarlos a la recepción.</h2>",
      init: function() {
        this.on("errormultiple", function(files, response) {
          console.log(response);
        });
      }
    };
    
    function muestraComprobante(ruta){
        $("#PDFModal .modal-body").html('<iframe src="'+ruta+'"  frameborder="0" height="100%" width="99.6%">d</iframe>');
        $("#PDFModal").modal("show");
    }
    $("#cancela_recepcion").off().on("submit", function(e){
        var formURL = $(this).attr("action");
        swal({
            title: "¿Desea continuar con la cancelación?",
            text: "¿Esta seguro de cancelar la recepción?",
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
                    window.location = "{{ route('recepciones.index') }}" ;
                },
                error: function (xhr, textStatus, thrownError)
                {
                    console.log(xhr.responseText);
                    var salida = '<div class="alert alert-danger" role="alert"><strong>Errores: </strong> <br> <br><ul >';
                    $.each($.parseJSON(xhr.responseText), function (ind, elem) {
                        salida += '<li>' + elem + '</li>';
                    });
                    salida += '</ul></div>';
                    $("div.errores_venta").html(salida);
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
