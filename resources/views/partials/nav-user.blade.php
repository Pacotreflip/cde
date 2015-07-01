<li class="dropdown">
    <a href="#" class="dropdown-toggle" data-toggle="dropdown">
        <i class="fa fa-user fa-fw"></i> {{ Auth::user()->present()->nombreCompleto }} <span class="caret"></span>
    </a>

    <ul class="dropdown-menu" role="menu">
        <li>
            <a href="{{ route('auth.logout') }}"><i class="fa fa-sign-out fa-fw"></i> Cerrar Sesión</a>
        </li>
    </ul>
</li>