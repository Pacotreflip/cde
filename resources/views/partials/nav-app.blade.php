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
        <li><a href="{{ route('tipos.index') }}">Áreas Tipo</a></li>
        <li><a href="{{ route('areas.index') }}">Áreas</a></li>
        <li><a href="{{ route('clasificadores.index') }}">Clasificadores de Artículo</a></li>
        <li><a href="{{ route('articulos.index') }}">Artículos</a></li>
        <li><a href="{{ route('proveedores.index') }}">Proveedores</a></li>
    </ul>
  </li>
  <li><a href="{{ route('compras.index') }}">Compras</a></li>
  <li><a href="{{ route('recepciones.index') }}">Recepción</a></li>
  <li><a href="{{ route('transferencias.index') }}">Transferencia</a></li>
  <li><a href="{{ route('asignaciones.index') }}">Asignación</a></li>
@else
  <li><a href="{{ route('obras') }}">Proyectos</a></li>
@endif