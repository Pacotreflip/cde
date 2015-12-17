@extends("layout")
@section("content")
<ol class="breadcrumb">
    <li><a href="{{ route("home") }}">Inicio</a></li>
    <li><a href="#">Administración</a></li>
    <li><a href="{{ route('users_index_path') }}">Usuarios</a></li>
    <li class="active">{{ $user->nombre_usuario }}</li>
</ol>
@include("users.navbar")
<form method="post" action="{{ route('users_update_path', $user->id) }}">
    {{ csrf_field() }}
    <input type="hidden" name="_method" value="patch" >
    <div class="row">
        <div class="col-md-6 col-md-offset-3">
            <div class="panel panel-default">
                <div class="panel-heading"><span class="glyphicon glyphicon-edit" style="margin-right: 5px"></span>Datos del Usuario</div>
                <div class="panel-body">
                    <div class="form-group" style="overflow: hidden">
                        <label class="col-md-4 control-label" for="nombre_usuario" >Usuario:</label>
                        <div class="col-md-8"><input type="text" name="nombre_usuario" id="nombre_usuario" class="form-control" value="{{ $user->nombre_usuario }}"></div>

                    </div>
                    <div class="form-group" style="overflow: hidden">
                        <label class="col-md-4 control-label" for="nombre" >Nombre:</label>
                        <div class="col-md-8"><input type="text" name="nombre" id="nombre" class="form-control" value="{{ $user->nombre }}"></div>

                    </div>
                    
                    <div class="form-group" style="overflow: hidden">
                        <label class="col-md-4 control-label" for="apellido_paterno" >Apellido Paterno:</label>
                        <div class="col-md-8"><input type="text" name="apellido_paterno" id="apellido_paterno" class="form-control" value="{{ $user->apellido_paterno }}"></div>

                    </div>
                    
                    <div class="form-group" style="overflow: hidden">
                        <label class="col-md-4 control-label" for="apellido_materno" >Apellido Materno:</label>
                        <div class="col-md-8"><input type="text" name="apellido_materno" id="apellido_materno" class="form-control" value="{{ $user->apellido_materno }}"></div>

                    </div>
                    
                    <div class="form-group" style="overflow: hidden">
                        <label class="col-md-4 control-label" for="email" >Email:</label>
                        <div class="col-md-8"><input type="email" name="email" id="email" class="form-control" value="{{ $user->email }}"></div>

                    </div>
                    
                    <div class="form-group" style="overflow: hidden">
                        <label class="col-md-4 control-label" for="password" >Password:</label>
                        <div class="col-md-8"><input type="password" name="password" id="password" class="form-control" value=""></div>

                    </div>
                    <div class="form-group" style="overflow: hidden">
                        <label class="col-md-4 control-label" for="rep_password" >Confirmar Password:</label>
                        <div class="col-md-8"><input type="password" name="repetir_password" id="repetir_password" class="form-control" value=""></div>

                    </div>
                    
                    <div class="form-group" style="overflow: hidden">
                        <label class="col-md-4 control-label" >Roles:</label>
                        <div class="col-md-8">
                            <select multiple size="8" name="roles[]" id="roles" class="form-control">
                                @foreach($roles as $rol)
                                <option value="{{ $rol->id }}"
                                    @if(in_array($rol->id, $arreglo_roles) )
                                        selected="selected"
                                    @endif    
                                        >
                                    {{ $rol->display_name }}
                                </option>
                                @endforeach
                            </select>
                            
                        </div>

                    </div>
                    
                    
                </div>
                <div class="panel-footer" style="text-align: right"> 
                    <button type="submit" class="btn btn-primary">
                        <span class="glyphicon glyphicon-floppy-save" style="margin-right: 5px"></span>	Actualizar
                    </button>
                </div>
            </div>
        </div>
    </div>
</form>
@stop

