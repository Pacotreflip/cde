@extends("layout")
@section("content")
<ol class="breadcrumb">
    <li><a href="{{ route("home") }}">Inicio</a></li>
    <li><a href="#">Administración</a></li>
    <li class="active">Roles</li>
</ol>
@include("roles.navbar")
<div class="row">
    <div class="col-md-2"></div>
    <div class="col-md-8">
        <table class="table table-striped table-bordered">
            <thead>
                <tr>
                    <td>Rol</td>
                    <td>Nombre</td>
                    <td>Descripción</td>
                    <td style="width: 190px">Acciones</td>
                </tr>
            </thead>
            <tbody>
                @foreach($roles as $key => $value)
                <tr>
                    
                    <td>{{ $value->name }}</td>
                    <td>{{ $value->display_name }}</td>
                    <td>{{ $value->description }}</td>
                    <td>
                        <a class="btn btn-small btn-success" href="{{ route('roles_show_path', $value->id) }}" title="Ver Detalle"><span class="glyphicon glyphicon-list-alt"></span></a>
                        <a class="btn btn-small btn-info" href="{{ route('roles_edit_path', $value->id) }}" title="Modificar"><span class="glyphicon glyphicon-pencil"></span></a>
                        <a class="btn btn-small btn-info asignar_permisos" href="{{ route('assign_permissions_to_role_create_path', $value->id) }}" title="Administrar Permisos"><span class="glyphicon glyphicon-knight"></span></a>
                        <form method="post"  action="{{ route('roles_destroy_path', $value->id) }}" style="float: right">
                            {{ csrf_field() }}
                            <input type="hidden" name="_method" value="delete">
                            <button type="submit" class="btn btn-small btn-danger">
                                <span class="glyphicon glyphicon-trash" style=""></span>
                            </button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <div class="col-md-2"></div>
    @stop
@section("scripts")
<script type="text/javascript">
$("a.asignar_permisos").off().on("click", function(e){
        var href = $(this).attr("href");
                $.get(href, function(data){
                    $("#contenedor_modal_assign_permissions_to_role").html(data);
                    $("#modal_assign_permissions_to_role").modal("show");
                    $(".agrega_permisos").off().on("click", function(e){
                        var postData = $("form#formulario_assign_permissions_to_role").serialize();
                        
                        $.ajax(
                        {
                            url : '{{ route("assign_permissions_to_role_store_path") }}',
                            type: "POST",
                            data : postData,
                            success:function(data) 
                            {                       
                                $("#modal_assign_permissions_to_role").modal("hide");
                                location.reload();
                            },
                            error: function(xhr, textStatus, thrownError) 
                            {
                                console.log(xhr.responseText);
                                var salida = '<div class="alert alert-danger" role="alert"><strong>Errores: </strong> <br> <br><ul >';

                                $.each($.parseJSON(xhr.responseText), function (ind, elem) { 
                                    salida += '<li>'+elem+'</li>';
                                });
                                salida += '</ul></div>';
                                $("#modal_assign_permissions_to_role div.errores").html(salida);
                            }
                        });
                        e.preventDefault();
                        
                    });
                    $(".quita_permisos").off().on("click", function(e){
                        var postData = $("form#formulario_assign_permissions_to_role").serialize();
                        
                        $.ajax(
                        {
                            url : '{{ route("remove_permissions_to_role_store_path") }}',
                            type: "POST",
                            data : postData,
                            success:function(data) 
                            {                       
                                $("#modal_assign_permissions_to_role").modal("hide");
                                location.reload();
                            },
                            error: function(xhr, textStatus, thrownError) 
                            {
                                console.log(xhr.responseText);
                                var salida = '<div class="alert alert-danger" role="alert"><strong>Errores: </strong> <br> <br><ul >';

                                $.each($.parseJSON(xhr.responseText), function (ind, elem) { 
                                    salida += '<li>'+elem+'</li>';
                                });
                                salida += '</ul></div>';
                                $("#modal_assign_permissions_to_role div.errores").html(salida);
                            }
                        });
                        e.preventDefault();
                        
                    });
                });
        e.preventDefault();  
    });

</script>
@stop
