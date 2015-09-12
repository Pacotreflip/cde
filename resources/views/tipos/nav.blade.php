<ul class="nav nav-pills nav-stacked">
  <li role="presentation" {!! Request::is('tipos-area/'.$tipo->id.'/modificar') ? 'class="active"' : '' !!}>
    <a href="{{ route('tipos.edit', [$tipo]) }}">Datos Generales</a>
  </li>
  <li role="presentation" {!! Request::is('tipos-area/'.$tipo->id.'/asignacion-requerimientos*') ? 'class="active"' : '' !!}>
    <a href="{{ route('requerimientos.edit', [$tipo]) }}">Requerimientos</a>
  </li>
</ul>