<ul class="nav nav-pills nav-stacked">
  <li role="presentation" {!! Request::is('areas-tipo/'.$tipo->id.'/modificar') ? 'class="active"' : '' !!}>
    <a href="{{ route('tipos.edit', [$tipo]) }}">Datos Generales</a>
  </li>
  <li role="presentation" {!! Request::is('areas-tipo/'.$tipo->id.'/articulos-requeridos*') ? 'class="active"' : '' !!}>
    <a href="{{ route('requerimientos.edit', [$tipo]) }}">Articulos Requeridos <span class="badge">{{ $tipo->conteoMateriales() }}</span></a>
  </li>
  <li role="presentation" {!! Request::is('areas-tipo/'.$tipo->id.'/areas-asignadas*') ? 'class="active"' : '' !!}>
    <a href="{{ '/areas-tipo/'.$tipo->id.'/areas-asignadas' }}">Areas Asignadas <span class="badge">{{ $tipo->conteoAreas() }}</span></a>
  </li>
  <li role="presentation" {!! Request::is('areas-tipo/'.$tipo->id.'/evaluacion-calidad*') ? 'class="active"' : '' !!}>
    <a href="{{ '/areas-tipo/'.$tipo->id.'/evaluacion-calidad' }}">Evaluación de Calidad</a>
  </li>
  <li role="presentation" {!! Request::is('areas-tipo/'.$tipo->id.'/comparativa*') ? 'class="active"' : '' !!}>
    <a href="{{ '/areas-tipo/'.$tipo->id.'/comparativa' }}">Comparativa</a>
  </li>
</ul>