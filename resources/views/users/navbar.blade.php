<nav class="navbar navbar-inverse">
    <div class="navbar-header">
        <a class="navbar-brand" href="{{ route('users_index_path') }}"><span class="glyphicon glyphicon-user" style="margin-right: 5px"></span>Usuarios</a>
    </div>
    <ul class="nav navbar-nav">
        <li><a href="{{ route('users_index_path') }}"><span class="glyphicon glyphicon-search" style="margin-right: 5px"></span>Buscar Usuario</a></li>
        <li><a href="{{ route('users_create_path') }}"><span class="glyphicon glyphicon-plus-sign" style="margin-right: 5px"></span>Nuevo Usuario</a>
    </ul>
</nav>