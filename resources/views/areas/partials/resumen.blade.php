<h3>Resumen del área</h3>
<hr>

<h4>Conteo de Areas Tipo</h4>
<ul class="list-group">
    @foreach($areas_tipo as $area_tipo)
    <li class="list-group-item">
        {{ $area_tipo->ruta(' / ') }} <span class="badge">{{ $area_tipo->areasAsignadasDentroDe($area)->count() }}</span>
    </li>
    @endforeach
</ul>