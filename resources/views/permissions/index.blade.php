@extends("layout")
@section("content")
<ol class="breadcrumb">
    <li><a href="{{ route("home") }}">Inicio</a></li>
    <li><a href="#">Administración</a></li>
    <li class="active">Permisos</li>
</ol>
@include("permissions.navbar")
<div class="row">
    <div class="col-md-2"></div>
    <div class="col-md-8">
        <table class="table table-striped table-bordered">
            <thead>
                <tr>
                    <td>Permiso</td>
                    <td>Nombre</td>
                    <td>Descripción</td>
                    <td style="width: 145px">Acciones</td>
                </tr>
            </thead>
            <tbody>
                @foreach($permissions as $key => $value)
                <tr>
                    
                    <td>{{ $value->name }}</td>
                    <td>{{ $value->display_name }}</td>
                    <td>{{ $value->description }}</td>
                    <td>
                        <a class="btn btn-small btn-success" href="{{ route('permissions_show_path', $value->id) }}" title="Ver Detalle"><span class="glyphicon glyphicon-list-alt"></span></a>
                        <a class="btn btn-small btn-info" href="{{ route('permissions_edit_path', $value->id) }}" title="Modificar"><span class="glyphicon glyphicon-pencil"></span></a>
                        <form method="post"  action="{{ route('permissions_destroy_path', $value->id) }}" style="float: right">
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

