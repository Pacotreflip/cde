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
                {{$articulo["cantidad_cierre"]}}
            </td>
            <td style="text-align: right">

               {{$articulo["ubicacion"]}}
            </td>
          
        </tr>
      @endforeach
    </tbody>
  </table>
  <div id="errores"></div>
  <form method="post" id="cancela_entrega"  action="{{ route('entregas.delete', $entrega->id) }}" style="float: right">
        {{ csrf_field() }}
        
        <input type="hidden" name="_method" value="delete">
        <button type="submit" class="btn btn-sm btn-danger">
            <span class="glyphicon glyphicon-ban-circle" style="margin-right: 5px"></span>Cancelar
        </button>
    </form>
  <button type="button" style="margin-right: 5px" class="btn btn-sm btn-success pull-right" onclick="muestraComprobante('{{  route('pdf.entregas', $entrega)}}')"><i class="fa fa-file-pdf-o" style="margin-right: 5px"></i> Ver Formato PDF</button>

@include('pdf/modal_vacia', ['titulo' => 'Consulta de formatos',]) 
@stop
@section('scripts')
  <script>
    
    
    function muestraComprobante(ruta){
        $("#PDFModal .modal-body").html('<iframe src="'+ruta+'"  frameborder="0" height="100%" width="99.6%">d</iframe>');
        $("#PDFModal").modal("show");
    }
    $("#cancela_entrega").off().on("submit", function(e){
        var formURL = $(this).attr("action");
        swal({
            title: "¿Desea continuar con la cancelación?",
            text: "¿Esta seguro de cancelar la entrega de área?",
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
                    window.location = "{{ route('entregas.index') }}" ;
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
