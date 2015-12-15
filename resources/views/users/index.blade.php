@extends("layout")
@section("content")
<ol class="breadcrumb">
    <li><a href="{{ route("home") }}">Inicio</a></li>
    <li><a href="#">Administración</a></li>
    <li class="active">Usuarios</li>
</ol>
@include("users.navbar")
<form method="post" action="{{ route("users_edit_path") }}" id="frm_agrega_material">
                {{ csrf_field() }}
    <div class="row">
        <div class="col-md-2"><label for="producto" ><h3 style="margin: 0px"><span class="label label-default">Ingrese Usuario:</span></h3></label></div>
        <div class="col-md-2" >
            <input name="producto" id="auto_usuarios" type="text" class="form-control" />
        </div>
            
            
        <div class="col-md-2">
    </div>
                
</form>
    @stop
@section("scripts")
<script type="text/javascript">
    


            $(document).ready(function () {
                $('input:text').bind({
                });
                $("#auto_usuarios").autocomplete({
                    minLength: 3,
                    autofocus: true,
                    source: '{{ route("usuarios_get_lista") }}',
                    select: function (event, ui) {
//                        if(ui.item.id !== "A99"){
//                            muestraProductoModal(ui.item.id);
//                        }
                    }

                });
//                $('#modalAgregarProductoVenta').on('hidden.bs.modal', function (e) {
//                    $("#modalAgregarProductoVenta .modal-body .contenido_modal").html("");
//                    $("#modalAgregarProductoVenta .modal-body .errores_carga_producto").html("");
//                    $("#auto_productos").val("");
//                });
//                $(".elimina_producto").off().on("click", function (e){
//                    alert("etfra");
//                });
            });
            
                  
    
        </script>
@stop
