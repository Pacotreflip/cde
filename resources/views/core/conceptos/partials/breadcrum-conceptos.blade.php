<ol class="breadcrumb">
    @if(is_null($idConcepto))
        <li class="active">Raiz</li>
    @else
        <li>
            {!! link_to_route('conceptos.index', 'Raiz') !!}
        </li>
    @endif

    @foreach($conceptos as $concepto)
        @if($idConcepto == $concepto->id_concepto)
            <li class="active">{{ $concepto->descripcion }}
        @else
            <li>{!! link_to_route('conceptos.index', $concepto->descripcion, [$concepto->id_concepto]) !!}
        @endif
            </li>
    @endforeach
</ol>