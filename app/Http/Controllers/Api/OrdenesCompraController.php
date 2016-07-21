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
            'materiales' => $this->transformaItems($compra->items->sortBy('material.descripcion')),
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
                'areas_destino' => [],
                'recibiendo' => false
            ];
        }

        return $materiales;
    }
    
    public function getOc(Request $request) {
        if(null !== ($request->input('depdrop_parents'))) {
            $ids = $request->input('depdrop_parents');
            $proveedor_id = empty($ids[0]) ? null : $ids[0];
            $articulo_id = empty($ids[1]) ? null : $ids[1];
//            if ($proveedor_id != null) {
                $data = Transaccion::ordenesCompraMateriales()
                    ->join("items", "transacciones.id_transaccion", "=", "items.id_transaccion")
                    ->join("materiales", "items.id_material", "=", "materiales.id_material")
                    ->where("id_obra", \Context::getId())
                    ->where("id_empresa", "LIKE", $proveedor_id)
                    ->where("materiales.id_material", "LIKE", $articulo_id)
                    ->groupBy('transacciones.id_transaccion', 'numero_folio')
                    ->select('transacciones.id_transaccion', 'numero_folio')
                    ->orderBy('numero_folio', 'DESC')
                    ->get();
                $out = [];
                foreach($data as $d) {
                    $out [] = [
                        'id' => $d->id_transaccion,
                        'name' => $d->numero_folio
                    ];
                }
                $selected = $data[0]->id_transaccion;
                return response()->json(['output' => $out, 'selected' => '']);
//            }
        }
        return response()->json(['output'=>'', 'selected'=>'']);    
    }
}
