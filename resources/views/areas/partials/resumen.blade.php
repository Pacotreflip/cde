<hr>

<div class="panel panel-default">
  <div class="panel-heading">
    Conteo por Áreas Tipo en {{ $area->nombre }}
  </div>

  <ul class="list-group">
    @foreach($areas_tipo as $area_tipo)
    @if (!Auth::user()->hasRole('consulta_provisional'))
    <a class="list-group-item" href="{{ route('tipos.edit', $area_tipo) }}">
      {{ $area_tipo->ruta(' / ') }} <span class="badge">{{ $area_tipo->areasAsignadasDentroDe($area)->count() }}</span>
    </a>
    @else
    <a class="list-group-item" href="#">
      {{ $area_tipo->ruta(' / ') }} <span class="badge">{{ $area_tipo->areasAsignadasDentroDe($area)->count() }}</span>
    </a>
    @endif
    @endforeach
  </ul>
</div>
