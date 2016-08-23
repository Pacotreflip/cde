@extends('layout')

@section('content')
<h1>Relacionar Presupuesto a Orden de Compra {{$compra->present()->numero_folio}}</h1>
<hr>
<div class="row">
    <div class="col-md-6"></div>
    <div class="col-md-6">
        <div class="table-responsive" >
            <table class="table table-bordered">
        <thead>
            <tr >
                <th style="text-align: center">
                Subtotal Orden Compra (Sin Impuestos)
            </th>
            <th style="text-align: center">
                Total Presupuesto
            </th>
            <th colspan="2" style="text-align: center">
                Variación
            </th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td style="text-align: right">
                    <input type="hidden" name="total_oc" id="total_oc" value="{{number_format($compra->total_dolares,2,".","")}}">
                    $ {{number_format($compra->total_dolares,2,".",",")}}</td>
                <td style="text-align: right">$ <span id="total_presupuesto">{{number_format($compra->total_presupuesto,2,".",",")}}</span></td>
                <td style="text-align: right">$ <span id="variacion">{{number_format($compra->variacion,2,".",",")}}</span></td>
                @if($compra->total_presupuesto>0)
                <td style="text-align: right"><span id="porcentaje_variacion">{{$compra->porcentaje_variacion}}</span></td>
                @endif
            </tr>
        </tbody>
    </table>
</div>
    </div>
</div>

<div class="table-responsive">
  <form id="actualiza_presupuesto" action="{{ route('compras.relacionar_presupuesto.post.store', $compra) }}" method="POST">
  {{ csrf_field() }}
    <table id="datos" class="table table-hover">
      <thead>
        <tr>
          <th># OC Dreams</th>  
          <th>No.</th>    
          <th>Proveedor</th>    
          <th>#OC Secrets</th>    
          <th>Descripción</th>    
          <th>Familia</th>    
          <th>Área Reporte</th>    
          <th>Tipo</th>    
          <th>Consolidado Dolares</th>
          <th>Presupuesto</th>
          <th><button type="button" class="btn btn-success toggle-checkbox" estado="no_seleccionado">Seleccionar / Deseleccionar</button></th>    
        </tr>
      </thead>
      <tbody>
        @foreach($datos_secrets_vs_dreams as $dato)
        <tr>
          <td>{{ $dato->folio_oc_dreams }}</td>
          <td>{{ $dato->no }}</td>
          <td>{{ $dato->proveedor }}</td>
          <td>{{ $dato->no_oc }}</td>
          <td>{{ $dato->descripcion_producto_oc }}</td>
          <td>{{ $dato->familia }}</td>
          <td>{{ $dato->area_reporte }}</td>
          <td>{{ $dato->tipo }}</td>
          <td style="text-align: right">{{ number_format($dato->consolidado_dolares,2,".",",") }}</td>
          <td style="text-align: right">{{ number_format($dato->presupuesto_c,2,".",",") }}</td>
          <td>
            @if($dato->id_transaccion == "" || ($dato->id_transaccion > 0 && $dato->id_transaccion == $compra->id_transaccion))  
            @if($compra->datosSecretsDreams->contains($dato->id))
                <input name="id_secrets[]" value="{{$dato->id}}" type="checkbox" checked="true">
            @else
                <input name="id_secrets[]" value="{{$dato->id}}" type="checkbox" >
            @endif
            <input name="importe_presupuesto" class="importe_presupuesto" value="{{number_format($dato->presupuesto_c,2,".","")}}" type="hidden" >
            @elseif($dato->id_transaccion > 0 && $dato->id_transaccion != $compra->id_transaccion)
            <div class="popover-markup"> 
                <span class="alert-danger glyphicon glyphicon-exclamation-sign trigger" style="cursor: pointer"></span>
                    <div class="content hide">
                        Partida de presupuesto relacionada a orden de compra {{$dato->folio_oc_dreams}}.
                    </div>
            </div>
            @endif
          </td>
        </tr>
        @endforeach
      </tbody>
    </table>
    <input type="hidden" name="_method" value="POST">
    <input type="hidden" id="accion" name="accion" value="" />
    <button type="button" class="btn btn-success envia agregar_p">Relacionar Presupuesto</button>

  </form>
