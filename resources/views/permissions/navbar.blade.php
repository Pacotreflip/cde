<nav class="navbar navbar-inverse">
    <div class="navbar-header">
        <a class="navbar-brand" href="{{ route('permissions_index_path') }}"><span class="glyphicon glyphicon-permission" style="margin-right: 5px"></span>Permisos</a>
    </div>
    <ul class="nav navbar-nav">
        <li><a href="{{ route('permissions_index_path') }}"><span class="glyphicon glyphicon-list-alt" style="margin-right: 5px"></span>Ver Lista de Permisos</a></li>
        <li><a href="{{ route('permissions_create_path') }}"><span class="glyphicon glyphicon-plus-sign" style="margin-right: 5px"></span>Nuevo Permiso</a>
    </ul>
</nav>