<ul class="nav nav-tabs">
    @if(Request::is('reporte-actividades*'))
        <li role="presentation" class="active">
    @else
        <li role="presentation">
    @endif
        <a href="{{ route('reportes.index', [$almacen]) }}">Reportes de actividad</a>
    </li>
    <li role="presentation">
        <a href="{{ route('horas-contrato.index', [$almacen]) }}">Horas de contrato</a>
    </li>
</ul>