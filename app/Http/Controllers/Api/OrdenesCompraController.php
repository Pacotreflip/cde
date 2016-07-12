<?php

namespace Ghi\Http\Controllers\Api;

use Ghi\Http\Requests;
use Illuminate\Http\Request;
use Ghi\Http\Controllers\Api\ApiController;
use Ghi\Equipamiento\Transacciones\Transaccion;

class OrdenesCompraController extends ApiController
{
    /**
     * Display a listing of the resource.
     *
     * @param $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $compra = Transaccion::ordenesCompraMateriales()
            ->with('empresa')
            ->with('items.material')
            ->findOrFail($id);

        return response()->json([
            'id' => $compra->id_transaccion,
            'numero_folio' => $compra->numero_folio,
            'proveedor' => $compra->empresa,
            'materiales' => $this->transformaItems($compra->items),
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
                'id' => $item->id_material,
                'id_item' => $item->id_item,
                'descripcion' => $item->material->descripcion,
                'unidad' => $item->material->unidad,
                'numero_parte' => $item->material->numero_parte,
                'cantidad_adquirida' => $item->cantidad,
                'precio_unitario' => $item->precio_unitario,
                'cantidad_recibida' => $item->cantidad_recibida,
                'cantidad_por_recibir' => $item->cantidad_por_recibir,
                'areas_destino' => []
            ];
        }

        return $materiales;
    }
}
