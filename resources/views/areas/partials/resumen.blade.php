<hr>
<h4>Conteo por Áreas Tipo en {{ $area->nombre }}</h4>

<ul class="list-group">
  @foreach($areas_tipo as $area_tipo)
  <a class="list-group-item" href="{{ route('tipos.edit', $area_tipo) }}">
    {{ $area_tipo->ruta(' / ') }} <span class="badge">{{ $area_tipo->areasAsignadasDentroDe($area)->count() }}</span>
  </a>
  @endforeach
</ul>