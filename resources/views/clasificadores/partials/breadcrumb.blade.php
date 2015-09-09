<ol class="breadcrumb">
    @unless($clasificador)
        <li class="active">Inicio</li>
    @else
        <li><a href="{{ route('clasificadores.index') }}">Inicio</a></li>
    @endunless

    @foreach($ancestros as $ancestro)
        <li>
            <a href="{{ route('clasificadores.index', ['clasificador' => $ancestro->id]) }}">
                {{ $ancestro->nombre }}
            </a>
        </li>
    @endforeach

    @if($clasificador)
        <li class="active">{{ $clasificador->nombre }}</li>
    @endif
</ol>