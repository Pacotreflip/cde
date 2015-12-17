@if($currentObra)
  <li class="dropdown">
    <a href="#" class="dropdown-toggle" data-toggle="dropdown">
        {{ $currentObra->nombre }} <span class="caret"></span>
    </a>
    <ul class="dropdown-menu" role="menu">
        <li>{!! link_to_route('obras', 'Cambiar de proyecto') !!}</li>
    </ul>
  </li>

  <li class="dropdown">
    <a href="#" class="dropdown-toggle" data-toggle="dropdown">
        Catalogos <span class="caret"></span>
    </a>
    <ul class="dropdown-menu" role="menu">
        @if (!Auth::user()->hasRole('consulta_provisional')) 
        <li><a href="{{ route('tipos.index') }}">Áreas Tipo</a></li>
        @endif
        <li><a href="{{ route('areas.index') }}">Áreas</a></li>
        @if (!Auth::user()->hasRole('consulta_provisional')) 
        <li><a href="{{ route('clasificadores.index') }}">Clasificadores de Artículo</a></li>
        <li><a href="{{ route('articulos.index') }}">Artículos</a></li>
        <li><a href="{{ route('proveedores.index') }}">Proveedores</a></li>
        @endif
    </ul>
  </li>
  @if (!Auth::user()->hasRole('consulta_provisional'))
  <li><a href="{{ route('compras.index') }}">Compras</a></li>
  <li><a href="{{ route('recepciones.index') }}">Recepción</a></li>
  <li><a href="{{ route('transferencias.index') }}">Transferencia</a></li>
  <li><a href="{{ route('asignaciones.index') }}">Asignación</a></li>
  @endif
@if (Auth::user()->hasRole('admin')) 
<li class="dropdown">
                <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                    <span style="margin-left: 5px">Administraci&oacute;n</span> <b class="caret"></b>
                </a>
                <ul class="dropdown-menu">
                    <li><a href="{{ route("permissions_index_path") }}" ><span style="margin-left: 5px">Permisos</span></a></li>
                    <li><a href="{{ route("roles_index_path") }}" ><span style="margin-left: 5px">Roles</span></a></li>
                    <li><a href="{{ route("users_index_path") }}" ><span style="margin-left: 5px">Usuarios</span></a></li>
                    <!--<li class="divider"></li>-->
                </ul>
            </li>
@endif
@else
  <li><a href="{{ route('obras') }}">Proyectos</a></li>
@endif