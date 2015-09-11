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
            <li><a href="{{ route('tipos.index') }}">Tipos de Area</a></li>
            <li><a href="{{ route('areas.index') }}">Areas</a></li>
            <li><a href="{{ route('clasificadores.index') }}">Clasificadores de Articulo</a></li>
            <li><a href="{{ route('articulos.index') }}">Articulos</a></li>
            <li><a href="{{ route('proveedores.index') }}">Proveedores</a></li>
        </ul>
    </li>
    {{--<li><a href="#">Adquisición</a></li>--}}
    {{--<li><a href="#">Recepción</a></li>--}}
    {{--<li><a href="#">Transferencias</a></li>--}}
    {{--<li><a href="#">Asignación</a></li>--}}
@else
    <li>
        <a href="{{ route('obras') }}">Proyectos</a>
    </li>
@endif
