@extends('tipos.layout')

@section('main-content')
    <dv id="app">
        <table class="table table-condensed table-striped">
            <thead>
                <tr>
                    <th colspan="4" class="text-center">ESTE PROYECTO</th>
                    <th></th>
                    <th colspan="4" class="text-center">PROYECTO COMPARATIVO</th>
                </tr>
                <tr>
                    <th>#</th>
                    <th class="text-center">Cantidad</th>
                    <th class="text-center">P.U. (USD)</th>
                    <th class="text-center">Importe (USD)</th>
                    <th class="text-center">Articulo</th>
                    <th class="text-center">Cantidad</th>
                    <th class="text-center">P.U. (USD)</th>
                    <th class="text-center">Importe (USD)</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @foreach ($tipo->materialesRequeridos as $key => $material)
                <?php $importe_total += $material->getImporte($tipo_cambio) ?>
                <?php $importe_total_comparativa += $material->getImporteComparativa($tipo_cambio) ?>
                    <tr>
                        <th>{{ $key + 1 }}</th>
                        <th class="text-right">{{ $material->cantidad_requerida }}</th>
                        <th class="text-right">
                            @if ($material->moneda)
                                @if ($material->moneda->esLocal())
                                    {{ round($material->precio_estimado / $tipo_cambio, 2) }}
                                @else
                                    {{ $material->precio_estimado }}
                                @endif
                            @endif
                        </th>
                        <th class="text-right">{{ round($material->getImporte($tipo_cambio), 2) }}</th>
                        <th>{{ $material->material->descripcion }}</th>
                        <th class="text-right">{{ $material->cantidad_comparativa }}</th>
                        <th class="text-right">
                            @if ($material->moneda)
                                @if ($material->moneda->esLocal())
                                    {{ round($material->precio_comparativa / $tipo_cambio, 2) }}
                                @else
                                    {{ $material->precio_comparativa }}
                                @endif
                            @endif
                        </th>
                        <th class="text-right">{{ round($material->getImporteComparativa($tipo_cambio), 2) }}</th>
                        <th></th>
                    </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr class="info">
                    <th></th>
                    <th></th>
                    <th class="text-right">Total:</th>
                    <th class="text-right">{{ round($importe_total, 2) }}</th>
                    <th></th>
                    <th></th>
                    <th class="text-right">Total:</th>
                    <th class="text-right">{{ round($importe_total_comparativa, 2) }}</th>
                    <th></th>
                </tr>
            </tfoot>
        </table>
    </dv>
@stop