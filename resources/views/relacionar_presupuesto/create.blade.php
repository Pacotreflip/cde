@extends('layout')

@section('content')
<h1>Relacionar Presupuesto</h1>
<hr>
<div class="table-responsive">
  <form action="{{ route('compras.relacionar_presupuesto.post.store', $compra) }}" method="POST">
  {{ csrf_field() }}
    <table id="datos" class="table table-hover">
      <thead>
        <tr>
          <th>No.</th>    
          <th>Proveedor</th>    
          <th>#OC</th>    
          <th>Descripción</th>    
          <th>Familia</th>    
          <th>Area Reporte</th>    
          <th>Tipo</th>    
          <th>Consolidado Dolares</th>
          <th><button type="submit" class="btn btn-success">Agregar Selección</button></th>    
        </tr>
      </thead>
      <tbody>
        @foreach($datos_secrets_vs_dreams as $dato)
        <tr>
          <td>{{ $dato->no }}</td>
          <td>{{ $dato->proveedor }}</td>
          <td>{{ $dato->no_oc }}</td>
          <td>{{ $dato->descripcion_producto_oc }}</td>
          <td>{{ $dato->familia }}</td>
          <td>{{ $dato->area_reporte }}</td>
          <td>{{ $dato->tipo }}</td>
          <td>{{ $dato->consolidado_dolares }}</td>
          <td>
            <input name="{{$dato->id}}" type="checkbox" {{$compra->datosSecretsDreams->contains($dato->id) ? 'checked' : ''}}>
          </td>
        </tr>
        @endforeach
      </tbody>
    </table>
  </form>
@stop
@section('scripts')
<script>
    var filtersConfig = {
        watermark: ['No.', 'Proveedor', 'Orden de Compra', 'Descripción', 'Familia', 'Area Reporte', 'Tipo', 'Consolidado Dolares'],
        base_path: '{{ asset("tableFilter") }}',      
        col_6: 'multiple',
        col_widths: [
            null,null,null,null,null,null,'5%',null
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