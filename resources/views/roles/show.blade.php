@extends("layout")
@section("content")
<ol class="breadcrumb">
    <li><a href="{{ route("home") }}">Inicio</a></li>
    <li><a href="#">Administración</a></li>
    <li><a href="{{ route('roles_index_path') }}">Roles</a></li>
    <li class="active">{{$role->display_name}}</li>
</ol>
@include("roles.navbar")

    <div class="row">
        <div class="col-md-6 col-md-offset-3">
            <div class="panel panel-default">
                <div class="panel-heading"><span class="glyphicon glyphicon-edit" style="margin-right: 5px"></span>Datos del Rol</div>
                <div class="panel-body">
                    <div class="form-group" style="overflow: hidden">
                        <label class="col-md-4 control-label" for="name" >Nombre:</label>
                        <div class="col-md-8"><input type="text" name="name" id="name" class="form-control" value="{{ $role->name }}"></div>

                    </div>
                    <div class="form-group" style="overflow: hidden">
                        <label class="col-md-4 control-label" for="display_name" >Nombre A Mostrar:</label>
                        <div class="col-md-8"><input type="text" name="display_name" id="display_name" class="form-control" value="{{ $role->display_name }}"></div>

                    </div>
                    <div class="form-group" style="overflow: hidden">
                        <label class="col-md-4 control-label" for="description" >Descripción:</label>
                        <div class="col-md-8"><input type="text" name="description" id="description" class="form-control" value="{{ $role->description }}"></div>

                    </div>
                    <br>
                    <br>
                    <div class="form-group" style="overflow: hidden ; text-align: center">
                        <label class="col-md-12 control-label" for="description" >Permisos Asignados</label>

                    </div>
                    <div class="form-group" style="overflow: hidden">
                        
                        @foreach($role->permissions as $permission)
                        <span class="btn btn-small btn-default" style="margin: 1px">
                        {{ $permission->display_name  }}
                        </span>
                        @endforeach

                    </div>
                </div>
                <div class="panel-footer" style="text-align: right"> 
                    <a class="btn btn-small btn-info" href="{{ route('roles_edit_path', $role->id) }}" title="Modificar" style="margin-right: 5px"><span class="glyphicon glyphicon-pencil" style="margin-right: 5px"></span>Modificar</a>
                    <form method="post"  action="{{ route('roles_destroy_path', $role->id) }}" style="float: right ; margin-left: 5px">
                        {{ csrf_field() }}
                        <input type="hidden" name="_method" value="delete">
                        <button type="submit" class="btn btn-small btn-danger">
                            <span class="glyphicon glyphicon-trash" style="margin-right: 5px"></span>Eliminar
                        </button>
                    </form>
                        <a class="btn btn-small btn-info asignar_permisos" href="{{ route('assign_permissions_to_role_create_path', $role->id) }}" title="Administrar Permisos"><span class="glyphicon glyphicon-knight" ></span>Administrar Permisos</a>
                </div>
            </div>
        </div>
    </div>

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