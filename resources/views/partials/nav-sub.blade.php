@if(Request::is('almacenes/*'))

    @if(! Request::is('almacenes/nuevo'))
        <li class="dropdown {{ Request::is('*/reporte-actividades*') ? 'active' : '' }}">
            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                Reporte de Actividades <span class="caret"></span>
            </a>
            <ul class="dropdown-menu" role="menu">
                <li><a href="{{ route('reportes.index', [$almacen]) }}">Lista de reportes</a></li>
                <li><a href="{{ route('reportes.create', [$almacen]) }}">Generar nuevo reporte</a></li>
                <li><a href="{{ route('horas-mensuales.index', [$almacen]) }}">Horas de contrato</a></li>
            </ul>
        </li>
    @endif
@endif
    <li class="{{ Request::is('conciliacion/*') ? 'active' : '' }}">
        {!! link_to_route('conciliacion.proveedores', 'Conciliación') !!}
    </li>