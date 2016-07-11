<?php

namespace Ghi\Http\Controllers\Api;

use Ghi\Http\Requests;
use Illuminate\Http\Request;
use Ghi\Http\Controllers\Api\ApiController;
use Ghi\Equipamiento\Transacciones\Transaccion;
use Illuminate\Support\Facades\DB;

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
                'areas_destino' => $this->getDestinos($item->id_material)
            ];
        }

        return $materiales;
    }
    
    protected function getDestinos($id_articulo)
    {        
        $destinos = \Ghi\Equipamiento\Areas\Area::with('materialesRequeridos')
                ->whereHas('materialesRequeridos', function($query) use ($id_articulo) {
                    $query->where('id_material', '=', $id_articulo);               
                })
                ->get()
                ;
   
        $areasDestino = [];

        foreach ($destinos as $destino) {
            if($this->requerida($destino->id, $id_articulo) - $this->asignada($destino->id, $id_articulo) > 0)
            {
                $areasDestino[] = [
                    'id'                    => $destino->id,
                    'nombre'                => $destino->nombre,
                    'ruta'                  => $destino->ruta(),
                    'cantidad'              => $this->requerida($destino->id, $id_articulo) - $this->asignada($destino->id, $id_articulo),
                    'recibe'                => null
                ];
            }
        }
        return ($areasDestino);
        
    }
    
    protected function requerida($id_area, $id_material) {
        return DB::connection('cadeco')
                    ->table('Equipamiento.materiales_requeridos_area')
                    ->where('id_material', $id_material)
                    ->where('id_area', $id_area)
                    ->sum('cantidad_requerida');
    }
    
    protected function asignada($id_area, $id_material) {
        return DB::connection('cadeco')
                    ->table('Equipamiento.asignacion_items')
                    ->where('id_material', $id_material)
                    ->where('id_area_destino', $id_area)
                    ->sum('cantidad_asignada');
    }
}
