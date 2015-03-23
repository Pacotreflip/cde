@if($currentObra)
    <li class="dropdown">
        <a href="#" class="dropdown-toggle" data-toggle="dropdown">
            {{ $currentObra->nombre }} <span class="caret"></span>
        </a>
        <ul class="dropdown-menu" role="menu">
            <li>{!! link_to_route('pages.obras', 'Cambiar de obra') !!}</li>
        </ul>
    </li>
    <li class="dropdown">
        <a href="#" class="dropdown-toggle" data-toggle="dropdown">
            Almacenes <span class="caret"></span>
        </a>
        <ul class="dropdown-menu" role="menu">
            <li><a href="{{ route('almacenes.index') }}">Lista de almacenes</a></li>
            <li><a href="{{ route('almacenes.create') }}">Nuevo almacén</a></li>
        </ul>
    </li>
@else
    <li>
        <a href="{{ route('pages.obras') }}">Obras</a>
    </li>
@endif

@yield('nav-sub')