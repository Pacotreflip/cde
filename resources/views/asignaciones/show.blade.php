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
    <div class="col-md-6 col-sm-6 gallery">
      <div class="row">
        @include('asignaciones.partials.comprobantes')
      </div>
    </div>
  </div>
  <div class="row">
    <div class="col-md-offset-2 col-md-8 col-sm-offset-2 col-sm-8">
      <form action="{{ route('asignaciones.comprobantes', [$asignacion]) }}" class="dropzone" id="dropzone" method="POST" enctype="multipart/form-data">
        {{ csrf_field() }}
      </form>
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
    Dropzone.options.dropzone = {
      paramName: "comprobante",
      dictDefaultMessage: "<h2 style='color:#bbb'><span class='glyphicon glyphicon-picture' style='padding-right:5px'></span>Arraste los comprobantes a esta zona para asociarlos a la asignación.</h2>",
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
    $("#cancela_asignacion").off().on("submit", function(e){
        var formURL = $(this).attr("action");
        swal({
            title: "¿Desea continuar con la cancelación?",
            text: "¿Esta seguro de cancelar la asignación?",
            type: "input",
            showCancelButton: true,
            closeOnConfirm: false,
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
                type: "post",
                data: {motivo: inputValue},
                success: function (data)
                {
                    window.location = "{{ route('asignaciones.index') }}" ;
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