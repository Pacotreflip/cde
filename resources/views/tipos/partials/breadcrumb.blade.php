<ol class="breadcrumb">
    @unless($tipo)
        <li class="active">Inicio</li>
    @else
        <li><a href="{{ route('tipos.index') }}">Inicio</a></li>

        @foreach($tipo->getAncestors() as $ancestro)
            <li>
                <a href="{{ route('tipos.index', ['tipo' => $ancestro->id]) }}">
                    {{ $ancestro->nombre }}
                </a>
            </li>
        @endforeach
    @endunless

    @if($tipo)
        <li class="active">{{ $tipo->nombre }}</li>
    @endif
</ol>