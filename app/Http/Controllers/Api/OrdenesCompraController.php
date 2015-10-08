<?php

namespace Ghi\Http\Controllers\Api;

use Ghi\Http\Requests;
use Illuminate\Http\Request;
use Ghi\Http\Controllers\Controller;
use Ghi\Equipamiento\Transacciones\Transaccion;

class OrdenesCompraController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $compra = Transaccion::ordenesCompraMateriales()
            ->with('empresa')
            ->with('items.material')
            ->findOrFail($id);

        // $recepciones = Ghi\Equipamiento\Recepciones\Recepcion::deOrdenCompra($compra->id_transaccion);

        // $recibidos = collect();
        // $recepciones->each(function ($recepcion, $key) use ($recibidos) {
        //     $recepcion->articulos->each(function ($material) use ($recibidos) {
        //         $recibidos->push($material);
        //     });
        // });
        // $recibidos = $recibidos->groupBy('pivot.id_material');

        return response()->json([
            'id'           => $compra->id_transaccion,
            'numero_folio' => $compra->numero_folio,
            'proveedor'    => $compra->empresa,
            'materiales'   => $this->transformaItems($compra->items),
        ]);
    }

    /**
     * Transforma los items de la orden de compra para la respuesta.
     * 
     * @param Illuminate\Database\Eloquent\Collection $items
     * 
     * @return array
     */
    protected function transformaItems($items)
    {
        $materiales = [];

        foreach ($items as $item) {
            $materiales[] = [
                'id'                 => $item->id_material,
                'descripcion'        => $item->material->descripcion,
                'unidad'             => $item->material->unidad,
                'numero_parte'       => $item->material->numero_parte,
                'cantidad_adquirida' => $item->cantidad,
                'precio_unitario'    => $item->precio_unitario,
                'cantidad_recibida'  => $item->cantidad_recibida,
                'cantidad_recibir'   => '',
            ];
        }

        return $materiales;
    }
}
