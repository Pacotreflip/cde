<div class="panel panel-success">
    <div class="panel-heading">
        <h3 class="panel-title text-left">Periodos</h3>
    </div>
    <ul class="list-group">
        @forelse($periodos as $periodo)
            <a class="list-group-item" href="{!! route('conciliacion.edit', [$idProveedor, $idEquipo, $periodo->id]) !!}">
                <span>{{ $periodo->present()->periodo }}</span>

                {!! $periodo->present()->statusLabel !!}

                {{--<span class="badge">{{ $periodo->present()->horasConciliadasConUnidad }}</span>--}}
            </a>
        @empty
            <li class="list-group-item text-danger">No existen periodos</li>
        @endforelse
    </ul>
</div>