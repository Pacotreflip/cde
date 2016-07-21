@extends('layout')

@section('content')
  <h1>Transferencia de Artículos</h1>
  <hr>
  <div class="row transferencia">
  <div class="col-sm-6">
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
  </div>
      <div class="col-md-6 col-sm-6 gallery">
      <div class="row">
        @include('transferencias.partials.comprobantes')
      </div>
    </div>
  </div>
  <div class="row">
    <div class="col-md-offset-2 col-md-8 col-sm-offset-2 col-sm-8">
      <form action="{{ route('transferencias.comprobantes', [$transferencia]) }}" class="dropzone" id="dropzone" method="POST" enctype="multipart/form-data">
        {{ csrf_field() }}
      </form>
    </div>
  </div>
  <hr>
  <h3>Articulos Transferidos</h3>
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
                {{$item->origen->ruta}}
            </td>
            <td>
                {{$item->destino->ruta}}
            </td>
          </tr>
        @endforeach
      </tbody>
    </table>
  </div>
  
<div id="errores"></div>
  <form method="post" id="cancela_transferencia"  action="{{ route('transferencias.delete', $transferencia->id) }}" style="float: right">
        {{ csrf_field() }}
        
        <input type="hidden" name="_method" value="delete">
        <button type="submit" class="btn btn-sm btn-danger">
            <span class="glyphicon glyphicon-ban-circle" style="margin-right: 5px"></span>Cancelar
        </button>
    </form>
  <button type="button" style="margin-right: 5px" class="btn btn-sm btn-success pull-right" onclick="muestraComprobante('{{  route('pdf.transferencias', $transferencia)}}')"><i class="fa fa-file-pdf-o" style="margin-right: 5px"></i> Ver Formato PDF</button>

@include('pdf/modal_vacia', ['titulo' => 'Consulta de formatos',]) 

@stop
@section('scripts')
  <script>
    Dropzone.options.dropzone = {
      paramName: "comprobante",
      dictDefaultMessage: "<h2 style='color:#bbb'><span class='glyphicon glyphicon-picture' style='padding-right:5px'></span>Arraste los comprobantes a esta zona para asociarlos a la transferencia.</h2>",
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
    $("#cancela_transferencia").off().on("submit", function(e){
        var formURL = $(this).attr("action");
        swal({
            title: "¿Desea continuar con la cancelación?",
            text: "¿Esta seguro de cancelar la transferencia?",
            type: "input",
            closeOnConfirm: false,
            showCancelButton: true,
            confirmButtonText: "Si",
            cancelButtonText: "No",
            confirmButtonColor: "#ec6c62"
        }, function (inputValue) {
            if (inputValue === false) return false;      
            if (inputValue === "") {     
                swal.showInputError("Es obligatorio indicar el motivo de la cancelación.");     
                return false   
            }
            $.ajax({
                url: formURL,
                type: "POST",
                data: {motivo: inputValue},
                success: function (data)
                {
                    window.location = "{{ route('transferencias.index') }}" ;
                },
                error: function (xhr, textStatus, thrownError)
                {
                    var ind1 = xhr.responseText.indexOf('<span class="exception_message">');

                    if(ind1 === -1){
                        var salida_swal = '';
                        var salida = '<div class="alert alert-danger" role="alert"><strong>Errores: </strong> <br> <br><ul >';
                        $.each($.parseJSON(xhr.responseText), function (ind, elem) { 
                            salida += '<li>'+elem+'</li>';
                            salida_swal += elem +'/'
                        });
                        salida += '</ul></div>';
                        swal(salida_swal);
                        $("div#errores").html(salida);
                    }else{
                        var salida = '<div class="alert alert-danger" role="alert"><strong>Errores: </strong> <br> <br><ul >';
                        var ind1 = xhr.responseText.indexOf('<span class="exception_message">');
                        var cad1 = xhr.responseText.substring(ind1);
                        var ind2 = cad1.indexOf('</span>');
                        var cad2 = cad1.substring(32,ind2);
                        var salida_swal = '';
                        if(cad2 !== ""){
                            salida += '<li><p><strong>¡ERROR GRAVE!: </strong></p><p>'+cad2+'</p></li>';
                            salida_swal += cad2 ;
                        }else{
                            salida += '<li>Un error grave ocurrió. Por favor intente otra vez.</li>';
                            salida_swal += 'Un error grave ocurrió. Por favor intente otra vez.';
                        }
                        salida += '</ul></div>';
                        swal(salida_swal);
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