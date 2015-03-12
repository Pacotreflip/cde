@if($currentObra)
    <li class="dropdown">
        <a href="#" class="dropdown-toggle" data-toggle="dropdown">
            {{ $currentObra->nombre }} <span class="caret"></span>
        </a>
        <ul class="dropdown-menu" role="menu">
            <li>{!! link_to_route('pages.obras', 'Cambiar de obra') !!}</li>
        </ul>
    </li>
@else
    <li>
        <a href="{{ route('pages.obras') }}">Obras</a>
    </li>
@endif

<li class="{{ Request::segment(1) == 'conciliacion' ? 'active': '' }}">
    {!! link_to_route('conciliacion.proveedores', 'Conciliación') !!}
</li>
<li class="{{ Request::segment(1) == 'operacion' ? 'active': '' }}">
    {!! link_to_route('reportes.almacenes', 'Reporte de Actividades') !!}
</li>