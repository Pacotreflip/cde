<ol class="breadcrumb">
  @unless($area)
    <li class="active">Inicio</li>
  @else
    <li><a href="{{ route('areas.index') }}">Áreas</a></li>
  @endunless

  @foreach($ancestros as $ancestro)
    <li>
      <a href="{{ route('areas.index', ['area' => $ancestro]) }}">
        {{ $ancestro->nombre }}
      </a>
    </li>
  @endforeach

  @if($area)
    <li class="active">{{ $area->nombre }}</li>
  @endif
</ol>