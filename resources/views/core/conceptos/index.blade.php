@extends('layouts.default')

@section('content')
    <div class="page-header">
        <h1>Presupuesto de Obra</h1>
    </div>

    @include('core.conceptos.partials.breadcrum-conceptos', ['idConcepto' => $idConcepto, 'conceptos' => $ancestros])

    <div class="list-group">
        @foreach($conceptos as $concepto)
            <a href="{{ route('conceptos.index', [$concepto->id_concepto]) }}" class="list-group-item">
                {{ $concepto->descripcion }}

                @if($concepto->esMedible())
                    <span class="badge">M</span>
                @endif

                @if($concepto->esMaterial())
                    <span class="label label-primary pull-right">material</span>
                @endif
            </a>
        @endforeach
    </div>
@stop