@stop
@section('scripts')
<script>
    $('.popover-markup>.trigger').popover({
        html: true,
        placement: "left",
        title: function () {
            return $(this).parent().find('.head').html();
        },
        content: function () {
            return $(this).parent().find('.content').html();
        }
    });
    $("button.envia").off().on("click", function(){
        if($(this).hasClass("agregar_p")){
            $("input#accion").val("agregar_p");
            
        }else if($(this).hasClass("quitar_p")){
            $("input#accion").val("quitar_p");
        }else if($(this).hasClass("reemplazar_p")){
            $("input#accion").val("reemplazar_p");
        }
        $("form#actualiza_presupuesto").submit();
    });
    function determina_variacion(){
        var presupuesto = parseFloat(0);
        inputs = $("input:checkbox:checked");
        $.each(inputs, function(k,input){
            valor = $(input).parent("td").find("input.importe_presupuesto").val();
           presupuesto += parseFloat(valor);
        });
        total = $("input#total_oc").val();
        variacion = total - presupuesto;
        if(presupuesto>0){
            porcentaje_variacion = (total - presupuesto)/presupuesto*100;
            $("span#porcentaje_variacion").html($.number(porcentaje_variacion, 2 ));
        }else{
            porcentaje_variacion = "-";
            $("span#porcentaje_variacion").html(porcentaje_variacion);
        }
        $("span#total_presupuesto").html($.number(presupuesto, 2 ));
        $("span#variacion").html($.number(variacion, 2 ));
        
    }
    $("input:checkbox").off().on("click", function(){
        determina_variacion();
    });
    $("button.toggle-checkbox").off().on("click", function(){
        boton = $(this);
        var estado = $(this).attr("estado");
        inputs = $("tr[validrow='true']").find("input:checkbox");
        if(inputs.length === 0){
            sweetAlert("Error", "Filtre primero los datos a seleccionar", "error");
        }
        
        if(estado === 'no_seleccionado'){
            nuevo_estado = 'seleccionado';
            boton.attr("estado",nuevo_estado);
            $.each(inputs, function(k,input){
               $(input).prop("checked",true); 
            });
        }else{
            
            nuevo_estado = 'no_seleccionado';
            boton.attr("estado",nuevo_estado);
            $.each(inputs, function(k,input){
                $(input).prop("checked",false); 
               //$(input).removeAttr("checked"); 
            });
        }
        determina_variacion();
    });
    var filtersConfig = {
        watermark: ['OC Dreams', 'No.', 'Proveedor', 'OC Secrets', 'Descripción', 'Familia', 'Area Reporte', 'Tipo', 'Consolidado Dolares','Presupuesto'],
        base_path: '{{ asset("tableFilter") }}',      
        col_7: 'multiple',
        col_widths: [
            null,null,null,null,null,null,null,'5%',null,null
        ],
        paging: true,
        paging_length: 100,
        rows_counter: true,
        rows_counter_text: '',
        btn_reset: true,
        btn_reset_text: "Limpiar",
        loader: true
       
    };
    var tf = new TableFilter('datos', filtersConfig);
    tf.init();
    
    $('.agregar').off().on('click', function(e) {
        var id_dato = $(this).attr('id_dato'); 
        $.ajax({
            type: 'GET',
            url: App.host + '/compras/{{$compra->id_transaccion}}/relacionar_presupuesto/' + id_dato + '/store',
            success: function(data) {
                if(data === '0') {
                    swal({
                        title: 'Datos relacionados con éxito',   
                        timer: 1000,
                        showConfirmButton: false,
                        type: 'success'                      
                    });
                    $(this).removeClass('btn-primary').addClass('btn-success');
                    $(this).children('i')
                            .removeClass('fa-plus-circle')
                            .addClass('fa-check-circle');
                } else {
                    swal({   
                        title: 'Estos datos ya están relacionados',
                        text: '¿Deseas desenlazar los datos?',   
                        type: "warning",   
                        showCancelButton: true,   
                        confirmButtonColor: '#DD6B55',   
                        confirmButtonText: 'Si, Desenlazar!',   
                        closeOnConfirm: false 
                    }, function(){ 
                        $.ajax({
                            type: 'DELETE',
                            url: App.host + '/compras/{{$compra->id_transaccion}}/relacionar_presupuesto/' + id_dato + '/destroy',
                            success: function( data ) {
                                swal({
                                    title: 'Datos desenlazados con éxito',
                                    timer: 1000,
                                    showConfirmButton: false,
                                    type: 'success'
                                });
                                $(button).removeClass('btn-success').addClass('btn-primary');
                                $(button).children('i')
                                        .removeClass('fa-check-circle')
                                        .addClass('fa-plus-circle');
                            },
                            error: function ( error ) {
                                alert('error');
                            }
                        });
                    });     
                }
            },
            error: function(error) {
                
            }
        });
    });
</script>
@stop