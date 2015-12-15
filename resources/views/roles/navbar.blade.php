<nav class="navbar navbar-inverse">
    <div class="navbar-header">
        <a class="navbar-brand" href="{{ route('roles_index_path') }}"><span class="glyphicon glyphicon-permission" style="margin-right: 5px"></span>Roles</a>
    </div>
    <ul class="nav navbar-nav">
        <li><a href="{{ route('roles_index_path') }}"><span class="glyphicon glyphicon-list-alt" style="margin-right: 5px"></span>Ver Lista de Roles</a></li>
        <li><a href="{{ route('roles_create_path') }}"><span class="glyphicon glyphicon-plus-sign" style="margin-right: 5px"></span>Nuevo Rol</a>
    </ul>
</nav>
<form id="formulario_assign_permissions_to_role" method="post" action="#">
    {{ csrf_field() }}
    <div id="contenedor_modal_assign_permissions_to_role"></div>
</form>