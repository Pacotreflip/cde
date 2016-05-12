<?php

namespace Ghi\Http\Controllers\Api;

use Ghi\Http\Requests;
use Illuminate\Http\Request;
use Ghi\Equipamiento\Areas\Area;
use Ghi\Equipamiento\Areas\Areas;
use Ghi\Http\Controllers\Api\ApiController;

class AreasController extends ApiController
{
    protected $areas;

    /**
     * AreasController constructor.
     *
     * @param Areas $areas
     */
    public function __construct(Areas $areas)
    {
        $this->middleware('auth');
        $this->middleware('context');

        $this->areas = $areas;
        parent::__construct();
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $areas = [];

        foreach ($this->areas->getAll() as $area) {
            $areas[] = [
                'id' => $area->id,
                'nombre' => $area->nombre,
                'depth' => $area->depth,
            ];
        }

        return response()->json($areas);
    }

    /**
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        $area = Area::with('materiales')->findOrFail($id);

        return response()->json($this->transformArea($area));
    }

    /**
     * @param Area $area
     * @return array
     */
    protected function transformArea(Area $area)
    {
        return [
            'id'          => $area->id,
            'clave'       => $area->clave,
            'nombre'      => $area->nombre,
            'ruta'        => $area->ruta(),
            'materiales'  => $this->includeMateriales($area->materiales, $area->id),
            //'materiales_asignados'  => $this->includeMaterialesAsignados($area->materiales_asignados),
        ];
    }

    /**
     * @param $materiales
     * @return array
     */
    protected function includeMateriales($materiales, $id_area)
    {
        $recursos = [];

        $materiales->map(function ($material) use (&$recursos, $id_area) {
            $recursos[] = $this->transformMaterial($material,$id_area);
        });
        
        return $recursos;
    }
    
    /**
     * @param $materiales
     * @return array
     */
    protected function includeMaterialesAsignados($materiales)
    {
        $recursos = [];

        $materiales->map(function ($material) use (&$recursos) {
            $recursos[] = $this->transformMaterialAsignado($material);
        });
        
        return $recursos;
    }

    /**
     * @param $material
     * @return array
     */
    protected function transformMaterial($material,$id_area)
    {
        return [
            'id' => $material->id_material,
            'id_inventario' => $material->pivot->id,
            'numero_parte' => $material->numero_parte,
            'descripcion' => $material->descripcion,
            'unidad' => $material->unidad,
            'existencia' => $material->pivot->cantidad_existencia,
            'asignados' => $material->cantidad_asignada($id_area),
            'esperados' => $material->cantidad_esperada($id_area),
        ];
    }
    
    /**
     * @param $material
     * @return array
     */
    protected function transformMaterialAsignado($material)
    {
        return [
            'id' => $material->id_material,
            'numero_parte' => $material->numero_parte,
            'descripcion' => $material->descripcion,
            'unidad' => $material->unidad,
            'existencia' => $material->pivot->cantidad_asignada,
        ];
    }
    
    
}
