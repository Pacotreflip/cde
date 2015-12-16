<nav class="navbar navbar-inverse">
    <div class="navbar-header">
        <a class="navbar-brand" href="{{ route('users_index_path') }}"><span class="glyphicon glyphicon-user" style="margin-right: 5px"></span>Usuarios</a>
    </div>
    <ul class="nav navbar-nav">
        <li><a href="{{ route('users_index_path') }}"><span class="glyphicon glyphicon-search" style="margin-right: 5px"></span>Buscar Usuario</a></li>
    </ul>
</nav>
<form id="formulario_assign_role_to_user" method="post" action="#">
    {{ csrf_field() }}
    <div id="contenedor_modal_assign_role_to_user"></div>
</form>