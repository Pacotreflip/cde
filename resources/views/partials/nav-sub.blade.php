@if(Request::is('almacenes/*'))
    <li class="dropdown">
        <a href="#" class="dropdown-toggle" data-toggle="dropdown">
            Reporte de Actividades <span class="caret"></span>
        </a>
        <ul class="dropdown-menu" role="menu">
            <li><a href="{{ route('reportes.index', [$almacen->id_almacen]) }}">Lista de reportes</a></li>
            <li><a href="{{ route('horas-mensuales.index', [$almacen->id_almacen]) }}">Horas de contrato</a></li>
        </ul>
    </li>

    <li class="{{ Request::segment(1) == 'conciliacion' ? 'active': '' }}">
        {!! link_to_route('conciliacion.proveedores', 'Conciliación') !!}
    </li>
@endif