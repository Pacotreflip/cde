<li class="dropdown">
    <a href="#" class="dropdown-toggle" data-toggle="dropdown">
        {{ Auth::user()->present()->nombreCompleto }} <span class="caret"></span>
    </a>

    <ul class="dropdown-menu" role="menu">
        <li>{!! link_to_route('auth.logout', 'Cerrar Sesión') !!}</li>
    </ul>
</li>