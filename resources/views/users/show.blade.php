@extends("layout")
@section("content")
<ol class="breadcrumb">
    <li><a href="{{ route("home") }}">Inicio</a></li>
    <li><a href="#">Administración</a></li>
    <li><a href="{{ route('users_index_path') }}">Usuarios</a></li>
    <li class="active">{{$user->nombre_usuario}}</li>
</ol>
@include("users.navbar")
<form method="post" action="{{ route('users_store_path') }}">
    {{ csrf_field() }}
    <div class="row">
        <div class="col-md-6 col-md-offset-3">
            <div class="panel panel-default">
                <div class="panel-heading"><span class="glyphicon glyphicon-edit" style="margin-right: 5px"></span>Datos del Usuario</div>
                <div class="panel-body">
                    <div class="form-group" style="overflow: hidden">
                        <label class="col-md-4 control-label" for="nombre_usuario" >Usuario:</label>
                        <div class="col-md-8"><input type="text" name="nombre_usuario" id="nombre_usuario" class="form-control" value="{{ $user->usuario }}"></div>

                    </div>
                    <div class="form-group" style="overflow: hidden">
                        <label class="col-md-4 control-label" for="nombre" >Nombre:</label>
                        <div class="col-md-8"><input type="text" name="nombre" id="nombre" class="form-control" value="{{ $user->nombre }}"></div>

                    </div>
                    
                    <div class="form-group" style="overflow: hidden">
                        <label class="col-md-4 control-label" for="apellido_paterno" >Apellido Paterno:</label>
                        <div class="col-md-8"><input type="text" name="apellido_paterno" id="apellido_paterno" class="form-control" value="{{ $user->apaterno }}"></div>

                    </div>
                    
                    <div class="form-group" style="overflow: hidden">
                        <label class="col-md-4 control-label" for="apellido_materno" >Apellido Materno:</label>
                        <div class="col-md-8"><input type="text" name="apellido_materno" id="apellido_materno" class="form-control" value="{{ $user->amaterno }}"></div>

                    </div>
                    
                    <div class="form-group" style="overflow: hidden">
                        <label class="col-md-4 control-label" for="email" >Email:</label>
                        <div class="col-md-8"><input type="email" name="email" id="email" class="form-control" value="{{ $user->correo }}"></div>

                    </div>
                    
                    <div class="form-group" style="overflow: hidden">
                        <label class="col-md-4 control-label" for="email" >Empresa:</label>
                        <div class="col-md-8"><input type="text" name="empresa" id="empresa" class="form-control" value="{{ $user->empresa->empresa }}"></div>

                    </div>
                    
                    <div class="form-group" style="overflow: hidden">
                        <label class="col-md-4 control-label" for="email" >Departamento:</label>
                        <div class="col-md-8"><input type="text" name="departamento" id="departamento" class="form-control" value="{{ $user->departamento->departamento }}"></div>

                    </div>
                    
                    <div class="form-group" style="overflow: hidden">
                        <label class="col-md-4 control-label" for="email" >Ubicación:</label>
                        <div class="col-md-8"><input type="text" name="ubicacion" id="ubicacion" class="form-control" value="{{ $user->ubicacion->ubicacion }}"></div>

                    </div>
                    
                    <div class="form-group" style="overflow: hidden">
                        <label class="col-md-4 control-label" >Roles:</label>
                        <div class="col-md-8">
                            @if(count($user->roles)>0)
                            @foreach($user->roles as $rol)
                            <span class="btn btn-small btn-default" style="margin: 2px">
                            {{ $rol->display_name  }}
                            </span>
                            @endforeach
                            @else
                            <span class="btn btn-small btn-danger" style="margin: 2px">
                            Sin Roles Asignados
                            </span>
                            @endif
                        </div>

                    </div>
                    
                    
                </div>
                <div class="panel-footer" style="text-align: right"> 
                    <a class="btn btn-small btn-info asignar_roles" href="{{ route('role_to_user_show_path', $user->idusuario) }}" title="Modificar Roles"><span class="glyphicon glyphicon-pencil" style="margin-right: 5px"></span>Modificar Roles</a>
                    
                </div>
            </div>
        </div>
    </div>
</form>
@stop
@section("scripts")
<script type="text/javascript">
$("a.asignar_roles").off().on("click", function(e){
        var href = $(this).attr("href");
                $.get(href, function(data){
                    $("#contenedor_modal_assign_role_to_user").html(data);
                    $("#modal_assign_role_to_user").modal("show");
                    $(".agrega_roles").off().on("click", function(e){
                        var postData = $("form#formulario_assign_role_to_user").serialize();
                        
                        $.ajax(
                        {
                            url : '{{ route("assign_role_to_user_store_path") }}',
                            type: "POST",
                            data : postData,
                            success:function(data) 
                            {                       
                                $("#modal_assign_role_to_user").modal("hide");
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
                                $("#modal_assign_role_to_user div.errores").html(salida);
                            }
                        });
                        e.preventDefault();
                        
                    });
                    $(".quita_roles").off().on("click", function(e){
                        var postData = $("form#formulario_assign_role_to_user").serialize();
                        
                        $.ajax(
                        {
                            url : '{{ route("remove_role_to_user_store_path") }}',
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
