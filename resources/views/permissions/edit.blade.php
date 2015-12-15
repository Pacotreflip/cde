@extends("layout")
@section("content")
<ol class="breadcrumb">
    <li><a href="{{ route("home") }}">Inicio</a></li>
    <li><a href="#">Administración</a></li>
    <li><a href="{{ route('permissions_index_path') }}">Permisos</a></li>
    <li class="active">{{ $permission->display_name }}</li>
</ol>
@include("permissions.navbar")
<form method="post" action="{{ route('permissions_update_path', $permission->id) }}">
    {{ csrf_field() }}
    <input type="hidden" name="_method" value="patch" >
    <div class="row">
        <div class="col-md-6 col-md-offset-3">
            <div class="panel panel-default">
                <div class="panel-heading"><span class="glyphicon glyphicon-edit" style="margin-right: 5px"></span>Datos del Permiso</div>
                <div class="panel-body">
                    <div class="form-group" style="overflow: hidden">
                        <label class="col-md-4 control-label" for="name" >Nombre:</label>
                        <div class="col-md-8"><input type="text" name="name" id="name" class="form-control" value="{{ $permission->name }}"></div>

                    </div>
                    <div class="form-group" style="overflow: hidden">
                        <label class="col-md-4 control-label" for="display_name" >Nombre A Mostrar:</label>
                        <div class="col-md-8"><input type="text" name="display_name" id="display_name" class="form-control" value="{{ $permission->display_name }}"></div>

                    </div>
                    <div class="form-group" style="overflow: hidden">
                        <label class="col-md-4 control-label" for="description" >Descripción:</label>
                        <div class="col-md-8"><input type="text" name="description" id="description" class="form-control" value="{{ $permission->description }}"></div>

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

